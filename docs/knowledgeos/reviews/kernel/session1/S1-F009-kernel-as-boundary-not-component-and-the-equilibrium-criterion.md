# S1-F009 · "The Kernel is a domain boundary, not a component" — plus an equilibrium criterion that addresses the too-small/too-large tension

**Finding ID:** S1-F009
**Finding class:** HYPOTHESIS (kernel ontology) + CRITERION (membership test) + seven candidate formulations of the core domain act
**Status:** OPEN
**Lenses:** DDD · Zero · Boundary · Identity · Evidence · Justification

---

## Source and provenance

| | |
|---|---|
| **Source document** | `brainstorming/kernel/20260823-110950-kernel-domain-level-brainstorming-admission-hypotheses.md` (1,216 lines) |
| **Document type** | domain-level brainstorming, hypothesis-testing format (numbered sections, explicit *"Hypothesis:"* labels, risk tests) |
| **Provenance** | **`P4` MODEL_INTERPRETATION / structured brainstorming** — no external sources cited, no project-decision language, no HPA act referenced. ⟦PROVENANCE: partly UNCERTAIN⟧ — the register (tables of "Formulation / Description / Domain Meaning") matches model-generated analysis rather than project ruling |
| **Date / phase** | 2026-08-23 · Phase 2 |
| **Relationship stated** | none explicit; sits between the god-object critique (`110248`) and the capability map (`110305`) by timestamp |

---

## Finding 1 · Seven candidate formulations of the core domain act ⟦MODEL⟧

⟦C⟧ **Verification** (checker) · **Admission** (gatekeeper) · **Determination** (decider) ·
**Transformation** (processor) · **Epistemic Maintenance** (custodian) · **Justification Preservation**
(memory system) · **Constitutional Transition** (rule-enforcing state machine).

Each is given a stated weakness, e.g. ⟦C⟧ *Verification* *"says 'this is valid' but doesn't explain…"*;
*Determination* is *"vague"*; *Transformation* *"suggests change of form, not change of s[tatus]"*;
*Justification Preservation* — *"memory alone is…"*.

⟦C⟧ Selected: *"**Admission is the act of crossing from candidate to knowledge.**"*
⟦C⟧ Hypothesis: *"The core domain act is **the constitutionally-gated transition from candidate to
admissible knowledge state**."*
⟦C⟧ *"Admission is the **gate through which all candidates must pass**"* — and it is *"not a standalone
act—it is the **culmination of a process**."*

⟦INFERENCE⟧ This is the corpus's only place where the core act is chosen **against explicitly stated
alternatives** rather than asserted. That makes the choice traceable even though it is a hypothesis.

---

## Finding 2 · The Kernel is a **boundary**, not a component ⟦HYPOTHESIS⟧

⟦C⟧ *"**Hypothesis:** The Kernel is a **domain boundary, not a component.** It is the set of rules and
invariants that govern admission. Its implementation may be in the aggregate, but…"*
⟦C⟧ *"The Kernel is **not the aggregate itself** — the aggregate may include read-side concerns,
projections, or other supporting capabilities. But the Kernel is the **part of the aggregate** …"*
⟦C⟧ *"It is the gate through which candidates pass, and it protects the consistency of the knowledge
state after admission."*

⟦INFERENCE⟧ **This is a different ontological category from every earlier proposal.** Phase 1 lists A/B/C
and the F-1…F-5 map all answer *"which capabilities does the Kernel have?"*. This answers *"what kind of
thing is the Kernel?"* — a set of rules and invariants, not a module. ⟦INFERENCE⟧ If correct, membership
questions change shape: one would ask *which invariants*, not *which capabilities* — which is also
KCON-012's criterion (atomicity, not relatedness) applied at Kernel altitude.

---

## Finding 3 · The equilibrium criterion — a membership test ⟦CRITERION⟧

⟦C⟧ **Risk: too small** — *"Essential domain responsibility could be pushed into mechanisms (e.g.,
evidence weighting in an ML model) · the aggregate invariant could be violated (e.g., identity assigned
by a mechanism) · the admission boundary could be bypassed."* → *"The Kernel **must own the full
admission responsibility** to protect the aggregate invariant."*

⟦C⟧ **Risk: too large** — *"It becomes a God Aggregate… absorbs responsibilities that belong in other
contexts… becomes difficult to reason about, test, and evolve."* → *"The Kernel **must NOT own**
semantic interpretation, evidence content, reasoning, or workflow."*

⟦C⟧ **The equilibrium:**

> *"**The Kernel owns everything necessary to protect the aggregate invariant during admission, and
> nothing more.**"*

| ⟦C⟧ INCLUDES | ⟦C⟧ EXCLUDES |
|---|---|
| Identity assignment · Evidence preservation · Justification preservation · Epistemic state determination · Confidence assignment · History recording · Constitutional evaluation | Semantic interpretation · Evidence content · Reasoning · Workflow · **Projection** · Infrastructure |

⟦INFERENCE⟧ **This directly addresses the `S1-F007` ↔ `S1-F008` opposition** — F007 warns *too large*,
F008 warns *too small*, and this document names **both risks and a test between them**. ⚠ It is a
**proposed criterion, not a ruling**: the tension is *addressed*, **not resolved**. No governance act
adopts it, and the criterion's own key term ("the aggregate invariant", singular) is not specified.

⟦INFERENCE⟧ Note **Projection is explicitly excluded** — the first exclusion of projection found in the
corpus, and it predates the entire Phase 2 projection thread (`110525`, `140251`, `181038`). It is
excluded here without argument.

---

## Finding 4 · `KnowledgeCreated` as the completion event ⟦MODEL⟧

⟦C⟧ *"KnowledgeCreated is the **domain event that signals the completion of admission**… the **business
moment** when KnowledgeOS changes."*
⟦C⟧ The Kernel's responsibility is *"to **produce KnowledgeCreated** when admission is successful—and to
**produce nothing** (or a rejection event) when admission fails."*
⟦C⟧ What becomes true: *"It has an identity, evidence, justification, epistemic state, confidence, and
his[tory]"*; invariants established: *"Identity is unique and immutable. Evidence links are valid.
Justification is preserved. Epistemic state is valid."*

---

## DDD interpretation

- Kernel → **CANDIDATE BOUNDARY / invariant set** (explicitly *not* a component, *not* the aggregate).
- Admission → **CANDIDATE DOMAIN ACT**, chosen against six alternatives.
- `KnowledgeCreated` → **CANDIDATE DOMAIN EVENT** marking completion.
- *"Identity is unique and immutable"* → **CANDIDATE INVARIANT**.
- Projection → **CANDIDATE EXTERNAL CONCERN** (excluded here).

⟦L⟧ Comparison target only: v1.1 §16 makes the kernel *"what KnowledgeOS must protect: the
constitutional invariants and the boundary that enforces them"*, and §9 has `KnowledgeCreated` with
initial state `UNKNOWN`. ⟦INFERENCE⟧ **CONSISTENT with the boundary hypothesis** — v1.1 also treats the
kernel as invariants-plus-boundary rather than a module. This document reaches that framing on 08-23,
after v1.1 (08-22), so it is **not** independent of it; ⟦PROVENANCE⟧ **no** priority claim is made.

---

## Relationship to previous Session-1 findings

- **`S1-F007` / `S1-F008`** — addresses their opposition with a criterion; **does not resolve it**.
- **`S1-F003` / `S1-F005` / `S1-F008`** — reframes all four member lists: if the Kernel is a boundary,
  capability lists are the wrong instrument.
- **Ledger KCON-012** — same criterion (protect an invariant) lifted to Kernel altitude.
- **`S1-F001`** — *history recording* is included, but temporal **validity** still unaddressed.

---

## Classification, confidence, open questions

**Type:** HYPOTHESIS (boundary-not-component) · CRITERION (equilibrium) · MODEL (act formulations,
event). **Not** a definition of Knowledge.
**Confidence:** high on quotations; **medium** on provenance (register suggests model-generated
analysis, not a project ruling); the priority relative to v1.1 is explicitly not claimed.
**Open questions:** *Which* aggregate invariant does the equilibrium protect? · Why is **Projection**
excluded without argument? · If the Kernel is rules-and-invariants, what does "own" mean in the
includes-list? · Is *Confidence assignment* inside the boundary compatible with S1-F007's three-way
confidence split (`W:C-11`)?

**Status:** OPEN. Criterion recorded, not adopted. F007/F008 tension left standing.
