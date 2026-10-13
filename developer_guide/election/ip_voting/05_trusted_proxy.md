# 05: Trusted proxy: where the client IP comes from (PBDIGIT-287)

**Story:** `PBDIGIT-287` · **Code:**
- `app/Http/Middleware/TrustProxies.php`
- `config/trustedproxy.php`
- `bootstrap/app.php`

**Tests:**
- `tests/Unit/Http/TrustedProxyTest.php` (real HTTP kernel, database-free)
- `tests/Architecture/ClientIpHasOneSourceTest.php`

## Purpose
Every IP protection (guides 01–04, the constitutional limit and IP binding) reads `$request->ip()`. When the app trusts a proxy, `$request->ip()` is the client named in `X-Forwarded-For`. Anyone can write that header, so it may only be believed from **our** reverse proxy.

Before this story the app trusted every sender (`trustProxies(at: '*')`), so a forged header got past all IP protections.

## How it works
`bootstrap/app.php` swaps in the app's middleware:
```php
$middleware->replace(\Illuminate\Http\Middleware\TrustProxies::class, \App\Http\Middleware\TrustProxies::class);
```
**The middleware:**
- trusts exactly the configured proxies;
- refuses wildcards;
- reads only the `X-Forwarded-For/-Host/-Port/-Proto` headers.
```php
protected function setTrustedProxyIpAddresses(Request $request)
{
    $this->setTrustedProxyIpAddressesToSpecificIps($request, $this->configuredProxies());
}
```
`configuredProxies()` splits `trustedproxy.proxies` on commas.
- **If any entry is `*`, `**` or `REMOTE_ADDR`** (Laravel's "trust whoever connects"), it logs `trusted_proxies_wildcard_refused` and returns `[]`, which trusts no proxy.
- **Why the override replaces the framework's method:** that method falls back to the raw config value when the list is empty, so a refused `*` would be trusted after all.

| Request | `TRUSTED_PROXIES` | `$request->ip()` |
|---|---|---|
| from 198.51.100.9, `X-Forwarded-For: 1.2.3.4` | not set | 198.51.100.9 |
| from 10.0.0.5 (the proxy), `X-Forwarded-For: 203.0.113.7` | `10.0.0.5` | 203.0.113.7 |
| from 198.51.100.9, `X-Forwarded-For: 1.2.3.4` | `10.0.0.5` | 198.51.100.9 |
| from the proxy, `X-Forwarded-For: 1.2.3.4, 203.0.113.7` (client prepended a fake) | `10.0.0.5` | 203.0.113.7 |
| anything | `*` | the connecting address (refused, warning) |

## Configuration and deployment
- **`.env`:** `TRUSTED_PROXIES=10.0.0.5`, or several entries and ranges: `TRUSTED_PROXIES=10.0.0.5, 10.0.1.0/24`. Then run `php artisan config:clear`.
- **Set it before voting opens.** Behind a proxy without it, every voter appears as the proxy's address and all share one per-IP limit.
- **The proxy must set or append `X-Forwarded-For` itself** (for nginx: `proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;`).

## How to extend it
- **Read the client IP only as `$request->ip()`** (or `getClientIp()` without arguments). Never parse `X-Forwarded-For` yourself.
- **Never add another "trust proxies" switch.** `ClientIpHasOneSourceTest` fails if the removed voting-specific setting returns, or if `getClientIp()` is called with an argument.

## Testing
`TrustedProxyTest` registers a test-only route that returns `$request->ip()` and sends requests through the real HTTP kernel with chosen `REMOTE_ADDR` and headers. It asserts that no database query runs.

## Pitfalls
- **`laravel_cloud()`:** the framework's own middleware trusts everything on Laravel Cloud when nothing is configured. Ours never does.
- **IPv6 proxies** work the same way: list the address or range.
- **`Forwarded` (RFC 7239) is ignored.** If the proxy only sends that header, configure it to send `X-Forwarded-For`.

**Traceability:** `PBDIGIT-287`, 2026-10-13. RED 7 of 11 and 2 of 2 → GREEN 11/49 and 2/2; isolated suite 118, 0 regressions. Evidence: `docs/publicdigit/election/evidence/2026-10-13-pbdigit287-trusted-proxy/`.
