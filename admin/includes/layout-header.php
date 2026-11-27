<?php
// Expects $page_title to be set by the including page.
$page_title = $page_title ?? 'Admin';
$current = basename($_SERVER['SCRIPT_NAME']);
// "New" enquiries badge in the nav (admins only; hidden if the table isn't there yet).
$newEnquiries = 0;
if (admin_has_role('admin')) {
    try {
        $newEnquiries = (int) get_db()->query("SELECT COUNT(*) FROM enquiries WHERE status = 'new'")->fetchColumn();
    } catch (Throwable $e) {
        $newEnquiries = 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?> | Admin</title>
<link rel="stylesheet" href="admin.css?v=<?php echo @filemtime(__DIR__ . '/../admin.css') ?: time(); ?>">
</head>
<body>
<div class="admin-shell">
    <nav class="admin-nav">
        <div class="admin-nav-brand">W&amp;S Admin</div>
        <div class="admin-nav-role"><?php echo admin_has_role('admin') ? 'Administrator' : (admin_has_role('seo') ? 'SEO Manager' : ''); ?></div>
        <a href="index.php" class="<?php echo $current === 'index.php' ? 'active' : ''; ?>">Dashboard</a>
<?php if (admin_has_role('admin')): ?>
        <a href="enquiries.php" class="<?php echo in_array($current, ['enquiries.php', 'enquiry-view.php'], true) ? 'active' : ''; ?>">Enquiries<?php echo $newEnquiries > 0 ? ' <span class="badge badge-info">' . $newEnquiries . ' new</span>' : ''; ?></a>
        <a href="blog-list.php" class="<?php echo in_array($current, ['blog-list.php', 'blog-form.php'], true) ? 'active' : ''; ?>">Blog Posts</a>
        <a href="team-list.php" class="<?php echo in_array($current, ['team-list.php', 'team-form.php'], true) ? 'active' : ''; ?>">Team Members</a>
        <a href="case-list.php" class="<?php echo in_array($current, ['case-list.php', 'case-form.php', 'case-weekly.php'], true) ? 'active' : ''; ?>">Case Studies</a>
        <a href="case-weekly-all.php" class="<?php echo $current === 'case-weekly-all.php' ? 'active' : ''; ?>">&nbsp;&nbsp;&#8627; Weekly Update (all)</a>
        <a href="review-list.php" class="<?php echo in_array($current, ['review-list.php', 'review-form.php'], true) ? 'active' : ''; ?>">Reviews</a>
<?php endif; ?>
        <div class="admin-nav-section">SEO</div>
        <a href="seo-pages.php" class="<?php echo in_array($current, ['seo-pages.php', 'seo-page-form.php'], true) ? 'active' : ''; ?>">Pages SEO</a>
        <a href="seo-content.php" class="<?php echo in_array($current, ['seo-content.php', 'seo-content-form.php'], true) ? 'active' : ''; ?>">Posts &amp; Case Studies</a>
        <a href="seo-redirects.php" class="<?php echo $current === 'seo-redirects.php' ? 'active' : ''; ?>">Redirects</a>
        <a href="seo-404.php" class="<?php echo $current === 'seo-404.php' ? 'active' : ''; ?>">404 Errors</a>
        <a href="seo-robots.php" class="<?php echo $current === 'seo-robots.php' ? 'active' : ''; ?>">Robots.txt</a>
        <a href="seo-sitemap.php" class="<?php echo $current === 'seo-sitemap.php' ? 'active' : ''; ?>">Sitemap</a>
        <a href="seo-images.php" class="<?php echo $current === 'seo-images.php' ? 'active' : ''; ?>">Image SEO</a>
        <a href="seo-settings.php" class="<?php echo $current === 'seo-settings.php' ? 'active' : ''; ?>">Site Settings &amp; Code</a>
        <div class="admin-nav-section">Account</div>
<?php if (admin_has_role('admin')): ?>
        <a href="users.php" class="<?php echo $current === 'users.php' ? 'active' : ''; ?>">Users &amp; Access</a>
<?php endif; ?>
        <a href="account.php" class="<?php echo $current === 'account.php' ? 'active' : ''; ?>">My Password</a>
        <a href="/" target="_blank">View Site &#8594;</a>
        <a href="logout.php" class="admin-nav-logout">Log Out</a>
    </nav>
    <main class="admin-main">
        <h1><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></h1>
