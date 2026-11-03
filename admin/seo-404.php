<?php
require_once __DIR__ . '/includes/auth.php';
require_role(['admin', 'seo']);
require_once __DIR__ . '/includes/seo-admin.php';
$page_title = '404 Errors';
seo_require_tables();
$pdo = get_db();
$user = (string) $_SESSION['admin_username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = (string) ($_POST['action'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);
    $stmt = $pdo->prepare('SELECT path FROM not_found_log WHERE id = ?');
    $stmt->execute([$id]);
    $path = $stmt->fetchColumn();

    if ($action === 'redirect' && $path !== false) {
        $code = (int) ($_POST['code'] ?? 301);
        $err = seo_redirect_save($pdo, $path, (string) ($_POST['to'] ?? ''), $code, 'From the 404 report', $user);
        $err ? flash_set('error', $err) : flash_set('success', $path . ($code === 410 ? ' now returns 410 Gone.' : ' now redirects to ' . trim((string) $_POST['to']) . '.'));
    } elseif (in_array($action, ['ignore', 'reopen', 'fixed'], true) && $path !== false) {
        $status = ['ignore' => 'ignored', 'reopen' => 'open', 'fixed' => 'fixed'][$action];
        $pdo->prepare('UPDATE not_found_log SET status = ? WHERE id = ?')->execute([$status, $id]);
    } elseif ($action === 'delete' && $path !== false) {
        $pdo->prepare('DELETE FROM not_found_log WHERE id = ?')->execute([$id]);
    } elseif ($action === 'clear') {
        $pdo->exec("DELETE FROM not_found_log WHERE status IN ('fixed', 'ignored')");
        flash_set('success', 'Cleared all fixed and ignored entries.');
    }
    header('Location: seo-404.php?status=' . rawurlencode((string) ($_GET['status'] ?? 'open')));
    exit;
}

$status = in_array($_GET['status'] ?? 'open', ['open', 'ignored', 'fixed', 'all'], true) ? $_GET['status'] ?? 'open' : 'open';
$search = trim((string) ($_GET['q'] ?? ''));
$where = [];
$args = [];
if ($status !== 'all') {
    $where[] = 'status = ?';
    $args[] = $status;
}
if ($search !== '') {
    $where[] = 'path LIKE ?';
    $args[] = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $search) . '%';
}
$stmt = $pdo->prepare('SELECT * FROM not_found_log ' . ($where ? 'WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY hits DESC, last_seen_at DESC LIMIT 300');
$stmt->execute($args);
$rows = $stmt->fetchAll();

$counts = ['open' => 0, 'ignored' => 0, 'fixed' => 0];
foreach ($pdo->query('SELECT status, COUNT(*) c FROM not_found_log GROUP BY status') as $c) {
    $counts[$c['status']] = (int) $c['c'];
}
$mode = seo_setting('unmatched_404', 'redirect_home');

include __DIR__ . '/includes/layout-header.php';
flash_render();
?>
<div class="help-box">
    Every address on this site that returned <strong>404 Not Found</strong> is logged here, most-hit first, with where the visitor came from.
    Fix each one by <strong>redirecting it</strong> to the right page, or <strong>ignore</strong> junk (bots probing for <code>/wp-login.php</code> etc.).
    Right now, an unmatched address <?php echo $mode === 'show_404' ? 'shows a <strong>real 404 page</strong>' : 'is sent (301) to the <strong>most relevant page</strong>, or the <strong>homepage</strong> when nothing matches'; ?>
    &mdash; change this in <a href="seo-settings.php">Site Settings &amp; Code</a>.
</div>

<div class="tabs">
    <?php foreach (['open' => 'Open', 'fixed' => 'Fixed', 'ignored' => 'Ignored', 'all' => 'All'] as $key => $label): ?>
        <a href="?status=<?php echo $key; ?>" class="<?php echo $status === $key ? 'active' : ''; ?>"><?php echo $label; ?><?php echo isset($counts[$key]) ? ' (' . $counts[$key] . ')' : ''; ?></a>
    <?php endforeach; ?>
</div>

<div class="filter-bar">
    <form method="get" style="display:flex; gap:10px; align-items:center;">
        <input type="hidden" name="status" value="<?php echo h($status); ?>">
        <input type="text" name="q" value="<?php echo h($search); ?>" placeholder="Search URLs...">
        <button type="submit" class="btn btn-secondary btn-sm">Search</button>
    </form>
    <?php if ($counts['fixed'] + $counts['ignored'] > 0): ?>
        <form method="post" style="margin-left:auto;" onsubmit="return confirm('Remove all fixed and ignored entries from the log?');">
            <?php echo csrf_field(); ?><input type="hidden" name="action" value="clear">
            <button type="submit" class="btn btn-secondary btn-sm">Clear fixed &amp; ignored</button>
        </form>
    <?php endif; ?>
</div>

<div class="table-scroll">
<table class="admin-table">
    <thead><tr><th>Broken URL</th><th class="num">Hits</th><th>First seen</th><th>Last seen / referrer</th><th>Fix it</th></tr></thead>
    <tbody>
    <?php if (!$rows): ?><tr><td colspan="5"><?php echo $status === 'open' ? 'No open 404 errors &mdash; nothing to fix.' : 'Nothing here.'; ?></td></tr><?php endif; ?>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td class="path"><a href="<?php echo h(SEO_SITE_BASE . $r['path']); ?>" target="_blank" rel="noopener nofollow"><?php echo h($r['path']); ?></a><?php echo $r['status'] !== 'open' ? ' <span class="badge">' . h($r['status']) . '</span>' : ''; ?></td>
            <td class="num"><strong><?php echo (int) $r['hits']; ?></strong></td>
            <td class="muted"><?php echo h(substr($r['first_seen_at'], 0, 10)); ?></td>
            <td class="muted"><?php echo h($r['last_seen_at']); ?><br>
                <?php if ($r['last_referrer'] !== ''): ?><span style="font-size:0.78rem; word-break:break-all;">from <?php echo h($r['last_referrer']); ?></span><?php else: ?><span style="font-size:0.78rem;">no referrer</span><?php endif; ?></td>
            <td>
                <form method="post" style="display:flex; gap:6px; align-items:center; margin-bottom:6px;">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="action" value="redirect">
                    <input type="hidden" name="id" value="<?php echo (int) $r['id']; ?>">
                    <input type="text" name="to" placeholder="/new-page" style="padding:7px 9px; border:1px solid var(--border); border-radius:6px; width:150px;">
                    <select name="code" style="padding:7px 6px; border:1px solid var(--border); border-radius:6px;"><option value="301">301</option><option value="302">302</option><option value="410">410</option></select>
                    <button type="submit" class="btn btn-primary btn-sm">Redirect</button>
                </form>
                <form method="post">
                    <?php echo csrf_field(); ?><input type="hidden" name="id" value="<?php echo (int) $r['id']; ?>">
                    <?php if ($r['status'] === 'open'): ?>
                        <button type="submit" name="action" value="ignore" class="btn btn-secondary btn-sm">Ignore</button>
                    <?php else: ?>
                        <button type="submit" name="action" value="reopen" class="btn btn-secondary btn-sm">Reopen</button>
                    <?php endif; ?>
                    <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php include __DIR__ . '/includes/layout-footer.php'; ?>
