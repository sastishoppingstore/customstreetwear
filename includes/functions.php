<?php
/**
 * Custom Streetwear - Helper Functions
 */

require_once __DIR__ . '/db.php';

/**
 * Safe output - prevent XSS
 */
function e($string) {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

/**
 * Create URL-friendly slug
 */
function createSlug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

/**
 * Generate unique slug
 */
function generateUniqueSlug($table, $title, $excludeId = null) {
    $slug = createSlug($title);
    $originalSlug = $slug;
    $counter = 1;
    
    while (true) {
        $sql = "SELECT id FROM {$table} WHERE slug = ?";
        $params = [$slug];
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        $existing = dbFetchOne($sql, $params);
        if (!$existing) {
            break;
        }
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }
    return $slug;
}

/**
 * Get site setting
 */
function getSetting($key, $default = '') {
    static $settings = null;
    if ($settings === null) {
        $settings = [];
        $rows = dbFetchAll("SELECT setting_key, setting_value FROM site_settings");
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settings[$key] ?? $default;
}

/**
 * Refresh settings cache
 */
function refreshSettings() {
    global $settings;
    $settings = null;
}

/**
 * Upload file with safe naming
 */
function uploadFile($file, $directory, $allowedTypes = ALLOWED_IMAGE_TYPES) {
    if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
        return ['success' => false, 'error' => 'No file uploaded'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload error: ' . $file['error']];
    }
    
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        return ['success' => false, 'error' => 'File too large. Max size: ' . (MAX_UPLOAD_SIZE / 1024 / 1024) . 'MB'];
    }
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes)];
    }
    
    // Validate image
    if (in_array($ext, ALLOWED_IMAGE_TYPES)) {
        $imageInfo = getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            return ['success' => false, 'error' => 'Invalid image file'];
        }
    }
    
    $filename = uniqid() . '_' . time() . '.' . $ext;
    $uploadPath = UPLOADS_PATH . '/' . $directory . '/' . $filename;
    
    if (!is_dir(UPLOADS_PATH . '/' . $directory)) {
        mkdir(UPLOADS_PATH . '/' . $directory, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return [
            'success' => true,
            'path' => '/uploads/' . $directory . '/' . $filename,
            'filename' => $filename
        ];
    }
    
    return ['success' => false, 'error' => 'Failed to move uploaded file'];
}

/**
 * Delete uploaded file
 */
function deleteUpload($path) {
    if (!$path) return;
    $fullPath = CSW_ROOT . $path;
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
}

/**
 * Truncate text
 */
function truncate($text, $length = 150) {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

/**
 * Format date
 */
function formatDate($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

/**
 * Get page URL
 */
function pageUrl($slug) {
    return SITE_URL . '/' . $slug;
}

/**
 * Get category URL
 */
function categoryUrl($slug) {
    return SITE_URL . '/category/' . $slug;
}

/**
 * Get product URL
 */
function productUrl($slug) {
    return SITE_URL . '/product/' . $slug;
}

/**
 * Get state URL
 */
function stateUrl($slug) {
    return SITE_URL . '/locations/' . $slug;
}

/**
 * Get blog URL
 */
function blogUrl($slug) {
    return SITE_URL . '/blog/' . $slug;
}

/**
 * Build breadcrumb
 */
function buildBreadcrumb($items) {
    $html = '<nav class="breadcrumb" aria-label="Breadcrumb"><ol>';
    $html .= '<li><a href="' . SITE_URL . '">Home</a></li>';
    foreach ($items as $item) {
        if (isset($item['url']) && $item['url']) {
            $html .= '<li><a href="' . $item['url'] . '">' . e($item['label']) . '</a></li>';
        } else {
            $html .= '<li aria-current="page">' . e($item['label']) . '</li>';
        }
    }
    $html .= '</ol></nav>';
    return $html;
}

/**
 * Generate meta tags
 */
function generateMetaTags($title = '', $description = '', $ogImage = '', $canonical = '') {
    $siteName = getSetting('site_name', 'Custom Streetwear');
    $defaultTitle = getSetting('seo_title', DEFAULT_SEO_TITLE);
    $defaultDesc = getSetting('seo_description', DEFAULT_SEO_DESC);
    $defaultOgImage = getSetting('og_image', '/uploads/settings/og-image.jpg');;
    
    $metaTitle = $title ? $title . ' | ' . $siteName : $defaultTitle;
    $metaDesc = $description ?: $defaultDesc;
    $metaImage = $ogImage ?: $defaultOgImage;
    $canonicalUrl = $canonical ?: SITE_URL . $_SERVER['REQUEST_URI'];
    
    $html = '<title>' . e($metaTitle) . '</title>' . "\n";
    $html .= '<meta name="description" content="' . e($metaDesc) . '">' . "\n";
    $html .= '<link rel="canonical" href="' . e($canonicalUrl) . '">' . "\n";
    $html .= '<meta property="og:title" content="' . e($metaTitle) . '">' . "\n";
    $html .= '<meta property="og:description" content="' . e($metaDesc) . '">' . "\n";
    $html .= '<meta property="og:image" content="' . SITE_URL . $metaImage . '">' . "\n";
    $html .= '<meta property="og:url" content="' . e($canonicalUrl) . '">' . "\n";
    $html .= '<meta property="og:type" content="website">' . "\n";
    $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    $html .= '<meta name="twitter:title" content="' . e($metaTitle) . '">' . "\n";
    $html .= '<meta name="twitter:description" content="' . e($metaDesc) . '">' . "\n";
    $html .= '<meta name="twitter:image" content="' . SITE_URL . $metaImage . '">' . "\n";
    
    return $html;
}

/**
 * Pagination
 */
function pagination($total, $perPage, $currentPage, $urlPattern) {
    $totalPages = ceil($total / $perPage);
    if ($totalPages <= 1) return '';
    
    $html = '<div class="pagination">';
    
    // Previous
    if ($currentPage > 1) {
        $html .= '<a href="' . str_replace('{page}', $currentPage - 1, $urlPattern) . '" class="prev">&laquo; Prev</a>';
    }
    
    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    
    if ($start > 1) {
        $html .= '<a href="' . str_replace('{page}', 1, $urlPattern) . '">1</a>';
        if ($start > 2) $html .= '<span>...</span>';
    }
    
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $currentPage) {
            $html .= '<span class="current">' . $i . '</span>';
        } else {
            $html .= '<a href="' . str_replace('{page}', $i, $urlPattern) . '">' . $i . '</a>';
        }
    }
    
    if ($end < $totalPages) {
        if ($end < $totalPages - 1) $html .= '<span>...</span>';
        $html .= '<a href="' . str_replace('{page}', $totalPages, $urlPattern) . '">' . $totalPages . '</a>';
    }
    
    // Next
    if ($currentPage < $totalPages) {
        $html .= '<a href="' . str_replace('{page}', $currentPage + 1, $urlPattern) . '" class="next">Next &raquo;</a>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Send email
 */
function sendEmail($to, $subject, $body, $from = '') {
    $from = $from ?: getSetting('site_email', 'info@customstreetwear.co');
    $siteName = getSetting('site_name', 'Custom Streetwear');
    
    $headers = "From: {$siteName} <{$from}>\r\n";
    $headers .= "Reply-To: {$from}\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    
    return mail($to, $subject, $body, $headers);
}

/**
 * Flash message
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function showFlash() {
    $flash = getFlash();
    if ($flash) {
        return '<div class="alert alert-' . $flash['type'] . '">' . e($flash['message']) . '</div>';
    }
    return '';
}

/**
 * Get active categories
 */
function getCategories($parentOnly = false) {
    return dbFetchAll("SELECT * FROM categories WHERE status = 1 ORDER BY sort_order, name");
}

/**
 * Get subcategories by category
 */
function getSubcategories($categoryId = null) {
    if ($categoryId) {
        return dbFetchAll("SELECT s.*, c.name as category_name, c.slug as category_slug 
            FROM subcategories s 
            JOIN categories c ON s.category_id = c.id 
            WHERE s.category_id = ? AND s.status = 1 
            ORDER BY s.sort_order, s.name", [$categoryId]);
    }
    return dbFetchAll("SELECT s.*, c.name as category_name, c.slug as category_slug 
        FROM subcategories s 
        JOIN categories c ON s.category_id = c.id 
        WHERE s.status = 1 
        ORDER BY s.sort_order, s.name");
}

/**
 * Get products
 */
function getProducts($filters = [], $limit = null, $offset = 0) {
    $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
        s.name as subcategory_name, s.slug as subcategory_slug
        FROM products p
        JOIN categories c ON p.category_id = c.id
        LEFT JOIN subcategories s ON p.subcategory_id = s.id
        WHERE p.status = 1";
    $params = [];
    
    if (!empty($filters['category_id'])) {
        $sql .= " AND p.category_id = ?";
        $params[] = $filters['category_id'];
    }
    if (!empty($filters['subcategory_id'])) {
        $sql .= " AND p.subcategory_id = ?";
        $params[] = $filters['subcategory_id'];
    }
    if (!empty($filters['featured'])) {
        $sql .= " AND p.is_featured = 1";
    }
    if (!empty($filters['best_seller'])) {
        $sql .= " AND p.is_best_seller = 1";
    }
    
    $sql .= " ORDER BY p.sort_order, p.created_at DESC";
    
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
        if ($offset) {
            $sql .= " OFFSET " . (int)$offset;
        }
    }
    
    return dbFetchAll($sql, $params);
}

/**
 * Get product by slug
 */
function getProduct($slug) {
    return dbFetchOne("SELECT p.*, c.name as category_name, c.slug as category_slug,
        s.name as subcategory_name, s.slug as subcategory_slug
        FROM products p
        JOIN categories c ON p.category_id = c.id
        LEFT JOIN subcategories s ON p.subcategory_id = s.id
        WHERE p.slug = ? AND p.status = 1", [$slug]);
}

/**
 * Get product gallery images
 */
function getProductImages($productId) {
    return dbFetchAll("SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order", [$productId]);
}

/**
 * Get related products
 */
function getRelatedProducts($productId, $categoryId, $limit = 4) {
    return dbFetchAll("SELECT p.*, c.name as category_name, c.slug as category_slug
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.id != ? AND p.category_id = ? AND p.status = 1
        ORDER BY RAND()
        LIMIT " . (int)$limit, [$productId, $categoryId]);
}

/**
 * Get navigation menu
 */
function getMenu($location = 'header') {
    $items = dbFetchAll("SELECT * FROM menus WHERE menu_location = ? AND status = 1 ORDER BY sort_order", [$location]);
    $menu = [];
    foreach ($items as $item) {
        if ($item['parent_id'] === null) {
            $item['children'] = [];
            foreach ($items as $child) {
                if ($child['parent_id'] == $item['id']) {
                    $item['children'][] = $child;
                }
            }
            $menu[] = $item;
        }
    }
    return $menu;
}

/**
 * Log activity
 */
function logActivity($action, $details = '') {
    $userId = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : 0;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    error_log("[CSW Activity] User: {$userId} | Action: {$action} | IP: {$ip} | Details: {$details}");
}

/**
 * Redirect
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Get current URL path
 */
function currentPath() {
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
}

/**
 * Check if current page
 */
function isCurrentPage($path) {
    return currentPath() === $path;
}

/**
 * Get URL parameter safely
 */
function getParam($key, $default = '') {
    return isset($_GET[$key]) ? htmlspecialchars($_GET[$key]) : $default;
}

/**
 * Get USA states array
 */
function getUSAStates() {
    return [
        'alabama' => ['name' => 'Alabama', 'code' => 'AL', 'slug' => 'alabama'],
        'alaska' => ['name' => 'Alaska', 'code' => 'AK', 'slug' => 'alaska'],
        'arizona' => ['name' => 'Arizona', 'code' => 'AZ', 'slug' => 'arizona'],
        'arkansas' => ['name' => 'Arkansas', 'code' => 'AR', 'slug' => 'arkansas'],
        'california' => ['name' => 'California', 'code' => 'CA', 'slug' => 'california'],
        'colorado' => ['name' => 'Colorado', 'code' => 'CO', 'slug' => 'colorado'],
        'connecticut' => ['name' => 'Connecticut', 'code' => 'CT', 'slug' => 'connecticut'],
        'delaware' => ['name' => 'Delaware', 'code' => 'DE', 'slug' => 'delaware'],
        'florida' => ['name' => 'Florida', 'code' => 'FL', 'slug' => 'florida'],
        'georgia' => ['name' => 'Georgia', 'code' => 'GA', 'slug' => 'georgia'],
        'hawaii' => ['name' => 'Hawaii', 'code' => 'HI', 'slug' => 'hawaii'],
        'idaho' => ['name' => 'Idaho', 'code' => 'ID', 'slug' => 'idaho'],
        'illinois' => ['name' => 'Illinois', 'code' => 'IL', 'slug' => 'illinois'],
        'indiana' => ['name' => 'Indiana', 'code' => 'IN', 'slug' => 'indiana'],
        'iowa' => ['name' => 'Iowa', 'code' => 'IA', 'slug' => 'iowa'],
        'kansas' => ['name' => 'Kansas', 'code' => 'KS', 'slug' => 'kansas'],
        'kentucky' => ['name' => 'Kentucky', 'code' => 'KY', 'slug' => 'kentucky'],
        'louisiana' => ['name' => 'Louisiana', 'code' => 'LA', 'slug' => 'louisiana'],
        'maine' => ['name' => 'Maine', 'code' => 'ME', 'slug' => 'maine'],
        'maryland' => ['name' => 'Maryland', 'code' => 'MD', 'slug' => 'maryland'],
        'massachusetts' => ['name' => 'Massachusetts', 'code' => 'MA', 'slug' => 'massachusetts'],
        'michigan' => ['name' => 'Michigan', 'code' => 'MI', 'slug' => 'michigan'],
        'minnesota' => ['name' => 'Minnesota', 'code' => 'MN', 'slug' => 'minnesota'],
        'mississippi' => ['name' => 'Mississippi', 'code' => 'MS', 'slug' => 'mississippi'],
        'missouri' => ['name' => 'Missouri', 'code' => 'MO', 'slug' => 'missouri'],
        'montana' => ['name' => 'Montana', 'code' => 'MT', 'slug' => 'montana'],
        'nebraska' => ['name' => 'Nebraska', 'code' => 'NE', 'slug' => 'nebraska'],
        'nevada' => ['name' => 'Nevada', 'code' => 'NV', 'slug' => 'nevada'],
        'new-hampshire' => ['name' => 'New Hampshire', 'code' => 'NH', 'slug' => 'new-hampshire'],
        'new-jersey' => ['name' => 'New Jersey', 'code' => 'NJ', 'slug' => 'new-jersey'],
        'new-mexico' => ['name' => 'New Mexico', 'code' => 'NM', 'slug' => 'new-mexico'],
        'new-york' => ['name' => 'New York', 'code' => 'NY', 'slug' => 'new-york'],
        'north-carolina' => ['name' => 'North Carolina', 'code' => 'NC', 'slug' => 'north-carolina'],
        'north-dakota' => ['name' => 'North Dakota', 'code' => 'ND', 'slug' => 'north-dakota'],
        'ohio' => ['name' => 'Ohio', 'code' => 'OH', 'slug' => 'ohio'],
        'oklahoma' => ['name' => 'Oklahoma', 'code' => 'OK', 'slug' => 'oklahoma'],
        'oregon' => ['name' => 'Oregon', 'code' => 'OR', 'slug' => 'oregon'],
        'pennsylvania' => ['name' => 'Pennsylvania', 'code' => 'PA', 'slug' => 'pennsylvania'],
        'rhode-island' => ['name' => 'Rhode Island', 'code' => 'RI', 'slug' => 'rhode-island'],
        'south-carolina' => ['name' => 'South Carolina', 'code' => 'SC', 'slug' => 'south-carolina'],
        'south-dakota' => ['name' => 'South Dakota', 'code' => 'SD', 'slug' => 'south-dakota'],
        'tennessee' => ['name' => 'Tennessee', 'code' => 'TN', 'slug' => 'tennessee'],
        'texas' => ['name' => 'Texas', 'code' => 'TX', 'slug' => 'texas'],
        'utah' => ['name' => 'Utah', 'code' => 'UT', 'slug' => 'utah'],
        'vermont' => ['name' => 'Vermont', 'code' => 'VT', 'slug' => 'vermont'],
        'virginia' => ['name' => 'Virginia', 'code' => 'VA', 'slug' => 'virginia'],
        'washington' => ['name' => 'Washington', 'code' => 'WA', 'slug' => 'washington'],
        'west-virginia' => ['name' => 'West Virginia', 'code' => 'WV', 'slug' => 'west-virginia'],
        'wisconsin' => ['name' => 'Wisconsin', 'code' => 'WI', 'slug' => 'wisconsin'],
        'wyoming' => ['name' => 'Wyoming', 'code' => 'WY', 'slug' => 'wyoming'],
    ];
}

/**
 * Get USA state info by slug
 */
function getUSAState($slug) {
    $states = getUSAStates();
    return $states[$slug] ?? null;
}

/**
 * Get cities by state slug
 */
function getStateCities($stateSlug) {
    $cities = [
        'alabama' => ['birmingham', 'montgomery', 'mobile', 'huntsville', 'tuscaloosa'],
        'alaska' => ['anchorage', 'fairbanks', 'juneau'],
        'arizona' => ['phoenix', 'tucson', 'mesa', 'chandler', 'scottsdale'],
        'arkansas' => ['little-rock', 'fayetteville', 'fort-smith'],
        'california' => ['los-angeles', 'san-diego', 'san-jose', 'san-francisco', 'fresno', 'sacramento', 'long-beach', 'oakland', 'anaheim'],
        'colorado' => ['denver', 'colorado-springs', 'aurora', 'fort-collins'],
        'connecticut' => ['bridgeport', 'new-haven', 'hartford', 'stamford'],
        'delaware' => ['wilmington', 'dover'],
        'florida' => ['miami', 'jacksonville', 'tampa', 'orlando', 'st-petersburg', 'fort-lauderdale'],
        'georgia' => ['atlanta', 'augusta', 'columbus', 'savannah'],
        'hawaii' => ['honolulu'],
        'idaho' => ['boise', 'meridian'],
        'illinois' => ['chicago', 'aurora', 'rockford', 'springfield', 'champaign'],
        'indiana' => ['indianapolis', 'fort-wayne', 'evansville', 'south-bend'],
        'iowa' => ['des-moines', 'cedar-rapids', 'davenport'],
        'kansas' => ['wichita', 'overland-park', 'kansas-city'],
        'kentucky' => ['louisville', 'lexington', 'bowling-green'],
        'louisiana' => ['new-orleans', 'baton-rouge', 'shreveport', 'lafayette'],
        'maine' => ['portland', 'lewiston'],
        'maryland' => ['baltimore', 'columbia', 'silver-spring'],
        'massachusetts' => ['boston', 'worcester', 'springfield', 'cambridge'],
        'michigan' => ['detroit', 'grand-rapids', 'warren', 'sterling-heights', 'ann-arbor'],
        'minnesota' => ['minneapolis', 'saint-paul', 'rochester'],
        'mississippi' => ['jackson', 'gulfport'],
        'missouri' => ['kansas-city', 'saint-louis', 'springfield', 'columbia'],
        'montana' => ['billings', 'missoula'],
        'nebraska' => ['omaha', 'lincoln'],
        'nevada' => ['las-vegas', 'henderson', 'reno', 'north-las-vegas'],
        'new-hampshire' => ['manchester', 'nashua'],
        'new-jersey' => ['newark', 'jersey-city', 'paterson', 'edison'],
        'new-mexico' => ['albuquerque', 'las-cruces', 'santa-fe'],
        'new-york' => ['new-york-city', 'buffalo', 'rochester', 'albany', 'syracuse'],
        'north-carolina' => ['charlotte', 'raleigh', 'greensboro', 'durham', 'winston-salem'],
        'north-dakota' => ['fargo', 'bismarck'],
        'ohio' => ['columbus', 'cleveland', 'cincinnati', 'toledo', 'akron', 'dayton'],
        'oklahoma' => ['oklahoma-city', 'tulsa', 'norman'],
        'oregon' => ['portland', 'salem', 'eugene', 'gresham'],
        'pennsylvania' => ['philadelphia', 'pittsburgh', 'allentown', 'erie', 'scranton'],
        'rhode-island' => ['providence', 'warwick'],
        'south-carolina' => ['charleston', 'columbia', 'greenville'],
        'south-dakota' => ['sioux-falls', 'rapid-city'],
        'tennessee' => ['nashville', 'memphis', 'knoxville', 'chattanooga'],
        'texas' => ['houston', 'san-antonio', 'dallas', 'austin', 'fort-worth', 'el-paso', 'arlington', 'corpus-christi'],
        'utah' => ['salt-lake-city', 'west-valley-city', 'provo'],
        'vermont' => ['burlington'],
        'virginia' => ['virginia-beach', 'norfolk', 'chesapeake', 'richmond', 'newport-news'],
        'washington' => ['seattle', 'spokane', 'tacoma', 'vancouver', 'bellevue'],
        'west-virginia' => ['charleston', 'huntington'],
        'wisconsin' => ['milwaukee', 'madison', 'green-bay', 'kenosha'],
        'wyoming' => ['cheyenne', 'casper'],
    ];
    return $cities[$stateSlug] ?? [];
}

/**
 * Get city display name from slug
 */
function getCityName($slug) {
    $nameMap = [
        'los-angeles' => 'Los Angeles', 'san-diego' => 'San Diego', 'san-jose' => 'San Jose',
        'san-francisco' => 'San Francisco', 'long-beach' => 'Long Beach', 'colorado-springs' => 'Colorado Springs',
        'fort-collins' => 'Fort Collins', 'new-haven' => 'New Haven', 'st-petersburg' => 'St. Petersburg',
        'fort-lauderdale' => 'Fort Lauderdale', 'little-rock' => 'Little Rock', 'fort-smith' => 'Fort Smith',
        'fort-wayne' => 'Fort Wayne', 'south-bend' => 'South Bend', 'des-moines' => 'Des Moines',
        'cedar-rapids' => 'Cedar Rapids', 'overland-park' => 'Overland Park', 'kansas-city' => 'Kansas City',
        'new-orleans' => 'New Orleans', 'baton-rouge' => 'Baton Rouge', 'silver-spring' => 'Silver Spring',
        'grand-rapids' => 'Grand Rapids', 'sterling-heights' => 'Sterling Heights', 'ann-arbor' => 'Ann Arbor',
        'saint-paul' => 'Saint Paul', 'saint-louis' => 'Saint Louis', 'las-vegas' => 'Las Vegas',
        'north-las-vegas' => 'North Las Vegas', 'jersey-city' => 'Jersey City', 'new-york-city' => 'New York City',
        'north-carolina' => 'North Carolina', 'north-dakota' => 'North Dakota', 'winston-salem' => 'Winston-Salem',
        'oklahoma-city' => 'Oklahoma City', 'salt-lake-city' => 'Salt Lake City', 'west-valley-city' => 'West Valley City',
        'virginia-beach' => 'Virginia Beach', 'newport-news' => 'Newport News', 'green-bay' => 'Green Bay',
        'rhode-island' => 'Rhode Island', 'south-carolina' => 'South Carolina', 'south-dakota' => 'South Dakota',
        'sioux-falls' => 'Sioux Falls', 'rapid-city' => 'Rapid City', 'el-paso' => 'El Paso',
        'fort-worth' => 'Fort Worth', 'corpus-christi' => 'Corpus Christi', 'santa-fe' => 'Santa Fe',
        'las-cruces' => 'Las Cruces',
    ];
    return $nameMap[$slug] ?? ucwords(str_replace('-', ' ', $slug));
}

/**
 * Get state SEO data
 */
function getStateSEODescription($stateName) {
    $descriptions = [
        'California' => 'Custom apparel manufacturing serving California with premium sportswear, streetwear, and uniforms. Factory-direct pricing for LA, San Francisco, San Diego, and all CA cities.',
        'Florida' => 'Custom apparel manufacturer serving Florida with premium sportswear, streetwear, workwear, and uniforms. Serving Miami, Orlando, Tampa, Jacksonville, and all FL cities.',
        'Texas' => 'Custom apparel manufacturer serving Texas with premium sportswear, streetwear, workwear, and uniforms. Serving Dallas, Houston, Austin, San Antonio, and all TX cities.',
        'New York' => 'Custom apparel manufacturer serving New York with premium sportswear, streetwear, and uniforms. Serving NYC, Buffalo, Rochester, Albany, and all NY cities.',
        'Illinois' => 'Custom apparel manufacturer serving Illinois with premium sportswear, streetwear, and uniforms. Serving Chicago, Aurora, Rockford, and all IL cities.',
        'Georgia' => 'Custom apparel manufacturer serving Georgia with premium sportswear, streetwear, and uniforms. Serving Atlanta, Augusta, Savannah, and all GA cities.',
        'Washington' => 'Custom apparel manufacturer serving Washington with premium sportswear, streetwear, and uniforms. Serving Seattle, Spokane, Tacoma, and all WA cities.',
        'Colorado' => 'Custom apparel manufacturer serving Colorado with premium sportswear, streetwear, and uniforms. Serving Denver, Colorado Springs, Aurora, and all CO cities.',
        'Nevada' => 'Custom apparel manufacturer serving Nevada with premium sportswear, streetwear, and uniforms. Serving Las Vegas, Henderson, Reno, and all NV cities.',
        'Massachusetts' => 'Custom apparel manufacturer serving Massachusetts with premium sportswear, streetwear, and uniforms. Serving Boston, Worcester, Springfield, and all MA cities.',
        'Pennsylvania' => 'Custom apparel manufacturer serving Pennsylvania with premium sportswear, streetwear, and uniforms. Serving Philadelphia, Pittsburgh, Allentown, and all PA cities.',
        'Ohio' => 'Custom apparel manufacturer serving Ohio with premium sportswear, streetwear, and uniforms. Serving Columbus, Cleveland, Cincinnati, and all OH cities.',
        'Michigan' => 'Custom apparel manufacturer serving Michigan with premium sportswear, streetwear, and uniforms. Serving Detroit, Grand Rapids, Ann Arbor, and all MI cities.',
        'North Carolina' => 'Custom apparel manufacturer serving North Carolina with premium sportswear, streetwear, and uniforms. Serving Charlotte, Raleigh, Greensboro, and all NC cities.',
        'Tennessee' => 'Custom apparel manufacturer serving Tennessee with premium sportswear, streetwear, and uniforms. Serving Nashville, Memphis, Knoxville, and all TN cities.',
        'Arizona' => 'Custom apparel manufacturer serving Arizona with premium sportswear, streetwear, and uniforms. Serving Phoenix, Tucson, Mesa, and all AZ cities.',
        'Oregon' => 'Custom apparel manufacturer serving Oregon with premium sportswear, streetwear, and uniforms. Serving Portland, Salem, Eugene, and all OR cities.',
        'New Jersey' => 'Custom apparel manufacturer serving New Jersey with premium sportswear, streetwear, and uniforms. Serving Newark, Jersey City, Paterson, and all NJ cities.',
        'Virginia' => 'Custom apparel manufacturer serving Virginia with premium sportswear, streetwear, and uniforms. Serving Virginia Beach, Norfolk, Richmond, and all VA cities.',
        'Minnesota' => 'Custom apparel manufacturer serving Minnesota with premium sportswear, streetwear, and uniforms. Serving Minneapolis, Saint Paul, Rochester, and all MN cities.',
    ];
    return $descriptions[$stateName] ?? "Custom apparel manufacturer serving {$stateName} with premium sportswear, streetwear, workwear, and uniforms. Factory-direct pricing for bulk orders.";
}

/**
 * Get city SEO data
 */
function getCitySEOTitle($cityName, $stateName) {
    return "Apparel Manufacturer in {$cityName}, {$stateName}";
}

function getCitySEODescription($cityName, $stateName) {
    return "Custom apparel manufacturer in {$cityName}, {$stateName}. Premium sportswear, streetwear, workwear, and uniforms. Factory-direct pricing. Fast delivery across {$stateName}.";
}

/**
 * State flag emoji (US states don't have flags, so use a generic one)
 */
function getStateFlag() {
    return '🇺🇸';
}

/**
 * Post parameter safely
 */
function postParam($key, $default = '') {
    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key]) : $default;
}
