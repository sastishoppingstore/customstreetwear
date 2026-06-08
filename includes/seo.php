<?php
/**
 * Custom Streetwear - SEO & Schema Functions
 */

require_once __DIR__ . '/functions.php';

/**
 * Get SEO meta for entity
 */
function getSeoMeta($entityType, $entityId) {
    return dbFetchOne("SELECT * FROM seo_meta WHERE entity_type = ? AND entity_id = ?", [$entityType, $entityId]);
}

/**
 * Save or update SEO meta
 */
function saveSeoMeta($entityType, $entityId, $data) {
    $existing = getSeoMeta($entityType, $entityId);
    
    if ($existing) {
        dbExecute("UPDATE seo_meta SET meta_title = ?, meta_description = ?, canonical_url = ?, og_title = ?, og_description = ?, og_image = ? WHERE id = ?",
            [$data['meta_title'] ?? null, $data['meta_description'] ?? null, $data['canonical_url'] ?? null, 
             $data['og_title'] ?? null, $data['og_description'] ?? null, $data['og_image'] ?? null, $existing['id']]);
    } else {
        dbInsert("INSERT INTO seo_meta (entity_type, entity_id, meta_title, meta_description, canonical_url, og_title, og_description, og_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$entityType, $entityId, $data['meta_title'] ?? null, $data['meta_description'] ?? null, 
             $data['canonical_url'] ?? null, $data['og_title'] ?? null, $data['og_description'] ?? null, $data['og_image'] ?? null]);
    }
}

/**
 * Generate Organization Schema
 */
function organizationSchema() {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Organization",
        "name" => getSetting('site_name', 'Custom Streetwear'),
        "url" => SITE_URL,
        "logo" => SITE_URL . "/uploads/settings/logo.png",
        "description" => getSetting('seo_description', 'Custom apparel manufacturer serving the USA. Premium sportswear, streetwear, workwear, and uniforms with factory-direct pricing.'),
        "email" => getSetting('site_email', 'info@customstreetwear.co'),
        "telephone" => getSetting('site_phone', ''),
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => "Sialkot",
            "postalCode" => "51310",
            "addressCountry" => "US"
        ],
        "sameAs" => [
            getSetting('facebook_url', ''),
            getSetting('instagram_url', ''),
            getSetting('twitter_url', ''),
            getSetting('youtube_url', ''),
            getSetting('linkedin_url', '')
        ],
        "areaServed" => [
            "@type" => "Country",
            "name" => "United States"
        ]
    ];
    return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

/**
 * Generate LocalBusiness Schema
 */
function localBusinessSchema() {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Organization",
        "name" => getSetting('site_name', 'Custom Streetwear'),
        "image" => SITE_URL . "/uploads/settings/logo.png",
        "url" => SITE_URL,
        "telephone" => getSetting('site_phone', ''),
        "email" => getSetting('site_email', 'info@customstreetwear.co'),
        "description" => "Custom apparel manufacturer serving the USA. Premium sportswear, streetwear, workwear, and uniforms.",
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => "Sialkot",
            "postalCode" => "51310",
            "addressCountry" => "US"
        ],
        "areaServed" => [
            "@type" => "Country",
            "name" => "United States"
        ],
        "openingHoursSpecification" => [
            [
                "@type" => "OpeningHoursSpecification",
                "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                "opens" => "09:00",
                "closes" => "18:00"
            ]
        ]
    ];
    return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

/**
 * Generate Product Schema
 */
function productSchema($product) {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Product",
        "name" => $product['title'],
        "image" => SITE_URL . ($product['main_image'] ?: '/uploads/products/default.jpg'),
        "description" => strip_tags($product['short_description'] ?? ''),
        "sku" => $product['sku'] ?? '',
        "brand" => [
            "@type" => "Brand",
            "name" => getSetting('site_name', 'Custom Streetwear')
        ],
        "manufacturer" => [
            "@type" => "Organization",
            "name" => getSetting('site_name', 'Custom Streetwear')
        ],
        "offers" => [
            "@type" => "Offer",
            "url" => productUrl($product['slug']),
            "availability" => "https://schema.org/InStock",
            "seller" => [
                "@type" => "Organization",
                "name" => getSetting('site_name', 'Custom Streetwear')
            ]
        ]
    ];
    return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

/**
 * Generate BreadcrumbList Schema
 */
function breadcrumbSchema($items) {
    $itemList = [];
    $position = 1;
    
    // Home
    $itemList[] = [
        "@type" => "ListItem",
        "position" => $position++,
        "name" => "Home",
        "item" => SITE_URL
    ];
    
    foreach ($items as $item) {
        $listItem = [
            "@type" => "ListItem",
            "position" => $position++,
            "name" => $item['label']
        ];
        if (isset($item['url']) && $item['url']) {
            $listItem['item'] = $item['url'];
        }
        $itemList[] = $listItem;
    }
    
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "BreadcrumbList",
        "itemListElement" => $itemList
    ];
    return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

/**
 * Generate BlogPosting Schema
 */
function blogPostingSchema($blog) {
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "BlogPosting",
        "headline" => $blog['title'],
        "description" => strip_tags($blog['short_description'] ?? ''),
        "image" => SITE_URL . ($blog['image'] ?: '/uploads/blogs/default.jpg'),
        "url" => blogUrl($blog['slug']),
        "datePublished" => $blog['published_at'] ?? $blog['created_at'],
        "dateModified" => $blog['updated_at'],
        "author" => [
            "@type" => "Organization",
            "name" => getSetting('site_name', 'Custom Streetwear')
        ],
        "publisher" => [
            "@type" => "Organization",
            "name" => getSetting('site_name', 'Custom Streetwear'),
            "logo" => [
                "@type" => "ImageObject",
                "url" => SITE_URL . "/uploads/settings/logo.png"
            ]
        ]
    ];
    return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}
