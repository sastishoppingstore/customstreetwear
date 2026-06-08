<?php
/**
 * Custom Streetwear - Category Page Template
 */

require_once __DIR__ . '/../includes/functions.php';

$slug = $slug ?? '';
$category = dbFetchOne("SELECT * FROM categories WHERE slug = ? AND status = 1", [$slug]);

if (!$category) {
    include __DIR__ . '/404.php';
    exit;
}

$subcategories = getSubcategories($category['id']);
$products = getProducts(['category_id' => $category['id']], 24);

$metaTags = generateMetaTags($category['seo_title'] ?: $category['name'], $category['seo_description'] ?: $category['description']);

$breadcrumb = [
    ['label' => 'Products', 'url' => '/#categories'],
    ['label' => $category['name']]
];

include __DIR__ . '/../includes/header.php';
?>

<!-- Category Banner -->
<section style="padding: 60px 0 40px; background: linear-gradient(135deg, var(--color-bg-alt) 0%, var(--color-bg) 100%); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        <div style="display: grid; gap: 30px; align-items: center; <?php echo $category['banner_image'] ? 'grid-template-columns: 1fr 1fr;' : ''; ?>">
            <div>
                <span class="section-label">Product Category</span>
                <h1 class="section-title" style="font-size: clamp(28px, 4vw, 48px); margin-bottom: 16px;"><?php echo e($category['name']); ?></h1>
                <?php if ($category['description']): ?>
                <p style="color: var(--color-text-muted); max-width: 600px; line-height: 1.7;"><?php echo e($category['description']); ?></p>
                <?php endif; ?>
            </div>
            <?php if ($category['banner_image']): ?>
            <div style="border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--color-border);">
                <img src="<?php echo e($category['banner_image']); ?>" alt="<?php echo e($category['name']); ?>" style="width: 100%; height: auto;">
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Subcategories -->
<?php if (!empty($subcategories)): ?>
<section class="section" style="padding: 40px 0; background: var(--color-bg-alt);">
    <div class="container">
        <h2 style="font-family: var(--font-display); font-size: 20px; text-transform: uppercase; margin-bottom: 24px;">Subcategories</h2>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <?php foreach ($subcategories as $sub): ?>
            <a href="/category/<?php echo e($category['slug']); ?>/<?php echo e($sub['slug']); ?>" style="display: inline-flex; padding: 10px 20px; background: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 13px; font-weight: 500; transition: var(--transition);" onmouseover="this.style.borderColor='var(--color-accent)'; this.style.color='var(--color-accent)';" onmouseout="this.style.borderColor='var(--color-border)'; this.style.color='var(--color-text)';">
                <?php echo e($sub['name']); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Products -->
<section class="section">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <h2 style="font-family: var(--font-display); font-size: 24px; text-transform: uppercase;"><?php echo e($category['name']); ?> Products</h2>
            <span style="font-size: 13px; color: var(--color-text-muted);"><?php echo count($products); ?> products</span>
        </div>
        
        <?php if (!empty($products)): ?>
        <div class="product-grid">
            <?php foreach ($products as $index => $product): ?>
            <div class="product-card reveal" style="transition-delay: <?php echo ($index % 4) * 0.1; ?>s">
                <div class="product-card-image">
                    <img src="<?php echo e($product['main_image'] ?: '/uploads/products/' . $product['slug'] . '.jpg'); ?>" alt="<?php echo e($product['alt_text'] ?: $product['title']); ?>" loading="lazy">
                    <div class="product-card-overlay">
                        <a href="/product/<?php echo e($product['slug']); ?>" class="product-card-action" title="View Details">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <button class="product-card-action" title="Request Quote" onclick="openQuoteModal()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </button>
                    </div>
                </div>
                <div class="product-card-info">
                    <span class="product-card-category"><?php echo e($product['subcategory_name'] ?: $product['category_name']); ?></span>
                    <h3 class="product-card-title"><?php echo e($product['title']); ?></h3>
                    <span class="product-card-sku"><?php echo e($product['sku']); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div style="text-align: center; padding: 80px 20px; background: var(--color-bg-card); border-radius: var(--radius-lg); border: 1px solid var(--color-border);">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-dim)" stroke-width="1.5" style="margin: 0 auto 16px;"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
            <h3 style="font-family: var(--font-display); font-size: 20px; margin-bottom: 8px;">No Products Yet</h3>
            <p style="color: var(--color-text-muted); margin-bottom: 24px;">Products are being added. Contact us for custom orders.</p>
            <button class="btn btn-primary" onclick="openQuoteModal()">Request a Quote</button>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA -->
<section class="cta-section" style="padding: 60px 0;">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <h2 class="cta-section-title" style="font-size: 32px;">Need Custom <?php echo e($category['name']); ?>?</h2>
            <p class="cta-section-desc">Get in touch for bulk orders, custom designs, and wholesale pricing.</p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary" onclick="openQuoteModal()">Get a Quote</button>
                <a href="/contact" class="btn btn-outline">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
