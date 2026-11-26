<?php
// Search-result title + description for each case study page, written for
// click-through (title 45-50 characters, description 130-140). Applied by
// case-study.php on top of the database values, so it takes effect on deploy
// with no SQL to run. Titles carry the brand themselves (no auto suffix).
// A study that isn't listed here keeps the meta fields set in the admin panel.
function case_study_meta_override(string $slug): ?array
{
    static $m = [
        'clever-beavers' => ['Clever Beavers Shopify Case Study | W&S Digital', 'See how we built a Shopify store for Clever Beavers, turning educational workbooks and printables into a smooth, easy shopping journey.'],
        'cafe-calibre' => ['Cafe Calibre Shopify Case Study | W&S Digital', 'See how we built a branded Shopify store for Cafe Calibre, with organised collections and easy online ordering. Read the full case study.'],
        'on-crew' => ['OnCrew SEO Case Study | Results by W&S Digital', 'See the SEO strategy we ran for workforce platform OnCrew: keyword research, on-page and technical fixes, and content. Read the case study.'],
        'master-fridge-repairs' => ['Master Fridge Repairs Website Case Study | W&S', 'See how we built a WordPress website for Master Fridge Repairs, with clear Sydney service areas and easy enquiries. Read the case study.'],
        'commercial-fridge-repairs-sydney' => ['Commercial Fridge Repairs Website Case Study | W&S', 'See how we built a service-focused WordPress site for Commercial Fridge Repairs Sydney that wins business enquiries. Read the case study.'],
        'fridge-experts' => ['Fridge Experts Website Case Study | W&S Digital', 'See how we built a mobile-friendly WordPress site for Fridge Experts, with clear services and locations. Read the full case study today.'],
        'ace-fridge-repairs-sydney' => ['Ace Fridge Repairs Sydney Case Study | W&S Digital', 'See how we built a WordPress site for Ace Fridge Repairs Sydney, with clear services and easy repair enquiries. Read the full case study.'],
        'sydney-art-flooring' => ['Sydney Art Flooring Web Design Case Study | W&S', 'See how we built a website for Sydney Art Flooring, showcasing flooring and installs with easy enquiries. Read the full case study today.'],
        'fast-fridge-repairs' => ['Fast Fridge Repairs Web Design Case Study | W&S', 'See how we built a WordPress site for Fast Fridge Repairs that makes Sydney fridge repair enquiries easy. Read the full case study today.'],
        'magnet-cleaning-australia' => ['Magnet Cleaning Website Case Study | W&S Digital', 'See how we built a commercial cleaning website for Magnet Cleaning Australia with clear services and easy enquiries. Read the case study.'],
        'fast-appliance-repairs' => ['Fast Appliance Repairs Website Case Study | W&S', 'See how we built a WordPress site for Fast Appliance Repairs covering fridge, washer and dryer repairs. Read the full case study today.'],
        'royal-fragrances' => ['Royal Fragrances Shopify Case Study | W&S Digital', 'See how we built a Shopify perfume store for Royal Fragrances, with organised collections and a smooth checkout. Read the full case study.'],
        'becoming-her-with-salma' => ['Becoming Her With Salma Website Case Study | W&S', 'See how we built a warm, welcoming coaching website for Becoming Her With Salma, with clear programs and enquiries. Read the case study.'],
        'northside-coffee' => ['Northside Coffee Website Case Study | W&S Digital', 'See how we built a mobile-friendly cafe website for Northside Coffee, showcasing its menu and brand. Read the full case study today.'],
        'freak-eats' => ['Freak Eats Australia Website Case Study | W&S', 'See how we built a brand-led website for Freak Eats Australia, with menus, events and locations in one place. Read the full case study.'],
        'gilgit-shilajit' => ['Gilgit Shilajit Ecommerce Case Study | W&S Digital', 'See how we supported Gilgit Shilajit with store design, SEO, product content, paid ads and social media. Read the full ecommerce case study.'],
        'fast-fridge-spares' => ['Fast Fridge Spares Ecommerce Case Study | W&S', 'See how we built an organised parts store for Fast Fridge Spares, backed by SEO, ads and social media. Read the full ecommerce case study.'],
    ];
    return $m[$slug] ?? null;
}
