<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/includes/seo.php';
$page_title = 'Digital Marketing Services Australia';
$page_description = 'Explore SEO, Google Ads, social media, websites, ecommerce, graphic design and content writing for Australian businesses. Request a free growth plan.';

// [anchor id, icon, title, image, image alt, description, link]
$services = [
    ['seo', 'fa-magnifying-glass-chart', 'Search Engine Optimisation', '/images/photos/seo.webp', 'Laptop on an office desk showing a website analytics dashboard with a rising traffic graph', 'Improve visibility in Google search and Maps with technical SEO, local optimisation, useful content and relevant outreach.', '/seo'],
    ['ecom', 'fa-cart-shopping', 'Ecommerce', '/images/photos/ecommerce.webp', 'Online store owner taping up a customer order in a packing room, with a laptop showing sales and parcels ready to ship', 'Shopify and WooCommerce stores with organised catalogues, clear product pages and a straightforward checkout.', '/ecommerce'],
    ['web', 'fa-laptop-code', 'Website Design &amp; Development', '/images/photos/website-development.webp', 'Web developer working on website code and a WordPress page builder across two monitors, with the finished site shown on a laptop and smartphone', 'Fast, mobile-friendly websites that explain your services clearly and make it easy for visitors to get in touch.', '/website-development'],
    ['social', 'fa-share-nodes', 'Social Media Marketing', '/images/socialMedia.webp', 'Social media app icons floating around a smartphone', 'Planned content and community management across Facebook, Instagram and TikTok to build your brand presence.', '/social-media'],
    ['ppc', 'fa-hand-pointer', 'Paid Advertising', '/images/photos/paid-advertising.webp', 'Marketing specialist reviewing paid advertising campaign charts on a laptop and taking notes in an office', 'Google Ads and Meta Ads campaigns targeted at people searching for your services, with clear tracking of calls and enquiries.', '/ppc-advertising'],
    ['graphic', 'fa-palette', 'Graphic Design', '/images/photos/graphic-design.webp', 'Graphic designer creating a logo in design software with a drawing tablet, colour swatches and logo sketches on the desk', 'Logos, brand identity, ad creative and marketing materials that stay consistent across every channel.', '/graphic-design'],
    ['content', 'fa-pen-nib', 'Content Writing', '/images/photos/content-writing.webp', 'Content writer typing a blog article on a laptop at a home office desk with a notebook of headline ideas', 'Website copy, blog articles and email content written around your audience, brand voice and relevant searches.', '/content-writing'],
];

// FAQ copy feeds both the visible accordion and the FAQPage schema.
$servicesFaqs = [
    [
        'q' => 'What digital marketing services do you offer?',
        'a' => 'We provide search engine optimisation, paid advertising on Google and Meta, social media marketing, website design and development, ecommerce stores, graphic design and content writing.',
    ],
    [
        'q' => 'Which service should I start with?',
        'a' => 'It depends on your goals, budget and current website. Many businesses start with a website and local SEO foundation, then add paid advertising for faster enquiries. Our free growth plan recommends a starting point for your situation.',
    ],
    [
        'q' => 'Can I combine more than one service?',
        'a' => 'Yes. Services can be combined into one plan, for example a new website with SEO and content, or paid ads supported by social media. Combined plans are coordinated by one team so your messaging stays consistent.',
    ],
    [
        'q' => 'Do you work with businesses across Australia?',
        'a' => 'Yes. We are based in NSW and work with businesses across Australia, from local service providers to online stores. See the <a href="/industries">industries we support</a> for sector-specific approaches.',
    ],
    [
        'q' => 'How much do your services cost?',
        'a' => 'Pricing depends on the services, scope and competition in your market. Our <a href="/pricing">pricing plans</a> outline typical packages, and we confirm a quote after reviewing your requirements.',
    ],
];

$serviceListItems = [];
foreach ($services as $i => $svc) {
    $serviceListItems[] = [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => html_entity_decode($svc[2], ENT_QUOTES, 'UTF-8'),
        'url' => SEO_SITE_BASE . $svc[6],
    ];
}
$extra_head = seo_graph_tag([
    [
        '@type' => 'ItemList',
        'name' => 'Digital Marketing Services',
        'itemListElement' => $serviceListItems,
    ],
    seo_breadcrumb_schema([['Home', '/'], ['Services', '/services']]),
    seo_faq_schema($servicesFaqs),
]);
include 'header.php';
?>

    <!-- SERVICES HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">OUR SERVICES</span>
            <h1 class="fade-up">FULL-SERVICE DIGITAL MARKETING <br><span class="text-gradient">BUILT TO CONVERT.</span></h1>
            <p class="hero-subtitle fade-up">From search engine optimisation and paid ads to websites, ecommerce and brand content, every service is planned around your goals and the customers you want to reach.</p>
            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary">GET MY FREE GROWTH PLAN <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="/case-studies" class="btn-secondary">VIEW OUR WORK</a>
            </div>
        </div>
    </section>

    <!-- SERVICES OVERVIEW GRID: same image cards as the homepage (css: .svc-*); $services is defined at the top -->
    <section class="services-section svc-section fade-up">
        <div class="container">
            <div class="section-header page-intro">
                <span class="eyebrow">WHAT WE DO</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Seven Digital Marketing Services. One Growth Plan.</h2>
                <p>W&amp;S Digital Marketing helps Australian businesses get found, explain what they offer and turn visitors into enquiries and sales. Choose a single service or combine several. Each one connects to a clear goal, whether that is ranking in local search, launching an online store or running campaigns that bring in leads.</p>
            </div>
            <div class="svc-grid">
                <?php foreach ($services as $i => [$id, $icon, $title, $img, $alt, $desc, $href]): ?>
                <article class="svc-card" id="<?php echo $id; ?>" style="scroll-margin-top: 110px;">
                    <div class="svc-media"><img <?php echo $i < 3 ? '' : 'loading="lazy" '; ?>decoding="async" src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="svc-shade"></div>
                    <div class="svc-body">
                        <div class="svc-top">
                            <span class="svc-icon"><i class="fa-solid <?php echo $icon; ?>"></i></span>
                            <span class="svc-num" aria-hidden="true"><?php echo sprintf('%02d', $i + 1); ?></span>
                        </div>
                        <h3><?php echo $title; ?></h3>
                        <p><?php echo $desc; ?></p>
                        <a href="<?php echo $href; ?>" class="svc-link" aria-label="Learn More &ndash; <?php echo htmlspecialchars(html_entity_decode($title, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8'); ?>">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="services-section fade-up">
        <div class="container" style="max-width: 900px;">
            <div class="section-header">
                <span class="eyebrow">FAQ</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Questions About Our Services</h2>
            </div>
            <div class="faq-list" style="margin-top: clamp(30px, 5vw, 50px);">
<?php foreach ($servicesFaqs as $i => $f): ?>
                <details class="faq-item"<?php echo $i === 0 ? ' open' : ''; ?>>
                    <summary><h3><?php echo htmlspecialchars($f['q'], ENT_QUOTES, 'UTF-8'); ?></h3></summary>
                    <p><?php echo $f['a']; ?></p>
                </details>
<?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Not Sure Which Service You Need?</h2>
                    <p>Book a zero-obligation audit and we'll recommend the mix of services that suits your goals and budget.</p>
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
