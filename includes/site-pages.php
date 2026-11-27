<?php
// Catalogue of the site's static public pages. Shared by sitemap.php (what to
// list) and the admin SEO screens (which pages can be edited), so a new page
// only needs adding here once.
//
// Dynamic pages (/blog/<slug>, /case-studies/<slug>) are NOT listed -- they
// come straight from the database.

function site_static_pages(): array
{
    $pages = [
        // path, label, group, sitemap priority, sitemap changefreq
        ['/', 'Home', 'Main', '1.0', 'weekly'],

        ['/services', 'All Services', 'Services', '0.9', 'monthly'],
        ['/seo', 'Search Engine Optimisation', 'Services', '0.9', 'monthly'],
        ['/ecommerce', 'Ecommerce', 'Services', '0.9', 'monthly'],
        ['/website-development', 'Website Design & Development', 'Services', '0.9', 'monthly'],
        ['/social-media', 'Social Media Marketing', 'Services', '0.9', 'monthly'],
        ['/ppc-advertising', 'Paid Advertising (PPC)', 'Services', '0.9', 'monthly'],
        ['/graphic-design', 'Graphic Design', 'Services', '0.9', 'monthly'],
        ['/content-writing', 'Content Writing', 'Services', '0.9', 'monthly'],
        ['/seo/sydney', 'SEO Sydney', 'Services', '0.8', 'monthly'],
        ['/website-development/sydney', 'Web Design Sydney', 'Services', '0.8', 'monthly'],
        ['/ppc-advertising/sydney', 'Google Ads Agency Sydney', 'Services', '0.8', 'monthly'],
        ['/social-media/sydney', 'Social Media Marketing Sydney', 'Services', '0.8', 'monthly'],
        ['/graphic-design/sydney', 'Graphic Designer Sydney', 'Services', '0.8', 'monthly'],
        ['/ecommerce/sydney', 'Ecommerce Web Design Sydney', 'Services', '0.8', 'monthly'],
        ['/seo/sydney/marsden-park', 'SEO Marsden Park', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/rouse-hill', 'SEO Rouse Hill', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/blacktown', 'SEO Blacktown', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/penrith', 'SEO Penrith', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/castle-hill', 'SEO Castle Hill', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/norwest', 'SEO Norwest', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/baulkham-hills', 'SEO Baulkham Hills', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/parramatta', 'SEO Parramatta', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/hills-district', 'SEO Hills District', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/western-sydney', 'SEO Western Sydney', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/liverpool', 'SEO Liverpool', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/chatswood', 'SEO Chatswood', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/hornsby', 'SEO Hornsby', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/campbelltown', 'SEO Campbelltown', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/bankstown', 'SEO Bankstown', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/mount-druitt', 'SEO Mount Druitt', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/north-sydney', 'SEO North Sydney', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/northern-beaches', 'SEO Northern Beaches', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/ryde', 'SEO Ryde', 'Services', '0.7', 'monthly'],
        ['/seo/sydney/wetherill-park', 'SEO Wetherill Park', 'Services', '0.7', 'monthly'],

        ['/industries', 'All Industries', 'Industries', '0.8', 'monthly'],
        ['/accounting-finance', 'Accounting & Finance', 'Industries', '0.7', 'monthly'],
        ['/automotive', 'Automotive', 'Industries', '0.7', 'monthly'],
        ['/construction-building', 'Construction & Building', 'Industries', '0.7', 'monthly'],
        ['/dental', 'Dental', 'Industries', '0.7', 'monthly'],
        ['/ecommerce-industry', 'Ecommerce (Industry)', 'Industries', '0.7', 'monthly'],
        ['/franchise', 'Franchise', 'Industries', '0.7', 'monthly'],
        ['/healthcare-medical', 'Healthcare & Medical', 'Industries', '0.7', 'monthly'],
        ['/hospitality-tourism', 'Hospitality & Tourism', 'Industries', '0.7', 'monthly'],
        ['/hotel-motel', 'Hotel & Motel', 'Industries', '0.7', 'monthly'],
        ['/legal-law', 'Legal & Law', 'Industries', '0.7', 'monthly'],
        ['/real-estate', 'Real Estate', 'Industries', '0.7', 'monthly'],
        ['/small-business-digital-marketing', 'Small Business', 'Industries', '0.7', 'monthly'],
        ['/ndis', 'NDIS Providers', 'Industries', '0.7', 'monthly'],
        ['/trades', 'Trades', 'Industries', '0.7', 'monthly'],

        ['/about', 'About Us', 'Agency', '0.6', 'monthly'],
        ['/our-team', 'Our Team', 'Agency', '0.6', 'monthly'],
        ['/case-studies', 'Case Studies (listing)', 'Agency', '0.8', 'weekly'],
        ['/reviews', 'Reviews', 'Agency', '0.6', 'monthly'],
        ['/pricing', 'Pricing', 'Agency', '0.7', 'monthly'],
        ['/blog', 'Blog (listing)', 'Agency', '0.7', 'weekly'],
        ['/faq', 'FAQ', 'Agency', '0.6', 'monthly'],
        ['/contact', 'Contact', 'Agency', '0.6', 'monthly'],

        ['/privacy-policy', 'Privacy Policy', 'Legal', '0.3', 'yearly'],
        ['/terms-of-service', 'Terms of Service', 'Legal', '0.3', 'yearly'],
    ];

    $out = [];
    foreach ($pages as [$path, $label, $group, $priority, $freq]) {
        $out[] = ['path' => $path, 'label' => $label, 'group' => $group, 'priority' => $priority, 'freq' => $freq];
    }
    return $out;
}

// The PHP file that renders a static page path ("/seo" -> seo.php), or null.
function site_page_file(string $path): ?string
{
    $name = $path === '/' ? 'index' : ltrim($path, '/');
    if (!preg_match('/^[a-z0-9-]+$/', $name)) {
        return null;
    }
    $file = __DIR__ . '/../' . $name . '.php';
    return is_file($file) ? $file : null;
}

// The title/description a page hard-codes in its own source ($page_title =
// '...';), read by plain text matching -- the file is never executed. Returns
// null for either value when the page builds it dynamically.
function site_page_defaults(string $path): array
{
    $result = ['title' => null, 'description' => null];
    // The six Sydney service pages take their copy from includes/sydney/<key>.php.
    require_once __DIR__ . '/sydney-pages.php';
    $sydneyPage = sydney_any_page_for_path($path);
    if ($sydneyPage !== null) {
        return ['title' => $sydneyPage['meta_title'], 'description' => $sydneyPage['meta_description']];
    }
    $file = site_page_file($path);
    if ($file === null) {
        return $result;
    }
    $source = @file_get_contents($file);
    if ($source === false) {
        return $result;
    }
    foreach (['title' => 'page_title', 'description' => 'page_description'] as $key => $var) {
        if (preg_match('/\$' . $var . '\s*=\s*\'((?:[^\'\\\\]|\\\\.)*)\'\s*;/s', $source, $m)) {
            $result[$key] = str_replace(["\\'", '\\\\'], ["'", '\\'], $m[1]);
        } elseif (preg_match('/\$' . $var . '\s*=\s*"((?:[^"\\\\]|\\\\.)*)"\s*;/s', $source, $m)) {
            $result[$key] = stripcslashes($m[1]);
        }
    }
    return $result;
}
