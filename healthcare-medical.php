<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Healthcare Digital Marketing';
$page_description = 'Help people find your clinic or medical practice with local search, clear service information and useful websites. Support straightforward enquiries.';
include 'header.php';
?>

    <!-- HEALTHCARE & MEDICAL HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">HEALTHCARE &amp; MEDICAL</span>
            <h1 class="fade-up">CLEAR INFORMATION. <br><span class="text-gradient">EASIER ACCESS TO YOUR PRACTICE.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for clinics, medical practices and healthcare providers &mdash; helping people find your services, understand their options and contact your team.</p>
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
                    <img loading="lazy" decoding="async" src="/images/photos/healthcare.webp" alt="Patient being welcomed at the reception desk of a bright, modern medical clinic, with people in the waiting area" onerror="this.remove()">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Help People Understand Your Services Before They Enquire</h2>
                    <p>People looking for healthcare need clear information about services, practitioners, locations and appointment requirements. We organise that information across your website and local search presence, with clinical content and service claims reviewed by your practice before publication.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Local Search &amp; Accurate Practice Information</li>
                        <li><i class="fa-solid fa-check"></i> Clear Service Pages &amp; Appointment Pathways</li>
                        <li><i class="fa-solid fa-check"></i> Practice-Reviewed Content &amp; Campaign Messaging</li>
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
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing Support For Your Healthcare Practice</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-user-doctor"></i></div>
                    <h3>Local Search Visibility</h3>
                    <p>Improve business profiles and service pages so people can find accurate information about your practice, services and locations.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-briefcase-medical"></i></div>
                    <h3>Practice Website Design</h3>
                    <p>Create clear navigation, readable content and mobile-friendly layouts, with straightforward access to practitioner details and appointment information.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-star"></i></div>
                    <h3>Reputation &amp; Feedback Support</h3>
                    <p>Support review monitoring and response preparation, with your practice approving replies and decisions about using patient feedback.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <h3>Appointment &amp; Referral Information</h3>
                    <p>Develop pages and campaigns that explain appointment options and referral requirements, with booking-system connections scoped to your needs.</p>
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
                    <h2>Ready To Grow Your Patient Base?</h2>
                    <p>Get a free audit of your practice's digital presence and a compliant plan to attract more patients.</p>
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
