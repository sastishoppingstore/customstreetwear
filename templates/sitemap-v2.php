<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$pages = dbFetchAll("SELECT * FROM pages WHERE status = 1 ORDER BY title");
$categories = getCategories();
$blogs = dbFetchAll("SELECT id, slug, title, published_at FROM blogs WHERE status = 1 ORDER BY title");
$usaStates = getUSAStates();
$sitePhone = getSetting('site_phone', '+1 (555) 123-4567');

$metaTags = generateAdvancedMetaTags([
    'meta_title' => 'Sitemap - Custom Apparel Manufacturer USA | Custom Streetwear',
    'meta_description' => 'Browse the complete sitemap of Custom Streetwear. Find custom apparel manufacturing services, product categories, locations, and more.',
    'robots' => 'noindex, follow',
]);

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);">
    <div class="container">
        <?php echo advancedBreadcrumb([['label' => 'Sitemap']]); ?>
        <div class="reveal">
            <span class="section-label">Sitemap</span>
            <h1 style="font-size:clamp(28px,4vw,48px);font-weight:800;margin:8px 0 16px;">Sitemap - Custom Streetwear</h1>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:32px;">
            <div class="reveal">
                <h3 style="font-family:var(--font-display);font-size:16px;text-transform:uppercase;margin-bottom:16px;color:var(--color-accent);">Main Pages</h3>
                <ul style="list-style:none;padding:0;display:grid;gap:8px;">
                    <li><a href="/" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; Home</a></li>
                    <li><a href="/about-us" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; About Us</a></li>
                    <li><a href="/what-we-do" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; What We Do</a></li>
                    <li><a href="/how-we-do" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; How We Do</a></li>
                    <li><a href="/contact" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; Contact Us</a></li>
                    <li><a href="/faq" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; FAQ</a></li>
                    <li><a href="/sports-uniforms" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; Sports Uniforms</a></li>
                    <li><a href="/customisations" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; Customisations</a></li>
                    <li><a href="/fabrics" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; Fabrics</a></li>
                    <li><a href="/checkout" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; Request a Quote</a></li>
                    <li><a href="/blogs" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; Blog</a></li>
                    <li><a href="/locations" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; USA Locations</a></li>
                </ul>
                <h3 style="font-family:var(--font-display);font-size:16px;text-transform:uppercase;margin:24px 0 16px;color:var(--color-accent);">Policies</h3>
                <ul style="list-style:none;padding:0;display:grid;gap:8px;">
                    <li><a href="/privacy-policy" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; Privacy Policy</a></li>
                    <li><a href="/return-policy" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; Return Policy</a></li>
                    <li><a href="/terms" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; Terms & Conditions</a></li>
                </ul>
            </div>

            <div class="reveal" style="transition-delay:0.1s;">
                <h3 style="font-family:var(--font-display);font-size:16px;text-transform:uppercase;margin-bottom:16px;color:var(--color-accent);">Product Categories</h3>
                <ul style="list-style:none;padding:0;display:grid;gap:8px;">
                    <?php foreach ($categories as $cat): ?>
                    <li><a href="/category/<?php echo e($cat['slug']); ?>" style="color:var(--color-text-muted);text-decoration:none;font-size:14px;">&rarr; <?php echo e($cat['name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="reveal" style="transition-delay:0.2s;">
                <h3 style="font-family:var(--font-display);font-size:16px;text-transform:uppercase;margin-bottom:16px;color:var(--color-accent);">USA Locations</h3>
                <ul style="list-style:none;padding:0;display:grid;gap:6px;max-height:400px;overflow-y:auto;">
                    <?php foreach ($usaStates as $slug => $state): ?>
                    <li><a href="/locations/<?php echo e($slug); ?>" style="color:var(--color-text-muted);text-decoration:none;font-size:13px;">&rarr; <?php echo e($state['name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if (!empty($blogs)): ?>
            <div class="reveal" style="transition-delay:0.3s;">
                <h3 style="font-family:var(--font-display);font-size:16px;text-transform:uppercase;margin-bottom:16px;color:var(--color-accent);">Blog Posts</h3>
                <ul style="list-style:none;padding:0;display:grid;gap:6px;max-height:400px;overflow-y:auto;">
                    <?php foreach ($blogs as $blog): ?>
                    <li><a href="/blog/<?php echo e($blog['slug']); ?>" style="color:var(--color-text-muted);text-decoration:none;font-size:13px;">&rarr; <?php echo e(truncate($blog['title'], 50)); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
