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

---

## Implementation (2026-10-13, Human PO: "start S9c"; decision: redact live sites only)

**Rule applied at every site:**
- the slug value becomes the slug's database id (`slug_id` / `voter_slug_id`);
- a request URL, path or full URL becomes the route name;
- a redirect target URL becomes the route name, or is dropped;
- route parameters, `route('vslug')` and voting-URL variables are removed.

Each site was read, then edited deliberately (no bulk replace). In `CodeController`, two lines with identical text (218 and 519) are Inertia page props that must carry the link to build links; they are unchanged.

**Files changed:**
- **Bootstrap and controllers:**
  - `bootstrap/app.php` (renderer `url` → `route`);
  - `CodeController` (11 sites);
  - `VoteController` (5: `url`, `target_url`, `slug` in the post-commit block, and two non-slug redirect logs);
  - `VoterSlugController` (9);
  - `Election/ElectionManagementController` (3, incl. `session('last_created_voter_slug')`, which holds the link and was missed by the scan);
  - `Demo/DemoCodeController` (13);
  - `Demo/DemoVoteController` (12, incl. the dead line 1817 in a live file).
- **Middleware and traits:**
  - `Middleware/EnsureVoterStepOrder`;
  - `Middleware/ValidateVotingIp` (4; the `$divergence` array also feeds the `DivergenceTelemetryStore` file);
  - `Middleware/EnsureRealVoteOrganisation` (2);
  - `Middleware/CheckVotingWindow`;
  - `Traits/EnsuresVoterMembership`.

## Tests
- **`tests/Architecture/NoRawVoterSlugInLogsTest.php`** (static, database-free). In the 12 files, no log call (balanced parentheses, multi-line, `Log::channel()` included) may carry any of:
  - a voter-slug value, `route('vslug')` or route parameters;
  - `url()` / `fullUrl()` / `path()` / `getRequestUri()`, or `getTargetUrl()`;
  - a `$…Url` variable, a `route('slug.…')` URL or a `'vslug' =>` key.

  It also bans a `'url' => $request->url()`-style entry anywhere in those files.
- **`tests/Isolated/Pbdigit274/Database/RawSlugLoggingTest.php`.** Every `MessageLogged` record is captured (all channels) while the real paths run:
  - **a whole real voter journey over HTTP:** start → code entry → code → agreement → ballot → verification → cast, ending in a committed vote;
  - **the voting-exception renderer** (JSON and web);
  - **a voter refused at code entry;**
  - **the demo start and demo code entry.**

  The raw link must occur in no message and no serialised context, nor in the JSON error body.

## Runs
| # | File | Result |
|---|---|---|
| 01 | `01-static-red.out` | static guard against the code before S9c: **87 hits** (RED) |
| 02 | `02-dynamic-red.out` | behaviour tests before S9c: **4 / 4 RED**. The link was in 14 of 247 records (real flow), 2 of 13 (renderer), 1 of 22 (refused), 3 of 38 (demo). No link appears in this output (checked) |
| 03 | `03-static-green.out` | static guard after S9c: **OK** |
| 04 | `04-dynamic-green.out` | behaviour tests after S9c: **4 / 32**, with the flow trail `start=302 code.create=200 code.store=302 agreement=200 agreement.submit=302 vote.create=200 vote.submit=302 vote.verify=200 vote.store=302` |
| 05 | `05-isolated-all.out` | the whole isolated PBDIGIT-274 suite: **94 / 490** |
| 06 | `06-database-free.out` | `tests/Unit/Services/Voting`, `VerifyVoterSlugTest` and both architecture tests (`CACHE_STORE=array`, PBDIGIT-278 item 5): **107 / 549** |

## Found while implementing (PBDIGIT-278, not fixed here)
- **Item 8:** `MAX_USE_IP_ADDRESS` has no default, so `0 >= null` refuses every real voter at verification, and the refusal renders the missing view `components.error_message`. The behaviour test sets the limit to 7, as `tests/Feature/Characterization/Phase1/CastFixtures.php` does.
- **Item 9:** the 20 dead-code sites.
- **Item 10:** `session_id` and a demo `voting_code` in logs (not voting links).
- **Renderer status:** the renderer answers an organisation mismatch with HTTP 500 on JSON (known; PBDIGIT-275).
- **PBDIGIT-276:** T1 is its item 1. **It stays OPEN** until the PO records the outcome at 274's closure (plan section 2.1a).
