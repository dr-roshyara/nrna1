<?php

return [
    // Basic
    'title'            => 'Public Digit | Secure Online Voting Platform',
    'title_separator'  => ' | ',
    'site_name'        => 'Public Digit',
    'description'      => 'Secure, anonymous and verifiable online voting for organisations. GDPR compliant, end-to-end encrypted.',
    'keywords'         => 'online voting, digital elections, secure voting, anonymous voting, GDPR voting',
    'author'           => 'Public Digit',
    'robots'           => 'index, follow',
    'googlebot'        => 'index, follow',
    'content_language' => 'en',

    // Open Graph
    'og_type'          => 'website',
    'og_url'           => env('APP_URL', 'https://publicdigit.com'),
    'og_title'         => 'Public Digit | Secure Online Voting',
    'og_description'   => 'Secure, anonymous and verifiable online voting for organisations.',
    'og_image'         => env('APP_URL', 'https://publicdigit.com') . '/images/og-home.jpg',
    'og_image_width'   => '1200',
    'og_image_height'  => '630',
    'og_image_alt'     => 'Public Digit — Secure Online Voting Platform',
    'og_site_name'     => 'Public Digit',
    'og_locale'        => 'en_US',

    // Twitter Card
    'twitter_card'        => 'summary_large_image',
    'twitter_site'        => '@publicdigit',
    'twitter_creator'     => '@publicdigit',
    'twitter_title'       => 'Public Digit | Secure Online Voting',
    'twitter_description' => 'Secure, anonymous and verifiable online voting for organisations.',
    'twitter_image'       => env('APP_URL', 'https://publicdigit.com') . '/images/og-home.jpg',

    // Mobile / PWA
    'theme_color'       => '#4F46E5',
    'mobile_app_capable'=> 'yes',

    // Canonical
    'canonical' => env('APP_URL', 'https://publicdigit.com'),

    // Publisher
    'publisher' => 'Public Digit',

    // Verification (fill in real values via .env)
    'google_verification' => env('GOOGLE_SITE_VERIFICATION', ''),
    'bing_verification'   => env('BING_SITE_VERIFICATION', ''),
    'yandex_verification' => env('YANDEX_SITE_VERIFICATION', ''),

    // Structured sections for SeoService::getMeta()
    'site_url'          => env('APP_URL', 'https://publicdigit.com'),
    'default_locale'    => env('APP_LOCALE', 'de'),
    'supported_locales' => ['de', 'en', 'np'],
    'og_locales'        => ['de' => 'de_DE', 'en' => 'en_US', 'np' => 'ne_NP'],

    'og' => [
        'type'      => 'website',
        'image'     => '/images/og-home.png',
        'site_name' => 'Public Digit',
        'width'     => 1200,
        'height'    => 630,
    ],

    'twitter' => [
        'card'    => 'summary_large_image',
        'site'    => '@publicdigit',
        'creator' => '@publicdigit',
        'image'   => '/images/og-home.png',
    ],

    'images' => [
        'logo'       => '/images/logo-2.png',
        'favicon'    => '/images/favicon.ico',
        'og_default' => '/images/og-home.png',
    ],

    'cache' => [
        'enabled'    => env('META_CACHE_ENABLED', true),
        'ttl'        => env('META_CACHE_TTL', 3600),
        'key_prefix' => 'meta:',
    ],

    'performance' => [
        'log_slow_generation' => env('LOG_SLOW_META', false),
        'slow_threshold_ms'   => env('META_SLOW_THRESHOLD', 200),
    ],

    // Organisation (for JSON-LD)
    'organisation' => [
        'name'         => 'Public Digit',
        'legal_name'   => 'Public Digit GmbH',
        'url'          => env('APP_URL', 'https://publicdigit.com'),
        'logo'         => env('APP_URL', 'https://publicdigit.com') . '/images/logo-2.png',
        'founding_date'=> '2023',
        'email'        => env('MAIL_FROM_ADDRESS', 'info@publicdigit.com'),
        'address' => [
            'street_address'   => '',
            'address_locality' => '',
            'address_region'   => '',
            'postal_code'      => '',
            'address_country'  => 'DE',
        ],
        'same_as' => [],
    ],
];
