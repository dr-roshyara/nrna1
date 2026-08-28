# S1-F027 · A family of **inference-licensing constraints**: never infer semantic identity from functional similarity, never infer ontology from representation — and *"no high-value architectural claim without an identifiable evidential bridge"*

**Finding ID:** S1-F027
**Finding class:** CONSTRAINT family on what may be inferred (distinct from constraints on what the Kernel contains, and from `S1-F025`'s constraint on decision procedure)
**Status:** OPEN
**Implementation relevance:** **STRONG IMPLEMENTATION EVIDENCE** as fitness constraints on any inference the system performs or records; **RESEARCH ONLY** as philosophy
**Lenses:** Chalmers (conceptual distinction before functional modelling) · Williamson (modal logic as metaphysics) · Escher · Zero

---

## Source and provenance

| | |
|---|---|
| **Source documents** | `20260824-014614-chalmers-conscious-mind-conceptual-distinction-before-functional-modeling.md` (3,812 lines); `20260824-020936-chalmers-conscious-mind-research-extraction-protocol-rerun.md` (1,633 lines) — ⚠ **re-processing of the same book** after a project correction, with `20260824-021011-…-duplicate.md` byte-identical to it; `20260824-021526-williamson-modal-logic-as-metaphysics-extraction.md` (1,755 lines) |
| **Source works** | David Chalmers, *The Conscious Mind*; Timothy Williamson, *Modal Logic as Metaphysics* |
| **Provenance** | **`P3` BOOK_EXTRACTION → constraint derivation.** ⚠ ⟦VERIFIED⟧ 0 URLs |
| **Date / phase** | 2026-08-24 **01:46 / 02:09 / 02:15** · Phase 2 |
| ⚠ **Same-source caution** | `014614` and `020936` are two readings of **one book**; the second exists because the project judged the first insufficiently disciplined (recorded in the corpus as *"your correction is important"*). Their agreement is **not** independent corroboration |

---

## Finding 1 · The constraint family ⟦CLAIM ×4⟧

⟦C⟧ **Chalmers, first reading:** *"A domain concept, its observable behavior, its functional role, its
implementation, and **our knowledge of it** are different things."*
⟦C⟧ *"**Never infer semantic identity from functional similarity.**"*

⟦C⟧ **Chalmers, re-reading:** *"**Behavioral understanding must not be promoted to semantic understanding
without additional evidence.**"*
⟦C⟧ *"**No high-value architectural claim without an identifiable evidential bridge.**"*

⟦C⟧ **Williamson:** *"**Never infer ontology directly from representation without establishing the
intended semantic interpretation.**"*
⟦C⟧ Framing question: *"How do we **compare competing conceptual systems without prematurely declaring one
of them the truth**?"*

⟦INFERENCE⟧ **These four form one family, and it is a category the corpus had not previously isolated.**
`S1-F025` constrains what a Kernel *decision* may rely on; these constrain what any actor — human, model,
or mechanism — may *infer* from what it observes. The family reads as a licensing rule: an inference from
behaviour/function/representation to identity/semantics/ontology requires an **explicitly named bridge**.

⟦INFERENCE⟧ **The evidential-bridge requirement is the most directly applicable item.** Applied to this
corpus it is self-indicting: `S1-F008`'s nine capabilities were asserted as *"existing law"* with the
F-1…F-5 record absent; `S1-F019` listed Confidence as an *"established fact"* while conceding its
derivation is unknown; `S1-F013`'s three candidate models were proposed with evidence declared
insufficient. Each is a high-value architectural claim without an identifiable bridge. ⚠ **That mapping is
my inference**, not the documents'.

⟦L⟧ Comparison target only: v1.1 ⟨C-1⟩ (*canonical-form equality is a similarity claim, not an identity
determination*) and ⟨r4⟩ (*probability ≠ truth*, *canonicalization ≠ authority*, *low entropy ≠ certainty*)
are **instances of exactly this family** — each forbids an inference from a representational or measured
property to an epistemic one. ⟦INFERENCE⟧ **CONSISTENT**, and the corpus supplies the general rule of
which v1.1's rows are cases. That generalisation is the finding.

---

## Finding 2 · Williamson's comparison problem ⟦QUESTION⟧

⟦C⟧ *"How do we compare competing conceptual systems without prematurely declaring one of them the
truth?"*

⟦INFERENCE⟧ This is **precisely the problem this extraction is in**: eight Kernel formulations
(`S1-F009`…`F019`), six Knowledge genera, four incompatible relation classifications, and no adjudication
authority. The corpus contains a stated method question for its own condition, imported from modal logic —
and the answer it implies (compare without declaring) is what `S1-F013`'s UNRESOLVED verdict and this
extraction's preserve-don't-reconcile discipline do in practice.

---

## Finding 3 · Nyāya–Vaiśeṣika: superseded knowledge is still knowledge ⟦CLAIM⟧

From `20260824-023136` (Keith, *Indian Logic and Atomism*, `P3`, 2,207 lines):
⟦C⟧ *"**Negative and superseded knowledge are still knowledge about the evolution of the knowledge
state.**"*
⟦C⟧ Also carried: *vyāpti* — *"invariable concomitance"* (the universality test already recorded as
`S1-F023` family 3).

⟦INFERENCE⟧ **This is a substantive claim on the retraction/supersession question and a new position in
it.** Every earlier treatment asks what *happens to* a superseded claim (state? relationship? property?
removal?). This says the superseded claim **remains knowledge — about the evolution of the state**, i.e.
it changes what it is knowledge *of*, not whether it is knowledge. ⟦INFERENCE⟧ **Eighth angle on
retraction** (after F010, F014, F015, F018, F021, F022, F023), and the first that keeps the retracted
content inside the knowledge set. Bears on unruled `W:C-15`. **Not adjudicated.**

---

## Finding 4 · The research-direction document reasserts formalisation-before-scenarios ⟦DECISION⟧

From `20260824-022039` (`P1`/`P5`, 539 lines):
⟦C⟧ *"**Formalize the epistemic architecture we have extracted, then test it against technical/DDD/
architecture scenarios.**"*
⟦C⟧ Test question: *"**Can the KnowledgeOS epistemic model actually represent real engineering knowledge
without losing its important distinctions?**"*

⟦INFERENCE⟧ Note the ordering conflict with `S1-F026` (01:03), which said the next step is *falsification,
especially scenario-based DDD analysis*. Nineteen minutes later the sequence is *formalise, then test
against scenarios*. ⟦INFERENCE⟧ Two adjacent project statements ordering the same two activities
differently. Recorded, unresolved.
⟦INFERENCE⟧ The test question is however the sharpest **acceptance criterion** in the corpus: not *is the
model true* but *can it represent real engineering knowledge without losing distinctions* — a
representability test, and the natural companion to `S1-F026`'s preserve-distinctions rule.

---

## DDD interpretation

- The four constraints → **CANDIDATE fitness constraints / anti-inference policies**, not members.
- *Evidential bridge* → **CANDIDATE requirement on any recorded architectural claim** (and on justification
  paths).
- *Superseded knowledge as knowledge-about-evolution* → **CANDIDATE reading of history/supersession**.
- *Representability without distinction loss* → **CANDIDATE acceptance test**.

---

## Relationship to previous Session-1 findings

- **`S1-F025`** — sibling constraint family: F025 constrains the *decision procedure*, F027 constrains
  *inference licensing*. Together they are the corpus's only two mechanism-independent constraint sets.
- **`S1-F008`/`F013`/`F019`** — each fails the evidential-bridge requirement (my inference).
- **`S1-F024`** — Chalmers/Williamson/Keith rows belong to that register's pattern; recorded here instead
  because they yield **constraints**, not merely distinctions.
- **`S1-F010`…`F023`** — eighth angle on retraction, first to retain the superseded content as knowledge.
- **`S1-F026`** — ordering conflict on falsification-vs-formalisation, 19 minutes apart.
- **`S1-F013`** — Williamson's comparison problem is the method question for F013's condition.

---

## Classification, confidence, open questions

**Type:** CLAIM ×4 (constraints) · QUESTION (comparison problem) · CLAIM (superseded knowledge) ·
DECISION (research direction, conflicting).
**Confidence:** high on quotations; **medium-high** on the family's usability (mechanism-independent and
testable); ⚠ the self-indicting application to F008/F013/F019 is **my inference**.
**Open questions:** what counts as an *identifiable evidential bridge*? · does the licensing family belong
in a non-collapse register or as fitness tests? · is superseded knowledge inside or outside the knowledge
set (`W:C-15`)? · formalise first or falsify first?

**Status:** OPEN. Constraints recorded, not adopted; nothing adjudicated.
