<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$action = $_GET['action'] ?? 'list'; $id = intval($_GET['id'] ?? 0); $pageTitle = 'Menus';
if ($action === 'delete' && $id) { requireCsrf(); dbExecute("DELETE FROM menus WHERE id = ? OR parent_id = ?", [$id, $id]); setFlash('success', 'Menu item deleted.'); redirect('menus.php'); }
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf(); $data = ['title' => trim($_POST['title']), 'url' => trim($_POST['url']), 'menu_location' => $_POST['menu_location'] ?? 'header', 'sort_order' => intval($_POST['sort_order'] ?? 0), 'status' => intval($_POST['status'] ?? 1), 'parent_id' => !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null];
    if ($id) { $fs = []; $vs = []; foreach ($data as $k => $v) { $fs[] = "$k = ?"; $vs[] = $v; } $vs[] = $id; dbExecute("UPDATE menus SET " . implode(', ', $fs) . " WHERE id = ?", $vs); setFlash('success', 'Menu updated.'); }
    else { $cols = implode(', ', array_keys($data)); $ph = implode(', ', array_fill(0, count($data), '?')); dbInsert("INSERT INTO menus ($cols) VALUES ($ph)", array_values($data)); setFlash('success', 'Menu created.'); }
    redirect('menus.php');
}
$editItem = $id && $action === 'edit' ? dbFetchOne("SELECT * FROM menus WHERE id = ?", [$id]) : null;
$items = dbFetchAll("SELECT m.*, (SELECT title FROM menus WHERE id = m.parent_id) as parent_title FROM menus m ORDER BY m.menu_location, m.sort_order, m.title");
$parentOptions = dbFetchAll("SELECT * FROM menus WHERE parent_id IS NULL ORDER BY title");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Navigation Menus</h1><div class="content-actions"><a href="menus.php" class="btn btn-outline btn-sm">List</a><a href="menus.php?action=add" class="btn btn-primary btn-sm">+ Add</a></div></div>
    <?php echo showFlash(); ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <form method="POST" action="menus.php?action=save<?php echo $id ? '&id='.$id : ''; ?>" enctype="multipart/form-data">
            <?php echo csrfField(); ?>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Title *</label><input type="text" name="title" class="form-input" required value="<?php echo e($editItem['title'] ?? ''); ?>"></div>
                <div class="form-group"><label class="form-label">URL</label><input type="text" name="url" class="form-input" value="<?php echo e($editItem['url'] ?? '/'); ?>" placeholder="/products"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Parent Menu</label><select name="parent_id" class="form-select"><option value="">— No Parent —</option><?php foreach ($parentOptions as $p): ?><option value="<?php echo $p['id']; ?>" <?php echo ($editItem['parent_id'] ?? '') == $p['id'] ? 'selected' : ''; ?>><?php echo e($p['title']); ?></option><?php endforeach; ?></select></div>
                <div class="form-group"><label class="form-label">Location</label><select name="menu_location" class="form-select"><option value="header">Header</option><option value="footer" <?php echo ($editItem['menu_location'] ?? '') === 'footer' ? 'selected' : ''; ?>>Footer</option><option value="sidebar" <?php echo ($editItem['menu_location'] ?? '') === 'sidebar' ? 'selected' : ''; ?>>Sidebar</option><option value="mobile" <?php echo ($editItem['menu_location'] ?? '') === 'mobile' ? 'selected' : ''; ?>>Mobile</option></select></div>
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
            <thead><tr><th>Title</th><th>URL</th><th>Location</th><th>Parent</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td><?php echo e($item['title']); ?></td><td style="font-family:monospace;font-size:12px;color:var(--muted);max-width:150px;overflow:hidden;text-overflow:ellipsis;"><?php echo e($item['url']); ?></td><td><?php echo e($item['menu_location']); ?></td><td><?php echo e($item['parent_title'] ?? '-'); ?></td><td><?php echo e($item['sort_order']); ?></td><td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Active' : 'Inactive'; ?></span></td><td><a href="menus.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a> <a href="menus.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
