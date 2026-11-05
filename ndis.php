<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'NDIS Provider Digital Marketing';
$page_description = 'Help participants, supporters and referrers understand your NDIS services with clear websites, local search and content. Make service enquiries easier.';
include 'header.php';
?>

    <!-- NDIS HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">NDIS PROVIDERS</span>
            <h1 class="fade-up">CLEAR SERVICES. <br><span class="text-gradient">INFORMED PARTICIPANT CHOICES.</span></h1>
            <p class="hero-subtitle fade-up">We help NDIS providers communicate their services clearly, connect with support coordinators and referrers, and make it easier for participants and their chosen supporters to enquire online.</p>
            <p class="hero-subtitle fade-up" style="margin-top: 15px;">Navigating NDIS marketing requires more than standard digital marketing. It requires trust, careful attention to compliance, clear communication and genuine empathy. We build practical online strategies for disability service providers, from high-intent local search and participant-first content to considered campaigns that help the right people find and understand your services.</p>
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
                    <img loading="lazy" decoding="async" src="/images/photos/ndis.webp" alt="Support worker and a smiling NDIS participant in a wheelchair planning together on a tablet at a community centre">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Help Participants Understand Their Options</h2>
                    <p>Participants need clear information to decide whether a provider's services suit their needs and preferences. We help you explain the supports you offer, where you work and how to enquire, using plain language and straightforward website navigation. Your team reviews service details before publication.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Clear Support Descriptions &amp; Service Areas</li>
                        <li><i class="fa-solid fa-check"></i> Readable Content &amp; Straightforward Navigation</li>
                        <li><i class="fa-solid fa-check"></i> Enquiry Information For Participants &amp; Coordinators</li>
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
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing Built Around Your Support Services</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
                    <h3>Clear Provider Websites</h3>
                    <p>Present your supports, team and contact options through readable content and organised layouts, with accessibility requirements agreed during planning.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-magnifying-glass-location"></i></div>
                    <h3>Local &amp; Service Search</h3>
                    <p>Improve website content and business information around the supports you provide and the locations you serve.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-people-arrows"></i></div>
                    <h3>Coordinator &amp; Referrer Information</h3>
                    <p>Develop clear service pages and information materials that help support coordinators and other referrers understand your offering.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-comments"></i></div>
                    <h3>Enquiry Pathway Improvements</h3>
                    <p>Simplify contact forms and explain what happens after an enquiry, helping people reach the appropriate member of your team.</p>
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
                    <h2>Ready To Grow Your Provider Services?</h2>
                    <p>Get a free audit of your provider's digital presence and a trust-first plan to grow enquiries.</p>
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
