<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Meet the W&S Digital Marketing Team in Sydney';
$page_description = 'Meet the SEO, ads, web design and content specialists behind your results at W&S Digital Marketing. Get to know us, then say hello today.';
$page_no_suffix = true; // the title already carries the brand/keyword, so no auto suffix

require_once __DIR__ . '/config.php';
try {
    $team = get_db()->query('SELECT * FROM team_members ORDER BY display_order ASC, id ASC')->fetchAll();
} catch (PDOException $e) {
    $team = [];
}

include 'header.php';
?>

    <!-- OUR TEAM HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">OUR TEAM</span>
            <h1 class="fade-up">THE PEOPLE BEHIND <br><span class="text-gradient">YOUR GROWTH.</span></h1>
            <p class="hero-subtitle fade-up">A small, senior team of strategists, developers, and creatives who obsess over your numbers as much as you do.</p>
        </div>
    </section>

    <!-- FULL TEAM ROSTER -->
    <section class="services-section fade-up" style="padding-top: 20px;">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">MEET EVERYONE</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">A Senior Team, Not A Call Centre</h2>
            </div>

            <?php if ($team): ?>
            <div class="team-grid">
                <?php foreach ($team as $i => $member): ?>
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
            <?php else: ?>
            <p style="text-align:center; color: var(--text-muted);">Team roster coming soon.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Work With Us?</h2>
                    <p>Get a zero-obligation growth audit from the team that will actually be running your campaigns.</p>
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
