<?php

declare(strict_types=1);

/*
 * PBDIGIT-349 I-3: Identity configuration.
 *
 * redemption_throttle (INV-ID-9, D-197): failed claim-code redemptions allowed per code and per client within a
 * window, after which attempts are refused until the window ends. Throttling never disables a claim.
 * The VALUES are [TU] in I-TDD-02 (§22 "throttling values"): these defaults are proposals awaiting the Principal's
 * confirmation before release, not accepted policy.
 *
 * client_key_secret (review R-2): the secret for HMAC-hashing client keys (e.g. IP addresses). Optional: when unset,
 * IdentityServiceProvider derives one from APP_KEY. At least 16 characters; without a usable secret every redemption
 * is refused as unavailable (fail closed). Never log or display it. Changing it (or APP_KEY, when the secret is
 * derived) is NOT neutral: every client's failure count starts again from zero, so a throttled client gets a fresh
 * allowance at once. Per-code counts are unaffected (the code key is the code's hash, not keyed).
 */
return [
    'redemption_throttle' => [
        'per_code' => (int) env('IDENTITY_REDEMPTION_THROTTLE_PER_CODE', 5),
        'per_client' => (int) env('IDENTITY_REDEMPTION_THROTTLE_PER_CLIENT', 10),
        'window_seconds' => (int) env('IDENTITY_REDEMPTION_THROTTLE_WINDOW_SECONDS', 900),
        'client_key_secret' => env('IDENTITY_REDEMPTION_THROTTLE_CLIENT_KEY_SECRET'),
    ],
];
