<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/seo-admin.php';
$page_title = 'Dashboard';

$pdo = get_db();
$isAdmin = admin_has_role('admin');

// SEO snapshot -- each figure is null until the SEO migration has been run.
$seo = ['open404' => null, 'redirects' => null, 'overrides' => null, 'noindex' => null, 'noDesc' => null, 'noAlt' => null];
$top404 = [];
try {
    $seo['open404'] = (int) $pdo->query("SELECT COUNT(*) FROM not_found_log WHERE status = 'open'")->fetchColumn();
    $seo['redirects'] = (int) $pdo->query('SELECT COUNT(*) FROM redirects WHERE is_active = 1')->fetchColumn();
    $seo['overrides'] = (int) $pdo->query("SELECT COUNT(*) FROM page_seo WHERE meta_title <> '' OR meta_description <> '' OR canonical_url <> '' OR og_title <> '' OR schema_json <> '' OR extra_content <> ''")->fetchColumn();
    $seo['noindex'] = (int) $pdo->query('SELECT COUNT(*) FROM page_seo WHERE noindex = 1')->fetchColumn();
    $top404 = $pdo->query("SELECT path, hits, last_seen_at FROM not_found_log WHERE status = 'open' ORDER BY hits DESC, last_seen_at DESC LIMIT 5")->fetchAll();
} catch (PDOException $e) {
    $seo['open404'] = null;
}
try {
    $seo['noDesc'] = (int) $pdo->query("SELECT (SELECT COUNT(*) FROM blog_posts WHERE is_published = 1 AND (meta_description IS NULL OR meta_description = '')) + (SELECT COUNT(*) FROM case_studies WHERE meta_description IS NULL OR meta_description = '')")->fetchColumn();
    $seo['noAlt'] = (int) $pdo->query("SELECT (SELECT COUNT(*) FROM blog_posts WHERE image_path <> '' AND (image_alt IS NULL OR image_alt = '')) + (SELECT COUNT(*) FROM case_studies WHERE image_path <> '' AND (image_alt IS NULL OR image_alt = ''))")->fetchColumn();
} catch (PDOException $e) {
    // image_alt column missing until the SEO migration runs
    try {
        $seo['noDesc'] = (int) $pdo->query("SELECT (SELECT COUNT(*) FROM blog_posts WHERE is_published = 1 AND (meta_description IS NULL OR meta_description = '')) + (SELECT COUNT(*) FROM case_studies WHERE meta_description IS NULL OR meta_description = '')")->fetchColumn();
    } catch (PDOException $e2) {
        $seo['noDesc'] = null;
    }
}

include __DIR__ . '/includes/layout-header.php';
?>
<?php if (!empty($_GET['denied'])): ?>
    <div class="alert alert-error">Your account doesn't have access to that page.</div>
<?php endif; ?>
<p>Signed in as <strong><?php echo h($_SESSION['admin_username']); ?></strong> (<?php echo $isAdmin ? 'Administrator' : 'SEO Manager'; ?>).</p>

<?php
$enq = null;
if ($isAdmin) {
    try {
        $enq = ['new' => 0, 'week' => 0, 'won' => 0];
        $enq['new'] = (int) $pdo->query("SELECT COUNT(*) FROM enquiries WHERE status = 'new'")->fetchColumn();
        $enq['week'] = (int) $pdo->query("SELECT COUNT(*) FROM enquiries WHERE status <> 'spam' AND created_at > (NOW() - INTERVAL 7 DAY)")->fetchColumn();
        $enq['won'] = (int) $pdo->query("SELECT COUNT(*) FROM enquiries WHERE status = 'won'")->fetchColumn();
    } catch (PDOException $e) {
        $enq = null; // table is created on first visit to Enquiries
    }
}
?>
<?php if ($enq !== null): ?>
<h2 style="font-size:1.1rem; margin:24px 0 12px;">Enquiries</h2>
<div class="stat-row">
    <a class="admin-card" href="enquiries.php?status=new"><div class="num"><?php echo $enq['new']; ?></div><div class="label">New enquiries to follow up</div></a>
    <a class="admin-card" href="enquiries.php"><div class="num"><?php echo $enq['week']; ?></div><div class="label">Enquiries in the last 7 days</div></a>
    <a class="admin-card" href="enquiries.php?status=won"><div class="num"><?php echo $enq['won']; ?></div><div class="label">Won</div></a>
</div>
<?php endif; ?>

<h2 style="font-size:1.1rem; margin:24px 0 12px;">SEO overview</h2>
<?php if ($seo['open404'] === null): ?>
    <div class="alert alert-warning">The SEO tools need a one-time database setup: run <code>seo-dashboard-migration.sql</code> in phpMyAdmin (SQL tab).</div>
<?php else: ?>
<div class="stat-row">
    <a class="admin-card" href="seo-404.php"><div class="num"><?php echo $seo['open404']; ?></div><div class="label">Open 404 errors</div></a>
    <a class="admin-card" href="seo-redirects.php"><div class="num"><?php echo $seo['redirects']; ?></div><div class="label">Active redirects</div></a>
    <a class="admin-card" href="seo-pages.php"><div class="num"><?php echo $seo['overrides']; ?></div><div class="label">Pages with SEO overrides</div></a>
    <a class="admin-card" href="seo-pages.php"><div class="num"><?php echo $seo['noindex']; ?></div><div class="label">Pages set to noindex</div></a>
    <a class="admin-card" href="seo-content.php"><div class="num"><?php echo (int) $seo['noDesc']; ?></div><div class="label">Posts / case studies missing a meta description</div></a>
    <a class="admin-card" href="seo-images.php"><div class="num"><?php echo $seo['noAlt'] === null ? '&ndash;' : $seo['noAlt']; ?></div><div class="label">Images missing alt text</div></a>
</div>

<?php if ($top404): ?>
<div class="admin-card">
    <strong>Most-hit broken URLs</strong>
    <div class="table-scroll"><table class="admin-table" style="margin-top:10px;">
        <thead><tr><th>URL</th><th class="num">Hits</th><th>Last seen</th></tr></thead>
        <tbody>
        <?php foreach ($top404 as $row): ?>
            <tr><td class="path"><?php echo h($row['path']); ?></td><td class="num"><?php echo (int) $row['hits']; ?></td><td class="muted"><?php echo h($row['last_seen_at']); ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <p style="margin-bottom:0;"><a href="seo-404.php" class="btn btn-primary btn-sm">Review all 404 errors</a></p>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if ($isAdmin):
    $blogCount = (int) $pdo->query('SELECT COUNT(*) FROM blog_posts')->fetchColumn();
    $publishedCount = (int) $pdo->query('SELECT COUNT(*) FROM blog_posts WHERE is_published = 1')->fetchColumn();
    $teamCount = (int) $pdo->query('SELECT COUNT(*) FROM team_members')->fetchColumn();
    $caseCount = (int) $pdo->query('SELECT COUNT(*) FROM case_studies')->fetchColumn();
    $reviewCount = (int) $pdo->query('SELECT COUNT(*) FROM reviews')->fetchColumn();
?>
<h2 style="font-size:1.1rem; margin:28px 0 12px;">Content</h2>
<div class="dashboard-grid">
    <div class="admin-card"><div class="num"><?php echo $blogCount; ?></div><div class="label">Total Blog Posts</div></div>
    <div class="admin-card"><div class="num"><?php echo $publishedCount; ?></div><div class="label">Published Posts</div></div>
    <div class="admin-card"><div class="num"><?php echo $teamCount; ?></div><div class="label">Team Members</div></div>
    <div class="admin-card"><div class="num"><?php echo $caseCount; ?></div><div class="label">Case Studies</div></div>
    <div class="admin-card"><div class="num"><?php echo $reviewCount; ?></div><div class="label">Reviews</div></div>
</div>

<div class="admin-card" style="margin-top: 20px;">
    <p style="margin-top:0;"><a href="blog-form.php" class="btn btn-primary">+ Add Blog Post</a></p>
    <p><a href="team-form.php" class="btn btn-primary">+ Add Team Member</a></p>
    <p><a href="case-form.php" class="btn btn-primary">+ Add Case Study</a></p>
    <p style="margin-bottom:0;"><a href="review-form.php" class="btn btn-primary">+ Add Review</a></p>
</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/layout-footer.php'; ?>
