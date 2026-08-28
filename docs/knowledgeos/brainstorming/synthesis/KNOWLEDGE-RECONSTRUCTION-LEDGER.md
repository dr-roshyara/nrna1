# Knowledge Reconstruction — Research Ledger

**Programme:** Session 1 · KnowledgeOS Research Reconstruction
**Method:** one document at a time · provenance before content · model changes only when justified
**Status:** RESEARCH · NON-AUTHORITATIVE · the formal architecture remains the sole authority
**Registers:** RESEARCH FACT · RESEARCH HYPOTHESIS · EXTERNAL THEORY · PROJECT CONCEPT ·
ARCHITECTURAL PRESSURE · FORMAL ARCHITECTURE — never merged

---

## Document log

| # | Document | Provenance | Change | Model after |
|---|---|---|---|---|
| 1 | `20260801-1231-eks-current-architecture-baseline.md` | **ORIGINAL_PROJECT** | **M2 EXTEND** | **K-M1** |

---

## MODEL K-M0 (inherited starting model — not created here)

Knowledge is **not** merely stored information. It potentially involves: relationship · knower /
participant · object / subject · evidence · justification · reasoning · context · conclusion · state ·
change / evolution · time. Phase 1 original thinking: *"Knowledge is the relationship"* and
*"justified state evolution."* Competing models exist; none assumed correct.

---

## MODEL K-M1

**MODEL ID:** K-M1
**PREVIOUS MODEL:** K-M0
**TRIGGER DOCUMENT:** `20260801-1231-eks-current-architecture-baseline.md` (§9.1, §9.2–9.6, §10)
**CHANGE TYPE:** **M2 — EXTEND** (with a secondary M3 candidate, recorded below, not applied)

**NEW CONCEPT — knowledge has a generative pathway.** K-M0 lists the *constituents* of knowledge
(evidence, justification, reasoning). DOC-1 supplies something K-M0 does not contain at all: the
**process that produces** knowledge, with named intermediate objects.

**OLD MODEL (K-M0):**
```
Knowledge ← relationship( knower, object, evidence, justification, reasoning, context, conclusion )
```

**NEW MODEL (K-M1):**
```
Observation ──► Recommendation ──► Decision ──► Outcome ──► Assessment ──► KNOWLEDGE
                                                                (evidence / effectiveness)

Knowledge = durable understanding derived from accumulated decisions and outcomes
            — and explicitly NOT an observation
```
plus everything K-M0 already held, unchanged.

**WHY THE CHANGE IS NECESSARY:** ⟦RESEARCH FACT⟧ DOC-1 defines knowledge by *derivation*, not by
composition: *"knowledge derived from multiple decisions and outcomes"*, and §9.6 names the
transition point — *"this is where the system begins transforming operational history into
engineering knowledge."* Decision, Outcome and Assessment appear nowhere in K-M0. This is new
content, not new wording.

**EVIDENCE (⟦C⟧ quoted):**
- §9.1 *"Knowledge is durable engineering understanding. It differs from raw observation."*
- §9.1 `LCOM4 = 29` is an **observation**; *"LCOM4 warnings on orchestration classes are accepted only
  under certain circumstances"* is **knowledge** — *"derived from multiple decisions and outcomes"*
- §9.4 *"These concepts must not collapse into one object"* (Observation → Recommendation → Decision)
- §10 *"the most significant current conceptual chain"*: Recommendation → Decision → Outcome →
  Assessment → Effectiveness / Evidence

**PROVENANCE:** ORIGINAL_PROJECT. Self-described *"Current Architecture Reconstruction / evidence-first
architecture archaeology / descriptive baseline; no future architecture is implied."* Not book-derived;
no external author cited for the definition.

**WHAT REMAINS UNCERTAIN:** whether *understanding* is a genus compatible with *relationship*
(see C-K1) · whether the pathway is constitutive of knowledge or merely one production route ·
whether Assessment is the only transition point.

**WHAT THIS DOES NOT MEAN:** it does **not** mean adding Decision, Outcome or Assessment to the
Kernel, to `KnowledgeAggregate`, or to any aggregate; it does **not** mean the pathway is an
invariant; it does **not** promote anything to architecture. ⟦FORMAL ARCHITECTURE⟧ is untouched.

---

## Concept register

**KCON-001 · Knowledge as relationship**
First appearance: Phase 1 (`20260822-0135`) · Provenance ORIGINAL_PROJECT · Status **STRONG RESEARCH
MODEL** · Supporting `0135`, `0225`, `0249`, `0302`, `1057` · Challenged by **KCON-002** · Model K-M0

**KCON-002 · Knowledge as durable understanding**
First appearance **`20260801-1231` §9.1 — the oldest document in the corpus** · Provenance
ORIGINAL_PROJECT · Status **DEFINITION (project)** · Supporting: DOC-1 only · Challenged by KCON-001 ·
Model K-M1

**KCON-003 · Knowledge is derived from decisions and outcomes**
First appearance `20260801-1231` §9.1/§10 · Provenance ORIGINAL_PROJECT · Status **CLAIM** ·
Model K-M1 · Open: is the pathway constitutive or contingent?

**KCON-004 · Observation ≠ Knowledge**
First appearance `20260801-1231` §9.1–9.2 · Provenance ORIGINAL_PROJECT · Status **DEFINITION /
non-collapse** · ⟦FORMAL ARCHITECTURE⟧ comparison: v1.1 §15 carries *Inference ≠ Observation*, a
**related but different** non-collapse — this one is not among the eleven

**KCON-005 · Evidence as first-class**
`20260801-1231` §9.7: *"Evidence is the basis on which a claim, decision, assessment or architectural
transition is justified"* · Status **DEFINITION** · consistent with K-M0

---

## Contradictions — recorded, not resolved

### C-K1 · Is knowledge *understanding* or *a relationship*?

| | Position A | Position B |
|---|---|---|
| **Proposition** | Knowledge is **durable engineering understanding** — a cognitive achievement | Knowledge is **the relationship** among knower, object, evidence, reasoning, context, conclusion |
| **Source** | `20260801-1231` §9.1 | `20260822-0135`, `0225`, `0249`, `0302`, `1057` |
| **Provenance** | ORIGINAL_PROJECT | ORIGINAL_PROJECT |
| **Date** | 2026-08-01 | 2026-08-22 (five arrivals in one day) |

**Why they conflict:** *understanding* is **participant-internal and non-inspectable**; a
*relationship* is **structural and inspectable**. A system can record a relationship; it cannot record
an understanding. The two genera imply different answers to *what is preserved*.
**Possible reconciliation:** understanding may be the *human-side effect* of a recorded relationship.
**Why reconciliation is NOT accepted:** no document proposes it; DOC-1 does not mention relationship
as the genus, and the Phase-1 relationship documents do not mention understanding. Adopting the
reconciliation would be my inference, not the corpus's position.
**Note:** both sides are ORIGINAL_PROJECT and three weeks apart — this is **evolution within the
project's own thinking**, not project-versus-import.

---

## Meta-finding from DOC-1 — it applies to this programme

⟦RESEARCH FACT⟧ DOC-1 §3 establishes the project's own **evidence hierarchy**:

| Priority | Evidence | Weight |
|---|---|---|
| 1 | implementation / source code | Highest |
| 2 | executable tests, enforced invariants | Very high |
| 3 | persistence, durable records | Very high |
| 4 | accepted ADRs, governance decisions | High |
| 5 | architecture documentation | Medium-high |
| 6 | C4 / diagrams | Medium |
| **7** | **proposals and brainstorming** | **Low** |
| 8 | historical contextual knowledge | Lowest |

with the rule: *"A documented architecture that is not implemented is not current architecture."*

⟦I⟧ **Applied reflexively, the entire 257-document corpus this programme reconstructs is priority-7
evidence by the project's oldest stated rule.** That is consistent with the standing discipline
(research has evidence, not authority) — and it means the reconstruction's output can only ever be
**candidate** pressure on the formal model, never a competing authority. Recorded here so it is not
rediscovered later.

⟦RESEARCH FACT⟧ DOC-1 §61 also **excludes** *"future KnowledgeOS kernel concepts"* from current EKS,
and §44 states: *"Historical EKS architecture and the later KnowledgeOS target architecture are not
interchangeable."* ⟦I⟧ So the corpus's oldest document already forbids the retrospective attribution
this programme is guarding against.
