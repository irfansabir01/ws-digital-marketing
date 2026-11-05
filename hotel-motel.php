<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Hotel & Motel Digital Marketing';
$page_description = 'Showcase your hotel or motel with clear room information, search visibility and targeted campaigns. Help guests explore your property and book direct.';
include 'header.php';
?>

    <!-- HOTEL & MOTEL HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">HOTEL &amp; MOTEL</span>
            <h1 class="fade-up">SHOWCASE YOUR PROPERTY. <br><span class="text-gradient">SUPPORT DIRECT BOOKINGS.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for hotels and motels &mdash; helping travellers discover your property, compare room options and book through a clear, convenient website experience.</p>
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
                    <img loading="lazy" decoding="async" src="/images/photos/hotel-motel.webp" alt="Hotel receptionist welcoming a smiling couple with a suitcase who show their direct booking confirmation on a phone" onerror="this.remove()">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Give Guests A Clear Path To Booking Direct</h2>
                    <p>Your website should help guests understand your rooms, facilities, location and booking conditions. We strengthen that experience through search visibility, useful property content and targeted campaigns, supporting direct reservations alongside your existing booking channels and seasonal priorities.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Property &amp; Destination Search Visibility</li>
                        <li><i class="fa-solid fa-check"></i> Clear Room Information &amp; Direct Booking Pathways</li>
                        <li><i class="fa-solid fa-check"></i> Campaigns For Prospective &amp; Returning Guests</li>
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
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing Built Around Your Property And Guests</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-bed"></i></div>
                    <h3>Property &amp; Destination Search</h3>
                    <p>Improve visibility for relevant accommodation and destination searches through useful property pages, local information and business profile updates.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-globe"></i></div>
                    <h3>Direct Booking Websites</h3>
                    <p>Present rooms, facilities and booking conditions clearly, with links or agreed integrations connecting guests to your booking system.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-star"></i></div>
                    <h3>Guest Reviews &amp; Reputation</h3>
                    <p>Support honest guest feedback requests and review monitoring, helping your team respond professionally across relevant platforms.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-arrows-rotate"></i></div>
                    <h3>Guest Advertising &amp; Email</h3>
                    <p>Use targeted advertising and email campaigns to reach prospective guests and reconnect with subscribers who have agreed to receive marketing.</p>
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
                    <h2>Ready To Win More Direct Bookings?</h2>
                    <p>Get a free audit of your property's digital presence and a plan to grow direct revenue.</p>
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
