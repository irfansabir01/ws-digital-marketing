<?php
// Layout for the Sydney service pages. Expects $sy (see sydney_page()) from sydney-service.php.
// Everything is rendered from the page's content file, so each page is unique copy in one
// consistent design: hero, intro, jump list, content sections, service-area map, case
// studies, FAQ, related links and the enquiry form.

$e = static fn($v): string => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$base = SEO_SITE_BASE;
$reg = $sy['reg'];
$img = $sy['images'];
$url = $base . $sy['path'];
$short = $sy['short'];
$isArea = ($sy['type'] ?? '') === 'area';      // suburb page under /seo/sydney (see sydney-area.php)
$parentLabelPlain = html_entity_decode($sy['parent_label'], ENT_QUOTES, 'UTF-8');
// Breadcrumb trail, shared by the visible crumbs and the BreadcrumbList schema.
$crumbs = $isArea
    ? [['Home', '/'], ['Services', '/services'], ['Search Engine Optimisation', '/seo'], ['SEO Sydney', '/seo/sydney'], [$reg['label'], $sy['path']]]
    : [['Home', '/'], ['Services', '/services'], [$parentLabelPlain, $sy['parent_path']], [$reg['label'], $sy['path']]];

// Responsive <img> for one of this page's images (a, b or c).
$syImg = static function (string $slot, string $sizes, string $class = '', bool $eager = false) use ($img, $e): string {
    if (empty($img[$slot])) {
        return '';
    }
    $i = $img[$slot];
    return '<img' . ($class !== '' ? ' class="' . $e($class) . '"' : '') . ' src="' . $e($i['file']) . '" srcset="' . $e($i['file_small']) . ' ' . (int) $i['w_small'] . 'w, ' . $e($i['file']) . ' ' . (int) $i['w'] . 'w" sizes="' . $e($sizes) . '" width="' . (int) $i['w'] . '" height="' . (int) $i['h'] . '" alt="' . $e($i['alt']) . '" ' . ($eager ? 'fetchpriority="high"' : 'loading="lazy"') . ' decoding="async">';
};

// ------------------------------------------------------------------ meta + schema
$page_title = $sy['meta_title'];
$page_no_suffix = true;                     // the title already carries the brand ("... | W&S")
$page_description = $sy['meta_description'];
$page_og_image = $img['og']['file'] ?? '';
$h1Plain = $isArea ? trim($sy['h1_main'] . ' ' . $sy['h1_sub']) : $sy['h1_main'];
$page_og_image_alt = ($isArea ? $h1Plain : $sy['h1_main']) . ' - W&S Digital Marketing';

$faqForSchema = array_map(static fn($f) => ['q' => html_entity_decode(strip_tags($f['q']), ENT_QUOTES, 'UTF-8'), 'a' => html_entity_decode(strip_tags($f['a']), ENT_QUOTES, 'UTF-8')], $sy['faqs']);
$catalog = [];
foreach ($sy['sections'] as $sec) {
    if ($sec['kind'] === 'features') {
        foreach ($sec['subs'] as $sub) {
            $catalog[] = ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $sub['title']]];
        }
    }
}
$provider = [
    '@type' => 'ProfessionalService',
    'name' => 'W&S Digital Marketing',
    'alternateName' => ['WS Digital Marketing', 'W and S Digital Marketing'],
    'url' => $base . '/',
    'image' => $base . '/images/og-default.jpg',
    'logo' => $base . '/images/logo-ws.webp',
    'telephone' => '+61403889630',
    'email' => 'info@wsdigitalmarketing.com.au',
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => '3 Weebill St', 'addressLocality' => 'Melonba', 'addressRegion' => 'NSW', 'postalCode' => '2765', 'addressCountry' => 'AU'],
    'sameAs' => ['https://www.facebook.com/w.and.s.digital.marketing', 'https://www.instagram.com/wsdigitalmarketing', 'https://www.linkedin.com/company/w-s-digital-marketing/'],
];
$imageNodes = [];
foreach (['a', 'b', 'c'] as $slot) {
    if (!empty($img[$slot])) {
        $imageNodes[] = ['@type' => 'ImageObject', '@id' => $url . '#image-' . $slot, 'url' => $base . $img[$slot]['file'], 'contentUrl' => $base . $img[$slot]['file'], 'width' => $img[$slot]['w'], 'height' => $img[$slot]['h'], 'caption' => $img[$slot]['alt'], 'inLanguage' => 'en-AU'];
    }
}
if (!empty($img['og'])) {
    $imageNodes[] = ['@type' => 'ImageObject', '@id' => $url . '#primaryimage', 'url' => $base . $img['og']['file'], 'contentUrl' => $base . $img['og']['file'], 'width' => $img['og']['w'], 'height' => $img['og']['h'], 'caption' => ($isArea ? $h1Plain : $sy['h1_main']) . ' - W&S Digital Marketing', 'inLanguage' => 'en-AU'];
}
// Where the service is offered: the suburb page names its own suburb and the nearby areas the copy
// says are genuinely served, inside Sydney; the service pages name Sydney as a whole.
$areaServed = [
    ['@type' => 'City', 'name' => 'Sydney'],
    ['@type' => 'AdministrativeArea', 'name' => 'Greater Sydney, New South Wales'],
];
if ($isArea) {
    $sydneyPlace = ['@type' => 'City', 'name' => 'Sydney'];
    $areaServed = [['@type' => 'Place', 'name' => $sy['suburb'], 'address' => ['@type' => 'PostalAddress', 'addressLocality' => $sy['suburb'], 'addressRegion' => 'NSW', 'addressCountry' => 'AU'], 'containedInPlace' => $sydneyPlace]];
    foreach ($sy['nearby'] as $nb) {
        $areaServed[] = ['@type' => 'Place', 'name' => $nb, 'address' => ['@type' => 'PostalAddress', 'addressLocality' => $nb, 'addressRegion' => 'NSW', 'addressCountry' => 'AU'], 'containedInPlace' => $sydneyPlace];
    }
    $areaServed[] = ['@type' => 'AdministrativeArea', 'name' => 'Greater Sydney, New South Wales'];
}
$nodes = [
    [
        '@type' => 'WebPage', '@id' => $url . '#webpage', 'url' => $url, 'name' => $sy['meta_title'], 'description' => $sy['og_description'],
        'inLanguage' => 'en-AU', 'isPartOf' => ['@id' => $base . '/#website'], 'primaryImageOfPage' => ['@id' => $url . '#primaryimage'],
        'breadcrumb' => ['@id' => $url . '#breadcrumb'], 'about' => ['@id' => $url . '#service'], 'mainEntity' => ['@id' => $url . '#service'],
    ],
    [
        '@type' => 'Service', '@id' => $url . '#service', 'name' => $isArea ? $h1Plain : $sy['h1_main'], 'serviceType' => $sy['service'],
        'description' => $sy['og_description'], 'url' => $url, 'image' => $base . ($img['og']['file'] ?? '/images/og-default.jpg'),
        'provider' => $provider,
        'areaServed' => $areaServed,
        'availableChannel' => ['@type' => 'ServiceChannel', 'serviceUrl' => $base . '/contact', 'servicePhone' => ['@type' => 'ContactPoint', 'telephone' => '+61403889630', 'contactType' => 'customer service', 'areaServed' => 'AU', 'availableLanguage' => 'English']],
    ] + ($catalog ? ['hasOfferCatalog' => ['@type' => 'OfferCatalog', 'name' => $short . ' services for Sydney businesses', 'itemListElement' => $catalog]] : []),
    seo_faq_schema($faqForSchema) + ['@id' => $url . '#faq'],
    seo_breadcrumb_schema($crumbs) + ['@id' => $url . '#breadcrumb'],
];
$extra_head = seo_graph_tag(array_merge($nodes, $imageNodes));

include __DIR__ . '/../header.php';

// ------------------------------------------------------------------ small helpers
$paras = static function (array $list): string {
    // A paragraph marked '§LINK§' is a one-line pointer to another page: shown as a highlighted link bar.
    return implode('', array_map(static fn($p) => strpos($p, '§LINK§') === 0
        ? '<p class="sy-linkline"><i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i><span>' . substr($p, strlen('§LINK§')) . '</span></p>'
        : '<p>' . $p . '</p>', $list));
};
$tocItems = [];
foreach ($sy['sections'] as $sec) {
    $tocItems[] = [$sec['id'], $sec['title']];
}
if ($sy['key'] === 'seo') {
    $tocItems[] = ['sydney-seo-areas', 'SEO by Sydney area'];
}
$tocItems[] = ['sydney-coverage', $isArea ? 'Nearby areas and the Sydney hub' : 'Where we work across Sydney'];
$tocItems[] = ['faqs', 'Frequently asked questions'];
$areas = ['Marsden Park', 'Rouse Hill', 'Hills District', 'Blacktown', 'Parramatta', 'Inner West', 'North Sydney', 'Sydney CBD'];
$coverageText = [
    'seo' => '',
    'website-development' => 'Our Sydney website projects are planned around where your customers are. From Blacktown and Parramatta to the Hills District and the CBD, we shape structure, content and service-area information around the places you genuinely serve, without padding your site with near-identical suburb pages.',
    'ppc-advertising' => 'Location targeting should follow the suburbs you can actually serve. We set up campaigns around real service areas across Greater Sydney, review where clicks and enquiries come from, and adjust targeting when the evidence points somewhere different.',
    'social-media' => 'A café in Newtown and a supplier in Alexandria need different content. We shape social plans around the part of Greater Sydney your customers live and work in, and the local detail that makes your business recognisable.',
    'graphic-design' => 'Design for Sydney businesses has to work wherever customers meet it: shopfront signage, printed quotes, vehicle graphics, social feeds and websites. We plan assets around where your material will actually appear across Greater Sydney.',
    'ecommerce' => 'Many Sydney stores ship well beyond the city, but local customers still need clear pickup, delivery and returns information. We design store structure and delivery messaging around how your business really fulfils orders from Greater Sydney.',
][$sy['key']] ?? '';
$hasAreasSection = (bool) array_filter($sy['sections'], static fn($s) => $s['kind'] === 'areas');

// Case studies for this service (the ones ticked for it in admin), at most three.
$syCases = [];
try {
    $syCases = array_slice(service_page_case_studies($isArea ? 'seo' : $sy['key']), 0, 3);
} catch (Throwable $ex) {
    $syCases = [];
}
// Related blog posts (only those that exist and are published).
$syPosts = [];
try {
    $in = implode(',', array_fill(0, count($sy['blog']), '?'));
    try {
        $st = get_db()->prepare('SELECT title, slug, category, excerpt, image_path, image_alt, created_at FROM blog_posts WHERE is_published = 1 AND slug IN (' . $in . ')');
    } catch (PDOException $ex) {
        $st = get_db()->prepare('SELECT title, slug, category, excerpt, image_path, created_at FROM blog_posts WHERE is_published = 1 AND slug IN (' . $in . ')');
    }
    $st->execute($sy['blog']);
    $bySlug = [];
    foreach ($st->fetchAll() as $row) {
        $bySlug[$row['slug']] = $row;
    }
    foreach ($sy['blog'] as $slug) {
        if (isset($bySlug[$slug])) {
            $syPosts[] = $bySlug[$slug];
        }
    }
    if ($syPosts) {
        require_once __DIR__ . '/blog-seo.php';
    }
} catch (Throwable $ex) {
    $syPosts = [];
}
$registry = sydney_registry();
?>

    <!-- HERO -->
    <section class="hero sy-hero" style="padding-bottom: clamp(40px, 7vw, 70px);">
        <div class="container hero-content">
            <nav class="sy-crumbs" aria-label="Breadcrumb">
                <ol>
                    <li><a href="/">Home</a></li>
                    <?php foreach (array_slice($crumbs, 1, -1) as [$cLabel, $cPath]): ?>
                    <li><a href="<?php echo $e($cPath); ?>"><?php echo $e($cLabel); ?></a></li>
                    <?php endforeach; ?>
                    <li aria-current="page"><?php echo $e($reg['label']); ?></li>
                </ol>
            </nav>
            <span class="eyebrow fade-up"><?php echo $e(strtoupper($short)); ?> &middot; <?php echo $e(strtoupper($isArea ? $sy['suburb'] : 'Sydney')); ?></span>
            <h1 class="fade-up"><?php echo $e($sy['h1_main']); ?><?php if ($sy['h1_sub'] !== ''): ?> <br><span class="text-gradient"><?php echo $e($sy['h1_sub']); ?></span><?php endif; ?></h1>
            <p class="hero-subtitle fade-up"><?php echo $e($sy['lead']); ?></p>
            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary"><?php echo $e($sy['cta_label']); ?> <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="<?php echo $e($sy['parent_path']); ?>" class="btn-secondary"><?php echo $isArea ? 'VIEW SEO SYDNEY' : 'OUR ' . $e(strtoupper($short)) . ' SERVICE'; ?></a>
            </div>
            <ul class="sy-trust fade-up">
                <li><i class="fa-solid fa-location-dot"></i> Based in Melonba, NSW</li>
                <li><i class="fa-solid fa-city"></i> <?php echo $isArea ? 'Local SEO for ' . $e($sy['suburb']) : 'Serving Greater Sydney'; ?></li>
                <li><i class="fa-solid fa-calendar-check"></i> Month-to-month plans</li>
            </ul>
        </div>
    </section>

    <!-- INTRO -->
    <section class="agency-section sy-intro fade-up">
        <div class="container">
            <div class="agency-grid">
                <div class="agency-text">
                    <span class="eyebrow">AT A GLANCE</span>
                    <?php echo $paras($sy['intro']); ?>
                    <ul class="sy-facts">
                        <li><i class="fa-solid fa-bullseye"></i><span><strong>Service</strong> <?php echo $e(html_entity_decode($sy['service'], ENT_QUOTES, 'UTF-8')); ?> for <?php echo $isArea ? $e($sy['suburb']) : 'Sydney'; ?> businesses</span></li>
                        <li><i class="fa-solid fa-location-dot"></i><span><strong><?php echo $isArea ? 'Nearby areas' : 'Coverage'; ?></strong> <?php echo $isArea ? $e($sy['nearby_text']) : 'Greater Sydney, from our office in Melonba'; ?></span></li>
                        <li><i class="fa-solid fa-file-contract"></i><span><strong>Plans</strong> Month-to-month, no lock-in contracts</span></li>
                        <li><i class="fa-solid fa-comments"></i><span><strong>Next step</strong> A zero-obligation review of what you have now</span></li>
                    </ul>
                </div>
                <div class="agency-image-wrapper agency-image-natural"><?php echo $syImg('a', '(max-width: 900px) 92vw, 560px'); ?></div>
            </div>
            <details class="sy-toc">
                <summary>On this page <span><?php echo count($tocItems); ?> sections</span></summary>
                <ol>
                    <?php foreach ($tocItems as [$tid, $ttitle]): ?>
                    <li><a href="#<?php echo $e($tid); ?>"><?php echo $e($ttitle); ?></a></li>
                    <?php endforeach; ?>
                </ol>
            </details>
        </div>
    </section>

<?php
$eyebrows = ['features' => strtoupper($short) . ' SERVICES', 'steps' => 'HOW IT WORKS', 'tiles' => 'WHO WE HELP', 'checklist' => 'BEFORE YOU DECIDE', 'related' => 'JOINED-UP MARKETING'];
foreach ($sy['sections'] as $i => $sec):
    $alt = ($i % 2 === 1) ? ' sy-alt' : '';
    $kind = $sec['kind'];
    $sid = $e($sec['id']);
?>
    <!-- SECTION: <?php echo $e($sec['title']); ?> -->
<?php if ($kind === 'features'): ?>
    <section class="sy-section<?php echo $alt; ?> fade-up" id="<?php echo $sid; ?>">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow"><?php echo $e($eyebrows['features']); ?></span>
                <h2 class="sy-h2"><?php echo $e($sec['title']); ?></h2>
                <?php if ($sec['paras']): ?><div class="sy-lead"><?php echo $paras($sec['paras']); ?></div><?php endif; ?>
            </div>
            <div class="sy-card-grid">
                <?php foreach ($sec['subs'] as $n => $sub): ?>
                <article class="sy-card">
                    <div class="sy-card-top"><span class="sy-card-icon"><i class="<?php echo $e(strpos($sub['icon'], 'fa-brands') === 0 ? $sub['icon'] : 'fa-solid ' . $sub['icon']); ?>"></i></span><span class="sy-card-num" aria-hidden="true"><?php echo sprintf('%02d', $n + 1); ?></span></div>
                    <h3><?php echo $e($sub['title']); ?></h3>
                    <?php echo $paras($sub['paras']); ?>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php elseif ($kind === 'numbered'): ?>
    <section class="sy-section<?php echo $alt; ?> fade-up" id="<?php echo $sid; ?>">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">KEY DECISIONS</span>
                <h2 class="sy-h2"><?php echo $e($sec['title']); ?></h2>
                <?php if ($sec['paras']): ?><div class="sy-lead"><?php echo $paras($sec['paras']); ?></div><?php endif; ?>
            </div>
            <div class="sy-card-grid sy-card-grid-num">
                <?php foreach ($sec['subs'] as $n => $sub): ?>
                <article class="sy-card">
                    <div class="sy-card-top"><span class="sy-card-badge"><?php echo sprintf('%02d', $n + 1); ?></span></div>
                    <h3><?php echo $e($sub['title']); ?></h3>
                    <?php echo $paras($sub['paras']); ?>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php elseif ($kind === 'chapter'): ?>
    <section class="sy-section<?php echo $alt; ?> fade-up" id="<?php echo $sid; ?>">
        <div class="container">
            <div class="sy-chapter">
                <div class="sy-chapter-head">
                    <span class="sy-chapter-num" aria-hidden="true"><?php echo sprintf('%02d', $i + 1); ?></span>
                    <h2 class="sy-h2"><?php echo $e($sec['title']); ?></h2>
                </div>
                <div class="sy-chapter-body"><?php echo $paras($sec['paras']); ?></div>
            </div>
        </div>
    </section>

<?php elseif ($kind === 'timeline'): ?>
    <section class="sy-section<?php echo $alt; ?> fade-up" id="<?php echo $sid; ?>">
        <div class="container">
            <div class="sy-timeline-wrap">
                <span class="eyebrow">STEP BY STEP</span>
                <h2 class="sy-h2"><?php echo $e($sec['title']); ?></h2>
                <ol class="sy-timeline">
                    <?php foreach ($sec['paras'] as $n => $tp): ?>
                    <li class="sy-phase"><span class="sy-phase-dot"><?php echo sprintf('%02d', $n + 1); ?></span><div class="sy-phase-card"><?php echo $paras([$tp]); ?></div></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </section>

<?php elseif ($kind === 'steps'): ?>
    <section class="sy-section methodology-section<?php echo $alt; ?> fade-up" id="<?php echo $sid; ?>">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow"><?php echo $e($eyebrows['steps']); ?></span>
                <h2 class="sy-h2"><?php echo $e($sec['title']); ?></h2>
                <?php if ($sec['paras']): ?><div class="sy-lead"><?php echo $paras($sec['paras']); ?></div><?php endif; ?>
            </div>
            <div class="methodology-steps">
                <?php foreach ($sec['subs'] as $n => $sub): ?>
                <div class="m-step">
                    <div class="m-step-num"><?php echo sprintf('%02d', $n + 1); ?></div>
                    <div class="m-step-content">
                        <h3><i class="<?php echo $e(strpos($sub['icon'], 'fa-brands') === 0 ? $sub['icon'] : 'fa-solid ' . $sub['icon']); ?>"></i> <?php echo $e(preg_replace('/^(Step\s*)?\d+[.:]\s*/i', '', $sub['title'])); ?></h3>
                        <?php echo $paras($sub['paras']); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php elseif ($kind === 'tiles'): ?>
    <section class="sy-section<?php echo $alt; ?> fade-up" id="<?php echo $sid; ?>">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow"><?php echo $e($eyebrows['tiles']); ?></span>
                <h2 class="sy-h2"><?php echo $e($sec['title']); ?></h2>
                <?php if ($sec['paras']): ?><div class="sy-lead"><?php echo $paras($sec['paras']); ?></div><?php endif; ?>
            </div>
            <div class="sy-tile-grid sy-tiles-<?php echo count($sec['subs']); ?>">
                <?php foreach ($sec['subs'] as $sub): ?>
                <article class="industry-tile sy-tile">
                    <div class="industry-tile-icon"><i class="<?php echo $e(strpos($sub['icon'], 'fa-brands') === 0 ? $sub['icon'] : 'fa-solid ' . $sub['icon']); ?>"></i></div>
                    <h3><?php echo $e($sub['title']); ?></h3>
                    <?php echo $paras($sub['paras']); ?>
                </article>
                <?php endforeach; ?>
            </div>
            <?php if (!empty($sec['after'])): ?><div class="sy-narrow sy-after"><?php echo $paras($sec['after']); ?></div><?php endif; ?>
        </div>
    </section>

<?php elseif ($kind === 'callout'): ?>
    <section class="sy-section<?php echo $alt; ?> fade-up" id="<?php echo $sid; ?>">
        <div class="container">
            <div class="sy-callout">
                <div class="sy-callout-icon"><i class="fa-solid <?php echo preg_match('/report|measure/i', $sec['title']) ? 'fa-chart-line' : 'fa-coins'; ?>"></i></div>
                <div class="sy-callout-body">
                    <h2 class="sy-h2"><?php echo $e($sec['title']); ?></h2>
                    <?php echo $paras($sec['paras']); ?>
                    <?php if (preg_match('/cost|spend|price|budget/i', $sec['title'])): ?>
                    <a href="/pricing" class="btn-secondary sy-callout-btn">SEE PRICING PLANS <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

<?php elseif ($kind === 'checklist'): ?>
    <section class="sy-section<?php echo $alt; ?> fade-up" id="<?php echo $sid; ?>">
        <div class="container">
            <div class="sy-narrow">
                <span class="eyebrow"><?php echo $e($eyebrows['checklist']); ?></span>
                <h2 class="sy-h2"><?php echo $e($sec['title']); ?></h2>
                <ul class="sy-checklist">
                    <?php foreach ($sec['paras'] as $p): ?>
                    <li><span class="sy-check"><i class="fa-solid fa-check"></i></span><div><?php echo $paras([$p]); ?></div></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>

<?php elseif ($kind === 'areas'): ?>
    <section class="sy-section<?php echo $alt; ?> fade-up" id="<?php echo $sid; ?>">
        <div class="container">
            <div class="sy-split">
                <div class="sy-split-text">
                    <h2 class="sy-h2"><?php echo $e($sec['title']); ?></h2>
                    <?php echo $paras($sec['paras']); ?>
                    <?php $areaPages = []; foreach (sydney_area_registry() as $ar) { $areaPages[$ar['suburb']] = $ar['path']; } ?>
                    <ul class="sy-chips" aria-label="Sydney areas we work with"><?php foreach ($areas as $a): ?><li><i class="fa-solid fa-location-dot"></i> <?php echo isset($areaPages[$a]) ? '<a href="' . $e($areaPages[$a]) . '">' . $e($a) . '</a>' : $e($a); ?></li><?php endforeach; ?></ul>
                </div>
                <div class="sy-split-media"><?php echo $syImg('c', '(max-width: 900px) 92vw, 560px'); ?></div>
            </div>
        </div>
    </section>

<?php else: /* prose + related */ ?>
    <section class="sy-section<?php echo $alt; ?> fade-up" id="<?php echo $sid; ?>">
        <div class="container">
            <?php if (!empty($sec['image']) && !empty($img[$sec['image']])): ?>
            <div class="sy-split sy-split-flip">
                <div class="sy-split-text">
                    <h2 class="sy-h2"><?php echo $e($sec['title']); ?></h2>
                    <?php echo $paras($sec['paras']); ?>
                </div>
                <div class="sy-split-media"><?php echo $syImg($sec['image'], '(max-width: 900px) 92vw, 560px'); ?></div>
            </div>
            <?php else: ?>
            <div class="sy-narrow">
                <h2 class="sy-h2"><?php echo $e($sec['title']); ?></h2>
                <?php echo $paras($sec['paras']); ?>
            </div>
            <?php endif; ?>
            <?php if ($kind === 'related' && !empty($sec['related'])): ?>
            <div class="sy-link-grid" role="list">
                <?php foreach ($sec['related'] as $rk): if (!isset($registry[$rk])) { continue; } $rr = $registry[$rk]; ?>
                <a class="sy-link-card" role="listitem" href="<?php echo $e($rr['path']); ?>">
                    <span class="sy-link-eyebrow">Sydney service</span>
                    <strong><?php echo $e($rr['label']); ?></strong>
                    <span><?php echo $e($rr['teaser']); ?></span>
                    <em>Read the guide <i class="fa-solid fa-arrow-right"></i></em>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>
<?php endforeach; ?>

<?php if ($isArea): ?>
    <!-- NEARBY AREAS + SYDNEY HUB -->
    <section class="sy-section<?php echo (count($sy['sections']) % 2 === 1) ? ' sy-alt' : ''; ?> fade-up" id="sydney-coverage">
        <div class="container">
            <div class="sy-split">
                <div class="sy-split-text">
                    <span class="eyebrow">LOCAL CATCHMENT</span>
                    <h2 class="sy-h2">SEO For <?php echo $e($sy['suburb']); ?> And Nearby Areas</h2>
                    <p>We plan <?php echo $e($sy['suburb']); ?> work around the customers you genuinely serve, including nearby areas such as <?php echo $e($sy['nearby_text']); ?>. Where a place needs its own page we say so; where one clear service-area section is enough, we keep it simple. For the wider offer across the city, see our <a href="/seo/sydney">Sydney SEO overview</a>.</p>
                    <ul class="sy-chips" aria-label="Nearby areas"><?php foreach ($sy['nearby'] as $a): ?><li><i class="fa-solid fa-location-dot"></i> <?php echo $e($a); ?></li><?php endforeach; ?></ul>
                    <p class="sy-note">Our team is based in Melonba, in Sydney&rsquo;s north-west. We do not operate a physical office in every suburb &mdash; we shape the work around the places you serve.</p>
                </div>
                <div class="sy-split-media"><?php echo $syImg('c', '(max-width: 900px) 92vw, 560px'); ?></div>
            </div>
        </div>
    </section>
<?php elseif (!$hasAreasSection && $coverageText !== ''): ?>
    <!-- SERVICE AREA -->
    <section class="sy-section<?php echo (count($sy['sections']) % 2 === 1) ? ' sy-alt' : ''; ?> fade-up" id="sydney-coverage">
        <div class="container">
            <div class="sy-split">
                <div class="sy-split-text">
                    <span class="eyebrow">SERVICE AREA</span>
                    <h2 class="sy-h2"><?php echo $e($short); ?> For Businesses Across Greater Sydney</h2>
                    <p><?php echo $e($coverageText); ?></p>
                    <ul class="sy-chips" aria-label="Sydney areas we work with"><?php foreach ($areas as $a): ?><li><i class="fa-solid fa-location-dot"></i> <?php echo $e($a); ?></li><?php endforeach; ?></ul>
                    <p class="sy-note">Our team is based in Melonba, in Sydney&rsquo;s north-west. We do not operate a physical office in every suburb &mdash; we shape the work around the places you serve.</p>
                </div>
                <div class="sy-split-media"><?php echo $syImg('c', '(max-width: 900px) 92vw, 560px'); ?></div>
            </div>
        </div>
    </section>
<?php elseif ($hasAreasSection): ?>
    <span id="sydney-coverage" aria-hidden="true"></span>
<?php endif; ?>

<?php if ($sy['key'] === 'seo'): ?>
    <!-- SEO BY SYDNEY AREA: the hub links to each suburb page -->
    <section class="sy-section sy-alt fade-up" id="sydney-seo-areas">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">LOCAL SEO PAGES</span>
                <h2 class="sy-h2">SEO By Sydney Area</h2>
                <p class="sy-lead-single">Where a suburb has distinct customers, competition and search needs, we explain how local SEO applies there. Choose the area closest to where your customers are.</p>
            </div>
            <div class="sy-link-grid" role="list">
                <?php foreach (sydney_area_registry() as $ar): ?>
                <a class="sy-link-card" role="listitem" href="<?php echo $e($ar['path']); ?>">
                    <span class="sy-link-eyebrow">Local SEO</span>
                    <strong><?php echo $e($ar['label']); ?></strong>
                    <span><?php echo $e($ar['teaser']); ?></span>
                    <em>Read the guide <i class="fa-solid fa-arrow-right"></i></em>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php endif; ?>
<?php if ($syCases): ?>
    <!-- SYDNEY CLIENT WORK -->
    <section class="services-section sy-cases fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">CLIENT WORK</span>
                <h2 class="sy-h2">Recent <?php echo $e($short); ?> Projects</h2>
                <p class="sy-lead-single">Real projects for service businesses and online brands. Each case study explains the brief and the work delivered.</p>
            </div>
            <div class="case-grid sy-blog-grid">
                <?php foreach ($syCases as $cs): ?>
                <article class="case-card">
                    <div class="case-image">
                        <span class="case-tag"><?php echo $e($cs['tag']); ?></span>
                        <img loading="lazy" decoding="async" src="<?php echo $e($cs['image_path']); ?>" alt="<?php echo $e($cs['image_alt']); ?>">
                        <h3><?php echo $e(strtoupper($cs['name'])); ?></h3>
                    </div>
                    <div class="case-content">
                        <div><p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 15px;"><?php echo $e(strip_tags($cs['summary'])); ?></p></div>
                        <div><a href="/case-studies/<?php echo $e($cs['slug']); ?>" class="service-link">View Project <i class="fa-solid fa-arrow-right"></i></a></div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: clamp(30px, 4vw, 40px);">
                <a href="/case-studies" class="btn-secondary">VIEW ALL CASE STUDIES <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i></a>
            </div>
        </div>
    </section>
<?php endif; ?>

    <!-- FAQS: all answers are in the HTML on load (the same text is in the FAQPage schema); js/main.js collapses them into an accordion -->
    <section class="hfaq-section sy-faq fade-up" id="faqs">
        <div class="container hfaq-layout">
            <div class="hfaq-intro">
                <span class="eyebrow">FAQS</span>
                <h2><?php echo $e($short); ?> In <?php echo $e($isArea ? $sy['suburb'] : 'Sydney'); ?>: Your Questions Answered</h2>
                <p>Straight answers to the questions <?php echo $e($isArea ? $sy['suburb'] : 'Sydney'); ?> businesses ask before they enquire. For general questions about working with us, see our <a href="/faq">full FAQ</a>.</p>
                <a href="#contact" class="btn-secondary">SPEAK WITH OUR TEAM <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i></a>
            </div>
            <div class="hfaq-list" data-faq>
                <?php foreach ($sy['faqs'] as $n => $f): ?>
                <div class="hfaq-item">
                    <h3><button type="button" class="hfaq-q" id="syfaq-q<?php echo $n + 1; ?>" aria-expanded="true" aria-controls="syfaq-a<?php echo $n + 1; ?>"><?php echo $e($f['q']); ?></button></h3>
                    <div class="hfaq-answer" id="syfaq-a<?php echo $n + 1; ?>" role="region" aria-labelledby="syfaq-q<?php echo $n + 1; ?>"><p><?php echo $f['a']; ?></p></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php if ($syPosts): ?>
    <!-- RELATED READING -->
    <section class="lb-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">FROM THE BLOG</span>
                <h2 class="sy-h2">Related Reading</h2>
            </div>
            <div class="case-grid sy-blog-grid">
                <?php foreach ($syPosts as $post): ?>
                <article class="case-card blog-card">
                    <div class="case-image">
                        <?php if (!empty($post['category'])): ?><span class="case-tag"><?php echo $e($post['category']); ?></span><?php endif; ?>
                        <img loading="lazy" decoding="async" src="<?php echo $e(($post['image_path'] ?? '') !== '' ? $post['image_path'] : blog_fallback_image((string) ($post['category'] ?? ''))); ?>" alt="<?php echo $e(($post['image_alt'] ?? '') !== '' ? $post['image_alt'] : $post['title']); ?>">
                    </div>
                    <div class="case-content">
                        <div>
                            <h3 class="bc-title"><?php echo $e($post['title']); ?></h3>
                            <p class="lb-excerpt"><?php echo $e($post['excerpt']); ?></p>
                        </div>
                        <div><a href="/blog/<?php echo urlencode($post['slug']); ?>" class="service-link" aria-label="Read More &ndash; <?php echo $e($post['title']); ?>">Read More <i class="fa-solid fa-arrow-right"></i></a></div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

    <!-- MORE SERVICES -->
    <section class="sy-section sy-alt fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">KEEP EXPLORING</span>
                <h2 class="sy-h2"><?php echo $isArea ? 'More Local SEO Help Around Sydney' : 'More Ways We Help Sydney Businesses'; ?></h2>
            </div>
            <div class="sy-link-grid sy-link-grid-3" role="list">
<?php if ($isArea): ?>
                <a class="sy-link-card" role="listitem" href="/seo/sydney">
                    <span class="sy-link-eyebrow">Sydney hub</span>
                    <strong>SEO Sydney</strong>
                    <span><?php echo $e($registry['seo']['teaser']); ?></span>
                    <em>Read the guide <i class="fa-solid fa-arrow-right"></i></em>
                </a>
                <?php foreach ($sy['neighbors'] as $nk): $na = sydney_area_registry()[$nk] ?? null; if (!$na) { continue; } ?>
                <a class="sy-link-card" role="listitem" href="<?php echo $e($na['path']); ?>">
                    <span class="sy-link-eyebrow">Nearby area</span>
                    <strong><?php echo $e($na['label']); ?></strong>
                    <span><?php echo $e($na['teaser']); ?></span>
                    <em>Read the guide <i class="fa-solid fa-arrow-right"></i></em>
                </a>
                <?php endforeach; ?>
                <a class="sy-link-card" role="listitem" href="/ppc-advertising/sydney">
                    <span class="sy-link-eyebrow">Paid search</span>
                    <strong><?php echo $e($registry['ppc-advertising']['label']); ?></strong>
                    <span><?php echo $e($registry['ppc-advertising']['teaser']); ?></span>
                    <em>Read the guide <i class="fa-solid fa-arrow-right"></i></em>
                </a>
                <a class="sy-link-card" role="listitem" href="/website-development/sydney">
                    <span class="sy-link-eyebrow">Website</span>
                    <strong><?php echo $e($registry['website-development']['label']); ?></strong>
                    <span><?php echo $e($registry['website-development']['teaser']); ?></span>
                    <em>Read the guide <i class="fa-solid fa-arrow-right"></i></em>
                </a>
<?php else: ?>
                <a class="sy-link-card" role="listitem" href="<?php echo $e($sy['parent_path']); ?>">
                    <span class="sy-link-eyebrow">National service</span>
                    <strong><?php echo $e(html_entity_decode($sy['parent_label'], ENT_QUOTES, 'UTF-8')); ?></strong>
                    <span>The full service for businesses across Australia, with case studies and results.</span>
                    <em>View the service <i class="fa-solid fa-arrow-right"></i></em>
                </a>
                <?php foreach ($sy['siblings'] as $rk): if (!isset($registry[$rk])) { continue; } $rr = $registry[$rk]; ?>
                <a class="sy-link-card" role="listitem" href="<?php echo $e($rr['path']); ?>">
                    <span class="sy-link-eyebrow">Sydney service</span>
                    <strong><?php echo $e($rr['label']); ?></strong>
                    <span><?php echo $e($rr['teaser']); ?></span>
                    <em>Read the guide <i class="fa-solid fa-arrow-right"></i></em>
                </a>
                <?php endforeach; ?>
<?php endif; ?>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Free <?php echo $e($short); ?> Review</div>
                    <h2><?php echo $e($sy['cta']['title']); ?></h2>
                    <div class="sy-cta-copy"><?php echo $paras($sy['cta']['paras']); ?></div>
                    <div class="cta-features">
                        <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Review</span>
                        <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Month-To-Month Plans</span>
                        <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Based In Sydney</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                    <?php $cta_default_service = $sy['cta_service']; $cta_button_label = $sy['cta_label']; include __DIR__ . '/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/../footer.php'; ?>
