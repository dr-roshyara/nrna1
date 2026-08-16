# `EM-ARCH-001` — Model A Operating Core: Discovery, Domain Model and Target Architecture

**Type:** Architecture design deliverable (Architecture — Session 4) · **Date:** 2026-08-17
**Commission:** `EM-ARCH-001`, signed 2026-08-17 (`docs/publicdigit/reviews/2026-08-17-election-architecture-commission-prepared.md` §5)
**Corpus:** Election Manifesto @ `52587d41`, sha256 `b6f232cd…` — **verified byte-identical at session start. ADOPTED rows only are rules.**
**Status:** ⏸️ **RETURNED FOR HUMAN/PO/ARB DESIGN REVIEW. Implementation is NOT authorized by this document. No technology is committed by this document.**
**Placement:** derived — `php scripts/doc-placement.php --scope=product-specific --maturity=qualified --domain=publicdigit` → `docs/publicdigit` (exit 0, ruled); `architecture/` is the existing sub-root for architecture artifacts.

> ## Reading contract
>
> **Scope is exactly the commission's:** the qualified **Model A operating core** — the Committee-only acceptance subsystem with its recovery, vacancy, Inoperative and clock model. Everything outside it stays under the Architecture pause.
> **Every element carries the qualified rule that requires it.** Where a design branch needs an unresolved governance answer, the branch **stops** and the dependency is recorded in §7 — nothing is assumed.
> **All event, command and type names in this document are ILLUSTRATIVE PLACEHOLDERS**, not canonical vocabulary. The canonical protocol-event vocabulary is expressly open (`EM-OPEN-045`); the terminal state of `EM-GOV-063` is expressly unnamed (`EM-OPEN-110`). An illustration is not a rule (`EM-OPEN-045` standing principle).

---

## Phase ① — Reconstruction: which adopted rules participate in Model A

**Model A is the participation model "Election Committee alone" (`EM-GOV-025`(a)), selected with its decision rule at Election Application (`EM-GOV-025`(b), `EM-GOV-032`).** The reconstruction classifies the adopted corpus into four classes. Nothing is redesigned; classification is by the rules' own text.

### 1a · CORE — rules that constitute the Model A operating core

| Cluster | Rules | What they fix |
|---|---|---|
| **Acceptance configuration** | `EM-GOV-025` · `017` · `032` · `035` | Participation model + decision rule chosen at Application, from a governed menu, recorded as a **named rule**, never a numeric percentage; after publication not silently changed |
| **Committee constitution** | `EM-GOV-026` · `027` · `028` · `029` · `033` | `Election Appointment` is the first phase; the **external organisational authority** appoints Committee (and Deputy); Chief has no appointment power; independence mandatory; ≥ 3 eligible independent members; incompatibility with the representative role |
| **Committee vote & threshold** | `EM-GOV-030` · `033` · `034` · `036` · `038` · `057` | One Committee Vote per member; `Committee Vote ≠ Representation Vote`; no unanimity; `TWO_THIRDS_OF_COMMITTEE_VOTES` rounded **up**; strict >50% for majority; **denominator = constituted membership**, never occupied seats |
| **Gates** | `EM-GOV-016` · `052` · `068` | Both acceptance gates **election-wide**; a gate that cannot be satisfied halts progression **at the gate condition** (preceding phase stays completed); gate-interval states **OPEN** (undecided + mathematically achievable on recorded facts, **no clock**) vs **HALTED** (decided failure or mathematical impossibility on recorded facts) |
| **Vacancy & Inoperative** | `EM-GOV-064` · `065` · `056` · `066` | Vacancy exists **only** upon a recorded vacancy event (resignation-with-reason · death/permanent incapacity · loss of eligibility/independence); temporary unavailability is NOT a vacancy; *unable to function* is **arithmetic** (non-vacant seats < required votes); **Inoperative begins at the causing recorded event** — no declaration, no classifier; the external authority fills vacancies; a **cast Committee Vote stands**; a seat expresses at most one position per decision; a replacement participates only in positions the seat has not yet expressed |
| **Recovery & clocks** | `EM-GOV-058` · `059` · `060` · `061` · `062` · `063` · `067` · `014` | Inoperative → restoration or cancellation at restoration-period expiry; **two separate service-policy periods**, version-and-duration bound at period start; Inoperative is dominant but does not erase the halt; resumption returns to the **unresolved gate**; pause-not-reset; no clock manufactures new time; **each clock runs only while its triggering condition is active** (so the two clocks can never run at once); halted-recovery expiry ⇒ **unnamed terminal state**, recording expiry + failed recovery + resulting state; pre-Chief halts are recoverable **only** by the external authority supplying the missing appointments |

### 1b · SPINE — adopted rules the core consumes as given (not redesigned, not restated)

`EM-GOV-004` (correction principle) · `005` + its negative half + **`F-PROTO-1` properties 1–7** + **`P-2H`** (the protocol) · `006` (no technical grace period) · `007`/`008`/`009` (publication) · `010`/`011`/`012`/`013` (progression, halt, reschedule) · `EM-VOC-004`/`005`/`007`/`008` (opportunity model and progression-chain vocabulary) · `EM-VOT-001`–`005` (voting-boundary conditions and opportunity-bound authority) · **`ADR-T11`** (anonymity, constitutional, build-breaking) · the `ElectionConstitution`-homed rules of Manifesto §7 (lifecycle transitions live **there and nowhere else** — this design consumes that home; it does not duplicate it).

### 1c · NOT PARTICIPATING in Model A — the representative channel (rules remain in force; this core never consumes them)

`EM-GOV-018` · `020` · `021` · `022` · `024` · `039`–`051` · `053` · `054` · `055` (trustee model, representation votes, representative recovery, Committee substitution for an incapable representative channel). **In Model A no Representation Vote exists, so none of these mechanisms can arise.** Two are consumed *negatively*: `029`'s incompatibility clause and `030`'s naming wall bind the model even while vacuous here, so a later Model C cannot collide with anything this design names. `EM-GOV-019` (Voting Preparation) participates as a **phase of the spine**; its representative-consultation content is inapplicable in Model A **by `EM-GOV-025`'s own text** (representatives are not mandatory for every election) — the phase still finalizes the voting configuration.

### 1d · NOT RULES — never consumed

`EM-GOV-023` and `EM-GOV-037` (status: PENDING) · §4c proposals (`EM-VOC-006`, `EM-VOT-006`) · every §9 item. **The §4 KNOWN constraints of the final qualification report transfer as explicit constraints, not as pretended-away gaps** — each appears in §7 below.

### 1e · Reconstruction findings the review should see

* **R-F1 · The gates' number and scope are fixed; one gate's identity is not.** Adopted text fixes **two** acceptance gates, both **election-wide** (`EM-GOV-016`), names **post-counting acceptance** expressly (`016`), and says Voting Preparation *"does NOT accept the election or its result — the two gates keep that word"* (`019`). The precise chain position and decision subject of the first gate, and whether a gate decision is process-acceptance, tabulation-acceptance, combined or sequential, are **not fixed by adopted text** (`EM-OPEN-054` Q3 residue, `EM-OPEN-055`). The design therefore models **`AcceptanceGate` as a first-class concept with an abstract decision subject** and stops there (§7 D-3).
* **R-F2 · The confirmed S-06 derivation is treated as the authoritative reading of the gate interval**, being two-reader-confirmed against the frozen corpus (final report §1): temporary unavailability ⇒ gate **OPEN**, **no clock runs at any moment**; the situation ends **only** by a member returning and the decision being made, or by recorded vacancy events; a decided failure needs enough objections that satisfaction is impossible **by decision** — at 3 seats/threshold 2, two objections; **a single remaining member can neither pass nor decidedly fail the gate. No third exit exists.**
* **R-F3 · Model A's threshold does not wait on the unadopted menu.** `EM-GOV-036` adopts `TWO_THIRDS_OF_COMMITTEE_VOTES` for the Committee channel directly, verified safe at every size ≥ 3. The permitted-menu artifact (`EM-OPEN-077`/`076`) remains a dependency **of the general configuration capability**, not of Model A's own rule (§7 D-6).
* **R-F4 · Nothing in Model A is reachable without the external authority.** `EM-GOV-026` gates Administration on appointments; `028` places Committee and Deputy appointment with an external, undefined authority; §4 of the final report: *no acceptance model is reachable until the external authority exists* (`094`). This is a **wall**, and the design builds a **port with no adapter** at it (§3 B-3, §7 D-1).

---

## Phase ② — Domain model

**Bounded context:** everything below lives in the **Election context** (`EM-ENT-007` fixes that context's existence; the operating core is a subsystem within it). Election lifecycle transitions remain canonically homed in `ElectionConstitution` (Manifesto §1/§7) — this model **plugs into** that lifecycle at the gate condition; it does not re-declare transitions.

### 2a · Ubiquitous language (from adopted text only)

| Term | Meaning | Source |
|---|---|---|
| **Election Committee** | independent, impartial election body, constituted once during Election Appointment | `EM-GOV-029`, `026`, `056` |
| **Committee Seat** | a constituted membership position; survives its occupant; the unit that expresses at most one position per acceptance decision | `EM-GOV-056` ("fills the existing Committee seat"), `066` |
| **Vacancy Event** | the recorded event that alone makes a seat vacant | `EM-GOV-064` |
| **Committee Vote** | one per member; never combined with, or named like, a Representation Vote | `EM-GOV-030`, `033` |
| **Acceptance Gate** | election-wide mandatory decision between phases; a gate, **not** a phase, with **no schedule** | `EM-GOV-016`, `052`; `EM-OPEN-053` (constraint) |
| **OPEN** | gate reached, undecided, threshold mathematically achievable on recorded facts; in progress; **no recovery period runs** | `EM-GOV-068` |
| **HALTED** | progression halted at the gate condition (decided failure or mathematical impossibility on recorded facts) — or at a phase's failed mandatory condition | `EM-GOV-068`, `052`, `011`, `012` |
| **Election Inoperative** | the condition beginning at the recorded vacancy event that makes non-vacant seats < required Committee Votes, while the responsible authority has not restored the Committee | `EM-GOV-058`, `065` |
| **Terminal state (unnamed)** | the election-level state reached when the halted-recovery period expires without successful recovery — **vocabulary reserved to `EM-OPEN-110`** | `EM-GOV-063` |
| **Governed period** | halted-election recovery period · Committee-restoration period — two separate service-policy parameters, version-bound at start | `EM-GOV-059`(a), `014` Part 2, `050`(b) |
| **Election Protocol** | the permanent record of every material event; sits **outside** the progression chain | `EM-GOV-005`, `EM-VOC-008`, `P-2H`, `F-PROTO-1` |

### 2b · The four conditions — distinct by construction (hard gates G-3/G-4)

The four business conditions are **different types owned by different concepts**, so no implementation state can collapse them:

| Condition | Kind of thing | Owner concept | What ends it |
|---|---|---|---|
| **OPEN** | gate-interval classification, **derived on recorded facts** | `AcceptanceGate` | a decision (pass / decided failure) or a vacancy event breaching the arithmetic — **never time** (`068`; `053` unbounded, tolerated) |
| **HALTED** | progression condition of the election lifecycle | progression (Constitution-homed lifecycle + `052` gate condition) | governed recovery (`013`/`014`) · or period expiry ⇒ terminal (`063`) |
| **INOPERATIVE** | operational condition **overlaying** the lifecycle — dominant, never erasing the halt | election operational condition | Committee restoration (`058`) · or restoration-period expiry ⇒ cancellation (`058`) |
| **TERMINAL (unnamed)** | election-level end state | election lifecycle | nothing — terminal (`063`) |

Legal combinations, derived: `HALTED ∧ INOPERATIVE` is a **required** representable combination (`059`(b): both facts retained). `OPEN ∧ INOPERATIVE` cannot arise in Model A: the same recorded vacancy event that begins Inoperative (`065`) makes the threshold unachievable on recorded facts, which ends OPEN (`068`) — one event, two classifications, both **derived from the same recorded fact**, which is why they can never disagree. No clock ever attaches to OPEN (`062`, `068`). **OPEN is not a timeout, has no deadline, and the model gives it no expiry hook to hang one on.**

### 2c · Aggregates, entities, value objects

**AG-1 · `ElectionCommittee`** *(aggregate)* — the constituted body.
* State: election identity · the set of **`CommitteeSeat`** entities · constitution facts.
* Invariants it owns:
  * **I-1** constituted size ≥ 3 at constitution (`EM-GOV-033`);
  * **I-2** a seat becomes vacant **only** by a recorded `VacancyEvent` with one of the three grounds; temporary unavailability changes nothing structural (`064`);
  * **I-3** the acceptance denominator is the **constituted** membership — vacancy does not reduce it, filling does not change it (`057`);
  * **I-4** filling a vacancy re-occupies the **existing seat**, creates no new membership, reopens nothing, alters no decision already made (`056`);
  * **I-5** the Chief and the Committee itself have no appointment/filling power — filling arrives only through the external-authority port (`028`, `056`);
  * **I-6** eligibility/incompatibility: member ⇥ candidate-representative (`029`; vacuous in Model A, kept as a wall).
* Derived (never stored as an independent flag): **`unableToFunction` = non-vacant seats < required Committee Votes** (`065` — arithmetic, no determiner).

**AG-2 · `AcceptanceGateDecision`** *(aggregate; one per gate per election)* — the decision record of one election-wide gate.
* State: gate identity (which of the two gates — subject abstract, §7 D-3) · the **positions expressed per seat** · the bound acceptance rule.
* Invariants it owns:
  * **I-7** at most **one** position per seat per acceptance decision (`066`);
  * **I-8** a validly cast Committee Vote **stands** notwithstanding later unavailability, resignation or seat-filling (`066`) — positions are append-only facts;
  * **I-9** a replacement occupant may express positions only where the **seat** has not yet expressed one (`066`);
  * **I-10** evaluation applies the **named** rule (`035`) `TWO_THIRDS_OF_COMMITTEE_VOTES` with round-up arithmetic (`036`, `038`) against the constituted denominator (`057`);
  * **I-11** the interval state is **derived, on recorded facts**: OPEN iff undecided ∧ achievable; decided-pass iff accepts ≥ required; decided-failure iff accepts can no longer reach required counting every seat that could still express a position (`068`; R-F2);
  * **I-12** dissent is recorded even where the threshold is achieved (`031`'s recorded-dissent clause, whose recording half `005` already requires).
* Expressible positions: **accept · object** — adopted text defines no Committee abstention; the model **does not invent one** (G-1; see §7 D-9).

**AG-3 · `RecoveryProcess`** *(aggregate; per governed period instance)* — one halted-recovery or Committee-restoration period.
* State: period kind (two kinds, never merged — `059`(a)) · the **`PolicyBinding`** value (policy version + duration captured at period start — `050`(b), `014` Part 2) · onset fact reference.
* Invariants it owns:
  * **I-13** the applicable policy version and duration are bound at period start and a later policy change never retroactively alters them (`050`(b));
  * **I-14** elapsed time accrues **only while the triggering condition is active** (`062`) — see the clock model, 2d;
  * **I-15** the Committee-restoration allowance is per **election**, never per failure; no repetition renews it (`061`(b));
  * **I-16** resumption resumes the **remaining** portion; no minimum is guaranteed, no time is created (`060`, `061`(a)).

**VO (value objects):** `CommitteeSeatId` · `VacancyGround` (the three grounds of `064`, closed set) · `AcceptancePosition` (accept | object) · `ThresholdRule` (named rule token, `035`) · `RequiredVotes` (⌈2n/3⌉ arithmetic, `036`/`038`) · `PolicyBinding` (version + duration, `050`(b)) · `ClockReading` (elapsed · remaining, computed) · `TerminalStatePlaceholder` (**a deliberately unnameable token — carries `EM-OPEN-110`, cannot be rendered as a business word**).

**`ElectionProtocol`** *(dedicated record, not an aggregate of the decision model)* — the append-only election record with the seven `F-PROTO-1` properties. **Two histories, never merged** (`P-2H`): lifecycle events and progression-decision events stay distinguishable; a refusal is never a termination; a later success never erases prior refusals. The protocol **records; it does not decide** (`EM-VOC-008`). Its negative half is structural: **no component may write an event for a phase that never occurred** (`005`).

### 2d · The clock model — clocks as computations, never as countdowns

**DD-1 (design decision).** A recovery clock is a **derived measure over recorded condition intervals**, computed from the protocol's recorded onset/offset facts — never a mutable ticking counter and never a scheduled job's state.
**Required by:** `EM-GOV-065` (*"clock pause/start computed from the recorded onset"*) · `062` (a clock runs only while its condition is active — a computation over condition intervals satisfies this by construction) · `060` (pause-not-reset and non-retroactivity fall out of interval arithmetic) · `061` (remainder is arithmetic on the same intervals; no new time can appear).
**Consequences:** the two clocks can never run at once because *operative* and *Inoperative* are mutually exclusive recorded conditions (`062` structural consequence) — the model does not "coordinate timers", it has none. **Expiry is a question, not an event the clock produces:** the service **reports** expiry (`EM-OPEN-047` resolution, adopted act v2 item 4); the Election rule alone supplies the consequence (`058` cancellation · `063` terminal). A clock-driven mechanism progresses nothing and exercises nobody's authority (`EM-VOT-004` boundary, `EM-GOV-006`).

### 2e · Policies (rule realizations, stateless)

| Policy | Realizes |
|---|---|
| `P-1 ThresholdEvaluation` — required = ⌈2·constituted/3⌉; pass iff accepts ≥ required | `036` · `038` · `057` |
| `P-2 GateIntervalClassification` — OPEN / decided-pass / decided-failure / impossible, on recorded facts only | `068` · R-F2 |
| `P-3 UnableToFunction` — non-vacant < required | `065` |
| `P-4 InoperativeOnset` — begins **at** the causing recorded vacancy event | `065` |
| `P-5 ClockAccrual` — interval sum while the triggering condition is active | `062` · `060` · `061` |
| `P-6 ExpiryConsequence` — halted-recovery expiry ⇒ record(expiry · failed recovery · resulting state) ⇒ terminal placeholder; restoration expiry ⇒ cancellation | `063` · `058` |
| `P-7 ResumptionTarget` — restoration returns the election to its prior halted condition, at the **unresolved gate**; the gate is never treated as satisfied by nobody's acceptance | `059`(c) · `060` |

---

## Phase ③ — Boundaries

**B-1 · Acceptance boundary.** The gate subsystem decides **acceptance**; it never advances phases. Gate satisfied ⇒ the *lifecycle* (Constitution-homed) may progress; gate unsatisfiable/failed ⇒ progression is halted **at the gate condition** while the preceding phase remains completed and is never restarted (`052`). The gate has **no schedule and no time bound** (`EM-OPEN-053` — tolerated, not fixed; G-3).

**B-2 · Committee membership & recovery boundary.** Constitution happens once, in Election Appointment (`026`, `056`); afterwards only **vacancy-filling** exists — no "reconstitution" concept is modeled, because the rules define none. Temporary unavailability is **participation**, not structure (`064`), and is therefore a fact about a decision, never a state of the Committee.

**B-3 · External-authority boundary.** Committee/Deputy appointment and vacancy filling arrive through the **`OrganisationalAppointmentAuthority` port**, which this design leaves **without any adapter, default, stub or fallback**. Absence of the authority means the election cannot be constituted — the design does not manufacture a substitute (`028`, `049`, `066`, `094`; commission text). The Chief-appointing authority is likewise external and additionally **unidentified even in form** (`EM-OPEN-049`) — no port parameter presumes the two authorities are the same body (§7 D-1).

**B-4 · Terminal-state boundary.** `063`'s terminal state enters the model **only** as `TerminalStatePlaceholder`, a token that deliberately cannot be rendered as business vocabulary. Everything that must be recorded at expiry (the expiry · that recovery did not succeed · the resulting state) is modeled; the state's **name** is not — implementation is blocked at exactly this point by `EM-OPEN-110`, and the design keeps that block visible instead of absorbing it. The same boundary carries the *cancelled* two-level overload (`EM-OPEN-048`(b)/`110`): **opportunity-level `cancelled`** (`EM-VOC-004`) and **election-level cancellation** (`058`) are distinct types in this model and are never represented by one value.

**B-5 · Timing/recovery boundary (context boundary).** The **duration values** of both governed periods belong to the **service-policy context** — outside Election (`014` Part 2, `059`(a), `EM-OPEN-050`(a) records that the context-map consequence is open). The Election context owns: that the periods exist, what they mean, their version-binding, and every consequence. The port therefore hands over **only** `(policyVersion, duration)` at period start; the service side is never asked for, and can never supply, a consequence (`047` resolution, `058` safeguard).

**B-6 · Authority boundary (spine, restated as a wall).** No clock-driven mechanism exercises the Chief's authority; no Chief action overrides a mandatory condition (`EM-VOT-004`). The Committee **participates in acceptance** and holds **no adjudicative/interpretive power** — the design exposes no interface through which the Committee could rule on what a rule means (`EM-OPEN-071` open; the modest reading is the only one the model can express). Nobody classifies a halt as unsatisfiable-in-fact: no `C-2` classifier component exists (`EM-OPEN-109`, blocked on new policy).

**B-7 · Record boundary.** The protocol is the evidence of the chain, **outside** the chain (`EM-VOC-008`). Aggregate state is always reconstructable **from** recorded facts, never richer than them — anything the record cannot show, the model must not know (`P-2H`, `F-PROTO-1` property 7 requires the lifecycle/decision distinction inside the record itself).

---

## Phase ④ — Behavioural flows

Every step that is a material event is recorded (`005`); event names below are placeholders (`EM-OPEN-045`). "Gate reached" presupposes the predecessor phase legitimately completed (`010`/`011`).

**F-1 · Normal acceptance.** Gate reached → interval state **OPEN** (`068`) → seats express positions (each seat once — `066`) → accepts reach required (`036`/`038`/`057`) → gate satisfied → dissent, if any, remains on the record (`031`/`005`) → lifecycle may progress (Constitution home).

**F-2 · Decided failure.** Positions make satisfaction impossible **by decision** (at 3/2: two objections — R-F2) → progression **HALTED at the gate condition**; the preceding phase remains completed (`052`) → halted-recovery period begins; `PolicyBinding` captured (`050`(b)) → halted clock accrues while operative-and-halted (`062`) → recovery through governed rescheduling or other governed path (`013`, `014` Part 1) → or F-7.

**F-3 · Temporary unavailability (the S-06 flow).** One or more members temporarily unavailable while non-vacant ≥ required → gate stays **OPEN** · **no vacancy** (`064`) · **no clock runs at any moment** (`062`) → ends **only** by (a) a member returning and the decision being made (F-1/F-2), or (b) recorded vacancy events (F-4/F-5). **No third exit** (R-F2). The wait is unbounded (`EM-OPEN-053`, tolerated) — the design attaches **nothing** to its duration.

**F-4 · Vacancy without arithmetic breach.** Recorded `VacancyEvent` on one seat; non-vacant still ≥ required → denominator unchanged (`057`) → gate remains OPEN if undecided-and-achievable (`068`) → external authority fills the existing seat via the port (`056`) → the replacement may express positions only where the seat has not yet expressed one (`066`).

**F-5 · Inoperative onset.** A recorded `VacancyEvent` makes non-vacant < required → **Election Inoperative begins at that event** — no declaration (`065`) → any running halted-recovery clock **pauses from that moment, non-retroactively** (`060`) → Committee-restoration clock accrues while Inoperative (`062`) → both facts retained where a halt preceded: *halted at the gate* and *subsequently Inoperative* (`059`(b)).

**F-6 · Restoration.** External authority fills seats until non-vacant ≥ required → Inoperative ends → the election **returns to its prior condition at the unresolved gate** — restored ability to decide, never a deemed decision (`059`(c)) → a paused halted-recovery clock resumes with its **remaining** portion (`060`, `061`(a)) → a subsequent Committee failure draws on the **same** per-election restoration allowance; nothing renews it (`061`(b)).

**F-7 · Halted-recovery expiry → terminal.** Service reports expiry (`047` resolution) → rule evaluates: halt present ∧ governed period expired ∧ recovery not succeeded — **three facts, not one** (`063`) → protocol records expiry, the failure of recovery, and the resulting state → election reaches the **unnamed terminal state** (`TerminalStatePlaceholder`). Cannot fire while Inoperative — the halted clock never runs there (`062`, `063`).

**F-8 · Restoration-period expiry → cancellation.** Restoration period exhausted while Inoperative → *cannot restore* is evidenced solely by that expiry (`065`) → election is **cancelled** — the election-level consequence, an Election Rule, never a service decision (`058`).

**F-9 · Pre-Chief halt.** Progression halts before any Chief exists → recovery initiation is **assignable to nobody inside the election** (`067`) → the halt, the awaited appointments and the applicable period are recorded → the external authority supplies the missing appointments, or expiry follows F-7 (`067` → `063`). No notification duty exists and none is designed (`067` expressly declined it).

---

## Phase ⑤ — Target architecture

**Style: hexagonal (ports & adapters) over a clean-architecture layering — dependencies point inward, the domain core is framework-free.** This is an architectural style, not a technology commitment (G-6): it is required here because the commission itself demands a core that realizes rules **without** technology (`no technology commitment`), because the external-authority wall (B-3) and the service-policy context boundary (B-5) are *exactly* port-shaped, and because the repository's standing layer discipline (Domain: no framework · Application: limited · Infrastructure: free) already binds this codebase. No framework, database, queue or scheduler is chosen by this document.

### 5a · Context map (Model A operating core)

```
                          ┌──────────────────────────────────────────────┐
                          │            ELECTION CONTEXT                  │
                          │                                              │
   Chief / Deputy ───────▶│  Progression & lifecycle (ElectionConstitu-  │
   (progression requests) │  tion home — consumed, not duplicated)       │
                          │        │ gate condition (EM-GOV-052)         │
                          │        ▼                                     │
                          │  ┌────────────────────────────────────┐      │
 Committee members ──────▶│  │  MODEL A OPERATING CORE            │      │
 (positions, via seats)   │  │  ElectionCommittee ·               │      │
                          │  │  AcceptanceGateDecision ·          │      │
                          │  │  RecoveryProcess · clocks (derived)│      │
                          │  └──────┬──────────────┬──────────────┘      │
                          │         │              │                     │
                          │         ▼              │                     │
                          │  Election Protocol     │                     │
                          │  (append-only, P-2H,   │                     │
                          │   F-PROTO-1 1..7)      │                     │
                          └─────────┼──────────────┼─────────────────────┘
                                    │              │
                     (port, ACL)    ▼              ▼   (port, NO adapter)
                          ┌──────────────┐   ┌─────────────────────────┐
                          │ SERVICE      │   │ ORGANISATIONAL          │
                          │ POLICY ctx   │   │ GOVERNANCE (external,   │
                          │ (version +   │   │ undefined — EM-OPEN-049 │
                          │ duration only│   │ / 066 / 094: absence ⇒  │
                          │ — never a    │   │ election cannot be      │
                          │ consequence) │   │ constituted)            │
                          └──────────────┘   └─────────────────────────┘
```

### 5b · Layer responsibilities (abstract; conforms to the repo's standing layer rules)

| Layer | Holds | May not hold |
|---|---|---|
| **Domain** (pure) | AG-1/AG-2/AG-3, all VOs, policies P-1…P-7, domain events, the four condition types, port **interfaces** | any framework artifact, any clock source, any storage notion |
| **Application** | command handlers orchestrating one governed act each; DTOs; transaction boundary = one aggregate per command (repo Rule) | rule arithmetic (lives in policies); protocol interpretation |
| **Infrastructure** | adapters: protocol store, persistence, service-policy client (ACL — B-5), expiry reporting, time-instant source | **any adapter for `OrganisationalAppointmentAuthority`** (B-3 — deliberately absent) · **any timer attached to OPEN** (G-3) |
| **Interface** | the entry points for the driving actors | authority decisions of any kind |

### 5c · Ports

**Driving (into the core)** — each is an authorized business act, each is recorded whether it succeeds or is refused (`005`, `F-PROTO-1` property 6):
`ExpressCommitteePosition(seat, accept|object)` · `RecordVacancyEvent(seat, ground, reason)` (`064`) · `FillCommitteeSeat(seat, appointee)` — **callable only by the external authority's future adapter; no internal caller exists** (`056`, `028`) · `ReportPeriodExpiry(period)` — reporting only; consequence evaluated inside (`047` resolution) · progression requests remain the Chief's, on the Constitution-homed lifecycle (`EM-VOT-004`), outside this subsystem.

**Driven (out of the core):**
`ProtocolAppend` — append-only in meaning, durable, truncation-resistant, refusal-recordable independently of state success, lifecycle-vs-decision distinguishing (`F-PROTO-1` 1–7) · `ServicePolicySnapshot` — returns `(version, duration)` at period start, nothing else (B-5) · `OrganisationalAppointmentAuthority` — **declared, unimplemented** (B-3) · aggregate persistence (repositories for AG-1/2/3 only — repo Rule 9) · `InstantSource` — supplies recording instants only; **no schedule semantics** (`EM-OPEN-024` open — §7 D-8).

### 5d · State transitions (the two derived classifications)

```
GATE (derived per EM-GOV-068, on recorded facts only):
  NOT_REACHED ──predecessor legitimately completed──▶ OPEN
  OPEN ──accepts ≥ required──────────────────────────▶ SATISFIED (decided)
  OPEN ──satisfaction impossible by decision─────────▶ HALTED-AT-GATE (decided failure)
  OPEN ──vacancy event: non-vacant < required────────▶ (unachievable; election INOPERATIVE at that event)
  (restoration: non-vacant ≥ required, undecided)────▶ OPEN again   (EM-GOV-059(c))

ELECTION operational overlay:
  OPERATIVE ──vacancy event breaches arithmetic──────▶ INOPERATIVE          (065)
  INOPERATIVE ──seats filled to sufficiency──────────▶ OPERATIVE, prior condition restored (058, 059(c))
  INOPERATIVE ──restoration period expires───────────▶ CANCELLED (election-level)          (058)
  OPERATIVE ∧ HALTED ──recovery period expires───────▶ ⟨TERMINAL — EM-OPEN-110 placeholder⟩ (063)
```

No transition above is time-initiated except the two expiry consequences, and those fire only on a **reported** expiry evaluated by the rule — the clock decides nothing (`047`, `EM-VOT-004`, `006`).

### 5e · Persistence responsibilities

* **Protocol store:** the system of record for facts; append-only in meaning; both histories distinguishable inside it (`P-2H`; property 7). The **storage mechanism is deliberately not prescribed** — `F-PROTO-1` prescribes properties, not machinery; choosing (e.g.) event-store tech is an implementation decision gated behind design approval (G-6).
* **Aggregate persistence:** AG-1/AG-2/AG-3 persist current state **as a projection of recorded facts** — rebuildable, never contradicting the protocol (B-7). Derived classifications (OPEN, unable-to-function, clock readings) are **computed, never stored as authoritative columns** (`065`, `068`, DD-1) — storing them would create a second truth that could drift from the record.
* **`ADR-T11` binds every persisted shape** in this subsystem: no voter↔vote linkage can appear in any aggregate, event payload or projection — relevant here to free-text reason fields (vacancy grounds, `064`) exactly as Governance flagged for the representative analogue (`EM-OPEN-091`②): reason fields are constrained surfaces, checked at the boundary.

### 5f · Domain events (justified individually; names are placeholders — `EM-OPEN-045`)

| Business fact | Recording obligation | Placeholder |
|---|---|---|
| position expressed at a gate (incl. dissent that changes nothing) | `005` · `031` | `CommitteePositionExpressed` |
| gate satisfied / decided failure | `005` · `052` | `GateSatisfied` / `GateFailedByDecision` |
| vacancy event (ground + reason) | `064` · `005` | `CommitteeSeatVacated` |
| seat filled by external authority | `056` · `005` | `CommitteeSeatFilled` |
| Inoperative onset at the causing event | `065` · `059`(b) | `ElectionBecameInoperative` |
| restoration; return to unresolved gate | `059`(c) · `005` | `ElectionRestored` |
| period start with policy binding | `050`(b) · `059`(a) | `RecoveryPeriodStarted` |
| expiry + failed recovery + resulting state | `063` (the one genuinely new recording obligation) | `RecoveryPeriodExpired` |
| election-level cancellation | `058` | `ElectionCancelledOnRestorationExpiry` |
| pre-Chief halt: awaited appointments recorded | `067` | `AppointmentsAwaited` |

**No event exists for phases that never occurred** — the protocol describes reality (`005`, negative half). Events here are **domain events** carrying business meaning (repo Rule 7); infrastructure eventing is unaddressed.

### 5g · Design patterns employed (and why each is rule-driven, not taste)

| Pattern | Where | The rule that makes it necessary |
|---|---|---|
| Aggregate w/ invariant ownership | AG-1/2/3 | invariants I-1…I-16 each cite their rule |
| Value Object | `VacancyGround`, `ThresholdRule`, `PolicyBinding`, placeholder token | closed sets and version-binding in adopted text (`064`, `035`, `050`(b), `110`) |
| **Specification** (named rule object) | `ThresholdRule` → P-1 | `035`: the configuration records the **named rule**, evaluated against the population — a named, versionable rule object is that sentence as code |
| **Derived state / functional classification** (no state machine object for OPEN) | P-2, P-3, clock readings | `065`/`068`: classifications hold "on recorded facts", with "no declaration, no determiner" — storing a mutable state would create the determiner the rule excludes |
| **Memento / snapshot binding** | `PolicyBinding` | `050`(b): version + duration bound at period start |
| **Ports & Adapters** | §5c | external walls the design must not fill (`028`/`049`/`066`/`094`; `014` Part 2) |
| **Anti-Corruption Layer** | service-policy adapter | `EM-OPEN-050`(a): the parameter's owning context is outside Election and unsettled — the ACL keeps its vocabulary out of the domain |
| **Append-only ledger** (in meaning) | Protocol | `F-PROTO-1` properties 1–7; `P-2H` |

### 5h · Dependency directions

Domain ← Application ← Interface; Domain ← Application ← Infrastructure (adapters implement domain-owned ports). The Domain depends on **nothing** — not the framework, not the protocol store, not the clock. The operating core depends on the Constitution-homed lifecycle **conceptually** (it halts *that* progression), wired at the application layer so neither package imports the other's internals. Nothing anywhere depends on the terminal state having a name.

---

## §6 · Hard-gate self-check (G-1…G-6)

| Gate | This deliverable's position |
|---|---|
| **G-1** no invented business rules | every invariant, policy, flow and event cites adopted text; the two candidate temptations were explicitly declined: no Committee abstention (§7 D-9), no bounded wait on OPEN (§7 D-4) |
| **G-2** per-element traceability | tables in §§1, 2c, 2e, 5f, 5g; invariants I-1…I-16 individually cited |
| **G-3** OPEN ≠ HALTED, OPEN ≠ timeout | OPEN is a derived classification with **no clock, no deadline, no expiry hook** (2b, 5d); the only time-consequences in the design attach to HALTED (`063`) and INOPERATIVE (`058`) via reported expiry |
| **G-4** Inoperative distinct from all three | separate condition type, overlay semantics, `HALTED ∧ INOPERATIVE` representable, dominance-without-erasure (`059`(b)) modeled structurally (2b) |
| **G-5** open question ⇒ explicit dependency | §7 — nine stopped branches, none assumed |
| **G-6** no implementation/technology commitment | style only (hexagonal/clean, mandated by the commission's own constraints and the repo's standing rules); no framework, storage, scheduler or event technology chosen; protocol mechanism expressly unprescribed |

## §7 · Dependency register — branches stopped, nothing assumed

| # | Stopped branch | Blocking item | What the design does meanwhile |
|---|---|---|---|
| **D-1** | any adapter, default or identity assumption for the appointing authorities (Committee/Deputy vs Chief) | `EM-OPEN-049` · `066` · `094` | port declared, unimplemented; two authorities kept distinct in the port model |
| **D-2** | naming the terminal state; any rendering of it as business vocabulary | `EM-OPEN-110` | `TerminalStatePlaceholder`, deliberately unnameable; implementation blocked here by design |
| **D-3** | fixing the first gate's chain position and each gate's decision subject (process vs tabulation, combined vs sequential) | `EM-OPEN-054` Q3 · `055` | `AcceptanceGate` abstract over its subject; both gates election-wide (`016`) is all that is fixed |
| **D-4** | any bound, timer, escalation or obligation on an OPEN gate or on inaction | `EM-OPEN-053` · `046` · `102` | indefinite OPEN tolerated by construction (PO-classified non-blocking) |
| **D-5** | any `C-2` (unsatisfiable-in-fact) classifier component | `EM-OPEN-109` | none exists; no actor can convert waiting into impossibility |
| **D-6** | the permitted-menu artifact as a configuration surface | `EM-OPEN-077`/`076` | Model A consumes adopted `036` directly; the menu capability is not designed |
| **D-7** | canonical protocol-event vocabulary | `EM-OPEN-045` | all event names placeholders, marked as such |
| **D-8** | schedule-time meaning/timezone semantics inside any clock or schedule computation | `EM-OPEN-024` (+ `023`(b)) | clocks compute durations between **recorded instants** only; no civil-time meaning is fixed |
| **D-9** | Committee abstention as a position; the *outcome* and *cancelled* vocabulary overloads | `EM-OPEN-088` (rep-side analogue, unruled for the Committee) · `043` · `048`(b)/`110` | positions = accept/object only; the two *cancelled* levels are distinct types; no overload-resolving names invented |

## §8 · What happens next (per the signed commission)

This deliverable returns to **Session 2 (Governance)** for review against G-1…G-6 and the commission text, then to the **Human/PO/ARB** for design approval. **Implementation authorization is a separate future act; nothing here supplies it.**

**Traceability.** `EM-ARCH-001` signed commission §1/§5 · signed registration · startup brief §4/§5 · final qualification report §1/§4 · Election Manifesto @ `52587d41` (`b6f232cd…`), ADOPTED rows · ADR-T11 · `F-PROTO-1` · `P-2H` · repo standing layer rules (`.claude/CLAUDE.md`) · A-3.
