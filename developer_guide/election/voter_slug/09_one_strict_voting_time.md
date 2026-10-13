# 09: One strict voting time for every reader (PBDIGIT-274 S6)

**Story:** `PBDIGIT-274` S6 · **Code:** `app/Services/Voting/VotingDuration.php`, `config/voting.php`, and the readers R2–R8 (below). **Tests:** `tests/Unit/Services/Voting/DurationReaderRegressionTest.php` (database-free), `tests/Isolated/Pbdigit274/Database/DurationReaderRegressionDatabaseTest.php`.

## Purpose
`VOTING_TIME_IN_MINUTES` controls the link lifetime and the code and voting windows.
- **Before S6:** `config/voting.php` cast it with `(int)`, so `20min` silently became 20 and `abc` became 0. The readers then used the value unchecked.
- **Decision O-10 = (B):** every reader validates strictly, and an invalid value **fails closed** (HTTP 503).
- **O-9 = (a):** a missing variable means 30.

## How to read the voting time
```php
use App\Services\Voting\VotingDuration;

$minutes = VotingDuration::minutes();   // int > 0, or VoterSlugLifetimeMisconfigured (503)
```
`VotingDuration` adds no second rule. It calls the existing strict rule and logs the failure without the value:
```php
public static function minutes(): int
{
    try {
        return VoterSlugLifetime::fromConfig(config('voting.time_in_minutes'))->minutes();
    } catch (VoterSlugLifetimeMisconfigured $e) {
        Log::critical('voter_slug_lifetime_misconfigured', $e->getContext());

        throw $e;
    }
}
```
`config/voting.php` now keeps the raw value: `'time_in_minutes' => env('VOTING_TIME_IN_MINUTES', 30)`.

## The readers
| # | Site | Now |
|---|---|---|
| R2 | `VoterSlugService::generateSlugForUser` | `now()->addMinutes(VotingDuration::minutes())` |
| R3 | default `expires_at` in `VoterSlug` / `DemoVoterSlug` `creating`, and the demo branch of `getOrCreateSlug` | the configured time (before: the undefined key `voting.slug_expiration_minutes`, i.e. always 30) |
| R4 / R5 | `CodeController` / `DemoCodeController` constructor | `$this->votingTimeInMinutes = VotingDuration::minutes()`. An invalid value makes every route of the controller fail closed |
| R6 / R7 / R8 | `CheckVotingWindow`, `CodeVerificationTrait`, `VoteController` / `DemoVoteController` (4 sites each), `DemoCode::isExpired` (both models) | `$code->voting_time_in_minutes ?? VotingDuration::minutes()` |

R1 (the guard) and the issuer already used `VoterSlugLifetime::fromConfig` since S1 / S3.

## Design decisions
- **The fallback semantics are kept.** The configuration is read only where it was read before (`column ?? …`), so a stored code keeps its own window.
- **`send_second_voting_code`** (real and demo) reads the time **before** its `catch (\Exception)`. Inside it, the 503 would have become "Code generation failed".

## Testing
```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Services/Voting/DurationReaderRegressionTest.php
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/DurationReaderRegressionDatabaseTest.php
```
- **`test_valid_value_unchanged_*`** are characterisation tests. They were run against the code **before** S6 and again after: a valid value keeps its meaning.
- **`test_invalid_value_fails_closed_*`** were RED before S6.
- **The configured value is 20, not the default 30**, so that a reader ignoring the configuration is visible.

## Pitfalls
- **Never read `config('voting.time_in_minutes')` directly**, and never cast it.
- **`codes.voting_time_in_minutes` and `demo_codes.voting_time_in_minutes` are NOT NULL (default 30).** So the configuration fallback in R6 never runs for a stored code. That is why there is no R6 fail-closed test.
- **Several R8 sites are dead code** (unrouted `second_submission` and its helpers; the trait method overridden in `DemoVoteController`): PBDIGIT-278 item 7.

**Traceability:** `PBDIGIT-274` S6: code `ac2a4736c`; tests `a73c80332`; evidence `bfe54c23d`.
