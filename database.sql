-- Custom Streetwear - Complete Database
-- Domain: customstreetwear.co
-- Charset: utf8mb4_unicode_ci

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables
DROP TABLE IF EXISTS `login_attempts`;
DROP TABLE IF EXISTS `color_charts`;
DROP TABLE IF EXISTS `menus`;
DROP TABLE IF EXISTS `seo_meta`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `enquiries`;
DROP TABLE IF EXISTS `blogs`;
DROP TABLE IF EXISTS `states_cities`;
DROP TABLE IF EXISTS `countries`;
DROP TABLE IF EXISTS `testimonials`;
DROP TABLE IF EXISTS `videos`;
DROP TABLE IF EXISTS `brochures`;
DROP TABLE IF EXISTS `fabrics`;
DROP TABLE IF EXISTS `customisations`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `subcategories`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `sliders`;
DROP TABLE IF EXISTS `pages`;
DROP TABLE IF EXISTS `site_settings`;
DROP TABLE IF EXISTS `admins`;

-- Table: admins
CREATE TABLE `admins` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','editor') DEFAULT 'editor',
  `status` tinyint(1) DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default Admin (password: Admin@12345)
INSERT INTO `admins` (`name`, `email`, `password_hash`, `role`, `status`) VALUES
('Super Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 1);

-- Table: site_settings
CREATE TABLE `site_settings` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','image','boolean','number','email','url') DEFAULT 'text',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Site Settings Seed Data
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_type`) VALUES
('site_name', 'Custom Streetwear', 'text'),
('site_domain', 'customstreetwear.co', 'text'),
('site_email', 'info@customstreetwear.co', 'email'),
('site_phone', '+92-331-0000000', 'text'),
('site_whatsapp', '+92-331-0000000', 'text'),
('site_address', 'Bajwa Street Rangpura, Sialkot - 51310, Pakistan', 'textarea'),
('site_logo_text', 'CUSTOM STREETWEAR', 'text'),
('seo_title', 'Custom Streetwear | Custom Sportswear, Streetwear & Apparel Manufacturer', 'text'),
('seo_description', 'Custom Streetwear is a premium custom sportswear, streetwear, workwear, uniform, promotional apparel, and leatherwear manufacturing/export brand serving global markets.', 'textarea'),
('seo_keywords', 'custom streetwear, sportswear manufacturer, apparel export, custom hoodies, tracksuits, uniforms, workwear, promotional apparel, leather jackets', 'textarea'),
('og_image', '/uploads/settings/og-image.jpg', 'image'),
('favicon', '/uploads/settings/favicon.ico', 'image'),
('footer_text', 'Custom Streetwear manufactures custom apparel, streetwear, sportswear, uniforms, workwear, promotional products, and leatherwear for global brands, clubs, teams, and businesses.', 'textarea'),
('copyright_text', 'Custom Streetwear. All Rights Reserved.', 'text'),
('facebook_url', 'https://facebook.com/customstreetwear', 'url'),
('instagram_url', 'https://instagram.com/customstreetwear', 'url'),
('twitter_url', 'https://twitter.com/customstreetwear', 'url'),
('youtube_url', 'https://youtube.com/@customstreetwear', 'url'),
('linkedin_url', 'https://linkedin.com/company/customstreetwear', 'url'),
('google_map_iframe', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d107687.123456789!2d74.5123!3d32.4945!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzLCsDI5JzQwLjIiTiA3NMKwMzAnNDQuMyJF!5e0!3m2!1sen!2s!4v1234567890" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>', 'textarea'),
('home_hero_title', 'PREMIUM CUSTOM APPAREL MANUFACTURER', 'text'),
('home_hero_subtitle', 'Custom Streetwear, Sportswear, Workwear, Uniforms & Leatherwear', 'text'),
('about_since_year', '2012', 'text'),
('about_ceo_name', 'CEO Name', 'text'),
('about_ceo_message', 'Content pending: replace from Admin Panel after authorized copy is provided.', 'textarea'),
('analytics_code', '', 'textarea'),
('maintenance_mode', '0', 'boolean'),
('quote_email_subject', 'New Quote Request - Custom Streetwear', 'text'),
('contact_email_subject', 'New Contact Message - Custom Streetwear', 'text'),
('items_per_page', '12', 'number'),
('products_per_page', '24', 'number'),
('blogs_per_page', '9', 'number'),
('enable_registration', '0', 'boolean'),
('recaptcha_site_key', '', 'text'),
('recaptcha_secret_key', '', 'text'),
('smtp_host', '', 'text'),
('smtp_port', '587', 'number'),
('smtp_username', '', 'text'),
('smtp_password', '', 'text'),
('smtp_encryption', 'tls', 'text'),
('whatsapp_button_number', '+923310000000', 'text'),
('whatsapp_button_message', 'Hi, I am interested in custom apparel from Custom Streetwear.', 'text');

-- Table: pages
CREATE TABLE `pages` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `page_type` enum('static','dynamic','market','service','landing') DEFAULT 'static',
  `short_description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pages Seed Data
INSERT INTO `pages` (`title`, `slug`, `page_type`, `short_description`, `content`, `sort_order`) VALUES
('About Us', 'about-us', 'static', 'Learn about Custom Streetwear, a leading manufacturer of custom sportswear, streetwear, and apparel since 2012.', '<h2>About Custom Streetwear</h2><p>Custom Streetwear got its start in 2012, right in the middle of Sialkot, Pakistan, where making great sports gear is just part of everyday life. With our experienced leadership at the helm, we wanted to do more than just churn out uniforms—we wanted to help teams actually feel good and look like they belong out there.</p><p>We put together a mix of old-school craftsmanship and whatever new tricks and ideas we can find, plus we pay attention to what athletes actually go through, not just what looks good on paper. That is probably why we managed to become one of the top sportswear manufacturers in Sialkot, Pakistan, where our name stands for both great gear and genuine customer relationships.</p><p>People come to us for custom kits that hold up, feel good, and help teams stand out, even if the game gets a little rough. We have built our name not just on quality, but on trust and word-of-mouth from teams who keep coming back.</p><h3>Our Process</h3><p>We keep it all in-house, making sure every step—from first sketch to final stitch—happens right under our own roof for total control and quality. We design, dig through fabric choices, sew, finish, and give everything a last once-over before it ships. That way, nothing gets missed and we can stand behind every piece that leaves our place.</p><h3>Our Mission</h3><p>We are not stuck on big or small orders—if you need gear for the whole league or just one local team, we will sort it out and keep things friendly, because every team has got its own vibe. For us, a uniform is not just some random shirt or pair of shorts; it is something a team can get behind and feel proud of.</p><h3>Our Vision</h3><p>Now that we have shipped gear all over the place, we are still looking for ways to up our game, trying out new materials and designs so our stuff keeps getting better. Every single product we send out is made with care and a genuine passion for sports, because when a team throws on Custom Streetwear gear, we want them to feel like they have already got a bit of an edge—even before the whistle blows.</p>', 1),
('What We Do', 'what-we-do', 'service', 'Custom Streetwear offers complete manufacturing services for sportswear, streetwear, workwear, uniforms, and promotional apparel.', '<h2>What We Do</h2><p>At Custom Streetwear, we specialize in manufacturing premium quality custom apparel for brands, teams, clubs, and businesses worldwide. Our comprehensive services cover every aspect of apparel production from design to delivery.</p><h3>Custom Sportswear Manufacturing</h3><p>We design and manufacture high-performance sportswear including football jerseys, basketball uniforms, baseball kits, soccer apparel, and more. Our sportswear is built for performance, comfort, and durability.</p><h3>Streetwear & Fashion Apparel</h3><p>From trendy hoodies and tracksuits to stylish jackets and casual wear, we create streetwear that makes a statement. Our designs combine urban aesthetics with premium quality materials.</p><h3>Workwear & Uniforms</h3><p>We provide professional workwear, corporate uniforms, medical scrubs, safety gear, and industrial clothing that meets industry standards while keeping workers comfortable and protected.</p><h3>Promotional Products</h3><p>Elevate your brand with our custom promotional apparel including branded t-shirts, caps, bags, towels, and accessories perfect for events, giveaways, and marketing campaigns.</p><h3>Leather Products</h3><p>Our leather division crafts premium leather jackets, motorcycle gear, and fashion accessories with attention to detail and superior craftsmanship.</p><h3>Private Label & OEM Services</h3><p>We offer complete private label manufacturing services allowing you to build your own brand with our production capabilities. From concept to finished product, we handle everything.</p>', 2),
('How We Do', 'how-we-do', 'service', 'Our step-by-step manufacturing process ensures quality at every stage from design to delivery.', '<h2>How We Do It</h2><p>Our manufacturing process is designed to ensure the highest quality standards at every stage. Here is how we bring your custom apparel to life:</p><h3>01. Design & Tech Pack</h3><p>We start by understanding your vision. Our design team creates detailed tech packs with specifications, measurements, materials, and construction details. You can provide your own designs or work with our team to create something unique.</p><h3>02. Fabric Sourcing</h3><p>We source premium fabrics from trusted suppliers worldwide. Whether you need moisture-wicking polyester, soft cotton blends, durable nylon, or sustainable eco-friendly materials, we have access to the best fabrics in the industry.</p><h3>03. Pattern Making</h3><p>Our experienced pattern makers create precise patterns that ensure perfect fit and comfort. We use both traditional techniques and digital pattern making software for accuracy.</p><h3>04. Sampling</h3><p>Before full production, we create samples for your approval. This allows you to check the fit, fabric, colors, and overall quality. We make adjustments until you are completely satisfied.</p><h3>05. Cutting</h3><p>Using advanced cutting technology including computer-controlled cutting machines, we ensure precision and minimize waste. Each piece is cut with accuracy for consistent sizing.</p><h3>06. Stitching & Assembly</h3><p>Our skilled craftsmen assemble each garment with precision stitching using industrial-grade equipment. We pay attention to every seam, hem, and detail.</p><h3>07. Printing & Embroidery</h3><p>We apply your custom designs using various techniques including sublimation printing, screen printing, embroidery, applique, heat transfer, and DTG printing depending on your requirements.</p><h3>08. Quality Control</h3><p>Every garment undergoes rigorous quality inspection. We check for stitching quality, print accuracy, sizing consistency, and overall finish. Only products that meet our standards move forward.</p><h3>09. Packing</h3><p>We pack your order carefully with custom packaging options available. Each item is folded, tagged, and packed to ensure it arrives in perfect condition.</p><h3>10. Delivery</h3><p>We ship worldwide using reliable logistics partners. We handle all export documentation and ensure your order reaches you on time, every time.</p>', 3),
('Color Charts', 'color-charts', 'static', 'Browse our extensive color charts to find the perfect colors for your custom apparel.', '<h2>Color Charts</h2><p>Choose from our extensive range of colors to create the perfect look for your custom apparel. Our color charts help you select the exact shades that match your brand or team colors.</p><p>We offer Pantone color matching, custom dye services, and a wide selection of standard colors across all fabric types.</p>', 4),
('Brochures', 'brochures', 'static', 'Download our product catalogs and brochures to explore our complete range of custom apparel manufacturing capabilities.', '<h2>Our Brochures</h2><p>Explore our comprehensive range of premium sportswear and custom apparel through our brochures. Download our catalogs to discover detailed insights into our innovative designs, high-performance fabrics, and versatile manufacturing capabilities.</p>', 5),
('Sitemap', 'sitemap', 'static', 'Browse all pages on Custom Streetwear website.', '<h2>Sitemap</h2><p>Browse all pages, products, categories, and market areas on our website.</p>', 6);

-- Table: sliders
CREATE TABLE `sliders` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `button_text` varchar(100) DEFAULT 'Get a Quote',
  `button_link` varchar(255) DEFAULT '/contact',
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sliders Seed Data
INSERT INTO `sliders` (`title`, `subtitle`, `description`, `image`, `button_text`, `button_link`, `sort_order`) VALUES
('PREMIUM CUSTOM APPAREL', 'Manufacturer & Exporter Since 2012', 'Custom Streetwear is a leading manufacturer of custom sportswear, streetwear, workwear, uniforms, and leather garments serving brands, clubs, and teams worldwide.', '/uploads/sliders/slider-1.jpg', 'Request a Quote', '/contact', 1),
('CUSTOM SPORTSWEAR & UNIFORMS', 'High-Performance Athletic Gear', 'From football jerseys to basketball uniforms, we craft high-performance sportswear that helps athletes perform at their best while looking sharp.', '/uploads/sliders/slider-2.jpg', 'Explore Products', '/products', 2),
('STREETWEAR & FASHION', 'Trendy Designs, Premium Quality', 'Create your own streetwear brand with our custom hoodies, tracksuits, jackets, and fashion apparel manufactured to the highest standards.', '/uploads/sliders/slider-3.jpg', 'View Collection', '/categories', 3),
('WORKWEAR & SAFETY GEAR', 'Professional & Industrial Clothing', 'We manufacture durable workwear, safety coveralls, medical scrubs, and industrial uniforms that meet safety standards and keep workers protected.', '/uploads/sliders/slider-4.jpg', 'Get a Quote', '/contact', 4);

-- Table: categories
CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `seo_title` varchar(200) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories Seed Data
INSERT INTO `categories` (`name`, `slug`, `description`, `seo_title`, `seo_description`, `sort_order`) VALUES
('Hoodies', 'hoodies', 'Custom hoodies in various styles including acid wash, tie-dye, sublimation, oversized, and more. Premium quality fabrics and custom printing options available.', 'Custom Hoodies Manufacturer | Custom Streetwear', 'Premium custom hoodies manufacturer. Acid wash, tie-dye, sublimation, oversized hoodies with custom printing and embroidery. Wholesale prices.', 1),
('Tracksuits', 'tracksuits', 'High-quality custom tracksuits for sports teams, gyms, and fashion brands. Available in various fabrics, fits, and custom design options.', 'Custom Tracksuits Manufacturer | Custom Streetwear', 'Custom tracksuits manufacturer for sports teams and brands. Premium fabrics, custom designs, wholesale tracksuits from Pakistan.', 2),
('T-Shirts', 't-shirts', 'Custom t-shirts including sublimation, polo shirts, flannel shirts, and more. Perfect for teams, brands, and promotional use.', 'Custom T-Shirts Manufacturer | Custom Streetwear', 'Premium custom t-shirts manufacturer. Sublimation, polo, flannel shirts with custom printing. Wholesale t-shirts export.', 3),
('Varsity Jackets', 'varsity-jackets', 'Classic custom varsity jackets with wool body, leather sleeves, and custom patches. Perfect for teams, schools, and fashion brands.', 'Custom Varsity Jackets Manufacturer | Custom Streetwear', 'Premium custom varsity jackets manufacturer. Wool and leather varsity jackets with custom patches and embroidery.', 4),
('Softshell Jacket', 'softshell-jacket', 'Durable softshell jackets for outdoor activities, sports teams, and corporate wear. Water-resistant and breathable options available.', 'Custom Softshell Jackets Manufacturer | Custom Streetwear', 'Custom softshell jackets manufacturer. Water-resistant, breathable outdoor jackets for teams and brands.', 5),
('Sports Uniform', 'sports-uniform', 'Complete range of sports uniforms for football, basketball, baseball, soccer, rugby, volleyball, and more. Custom designs and team colors.', 'Custom Sports Uniforms Manufacturer | Custom Streetwear', 'Premium sports uniforms manufacturer. Football, basketball, baseball, soccer, rugby uniforms with custom designs.', 6),
('Promotional Products', 'promotional-products', 'Custom promotional apparel and products including branded hoodies, t-shirts, towels, bags, and accessories for marketing campaigns.', 'Custom Promotional Products Manufacturer | Custom Streetwear', 'Custom promotional products manufacturer. Branded apparel, bags, towels for events and marketing campaigns.', 7),
('Workwear', 'workwear', 'Professional workwear including mechanic uniforms, cleaning uniforms, electrician uniforms, and construction wear. Durable and comfortable.', 'Custom Workwear Manufacturer | Custom Streetwear', 'Custom workwear manufacturer. Mechanic, cleaning, electrician, construction uniforms. Durable professional workwear.', 8),
('Hospital Uniform', 'hospital-uniform', 'Medical uniforms, scrubs, and scrub sets for healthcare professionals. Comfortable, hygienic, and professional medical apparel.', 'Custom Hospital Uniforms & Scrubs Manufacturer | Custom Streetwear', 'Custom hospital uniforms and scrubs manufacturer. Medical apparel, nursing uniforms, scrub sets for healthcare.', 9),
('Bomber Jackets', 'bomber-jackets', 'Stylish custom bomber jackets for fashion brands, teams, and corporate wear. Various materials and custom design options.', 'Custom Bomber Jackets Manufacturer | Custom Streetwear', 'Custom bomber jackets manufacturer. Stylish bomber jackets for brands and teams with custom designs.', 10),
('Winter Coat', 'winter-coat', 'Warm and durable winter coats for extreme weather conditions. Custom designs for brands, teams, and workwear applications.', 'Custom Winter Coats Manufacturer | Custom Streetwear', 'Custom winter coats manufacturer. Warm, durable winter jackets for brands, teams, and workwear.', 11),
('Leather Jackets', 'leather-jackets', 'Premium leather jackets including motorcycle jackets, fashion leatherwear, and custom designs. Genuine and faux leather options.', 'Custom Leather Jackets Manufacturer | Custom Streetwear', 'Premium custom leather jackets manufacturer. Motorcycle jackets, fashion leatherwear with genuine leather.', 12),
('Motorcycle Jackets', 'motorcycle-jackets', 'High-quality motorcycle jackets with protective features. Custom designs for biker brands and motorcycle clubs.', 'Custom Motorcycle Jackets Manufacturer | Custom Streetwear', 'Custom motorcycle jackets manufacturer. Protective biker jackets with custom designs and branding.', 13);

-- Table: subcategories
CREATE TABLE `subcategories` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `seo_title` varchar(200) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_category` (`slug`, `category_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `fk_subcategories_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Subcategories Seed Data
INSERT INTO `subcategories` (`category_id`, `name`, `slug`, `description`, `seo_title`, `sort_order`) VALUES
(1, 'Acid Washed Hoodie', 'acid-washed-hoodie', 'Trendy acid washed hoodies with vintage distressed look. Custom designs, oversized fits, and premium cotton fabrics.', 'Acid Washed Hoodies Manufacturer | Custom Streetwear', 1),
(1, 'Sweatshirts', 'sweatshirts', 'Premium custom sweatshirts in various styles including crew neck, quarter zip, and pullover designs.', 'Custom Sweatshirts Manufacturer | Custom Streetwear', 2),
(1, 'Tye Dye Hoodies', 'tye-dye-hoodies', 'Vibrant tie-dye hoodies in multi-color patterns. Perfect for streetwear brands and fashion collections.', 'Tie Dye Hoodies Manufacturer | Custom Streetwear', 3),
(1, 'Sublimation Hoodie', 'sublimation-hoodie', 'Full sublimation printed hoodies with all-over custom designs. Unlimited colors and patterns.', 'Sublimation Hoodies Manufacturer | Custom Streetwear', 4),
(3, 'Sublimation T Shirts', 'sublimation-t-shirts', 'Full sublimation printed t-shirts with vibrant all-over designs. Perfect for sports teams and brands.', 'Sublimation T-Shirts Manufacturer | Custom Streetwear', 1),
(3, 'Polo Shirts', 'polo-shirts', 'Custom polo shirts for corporate wear, golf, and casual uniforms. Various fabrics and fits available.', 'Custom Polo Shirts Manufacturer | Custom Streetwear', 2),
(3, 'Flannel Shirts', 'flannel-shirts', 'Premium flannel shirts in various patterns and colors. Custom designs for fashion brands.', 'Custom Flannel Shirts Manufacturer | Custom Streetwear', 3),
(6, 'American Football Uniforms', 'american-football-uniforms', 'Professional American football uniforms with pads integration, custom designs, and team colors.', 'American Football Uniforms Manufacturer | Custom Streetwear', 1),
(6, 'Baseball Uniforms', 'baseball-uniforms', 'Complete baseball uniforms including jerseys, pants, and caps. Custom designs for teams and leagues.', 'Baseball Uniforms Manufacturer | Custom Streetwear', 2),
(6, 'Basketball Uniforms', 'basketball-uniforms', 'Lightweight, breathable basketball uniforms with custom designs, numbers, and team branding.', 'Basketball Uniforms Manufacturer | Custom Streetwear', 3),
(6, 'Soccer Uniforms', 'soccer-uniforms', 'Professional soccer uniforms and kits including jerseys, shorts, and socks. Custom designs available.', 'Soccer Uniforms Manufacturer | Custom Streetwear', 4),
(6, 'Rugby Uniforms', 'rugby-uniforms', 'Durable rugby uniforms built to withstand rough play. Custom designs for clubs and teams.', 'Rugby Uniforms Manufacturer | Custom Streetwear', 5),
(6, 'Softball Uniforms', 'softball-uniforms', 'Custom softball uniforms for women''s and men''s teams. Various styles and designs available.', 'Softball Uniforms Manufacturer | Custom Streetwear', 6),
(6, 'Netball Uniforms', 'netball-uniforms', 'Professional netball uniforms and dresses with custom designs and team colors.', 'Netball Uniforms Manufacturer | Custom Streetwear', 7),
(6, 'Lacrosse Uniforms', 'lacrosse-uniforms', 'Custom lacrosse uniforms with reversible options and team branding.', 'Lacrosse Uniforms Manufacturer | Custom Streetwear', 8),
(6, 'Paintball Uniform', 'paintball-uniform', 'Durable paintball uniforms and jerseys with custom designs and player names.', 'Paintball Uniforms Manufacturer | Custom Streetwear', 9),
(6, 'Hockey Uniforms', 'hockey-uniforms', 'Ice and field hockey uniforms with custom designs, team colors, and player numbers.', 'Hockey Uniforms Manufacturer | Custom Streetwear', 10),
(6, 'Volleyball Uniforms', 'volleyball-uniforms', 'Comfortable volleyball uniforms for men''s and women''s teams. Custom designs available.', 'Volleyball Uniforms Manufacturer | Custom Streetwear', 11),
(6, 'Handball Uniforms', 'handball-uniforms', 'Professional handball uniforms with custom designs and team branding.', 'Handball Uniforms Manufacturer | Custom Streetwear', 12),
(6, 'Boxing Uniform', 'boxing-uniform', 'Boxing uniforms, robes, and shorts with custom designs for fighters and gyms.', 'Boxing Uniforms Manufacturer | Custom Streetwear', 13),
(6, 'MMA Uniform', 'mma-uniform', 'MMA fight gear including shorts, rash guards, and walkout shirts with custom designs.', 'MMA Uniforms & Fight Gear Manufacturer | Custom Streetwear', 14),
(6, 'Golf Clothing', 'golf-clothing', 'Premium golf clothing including polo shirts, pants, and jackets with custom branding.', 'Custom Golf Clothing Manufacturer | Custom Streetwear', 15),
(2, 'Leggings', 'leggings', 'Custom leggings for gym, yoga, and fashion. High-quality fabrics with custom prints.', 'Custom Leggings Manufacturer | Custom Streetwear', 1),
(7, 'Promotional Hoodies', 'promotional-hoodies', 'Branded hoodies for promotional campaigns, events, and corporate giveaways.', 'Promotional Hoodies Manufacturer | Custom Streetwear', 1),
(7, 'Promotional T Shirts', 'promotional-t-shirts', 'Custom branded t-shirts for marketing campaigns, events, and promotional giveaways.', 'Promotional T-Shirts Manufacturer | Custom Streetwear', 2),
(7, 'Promotional Towel', 'promotional-towel', 'Custom branded towels for gyms, sports clubs, and promotional events.', 'Promotional Towels Manufacturer | Custom Streetwear', 3),
(7, 'Promotional Bags', 'promotional-bags', 'Custom branded bags including duffel bags, gym bags, and backpacks for promotions.', 'Promotional Bags Manufacturer | Custom Streetwear', 4),
(8, 'Mechanic Uniform', 'mechanic-uniform', 'Durable mechanic uniforms and coveralls with oil-resistant fabrics and custom branding.', 'Mechanic Uniforms Manufacturer | Custom Streetwear', 1),
(8, 'Cleaning Uniform', 'cleaning-uniform', 'Professional cleaning service uniforms that are comfortable and easy to maintain.', 'Cleaning Uniforms Manufacturer | Custom Streetwear', 2),
(8, 'Sanitation Worker Uniform', 'sanitation-worker-uniform', 'High-visibility sanitation worker uniforms with reflective strips and durable fabrics.', 'Sanitation Worker Uniforms Manufacturer | Custom Streetwear', 3),
(8, 'Electrician Uniforms', 'electrician-uniforms', 'Safety electrician uniforms with flame-resistant options and proper insulation.', 'Electrician Uniforms Manufacturer | Custom Streetwear', 4),
(8, 'Construction Wear', 'construction-wear', 'Heavy-duty construction wear including work pants, shirts, and safety vests.', 'Construction Wear Manufacturer | Custom Streetwear', 5),
(8, 'Kitchenware', 'kitchenware', 'Chef coats, kitchen uniforms, and hospitality apparel for restaurants and hotels.', 'Kitchen Uniforms Manufacturer | Custom Streetwear', 6),
(8, 'Safety Coverall', 'safety-coverall', 'Full-body safety coveralls for industrial work with high-visibility and protective features.', 'Safety Coveralls Manufacturer | Custom Streetwear', 7),
(8, 'Safety Shirt', 'safety-shirt', 'High-visibility safety shirts with reflective strips for construction and industrial workers.', 'Safety Shirts Manufacturer | Custom Streetwear', 8),
(8, 'Safety Jacket', 'safety-jacket', 'Protective safety jackets for hazardous work environments with weather-resistant features.', 'Safety Jackets Manufacturer | Custom Streetwear', 9),
(8, 'Safety Pant', 'safety-pant', 'Durable safety pants with reinforced knees and high-visibility options.', 'Safety Pants Manufacturer | Custom Streetwear', 10),
(8, 'Safety Vests', 'safety-vests', 'High-visibility safety vests for traffic control, construction, and emergency services.', 'Safety Vests Manufacturer | Custom Streetwear', 11),
(8, 'Bib Overalls', 'bib-overalls', 'Heavy-duty bib overalls for industrial and agricultural work with custom branding.', 'Bib Overalls Manufacturer | Custom Streetwear', 12),
(9, 'Medical Uniform', 'medical-uniform', 'Professional medical uniforms for doctors, nurses, and healthcare staff.', 'Medical Uniforms Manufacturer | Custom Streetwear', 1),
(9, 'Scrub', 'scrub', 'Comfortable medical scrubs in various colors and styles for healthcare professionals.', 'Medical Scrubs Manufacturer | Custom Streetwear', 2),
(9, 'Scrub Sets', 'scrub-sets', 'Complete scrub sets including top and bottom with custom embroidery options.', 'Scrub Sets Manufacturer | Custom Streetwear', 3);

-- Table: products
CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(11) UNSIGNED NOT NULL,
  `subcategory_id` int(11) UNSIGNED DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `full_description` longtext DEFAULT NULL,
  `features` text DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `sizes` varchar(255) DEFAULT NULL,
  `colors` varchar(500) DEFAULT NULL,
  `customization_options` text DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `seo_title` varchar(200) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_best_seller` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  KEY `subcategory_id` (`subcategory_id`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_products_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products Seed Data
INSERT INTO `products` (`category_id`, `subcategory_id`, `title`, `slug`, `sku`, `short_description`, `full_description`, `features`, `specifications`, `sizes`, `colors`, `is_featured`, `is_best_seller`, `sort_order`) VALUES
(1, 1, 'Custom Acid Washed Hoodie', 'custom-acid-washed-hoodie', 'CSW-HD-001', 'Trendy acid washed hoodie with vintage distressed look and premium cotton fabric.', '<p>Our Custom Acid Washed Hoodie features a unique vintage distressed look that is perfect for streetwear brands and fashion collections. Made from premium cotton fleece, these hoodies offer exceptional comfort and style.</p><h3>Key Features</h3><ul><li>Acid washed vintage finish</li><li>Premium 80% cotton, 20% polyester fleece</li><li>Oversized relaxed fit</li><li>Double-lined hood</li><li>Kangaroo pocket</li><li>Ribbed cuffs and hem</li></ul>', 'Acid washed vintage finish, Premium cotton fleece, Oversized fit, Double-lined hood, Kangaroo pocket', 'Material: 80% Cotton, 20% Polyester\nWeight: 320-350 GSM\nFit: Oversized/Relaxed\nWash: Acid Washed\nSizes: XS-3XL', 'XS,S,M,L,XL,2XL,3XL', 'Black,Grey,Blue,Green,Custom', 1, 1, 1),
(1, 3, 'Custom Tie Dye Hoodie', 'custom-tie-dye-hoodie', 'CSW-HD-002', 'Vibrant tie-dye hoodie in multi-color patterns for streetwear fashion brands.', '<p>Create eye-catching apparel with our Custom Tie Dye Hoodies. Each piece features unique multi-color patterns that make every hoodie one-of-a-kind. Perfect for streetwear brands, festivals, and fashion collections.</p><h3>Key Features</h3><ul><li>Hand-crafted tie-dye patterns</li><li>Multi-color options available</li><li>Soft cotton blend fabric</li><li>Unique design on every piece</li><li>Drawstring hood</li><li>Kangaroo pocket</li></ul>', 'Hand-crafted tie-dye, Multi-color patterns, Cotton blend, Unique designs, Drawstring hood', 'Material: 100% Cotton\nWeight: 300-320 GSM\nFit: Regular/Oversized\nStyle: Tie-Dye\nSizes: S-3XL', 'S,M,L,XL,2XL,3XL', 'Rainbow,Blue/White,Pastel,Custom', 1, 0, 2),
(1, 4, 'Custom Sublimation Hoodie', 'custom-sublimation-hoodie', 'CSW-HD-003', 'Full sublimation printed hoodie with all-over custom designs and unlimited colors.', '<p>Our Custom Sublimation Hoodies allow for unlimited color options and all-over printing. The dye-sublimation process ensures vibrant, long-lasting prints that won''t crack, peel, or fade.</p><h3>Key Features</h3><ul><li>All-over sublimation printing</li><li>Unlimited colors and designs</li><li>Moisture-wicking fabric</li><li>Vibrant, permanent prints</li><li>Full customization</li></ul>', 'All-over sublimation, Unlimited colors, Moisture-wicking, Permanent prints, Full custom designs', 'Material: 100% Polyester\nWeight: 280-300 GSM\nPrinting: Dye Sublimation\nFit: Regular\nSizes: XS-3XL', 'XS,S,M,L,XL,2XL,3XL', 'Full Color Custom', 1, 1, 3),
(1, 2, 'Custom Sweatshirt', 'custom-sweatshirt', 'CSW-HD-004', 'Premium custom sweatshirt with crew neck, quarter zip, and pullover options.', '<p>Our Custom Sweatshirts are crafted from premium fabrics for ultimate comfort and durability. Available in crew neck, quarter zip, and pullover styles to suit your brand needs.</p><h3>Key Features</h3><ul><li>Multiple style options</li><li>Premium fleece fabric</li><li>Ribbed cuffs and hem</li><li>Custom embroidery/print</li></ul>', 'Crew neck, Quarter zip, Pullover, Premium fleece, Custom branding', 'Material: 80% Cotton, 20% Polyester\nWeight: 300-350 GSM\nStyles: Crew, Quarter Zip\nSizes: S-3XL', 'S,M,L,XL,2XL,3XL', 'Black,Grey,Navy,White,Custom', 0, 0, 4),
(2, 23, 'Custom Tracksuit Set', 'custom-tracksuit-set', 'CSW-TR-001', 'High-quality custom tracksuit set for sports teams, gyms, and fashion brands.', '<p>Our Custom Tracksuit Sets combine style and functionality. Perfect for sports teams, gym wear, and fashion brands seeking premium quality athletic apparel.</p><h3>Key Features</h3><ul><li>Jacket and pants set</li><li>Premium polyester fabric</li><li>Custom design options</li><li>Elastic cuffs and waistband</li></ul>', 'Jacket + Pants set, Polyester fabric, Custom designs, Elastic details', 'Material: 100% Polyester\nWeight: 220-250 GSM\nFit: Regular/Relaxed\nSizes: XS-3XL', 'XS,S,M,L,XL,2XL,3XL', 'Black,Navy,Red,Custom', 1, 1, 5),
(3, 5, 'Custom Sublimation T-Shirt', 'custom-sublimation-t-shirt', 'CSW-TS-001', 'Full sublimation printed t-shirt with vibrant all-over designs.', '<p>Create stunning all-over designs with our Custom Sublimation T-Shirts. Perfect for sports teams, events, and brands looking for vibrant, full-color printing.</p><h3>Key Features</h3><ul><li>All-over sublimation printing</li><li>Vibrant, permanent colors</li><li>Lightweight fabric</li><li>Breathable and comfortable</li></ul>', 'All-over sublimation, Vibrant colors, Lightweight, Breathable', 'Material: 100% Polyester\nWeight: 140-160 GSM\nPrinting: Dye Sublimation\nSizes: XS-3XL', 'XS,S,M,L,XL,2XL,3XL', 'Full Color Custom', 1, 1, 6),
(3, 6, 'Custom Polo Shirt', 'custom-polo-shirt', 'CSW-TS-002', 'Custom polo shirts for corporate wear, golf, and casual uniforms.', '<p>Our Custom Polo Shirts offer a professional look with comfortable wear. Available in various fabrics including pique cotton, jersey, and performance blends.</p><h3>Key Features</h3><ul><li>Professional collar</li><li>2-3 button placket</li><li>Multiple fabric options</li><li>Custom embroidery</li></ul>', 'Professional collar, Button placket, Multiple fabrics, Custom embroidery', 'Material: 100% Cotton Pique\nWeight: 200-220 GSM\nFit: Regular\nSizes: S-3XL', 'S,M,L,XL,2XL,3XL', 'White,Black,Navy,Red,Custom', 0, 0, 7),
(4, NULL, 'Custom Varsity Jacket', 'custom-varsity-jacket', 'CSW-VJ-001', 'Classic varsity jacket with wool body, leather sleeves, and custom patches.', '<p>Our Custom Varsity Jackets feature the classic wool body with genuine leather sleeves. Add custom patches, embroidery, and chenille lettering to create a unique piece for your team or brand.</p><h3>Key Features</h3><ul><li>Wool body with leather sleeves</li><li>Custom chenille patches</li><li>Quilted lining</li><li> Snap button closure</li><li>Ribbed collar, cuffs, and hem</li></ul>', 'Wool body, Leather sleeves, Chenille patches, Quilted lining, Snap buttons', 'Body: Wool Melton\nSleeves: Genuine Leather\nLining: Quilted Satin\nSizes: XS-3XL', 'XS,S,M,L,XL,2XL,3XL', 'Black/White,Navy/White,Red/White,Custom', 1, 1, 8),
(5, NULL, 'Custom Softshell Jacket', 'custom-softshell-jacket', 'CSW-SJ-001', 'Durable softshell jacket for outdoor activities and corporate wear.', '<p>Our Custom Softshell Jackets offer water-resistant and breathable protection for outdoor activities. Perfect for sports teams, corporate wear, and outdoor brands.</p><h3>Key Features</h3><ul><li>Water-resistant outer layer</li><li>Breathable membrane</li><li>Fleece-lined interior</li><li>Adjustable hood and cuffs</li></ul>', 'Water-resistant, Breathable, Fleece-lined, Adjustable features', 'Material: 94% Polyester, 6% Spandex\nWeight: 320 GSM\nWater Resistance: 5000mm\nSizes: XS-3XL', 'XS,S,M,L,XL,2XL,3XL', 'Black,Navy,Grey,Red,Custom', 1, 0, 9),
(6, 8, 'American Football Uniform', 'american-football-uniform', 'CSW-SU-001', 'Professional American football uniform with integrated pads and custom design.', '<p>Our American Football Uniforms are designed for peak performance with integrated pad pockets, durable construction, and bold custom designs that stand out on the field.</p><h3>Key Features</h3><ul><li>Integrated pad pockets</li><li>Durable double-knit fabric</li><li>Full sublimation printing</li><li>Reinforced stitching</li></ul>', 'Integrated pads, Durable fabric, Full sublimation, Reinforced stitching', 'Material: 100% Polyester\nWeight: 250 GSM\nPrinting: Dye Sublimation\nSizes: YS-3XL', 'YS,S,M,L,XL,2XL,3XL', 'Full Color Custom', 1, 1, 10),
(6, 9, 'Baseball Uniform', 'baseball-uniform', 'CSW-SU-002', 'Complete baseball uniform including jersey and pants with custom designs.', '<p>Our Baseball Uniforms include button-down jerseys and matching pants with professional-grade fabrics and custom team designs.</p><h3>Key Features</h3><ul><li>Button-down jersey</li><li>Matching pants</li><li>Professional-grade fabric</li><li>Custom numbers and names</li></ul>', 'Button-down jersey, Matching pants, Pro fabric, Custom numbers', 'Material: 100% Polyester\nWeight: 200 GSM\nStyle: Button-down\nSizes: YS-3XL', 'YS,S,M,L,XL,2XL,3XL', 'Full Color Custom', 1, 0, 11),
(6, 10, 'Basketball Uniform', 'basketball-uniform', 'CSW-SU-003', 'Lightweight, breathable basketball uniform with custom designs.', '<p>Our Basketball Uniforms feature lightweight, moisture-wicking fabrics that keep players cool and comfortable during intense games.</p><h3>Key Features</h3><ul><li>Lightweight fabric</li><li>Moisture-wicking</li><li>Reversible options</li><li>Custom designs</li></ul>', 'Lightweight, Moisture-wicking, Reversible, Custom designs', 'Material: 100% Polyester\nWeight: 160 GSM\nFit: Athletic\nSizes: YS-3XL', 'YS,S,M,L,XL,2XL,3XL', 'Full Color Custom', 1, 1, 12),
(6, 11, 'Soccer Uniform Kit', 'soccer-uniform-kit', 'CSW-SU-004', 'Professional soccer kit including jersey, shorts, and socks.', '<p>Complete soccer kits with jersey, shorts, and socks. Designed for performance with breathable fabrics and vibrant custom designs.</p><h3>Key Features</h3><ul><li>Complete kit (jersey, shorts, socks)</li><li>Breathable fabric</li><li>Full sublimation</li><li>Team customization</li></ul>', 'Complete kit, Breathable, Full sublimation, Team customization', 'Material: 100% Polyester\nWeight: 140-160 GSM\nKit: Jersey+Shorts+Socks\nSizes: YS-3XL', 'YS,S,M,L,XL,2XL,3XL', 'Full Color Custom', 1, 1, 13),
(6, 12, 'Rugby Uniform', 'rugby-uniform', 'CSW-SU-005', 'Durable rugby uniform built to withstand rough play and scrums.', '<p>Our Rugby Uniforms are built tough to withstand the physical demands of the sport. Reinforced stitching and durable fabrics ensure longevity.</p><h3>Key Features</h3><ul><li>Heavy-duty fabric</li><li>Reinforced stitching</li><li>Tight fit design</li><li>Team customization</li></ul>', 'Heavy-duty fabric, Reinforced stitching, Tight fit, Custom designs', 'Material: 100% Polyester\nWeight: 280 GSM\nFit: Athletic/Tight\nSizes: XS-3XL', 'XS,S,M,L,XL,2XL,3XL', 'Full Color Custom', 0, 0, 14),
(6, 17, 'Hockey Uniform', 'hockey-uniform', 'CSW-SU-006', 'Ice and field hockey uniforms with custom designs.', '<p>Professional hockey uniforms designed for both ice and field hockey with durable fabrics and bold custom designs.</p>', 'Durable fabric, Bold designs, Team colors, Custom numbers', 'Material: 100% Polyester\nWeight: 220 GSM\nSizes: YS-3XL', 'YS,S,M,L,XL,2XL,3XL', 'Full Color Custom', 0, 0, 15),
(7, 24, 'Promotional Hoodie', 'promotional-hoodie', 'CSW-PP-001', 'Branded promotional hoodie for marketing campaigns and events.', '<p>High-quality branded hoodies perfect for corporate giveaways, marketing campaigns, and promotional events. Add your logo and brand colors.</p>', 'Custom branding, Logo placement, Multiple colors, Bulk orders', 'Material: 80% Cotton, 20% Polyester\nWeight: 280 GSM\nSizes: S-3XL', 'S,M,L,XL,2XL,3XL', 'Custom Brand Colors', 0, 0, 16),
(7, 25, 'Promotional T-Shirt', 'promotional-t-shirt', 'CSW-PP-002', 'Custom branded t-shirt for marketing and promotional use.', '<p>Affordable, high-quality branded t-shirts perfect for large-scale marketing campaigns and events.</p>', 'Custom print, Bulk pricing, Fast turnaround, Multiple colors', 'Material: 100% Cotton\nWeight: 160 GSM\nSizes: S-3XL', 'S,M,L,XL,2XL,3XL', 'Custom Brand Colors', 0, 0, 17),
(8, 27, 'Mechanic Uniform', 'mechanic-uniform', 'CSW-WW-001', 'Durable mechanic uniform with oil-resistant fabrics.', '<p>Professional mechanic uniforms designed to withstand oil, grease, and heavy use while keeping workers comfortable.</p>', 'Oil-resistant, Durable fabric, Multiple pockets, Custom logo', 'Material: 65% Polyester, 35% Cotton\nWeight: 240 GSM\nSizes: S-3XL', 'S,M,L,XL,2XL,3XL', 'Navy,Black,Grey,Custom', 0, 0, 18),
(8, 34, 'Safety Coverall', 'safety-coverall', 'CSW-WW-002', 'Full-body safety coverall for industrial work environments.', '<p>High-visibility safety coveralls with reflective strips for maximum protection in industrial environments.</p>', 'High-visibility, Reflective strips, Full coverage, Durable', 'Material: 65% Polyester, 35% Cotton\nWeight: 260 GSM\nSizes: S-4XL', 'S,M,L,XL,2XL,3XL,4XL', 'Orange,Yellow,Navy,Custom', 1, 0, 19),
(9, 43, 'Medical Scrub Set', 'medical-scrub-set', 'CSW-HU-001', 'Complete medical scrub set for healthcare professionals.', '<p>Comfortable, hygienic medical scrub sets including top and bottom with custom embroidery options.</p>', 'Comfortable fit, Easy care, Custom embroidery, Multiple colors', 'Material: 65% Polyester, 35% Cotton\nWeight: 160 GSM\nSizes: XS-3XL', 'XS,S,M,L,XL,2XL,3XL', 'Blue,Green,Grey,White,Custom', 1, 1, 20),
(12, NULL, 'Custom Leather Jacket', 'custom-leather-jacket', 'CSW-LJ-001', 'Premium leather jacket with custom designs and genuine leather options.', '<p>Handcrafted leather jackets made from genuine or faux leather with custom designs, linings, and hardware options.</p>', 'Genuine leather, Custom lining, Multiple styles, Handcrafted', 'Material: Genuine Cowhide Leather\nLining: Satin\nSizes: S-3XL', 'S,M,L,XL,2XL,3XL', 'Black,Brown,Red,Custom', 1, 1, 21),
(13, NULL, 'Custom Motorcycle Jacket', 'custom-motorcycle-jacket', 'CSW-MJ-001', 'High-quality motorcycle jacket with protective features.', '<p>Protective motorcycle jackets with CE-approved armor, premium leather, and custom designs for bikers and motorcycle clubs.</p>', 'CE armor, Premium leather, Protective features, Custom designs', 'Material: Genuine Leather\nArmor: CE Approved\nSizes: S-3XL', 'S,M,L,XL,2XL,3XL', 'Black,Brown,Custom', 1, 0, 22);

-- Table: product_images
CREATE TABLE `product_images` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(11) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: customisations
CREATE TABLE `customisations` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `seo_title` varchar(200) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Customisations Seed Data
INSERT INTO `customisations` (`title`, `slug`, `description`, `icon`, `seo_title`, `sort_order`) VALUES
('Sublimation', 'sublimation', 'Dye sublimation printing allows for full-color, all-over designs that become part of the fabric. Perfect for complex designs and photographic prints on polyester fabrics.', 'sublimation', 'Custom Sublimation Printing | Custom Streetwear', 1),
('Cut & Sew', 'cut-and-sew', 'Our cut and sew manufacturing process allows for complete customization of patterns, panels, and construction. Create unique garment designs with different fabrics and colors in different areas.', 'cut-sew', 'Cut & Sew Manufacturing | Custom Streetwear', 2),
('Screen Printing', 'screen-printing', 'Traditional screen printing offers vibrant, long-lasting prints on cotton and cotton-blend fabrics. Cost-effective for larger orders with spot color designs.', 'screen', 'Custom Screen Printing | Custom Streetwear', 3),
('Embroidery & Applique', 'embroidery-applique', 'Professional embroidery and applique services for logos, names, and decorative elements. Adds a premium, textured look to your apparel.', 'embroidery', 'Custom Embroidery & Applique | Custom Streetwear', 4),
('Private Label', 'private-label', 'Build your own brand with our private label services. We manufacture products with your branding, tags, and packaging, ready for retail sale.', 'label', 'Private Label Manufacturing | Custom Streetwear', 5),
('Custom Logo', 'custom-logo', 'Add your logo to any product through various methods including embroidery, woven labels, rubber patches, heat transfer, and direct printing.', 'logo', 'Custom Logo Services | Custom Streetwear', 6),
('Custom Packaging', 'custom-packaging', 'Complete custom packaging solutions including poly bags, hang tags, tissue paper, and boxes with your branding for a premium unboxing experience.', 'package', 'Custom Packaging Solutions | Custom Streetwear', 7);

-- Table: fabrics
CREATE TABLE `fabrics` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `specs` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery_images` text DEFAULT NULL,
  `seo_title` varchar(200) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Fabrics Seed Data
INSERT INTO `fabrics` (`title`, `slug`, `category`, `description`, `specs`, `seo_title`, `sort_order`) VALUES
('Cotton Fleece', 'cotton-fleece', 'Knitted', 'Soft and warm cotton fleece fabric perfect for hoodies, sweatshirts, and casual wear. Available in various weights and blends.', 'Composition: 80% Cotton, 20% Polyester\nWeight: 300-400 GSM\nWidth: 60 inches\nAvailable Colors: 50+', 'Cotton Fleece Fabric | Custom Streetwear', 1),
('Polyester Spandex', 'polyester-spandex', 'Knitted', 'Stretchy polyester spandex blend ideal for sportswear, activewear, and fitted garments. Excellent moisture-wicking properties.', 'Composition: 88% Polyester, 12% Spandex\nWeight: 180-220 GSM\nWidth: 60 inches\nStretch: 4-way', 'Polyester Spandex Fabric | Custom Streetwear', 2),
('Mesh Fabric', 'mesh-fabric', 'Knitted', 'Breathable mesh fabric for sports jerseys, lining, and ventilation panels. Available in various hole sizes and patterns.', 'Composition: 100% Polyester\nWeight: 120-160 GSM\nWidth: 60 inches\nTypes: Micro, Mini, Standard', 'Mesh Fabric | Custom Streetwear', 3),
('Interlock Fabric', 'interlock-fabric', 'Knitted', 'Smooth, double-knit interlock fabric ideal for polo shirts, uniforms, and premium t-shirts. Soft hand feel and excellent drape.', 'Composition: 100% Cotton or Cotton/Poly blend\nWeight: 180-240 GSM\nWidth: 60 inches\nFinish: Soft hand feel', 'Interlock Fabric | Custom Streetwear', 4),
('Taslan Nylon', 'taslan-nylon', 'Woven', 'Durable taslan nylon fabric for outdoor jackets, windbreakers, and bags. Water-resistant and quick-drying properties.', 'Composition: 100% Nylon\nWeight: 100-120 GSM\nWidth: 58 inches\nFinish: Water-resistant', 'Taslan Nylon Fabric | Custom Streetwear', 5),
('Softshell Fabric', 'softshell-fabric', 'Woven', 'Three-layer softshell fabric with membrane for outdoor and performance jackets. Windproof, water-resistant, and breathable.', 'Composition: 94% Polyester, 6% Spandex + TPU membrane\nWeight: 320 GSM\nWidth: 58 inches\nWater resistance: 5000mm', 'Softshell Fabric | Custom Streetwear', 6),
('Wool Melton', 'wool-melton', 'Woven', 'Heavy wool melton fabric for varsity jackets, coats, and premium outerwear. Dense, warm, and wind-resistant.', 'Composition: 80% Wool, 20% Nylon\nWeight: 700-900 GSM\nWidth: 60 inches\nColors: Standard + Custom dye', 'Wool Melton Fabric | Custom Streetwear', 7),
('Genuine Leather', 'genuine-leather', 'Leather', 'Premium genuine cowhide leather for jackets, motorcycle gear, and accessories. Various finishes and colors available.', 'Type: Cowhide\nThickness: 0.8-1.2mm\nFinishes: Aniline, Semi-aniline, Corrected\nColors: Custom dye available', 'Genuine Leather | Custom Streetwear', 8),
('Dry Fit Fabric', 'dry-fit-fabric', 'Performance', 'High-performance moisture-wicking fabric for athletic wear. Quick-dry technology keeps athletes cool and comfortable.', 'Composition: 100% Polyester\nWeight: 140-160 GSM\nWidth: 60 inches\nFeature: Moisture-wicking', 'Dry Fit Fabric | Custom Streetwear', 9),
('Scuba Fabric', 'scuba-fabric', 'Knitted', 'Smooth, neoprene-like scuba fabric for fashion apparel, skirts, and structured garments. Excellent shape retention.', 'Composition: 95% Polyester, 5% Spandex\nWeight: 230-260 GSM\nWidth: 60 inches\nFeature: Shape retention', 'Scuba Fabric | Custom Streetwear', 10);

-- Table: brochures
CREATE TABLE `brochures` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `file_type` varchar(20) DEFAULT 'pdf',
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Brochures Seed Data
INSERT INTO `brochures` (`title`, `description`, `file_type`, `sort_order`) VALUES
('Customised Oversized Apparel', 'Complete catalog of our oversized apparel collection including hoodies, t-shirts, and streetwear.', 'pdf', 1),
('Fitness & Gym Wear', 'Browse our fitness and gym wear catalog featuring tracksuits, leggings, and activewear.', 'pdf', 2),
('Mens Clothing', 'Complete mens clothing catalog with t-shirts, polo shirts, flannel shirts, and more.', 'pdf', 3),
('Varsity Jackets', 'Custom varsity jackets catalog with various styles, materials, and customization options.', 'pdf', 4),
('Softshell/Bomber Jackets', 'Outerwear catalog featuring softshell jackets, bomber jackets, and winter coats.', 'pdf', 5),
('Sports Uniforms', 'Complete sports uniforms catalog for football, basketball, baseball, soccer, and more.', 'pdf', 6),
('Workwear & Safety', 'Professional workwear and safety gear catalog for industrial and corporate needs.', 'pdf', 7),
('Promotional Products', 'Custom promotional products catalog for marketing campaigns and corporate giveaways.', 'pdf', 8);

-- Table: videos
CREATE TABLE `videos` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `video_type` enum('youtube','vimeo','upload') DEFAULT 'youtube',
  `video_url` varchar(500) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Videos Seed Data
INSERT INTO `videos` (`title`, `description`, `video_type`, `video_url`, `sort_order`) VALUES
('Varsity Jacket Step By Step Production Insights', 'See how we craft our premium varsity jackets from start to finish. Behind the scenes production footage.', 'youtube', 'https://www.youtube.com/watch?v=kYVZ-xazX6A', 1),
('Unit 10 Tons per day Production', 'Tour our manufacturing facility with 10 tons per day production capacity. See our scale and capabilities.', 'youtube', 'https://www.youtube.com/watch?v=aDEa6UkY9fg', 2),
('High End Clothing Manufacturing', 'Learn about our high-end custom clothing manufacturing process and quality standards.', 'youtube', 'https://www.youtube.com/watch?v=Rs7PbQD1_Go', 3),
('Custom Sportswear Production Process', 'Watch our complete custom sportswear production process from design to delivery.', 'youtube', 'https://www.youtube.com/embed/customstreetwear-process', 4),
('Factory Tour - Custom Streetwear', 'Take a virtual tour of our state-of-the-art manufacturing facility in Sialkot, Pakistan.', 'youtube', 'https://www.youtube.com/embed/customstreetwear-factory', 5);

-- Table: testimonials
CREATE TABLE `testimonials` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_name` varchar(100) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `rating` int(1) DEFAULT 5,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Testimonials Seed Data
INSERT INTO `testimonials` (`client_name`, `country`, `company`, `message`, `rating`, `sort_order`) VALUES
('Omar Al Mansoori', 'UAE', 'Al Mansoori Medical Supplies', 'We have tried several suppliers, but Custom Streetwear stands out for comfort and durability. Their hospital uniforms fit great, breathe well, and still look fresh after months of daily washing. The custom embroidery quality is exceptional.', 5, 1),
('Carlos Navarro', 'Spain', 'Navarro Sports Club', 'Our crew looks sharp and feels comfortable in their new uniforms from Custom Streetwear. The material is strong, the fit is perfect, and the embroidered logos look professional. Highly recommended for any sports team.', 5, 2),
('Anastasia Volkova', 'Russia', 'Volkova Events Moscow', 'We needed branded gear for an event, and Custom Streetwear nailed it. The T-shirts, hats, and bags were top-notch in both quality and design. Everything arrived on time and exactly as specified.', 5, 3),
('Jonas Muller', 'Germany', 'FC Dynamo Berlin', 'These soccer uniforms are fantastic! They are breathable, durable, and stylish—exactly what our team needed. Every player loved the fit and feel. Custom Streetwear delivered beyond expectations.', 5, 4),
('Noah Tremblay', 'Canada', 'Toronto Basketball Academy', 'Our basketball team could not be happier with our new uniforms! Lightweight, breathable, and perfectly tailored for movement. The custom prints look amazing. Str', 5, 5),
('Emily Lawson', 'Australia', 'Lawson Baseball League', 'These baseball uniforms exceeded our expectations. Comfortable, durable, and stylish—even after many games. The custom designs were flawless and arrived quickly. Will definitely order again.', 5, 6),
('Marco Rossi', 'Italy', 'Rossi Fight Gear', 'The boxing uniforms and fight gear we ordered are top quality. The satin fabric feels premium and the embroidery work is precise. Our fighters love wearing them to the ring.', 5, 7),
('James Wilson', 'USA', 'Wilson Fitness Centers', 'We have been ordering custom tracksuits and gym wear from Custom Streetwear for over 3 years. The quality is consistently excellent and their customer service is outstanding.', 5, 8);

-- Table: countries
CREATE TABLE `countries` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `flag_image` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `seo_title` varchar(200) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Countries Seed Data (Primary Markets)
INSERT INTO `countries` (`name`, `slug`, `short_description`, `content`, `seo_title`, `sort_order`) VALUES
('Australia', 'australia', 'Custom Streetwear exports premium sportswear, streetwear, and uniforms to Australia. We serve sports clubs, schools, and fashion brands across Sydney, Melbourne, Brisbane, and beyond.', '<h2>Custom Apparel Manufacturer & Exporter to Australia</h2><p>Custom Streetwear is a trusted manufacturer and exporter of custom sportswear, streetwear, workwear, and uniforms to Australia. We have been serving Australian sports clubs, schools, corporate businesses, and fashion brands since 2012.</p><h3>Products We Export to Australia</h3><ul><li>Custom sports uniforms (cricket, rugby, AFL, netball, soccer)</li><li>Streetwear and hoodies</li><li>Workwear and safety gear</li><li>School uniforms</li><li>Promotional apparel</li><li>Leather jackets</li></ul><h3>Shipping & Delivery</h3><p>We ship to all major Australian cities including Sydney, Melbourne, Brisbane, Perth, Adelaide, and Canberra. Delivery typically takes 3-4 weeks depending on order size and customization requirements.</p>', 'Custom Apparel Manufacturer & Exporter to Australia | Custom Streetwear', 1),
('Canada', 'canada', 'Premium custom apparel manufacturer and exporter to Canada. Serving hockey teams, sports clubs, and fashion brands across Toronto, Vancouver, Montreal, and all provinces.', '<h2>Custom Apparel Manufacturer & Exporter to Canada</h2><p>Custom Streetwear proudly manufactures and exports premium custom apparel to Canada. From hockey uniforms to winter wear, we serve Canadian sports teams, clubs, and businesses with top-quality garments.</p><h3>Products for Canadian Market</h3><ul><li>Ice hockey uniforms and jerseys</li><li>Winter coats and jackets</li><li>Basketball and baseball uniforms</li><li>Streetwear collections</li><li>Workwear for harsh winters</li><li>Promotional products</li></ul><h3>Provinces We Serve</h3><p>We ship to all Canadian provinces including Ontario, British Columbia, Quebec, Alberta, Manitoba, and the Atlantic provinces.</p>', 'Custom Apparel Manufacturer & Exporter to Canada | Custom Streetwear', 2),
('Germany', 'germany', 'Leading custom sportswear manufacturer exporting to Germany. Premium quality football kits, teamwear, and streetwear for German clubs and brands.', '<h2>Custom Apparel Manufacturer & Exporter to Germany</h2><p>Custom Streetwear exports high-quality custom apparel to Germany, serving football clubs, sports teams, and fashion brands with precision-engineered garments that meet German quality standards.</p><h3>Products for German Market</h3><ul><li>Football (soccer) kits and uniforms</li><li>Handball and volleyball uniforms</li><li>Streetwear and fashion apparel</li><li>Workwear and safety clothing</li><li>Corporate uniforms</li></ul>', 'Custom Apparel Manufacturer & Exporter to Germany | Custom Streetwear', 3),
('Italy', 'italy', 'Premium custom apparel manufacturing for the Italian market. Fashion-forward streetwear, sportswear, and leather products.', '<h2>Custom Apparel Manufacturer & Exporter to Italy</h2><p>Italy is known for fashion, and Custom Streetwear meets that standard with premium custom apparel manufacturing. We serve Italian fashion brands, sports clubs, and businesses with style-forward garments.</p><h3>Products for Italian Market</h3><ul><li>Fashion streetwear collections</li><li>Premium leather jackets</li><li>Soccer uniforms</li><li>Motorcycle gear</li><li>Basketball and volleyball kits</li></ul>', 'Custom Apparel Manufacturer & Exporter to Italy | Custom Streetwear', 4),
('Russia', 'russia', 'Custom apparel manufacturer and exporter to Russia. Winter-ready sportswear, workwear, and streetwear for the Russian climate.', '<h2>Custom Apparel Manufacturer & Exporter to Russia</h2><p>Custom Streetwear manufactures and exports custom apparel suited for the Russian climate. From warm winter gear to sports uniforms, we serve Russian teams, businesses, and brands.</p><h3>Products for Russian Market</h3><ul><li>Heavy winter coats and jackets</li><li>Ice hockey uniforms</li><li>Soccer and handball kits</li><li>Workwear for cold climates</li><li>Streetwear collections</li></ul>', 'Custom Apparel Manufacturer & Exporter to Russia | Custom Streetwear', 5),
('Spain', 'spain', 'Custom sportswear and streetwear manufacturer exporting to Spain. Football kits, basketball uniforms, and fashion apparel.', '<h2>Custom Apparel Manufacturer & Exporter to Spain</h2><p>Custom Streetwear exports premium custom apparel to Spain, serving football clubs, basketball teams, and fashion brands with high-quality, stylish garments.</p><h3>Products for Spanish Market</h3><ul><li>Football (soccer) kits</li><li>Basketball uniforms</li><li>Streetwear and casual wear</li><li>Motorcycle jackets</li><li>Promotional products</li></ul>', 'Custom Apparel Manufacturer & Exporter to Spain | Custom Streetwear', 6),
('UAE', 'uae', 'Custom uniform and sportswear manufacturer for the UAE market. Corporate uniforms, medical scrubs, and teamwear.', '<h2>Custom Apparel Manufacturer & Exporter to UAE</h2><p>Custom Streetwear is a leading supplier of custom apparel to the UAE, serving businesses, medical facilities, sports clubs, and government organizations across Dubai, Abu Dhabi, and Sharjah.</p><h3>Products for UAE Market</h3><ul><li>Corporate uniforms</li><li>Medical scrubs and hospital uniforms</li><li>Soccer and cricket kits</li><li>Construction safety wear</li><li>Promotional apparel</li></ul>', 'Custom Apparel Manufacturer & Exporter to UAE | Custom Streetwear', 7),
('UK', 'uk', 'Custom sportswear and streetwear manufacturer exporting to the United Kingdom. Football kits, rugby wear, and fashion apparel.', '<h2>Custom Apparel Manufacturer & Exporter to UK</h2><p>Custom Streetwear exports premium custom apparel to the United Kingdom, serving football clubs, rugby teams, schools, and fashion brands across England, Scotland, Wales, and Northern Ireland.</p><h3>Products for UK Market</h3><ul><li>Football kits and training wear</li><li>Rugby uniforms</li><li>Cricket whites and colored kits</li><li>Streetwear collections</li><li>School uniforms</li><li>Workwear</li></ul>', 'Custom Apparel Manufacturer & Exporter to UK | Custom Streetwear', 8),
('USA', 'usa', 'Leading custom apparel manufacturer exporting to the United States. NFL, NBA, MLB style uniforms, streetwear, and workwear.', '<h2>Custom Apparel Manufacturer & Exporter to USA</h2><p>Custom Streetwear is a premier manufacturer and exporter of custom apparel to the United States. We serve sports teams, fashion brands, corporate clients, and promotional companies across all 50 states.</p><h3>Products for US Market</h3><ul><li>American football uniforms</li><li>Basketball jerseys and shorts</li><li>Baseball uniforms</li><li>Softball and lacrosse kits</li><li>Streetwear and hoodies</li><li>Workwear and safety gear</li><li>Promotional products</li></ul><h3>States We Serve</h3><p>We ship to all US states including California, Texas, Florida, New York, Pennsylvania, Ohio, Georgia, North Carolina, and all other states.</p>', 'Custom Apparel Manufacturer & Exporter to USA | Custom Streetwear', 9);

-- Table: states_cities
CREATE TABLE `states_cities` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `country_id` int(11) UNSIGNED NOT NULL,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `type` enum('state','city') DEFAULT 'city',
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `seo_title` varchar(200) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `fk_states_cities_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- USA States Seed Data
INSERT INTO `states_cities` (`country_id`, `type`, `name`, `slug`, `short_description`, `content`, `seo_title`, `sort_order`) VALUES
(9, 'state', 'California', 'california', 'Custom apparel manufacturer serving California. We ship custom sportswear, streetwear, and uniforms to Los Angeles, San Francisco, San Diego, and throughout the Golden State.', '<h2>Custom Apparel Manufacturer for California</h2><p>Custom Streetwear serves the entire state of California with premium custom apparel manufacturing services. From LA fashion brands to Bay Area tech companies and San Diego sports teams, we deliver quality custom garments across the Golden State.</p><h3>Areas Served</h3><ul><li>Los Angeles</li><li>San Francisco</li><li>San Diego</li><li>Sacramento</li><li>San Jose</li><li>Fresno</li><li>Oakland</li></ul>', 'Custom Apparel Manufacturer California | Custom Streetwear', 1),
(9, 'state', 'Florida', 'florida', 'Custom sportswear and streetwear manufacturer serving Florida. Serving Miami, Orlando, Tampa, and Jacksonville with premium custom apparel.', '<h2>Custom Apparel Manufacturer for Florida</h2><p>Custom Streetwear manufactures and exports premium custom apparel to Florida. We serve Miami fashion brands, Orlando sports teams, Tampa businesses, and Jacksonville organizations with top-quality garments.</p><h3>Areas Served</h3><ul><li>Miami</li><li>Orlando</li><li>Tampa</li><li>Jacksonville</li><li>Fort Lauderdale</li></ul>', 'Custom Apparel Manufacturer Florida | Custom Streetwear', 2),
(9, 'state', 'Georgia', 'georgia', 'Custom apparel manufacturer for Georgia. Atlanta sports teams, businesses, and fashion brands trust us for quality custom garments.', '<h2>Custom Apparel Manufacturer for Georgia</h2><p>Custom Streetwear exports custom apparel to Georgia, serving Atlanta sports clubs, businesses, and organizations with premium quality uniforms and sportswear.</p><h3>Areas Served</h3><ul><li>Atlanta</li><li>Savannah</li><li>Augusta</li><li>Columbus</li></ul>', 'Custom Apparel Manufacturer Georgia | Custom Streetwear', 3),
(9, 'state', 'New Jersey', 'new-jersey', 'Custom sportswear manufacturer serving New Jersey. Premium uniforms and teamwear for NJ sports clubs and schools.', '<h2>Custom Apparel Manufacturer for New Jersey</h2><p>Custom Streetwear serves New Jersey with custom sportswear, uniforms, and team apparel for sports clubs, schools, and businesses throughout the Garden State.</p>', 'Custom Apparel Manufacturer New Jersey | Custom Streetwear', 4),
(9, 'state', 'New York', 'new-york', 'Leading custom apparel manufacturer for New York. Serving NYC fashion brands, sports teams, and corporate clients.', '<h2>Custom Apparel Manufacturer for New York</h2><p>New York demands the best, and Custom Streetwear delivers. We serve NYC fashion brands, Upstate sports teams, and Long Island businesses with premium custom apparel manufacturing.</p><h3>Areas Served</h3><ul><li>New York City</li><li>Buffalo</li><li>Rochester</li><li>Albany</li><li>Syracuse</li></ul>', 'Custom Apparel Manufacturer New York | Custom Streetwear', 5),
(9, 'state', 'North Carolina', 'north-carolina', 'Custom apparel manufacturer serving North Carolina. Charlotte, Raleigh, and Durham sports teams and businesses.', '<h2>Custom Apparel Manufacturer for North Carolina</h2><p>Custom Streetwear exports custom apparel to North Carolina, serving Charlotte, Raleigh, Durham, and Greensboro with quality sportswear and uniforms.</p>', 'Custom Apparel Manufacturer North Carolina | Custom Streetwear', 6),
(9, 'state', 'Ohio', 'ohio', 'Custom sportswear and uniform manufacturer for Ohio. Serving Cleveland, Columbus, Cincinnati, and statewide.', '<h2>Custom Apparel Manufacturer for Ohio</h2><p>Custom Streetwear manufactures custom apparel for Ohio sports teams, schools, and businesses in Cleveland, Columbus, Cincinnati, and throughout the Buckeye State.</p>', 'Custom Apparel Manufacturer Ohio | Custom Streetwear', 7),
(9, 'state', 'Pennsylvania', 'pennsylvania', 'Custom apparel manufacturer serving Pennsylvania. Philadelphia and Pittsburgh sports clubs trust our quality.', '<h2>Custom Apparel Manufacturer for Pennsylvania</h2><p>Custom Streetwear serves Pennsylvania with custom sportswear, team uniforms, and workwear for Philadelphia, Pittsburgh, and statewide organizations.</p>', 'Custom Apparel Manufacturer Pennsylvania | Custom Streetwear', 8),
(9, 'state', 'Tennessee', 'tennessee', 'Custom apparel manufacturer for Tennessee. Nashville and Memphis sports teams, schools, and businesses.', '<h2>Custom Apparel Manufacturer for Tennessee</h2><p>Custom Streetwear exports custom apparel to Tennessee, serving Nashville, Memphis, Knoxville, and Chattanooga with premium garments.</p>', 'Custom Apparel Manufacturer Tennessee | Custom Streetwear', 9),
(9, 'state', 'Texas', 'texas', 'Major custom apparel manufacturer serving Texas. Dallas, Houston, Austin, and San Antonio sports teams and brands.', '<h2>Custom Apparel Manufacturer for Texas</h2><p>Everything is bigger in Texas, including the demand for quality custom apparel. Custom Streetwear serves Dallas, Houston, Austin, San Antonio, and Fort Worth with premium custom garments.</p><h3>Areas Served</h3><ul><li>Dallas/Fort Worth</li><li>Houston</li><li>Austin</li><li>San Antonio</li><li>El Paso</li></ul>', 'Custom Apparel Manufacturer Texas | Custom Streetwear', 10),
(9, 'state', 'Wisconsin', 'wisconsin', 'Custom sportswear manufacturer for Wisconsin. Milwaukee and Madison teams trust our cold-weather gear.', '<h2>Custom Apparel Manufacturer for Wisconsin</h2><p>Custom Streetwear manufactures custom apparel for Wisconsin sports teams, businesses, and organizations in Milwaukee, Madison, and throughout the Badger State.</p>', 'Custom Apparel Manufacturer Wisconsin | Custom Streetwear', 11);

-- Table: blogs
CREATE TABLE `blogs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `tags` varchar(500) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `seo_title` varchar(200) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blogs Seed Data
INSERT INTO `blogs` (`title`, `slug`, `category`, `tags`, `short_description`, `content`, `seo_title`, `published_at`) VALUES
('Why Are Custom Varsity Jackets Suppliers in Pakistan Popular Among Sportswear Brands?', 'why-are-custom-varsity-jackets-suppliers-in-pakistan-popular-among-sportswear-brands', 'Industry Insights', 'varsity jackets, pakistan manufacturing, sportswear brands, custom apparel', 'Discover why Pakistani manufacturers are the go-to choice for custom varsity jackets worldwide.', '<h2>Why Pakistani Varsity Jacket Manufacturers Lead the Global Market</h2><p>Pakistan has emerged as a global hub for custom varsity jacket manufacturing, and for good reason. The combination of skilled craftsmanship, competitive pricing, and quality materials makes Pakistani suppliers the preferred choice for sportswear brands worldwide.</p><h3>Superior Craftsmanship</h3><p>Pakistani manufacturers have decades of experience in textile production. The city of Sialkot, in particular, is renowned for its sports goods and apparel manufacturing industry. This heritage translates into exceptional quality in every varsity jacket produced.</p><h3>Cost-Effective Production</h3><p>One of the biggest advantages of working with Pakistani manufacturers is the cost savings. Brands can get premium quality varsity jackets at a fraction of the cost compared to Western manufacturers, without compromising on quality.</p><h3>Customization Options</h3><p>Pakistani suppliers offer extensive customization options including custom patches, embroidery, chenille lettering, fabric choices, and sizing options. This flexibility allows brands to create unique products that stand out in the market.</p><h3>Conclusion</h3><p>For sportswear brands looking for reliable, high-quality varsity jacket manufacturing, Pakistan remains the top destination. The combination of quality, price, and customization options is unmatched.</p>', 'Why Pakistani Varsity Jacket Manufacturers Are Popular | Custom Streetwear', '2026-01-15 10:00:00'),
('Why Do International Brands Prefer Custom Tracksuits Suppliers in Pakistan?', 'why-do-international-brands-prefer-custom-tracksuits-suppliers-in-pakistan', 'Industry Insights', 'tracksuits, pakistan manufacturing, international brands, custom sportswear', 'Learn why global brands choose Pakistani manufacturers for their custom tracksuit needs.', '<h2>The Pakistani Advantage in Custom Tracksuit Manufacturing</h2><p>International brands consistently turn to Pakistani manufacturers for their custom tracksuit needs. This trend is driven by several key factors that make Pakistan an ideal manufacturing destination.</p><h3>Complete Supply Chain</h3><p>Pakistan has a complete textile supply chain, from raw cotton production to finished garments. This vertical integration allows for better quality control and faster turnaround times.</p><h3>Experience in Sportswear</h3><p>With decades of experience producing sports goods and apparel, Pakistani manufacturers understand the specific requirements of athletic wear—moisture-wicking, durability, and comfort.</p><h3>Infrastructure & Capacity</h3><p>Modern manufacturing facilities with the latest equipment allow Pakistani suppliers to handle large orders while maintaining consistent quality standards.</p>', 'Why Brands Choose Pakistani Tracksuit Manufacturers | Custom Streetwear', '2026-02-01 10:00:00'),
('How Are Hoodies Manufacturers in Sialkot Reinventing Streetwear Style?', 'how-are-hoodies-manufacturers-in-sialkot-reinventing-streetwear-style', 'Streetwear Trends', 'hoodies, sialkot manufacturing, streetwear, fashion trends', 'Explore how Sialkot manufacturers are driving innovation in the global streetwear hoodie market.', '<h2>Sialkot: The New Streetwear Capital</h2><p>Sialkot, Pakistan is rapidly becoming known as a global center for streetwear manufacturing, particularly hoodies. Local manufacturers are combining traditional craftsmanship with modern design trends to create products that rival those from established fashion capitals.</p><h3>Innovation in Fabric</h3><p>Sialkot manufacturers are experimenting with new fabric blends, washes, and treatments to create unique hoodie textures and looks. From acid wash to garment-dyed finishes, the options are endless.</p><h3>Trend-Forward Designs</h3><p>By staying connected to global fashion trends, Sialkot manufacturers offer designs that are on-trend including oversized fits, cropped styles, and unique detailing.</p><h3>Quality Standards</h3><p>Despite competitive pricing, Sialkot hoodie manufacturers maintain high quality standards using premium fabrics and construction techniques that ensure durability and comfort.</p>', 'How Sialkot Manufacturers Are Reinventing Streetwear | Custom Streetwear', '2026-02-20 10:00:00'),
('How Are T-Shirts Manufacturers in Sialkot Expanding The Apparel Industry?', 'how-are-t-shirts-manufacturers-in-sialkot-expanding-the-apparel-industry', 'Industry Insights', 't-shirts, sialkot, apparel industry, manufacturing', 'Discover how t-shirt manufacturers in Sialkot are transforming the global apparel industry.', '<h2>Sialkot T-Shirt Manufacturers: Driving Global Apparel Growth</h2><p>T-shirt manufacturing in Sialkot has evolved from basic production to sophisticated garment creation serving some of the world''s biggest brands. Here is how these manufacturers are expanding the apparel industry.</p><h3>Advanced Printing Technologies</h3><p>Sialkot manufacturers have invested heavily in modern printing technologies including DTG, screen printing, and sublimation, allowing for complex, multi-color designs that were previously impossible.</p><h3>Sustainable Practices</h3><p>Many manufacturers are adopting eco-friendly practices including organic cotton sourcing, water-based inks, and energy-efficient production methods to meet global sustainability standards.</p><h3>Diversification</h3><p>Beyond basic t-shirts, Sialkot manufacturers now produce a wide range of styles including polo shirts, flannel shirts, henleys, and performance tees, serving diverse market segments.</p>', 'T-Shirt Manufacturers in Sialkot Expanding the Apparel Industry | Custom Streetwear', '2026-03-10 10:00:00');

-- Table: enquiries
CREATE TABLE `enquiries` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `product_interest` varchar(200) DEFAULT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `source_page` varchar(255) DEFAULT NULL,
  `status` enum('New','Contacted','Quoted','Completed','Rejected') DEFAULT 'New',
  `admin_notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: contact_messages
CREATE TABLE `contact_messages` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('New','Read','Replied','Archived') DEFAULT 'New',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: seo_meta
CREATE TABLE `seo_meta` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` int(11) UNSIGNED NOT NULL,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `og_title` varchar(200) DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `schema_json` longtext DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `entity_type_id` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: menus
CREATE TABLE `menus` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `menu_location` enum('header','footer','sidebar','mobile') DEFAULT 'header',
  `sort_order` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menu Seed Data
INSERT INTO `menus` (`parent_id`, `title`, `url`, `menu_location`, `sort_order`) VALUES
(NULL, 'Home', '/', 'header', 1),
(NULL, 'Our Products', '#', 'header', 2),
(2, 'Hoodies', '/category/hoodies', 'header', 1),
(2, 'Tracksuits', '/category/tracksuits', 'header', 2),
(2, 'T-Shirts', '/category/t-shirts', 'header', 3),
(2, 'Varsity Jackets', '/category/varsity-jackets', 'header', 4),
(2, 'Softshell Jacket', '/category/softshell-jacket', 'header', 5),
(2, 'Sports Uniform', '/category/sports-uniform', 'header', 6),
(2, 'Promotional Products', '/category/promotional-products', 'header', 7),
(2, 'Workwear', '/category/workwear', 'header', 8),
(2, 'Hospital Uniform', '/category/hospital-uniform', 'header', 9),
(2, 'Bomber Jackets', '/category/bomber-jackets', 'header', 10),
(2, 'Winter Coat', '/category/winter-coat', 'header', 11),
(2, 'Leather Jackets', '/category/leather-jackets', 'header', 12),
(2, 'Motorcycle Jackets', '/category/motorcycle-jackets', 'header', 13),
(NULL, 'Customisations', '/customisations', 'header', 3),
(NULL, 'Fabrics', '/fabrics', 'header', 4),
(NULL, 'What We Do', '/what-we-do', 'header', 5),
(NULL, 'How We Do', '/how-we-do', 'header', 6),
(NULL, 'Color Charts', '/color-charts', 'header', 7),
(NULL, 'About Us', '/about-us', 'header', 8),
(NULL, 'Blogs', '/blogs', 'header', 9),
(NULL, 'Contact Us', '/contact', 'header', 10),
(NULL, 'Market Area', '/market-area', 'header', 11),
(NULL, 'Home', '/', 'footer', 1),
(NULL, 'About Us', '/about-us', 'footer', 2),
(NULL, 'Blogs', '/blogs', 'footer', 3),
(NULL, 'Contact Us', '/contact', 'footer', 4),
(NULL, 'Sitemap', '/sitemap', 'footer', 5),
(NULL, 'Market Area', '/market-area', 'footer', 6),
(NULL, 'Hoodies', '/category/hoodies', 'footer', 7),
(NULL, 'Tracksuits', '/category/tracksuits', 'footer', 8),
(NULL, 'T-Shirts', '/category/t-shirts', 'footer', 9),
(NULL, 'Varsity Jackets', '/category/varsity-jackets', 'footer', 10),
(NULL, 'Softshell Jacket', '/category/softshell-jacket', 'footer', 11),
(NULL, 'Sports Uniform', '/category/sports-uniform', 'footer', 12);

-- Table: color_charts
CREATE TABLE `color_charts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `pdf_file` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Color Charts Seed Data
INSERT INTO `color_charts` (`title`, `category`, `description`, `sort_order`) VALUES
('Pantone Color Chart', 'Standard', 'Standard Pantone color matching chart for accurate color selection.', 1),
('Polyester Fabric Colors', 'Polyester', 'Available color options for polyester and performance fabrics.', 2),
('Cotton Fabric Colors', 'Cotton', 'Available color options for cotton and cotton-blend fabrics.', 3),
('Nylon Fabric Colors', 'Nylon', 'Available color options for nylon and outdoor fabrics.', 4),
('Leather Colors', 'Leather', 'Available color options for genuine and faux leather materials.', 5);

-- Table: login_attempts
CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempts` int(11) DEFAULT 1,
  `last_attempt` datetime DEFAULT CURRENT_TIMESTAMP,
  `locked_until` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
