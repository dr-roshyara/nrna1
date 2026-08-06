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

    /*
    |--------------------------------------------------------------------------
    | Constitutional Mode (D.0 Sovereignty Transition)
    |--------------------------------------------------------------------------
    |
    | When enabled, the ValidateVotingIp middleware switches from blocking to
    | shadow-recording mode: it records divergence telemetry when it WOULD have
    | blocked, but passes the request through to the constitutional evaluator.
    |
    | D.0.1: Shadow-run constitutionally, record divergence telemetry
    | D.0.2: Verify no divergence after observation window
    | D.0.3: Remove middleware entirely
    |
    | Options:
    | - false (default): Legacy behavior — block on IP mismatch
    | - true: Shadow mode — record divergence, pass through to constitutional path
    |
    */
    'constitutional_mode' => env('VOTING_CONSTITUTIONAL_MODE', true),

    /*
    |--------------------------------------------------------------------------
    | Observe reads of deprecated election-state fields (PBDIGIT-58A)
    |--------------------------------------------------------------------------
    |
    | When true, queries against the elections table that filter on the
    | deprecated `status` or `is_active` fields are logged with their calling
    | code path, so the migration inventory is measured rather than inferred.
    |
    | Observation only: it cannot change a query or its results, and it never
    | throws. Default false, so behaviour is unchanged unless switched on.
    |
    | See docs/publicdigit/backlog/PBDIGIT-58-complete-legacy-election-state-migration.md
    |
    */
    'observe_legacy_election_state' => env('OBSERVE_LEGACY_ELECTION_STATE', false),
];
