<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'My Profile';
$adminId = $_SESSION['admin_id'];
$admin = dbFetchOne("SELECT * FROM admins WHERE id = ?", [$adminId]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $currentPass = $_POST['current_password'] ?? '';
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email)) {
        setFlash('error', 'Name and email are required.');
    } elseif (!empty($newPass) && $newPass !== $confirmPass) {
        setFlash('error', 'New passwords do not match.');
    } elseif (!empty($newPass) && strlen($newPass) < PASSWORD_MIN_LENGTH) {
        setFlash('error', 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters.');
    } elseif (!empty($newPass) && !password_verify($currentPass, $admin['password_hash'])) {
        setFlash('error', 'Current password is incorrect.');
    } else {
        dbExecute("UPDATE admins SET name=?, email=? WHERE id=?", [$name, $email, $adminId]);
        if (!empty($newPass)) {
            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            dbExecute("UPDATE admins SET password_hash=? WHERE id=?", [$hash, $adminId]);
        }
        setFlash('success', 'Profile updated successfully.');
        redirect('profile.php');
    }
    $admin = dbFetchOne("SELECT * FROM admins WHERE id = ?", [$adminId]);
}

include __DIR__ . '/includes/header.php';
?>
<div class="admin-content">
    <div class="content-header">
        <h1 class="content-title">My Profile</h1>
    </div>
    <?php echo showFlash(); ?>
    
    <div class="admin-card" style="max-width:600px;">
        <form method="POST" action="profile.php">
            <?php echo csrfField(); ?>
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-input" value="<?php echo e($admin['name']); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" value="<?php echo e($admin['email']); ?>" required>
            </div>
            <hr style="border-color:var(--border);margin:20px 0;">
            <h4 style="margin-bottom:12px;">Change Password (leave blank to keep current)</h4>
            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-input" minlength="<?php echo PASSWORD_MIN_LENGTH; ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-input">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>

<style>
.admin-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 24px; }
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 4px; color: var(--text); }
.form-input { width: 100%; padding: 8px 12px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text); font-size: 14px; }
hr { border: 0; border-top: 1px solid var(--border); }
</style>
<?php include __DIR__ . '/includes/footer.php'; ?>
