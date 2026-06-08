<?php
/**
 * Custom Streetwear - Blog Detail Template
 */
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo.php';

$slug = $slug ?? '';
$blog = dbFetchOne("SELECT * FROM blogs WHERE slug = ? AND status = 1", [$slug]);

if (!$blog) {
    include __DIR__ . '/404.php';
    exit;
}

$related = dbFetchAll("SELECT * FROM blogs WHERE status = 1 AND id != ? ORDER BY published_at DESC LIMIT 3", [$blog['id']]);
$schemaScript = '<script type="application/ld+json">' . blogPostingSchema($blog) . '</script>';
$metaTags = generateMetaTags($blog['seo_title'] ?: $blog['title'], $blog['seo_description'] ?: strip_tags($blog['short_description'] ?: ''));
$extraHead = $schemaScript;

$breadcrumb = [
    ['label' => 'Blogs', 'url' => '/blogs'],
    ['label' => truncate($blog['title'], 40)]
];

include __DIR__ . '/../includes/header.php';
?>

<section class="section" style="padding-top: 40px;">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        
        <article>
            <?php if ($blog['image']): ?>
            <div style="border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--color-border); margin-bottom: 40px;">
                <img src="<?php echo e($blog['image']); ?>" alt="<?php echo e($blog['alt_text'] ?: $blog['title']); ?>" style="width: 100%; height: auto;">
            </div>
            <?php endif; ?>
            
            <div style="max-width: 900px;">
                <div class="blog-card-meta" style="margin-bottom: 20px;">
                    <span class="blog-card-category"><?php echo e($blog['category'] ?: 'News'); ?></span>
                    <span><?php echo formatDate($blog['published_at'] ?: $blog['created_at']); ?></span>
                </div>
                
                <h1 style="font-family: var(--font-display); font-size: clamp(24px, 4vw, 40px); font-weight: 700; text-transform: uppercase; margin-bottom: 30px; line-height: 1.2;"><?php echo e($blog['title']); ?></h1>
                
                <div class="page-content">
                    <?php echo $blog['content']; ?>
                </div>
                
                <?php if ($blog['tags']): ?>
                <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid var(--color-border);">
                    <span style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: var(--color-text-muted);">Tags:</span>
                    <div style="display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap;">
                        <?php foreach (explode(',', $blog['tags']) as $tag): ?>
                        <span style="padding: 6px 14px; background: var(--color-bg-alt); border: 1px solid var(--color-border); border-radius: var(--radius-sm); font-size: 12px;"><?php echo e(trim($tag)); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </article>
        
        <!-- Related Blogs -->
        <?php if (!empty($related)): ?>
        <div style="margin-top: 80px;">
            <h2 style="font-family: var(--font-display); font-size: 24px; text-transform: uppercase; margin-bottom: 30px;">Related Articles</h2>
            <div class="blog-grid">
                <?php foreach ($related as $r): ?>
                <article class="blog-card">
                    <div class="blog-card-image">
                        <img src="<?php echo e($r['image'] ?: '/uploads/blogs/blog-1.jpg'); ?>" alt="<?php echo e($r['title']); ?>" loading="lazy">
                    </div>
                    <div class="blog-card-content">
                        <div class="blog-card-meta">
                            <span class="blog-card-category"><?php echo e($r['category'] ?: 'News'); ?></span>
                            <span><?php echo formatDate($r['published_at'] ?: $r['created_at']); ?></span>
                        </div>
                        <h3 class="blog-card-title"><a href="/blog/<?php echo e($r['slug']); ?>"><?php echo e(truncate($r['title'], 60)); ?></a></h3>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
