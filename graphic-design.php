<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Graphic Design Services Australia';
$page_description = 'Build a consistent brand with logo design, advertising creative, social graphics and print materials. Discuss your graphic design needs with W&S.';
include 'header.php';
?>

    <!-- GRAPHIC DESIGN HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">GRAPHIC DESIGN</span>
            <h1 class="fade-up">MAKE YOUR BUSINESS RECOGNISABLE <br><span class="text-gradient">AT EVERY TOUCHPOINT.</span></h1>
            <p class="hero-subtitle fade-up">Bring your brand together with logo design, advertising creative and marketing materials that communicate clearly and look consistent wherever customers encounter your business.</p>
            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary">GET MY FREE BRAND REVIEW <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="/services" class="btn-secondary">VIEW ALL SERVICES</a>
            </div>
        </div>
    </section>

    <!-- INTRO SECTION -->
    <section class="agency-section fade-up">
        <div class="container">
            <div class="agency-grid">
                <div class="agency-image-wrapper">
                    <img loading="lazy" decoding="async" src="/images/photos/graphic-design.webp" alt="Graphic designer creating a logo in design software with a drawing tablet, colour swatches and logo sketches on the desk">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Give Your Brand A Consistent Visual Identity</h2>
                    <p>Your logo, website, social posts and printed materials should feel like parts of the same business. We create designs around your audience, message and practical requirements, helping your team present the brand consistently across everyday marketing and larger campaigns.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Logo Design &amp; Visual Identity</li>
                        <li><i class="fa-solid fa-check"></i> Advertising &amp; Social Media Graphics</li>
                        <li><i class="fa-solid fa-check"></i> Print-Ready Marketing Materials</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="margin-top: 10px;">Speak With A Design Strategist</a>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW WE CAN HELP -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">HOW WE CAN HELP</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Design Support Across Your Brand</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-signature"></i></div>
                    <h3>Logo &amp; Visual Identity</h3>
                    <p>Develop a logo and supporting colours, typography and visual elements suited to your business and intended uses.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-image"></i></div>
                    <h3>Advertising &amp; Social Graphics</h3>
                    <p>Create graphics for agreed advertising formats and social platforms, keeping your message and brand presentation consistent.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-print"></i></div>
                    <h3>Print &amp; Marketing Materials</h3>
                    <p>Design brochures, signage and other business materials, with artwork prepared to the agreed production specifications.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-swatchbook"></i></div>
                    <h3>Brand Guidelines &amp; Handover</h3>
                    <p>Document the approved visual direction and provide agreed file formats so your team can apply the brand consistently.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PROCESS -->
    <section class="methodology-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">OUR PROCESS</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">From Your Brief To Finished Artwork</h2>
            </div>
            <div class="methodology-steps">
                <div class="m-step">
                    <div class="m-step-num">01</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-lightbulb"></i> Understand &amp; Define</h3>
                        <p>We discuss your audience, brand and required materials, then agree on the brief, deliverables and review stages.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">02</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-pen-nib"></i> Explore &amp; Design</h3>
                        <p>We develop design concepts around the agreed direction, considering both visual presentation and practical use.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">03</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-arrows-rotate"></i> Review &amp; Refine</h3>
                        <p>We gather your feedback and refine the selected designs through the revision rounds included in your project.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">04</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-box-open"></i> Prepare &amp; Deliver</h3>
                        <p>We prepare approved artwork and agreed files, explaining any relevant usage guidance or third-party licensing requirements.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    $relatedServiceSlug = 'graphic-design';
    $relatedHeading = 'Explore Our Design Work';
    include __DIR__ . '/includes/related-case-studies-section.php';
    ?>

    <!-- FINAL CTA -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready For A Brand That Stands Out?</h2>
                    <p>Get a free review of your current brand and creative — and a plan to sharpen it up.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                <?php $cta_default_service = 'Graphic Design'; ?>
                    <?php $cta_button_label = 'GET MY FREE BRAND REVIEW'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
