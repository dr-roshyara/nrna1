# Election lifecycle and audit `from_state` — investigation report

**Date:** 2026-10-09 · **Commission:** `docs/publicdigit/election_management/brainstorming/20261009_2120_election_management.md` (Phases 0–2, 5) · **Author:** Claude Code session (self-declared)
**Nature:** investigation and evidence only. **No production code, test, story or ADR was changed.** A temporary probe test was created, run once and deleted (not committed).
**Evidence labels:** FACT (`path:line` or command output) · [I] inference · DECISION REQUIRED.

## A. Current-state verification (Phase 0)

| Item | Finding |
|---|---|
| HEAD | `27f57aec5` (membership work, PBDIGIT-258…264, by another session since the last election run) |
| PBDIGIT-255 (story 1) | Committed `3273ce05c` |
| PBDIGIT-256 (story 2) | **Entirely uncommitted:** 11 test files, `tests/Support/ElectionScenarioFactory.php`, `database/factories/OrganisationFactory.php`, story file, plan, one backlog README row. **Awaiting PO commit approval.** |
| Last verified evidence for PBDIGIT-256 | Clean solo runs on 2026-10-07: Election module 55 failed / 732 passed, 0 newly failing, 19 fixed; 97-suite regression 595 / 320 / 19, 0 pass → fail |
| Re-run on the current tree (2026-10-09) | **INVALID AS EVIDENCE:** 104 failures, dominated by schema errors (`uses_full_membership`, `voter_source_strategy`, `state` columns and the `organisations` table missing). The same signature as the 2026-10-07 run proven to be wiped by a concurrent `migrate:fresh` on the shared `nrna_test` database. **The PBDIGIT-256 evidence is therefore not re-confirmed on the current tree.** |
| Concurrent writers | A second session commits membership work and (very likely [I]) runs tests on the same test database. Related: **PBDIGIT-263** (raised by that session): `Tests\TestCase` claims per-test rollback it skips on PostgreSQL. This is the same root cause as story 1's `SecuritySchemaTest` finding. |
| Next story in the plan | Story 3 (transition-guard tests, category C), **after** PBDIGIT-256 is approved and committed |

**Stop condition met for commits:** PBDIGIT-256 needs PO approval **and** a clean, uncontended verification run on the current tree.

## B. Lifecycle specification (Phase 1)

Source: `app/Application/Election/Services/ElectionLifecycleEngineImpl.php:64-176`. The first matching rule wins. Clock: `now()`. The window is half-open, `[voting_starts_at, voting_ends_at)` (`ElectionClockService.php:47-55`).

| # | Derived state | Source facts / predicate | Negative / boundary notes |
|---|---|---|---|
| 1 | Suspended | `suspended_at ≠ null` | Overrides everything, including results |
| 2 | Archived | `archived_at ≠ null` | — |
| 3 | ResultsPublished | `results_published_at ≠ null` | — |
| 4 | Counting | `now ≥ voting_ends_at` ∧ `voting_locked` ∧ `approved_at ≠ null` ∧ admin ∧ nomination completed | If the window ended **without** legitimacy: logged "constitutional anomaly", falls through (see I-3) |
| 5 | VotingActive | `voting_locked` ∧ `starts ≤ now < ends` ∧ ∃ approved candidacy (EM-VOT-002, PBDIGIT-64) | **Does not check approval or completed setup** (see I-2) |
| 6 | ReadyForVoting | admin ∧ nomination completed ∧ both window ends set | Also reached when the window is open but voting was not opened, or no approved candidate |
| 6b | SetupNomination | admin ∧ nomination completed ∧ a window end missing | — |
| 7 | SetupNomination / SetupAdministration | admin ∧ ¬nomination; `SetupAdministration` only while the nomination window is pending | — |
| 8 | SetupAdministration | `setup_started_at ≠ null` ∧ ¬admin | — |
| 9 | Approved | `approved_at ≠ null` ∧ `setup_started_at = null` | — |
| 10 | SubmittedForApproval | submitted ∧ ¬approved ∧ ¬rejected | — |
| 11 | Rejected | rejected ∧ ¬approved | — |
| 12 | Draft | ¬approved ∧ ¬submitted ∧ ¬rejected | Otherwise `InvalidElectionStateException` |

**Invariant observations (formal reasoning, FACT unless marked):**
- **I-1 (consistent boundaries):** the VotingActive window ends exactly where Counting begins (`now < ends` vs `now ≥ ends`). There is no gap or overlap. *Probe-verified at `ends − 1 s` and `ends`.*
- **I-2 (asymmetric legitimacy):** Counting verifies setup legitimacy; VotingActive trusts `voting_locked` alone. [I] It is sound **only if** `voting_locked` can be set solely by guarded paths (`open_voting` side effect; `enforceVotingLock`). A direct write (fixture, repair script, `ActivateElectionCommand`) would derive VotingActive for an unapproved election. *Candidate fitness test:* `voting_locked` writers ⊆ {guarded transitions}.
- **I-3 (post-window limbo):** window ended ∧ ¬`voting_locked` derives **ReadyForVoting forever**, which says "ready" after voting can no longer happen. The engine logs it as an anomaly; there is no state for "voting window missed". **DECISION REQUIRED** (cf. PBDIGIT-72 "ELECTION DISCONTINUED").
- **I-4 (EM-VOT-002):** only proven when voting is otherwise open. PBDIGIT-256 fixed the vacuous negative tests by opening voting in the helper (`EmVot002ApprovedCandidateBeforeVotingTest`).

**Coverage gaps (proposed tests, not written):**
- property / boundary tests per rule, varying one fact at a time;
- an explicit I-3 characterization;
- an I-2 writer-set fitness test.

## C. Audit `from_state` discrepancy (Phase 2)

### Call path (FACT)
| Concern | Component |
|---|---|
| Derives the current state | `ElectionLifecycleEngineImpl::getState()` via `ElectionLifecycle::of()` |
| Authorises a transition | `ConstitutionalTransitionGuard::assertAllowed($election, $action, $snapshot)`, using the **derived** snapshot (`Election.php:1709-1714`) |
| Audit `from_state` | `$freshElection->current_state` (`Election.php:1702`) → `getCurrentStateAttribute()` → **persisted `state` column** (`Election.php:940-945`) |
| Audit `to_state` | `ElectionConstitution::getTargetStateForAction()` (`Election.php:1705`) |
| Persists the state | `updateQuietly(['state' => $toState])` (`Election.php:1735`); other writers: `BackfillElectionState`, `ActivateElectionCommand.php:30` (writes non-constitutional `'administration'`) |
| Clock-driven changes | **None persisted.** `elections:process-auto-transitions` exists but is **not scheduled** (`routes/console.php`) |
| Other readers of the persisted state | `ElectionStateMachine.php:31` |

### Reproduction (controlled clock; `ElectionScenarioFactory::votingActiveWithChief()`; authenticated chief)

```
1 before deadline                derived=voting_active  persisted=voting_active  transitions=0
2 one second before deadline     derived=voting_active  persisted=voting_active  transitions=0
3 exactly at deadline            derived=counting       persisted=voting_active  transitions=0
4 one hour after deadline        derived=counting       persisted=voting_active  transitions=0
5 close_voting after deadline: REFUSED — Action 'close_voting' not allowed in state 'counting'. Allowed states: voting_active
7 publish_results: ACCEPTED
8 after publish_results          derived=results_published persisted=results_published transitions=1
  audit record: from_state=voting_active to_state=results_published trigger=manual
```

**Verdict: CONFIRMED.**
1. The audit trail records `voting_active → results_published`, a transition **absent from the constitution** (`publish_results` is allowed only from `counting`, `ElectionConstitution.php:120`).
2. The Counting phase never appears in the audit.
3. After the deadline, `close_voting` can no longer be used, so time is the only way voting ends when the chief does not close early.

### Intended semantics (answers)
- **What does a record describe?** Currently an **explicit command** (`trigger=manual`) with an attempted before/after. [I] Its `from_state` claims to be the state *at command time*. It is not, whenever time advanced the lifecycle.
- **Is a time-derived change auditable?** Not ruled in any ADR found. **DECISION REQUIRED.**
- **Can `from_state` be truthful when the previous state was never persisted?** Yes, if it is taken from the **derived** state at command time (which the Guard already uses), not from the persisted column.
- **Two authorities?** Yes: authorisation uses the derived state; the audit uses the persisted column. This contradicts **ADR_20260807_1500** (ACCEPTED): *"`state` … compatibility artifacts … decided on by nothing"*; *"Definition of Done is zero production readers"*.

### Design options (not chosen; consequences only)
| Option | Invariant gained | Failure modes / costs |
|---|---|---|
| **O1:** `from_state` = the Guard's derived snapshot state | The audit records the state the authorisation was based on; one authority (ADR-conform) | Historic records keep their old meaning. The Counting interval is still not recorded as its own event. Smallest change: one line + tests. |
| **O2:** persist explicit transitions for clock changes (scheduled `elections:process-auto-transitions`) | Every state appears in the audit | Introduces a scheduler and a second writer of the legacy column, which the ADR rejected (Option A); timing races at the deadline; job-failure semantics |
| **O3:** distinguish commanded transitions from time-derived observations (e.g. a `trigger=observed` record written lazily when a command first sees a derived change) | Truthful, complete trail without a scheduler | New audit semantics need a decision; idempotency under concurrent commands |

O1 is compatible with O3 (O3 can be added later); O2 conflicts with the accepted ADR.

### Owner
**PBDIGIT-58** "Complete the legacy election-state migration" (strategy approved; remaining capabilities awaiting authorisation) under **ADR_20260807_1500**. The audit `from_state` read is an unmigrated production reader of the legacy `state` field.
- **Recommendation:** record it as a new capability in PBDIGIT-58's inventory, rather than a new story or epic.
- `ElectionStateMachine.php:31` and `ActivateElectionCommand.php:30` belong to the same inventory.

## D. Risk assessment
| Area | Impact |
|---|---|
| Election correctness | Decisions are correct (they use the derived state); the **record** of them is wrong |
| Audit integrity / legal traceability | **High:** the trail shows a constitutionally impossible transition and omits counting; in a dispute (Contestation) this evidence is misleading |
| Anonymity / tenant isolation | Not affected (no voter data involved; election-scoped) |
| Concurrency / timing | The deadline instant is consistent (I-1). O2 would add deadline races; O1 / O3 don't |
| Test environment | **The shared `nrna_test` database is used concurrently by another session**: full-module evidence is unreliable until runs are serialised or isolated (cf. PBDIGIT-263) |

## E. Next-step decision (recommendation)
1. **Unblock evidence first:** serialise test runs between sessions (or give each session its own test database). Then re-verify PBDIGIT-256 on the current tree and commit it (PO approval pending).
2. **Then the audit defect, as a PBDIGIT-58 capability:** RED test = the reproduction above as an assertion (`from_state` must equal the state the Guard authorised against). GREEN = **O1**.
   - **Prerequisite:** PO authorisation of that PBDIGIT-58 slice, plus a PO / ARB ruling on whether time-derived changes must also be recorded (O3, separate).
   - **Acceptance criteria:**
     - (a) after the deadline, `publish_results` records `counting → results_published`;
     - (b) before the deadline, recorded transitions are unchanged;
     - (c) no new writer of the `state` column;
     - (d) the 97-suite regression has no pass → fail.
3. **Plan story 3 continues afterwards.** Separate decisions to raise: I-2 (writer-set fitness test) and I-3 (post-window limbo state).

**Traceability:** ADR_20260807_1500 · PBDIGIT-48 · PBDIGIT-58 · PBDIGIT-64 (EM-VOT-002) · PBDIGIT-256 · PBDIGIT-263 · PBDIGIT-72 · `Election.php:940-945,1702-1735` · `ElectionLifecycleEngineImpl.php:64-176` · `ElectionClockService.php:47-55` · `ElectionConstitution.php:111-123` · `routes/console.php`.
