# S1-F006 · Semantic measurement (SNF, entropy, canonical form) is **relative to a knowledge base**, not an absolute property

**Finding ID:** S1-F006
**Finding class:** EXTERNAL RESEARCH FINDING (mechanism/measurement altitude)
**Status:** OPEN
**Lenses:** Mathematical · Vāṇī/semantic · Identity · Evidence

---

## Source and provenance

| | |
|---|---|
| **Source document** | `brainstorming/kernel/20260822-161933-snf-formula-research-review-and-measurement-framework.md` (1,562 lines) |
| **Document type** | research review / improvement analysis |
| **Provenance** | **`P2` EXTERNAL_RESEARCH** — self-declared *"Researcher Role: Independent Research Architect"*; cites ACL Anthology, URDNA2015, PERIN |
| **Date in document** | 2026-08-22 (matches filename) |
| **Phase** | 2 (earliest document in the `kernel/` corpus) |
| **Relationship to earlier documents** | none stated |

---

## Extracted findings

**1 · Semantic entropy is knowledge-base-dependent** ⟦FACT (external research)⟧
⟦C⟧ *"semantic entropy can be defined as uncertainty in semantic interpretation **relative to a
knowledge base**, not just Shannon entropy over clusters."*
Adopted formula: `H_sem(K|𝒦) = -Σ P(s|𝒦)·log₂ P(s|𝒦)` — explicitly conditioned on `𝒦`.
⟦C⟧ Rated *"Strong"* research evidence; recommended with LLM-based estimation.

**2 · Transformation stability should be permutation-invariant** ⟦FACT (external research)⟧
⟦C⟧ *"Permutation-invariant semantic parsing achieves state-of-the-art results by predicting all
semantic graph nodes in parallel without fixed order."* Formula:
`T_snf = 1 − (1/m)·Σ NSID(SNF(K), SNF(T_j(K)))`.

**3 · The measurement stack** ⟦MODEL (proposal, mechanism altitude)⟧ — Gödel fingerprint ·
knowledge-base-dependent semantic entropy · NSID semantic distance · hash-based canonical labeling
(URDNA2015) · permutation-invariant transformation stability.

---

## Why it matters

⟦INFERENCE⟧ **The load-bearing consequence is not the formulae — it is the conditioning.** If semantic
entropy is defined *relative to a knowledge base* `𝒦`, then:

- the same expression has **different** semantic entropy under different knowledge bases;
- entropy is therefore a **participant/context-relative measurement**, not a property of the
  expression;
- so **no entropy value can carry absolute epistemic weight**.

⟦L⟧ Comparison target only: v1.1 ⟨r4⟩ records *"Low entropy ≠ Certainty"* — *"a highly-normalized /
low-entropy form is **not thereby more certain**."* ⟦INFERENCE⟧ This document supplies the *mechanical
reason* that row is correct: the quantity is KB-relative. **Classification: CONSISTENT** — and it
strengthens an existing v1.1 interpretation row from the mechanism side rather than challenging it.

⟦INFERENCE⟧ Same reasoning applies to canonical form: URDNA2015 hash equality is equality *of a
canonicalisation*, which is why ⟦L⟧ ⟨C-1⟩ holds that *canonical-form equality is a similarity claim,
not an identity determination*. This document does **not** contest that; it operates entirely below it.

---

## DDD interpretation

- SNF / semantic entropy / NSID / canonical labeling → **CANDIDATE MECHANISM** (measurement), not
  domain concepts. ⟦L⟧ v1.1 §16 already places normalizers and SNF at **mechanism/representation**
  altitude.
- `H_sem(K|𝒦)` → **CANDIDATE VALUE OBJECT** *if* ever recorded — but it would be candidate-side
  metadata, which ⟦L⟧ ⟨r4⟩ says is *"port-contract vocabulary, never aggregate members"*.
- **No entity, no aggregate, no invariant is established by this document.**

---

## Relationship to previous Session-1 findings

- **`S1-F002`** (vocabulary instability): consistent — measurement terms need the same
  preserve-don't-normalise discipline.
- **`S1-F005`** (*mathematics describes, the kernel protects*): **this document is an instance of the
  described side.** It proposes no kernel role for any of its measures. Convergent.
- **`S1-F004`** (no established kernel category): unrelated; this is a measurement literature with
  real external grounding, unlike the kernel term.

⟦INFERENCE⟧ Notable: the **earliest document in the `kernel/` corpus is a measurement paper at
mechanism altitude** — the corpus does not begin with a Kernel definition.

---

## Classification, confidence, open questions

**Type:** FACT (external research) + MODEL (measurement proposal). **Not** a Kernel definition.
**Confidence:** high on what the document says; the KB-relativity consequence is marked INFERENCE.
**Open questions:**
- Is `𝒦` in `H_sem(K|𝒦)` a participant's knowledge base, an organisational one, or the whole corpus?
  The document does not say — bears on later participant-relative material.
- `W:OQ-2` (may SNF-equivalence be recorded as an EvidenceLink without becoming an identity
  mechanism?) is **untouched** by this document. It measures; it makes no admission claim.

**Status:** OPEN. Nothing adjudicated. No Kernel candidate proposed.
