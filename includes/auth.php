<?php
/**
 * Custom Streetwear - Authentication Functions
 */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

/**
 * Check if admin is logged in
 */
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0;
}

/**
 * Get current admin info
 */
function getCurrentAdmin() {
    if (!isAdminLoggedIn()) return null;
    return dbFetchOne("SELECT id, name, email, role FROM admins WHERE id = ? AND status = 1", [$_SESSION['admin_id']]);
}

/**
 * Require admin login
 */
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        setFlash('error', 'Please login to access the admin panel.');
        redirect(ADMIN_URL . '/login.php');
    }
}

/**
 * Require super admin
 */
function requireSuperAdmin() {
    requireAdmin();
    $admin = getCurrentAdmin();
    if (!$admin || $admin['role'] !== 'super_admin') {
        setFlash('error', 'You do not have permission to access this page.');
        redirect(ADMIN_URL . '/dashboard.php');
    }
}

/**
 * Admin login
 */
function adminLogin($email, $password) {
    // Check lockout
    $lockout = dbFetchOne("SELECT locked_until FROM login_attempts WHERE email = ? AND locked_until > NOW()", [$email]);
    if ($lockout) {
        return ['success' => false, 'error' => 'Account temporarily locked. Please try again later.'];
    }
    
    $admin = dbFetchOne("SELECT * FROM admins WHERE email = ? AND status = 1", [$email]);
    if (!$admin) {
        recordFailedAttempt($email);
        return ['success' => false, 'error' => 'Invalid email or password.'];
    }
    
    if (!password_verify($password, $admin['password_hash'])) {
        recordFailedAttempt($email);
        return ['success' => false, 'error' => 'Invalid email or password.'];
    }
    
    // Clear failed attempts
    dbExecute("DELETE FROM login_attempts WHERE email = ?", [$email]);
    
    // Update last login
    dbExecute("UPDATE admins SET last_login = NOW(), failed_attempts = 0 WHERE id = ?", [$admin['id']]);
    
    // Set session
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin_email'] = $admin['email'];
    $_SESSION['admin_role'] = $admin['role'];
    $_SESSION['admin_login_time'] = time();
    
    logActivity('Login', 'Admin logged in successfully');
    
    return ['success' => true];
}

/**
 * Record failed login attempt
 */
function recordFailedAttempt($email) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $existing = dbFetchOne("SELECT * FROM login_attempts WHERE email = ?", [$email]);
    
    if ($existing) {
        $attempts = $existing['attempts'] + 1;
        $lockedUntil = null;
        if ($attempts >= MAX_LOGIN_ATTEMPTS) {
            $lockedUntil = date('Y-m-d H:i:s', time() + LOCKOUT_DURATION);
        }
        dbExecute("UPDATE login_attempts SET attempts = ?, last_attempt = NOW(), locked_until = ? WHERE id = ?", 
            [$attempts, $lockedUntil, $existing['id']]);
    } else {
        dbExecute("INSERT INTO login_attempts (email, ip_address, attempts, last_attempt) VALUES (?, ?, 1, NOW())", 
            [$email, $ip]);
    }
}

/**
 * Admin logout
 */
function adminLogout() {
    logActivity('Logout', 'Admin logged out');
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_name']);
    unset($_SESSION['admin_email']);
    unset($_SESSION['admin_role']);
    unset($_SESSION['admin_login_time']);
    session_destroy();
}

/**
 * Check if admin session is valid
 */
function validateAdminSession() {
    if (!isAdminLoggedIn()) return false;
    
    // Check session timeout (2 hours)
    if (isset($_SESSION['admin_login_time']) && (time() - $_SESSION['admin_login_time'] > 7200)) {
        adminLogout();
        return false;
    }
    
    // Refresh login time
    $_SESSION['admin_login_time'] = time();
    return true;
}

/**
 * Has permission
 */
function hasPermission($permission) {
    if (!isAdminLoggedIn()) return false;
    $admin = getCurrentAdmin();
    if (!$admin) return false;
    
    if ($admin['role'] === 'super_admin') return true;
    
    $permissions = [
        'admin' => ['dashboard', 'pages', 'products', 'categories', 'orders', 'enquiries', 'blogs', 'settings'],
        'editor' => ['dashboard', 'pages', 'products', 'blogs']
    ];
    
    return in_array($permission, $permissions[$admin['role']] ?? []);
}
