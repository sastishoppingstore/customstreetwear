<?php
/**
 * Custom Streetwear - Dynamic Sitemap Generator
 */
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/xml; charset=UTF-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <url><loc><?php echo SITE_URL; ?>/</loc><changefreq>daily</changefreq><priority>1.0</priority></url>
    <url><loc><?php echo SITE_URL; ?>/about-us</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc><?php echo SITE_URL; ?>/what-we-do</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc><?php echo SITE_URL; ?>/how-we-do</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc><?php echo SITE_URL; ?>/customisations</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc><?php echo SITE_URL; ?>/fabrics</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc><?php echo SITE_URL; ?>/color-charts</loc><changefreq>weekly</changefreq><priority>0.6</priority></url>
    <url><loc><?php echo SITE_URL; ?>/blogs</loc><changefreq>daily</changefreq><priority>0.8</priority></url>
    <url><loc><?php echo SITE_URL; ?>/contact</loc><changefreq>monthly</changefreq><priority>0.9</priority></url>
    <url><loc><?php echo SITE_URL; ?>/sports-uniforms</loc><changefreq>weekly</changefreq><priority>0.9</priority></url>
    <url><loc><?php echo SITE_URL; ?>/locations</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>
    <url><loc><?php echo SITE_URL; ?>/faq</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
    <url><loc><?php echo SITE_URL; ?>/sitemap</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>
    
    <?php
    $cats = dbFetchAll("SELECT name, slug, image, updated_at FROM categories WHERE status = 1");
    foreach ($cats as $c): ?>
    <url><loc><?php echo SITE_URL; ?>/category/<?php echo e($c['slug']); ?></loc><lastmod><?php echo date('Y-m-d', strtotime($c['updated_at'])); ?></lastmod><changefreq>weekly</changefreq><priority>0.7</priority><?php if (!empty($c['image'])): ?><image:image><image:loc><?php echo SITE_URL . e($c['image']); ?></image:loc><image:title><?php echo e($c['name']); ?></image:title></image:image><?php endif; ?></url>
    <?php endforeach; ?>
    
    <?php
    $subcats = dbFetchAll("SELECT s.slug, s.updated_at, c.slug as cat_slug FROM subcategories s JOIN categories c ON s.category_id = c.id WHERE s.status = 1");
    foreach ($subcats as $sc): ?>
    <url><loc><?php echo SITE_URL; ?>/category/<?php echo $sc['cat_slug']; ?>/<?php echo $sc['slug']; ?></loc><lastmod><?php echo date('Y-m-d', strtotime($sc['updated_at'])); ?></lastmod><changefreq>weekly</changefreq><priority>0.5</priority></url>
    <?php endforeach; ?>
    
    <?php
    $prods = dbFetchAll("SELECT title, slug, main_image, updated_at FROM products WHERE status = 1");
    foreach ($prods as $p): ?>
    <url><loc><?php echo SITE_URL; ?>/product/<?php echo e($p['slug']); ?></loc><lastmod><?php echo date('Y-m-d', strtotime($p['updated_at'])); ?></lastmod><changefreq>weekly</changefreq><priority>0.6</priority><?php if (!empty($p['main_image'])): ?><image:image><image:loc><?php echo SITE_URL . e($p['main_image']); ?></image:loc><image:title><?php echo e($p['title']); ?></image:title></image:image><?php endif; ?></url>
    <?php endforeach; ?>
    
    <?php
    $blogs = dbFetchAll("SELECT slug, updated_at FROM blogs WHERE status = 1");
    foreach ($blogs as $b): ?>
    <url><loc><?php echo SITE_URL; ?>/blog/<?php echo $b['slug']; ?></loc><lastmod><?php echo date('Y-m-d', strtotime($b['updated_at'])); ?></lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>
    <?php endforeach; ?>
    
    <?php
    $usaStates = getUSAStates();
    foreach ($usaStates as $slug => $state): ?>
    <url><loc><?php echo SITE_URL; ?>/locations/<?php echo $slug; ?></loc><changefreq>monthly</changefreq><priority>0.6</priority></url>
    <?php
        $cities = getStateCities($slug);
        foreach ($cities as $city): ?>
    <url><loc><?php echo SITE_URL; ?>/locations/<?php echo $slug; ?>/<?php echo $city; ?></loc><changefreq>monthly</changefreq><priority>0.5</priority></url>
        <?php endforeach; ?>
    <?php endforeach; ?>
    
    <?php
    $pages = dbFetchAll("SELECT slug, updated_at FROM pages WHERE status = 1");
    foreach ($pages as $p): ?>
    <url><loc><?php echo SITE_URL; ?>/<?php echo $p['slug']; ?></loc><lastmod><?php echo date('Y-m-d', strtotime($p['updated_at'])); ?></lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>
    <?php endforeach; ?>
    
    <?php
    $customs = dbFetchAll("SELECT slug, updated_at FROM customisations WHERE status = 1");
    foreach ($customs as $cu): ?>
    <url><loc><?php echo SITE_URL; ?>/customisation/<?php echo $cu['slug']; ?></loc><lastmod><?php echo date('Y-m-d', strtotime($cu['updated_at'])); ?></lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
    <?php endforeach; ?>
    
    <?php
    $fabricsList = dbFetchAll("SELECT slug, updated_at FROM fabrics WHERE status = 1");
    foreach ($fabricsList as $f): ?>
    <url><loc><?php echo SITE_URL; ?>/fabric/<?php echo $f['slug']; ?></loc><lastmod><?php echo date('Y-m-d', strtotime($f['updated_at'])); ?></lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>
    <?php endforeach; ?>
</urlset>
