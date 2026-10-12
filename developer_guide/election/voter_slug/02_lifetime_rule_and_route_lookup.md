# 02: The voter-slug lifetime rule and the route lookup (PBDIGIT-274, slice S1)

**Story:** `PBDIGIT-274` · **Plan:** `docs/plans/20261011-2302-voter-slug-lifecycle-plan.md` (rev 10 + R11-1; approved by the Human PO / ARB on 2026-10-11; only S1 authorised) · **Evidence:** `docs/publicdigit/election/evidence/2026-10-11-voter-slug-lifecycle-s1/` · **Status:** S1 implemented with database-free tests passing; S1 code committed as `a78d7c2f2`, removed from HEAD by the unrelated `118193320`, restored byte-identical in recovery commit `4d83dffc0` (no history rewrite, no push, no database operation); not pushed; later slices still gated. No database-backed, request-path or concurrency evidence yet.

## Purpose
One place decides when a voting session (voter slug) has ended, and loading a slug from a URL must not write anything before the system knows who is asking.

## `VoterSlugLifetime` (`app/Services/Voting/VoterSlugLifetime.php`)

```php
$lifetime = VoterSlugLifetime::fromConfig(config('voting.time_in_minutes'));
$lifetime->endsAt($slug);              // min(expires_at, created_at + minutes), or null if a timestamp is missing
$lifetime->isExpired($slug, now());    // now >= end; a missing timestamp counts as expired
$lifetime->isReusable($slug, now());   // the issuer's rule: not soft-deleted, active, not voted, status not 'expired', not expired
```

- **Strict configuration (decision O-10 = B):** an `int > 0`, or a string of digits without a leading zero. `'abc'`, `'20min'`, `'5.9'`, `0`, negatives, floats and arrays throw `VoterSlugLifetimeMisconfigured` with a reason code (`missing` · `not_integer` · `non_positive`). It maps to HTTP **503** with a generic message, and has **no fallback**.
- **A missing environment variable** gets the documented default 30 from `config/voting.php` (O-9 = a). `null` reaching `fromConfig` means the config key itself is absent, so it fails closed.
- **Instants are compared,** so the clock's time zone doesn't matter.
- **Note:** `config/voting.php` still casts with `(int)`. Removing the cast and validating the other readers (code windows) is slice S6, behind characterisation tests of today's behaviour.

## Guard order in `VerifyVoterSlug` (changed by S1)
V-1 auth → **lifetime from config (misconfiguration → 503, logged as `voter_slug_lifetime_misconfigured` with reason and type only)** → V-2 type → V-3 owner → **V-5a soft-deleted → V-4 expired (status `expired` or the lifetime rule) → V-5b inactive** → V-6 election / organisation.

Why V-4 comes before V-5b: the model's `retrieved` hook marks an expired slug `is_active=false` and `status='expired'` together. The owner must get the "session expired" outcome (restartable), not "no longer active".

## `VoterSlugRouteLookup` (`app/Services/Voting/VoterSlugRouteLookup.php`) and the `{vslug}` binding
- `find($modelClass, $slug)` runs **only the lookup query** inside `Model::withoutEvents(...)`, so the `retrieved` hook can't write before ownership is checked. Laravel restores the dispatcher in `finally`, also after an exception.
- **Only the `'tenant'` global scope is removed** (registered by `BelongsToTenant`). SoftDeletes stays, so a replaced or soft-deleted slug is a 404, just like an unknown one.
- **The binding (`RouteServiceProvider`) no longer checks expiry and no longer answers 410.** The guard decides, after ownership. The redirect target for an expired session (the election start page) comes in slice S7.

## Testing (database-free)
```bash
vendor/bin/phpunit tests/Unit/Services/Voting/VoterSlugLifetimeTest.php
vendor/bin/phpunit tests/Unit/Services/Voting/VoterSlugRouteLookupTest.php
vendor/bin/phpunit tests/Unit/Http/Middleware/VerifyVoterSlugTest.php
```
The lookup tests hydrate models in memory (`Model::hydrate`, which fires `retrieved` when events are on) and only compile SQL (`toSql`). A query counter proves no query ran.

## Pitfalls
- **Don't load a slug before the guard** with events on (e.g. `VoterSlug::where(...)->first()` in middleware that runs earlier). That reintroduces writes before ownership is checked.
- **Two database tests written for PBDIGIT-273 are now out of date and will be updated in S7:**
  - `ConstitutionalVotingProtectionTest::test_expired_slug_is_rejected_at_code_creation` still expects 410;
  - `VerifyVoterSlugRequestPathTest` has a comment about the binding's `isPast()`.
- **The binding still logs a 10-character slug prefix** for unknown slugs. That is an O-7 site, handled with the log-redaction work.

**Traceability:** PBDIGIT-274 plan sections 3 (L-1 … L-3, L-13), 6, 7 · decisions O-9, O-10 · evidence `…-voter-slug-lifecycle-s1/`.
