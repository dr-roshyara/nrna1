# Research State — after F0001–F0005

**Corpus read:**
- 5 files, read in the commissioned order.
- Stated dates: F0001 and F0005 are dated 2026-08-02; F0002–F0004 are dated 2026-08-03.
- These files are a *late snapshot*, taken at the end of strategic discovery. They are not the programme's origin.

**Companion files:**
- `observations.md` (O1–O38)
- `patterns.md` (P1–P8)
- `contradictions.md` (C1–C10)
- `hypotheses.md` (H1–H8)
- `theory-candidates.md` (T1–T4)

---

## Current understanding

**What the corpus says KnowledgeOS is (the espoused view):**
- a reusable engineering platform that *creates* one Product Knowledge Space (PKS) per product;
- governed by human authorities;
- currently a Supporting Subdomain of a single product, PublicDigit, and gated on a second adopter.

**What the corpus shows KnowledgeOS doing (theory-in-use):**
- tracking the **epistemic and institutional status of claims**;
- counting evidence;
- grading sources;
- separating evidence from authority;
- treating documents as derived projections;
- correcting itself by appending corrections.

**The current corpus suggests** that the second description is far better evidenced than the first (O38, H5).

---

## Strong recurring patterns

1. **P2 — two independent axes, evidence vs authority.** Independence is shown in both directions (O19, O20). This is the strongest candidate invariant.
2. **P1 — evidence ceiling.** It appears at two scales: internal instance counts and external source grades.
3. **P5 — realization levels.** Most corrections separate two levels that had been merged.
4. **P6 — reflexivity.** The corpus applies its own rules to itself, and corrections are appended alongside what they correct.
5. **P7 — distinctive claims at n≈0.** Generation and the canon-level back-edge are at or near zero.

---

## Important unresolved questions

- **What bears a status: concepts, or propositions about concepts?** (C3) This decides what the atoms of any theory are.
- **What exactly are F0003's four partial orders?** Do they coincide with the evidence × standing × realization axes found here?
- **Is reasoning or review an admissible transition trigger?** (C7) Is the transition rule asymmetric (H2a)?
- **Is the platform advisory by design (H7) or by immaturity?** Canon contains evidence both ways: C-8 "AI recommends" vs PGP "fail closed".
- **Is METHOD/BINDING/EVIDENCE central, or a local tool?** (H4) It is absent from 3 of the 5 files.
- **How is a "traversal" defined?** (C1) Every loop measurement depends on it.

---

## Contradictions (the ones that matter most)

- **C1** — the loop is "never traversed" vs n≈3 vs formal n=1. This is measurement-definition dependence.
- **C3** — "nothing in two states" vs concepts with two standings. This concerns the choice of atoms.
- **C5** — "method demonstrated portable" at n=1 vs the corpus's own rule that n=1 yields only a candidate.
- **C7** — "never by re-reasoning" vs demotion on review.
- **C10** — the counts inside the corpus are not self-consistent. Treat every count as a claim, not as data.

---

## Candidate hypotheses

| ID | Hypothesis | Status |
|---|---|---|
| H1 | two independent axes (evidence / authority) | **strongly supported by the current corpus** |
| H2 | event-sourced knowledge ledger | plausible |
| H2a | asymmetric transitions | speculative |
| H3 | corrections separate realization levels | plausible |
| H4 | METHOD/BINDING/EVIDENCE is the core | unresolved / weakened |
| H5 | distinctiveness rests on n≈0 edges | plausible |
| H6 | Genesis is a missing base case | plausible |
| H7 | advisory by design | unresolved |
| H8 | three-valued verdict logic | speculative |

---

## Candidate theory structures

**Structures that fit the corpus's behaviour:**
- **Order theory:** status is a product of partial orders (evidence × standing × realization), not a single stack. The corpus itself rejected the single stack (R-4).
- **Transition systems and event sourcing:** state = fold(events); documents = projections.
- **Modal logic:** two operators, E (evidenced) and A (ruled). E ⇏ A and A ⇏ E. Promotion requires E ≥ θ as a precondition of an A-act.
- **Induction and fixed points:** governance without an axiom has an empty least fixed point, which is Genesis (P8).

**Structures that fit only the corpus's stated purpose:**
- **λ-abstraction / compiler:** Method as a closed term, Binding as a parameter, Evidence as traces (P4, T3).

**Statistics:**
- The corpus's n-thresholds are *governance conventions*, not inferential warrant.
- P3's conditions (exercised by a non-originator; ≥2 instances) are independence and replication criteria.
- F0004 admits it has no control group ("no unguided baseline").
- The ratio of rejected to candidate items is **not** a severity measure.

**Current ranking** (see `theory-candidates.md`):
- **T2 (knowledge-state system)** has the best evidential support.
- **T3 (generator)** is the espoused purpose, but it is unevidenced.
- **T4** is premature.

**No theory is established.**

---

## Evidence gaps

1. Definitions of the four partial orders and the four progression kinds.
2. ES-006.1: the actual promotion ladder and the thresholds θ.
3. The Phase B blind review and the Operational Evidence Register, which bear on C1.
4. Any attempt at `generates → PKS`, which bears on H5 and T3.
5. Whether METHOD/BINDING/EVIDENCE recurs in later work (H4).
6. A shared definition of a *claim*, the status bearer (C3).

---

## Recommended next research

**Files.** These are named in the pilot files and exist in the list. I have **not** read them. They are ranked by how many open questions they address:
1. `KnowledgeOS_Ontology_Discovery.md` — the four partial orders (Q on T2's formal fit)
2. `KnowledgeOS_Engineering_Progression_Model.md` — provenance × standing and the four progression kinds (H1, H2a)
3. `KnowledgeOS_Meta_Model_Discovery.md` / `KnowledgeOS_Meta_Model.md` — ELEMENT = KIND × dimensions (C3: what are the atoms?)
4. `KnowledgeOS_Operational_Evidence_Register.md` and `KnowledgeOS_Independent_Review_Phase_B.md` — the definition of traversal (C1)
5. `KnowledgeOS_Relationship_Ontology.md` / `KnowledgeOS_Relationship_Validation_Matrix.md` — the `generates → PKS` edge (H5)

**Method.** Keep the current reading order, but record each file's stated date, because reading order and date order already diverge.

**Theoretical areas:**
- the order dimension of the status space;
- modal / deontic formalization of E and A;
- the event-sourced invariants I-a, I-b and I-c;
- a pre-registered classification of corrections, to test H3.
