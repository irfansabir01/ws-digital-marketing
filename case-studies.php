<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/config.php';
try {
    $caseStudies = get_db()->query('SELECT slug, name, tag, image_path, image_alt, summary FROM case_studies ORDER BY display_order ASC, name ASC')->fetchAll();
} catch (PDOException $e) {
    // Falls back to an empty grid (with the rest of the page still showing)
    // rather than a fatal error if the database is briefly unreachable.
    $caseStudies = [];
}

$page_title = 'Case Studies | Real Website & SEO Results | W&S';
$page_description = 'See real websites, Shopify stores and SEO projects we\'ve delivered for Australian businesses. Browse the results, then start yours today.';
$page_no_suffix = true; // the title already carries the brand/keyword, so no auto suffix
include 'header.php';
?>

    <!-- CASE STUDIES HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">CASE STUDIES</span>
            <h1 class="fade-up">DISCOVER HOW OUR DIGITAL MARKETING SOLUTIONS <br><span class="text-gradient">HAVE HELPED BUSINESSES GROW.</span></h1>
            <p class="hero-subtitle fade-up">Discover how our digital marketing solutions have helped businesses increase traffic, generate leads, and achieve measurable online growth.</p>
        </div>
    </section>

    <!-- CASE STUDIES FILTER & GRID SECTION -->
    <section class="case-studies-section fade-up" style="padding-top: 20px; padding-bottom: 100px;">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">OUR PORTFOLIO</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px; color: var(--navy);">Explore All Client Success Stories</h2>
            </div>
            
            <div class="case-grid">
                <?php foreach ($caseStudies as $cs): ?>
                <div class="case-card">
                    <div class="case-image">
                        <span class="case-tag"><?php echo htmlspecialchars($cs['tag'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($cs['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($cs['image_alt'], ENT_QUOTES, 'UTF-8'); ?>" onerror="this.remove()">
                        <h3><?php echo htmlspecialchars(strtoupper($cs['name']), ENT_QUOTES, 'UTF-8'); ?></h3>
                    </div>
                    <div class="case-content">
                        <div>
                            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 15px;"><?php echo htmlspecialchars(strip_tags($cs['summary']), ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div>
                            <a href="/case-studies/<?php echo htmlspecialchars($cs['slug'], ENT_QUOTES, 'UTF-8'); ?>" class="service-link" aria-label="View Details &ndash; <?php echo htmlspecialchars($cs['name'], ENT_QUOTES, 'UTF-8'); ?>">View Details <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- STATISTICS PROOF STRIP -->
    <section class="stats-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow" style="color: var(--cyan-neon);">THE IMPACT</span>
                <h2 style="color: #fff; font-size: clamp(1.8rem, 4vw, 2.8rem);">Numbers That Speak For Themselves</h2>
            </div>
            <div class="stats-4-grid">
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-arrow-trend-up"></i></div>
                    <h3>+142%</h3>
                    <p>Organic Traffic Growth</p>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <h3>8.4x</h3>
                    <p>ROAS Achieved</p>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-trophy"></i></div>
                    <h3>15+</h3>
                    <p>Case Studies Delivered</p>
                </div>
                <div class="stat-box">
                    <div class="stat-icon"><i class="fa-solid fa-handshake"></i></div>
                    <h3>98%</h3>
                    <p>Client Retention</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PREMIUM FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Write Your Own Success Story?</h2>
                    <p>Partner with W&S Digital Marketing to build a custom, data-driven growth plan that generates high-intent leads, consistent sales, and maximum ROAS.</p>
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