<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$usaStates = getUSAStates();
$deliveryCharges = dbFetchAll("SELECT * FROM delivery_charges WHERE status = 1 ORDER BY state, city");
$sitePhone = getSetting('site_phone', '+1 (555) 123-4567');

$metaTags = generateAdvancedMetaTags([
    'meta_title' => 'Request a Quote - Custom Apparel Manufacturing USA | Custom Streetwear',
    'meta_description' => 'Get a free quote for custom apparel manufacturing. Tell us your requirements and our team will respond within 24 hours.',
    'focus_keyword' => 'Custom Apparel Quote USA',
]);

include __DIR__ . '/../includes/header.php';
?>

<?php if (getSetting('site_psycology_first_look', '1') === '1'): ?>
<div class="first-look-elements"><?php echo renderFirstLookElements(); ?></div>
<?php endif; ?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);">
    <div class="container">
        <?php echo advancedBreadcrumb([['label' => 'Request a Quote']]); ?>
        <div class="reveal">
            <span class="section-label"><?php echo e(getSetting('checkout_page_label', 'Get Started')); ?></span>
            <h1 style="font-size:clamp(28px,4vw,48px);font-weight:800;margin:8px 0 16px;"><?php echo e(getSetting('checkout_page_title', 'Request a Custom Apparel Quote')); ?></h1>
            <p style="font-size:18px;color:var(--color-text-muted);max-width:600px;"><?php echo e(getSetting('checkout_page_desc', 'Fill out the form below and our team will respond within 24 hours with a detailed quote.')); ?></p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 380px;gap:40px;align-items:start;">
            <div style="padding:32px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-lg);">
                <form id="checkoutForm" method="POST" action="/api/order.php" enctype="multipart/form-data">
                    <?php echo csrfField(); ?>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-input" required placeholder="Your full name">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-input" required placeholder="your@email.com">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" name="phone" class="form-input" required placeholder="+1 (555) 123-4567">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Company / Team Name</label>
                            <input type="text" name="company" class="form-input" placeholder="Your company name">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px;">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Product Category *</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select category</option>
                                <?php $cats = getCategories(); foreach ($cats as $cat): ?>
                                <option value="<?php echo e($cat['id']); ?>"><?php echo e($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">Estimated Quantity *</label>
                            <select name="quantity" class="form-select" required>
                                <option value="">Select quantity</option>
                                <option value="1-50">1 - 50 units</option>
                                <option value="51-200">51 - 200 units</option>
                                <option value="201-500">201 - 500 units</option>
                                <option value="501-1000">501 - 1,000 units</option>
                                <option value="1001-5000">1,001 - 5,000 units</option>
                                <option value="5000+">5,000+ units</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px;">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">State</label>
                            <select name="state" class="form-select">
                                <option value="">Select state</option>
                                <?php foreach ($usaStates as $s): ?>
                                <option value="<?php echo e($s['name']); ?>"><?php echo e($s['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-input" placeholder="Your city">
                        </div>
                    </div>

                    <div class="form-group" style="margin:16px 0;">
                        <label class="form-label">Product Details *</label>
                        <textarea name="details" class="form-textarea" rows="5" required placeholder="Describe your requirements: product type, design details, colors, sizes, deadline, etc."></textarea>
                    </div>

                    <div class="form-group" style="margin:16px 0;">
                        <label class="form-label">Upload Design / Artwork (optional)</label>
                        <input type="file" name="artwork" class="form-input" accept="image/*,.pdf">
                        <small style="color:var(--color-text-muted);font-size:11px;display:block;margin-top:4px;">Accepted: JPG, PNG, PDF, AI, EPS. Max 10MB.</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%;" id="checkoutSubmitBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                        Submit Quote Request
                    </button>
                </form>
                <div id="checkoutSuccess" class="alert alert-success" style="display:none;margin-top:20px;">
                    Thank you! Your quote request has been submitted. We'll get back to you within 24 hours.
                </div>
            </div>

            <div style="display:grid;gap:16px;">
                <div style="padding:24px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-md);">
                    <h4 style="font-size:14px;font-weight:600;margin-bottom:12px;">Need Help?</h4>
                    <div style="display:grid;gap:12px;">
                        <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $sitePhone)); ?>" style="display:flex;align-items:center;gap:10px;color:var(--color-accent);text-decoration:none;font-size:14px;font-weight:600;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                            <?php echo e($sitePhone); ?>
                        </a>
                        <a href="mailto:<?php echo e(getSetting('site_email', 'sales@customstreetwear.co')); ?>" style="display:flex;align-items:center;gap:10px;color:var(--color-accent);text-decoration:none;font-size:14px;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <?php echo e(getSetting('site_email', 'sales@customstreetwear.co')); ?>
                        </a>
                    </div>
                </div>
                <div style="padding:24px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-md);">
                    <h4 style="font-size:14px;font-weight:600;margin-bottom:12px;">Why Choose Us?</h4>
                    <div style="display:grid;gap:10px;font-size:13px;color:var(--color-text-muted);">
                        <span style="display:flex;align-items:center;gap:8px;"><span style="color:var(--color-accent);">&#10003;</span> Factory-Direct Pricing</span>
                        <span style="display:flex;align-items:center;gap:8px;"><span style="color:var(--color-accent);">&#10003;</span> Ships Within 15-20 Days</span>
                        <span style="display:flex;align-items:center;gap:8px;"><span style="color:var(--color-accent);">&#10003;</span> 100% Quality Guaranteed</span>
                        <span style="display:flex;align-items:center;gap:8px;"><span style="color:var(--color-accent);">&#10003;</span> No Minimum Order</span>
                        <span style="display:flex;align-items:center;gap:8px;"><span style="color:var(--color-accent);">&#10003;</span> Free Design Support</span>
                    </div>
                </div>
                <div class="urgency-bar" style="margin:0;">
                    <span class="urgency-dot"></span>
                    <span>Get Response Within 24 Hours</span>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('checkoutSubmitBtn');
    btn.disabled = true;
    btn.innerHTML = 'Submitting...';
    fetch('/api/order.php', { method: 'POST', body: new FormData(this) })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (typeof trackConversionEvent === 'function') {
                trackConversionEvent('order_submit', {
                    form_name: 'checkout',
                    order_number: data.order_number || ''
                });
            }
            this.reset();
            document.getElementById('checkoutSuccess').style.display = 'block';
        } else {
            alert(data.message || 'Error submitting quote.');
        }
    })
    .catch(() => alert('Network error. Please email us directly.'))
    .finally(() => { btn.disabled = false; btn.innerHTML = 'Submit Quote Request'; });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
