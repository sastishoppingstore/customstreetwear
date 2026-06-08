-- ============================================
-- Custom Streetwear v2 - SEO + Settings Migration
-- ============================================

-- SEO Analysis table (RankMath-like)
DROP TABLE IF EXISTS `seo_analysis`;
CREATE TABLE `seo_analysis` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` int(11) UNSIGNED NOT NULL,
  `focus_keyword` varchar(200) DEFAULT NULL,
  `seo_score` int(11) DEFAULT 0,
  `readability_score` int(11) DEFAULT 0,
  `analysis_data` longtext DEFAULT NULL,
  `last_analyzed` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `entity` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Redirect Manager
DROP TABLE IF EXISTS `redirects`;
CREATE TABLE `redirects` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `old_url` varchar(500) NOT NULL,
  `new_url` varchar(500) NOT NULL,
  `redirect_type` enum('301','302') DEFAULT '301',
  `status` tinyint(1) DEFAULT 1,
  `hits` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `old_url` (`old_url`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Site Reviews / Testimonials Extended
DROP TABLE IF EXISTS `site_reviews`;
CREATE TABLE `site_reviews` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `platform` varchar(50) DEFAULT 'google',
  `reviewer_name` varchar(200) NOT NULL,
  `rating` decimal(2,1) DEFAULT 5.0,
  `review_text` text DEFAULT NULL,
  `review_url` varchar(500) DEFAULT NULL,
  `review_date` date DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- FAQ Categories
DROP TABLE IF EXISTS `faq_categories`;
CREATE TABLE `faq_categories` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'question',
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add faq_category_id to faqs table
ALTER TABLE `faqs` ADD COLUMN IF NOT EXISTS `faq_category_id` int(11) UNSIGNED DEFAULT NULL AFTER `id`;
ALTER TABLE `faqs` ADD COLUMN IF NOT EXISTS `ai_summary` text DEFAULT NULL AFTER `answer`;
ALTER TABLE `faqs` ADD COLUMN IF NOT EXISTS `related_keywords` text DEFAULT NULL AFTER `ai_summary`;

-- Add SEO columns to pages if missing
ALTER TABLE `pages` ADD COLUMN IF NOT EXISTS `focus_keyword` varchar(200) DEFAULT NULL AFTER `seo_description`;
ALTER TABLE `pages` ADD COLUMN IF NOT EXISTS `robots_meta` varchar(100) DEFAULT 'index,follow' AFTER `focus_keyword`;
ALTER TABLE `pages` ADD COLUMN IF NOT EXISTS `canonical_url` varchar(500) DEFAULT NULL AFTER `robots_meta`;
ALTER TABLE `pages` ADD COLUMN IF NOT EXISTS `og_title` varchar(200) DEFAULT NULL AFTER `canonical_url`;
ALTER TABLE `pages` ADD COLUMN IF NOT EXISTS `og_description` text DEFAULT NULL AFTER `og_title`;
ALTER TABLE `pages` ADD COLUMN IF NOT EXISTS `og_image` varchar(255) DEFAULT NULL AFTER `og_description`;

-- Add SEO columns to categories if missing
ALTER TABLE `categories` ADD COLUMN IF NOT EXISTS `focus_keyword` varchar(200) DEFAULT NULL AFTER `seo_description`;
ALTER TABLE `categories` ADD COLUMN IF NOT EXISTS `robots_meta` varchar(100) DEFAULT 'index,follow' AFTER `focus_keyword`;
ALTER TABLE `categories` ADD COLUMN IF NOT EXISTS `canonical_url` varchar(500) DEFAULT NULL AFTER `robots_meta`;
ALTER TABLE `categories` ADD COLUMN IF NOT EXISTS `og_title` varchar(200) DEFAULT NULL AFTER `canonical_url`;
ALTER TABLE `categories` ADD COLUMN IF NOT EXISTS `og_description` text DEFAULT NULL AFTER `og_title`;
ALTER TABLE `categories` ADD COLUMN IF NOT EXISTS `og_image` varchar(255) DEFAULT NULL AFTER `og_description`;

-- Add SEO columns to products if missing
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `focus_keyword` varchar(200) DEFAULT NULL AFTER `seo_description`;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `robots_meta` varchar(100) DEFAULT 'index,follow' AFTER `focus_keyword`;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `canonical_url` varchar(500) DEFAULT NULL AFTER `robots_meta`;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `og_title` varchar(200) DEFAULT NULL AFTER `canonical_url`;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `og_description` text DEFAULT NULL AFTER `og_title`;
ALTER TABLE `products` ADD COLUMN IF NOT EXISTS `og_image` varchar(255) DEFAULT NULL AFTER `og_description`;

-- Add SEO columns to blogs if missing
ALTER TABLE `blogs` ADD COLUMN IF NOT EXISTS `focus_keyword` varchar(200) DEFAULT NULL AFTER `seo_description`;
ALTER TABLE `blogs` ADD COLUMN IF NOT EXISTS `robots_meta` varchar(100) DEFAULT 'index,follow' AFTER `focus_keyword`;
ALTER TABLE `blogs` ADD COLUMN IF NOT EXISTS `canonical_url` varchar(500) DEFAULT NULL AFTER `robots_meta`;
ALTER TABLE `blogs` ADD COLUMN IF NOT EXISTS `og_title` varchar(200) DEFAULT NULL AFTER `canonical_url`;
ALTER TABLE `blogs` ADD COLUMN IF NOT EXISTS `og_description` text DEFAULT NULL AFTER `og_title`;
ALTER TABLE `blogs` ADD COLUMN IF NOT EXISTS `og_image` varchar(255) DEFAULT NULL AFTER `og_description`;

-- Homepage Sections table for full editability
DROP TABLE IF EXISTS `home_sections`;
CREATE TABLE `home_sections` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_key` varchar(100) NOT NULL,
  `section_name` varchar(200) NOT NULL,
  `section_type` enum('hero','categories','bestsellers','about','whychoose','featured','tech','videos','brochures','testimonials','locations','blogs','cta','custom') DEFAULT 'custom',
  `title` varchar(500) DEFAULT NULL,
  `subtitle` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `image_alt` varchar(255) DEFAULT NULL,
  `button_text` varchar(200) DEFAULT NULL,
  `button_link` varchar(500) DEFAULT NULL,
  `background_color` varchar(50) DEFAULT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `custom_css` text DEFAULT NULL,
  `custom_html` text DEFAULT NULL,
  `visibility` enum('visible','hidden') DEFAULT 'visible',
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `section_key` (`section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert home sections
INSERT INTO `home_sections` (`section_key`, `section_name`, `section_type`, `visibility`, `sort_order`) VALUES
('hero', 'Hero Slider', 'hero', 'visible', 1),
('categories', 'Product Categories', 'categories', 'visible', 2),
('bestsellers', 'Best Sellers', 'bestsellers', 'visible', 3),
('about', 'About Section', 'about', 'visible', 4),
('whychoose', 'Why Choose Us', 'whychoose', 'visible', 5),
('featured', 'Featured Products', 'featured', 'visible', 6),
('tech', 'Technology', 'tech', 'visible', 7),
('videos', 'Factory Videos', 'videos', 'visible', 8),
('brochures', 'Brochures', 'brochures', 'visible', 9),
('testimonials', 'Testimonials', 'testimonials', 'visible', 10),
('locations', 'USA Locations', 'locations', 'visible', 11),
('blogs', 'Latest Blogs', 'blogs', 'visible', 12),
('cta', 'CTA Section', 'cta', 'visible', 13);

-- Add more settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_type`) VALUES
('home_hero_3d_enabled', '1', 'boolean'),
('home_hero_auto_slide', '1', 'boolean'),
('home_hero_slide_interval', '5000', 'number'),
('home_3d_particle_count', '800', 'number'),
('home_3d_shape_count', '25', 'number'),
('home_3d_color', '#39ff14', 'text'),
('home_animation_speed', '1', 'number'),
('home_reveal_animation', 'fadeUp', 'text'),
('site_logo_image', '/uploads/settings/logo.png', 'image'),
('site_logo_dark', '/uploads/settings/logo.png', 'image'),
('favicon', '/uploads/settings/favicon.ico', 'image'),
('site_favicon', '/uploads/settings/favicon.ico', 'image'),
('site_favicon_192', '/uploads/settings/favicon.ico', 'image'),
('site_favicon_512', '/uploads/settings/favicon.ico', 'image'),
('site_apple_touch_icon', '/uploads/settings/favicon.ico', 'image'),
('seo_twitter_handle', '@customstreetwear', 'text'),
('seo_facebook_page_id', '', 'text'),
('seo_google_verification', '', 'text'),
('seo_bing_verification', '', 'text'),
('seo_facebook_pixel', '', 'textarea'),
('seo_tiktok_pixel', '', 'textarea'),
('seo_gtm_head', '', 'textarea'),
('seo_gtm_body', '', 'textarea'),
('seo_custom_head_code', '', 'textarea'),
('seo_custom_body_code', '', 'textarea'),
('seo_schema_organization', '', 'textarea'),
('seo_schema_website', '', 'textarea'),
('seo_breadcrumb_separator', '/', 'text'),
('seo_noindex_empty', '0', 'boolean'),
('seo_enable_sitemap', '1', 'boolean'),
('seo_enable_robots', '1', 'boolean'),
('seo_sitemap_exclude', '', 'textarea'),
('seo_robots_custom', '', 'textarea'),
('theme_primary_color', '#39ff14', 'text'),
('theme_secondary_color', '#00ccff', 'text'),
('theme_dark_bg', '#0a0a0a', 'text'),
('theme_card_bg', '#161616', 'text'),
('theme_border_color', '#2a2a2a', 'text'),
('theme_accent_glow', 'rgba(57, 255, 20, 0.15)', 'text'),
('theme_font_primary', 'Inter', 'text'),
('theme_font_display', 'Oswald', 'text'),
('theme_custom_css', '', 'textarea'),
('home_hero_overlay_opacity', '85', 'number'),
('home_hero_overlay_color', '#050505', 'text'),
('home_section_spacing', '80', 'number'),
('home_card_animation_delay', '0.1', 'text'),
('home_counter_duration', '2000', 'number'),
('site_cta_phone', '+1-555-123-4567', 'text'),
('site_cta_email', 'sales@customstreetwear.co', 'email'),
('site_cta_text', 'Call Us Today', 'text'),
('site_trust_badges', '[]', 'textarea'),
('site_payment_methods', '["visa","mastercard","amex","paypal","bank-transfer"]', 'textarea'),
('site_shipping_info', 'Free shipping on orders over $500', 'text'),
('site_return_days', '30', 'number'),
('site_satisfaction_badge', '100% Satisfaction Guaranteed', 'text'),
('home_trust_bar_enabled', '1', 'boolean'),
('home_trust_bar_text', 'Trusted by 2500+ USA Brands | Factory-Direct Pricing | 100% Quality Guaranteed', 'text'),
('home_urgent_badge_text', 'Bulk Orders Welcome | Ships Within 15-20 Days', 'text'),
('home_locations_target_keyword', 'Custom Apparel Manufacturer in USA', 'text'),
('home_sports_uniforms_target', 'Custom Sports Uniforms Manufacturer in USA', 'text'),
('home_sports_uniforms_desc', 'Premium custom sports uniforms manufacturer serving teams, schools, and clubs across the USA. Factory-direct pricing, custom designs, fast delivery.', 'textarea'),
('site_psycology_first_look', '1', 'boolean'),
('site_scarcity_enabled', '1', 'boolean'),
('site_social_proof_enabled', '1', 'boolean'),
('site_urgency_enabled', '1', 'boolean')
ON DUPLICATE KEY UPDATE setting_key=setting_key;

-- 2FA and Remember Me columns for admins
ALTER TABLE `admins` ADD COLUMN IF NOT EXISTS `2fa_secret` varchar(255) DEFAULT NULL AFTER `failed_attempts`;
ALTER TABLE `admins` ADD COLUMN IF NOT EXISTS `2fa_enabled` tinyint(1) DEFAULT 0 AFTER `2fa_secret`;
ALTER TABLE `admins` ADD COLUMN IF NOT EXISTS `remember_token` varchar(255) DEFAULT NULL AFTER `2fa_enabled`;
ALTER TABLE `admins` ADD COLUMN IF NOT EXISTS `remember_expires` datetime DEFAULT NULL AFTER `remember_token`;

-- Location SEO content table
DROP TABLE IF EXISTS `location_seo`;
CREATE TABLE `location_seo` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `location_type` enum('state','city') NOT NULL,
  `state_slug` varchar(100) NOT NULL,
  `city_slug` varchar(100) DEFAULT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `h1_heading` varchar(255) DEFAULT NULL,
  `content_top` longtext DEFAULT NULL,
  `content_bottom` longtext DEFAULT NULL,
  `focus_keyword` varchar(200) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `location` (`location_type`, `state_slug`, `city_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `location_seo` (`location_type`, `state_slug`, `city_slug`, `page_title`, `meta_description`, `h1_heading`, `content_top`, `content_bottom`, `focus_keyword`, `status`) VALUES
('city', 'florida', 'miami', 'Apparel Manufacturer in Miami, Florida', 'Apparel Manufacturer in Miami, Florida for custom sports uniforms, streetwear, workwear, promotional apparel, private label clothing, and bulk branded apparel with USA delivery.', 'Apparel Manufacturer in Miami, Florida', '<h2>Custom Apparel Manufacturing for Miami Buyers</h2><p>Custom Streetwear serves Miami brands, teams, schools, agencies, event companies, and businesses that need custom apparel production with clear quoting, custom decoration, bulk order support, and reliable delivery.</p>', '<h2>Miami Apparel Products We Support</h2><p>Order custom sports uniforms, hoodies, t-shirts, workwear, jackets, promotional apparel, private label clothing, embroidery, screen printing, sublimation, and cut-and-sew apparel for Miami, Florida projects.</p>', 'Apparel Manufacturer in Miami, Florida', 1)
ON DUPLICATE KEY UPDATE page_title=VALUES(page_title), meta_description=VALUES(meta_description), h1_heading=VALUES(h1_heading), content_top=VALUES(content_top), content_bottom=VALUES(content_bottom), focus_keyword=VALUES(focus_keyword), status=VALUES(status);
