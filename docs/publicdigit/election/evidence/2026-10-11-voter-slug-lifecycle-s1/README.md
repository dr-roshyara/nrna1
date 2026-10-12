# PBDIGIT-274, slice S1 (database-free): evidence, 2026-10-11

**Authority:** Human PO / ARB, 2026-10-11, in session:
- plan approved (G2), rev 10 + R11-1;
- decisions O-6 (B), O-6b (i), O-7 (B), O-9 (a), O-10 (B), O-11 (a), O-12 (a), O-13 (a), O-14 (a), O-15 (a);
- **only S1 authorised.**

Outputs (`.out`) and exit codes (`.exit`) are copied unchanged. **Every run is database-free.** No `.env` file was read; no migration; nothing committed.

| Step | Files | Result | Status |
|---|---|---|---|
| S0 baseline | `00-baseline-verifyvoterslug` | 31 tests, 328 assertions, exit 0 | executed, passed |
| S1-1 RED lifetime | `01-red-lifetime` | exit 2: 32 **errors** (class not found). A deviation: errors, not assertion failures, because the class didn't exist yet | executed |
| S1-2 GREEN lifetime | `02-green-lifetime` | 32 tests, 70 assertions, exit 0 | executed, passed |
| S1-3 RED guard | `03-red-guard` | exit 1: **3 assertion failures** (status-expired → Expired; hook-marked expired and inactive → Expired; misconfiguration → 503 exception). The new "inactive, not expired stays Invalid" case passed at once (characterisation) | executed |
| S1-4 GREEN guard | `04-green-guard` | 34 tests, 339 assertions, exit 0 | executed, passed |
| S1-5 RED route lookup | `05-red-route-lookup` | exit 2: 6 **errors** (class not found). The same deviation as S1-1 | executed |
| S1-6 GREEN route lookup | `06-green-route-lookup` | 6 tests, 15 assertions, exit 0 | executed, passed |
| S1 regression | `07-regression-s1` | 72 tests, 424 assertions, exit 0 | executed, passed |

Every run prints "OK, but there were issues! … Deprecations: 3". These are PHP 8.5 notices about `PDO::MYSQL_ATTR_*` constants in vendor framework config, not project code.

**What S1 implements:**
- `App\Services\Voting\VoterSlugLifetime`: the single lifetime rule; strict configuration per O-10 B; a missing key fails closed; the env default 30 comes from `config/voting.php` per O-9 a;
- `App\Exceptions\Voting\VoterSlugLifetimeMisconfigured` (503);
- `VerifyVoterSlug`: delegates to the rule; order V-5a → **V-4** → V-5b; misconfiguration → a critical log with reason code and type only;
- `App\Services\Voting\VoterSlugRouteLookup`: no model events during the lookup; only the `'tenant'` scope removed; SoftDeletes kept;
- the `{vslug}` binding uses the lookup and **no longer evaluates expiry**.

**Decision-driven test change:** `VerifyVoterSlugTest::test_a_slug_with_expired_status_is_rejected_even_when_still_marked_active` now expects `ExpiredVoterSlugException` (was `InvalidVoterSlugException`). This follows the PO decision that an expired session is restartable for its owner, and guard order V-4 before V-5b.

**Not done in S1 (gated, as planned):**
- `config/voting.php`'s `(int)` cast and readers R4–R8 (S6, behind the characterisation precondition);
- the expiry / closure redirects (S7);
- the issuer and migration (S2–S5);
- every database test.

**Known follow-ups for later slices, recorded rather than fixed:**
1. **Stale expectations in tests written but not run (database).** `ConstitutionalVotingProtectionTest::test_expired_slug_is_rejected_at_code_creation` still expects the binding's **410**. Since S1 the binding no longer evaluates expiry; the guard raises `ExpiredVoterSlugException`. It is to be updated in S7 together with the redirect target. `VerifyVoterSlugRequestPathTest`'s comment about the binding's strict `isPast()` is outdated in the same way.
2. **Another O-7 log site** found while editing the binding: `RouteServiceProvider` logs `'slug_prefix' => substr($value, 0, 10)` (the first 10 characters of the voting link) on a not-found lookup. Added to the section 12.2 classification; not changed in S1.
