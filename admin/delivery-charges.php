<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$action = $_GET['action'] ?? 'list'; $id = intval($_GET['id'] ?? 0); $pageTitle = 'Delivery Charges';
if ($action === 'delete' && $id) { requireCsrf(); dbExecute("DELETE FROM delivery_charges WHERE id = ?", [$id]); setFlash('success', 'Delivery charge deleted.'); redirect('delivery-charges.php'); }
if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $data = ['city' => trim($_POST['city']), 'state' => trim($_POST['state']), 'charge' => floatval($_POST['charge']), 'estimated_days' => trim($_POST['estimated_days']), 'status' => intval($_POST['status'] ?? 1)];
    if ($id) { $fs = []; $vs = []; foreach ($data as $k => $v) { $fs[] = "$k = ?"; $vs[] = $v; } $vs[] = $id; dbExecute("UPDATE delivery_charges SET " . implode(', ', $fs) . " WHERE id = ?", $vs); setFlash('success', 'Delivery charge updated.'); }
    else { $cols = implode(', ', array_keys($data)); $ph = implode(', ', array_fill(0, count($data), '?')); dbInsert("INSERT INTO delivery_charges ($cols) VALUES ($ph)", array_values($data)); setFlash('success', 'Delivery charge created.'); }
    redirect('delivery-charges.php');
}
$editItem = $id && $action === 'edit' ? dbFetchOne("SELECT * FROM delivery_charges WHERE id = ?", [$id]) : null;
$filterState = $_GET['state'] ?? '';
$items = $filterState ? dbFetchAll("SELECT * FROM delivery_charges WHERE state = ? ORDER BY city", [$filterState]) : dbFetchAll("SELECT * FROM delivery_charges ORDER BY state, city");
$usaStates = getUSAStates();
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Delivery Charges by City</h1><div class="content-actions"><a href="delivery-charges.php" class="btn btn-outline btn-sm">List</a><a href="delivery-charges.php?action=add" class="btn btn-primary btn-sm">+ Add City</a></div></div>
    <?php echo showFlash(); ?>
    <?php if ($action === 'add' || $action === 'edit'): ?>
    <div class="admin-card">
        <form method="POST" action="delivery-charges.php?action=save<?php echo $id ? '&id='.$id : ''; ?>">
            <?php echo csrfField(); ?>
            <div class="form-row">
                <div class="form-group"><label class="form-label">City *</label><input type="text" name="city" class="form-input" required value="<?php echo e($editItem['city'] ?? ''); ?>"></div>
                <div class="form-group"><label class="form-label">State *</label>
                    <select name="state" class="form-select" required>
                        <option value="">Select State</option>
                        <?php foreach ($usaStates as $slug => $st): ?>
                        <option value="<?php echo e($st['name']); ?>" <?php echo ($editItem['state'] ?? '') === $st['name'] ? 'selected' : ''; ?>><?php echo e($st['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Charge ($) *</label><input type="number" step="0.01" name="charge" class="form-input" required value="<?php echo e($editItem['charge'] ?? '0.00'); ?>"></div>
                <div class="form-group"><label class="form-label">Estimated Delivery</label><input type="text" name="estimated_days" class="form-input" value="<?php echo e($editItem['estimated_days'] ?? '5-7 business days'); ?>"></div>
            </div>
            <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1">Active</option><option value="0" <?php echo ($editItem['status'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactive</option></select></div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <?php else: ?>
    <div class="filter-bar" style="margin-bottom:16px;">
        <form method="GET" action="delivery-charges.php" style="display:flex;gap:8px;">
            <select name="state" class="form-select" style="width:200px;" onchange="this.form.submit()">
                <option value="">All States</option>
                <?php foreach ($usaStates as $slug => $st): ?>
                <option value="<?php echo e($st['name']); ?>" <?php echo $filterState === $st['name'] ? 'selected' : ''; ?>><?php echo e($st['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ($filterState): ?><a href="delivery-charges.php" class="btn btn-outline btn-sm">Clear</a><?php endif; ?>
        </form>
    </div>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>City</th><th>State</th><th>Charge ($)</th><th>Est. Delivery</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td><?php echo e($item['city']); ?></td><td><?php echo e($item['state']); ?></td><td>$<?php echo number_format($item['charge'], 2); ?></td><td><?php echo e($item['estimated_days']); ?></td><td><span class="badge badge-<?php echo $item['status'] ? 'new' : 'rejected'; ?>"><?php echo $item['status'] ? 'Active' : 'Inactive'; ?></span></td><td><a href="delivery-charges.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Edit</a> <a href="delivery-charges.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
