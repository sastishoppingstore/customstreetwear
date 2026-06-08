<?php
require_once __DIR__ . '/../includes/functions.php';
$usaStates = getUSAStates();
$deliveryCharges = dbFetchAll("SELECT * FROM delivery_charges WHERE status = 1 ORDER BY state, city");
$metaTags = generateMetaTags(
    'Request a Quote - Custom Streetwear',
    'Get a free quote for custom apparel manufacturing. Tell us your requirements and our team will respond within 24 hours.',
    '',
    SITE_URL . '/checkout'
);
include __DIR__ . '/../includes/header.php';
?>
<!-- Page Banner -->
<section class="page-banner">
    <div class="page-banner-bg" style="background-image: url('/uploads/sliders/slider-1.jpg');"></div>
    <div class="page-banner-overlay"></div>
    <div class="container">
        <div class="page-banner-content">
            <span class="section-label">Get Started</span>
            <h1 class="page-banner-title">Request a Quote</h1>
            <p class="page-banner-desc">Fill out the form below and our team will respond within 24 hours with a detailed quote.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-grid" style="grid-template-columns: 1fr 380px;">
            <div class="admin-card reveal">
                <form id="checkoutForm" method="POST" action="/api/order.php" enctype="multipart/form-data">
                    <?php echo csrfField(); ?>
                    <h3 style="margin-bottom:20px;">Contact Information</h3>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-input" required></div>
                        <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-input" required></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Phone *</label><input type="tel" name="phone" class="form-input" required></div>
                        <div class="form-group"><label class="form-label">Company</label><input type="text" name="company" class="form-input"></div>
                    </div>
                    
                    <h3 style="margin:30px 0 20px;">Shipping Address</h3>
                    <div class="form-group"><label class="form-label">Street Address</label><textarea name="address" class="form-textarea" rows="2"></textarea></div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">City *</label>
                            <select name="city" class="form-select" id="citySelect" required onchange="updateDeliveryCharge()">
                                <option value="">Select City</option>
                                <?php 
                                $groupedCities = [];
                                foreach ($deliveryCharges as $dc) {
                                    $groupedCities[$dc['state']][] = $dc;
                                }
                                foreach ($groupedCities as $state => $cities): ?>
                                <optgroup label="<?php echo e($state); ?>">
                                    <?php foreach ($cities as $c): ?>
                                    <option value="<?php echo e($c['city']); ?>" data-state="<?php echo e($c['state']); ?>" data-charge="<?php echo $c['charge']; ?>" data-days="<?php echo e($c['estimated_days']); ?>"><?php echo e($c['city']); ?> ($<?php echo number_format($c['charge'], 2); ?>)</option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">State</label><input type="text" name="state" class="form-input" id="stateInput" readonly></div>
                    </div>
                    <div class="form-group"><label class="form-label">ZIP Code</label><input type="text" name="zip" class="form-input"></div>
                    
                    <h3 style="margin:30px 0 20px;">Order Details</h3>
                    <div class="form-group"><label class="form-label">Products Interested In</label><textarea name="product_interest" class="form-textarea" rows="3" placeholder="Tell us what products you need..."></textarea></div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Estimated Quantity</label>
                            <select name="quantity" class="form-select">
                                <option value="">Select Quantity</option>
                                <option value="10-50">10-50 pieces</option>
                                <option value="50-100">50-100 pieces</option>
                                <option value="100-500">100-500 pieces</option>
                                <option value="500-1000">500-1,000 pieces</option>
                                <option value="1000+">1,000+ pieces</option>
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">Custom Details</label><textarea name="custom_details" class="form-textarea" rows="2" placeholder="Colors, sizes, customization..."></textarea></div>
                    </div>
                    
                    <h3 style="margin:30px 0 20px;">Payment Method</h3>
                    <div class="form-group">
                        <select name="payment_method" class="form-select">
                            <option value="bank_transfer">Bank Transfer (Wire)</option>
                            <option value="paypal">PayPal</option>
                            <option value="credit_card">Credit Card</option>
                        </select>
                    </div>
                    <div class="form-group" id="paymentProofGroup">
                        <label class="form-label">Upload Payment Proof (optional)</label>
                        <input type="file" name="payment_proof" class="form-input" accept="image/*,.pdf">
                        <small style="display:block;color:var(--muted);margin-top:4px;">Upload bank transfer receipt or payment screenshot</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg" style="margin-top:20px;">Submit Order Request</button>
                </form>
            </div>
            
            <!-- Order Summary Sidebar -->
            <div class="admin-card reveal" style="position:sticky;top:100px;height:fit-content;">
                <h3 style="margin-bottom:20px;">Order Summary</h3>
                <div style="padding:20px;background:var(--color-bg-alt);border-radius:var(--radius-md);margin-bottom:16px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                        <span style="color:var(--color-text-muted);">Subtotal</span>
                        <span id="summarySubtotal" style="font-weight:600;">Calculated in quote</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:12px;">
                        <span style="color:var(--color-text-muted);">Delivery</span>
                        <span id="summaryDelivery" style="font-weight:600;">Select city</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:12px;" id="summaryDaysRow" class="hidden">
                        <span style="color:var(--color-text-muted);">Est. Delivery</span>
                        <span id="summaryDays" style="font-weight:600;"></span>
                    </div>
                    <hr style="border-color:var(--color-border);margin:16px 0;">
                    <div style="display:flex;justify-content:space-between;">
                        <span style="font-weight:600;">Total</span>
                        <span id="summaryTotal" style="font-weight:700;color:var(--color-accent);">Will be quoted</span>
                    </div>
                </div>
                <div style="font-size:13px;color:var(--color-text-muted);line-height:1.6;">
                    <p>✓ Free consultation and sample development</p>
                    <p>✓ 100% quality guarantee</p>
                    <p>✓ Factory-direct pricing</p>
                    <p>✓ USA-wide delivery</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function updateDeliveryCharge() {
    const select = document.getElementById('citySelect');
    const selected = select.options[select.selectedIndex];
    const stateInput = document.getElementById('stateInput');
    const summaryDelivery = document.getElementById('summaryDelivery');
    const summaryDays = document.getElementById('summaryDays');
    const summaryDaysRow = document.getElementById('summaryDaysRow');
    
    if (selected && selected.value) {
        const state = selected.getAttribute('data-state');
        const charge = parseFloat(selected.getAttribute('data-charge'));
        const days = selected.getAttribute('data-days');
        stateInput.value = state;
        summaryDelivery.textContent = '$' + charge.toFixed(2);
        summaryDays.textContent = days;
        summaryDaysRow.classList.remove('hidden');
    } else {
        stateInput.value = '';
        summaryDelivery.textContent = 'Select city';
        summaryDays.textContent = '';
        summaryDaysRow.classList.add('hidden');
    }
}
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
