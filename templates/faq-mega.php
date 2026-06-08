<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$categories = dbFetchAll("SELECT * FROM faq_categories WHERE status = 1 ORDER BY sort_order");
$faqs = dbFetchAll("SELECT f.*, fc.name as category_name, fc.slug as category_slug 
    FROM faqs f 
    LEFT JOIN faq_categories fc ON f.faq_category_id = fc.id 
    WHERE f.status = 1 
    ORDER BY fc.sort_order, f.sort_order, f.id");
$faqsByCat = [];
foreach ($faqs as $faq) {
    $catSlug = $faq['category_slug'] ?? 'general';
    $catName = $faq['category_name'] ?? 'General';
    $faqsByCat[$catSlug]['name'] = $catName;
    $faqsByCat[$catSlug]['items'][] = $faq;
}

$totalFaqs = count($faqs);
$metaTitle = "Frequently Asked Questions - " . getSetting('site_name', 'Custom Streetwear');
$metaDesc = "Find answers to all your questions about custom apparel manufacturing, ordering, pricing, shipping, and more. " . $totalFaqs . " comprehensive FAQs updated regularly.";

$metaTags = generateAdvancedMetaTags([
    'meta_title' => "Frequently Asked Questions About Custom Apparel Manufacturing",
    'meta_description' => $metaDesc,
    'og_type' => 'website'
]);

include __DIR__ . '/../includes/header.php';
?>

<section class="section" style="padding-top:120px;">
    <div class="container">
        <div class="faq-mega">
            <div class="faq-header reveal">
                <span class="section-label" style="margin-bottom:12px;display:block;"><?php echo e(getSetting('faq_page_label', 'Got Questions?')); ?></span>
                <h1><?php echo e(getSetting('faq_page_title', 'Frequently Asked Questions')); ?></h1>
                <p><?php echo e(getSetting('faq_page_desc', 'Everything you need to know about custom apparel manufacturing. Can\'t find what you\'re looking for? Contact our team.')); ?></p>
            </div>

            <!-- Search -->
            <div class="faq-search reveal" style="transition-delay:0.1s;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="faqSearch" placeholder="Search FAQs..." autocomplete="off">
            </div>

            <!-- Category Filters -->
            <div class="faq-categories reveal" style="transition-delay:0.2s;">
                <button class="faq-category-btn active" data-category="all">All (<?php echo $totalFaqs; ?>)</button>
                <?php foreach ($categories as $cat): ?>
                <button class="faq-category-btn" data-category="<?php echo e($cat['slug']); ?>">
                    <?php echo e($cat['name']); ?>
                    (<?php echo count(array_filter($faqs, fn($f) => ($f['category_slug'] ?? 'general') === $cat['slug'])); ?>)
                </button>
                <?php endforeach; ?>
                <?php if (!empty($faqsByCat['general'])): ?>
                <button class="faq-category-btn" data-category="general">General (<?php echo count($faqsByCat['general']['items']); ?>)</button>
                <?php endif; ?>
            </div>

            <!-- FAQ Content -->
            <div id="faqContent">
                <?php $sectionIdx = 0; foreach ($faqsByCat as $catSlug => $catData): ?>
                <div class="faq-section reveal" data-category="<?php echo e($catSlug); ?>" style="transition-delay:<?php echo $sectionIdx * 0.1; ?>s;">
                    <h2 class="faq-section-title"><?php echo e($catData['name']); ?></h2>
                    <?php foreach ($catData['items'] as $idx => $faq): ?>
                    <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                        <button class="faq-question" itemprop="name" onclick="this.closest('.faq-item').classList.toggle('active');const a=this.closest('.faq-item').querySelector('.faq-answer');if(a)a.style.maxHeight=a.style.maxHeight==='0px'?a.scrollHeight+'px':'0px';">
                            <?php echo e($faq['question']); ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>
                        <div class="faq-answer" itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer" style="max-height:0;">
                            <div class="faq-answer-inner" itemprop="text">
                                <?php echo $faq['answer']; ?>
                                <?php if (!empty($faq['ai_summary'])): ?>
                                <div style="margin-top:12px;padding:12px;background:rgba(57,255,20,0.05);border-radius:8px;border:1px solid rgba(57,255,20,0.1);font-size:13px;">
                                    <strong style="color:var(--color-accent);">AI Summary:</strong> <?php echo e($faq['ai_summary']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php $sectionIdx++; endforeach; ?>
            </div>

            <div id="faqNoResults" style="display:none;text-align:center;padding:60px 20px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 16px;color:var(--color-text-muted);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <h3 style="margin-bottom:8px;">No FAQs Found</h3>
                <p style="color:var(--color-text-muted);">Try different keywords or browse by category.</p>
            </div>

            <!-- CTA -->
            <div class="cta-section" style="margin-top:60px;border-radius:var(--radius-lg);text-align:center;padding:60px 40px;">
                <h2 style="font-size:28px;margin-bottom:12px;">Still Have Questions?</h2>
                <p style="color:var(--color-text-muted);margin-bottom:24px;max-width:500px;margin-left:auto;margin-right:auto;">Our team is ready to help you with any questions about your custom apparel needs.</p>
                <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                    <a href="/contact" class="btn btn-primary btn-lg">Contact Us</a>
                    <button class="btn btn-outline btn-lg" onclick="openQuoteModal()">Request a Quote</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Schema.org JSON-LD -->
<script type="application/ld+json">
<?php
$schemaItems = [];
foreach ($faqs as $faq) {
    $schemaItems[] = [
        "@type" => "Question",
        "name" => $faq['question'],
        "acceptedAnswer" => [
            "@type" => "Answer",
            "text" => strip_tags($faq['answer'])
        ]
    ];
}
echo json_encode([
    "@context" => "https://schema.org",
    "@type" => "FAQPage",
    "mainEntity" => $schemaItems,
    "name" => getSetting('faq_page_title', 'Frequently Asked Questions'),
    "description" => getSetting('faq_page_desc', 'Everything you need to know about custom apparel manufacturing.')
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>
</script>

<style>
.faq-item.active .faq-answer { max-height: 1000px !important; }
.faq-item .faq-answer { transition: max-height 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94); overflow: hidden; }
</style>

<?php include __DIR__ . '/../includes/footer.php'; ?>
