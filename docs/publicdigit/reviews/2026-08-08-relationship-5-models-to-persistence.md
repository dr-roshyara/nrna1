# Relationship 5 — Models → Persistence of authoritative decisions

**Commission:** Principal Architect, Election architecture discovery (`PBDIGIT-48`, Slice 1) · **Date:** 2026-08-08
**Status:** DISCOVERY ONLY — **no production code, test, fixture, migration or schema was changed**
**Carried forward, not re-derived:** `PBDIGIT-48` (three generations of representation; consumer inventory) · `ADR_20260807_1500_Election_Lifecycle_Single_Source_Of_Truth` (lifecycle authority, PO-approved)

> **Governing principle adopted for this report:** **Persistence is not authority merely because it is durable. A persisted representation derives its architectural meaning from the business decision it represents and the component that owns that decision.**

---

## 0 · Corrections to the interim Relationship 5 entry — read this first

The plan entry of 2026-08-08 (`d419ff80`) recorded four claims that **exceeded the evidence**. All four are corrected here, and the corrections are the substance of this report rather than footnotes.

| # | What I wrote | Why it was wrong | What the evidence supports |
|---|---|---|---|
| **C-1** | `ElectionStateTransition` is *"the only durable record **of the decision**"* | *"Decision"* is a **business** concept. I named one from the record's **shape** without establishing its business meaning or a single consumer | It is an **immutable, attributed record of executed transition operations** — and **nothing reads it** (§7) |
| **C-2** | *"state can change with **no decision** and no audit row"* | Conflates *derived state* with *transition authority*. In a derived model a state does not "change because someone decided" | **The evaluated lifecycle state changes because an authoritative business fact changed.** No decision is implied or missing |
| **C-3** | Treated current state and transition history as one authority | They answer different questions and may be **intentionally** different concepts | Question A (*what state is it in?*) and Question C (*what transition was executed?*) have **different authorities** (§5) |
| **C-4** | *"A test that sets `['state' => …]` is priming the cache, not the truth"* — stated generally | Would become a blanket rule *"state fixture = bad test"*. Some tests **intend** to verify the persisted representation | **Intent determines correctness.** The question is what business contract a test verifies and which representation is authoritative *for that contract* (§8) |

**C-2 is the most consequential.** Left standing, it invites the conclusion *"every lifecycle-state change must have a transition record"* — which would convert a **derived-state model** into a **state-machine persistence requirement**, contradicting the approved architecture.

---

## 1 · Business meaning

**OBSERVED FACT.** The concept is **the constitutional position of an election in its lifecycle** — 12 states (`draft … setup_administration, setup_nomination, ready_for_voting, voting_active, counting, results_published, archived`, plus overlay states `suspended`, `rejected`).

**OBSERVED FACT.** The concept is defined as a **function of business facts**, not as a stored value. `ElectionLifecycleEngineImpl` declares:

> *"CRITICAL: Derives state ONLY from business facts. **State column is compatibility cache, not truth.**"*

`getState()` evaluates a documented **12-step constitutional priority order** (suspended → archived → results published → counting → voting active → ready for voting → setup nomination → setup administration → approved → submitted → rejected → draft fallback).

**CONCLUSION.** The lifecycle is a **derived read model over business facts**. This is the already-approved authority; **this report does not reopen it.**

## 2 · Authority

| Question | Authority | Evidence |
|---|---|---|
| What state is this election in **now**? | `ElectionLifecycleEngineImpl` (derivation) | its own contract; `getState()` reads facts, never `elections.state` |
| Is this **transition operation** permitted? | `ElectionConstitution` (rule content) applied by `ConstitutionalTransitionGuard` (Application) + `validateTransitionRules()` (Domain) | Relationship 4 |
| What transition operations **were executed**? | `election_state_transitions` | §7 |

**INTERPRETATION.** Authority is **split by question**, not by table. No single artifact is "the authority for election state"; asking that question without qualifying *which* question produces a wrong answer.

## 3 · Persistence map

| Representation | Semantic meaning | Written by | Read by | Classification |
|---|---|---|---|---|
| **business facts** (`voting_starts_at`, `voting_ends_at`, `nomination_completed`, counts, approval fields, `suspended_at`, `archived_at`) | the inputs the lifecycle is derived from | many paths (ordinary model updates) | `ElectionLifecycleEngineImpl` | **AUTHORITATIVE DOMAIN STATE** |
| **lifecycle snapshot** (`ElectionLifecycleSnapshot`) | the evaluated position + permissions | not persisted — computed per request | capability/authorization/UI paths | **DERIVED STATE** |
| **`elections.state`** | last value written by a transition operation | `transitionTo()` (`updateQuietly`) · `ActivateElectionCommand:30` · `BackfillElectionState:67` | legacy consumers (`PBDIGIT-48` inventory) | **COMPATIBILITY CACHE** |
| **`elections.status`** | gen-1 vocabulary (`planned/active/completed/archived`) | **only** `SetupDemoElection` / `SetupPublicDemoElection` at creation (`PBDIGIT-48`) | routing (`PBDIGIT-47`), demo commands, some queries | **LEGACY REPRESENTATION** |
| **`elections.is_active`** | gen-1 boolean | gen-1 paths | `ElectionManagementController:388` | **LEGACY REPRESENTATION** |
| **`election_state_transitions`** | executed transition operations, attributed and immutable | `transitionTo()` only | 🔴 **nothing** | **HISTORICAL RECORD — UNCONSUMED** (§7) |

**Categories deliberately not collapsed:** *derived* ≠ *cached* ≠ *legacy* ≠ *historical*. `elections.state` is a cache of a **derived** value; `elections.status` is a **different vocabulary** from a previous generation, not a stale copy of the same thing.

## 4 · Write paths

| Writer | Business operation | Guard? | Invariants | Events | Writes |
|---|---|---|---|---|---|
| `Election::transitionTo()` `:1614` | a requested lifecycle transition | ✅ `ConstitutionalTransitionGuard` — **but only if `!isSystemTriggered()`** (`:1649`) | ✅ `validateTransitionRules()` (always) | 🔴 **bypassed** — `updateQuietly` (`:1670`) | `election_state_transitions` **and** `elections.state` |
| `ActivateElectionCommand:30` | CLI activation | 🔴 **none** — does not call `transitionTo()` | 🔴 none | ✅ fire (`update`) | `elections.state` **only** |
| `BackfillElectionState:67` | reconciliation of cache to derivation | n/a — by design | n/a | ✅ fire (`update`) | `elections.state` **only** |
| `SetupDemoElection` / `SetupPublicDemoElection` | demo provisioning | n/a | n/a | ✅ | `elections.status` at creation |

**OBSERVED FACT — answers an open item from the interim entry.** Two production paths write `elections.state` **without** passing through `transitionTo()`: `ActivateElectionCommand` and `BackfillElectionState`.

**OBSERVED FACT.** Event behaviour is **inconsistent between writers**: the transition path suppresses events (`updateQuietly`), the two command paths emit them (`update`).

**HYPOTHESIS — not established.** That `updateQuietly` is deliberate (avoiding observer recursion during a transition). **`updateQuietly` is not treated here as a defect**; what business contract requires event emission on a cache write has not been established, and no consumer of such an event was found.

## 5 · Read paths — and the four questions kept separate

| | Question | Authoritative source | Consumers observed |
|---|---|---|---|
| **A** | What is the election's current lifecycle state? | derivation (engine) | capability/authorization paths, `ElectionLifecycle::of(...)->snapshot()` |
| **B** | What happened previously in the lifecycle? | `election_state_transitions` | 🔴 **none** |
| **C** | What controlled transition was requested/executed? | `transitionTo()` + its record | 🔴 **none read the record** |
| **D** | What evidence exists that a decision was made? | — | **UNDETERMINED** (§7) |

**A ≠ B ≠ C ≠ D.** No evidence establishes their equivalence, and §7 shows B/C/D are unconsumed.

**Legacy reads — classified per consumer, not globally** (Relationship 5 rule: *a representation can be harmless for one consumer and dangerous for another*):

| Site | Reads | Does it decide business behaviour? |
|---|---|---|
| `ElectionManagementController:186` | `status = 'active'` | **yes** — selects elections |
| `ElectionManagementController:388` | `is_active = true` | **yes** |
| `ProcessElectionAutoTransitions:141` | `status = 'active'` | **yes** — selects candidates for auto-transition |
| `SetupDemoElection:362`, `SetupPublicDemoElection:295` | `status === 'active'` | **provisioning only** — not a live business decision |
| `ProcessElectionAutoTransitions:66-67`, `ElectionManagementController:1292,1297` | `candidacies.status` | **unrelated concept** — candidacy status, not election state. **Recorded to prevent name-collision false positives** |

## 6 · Divergence model

| Question | Answer |
|---|---|
| 1 · Can cache and derivation diverge? | **Yes — OBSERVED FACT.** Different write triggers (§4) and different read logic (§1) |
| 2 · What causes it? | Any edit to a business fact moves the derivation; the cache moves only on a transition or a command |
| 3 · Expected by design? | **Yes** — a reconciliation command exists precisely for it (`PBDIGIT-48`) |
| 4 · Repaired automatically? | **No** — `PBDIGIT-48` verified no scheduler invokes it |
| 5 · Repair manual? | **Yes** — `app:backfill-election-state` |
| 6 · Who depends on the representation? | legacy consumers in §5 |
| 7 · Can divergence affect a business decision? | **Yes — demonstrated.** `PBDIGIT-47`: a voter was not routed to a live election because routing read a stale representation |
| 8 · Observable customer impact? | **Yes — `PBDIGIT-47`**, already a story |
| 9 · How often does it occur? | 🔴 **Frequency not established.** No measurement has been taken and none is inferred |

## 7 · Transition semantics — what `election_state_transitions` actually is

**OBSERVED FACTS:**

* Fields: `election_id`, `from_state`, `to_state`, **`trigger`**, **`actor_id`**, **`reason`**, `metadata`, `created_at`.
* **Immutable by enforcement** — `updating` and `deleting` both `throw new \RuntimeException` (`ElectionStateTransition:35-42`).
* `created_at` stamped on creation.
* Written **only** by `transitionTo()`.
* 🔴 **No production reader.** Every occurrence in `app/` is a creation, a return type, or its own immutability hook. **No UI surface, no controller query, no audit report, no export.**

**INTERPRETATION.** The **shape** is accountability-shaped: *who* acted, *why*, *what triggered it*, and it cannot be altered afterwards. Those fields exist for answering questions after the fact.

**CONCLUSION — and it is narrower than the shape suggests.** It is an **immutable, attributed record of executed transition operations that nothing currently consumes.**

**Explicitly NOT concluded** (this is correction C-1): that it is *the* record of a business decision, an audit log in the governance sense, a domain event, or a source of truth. **A record whose only consumer is its own write path cannot be shown to function as evidence.** Whether it is *intended* as constitutional audit evidence is a **BUSINESS DECISION REQUIRED** (§11), not a technical reading.

**⚠️ Note the coverage boundary this creates:** because it is written only by `transitionTo()`, transitions performed by `ActivateElectionCommand` leave **no record at all** — the history is silent about them.

## 8 · Test implications

**The blanket rule is explicitly rejected** (correction C-4). *"This test sets `state`, therefore it is wrong"* is **not** a valid finding. The valid form is:

> **This test intends to verify X; it arranges representation Y; the authoritative source for X is Z.**

| Group | What it can legitimately verify | Examples |
|---|---|---|
| Arranges/asserts **`elections.state`** | **PERSISTENCE** · **COMPATIBILITY** · **LEGACY-CONSUMER** behaviour — all legitimate intents | `ElectionActivationTest` · `ElectionStateMachineCapabilitiesTest` · `ResultsPublicationTest` · `ElectionStateMachineProjectionTest` · `ElectionDashboardCapabilitiesTest` |
| Asserts the **snapshot** | **BUSINESS-TRUTH** · **APPLICATION-CAPABILITY** · **AUTHORIZATION** | `ElectionStateMachineTest` · `ElectionManagementConstitutionalTest` · `ConstitutionalVotingProtectionTest` · `ElectionPolicyStateAwareTest` · `VoterEligibilityTest` |
| Asserts **`ElectionStateTransition`** | **HISTORY/AUDIT** | `StateMachineTransitionAuditTest` · `ElectionStateTransitionMigrationTest` · `CapacityApprovalTest` |

**The genuine risk, stated as a hypothesis to be tested per test in Step 2 — not as a verdict:**

> **HYPOTHESIS.** A test intending **BUSINESS-TRUTH** that arranges `['state' => …]` may pass or fail for reasons unrelated to its intent, because the production path under test derives state from facts and does not read that column. **Which tests are in that position is UNDETERMINED** and requires reading each test's intent.

**Two failure modes to check per test:** could it **pass while authoritative business state is wrong**? Could it **fail while business behaviour is correct**?

## 9 · Architectural findings

| # | Finding | Class |
|---|---|---|
| **R5-1** | Authority is **split by question** (A/B/C/D) — no single "state authority" exists | CONCLUSION |
| **R5-2** | `elections.state` is a **compatibility cache**, declared as such by the engine; the authority never reads it | OBSERVED FACT |
| **R5-3** | **Two production paths write `elections.state` outside `transitionTo()`** — `ActivateElectionCommand` (no guard, no history) and `BackfillElectionState` (reconciliation) | OBSERVED FACT |
| **R5-4** | Event emission is **inconsistent across writers** of the same column | OBSERVED FACT |
| **R5-5** | `election_state_transitions` is **immutable, attributed, and unconsumed** | OBSERVED FACT + CONCLUSION |
| **R5-6** | Transition history is **incomplete by construction** — `ActivateElectionCommand` transitions leave no record | OBSERVED FACT |
| **R5-7** | Divergence is **possible, expected, manually repaired, and has already caused customer impact** (`PBDIGIT-47`); **frequency unmeasured** | CONCLUSION |
| **R5-8** | The evaluated lifecycle state changes when an authoritative business fact changes — **this is the design, not a defect** | INTERPRETATION |

## 10 · Open questions

1. Which `elections.state`-arranging tests actually depend on the column being **read back**, versus merely constructing a fixture? *(Step 2, per test.)*
2. Is `ActivateElectionCommand`'s direct write **intentional** (a provisioning shortcut) or an unmigrated legacy path? **Mechanism established; intent not.**
3. How often do cache and derivation diverge in production? **Frequency not established.**
4. Is `updateQuietly` deliberate, and does any consumer need an event on a cache write? **Undetermined.**
5. Does any consumer outside `app/` (jobs, exports, reporting, BI) read `election_state_transitions`? **Only `app/` was searched.**

## 11 · Business decisions required

| # | Decision | Why it cannot be derived |
|---|---|---|
| **BD-1** | **Is `election_state_transitions` intended to be constitutional audit evidence?** | Its shape says accountability; its usage says nothing reads it. **If it is evidence, R5-6 (missing records from `ActivateElectionCommand`) is a governance gap. If it is implementation history, R5-6 is harmless.** The same fact means opposite things depending on this answer |
| **BD-2** | **Must every lifecycle-state change be attributable, or only every transition *operation*?** | Determines whether fact-edit paths (§ R5-8) need constitutional guarding — the same shape as Relationship 4's Finding 4c |
| **BD-3** | **Is `ActivateElectionCommand` an authorised bypass?** | Operational convenience vs an unguarded state write |

## 12 · Recommended next discovery step

**Relationship 6 — Demo** (`PBDIGIT-59`-adjacent), completing the relationship map before Step 2 classification. **No implementation is prescribed.**

**BD-1 should be put to the Product Owner before Step 2**, because it changes how every history/audit test is classified: if the record is not evidence, `StateMachineTransitionAuditTest` verifies an implementation detail rather than a business invariant.

---

## Self-audit

| # | Check | ✓ |
|---|---|---|
| 1 | Started from business meaning, not columns | ✅ §1 |
| 2 | Preserved the approved lifecycle authority; did not reopen SSOT | ✅ |
| 3 | Distinguished current state from transition history | ✅ §5 A/B/C/D |
| 4 | Distinguished authoritative from cached/derived/legacy | ✅ §3 — categories not collapsed |
| 5 | Distinguished persistence from domain ownership | ✅ governing principle |
| 6 | Distinguished business decisions from technical writes | ✅ C-1, §7 |
| 7 | Inspected writers **and** readers separately | ✅ §4, §5 |
| 8 | Did not assume divergence is a defect | ✅ §6, R5-8 |
| 9 | Distinguished test intent from fixture mechanics | ✅ §8, C-4 |
| 10 | Did not treat every legacy reference as a defect | ✅ §5 — per consumer; candidacy `status` excluded as a name collision |
| 11 | Separated fact / interpretation / hypothesis / conclusion | ✅ labelled throughout |
| 12 | Quantified nothing unmeasured | ✅ "frequency not established" |
| 13 | No implementation changes | ✅ |
| 14 | Preserved `PBDIGIT-48`'s conclusions | ✅ carried forward, not re-derived |
| 15 | Identified what remains unknown | ✅ §10 |

---

**RELATIONSHIP 5 COMPLETE — EVIDENCE RECORDED — NO IMPLEMENTATION CHANGES — AWAITING PRODUCT OWNER REVIEW**

**Traceability:** `app/Application/Election/Services/ElectionLifecycleEngineImpl.php` (contract, `getState()`) · `app/Models/Election.php:1614,1649,1658,1670` · `app/Models/ElectionStateTransition.php:17-46` · `app/Console/Commands/ActivateElectionCommand.php:30` · `app/Console/Commands/BackfillElectionState.php:67` · `app/Http/Controllers/Election/ElectionManagementController.php:186,388` · `app/Console/Commands/ProcessElectionAutoTransitions.php:141` · `PBDIGIT-48` · `PBDIGIT-47` · `PBDIGIT-59` · `ADR_20260807_1500` · plan `docs/plans/20260808-1030-election-verification-execution-plan.md` (Relationships 1–5)
