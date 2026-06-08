<?php
/**
 * Custom Streetwear - Quote Request Modal
 */
?>
<!-- Quote Modal Overlay -->
<div class="quote-modal-overlay" id="quoteModalOverlay" onclick="closeQuoteModal()"></div>

<!-- Quote Modal -->
<div class="quote-modal" id="quoteModal">
    <div class="quote-modal-header">
        <h3 class="quote-modal-title">Request a Quote</h3>
        <p class="quote-modal-subtitle">Fill in the details below and we'll get back to you within 24 hours.</p>
        <button class="quote-modal-close" onclick="closeQuoteModal()" aria-label="Close modal">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    
    <div class="quote-modal-body">
        <form action="/api/quote.php" method="POST" enctype="multipart/form-data" id="quoteForm" onsubmit="return submitQuoteForm(event)">
            <?php echo csrfField(); ?>
            <input type="hidden" name="source_page" value="<?php echo e($_SERVER['REQUEST_URI'] ?? '/'); ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="quote_name" class="form-label">Full Name *</label>
                    <input type="text" id="quote_name" name="name" class="form-input" required placeholder="Your full name">
                </div>
                <div class="form-group">
                    <label for="quote_email" class="form-label">Email Address *</label>
                    <input type="email" id="quote_email" name="email" class="form-input" required placeholder="your@email.com">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="quote_phone" class="form-label">Phone Number</label>
                    <input type="tel" id="quote_phone" name="phone" class="form-input" placeholder="+1 234 567 8900">
                </div>
                <div class="form-group">
                    <label for="quote_whatsapp" class="form-label">WhatsApp</label>
                    <input type="tel" id="quote_whatsapp" name="whatsapp" class="form-input" placeholder="+1 234 567 8900">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="quote_country" class="form-label">Country</label>
                    <input type="text" id="quote_country" name="country" class="form-input" placeholder="Your country">
                </div>
                <div class="form-group">
                    <label for="quote_company" class="form-label">Company</label>
                    <input type="text" id="quote_company" name="company" class="form-input" placeholder="Your company name">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="quote_product" class="form-label">Product Interest *</label>
                    <input type="text" id="quote_product" name="product_interest" class="form-input" required placeholder="e.g., Custom Hoodies, Sports Uniforms">
                </div>
                <div class="form-group">
                    <label for="quote_quantity" class="form-label">Quantity</label>
                    <input type="text" id="quote_quantity" name="quantity" class="form-input" placeholder="e.g., 100 pieces">
                </div>
            </div>
            
            <div class="form-group">
                <label for="quote_message" class="form-label">Message *</label>
                <textarea id="quote_message" name="message" class="form-textarea" rows="4" required placeholder="Tell us about your requirements..."></textarea>
            </div>
            
            <div class="form-group">
                <label for="quote_attachment" class="form-label">Attachment (Design/Reference)</label>
                <input type="file" id="quote_attachment" name="attachment" class="form-file" accept=".jpg,.jpeg,.png,.pdf,.zip">
                <span class="form-hint">Max 10MB. JPG, PNG, PDF, ZIP accepted.</span>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block btn-lg" id="quoteSubmitBtn">
                <span class="btn-text">Submit Quote Request</span>
                <span class="btn-loader" style="display:none;">
                    <svg class="spinner" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-dasharray="60" stroke-dashoffset="20"/></svg>
                    Sending...
                </span>
            </button>
        </form>
        
        <!-- Success Message -->
        <div class="quote-success" id="quoteSuccess" style="display:none;">
            <div class="quote-success-icon">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <h4>Quote Request Submitted!</h4>
            <p>Thank you for your interest. Our team will review your request and get back to you within 24 hours.</p>
            <button class="btn btn-outline" onclick="closeQuoteModal()">Close</button>
        </div>
    </div>
</div>
