<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Digital Marketing for Builders';
$page_description = 'Showcase your building and construction expertise with project portfolios, clear websites and targeted marketing. Help relevant clients enquire.';
include 'header.php';
?>

    <!-- CONSTRUCTION & BUILDING HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">CONSTRUCTION &amp; BUILDING</span>
            <h1 class="fade-up">SHOWCASE YOUR WORK. <br><span class="text-gradient">REACH THE RIGHT CLIENTS.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for builders and construction companies &mdash; showcasing your capabilities and helping prospective clients enquire about projects that suit your services and experience.</p>
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
                    <img loading="lazy" decoding="async" src="/images/photos/construction.webp" alt="Two construction workers in hard hats and high-visibility vests reviewing plans on a tablet at a building site with a tower crane">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Help Clients See What You Can Deliver</h2>
                    <p>Prospective clients need to understand your experience, project types and service areas before making an enquiry. We bring that information together through clear websites, detailed project portfolios and targeted marketing that helps homeowners, developers and project managers assess your suitability for their next project.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Project Portfolios &amp; Clear Capability Information</li>
                        <li><i class="fa-solid fa-check"></i> Commercial &amp; Residential Enquiry Campaigns</li>
                        <li><i class="fa-solid fa-check"></i> Search Visibility Across Your Service Areas</li>
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
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing Built Around Your Project Expertise</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-diagram-project"></i></div>
                    <h3>Project Portfolio Websites</h3>
                    <p>Present completed work through project descriptions, photographs and clear explanations of your role, capabilities and services.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-helmet-safety"></i></div>
                    <h3>Commercial Project Enquiries</h3>
                    <p>Develop pages and campaigns that communicate your capabilities to developers, project managers and other commercial decision-makers.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-house-chimney"></i></div>
                    <h3>Residential Project Enquiries</h3>
                    <p>Help homeowners explore your renovation, extension or new-build services, with clear information about project types and service areas.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-ranking-star"></i></div>
                    <h3>Local &amp; Regional Search</h3>
                    <p>Improve search visibility for the construction services and locations that match the work your business wants to attract.</p>
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
                    <h2>Ready To Fill Your Project Pipeline?</h2>
                    <p>Get a free audit of your digital presence and a plan to win bigger, better projects.</p>
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
