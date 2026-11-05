<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Franchise Digital Marketing';
$page_description = 'Support your franchise locations with local search, location pages and coordinated advertising. Keep your brand consistent across the network.';
include 'header.php';
?>

    <!-- FRANCHISE HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">FRANCHISE</span>
            <h1 class="fade-up">LOCAL REACH. <br><span class="text-gradient">ONE CONSISTENT BRAND.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for franchise networks &mdash; connecting local search, location pages and advertising to support individual businesses while keeping your brand consistent.</p>
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
                    <img loading="lazy" decoding="async" src="/images/photos/franchise.webp" alt="Row of matching franchise café shopfronts with awnings and outdoor seating on a busy Australian shopping street">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Give Each Location A Clear Local Presence</h2>
                    <p>Your locations share a brand, but serve different communities. We coordinate marketing around your brand guidelines while reflecting each location's services, trading details and audience. Clear responsibilities, local content and reporting help your head office and franchisees work towards shared priorities.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Local Search &amp; Business Profile Management</li>
                        <li><i class="fa-solid fa-check"></i> Location Pages With Relevant Local Information</li>
                        <li><i class="fa-solid fa-check"></i> Coordinated Campaigns &amp; Location Reporting</li>
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
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing Support Across Your Franchise Network</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                    <h3>Local Search Management</h3>
                    <p>Improve business profiles and website information so customers can find relevant locations, services, opening hours and contact details.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-store"></i></div>
                    <h3>Individual Location Pages</h3>
                    <p>Create pages with useful local information and clear enquiry options, while maintaining your franchise's visual identity and messaging.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-chart-pie"></i></div>
                    <h3>Network &amp; Location Reporting</h3>
                    <p>Bring agreed campaign measures together to review network activity, compare locations and identify priorities using available tracking data.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-bullhorn"></i></div>
                    <h3>Brand &amp; Local Advertising</h3>
                    <p>Coordinate campaigns around shared brand goals, with local targeting, budgets and approvals agreed with your franchise team.</p>
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
                    <h2>Ready To Grow Every Location?</h2>
                    <p>Get a free audit of your franchise's local visibility and a plan to grow every location, not just a few.</p>
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
