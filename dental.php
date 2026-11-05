<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Dental Digital Marketing';
$page_description = 'Help patients find your dental practice with local search, clear service content and useful websites. Make appointment enquiries more straightforward.';
include 'header.php';
?>

    <!-- DENTAL HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">DENTAL</span>
            <h1 class="fade-up">HELP PATIENTS FIND <br><span class="text-gradient">YOUR DENTAL PRACTICE.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for dental practices &mdash; helping prospective patients find your location, understand your services and take the next step towards an appointment.</p>
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
                    <img loading="lazy" decoding="async" src="/images/industries/dental.webp" alt="Dental Digital Marketing" onerror="this.remove()">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Make Choosing And Contacting Your Practice Easier</h2>
                    <p>People comparing dental practices need clear information about services, practitioners, location and appointment options. We help your practice present that information through local search, useful website content and straightforward enquiry pathways, with treatment information reviewed by your practice before it is published.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Local Search Visibility For Your Practice</li>
                        <li><i class="fa-solid fa-check"></i> Clear Service Information &amp; Appointment Options</li>
                        <li><i class="fa-solid fa-check"></i> Practice-Reviewed Website &amp; Campaign Content</li>
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
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing Built Around Your Dental Practice</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-tooth"></i></div>
                    <h3>Local Search Visibility</h3>
                    <p>Improve your business profile and service pages to help people find relevant information about your practice and location.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <h3>Websites &amp; Appointment Options</h3>
                    <p>Create clear, mobile-friendly pages with appointment requests or links to your booking system, with integrations scoped separately.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-star"></i></div>
                    <h3>Reputation &amp; Patient Feedback</h3>
                    <p>Support feedback monitoring and careful response processes, with any use of patient comments assessed against applicable advertising requirements.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                    <h3>Service Information &amp; Campaigns</h3>
                    <p>Develop clear pages and campaigns for the treatments you offer, with information checked and approved by your practice.</p>
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
                    <h2>Ready To Fill Your Chairs?</h2>
                    <p>Get a free audit of your practice's digital presence and a plan to attract more of the patients you want.</p>
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
