<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Digital Marketing Services';
$page_description = 'Explore SEO, Google Ads, social media, websites, ecommerce, graphic design and copywriting. Find practical marketing services suited to your business.';
include 'header.php';
?>

    <!-- SERVICES HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">OUR SERVICES</span>
            <h1 class="fade-up">FULL-SERVICE DIGITAL MARKETING <br><span class="text-gradient">BUILT TO CONVERT.</span></h1>
            <p class="hero-subtitle fade-up">From organic search to paid ads, ecommerce builds to brand content — every service we offer is engineered around one goal: measurable revenue growth.</p>
        </div>
    </section>

    <!-- SERVICES OVERVIEW GRID: same image cards as the homepage (css: .svc-*) -->
    <?php
    // [anchor id, icon, title, image, image alt, description, link]
    $services = [
        ['seo', 'fa-magnifying-glass-chart', 'Search Engine Optimisation', '/images/photos/seo.webp', 'Laptop on an office desk showing a website analytics dashboard with a rising traffic graph', 'Dominate local search and Google Maps with technical SEO, content, and link-building strategies that attract high-intent organic traffic.', '/seo'],
        ['ecom', 'fa-cart-shopping', 'Ecommerce', '/images/photos/ecommerce.webp', 'Online store owner taping up a customer order in a packing room, with a laptop showing sales and parcels ready to ship', 'Shopify and WooCommerce builds, catalog strategy, and conversion-focused checkout flows that turn browsers into repeat customers.', '/ecommerce'],
        ['web', 'fa-laptop-code', 'Website Design &amp; Development', '/images/photos/website-development.webp', 'Web developer working on website code and a WordPress page builder across two monitors, with the finished site shown on a laptop and smartphone', 'Fast, mobile-responsive websites engineered specifically for conversion rate optimization, not just aesthetics.', '/website-development'],
        ['social', 'fa-share-nodes', 'Social Media Marketing', '/images/socialMedia.webp', 'Social media app icons floating around a smartphone', 'Strategic content and community management across Meta and TikTok to build brand presence and engage your target audience.', '/social-media'],
        ['ppc', 'fa-hand-pointer', 'Paid Advertising', '/images/photos/paid-advertising.webp', 'Marketing specialist reviewing paid advertising campaign charts on a laptop and taking notes in an office', 'Google Ads and paid social campaigns built for instant, high-converting traffic, more phone calls, and maximum ROAS.', '/ppc-advertising'],
        ['graphic', 'fa-palette', 'Graphic Design', '/images/photos/graphic-design.webp', 'Graphic designer creating a logo in design software with a drawing tablet, colour swatches and logo sketches on the desk', 'Brand identity, ad creative, and marketing collateral designed to stand out and stay consistent across every channel.', '/graphic-design'],
        ['content', 'fa-pen-nib', 'Content Writing', '/images/photos/content-writing.webp', 'Content writer typing a blog article on a laptop at a home office desk with a notebook of headline ideas', 'SEO-optimized copy, blog content, and website content that ranks, builds authority, and speaks directly to your customers.', '/content-writing'],
    ];
    ?>
    <section class="services-section svc-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">WHAT WE DO</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Seven Disciplines. One Growth Engine.</h2>
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

    <!-- FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Not Sure Which Service You Need?</h2>
                    <p>Book a zero-obligation audit and we'll recommend the exact mix of services to hit your growth goals.</p>
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
