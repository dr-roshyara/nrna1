<?php

return [
    /*
    |--------------------------------------------------------------------------
    | IP Address Validation Control
    |--------------------------------------------------------------------------
    |
    | This setting controls whether IP address validation is enforced during voting.
    |
    | Options:
    | - 1 (enabled): Voters with voting_ip set can only vote from that IP
    | - 0 (disabled): Voters can vote from any IP address
    |
    | When enabled, voters approved with IP checking will be restricted to their
    | registered IP address. Voters approved without IP checking can vote from any IP.
    |
    */
    'control_ip_address' => env('CONTROL_IP_ADDRESS', 1),

    /*
    |--------------------------------------------------------------------------
    | IP Validation Mode
    |--------------------------------------------------------------------------
    |
    | How to handle IP validation when enabled:
    |
    | Options:
    | - 'strict': Block voting if IP doesn't match (recommended for production)
    | - 'log_only': Allow voting but log mismatches (for testing/debugging)
    | - 'disabled': No IP checking
    |
    */
    'ip_validation_mode' => env('VOTING_IP_MODE', 'strict'),

    /*
    |--------------------------------------------------------------------------
    | IP Mismatch Action
    |--------------------------------------------------------------------------
    |
    | What to do when IP mismatch is detected (only when control_ip_address=1)
    |
    | Options:
    | - 'block': Prevent voting and show error message
    | - 'warn': Allow but log warning
    |
    */
    'ip_mismatch_action' => env('IP_MISMATCH_ACTION', 'block'),

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Control how IP validation events are logged
    |
    */
    'logging' => [
        'enabled' => env('VOTING_IP_LOGGING', true),
        'log_successful_matches' => env('LOG_IP_MATCHES', false),
        'log_mismatches' => env('LOG_IP_MISMATCHES', true),
        'log_bypassed_checks' => env('LOG_IP_BYPASSED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    |
    | Customizable error messages for IP validation failures
    |
    */
    'messages' => [
        'ip_mismatch_english' => 'You can only vote from your registered IP address. Your current IP does not match.',
        'ip_mismatch_nepali' => 'तपाईं आफ्नो दर्ता गरिएको IP ठेगानाबाट मात्र मतदान गर्न सक्नुहुन्छ। तपाईंको हालको IP मेल खाँदैन।',
        'contact_support' => 'If you believe this is an error, please contact the election committee.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Trusted Proxies Configuration
    |--------------------------------------------------------------------------
    |
    | If using load balancers or reverse proxies, the system needs to know
    | to trust the X-Forwarded-For header
    |
    */
    'trust_proxies' => env('VOTING_TRUST_PROXIES', false),
];
