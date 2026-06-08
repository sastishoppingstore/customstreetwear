<?php
/**
 * Custom Streetwear - Contact Page Template
 */

require_once __DIR__ . '/../includes/functions.php';

$sitePhone = getSetting('site_phone', '');
$siteEmail = getSetting('site_email', '');
$siteAddress = getSetting('site_address', '');
$siteWhatsapp = getSetting('whatsapp_button_number', '');

$metaTags = generateMetaTags('Contact Us - Get in Touch', 'Contact Custom Streetwear for custom apparel manufacturing inquiries. We ship worldwide.');

$breadcrumb = [
    ['label' => 'Contact Us']
];

include __DIR__ . '/../includes/header.php';
?>

<section style="padding: 60px 0 40px; background: linear-gradient(135deg, var(--color-bg-alt) 0%, var(--color-bg) 100%); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        <div class="section-header" style="text-align: left; margin-bottom: 20px;">
            <span class="section-label">Get In Touch</span>
            <h1 class="section-title" style="font-size: clamp(28px, 4vw, 48px);">Contact Us</h1>
            <p class="section-desc" style="margin: 0; max-width: 600px;">Have a question or need a custom quote? Our team is ready to help you create the perfect apparel for your brand.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="reveal">
                <h2 style="font-family: var(--font-display); font-size: 22px; text-transform: uppercase; margin-bottom: 30px;">Send us a Message</h2>
                <form action="/api/contact.php" method="POST" id="contactForm" onsubmit="return submitContactForm(event)">
                    <?php echo csrfField(); ?>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-input" required placeholder="Your name">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-input" required placeholder="your@email.com">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone</label>
                            <input type="tel" name="phone" class="form-input" placeholder="+1 234 567 8900">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-input" placeholder="How can we help?">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Message *</label>
                        <textarea name="message" class="form-textarea" rows="6" required placeholder="Tell us about your requirements..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg" id="contactSubmitBtn">
                        <span class="btn-text">Send Message</span>
                    </button>
                </form>
                <div id="contactSuccess" class="alert alert-success" style="display:none; margin-top: 20px;">
                    Thank you! Your message has been sent. We'll get back to you soon.
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="reveal" style="transition-delay: 0.2s;">
                <h2 style="font-family: var(--font-display); font-size: 22px; text-transform: uppercase; margin-bottom: 30px;">Contact Information</h2>
                
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div class="contact-info-content">
                        <h4>Address</h4>
                        <p><?php echo e($siteAddress); ?></p>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                    </div>
                    <div class="contact-info-content">
                        <h4>Phone</h4>
                        <a href="tel:<?php echo e($sitePhone); ?>"><?php echo e($sitePhone); ?></a>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <div class="contact-info-content">
                        <h4>Email</h4>
                        <a href="mailto:<?php echo e($siteEmail); ?>"><?php echo e($siteEmail); ?></a>
                    </div>
                </div>
                
                <div class="contact-info-item">
                    <div class="contact-info-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                    <div class="contact-info-content">
                        <h4>WhatsApp</h4>
                        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $siteWhatsapp)); ?>" target="_blank" rel="noopener"><?php echo e($siteWhatsapp); ?></a>
                    </div>
                </div>
                
                <div style="margin-top: 30px; padding: 24px; background: var(--color-bg-card); border: 1px solid var(--color-border); border-radius: var(--radius-md);">
                    <h4 style="font-family: var(--font-display); font-size: 16px; text-transform: uppercase; margin-bottom: 12px;">Business Hours</h4>
                    <p style="font-size: 14px; color: var(--color-text-muted); line-height: 1.8;">
                        Monday - Saturday: 9:00 AM - 6:00 PM<br>
                        Sunday: Closed
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map -->
<section>
    <div class="contact-map">
        <?php echo getSetting('google_map_iframe', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d107687.123456789!2d74.5123!3d32.4945!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzLCsDI5JzQwLjIiTiA3NMKwMzAnNDQuMyJF!5e0!3m2!1sen!2s!4v1234567890" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'); ?>
    </div>
</section>

<script>
function submitContactForm(event) {
    event.preventDefault();
    const form = event.target;
    const btn = document.getElementById('contactSubmitBtn');
    const success = document.getElementById('contactSuccess');
    
    btn.disabled = true;
    
    fetch('/api/contact.php', {
        method: 'POST',
        body: new FormData(form)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            form.reset();
            success.style.display = 'block';
            setTimeout(() => success.style.display = 'none', 5000);
        } else {
            alert(data.message || 'Error sending message.');
        }
    })
    .catch(() => alert('Failed to send. Please try again.'))
    .finally(() => btn.disabled = false);
    
    return false;
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
