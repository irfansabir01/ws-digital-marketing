<?php
// Serves the six Sydney service pages: /seo/sydney, /website-development/sydney,
// /ppc-advertising/sydney, /social-media/sydney, /graphic-design/sydney and
// /ecommerce/sydney. .htaccess rewrites each to this file with ?s=<key>; the copy
// is in includes/sydney/<key>.php and the layout in includes/sydney-template.php.
require_once __DIR__ . '/includes/cta-form-handler.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/case-study-helpers.php';
require_once __DIR__ . '/includes/seo.php';
require_once __DIR__ . '/includes/sydney-pages.php';

$sy = sydney_page((string) ($_GET['s'] ?? ''));
if ($sy === null) {
    header('Location: /services', true, 301);
    exit;
}
// One URL per page: opening this file directly (/sydney-service?s=seo) goes to the real address.
$syRequestPath = rtrim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
if ($syRequestPath !== $sy['path']) {
    header('Location: ' . $sy['path'], true, 301);
    exit;
}

require __DIR__ . '/includes/sydney-template.php';
