-- W&S Digital Marketing -- admin panel schema + seed data.
--
-- HOW TO IMPORT ON HOSTINGER:
-- 1. hPanel -> Databases -> MySQL Databases -> create a database + user if you
--    haven't already (note the DB name, username, password it gives you).
-- 2. hPanel -> Databases -> phpMyAdmin -> open your database -> "Import" tab.
-- 3. Choose this file, click "Go". It creates all 3 tables and seeds them with
--    the site's current blog posts and team members so nothing is lost.
-- 4. On the server, copy config.sample.php to config.php (same folder) and put
--    the DB name/user/password from step 1 into it (DB_NAME, DB_USER, DB_PASS).
--    config.php is never tracked by git/future updates, so do this directly on
--    Hostinger and it will not be overwritten the next time you upload new
--    files -- only ever re-upload config.sample.php, never config.php.
--
-- DEFAULT ADMIN LOGIN (created by this file) -- CHANGE THIS PASSWORD THE FIRST
-- TIME YOU LOG IN (Admin Panel -> Account -> Change Password), it's a shared
-- placeholder from setup, not something that should stay in use long-term:
--   Username: admin
--   Password: 62e80ad50bbb

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO admin_users (username, password_hash) VALUES
('admin', '$2y$12$nQcG8pv56VqGIPXuDl7MzuQ1VUmm5GlenX3dyWQxMc6uqxu2HSHDa');

CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    role VARCHAR(150) NOT NULL,
    description TEXT,
    photo_path VARCHAR(255) DEFAULT '',
    linkedin_url VARCHAR(255) DEFAULT '#',
    display_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO team_members (name, role, description, photo_path, linkedin_url, display_order) VALUES
('Sahar Afridi', 'Managing Director / Social Media Manager', 'Oversees company direction and leads social media strategy across every client account.', 'images/team/sahar-afridi.jpg', '#', 1),
('Wishal Khan Mohammadi', 'Chief Executive Officer', 'Sets company vision and ensures every client gets a growth plan built around real numbers.', 'images/team/wishal-khan-mohammadi.jpg', '#', 2),
('Shawal Khan Mohammadi', 'Chief Executive Officer', 'Drives company strategy and long-term growth across every service we deliver.', 'images/team/shawal-khan-mohammadi.jpg', '#', 3),
('Hammad Hassan', 'Digital Marketing Manager', 'Manages day-to-day marketing execution across SEO, ads, and content.', 'images/team/hammad-hassan.jpg', '#', 4),
('Usman Zahid', 'Lead Web Developer', 'Builds fast, conversion-focused websites engineered to turn visitors into customers.', 'images/team/usman-zahid.jpg', '#', 5),
('Azhar Rasheed', 'SEO Expert', 'Drives organic growth through technical SEO, content strategy, and local search dominance.', 'images/team/azhar-rasheed.jpg', '#', 6),
('Tabarak Hussain', 'Shopify Expert', 'Builds and optimises Shopify stores engineered for conversions and repeat customers.', 'images/team/tabarak-hussain.jpg', '#', 7),
('Tariq Aziz', 'Social Media Expert', 'Plans and manages social content and campaigns that build real engagement.', 'images/team/tariq-aziz.jpg', '#', 8),
('Salim Yousaf', 'SEO Expert', 'Focuses on keyword research and on-page optimisation that lifts search rankings.', 'images/team/salim-yousaf.jpg', '#', 9);

CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    category VARCHAR(50) DEFAULT '',
    excerpt TEXT,
    content LONGTEXT,
    image_path VARCHAR(255) DEFAULT '',
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    meta_title VARCHAR(200) DEFAULT '',
    meta_description VARCHAR(500) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO blog_posts (title, slug, category, excerpt, content, image_path, is_published) VALUES
(
    '5 Local SEO Wins Every Small Business Should Make',
    'local-seo-wins-for-small-business',
    'SEO',
    'From Google Business Profile optimization to local link building, here are the highest-leverage local SEO moves for Australian businesses.',
    '<p>Local SEO is the single highest-leverage channel for most small businesses, yet it is consistently the most neglected. Here are five changes that move the needle fastest.</p><h3>1. Fully optimise your Google Business Profile</h3><p>Complete every field, add real photos, choose accurate categories, and post updates regularly. This is the single biggest factor in local map pack rankings.</p><h3>2. Build local citations consistently</h3><p>Your business name, address, and phone number should match exactly across every directory, from your own website to third-party listings.</p><h3>3. Earn genuine local backlinks</h3><p>Local chambers of commerce, supplier partnerships, and community sponsorships all create natural, relevant links back to your site.</p><h3>4. Actively manage your reviews</h3><p>Respond to every review, good or bad. Review volume and recency are ranking factors, and responsiveness builds trust with prospective customers.</p><h3>5. Create location-specific landing pages</h3><p>If you serve multiple suburbs or regions, dedicated pages for each area outperform a single generic services page in local search.</p>',
    'https://images.unsplash.com/photo-1571171637578-41bc2dd41cd2?auto=format&fit=crop&w=800&q=80',
    1
),
(
    'How To Stop Wasting Ad Spend On Google Ads',
    'stop-wasting-ad-spend-google-ads',
    'PPC',
    'Negative keywords, dayparting, and audience layering — the tactics we use to cut cost-per-lead without sacrificing volume.',
    '<p>Most Google Ads accounts we audit are leaking 20-40% of their budget on searches that were never going to convert. Here is where that waste typically comes from and how to fix it.</p><h3>Negative keywords are non-negotiable</h3><p>Review the search terms report weekly, not monthly. Every irrelevant query that triggers your ad is money you will never get back.</p><h3>Dayparting around real buying behaviour</h3><p>Most service businesses see conversion rates fall off a cliff outside business hours. Scheduling ads around when your team can actually respond protects cost-per-lead.</p><h3>Audience layering, not just keyword targeting</h3><p>Overlaying in-market and affinity audiences on top of keyword targeting lets you bid more aggressively on the traffic most likely to convert, and pull back on the rest.</p><h3>Match types matter more than ever</h3><p>Broad match without strong automated bidding and a mature negative keyword list is one of the fastest ways to burn budget. Start tighter, expand deliberately.</p>',
    'https://images.unsplash.com/photo-1533750349088-cd871a92f312?auto=format&fit=crop&w=800&q=80',
    1
),
(
    'Why Your Website Isn''t Converting Traffic Into Leads',
    'website-not-converting-traffic-into-leads',
    'CRO',
    'Traffic isn''t the problem for most businesses — conversion is. Here''s how we audit and fix leaky landing pages.',
    '<p>Businesses often pour budget into driving more traffic when the real problem is what happens once visitors land on the page. A few of the most common leaks we find during conversion audits:</p><h3>Unclear value proposition</h3><p>If a visitor cannot understand what you do and why it matters to them within five seconds, they will leave. Headlines need to lead with the outcome, not the feature.</p><h3>Too many competing calls to action</h3><p>Every extra choice on a page reduces the odds any single one gets taken. A strong landing page has one primary action, reinforced consistently.</p><h3>Forms that ask for too much, too soon</h3><p>Every additional form field measurably reduces completion rate. Ask only for what you need to make first contact, and gather the rest later.</p><h3>No real trust signals</h3><p>Reviews, case studies, and recognisable logos do real work here. Visitors are looking for proof before they hand over their details.</p><h3>Slow load times</h3><p>Every extra second of load time on mobile compounds directly into lost conversions, especially for paid traffic where every visitor already cost you money to acquire.</p>',
    'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80',
    1
);

CREATE TABLE IF NOT EXISTS case_studies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    tag VARCHAR(100) DEFAULT '',
    image_path VARCHAR(255) DEFAULT '',
    image_alt VARCHAR(255) DEFAULT '',
    summary TEXT,
    services TEXT,
    has_data TINYINT(1) NOT NULL DEFAULT 0,
    period VARCHAR(100) DEFAULT '',
    stat1_value VARCHAR(50) DEFAULT '', stat1_label VARCHAR(100) DEFAULT '',
    stat2_value VARCHAR(50) DEFAULT '', stat2_label VARCHAR(100) DEFAULT '',
    stat3_value VARCHAR(50) DEFAULT '', stat3_label VARCHAR(100) DEFAULT '',
    stat4_value VARCHAR(50) DEFAULT '', stat4_label VARCHAR(100) DEFAULT '',
    display_order INT NOT NULL DEFAULT 0,
    meta_title VARCHAR(200) DEFAULT '',
    meta_description VARCHAR(500) DEFAULT '',
    gsc_clicks VARCHAR(50) DEFAULT '', gsc_impressions VARCHAR(50) DEFAULT '',
    gsc_ctr VARCHAR(20) DEFAULT '', gsc_position VARCHAR(20) DEFAULT '', gsc_image VARCHAR(255) DEFAULT '',
    gsc_ai_value VARCHAR(50) DEFAULT '', gsc_ai_image VARCHAR(255) DEFAULT '',
    gbp_interactions VARCHAR(50) DEFAULT '', gbp_calls VARCHAR(50) DEFAULT '', gbp_calls_image VARCHAR(255) DEFAULT '',
    gbp_directions VARCHAR(50) DEFAULT '', gbp_directions_image VARCHAR(255) DEFAULT '',
    gbp_website_clicks VARCHAR(50) DEFAULT '', gbp_website_image VARCHAR(255) DEFAULT '', gbp_image VARCHAR(255) DEFAULT '',
    gbp_extra_label VARCHAR(100) DEFAULT '', gbp_extra_value VARCHAR(50) DEFAULT '', gbp_extra_image VARCHAR(255) DEFAULT '',
    related_services VARCHAR(255) NOT NULL DEFAULT '',
    shop_period VARCHAR(100) DEFAULT '',
    shop_sales VARCHAR(50) DEFAULT '', shop_sales_growth VARCHAR(20) DEFAULT '',
    shop_sessions VARCHAR(50) DEFAULT '', shop_sessions_growth VARCHAR(20) DEFAULT '',
    shop_orders VARCHAR(50) DEFAULT '', shop_orders_growth VARCHAR(20) DEFAULT '',
    shop_conversion VARCHAR(50) DEFAULT '', shop_conversion_growth VARCHAR(20) DEFAULT '',
    shop_image VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO case_studies (name, slug, tag, image_path, image_alt, summary, services, has_data, period, stat1_value, stat1_label, stat2_value, stat2_label, stat3_value, stat3_label, stat4_value, stat4_label, display_order) VALUES
(
    'Clever Beavers',
    'clever-beavers',
    'Full Suite',
    'https://wsdigitalmarketing.com.au/wp-content/uploads/2026/07/screencapture-cleverbeavers-2026-07-27-10_25_46-scaled-e1785130737259.webp',
    'Clever Beavers Workbooks and Branding',
    'Clever Beavers is an educational brand creating engaging learning resources for preschool and early childhood education. We designed and built a modern Shopify e-commerce store that showcases its educational workbooks, printable learning resources and digital products, giving parents, teachers and caregivers a fast, fully responsive and playful-yet-professional shopping experience -- from custom homepage sections and product pages to an optimised, secure checkout flow.',
    'Custom Shopify Store Development
Mobile-First Responsive Design
Product & Collection Page Optimization
Technical SEO Implementation
Payment Gateway Integration
Speed & Performance Optimization',
    0,
    '',
    '', '', '', '', '', '', '', '',
    1
),
(
    'Cafe Calibre',
    'cafe-calibre',
    'Hospitality',
    'https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80',
    'Cafe Calibre Dining and Dome Reservations',
    'A full-suite engagement covering brand identity, an online reservation experience and ongoing marketing for Cafe Calibre.',
    'AI Design
Content Writing
Ecommerce Development
Graphic Design
PPC Advertising
Search Engine Optimization
Social Media Marketing
Website Design',
    0,
    '',
    '', '', '', '', '', '', '', '',
    2
),
(
    'On Crew',
    'on-crew',
    'Commercial',
    'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=1200&q=80',
    'On Crew Professional Team',
    'A commercial engagement covering website development and ongoing digital marketing for On Crew.',
    'Content Writing
Ecommerce Development
Graphic Design
PPC Advertising
Search Engine Optimization
Social Media Marketing
Website Design',
    0,
    '',
    '', '', '', '', '', '', '', '',
    3
),
(
    'Master Fridge Repairs',
    'master-fridge-repairs',
    'Refrigeration',
    'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=1200&q=80',
    'Master Fridge Repairs Service',
    'A local refrigeration repair business supported with website development and ongoing local search marketing.',
    'Content Writing
Ecommerce Development
Graphic Design
PPC Advertising
Search Engine Optimization
Social Media Marketing
Website Design',
    0,
    '',
    '', '', '', '', '', '', '', '',
    4
),
(
    'Commercial Fridge Repairs Sydney',
    'commercial-fridge-repairs-sydney',
    'Sydney Repairs',
    'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=1200&q=80',
    'Commercial Fridge Repairs Sydney',
    'Before this engagement, Commercial Fridge Repairs Sydney had been losing organic value: a large share of the traffic being recorded was coming from outside Australia, and the Google Business Profile had almost no meaningful search presence, not even for branded searches. We rebuilt the website, re-optimised the Business Profile, and built out new suburb, brand and service-focused content across the Sydney commercial refrigeration market. The reporting period below shows the site back on a measurable ranking track across organic search, local search and Google''s AI features.',
    'Full Website Rebuild
Google Business Profile Re-optimisation
Local SEO & Suburb Content
Search Engine Optimization',
    1,
    '20 Jul – 20 Aug 2026',
    '102', 'Organic Clicks', '24.1K', 'Search Impressions', '+5.3%', 'GBP Growth YoY', '1.63K', 'AI Feature Impressions',
    5
),
(
    'Fridge Experts',
    'fridge-experts',
    'Experts',
    'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=1200&q=80',
    'Fridge Experts Technical Work',
    'Fridge Experts sells and services refrigeration equipment across Sydney, and this quarter''s SEO campaign moved the site decisively into the high-value part of Google''s results. Of 33 tracked commercial keywords, 28 now rank on page one and 13 sit in the top three, at an average tracked position of 5.8. Four keywords -- Fridge Repair Normanhurst, Husky Fridge Repairs Sydney, Bar Fridge Repairs and Skipio Fridge Repairs -- hold the number one position outright, and an estimated 85–90% of tracked keywords are already being surfaced inside AI Overviews and AI Mode.',
    'Local SEO & Keyword Tracking
Google Business Profile Management
Suburb & Brand Content
AI Search Visibility',
    1,
    '1 Jun – 21 Aug 2026',
    '514', 'Organic Clicks', '28/33', 'Keywords On Page 1', '5.8', 'Avg. Tracked Position', '232', 'GBP Interactions',
    6
),
(
    'Ace Fridge Repairs Sydney',
    'ace-fridge-repairs-sydney',
    'Local SEO',
    'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1200&q=80',
    'Ace Fridge Repairs Sydney',
    'Ace Fridge Repairs shows a strong and improving search position, especially on brand-name searches. Ranking #1 for manufacturer names such as CHiQ, GE Appliances, Makeline and Skipio captures customers who''ve already identified their appliance and are looking for someone to fix it -- the shortest path from search to booked job. 16 of 18 tracked keywords already appear inside Google''s AI Mode and AI Overview results, including two brand terms surfaced in both AI surfaces simultaneously.',
    'Brand & Suburb SEO
Google Business Profile Audit
AI Search Visibility
Local Content Programme',
    1,
    '1 Jul – 8 Aug 2026',
    '10/18', 'Keywords At #1', '220', 'Organic Clicks', '39.3K', 'Search Impressions', '16/18', 'Visible In AI Results',
    7
),
(
    'Sydney Art Flooring',
    'sydney-art-flooring',
    'Flooring',
    'https://images.unsplash.com/photo-1581858726788-75bc0f6a952d?auto=format&fit=crop&w=1200&q=80',
    'Sydney Art Flooring Interior',
    'A flooring specialist supported with website development and ongoing digital marketing to reach more Sydney homeowners and tradespeople.',
    'Content Writing
Ecommerce Development
Graphic Design
PPC Advertising
Search Engine Optimization
Social Media Marketing
Website Design',
    0,
    '',
    '', '', '', '', '', '', '', '',
    8
),
(
    'Fast Fridge Repairs',
    'fast-fridge-repairs',
    'Repairs',
    'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=1200&q=80',
    'Fast Fridge Repairs',
    'Fast Fridge Repairs recorded consistent, compounding growth across the reporting period: the second half of the period generated 12% more organic clicks than the first half, with search visibility spanning fridge repair, regas, coolroom and commercial refrigeration terms across Sydney suburbs. Generative AI impressions were distributed across technical guides, the homepage, and emergency repair and local service pages -- a fast-growing channel most local competitors aren''t tracking yet.',
    'Local SEO & Suburb Content
Google Business Profile Management
AI Search Visibility
Website Analytics & Reporting',
    1,
    '20 Jul – 20 Aug 2026',
    '282', 'Organic Clicks', '34.8K', 'Search Impressions', '2.68K', 'AI Overview Impressions', '+5.3%', 'GBP Growth YoY',
    9
),
(
    'Magnet Cleaning Australia',
    'magnet-cleaning-australia',
    'Cleaning',
    'https://images.unsplash.com/photo-1581092335397-9583fe92d232?auto=format&fit=crop&w=1200&q=80',
    'Magnet Cleaning Australia',
    'A commercial and residential cleaning business supported with website development and ongoing digital marketing across Australia.',
    'Content Writing
Ecommerce Development
Graphic Design
PPC Advertising
Search Engine Optimization
Social Media Marketing
Website Design',
    0,
    '',
    '', '', '', '', '', '', '', '',
    10
),
(
    'Fast Appliance Repairs',
    'fast-appliance-repairs',
    'Appliances',
    'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=1200&q=80',
    'Fast Appliance Repairs Kitchen',
    'Fast Appliance Repairs moved from broad visibility into consistent front-page presence this quarter. Across 58 tracked keywords spanning brand, location and service terms, 57 now rank on page one -- an average position of 3.7. The Google Business Profile is doing real work too: 867 interactions including 333 phone calls, close to four inbound calls a day from the listing alone, backed by a full website redesign and 33 Business Profile posts published across the period.',
    'Website Redesign
Weekly Google Business Profile Posting
Brand & Location SEO
Keyword Rank Tracking',
    1,
    '1 Jun – 21 Aug 2026',
    '57/58', 'Keywords On Page 1', '775', 'Organic Clicks', '333', 'Calls From GBP', '71.5K', 'Search Impressions',
    11
),
(
    'Royal Fragrances',
    'royal-fragrances',
    'Ecommerce',
    'https://images.unsplash.com/photo-1615397349754-cfa2066a298e?auto=format&fit=crop&w=1200&q=80',
    'Royal Fragrances Perfume Bottle',
    'An e-commerce fragrance brand supported with online store development, content and ongoing digital marketing.',
    'Content Writing
Ecommerce Development
Graphic Design
PPC Advertising
Search Engine Optimization
Social Media Marketing
Website Design',
    0,
    '',
    '', '', '', '', '', '', '', '',
    12
),
(
    'Becoming Her With Salma',
    'becoming-her-with-salma',
    'Lifestyle',
    'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=1200&q=80',
    'Becoming Her With Salma Lifestyle',
    'A personal lifestyle brand supported with website development, content and ongoing digital marketing to grow its audience.',
    'Content Writing
Ecommerce Development
Graphic Design
PPC Advertising
Search Engine Optimization
Social Media Marketing
Website Design',
    0,
    '',
    '', '', '', '', '', '', '', '',
    13
),
(
    'Northside Coffee',
    'northside-coffee',
    'Coffee',
    'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1200&q=80',
    'Northside Coffee Shop',
    'A local coffee shop supported with website development and ongoing digital marketing to grow foot traffic and local awareness.',
    'Content Writing
Ecommerce Development
Graphic Design
PPC Advertising
Search Engine Optimization
Social Media Marketing
Website Design',
    0,
    '',
    '', '', '', '', '', '', '', '',
    14
),
(
    'Freak Eats Australia',
    'freak-eats',
    'Food & Eats',
    'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=1200&q=80',
    'Freak Eats Fast Food',
    'Freak Eats Australia moved from minimal search presence to clear market leadership across Western Sydney in a single quarter. A complete website redesign plus a full Google Business Profile audit and implementation delivered 1,910 additional ranking keywords: of 56 tracked commercial terms, 53 now rank on page one and 36 hold the number one position outright, including the majority of Western Sydney suburb terms. Roughly 95% of tracked queries now appear in Google''s AI Overviews, and 50–60% are being cited by external AI assistants including ChatGPT, Gemini and Claude.',
    'Complete Website Redesign
Google Business Profile Audit & Implementation
Suburb & Dish-Level Content
AI Search Visibility',
    1,
    '1 Jun – 21 Aug 2026',
    '53/56', 'Keywords On Page 1', '36', 'Keywords At #1', '776', 'Organic Clicks', '258', 'GBP Interactions',
    15
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_name VARCHAR(150) NOT NULL,
    author_role VARCHAR(200) DEFAULT '',
    quote TEXT NOT NULL,
    rating TINYINT NOT NULL DEFAULT 5,
    photo_path VARCHAR(255) DEFAULT '',
    display_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO reviews (author_name, author_role, quote, rating, photo_path, display_order) VALUES
('John Smith', 'Director, Apex Plumbing', 'WS Digital helped us generate significantly more qualified enquiries while actively reducing our cost per lead by 32%. The entire team is transparent and responsive.', 5, 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=150&q=80', 1),
('Sarah Collins', 'Owner, Luxe Homes', 'Our overall online bookings increased by a massive 68% in just 3 short months. I highly recommend their custom strategies and clear monthly reporting processes!', 5, 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80', 2),
('Michael Brown', 'CEO, Premium Brands', 'Extremely professional, incredibly proactive and they always deliver exactly the results they promise. Our ROAS has quite frankly never been better since we switched to them!', 5, 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=150&q=80', 3),
('Emma Rodriguez', 'Marketing Manager, Bright Dental Group', 'The reporting alone is worth it -- for the first time we can actually see where every dollar goes. Bookings are up and our front desk isn''t drowning in no-shows anymore.', 5, 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80', 4);

-- Weekly results per case study (admin: Case Studies -> Weekly Stats).
-- On an existing site the "Set Up Weekly Stats" button in the admin creates
-- this table and imports each case study's current stats; no SQL needed.
CREATE TABLE IF NOT EXISTS case_study_weekly_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    case_study_id INT NOT NULL,
    is_baseline TINYINT(1) NOT NULL DEFAULT 0,
    label VARCHAR(100) NOT NULL DEFAULT '',
    week_start DATE NULL,
    week_end DATE NULL,
    organic_clicks DECIMAL(14,2) NULL,
    search_impressions DECIMAL(14,2) NULL,
    gbp_interactions DECIMAL(14,2) NULL,
    gbp_calls DECIMAL(14,2) NULL,
    gbp_directions DECIMAL(14,2) NULL,
    gbp_website_clicks DECIMAL(14,2) NULL,
    gbp_chat_clicks DECIMAL(14,2) NULL,
    shop_sales DECIMAL(14,2) NULL,
    shop_orders DECIMAL(14,2) NULL,
    shop_sessions DECIMAL(14,2) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_case_study (case_study_id)
);

-- Enquiry tracker (admin: Enquiries). Created automatically by the site on first use.
CREATE TABLE IF NOT EXISTS enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(60) NOT NULL DEFAULT '',
    service VARCHAR(150) NOT NULL DEFAULT '',
    plan VARCHAR(50) NOT NULL DEFAULT '',
    source VARCHAR(40) NOT NULL DEFAULT '',
    page VARCHAR(255) NOT NULL DEFAULT '',
    message TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'new',
    notes TEXT NULL,
    deal_value DECIMAL(12,2) NULL,
    email_sent TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
