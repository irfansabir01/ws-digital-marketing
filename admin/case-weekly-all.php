<?php
// Weekly Update for every case study on one page: choose the week once, then
// type each client's Search Console / Business Profile / store numbers (and
// optionally upload their screenshots). Each number is saved as that week's
// entry and added to the case study's total, the service pages and the
// homepage combined stats -- exactly like the per-case-study Weekly Stats
// page, just faster for a whole round of reports.
require_once __DIR__ . '/includes/auth.php';
require_role(['admin']);
require_once __DIR__ . '/../includes/case-study-helpers.php';
$pdo = get_db();

try {
    $pdo->query('SELECT id FROM case_study_weekly_stats LIMIT 1');
    $tableReady = true;
    weekly_stats_ensure_columns($pdo);
} catch (PDOException $e) {
    $tableReady = false;
}

$studies = $pdo->query('SELECT id, name FROM case_studies ORDER BY display_order ASC, name ASC')->fetchAll();
$hasBaseline = [];
$latestWeek = [];
if ($tableReady) {
    foreach ($pdo->query('SELECT case_study_id, MAX(is_baseline) AS b, MAX(week_end) AS last_end FROM case_study_weekly_stats GROUP BY case_study_id')->fetchAll() as $r) {
        if ((int) $r['b'] === 1) {
            $hasBaseline[(int) $r['case_study_id']] = true;
        }
        $latestWeek[(int) $r['case_study_id']] = $r['last_end'];
    }
}

$error = '';
$messages = [];
$metricCols = array_keys(WEEKLY_METRICS);

// One entry of a multi-file input (name="x[<id>]") as a plain $_FILES-style array.
function nested_upload(string $field, int $id): array
{
    $f = $_FILES[$field] ?? null;
    if (!$f || !isset($f['error'][$id])) {
        return ['error' => UPLOAD_ERR_NO_FILE];
    }
    return ['name' => $f['name'][$id], 'tmp_name' => $f['tmp_name'][$id], 'error' => $f['error'][$id], 'size' => $f['size'][$id]];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (($_POST['action'] ?? '') === 'setup') {
        $added = weekly_stats_setup($pdo);
        header('Location: case-weekly-all.php?setup=' . $added);
        exit;
    }
    $start = trim($_POST['week_start'] ?? '');
    $end = trim($_POST['week_end'] ?? '');
    if (!$tableReady) {
        $error = 'Weekly stats are not set up yet.';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) {
        $error = 'Please choose the week start date.';
    } else {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) {
            $end = date('Y-m-d', strtotime($start . ' +6 days'));
        }
        $saved = $updated = 0;
        foreach ($studies as $study) {
            $sid = (int) $study['id'];
            $in = $_POST['s'][$sid] ?? [];
            $values = [];
            foreach ($metricCols as $metric) {
                $values[$metric] = parse_weekly_number($in[$metric] ?? '');
            }
            $hasNumbers = count(array_filter($values, fn($v) => $v !== null)) > 0;
            $gscFile = nested_upload('gsc_image', $sid);
            $gbpFile = nested_upload('gbp_image', $sid);
            $hasFiles = $gscFile['error'] !== UPLOAD_ERR_NO_FILE || $gbpFile['error'] !== UPLOAD_ERR_NO_FILE;
            if (!$hasNumbers && !$hasFiles) {
                continue;
            }
            if (empty($hasBaseline[$sid])) {
                $messages[] = [false, $study['name'] . ': skipped, set up weekly stats first (button above).'];
                continue;
            }
            if (!$hasNumbers) {
                $messages[] = [false, $study['name'] . ': a screenshot was chosen but no numbers were typed, so nothing was saved.'];
                continue;
            }
            try {
                $gscUp = store_uploaded_image($gscFile, 'images/case-studies', $study['name'] . '-gsc-' . $start);
                $gbpUp = store_uploaded_image($gbpFile, 'images/case-studies', $study['name'] . '-gbp-' . $start);
            } catch (RuntimeException $e) {
                $messages[] = [false, $study['name'] . ': ' . $e->getMessage() . ' Nothing was saved for this one.'];
                continue;
            }
            $result = weekly_save_week_merge($pdo, $sid, $start, $end, $values, parse_weekly_number($in['gsc_ctr'] ?? ''), parse_weekly_number($in['gsc_position'] ?? ''));
            $result === 'inserted' ? $saved++ : $updated++;
            if (($gscUp !== null || $gbpUp !== null) && weekly_is_latest_week($pdo, $sid, $start)) {
                weekly_sync_case_sections($pdo, $sid, $values, $in, $gscUp, $gbpUp);
            }
            $messages[] = [true, $study['name'] . ': ' . ($result === 'inserted' ? 'week added' : 'week updated') . (($gscUp || $gbpUp) ? ', screenshot' . (($gscUp && $gbpUp) ? 's' : '') . ' saved' : '') . '.'];
        }
        if (!$messages) {
            $error = 'Nothing to save: type at least one number for a case study.';
        }
    }
    // refresh "latest week" after saving
    if ($tableReady && !$error) {
        $latestWeek = [];
        foreach ($pdo->query('SELECT case_study_id, MAX(week_end) AS last_end FROM case_study_weekly_stats GROUP BY case_study_id')->fetchAll() as $r) {
            $latestWeek[(int) $r['case_study_id']] = $r['last_end'];
        }
    }
}

$nextStart = date('Y-m-d', strtotime('monday last week'));
if ($tableReady && ($latest = weekly_stats_latest_end())) {
    $nextStart = date('Y-m-d', strtotime($latest . ' +1 day'));
}
$formStart = $_POST['week_start'] ?? $nextStart;
$formEnd = $_POST['week_end'] ?? date('Y-m-d', strtotime($formStart . ' +6 days'));

$h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$page_title = 'Weekly Update: All Case Studies';
include __DIR__ . '/includes/layout-header.php';
?>
<div class="page-actions">
    <a href="case-list.php" class="btn btn-secondary btn-sm">&larr; All Case Studies</a>
</div>

<?php if (isset($_GET['setup'])): ?><div class="alert alert-success">Weekly stats are set up. Starting entries added from current stats: <?php echo (int) $_GET['setup']; ?>.</div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error"><?php echo $h($error); ?></div><?php endif; ?>
<?php foreach ($messages as [$ok, $text]): ?><div class="alert <?php echo $ok ? 'alert-success' : 'alert-error'; ?>"><?php echo $h($text); ?></div><?php endforeach; ?>

<?php if (!$tableReady): ?>
<div class="admin-card">
    <p style="margin-bottom:12px;"><strong>Weekly stats are not set up yet.</strong></p>
    <p class="hint" style="margin-bottom:16px;">Click once to turn them on. Every case study's current stats are saved as its starting entry, so totals continue from today's numbers.</p>
    <form method="post">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="setup">
        <button type="submit" class="btn btn-primary">Set Up Weekly Stats</button>
    </form>
</div>
<?php else: ?>

<?php $missing = array_filter($studies, fn($s) => empty($hasBaseline[(int) $s['id']])); ?>
<?php if ($missing): ?>
<div class="alert alert-warning">
    <?php echo count($missing); ?> case stud<?php echo count($missing) === 1 ? 'y has' : 'ies have'; ?> no starting entry yet (<?php echo $h(implode(', ', array_map(fn($s) => $s['name'], $missing))); ?>).
    <form method="post" style="display:inline; margin-left:8px;"><?php echo csrf_field(); ?><input type="hidden" name="action" value="setup"><button type="submit" class="btn btn-secondary btn-sm">Add starting entries</button></form>
</div>
<?php endif; ?>

<form method="post" class="admin-form" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" value="save">
    <div class="admin-card">
        <h2 style="font-size:1.1rem; margin-bottom:14px;">1. Choose the week</h2>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <div style="flex:1; min-width:180px;"><label for="week_start">Week start</label><input type="date" id="week_start" name="week_start" required value="<?php echo $h($formStart); ?>"></div>
            <div style="flex:1; min-width:180px;"><label for="week_end">Week end</label><input type="date" id="week_end" name="week_end" value="<?php echo $h($formEnd); ?>"></div>
        </div>
        <div class="hint">Use the dates shown on the Search Console chart (e.g. 11/18/26 &ndash; 11/24/26). Leave "Week end" blank for 7 days from the start.</div>
    </div>

    <h2 style="font-size:1.1rem; margin:22px 0 8px;">2. Type each client's numbers</h2>
    <p class="hint" style="margin-bottom:12px;">Open a client, fill in only what you have (blank = not tracked this week), then press Save at the bottom. Everything entered is added to that client's total and to the homepage / service page totals. A screenshot is optional: with the latest week it also refreshes the case study's Search Console or Business Profile section.</p>

    <?php
    $field = function (int $sid, string $metric) use ($h): void { ?>
        <div><label><?php echo $h(WEEKLY_METRICS[$metric] . ($metric === 'shop_sales' ? ' (A$)' : '')); ?></label><input type="text" inputmode="decimal" name="s[<?php echo $sid; ?>][<?php echo $metric; ?>]" value="<?php echo $h($_POST['s'][$sid][$metric] ?? ''); ?>"></div>
    <?php };
    $grid = 'display:grid; grid-template-columns:repeat(auto-fit, minmax(170px, 1fr)); gap:10px; margin-top:8px;';
    foreach ($studies as $study):
        $sid = (int) $study['id'];
        $ready = !empty($hasBaseline[$sid]);
        $touched = !empty(array_filter((array) ($_POST['s'][$sid] ?? [])));
    ?>
    <details class="admin-card" style="margin-bottom:10px;"<?php echo $touched ? ' open' : ''; ?>>
        <summary style="cursor:pointer; font-weight:700;"><?php echo $h($study['name']); ?>
            <span class="hint" style="font-weight:400; margin-left:8px;"><?php
                echo !$ready ? 'no starting entry yet' : ($latestWeek[$sid] ? 'latest week entered: ends ' . date('j M Y', strtotime($latestWeek[$sid])) : 'no weeks entered yet');
            ?></span>
        </summary>
        <?php if (!$ready): ?>
            <p class="hint" style="margin-top:10px;">Add the starting entries (button at the top) before entering weeks for this client.</p>
        <?php else: ?>
        <h3 style="font-size:0.9rem; margin:14px 0 0;">Search Console</h3>
        <div style="<?php echo $grid; ?>">
            <?php $field($sid, 'organic_clicks'); $field($sid, 'search_impressions'); ?>
            <div><label>Average CTR % (optional)</label><input type="text" inputmode="decimal" name="s[<?php echo $sid; ?>][gsc_ctr]" value="<?php echo $h($_POST['s'][$sid]['gsc_ctr'] ?? ''); ?>" placeholder="auto"></div>
            <div><label>Average position (optional)</label><input type="text" inputmode="decimal" name="s[<?php echo $sid; ?>][gsc_position]" value="<?php echo $h($_POST['s'][$sid]['gsc_position'] ?? ''); ?>"></div>
        </div>
        <label style="margin-top:8px;">Search Console screenshot (optional)</label>
        <input type="file" name="gsc_image[<?php echo $sid; ?>]" accept="image/png,image/jpeg,image/webp">

        <h3 style="font-size:0.9rem; margin:16px 0 0;">Google Business Profile</h3>
        <div style="<?php echo $grid; ?>">
            <?php foreach (['gbp_interactions', 'gbp_calls', 'gbp_directions', 'gbp_website_clicks', 'gbp_chat_clicks'] as $m) { $field($sid, $m); } ?>
        </div>
        <label style="margin-top:8px;">Business Profile screenshot (optional)</label>
        <input type="file" name="gbp_image[<?php echo $sid; ?>]" accept="image/png,image/jpeg,image/webp">

        <h3 style="font-size:0.9rem; margin:16px 0 0;">Online store</h3>
        <div style="<?php echo $grid; ?>">
            <?php foreach (['shop_sales', 'shop_orders', 'shop_sessions'] as $m) { $field($sid, $m); } ?>
        </div>
        <p style="margin-top:10px;"><a href="case-weekly.php?id=<?php echo $sid; ?>" class="btn btn-secondary btn-sm">See / edit all weeks</a></p>
        <?php endif; ?>
    </details>
    <?php endforeach; ?>

    <button type="submit" class="btn btn-primary" style="margin-top:14px;">Save This Week</button>
</form>
<?php endif; ?>

<?php include __DIR__ . '/includes/layout-footer.php'; ?>
