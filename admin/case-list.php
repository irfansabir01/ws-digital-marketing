<?php
require_once __DIR__ . '/includes/auth.php';
require_role(['admin']);
require_once __DIR__ . '/../includes/case-study-helpers.php';
$page_title = 'Case Studies';
$pdo = get_db();
try {
    $studies = $pdo->query('SELECT id, name, tag, image_path, has_data, display_order, related_services FROM case_studies ORDER BY display_order ASC, name ASC')->fetchAll();
    $hasRelatedServices = true;
} catch (PDOException $e) {
    // related_services column not added yet -- list still works without it.
    $studies = $pdo->query('SELECT id, name, tag, image_path, has_data, display_order FROM case_studies ORDER BY display_order ASC, name ASC')->fetchAll();
    $hasRelatedServices = false;
}

include __DIR__ . '/includes/layout-header.php';
?>
<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert alert-success">Case study deleted.</div>
<?php endif; ?>
<?php if (!empty($_GET['saved'])): ?>
    <div class="alert alert-success">Case study saved.</div>
<?php endif; ?>

<div class="page-actions">
    <a href="case-weekly-all.php" class="btn btn-secondary">Weekly Update (all case studies)</a>
    <a href="case-form.php" class="btn btn-primary">+ Add Case Study</a>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Tag</th>
            <th>Results Data</th>
            <th>Shown On Service Pages</th>
            <th>Order</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!$studies): ?>
            <tr><td colspan="7">No case studies yet.</td></tr>
        <?php endif; ?>
        <?php foreach ($studies as $study): ?>
            <tr>
                <td><?php if ($study['image_path']): ?><img src="<?php echo htmlspecialchars($study['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt=""><?php endif; ?></td>
                <td><?php echo htmlspecialchars($study['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($study['tag'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo $study['has_data'] ? 'Yes' : 'Scope only'; ?></td>
                <td>
                    <?php
                    $shownOn = $hasRelatedServices ? parse_related_services($study['related_services']) : [];
                    if (!$hasRelatedServices) {
                        echo '&mdash;';
                    } elseif (!$shownOn) {
                        echo 'Case Studies page only';
                    } else {
                        echo htmlspecialchars(implode(', ', array_map(fn($s) => CASE_STUDY_SERVICE_PAGES[$s], $shownOn)), ENT_QUOTES, 'UTF-8');
                    }
                    ?>
                </td>
                <td><?php echo (int) $study['display_order']; ?></td>
                <td>
                    <a href="case-form.php?id=<?php echo (int) $study['id']; ?>" class="btn btn-secondary btn-sm">Edit</a>
                    <a href="case-weekly.php?id=<?php echo (int) $study['id']; ?>" class="btn btn-secondary btn-sm">Weekly Stats</a>
                    <form method="post" action="case-delete.php" style="display:inline;" onsubmit="return confirm('Delete this case study? This cannot be undone.');">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo (int) $study['id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/includes/layout-footer.php'; ?>
