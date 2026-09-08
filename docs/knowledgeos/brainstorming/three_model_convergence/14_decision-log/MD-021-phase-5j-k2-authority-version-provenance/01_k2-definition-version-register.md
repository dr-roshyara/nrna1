# Phase 5J — K-2 Definition/Version Register (extends Phase 5I's `01`, records not merged)

| # | Source | Seq | Path | Filename date | Exact definition | Parent | Predecessor | Successor | Explicit reference | Explicit revision language | Explicit supersession language | Governance status | Normative/descriptive/executable/research | Confidence |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| D1 | D285-1 §2 | 1006 | `.../research/D285-1-STATE-ONTOLOGY-MATRIX.md` | 2026-08-31 (header date) | `Assertion→{Proposition,Entity,Evidence,Context,Time,Provenance}` | none stated | none stated | none stated | none | Yes — ¶43 self-revises an *adjacent* claim (the "lanes agree" retraction), not this unpacking itself | None for this specific unpacking | Descriptive, research | Descriptive | High |
| D2 | D285-6 §3 | 1007 | `.../research/D285-6-STATE-CANDIDATE-EVALUATION.md` | 2026-08-31 | `Assertion→{Proposition,Entity,Observation}+{id,c,t,Π}` | none stated | none stated | none stated | none | Yes — §5 explicitly revises the *equality claim* around this unpacking, not the field set itself | None | Descriptive, research | Descriptive | High |
| D3 | `t285_reconcile.py`, `ASSERTION_CONTAINS` | — | `.../exec/t285_reconcile.py` | **No internal date; git shows only the 2026-09-06 bulk import** | matches D1 | none stated in code | none stated | none stated | none | none | none | Executable | Executable | High |
| D4 | `t285_equality.py`, `UNPACK` | — | `.../exec/t285_equality.py` | same as D3 | matches D2 (partially — see `01` of Phase 5I) | none stated | none stated | none stated | none | Docstring self-corrects D285-6's own *equality claim*, not the field set | None | Executable | Executable | High |
| D5 | `e_equality.py`, `A(P,e,c,t,Pi)` | — | `.../exec/e_equality.py` | same as D3 | narrower, 5-param constructor | none stated | none stated | none stated | Cites "Reviewer B's mandate E3/E4/E5... Step 246" — the **only** cross-reference to a specific external step found among all 5 records | none | none | Executable | Executable | High |
| S1 | Step 272A | 911 | `.../phase_measure_theory/20260830-224242_step_272a_...md` | 2026-08-30 | **Does not define Assertion's field structure** — defines `𝒪_sem` (operations) and `Qualify(o,c,π)→e` (3-arg) | none stated | Step 271 (explicit "Predecessor" header field) | none stated | Cites Step 271 by name in its own header | none regarding Assertion | none | Research derivation | Descriptive | High |
| S2 | Step 272B | 912 | `.../phase_measure_theory/20260830-225058_step_272b_...md` | 2026-08-30 | **Does not define Assertion's field structure** — defines `Σ_E`/epistemic-status structure, `Assess(A,E,Π,C,π)→AssessmentResult` | none stated | (implicitly follows 272A, same day, sequential numbering) | none stated | No explicit citation of 272A found | none regarding Assertion | discusses supersession as a **modeled domain concept**, not as a relationship between corpus documents | Research derivation | Descriptive | High |

## New finding this phase

**S1 and S2 are silent on the Assertion field-set conflict.** Neither defines `Assertion` as a
structured object at all — they are upstream sources for K-2's *operations* and *epistemic-status
structure*, not its *carrier shape*. This means the two documents D285-1/D285-6 most plausibly *should*
have drawn their own `Assertion` definitions from (given they are dated one day later, 2026-08-31, and
sit in the same research package) **do not themselves resolve the question** — the conflict's true
origin remains outside the set of documents this reconstruction has now read.

## Discipline note

**7 records, still not merged.** D5's own citation of "Reviewer B's mandate E3/E4/E5... Step 246" is
the single clearest explicit cross-reference found among all 7 — recorded precisely, not generalized
into a claim that the other 6 records are similarly well-cross-referenced.
