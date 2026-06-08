<?php
/**
 * Custom Streetwear - Main Router
 * All requests route through this file
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/seo.php';

// Get route
$route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';
$segments = explode('/', $route);
$page = $segments[0] ?? '';

// Set default page
if (empty($page)) {
    $page = 'home';
}

// Route mapping
$template = null;
$params = [];

switch ($page) {
    case 'home':
        $template = 'home.php';
        break;
        
    case 'about-us':
        $template = 'page.php';
        $params['slug'] = 'about-us';
        break;
        
    case 'what-we-do':
        $template = 'page.php';
        $params['slug'] = 'what-we-do';
        break;
        
    case 'how-we-do':
        $template = 'page.php';
        $params['slug'] = 'how-we-do';
        break;
        
    case 'color-charts':
        $template = 'page.php';
        $params['slug'] = 'color-charts';
        break;
        
    case 'brochures':
        $template = 'page.php';
        $params['slug'] = 'brochures';
        break;
        
    case 'category':
        if (isset($segments[1])) {
            $params['slug'] = $segments[1];
            $template = 'category.php';
        }
        break;
        
    case 'product':
        if (isset($segments[1])) {
            $params['slug'] = $segments[1];
            $template = 'product-detail.php';
        }
        break;
        
    case 'customisations':
        $template = 'customisations.php';
        break;
        
    case 'fabrics':
        $template = 'fabrics.php';
        break;
        
    case 'blogs':
        $template = 'blog-list.php';
        if (isset($segments[1])) {
            $params['slug'] = $segments[1];
            $template = 'blog-detail.php';
        }
        break;
        
    case 'blog':
        if (isset($segments[1])) {
            $params['slug'] = $segments[1];
            $template = 'blog-detail.php';
        }
        break;
        
    case 'contact':
        $template = 'contact.php';
        break;
        
    case 'sports-uniforms':
        $template = 'sports-uniforms.php';
        break;
        
    case 'locations':
        if (isset($segments[2])) {
            $params['state_slug'] = $segments[1];
            $params['city_slug'] = $segments[2];
            $template = 'location-city.php';
        } elseif (isset($segments[1])) {
            $params['state_slug'] = $segments[1];
            $template = 'location-state.php';
        } else {
            $template = 'locations.php';
        }
        break;
        
    case 'market-area':
        $template = 'locations.php';
        break;
        
    case 'sitemap':
        $template = 'sitemap.php';
        break;
        
    default:
        // Check if it's a page slug
        $pageData = dbFetchOne("SELECT * FROM pages WHERE slug = ? AND status = 1", [$page]);
        if ($pageData) {
            $template = 'page.php';
            $params['slug'] = $page;
        } else {
            $template = '404.php';
            http_response_code(404);
        }
}

if (!$template) {
    $template = '404.php';
    http_response_code(404);
}

$templatePath = TEMPLATES_PATH . '/' . $template;
if (!file_exists($templatePath)) {
    $templatePath = TEMPLATES_PATH . '/404.php';
    http_response_code(404);
}

// Extract params to variables
extract($params);

// Include template
include $templatePath;
