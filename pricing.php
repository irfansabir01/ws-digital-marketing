<?php
require_once __DIR__ . '/includes/cta-form-handler.php';

// The 3 plan buttons below link to this same page's CTA form, carrying which
// plan the visitor clicked (?plan=Growth#contact) so the form can show and
// pre-fill it -- whitelisted since it's reflected back into the page.
$valid_plans = ['Starter', 'Growth', 'Scale'];
$selected_plan = $_POST['plan'] ?? $_GET['plan'] ?? '';
$selected_plan = in_array($selected_plan, $valid_plans, true) ? $selected_plan : '';

$page_title = 'Digital Marketing Pricing | Clear Plans & Quotes';
$page_description = 'Compare clear Starter, Growth and Scale marketing plans. See exactly what\'s included and get a tailored quote. No hidden surprises, ever.';
$page_no_suffix = true; // the title already carries the brand/keyword, so no auto suffix
include 'header.php';
?>

    <!-- PRICING HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">PRICING</span>
            <h1 class="fade-up">PLANS BUILT AROUND <br><span class="text-gradient">YOUR GROWTH STAGE.</span></h1>
            <p class="hero-subtitle fade-up">Every engagement is scoped around your goals and budget — no confusing tiers, no hidden fees. Tell us where you're at and we'll recommend the right plan.</p>
        </div>
    </section>

    <!-- PRICING GRID -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="pricing-grid">

                <div class="pricing-card">
                    <span class="eyebrow">STARTER</span>
                    <p class="pricing-desc" style="margin-top: 15px;">For small businesses ready to establish a real online presence.</p>
                    <ul class="pricing-features">
                        <li><i class="fa-solid fa-check"></i> 1 Core Channel (SEO or PPC)</li>
                        <li><i class="fa-solid fa-check"></i> Monthly Performance Reporting</li>
                        <li><i class="fa-solid fa-check"></i> Local Search Optimization</li>
                        <li><i class="fa-solid fa-check"></i> Email Support</li>
                    </ul>
                    <a href="?plan=Starter#contact" class="btn-secondary" style="width: 100%;">Get The Starter Plan</a>
                </div>

                <div class="pricing-card featured">
                    <span class="pricing-badge">MOST POPULAR</span>
                    <span class="eyebrow" style="color: var(--cyan-neon);">GROWTH</span>
                    <p class="pricing-desc" style="margin-top: 15px;">For businesses ready to scale with multiple channels working together.</p>
                    <ul class="pricing-features">
                        <li><i class="fa-solid fa-check"></i> SEO + PPC + Social Combined</li>
                        <li><i class="fa-solid fa-check"></i> Bi-Weekly Strategy Calls</li>
                        <li><i class="fa-solid fa-check"></i> Landing Page & CRO Support</li>
                        <li><i class="fa-solid fa-check"></i> Priority Support</li>
                    </ul>
                    <a href="?plan=Growth#contact" class="btn-primary" style="width: 100%;">Get The Growth Plan</a>
                </div>

                <div class="pricing-card">
                    <span class="eyebrow">SCALE</span>
                    <p class="pricing-desc" style="margin-top: 15px;">For established brands needing a full-funnel growth engine.</p>
                    <ul class="pricing-features">
                        <li><i class="fa-solid fa-check"></i> Full-Funnel Multi-Channel Strategy</li>
                        <li><i class="fa-solid fa-check"></i> Dedicated Account Team</li>
                        <li><i class="fa-solid fa-check"></i> Website & Ecommerce Development</li>
                        <li><i class="fa-solid fa-check"></i> Weekly Reporting & Calls</li>
                    </ul>
                    <a href="?plan=Scale#contact" class="btn-secondary" style="width: 100%;">Request The Scale Plan</a>
                </div>

            </div>
            <p style="text-align: center; color: var(--text-muted); margin-top: 40px; font-size: 0.95rem;">All plans exclude ad spend and are billed monthly with no lock-in contracts. Get in touch for a tailored quote based on your goals.</p>
        </div>
    </section>

    <!-- FINAL CTA SECTION -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Need Something More Tailored?</h2>
                    <p>Every business is different. Book a free audit and we'll build a plan and quote around your actual goals.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                    <?php
                    $cta_button_label = 'GET MY FREE GROWTH PLAN';
                    $cta_plan = $selected_plan;
                    $cta_prefill_message = $selected_plan !== '' ? "I'm interested in the {$selected_plan} plan and would like to learn more." : '';
                    include __DIR__ . '/includes/cta-form.php';
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Scoped Pricing Card Styles -->
    <style>
        .pricing-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr)); gap: 30px; align-items: stretch; }
        .pricing-card { background: #fff; border: 1px solid var(--border-light); border-radius: 20px; padding: clamp(30px, 4vw, 40px); box-shadow: var(--shadow-sm); transition: var(--transition-smooth); display: flex; flex-direction: column; position: relative; }
        .pricing-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-hover); border-color: var(--cyan-neon); }
        .pricing-card.featured { background: var(--navy); border-color: var(--navy); color: #fff; transform: scale(1.03); }
        .pricing-card.featured:hover { transform: scale(1.03) translateY(-8px); }
        .pricing-badge { position: absolute; top: -14px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, var(--cyan-neon), var(--magenta-neon)); color: #fff; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; padding: 6px 16px; border-radius: 30px; }
        .pricing-desc { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 25px; line-height: 1.5; }
        .pricing-card.featured .pricing-desc { color: rgba(255,255,255,0.7); }
        .pricing-features { list-style: none; margin: 0 0 30px; padding: 0; flex: 1; }
        .pricing-features li { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; font-size: 0.9rem; font-weight: 600; }
        .pricing-features li i { color: var(--cyan-neon); }
    </style>

<?php include 'footer.php'; ?>
