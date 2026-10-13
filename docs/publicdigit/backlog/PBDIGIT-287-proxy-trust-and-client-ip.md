# PBDIGIT-287: Every proxy is trusted, so the client IP can be spoofed (deployment-dependent)

**Type:** Security / operations · **Epic:** none · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the IP voting strategy review (finding D). **Index row:** added 2026-10-13.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · priority high **if the application is reachable without a trusted proxy** |
| **Classification** | the code is **verified**; the exposure **depends on the production topology** (not in the repo) |

## Finding
- **`bootstrap/app.php:31`:** `$middleware->trustProxies(at: '*')` treats every remote address as a trusted proxy.
  - **Reachable directly:** a client can set `X-Forwarded-For` to any IP and get past every IP limit and IP binding.
  - **Behind a CDN plus a load balancer:** voters may appear as a few edge IPs, which would mean mass false refusals by the IP limits.
- **Dead configuration:**
  - `app/Http/Middleware/TrustProxies.php` is never registered;
  - `VOTING_TRUST_PROXIES` has no effect (both branches return `$request->ip()`, `ValidateVotingIp.php:132-143`);
  - `getClientIp(true)` passes an argument Symfony ignores.

## Acceptance
- [ ] **Document the production topology** (direct, reverse proxy, CDN) with ops.
- [ ] **Replace `'*'`** with explicit proxy CIDRs from configuration, plus the correct header set.
- [ ] **Remove** the unused `TrustProxies` class and `VOTING_TRUST_PROXIES`.
- [ ] **A test:** a spoofed `X-Forwarded-For` from an untrusted address does not change the IP the limits see.

## Related
`PBDIGIT-285` · `PBDIGIT-286` · `PBDIGIT-288`.
