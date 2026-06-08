<?php
/**
 * Custom Streetwear - Frontend Header Template
 */

$headerMenu = getMenu('header');
$siteName = getSetting('site_name', 'Custom Streetwear');
$sitePhone = getSetting('site_phone', '');
$siteEmail = getSetting('site_email', '');
$siteAddress = getSetting('site_address', '');
$logoText = getSetting('site_logo_text', 'CUSTOM STREETWEAR');
$logoTagline = getSetting('site_logo_tagline', 'Custom Apparel Manufacturer');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <?php echo $metaTags ?? generateMetaTags(); ?>
    <?php echo hreflangTags(); ?>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/uploads/settings/favicon.ico">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo filemtime(CSW_ROOT . '/assets/css/style.css'); ?>">
    
    <!-- Schema.org -->
    <script type="application/ld+json">
    <?php echo organizationSchema(); ?>
    </script>
    <script type="application/ld+json">
    <?php echo localBusinessSchema(); ?>
    </script>
    
    <?php echo $extraHead ?? ''; ?>
</head>
<body>
    <!-- Top Contact Bar -->
    <div class="top-bar" id="topBar">
        <div class="container">
            <div class="top-bar-left">
                <span class="top-bar-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                    <a href="tel:<?php echo e($sitePhone); ?>"><?php echo e($sitePhone); ?></a>
                </span>
                <span class="top-bar-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <a href="mailto:<?php echo e($siteEmail); ?>"><?php echo e($siteEmail); ?></a>
                </span>
            </div>
            <div class="top-bar-right">
                <span class="top-bar-item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?php echo e($siteAddress); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header class="main-header" id="mainHeader">
        <div class="container">
            <div class="header-inner">
                <!-- Logo -->
                <a href="/" class="logo" aria-label="<?php echo e($siteName); ?> - Home">
                    <span class="logo-text"><?php echo e($logoText); ?></span>
                    <span class="logo-tagline"><?php echo e($logoTagline); ?></span>
                </a>

                <!-- Desktop Navigation -->
                <nav class="main-nav" aria-label="Main Navigation">
                    <ul class="nav-list">
                        <?php foreach ($headerMenu as $item): ?>
                        <li class="nav-item <?php echo !empty($item['children']) ? 'has-dropdown' : ''; ?>">
                            <a href="<?php echo e($item['url']); ?>" class="nav-link" <?php echo $item['url'] === '#' ? 'onclick="return false;"' : ''; ?>>
                                <?php echo e($item['title']); ?>
                                <?php if (!empty($item['children'])): ?>
                                <svg class="dropdown-arrow" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="6 9 12 15 18 9"/></svg>
                                <?php endif; ?>
                            </a>
                            <?php if (!empty($item['children'])): ?>
                            <div class="mega-menu">
                                <div class="mega-menu-inner">
                                    <div class="mega-menu-grid">
                                        <?php foreach ($item['children'] as $child): ?>
                                        <a href="<?php echo e($child['url']); ?>" class="mega-menu-item">
                                            <span class="mega-menu-title"><?php echo e($child['title']); ?></span>
                                        </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions">
                    <button class="btn btn-primary btn-quote" onclick="openQuoteModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Request a Quote
                    </button>
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle Menu" onclick="toggleMobileMenu()">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-inner">
            <nav class="mobile-nav" aria-label="Mobile Navigation">
                <ul class="mobile-nav-list">
                    <?php foreach ($headerMenu as $item): ?>
                    <li class="mobile-nav-item <?php echo !empty($item['children']) ? 'has-children' : ''; ?>">
                        <a href="<?php echo e($item['url'] !== '#' ? $item['url'] : 'javascript:void(0)'); ?>" class="mobile-nav-link" onclick="<?php echo !empty($item['children']) ? 'toggleMobileSubmenu(this)' : ''; ?>">
                            <?php echo e($item['title']); ?>
                            <?php if (!empty($item['children'])): ?>
                            <svg class="submenu-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
                            <?php endif; ?>
                        </a>
                        <?php if (!empty($item['children'])): ?>
                        <ul class="mobile-submenu">
                            <?php foreach ($item['children'] as $child): ?>
                            <li><a href="<?php echo e($child['url']); ?>"><?php echo e($child['title']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <div class="mobile-actions">
                <button class="btn btn-primary btn-block" onclick="openQuoteModal(); toggleMobileMenu();">
                    Request a Quote
                </button>
                <div class="mobile-contact">
                    <a href="tel:<?php echo e($sitePhone); ?>"><?php echo e($sitePhone); ?></a>
                    <a href="mailto:<?php echo e($siteEmail); ?>"><?php echo e($siteEmail); ?></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>

    <!-- Main Content Start -->
    <main id="mainContent">
