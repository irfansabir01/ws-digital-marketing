<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'About W&S Digital Marketing | Sydney Agency Team';
$page_description = 'Meet the Sydney team behind W&S Digital Marketing. See how we plan SEO, ads and websites around your goals. Book a free chat today.';
$page_no_suffix = true; // the title already carries the brand/keyword, so no auto suffix

require_once __DIR__ . '/config.php';
try {
    $teamTeaser = get_db()->query('SELECT * FROM team_members ORDER BY display_order ASC, id ASC LIMIT 4')->fetchAll();
} catch (PDOException $e) {
    $teamTeaser = [];
}

include 'header.php';
?>

    <!-- ABOUT HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">WHO WE ARE</span>
            <h1 class="fade-up">DRIVING DIGITAL SUCCESS <br><span class="text-gradient">WITH INTEGRITY.</span></h1>
            <p class="hero-subtitle fade-up">W&S Digital Marketing was founded on a simple principle: to eliminate marketing guesswork and deliver transparent, measurable, and scalable results for ambitious Australian businesses.</p>
        </div>
    </section>

    <!-- OUR STORY SECTION (Image Left, Text Right) -->
    <section class="agency-section fade-up">
        <div class="container">
            <div class="agency-grid">
                <div class="agency-image-wrapper agency-image-natural" style="min-height: 400px; background: linear-gradient(160deg, rgba(0, 242, 254, 0.1), rgba(255, 0, 127, 0.08));">
                    <img loading="lazy" decoding="async" src="/images/about-ws.webp" alt="W&S Digital Marketing" onerror="this.remove()">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">OUR STORY</span>
                    <h2>A Passion For Real ROI</h2>
                    <p>In an industry filled with vanity metrics and empty promises, we stand apart. We started W&S Digital Marketing because we saw too many businesses wasting their budgets on campaigns that didn't move the needle.</p>
                    <p>Our team consists of data analysts, creative strategists, and conversion experts who obsess over your numbers. We don't just want to bring you traffic; we are engineered to bring you paying clients.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-arrow-trend-up"></i> Focus on Revenue, Not Just Clicks</li>
                        <li><i class="fa-solid fa-users-gear"></i> Elite Team of Specialized Experts</li>
                        <li><i class="fa-solid fa-handshake"></i> Long-term Strategic Partnerships</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CORE VALUES SECTION (Reusing Services Grid for a clean look) -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">CORE VALUES</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">What Drives Us Forward</h2>
            </div>
            
            <div class="services-grid">
                <!-- Value 1 -->
                <div class="service-card" style="padding: 40px 30px; display: block; text-align: center;">
                    <div style="width: 70px; height: 70px; background: rgba(0, 242, 254, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fa-solid fa-bullseye" style="font-size: 2rem; color: var(--cyan-neon);"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; color: var(--navy); margin-bottom: 15px; font-weight: 800;">Radical Transparency</h3>
                    <p style="color: var(--text-muted); line-height: 1.6; font-size: 0.95rem;">No hidden fees, no confusing jargon. You always know exactly where your budget goes, what we are testing, and what financial returns it generates.</p>
                </div>
                
                <!-- Value 2 -->
                <div class="service-card" style="padding: 40px 30px; display: block; text-align: center;">
                    <div style="width: 70px; height: 70px; background: rgba(0, 242, 254, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fa-solid fa-chart-pie" style="font-size: 2rem; color: var(--cyan-neon);"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; color: var(--navy); margin-bottom: 15px; font-weight: 800;">Data-Driven Decisions</h3>
                    <p style="color: var(--text-muted); line-height: 1.6; font-size: 0.95rem;">We don't rely on gut feelings. Every campaign launch, landing page tweak, and scaling strategy is backed by hard data and rigorous testing.</p>
                </div>
                
                <!-- Value 3 -->
                <div class="service-card" style="padding: 40px 30px; display: block; text-align: center;">
                    <div style="width: 70px; height: 70px; background: rgba(0, 242, 254, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                        <i class="fa-solid fa-bolt" style="font-size: 2rem; color: var(--cyan-neon);"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; color: var(--navy); margin-bottom: 15px; font-weight: 800;">Relentless Execution</h3>
                    <p style="color: var(--text-muted); line-height: 1.6; font-size: 0.95rem;">Digital marketing moves fast, and so do we. We proactively optimize your campaigns daily to ensure you stay ahead of the competition.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- MEET THE TEAM (teaser — full roster lives on our-team.php) -->
    <section id="team" class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">MEET THE TEAM</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">The People Behind Your Growth</h2>
            </div>

            <?php if ($teamTeaser): ?>
            <div class="team-grid">
                <?php foreach ($teamTeaser as $i => $member): ?>
                <div class="team-card">
                    <div class="team-photo">
                        <div class="team-photo-placeholder"><i class="fa-solid fa-user"></i></div>
                        <?php if ($member['photo_path']): ?>
                        <img loading="lazy" decoding="async" src="<?php echo htmlspecialchars($member['photo_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($member['name'] . ', ' . $member['role'], ENT_QUOTES, 'UTF-8'); ?>" onerror="this.remove()">
                        <?php endif; ?>
                        <span class="team-card-num"><?php echo str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT); ?></span>
                        <div class="team-card-overlay"></div>
                        <div class="team-card-info">
                            <h5><?php echo htmlspecialchars($member['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            <span class="team-role"><?php echo htmlspecialchars($member['role'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php if ($member['description']): ?><p class="team-card-desc"><?php echo htmlspecialchars($member['description'], ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div style="text-align: center; margin-top: clamp(30px, 4vw, 40px);">
                <a href="/our-team" class="btn-secondary">MEET THE FULL TEAM <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i></a>
            </div>
        </div>
    </section>

    <!-- FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Grow Your Business?</h2>
                    <p>Partner with W&S Digital Marketing to build a custom, data-driven growth plan that generates high-intent leads, consistent sales, and maximum ROAS.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                    <?php $cta_button_label = 'SPEAK WITH OUR TEAM'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>