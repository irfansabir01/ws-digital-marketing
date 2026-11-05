<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Small Business Digital Marketing';
$page_description = 'Explore websites, local search and focused advertising for Australian small businesses. Get clear marketing priorities shaped around your goals and budget.';
include 'header.php';
?>

    <!-- SMALL BUSINESS DIGITAL MARKETING HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">SMALL BUSINESS</span>
            <h1 class="fade-up">PRACTICAL MARKETING. <br><span class="text-gradient">CLEAR PRIORITIES FOR YOUR BUSINESS.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for small and local businesses &mdash; bringing together websites, search and advertising in a plan shaped around your customers, goals and budget.</p>
            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary">GET MY FREE GROWTH PLAN <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="/industries" class="btn-secondary">VIEW ALL INDUSTRIES</a>
            </div>
        </div>
    </section>

    <!-- INTRO SECTION -->
    <section class="agency-section fade-up">
        <div class="container">
            <div class="agency-grid">
                <div class="agency-image-wrapper">
                    <img loading="lazy" decoding="async" src="/images/photos/small-business.webp" alt="Smiling local shop owner handing a wrapped gift to a customer across the counter, with an open sign on the door">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Put Your Budget Behind Clear Priorities</h2>
                    <p>Running a small business means choosing carefully where to spend your time and money. We review your current marketing, identify practical improvements and recommend a manageable scope of work. You'll understand what is included, what it costs and how progress will be reviewed.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Marketing Priorities Matched To Your Budget</li>
                        <li><i class="fa-solid fa-check"></i> Clear Website &amp; Local Search Improvements</li>
                        <li><i class="fa-solid fa-check"></i> Campaign Reporting In Plain Language</li>
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
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing Support That Fits Your Business</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-magnifying-glass-dollar"></i></div>
                    <h3>Local Search Improvements</h3>
                    <p>Help nearby customers find your business through accurate profile information, useful service pages and relevant local content.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-globe"></i></div>
                    <h3>Small Business Websites</h3>
                    <p>Create a professional website with clear service information and enquiry options, with features and costs agreed before development.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-bullhorn"></i></div>
                    <h3>Focused Advertising Campaigns</h3>
                    <p>Plan campaigns around selected services, locations and an agreed budget, with advertising spend separate from management fees.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <h3>Clear Progress Reporting</h3>
                    <p>Review agreed measures such as website enquiries and campaign activity, explaining what the available tracking shows and where gaps remain.</p>
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
                    <h2>Ready To Grow Without Overspending?</h2>
                    <p>Get a free growth plan built around your budget, not a one-size-fits-all retainer.</p>
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
