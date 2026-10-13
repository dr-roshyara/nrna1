# 14: No raw voting link in logs (PBDIGIT-274 S9c, O-7)

**Story:** `PBDIGIT-274` S9c · **Code:** 12 files: `bootstrap/app.php`, the code, vote, voter-slug, demo-code and demo-vote controllers, `ElectionManagementController`, five middleware and `EnsuresVoterMembership` (list in the test's `FILES`). **Tests:** `tests/Architecture/NoRawVoterSlugInLogsTest.php` (database-free), `tests/Isolated/Pbdigit274/Database/RawSlugLoggingTest.php`.

## Why
A voting link `/v/{vslug}/…` is a **bearer secret**: whoever has it can act in the voting flow. A link written to a log is readable by anyone with log access, for as long as the logs are kept.

## The rule
In a log message or context:
| Never | Instead |
|---|---|
| `$voterSlug->slug`, `$vslug->slug`, `route('vslug')`, `$request->route()->parameters()` | `'slug_id' => $voterSlug?->id` |
| `$request->url()`, `fullUrl()`, `path()`, `getRequestUri()` | `'route' => $request->route()?->getName()` |
| `$redirect->getTargetUrl()`, a `$…Url` variable, `route('slug.…', ['vslug' => …])` | the route name (e.g. `'redirect_route' => 'slug.code.agreement'`), or nothing |
| a session value that holds the link (e.g. `session('last_created_voter_slug')`) | the slug's id |

Example from `CodeController::store`:
```php
// PBDIGIT-274 S9c: route name and slug id only; the agreement URL carries the voting link.
Log::info('🟡 [STORE] About to return redirect', [
    'redirect_route' => $voterSlug ? 'slug.code.agreement' : 'code.agreement',
    'slug_id' => $voterSlug?->id,
]);
```

**Not logs:** Inertia props and redirects **must** carry the link to build it (e.g. `'slug' => $voterSlug->slug` in `CodeController`'s page props). Don't change those.

## The two tests
- **`NoRawVoterSlugInLogsTest` (static).**
  - In the listed files, every `Log::…(` / `Log::channel(…)->…(` / `logger(` / `->log(` call is read with balanced parentheses, so multi-line arrays are included. The call must not match the forbidden patterns.
  - Separately, no `'url' => $request->url()`-style array entry may appear anywhere in those files. This catches context arrays built before the log call, like `ValidateVotingIp`'s `$divergence`, which also feeds a telemetry file.
  - **Adding a file that handles voting links?** Add it to `FILES`.
- **`RawSlugLoggingTest` (behaviour).** It captures every `MessageLogged` record (all channels) while the real paths run:
  - a whole real voter journey: start → code entry → code → agreement → ballot → verification → cast;
  - the voting-exception renderer;
  - a refused voter;
  - the demo start.

  The link string must appear in no message or serialised context, nor in the JSON error body.

## Testing
```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/NoRawVoterSlugInLogsTest.php
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/RawSlugLoggingTest.php
```
Before S9c: 87 static hits; the link was in 14 / 2 / 1 / 3 log records on the four paths. After: none.

## Pitfalls
- **The static test is limited by design.** It cannot see a link hidden behind an unusual variable name or a session value; the scan missed `session('last_created_voter_slug')`. The behaviour test is the real proof for the paths it drives.
- **The journey test sets `app.max_use_clientIP = 7`.** `MAX_USE_IP_ADDRESS` has no default, and unset it refuses every real voter at verification (PBDIGIT-278 item 8).
- **Dead code still logs links** (PBDIGIT-278 item 9: delete it). Other secrets in logs, a `session_id` and a demo `voting_code`, are item 10.
- **The binding's `slug_prefix`** (10 characters, logged only when no link matches) was reviewed and left: it is a prefix of a non-existent link.
- **PBDIGIT-276** (the renderer URL) stays open until the PO records the outcome at 274's closure.

**Traceability:** `PBDIGIT-274` S9c: code `712fa9250`; tests `9bce001ae`; inventory and evidence `1c5df4496`, `4b78b7fb3`.
