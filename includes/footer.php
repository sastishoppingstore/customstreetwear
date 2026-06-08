<?php
/**
 * Custom Streetwear - Frontend Footer Template
 */

$siteName = getSetting('site_name', 'Custom Streetwear');
$sitePhone = getSetting('site_phone', '');
$siteEmail = getSetting('site_email', '');
$siteAddress = getSetting('site_address', '');
$footerText = getSetting('footer_text', '');
$copyright = getSetting('copyright_text', 'Custom Streetwear. All Rights Reserved.');
$whatsapp = getSetting('whatsapp_button_number', '');
$facebook = getSetting('facebook_url', '');
$instagram = getSetting('instagram_url', '');
$twitter = getSetting('twitter_url', '');
$youtube = getSetting('youtube_url', '');
$linkedin = getSetting('linkedin_url', '');
$logoText = getSetting('site_logo_text', 'CUSTOM STREETWEAR');
?>
    </main><!-- /#mainContent -->

    <!-- WhatsApp Float Button -->
    <?php if ($whatsapp): ?>
    <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $whatsapp)); ?>?text=<?php echo urlencode(getSetting('whatsapp_button_message', 'Hi, I am interested in custom apparel.')); ?>" 
       class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
    </a>
    <?php endif; ?>

    <!-- Back to Top -->
    <button class="back-to-top" id="backToTop" onclick="scrollToTop()" aria-label="Back to top">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="18 15 12 9 6 15"/></svg>
    </button>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-grid">
                    <!-- Company Info -->
                    <div class="footer-col footer-about">
                        <a href="/" class="footer-logo">
                            <span class="footer-logo-text"><?php echo e($logoText); ?></span>
                        </a>
                        <p class="footer-desc"><?php echo e($footerText); ?></p>
                        <div class="footer-social">
                            <?php if ($facebook): ?>
                            <a href="<?php echo e($facebook); ?>" target="_blank" rel="noopener" aria-label="Facebook">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                            </a>
                            <?php endif; ?>
                            <?php if ($instagram): ?>
                            <a href="<?php echo e($instagram); ?>" target="_blank" rel="noopener" aria-label="Instagram">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                            </a>
                            <?php endif; ?>
                            <?php if ($twitter): ?>
                            <a href="<?php echo e($twitter); ?>" target="_blank" rel="noopener" aria-label="Twitter">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <?php endif; ?>
                            <?php if ($youtube): ?>
                            <a href="<?php echo e($youtube); ?>" target="_blank" rel="noopener" aria-label="YouTube">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                            <?php endif; ?>
                            <?php if ($linkedin): ?>
                            <a href="<?php echo e($linkedin); ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="footer-col">
                        <h4 class="footer-title">Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="/">Home</a></li>
                            <li><a href="/about-us">About Us</a></li>
                            <li><a href="/what-we-do">What We Do</a></li>
                            <li><a href="/how-we-do">How We Do</a></li>
                            <li><a href="/faq">FAQ</a></li>
                            <li><a href="/customisations">Customisations</a></li>
                            <li><a href="/fabrics">Fabrics</a></li>
                            <li><a href="/sports-uniforms">Sports Uniforms</a></li>
                        </ul>
                    </div>

                    <!-- Locations -->
                    <div class="footer-col">
                        <h4 class="footer-title">USA Locations</h4>
                        <ul class="footer-links">
                            <li><a href="/locations/california">California</a></li>
                            <li><a href="/locations/florida">Florida</a></li>
                            <li><a href="/locations/texas">Texas</a></li>
                            <li><a href="/locations/new-york">New York</a></li>
                            <li><a href="/locations/illinois">Illinois</a></li>
                            <li><a href="/locations">View All States</a></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div class="footer-col footer-contact-col">
                        <h4 class="footer-title">Contact Us</h4>
                        <div class="footer-contact">
                            <div class="footer-contact-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span><?php echo e($siteAddress); ?></span>
                            </div>
                            <div class="footer-contact-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                                <a href="tel:<?php echo e($sitePhone); ?>"><?php echo e($sitePhone); ?></a>
                            </div>
                            <div class="footer-contact-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                <a href="mailto:<?php echo e($siteEmail); ?>"><?php echo e($siteEmail); ?></a>
                            </div>
                        </div>
                        <a href="/contact" class="btn btn-outline btn-sm">Get in Touch</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <p class="copyright">&copy; <?php echo date('Y'); ?> <?php echo e($copyright); ?></p>
                    <div class="footer-bottom-links">
                        <a href="/privacy-policy">Privacy Policy</a>
                        <a href="/return-policy">Return Policy</a>
                        <a href="/terms">Terms</a>
                        <a href="/faq">FAQ</a>
                        <a href="/sitemap">Sitemap</a>
                        <a href="/locations">USA Locations</a>
                        <a href="/contact">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Quote Modal -->
    <?php include __DIR__ . '/quote-modal.php'; ?>

    <!-- JavaScript -->
    <script src="/assets/js/main.js?v=<?php echo filemtime(CSW_ROOT . '/assets/js/main.js'); ?>"></script>
    
    <?php echo $extraFoot ?? ''; ?>
</body>
</html>
