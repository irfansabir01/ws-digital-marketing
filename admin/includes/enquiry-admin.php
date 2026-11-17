<?php
// Shared helpers for the Enquiries admin pages.
require_once __DIR__ . '/../../includes/enquiries.php';
require_once __DIR__ . '/seo-admin.php';

function enquiry_status_badge(string $status): string
{
    $class = ['new' => 'badge-info', 'contacted' => 'badge-warn', 'quoted' => 'badge-warn', 'won' => 'badge-ok', 'lost' => '', 'spam' => 'badge-bad'][$status] ?? '';
    $label = ENQUIRY_STATUSES[$status] ?? $status;
    return '<span class="badge ' . $class . '">' . h($label) . '</span>';
}

// WHERE clause + args from the list filters (status, source, search).
function enquiry_filter_sql(string $status, string $source, string $search): array
{
    $where = [];
    $args = [];
    if ($status === 'active') {
        $where[] = "status <> 'spam'";
    } elseif ($status !== 'all' && isset(ENQUIRY_STATUSES[$status])) {
        $where[] = 'status = ?';
        $args[] = $status;
    }
    if ($source !== '') {
        $where[] = 'source = ?';
        $args[] = $source;
    }
    if ($search !== '') {
        $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search) . '%';
        $where[] = '(name LIKE ? OR email LIKE ? OR phone LIKE ? OR message LIKE ? OR service LIKE ?)';
        array_push($args, $like, $like, $like, $like, $like);
    }
    return [$where ? 'WHERE ' . implode(' AND ', $where) : '', $args];
}

function enquiry_source_label(string $source): string
{
    return ['contact-page' => 'Contact page', 'page-form' => 'Page form'][$source] ?? ($source !== '' ? $source : '-');
}
