<?php
/**
 * Custom Streetwear - Sitemap Page
 */
require_once __DIR__ . '/../includes/functions.php';

$pages = dbFetchAll("SELECT title, slug FROM pages WHERE status = 1 ORDER BY sort_order");
$categories = dbFetchAll("SELECT name, slug FROM categories WHERE status = 1 ORDER BY name");
$subcategories = dbFetchAll("SELECT s.name, s.slug, c.slug as cat_slug FROM subcategories s JOIN categories c ON s.category_id = c.id WHERE s.status = 1 ORDER BY s.name");
$products = dbFetchAll("SELECT title, slug FROM products WHERE status = 1 ORDER BY title LIMIT 100");
$blogs = dbFetchAll("SELECT title, slug FROM blogs WHERE status = 1 ORDER BY title");
$usaStates = getUSAStates();
$customisations = dbFetchAll("SELECT title, slug FROM customisations WHERE status = 1 ORDER BY title");

$metaTags = generateMetaTags('Sitemap', 'Browse all pages, products, categories, and market areas on Custom Streetwear.');
$breadcrumb = [['label' => 'Sitemap']];
include __DIR__ . '/../includes/header.php';
?>

<section class="section" style="padding-top: 40px;">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        <div class="section-header" style="text-align: left; margin-bottom: 40px;">
            <span class="section-label">Navigation</span>
            <h1 class="section-title" style="font-size: clamp(28px, 4vw, 48px);">Sitemap</h1>
        </div>
        
        <div class="sitemap-section">
            <h2 class="sitemap-title">Main Pages</h2>
            <div class="sitemap-list">
                <a href="/">Home</a>
                <a href="/about-us">About Us</a>
                <a href="/what-we-do">What We Do</a>
                <a href="/how-we-do">How We Do</a>
                <a href="/customisations">Customisations</a>
                <a href="/fabrics">Fabrics</a>
                <a href="/color-charts">Color Charts</a>
                <a href="/blogs">Blogs</a>
                <a href="/contact">Contact Us</a>
                <a href="/sports-uniforms">Sports Uniforms</a>
                <a href="/locations">USA Locations</a>
                <?php foreach ($pages as $p): ?>
                <a href="/<?php echo e($p['slug']); ?>"><?php echo e($p['title']); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="sitemap-section">
            <h2 class="sitemap-title">Product Categories</h2>
            <div class="sitemap-list">
                <?php foreach ($categories as $c): ?>
                <a href="/category/<?php echo e($c['slug']); ?>"><?php echo e($c['name']); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="sitemap-section">
            <h2 class="sitemap-title">Subcategories</h2>
            <div class="sitemap-list">
                <?php foreach ($subcategories as $s): ?>
                <a href="/category/<?php echo e($s['cat_slug']); ?>/<?php echo e($s['slug']); ?>"><?php echo e($s['name']); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="sitemap-section">
            <h2 class="sitemap-title">Products</h2>
            <div class="sitemap-list">
                <?php foreach ($products as $p): ?>
                <a href="/product/<?php echo e($p['slug']); ?>"><?php echo e(truncate($p['title'], 50)); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="sitemap-section">
            <h2 class="sitemap-title">USA Locations</h2>
            <div class="sitemap-list">
                <?php foreach ($usaStates as $slug => $state): ?>
                <a href="/locations/<?php echo e($slug); ?>"><?php echo e($state['name']); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="sitemap-section">
            <h2 class="sitemap-title">Blog</h2>
            <div class="sitemap-list">
                <?php foreach ($blogs as $b): ?>
                <a href="/blog/<?php echo e($b['slug']); ?>"><?php echo e(truncate($b['title'], 50)); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
