<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$action = $_GET['action'] ?? 'list';
$id = intval($_GET['id'] ?? 0);
$pageTitle = 'Contact Messages';

if ($action === 'delete' && $id) {
    requireCsrf();
    dbExecute("DELETE FROM contact_messages WHERE id = ?", [$id]);
    setFlash('success', 'Message deleted.');
    redirect('contact-messages.php');
}

if ($action === 'mark-read' && $id) {
    dbExecute("UPDATE contact_messages SET status = 'Read' WHERE id = ?", [$id]);
    setFlash('success', 'Marked as read.');
    redirect('contact-messages.php');
}

$page_num = max(1, intval($_GET['page'] ?? 1));
$per_page = 20;
$offset = ($page_num - 1) * $per_page;
$total = dbCount('contact_messages');
$items = dbFetchAll("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT ? OFFSET ?", [$per_page, $offset]);

include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header"><h1 class="content-title">Contact Messages</h1></div>
    <?php echo showFlash(); ?>
    <div class="admin-card">
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr style="<?php echo $item['status'] === 'New' ? 'font-weight:600;' : ''; ?>">
                    <td><?php echo e($item['name']); ?></td>
                    <td><?php echo e($item['email']); ?></td>
                    <td><?php echo e(truncate($item['subject'] ?: 'No Subject', 30)); ?></td>
                    <td><span class="badge badge-<?php echo strtolower($item['status'] ?: 'new'); ?>"><?php echo e($item['status'] ?: 'New'); ?></span></td>
                    <td><?php echo formatDate($item['created_at']); ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline" onclick="alert('<?php echo e(addslashes($item['message'])); ?>')">View</button>
                        <?php if ($item['status'] === 'New'): ?>
                        <a href="contact-messages.php?action=mark-read&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline">Mark Read</a>
                        <?php endif; ?>
                        <a href="contact-messages.php?action=delete&id=<?php echo $item['id']; ?>&<?php echo CSRF_TOKEN_NAME; ?>=<?php echo csrfToken(); ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php echo pagination($total, $per_page, $page_num, 'contact-messages.php?page={page}'); ?>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
