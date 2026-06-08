<?php
require_once __DIR__ . '/../includes/functions.php';

$metaTags = generateMetaTags(
    'Custom Sports Uniforms Manufacturer in USA',
    'Custom Streetwear is America\'s premier custom sports uniforms manufacturer. Premium basketball, football, baseball, soccer, and hockey uniforms for teams, schools, and leagues nationwide. Factory-direct pricing, custom designs, fast delivery across all 50 states.',
    '/uploads/categories/sports-uniform.jpg',
    SITE_URL . '/sports-uniforms'
);

$breadcrumb = [
    ['label' => 'Sports Uniforms']
];

include __DIR__ . '/../includes/header.php';
?>

<section style="padding: 60px 0 40px; background: linear-gradient(135deg, var(--color-bg-alt) 0%, var(--color-bg) 100%); border-bottom: 1px solid var(--color-border);">
    <div class="container">
        <?php echo buildBreadcrumb($breadcrumb); ?>
        <div style="display: grid; gap: 30px; align-items: center; grid-template-columns: 1fr;">
            <div>
                <span class="section-label">Premium Quality</span>
                <h1 class="section-title" style="font-size: clamp(28px, 4vw, 48px); margin-bottom: 16px;">Custom Sports Uniforms Manufacturer in USA</h1>
                <p style="color: var(--color-text-muted); max-width: 750px; line-height: 1.8; font-size: 16px;">Custom Streetwear is America's premier custom sports uniforms manufacturer, delivering premium-quality basketball, football, baseball, soccer, and hockey uniforms to teams, schools, colleges, and professional organizations across all 50 states. With over a decade of manufacturing excellence, factory-direct pricing, and end-to-end customization, we produce uniforms that perform as hard as your athletes.</p>
                <div style="display: flex; gap: 16px; margin-top: 30px; flex-wrap: wrap;">
                    <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Get a Quote</button>
                    <a href="/contact" class="btn btn-outline btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label">Our Range</span>
            <h2 class="section-title">Sports Uniforms We Manufacture</h2>
            <p class="section-desc">From professional-grade performance wear to team uniforms, we manufacture it all with premium materials and precision craftsmanship. Every uniform is built to perform at the highest level.</p>
        </div>
        <div class="category-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            <div class="glass-card reveal">
                <div class="glass-card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12l2 2 4-4"/></svg>
                </div>
                <h3 class="glass-card-title">Basketball Uniforms</h3>
                <p class="glass-card-text">Premium basketball jerseys and shorts with moisture-wicking fabric, customizable colors, logos, and player numbers. Designed for mobility and breathability on the court.</p>
            </div>
            <div class="glass-card reveal" style="transition-delay: 0.1s">
                <div class="glass-card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3 class="glass-card-title">Football Uniforms</h3>
                <p class="glass-card-text">Durable football jerseys, pants, and padded gear built for impact. Available in custom team colors with full sublimation printing, embroidery, and heat-sealed numbers.</p>
            </div>
            <div class="glass-card reveal" style="transition-delay: 0.2s">
                <div class="glass-card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                </div>
                <h3 class="glass-card-title">Baseball Uniforms</h3>
                <p class="glass-card-text">Professional baseball jerseys, pants, and caps with authentic stitching. Custom piping, button-front options, and full team branding available for all levels of play.</p>
            </div>
            <div class="glass-card reveal" style="transition-delay: 0.3s">
                <div class="glass-card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <h3 class="glass-card-title">Soccer Uniforms</h3>
                <p class="glass-card-text">Lightweight, breathable soccer kits including jerseys, shorts, and socks. Engineered for performance with moisture management and full sublimation printing.</p>
            </div>
            <div class="glass-card reveal" style="transition-delay: 0.4s">
                <div class="glass-card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/></svg>
                </div>
                <h3 class="glass-card-title">Hockey Uniforms</h3>
                <p class="glass-card-text">Performance hockey jerseys engineered for durability on ice. Breathable mesh fabrics, reinforced stitching, custom numbering, and full team crests.</p>
            </div>
            <div class="glass-card reveal" style="transition-delay: 0.5s">
                <div class="glass-card-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                </div>
                <h3 class="glass-card-title">Custom Training Gear</h3>
                <p class="glass-card-text">Practice jerseys, warm-up suits, tracksuits, and training apparel. Affordable bulk pricing for teams, schools, and sports organizations across America.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="about-grid">
            <div class="about-image reveal">
                <img src="/uploads/products/custom-sublimation-t-shirt.jpg" alt="Custom Sports Uniform Manufacturing" loading="lazy" style="width: 100%;">
            </div>
            <div class="about-content reveal">
                <span class="section-label">Why Choose Us</span>
                <h2>America's Most Trusted Sports Uniform Manufacturer</h2>
                <p>Custom Streetwear is the preferred sports uniform manufacturer for teams, schools, colleges, and professional organizations across the United States. With over a decade of experience and a production capacity of 50,000+ units daily, we have the scale and expertise to handle any order — from small school teams to large multi-team league programs.</p>
                <p>Every uniform is crafted with precision using premium materials sourced from around the world. Our 12-point quality inspection ensures every garment meets exact specifications before shipment. We serve high schools, NCAA programs, minor league teams, recreational leagues, and sports brands across all 50 states.</p>
                <div class="about-stats">
                    <div class="about-stat">
                        <div class="about-stat-number" data-counter="50000000">0</div>
                        <div class="about-stat-label">Total Uniforms Produced</div>
                    </div>
                    <div class="about-stat">
                        <div class="about-stat-number" data-counter="2500">0</div>
                        <div class="about-stat-label">USA Clients Served</div>
                    </div>
                    <div class="about-stat">
                        <div class="about-stat-number" data-counter="50">0</div>
                        <div class="about-stat-label">States Served</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label">Customization</span>
            <h2 class="section-title">Full Customization Options</h2>
            <p class="section-desc">Every sports uniform is fully customizable to match your team's identity and performance requirements. No design is too complex.</p>
        </div>
        <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));">
            <div class="stat-item reveal">
                <div class="stat-number" style="font-size: 24px;">🎨</div>
                <div class="stat-label" style="margin-top: 12px; font-size: 14px; text-transform: none; color: var(--color-text);">Custom Colors & Designs</div>
                <p style="font-size: 13px; color: var(--color-text-muted); margin-top: 8px;">Any PMS colors, gradients, patterns, and full custom artwork</p>
            </div>
            <div class="stat-item reveal" style="transition-delay: 0.1s">
                <div class="stat-number" style="font-size: 24px;">👕</div>
                <div class="stat-label" style="margin-top: 12px; font-size: 14px; text-transform: none; color: var(--color-text);">Sublimation Printing</div>
                <p style="font-size: 13px; color: var(--color-text-muted); margin-top: 8px;">Vibrant all-over sublimation that never peels, cracks, or fades</p>
            </div>
            <div class="stat-item reveal" style="transition-delay: 0.2s">
                <div class="stat-number" style="font-size: 24px;">🧵</div>
                <div class="stat-label" style="margin-top: 12px; font-size: 14px; text-transform: none; color: var(--color-text);">Embroidery Available</div>
                <p style="font-size: 13px; color: var(--color-text-muted); margin-top: 8px;">Premium embroidery for logos, team names, and player numbers</p>
            </div>
            <div class="stat-item reveal" style="transition-delay: 0.3s">
                <div class="stat-number" style="font-size: 24px;">📏</div>
                <div class="stat-label" style="margin-top: 12px; font-size: 14px; text-transform: none; color: var(--color-text);">Custom Sizing</div>
                <p style="font-size: 13px; color: var(--color-text-muted); margin-top: 8px;">Youth to adult sizing, custom measurements available for teams</p>
            </div>
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <button class="btn btn-primary" onclick="openQuoteModal()">Request a Free Quote</button>
        </div>
    </div>
</section>

<section class="section" style="background: var(--color-bg-alt);">
    <div class="container">
        <div class="section-header reveal">
            <span class="section-label">Service Areas</span>
            <h2 class="section-title">Serving Teams Across the USA</h2>
            <p class="section-desc">We ship sports uniforms to all 50 states. Here are key locations we serve from coast to coast:</p>
        </div>
        <div class="country-grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));">
            <a href="/locations/california" class="country-card reveal" style="padding: 20px;">
                <div class="country-flag" style="width: 48px; height: 48px; font-size: 20px;">🇺🇸</div>
                <h3 class="country-name" style="font-size: 16px;">California</h3>
            </a>
            <a href="/locations/florida" class="country-card reveal" style="padding: 20px; transition-delay: 0.05s;">
                <div class="country-flag" style="width: 48px; height: 48px; font-size: 20px;">🇺🇸</div>
                <h3 class="country-name" style="font-size: 16px;">Florida</h3>
            </a>
            <a href="/locations/texas" class="country-card reveal" style="padding: 20px; transition-delay: 0.1s;">
                <div class="country-flag" style="width: 48px; height: 48px; font-size: 20px;">🇺🇸</div>
                <h3 class="country-name" style="font-size: 16px;">Texas</h3>
            </a>
            <a href="/locations/new-york" class="country-card reveal" style="padding: 20px; transition-delay: 0.15s;">
                <div class="country-flag" style="width: 48px; height: 48px; font-size: 20px;">🇺🇸</div>
                <h3 class="country-name" style="font-size: 16px;">New York</h3>
            </a>
            <a href="/locations/illinois" class="country-card reveal" style="padding: 20px; transition-delay: 0.2s;">
                <div class="country-flag" style="width: 48px; height: 48px; font-size: 20px;">🇺🇸</div>
                <h3 class="country-name" style="font-size: 16px;">Illinois</h3>
            </a>
            <a href="/locations/georgia" class="country-card reveal" style="padding: 20px; transition-delay: 0.25s;">
                <div class="country-flag" style="width: 48px; height: 48px; font-size: 20px;">🇺🇸</div>
                <h3 class="country-name" style="font-size: 16px;">Georgia</h3>
            </a>
            <a href="/locations/ohio" class="country-card reveal" style="padding: 20px; transition-delay: 0.3s;">
                <div class="country-flag" style="width: 48px; height: 48px; font-size: 20px;">🇺🇸</div>
                <h3 class="country-name" style="font-size: 16px;">Ohio</h3>
            </a>
            <a href="/locations/pennsylvania" class="country-card reveal" style="padding: 20px; transition-delay: 0.35s;">
                <div class="country-flag" style="width: 48px; height: 48px; font-size: 20px;">🇺🇸</div>
                <h3 class="country-name" style="font-size: 16px;">Pennsylvania</h3>
            </a>
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="/locations" class="btn btn-primary">View All States</a>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="cta-section-bg"></div>
    <div class="cta-section-accent"></div>
    <div class="container">
        <div class="cta-section-content reveal">
            <span class="section-label">Get Started Today</span>
            <h2 class="cta-section-title">Ready for Custom Sports Uniforms?</h2>
            <p class="cta-section-desc">Whether you need uniforms for a school team, college program, professional league, or sports brand, we deliver premium quality at factory-direct prices. Request your free quote today.</p>
            <div class="cta-section-buttons">
                <button class="btn btn-primary btn-lg" onclick="openQuoteModal()">Request a Quote</button>
                <a href="/contact" class="btn btn-outline btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
