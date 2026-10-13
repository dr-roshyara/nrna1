<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Trusted proxies (PBDIGIT-287, read by App\Http\Middleware\TrustProxies)
    |--------------------------------------------------------------------------
    |
    | The reverse proxy in front of PHP, as comma-separated addresses or CIDR ranges, e.g.
    | TRUSTED_PROXIES=10.0.0.5 or TRUSTED_PROXIES=10.0.0.0/24. Only a request from one of these may set the
    | client IP through X-Forwarded-For. Not set: no proxy is trusted (every voter then appears as the proxy's
    | address if one is in front — set this before voting opens). A wildcard ('*') is refused.
    |
    */
    'proxies' => env('TRUSTED_PROXIES'),
];
