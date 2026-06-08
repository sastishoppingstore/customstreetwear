<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();
$pageTitle = 'Orders';
$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);

if ($action === 'view' && $id) {
    $order = dbFetchOne("SELECT * FROM orders WHERE id = ?", [$id]);
    if (!$order) { setFlash('error', 'Order not found.'); redirect('orders.php'); }
    include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Order #<?php echo e($order['order_number']); ?></h1><div class="content-actions"><a href="orders.php" class="btn btn-outline btn-sm">← Back</a></div></div>
    <?php echo showFlash(); ?>
    <div class="admin-card" style="margin-bottom:20px;">
        <form method="POST" action="orders.php?action=update&id=<?php echo $id; ?>">
            <?php echo csrfField(); ?>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Order Status</label>
                    <select name="order_status" class="form-select">
                        <?php foreach (['pending','confirmed','processing','shipped','delivered','cancelled'] as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo $order['order_status'] === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        <?php foreach (['pending','paid','verified','failed'] as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo $order['payment_status'] === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group"><label class="form-label">Admin Notes</label><textarea name="admin_notes" class="form-textarea" rows="3"><?php echo e($order['admin_notes'] ?? ''); ?></textarea></div>
            <button type="submit" class="btn btn-primary">Update Order</button>
        </form>
    </div>
    <div class="admin-card">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;">
            <div><h4 style="margin-bottom:10px;">Contact Info</h4><p><strong>Name:</strong> <?php echo e($order['name']); ?></p><p><strong>Email:</strong> <?php echo e($order['email']); ?></p><p><strong>Phone:</strong> <?php echo e($order['phone']); ?></p><p><strong>Company:</strong> <?php echo e($order['company'] ?? '-'); ?></p></div>
            <div><h4 style="margin-bottom:10px;">Shipping</h4><p><strong>Address:</strong> <?php echo nl2br(e($order['address'] ?? '-')); ?></p><p><strong>City:</strong> <?php echo e($order['city'] ?? '-'); ?></p><p><strong>State:</strong> <?php echo e($order['state'] ?? '-'); ?></p><p><strong>ZIP:</strong> <?php echo e($order['zip'] ?? '-'); ?></p></div>
            <div><h4 style="margin-bottom:10px;">Order Details</h4><p><strong>Order #:</strong> <?php echo e($order['order_number']); ?></p><p><strong>Products:</strong> <?php echo nl2br(e($order['product_interest'] ?? '-')); ?></p><p><strong>Quantity:</strong> <?php echo e($order['quantity'] ?? '-'); ?></p><p><strong>Custom Details:</strong> <?php echo nl2br(e($order['custom_details'] ?? '-')); ?></p></div>
            <div><h4 style="margin-bottom:10px;">Payment</h4><p><strong>Method:</strong> <?php echo e(str_replace('_', ' ', ucfirst($order['payment_method'] ?? '-'))); ?></p><p><strong>Delivery Charge:</strong> $<?php echo number_format($order['delivery_charge'] ?? 0, 2); ?></p><p><strong>Payment Status:</strong> <span class="badge badge-<?php echo $order['payment_status'] === 'paid' || $order['payment_status'] === 'verified' ? 'new' : 'rejected'; ?>"><?php echo ucfirst($order['payment_status'] ?? 'pending'); ?></span></p>
                <?php if ($order['payment_proof']): ?><p><strong>Payment Proof:</strong> <a href="<?php echo e($order['payment_proof']); ?>" target="_blank" class="btn btn-sm btn-outline">View File</a></p><?php endif; ?>
            </div>
            <div><h4 style="margin-bottom:10px;">Dates</h4><p><strong>Created:</strong> <?php echo formatDate($order['created_at'], 'M d, Y h:i A'); ?></p><p><strong>Updated:</strong> <?php echo formatDate($order['updated_at'], 'M d, Y h:i A'); ?></p></div>
        </div>
    </div>
</div>
<?php
    include __DIR__ . '/includes/footer.php';
    exit;
}

if ($action === 'update' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    dbExecute("UPDATE orders SET order_status = ?, payment_status = ?, admin_notes = ? WHERE id = ?", [$_POST['order_status'], $_POST['payment_status'], trim($_POST['admin_notes'] ?? ''), $id]);
    setFlash('success', 'Order updated.');
    redirect('orders.php?action=view&id=' . $id);
}

if ($action === 'delete' && $id) {
    requireCsrf();
    $order = dbFetchOne("SELECT payment_proof FROM orders WHERE id = ?", [$id]);
    if ($order && $order['payment_proof']) deleteUpload($order['payment_proof']);
    dbExecute("DELETE FROM orders WHERE id = ?", [$id]);
    setFlash('success', 'Order deleted.');
    redirect('orders.php');
}

$filterStatus = $_GET['status'] ?? '';
$search = trim($_GET['search'] ?? '');
$where = "1=1"; $params = [];
if ($filterStatus) { $where .= " AND order_status = ?"; $params[] = $filterStatus; }
if ($search) { $where .= " AND (order_number LIKE ? OR name LIKE ? OR email LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%"; }
$items = dbFetchAll("SELECT * FROM orders WHERE $where ORDER BY id DESC", $params);
include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Orders</h1></div>
    <?php echo showFlash(); ?>
    <div class="filter-bar" style="margin-bottom:16px;">
        <form method="GET" action="orders.php" style="display:flex;gap:8px;flex-wrap:wrap;">
            <select name="status" class="form-select" style="width:160px;" onchange="this.form.submit()">
                <option value="">All Status</option>
                <?php foreach (['pending','confirmed','processing','shipped','delivered','cancelled'] as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo $filterStatus === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="search" class="form-input" style="width:200px;" placeholder="Search orders..." value="<?php echo e($search); ?>">
            <button type="submit" class="btn btn-outline btn-sm">Search</button>
            <?php if ($filterStatus || $search): ?><a href="orders.php" class="btn btn-outline btn-sm">Clear</a><?php endif; ?>
        </form>
    </div>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Order #</th><th>Name</th><th>Email</th><th>City</th><th>Status</th><th>Payment</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody><?php foreach ($items as $item): ?><tr><td style="font-family:monospace;"><?php echo e($item['order_number']); ?></td><td><?php echo e($item['name']); ?></td><td style="font-size:12px;"><?php echo e($item['email']); ?></td><td><?php echo e($item['city'] ?: '-'); ?></td><td><span class="badge badge-<?php echo $item['order_status'] === 'delivered' ? 'new' : ($item['order_status'] === 'cancelled' ? 'rejected' : 'warning'); ?>"><?php echo ucfirst($item['order_status']); ?></span></td><td><span class="badge badge-<?php echo $item['payment_status'] === 'verified' || $item['payment_status'] === 'paid' ? 'new' : 'rejected'; ?>"><?php echo ucfirst($item['payment_status']); ?></span></td><td style="font-size:12px;"><?php echo formatDate($item['created_at'], 'M d, Y'); ?></td><td><a href="orders.php?action=view&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">View</a> <a href="orders.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr><?php endforeach; ?></tbody>
        </table>
    </div>
</div>
<style>.badge-warning { background: #f59e0b; color: #000; }</style>
<?php include __DIR__ . '/includes/footer.php'; ?>
