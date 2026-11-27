-- Search Console update for the week 18 - 24 Nov 2026: Fast Fridge Spares and
-- Freak Eats Australia.
--
-- Source: the Google Search Console "Last 7 days" screenshots (the chart
-- axis runs 11/18/26 - 11/24/26):
--   Fast Fridge Spares (fastfridgespares.com.au):
--       93 clicks (previous 7 days: 66), 6.66K impressions (previous 4.76K),
--       CTR 1.4%, average position 10.4
--   Freak Eats Australia:
--       55 clicks, 6.72K impressions, CTR 0.8%, average position 16.6
--
-- For each case study this file:
--   1. Adds the week as a Weekly Stats row (18 - 24 Nov 2026). The row is only
--      added if the case study already has its starting entry (Weekly Stats
--      set up) and that week is not already there, so it never double counts.
--      The case study page, service pages and homepage totals update
--      automatically.
--   2. Sets the case study's "Google Search Console Performance" figures and
--      screenshot to the same numbers (cropped screenshots are
--      images/case-studies/*-nov27-gsc.png, deployed with the site). NOTE:
--      this replaces whatever Search Console figures and screenshot that
--      section showed before.
--
-- Fast Fridge Spares: an earlier version of this update dated its week
-- 20 - 26 Nov by mistake. The first statement moves that row to 18 - 24 Nov
-- if you already ran it (it does nothing otherwise).
--
-- Safe to re-run. Run in Hostinger hPanel -> phpMyAdmin -> SQL tab (paste the
-- whole file and click Go).

-- ---------------------------------------------------------------- Fast Fridge Spares
UPDATE case_study_weekly_stats w
JOIN case_studies cs ON cs.id = w.case_study_id AND cs.slug = 'fast-fridge-spares'
SET w.week_start = '2026-11-18', w.week_end = '2026-11-24'
WHERE w.is_baseline = 0 AND w.week_start = '2026-11-20' AND w.week_end = '2026-11-26' AND w.organic_clicks = 93;

INSERT INTO case_study_weekly_stats (case_study_id, is_baseline, label, week_start, week_end, organic_clicks, search_impressions)
SELECT cs.id, 0, '', '2026-11-18', '2026-11-24', 93, 6660
FROM case_studies cs
WHERE cs.slug = 'fast-fridge-spares'
  AND EXISTS (SELECT 1 FROM case_study_weekly_stats b WHERE b.case_study_id = cs.id AND b.is_baseline = 1)
  AND NOT EXISTS (SELECT 1 FROM case_study_weekly_stats w WHERE w.case_study_id = cs.id AND w.is_baseline = 0 AND w.week_start = '2026-11-18');

UPDATE case_studies SET
    gsc_clicks = '93',
    gsc_impressions = '6.66K',
    gsc_ctr = '1.4',
    gsc_position = '10.4',
    gsc_image = '/images/case-studies/fast-fridge-spares-nov27-gsc.png'
WHERE slug = 'fast-fridge-spares';

-- ---------------------------------------------------------------- Freak Eats Australia
-- (its live slug is freak-eats-australia; 'freak-eats' is the original seed slug)
INSERT INTO case_study_weekly_stats (case_study_id, is_baseline, label, week_start, week_end, organic_clicks, search_impressions)
SELECT cs.id, 0, '', '2026-11-18', '2026-11-24', 55, 6720
FROM case_studies cs
WHERE cs.slug IN ('freak-eats-australia', 'freak-eats')
  AND EXISTS (SELECT 1 FROM case_study_weekly_stats b WHERE b.case_study_id = cs.id AND b.is_baseline = 1)
  AND NOT EXISTS (SELECT 1 FROM case_study_weekly_stats w WHERE w.case_study_id = cs.id AND w.is_baseline = 0 AND w.week_start = '2026-11-18');

UPDATE case_studies SET
    gsc_clicks = '55',
    gsc_impressions = '6.72K',
    gsc_ctr = '0.8',
    gsc_position = '16.6',
    gsc_image = '/images/case-studies/freak-eats-australia-nov27-gsc.png'
WHERE slug IN ('freak-eats-australia', 'freak-eats');
