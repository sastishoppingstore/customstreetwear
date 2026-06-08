<?php
/**
 * Custom Streetwear - CSRF Protection
 */

/**
 * Generate CSRF token
 */
function generateCsrfToken() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Get CSRF token field (HTML input)
 */
function csrfField() {
    $token = generateCsrfToken();
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . $token . '">';
}

/**
 * Get CSRF token value
 */
function csrfToken() {
    return generateCsrfToken();
}

/**
 * Validate CSRF token
 */
function validateCsrfToken($token = null) {
    if ($token === null) {
        $token = $_POST[CSRF_TOKEN_NAME] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    }
    
    if (empty($_SESSION[CSRF_TOKEN_NAME]) || empty($token)) {
        return false;
    }
    
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Verify CSRF or die
 */
function requireCsrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!validateCsrfToken()) {
            http_response_code(403);
            die('Invalid CSRF token. Please refresh the page and try again.');
        }
    }
}
