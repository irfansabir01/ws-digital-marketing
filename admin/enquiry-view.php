<?php
require_once __DIR__ . '/includes/auth.php';
require_role(['admin']);
require_once __DIR__ . '/includes/enquiry-admin.php';
$pdo = get_db();
if (!enquiry_ensure_table($pdo)) {
    header('Location: enquiries.php');
    exit;
}

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM enquiries WHERE id = ?');
$stmt->execute([$id]);
$e = $stmt->fetch();
if (!$e) {
    flash_set('error', 'That enquiry no longer exists.');
    header('Location: enquiries.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (($_POST['action'] ?? '') === 'delete') {
        $pdo->prepare('DELETE FROM enquiries WHERE id = ?')->execute([$id]);
        flash_set('success', 'Enquiry deleted.');
        header('Location: enquiries.php');
        exit;
    }
    $status = (string) ($_POST['status'] ?? $e['status']);
    if (!isset(ENQUIRY_STATUSES[$status])) {
        $status = $e['status'];
    }
    $notes = trim((string) ($_POST['notes'] ?? ''));
    $valueRaw = trim((string) ($_POST['deal_value'] ?? ''));
    $value = null;
    if ($valueRaw !== '') {
        $clean = preg_replace('/[^0-9.]/', '', $valueRaw);
        $value = $clean !== '' ? min(9999999999.0, (float) $clean) : null;
    }
    $pdo->prepare('UPDATE enquiries SET status = ?, notes = ?, deal_value = ? WHERE id = ?')->execute([$status, $notes, $value, $id]);
    flash_set('success', 'Enquiry updated.');
    header('Location: enquiry-view.php?id=' . $id);
    exit;
}

$page_title = 'Enquiry from ' . $e['name'];
include __DIR__ . '/includes/layout-header.php';
flash_render();
$tel = preg_replace('/[^0-9+]/', '', (string) $e['phone']);
?>
<div class="page-actions"><a href="enquiries.php" class="btn btn-secondary">&larr; All enquiries</a><span></span></div>

<div class="admin-card">
    <div class="form-grid">
        <div><strong>Name</strong><br><?php echo h($e['name']); ?></div>
        <div><strong>Received</strong><br><?php echo h($e['created_at']); ?> <?php echo enquiry_status_badge($e['status']); ?></div>
        <div style="margin-top:14px;"><strong>Email</strong><br><a href="mailto:<?php echo h($e['email']); ?>?subject=<?php echo h(rawurlencode('Re: your enquiry to W&S Digital Marketing')); ?>"><?php echo h($e['email']); ?></a></div>
        <div style="margin-top:14px;"><strong>Phone</strong><br><?php echo $e['phone'] !== '' ? '<a href="tel:' . h($tel) . '">' . h($e['phone']) . '</a>' : '-'; ?></div>
        <div style="margin-top:14px;"><strong>Service needed</strong><br><?php echo h($e['service'] !== '' ? $e['service'] : '-'); ?></div>
        <div style="margin-top:14px;"><strong>Plan</strong><br><?php echo h($e['plan'] !== '' ? $e['plan'] : '-'); ?></div>
        <div style="margin-top:14px;"><strong>Came from</strong><br><?php echo h(enquiry_source_label($e['source'])); ?><?php echo $e['page'] !== '' ? ' &middot; <a href="' . h(SEO_SITE_BASE . $e['page']) . '" target="_blank" rel="noopener">' . h($e['page']) . '</a>' : ''; ?></div>
        <div style="margin-top:14px;"><strong>Notification email</strong><br><?php echo (int) $e['email_sent'] ? 'Sent to info@' : '<span class="badge badge-bad">Failed to send</span> &mdash; this record is the only copy'; ?></div>
    </div>
    <div style="margin-top:18px;"><strong>Message</strong>
        <div style="margin-top:6px; white-space:pre-wrap; background:#F6F8FB; border:1px solid var(--border); border-radius:8px; padding:14px;"><?php echo h($e['message']); ?></div>
    </div>
</div>

<div class="admin-card">
    <form method="post" class="admin-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo (int) $e['id']; ?>">
        <div class="form-grid">
            <div>
                <label for="status">Status</label>
                <select id="status" name="status">
                    <?php foreach (ENQUIRY_STATUSES as $key => $label): ?>
                        <option value="<?php echo h($key); ?>" <?php echo $e['status'] === $key ? 'selected' : ''; ?>><?php echo h($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="deal_value">Deal value (A$) &mdash; optional</label>
                <input type="text" id="deal_value" name="deal_value" inputmode="decimal" placeholder="e.g. 1500" value="<?php echo $e['deal_value'] !== null ? h(rtrim(rtrim(number_format((float) $e['deal_value'], 2, '.', ''), '0'), '.')) : ''; ?>">
            </div>
            <div class="full">
                <label for="notes">Follow-up notes</label>
                <textarea id="notes" name="notes" rows="6" placeholder="Who called whom, what was quoted, next step..."><?php echo h((string) $e['notes']); ?></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top:14px;">Save changes</button>
    </form>
    <form method="post" style="margin-top:14px;" onsubmit="return confirm('Delete this enquiry? This cannot be undone.');">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo (int) $e['id']; ?>">
        <button type="submit" name="action" value="delete" class="btn btn-danger btn-sm">Delete enquiry</button>
    </form>
</div>
<?php include __DIR__ . '/includes/layout-footer.php'; ?>
