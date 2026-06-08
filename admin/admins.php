<?php
/**
 * Admin Users Management
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/auth-v2.php';
requireSuperAdmin();

$pageTitle = 'Admin Users';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $role = $_POST['role'] ?? 'editor';
        
        if (empty($name) || empty($email) || empty($password)) {
            setFlash('error', 'All fields required.');
        } elseif (strlen($password) < PASSWORD_MIN_LENGTH) {
            setFlash('error', 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters.');
        } elseif (dbFetchOne("SELECT id FROM admins WHERE email = ?", [$email])) {
            setFlash('error', 'Email already exists.');
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            dbExecute("INSERT INTO admins (name, email, password_hash, role) VALUES (?,?,?,?)", [$name, $email, $hash, $role]);
            logActivity('Admin Created', "New admin: {$name} ({$email})");
            setFlash('success', 'Admin user created successfully.');
        }
    } elseif ($action === 'edit') {
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $role = $_POST['role'] ?? 'editor';
        $password = $_POST['password'] ?? '';
        
        // Check email uniqueness
        $existing = dbFetchOne("SELECT id FROM admins WHERE email = ? AND id != ?", [$email, $id]);
        if ($existing) {
            setFlash('error', 'Email already in use.');
        } else {
            dbExecute("UPDATE admins SET name=?, email=?, role=? WHERE id=?", [$name, $email, $role, $id]);
            if (!empty($password) && strlen($password) >= PASSWORD_MIN_LENGTH) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                dbExecute("UPDATE admins SET password_hash=? WHERE id=?", [$hash, $id]);
            }
            setFlash('success', 'Admin updated.');
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);
        if ($id == $_SESSION['admin_id']) {
            setFlash('error', 'Cannot delete yourself.');
        } else {
            dbExecute("DELETE FROM admins WHERE id=?", [$id]);
            logActivity('Admin Deleted', "Admin #{$id} deleted");
            setFlash('success', 'Admin deleted.');
        }
    } elseif ($action === 'toggle_status') {
        $id = intval($_POST['id']);
        $admin = dbFetchOne("SELECT status FROM admins WHERE id=?", [$id]);
        if ($admin) {
            $newStatus = $admin['status'] ? 0 : 1;
            dbExecute("UPDATE admins SET status=? WHERE id=?", [$newStatus, $id]);
            setFlash('success', 'Status changed.');
        }
    } elseif ($action === 'force_logout') {
        $id = intval($_POST['id']);
        forceLogoutAdmin($id);
        setFlash('success', 'User force logged out.');
    } elseif ($action === 'setup_2fa') {
        $id = intval($_POST['id']);
        $secret = generate2FASecret();
        dbExecute("UPDATE admins SET 2fa_secret=?, 2fa_enabled=1 WHERE id=?", [$secret, $id]);
        $admin = dbFetchOne("SELECT email FROM admins WHERE id=?", [$id]);
        $qrUrl = get2FAQRCodeURL($admin['email'], $secret);
        setFlash('success', '2FA enabled. Scan QR code in authenticator app.');
        // Store QR URL in session for display
        $_SESSION['2fa_qr_url'] = $qrUrl;
        $_SESSION['2fa_secret_display'] = $secret;
    } elseif ($action === 'disable_2fa') {
        $id = intval($_POST['id']);
        dbExecute("UPDATE admins SET 2fa_secret=NULL, 2fa_enabled=0 WHERE id=?", [$id]);
        setFlash('success', '2FA disabled.');
    } elseif ($action === 'toggle_2fa') {
        $id = intval($_POST['id']);
        $admin = dbFetchOne("SELECT 2fa_enabled FROM admins WHERE id=?", [$id]);
        if ($admin) {
            if ($admin['2fa_enabled']) {
                dbExecute("UPDATE admins SET 2fa_enabled=0 WHERE id=?", [$id]);
                setFlash('success', '2FA disabled.');
            } else {
                $secret = generate2FASecret();
                $adminData = dbFetchOne("SELECT email FROM admins WHERE id=?", [$id]);
                dbExecute("UPDATE admins SET 2fa_secret=?, 2fa_enabled=1 WHERE id=?", [$secret, $id]);
                $qrUrl = get2FAQRCodeURL($adminData['email'], $secret);
                $_SESSION['2fa_qr_url'] = $qrUrl;
                $_SESSION['2fa_secret_display'] = $secret;
                setFlash('success', '2FA enabled for user.');
            }
        }
    }
    redirect('admins.php');
}

$admins = dbFetchAll("SELECT id, name, email, role, status, last_login, 2fa_enabled, created_at FROM admins ORDER BY role, name");
$loginHistory = getLoginHistory();
$activeSessions = getActiveSessions(60);

include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header">
        <h1 class="content-title">Admin Users</h1>
        <button class="btn btn-primary" onclick="document.getElementById('addAdminForm').classList.toggle('hidden')">Add Admin</button>
    </div>
    <?php echo showFlash(); ?>
    
    <!-- 2FA QR Display -->
    <?php if (isset($_SESSION['2fa_qr_url'])): ?>
    <div class="admin-card" style="margin-bottom:20px;text-align:center;">
        <h3 style="margin-bottom:12px;">Two-Factor Authentication Setup</h3>
        <p style="color:var(--muted);margin-bottom:16px;">Scan this QR code with Google Authenticator or Microsoft Authenticator:</p>
        <div style="background:#fff;padding:20px;display:inline-block;border-radius:12px;margin-bottom:16px;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo urlencode($_SESSION['2fa_qr_url']); ?>" alt="2FA QR Code" width="200" height="200" style="display:block;">
        </div>
        <p style="font-size:12px;color:var(--muted);">Or enter this code manually: <code style="background:var(--bg);padding:4px 8px;border-radius:4px;"><?php echo e($_SESSION['2fa_secret_display']); ?></code></p>
        <button class="btn btn-primary btn-sm" style="margin-top:12px;" onclick="window.location.href='admins.php'">Done</button>
    </div>
    <?php unset($_SESSION['2fa_qr_url'], $_SESSION['2fa_secret_display']); ?>
    <?php endif; ?>

    <!-- Add Admin Form -->
    <div id="addAdminForm" class="admin-card hidden" style="margin-bottom:20px;">
        <h3 style="margin-bottom:16px;">Add New Admin</h3>
        <form method="POST" action="admins.php">
            <?php echo csrfField(); ?>
            <input type="hidden" name="action" value="add">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:12px;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required minlength="<?php echo PASSWORD_MIN_LENGTH; ?>">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="editor">Editor</option>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm" style="margin-top:12px;">Create Admin</button>
        </form>
    </div>

    <!-- Admin Users Table -->
    <div class="admin-card" style="margin-bottom:20px;">
        <h3 style="margin-bottom:16px;">All Administrators</h3>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>2FA</th><th>Last Login</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($admins as $a): ?>
                    <tr>
                        <td><strong><?php echo e($a['name']); ?></strong></td>
                        <td><?php echo e($a['email']); ?></td>
                        <td><span class="badge <?php echo $a['role']==='super_admin'?'badge-new':($a['role']==='admin'?'badge-info':'badge-warning');?>"><?php echo e($a['role']); ?></span></td>
                        <td><span class="badge <?php echo $a['status']?'badge-new':'badge-rejected';?>"><?php echo $a['status']?'Active':'Inactive';?></span></td>
                        <td>
                            <span class="badge <?php echo $a['2fa_enabled']?'badge-new':'badge-rejected';?>">
                                <?php echo $a['2fa_enabled']?'✅ On':'❌ Off'; ?>
                            </span>
                        </td>
                        <td style="font-size:13px;color:var(--muted);"><?php echo $a['last_login'] ? formatDate($a['last_login'], 'M d, Y H:i') : 'Never'; ?></td>
                        <td class="actions">
                            <?php if ($a['id'] != $_SESSION['admin_id']): ?>
                            <form method="POST" action="admins.php" style="display:inline;">
                                <?php echo csrfField(); ?>
                                <input type="hidden" name="action" value="toggle_status">
                                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline" title="<?php echo $a['status']?'Deactivate':'Activate'; ?>">
                                    <?php echo $a['status']?'Deactivate':'Activate'; ?>
                                </button>
                            </form>
                            <form method="POST" action="admins.php" style="display:inline;">
                                <?php echo csrfField(); ?>
                                <input type="hidden" name="action" value="toggle_2fa">
                                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline"><?php echo $a['2fa_enabled']?'Disable 2FA':'Enable 2FA'; ?></button>
                            </form>
                            <form method="POST" action="admins.php" style="display:inline;">
                                <?php echo csrfField(); ?>
                                <input type="hidden" name="action" value="force_logout">
                                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline" style="color:var(--warning);">Force Logout</button>
                            </form>
                            <form method="POST" action="admins.php" style="display:inline;">
                                <?php echo csrfField(); ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline" style="color:var(--danger);" onclick="return confirm('Delete this admin?')">Delete</button>
                            </form>
                            <?php else: ?>
                            <span style="font-size:12px;color:var(--muted);">You</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Active Sessions -->
    <div class="admin-card" style="margin-bottom:20px;">
        <h3 style="margin-bottom:16px;">Active Sessions (Last 60 min)</h3>
        <table class="admin-table">
            <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Last Active</th></tr></thead>
            <tbody>
                <?php foreach ($activeSessions as $s): ?>
                <tr>
                    <td><?php echo e($s['name']); ?></td>
                    <td><?php echo e($s['email']); ?></td>
                    <td><span class="badge badge-new"><?php echo e($s['role']); ?></span></td>
                    <td><?php echo formatDate($s['last_login'], 'M d, Y H:i:s'); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($activeSessions)): ?>
                <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:20px;">No active sessions.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Login History -->
    <div class="admin-card">
        <h3 style="margin-bottom:16px;">Login History (Last 50)</h3>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead><tr><th>Email/Admin</th><th>IP Address</th><th>Attempts</th><th>Time</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach ($loginHistory as $h): ?>
                    <tr>
                        <td><?php echo e($h['admin_name'] ?? $h['email']); ?></td>
                        <td><code style="font-size:11px;"><?php echo e($h['ip_address']); ?></code></td>
                        <td><?php echo intval($h['attempts']); ?></td>
                        <td style="font-size:13px;color:var(--muted);"><?php echo formatDate($h['last_attempt'], 'M d, Y H:i:s'); ?></td>
                        <td>
                            <?php if ($h['attempts'] >= MAX_LOGIN_ATTEMPTS): ?>
                            <span class="badge badge-rejected">Locked</span>
                            <?php elseif ($h['attempts'] > 1): ?>
                            <span class="badge badge-warning">Failed</span>
                            <?php else: ?>
                            <span class="badge badge-new">OK</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($loginHistory)): ?>
                    <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:20px;">No login history.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.form-label { display:block; font-size:13px; font-weight:600; margin-bottom:4px; }
.form-input, .form-select { width:100%; padding:8px 12px; background:var(--bg); border:1px solid var(--border); border-radius:var(--radius-sm); color:var(--text); font-size:14px; }
.hidden { display:none !important; }
</style>
<?php include __DIR__ . '/includes/footer.php'; ?>
