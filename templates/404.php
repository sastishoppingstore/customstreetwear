<?php
/**
 * Custom Streetwear - 404 Page
 */
require_once __DIR__ . '/../includes/functions.php';
$metaTags = generateMetaTags('Page Not Found', 'The page you are looking for does not exist.');
include __DIR__ . '/../includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="error-404">
            <div class="error-404-code">404</div>
            <h1 class="error-404-title">Page Not Found</h1>
            <p class="error-404-desc">The page you are looking for doesn't exist or has been moved.</p>
            <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
                <a href="/" class="btn btn-primary btn-lg">Go Home</a>
                <a href="/contact" class="btn btn-outline btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
