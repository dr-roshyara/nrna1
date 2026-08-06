# Election Process Review — Phase 1 (Event-Based Process Discovery)

> **This is the ARCHITECTURE DISCOVERY REPORT.** It answers *"is the election architecture internally consistent?"* — **not** *"can a customer run an election today?"* For the product question, and for the Product-Gap vs Architecture-Debt split of these findings, see **`2026-08-05-election-product-readiness-overview.md`**. ⚠️ One correction from that review: this report records *"Evidence not found"* for **candidate verification**; it **does exist** (`Election/CandidacyReviewController`, `Election/CandidacyManagementController`).

**Commission:** Election Process Review, Phase 1 — Discovery Only (`architecture_legacy/election/election_process_review/20260806_1733_prompt.md`) · **Date:** 2026-08-05
**Mode:** Reviewer. No code changed · no architecture proposed · no refactoring · no ADR · no solutions.
**Baseline:** `main` @ `9158ef11` (post-PB003 certified baseline)
**Placement:** derived — `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit`
**Revision:** **3** — rev 1 contained a false negative, corrected in rev 2 with provenance preserved (§0). **Rev 3 retrofits the three closing artifacts** the method acquired later (event summary · Business Outcome · Findings Table) as an **appendix** — see §A. **The body of rev 2 is deliberately unchanged:** this report predates Phase 0 and the later structure, and rewriting it would destroy the record of how the method evolved. It is aligned by addition, not by revision.
**Evidence rule applied:** every claim cites a file/line. Where evidence was not obtained, this report says **"Evidence not found"** rather than inferring.

---

## 0. Corrections to Revision 1 (recorded, not hidden)

| # | Revision-1 claim | Status | Cause |
|---|---|---|---|
| **X-1** | *"The lifecycle emits almost no domain events… no per-step business events"* | **WITHDRAWN — FALSE.** `app/Domain/Election/Events/` contains **11 event classes**, 10 of them dispatched (§1c) | My inventory command sorted results and truncated at `head -60`; `Contexts/…` entries filled the window and `Domain/Election/Events/…` fell off the end. **I then asserted a negative from a truncated list** — the exact failure the commission's "never infer without evidence" rule targets |
| **X-2** | *"an event-driven reading of the election process is not available from the code"* | **WITHDRAWN.** An action→event dispatch map exists in `Election.php` (~`:1695`) | consequence of X-1 |
| **X-3** | Reference (intended) process absent from the review | **ADDED** (§2a) — reconstructed from repository documentation, not imagination | Revision 1 reconstructed only the *implemented* process, so Methodology-v1 Phase 4 (business vs implemented) could not be performed |

**Findings E-1, E-3, L-1, L-2, R-1…R-4, C-1 from Revision 1 stand unchanged.** One new finding (**U-1**) is added at §5b.

---

## 7. Executive Summary

The election lifecycle is governed by **one authority**: `ElectionConstitution::RULES` — 12 states (`ElectionLifecycleState`), 15 actions (`ElectionAction`), executed by `ElectionLifecycleEngineImpl`, guarded by a ten-file constitutional test family. **It also emits a real per-action event catalogue** (11 classes in `app/Domain/Election/Events/`). The core is stronger than Revision 1 reported.

Four findings dominate:

1. **U-1 (most business-significant).** **`unpublish_results` is not a constitutional action** — yet unpublishing is implemented in six places (console command, two controllers, service, policy, its own event) and is documented as a user capability in the management tutorial. A capability that *reverses published election results* sits outside the artifact that declares itself *"THE SINGLE SOURCE OF TRUTH for what actions are constitutionally allowed."*
2. **L-1.** The constitution's invariant *"All transitions defined here and NOWHERE ELSE"* is literally false in the tree: two further transition tables exist (`TransitionMatrix`, `ElectionStateMachine::TRANSITIONS`) with a different state vocabulary, and one is **live-instantiable** via `Election::getStateMachine()`. Deprecated by comment; unenforced by anything.
3. **E-1 / E-4.** Election events live in **three homes** (`app/Domain/Election/Events/`, `app/Contexts/Election/`, `app/Contexts/Elections/` — the last two differ only by pluralisation), and the *concept* `ResultsPublished` exists **twice**: the legacy class is imported but never dispatched (orphan), while the plural-context `ResultsPublishedEvent` is the live one.
4. **R-2.** The 5-step voter journey is defined **twice in one route file** (closures and slug-scoped controllers, same paths), with precedence unverified, plus a misspelled public path.

**Business-step test coverage: Evidence not found** (§6) — the DB-backed suites cannot run here, and a keyword scan matched 100 % of test files, so it establishes nothing. A named constitutional test family does exist.

---

## 1. Business Event Catalogue

**Method note:** the repository expresses the lifecycle as **states + actions**, and separately emits **events per action**. Both are catalogued; neither is inferred.

### 1a. Constitutional actions — `app/Domain/Election/Enum/ElectionAction.php` (15)

`submit_for_approval` · `auto_submit` · `approve` · `reject` · `begin_setup` · `revise_and_resubmit` · `complete_administration` · `complete_nomination` · `apply_candidacy` · `open_voting` · `close_voting` · `publish_results` · `archive` · `suspend` · `resume` — each with `allowed_states` · `allowed_roles` · `preconditions` in `ElectionConstitution::RULES`.

### 1b. Lifecycle states — `app/Domain/Election/Enum/ElectionLifecycleState.php` (12)

`draft` · `submitted_for_approval` · `approved` · `rejected` · `setup_administration` · `setup_nomination` · `ready_for_voting` · `voting_active` · `counting` · `results_published` · `archived` · `suspended`

### 1c. Domain events — `app/Domain/Election/Events/` (11 classes) + dispatch evidence

| Event class | Dispatched at | Idiom |
|---|---|---|
| `ElectionCreated` | `app/Models/Election.php:2276` | `Event::dispatch` |
| `ElectionSubmittedForApproval` | `Election.php` match arm (~`:1695`) | `event()` |
| `ElectionApproved` | match arm (~`:1695`) | `event()` |
| `ElectionRejected` | match arm (~`:1695`) | `event()` |
| `AdministrationCompleted` | `Election.php:1360` | `Event::dispatch` |
| `NominationCompleted` | `Election.php:1396` | `Event::dispatch` |
| `VotingOpened` | match arm (`:1695`) | `event()` |
| `VotingClosed` | match arm (~`:1695`) | `event()` |
| `VoterAssignedToElection` | `Contexts/Elections/Application/Handlers/AssignVoterHandler.php:97` | `Event::dispatch` |
| `BulkVotersAssignedToElection` | `…/BulkAssignVotersHandler.php:132` | `Event::dispatch` |
| `ResultsPublished` | **no dispatch site found** — imported at `Election.php:29` only | — **orphan** |

**Fallback mechanism (evidence, `Election.php` ~`:1690-1700`):** the dispatch `match` is keyed by constitutional action; actions without a specific arm fall to `default => event(new \App\Events\ElectionStateChangedEvent(...))`. So every transition emits *something*; five emit a named business event, the remainder emit the generic state-change event.

**Other election-related events (different homes):**

| Event | Home | Live? |
|---|---|---|
| `ResultsPublishedEvent` · `ResultsUnpublishedEvent` | `app/Contexts/Elections/Domain/Events/` (**plural**) | ✅ `ResultsPublishedEvent` dispatched at `ElectionManagementController.php:855` |
| `ElectionCorrectionApplied` | `app/Contexts/Election/Domain/Events/` (**singular**, PB003 certified scope) | greenfield |
| `ElectionStateChangedEvent` | `app/Events/` | ✅ default fallback |

**Finding E-1** — election events occupy three namespaces, two differing only by pluralisation (`Election` vs `Elections`).
**Finding E-4** — the *concept* "results published" exists twice: legacy `Domain\Election\Events\ResultsPublished` (imported, never dispatched) and live `Contexts\Elections\…\ResultsPublishedEvent`. A reader cannot tell which is authoritative from the code alone.
**Finding E-5** — two dispatch idioms coexist (`event()` and `Event::dispatch()`) within the same model. Consistency observation only.

---

## 2. Timeline

### 2a. Reference (intended) process — from repository documentation

Two documentary sources, both evidence:

**(i) `docs/architecture/contexts/ObservedEventInventory.md`** (2026-06-02, method: code inspection) states *"Domain Events Found: **11 Election** + 7 Security + 22 Membership + 8 Governance"* and names: `ElectionCreated`, `ElectionApproved`, `ElectionRejected`, `AdministrationCompleted`, `NominationCompleted`, `VotingOpened`, `VotingClosed`, `ResultsPublished`, `ConstitutionalDenialIssued`.
**→ Verified accurate:** the count matches the 11 classes found in §1c. **This document is a rare case of documentation that is still true.**

**(ii) `docs/election-management-tutorial.md`** — the user-perspective process, in the words a manager sees: *Start Voting* · *Stop Voting* · *Publish Results* · **Unpublish Results** (with steps: "Ensure all voting has concluded → verify accuracy in preview → Publish → confirm → results become publicly accessible").

### 2b. Implemented timeline — from `ElectionConstitution::RULES`

```
draft
  │ submit_for_approval  (chief · admin · owner)        → ElectionSubmittedForApproval
  │ auto_submit          (system; free plan ≤40 voters auto-approves)
  ▼
submitted_for_approval
  ├─ approve (super_admin · platform_admin) → approved  → ElectionApproved
  └─ reject  (super_admin · platform_admin) → rejected  → ElectionRejected
                                                │ revise_and_resubmit
approved
  │ begin_setup                                          (no HTTP route — §3)
  ▼
setup_administration ─ complete_administration ─▶ setup_nomination   → AdministrationCompleted
                                                   │ apply_candidacy
                                                   │ complete_nomination → NominationCompleted
                                                   ▼
                                              ready_for_voting
                                                   │ open_voting  (chief only) → VotingOpened
                                                   ▼
                                              voting_active
                                                   │ close_voting             → VotingClosed
                                                   ▼
                                               counting
                                                   │ publish_results (chief only) → ResultsPublishedEvent
                                                   ▼                                 (plural context)
                                           results_published ─ archive ─▶ archived
                                                   │
                                                   └ UNPUBLISH — implemented, NOT constitutional (U-1)

  (suspend / resume — cross-cutting: suspended)
```

Constitutional role rules, verbatim: *"Only committees (chief, deputy) can administer elections"* · *"Only chief can open voting or publish results"*.

### 2c. Reference vs implemented (Methodology-v1 Phase 4)

| Documented / intended step | Implemented? | Evidence |
|---|---|---|
| Election created | ✅ | `ElectionCreated` dispatched `Election.php:2276` |
| Submitted for approval → approved / rejected | ✅ | constitution + 3 events + `routes/platform.php:21,24` |
| Administration completed | ✅ | `AdministrationCompleted` `:1360`; route `organisations.php:328` |
| Nomination (candidacy application, completion) | ✅ | `apply_candidacy` route `organisations.php:281`; `NominationCompleted` `:1396` |
| Candidate verification / publish candidates | **Evidence not found** in this pass | no constitutional action, no event; not traced |
| Voter verification | ⚠️ partial | `VoterlistController` approve/reject routes `electionRoutes.php:202,203`; no constitutional action, no event |
| Voting opened / closed | ✅ | `VotingOpened` · `VotingClosed`; routes `:289,293` |
| Vote cast / verified (voter journey) | ⚠️ implemented **outside** the constitutional model | slug routes `:487-520`; **E-3** |
| Counting | ⚠️ state exists (`counting`); **mechanism: Evidence not found** | not traced this pass |
| Results published | ✅ | `publish_results`; `ResultsPublishedEvent` `ElectionManagementController.php:855` |
| **Results unpublished** | ✅ implemented · ❌ **not constitutional** | **U-1**, §5b |
| Audit | **Evidence not found** for the *election* lifecycle | the certified audit machinery is Contestation/Adjudication (PB003), not the election lifecycle |

**Finding E-3 stands:** the voter journey (code entry → agreement → vote → verify → complete) and the constitutional lifecycle are **two disjoint models sharing no vocabulary in code**; `apply_candidacy` is the only citizen-facing constitutional action.

---

## 3. Action → Entry-Point Traceability

| Action | HTTP entry point | Route name |
|---|---|---|
| `submit_for_approval` | `electionRoutes.php:310,314` | `elections.submit-for-approval[.show]` |
| `approve` · `reject` | `platform.php:21,24` | platform-scoped |
| `begin_setup` | **no route** (underscore/hyphen/camel all searched) — invoked internally: `Election.php:1675`, `ElectionManagementController.php:214,232` | — |
| `complete_administration` | `organisations.php:328` | `organisations.elections.complete-administration` |
| `complete_nomination` | `organisations.php:330` | `organisations.elections.complete-nomination` |
| `apply_candidacy` | `organisations.php:281` | organisation-scoped |
| `open_voting` · `close_voting` | `electionRoutes.php:289,293` | `elections.open-voting` · `.close-voting` |
| `publish_results` | `electionRoutes.php:280` | `elections.publish` |
| `archive` | `organisations.php:199` (PATCH) | organisation-scoped |
| `suspend` · `resume` | `electionRoutes.php:297,305` | `elections.suspend` · `.resume` |
| `auto_submit` · `revise_and_resubmit` | **Evidence not found** as routes (constitution assigns `roles: [system]`) | — |

**Full per-action trace (controller → application → domain → repository → read model → response) was not completed. Evidence not found** at that depth. Established: `ElectionManagementController` is the common lifecycle controller; `ElectionLifecycleEngineImpl` is the engine; `Election` (Eloquent model) hosts both the transition guard and the dispatch map.

---

## 4. Route Review

**4a — one lifecycle, three route files, two name prefixes.**

| File | Actions | Prefix |
|---|---|---|
| `routes/election/electionRoutes.php` | submit-for-approval · open-voting · close-voting · publish · suspend · resume | `elections.*` |
| `routes/organisations.php` | complete-administration · complete-nomination · apply_candidacy · archive | `organisations.elections.*` |
| `routes/platform.php` | approve · reject | platform |

**Finding R-1:** no evidence found of a rule assigning an action to a file.

**4b — the voter journey is defined twice in one file.**

| Definition | Lines | Form |
|---|---|---|
| closures | `electionRoutes.php:151,155,159,163` — `/vote/create`, `/vote/submit`, `/vote/submit_seleccted`, `/vote/verify` | anonymous |
| controllers | `electionRoutes.php:487-520` — `code/create`, `code`, `vote/agreement`, `code/agreement`, `vote/create`, `vote/submit`, `vote/verify` | `CodeController` / `VoteController`, `slug.*` |

**R-2** `/vote/create`, `/vote/submit`, `/vote/verify` appear in **both**; precedence **not verified** (Evidence not found — `route:list` requires a booted app; DB unavailable).
**R-3** `/vote/submit_seleccted` (`:159`) — misspelling in a public path. **R-4** commented-out route blocks retained at `:408-460`.

**4c — not reviewed** (Evidence not found): REST conformance, per-route middleware/authorization/validation correctness, tenant-scoping per route, dead-route detection — all require `route:list` + booted middleware resolution.

---

## 5. Governance-of-Actions Findings

### 5a. Legacy transition machinery

| Artifact | Vocabulary | State | Evidence |
|---|---|---|---|
| `ElectionConstitution::RULES` | `ElectionLifecycleState` (12) | ✅ authoritative | *"THE SINGLE SOURCE OF TRUTH"*, *"All transitions defined here and NOWHERE ELSE"*; 18 files use the enum; `Election.php:1804` *"Delegate to ElectionConstitution instead of deprecated TransitionMatrix"* |
| `TransitionMatrix::TRANSITIONS` | `setup` · `ready_for_voting` · `voting_active` · `counting` · `results_published` · `approved` · `rejected` | ⚠️ deprecated **by comment only** | `Election.php:1644`; still referenced by `Election.php` + `AppServiceProvider.php` |
| `ElectionStateMachine::TRANSITIONS` | a **third** private table (`:16,37,46`) | ⚠️ **live-instantiable** | `Election.php:1559-1563` `getStateMachine()` |
| `ElectionState` enum | 7 states (`administration`, `nomination`, `voting`, `results_pending`, `results`) | ❄️ near-dead — **1** referencing file vs 18; only `draft` overlaps | `app/Domain/Election/Enum/ElectionState.php` |

**L-1** the *"NOWHERE ELSE"* invariant is unenforced — nothing fails if a caller uses `getStateMachine()`. No current misuse found; **no defect asserted**.
**L-2** `ElectionState`'s vocabulary matches neither live source. It misled this review before cross-checking — evidence of its capacity to mislead.

### 5b. Finding U-1 — a results-reversing capability outside the constitution

| | Evidence |
|---|---|
| Not a constitutional action | `unpublish` absent from both `ElectionAction.php` and `ElectionConstitution.php` (searched) |
| Implemented in 6 places | `app/Console/Commands/UnpublishResults.php` · `app/Http/Controllers/ElectionProcessController.php` · `app/Http/Controllers/Election/ElectionManagementController.php` · `app/Services/ElectionService.php` · `app/Policies/ElectionPolicy.php` · `app/Contexts/Elections/Domain/Events/ResultsUnpublishedEvent.php` |
| Documented as a user capability | `docs/election-management-tutorial.md` §"Unpublishing Results" — *"Results are immediately hidden from public"* |
| Constitutional consequence | the constitution governs who may **publish** (chief only) but **says nothing about who may un-publish**; authorization rests on `ElectionPolicy` instead |

**Recorded as the review's most business-significant finding.** No solution proposed (Phase 1 forbids it).

---

## 6. Business Coverage Matrix

**Per-business-step coverage: Evidence not found.** Two measurement attempts failed and are reported as failures, not as coverage:
- keyword scan matched **362/362** Feature · **488/488** Unit · **25/25** Architecture files — the terms occur in shared bootstrap/namespaces, so the measurement is invalid;
- the DB-backed suites cannot execute here (no Postgres credentials — same limitation recorded in the PB003 certification).

**Established:** a named constitutional test family exists — `tests/Architecture/ElectionStateMachineConsistencyTest.php` (asserts every action referenced in code is constitutionally defined; asserts `pause_voting` / `lock_voting` removal) · `ConstitutionalAssertionsTest.php` · `tests/Feature/ElectionStateMachineTest.php` · `tests/Feature/Election/{ConstitutionalParityIntegrationTest, ConstitutionalVotingProtectionTest, ElectionManagementConstitutionalTest, ElectionStateMachineCapabilitiesTest, ElectionStateMachineProjectionTest, ElectionStateTransitionMigrationTest}.php` · `tests/Feature/Console/ProcessElectionAutoTransitionsTest.php`.

**C-1** the consistency test enforces **code → constitution** (no orphan actions). **No test found enforcing constitution → code** (every constitutional action reachable) — which is why `begin_setup`-without-a-route and the orphaned `ResultsPublished` class were invisible to the suite.

---

## 8. Gap Analysis (four categories, no solutions)

### A. Implemented, coherent
12-state / 15-action constitutional lifecycle with per-action states/roles/preconditions · role separation (committee administers; chief alone opens voting and publishes) · capacity-based auto-approval · 11-class event catalogue with 10 dispatched · generic fallback so no transition is silent · every action except `begin_setup` has an HTTP entry point (`begin_setup` reachable internally) · a ten-file constitutional test family including a code→constitution guard · `ObservedEventInventory.md` verified still accurate.

### B. Implemented but weak
**U-1** unpublish outside the constitution · **L-1** unenforced *"NOWHERE ELSE"* invariant, two rival tables, one live-instantiable · **L-2** near-dead `ElectionState` with a non-matching vocabulary · **E-1** three event namespaces (`Election` vs `Elections`) · **E-4** duplicate `ResultsPublished` concept, legacy class orphaned · **E-5** two dispatch idioms · **R-1** three route files, two prefixes · **R-2** voter journey defined twice, precedence unverified · **R-3** misspelled public path · **R-4** retained commented-out routes.

### C. Business process exists, implementation not evidenced
**E-3** voter journey has no representation in the constitutional model (disjoint vocabularies) · **C-1** no constitution→code reachability guard · candidate verification / publish-candidates step: **Evidence not found** · counting/tally mechanism: **Evidence not found** · voter verification has routes but no constitutional action or event · **audit of the election lifecycle: Evidence not found** (the certified audit machinery is Contestation/Adjudication, not the lifecycle).

### D. Potential future improvements
**Deliberately empty** — the commission forbids proposing solutions in Phase 1; every candidate would be a solution to a B/C item.

---

## Authorization boundary

| | |
|---|---|
| Code changed | **none** |
| Architecture proposed | **none** |
| ADRs created | **none** |
| Solutions recommended | **none** (category D intentionally empty) |
| Status | **Phase 1 complete — STOPPED, awaiting approval** |

**Phase 2 is not authorized by this document.** Candidates *if* commissioned: per-action trace to persistence and read models · `route:list` verification of R-2 precedence · counting/tally mechanism · candidate-verification step · the voter-journey ↔ lifecycle relationship · election-lifecycle audit trail.

**Traceability:** `app/Domain/Election/Constitution/ElectionConstitution.php` · `app/Domain/Election/Enum/{ElectionAction,ElectionLifecycleState,ElectionState}.php` · `app/Domain/Election/Events/` (11 classes) · `app/Domain/Election/StateMachine/{ElectionStateMachine,TransitionMatrix}.php` · `app/Models/Election.php:29,1360,1396,1559-1563,1644,1675,~1695,1804,2276` · `app/Http/Controllers/Election/ElectionManagementController.php:5,214,232,855` · `app/Contexts/Elections/Application/Handlers/{AssignVoterHandler:97,BulkAssignVotersHandler:132}.php` · `app/Contexts/Election{,s}/Domain/Events/` · `app/Console/Commands/UnpublishResults.php` · `app/Policies/ElectionPolicy.php` · `routes/{election/electionRoutes,organisations,platform}.php` · `docs/architecture/contexts/ObservedEventInventory.md` · `docs/election-management-tutorial.md` · `tests/Architecture/ElectionStateMachineConsistencyTest.php` + the constitutional test family

---

# §A — Rev 3 appendix: the method's closing artifacts, retrofitted

**Why an appendix and not a rewrite.** This report was written before the method acquired Phase 0 (customer journey), the Business Outcome, the Findings Table and the Method Assessment. Restructuring it now would erase the evidence of how the method evolved — and that evolution is itself a finding. **The body above stands as written; the artifacts below are added.**

*(A Phase 0 customer journey is deliberately **not** back-written here. Inventing the customer's expectations after the fact would be exactly the inference this report's own evidence rule forbids. The Election journey is stated properly in `PBDIGIT-29` §0, which was written with the method in place.)*

## A.1 — Event summary (the one-glance table)

| Business step | Event | Implemented |
|---|---|---|
| Submit for approval | `ElectionSubmittedForApproval` | ✅ |
| Approve | `ElectionApproved` | ✅ |
| Reject | `ElectionRejected` | ✅ |
| Begin setup | — | ⚠️ action exists, **no HTTP route**; invoked internally only |
| Complete administration | `AdministrationCompleted` | ✅ |
| Complete nomination | `NominationCompleted` | ✅ |
| Apply candidacy | — | ⚠️ route exists, **no event** |
| Open voting | `VotingOpened` | ✅ |
| Close voting | `VotingClosed` | ✅ |
| Publish results | `ResultsPublishedEvent` *(plural `Elections` context)* | ✅ |
| **Unpublish results** | `ResultsUnpublishedEvent` | ⚠️ **implemented, outside the constitution** |
| Archive | — | ⚠️ route exists, no event |
| Voter verification | — | ⚠️ routes exist, **no constitutional action, no event** |
| Vote cast (5-step journey) | — | ⚠️ implemented in a **separate model** with no shared vocabulary |
| *(any other transition)* | `ElectionStateChangedEvent` | ✅ generic fallback — no transition is silent |
| — | `ResultsPublished` *(legacy, singular context)* | ❌ **orphan — imported, never dispatched** |

The detailed dispatch/fallback evidence remains in §1c above.

## A.2 — Business Outcome

```
Business Outcome

Today     A customer can create an election, get it approved, configure posts,
          run nomination, open and close voting, count, publish results, and audit.
          But a published result can be WITHDRAWN through a path the constitution
          does not describe, and the voter's own journey is governed by a second,
          disconnected model that shares no vocabulary with the election lifecycle.

Expected  Every customer-visible election capability — including unpublishing —
          is governed by one constitutional lifecycle, and the voter journey is
          part of it rather than parallel to it.
```

**In one sentence:** the election lifecycle is constitutional; the things done *to* a finished election, and the things done *by* a voter, are not.

## A.3 — Findings Table

| # | Finding | Type | Priority | Needs business decision | Needs code | Verified |
|---|---|---|---|---|---|---|
| **U-1** | Unpublishing a published result sits **outside the constitution** — implemented in 6 places, documented for customers, authorised only by `ElectionPolicy` | **Product** | **High** | **Yes** — who may withdraw a result, and is it recorded? | Yes | No |
| **E-3** | Voter journey and election lifecycle are **two disjoint models** with no shared vocabulary | **Product** | **High** | **Yes** — what does closing voting mean for a voter mid-ballot? | Yes | No |
| **R-2** | Voter-journey paths defined **twice** in one route file; precedence unverified — a voter action may reach the wrong handler | **Product** | Medium | No | Yes | No |
| **C-1** | No constitution→code reachability guard — which is why `begin_setup`'s missing route and the orphan event went unnoticed | **Technical** | Medium | No | Yes | No |
| **L-1** | Two rival transition tables remain, one **live-instantiable** via `Election::getStateMachine()`, while the constitution claims transitions live "NOWHERE ELSE" | **Architecture** | Medium | No | Yes | No |
| **L-2** | Near-dead `ElectionState` enum whose 7-state vocabulary matches neither live source | **Architecture** | Medium | No | Yes | No |
| **E-4** | Duplicate `ResultsPublished` concept — legacy class imported but never dispatched | **Architecture** | Medium | No | Yes | No |
| **E-1** | Election events live in **three namespaces**, two differing only by pluralisation | **Architecture** | Medium | No | Yes | No |
| **R-3** | `/vote/submit_seleccted` — misspelling in a public voter path | **Product** | Low | No | Yes | No |
| **E-5** | Two dispatch idioms (`event()` / `Event::dispatch()`) in one model | **Technical** | Low | No | Yes | No |
| **R-1** | One lifecycle spread over three route files with two naming schemes | **Technical** | Low | No | Yes | No |
| **R-4** | Commented-out route blocks retained | **Technical** | Low | No | Yes | No |

**Product: U-1, E-3, R-2, R-3** — the four a customer could encounter. **Architecture: L-1, L-2, E-4, E-1.** **Technical: C-1, E-5, R-1, R-4.**
**Verified column: "No" on every row** — nothing in this report was observed at runtime.

## A.4 — Retrospective note on the two 3/3 patterns

This report is the **first sighting** of both patterns later confirmed across Organisation and Membership:

| Pattern (as abstracted after 3 applications) | Its form here |
|---|---|
| **The business lifecycle has no single authoritative representation** | `begin_setup` reachable by no route · three transition tables · a 7-state enum matching neither live source (L-1, L-2) |
| **Multiple competing representations of one business concept** | two `ResultsPublished` classes · three event namespaces · voter-journey routes defined twice (E-4, E-1, R-2) |

Recorded here so the first sighting is findable from the pattern, not only the pattern from the sighting.

---

**Rev 3 authorization boundary:** no code changed · no architecture proposed · no solutions recommended · **body of rev 2 unaltered** · appendix added only.
