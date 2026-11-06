<?php
// SEO / GEO helpers for blog-post.php and blog.php.
//
// Nothing here needs a database column or a per-post setting: the FAQ schema
// is read straight from the FAQ section already written inside each post's
// content, so the visible FAQ and the FAQPage markup can never disagree, and
// every post (existing or future) gets the same treatment automatically.

require_once __DIR__ . '/seo.php';

function blog_slugify(string $text): string
{
    $text = strtolower(trim(html_entity_decode(strip_tags($text), ENT_QUOTES, 'UTF-8')));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
    return trim(substr($text, 0, 60), '-');
}

function blog_plain_text(string $html): string
{
    $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return trim(preg_replace('/\s+/u', ' ', $text) ?? '');
}

// Question/answer pairs from the post's FAQ section: a heading reading
// "Frequently Asked Questions" (or "FAQ"), with each question as the next
// heading level down and the answer as the paragraphs/lists that follow it.
// Returns [['q' => ..., 'a' => ...], ...] in plain text; [] when there is none.
function blog_extract_faqs(string $html): array
{
    if (trim($html) === '' || !class_exists('DOMDocument')) {
        return [];
    }
    $prev = libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $ok = $dom->loadHTML('<?xml encoding="utf-8"?><div id="faq-root">' . $html . '</div>');
    libxml_clear_errors();
    libxml_use_internal_errors($prev);
    if (!$ok) {
        return [];
    }
    $root = $dom->getElementById('faq-root');
    if (!$root) {
        return [];
    }

    $faqLevel = null;
    $faqs = [];
    $current = null;
    foreach ($root->childNodes as $node) {
        if (!($node instanceof DOMElement)) {
            continue;
        }
        $isHeading = preg_match('/^h([1-6])$/', $node->nodeName, $m) === 1;
        $level = $isHeading ? (int) $m[1] : 0;

        if ($faqLevel === null) {
            if ($isHeading && preg_match('/^\s*(frequently asked questions|faqs?)\b/i', $node->textContent)) {
                $faqLevel = $level;
            }
            continue;
        }
        if ($isHeading && $level <= $faqLevel) {
            break;                                   // next section: the FAQ is over
        }
        if ($isHeading) {
            if ($current !== null) {
                $faqs[] = $current;
            }
            $current = ['q' => blog_plain_text($node->textContent), 'a' => ''];
        } elseif ($current !== null) {
            $current['a'] .= ' ' . $node->textContent;
        }
    }
    if ($current !== null) {
        $faqs[] = $current;
    }

    $out = [];
    foreach ($faqs as $f) {
        $q = blog_plain_text($f['q']);
        $a = blog_plain_text($f['a']);
        if ($q !== '' && $a !== '') {
            $out[] = ['q' => $q, 'a' => $a];
        }
    }
    return $out;
}

// Body HTML as it is printed on the page. The page already has an <h1>, so
// content that starts at <h3> (as these posts do) skips the <h2> level; shift
// every heading up one level (CSS in style.css keeps the look identical) and
// give each <h2>/<h3> an id so Google can offer "Jump to" links in results.
function blog_prepare_content(string $html): string
{
    if (stripos($html, '<h2') === false && stripos($html, '<h3') !== false) {
        foreach ([3, 4, 5] as $n) {
            $html = preg_replace('#<(/?)h' . $n . '(?=[\s>])#i', '<$1h' . ($n - 1), $html) ?? $html;
        }
    }
    $used = [];
    return preg_replace_callback('#<h([23])(\s[^>]*)?>(.*?)</h\1>#is', function (array $m) use (&$used) {
        $attrs = $m[2] ?? '';
        if (stripos($attrs, ' id=') !== false) {
            return $m[0];
        }
        $slug = blog_slugify($m[3]);
        if ($slug === '') {
            return $m[0];
        }
        $base = $slug;
        for ($i = 2; isset($used[$slug]); $i++) {
            $slug = $base . '-' . $i;
        }
        $used[$slug] = true;
        return '<h' . $m[1] . ' id="' . $slug . '"' . $attrs . '>' . $m[3] . '</h' . $m[1] . '>';
    }, $html) ?? $html;
}

function blog_iso_date(?string $timestamp): string
{
    $time = $timestamp ? strtotime($timestamp) : false;
    return date('c', $time ?: time());
}

// BlogPosting + BreadcrumbList (+ FAQPage when the post has a FAQ section),
// printed as one code-origin JSON-LD block (admin schema for the same URL wins).
function blog_post_schema_tag(array $post, string $url, string $imageUrl, string $description, array $faqs): string
{
    $title = html_entity_decode((string) $post['title'], ENT_QUOTES, 'UTF-8');
    $org = [
        '@type' => 'Organization',
        '@id' => SEO_SITE_BASE . '/#organization',
        'name' => 'W&S Digital Marketing',
        'url' => SEO_SITE_BASE . '/',
    ];
    $publisher = $org + ['logo' => [
        '@type' => 'ImageObject',
        'url' => SEO_SITE_BASE . '/images/logo-ws.webp',
        'width' => 766,
        'height' => 326,
    ]];

    $article = [
        '@type' => 'BlogPosting',
        '@id' => $url . '#article',
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $url],
        'headline' => function_exists('mb_substr') ? mb_substr($title, 0, 110) : substr($title, 0, 110),
        'description' => $description,
        'datePublished' => blog_iso_date($post['created_at'] ?? null),
        'dateModified' => blog_iso_date($post['updated_at'] ?? ($post['created_at'] ?? null)),
        'inLanguage' => 'en-AU',
        'author' => $org,
        'publisher' => $publisher,
        'wordCount' => str_word_count(blog_plain_text((string) ($post['content'] ?? ''))),
    ];
    if (($post['category'] ?? '') !== '') {
        $article['articleSection'] = $post['category'];
    }
    if ($imageUrl !== '') {
        $article['image'] = [$imageUrl];
    }

    $nodes = [
        $article,
        seo_breadcrumb_schema([['Home', '/'], ['Blog', '/blog'], [$title, '/blog/' . $post['slug']]]),
    ];
    if ($faqs) {
        $nodes[] = seo_faq_schema($faqs);
    }
    return seo_graph_tag($nodes);
}

// Open Graph "article" tags (publish/modified time, section) -- the extras
// social platforms and AI crawlers read for freshness.
function blog_article_meta_tags(array $post): string
{
    $out = '    <meta property="article:published_time" content="' . blog_iso_date($post['created_at'] ?? null) . "\">\n";
    $out .= '    <meta property="article:modified_time" content="' . blog_iso_date($post['updated_at'] ?? ($post['created_at'] ?? null)) . "\">\n";
    if (($post['category'] ?? '') !== '') {
        $out .= '    <meta property="article:section" content="' . htmlspecialchars($post['category'], ENT_QUOTES, 'UTF-8') . "\">\n";
    }
    $out .= '    <meta name="author" content="W&amp;S Digital Marketing">' . "\n";
    return $out;
}

// A local cover image for posts whose image_path is empty (an empty src makes
// the browser re-request the page itself), chosen by category.
function blog_fallback_image(string $category): string
{
    $c = strtolower($category);
    if (strpos($c, 'ecommerce') !== false || strpos($c, 'shop') !== false) {
        return '/images/photos/ecommerce.webp';
    }
    if (strpos($c, 'ppc') !== false || strpos($c, 'ads') !== false) {
        return '/images/photos/paid-advertising.webp';
    }
    if (strpos($c, 'web') !== false || strpos($c, 'cro') !== false) {
        return '/images/photos/website-development.webp';
    }
    return '/images/photos/seo.webp';
}

// A meta description cut at a word boundary to fit search results (~155 chars).
function blog_trim_description(string $text, int $max = 155): string
{
    $text = blog_plain_text($text);
    if (mb_strlen($text) <= $max) {
        return $text;
    }
    $cut = mb_substr($text, 0, $max - 1);
    $space = mb_strrpos($cut, ' ');
    if ($space !== false && $space > $max * 0.6) {
        $cut = mb_substr($cut, 0, $space);
    }
    return rtrim($cut, " ,;:-\xE2\x80\x93\xE2\x80\x94") . '…';
}
