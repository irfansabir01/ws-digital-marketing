<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/case-study-helpers.php';

// This page's original curated "Client Projects" showcase -- these slugs, in
// this exact order, with a short page-specific blurb for each. The blurb is
// page-specific copy, kept here rather than overwriting the case study's own
// (longer) summary field, which other pages and /case-studies/{slug} still
// show in full.
$ecommerceProjectSlugs = [
    'royal-fragrances' => 'A Shopify fragrance store with organised collections, clear product presentation and integrated checkout functionality.',
    'gilgit-shilajit' => 'An ecommerce website bringing together product information, educational content and a consistent visual identity for a wellness brand.',
    'fast-fridge-spares' => 'An online refrigeration-parts store structured to help customers browse components, review product information and find the parts they need.',
];

// Shows every case study ticked for this page under "Show on these Service
// Pages" in the admin (stored in case_studies.related_services), read live
// so ticking/unticking a study there updates this page immediately. The
// curated studies above keep their order and blurb; newly ticked ones
// follow in display_order.
$ecommerceCaseStudies = service_page_case_studies('ecommerce', $ecommerceProjectSlugs);
// Stats sharing the same label (e.g. multiple businesses' "Organic
// Clicks") are combined into one box instead of shown per-business.
$ecommerceStats = combine_stats(collect_case_study_stats($ecommerceCaseStudies));

$page_title = 'Shopify & WooCommerce Development';
$page_description = 'Build or improve your Shopify or WooCommerce store with clear product pages, organised collections and straightforward checkout. Discuss your project.';
include 'header.php';
?>

    <!-- ECOMMERCE HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">ECOMMERCE</span>
            <h1 class="fade-up">MAKE ONLINE SHOPPING <br><span class="text-gradient">SIMPLE FOR YOUR CUSTOMERS.</span></h1>
            <p class="hero-subtitle fade-up">Build or improve your Shopify or WooCommerce store with clear product information, straightforward navigation and a shopping experience designed around your customers.</p>
            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary">GET MY FREE STORE AUDIT <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="/services" class="btn-secondary">VIEW ALL SERVICES</a>
            </div>
        </div>
    </section>

    <!-- INTRO SECTION -->
    <section class="agency-section fade-up">
        <div class="container">
            <div class="agency-grid">
                <div class="agency-image-wrapper">
                    <img loading="lazy" decoding="async" src="/images/photos/ecommerce.webp" alt="Online store owner taping up a customer order in a packing room, with a laptop showing sales and parcels ready to ship">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Give Customers A Clear Path To Purchase</h2>
                    <p>A useful online store helps customers find the right product, understand their options and complete an order with confidence. We bring together store design, product organisation and practical functionality to support that journey, whether you are launching a new business or improving an existing shop.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Shopify &amp; WooCommerce Development</li>
                        <li><i class="fa-solid fa-check"></i> Store Migrations &amp; Product Organisation</li>
                        <li><i class="fa-solid fa-check"></i> Shopping Experience &amp; Product Feed Improvements</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="margin-top: 10px;">Speak With An Ecommerce Strategist</a>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW WE CAN HELP -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">HOW WE CAN HELP</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Support Across Your Ecommerce Store</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-store"></i></div>
                    <h3>Store Development &amp; Migration</h3>
                    <p>Build a new store or plan a platform migration, with agreed requirements for product data, content and essential functionality.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                    <h3>Product &amp; Catalogue Structure</h3>
                    <p>Organise products, collections and descriptions so customers can browse your range, compare options and find relevant information.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-credit-card"></i></div>
                    <h3>Cart &amp; Checkout Improvements</h3>
                    <p>Review the journey to purchase and improve cart information, navigation and available checkout settings within your platform's capabilities.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-bullhorn"></i></div>
                    <h3>Shopping Ads &amp; Product Feeds</h3>
                    <p>Prepare product feeds and manage agreed shopping campaigns, with advertising management and platform spend clearly scoped.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PROCESS -->
    <section class="methodology-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">OUR PROCESS</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">From Store Planning To Launch</h2>
            </div>
            <div class="methodology-steps">
                <div class="m-step">
                    <div class="m-step-num">01</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-magnifying-glass"></i> Review &amp; Plan</h3>
                        <p>We review your products, current platform and customer journey to establish the store's requirements and priorities.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">02</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-hammer"></i> Design &amp; Develop</h3>
                        <p>We create the store layout, organise the catalogue and configure the functionality included in your project.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">03</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-gauge-high"></i> Test &amp; Prepare</h3>
                        <p>We check product information, mobile layouts and purchase pathways, and validate the agreed data included in any migration.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">04</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-rocket"></i> Launch &amp; Support</h3>
                        <p>We launch the store, explain its management features and provide the support or ongoing improvements agreed for your project.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if ($ecommerceCaseStudies): ?>
    <!-- CLIENT PROJECTS: the case studies ticked for this page in the admin
         ("Show on these Service Pages"), pulled live -- see $ecommerceProjectSlugs above. -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">CLIENT PROJECTS</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Online Stores We've Helped Build</h2>
                <p style="color: var(--text-muted); max-width: 700px; margin: 15px auto 0; font-size: 1.05rem;">Explore ecommerce projects designed around different products, audiences and shopping requirements.</p>
            </div>
            <div class="case-grid">
                <?php foreach ($ecommerceCaseStudies as $cs): ?>
                <article class="case-card">
                    <div class="case-image">
                        <span class="case-tag"><?php echo htmlspecialchars($cs['tag'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($cs['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($cs['image_alt'], ENT_QUOTES, 'UTF-8'); ?>">
                        <h3><?php echo htmlspecialchars(strtoupper($cs['name']), ENT_QUOTES, 'UTF-8'); ?></h3>
                    </div>
                    <div class="case-content">
                        <div>
                            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 15px;"><?php echo htmlspecialchars(strip_tags($cs['summary']), ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div>
                            <a href="/case-studies/<?php echo htmlspecialchars($cs['slug'], ENT_QUOTES, 'UTF-8'); ?>" class="service-link">View Project <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
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

    <?php if ($ecommerceStats): ?>
    <!-- STATS: combined real "Results At A Glance" stats from every ecommerce-
         tagged case study, pulled live -- adding stats to an ecommerce case
         study from the admin dashboard makes them appear here automatically. -->
    <section class="stats-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow" style="color: var(--cyan-neon);">PROVEN RESULTS</span>
                <h2 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem);">Real Ecommerce Results, Verified From Client Reporting</h2>
            </div>
            <div class="stats-4-grid">
                <?php foreach ($ecommerceStats as [$value, $label]): ?>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-arrow-trend-up"></i></div>
                    <h3><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- FINAL CTA -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Grow Your Store?</h2>
                    <p>Get a free store audit and see exactly where you're losing sales — and how to fix it.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                <?php $cta_default_service = 'Ecommerce'; ?>
                    <?php $cta_button_label = 'GET MY FREE STORE AUDIT'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
