-- Fast Fridge Spares: Search Console update for the week to 26 Nov 2026.
--
-- Source: the Google Search Console "Last 7 days" screenshot
-- (fastfridgespares.com.au, compared with the previous 7 days):
--   Clicks 93 (previous 66), Impressions 6.66K (previous 4.76K),
--   CTR 1.4%, Average position 10.4.
--
-- What this file does (two steps, both safe to re-run):
--   1. Adds this week as a Weekly Stats row (20 - 26 Nov 2026: 93 clicks,
--      6,660 impressions). The row is only added if the case study already
--      has its starting entry (Admin -> Case Studies -> Weekly Stats set up),
--      and only if that week is not already there, so it can never double
--      count. The case study page, service pages and the homepage totals
--      pick it up automatically.
--   2. Sets the case study's "Google Search Console Performance" numbers and
--      screenshot to the same figures (the cropped screenshot is
--      images/case-studies/fast-fridge-spares-nov27-gsc.png, deployed with
--      the site). NOTE: this replaces whatever Search Console numbers and
--      screenshot that section showed before.
--
-- If the week dates are not what you want, edit them afterwards in
-- Admin -> Case Studies -> Weekly Stats (Edit on that row).
--
-- Run in Hostinger hPanel -> phpMyAdmin -> SQL tab (paste the whole file and
-- click Go).

INSERT INTO case_study_weekly_stats (case_study_id, is_baseline, label, week_start, week_end, organic_clicks, search_impressions)
SELECT cs.id, 0, '', '2026-11-20', '2026-11-26', 93, 6660
FROM case_studies cs
WHERE cs.slug = 'fast-fridge-spares'
  AND EXISTS (SELECT 1 FROM case_study_weekly_stats b WHERE b.case_study_id = cs.id AND b.is_baseline = 1)
  AND NOT EXISTS (SELECT 1 FROM case_study_weekly_stats w WHERE w.case_study_id = cs.id AND w.is_baseline = 0 AND w.week_start = '2026-11-20');

UPDATE case_studies SET
    gsc_clicks = '93',
    gsc_impressions = '6.66K',
    gsc_ctr = '1.4',
    gsc_position = '10.4',
    gsc_image = '/images/case-studies/fast-fridge-spares-nov27-gsc.png'
WHERE slug = 'fast-fridge-spares';
