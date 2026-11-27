<?php
// Session guard + CSRF helper, included at the top of every protected admin
// page. login.php is the only admin page that does NOT include this.
require_once __DIR__ . '/secure-session.php';

require_once __DIR__ . '/../../config.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Re-check the account on every request, so a user who is disabled, deleted
// or given a different role loses (or gains) access immediately rather than
// at their next login. Roles: 'admin' (everything) and 'seo' (the SEO tools).
$adminAccount = null;
try {
    $stmt = get_db()->prepare('SELECT id, username, role, is_active FROM admin_users WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $adminAccount = $stmt->fetch() ?: null;
} catch (PDOException $e) {
    // The SEO migration hasn't been run yet: there are no role columns, and
    // every existing account is a full admin.
    try {
        $stmt = get_db()->prepare('SELECT id, username FROM admin_users WHERE id = ?');
        $stmt->execute([$_SESSION['admin_id']]);
        $legacy = $stmt->fetch();
        $adminAccount = $legacy ? $legacy + ['role' => 'admin', 'is_active' => 1] : null;
    } catch (PDOException $e2) {
        $adminAccount = null;
    }
}
if (!$adminAccount || !(int) $adminAccount['is_active']) {
    $_SESSION = [];
    session_destroy();
    header('Location: login.php');
    exit;
}
$_SESSION['admin_role'] = (string) $adminAccount['role'];
$_SESSION['admin_username'] = (string) $adminAccount['username'];

function admin_role(): string
{
    return $_SESSION['admin_role'] ?? '';
}

function admin_has_role(string ...$roles): bool
{
    return in_array(admin_role(), $roles, true);
}

// Sends the user back to the dashboard if their role isn't one of $roles.
function require_role(array $roles): void
{
    if (!in_array(admin_role(), $roles, true)) {
        header('Location: index.php?denied=1');
        exit;
    }
}

// One CSRF token per session, checked on every state-changing POST.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
}

function csrf_check(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        exit('Invalid or expired form submission. Go back and try again.');
    }
}

// Saves an uploaded image (blog cover / team headshot) into $destDir with a
// safe, unique filename. Returns the web-relative path to store in the DB, or
// null if no file was uploaded / upload was invalid.
function handle_image_upload(string $fieldName, string $destDir, string $slugHint): ?string
{
    if (empty($_FILES[$fieldName])) {
        return null;
    }
    return store_uploaded_image($_FILES[$fieldName], $destDir, $slugHint);
}

// Same as handle_image_upload() for one already-extracted $_FILES entry
// (name/tmp_name/error/size), e.g. one row of a multi-file form.
function store_uploaded_image(array $file, string $destDir, string $slugHint): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed (error code ' . $file['error'] . ').');
    }
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('Image must be under 5MB.');
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Only JPG, PNG, or WEBP images are allowed.');
    }
    $ext = $allowed[$mime];
    $slug = preg_replace('/[^a-z0-9-]+/', '-', strtolower(trim($slugHint)));
    $slug = trim($slug, '-') ?: 'image';
    $filename = $slug . '-' . substr(bin2hex(random_bytes(4)), 0, 8) . '.' . $ext;
    $relativeDestPath = rtrim($destDir, '/') . '/' . $filename;
    $destAbsoluteDir = __DIR__ . '/../../' . rtrim($destDir, '/');
    if (!is_dir($destAbsoluteDir) && !mkdir($destAbsoluteDir, 0755, true) && !is_dir($destAbsoluteDir)) {
        throw new RuntimeException('Could not create the upload folder (' . $destDir . '). Check folder permissions on the server.');
    }
    if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../../' . $relativeDestPath)) {
        throw new RuntimeException('Could not save the uploaded file. Check that the "' . $destDir . '" folder exists and is writable.');
    }
    // Stored (and later rendered) root-relative -- e.g. blog-post.php now
    // serves at /blog/<slug>, and a document-relative "images/blog/x.jpg"
    // would resolve against that URL's own directory instead of the site
    // root, breaking on any page that isn't exactly one path segment deep.
    return '/' . $relativeDestPath;
}
