<?php
/**
 * Custom Streetwear v2 - Main Router
 * Enhanced with SEO v2, redirects, and new routes
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/seo.php';
require_once __DIR__ . '/includes/seo-v2.php';

// Check redirects first
checkRedirect();

// Handle special endpoints
$route = isset($_GET['route']) ? trim($_GET['route'], '/') : '';

// Sitemap XML
if ($route === 'sitemap.xml.php' || basename($_SERVER['PHP_SELF'] ?? '') === 'sitemap.xml.php') {
    if (getSetting('seo_enable_sitemap', '1') === '1') {
        generateSitemap();
    }
}

// Robots.txt
if ($route === 'robots.txt' || basename($_SERVER['PHP_SELF'] ?? '') === 'robots.txt') {
    if (getSetting('seo_enable_robots', '1') === '1') {
        generateRobots();
    }
}

$segments = explode('/', $route);
$page = $segments[0] ?? '';

if (empty($page)) {
    $page = 'home';
}

$template = null;
$params = [];

switch ($page) {
    case 'home':
        $template = 'home-v2.php';
        break;
        
    case 'about-us':
        $template = 'about-v2.php';
        break;
        
    case 'what-we-do':
        $template = 'what-we-do-v2.php';
        break;
        
    case 'how-we-do':
        $template = 'how-we-do-v2.php';
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
            $template = 'category-v2.php';
        }
        break;
        
    case 'product':
        if (isset($segments[1])) {
            $params['slug'] = $segments[1];
            $template = 'product-detail-v2.php';
        }
        break;
        
    case 'customisations':
        $template = 'customisations-v2.php';
        break;
        
    case 'fabrics':
        $template = 'fabrics-v2.php';
        break;
        
    case 'blogs':
        $template = 'blog-list-v2.php';
        if (isset($segments[1])) {
            $params['slug'] = $segments[1];
            $template = 'blog-detail-v2.php';
        }
        break;
        
    case 'blog':
        if (isset($segments[1])) {
            $params['slug'] = $segments[1];
            $template = 'blog-detail-v2.php';
        }
        break;
        
    case 'contact':
        $template = 'contact-v2.php';
        break;
        
    case 'sports-uniforms':
        $template = 'sports-uniforms-v2.php';
        break;
        
    case 'locations':
        if (isset($segments[2])) {
            $params['state_slug'] = $segments[1];
            $params['city_slug'] = $segments[2];
            $template = 'location-city-v2.php';
        } elseif (isset($segments[1])) {
            $params['state_slug'] = $segments[1];
            $template = 'location-state-v2.php';
        } else {
            $template = 'locations-v2.php';
        }
        break;
        
    case 'market-area':
        $template = 'locations-v2.php';
        break;
        
    case 'faq':
        $template = 'faq-mega.php';
        break;
        
    case 'checkout':
        $template = 'checkout-v2.php';
        break;
        
    case 'privacy-policy':
        $template = 'page.php';
        $params['slug'] = 'privacy-policy';
        break;
        
    case 'return-policy':
        $template = 'page.php';
        $params['slug'] = 'return-policy';
        break;
        
    case 'terms':
        $template = 'page.php';
        $params['slug'] = 'terms';
        break;
        
    case 'sitemap':
        $template = 'sitemap-v2.php';
        break;
        
    default:
        $pageData = dbFetchOne("SELECT * FROM pages WHERE slug = ? AND status = 1", [$page]);
        if ($pageData) {
            $template = 'page.php';
            $params['slug'] = $page;
        } else {
            $template = '404-v2.php';
            http_response_code(404);
        }
}

if (!$template) {
    $template = '404-v2.php';
    http_response_code(404);
}

$templatePath = TEMPLATES_PATH . '/' . $template;
if (!file_exists($templatePath)) {
    $templatePath = TEMPLATES_PATH . '/404-v2.php';
    http_response_code(404);
}

extract($params);
include $templatePath;
