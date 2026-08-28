# S1-F029 · **Abstention is a valid result**, and **construction evidence must not serve as its own validation** — two operational epistemic constraints from the statistical block

**Finding ID:** S1-F029
**Finding class:** CONSTRAINT ×2 (operational, testable) + a mechanism-placement claim for RAG + an authority-retention rule for AI
**Status:** OPEN
**Implementation relevance:** **STRONG IMPLEMENTATION EVIDENCE** for both constraints; **RESEARCH ONLY** for the statistical apparatus behind them
**Lenses:** Evidence · Justification · Zero · Assurance · Agency

---

## Source and provenance

| | |
|---|---|
| **Source documents** | `20260824-030300-freedman-statistical-models-no-inference-without-explicit-basis.md` (1,369 l); `20260824-032208-pattern-recognition-twelve-knowledgeos-statistical-techniques.md` (2,676 l); `20260824-024726-rag-as-controlled-knowledge-access-layer-not-knowledge-model.md` (2,110 l); `20260824-025232-human-ai-collaboration-layer-outside-the-kernel.md` (1,199 l); `20260824-024119-architecture-patterns-with-python-technical-pattern-set-extraction.md` (4,186 l) |
| **Source works** | David A. Freedman, *Statistical Models and Causal Inference*; a pattern-recognition text; a RAG text; a human–AI interaction text; *Architecture Patterns with Python* |
| **Provenance** | **`P3` BOOK_EXTRACTION** throughout. ⚠ ⟦VERIFIED⟧ 0 URLs |
| **Date / phase** | 2026-08-24 **02:41 – 03:22** · Phase 2 |

---

## Finding 1 · Abstention as a first-class output ⟦CONSTRAINT⟧

⟦C⟧ *"**If evidence does not support a sufficiently safe decision, abstention is a valid result.**"*
⟦C⟧ Freedman's counterpart, quoted as the disciplined answer: *"**'we can't tell from the data
available.'**"*
⟦C⟧ Freedman's contribution characterised as *"an **epistemic discipline for preventing a system from
turning models, correlations, assumptions, or fluent explanations into unjustified knowledge**."*

⟦INFERENCE⟧ **Third independent arrival at abstention as a required capability**, from a new direction:
`S1-F024` recorded Audi's *evidence ≠ adoption*; ⟦L⟧ v1.1's Port Contract obligation 3 makes declared
insufficiency a first-class output (*"'I did not determine this' is a valid answer"*); this arrives from
statistics and decision theory. ⟦INFERENCE⟧ **CONSISTENT with v1.1** — and it adds the *decision-safety*
framing v1.1 does not have: abstention is licensed not merely by insufficient grounds but by
**insufficient grounds for a sufficiently safe decision**, which makes the threshold consequence-relative.
That is a stronger claim than v1.1's, and it is **not** adjudicated here.

⟦INFERENCE⟧ Freedman's *"fluent explanations"* is the sharpest phrase: it names the failure where
articulacy substitutes for warrant — the same hazard `S1-F027`'s evidential-bridge requirement addresses,
reached independently.

---

## Finding 2 · Construction evidence must not validate itself ⟦CONSTRAINT — new to Session 1⟧

⟦C⟧ *"**The same evidence should not automatically serve as both construction evidence and independent
validation evidence.**"*

⟦INFERENCE⟧ **This is new to the corpus and directly operational.** It separates two *roles* the same
evidence item may occupy, and forbids one item from occupying both without further warrant. Consequences:
- It is the evidential analogue of the **producer ≠ verifier** separation (`S1-F002`, KCON-013,
  KCON-018 — *records, does not create*), now stated about **evidence rather than actors**. Fifth arrival
  in that family, and the first at the evidence level.
- It bears on unruled **`W:C-10`** (evidence-reference resolvability): if role matters, an evidence
  *reference* may need to carry its **role**, not merely its identity.
- It intersects `S1-F018`'s decisive Entity/Value-Object question from a new angle: a *shareable Entity*
  could be reused across construction and validation, which this constraint forbids — so the Entity horn
  acquires an additional cost the corpus had not recorded.

⟦INFERENCE⟧ ⟦L⟧ v1.1 has no counterpart row: *Evidence ≠ Authority* (§15) is about evidence versus
warrant, not about **evidence roles**. Classified **NOT ADDRESSED**; recorded as a research constraint,
**not** a gap claim.

---

## Finding 3 · RAG placed as access/projection, not as the knowledge model ⟦CLAIM⟧

⟦C⟧ Contrast drawn between the two books: *"How should a **governed domain system mutate and preserve
state**?"* versus *"How should a knowledge system **discover, assemble, rank, contextualize, evaluate, and
operationalize** information for an AI system?"*

⟦INFERENCE⟧ The document's title records the disposition (*RAG as a controlled knowledge-access layer, not
the knowledge model*), which is the **third arrival at *mechanism ≠ authority*** after Audi's
*evidence ≠ adoption* and the retrieval claim in `S1-F025`. Consistent with `S1-F011`'s exclusion of
retrieval/search/ranking. **RESEARCH ONLY.**

---

## Finding 4 · Authority remains assigned, whatever the AI does ⟦CLAIM⟧

⟦C⟧ *"**AI may recommend, analyze, classify and propose; authority remains explicitly assigned.**"*
⟦C⟧ Plus an assurance frame: *"**Fairness, Explanatory Ability, Auditability and Safety (FEAS)**"*.

⟦INFERENCE⟧ **Sixth arrival in the authority family** and the cleanest verb-level statement of it:
recommend/analyse/classify/propose are permitted; *assign* is not. ⟦L⟧ **CONSISTENT** with v1.1 obligation
4 (*never proposes or derives a KnowledgeId*) and §13 (LLM *"does not own truth"*). FEAS is a named
external framework; recorded as **RESEARCH ONLY**, no placement claimed.

---

## Finding 5 · The only implementation-altitude document in the corpus ⟦IMPLEMENTATION EVIDENCE⟧

`20260824-024119` (*Architecture Patterns with Python*, 4,186 lines — the largest document in this batch)
frames a division of labour: ⟦C⟧ *"**How knowledge should be justified**"* (from the Nyāya reading) versus
⟦C⟧ *"**How a system can reliably represent, mutate, persist, validate, publish, and project that
knowledge**."*

⟦INFERENCE⟧ Census C8 flagged this as the corpus's **only implementation-altitude document**. Its
contribution is a *technical pattern vocabulary* (repository, unit of work, aggregate, domain events,
message bus, CQRS), and — importantly — it is the source of the **CQRS sense of "projection"** that
`S1-F014`/`S1-F018` recorded as a **vocabulary collision** with epistemic projection. ⟦INFERENCE⟧ So the
collision has a single identifiable origin document, which makes it tractable: the five senses of
*projection* are not five confusions but one imported technical term plus four research senses.
Classification: **IMPLEMENTATION EVIDENCE** for patterns; **explicitly not** a Kernel model — *a
mathematical or technical model is not automatically a Kernel model.*

---

## DDD interpretation

- Abstention → **CANDIDATE first-class output** of any admission or assessment procedure.
- Evidence **role** (construction vs validation) → **CANDIDATE attribute of an evidence reference**
  (`W:C-10`).
- RAG / retrieval / ranking → **CANDIDATE MECHANISMS**, outside.
- *recommend/analyse/classify/propose* vs *assign* → **CANDIDATE authority verb boundary**.
- Repository / UoW / message bus / CQRS → **infrastructure patterns**, no domain standing.

---

## Relationship to previous Session-1 findings

- **`S1-F024`** — Freedman, RAG, human–AI and pattern-recognition rows belong to that register's pattern;
  recorded here because they yield **constraints**, not merely distinctions.
- **`S1-F002`/KCON-013/018** — fifth and sixth arrivals in the authority family; the evidence-role
  constraint is the first at evidence rather than actor level.
- **`S1-F018`** — the Entity horn gains a new cost: a shareable evidence Entity could serve both roles,
  which Finding 2 forbids.
- **`S1-F025`/`S1-F027`** — third mechanism-independent constraint set; *fluent explanations* converges
  with the evidential-bridge requirement.
- **`S1-F014`** — the CQRS-projection collision is traced to `024119` as its origin.

---

## Classification, confidence, open questions

**Type:** CONSTRAINT ×2 · CLAIM ×2 · IMPLEMENTATION EVIDENCE (patterns).
**Confidence:** high on quotations; the two constraints are **operational and testable**, which is why they
are separated from the `S1-F024` register.
**Open questions:** is the abstention threshold consequence-relative (safety) or grounds-relative
(v1.1)? · must an evidence reference carry its **role** (`W:C-10`)? · does the evidence-role constraint
survive if evidence is a shared Entity (`S1-F018`)?

**Status:** OPEN. Constraints recorded, not adopted; nothing adjudicated.
