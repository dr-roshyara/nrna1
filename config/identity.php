<?php

declare(strict_types=1);

/*
 * PBDIGIT-349 I-3: Identity configuration.
 *
 * redemption_throttle (INV-ID-9, D-197): failed claim-code redemptions allowed per code and per client within a
 * window, after which attempts are refused until the window ends. Throttling never disables a claim.
 * The VALUES are [TU] in I-TDD-02 (§22 "throttling values"): these defaults are proposals awaiting the Principal's
 * confirmation before release, not accepted policy.
 */
return [
    'redemption_throttle' => [
        'per_code' => (int) env('IDENTITY_REDEMPTION_THROTTLE_PER_CODE', 5),
        'per_client' => (int) env('IDENTITY_REDEMPTION_THROTTLE_PER_CLIENT', 10),
        'window_seconds' => (int) env('IDENTITY_REDEMPTION_THROTTLE_WINDOW_SECONDS', 900),
    ],
];
