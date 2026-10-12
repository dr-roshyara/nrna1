# PBDIGIT-274 S4 (second cut): VoterSlugController start / restart through the issuer, 2026-10-13

**Authority:** Human PO ("yes": fix the entry-point cleanup, then the rest of S4).

**Scope:** `pbdigit274_isolated_test` only (committing test base, no migration). No password appears in this folder.

**Change (`app/Http/Controllers/VoterSlugController.php`):** when the session's selected election is a **real** election, `start()` (`GET /voter/start`) and `restart()` (`POST /voter/restart`) go straight to `VoterSlugIssuer::fresh()->issue()`. That removes, for real elections:
- the deactivation of "an active link" across **all** elections before anything was decided (plan 10.3);
- the legacy user-flag check `VotingSecurityService::canIssueVotingSlug` (`is_voter`, `can_vote`, `has_voted`), which the plan rules out ("never legacy flags"); the issuer checks fresh entitlement instead;
- `getOrCreateSlug`.

Demo elections and the case without a selected election keep the legacy path unchanged (D1).

**Behaviour changes for real elections:**
- `restart` keeps a **valid** link (no replacement while valid, acceptance 7) and replaces an expired one;
- a voter who is entitled but lacks the legacy flags can now start;
- a code row with `has_voted` refuses a link.

| # | File | Result |
|---|---|---|
| 01 | `01-red.out` | RED: with a real election in the session, `voter.start` issued **no** link (it redirected before issuing). "No link after voting" passed for the wrong reason |
| 02 | `02-green.out` | **entry points 9 tests / 47 assertions**, issuer 18 / 77, G6 4 / 56; all exit 0 |

## Admin and CLI recovery (PO decision 2026-10-13: "add election, use issuer")

**Changes:**
- `Admin/VotingSecurityController::generateRecoverySlug`: the route is now `POST admin/voting-security/recovery/{user}/{election}`; the election is loaded explicitly by id (no model binding for a write).
- `election:recover-voter {user_id} --election=<id>` (required).

Both, for **real** elections only, go through `VoterSlugIssuer::fresh()`:
- a valid link is returned unchanged;
- an expired or deactivated one is replaced, with history kept;
- after voting, or without entitlement, no link.

Removed: legacy user flags, deactivating all of the voter's links, `secureSlugGeneration`, the `step_meta` recovery annotation. Instead the log records ids and the outcome only, **no raw link** (the old code logged it, an O-7 site). Demo or unknown elections are refused. The admin middleware (`committee.member`) is unchanged.

| # | File | Result |
|---|---|---|
| 03 | `03-red-admin-cli.out` | RED: 4 failures with the expected causes (`--election` missing; the old admin path did not replace and returned no URL). 3 tests passed for the wrong reason (the legacy-flag refusal) |
| 04 | `04-green-admin-cli.out` | **recovery 7 tests / 30 assertions**, entry points 9 / 47, issuer 18 / 77, G6 4 / 56; all exit 0 |

**Consequences outside this change (not run, `nrna_test` forbidden):**
- **`PBDIGIT-215`:** its suggested fix (entitlement via `VotingEntitlementQuery`, atomic, no missing method) is what this change implements. Closing it is the PO's decision.
- **`tests/Feature/AdminRecoverySlugTest.php`** asserts the old contract (old URL, legacy flags and messages, `step_meta`, deactivation). Per PBDIGIT-215 the old gate refused everyone, so it was most likely already failing. Now superseded by `VoterSlugRecoveryPathsTest`.
- **`LegacyVotingFlagsBoundaryTest::test_a_recovery_request_cannot_issue_a_credential_to_a_voter_who_is_not_entitled`** uses the old route without `election` and expects a not-entitled voter's credential to be left untouched. Plan decision O-3 (PO) says "refuse **and retire**", which the issuer does. **Conflict to be decided by the PO.**
- **`resources/js/ziggy.js`** (generated route map) still lists the old URI until it is regenerated. No UI calls this route.

## Default-suite tests updated (PO decisions 2026-10-13); NOT RUN here (they use `nrna_test`)
- **`tests/Feature/Election/LegacyVotingFlagsBoundaryTest.php`**, recovery case:
  - the route now carries `election`;
  - **decision-driven change (O-3 kept):** a not-entitled voter gets no new credential, and the current one is **retired** (soft-deleted, inactive). Previously the test expected it untouched.
- **`tests/Feature/AdminRecoverySlugTest.php`**, rewritten to the per-election issuer contract:
  - an expired link is replaced and kept;
  - a valid link is returned unchanged;
  - no link after voting, or without entitlement;
  - an unknown election is refused.

  The old version could not pass: `VoterSlug::create` had no `election_id` (foreign key), it relied on the dropped legacy columns, and it called the nonexistent `assertStringContains`.

Fixtures follow the existing `ElectionScenarioFactory::votingActive` pattern. Both files pass the syntax check only; **running them needs a run of the default suite on its own test database**, outside this isolated scope.
