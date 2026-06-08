-- Migration: New tables for FAQ, Delivery Charges, Orders, and Policy Pages
-- Run this on your production database after the homepage settings migration

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------------
-- Table: faqs (Q&A for frontend FAQ page + FAQ Schema)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL DEFAULT 'General',
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `sort_order` (`sort_order`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: delivery_charges (per-city delivery pricing for USA)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `delivery_charges`;
CREATE TABLE `delivery_charges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(255) NOT NULL,
  `state` varchar(100) NOT NULL,
  `charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estimated_days` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `city` (`city`),
  KEY `state` (`state`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Table: orders (checkout / quote request submissions)
-- -----------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `delivery_charge` decimal(10,2) DEFAULT 0.00,
  `estimated_days` varchar(50) DEFAULT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `payment_proof` varchar(500) DEFAULT NULL,
  `payment_status` enum('pending','received','verified','failed') DEFAULT 'pending',
  `order_status` enum('pending','processing','in_progress','shipped','delivered','cancelled') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `source` varchar(50) DEFAULT 'checkout',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `customer_email` (`customer_email`),
  KEY `order_status` (`order_status`),
  KEY `payment_status` (`payment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------
-- Policy Pages Seed Data
-- -----------------------------------------------------------
INSERT INTO `pages` (`title`, `slug`, `page_type`, `short_description`, `content`, `status`, `sort_order`) VALUES
('Privacy Policy', 'privacy-policy', 'static', 'Custom Streetwear Privacy Policy - Learn how we collect, use, and protect your personal information.', '<h2>Privacy Policy</h2><p>Last updated: June 2026</p><h3>1. Introduction</h3><p>Custom Streetwear ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our services.</p><h3>2. Information We Collect</h3><p><strong>Personal Information:</strong> We may collect personal information such as your name, email address, phone number, shipping address, and company name when you fill out forms, place orders, or contact us.</p><p><strong>Usage Data:</strong> We automatically collect certain information when you visit our website, including your IP address, browser type, operating system, referring URLs, and pages viewed.</p><h3>3. How We Use Your Information</h3><p>We use the collected information for: processing and fulfilling orders, communicating with you about your orders, providing customer support, improving our website and services, sending marketing communications (with your consent), and complying with legal obligations.</p><h3>4. Data Protection</h3><p>We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p><h3>5. Third-Party Disclosure</h3><p>We do not sell, trade, or transfer your personal information to third parties without your consent, except as required by law or as necessary to provide our services (e.g., shipping carriers, payment processors).</p><h3>6. Cookies</h3><p>Our website uses cookies to enhance your browsing experience. You can control cookie settings through your browser preferences.</p><h3>7. Your Rights</h3><p>You have the right to access, correct, or delete your personal information. Contact us to exercise these rights.</p><h3>8. Contact</h3><p>For questions about this Privacy Policy, contact us at info@customstreetwear.co or (555) 123-4567.</p><h3>9. Changes to This Policy</h3><p>We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated revision date.</p>', 1, 10),
('Return Policy', 'return-policy', 'static', 'Custom Streetwear Return Policy - Learn about our return and exchange process for custom apparel orders.', '<h2>Return & Exchange Policy</h2><p>Last updated: June 2026</p><h3>1. Overview</h3><p>At Custom Streetwear, we are committed to delivering high-quality custom apparel. Because each item is made to order, our return policy is designed to ensure fairness for both our customers and our manufacturing process.</p><h3>2. Manufacturing Defects</h3><p>If your order has manufacturing defects (incorrect sizing, printing errors, material defects, stitching issues), please contact us within 7 days of receiving your order. We will provide a free replacement or full refund for defective items.</p><h3>3. Custom Orders</h3><p>Due to the custom nature of our products, we generally cannot accept returns for: items made to your specific design, size, or specifications; items that have been worn, washed, or altered; orders where the customer provided incorrect specifications.</p><h3>4. Wrong Item Received</h3><p>If you received the wrong item or an incorrect design, please contact us within 48 hours of delivery. We will arrange for return shipping and send the correct item at no additional cost.</p><h3>5. Return Process</h3><p>To initiate a return: contact our customer service team with your order number and photos of the issue; we will review your request within 48 hours; if approved, we will provide a return authorization and shipping instructions; returns must be sent within 14 days of receiving authorization.</p><h3>6. Refunds</h3><p>Approved refunds will be processed within 7-10 business days to your original payment method. Refunds include the product cost but may exclude shipping charges unless the error was on our part.</p><h3>7. Exchanges</h3><p>Size exchanges for non-custom items may be possible within 14 days of delivery, subject to inventory availability. The customer is responsible for return shipping on exchanges.</p><h3>8. Contact</h3><p>For return requests and questions, email info@customstreetwear.co or call (555) 123-4567.</p>', 1, 11),
('Terms of Service', 'terms', 'static', 'Custom Streetwear Terms of Service - Terms and conditions governing the use of our website and services.', '<h2>Terms of Service</h2><p>Last updated: June 2026</p><h3>1. Acceptance of Terms</h3><p>By accessing or using the Custom Streetwear website and services, you agree to be bound by these Terms of Service. If you do not agree, please do not use our services.</p><h3>2. Services Description</h3><p>Custom Streetwear provides custom apparel manufacturing services including design, production, and shipping of custom sportswear, streetwear, workwear, uniforms, and related products.</p><h3>3. Order Process</h3><p>Submitting a quote request or order does not constitute acceptance. We reserve the right to accept or decline any order. Orders are confirmed only after we provide a formal quotation and receive payment or payment confirmation.</p><h3>4. Pricing & Payment</h3><p>All prices are quoted in USD unless otherwise specified. Prices are subject to change without notice. Payment terms are specified in individual quotations. Late payments may incur additional charges.</p><h3>5. Intellectual Property</h3><p>You retain all rights to designs, logos, and artwork you provide. You warrant that you have the legal right to use any designs, trademarks, or copyrighted materials you submit for manufacturing.</p><h3>6. Limitation of Liability</h3><p>Custom Streetwear shall not be liable for indirect, incidental, or consequential damages arising from the use of our products or services. Our total liability is limited to the amount paid for the specific product or service giving rise to the claim.</p><h3>7. Shipping & Delivery</h3><p>Delivery timelines are estimates and not guaranteed. We are not responsible for delays caused by customs, carriers, or force majeure events. Risk of loss passes to you upon delivery to the carrier.</p><h3>8. Governing Law</h3><p>These terms are governed by the laws of Pakistan. Any disputes shall be resolved through binding arbitration in Sialkot, Pakistan.</p><h3>9. Changes to Terms</h3><p>We reserve the right to modify these terms at any time. Continued use of our services after changes constitutes acceptance of the new terms.</p><h3>10. Contact</h3><p>For questions about these terms, contact us at info@customstreetwear.co.</p>', 1, 12);

-- -----------------------------------------------------------
-- FAQ Seed Data
-- -----------------------------------------------------------
INSERT INTO `faqs` (`category`, `question`, `answer`, `sort_order`) VALUES
('Orders', 'How do I place a custom order?', 'Placing a custom order is simple. Browse our products, select your desired items, and click "Request a Quote" or use our quote form. Tell us your requirements, quantity, and customization needs. Our team will get back to you within 24 hours with a detailed quote.', 1),
('Orders', 'What is the minimum order quantity (MOQ)?', 'Our MOQ varies by product type. For most custom apparel, the minimum is 50-100 pieces per design. For promotional products, the MOQ can be as low as 25 pieces. Contact us for specific product MOQs.', 2),
('Orders', 'Can I order samples before placing a bulk order?', 'Yes, we offer sample production before bulk manufacturing. Sample charges apply, which are typically deducted from your final order. Samples take 5-7 business days to produce and 3-5 days for shipping.', 3),
('Orders', 'How long does it take to fulfill an order?', 'Standard production takes 3-4 weeks after sample approval. Rush orders can be completed in 10-15 days. Delivery time depends on your location. We ship worldwide via DHL, FedEx, and cargo services.', 4),
('Pricing', 'How is pricing calculated?', 'Pricing depends on product type, quantity, materials, customization complexity, and shipping destination. We offer tiered pricing - larger quantities get better rates. Request a quote for accurate pricing.', 5),
('Pricing', 'Do you offer bulk discounts?', 'Yes, we offer significant discounts for bulk orders. Pricing tiers are based on quantity brackets. Contact us for volume pricing and wholesale rates.', 6),
('Pricing', 'What payment methods do you accept?', 'We accept bank transfers (wire transfer), PayPal, and major credit cards. For large orders, we offer flexible payment terms including 50% advance and 50% against documents.', 7),
('Customization', 'What customization options are available?', 'We offer: sublimation printing, screen printing, embroidery, applique, heat transfer, DTG printing, custom patches, custom labels, custom packaging, and private labeling. Almost any design can be realized.', 8),
('Customization', 'Can I use my own design or logo?', 'Absolutely! You can provide your own designs, logos, and artwork. We accept AI, EPS, PDF, PNG, and PSD formats. Our design team can also help create or refine your designs.', 9),
('Customization', 'Do you offer private label services?', 'Yes, we offer comprehensive private label services. We can manufacture products with your brand name, logo, tags, and custom packaging - ready for retail sale.', 10),
('Shipping', 'Do you ship to the USA?', 'Yes, we ship to all 50 USA states including California, Texas, New York, Florida, and more. We have regular shipments to the US via DHL, FedEx, and sea cargo.', 11),
('Shipping', 'How much is shipping?', 'Shipping costs vary by destination, order size, and delivery speed. We offer competitive shipping rates. Use our delivery charge calculator on the checkout page to estimate costs.', 12),
('Shipping', 'How long does shipping take?', 'To USA: Express shipping 3-5 business days, Standard 5-7 business days. To other destinations: 5-10 business days depending on location and shipping method.', 13),
('Returns', 'What is your return policy?', 'We accept returns within 30 days of delivery for manufacturing defects. Custom-made items cannot be returned unless there is a production error. Contact us immediately if you receive defective items.', 14),
('Returns', 'How do I request a return?', 'Contact our support team with your order number and photos of the issue. We will review and provide a return authorization within 48 hours. Approved returns are processed within 5-7 business days.', 15),
('Products', 'What fabrics do you use?', 'We use premium fabrics including cotton fleece, polyester spandex, mesh, interlock, taslan nylon, softshell, wool melton, genuine leather, dry-fit performance fabrics, and more. All materials are sourced from trusted suppliers.', 16),
('Products', 'Can you match specific colors?', 'Yes, we offer Pantone color matching for precise color replication. Our standard color range includes 50+ colors across all fabric types. Custom dyeing is available for large orders.', 17),
('Products', 'Do you offer size customization?', 'Yes, we can customize sizes based on your requirements. We offer standard sizing (XS-5XL) and can create custom size charts for your specific needs. Graded specs available for professional fit.', 18),
('Quality', 'What quality control measures do you have?', 'Every garment undergoes 12-point quality inspection including: fabric check, stitching quality, print/embroidery accuracy, color fastness, size verification, packaging inspection, and more.', 19),
('Quality', 'Do you offer quality guarantees?', 'Yes, we stand behind our products with a 100% quality guarantee. If there are manufacturing defects, we will replace or refund the affected items. Our QC team ensures consistent quality across all orders.', 20),
('Company', 'Where is your facility located?', 'Our manufacturing facility is headquartered in Sialkot, Pakistan - the global hub for sports goods and apparel manufacturing. We have a 150,000 sq ft state-of-the-art production facility.', 21),
('Company', 'Can I visit your factory?', 'Yes, we welcome factory visits. Contact us to schedule a tour of our manufacturing facility. We are happy to show you our production process and quality control measures.', 22),
('Company', 'Are you an approved supplier for government contracts?', 'Yes, we are registered and approved for government and institutional contracts. We meet all compliance requirements including labor standards, environmental regulations, and quality certifications.', 23);

-- -----------------------------------------------------------
-- Delivery Charges Seed Data (USA cities)
-- -----------------------------------------------------------
INSERT INTO `delivery_charges` (`city`, `state`, `charge`, `estimated_days`, `status`) VALUES
('New York', 'New York', 25.00, '3-5 business days', 1),
('Los Angeles', 'California', 25.00, '3-5 business days', 1),
('Chicago', 'Illinois', 25.00, '3-5 business days', 1),
('Houston', 'Texas', 25.00, '3-5 business days', 1),
('Phoenix', 'Arizona', 30.00, '4-6 business days', 1),
('Philadelphia', 'Pennsylvania', 25.00, '3-5 business days', 1),
('San Antonio', 'Texas', 25.00, '3-5 business days', 1),
('San Diego', 'California', 25.00, '3-5 business days', 1),
('Dallas', 'Texas', 25.00, '3-5 business days', 1),
('San Jose', 'California', 25.00, '3-5 business days', 1),
('Austin', 'Texas', 25.00, '3-5 business days', 1),
('Jacksonville', 'Florida', 30.00, '4-6 business days', 1),
('Fort Worth', 'Texas', 25.00, '3-5 business days', 1),
('Columbus', 'Ohio', 25.00, '3-5 business days', 1),
('Charlotte', 'North Carolina', 25.00, '3-5 business days', 1),
('Indianapolis', 'Indiana', 25.00, '3-5 business days', 1),
('San Francisco', 'California', 25.00, '3-5 business days', 1),
('Seattle', 'Washington', 30.00, '4-6 business days', 1),
('Denver', 'Colorado', 30.00, '4-6 business days', 1),
('Nashville', 'Tennessee', 25.00, '3-5 business days', 1);

SET FOREIGN_KEY_CHECKS = 1;
