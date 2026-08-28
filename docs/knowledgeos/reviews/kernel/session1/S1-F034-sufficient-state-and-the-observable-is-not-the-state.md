# S1-F034 · **"Sufficient state"** as the corpus's most operational temporal concept — and *"do not confuse the observable representation with the underlying state"*

**Finding ID:** S1-F034
**Finding class:** CONCEPT (sufficient state) + DISTINCTION (observable ≠ state; language as projection) + a legitimacy precondition for inference + one new non-collapse in the Assessment family
**Status:** OPEN
**Implementation relevance:** *sufficient state* = **POSSIBLE IMPLEMENTATION CANDIDATE**; *what must exist before inference is legitimate* = **STRONG IMPLEMENTATION EVIDENCE**; the formal apparatus = **RESEARCH ONLY**
**Lenses:** Temporal · Zero · Identity · Evidence · Topological (applied in-document)

---

## Sources and provenance

| Document | Source work | Lines | Prov |
|---|---|---|---|
| `20260824-142848-astrom-stochastic-control-theory-initial-assessment.md` | Karl J. Åström, *Introduction to Stochastic Control Theory* | 165 | `P3` |
| `20260824-151311-topological-extraction-of-astrom-stochastic-control-theory.md` (+ `../20260824-145631` cross-folder duplicate) | same | 842 | `P4` |
| `20260824-154256-fraser-hidden-markov-models-and-dynamical-systems-extraction.md` (+ `155130` duplicate) | Andrew Fraser, *HMMs and Dynamical Systems* | 1,001 | `P3` |
| `20260824-140348` · `141231` · `141924` | Hastie et al. *ESL*; Bishop *PRML* | 2,351 / 2,041 / 2,245 | `P3` |

⚠ ⟦VERIFIED⟧ 0 external URLs. Dates 2026-08-24 **14:03 – 15:13** · Phase 2.

---

## Finding 1 · *Sufficient state* ⟦CONCEPT — new to Session 1⟧

⟦C⟧ Åström's loop, quoted as the transferable structure:
> ⟦C⟧ *"uncertain environment → observation → **sufficient state** → estimation → decision → action → new
> observation"*
⟦C⟧ Translated to the project's question: *"**What does it mean to maintain a sufficient, uncertainty-aware
state of knowledge from which a governed engineering decision can be made?**"*

⟦INFERENCE⟧ **This is the most operational temporal concept in the corpus.** Everything else in the temporal
family asks *what changes* (`S1-F015`'s mutability table), *what persists* (`S1-F022`'s identity ≠
continuity), or *what absence means* (`S1-F023`). *Sufficient state* asks a different question: **what is
the minimum that must be retained so a governed decision remains possible?** That is a *retention*
criterion, not a representation criterion.

⟦INFERENCE⟧ Two consequences the corpus has not drawn:
- It gives a **principled answer shape** to the question `S1-F001` measured and found unanswerable —
  *"was this authority valid at a particular historical point in time?"* A sufficient-state criterion says
  what must have been retained for that question to be answerable at all.
- It is **decision-relative**, like `S1-F029`'s abstention threshold (*"a sufficiently safe decision"*).
  ⟦INFERENCE⟧ Two independent sources now make an epistemic adequacy criterion depend on the **decision it
  must support**, not on the claim alone. That is a recurring shape worth flagging: *adequacy is relative to
  use.*

⟦L⟧ Comparison target only: v1.1 has forward-only history and *nothing overwritten* (§9), which guarantees
retention but does not define **sufficiency**. Classified **NOT ADDRESSED**; recorded as a research concept.

---

## Finding 2 · The observable is not the state ⟦DISTINCTION⟧

⟦C⟧ Fraser: *"**Do not confuse the observable representation with the underlying state.**"*
⟦C⟧ And, applied: *"**Language is a projection of meaning, not the container of meaning.**"*

⟦INFERENCE⟧ ⟦L⟧ This is v1.1's *Expression ≠ Meaning* and *Representation ≠ Identity* (§15) reached from
state-space modelling — **CONSISTENT**, and the fourth independent route to it. ⟦INFERENCE⟧ But the second
sentence adds something: it uses *projection* in the **epistemic** sense (language projects meaning) — a
**sixth** occurrence of that word's senses, and the first where *projection* names the
representation→meaning relation rather than a view, a measure, a read-model or a derived attribute.
⚠ Registered as a **VOCABULARY COLLISION** addition; **not merged** with any earlier sense.

---

## Finding 3 · A legitimacy precondition for inference ⟦CONSTRAINT⟧

⟦C⟧ ESL reading: *"**What must exist before inference is legitimate?**"*
⟦C⟧ Method frame applied to every technique: *"What problem does each technique solve? What concept does it
embody? **What responsibility does it have? What invariant does it preserve?**"*
⟦C⟧ PRML reading: *"**'Does the evidence justify the additional model complexity?'**"* · ⟦C⟧ *"Numerical
stability is part of **assurance**."*

⟦INFERENCE⟧ The first question is the same shape as `S1-F027`'s evidential-bridge requirement and
`S1-F029`'s *no inference without explicit basis* — **third arrival**, here as a **precondition** rather
than a prohibition. ⟦INFERENCE⟧ The four-part method frame is notable independently: it is the only place in
the corpus where each imported technique is required to declare **which invariant it preserves** — i.e. the
corpus applies its own invariant-first discipline to borrowed machinery.
⟦INFERENCE⟧ *"Numerical stability is part of assurance"* quietly enlarges assurance from an epistemic to an
**implementation** property. Recorded; it is the only such claim found, and it is **IMPLEMENTATION
EVIDENCE** rather than research.

---

## Finding 4 · A new non-collapse in the Assessment family ⟦DISTINCTION⟧

⟦C⟧ *"**Assessment is not the same thing as Observation, Evidence, Recommendation, or Decision.**"*

⟦INFERENCE⟧ A **five-way** non-collapse, and it completes the chain begun in Phase 1: DOC-1's
`Observation → Recommendation → Decision → Outcome → Assessment` (ledger KCON-003) asserted the *sequence*;
this asserts the members are **mutually irreducible**. ⟦INFERENCE⟧ It also bears on `S1-F014`: if Assessment
is distinct from Evidence and Decision, then `INSUFFICIENT_EVIDENCE` as an *assessment* is a different kind
of thing from `CONTESTED` as a *relationship* — which is exactly the classification F014 proposed and
`S1-F018` disputed. Recorded as support for the assessment/relationship split, **not** as its resolution.

---

## Finding 5 · Lens honesty and the Zero-lens definition ⟦METHOD⟧

⟦C⟧ Topological reading: *"**Topology is our analytical lens; it is not presented by Åström as the
organizing theory of the book.**"*
⟦C⟧ Zero-lens definition (`152415`): *"**remove as much prior structure as possible and ask what can be
recovered from the source/data alone**"* · *"If we temporarily assume nothing about what the structure is
supposed to be, what structure can we actually observe?"*

⟦INFERENCE⟧ The first is the corpus separating **its lens** from **its source's claims** in a single
sentence — the discipline `S1-F022` executed at scale, here as a one-line disclaimer. ⟦INFERENCE⟧ The second
is significant for provenance: the Zero lens is **defined on 2026-08-24 at 15:24** while it has been in
continuous use since 2026-08-23 (census T-6, a ~17-hour definition lag). This document is that definition.
Every Zero-lens conclusion recorded before it — including `S1-F013`, `S1-F016`, `S1-F021` — used an
**undefined** instrument. ⚠ Recorded as a **provenance caveat on the Zero-derived findings**, not as
invalidation.

---

## Finding 6 · Sanskrit/Pāṇinian material contributes an identity-preservation question ⟦QUESTION⟧

⟦C⟧ `151951`: *"How can meaning be represented, transformed, combined, and expressed **without losing
semantic identity**?"* · ⟦C⟧ *"**When something changes its form, how do we know that its identity and
meaning have been preserved?**"*
⟦C⟧ `151337`: *"What kinds of things exist in mathematical thought?"* — an ontology-inventory question.
⟦C⟧ `152751`: *"Can complex problem-solving vocabulary be **decomposed into a small number of reusable
semantic operations**?"* — and, disciplined: *"**Does the text actually contain ten statistically distinct**
problem-solving mechanisms?"*

⟦INFERENCE⟧ The second Pāṇinian question is the **fifth arrival** at identity-through-change (after
`S1-F015`, `F022`, `F026`, `F031`) — this time as a *transformation-invariance* question, which is the
Escher-lens role `S1-F021` assigned. ⟦INFERENCE⟧ `152751`'s second question is methodologically the sharpest
in this batch: it refuses to accept the source's own taxonomy count without checking distinctness — the
same *ativyāpti* discipline as `S1-F023`.

---

## DDD interpretation

- *Sufficient state* → **CANDIDATE retention criterion** (what must be kept for a governed decision to
  remain possible).
- `Assessment` → **CANDIDATE concept irreducible to Observation/Evidence/Recommendation/Decision**.
- *observable ≠ state*; *language projects meaning* → **CANDIDATE non-collapses**; sixth *projection* sense.
- *What must exist before inference is legitimate* → **CANDIDATE precondition** on any inference recorded.
- Numerical stability → **CANDIDATE assurance property at implementation altitude**.

---

## Relationship to previous Session-1 findings

- **`S1-F001`** — *sufficient state* is the first concept that could make its measured question answerable.
- **`S1-F029`** — second source making adequacy **decision-relative**.
- **`S1-F027`** — third arrival at the inference-legitimacy family, as precondition rather than prohibition.
- **`S1-F014`/`F018`** — supports the assessment-vs-relationship split without resolving it.
- **`S1-F013`/`F016`/`F021`** — ⚠ all used the Zero lens **before** it was defined (defined here).
- **`S1-F015`/`F022`/`F026`/`F031`** — fifth arrival at identity-through-change, as transformation
  invariance.
- **`S1-F032`** — same regime-not-ontology placement for ESL/PRML; no new placement claim.

---

## Classification, confidence, open questions

**Type:** CONCEPT (sufficient state) · DISTINCTION ×3 · CONSTRAINT (inference legitimacy) · METHOD ×2 ·
QUESTION (transformation invariance) · VOCABULARY COLLISION (sixth *projection* sense).
**Confidence:** high on quotations; *sufficient state* is **single-sourced** (Åström) and marked accordingly.
**Open questions:** what is the sufficiency criterion for retained state? · is adequacy relative to the
decision or to the claim? · does the Zero-lens definition lag affect earlier Zero-derived conclusions? ·
how is transformation-invariance of identity established (`W:C-8`)?

**Status:** OPEN. Nothing adopted; nothing adjudicated.
