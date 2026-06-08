<?php
/**
 * Custom Streetwear - Admin Login
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

if (isAdminLoggedIn()) {
    redirect(ADMIN_URL . '/dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $result = adminLogin($email, $password);
    if ($result['success']) {
        redirect(ADMIN_URL . '/dashboard.php');
    } else {
        $error = $result['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo e(getSetting('site_name')); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --bg: #0a0a0a; --bg-alt: #111; --card: #161616; --border: #2a2a2a; --text: #fff; --muted: #888; --accent: #39ff14; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-box { width: 100%; max-width: 420px; padding: 0 24px; }
        .login-logo { text-align: center; margin-bottom: 40px; }
        .login-logo h1 { font-family: 'Oswald', sans-serif; font-size: 28px; letter-spacing: 2px; }
        .login-logo p { color: var(--muted); font-size: 13px; margin-top: 4px; }
        .login-card { background: var(--card); border: 1px solid var(--border); border-radius: 16px; padding: 40px; }
        .login-card h2 { font-family: 'Oswald', sans-serif; font-size: 20px; text-transform: uppercase; margin-bottom: 24px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); margin-bottom: 8px; }
        .form-input { width: 100%; padding: 14px 16px; background: var(--bg-alt); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-family: inherit; font-size: 14px; outline: none; transition: all 0.3s; }
        .form-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(57,255,20,0.1); }
        .btn { width: 100%; padding: 14px; background: var(--accent); color: var(--bg); border: none; border-radius: 8px; font-family: inherit; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; cursor: pointer; transition: all 0.3s; }
        .btn:hover { box-shadow: 0 0 25px rgba(57,255,20,0.3); }
        .alert { padding: 14px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; border: 1px solid; }
        .alert-error { background: rgba(255,68,68,0.1); border-color: #ff4444; color: #ff4444; }
        .login-footer { text-align: center; margin-top: 24px; font-size: 12px; color: var(--muted); }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="login-logo">
            <h1>CUSTOM STREETWEAR</h1>
            <p>Admin Panel</p>
        </div>
        <div class="login-card">
            <h2>Sign In</h2>
            <?php if ($error): ?>
            <div class="alert alert-error"><?php echo e($error); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <?php echo csrfField(); ?>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" required autofocus placeholder="admin@example.com">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>
        <div class="login-footer">
            Default: admin@example.com / Admin@12345
        </div>
    </div>
</body>
</html>
