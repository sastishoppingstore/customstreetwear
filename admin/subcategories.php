<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$action = $_GET['action'] ?? 'list'; $id = intval($_GET['id'] ?? 0); $pageTitle = 'Subcategories';
if ($action === 'delete' && $id) { requireCsrf(); dbExecute("DELETE FROM subcategories WHERE id = ?", [$id]); setFlash('success', 'Subcategory deleted.'); redirect('subcategories.php'); }
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $data = ['category_id' => intval($_POST['category_id']), 'name' => trim($_POST['name']), 'slug' => generateUniqueSlug('subcategories', $_POST['name'], $id), 'description' => trim($_POST['description']), 'seo_title' => trim($_POST['seo_title']), 'seo_description' => trim($_POST['seo_description']), 'status' => intval($_POST['status'] ?? 1), 'sort_order' => intval($_POST['sort_order'] ?? 0)];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) { $r = uploadFile($_FILES['image'], 'subcategories'); if ($r['success']) $data['image'] = $r['path']; }
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) { $r = uploadFile($_FILES['banner_image'], 'subcategories'); if ($r['success']) $data['banner_image'] = $r['path']; }
    if ($id) { $fs = []; $vs = []; foreach ($data as $k => $v) { $fs[] = "$k = ?"; $vs[] = $v; } $vs[] = $id; dbExecute("UPDATE subcategories SET " . implode(', ', $fs) . " WHERE id = ?", $vs); setFlash('success', 'Subcategory updated.'); }
    else { $cols = implode(', ', array_keys($data)); $ph = implode(', ', array_fill(0, count($data), '?')); dbInsert("INSERT INTO subcategories ($cols) VALUES ($ph)", array_values($data)); setFlash('success', 'Subcategory created.'); }
    redirect('subcategories.php');
}
$editItem = $id && $action === 'edit' ? dbFetchOne("SELECT * FROM subcategories WHERE id = ?", [$id]) : null;
$items = dbFetchAll("SELECT s.*, c.name as category_name FROM subcategories s LEFT JOIN categories c ON s.category_id = c.id ORDER BY s.sort_order, s.name");
$categories = dbFetchAll("SELECT * FROM categories WHERE status = 1 ORDER BY name");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Subcategories</h1><div class="content-actions"><a href="subcategories.php" class="btn btn-outline btn-sm">List</a><a href="subcategories.php?action=add" class="btn btn-primary btn-sm">+ Add</a></div></div>
    <?php echo showFlash(); ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <form method="POST" action="subcategories.php?action=save<?php echo $id ? '&id='.$id : ''; ?>" enctype="multipart/form-data">
            <?php echo csrfField(); ?>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Name *</label><input type="text" name="name" class="form-input" required value="<?php echo e($editItem['name'] ?? ''); ?>"></div>
                <div class="form-group"><label class="form-label">Category *</label><select name="category_id" class="form-select" required><option value="">Select Category</option><?php foreach ($categories as $c): ?><option value="<?php echo $c['id']; ?>" <?php echo ($editItem['category_id'] ?? '') == $c['id'] ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option><?php endforeach; ?></select></div>
            </div>
            <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-textarea" rows="3"><?php echo e($editItem['description'] ?? ''); ?></textarea></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Image</label><input type="file" name="image" class="form-input" accept="image/*"><?php if (!empty($editItem['image'])): ?><br><img src="<?php echo e($editItem['image']); ?>" alt="" style="max-width:80px;margin-top:4px;border-radius:4px;"><?php endif; ?></div>
                <div class="form-group"><label class="form-label">Banner Image</label><input type="file" name="banner_image" class="form-input" accept="image/*"><?php if (!empty($editItem['banner_image'])): ?><br><img src="<?php echo e($editItem['banner_image']); ?>" alt="" style="max-width:80px;margin-top:4px;border-radius:4px;"><?php endif; ?></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">SEO Title</label><input type="text" name="seo_title" class="form-input" value="<?php echo e($editItem['seo_title'] ?? ''); ?>"></div>
                <div class="form-group"><label class="form-label">SEO Description</label><input type="text" name="seo_description" class="form-input" value="<?php echo e($editItem['seo_description'] ?? ''); ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-input" value="<?php echo e($editItem['sort_order'] ?? 0); ?>"></div>
                <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Active</option><option value="0" <?php echo ($editItem['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactive</option></select></div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <?php else: ?>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Category</th><th>Slug</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td><?php echo e($item['name']); ?></td><td><?php echo e($item['category_name'] ?? '-'); ?></td><td style="font-family:monospace;font-size:12px;color:var(--muted);"><?php echo e($item['slug']); ?></td><td><?php echo e($item['sort_order']); ?></td><td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Active' : 'Inactive'; ?></span></td><td><a href="subcategories.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a> <a href="subcategories.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
