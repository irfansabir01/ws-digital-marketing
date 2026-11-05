<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Hospitality & Tourism Marketing';
$page_description = 'Help diners and visitors discover your restaurant, café, venue or tour business with search, websites and campaigns that support enquiries and bookings.';
include 'header.php';
?>

    <!-- HOSPITALITY & TOURISM HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">HOSPITALITY &amp; TOURISM</span>
            <h1 class="fade-up">GET DISCOVERED. <br><span class="text-gradient">MAKE BOOKING EASIER.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for restaurants, caf&eacute;s, venues and tour operators &mdash; helping locals and visitors discover your offering, explore the details and make bookings.</p>
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
                    <img loading="lazy" decoding="async" src="/images/industries/resort.webp" alt="Hospitality & Tourism Digital Marketing" onerror="this.remove()">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Help Customers Plan Their Next Visit Or Experience</h2>
                    <p>Some customers book at short notice; others compare options well ahead. We help both find useful information about your menus, experiences, location and availability, with clear websites and relevant campaigns that make the next step easier across your key seasons.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Local &amp; Destination Search Visibility</li>
                        <li><i class="fa-solid fa-check"></i> Clear Menus, Experience Details &amp; Booking Options</li>
                        <li><i class="fa-solid fa-check"></i> Seasonal Campaigns For Locals &amp; Visitors</li>
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
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing For Dining, Venues And Experiences</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-utensils"></i></div>
                    <h3>Local &amp; Destination Search</h3>
                    <p>Improve your website and business profile around the places, dining options and experiences your customers are searching for.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-star"></i></div>
                    <h3>Reviews &amp; Reputation</h3>
                    <p>Support honest feedback requests, monitor customer reviews and help your team prepare useful, professional responses.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <h3>Websites &amp; Booking Pathways</h3>
                    <p>Present menus, tour details and visitor information clearly, with enquiry forms or connections to your existing booking platform.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-share-nodes"></i></div>
                    <h3>Social &amp; Search Campaigns</h3>
                    <p>Promote relevant experiences, events and seasonal offers through campaigns planned around your audience, availability and budget.</p>
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
                    <h2>Ready To Fill Every Table?</h2>
                    <p>Get a free audit of your venue's local visibility and a plan to fill more bookings, every week.</p>
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
