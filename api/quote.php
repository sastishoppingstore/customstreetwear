<?php
/**
 * Custom Streetwear - Quote Form API
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Validate CSRF
if (!validateCsrfToken($_POST[CSRF_TOKEN_NAME] ?? '')) {
    echo json_encode(['success' => false, 'message' => 'Invalid security token']);
    exit;
}

// Validate required fields
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');
$productInterest = trim($_POST['product_interest'] ?? '');

if (empty($name) || empty($email) || empty($message) || empty($productInterest)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address']);
    exit;
}

// Handle attachment
$attachmentPath = null;
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $result = uploadFile($_FILES['attachment'], 'temp', array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_PDF_TYPES, ['zip']));
    if ($result['success']) {
        $attachmentPath = $result['path'];
    }
}

// Save to database
try {
    $enquiryId = dbInsert(
        "INSERT INTO enquiries (name, email, phone, whatsapp, country, company, product_interest, quantity, message, attachment, source_page, status) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New')",
        [
            $name, $email, 
            trim($_POST['phone'] ?? ''), 
            trim($_POST['whatsapp'] ?? ''),
            trim($_POST['country'] ?? ''),
            trim($_POST['company'] ?? ''),
            $productInterest,
            trim($_POST['quantity'] ?? ''),
            $message,
            $attachmentPath,
            trim($_POST['source_page'] ?? '')
        ]
    );
    
    // Send email notification
    $adminEmail = getSetting('admin_email', 'info@customstreetwear.co');
    $subject = getSetting('quote_email_subject', 'New Quote Request - Custom Streetwear');
    $body = "<h2>New Quote Request</h2>
    <p><strong>Name:</strong> " . e($name) . "</p>
    <p><strong>Email:</strong> " . e($email) . "</p>
    <p><strong>Phone:</strong> " . e(trim($_POST['phone'] ?? '')) . "</p>
    <p><strong>WhatsApp:</strong> " . e(trim($_POST['whatsapp'] ?? '')) . "</p>
    <p><strong>Country:</strong> " . e(trim($_POST['country'] ?? '')) . "</p>
    <p><strong>Company:</strong> " . e(trim($_POST['company'] ?? '')) . "</p>
    <p><strong>Product Interest:</strong> " . e($productInterest) . "</p>
    <p><strong>Quantity:</strong> " . e(trim($_POST['quantity'] ?? '')) . "</p>
    <p><strong>Message:</strong> " . nl2br(e($message)) . "</p>
    <p><strong>Source:</strong> " . e(trim($_POST['source_page'] ?? '')) . "</p>";
    
    sendEmail($adminEmail, $subject, $body);
    
    echo json_encode(['success' => true, 'message' => 'Quote request submitted successfully']);
    
} catch (Exception $e) {
    error_log("Quote submission error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to submit. Please try again.']);
}
