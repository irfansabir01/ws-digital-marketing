<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/case-study-helpers.php';

// This page's original curated "Client Projects" showcase -- these slugs, in
// this exact order, with a short page-specific blurb for each. The blurb
// is page-specific copy, kept here rather than overwriting the case study's
// own (longer) summary field, which other pages and /case-studies/{slug}
// still show in full.
$seoProjectSlugs = [
    'clever-beavers' => 'A Shopify store presenting educational resources through organised collections, clear product pages and a mobile-friendly shopping experience.',
    'cafe-calibre' => 'A branded Shopify storefront bringing together product browsing, online ordering and practical store management for the café team.',
    'on-crew' => 'Search optimisation for a workforce management platform, covering keyword research, on-page improvements, technical reviews and content planning.',
    'master-fridge-repairs' => 'A WordPress website presenting refrigeration services, coverage areas and clear enquiry options for residential and commercial customers.',
    'commercial-fridge-repairs-sydney' => 'A service-focused WordPress website helping businesses explore commercial refrigeration repairs and contact the team about their requirements.',
    'fridge-experts' => 'A responsive website organising refrigeration repair information, service locations and contact options for customers across Sydney.',
    'ace-fridge-repairs-sydney' => 'A WordPress website presenting residential and commercial refrigeration services through clear service pages, navigation and enquiry pathways.',
    'fast-fridge-repairs' => 'A WordPress service website helping customers find refrigeration repair information and request assistance across Sydney.',
    'magnet-cleaning-australia' => 'A commercial cleaning website with clear service descriptions, coverage information and an enquiry form for prospective business customers.',
    'fast-appliance-repairs' => 'A WordPress website helping customers explore appliance repair services, check service areas and contact the business.',
    'royal-fragrances' => 'A Shopify fragrance store with organised collections, detailed product presentation and a straightforward journey from browsing to checkout.',
    'becoming-her-with-salma' => "A coaching website presenting Salma's approach, programmes and contact information through clear content and a welcoming design.",
    'northside-coffee' => 'A café website bringing together menu information, imagery and business details in a layout suited to desktop and mobile browsing.',
    'freak-eats' => "A food and events website showcasing the brand's menu, locations and activities, with straightforward navigation and contact information.",
];

// Shows every case study ticked for this page under "Show on these Service
// Pages" in the admin (stored in case_studies.related_services), read live
// so ticking/unticking a study there updates this page immediately. The
// curated studies above keep their order and blurb; newly ticked ones
// follow in display_order.
$seoCaseStudies = service_page_case_studies('seo', $seoProjectSlugs);
// Stats sharing the same label (e.g. multiple businesses' "Organic
// Clicks") are combined into one box instead of shown per-business.
$seoStats = combine_stats(collect_case_study_stats($seoCaseStudies));

$page_title = 'SEO Services Australia';
$page_description = 'Help customers find your business with technical SEO, local search optimisation and useful content. Request a free review of your search visibility.';
include 'header.php';
?>

    <!-- SEO HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">SEARCH ENGINE OPTIMISATION</span>
            <h1 class="fade-up">HELP MORE CUSTOMERS <br><span class="text-gradient">FIND YOUR BUSINESS.</span></h1>
            <p class="hero-subtitle fade-up">Make your business easier to discover with technical SEO, local search optimisation and useful content shaped around what your customers are looking for.</p>
            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary">GET MY FREE SEO AUDIT <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="/services" class="btn-secondary">VIEW ALL SERVICES</a>
            </div>
        </div>
    </section>

    <!-- INTRO SECTION -->
    <section class="agency-section fade-up">
        <div class="container">
            <div class="agency-grid">
                <div class="agency-image-wrapper">
                    <img loading="lazy" decoding="async" src="/images/photos/seo.webp" alt="Laptop on an office desk showing a website analytics dashboard with a rising traffic graph">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY SEO</span>
                    <h2>Connect With Customers When They Search</h2>
                    <p>Your customers use search to compare options, find answers and choose businesses. We help your website communicate what you offer, address technical obstacles and build a clearer local presence. Your SEO priorities are shaped around your services, target locations and the customers you want to reach.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Technical Website Reviews &amp; Improvements</li>
                        <li><i class="fa-solid fa-check"></i> Local SEO &amp; Google Business Profile Optimisation</li>
                        <li><i class="fa-solid fa-check"></i> Search-Focused Content &amp; Relevant Outreach</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="margin-top: 10px;">Speak With An SEO Strategist</a>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW WE CAN HELP -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">HOW WE CAN HELP</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">The Foundations Of Better Search Visibility</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-magnifying-glass-chart"></i></div>
                    <h3>Technical SEO</h3>
                    <p>Review website structure, search engine access, page speed and mobile usability, then prioritise technical improvements within your agreed scope.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-file-lines"></i></div>
                    <h3>On-Page Optimisation</h3>
                    <p>Improve page titles, headings, internal links and content so customers and search engines can understand your products and services.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <h3>Local SEO &amp; Maps</h3>
                    <p>Refine your Google Business Profile, service information and business listings to support discovery in relevant local searches.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-link"></i></div>
                    <h3>Content &amp; Outreach</h3>
                    <p>Create useful content around customer questions and pursue relevant opportunities to introduce your website to wider audiences.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PROCESS -->
    <section class="methodology-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">OUR PROCESS</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">A Clear Plan For Your SEO</h2>
            </div>
            <div class="methodology-steps">
                <div class="m-step">
                    <div class="m-step-num">01</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-magnifying-glass"></i> Understand Your Business</h3>
                        <p>We discuss your services, customers and goals, then review your website and existing search performance.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">02</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-key"></i> Research &amp; Prioritise</h3>
                        <p>We investigate relevant searches and competing websites to identify content opportunities and the improvements worth addressing first.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">03</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-screwdriver-wrench"></i> Make Improvements</h3>
                        <p>We implement agreed technical changes, refine website content and improve the information supporting your local presence.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">04</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-chart-line"></i> Review &amp; Refine</h3>
                        <p>We review search performance and available enquiry data, explain progress and adjust priorities as your website develops.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if ($seoCaseStudies): ?>
    <!-- CLIENT PROJECTS: the case studies ticked for this page in the admin
         ("Show on these Service Pages"), pulled live -- see $seoProjectSlugs above. -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">CLIENT PROJECTS</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Explore Our Website &amp; Search Work</h2>
                <p style="color: var(--text-muted); max-width: 700px; margin: 15px auto 0; font-size: 1.05rem;">Discover projects across service businesses, online stores and hospitality brands. Each case study explains the client's requirements and the work delivered.</p>
            </div>
            <div class="case-grid">
                <?php foreach ($seoCaseStudies as $cs): ?>
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

    <?php if ($seoStats): ?>
    <!-- STATS: combined real "Results At A Glance" stats from every case study
         with verified results, pulled live -- adding stats to a case study
         from the admin dashboard makes them appear here automatically. -->
    <section class="stats-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow" style="color: var(--cyan-neon);">PROVEN RESULTS</span>
                <h2 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem);">SEO That Shows Up In The Numbers</h2>
            </div>
            <div class="stats-4-grid">
                <?php foreach ($seoStats as [$value, $label]): ?>
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
                    <h2>Ready To Rank Higher?</h2>
                    <p>Get a free SEO audit and see exactly what's holding your rankings back — and what it'll take to fix it.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                <?php $cta_default_service = 'Search Engine Optimisation'; ?>
                    <?php $cta_button_label = 'GET MY FREE SEO AUDIT'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
