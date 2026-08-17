# `EM-ARCH-002` — Increment-2 Architectural Proposal v1.0: Application Layer for the Election Operating Core

**Type:** Architecture proposal (Increment-2 preparation) · **Date:** 2026-08-17
**Status:** 🟡 **PROPOSED — NOT approved, NOT authorized.** This document is the boundary presentation for Increment 2. Implementation, technology selection, persistence and deployment remain NOT authorized; the sequence fixed by the PO stands: *proposal → boundary → grant → START → RED*.
**Baseline consumed:** `EM-IMPL-001` — Architecture `BASELINED` · Implementation `ACCEPTED` · Verification `INDEPENDENTLY VERIFIED` (`docs/publicdigit/implementation/2026-08-17-EM-IMPL-001-baseline-freeze.md`). The domain core (56 files @ `a31f54f1`) is the authoritative baseline; **this proposal changes none of it.**
**Rule corpus:** `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` — ADOPTED rows only are rules; `EM-GOV-070`/`071` (Reading A, Meaning-1/Meaning-2 precision) adopted 2026-08-17; `EM-GOV-069` names the terminal rendering *Election Discontinued*.
**Parent design:** `docs/publicdigit/architecture/2026-08-17-EM-ARCH-001-model-a-operating-core-design.md` — §2b read as CORRECTED (registration `…-s2b-correction-registration.md`).

> ## Reading contract
>
> **Scope is the Application Layer introduction only** — the orchestration shell around the frozen Model A domain core. The domain model is **not** redesigned; every domain element cited below exists in the baseline exactly as cited.
> **All command, query and handler names are ILLUSTRATIVE PLACEHOLDERS** (`EM-OPEN-045` standing principle — an illustration is not a rule). The recorded-fact vocabulary remains non-canonical (`NonCanonicalEventName`).
> **Every unresolved governance wall is a NAMED DEPENDENCY (§7), never an assumption.** Where an orchestration branch would need an unruled answer, the branch stops and the dependency is recorded.
> **The D-1 port (`OrganisationalAppointmentAuthority`) stays operation-less and adapterless.** §5 *describes* where a future adapter would sit; nothing here designs one.

---

## 1 · Objective, scope, non-goals

### 1a · Objective

Introduce the **Application Layer** of the Election Operating Core: thin, framework-free orchestration that exposes the frozen domain core's governed acts as **one command handler per governed act** and **derivation-only queries**, coordinating aggregates, recorded facts and the protocol port — while calculating **no** rule, deciding **no** authority question, and storing **no** derived classification.

### 1b · Scope (exactly)

| In scope | Out of scope (non-goals) |
|---|---|
| Application services (command handlers), one governed act each | any change to `app/Contexts/Election/Domain/OperatingCore/` (frozen baseline) |
| Command DTOs (readonly, no arrays — repo Rule 4) | controllers, routes, HTTP, any Interface-layer artifact |
| Query services (derivation-only reads over the aggregates via domain policies) | persistence adapters, protocol-store technology, migrations |
| Refusal recording at the application boundary (F-PROTO-1 property 6) | any Laravel class, facade, or framework dependency (Application layer: Facades ❌, Eloquent ❌ — repo standing rules) |
| Orchestration of consequence facts (Inoperative onset, clock pause/resume, period start) via existing domain policies | any adapter/binding/operation for the D-1 port |
| Placement: `app/Contexts/Election/Application/OperatingCore/` (namespace exists in the context skeleton; described here, created only after grant) | schedulers, timers, notification duties (`EM-GOV-067` expressly declined one), identity/authentication management |
| | lifecycle transitions (Constitution-homed — Manifesto §1/§7); progression remains the Chief's, outside this subsystem |

### 1c · Bounded contexts touched

| Context | How touched | Change to context map |
|---|---|---|
| **Election** (owner) | new Application sub-layer inside the existing context; the OperatingCore subsystem gains its orchestration shell | none — ownership unchanged |
| **Service Policy** (external) | consumed unchanged through the existing `ServicePolicySnapshot` ACL port (B-5): `(policyVersion, duration)` only, never a consequence | none |
| **Organisational Governance** (external, undefined) | untouched — the D-1 wall stands; the port remains operation-less, adapterless | none |

No Published-Language interaction changes; no ownership moves; the context map of `EM-ARCH-001` §5a is consumed as-is.

---

## 2 · Application use cases required to expose the existing domain

The approved design already fixed the driving ports (`EM-ARCH-001` §5c). The application layer realizes **exactly those**, plus two lifecycle-of-aggregate entry points it cannot avoid naming (flagged as boundary decisions, §7 M-1/M-2 — not assumed approved).

### 2a · Commands (state-changing governed acts — each recorded whether it succeeds or is refused, `EM-GOV-005`, F-PROTO-1 property 6)

| # | Use case (placeholder name) | Domain act exposed | Actor wall |
|---|---|---|---|
| **UC-1** | `ExpressCommitteePosition` (seat, accept\|object) | `AcceptanceGateDecision::expressPosition()` — I-7/I-8/I-9/I-12 | seat occupant (Committee member); identity arrives from upstream (§5 A-4) |
| **UC-2** | `RecordVacancyEvent` (seat, ground, reason) | `ElectionCommittee::recordVacancy()` — I-2; consequences via P-3/P-4 (Inoperative onset), clock pause (`060`), restoration-period start/resume (`062`) | recording authority not fixed by adopted text — named dependency (§7 M-4) |
| **UC-3** | `FillCommitteeSeat` (seat, appointee) | `ElectionCommittee::fillSeat()` — I-4/I-5; consequences via restoration derivation (`058`/`059`(c)), clock swap (`062`), P-7 resumption target | **exclusively the external organisational authority's future D-1 adapter; no internal caller exists** (`028`, `056`, B-3) |
| **UC-4** | `ReportPeriodExpiry` (period kind) | `ExpiryConsequence::onHaltedRecoveryExpiry()` / `::onRestorationExpiry()` (P-6) | service side **reports** only (`EM-OPEN-047` resolution); the rule alone supplies the consequence; the reporter's technology is a later increment |
| **UC-5** *(boundary decision M-1)* | `RecordCommitteeConstitution` (seat ids) | `ElectionCommittee::constitute()` — I-1 | same D-1 wall as UC-3: constitution facts arrive only from the external authority (`026`, `028`; R-F4) |
| **UC-6** *(boundary decision M-2)* | `EstablishGateDecision` (gate designation) | `AcceptanceGateDecision::establish()` | driven by the Constitution-homed lifecycle reaching the gate (`010`/`011`); the trigger's integration point is a named decision, not designed here |

UC-5/UC-6 are **not** in the §5c driving-port list; they are the aggregate-lifecycle entry points without which no repository-held aggregate can come into existence. They add **no domain behaviour** — each merely records an act the domain already models. They enter Increment 2 **only if the PO confirms them at the boundary**; otherwise Increment-2 tests construct aggregates directly, as EM-IMPL-001's tests do.

### 2b · Queries (derivation-only — record nothing, store nothing, decide nothing)

| # | Query (placeholder name) | Derivation used |
|---|---|---|
| **UQ-1** | `GateIntervalStateQuery` | `AcceptanceGateDecision::intervalState(ElectionCommittee)` → P-2 (`068`; I-11 trusted-collaborator check preserved) |
| **UQ-2** | `ProgressionEligibilityQuery` — *"can progression continue?"* | operational condition (P-3 arithmetic over AG-1) ∧ gate classification (P-2). **This is the exact question the adoption registration permits the application layer to ask** (`EM-GOV-070`/`071` registration §2). It returns an answer; it never performs, blocks or schedules a transition |
| **UQ-3** | `ClockReadingQuery` (period kind, at instant) | `RecoveryProcess::readingAt()` — DD-1 computation over recorded intervals |
| **UQ-4** | `RecoveryPeriodStatusQuery` (period kind) | `RecoveryProcess::isExpiredAt()` + `PolicyBinding` — expiry as a **question** (`047` resolution); asking is not reporting: no consequence flows from a query |

Nothing else. In particular: **no query exposes "unable to function" as a standalone verdict surface** detached from its arithmetic inputs — it is derivable through UQ-2's composition, and publishing it separately would invite the forbidden shape (§6 W-1).

---

## 3 · Application Layer boundary

### 3a · The elements

| Element | Content | Placement |
|---|---|---|
| **Commands** | `final readonly` DTOs: `ExpressCommitteePositionCommand` (electionId, gate, seatId, position, — instant supplied by `InstantSource`, never by the caller), `RecordVacancyEventCommand`, `FillCommitteeSeatCommand`, `ReportPeriodExpiryCommand` (+ M-1/M-2 if confirmed). No arrays (repo Rule 4). No civil-time fields (D-8) | `Application/OperatingCore/Command/` |
| **Application services** | one handler per command; constructor-injected ports only (no facades, no Eloquent — repo layer rules); each executes **one governed act**, appends its recorded fact(s), and records refusals | `Application/OperatingCore/Handler/` |
| **Queries** | UQ-1…UQ-4 as read services calling domain policies; **zero** arithmetic of their own | `Application/OperatingCore/Query/` |
| **Domain services** | **none new.** P-1…P-7 already exist in the baseline and stay in Domain. The application layer *calls* them; it never re-implements or partially inlines them | frozen — `Domain/OperatingCore/Policy/` |
| **Ports consumed** | the three repositories (AG-1/2/3) · `ProtocolAppend` · `ServicePolicySnapshot` · `InstantSource` | all exist in the baseline; **no new port is introduced** |
| **Ports NOT consumed** | `OrganisationalAppointmentAuthority` — the application layer neither calls it nor implements it; it stays operation-less (D-1). UC-3/UC-5 handlers are the *entry points a future adapter would call* — they never call *out* to the authority | — |

### 3b · What the application layer is, in one sentence

**A recorder-coordinator:** it loads aggregates through repository interfaces, invokes exactly one domain act, hands the resulting recorded fact(s) to `ProtocolAppend` with the correct `HistoryKind`, saves the projection, and — where the domain's own policies derive a consequence — orchestrates the consequent governed acts as further recorded facts. It computes nothing, decides nothing, times nothing.

---

## 4 · Transaction boundaries

**Coordinator: the application handler — always.** No aggregate coordinates another; no domain policy opens a transaction; no infrastructure component initiates a governed act.

**The atomic unit is: one aggregate mutation + the protocol append of its recorded fact(s) + the aggregate save** (repo standing rule: transaction boundary = one aggregate per command; `EM-ARCH-001` §5b). The recorded fact is the truth (I-8, B-7): handlers follow the domain's own ordering — the fact is produced by the domain act, appended, and the state saved as its projection.

**Cross-aggregate consequences are separate acts, not one large transaction.** They are safe to sequence because of DD-1: every clock is a computation over **recorded instants**, and every onset/pause anchors to **the causing event's own instant** (P-4: onset = the vacancy event's instant, never the evaluation's; `060`: pause from the recorded moment, non-retroactive). Coordination latency between the primary act and its consequence acts therefore **cannot distort any clock or classification** — the derived model absorbs eventual consistency by construction. The dual-write consistency of protocol-append vs aggregate-save is an **infrastructure decision deferred to the persistence increment** (ADR candidate A-1); in Increment 2 both sides are in-memory test doubles and the question cannot yet be answered honestly.

Per use case:

| Use case | Atomic (one transaction) | Consequent acts (separate, same handler orchestrates) | Recorded | Domain-owned throughout |
|---|---|---|---|---|
| **UC-1** Express position | AG-2 mutation + `CommitteePositionExpressed` append + save | on derived `DecidedFailure` (P-2): append `GateFailedByDecision`; start AG-3 `HaltedElectionRecovery` with `ServicePolicySnapshot` binding (`050`(b)) + `RecoveryPeriodStarted`; on derived `DecidedPass`: append `GateSatisfied` — **and nothing more: no phase advances (B-1)** | position fact (ProgressionDecision history); outcome facts; dissent recorded even on pass (I-12) | I-7…I-12; P-1/P-2 classification; the decision itself |
| **UC-2** Record vacancy | AG-1 mutation + `CommitteeSeatVacated` append + save | if P-4 yields an onset: append `ElectionBecameInoperative` (onset = the event's instant); pause any accruing halted-recovery AG-3 at that instant (`060`); start `CommitteeRestoration` AG-3 **iff none exists for the election** (I-15 — one per-election allowance, `061`(b)) else `resume()` it | vacancy fact (ground + reason — ADR-T11-constrained surface); Inoperative onset; period start with `PolicyBinding` | I-2/I-3; P-3/P-4 arithmetic; ground closure |
| **UC-3** Fill seat | AG-1 mutation + `CommitteeSeatFilled` append + save | if non-vacant ≥ required again: pause restoration AG-3, append `ElectionRestored`; resume a paused halted-recovery AG-3 with its **remaining** portion (`061`(a)); resumption target = the unresolved gate via P-7 — **restored ability to decide, never a deemed decision** (`059`(c)) | fill fact; restoration fact | I-4/I-5; P-3; P-7 |
| **UC-4** Report expiry | **no aggregate mutation** — P-6 evaluates preconditions (three facts, not one — `063`; kind checks; the Inoperative guard) and returns the consequence event; handler appends it (Lifecycle history) | none | `RecoveryPeriodExpired` (expiry + failed recovery + resulting state — `063`'s one new recording obligation; rendering *Election Discontinued*, `069`) or `ElectionCancelledOnRestorationExpiry` (`058`) | the entire consequence — P-6 is the only place the answer exists |
| **any, on domain refusal** | append `ProtocolEntry::refusal(...)` with act + reason (property 6) — recordable independently of any state success | none | the refusal | the refusal's *reason* is the domain exception's business message (repo Rule 8: Domain/Application exceptions are user-visible) |

**What is never inside any transaction:** a lifecycle progression (Chief's, Constitution-homed) · a stored derived classification (`065`/`068`, DD-1) · a second history (P-2H: the handler sets `HistoryKind` per fact; lifecycle facts and progression-decision facts are never merged) · an event for a phase that never occurred (`005`, negative half).

---

## 5 · Authorization boundaries

### 5a · Principles (adopted text, restated as walls)

* **A-1 · A condition never becomes an actor** (registered with the `070`/`071` adoption). No derived state — `unableToFunction`, `Unachievable`, Inoperative, expiry — ever *initiates* anything. Every governed act has a requesting actor or a reporting party; derived conditions are only ever *inputs to rules*.
* **A-2 · The application layer authenticates nobody and authorizes nothing.** It receives actor references from upstream (Interface layer, future increment) and enforces *structural* walls only (a seat that already expressed cannot express again — that is domain I-7, not authorization). WHO may hold a seat, WHO may record a vacancy, WHO speaks for the external authority — all upstream or external (§7).
* **A-3 · The external authority's acts have no internal caller** (`028`, `056`, B-3, D-1). UC-3/UC-5 handlers exist as entry points; **nothing inside the codebase invokes them**. A future D-1 adapter (Infrastructure, driving side — *described, not designed*: it would translate the organisation's governance vocabulary into `FillCommitteeSeatCommand`/`RecordCommitteeConstitutionCommand` calls) is the only legitimate caller, and building it is blocked until `EM-OPEN-049`/`066`/`094` resolve externally. The two external authorities (Committee/Deputy-appointing vs Chief-appointing) are never presumed the same body.
* **A-4 · Reporting is not authority** (`047` resolution, `EM-VOT-004`, `006`). The expiry reporter triggers an *evaluation*; P-6's preconditions can refuse it, and the refusal is recorded. No clock-driven mechanism progresses anything or exercises anyone's authority.
* **A-5 · What the application layer must NEVER decide:** close/fail a gate from Committee incapability (wrong BY ADOPTED RULE `070`/`071`) · invalidate or suppress a recorded position (`066`: a cast vote stands) · block position expression while Inoperative (Reading A / Meaning-1: decision FACTS are recordable while Inoperative; only the PROGRESSION TRANSITION defers — Meaning-2) · classify a halt as unsatisfiable-in-fact (D-5, `EM-OPEN-109`: no such classifier exists) · progress the lifecycle · supply a consequence to an expiry · render the terminal state as anything but *Election Discontinued* (`069`, PO meaning boundary) · treat opportunity-level and election-level *cancelled* as one value (D-9, B-4).

### 5b · Authorization Matrix

| Operation | Actor (may request) | Application entry point | Domain validation | Recorded fact | Outcome |
|---|---|---|---|---|---|
| Express committee position | seat occupant (Committee member) — identity from upstream; **permitted also while Inoperative** (Meaning-1) | `ExpressCommitteePositionHandler` | I-7 one position per seat · I-9 replacement only where seat silent · gate/election match (B-7 guards) | `CommitteePositionExpressed` (ProgressionDecision) | position stands (I-8); state re-derived; on pass/failure the outcome fact is appended; **a decision completed while Inoperative takes effect on progression only when operative again** (`071`) |
| Record vacancy event | recording authority **not fixed by adopted text** → named dependency M-4; shape validated regardless | `RecordVacancyEventHandler` | I-2 closed grounds; resignation requires reason (`064`); seat not already vacant | `CommitteeSeatVacated`; `ElectionBecameInoperative` iff P-4 onset | seat vacant; denominator unchanged (I-3); possible Inoperative onset at the event's instant; clock pause/start per §4 |
| Fill committee seat | **external organisational authority only**, via future D-1 adapter — no internal caller | `FillCommitteeSeatHandler` | seat must be vacant (`SeatNotVacant`); appointee reference non-blank; I-4 existing seat re-occupied | `CommitteeSeatFilled`; `ElectionRestored` iff sufficiency returns | seat filled; no decision altered (I-4); replacement expresses only where the seat is silent (I-9); restoration returns to the **unresolved gate** (P-7) |
| Record committee constitution *(M-1)* | **external organisational authority only**, via future D-1 adapter | `RecordCommitteeConstitutionHandler` | I-1 size ≥ 3; no duplicate seats | constitution fact (placeholder — `EM-OPEN-045`) | AG-1 exists; denominator fixed forever (I-3) |
| Establish gate decision *(M-2)* | Constitution-homed lifecycle reaching the gate (`010`/`011`) — integration point is decision M-2 | `EstablishGateDecisionHandler` | denominator ≥ 3; one decision per gate per election (I-15-analogue, repository contract) | establishment fact (placeholder) | AG-2 exists, subject abstract (D-3) |
| Report period expiry | service-side reporter (Infrastructure, later increment) — **reports only** | `ReportPeriodExpiryHandler` | P-6: correct period kind · not while Inoperative (halted branch) · recovery not succeeded · genuinely expired on recorded intervals — each violation refused and recorded | `RecoveryPeriodExpired` → *Election Discontinued*, or `ElectionCancelledOnRestorationExpiry` (Lifecycle history) | terminal / election-level cancellation — **the rule's consequence, never the clock's or the reporter's** |
| Gate state (UQ-1) / progression eligibility (UQ-2) / clock reading (UQ-3) / period status (UQ-4) | any authorized reader (read authorization is Interface-layer, later) | query services | derivations only (P-2/P-3, DD-1) with the baseline's trusted-collaborator guards | **nothing** — queries record nothing | an answer; never an act |

---

## 6 · Anti-corruption boundaries

| External system | Boundary | Increment-2 treatment |
|---|---|---|
| **Organisational appointment authority** (Committee/Deputy) | D-1 port, operation-less, adapterless (B-3) | untouched. A future adapter would sit in `Infrastructure/` on the **driving** side, translating organisational-governance vocabulary into UC-3/UC-5 commands — described only; designing it is blocked by `EM-OPEN-049`/`066`/`094`. The Chief-appointing authority is additionally unidentified even in form; no shared-body assumption |
| **Policy provider** (service-policy context) | `ServicePolicySnapshot` ACL (B-5) | consumed as-is: `(policyVersion, duration)` at period start, captured into `PolicyBinding` (`050`(b)); the provider's vocabulary never enters the domain; the provider can never supply a consequence (`058` safeguard) |
| **Protocol storage** | `ProtocolAppend` port; F-PROTO-1 properties are the contract, machinery unprescribed (G-6) | application appends through the interface only; store technology is the persistence increment's own authorized decision |
| **Identity / authentication** | none exists in this subsystem — actor identity is upstream | the application layer accepts opaque actor/appointee **references** (strings/ids), never identities it verifies; mapping authenticated principals to Committee seats is an Interface-layer + governance question (named dependency M-5). ADR-T11 binds every reference surface: no voter↔vote linkage can enter any command, fact or refusal |

---

## 7 · Forbidden application patterns · named dependencies

### 6a · Forbidden shapes (each wrong by adopted rule or baseline prohibition — a test in RED should pin each)

| # | WRONG | Why |
|---|---|---|
| **W-1** | `if ($committee->unableToFunction(...)) { /* fail/close the gate */ }` | the canonical forbidden shape — wrong BY ADOPTED RULE (`070`/`071`): gate state and election condition are independent; a derived condition may never become an authority decision |
| **W-2** | `if ($inoperative) { /* refuse or delete positions/decisions */ }` | `066`: a cast vote stands; Meaning-1: decision FACTS are recordable while Inoperative — a guard here would be Reading B, which the PO did not choose |
| **W-3** | handler computes `⌈2n/3⌉`, re-derives achievability, or inlines any P-1…P-7 arithmetic | rule arithmetic lives in Domain policies only (repo layer rule; `EM-ARCH-001` §5b: Application may not hold rule arithmetic) |
| **W-4** | storing `GateIntervalState`, `unableToFunction`, or a clock reading as an authoritative column/field | `065`/`068`, DD-1 — a stored classification is the determiner the rules exclude, a second truth that drifts |
| **W-5** | any deadline, timeout, scheduler, polling loop or expiry hook attached to an OPEN gate or to inaction | `068`, G-3, D-4; `EM-OPEN-053` is tolerated-unbounded by PO classification |
| **W-6** | handler advances a phase after `GateSatisfied`, or auto-progresses on restoration | B-1: the gate decides acceptance, never progression; progression is the Chief's (`EM-VOT-004`); Meaning-2 defers effect until operative |
| **W-7** | inventing an escalation, notification duty, or default authority when the external authority is silent | `067` expressly declined the duty; `EM-OPEN-049`: no default authority may be invented merely because Election Governance needs one |
| **W-8** | treating `Unachievable` as failure, or `Election Cancelled` / `Election Discontinued` / opportunity-`cancelled` as interchangeable | `059`(c) restoration returns Unachievable→OPEN; `069` + D-9/B-4 keep the three end-vocabularies distinct types |
| **W-9** | a handler catching a domain refusal and retrying/absorbing it silently | `005` + property 6: refusals and their reasons are material events — recorded, never smoothed over |
| **W-10** | implementing, stubbing, faking or "temporarily" binding the D-1 port — anywhere, including tests of the application layer | baseline prohibition; the wall is the design |

### 7b · Named dependencies (walls consumed, never assumptions)

| # | Dependency | Effect on Increment 2 |
|---|---|---|
| **D-1** (`EM-OPEN-049`/`066`/`094`) | external authorities unresolved | UC-3/UC-5 handlers have no legitimate caller until resolution; they are exercised only by tests standing in for the future adapter's *position in the flow*, never by a stub of the authority itself |
| **D-3** (`EM-OPEN-054` Q3 · `055`) | gate subject abstract | commands carry `GateDesignation` (first/second) only; no subject binding |
| **D-4** (`EM-OPEN-053`) | OPEN unbounded, tolerated | no application element observes duration of OPEN |
| **D-5** (`EM-OPEN-109`) | no C-2 classifier | no entry point can convert waiting into impossibility |
| **D-6** (`EM-OPEN-077`/`076`) | threshold menu unadopted | `ThresholdRule::twoThirdsOfCommitteeVotes()` consumed directly (`036`); no configuration surface |
| **D-7** (`EM-OPEN-045`) | event vocabulary non-canonical | command/handler names are placeholders with the same standing |
| **D-8** (`EM-OPEN-024`) | civil-time semantics open | commands carry no caller-supplied timestamps; instants come from `InstantSource` as recording instants only |

---

## 8 · Sequence diagrams (illustrative; names placeholder — `EM-OPEN-045`)

### 8a · UC-1 Express position → decided failure → halted-recovery period (F-2)

```
Member (via seat)   Handler                AG-2            P-2/P-1        AG-3        ServicePolicy   Protocol
      |  command      |                     |                 |             |               |            |
      |──────────────▶| find(AG-2, AG-1)    |                 |             |               |            |
      |               |──expressPosition───▶|                 |             |               |            |
      |               |◀──fact: PositionExpressed             |             |               |            |
      |               |──append(fact, ProgressionDecision)───────────────────────────────────────────────▶|
      |               |──save(AG-2)         |                 |             |               |            |
      |               |──intervalState(committee)──────────▶ classify       |               |            |
      |               |◀─────────────── DecidedFailure ───────|             |               |            |
      |               |──append(GateFailedByDecision)────────────────────────────────────────────────────▶|
      |               |            (separate act — §4)        |             |               |            |
      |               |──snapshotFor(HaltedElectionRecovery)─────────────────────────────▶ (version,dur)  |
      |               |──RecoveryProcess::start(binding, onset=halt instant)─▶|            |              |
      |               |──append(RecoveryPeriodStarted)───────────────────────────────────────────────────▶|
      |               |──save(AG-3)         |                 |             |               |            |
      |◀── outcome ───|   NOTHING ELSE: no phase advances (B-1); progression stays the Chief's
```

### 8b · UC-2 Record vacancy → Inoperative onset (F-5)

```
Requester        Handler               AG-1           P-3/P-4          AG-3(halted)   AG-3(restor.)   Protocol
    | command       |                    |                |                 |               |            |
    |──────────────▶| find(AG-1)         |                |                 |               |            |
    |               |──recordVacancy────▶|                |                 |               |            |
    |               |◀─fact: SeatVacated(instant t)       |                 |               |            |
    |               |──append(fact)──────────────────────────────────────────────────────────────────────▶|
    |               |──save(AG-1)        |                |                 |               |            |
    |               |──onsetFor(committee, required, t)──▶| onset = t (or null)             |            |
    |               |   [onset ≠ null:]  |                |                 |               |            |
    |               |──append(ElectionBecameInoperative @ t)─────────────────────────────────────────────▶|
    |               |──pause(@ t) — non-retroactive (060)────────────────▶ |               |            |
    |               |──start-or-resume restoration period (I-15: one allowance/election)──▶|            |
    |               |──append(RecoveryPeriodStarted) [first time only]──────────────────────────────────▶|
    |               |──save(AG-3 …)      |                |                 |               |            |
    |◀── outcome ───|  Gate stays OPEN if a standing accept keeps the threshold achievable —
                       OPEN ∧ INOPERATIVE is valid (070); positions remain expressible (071/Meaning-1)
```

### 8c · UC-3 Fill seat → restoration (F-6) — caller: the future D-1 adapter only

```
[future D-1 adapter]  Handler            AG-1          P-3/P-7        AG-3(restor.)  AG-3(halted)   Protocol
        | command        |                 |               |                |              |            |
        |───────────────▶| find(AG-1)      |               |                |              |            |
        |                |──fillSeat──────▶|               |                |              |            |
        |                |◀─fact: SeatFilled(@ t)          |                |              |            |
        |                |──append(fact)───────────────────────────────────────────────────────────────▶|
        |                |──save(AG-1)     |               |                |              |            |
        |                |──unableToFunction?─────────────▶| false (restored)              |            |
        |                |──pause(@ t)────────────────────────────────────▶|              |            |
        |                |──append(ElectionRestored)───────────────────────────────────────────────────▶|
        |                |──resume(@ t) — REMAINING portion only (061(a))──────────────────▶ (if halted)|
        |                |   resumption target = the unresolved gate (P-7) — ability restored,
        |                |   never a deemed decision (059(c)); a decision completed while
        |                |   Inoperative now takes effect on progression (071/Meaning-2) —
        |                |   BY THE CHIEF'S REQUEST on the lifecycle, never by this handler (W-6)
```

### 8d · UC-4 Report expiry → consequence (F-7/F-8)

```
Reporter (infra, later)  Handler              AG-3            P-6 ExpiryConsequence        Protocol
        | report            |                   |                      |                      |
        |──────────────────▶| find(AG-3)        |                      |                      |
        |                   |──preconditions: kind · ¬Inoperative (halted branch) ·           |
        |                   |  ¬recoverySucceeded · isExpiredAt(reported instant)             |
        |                   |─────────────────────────────────────────▶ evaluate              |
        |                   |◀── RecoveryPeriodExpired(…, Election Discontinued)              |
        |                   |            — or ExpiryConsequencePreconditionNotMet             |
        |                   |──append(consequence, Lifecycle)────────────────────────────────▶|
        |                   |  [on precondition failure:]                                     |
        |                   |──append(ProtocolEntry::refusal(act, reason))────────────────────▶|
        |◀── outcome ───────|  the clock produced no event; the reporter exercised no authority
```

---

## 9 · DDD Boundary Review

### 9a · What stays in Domain (frozen — nothing moves out)

* AG-1/AG-2/AG-3 with invariants I-1…I-16; all value objects; the four condition types; the domain events; the exceptions.
* **All rule arithmetic and classification:** P-1…P-7 remain Domain policies. In particular P-6 (`ExpiryConsequence`) stays Domain even though it *feels* like orchestration — it is the rule that supplies expiry's meaning (`063`/`058`), and moving it to Application would let an orchestrator own a consequence.
* The port **interfaces** (repositories, `ProtocolAppend`, `ServicePolicySnapshot`, `InstantSource`, the operation-less D-1 port) — domain-owned contracts (dependency inversion; `EM-ARCH-001` §5h).
* The reconstitution discipline (`fromRecordedFacts`, B-7) and the trusted-collaborator guards (I-11).

### 9b · What Increment 2 introduces in Application

* Command DTOs and one handler per governed act (UC-1…UC-4, + UC-5/UC-6 if confirmed at boundary).
* Query services UQ-1…UQ-4 (derivation-only).
* The refusal-recording pattern at the boundary (property 6 realization): domain refusal → recorded `RefusalRecord` → the refusal is the outcome; never absorbed, never retried silently.
* Consequence orchestration sequencing (§4) — the *ordering* of already-domain-owned acts, nothing more.
* `HistoryKind` assignment per fact (P-2H): position/gate-outcome facts → ProgressionDecision; Inoperative/restoration/period/expiry/cancellation facts → Lifecycle. *(The precise per-event assignment table is part of RED — pinned by tests, not by prose.)*

### 9c · What belongs to Infrastructure — later, each behind its own authorization

* Protocol-store adapter (technology unprescribed — G-6) · repository adapters/persistence · the service-policy client ACL implementation · the expiry **reporter** (whatever technology reports; never a consequence-supplier) · `InstantSource` adapter · the Interface layer (entry points, identity mapping, read authorization).
* **Never, in any increment:** an adapter for the D-1 port until organisational governance resolves the authorities externally; any timer attached to OPEN.

---

## 10 · Risks

| # | Risk | Mitigation in this proposal |
|---|---|---|
| R-1 | **Dual-write drift** (protocol append vs aggregate save) once persistence exists | out of Increment-2 scope by construction (in-memory doubles); ADR candidate A-1 reserved for the persistence increment; B-7 (state = projection of facts) keeps the record authoritative meanwhile |
| R-2 | **Consequence-orchestration gap**: handler records the primary fact, then fails before a consequence act | clocks anchor to recorded instants (DD-1/P-4/`060`), so late consequence acts do not distort time; the gap's *detection/repair* discipline is ADR candidate A-2 |
| R-3 | **Scope creep toward the lifecycle**: the temptation to "just advance the phase" on `GateSatisfied` or restoration | W-6 forbidden shape; progression eligibility is exposed as a QUERY (UQ-2) — the answer-shape, never the act-shape |
| R-4 | **Reading-B contamination**: adding an Inoperative guard on position expression "for safety" | W-2; Reading A is adopted; a guard would be a NEW rule requiring its own governance act |
| R-5 | **The reporter becomes a scheduler**: designing UC-4's caller pulls in timer semantics | the reporter is Infrastructure, later increment, own authorization; UC-4's contract accepts a report and can refuse it |
| R-6 | **Actor-model vacuum**: without identity, tests may hard-code actor assumptions that harden into architecture | A-2/M-4/M-5 keep the question named and open; commands carry opaque references only |
| R-7 | **Placeholder names harden into canon** | every name in this document inherits `EM-OPEN-045`'s standing; the `NonCanonicalEventName` discipline extends to commands |

---

## 11 · ADR candidates (none adopted here; each is a decision the boundary or a later increment must take)

| # | Candidate decision | When needed |
|---|---|---|
| **A-1** | Protocol-append / aggregate-save consistency strategy (ordering, failure atomicity) | persistence increment (Increment 3), not before |
| **A-2** | Consequence-act completion discipline (how an interrupted orchestration is detected and completed — replay from protocol vs idempotent re-issue) | Increment-2 RED can pin idempotence; full answer with persistence |
| **A-3** | UC-5 `RecordCommitteeConstitution` in/out of Increment-2 scope (extension of the §5c driving-port list) | **at the Increment-2 boundary** |
| **A-4** | UC-6 `EstablishGateDecision` trigger — the lifecycle integration point ("gate reached") | **at the Increment-2 boundary** (in/out); integration design with the lifecycle increment |
| **A-5** | Refusal taxonomy: which domain exceptions are recorded refusals vs caller errors (e.g. `UnknownCommitteeSeat` — malformed request or material refusal?) | Increment-2 RED (pinned by tests) |
| **A-6** | Vacancy-event recording authority (who may submit UC-2) | governance question — may resolve alongside the Interface increment; Increment 2 proceeds with the shape validated and the actor named-open |
| **A-7** | Deferred-effect representation (Meaning-2): how a decision completed while Inoperative is *presented* to the lifecycle on restoration (a query the Chief-side consults vs a recorded readiness fact) | Increment-2 boundary can choose the query form (UQ-2 suffices); anything richer needs the lifecycle increment |

---

## 12 · Implementation readiness assessment

**Question: Is Increment-2 ready for boundary authorization? grant? START? RED?**

**Answer:**

* **Boundary presentation: READY.** This proposal is the boundary presentation. It consumes only the frozen baseline, the approved corrected design, and adopted rules; every unresolved wall is a named dependency; no domain change, no technology, no framework artifact is proposed.
* **Boundary authorization: NOT YET — it requires the PO/ARB act on this proposal**, including explicit rulings on **A-3** (UC-5 in/out), **A-4** (UC-6 in/out), and **A-7** (Meaning-2 representation: confirm the query-form suffices for Increment 2).
* **Grant / START: NOT YET** — they follow the authorized boundary in the fixed sequence (proposal → boundary → grant → START → RED); nothing here supplies them.
* **RED: NOT YET** — RED begins only after START, and its first tests should pin the forbidden shapes W-1…W-10 and the refusal taxonomy (A-5).

**Missing decisions (blocking grant, none blocking the boundary act itself):**
1. PO/ARB approval of this proposal (the boundary act).
2. A-3 — UC-5 `RecordCommitteeConstitution`: in or out of Increment-2 scope.
3. A-4 — UC-6 `EstablishGateDecision`: in or out, and if in, its trigger contract.
4. A-7 — confirmation that UQ-2 (query form) is the Increment-2 realization of Meaning-2's deferral.
5. A-5 — refusal taxonomy ruling (may alternatively be delegated to RED with review).
6. Commission identity for the increment (EM-IMPL-002 naming, scope fixation, verification protocol — following the EM-IMPL-001 pattern including independent verification).

No governance adoption is missing: Increment 2 as proposed requires **no new business rule** — it exposes adopted rules already realized in the baseline. `EM-OPEN-049`/`066`/`094`, `053`, `077`/`076` remain walls, respected as named dependencies, blocking nothing in this scope.

---

## Traceability

`EM-IMPL-001` baseline freeze (`docs/publicdigit/implementation/2026-08-17-EM-IMPL-001-baseline-freeze.md`) · `EM-ARCH-001` approved design, §2b as corrected (`…-s2b-correction-registration.md`) · `EM-GOV-070`/`071` adoption registration incl. Meaning-1/Meaning-2 precision and the registered Increment-2 boundary (`docs/publicdigit/reviews/2026-08-17-EM-GOV-070-071-adoption-registration.md`) · adopted rows `EM-GOV-005`/`016`/`026`/`028`/`033`/`035`/`036`/`038`/`052`/`056`/`057`/`058`/`059`/`060`/`061`/`062`/`063`/`064`/`065`/`066`/`067`/`068`/`069`/`070`/`071` · baseline code `app/Contexts/Election/Domain/OperatingCore/` @ `a31f54f1` · developer guide `developer_guide/election_operating_core/01_step_domain_core_first_increment.md` · ADR-T11 · `F-PROTO-1` · `P-2H` · repo standing layer rules (`.claude/CLAUDE.md`).
