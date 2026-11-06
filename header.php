<?php
// Post-processes the finished page (de-duplicates structured data, adds image
// dimensions) -- see includes/output-filter.php.
require_once __DIR__ . '/includes/output-filter.php';
ob_start('seo_output_filter');

// Pages can set these before including this file; sensible defaults otherwise.
$page_title = $page_title ?? 'Premium Agency';
$page_description = $page_description ?? 'Data-driven Google Ads, Meta Ads, SEO and web design strategies that generate high-intent leads, sales, and revenue for ambitious Australian businesses.';

// Canonical URL: the current clean path (query string stripped) on the one
// true apex domain, regardless of which host/query variant the request
// actually came in on. Stale/garbage query strings indexed from before this
// site existed on this stack (WordPress search/AJAX URLs like ?s=... or
// ?wc-ajax=... hitting "/") collapse to the same canonical as a plain
// request for that path -- for "/" specifically, that's the homepage.
$canonical_path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
// /blog/some-post/ and /blog/some-post are one page: the canonical never has a trailing slash.
if ($canonical_path !== '/') {
    $canonical_path = rtrim($canonical_path, '/') ?: '/';
}
$canonical_url = 'https://wsdigitalmarketing.com.au' . $canonical_path;

// SEO dashboard overrides (admin -> SEO): per-page title/description/
// canonical/robots/social tags/schema, site-wide head code, and any active
// redirect for this URL. All of it silently does nothing until the SEO
// migration has been run, so the page renders as before in the meantime.
require_once __DIR__ . '/includes/seo.php';
$seo = seo_context(!empty($seo_skip_redirect));
$seo_page = $seo['page'];
$seo_settings = $seo['settings'];
if ($seo_page) {
    if ($seo_page['meta_title'] !== '') {
        $page_title = $seo_page['meta_title'];
    }
    if ($seo_page['meta_description'] !== '') {
        $page_description = $seo_page['meta_description'];
    }
    if ($seo_page['canonical_url'] !== '') {
        $canonical_url = seo_abs_url($seo_page['canonical_url']);
    }
}
$seo_title_suffix = $seo_settings['title_suffix'] ?? SEO_DEFAULT_TITLE_SUFFIX;
if ($seo_page && !empty($seo_page['no_suffix'])) {
    $seo_title_suffix = '';
}
// Pages with a long, descriptive headline (blog posts) skip the site-name suffix.
if (!empty($page_no_suffix)) {
    $seo_title_suffix = '';
}
$seo_noindex = $seo_page && !empty($seo_page['noindex']);
$seo_nofollow = $seo_page && !empty($seo_page['nofollow']);
$seo_og_title = ($seo_page['og_title'] ?? '') !== '' ? $seo_page['og_title'] : $page_title;
$seo_og_description = ($seo_page['og_description'] ?? '') !== '' ? $seo_page['og_description'] : $page_description;
$seo_og_image = ($seo_page['og_image'] ?? '') !== '' ? $seo_page['og_image'] : (!empty($page_og_image) ? $page_og_image : ($seo_settings['default_og_image'] ?? ''));
// No share image set anywhere (page, admin default): use the branded 1200x630 card
// instead of sending no image at all.
if ($seo_og_image === '') {
    $seo_og_image = '/images/og-default.jpg';
}
$seo_og_image = seo_abs_url((string) $seo_og_image);
$seo_og_type = !empty($page_og_type) ? $page_og_type : 'website';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title . $seo_title_suffix, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
<?php if ($seo_noindex || $seo_nofollow): ?>
    <meta name="robots" content="<?php echo $seo_noindex ? 'noindex' : 'index'; ?>, <?php echo $seo_nofollow ? 'nofollow' : 'follow'; ?>">
<?php endif; ?>

    <!-- Social sharing (Open Graph / Twitter) -->
    <meta property="og:locale" content="en_AU">
    <meta property="og:type" content="<?php echo htmlspecialchars($seo_og_type, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:site_name" content="W&amp;S Digital Marketing">
    <meta property="og:title" content="<?php echo htmlspecialchars($seo_og_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seo_og_description, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url, ENT_QUOTES, 'UTF-8'); ?>">
<?php if ($seo_og_image !== ''): ?>
    <meta property="og:image" content="<?php echo htmlspecialchars($seo_og_image, ENT_QUOTES, 'UTF-8'); ?>">
<?php endif; ?>
<?php if ($seo_og_image !== '' && !empty($page_og_image_alt)): ?>
    <meta property="og:image:alt" content="<?php echo htmlspecialchars($page_og_image_alt, ENT_QUOTES, 'UTF-8'); ?>">
<?php endif; ?>
    <meta name="twitter:card" content="<?php echo $seo_og_image !== '' ? 'summary_large_image' : 'summary'; ?>">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($seo_og_title, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($seo_og_description, ENT_QUOTES, 'UTF-8'); ?>">
<?php if ($seo_og_image !== ''): ?>
    <meta name="twitter:image" content="<?php echo htmlspecialchars($seo_og_image, ENT_QUOTES, 'UTF-8'); ?>">
<?php if (!empty($page_og_image_alt)): ?>
    <meta name="twitter:image:alt" content="<?php echo htmlspecialchars($page_og_image_alt, ENT_QUOTES, 'UTF-8'); ?>">
<?php endif; ?>
<?php endif; ?>

    <!-- Favicon: the W&S logo, shown in every browser tab/bookmark on every page since
         this file is included everywhere. favicon.ico covers old browsers and anything
         that requests /favicon.ico directly regardless of <link> tags; the PNG sizes
         cover modern browsers, Android home-screen icons, and iOS "Add to Home Screen". -->
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/favicon-192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">

    <!-- Agent discovery: the ARD catalog (/.well-known/ard.json) and the
         plain-markdown site guide (/llms.txt, generated by llms.php) tell AI agents and assistants what
         this site offers and where the key pages/actions are, without them having
         to crawl and guess. -->
    <link rel="ard" href="/.well-known/ard.json">
    <link rel="alternate" type="text/markdown" href="/llms.txt" title="LLM-friendly site guide">

    <!-- Everything below (fonts, icons, GSAP, Three.js) is self-hosted from this same
         domain under /fonts and /vendor -- identical files/versions to the public CDN
         copies used before. Browsers partition their cache per site nowadays, so a
         shared CDN gave no cache benefit, but every extra origin cost a separate
         DNS + TCP + TLS handshake before its first byte. Serving them from the one
         connection that's already open for the HTML removes those round trips
         entirely, which is what was holding back the first paint of the hero text. -->

    <!-- The logo renders immediately (splash screen + header on every page) -->
    <link rel="preload" as="image" href="/images/logo-ws.webp" fetchpriority="high">

    <!-- Inter (the site font): preloaded so the hero headline paints in its final
         font straight away instead of swapping late (which on desktop was pushing
         Largest Contentful Paint back). One variable file covers every weight used
         (400-800); the @font-face rules live at the top of css/style.css. -->
    <link rel="preload" as="font" type="font/woff2" href="/fonts/inter-latin.woff2" crossorigin>

    <!-- Site CSS: one external, browser-cached file shared by every page instead of a
         ~20KB inline block repeated on each request. .htaccess caches this file for a
         year as "immutable", so the ?v= query string (the file's own last-modified
         time) is what actually busts that cache on every real edit -- without it,
         visitors who already loaded the site once would keep serving the old CSS for
         a full year no matter how many fixes get shipped. -->
    <link rel="stylesheet" href="/css/style.css?v=<?php echo @filemtime(__DIR__ . '/css/style.css') ?: time(); ?>">

    <!-- GSAP / ScrollTrigger: deferred so they never block HTML parsing or first paint.
         Deferred scripts execute in strict source order before DOMContentLoaded, so
         js/main.js (loaded right after these, at the end of the page) can safely
         assume gsap/ScrollTrigger are ready. Three.js is intentionally NOT loaded here
         -- see footer.php for why -- it's ~600KB and only needed by the decorative
         background, so it must not sit in front of main.js in the defer queue or the
         header/nav toggle would have to wait for it to finish downloading first. -->
    <script src="/vendor/gsap-3.12.2/gsap.min.js" defer></script>
    <script src="/vendor/gsap-3.12.2/ScrollTrigger.min.js" defer></script>

    <!-- FontAwesome: loaded non-render-blocking (applied once ready) since icons are
         decorative and shouldn't hold up first paint. No separate preload: a preload
         at the same priority as the site CSS made this ~100KB file compete with it
         for bandwidth during the most critical part of the load. -->
    <link rel="stylesheet" href="/vendor/fontawesome-6.4.2/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="/vendor/fontawesome-6.4.2/css/all.min.css"></noscript>

<?php
    // Structured data (JSON-LD): admin -> SEO site-wide, then this page's own.
    echo seo_json_ld_tag($seo_settings['schema_json'] ?? '');
    echo seo_json_ld_tag($seo_page['schema_json'] ?? '');
    // Schema the page defines in code ($extra_head, marked data-origin="code")
    // plus an automatic breadcrumb. Anything the admin already provides above
    // wins -- includes/output-filter.php drops the duplicate types.
    if (!empty($extra_head)) echo $extra_head;
    echo seo_auto_breadcrumb_tag($canonical_path);
    // Custom code from admin -> SEO -> Site Code (Analytics, Search Console
    // verification, etc.) -- printed as-is, it's trusted admin-entered markup.
    if (($seo_settings['head_code'] ?? '') !== '') echo "\n" . $seo_settings['head_code'] . "\n";
?>
</head>
<body>
<?php if (($seo_settings['body_code'] ?? '') !== '') echo $seo_settings['body_code'] . "\n"; ?>

    <!-- SPLASH SCREEN: plays on every page load/navigation and on refresh -->
    <div id="splash-screen" aria-hidden="true">
        <img src="/images/logo-ws.webp" alt="" class="splash-logo">
        <div class="splash-bar"></div>
    </div>

    <!-- HEADER (Solid Dark Theme, No Blur) -->
    <header>
        <div class="container nav-wrapper">
            <a href="/" class="logo" style="display: flex; align-items: center;">
                <img src="/images/logo-ws.webp" alt="W&S Digital Marketing Logo" class="logo-img">
            </a>
            
            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="/" onclick="toggleMenu()">Home</a></li>
                    <li><a href="/about" onclick="toggleMenu()">About Us</a></li>
                    
                    <!-- SERVICES DROPDOWN -->
                    <li class="dropdown" onclick="toggleDropdown(event)">
                        <a href="/services">Services <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem; margin-left: 4px;"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="/services" class="dropdown-hub-link">All Services</a></li>
                            <li><a href="/seo">Search Engine Optimisation</a></li>
                            <li><a href="/ecommerce">Ecommerce</a></li>
                            <li><a href="/website-development">Website Design &amp; Development</a></li>
                            <li><a href="/social-media">Social Media Marketing</a></li>
                            <li><a href="/ppc-advertising">Paid Advertising</a></li>
                            <li><a href="/graphic-design">Graphic Design</a></li>
                            <li><a href="/content-writing">Content Writing</a></li>
                        </ul>
                    </li>

                    <li><a href="/case-studies" onclick="toggleMenu()">Case Studies</a></li>
                    
                    <!-- AGENCY DROPDOWN -->
                    <li class="dropdown" onclick="toggleDropdown(event)">
                        <a href="/about">Agency <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem; margin-left: 4px;"></i></a>
                        <ul class="dropdown-menu">
                            <li><a href="/our-team">Our Team</a></li>
                            <li><a href="/reviews">Reviews</a></li>
                            <li><a href="/blog">Blog</a></li>
                            <li><a href="/pricing">Pricing Plans</a></li>
                            <li><a href="/faq">FAQ</a></li>
                        </ul>
                    </li>

                    <!-- INDUSTRIES DROPDOWN -->
                    <li class="dropdown" onclick="toggleDropdown(event)">
                        <a href="/industries">Industries <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem; margin-left: 4px;"></i></a>
                        <ul class="dropdown-menu dropdown-menu-grid">
                            <li><a href="/industries" class="dropdown-hub-link">All Industries</a></li>
                            <li><a href="/accounting-finance">Accounting & Finance</a></li>
                            <li><a href="/automotive">Automotive</a></li>
                            <li><a href="/construction-building">Construction & Building</a></li>
                            <li><a href="/dental">Dental</a></li>
                            <li><a href="/ecommerce-industry">eCommerce</a></li>
                            <li><a href="/franchise">Franchise</a></li>
                            <li><a href="/healthcare-medical">Healthcare & Medical</a></li>
                            <li><a href="/hospitality-tourism">Hospitality & Tourism</a></li>
                            <li><a href="/hotel-motel">Hotel & Motel</a></li>
                            <li><a href="/legal-law">Legal / Law</a></li>
                            <li><a href="/real-estate">Real Estate</a></li>
                            <li><a href="/small-business-digital-marketing">Small Business Digital Marketing</a></li>
                            <li><a href="/ndis">NDIS</a></li>
                            <li><a href="/trades">Trades</a></li>
                        </ul>
                    </li>
                    <li><a href="/contact" onclick="toggleMenu()">Contact Us</a></li>
                </ul>
                <div class="nav-roll-edge" id="navRollEdge" aria-hidden="true"></div>
            </nav>
            <div class="nav-cta">
                <a href="#contact" class="header-cta">GET FREE PLAN</a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button class="mobile-btn" onclick="toggleMenu()" aria-label="Toggle menu" aria-expanded="false" aria-controls="navMenu"><i class="fa-solid fa-bars"></i></button>
        </div>
    </header>

    <main>