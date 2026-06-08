<?php
/**
 * Custom Streetwear - Order Submission API
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Verify CSRF
    requireCsrf();
    
    // Generate order number
    $orderNumber = 'CSW-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    
    // Prepare data
    $data = [
        'order_number' => $orderNumber,
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'company' => trim($_POST['company'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'state' => trim($_POST['state'] ?? ''),
        'zip' => trim($_POST['zip'] ?? ''),
        'product_interest' => trim($_POST['product_interest'] ?? ''),
        'quantity' => trim($_POST['quantity'] ?? ''),
        'custom_details' => trim($_POST['custom_details'] ?? ''),
        'payment_method' => $_POST['payment_method'] ?? 'bank_transfer',
        'source_page' => $_SERVER['HTTP_REFERER'] ?? '',
    ];
    
    // Validate required fields
    $required = ['name', 'email', 'phone'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception('Please fill in all required fields.');
        }
    }
    
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please enter a valid email address.');
    }
    
    // Get delivery charge
    if (!empty($data['city'])) {
        $dc = dbFetchOne("SELECT charge, estimated_days FROM delivery_charges WHERE city = ? AND status = 1", [$data['city']]);
        if ($dc) {
            $data['delivery_charge'] = floatval($dc['charge']);
        }
    }
    
    // Handle payment proof upload
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
        $result = uploadFile($_FILES['payment_proof'], 'payments', array_merge(ALLOWED_IMAGE_TYPES, ['pdf']));
        if ($result['success']) {
            $data['payment_proof'] = $result['path'];
        }
    }
    
    // Insert order
    $cols = implode(', ', array_keys($data));
    $phs = implode(', ', array_fill(0, count($data), '?'));
    dbInsert("INSERT INTO orders ($cols) VALUES ($phs)", array_values($data));
    
    // Send email notification to admin
    $adminEmail = getSetting('site_email', ADMIN_EMAIL);
    $subject = "New Order Request: $orderNumber - " . $data['name'];
    $body = "New order request received.\n\nOrder #: $orderNumber\nName: {$data['name']}\nEmail: {$data['email']}\nPhone: {$data['phone']}\nCity: {$data['city']}, {$data['state']}\n\nProducts: {$data['product_interest']}\nQuantity: {$data['quantity']}";
    sendEmail($adminEmail, $subject, $body);
    
    echo json_encode([
        'success' => true,
        'message' => 'Your order request has been submitted successfully! We will contact you within 24 hours.',
        'order_number' => $orderNumber
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
