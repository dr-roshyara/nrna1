# Work Plan — WP-2: Adjudication Process Manager (APM) core

**Created:** 2026-07-30 · **Status:** AUTHORIZED — pre-implementation assessment COMPLETE; next step is RED
**Mode:** Auto · **Discipline:** Business Model → Strategic DDD → Tactical → RED → GREEN (PA commission 2026-07-30)

> **Implementation objective: realize the approved business model through disciplined DDD — implementation stays subordinate to architecture, architecture stays subordinate to the business domain.**

**Closed commissions, not to be reopened:** F-2 Repository Hygiene · CONTEXT Runtime Simplification · Architecture-to-Implementation Fidelity Verification · Verification Artifact Placement (deferred to D-1).

---

## 1. Authority Register

| Authority | Purpose for WP-2 | Status |
|---|---|---|
| `EPIC-004K_Adjudication_Process_Manager_Architecture.md` §§1–13 | THE approved design: purpose · 8 responsibilities · boundaries · 6 states · transitions+guards · timers · triggers · messages · **§11 persistence** | APPROVED (ARB 2026-07-26) |
| `EPIC-004_Architecture_to_Implementation_Roadmap.md` §WP-2 | Slice scope + keystone tests + gates | APPROVED; **WP-2 authorized** after WP-1 acceptance (2026-07-27) |
| **ADR-T8** | The PM is the loop's **HEAD, never its coordinator** — no saga, no compensation | Frozen |
| **ADR-T23** (supersedes T17) | **The authority decides; the PM receives.** No domain service computes legitimacy | Issued |
| **ADR-T22** | Considered-set's permanent fixation is the aggregate's issuance; the PM's log is the *working* record | Issued; carrier delivered by WP-1 |
| **Q-1** (`EPIC-004_Q1_Authority_Resolution.md`) | Governance owns authority; Adjudication consumes via published contract; **never** implements/duplicates/validates authority rules | Frozen |
| **Q-2** (`EPIC-004_Q2_Resolution_Package.md`) | Adjudication horizon = **MAD, bootstrap 60 days**; the APM *enforces*, never *owns* the duration | Gate closed; values = implementation bootstrap defaults |
| **EPIC-004E** INV-B1 + INV-1..7 | The two-seat pattern (boundary guard + unique index); the aggregate's 8 invariants stay untouched | Frozen |
| **ADR-T16** | Cross-context identity crosses as strings; each context reconstructs LOCAL VOs | Frozen |
| Four constitutional policies | esp. **Policy 4** (detection may be automatic; determination may not) · Policy 2 (EPW needs a bounded horizon) | Ratified |
| DDD Tactical Governance Principles (7) | APP · VODP · ASP · ADP · DMT · RMSP · Methodological Fitness | Adopted |
| **ADR-T21** | `ChallengeRouted` → published language — **WP-3, explicitly NOT WP-2** | Issued, unimplemented |

## 2. Domain Understanding

**Business goal.** Conduct the adjudication of **one routed challenge** from request to conclusion, and conclude **exactly once** — either as a ruling request toward the Determination aggregate, or as a declared failure.

**Business problem (EPIC-003 risk R-1).** The correction loop is an **open arc**: there is no production path from a routed challenge to issuance. WP-2 builds the missing head — and only the head; from `DeterminationIssued` onward the loop stays pure choreography (ADR-T8).

**Ubiquitous language (business-derived; §5/§9 of EPIC-004K).** adjudication request · admission (of an opaque evidence reference) · demand · basis · **the authority's decision** · conclusion (*ruling-requested* | *failure-declared*) · adjudication horizon · expiry.
**Rejected vocabulary:** "saga", "orchestrator", "workflow step" (mechanism, not business) · **"proceeding"** (Candidate-2 ruling: an architectural hypothesis with no business identity) · any finer state gradation than the six (ASP — would be invented, not discovered).

**Business policies.**
- **K1 / Q-1 / ADR-T23:** the constitutional authority decides; the process **receives, never computes** legitimacy or sufficiency.
- **TP-2:** Contestation *requests*, never creates an adjudication.
- **Policy 4:** automated verification never determines significance — no automatic escalation from a timer to a conclusion.
- **Q-2:** the conduct is bounded by MAD; the APM enforces the bound it does not define.

**Business invariants owned by WP-2** (candidates for RED):
1. **One active process per challenge** (PM-1 — mirrors INV-B1 from the process side).
2. **Exactly one conclusion, of exactly one kind — or an expiry; never more than one, never a mix.**
3. **No admission after conclusion.**
4. **Conclusion + considered-set + authority reference commit as one write** (PM-5 conclude-time atomicity).
5. **The conduct is horizon-bounded** — expiry is a distinct terminal fact, never silent disappearance.
6. **Anonymity (ADR-T11/AT-Q7):** evidence travels as opaque references only; no voter↔vote linkage anywhere in PM state or events.

**Business ownership (never inferred from implementation).** APM owns the **conduct**. Contestation owns the challenge lifecycle. Governance owns authority validity. The Determination aggregate owns the ruling record. Upstream contexts own evidence content, custody, integrity.

## 3. Strategic DDD Assessment

- **Bounded context:** Adjudication (one of the five ratified BCs). WP-2 adds nothing outside `app/Contexts/Adjudication`.
- **Upstream:** Contestation (`ChallengeRouted` — arrives in WP-3) · Governance (authority decision via Q-1's published contract) · Collection-side (evidence admissions/demands — business shape defined, **transport deferred**, EPIC-004K §14).
- **Downstream:** the Determination aggregate (in-context, via `IssueDeterminationCommand`) · Contestation + Collection (`AdjudicationFailureDeclared`).
- **Relationship patterns:** Published Language for the loop head (ADR-T21) and for the authority crossing (Q-1). **No Shared Kernel and no ACL is introduced by WP-2.** Identity crosses as strings; Adjudication reconstructs local VOs (ADR-T16).
- **Seating decision (already ruled, not re-opened):** the APM is **orchestration, not an aggregate** (Candidate-2 ruling) → it lives in **Application** (+ Infrastructure for its store); it gets **no domain aggregate and no domain repository** (EPIC-004K §11; RMSP by analogy, not stretched).

## 4. Business Model Fidelity Review *(runs BEFORE implementation — PA methodology, first application)*

| Dimension | Preserved by the planned implementation? | How it is protected |
|---|---|---|
| **Ubiquitous language** | Yes | States/methods named from §5's business words; no minted intermediary states; "proceeding" absent |
| **Business ownership** | Yes | No class computes legitimacy or sufficiency; no delegation validation; the PM only *receives* a decision (K1/Q-1/ADR-T23) |
| **Aggregate boundaries** | Yes | Determination's 8 invariants untouched; the PM **requests** issuance, never issues; INV-B1 stays the aggregate-boundary seat, mirrored (not moved) at the process side |
| **Context autonomy** | Yes | Only `app/Contexts/Adjudication` touched; wire values reconstructed into local VOs |
| **Domain events** | Yes | `AdjudicationFailureDeclared` is Adjudication-owned; `DeterminationIssued` remains the aggregate's sole emission |
| **Invariants** | Yes | The six of §2 become tests before code; anonymity enforced by opaque refs (AT-Q7 fitness scan already covers the surface) |
| **Business policies** | Yes | K1 · TP-2 · Policy 4 (no timer→conclusion path: horizon fires **Expired**, never a conclusion) · horizon enforced, not defined |

**Uncertainty check → one scope boundary stated rather than assumed (not a blocker).** The roadmap's WP-2 row lists store · model · mapper · state machine · conclude-time fixation · uniqueness · event-logged admissions/demands. It does **not** list transport: the loop-head event (WP-3), handler registration/wiring and the crash-safe conclude→issue seam (WP-4), timer execution (WP-6). **WP-2 therefore delivers the core driven through in-memory ports; no messaging wiring.** The authority-intake channel is a recorded external in the roadmap, outside this authorization.

## 5. Tactical Design Assessment

**Reuse-before-create finding (repository evidence).** A house process-manager pattern already exists: `app/Contexts/Governance/Application/Approval/GovernanceApprovalProcessManager.php` — Application-seated; `handle(object $event, DateTimeImmutable $now)` with `match(true)` dispatch; an **immutable state object** (`ApprovalProcessState::start()` / `addApproval()` / `status()->isTerminal()`); idempotency via key lookup (`findByDecisionId(...) !== null`); **time injected, never read**; terminal-state guard before mutation. **Reuse this shape.**

**One deliberate divergence from that precedent, on authority.** Governance names its store `ApprovalProcessRepository`. EPIC-004K §11 rules that PM state **is not an aggregate and gets no domain repository**, and fixes the store's surface at exactly five operations. WP-2 therefore names it a **store**, not a repository — recorded here so the divergence reads as authority-driven, not stylistic. *(Whether Governance's naming should later align is that context's question, not WP-2's.)*

**Planned components (all `app/Contexts/Adjudication`):**

| Layer | Component | Note |
|---|---|---|
| Application | `AdjudicationProcessStatus` (enum, 6 states) | Opened · Assembling · AwaitingDecision · ConcludedRulingRequested · ConcludedFailureDeclared · Expired; `isTerminal()` |
| Application | `AdjudicationProcessState` (immutable) | transitions with guards per §6; holds admissions/demands, conclusion, authority ref, considered set |
| Application | `AdjudicationProcessStore` (port) | Exactly §11's surface: create-on-open · append admission/demand · record conclusion · load-for-reaction · due-timer query. **No query zoo.** |
| Application | `AdjudicationProcessManager` | `handle(event, now)`; PM-1..PM-8 minus deferred transport |
| Infrastructure | Eloquent store impl · model (`BelongsToTenant`) · mapper · additive migration | `unique(organisation_id, challenge_ref)` for the active process — the INV-B1 two-seat backstop (precedent: `uniq_determination_per_challenge`) |

**Deliberate absences (ASP):** no domain aggregate · no domain repository · no saga/compensation · no `FinalizeDetermination*` (Q-2 temporal policy, WP-6) · no sufficiency/legitimacy computation · no timer scheduler (WP-6) · no handler registration (WP-4).

## 6. RED Test Plan (written first; must fail for the expected reasons)

**Keystones (roadmap-named acceptance criteria):**
1. **Exactly-once conclusion under concurrent redelivery** — two concurrent/duplicate authority decisions produce exactly one conclusion; the second is refused, not a second conclusion.
2. **No admission after conclusion** — admitting evidence into a concluded process is refused, state unchanged.
3. **Unique-active-per-challenge under race** — Feature test at the DB seam: two concurrent opens for one challenge ⇒ one row, one process (guard + unique index, INV-B1 pattern).

**Supporting (invariant coverage):**
4. Legal transitions per §6 traverse ∅→Opened→Assembling⇄AwaitingDecision→each terminal; **illegal transitions throw with no mutation and no event** (house discipline from the aggregate).
5. **Conclude-time atomicity** — conclusion + considered-set + authority reference are one write; a partial conclusion is unrepresentable.
6. **Horizon expiry** under `FrozenClock`: horizon elapsed without conclusion ⇒ `Expired`; **a timer never produces a conclusion** (Policy 4).
7. Terminal guard — no mutation of any kind after a terminal state.
8. Anonymity — evidence references remain opaque; no voter/vote identifier appears in state or events.

## 7. Implementation Plan

RED (§6) → confirm RED for expected reasons → **STOP + report** → GREEN minimal (Application first: status/state/store port/PM; then Infrastructure: model, mapper, migration, store impl) → gates: `composer merge-gate` PASS · triple qualification (Architecture/DDD/Trustworthiness) · measurable conformance gate · dev guide `developer_guide/adjudication/03_*` → **STOP for ARB slice acceptance.** No WP-3/WP-4 work.

## 8. Risks

| # | Risk | Mitigation |
|---|---|---|
| R-1 | Scope creep into WP-4 wiring (the store *invites* a handler) | Ports only; no registration; §4's boundary is binding |
| R-2 | Accidentally computing sufficiency to "help" the authority decide | Constitutionally forbidden (K1/Q-1/ADR-T23); RED test 6 + review guard |
| R-3 | Concurrency test flakiness at the DB seam (F-7D-2 lesson: measurement can lie) | Assert on DB constraint outcome, not timing; scope by organisation (PB-006 convention) |
| R-4 | Treating the PM log as a second source of authority | EPIC-004K §11: one truth, two records, one authoritative — dev guide states it |
| R-5 | Minting states beyond the six | ASP; §5's own note |

## 9. Architectural Assumptions (stated, falsifiable)

1. WP-2 needs **no** new ADR — every element realizes an issued decision (if that proves false: STOP, request an architectural decision).
2. Horizon duration is **configuration** seeded from Q-2's bootstrap (MAD 60d), not a hardcoded constant.
3. The existing bound `App\Domain\Shared\Clock\ClockInterface` is reused (ER-03/04 — no new clock).
4. Admissions/demands persist as an **event-logged** append within the store's fixed surface — no separate event store.

## 10. Next Authorized Step

**Write the §6 RED tests and confirm they fail for the expected reasons. Report at the RED boundary before any production code.**

---

## 11. Architectural Traceability Review (PA checkpoint, 2026-07-30) — run BEFORE RED

### 11.1 Component-to-Authority table

| Component | Authority | Section / decision | Rationale |
|---|---|---|---|
| `AdjudicationProcessStatus` (6 cases, `isTerminal()`) | EPIC-004K | §5 States | Six business-derived states, closed set; an enum makes minting a 7th a compile-time act (ASP). Precedent: `DeterminationState` |
| `AdjudicationProcessState` (immutable) + transitions/guards | EPIC-004K | §6 Transitions | The guards' seat; "exactly one conclusion of exactly one kind, or an expiry" |
| `AdjudicationProcessId` | EPIC-004K | §6 guard ("no **active** process") + §11 | **Implicitly required** — see finding F-T1: uniqueness is scoped to *active* processes, so a challenge may have >1 row over time ⇒ row identity cannot be `challenge_ref` |
| `IllegalProcessTransition` (exception) | House discipline | Determination precedent | Forbidden transition throws; no mutation, no event |
| `AdjudicationProcessStore` (port, 5 operations) | EPIC-004K | §11 Persistence | Durable long-running coordination; surface fixed at create-on-open · append admission/demand · record conclusion · load-for-reaction · due-timer query. **No query zoo** |
| `AdjudicationProcessManager` (Application) | EPIC-004K | §3 PM-1..PM-8 | The conduct's seat; the reason WP-2 exists |
| Eloquent store impl + model (`BelongsToTenant`) | EPIC-004K §11 + layer rules | Application bans Eloquent | Infrastructure realizes the port; tenancy is an infrastructure concern (ADR-T16) |
| Migration with **partial** unique index on active rows | **INV-B1** (EPIC-004E) + EPIC-004K §6 | Two-seat pattern | Guard at the boundary + DB backstop; precedent `uniq_determination_per_challenge`. Scoped to active per F-T1 |
| Conclude-time atomicity (one write) | EPIC-004K | §3 PM-5, §11 | Conclusion + considered-set + authority ref inseparable |
| Reuse of `EvidenceSet` | **ADR-T22** (delivered by WP-1) | §11 R-4-expanded | The considered-set carrier already exists — reuse, do not re-create |
| Reuse of `ChallengeRef`, `IssuedByAuthority`, `Reason` | Reuse-before-create | Existing Adjudication VOs | Same context; no new VO where one exists |
| Reuse of `ClockInterface` / `FrozenClock` | ER-03/04 (PB-004 clock ruling) | — | Time injected, never read; no new clock |
| Horizon enforcement (→ `Expired`) | **Q-2** + **Policy 4** | MAD 60d bootstrap | The APM enforces a duration it does not own; a timer yields **Expired**, never a conclusion |
| `AdjudicationFailureDeclared` (business occurrence) | EPIC-004K | §3 PM-7, §10 | Recorded in WP-2; **publication wiring is WP-4** |
| Opaque evidence references only | **ADR-T11 / AT-Q7** | Anonymity | No voter↔vote linkage in state, store, or events |
| Store **not** Repository | EPIC-004K §11 + RMSP by analogy | — | PM state is not an aggregate; divergence from Governance's `...Repository` naming is authority-driven |

### 11.2 Completeness check

Every planned component appears above with a named authority. **No component lacks authority → no STOP required.**

**Finding F-T1 (surfaced by this review, flagged not silently decided).** §6's opening guard reads *"No **active** process exists for this challenge"*, and the ARB's horizon-expiry ruling returns an expired challenge **to Contestation** (which may re-route it — WP-5's path). Both readings agree that uniqueness binds **active** processes, not all processes ever. Consequences taken: (a) the unique index is **partial** (active rows only); (b) `AdjudicationProcessId` is required for row identity; (c) a RED test asserts a new process **may** open after expiry. *If the ARB instead intends one-process-per-challenge-forever, that is a stricter constraint than §6's wording and needs an explicit ruling — the literal reading is implemented and the alternative is recorded here.*

### 11.3 Simplification check — *"can this be deleted while still satisfying the architecture?"*

| Component | Deletable? | Finding / action |
|---|---|---|
| `AdjudicationProcessStatus` | No | Closed state set must be structurally closed (ASP) |
| `AdjudicationProcessState` | No | Deleting it moves guards into the PM where they become bypassable |
| `AdjudicationProcessId` | No | Required by F-T1's active-scoped uniqueness |
| `AdjudicationProcessStore` port | No | Application may not touch Eloquent |
| `AdjudicationProcessManager` | No | PM-1..PM-8 have no other seat |
| Eloquent impl · model · migration | No | Realize the port and INV-B1's second seat |
| **Separate `AdjudicationProcessMapper`** | **YES → CONSOLIDATE** | §11 mandates a *store*, never a mapper. The aggregate-purity motive for `DeterminationMapper`/`ChallengeMapper` does not apply — PM state is orchestration, not an aggregate. **Decision: no mapper class in WP-2**; the store implementation owns translation. Extract only if GREEN shows translation growing (then it is refactoring, not design) |
| New `EvidenceSet` / `ChallengeRef` / `Reason` / authority VOs | **YES → REUSE** | All four already exist in-context (WP-1 delivered `EvidenceSet`). **Only two genuinely new value concepts remain: `AdjudicationProcessId`, `AdjudicationProcessStatus`** |

**Net effect of the check: one planned class deleted (mapper), four VOs reused instead of created.**

### 11.4 Component classification

- **Directly required:** Status · State+guards · Store port · Process Manager · Eloquent store impl · model · migration/partial-unique-index · conclude-time atomicity.
- **Implicitly required:** `AdjudicationProcessId` (F-T1) · `IllegalProcessTransition`.
- **Supporting:** `InMemoryAdjudicationProcessStore` test double · dev guide `developer_guide/adjudication/03_*`.
- **Strictly prohibited — guarded by RED and by review:** saga/compensation (ADR-T8) · any computation of legitimacy or sufficiency (K1/Q-1/ADR-T23) · a domain repository or aggregate for PM state (§11/RMSP) · `FinalizeDetermination*` (Q-2/DMT — WP-6) · any timer→conclusion path (Policy 4) · a seventh state (ASP) · voter↔vote linkage (ADR-T11) · transport wiring (WP-3/WP-4/WP-6).

### 11.5 RED Readiness Statement

```text
Architectural Traceability Review — PASSED

All planned components trace to architectural authority.
No component lacks a constitutional reason to exist.
Simplification check: 1 class deleted (mapper -> store owns translation);
                      4 value objects reused instead of created.
One finding flagged, not silently decided: F-T1 (uniqueness binds ACTIVE
processes; partial unique index + process id + "may reopen after expiry" test).
RED is authorized.

Committed components:  Status · State(+guards) · ProcessId · IllegalProcessTransition ·
                       Store port · ProcessManager · Eloquent store impl · model ·
                       migration (partial unique index)
Reused, not created:   EvidenceSet (WP-1) · ChallengeRef · IssuedByAuthority · Reason ·
                       ClockInterface/FrozenClock
Deferred:              WP-3 (ChallengeRouted published language) · WP-4 (wiring, crash-safe
                       conclude->issue seam, FailureDeclared publication) · WP-6 (timer execution)
Strictly prohibited:   saga/compensation · sufficiency or legitimacy computation · domain
                       repository/aggregate for PM state · FinalizeDetermination* ·
                       timer->conclusion path · 7th state · voter<->vote linkage
```

### 11.6 CORRECTION to §11.3 (self-caught before RED — the mapper)

§11.3 concluded *"no mapper class in WP-2"*. **That overstepped.** Re-reading the authority: the roadmap's WP-2 row names the component list explicitly — *"process-store migration + model (+`BelongsToTenant`) + **mapper** · state machine …"*. EPIC-004K §11 neither mandates nor forbids a mapper (it forbids a *domain repository* and fixes the *store's surface*), so the roadmap — the approved implementation design — governs the component list here.

**Corrected disposition:** the **mapper stays IN WP-2's scope**, as the approved plan names it. The simplification question is not discarded but **recorded for slice review**: if GREEN shows the translation is trivial, consolidating it into the store implementation is a candidate refinement to *propose*, never a unilateral deletion of an approved component.

**Why this correction matters more than the mapper:** deleting a component the approved plan names — on my own reasoning, without a recorded decision — is precisely the architectural drift this review exists to prevent. The simplification check is a source of *proposals*, not of authority. Recorded so the pattern is visible: **a good question does not become a decision by being well-argued.**

*(Net effect on §11.5's readiness statement: `AdjudicationProcessMapper` returns to the committed-components list; the reuse findings — 4 VOs reused, only 2 new — stand unchanged.)*

---

## 12. RED WRITTEN + CONFIRMED (2026-07-30) — STOP at the RED boundary

**25 tests · 25 errors · 0 assertions — every failure is a missing, intentionally-unimplemented component** (`AdjudicationProcessState` · `AdjudicationProcessStatus` · `AdjudicationProcessId` · `IllegalProcessTransition` · `AdjudicationProcessStore` · `AdjudicationProcessManager` · the `adjudication_processes` table). No unexpected failure, no assertion failure masking a design error.

| File | Tests | Covers |
|---|---|---|
| `tests/Unit/.../Process/AdjudicationProcessStateTest.php` | 15 | §6 lawful path (open → assembling → awaiting → each terminal, incl. authority's *not-yet-decide*) · **exactly one conclusion of exactly one kind** · **no admission after conclusion** · illegal transition leaves state untouched · **PM-5 conclude-time fixation** (considered-set + authority together) · **horizon → Expired** · **expiry never concludes (Policy 4)** · late decision refused · concluded cannot expire · **state set is exactly six (ASP)** |
| `tests/Unit/.../Process/AdjudicationProcessManagerTest.php` | 5 | **PM-1** one active process per challenge · **KEYSTONE exactly-once conclusion under redelivery** (duplicate = idempotent no-op, Governance-precedent terminal guard) · conflicting late decision does not replace the conclusion · PM-4 records the deciding authority |
| `tests/Feature/.../AdjudicationProcessUniquenessTest.php` | 5 | persistence + load-for-reaction · **KEYSTONE unique-active-per-challenge at the DB seam** · **F-T1 a new process may open after expiry** · terminal never returned as active · tenant scoping |
| `tests/Support/Adjudication/InMemoryAdjudicationProcessStore.php` | — | store double; records writes so "exactly one conclusion" is assertable |

**Design proposals the RED encodes** (RED is where the API is proposed — all authority-traceable): immutable state (Governance PM precedent ⇒ "no mutation on illegal transition" becomes structural) · store surface of 3 methods covering §11's 5 named operations (`activeForChallenge` · `save` · `dueForHorizon`) — RMSP spirit, no query zoo · duplicate decision = idempotent no-op, matching inbox replay semantics rather than throwing.

**Progress:** ✔ authority · ✔ domain · ✔ strategic DDD · ✔ business-model fidelity · ✔ tactical · ✔ traceability review (+ self-caught mapper correction) · ✔ **RED confirmed** · ⏳ GREEN (awaiting report acceptance) · ⏳ gates · ⏳ dev guide · ⏳ ARB slice acceptance.
