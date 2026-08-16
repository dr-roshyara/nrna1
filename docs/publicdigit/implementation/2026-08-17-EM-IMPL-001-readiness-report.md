# `EM-IMPL-001` — Implementation Readiness Report (first bounded increment)

**Type:** Implementation readiness assessment (Implementation lane — Session 3) · **Date:** 2026-08-17
**Placement:** derived — `php scripts/doc-placement.php --scope=product-specific --maturity=qualified --domain=publicdigit` → `docs/publicdigit` (exit 0, ruled); `implementation/` created as the sub-root for implementation artifacts in that root.
**⛔ This report contains no implementation. No production file, test file, migration, model or configuration was created or modified. The only file produced is this report.**

**Inputs read:** prepared `EM-IMPL-001` (`docs/publicdigit/reviews/2026-08-17-implementation-authorization-prepared.md`) · approved `EM-ARCH-001` design (`docs/publicdigit/architecture/2026-08-17-EM-ARCH-001-model-a-operating-core-design.md`) · final qualification report (`docs/publicdigit/reviews/2026-08-17-EM-BRQ-001-final-qualification-report.md`) · D-1…D-9 reconciliation (`docs/publicdigit/architecture/2026-08-17-EM-ARCH-001-dependency-reconciliation.md`) · Election Manifesto ADOPTED rows (`docs/publicdigit/business_rules/ELECTION_MANIFESTO.md`) · repo standing layer rules (`.claude/CLAUDE.md`) · repository structure (`app/`, `tests/` — read-only).

---

## A · Authorization status

**`EM-IMPL-001` is PREPARED but NOT YET AUTHORIZED.** The act awaits the PO's signature, which must additionally supply: (i) the performing lane, (ii) the increment boundary, (iii) checkpoint/deadline, (iv) the signature — with grant and START following as separate gated acts. **Therefore no implementation has started, and none starts from this report.** The existence of the prepared document confers nothing; this assessment treats it as a draft act whose constraints C-1…C-6 will bind if and when signed.

## B · First increment boundary

The proposed increment — **"Domain core: the three aggregates + policies + protocol port contracts"** — resolves against the approved design's Domain-layer row (§5b) to exactly the following. Nothing outside this list is in the increment.

**IN (all pure PHP, framework-free, under the repo's Domain layer rules):**

| Element class | Contents (design ref) |
|---|---|
| Aggregates | **AG-1 `ElectionCommittee`** (invariants I-1…I-6, derived `unableToFunction`) · **AG-2 `AcceptanceGateDecision`** (I-7…I-12, **gate subject abstract** per D-3) · **AG-3 `RecoveryProcess`** (I-13…I-16) — §2c |
| Value objects | `CommitteeSeatId` · `VacancyGround` (closed set of 3) · `AcceptancePosition` (**accept · object only**) · `ThresholdRule` (named token) · `RequiredVotes` (⌈2n/3⌉) · `PolicyBinding` (version + duration) · `ClockReading` (computed) · `TerminalStatePlaceholder` (renders as **Election Discontinued** per adopted `EM-GOV-069`; D-2 discharged) — §2c |
| Policies (stateless) | **P-1…P-7** exactly as §2e defines them |
| Condition types | the four distinct types OPEN / HALTED / INOPERATIVE / TERMINAL as **derived classifications**, never stored state — §2b, §5d, DD-1 |
| Domain events | the ten recorded business facts of §5f, **type names marked non-canonical** (D-7); constituents of the protocol contract, not an eventing infrastructure |
| Port **interfaces** (domain-owned, no adapters) | `ProtocolAppend` (F-PROTO-1 properties 1–7, P-2H two-histories) · `ServicePolicySnapshot` (`(version, duration)` only) · **`OrganisationalAppointmentAuthority` — declared, permanently unimplemented in this increment (D-1)** · `InstantSource` (recording instants only, no schedule semantics — D-8) · repository interfaces for AG-1/2/3 only (repo Rule 9) — §5c |

**OUT (explicitly excluded; touching any of these exceeds the boundary):** Application-layer command handlers and DTOs · every adapter (protocol store, persistence, service-policy ACL, expiry reporting, instant source) · migrations, Eloquent models, controllers, routes, providers · **any adapter/stub/default for `OrganisationalAppointmentAuthority`** · any scheduler, timer, job, or expiry hook (none may ever exist for OPEN) · any concrete first-gate binding · any wiring to the `ElectionConstitution`-homed lifecycle (application-layer concern, later increment — design §5h) · any technology selection (protocol store mechanism expressly unprescribed — G-6).

**Boundary note for the PO's signature (§C-item (ii) of the prepared act):** the act's own recommendation names "AG-1/AG-2/AG-3 + policies P-1…P-7 + protocol port contracts". The table above reads "domain core" as the design's §5b Domain row, which additionally contains the VOs (constituents of the aggregates — the aggregates are unbuildable without them), the four condition types (P-2/P-3's return types), and the domain events (the protocol contract's payloads). This reading adds no behaviour; it adds the types the named elements cannot compile without. **The signature should confirm or trim this reading; this report does not decide it.**

**Repository placement observation (not a decision):** `app/Contexts/Election/Domain/` exists and currently holds a different subsystem (determination/correction reactions). The Model A operating core does not exist anywhere in `app/`. Where the new subtree sits inside the Election context namespace is an implementation decision within standing conventions; it is recorded here so it is made visibly at START, not silently.

## C · Rule-to-element traceability

Every element carries its requiring authority. (The design's own G-2 tables carry the full per-invariant citations; this table is the increment-level map. **No element below exists for convenience, convention, or pattern preference.**)

| Implementation element | Architecture element | Governance rule(s) | Business behaviour |
|---|---|---|---|
| `ElectionCommittee` aggregate | AG-1, §2c | `033` · `064` · `057` · `056` · `028` · `029` | constituted body ≥ 3; vacancy only by recorded event; constituted denominator; seat-filling only via external authority |
| `unableToFunction` (derived, never stored) | AG-1 derived · P-3 | `065` | arithmetic condition, no declaration, no determiner |
| `AcceptanceGateDecision` aggregate | AG-2, §2c | `016` · `066` · `035` · `036` · `038` · `057` · `068` · `031` · `005` | one position per seat; cast vote stands; named-rule evaluation; derived interval state; dissent recorded |
| Abstract gate subject | AG-2 "subject abstract", §7 D-3 | `016` (all that is fixed) | two election-wide gates; subject unbound |
| `RecoveryProcess` aggregate | AG-3, §2c | `059`(a) · `050`(b) · `014` Part 2 · `060` · `061` · `062` | two period kinds never merged; policy version-binding; pause-not-reset; per-election allowance |
| `VacancyGround` VO | §2c VO | `064` | closed set: resignation-with-reason · death/permanent incapacity · loss of eligibility/independence |
| `AcceptancePosition` VO | §2c VO | `066` · design G-1 (D-9) | accept · object; **no abstention — none is invented** |
| `ThresholdRule` + `RequiredVotes` VOs · P-1 | §2c · §2e P-1 · §5g Specification | `035` · `036` · `038` · `057` | named rule, ⌈2n/3⌉ round-up, constituted denominator |
| `PolicyBinding` VO | §2c · §5g Memento | `050`(b) · `014` Part 2 · `059`(a) | version + duration captured at period start, never retroactively altered |
| `ClockReading` VO · P-5 | §2c · §2e P-5 · DD-1 | `060` · `061` · `062` · `065` | clocks are computations over recorded intervals; never countdowns; two clocks never accrue at once |
| `TerminalStatePlaceholder` → *Election Discontinued* | §2c VO · B-4 | `063` · **`EM-GOV-069`** (D-2 discharged) | purely terminal; Discontinued ≠ Cancelled; two *cancelled* levels distinct types (D-9) |
| Condition types OPEN/HALTED/INOPERATIVE/TERMINAL · P-2/P-3/P-4 | §2b · §2e · §5d | `068` · `052` · `011`/`012` · `065` · `058` · `059`(b) · `063` | four distinct conditions; OPEN derived, **no clock, no deadline, no expiry hook**; HALTED ∧ INOPERATIVE representable |
| P-6 `ExpiryConsequence` | §2e | `063` · `058` · `047`-resolution | expiry is **reported**, the rule supplies the consequence; three facts recorded at terminal |
| P-7 `ResumptionTarget` | §2e | `059`(c) · `060` | return to the unresolved gate; never a deemed decision |
| Domain events (10, placeholder-named) | §5f | per-event: `005` · `031` · `052` · `056` · `058` · `059` · `063` · `064` · `065` · `067` | every material fact recorded; **no event for a phase that never occurred** (`005` negative half) |
| `ProtocolAppend` port | §5c driven · B-7 | `F-PROTO-1` 1–7 · `P-2H` · `005` · `EM-VOC-008` | append-only in meaning; two histories never merged; refusal recordable; records, never decides |
| `ServicePolicySnapshot` port | §5c · B-5 (ACL) | `014` Part 2 · `059`(a) · `047`-resolution | hands over `(version, duration)` only; can never supply a consequence |
| `OrganisationalAppointmentAuthority` port — **no adapter** | §5c · B-3 · R-F4 | `028` · `049` · `066` · `094` · `EM-OPEN-049` safeguard | external wall; absence ⇒ election cannot be constituted; **no substitute manufactured** |
| `InstantSource` port | §5c | `EM-OPEN-024` interim rule (D-8) | recording instants only; no civil-time meaning fixed |
| Repository interfaces (AG-1/2/3 only) | §5c · repo Rule 9 | layer rules (`.claude/CLAUDE.md`) · B-7 | aggregate persistence contracts; state a projection of recorded facts |

**Elements deliberately absent, each because a rule forbids or nothing requires it:** OPEN timeout/expiry (D-4, G-3) · C-2 classifier (D-5) · Committee abstention (D-9) · authority adapter or fallback (D-1) · configuration menu capability (D-6 — adopted `036` consumed directly) · canonical event vocabulary (D-7) · timezone semantics (D-8) · generic state machine collapsing the four conditions (G-3/G-4) · "reconstitution" concept (B-2) · notification duty on pre-Chief halts (`067` expressly declined it) · representative-channel anything (§1c — not participating in Model A).

## D · D-1…D-9 treatment

Per the accepted reconciliation — **treated as binding; nothing reopened here:**

| # | Classification (reconciliation) | Implementation treatment in this increment |
|---|---|---|
| **D-1** | **External boundary** — intentionally unresolved | implement the port **interface** and stop; no adapter, default, stub, fallback, or identity assumption; the two authorities (Committee/Deputy vs Chief) stay distinct in the port model |
| **D-2** | **Discharged** (`EM-GOV-069`) | `TerminalStatePlaceholder` renders as **Election Discontinued**; no other terminal word used or aliased |
| **D-3** | **Implemented constraint** | gate subject stays abstract; no concrete first-gate binding anywhere, including in tests and fixtures |
| **D-4** | **Implemented constraint** | nothing attaches to OPEN or to inaction — no timer, escalation, expiry hook, or maximum duration; building **nothing** is the faithful representation |
| **D-5** | **Implemented constraint** | no C-2 classifier component; P-2 realizes decided-failure and mathematical impossibility on recorded facts completely; no third exit |
| **D-6** | **Implemented constraint** | adopted `036` consumed directly via `ThresholdRule`/P-1; no menu capability designed or built |
| **D-7** | **Implemented constraint** | event type names carry an explicit non-canonical marking; business rendering awaits `EM-OPEN-045`; recorded facts are fixed, names are not |
| **D-8** | **Implemented constraint** | clocks compute between recorded instants only; `InstantSource` has no schedule semantics; no timezone meaning fixed |
| **D-9** | **Implemented constraint** | `AcceptancePosition` closed at accept/object; opportunity-level *cancelled* and election-level cancellation are distinct types never one value; no type named *outcome* is built |

**No category-① blocker exists.** This matches the reconciliation's conclusion verbatim and is not re-derived here.

## E · RED-test inventory

The first failing tests, written **before** any production code, each expressing an approved business rule (not implementation structure). All are pure domain unit tests plus a small set of structural/architecture tests — no database, no framework, no protocol store (a test double satisfies the `ProtocolAppend` **contract**; choosing a real store is out of increment). **25 RED tests:**

**AG-1 `ElectionCommittee` (6):**
1. Constitution with fewer than 3 members is rejected (I-1, `033`).
2. A seat becomes vacant only by a recorded `VacancyEvent` with one of the three closed grounds; any other ground is rejected (I-2, `064`).
3. Temporary unavailability changes nothing structural — no vacancy, no filling trigger, denominator unchanged (I-2, `064`).
4. Denominator is the constituted membership: vacancy does not reduce it, filling does not change it (I-3, `057`).
5. Filling re-occupies the existing seat — no new membership, no reopened appointment, no altered decision (I-4, `056`).
6. `unableToFunction` is derived as non-vacant < required and is not independently settable (P-3, `065`).

**AG-2 `AcceptanceGateDecision` (8):**
7. Required votes = ⌈2·constituted/3⌉: 3→2, 4→3, 5→4, 6→4, 7→5 (P-1, `036`/`038`/`057`).
8. Evaluation applies the **named** rule token, never a numeric percentage (I-10, `035`).
9. A seat expresses at most one position per acceptance decision (I-7, `066`).
10. A validly cast Committee Vote stands after later vacancy, resignation, or seat-filling (I-8, `066`).
11. A replacement occupant may express positions only where the seat has not yet expressed one (I-9, `066`).
12. Interval state is derived on recorded facts: OPEN iff undecided ∧ achievable; decided-pass iff accepts ≥ required; decided-failure iff satisfaction impossible by decision (I-11, `068`, P-2).
13. **S-06:** temporary unavailability with non-vacant ≥ required ⇒ gate OPEN, no clock accrues at any moment; at 3/2 a single remaining member can neither pass nor decidedly fail — no third exit (R-F2, `068`/`062`/`064`).
14. Dissent is recorded even where the threshold is achieved (I-12, `031`/`005`).

**VOs and closed sets (3):**
15. `AcceptancePosition` admits exactly accept and object; no abstention value is constructible (D-9, G-1).
16. `VacancyGround` admits exactly the three grounds of `064`.
17. `TerminalStatePlaceholder` renders as *Election Discontinued* and as nothing else; it is distinct in type from election-level cancellation (`058`) and from opportunity-level *cancelled* (`EM-VOC-004`) (D-2/D-9, `EM-GOV-069`).

**AG-3 `RecoveryProcess` and clocks (5):**
18. `PolicyBinding` (version + duration) is captured at period start; a later policy change never alters a running period (I-13, `050`(b)).
19. Clock accrual sums only intervals while the triggering condition is active; the halted clock and the restoration clock never accrue over the same instant (I-14, P-5, `062`).
20. Pause on Inoperative onset is non-retroactive; a deadline already legitimately expired stays expired (`060`).
21. Resumption resumes the remaining portion only; no minimum guaranteed, no time created; the restoration allowance is per election and is never renewed by a repeated failure (I-15/I-16, `060`/`061`).
22. The two period kinds are distinct types and cannot be merged or substituted for one another (`059`(a)).

**Condition semantics and consequences (3):**
23. Inoperative begins **at** the causing recorded vacancy event — no declaration; the same event ends OPEN (achievability lost) so the two derived classifications can never disagree (P-4, `065`, §2b).
24. `HALTED ∧ INOPERATIVE` is representable and both facts are retained; Inoperative is dominant but does not erase the halt (`059`(b)).
25. P-6: reported halted-recovery expiry with halt present ∧ period expired ∧ recovery not succeeded ⇒ records expiry + failed recovery + resulting state ⇒ terminal placeholder; it **cannot** fire while Inoperative; reported restoration expiry ⇒ election-level cancellation (`063`/`058`/`062`; `047`-resolution: the clock decides nothing).

**Structural / architecture guards (counted within the 25 above where behavioural; the following are additional standing assertions folded into the suite):** no adapter for `OrganisationalAppointmentAuthority` exists anywhere (D-1) · no timer/expiry hook is attachable to OPEN (D-4/G-3) · the Domain namespace imports no framework symbol (layer rule) · no C-2 classifier type exists (D-5) · no domain type or event payload exposes a voter↔vote linkage surface, and free-text reason fields are constrained surfaces (ADR-T11, §5e).

Per C-4 of the prepared act: tests land as **separate commits before** their production counterparts, so RED→GREEN ordering is provable.

## F · Potential stop conditions

1. **The signature itself (blocking now).** No PO signature, grant, and START ⇒ nothing proceeds. This is the current state.
2. **Boundary precision (at signature, not after).** Whether "domain core + protocol port contracts" includes the domain events and the three non-protocol port interfaces (`ServicePolicySnapshot`, `OrganisationalAppointmentAuthority`, `InstantSource`) as read in §B — the PO's boundary statement should confirm or trim; implementation will not decide it silently.
3. **Any need for a concrete gate subject (D-3).** If any test, fixture, or type cannot be written without naming the first gate's decision subject ⇒ STOP; the increment must keep the subject abstract or the branch is out of scope.
4. **Any need for canonical event vocabulary (D-7 / `EM-OPEN-045`).** If any surface demands a business-facing event name ⇒ STOP; only marked non-canonical technical identifiers are available.
5. **Any pressure to bound OPEN (D-4).** If a test-determinism or lifecycle-integration concern appears to "need" an OPEN duration ⇒ STOP; the answer is a recorded-facts fixture, never a bound.
6. **Any technology pull (G-6 / C-6).** If contract-testing `ProtocolAppend` appears to require a real store, queue, scheduler, or framework artifact ⇒ STOP and raise a separate architectural decision; test doubles are the increment's ceiling.
7. **Lifecycle wiring temptation (§5h).** If the domain core cannot express "progression halted at the gate condition" without importing `ElectionConstitution` internals (`app/Domain/Election/Constitution/`) ⇒ STOP; that wiring is application-layer and a later increment.
8. **Namespace collision.** The operating core's placement beside the existing determination/correction subsystem in `app/Contexts/Election/Domain/` is an implementation decision to be made visibly at START; any restructuring of existing code it seems to invite ⇒ out of scope, backlog it.

## G · Authorization readiness

**YES** — the bounded first increment can be implemented without inventing business behaviour and without an unauthorized technology or architecture decision: every element in §B traces to an adopted rule or approved design element (§C); every surviving dependency is an executable constraint or an intentionally unresolved external wall (§D); the RED suite (§E) expresses adopted rules only; and the increment requires no framework, storage, scheduler, or eventing choice.

**Implementation does not start on this YES.** It starts only upon the PO's signature of `EM-IMPL-001` with the boundary fixed, followed by grant and START as separate acts (prepared act §3; C-5: independent verification follows in its own lane — this lane never self-certifies).

---

**Traceability.** Prepared `EM-IMPL-001` · approved `EM-ARCH-001` design §§2–7 · dependency reconciliation (accepted) · `EM-BRQ-001` final qualification report §§1/4 · Election Manifesto ADOPTED rows (`016`–`069` as cited) · `EM-GOV-069` · ADR-T11 · `F-PROTO-1` · `P-2H` · repo standing layer rules · doc-placement exit 0 → `docs/publicdigit` · A-3.
