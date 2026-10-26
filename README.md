# Eco Heat Shop - Custom WooCommerce Child Theme

## Project Overview
Eco Heat Shop was an e-commerce platform engineered for an online heating, plumbing, and trade solutions business operating in the UK (2023–2026).

This repository contains the custom Porto Child Theme (`ecoheatshop`), specialized PHP logic, custom WooCommerce templates, and backend automation modules built for the platform.

> **Archive Notice:** The live platform lifecycle has concluded. This repository serves as a permanent public showcase and codebase archive for portfolio and technical evaluation purposes.

## Technical Stack
- **CMS & E-Commerce:** WordPress, WooCommerce
- **Theme Architecture:** Porto Parent Theme (Excluded for licensing) + Custom Porto Child Theme (`ecoheatshop`)
- **Backend:** PHP (Custom Action/Filter Hooks, WP REST API Endpoints)
- **Frontend:** JavaScript (ES6), HTML5, CSS3/Sass, jQuery AJAX
- **Database:** MySQL (WordPress `$wpdb` Abstraction Layer)
- **Security & Standards:** WP Nonces, Input Sanitization, Parameterized Queries, Data Protection

## Engineering Highlights & Key Features
- **Custom Child Theme Architecture:** Developed a modular Porto Child Theme to override core templates safely without altering parent theme files.
- **Inventory & Order Logic:** Built backend hooks and endpoints to align physical trade counter stock with WooCommerce inventory.
- **Custom Order Tracking:** Engineered a dynamic verification module for real-time order tracking via Order ID and Billing Email.
- **Support & Warranty Modules:** Integrated custom dynamic templates for off-hours support logging and automated product warranty registration.
- **Performance & Security Tuning:** Applied WP nonces, input sanitization (`sanitize_text_field`), and parameterized SQL queries (`$wpdb->prepare()`) to protect against common web vulnerabilities.

## Repository Structure
```text
ecoheatshop
├── inc
├── js
├── src
├── vc_templates
├── functions.php
├── screenshot.png
├── style.css
└── README.md
