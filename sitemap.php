<?php
// Served at /sitemap.xml (see the .htaccess rewrite near the bottom of the
// rewrite block). Static pages are listed below; case study and blog post
// detail pages are pulled live from the database so a new one added through
// the admin panel appears here automatically, with no file to edit.
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/site-pages.php';
require_once __DIR__ . '/includes/seo.php';

header('Content-Type: application/xml; charset=UTF-8');
// Always build from the live database: stop the web server's page cache
// (LiteSpeed on Hostinger), a CDN or a browser from serving an old copy, so a
// newly published blog post shows up in the sitemap straight away.
header('Cache-Control: no-cache, must-revalidate, max-age=0');
header('X-LiteSpeed-Cache-Control: no-cache');

$base = 'https://wsdigitalmarketing.com.au';

$staticPages = site_static_pages();

$caseStudies = [];
$blogPosts = [];
// Separate try blocks: a problem reading one table must not drop the other's URLs.
try {
    $caseStudies = get_db()->query('SELECT slug, updated_at FROM case_studies ORDER BY display_order ASC')->fetchAll();
} catch (PDOException $e) {
    $caseStudies = [];
}
try {
    $blogPosts = get_db()->query('SELECT slug, updated_at FROM blog_posts WHERE is_published = 1 ORDER BY created_at DESC')->fetchAll();
} catch (PDOException $e) {
    $blogPosts = [];
}

// Per-URL sitemap settings from admin -> SEO (exclude a URL, or change its
// priority / change frequency). A noindex page is never listed.
$seoOverrides = [];
try {
    foreach (get_db()->query('SELECT path, in_sitemap, noindex, sitemap_priority, sitemap_changefreq FROM page_seo') as $row) {
        $seoOverrides[$row['path']] = $row;
    }
} catch (PDOException $e) {
    $seoOverrides = [];
}

function sitemap_included(array $overrides, string $path): bool
{
    $ov = $overrides[$path] ?? null;
    return !$ov || ((int) $ov['in_sitemap'] === 1 && (int) $ov['noindex'] === 0);
}

function sitemap_value(array $overrides, string $path, string $field, string $default): string
{
    $ov = $overrides[$path] ?? null;
    return ($ov && $ov[$field] !== '') ? $ov[$field] : $default;
}

function sitemap_lastmod(?string $timestamp): string
{
    $time = $timestamp ? strtotime($timestamp) : false;
    return $time ? date('Y-m-d', $time) : date('Y-m-d');
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($staticPages as $page): if (!sitemap_included($seoOverrides, $page['path'])) continue; ?>
    <url>
        <loc><?php echo htmlspecialchars($base . ($page['path'] === '/' ? '/' : $page['path']), ENT_QUOTES, 'UTF-8'); ?></loc>
        <changefreq><?php echo htmlspecialchars(sitemap_value($seoOverrides, $page['path'], 'sitemap_changefreq', $page['freq']), ENT_QUOTES, 'UTF-8'); ?></changefreq>
        <priority><?php echo htmlspecialchars(sitemap_value($seoOverrides, $page['path'], 'sitemap_priority', $page['priority']), ENT_QUOTES, 'UTF-8'); ?></priority>
    </url>
<?php endforeach; ?>
<?php foreach ($caseStudies as $cs): $csPath = '/case-studies/' . $cs['slug']; if (!sitemap_included($seoOverrides, $csPath)) continue; ?>
    <url>
        <loc><?php echo htmlspecialchars($base . $csPath, ENT_QUOTES, 'UTF-8'); ?></loc>
        <lastmod><?php echo sitemap_lastmod($cs['updated_at'] ?? null); ?></lastmod>
        <changefreq><?php echo htmlspecialchars(sitemap_value($seoOverrides, $csPath, 'sitemap_changefreq', 'monthly'), ENT_QUOTES, 'UTF-8'); ?></changefreq>
        <priority><?php echo htmlspecialchars(sitemap_value($seoOverrides, $csPath, 'sitemap_priority', '0.7'), ENT_QUOTES, 'UTF-8'); ?></priority>
    </url>
<?php endforeach; ?>
<?php foreach ($blogPosts as $post): $postPath = '/blog/' . $post['slug']; if (!sitemap_included($seoOverrides, $postPath)) continue; ?>
    <url>
        <loc><?php echo htmlspecialchars($base . $postPath, ENT_QUOTES, 'UTF-8'); ?></loc>
        <lastmod><?php echo sitemap_lastmod($post['updated_at'] ?? null); ?></lastmod>
        <changefreq><?php echo htmlspecialchars(sitemap_value($seoOverrides, $postPath, 'sitemap_changefreq', 'monthly'), ENT_QUOTES, 'UTF-8'); ?></changefreq>
        <priority><?php echo htmlspecialchars(sitemap_value($seoOverrides, $postPath, 'sitemap_priority', '0.6'), ENT_QUOTES, 'UTF-8'); ?></priority>
    </url>
<?php endforeach; ?>
</urlset>
