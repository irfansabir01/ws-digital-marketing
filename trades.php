<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Digital Marketing for Trades';
$page_description = 'Help customers find your trade business with local search, clear service pages and targeted advertising. Support calls, quote requests and repair enquiries.';
include 'header.php';
?>

    <!-- TRADES HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">TRADES &amp; SERVICES</span>
            <h1 class="fade-up">GET FOUND LOCALLY. <br><span class="text-gradient">MAKE ENQUIRING EASY.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for plumbers, electricians and local trade businesses &mdash; helping customers find your services, check your coverage and request a quote or repair.</p>
            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary">GET MY FREE MARKETING AUDIT <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="/industries" class="btn-secondary">VIEW ALL INDUSTRIES</a>
            </div>
        </div>
    </section>

    <!-- INTRO SECTION -->
    <section class="agency-section fade-up">
        <div class="container">
            <div class="agency-grid">
                <div class="agency-image-wrapper">
                    <img loading="lazy" decoding="async" src="/images/photos/trades.webp" alt="Plumber in high-visibility workwear fixing the pipes under a kitchen sink while the homeowner looks on">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Help Customers Find The Right Trade For The Job</h2>
                    <p>Some customers need urgent repairs; others are planning maintenance or installation work. We help them understand your services, coverage and availability through clear websites, local search and targeted campaigns, with straightforward ways to call, describe the job or request a quote.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Local Search &amp; Business Profile Improvements</li>
                        <li><i class="fa-solid fa-check"></i> Clear Service Areas &amp; Quote Request Options</li>
                        <li><i class="fa-solid fa-check"></i> Campaigns Matched To Your Services &amp; Availability</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="margin-top: 10px;">Speak With Our Team</a>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW WE CAN HELP -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">HOW WE CAN HELP</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing Built Around Your Trade Business</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <h3>Local Search Visibility</h3>
                    <p>Improve business profiles and service pages so customers can find relevant information about your trade and service areas.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-star"></i></div>
                    <h3>Reviews &amp; Reputation</h3>
                    <p>Support honest customer feedback requests, monitor reviews and help your team respond professionally to customer experiences.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <h3>Targeted Service Advertising</h3>
                    <p>Plan ads around your services, coverage and working hours, including urgent repairs where your business offers that service.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <h3>Websites &amp; Quote Requests</h3>
                    <p>Build clear, mobile-friendly pages with click-to-call links and enquiry forms that help customers explain the work they need.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Keep Your Trucks Rolling?</h2>
                    <p>Get a free audit of your trade business's local visibility and a plan to book more jobs.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                    <?php $cta_button_label = 'GET MY FREE MARKETING AUDIT'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
