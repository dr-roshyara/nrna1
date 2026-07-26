# EPIC-004E Determination Invariants

**Kind:** Tactical DDD artifact №4 (one artifact → ARB review → refine → freeze → next). **Role:** implementation architect from the frozen baseline. This artifact **formalizes the invariants that implement the six protected domain truths** frozen in artifact №3. It designs nothing executable — no methods, events, commands, VOs, repositories, services; invariants are stated as testable propositions with named enforcement seats.
**The load-bearing distinction (reserved for this artifact by the ARB):** **a Business Truth is *implemented by* one or more Business Invariants.** The truth is what the business asserts about the world; the invariant is the testable proposition whose preservation keeps the assertion true. The aggregate protects the invariant *in order to* preserve the truth.
**Entry condition (ARB, binding):** *every invariant must directly preserve at least one protected domain truth accepted in artifact №3; no invariant may be introduced unless its protection target is traceable to an accepted responsibility.* Nothing appears here "because it seems useful" — every invariant has a parent.
**Litmus test (ARB, binding — business invariants only):** *would a domain expert recognize this rule as part of the business, even if the software didn't exist?* Implementation constraints ("status cannot be null", "createdAt is immutable") are NOT domain invariants — they belong to implementation, persistence, or validation. **Consequence for this artifact:** invariant propositions are stated in business language; transactional/storage mechanics appear only in the enforcement-seat line, never in the proposition.
**Aggregate Protection Principle (APP — adopted as permanent governance, recorded in MEMORY):** *an aggregate exists to protect domain properties (finality, uniqueness, attribution, authenticity, integrity), not merely to encapsulate domain objects — objects are the vehicle; protected properties are the purpose.*
**Inputs (frozen):** artifact №3's six accepted responsibilities + protected truths · the R-3 boundary finding · the Q-1 Resolution and its binding constraint · the four Constitutional Policies · ADR-T1/T11/T14/T19 · the qualified implementation and its test suite (cited as enforcement evidence, never extended here).

---

## Truth → Invariant map

| # | Protected domain truth (artifact №3) | Implementing invariant(s) |
|---|---|---|
| T-1 | The record's history is lawful; its finality is absolute | INV-1, INV-2 |
| T-2 | Each determination represents exactly one act of ruling | INV-3 |
| T-4 | The ruling the world sees is the ruling that was decided — never a reconstruction | INV-4 |
| T-5 | Every determination has an accountable issuer | INV-5 |
| T-6 | Answerability — who, what jurisdiction, what evidence — from the record alone | INV-6 |
| T-7 | Ballot secrecy at its most permanent point | INV-7 |
| *(boundary)* | At most one determination exists per challenge | INV-B1 *(issuance boundary, not aggregate — per the frozen R-3 finding)* |

## The invariants

### INV-1 — Lawful progression only
- **Proposition (business language):** a ruling is prepared, then issued, then becomes final — in that order and no other; an unlawful step leaves the record exactly as it was, with nothing announced.
- **Enforcement seat:** the aggregate's transition guards over its Draft → Issued → Final states (the state machine is the mechanism, not the rule).
- **Evidence of enforcement (existing):** `DeterminationTest` — cannot-issue-twice, cannot-finalize-a-draft, illegal-transition exceptions naming state and command; guard-throws-without-mutation behavior.
- **Status:** implemented and pinned.

### INV-2 — Final is absolutely terminal
- **Proposition:** from Final, no transition succeeds and no event is ever emitted.
- **Enforcement seat:** the aggregate.
- **Evidence:** `no_event_emitted_after_final`; Round 50-07 ("Final emits nothing").
- **Status:** implemented and pinned.

### INV-3 — One act of ruling per determination
- **Proposition:** `issue` succeeds at most once per determination instance, and only from Draft; exactly one ruling-bearing event exists per issued determination.
- **Enforcement seat:** the aggregate (instance state at commit time).
- **Evidence:** `cannot_issue_twice`; the issue-from-Draft guard.
- **Status:** implemented and pinned.

### INV-4 — Fixation is one fact
- **Proposition (business language):** the ruling's content — what was decided, on what legitimacy, for what reason, resting on which evidence — is fixed at the moment of issuance, inseparably from the act of issuing; the record of the ruling and the announcement of the ruling can never diverge, and nothing revises either afterward.
- **Enforcement seat:** the aggregate (fixation-at-issue) **together with** the transactional wrapper (ADR-T1: one transaction = aggregate write + the ruling-bearing outbox row) — one invariant, two cooperating seats, honestly named; the transaction and the outbox are mechanics of the seat, not part of the business rule.
- **Evidence:** ADR-T19 Model B (content lives in the event, not mutable state — revision is structurally impossible, not merely forbidden); `AdjudicationServiceIntegrationTest` (row + exactly one outbox event, atomically).
- **Status:** implemented and pinned. **Deferred rider (unchanged from №3):** whether the fixation basis expands to an evidence-*set* (R-4-expanded) awaits the Process Manager design; this invariant's proposition is stated over the current single-envelope basis and does not foreclose the expansion.

### INV-5 — No unattributed record, ever
- **Proposition:** a determination cannot come into existence without a non-empty authority assertion, evidence reference, and challenge reference; refusal is atomic non-creation.
- **Enforcement seat:** the aggregate's construction/issue path (the VOs' non-empty rules are its current mechanism).
- **Evidence:** `DeterminationValueObjectsTest` (every VO rejects empty/whitespace); "A determination requires an evidence reference."
- **Status:** implemented and pinned. **Bounded by Q-1's constraint:** presence, never validity — validity is Governance's; any future consultation of Governance's published contract sits *outside* this invariant's seat, by design.

### INV-6 — The accountability triple is inseparable from the record
- **Proposition:** every issued ruling permanently carries who issued it, under what jurisdiction, on what evidence — inside the same fixed fact as the ruling content, never in a separable side record.
- **Enforcement seat:** the aggregate (the triple is part of INV-4's single fixation).
- **Evidence:** the issue-time event carries all three; `DeterminationIssuedTest` payload-shape lock.
- **Status:** implemented and pinned.
- **Finding on the offered R-4/R-6 merge (this artifact's to make, per the freeze note):** INV-4 and INV-6 are **one atomic fact protecting two distinct truths** — fixation-integrity (T-4) and attribution (T-6). Recommendation: **keep the truths distinct, acknowledge the shared fact.** Merging the truths would blur *why* the fact matters twice; merging nothing loses nothing, since the enforcement is already shared. *(ARB decides at review.)*

### INV-7 — No linkage-enabling content in the permanent record
- **Proposition:** nothing the aggregate fixes or emits contains, or permits derivation of, voter↔vote linkage.
- **Enforcement seat:** the aggregate at fixation (refusal of forbidden content) — **shared with** the architecture-fitness layer (AT-Q7 scans) as the falsification probe; the aggregate's seat is the unbypassable one.
- **Evidence:** ADR-T11 discipline across every VO; AT-Q7 fitness test scanning the messaging surface.
- **Status:** implemented (structurally: no voter-identifying field exists to fix) and pinned by fitness.

### INV-B1 — At most one determination per challenge *(boundary invariant — deliberately NOT the aggregate's)*
- **Proposition:** across all determinations in an organisation, at most one exists per challenge reference.
- **Enforcement seat:** the **issuance boundary** — the application service's uniqueness check + the DB unique index (`uniq_determination_per_challenge`) as the concurrency backstop. Recorded here so the invariant catalogue is complete; its seat assignment is the frozen R-3 finding, not revisited.
- **Evidence:** `reissue_same_challenge_throws_and_keeps_single_row` (service guard + single row after duplicate attempt).
- **Status:** implemented and pinned.

## Explicitly not invariants of this aggregate (with grounds)

- **"The issuing authority holds a valid delegation"** — Governance's truth, Governance's invariant (Q-1 binding constraint); Adjudication's related invariant is INV-5's presence rule only.
- **"The evidence in the envelope is intact/custodial"** — Custodial Integrity's territory (COL-3a); the aggregate holds a reference, not the evidence.
- **"The correction supersedes rather than replaces"** — Constitutional Policy 1's invariant, seated on the publication side.
- **"Evidence survives until the challenge window closes"** — the Evidence Preservation Window (Q-2, open); a records-management invariant, not the ruling record's.
- **"The deliberation concluded before issuance"** — the Process Manager's coordination concern; when designed, it may *request* issuance but its sequencing rules are its own.

## Self-review

**The litmus test discriminated on first application (Methodological Fitness Rule satisfied for it immediately):** INV-1's and INV-4's original propositions carried implementation vocabulary (state-machine labels; "transaction"/"outbox row") — both re-worded into business language with the mechanics demoted to their enforcement-seat lines. A domain expert now recognizes every proposition without the software existing ✅ · **Entry condition held:** every invariant's parent truth is named in the map; INV-B1's parent is the frozen R-3 boundary finding ✅ · Every invariant implements a named frozen truth — none is free-floating ✅ · every invariant is a testable proposition with a named enforcement seat; where a seat is shared (INV-4's transaction wrapper; INV-7's fitness probe), the sharing is stated honestly rather than idealized ✅ · every implemented invariant cites its existing enforcement evidence — nothing was designed, everything was formalized ✅ · the boundary invariant (INV-B1) is catalogued without reopening the frozen R-3 seat assignment ✅ · the R-4/R-6 merge question is answered with a finding (one fact, two truths — keep truths distinct) and returned to the ARB ✅ · the truth/invariant distinction did real work: seven truths yielded eight invariants, one of which (INV-B1) lives outside the aggregate — the distinction separated *what is protected* from *where protection sits* ✅ · Q-1 constraint, Q-2 openness, and the R-4-expanded deferral all preserved ✅ · no methods, events, commands, VOs, repositories, or services designed ✅.

**Stop condition: STOP.** Invariants only. Value Objects (artifact №5) and everything after begin on explicit ARB opening, after this artifact's review → refine → freeze.

---
*Frozen inputs: `EPIC-004D` (artifact №3, FROZEN — the six truths) · `EPIC-004C` (complete) · `EPIC-004_Q1_Authority_Resolution.md` · the four Constitutional Policies · ADR-T1/T11/T14/T19 · Enforcement evidence: the qualified Adjudication test suite (EPIC-003 inventory).*
