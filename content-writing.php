<?php
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/includes/seo.php';
$page_title = 'Content Writing Services Australia';
$page_description = 'Website copy, blog articles and email content written around your audience, brand voice and relevant searches. Request a free content review today.';
$page_og_image = '/images/photos/content-writing.webp';

// FAQ copy lives here once and feeds both the visible accordion below and
// the FAQPage structured data, so the two can never drift apart.
$contentFaqs = [
    [
        'q' => 'What types of content do you write?',
        'a' => 'We write website and service page copy, landing pages, blog articles and customer guides, plus email and newsletter content. We can also review existing pages and plan new topics.',
    ],
    [
        'q' => 'Is your content written for SEO?',
        'a' => 'Yes. Each piece is planned around your audience and the searches relevant to your services, with clear headings and structure. Content works best alongside technical and local optimisation, which our <a href="/seo">SEO services</a> cover.',
    ],
    [
        'q' => 'Will the content match our brand voice?',
        'a' => 'We start with a brief covering your audience, tone and key messages, then write in that voice. You review every draft and confirm business-specific facts before anything is finalised.',
    ],
    [
        'q' => 'Do you upload the content to our website?',
        'a' => 'We supply approved copy with any agreed publishing guidance. Uploading, formatting and ongoing updates can be added to the scope, or handled as part of a <a href="/website-development">website build</a>.',
    ],
    [
        'q' => 'How long does it take to receive content?',
        'a' => 'Timing depends on the number of pages or articles, the research involved and feedback rounds. We agree delivery dates during the brief so you know what to expect before writing starts.',
    ],
];

$extra_head = seo_graph_tag([
    [
        '@type' => 'Service',
        '@id' => 'https://wsdigitalmarketing.com.au/content-writing#service',
        'name' => 'Content Writing Services',
        'serviceType' => 'Content writing and copywriting',
        'description' => 'Website copy, landing pages, blog articles, customer guides, email and newsletter content, and content planning for Australian businesses.',
        'url' => 'https://wsdigitalmarketing.com.au/content-writing',
        'image' => 'https://wsdigitalmarketing.com.au/images/photos/content-writing.webp',
        'areaServed' => ['@type' => 'Country', 'name' => 'Australia'],
        'provider' => [
            '@type' => 'Organization',
            '@id' => 'https://wsdigitalmarketing.com.au/#organization',
            'name' => 'W&S Digital Marketing',
            'url' => 'https://wsdigitalmarketing.com.au/',
        ],
    ],
    seo_breadcrumb_schema([['Home', '/'], ['Services', '/services'], ['Content Writing', '/content-writing']]),
    seo_faq_schema($contentFaqs),
]);
include 'header.php';
?>

    <!-- CONTENT WRITING HERO SECTION -->
    <section class="hero" style="padding-bottom: clamp(40px, 8vw, 80px);">
        <div class="container hero-content">
            <span class="eyebrow fade-up">CONTENT WRITING</span>
            <h1 class="fade-up">MAKE YOUR MESSAGE CLEAR <br><span class="text-gradient">TO YOUR CUSTOMERS.</span></h1>
            <p class="hero-subtitle fade-up">Explain what you offer with useful website copy, blog articles and email content written around your audience, brand voice and the questions customers ask.</p>
            <div class="hero-buttons fade-up">
                <a href="#contact" class="btn-primary">GET MY FREE CONTENT AUDIT <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                <a href="/services" class="btn-secondary">VIEW ALL SERVICES</a>
            </div>
        </div>
    </section>

    <!-- INTRO SECTION -->
    <section class="agency-section fade-up">
        <div class="container">
            <div class="agency-grid">
                <div class="agency-image-wrapper">
                    <img loading="lazy" decoding="async" src="/images/photos/content-writing.webp" alt="Content writer typing a blog article on a laptop at a home office desk with a notebook of headline ideas">
                </div>
                <div class="agency-text">
                    <span class="eyebrow">WHY IT MATTERS</span>
                    <h2>Help Customers Understand Your Business</h2>
                    <p>Good content gives people the information they need to make a decision. We research your services, audience and relevant searches, then turn that information into clear copy. Each piece has a defined purpose, whether it explains a service, answers a question or introduces an offer.</p>
                    <p>Our writing supports the rest of your marketing too, from <a href="/seo" style="color: var(--navy); font-weight: 700; text-decoration: underline;">search engine optimisation</a> to <a href="/website-development" style="color: var(--navy); font-weight: 700; text-decoration: underline;">new website builds</a> and <a href="/social-media" style="color: var(--navy); font-weight: 700; text-decoration: underline;">social media campaigns</a>.</p>
                    <ul class="agency-list" style="margin-top: 20px;">
                        <li><i class="fa-solid fa-check"></i> Website &amp; Service Page Copy</li>
                        <li><i class="fa-solid fa-check"></i> Blog Articles &amp; Customer Guides</li>
                        <li><i class="fa-solid fa-check"></i> Email &amp; Newsletter Content</li>
                    </ul>
                    <a href="#contact" class="btn-primary" style="margin-top: 10px;">Speak With A Content Strategist</a>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW WE CAN HELP -->
    <section class="services-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">HOW WE CAN HELP</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Content For The Ways Customers Find You</h2>
            </div>
            <div class="industry-tile-grid">
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-file-lines"></i></div>
                    <h3>Website &amp; Landing Page Copy</h3>
                    <p>Explain your services, address common questions and guide visitors towards a relevant next step using clear, structured copy.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-pen-nib"></i></div>
                    <h3>Blog Articles &amp; Guides</h3>
                    <p>Develop useful articles around your audience's interests, relevant searches and the topics connected to your business.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
                    <h3>Email &amp; Newsletter Writing</h3>
                    <p>Write email messages and sequences with a clear purpose, consistent tone and an appropriate call to action.</p>
                </div>
                <div class="industry-tile">
                    <div class="industry-tile-icon"><i class="fa-solid fa-calendar-check"></i></div>
                    <h3>Content Planning &amp; Reviews</h3>
                    <p>Identify content gaps, plan useful topics and review existing pages for clarity, consistency and opportunities to improve.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PROCESS -->
    <section class="methodology-section fade-up">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">OUR PROCESS</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">How We Develop Your Content</h2>
            </div>
            <div class="methodology-steps">
                <div class="m-step">
                    <div class="m-step-num">01</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-magnifying-glass"></i> Research &amp; Brief</h3>
                        <p>We establish the audience, purpose and key information, then agree on the format, scope and delivery requirements.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">02</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-pen"></i> Write &amp; Structure</h3>
                        <p>We draft content in your brand voice, using clear headings and a logical flow suited to the channel.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">03</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-magnifying-glass-plus"></i> Review &amp; Refine</h3>
                        <p>We review clarity and supporting information, then incorporate your feedback and confirmation of business-specific facts.</p>
                    </div>
                </div>
                <div class="m-step">
                    <div class="m-step-num">04</div>
                    <div class="m-step-content">
                        <h3><i class="fa-solid fa-chart-line"></i> Deliver &amp; Support</h3>
                        <p>We supply the approved copy and any agreed publishing guidance. Uploads, distribution and ongoing reviews are scoped separately.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    $relatedServiceSlug = 'content-writing';
    $relatedHeading = 'Explore Our Content Work';
    include __DIR__ . '/includes/related-case-studies-section.php';
    ?>

    <!-- FAQ -->
    <section class="services-section fade-up">
        <div class="container" style="max-width: 900px;">
            <div class="section-header">
                <span class="eyebrow">FAQ</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Content Writing Questions</h2>
            </div>
            <div class="faq-list" style="margin-top: clamp(30px, 5vw, 50px);">
<?php foreach ($contentFaqs as $i => $f): ?>
                <details class="faq-item"<?php echo $i === 0 ? ' open' : ''; ?>>
                    <summary><h3><?php echo htmlspecialchars($f['q'], ENT_QUOTES, 'UTF-8'); ?></h3></summary>
                    <p><?php echo $f['a']; ?></p>
                </details>
<?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="cta-section" id="contact">
        <div class="container">
            <div class="cta-box cta-box-split fade-up">
                <div class="cta-info">
                    <div class="cta-badge"><i class="fa-solid fa-rocket"></i> Let's Scale Together</div>
                    <h2>Ready For Content That Works?</h2>
                    <p>Get a free review of your current content and a plan to make it rank and convert.</p>
                    <div class="cta-features">
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Zero Obligation Audit</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Custom Growth Strategy</span>
                    <span><i class="fa-solid fa-check" style="color: var(--cyan-neon);"></i> Proven Australian Results</span>
                    </div>
                </div>
                <div class="cta-form-wrap">
                <?php $cta_default_service = 'Content Writing'; ?>
                    <?php $cta_button_label = 'GET MY FREE CONTENT AUDIT'; include __DIR__ . '/includes/cta-form.php'; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'footer.php'; ?>
