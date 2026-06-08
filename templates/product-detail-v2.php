<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$slug = $slug ?? '';
$product = getProduct($slug);

if (!$product) {
    include __DIR__ . '/404-v2.php';
    exit;
}

$images = getProductImages($product['id']);
$related = getRelatedProducts($product['id'], $product['category_id'], 4);

$schemaScript = '<script type="application/ld+json">' . productSchema($product) . '</script>';
$metaTags = generateAdvancedMetaTags([
    'meta_title' => ($product['seo_title'] ?: $product['title']) . ' - Custom Apparel Manufacturer USA',
    'meta_description' => $product['seo_description'] ?: $product['short_description'],
    'focus_keyword' => $product['title'],
]);
$extraHead = $schemaScript;

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section class="section" style="padding-top:40px;">
    <div class="container">
        <?php echo advancedBreadcrumb([['label' => 'Products', 'url' => '/#categories'], ['label' => $product['category_name'], 'url' => '/category/' . $product['category_slug']], ['label' => $product['title']]]); ?>

        <div class="product-detail-grid" style="margin-top:30px;">
            <div class="product-gallery reveal">
                <div class="product-gallery-main">
                    <img id="productMainImage" src="<?php echo e($product['main_image'] ?: '/uploads/products/' . $product['slug'] . '.jpg'); ?>" alt="<?php echo e($product['alt_text'] ?: $product['title']); ?>">
                </div>
                <?php if (!empty($images)): ?>
                <div class="product-gallery-thumbs">
                    <div class="gallery-thumb active" onclick="switchGalleryImage(this, '<?php echo e($product['main_image'] ?: '/uploads/products/' . $product['slug'] . '.jpg'); ?>')">
                        <img src="<?php echo e($product['main_image'] ?: '/uploads/products/' . $product['slug'] . '.jpg'); ?>" alt="<?php echo e($product['title']); ?>">
                    </div>
                    <?php foreach ($images as $img): ?>
                    <div class="gallery-thumb" onclick="switchGalleryImage(this, '<?php echo e($img['image']); ?>')">
                        <img src="<?php echo e($img['image']); ?>" alt="<?php echo e($img['alt_text'] ?: $product['title']); ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="product-info-detail reveal">
                <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:8px;">
                    <span class="section-label" style="margin:0;"><?php echo e($product['category_name']); ?></span>
                    <?php if ($product['is_best_seller']): ?><span class="product-card-badge" style="position:static;">Best Seller</span><?php endif; ?>
                </div>
                <h1 style="font-size:clamp(24px,3vw,36px);line-height:1.2;"><?php echo e($product['title']); ?></h1>
                <div class="product-sku-detail" style="margin-bottom:16px;">SKU: <?php echo e($product['sku']); ?></div>

                <div class="product-short-desc" style="color:var(--color-text-muted);line-height:1.7;margin-bottom:20px;">
                    <?php echo nl2br(e($product['short_description'])); ?>
                </div>

                <?php if ($product['specifications']): ?>
                <div class="product-specs-list" style="margin-bottom:20px;">
                    <?php foreach (explode("\n", $product['specifications']) as $spec): ?>
                    <?php $parts = explode(':', $spec, 2); if (count($parts) == 2): ?>
                    <div class="product-spec-item"><span class="product-spec-label"><?php echo e(trim($parts[0])); ?>:</span><span class="product-spec-value"><?php echo e(trim($parts[1])); ?></span></div>
                    <?php endif; endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($product['sizes']): ?>
                <div style="margin-bottom:16px;">
                    <span class="form-label">Available Sizes</span>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:6px;">
                        <?php foreach (explode(',', $product['sizes']) as $size): ?>
                        <span style="padding:6px 14px;background:var(--color-bg-alt);border:1px solid var(--color-border);border-radius:var(--radius-sm);font-size:13px;"><?php echo e(trim($size)); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($product['colors']): ?>
                <div style="margin-bottom:20px;">
                    <span class="form-label">Available Colors</span>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:6px;">
                        <?php foreach (explode(',', $product['colors']) as $color): ?>
                        <span style="padding:6px 14px;background:var(--color-bg-alt);border:1px solid var(--color-border);border-radius:var(--radius-sm);font-size:13px;"><?php echo e(trim($color)); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="urgency-bar" style="margin-bottom:20px;display:inline-flex;">
                    <span class="urgency-dot"></span>
                    <span>Bulk Orders Welcome | Factory-Direct Pricing</span>
                </div>

                <div class="product-actions-detail" style="display:flex;gap:12px;flex-wrap:wrap;">
                    <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Request Quote
                    </button>
                    <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', getSetting('whatsapp_button_number', ''))); ?>?text=Hi, I am interested in <?php echo urlencode($product['title']); ?>" class="btn btn-outline btn-lg" target="_blank" rel="noopener">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <div class="product-tabs" style="margin-top:60px;">
            <div class="product-tabs-nav">
                <button class="product-tab-btn active" data-tab="tab-description">Description</button>
                <?php if ($product['features']): ?>
                <button class="product-tab-btn" data-tab="tab-features">Features</button>
                <?php endif; ?>
                <?php if ($product['customization_options']): ?>
                <button class="product-tab-btn" data-tab="tab-customization">Customization</button>
                <?php endif; ?>
            </div>
            <div class="product-tab-panel active" id="tab-description"><div class="page-content"><?php echo $product['full_description'] ?: '<p>' . e($product['short_description']) . '</p>'; ?></div></div>
            <?php if ($product['features']): ?>
            <div class="product-tab-panel" id="tab-features"><div class="page-content"><ul><?php foreach (explode(',', $product['features']) as $feature): ?><li><?php echo e(trim($feature)); ?></li><?php endforeach; ?></ul></div></div>
            <?php endif; ?>
            <?php if ($product['customization_options']): ?>
            <div class="product-tab-panel" id="tab-customization"><div class="page-content"><p><?php echo nl2br(e($product['customization_options'])); ?></p></div></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!empty($related)): ?>
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('product_related_label', 'Related Products')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('product_related_title', 'You May Also Like')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('product_related_desc', 'Explore more custom apparel products from our collection.')); ?></p>
        </div>
        <div class="product-grid">
            <?php foreach ($related as $r): ?>
            <div class="product-card reveal">
                <div class="product-card-image">
                    <img src="<?php echo e($r['main_image'] ?: '/uploads/products/' . $r['slug'] . '.jpg'); ?>" alt="<?php echo e($r['title']); ?>" loading="lazy">
                    <div class="product-card-overlay">
                        <a href="/product/<?php echo e($r['slug']); ?>" class="product-card-action"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                        <button class="product-card-action" onclick="openQuoteModal()"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></button>
                    </div>
                </div>
                <div class="product-card-info">
                    <span class="product-card-category"><?php echo e($r['category_name']); ?></span>
                    <h3 class="product-card-title"><?php echo e($r['title']); ?></h3>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
function switchGalleryImage(thumb, src) {
    document.getElementById('productMainImage').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
