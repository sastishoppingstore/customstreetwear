<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$slug = $slug ?? '';
$category = dbFetchOne("SELECT * FROM categories WHERE slug = ? AND status = 1", [$slug]);

if (!$category) {
    include __DIR__ . '/404-v2.php';
    exit;
}

$subcategories = getSubcategories($category['id']);
$products = getProducts(['category_id' => $category['id']], 24);

$metaTags = generateAdvancedMetaTags([
    'meta_title' => ($category['seo_title'] ?: $category['name']) . ' - Custom Apparel Manufacturer USA | Custom Streetwear',
    'meta_description' => $category['seo_description'] ?: "Custom " . $category['name'] . " manufacturing in USA. Premium quality, factory-direct pricing. Custom " . strtolower($category['name']) . " for brands, teams & businesses nationwide.",
    'focus_keyword' => 'Custom ' . $category['name'] . ' Manufacturer USA',
]);
$categoryFaqs = [
    [
        'question' => 'Can Custom Streetwear manufacture custom ' . strtolower($category['name']) . ' for USA buyers?',
        'answer' => 'Yes. Custom Streetwear manufactures custom ' . strtolower($category['name']) . ' for U.S. brands, teams, companies, schools, and organizations with factory-direct production support.'
    ],
    [
        'question' => 'Can this category be customized with logos, colors, and labels?',
        'answer' => 'Yes. Available options include custom colors, logos, embroidery, printing, sublimation, labels, packaging, sizing, and cut-and-sew details depending on the product.'
    ],
    [
        'question' => 'Do you support bulk orders for ' . strtolower($category['name']) . '?',
        'answer' => 'Yes. Bulk and repeat production orders are supported with quoting, artwork review, sampling guidance, and delivery across the USA.'
    ]
];
$extraHead = schemaScript([
    '@context' => 'https://schema.org',
    '@type' => 'CollectionPage',
    'name' => 'Custom ' . $category['name'] . ' Manufacturer USA',
    'description' => $category['seo_description'] ?: $category['description'],
    'url' => SITE_URL . '/category/' . $category['slug'],
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => array_map(function ($product, $index) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $product['title'],
                'url' => SITE_URL . '/product/' . $product['slug']
            ];
        }, $products, array_keys($products))
    ]
]);
$extraHead .= schemaScript(faqSchemaFromRows($categoryFaqs, 'Custom ' . $category['name'] . ' Manufacturer USA FAQ'));

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);">
    <div class="container">
        <?php echo advancedBreadcrumb([['label' => 'Products', 'url' => '/#categories'], ['label' => $category['name']]]); ?>
        <div class="reveal">
            <span class="section-label"><?php echo e(getSetting('category_page_label', 'Product Category')); ?></span>
            <h1 style="font-size:clamp(28px,4vw,48px);font-weight:800;margin:8px 0 16px;"><?php echo e($category['name']); ?> - Custom Apparel Manufacturer USA</h1>
            <?php if ($category['description']): ?>
            <p style="color:var(--color-text-muted);max-width:600px;line-height:1.7;"><?php echo e($category['description']); ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if ($subcategories): ?>
<section class="section" style="padding:40px 0;border-bottom:1px solid var(--color-border);">
    <div class="container">
        <h3 style="font-family:var(--font-display);font-size:16px;text-transform:uppercase;margin-bottom:16px;">Subcategories</h3>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <?php foreach ($subcategories as $sub): ?>
            <a href="/category/<?php echo e($category['slug']); ?>?sub=<?php echo e($sub['slug']); ?>" class="btn btn-outline btn-sm"><?php echo e($sub['name']); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
            <p style="font-size:14px;color:var(--color-text-muted);margin:0;"><strong><?php echo count($products); ?></strong> products found</p>
            <div class="urgency-bar" style="margin:0;">
                <span class="urgency-dot"></span>
                <span>Bulk Orders Welcome | Ships Within 15-20 Days</span>
            </div>
        </div>
        <div class="product-grid">
            <?php if (empty($products)): ?>
            <div style="grid-column:1/-1;text-align:center;padding:60px 0;color:var(--color-text-muted);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" opacity="0.3" style="margin:0 auto 16px;display:block;"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <p>No products found in this category yet. <a href="/contact" style="color:var(--color-accent);">Contact us</a> to inquire.</p>
            </div>
            <?php endif; ?>
            <?php foreach ($products as $index => $product): ?>
            <div class="product-card reveal" style="transition-delay:<?php echo $index * 0.1; ?>s;">
                <div class="product-card-image">
                    <img src="<?php echo e($product['main_image'] ?: '/uploads/products/' . $product['slug'] . '.jpg'); ?>" alt="<?php echo e($product['alt_text'] ?: $product['title']); ?>" loading="lazy">
                    <div class="product-card-overlay">
                        <a href="/product/<?php echo e($product['slug']); ?>" class="product-card-action"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                        <button class="product-card-action" onclick="openQuoteModal()"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></button>
                    </div>
                    <?php if ($product['is_best_seller']): ?><span class="product-card-badge">Best Seller</span><?php endif; ?>
                    <?php if ($product['is_featured']): ?><span class="product-card-badge" style="background:var(--color-accent);color:#0a0a0a;">Featured</span><?php endif; ?>
                </div>
                <div class="product-card-info">
                    <span class="product-card-category"><?php echo e($product['category_name']); ?></span>
                    <h3 class="product-card-title"><?php echo e($product['title']); ?></h3>
                    <span class="product-card-sku"><?php echo e($product['sku']); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label">Technical SEO FAQ</span>
            <h2 class="section-title">Custom <?php echo e($category['name']); ?> Manufacturing FAQ</h2>
            <p class="section-desc">Structured answers for buyers and AI search systems evaluating custom <?php echo e(strtolower($category['name'])); ?> manufacturers in the USA.</p>
        </div>
        <div class="faq-section reveal" style="max-width:900px;margin:0 auto;">
            <?php foreach ($categoryFaqs as $faq): ?>
            <div class="faq-item">
                <button class="faq-question" onclick="this.closest('.faq-item').classList.toggle('active');">
                    <?php echo e($faq['question']); ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer"><div class="faq-answer-inner"><?php echo e($faq['answer']); ?></div></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
