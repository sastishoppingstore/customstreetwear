<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$action = $_GET['action'] ?? 'list'; $id = intval($_GET['id'] ?? 0); $pageTitle = 'Blogs';
if ($action === 'delete' && $id) { requireCsrf(); dbExecute("DELETE FROM blogs WHERE id = ?", [$id]); setFlash('success', 'Blog deleted.'); redirect('blogs.php'); }
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf(); $data = ['title' => trim($_POST['title']), 'slug' => generateUniqueSlug('blogs', $_POST['title'], $id), 'category' => trim($_POST['category']), 'tags' => trim($_POST['tags']), 'short_description' => trim($_POST['short_description']), 'content' => trim($_POST['content']), 'seo_title' => trim($_POST['seo_title']), 'seo_description' => trim($_POST['seo_description']), 'status' => intval($_POST['status'] ?? 1), 'published_at' => $_POST['published_at'] ?: date('Y-m-d H:i:s')];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) { $r = uploadFile($_FILES['image'], 'blogs'); if ($r['success']) $data['image'] = $r['path']; }
    if ($id) { $fields = []; $vals = []; foreach ($data as $k => $v) { $fields[] = "$k = ?"; $vals[] = $v; } $vals[] = $id; dbExecute("UPDATE blogs SET " . implode(', ', $fields) . " WHERE id = ?", $vals); setFlash('success', 'Blog updated.'); }
    else { $cols = implode(', ', array_keys($data)); $ph = implode(', ', array_fill(0, count($data), '?')); dbInsert("INSERT INTO blogs ($cols) VALUES ($ph)", array_values($data)); setFlash('success', 'Blog created.'); }
    redirect('blogs.php');
}
$editItem = $id && $action === 'edit' ? dbFetchOne("SELECT * FROM blogs WHERE id = ?", [$id]) : null;
$items = dbFetchAll("SELECT * FROM blogs ORDER BY published_at DESC");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Blogs</h1><div class="content-actions"><a href="blogs.php" class="btn btn-outline btn-sm">List</a><a href="blogs.php?action=add" class="btn btn-primary btn-sm">+ Add</a></div></div>
    <?php echo showFlash(); ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <form method="POST" action="blogs.php?action=save<?php echo $id ? '&id=' . $id : ''; ?>" enctype="multipart/form-data">
            <?php echo csrfField(); ?>
            <div class="form-row"><div class="form-group"><label class="form-label">Title *</label><input type="text" name="title" class="form-input" required value="<?php echo e($editItem['title'] ?? ''); ?>"></div><div class="form-group"><label class="form-label">Category</label><input type="text" name="category" class="form-input" value="<?php echo e($editItem['category'] ?? ''); ?>"></div></div>
            <div class="form-group"><label class="form-label">Short Description</label><textarea name="short_description" class="form-textarea" rows="3"><?php echo e($editItem['short_description'] ?? ''); ?></textarea></div>
            <div class="form-group"><label class="form-label">Content (HTML)</label><textarea name="content" class="form-textarea" rows="12"><?php echo e($editItem['content'] ?? ''); ?></textarea></div>
            <div class="form-row"><div class="form-group"><label class="form-label">Image</label><input type="file" name="image" class="form-input" accept="image/*"></div><div class="form-group"><label class="form-label">Tags (comma separated)</label><input type="text" name="tags" class="form-input" value="<?php echo e($editItem['tags'] ?? ''); ?>"></div></div>
            <div class="form-row"><div class="form-group"><label class="form-label">SEO Title</label><input type="text" name="seo_title" class="form-input" value="<?php echo e($editItem['seo_title'] ?? ''); ?>"></div><div class="form-group"><label class="form-label">SEO Description</label><input type="text" name="seo_description" class="form-input" value="<?php echo e($editItem['seo_description'] ?? ''); ?>"></div></div>
            <div class="form-row"><div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Published</option><option value="0" <?php echo ($editItem['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Draft</option></select></div><div class="form-group"><label class="form-label">Published Date</label><input type="datetime-local" name="published_at" class="form-input" value="<?php echo $editItem ? date('Y-m-d\TH:i', strtotime($editItem['published_at'])) : ''; ?>"></div></div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <?php else: ?>
    <div class="admin-card">
        <table class="admin-table"><thead><tr><th>Image</th><th>Title</th><th>Category</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td><?php if ($item['image']): ?><img src="<?php echo e($item['image']); ?>" alt=""><?php else: ?>-<?php endif; ?></td><td><?php echo e(truncate($item['title'], 40)); ?></td><td><?php echo e($item['category'] ?: '-'); ?></td><td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Published' : 'Draft'; ?></span></td><td><?php echo formatDate($item['published_at'] ?: $item['created_at']); ?></td><td><a href="blogs.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a> <a href="blogs.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
