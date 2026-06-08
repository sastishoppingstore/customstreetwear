<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$action = $_GET['action'] ?? 'list'; $id = intval($_GET['id'] ?? 0); $pageTitle = 'Fabrics';
if ($action === 'delete' && $id) { requireCsrf(); dbExecute("DELETE FROM fabrics WHERE id = ?", [$id]); setFlash('success', 'Deleted.'); redirect('fabrics.php'); }
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf(); $data = ['title' => trim($_POST['title']), 'slug' => generateUniqueSlug('fabrics', $_POST['title'], $id), 'category' => trim($_POST['category']), 'description' => trim($_POST['description']), 'specs' => trim($_POST['specs']), 'seo_title' => trim($_POST['seo_title']), 'seo_description' => trim($_POST['seo_description']), 'sort_order' => intval($_POST['sort_order'] ?? 0), 'status' => intval($_POST['status'] ?? 1)];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) { $r = uploadFile($_FILES['image'], 'fabrics'); if ($r['success']) $data['image'] = $r['path']; }
    if ($id) { $fields = []; $vals = []; foreach ($data as $k => $v) { $fields[] = "$k = ?"; $vals[] = $v; } $vals[] = $id; dbExecute("UPDATE fabrics SET " . implode(', ', $fields) . " WHERE id = ?", $vals); setFlash('success', 'Updated.'); }
    else { $cols = implode(', ', array_keys($data)); $ph = implode(', ', array_fill(0, count($data), '?')); dbInsert("INSERT INTO fabrics ($cols) VALUES ($ph)", array_values($data)); setFlash('success', 'Created.'); }
    redirect('fabrics.php');
}
$editItem = $id && $action === 'edit' ? dbFetchOne("SELECT * FROM fabrics WHERE id = ?", [$id]) : null;
$items = dbFetchAll("SELECT * FROM fabrics ORDER BY sort_order");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Fabrics</h1><div class="content-actions"><a href="fabrics.php" class="btn btn-outline btn-sm">List</a><a href="fabrics.php?action=add" class="btn btn-primary btn-sm">+ Add</a></div></div>
    <?php echo showFlash(); ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <form method="POST" action="fabrics.php?action=save<?php echo $id ? '&id=' . $id : ''; ?>" enctype="multipart/form-data">
            <?php echo csrfField(); ?>
            <div class="form-row"><div class="form-group"><label class="form-label">Title *</label><input type="text" name="title" class="form-input" required value="<?php echo e($editItem['title'] ?? ''); ?>"></div><div class="form-group"><label class="form-label">Category</label><input type="text" name="category" class="form-input" value="<?php echo e($editItem['category'] ?? ''); ?>"></div></div>
            <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-textarea" rows="3"><?php echo e($editItem['description'] ?? ''); ?></textarea></div>
            <div class="form-group"><label class="form-label">Specifications</label><textarea name="specs" class="form-textarea" rows="4" placeholder="Material: 100% Cotton&#10;Weight: 300 GSM&#10;Width: 60 inches"><?php echo e($editItem['specs'] ?? ''); ?></textarea></div>
            <div class="form-group"><label class="form-label">Image</label><input type="file" name="image" class="form-input" accept="image/*"></div>
            <div class="form-row"><div class="form-group"><label class="form-label">SEO Title</label><input type="text" name="seo_title" class="form-input" value="<?php echo e($editItem['seo_title'] ?? ''); ?>"></div><div class="form-group"><label class="form-label">SEO Description</label><input type="text" name="seo_description" class="form-input" value="<?php echo e($editItem['seo_description'] ?? ''); ?>"></div></div>
            <div class="form-row"><div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-input" value="<?php echo e($editItem['sort_order'] ?? 0); ?>"></div><div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Active</option><option value="0" <?php echo ($editItem['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactive</option></select></div></div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <?php else: ?>
    <div class="admin-card">
        <table class="admin-table"><thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td><?php if ($item['image']): ?><img src="<?php echo e($item['image']); ?>" alt=""><?php else: ?>-<?php endif; ?></td><td><?php echo e($item['title']); ?></td><td><?php echo e($item['category'] ?: '-'); ?></td><td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Active' : 'Inactive'; ?></span></td><td><a href="fabrics.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a> <a href="fabrics.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
