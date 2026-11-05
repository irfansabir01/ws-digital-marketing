<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/case-study-helpers.php';

// This page's original curated "Client Projects" showcase -- these slugs, in
// this exact order, with a short page-specific blurb for each. The blurb is
// page-specific copy, kept here rather than overwriting the case study's own
// (longer) summary field, which other pages and /case-studies/{slug} still
// show in full.
$webdevProjectSlugs = [
    'commercial-fridge-repairs-sydney' => 'A WordPress website presenting commercial refrigeration services, service information and clear contact options for business customers.',
    'fast-appliance-repairs' => 'A responsive WordPress website helping customers explore repair services, find coverage information and request assistance.',
];

// Shows every case study ticked for this page under "Show on these Service
// Pages" in the admin (stored in case_studies.related_services), read live
// so ticking/unticking a study there updates this page immediately. The
// curated studies above keep their order and blurb; newly ticked ones
// follow in display_order.
$webdevCaseStudies = service_page_case_studies('website-development', $webdevProjectSlugs);
// Stats sharing the same label (e.g. multiple businesses' "Organic
// Clicks") are combined into one box instead of shown per-business.
$webdevStats = combine_stats(collect_case_study_stats($webdevCaseStudies));

$page_title = 'Website Design & Development';
$page_description = 'Create a mobile-friendly business website with clear service information, easy navigation and enquiry options. Talk to W&S about your website project.';
include 'header.php';
?>

    <!-- WEBSITE DEV HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">WEBSITE DESIGN &amp; DEVELOPMENT</span>
            <h1 class="fade-up">WEBSITES THAT MAKE <br><span class="text-gradient">THE NEXT STEP EASY.</span></h1>
            <p class="hero-subtitle fade-up">Give customers a clear introduction to your business with a responsive website that explains your services and makes it straightforward to enquire, book or buy.</p>
            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary">GET MY FREE WEBSITE AUDIT <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="/services" class="btn-secondary">VIEW ALL SERVICES</a>
            </div>
        </div>
    </section>

    <!-- INTRO SECTION -->
    <section class="agency-section fade-up">
        <div class="container">
            <div class="agency-grid">
                <div class="agency-image-wrapper">
                    <img loading="lazy" decoding="async" src="/images/photos/website-development.webp" alt="Web developer working on website code and a WordPress page builder across two monitors, with the finished site shown on a laptop and smartphone">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Build A Website Around Your Customers</h2>
                    <p>Your website should help visitors understand what you offer and how to take the next step. We plan and develop websites around your business, combining clear content, logical navigation and practical features that support customers across desktop, tablet and mobile devices.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Website Design Matched To Your Brand</li>
                        <li><i class="fa-solid fa-check"></i> Responsive Layouts &amp; Clear Navigation</li>
                        <li><i class="fa-solid fa-check"></i> Enquiry Features &amp; Analytics Setup</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="margin-top: 10px;">Speak With A Website Strategist</a>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW WE CAN HELP -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">HOW WE CAN HELP</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">The Essentials Of A Useful Business Website</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-pen-ruler"></i></div>
                    <h3>Website Design &amp; User Experience</h3>
                    <p>Plan layouts and navigation around your services, brand identity and the information customers need before making contact.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-mobile-screen"></i></div>
                    <h3>Responsive Development</h3>
                    <p>Build pages that adapt to desktop, tablet and mobile screens, with readable content and straightforward controls.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-gauge-high"></i></div>
                    <h3>Speed &amp; Technical Setup</h3>
                    <p>Optimise images and website resources, and review the technical setup supporting page performance and ongoing maintenance.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-chart-simple"></i></div>
                    <h3>Enquiries &amp; Analytics</h3>
                    <p>Configure agreed forms, calls to action and analytics so you can understand how visitors use your website.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PROCESS -->
    <section class="methodology-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">OUR PROCESS</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">How We Build Your Website</h2>
            </div>
            <div class="methodology-steps">
                <div class="m-step">
                    <div class="m-step-num">01</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-lightbulb"></i> Discover &amp; Plan</h3>
                        <p>We establish your goals, required pages and functionality, then map the website structure and customer journey.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">02</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-palette"></i> Design &amp; Review</h3>
                        <p>We develop a visual direction and page layouts, incorporating your feedback through the agreed review stages.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">03</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-code"></i> Develop &amp; Test</h3>
                        <p>We build the website and test its layouts, links, forms and agreed features across relevant browsers and devices.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">04</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-rocket"></i> Launch &amp; Handover</h3>
                        <p>We prepare the site for launch, explain how to manage it and confirm the support included after delivery.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if ($webdevCaseStudies): ?>
    <!-- CLIENT PROJECTS: the case studies ticked for this page in the admin
         ("Show on these Service Pages"), pulled live -- see $webdevProjectSlugs above. -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">CLIENT PROJECTS</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Websites Built Around Real Business Needs</h2>
                <p style="color: var(--text-muted); max-width: 700px; margin: 15px auto 0; font-size: 1.05rem;">Explore examples of service websites designed to explain business offerings and make customer enquiries straightforward.</p>
            </div>
            <div class="case-grid">
                <?php foreach ($webdevCaseStudies as $cs): ?>
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

    <?php if ($webdevStats): ?>
    <!-- STATS: combined real "Results At A Glance" stats from the case
         studies above, pulled live -- adding stats to one of them from the
         admin dashboard makes them appear here automatically. -->
    <section class="stats-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow" style="color: var(--cyan-neon);">PROVEN RESULTS</span>
                <h2 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem);">Websites That Perform, Not Just Impress</h2>
            </div>
            <div class="stats-4-grid">
                <?php foreach ($webdevStats as [$value, $label]): ?>
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
                    <h2>Ready For A Website That Converts?</h2>
                    <p>Get a free audit of your current site and a clear plan to turn more visitors into enquiries.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                <?php $cta_default_service = 'Website Design & Development'; ?>
                    <?php $cta_button_label = 'GET MY FREE WEBSITE AUDIT'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
