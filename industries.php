<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/includes/seo.php';
$page_title = 'Digital Marketing by Industry';
$page_description = 'Digital marketing for trades, healthcare, dental, legal, real estate, NDIS, ecommerce and more. Find website, SEO and advertising support for your sector.';

// FAQ copy feeds both the visible accordion and the FAQPage schema.
$industryFaqs = [
    [
        'q' => 'Which industries do you work with?',
        'a' => 'We work with accounting and finance firms, automotive businesses, builders, dental and medical practices, online stores, franchises, hospitality venues, hotels and motels, law firms, real estate agencies, NDIS providers, trades and small businesses.',
    ],
    [
        'q' => 'Why does digital marketing need to suit my industry?',
        'a' => 'Customers search, compare and enquire differently in each sector. A tradie needs local visibility and quick quote requests, while a law firm needs detailed practice area content. We shape websites, SEO and advertising around how your customers make decisions.',
    ],
    [
        'q' => 'My industry is not listed. Can you still help?',
        'a' => 'Yes. The same principles apply to most service and retail businesses. <a href="/contact">Contact us</a> with a few details about your business and we will suggest a practical approach.',
    ],
    [
        'q' => 'Do you follow advertising rules for regulated industries?',
        'a' => 'We plan content and campaigns with the relevant platform policies and industry guidelines in mind, such as those for health and legal services, and ask you to confirm claims and professional details before anything goes live.',
    ],
];

$extra_head = seo_graph_tag([
    seo_breadcrumb_schema([['Home', '/'], ['Industries', '/industries']]),
    seo_faq_schema($industryFaqs),
]);
include 'header.php';
?>

    <!-- INDUSTRIES HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">INDUSTRIES WE SERVE</span>
            <h1 class="fade-up">DIGITAL MARKETING BUILT <br><span class="text-gradient">AROUND YOUR INDUSTRY.</span></h1>
            <p class="hero-subtitle fade-up">Your customers, services and sales process shape your marketing needs. Explore website, search and advertising services tailored to how people find and choose your business.</p>
        </div>
    </section>

    <!-- INDUSTRIES GRID SECTION -->
    <section class="industries-section fade-up" style="padding-top: 20px; padding-bottom: 100px;">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">SECTORS WE SUPPORT</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px; color: var(--navy);">Find The Right Approach For Your Business</h2>
                <div class="page-intro">
                    <p>Each industry page explains how we apply our <a href="/seo">SEO</a>, <a href="/ppc-advertising">paid advertising</a> and <a href="/website-development">website design</a> services to your sector, including the searches your customers use and the enquiries that matter most.</p>
                </div>
            </div>
            
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-calculator"></i></div>
                    <h3>Accounting &amp; Finance</h3>
                    <p>Websites, search optimisation and content that help prospective clients understand your services and expertise.</p>
                    <a href="/accounting-finance" class="industry-tile-btn" aria-label="Learn more about accounting and finance digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-car"></i></div>
                    <h3>Automotive</h3>
                    <p>Local search, websites and advertising that help customers find your automotive business and make enquiries.</p>
                    <a href="/automotive" class="industry-tile-btn" aria-label="Learn more about automotive digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-helmet-safety"></i></div>
                    <h3>Construction &amp; Building</h3>
                    <p>Project portfolios and targeted marketing that showcase your capabilities and support relevant project enquiries.</p>
                    <a href="/construction-building" class="industry-tile-btn" aria-label="Learn more about construction and building digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-tooth"></i></div>
                    <h3>Dental</h3>
                    <p>Local search and clear practice websites that help prospective patients explore services and request appointments.</p>
                    <a href="/dental" class="industry-tile-btn" aria-label="Learn more about dental digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                    <h3>Ecommerce</h3>
                    <p>Search, advertising and email campaigns that help online retailers reach shoppers and encourage repeat purchases.</p>
                    <a href="/ecommerce-industry" class="industry-tile-btn" aria-label="Learn more about ecommerce digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-store"></i></div>
                    <h3>Franchise</h3>
                    <p>Coordinated marketing that supports individual locations while maintaining a consistent brand across your franchise.</p>
                    <a href="/franchise" class="industry-tile-btn" aria-label="Learn more about franchise digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-user-doctor"></i></div>
                    <h3>Healthcare &amp; Medical</h3>
                    <p>Clear service information and local visibility that help people find your practice and understand their options.</p>
                    <a href="/healthcare-medical" class="industry-tile-btn" aria-label="Learn more about healthcare and medical digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-utensils"></i></div>
                    <h3>Hospitality &amp; Tourism</h3>
                    <p>Websites and local campaigns that help customers discover your venue, explore experiences and make bookings.</p>
                    <a href="/hospitality-tourism" class="industry-tile-btn" aria-label="Learn more about hospitality and tourism digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-bed"></i></div>
                    <h3>Hotel &amp; Motel</h3>
                    <p>Search and advertising campaigns that help travellers discover your property and explore direct booking options.</p>
                    <a href="/hotel-motel" class="industry-tile-btn" aria-label="Learn more about hotel and motel digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-scale-balanced"></i></div>
                    <h3>Legal &amp; Law</h3>
                    <p>Practice area content and search optimisation that help prospective clients understand your expertise and make enquiries.</p>
                    <a href="/legal-law" class="industry-tile-btn" aria-label="Learn more about legal and law digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-house"></i></div>
                    <h3>Real Estate</h3>
                    <p>Local search, property marketing and agent branding that support buyer, seller and landlord enquiries.</p>
                    <a href="/real-estate" class="industry-tile-btn" aria-label="Learn more about real estate digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-shop"></i></div>
                    <h3>Small Business</h3>
                    <p>Practical websites, search optimisation and advertising shaped around your services, priorities and available budget.</p>
                    <a href="/small-business-digital-marketing" class="industry-tile-btn" aria-label="Learn more about small business digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-hand-holding-heart"></i></div>
                    <h3>NDIS</h3>
                    <p>Clear websites and service information that help participants, families and support coordinators understand your offering.</p>
                    <a href="/ndis" class="industry-tile-btn" aria-label="Learn more about NDIS digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <h3>Trades</h3>
                    <p>Local search and service websites that help customers find your trade business and request quotes or repairs.</p>
                    <a href="/trades" class="industry-tile-btn" aria-label="Learn more about trades digital marketing">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- STATISTICS PROOF STRIP -->
    <section class="stats-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow" style="color: var(--cyan-neon);">PROVEN IMPACT</span>
                <h2 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem);">Driving Growth Across All Sectors</h2>
            </div>
            <div class="stats-4-grid">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <p class="stat-value">Top 3</p>
                    <p>Avg. Local Map Ranking</p>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-user-plus"></i></div>
                    <p class="stat-value">+193</p>
                    <p>Leads Generated</p>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-arrow-trend-down"></i></div>
                    <p class="stat-value">-31%</p>
                    <p>Cost Per Lead</p>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                    <p class="stat-value">+68%</p>
                    <p>Revenue Growth</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="services-section fade-up">
        <div class="container" style="max-width: 900px;">
            <div class="section-header">
                <span class="eyebrow">FAQ</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Industry Marketing Questions</h2>
            </div>
            <div class="faq-list" style="margin-top: clamp(30px, 5vw, 50px);">
<?php foreach ($industryFaqs as $i => $f): ?>
                <details class="faq-item"<?php echo $i === 0 ? ' open' : ''; ?>>
                    <summary><h3><?php echo htmlspecialchars($f['q'], ENT_QUOTES, 'UTF-8'); ?></h3></summary>
                    <p><?php echo $f['a']; ?></p>
                </details>
<?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- PREMIUM FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Grow In Your Industry?</h2>
                    <p>Partner with W&amp;S Digital Marketing to build a custom, data-driven growth plan tailored specifically to your market sector.</p>
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
