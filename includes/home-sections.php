<?php
// Homepage sections that follow the services grid (included by index.php):
// Why Choose, Author Bio, Google Reviews, Latest Blog, FAQs.
// Copy lives in includes/home-content.php; styles are in css/style.css
// (search "HOMEPAGE SECTIONS"), behaviour in js/main.js.

require_once __DIR__ . '/home-content.php';
require_once __DIR__ . '/google-reviews.php';

$hs = static fn($v): string => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
$hsJson = static fn($v): string => json_encode($v, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
$hsPath = static fn(string $p): string => ($p === '' || preg_match('#^(https?:)?//|^/#i', $p)) ? $p : '/' . $p;

// ------------------------------------------------------------ author photo
if (!empty($authorBio['enabled']) && $authorBio['photo'] === '' && $authorBio['team_member'] !== '') {
    try {
        $st = get_db()->prepare('SELECT photo_path FROM team_members WHERE name = ? LIMIT 1');
        $st->execute([$authorBio['team_member']]);
        $authorBio['photo'] = (string) ($st->fetchColumn() ?: '');
    } catch (Throwable $e) {
        // photo stays empty; the card falls back to initials
    }
}

// ------------------------------------------------------------ reviews
$google = null;
try {
    $google = ws_google_reviews();
} catch (Throwable $e) {
    $google = null;
}
$reviewCards = [];
$reviewSource = 'site';
if ($google && $google['reviews']) {
    $reviewSource = 'google';
    foreach ($google['reviews'] as $r) {
        $reviewCards[] = ['name' => $r['name'], 'sub' => $r['time'], 'text' => $r['text'], 'rating' => $r['rating'], 'photo' => $r['photo'], 'url' => $r['url']];
    }
} else {
    try {
        $rows = get_db()->query('SELECT author_name, author_role, quote, rating, photo_path FROM reviews ORDER BY display_order ASC, id ASC')->fetchAll();
        foreach ($rows as $r) {
            $reviewCards[] = ['name' => $r['author_name'], 'sub' => $r['author_role'], 'text' => $r['quote'], 'rating' => (int) $r['rating'], 'photo' => $r['photo_path'], 'url' => ''];
        }
    } catch (Throwable $e) {
        $reviewCards = [];
    }
}
$reviewCards = array_slice($reviewCards, 0, (int) $reviewsSection['max_cards']);
$googleLink = ($google['maps_url'] ?? '') !== '' ? $google['maps_url'] : $reviewsSection['google_url'];

// ------------------------------------------------------------ latest posts
require_once __DIR__ . '/blog-seo.php';
$latestPosts = [];
try {
    try {
        $latestPosts = get_db()->query('SELECT title, slug, category, excerpt, image_path, image_alt, created_at FROM blog_posts WHERE is_published = 1 ORDER BY created_at DESC LIMIT 3')->fetchAll();
    } catch (PDOException $e) {
        $latestPosts = get_db()->query('SELECT title, slug, category, excerpt, image_path, created_at FROM blog_posts WHERE is_published = 1 ORDER BY created_at DESC LIMIT 3')->fetchAll();
    }
} catch (Throwable $e) {
    $latestPosts = [];
}
?>

<?php if (!empty($whyChoose['enabled'])): ?>
    <!-- WHY CHOOSE -->
    <section class="why-section fade-up" id="why-choose">
        <div class="container">
            <div class="why-top">
                <div class="why-media">
                    <img loading="lazy" decoding="async" width="<?php echo (int) $whyChoose['image_w']; ?>" height="<?php echo (int) $whyChoose['image_h']; ?>" src="<?php echo $hs($whyChoose['image']); ?>" alt="<?php echo $hs($whyChoose['image_alt']); ?>">
                </div>
                <div class="why-copy">
                    <span class="eyebrow"><?php echo $hs($whyChoose['eyebrow']); ?></span>
                    <h2><?php echo $hs($whyChoose['heading']); ?></h2>
                    <p class="why-intro"><?php echo $hs($whyChoose['intro']); ?></p>
                    <div class="why-cta">
                        <a href="<?php echo $hs($whyChoose['cta_primary'][1]); ?>" class="btn-primary"><?php echo $hs(mb_strtoupper($whyChoose['cta_primary'][0])); ?> <i class="fa-solid fa-arrow-right" style="margin-left: 5px;"></i></a>
                        <a href="<?php echo $hs($whyChoose['cta_secondary'][1]); ?>" class="btn-secondary"><?php echo $hs(mb_strtoupper($whyChoose['cta_secondary'][0])); ?></a>
                    </div>
                </div>
            </div>

            <ul class="why-cards">
                <?php foreach ($whyChoose['points'] as [$wi, $wt, $wd, $wl]): ?>
                <li class="why-card">
                    <span class="why-icon"><i class="fa-solid <?php echo $hs($wi); ?>"></i></span>
                    <h3><?php echo $wl ? '<a href="' . $hs($wl) . '">' . $hs($wt) . '</a>' : $hs($wt); ?></h3>
                    <p><?php echo $hs($wd); ?></p>
                </li>
                <?php endforeach; ?>
            </ul>

            <?php if ($whyChoose['results']): ?>
            <div class="why-results">
                <ul>
                    <?php foreach ($whyChoose['results'] as [$rv, $rl]): ?>
                    <li><strong><?php echo $hs($rv); ?></strong><span><?php echo $hs($rl); ?></span></li>
                    <?php endforeach; ?>
                </ul>
                <p><?php echo $hs($whyChoose['results_note']); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<?php if (!empty($authorBio['enabled']) && $authorBio['name'] !== ''):
    $ab = $authorBio;
    $abPhoto = $hsPath((string) $ab['photo']);
    $abInitials = '';
    foreach (preg_split('/\s+/', trim($ab['name'])) as $part) {
        $abInitials .= mb_strtoupper(mb_substr($part, 0, 1));
        if (mb_strlen($abInitials) >= 2) {
            break;
        }
    }
    $abIcons = ['linkedin' => ['fa-brands fa-linkedin-in', 'LinkedIn'], 'facebook' => ['fa-brands fa-facebook-f', 'Facebook'], 'instagram' => ['fa-brands fa-instagram', 'Instagram'], 'x' => ['fa-brands fa-x-twitter', 'X'], 'email' => ['fa-solid fa-envelope', 'Email']];
    $abSameAs = [];
    foreach ($ab['links'] as $k => $u) {
        if ($k !== 'email' && preg_match('#^https://#i', (string) $u)) {
            $abSameAs[] = $u;
        }
    }
    $abSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => $ab['name'],
        'jobTitle' => $ab['role'],
        'image' => $abPhoto !== '' ? (preg_match('#^https?:#i', $abPhoto) ? $abPhoto : 'https://wsdigitalmarketing.com.au' . $abPhoto) : null,
        'worksFor' => ['@id' => 'https://wsdigitalmarketing.com.au/#organization'],
        'knowsAbout' => $ab['specialties'] ?: null,
        'sameAs' => $abSameAs ?: null,
    ]);
?>
    <!-- AUTHOR BIO -->
    <section class="author-section fade-up" id="meet-the-ceo">
        <div class="container">
            <div class="author-card">
                <div class="author-photo">
                    <span class="author-initials" aria-hidden="true"><?php echo $hs($abInitials); ?></span>
                    <?php if ($abPhoto !== ''): ?><img loading="lazy" decoding="async" src="<?php echo $hs($abPhoto); ?>" alt="<?php echo $hs($ab['name']); ?>, <?php echo $hs($ab['role']); ?>" onerror="this.remove()"><?php endif; ?>
                </div>
                <div class="author-copy">
                    <span class="eyebrow"><?php echo $hs($ab['eyebrow']); ?></span>
                    <h2><?php echo $hs($ab['name']); ?></h2>
                    <p class="author-role"><?php echo $hs($ab['role']); ?></p>
                    <p class="author-bio"><?php echo nl2br($hs($ab['bio'])); ?></p>
                    <?php if ($ab['highlights']): ?>
                    <div class="author-highlights">
                        <?php foreach ($ab['highlights'] as [$hv, $hl]): ?><div><strong><?php echo $hs($hv); ?></strong><span><?php echo $hs($hl); ?></span></div><?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($ab['specialties']): ?>
                    <ul class="author-tags" aria-label="Areas of expertise">
                        <?php foreach ($ab['specialties'] as $sp): ?><li><?php echo $hs($sp); ?></li><?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if ($ab['links']): ?>
                    <div class="author-links">
                        <?php foreach ($ab['links'] as $k => $u):
                            if (!isset($abIcons[$k]) || $u === '') { continue; }
                            $href = $k === 'email' ? 'mailto:' . $u : $u;
                            if ($k !== 'email' && !preg_match('#^https?://#i', $href)) { continue; } ?>
                        <a href="<?php echo $hs($href); ?>" aria-label="<?php echo $hs($ab['name'] . ' on ' . $abIcons[$k][1]); ?>"<?php echo $k === 'email' ? '' : ' target="_blank" rel="noopener noreferrer"'; ?>><i class="<?php echo $abIcons[$k][0]; ?>"></i></a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <script type="application/ld+json" data-origin="code"><?php echo $hsJson($abSchema); ?></script>
    </section>
<?php endif; ?>

<?php if ($reviewCards): ?>
    <!-- GOOGLE REVIEWS -->
    <section class="rv-section fade-up" id="reviews">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow"><?php echo $hs($reviewsSection['eyebrow']); ?></span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;"><?php echo $hs($reviewSource === 'google' ? $reviewsSection['heading'] : $reviewsSection['heading_fallback']); ?></h2>
            </div>

            <div class="rv-bar">
                <?php if ($reviewSource === 'google' && $google['rating']): ?>
                <div class="rv-summary">
                    <span class="rv-g"><i class="fa-brands fa-google"></i></span>
                    <strong class="rv-score"><?php echo $hs(number_format((float) $google['rating'], 1)); ?></strong>
                    <span class="rv-stars" role="img" aria-label="Rated <?php echo $hs($google['rating']); ?> out of 5"><?php for ($i = 1; $i <= 5; $i++): ?><i class="fa-solid fa-star<?php echo $i <= round($google['rating']) ? '' : ' rv-star-off'; ?>"></i><?php endfor; ?></span>
                    <?php if ($google['total'] > 0): ?><span class="rv-count">Based on <?php echo (int) $google['total']; ?> Google reviews</span><?php endif; ?>
                </div>
                <?php else: ?>
                <div class="rv-summary"><span class="rv-count">Feedback from the businesses we work with</span></div>
                <?php endif; ?>
                <div class="rv-nav">
                    <button type="button" class="rv-btn" data-rv="prev" aria-label="Previous reviews"><i class="fa-solid fa-arrow-left"></i></button>
                    <button type="button" class="rv-btn" data-rv="next" aria-label="Next reviews"><i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>

            <div class="rv-track" tabindex="0" role="region" aria-label="Client reviews">
                <?php foreach ($reviewCards as $rc):
                    $initial = mb_strtoupper(mb_substr(trim((string) $rc['name']), 0, 1)); ?>
                <article class="rv-card">
                    <div class="rv-card-top">
                        <div class="rv-avatar">
                            <span aria-hidden="true"><?php echo $hs($initial); ?></span>
                            <?php if ($rc['photo'] !== ''): ?><img loading="lazy" decoding="async" referrerpolicy="no-referrer" src="<?php echo $hs($rc['photo']); ?>" alt="" onerror="this.remove()"><?php endif; ?>
                        </div>
                        <div class="rv-who">
                            <h3><?php echo $rc['url'] !== '' ? '<a href="' . $hs($rc['url']) . '" target="_blank" rel="noopener noreferrer">' . $hs($rc['name']) . '</a>' : $hs($rc['name']); ?></h3>
                            <?php if ($rc['sub'] !== ''): ?><span><?php echo $hs($rc['sub']); ?></span><?php endif; ?>
                        </div>
                        <?php if ($reviewSource === 'google'): ?><span class="rv-g rv-g-sm" title="Google review"><i class="fa-brands fa-google"></i></span><?php endif; ?>
                    </div>
                    <div class="rv-card-stars" role="img" aria-label="<?php echo (int) $rc['rating']; ?> out of 5 stars"><?php for ($i = 0; $i < (int) $rc['rating']; $i++): ?><i class="fa-solid fa-star"></i><?php endfor; ?></div>
                    <p class="rv-text"><?php echo nl2br($hs($rc['text'])); ?></p>
                    <button type="button" class="rv-more" hidden>Read more</button>
                </article>
                <?php endforeach; ?>
            </div>

            <div class="rv-actions">
                <?php if ($reviewSource === 'google'): ?><a href="<?php echo $hs($googleLink); ?>" target="_blank" rel="noopener noreferrer" class="btn-secondary"><i class="fa-brands fa-google" style="margin-right: 8px;"></i>SEE ALL GOOGLE REVIEWS</a><?php endif; ?>
                <a href="/reviews" class="rv-link">Read more client feedback <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if ($latestPosts): ?>
    <!-- LATEST BLOG -->
    <section class="lb-section fade-up" id="latest-blog">
        <div class="container">
            <div class="section-header">
                <span class="eyebrow">FROM THE BLOG</span>
                <h2 style="font-size: clamp(1.8rem, 4vw, 2.8rem); margin-top: 10px;">Latest Insights For Growing Your Business</h2>
            </div>
            <div class="case-grid">
                <?php foreach ($latestPosts as $post): ?>
                <article class="case-card blog-card">
                    <div class="case-image">
                        <?php if ($post['category']): ?><span class="case-tag"><?php echo $hs($post['category']); ?></span><?php endif; ?>
                        <img loading="lazy" decoding="async" src="<?php echo $hs($post['image_path'] !== '' ? $post['image_path'] : blog_fallback_image((string) $post['category'])); ?>" alt="<?php echo $hs(($post['image_alt'] ?? '') !== '' ? $post['image_alt'] : $post['title']); ?>">
                    </div>
                    <div class="case-content">
                        <div>
                            <h3 class="bc-title"><?php echo $hs($post['title']); ?></h3>
                            <?php if (!empty($post['created_at'])): ?><p class="lb-date"><i class="fa-regular fa-calendar"></i> <?php echo $hs(date('j F Y', strtotime($post['created_at']))); ?></p><?php endif; ?>
                            <p class="lb-excerpt"><?php echo $hs($post['excerpt']); ?></p>
                        </div>
                        <div>
                            <a href="/blog/<?php echo urlencode($post['slug']); ?>" class="service-link" aria-label="Read More &ndash; <?php echo $hs($post['title']); ?>">Read More <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: clamp(30px, 4vw, 40px);">
                <a href="/blog" class="btn-secondary">VIEW ALL POSTS <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i></a>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php if ($homeFaqs):
    $faqSchema = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array_map(static fn($f) => [
        '@type' => 'Question',
        'name' => html_entity_decode($f[0], ENT_QUOTES, 'UTF-8'),
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(html_entity_decode(strip_tags($f[1]), ENT_QUOTES, 'UTF-8'))],
    ], $homeFaqs)];
?>
    <!-- FAQS: every answer is in the HTML on load; js/main.js collapses them
         into an accordion (buttons with aria-expanded), so without JS they all
         simply stay open. -->
    <section class="hfaq-section fade-up" id="faqs">
        <div class="container hfaq-layout">
            <div class="hfaq-intro">
                <span class="eyebrow">FAQS</span>
                <h2>WS Digital Marketing FAQs</h2>
                <p><?php echo $homeFaqsIntro; ?></p>
                <a href="/contact" class="btn-secondary">SPEAK WITH OUR TEAM <i class="fa-solid fa-arrow-right" style="margin-left: 6px;"></i></a>
            </div>
            <div class="hfaq-list" data-faq>
                <?php foreach ($homeFaqs as $i => [$q, $a]): ?>
                <div class="hfaq-item">
                    <h3><button type="button" class="hfaq-q" id="hfaq-q<?php echo $i + 1; ?>" aria-expanded="true" aria-controls="hfaq-a<?php echo $i + 1; ?>"><?php echo $hs($q); ?></button></h3>
                    <div class="hfaq-answer" id="hfaq-a<?php echo $i + 1; ?>" role="region" aria-labelledby="hfaq-q<?php echo $i + 1; ?>"><p><?php echo $a; ?></p></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <script type="application/ld+json" data-origin="code"><?php echo $hsJson($faqSchema); ?></script>
    </section>
<?php endif; ?>
