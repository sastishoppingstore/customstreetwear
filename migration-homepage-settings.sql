-- Homepage Content Settings Migration
-- Adds all homepage section content to site_settings for full admin editability
-- Run: mysql -u root customstreetwear < migration-homepage-settings.sql

INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_type`) VALUES

-- Logo Tagline
('site_logo_tagline', 'Custom Apparel Manufacturer', 'text'),

-- About Section
('home_about_label', 'About Us', 'text'),
('home_about_title', "America's Trusted Custom Apparel Manufacturer Since 2012", 'text'),
('home_about_text1', 'Custom Streetwear is a premier manufacturer of custom sportswear, streetwear, workwear, uniforms, and leather garments. Since 2012, we have been at the forefront of the apparel manufacturing industry, serving Fortune 500 brands, professional sports teams, major retailers, and government institutions across the United States.', 'textarea'),
('home_about_text2', 'Our state-of-the-art manufacturing facility spans 150,000 square feet with a production capacity of over 50,000 units per day. We combine cutting-edge technology with generations of craftsmanship to deliver products that meet the most demanding quality standards. From concept to delivery, every garment undergoes rigorous quality control across 12 inspection checkpoints.', 'textarea'),
('home_about_image', '/uploads/pages/about-factory.jpg', 'text'),
('home_about_link', '/about-us', 'url'),
('home_about_link_text', 'Learn More About Us', 'text'),

-- About Stats
('home_stat_units_number', '5000000', 'text'),
('home_stat_units_label', 'Units Produced Annually', 'text'),
('home_stat_clients_number', '2500', 'text'),
('home_stat_clients_label', 'USA Clients Served', 'text'),
('home_stat_states_number', '50', 'text'),
('home_stat_states_label', 'States Served', 'text'),

-- Why Choose Us Section
('home_whychoose_label', 'Why Custom Streetwear', 'text'),
('home_whychoose_title', 'Trusted by Industry Leaders Across America', 'text'),
('home_whychoose_desc', 'Preferred by Fortune 500 companies, professional sports teams, and thousands of businesses nationwide. Here is why they choose us.', 'textarea'),

-- Why Choose Stats
('home_whychoose_stat1_number', '50000000', 'text'),
('home_whychoose_stat1_label', 'Total Units Produced', 'text'),
('home_whychoose_stat2_number', '2500', 'text'),
('home_whychoose_stat2_label', 'USA Clients Served', 'text'),
('home_whychoose_stat3_number', '50', 'text'),
('home_whychoose_stat3_label', 'States Served', 'text'),
('home_whychoose_stat4_number', '13', 'text'),
('home_whychoose_stat4_label', 'Years of Excellence', 'text'),

-- Why Choose Cards
('home_whychoose_card1_icon', 'shield', 'text'),
('home_whychoose_card1_title', 'Trusted by Millions', 'text'),
('home_whychoose_card1_text', 'Preferred by millions across America, delivering exceptional quality, performance, and reliability that businesses, teams, and organizations can count on every single day.', 'textarea'),
('home_whychoose_card2_icon', 'smile', 'text'),
('home_whychoose_card2_title', 'Uncompromised Quality', 'text'),
('home_whychoose_card2_text', 'Experience top-tier craftsmanship with our premium products, designed for durability, comfort, and performance. Every garment passes 12-point quality inspection.', 'textarea'),
('home_whychoose_card3_icon', 'dollar', 'text'),
('home_whychoose_card3_title', 'Competitive Pricing', 'text'),
('home_whychoose_card3_text', 'Get the best value with factory-direct pricing on premium sportswear and custom uniforms. Quality, performance, and affordability combined in every order.', 'textarea'),
('home_whychoose_card4_icon', 'truck', 'text'),
('home_whychoose_card4_title', 'On-Time Delivery', 'text'),
('home_whychoose_card4_text', 'Reliable, efficient, and punctual delivery ensures your products arrive when you need them, every time, without compromise. 98% on-time delivery record.', 'textarea'),

-- Technology Section
('home_tech_label', 'Manufacturing Excellence', 'text'),
('home_tech_title', 'Performance-Driven Technology', 'text'),
('home_tech_desc', 'We use cutting-edge apparel technology to ensure comfort, durability, and peak performance. From moisture-wicking fabrics to precision stitching and digital printing, every piece is engineered with innovation.', 'textarea'),

-- Technology Cards
('home_tech_card1_icon', 'layers', 'text'),
('home_tech_card1_title', 'Sublimation', 'text'),
('home_tech_card1_text', 'Transform your designs into vibrant, durable prints that won\'t fade, crack, or peel over time.', 'textarea'),
('home_tech_card2_icon', 'package', 'text'),
('home_tech_card2_title', 'Cut & Sew', 'text'),
('home_tech_card2_text', 'Precision Cut & Sew techniques, crafting custom apparel with impeccable fit, style, and durability.', 'textarea'),
('home_tech_card3_icon', 'clock', 'text'),
('home_tech_card3_title', 'Screen Printing', 'text'),
('home_tech_card3_text', 'High-quality screen printing services delivering vibrant, long-lasting prints on every garment.', 'textarea'),
('home_tech_card4_icon', 'message-circle', 'text'),
('home_tech_card4_title', 'Embroidery', 'text'),
('home_tech_card4_text', 'Elevate your apparel with custom embroidery and applique, adding professional designs to every piece.', 'textarea'),

-- Section Labels (Categories, Products, Video, Brochure, Testimonial, Locations, Blog)
('home_categories_label', 'Our Collection', 'text'),
('home_categories_title', 'Product Categories', 'text'),
('home_categories_desc', 'Explore our wide range of custom apparel categories. From streetwear to sportswear, workwear to leather goods.', 'textarea'),

('home_bestseller_label', 'Most Popular', 'text'),
('home_bestseller_title', 'Best Seller Products', 'text'),
('home_bestseller_desc', 'Our most popular custom apparel products trusted by brands and teams worldwide.', 'textarea'),

('home_featured_label', 'Featured Items', 'text'),
('home_featured_title', 'Featured Products', 'text'),
('home_featured_desc', 'Handpicked custom apparel products showcasing our manufacturing excellence.', 'textarea'),

('home_video_label', 'Behind The Scenes', 'text'),
('home_video_title', 'Factory & Product Videos', 'text'),
('home_video_desc', 'Explore our manufacturing process and see the craftsmanship that goes into every garment.', 'textarea'),

('home_brochure_label', 'Download', 'text'),
('home_brochure_title', 'Our Brochures', 'text'),
('home_brochure_desc', 'Download our product catalogs to discover our complete range of custom apparel.', 'textarea'),

('home_testimonial_label', 'Testimonials', 'text'),
('home_testimonial_title', 'What Our Clients Say', 'text'),
('home_testimonial_desc', 'Trusted by brands, teams, and businesses worldwide. Here is what they have to say about us.', 'textarea'),

('home_locations_label', 'USA Coverage', 'text'),
('home_locations_title', 'Custom Apparel Manufacturer in USA', 'text'),
('home_locations_desc', 'We serve all 50 states with premium custom apparel manufacturing. Factory-direct pricing, nationwide delivery.', 'textarea'),

('home_blog_label', 'Latest News', 'text'),
('home_blog_title', 'From Our Blog', 'text'),
('home_blog_desc', 'Stay updated with the latest trends, insights, and news from the apparel industry.', 'textarea'),

-- CTA Section
('home_cta_label', 'Get Started', 'text'),
('home_cta_title', 'Ready to Create Your Custom Apparel?', 'text'),
('home_cta_desc', 'Whether you need custom sportswear, streetwear, workwear, or uniforms, we are here to bring your vision to life. Request a free quote today.', 'textarea'),
('home_cta_btn1_text', 'Request a Quote', 'text'),
('home_cta_btn1_link', '#quote', 'text'),
('home_cta_btn2_text', 'Contact Us', 'text'),
('home_cta_btn2_link', '/contact', 'url'),

-- Products Section Counts
('home_bestseller_count', '8', 'number'),
('home_featured_count', '8', 'number'),
('home_blog_count', '4', 'number'),
('home_video_count', '3', 'number'),
('home_brochure_count', '8', 'number'),
('home_testimonial_count', '6', 'number'),

-- Target SEO Pages
('home_meta_title', 'Custom Apparel Manufacturer in USA | Custom Streetwear', 'text'),
('home_meta_desc', 'Custom Streetwear is a USA-focused custom apparel manufacturer for sports uniforms, streetwear, workwear, promotional apparel, and private label clothing with factory-direct pricing.', 'textarea'),
('home_sports_uniforms_target', 'Custom Sports Uniforms Manufacturer in USA', 'text'),
('home_sports_uniforms_desc', 'Custom Sports Uniforms Manufacturer in USA for teams, schools, leagues, clubs, and brands. Factory-direct custom jerseys, kits, sublimation uniforms, embroidery, and bulk team apparel shipped nationwide.', 'textarea')
ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value), setting_type=VALUES(setting_type);

INSERT INTO `home_sections` (`section_key`, `section_name`, `section_type`, `title`, `subtitle`, `description`, `visibility`, `sort_order`) VALUES
('categories', 'Product Categories', 'categories', 'Custom Apparel Categories Built for USA Buyers', 'Our Collection', 'Explore custom sports uniforms, streetwear, workwear, promotional apparel, jackets, hoodies, and private label clothing manufactured for U.S. teams, brands, companies, and organizations.', 'visible', 2),
('whychoose', 'Why Choose Us', 'whychoose', 'Built to Win Buyer Trust in the First Look', 'USA-Focused Manufacturing', 'A clear quote path, visible proof points, factory-direct pricing, production timelines, and nationwide delivery help serious buyers keep moving without confusion.', 'visible', 5),
('locations', 'USA Locations', 'locations', 'Custom Apparel Manufacturer in USA', 'USA Coverage', 'We serve all 50 states with custom apparel manufacturing for local teams, businesses, schools, events, resellers, and private label brands.', 'visible', 11)
ON DUPLICATE KEY UPDATE title=VALUES(title), subtitle=VALUES(subtitle), description=VALUES(description);

-- Attach existing image assets to database records so no uploaded product/category art is idle.
UPDATE `categories` SET `image` = CONCAT('/uploads/categories/', `slug`, '.jpg') WHERE `slug` IN (
'hoodies','tracksuits','t-shirts','varsity-jackets','softshell-jacket','sports-uniform','promotional-products','workwear','hospital-uniform','bomber-jackets','winter-coat','leather-jackets','motorcycle-jackets'
) AND (`image` IS NULL OR `image` = '');

UPDATE `products` SET `main_image` = CONCAT('/uploads/products/', `slug`, '.jpg') WHERE `slug` IN (
'custom-acid-washed-hoodie','custom-tie-dye-hoodie','custom-sublimation-hoodie','custom-sweatshirt','custom-tracksuit-set','custom-sublimation-t-shirt','custom-polo-shirt','custom-varsity-jacket','custom-softshell-jacket','american-football-uniform','baseball-uniform','basketball-uniform','soccer-uniform-kit','rugby-uniform','hockey-uniform','promotional-hoodie','promotional-t-shirt','mechanic-uniform','safety-coverall','medical-scrub-set','custom-leather-jacket','custom-motorcycle-jacket'
) AND (`main_image` IS NULL OR `main_image` = '');
