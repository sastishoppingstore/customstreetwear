<?php
/**
 * Redirect to v2 login
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/auth-v2.php';

// Check remember me cookie
checkRememberMe();

if (isAdminLoggedIn()) {
    redirect(ADMIN_URL . '/dashboard.php');
}

// Redirect to v2 login
redirect(ADMIN_URL . '/login-v2.php');
