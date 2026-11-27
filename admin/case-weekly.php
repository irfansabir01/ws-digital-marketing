<?php
// Weekly Stats for one case study: add this week's numbers, edit or delete
// past weeks, and see the running total. Everything here feeds the case
// study page, the service pages and the homepage combined stats live (see
// includes/weekly-stats.php).
require_once __DIR__ . '/includes/auth.php';
require_role(['admin']);
require_once __DIR__ . '/../includes/case-study-helpers.php';
$pdo = get_db();

$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($_POST['id'] ?? 0);
$stmt = $pdo->prepare('SELECT id, name FROM case_studies WHERE id = ?');
$stmt->execute([$id]);
$study = $stmt->fetch();
if (!$study) {
    header('Location: case-list.php');
    exit;
}

try {
    $pdo->query('SELECT id FROM case_study_weekly_stats LIMIT 1');
    $tableReady = true;
    weekly_stats_ensure_columns($pdo);
} catch (PDOException $e) {
    $tableReady = false;
}

$error = '';
$self = 'case-weekly.php?id=' . (int) $id;
$metricCols = array_keys(WEEKLY_METRICS);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'setup') {
        $added = weekly_stats_setup($pdo);
        header('Location: ' . $self . '&setup=' . $added);
        exit;
    }

    if ($tableReady && $action === 'delete') {
        $pdo->prepare('DELETE FROM case_study_weekly_stats WHERE id = ? AND case_study_id = ?')->execute([(int) ($_POST['row_id'] ?? 0), $id]);
        header('Location: ' . $self . '&deleted=1');
        exit;
    }

    if ($tableReady && $action === 'save') {
        $rowId = (int) ($_POST['row_id'] ?? 0);
        $isBaseline = false;
        if ($rowId) {
            $check = $pdo->prepare('SELECT is_baseline FROM case_study_weekly_stats WHERE id = ? AND case_study_id = ?');
            $check->execute([$rowId, $id]);
            $existingRow = $check->fetch();
            if (!$existingRow) {
                header('Location: ' . $self);
                exit;
            }
            $isBaseline = (int) $existingRow['is_baseline'] === 1;
        }

        $start = trim($_POST['week_start'] ?? '');
        $end = trim($_POST['week_end'] ?? '');
        $label = trim($_POST['label'] ?? '');
        $values = [];
        foreach ($metricCols as $metric) {
            $values[] = parse_weekly_number($_POST[$metric] ?? '');
        }

        if (!$isBaseline && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) {
            $error = 'Please choose the week start date.';
        } elseif (count(array_filter($values, fn($v) => $v !== null)) === 0) {
            $error = 'Please enter at least one number.';
        } else {
            if (!$isBaseline && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) {
                $end = date('Y-m-d', strtotime($start . ' +6 days'));
            }
            // Optional screenshots: proof for this week's Search Console / Business
            // Profile numbers (see weekly_sync_case_sections()).
            $gscUp = $gbpUp = null;
            if (!$isBaseline) {
                try {
                    $gscUp = handle_image_upload('gsc_image_file', 'images/case-studies', $study['name'] . '-gsc-' . $start);
                    $gbpUp = handle_image_upload('gbp_image_file', 'images/case-studies', $study['name'] . '-gbp-' . $start);
                } catch (RuntimeException $e) {
                    $error = $e->getMessage();
                }
            }
            if ($error === '') {
                if ($isBaseline) {
                    $sql = 'UPDATE case_study_weekly_stats SET label = ?, ' . implode(' = ?, ', $metricCols) . ' = ? WHERE id = ?';
                    $pdo->prepare($sql)->execute([mb_substr($label ?: 'Before weekly tracking', 0, 100), ...$values, $rowId]);
                } elseif ($rowId) {
                    $sql = 'UPDATE case_study_weekly_stats SET week_start = ?, week_end = ?, ' . implode(' = ?, ', $metricCols) . ' = ? WHERE id = ?';
                    $pdo->prepare($sql)->execute([$start, $end, ...$values, $rowId]);
                } else {
                    $sql = 'INSERT INTO case_study_weekly_stats (case_study_id, is_baseline, label, week_start, week_end, ' . implode(', ', $metricCols) . ') VALUES (?, 0, \'\', ?, ?, ' . implode(', ', array_fill(0, count($metricCols), '?')) . ')';
                    $pdo->prepare($sql)->execute([$id, $start, $end, ...$values]);
                    $rowId = (int) $pdo->lastInsertId();
                }
                if (!$isBaseline) {
                    // Optional CTR / position typed for the week: used as typed,
                    // blank = worked out from clicks and impressions.
                    weekly_store_ctr_position($pdo, $rowId, parse_weekly_number($_POST['gsc_ctr'] ?? ''), parse_weekly_number($_POST['gsc_position'] ?? ''), true);
                }
                $synced = false;
                if (!$isBaseline && ($gscUp !== null || $gbpUp !== null) && weekly_is_latest_week($pdo, $id, $start)) {
                    weekly_sync_case_sections($pdo, $id, array_combine($metricCols, $values), $_POST, $gscUp, $gbpUp);
                    $synced = true;
                }
                header('Location: ' . $self . '&saved=1' . ($synced ? '&synced=1' : ''));
                exit;
            }
        }
    }
}

$rows = [];
if ($tableReady) {
    $stmt = $pdo->prepare('SELECT * FROM case_study_weekly_stats WHERE case_study_id = ? ORDER BY is_baseline DESC, week_start ASC, id ASC');
    $stmt->execute([$id]);
    $rows = $stmt->fetchAll();
}
$summary = summarise_weekly_stats($rows);

// The row being edited (?edit=<row id>), or a blank new week starting the
// day after the latest one.
$editing = null;
if (isset($_GET['edit'])) {
    foreach ($rows as $row) {
        if ((int) $row['id'] === (int) $_GET['edit']) {
            $editing = $row;
        }
    }
}
$nextStart = date('Y-m-d', strtotime('monday last week'));
if ($summary && $summary['latest']) {
    $nextStart = date('Y-m-d', strtotime(($summary['latest']['week_end'] ?: $summary['latest']['week_start']) . ' +1 day'));
}
$form = $editing ?? ['id' => 0, 'is_baseline' => 0, 'label' => '', 'week_start' => $nextStart, 'week_end' => date('Y-m-d', strtotime($nextStart . ' +6 days'))];
if ($error) {
    $form = array_merge($form, $_POST);
}

// Plain number for a form field (no commas or K suffix, so it saves back
// exactly as shown).
function weekly_input_value($v): string
{
    if ($v === null || $v === '') {
        return '';
    }
    $f = (float) $v;
    return $f == floor($f) ? (string) (int) $f : rtrim(rtrim(number_format($f, 2, '.', ''), '0'), '.');
}

$page_title = 'Weekly Stats: ' . $study['name'];
include __DIR__ . '/includes/layout-header.php';
?>
<div class="page-actions">
    <a href="case-list.php" class="btn btn-secondary btn-sm">&larr; All Case Studies</a>
    <a href="case-form.php?id=<?php echo (int) $id; ?>" class="btn btn-secondary btn-sm">Edit Case Study</a>
</div>

<?php if (isset($_GET['setup'])): ?><div class="alert alert-success">Weekly stats are set up. Starting entries added from current stats: <?php echo (int) $_GET['setup']; ?>.</div><?php endif; ?>
<?php if (!empty($_GET['saved'])): ?><div class="alert alert-success">Week saved. The website is already showing the new numbers.<?php echo !empty($_GET['synced']) ? ' The screenshot and numbers were also put in the case study\'s Search Console / Business Profile section.' : ''; ?></div><?php endif; ?>
<?php if (!empty($_GET['deleted'])): ?><div class="alert alert-success">Week deleted.</div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>

<?php if (!$tableReady): ?>
<div class="admin-card">
    <p style="margin-bottom:12px;"><strong>Weekly stats are not set up yet.</strong></p>
    <p class="hint" style="margin-bottom:16px;">Click once to turn them on. Every case study's current stats are saved as its starting entry, so totals continue from today's numbers. This only needs doing once, for all case studies.</p>
    <form method="post">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo (int) $id; ?>">
        <input type="hidden" name="action" value="setup">
        <button type="submit" class="btn btn-primary">Set Up Weekly Stats</button>
    </form>
</div>
<?php else: ?>

<?php if ($summary): ?>
<div class="dashboard-grid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:14px; margin-bottom:20px;">
    <?php foreach (weekly_stat_boxes($summary['totals']) as [$value, $label]): ?>
    <div class="admin-card" style="margin:0;"><div class="num" style="font-size:1.6rem;"><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></div><div class="label">Total <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></div></div>
    <?php endforeach; ?>
</div>
<p class="hint" style="margin:-8px 0 20px; color:#5A6482; font-size:0.85rem;">Total period: <?php echo htmlspecialchars($summary['period'], ENT_QUOTES, 'UTF-8'); ?><?php if ($summary['latest']): ?> &middot; Latest week: <?php echo htmlspecialchars($summary['latest_period'], ENT_QUOTES, 'UTF-8'); ?><?php endif; ?></p>
<?php endif; ?>

<div class="admin-card" id="weekly-form"<?php echo $editing ? ' style="border:2px solid #00C2CB; background:#F0FDFF; scroll-margin-top:20px;"' : ''; ?>>
    <h2 style="font-size:1.1rem; margin-bottom:14px;"><?php
        if (!$editing) {
            echo 'Add This Week';
        } elseif ((int) $form['is_baseline']) {
            echo 'Editing: Starting Entry';
        } else {
            echo 'Editing: Week of ' . htmlspecialchars(format_week_range($editing['week_start'], $editing['week_end']), ENT_QUOTES, 'UTF-8');
        }
    ?></h2>
    <?php if ($editing): ?><div class="hint" style="margin:-6px 0 14px;">Change the numbers below and click "Save Changes". The website updates straight away.</div><?php endif; ?>
    <form method="post" class="admin-form" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo (int) $id; ?>">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="row_id" value="<?php echo (int) ($form['id'] ?? 0); ?>">

        <?php if ((int) ($form['is_baseline'] ?? 0)): ?>
            <label for="label">Starting entry name (shown as the start of the total period, e.g. "May – Sept 2026")</label>
            <input type="text" id="label" name="label" value="<?php echo htmlspecialchars($form['label'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <?php else: ?>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <div style="flex:1; min-width:180px;"><label for="week_start">Week start</label><input type="date" id="week_start" name="week_start" required value="<?php echo htmlspecialchars($form['week_start'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
                <div style="flex:1; min-width:180px;"><label for="week_end">Week end</label><input type="date" id="week_end" name="week_end" value="<?php echo htmlspecialchars($form['week_end'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></div>
            </div>
            <div class="hint">Leave "Week end" blank to use 7 days from the start.</div>
        <?php endif; ?>

        <?php
        $isBase = (int) ($form['is_baseline'] ?? 0) === 1;
        $metricField = function (string $metric) use ($form, $error): void {
            $label = WEEKLY_METRICS[$metric];
            ?>
            <div>
                <label for="<?php echo $metric; ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?><?php echo $metric === 'shop_sales' ? ' (A$)' : ''; ?></label>
                <input type="text" inputmode="decimal" id="<?php echo $metric; ?>" name="<?php echo $metric; ?>" value="<?php echo htmlspecialchars($error ? (string) ($form[$metric] ?? '') : weekly_input_value($form[$metric] ?? null), ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <?php
        };
        $groupStyle = 'display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:10px; margin-top:8px;';
        ?>
        <h3 style="font-size:0.95rem; margin:18px 0 0;">Search Console</h3>
        <div style="<?php echo $groupStyle; ?>">
            <?php $metricField('organic_clicks'); $metricField('search_impressions'); ?>
            <?php if (!$isBase): ?>
            <div><label for="gsc_ctr">Average CTR % (optional)</label><input type="text" inputmode="decimal" id="gsc_ctr" name="gsc_ctr" value="<?php echo htmlspecialchars($error ? (string) ($form['gsc_ctr'] ?? '') : weekly_input_value($form['gsc_ctr'] ?? null), ENT_QUOTES, 'UTF-8'); ?>" placeholder="blank = worked out from clicks / impressions"></div>
            <div><label for="gsc_position">Average position (optional)</label><input type="text" inputmode="decimal" id="gsc_position" name="gsc_position" value="<?php echo htmlspecialchars($error ? (string) ($form['gsc_position'] ?? '') : weekly_input_value($form['gsc_position'] ?? null), ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 10.4"></div>
            <?php endif; ?>
        </div>
        <?php if (!$isBase): ?>
        <label for="gsc_image_file" style="margin-top:10px;">Search Console screenshot (optional)</label>
        <input type="file" id="gsc_image_file" name="gsc_image_file" accept="image/png,image/jpeg,image/webp">
        <div class="hint">Upload a cropped Performance screenshot (filter bar, the boxes and the chart). If this is the latest week, it also replaces the numbers and screenshot in the case study's "Google Search Console Performance" section.</div>
        <?php endif; ?>

        <h3 style="font-size:0.95rem; margin:22px 0 0;">Google Business Profile</h3>
        <div style="<?php echo $groupStyle; ?>">
            <?php foreach (['gbp_interactions', 'gbp_calls', 'gbp_directions', 'gbp_website_clicks', 'gbp_chat_clicks'] as $m) { $metricField($m); } ?>
        </div>
        <?php if (!$isBase): ?>
        <label for="gbp_image_file" style="margin-top:10px;">Business Profile screenshot (optional)</label>
        <input type="file" id="gbp_image_file" name="gbp_image_file" accept="image/png,image/jpeg,image/webp">
        <div class="hint">If this is the latest week, it also replaces the interactions, calls, directions and website clicks (the ones you filled in) and the screenshot in the "Google Business Profile Performance" section.</div>
        <?php endif; ?>

        <h3 style="font-size:0.95rem; margin:22px 0 0;">Online store (Shopify / WooCommerce)</h3>
        <div style="<?php echo $groupStyle; ?>">
            <?php foreach (['shop_sales', 'shop_orders', 'shop_sessions'] as $m) { $metricField($m); } ?>
        </div>
        <div class="hint">Leave a box blank if it isn't tracked for this client. "1,998", "7.2K" and "A$1,250" all work. Average CTR is worked out automatically from clicks and impressions. Every number you enter is added to the case study's total, which also feeds the homepage and service pages.</div>

        <button type="submit" class="btn btn-primary" style="margin-top:16px;"><?php echo $editing ? 'Save Changes' : 'Add Week'; ?></button>
        <?php if ($editing): ?><a href="<?php echo htmlspecialchars($self, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-secondary" style="margin-top:16px;">Cancel</a><?php endif; ?>
    </form>
</div>

<div style="overflow-x:auto;">
<table class="admin-table">
    <thead>
        <tr>
            <th>Period</th>
            <?php foreach (WEEKLY_METRICS as $label): ?><th><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></th><?php endforeach; ?>
            <th>CTR</th>
            <th>Position</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!$rows): ?>
            <tr><td colspan="<?php echo count(WEEKLY_METRICS) + 4; ?>">No weekly stats yet. Add the first week above.</td></tr>
        <?php endif; ?>
        <?php foreach ($rows as $row): ?>
        <tr<?php echo $summary && $summary['latest'] && (int) $summary['latest']['id'] === (int) $row['id'] ? ' style="background:#F0FDFF;"' : ''; ?>>
            <td><?php echo (int) $row['is_baseline'] ? '<strong>Starting entry</strong><br>' . htmlspecialchars($row['label'], ENT_QUOTES, 'UTF-8') : htmlspecialchars(format_week_range($row['week_start'], $row['week_end']), ENT_QUOTES, 'UTF-8'); ?></td>
            <?php foreach ($metricCols as $metric): ?>
            <td><?php echo ($row[$metric] ?? null) === null || $row[$metric] === '' ? '&ndash;' : htmlspecialchars(format_weekly_value($metric, (float) $row[$metric]), ENT_QUOTES, 'UTF-8'); ?></td>
            <?php endforeach; ?>
            <td><?php $rowCtr = weekly_row_ctr($row); echo $rowCtr === null ? '&ndash;' : htmlspecialchars(weekly_percent($rowCtr), ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo ($row['gsc_position'] ?? null) === null || $row['gsc_position'] === '' ? '&ndash;' : htmlspecialchars(weekly_input_value($row['gsc_position']), ENT_QUOTES, 'UTF-8'); ?></td>
            <td style="white-space:nowrap;">
                <a href="<?php echo htmlspecialchars($self . '&edit=' . (int) $row['id'] . '#weekly-form', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-secondary btn-sm">Edit</a>
                <form method="post" style="display:inline;" onsubmit="return confirm('Delete this entry? The totals on the website will go down by these numbers.');">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo (int) $id; ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="row_id" value="<?php echo (int) $row['id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if ($summary): ?>
        <tr style="background:#F9FAFC; font-weight:700;">
            <td>Total</td>
            <?php foreach ($metricCols as $metric): ?>
            <td><?php echo $summary['totals'][$metric] === null ? '&ndash;' : htmlspecialchars(format_weekly_value($metric, $summary['totals'][$metric]), ENT_QUOTES, 'UTF-8'); ?></td>
            <?php endforeach; ?>
            <td><?php echo $summary['totals']['_ctr'] === null ? '&ndash;' : htmlspecialchars(weekly_percent($summary['totals']['_ctr']), ENT_QUOTES, 'UTF-8'); ?></td>
            <td></td>
            <td></td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/layout-footer.php'; ?>
