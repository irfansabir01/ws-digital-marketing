<?php
// Final-HTML safety net, registered once from header.php with ob_start().
//
//  1. Structured data de-duplication. Pages ship some JSON-LD from code
//     (marked data-origin="code"), and admin -> SEO adds more (site-wide
//     schema, per-page schema, custom head code). Two Organization / FAQPage /
//     BreadcrumbList nodes on one URL make Google ignore or warn about the
//     markup, so any code-made node whose type the admin already supplies is
//     dropped. Admin-entered markup is never touched.
//  2. Image dimensions. <img> tags without width/height get the real pixel
//     size of the local file so the browser reserves the space before the
//     image loads (no layout shift). css/style.css keeps height:auto for
//     these, so nothing renders at a different size.
//
// Everything is best-effort: on any error the original HTML is returned.

const SEO_SINGLETON_LD_TYPES = [
    'FAQPage', 'BreadcrumbList', 'Organization', 'LocalBusiness',
    'ProfessionalService', 'WebSite', 'Service', 'ItemList',
    'Article', 'BlogPosting', 'NewsArticle', 'Blog',
];

function seo_ld_node_types($node): array
{
    if (!is_array($node) || !isset($node['@type'])) {
        return [];
    }
    $types = is_array($node['@type']) ? $node['@type'] : [$node['@type']];
    return array_values(array_filter($types, 'is_string'));
}

// Splits decoded JSON-LD into its nodes, whichever shape it has.
function seo_ld_nodes($data): array
{
    if (!is_array($data)) {
        return [];
    }
    if (isset($data['@graph']) && is_array($data['@graph'])) {
        return $data['@graph'];
    }
    if (array_keys($data) === range(0, count($data) - 1) && $data !== []) {
        return $data;
    }
    return [$data];
}

function seo_dedupe_json_ld(string $html): string
{
    $re = '#<script\b([^>]*type\s*=\s*["\']application/ld\+json["\'][^>]*)>(.*?)</script>#is';
    if (!preg_match_all($re, $html, $all, PREG_SET_ORDER)) {
        return $html;
    }

    // Pass 1: every singleton type the admin (unmarked blocks) already supplies.
    $taken = [];
    foreach ($all as $m) {
        if (stripos($m[1], 'data-origin="code"') !== false) {
            continue;
        }
        $data = json_decode(trim($m[2]), true);
        foreach (seo_ld_nodes($data) as $node) {
            foreach (seo_ld_node_types($node) as $t) {
                if (in_array($t, SEO_SINGLETON_LD_TYPES, true)) {
                    $taken[$t] = true;
                }
            }
        }
    }

    // Pass 2: filter the code-made blocks (also de-duplicating among themselves).
    return preg_replace_callback($re, function (array $m) use (&$taken) {
        $isCode = stripos($m[1], 'data-origin="code"') !== false;
        if (!$isCode) {
            return $m[0];
        }
        $attrs = str_ireplace(' data-origin="code"', '', $m[1]);
        $data = json_decode(trim($m[2]), true);
        if (!is_array($data)) {
            return '<script' . $attrs . '>' . $m[2] . '</script>';
        }
        $keep = [];
        $dropped = false;
        foreach (seo_ld_nodes($data) as $node) {
            $singles = array_intersect(seo_ld_node_types($node), SEO_SINGLETON_LD_TYPES);
            $clash = false;
            foreach ($singles as $t) {
                if (isset($taken[$t])) {
                    $clash = true;
                }
            }
            if ($clash) {
                $dropped = true;
                continue;
            }
            foreach ($singles as $t) {
                $taken[$t] = true;
            }
            $keep[] = $node;
        }
        if (!$dropped) {
            return '<script' . $attrs . '>' . $m[2] . '</script>';
        }
        if ($keep === []) {
            return '';
        }
        if (isset($data['@graph'])) {
            $data['@graph'] = $keep;
        } elseif (array_keys($data) === range(0, count($data) - 1)) {
            $data = $keep;
        } else {
            $data = $keep[0];
        }
        $json = str_replace('</', '<\\/', (string) json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        return '<script' . $attrs . '>' . $json . '</script>';
    }, $html) ?? $html;
}

function seo_add_image_dimensions(string $html): string
{
    $root = realpath(__DIR__ . '/..');
    if ($root === false) {
        return $html;
    }
    $cache = [];
    return preg_replace_callback('#<img\b([^>]*)>#i', function (array $m) use ($root, &$cache) {
        $attrs = $m[1];
        if (preg_match('/\s(?:width|height)\s*=/i', $attrs)) {
            return $m[0];
        }
        if (!preg_match('/\ssrc\s*=\s*(["\'])(\/[^"\']*)\1/i', $attrs, $s) || substr($s[2], 0, 2) === '//') {
            return $m[0];
        }
        $rel = strtok($s[2], '?#');
        if (!array_key_exists($rel, $cache)) {
            $cache[$rel] = null;
            $file = realpath($root . $rel);
            if ($file !== false && strpos($file, $root . DIRECTORY_SEPARATOR) === 0 && is_file($file)
                && !preg_match('/\.svg$/i', $file)) {
                $size = @getimagesize($file);
                if ($size && $size[0] > 0 && $size[1] > 0) {
                    $cache[$rel] = [$size[0], $size[1]];
                }
            }
        }
        if ($cache[$rel] === null) {
            return $m[0];
        }
        return '<img width="' . $cache[$rel][0] . '" height="' . $cache[$rel][1] . '"' . $attrs . '>';
    }, $html) ?? $html;
}

function seo_output_filter(string $html): string
{
    try {
        if (stripos($html, '<html') === false) {
            return $html;   // not a full page (redirect stub, JSON, fragment)
        }
        $out = seo_dedupe_json_ld($html);
        $out = seo_add_image_dimensions($out);
        return $out !== '' ? $out : $html;
    } catch (Throwable $e) {
        return $html;
    }
}
