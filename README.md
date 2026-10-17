<div align="center">

# ECO Heat Shop | SMB Digital Platform

[![Project Status](https://img.shields.io/badge/Project_Status-Active_%26_Ongoing_(Since_2023)-brightgreen?style=for-the-badge&logo=github)](https://github.com/taimoursohail/ecoheatshop)
[![CMS](https://img.shields.io/badge/CMS-WordPress_%7C_WooCommerce-blue?style=for-the-badge&logo=wordpress)](https://woocommerce.com/)
[![Architecture](https://img.shields.io/badge/Theme-Custom_Porto_Child_Theme-orange?style=for-the-badge)](https://www.ecoheatshop.co.uk/)
[![Parent Theme](https://img.shields.io/badge/Parent_Theme-Excluded_(Privacy_%26_Licensing)-lightgrey?style=for-the-badge)]()
[![Academic Mapping](https://img.shields.io/badge/Academic_Mapping-BTEC_%2F_B.Tech_in_IT-purple?style=for-the-badge)](https://taimoursohail.com/)
[![Licence](https://img.shields.io/badge/Licence-Proprietary_%2F_All_Rights_Reserved-red?style=for-the-badge)]()

<br />

[Live Platform](https://www.ecoheatshop.co.uk/) | [Store Location Map](https://maps.app.goo.gl/e6srdsRnmu7UYSMX6) | [Developer Portfolio](https://taimoursohail.com/) | [Track Order](https://www.ecoheatshop.co.uk/track-order-eco-heat-shop/)

</div>

---

> [!CAUTION]
> ### PROPRIETARY & CONFIDENTIAL NOTICE
> This repository contains exclusive digital assets, custom PHP functions, and specialized child theme code engineered for ECO Heat Shop Ltd by Taimour Bin Sohail. It is strictly proprietary software and is not open source. Unauthorized copying, redistribution, commercial deployment, or usage by third-party businesses is strictly prohibited.

---

## Executive Summary

Welcome to the ECO Heat Shop primary repository. I am Taimour Bin Sohail, the full-stack developer and system creator behind this SME e-commerce platform and retail automation engine built for ECO Heat Shop Ltd, operating from Unit 8, West Cross Shopping Centre, Oldbury Road, Smethwick, West Midlands, UK.

I launched this project in 2023 as an ongoing enterprise system. Built on WordPress and WooCommerce, the platform uses Porto as its parent theme alongside a custom Porto Child Theme (`porto-child-ecoheatshop`) that I engineered from scratch. This custom codebase implements custom PHP action and filter hooks, WooCommerce template overrides, automated REST API endpoints, and real-time inventory synchronization logic tailored for our SME operations.

As I progress through my academic studies in Information Technology (BTEC / B.Tech in IT), I continuously evolve and scale this repository by applying advanced software engineering practices, security protocols, and backend automation modules directly to this live codebase.

---

## Repository Privacy & Parent Theme Exclusion

To maintain commercial confidentiality, protect intellectual property, and comply with commercial theme licensing terms, the Porto parent theme files have been strictly excluded from this repository. 

This repository tracks only my custom-engineered child theme source code, specialized page templates, custom hooks, JavaScript assets, and API integration routines.

| System Component | Technology / Layer | Repository Status | Access Policy |
| :--- | :--- | :---: | :--- |
| **Parent Theme** | Porto Commercial WordPress Theme | Excluded | Privacy & Licensing Compliance |
| **Child Theme** | Custom Porto Child Theme (`porto-child-ecoheatshop`) | Included | Version Controlled Codebase |
| **Custom Functions** | PHP Action & Filter Hooks (`functions.php`) | Included | Proprietary Logic |
| **Custom Templates** | WooCommerce & Theme Overrides (`page-templates/`) | Included | Proprietary Layouts |
| **API & Automation** | Custom WP REST Endpoints & AJAX Handlers | Included | Bespoke Integration Logic |

---

## SME Business Architecture & Location

My system establishes an omnichannel bridge connecting online web traffic with our physical trade counter operations at Smethwick:

* **Physical Headquarters**: [ECO Heat Shop Ltd Location Map](https://maps.app.goo.gl/e6srdsRnmu7UYSMX6) (Unit 8, West Cross Shopping Centre, Oldbury Road, Smethwick, B66 1JG).
* **Online Storefront**: `ecoheatshop.co.uk` handling retail and trade digital transactions.
* **Inventory Alignment**: Custom backend logic linking physical trade counter sales with real-time WooCommerce stock levels to prevent overselling.

---

## Key System Capabilities

* **Custom Porto Child Theme**: I engineered modular code extending the Porto parent theme via clean hooks and template overrides without modifying core theme files.
* **Multi-Category Catalog Engine**: The platform manages complex product hierarchies across Heating, Plumbing, Renewable Storage (LiFePO4 Batteries), External Drainage, Roofing, and Trade Tools.
* **Dual-Parameter Order Tracking**: I built a verification engine querying order status dynamically using Order ID and Billing Email.
* **Automated Customer Support**: An embedded conversational agent provides off-hours assistance, store information, and formal complaint logging.
* **Digital Warranty Management**: A dedicated portal handles product warranty registrations and automated routing to technical support teams.

---

## Academic Curriculum Mapping (BTEC & B.Tech IT)

This project serves as a live practical synthesis of my academic progression in Information Technology from 2023 to the present:

| Academic Level | IT Subject Area | Concepts Learned | Implementation in ECO Heat Shop |
| :--- | :--- | :--- | :--- |
| **Level 1** | Web Engineering | Semantic HTML5, CSS3, DOM Manipulation | Porto Child Theme CSS overrides, responsive layout templates, dynamic form validation routines. |
| **Level 2** | Data Structures & DSA | N-ary Taxonomy Trees, Indexing | WooCommerce category hierarchies and custom product query filters. |
| **Level 2** | OOP & Database Systems | OOP PHP, 3NF MySQL, REST APIs | Custom PHP classes with hooks (`add_action`/`add_filter`), `$wpdb` queries, WP REST API endpoints. |
| **Level 3** | Software Engineering | Template Hierarchy, Modular Logic | Decoupled Porto child theme view templates from core backend logic for long-term maintainability. |
| **Level 3** | Web Security & Standards | Nonces, SQLi/XSS Prevention, GDPR | Input sanitisation (`sanitize_text_field`), parameterised SQL (`$wpdb->prepare()`), GDPR compliance portals. |
| **Level 4** | Intelligent Systems | Conversational Agents | Automated support chatbot integrated into the Porto child theme interface. |
| **Level 4** | DevOps & Version Control | Private Repo Management, Boundary Control | Dedicated GitHub repository (`taimoursohail/ecoheatshop`) excluding commercial parent theme assets. |

---

## Technology Stack

* **Core Platform**: WordPress & WooCommerce Engine
* **Theme Layer**: Porto Parent Theme (Excluded) + Custom Porto Child Theme (Included)
* **Backend**: PHP (Object-Oriented, Custom Action/Filter Hooks, WP REST Endpoints)
* **Frontend**: HTML5, CSS3, JavaScript (ES6+), jQuery AJAX
* **Database**: MySQL / MariaDB via WordPress Data Abstraction (`$wpdb`)
* **Security**: WP Nonces, Data Sanitisation, Parameterised SQL Queries, SSL/TLS Encryption

---

## Internal Deployment & Setup Guide

> [!NOTE]
> This setup procedure is strictly for authorized internal developers.

1. **Clone the Repository**:
   ```bash
   git clone [https://github.com/taimoursohail/ecoheatshop.git](https://github.com/taimoursohail/ecoheatshop.git)
   /wp-content/themes/porto-child-ecoheatshop/
2) **Verify Parent Theme Dependency**:
Ensure the Porto parent theme is installed and licensed on the server, then activate the child theme via the WordPress Admin Dashboard (Appearance > Themes).
3) **Verify Core Plugin Dependencies**:
Ensure WooCommerce is active to enable custom product taxonomies, checkout hooks, and REST API endpoints.

## Copyright & Ownership Notice
Copyright © 2023 - Present ECO Heat Shop Ltd & Taimour Bin Sohail. All Rights Reserved.
  This repository and its contents are proprietary intellectual property belonging to ECO Heat Shop Ltd and engineered by Taimour Bin Sohail. Unauthorized duplication, distribution, reverse engineering, or commercial use by third parties is strictly prohibited under UK and international copyright laws.
