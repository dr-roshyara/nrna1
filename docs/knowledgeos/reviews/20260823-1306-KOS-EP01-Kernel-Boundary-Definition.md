# KnowledgeOS — Kernel Boundary Definition — final architectural consolidation + the smallest authoritative KnowledgeCore boundary (2026-08-23)

> **Role:** the commissioned **Kernel Boundary Definition** act — the last DDD step before the Kernel implementation decision. Phase 1 architectural consolidation + Phase 2 boundary definition, producing the thirteen prescribed outputs, the five mandatory investigations, and a **separate adversarial critic pass**.
> **Commission:** HPA, 2026-08-23 — *"Proceed with the commissioned Kernel Boundary Definition… Treat A–E as architectural questions to be investigated by the boundary act, not as reasons to reopen research."* Handoff: `docs/plans/20260823-1241-kernel-boundary-definition-handoff-plan.md` §7 (the approved scope).
> **Position:** P5 ✅ → AH-1…AH-5 ✅ (gate CLOSED) → T-2/T-3 ✅ → T-5 ✅ → OQ-2 ✅ → OQ-3 ✅ → OQ-5 ✅ → F-1…F-5 ✅ → **← THIS ACT** → HPA ruling → Kernel capability mapping → implementation decision.
> **Status:** ✅ **RULED — HPA 2026-08-23 (§10): ACCEPTED as the architectural decision-support basis, subject to the rulings recorded there** (ZERO-DEFECT-1 reading (b) · ZERO-DEFECT-2 reading (b) · K-1 ACCEPT · UQ-3/UQ-4 DEFER · DEF-1 REMAINS DEFERRED · the anti-reasoner constraint carried forward as a mandatory capability fitness constraint). **Boundary ACCEPTED · Kernel NOT built · capability mapping NOT begun · implementation NOT authorized.** *(Status as delivered, preserved: 📋 DELIVERED · PROPOSED · NON-AUTHORITATIVE · HPA DECISION REQUIRED.)* No code · no technology · no research · no DSL · no parser · no FST · no SNF · no redesign · no v1.2 · register **25+4 unchanged** · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · invariants **11→11** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **UNAUTHORIZED**. **Nothing is adopted by this act.**
> **Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos --maturity=research` → `docs/knowledgeos` (exit 0).

---

## 0 · The answer, in one page

**The question (§7.1 of the commission):** *what is the smallest authoritative KnowledgeCore boundary capable of preserving all eleven invariants and hosting the nine established MUST-EXIST responsibilities, without importing semantic interpretation, reasoning, governance authority, workflow execution, evidence-mechanism responsibility, or infrastructure?*

**The answer:**

> **The smallest authoritative KnowledgeCore boundary is the *KnowledgeCore Admission Boundary*: it contains exactly two existing aggregates — `KnowledgeAggregate` and `ConflictRecord`, which remain *separate* aggregate consistency boundaries — admits state transitions through the Verification Port as its single inbound gate, and preserves three of the eleven invariants as boundary *refusals* whose records live in supporting contexts.**

> ⚖️ **POST-RULING PRECISION (HPA, 2026-08-23 · §10).** This sentence originally read *"the KnowledgeAggregate consistency boundary together with ConflictRecord."* That conflated two altitudes: an aggregate **is** a consistency boundary, so two aggregates cannot share one. The admission boundary is **one gate over two consistency boundaries**; their coordination is **cross-aggregate** and remains subject to **DEF-1**. Corrected as a consequence of the ruling, per the HPA's direction.

Stated as a structure — **no new element is created; every element below already exists in v1.1:**

```
                    ┌─ Expression↔Meaning Port (ACL) ─┐   candidates only
   mechanisms ─────▶│  conformance gate — OUTSIDE     │   (never state, never identity)
                    └──────────────┬──────────────────┘
                                   ▼
              ╔════════ Verification Port ═════════╗  ← the SINGLE admission gate
              ║  KnowledgeCore ADMISSION BOUNDARY    ║     contract INSIDE (published language)
              ║                                     ║     adapter OUTSIDE (mechanism)
              ║   KnowledgeAggregate (12 members)   ║
              ║   ConflictRecord                    ║
              ║   the eleven invariants             ║
              ║                                     ║
              ║   assigns identity · admits meaning ║
              ║   admits evidence links · evaluates ║
              ║   justification sufficiency ·       ║
              ║   determines epistemic state ·      ║
              ║   assigns confidence · records      ║
              ║   history — forward-only            ║
              ╚═════╤════════════════════╤══════════╝
        advisory    │                    │  read-only, no write-back
                    ▼                    ▼
           Decision Boundary        Projection
        (record lives outside)  (record lives outside)
```

**Three findings that shape it:**

1. **The boundary is strictly *smaller* than the §16 KERNEL altitude.** §16's KERNEL altitude groups *five* aggregates (the two core ones plus AuthorityGrant · DerivedView · DecisionRecord) for **change-governance** purposes. The admission boundary groups *two* for **consistency** purposes. Both are lawful; they are different notions wearing one word (**FINDING K-1**, §3).
2. **Two of the thirteen requested outputs are deferred by v1.1's own law.** "Domain services" and "Commands" are the realization form of the boundary, which **DEF-1** defers to Logical/Implementation Architecture (v1.1 §6 altitude note, §20). They are answered as *deferred*, not invented (**§4**).
3. **The pre-admission rejection path contains a genuine, previously unrecorded boundary defect.** Of the three gate-refusal causes in §9, **one is representable and two are not** (**ZERO-DEFECT-1/2**, §2). This is recorded for HPA ruling, not resolved here.

---

## 1 · PHASE 1 · Architectural consolidation — the authoritative input set

Assembled by reading each source directly; **none silently changed.** Line references are to the cited artifacts.

| # | Input | Verified content consumed | Status |
|---|---|---|---|
| 1 | **Reference Architecture v1.1** (`20260822-1402`, 677 ln) | §4 core domain + §4.3 minimality argument · §5.2 context map · §5.3 the six contexts · §6 aggregate + the **altitude note** · §6.1 ConflictRecord · §6.2 supporting aggregates · §7 eleven invariants + enforcement loci · §8 ten events + ⟨A-3⟩ · §9 epistemic lifecycle · §14 Zero placement + ⟨C-5⟩ · §16 altitudes · §17 rejected concepts · §18 D-1…D-6 · §19 quality gates · §20 DEF-1…DEF-5 / OQ-1…OQ-5 | ✅ FROZEN — consumed, unchanged |
| 2 | **KnowledgeCore / aggregate model** | root `Knowledge` · KnowledgeId · twelve members · ConflictRecord (ConflictState · Resolution) · three supporting aggregates | ✅ 12→12 · 5→5 |
| 3 | **The eleven invariants** | IDENTITY · DIMENSION · AUTHORITY · DECISION · PROJECTION · VERIFICATION · FAILURE · CONTRADICTION · UNKNOWN · AGENCY · HISTORY, each with its §7 enforcement locus | ✅ 11→11 |
| 4 | **OQ-2 ruling** (`20260823-0931`) | ACCEPT — SNF-equivalence admissible as an EvidenceLink, never an identity mechanism | ✅ CLOSED |
| 5 | **OQ-3 ruling** (`20260823-0953`) | ACCEPT — cross-language sameness disposition | ✅ CLOSED |
| 6 | **OQ-5 ruling** (`20260823-1014`) | ACCEPT — Confidence **remains** an aggregate member, ⟨R-1⟩ assignment inside the boundary | ✅ CLOSED — **not reopened by this act** |
| 7 | **F-1…F-5 findings** (`20260823-1051`, 430 ln) | verdict: **no new capability** — nine MUST EXIST · mechanism-side MAY EXIST · five MUST NOT EXIST IN KERNEL · OUTSIDE set · §9 ten preconditions | ✅ DELIVERED |
| 8 | **AH-1…AH-5 final dispositions** (`20260822-2346`) | **gate CLOSED, rulings FINAL:** AH-1 ACCEPT · AH-2 REJECT/CORROBORATION · AH-3 ACCEPT · AH-4 ACCEPT AS GOVERNANCE · AH-5 **DEFER** | ✅ CLOSED — see **CONS-1** |
| 9 | **Port Contract r4** (`20260822-1559`, 216 ln) | §1 pipeline · §2 six obligations · §3 Q1–Q8 · §4 candidate vocabulary (**r4-4**: port vocabulary is never an aggregate member) · §5 trust boundary · §6 what it does not decide | ⬜ **PROPOSED · NON-AUTHORITATIVE** |
| 10 | **Current capability map** | §4.1/4.2/4.3/4.4 of the handoff, traced to their v1.1 loci | ✅ consumed |

### 1.1 Consolidation observations (recorded, not decided)

- **CONS-1 · one stale status corrected in the input set, not in the historical artifact.** The handoff §3 row 2 records AH-1…AH-5 as *"decision fields ⬜ OPEN (HPA)"*. The authoritative record (`20260822-2346`) states the **gate is CLOSED and the five rulings are FINAL**. This act consumes the authoritative record. Per ES-004.3 and the HPA's instruction not to modify historical artifacts for terminology, **the handoff is left as written**; the correction lives here, in the input set. What remains genuinely outstanding is AH-4's derivative *governance recording* and AH-5's deliberate *deferral* — neither is a reopenable ruling.
- **CONS-2 · precondition 9 is discharged, not blocking.** The handoff §5 table marks it ⬜ PENDING; §7.4 Phase 2 defines the obligation as *recording* the contract's status and not assuming ratification. This act adopts the §7.4 reading and discharges it: **the Port Contract is PROPOSED · NON-AUTHORITATIVE; the F-1…F-5 amendments are not applied; ratification is a separate HPA act; no boundary claim below depends on either.** See **ATTACK 2** for the residual risk this leaves.
- **CONS-3 · open items carried, none decided.** F-1…F-5 amendment candidates ⬜ OPEN · AH-4 governance recording ⬜ outstanding · AH-5 ⬜ deferred (must not be resolved by inference) · DEF-1 · DEF-4 · DEF-5 ⬜ deferred by altitude · OQ-1 ⬜ open · OQ-4 **UNAUTHORIZED** · Port Contract ratification ⬜ pending. Each keeps its owner.

---

## 2 · MANDATORY INVESTIGATION 1 · The domain meaning of `REJECTED`

### 2.1 What the law actually says

| Source | Statement |
|---|---|
| v1.1 **§9** lifecycle | the gate asks **three** questions — *justification path preserved? · agency present? · context present?* — and has two outcomes: **KnowledgeRejected** *(REJECTED, kept)* or **KnowledgeCreated** *(initial state **UNKNOWN**)* |
| v1.1 **§8** events | **KnowledgeRejected** — trigger *"a candidate failed verification"*; aggregate effect *"preserved as REJECTED; **never discarded, never knowledge**"*. **Note the asymmetry:** KnowledgeCreated's effect says *"aggregate created; identity assigned; agency recorded"*; KnowledgeRejected's does **not** say an aggregate is created |
| v1.1 **§8 ⟨A-3⟩** | *"An event records a domain state transition… **If nothing in the aggregate changes, there is no domain event.**"* |
| **INV-KOS-FAILURE-001** | failed reasoning preserved as an explicit state; **never knowledge**, never silently discarded. Enforcement locus: **EpistemicState (REJECTED) + History** — both aggregate members |
| **F-4 / D-5** | non-admitted candidates are **not** domain state; **no candidate store**; no hidden aggregate |
| **Port obligations 3 & 6 · ⟨C-5⟩** | mechanism failure, silence, or declared insufficiency maps to **UNKNOWN** — never ABSENT, never FALSE, never a low-confidence accept |

### 2.2 The four distinctions, drawn

| # | Situation | Domain classification | Lawful outcome | Verdict |
|---|---|---|---|---|
| **R-1** | **Pre-admission rejection** — a candidate fails a §9 gate check | contested — see §2.3 | `KnowledgeRejected` → REJECTED, kept | ⚠️ **partially unrepresentable** |
| **R-2** | **Post-admission REJECTED state** — an admitted knowledge's justification later fails | **domain state transition** of an existing aggregate | `BeliefRevised` → EpistemicState REJECTED; prior state → History | ✅ **clean** |
| **R-3** | **Mechanism-side refusal** — the mechanism declares it did not determine (`declared insufficiency`) | **outside the boundary**; a port-vocabulary declaration | admitted with EpistemicState **UNKNOWN** — **never REJECTED** | ✅ **clean** |
| **R-4** | **Conformance failure** — a candidate violates a port obligation (carries a verdict, proposes a KnowledgeId, emits a scalar) | **outside the boundary**, at the ACL | refused at the ACL; **no domain event** (conformance ≠ admission) | ✅ **clean** |

**The three clean cases settle most of the ambiguity.** R-3 is the most consequential: **a mechanism's inability is never rejection** — it is admitted knowledge in state UNKNOWN. Conflating R-3 with R-1 would let a parser failure look like an epistemic verdict, which ⟨C-5⟩ exists to forbid.

### 2.3 The defect in R-1

A formal argument from the law, with no step supplied by this act:

1. `KnowledgeRejected` is one of the ten **admitted** domain events (§8).
2. ⟨A-3⟩: an event exists only if something in the aggregate changes.
3. ∴ a gate refusal must change aggregate state — it cannot be a purely external occurrence.
4. INV-KOS-FAILURE-001 enforces the preservation through **EpistemicState + History**, both aggregate *members* — which presuppose an aggregate instance, whose root is identified by a **KnowledgeId** (§6).
5. F-4/D-5 forbid a candidate store, so the retained thing cannot be the candidate payload; it can only be **the domain's own act of refusing**.

**A reading that satisfies 1–5 requires one distinction the law does not currently state: *entry into the aggregate* ≠ *entry into knowledge*.** Under it the Verification Port has two lawful outcomes, both aggregate transitions: pass → `KnowledgeCreated` (state UNKNOWN, inside knowledge's lifecycle); fail → `KnowledgeRejected` (state REJECTED, **a domain state that is not knowledge**). INV-KOS-VERIFICATION-001 survives because REJECTED is not knowledge; F-4 survives because no candidate is stored.

**But two of §9's three gate causes then become unrepresentable:**

| Gate cause | Required member it lacks | Invariant that breaks | Verdict |
|---|---|---|---|
| **justification path absent** | JustificationPath | none — INV-KOS-VERIFICATION-001 forbids entry into **knowledge** without a path, and REJECTED is **not knowledge** | ✅ **REPRESENTABLE** |
| **agency absent** | Agency — *"the one member whose absence rejects creation"* (§6) | **INV-KOS-AGENCY-001**: *"**Every state** preserves epistemic agency"* — scoped to every **state**, not merely to knowledge. A REJECTED record created *because* agency was absent is a state without agency | ❌ **ZERO-DEFECT-1** |
| **context absent** | ContextTuple — and *"context is part of identity"* (Article 1.4); §7 enforcement: *"every context-less claim rejected as incomplete"* | **INV-KOS-IDENTITY-001**: the root must bear a KnowledgeId, an identity-of-meaning of which context is constitutive. A context-less record cannot bear a well-formed identity | ❌ **ZERO-DEFECT-2** |

**Two candidate resolutions — neither adopted here; the ruling is the HPA's:**

- **(a) Rejector-agency reading.** A rejection record carries the **rejecting domain act's** agency and a provisional identity, not the candidate's. Cost: it changes what `Agency` denotes (from the knowledge's epistemic lineage to, in this one case, the refusal's authorship) and admits a KnowledgeId for an incompletely-contextualized item. It touches two invariants' meaning — an amendment-class change.
- **(b) Pre-domain reading (this act's recommendation, offered as evidence only).** `KnowledgeRejected` covers **R-2** and the **justification-absent** case only. Agency-absent and context-absent candidates never reach the aggregate: they are refused *at the ACL* alongside **R-4**, retained mechanism-side, and produce **no domain event** — consistent with ⟨A-3⟩ read strictly. Cost: **v1.1 §9's diagram is imprecise**, because it routes all three checks to `KnowledgeRejected`. That is a documentation-level correction of a *frozen* artifact, so it is an HPA act, not an editorial one.

**Recorded, not resolved *by this act*.** Reading (b) is smaller — it changes a diagram, not two invariants' meanings — and it is corroborated independently by **§3.1** below, where identity-assignment's zero case forces the same conclusion. This act stated the evidence and stopped.

> ⚖️ **RULED — HPA, 2026-08-23 (§10): reading (b) ACCEPTED for both defects.** An **agency-absent** or **context-absent** candidate **does not enter KnowledgeCore**: no `KnowledgeAggregate`, no `KnowledgeId`, no epistemic state, **no domain event**. **Rejector-agency and provisional identity are explicitly refused** — they would alter the meaning of existing invariants. The HPA's governing principle, recorded verbatim: **"absence of a constitutive prerequisite is not an epistemic state."** Consequence carried forward: `KnowledgeRejected` covers **R-2** and the **justification-path-absent** case only, and v1.1 §9's diagram — which routes all three gate causes to `KnowledgeRejected` — is **imprecise and awaits a separate governed correction of the frozen artifact.** This act performs no such correction.

---

## 3 · MANDATORY INVESTIGATION 2 · What does "Kernel" denote?

Four candidate referents were tested. **No fourth structure is created** (ES-005.4).

| Candidate | What it is in v1.1 | Test against §7.1 | Ruling |
|---|---|---|---|
| **§16 KERNEL altitude** | a **change-governance** grouping: *"the eleven invariants · KnowledgeAggregate · ConflictRecord · the three small supporting aggregates"* — may not change except by HPA amendment | **FAILS the no-import constraint.** It includes AuthorityGrant · DerivedView · DecisionRecord, which live in the **Authority · Projection · Decision Boundary** contexts. Adopting it would import governance-authority and projection responsibility into the boundary — exactly what §7.1 forbids | ❌ not the boundary |
| **KnowledgeCore bounded context** (§5.3) | owns KnowledgeAggregate · ConflictRecord · all eleven invariants; depends on nothing | Satisfies the constraint, but naming it "Kernel" makes "Kernel" a **synonym for an existing context** — a second name for one thing | ⚠️ correct extent, wrong name |
| **KnowledgeAggregate consistency boundary** (§6) | *"the authoritative domain boundary at which constitutional admissibility of a state transition is determined"* | Satisfies the constraint and matches the HPA's working formulation almost verbatim — but **alone it cannot preserve INV-KOS-CONTRADICTION-001**, whose §7 enforcement locus is *"EpistemicState (CONFLICTED) + **ConflictRecord**"* | ⚠️ necessary, not sufficient |
| **KnowledgeAggregate + ConflictRecord + the Verification Port as sole gate** | the two core aggregates and the single admission gate | Preserves all eleven; hosts the nine MUST-EXIST responsibilities; imports nothing | ✅ **THE BOUNDARY** |

### 3.1 FINDING K-1 — the word "Kernel" is overloaded across two altitudes

The §16 KERNEL altitude and the admission boundary are **different notions**: the first answers *what may not change without an amendment*, the second answers *where constitutional admissibility is determined*. They have **different extents** (five aggregates vs two) and neither is wrong. But one word names both, and the boundary act is precisely where that overload becomes load-bearing.

> ⚖️ **RULED — HPA, 2026-08-23 (§10): K-1 ACCEPTED.** `KERNEL` is **reserved for the existing §16 change-governance altitude**. The structure defined by this act is **the KnowledgeCore Admission Boundary**. It contains exactly two existing aggregates, which **remain separate consistency boundaries**; the admission boundary is **not** itself an aggregate and must not be described as one shared consistency boundary.

**Recommendation (as proposed — now RULED, above):** reserve **"KERNEL"** for the §16 **altitude**, and name the structure defined here **"the KnowledgeCore Admission Boundary."** The HPA's working hypothesis — *"the Kernel is the authoritative admission boundary of KnowledgeCore"* — is thereby **CONFIRMED in substance and refined in extent**: it is the admission boundary, it is *narrower* than the §16 altitude, and it needs no new architectural element. **v1.1 requires no change for this**; §16's altitude table and §6's boundary sentence are both already correct as written.

**Corroboration from the ZERO lens.** Identity-assignment's zero case (§5, row 3) independently forces reading (b) of §2.3: `UNKNOWN` is a state *of a created aggregate*, and a created aggregate requires a KnowledgeId — so `UNKNOWN` can never cover "no identity could be assigned." An identity-less candidate therefore cannot be inside the boundary in any state at all. Two independent routes reaching the same conclusion is the strongest evidence this act produced.

---

## 4 · MANDATORY INVESTIGATION 3 · Is a Domain Service required?

**Answer: the question is not this act's to decide — and that is a finding, not an evasion.**

v1.1 **§6 altitude note** (verbatim): *"Whether that boundary is realized as commands, methods, **domain services**, policy evaluation, or any other form is a Logical / Implementation Architecture decision, deliberately deferred."* — and **§20 DEF-1**: *"How the aggregate boundary is realized — commands · methods · domain services · policy evaluation, or another form."* §19's Aggregate gate closes with *"the realization stays deferred."* v1.1 even records that *"the phrase 'command surface' is deliberately absent."*

**Consequence for the deliverable: items 5 (Domain services) and 7 (Commands) of the thirteen are DEFERRED BY LAW (DEF-1).** Producing either as a list would decide DEF-1 by side effect — precisely the failure mode the handoff §8.2 warns against. They are answered as *deferred*, with the underlying **domain acts** stated at modelling altitude, which is lawful because the ten events already name them (§8).

**Two candidate service loci are nevertheless recorded, because a future DEF-1 act will need them:**

| # | Responsibility with no aggregate able to host it | Why no aggregate can | Disposition |
|---|---|---|---|
| **DS-CAND-1** | the **admission decision itself** | before admission there is *no aggregate instance* to host the decision; the decision spans an outside candidate and an inside state | ⬜ **DEF-1** — recorded, **not created** |
| **DS-CAND-2** | **cross-aggregate contradiction detection** | `ContradictionDetected` relates **two** KnowledgeAggregates and creates a ConflictRecord; ⟨C-4⟩ forbids containment, so no single aggregate owns the invariant | ⬜ **DEF-1** — recorded, **not created** |

**No domain service is created by this act.** Per the HPA's instruction, `domain services = none` was a permitted outcome; the lawful outcome is stronger — *not yet answerable*, and the two loci above are where the answer will be needed.

---

## 5 · MANDATORY INVESTIGATION 4 · The ZERO lens across every capability

Each of the nine MUST-EXIST responsibilities, tested against the seven conditions. Classification: **DS** domain state · **EV** domain event · **PO** process outcome · **OUT** outside the boundary · **REL** relationship.

| # | Capability | Zero / absence case | Lawful resolution | Class | Verdict |
|---|---|---|---|---|---|
| 1 | **Single-gate admission** | no candidate arrives | no aggregate change → **no event** (⟨A-3⟩) | OUT | ✅ |
| | | candidate fails a gate check | see **§2.3** — one cause representable, two not | DS/OUT | ❌ **DEFECT-1/2** |
| 2 | **Contract-conformance** | candidate violates an obligation | refused **at the ACL**; conformance ≠ admission (precondition 6); no domain event | OUT | ✅ (see **ATTACK 5**) |
| 3 | **Identity assignment** | identity cannot be assigned (context absent) | **must not** fall back to similarity or canonical-form equality (⟨C-1⟩). `UNKNOWN` cannot cover it — UNKNOWN is a state *of a created aggregate*, which presupposes a KnowledgeId | OUT | ❌ **DEFECT-2**; forces reading (b) |
| 4 | **Evidence admission** | no evidence offered | Article 6 requires the **justification path**, not evidence links; `EvidenceLinks` is a Collection — **empty is representable**; state `UNKNOWN` | DS | ✅ (see **ATTACK 6**) |
| 5 | **Justification preservation + sufficiency** | path **absent** | gate refusal — §2.3 | DS/OUT | ⚠️ per §2.3 |
| | | path **preserved but insufficient** | admitted; state `QUESTIONABLE` **or** `UNKNOWN` — **the law does not say which** | DS | ⚠️ **UQ-3** |
| 6 | **History recording** | first state, no prior history | History present and empty; forward-only trivially holds | DS | ✅ |
| 7 | **Representation-agnostic intake** | surface form unparseable / unknown | mechanism declares insufficiency (obligations 3 · 6) → `UNKNOWN`; **never** ABSENT/FALSE | OUT→DS | ✅ |
| 8 | **Epistemic-state determination** | the domain cannot determine a state | `UNKNOWN` — *the initial state*, §14's operational form of epistemic honesty. **This is ZERO's home in the architecture** | DS | ✅ |
| 9 | **Confidence assignment** | no confidence determinable | must **not** become scalar 0 or a low-confidence accept (⟨R-1⟩ · ⟨C-5⟩); Confidence is structured. Whether the member is *absent* or *present-as-unknown* is unstated | DS | ⚠️ **UQ-4** |

**The other conditions, swept across all nine:**

| Condition | Resolution | Class | Verdict |
|---|---|---|---|
| **unknown** | `UNKNOWN` — first-class, initial, never a degree of another state (§9 · §14) | DS | ✅ |
| **contradictory** | `CONFLICTED` + **ConflictRecord** created; `ContradictionDetected`; weaker side never deleted | DS + EV + REL | ✅ |
| **unresolved** | ConflictRecord persists in `CONFLICTED`; resolution forward-only, record survives (8.3) | DS | ✅ |
| **not applicable** | open-ended `TemporalValidity`; empty `Relations` / `EvidenceLinks` collections | DS | ✅ |
| **rejected** | R-1…R-4 — see §2.2 | mixed | ⚠️ per §2.3 |
| **absent** | `ABSENT` — *grounds that it does not exist*; strictly ≠ UNKNOWN ≠ FALSE (§9) | DS | ✅ |
| **insufficient** | mechanism-side → `UNKNOWN` (⟨C-5⟩); domain-side sufficiency → **UQ-3** | OUT/DS | ⚠️ |

**Sweep result: 7 of 9 capabilities have fully lawful zero cases. Two defects, both localized to the single pre-admission refusal path. Two minor open questions (UQ-3, UQ-4).** No zero case resolves to a silent gap, a second admission path, or a hidden aggregate.

---

## 6 · MANDATORY INVESTIGATION 5 · Aggregate-membership proofs

For each member: **what invariant becomes invalid if this element changes independently of knowledge state?** Conceptual relatedness, traceability and convenience are **not** accepted as justification.

| Member | Invariant that breaks under independent change | Strength |
|---|---|---|
| **KnowledgeId** | INV-KOS-IDENTITY-001 — identity changing independently *is* derived identity; the root ceases to be identifiable | **STRONG** |
| **Meaning** | INV-KOS-IDENTITY-001 ⟨C-2⟩ — meaning changing without admission makes a candidate into content; representation becomes meaning | **STRONG** |
| **ContextTuple** | INV-KOS-IDENTITY-001 (1.4) — context is *constitutive of* identity; independent change silently redefines what was identified | **STRONG** |
| **EvidenceLinks** | INV-KOS-VERIFICATION-001 — evidence changing without a transition breaks the justification the state rests on | **STRONG** |
| **JustificationPath** | INV-KOS-VERIFICATION-001 (6.4) — a state whose path changed independently is a black box | **STRONG** |
| **EpistemicState** | INV-KOS-DIMENSION-001 + FAILURE-001 — an independent state change is an *implicit transition*, explicitly forbidden | **STRONG** |
| **Agency** | INV-KOS-AGENCY-001 — independent change makes knowledge anonymous or misattributed | **STRONG** |
| **History** | INV-KOS-HISTORY-001 — history mutating independently is erasure | **STRONG** |
| **TemporalValidity** | INV-KOS-HISTORY-001 (11) — validity changing without a transition makes freshness into truth | **STRONG** |
| **Authority** *(Reference)* | INV-KOS-AUTHORITY-001 — the *reference* must change only by `AuthorityAssigned`. **But the referenced grant lives outside** and can change there → **UQ-5** | **MEDIUM** |
| **Relations** *(Collection)* | INV-KOS-IDENTITY-001 (1.5) — knowledge is a relationship. Yet ⟨C-4⟩ makes relations *references only*, so adding one breaks **no** aggregate invariant atomically | **WEAK** |
| **Confidence** | ⟨R-1⟩ protects the *assignment locus*, not atomicity. No invariant is invalidated by Confidence changing independently — v1.1's own OQ-5 wording said *"⟨R-1⟩ makes it safe; it does not make it necessary"* | **WEAKEST** |

**Observations — recorded, nothing reopened:**

- **MEM-1 · Confidence has the weakest atomicity proof of the twelve.** This is **not** a proposal to remove it: **OQ-5 is CLOSED (ACCEPT — the member stays)** and this act does not reopen a closed ruling. Recorded because a future DEF-1 realization act will find this member's justification rests on assignment-locus discipline rather than on transactional necessity.
- **MEM-2 · Relations is second-weakest**, for a structural reason: ⟨C-4⟩ deliberately prevents transactional coupling, which is exactly what an atomicity proof would require. Its membership rests on Article 1.5's constitutive claim, not on consistency need.
- **UQ-5 · the Authority reference is the boundary's only outward dependency for a *current* fact.** If an `AuthorityGrant` is revoked in the Authority context, does an already-admitted state's justification change retroactively? D-5 (*nothing written outside returns as knowledge*) suggests **no**; INV-KOS-AUTHORITY-001 does not say. **Recorded as an open question.**

---

## 7 · PHASE 2 · THE THIRTEEN OUTPUTS

### 1 · Kernel boundary

**The KnowledgeCore Admission Boundary** (name **RULED — ACCEPTED**, HPA 2026-08-23 · **K-1**): a single admission gate over **exactly two existing aggregates**, admitting every state transition through the **Verification Port**. Strictly narrower than the §16 KERNEL altitude. **No new element; nothing renamed in v1.1.**

```
KnowledgeCore Admission Boundary        ← ONE admission boundary
│                                          (the Verification Port is its only gate)
├── KnowledgeAggregate                  ← consistency boundary #1
│
└── ConflictRecord                      ← consistency boundary #2 (SEPARATE)
     └── coordination is CROSS-AGGREGATE → DS-CAND-2 → ⬜ DEF-1
```

**Ruled constraint (HPA · §10):** the two aggregates **remain separate aggregate consistency boundaries** and **must not be described as one shared consistency boundary.** The admission boundary is not itself an aggregate.

**Why ConflictRecord is inside — the atomicity ground, not a relatedness ground** *(post-ruling precision; the original ATTACK 1 argued only from the enforcement locus and D-5)*: **INV-KOS-CONTRADICTION-001's enforcement locus is defined as `EpistemicState(CONFLICTED)` + `ConflictRecord` jointly, and Article 8.3 requires the record to survive resolution forward-only. Atomic consistency between the CONFLICTED state and the existence of its record is therefore necessary *under the current invariant definition*.** It is **not** inside because contradiction is conceptually related to knowledge. Should that invariant definition ever change, this membership must be re-derived, not inherited.

### 2 · Inside

The eleven invariants · **KnowledgeAggregate** (root `Knowledge`, twelve members) · **ConflictRecord** (root, ConflictState, Resolution) · identity **assignment** · admitted **Meaning** · **evidence-link** admission · justification **preservation + sufficiency evaluation** · **epistemic-state determination** (all seven states) · **Confidence assignment** (⟨R-1⟩) · **History** recording, forward-only · the **port contract as published language** (D-2: *the core names what may cross*) · and the **refusals** that render INV-KOS-AUTHORITY-001 · PROJECTION-001 · DECISION-001 — *never self-authorize · never accept write-back · never execute*.

### 3 · Outside

Expression handling, parsing, tokenization, normalization, canonicalization, **SNF**, the Semantic Compiler, the **LLM** · reasoning · validation · debate · fallacy detection · candidate **production** · **declared insufficiency** (port vocabulary) · evidence **content and artifacts** (⟨C-3⟩ — the core holds links only) · the **AuthorityGrant** record · **DerivedView** · **DecisionRecord** · the **human authority act** · the **human decision** · **conformance enforcement at the ACL** (per **ATTACK 5**) · non-admitted candidate retention · storage · schemas · APIs · classes · frameworks · message formats · model selection · **the port adapters** (the *contract* is inside, the *adapter* is not).

### 4 · Aggregate(s)

**Exactly two: KnowledgeAggregate · ConflictRecord.** No third. **No candidate store, no semantic aggregate, no hidden aggregate** (F-4 · D-5 · ⟨A-3⟩). The three supporting aggregates — AuthorityGrant · DerivedView · DecisionRecord — remain in their own contexts, inside the §16 altitude, **outside this boundary**. Count unchanged: **5→5**.

### 5 · Domain services

⬜ **DEFERRED BY LAW — DEF-1** (v1.1 §6 altitude note · §20). **None created.** Two candidate loci recorded: **DS-CAND-1** admission decision · **DS-CAND-2** cross-aggregate contradiction detection (§4).

### 6 · Value objects

Unchanged, **12→12**. Value objects: `KnowledgeId` · `Meaning` · `ContextTuple` · `JustificationPath` · `Agency` · `EpistemicState` · `TemporalValidity` · `Confidence` (governed) · `History` (immutable). Collections: `EvidenceLinks` · `Relations`. Reference: `Authority`. ConflictRecord's: `ConflictState` · `Resolution`. **No value object is added, removed, split, or renamed by this act.**

### 7 · Commands

⬜ **DEFERRED BY LAW — DEF-1** (*"commands"* is named verbatim in the deferral; v1.1 records that *"the phrase 'command surface' is deliberately absent"*). At **modelling altitude only**, the domain acts are the triggers of the ten events: admit a candidate · add evidence · revise a belief · translate meaning · detect contradiction · resolve contradiction · supersede · reject · assign authority · inform a decision. **Their form is not decided here.**

### 8 · Domain events

The ten, unchanged: `KnowledgeCreated` · `EvidenceAdded` · `BeliefRevised` · `MeaningTranslated` · `ContradictionDetected` · `ContradictionResolved` · `KnowledgeSuperseded` · `KnowledgeRejected` · `AuthorityAssigned` · `DecisionInformed`. **⟨A-3⟩ holds:** no parse, normalization, canonical-form computation, database write, LLM response or benchmark run is an event. **No event added or removed (10→10).** `KnowledgeRejected`'s scope is the subject of **§2.3** and awaits an HPA ruling.

### 9 · Invariants

The eleven, unchanged (**11→11**), with their enforcement split made explicit:

| Enforcement | Invariants |
|---|---|
| **Wholly inside the boundary** (8) | IDENTITY · DIMENSION · VERIFICATION · FAILURE · CONTRADICTION · UNKNOWN · AGENCY · HISTORY |
| **Preserved inside as a refusal; record outside** (3) | AUTHORITY *(references, never holds)* · PROJECTION *(no write-back)* · DECISION *(informs, never executes)* |

This split is **why the boundary can be smaller than the §16 altitude** without weakening any invariant: the core keeps the *refusal*; the supporting context keeps the *record*.

### 10 · Required ports

| Port | Direction | Role | Contract status |
|---|---|---|---|
| **Expression↔Meaning Port (ACL)** | inbound | translates expression → **meaning candidate**; conformance gate; never determines identity | ⬜ PROPOSED · NON-AUTHORITATIVE |
| **Verification Port** | inbound | **THE SINGLE ADMISSION GATE**; candidate + preserved justification path → admitted state | ⬜ PROPOSED · NON-AUTHORITATIVE |
| **Authority reference** | inbound | records the assigned human act; the core references, never holds | law (Article 3) |
| **Projection Port** | outbound | read-only, regenerable, **no write-back** | law (Article 5) |
| **Decision Port** | outbound | advisory only; execution stays outside | law (Article 4) |

**Precondition 9 discharged:** the two inbound contracts are **PROPOSED · NON-AUTHORITATIVE**; the F-1…F-5 amendments are **not applied**; ratification is a separate HPA act. **UQ-6:** whether Expression↔Meaning is a distinct port or an upstream *stage* of Verification is not settled by the law — Port Contract §1's pipeline shows them in sequence, and §7.1's "single gate" language is satisfied either way.

### 11 · Explicit anti-capabilities

The boundary **must not** contain: a **reasoner** or any conclusion-generating element (§16 — *"the kernel does not reason"*) · a **second admission path** (INV-KOS-VERIFICATION-001) · a **candidate store / hidden aggregate** (F-4) · **mechanism-authored identity** (obligation 4 · ⟨C-1⟩) · a **mechanism scalar becoming Confidence** (⟨R-1⟩) · a **mechanism-produced EpistemicState** (§6 — *"no mechanism may ever produce this member"*) · **natural-language interpretation** (F-3 · Test F) · **evidence content** (⟨C-3⟩) · **held authority** (Article 3) · **execution** (Article 4) · **write-back** (Article 5) · a **"Zero component"** (§14 — Zero is a meta-principle, not a domain object) · an **eighth epistemic state** (§9) · **`WisdomDerived`** or a Wisdom context (V.3) · and the eleven **§17 refusals** (language engine · database · chatbot · LLM wrapper · ontology repository · truth machine/oracle · Wisdom-as-core · similarity-or-canonicalization-as-identity · probability-as-verdict · canonicalization-as-authority · low-entropy-as-certainty).

### 12 · Unresolved questions

| # | Question | Owner |
|---|---|---|
| **ZERO-DEFECT-1** | An **agency-absent** gate refusal is unrepresentable: INV-KOS-AGENCY-001 binds *every state*, and the refusal's cause is the missing member. Reading (a) rejector-agency vs (b) pre-domain refusal | **HPA** — amendment-class if (a) |
| **ZERO-DEFECT-2** | A **context-absent** gate refusal cannot bear a well-formed KnowledgeId (Article 1.4). Same two readings | **HPA** — amendment-class if (a) |
| **UQ-3** | A **preserved-but-insufficient** justification path → `QUESTIONABLE` or `UNKNOWN`? The law does not say | Logical Architecture |
| **UQ-4** | Is an undeterminable `Confidence` **absent** or **present-as-unknown**? ⟨R-1⟩ forbids the scalar fallback but does not settle the representation | Logical Architecture |
| **UQ-5** | Does revoking an external `AuthorityGrant` retroactively affect an already-admitted state? D-5 suggests no; INV-KOS-AUTHORITY-001 is silent | **HPA** / Logical Architecture |
| **UQ-6** | Is Expression↔Meaning a distinct port or an upstream stage of the Verification Port? | Logical Architecture |
| **K-1** | Should "KERNEL" be reserved for the §16 altitude, with this structure named *the KnowledgeCore admission boundary*? | **HPA** |
| **§9 diagram** | Under reading (b), v1.1 §9 routes all three gate causes to `KnowledgeRejected` and would be imprecise — a frozen-artifact correction | **HPA** |

**Carried, not resolved (each keeps its owner):** F-1…F-5 amendment candidates · AH-4 governance recording · AH-5 (deferred; **must not be resolved by inference**) · DEF-1 · DEF-4 · DEF-5 · OQ-1 · OQ-4 **UNAUTHORIZED** · Port Contract ratification.

### 13 · Implementation preconditions

1. **HPA ruling on this boundary** — it is PROPOSED · NON-AUTHORITATIVE until then.
2. **ZERO-DEFECT-1/2 resolved** before *any* admission path is realized: an implementation would otherwise silently pick reading (a) or (b) in code, deciding an amendment-class question by side effect.
3. **K-1 terminology ruled**, so realization does not create a fourth structure named "Kernel."
4. **Port Contract ratification** (precondition 9) — the two inbound contracts are non-authoritative; the F-1…F-5 amendments are unapplied.
5. **DEF-1 decided** — commands · methods · domain services · policy evaluation. Items 5 and 7 above cannot be filled before this.
6. **UQ-3 · UQ-4 · UQ-6** settled at Logical Architecture altitude.
7. **Then** Kernel capability mapping, **then** the implementation decision — separate acts.

**Explicitly NOT authorized by this act:** code · schema · API · class · framework · technology selection · parser · FST · SNF work · research · corpus · capability implementation · Kernel implementation · migration · adoption.

---

## 8 · ADVERSARIAL CRITIC PASS — separate pass, falsification-oriented

**Pass discipline (R-34 · handoff §7.6):** this pass attempts to **refute** §7, not to check it for completeness. It is authored as a distinct pass and its findings are recorded even where they weaken the producer output. Two attacks **landed and changed §7**.

| # | Attack | Test against law | Outcome |
|---|---|---|---|
| **1** | *The boundary is not minimal — ConflictRecord could sit outside.* | INV-KOS-CONTRADICTION-001's §7 locus is *"EpistemicState (CONFLICTED) + ConflictRecord"*; 8.3 requires the record to survive resolution forward-only. Outside, D-5 (*nothing written outside returns as knowledge*) would forbid reading it back into a state determination | **SURVIVES** — ⚠️ **but the pass was incomplete**: it argued from the enforcement locus, **not** from atomicity. The **HPA supplied the missing challenge** (2026-08-23): *is ConflictRecord in the same consistency boundary, or a separate aggregate coordinated by a service/process?* The atomicity ground is now stated in §7 item 1; the answer is **separate aggregate, cross-aggregate coordination, DEF-1**. **Self-assessed gap:** the §6 atomicity test was applied to the twelve members but **not** to ConflictRecord's boundary inclusion |
| **2** | *The boundary depends on an unratified artifact — so it is not authoritative.* | The two inbound port contracts are **PROPOSED · NON-AUTHORITATIVE** (precondition 9). D-2 makes the *contract* core-owned published language, so its ownership is sound — but its **content** is not yet ratified | ⚠️ **LANDS PARTIALLY** — recorded as a dependency risk; no §7 claim rests on unratified *content*, and the six obligations are cited only where they render an invariant already in v1.1 |
| **3** | *"Kernel = the admission boundary" contradicts §16, where KERNEL spans five aggregates.* | §16 is change-governance; the boundary is consistency/admissibility. Different notions, different extents, both lawful — **but one word names both** | ⚠️ **LANDS** → became **FINDING K-1** and **UQ-K-1**; drove the recommended rename |
| **4** | *Putting REJECTED inside makes the aggregate a candidate store under another name.* | F-4 forbids storing **candidates**; what is retained is the domain's **own refusal act**, not the candidate payload — and under reading (b) only for justification-absent cases | ⚠️ **LANDS PARTIALLY** → strengthens the recommendation for reading (b); recorded, not decided |
| **5** | *"Contract-conformance enforcement" is an ACL responsibility; placing it inside imports mechanism responsibility.* | Precondition 6 is explicit: **conformance ≠ admission**, a two-gate structure. The ACL *enforces* conformance; the domain *decides* admission | ✅ **LANDS — producer output corrected.** §7 item 2 now keeps only the conformance **specification** inside (D-2); item 3 places conformance **enforcement** outside. F-1's capability name reads as a single domain responsibility and is better read as two |
| **6** | *"Zero evidence + preserved path = admissible" collapses the core to a claim store (Ch IV refusal).* | §4: *"remove justification and it is a claim store."* The path is present, so the refusal does not trigger; Article 6 requires the **path**, and `EvidenceLinks` is a Collection for which empty is well-formed | **SURVIVES** — recorded as an intentional, narrow allowance |
| **7** | *The boundary hosts "sufficiency evaluation" — that is reasoning, and the kernel does not reason.* | §16: a kernel member *"can refuse a transition, record a state, assign an identity, retain a history — it **cannot generate a conclusion**."* Evaluating a **supplied** path's sufficiency is a refusal judgment, not conclusion generation; F-5 assigns it to the domain | **SURVIVES — but this is the thinnest line in the boundary.** Recorded as the primary anti-drift watchpoint: any realization that *derives* a conclusion while "evaluating sufficiency" has imported a reasoner |
| **8** | *Nine MUST-EXIST responsibilities inside, yet three invariants enforced partly outside — the boundary leaks.* | The split is *refusal inside · record outside* (§7 item 9). The core never holds authority, never accepts write-back, never executes — each is a **refusal**, which requires no outside element | **SURVIVES** |
| **9** | *`UNKNOWN` as initial state means everything is admitted, so the gate admits nothing meaningfully.* | The gate's three checks (§9) are the filter; UNKNOWN is the *post-admission* starting state. Admission ≠ validation — a real and deliberate separation | **SURVIVES** — and it is the reason R-3 (mechanism insufficiency) is not rejection |

**Critic verdict:** the boundary **survives falsification** with one substantive correction (**ATTACK 5**, applied to items 2 and 3), one terminology finding (**ATTACK 3** → K-1), one dependency risk (**ATTACK 2**), one strengthened recommendation (**ATTACK 4**), and one named anti-drift watchpoint (**ATTACK 7**). **No attack forced a new element, a new aggregate, or a change to any invariant.**

**Producer/critic separation — stated honestly:** both passes were performed by the same actor in explicitly separate, labelled passes, which handoff §7.6 permits *"If you perform both, keep them in separate passes and label the passes explicitly."* A genuinely independent critic remains the stronger discipline, and **this act does not accept its own output** (R-34).

---

## 9 · Completion report

```yaml
act: KERNEL-BOUNDARY-DEFINITION
status: COMPLETED
research_required: NO
research_track_opened: NO
implementation_performed: NO
kernel_designed: NO
kernel_implemented: NO
domain_model_changed: NO
aggregate_changed: NO
invariants_changed: NO
vocabulary_changed: NO
constitution_changed: NO
v1_1_changed: NO
boundary:
  kernel_boundary: "The KnowledgeCore admission boundary — KnowledgeAggregate + ConflictRecord, admitting every transition through the Verification Port as sole gate. Strictly narrower than the §16 KERNEL altitude (which groups 5 aggregates for change-governance). Recommended rename: reserve KERNEL for the altitude (FINDING K-1)."
  inside: "11 invariants · KnowledgeAggregate (12 members) · ConflictRecord · identity assignment · admitted Meaning · evidence-link admission · justification preservation + sufficiency evaluation · epistemic-state determination · Confidence assignment (R-1) · History · the port CONTRACT as published language · the AUTHORITY/PROJECTION/DECISION refusals"
  outside: "expression/parsing/normalization/SNF/Semantic Compiler/LLM · reasoning & validation · candidate production · declared insufficiency · evidence content · AuthorityGrant · DerivedView · DecisionRecord · human authority act · human decision · CONFORMANCE ENFORCEMENT at the ACL (critic ATTACK 5) · non-admitted candidate retention · storage/schemas/APIs · port ADAPTERS"
  aggregates: "exactly two — KnowledgeAggregate · ConflictRecord. No candidate store, no semantic aggregate, no hidden aggregate. 5→5 overall."
  domain_services: "DEFERRED BY LAW (DEF-1). None created. Two candidate loci recorded: DS-CAND-1 admission decision · DS-CAND-2 cross-aggregate contradiction detection."
  value_objects: "unchanged 12→12 — KnowledgeId · Meaning · ContextTuple · JustificationPath · Agency · EpistemicState · TemporalValidity · Confidence · History · EvidenceLinks · Relations · Authority(ref) + ConflictState · Resolution. None added/removed/renamed."
  commands: "DEFERRED BY LAW (DEF-1 names 'commands' verbatim). Domain acts stated at modelling altitude only (the ten events' triggers)."
  domain_events: "the ten, unchanged (10→10); A-3 guard holds. KnowledgeRejected's scope awaits an HPA ruling (§2.3)."
  invariants: "the eleven, unchanged (11→11). 8 enforced wholly inside; 3 (AUTHORITY/PROJECTION/DECISION) preserved inside as refusals with records outside — this is why the boundary can be smaller than the altitude without weakening any invariant."
  required_ports: "5 — Expression↔Meaning ACL (inbound) · Verification Port (inbound, SOLE admission gate) · Authority reference (inbound) · Projection (outbound, read-only) · Decision (outbound, advisory). The two inbound contracts are PROPOSED · NON-AUTHORITATIVE."
  anti_capabilities: "no reasoner · no second admission path · no candidate store/hidden aggregate · no mechanism-authored identity · no mechanism scalar as Confidence · no mechanism-produced EpistemicState · no natural-language interpretation · no evidence content · no held authority · no execution · no write-back · no Zero component · no eighth state · no WisdomDerived · the eleven §17 refusals"
  unresolved_questions: "ZERO-DEFECT-1 (agency-absent refusal unrepresentable) · ZERO-DEFECT-2 (context-absent refusal unrepresentable) · UQ-3 (insufficient vs absent path → which state) · UQ-4 (absent Confidence representation) · UQ-5 (revoked AuthorityGrant retroactivity) · UQ-6 (Expression↔Meaning: port or stage) · K-1 (terminology) · §9 diagram imprecision under reading (b)"
  implementation_preconditions: "HPA ruling on this boundary · ZERO-DEFECT-1/2 resolved BEFORE any admission realization · K-1 ruled · Port Contract ratified · DEF-1 decided · UQ-3/4/6 settled — then capability mapping, then the implementation decision"
consolidation:
  input_set_assembled: YES
  open_items_recorded: "F-1…F-5 amendments ⬜ OPEN · AH-4 governance recording ⬜ outstanding · AH-5 ⬜ DEFERRED (not to be resolved by inference) · DEF-1/4/5 ⬜ deferred · OQ-1 ⬜ open · OQ-4 UNAUTHORIZED · Port Contract ratification ⬜ pending — all recorded, none decided; each keeps its owner"
zero_lens_applied:
  producer_pass: YES
  adversarial_pass: YES
  result: "7 of 9 capabilities fully lawful; 2 defects localized to the pre-admission refusal path; 2 minor open questions (UQ-3, UQ-4). No zero case resolves to a silent gap, a second admission path, or a hidden aggregate."
next_actor: HUMAN PRINCIPAL ARCHITECT
next_act: SEPARATE GOVERNED ACT
```

**The YAML above is the completion report AS DELIVERED (pre-ruling): PROPOSED · NON-AUTHORITATIVE · HPA DECISION REQUIRED.** It is retained unrewritten as the record of what was proposed (ES-004.3). For the disposition of every item in it, see **§10 · HPA RULING** — where the boundary is **ACCEPTED subject to rulings**, `KERNEL` is reserved for the §16 altitude, the two aggregates are confirmed as **separate consistency boundaries**, ZERO-DEFECT-1/2 take **reading (b)**, UQ-3/UQ-4 and DEF-1 stay deferred, and the **anti-reasoner constraint** is carried forward as mandatory. **Nothing is implemented or authorized for implementation. The Kernel still waits.**

---

---

## 10 · HPA RULING (2026-08-23) — recorded verbatim-in-substance

> **The Kernel Boundary Definition is accepted as the architectural decision-support basis, subject to the following rulings.**

| # | Item | Ruling | Consequence |
|---|---|---|---|
| **1** | **ZERO-DEFECT-1** — agency absent | ✅ **ACCEPT the pre-domain reading (B)** | Agency-absent candidates **do not enter KnowledgeCore**: no `KnowledgeAggregate`, no `KnowledgeId`, **no domain event**, and **not** represented as a Knowledge `REJECTED` state. **Rejector-agency and provisional identity are refused** — they would alter the meaning of existing invariants |
| **2** | **ZERO-DEFECT-2** — context absent | ✅ **ACCEPT the pre-domain reading (B)** | Without context a valid `KnowledgeId` cannot be assigned, because **context is constitutive of identity**. The candidate cannot enter the aggregate as `UNKNOWN`, `REJECTED`, or any other epistemic state. **No provisional identity is introduced** |
| **3** | **K-1** — terminology | ✅ **ACCEPT** | `KERNEL` is **reserved for the existing §16 change-governance altitude**. This structure is **the KnowledgeCore Admission Boundary**, containing exactly two existing aggregates — `KnowledgeAggregate` and `ConflictRecord` — which **remain separate aggregate consistency boundaries**, coordinated **cross-aggregate**, subject to **DEF-1**. **Do not describe them as one shared aggregate consistency boundary** |
| **4** | **UQ-3** — preserved-but-insufficient path → `QUESTIONABLE` or `UNKNOWN`? | ⬜ **DEFER** | Logical Architecture. **Not** a reason to reopen domain discovery |
| **5** | **UQ-4** — indeterminate Confidence: absent or explicitly unknown? | ⬜ **DEFER** | Logical Architecture |
| **6** | **DEF-1** — commands · methods · domain services · policy evaluation | ⬜ **REMAINS DEFERRED** | **DS-CAND-1** (admission decision) and **DS-CAND-2** (cross-aggregate contradiction coordination) remain **candidate loci, not services** |

### 10.1 The governing principle the ruling establishes

> **Absence of a constitutive prerequisite is not an epistemic state.**

This is the ZERO lens's most consequential result. `UNKNOWN`, `ABSENT`, `FALSE` and `REJECTED` are states *of an identified epistemic object*; where the prerequisites of identity itself (**agency**, **context**) are missing, there is no object to bear a state, and the lawful outcome is **refusal before the domain**, not a negative state inside it.

### 10.2 The anti-reasoner constraint — carried forward as MANDATORY

> **The KnowledgeCore may evaluate whether a supplied justification path satisfies predefined domain admissibility requirements; it may not generate, infer, discover, or derive the semantic conclusion that the justification purports to support.**

**Status: a mandatory implementation / capability **fitness constraint** for the next act (Kernel Capability Mapping).** It is the hardest future Kernel constraint, and it is where **ATTACK 7**'s watchpoint acquires teeth — the boundary can only *name* the prohibition; capability mapping is where a responsibility could quietly acquire the power to derive a conclusion.

```
EXTERNAL WORLD
      │
      ▼
Expression · Semantic Compiler · Reasoning & Validation
      │  produces
      ▼
Candidate + JustificationPath
      │
      ▼
┌──────────────────────────────────────────┐
│  KnowledgeCore Admission Boundary         │
│                                           │
│  verify admissibility · assign identity   │
│  admit meaning · determine epistemic      │
│  state · preserve justification ·         │
│  assign confidence · record history       │
│                                           │
│  ⛔ NEVER GENERATE A CONCLUSION            │
└──────────────────────────────────────────┘
```

### 10.3 The REJECTED model, as ruled — four situations, cleanly separated

| Situation | Where | Domain state? |
|---|---|---|
| Candidate violates the representation / port contract | **ACL** | **No** |
| **Agency or context prerequisite missing** | **Pre-domain refusal** | **No** |
| Justification path absent, domain refusal representable | KnowledgeCore rejection path | **`REJECTED`** |
| Already-admitted knowledge later fails justification | KnowledgeCore lifecycle (`BeliefRevised`) | **`REJECTED`** |

**And critically: mechanism inability is not rejection.** `declared insufficiency → UNKNOWN` remains intact (⟨C-5⟩ · port obligations 3 · 6), preserving the distinction P5 exposed between *"the system cannot determine"* and *"the domain has determined that the admitted epistemic object is rejected."*

### 10.4 Corrections applied to this artifact as a consequence of the ruling

Per the HPA's direction — *"apply the terminology/precision correction to the boundary artifact only as a consequence of this ruling"* — four surgical corrections were made and each is marked in place:

1. **§0** — the boundary sentence no longer says *"the KnowledgeAggregate consistency boundary together with ConflictRecord"* (a conflation: an aggregate **is** a consistency boundary, so two cannot share one). It now states **one admission gate over two separate consistency boundaries**.
2. **§7 item 1** — restated with the ruled name, the two-aggregate diagram, and the **atomicity ground** for ConflictRecord's inclusion: *atomic consistency between `EpistemicState(CONFLICTED)` and the existence of its record is necessary **under the current invariant definition**, not because contradiction is conceptually related to knowledge.* Should that definition change, the membership must be **re-derived, not inherited**.
3. **§2.3 · §3.1** — ruling annotations recording readings (b) and K-1 as ACCEPTED.
4. **§8 ATTACK 1** — annotated with a **self-assessed gap**: the atomicity test of §6 was applied to the twelve members but **not** to ConflictRecord's boundary inclusion; the HPA supplied that missing challenge.

**Nothing else was rewritten.** The analysis, the thirteen outputs, the ZERO sweep, the membership proofs and the critic pass stand as delivered (ES-004.3 — decision text and history are never rewritten; status annotations are the mechanism).

### 10.5 Scope of the ruling — what it does NOT authorize

**Do not reopen** research · SNF · OQ-4 · the domain model. **Do not begin** capability mapping or implementation until the **next separately governed act is commissioned**. The ruling accepts a **boundary**; it authorizes **no build**.

```
Research                      CLOSED
Domain discovery              CLOSED
F-1…F-5                       CLOSED
Kernel boundary definition    COMPLETED
ZERO defects                  RULED
Kernel terminology            RULED
Boundary                      ACCEPTED
Capability mapping            NEXT — requires a separate commission
Implementation                NOT YET
Kernel implementation         NOT YET
```

**We have defined what the Kernel is allowed to protect. We have not built the Kernel.**


---

## 11 · v1.1 §9 CONFORMANCE CORRECTION (r5) — applied and verified

**Commission:** HPA, 2026-08-23 — *"make the next immediate act: correct v1.1 §9 under explicit HPA authorization. This is **not research** and **not architecture redesign**. It is a **law-conformance correction caused by an accepted architectural ruling**."* The §10 ruling made the previous single-stage rendering of the §9 gate imprecise; without correction a capability mapper reading the frozen diagram would reproduce *three gate causes → `KnowledgeRejected`*.

**Mechanism used:** v1.1's **own** established revision apparatus — a lettered revision with a change ledger, each entry traced to a named authority act, plus a structural inventory (the pattern of r3 and r4). **No new mechanism was invented.** This is **r5**.

### 11.1 The corrected gate (v1.1 §9)

```
              (a candidate arrives at the Verification Port)
                                │
             ┌──────────────────┴───────────────────┐
             │ ⟨Z-1⟩ CONSTITUTIVE PREREQUISITES      │
             │ agency present?   context present?    │
             └──────────────────┬───────────────────┘
                     absent │           │ present
                            ▼           │
        ┌────────────────────────────┐  │
        │  PRE-DOMAIN REFUSAL         │  │
        │  no aggregate               │  │
        │  no KnowledgeId             │  │
        │  no epistemic state         │  │
        │  NO DOMAIN EVENT            │  │
        │  (retained mechanism-side)  │  │
        └────────────────────────────┘  │
                                        ▼
                     ┌──────────────────────────────────┐
                     │  justification path preserved?    │
                     └──────────────┬───────────────────┘
                             no │        │ yes
                                ▼        ▼
                   KnowledgeRejected   KnowledgeCreated ── initial state: UNKNOWN
```

### 11.2 The r5 change set — five entries, all renderings

| Ref | Class | Change | Site |
|---|---|---|---|
| **r5-1** | CORRECTION (rendering) | the gate drawn as **two stages** — constitutive prerequisites, then justification path | §9 diagram |
| **r5-2** | ADDITION (interpretation) | **⟨Z-1⟩** *absence of a constitutive prerequisite is not an epistemic state*; rejection-preservation scoped to candidates the domain admitted into its own record | §9 bullets |
| **r5-3** | CORRECTION (rendering) | `KnowledgeRejected`'s **trigger** narrowed to justification-path failure; a pre-domain refusal raises **no event**. The event itself unchanged | §8 event table |
| **r5-4** | CORRECTION (rendering) | INV-KOS-AGENCY-001's **enforcement locus** restated — refused before the domain, **not** recorded as `REJECTED` (which would violate the invariant it enforces). **The statement column is untouched** | §7 |
| **r5-5** | CORRECTION (wording) | the `Agency` member reads **"absence refuses creation"**, not *"rejects"* — no domain object comes into being | §6 |

**The r5-4 distinction is the one that made the correction possible without amending law:** v1.1's invariant table separates the **statement** column (the invariant) from the **enforcement locus** column (its rendering). Only the rendering changed.

### 11.3 Verification against the six commissioned criteria

| # | Criterion | Method | Result |
|---|---|---|---|
| **1** | changes only the affected lifecycle representation | every diff hunk mapped to its owning section | ✅ **PASS** — §9 (the lifecycle), its three renderings (§6 · §7 · §8), plus §1 status row and Appendix A ledger (the traceability apparatus). **No other section touched** |
| **2** | does not change the eleven invariants | column-2 (statement) of all eleven `INV-KOS-*` rows extracted at `HEAD` and at working tree, compared | ✅ **PASS** — **byte-identical**. Only INV-KOS-AGENCY-001's *enforcement-locus* column changed |
| **3** | does not introduce a new state | the seven-state vocabulary parsed and counted both sides | ✅ **PASS** — `VALIDATED · QUESTIONABLE · REJECTED · CONFLICTED · UNKNOWN · ABSENT · FALSE` — **7 → 7**, identical |
| **4** | does not change aggregate membership | member-table rows counted both sides; aggregate count string checked | ✅ **PASS** — members **12 → 12**; aggregates **5 → 5** |
| **5** | does not introduce a second admission path | *"the only admission path"* occurrences and all `Verification Port` references counted both sides | ✅ **PASS** — 3 → 3 and 12 → 12; admission paths **1 → 1**. The correction **subtracts** from what enters the gate; it adds no gate |
| **6** | preserves historical traceability | the r5 ledger records the prior rendering verbatim (*"Previously all three checks routed to `KnowledgeRejected`"*); the ruling record and both prior commits stand | ✅ **PASS** — nothing rewritten (ES-004.3) |

**Also verified:** domain events **10 → 10** · bounded contexts **6 → 6** · register **25+4** · constitutional articles **11 → 11**. **Net structural change: none.**

### 11.4 What this correction is, and is not

**It is:** a conformance correction of a **rendering** of law, caused by an accepted ruling, executed through v1.1's own revision mechanism, with the prior rendering preserved.

**It is not:** research · architecture redesign · a v1.2 · a new invariant · a new state · a new event · a new member · a new aggregate · a second admission path · an amendment to the Constitution. **⟨Z-1⟩ is an interpretation of existing invariants** (INV-KOS-AGENCY-001 · INV-KOS-IDENTITY-001 Article 1.4 · INV-KOS-UNKNOWN-001 · ⟨A-3⟩), in the same class as ⟨C-1⟩…⟨C-5⟩, ⟨R-1⟩ and the ⟨r4⟩ annotations — **not new law**.

**Next act: Kernel Capability Mapping — requires a separate commission.** The anti-reasoner constraint (§10.2) enters it as a **fitness constraint with a test**, not as prose.


## Traceability

- **Commission:** HPA, 2026-08-23 — *"Proceed with the commissioned Kernel Boundary Definition"*, with five mandatory investigations (REJECTED semantics · what "Kernel" denotes · whether a Domain Service is required · the ZERO lens per capability · aggregate-membership proofs), a separate adversarial critic pass, and the instruction to treat readiness findings A–E as architectural questions rather than grounds to reopen research.
- **Approved scope:** `docs/plans/20260823-1241-kernel-boundary-definition-handoff-plan.md` §7 (one question · thirteen outputs · exclusions verbatim · two-phase shape · §7.6 completion discipline · §7.7 ZERO lens).
- **Inputs:** v1.1 (`20260822-1402`) · Port Contract r4 (`20260822-1559`) · LA Review-01 (`20260822-1611`) · F-1…F-5 (`20260823-1051`) · OQ-2 (`20260823-0931`) · OQ-3 (`20260823-0953`) · OQ-5 (`20260823-1014`) · AH-1…AH-5 decision record (`20260822-2341`) + **confirmation** (`20260822-2346`, gate CLOSED) · P5 acceptance (`20260822-2327`).
- **Readiness findings carried in as investigations:** A → §2 (REJECTED) · B → §3 (Kernel referent) · C → CONS-1 (AH status, corrected in the input set, historical artifact untouched) · D → CONS-2 (precondition 9 discharged) · E → §4 (domain services / DEF-1).
- **Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos --maturity=research` → `docs/knowledgeos` (exit 0); `reviews/` per the chain's convention for decision-support records.
- **Status:** 📋 **DELIVERED · PROPOSED · NON-AUTHORITATIVE · decision ⬜ OPEN (HPA).** No v1.1 edit · no contract edit · no code · no v1.2 · register **25+4 unchanged** · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · invariants **11→11** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **UNAUTHORIZED** · no capability mapping · no implementation decision · **the Kernel still waits.**
