<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Automotive Digital Marketing';
$page_description = 'Explore local SEO, websites and advertising for automotive businesses. Help customers find your services and make booking or sales enquiries.';
include 'header.php';
?>

    <!-- AUTOMOTIVE HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">AUTOMOTIVE</span>
            <h1 class="fade-up">HELP LOCAL CUSTOMERS <br><span class="text-gradient">FIND YOUR BUSINESS.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for repair workshops, dealerships and detailers &mdash; helping customers discover your business, explore your offering and make service or vehicle enquiries.</p>
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
                    <img loading="lazy" decoding="async" src="/images/industries/automotive.webp" alt="Automotive Digital Marketing" onerror="this.remove()">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Make Your Business Easier To Find And Choose</h2>
                    <p>Whether someone needs a repair, is comparing vehicles or wants detailing, clear information helps them decide who to contact. We improve how your business appears online, with relevant service or vehicle information, local search optimisation and straightforward ways to call, enquire or request a booking.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Google Business Profile &amp; Local Search Optimisation</li>
                        <li><i class="fa-solid fa-check"></i> Clear Service Pages &amp; Vehicle Enquiry Options</li>
                        <li><i class="fa-solid fa-check"></i> Customer Feedback &amp; Reputation Support</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="margin-top: 10px;">SPEAK WITH OUR TEAM</a>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW WE CAN HELP -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">HOW WE CAN HELP</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing For Your Automotive Business</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <h3>Local Search Visibility</h3>
                    <p>Improve your business profile and website content around the services, vehicles and locations relevant to your customers.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-star"></i></div>
                    <h3>Reviews &amp; Reputation</h3>
                    <p>Support honest customer feedback requests, monitor reviews and help your team respond professionally to customer experiences.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <h3>Websites &amp; Enquiry Options</h3>
                    <p>Create clear pages with calls, enquiry forms and links to existing booking systems, with additional integrations agreed separately.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-bullhorn"></i></div>
                    <h3>Search &amp; Social Advertising</h3>
                    <p>Use Google Search ads to reach relevant searches and social campaigns to introduce your offering to suitable audiences.</p>
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
                    <h2>Ready To Grow Your Automotive Business?</h2>
                    <p>Get a free audit of your business's digital presence and a plan to attract more service and sales enquiries.</p>
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
