<?php
/**
 * Custom Streetwear - Fabrics Page
 */
require_once __DIR__ . '/../includes/functions.php';

$fabrics = dbFetchAll("SELECT * FROM fabrics WHERE status = 1 ORDER BY sort_order");
$metaTags = generateMetaTags('Fabric Collection', 'Explore our extensive fabric collection including cotton fleece, polyester spandex, mesh, interlock, nylon, softshell, wool melton, and more.');
$breadcrumb = [['label' => 'Fabrics']];
include __DIR__ . '/../includes/header.php';
?>

<section style="padding: 60px 0 40px; background: linear-gradient(135deg, var(--color-bg-alt) 0%, var(--color-bg) 100%); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        <div class="section-header" style="text-align: left; margin-bottom: 20px;">
            <span class="section-label">Materials</span>
            <h1 class="section-title" style="font-size: clamp(28px, 4vw, 48px);">Our Fabrics</h1>
            <p class="section-desc" style="margin: 0; max-width: 600px;">We source premium fabrics from trusted suppliers worldwide to ensure the highest quality in every garment.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="fabric-grid">
            <?php foreach ($fabrics as $index => $f): ?>
            <div class="fabric-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s">
                <div class="fabric-card-image">
                    <img src="<?php echo e($f['image'] ?: '/uploads/categories/hoodies.jpg'); ?>" alt="<?php echo e($f['title']); ?>" loading="lazy">
                </div>
                <div class="fabric-card-content">
                    <span class="fabric-card-category"><?php echo e($f['category'] ?: 'Fabric'); ?></span>
                    <h3 class="fabric-card-title"><?php echo e($f['title']); ?></h3>
                    <p class="fabric-card-desc"><?php echo e(truncate($f['description'] ?: 'Content pending: replace from Admin Panel.', 120)); ?></p>
                    <?php if ($f['specs']): ?>
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--color-border); font-size: 12px; color: var(--color-text-muted); font-family: monospace; line-height: 1.8;">
                        <?php echo nl2br(e($f['specs'])); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
