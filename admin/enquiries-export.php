<?php
require_once __DIR__ . '/includes/auth.php';
require_role(['admin']);
require_once __DIR__ . '/includes/enquiry-admin.php';
$pdo = get_db();
if (!enquiry_ensure_table($pdo)) {
    header('Location: enquiries.php');
    exit;
}
$status = (string) ($_GET['status'] ?? 'active');
$source = (string) ($_GET['source'] ?? '');
$search = trim((string) ($_GET['q'] ?? ''));
[$whereSql, $args] = enquiry_filter_sql($status, $source, $search);
$stmt = $pdo->prepare('SELECT * FROM enquiries ' . $whereSql . ' ORDER BY created_at DESC, id DESC');
$stmt->execute($args);

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="enquiries-' . date('Y-m-d') . '.csv"');
$out = fopen('php://output', 'w');
fwrite($out, "\xEF\xBB\xBF"); // BOM so Excel reads UTF-8
fputcsv($out, ['Received', 'Name', 'Email', 'Phone', 'Service', 'Plan', 'Source', 'Page', 'Status', 'Deal value', 'Message', 'Notes']);
// A leading = + - @ would run as a formula when opened in Excel; prefix it.
$safe = function ($v) {
    $v = (string) $v;
    return $v !== '' && strpbrk($v[0], "=+-@\t\r") !== false ? "'" . $v : $v;
};
while ($r = $stmt->fetch()) {
    fputcsv($out, [
        $r['created_at'], $safe($r['name']), $safe($r['email']), $safe($r['phone']), $safe($r['service']), $safe($r['plan']),
        enquiry_source_label($r['source']), $r['page'], ENQUIRY_STATUSES[$r['status']] ?? $r['status'],
        $r['deal_value'], $safe($r['message']), $safe($r['notes']),
    ]);
}
fclose($out);
