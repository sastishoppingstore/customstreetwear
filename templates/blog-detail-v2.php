<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$slug = $slug ?? '';
$blog = dbFetchOne("SELECT * FROM blogs WHERE slug = ? AND status = 1", [$slug]);

if (!$blog) {
    include __DIR__ . '/404-v2.php';
    exit;
}

$metaTags = generateAdvancedMetaTags([
    'meta_title' => $blog['seo_title'] ?: ($blog['title'] . ' - Custom Apparel Manufacturing Blog | Custom Streetwear'),
    'meta_description' => $blog['seo_description'] ?: strip_tags($blog['short_description'] ?: $blog['content']),
    'focus_keyword' => $blog['title'],
    'og_type' => 'article',
]);

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);">
    <div class="container">
        <?php echo advancedBreadcrumb([['label' => 'Blog', 'url' => '/blogs'], ['label' => $blog['title']]]); ?>
        <div class="reveal" style="max-width:800px;">
            <span class="section-label" style="margin-bottom:8px;display:inline-block;"><?php echo e($blog['category'] ?: 'News'); ?></span>
            <h1 style="font-size:clamp(26px,4vw,42px);font-weight:800;margin-bottom:12px;"><?php echo e($blog['title']); ?></h1>
            <div style="display:flex;gap:16px;align-items:center;font-size:13px;color:var(--color-text-muted);">
                <span><?php echo formatDate($blog['published_at'] ?: $blog['created_at']); ?></span>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="max-width:800px;margin:0 auto;">
            <?php if ($blog['image']): ?>
            <div style="border-radius:var(--radius-lg);overflow:hidden;border:1px solid var(--color-border);margin-bottom:40px;">
                <img src="<?php echo e($blog['image']); ?>" alt="<?php echo e($blog['alt_text'] ?: $blog['title']); ?>" style="width:100%;height:auto;display:block;">
            </div>
            <?php endif; ?>
            <?php if ($blog['short_description']): ?>
            <p style="font-size:18px;color:var(--color-text-muted);line-height:1.7;margin-bottom:30px;"><?php echo e($blog['short_description']); ?></p>
            <?php endif; ?>
            <div class="page-content">
                <?php echo $blog['content']; ?>
            </div>
            <div style="margin-top:40px;padding-top:30px;border-top:1px solid var(--color-border);">
                <a href="/blogs" class="btn btn-outline">&larr; Back to Blog</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
