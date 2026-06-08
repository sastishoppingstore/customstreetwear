<?php
/**
 * Custom Streetwear - Generic Page Template
 */

require_once __DIR__ . '/../includes/functions.php';

$slug = $slug ?? '';
$page = dbFetchOne("SELECT * FROM pages WHERE slug = ? AND status = 1", [$slug]);

if (!$page) {
    redirect('/404');
}

$metaTags = generateMetaTags($page['title'], strip_tags($page['short_description'] ?? ''));

$breadcrumb = [
    ['label' => $page['title']]
];

include __DIR__ . '/../includes/header.php';
?>

<section class="section" style="padding-top: 40px;">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        
        <div class="section-header" style="text-align: left; margin-bottom: 40px;">
            <span class="section-label"><?php echo e(getSetting('site_name')); ?></span>
            <h1 class="section-title" style="font-size: clamp(28px, 4vw, 42px);"><?php echo e($page['title']); ?></h1>
            <?php if ($page['short_description']): ?>
            <p class="section-desc" style="margin: 0;"><?php echo e($page['short_description']); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if ($page['banner_image']): ?>
        <div style="margin-bottom: 40px; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--color-border);">
            <img src="<?php echo e($page['banner_image']); ?>" alt="<?php echo e($page['title']); ?>" style="width: 100%; height: auto;">
        </div>
        <?php endif; ?>
        
        <div class="page-content">
            <?php echo $page['content']; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
