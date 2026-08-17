# `EM-IMPL-001` — First Bounded Increment: Implementation Completion Report

**Type:** Implementation completion evidence (Implementation lane — Session 3) · **Date:** 2026-08-17
**Authorization:** PO signature received in-session, fixing the boundary exactly as read by the readiness report (`2026-08-17-EM-IMPL-001-readiness-report.md` §B): *"the pure-PHP, framework-free domain core comprising AG-1, AG-2, AG-3, their constituent value objects, P-1…P-7, the four derived condition types, the ten non-canonical domain-event types, and the domain-owned port and repository interfaces listed in the report. No application layer, adapters, migrations, Eloquent models, controllers, schedulers, lifecycle wiring, technology selection, or implementation of unresolved governance behaviour is authorized."*
**⛔ This lane supplies evidence and does not accept its own work (C-5; EP-02/R-34). Acceptance, the C-4 commits, and independent verification are separate acts.**

## 1 · What was built

**Production:** `app/Contexts/Election/Domain/OperatingCore/` — **56 pure-PHP files**, zero framework imports (structurally tested). Namespace decision made visibly at START: a new subtree inside the Election context's Domain layer, beside and never touching the existing determination/correction subsystem.

| Package | Contents |
|---|---|
| `Committee/` | **AG-1 `ElectionCommittee`** (I-1…I-6; derived `unableToFunction`, never stored) · `CommitteeSeat` entity · `CommitteeSeatId` · `VacancyGround` (closed 3-ground set, `064`) · `VacancyReason` (ADR-T11 constrained surface) |
| `Gate/` | **AG-2 `AcceptanceGateDecision`** (I-7…I-12; subject abstract per D-3; state changed ONLY by applying `CommitteePositionExpressed` facts; reconstitutable via `fromRecordedFacts` — B-7; interval state derived against the trusted `ElectionCommittee` collaborator only) · `GateDesignation` (First/Second, order only) · `AcceptancePosition` (accept·object, closed — D-9) · `ThresholdRule` (named token, adopted `036` only — D-6) · `RequiredVotes` (⌈2n/3⌉, `036`/`038`) |
| `Recovery/` | **AG-3 `RecoveryProcess`** (I-13…I-16; DD-1 interval clocks; expiry is a question, never an event) · `PeriodKind` (two kinds, never merged — `059`(a)) · `PolicyBinding` (version+duration bound at start — `050`(b)) · `ClockReading` (computed) |
| `Condition/` | `GateIntervalState` (Open/DecidedPass/DecidedFailure/Unachievable — closed, `068`) · `OperationalCondition` (Operative/Inoperative) · `HaltedAtGate` · `ElectionOperationalStatus` (HALTED ∧ INOPERATIVE representable — `059`(b)) · `TerminalStatePlaceholder` (renders only **Election Discontinued** — `EM-GOV-069`) · `ElectionLevelCancellation` (distinct type — D-9/B-4) |
| `Policy/` | **P-1…P-7** exactly per design §2e, all stateless |
| `Event/` | `NonCanonicalEventName` marker (D-7) + **11 placeholder event types** for the §5f recorded facts, all `final readonly`, ADR-T11-clean |
| `Port/` | `ProtocolAppend` + `ProtocolEntry`/`RefusalRecord`/`HistoryKind` (F-PROTO-1 properties in the contract; P-2H two histories; refusal recordable — property 6) · `ServicePolicySnapshot` (B-5 ACL: `(version, duration)` only) · **`OrganisationalAppointmentAuthority` — declared, no operations, NO adapter (D-1 wall)** · `InstantSource` (recording instants only — D-8) |
| `Repository/` | interfaces for AG-1/2/3 only (repo Rule 9); derived classifications never persisted as authoritative columns |
| `Exception/`, `Time/` | 6 domain exceptions · `RecordedInstant` (epoch arithmetic, no civil-time meaning — D-8) |

**Tests:** `tests/Unit/Contexts/Election/OperatingCore/` — **7 files, 42 tests, 2420 assertions**, pure `PHPUnit\Framework\TestCase`, no DB, no framework; protocol contract exercised against an in-memory double (test doubles are the increment's ceiling — G-6).

## 2 · TDD evidence (C-4)

RED proven before any production file existed: first run = 41 tests, 40 errors + 1 failure (missing classes / undeclared port). GREEN after implementation: `OK (41 tests, 2412 assertions)`; final state after review corrections: **`OK (42 tests, 2420 assertions)`**. RED and GREEN are currently uncommitted working-tree state — **the two-commit sequence (tests first, then production) awaits the PO's commit instruction** so ordering remains provable in history.

## 3 · Regression evidence

Baseline established by removing the increment and re-running: Unit suite 3351 tests / ~470 errors / 71 failures WITHOUT the increment; 3392 / same errors / same failures WITH it (all pre-existing, zero mentions of OperatingCore in any failure). Architecture suite: exactly 2 failures with and without (pre-existing, legacy `ElectionStateMachineConsistencyTest`). **The increment adds 42 passing tests and breaks nothing.**

## 4 · Review cycle — corrections applied

All PO/architect review verdicts were dispositioned in-session:

1. **`StructuralGuardsTest`** (3 required corrections, applied): D-1 guard detects implementations/infrastructure-bindings only, not textual references · `InstantSource` removed from the OPEN time guard (mechanisms only: Deadline/Timeout/Scheduler/Cron/ExpiryHook/OpenGateExpiry) · event-count freeze removed (per-event rule verification, no frozen inventory).
2. **`ElectionBecameInoperative`** (applied): `causedBySeat` removed; payload = `electionId` + `onset`; wording recast as the derived condition becoming true at the breaching event's recorded instant (G-1/G-4). *Recorded note:* adopted `065`'s own text says "at the recorded vacancy event **causing it**" — the revision remains conformant because the onset instant preserves the rule.
3. **`AcceptanceGateDecision`** (2 blocking corrections, applied): `intervalState(ElectionCommittee)` — trusted collaborator with election-identity and denominator guards, arbitrary vacancy arrays unrepresentable · every state change routed through `apply(CommitteePositionExpressed)`; `fromRecordedFacts` reconstitution added and tested (facts are the source of truth — B-7).
4. **`RecoveryProcess`** (approved; requested check performed): grep of the whole operating core shows no system-clock read anywhere — the only `now()` is the `InstantSource` port *declaration*, unimplemented. `private bool $active` deliberately narrow (accruing/paused only); expired/completed/cancelled remain questions/consequences outside the aggregate — recorded as a do-not-expand note.
5. **`UnableToFunction`** (approved): single production caller (`ElectionCommittee::unableToFunction`); no lifecycle mutation reachable from it.

## 5 · Review point REJECTED with evidence (open for verification/Governance)

**`GateIntervalClassification` reordering request (DecidedFailure vs Unachievable) — not applied.** The requested tree (Unachievable checked first / DecidedFailure only when all seats expressed) misclassifies the canonical R-F2 decided failure: 3 seats, **no vacancies**, two objections, one seat unexpressed ⇒ it would return Unachievable, contradicting `EM-GOV-068` and the two-reader-confirmed EM-BRQ-001 §1 derivation (*"2 objections = decided failure"*). The implemented condition is provably vacancy-proof: `accepts + unexpressedAll = constituted − objections`, so `DecidedFailure ⟺ objections > constituted − required` — a pure function of objections; vacancy arithmetic yields `Unachievable` (recoverable: filling returns the gate to OPEN, `059`(c)). **Accepted from the review:** the misleading comment was rewritten with the algebra; the disputed semantics are pinned executable in `test_decided_failure_is_a_pure_function_of_objections_never_of_vacancies` (canonical case + both reviewer examples + occupy-the-vacancy proof pair). If the PO still reads the corpus as requiring the reordering, that is a **Governance question about `EM-GOV-068`'s wording**, not an implementation edit.

## 6 · Observations queued for independent verification (C-5)

* **OBS-1 — §2b commentary edge:** design §2b claims `OPEN ∧ INOPERATIVE` cannot arise; under adopted arithmetic it can when a cast accept stands (`066`) and later vacancies breach `065` while the standing accept keeps `068`-achievability alive. Both derivations are implemented exactly as adopted; no invariant enforces the commentary. Falsifies only the commentary, not either rule.
* **OBS-2 — the §5 classification dispute**, pinned by test, awaiting the verifier's independent reading.
* **OBS-3 — `065` causal wording** vs the seat-free `ElectionBecameInoperative` payload (conformant; noted for the record).

## 7 · Boundary conformance — what was deliberately NOT done

No application layer, no adapter of any kind (**including none for `OrganisationalAppointmentAuthority` — structurally tested**), no migrations, no Eloquent models, no controllers, no scheduler/timer/expiry hook, no concrete first-gate binding (D-3), no bound on OPEN (D-4), no C-2 classifier (D-5), no menu capability (D-6), no canonical event vocabulary (D-7), no civil-time semantics (D-8), no abstention and no merged *cancelled* levels (D-9), no lifecycle wiring to `ElectionConstitution`, no technology selection (G-6), **no commits, no self-certification**.

## 8 · Next acts (not this lane's to perform)

① PO instruction → two C-4 commits (tests, then production, `EM-IMPL-001` in the subjects) · ② C-5 independent verification lane, seeded with OBS-1…OBS-3 · ③ PO/ARB acceptance on the verifier's evidence · ④ later increments (application layer, adapters, persistence) each need their own authorization.

**Traceability.** Signed EM-IMPL-001 (PO, in-session, 2026-08-17) · readiness report §B boundary · EM-ARCH-001 approved design · D-1…D-9 reconciliation · Election Manifesto ADOPTED rows · EM-GOV-069 · ADR-T11 · F-PROTO-1 · P-2H · in-session review corrections 1–5 · A-3.
