<?php
/**
 * Admin Logout v2 - Clear session and remember me
 */
require_once __DIR__ . '/../includes/auth.php';

// Clear remember me cookie
if (isset($_COOKIE['admin_remember'])) {
    $parts = explode(':', $_COOKIE['admin_remember'], 2);
    if (count($parts) === 2) {
        dbExecute("UPDATE admins SET remember_token = NULL, remember_expires = NULL WHERE id = ?", [intval($parts[0])]);
    }
    setcookie('admin_remember', '', time() - 3600, '/');
}

// Clear 2FA session
unset($_SESSION['2fa_admin_id'], $_SESSION['2fa_temp_token'], $_SESSION['2fa_qr_url'], $_SESSION['2fa_secret_display']);

adminLogout();
redirect(ADMIN_URL . '/login-v2.php');
