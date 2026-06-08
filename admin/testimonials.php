<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$action = $_GET['action'] ?? 'list'; $id = intval($_GET['id'] ?? 0); $pageTitle = 'Testimonials';
if ($action === 'delete' && $id) { requireCsrf(); dbExecute("DELETE FROM testimonials WHERE id = ?", [$id]); setFlash('success', 'Deleted.'); redirect('testimonials.php'); }
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf(); $data = ['client_name' => trim($_POST['client_name']), 'country' => trim($_POST['country']), 'company' => trim($_POST['company']), 'message' => trim($_POST['message']), 'rating' => intval($_POST['rating'] ?? 5), 'sort_order' => intval($_POST['sort_order'] ?? 0), 'status' => intval($_POST['status'] ?? 1)];
    if ($id) { $fields = []; $vals = []; foreach ($data as $k => $v) { $fields[] = "$k = ?"; $vals[] = $v; } $vals[] = $id; dbExecute("UPDATE testimonials SET " . implode(', ', $fields) . " WHERE id = ?", $vals); setFlash('success', 'Updated.'); }
    else { $cols = implode(', ', array_keys($data)); $ph = implode(', ', array_fill(0, count($data), '?')); dbInsert("INSERT INTO testimonials ($cols) VALUES ($ph)", array_values($data)); setFlash('success', 'Created.'); }
    redirect('testimonials.php');
}
$editItem = $id && $action === 'edit' ? dbFetchOne("SELECT * FROM testimonials WHERE id = ?", [$id]) : null;
$items = dbFetchAll("SELECT * FROM testimonials ORDER BY sort_order");
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Testimonials</h1><div class="content-actions"><a href="testimonials.php" class="btn btn-outline btn-sm">List</a><a href="testimonials.php?action=add" class="btn btn-primary btn-sm">+ Add</a></div></div>
    <?php echo showFlash(); ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <form method="POST" action="testimonials.php?action=save<?php echo $id ? '&id=' . $id : ''; ?>">
            <?php echo csrfField(); ?>
            <div class="form-row"><div class="form-group"><label class="form-label">Client Name *</label><input type="text" name="client_name" class="form-input" required value="<?php echo e($editItem['client_name'] ?? ''); ?>"></div><div class="form-group"><label class="form-label">Company</label><input type="text" name="company" class="form-input" value="<?php echo e($editItem['company'] ?? ''); ?>"></div></div>
            <div class="form-row"><div class="form-group"><label class="form-label">Country</label><input type="text" name="country" class="form-input" value="<?php echo e($editItem['country'] ?? ''); ?>"></div><div class="form-group"><label class="form-label">Rating (1-5)</label><input type="number" name="rating" class="form-input" min="1" max="5" value="<?php echo e($editItem['rating'] ?? 5); ?>"></div></div>
            <div class="form-group"><label class="form-label">Message *</label><textarea name="message" class="form-textarea" rows="4" required><?php echo e($editItem['message'] ?? ''); ?></textarea></div>
            <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Active</option><option value="0" <?php echo ($editItem['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactive</option></select></div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <?php else: ?>
    <div class="admin-card">
        <table class="admin-table"><thead><tr><th>Name</th><th>Company</th><th>Country</th><th>Rating</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td><?php echo e($item['client_name']); ?></td><td><?php echo e($item['company']); ?></td><td><?php echo e($item['country']); ?></td><td><?php echo str_repeat('★', $item['rating']); ?></td><td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Active' : 'Inactive'; ?></span></td><td><a href="testimonials.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a> <a href="testimonials.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
