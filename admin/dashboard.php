<?php
/**
 * Custom Streetwear - Admin Dashboard
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

// Stats
$stats = [
    'products' => dbCount('products', 'status = 1'),
    'categories' => dbCount('categories', 'status = 1'),
    'enquiries' => dbCount('enquiries'),
    'new_enquiries' => dbCount('enquiries', "status = 'New'"),
    'contact_messages' => dbCount('contact_messages'),
    'blogs' => dbCount('blogs', 'status = 1'),
    'countries' => dbCount('countries', 'status = 1'),
    'testimonials' => dbCount('testimonials', 'status = 1'),
];

$recentEnquiries = dbFetchAll("SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 5");
$recentContacts = dbFetchAll("SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");

$pageTitle = 'Dashboard';
include __DIR__ . '/includes/header.php';
?>

<div class="admin-content">
    <div class="content-header">
        <h1 class="content-title">Dashboard</h1>
    </div>
    
    <!-- Stats Grid -->
    <div class="stats-grid-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(57,255,20,0.1); color: var(--accent);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $stats['products']; ?></div>
                <div class="stat-label">Products</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(0,204,255,0.1); color: #00ccff;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $stats['categories']; ?></div>
                <div class="stat-label">Categories</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(255,170,0,0.1); color: #ffaa00;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $stats['new_enquiries']; ?></div>
                <div class="stat-label">New Enquiries</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: rgba(255,68,68,0.1); color: #ff4444;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <div class="stat-info">
                <div class="stat-value"><?php echo $stats['contact_messages']; ?></div>
                <div class="stat-label">Messages</div>
            </div>
        </div>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-top: 30px;">
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Recent Enquiries</h3>
                <a href="enquiries.php" class="btn btn-sm btn-outline">View All</a>
            </div>
            <table class="admin-table">
                <thead><tr><th>Name</th><th>Product</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($recentEnquiries as $e): ?>
                    <tr>
                        <td><?php echo e($e['name']); ?></td>
                        <td><?php echo e(truncate($e['product_interest'], 25)); ?></td>
                        <td><span class="badge badge-<?php echo strtolower($e['status']); ?>"><?php echo e($e['status']); ?></span></td>
                        <td><?php echo formatDate($e['created_at']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Recent Messages</h3>
                <a href="contact-messages.php" class="btn btn-sm btn-outline">View All</a>
            </div>
            <table class="admin-table">
                <thead><tr><th>Name</th><th>Subject</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    <?php foreach ($recentContacts as $c): ?>
                    <tr>
                        <td><?php echo e($c['name']); ?></td>
                        <td><?php echo e(truncate($c['subject'] ?: 'No Subject', 25)); ?></td>
                        <td><span class="badge badge-new"><?php echo e($c['status']); ?></span></td>
                        <td><?php echo formatDate($c['created_at']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Quick Links -->
    <div class="admin-card" style="margin-top: 24px;">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Quick Management</h3>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
            <a href="products.php" class="quick-link"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg> Products</a>
            <a href="categories.php" class="quick-link"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg> Categories</a>
            <a href="enquiries.php" class="quick-link"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg> Enquiries</a>
            <a href="blogs.php" class="quick-link"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Blogs</a>
            <a href="countries.php" class="quick-link"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg> Countries</a>
            <a href="settings.php" class="quick-link"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg> Settings</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
