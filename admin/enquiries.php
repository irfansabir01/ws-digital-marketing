<?php
require_once __DIR__ . '/includes/auth.php';
require_role(['admin']);
require_once __DIR__ . '/includes/enquiry-admin.php';
$page_title = 'Enquiries';
$pdo = get_db();
$ready = enquiry_ensure_table($pdo);

// Quick status change from the list.
if ($ready && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int) ($_POST['id'] ?? 0);
    $new = (string) ($_POST['status'] ?? '');
    if ($id && isset(ENQUIRY_STATUSES[$new])) {
        $pdo->prepare('UPDATE enquiries SET status = ? WHERE id = ?')->execute([$new, $id]);
    }
    // Only ever send the user back to this same list page.
    $back = (string) ($_POST['back'] ?? '');
    header('Location: ' . (strpos($back, 'enquiries.php') === 0 && !preg_match('/[\r\n]/', $back) ? $back : 'enquiries.php'));
    exit;
}

$status = (string) ($_GET['status'] ?? 'active');
if ($status !== 'active' && $status !== 'all' && !isset(ENQUIRY_STATUSES[$status])) {
    $status = 'active';
}
$source = (string) ($_GET['source'] ?? '');
$search = trim((string) ($_GET['q'] ?? ''));
$perPage = 25;
$pageNo = max(1, (int) ($_GET['p'] ?? 1));

$rows = [];
$total = 0;
$counts = array_fill_keys(array_keys(ENQUIRY_STATUSES), 0);
$sources = [];
if ($ready) {
    [$whereSql, $args] = enquiry_filter_sql($status, $source, $search);
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM enquiries ' . $whereSql);
    $stmt->execute($args);
    $total = (int) $stmt->fetchColumn();
    $pages = max(1, (int) ceil($total / $perPage));
    $pageNo = min($pageNo, $pages);
    $stmt = $pdo->prepare('SELECT * FROM enquiries ' . $whereSql . ' ORDER BY created_at DESC, id DESC LIMIT ' . $perPage . ' OFFSET ' . (($pageNo - 1) * $perPage));
    $stmt->execute($args);
    $rows = $stmt->fetchAll();
    foreach ($pdo->query('SELECT status, COUNT(*) c FROM enquiries GROUP BY status') as $c) {
        $counts[$c['status']] = (int) $c['c'];
    }
    $sources = $pdo->query("SELECT DISTINCT source FROM enquiries WHERE source <> '' ORDER BY source")->fetchAll(PDO::FETCH_COLUMN);
}
$allCount = array_sum($counts);
$activeCount = $allCount - $counts['spam'];

$qs = function (array $over = []) use ($status, $source, $search) {
    return '?' . http_build_query(array_filter(array_merge(['status' => $status, 'source' => $source, 'q' => $search], $over), fn($v) => $v !== '' && $v !== null));
};
$back = 'enquiries.php' . $qs(['p' => $pageNo > 1 ? $pageNo : null]);

include __DIR__ . '/includes/layout-header.php';
flash_render();
?>
<?php if (!$ready): ?>
    <div class="alert alert-error">The enquiries table couldn't be created automatically. Run <code>enquiries-migration.sql</code> in phpMyAdmin (SQL tab).</div>
<?php else: ?>
<div class="help-box">
    Every enquiry from the contact page and the "Ready To...?" forms on other pages lands here (as well as in your inbox).
    Open one to set its status, add follow-up notes and record the deal value when you win it.
</div>

<div class="tabs">
    <a href="<?php echo h($qs(['status' => 'active', 'p' => null])); ?>" class="<?php echo $status === 'active' ? 'active' : ''; ?>">Active (<?php echo $activeCount; ?>)</a>
    <?php foreach (ENQUIRY_STATUSES as $key => $label): ?>
        <a href="<?php echo h($qs(['status' => $key, 'p' => null])); ?>" class="<?php echo $status === $key ? 'active' : ''; ?>"><?php echo h($label); ?> (<?php echo $counts[$key]; ?>)</a>
    <?php endforeach; ?>
    <a href="<?php echo h($qs(['status' => 'all', 'p' => null])); ?>" class="<?php echo $status === 'all' ? 'active' : ''; ?>">All (<?php echo $allCount; ?>)</a>
</div>

<div class="filter-bar">
    <form method="get" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <input type="hidden" name="status" value="<?php echo h($status); ?>">
        <input type="text" name="q" value="<?php echo h($search); ?>" placeholder="Search name, email, phone, message...">
        <select name="source">
            <option value="">All sources</option>
            <?php foreach ($sources as $s): ?>
                <option value="<?php echo h($s); ?>" <?php echo $source === $s ? 'selected' : ''; ?>><?php echo h(enquiry_source_label($s)); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
    </form>
    <a class="btn btn-secondary btn-sm" style="margin-left:auto;" href="enquiries-export.php<?php echo h($qs(['p' => null])); ?>">Export CSV</a>
</div>

<div class="table-scroll">
<table class="admin-table">
    <thead><tr><th>Received</th><th>Contact</th><th>Interested in</th><th>Message</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php if (!$rows): ?><tr><td colspan="6"><?php echo $allCount === 0 ? 'No enquiries yet. They will appear here as soon as someone submits a form.' : 'Nothing matches these filters.'; ?></td></tr><?php endif; ?>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td class="muted" style="white-space:nowrap;"><?php echo h(substr($r['created_at'], 0, 16)); ?>
                <?php if (!(int) $r['email_sent']): ?><br><span class="badge badge-bad" title="The notification email to info@ did not send, so this record is the only copy.">email failed</span><?php endif; ?></td>
            <td>
                <strong><a href="enquiry-view.php?id=<?php echo (int) $r['id']; ?>"><?php echo h($r['name']); ?></a></strong><br>
                <a href="mailto:<?php echo h($r['email']); ?>"><?php echo h($r['email']); ?></a>
                <?php if ($r['phone'] !== ''): ?><br><a href="tel:<?php echo h(preg_replace('/[^0-9+]/', '', $r['phone'])); ?>"><?php echo h($r['phone']); ?></a><?php endif; ?>
            </td>
            <td><?php echo h($r['service'] !== '' ? $r['service'] : '-'); ?>
                <?php if ($r['plan'] !== ''): ?><br><span class="badge badge-info"><?php echo h($r['plan']); ?> plan</span><?php endif; ?>
                <br><span class="muted" style="font-size:0.78rem;"><?php echo h(enquiry_source_label($r['source'])); ?><?php echo $r['page'] !== '' && $r['page'] !== '/contact' ? ' &middot; ' . h($r['page']) : ''; ?></span></td>
            <td class="clip" style="max-width:320px;"><?php $m = (string) $r['message']; echo h(mb_strlen($m) > 140 ? mb_substr($m, 0, 140) . '...' : $m); ?></td>
            <td>
                <form method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo (int) $r['id']; ?>">
                    <input type="hidden" name="back" value="<?php echo h($back); ?>">
                    <select name="status" onchange="this.form.submit()" style="padding:6px; border:1px solid var(--border); border-radius:6px;">
                        <?php foreach (ENQUIRY_STATUSES as $key => $label): ?>
                            <option value="<?php echo h($key); ?>" <?php echo $r['status'] === $key ? 'selected' : ''; ?>><?php echo h($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <noscript><button type="submit" class="btn btn-secondary btn-sm">Save</button></noscript>
                </form>
            </td>
            <td><a href="enquiry-view.php?id=<?php echo (int) $r['id']; ?>" class="btn btn-secondary btn-sm">Open</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php if ($total > $perPage): ?>
<div class="tabs" style="margin-top:18px;">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="<?php echo h($qs(['p' => $i > 1 ? $i : null])); ?>" class="<?php echo $i === $pageNo ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>
<?php endif; ?>
<?php include __DIR__ . '/includes/layout-footer.php'; ?>
