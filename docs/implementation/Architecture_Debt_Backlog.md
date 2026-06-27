# Architecture Debt Backlog

**Status:** living · pre-existing architecture-test failures surfaced when the Architecture testsuite was registered (Push A / F-4). **None are in the greenfield Core (Adjudication/Contestation)** — all are legacy. Tracked here as a **separate workstream** so greenfield-Core evaluation stays clean. Triage/own independently.
**Date:** 2026-06-27 · Source: `php vendor/bin/phpunit --testsuite Architecture` (7 failures + 1 risky).

| ID | Failing test | Issue | Owner |
|----|--------------|-------|-------|
| **AD-001** | `CommitteeDomainPurityTest::test_domain_layer_has_no_framework_dependencies` | `Membership/Domain/Committee/CommitteeStructureId.php` and `ValueObjects/CommitteeSlug.php` import `Illuminate\` (domain-purity violation) | Membership |
| **AD-002** | `ElectionStateMachineConsistencyTest::test_frontend_contains_all_php_enum_actions` | Frontend `ElectionActions.ts` missing `apply_candidacy` action — PHP↔TS enum drift | Election/UI |
| **AD-003** | `ElectionLifecycleStateConsistencyTest::computes_counting_state` + `computes_voting_active_state` | Lifecycle engine does not compute `counting`/`voting_active` from the voting window (2 tests) | Election |
| **AD-004** | `Phase_C25_SovereigntyLeakagePreventionTest` (×3) | new `allowsAction()` call-site (`C:0`); hardcoded denial reason `'not eligible'` in `Admin/VotingSecurityController.php`; new `getStateMachine()` call-site (`C:0`) — note `C:0` may indicate a detector false-positive to verify | Election/Governance |
| **AD-005** | `VocabularyProhibitionTest::test_layer3_no_authority_vocabulary` | risky — performs no assertions (test needs a real assertion) | Governance |

## Rules
- These are **not** greenfield-Core regressions; do **not** mix into Push A/B commits.
- The greenfield fitness tests (`GreenfieldCoreArchitectureTest`) are **green** — the Architecture suite registration is valuable precisely because it surfaced this legacy debt.
- Fix under dedicated AD-### commits with the owning context; re-run `--testsuite Architecture` until green, then the full suite becomes a hard CI gate.

---
*Architecture Debt Backlog — 5 items (AD-001..AD-005, 7 failing + 1 risky tests) — all PRE-EXISTING legacy, none in greenfield Core. Separate workstream.*
