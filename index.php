<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/case-study-helpers.php';

// Reporting period shown in the laptop dashboard chip, the mobile overview
// chip and under the "Combined Results From Our Client Work" heading. Once
// weekly stats are being entered in the admin it runs up to the latest
// week automatically (e.g. "May 2026 - 26 Oct 2026").
$statsPeriod = 'May - Sept 2026';
$latestWeekEnd = weekly_stats_latest_end();
if ($latestWeekEnd !== null) {
    $statsPeriod = 'May 2026 - ' . format_week_date($latestWeekEnd);
}

// Every case study's "Results At A Glance" stats combined, read live from
// the database on each page load -- adding, editing or deleting a case
// study (or its stats) in the admin updates every number below with no
// code change. The headline four feed the scatter boxes, laptop cards,
// mobile overview and the Combined Results section.
$combinedStats = fetch_all_combined_stats();
$headlineStats = array_slice($combinedStats, 0, 4);
$hasRealStats = count($headlineStats) > 0;
if (!$hasRealStats) {
    // Fallback only -- shown when the database is unreachable or no case
    // study has stats yet.
    $headlineStats = [
        ['2.71K', 'Total Clicks'],
        ['71.9K', 'Total Impressions'],
        ['3.8%', 'Average CTR'],
        ['12.5', 'Average Position'],
    ];
}
$dashTitle = $hasRealStats ? 'Combined Client Results' : 'SEO Ranking Results';
$scatterIcons = ['fa-arrow-trend-up', 'fa-chart-column', 'fa-bullseye', 'fa-trophy'];

// Both spellings of the brand ("W&S" and "WS") lead the title and description:
// people search either way, and the ampersand is dropped by Google when matching.
// The title is the full brand-led title, so the site-name suffix is skipped.
$page_title = 'W&S Digital Marketing | WS Digital Marketing Agency Sydney';
$page_no_suffix = true;
$page_description = 'W&S Digital Marketing (WS Digital Marketing) is a Sydney agency helping Australian businesses grow with SEO, Google Ads, social media and web design.';
$extra_head = <<<'HTML'
<script type="application/ld+json" data-origin="code">
{
  "@context": "https://schema.org",
  "@type": ["Organization", "ProfessionalService"],
  "@id": "https://wsdigitalmarketing.com.au/#organization",
  "name": "W&S Digital Marketing",
  "alternateName": ["WS Digital Marketing", "W and S Digital Marketing", "W & S Digital Marketing", "WandS Digital Marketing"],
  "url": "https://wsdigitalmarketing.com.au/",
  "description": "Digital marketing agency providing SEO, Google Ads, social media management, website development, ecommerce development, graphic design and content writing services for Australian businesses.",
  "image": "https://wsdigitalmarketing.com.au/images/og-default.jpg",
  "logo": {
    "@type": "ImageObject",
    "url": "https://wsdigitalmarketing.com.au/images/logo-ws.webp",
    "contentUrl": "https://wsdigitalmarketing.com.au/images/logo-ws.webp",
    "width": 766,
    "height": 326
  },
  "telephone": "+61403889630",
  "email": "info@wsdigitalmarketing.com.au",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "3 Weebill St",
    "addressLocality": "Melonba",
    "addressRegion": "NSW",
    "postalCode": "2765",
    "addressCountry": "AU"
  },
  "areaServed": [
    { "@type": "City", "name": "Sydney" },
    { "@type": "Country", "name": "Australia" }
  ],
  "knowsAbout": ["Search engine optimisation", "Google Ads", "Social media marketing", "Website design and development", "Ecommerce development", "Local SEO"],
  "sameAs": [
    "https://www.facebook.com/w.and.s.digital.marketing",
    "https://www.instagram.com/wsdigitalmarketing",
    "https://www.linkedin.com/company/w-s-digital-marketing/"
  ]
}
</script>
<script type="application/ld+json" data-origin="code">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "@id": "https://wsdigitalmarketing.com.au/#website",
  "name": "W&S Digital Marketing",
  "alternateName": ["WS Digital Marketing", "W and S Digital Marketing", "W & S Digital Marketing", "WandS Digital Marketing"],
  "url": "https://wsdigitalmarketing.com.au/",
  "inLanguage": "en-AU",
  "publisher": { "@id": "https://wsdigitalmarketing.com.au/#organization" }
}
</script>
<script type="application/ld+json" data-origin="code">
{
  "@context": "https://schema.org",
  "@type": "WebPage",
  "@id": "https://wsdigitalmarketing.com.au/#webpage",
  "url": "https://wsdigitalmarketing.com.au/",
  "name": "W&S Digital Marketing | WS Digital Marketing Agency Sydney",
  "inLanguage": "en-AU",
  "isPartOf": { "@id": "https://wsdigitalmarketing.com.au/#website" },
  "about": { "@id": "https://wsdigitalmarketing.com.au/#organization" },
  "primaryImageOfPage": {
    "@type": "ImageObject",
    "url": "https://wsdigitalmarketing.com.au/images/og-default.jpg",
    "width": 1200,
    "height": 630
  }
}
</script>
HTML;
include 'header.php';
?>

    <!-- SECTION 1: HERO -->
    <section id="hero" class="hero">
        <div class="container hero-content">
            <span class="eyebrow fade-up">YOUR BUSINESS. OUR FOCUS.</span>
            <h1 class="fade-up">DIGITAL MARKETING <br><span class="text-gradient">BUILT TO GROW YOUR BUSINESS.</span></h1>
            <p class="hero-subtitle fade-up">SEO, Google Ads, social media and website design that help Australian businesses attract the right customers, generate more enquiries and increase online sales.</p>

            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary">GET MY FREE GROWTH PLAN <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="/case-studies" class="btn-secondary">VIEW OUR WORK</a>
            </div>
        </div>
    </section>

    <!-- SECTION 2: MASTER CINEMATIC STAGE -->
    <div class="cinematic-wrapper">
        <div class="devices-stage">
            <div class="stage-header">
                <span class="eyebrow">Real Time Analytics</span>
                <h2>MARKETING THAT MOVES THE NUMBERS</h2>
            </div>
            
            <?php foreach ($headlineStats as $i => [$value, $label]): ?>
            <div class="scatter-item fs-<?php echo $i + 1; ?>"><span><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></span> <div class="val"><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?> <i class="fa-solid <?php echo $scatterIcons[$i]; ?>"></i></div></div>
            <?php endforeach; ?>
            
            <!-- Laptop (Hidden on Mobile) -->
            <div class="laptop-container">
                <div class="mac-screen-bezel">
                    <div class="mac-camera"></div>
                    <div class="dashboard-ui">
                        <div class="dash-sidebar">
                            <div class="icon" style="background: var(--cyan-neon);"></div>
                            <div class="icon"></div>
                            <div class="icon"></div>
                        </div>
                        <div class="dash-main">
                            <div class="dash-topbar" style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="dash-title"><?php echo htmlspecialchars($dashTitle, ENT_QUOTES, 'UTF-8'); ?></div>
                                <span style="font-size: 11px; color: var(--text-muted); background: var(--bg-secondary); padding: 4px 12px; border-radius: 15px; border: 1px solid var(--border-light);"><?php echo htmlspecialchars($statsPeriod, ENT_QUOTES, 'UTF-8'); ?></span>
                            </div>
                            <div class="dash-cards">
                                <?php foreach ($headlineStats as [$value, $label]): ?>
                                <div class="dash-card"><div class="dash-card-title"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></div><div class="dash-card-value"><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></div></div>
                                <?php endforeach; ?>
                            </div>
                            <div class="dash-chart-area">
                                <span style="font-weight: 700; font-size: 0.85rem; color: var(--navy); z-index: 10; position: relative;">SEO Ranking Growth Analytics</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mac-base"><div class="mac-notch"></div></div>
            </div>

            <!-- Mobile Phone -->
            <div class="mobile-container">
                <div class="iphone-island"></div>
                <div class="phone-inner">
                    <div class="serp-screen-wrapper">
                        <div class="serp-screen">
                            <div class="serp-item">
                                <a href="https://wsdigitalmarketing.com.au" target="_blank" rel="noopener noreferrer" class="serp-link">
                                    <div class="serp-url"><img loading="lazy" decoding="async" src="https://wsdigitalmarketing.com.au/favicon.ico" alt="W&amp;S Digital Marketing favicon" onerror="this.style.display='none'"> wsdigitalmarketing.com.au</div>
                                    <div class="serp-title">W&S Digital Marketing | #1 Agency in Australia</div>
                                    <div class="serp-desc">Drive high-intent traffic, build custom sites, and scale revenue.</div>
                                </a>
                            </div>
                            <div class="serp-item">
                                <a href="https://wsdigitalmarketing.com.au/seo" target="_blank" rel="noopener noreferrer" class="serp-link">
                                    <div class="serp-url"><img loading="lazy" decoding="async" src="https://wsdigitalmarketing.com.au/favicon.ico" alt="W&amp;S Digital Marketing favicon" onerror="this.style.display='none'"> wsdigitalmarketing.com.au › seo</div>
                                    <div class="serp-title">Search Engine Optimisation Services</div>
                                    <div class="serp-desc">Dominate local search results and Google Maps effortlessly.</div>
                                </a>
                            </div>
                            <div class="serp-item">
                                <a href="https://wsdigitalmarketing.com.au/ppc" target="_blank" rel="noopener noreferrer" class="serp-link">
                                    <div class="serp-url"><img loading="lazy" decoding="async" src="https://wsdigitalmarketing.com.au/favicon.ico" alt="W&amp;S Digital Marketing favicon" onerror="this.style.display='none'"> wsdigitalmarketing.com.au › ppc</div>
                                    <div class="serp-title">Google Ads & PPC Management</div>
                                    <div class="serp-desc">Maximize your ROAS and get instant high-converting calls.</div>
                                </a>
                            </div>
                            <div class="serp-item">
                                <a href="https://wsdigitalmarketing.com.au/web-design" target="_blank" rel="noopener noreferrer" class="serp-link">
                                    <div class="serp-url"><img loading="lazy" decoding="async" src="https://wsdigitalmarketing.com.au/favicon.ico" alt="W&amp;S Digital Marketing favicon" onerror="this.style.display='none'"> wsdigitalmarketing.com.au › web-design</div>
                                    <div class="serp-title">Web Design & CRO Solutions</div>
                                    <div class="serp-desc">Fast, responsive websites engineered specifically to convert.</div>
                                </a>
                            </div>
                            <div class="serp-item">
                                <a href="https://wsdigitalmarketing.com.au/contact" target="_blank" rel="noopener noreferrer" class="serp-link">
                                    <div class="serp-url"><img loading="lazy" decoding="async" src="https://wsdigitalmarketing.com.au/favicon.ico" alt="W&amp;S Digital Marketing favicon" onerror="this.style.display='none'"> wsdigitalmarketing.com.au › contact</div>
                                    <div class="serp-title">Contact W&S Digital - Get a Free Plan</div>
                                    <div class="serp-desc">Ready to scale your business revenue? Get in touch today.</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="logo-screen">
                        <img loading="lazy" decoding="async" src="/images/logo-ws.webp" alt="W&amp;S Digital Marketing animated logo">
                        <div class="logo-screen-sub">Measurable Growth</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MOBILE EXCLUSIVE PERFORMANCE OVERVIEW -->
    <div class="mobile-perf-overview fade-up">
        <div class="container">
            <div class="dash-ui-mobile">
                <div class="dash-topbar" style="margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <div class="dash-title" style="font-weight: 700; font-size: 1.1rem; color: var(--navy);"><?php echo htmlspecialchars($dashTitle, ENT_QUOTES, 'UTF-8'); ?></div>
                    <span style="font-size: 11px; color: var(--text-muted); background: var(--bg-secondary); padding: 5px 12px; border-radius: 15px; border: 1px solid var(--border-light);"><?php echo htmlspecialchars($statsPeriod, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <div class="mobile-dash-cards">
                    <?php foreach ($headlineStats as [$value, $label]): ?>
                    <div class="m-dash-card"><div class="m-dash-card-title"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></div><div class="m-dash-card-value"><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></div></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($hasRealStats): ?>
    <!-- COMBINED RESULTS: the same headline four combined stats as the
         dashboards above, pulled live (see fetch_all_combined_stats()). -->
    <section class="stats-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow" style="color: var(--cyan-neon);">PROVEN RESULTS</span>
                <h2 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem);">Combined Results From Our Client Work</h2>
                <span style="display: inline-block; margin-top: 12px; font-size: 0.85rem; font-weight: 600; color: rgba(255,255,255,0.85); background: rgba(255,255,255,0.08); padding: 6px 16px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.2);"><?php echo htmlspecialchars($statsPeriod, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="stats-4-grid">
                <?php foreach ($headlineStats as [$value, $label]): ?>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-arrow-trend-up"></i></div>
                    <h3><?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- TRUSTED STRIP (MARQUEE) -->
    <div class="trusted-strip-wrapper fade-up">
        <div class="partner-marquee">
            <div class="partner-marquee-track">
                <div class="partner-strip">
                    <div><i class="fa-brands fa-google" style="color: #4285F4; margin-right: 5px;"></i> Google <span>Partner</span></div>
                    <div><i class="fa-brands fa-meta" style="color: #0668E1; margin-right: 5px;"></i> Meta <span>Business</span></div>
                    <div><i class="fa-solid fa-chart-simple" style="color: #F26722; margin-right: 5px;"></i> SEMRUSH</div>
                    <div><i class="fa-solid fa-brain" style="color: #D97706; margin-right: 5px;"></i> CLAUDE</div>
                    <div><i class="fa-brands fa-shopify" style="color: #96bf48; margin-right: 5px;"></i> shopify</div>
                </div>
                <div class="partner-strip" aria-hidden="true">
                    <div><i class="fa-brands fa-google" style="color: #4285F4; margin-right: 5px;"></i> Google <span>Partner</span></div>
                    <div><i class="fa-brands fa-meta" style="color: #0668E1; margin-right: 5px;"></i> Meta <span>Business</span></div>
                    <div><i class="fa-solid fa-chart-simple" style="color: #F26722; margin-right: 5px;"></i> SEMRUSH</div>
                    <div><i class="fa-solid fa-brain" style="color: #D97706; margin-right: 5px;"></i> CLAUDE</div>
                    <div><i class="fa-brands fa-shopify" style="color: #96bf48; margin-right: 5px;"></i> shopify</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SERVICES SECTION: image-forward cards. Every photo is visible as soon
         as the section scrolls into view (js/main.js reveals the cards with a
         staggered wipe); nothing needs a click or hover to show its image.
         services.php keeps its own flip-card layout. -->
    <?php
    // [icon, title, image, image alt, description, link, link aria-label]
    $homeServices = [
        ['fa-magnifying-glass-chart', 'Search Engine Optimisation', '/images/photos/seo.webp', 'Laptop on an office desk showing a website analytics dashboard with a rising traffic graph', 'Improve your visibility in Google Search and Maps with technical improvements, useful content and local optimisation that help relevant customers find your business.', '/seo', 'Learn More &ndash; Search Engine Optimisation'],
        ['fa-cart-shopping', 'Ecommerce', '/images/photos/ecommerce.webp', 'Online store owner taping up a customer order in a packing room, with a laptop showing sales and parcels ready to ship', 'Build or improve your Shopify or WooCommerce store with clear product information, organised collections and a straightforward shopping experience from browsing to checkout.', '/ecommerce', 'Learn More &ndash; Ecommerce'],
        ['fa-laptop-code', 'Website Design & Development', '/images/photos/website-development.webp', 'Web developer working on website code and a WordPress page builder across two monitors, with the finished site shown on a laptop and smartphone', 'Fast, mobile-friendly websites with clear navigation and practical features that make it easier for customers to enquire, book a service or buy.', '/website-development', 'Learn More &ndash; Website Design &amp; Development'],
        ['fa-share-nodes', 'Social Media Marketing', '/images/socialMedia.webp', 'Social media app icons floating around a smartphone', 'Keep your social channels active with planned content, consistent posting and community engagement that help customers understand and connect with your business.', '/social-media', 'Learn More &ndash; Social Media Marketing'],
        ['fa-hand-pointer', 'Paid Advertising', '/images/photos/paid-advertising.webp', 'Marketing specialist reviewing paid advertising campaign charts on a laptop and taking notes in an office', 'Reach relevant customers through Google Ads and paid social campaigns, with ongoing testing and reporting focused on enquiries, purchases and advertising costs.', '/ppc-advertising', 'Learn More &ndash; Paid Advertising'],
        ['fa-palette', 'Graphic Design', '/images/photos/graphic-design.webp', 'Graphic designer creating a logo in design software with a drawing tablet, colour swatches and logo sketches on the desk', 'Create a consistent business identity with logo design, social media graphics, advertising creative and marketing materials that reflect your brand across every channel.', '/graphic-design', 'Learn More &ndash; Graphic Design'],
        ['fa-pen-nib', 'Content Writing', '/images/photos/content-writing.webp', 'Content writer typing a blog article on a laptop at a home office desk with a notebook of headline ideas', 'Clear website copy, product descriptions and blog articles shaped around customer questions, helping people understand your services and supporting your visibility in search.', '/content-writing', 'Learn More &ndash; Content Writing'],
    ];
    ?>
    <section id="services" class="services-section svc-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">OUR EXPERTISE</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Digital Services For Your Business</h2>
            </div>
            <div class="svc-grid">
                <?php foreach ($homeServices as $i => [$icon, $title, $img, $alt, $desc, $href, $aria]): ?>
                <article class="svc-card">
                    <div class="svc-media"><img <?php echo $i < 3 ? 'fetchpriority="low" ' : 'loading="lazy" '; ?>decoding="async" src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>"></div>
                    <div class="svc-shade"></div>
                    <div class="svc-body">
                        <div class="svc-top">
                            <span class="svc-icon"><i class="fa-solid <?php echo $icon; ?>"></i></span>
                            <span class="svc-num" aria-hidden="true"><?php echo sprintf('%02d', $i + 1); ?></span>
                        </div>
                        <h3><?php echo $title; ?></h3>
                        <p><?php echo $desc; ?></p>
                        <a href="<?php echo $href; ?>" class="svc-link" aria-label="<?php echo $aria; ?>">Learn More <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: clamp(30px, 4vw, 40px);">
                <a href="/services" class="btn-secondary">VIEW ALL SERVICES <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i></a>
            </div>
        </div>
    </section>

    <!-- Brand intro: states the business name in both spellings in visible copy. -->
    <section class="agency-section">
        <div class="container">
            <div class="agency-summary">
                <h2 style="color: var(--navy); font-size: clamp(1.6rem, 3.5vw, 2.4rem); font-weight: 800; margin-bottom: 16px; line-height: 1.15;">About W&amp;S Digital Marketing (WS Digital Marketing)</h2>
                <p>W&amp;S Digital Marketing, also searched as WS Digital Marketing, is a Sydney-based digital marketing agency at 3 Weebill St, Melonba NSW 2765. We help Australian businesses win more calls, enquiries and sales through SEO, Google Ads, social media marketing, website design and ecommerce development, on simple month-to-month plans with no lock-in contracts.</p>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/home-sections.php'; ?>

    <!-- PREMIUM FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Grow Your Business?</h2>
                    <p>Partner with W&S Digital Marketing to build a custom, data-driven growth plan that generates high-intent leads, sales, and maximum ROAS.</p>
                    <div class="cta-features">
                        <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                        <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                        <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                    <?php $cta_button_label = 'REQUEST MY FREE GROWTH PLAN'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>