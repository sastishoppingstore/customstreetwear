<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$action = $_GET['action'] ?? 'list'; $id = intval($_GET['id'] ?? 0); $pageTitle = 'Sliders';
if ($action === 'delete' && $id) { requireCsrf(); dbExecute("DELETE FROM sliders WHERE id = ?", [$id]); setFlash('success', 'Deleted.'); redirect('sliders.php'); }
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf(); $data = ['title' => trim($_POST['title']), 'subtitle' => trim($_POST['subtitle']), 'description' => trim($_POST['description']), 'button_text' => trim($_POST['button_text']), 'button_link' => trim($_POST['button_link']), 'sort_order' => intval($_POST['sort_order'] ?? 0), 'status' => intval($_POST['status'] ?? 1)];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) { $r = uploadFile($_FILES['image'], 'sliders'); if ($r['success']) $data['image'] = $r['path']; }
    if ($id) { $fields = []; $vals = []; foreach ($data as $k => $v) { $fields[] = "$k = ?"; $vals[] = $v; } $vals[] = $id; dbExecute("UPDATE sliders SET " . implode(', ', $fields) . " WHERE id = ?", $vals); setFlash('success', 'Updated.'); }
    else { $cols = implode(', ', array_keys($data)); $ph = implode(', ', array_fill(0, count($data), '?')); dbInsert("INSERT INTO sliders ($cols) VALUES ($ph)", array_values($data)); setFlash('success', 'Created.'); }
    redirect('sliders.php');
}
$editItem = $id && $action === 'edit' ? dbFetchOne("SELECT * FROM sliders WHERE id = ?", [$id]) : null;
$items = dbFetchAll("SELECT * FROM sliders ORDER BY sort_order");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Sliders</h1><div class="content-actions"><a href="sliders.php" class="btn btn-outline btn-sm">List</a><a href="sliders.php?action=add" class="btn btn-primary btn-sm">+ Add</a></div></div>
    <?php echo showFlash(); ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <form method="POST" action="sliders.php?action=save<?php echo $id ? '&id=' . $id : ''; ?>" enctype="multipart/form-data">
            <?php echo csrfField(); ?>
            <div class="form-row"><div class="form-group"><label class="form-label">Title *</label><input type="text" name="title" class="form-input" required value="<?php echo e($editItem['title'] ?? ''); ?>"></div><div class="form-group"><label class="form-label">Subtitle</label><input type="text" name="subtitle" class="form-input" value="<?php echo e($editItem['subtitle'] ?? ''); ?>"></div></div>
            <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-textarea" rows="3"><?php echo e($editItem['description'] ?? ''); ?></textarea></div>
            <div class="form-row"><div class="form-group"><label class="form-label">Button Text</label><input type="text" name="button_text" class="form-input" value="<?php echo e($editItem['button_text'] ?? 'Get a Quote'); ?>"></div><div class="form-group"><label class="form-label">Button Link</label><input type="text" name="button_link" class="form-input" value="<?php echo e($editItem['button_link'] ?? '/contact'); ?>"></div></div>
            <div class="form-group"><label class="form-label">Slider Image *</label><input type="file" name="image" class="form-input" accept="image/*" <?php echo $id ? '' : 'required'; ?>></div>
            <div class="form-row"><div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-input" value="<?php echo e($editItem['sort_order'] ?? 0); ?>"></div><div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Active</option><option value="0" <?php echo ($editItem['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactive</option></select></div></div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <?php else: ?>
    <div class="admin-card">
        <table class="admin-table"><thead><tr><th>Image</th><th>Title</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td><?php if ($item['image']): ?><img src="<?php echo e($item['image']); ?>" alt=""><?php else: ?>-<?php endif; ?></td><td><?php echo e($item['title']); ?></td><td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Active' : 'Inactive'; ?></span></td><td><a href="sliders.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a> <a href="sliders.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
