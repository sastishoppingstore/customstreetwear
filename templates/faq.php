<?php
require_once __DIR__ . '/../includes/functions.php';
$pageTitle = 'Frequently Asked Questions';
$faqs = dbFetchAll("SELECT * FROM faqs WHERE status = 1 ORDER BY sort_order, id");
$categories = dbFetchAll("SELECT DISTINCT category FROM faqs WHERE status = 1 ORDER BY category");
$activeCat = $_GET['category'] ?? '';
$metaTags = generateMetaTags(
    'Frequently Asked Questions - Custom Streetwear',
    'Find answers to common questions about custom apparel manufacturing, ordering, pricing, shipping, returns, and more at Custom Streetwear.',
    '',
    SITE_URL . '/faq'
);
include __DIR__ . '/../includes/header.php';

// Build FAQ schema
$faqItems = [];
foreach ($faqs as $f) {
    $faqItems[] = ['question' => $f['question'], 'answer' => $f['answer']];
}
$faqSchema = faqSchema($faqItems);
?>
<!-- Page Banner -->
<section class="page-banner">
    <div class="page-banner-bg" style="background-image: url('/uploads/sliders/slider-1.jpg');"></div>
    <div class="page-banner-overlay"></div>
    <div class="container">
        <div class="page-banner-content">
            <span class="section-label">Help Center</span>
            <h1 class="page-banner-title">Frequently Asked Questions</h1>
            <p class="page-banner-desc">Find answers to common questions about our custom apparel manufacturing services.</p>
        </div>
    </div>
</section>

<!-- FAQ Schema -->
<script type="application/ld+json"><?php echo $faqSchema; ?></script>

<!-- FAQ Content -->
<section class="section">
    <div class="container">
        <?php if ($categories): ?>
        <div class="faq-categories" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:40px;justify-content:center;">
            <a href="/faq" class="btn <?php echo !$activeCat ? 'btn-primary' : 'btn-outline'; ?> btn-sm">All</a>
            <?php foreach ($categories as $cat): ?>
            <a href="/faq?category=<?php echo urlencode($cat['category']); ?>" class="btn <?php echo $activeCat === $cat['category'] ? 'btn-primary' : 'btn-outline'; ?> btn-sm"><?php echo e($cat['category']); ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <div class="faq-list" style="max-width:850px;margin:0 auto;">
            <?php $displayFaqs = $activeCat ? array_filter($faqs, fn($f) => $f['category'] === $activeCat) : $faqs; ?>
            <?php if (empty($displayFaqs)): ?>
            <div style="text-align:center;padding:60px 20px;color:var(--color-text-muted);"><h3>No FAQs found in this category.</h3><p style="margin-top:10px;">Please select a different category or check back later.</p></div>
            <?php else: ?>
            <?php foreach ($displayFaqs as $index => $faq): ?>
            <div class="faq-item reveal" style="transition-delay: <?php echo $index * 0.05; ?>s;">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <span><?php echo e($faq['question']); ?></span>
                    <svg class="faq-arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        <p><?php echo nl2br(e($faq['answer'])); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <div style="text-align:center;margin-top:60px;padding:40px;background:var(--color-bg-alt);border-radius:var(--radius-lg);">
            <h3>Still Have Questions?</h3>
            <p style="color:var(--color-text-muted);margin:10px 0 20px;">Our team is ready to help you with any questions about your custom apparel needs.</p>
            <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
                <a href="/contact" class="btn btn-primary btn-lg">Contact Us</a>
                <a href="<?php echo e(getSetting('whatsapp_button_number', '#') ? 'https://wa.me/' . getSetting('whatsapp_button_number') : '#'); ?>" target="_blank" class="btn btn-outline btn-lg">Chat on WhatsApp</a>
            </div>
        </div>
    </div>
</section>

<style>
.faq-item { border: 1px solid var(--color-border); border-radius: var(--radius-md); margin-bottom: 12px; overflow: hidden; transition: var(--transition); }
.faq-item:hover { border-color: var(--color-border-light); }
.faq-question { width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 20px 24px; background: none; border: none; cursor: pointer; font-size: 16px; font-weight: 500; text-align: left; color: var(--color-text); transition: var(--transition); }
.faq-question:hover { color: var(--color-accent); }
.faq-arrow { flex-shrink: 0; transition: transform 0.3s ease; color: var(--color-accent); }
.faq-item.active .faq-arrow { transform: rotate(180deg); }
.faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.4s ease, padding 0.4s ease; }
.faq-item.active .faq-answer { max-height: 600px; }
.faq-answer-inner { padding: 0 24px 20px; font-size: 15px; line-height: 1.7; color: var(--color-text-muted); }
</style>

<script>
function toggleFaq(btn) {
    const item = btn.parentElement;
    const wasActive = item.classList.contains('active');
    document.querySelectorAll('.faq-item.active').forEach(el => el.classList.remove('active'));
    if (!wasActive) item.classList.add('active');
}
if (window.location.hash) {
    const hash = window.location.hash.substring(1);
    document.querySelectorAll('.faq-item').forEach(el => {
        if (el.querySelector('.faq-question span')?.textContent.includes(decodeURIComponent(hash))) {
            el.classList.add('active');
        }
    });
}
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
