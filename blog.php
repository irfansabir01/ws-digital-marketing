<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/includes/blog-seo.php';
$page_title = 'Digital Marketing Blog: SEO, Google Ads & Website Tips';
$page_no_suffix = true;
$page_description = 'Practical SEO, Google Ads, local search and ecommerce guides for Australian businesses, written by the W&S Digital Marketing team. Read our latest articles.';

require_once __DIR__ . '/config.php';
try {
    try {
        $posts = get_db()->query('SELECT id, title, slug, category, excerpt, image_path, image_alt, created_at, updated_at FROM blog_posts WHERE is_published = 1 ORDER BY created_at DESC')->fetchAll();
    } catch (PDOException $e) {
        // image_alt column not added yet (SEO migration pending)
        $posts = get_db()->query('SELECT id, title, slug, category, excerpt, image_path, created_at, updated_at FROM blog_posts WHERE is_published = 1 ORDER BY created_at DESC')->fetchAll();
    }
} catch (PDOException $e) {
    // Falls back to an empty grid (with the CTA still showing) rather than a
    // fatal error if config.php hasn't been filled in with real DB credentials
    // yet, or the database is briefly unreachable.
    $posts = [];
}

// Blog + ItemList schema so search and AI engines can see every article and its dates.
$blogItems = [];
foreach ($posts as $i => $bp) {
    $blogItems[] = [
        '@type' => 'BlogPosting',
        '@id' => SEO_SITE_BASE . '/blog/' . $bp['slug'] . '#article',
        'headline' => html_entity_decode((string) $bp['title'], ENT_QUOTES, 'UTF-8'),
        'url' => SEO_SITE_BASE . '/blog/' . $bp['slug'],
        'datePublished' => blog_iso_date($bp['created_at'] ?? null),
        'dateModified' => blog_iso_date($bp['updated_at'] ?? ($bp['created_at'] ?? null)),
    ];
}
$extra_head = seo_graph_tag([[
    '@type' => 'Blog',
    '@id' => SEO_SITE_BASE . '/blog#blog',
    'name' => 'W&S Digital Marketing Blog',
    'url' => SEO_SITE_BASE . '/blog',
    'inLanguage' => 'en-AU',
    'publisher' => ['@type' => 'Organization', '@id' => SEO_SITE_BASE . '/#organization', 'name' => 'W&S Digital Marketing'],
    'blogPost' => $blogItems,
]]);

include 'header.php';
?>

    <!-- BLOG HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">INSIGHTS</span>
            <h1 class="fade-up">GROWTH STRATEGIES, <br><span class="text-gradient">STRAIGHT FROM THE TEAM.</span></h1>
            <p class="hero-subtitle fade-up">Practical SEO, PPC and growth marketing insights to help you make smarter decisions about your online presence.</p>
        </div>
    </section>

    <!-- BLOG GRID SECTION -->
    <section class="case-studies-section fade-up">
        <div class="container">
            <?php if ($posts): ?>
            <div class="case-grid">
                <?php foreach ($posts as $post): ?>
                <article class="case-card">
                    <div class="case-image">
                        <?php if ($post['category']): ?><span class="case-tag"><?php echo htmlspecialchars($post['category'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                        <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($post['image_path'] !== '' ? $post['image_path'] : blog_fallback_image((string) $post['category']), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars(($post['image_alt'] ?? '') !== '' ? $post['image_alt'] : $post['title'], ENT_QUOTES, 'UTF-8'); ?>">
                        <h2><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    </div>
                    <div class="case-content">
                        <div>
                            <p><?php echo htmlspecialchars($post['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div>
                            <a href="/blog/<?php echo urlencode($post['slug']); ?>" class="service-link" aria-label="Read More &ndash; <?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>">Read More <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p style="text-align:center; color: var(--text-muted);">No posts published yet -- check back soon.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Put This Into Practice?</h2>
                    <p>Skip the guesswork. Let our team build and run a custom growth strategy for your business.</p>
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
