# PBDIGIT-274 S9c: raw voting links in logs, inventory (plan section 12.2), 2026-10-13

**Status:** inventory only, read-only. **No code changed.** The redaction is S9c's implementation, after S9a's decisions.

## Method
1. **Scan** (scratch script, not committed). Every `Log::` / `\Log::` / `logger(` / `->log(` call in `app/`, `bootstrap/` and `routes/` was parsed with balanced parentheses, so multi-line arrays are included.
   - **Patterns:** `->slug`, `'slug'`, `'vslug'`, `'voter_slug'`, `route('vslug')`, `url()`, `fullUrl()`, `path()`, `getRequestUri()`, `getTargetUrl()`, `route(`, `$slug`, `$vslug`, `getMessage()`.
   - **Counts:** 273 raw candidates. After dropping those that matched only `getMessage()` / `route(`, **126** remained.
2. **Classification.** Each of the 126 was classified by a read-only subagent:
   - **RAW:** a voter or demo-voter slug can be written.
   - **CONDITIONAL:** a URL that contains the slug on `/v/{vslug}` routes.
   - **NOT_IN_SCOPE:** an organisation or election slug, ids only, or not a voting route.

   It also covered the scanner's blind spots: `Log::channel(...)`, context arrays built in a variable, audit helpers, the legacy `Handler`, and exception messages.
3. **Verification by me:** three spot checks, all confirmed:
   - `bootstrap/app.php:261` (`'url' => $request->url()` in the `VotingException` renderer);
   - `app/Traits/EnsuresVoterMembership.php:49` (`fullUrl()`);
   - `EnsureVoterSlugWindow` has no reference in `app/`, `bootstrap/` or `routes/`.

   **The rest of the classification is unverified (subagent output).** Each site is re-read when it is edited.

## Result: 58 live sites to redact (52 from the scan, 6 from blind spots)
| File | Lines (log call start) |
|---|---|
| `bootstrap/app.php` | 256 (`url` at 261; this is T1's remaining leak, the old T1 line now logs ids only) |
| `Demo/DemoCodeController.php` | 58, 77, 178, 276, 286, 295, 316, 416 (T9), 429, 437, 501, 511, 567 |
| `Demo/DemoVoteController.php` | 591, 776, 1802, 1829, 1840, 2128, 3703, 3714, 3721 |
| `Election/ElectionManagementController.php` | 423 (T8), 477, 645 (T8) |
| `VoterSlugController.php` | 63, 77, 85, 161, 202, 212, 269, 329 (T6) |
| `CodeController.php` | 66, 95, 106, 248, 374, 392, 401, 427, 533 (T5), 549, 621 (T4) |
| `VoteController.php` | 486, 706 (T3's remaining leak), 1953 (T2) |
| `Middleware/EnsureVoterStepOrder.php` | 87 |
| `Middleware/ValidateVotingIp.php` | 176, 199, 232, 265, 269 (and the `DivergenceTelemetryStore` file write at 262) |
| `Middleware/EnsureRealVoteOrganisation.php` | 40, 111 (CONDITIONAL) |
| `Traits/EnsuresVoterMembership.php` | 45 |
| `Middleware/CheckVotingWindow.php` | 117 |

## Leaking, but in dead or unreachable code (20)
- `Exceptions/Handler.php` 40, 92 (T11; not registered under Laravel 11 `withExceptions`).
- `ElectionController.php` 257, 281 (T7; `startDemo` is unrouted).
- `Middleware/EnsureVoterSlugWindow.php` 27, 39, 49, 59, 70, 90, 101 (no reference).
- `Services/VoterRecoveryService.php` 47, 71 (no caller).
- `Services/VoterSlugService.php` 90 (callers build it without its constructor argument).
- `Demo/DemoVoteController.php` 1817 (an `instanceof VoterSlug` guard on demo routes).
- `Helpers/ElectionAudit.php` ~87, 136, 163, 188, 212 (T10; the functions have no callers).

## Not in scope (examples)
- Organisation and election slugs (installer jobs, `OrganisationController`, `DashboardResolver`).
- Id-only contexts (`VerifyVoterSlug`, `VoterStepTrackingService`, `VoterProgressService`, the binding's 10-character prefix on lookup failure).
- UI props that must carry the slug.
