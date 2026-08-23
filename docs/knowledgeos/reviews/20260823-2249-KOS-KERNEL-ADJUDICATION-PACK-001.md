# KOS-KERNEL-ADJUDICATION-PACK-001 — decision support for the open Kernel questions

> **Role:** the **adjudication preparation pack**. It assembles, for each open question, the authoritative law, the existing governed architecture, the brainstorming evidence, the critique finding, the DDD interpretation, and the **class of decision required** — so that the HPA/ARB can adjudicate.
> **Commission:** HPA, 2026-08-23 — *"build the adjudication pack from the critique questions, using the brainstorming report as historical/adversarial evidence and v1.1 + HPA rulings as authoritative law. Then stop and adjudicate."* With the binding constraint: **the pack recommends nothing.**
> **⛔ THIS PACK MAKES NO RECOMMENDATION AND NO DECISION.** It contains no preferred option, no proposed resolution, and no architecture. Where the corpus or the critique offered a candidate reading, it is **reported as evidence**, never endorsed. Every row ends in a decision that belongs to the HPA/ARB.
> **Status:** 📋 **DECISION SUPPORT · PROPOSED · NON-AUTHORITATIVE.** Register **25+4** · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · states **7→7** · invariants **11→11** · Constitution **FROZEN** · research **CLOSED** · SNF **CLOSED** · OQ-4 **UNAUTHORIZED** · AH-5 **deferred** · **implementation ungated · the Kernel is not built.**
> **Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos --maturity=research` → `docs/knowledgeos` (exit 0).

---

## 0 · How to read this pack

### 0.1 Scope note — the pack covers 21 items, not 13

Two lists of "13 questions" are in circulation and they are **not the same list**:

| | Items |
|---|---|
| **Critique §20** (13) | C-1 · C-2 · C-3 · C-6 · C-7 · C-11 · C-14 · C-15 · C-17 · C-18 · F-CM-1 · F-CM-2 · C-19 |
| **HPA list** (13) | C-1 · C-2 · C-3 · **C-4** · **C-8** · **C-10** · C-11 · **C-12** · C-15 · C-17 · C-18 · F-CM-1 · F-CM-2 |
| **Difference** | HPA elevates **C-4 · C-8 · C-10 · C-12** (recorded defects) · critique includes **C-6 · C-7 · C-14 · C-19** not on the HPA list |

This pack covers the **union (17)** plus the three recorded defects not on either list (**C-5 · C-9 · C-13**) and the **CC-1** scope item = **21 rows**. Nothing is dropped for being on only one list.

### 0.2 The four threads — deliberately not flattened

Per the HPA's direction that a checklist must not flatten different kinds of question into one bucket:

```
                      OPEN WORK
                          │
   ┌──────────────┬───────┴───────┬──────────────────┐
   │              │               │                  │
COMPLETENESS    SCOPE      CONSTITUTIONAL       CONSISTENCY
   │              │          ADEQUACY               │
12 items      3 items        5 items             1 item
   │              │               │                  │
description   what belongs   does law express     does the boundary
of an         inside / does   what the domain     contradict established
ACCEPTED      the context     needs?              architecture?
boundary      even exist?     ⚠️ MUST NOT be
                              "fixed" by putting
                              it in the Kernel
```

### 0.3 The five decision classes

| Class | Meaning | Who |
|---|---|---|
| **MAPPING CORRECTION** | the boundary stands; its *description* is amended | Architecture, on an HPA ruling |
| **BOUNDARY DECISION** | what is inside/outside changes, or a context is added | **HPA** |
| **CONSTITUTIONAL AMENDMENT** | law does not express it; adding it extends the Constitution | **HPA under V.3** |
| **UNRESOLVED ARCHITECTURAL DECISION** | genuinely open; may lawfully remain deferred | **HPA** |
| **REJECTION** | the finding is refused; nothing changes | **HPA** |

### 0.4 Brainstorming evidence — classification and standing

The corpus is **NON-AUTHORITATIVE**. Each row's brainstorming evidence carries one of four labels:

- **ANTICIPATED** — the corpus reached the same finding before the governed chain did
- **FALSIFICATION ARGUMENT** — the corpus supplies an argument usable *against* a position
- **CONFLICTS WITH LAW** — the corpus wanted something law forbids; **law wins, and the corpus is evidence of the temptation, not of the answer**
- **SILENT** — the corpus does not address it; the finding is genuinely new

**Explicitly not resurrected by this pack** (the reading report records these as *not law*): K1–K8 · `KnowledgeClaim` as root · `AdmissionContract` · the candidate epistemic states · the candidate bounded contexts · the candidate domain events · the topological lens as a register family · **AH-6**. Their *reasoning* is reused; their *designs* are not.

---

## THREAD C · CONSTITUTIONAL ADEQUACY — 5 items
### *does current law express what the domain needs?*
> ⚠️ **These five must not be resolved by putting anything into the Kernel.** If law is silent, the remedy is an amendment act or a ruling that the silence is deliberate — never a capability.

---

### F-CM-1 · Is *Ambiguity ≠ Contradiction* a required distinction, and does interpretation-selection sit inside or outside the boundary?

**Authoritative law.** §15 lists **eleven** non-collapse distinctions; *Ambiguity ≠ Contradiction* is **not** among them. The token `ambiguity` occurs in v1.1 exactly **twice** — the §13 LLM row and OQ-4's corpus requirements — never as a modelled distinction. INV-KOS-CONTRADICTION-001 governs *conflicting knowledge* — two claims that cannot both be true. ⟨C-1⟩ forbids similarity becoming identity. Obligation 3 + ⟨C-5⟩ give underdetermination a home: **declared insufficiency → `UNKNOWN`**.

**Existing governed architecture.** The capability mapping recorded it as unanswerable from existing law and **refused** to resolve it. The critique **confirmed** the absence by direct search and added that the intuitive Model B (`CONFLICTED` + `ConflictRecord`) requires the core to judge two candidates *about the same thing* — a similarity determination ⟨C-1⟩ forbids — thereby making the core a semantic arbiter.

**Brainstorming evidence — ANTICIPATED, most heavily of all.** Five independent artifacts reach for an explicit `AMBIGUOUS` state or verdict: `20260823-103255` (*"Ambiguity → explicit state | **Very high**"*; `{MATCH|MISMATCH|AMBIGUOUS|UNRESOLVED}`) · `104148` (`RESOLVED/AMBIGUOUS/MISMATCH/UNRESOLVED` as kernel output) · `104251` (`AMBIGUOUS` as rule result *and* kernel state) · `111647` (ambiguity among proposed epistemic states) · `123630` (the ZERO non-collapse pairs). **`105200` §10 supplies the layered alternative** — *"Interpretation: UNRESOLVED, AMBIGUOUS / Governance: NOT_AUTHORIZED… / Domain: admissible/rejected — one generic status enum would probably be a mistake."* **P5 Established item 7** is the affirmative evidence: SNF-C's one measurable advantage was recognising an omitted kāraka as **underdetermined rather than resolvable**.

**Independent critique.** F-CM-1 **CONFIRMED** — not derivable from existing law.

**DDD interpretation.** *Which meaning is carried* is an **identity** question (Article 1.2, 1.4). *Which claim is true about one meaning* is a **truth** question (Articles 6, 9). Collapsing the first into the second moves a determination the core is forbidden to make (⟨C-1⟩) into the core.

**Decision class.** **CONSTITUTIONAL AMENDMENT** (to add a §15 row) **and/or BOUNDARY DECISION** (whether selection sits inside or outside). These are separable: the distinction could be recorded without settling placement.

**If unruled, an implementation decides silently:** whether the boundary compares candidates for sameness. Once written, that comparison is a semantic arbiter, and no test in the current suite detects it except T-2.

---

### C-15 · Does retraction/withdrawal require a lawful representation?

**Authoritative law.** The **ten** domain events (§8) contain no withdrawal. INV-KOS-HISTORY-001 forbids deletion. `KnowledgeSuperseded` requires a **successor** claim, which a retraction does not have. ⟨A-3⟩ guards against adding events. §9's seven states contain no `WITHDRAWN`.

**Existing governed architecture.** The mapping's eleven domain acts mirror the ten events faithfully and therefore inherit the silence. The critique classified this as a **gap in law, not in the mapping**.

**Brainstorming evidence — ANTICIPATED, in four independent artifacts.** `111647` §5 lists `ADMITTED → WITHDRAWN` as a lifecycle transition and §12 includes WITHDRAWN in the lifecycle diagram · `113410` investigates WITHDRAWN as state/relationship/event/derived-condition and returns **UNRESOLVED** · `123630` lists **`WITHDRAWN ≠ FALSE`** among the six ZERO non-collapse pairs · `123619` scenario 12 is *"Claim withdrawn."*

> **This directly answers the HPA's stated concern.** C-15 is **not** a new discovery. The brainstorming identified retraction as a lifecycle concept the model lacks, and the governed chain independently rediscovered it. Two independent routes to the same gap.

**DDD interpretation.** Retraction is a *speech act of the claimant*, not a change in the evidence. Nothing in the current lifecycle expresses "the author no longer asserts this" as distinct from "it is false", "it is superseded" or "it was rejected".

**Decision class.** **CONSTITUTIONAL AMENDMENT** (a state, an event, or a ruling that retraction is deliberately inexpressible) — or **REJECTION** with the reason recorded.

**If unruled, an implementation decides silently:** which existing state absorbs retraction. The likely default is `REJECTED`, which collapses *withdrawn* into *failed justification*.

---

### C-17 · What state does an internally impossible claim bear?

**Authoritative law.** `CONFLICTED` is a relation **between** claims (INV-KOS-CONTRADICTION-001 + ConflictRecord references two aggregates). `FALSE` requires *grounds that it is not so*. `REJECTED` requires an absent justification path — and a self-contradictory claim may arrive with a complete, well-formed path. A-2/§16 forbid the boundary from reading meaning, which is what detecting internal contradiction requires.

**Existing governed architecture.** The critique's independent ZERO pass (15 conditions) found *impossible* to be the only condition with **no lawful state**.

**Brainstorming evidence — SILENT.** The corpus addresses contradiction *between* claims extensively and lists *"Contradictory determination"* as scenario 20 in `123619` — but that is the boundary producing inconsistent verdicts, **not a single internally-inconsistent claim**. No artifact addresses P ∧ ¬P within one claim.

> **C-17 appears genuinely new.** It is the one item in this pack the brainstorming did not anticipate.

**DDD interpretation.** The distinction is *inter-claim* versus *intra-claim* inconsistency. The model expresses the first and has no vocabulary for the second — and cannot acquire one without either reading meaning (forbidden) or receiving the determination as port vocabulary.

**Decision class.** **CONSTITUTIONAL AMENDMENT** (a state or a port-vocabulary route) — or **REJECTION** on the ground that internal consistency is a mechanism's obligation before submission, which would be a defensible ruling and should be recorded as one if taken.

**If unruled, an implementation decides silently:** nothing — it admits the claim and no state is wrong, so the defect is invisible in operation. That is what makes it dangerous.

---

### C-18 · Must an admitted state bind the constitutional version that admitted it?

**Authoritative law.** §16: *"nothing moves between altitudes except by constitutional amendment"* — so amendment is possible. **r5 has just demonstrated that v1.1 revises.** No member, event or invariant binds an admitted state to a law version. INV-KOS-HISTORY-001 preserves the transition, not the governing law.

**Existing governed architecture.** The critique recorded it; the ADR notes r5 made it concrete.

**Brainstorming evidence — ANTICIPATED, three times.** `104148` §2: constitutional rules are *"Versioned (they evolve over time, like a real constitution)"* · `110248` §12: *"The constitution is versioned. The Kernel applies the current version. If the constitution changes, the Kernel references the new version"* · `110950` §3 asks the unanswered questions verbatim: *"Who determines the authoritative constitutional version? Who certifies it? How is it identified? Can an arbitrary caller supply rules? Can rules contradict the Kernel's immutable invariants?"* — **`110248` §12 also warns that KnowledgeOS history ≠ Kernel software history, and that they may be linked through provenance/evidence but must not be conflated** (relevant because an EvidenceLink is one candidate home).

**DDD interpretation.** Either the governing law version is part of the admitted state's justification (and therefore domain-owned), or it is infrastructure metadata (and therefore outside). The corpus's own warning is that these are different and must not be conflated.

**Decision class.** **UNRESOLVED ARCHITECTURAL DECISION** — with a possible **CONSTITUTIONAL AMENDMENT** if the binding must be a member. A lawful home may already exist (an `EvidenceLink`, or `History`), which would make it a **MAPPING CORRECTION** instead. **The choice between those three is the decision.**

**If unruled, an implementation decides silently:** nothing is recorded, and after the first invariant amendment prior admissions become unreproducible — which also silently resolves **C-2**.

---

### C-14 · Is `NOT_ASSESSED ≠ LOW_CONFIDENCE` a non-collapse requirement rather than a representation choice?

**Authoritative law.** ⟨C-5⟩ **already forbids** the collapse: a mechanism's inability maps to `UNKNOWN`, *"never to a low-confidence accept."* ⟨R-1⟩ forbids a mechanism scalar becoming Confidence. **What law does not define is the positive representation** of a not-assessed Confidence.

**Existing governed architecture.** The mapping recorded it as **UQ-4** and the HPA **deferred it to Logical Architecture**. The critique argued the classification may be wrong — that this is law-adjacent, in F-CM-1's class.

**Brainstorming evidence — ANTICIPATED.** `123630` §20 lists **`NOT_ASSESSED ≠ LOW_CONFIDENCE`** among the six ZERO non-collapse pairs, beside `UNKNOWN ≠ ABSENT` — which **is** law. `20260822-161933` promotes **abstention quality (`A_abstain`)** and **false collapse (FCR)** to first-class metrics, i.e. treats the collapse as the dangerous failure.

**DDD interpretation.** The *prohibition* is law; the *representation* is not. That is an unusual asymmetry: the model forbids a state it cannot express the absence of.

**Decision class.** **RECLASSIFICATION** — either it stays **UNRESOLVED ARCHITECTURAL DECISION** (Logical Architecture, as ruled) or it becomes a **CONSTITUTIONAL** matter (a §15 row / a defined representation). **This row asks only whether the existing deferral stands.**

**If unruled, an implementation decides silently:** the representation — most likely a nullable scalar, which is precisely the collapse ⟨C-5⟩ forbids.

---

## THREAD B · SCOPE — 3 items
### *what belongs inside the smallest boundary, and does the context even exist?*

---

### C-3 / CC-2 · Who checks that an authority is *adequate in scope* for the act?

**Authoritative law.** INV-KOS-AUTHORITY-001: authority is **assigned, never emergent**; evidence, assessment and source **never self-authorize**; enforcement locus *"the AuthorityGrant boundary; the aggregate references, never holds."* §5.3 declares **six** bounded contexts and **Governance is not one**; `Authority`'s stated purpose is *"record the human act that assigns authority"* and it owns the **grant** only. Article 3.

**Existing governed architecture.** The mapping lists INV-KOS-AUTHORITY-001 as *"preserved inside as a refusal"* and names **one** failure mode: never self-authorize. **No capability checks scope adequacy.** The critique found the responsibility **unhomed**.

**Brainstorming evidence — ANTICIPATED, repeatedly, and it proposes the amendment-class answer.** `110248` §4 defines a **Governance Context** owning *"constitutional rules, policies, role definitions, authority"*, and §14 places *"Command authorization | Governance Context"* in MAY EXIST · §15 lists *"Governance Rule Evaluator (Governance Context) → provides rule evaluation results"* as an external mechanism · `105200` §12 places a **Governance Context** — *"authority, eligibility, policy, evidence admissibility"* — between interpretation and the domain · `110950` §4 warns against making the Kernel the *owner/interpreter* of the Constitution.

**CC-2, from the consistency check:** a Governance context appears in **the corpus, in the critique's C-3, and in the HPA's own architectural view** — **three independent models. The law contains none.**

**DDD interpretation.** Two distinct acts are being named by one invariant: *refusing self-authorization* (a refusal the core can perform from structure alone) and *evaluating whether a referenced authority covers this act* (which requires policy the core does not hold). The second has no owner in the six contexts.

**Decision class.** **BOUNDARY DECISION** — either the boundary gains a tenth capability, or a context must own it, which is **contexts 6→7** and therefore also **CONSTITUTIONAL**. A third option exists and must be stated: **REJECTION**, ruling that scope adequacy is the submitting authority's obligation and not the domain's.

**If unruled, an implementation decides silently:** any well-formed authority reference is treated as sufficient — INV-KOS-AUTHORITY-001 enforced at half strength, undetectably.

---

### Wisdom · Does Wisdom enter KnowledgeCore, and does that require a V.3 act?

**Authoritative law.** §4.1 — *Wisdom Formation*: *"no register row; the character ends at **trustworthy truth discovery**; adopting it would extend the frozen Constitution (V.3)"* → **REJECTED at the boundary**, amendment-gated. §17 lists it among the Chapter IV refusals against **V.3**. §8 — *"**WisdomDerived** — recorded for traceability, **NOT admitted**… a future Wisdom context would require a constitutional amendment, and **V.3 forbids adding concepts**."*

**Existing governed architecture.** ADR-KOS-SCOPE-001 §16 states the position with sources, records that a question of the form *"the smallest boundary of knowledge **and wisdom**"* cannot be answered by a boundary act, and **makes no amendment and no recommendation**.

**Brainstorming evidence — extensive, and filed at mechanism altitude by the corpus itself.** The first intake batch carries the Gaṇeśa/Wisdom character work and `WISDOM-MECH-001..006`; the **26-lens system** (`205735`) files the wisdom family in **tier 3, mechanism candidates**, not in the adjudication tier. `112855` develops the Gaṇeśa threshold lens and `123619` §9 uses it for the boundary question. **The corpus never claims Wisdom is core.**

**DDD interpretation.** Wisdom as a *mechanism* behind a port requires nothing from law. Wisdom as a *core domain concept* adds a concept, which V.3 forbids.

**Decision class.** **CONSTITUTIONAL AMENDMENT (V.3)** if Wisdom is to become core — a **separate act with its own commission**. Otherwise **REJECTION / status quo**, with wisdom remaining research, a lens, or a mechanism candidate outside the boundary.

**If unruled:** nothing happens — the status quo already excludes it. **This row is the only one in the pack where inaction is safe.**

---

### CC-1 · What is the disposition of the historical stack diagram?

**Authoritative law.** §16's KERNEL altitude members: *"the eleven invariants · KnowledgeAggregate · ConflictRecord · **the three small supporting aggregates**."* §6.2 places `AuthorityGrant` in **Authority**, `DerivedView` in **Projection**, `DecisionRecord` in **Decision Boundary**.

**Existing governed architecture.** K-1 ruled that `KERNEL` denotes the §16 altitude and this chain's structure is the **KnowledgeCore Admission Boundary**. The consistency check (**CC-1**) found the historical diagram `AIP → EKS/PKS → KnowledgeOS → KnowledgeCore → Kernel` draws `Kernel ⊂ KnowledgeCore` — **which does not hold under the altitude reading, since the altitude spans four contexts.** The diagram draws containment true of the admission boundary while using the name belonging to the altitude.

**Brainstorming evidence — FALSIFICATION ARGUMENT.** `110950` §1 asks whether *"Kernel"* is a domain concept or *"our architectural name for the minimal implementation of a bounded context's invariants"*, and warns the term conflates **bounded context, aggregate boundary and domain capability**. `123619` §9 asks *"Is the Kernel an aggregate at all?"* The corpus identified the overload before K-1 named it.

**DDD interpretation.** A change-governance grouping and a consistency/admission grouping are different notions with different extents. One name for both is the defect.

**Decision class.** **MAPPING CORRECTION / documentation disposition** — supersede the diagram, annotate it, or retain it explicitly as historical. The HPA has already stated the principle: *"if the old diagram is genuinely obsolete, we should eventually record that explicitly rather than allowing two architectural pictures to coexist indefinitely."*

**If unruled:** the diagram remains citable as current nesting, and the K-1 distinction erodes by reuse.

---

## THREAD A · COMPLETENESS — 12 items
### *does the description of the accepted boundary contain everything necessary?*

---

### C-1 · Is the nine-item capability list to be re-typed?

**Authoritative law.** Law types **invariants** (§7), **members** (§6), **events** (§8) and **contexts** (§5.3). **Law does not type "capabilities" at all** — the nine come from F-1…F-5, whose amendment candidates are ⬜ **OPEN**.

**Existing governed architecture.** The mapping inherited the nine and classified them PROTECT/PRODUCE. The critique tested each and found **6 capabilities + 1 invariant + 1 anti-capability + 1 split**, with two of the six compound: *single admission gate* is an invariant rendered as a boundary property; *representation-agnostic intake* is an anti-capability. Also confirmed for the HPA's other four: *no semantic reasoning* → anti-capability · *no evidence custody* → anti-capability · *no authority ownership* → invariant.

**Brainstorming evidence — ANTICIPATED, with a different count.** `110248` §14 produced its **own** Domain Capability Map with a **six-item** MUST-EXIST list — identity preservation · state-transition admissibility · domain history preservation · provenance preservation · contradiction detection · evidence-reference preservation. An independent analysis reached **six** where F-1…F-5 reached **nine**. `110950` §2 states the test the mapping did not run: *"which invariant cannot be protected if this concept is outside the aggregate?"*

**DDD interpretation.** A capability is an act the domain performs; an invariant is a constraint on it; an anti-capability is a prohibition. Naming all three "capability" makes the list uncountable and hides that two entries are prohibitions.

**Decision class.** **MAPPING CORRECTION** — but it **touches the ⬜ OPEN F-1…F-5 record**, so the HPA must decide whether the correction happens in a revised mapping or by disposing of the F-record.

**If unruled, an implementation decides silently:** it builds nine things, two of which are prohibitions and cannot be built — most likely by inventing a component for *representation-agnostic intake*, which is the opposite of what the entry means.

---

### C-2 · Is determinism a boundary property, a fitness constraint, or DEF-1 realization?

**Authoritative law.** **Not among the eleven.** §9 (*"no implicit transition"*) and INV-KOS-HISTORY-001 imply reproducibility without stating it. §19's gates do not test it.

**Existing governed architecture.** Absent from the mapping's capabilities, anti-capabilities and tests — **yet T-2 SEMANTIC-INVARIANCE presupposes it**: "identical structure, different meaning → identical disposition" is only meaningful if identical input yields identical disposition.

**Brainstorming evidence — ANTICIPATED, four times, as a first-class Kernel property.** `103606` **K8 "Deterministic State Transition"** — *"given the same admissible input and the same prior state, the Kernel should produce the same resulting state"* — and explicitly ties it to *replayability · auditability · deterministic testing · reproducibility · governance confidence*, **and to the existing EKS/PKS deterministic-assurance work** · `104148` §2 *"Applied deterministically (same input → same admissibility decision)"* · `110248` §17 *"It is deterministic"* · `205735` lists a **deterministic-assurance lens** in the lens stack.

**DDD interpretation.** Determinism is a property of the boundary's *behaviour*, not a responsibility it *owns*. That makes it a candidate invariant or fitness constraint rather than a capability — which is exactly the classification question.

**Decision class.** **UNRESOLVED ARCHITECTURAL DECISION** with three named options: a **boundary property** (possibly **CONSTITUTIONAL**, since it would be a new invariant), a **fitness constraint** (MAPPING CORRECTION, testable), or **DEF-1 realization** (deferred).

**If unruled, an implementation decides silently:** T-2 becomes unrunnable or flaky, and the anti-reasoner constraint loses its only detector.

---

### C-4 · How does a submission target an existing `KnowledgeId` for a revision?

**Authoritative law.** Six of the ten events operate on an **existing** aggregate (`EvidenceAdded` *"EvidenceLinks extended"* · `BeliefRevised` *"prior state → History"* · `MeaningTranslated` *"KnowledgeId unchanged"* · `ContradictionDetected/Resolved` · `KnowledgeSuperseded`). **Obligation 4** forbids a mechanism proposing or deriving a `KnowledgeId`. **⟨C-1⟩** forbids the core matching by meaning. **Nothing states how the target is identified.**

**Existing governed architecture.** The mapping's Q1 lists eight elements entering the port; **none is a target identity, and the current state is not listed either.** The critique classified this as the one **outright falsification**: Q1 answers only the creation case.

**Brainstorming evidence — ANTICIPATED explicitly.** `110248` §15 *"Information That Must Cross Into the Kernel"* lists — verbatim — *"**Current Knowledge State** (the state to be transitioned)"* alongside the structured command, rules and evidence references. **The corpus listed the input the mapping dropped.** `111647` §5 enumerates twelve transitions on existing claims. `110248` §6 has the Kernel *"receive an Authorized Command"* — i.e. a command that already names its target.

**DDD interpretation.** The horn is real: a mechanism may not supply an identity (obligation 4), and the core may not resolve one by meaning (⟨C-1⟩). Either obligation 4 means *"never propose a **new** identity"* (a narrower reading than its text), or a third route exists that nothing has named.

**Decision class.** **MAPPING CORRECTION** if obligation 4's scope is clarified as new-identity-only; **BOUNDARY DECISION** if a target-reference element must be added to what crosses the port; possibly **Port Contract amendment** (⬜ PROPOSED, unratified).

**If unruled, an implementation decides silently:** it lets the mechanism name the `KnowledgeId`, quietly narrowing obligation 4 — the single most consequential silent decision available in this pack.

---

### C-11 · From what lawful input may `Confidence` be computed?

**Authoritative law.** ⟨R-1⟩ — Confidence is **structured** and assigned **inside**; a mechanism-supplied score *"must never cross the port and become Confidence."* ⟨C-3⟩ — the core owns evidence **links**, never content. §16 — the kernel *"cannot generate a conclusion."* Obligation 5 — no scalar in place of epistemic structure. **OQ-5 ruled the member stays; derivability is expressed nowhere.**

**Existing governed architecture.** The mapping classifies confidence assignment as PROTECT. The critique found **every candidate input forbidden**: content (⟨C-3⟩), mechanism score (⟨R-1⟩), reasoning (§16), quality (requires content) — leaving only structural facts, so the member is either trivial or disguised assessment.

**Brainstorming evidence — ANTICIPATED, and left open by the corpus too.** `110950` §13 asks it verbatim as an unresolved question: ***"How does the Kernel determine confidence without understanding evidence content?"*** — never answered anywhere in the corpus. `114530` §4 offers a distinction as candidate shape: Confidence is *"an assessment output"*, *"derived from evidence and justification (which may be outside)"*, and **"the Kernel is the assigner, not the evaluator."** `110950` §7 gives three models (intrinsic / assessment / contextual projection) and rates *"confidence is essential"* **🔴 Unproven**. `114358` §7 states *"Confidence is not a number."*

**DDD interpretation.** ⟨R-1⟩ fixes the **locus** of assignment; it does not supply the **function**. Assigner-without-evaluator is coherent only if some lawful input exists, and none has been named.

**Decision class.** **UNRESOLVED ARCHITECTURAL DECISION**, with escalation: a **MAPPING CORRECTION** if lawful structural inputs can be enumerated; **CONSTITUTIONAL** if the answer is that the member is vestigial under ⟨R-1⟩ + ⟨C-3⟩ + §16 together — which touches OQ-5's ruling and would need its own act. **OQ-5 is not reopened by this row:** OQ-5 settled *membership*, this asks *derivability*.

**If unruled, an implementation decides silently:** it computes Confidence from something — and whatever it picks is either trivial or a hidden reasoner.

---

### C-8 · Must a fitness test assert identity continuity across transitions?

**Authoritative law.** **Law already expresses the requirement.** §8: `MeaningTranslated` — *"meaning preserved; **KnowledgeId unchanged**."* INV-KOS-IDENTITY-001: identity *"assigned once, stable through representation, expression, context and projection change."* §4.3: remove identity → claim-store, a Chapter IV refusal.

**Existing governed architecture.** T-7 tests that identity is **not derived**; **no test asserts it persists** across `EvidenceAdded`, `BeliefRevised`, `MeaningTranslated`, `KnowledgeSuperseded`.

**Brainstorming evidence — ANTICIPATED, as the central question of two lenses.** `112855` §6: *"State is Shakti; identity continuity is Shiva"* and *"what must remain invariant while epistemic state changes?"* · `210001` §9: *"Can a knowledge object evolve continuously without losing identity?"* — used explicitly to distinguish **revision from replacement** and **supersession from identity destruction**.

**DDD interpretation.** This is **not** a law gap. Law states it; the test suite omits it.

**Decision class.** **MAPPING CORRECTION** (add the fitness test). The narrowest decision in the pack.

**If unruled, an implementation decides silently:** nothing detects an implementation that reassigns identity on revision — the architecture's most important invariant, untested for persistence.

---

### C-10 · Who verifies that an evidence reference resolves?

**Authoritative law.** ⟨C-3⟩ — the core owns *references + acquisition method + reliability conditions*, **never content**; holding content would drift toward the Chapter IV **database** refusal. **D-1** — the core depends on **nothing**. Article 6 requires the justification **path**.

**Existing governed architecture.** The mapping lists "evidence admission" as a PROTECT capability and T-6 presumes the boundary can observe an **unresolvable** reference. The critique found that verifying resolvability requires reaching outside, which **D-1 forbids** — so the capability reduces to accepting unverified references.

**Brainstorming evidence — ANTICIPATED, with the corpus's answer placing the check outside.** `110248` §9 models Evidence with `admissibility: enum[ADMISSIBLE, INADMISSIBLE, PENDING]` and assigns *"Evidence admissibility checking"* to an **Evidence Context or Governance Context** · §15 lists *"**Evidence Admissibility Checker** (Evidence Context) → provides admissibility status"* as an external mechanism supplying status inward. `123619` §13 adds the many-to-many stress test.

**DDD interpretation.** If an external checker supplies admissibility status, that status is **port vocabulary** (r4-4) and must never become an aggregate member — which is a Port Contract question, and the contract is ⬜ **PROPOSED**.

**Decision class.** **MAPPING CORRECTION** (state that references are accepted as-supplied) or **BOUNDARY DECISION** (admissibility status crosses as port vocabulary) — the latter touching the unratified Port Contract.

**If unruled, an implementation decides silently:** either it calls out (breaking D-1) or it accepts anything (making the capability vacuous).

---

### C-6 · What is the disposition of a stale outward reference?

**Authoritative law.** The boundary holds **three** outward reference kinds: `Authority` (a grant held by another context, Article 3), `EvidenceLinks` (external artifacts, ⟨C-3⟩), `Relations` / `TemporalValidity.superseded-by` (other aggregates, ⟨C-4⟩). **D-1** forbids the core reaching out; **D-5** forbids anything written outside returning as knowledge.

**Existing governed architecture.** The mapping records only the narrowest instance — **UQ-5**, revoked authority — as one open question. The critique generalised it to a **class** and noted the fan-out.

**Brainstorming evidence — ANTICIPATED, with the fan-out.** `111647` — *"evidence invalidated could be an event originating in the Evidence Context that causes a downstream epistemic reassessment"* · `123619` §13 runs the many-to-many test explicitly (`E1 → Claim A, B, C`; *"Can E1 change independently of A, B and C?"*) · `123619` scenario list includes **"Evidence invalidated"** and **"Evidence removed"**.

**DDD interpretation.** A relation modelled as a property of one side cannot notice that its other end moved. The core is *structurally* unable to learn of the change, and D-5 forbids the outside pushing it in — so there is no lawful repair path within the current dependency rules.

**Decision class.** **UNRESOLVED ARCHITECTURAL DECISION** — possible answers: references are **as-of-admission** and staleness is out of scope (MAPPING CORRECTION); or invalidation enters as a **new candidate submission** through the single gate (BOUNDARY DECISION, and consistent with D-5); or it is a **CONSTITUTIONAL** matter about what justification means over time.

**If unruled, an implementation decides silently:** admitted knowledge silently retains justification that no longer exists.

---

### C-7 · What is the cardinality and precedence between `CONFLICTED` and `ConflictRecord`?

**Authoritative law.** §7's enforcement locus for INV-KOS-CONTRADICTION-001 is *"`EpistemicState (CONFLICTED)` **+** `ConflictRecord`"* — **jointly**. §6.1: ConflictRecord *"references the conflicting aggregates by identity"*; ConflictState is `CONFLICTED · RESOLVED`; resolution is forward-only and the record survives. **Neither is designated authoritative if they diverge, and cardinality is unstated.**

**Existing governed architecture.** The boundary act's post-ruling §7 established that atomic consistency between the state and its record is necessary *under the current invariant definition*. The critique added that the relation is **one-to-many against a single-valued member**, so resolving one conflict cannot lawfully clear `CONFLICTED` while others stand.

**Brainstorming evidence — ANTICIPATED, as one of the corpus's central unresolved questions.** `113410` investigates `SUPERSEDED`/`RECONCILED`/`CONTESTED`/`INSUFFICIENT_EVIDENCE` against four hypotheses — **state · relationship · event · derived condition** — and returns **UNRESOLVED** for each · `113645` §7: *"epistemic states are not independent properties of a claim; they are **projections of relationships and assessments**"* · `111647` : `RECONCILED` *"may not be a state of a knowledge claim at all — it may describe a relationship between two previously conflicting claims."* **⚠️ Note also CONFLICTS WITH LAW:** the corpus's *states-are-derived* conclusion is refuted by §9's *"no implicit transition"* and by INV-KOS-PROJECTION-001 (a projection is never authoritative).

**DDD interpretation.** The same fact is recorded twice — once as an intrinsic member, once as a relation. That is lawful only with a stated precedence rule and a stated cardinality rule.

**Decision class.** **MAPPING CORRECTION** (state the rule) or **CONSTITUTIONAL** (if the rule changes what `CONFLICTED` means).

**If unruled, an implementation decides silently:** whether one resolution clears the state — and either choice can leave the two records disagreeing.

---

### C-12 · Must a fitness test assert `Relations` referential integrity?

**Authoritative law.** ⟨C-4⟩ — Relations are *"by KnowledgeId reference only, never containment — so relations cannot create transactional coupling across aggregates."* Article 1.5 — knowledge is a relationship. **Relation targets are internal `KnowledgeId`s**, so unlike evidence this **is** checkable without violating D-1.

**Existing governed architecture.** No test asserts a relation's target exists.

**Brainstorming evidence — ANTICIPATED, as a named failure mode.** `210001` §11 defines **"False connection: the system assumes A and B are related although no valid relation exists"** and **"Broken connection: a relationship required for identity/justification has disappeared"** · `123619` §13 asks whether the *support relation* can change independently of its ends.

**DDD interpretation.** A dangling relation is a *false connection* the boundary can detect and currently does not — the cheapest gap in the pack to close.

**Decision class.** **MAPPING CORRECTION** (add the test).

**If unruled, an implementation decides silently:** relations may point at nothing, and Article 1.5's *knowledge is a relationship* becomes unenforced.

---

### C-16 · Is admission idempotent, and is non-unique identity-of-meaning intended? *(with F-CM-2)*

**Authoritative law.** ⟨C-1⟩ — similarity and canonical-form equality **never** become identity. INV-KOS-IDENTITY-001 — identity is **assigned**. **Nothing requires `KnowledgeId` to be unique per meaning**; uniqueness is per assignment.

**Existing governed architecture.** F-CM-2 established that the inability to deduplicate **violates no invariant** — it is ⟨C-1⟩ working as designed. The critique added the unstated operational consequence: **replay of a submission produces a second identity**; there is no idempotency concept at the gate.

**Brainstorming evidence — ANTICIPATED, and here the corpus CONFLICTS WITH LAW.** `103606` **K1** wants the Kernel to distinguish *"same entity / different entity / unknown identity / candidate identity / identity conflict"* — i.e. it wants **exactly the sameness discrimination ⟨C-1⟩ forbids**. `123619` scenario 18 is *"Duplicate identity attempt"* and scenario 19 *"Replay of admission"*.

> **This is the clearest case in the pack where brainstorming would have led the architecture wrong.** Law wins; the corpus is evidence of the temptation, not of the answer.

**DDD interpretation.** *Sameness of meaning* is a relation the core is forbidden to compute. Deduplication and idempotency both require it. So both must be handled outside the boundary or declared out of scope.

**Decision class.** **UNRESOLVED ARCHITECTURAL DECISION** — is non-uniqueness intended (a **REJECTION** of the finding), and may the assigner consult existing knowledge without *deriving* identity (a **BOUNDARY DECISION**)?

**If unruled, an implementation decides silently:** a retried submission creates a duplicate knowledge object, or the implementer adds deduplication and breaks ⟨C-1⟩.

---

### C-5 · Is supersession a second cross-aggregate coordination locus?

**Authoritative law.** `TemporalValidity` holds *valid-from · valid-until · **superseded-by***; ⟨C-4⟩ forbids containment; `KnowledgeSuperseded` is forward-only with the prior state retained. **The supersession invariant spans a pair of aggregates**, exactly as contradiction does.

**Existing governed architecture.** The mapping records **DS-CAND-2** for cross-aggregate *contradiction* coordination and **no equivalent locus for supersession**.

**Brainstorming evidence — ANTICIPATED.** `111647` — *"`SUPERSEDED → ...` may involve a relationship between **two KnowledgeAggregates**, not merely a state transition inside one"* · `113410` investigates SUPERSEDED across four hypotheses, **UNRESOLVED**.

**DDD interpretation.** If contradiction needs a coordination locus because no single aggregate owns the invariant, supersession needs one for the same reason.

**Decision class.** **MAPPING CORRECTION** (record a third candidate locus, DEF-1-deferred like the others).

**If unruled, an implementation decides silently:** supersession is implemented inside one aggregate, coupling it to another and breaching ⟨C-4⟩.

---

### C-9 · Should the anti-capability register gain the three uncovered prohibitions?

**Authoritative law.** Article 11 — *"freshness never truth, expiry never absence"* — **explicit law with no anti-capability and no test**. INV-KOS-DIMENSION-001 · ⟨R-1⟩ — no scalar surrogate for structure. INV-KOS-CONTRADICTION-001 — resolution is **governed** (with an authority reference).

**Existing governed architecture.** The register's twenty entries catch fifteen of the corpus's twenty drift points. **Three are uncovered:** a scalar deciding **contradiction resolution** (A-8 covers *Confidence* only) · **age/recency deciding truth** (no entry) · **automatic contradiction resolution without a recorded authority act** (no entry).

**Brainstorming evidence — FALSIFICATION ARGUMENT, supplying all three counterexamples.** `104251`: `SUPERSEDE: evidence.weight(A) > evidence.weight(B) × 1.5` · `ENTRENCH: claimA.age > claimB.age × 10` · `observation_not_ancient: current_timestamp − observed_at < 30 days` · and a `CONTRADICTION_RULE` resolving automatically by arithmetic with no authority act.

**DDD interpretation.** The register is a coverage artifact; a prohibition in law with no register entry and no test is unenforced.

**Decision class.** **MAPPING CORRECTION** (three additions).

**If unruled, an implementation decides silently:** recency or a weight decides truth — the exact collapse §15 exists to forbid.

---

### C-13 · Must the required reasoning chain be run on the capabilities?

**Authoritative law.** Not a law question.

**Existing governed architecture.** `20260823_1239_working_state.md` specifies the chain: *capability → domain responsibility → invariant → **what must change atomically** → **what may change independently** → consistency boundary → aggregate/service/process*. The mapping classified the nine by PROTECT/PRODUCE and **skipped the atomicity and independence steps**; they were run in the prior act on the twelve **members** only.

**Brainstorming evidence — ANTICIPATED, and it is the corpus's own methodological demand.** `110950` §2: *"DDD requires us to prove why these concepts must share one consistency boundary… the stronger DDD test is **which invariant cannot be protected if this concept is outside the aggregate?**"* · `123619` §12 states the filter: *"if responsibility X is removed: which invariant breaks? If no invariant breaks: X is not Kernel responsibility."*

**DDD interpretation.** Had the chain been run on the capabilities, **C-1 would have surfaced there** rather than in an independent critique.

**Decision class.** **MAPPING CORRECTION / process** — whether a revised mapping must run the chain per capability.

**If unruled:** the next mapping revision repeats the omission.

---

## THREAD D · CONSISTENCY — 1 item

### C-19 · Does the proposed boundary contradict the established EKS/PKS/AIP/KnowledgeOS architecture?

**Reframed by the HPA (recorded as critique §25):** a **bounded consistency check, not a research programme**. Direction of travel **downward into a smaller boundary, never outward**. **EKS/PKS/AIP are context for validating the boundary, not candidates for inclusion.**

**Existing governed architecture.** Two of the checks are executed: **CC-1** (the historical diagram is inconsistent with §16 — see Thread B) and **CC-2** (a Governance context in three models and none in law — see C-3). **Neither moved a responsibility; neither found anything in EKS/PKS/AIP that must be inside the Kernel. So far the check supports the boundary.** The remainder is unexecuted.

**Brainstorming evidence — ANTICIPATED, and it proposed the very matrix that was never built.** `103606` §8 specifies a strict capability matrix over eleven inputs including **existing EKS, existing PKS and the existing AI Engineering Platform**, with columns *Kernel? · Supporting? · External? · Evidence · Invariant protected* — and calls it *"more important than choosing a programming language right now."* `110950` §5 marks **EKS/PKS/KnowledgeOS historical-continuity 🔴 MISSING** and asks whether the Kernel is *"the distilled domain core… or are we accidentally reducing KnowledgeOS to its latest admission model?"*

**DDD interpretation.** A consistency check compares an existing ownership map against a proposed boundary. It can only *falsify* the boundary or clear it; it cannot enlarge it.

**Decision class.** **UNRESOLVED — awaiting a commission to execute the remainder.** No decision is required to run it; a decision is required only if it produces a conflict.

---

## Summary tables

### S.1 · By thread and decision class

| Thread | Items | Predominant decision class |
|---|---|---|
| **C · Constitutional adequacy** | F-CM-1 · C-15 · C-17 · C-18 · C-14 | **AMENDMENT or REJECTION** — never a capability |
| **B · Scope** | C-3/CC-2 · Wisdom · CC-1 | **BOUNDARY DECISION** (two also amendment-class) |
| **A · Completeness** | C-1 · C-2 · C-4 · C-5 · C-6 · C-7 · C-8 · C-9 · C-10 · C-11 · C-12 · C-13 · C-16 | **MAPPING CORRECTION**, four escalating |
| **D · Consistency** | C-19 | no decision needed to execute |

### S.2 · Brainstorming coverage — the answer to *"did we already discover this?"*

| Classification | Items |
|---|---|
| **ANTICIPATED** by the brainstorming | F-CM-1 · C-15 · C-18 · C-14 · C-3 · C-1 · C-2 · C-4 · C-5 · C-6 · C-7 · C-8 · C-10 · C-11 · C-12 · C-13 · C-16 · C-19 — **18 of 21** |
| **FALSIFICATION ARGUMENT** supplied | C-9 (all three counterexamples) · CC-1 (the Kernel-overload warning) |
| **CONFLICTS WITH LAW** — corpus wanted what law forbids | **C-16/F-CM-2** (K1 sameness discrimination vs ⟨C-1⟩) · **C-7** (states-as-derived vs §9 *no implicit transition*) |
| **SILENT** — genuinely new | **C-17** only |
| Wisdom | corpus is extensive but files it at **mechanism altitude**; never claims it is core |

> **The critique did not open an unrelated problem space.** Eighteen of twenty-one findings were reached by the brainstorming first; the governed chain's contribution was to test them against authoritative law and to establish **which are law gaps and which are description gaps** — a distinction the corpus could not make.

### S.3 · Where law already expresses the requirement (so the gap is a *test or description* gap, not a law gap)

**Law expresses it:** **C-8** (identity continuity — §8, INV-KOS-IDENTITY-001) · **C-12** (⟨C-4⟩) · **C-9** (Article 11, INV-KOS-DIMENSION-001, INV-KOS-CONTRADICTION-001) · **C-14's prohibition** (⟨C-5⟩ forbids the low-confidence accept; only the *representation* is undefined) · **C-5** (⟨C-4⟩ + forward-only supersession).

**Law is silent:** F-CM-1 · C-15 · C-17 · C-18 · C-2 · C-3 (scope adequacy) · C-11 (derivability) · C-4 (targeting) · C-6 (staleness) · C-7 (cardinality/precedence) · C-16 (idempotency).

### S.4 · What an implementation would settle silently — ranked by consequence

| # | Item | Silent decision |
|---|---|---|
| 1 | **C-4** | the mechanism names the `KnowledgeId`, narrowing obligation 4 |
| 2 | **C-11** | Confidence computed from something — trivial, or a hidden reasoner |
| 3 | **F-CM-1** | the boundary compares candidates for sameness and becomes a semantic arbiter |
| 4 | **C-3** | any well-formed authority reference is treated as sufficient |
| 5 | **C-2** | T-2 becomes unrunnable; the anti-reasoner constraint loses its detector |
| 6 | **C-17** | the claim is admitted and no state is wrong — the defect is invisible |
| 7 | **C-18** | after the first amendment, prior admissions are unreproducible |
| 8 | **C-15** | retraction collapses into `REJECTED` |
| 9 | **C-6** | admitted knowledge retains justification that no longer exists |
| 10 | **C-16** | replay creates duplicates, or ⟨C-1⟩ is broken by adding dedup |

---

## Traceability

- **Commission:** HPA, 2026-08-23 — build the adjudication pack from the critique questions, using the brainstorming report as historical/adversarial evidence and v1.1 + HPA rulings as authoritative law; **the pack recommends nothing**; keep completeness / scope / constitutional adequacy / consistency separate; do not resurrect the corpus's non-law artifacts; then stop and adjudicate.
- **Authoritative law:** Reference Architecture v1.1 **r5** (`20260822-1402`) §4.1 · §4.3 · §5.3 · §6 · §6.1 · §6.2 · §7 · §8 + ⟨A-3⟩ · §9 + ⟨Z-1⟩ · §14 · §15 · §16 · §17 · §18 D-1…D-6 · §19 · §20 · Appendix B (OBS-2) · Expression↔Meaning Port Contract r4 (`20260822-1559`) §2 obligations 1–6 · §4 r4-4 · §5 — **contract ⬜ PROPOSED, unratified**.
- **Governed chain:** F-1…F-5 (`20260823-1051`, amendments ⬜ OPEN) · Boundary Definition + HPA ruling + r5 (`20260823-1306`) §10 · §11 · Capability Mapping (`20260823-2103`) · Independent Critique (`20260823-2154`) incl. §25 · ADR-KOS-SCOPE-001 (`20260823-2229`) · P5 acceptance (`20260822-2327`) Established item 7 · AH-1…AH-5 confirmation (`20260822-2346`).
- **Non-authoritative corpus** (fourth intake `ec6a4748`; reading report `20260823-2212`): `20260822-161933` · `20260823-103255` · `103606` · `104148` · `104251` · `105200` · `kernel/110248` · `kernel/110950` · `kernel/111647` · `kernel/112155` · `kernel/112855` · `kernel/113410` · `kernel/113645` · `kernel/114358` · `kernel/114530` · `kernel/123619` · `kernel/123630` · `20260823_1239_working_state.md` · `205735` · `210001`.
- **Status:** 📋 **DECISION SUPPORT · PROPOSED · NON-AUTHORITATIVE · RECOMMENDS NOTHING.** 21 items · 4 threads · 5 decision classes. No architecture changed · no mapping corrected · no question resolved · no research opened · no corpus artifact promoted · register **25+4** · Constitution **FROZEN** · **implementation ungated · the Kernel is not built.**
