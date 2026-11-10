-- Adds the new blog post "Is SEO Worth It for Small Business in Australia? (Honest ROI Maths)".
--
-- HOW TO RUN: Hostinger hPanel -> phpMyAdmin -> your database -> SQL tab ->
-- paste this whole file -> Go (same as the other blog-*-migration.sql files).
-- Safe to run more than once: the post is only inserted if its slug is not
-- already in the database, and the column checks skip columns that exist.
--
-- Nothing else needs editing. /blog, /sitemap.xml, /llms.txt, the "More From
-- The Blog" sidebar, BlogPosting + Breadcrumb + FAQPage schema and the
-- Open Graph / article meta tags are all generated from this one row.
--
-- Primary keyword: is seo worth it for small business
-- Money page: /seo   |   Cover image: /images/blog/is-seo-worth-it-for-small-business.webp

SET @col_exists = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'blog_posts' AND COLUMN_NAME = 'meta_title'
);
SET @sql = IF(@col_exists = 0,
    'ALTER TABLE blog_posts ADD COLUMN meta_title VARCHAR(200) DEFAULT '''' ',
    'SELECT ''meta_title already exists, skipping'''
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'blog_posts' AND COLUMN_NAME = 'meta_description'
);
SET @sql = IF(@col_exists = 0,
    'ALTER TABLE blog_posts ADD COLUMN meta_description VARCHAR(500) DEFAULT '''' ',
    'SELECT ''meta_description already exists, skipping'''
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists = (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'blog_posts' AND COLUMN_NAME = 'image_alt'
);
SET @sql = IF(@col_exists = 0,
    'ALTER TABLE blog_posts ADD COLUMN image_alt VARCHAR(255) NOT NULL DEFAULT ''''',
    'SELECT ''image_alt already exists, skipping'''
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

INSERT INTO blog_posts (title, slug, category, excerpt, content, image_path, image_alt, is_published, meta_title, meta_description)
SELECT 'Is SEO Worth It for Small Business in Australia? (Honest ROI Maths)', 'is-seo-worth-it-for-small-business', 'SEO', 'Is SEO worth it for small business in Australia? Use our honest ROI maths, break-even formula, a two-year worked example and real client data to decide.',
'<p>Is SEO worth it for small business owners in Australia? For most service businesses with a decent job value and customers who search on Google, yes. But it is rarely worth it in the first six months, and it is not the right investment for every business.</p>
<p>The honest answer comes down to four numbers you already know: what a job is worth, how much of that is profit, how many enquiries you win, and how long you can wait for a return. This guide walks through the maths step by step, using a worked example for a Sydney trade business. You will see when SEO usually breaks even, why year two looks very different from year one, and when your money is better spent somewhere else.</p>

<h3>The Short Answer</h3>
<p>SEO is worth it when the gross profit from new organic enquiries will exceed what you pay for SEO within a timeframe your cash flow can handle. For most local businesses that window is somewhere between 9 and 18 months. It suits businesses with healthy margins, steady local search demand and the patience to wait. It is a poor fit for businesses that need leads this week or sell low-margin, one-off products.</p>

<div style="overflow-x:auto; margin: 24px 0;">
<table style="width:100%; min-width:560px; border-collapse:collapse; font-size:0.95rem;">
<thead>
<tr style="background: var(--bg-secondary);"><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">SEO is usually worth it if...</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">SEO is probably not worth it yet if...</th></tr>
</thead>
<tbody>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Customers search for your service on Google or Maps</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Nobody searches for what you sell yet</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Each job or client brings in a meaningful profit</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Your margin per sale is a few dollars</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">You can commit to 12 months or more</td><td style="padding:12px 14px; border:1px solid var(--border-light);">You need enquiries in the next few weeks</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Your website and phone handling can convert enquiries</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Calls go unanswered and the website is broken</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Customers come back or refer others</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Every sale is a one-off with no repeat value</td></tr>
</tbody>
</table>
</div>

<h3>How to Calculate SEO ROI for Small Business</h3>
<p>Most attempts to measure SEO ROI for small business go wrong in the first line because they use revenue. A $400 job does not put $400 back in your pocket. Use gross profit instead, which is what remains after parts, labour, fuel and other direct costs.</p>

<h4>The Four Numbers You Need</h4>
<ol>
<li><strong>Average job or sale value.</strong> Pull this from your invoicing software for the last 6 to 12 months.</li>
<li><strong>Gross margin.</strong> The percentage of that job value you keep after direct costs.</li>
<li><strong>Close rate.</strong> The share of enquiries that become paying jobs. If 10 people call and 5 book, your close rate is 50%.</li>
<li><strong>Repeat and referral value.</strong> Optional, but important. A customer who returns twice or refers a neighbour is worth far more than one invoice.</li>
</ol>

<h4>The Three Formulas</h4>
<ul>
<li><strong>Profit per enquiry</strong> = average job value &times; gross margin &times; close rate</li>
<li><strong>Break-even enquiries per month</strong> = monthly SEO cost &divide; profit per enquiry</li>
<li><strong>SEO ROI</strong> = (gross profit from organic customers &minus; SEO cost) &divide; SEO cost &times; 100</li>
</ul>
<p>An organic enquiry is any contact that came from unpaid search. That includes phone calls and form submissions from your website, plus calls, messages and direction requests from your Google Business Profile. If you cannot separate these from other lead sources, you cannot measure ROI, so tracking needs to be set up before any SEO work starts.</p>

<h4>How Many Enquiries Do You Need to Break Even?</h4>
<p>The table below uses a round example SEO cost of $1,500 per month. This is not a quote or a market average. It is simply a number that makes the maths easy to follow. Swap in your own figures.</p>

<div style="overflow-x:auto; margin: 24px 0;">
<table style="width:100%; min-width:620px; border-collapse:collapse; font-size:0.95rem;">
<thead>
<tr style="background: var(--bg-secondary);"><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Example business</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Job value</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Margin</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Close rate</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Profit per enquiry</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Enquiries/month to break even</th></tr>
</thead>
<tbody>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Mobile dog groomer</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$90</td><td style="padding:12px 14px; border:1px solid var(--border-light);">60%</td><td style="padding:12px 14px; border:1px solid var(--border-light);">70%</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$37.80</td><td style="padding:12px 14px; border:1px solid var(--border-light);">About 40</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Appliance repairer</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$350</td><td style="padding:12px 14px; border:1px solid var(--border-light);">50%</td><td style="padding:12px 14px; border:1px solid var(--border-light);">50%</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$87.50</td><td style="padding:12px 14px; border:1px solid var(--border-light);">About 17</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Electrician</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$600</td><td style="padding:12px 14px; border:1px solid var(--border-light);">40%</td><td style="padding:12px 14px; border:1px solid var(--border-light);">40%</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$96</td><td style="padding:12px 14px; border:1px solid var(--border-light);">About 16</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Bookkeeper (annual client)</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$3,000</td><td style="padding:12px 14px; border:1px solid var(--border-light);">50%</td><td style="padding:12px 14px; border:1px solid var(--border-light);">30%</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$450</td><td style="padding:12px 14px; border:1px solid var(--border-light);">About 4</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Kitchen renovator</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$25,000</td><td style="padding:12px 14px; border:1px solid var(--border-light);">20%</td><td style="padding:12px 14px; border:1px solid var(--border-light);">10%</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$500</td><td style="padding:12px 14px; border:1px solid var(--border-light);">About 3</td></tr>
</tbody>
</table>
</div>

<p>The pattern is clear. The higher your profit per job, the fewer enquiries SEO has to produce to pay for itself. A renovator needs about three extra enquiries a month. A dog groomer needs around forty, which is far harder for SEO alone to deliver at that budget. Neither business is &quot;wrong&quot; to consider SEO, but the groomer would need a lower cost, a higher price point or strong repeat bookings for the numbers to work.</p>

<h3>Worked Example: Year One vs Year Two for a Sydney Appliance Repairer</h3>
<p>Let&#39;s follow the appliance repairer from the table through two years. The assumptions are: $350 average job, 50% margin ($175 gross profit per job), 50% close rate and a $1,500 monthly SEO cost. Enquiry numbers ramp up gradually, which is how organic visibility typically builds. These are illustrative figures for planning, not a forecast or a guarantee.</p>

<div style="overflow-x:auto; margin: 24px 0;">
<table style="width:100%; min-width:700px; border-collapse:collapse; font-size:0.95rem;">
<thead>
<tr style="background: var(--bg-secondary);"><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Period</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Organic enquiries/month</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Jobs/month</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Gross profit (period)</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">SEO cost (period)</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Net (period)</th></tr>
</thead>
<tbody>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Months 1 to 3</td><td style="padding:12px 14px; border:1px solid var(--border-light);">2</td><td style="padding:12px 14px; border:1px solid var(--border-light);">1</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$525</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$4,500</td><td style="padding:12px 14px; border:1px solid var(--border-light);">&minus;$3,975</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Months 4 to 6</td><td style="padding:12px 14px; border:1px solid var(--border-light);">8</td><td style="padding:12px 14px; border:1px solid var(--border-light);">4</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$2,100</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$4,500</td><td style="padding:12px 14px; border:1px solid var(--border-light);">&minus;$2,400</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Months 7 to 9</td><td style="padding:12px 14px; border:1px solid var(--border-light);">16</td><td style="padding:12px 14px; border:1px solid var(--border-light);">8</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$4,200</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$4,500</td><td style="padding:12px 14px; border:1px solid var(--border-light);">&minus;$300</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Months 10 to 12</td><td style="padding:12px 14px; border:1px solid var(--border-light);">24</td><td style="padding:12px 14px; border:1px solid var(--border-light);">12</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$6,300</td><td style="padding:12px 14px; border:1px solid var(--border-light);">$4,500</td><td style="padding:12px 14px; border:1px solid var(--border-light);">+$1,800</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);"><strong>Year 1 total</strong></td><td style="padding:12px 14px; border:1px solid var(--border-light);"></td><td style="padding:12px 14px; border:1px solid var(--border-light);"></td><td style="padding:12px 14px; border:1px solid var(--border-light);"><strong>$13,125</strong></td><td style="padding:12px 14px; border:1px solid var(--border-light);"><strong>$18,000</strong></td><td style="padding:12px 14px; border:1px solid var(--border-light);"><strong>&minus;$4,875 (ROI &minus;27%)</strong></td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);"><strong>Year 2 total</strong></td><td style="padding:12px 14px; border:1px solid var(--border-light);"><strong>28</strong></td><td style="padding:12px 14px; border:1px solid var(--border-light);"><strong>14</strong></td><td style="padding:12px 14px; border:1px solid var(--border-light);"><strong>$29,400</strong></td><td style="padding:12px 14px; border:1px solid var(--border-light);"><strong>$18,000</strong></td><td style="padding:12px 14px; border:1px solid var(--border-light);"><strong>+$11,400 (ROI 63%)</strong></td></tr>
</tbody>
</table>
</div>

<p>This is the part many sales pitches leave out. Judged at month six, this SEO campaign looks like a bad decision: the business is almost $6,400 behind. Judged at month 24, it has paid for itself and is profitable, with a cumulative gain of about $6,500 before counting a single repeat customer or referral. If even a third of those customers book again within two years, the return improves considerably.</p>
<p>The table also shows why cash flow matters as much as ROI. A business that cannot carry a loss for three quarters may abandon SEO right before it starts paying, which is the worst possible outcome.</p>

<h4>What Changes the Numbers Most</h4>
<ul>
<li><strong>Job value and margin.</strong> A higher average job shortens the payback period more than anything else.</li>
<li><strong>Local competition.</strong> Ranking for &quot;fridge repair Blacktown&quot; is a different challenge to ranking for &quot;plumber Sydney&quot;. Tighter suburb and service combinations usually move faster.</li>
<li><strong>Your starting point.</strong> An established website with some history tends to respond sooner than a brand-new domain.</li>
<li><strong>Website conversion.</strong> If visitors arrive and leave without calling, extra traffic will not fix it. It is worth reviewing <a href="/blog/website-not-converting-traffic-into-leads">why a website isn&#39;t converting traffic into leads</a> before or alongside any SEO work.</li>
<li><strong>Phone handling.</strong> A missed call from a ready-to-book customer is lost profit that no ranking can recover.</li>
</ul>

<h3>Why SEO Returns Build Over Time</h3>
<p>With Google Ads, you pay for every click, and the leads stop when the budget stops. With SEO, you pay for work: technical fixes, better service pages, location content, a well-managed Google Business Profile and genuine reviews. Once those pages rank, they can keep producing enquiries without a per-click charge.</p>
<p>That is why the cost per enquiry from SEO usually falls over time while the cost per enquiry from ads tends to stay tied to click prices. Our <a href="/blog/seo-vs-google-ads-small-business">SEO vs Google Ads comparison</a> covers when each channel makes sense and how many businesses use ads for immediate leads while SEO builds in the background.</p>
<p>Two cautions keep this realistic. First, rankings are not permanent. Competitors improve their websites and Google updates its systems, so some ongoing work is needed. Second, results take time. <a href="https://developers.google.com/search/docs/fundamentals/do-i-need-seo" target="_blank" rel="noopener">Google&#39;s own guidance</a> says it typically takes four months to a year from making changes before you start to see the benefits. Any provider promising page one in a few weeks is setting you up for disappointment.</p>

<h3>Does SEO Work for Small Business? What Our Client Data Shows</h3>
<p>Formulas are useful, but it helps to see real numbers. Here is what two appliance and fridge repair businesses we work with recorded over a six-month period, taken from Google Search Console and Google Business Profile reports.</p>

<div style="overflow-x:auto; margin: 24px 0;">
<table style="width:100%; min-width:520px; border-collapse:collapse; font-size:0.95rem;">
<thead>
<tr style="background: var(--bg-secondary);"><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Metric (six months)</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">AI Appliance Repairs</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Fridge Repair Experts</th></tr>
</thead>
<tbody>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Organic clicks</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Grew from 40 to 524</td><td style="padding:12px 14px; border:1px solid var(--border-light);">971</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Search impressions</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Grew from 614 to 22,400</td><td style="padding:12px 14px; border:1px solid var(--border-light);">162,000</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Google Business Profile interactions</td><td style="padding:12px 14px; border:1px solid var(--border-light);">425</td><td style="padding:12px 14px; border:1px solid var(--border-light);">334</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Phone calls from the profile</td><td style="padding:12px 14px; border:1px solid var(--border-light);">79</td><td style="padding:12px 14px; border:1px solid var(--border-light);">37</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Website clicks from the profile</td><td style="padding:12px 14px; border:1px solid var(--border-light);">283</td><td style="padding:12px 14px; border:1px solid var(--border-light);">74</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Direction requests</td><td style="padding:12px 14px; border:1px solid var(--border-light);">56</td><td style="padding:12px 14px; border:1px solid var(--border-light);">223</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Average search position</td><td style="padding:12px 14px; border:1px solid var(--border-light);">11.8</td><td style="padding:12px 14px; border:1px solid var(--border-light);">26</td></tr>
</tbody>
</table>
</div>

<p>Now apply the maths. AI Appliance Repairs received 79 calls from its profile in six months, roughly 13 a month. Purely as an illustration, if half of those became jobs at the $175 gross profit used earlier, that is about $1,150 a month in gross profit from profile calls alone, before counting the 283 website clicks. We do not publish client revenue, so treat this as a way to test the logic rather than a reported result. The full <a href="/case-studies/ai-appliance-repairs">AI Appliance Repairs case study</a> explains the local SEO and profile work behind those numbers.</p>
<p>Fridge Repair Experts shows the other side of the story. It has strong visibility, with 162,000 impressions, but an average position of 26 and a click-through rate of 0.6%. Being seen is not the same as being chosen. Most of the profit in that account still sits in moving key pages higher, which is why early SEO reports should be read for direction of travel rather than final value.</p>
<p>So, does SEO work for small business? These results suggest it can, particularly for local services with urgent demand. They also show that impressions and rankings are leading indicators. Calls, bookings and profit are the numbers that decide whether it was worth it.</p>

<h3>Benefits of SEO for Small Business Beyond the Spreadsheet</h3>
<p>Some benefits of SEO for small business are hard to put in a formula but still affect the decision.</p>
<ul>
<li><strong>You appear at the moment of need.</strong> Someone searching &quot;washing machine repair near me&quot; wants help now, which tends to mean shorter sales conversations and higher close rates.</li>
<li><strong>Google Maps visibility.</strong> For local businesses, the map results often drive more calls than the website itself. These <a href="/blog/local-seo-wins-for-small-business">five local SEO wins</a> are a good place to start.</li>
<li><strong>Trust before the first call.</strong> Reviews, clear service pages and an accurate profile do some of the selling before the customer picks up the phone.</li>
<li><strong>An asset you control.</strong> Well-built pages and an established profile keep their value. Advertising spend does not.</li>
<li><strong>Better results from other channels.</strong> Faster, clearer service pages also improve Google Ads and social campaigns that send traffic to the same website.</li>
</ul>

<h3>When SEO Is Not Worth It (Yet)</h3>
<p>An honest answer to &quot;is SEO worth it&quot; has to include the situations where it isn&#39;t. Consider holding off, or starting with something smaller, if any of these apply:</p>
<ul>
<li><strong>You need leads within weeks.</strong> Google Ads or Local Services Ads can produce calls much faster while you build organic visibility.</li>
<li><strong>Your profit per sale is very low.</strong> If you need 40 or more extra enquiries a month to cover the cost, focus on pricing, repeat bookings or referrals first.</li>
<li><strong>There is little or no search demand.</strong> A new product category that people don&#39;t yet search for is better suited to social media, partnerships or paid awareness.</li>
<li><strong>Your website cannot convert.</strong> Fix broken forms, slow pages and missing phone numbers before paying to send more people there.</li>
<li><strong>You cannot commit for at least 12 months.</strong> Stopping at month five usually means paying for the hard part and missing the payoff.</li>
</ul>
<p>None of these is permanent. Many businesses start with Google Business Profile improvements and basic on-page fixes, then expand into full SEO once cash flow and capacity allow.</p>

<h3>Is Paying for SEO Worth It, or Should You Do It Yourself?</h3>
<p>Google itself notes that small local businesses can often do much of the work themselves. The real question is whether your time is better spent on SEO or on paid work. If you can bill $80 an hour and SEO takes you six hours a week, that is roughly $2,080 a month in time you are not invoicing.</p>

<div style="overflow-x:auto; margin: 24px 0;">
<table style="width:100%; min-width:680px; border-collapse:collapse; font-size:0.95rem;">
<thead>
<tr style="background: var(--bg-secondary);"><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Option</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Cash cost</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Time cost to you</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Best for</th><th style="text-align:left; padding:12px 14px; border:1px solid var(--border-light); color: var(--navy); font-weight:800;">Main risk</th></tr>
</thead>
<tbody>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Do it yourself</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Low (tools and learning)</td><td style="padding:12px 14px; border:1px solid var(--border-light);">High, every week</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Owners with time and interest, tight budgets</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Slow progress, technical gaps, work drops off when busy</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Freelancer</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Moderate</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Medium</td><td style="padding:12px 14px; border:1px solid var(--border-light);">One-off fixes or a narrow scope</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Quality varies, limited capacity for content and profiles</td></tr>
<tr><td style="padding:12px 14px; border:1px solid var(--border-light);">Agency</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Highest</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Low</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Owners who want leads without doing the work</td><td style="padding:12px 14px; border:1px solid var(--border-light);">Paying for activity rather than results if reporting is weak</td></tr>
</tbody>
</table>
</div>

<p>Is paying for SEO worth it? It is when your hourly value is higher than what you would pay someone else, and when the provider reports on enquiries rather than just rankings. If neither is true, a do-it-yourself approach focused on your Google Business Profile, reviews and core service pages is a sensible first step.</p>

<h3>Is SEO Still Relevant With AI Search?</h3>
<p>Yes. Many owners now ask, is SEO still relevant when Google shows AI Overviews and customers use tools such as ChatGPT. For local service businesses, the foundations are largely the same. AI-generated answers draw on websites, business profiles, reviews and directory information. A business with clear service pages, consistent details and genuine reviews gives those systems more to work with.</p>
<p>Local searches with urgent intent, such as &quot;emergency electrician Penrith&quot;, still lead most people to a short list of businesses they can call. Being on that list is still the job of SEO. What has changed is that clarity matters more. Pages that answer questions directly and state prices, service areas and availability plainly are easier for both people and AI tools to use. No one can guarantee an AI mention, so treat it as a bonus on top of strong search visibility rather than the main reason to invest.</p>

<h3>A Five-Question Test Before You Invest</h3>
<p>Answer these honestly. Each &quot;yes&quot; strengthens the case for SEO.</p>
<ol>
<li>Do people in your area search for your service on Google or Maps every month?</li>
<li>Is your profit per enquiry high enough that you need fewer than about 20 extra enquiries a month to break even?</li>
<li>Can your business carry the cost for 9 to 12 months before expecting a positive return?</li>
<li>Do you answer calls promptly, and does your website make it easy to enquire?</li>
<li>Do customers come back or refer others?</li>
</ol>
<p><strong>Four or five yes answers:</strong> SEO is very likely worth it. Start with a clear plan and proper tracking. <strong>Two or three:</strong> fix the weak points first, such as your website, phone handling or profile, then reassess in a few months. <strong>Zero or one:</strong> paid advertising, referrals or partnerships are likely to give a faster, safer return for now.</p>

<h3>How to Make Sure Your SEO Spend Pays Off</h3>
<p>If the numbers stack up, these steps protect your investment:</p>
<ul>
<li><strong>Set up tracking first.</strong> Call tracking, form tracking and Google Business Profile insights should be in place before work begins, so you have a baseline.</li>
<li><strong>Agree on business metrics.</strong> Rankings are useful signals, but calls, bookings and cost per enquiry are what you are paying for.</li>
<li><strong>Prefer flexible terms.</strong> Month-to-month arrangements keep a provider accountable for results rather than a contract.</li>
<li><strong>Ask for plain-English monthly reports.</strong> You should know what was done, what changed and what happens next.</li>
<li><strong>Watch for red flags.</strong> Guaranteed number one rankings, bulk link packages and refusing to give you access to your own accounts are all reasons to walk away.</li>
<li><strong>Budget realistically.</strong> Understanding how much SEO costs in Australia helps you set a monthly figure that your break-even maths can support.</li>
</ul>
<p>If you want SEO to sit inside a wider plan that includes your website, Google Business Profile and ads, our approach to <a href="/small-business-digital-marketing">digital marketing for small business</a> shows how those channels fit together.</p>

<h3>Making the Call for Your Business</h3>
<p>Is SEO worth it for small business? Not automatically, but it is not a gamble either. It is an investment you can model. Work out your profit per enquiry, calculate your break-even number, and be honest about whether you can wait 9 to 18 months for the return. If the maths works, SEO is often one of the most cost-effective ways for a local business to win steady enquiries over the long term.</p>
<p>If you would like a second opinion on your numbers, <a href="/">W&amp;S Digital Marketing</a> can review your website, search visibility and Google Business Profile and show you where the realistic opportunities are. You can also read more about our <a href="/seo">SEO services</a>, including how we plan, track and report on local search work.</p>

<h3>Frequently Asked Questions</h3>
<h4>How much should a small business spend on SEO each month?</h4>
<p>Work backwards from your break-even number rather than picking a figure. Multiply your average job value by your margin and close rate to get profit per enquiry. Then decide how many extra enquiries a month you can realistically expect within a year. Your monthly budget should be comfortably below the profit those enquiries would generate.</p>
<h4>What is a good ROI for SEO?</h4>
<p>There is no single benchmark, because it depends on job value, competition and how long you measure. A practical goal is to reach break-even within 12 to 18 months and a clearly positive return in year two. Always calculate ROI on gross profit, not revenue, or the figure will look better than it really is.</p>
<h4>Can SEO work for a brand-new small business website?</h4>
<p>Yes, but expect a slower start. A new domain has no history with Google, so early months are spent on technical setup, service pages and building the Google Business Profile. Many new businesses run Google Ads at the same time to generate enquiries while organic visibility develops.</p>
<h4>Should I stop SEO once I reach the first page?</h4>
<p>Stopping completely is risky. Competitors keep improving their websites, reviews keep coming in for other businesses, and search results change over time. Many businesses reduce the intensity once key pages rank well, then keep a smaller ongoing plan for reviews, profile updates and content to protect their positions.</p>
<h4>Is SEO worth it for a business that only serves one suburb?</h4>
<p>Often, yes. A tightly defined area usually means less competition, so a well-managed Google Business Profile and a few strong service pages can rank sooner. The deciding factor is search demand. If very few people in that suburb search for your service, referrals and local partnerships may deliver more.</p>
',
'/images/blog/is-seo-worth-it-for-small-business.webp', 'Is SEO worth it for small business? Chart of profit versus SEO cost per quarter, showing Year 1 ROI of -27% and Year 2 ROI of +63%', 1, 'Is SEO Worth It for Small Business? Honest ROI Maths', 'Is SEO worth it for small business in Australia? See the honest ROI maths, the break-even formula, real client data and when SEO is not worth it yet.'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM blog_posts WHERE slug = 'is-seo-worth-it-for-small-business');
