<?php
// Handles every URL the web server couldn't match to a file or route (wired
// up in .htaccess with "ErrorDocument 404 /not-found.php"). In order:
//   1. an active redirect from admin -> SEO -> Redirects, if one matches;
//   2. otherwise the hit is logged for admin -> SEO -> 404 Errors;
//   3. then the visitor gets a permanent (301) redirect to the most relevant
//      live page for that URL (see seo_smart_redirect_target() in
//      includes/seo.php), or to the homepage when nothing relevant fits
//      (unless "Unmatched 404" in admin -> SEO -> Site Settings is set to
//      show a real 404 page).
//      Genuinely missing images/CSS/JS still return a plain 404.
require_once __DIR__ . '/includes/seo.php';

$requested = (string) ($_SERVER['REQUEST_URI'] ?? '/');
$path = seo_normalize_path($requested);
$requestedDirectly = ($_SERVER['REDIRECT_STATUS'] ?? '') !== '404';

if (!$requestedDirectly) {
    seo_try_redirect($path);
    seo_log_404($path);

    $isAsset = (bool) preg_match('/\.(?:jpe?g|png|gif|webp|avif|svg|ico|css|js|map|woff2?|ttf|eot|mp4|webm|pdf|xml|txt)$/i', $path);
    // Leftover files from the old WordPress site (wp-content uploads etc.)
    // and old sitemap URLs are treated as pages, not broken assets: they get
    // a permanent redirect like every other retired URL below.
    $isOldSiteFile = (bool) preg_match('#^/(?:wp-[a-z-]+|feed)(?:/|$)|sitemap[^/]*\.xml$#i', $path);
    if (!$isAsset || $isOldSiteFile) {
        // Every retired page URL gets a permanent (301) redirect: to the most
        // relevant live page when the URL's words point to one (old service,
        // industry, blog or case study URLs), otherwise to the homepage.
        // The "Unmatched 404" choice in admin -> SEO -> Site Settings still
        // applies to URLs with no relevant match (default: homepage).
        $target = seo_smart_redirect_target($path, '/');
        if ($target !== '/' || seo_setting('unmatched_404', 'redirect_home') !== 'show_404') {
            header('Cache-Control: no-cache, must-revalidate');
            header('Location: ' . SEO_SITE_BASE . $target, true, 301);
            exit;
        }
    }
    if ($isAsset && !$isOldSiteFile) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=UTF-8');
        echo 'Not found';
        exit;
    }
}

http_response_code(404);
$seo_skip_redirect = true;
$page_title = 'Page Not Found';
$page_description = 'The page you were looking for could not be found.';
$extra_head = '    <meta name="robots" content="noindex, follow">' . "\n";
include __DIR__ . '/header.php';
?>
    <section class="hero">
        <div class="container hero-content">
            <h1>Page Not Found</h1>
            <p class="hero-subtitle">The page you were looking for may have been moved or no longer exists.</p>
            <div class="hero-buttons">
                <a href="/" class="btn-primary">Back To Homepage</a>
                <a href="/services" class="btn-secondary">View Our Services</a>
            </div>
        </div>
    </section>
<?php include __DIR__ . '/footer.php'; ?>
