# Custom Streetwear - Custom Apparel Manufacturing Website

**Domain:** customstreetwear.co

## Overview

A complete dynamic PHP + MySQL website for Custom Streetwear, a premium custom sportswear, streetwear, workwear, uniform, promotional apparel, and leatherwear manufacturing/export brand.

## Features

### Frontend
- Premium dark UI with neon green accents
- Responsive design (mobile, tablet, desktop)
- Hero slider with auto-play
- Product categories and subcategories
- Product detail pages with image gallery
- Customisation services page
- Fabrics/materials page
- Market area with country/state pages
- Blog with categories and tags
- Contact form with map
- Quote request modal on all pages
- WhatsApp floating button
- SEO optimized with schema markup
- Dynamic sitemap.xml
- Smooth scroll animations
- Animated counters

### Admin Panel
- Secure login with rate limiting
- Dashboard with statistics
- Full CRUD for products, categories, pages, blogs
- Manage sliders, testimonials, countries
- Manage enquiries and contact messages
- Site settings editor
- Image upload system
- CSRF protection

### Database (22 Tables)
- admins, site_settings, pages, sliders
- categories, subcategories, products, product_images
- customisations, fabrics, brochures, videos
- testimonials, countries, states_cities, blogs
- enquiries, contact_messages, seo_meta, menus
- color_charts, login_attempts

## Tech Stack

- Core PHP (no frameworks)
- MySQL with PDO prepared statements
- HTML5 + CSS3 (custom, no Bootstrap)
- Vanilla JavaScript (no jQuery)
- No external dependencies

## Installation

1. Upload files to hosting
2. Create MySQL database
3. Import database.sql
4. Edit config/config.php
5. Access /admin/ and login

**Default Login:** admin@example.com / Admin@12345

## Folder Structure

```
customstreetwear.co/
  admin/          - Admin panel
  api/            - API endpoints (quote, contact)
  assets/         - CSS, JS, images
  config/         - Configuration
  includes/       - PHP includes
  templates/      - Frontend templates
  uploads/        - Uploaded files
  index.php       - Main router
  .htaccess       - URL rewriting
  database.sql    - Database dump
```

## License

This is a custom-built project for customstreetwear.co
