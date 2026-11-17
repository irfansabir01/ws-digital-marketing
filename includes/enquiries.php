<?php
// Enquiry tracker: every enquiry-form submission (the contact page and the
// "Ready To...?" CTA forms on every other page) is saved to the `enquiries`
// table so it can be followed up from admin -> Enquiries, not just emailed.
//
// Fail-safe: saving never breaks a form. If the database is unreachable or
// the table can't be created, enquiry_save() returns null and the form
// carries on exactly as before (the notification email is still sent).

require_once __DIR__ . '/seo.php';

// status key => label shown in admin
const ENQUIRY_STATUSES = [
    'new' => 'New',
    'contacted' => 'Contacted',
    'quoted' => 'Quoted',
    'won' => 'Won',
    'lost' => 'Lost',
    'spam' => 'Spam',
];

function enquiry_create_table_sql(): string
{
    return "CREATE TABLE IF NOT EXISTS enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(60) NOT NULL DEFAULT '',
    service VARCHAR(150) NOT NULL DEFAULT '',
    plan VARCHAR(50) NOT NULL DEFAULT '',
    source VARCHAR(40) NOT NULL DEFAULT '',
    page VARCHAR(255) NOT NULL DEFAULT '',
    message TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'new',
    notes TEXT NULL,
    deal_value DECIMAL(12,2) NULL,
    email_sent TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
}

// Creates the table if it doesn't exist yet (no phpMyAdmin step needed).
// Returns false when the table can't be created or read.
function enquiry_ensure_table(PDO $pdo): bool
{
    static $ok = null;
    if ($ok !== null) {
        return $ok;
    }
    try {
        $pdo->exec(enquiry_create_table_sql());
        return $ok = true;
    } catch (Throwable $e) {
        return $ok = false;
    }
}

// Saves one enquiry. $d keys: name, email, phone, service, plan, source,
// page, message (already cleaned by the caller). Returns the new row id,
// the id of an identical enquiry sent in the last 2 minutes (double click /
// refresh), or null when it couldn't be saved.
function enquiry_save(array $d): ?int
{
    $pdo = seo_db();
    if (!$pdo || !enquiry_ensure_table($pdo)) {
        return null;
    }
    $cut = function ($v, int $max) {
        $v = (string) $v;
        return function_exists('mb_substr') ? mb_substr($v, 0, $max) : substr($v, 0, $max);
    };
    $row = [
        $cut($d['name'] ?? '', 150),
        $cut($d['email'] ?? '', 190),
        $cut($d['phone'] ?? '', 60),
        $cut($d['service'] ?? '', 150),
        $cut($d['plan'] ?? '', 50),
        $cut($d['source'] ?? '', 40),
        $cut($d['page'] ?? '', 255),
        $cut($d['message'] ?? '', 10000),
    ];
    try {
        $dupe = $pdo->prepare('SELECT id FROM enquiries WHERE email = ? AND message = ? AND created_at > (NOW() - INTERVAL 2 MINUTE) LIMIT 1');
        $dupe->execute([$row[1], $row[7]]);
        $existing = $dupe->fetchColumn();
        if ($existing) {
            return (int) $existing;
        }
        $pdo->prepare('INSERT INTO enquiries (name, email, phone, service, plan, source, page, message) VALUES (?,?,?,?,?,?,?,?)')
            ->execute($row);
        return (int) $pdo->lastInsertId();
    } catch (Throwable $e) {
        return null;
    }
}

// Records whether the notification email for an enquiry went out, so a
// failed email is visible in admin instead of the lead being lost.
function enquiry_mark_emailed(?int $id, bool $sent): void
{
    $pdo = seo_db();
    if (!$id || !$pdo) {
        return;
    }
    try {
        $pdo->prepare('UPDATE enquiries SET email_sent = ? WHERE id = ?')->execute([$sent ? 1 : 0, $id]);
    } catch (Throwable $e) {
        // best-effort
    }
}
