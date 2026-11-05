<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Real Estate Digital Marketing';
$page_description = "Build your agency's local presence with search, agent branding and property campaigns. Support listing enquiries, inspection requests and appraisals.";
include 'header.php';
?>

    <!-- REAL ESTATE HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">REAL ESTATE</span>
            <h1 class="fade-up">BUILD YOUR LOCAL PRESENCE. <br><span class="text-gradient">SHOWCASE YOUR EXPERTISE.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for real estate agents and agencies &mdash; helping buyers discover properties and prospective sellers understand your services, local knowledge and approach.</p>
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
                    <img loading="lazy" decoding="async" src="/images/photos/real-estate.webp" alt="Hand holding a tablet showing an online property listing in front of a modern Australian home with a for sale sign at dusk" onerror="this.remove()">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Connect Your Agency With Buyers And Sellers</h2>
                    <p>Buyers want useful property information, while sellers need to understand who will represent them. We develop your local search presence, agent profiles and campaigns around these different needs, with clear pathways for property enquiries, inspection requests and appraisal bookings.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Local Search &amp; Useful Suburb Content</li>
                        <li><i class="fa-solid fa-check"></i> Consistent Agent &amp; Agency Branding</li>
                        <li><i class="fa-solid fa-check"></i> Property Enquiry &amp; Appraisal Campaigns</li>
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
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing For Your Agency, Agents And Listings</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-house"></i></div>
                    <h3>Local &amp; Suburb Search</h3>
                    <p>Develop useful suburb and service pages that help people discover your agency and understand the areas you work in.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-id-badge"></i></div>
                    <h3>Agent &amp; Agency Branding</h3>
                    <p>Present your experience, local knowledge and approach consistently across agent profiles, website content and campaign materials.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-bullhorn"></i></div>
                    <h3>Property Listing Campaigns</h3>
                    <p>Promote current listings through agreed advertising channels, using accurate property details and clear inspection or enquiry options.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-user-plus"></i></div>
                    <h3>Seller &amp; Appraisal Campaigns</h3>
                    <p>Create campaigns and landing pages that explain your selling services and help property owners request an appraisal.</p>
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
                    <h2>Ready To Win More Listings?</h2>
                    <p>Get a free audit of your agency's digital presence and a plan to win more listings and deals.</p>
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
