<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/config.php';
try {
    $reviews = get_db()->query('SELECT author_name, author_role, quote, rating, photo_path FROM reviews ORDER BY display_order ASC, id ASC')->fetchAll();
} catch (PDOException $e) {
    // Falls back to an empty grid (with the rest of the page still showing)
    // rather than a fatal error if the database is briefly unreachable.
    $reviews = [];
}

$page_title = 'Client Reviews & Testimonials';
$page_description = 'Read client reviews of W&S Digital Marketing covering websites, SEO, ecommerce and advertising. Explore feedback on our work, communication and support.';
include 'header.php';
?>

    <!-- REVIEWS HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">CLIENT REVIEWS</span>
            <h1 class="fade-up">WHAT OUR CLIENTS <br><span class="text-gradient">ARE SAYING.</span></h1>
            <p class="hero-subtitle fade-up">Real feedback from real Australian businesses — verified reviews from the clients whose growth we've helped drive.</p>
        </div>
    </section>

    <!-- REVIEWS GRID SECTION -->
    <section class="testimonials-section fade-up" style="padding-top: 20px;">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">CLIENT FEEDBACK</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">What Our Clients Say</h2>
            </div>

            <div class="testi-layout">
                <div class="testi-trust-panel fade-up">
                    <div class="testi-trust-rating">4.9<span>/5</span></div>
                    <div class="testi-trust-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <h3>Excellent</h3>
                    <p>Based on 150+ verified Google Reviews from Australian businesses just like yours.</p>

                    <?php $stackPhotos = array_filter(array_column(array_slice($reviews, 0, 4), 'photo_path')); ?>
                    <?php if ($stackPhotos): ?>
                    <div class="testi-avatar-stack">
                        <?php foreach ($stackPhotos as $photo): ?>
                        <div class="stack-avatar"><img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($photo, ENT_QUOTES, 'UTF-8'); ?>" alt="" onerror="this.parentElement.remove()"></div>
                        <?php endforeach; ?>
                        <div class="stack-count">+196</div>
                    </div>
                    <?php endif; ?>

                    <div class="testi-trust-badge"><i class="fa-brands fa-google" style="color: #4285F4;"></i> Verified On Google</div>
                </div>

                <div class="testimonials-grid">
                    <?php foreach ($reviews as $review): ?>
                    <div class="testi-card">
                        <div>
                            <div class="testi-stars">
                                <?php for ($i = 0; $i < (int) $review['rating']; $i++): ?><i class="fa-solid fa-star"></i><?php endfor; ?>
                            </div>
                            <p class="testi-text">"<?php echo htmlspecialchars($review['quote'], ENT_QUOTES, 'UTF-8'); ?>"</p>
                        </div>
                        <div class="testi-author">
                            <div class="testi-avatar-wrap">
                                <?php if ($review['photo_path']): ?>
                                <div class="testi-avatar">
                                    <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($review['photo_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($review['author_name'], ENT_QUOTES, 'UTF-8'); ?>" onerror="this.closest('.testi-avatar-wrap').remove()">
                                </div>
                                <span class="testi-verified"><i class="fa-solid fa-check"></i></span>
                                <?php endif; ?>
                            </div>
                            <div class="testi-author-info">
                                <h3><?php echo htmlspecialchars($review['author_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <?php if ($review['author_role']): ?><span><?php echo htmlspecialchars($review['author_role'], ENT_QUOTES, 'UTF-8'); ?></span><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Become Our Next Success Story?</h2>
                    <p>Get a zero-obligation growth audit and see exactly how we'd approach your account.</p>
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
