<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Law Firm Digital Marketing';
$page_description = 'Help prospective clients find your law firm with local search, clear practice area content and professional websites. Support relevant legal enquiries.';
include 'header.php';
?>

    <!-- LEGAL / LAW HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">LEGAL &amp; LAW</span>
            <h1 class="fade-up">MAKE YOUR EXPERTISE CLEAR. <br><span class="text-gradient">HELP CLIENTS FIND YOU.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for law firms and legal practices &mdash; helping prospective clients understand your services, explore your expertise and contact the right team.</p>
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
                    <img loading="lazy" decoding="async" src="/images/industries/legal.webp" alt="Legal / Law Digital Marketing" onerror="this.remove()">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Help Prospective Clients Understand How You Can Help</h2>
                    <p>Choosing a law firm starts with understanding its services, experience and approach. We organise your digital presence around the matters you handle and the clients you serve, using clear content, local search and straightforward enquiry options. Your firm reviews legal information before publication.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Search Visibility For Your Practice Areas</li>
                        <li><i class="fa-solid fa-check"></i> Clear Service Pages &amp; Firm-Reviewed Content</li>
                        <li><i class="fa-solid fa-check"></i> Professional Websites &amp; Enquiry Pathways</li>
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
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing Built Around Your Legal Practice</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-scale-balanced"></i></div>
                    <h3>Practice Area &amp; Local Search</h3>
                    <p>Improve service and location pages around the legal matters you handle and the areas your firm serves.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-file-lines"></i></div>
                    <h3>Legal Website Content</h3>
                    <p>Develop service explanations, guides and frequently asked questions, with your firm reviewing legal accuracy and suitability before publication.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <h3>Professional Website Design</h3>
                    <p>Present your team, experience and practice areas clearly, with readable layouts and straightforward ways to contact your firm.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-phone-volume"></i></div>
                    <h3>Enquiry Journey Improvements</h3>
                    <p>Review forms, contact options and enquiry routing to help prospective clients reach the appropriate team within your firm.</p>
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
                    <h2>Ready To Attract More Of The Right Cases?</h2>
                    <p>Get a free audit of your firm's digital presence and a plan to attract higher-value clients.</p>
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
