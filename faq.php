<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Digital Marketing FAQs';
$page_description = 'Find answers about W&S Digital Marketing services, pricing, contracts, reporting and getting started. Understand what to expect before making an enquiry.';
include 'header.php';
?>

    <!-- FAQ HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">FAQ</span>
            <h1 class="fade-up">GOT QUESTIONS? <br><span class="text-gradient">WE'VE GOT ANSWERS.</span></h1>
            <p class="hero-subtitle fade-up">Everything you need to know about working with W&amp;S Digital Marketing. Can't find what you're looking for? Just reach out.</p>
        </div>
    </section>

    <!-- FAQ ACCORDION SECTION -->
    <section class="services-section fade-up">
        <div class="container" style="max-width: 900px;">
            <div class="faq-list">

                <details class="faq-item" open>
                    <summary>How quickly will I see results?</summary>
                    <p>PPC campaigns typically start generating leads within days of launch. SEO is a longer-term investment, with most clients seeing meaningful ranking improvements within 3-6 months, depending on competition and starting position.</p>
                </details>

                <details class="faq-item">
                    <summary>Do you require long-term contracts?</summary>
                    <p>No. All of our plans are month-to-month with no lock-in contracts. We earn your business through results, not through cancellation fees.</p>
                </details>

                <details class="faq-item">
                    <summary>What industries do you work with?</summary>
                    <p>We work across trades &amp; services, healthcare, professional services, eCommerce, hospitality, and automotive — see our <a href="/industries">Industries</a> page for more detail on how we tailor strategy per sector.</p>
                </details>

                <details class="faq-item">
                    <summary>How does your reporting work?</summary>
                    <p>Every client gets transparent, easy-to-read monthly reporting covering clicks, impressions, conversions, cost per lead, and ROAS — plus a strategist call to walk through what's working and what's next.</p>
                </details>

                <details class="faq-item">
                    <summary>Do you manage the ad spend budget?</summary>
                    <p>Our plans cover strategy, management, and optimization. Ad spend (what you pay directly to Google or Meta) is billed separately and stays fully in your control.</p>
                </details>

                <details class="faq-item">
                    <summary>Can you build me a new website?</summary>
                    <p>Yes. Our web design &amp; CRO service builds fast, mobile-responsive websites engineered to convert, whether that's a brochure site or a full ecommerce store. See our <a href="/website-development">Website Development</a> service for details.</p>
                </details>

                <details class="faq-item">
                    <summary>How do I get started?</summary>
                    <p>Book a zero-obligation growth audit through our <a href="/contact">contact page</a>. We'll review your current marketing, identify opportunities, and recommend a plan tailored to your goals.</p>
                </details>

            </div>
        </div>
    </section>

    <!-- FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Still Have Questions?</h2>
                    <p>Our team is happy to walk you through exactly how we'd approach your growth strategy.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                    <?php $cta_button_label = 'GET MY FREE GROWTH PLAN'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
