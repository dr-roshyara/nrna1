# KnowledgeOS — INDEPENDENT DDD CRITIQUE of the Kernel Capability Mapping (2026-08-23)

> **Role:** the commissioned **independent adversarial critique**. Its purpose is to **falsify**, not to improve. No repair is proposed, no architecture is changed, no HPA question is resolved.
> **Commission:** HPA, 2026-08-23 — *"You are now the INDEPENDENT CRITIC. Do not improve the Kernel Capability Mapping. Do not redesign it. Do not implement anything. Your job is to TRY TO FALSIFY it."* Twenty falsification targets · the lens corpus · the primary rule *DO NOT IMPROVE THE MAPPING — TRY TO BREAK IT*.
> **Target under attack:** `docs/knowledgeos/reviews/20260823-2103-KOS-EP01-Kernel-Capability-Mapping.md` (PROPOSED · NON-AUTHORITATIVE).
> **Brainstorming corpus:** read in full, chronological order, 21 artifacts (~24,000 lines) — **NON-AUTHORITATIVE**, used only as a falsification instrument. Renamed to the house convention in the same act (see §23).
> **Status:** 📋 **DELIVERED · PROPOSED · NON-AUTHORITATIVE.** No code · no tests created in the repository · no research opened · no architecture changed · no terminology renamed · register **25+4** · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · states **7→7** · invariants **11→11** · Constitution **FROZEN** · OQ-4 **UNAUTHORIZED** · AH-5 **untouched**.
> **Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos --maturity=research` → `docs/knowledgeos` (exit 0).

---

## 1 · Executive verdict

> **The Kernel Capability Mapping SURVIVES falsification on every question of PLACEMENT, and FAILS on questions of COMPLETENESS.**

Not one attack moved a responsibility across the boundary. The PROTECT/PRODUCE discriminator held against every counterexample the corpus could supply — including five independent, competently-argued Kernel designs that each drifted into prohibited territory. The boundary extent, the two-aggregate structure, the anti-reasoner constraint and the ⟨Z-1⟩ ruling all survived.

What did not survive is the claim that the mapping's answers are **complete**. The critique found **one outright falsification**, **five partial falsifications**, and **eleven recorded defects or gaps** — concentrated in Q1 (what enters), Q6 (domain acts), Q9 (the anti-capability register) and Q10 (the test suite).

| | Count |
|---|---|
| **FALSIFIED** | 1 — C-4 (the revision/targeting path is unmapped) |
| **PARTIALLY FALSIFIED** | 5 — C-1 taxonomy · C-2 determinism · C-9 register coverage · C-13 methodology · C-16 program derivation |
| **SURVIVES** | 9 major claims (§2, §5, §6, §11, §12) |
| **HPA DECISION REQUIRED** | 7 |
| **RESEARCH REQUIRED — NOT AUTHORIZED** | 2 |
| **DEFERRED BY EXISTING LAW** | 3 |

**The single most important finding** is not a defect in the mapping but a defect in what the mapping *inherited*: **the "nine MUST-EXIST capabilities" is a category mixture** (§3). It contains genuine capabilities, one invariant restated as a capability, and one anti-capability wearing a positive name. The HPA anticipated exactly this; the critique confirms it against law.

**The most dangerous finding** is **C-11 (§7): `Confidence` has no lawful input.** The boundary may not read evidence content (⟨C-3⟩), may not accept a mechanism scalar (⟨R-1⟩), and may not reason (§16). Every input from which confidence could be computed is forbidden. This is the third independent route to the same weak member, and it is the place a hidden reasoner is most likely to enter.

---

## 2 · Boundary falsification results (targets 1, 2)

### 2.1 Target 1 — BOUNDARY TOO LARGE

**Attack (strongest available).** The corpus supplies five independent Kernel designs. Each was tested against the mapping's placements to see whether the mapping had *retained* anything those designs correctly externalised.

| Corpus design | Placed inside the Kernel | Mapping's placement | Verdict |
|---|---|---|---|
| `20260823-104148` constitutional knowledge engine | proposal parsing · intent candidates · evidence scoring · contradiction monitor · transition execution | all **outside** | mapping smaller |
| `20260823-104251` constitutional DSL | DSL · regex intent parser · numeric evidence weights · resolution thresholds | all **outside** | mapping smaller |
| `20260823-103606` eight capacities K1–K8 | identity *sameness* discrimination · contradiction states · determinism | sameness **excluded** (⟨C-1⟩) | mapping smaller |
| `20260823-110248` DDD critical review | admissibility only; rules *supplied from outside* | rules **inside** (§5.3 owns all eleven) | mapping *larger*, and correct |
| `20260823-112855` epistemic accountability core | discrimination + commitment | consistent | equivalent |

**Result: SURVIVES.** In four of five comparisons the mapping is *smaller* than the corpus proposal. In the fifth the mapping is larger — and lawfully so: §5.3 assigns KnowledgeCore **all eleven invariants**, so a design that receives its constitution from an external Governance Context contradicts law. The corpus proposal loses.

**One residual, recorded:** the corpus's own warning — *"Kernel interprets Constitution → Kernel decides what Knowledge means"* (`20260823-110950`) — identifies a distinction the mapping never states: **applying** an invariant versus **interpreting** it. The mapping places invariants inside without saying which of the two it means. See C-3 (§4.3).

### 2.2 Target 2 — BOUNDARY TOO SMALL

Four attacks. Two land.

**C-2 · Determinism is unmapped, yet the mapping's decisive test presupposes it. — PARTIALLY FALSIFIED.**
The corpus makes determinism a first-class Kernel capacity in three independent places (`20260823-103606` K8; `20260823-104148` *"same input → same admissibility decision"*; `20260823-205735` deterministic-assurance lens). The mapping lists determinism **nowhere** — not in the nine capabilities, not in the twenty anti-capabilities, not in the twenty tests. Yet **T-2 SEMANTIC-INVARIANCE cannot be executed without it**: "identical structure, different meaning → identical disposition" is only meaningful if identical input yields identical disposition in the first place. The mapping's flagship fitness constraint rests on an unstated property.
*Is determinism law?* Not among the eleven. §9's *"no implicit transition"* and INV-KOS-HISTORY-001 imply reproducibility but do not state it. **HPA DECISION REQUIRED** — is determinism a boundary property, a fitness constraint, or DEF-1 realization?

**C-3 · The authority-adequacy check is unhomed. — HPA DECISION REQUIRED.**
INV-KOS-AUTHORITY-001 is listed as *"preserved inside as a refusal"*. The mapping names one failure mode: never self-authorize. It does not name the other: **authority that exists but does not cover the act**. Nothing in the nine capabilities checks that the referenced authority is *adequate in scope* for the transition requested. The corpus solves this with a **Governance Context** performing eligibility/policy evaluation (`20260823-110248`, `20260823-110950`) — but **v1.1 §5.3 has no Governance context**; the six are KnowledgeCore · Authority · Projection · Decision Boundary · Reasoning & Validation · Expression, and Authority holds only the *grant record*. So the responsibility is **unhomed**: either the boundary performs it (and the mapping omits a tenth capability) or a context must own it (and none exists). Adding one would be contexts **6→7**.

**Attacks that did not land:** *conformance enforcement should be inside* — refuted, precondition 6 makes conformance ≠ admission a two-gate structure. *Execution should be inside* — refuted, v1.1's canonical wording puts *admissibility of a state transition* at the aggregate, and the corpus's own §5 (`20260823-105200`) warns that splitting adjudication from application creates a state-drift window, which supports keeping them together.

---

## 3 · Capability taxonomy falsification (target 4) — **PARTIALLY FALSIFIED**

The HPA's challenge, tested item by item against law. The test used: *is this an act the domain performs, or a constraint on what it may do?*

| # | Item as named in the nine | Actual DDD construct | Governing law | Verdict |
|---|---|---|---|---|
| 1 | **Single admission gate** | **INVARIANT**, rendered as a boundary property — not an act | INV-KOS-VERIFICATION-001 (*"the only admission path"*) | ❌ **not a capability** |
| 2 | **Contract conformance** | **SPLIT**: published-language **specification** (inside, D-2) + **ACL responsibility** (outside) | §5.2 · precondition 6 | ⚠️ two constructs named as one |
| 3 | **Identity assignment** | **CAPABILITY** — a domain act (assignment) | INV-KOS-IDENTITY-001 | ✅ capability |
| 4 | **Evidence admission** | **CAPABILITY** — but hollow, see C-10 (§7) | ⟨C-3⟩ | ✅ capability (weakened) |
| 5 | **Justification preservation + sufficiency** | **CAPABILITY** ×2 — preservation (recording) and sufficiency evaluation (judgment) are different acts | INV-KOS-VERIFICATION-001 · §16 | ⚠️ two acts named as one |
| 6 | **History recording** | **CAPABILITY** | INV-KOS-HISTORY-001 | ✅ capability |
| 7 | **Representation-agnostic intake** | **ANTI-CAPABILITY** — the *absence* of surface-form inspection | Test F · §11.2 | ❌ **not a capability** |
| 8 | **Epistemic-state determination** | **CAPABILITY** | §9 · §6 (*no mechanism may produce this member*) | ✅ capability |
| 9 | **Confidence assignment** | **CAPABILITY** — with no lawful input, see C-11 (§7) | ⟨R-1⟩ | ✅ capability (unconstructible) |

**Result: the nine resolve to 6 capabilities + 1 invariant + 1 anti-capability + 1 split (which is itself 1 inside + 1 outside), and two of the six are compound.** The list is a category mixture.

**Also tested, as the HPA asked:** *no semantic reasoning* → **anti-capability** (§16); *no evidence custody* → **anti-capability** (⟨C-3⟩ boundary rule); *no authority ownership* → **invariant** (INV-KOS-AUTHORITY-001) rendered as a boundary property.

**What this does and does not mean.** It does **not** move any responsibility across the boundary — the placements are unaffected. It means the *count* and the *kind* are wrong, and the mapping propagated an inherited error. **The defect originates in F-1…F-5**, whose amendment candidates remain ⬜ OPEN before the HPA, so this is not repairable inside the mapping. **HPA DECISION REQUIRED.** The mapping itself half-detected this ("a prohibition wearing a positive name") without following it through.

---

## 4 · Aggregate / consistency-boundary critique (target 3)

### 4.1 The corpus's strongest attack, in its own words

`20260823-114530` performs a pair-by-pair analysis of the six-part invariant and concludes:

> **"Semantic relatedness and traceability do NOT imply transactional atomicity."** → *"Hypothesis FALSIFIED: the six parts do NOT require one aggregate."*

Every pair is rated WEAK or MODERATE. If sound, the KnowledgeAggregate is **too large** and should decompose into events plus derived projections.

### 4.2 Why the attack fails — **SURVIVES**

The corpus's atomicity test is a **persistence-level** test ("events can be stored separately and linked by identity"). An aggregate consistency boundary is a **domain invariant-protection** boundary. Two different notions of atomicity are being conflated, and the conclusion follows only under the persistence reading — which is **DEF-5**, explicitly deferred.

Tested on the two members the corpus rates weakest under its own method:

- **EpistemicState.** Corpus: derived from event history, therefore a projection. **Refuted twice over.** If state were derived it would change *implicitly* whenever events changed — and §9 states *"There is no implicit transition"*, INV-KOS-DIMENSION-001 forbids implicit transitions outright. Further, INV-KOS-PROJECTION-001 makes a projection *never its source*, while §6 makes EpistemicState authoritative. A projection cannot be authoritative. **The law's position is not an oversight; it is load-bearing.**
- **History.** Corpus: an event stream, storable separately. **Refuted at domain altitude:** if History were outside, a revision could complete while its history record failed, violating INV-KOS-HISTORY-001 (*revision never deletes*). Atomicity holds. The corpus's counter-argument is about storage topology, not invariant protection.

### 4.3 What the attack *does* establish — convergent confirmation

The corpus reaches **the same two weak members** the mapping's own §6 proofs identified, by a completely different route:

| Member | Mapping §6 | Corpus `20260823-114530` | Convergence |
|---|---|---|---|
| **Confidence** | WEAKEST — protected by assignment locus, not atomicity | *"an assessment output… not necessary for the claim's existence"* | ✅ two independent derivations |
| **Relations** | WEAK — ⟨C-4⟩ prevents the coupling a proof needs | not tested, but ⟨C-4⟩ agrees | ✅ |

**OQ-5 is CLOSED and is not reopened here.** OQ-5 asked *whether* Confidence remains a member (ruled: yes). The critique's question is different and unasked: *from what inputs may it lawfully be computed?* — see C-11.

### 4.4 The ConflictRecord gap the HPA already caught — and its generalisation

The corpus states the governing principle explicitly, five files before the mapping was written:

> **"Do not confuse accountability, coherence, traceability, and semantic relatedness with transactional ownership."** (`20260823-123619`)

The boundary act applied this to twelve members but not to ConflictRecord's own inclusion; the HPA supplied the missing challenge and the atomicity ground is now recorded. **The critique's addition is that the principle was available in the corpus and was not applied** — a process observation, not a new architectural defect.

**C-7 · The `CONFLICTED` ↔ `ConflictRecord` cardinality is unspecified. — HPA DECISION REQUIRED.**
Via the Navya-Nyāya lens (*is a relation being treated as an intrinsic property?*): the same fact — *this claim is in conflict* — is recorded twice, once as an intrinsic member (`EpistemicState = CONFLICTED`) and once as a relation (`ConflictRecord`). The relation is **one-to-many**: a claim may be in several conflicts at once. The member is single-valued. Therefore **resolving one conflict cannot lawfully clear `CONFLICTED`** while others stand, and nothing in law or in the mapping states the rule. Neither is designated authoritative if they diverge.

**C-5 · Supersession is a second cross-aggregate locus, unrecorded. — recorded defect.**
`TemporalValidity.superseded-by` references **another** `KnowledgeId`; ⟨C-4⟩ forbids containment. So the supersession invariant (*forward-only, prior retained*) spans a **pair** of aggregates, exactly as contradiction does. The mapping records **DS-CAND-2** for cross-aggregate contradiction coordination but no equivalent locus for supersession. A third candidate locus exists and is unnamed.

---

## 5 · PROTECT vs PRODUCE critique (targets 5, 7) — **SURVIVES**

**Attack.** Can any capability classified PROTECT actually require semantic understanding? Each of the six genuine capabilities was tested against the discriminator *could this be performed without knowing what the expression means?*

| Capability | Requires meaning? | Why not |
|---|---|---|
| Identity assignment | **No** | assignment is a free authoritative act; ⟨C-1⟩ forbids deriving it from content |
| Evidence-link admission | **No** | references only; ⟨C-3⟩ forbids reading content |
| Justification **preservation** | **No** | recording a supplied structure |
| Justification **sufficiency** | ⚠️ **the thin line** — see below |
| Epistemic-state determination | **No** | determined by the disposition, not by the claim |
| History recording | **No** | append-only |
| Confidence assignment | ⚠️ **no lawful input** — C-11 |

**Target 7 — does "structural sufficiency" become semantic validity?** The mapping's line is *structural completeness* (premises present · inference rule named · conclusion the one claimed · references resolvable · path non-circular) versus *semantic validity* (are the premises true · does the conclusion follow). **The line holds**, and §16 supplies the exact warrant: a kernel member *"can refuse a transition, record a state, assign an identity, retain a history — it **cannot generate a conclusion**."* Checking that a path is well-formed generates no conclusion.

**But two of the five structural checks are not performable inside the boundary:**
- *references resolvable* — requires reaching outside, violating **D-1** (*the core depends on nothing*). See C-10.
- *path non-circular* — decidable for a finite supplied path; survives.

**Verdict: SURVIVES**, with the sufficiency check reduced in scope by C-10 and the anti-drift watchpoint confirmed as correctly placed.

---

## 6 · Anti-reasoner critique (target 6) — **SURVIVES on all ten vectors**

The commission names ten routes by which semantic reasoning could enter. Each was attacked with the strongest corpus counterexample.

| Vector | Strongest corpus attack | Caught by | Verdict |
|---|---|---|---|
| sufficiency evaluation | *"score by evidence strength"* (`20260823-104148`) | A-6 · T-2 | SURVIVES |
| epistemic-state determination | *"Kernel uses ℳSNF metrics to decide epistemic state"* (`20260822-161933`) | A-9 · T-9 | SURVIVES |
| confidence | numeric weights 0.0–1.0 (`20260823-104251`) | A-8 · T-8 | SURVIVES — but see C-11 |
| identity assignment | *"same entity / different entity"* discrimination (`20260823-103606` K1) | A-7 · A-20 · T-7 | SURVIVES |
| justification evaluation | *"does the conclusion follow"* | §16 · T-2 | SURVIVES |
| contradiction detection | *"identifies logical contradictions using formal logic or semantic inference"* (`20260823-104148`) | A-2 · T-2 | SURVIVES |
| selection | *"for each candidate interpretation… choose"* | A-4 · T-4 | SURVIVES |
| metadata | interpretation probability crossing as truth | r4-1 · A-8 | SURVIVES |
| canonicalization | SNF equality as identity | ⟨C-1⟩ · r4-2 · A-7 | SURVIVES |
| evidence handling | `evidence.weight`, `evidence_not_ancient` | A-6 · A-10 | SURVIVES |

**This is the mapping's strongest result.** Five independent, competent designs drifted into prohibited territory at more than twenty distinct points, and the register caught every drift that it names. **The register's necessity is now empirically demonstrated, not merely asserted.**

**C-9 · But the register has three coverage gaps. — PARTIALLY FALSIFIED.**
Three corpus violations are **not** named by any of the twenty anti-capabilities:

| Gap | Corpus instance | Law violated | Nearest A-n |
|---|---|---|---|
| **a scalar deciding contradiction resolution** | `SUPERSEDE: weight(A) > weight(B) × 1.5` | INV-KOS-DIMENSION-001 · INV-KOS-CONTRADICTION-001 | A-8 covers *Confidence* only, not resolution |
| **recency or age deciding truth** | `ENTRENCH: age(A) > age(B) × 10`; `observation_not_ancient: < 30 days` | Article 11 (*freshness never truth, expiry never absence*) | **none** |
| **automatic contradiction resolution without a recorded authority act** | the "continuous contradiction monitor" resolving by rule | INV-KOS-CONTRADICTION-001 (*governed resolution*) · Article 3 | **none** |

The second is the sharpest: *"freshness never truth"* is explicit law with **no anti-capability and no test**.

---

## 7 · Identity critique (target 8) + the Turing-lens findings

**Target 8 — does identity assignment become identity derivation? SURVIVES.** A-7 and T-7 pin it, ⟨C-1⟩ is unambiguous, and the corpus's contrary proposal (K1 *"the Kernel must distinguish same entity / different entity"*) is refuted directly by ⟨C-1⟩: distinguishing sameness *is* a similarity determination.

Applying the corpus's **Turing lens** (`20260823-205735` #25 — *which responsibilities are actually computable inside the boundary?*) produced the two sharpest findings of this critique.

**C-10 · Evidence-reference resolvability is not decidable inside the boundary. — recorded defect.**
Capability 4 admits evidence *references*. Verifying that a reference resolves requires reaching the external artifact — forbidden by **D-1** (*the core depends on nothing*). ⟨C-3⟩ forbids holding the content. Therefore the boundary can only **accept whatever references are supplied**, unverified. "Evidence admission" reduces to recording claims about evidence. The mapping's own T-6 presumes the boundary can observe that references are *unresolvable* — it cannot.

**C-11 · `Confidence` has no lawful input. — HPA DECISION REQUIRED. (The most dangerous finding.)**
`Confidence` is assigned **inside** (⟨R-1⟩). From what?

- evidence **content** → forbidden (⟨C-3⟩)
- a mechanism **score** → forbidden (⟨R-1⟩, obligation 5)
- **reasoning** over evidence → forbidden (§16, A-6)
- evidence **quality** → requires content
- ⟹ only **structural** facts remain (how many links, whether the path is complete)

So Confidence is either **trivial** (a restatement of structure) or it is **disguised assessment** — and the second is precisely the hidden reasoner the anti-reasoner constraint exists to exclude. The corpus asked this question explicitly and never answered it: *"How does the Kernel determine confidence without understanding evidence content?"* (`20260823-110950` Q3). This is the **third independent route** to Confidence being the weakest member. **OQ-5 is not reopened** — that ruling settled membership, not derivability.

---

## 8 · ZERO lens results (target 11) — independent pass over all fifteen conditions

| Condition | Lawful disposition | Verdict |
|---|---|---|
| **missing** (prerequisite) | pre-domain refusal — no aggregate, no identity, no state, **no event** | ✅ ⟨Z-1⟩ ruled |
| **absent** | `ABSENT` — *grounds that it does not exist* | ✅ |
| **null** | ⚠️ undistinguished from *empty* and *absent* — see below | ⚠️ gap |
| **empty** | empty collection (`EvidenceLinks`, `Relations`); open-ended `TemporalValidity` | ✅ |
| **refused** | no domain state (⟨Z-1⟩) or `REJECTED` (justification-absent) | ✅ ruled |
| **unknown** | `UNKNOWN` — first-class, initial state | ✅ |
| **unresolved** | `ConflictRecord` persists `CONFLICTED` | ✅ |
| **duplicated** | two identities (⟨C-1⟩ forbids detection) — F-CM-2 | ⚠️ unruled |
| **ambiguous** | **no lawful home** — F-CM-1 | ⚠️ unruled |
| **contradictory** | `CONFLICTED` + `ConflictRecord` | ✅ (cardinality → C-7) |
| **unverifiable** | UQ-3, deferred — and **undecidable inside** (C-10) | ⚠️ |
| **impossible** | ⚠️ **no lawful state — C-17** | ❌ **new gap** |
| **no-agent** | pre-domain refusal | ✅ ⟨Z-1⟩ |
| **no-context** | pre-domain refusal | ✅ ⟨Z-1⟩ |
| **no-justification** | `KnowledgeRejected` → `REJECTED`, kept | ✅ |

**C-17 · An internally impossible claim has no lawful epistemic state. — HPA DECISION REQUIRED. (New.)**
A single claim that is *internally* self-contradictory (asserting P and ¬P within one claim) is not covered:
`CONFLICTED` is a relation **between** claims (INV-KOS-CONTRADICTION-001 + ConflictRecord, which references two aggregates); `FALSE` requires *grounds that it is not so*; `REJECTED` requires an absent justification path — and a self-contradictory claim may arrive with a complete, well-formed path. Detecting the contradiction would in any case require **reading the claim's meaning**, which the boundary may not do (A-2). So the boundary must admit it, and no state fits.

**C-14 · `NOT_ASSESSED ≠ LOW_CONFIDENCE` is a non-collapse requirement, not a representation choice. — HPA DECISION REQUIRED (reclassification).**
The corpus's ZERO section (`20260823-123630`) lists it among six distinctions that must not collapse, alongside `UNKNOWN ≠ ABSENT` — which **is** law (§9). ⟨C-5⟩ already forbids a *"low-confidence accept"*. The mapping deferred this to Logical Architecture as **UQ-4**; on this evidence it is **law-adjacent**, the same class as F-CM-1, and deferring it to realization is precisely where the collapse would be implemented.

**Two further corpus non-collapse pairs the law cannot express:** `WITHDRAWN ≠ FALSE` (→ C-15) and `NO_EVIDENCE ≠ INVALID_EVIDENCE` — the mapping's ZERO round two conflated the latter under "unverifiable"; *no evidence*, *unverifiable evidence* and *known-invalid evidence* are three cases with two dispositions.

**C-15 · Retraction / withdrawal has no lawful representation. — HPA DECISION REQUIRED.**
The corpus raises `WITHDRAWN` in four independent files. The ten domain events contain no withdrawal; INV-KOS-HISTORY-001 forbids deletion; supersession requires a successor claim, which a retraction does not have. The mapping's eleven domain acts faithfully mirror the ten events and therefore inherit the silence. **The gap is in law, not in the mapping.**

---

## 9 · Topological lens results (target 12)

Using the corpus's own failure taxonomy (`20260823-210001`).

| Topological failure | Definition | Mapping coverage | Verdict |
|---|---|---|---|
| **Boundary leakage** | something enters without satisfying admission | **T-14** (count of admission paths = 1) | ✅ covered |
| **Boundary collapse** | external material no longer distinguished from admitted knowledge | T-2 · T-9 · T-15 partially | ⚠️ no single test |
| **False connection** | a relation asserted where none valid exists | **none** — C-12 | ❌ gap |
| **Broken connection** | a relation required for justification has disappeared | **none** — C-6 | ❌ gap |
| **Identity-preserving transformation** | representation changes, invariant survives | **none** — C-8 | ❌ gap |

**C-12 · No test for `Relations` referential integrity.** ⟨C-4⟩ makes Relations references to other `KnowledgeId`s — **internal**, so unlike evidence this *is* checkable inside the boundary without violating D-1. No test asserts that a relation's target exists. A dangling relation is a *false connection* the boundary can detect and does not.

**C-6 · The referential-staleness class. — HPA DECISION REQUIRED.**
The boundary holds **three kinds of outward reference** — `Authority` (a grant held by another context), `EvidenceLinks` (external artifacts), `TemporalValidity.superseded-by` / `Relations` (other aggregates) — and has **no mechanism to learn that any referent changed**. D-5 forbids the outside pushing knowledge back in; D-1 forbids the core reaching out. Therefore admitted knowledge can become silently stale in its own justification, with **no lawful repair path**.
The corpus supplies the fan-out that makes this severe: one evidence artifact may support many claims (`20260823-123619` many-to-many test), so a single external invalidation silently affects arbitrarily many admitted states. The mapping records only the narrowest instance (**UQ-5**, revoked authority) and treats it as one open question rather than a class.

**Is the boundary the smallest structure preserving the invariants?** **Yes — SURVIVES.** §4.2 refutes the only serious decomposition attack; and no attack showed a *smaller* structure preserving all eleven.

---

## 10 · Transformation / Escher lens results (target 13)

**What must remain invariant under representation change?** `KnowledgeId`. §8 states it explicitly for `MeaningTranslated` (*meaning preserved; **KnowledgeId unchanged***).

**C-8 · No fitness test asserts identity continuity across transitions. — recorded defect.**
The twenty tests cover identity *assignment* (T-7, that it is not derived from similarity) but **not identity persistence**: nothing asserts that `KnowledgeId` is invariant under `EvidenceAdded`, `BeliefRevised`, `MeaningTranslated` or `KnowledgeSuperseded`. This is the single most important invariant in the architecture (INV-KOS-IDENTITY-001 — remove it and the system is a claim store, §4.3) and the suite does not test its persistence.

**Could the boundary make representation-dependent decisions?** T-1 (no parser in the closure) and T-2 (semantic invariance) together make this detectable. **SURVIVES.**

---

## 11 · Sanskrit / semantic-compiler lens results (target 14) — **SURVIVES**

Verified that grammar, parsing, candidate generation, disambiguation and semantic interpretation all remain outside:

```
expression → candidate interpretation → semantic structure → domain admission
   Expression ctx        R&V ctx            port vocabulary      KnowledgeCore
   (outside)            (outside)           (crossing)           (inside)
```

Every stage before the port is external by law: Test F · F-3 · §16 (Semantic Compiler at *mechanism* altitude, SNF at *representation* altitude) · §17 (language engine is a Chapter IV refusal) · obligations 1 and 4. Tests T-1, T-3, T-4, T-5 pin it structurally.

The corpus agrees emphatically and independently — `20260823-103255`, `20260823-110248`, `20260823-110305`, `20260823-205735` all place the Pāṇinian/FST/compiler family outside the Kernel. **The strongest corpus statement:** *"interpretation mechanisms are replaceable; identity authority is not."* **No falsification available.**

---

## 12 · Navya-Nyāya relational lens results (target 15)

*Where is a relation being treated as an intrinsic property?*

| Concept | Relation or property? | In law | Verdict |
|---|---|---|---|
| **same meaning** | **relation** | not expressible — ⟨C-1⟩ forbids the core determining it | F-CM-2 · C-4 |
| **contradiction** | **relation** | recorded **both** ways: `EpistemicState=CONFLICTED` (property) + `ConflictRecord` (relation) | **C-7** |
| **evidence** | **relation** (*E supports K*) | held as `EvidenceLinks` — a property of K, not a first-class relation | ⚠️ see below |
| **authority** | **relation** | correctly a **reference** (Article 3) | ✅ |
| **agency** | **property** | correctly a member | ✅ |
| **context** | **property**, constitutive of identity | correctly a member (Article 1.4) | ✅ |
| **justification** | **relation** (*path from premises to conclusion*) | held as a member VO | ⚠️ acceptable — the path is recorded, not the relation's own lifecycle |

**Recorded observation (not a defect):** *evidence supports claim* is modelled as a property of the claim (`EvidenceLinks`) rather than as a relation with its own lifecycle. Under ⟨C-3⟩ this is deliberate and lawful — the core owns the link, not the artifact — but it is **why C-6 and C-10 bite**: a relation modelled as a property cannot notice that its other end moved.

---

## 13 · Temporal lens results (target 16)

| Concern | Coverage | Verdict |
|---|---|---|
| **revision** | `BeliefRevised`, forward-only, prior → History | ✅ |
| **supersession** | `KnowledgeSuperseded` — but **cross-aggregate**, unrecorded locus | **C-5** |
| **rejection** | ruled (⟨Z-1⟩ / r5) — three causes, two dispositions | ✅ |
| **conflict / resolution** | `CONFLICTED` + ConflictRecord — cardinality unspecified | **C-7** |
| **history** | member, append-only, atomicity defended §4.2 | ✅ |
| **identity continuity** | law states it; **no test** | **C-8** |
| **retraction / withdrawal** | **no lawful representation** | **C-15** |
| **constitutional version** | ⚠️ nothing binds an admitted state to the law version that admitted it | **C-18** |

**C-18 · No constitutional-version binding on admitted state. — HPA DECISION REQUIRED.**
§16 permits amendment (*"nothing moves between altitudes except by constitutional amendment"*), and **r5 has just demonstrated that v1.1 revises**. An admitted state was admitted under a particular version of law, and nothing records which. After an amendment, prior admissions are not reproducible — which interacts directly with C-2 (determinism). The corpus asks exactly this and leaves it open: *"Who determines the authoritative constitutional version? Who certifies it? How is it identified?"* (`20260823-110248`). A possible lawful home exists (an EvidenceLink, or History) — that is the HPA's call, not the critic's.

---

## 14 · Authority / governance lens results (target 19)

Every route by which something could accidentally acquire authority:

| Route | Could it acquire authority? | Barrier | Verdict |
|---|---|---|---|
| **candidate** | No | obligation 1 · ⟨C-2⟩ · T-9 | ✅ |
| **selector** | No | obligation 4 · r4-4 · A-4 | ✅ |
| **confidence** | ⚠️ **yes, structurally** — C-11: with no lawful input, any real input is smuggled | ⟨R-1⟩ names the locus, not the inputs | ❌ |
| **canonical form** | No | ⟨C-1⟩ · r4-2 · A-7 | ✅ |
| **reasoning result** | No | §16 · A-2 · T-2 | ✅ |
| **evidence** | ⚠️ **partly** — C-9 gap (a) and (b): weight or age deciding resolution | INV-KOS-DIMENSION-001, but no A-n | ❌ |
| **human act** | No — correctly a reference, never held | Article 3 · A-11 | ✅ but see C-3 (scope unchecked) |

**Gödel-lens corroboration.** The corpus concludes *"the Kernel cannot be the ultimate constitutional authority"* and *"cannot establish its own correctness from inside itself."* The mapping's approach — external fitness tests rather than self-audit — is the correct consequence, and the corpus's proposal that the Kernel self-audit its own code (`20260823-104148`) is correctly refused. **SURVIVES.**

**Anti-corruption layer (target 18) — SURVIVES.** No external semantic vocabulary leaks into the domain model: `declared determination` / `declared insufficiency` / interpretation probability / provenance / transformation evidence all remain **port vocabulary** and never members (r4-4). Verified against Q1's eight elements — none is a member.

---

## 15 · Failure / refusal-path results (target 20)

Every refusal traced. **This is where the r5 correction proves its worth: the three gate causes now have two distinct dispositions.**

| Refusal | Where | Domain state? | History? | Event? |
|---|---|---|---|---|
| **port-contract violation** (verdict, proposed KnowledgeId, scalar) | **ACL** | No | No | **None** |
| **agency absent** (constitutive prerequisite) | **pre-domain** | No | No | **None** — ⟨Z-1⟩ |
| **context absent** (constitutive prerequisite) | **pre-domain** | No | No | **None** — ⟨Z-1⟩ |
| **justification path absent** | **KnowledgeCore** | **`REJECTED`, kept** | Yes | **`KnowledgeRejected`** |
| **post-admission justification failure** | **KnowledgeCore** | `REJECTED` | Yes, prior → History | `BeliefRevised` |
| **mechanism declared insufficiency** | mechanism-side → admitted | **`UNKNOWN`** | Yes | `KnowledgeCreated` |
| **authority absent** | ⚠️ **untraced** — C-3 | ? | ? | ? |
| **authority present but out of scope** | ⚠️ **untraced** — C-3 | ? | ? | ? |
| **internally impossible claim** | ⚠️ **untraced** — C-17 | none fits | ? | ? |

**The three ruled causes are clean and complete.** Three refusal paths are untraced, and all three trace back to C-3 and C-17 rather than to a defect in the ⟨Z-1⟩ ruling itself.

**One residual, recorded:** the mapping states no **atomicity** guarantee for a partially-completed admission. The corpus flags the window (`20260823-110248` §2: *"the state could have changed between adjudication and application"*). If admission is not atomic, a refusal could leave partial state — which no test detects. Related to C-2. **DEFERRED BY EXISTING LAW (DEF-1/DEF-5)** as realization, but the *requirement* is unstated.

---

## 16 · F-CM-1 critique (target 9) — is *Ambiguity ≠ Contradiction* derivable from existing law?

**Verdict: NO. It genuinely requires an HPA ruling. The mapping's finding is CONFIRMED, and the critique strengthens the evidence.**

**Independent verification of the absence.** §15's non-collapse list has **eleven** rows; *Ambiguity ≠ Contradiction* is not among them. The token `ambiguity` occurs in v1.1 exactly **twice** — the §13 LLM row and OQ-4's corpus requirements — never as a modelled distinction. Confirmed by direct search, not by trusting the mapping.

**New evidence the mapping did not have — the corpus exerts sustained pressure toward an eighth state.** Across **five independent artifacts** the corpus reaches for an explicit `AMBIGUOUS` state or verdict:

| Artifact | Form |
|---|---|
| `20260823-103255` | *"Ambiguity → explicit state \| **Very high**"*; `{MATCH \| MISMATCH \| AMBIGUOUS \| UNRESOLVED}` |
| `20260823-104148` | `RESOLVED / AMBIGUOUS / MISMATCH / UNRESOLVED` as a kernel output |
| `20260823-104251` | `AMBIGUOUS` as a rule result and a kernel state |
| `20260823-111647` | ambiguity among the proposed epistemic states |
| `20260823-123630` | ZERO-lens non-collapse pairs |

Five independent attempts, each landing on a state the law does not have and **T-19 forbids**. That is not a coincidence; it is the shape of a real modelling gap.

**Why Model B remains a category error — independently re-derived.** Treating competing interpretations as `CONFLICTED` requires the core to judge that two candidates are *about the same thing* — a **similarity determination forbidden by ⟨C-1⟩**. The mapping's reasoning holds under attack.

**The corpus supplies the affirmative evidence for the third reading, from its own research result:** P5 Established item 7 — SNF-C's one measurable advantage was recognising an omitted kāraka as **underdetermined rather than resolvable** (`20260822-161933`, `20260823-123630` §21). Underdetermination already has a lawful home: **declared insufficiency → `UNKNOWN`** (obligations 3 and 6, ⟨C-5⟩).

**Not decided here.** The ruling determines whether selection sits **outside** the boundary (Model A / third reading) or **inside** it (Model B) — a placement consequence. **HPA DECISION REQUIRED.**

---

## 17 · F-CM-2 critique (target 10) — does the inability to deduplicate violate an invariant?

**Verdict: NO. It violates nothing. It is the direct and intended-looking consequence of *identity is assigned, never derived*.**

Tested against all eleven. INV-KOS-IDENTITY-001 requires identity be **assigned**, and ⟨C-1⟩ forbids similarity or canonical-form equality becoming identity. Detecting that two candidates carry the same meaning **is** a similarity determination. So the inability is not a defect — it is the invariant working. Nothing anywhere in law requires `KnowledgeId` to be unique *per meaning*; uniqueness is per *assignment*.

**But the mapping understates the consequence. — C-4 territory.**

**C-16 · Admission is not idempotent, and this is unstated.** The corpus's scenario list (`20260823-123619`) includes **"replay of admission"** and **"duplicate identity attempt"**. Under F-CM-2, resubmitting the identical candidate produces a **second identity** — the boundary cannot recognise a replay. T-20 records that two admissions of one meaning produce two identities, but the mapping never states the operational consequence: **there is no idempotency concept at the gate**, so a retried submission silently creates a duplicate knowledge object. Combined with C-2 (determinism unmapped), the gate has neither a stated determinism property nor a stated idempotency property.

**HPA DECISION REQUIRED** on intent — and on the narrower question the corpus raises: may the *assigner* consult existing knowledge when assigning, without thereby *deriving* identity?

---

## 18 · New defects — consolidated register

| # | Defect | Lens that exposed it | Class |
|---|---|---|---|
| **C-1** | the nine "capabilities" are a category mixture (6 capabilities + 1 invariant + 1 anti-capability + 1 split; 2 compound) | DDD / taxonomy | **PARTIALLY FALSIFIED** · HPA (F-1…F-5) |
| **C-2** | determinism unmapped, yet T-2 presupposes it | deterministic-assurance | **PARTIALLY FALSIFIED** · HPA |
| **C-3** | authority-adequacy/scope check unhomed; three refusal paths untraced | authority / governance | **HPA DECISION REQUIRED** |
| **C-4** | **the revision/targeting path is unmapped** — Q1 covers creation only | DDD / lifecycle | **FALSIFIED** |
| **C-5** | supersession is a second cross-aggregate locus, unrecorded | temporal / relational | recorded defect |
| **C-6** | referential-staleness class (Authority · Evidence · Relations), with fan-out | topological (broken connection) | **HPA DECISION REQUIRED** |
| **C-7** | `CONFLICTED` ↔ `ConflictRecord` cardinality unspecified | Navya-Nyāya | **HPA DECISION REQUIRED** |
| **C-8** | no test for identity continuity across transitions | Escher / Śiva–Śakti | recorded defect |
| **C-9** | three coverage gaps in the anti-capability register (scalar-resolution · age-as-truth · unauthorised auto-resolution) | authority | **PARTIALLY FALSIFIED** |
| **C-10** | evidence-reference resolvability undecidable inside (D-1) | Turing | recorded defect |
| **C-11** | **`Confidence` has no lawful input** | Turing / epistemic | **HPA DECISION REQUIRED** |
| **C-12** | no test for `Relations` referential integrity | topological (false connection) | recorded defect |
| **C-14** | UQ-4 misclassified — a non-collapse requirement, not a representation choice | ZERO | **HPA DECISION REQUIRED** |
| **C-15** | retraction/withdrawal has no lawful representation | temporal / ZERO | **HPA DECISION REQUIRED** |
| **C-16** | admission is not idempotent; replay creates duplicates | ZERO (replay) | recorded defect |
| **C-17** | internally impossible claim has no lawful state | ZERO (impossible) | **HPA DECISION REQUIRED** |
| **C-18** | no constitutional-version binding on admitted state | temporal / constitutional | **HPA DECISION REQUIRED** |

**Two methodological findings, recorded separately because they concern the chain rather than the artifact:**

**C-13 · The mapping never ran the HPA's own required reasoning chain on the capabilities. — PARTIALLY FALSIFIED.**
`20260823_1239_working_state.md` specifies it: *Capability → Domain responsibility → Invariant → What must change atomically? → What may change independently? → Consistency boundary → Aggregate/service/process.* The mapping classified the nine by PROTECT/PRODUCE and placed them inside/outside, **skipping the atomicity and independence steps**. Those steps were run in the *prior* act, on the twelve **members** — never on the **capabilities**. Had they been run, C-1 would have surfaced there.

**C-19 · The boundary was never validated against the historical domain corpus. — PARTIALLY FALSIFIED (program level).**
The corpus warns explicitly: *"Is the proposed Kernel actually the distilled domain core of EKS/PKS/KnowledgeOS, or are we accidentally reducing KnowledgeOS to its latest admission model?"* — and marks EKS/PKS historical continuity **🔴 MISSING** (`20260823-110950`). The whole chain (F-1…F-5 → boundary → mapping) derives from the **inbound admission pipeline**. `20260823-103606` §8 proposed a capability matrix explicitly including existing EKS, PKS and the AI Engineering Platform; that matrix was never built. **The mapping may be a faithful model of one pipeline rather than of the domain.**

---

## 19 · Surviving claims

Recorded explicitly, because a critique that reports only defects misrepresents the result.

| Claim | Status |
|---|---|
| Boundary extent = KnowledgeAggregate + ConflictRecord, one admission gate | ✅ **SURVIVES** — no smaller structure preserves the eleven; the decomposition attack fails on a persistence/domain conflation |
| Two aggregates, separate consistency boundaries (K-1 ruling) | ✅ **SURVIVES** |
| Name *KnowledgeCore Admission Boundary* | ✅ **SURVIVES** — v1.1's canonical wording is *admissibility of a **state transition***, so post-admission acts are admissions too |
| PROTECT/PRODUCE discriminator | ✅ **SURVIVES** — held on every capability; the structural-completeness line holds |
| Anti-reasoner constraint, all ten vectors | ✅ **SURVIVES** — and its necessity is now empirically demonstrated |
| ⟨Z-1⟩ / the three gate causes (r5) | ✅ **SURVIVES** — clean and complete for the causes it names |
| Items 5 and 7 deferred by DEF-1 | ✅ **SURVIVES** — correctly refused |
| Sanskrit/FST/SNF/LLM outside | ✅ **SURVIVES** — no falsification available |
| EpistemicState as an authoritative member, not a projection | ✅ **SURVIVES** — derivation would be an implicit transition |
| T-2 SEMANTIC-INVARIANCE as the decisive test | ✅ **SURVIVES** — correctly identified as the only detector of invisible drift (given C-2) |
| ACL: no external semantic vocabulary in the domain model | ✅ **SURVIVES** |
| F-CM-1 requires an HPA ruling | ✅ **CONFIRMED**, with stronger evidence |
| F-CM-2 violates no invariant | ✅ **CONFIRMED** |

---

## 20 · Questions requiring an HPA ruling

1. **C-1** — is the nine-item capability list to be re-typed? (touches F-1…F-5, ⬜ OPEN)
2. **C-2** — is determinism a boundary property, a fitness constraint, or DEF-1?
3. **C-3** — who checks authority *adequacy*? (a tenth capability, or a seventh context)
4. **C-6** — what is the disposition of a stale outward reference? (class, not one question)
5. **C-7** — `CONFLICTED` ↔ `ConflictRecord` cardinality and precedence
6. **C-11** — from what lawful input may `Confidence` be computed? (**not** OQ-5)
7. **C-14** — reclassify UQ-4 as a non-collapse requirement?
8. **C-15** — does retraction require a lawful representation?
9. **C-17** — what state does an internally impossible claim bear?
10. **C-18** — must an admitted state bind the constitutional version that admitted it?
11. **F-CM-1** — *Ambiguity ≠ Contradiction*: add the distinction, and does selection sit inside or outside?
12. **F-CM-2** — is non-unique identity-of-meaning intended, and may the assigner consult existing knowledge?
13. **C-19** — must the boundary be validated against EKS/PKS/AIP before the implementation decision?

---

## 21 · Questions requiring future research — **RECORDED ONLY, NOT AUTHORIZED**

1. **RESEARCH REQUIRED — NOT AUTHORIZED.** Whether an *invariant-conflict meta-rule* is derivable, or whether each conflict needs a bespoke ruling. ZERO-DEFECT-1/2 proved two invariants can jointly make a case unrepresentable; both were ruled case-by-case; no general rule exists and none was sought.
2. **RESEARCH REQUIRED — NOT AUTHORIZED.** Whether a structurally-only-derived `Confidence` (C-11) carries any epistemic meaning, or whether the member is vestigial under ⟨R-1⟩ + ⟨C-3⟩ + §16 taken together.

**No research was performed. SNF stays CLOSED. OQ-4 stays UNAUTHORIZED.** The topological lens was used analytically and is **not** promoted — the corpus itself records it as *"strongly justified analytical lens, but not yet an independently admitted KnowledgeOS research family"*; register **25+4 unchanged**.

---

## 22 · Explicit statement: did the Kernel mapping survive?

> **YES — as an architecture. NO — as a complete specification.**

**Survived:** every placement, the boundary extent, the two-aggregate structure, the PROTECT/PRODUCE discriminator, all ten anti-reasoner vectors, the ⟨Z-1⟩ ruling, the DEF-1 refusals, and both F-CM findings. **No attack moved a single responsibility across the boundary, and no attack produced a smaller sufficient boundary.**

**Did not survive:** completeness. **C-4 is an outright falsification** — the mapping answers "what enters" only for the creation of a new claim, while six of its own eleven domain acts operate on *existing* aggregates, and the targeting mechanism for those is caught between obligation 4 (a mechanism may never propose a `KnowledgeId`) and ⟨C-1⟩ (the core may not match by meaning). Five further partial falsifications concern the taxonomy, determinism, register coverage, methodology and program-level derivation.

**The honest summary:** the mapping is a **correct partial specification of admission** that presents itself as a specification of the boundary. Its placements are sound; its enumerations are not closed.

---

## 23 · Explicit statement: no architecture was changed

**Nothing in this act changed any architecture.** No repair was proposed for any defect; each is recorded and classified only. Specifically **not** done: no edit to the Kernel Capability Mapping · no edit to v1.1 · no edit to the Constitution · no edit to the Port Contract · no edit to any aggregate · no invariant added · no state added · no event added · no code · **no tests created in the repository** (the twenty test specifications remain the mapping's; this critique creates none) · no research opened · no HPA question resolved · no terminology renamed (C-1 and the taxonomy are recorded as findings, and no name was changed).

**Verified unchanged:** register **25+4** · bounded contexts **6→6** · aggregates **5→5** · aggregate members **12→12** · domain events **10→10** · epistemic states **7→7** · invariants **11→11** · constitutional articles **11→11** · Constitution **FROZEN** · research **CLOSED** · SNF **CLOSED** · OQ-4 **UNAUTHORIZED** · AH-5 **still deferred and not resolved by inference**.

**One file-system act was performed under the same commission** (the HPA's instruction *"Rename them starting the name with timestamp"*): **20 brainstorming artifacts renamed** to the house convention `YYYYMMDD-HHMMSS-<content-title>.md` using each file's filesystem **mtime** as the saved time, following the three prior intake batches recorded in `docs/knowledgeos/brainstorming/00_INDEX.md`. **Renames only — no content edited, nothing deleted, mtimes preserved, 117 corpus files intact.** `00_INDEX.md` was deliberately **not** renamed: its `00_` prefix is the documented sort-first index convention and a timestamp would break its role. One byte-exact duplicate was found and **preserved** per house rule (`kernel/20260823-110305-…-duplicate.md` = the tail of `kernel/20260823-110248-…`). Recorded in `00_INDEX.md` as the **fourth intake batch**.

---

## 24 · Completion report

```yaml
act: INDEPENDENT-DDD-CRITIQUE-OF-KERNEL-CAPABILITY-MAPPING
status: COMPLETED
role: INDEPENDENT CRITIC (falsification only)
mapping_improved: NO
mapping_edited: NO
architecture_changed: NO
research_opened: NO
research_required_recorded: 2
tests_created_in_repository: NO
hpa_questions_resolved: NO
terminology_renamed: NO
corpus_read: "21 artifacts, ~24,000 lines, chronological, in full"
corpus_files_renamed: 20
verdict:
  placement:     "SURVIVES — no responsibility moved across the boundary; no smaller sufficient boundary found"
  completeness:  "FAILS — 1 falsified, 5 partially falsified, 11 recorded defects"
falsified:            "C-4 (revision/targeting path unmapped)"
partially_falsified:  "C-1 taxonomy · C-2 determinism · C-9 register coverage · C-13 methodology · C-19 program derivation"
survives:             "boundary extent · two aggregates · K-1 name · PROTECT/PRODUCE · all 10 anti-reasoner vectors · Z-1 · DEF-1 refusals · Sanskrit outside · EpistemicState as member · T-2 · ACL · F-CM-1 · F-CM-2"
most_dangerous:       "C-11 — Confidence has no lawful input; every candidate input is forbidden by C-3, R-1 or §16"
most_important:       "C-1 — the nine MUST-EXIST capabilities are a category mixture (defect originates in F-1…F-5)"
hpa_decisions_required: 13
deferred_by_existing_law: "items 5 and 7 (DEF-1) · admission atomicity (DEF-1/DEF-5) · UQ-3"
structural_inventory: "register 25+4 · contexts 6→6 · aggregates 5→5 · members 12→12 · events 10→10 · states 7→7 · invariants 11→11"
next_actor: HUMAN PRINCIPAL ARCHITECT
next_act: "governance adjudication of the 13 recorded questions — NOT implementation"
```

**PROPOSED · NON-AUTHORITATIVE.** This critique supplies evidence and classifications only. It accepts nothing, adopts nothing, repairs nothing, and authorizes nothing (R-34). **The next actor is governance/HPA, not implementation. The Kernel is not built.**

---

---

## 25 · HPA REFRAMING OF C-19 (2026-08-23) — and the first two consistency-check results

### 25.1 The reframing, recorded

The HPA reframed **C-19** before adjudication. Recorded verbatim-in-substance:

> **C-19 is a CONSISTENCY CHECK, not a research programme.** The question is **not** *"does the Kernel need to validate itself against all of EKS/PKS/AIP?"* — which risks **expanding** the Kernel. It is: **"Given the previously established KnowledgeOS scope and its relationship to EKS, PKS and the AI Engineering Platform, does the proposed Kernel boundary violate any already-established architectural dependency, responsibility, or ownership?"**
>
> The direction of travel is **downward into a smaller boundary, never outward**. **EKS/PKS/AIP are context for validating the boundary, not candidates for inclusion in it.** No new research track is implied.

The HPA additionally ruled on the historical stack diagram (`AI Engineering Platform → EKS/PKS → KnowledgeOS → KnowledgeCore → Kernel`): it is **historical architectural context / candidate predecessor model — verify, do not assume authoritative**, and it **mixes architectural altitudes**. Three views are to be kept separate: an **ecosystem** view, a **KnowledgeOS architectural** view, and the **Kernel** view.

**Effect on this critique's classification.** C-19 was recorded as *PARTIALLY FALSIFIED (program-level derivation)* with the open question *"must the boundary be validated against EKS/PKS/AIP before the implementation decision?"* The reframing **answers that question** — yes, but as a bounded consistency check — and thereby **narrows C-19 from an open programme risk to a defined, executable check**. The finding itself is unchanged; its remedy is now specified. Recorded as a consequence of the reframing; the original text of §18 stands (ES-004.3 — findings are not rewritten).

### 25.2 CC-1 · The historical nesting contradicts §16's altitude extent — **CONFIRMED INCONSISTENCY**

The first consistency check, executed against law:

The historical diagram draws strict containment — **`Kernel ⊂ KnowledgeCore ⊂ KnowledgeOS`**. But v1.1 **§16** declares the **KERNEL altitude**'s members as *"the eleven invariants · KnowledgeAggregate · ConflictRecord · **the three small supporting aggregates**."* Those three are `AuthorityGrant`, `DerivedView` and `DecisionRecord` — and **§6.2 places them in the Authority, Projection and Decision Boundary contexts**, all *outside* KnowledgeCore.

> **Therefore, under the §16 altitude reading, `Kernel ⊄ KnowledgeCore`.** The altitude is **wider** than KnowledgeCore, spanning four bounded contexts. Only the **admission-boundary** reading nests the way the historical diagram draws it.

This is exactly the overload **K-1** identified and the HPA ruled: `KERNEL` is reserved for the §16 change-governance altitude, while the structure this chain defines is the **KnowledgeCore Admission Boundary**. **The historical diagram is therefore accurate for neither reading without disambiguation** — it draws containment that holds for the admission boundary while using the name that belongs to the altitude.

**Verdict: a real inconsistency between the historical picture and current law, confirmed.** It does **not** falsify the proposed boundary — it falsifies the *diagram*. Per the HPA's own direction (*"if the old diagram is genuinely obsolete, we should eventually record that explicitly rather than allowing two architectural pictures to coexist indefinitely"*), the disposition of the diagram is an **HPA act**; this critique records the inconsistency and performs no correction.

### 25.3 CC-2 · A Governance bounded context appears in three independent models and in none of the law — **C-3 CORROBORATED FROM A THIRD DIRECTION**

The second consistency check produced a stronger result than expected.

v1.1 **§5.3** declares **six** bounded contexts: KnowledgeCore · Authority · Projection · Decision Boundary · Reasoning & Validation · Expression. **Governance is not among them.** `Authority` owns only the `AuthorityGrant` — *the recorded reference to a human act* — and nothing in the six evaluates **eligibility, policy, or authority scope**.

Yet a **Governance context** appears in three independent models:

| Source | Form |
|---|---|
| brainstorming corpus (`kernel/20260823-110248`, `…110950`) | a **Governance Context** owning constitutional rules, authority, eligibility, policy, evidence admissibility |
| this critique, **C-3** | the authority-**adequacy** responsibility found **unhomed** — the register catches self-authorization, not *insufficient* authority |
| the HPA's own KnowledgeOS architectural view (2026-08-23) | `Governance` listed as a bounded context alongside Expression, R&V, Projection, Decision Boundary and KnowledgeCore |

> **Three independent models contain a Governance context. The law contains none.** That is the strongest available evidence that **C-3 is a genuine gap in the model rather than an omission in the mapping.**

The gap remains exactly as C-3 states it: either the boundary performs the authority-adequacy check (and the capability list needs a tenth entry) or a context must own it (and adding one is **contexts 6→7**, an amendment-class change). **Nothing is decided here.**

**One further minor inconsistency, recorded without weight:** the HPA's architectural view lists *Expression* among KnowledgeOS's own contexts. §5.3 classifies **Expression as GENERIC/EXTERNAL** — an adapter *at a port*, owning *"its own transient artifacts — no domain state."* The Kernel column of the HPA's concern table is correct; only the KnowledgeOS column overstates ownership.

### 25.4 What these two results do and do not establish

**They establish:** the historical stack picture cannot be used as current authoritative nesting (**CC-1**), and the authority-adequacy gap is real and independently triple-confirmed (**CC-2**).

**They do not establish:** any change to the proposed boundary. Neither check moved a responsibility, and neither found EKS, PKS or the AI Engineering Platform to contain anything that must be inside the Kernel. **The consistency check, so far, supports the boundary.** C-19's remaining scope — the full check against established EKS/PKS/AIP dependencies and ownership — is unexecuted and awaits a commission.

**Also routed onward, not resolved here:** the HPA's question about *"the smallest boundary of knowledge **and wisdom**"* was flagged as **amendment-gated** — v1.1 §4.1 rejects Wisdom Formation at the boundary, §17 lists it among the Chapter IV refusals against **V.3**, and §8 records `WisdomDerived` as *"NOT admitted."* That flag is carried into the separately commissioned ADR (`20260823-2229-KOS-ADR-KnowledgeOS-Core-Today-and-Future-Epistemic-Brain-Vision.md`), which states the constitutional position and makes no amendment.

**Register unchanged by §25:** 25+4 · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · states **7→7** · invariants **11→11** · Constitution **FROZEN**. No architecture changed, no diagram corrected, no context added.


## Traceability

- **Commission:** HPA, 2026-08-23 — *KOS — INDEPENDENT DDD CRITIQUE OF KERNEL CAPABILITY MAPPING*, verbatim-in-substance: independent critic role · primary rule *do not improve, try to break* · 20 falsification targets · the lens corpus as a non-authoritative falsification instrument · the governance rules (`RESEARCH REQUIRED — NOT AUTHORIZED` · `HPA DECISION REQUIRED` · no silent renaming) · the 23-item deliverable · the critical stop condition. Plus the file commission: list untimestamped brainstorming files, determine their saved timestamps, read oldest-first, rename with timestamp, and assess kernel usefulness.
- **Target:** `20260823-2103-KOS-EP01-Kernel-Capability-Mapping.md`.
- **Law consulted directly:** v1.1 **r5** (`20260822-1402`) §4 · §4.3 · §5.2 · §5.3 · §6 + altitude note · §6.1 · §7 + enforcement loci · §8 + ⟨A-3⟩ · §9 + ⟨Z-1⟩ · §11.2 · §13 · §14 · §15 (eleven non-collapse rows) · §16 · §17 · §18 D-1…D-6 · §19 · §20 · Appendix A (r3/r4/r5 ledgers) · Port Contract r4 (`20260822-1559`) §2 obligations 1–6 · §4 r4-4 · §5 trust boundary · F-1…F-5 (`20260823-1051`) · boundary + ruling + r5 (`20260823-1306`) §10 · §11 · P5 acceptance (`20260822-2327`) Established item 7 · AH confirmation (`20260822-2346`).
- **Corpus read (chronological, renamed in this act):** `20260822-161933` · `20260823-103255` · `103606` · `104148` · `104251` · `105200` · `110248` · `110305`(dup) · `110950` · `111647` · `112155` · `112855` · `113410` · `113645` · `114358` · `114530` · `123619` · `123630` · `20260823_1239_working_state.md` · `205735` · `210001`, plus `00_INDEX.md`.
- **Branch note:** the corpus renames + `00_INDEX.md` fourth-intake record land on **`election-review`** (where the corpus is tracked and where the untracked files physically live, matching the three prior intake batches); this critique lands on **`kos-v11-ddd-refinement`** with the rest of the governed Kernel chain. The two branches diverge; the split is recorded so citations remain traceable.
- **Status:** 📋 **DELIVERED · PROPOSED · NON-AUTHORITATIVE.** Mapping **SURVIVES on placement, FAILS on completeness** · 13 HPA questions recorded · 2 research questions recorded and **NOT** authorized · no architecture changed · **the Kernel is not built.**
