# S1-F038 · **"Store the substrate; compute the measure"** — and the corpus's sharpest acceptance question: *"What did we know at 13:47?"*

**Finding ID:** S1-F038
**Finding class:** DESIGN PRINCIPLE (substrate/measure split) + ACCEPTANCE TEST (point-in-time reconstruction) + a projection-consistency invariant + an explicit refusal of possible-worlds storage
**Status:** OPEN
**Implementation relevance:** substrate/measure split = **STRONG IMPLEMENTATION EVIDENCE**; point-in-time test = **STRONG IMPLEMENTATION EVIDENCE**; projection-consistency invariant = **POSSIBLE IMPLEMENTATION CANDIDATE**
**Lenses:** Temporal · Zero · Evidence · DDD · Gärdenfors/belief revision

---

## Sources and provenance

| Document | Source work | Lines | Prov | Time |
|---|---|---|---|---|
| `20260825-133456-gardenfors-knowledge-in-flux-epistemic-state-as-primary-model.md` | Gärdenfors, *Knowledge in Flux* | 1,563 | `P3` | 13:34 |
| `20260825-135642-evolving-knowledge-space-imagination-additions-not-yet-architecture.md` | — | 766 | `P5` | 13:56 |
| `20260825-140251-per-agent-projection-divergence-without-divergent-reality.md` (+ `145918` dup) | — | 463 | `P5` | 14:02 |
| `20260825-171853-c15-reframed-knowledge-at-a-time-in-evolving-knowledge-space.md` | — | 854 | `P5` | 17:18 |
| `20260825-172121-knowledge-element-versus-state-and-what-can-be-quantified.md` | — | 1,781 | `P4`/`P5` | 17:21 |

⚠ ⟦VERIFIED⟧ 0 external URLs. Phase 2.

---

## Finding 1 · *Store the substrate; compute the measure* ⟦DESIGN PRINCIPLE⟧

⟦C⟧ *"**Store the substrate; compute the measure.**"*
⟦C⟧ Context: *"How concrete is an answer to a question?"* · *"**coverage of a declared finite inquiry
boundary**."*

⟦INFERENCE⟧ **This is the constructive resolution of the corpus's longest-running failure.** The
quantification programme repeatedly proposed measures *as members* — entropy (`S1-F006`), Confidence
(`S1-F008`, `S1-F019`), a Knowledge Score (foreclosed at `S1-F032`), a measure-theoretic projection
(`S1-F029`-adjacent thread). This principle says the **substrate is preserved and every measure is
derived**, which:
- satisfies `S1-F032`'s *"there is no single Knowledge Score"* without abandoning measurement;
- matches ⟦L⟧ v1.1 ⟨r4⟩ exactly — interpretation probabilities are *port-contract vocabulary, never
  aggregate members* — **CONSISTENT**, and gives the general rule of which ⟨r4⟩ is a case;
- bears on unruled **`W:C-11`** (Confidence derivability) and **`W:OQ-5`** (should Confidence be an
  aggregate member at all?) by answering *derive, don't store* — ⚠ **as research evidence only, not a
  ruling**.

⟦INFERENCE⟧ Note the qualifier that makes it usable: coverage is relative to a **declared finite inquiry
boundary**. Measurement is therefore scoped by a stated question, not global — the same *adequacy relative
to use* shape recorded at `S1-F034`.

---

## Finding 2 · The acceptance question ⟦TEST⟧

⟦C⟧ *"**'What did we know at 13:47?'**"*

⟦INFERENCE⟧ **The single most concrete acceptance test in the corpus.** It is checkable, it is temporal, and
it is the question `S1-F001` measured the system as unable to answer (0/20 grants carry validity
information). ⟦INFERENCE⟧ It also operationalises `S1-F034`'s *sufficient state*: sufficiency is whatever
must be retained for this question to be answerable at an arbitrary past instant.
⟦INFERENCE⟧ Together with `S1-F036`'s *why/when did X change*, the corpus now has **two point-in-time
acceptance tests** and one representability test (`S1-F027`). None has been run.

---

## Finding 3 · A projection-consistency invariant ⟦CLAIM — candidate invariant⟧

⟦C⟧ *"**Different projections must never contain contradictory propositions.**"*
⟦C⟧ *"A projection becomes more informative or structurally adequate **relative to a defined inquiry and
boundary**."*

⟦INFERENCE⟧ This answers one of the three questions I recorded at S1-F035-era as *asked by no document*:
**can two projections differ without knowledge differing?** The answer given is *yes, in informativeness;
no, in consistency* — divergence is permitted in **coverage**, forbidden in **content**.
⟦INFERENCE⟧ It is a genuine candidate invariant and the first one proposed **about projections** rather than
about the aggregate. ⚠ Single-sourced; and it sits in tension with `S1-F037`'s participants who *transform*
the space (if participants transform, contradictory projections could be legitimate). Recorded, unresolved.

---

## Finding 4 · Gärdenfors: epistemic state as the primary object — and an explicit storage refusal ⟦CLAIM⟧

⟦C⟧ *"**What is the minimal representation of an epistemic state, and how does that state change?**"*
⟦C⟧ *"**KnowledgeOS should not store an epistemic state as 'possible worlds.'**"*
⟦C⟧ Framing: *"What should the KnowledgeOS Kernel contain, and what should be **derived outside it**?"*

⟦INFERENCE⟧ **The explicit refusal matters:** it is the corpus rejecting a *specific formal storage model*
by name — and it converges with the Fagin thread's own resolution (possible-worlds semantics as a
supported regime, not the Kernel). ⟦INFERENCE⟧ Gärdenfors also supplies the belief-revision framing the
corpus lacked: the question is not *what is knowledge* but *what is the minimal state representation and its
change law* — which is the same contain/derive split as Finding 1, at state rather than measure level.

---

## Finding 5 · Two further temporal claims ⟦CLAIM⟧

⟦C⟧ *"**Knowledge is always knowledge-at-a-time within an evolving Knowledge Space.**"*
⟦C⟧ *"**Absence must not silently become an epistemic state.**"*
⟦C⟧ *"What knowledge is relevant/visible/applicable **from this perspective at this time**?"*
⟦C⟧ *"**The Knowledge Space is not necessarily the same thing as any one model of it**"* · ⟦C⟧ *"KnowledgeOS
should preserve and serve that space, **rather than pretending to own the whole of knowledge**."*

⟦INFERENCE⟧ *Absence must not silently become an epistemic state* is the **ninth** angle on the
absence/retraction family and the closest to ⟦L⟧ ⟨Z-1⟩ (*absence of a constitutive prerequisite is not an
epistemic state*) — **CONSISTENT**, reached independently.
⟦INFERENCE⟧ *"Not pretending to own the whole of knowledge"* is a **scope-humility claim** with a boundary
consequence: KnowledgeOS serves a space it does not contain, which is compatible with `S1-F020`'s
KnowledgeOS ⊃ KnowledgeCore ⊃ Kernel nesting but adds an outer layer the ADR does not name.
⚠ **Provenance caution retained:** this document is the one whose attribution of the Knowledge-Space model
to *"your earlier conceptual model"* was tested and **not supported** by the Phase 1 corpus (recorded
earlier in the programme's contradiction map). Its claims stand as Phase 2 positions.

---

## DDD interpretation

- **Substrate** (stored) vs **measure** (computed) → **CANDIDATE storage policy**, ⟦L⟧-consistent.
- *What did we know at t* → **CANDIDATE acceptance test**.
- *Projections must not contradict* → **CANDIDATE INVARIANT over projections**.
- Epistemic state → **CANDIDATE primary object with a change law**, not possible-worlds-stored.
- *Declared finite inquiry boundary* → **CANDIDATE scoping construct for any measure**.

---

## Relationship to previous Session-1 findings

- **`S1-F032`** — substrate/measure is the constructive counterpart of *no single Knowledge Score*.
- **`S1-F001`/`F034`** — the 13:47 test operationalises *sufficient state* and targets the measured gap.
- **`S1-F035`** — answers the *can projections differ?* question left open there.
- **`S1-F037`** — tension: participants who *transform* the space vs projections that must not contradict.
- **`S1-F036`** — second point-in-time acceptance test alongside *why/when did X change*.
- **`W:C-11` / `W:OQ-5`** — *derive, don't store* is research evidence bearing on both; not a ruling.

---

## Classification, confidence, open questions

**Type:** DESIGN PRINCIPLE · TEST ×1 · CLAIM ×5 · candidate INVARIANT.
**Confidence:** high on quotations; the substrate/measure principle is **the most implementable positive
result in the corpus** and is ⟦L⟧-consistent; the projection-consistency invariant is single-sourced.
**Open questions:** what is the substrate, exactly? · can participants transform the space without producing
contradictory projections? · has any point-in-time test ever been run? · what is the minimal epistemic-state
representation if not possible worlds?

**Status:** OPEN. Nothing adopted; nothing adjudicated.
