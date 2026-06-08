<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$action = $_GET['action'] ?? 'list'; $id = intval($_GET['id'] ?? 0); $pageTitle = 'Countries';
if ($action === 'delete' && $id) { requireCsrf(); dbExecute("DELETE FROM countries WHERE id = ?", [$id]); setFlash('success', 'Deleted.'); redirect('countries.php'); }
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf(); $data = ['name' => trim($_POST['name']), 'slug' => generateUniqueSlug('countries', $_POST['name'], $id), 'short_description' => trim($_POST['short_description']), 'content' => trim($_POST['content']), 'seo_title' => trim($_POST['seo_title']), 'seo_description' => trim($_POST['seo_description']), 'status' => intval($_POST['status'] ?? 1), 'sort_order' => intval($_POST['sort_order'] ?? 0)];
    if ($id) { $fields = []; $vals = []; foreach ($data as $k => $v) { $fields[] = "$k = ?"; $vals[] = $v; } $vals[] = $id; dbExecute("UPDATE countries SET " . implode(', ', $fields) . " WHERE id = ?", $vals); setFlash('success', 'Updated.'); }
    else { $cols = implode(', ', array_keys($data)); $ph = implode(', ', array_fill(0, count($data), '?')); dbInsert("INSERT INTO countries ($cols) VALUES ($ph)", array_values($data)); setFlash('success', 'Created.'); }
    redirect('countries.php');
}
$editItem = $id && $action === 'edit' ? dbFetchOne("SELECT * FROM countries WHERE id = ?", [$id]) : null;
$items = dbFetchAll("SELECT * FROM countries ORDER BY sort_order, name");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Countries</h1><div class="content-actions"><a href="countries.php" class="btn btn-outline btn-sm">List</a><a href="countries.php?action=add" class="btn btn-primary btn-sm">+ Add</a></div></div>
    <?php echo showFlash(); ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <form method="POST" action="countries.php?action=save<?php echo $id ? '&id=' . $id : ''; ?>">
            <?php echo csrfField(); ?>
            <div class="form-row"><div class="form-group"><label class="form-label">Name *</label><input type="text" name="name" class="form-input" required value="<?php echo e($editItem['name'] ?? ''); ?>"></div><div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-input" value="<?php echo e($editItem['sort_order'] ?? 0); ?>"></div></div>
            <div class="form-group"><label class="form-label">Short Description</label><textarea name="short_description" class="form-textarea" rows="3"><?php echo e($editItem['short_description'] ?? ''); ?></textarea></div>
            <div class="form-group"><label class="form-label">Content (HTML)</label><textarea name="content" class="form-textarea" rows="10"><?php echo e($editItem['content'] ?? ''); ?></textarea></div>
            <div class="form-row"><div class="form-group"><label class="form-label">SEO Title</label><input type="text" name="seo_title" class="form-input" value="<?php echo e($editItem['seo_title'] ?? ''); ?>"></div><div class="form-group"><label class="form-label">SEO Description</label><input type="text" name="seo_description" class="form-input" value="<?php echo e($editItem['seo_description'] ?? ''); ?>"></div></div>
            <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Active</option><option value="0" <?php echo ($editItem['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactive</option></select></div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <?php else: ?>
    <div class="admin-card">
        <table class="admin-table"><thead><tr><th>Name</th><th>Slug</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td><?php echo e($item['name']); ?></td><td style="font-family:monospace;font-size:12px;color:var(--muted);"><?php echo e($item['slug']); ?></td><td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Active' : 'Inactive'; ?></span></td><td><a href="countries.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a> <a href="countries.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
