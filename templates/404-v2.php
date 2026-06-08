<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

http_response_code(404);

$metaTags = generateAdvancedMetaTags([
    'meta_title' => 'Page Not Found - Custom Apparel Manufacturer USA | Custom Streetwear',
    'meta_description' => 'The page you are looking for does not exist. Browse our custom apparel manufacturing services or contact us for assistance.',
    'robots' => 'noindex, follow',
]);

include __DIR__ . '/../includes/header.php';
?>

<section style="padding:120px 0;text-align:center;">
    <div class="container">
        <div style="max-width:500px;margin:0 auto;">
            <div style="font-size:120px;font-weight:800;font-family:var(--font-display);color:var(--color-accent);opacity:0.3;line-height:1;margin-bottom:20px;">404</div>
            <h1 style="font-size:32px;font-weight:800;margin-bottom:12px;"><?php echo e(getSetting('404_title', 'Page Not Found')); ?></h1>
            <p style="color:var(--color-text-muted);font-size:16px;line-height:1.6;margin-bottom:30px;"><?php echo e(getSetting('404_text', 'The page you are looking for might have been moved or deleted. Let us help you find what you need.')); ?></p>
            <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                <a href="/" class="btn btn-primary btn-lg">Back to Home</a>
                <a href="/contact" class="btn btn-outline btn-lg">Contact Us</a>
                <button class="btn btn-outline btn-lg" onclick="openQuoteModal()">Request a Quote</button>
            </div>
            <div style="margin-top:40px;padding-top:30px;border-top:1px solid var(--color-border);">
                <p style="font-size:14px;color:var(--color-text-muted);margin-bottom:16px;">Popular pages:</p>
                <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                    <a href="/about-us" class="btn btn-outline btn-sm">About Us</a>
                    <a href="/what-we-do" class="btn btn-outline btn-sm">What We Do</a>
                    <a href="/customisations" class="btn btn-outline btn-sm">Customisations</a>
                    <a href="/fabrics" class="btn btn-outline btn-sm">Fabrics</a>
                    <a href="/sports-uniforms" class="btn btn-outline btn-sm">Sports Uniforms</a>
                    <a href="/locations" class="btn btn-outline btn-sm">USA Locations</a>
                    <a href="/faq" class="btn btn-outline btn-sm">FAQ</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
