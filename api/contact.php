<?php
/**
 * Custom Streetwear - Contact Form API
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!validateCsrfToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Invalid security token']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

try {
    dbInsert(
        "INSERT INTO contact_messages (name, email, phone, subject, message, status) VALUES (?, ?, ?, ?, ?, 'New')",
        [$name, $email, trim($_POST['phone'] ?? ''), trim($_POST['subject'] ?? ''), $message]
    );
    
    $adminEmail = getSetting('admin_email', 'info@customstreetwear.co');
    $subject = getSetting('contact_email_subject', 'New Contact Message - Custom Streetwear');
    $body = "<h2>New Contact Message</h2>
    <p><strong>Name:</strong> " . e($name) . "</p>
    <p><strong>Email:</strong> " . e($email) . "</p>
    <p><strong>Phone:</strong> " . e(trim($_POST['phone'] ?? '')) . "</p>
    <p><strong>Subject:</strong> " . e(trim($_POST['subject'] ?? '')) . "</p>
    <p><strong>Message:</strong> " . nl2br(e($message)) . "</p>";
    
    sendEmail($adminEmail, $subject, $body);
    
    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
} catch (Exception $e) {
    error_log("Contact submission error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to send message']);
}
