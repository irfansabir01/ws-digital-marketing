<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'PPC & Google Ads Agency Australia';
$page_description = 'Grow leads with expert PPC advertising across Google Ads, Facebook & Instagram. Smart targeting, conversion tracking and clear reports. Get a free ads audit.';
include 'header.php';
?>

    <!-- PPC HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">PAID ADVERTISING</span>
            <h1 class="fade-up">PUT YOUR BUSINESS IN FRONT <br><span class="text-gradient">OF RELEVANT CUSTOMERS.</span></h1>
            <p class="hero-subtitle fade-up">Reach people through Google Ads and paid social campaigns, with clear targeting, ongoing testing and reporting focused on the actions that matter to your business.</p>
            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary">GET MY FREE ADS AUDIT <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="/services" class="btn-secondary">VIEW ALL SERVICES</a>
            </div>
        </div>
    </section>

    <!-- INTRO SECTION -->
    <section class="agency-section fade-up">
        <div class="container">
            <div class="agency-grid">
                <div class="agency-image-wrapper">
                    <img loading="lazy" decoding="async" src="/images/photos/paid-advertising.webp" alt="Marketing specialist reviewing paid advertising campaign charts on a laptop and taking notes in an office">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Give Your Advertising A Clear Purpose</h2>
                    <p>Effective advertising starts with a clear offer, a relevant audience and a useful destination after the click. We plan and manage campaigns around your services, budget and goals, reviewing performance to identify where changes are needed. Advertising spend is separate from our management fees.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Google Search &amp; Display Advertising</li>
                        <li><i class="fa-solid fa-check"></i> Facebook, Instagram &amp; TikTok Campaigns</li>
                        <li><i class="fa-solid fa-check"></i> Conversion Tracking &amp; Campaign Reporting</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="margin-top: 10px;">Speak With An Advertising Strategist</a>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW WE CAN HELP -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">HOW WE CAN HELP</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Support Across Your Advertising Campaigns</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-brands fa-google"></i></div>
                    <h3>Google Ads Management</h3>
                    <p>Build and manage search and display campaigns with targeting, advertising copy and budgets shaped around your business priorities.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-hand-pointer"></i></div>
                    <h3>Paid Social Campaigns</h3>
                    <p>Develop campaigns for relevant social platforms, combining audience selection, creative testing and clear calls to action.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-arrow-pointer"></i></div>
                    <h3>Landing Page Improvements</h3>
                    <p>Review the pages receiving advertising traffic and scope improvements or new pages where the customer journey needs attention.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-chart-pie"></i></div>
                    <h3>Tracking &amp; Reporting</h3>
                    <p>Configure agreed conversion tracking and explain campaign performance, including the measurement limitations relevant to your setup.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PROCESS -->
    <section class="methodology-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">OUR PROCESS</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">How We Plan And Manage Your Ads</h2>
            </div>
            <div class="methodology-steps">
                <div class="m-step">
                    <div class="m-step-num">01</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-magnifying-glass"></i> Review &amp; Set Goals</h3>
                        <p>We review your offer, existing accounts and budget, then agree on campaign objectives and the actions to measure.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">02</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-rocket"></i> Build &amp; Launch</h3>
                        <p>We prepare targeting, advertising creative and tracking, with account access and spending arrangements confirmed before launch.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">03</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-flask"></i> Test &amp; Improve</h3>
                        <p>We review search terms, audiences, creative and landing pages, using campaign data to guide ongoing adjustments.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">04</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-chart-line"></i> Review &amp; Plan Ahead</h3>
                        <p>We explain what the campaigns are delivering and recommend the next changes within your agreed budget.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    $relatedServiceSlug = 'ppc-advertising';
    $relatedHeading = 'Explore Our Paid Advertising Work';
    include __DIR__ . '/includes/related-case-studies-section.php';
    ?>

    <!-- FINAL CTA -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready For Leads On Demand?</h2>
                    <p>Get a free audit of your ad accounts and a clear plan to lower cost per lead and scale what works.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                <?php $cta_default_service = 'Paid Advertising'; ?>
                    <?php $cta_button_label = 'GET MY FREE ADS AUDIT'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
