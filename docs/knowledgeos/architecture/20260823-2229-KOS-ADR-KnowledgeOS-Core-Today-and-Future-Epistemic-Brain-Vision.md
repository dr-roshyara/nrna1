# ADR — KnowledgeOS Core Today, and the Future Epistemic-Brain Vision

## 1 · Title

**ADR-KOS-SCOPE-001 — What the KnowledgeOS Core/Kernel is today, what it is explicitly not, and the long-term vision that it may become the epistemic substrate of future computer systems.**

## 2 · Status

📋 **PROPOSED · NON-AUTHORITATIVE · SCOPE AND VISION CLARIFICATION · awaiting HPA/ARB review.**

This ADR **decides no architecture**. It records a scope boundary that already exists in law and separates it from an aspiration that does not. It **adds no capability, no invariant, no state, no event, no member, no aggregate, no bounded context and no register row.** Register **25+4** · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · states **7→7** · invariants **11→11** · Constitution **FROZEN** · research **CLOSED** · SNF **CLOSED** · OQ-4 **UNAUTHORIZED** · AH-5 **deferred**.

**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos --maturity=research` → `docs/knowledgeos` (exit 0); `architecture/` alongside Reference Architecture v1.1 and the Expression↔Meaning Port Contract.

## 3 · Context

The governed chain has reached the point where the boundary is defined and its description is being completed:

```
brainstorming → domain discovery → DDD invariant analysis → smallest-consistency-boundary analysis
  → KnowledgeCore admission-boundary definition → Kernel capability mapping
  → independent adversarial critique → HPA adjudication → implementation decision → Kernel implementation
                                        ▲
                                    WE ARE HERE
```

Two pressures made this ADR necessary.

**First, an ambiguity of altitude.** A historical conceptual picture nested `AI Engineering Platform → EKS/PKS → KnowledgeOS → KnowledgeCore → Kernel`. That picture mixes an ecosystem view with a domain view with a consistency-boundary view, and the independent critique found it inconsistent with law in a specific way (§14 below).

**Second, an ambiguity of ambition.** The programme has an explicit long-term aspiration: that this core could one day serve as the epistemic substrate of trustworthy cognitive and AI-native computer systems. That aspiration is legitimate and worth recording. It is also exactly the kind of thing that silently enlarges a Kernel if it is not fenced.

## 4 · Problem statement

> **Four distinct things are being referred to by overlapping names, and a future aspiration is at risk of becoming a present requirement.**

Concretely:

1. **KnowledgeOS**, **KnowledgeCore**, **Kernel** and a **future cognitive system** are used as if interchangeable. They are not.
2. The word **Kernel** itself carries two lawful meanings — the §16 change-governance *altitude* and the *admission boundary* this chain defines — which the K-1 ruling separated but the historical diagram still conflates.
3. Capabilities a *future* computer might need risk being read as capabilities the *present* Kernel must have.
4. **Wisdom** was explored extensively in the brainstorming corpus and is constitutionally refused at the core; without an explicit statement it may drift inward.

## 5 · Decision

**We decide the following, and nothing more.**

1. **The present KnowledgeOS Core work is bounded by the question already commissioned:** *what is the smallest authoritative KnowledgeCore boundary required to preserve KnowledgeOS's constitutional and domain invariants?* It is **not** *how do we build an intelligent computer brain?*
2. **The four concepts are distinct and are never synonyms** (§6–§8, §12).
3. **The present Kernel's capacity is exhausted by existing law.** No capability enters because it would be useful; each traces to an existing invariant, domain responsibility, or accepted decision (§10).
4. **An explicit anti-scope holds** (§9). Semantic interpretation and reasoning mechanisms remain **external providers at ports**.
5. **The epistemic-brain vision is recorded as a vision** (§11–§12) — an architectural aspiration, not a product requirement, not an implementation commitment, and not a source of present Kernel capability.
6. **The governing design principle is adopted as a principle, not as a capability** (§18): *the future brain should be built **around** the epistemic Kernel, not by enlarging the Kernel until it becomes the brain.*
7. **Wisdom is not added to KnowledgeCore or the Kernel** (§16). Making it a core concept requires a constitutional amendment, which this ADR does not make.
8. **EKS/PKS/AIP relate to the Kernel by consistency check, never by absorption** (§14).

## 6 · Current KnowledgeOS scope — the wider architectural system

**KnowledgeOS is the whole architecture**, comprising six bounded contexts (v1.1 §5.3):

| Context | Role | Owns |
|---|---|---|
| **KnowledgeCore** | **CORE** | KnowledgeAggregate · ConflictRecord — **all eleven invariants** |
| **Authority** | SUPPORTING | AuthorityGrant — *the recorded reference to a human act* |
| **Projection** | SUPPORTING | DerivedView — regenerable, non-authoritative, no write-back |
| **Decision Boundary** | SUPPORTING | DecisionRecord — informs, never executes |
| **Reasoning & Validation** | **GENERIC / EXTERNAL** | its own transient artifacts — **no domain state** |
| **Expression** | **GENERIC / EXTERNAL** | its own transient artifacts — **no domain state** |

**Two clarifications this ADR records because they are commonly misread:**

- **Expression and Reasoning & Validation are external.** They are adapters at ports, not parts KnowledgeOS owns. It is inaccurate to list them as KnowledgeOS-owned responsibilities.
- **There is no Governance bounded context.** Authority holds the *grant*; nothing in the six evaluates eligibility, policy, or the *scope adequacy* of an authority. That a Governance context appears in three independent working models and in none of the law is a **recorded open gap** (critique **C-3**), not a licence to add a seventh context here.

## 7 · KnowledgeCore scope — the core domain

**KnowledgeCore is the only core domain**, and its purpose is narrow: *preserve the identity and justified life of epistemic states.* It owns two aggregates and all eleven invariants, and **depends on nothing** (D-1).

Its canonical wording, ratified and used verbatim:

> **"The KnowledgeAggregate is the authoritative domain boundary at which constitutional admissibility of a state transition is determined."**

Note *state transition* — not *creation*. Admission is admission **of a transition**, which is why post-admission acts (revision, translation, supersession, contradiction handling) are also admissions.

## 8 · Kernel scope — the smallest authoritative protection boundary

**`KERNEL` is reserved for the §16 change-governance altitude** (K-1, ruled 2026-08-23). The structure this chain defines is named:

> ### **the KnowledgeCore Admission Boundary**

**Its exact shape, as established and not redesigned here:**

```
KnowledgeCore Admission Boundary        ← ONE admission boundary
│                                          (the Verification Port is its only gate)
├── KnowledgeAggregate                  ← consistency boundary #1
│
└── ConflictRecord                      ← consistency boundary #2 (SEPARATE)
     └── coordination is CROSS-AGGREGATE → DS-CAND-2 → ⬜ DEF-1
```

**Three distinctions this ADR is required to preserve, and does:**

- **Bounded context ≠ aggregate ≠ admission boundary.** KnowledgeCore is a context; KnowledgeAggregate and ConflictRecord are each a consistency boundary; the admission boundary is one gate over both.
- **The two aggregates are not one aggregate.** They remain separate consistency boundaries; their coordination is cross-aggregate and subject to **DEF-1**.
- **The admission boundary is not itself an aggregate.**

**ConflictRecord is inside for a stated reason, not a conceptual one:** INV-KOS-CONTRADICTION-001's enforcement locus is defined as `EpistemicState(CONFLICTED)` **+** `ConflictRecord` jointly, and Article 8.3 requires the record to survive resolution forward-only. **Atomic consistency between the state and the existence of its record is therefore necessary under the current invariant definition** — not because contradiction is conceptually related to knowledge. Should that definition change, the membership must be **re-derived, not inherited**.

### 8.1 Status of the boundary — stated explicitly as commissioned

| Artifact | Status per the repository |
|---|---|
| **The boundary itself** (extent, two aggregates, sole gate) | ✅ **ACCEPTED as the architectural decision-support basis, subject to recorded rulings** (HPA, 2026-08-23) |
| **⟨Z-1⟩ / the three gate causes** | ✅ **RULED** — pre-domain reading (B); applied to v1.1 as the **r5** conformance correction and verified |
| **K-1 terminology** | ✅ **RULED — ACCEPTED** |
| **The Kernel Capability Mapping** | 📋 **PROPOSED · NON-AUTHORITATIVE · decision ⬜ OPEN** |
| **The independent critique** | 📋 **DELIVERED · PROPOSED** — mapping **SURVIVES on placement, FAILS on completeness**: 1 falsified, 5 partially falsified, 11 recorded defects, **13 HPA questions unresolved** |
| **Implementation** | ❌ **NOT AUTHORIZED. Nothing is implemented.** |

> **The boundary is accepted; the complete description of what it must protect and do is not. Implementation remains ungated.**

## 9 · Kernel anti-scope — what the Kernel must never become

**The KnowledgeCore Admission Boundary must NOT be, or become:**

a general NLP system · a Sanskrit/Pāṇinian grammar engine · an FST engine · a semantic interpreter · a general reasoning engine · a truth oracle · an LLM or LLM wrapper · a confidence-scoring mechanism derived from semantic content · a workflow engine · a planning engine · a governance administration engine · an evidence search engine · a database abstraction · an AI agent · a UI · an infrastructure platform.

**Grounded in existing law, not asserted:** §16 (*"the kernel does not reason… a kernel member cannot generate a conclusion"*) · §17's eleven Chapter IV refusals (language engine · database · chatbot · LLM wrapper · ontology repository · truth machine/oracle · …) · Test F and F-3 (no surface form is core) · ⟨C-3⟩ (links, never evidence content) · ⟨R-1⟩ (no mechanism scalar becomes Confidence) · §6 (*no mechanism may ever produce* EpistemicState) · Article 4 (informs, never executes) · Article 5 (no write-back) · D-1 (the core depends on nothing).

**Natural-language interpretation and all semantic mechanisms remain external providers at ports.** The boundary receives candidate representations and protects authoritative domain state. It does not produce meaning.

## 10 · Current capacity — every entry traceable, nothing invented

The present responsibilities, each with the law that requires it. **This list adds nothing.**

| Responsibility | Traces to |
|---|---|
| Authoritative admission through a **single gate** | INV-KOS-VERIFICATION-001 (*the only admission path*) |
| **Identity assignment** — assigned, never derived | INV-KOS-IDENTITY-001 · ⟨C-1⟩ |
| **Evidence admission** — references only | ⟨C-3⟩ |
| **Justification preservation** | INV-KOS-VERIFICATION-001 (Article 6.4) |
| **Structural sufficiency / conformance specification** | §5.2 (ACL) · Port Contract §2 · D-2 (*the core names what may cross*) |
| **Epistemic-state integrity** — the seven states, domain-determined | §9 · §6 |
| **Confidence** — *only to the extent lawfully defined*: assigned **inside**, structured, never a mechanism scalar | ⟨R-1⟩ · OQ-5 (member retained) |
| **History / lifecycle preservation** — forward-only | INV-KOS-HISTORY-001 |
| **Lawful state transitions** — named, governed, no implicit transition | INV-KOS-DIMENSION-001 · §9 |
| **Contradiction handling** — `CONFLICTED` + ConflictRecord, resolution governed | INV-KOS-CONTRADICTION-001 |
| **Invariant-preserving refusal** — AUTHORITY · PROJECTION · DECISION preserved *as refusals*, records outside | Articles 3 · 4 · 5 |

**Recorded honestly, because this ADR must not overstate the present capacity:** the independent critique found this list **incomplete and mis-typed** in ways that are unresolved. In particular — the nine-item capability list is a **category mixture** (**C-1**); **`Confidence` has no lawful input** identified, since evidence content, mechanism scores and reasoning are each forbidden (**C-11**); **determinism** is presupposed by the fitness tests but nowhere mapped (**C-2**); and the **revision/targeting path** for transitions on *existing* aggregates is unmapped (**C-4**, the one outright falsification). *"Deterministic and invariant-preserving behaviour"* is therefore listed in this ADR as a **required property under adjudication**, not as an established capability.

## 11 · Future vision

> **KnowledgeOS Core could eventually serve as the epistemic core, epistemic memory, or epistemic-integrity substrate of future computer systems** — conventional, autonomous, AI-native, agentic, or future cognitive architectures.

**This does not exist. It is not being built. It creates no present requirement.**

What the vision claims is modest and specific: that a system which is *entitled to treat something as knowledge* needs an authoritative record of **what** it knows, under **what identity**, on **what evidence and justification**, in **what epistemic state**, through **what historical path** — and that such a record is worth building once, correctly, and reusing.

**The Kernel does not need to think in order to be part of a future machine's mind. Its possible role is to make that machine's knowledge accountable.**

## 12 · Future "epistemic brain" architecture — vision only

```
                    Future computer system
                              │
        ┌───────┬───────┬─────┴─────┬───────┬───────┬───────┐
        │       │       │           │       │       │       │
   perception expression interpretation reasoning planning learning  action / interaction
        └───────┴───────┴─────┬─────┴───────┴───────┴───────┘
                              │  candidate claims
                              ▼
                        ┌───────────────┐
                        │  KnowledgeOS  │
                        │       │       │
                        │ KnowledgeCore │
                        │       │       │
                        │  Admission    │
                        │  Boundary     │
                        └───────┬───────┘
                                │  authoritative knowledge state
                                ▼
                    reasoning · planning · action
```

The outer system may be extremely capable. **KnowledgeOS would provide the substrate against which that capability is held accountable** — not a competitor to it.

**The flow, stated as a discipline:**

```
perception → interpretation → reasoning → candidate knowledge
    → KnowledgeOS admission boundary → authoritative knowledge state → reasoning / planning / action
```

**The boundary protects the crossing. It never becomes the producer of semantic knowledge.**

## 13 · DDD interpretation — why this does not create a God Kernel

A future cognitive architecture can be **composed around** KnowledgeOS without moving cognitive responsibility into KnowledgeCore, because DDD already supplies the mechanisms:

- **Bounded contexts retain ownership.** Perception, interpretation, reasoning, planning and learning are contexts in their own right, with their own ubiquitous languages. Their vocabulary is theirs.
- **Aggregates protect their own consistency boundaries.** `KnowledgeAggregate` and `ConflictRecord` protect theirs and nothing else. A cognitive system's working memory, plans and model state are not their concern.
- **Domain services coordinate where no aggregate can.** Cross-aggregate coordination (admission decision; contradiction; supersession) has candidate loci recorded and deferred to **DEF-1** — it is not resolved by enlarging an aggregate.
- **Ports isolate mechanisms.** Every inbound arrow is a port with an ACL (D-4); every outbound arrow is advisory or projection (D-5).
- **Anti-corruption boundaries stop vocabulary capture.** Candidate payload vocabulary — interpretation probability, uncertainty, provenance, transformation evidence — is **port vocabulary and never an aggregate member** (r4-4). This is the specific mechanism by which a future LLM's or planner's vocabulary is prevented from becoming domain language.
- **Replaceability is a property, not a hope** (D-6). Any mechanism may be swapped without touching the core — which is precisely what makes the core reusable across future systems whose models and algorithms will change.

**The anti-pattern this ADR names and forbids:** treating a compelling metaphor — *constitutional adjudicator*, *epistemic brain* — as a domain concept and then using it as a container for every responsibility that seemed important. The brainstorming corpus produced that failure twice, in full detail, and rejected it twice.

## 14 · Relationship to EKS / PKS / AI Engineering Platform

**The historical picture, recorded as historical:**

```
AI Engineering Platform  →  EKS / PKS  →  KnowledgeOS  →  KnowledgeCore  →  Kernel
```

**Status: historical architectural context / candidate predecessor model — verify, do not assume authoritative.** It mixes an ecosystem view with a domain view with a consistency-boundary view.

**Three views, kept separate:**

| View | Answers |
|---|---|
| **Ecosystem** — AI Engineering Platform ⊇ {EKS, PKS, KnowledgeOS} | *where does KnowledgeOS live in the wider engineering environment?* |
| **KnowledgeOS architectural** — the six bounded contexts (§6) | *what are the contexts and responsibilities inside KnowledgeOS?* |
| **Kernel** — the admission boundary (§8) | *what is the smallest authoritative boundary protecting KnowledgeCore's invariants?* |

**The correct present question is a consistency check:**

> **Does the proposed Kernel boundary violate any already-established architectural dependency, responsibility, or ownership in the EKS/PKS/AIP/KnowledgeOS architecture?**

**This is not authorization to absorb EKS, PKS or AIP into the Kernel.** The direction of travel is **downward into a smaller boundary, never outward**. Those systems are **context for validating the boundary, not candidates for inclusion in it**.

**Two results of that check are already recorded** (critique §25):

- **CC-1 — the historical nesting contradicts §16.** The diagram draws `Kernel ⊂ KnowledgeCore`, but §16's **KERNEL altitude** includes the three supporting aggregates (`AuthorityGrant`, `DerivedView`, `DecisionRecord`), which §6.2 places in the **Authority, Projection and Decision Boundary** contexts — *outside* KnowledgeCore. So under the altitude reading **`Kernel ⊄ KnowledgeCore`**; the altitude spans four contexts. Only the *admission-boundary* reading nests as drawn. **The diagram is confirmed inconsistent with law; its disposition is an HPA act and no correction is made here.**
- **CC-2 — a Governance context appears in three independent models and in none of the law**, corroborating **C-3** (the authority-adequacy responsibility is unhomed) from a third direction.

**Neither check moved a responsibility, and neither found anything in EKS/PKS/AIP that must be inside the Kernel. So far the consistency check supports the boundary.** Its remaining scope is unexecuted and awaits a commission.

## 15 · Relationship to the brainstorming corpus and analytical lenses

**The corpus is NON-AUTHORITATIVE.** It amends nothing: not v1.1, the Constitution, the Port Contract, the aggregate model, the invariants, the boundary, the capability mapping, the register, or any governance decision. It is cited here only for **architectural motivation and long-term vision**, and its own meta-rule is adopted:

> **"Convergence of lenses does not make the lens itself architectural authority."**
> **"The lenses are instruments for discovering and challenging architecture. They are not components of the Kernel."**

**No spiritual or philosophical lens becomes domain law.** The lenses used, and the question each contributes:

| Lens | Question it asks of the Kernel |
|---|---|
| **DDD** | *which context actually owns this responsibility?* — the adjudicator over all other lenses |
| **Zero** | **what must the Kernel remain incapable of becoming?** — and: what can disappear without the Kernel inventing a state for it? |
| **Topological** | **what is the smallest boundary whose removal breaks the constitutional integrity of KnowledgeCore?** |
| **Sanskrit / Pāṇinian / compiler** | reinforces **expression → interpretation → candidate → admission**, and thereby keeps grammar and semantic interpretation *outside* |
| **Semantic identity / representation-invariance (Escher)** | does this property survive representation transformation, or is the Kernel bound to a surface form? |
| **Epistemic (Nyāya/Tarka)** | what makes a claim warranted, and where does the justification end? |
| **Temporal (Śiva–Śakti)** | what remains invariant while epistemic state changes? — `KnowledgeId` |
| **Authority** | where could something accidentally acquire authority merely by crossing? |
| **Navya-Nyāya relational** | is a relation being treated as an intrinsic property? |
| **Gaṇeśa** | what must be true *before* something crosses a threshold? |
| **Gödel** | the Kernel cannot establish its own correctness from inside itself — hence **external** fitness tests, never self-audit |

**Three lens results carried into this ADR as discipline, not as law:** the Zero lens's answer to *what must the Kernel remain incapable of becoming* **is** §9's anti-scope. The Topological lens's question **is** the commissioned boundary question. The Sanskrit/compiler lens's chain **is** why §9 holds.

## 16 · Wisdom boundary — precise, and no amendment made

**The current Constitution refuses Wisdom as a KnowledgeCore concept. Stated with its sources:**

- **v1.1 §4.1** — *Wisdom Formation*: *"no register row; the character ends at **trustworthy truth discovery**; adopting it would extend the frozen Constitution (V.3)"* → **REJECTED at the boundary** (§17; **amendment-gated**).
- **v1.1 §17** — *"Wisdom Formation as a core domain"* is listed among the Chapter IV / external refusals, against **V.3**.
- **v1.1 §8** — *"⚠️ **WisdomDerived** — recorded for traceability, **NOT admitted**. Wisdom is not a constitutional concept… A future Wisdom context would require a constitutional amendment, and **V.3 forbids adding concepts**."*

**Therefore:**

> **Wisdom is not added to KnowledgeCore or to the Kernel by this ADR.** A question of the form *"what is the smallest boundary of knowledge **and wisdom**?"* cannot be answered by a boundary act, because the second term is amendment-gated. Folding it in would extend the frozen Constitution as a side effect — the precise failure mode this chain exists to prevent.

**What Wisdom may lawfully remain:** research · a conceptual lens · a **mechanism candidate** outside the boundary (the 26-lens system files the wisdom family in tier 3, mechanism candidates) · a future architectural possibility.

**Making Wisdom a core domain concept requires a constitutional amendment under V.3. This ADR does not make that amendment and does not recommend one.** If the programme wants Wisdom in scope, that is a **separate constitutional act with its own commission**, not an enlargement of the Kernel question.

## 17 · Current vs future capability matrix

**Future rows are vision. They are not present requirements.**

| Capability | NOW | FUTURE VISION |
|---|---|---|
| Knowledge admission | **YES** | **YES** |
| Identity assignment | **YES** | **YES** |
| Evidence / justification (references, preservation) | **YES** | **YES** |
| Epistemic lifecycle & history | **YES** | **YES** |
| Contradiction handling (`CONFLICTED` + ConflictRecord) | **YES** | **YES** |
| Confidence | **YES — only as lawfully defined** (⟨R-1⟩; derivability under adjudication, **C-11**) | YES, same constraint |
| Determinism | **REQUIRED PROPERTY, UNDER ADJUDICATION** (**C-2**) | YES |
| Semantic interpretation | **OUTSIDE** | MAY SURROUND CORE |
| Reasoning | **OUTSIDE** | MAY SURROUND CORE |
| Planning | **OUTSIDE** | MAY SURROUND CORE |
| Learning | **OUTSIDE** | MAY SURROUND CORE |
| Perception | **OUTSIDE** | MAY SURROUND CORE |
| Action / interaction | **OUTSIDE** | MAY SURROUND CORE |
| Autonomous agents | **OUTSIDE** | MAY USE CORE |
| AI-native computer | **OUTSIDE** | POSSIBLE FUTURE HOST |
| Wisdom | **NO — amendment-gated (V.3)** | POSSIBLE, amendment required |
| **"Brain"** | **NO** | **ARCHITECTURAL VISION** |

**The Kernel is not "KnowledgeOS in miniature."** It is the authoritative protection boundary for the part of KnowledgeOS that owns the **life and identity of admitted knowledge** — and no more.

## 18 · Architectural invariants this ADR commits to

1. **The future brain is built AROUND the epistemic Kernel — never by enlarging the Kernel until it becomes the brain.**
2. **Build the smallest correct epistemic core today so that a much larger cognitive architecture can safely be built around it tomorrow.**
3. **Never enlarge the current Kernel merely because a future computer might eventually need the capability.**
4. **The Kernel is semantically non-authoritative.** It protects the crossing; it never produces meaning.
5. **The Kernel is resistant to mechanism capture.** Mechanism vocabulary stays port vocabulary (r4-4); mechanism scores never become Confidence (⟨R-1⟩); mechanism outputs never become identity (obligation 4, ⟨C-1⟩) or epistemic state (§6).
6. **Every present capability traces to existing law.** Usefulness is not a warrant.

**Why (1) is the load-bearing one.** The outer cognitive system will change — models, algorithms, languages, sensors, reasoning engines, planners, agents, learning methods, representations. If those changes reach the epistemic core, the core's guarantees change with them and nothing is preserved. **The core is valuable precisely to the degree that it does not move when they do.** Enlarging it to satisfy a future need destroys the only property that made it worth reusing.

## 19 · Consequences

**Positive.** The scope question stops recurring. The vision is recorded once, in a place where it cannot be mistaken for a requirement. The anti-scope becomes citable. The three views (ecosystem / architectural / Kernel) are separated, so the historical diagram can no longer be read as current nesting. The future ambition becomes an argument for a *smaller* core, which is the opposite of its usual effect.

**Negative / costs.** A second architectural picture now exists alongside the historical one until the HPA disposes of the latter (**CC-1**). This ADR states an anti-scope it cannot enforce — enforcement is the fitness-constraint work, still ahead. And it records a present capacity that the independent critique has shown to be incomplete, which may read as endorsement if §10's caveat is skipped.

**Risks.** The vision section is the attack surface: any future actor may cite §11–§12 as authority for a capability. §18(3) exists specifically to refuse that. A second risk is that *"epistemic brain"* becomes a slogan that outruns the evidence; the language here is deliberately conditional throughout.

## 20 · Explicit non-decisions

**DECIDED NOW:** the scope of the present Core/Kernel work · the Kernel is **not** the whole KnowledgeOS · the Kernel is **not** the future brain · semantic and reasoning mechanisms remain **outside** · present capacity is constrained by existing law · the future cognitive architecture is a **vision** · EKS/PKS/AIP relate by **consistency check** · Wisdom is **not** added.

**NOT DECIDED NOW:** the exact future computer architecture · AGI architecture · consciousness · learning architecture · a future Wisdom domain · future AI-agent architecture · future EKS/PKS/AIP integration · implementation technology · **and every one of the 13 HPA questions recorded by the independent critique**, including C-1 (capability taxonomy), C-2 (determinism), C-3 (authority adequacy), C-4 (revision path), C-11 (Confidence derivability), F-CM-1 (*Ambiguity ≠ Contradiction*) and F-CM-2 (identity uniqueness).

## 21 · Governance / implementation gate

```
this ADR (PROPOSED)
   → HPA/ARB review
   → adjudication of the 13 recorded critique questions
   → fitness-constraint definition (T-2 semantic-invariance and T-17 ⟨Z-1⟩ before any admission code)
   → HPA ruling
   → implementation decision
   → Kernel implementation
```

**No step may be skipped, and this ADR authorizes none of them.** Implementation is **ungated** and remains so. Two questions are gating in the strict sense: **C-4** and **C-11** should be ruled before any admission code exists, because an implementation would otherwise settle them silently in code.

## 22 · One-sentence architectural definition

> **The KnowledgeOS Kernel is the smallest authoritative boundary through which anything may become knowledge — it makes knowledge accountable, and it never makes knowledge.**

---

## Final statement

> **TODAY:** we are building the smallest authoritative epistemic core required by KnowledgeOS.
>
> **TOMORROW:** we aspire to make that epistemic core reusable as the trustworthy knowledge and memory substrate of increasingly autonomous computer and AI systems.
>
> **NEVER:** we do not turn the Kernel itself into a God component merely to satisfy the future vision.

---

## Traceability

- **Commission:** HPA, 2026-08-23 — *ADR COMMISSION: KnowledgeOS Core Today and Future Epistemic Brain Vision*; scope-and-vision clarification; 22-section structure; the central four-concept distinction; anti-scope; the current-vs-future matrix; the DDD interpretation; EKS/PKS/AIP as consistency check; the Wisdom precision requirement; lenses as conceptual only; decided/not-decided separation; *"do not write this ADR as science fiction"*; stop for HPA/ARB review.
- **Authoritative law consulted:** Reference Architecture v1.1 **r5** (`20260822-1402`) §4 · §4.1 · §4.3 · §5.2 · §5.3 · §6 + altitude note · §6.1 · §6.2 · §7 · §8 + ⟨A-3⟩ · §9 + ⟨Z-1⟩ · §11.2 · §13 · §14 · §15 · §16 · §17 · §18 D-1…D-6 · §19 · §20 · Appendix A (r3/r4/r5) + Appendix B · Expression↔Meaning Port Contract r4 (`20260822-1559`) §2 · §4 r4-4 · §5.
- **Governed chain consumed:** F-1…F-5 (`20260823-1051`) · Kernel Boundary Definition + HPA ruling + r5 record (`20260823-1306`) §10 · §11 · Kernel Capability Mapping (`20260823-2103`) · Independent DDD Critique (`20260823-2154`) incl. **§25** (C-19 reframing · CC-1 · CC-2) · P5 acceptance (`20260822-2327`) · AH-1…AH-5 confirmation (`20260822-2346`).
- **Non-authoritative corpus cited for motivation only:** the brainstorming Kernel thread (fourth intake, `ec6a4748`) and its reading report (`20260823-2212`) — the 26-lens system, the 20-point drift catalogue, and the two rejected god-Kernel designs.
- **Status:** 📋 **PROPOSED · NON-AUTHORITATIVE · awaiting HPA/ARB review.** No code · no Kernel modification · no aggregate modification · no Constitution modification · no v1.1 modification · no Port Contract modification · no new domain concept · no research · SNF **CLOSED** · OQ-4 **UNAUTHORIZED** · **Wisdom NOT added to the core** · no EKS/PKS/AIP redesign · no technology choice. Register **25+4** · contexts **6→6** · aggregates **5→5** · members **12→12** · events **10→10** · states **7→7** · invariants **11→11** · Constitution **FROZEN** · **the Kernel is not built.**
