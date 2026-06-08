<?php
/**
 * Custom Streetwear - Advanced SEO Functions v2
 * RankMath-like functionality
 */

require_once __DIR__ . '/functions.php';

/**
 * Generate advanced meta tags with per-page control
 */
function generateAdvancedMetaTags($data = []) {
    $siteName = getSetting('site_name', 'Custom Streetwear');
    $defaultTitle = getSetting('seo_title', DEFAULT_SEO_TITLE);
    $defaultDesc = getSetting('seo_description', DEFAULT_SEO_DESC);
    $defaultOgImage = getSetting('og_image', '/uploads/settings/og-image.jpg');
    
    $title = $data['meta_title'] ?? $data['seo_title'] ?? $data['title'] ?? '';
    $desc = $data['meta_description'] ?? $data['seo_description'] ?? '';
    $ogTitle = $data['og_title'] ?? $title;
    $ogDesc = $data['og_description'] ?? $desc;
    $ogImage = $data['og_image'] ?? $defaultOgImage;
    $canonical = $data['canonical_url'] ?? SITE_URL . ($_SERVER['REQUEST_URI'] ?? '/');
    $robots = $data['robots_meta'] ?? 'index,follow';
    $focusKw = $data['focus_keyword'] ?? '';
    $pageType = $data['og_type'] ?? 'website';
    
    $metaTitle = $title ? $title . ' | ' . $siteName : $defaultTitle;
    $metaDesc = $desc ?: $defaultDesc;
    
    $html = '<!-- Advanced SEO v2 -->' . "\n";
    $html .= '<title>' . e($metaTitle) . '</title>' . "\n";
    $html .= '<meta name="description" content="' . e($metaDesc) . '">' . "\n";
    $html .= '<meta name="robots" content="' . e($robots) . '">' . "\n";
    $html .= '<link rel="canonical" href="' . e($canonical) . '">' . "\n";
    
    // OpenGraph
    $html .= '<meta property="og:title" content="' . e($ogTitle ?: $metaTitle) . '">' . "\n";
    $html .= '<meta property="og:description" content="' . e($ogDesc ?: $metaDesc) . '">' . "\n";
    $html .= '<meta property="og:image" content="' . e($ogImage ? (strpos($ogImage, 'http') === 0 ? $ogImage : SITE_URL . $ogImage) : SITE_URL . $defaultOgImage) . '">' . "\n";
    $html .= '<meta property="og:url" content="' . e($canonical) . '">' . "\n";
    $html .= '<meta property="og:type" content="' . e($pageType) . '">' . "\n";
    $html .= '<meta property="og:site_name" content="' . e($siteName) . '">' . "\n";
    
    // Twitter Card
    $twHandle = getSetting('seo_twitter_handle', '@customstreetwear');
    $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
    $html .= '<meta name="twitter:title" content="' . e($ogTitle ?: $metaTitle) . '">' . "\n";
    $html .= '<meta name="twitter:description" content="' . e($ogDesc ?: $metaDesc) . '">' . "\n";
    $html .= '<meta name="twitter:image" content="' . e($ogImage ? (strpos($ogImage, 'http') === 0 ? $ogImage : SITE_URL . $ogImage) : SITE_URL . $defaultOgImage) . '">' . "\n";
    if ($twHandle) {
        $html .= '<meta name="twitter:site" content="' . e($twHandle) . '">' . "\n";
    }
    
    // Verification codes
    $gVer = getSetting('seo_google_verification', '');
    if ($gVer) $html .= '<meta name="google-site-verification" content="' . e($gVer) . '">' . "\n";
    $bVer = getSetting('seo_bing_verification', '');
    if ($bVer) $html .= '<meta name="msvalidate.01" content="' . e($bVer) . '">' . "\n";
    
    // Focus keyword meta
    if ($focusKw) {
        $html .= '<meta name="keywords" content="' . e($focusKw) . '">' . "\n";
    }
    
    // Facebook page ID
    $fbId = getSetting('seo_facebook_page_id', '');
    if ($fbId) {
        $html .= '<meta property="fb:app_id" content="' . e($fbId) . '">' . "\n";
    }
    
    return $html;
}

/**
 * Render a JSON-LD script tag.
 */
function schemaScript($schema) {
    return '<script type="application/ld+json">' . "\n" .
        json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) .
        "\n" . '</script>' . "\n";
}

/**
 * Service schema for high-intent manufacturing landing pages.
 */
function apparelServiceSchema($name, $description, $url, $areaServed = 'United States') {
    return [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $name,
        'description' => $description,
        'url' => $url,
        'provider' => [
            '@type' => 'Organization',
            'name' => getSetting('site_name', 'Custom Streetwear'),
            'url' => SITE_URL,
            'telephone' => getSetting('site_phone', ''),
            'email' => getSetting('site_email', 'info@customstreetwear.co')
        ],
        'serviceType' => 'Custom apparel manufacturing',
        'areaServed' => [
            '@type' => is_array($areaServed) ? ($areaServed['type'] ?? 'Place') : 'Country',
            'name' => is_array($areaServed) ? ($areaServed['name'] ?? 'United States') : $areaServed
        ],
        'offers' => [
            '@type' => 'Offer',
            'availability' => 'https://schema.org/InStock',
            'priceCurrency' => 'USD',
            'description' => 'Factory-direct custom apparel quotes for USA teams, brands, schools, and businesses.'
        ]
    ];
}

/**
 * Build FAQPage schema from database FAQ rows.
 */
function faqSchemaFromRows($faqs, $name = 'Frequently Asked Questions') {
    $items = [];
    foreach ($faqs as $faq) {
        if (empty($faq['question']) || empty($faq['answer'])) continue;
        $items[] = [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => trim(strip_tags($faq['answer']))
            ]
        ];
    }
    return [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'name' => $name,
        'mainEntity' => $items
    ];
}

/**
 * Generate WebSite schema
 */
function websiteSchema() {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "WebSite",
        "name" => getSetting('site_name', 'Custom Streetwear'),
        "url" => SITE_URL,
        "description" => getSetting('seo_description', DEFAULT_SEO_DESC),
        "potentialAction" => [
            "@type" => "SearchAction",
            "target" => SITE_URL . "/?route=search&q={search_term_string}",
            "query-input" => "required name=search_term_string"
        ]
    ];
    return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

/**
 * Generate complete breadcrumb with schema
 */
function advancedBreadcrumb($items) {
    $separator = getSetting('seo_breadcrumb_separator', '/');
    $html = '<nav class="breadcrumb" aria-label="Breadcrumb"><ol itemscope itemtype="https://schema.org/BreadcrumbList">';
    $position = 1;
    
    $html .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    $html .= '<a href="' . SITE_URL . '" itemprop="item"><span itemprop="name">Home</span></a>';
    $html .= '<meta itemprop="position" content="' . $position . '">';
    $html .= '<span class="breadcrumb-sep">' . $separator . '</span></li>';
    $position++;
    
    foreach ($items as $item) {
        $html .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        if (isset($item['url']) && $item['url']) {
            $html .= '<a href="' . $item['url'] . '" itemprop="item"><span itemprop="name">' . e($item['label']) . '</span></a>';
        } else {
            $html .= '<span itemprop="name" aria-current="page">' . e($item['label']) . '</span>';
        }
        $html .= '<meta itemprop="position" content="' . $position . '">';
        $html .= '</li>';
        $position++;
    }
    
    $html .= '</ol></nav>';
    return $html;
}

/**
 * Check if redirect exists and redirect
 */
function checkRedirect() {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($requestUri, PHP_URL_PATH);
    $redirect = dbFetchOne("SELECT * FROM redirects WHERE old_url = ? AND status = 1", [$path]);
    if ($redirect) {
        dbExecute("UPDATE redirects SET hits = hits + 1 WHERE id = ?", [$redirect['id']]);
        $status = $redirect['redirect_type'] === '302' ? 302 : 301;
        header("Location: " . $redirect['new_url'], true, $status);
        exit;
    }
}

/**
 * Generate sitemap XML
 */
function generateSitemap() {
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    
    // Homepage
    echo '  <url><loc>' . SITE_URL . '</loc><priority>1.0</priority><changefreq>daily</changefreq></url>' . "\n";
    
    // Pages
    $pages = dbFetchAll("SELECT slug, updated_at FROM pages WHERE status=1 ORDER BY slug");
    foreach ($pages as $p) {
        echo '  <url><loc>' . SITE_URL . '/' . e($p['slug']) . '</loc><priority>0.8</priority><changefreq>monthly</changefreq><lastmod>' . date('c', strtotime($p['updated_at'])) . '</lastmod></url>' . "\n";
    }
    
    // Categories
    $cats = dbFetchAll("SELECT slug, updated_at FROM categories WHERE status=1 ORDER BY slug");
    foreach ($cats as $c) {
        echo '  <url><loc>' . SITE_URL . '/category/' . e($c['slug']) . '</loc><priority>0.7</priority><changefreq>weekly</changefreq><lastmod>' . date('c', strtotime($c['updated_at'])) . '</lastmod></url>' . "\n";
    }
    
    // Products
    $prods = dbFetchAll("SELECT slug, updated_at FROM products WHERE status=1 ORDER BY slug");
    foreach ($prods as $p) {
        echo '  <url><loc>' . SITE_URL . '/product/' . e($p['slug']) . '</loc><priority>0.6</priority><changefreq>weekly</changefreq><lastmod>' . date('c', strtotime($p['updated_at'])) . '</lastmod></url>' . "\n";
    }
    
    // Blogs
    $blogs = dbFetchAll("SELECT slug, updated_at FROM blogs WHERE status=1 ORDER BY slug");
    foreach ($blogs as $b) {
        echo '  <url><loc>' . SITE_URL . '/blog/' . e($b['slug']) . '</loc><priority>0.6</priority><changefreq>monthly</changefreq><lastmod>' . date('c', strtotime($b['updated_at'])) . '</lastmod></url>' . "\n";
    }
    
    // Static routes
    $staticRoutes = ['about-us', 'what-we-do', 'how-we-do', 'color-charts', 'brochures', 'customisations', 'fabrics', 'sports-uniforms', 'faq', 'contact', 'privacy-policy', 'return-policy', 'terms'];
    foreach ($staticRoutes as $route) {
        echo '  <url><loc>' . SITE_URL . '/' . $route . '</loc><priority>0.5</priority><changefreq>monthly</changefreq></url>' . "\n";
    }
    
    // Locations
    $states = getUSAStates();
    foreach ($states as $slug => $state) {
        echo '  <url><loc>' . SITE_URL . '/locations/' . $slug . '</loc><priority>0.5</priority><changefreq>monthly</changefreq></url>' . "\n";
        $cities = getStateCities($slug);
        foreach ($cities as $city) {
            echo '  <url><loc>' . SITE_URL . '/locations/' . $slug . '/' . $city . '</loc><priority>0.4</priority><changefreq>monthly</changefreq></url>' . "\n";
        }
    }
    
    echo '</urlset>';
    exit;
}

/**
 * Generate robots.txt
 */
function generateRobots() {
    header('Content-Type: text/plain; charset=utf-8');
    $custom = getSetting('seo_robots_custom', '');
    if ($custom) {
        echo $custom . "\n";
    } else {
        echo "User-agent: *\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /includes/\n";
        echo "Disallow: /config/\n";
        echo "Disallow: /api/\n";
        echo "Disallow: /uploads/\n";
        echo "Allow: /uploads/sliders/\n";
        echo "Allow: /uploads/products/\n";
        echo "Allow: /uploads/categories/\n";
        echo "Allow: /uploads/blogs/\n";
        echo "Allow: /uploads/brochures/\n";
        echo "\n";
        echo "Sitemap: " . SITE_URL . "/sitemap.xml.php\n";
    }
    exit;
}

/**
 * Get SEO data for an entity
 */
function getEntitySeoData($type, $id) {
    $tableMap = [
        'page' => 'pages', 'product' => 'products', 
        'category' => 'categories', 'blog' => 'blogs',
        'subcategory' => 'subcategories'
    ];
    $table = $tableMap[$type] ?? null;
    if (!$table) return [];
    $data = dbFetchOne("SELECT seo_title, seo_description, focus_keyword, robots_meta, canonical_url, og_title, og_description, og_image FROM {$table} WHERE id = ?", [$id]);
    return $data ?: [];
}

/**
 * Render GTM head code
 */
function gtmHead() {
    $code = getSetting('seo_gtm_head', '');
    if ($code) return $code . "\n";
    return '';
}

/**
 * Render GTM body code
 */
function gtmBody() {
    $code = getSetting('seo_gtm_body', '');
    if ($code) return $code . "\n";
    return '';
}

/**
 * Render Facebook Pixel
 */
function facebookPixel() {
    $code = getSetting('seo_facebook_pixel', '');
    if ($code) return $code . "\n";
    return '';
}

/**
 * Render TikTok Pixel
 */
function tiktokPixel() {
    $code = getSetting('seo_tiktok_pixel', '');
    if ($code) return $code . "\n";
    return '';
}

/**
 * Render additional admin-managed head code.
 */
function customHeadCode() {
    $code = getSetting('seo_custom_head_code', '');
    if ($code) return $code . "\n";
    return '';
}

/**
 * Render additional admin-managed footer/body code.
 */
function customBodyCode() {
    $code = getSetting('seo_custom_body_code', '');
    if ($code) return $code . "\n";
    return '';
}

/**
 * Get advanced SEO description for location pages
 */
function getLocationMetaDescription($type, $stateName, $cityName = null) {
    $siteName = getSetting('site_name', 'Custom Streetwear');
    if ($type === 'state') {
        return "{$siteName} is a premier custom apparel manufacturer serving {$stateName}. Custom sportswear, streetwear, workwear, and uniforms. Factory-direct pricing, fast delivery across {$stateName}. Serving all major cities in {$stateName}.";
    }
    if ($type === 'city') {
        return "{$siteName} is a trusted custom apparel manufacturer in {$cityName}, {$stateName}. Premium custom sportswear, streetwear, workwear and uniforms with factory-direct pricing. Serving {$cityName} and surrounding areas.";
    }
    return '';
}

/**
 * Render trust signals (psychology-based)
 */
function renderTrustSignals() {
    $reviews = dbFetchAll("SELECT * FROM site_reviews WHERE status=1 ORDER BY sort_order LIMIT 5");
    $totalReviews = dbFetchOne("SELECT COUNT(*) as c FROM site_reviews WHERE status=1")['c'] ?? 0;
    $avgRating = dbFetchOne("SELECT ROUND(AVG(rating), 1) as r FROM site_reviews WHERE status=1")['r'] ?? 4.9;
    
    $html = '<div class="trust-signals">';
    
    // Rating display
    if ($totalReviews > 0) {
        $html .= '<div class="trust-rating">';
        $html .= '<div class="trust-stars">';
        for ($i = 1; $i <= 5; $i++) {
            $fill = $i <= floor($avgRating) ? 'fill="currentColor"' : 'fill="none"';
            $html .= '<svg width="16" height="16" viewBox="0 0 24 24" ' . $fill . ' stroke="currentColor" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>';
        }
        $html .= '</div>';
        $html .= '<span class="trust-rating-text">' . $avgRating . ' average from ' . $totalReviews . ' reviews</span>';
        $html .= '</div>';
    }
    
    // Urgency/scarcity
    $html .= '<div class="trust-urgency"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><span>Typically ships within 15-20 business days</span></div>';
    
    // Satisfaction
    $badge = getSetting('site_satisfaction_badge', '100% Satisfaction Guaranteed');
    $html .= '<div class="trust-badge"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg><span>' . e($badge) . '</span></div>';
    
    // Payment methods
    $html .= '<div class="trust-payments">';
    $methods = ['visa' => 'Visa', 'mastercard' => 'Mastercard', 'amex' => 'Amex', 'paypal' => 'PayPal'];
    foreach ($methods as $key => $label) {
        $html .= '<span class="payment-icon" title="' . $label . '">' . strtoupper(substr($label, 0, 2)) . '</span>';
    }
    $html .= '</div>';
    
    $html .= '</div>';
    return $html;
}

/**
 * Get first-look psychology elements
 */
function renderFirstLookElements() {
    $html = '';
    
    // Social proof counter
    $clients = getSetting('home_stat_clients_number', '2500');
    $html .= '<div class="first-look-badge">';
    $html .= '<span class="fl-badge-icon">★</span>';
    $html .= '<span class="fl-badge-text">Trusted by <strong>' . number_format(intval($clients)) . '+</strong> USA Brands</span>';
    $html .= '</div>';
    
    // Urgency bar
    $urgentText = getSetting('home_urgent_badge_text', 'Bulk Orders Welcome | Ships Within 15-20 Days');
    $html .= '<div class="urgency-bar">';
    $html .= '<span class="urgency-dot"></span>';
    $html .= '<span>' . e($urgentText) . '</span>';
    $html .= '</div>';
    
    return $html;
}
