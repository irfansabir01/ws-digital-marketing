-- November 2026 stats refresh for the case studies covered by the latest
-- Google Search Console and Google Business Profile screenshots.
--
-- Updates ONLY the dedicated "Google Search Console Performance" and
-- "Google Business Profile Performance" sections of each case study (the
-- gsc_* / gbp_* columns and their proof screenshots). It does not touch the
-- "Results At A Glance" stats, the period label, the summary text or the
-- weekly stats, so the homepage combined totals are unchanged.
--
-- Periods shown in the new screenshots:
--   Search Console: last 28 days (14 Oct - 10 Nov 2026)
--   Business Profile: Jun - Nov 2026
--
-- Screenshots are in images/case-studies/ as <slug>-nov26-*.png (new file
-- names, so the old images are left alone and nothing is cached stale).
-- Commit/deploy those images before or together with running this file.
--
-- Not covered by the new screenshots, so left exactly as they are:
--   * Freak Eats: Phone Calls (value and screenshot)
--   * Ace Fridge Repairs, Fridge Experts, AI Appliance Repairs: Chat Clicks
--
-- On Crew, Fridge Repair Parramatta and Fridge Repair Blacktown only have
-- Search Console screenshots. The Parramatta / Blacktown slugs below are
-- assumed: if those case studies don't exist (or use other slugs), the
-- UPDATE simply matches 0 rows -- change the slug and re-run.
--
-- Safe to re-run: every UPDATE sets fixed values. Run in Hostinger hPanel
-- -> phpMyAdmin, SQL tab (paste the whole file and click Go).

UPDATE case_studies SET
    gsc_clicks = '483', gsc_impressions = '38.1K',
    gsc_ctr = '1.3', gsc_position = '20.7',
    gsc_image = '/images/case-studies/ace-fridge-repairs-sydney-nov26-gsc.png',
    gsc_ai_value = '3.7K',
    gsc_ai_image = '/images/case-studies/ace-fridge-repairs-sydney-nov26-gsc-ai.png',
    gbp_interactions = '513',
    gbp_calls = '123',
    gbp_directions = '246', gbp_website_clicks = '128',
    gbp_image = '/images/case-studies/ace-fridge-repairs-sydney-nov26-gbp.png',
    gbp_calls_image = '/images/case-studies/ace-fridge-repairs-sydney-nov26-gbp-calls.png',
    gbp_directions_image = '/images/case-studies/ace-fridge-repairs-sydney-nov26-gbp-directions.png',
    gbp_website_image = '/images/case-studies/ace-fridge-repairs-sydney-nov26-gbp-website.png'
WHERE slug = 'ace-fridge-repairs-sydney';

UPDATE case_studies SET
    gsc_clicks = '133', gsc_impressions = '8.76K',
    gsc_ctr = '1.5', gsc_position = '15',
    gsc_image = '/images/case-studies/ai-appliance-repairs-nov26-gsc.png',
    gsc_ai_value = '1.28K',
    gsc_ai_image = '/images/case-studies/ai-appliance-repairs-nov26-gsc-ai.png',
    gbp_interactions = '506',
    gbp_calls = '100',
    gbp_directions = '99', gbp_website_clicks = '297',
    gbp_image = '/images/case-studies/ai-appliance-repairs-nov26-gbp.png',
    gbp_calls_image = '/images/case-studies/ai-appliance-repairs-nov26-gbp-calls.png',
    gbp_directions_image = '/images/case-studies/ai-appliance-repairs-nov26-gbp-directions.png',
    gbp_website_image = '/images/case-studies/ai-appliance-repairs-nov26-gbp-website.png'
WHERE slug = 'ai-appliance-repairs';

UPDATE case_studies SET
    gsc_clicks = '317', gsc_impressions = '38.4K',
    gsc_ctr = '0.8', gsc_position = '16.4',
    gsc_image = '/images/case-studies/fast-appliance-repairs-nov26-gsc.png',
    gsc_ai_value = '2.55K',
    gsc_ai_image = '/images/case-studies/fast-appliance-repairs-nov26-gsc-ai.png',
    gbp_interactions = '2,027',
    gbp_calls = '780',
    gbp_directions = '326', gbp_website_clicks = '921',
    gbp_image = '/images/case-studies/fast-appliance-repairs-nov26-gbp.png',
    gbp_calls_image = '/images/case-studies/fast-appliance-repairs-nov26-gbp-calls.png',
    gbp_directions_image = '/images/case-studies/fast-appliance-repairs-nov26-gbp-directions.png',
    gbp_website_image = '/images/case-studies/fast-appliance-repairs-nov26-gbp-website.png'
WHERE slug = 'fast-appliance-repairs';

UPDATE case_studies SET
    gsc_clicks = '306', gsc_impressions = '19.6K',
    gsc_ctr = '1.6', gsc_position = '15.2',
    gsc_image = '/images/case-studies/freak-eats-australia-nov26-gsc.png',
    gsc_ai_value = '1.3K',
    gsc_ai_image = '/images/case-studies/freak-eats-australia-nov26-gsc-ai.png',
    gbp_interactions = '570',
    gbp_directions = '263', gbp_website_clicks = '254',
    gbp_image = '/images/case-studies/freak-eats-australia-nov26-gbp.png',
    gbp_directions_image = '/images/case-studies/freak-eats-australia-nov26-gbp-directions.png',
    gbp_website_image = '/images/case-studies/freak-eats-australia-nov26-gbp-website.png',
    gbp_extra_label = 'Menu Views', gbp_extra_value = '34',
    gbp_extra_image = '/images/case-studies/freak-eats-australia-nov26-gbp-extra.png'
WHERE slug IN ('freak-eats-australia', 'freak-eats');

UPDATE case_studies SET
    gsc_clicks = '547', gsc_impressions = '41.8K',
    gsc_ctr = '1.3', gsc_position = '19.4',
    gsc_image = '/images/case-studies/fridge-experts-nov26-gsc.png',
    gsc_ai_value = '5.37K',
    gsc_ai_image = '/images/case-studies/fridge-experts-nov26-gsc-ai.png',
    gbp_interactions = '478',
    gbp_calls = '90',
    gbp_directions = '269', gbp_website_clicks = '115',
    gbp_image = '/images/case-studies/fridge-experts-nov26-gbp.png',
    gbp_calls_image = '/images/case-studies/fridge-experts-nov26-gbp-calls.png',
    gbp_directions_image = '/images/case-studies/fridge-experts-nov26-gbp-directions.png',
    gbp_website_image = '/images/case-studies/fridge-experts-nov26-gbp-website.png'
WHERE slug = 'fridge-experts';

UPDATE case_studies SET
    gsc_clicks = '443', gsc_impressions = '39.8K',
    gsc_ctr = '1.1', gsc_position = '20.8',
    gsc_image = '/images/case-studies/fridge-repair-experts-nov26-gsc.png',
    gsc_ai_value = '4.38K',
    gsc_ai_image = '/images/case-studies/fridge-repair-experts-nov26-gsc-ai.png',
    gbp_interactions = '353',
    gbp_calls = '34',
    gbp_directions = '245', gbp_website_clicks = '74',
    gbp_image = '/images/case-studies/fridge-repair-experts-nov26-gbp.png',
    gbp_calls_image = '/images/case-studies/fridge-repair-experts-nov26-gbp-calls.png',
    gbp_directions_image = '/images/case-studies/fridge-repair-experts-nov26-gbp-directions.png',
    gbp_website_image = '/images/case-studies/fridge-repair-experts-nov26-gbp-website.png'
WHERE slug = 'fridge-repair-experts';

UPDATE case_studies SET
    gsc_clicks = '174', gsc_impressions = '12.8K',
    gsc_ctr = '1.4', gsc_position = '23.2',
    gsc_image = '/images/case-studies/on-crew-nov26-gsc.png',
    gsc_ai_value = '1.59K',
    gsc_ai_image = '/images/case-studies/on-crew-nov26-gsc-ai.png'
WHERE slug = 'on-crew';

UPDATE case_studies SET
    gsc_clicks = '5', gsc_impressions = '2.88K',
    gsc_ctr = '0.2', gsc_position = '47.8',
    gsc_image = '/images/case-studies/fridge-repair-parramatta-nov26-gsc.png',
    gsc_ai_value = '72',
    gsc_ai_image = '/images/case-studies/fridge-repair-parramatta-nov26-gsc-ai.png'
WHERE slug = 'fridge-repair-parramatta';

UPDATE case_studies SET
    gsc_clicks = '25', gsc_impressions = '2.01K',
    gsc_ctr = '1.2', gsc_position = '31.1',
    gsc_image = '/images/case-studies/fridge-repair-blacktown-nov26-gsc.png',
    gsc_ai_value = '200',
    gsc_ai_image = '/images/case-studies/fridge-repair-blacktown-nov26-gsc-ai.png'
WHERE slug = 'fridge-repair-blacktown';
