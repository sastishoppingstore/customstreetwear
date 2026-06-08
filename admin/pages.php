<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$action = $_GET['action'] ?? 'list'; $id = intval($_GET['id'] ?? 0); $pageTitle = 'Pages';
if ($action === 'delete' && $id) { requireCsrf(); dbExecute("DELETE FROM pages WHERE id = ?", [$id]); setFlash('success', 'Page deleted.'); redirect('pages.php'); }
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf(); $data = ['title' => trim($_POST['title']), 'slug' => generateUniqueSlug('pages', $_POST['title'], $id), 'page_type' => $_POST['page_type'] ?? 'static', 'short_description' => trim($_POST['short_description']), 'content' => trim($_POST['content']), 'status' => intval($_POST['status'] ?? 1), 'sort_order' => intval($_POST['sort_order'] ?? 0)];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) { $r = uploadFile($_FILES['image'], 'pages'); if ($r['success']) $data['image'] = $r['path']; }
    if ($id) { $fields = []; $vals = []; foreach ($data as $k => $v) { $fields[] = "$k = ?"; $vals[] = $v; } $vals[] = $id; dbExecute("UPDATE pages SET " . implode(', ', $fields) . " WHERE id = ?", $vals); setFlash('success', 'Page updated.'); }
    else { $cols = implode(', ', array_keys($data)); $ph = implode(', ', array_fill(0, count($data), '?')); dbInsert("INSERT INTO pages ($cols) VALUES ($ph)", array_values($data)); setFlash('success', 'Page created.'); }
    redirect('pages.php');
}
$editItem = $id && $action === 'edit' ? dbFetchOne("SELECT * FROM pages WHERE id = ?", [$id]) : null;
$items = dbFetchAll("SELECT * FROM pages ORDER BY sort_order, title");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Pages</h1><div class="content-actions"><a href="pages.php" class="btn btn-outline btn-sm">List</a><a href="pages.php?action=add" class="btn btn-primary btn-sm">+ Add</a></div></div>
    <?php echo showFlash(); ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <form method="POST" action="pages.php?action=save<?php echo $id ? '&id=' . $id : ''; ?>" enctype="multipart/form-data">
            <?php echo csrfField(); ?>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Title *</label><input type="text" name="title" class="form-input" required value="<?php echo e($editItem['title'] ?? ''); ?>"></div>
                <div class="form-group"><label class="form-label">Page Type</label><select name="page_type" class="form-select"><option value="static">Static</option><option value="dynamic" <?php echo ($editItem['page_type'] ?? '') === 'dynamic' ? 'selected' : ''; ?>>Dynamic</option><option value="service" <?php echo ($editItem['page_type'] ?? '') === 'service' ? 'selected' : ''; ?>>Service</option></select></div>
            </div>
            <div class="form-group"><label class="form-label">Short Description</label><input type="text" name="short_description" class="form-input" value="<?php echo e($editItem['short_description'] ?? ''); ?>"></div>
            <div class="form-group"><label class="form-label">Content (HTML)</label><textarea name="content" class="form-textarea" rows="12"><?php echo e($editItem['content'] ?? ''); ?></textarea></div>
            <div class="form-group"><label class="form-label">Image</label><input type="file" name="image" class="form-input" accept="image/*"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Active</option><option value="0" <?php echo ($editItem['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactive</option></select></div>
                <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-input" value="<?php echo e($editItem['sort_order'] ?? 0); ?>"></div>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <?php else: ?>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Slug</th><th>Type</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td><?php echo e($item['title']); ?></td><td style="font-family:monospace;font-size:12px;color:var(--muted);"><?php echo e($item['slug']); ?></td><td><?php echo e($item['page_type']); ?></td><td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Active' : 'Inactive'; ?></span></td><td><a href="pages.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a> <a href="pages.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
