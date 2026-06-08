<?php
/**
 * Custom Streetwear - Main Configuration
 * Domain: customstreetwear.co
 */

// Prevent direct access
if (!defined('CSW_ROOT')) {
    define('CSW_ROOT', dirname(__DIR__));
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'customstreetwear');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Website Settings
define('SITE_URL', 'https://customstreetwear.co');
define('SITE_NAME', 'Custom Streetwear');
define('SITE_EMAIL', 'info@customstreetwear.co');
define('ADMIN_EMAIL', 'admin@customstreetwear.co');

// USA SEO Defaults
define('DEFAULT_SEO_TITLE', 'Custom Apparel Manufacturer in USA | Custom Streetwear');
define('DEFAULT_SEO_DESC', 'Custom Streetwear is a premium custom apparel manufacturer serving the USA. Custom sportswear, streetwear, workwear, and uniforms. Factory-direct pricing. Fast delivery nationwide.');

// Paths
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOADS_URL', SITE_URL . '/uploads');
define('UPLOADS_PATH', CSW_ROOT . '/uploads');
define('TEMPLATES_PATH', CSW_ROOT . '/templates');
define('INCLUDES_PATH', CSW_ROOT . '/includes');
define('ADMIN_URL', SITE_URL . '/admin');

// Security
define('CSRF_TOKEN_NAME', 'csw_csrf_token');
define('SESSION_NAME', 'csw_session');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_DURATION', 900); // 15 minutes
define('PASSWORD_MIN_LENGTH', 8);

// Upload Settings
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'webp', 'gif']);
define('ALLOWED_PDF_TYPES', ['pdf']);
define('ALLOWED_VIDEO_TYPES', ['mp4', 'webm']);

// reCAPTCHA (optional - configure in admin)
define('RECAPTCHA_SITE_KEY', '');
define('RECAPTCHA_SECRET_KEY', '');

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_lifetime', 0);

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}
