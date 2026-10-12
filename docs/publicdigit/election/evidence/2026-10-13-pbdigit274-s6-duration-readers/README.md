# PBDIGIT-274 S6: one strict voting time for every reader, 2026-10-13

**Authority:** Human PO ("start S6").

**Decision applied:** O-10 = (B): every reader of `voting.time_in_minutes` validates it strictly, and an invalid value fails closed. O-9 = (a) is unchanged: a missing environment variable means 30.

**Scope:**
- **Test runs:** `pbdigit274_isolated_test` (RefreshDatabase base, identity check per run) plus database-free tests. No password appears in this folder.
- **Disclosure:** during S6 one **read-only** `information_schema` query (column nullability) ran against the **development database `publicdigit`** by mistake. Plain `php -r` doesn't use the isolated bootstrap. Nothing was written. The same NOT NULL fact was then shown on the isolated database by the insert error in `02-before-s6-database.out` (first attempt, since overwritten).

## Changes
| File | Before | After |
|---|---|---|
| `app/Services/Voting/VotingDuration.php` (new) | — | `minutes()`: `VoterSlugLifetime::fromConfig(config(...))->minutes()`, the existing strict rule (no second rule). Invalid → `Log::critical` (reason and type only), then `VoterSlugLifetimeMisconfigured` (503) |
| `config/voting.php` | `(int) env(...)`: `'20min'` → 20, `'5.9'` → 5, `'abc'` → 0 | raw `env(...)`; the strict rule sees the real value |
| R4 `CodeController`, R5 `DemoCodeController` (constructor, agreement page) | `(int) config(...)` | `VotingDuration::minutes()`; the agreement pages use the validated field |
| R6 `CheckVotingWindow`, R7 `CodeVerificationTrait`, R8 `VoteController` / `DemoVoteController` (4 sites each), `DemoCode::isExpired` (both models) | `column ?? config(..., 30)` | `column ?? VotingDuration::minutes()` |
| `send_second_voting_code` (real and demo) | the read sat inside `catch (\Exception)`, which would turn the 503 into "Code generation failed" | the read is moved before the `try` |
| R3 `VoterSlug` / `DemoVoterSlug` default `expires_at`, `VoterSlugService::getOrCreateSlug` (demo) | the undefined key `voting.slug_expiration_minutes`, i.e. **always 30** | the configured voting time (F1: one source) |
| R2 `VoterSlugService::generateSlugForUser` | `config(..., 30)` unvalidated | `VotingDuration::minutes()` |

**Fallback semantics kept:** the configuration is read only where it was read before (`column ?? …`). A stored code's own column still decides its window.

## Runs
| # | File | Result |
|---|---|---|
| 01 | `01-before-s6.out` | database-free, **against the code before S6**. Characterisation `test_valid_value_unchanged_{R4,R5,R7,R8×3}`: **6 / 6 pass** (A3 precondition). The S6 tests (invalid → fail closed for R3, R4, R5, R7, R8×3; R3 follows the configuration; the config file keeps the raw value): **9 RED** |
| 02 | `02-before-s6-database.out` | isolated, before S6: characterisation **R6** and **R2 pass**; R2 invalid → **RED** |
| 03 | `03-after-s6.out` | database-free after S6: **15 / 15** (the characterisation tests re-executed and still pass). The first run after the change failed the R8 real test: the read was swallowed by `send_second_voting_code`'s catch, which led to the move above |
| 04 | `04-after-s6-database.out` | isolated after S6: **4 / 4**, incl. `test_R3_demo_slug_expiry_follows_the_configured_value`, which was **written with the change and has no RED run** |
| 05 | `05-database-free-regression.out` | `tests/Unit/Services/Voting` plus `VerifyVoterSlugTest`, with `CACHE_STORE=array` (PBDIGIT-278 item 5): **105 / 533** |
| 06 | `06-isolated-all.out` | the whole isolated PBDIGIT-274 suite: **72 / 316** |

## Findings (recorded, not fixed in S6)
- **R6 has no RED test.** `codes` and `demo_codes.voting_time_in_minutes` are NOT NULL (default 30), so R6's fallback to the configuration never runs for a stored code. Its characterisation uses the column. The fail-closed path has the same `column ?? reader` shape that R7 and R8 test database-free.
- **Dead readers:**
  - `CodeVerificationTrait::hasVotingWindowExpired` is overridden in its only user;
  - `second_submission` is not routed;
  - `validateVotingEligibility`, `prepareSessionData` and `send_second_voting_code` have no live caller.

  All were changed for consistency and are recorded in PBDIGIT-278 item 7.
- **R2's callers are inert:** the broken `GET test/generate-slug` route and the unused `VoterRecoveryService`. If repaired they would bypass the issuer. Recorded in PBDIGIT-278 item 6.
- **Unchanged:** `config/election.php:30` (`voting_time_in_minutes`, default 20) is unused. The hard-coded "30-minütige" text in `CheckVotingWindow` is display only.

## Default suite
No default-suite test reads `voting.time_in_minutes`, so none was updated or run.

Checksums: `SHA256SUMS`.
