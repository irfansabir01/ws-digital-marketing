<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Ecommerce Marketing Services';
$page_description = 'Connect SEO, paid advertising, email campaigns and store improvements around your products and customers. Explore digital marketing for online retailers.';
include 'header.php';
?>

    <!-- ECOMMERCE HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">ECOMMERCE &amp; RETAIL</span>
            <h1 class="fade-up">REACH MORE SHOPPERS. <br><span class="text-gradient">BUILD REPEAT BUSINESS.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for online retailers and product brands &mdash; connecting search, advertising, shopping experience improvements and email campaigns around your customers and products.</p>
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
                    <img loading="lazy" decoding="async" src="/images/photos/ecommerce-industry.webp" alt="Woman shopping online on her smartphone at home, with an online store open on a laptop and a delivered parcel on the coffee table">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Connect Your Marketing To The Shopping Journey</h2>
                    <p>Online shoppers need to discover your products, understand their value and feel confident placing an order. We coordinate marketing around those steps, using search, advertising and email alongside practical store improvements. Priorities are shaped by your products, customer behaviour, available data and budget.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Search &amp; Advertising For Relevant Shoppers</li>
                        <li><i class="fa-solid fa-check"></i> Product Page &amp; Purchase Journey Improvements</li>
                        <li><i class="fa-solid fa-check"></i> Email Campaigns To Encourage Repeat Purchases</li>
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
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing Support For Your Online Store</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-magnifying-glass-dollar"></i></div>
                    <h3>Search &amp; Shopping Campaigns</h3>
                    <p>Improve product visibility through search optimisation and agreed Google Shopping campaigns, with clear product information and organised feeds.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-share-nodes"></i></div>
                    <h3>Social &amp; Returning Visitor Ads</h3>
                    <p>Introduce products to relevant audiences and reconnect with previous visitors through suitable Meta and TikTok advertising campaigns.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-gauge-high"></i></div>
                    <h3>Shopping Experience Improvements</h3>
                    <p>Review product pages and purchase journeys, prioritising practical improvements and testing changes where traffic and measurement support reliable comparisons.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-rotate"></i></div>
                    <h3>Email &amp; Repeat Purchases</h3>
                    <p>Plan newsletters and customer emails, with writing, automation setup and ongoing campaign management agreed around your store's needs.</p>
                </div>
            </div>
            <div style="max-width: 800px; margin: clamp(30px, 4vw, 40px) auto 0; text-align: center;">
                <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.6;">Advertising spend is separate from management fees. Campaign scope, platform costs and any additional store work are agreed before work begins.</p>
                <p style="margin-top: 15px;"><a href="/ecommerce" class="service-link">Need A New Store Or Website Improvements? Explore Our Ecommerce Development Services <i class="fa-solid fa-arrow-right"></i></a></p>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Scale Your Store?</h2>
                    <p>Get a free audit of your store's growth potential and a plan to scale revenue, not just traffic.</p>
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
