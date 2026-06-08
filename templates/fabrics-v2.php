<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$fabrics = dbFetchAll("SELECT * FROM fabrics WHERE status = 1 ORDER BY sort_order");

$metaTags = generateAdvancedMetaTags([
    'meta_title' => 'Fabric Collection - Custom Apparel Materials USA | Custom Streetwear',
    'meta_description' => 'Explore our premium fabric collection for custom apparel manufacturing. Cotton fleece, polyester spandex, mesh, interlock, nylon, softshell, wool melton and more.',
    'focus_keyword' => 'Custom Apparel Fabric Collection USA',
]);

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30%;right:-8%;width:450px;height:450px;background:radial-gradient(circle,rgba(57,255,20,0.04),transparent);border-radius:50%;"></div>
    <div class="container" style="position:relative;z-index:1;">
        <?php echo advancedBreadcrumb([['label' => 'Fabrics']]); ?>
        <div class="reveal">
            <span class="section-label" style="margin-bottom:12px;display:block;"><?php echo e(getSetting('fabrics_page_label', 'Materials')); ?></span>
            <h1 style="font-size:clamp(32px,5vw,52px);font-weight:800;margin-bottom:16px;"><?php echo e(getSetting('fabrics_page_title', 'Premium Fabric Collection for Custom Apparel')); ?></h1>
            <p style="font-size:18px;color:var(--color-text-muted);max-width:720px;line-height:1.6;"><?php echo e(getSetting('fabrics_page_desc', 'We source premium fabrics from trusted USA suppliers to ensure exceptional quality, durability, and comfort in every garment we manufacture.')); ?></p>
        </div>
    </div>
</section>

<?php if (getSetting('fabrics_trust_bar_enabled', '1') === '1'): ?>
<section style="border-bottom:1px solid var(--color-border);"><div class="trust-bar"><div class="container"><div class="trust-bar-inner">
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>Trusted by 2500+ USA Brands</span>
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Ships Within 15-20 Days</span>
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>100% Quality Guaranteed</span>
    <span class="trust-bar-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>Factory-Direct Pricing</span>
</div></div></div></section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label"><?php echo e(getSetting('fabrics_grid_label', 'Our Collection')); ?></span>
            <h2 class="section-title"><?php echo e(getSetting('fabrics_grid_title', 'Premium Quality Fabrics')); ?></h2>
            <p class="section-desc"><?php echo e(getSetting('fabrics_grid_desc', 'We use only the highest quality fabrics sourced from trusted USA suppliers.')); ?></p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:24px;">
            <?php foreach ($fabrics as $index => $f): ?>
            <div class="glass-card reveal" style="transition-delay:<?php echo $index * 0.1; ?>s;">
                <div style="width:100%;height:160px;border-radius:var(--radius-sm);overflow:hidden;margin-bottom:16px;border:1px solid var(--color-border);">
                    <img src="<?php echo e($f['image'] ?: '/uploads/categories/hoodies.jpg'); ?>" alt="<?php echo e($f['title']); ?>" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
                </div>
                <span style="font-size:11px;color:var(--color-accent);text-transform:uppercase;letter-spacing:1px;"><?php echo e($f['category'] ?: 'Fabric'); ?></span>
                <h3 class="glass-card-title" style="margin-top:4px;"><?php echo e($f['title']); ?></h3>
                <p class="glass-card-text"><?php echo e(truncate($f['description'] ?: 'High-quality fabric for custom apparel manufacturing.', 150)); ?></p>
                <?php if ($f['specs']): ?>
                <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--color-border);font-size:12px;color:var(--color-text-muted);font-family:monospace;line-height:1.8;">
                    <?php echo nl2br(e($f['specs'])); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label"><?php echo e(getSetting('fabrics_cta_label', 'Need Help Choosing?')); ?></span>
            <h2 class="cta-section-title"><?php echo e(getSetting('fabrics_cta_title', 'Not Sure Which Fabric to Choose?')); ?></h2>
            <p class="cta-section-desc"><?php echo e(getSetting('fabrics_cta_desc', 'Our team will help you select the perfect fabric for your custom apparel project. Free consultation.')); ?></p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()"><?php echo e(getSetting('fabrics_cta_btn1', 'Get Free Consultation')); ?></button>
                <a href="/contact" class="btn btn-outline btn-lg"><?php echo e(getSetting('fabrics_cta_btn2', 'Contact Us')); ?></a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
