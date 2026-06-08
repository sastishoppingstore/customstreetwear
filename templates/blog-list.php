<?php
/**
 * Custom Streetwear - Blog List Template
 */
require_once __DIR__ . '/../includes/functions.php';

$page_num = max(1, intval($_GET['page'] ?? 1));
$per_page = 9;
$offset = ($page_num - 1) * $per_page;

$total = dbCount('blogs', 'status = 1');
$blogs = dbFetchAll("SELECT * FROM blogs WHERE status = 1 ORDER BY published_at DESC LIMIT ? OFFSET ?", [$per_page, $offset]);

$metaTags = generateMetaTags('Blog - Latest News & Insights', 'Stay updated with the latest in sportswear trends, performance tips, and industry insights.');
$breadcrumb = [['label' => 'Blogs']];
include __DIR__ . '/../includes/header.php';
?>

<section style="padding: 60px 0 40px; background: linear-gradient(135deg, var(--color-bg-alt) 0%, var(--color-bg) 100%); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        <div class="section-header" style="text-align: left; margin-bottom: 20px;">
            <span class="section-label">Latest News</span>
            <h1 class="section-title" style="font-size: clamp(28px, 4vw, 48px);">Our Blog</h1>
            <p class="section-desc" style="margin: 0;">Stay updated with the latest in sportswear trends, manufacturing insights, and industry news.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!empty($blogs)): ?>
        <div class="blog-grid">
            <?php foreach ($blogs as $index => $blog): ?>
            <article class="blog-card reveal" style="transition-delay: <?php echo $index * 0.1; ?>s">
                <div class="blog-card-image">
                    <img src="<?php echo e($blog['image'] ?: '/uploads/blogs/blog-1.jpg'); ?>" alt="<?php echo e($blog['alt_text'] ?: $blog['title']); ?>" loading="lazy">
                </div>
                <div class="blog-card-content">
                    <div class="blog-card-meta">
                        <span class="blog-card-category"><?php echo e($blog['category'] ?: 'News'); ?></span>
                        <span><?php echo formatDate($blog['published_at'] ?: $blog['created_at']); ?></span>
                    </div>
                    <h3 class="blog-card-title"><a href="/blog/<?php echo e($blog['slug']); ?>"><?php echo e(truncate($blog['title'], 70)); ?></a></h3>
                    <p class="blog-card-excerpt"><?php echo e(truncate(strip_tags($blog['short_description'] ?: $blog['content']), 120)); ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php echo pagination($total, $per_page, $page_num, '/blogs?page={page}'); ?>
        <?php else: ?>
        <div style="text-align: center; padding: 80px 20px;">
            <p style="color: var(--color-text-muted);">No blog posts found.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
