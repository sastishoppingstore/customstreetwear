<?php
$slug = $slug ?? '';
require_once __DIR__ . '/../includes/functions.php';
$state = getUSAState($slug);
if ($state) {
    header("Location: /locations/" . $slug, true, 301);
} else {
    header("Location: /locations", true, 301);
}
exit;
