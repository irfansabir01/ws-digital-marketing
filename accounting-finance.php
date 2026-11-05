<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
$page_title = 'Marketing for Accountants & Finance';
$page_description = 'Help clients find your accounting, bookkeeping or financial advice firm with local search, clear website content and straightforward enquiry options.';
include 'header.php';
?>

    <!-- ACCOUNTING & FINANCE HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">ACCOUNTING &amp; FINANCE</span>
            <h1 class="fade-up">HELP CLIENTS UNDERSTAND <br><span class="text-gradient">YOUR EXPERTISE.</span></h1>
            <p class="hero-subtitle fade-up">Digital marketing for accountants, bookkeepers and financial advisers &mdash; helping prospective clients find your firm, understand your services and take the next step.</p>
            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary">GET MY FREE MARKETING AUDIT <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="/industries" class="btn-secondary">VIEW ALL INDUSTRIES</a>
            </div>
        </div>
    </section>

    <!-- INTRO SECTION -->
    <section class="agency-section fade-up">
        <div class="container">
            <div class="agency-grid">
                <div class="agency-image-wrapper">
                    <img loading="lazy" decoding="async" src="/images/industries/finance.webp" alt="Accounting & Finance Digital Marketing" onerror="this.remove()">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Give Clients Confidence To Get In Touch</h2>
                    <p>Choosing an accounting or financial services firm starts with understanding its expertise. We help you present your services, team and approach clearly, with useful content and local search visibility that make it easier for prospective clients to assess whether your firm suits their needs.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Local Search Visibility For Your Services</li>
                        <li><i class="fa-solid fa-check"></i> Clear Website Copy &amp; Educational Content</li>
                        <li><i class="fa-solid fa-check"></i> Professional Websites With Simple Enquiry Options</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="margin-top: 10px;">Speak With Our Team</a>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW WE CAN HELP -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">HOW WE CAN HELP</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Marketing Built Around Your Firm</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-magnifying-glass-dollar"></i></div>
                    <h3>Local &amp; Specialist Search</h3>
                    <p>Help prospective clients find your accounting, bookkeeping or advisory services through relevant service pages and local search optimisation.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                    <h3>Service &amp; Enquiry Pages</h3>
                    <p>Explain who you help, what your services cover and how prospective clients can contact the right person.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <h3>Professional Website Design</h3>
                    <p>Present your team, qualifications and service information in a clear, accessible layout, with content reviewed by your firm before publication.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-comments-dollar"></i></div>
                    <h3>Email &amp; Educational Content</h3>
                    <p>Develop newsletters and useful guides, with content creation, campaign setup and ongoing management agreed around your requirements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready To Fill Your Client Pipeline?</h2>
                    <p>Get a free audit of your firm's digital presence and a plan to attract more of the clients you actually want.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                    <?php $cta_button_label = 'GET MY FREE MARKETING AUDIT'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
