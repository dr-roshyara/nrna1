<?php
return [
    /*
    |--------------------------------------------------------------------------
    | SEO Meta Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all the SEO-related configuration for your application.
    | You can override these values dynamically in your controllers.
    |
    */

    // Basic Meta Tags
    'title' => env('APP_NAME', 'Public Digit') . ' - Secure Digital Voting Platform for Nepali Diaspora',
    'title_separator' => ' | ',
    'site_name' => 'Public Digit',

    'description' => 'Public Digit is a secure digital voting platform for the Nepali Diaspora worldwide. Empowering democratic participation with transparent, secure, and accessible online elections for Non-Resident Nepali Association (NRNA) members globally.',

    'keywords' => 'Public Digit, Digital Voting, Online Election, NRNA, Non Resident Nepali Association, Nepali Diaspora, Nepalese Abroad, Secure Voting Platform, Democratic Elections, Nepal Community, NRN Voting, Electronic Voting System, Nepali Network',

    // Language and Locale
    'content_language' => 'en',
    'locale' => 'en_US',
    'alternate_locales' => ['de_DE', 'ne_NP'],

    // Open Graph (Facebook, LinkedIn)
    'og_type' => 'website',
    'og_title' => 'Public Digit - Secure Digital Voting Platform',
    'og_description' => 'Empowering democratic participation for the Nepali Diaspora with secure, transparent digital voting. Join thousands of NRNA members participating in elections worldwide.',
    'og_url' => env('APP_URL', 'https://publicdigit.com'),
    'og_site_name' => 'Public Digit',
    'og_locale' => 'en_US',
    'og_image' => env('APP_URL', 'https://publicdigit.com') . '/images/og-image.jpg',
    'og_image_width' => '1200',
    'og_image_height' => '630',
    'og_image_alt' => 'Public Digit - Secure Digital Voting Platform',

    // Twitter Card
    'twitter_card' => 'summary_large_image',
    'twitter_site' => '@publicdigit',
    'twitter_creator' => '@publicdigit',
    'twitter_title' => 'Public Digit - Secure Digital Voting',
    'twitter_description' => 'Secure digital voting platform for Nepali Diaspora. Participate in NRNA elections from anywhere in the world.',
    'twitter_image' => env('APP_URL', 'https://publicdigit.com') . '/images/twitter-card.jpg',

    // Author and Publisher
    'author' => 'Public Digit Team',
    'publisher' => 'Public Digit',
    'copyright' => 'Copyright © ' . date('Y') . ' Public Digit. All rights reserved.',

    // Canonical URLs
    'canonical' => env('APP_URL', 'https://publicdigit.com'),

    // Robots
    'robots' => env('APP_ENV') === 'production' ? 'index, follow' : 'noindex, nofollow',
    'googlebot' => 'index, follow',

    // Additional SEO
    'theme_color' => '#1e40af',
    'mobile_app_capable' => 'yes',

    // organisation Schema
    'organisation' => [
        'name' => 'Public Digit',
        'legal_name' => 'Public Digit Technology Solutions',
        'url' => env('APP_URL', 'https://publicdigit.com'),
        'logo' => env('APP_URL', 'https://publicdigit.com') . '/images/logo.png',
        'founding_date' => '2020',
        'email' => 'info@publicdigit.com',
        'address' => [
            'street_address' => '',
            'address_locality' => 'Frankfurt',
            'address_region' => 'Hessen',
            'postal_code' => '',
            'address_country' => 'DE',
        ],
        'same_as' => [
            'https://www.facebook.com/publicdigit',
            'https://twitter.com/publicdigit',
            'https://www.linkedin.com/company/publicdigit',
        ],
    ],

    // Verification Codes
    'google_verification' => env('GOOGLE_VERIFICATION_CODE', ''),
    'bing_verification' => env('BING_VERIFICATION_CODE', ''),
    'yandex_verification' => env('YANDEX_VERIFICATION_CODE', ''),

    // Analytics
    'google_analytics_id' => env('GOOGLE_ANALYTICS_ID', 'GTM-MH39X8L'),
    'facebook_pixel_id' => env('FACEBOOK_PIXEL_ID', ''),

];
