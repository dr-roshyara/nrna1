# 10: What the voter sees for an expired, foreign or closed link (PBDIGIT-274 S7)

**Story:** `PBDIGIT-274` S7 · **Code:**
- `app/Http/Middleware/VerifyVoterSlug.php` (V-3, V-4)
- `app/Http/Middleware/ValidateVoterSlugWindow.php`
- `app/Exceptions/Voting/ElectionVotingClosedException.php`
- `bootstrap/app.php` (renderers)

**Tests:**
- `tests/Isolated/Pbdigit274/Database/VoterSlugExpiryRoutingTest.php`
- `tests/Unit/Http/Middleware/VerifyVoterSlugTest.php` (database-free)

## Purpose
| Situation | Web | JSON |
|---|---|---|
| the owner opens an **expired** link | redirect to `elections.show` (fallback `organisations.show`, then `dashboard`) with "Your voting session has expired, please start again."; the row is unchanged | 403 |
| someone opens **another voter's** link | **404 "Voting link not found."**, identical to an unknown link (O-5 = b) | 404 |
| the election has **ended** | redirect to `elections.show` with "Voting in this election is closed."; no restart offered (O-2) | 403 |

## How it works
- **V-3 (`VerifyVoterSlug`)** logs `reason = not_owner` with ids only, then answers like the `{vslug}` binding:
  ```php
  if ((string) $voterSlug->user_id !== (string) auth()->id()) {
      Log::warning('[VerifyVoterSlug] voting link of another voter', $context + ['reason' => 'not_owner']);
      abort(404, 'Voting link not found.');
  }
  ```
- **V-4** throws `ExpiredVoterSlugException` with `election_id` and `organisation_id` in its context. The owner is already established by V-3, so the redirect target is the owner's election.
- **`ValidateVoterSlugWindow`** throws `ElectionVotingClosedException` (403) when the election's `end_date` (end of day) has passed.
- **`bootstrap/app.php`** registers two renderables **before** the generic `VotingException` one. Both use the `$votingSessionRedirect` closure:
  ```php
  $exceptions->renderable(function (\App\Exceptions\Voting\ExpiredVoterSlugException $e, Request $request) use ($votingSessionRedirect) {
      if ($request->wantsJson() || $request->isJson()) {
          return null;   // falls through to the generic VotingException renderer
      }

      return $votingSessionRedirect($e, 'Your voting session has expired, please start again.');
  });
  ```
  The closure finds the election with `withoutGlobalScope('tenant')`, so a soft-deleted election is not found and the organisation page is used. It logs ids only, never a URL.

## Testing
```bash
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/VoterSlugExpiryRoutingTest.php
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Http/Middleware/VerifyVoterSlugTest.php
```
`test_someone_elses_link_answers_exactly_like_an_unknown_link` compares the foreign-link response with an unknown-link response. Two `VerifyVoterSlugTest` tests changed because of the O-5 decision: they now expect `NotFoundHttpException`.

## Pitfalls
- **Guests still see a difference:** the binding runs before authentication, so a guest gets 404 for an unknown link but a login redirect for an existing one. Tracked in PBDIGIT-275.
- **A new `VotingException` that should redirect the voter** needs its own renderable registered before the generic one, as above.
- **Default-suite tests updated but not run** (they need `nrna_test`): `VerifyVoterSlugRequestPathTest` (foreign link → 404) and `ConstitutionalVotingProtectionTest` (expired link → redirect; the old 410 predated S1).

**Traceability:** `PBDIGIT-274` S7: code `0aaadb31c`; tests `2ef37274d`; evidence `04c70c4ba`.
