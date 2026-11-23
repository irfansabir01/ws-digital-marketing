<?php
// Registry of the six Sydney service pages (/seo/sydney, /website-development/sydney, ...).
// One renderer (sydney-service.php + includes/sydney-template.php) serves all of them; the
// copy for each page lives in includes/sydney/<key>.php and its images in includes/sydney/images.php.
//
// Used by: sydney-service.php (the pages), the parent service pages and homepage (internal
// links), includes/site-pages.php (sitemap), llms.php and blog-post.php (related-service box).

const SYDNEY_PAGE_KEYS = ['seo', 'website-development', 'ppc-advertising', 'social-media', 'graphic-design', 'ecommerce'];

// key => [path, nav label, parent path, parent label, short card text, parent-page blurb]
function sydney_registry(): array
{
    return [
        'seo' => [
            'path' => '/seo/sydney', 'label' => 'SEO Sydney', 'parent' => '/seo', 'parent_label' => 'Search Engine Optimisation',
            'teaser' => 'Local search plans built around calls, quotes and sales across Greater Sydney.',
            'blurb' => 'Based in Sydney\'s north-west, we plan local search work around the suburbs and customers you actually serve. See how our SEO service works for Sydney businesses, from technical fixes to Google Business Profile support.',
            'heading' => 'Looking For SEO In Sydney?', 'cta' => 'SEO Sydney: Local Search Services',
        ],
        'website-development' => [
            'path' => '/website-development/sydney', 'label' => 'Web Design Sydney', 'parent' => '/website-development', 'parent_label' => 'Website Design & Development',
            'teaser' => 'Mobile-friendly business websites designed to make enquiries easier.',
            'blurb' => 'Need a website for a Sydney business? Read how we plan, design and build mobile-friendly sites that help visitors enquire, and how a project runs from discovery to launch.',
            'heading' => 'Need A Website In Sydney?', 'cta' => 'Web Design Sydney: Business Websites',
        ],
        'ppc-advertising' => [
            'path' => '/ppc-advertising/sydney', 'label' => 'Google Ads Agency Sydney', 'parent' => '/ppc-advertising', 'parent_label' => 'Paid Advertising',
            'teaser' => 'Focused Google Ads campaigns with clear targeting and lead tracking.',
            'blurb' => 'Running ads in Sydney? Our Google Ads page explains how we plan campaigns, review search terms and track the leads that matter, along with budget and reporting expectations.',
            'heading' => 'Advertising To Sydney Customers?', 'cta' => 'Google Ads Agency Sydney',
        ],
        'social-media' => [
            'path' => '/social-media/sydney', 'label' => 'Social Media Marketing Sydney', 'parent' => '/social-media', 'parent_label' => 'Social Media Marketing',
            'teaser' => 'Social content and campaigns with a commercial purpose.',
            'blurb' => 'Our Sydney social media page covers content planning, platform choice, paid social and how we report what changed for local businesses.',
            'heading' => 'Social Media For Sydney Businesses', 'cta' => 'Social Media Marketing Sydney',
        ],
        'graphic-design' => [
            'path' => '/graphic-design/sydney', 'label' => 'Graphic Designer Sydney', 'parent' => '/graphic-design', 'parent_label' => 'Graphic Design',
            'teaser' => 'Logos, campaign graphics and print design that stay consistent.',
            'blurb' => 'Our Sydney graphic design page explains logo, brand identity and campaign design, what to prepare for a brief and which files you should receive.',
            'heading' => 'Need A Graphic Designer In Sydney?', 'cta' => 'Graphic Designer Sydney',
        ],
        'ecommerce' => [
            'path' => '/ecommerce/sydney', 'label' => 'Ecommerce Web Design Sydney', 'parent' => '/ecommerce', 'parent_label' => 'Ecommerce',
            'teaser' => 'Shopify and WooCommerce stores that make buying easier.',
            'blurb' => 'Building a store for Sydney customers? See how we approach Shopify and WooCommerce design, from catalogue structure and product pages to checkout testing and launch.',
            'heading' => 'Building An Online Store In Sydney?', 'cta' => 'Ecommerce Web Design Sydney',
        ],
    ];
}

// The page for a request path ("/seo/sydney" -> 'seo'), or null.
function sydney_key_for_path(string $path): ?string
{
    $path = rtrim($path, '/');
    foreach (sydney_registry() as $key => $p) {
        if ($p['path'] === $path) {
            return $key;
        }
    }
    return null;
}

function sydney_images(): array
{
    static $images = null;
    if ($images === null) {
        $images = require __DIR__ . '/sydney/images.php';
    }
    return $images;
}

// Full content + registry data + images for one page, or null for an unknown key.
function sydney_page(string $key): ?array
{
    $reg = sydney_registry();
    if (!isset($reg[$key]) || !is_file(__DIR__ . '/sydney/' . $key . '.php')) {
        return null;
    }
    $data = require __DIR__ . '/sydney/' . $key . '.php';
    $images = sydney_images()[$key] ?? [];
    return $data + ['reg' => $reg[$key], 'images' => $images];
}

// Small image card used by parent-page links, siblings and the homepage.
function sydney_thumb(string $key): array
{
    $img = sydney_images()[$key]['a'] ?? [];
    return $img;
}

// "Looking for X in Sydney?" section for a parent service page. Echoes markup.
function sydney_parent_section(string $key): void
{
    $reg = sydney_registry();
    if (!isset($reg[$key])) {
        return;
    }
    $r = $reg[$key];
    $img = sydney_thumb($key);
    $h = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
    echo "\n    <!-- SYDNEY PAGE LINK: sends visitors (and authority) from this national service page to its Sydney page -->\n";
    echo '    <section class="agency-section sy-parent-link fade-up"><div class="container"><div class="agency-grid">' . "\n";
    echo '        <div class="agency-text"><span class="eyebrow">SYDNEY BUSINESSES</span><h2>' . $h($r['heading']) . '</h2><p>' . $h($r['blurb']) . '</p>';
    echo '<a href="' . $h($r['path']) . '" class="btn-primary">' . $h(strtoupper($r['cta'])) . ' <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>';
    if ($key === 'seo') {
        $links = [];
        foreach (sydney_area_registry() as $a) {
            $links[] = '<a href="' . $h($a['path']) . '">' . $h($a['suburb']) . '</a>';
        }
        echo '<p class="sy-areas-line"><strong>Local SEO pages:</strong> ' . implode(' &middot; ', $links) . '</p>';
    }
    echo '</div>' . "\n";
    if ($img) {
        echo '        <a href="' . $h($r['path']) . '" class="agency-image-wrapper agency-image-natural" aria-label="' . $h($r['label']) . '"><img loading="lazy" decoding="async" src="' . $h($img['file_small']) . '" srcset="' . $h($img['file_small']) . ' ' . (int) $img['w_small'] . 'w, ' . $h($img['file']) . ' ' . (int) $img['w'] . 'w" sizes="(max-width: 900px) 92vw, 560px" width="' . (int) $img['w'] . '" height="' . (int) $img['h'] . '" alt="' . $h($img['alt']) . '"></a>' . "\n";
    }
    echo '    </div></div></section>' . "\n";
}

// The Sydney page most relevant to a blog post (by its title, slug and category), or null when none
// fits. blog-post.php uses it to link each article to the matching Sydney service page.
function sydney_key_for_post(array $post): ?string
{
    $text = strtolower(($post['title'] ?? '') . ' ' . ($post['slug'] ?? '') . ' ' . ($post['category'] ?? ''));
    $rules = [
        'ecommerce' => '/shopify|woocommerce|ecommerce|online[- ]store|online[- ]shop/',
        'ppc-advertising' => '/google[- ]ads|\bppc\b|ad[- ]spend|paid (search|social)|\badwords\b/',
        'social-media' => '/social[- ]media|facebook|instagram|tiktok|linkedin/',
        'graphic-design' => '/\blogo|branding|brand[- ]identity|graphic/',
        'seo' => '/\bseo\b|google[- ]maps|local[- ]seo|search[- ]engine|rank(ing)?\b|business profile/',
        'website-development' => '/website|web[- ]design|landing[- ]page|conversion/',
    ];
    foreach ($rules as $key => $re) {
        if (preg_match($re, $text)) {
            return $key;
        }
    }
    return null;
}

// ---------------------------------------------------------------------------------------------
// Suburb SEO pages under the SEO Sydney hub: /seo/sydney/<suburb>. Same renderer and template as the
// service pages (sydney-area.php sets $sy['type'] = 'area'); copy in includes/sydney-areas/<key>.php.
// ---------------------------------------------------------------------------------------------
function sydney_area_registry(): array
{
    return [
        'marsden-park' => ['path' => '/seo/sydney/marsden-park', 'label' => 'SEO Marsden Park', 'suburb' => 'Marsden Park',
            'teaser' => 'Local SEO for new-estate service businesses, retailers and trade suppliers around Sydney Business Park.'],
        'rouse-hill' => ['path' => '/seo/sydney/rouse-hill', 'label' => 'SEO Rouse Hill', 'suburb' => 'Rouse Hill',
            'teaser' => 'Clearer service pages and local discovery for family services, appointment-led and town-centre businesses.'],
        'blacktown' => ['path' => '/seo/sydney/blacktown', 'label' => 'SEO Blacktown', 'suburb' => 'Blacktown',
            'teaser' => 'Local SEO for repairs, trades, health and business-to-business services serving Blacktown and nearby suburbs.'],
        'penrith' => ['path' => '/seo/sydney/penrith', 'label' => 'SEO Penrith', 'suburb' => 'Penrith',
            'teaser' => 'Search help for Penrith tourism, health, education and trade businesses reaching a wide regional catchment.'],
        'castle-hill' => ['path' => '/seo/sydney/castle-hill', 'label' => 'SEO Castle Hill', 'suburb' => 'Castle Hill',
            'teaser' => 'Local SEO for high-consideration services, practices and advisers in Castle Hill.'],
    ];
}

function sydney_area_key_for_path(string $path): ?string
{
    $path = rtrim($path, '/');
    foreach (sydney_area_registry() as $key => $a) {
        if ($a['path'] === $path) {
            return $key;
        }
    }
    return null;
}

// Full content for one suburb page (same shape as sydney_page()), or null.
function sydney_area_page(string $key): ?array
{
    $reg = sydney_area_registry();
    if (!isset($reg[$key]) || !is_file(__DIR__ . '/sydney-areas/' . $key . '.php')) {
        return null;
    }
    $data = require __DIR__ . '/sydney-areas/' . $key . '.php';
    $images = (require __DIR__ . '/sydney-areas/images.php')[$key] ?? [];
    $a = $reg[$key];
    return $data + ['reg' => ['path' => $a['path'], 'label' => $a['label'], 'teaser' => $a['teaser']], 'images' => $images];
}

// Either kind of page for a request path ("/seo/sydney" or "/seo/sydney/penrith"), or null.
function sydney_any_page_for_path(string $path): ?array
{
    if (($k = sydney_key_for_path($path)) !== null) {
        return sydney_page($k);
    }
    if (($k = sydney_area_key_for_path($path)) !== null) {
        return sydney_area_page($k);
    }
    return null;
}
