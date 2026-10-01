# Push B / Greenfield Core — status verification (2026-10-01)

| | |
|---|---|
| **Kind** | ⚠ authority: generated. Engineering evidence. **No decision, no authorisation, no code or test changed.** |
| **Trigger** | A status narrative stating that PB-003 Inbox is "~20%" and PB-004…PB-007 are "0%" was verified against the repository |
| **Prepared by** | `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared) |
| **Placement** | `docs/implementation/` = the engineering programme backlog (`PB-nnn`, `ENG-nnn`), distinct from the product backlog (`PBDIGIT-nn`, `docs/publicdigit/backlog/README.md` §"ID conventions") |

## 1. Verdict on the narrative

**The narrative is stale.** It matches the programme state of early July 2026, not today's.

| Narrative claim | Repository reality | Evidence |
|---|---|---|
| Contestation: Challenge aggregate implemented | ✅ | `app/Contexts/Contestation/` (Challenge, reactions, `ChallengeResolved`) |
| Adjudication: Determination + VOs + persistence | ✅ | `app/Contexts/Adjudication/`; `DeterminationIssued` producer |
| PB-001 Event Registry, PB-002 Relay Registry done | ✅ | `EventHydratorRegistryWiringTest`, `OutboxEventProcessorRegistryTest` present |
| **PB-003 Inbox "~20%, current task"** | ❌ **CERTIFIED 2026-07-07** | `app/Contexts/Shared/Infrastructure/Inbox/{Inbox, InboxEvent, InboxExecutionEngine}.php`; `InboxConsumeTest`, `InboxRedriveTest`, `InboxHandlerRegistryWiringTest`; BACKLOG.md M1 |
| **PB-004 Election Reaction "0%"** | ❌ **CLOSED 2026-07-08** | `Election/Application/DeterminationIssuedReactionHandler.php`, `Election/Domain/Events/ElectionCorrectionApplied.php`; `ElectionReactionMessagingIntegrationTest` OK (4) |
| **PB-005 Contestation Reaction "0%"** | ❌ **CLOSED 2026-07-09** | `Contestation/Application/{ChallengeResolutionReaction, ChallengeAdjudicationReaction, ResolveChallengeHandler}.php` |
| **PB-006 Integration "0%"** | ❌ **CLOSED 2026-07-10** | `CorrectionLoopIntegrationTest` OK (4 tests, 39 assertions) |
| **PB-007 Merge gate "0%"** | ❌ **CLOSED 2026-07-10**, but **the gate is red today** (§2) | `composer.json` `merge-gate` |
| EPIC-001 status | **FORMALLY CLOSED** (ARB, 2026-07-11) | `BACKLOG.md` Epics table |
| "After Push B: Election / Mandate / Vote migration → Evidence …" | ⚠️ differs from the ARB roadmap | ARB order: **EPIC-002 Evidence discovery (next, not opened)** → EPIC-003 Voting → EPIC-004 Appointment → EPIC-005 Read Models → EPIC-006 Election/Lifecycle migration |
| Engineering debt: Deptrac install, Infection driver | ✅ closed by PB-007 (F-1, F-2) — but Deptrac is **absent from this workspace** (§2) | BACKLOG.md Debt table |
| ENG-002 Shared Infrastructure PHPStan alignment | still `Designed` | BACKLOG.md |

## 2. The merge gate today (`composer merge-gate`, each step run 2026-10-01)

| Step | Result | Classification |
|---|---|---|
| 1 `phpunit --testsuite=Architecture` | **149 tests, 3 failures** (1 skipped) | legacy-election tests: `ElectionLifecycleStateConsistencyTest::test_lifecycle_engine_computes_voting_active_state_when_voting_window_is_open`, `ElectionStateMachineConsistencyTest::test_engine_uses_election_clock_service_for_voting_window`, `ConstitutionalBoundaryTest::test_code_creation_is_preparatory_allowed_during_setup` — already tracked by `PBDIGIT-123` (triage) and `PBDIGIT-156` (policy tests in the Architecture suite) |
| 2 `deptrac analyse` | **cannot run**: `vendor/qossmic/deptrac-shim/deptrac` absent | **workspace**: `composer.json` requires `qossmic/deptrac-shim ^1.0` and `composer.lock` contains it; the dev dependency is simply not installed here (`composer install` not run) → `ENG-015` |
| 3 `phpstan -c phpstan-greenfield.neon` | **22 errors**, all under `app/Contexts/Election/{Application,Domain}/OperatingCore` | **6** null-dereference in `FillCommitteeSeatHandler` = the ADR-1 absent-aggregate finding (normalization BLOCKED at its Rule-8 gate); **14** "property never read" in GREEN-5/6/7 handler/query stubs (held per the EM-IMPL-002 records); **2** genuine domain findings (`AppointmentsAwaited:26`, `RecoveryProcess:92`) → `ENG-014` |
| 4 `phpunit --testsuite=GreenfieldCore` | **412 tests, 4,616 assertions, 15 errors, 2 failures, 112 risky** | **16 of 17** are the EM-IMPL-002 RED suite (`tests/Unit/Contexts/Election/OperatingCoreApplication/*RedTest`, committed failing-first in `1f4b4c5f3`, 2026-08-17, GREEN-5/6/7 held) → `ENG-013`; **1** genuine defect: `IssuanceContextRoundTripTest::test_the_issuance_marker_persists_and_closes_the_crash_window` → `PBDIGIT-158`; risky count → existing `ENG-012` |

**The programme's own health table therefore states the opposite of today's reality.** `BACKLOG.md` "Program Health" says "`composer merge-gate` PASS · GreenfieldCore 179✔/0F" and "Architecture suite 146✔/1 skip". The gate has been red since at least the EM-IMPL-002 RED commit.

## 3. New items (written in the backlog that owns each concern)

| Seq | ID | Backlog | Item |
|---|---|---|---|
| 1 | `ENG-015` | engineering (`BACKLOG.md`) | Programme health claims a green merge gate that is red; this workspace cannot run Deptrac |
| 2 | `PBDIGIT-158` | product (`docs/publicdigit/backlog/`) | The adjudication issuance marker comes back two hours shifted after persistence |
| 3 | `ENG-013` | engineering | The merge gate cannot pass while an authorised RED-first increment is open (**decision required**) |
| 4 | `ENG-014` | engineering | Greenfield PHPStan regression: 22 errors (2 fixable now, 20 bound to blocked/held work) |

**Sequence rationale:**
- **`ENG-015` first:** recording the truth costs nothing and stops stale status from steering work.
- **`PBDIGIT-158` is independent.** It is a correctness defect in persisted legal time.
- **`ENG-013` needs an ARB decision** before the gate can be green.
- **`ENG-014`:** its 2 independent findings can follow at any time; its other 20 resolve only with ADR-1 normalization and GREEN-5/6/7, so it is bounded by `ENG-013`'s decision.

**Product stories (added 2026-10-01 by the completeness audit, `docs/publicdigit/reviews/2026-10-01-pbdigit-completeness-audit.md`):** `ENG-015` → `PBDIGIT-159` · `ENG-013` → `PBDIGIT-160` · `ENG-014` → `PBDIGIT-161` · `ENG-012` → `PBDIGIT-162`. The sequence above is unchanged.

**Already tracked, not duplicated:** the 3 Architecture failures (`PBDIGIT-123`, `PBDIGIT-156`) · the risky-test growth (`ENG-012`) · Shared Infrastructure PHPStan alignment (`ENG-002`).

**Non-actions:** no code, test, configuration or dependency changed; `composer install` was **not** run; no decision taken.
