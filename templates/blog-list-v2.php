<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$page_num = max(1, intval($_GET['page'] ?? 1));
$per_page = 9;
$offset = ($page_num - 1) * $per_page;

$total = dbCount('blogs', 'status = 1');
$blogs = dbFetchAll("SELECT * FROM blogs WHERE status = 1 ORDER BY published_at DESC LIMIT ? OFFSET ?", [$per_page, $offset]);

$metaTags = generateAdvancedMetaTags([
    'meta_title' => 'Custom Apparel Blog - Latest News & Insights | Custom Streetwear',
    'meta_description' => 'Stay updated with the latest in custom apparel manufacturing, sportswear trends, and industry insights from Custom Streetwear.',
    'focus_keyword' => 'Custom Apparel Blog USA',
]);

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);">
    <div class="container">
        <?php echo advancedBreadcrumb([['label' => 'Blog']]); ?>
        <div class="reveal">
            <span class="section-label"><?php echo e(getSetting('blog_list_label', 'Latest News')); ?></span>
            <h1 style="font-size:clamp(28px,4vw,48px);font-weight:800;margin-bottom:16px;"><?php echo e(getSetting('blog_list_title', 'Custom Apparel Manufacturing Blog')); ?></h1>
            <p style="font-size:18px;color:var(--color-text-muted);max-width:600px;"><?php echo e(getSetting('blog_list_desc', 'Stay updated with the latest in sportswear trends, manufacturing insights, and industry news.')); ?></p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!empty($blogs)): ?>
        <div class="blog-grid">
            <?php foreach ($blogs as $index => $blog): ?>
            <article class="blog-card reveal" style="transition-delay:<?php echo $index * 0.1; ?>s;">
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
        <div style="text-align:center;padding:80px 20px;">
            <p style="color:var(--color-text-muted);">No blog posts found.</p>
            <a href="/" class="btn btn-outline" style="margin-top:16px;">Back to Home</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
