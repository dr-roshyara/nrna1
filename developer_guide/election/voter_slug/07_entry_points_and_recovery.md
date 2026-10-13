# 07: Entry points and recovery go through the issuer (PBDIGIT-274 S4)

**Story:** `PBDIGIT-274` S4 · **Code:**
- `app/Http/Controllers/ElectionVotingController.php` (`start`, `POST /elections/{slug}/start`, `elections.start`)
- `app/Http/Controllers/VoterSlugController.php` (`voter.start`, `voter.restart`)
- `app/Http/Controllers/Admin/VotingSecurityController.php` (`generateRecoverySlug`)
- `app/Console/Commands/GenerateRecoverySlug.php`

**Tests:** `tests/Isolated/Pbdigit274/Database/VoterSlugIssuanceEntryPointsTest.php`, `…/VoterSlugRecoveryPathsTest.php`.

## Purpose
Every place that gives a voter a link for a **real** election calls `VoterSlugIssuer::fresh()->issue()` (guide 06), and nothing else. Before S4, `start` rewrote the existing row in place and kept its old `created_at`. Once the 30 minutes had passed, the guard rejected the link and the voter was **locked out**. Now an expired link is replaced by a new row.

## The callers
| Caller | Refusal handling |
|---|---|
| `ElectionVotingController::start` | `ALREADY_VOTED` → `elections.show` with "You have already voted."; not entitled → `elections.show` with a reason-specific message; conflict → `elections.show` with the conflict message |
| `VoterSlugController::start` / `restart` | via `selectedRealElection()` and `issueForRealElection()`, for a real election selected in the session; demo and no selection keep the legacy path |
| admin recovery `voting-security/recovery/{user}/{election}` | per election (PO: "Add election, use issuer"); refuses after voting and for a demo or unknown election; returns the valid link unchanged, or a replacement |
| `election:recover-voter {user_id} --election=` | `--election` is required; exit code 1 after voting |
| `ElectionController::storeElection` (`election.store`) | moved in S5 (guide 08): it was the last live real-election path on the old service |

**Non-mutating refusals (plan section 10.3):** the lines that deactivated or deleted slugs **before** deciding were removed. Only the issuer writes, and only after its checks.

## How to add a new entry point
- **Call the issuer** and branch on `$issuance->outcome` / `hasLink()`. Never create, update or delete a `VoterSlug` yourself for a real election.
- **Redirect to `slug.code.create`** with `$issuance->slug->slug`. Log the slug's `id`, never the slug string (guide 14).

## Testing
```bash
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/VoterSlugIssuanceEntryPointsTest.php
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/VoterSlugRecoveryPathsTest.php
```
The tests use real entitlement (`ElectionMembership`) and an election open for voting (`voting_locked`, a voting window, an approved candidacy); the lifecycle engine needs all three. They cover: first start, reuse while valid (rows byte-identical), replacement after expiry (history kept, fresh `created_at`), refusals after voting and for an inactive membership, and both recovery paths.

## Pitfalls
- **The admin action is tested by calling the controller directly.** Its route middleware (`committee.member`) is not the subject of these tests.
- **`/vote` (`vote.direct`) is shadowed** by `vote.dashboard` (`routes/web.php:494-496`), so it is unreachable. It is not an entry point.
- **The default-suite recovery tests** (`AdminRecoverySlugTest`) follow the per-election contract, but they need `nrna_test` and **have not been run**.

**Traceability:** `PBDIGIT-274` S4:
- start: `ac84ca4dc`, tests `563fa9dd0`, evidence `79da40b48`;
- voter start/restart and recovery: `4beb3cfbb`, tests `510d53397` and `96ef5f59f`, evidence `ffded59c8`.
