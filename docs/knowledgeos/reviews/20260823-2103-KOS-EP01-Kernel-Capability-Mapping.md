# KnowledgeOS — Kernel Capability Mapping — what the KnowledgeCore Admission Boundary protects, what it refuses, and how every prohibition becomes a test (2026-08-23)

> **Role:** the commissioned **Kernel Capability Mapping** act — the governed step between the accepted boundary and the implementation decision. It answers the HPA's ten questions, applies the **ZERO lens** a second time, and turns every prohibition into an **executable architecture test specification**.
> **Commission:** HPA, 2026-08-23 — *"I would now commission Kernel Capability Mapping."* Ten questions (§2–§11) · the ZERO lens re-applied (§12) · and the explicit requirement: **"Capability mapping must distinguish 'the Kernel protects/validates a domain responsibility' from 'the Kernel performs reasoning that produces the responsibility's semantic content.'"**
> **Position:** P5 ✅ → F-1…F-5 ✅ → Kernel boundary **ACCEPTED** ✅ → ZERO-DEFECT-1/2 **RULED** ✅ → K-1 **RULED** ✅ → §9 correction **APPLIED + VERIFIED (r5)** ✅ → **← THIS ACT** → independent DDD critique → fitness tests → HPA ruling → implementation decision.
> **Status:** 📋 **DELIVERED · PROPOSED · NON-AUTHORITATIVE · decision ⬜ OPEN (HPA).** No code · no schema · no API · no class · no technology · no research · no DSL · no parser · no FST · no SNF · no redesign · no v1.2. Register **25+4** · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · states **7→7** · invariants **11→11** · Constitution **FROZEN** · OQ-4 **UNAUTHORIZED** · **AH-5 NOT resolved by inference** (see F-CM-5). **The Kernel is not built and no implementation is authorized.**
> **Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos --maturity=research` → `docs/knowledgeos` (exit 0).

---

## 0 · The answer, in one page

**The mapping in one sentence:** every capability inside the KnowledgeCore Admission Boundary is a **disposition over a supplied artifact** — admit, refuse, assign, record, relate, retain — and **not one of them produces semantic content**; everything that *produces* meaning, candidates, conclusions, normal forms or selections is a mechanism at a port.

```
                    ┌────────── PRODUCES (mechanism, outside) ──────────┐
                    │ parse · interpret · normalize · canonicalize      │
                    │ reason · infer · generate · select · score        │
                    └───────────────────────┬──────────────────────────┘
                                            │ candidate + justification path
                                            │ + declared determination / insufficiency
                    ════════════════════════▼════════════════════════
                            VERIFICATION PORT — the single gate
                    ════════════════════════┬════════════════════════
                    ┌───────────────────────▼──────────────────────────┐
                    │ DISPOSES (domain, inside)                        │
                    │ check prerequisites · admit or refuse · assign    │
                    │ identity · admit meaning · admit evidence links · │
                    │ evaluate sufficiency of the SUPPLIED path ·       │
                    │ determine epistemic state · assign confidence ·   │
                    │ record history · relate · retain conflict        │
                    │                                                  │
                    │ ⛔ NEVER: produce, infer, generate, select        │
                    └──────────────────────────────────────────────────┘
```

**The one genuinely new discovery — F-CM-1.** Q8 (*how are competing interpretations represented?*) cannot be answered from existing law, because the law lacks the distinction it needs: **ambiguity ≠ contradiction.** §15's non-collapse list has eleven rows; this is not one of them. Answering Q8 with `CONFLICTED` + `ConflictRecord` — the intuitive reading — would collapse *which meaning is this?* (an **identity** question) into *which claim is true?* (a **truth** question), and would make the core a **semantic arbiter**, breaching the anti-reasoner constraint the same commission established. **HPA ruling required. This act does not decide it.**

**The second finding — F-CM-2.** The core is *forbidden* to notice that two candidates carry the same meaning (⟨C-1⟩: similarity, canonical-form equality → never identity). Therefore **identity-of-meaning is not enforceably unique**: the boundary cannot deduplicate, and two admissions of one meaning yield two identities. This is a consequence of law, not a defect in the mapping — but nobody has ruled whether it is intended.

---

## 1 · The governing distinction — PROTECT/VALIDATE vs PRODUCE

The HPA's explicit requirement, and the discriminator used in every table below.

| | **PROTECT / VALIDATE** (inside) | **PRODUCE** (outside) |
|---|---|---|
| **Question it answers** | *does this supplied artifact satisfy the domain's requirements?* | *what is the semantic content?* |
| **Input → output** | artifact → **disposition** (admit · refuse · state · record) | expression/evidence → **new semantic content** |
| **Failure mode if misplaced** | none — this is the core's job | the core becomes a reasoner (§16: *"the kernel does not reason"*) |
| **Test** | could the capability be performed **without knowing what the expression means**? | if it requires *understanding* to perform, it produces |

**The operational discriminator, stated so a test can be written against it:**

> **A capability is a PROTECT capability if and only if its output is determined by the *presence, structure, and declared properties* of what was supplied — never by the *semantic content* of what was supplied.**

Worked contrast on the hardest case, **justification sufficiency** (the boundary act's primary anti-drift watchpoint):

| | Reads | Verdict | Placement |
|---|---|---|---|
| ✅ **PROTECT** | *are premises present? is an inference rule named? is the conclusion the one claimed? are the evidence references resolvable? is the path complete and non-circular?* | admissible / not admissible | **inside** |
| ⛔ **PRODUCE** | *are the premises **true**? does the conclusion **follow**? is this the **best** reading?* | a semantic conclusion | **outside** |

The line is **structural completeness vs semantic validity**. The core checks that a justification *exists and is well-formed*; whether it is *sound* is the reasoning mechanism's claim, carried in the path and preserved, never re-derived.

---

## 2 · Q1 · What exactly enters the KnowledgeCore Admission Boundary?

**Exactly one thing crosses: a candidate submission at the Verification Port.** Its parts, all mechanism-owned until disposed of (Port Contract §5):

| Element | Kind | Law |
|---|---|---|
| **meaning candidate** | proposed intensional content — *never* admitted Meaning | obligation 1 · ⟨C-2⟩ |
| **justification path** | premises · rules · assumptions · inference rule · conclusion | obligation 2 · Article 6.4 |
| **context delimiters** | what the claim is about, its setting — **a constitutive prerequisite** | Article 1.4 · ⟨Z-1⟩ |
| **agency** | epistemic lineage — **a constitutive prerequisite** | Article 10 · ⟨Z-1⟩ |
| **evidence references** | references + acquisition method + reliability conditions — **never content** | ⟨C-3⟩ |
| **declared determination** *or* **declared insufficiency** | exactly one; the mechanism states which it is making | obligation 3 · Port Contract §4 |
| **candidate-side metadata** | interpretation probability · uncertainty · provenance · transformation evidence — **port vocabulary, never a member** | r4-4 |
| **authority reference** | a reference to a recorded human act — never the grant itself | Article 3 |

**What may never enter:** an epistemic state · a verdict · a proposed `KnowledgeId` · a scalar offered as Confidence · evidence content · a selection presented as authority · a normal form presented as identity. Each is a port-obligation violation, refused **at the ACL**, raising **no domain event**.

---

## 3 · Q2 · What exactly leaves it?

| Leaves | To | Nature | Law |
|---|---|---|---|
| **domain events** (the ten) | any subscriber | notification of an admitted transition — never authority | §8 · ⟨A-3⟩ |
| **projections** | Projection context | regenerable, non-authoritative, **no write-back** | Article 5 · INV-KOS-PROJECTION-001 |
| **advisory output** | Decision Boundary | a recommendation — **execution stays outside** | Article 4 · INV-KOS-DECISION-001 |
| **refusals** | the submitting mechanism | *not admissible*, with the reason — **never a truth verdict** | INV-KOS-VERIFICATION-001 |

**Nothing leaves as authority, and nothing written outside returns as knowledge** (D-5). A refusal is the boundary's only "answer", and it is a statement about *admissibility*, never about truth — the canonical-wording rule: *"the aggregate is not a truth oracle."*

---

## 4 · Q3 / Q4 · The nine MUST-EXIST capabilities — inside or outside, and why

Each classified by the §1 discriminator. **All nine were established by F-1…F-5 as already-existing law.**

| # | Capability | Inside? | PROTECT or PRODUCE | Protected invariant |
|---|---|---|---|---|
| 1 | **Single-gate admission** | ✅ inside | PROTECT — disposes; produces nothing | INV-KOS-VERIFICATION-001 |
| 2 | **Contract-conformance** | ⚠️ **split** | **specification** inside (D-2: the core names what may cross) · **enforcement** outside at the ACL | §5.2 · precondition 6 |
| 3 | **Identity assignment** | ✅ inside | PROTECT — *assigns*, never *derives*; the act is authoritative, not semantic | INV-KOS-IDENTITY-001 · ⟨C-1⟩ |
| 4 | **Evidence admission** | ✅ inside | PROTECT — admits **references**; never reads or evaluates evidence content | ⟨C-3⟩ |
| 5 | **Justification preservation + sufficiency** | ✅ inside | PROTECT — **structural completeness only** (§1); soundness is the mechanism's claim | INV-KOS-VERIFICATION-001 |
| 6 | **History recording** | ✅ inside | PROTECT — append-only; forward-only | INV-KOS-HISTORY-001 |
| 7 | **Representation-agnostic intake** | ✅ inside | PROTECT — *indifference* to surface form is a refusal to look at it | Test F · F-3 |
| 8 | **Epistemic-state determination** | ✅ inside | PROTECT — determined by the **disposition**, not by understanding the claim | §9 · INV-KOS-UNKNOWN-001 |
| 9 | **Confidence assignment** | ✅ inside | PROTECT — structured, assigned inside; no mechanism scalar crosses | ⟨R-1⟩ |

**Capability 2 is the only split**, and it is the split the boundary act's critic pass discovered (ATTACK 5): the domain **defines** the contract as published language; the ACL **enforces** it. Keeping enforcement inside would import mechanism responsibility; keeping the specification outside would let mechanisms define what may cross, inverting D-2.

**Capability 7 deserves a note**, because it is the easiest to misread as a capability: *representation-agnostic intake* is not a translation ability. It is the **absence** of one. The core is agnostic because it never inspects surface form — the capability is a prohibition wearing a positive name.

**External (PRODUCE), and never inside:** parsing · tokenization · interpretation · normalization · canonicalization · SNF · the Semantic Compiler · the LLM · reasoning · validation · debate · fallacy detection · candidate generation · interpretation **selection** · scoring · evidence-content custody · the human authority act · the human decision.

---

## 5 · Q5 · Required ports

| Port | Direction | Carries | Contract |
|---|---|---|---|
| **Expression↔Meaning (ACL)** | inbound | expression + context delimiters → **meaning candidate**; conformance enforced here | ⬜ PROPOSED · NON-AUTHORITATIVE |
| **Verification Port** | inbound | **THE SINGLE ADMISSION GATE** — candidate + justification path → disposition | ⬜ PROPOSED · NON-AUTHORITATIVE |
| **Authority reference** | inbound | a reference to a recorded human act; the core references, never holds | law (Article 3) |
| **Projection** | outbound | read-only, regenerable, **no write-back** | law (Article 5) |
| **Decision** | outbound | advisory only | law (Article 4) |

**UQ-6 carried, not resolved:** whether Expression↔Meaning is a distinct port or an upstream *stage* of Verification. The mapping is unaffected — the single-gate property holds either way, because conformance is not admission.

---

## 6 · Q6 · Commands and events, without prematurely implementing them

**Events are law and unchanged: the ten of §8, guarded by ⟨A-3⟩.** **Commands remain DEF-1** — v1.1 §6's altitude note and §20 defer *"commands · methods · domain services · policy evaluation"*, so naming a command surface here would decide DEF-1 by side effect. What can lawfully be stated is each **domain act** with its **precondition** and **postcondition** — the modelling altitude, one level above realization:

| Domain act | Precondition | Postcondition | Event |
|---|---|---|---|
| **admit a candidate** | prerequisites present (⟨Z-1⟩) ∧ justification path preserved | aggregate exists; identity assigned; state `UNKNOWN` | `KnowledgeCreated` |
| **refuse a candidate** | prerequisites present ∧ justification path **absent** | REJECTED record retained; never knowledge | `KnowledgeRejected` |
| **refuse before the domain** | prerequisite **absent** (⟨Z-1⟩) | **nothing** — no aggregate, no identity, no state | **none** |
| **add evidence** | aggregate exists ∧ references resolvable | `EvidenceLinks` extended | `EvidenceAdded` |
| **revise a belief** | new evidence or reasoning changes the justification | state transitioned forward-only; prior → History | `BeliefRevised` |
| **translate meaning** | aggregate exists ∧ translation supplied | meaning preserved; **`KnowledgeId` unchanged** | `MeaningTranslated` |
| **detect contradiction** | two admitted claims incompatible **about the same meaning** (§8, F-CM-1) | both `CONFLICTED`; ConflictRecord created | `ContradictionDetected` |
| **resolve contradiction** | a governed resolution with authority reference | ConflictRecord → RESOLVED, retained | `ContradictionResolved` |
| **supersede** | a successor state admitted | prior → History; forward-only | `KnowledgeSuperseded` |
| **assign authority** | a recorded human act exists | Authority reference updated | `AuthorityAssigned` |
| **inform a decision** | an authorized decision step requests it | recommendation issued; **execution outside** | `DecisionInformed` |

**Note the third row.** *Refuse before the domain* is a domain act with an **empty postcondition and no event** — the ⟨Z-1⟩ ruling made explicit in r5. It is listed because a mapper who sees only ten acts for ten events will re-invent the three-cause gate.

---

## 7 · Q7 · `KnowledgeAggregate` vs `ConflictRecord`

| | **KnowledgeAggregate** | **ConflictRecord** |
|---|---|---|
| **Answers** | *what is justified to believe, by whom, on what evidence, at what time?* | *what is the recorded fact of a disagreement, and how was it disposed of?* |
| **Root identity** | `KnowledgeId` — identity-of-meaning | its own root, referencing conflicting aggregates **by identity** |
| **Owns** | the twelve members | ConflictState (CONFLICTED · RESOLVED) · Resolution + authority reference |
| **Why separate** | — | **two KnowledgeAggregates cannot own each other** (§6.1); ⟨C-4⟩ forbids containment |
| **Consistency** | consistency boundary #1 | consistency boundary #2 — **separate** (K-1 ruling) |
| **Coordination** | cross-aggregate — **DS-CAND-2 → ⬜ DEF-1** | idem |

**The division of labour, stated precisely:** `KnowledgeAggregate` holds *a* justified position; `ConflictRecord` holds the fact that **two positions collided and neither was destroyed**. Its existence is what makes INV-KOS-CONTRADICTION-001 (*challenge never destroys identity*) structural rather than aspirational — without it, resolving a conflict would mean deleting the loser.

**Neither is a candidate store.** A ConflictRecord references admitted aggregates only; it never holds candidates.

---

## 8 · Q8 · How are competing interpretations represented? — **F-CM-1, the finding**

### 8.1 The two models the HPA posed, tested against law

**Model A — external selection.** A mechanism picks one candidate; the core admits it.
*Survives the anti-reasoner constraint* (selection is mechanism work) **and** the authority constraint: the selector produces a *candidate*, never assigns identity (obligation 4); its selection is candidate-side metadata (r4-4), never authority (*canonicalization ≠ authority*); if it cannot select, obligation 3 requires it to **declare insufficiency** → `UNKNOWN` (⟨C-5⟩). **The HPA's stated risk — "who selects, on what evidence, and can the selector become semantic authority?" — is already answered by the port contract.** A selector that asserts rather than abstains is refused at the ACL.

**Model B — preserve competition inside as `CONFLICTED` + `ConflictRecord`.** ⚠️ **This model contains a category error.**

INV-KOS-CONTRADICTION-001 governs **conflicting knowledge** — two claims that cannot both be true *about the same meaning*. Competing *interpretations* are not that. They are rival answers to *which meaning is carried*, which is an **identity** question (Article 1.4, 1.2), not a truth question. Admitting them as `CONFLICTED` would:

1. **collapse ambiguity into contradiction** — treating *"we do not know which meaning"* as *"these claims contradict"*;
2. require the core to judge that two candidates are **about the same thing** in order to call them conflicting — which is a **similarity determination**, forbidden by ⟨C-1⟩ (*similarity never becomes identity*);
3. therefore make the core a **semantic arbiter**, breaching the anti-reasoner constraint established by the same commission.

### 8.2 What the law does support — and the distinction it is missing

**P5 Established item 7 is directly on point:** SNF-C's one measurable advantage was `role_omission_ambiguity` (60/60) — *"recognising that an omitted kāraka is **underdetermined** rather than resolvable."* **P5's own result treats ambiguity as underdetermination, not as competition to be resolved.** The port already has the vocabulary for underdetermination: **declared insufficiency → `UNKNOWN`**.

So the law supports a **third reading**:

| Situation | Kind of question | Lawful representation |
|---|---|---|
| One expression, meaning **underdetermined** | identity — unresolved | mechanism **declares insufficiency** → admitted with state `UNKNOWN`. **Not** a conflict |
| One expression, **several viable meanings**, each independently justified | identity — genuinely plural | each admitted **separately, with its own `KnowledgeId`** — distinct meanings are distinct identities. **Not** a conflict |
| Two admitted claims **incompatible about the same meaning** | truth | `CONFLICTED` + `ConflictRecord` — the law's actual target |

**The distinction this requires — and it is not in the law:**

> **Ambiguity ≠ Contradiction.** *Which meaning is carried* is an identity question; *which claim is true* is a truth question. Underdetermination is not disagreement.

§15's non-collapse list has **eleven** rows (Representation≠Identity · Expression≠Meaning · Similarity≠Identity · Meaning≠Truth · Evidence≠Authority · Revision≠Erasure · Inference≠Observation · Agent≠Truth · Probability≠Truth · Canonicalization≠Authority · Low entropy≠Certainty). **Ambiguity≠Contradiction is not among them**, and `ambiguity` appears in v1.1 only twice — in the §13 LLM row and in OQ-4's corpus requirements — never as a modelled distinction.

**F-CM-1 · HPA RULING REQUIRED.** This act **does not** add the row, **does not** choose Model A or the third reading, and **does not** decide whether plural admission is lawful. It records that Q8 is **unanswerable from existing law** and names exactly what is missing. *(Research posture: inform, never initiate — recorded, not researched.)*

**Why this matters more than a vocabulary gap:** whichever way it is ruled, the ruling determines whether **selection happens outside the boundary** (Model A / third reading) or **inside it** (Model B). That is a boundary-placement consequence, not a naming preference — and an implementation would settle it silently by writing code.

---

## 9 · Q9 · What the Kernel explicitly refuses to do

The **anti-capability register** — each with the law that forbids it, and the fitness test that will detect it (§10).

| # | The Kernel MUST NOT | Law | Test |
|---|---|---|---|
| **A-1** | parse, tokenize or interpret natural language | Test F · F-3 · §17 (language engine) | **T-1** |
| **A-2** | infer or discover semantic meaning | §16 (*cannot generate a conclusion*) | **T-2** |
| **A-3** | generate a candidate | obligation 1 · D-2 | **T-3** |
| **A-4** | **select between competing interpretations** | §16 · F-CM-1 pending | **T-4** |
| **A-5** | call an LLM, or any engine, to resolve meaning | §13 · D-1 (*the core depends on nothing*) | **T-5** |
| **A-6** | derive a conclusion from raw evidence | ⟨C-3⟩ · Article 6.4 | **T-6** |
| **A-7** | derive identity from similarity or canonical form | ⟨C-1⟩ · INV-KOS-IDENTITY-001 | **T-7** |
| **A-8** | accept a mechanism scalar as Confidence | ⟨R-1⟩ | **T-8** |
| **A-9** | accept a mechanism-produced EpistemicState | §6 (*no mechanism may ever produce this member*) | **T-9** |
| **A-10** | hold evidence content | ⟨C-3⟩ · Ch IV database refusal | **T-10** |
| **A-11** | hold authority, or self-authorize | Article 3 · INV-KOS-AUTHORITY-001 | **T-11** |
| **A-12** | execute an action | Article 4 · INV-KOS-DECISION-001 | **T-12** |
| **A-13** | accept write-back from a projection | Article 5 · D-5 | **T-13** |
| **A-14** | open a second admission path | INV-KOS-VERIFICATION-001 | **T-14** |
| **A-15** | store non-admitted candidates | F-4 · D-5 | **T-15** |
| **A-16** | overwrite or delete a prior state | INV-KOS-HISTORY-001 | **T-16** |
| **A-17** | admit a prerequisite-absent candidate in **any** state | ⟨Z-1⟩ | **T-17** |
| **A-18** | raise a domain event for a mechanism operation | ⟨A-3⟩ | **T-18** |
| **A-19** | introduce an eighth epistemic state | §9 · OBS-2 | **T-19** |
| **A-20** | deduplicate by meaning | ⟨C-1⟩ — see **F-CM-2** | **T-20** |

---

## 10 · Q10 · Turning every prohibition into an executable architecture test

### 10.1 The method

A prohibition is testable when it is restated as a **property of an observable trace**, not as a property of a name. Three test shapes suffice:

| Shape | Form | Detects |
|---|---|---|
| **DEPENDENCY** | the core's dependency closure contains no element of class *X* | structural imports of mechanism capability |
| **TRACE** | for input *I*, the emitted event/state sequence is exactly *S* | behavioural drift — the important class |
| **NEGATIVE-INPUT** | for malformed/hostile input *I*, the boundary produces *refusal* and **no state** | silent acceptance |

**Why TRACE is the load-bearing shape:** A-2 and A-4 cannot be caught structurally. A "sufficiency evaluator" that quietly resolves meaning imports nothing and names nothing suspicious — it is detectable only because **its output varies with semantic content that the boundary is not permitted to read**. That yields the decisive test:

> **T-2 / T-4 · SEMANTIC-INVARIANCE.** Submit two candidates that are **identical in structure, declared properties and prerequisites** but **different in semantic content**. The boundary's disposition — admit/refuse, assigned state, recorded members — **must be identical**. Any divergence proves the boundary read meaning.

This is the strongest fitness constraint the mapping produces, and it is a *property*, not a class name — it survives any realization DEF-1 later chooses.

### 10.2 The test specifications

| Test | Shape | Specification (all at boundary altitude — no realization assumed) |
|---|---|---|
| **T-1** | DEPENDENCY | the core's closure contains no parser, tokenizer, grammar, lexicon or language model |
| **T-2** | TRACE | **semantic-invariance** (§10.1) — disposition identical for structurally identical, semantically different candidates |
| **T-3** | TRACE | no emitted artifact is a candidate; the boundary never appears as a submitter at its own port |
| **T-4** | TRACE | given *n* viable candidates, the boundary never emits exactly one admission **plus** *n−1* silent discards. Its disposition of each is independent of the others |
| **T-5** | DEPENDENCY | no outbound call from the core to any engine, model or service (D-1: depends on **nothing**) |
| **T-6** | TRACE | with evidence references unresolvable, no state is derived from evidence — no `EvidenceAdded`, no state change |
| **T-7** | NEGATIVE-INPUT | two candidates with identical canonical form / high similarity receive **distinct** `KnowledgeId`s unless identity was independently assigned |
| **T-8** | NEGATIVE-INPUT | a candidate carrying a numeric confidence is refused at the ACL; no `Confidence` member derives from it |
| **T-9** | NEGATIVE-INPUT | a candidate carrying an `EpistemicState` is refused; the admitted state is always domain-determined |
| **T-10** | DEPENDENCY | no member holds evidence payload — references, method, reliability conditions only |
| **T-11** | NEGATIVE-INPUT | a candidate asserting its own authority is refused; `Authority` is only ever a reference to a recorded act |
| **T-12** | TRACE | no emitted artifact is an instruction; `DecisionInformed` carries a recommendation only |
| **T-13** | NEGATIVE-INPUT | a write originating from a projection is refused; no state changes |
| **T-14** | TRACE | **every** state-creating trace passes the Verification Port. Count of admission paths = 1 |
| **T-15** | TRACE | after a refusal, no domain-side record of the candidate payload exists |
| **T-16** | TRACE | for every transition, the prior state remains retrievable; no member is modified in place |
| **T-17** | NEGATIVE-INPUT | **⟨Z-1⟩** — a candidate lacking agency or context yields **no aggregate, no `KnowledgeId`, no state and no event**. Specifically: **no `KnowledgeRejected`** |
| **T-18** | TRACE | a parse, normalization, canonical-form computation, database write, LLM response or benchmark run emits **no** domain event |
| **T-19** | TRACE | the state vocabulary observed across all traces has cardinality **7** |
| **T-20** | TRACE | two admissions of the same meaning produce **two** identities; the boundary never merges them (**F-CM-2** — pending a ruling on whether this is intended) |

**T-17 and T-2 are the two that must exist before any admission code is written** — T-17 because it encodes the ⟨Z-1⟩ ruling, T-2 because it is the only test that catches the drift the anti-reasoner constraint exists to prevent.

---

## 11 · ZERO lens, round two

Applied to the whole boundary, over the HPA's seven conditions.

| Condition | Disposition | Class | Verdict |
|---|---|---|---|
| **every required input missing** | no candidate exists → no event (⟨A-3⟩). A submission missing a **prerequisite** → pre-domain refusal (⟨Z-1⟩); missing a **justification path** → `KnowledgeRejected` | OUT / DS | ✅ ruled |
| **contradictory** | two admitted claims incompatible about the same meaning → `CONFLICTED` + `ConflictRecord`; neither destroyed | DS + REL | ✅ |
| **malformed** | port-obligation violation → refused **at the ACL**; no domain event | OUT | ✅ |
| **duplicated** | ⚠️ the core **may not** detect same-meaning (⟨C-1⟩) → two identities. **F-CM-2** | DS | ⚠️ **unruled** |
| **ambiguous** | ⚠️ underdetermined → declared insufficiency → `UNKNOWN`; plural viable meanings → ⚠️ **F-CM-1** | OUT → DS | ⚠️ **unruled** |
| **unverifiable** | evidence references present but the artifacts cannot be checked — verification of content is external (⟨C-3⟩), so the path is preserved and the state is `QUESTIONABLE` **or** `UNKNOWN`: **UQ-3**, deferred | DS | ⚠️ **deferred** |
| **mutually incompatible** | if about the same meaning → contradiction (above). If incompatible *as interpretations* → **F-CM-1** again | DS | ⚠️ **partly unruled** |

**Round-two result:** **4 of 7 lawful; 3 unresolved — and two of the three are the same finding.** `duplicated` and `ambiguous` both trace to the core's **structural blindness to sameness of meaning**, which ⟨C-1⟩ mandates. That blindness is a *feature* (it is what stops similarity becoming identity) and it is *also* what makes deduplication and ambiguity-resolution impossible inside the boundary. **Both must therefore be handled outside it, or ruled.**

**Deliberately not resolved by inference:** **AH-5** (*asserted vs possible — category boundary or degree?*) is adjacent to the ambiguity question and remains **DEFERRED** by the HPA's own ruling. Nothing in §8 or §11 resolves it, and the modality question is untouched. **F-CM-5.**

---

## 12 · Findings requiring an HPA ruling

| # | Finding | Class | Why it cannot be settled here |
|---|---|---|---|
| **F-CM-1** | **Ambiguity ≠ Contradiction** is absent from §15's non-collapse list, and Q8 needs it. Model B collapses an identity question into a truth question and requires a forbidden similarity determination | **new non-collapse distinction** — law-adjacent | Adding a §15 row is new law; and the ruling decides whether selection happens **outside** or **inside** the boundary |
| **F-CM-2** | **Identity-of-meaning is not enforceably unique** — ⟨C-1⟩ forbids the core from detecting same-meaning, so it cannot deduplicate; two admissions of one meaning yield two identities | consequence of law, **intent unruled** | Whether this is intended, and whether the assigner may consult existing knowledge without *deriving* identity, is an identity-model ruling |
| **F-CM-3** | **Capability 2 is a split capability** — conformance *specification* inside, *enforcement* outside. F-1 names it as one domain responsibility | rendering of F-1 | Confirming the split touches the F-1…F-5 record, which is ⬜ OPEN before the HPA |
| **F-CM-4** | **T-2 / T-17 are preconditions of implementation**, not deliverables of it | governance sequencing | Requires the HPA to bind them to the implementation decision |
| **F-CM-5** | **AH-5 remains deferred and was not resolved by inference** — recorded so the next act cannot mistake §8 for a modality ruling | disclosure | AH-5's deferral is the HPA's |

**Carried, unchanged:** UQ-3 · UQ-4 · UQ-5 · UQ-6 · DEF-1 (DS-CAND-1/2 remain loci) · DEF-4 · DEF-5 · OQ-1 · OQ-4 **UNAUTHORIZED** · F-1…F-5 amendments · AH-4 recording · Port Contract ratification.

---

## 13 · Completion report

```yaml
act: KERNEL-CAPABILITY-MAPPING
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
governing_distinction: "PROTECT/VALIDATE vs PRODUCE — a capability is PROTECT iff its output is determined by the presence, structure and declared properties of what was supplied, never by its semantic content (§1)"
questions_answered:
  q1_what_enters:    "one candidate submission at the Verification Port — 8 named elements, all mechanism-owned until disposed (§2)"
  q2_what_leaves:    "domain events · projections · advisory output · refusals. Nothing as authority; nothing written outside returns as knowledge (§3)"
  q3_q4_capabilities:"8 of 9 wholly inside as PROTECT; capability 2 SPLIT (specification inside, enforcement at the ACL). All PRODUCE work external (§4)"
  q5_ports:          "5 — Expression↔Meaning ACL · Verification Port (sole gate) · Authority reference · Projection · Decision (§5)"
  q6_commands_events:"events = the ten, unchanged. COMMANDS REMAIN DEF-1; 11 domain acts stated with pre/postconditions at modelling altitude — including 'refuse before the domain', which has an EMPTY postcondition and NO event (§6)"
  q7_aggregates:     "KnowledgeAggregate holds a justified position; ConflictRecord holds the fact that two positions collided and neither was destroyed. Separate consistency boundaries; coordination is DS-CAND-2 -> DEF-1 (§7)"
  q8_competing:      "UNANSWERABLE FROM EXISTING LAW — F-CM-1. Model B collapses ambiguity into contradiction and needs a forbidden similarity determination; the law lacks Ambiguity != Contradiction. P5 Established item 7 (underdetermined, not resolvable) supports a third reading. HPA RULING REQUIRED (§8)"
  q9_refusals:       "20 anti-capabilities registered, each with its law and its test (§9)"
  q10_tests:         "3 test shapes (DEPENDENCY · TRACE · NEGATIVE-INPUT); 20 specifications. T-2/T-4 SEMANTIC-INVARIANCE is the decisive one: identical structure + different meaning MUST yield identical disposition (§10)"
zero_lens_round_2:
  conditions_tested: "missing · contradictory · malformed · duplicated · ambiguous · unverifiable · mutually incompatible"
  result:            "4 of 7 lawful; 3 unresolved, two of which trace to ONE cause — the core's mandated structural blindness to sameness of meaning (C-1). That blindness is a feature and is also why dedup and ambiguity-resolution cannot live inside the boundary"
findings_requiring_ruling: "F-CM-1 (Ambiguity != Contradiction; decides whether selection is inside or outside) · F-CM-2 (identity-of-meaning not enforceably unique) · F-CM-3 (capability 2 split) · F-CM-4 (T-2/T-17 precede implementation) · F-CM-5 (AH-5 NOT resolved by inference)"
next_actor: HUMAN PRINCIPAL ARCHITECT
next_act: "independent DDD critique of this mapping — then fitness tests, then the HPA ruling, then the implementation decision. Each a separate governed act."
```

**PROPOSED · NON-AUTHORITATIVE · HPA DECISION REQUIRED.** This act supplies evidence and a recommendation; it accepts nothing and adopts nothing (R-34). **No independent critique was performed — the commission places it as the next separate act, and this actor produced the mapping.** **The Kernel is not built.**

---

## Traceability

- **Commission:** HPA, 2026-08-23 — Kernel Capability Mapping; ten questions; the ZERO lens re-applied over seven conditions; the mandatory PROTECT/VALIDATE-vs-PRODUCE distinction; the MAY / MUST-NOT list carried from §10.2 of the ruling record.
- **Inputs:** v1.1 **r5** (`20260822-1402`, 705 ln — §4 · §5.2/5.3 · §6 + altitude note · §7 + enforcement loci · §8 + ⟨A-3⟩ · §9 + ⟨Z-1⟩ · §13 · §14 · §15 non-collapse list · §16 · §17 · §18 · §19 · §20) · boundary + ruling + r5 record (`20260823-1306`, 577 ln — §10 ruling, §11 correction) · Port Contract r4 (`20260822-1559`) · F-1…F-5 (`20260823-1051`) · P5 acceptance (`20260822-2327`, **Established item 7**) · AH-1…AH-5 confirmation (`20260822-2346`).
- **Chain position:** boundary ACCEPTED → ZERO-DEFECT-1/2 RULED → K-1 RULED → §9 corrected (r5) → **this mapping** → independent DDD critique → fitness tests → HPA ruling → implementation decision.
- **Status:** 📋 **DELIVERED · PROPOSED · NON-AUTHORITATIVE · decision ⬜ OPEN (HPA).** Register **25+4** · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · states **7→7** · invariants **11→11** · articles **11→11** · Constitution **FROZEN** · research **CLOSED** · OQ-4 **UNAUTHORIZED** · AH-5 **still deferred** · **no capability implemented · no Kernel authorization · the Kernel still waits.**
