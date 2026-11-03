<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/case-study-helpers.php';

$slug = $_GET['slug'] ?? '';
$study = null;
try {
    $pdo = get_db();
    $stmt = $pdo->prepare('SELECT * FROM case_studies WHERE slug = ?');
    $stmt->execute([$slug]);
    $study = $stmt->fetch();
} catch (PDOException $e) {
    $study = null;
}

// Still no match: an admin redirect for this exact URL wins first, then a
// permanent (301) redirect to the most relevant live page, falling back to
// /case-studies -- a removed URL should never be left as a 404.
if (!$study && $slug !== '') {
    require_once __DIR__ . '/includes/seo.php';
    seo_try_redirect(seo_normalize_path($_SERVER['REQUEST_URI'] ?? '/'));
    seo_log_404(seo_normalize_path($_SERVER['REQUEST_URI'] ?? '/'));
    seo_smart_redirect('/' . $slug, '/case-studies');
}

if (!$study) {
    header('HTTP/1.0 404 Not Found');
    $page_title = 'Case Study Not Found';
    $page_description = 'This case study could not be found.';
    include 'header.php';
    ?>
    <section class="hero">
        <div class="container hero-content">
            <h1>Case Study Not Found</h1>
            <p class="hero-subtitle">This project may have been moved or renamed.</p>
            <div class="hero-buttons">
                <a href="/case-studies" class="btn-primary">Back To Case Studies</a>
            </div>
        </div>
    </section>
    <?php
    include 'footer.php';
    exit;
}

// This study's stat pairs, skipping any the admin left empty. The section
// shows if the admin's manual has_data checkbox is on OR any stat is
// actually filled in -- either is enough, so filled-in stats can never
// silently fail to appear just because the checkbox was left unticked.
$stats = [];
foreach ([1, 2, 3, 4] as $n) {
    if ($study["stat{$n}_value"] !== '' || $study["stat{$n}_label"] !== '') {
        $stats[] = [$study["stat{$n}_value"], $study["stat{$n}_label"]];
    }
}
$hasResults = (bool) $study['has_data'] || !empty($stats);
$services = array_filter(array_map('trim', explode("\n", $study['services'] ?? '')), fn($s) => $s !== '');

// Dedicated Search Console / Business Profile stat sections -- separate from
// the combined "Results At A Glance" grid above, each only shows if the
// admin has actually filled in at least one of its own values.
$gscStats = [];
if (($study['gsc_clicks'] ?? '') !== '') { $gscStats[] = [$study['gsc_clicks'], 'Organic Clicks']; }
if (($study['gsc_impressions'] ?? '') !== '') { $gscStats[] = [$study['gsc_impressions'], 'Search Impressions']; }
if (($study['gsc_ctr'] ?? '') !== '') { $gscStats[] = [$study['gsc_ctr'] . '%', 'Average CTR']; }
if (($study['gsc_position'] ?? '') !== '') { $gscStats[] = [$study['gsc_position'], 'Average Position']; }
if (($study['gsc_ai_value'] ?? '') !== '') { $gscStats[] = [$study['gsc_ai_value'], 'AI Overview Impressions']; }
$hasGsc = !empty($gscStats);

// Proof-gallery images shown below the GSC/GBP stat grids -- one small
// captioned screenshot per individual metric (not just one combined
// overview), so every stat on the page has its own visible source.
$gscGallery = [];
if (($study['gsc_image'] ?? '') !== '') { $gscGallery[] = [$study['gsc_image'], 'Search Performance']; }
if (($study['gsc_ai_image'] ?? '') !== '') { $gscGallery[] = [$study['gsc_ai_image'], 'Generative AI Features']; }

$gbpStats = [];
if (($study['gbp_interactions'] ?? '') !== '') { $gbpStats[] = [$study['gbp_interactions'], 'Total Interactions']; }
if (($study['gbp_calls'] ?? '') !== '') { $gbpStats[] = [$study['gbp_calls'], 'Phone Calls']; }
if (($study['gbp_directions'] ?? '') !== '') { $gbpStats[] = [$study['gbp_directions'], 'Direction Requests']; }
if (($study['gbp_website_clicks'] ?? '') !== '') { $gbpStats[] = [$study['gbp_website_clicks'], 'Website Clicks']; }
if (($study['gbp_extra_value'] ?? '') !== '' && ($study['gbp_extra_label'] ?? '') !== '') { $gbpStats[] = [$study['gbp_extra_value'], $study['gbp_extra_label']]; }
$hasGbp = !empty($gbpStats);

$gbpGallery = [];
if (($study['gbp_image'] ?? '') !== '') { $gbpGallery[] = [$study['gbp_image'], 'Overview']; }
if (($study['gbp_calls_image'] ?? '') !== '') { $gbpGallery[] = [$study['gbp_calls_image'], 'Phone Calls']; }
if (($study['gbp_directions_image'] ?? '') !== '') { $gbpGallery[] = [$study['gbp_directions_image'], 'Direction Requests']; }
if (($study['gbp_website_image'] ?? '') !== '') { $gbpGallery[] = [$study['gbp_website_image'], 'Website Clicks']; }
if (($study['gbp_extra_image'] ?? '') !== '' && ($study['gbp_extra_label'] ?? '') !== '') { $gbpGallery[] = [$study['gbp_extra_image'], $study['gbp_extra_label']]; }

// Weekly results entered in the admin ("Weekly Stats"): the latest week
// (with the change from the week before) and the running total. Only shows
// once the case study has weekly data.
$weekly = summarise_weekly_stats(fetch_weekly_stats((int) $study['id']));
$weeklyLatestBoxes = $weekly && $weekly['latest'] ? weekly_stat_boxes($weekly['latest'], $weekly['previous']) : [];
$weeklyTotalBoxes = $weekly ? weekly_stat_boxes($weekly['totals']) : [];

// Dedicated Shopify store performance section (sales, sessions, orders,
// conversion rate), each with an optional "vs previous period" growth
// figure. Separate from the "Results At A Glance" stats, so it never
// changes the homepage combined totals. Only shows if a value is filled in.
$shopStats = [];
foreach ([
    'shop_sales' => 'Total Sales',
    'shop_sessions' => 'Sessions',
    'shop_orders' => 'Orders',
    'shop_conversion' => 'Conversion Rate',
] as $field => $label) {
    if (($study[$field] ?? '') !== '') {
        $shopStats[] = [$study[$field], $label, $study[$field . '_growth'] ?? ''];
    }
}
$hasShop = !empty($shopStats);
$shopGallery = [];
if (($study['shop_image'] ?? '') !== '') { $shopGallery[] = [$study['shop_image'], 'Shopify Sales Overview']; }

// Both fall back to the project name/summary when the admin panel's
// dedicated SEO fields are left blank, so existing case studies keep
// working exactly as before without needing to be edited.
// (Forced re-deploy marker: the live server kept serving the pre-SEO-field
// version of this exact file after the first deploy, likely a stale
// PHP opcode cache entry -- this comment guarantees a fresh file size/
// timestamp so the next deploy genuinely overwrites it.)
$page_title = ($study['meta_title'] ?? '') !== '' ? $study['meta_title'] : $study['name'];
$page_og_image = $study['image_path'] ?? '';
// The summary field allows HTML (edited via the admin panel), so the meta
// description -- which must be plain text -- strips tags rather than
// echoing the markup itself.
$page_description = ($study['meta_description'] ?? '') !== '' ? $study['meta_description'] : trim(strip_tags($study['summary']));

// A small "More Case Studies" strip below the content -- next 3 entries in
// display order after the current one (wrapping around), excluding itself.
// Falls back to an empty strip (the rest of the page still renders) rather
// than a fatal error if this second query hits a transient DB problem.
$more = [];
try {
    $allSlugs = $pdo->query('SELECT slug FROM case_studies ORDER BY display_order ASC, name ASC')->fetchAll(PDO::FETCH_COLUMN);
    $currentIndex = array_search($slug, $allSlugs, true);
    $moreSlugs = [];
    for ($i = 1; count($moreSlugs) < 3 && $i < count($allSlugs); $i++) {
        $candidate = $allSlugs[($currentIndex + $i) % count($allSlugs)];
        if ($candidate !== $slug) {
            $moreSlugs[] = $candidate;
        }
    }
    if ($moreSlugs) {
        $placeholders = implode(',', array_fill(0, count($moreSlugs), '?'));
        $moreStmt = $pdo->prepare("SELECT * FROM case_studies WHERE slug IN ($placeholders)");
        $moreStmt->execute($moreSlugs);
        $moreRows = $moreStmt->fetchAll();
        $moreBySlug = [];
        foreach ($moreRows as $row) {
            $moreBySlug[$row['slug']] = $row;
        }
        foreach ($moreSlugs as $moreSlug) {
            if (isset($moreBySlug[$moreSlug])) {
                $more[$moreSlug] = $moreBySlug[$moreSlug];
            }
        }
    }
} catch (PDOException $e) {
    $more = [];
}

include 'header.php';
?>

    <!-- CASE STUDY HERO -->
    <section class="hero" style="padding-bottom: clamp(30px, 6vw, 60px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up"><?php echo htmlspecialchars($study['tag'], ENT_QUOTES, 'UTF-8'); ?></span>
            <h1 class="fade-up"><?php echo htmlspecialchars($study['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
        </div>
    </section>

    <!-- CASE STUDY CONTENT -->
    <section class="agency-section fade-up">
        <div class="container">
            <div class="agency-grid case-study-feature">
                <div class="agency-image-wrapper">
                    <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($study['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($study['image_alt'], ENT_QUOTES, 'UTF-8'); ?>" onerror="this.remove()">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">
                        <?php echo $hasResults ? 'PROJECT RESULTS' : 'PROJECT SCOPE'; ?>
                        <?php if ($hasResults && $study['period']): ?><span style="color: var(--text-muted); font-weight: 600; text-transform: none; letter-spacing: normal;"> &middot; <?php echo htmlspecialchars($study['period'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                    </span>
                    <h2><?php echo $hasResults ? 'What We Delivered' : 'The Engagement'; ?></h2>
                    <div class="agency-summary"><?php echo $study['summary']; ?></div>
                    <?php if (!$hasResults): ?>
                    <p style="font-style: italic; color: var(--text-muted); font-size: 0.9rem;">Detailed performance results for this project will be published here soon.</p>
                    <?php endif; ?>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <?php foreach ($services as $service): ?>
                        <li><i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($service, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <?php if ($weeklyTotalBoxes): ?>
    <!-- WEEKLY RESULTS: latest week + running total, from the admin's
         weekly stats (see includes/weekly-stats.php) -->
    <section class="stats-section fade-up">
        <div class="container">
            <?php if ($weeklyLatestBoxes): ?>
            <div class="section-header">
                <span class="eyebrow" style="color: var(--cyan-neon);">LATEST WEEK</span>
                <h2 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem);">This Week's Results</h2>
                <p style="color: rgba(255,255,255,0.75); margin-top: 10px;"><?php echo htmlspecialchars($weekly['latest_period'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="stats-4-grid" style="margin-bottom: clamp(50px, 7vw, 80px);">
                <?php foreach ($weeklyLatestBoxes as [$value, $label, $change]): ?>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-calendar-week"></i></div>
                    <h3><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php if ($change !== null): ?>
                    <p style="margin-top: 8px; color: <?php echo $change >= 0 ? '#4ade80' : '#f87171'; ?>; text-transform: none; letter-spacing: normal;"><i class="fa-solid <?php echo $change >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down'; ?>"></i> <?php echo ($change >= 0 ? '+' : '') . htmlspecialchars(number_format($change, 1), ENT_QUOTES, 'UTF-8'); ?>% vs previous week</p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="section-header">
                <span class="eyebrow" style="color: var(--cyan-neon);">TOTAL TO DATE</span>
                <h2 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem);">Total Results</h2>
                <p style="color: rgba(255,255,255,0.75); margin-top: 10px;"><?php echo htmlspecialchars($weekly['period'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="stats-4-grid">
                <?php foreach ($weeklyTotalBoxes as [$value, $label]): ?>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <h3><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($hasGsc): ?>
    <!-- GOOGLE SEARCH CONSOLE PERFORMANCE -->
    <section class="stats-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow" style="color: var(--cyan-neon);">SEARCH CONSOLE DATA</span>
                <h2 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem);">Google Search Console Performance</h2>
            </div>
            <div class="stats-4-grid">
                <?php foreach ($gscStats as [$value, $label]): ?>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <h3><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if ($gscGallery): ?>
            <div class="proof-gallery">
                <?php foreach ($gscGallery as [$imgPath, $caption]): ?>
                <figure class="proof-gallery-item">
                    <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($imgPath, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($study['name'], ENT_QUOTES, 'UTF-8'); ?> &ndash; <?php echo htmlspecialchars($caption, ENT_QUOTES, 'UTF-8'); ?>" onerror="this.closest('figure').remove()" tabindex="0" role="button" aria-haspopup="dialog">
                    <figcaption><?php echo htmlspecialchars($caption, ENT_QUOTES, 'UTF-8'); ?></figcaption>
                </figure>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($hasGbp): ?>
    <!-- GOOGLE BUSINESS PROFILE PERFORMANCE -->
    <section class="stats-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow" style="color: var(--cyan-neon);">GOOGLE BUSINESS PROFILE DATA</span>
                <h2 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem);">Google Business Profile Performance</h2>
            </div>
            <div class="stats-4-grid">
                <?php foreach ($gbpStats as [$value, $label]): ?>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <h3><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if ($gbpGallery): ?>
            <div class="proof-gallery">
                <?php foreach ($gbpGallery as [$imgPath, $caption]): ?>
                <figure class="proof-gallery-item">
                    <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($imgPath, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($study['name'], ENT_QUOTES, 'UTF-8'); ?> &ndash; <?php echo htmlspecialchars($caption, ENT_QUOTES, 'UTF-8'); ?>" onerror="this.closest('figure').remove()" tabindex="0" role="button" aria-haspopup="dialog">
                    <figcaption><?php echo htmlspecialchars($caption, ENT_QUOTES, 'UTF-8'); ?></figcaption>
                </figure>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($hasShop): ?>
    <!-- SHOPIFY STORE PERFORMANCE -->
    <section class="stats-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow" style="color: var(--cyan-neon);">SHOPIFY STORE DATA</span>
                <h2 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem);">Online Store Performance</h2>
                <?php if (($study['shop_period'] ?? '') !== ''): ?>
                <p style="color: rgba(255,255,255,0.75); margin-top: 10px;"><?php echo htmlspecialchars($study['shop_period'], ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
            </div>
            <div class="stats-4-grid">
                <?php foreach ($shopStats as [$value, $label, $growth]): ?>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                    <h3><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php if ($growth !== ''): ?>
                    <p style="margin-top: 8px; color: #4ade80; text-transform: none; letter-spacing: normal;"><i class="fa-solid fa-arrow-trend-up"></i> <?php echo htmlspecialchars($growth, ENT_QUOTES, 'UTF-8'); ?> vs previous period</p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if ($shopGallery): ?>
            <div class="proof-gallery">
                <?php foreach ($shopGallery as [$imgPath, $caption]): ?>
                <figure class="proof-gallery-item">
                    <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($imgPath, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($study['name'], ENT_QUOTES, 'UTF-8'); ?> &ndash; <?php echo htmlspecialchars($caption, ENT_QUOTES, 'UTF-8'); ?>" onerror="this.closest('figure').remove()" tabindex="0" role="button" aria-haspopup="dialog">
                    <figcaption><?php echo htmlspecialchars($caption, ENT_QUOTES, 'UTF-8'); ?></figcaption>
                </figure>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- MORE CASE STUDIES -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">MORE PROJECTS</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">More Client Work</h2>
            </div>
            <div class="case-grid">
                <?php foreach ($more as $moreSlug => $item): ?>
                <article class="case-card">
                    <div class="case-image">
                        <span class="case-tag"><?php echo htmlspecialchars($item['tag'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($item['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['image_alt'], ENT_QUOTES, 'UTF-8'); ?>" onerror="this.remove()">
                        <h3><?php echo htmlspecialchars(strtoupper($item['name']), ENT_QUOTES, 'UTF-8'); ?></h3>
                    </div>
                    <div class="case-content">
                        <div>
                            <p><?php echo htmlspecialchars(strip_tags($item['summary']), ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div>
                            <a href="/case-studies/<?php echo htmlspecialchars($moreSlug, ENT_QUOTES, 'UTF-8'); ?>" class="service-link" aria-label="View Details &ndash; <?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>">View Details <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: clamp(30px, 4vw, 40px);">
                <a href="/case-studies" class="service-link"><i class="fa-solid fa-arrow-left"></i> Back To All Case Studies</a>
            </div>
        </div>
    </section>

    <!-- FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Write Your Own Success Story?</h2>
                    <p>Partner with W&S Digital Marketing to build a custom, data-driven growth plan that generates high-intent leads, consistent sales, and maximum ROAS.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                    <?php $cta_button_label = 'GET MY FREE GROWTH PLAN'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Lightbox for proof-gallery screenshots -- markup populated by JS on click -->
    <div class="image-lightbox" id="imageLightbox" role="dialog" aria-modal="true" aria-label="Enlarged screenshot" aria-hidden="true">
        <button type="button" class="image-lightbox-close" aria-label="Close">&times;</button>
        <img src="" alt="">
        <p class="image-lightbox-caption"></p>
    </div>

<?php include 'footer.php'; ?>
