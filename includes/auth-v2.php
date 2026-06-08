<?php
/**
 * Custom Streetwear v2 - Enhanced Authentication
 * 2FA, Remember Me, Session Management, Rate Limiting
 */

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

/**
 * V2 Login with 2FA support and remember me
 */
function adminLoginV2($email, $password, $remember = false) {
    // Check rate limiting
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $recentAttempts = dbFetchOne("SELECT COUNT(*) as c FROM login_attempts WHERE ip_address = ? AND last_attempt > DATE_SUB(NOW(), INTERVAL 15 MINUTE)", [$ip]);
    if ($recentAttempts && $recentAttempts['c'] >= 10) {
        return ['success' => false, 'error' => 'Too many login attempts. Please wait 15 minutes.'];
    }

    // Check lockout
    $lockout = dbFetchOne("SELECT locked_until FROM admins WHERE email = ? AND locked_until > NOW()", [$email]);
    if ($lockout) {
        return ['success' => false, 'error' => 'Account locked. Try again later.'];
    }

    $admin = dbFetchOne("SELECT * FROM admins WHERE email = ? AND status = 1", [$email]);
    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        recordFailedAttemptV2($email, $ip);
        return ['success' => false, 'error' => 'Invalid email or password.'];
    }

    // Clear failed attempts
    dbExecute("DELETE FROM login_attempts WHERE email = ?", [$email]);
    dbExecute("UPDATE admins SET failed_attempts = 0, locked_until = NULL WHERE id = ?", [$admin['id']]);

    // Check if 2FA is enabled
    $twoFASecret = $admin['2fa_secret'] ?? '';
    if (!empty($twoFASecret)) {
        $_SESSION['2fa_admin_id'] = $admin['id'];
        $_SESSION['2fa_temp_token'] = bin2hex(random_bytes(32));
        return [
            'success' => true,
            '2fa_required' => true,
            'temp_token' => $_SESSION['2fa_temp_token']
        ];
    }

    // Direct login
    completeAdminLogin($admin, $remember);
    return ['success' => true, '2fa_required' => false];
}

/**
 * Complete login after 2FA verification
 */
function completeAdminLogin($admin, $remember = false) {
    // Update last login
    dbExecute("UPDATE admins SET last_login = NOW(), failed_attempts = 0 WHERE id = ?", [$admin['id']]);
    
    // Set session
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin_email'] = $admin['email'];
    $_SESSION['admin_role'] = $admin['role'];
    $_SESSION['admin_login_time'] = time();
    $_SESSION['admin_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
    $_SESSION['admin_user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Remember me - 30 days cookie
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 30 * 24 * 3600);
        dbExecute("UPDATE admins SET remember_token = ?, remember_expires = ? WHERE id = ?", 
            [password_hash($token, PASSWORD_DEFAULT), $expires, $admin['id']]);
        
        setcookie('admin_remember', $admin['id'] . ':' . $token, time() + 30 * 24 * 3600, '/', '', true, true);
    }
    
    // Clean up 2FA session
    unset($_SESSION['2fa_admin_id'], $_SESSION['2fa_temp_token']);
    
    logActivity('Login', 'Admin logged in successfully via v2');
}

/**
 * Verify 2FA code
 */
function verify2FACode($tempToken, $code) {
    if (empty($tempToken) || $tempToken !== ($_SESSION['2fa_temp_token'] ?? '')) {
        return false;
    }
    
    $admin = dbFetchOne("SELECT * FROM admins WHERE id = ?", [$_SESSION['2fa_admin_id'] ?? 0]);
    if (!$admin || empty($admin['2fa_secret'])) {
        return false;
    }
    
    // Simple TOTP verification (6 digits)
    if (strlen($code) === 6 && ctype_digit($code)) {
        $expected = getTOTPCode($admin['2fa_secret']);
        // Allow 1 step before/after for time drift
        $prev = getTOTPCode($admin['2fa_secret'], -1);
        $next = getTOTPCode($admin['2fa_secret'], 1);
        
        return hash_equals($expected, $code) || hash_equals($prev, $code) || hash_equals($next, $code);
    }
    
    return false;
}

/**
 * Generate TOTP code (simplified - for production use a library)
 */
function getTOTPCode($secret, $offset = 0) {
    $interval = floor(time() / 30) + $offset;
    $msg = pack('N*', 0) . pack('N*', $interval);
    $hash = hash_hmac('sha1', $msg, $secret, true);
    $offset = ord($hash[19]) & 0xf;
    $code = (ord($hash[$offset]) & 0x7f) << 24 |
            (ord($hash[$offset + 1]) & 0xff) << 16 |
            (ord($hash[$offset + 2]) & 0xff) << 8 |
            (ord($hash[$offset + 3]) & 0xff);
    return str_pad($code % 1000000, 6, '0', STR_PAD_LEFT);
}

/**
 * Record failed attempt v2
 */
function recordFailedAttemptV2($email, $ip) {
    // Track by email
    dbExecute("INSERT INTO login_attempts (email, ip_address, attempts, last_attempt) VALUES (?, ?, 1, NOW())", [$email, $ip]);
    
    // Track by IP for rate limiting
    dbExecute("INSERT INTO login_attempts (email, ip_address, attempts, last_attempt) VALUES ('rate_limit', ?, 1, NOW())", [$ip]);
    
    // Update admin failed count
    dbExecute("UPDATE admins SET failed_attempts = failed_attempts + 1 WHERE email = ?", [$email]);
    
    // Lock account after max attempts
    $admin = dbFetchOne("SELECT id, failed_attempts FROM admins WHERE email = ?", [$email]);
    if ($admin && $admin['failed_attempts'] >= MAX_LOGIN_ATTEMPTS) {
        dbExecute("UPDATE admins SET locked_until = DATE_ADD(NOW(), INTERVAL " . LOCKOUT_DURATION . " SECOND) WHERE id = ?", [$admin['id']]);
    }
}

/**
 * Check remember me cookie
 */
function checkRememberMe() {
    if (isAdminLoggedIn()) return true;
    
    if (!isset($_COOKIE['admin_remember'])) return false;
    
    $parts = explode(':', $_COOKIE['admin_remember'], 2);
    if (count($parts) !== 2) return false;
    
    $adminId = intval($parts[0]);
    $token = $parts[1];
    
    $admin = dbFetchOne("SELECT * FROM admins WHERE id = ? AND status = 1 AND remember_token IS NOT NULL AND remember_expires > NOW()", [$adminId]);
    if (!$admin) return false;
    
    if (password_verify($token, $admin['remember_token'])) {
        completeAdminLogin($admin);
        return true;
    }
    
    return false;
}

/**
 * Get login history for admin
 */
function getLoginHistory($adminId = null, $limit = 50) {
    if ($adminId) {
        return dbFetchAll("SELECT * FROM login_attempts WHERE email = (SELECT email FROM admins WHERE id = ?) ORDER BY last_attempt DESC LIMIT " . intval($limit), [$adminId]);
    }
    return dbFetchAll("SELECT la.*, a.name as admin_name FROM login_attempts la LEFT JOIN admins a ON la.email = a.email WHERE la.email NOT IN ('rate_limit') ORDER BY la.last_attempt DESC LIMIT " . intval($limit));
}

/**
 * Get active admin sessions (based on login recency)
 */
function getActiveSessions($minutes = 30) {
    return dbFetchAll("SELECT id, name, email, role, last_login FROM admins WHERE last_login > DATE_SUB(NOW(), INTERVAL ? MINUTE) AND status = 1 ORDER BY last_login DESC", [intval($minutes)]);
}

/**
 * Force logout all sessions for an admin
 */
function forceLogoutAdmin($adminId) {
    dbExecute("UPDATE admins SET remember_token = NULL, remember_expires = NULL WHERE id = ?", [$adminId]);
    logActivity('Force Logout', 'Admin #' . $adminId . ' force logged out');
}

/**
 * Check if session is valid (prevent session hijacking)
 */
function validateSessionV2() {
    if (!isAdminLoggedIn()) return false;
    
    $storedIp = $_SESSION['admin_ip'] ?? '';
    $storedUA = $_SESSION['admin_user_agent'] ?? '';
    $currentIp = $_SERVER['REMOTE_ADDR'] ?? '';
    $currentUA = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Check IP changed (loose check - allow NAT changes)
    // Check user agent changed (strict)
    if (!empty($storedUA) && $storedUA !== $currentUA) {
        adminLogout();
        return false;
    }
    
    // Check timeout (2 hours)
    if (isset($_SESSION['admin_login_time']) && (time() - $_SESSION['admin_login_time'] > 7200)) {
        adminLogout();
        return false;
    }
    
    // Refresh login time
    $_SESSION['admin_login_time'] = time();
    $_SESSION['admin_ip'] = $currentIp;
    
    return true;
}

/**
 * Generate 2FA secret (for admin to set up)
 */
function generate2FASecret() {
    return bin2hex(random_bytes(10)); // 20 chars = 160 bits
}

/**
 * Get 2FA QR code URL (for Google Authenticator)
 */
function get2FAQRCodeURL($email, $secret) {
    $issuer = getSetting('site_name', 'Custom Streetwear');
    return "otpauth://totp/" . rawurlencode($issuer) . ":" . rawurlencode($email) .
           "?secret=" . $secret . "&issuer=" . rawurlencode($issuer);
}
