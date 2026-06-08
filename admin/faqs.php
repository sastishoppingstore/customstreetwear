<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$action = $_GET['action'] ?? 'list'; $id = intval($_GET['id'] ?? 0); $pageTitle = 'FAQs';
if ($action === 'delete' && $id) { requireCsrf(); dbExecute("DELETE FROM faqs WHERE id = ?", [$id]); setFlash('success', 'FAQ deleted.'); redirect('faqs.php'); }
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $data = ['category' => trim($_POST['category']), 'question' => trim($_POST['question']), 'answer' => trim($_POST['answer']), 'status' => intval($_POST['status'] ?? 1), 'sort_order' => intval($_POST['sort_order'] ?? 0)];
    if ($id) { $fs = []; $vs = []; foreach ($data as $k => $v) { $fs[] = "$k = ?"; $vs[] = $v; } $vs[] = $id; dbExecute("UPDATE faqs SET " . implode(', ', $fs) . " WHERE id = ?", $vs); setFlash('success', 'FAQ updated.'); }
    else { $cols = implode(', ', array_keys($data)); $ph = implode(', ', array_fill(0, count($data), '?')); dbInsert("INSERT INTO faqs ($cols) VALUES ($ph)", array_values($data)); setFlash('success', 'FAQ created.'); }
    redirect('faqs.php');
}
$editItem = $id && $action === 'edit' ? dbFetchOne("SELECT * FROM faqs WHERE id = ?", [$id]) : null;
$items = dbFetchAll("SELECT * FROM faqs ORDER BY sort_order, category, id");
$categories = dbFetchAll("SELECT DISTINCT category FROM faqs ORDER BY category");
$filterCat = $_GET['category'] ?? '';
if ($filterCat) $items = dbFetchAll("SELECT * FROM faqs WHERE category = ? ORDER BY sort_order, id", [$filterCat]);
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">FAQ Manager</h1><div class="content-actions"><a href="faqs.php" class="btn btn-outline btn-sm">List</a><a href="faqs.php?action=add" class="btn btn-primary btn-sm">+ Add FAQ</a></div></div>
    <?php echo showFlash(); ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <form method="POST" action="faqs.php?action=save<?php echo $id ? '&id='.$id : ''; ?>">
            <?php echo csrfField(); ?>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="General" <?php echo ($editItem['category'] ?? '') === 'General' ? 'selected' : ''; ?>>General</option>
                        <option value="Orders" <?php echo ($editItem['category'] ?? '') === 'Orders' ? 'selected' : ''; ?>>Orders</option>
                        <option value="Pricing" <?php echo ($editItem['category'] ?? '') === 'Pricing' ? 'selected' : ''; ?>>Pricing</option>
                        <option value="Customization" <?php echo ($editItem['category'] ?? '') === 'Customization' ? 'selected' : ''; ?>>Customization</option>
                        <option value="Shipping" <?php echo ($editItem['category'] ?? '') === 'Shipping' ? 'selected' : ''; ?>>Shipping</option>
                        <option value="Returns" <?php echo ($editItem['category'] ?? '') === 'Returns' ? 'selected' : ''; ?>>Returns</option>
                        <option value="Products" <?php echo ($editItem['category'] ?? '') === 'Products' ? 'selected' : ''; ?>>Products</option>
                        <option value="Quality" <?php echo ($editItem['category'] ?? '') === 'Quality' ? 'selected' : ''; ?>>Quality</option>
                        <option value="Company" <?php echo ($editItem['category'] ?? '') === 'Company' ? 'selected' : ''; ?>>Company</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-input" value="<?php echo e($editItem['sort_order'] ?? 0); ?>"></div>
            </div>
            <div class="form-group"><label class="form-label">Question *</label><input type="text" name="question" class="form-input" required value="<?php echo e($editItem['question'] ?? ''); ?>"></div>
            <div class="form-group"><label class="form-label">Answer *</label><textarea name="answer" class="form-textarea" rows="8"><?php echo e($editItem['answer'] ?? ''); ?></textarea></div>
            <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Active</option><option value="0" <?php echo ($editItem['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactive</option></select></div>
            <button type="submit" class="btn btn-primary">Save FAQ</button>
        </form>
    </div>
    <?php else: ?>
    <div class="filter-bar" style="margin-bottom:16px;">
        <form method="GET" action="faqs.php" style="display:flex;gap:8px;">
            <select name="category" class="form-select" style="width:200px;" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?php echo e($c['category']); ?>" <?php echo $filterCat === $c['category'] ? 'selected' : ''; ?>><?php echo e($c['category']); ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($filterCat): ?><a href="faqs.php" class="btn btn-outline btn-sm">Clear</a><?php endif; ?>
        </form>
    </div>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Question</th><th>Category</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td style="max-width:350px;overflow:hidden;text-overflow:ellipsis;"><?php echo e(truncate($item['question'], 80)); ?></td><td><span class="badge badge-new"><?php echo e($item['category']); ?></span></td><td><?php echo e($item['sort_order']); ?></td><td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Active' : 'Inactive'; ?></span></td><td><a href="faqs.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a> <a href="faqs.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
