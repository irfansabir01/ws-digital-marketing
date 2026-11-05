<?php
// SEO helpers shared by the public site (header.php, footer.php, sitemap.php,
// robots.php, not-found.php) and the admin SEO screens.
//
// Everything here is fail-safe: if config.php or any of the SEO tables don't
// exist yet (the migration hasn't been run), or the database is unreachable,
// every function quietly returns "nothing configured" and the site renders
// exactly as it did before this feature existed.

const SEO_SITE_BASE = 'https://wsdigitalmarketing.com.au';
const SEO_DEFAULT_TITLE_SUFFIX = ' | W&S Digital Marketing';

function seo_db(): ?PDO
{
    static $pdo = false;
    if ($pdo !== false) {
        return $pdo;
    }
    try {
        if (!function_exists('get_db')) {
            $config = __DIR__ . '/../config.php';
            if (!is_file($config)) {
                return $pdo = null;
            }
            require_once $config;
        }
        return $pdo = get_db();
    } catch (Throwable $e) {
        return $pdo = null;
    }
}

// ----------------------------------------------------------------- URL paths

// A request URI -> the canonical comparison form: decoded path, no query,
// single slashes, no trailing slash (except "/").
function seo_normalize_path(string $uri): string
{
    $path = parse_url($uri, PHP_URL_PATH);
    $path = rawurldecode(is_string($path) && $path !== '' ? $path : '/');
    $path = preg_replace('#/+#', '/', $path);
    if ($path === '' || $path[0] !== '/') {
        $path = '/' . $path;
    }
    return $path === '/' ? '/' : rtrim($path, '/');
}

// Validates a path typed (or pasted as a full URL) into an admin form.
// Returns the normalised path, or null when it isn't a usable site path.
function seo_clean_path(string $input): ?string
{
    $p = trim($input);
    if ($p === '') {
        return null;
    }
    if (preg_match('#^https?://([^/]+)(/.*)?$#i', $p, $m)) {
        $host = strtolower($m[1]);
        if ($host !== 'wsdigitalmarketing.com.au' && $host !== 'www.wsdigitalmarketing.com.au') {
            return null;
        }
        $p = $m[2] ?? '/';
    }
    if ($p[0] !== '/') {
        $p = '/' . $p;
    }
    $path = seo_normalize_path($p);
    if (strlen($path) > 191 || preg_match('/[\x00-\x1f\s<>"\']/', $path)) {
        return null;
    }
    return $path;
}

// Validates a redirect destination: a site path ("/seo") or an absolute
// http(s) URL. Returns it unchanged when valid, otherwise null.
function seo_clean_target(string $input): ?string
{
    $t = trim($input);
    if ($t === '' || strlen($t) > 500 || preg_match('/[\x00-\x1f\s<>"\']/', $t)) {
        return null;
    }
    if ($t[0] === '/' && substr($t, 0, 2) !== '//') {
        return $t;
    }
    if (preg_match('#^https?://#i', $t) && filter_var($t, FILTER_VALIDATE_URL)) {
        return $t;
    }
    return null;
}

function seo_abs_url(string $url): string
{
    $url = trim($url);
    if ($url === '') {
        return '';
    }
    if ($url[0] === '/' && substr($url, 0, 2) !== '//') {
        return SEO_SITE_BASE . $url;
    }
    return $url;
}

// ------------------------------------------------------------------ settings

function seo_settings(): array
{
    static $settings = null;
    if ($settings !== null) {
        return $settings;
    }
    $settings = [];
    $pdo = seo_db();
    if ($pdo) {
        try {
            foreach ($pdo->query('SELECT setting_key, setting_value FROM site_settings') as $row) {
                $settings[$row['setting_key']] = (string) $row['setting_value'];
            }
        } catch (Throwable $e) {
            $settings = [];
        }
    }
    return $settings;
}

function seo_setting(string $key, ?string $default = null): ?string
{
    $all = seo_settings();
    return array_key_exists($key, $all) ? $all[$key] : $default;
}

function seo_default_robots(): string
{
    return "User-agent: *\nDisallow: /admin/\nAllow: /\n\nSitemap: " . SEO_SITE_BASE . "/sitemap.xml\n";
}

// ----------------------------------------------------------- JSON-LD / HTML

// Validates a JSON-LD document. Returns it pretty-printed, or null if it
// isn't valid JSON (an object or an array of objects).
function seo_validate_json(string $json): ?string
{
    $json = trim($json);
    if ($json === '') {
        return '';
    }
    $decoded = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
        return null;
    }
    return json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

// A <script type="application/ld+json"> tag for stored JSON-LD, re-encoded on
// the way out so it can never close the script element early.
function seo_json_ld_tag(?string $json): string
{
    if ($json === null || trim($json) === '') {
        return '';
    }
    $decoded = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
        return '';
    }
    $safe = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $safe = str_replace('</', '<\\/', (string) $safe);
    return '    <script type="application/ld+json">' . $safe . "</script>\n";
}

// Defence in depth for the per-page "extra content" HTML: scripts and inline
// event handlers have no place in a copy block, so they're stripped.
function seo_clean_extra_html(string $html): string
{
    $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html) ?? '';
    $html = preg_replace('#</?script\b[^>]*>#i', '', $html) ?? '';
    $html = preg_replace('/\son[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html) ?? '';
    $html = preg_replace('/(href|src)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '$1=$2#$2', $html) ?? '';
    return trim($html);
}

// ----------------------------------------------------------------- redirects

function seo_redirect_destination(string $to): string
{
    return seo_abs_url($to);
}

// If an active redirect matches $path, sends it (or a 410) and exits.
// Returns normally when nothing matches. Only GET/HEAD requests redirect.
function seo_try_redirect(string $path): void
{
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if ($method !== 'GET' && $method !== 'HEAD') {
        return;
    }
    $pdo = seo_db();
    if (!$pdo) {
        return;
    }
    try {
        $stmt = $pdo->prepare('SELECT id, to_url, status_code FROM redirects WHERE from_path = ? AND is_active = 1 LIMIT 1');
        $stmt->execute([$path]);
        $row = $stmt->fetch();
    } catch (Throwable $e) {
        return;
    }
    if (!$row) {
        return;
    }
    try {
        $pdo->prepare('UPDATE redirects SET hits = hits + 1, last_hit_at = NOW() WHERE id = ?')->execute([$row['id']]);
    } catch (Throwable $e) {
        // hit counting is best-effort
    }

    $code = (int) $row['status_code'];
    if ($code === 410) {
        http_response_code(410);
        header('Content-Type: text/html; charset=UTF-8');
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="robots" content="noindex"><title>Gone</title></head>'
            . '<body style="font-family:sans-serif;text-align:center;padding:80px 20px"><h1>This page has been removed</h1>'
            . '<p><a href="/">Back to the W&amp;S Digital Marketing homepage</a></p></body></html>';
        exit;
    }
    $dest = seo_redirect_destination((string) $row['to_url']);
    if ($dest === '') {
        return;
    }
    // A redirect pointing at its own URL would loop forever.
    if (strpos($dest, SEO_SITE_BASE) === 0 && seo_normalize_path($dest) === $path) {
        return;
    }
    if (!in_array($code, [301, 302, 307, 308], true)) {
        $code = 301;
    }
    header('Location: ' . $dest, true, $code);
    exit;
}

// Records a 404 hit (best-effort) for the dashboard's 404 report.
function seo_log_404(string $path): void
{
    $pdo = seo_db();
    if (!$pdo || strlen($path) > 191) {
        return;
    }
    $referrer = substr((string) ($_SERVER['HTTP_REFERER'] ?? ''), 0, 500);
    try {
        $pdo->prepare(
            'INSERT INTO not_found_log (path, hits, first_seen_at, last_seen_at, last_referrer) VALUES (?, 1, NOW(), NOW(), ?)
             ON DUPLICATE KEY UPDATE hits = hits + 1, last_seen_at = NOW(),
             last_referrer = IF(VALUES(last_referrer) <> \'\', VALUES(last_referrer), last_referrer)'
        )->execute([$path, $referrer]);
        // Keep the log from growing without bound under bot traffic.
        if (mt_rand(1, 200) === 1) {
            $pdo->exec(
                'DELETE FROM not_found_log WHERE status = \'open\' AND id NOT IN (
                    SELECT id FROM (SELECT id FROM not_found_log WHERE status = \'open\' ORDER BY last_seen_at DESC LIMIT 3000) keep_rows
                 )'
            );
        }
    } catch (Throwable $e) {
        // logging must never break the 404 response
    }
}

// ------------------------------------------------------------ page context

// Everything the SEO dashboard has configured for the current request: the
// matching page_seo row (or null) and the site settings. Also applies any
// active redirect for the current URL first (pass $skipRedirect = true from
// pages that have already handled redirects, like the 404 page).
function seo_context(bool $skipRedirect = false): array
{
    static $ctx = null;
    if ($ctx !== null) {
        return $ctx;
    }
    $path = seo_normalize_path($_SERVER['REQUEST_URI'] ?? '/');
    $ctx = ['path' => $path, 'page' => null, 'settings' => []];

    if (!$skipRedirect) {
        seo_try_redirect($path);
    }
    $pdo = seo_db();
    if ($pdo) {
        try {
            $stmt = $pdo->prepare('SELECT * FROM page_seo WHERE path = ? LIMIT 1');
            $stmt->execute([$path]);
            $row = $stmt->fetch();
            $ctx['page'] = $row ?: null;
        } catch (Throwable $e) {
            $ctx['page'] = null;
        }
    }
    $ctx['settings'] = seo_settings();
    return $ctx;
}

// The "extra content" block an SEO manager attached to the current page,
// printed in the same container style as the rest of the page content.
function seo_render_extra_content(): void
{
    $ctx = seo_context(true);
    $html = trim((string) ($ctx['page']['extra_content'] ?? ''));
    if ($html === '') {
        return;
    }
    echo "\n    <section class=\"agency-section seo-extra-content\">\n        <div class=\"container\">\n            <div class=\"agency-summary\">\n"
        . seo_clean_extra_html($html)
        . "\n            </div>\n        </div>\n    </section>\n";
}

// ------------------------------------------------------- smart 404 redirects

// Works out the most relevant live page for a URL that no longer exists (old
// WordPress URLs, renamed pages, mistyped links, removed blog posts, etc.).
// Used by not-found.php, blog-post.php and case-study.php AFTER any redirect
// set up in admin -> SEO -> Redirects has had its chance, so a manual
// redirect always wins. Returns a site path; "/" when nothing relevant fits.
//
//   $fallback -- where to send it when no keyword/page match is found
//                ("/" for general URLs, "/blog" for a missing blog post...).
function seo_smart_redirect_target(string $path, string $fallback = '/'): string
{
    $path = strtolower(seo_normalize_path($path));
    if ($path === '/') {
        return '/';
    }

    // Old sitemap URLs from the previous WordPress/Yoast/Rank Math site.
    if (preg_match('#sitemap[^/]*\.xml$#', $path)) {
        return '/sitemap.xml';
    }

    // Strip file extensions old platforms used (.html, .php, .aspx ...).
    $clean = preg_replace('#\.(?:html?|php\d?|aspx?|jsp|cfm)$#', '', $path);
    $segments = array_values(array_filter(explode('/', trim($clean, '/')), 'strlen'));

    // Old WordPress system paths carry no page meaning.
    if (!empty($segments) && preg_match('#^(?:wp-[a-z-]+|xmlrpc|feed|rss|comments|trackback|cgi-bin|amp)$#', $segments[0])) {
        return '/';
    }
    // The section an old URL lived in decides where it falls back to.
    $first = $segments[0] ?? '';
    $inCaseStudies = (bool) preg_match('#^(?:case-stud(?:y|ies)|casestud(?:y|ies)|portfolio|projects?|our-work|work|clients?)$#', $first);
    if ($fallback === '/' && preg_match('#^(?:news|articles?|posts?|category|tag|insights|resources|(?:19|20)\d\d)$#', $first)) {
        $fallback = '/blog';
    }
    $segments = array_values(array_filter($segments, function ($s) {
        return !preg_match('#^(?:feed|amp|page|\d+|embed|trackback|rss)$#', $s);
    }));
    if (empty($segments)) {
        return $fallback;
    }

    // 1) Any segment that is itself a live page: /services/seo -> /seo.
    require_once __DIR__ . '/site-pages.php';
    $static = [];
    foreach (site_static_pages() as $p) {
        $static[$p['path']] = true;
    }
    //    The last segment is checked first; parent segments only after the
    //    keyword match below, so /services/web-design reaches the web page.
    $candidate = '/' . end($segments);
    if (isset($static[$candidate]) && $candidate !== $path) {
        return $candidate;
    }

    // 2) A blog post or case study whose slug matches the last segment (old
    //    WordPress /%postname%/ or /2024/05/slug/ permalinks, renamed slugs).
    $last = end($segments);
    $pdo = seo_db();
    if ($pdo && preg_match('/^[a-z0-9-]+$/', $last)) {
        $lead = implode('-', array_slice(explode('-', $last), 0, 4));
        $like = str_replace(['%', '_'], ['\\%', '\\_'], $lead) . '%';
        $lookups = [
            ['SELECT slug FROM blog_posts WHERE is_published = 1 AND (slug = ? OR slug LIKE ?)', '/blog/'],
            ['SELECT slug FROM case_studies WHERE slug = ? OR slug LIKE ?', '/case-studies/'],
        ];
        foreach ($lookups as [$sql, $prefix]) {
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$last, $like]);
                $slugs = $stmt->fetchAll(PDO::FETCH_COLUMN);
            } catch (Throwable $e) {
                $slugs = [];
            }
            if (in_array($last, $slugs, true)) {
                $target = $prefix . $last;
            } elseif (count($slugs) === 1) {
                $target = $prefix . $slugs[0];
            } else {
                continue;
            }
            if ($target !== $path) {
                return $target;
            }
        }
    }

    // Old portfolio/case study URLs belong on the case studies listing.
    if ($inCaseStudies) {
        return '/case-studies';
    }

    // 3) Keyword match on the whole URL, most specific topics first.
    $texts = [
        '-' . preg_replace('/[^a-z0-9]+/', '-', end($segments)) . '-',
        '-' . preg_replace('/[^a-z0-9]+/', '-', implode('-', $segments)) . '-',
    ];
    $rules = [
        // Industries
        '/dental'               => ['dental', 'dentist', 'dentists', 'orthodontist', 'orthodontists'],
        '/legal-law'            => ['legal', 'law', 'lawyer', 'lawyers', 'law-firm', 'law-firms', 'solicitor', 'solicitors', 'attorney', 'attorneys'],
        '/real-estate'          => ['real-estate', 'realestate', 'property', 'properties', 'realtor', 'realtors'],
        '/ndis'                 => ['ndis', 'disability', 'disabilities', 'aged-care'],
        '/hotel-motel'          => ['hotel', 'hotels', 'motel', 'motels', 'accommodation', 'resort', 'resorts'],
        '/hospitality-tourism'  => ['hospitality', 'tourism', 'restaurant', 'restaurants', 'cafe', 'cafes', 'travel', 'tour', 'tours', 'venue', 'venues', 'bar', 'bars'],
        '/healthcare-medical'   => ['healthcare', 'health', 'medical', 'clinic', 'clinics', 'doctor', 'doctors', 'physio', 'physiotherapy', 'chiropractor', 'chiropractors', 'gp', 'pharmacy', 'allied-health'],
        '/accounting-finance'   => ['accounting', 'accountant', 'accountants', 'finance', 'financial', 'bookkeeping', 'bookkeeper', 'tax', 'mortgage', 'broker', 'brokers', 'insurance'],
        '/automotive'           => ['automotive', 'car', 'cars', 'auto', 'mechanic', 'mechanics', 'vehicle', 'vehicles', 'dealership', 'dealerships', 'detailing', 'car-detailing', 'panel-beater', 'smash-repair'],
        '/construction-building'=> ['construction', 'builder', 'builders', 'building', 'renovation', 'renovations', 'flooring', 'concrete', 'carpentry', 'architect', 'architects'],
        '/trades'               => ['trades', 'trade', 'tradie', 'tradies', 'plumber', 'plumbers', 'plumbing', 'electrician', 'electricians', 'electrical', 'hvac', 'air-conditioning', 'roofing', 'roofer', 'landscaping', 'landscaper', 'cleaning', 'cleaners', 'pest-control', 'appliance', 'appliances', 'fridge', 'repairs', 'repair', 'locksmith', 'painter', 'painters'],
        '/franchise'            => ['franchise', 'franchises', 'franchising', 'multi-location'],
        '/small-business-digital-marketing' => ['small-business', 'small-businesses', 'startup', 'startups', 'sme', 'smes'],

        // Services (specific before generic)
        '/ecommerce'           => ['ecommerce', 'e-commerce', 'shopify', 'woocommerce', 'online-store', 'online-shop', 'magento', 'bigcommerce', 'store', 'shop', 'product', 'products', 'google-shopping', 'merchant-center'],
        '/graphic-design'      => ['graphic', 'graphics', 'graphic-design', 'logo', 'logos', 'logo-design', 'branding', 'brand', 'brand-identity', 'print', 'flyer', 'flyers', 'brochure', 'brochures', 'business-cards', 'illustration', 'creative'],
        '/ppc-advertising'     => ['ppc', 'google-ads', 'adwords', 'pay-per-click', 'sem', 'paid', 'ads', 'advertising', 'adverts', 'campaign', 'campaigns', 'meta-ads', 'facebook-ads', 'instagram-ads', 'remarketing', 'retargeting', 'display', 'youtube-ads', 'bing-ads', 'microsoft-ads'],
        '/social-media'        => ['social', 'social-media', 'smm', 'facebook', 'instagram', 'tiktok', 'linkedin', 'pinterest', 'youtube', 'influencer', 'influencers', 'community-management'],
        '/content-writing'     => ['content', 'content-writing', 'copywriting', 'copywriter', 'copywriters', 'copy', 'writing', 'writer', 'writers', 'article', 'articles', 'blog-writing', 'email-marketing', 'newsletter', 'newsletters'],
        '/website-development' => ['web', 'website', 'websites', 'web-design', 'webdesign', 'web-development', 'webdevelopment', 'wordpress', 'developer', 'developers', 'development', 'design', 'designer', 'landing-page', 'landing-pages', 'hosting', 'maintenance', 'ux', 'ui', 'cro', 'conversion', 'redesign', 'app', 'apps'],
        '/seo'                 => ['seo', 'search-engine', 'search-engine-optimisation', 'search-engine-optimization', 'optimisation', 'optimization', 'local-seo', 'google-maps', 'maps', 'gmb', 'gbp', 'google-business', 'google-my-business', 'google-business-profile', 'backlinks', 'backlink', 'link-building', 'keyword', 'keywords', 'ranking', 'rankings', 'rank', 'geo', 'aeo', 'ai-search', 'ai-overviews', 'llm', 'technical-seo', 'citations', 'search'],

        // Company pages
        '/privacy-policy'   => ['privacy', 'cookie', 'cookies', 'gdpr', 'disclaimer'],
        '/terms-of-service' => ['terms', 'conditions', 'tos', 'refund', 'refunds'],
        '/contact'          => ['contact', 'contact-us', 'get-in-touch', 'enquiry', 'enquire', 'enquiries', 'quote', 'free-quote', 'get-a-quote', 'consultation', 'book', 'booking', 'appointment', 'audit', 'free-audit', 'growth-plan', 'call-us', 'get-started', 'thank-you', 'thanks'],
        '/our-team'         => ['team', 'our-team', 'staff', 'people', 'meet-the-team', 'ceo', 'founder', 'founders', 'author', 'careers', 'career', 'jobs', 'join-us'],
        '/reviews'          => ['review', 'reviews', 'testimonial', 'testimonials', 'feedback', 'clients-say'],
        '/faq'              => ['faq', 'faqs', 'questions', 'help', 'support'],
        '/pricing'          => ['pricing', 'price', 'prices', 'cost', 'costs', 'packages', 'package', 'plans', 'plan', 'rates', 'fees'],

        // Generic sections
        '/case-studies' => ['case-study', 'case-studies', 'casestudy', 'casestudies', 'portfolio', 'portfolios', 'our-work', 'work', 'works', 'projects', 'project', 'results', 'clients', 'client', 'success-stories', 'gallery', 'showcase'],
        '/blog'         => ['blog', 'blogs', 'news', 'insights', 'resources', 'tips', 'guide', 'guides', 'post', 'posts', 'category', 'tag', 'tags', 'learn'],
        '/industries'   => ['industries', 'industry', 'sectors', 'sector', 'who-we-help', 'niches'],
        '/about'        => ['about', 'about-us', 'who-we-are', 'company', 'our-story', 'mission', 'agency-profile', 'why-us', 'why-choose-us'],
        '/services'     => ['services', 'service', 'our-services', 'what-we-do', 'solutions', 'digital-marketing', 'marketing', 'digital', 'agency', 'online-marketing', 'internet-marketing', 'strategy', 'consulting', 'growth'],
        '/'             => ['home', 'homepage', 'index', 'default', 'welcome'],
    ];
    foreach ($texts as $text) {
        foreach ($rules as $target => $keywords) {
            foreach ($keywords as $kw) {
                if (strpos($text, '-' . $kw . '-') !== false) {
                    return $target !== $path ? $target : $fallback;
                }
            }
        }
    }

    // Any parent segment that is a live page: /services/unknown -> /services.
    for ($i = count($segments) - 2; $i >= 0; $i--) {
        $candidate = '/' . $segments[$i];
        if (isset($static[$candidate]) && $candidate !== $path) {
            return $candidate;
        }
    }

    return $fallback;
}

// Sends a permanent (301) redirect to the most relevant live page and exits.
function seo_smart_redirect(string $path, string $fallback = '/'): void
{
    $target = seo_smart_redirect_target($path, $fallback);
    header('Cache-Control: no-cache, must-revalidate');
    header('Location: ' . SEO_SITE_BASE . $target, true, 301);
    exit;
}

// Structured-data builders for pages that define their own JSON-LD in code.
// FAQ answers may contain inline links for the visible accordion; the schema
// gets the same text with the tags stripped so the two always match.
function seo_faq_schema(array $faqs): array
{
    $items = [];
    foreach ($faqs as $f) {
        $items[] = [
            '@type' => 'Question',
            'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(strip_tags($f['a']))],
        ];
    }
    return ['@type' => 'FAQPage', 'mainEntity' => $items];
}

// $crumbs: [['Home', '/'], ['Services', '/services'], ...]
function seo_breadcrumb_schema(array $crumbs): array
{
    $items = [];
    foreach (array_values($crumbs) as $i => [$name, $path]) {
        $items[] = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $name, 'item' => SEO_SITE_BASE . $path];
    }
    return ['@type' => 'BreadcrumbList', 'itemListElement' => $items];
}

function seo_graph_tag(array $nodes): string
{
    return seo_json_ld_tag(json_encode(
        ['@context' => 'https://schema.org', '@graph' => $nodes],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    ));
}
