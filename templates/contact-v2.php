<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/seo-v2.php';

$sitePhone = getSetting('site_phone', '+1 (555) 123-4567');
$siteEmail = getSetting('site_email', 'sales@customstreetwear.co');
$siteAddress = getSetting('site_address', 'Los Angeles, CA, USA');
$siteWhatsapp = getSetting('whatsapp_button_number', '+1 (555) 123-4567');
$ctaPhone = getSetting('site_cta_phone', $sitePhone);
$ctaText = getSetting('site_cta_text', 'Call Us Today');

$metaTags = generateAdvancedMetaTags([
    'meta_title' => 'Contact Us - Custom Apparel Manufacturer in USA',
    'meta_description' => 'Contact Custom Streetwear, America\'s trusted custom apparel manufacturer. Get a free quote for custom sportswear, uniforms, and streetwear. Call ' . $sitePhone,
    'focus_keyword' => 'Custom Apparel Manufacturer Contact USA',
    'og_type' => 'website'
]);

include __DIR__ . '/../includes/header.php';
?>

<section style="padding:80px 0 40px;background:linear-gradient(135deg,var(--color-bg-alt) 0%,var(--color-bg) 100%);border-bottom:1px solid var(--color-border);position:relative;overflow:hidden;">
    <div style="position:absolute;top:-50%;right:-20%;width:400px;height:400px;background:radial-gradient(circle,rgba(57,255,20,0.05),transparent);border-radius:50%;"></div>
    <div class="container" style="position:relative;z-index:1;">
        <?php echo advancedBreadcrumb([['label' => 'Contact Us']]); ?>
        <div class="reveal">
            <span class="section-label" style="margin-bottom:12px;display:block;"><?php echo e(getSetting('contact_page_label', 'Get In Touch')); ?></span>
            <h1 style="font-size:clamp(32px,5vw,52px);font-weight:800;margin-bottom:16px;"><?php echo e(getSetting('contact_page_title', 'Contact Custom Apparel Manufacturer USA')); ?></h1>
            <p style="font-size:18px;color:var(--color-text-muted);max-width:600px;line-height:1.6;"><?php echo e(getSetting('contact_page_desc', 'Have a project in mind? Our team is ready to help you create premium custom apparel. Get a free quote within 24 hours.')); ?></p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start;">
            <!-- Contact Form -->
            <div class="reveal">
                <h2 style="font-family:var(--font-display);font-size:20px;text-transform:uppercase;margin-bottom:8px;">Send a Message</h2>
                <p style="color:var(--color-text-muted);font-size:14px;margin-bottom:24px;">Fill out the form and we'll get back to you within 24 hours.</p>
                <form action="/api/contact.php" method="POST" id="contactForm" onsubmit="return submitContactForm(event)">
                    <?php echo csrfField(); ?>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div class="form-group" style="margin:0;">
                            <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;color:var(--color-text-muted);">Full Name *</label>
                            <input type="text" name="name" class="form-input" required placeholder="Your name" style="width:100%;padding:12px 16px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-sm);color:var(--color-text);font-size:14px;">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;color:var(--color-text-muted);">Email Address *</label>
                            <input type="email" name="email" class="form-input" required placeholder="your@email.com" style="width:100%;padding:12px 16px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-sm);color:var(--color-text);font-size:14px;">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;color:var(--color-text-muted);">Phone</label>
                            <input type="tel" name="phone" class="form-input" placeholder="+1 (555) 123-4567" style="width:100%;padding:12px 16px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-sm);color:var(--color-text);font-size:14px;">
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;color:var(--color-text-muted);">Company / Team</label>
                            <input type="text" name="company" class="form-input" placeholder="Your company name" style="width:100%;padding:12px 16px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-sm);color:var(--color-text);font-size:14px;">
                        </div>
                    </div>
                    <div class="form-group" style="margin:16px 0;">
                        <label class="form-label" style="font-size:12px;font-weight:600;display:block;margin-bottom:4px;color:var(--color-text-muted);">Message *</label>
                        <textarea name="message" rows="5" required placeholder="Tell us about your requirements... (e.g., product type, quantity, deadline)" style="width:100%;padding:12px 16px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-sm);color:var(--color-text);font-size:14px;resize:vertical;font-family:inherit;min-height:120px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" id="contactSubmitBtn" style="width:100%;">
                        <span class="btn-text">Send Message →</span>
                    </button>
                </form>
                <div id="contactSuccess" style="display:none;margin-top:20px;padding:16px;background:rgba(57,255,20,0.08);border:1px solid rgba(57,255,20,0.2);border-radius:var(--radius-md);text-align:center;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#39ff14" stroke-width="2" style="margin:0 auto 8px;display:block;"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <strong>Thank you!</strong> Your message has been sent. We'll contact you within 24 hours.
                </div>
            </div>

            <!-- Contact Info -->
            <div class="reveal" style="transition-delay:0.2s;">
                <h2 style="font-family:var(--font-display);font-size:20px;text-transform:uppercase;margin-bottom:24px;">Contact Information</h2>

                <div style="display:grid;gap:16px;">
                    <div style="display:flex;gap:16px;align-items:flex-start;padding:20px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-md);">
                        <div style="width:44px;height:44px;border-radius:50%;background:rgba(57,255,20,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--color-accent);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        </div>
                        <div>
                            <h4 style="font-size:13px;font-weight:600;margin-bottom:4px;">Call Us</h4>
                            <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $sitePhone)); ?>" style="font-size:20px;font-weight:700;color:var(--color-accent);text-decoration:none;"><?php echo e($sitePhone); ?></a>
                            <p style="font-size:12px;color:var(--color-text-muted);margin-top:4px;">Mon-Sat, 9AM-6PM EST</p>
                        </div>
                    </div>

                    <div style="display:flex;gap:16px;align-items:flex-start;padding:20px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-md);">
                        <div style="width:44px;height:44px;border-radius:50%;background:rgba(57,255,20,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--color-accent);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div>
                            <h4 style="font-size:13px;font-weight:600;margin-bottom:4px;">WhatsApp</h4>
                            <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $siteWhatsapp)); ?>" target="_blank" rel="noopener" style="font-size:16px;font-weight:600;color:var(--color-accent);text-decoration:none;">Chat on WhatsApp →</a>
                            <p style="font-size:12px;color:var(--color-text-muted);margin-top:4px;">Quick reply within 1 hour</p>
                        </div>
                    </div>

                    <div style="display:flex;gap:16px;align-items:flex-start;padding:20px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-md);">
                        <div style="width:44px;height:44px;border-radius:50%;background:rgba(57,255,20,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--color-accent);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div>
                            <h4 style="font-size:13px;font-weight:600;margin-bottom:4px;">Email Us</h4>
                            <a href="mailto:<?php echo e($siteEmail); ?>" style="font-size:16px;font-weight:600;color:var(--color-accent);text-decoration:none;"><?php echo e($siteEmail); ?></a>
                            <p style="font-size:12px;color:var(--color-text-muted);margin-top:4px;">We reply within 24 hours</p>
                        </div>
                    </div>

                    <div style="display:flex;gap:16px;align-items:flex-start;padding:20px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-md);">
                        <div style="width:44px;height:44px;border-radius:50%;background:rgba(57,255,20,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--color-accent);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <h4 style="font-size:13px;font-weight:600;margin-bottom:4px;">Location</h4>
                            <p style="font-size:14px;color:var(--color-text-muted);"><?php echo e($siteAddress); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Business Hours -->
                <div style="margin-top:20px;padding:20px;background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:var(--radius-md);">
                    <h4 style="font-size:13px;font-weight:600;margin-bottom:12px;">Business Hours (EST)</h4>
                    <div style="display:grid;grid-template-columns:1fr auto;gap:8px;font-size:13px;">
                        <span style="color:var(--color-text-muted);">Monday - Saturday</span>
                        <span>9:00 AM - 6:00 PM</span>
                        <span style="color:var(--color-text-muted);">Sunday</span>
                        <span style="color:var(--color-accent);">Closed</span>
                    </div>
                </div>

                <!-- Quick CTA -->
                <div style="margin-top:20px;padding:24px;background:linear-gradient(135deg,rgba(57,255,20,0.05),rgba(57,255,20,0.02));border:1px solid rgba(57,255,20,0.15);border-radius:var(--radius-md);text-align:center;">
                    <h4 style="font-size:15px;margin-bottom:8px;">Need a Quote Fast?</h4>
                    <p style="font-size:13px;color:var(--color-text-muted);margin-bottom:16px;">Get factory-direct pricing on custom apparel. Minimum order: No minimum.</p>
                    <button class="btn btn-primary" onclick="openQuoteModal()" style="width:100%;">Request Free Quote</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map -->
<section style="margin-top:-40px;">
    <div style="border-top:1px solid var(--color-border);">
        <?php echo getSetting('google_map_iframe', ''); ?>
    </div>
</section>

<!-- CTA -->
<section class="cta-section" style="padding:60px 0;">
    <div class="cta-section-bg"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label"><?php echo e(getSetting('contact_cta_label', 'Let\'s Create Together')); ?></span>
            <h2 class="cta-section-title"><?php echo e(getSetting('contact_cta_title', 'Ready to Start Your Custom Apparel Project?')); ?></h2>
            <p class="cta-section-desc"><?php echo e(getSetting('contact_cta_desc', 'Get a free, no-obligation quote within 24 hours. Factory-direct pricing for USA brands.')); ?></p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Get Free Quote</button>
                <a href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $sitePhone)); ?>" class="btn btn-outline btn-lg"><?php echo e($ctaText); ?>: <?php echo e($sitePhone); ?></a>
            </div>
        </div>
    </div>
</section>

<script>
function submitContactForm(event) {
    event.preventDefault();
    const form = event.target;
    const btn = document.getElementById('contactSubmitBtn');
    const success = document.getElementById('contactSuccess');
    btn.disabled = true;
    btn.innerHTML = 'Sending...';
    fetch('/api/contact.php', { method: 'POST', body: new FormData(form) })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            form.reset();
            success.style.display = 'block';
            if (typeof trackConversionEvent === 'function') {
                trackConversionEvent('contact_submit', { form_name: 'contact' });
            }
            setTimeout(() => success.style.display = 'none', 8000);
        } else {
            alert(data.message || 'Error sending message.');
        }
    })
    .catch(() => alert('Network error. Please email us directly.'))
    .finally(() => { btn.disabled = false; btn.innerHTML = 'Send Message →'; });
    return false;
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
