<?php
require_once __DIR__ . '/includes/auth.php';
require_role(['admin', 'seo']);
require_once __DIR__ . '/includes/seo-admin.php';
$page_title = 'Site Settings & Code';
seo_require_tables();
$pdo = get_db();

$keys = ['title_suffix', 'default_og_image', 'unmatched_404', 'schema_json', 'head_code', 'body_code', 'footer_code'];
$v = [];
foreach ($keys as $k) {
    $v[$k] = seo_setting($k);
}
$v['title_suffix'] ??= SEO_DEFAULT_TITLE_SUFFIX;
$v['unmatched_404'] ??= 'redirect_home';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $errors = [];
    $new = [];
    $new['title_suffix'] = substr((string) ($_POST['title_suffix'] ?? ''), 0, 100);
    $og = trim((string) ($_POST['default_og_image'] ?? ''));
    if ($og !== '' && seo_clean_target($og) === null) {
        $errors[] = 'The default share image must be a full URL (https://...) or a path starting with /.';
        $og = (string) $v['default_og_image'];
    }
    $new['default_og_image'] = $og;
    $new['unmatched_404'] = ($_POST['unmatched_404'] ?? '') === 'show_404' ? 'show_404' : 'redirect_home';
    $schema = seo_validate_json((string) ($_POST['schema_json'] ?? ''));
    if ($schema === null) {
        $errors[] = 'The site-wide structured data is not valid JSON, so it was not saved.';
        $schema = (string) $v['schema_json'];
    }
    $new['schema_json'] = $schema;
    foreach (['head_code', 'body_code', 'footer_code'] as $k) {
        $new[$k] = trim(str_replace("\r\n", "\n", (string) ($_POST[$k] ?? '')));
        if (strlen($new[$k]) > 50000) {
            $errors[] = 'One of the code boxes is too large (limit 50,000 characters).';
            $new[$k] = (string) $v[$k];
        }
    }
    foreach ($errors as $e) {
        flash_set('error', $e);
    }
    foreach ($new as $k => $val) {
        seo_setting_save($pdo, $k, $val);
    }
    if (!$errors) {
        flash_set('success', 'Settings saved. They apply to every page immediately.');
    }
    header('Location: seo-settings.php');
    exit;
}

include __DIR__ . '/includes/layout-header.php';
flash_render();
?>
<form method="post" class="admin-form">
    <?php echo csrf_field(); ?>

    <div class="admin-card">
        <div class="section-title">Page titles &amp; sharing</div>
        <label for="title_suffix">Title suffix (added after every page title)</label>
        <input type="text" id="title_suffix" name="title_suffix" value="<?php echo h($v['title_suffix']); ?>" maxlength="100">
        <div class="hint">Currently <code><?php echo h($v['title_suffix']); ?></code>. Include the separator, e.g. <code> | W&amp;S Digital Marketing</code>. Leave empty for no suffix. Individual pages can opt out.</div>

        <label for="default_og_image">Default share image</label>
        <input type="text" id="default_og_image" name="default_og_image" value="<?php echo h($v['default_og_image']); ?>" placeholder="/images/share-default.jpg or https://...">
        <div class="hint">Used when a page has no image of its own, for link previews on Facebook, LinkedIn, X and WhatsApp. Best size 1200&times;630px.</div>
    </div>

    <div class="admin-card">
        <div class="section-title">404 behaviour</div>
        <p style="margin:0 0 8px;">When someone opens an address that doesn't exist and has no redirect:</p>
        <label class="check"><input type="radio" name="unmatched_404" value="redirect_home" <?php echo $v['unmatched_404'] === 'redirect_home' ? 'checked' : ''; ?>> Send them to the most relevant page, or the homepage (301) &mdash; the default</label>
        <label class="check"><input type="radio" name="unmatched_404" value="show_404" <?php echo $v['unmatched_404'] === 'show_404' ? 'checked' : ''; ?>> Show a real "Page Not Found" page (404 status) &mdash; what Google recommends for removed pages</label>
        <div class="hint">Either way the miss is logged in <a href="seo-404.php">404 Errors</a> so you can add redirects for the important ones.</div>
    </div>

    <div class="admin-card">
        <div class="section-title">Site-wide structured data (JSON-LD)</div>
        <label for="schema_json">Organisation / LocalBusiness schema <button type="button" class="btn btn-secondary btn-sm" id="fill-org" style="float:right;">Insert starter template</button></label>
        <textarea id="schema_json" name="schema_json" class="mono tall" rows="10" placeholder='{"@context":"https://schema.org","@type":"Organization", ...}'><?php echo h($v['schema_json']); ?></textarea>
        <div class="hint">Added to the <code>&lt;head&gt;</code> of every page. Paste JSON only (no <code>&lt;script&gt;</code> tags) &mdash; it's validated when you save. Page-specific schema is set per page in Pages SEO.</div>
    </div>

    <div class="admin-card">
        <div class="section-title">Custom code (header &amp; footer)</div>
        <div class="alert alert-warning">This code is printed on <strong>every public page exactly as typed</strong>. Only paste snippets from sources you trust (Google Analytics, Tag Manager, Search Console, Meta Pixel...) &mdash; a wrong snippet can break or compromise the whole site.</div>

        <label for="head_code">Inside &lt;head&gt;</label>
        <textarea id="head_code" name="head_code" class="mono tall" rows="7" spellcheck="false" placeholder='<meta name="google-site-verification" content="..." />&#10;<!-- Google tag (gtag.js) -->'><?php echo h($v['head_code']); ?></textarea>
        <div class="hint">Search Console / Bing verification meta tags, Analytics, Tag Manager (head part), extra <code>&lt;link&gt;</code> tags.</div>

        <label for="body_code">Right after &lt;body&gt; opens</label>
        <textarea id="body_code" name="body_code" class="mono" rows="4" spellcheck="false" placeholder='<!-- Google Tag Manager (noscript) -->'><?php echo h($v['body_code']); ?></textarea>

        <label for="footer_code">Before &lt;/body&gt; closes</label>
        <textarea id="footer_code" name="footer_code" class="mono" rows="5" spellcheck="false" placeholder="Chat widgets, tracking pixels that load last..."><?php echo h($v['footer_code']); ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Save Settings</button>
</form>

<script>
document.getElementById('fill-org').addEventListener('click', function () {
    var box = document.getElementById('schema_json');
    if (box.value.trim() !== '' && !confirm('Replace what is in the box with the starter template?')) { return; }
    box.value = JSON.stringify({
        "@context": "https://schema.org",
        "@type": "ProfessionalService",
        "name": "W&S Digital Marketing",
        "url": "https://wsdigitalmarketing.com.au/",
        "logo": "https://wsdigitalmarketing.com.au/images/logo-ws.webp",
        "telephone": "+61403889630",
        "areaServed": "AU",
        "sameAs": ["https://www.facebook.com/w.and.s.digital.marketing"]
    }, null, 2);
});
</script>
<?php include __DIR__ . '/includes/layout-footer.php'; ?>
