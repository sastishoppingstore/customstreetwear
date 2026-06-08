<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);
$pageTitle = 'Enquiries';

if ($action === 'update-status' && $id && $_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    dbExecute("UPDATE enquiries SET status = ?, admin_notes = ? WHERE id = ?", [$_POST['status'], trim($_POST['admin_notes'] ?? ''), $id]);
    setFlash('success', 'Status updated.');
    redirect('enquiries.php');
}

if ($action === 'delete' && $id) {
    requireCsrf();
    dbExecute("DELETE FROM enquiries WHERE id = ?", [$id]);
    setFlash('success', 'Deleted.');
    redirect('enquiries.php');
}

$viewItem = null;
if ($action === 'view' && $id) {
    $viewItem = dbFetchOne("SELECT * FROM enquiries WHERE id = ?", [$id]);
}

$page_num = max(1, intval($_GET['page'] ?? 1));
$per_page = 20;
$offset = ($page_num - 1) * $per_page;
$filter_status = $_GET['status'] ?? '';

$where = "1=1"; $params = [];
if ($filter_status) { $where .= " AND status = ?"; $params[] = $filter_status; }
$total = dbFetchOne("SELECT COUNT(*) as c FROM enquiries WHERE $where", $params)['c'];
$items = dbFetchAll("SELECT * FROM enquiries WHERE $where ORDER BY created_at DESC LIMIT ? OFFSET ?", array_merge($params, [$per_page, $offset]));

include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Quote Enquiries</h1></div>
    <?php echo showFlash(); ?>
    
    <?php if ($action === 'view' && $viewItem): ?>
    <div class="admin-card">
        <div class="admin-card-header"><h3 class="admin-card-title">Enquiry #<?php echo $viewItem['id']; ?></h3><a href="enquiries.php" class="btn btn-sm btn-outline">Back</a></div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:30px;">
            <div><p><strong>Name:</strong> <?php echo e($viewItem['name']); ?></p><p><strong>Email:</strong> <?php echo e($viewItem['email']); ?></p><p><strong>Phone:</strong> <?php echo e($viewItem['phone']); ?></p><p><strong>WhatsApp:</strong> <?php echo e($viewItem['whatsapp']); ?></p></div>
            <div><p><strong>Country:</strong> <?php echo e($viewItem['country']); ?></p><p><strong>Company:</strong> <?php echo e($viewItem['company']); ?></p><p><strong>Product:</strong> <?php echo e($viewItem['product_interest']); ?></p><p><strong>Quantity:</strong> <?php echo e($viewItem['quantity']); ?></p></div>
        </div>
        <p><strong>Message:</strong></p><p style="background:var(--bg-alt);padding:16px;border-radius:8px;margin:10px 0 30px;"><?php echo nl2br(e($viewItem['message'])); ?></p>
        <p><strong>Status:</strong> <span class="badge badge-<?php echo strtolower($viewItem['status']); ?>"><?php echo e($viewItem['status']); ?></span></p>
        <p style="margin-top:10px;"><strong>Date:</strong> <?php echo formatDate($viewItem['created_at'], 'M d, Y H:i'); ?></p>
        
        <form method="POST" action="enquiries.php?action=update-status&id=<?php echo $id; ?>" style="margin-top:30px;padding-top:30px;border-top:1px solid var(--border);">
            <?php echo csrfField(); ?>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Update Status</label>
                    <select name="status" class="form-select">
                        <option value="New" <?php echo $viewItem['status']==='New'?'selected':''; ?>>New</option>
                        <option value="Contacted" <?php echo $viewItem['status']==='Contacted'?'selected':''; ?>>Contacted</option>
                        <option value="Quoted" <?php echo $viewItem['status']==='Quoted'?'selected':''; ?>>Quoted</option>
                        <option value="Completed" <?php echo $viewItem['status']==='Completed'?'selected':''; ?>>Completed</option>
                        <option value="Rejected" <?php echo $viewItem['status']==='Rejected'?'selected':''; ?>>Rejected</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Admin Notes</label><input type="text" name="admin_notes" class="form-input" value="<?php echo e($viewItem['admin_notes'] ?? ''); ?>"></div>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
        </form>
    </div>
    <?php else: ?>
    <div class="filter-bar">
        <form method="GET" class="filter-bar" style="flex:1;margin:0;">
            <div class="form-group"><select name="status" class="form-select" onchange="this.form.submit()"><option value="">All Status</option><option value="New" <?php echo $filter_status==='New'?'selected':''; ?>>New</option><option value="Contacted" <?php echo $filter_status==='Contacted'?'selected':''; ?>>Contacted</option><option value="Quoted" <?php echo $filter_status==='Quoted'?'selected':''; ?>>Quoted</option><option value="Completed" <?php echo $filter_status==='Completed'?'selected':''; ?>>Completed</option><option value="Rejected" <?php echo $filter_status==='Rejected'?'selected':''; ?>>Rejected</option></select></div>
            <?php if ($filter_status): ?><a href="enquiries.php" class="btn btn-outline btn-sm">Clear</a><?php endif; ?>
        </form>
    </div>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Product</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr><td>#<?php echo $item['id']; ?></td><td><?php echo e($item['name']); ?></td><td><?php echo e($item['email']); ?></td><td><?php echo e(truncate($item['product_interest'], 25)); ?></td><td><span class="badge badge-<?php echo strtolower($item['status']); ?>"><?php echo e($item['status']); ?></span></td><td><?php echo formatDate($item['created_at']); ?></td><td><a href="enquiries.php?action=view&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">View</a> <a href="enquiries.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php echo pagination($total, $per_page, $page_num, 'enquiries.php?page={page}&status=' . $filter_status); ?>
    </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
