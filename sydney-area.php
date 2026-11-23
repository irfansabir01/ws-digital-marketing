<?php
// Serves the suburb SEO pages: /seo/sydney/marsden-park, /rouse-hill, /blacktown, /penrith and
// /castle-hill. .htaccess rewrites each to this file with ?a=<key>; the copy is in
// includes/sydney-areas/<key>.php and the layout is the shared includes/sydney-template.php.
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/case-study-helpers.php';
require_once __DIR__ . '/includes/seo.php';
require_once __DIR__ . '/includes/sydney-pages.php';

$sy = sydney_area_page((string) ($_GET['a'] ?? ''));
if ($sy === null) {
    header('Location: /seo/sydney', true, 301);
    exit;
}
// One URL per page: opening this file directly (/sydney-area?a=penrith) goes to the real address.
$syRequestPath = rtrim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
if ($syRequestPath !== $sy['path']) {
    header('Location: ' . $sy['path'], true, 301);
    exit;
}

require __DIR__ . '/includes/sydney-template.php';
