<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$action = $_GET['action'] ?? 'list'; $id = intval($_GET['id'] ?? 0); $pageTitle = 'Brochures';
if ($action === 'delete' && $id) { requireCsrf(); deleteUpload(dbFetchOne("SELECT file_path FROM brochures WHERE id = ?", [$id])['file_path'] ?? ''); deleteUpload(dbFetchOne("SELECT thumbnail FROM brochures WHERE id = ?", [$id])['thumbnail'] ?? ''); dbExecute("DELETE FROM brochures WHERE id = ?", [$id]); setFlash('success', 'Brochure deleted.'); redirect('brochures.php'); }
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf(); $data = ['title' => trim($_POST['title']), 'description' => trim($_POST['description']), 'file_type' => $_POST['file_type'] ?? 'pdf', 'status' => intval($_POST['status'] ?? 1), 'sort_order' => intval($_POST['sort_order'] ?? 0)];
    if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] === UPLOAD_ERR_OK) { $r = uploadFile($_FILES['file_path'], 'brochures', array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_PDF_TYPES)); if ($r['success']) $data['file_path'] = $r['path']; }
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) { $r = uploadFile($_FILES['thumbnail'], 'brochures'); if ($r['success']) $data['thumbnail'] = $r['path']; }
    if ($id) { $fs = []; $vs = []; foreach ($data as $k => $v) { $fs[] = "$k = ?"; $vs[] = $v; } $vs[] = $id; dbExecute("UPDATE brochures SET " . implode(', ', $fs) . " WHERE id = ?", $vs); setFlash('success', 'Brochure updated.'); }
    else { $cols = implode(', ', array_keys($data)); $ph = implode(', ', array_fill(0, count($data), '?')); dbInsert("INSERT INTO brochures ($cols) VALUES ($ph)", array_values($data)); setFlash('success', 'Brochure created.'); }
    redirect('brochures.php');
}
$editItem = $id && $action === 'edit' ? dbFetchOne("SELECT * FROM brochures WHERE id = ?", [$id]) : null;
$items = dbFetchAll("SELECT * FROM brochures ORDER BY sort_order, title");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Brochures</h1><div class="content-actions"><a href="brochures.php" class="btn btn-outline btn-sm">List</a><a href="brochures.php?action=add" class="btn btn-primary btn-sm">+ Add</a></div></div>
    <?php echo showFlash(); ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <form method="POST" action="brochures.php?action=save<?php echo $id ? '&id='.$id : ''; ?>" enctype="multipart/form-data">
            <?php echo csrfField(); ?>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Title *</label><input type="text" name="title" class="form-input" required value="<?php echo e($editItem['title'] ?? ''); ?>"></div>
                <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-input" value="<?php echo e($editItem['sort_order'] ?? 0); ?>"></div>
            </div>
            <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-textarea" rows="3"><?php echo e($editItem['description'] ?? ''); ?></textarea></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">File (PDF)</label><input type="file" name="file_path" class="form-input" accept=".pdf,image/*"><?php if (!empty($editItem['file_path'])): ?><br><a href="<?php echo e($editItem['file_path']); ?>" target="_blank" style="font-size:12px;">View current file</a><?php endif; ?></div>
                <div class="form-group"><label class="form-label">Thumbnail</label><input type="file" name="thumbnail" class="form-input" accept="image/*"><?php if (!empty($editItem['thumbnail'])): ?><br><img src="<?php echo e($editItem['thumbnail']); ?>" alt="" style="max-width:80px;margin-top:4px;border-radius:4px;"><?php endif; ?></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">File Type</label><select name="file_type" class="form-select"><option value="pdf">PDF</option><option value="image" <?php echo ($editItem['file_type'] ?? '') === 'image' ? 'selected' : ''; ?>>Image</option></select></div>
                <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Active</option><option value="0" <?php echo ($editItem['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactive</option></select></div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <?php else: ?>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>File</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td><?php echo e($item['title']); ?></td><td><?php if ($item['file_path']): ?><a href="<?php echo e($item['file_path']); ?>" target="_blank" style="font-size:12px;">View</a><?php else: ?>-<?php endif; ?></td><td><?php echo e($item['sort_order']); ?></td><td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Active' : 'Inactive'; ?></span></td><td><a href="brochures.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a> <a href="brochures.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
