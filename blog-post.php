<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/blog-seo.php';

$slug = $_GET['slug'] ?? '';
$post = null;
try {
    $stmt = get_db()->prepare('SELECT * FROM blog_posts WHERE slug = ? AND is_published = 1');
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
} catch (PDOException $e) {
    $post = null;
}

// An old or hand-written link to a post whose slug has since changed (e.g.
// re-saved from the admin with a new title): if exactly one published post
// starts with the same first few words, send visitors there permanently
// instead of showing "Not Found".
if (!$post && $slug !== '') {
    $leadWords = implode('-', array_slice(explode('-', $slug), 0, 4));
    try {
        $stmt = get_db()->prepare('SELECT slug FROM blog_posts WHERE is_published = 1 AND slug LIKE ?');
        $stmt->execute([str_replace(['%', '_'], ['\\%', '\\_'], $leadWords) . '%']);
        $matches = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        $matches = [];
    }
    if (count($matches) === 1 && $matches[0] !== $slug) {
        header('Location: /blog/' . rawurlencode($matches[0]), true, 301);
        exit;
    }
}

// Still no match: an admin redirect for this exact URL wins first, then a
// permanent (301) redirect to the most relevant live page, falling back to
// /blog -- a removed URL should never be left as a 404.
if (!$post && $slug !== '') {
    require_once __DIR__ . '/includes/seo.php';
    seo_try_redirect(seo_normalize_path($_SERVER['REQUEST_URI'] ?? '/'));
    seo_log_404(seo_normalize_path($_SERVER['REQUEST_URI'] ?? '/'));
    seo_smart_redirect('/' . $slug, '/blog');
}

if (!$post) {
    header('HTTP/1.0 404 Not Found');
    $page_title = 'Post Not Found';
    $page_description = 'This blog post could not be found.';
    include 'header.php';
    ?>
    <section class="hero">
        <div class="container hero-content">
            <h1>Post Not Found</h1>
            <p class="hero-subtitle">This article may have been moved or unpublished.</p>
            <div class="hero-buttons">
                <a href="/blog" class="btn-primary">Back To Blog</a>
            </div>
        </div>
    </section>
    <?php
    include 'footer.php';
    exit;
}

// Both fall back to the title/excerpt when the admin panel's dedicated SEO
// fields are left blank, so existing posts keep working exactly as before
// without needing to be edited.
$page_title = ($post['meta_title'] ?? '') !== '' ? $post['meta_title'] : $post['title'];
$page_description = ($post['meta_description'] ?? '') !== '' ? $post['meta_description'] : ($post['excerpt'] ?: $post['title']);
$page_description = blog_trim_description((string) $page_description);

// Social sharing + image SEO: the cover image doubles as the share image, and
// its alt text is whatever admin -> SEO -> Image SEO set (falling back to the title).
$page_og_type = 'article';
$page_og_image = $post['image_path'] ?? '';
$postImageAlt = ($post['image_alt'] ?? '') !== '' ? $post['image_alt'] : $post['title'];

$recentPosts = [];
try {
    try {
        $stmt = get_db()->prepare('SELECT title, slug, category, image_path, image_alt FROM blog_posts WHERE is_published = 1 AND id != ? ORDER BY created_at DESC LIMIT 4');
        $stmt->execute([$post['id']]);
    } catch (PDOException $e) {
        // image_alt column not added yet (SEO migration pending)
        $stmt = get_db()->prepare('SELECT title, slug, category, image_path FROM blog_posts WHERE is_published = 1 AND id != ? ORDER BY created_at DESC LIMIT 4');
        $stmt->execute([$post['id']]);
    }
    $recentPosts = $stmt->fetchAll();
} catch (PDOException $e) {
    $recentPosts = [];
}

// SEO / GEO: BlogPosting + Breadcrumb + FAQ schema (the FAQ is read from the
// post's own "Frequently Asked Questions" section), article meta tags, and a
// body with a proper heading order. Titles drop the site-name suffix so the
// full 50-60 characters work as the headline in search results.
$page_no_suffix = true;
$page_og_image_alt = $postImageAlt;
$postUrl = SEO_SITE_BASE . '/blog/' . $post['slug'];
$postFaqs = blog_extract_faqs((string) $post['content']);
$postBody = blog_prepare_content((string) $post['content']);
$postSchemaImage = seo_abs_url((string) ($post['image_path'] ?: (seo_setting('default_og_image') ?: '/images/logo-ws.webp')));
$extra_head = blog_post_schema_tag($post, $postUrl, $postSchemaImage, $page_description, $postFaqs)
    . blog_article_meta_tags($post);

$postPublished = strtotime((string) ($post['created_at'] ?? '')) ?: null;
$postUpdated = strtotime((string) ($post['updated_at'] ?? '')) ?: null;

include 'header.php';
?>

    <!-- BLOG POST HERO -->
    <section class="hero" style="padding-bottom: clamp(30px, 6vw, 60px);">
        <div class="container hero-content">
            <?php if ($post['category']): ?><span class="eyebrow fade-up"><?php echo htmlspecialchars($post['category'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
            <h1 class="fade-up"><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <?php if ($postPublished): ?>
            <p class="fade-up" style="margin-top: 18px; color: var(--text-muted); font-size: 0.95rem;">By W&amp;S Digital Marketing &middot; Published <time datetime="<?php echo date('c', $postPublished); ?>"><?php echo date('j F Y', $postPublished); ?></time><?php if ($postUpdated && $postUpdated - $postPublished > 86400): ?> &middot; Updated <time datetime="<?php echo date('c', $postUpdated); ?>"><?php echo date('j F Y', $postUpdated); ?></time><?php endif; ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- BLOG POST CONTENT + SIDEBAR -->
    <section class="agency-section fade-up">
        <div class="container blog-post-layout">
            <div class="blog-post-main">
                <?php if ($post['image_path']): ?>
                    <div style="border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-hover); margin-bottom: 40px;">
                        <img fetchpriority="high" decoding="async" src="<?php echo htmlspecialchars($post['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($postImageAlt, ENT_QUOTES, 'UTF-8'); ?>" style="width:100%; height:auto; display:block;">
                    </div>
                <?php endif; ?>
                <div class="blog-post-body">
                    <?php echo $postBody; ?>
                </div>
                <div style="margin-top: 40px;">
                    <a href="/blog" class="service-link"><i class="fa-solid fa-arrow-left"></i> Back To All Articles</a>
                </div>
            </div>

            <?php if ($recentPosts): ?>
            <aside class="blog-post-sidebar">
                <div class="blog-sidebar-card">
                    <h2>More From The Blog</h2>
                    <div class="blog-sidebar-list">
                        <?php foreach ($recentPosts as $recent): ?>
                        <a class="blog-sidebar-item" href="/blog/<?php echo urlencode($recent['slug']); ?>">
                            <?php if ($recent['image_path']): ?>
                            <div class="blog-sidebar-thumb">
                                <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($recent['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars(($recent['image_alt'] ?? '') !== '' ? $recent['image_alt'] : $recent['title'], ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <?php endif; ?>
                            <div class="blog-sidebar-item-text">
                                <?php if ($recent['category']): ?><span class="blog-sidebar-tag"><?php echo htmlspecialchars($recent['category'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                                <h3><?php echo htmlspecialchars($recent['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <a href="/blog" class="btn-secondary blog-sidebar-btn">VIEW ALL ARTICLES <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i></a>
                </div>
            </aside>
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
