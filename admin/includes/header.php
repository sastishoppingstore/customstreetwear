<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

$admin = isset($_SESSION['admin_id']) ? dbFetchOne("SELECT * FROM admins WHERE id = ?", [$_SESSION['admin_id']]) : null;
$siteName = getSetting('site_name', 'Custom Streetwear');
$siteLogoImage = getSetting('site_logo_image', '/uploads/settings/logo.png');
$unreadEnquiries = dbFetchOne("SELECT COUNT(*) as c FROM enquiries WHERE is_read = 0")['c'] ?? 0;
$unreadMessages = dbFetchOne("SELECT COUNT(*) as c FROM contact_messages WHERE is_read = 0")['c'] ?? 0;
$unreadOrders = dbFetchOne("SELECT COUNT(*) as c FROM orders WHERE order_status = 'pending'")['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle ?? 'Dashboard'); ?> - <?php echo e($siteName); ?> Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0a0a;
            --bg-card: #111111;
            --bg-card-hover: #1a1a1a;
            --border: #2a2a2a;
            --border-light: #333;
            --text: #ffffff;
            --text-secondary: #cccccc;
            --muted: #888888;
            --accent: #39ff14;
            --accent-dark: #2dd410;
            --accent-glow: rgba(57,255,20,0.1);
            --danger: #ff4444;
            --warning: #ffaa00;
            --success: #39ff14;
            --radius-sm: 6px;
            --radius-md: 12px;
            --font: 'Inter', -apple-system, sans-serif;
            --sidebar-width: 240px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font); background: var(--bg); color: var(--text); line-height: 1.5; overflow-x: hidden; }
        a { color: var(--text); text-decoration: none; transition: all 0.2s; }
        a:hover { color: var(--accent); }

        /* Admin Layout */
        .admin-layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--bg-card);
            border-right: 1px solid var(--border);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            text-align: center;
        }
        .sidebar-logo {
            font-family: 'Oswald', sans-serif;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--text);
        }
        .sidebar-logo-image {
            display: block;
            width: auto;
            height: 44px;
            max-width: 160px;
            object-fit: contain;
            margin: 0 auto;
        }
        .sidebar-logo span { color: var(--accent); }
        .sidebar-version { font-size: 10px; color: var(--muted); margin-top: 2px; }

        .sidebar-nav { padding: 12px 0; flex: 1; }
        .nav-section { padding: 8px 20px 4px; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); font-weight: 600; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 20px; font-size: 13px; color: var(--text-secondary); transition: all 0.2s; position: relative; }
        .nav-item:hover { background: var(--bg-card-hover); color: var(--text); }
        .nav-item.active { background: rgba(57,255,20,0.08); color: var(--accent); border-right: 2px solid var(--accent); }
        .nav-item .badge { margin-left: auto; background: var(--danger); color: #fff; font-size: 10px; padding: 1px 6px; border-radius: 10px; font-weight: 600; }
        .nav-item svg { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.7; }
        .nav-item:hover svg { opacity: 1; }

        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Bar */
        .admin-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }
        .topbar-search { padding: 6px 12px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); font-size: 13px; width: 200px; }
        .topbar-user { display: flex; align-items: center; gap: 8px; font-size: 13px; }
        .topbar-avatar { width: 32px; height: 32px; border-radius: 50%; background: var(--accent); color: #000; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; }

        /* Content */
        .admin-content { padding: 24px; flex: 1; }
        .content-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
        .content-title { font-size: 24px; font-weight: 700; }

        /* Cards */
        .admin-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 20px; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: var(--radius-sm); font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; border: none; font-family: inherit; text-decoration: none; }
        .btn-primary { background: var(--accent); color: #000; }
        .btn-primary:hover { background: var(--accent-dark); color: #000; box-shadow: 0 0 20px var(--accent-glow); }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-sm { padding: 5px 12px; font-size: 12px; }
        .btn-lg { padding: 12px 24px; font-size: 16px; }
        .btn-block { width: 100%; justify-content: center; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .btn svg { width: 16px; height: 16px; }

        /* Forms */
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px; color: var(--text); }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 8px 12px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-sm);
            color: var(--text); font-size: 14px; font-family: inherit; transition: border-color 0.2s;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: var(--accent); }
        .form-textarea { resize: vertical; min-height: 100px; }
        .form-select { cursor: pointer; }
        .form-error { color: var(--danger); font-size: 12px; margin-top: 2px; }
        .form-hint { color: var(--muted); font-size: 11px; margin-top: 2px; }

        /* Tables */
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th { padding: 10px 12px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--muted); border-bottom: 2px solid var(--border); }
        .admin-table td { padding: 10px 12px; border-bottom: 1px solid var(--border); font-size: 14px; }
        .admin-table tr:hover td { background: var(--bg-card-hover); }
        .admin-table .actions { display: flex; gap: 6px; }

        /* Badges */
        .badge { display: inline-flex; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; }
        .badge-new { background: rgba(57,255,20,0.15); color: var(--accent); }
        .badge-warning { background: rgba(255,170,0,0.15); color: var(--warning); }
        .badge-rejected { background: rgba(255,68,68,0.15); color: var(--danger); }
        .badge-info { background: rgba(0,204,255,0.15); color: var(--info); }

        /* Alerts */
        .alert { padding: 12px 16px; border-radius: var(--radius-sm); margin-bottom: 16px; font-size: 14px; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: rgba(57,255,20,0.1); border: 1px solid rgba(57,255,20,0.2); color: var(--accent); }
        .alert-error { background: rgba(255,68,68,0.1); border: 1px solid rgba(255,68,68,0.2); color: var(--danger); }
        .alert-warning { background: rgba(255,170,0,0.1); border: 1px solid rgba(255,170,0,0.2); color: var(--warning); }
        .alert-info { background: rgba(0,204,255,0.1); border: 1px solid rgba(0,204,255,0.2); color: var(--info); }

        /* Pagination */
        .pagination { display: flex; justify-content: center; gap: 4px; margin-top: 20px; }
        .pagination a, .pagination span { padding: 6px 12px; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 13px; }
        .pagination .current { background: var(--accent); color: #000; border-color: var(--accent); }
        .pagination a:hover { border-color: var(--accent); }

        /* Grid helpers */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .grid-4 { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }

        /* Modal */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 200; display: none; }
        .modal-overlay.active { display: block; }
        .modal { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 24px; z-index: 201; max-width: 600px; width: 90%; max-height: 80vh; overflow-y: auto; display: none; }
        .modal.active { display: block; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .modal-close { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; cursor: pointer; }
        .modal-close:hover { background: var(--bg-card-hover); }

        /* Utilities */
        .text-center { text-align: center; }
        .text-muted { color: var(--muted); }
        .text-accent { color: var(--accent); }
        .flex { display: flex; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; }
        .gap-8 { gap: 8px; }
        .gap-16 { gap: 16px; }
        .mt-16 { margin-top: 16px; }
        .mt-24 { margin-top: 24px; }
        .mb-16 { margin-bottom: 16px; }
        .mb-24 { margin-bottom: 24px; }
        .hidden { display: none !important; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--border-light); }

        /* Stats Cards */
        .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 20px; }
        .stat-card .stat-value { font-size: 28px; font-weight: 700; color: var(--accent); }
        .stat-card .stat-label { font-size: 13px; color: var(--muted); margin-top: 4px; }
        .stat-card .stat-trend { font-size: 12px; margin-top: 8px; }
        .stat-card .stat-trend.up { color: var(--accent); }
        .stat-card .stat-trend.down { color: var(--danger); }

        @media (max-width: 768px) {
            .admin-sidebar { width: 60px; }
            .admin-sidebar .sidebar-logo, .admin-sidebar .sidebar-version, .admin-sidebar .nav-section, .admin-sidebar .nav-item span { display: none; }
            .admin-sidebar .nav-item { justify-content: center; padding: 12px; }
            .admin-main { margin-left: 60px; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
            .topbar-search { width: 120px; }
        }
        .table-wrap { overflow-x: auto; }
        .ck-editor__editable { color: #000 !important; background: #fff !important; }
        input[type="file"] { color: var(--text); font-size: 13px; }
        .sidebar-footer { padding: 12px 20px; border-top: 1px solid var(--border); }
    </style>
</head>
<body>
<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <a href="dashboard.php" class="sidebar-logo">
                <?php if ($siteLogoImage): ?>
                <img src="<?php echo e($siteLogoImage); ?>" alt="<?php echo e($siteName); ?>" class="sidebar-logo-image">
                <?php else: ?>
                CUSTOM<span>.</span>SW
                <?php endif; ?>
            </a>
            <div class="sidebar-version">v2.0 - Admin Panel</div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">Main</div>
            <a href="dashboard.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                <span>Dashboard</span>
            </a>
            <div class="nav-section">Content</div>
            <a href="pages.php" class="nav-item <?php echo strpos(basename($_SERVER['PHP_SELF']), 'pages.php') === 0 ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span>Pages</span>
            </a>
            <a href="sliders.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'sliders.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                <span>Sliders</span>
            </a>
            <a href="home-sections.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'home-sections.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                <span>Home Sections</span>
            </a>
            <a href="media-manager.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'media-manager.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                <span>Media Manager</span>
            </a>
            <a href="categories.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h6v6H4z"/><path d="M14 4h6v6h-6z"/><path d="M4 14h6v6H4z"/><path d="M14 14h6v6h-6z"/></svg>
                <span>Categories</span>
            </a>
            <a href="subcategories.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'subcategories.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/></svg>
                <span>Subcategories</span>
            </a>
            <a href="products.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'products.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 002 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
                <span>Products</span>
            </a>
            <div class="nav-section">Management</div>
            <a href="enquiries.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'enquiries.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                <span>Enquiries</span>
                <?php if ($unreadEnquiries > 0): ?><span class="badge"><?php echo $unreadEnquiries; ?></span><?php endif; ?>
            </a>
            <a href="contact-messages.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'contact-messages.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <span>Messages</span>
                <?php if ($unreadMessages > 0): ?><span class="badge"><?php echo $unreadMessages; ?></span><?php endif; ?>
            </a>
            <a href="orders.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'orders.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                <span>Orders</span>
                <?php if ($unreadOrders > 0): ?><span class="badge"><?php echo $unreadOrders; ?></span><?php endif; ?>
            </a>
            <a href="testimonials.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'testimonials.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                <span>Testimonials</span>
            </a>
            <a href="site-reviews.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'site-reviews.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <span>Site Reviews</span>
            </a>
            <a href="faqs.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'faqs.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                <span>FAQs</span>
            </a>
            <a href="faq-categories.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'faq-categories.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/></svg>
                <span>FAQ Categories</span>
            </a>
            <div class="nav-section">SEO & Settings</div>
            <a href="seo-manager-v2.php" class="nav-item <?php echo strpos(basename($_SERVER['PHP_SELF']), 'seo-manager') === 0 ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <span>SEO Manager</span>
            </a>
            <a href="location-seo.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'location-seo.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Location SEO</span>
            </a>
            <a href="admins.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'admins.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                <span>Admin Users</span>
            </a>
            <a href="settings.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                <span>Settings</span>
            </a>
            <a href="menus.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'menus.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                <span>Menus</span>
            </a>
            <div class="nav-section">Tools</div>
            <a href="run-migration.php" class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'run-migration.php' ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4v16h16"/><polyline points="20 10 12 18 8 14"/></svg>
                <span>Run Migration</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="../" class="nav-item" target="_blank" style="font-size:12px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                <span>View Site</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="admin-main">
        <div class="admin-topbar">
            <div class="topbar-left">
                <span style="font-size:14px;font-weight:600;"><?php echo e($pageTitle ?? 'Dashboard'); ?></span>
            </div>
            <div class="topbar-right">
                <span class="topbar-user">
                    <span style="font-size:13px;"><?php echo e($admin['name'] ?? 'Admin'); ?></span>
                    <span class="topbar-avatar"><?php echo e(strtoupper(substr($admin['name'] ?? 'A', 0, 1))); ?></span>
                </span>
            <a href="profile.php" class="btn btn-outline btn-sm">Profile</a>
            <a href="admins.php" class="btn btn-outline btn-sm">Admins</a>
            <a href="logout.php" class="btn btn-outline btn-sm" style="color:var(--danger);">Logout</a>
            </div>
        </div>
        <!-- Content injected here -->
