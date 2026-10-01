# 1bk reconciliation: the R4 minimal formal theory (r2), sealed as REVIEWED DRAFT, pending R5

| Round | Verdict | Blocking |
|---|---|---|
| r0 | FAIL | S-1 (false lemma), S-2 (false remark); P-1 proof gap |
| r1 | FAIL → human | RG-1: generic proofs presupposed the frame axioms |
| r2 (human act F-LOG-0221) | **PASS_WITH_LIMITATIONS** | 0; no regressions; countermodels 12/12 each violate exactly one assumption |

## Core results (model-relative; same-family review; not yet independently verified)
- **Prop 4.1 (D1, generic).** Under A1, A2 and A5, every history in R_B has an O-equivalent history in H-D ∩ H-B.
  - "The work violated an unchanged norm" and "the norm state was different" are O-equivalent: (ii) and (iii).
  - Only the "without a recorded act" clause needs A7.
- **Lem 4.2 (robustness).** D1 holds even for the observation of every coordinate except σ and f, i.e. **even if the reference and mode coding were perfectly reliable**. **So the non-identifiability (H5) is logically independent of the instrument failure (1bj).**
- **Cor 4.3.** Any separating observation must depend on σ (the norm state) or on f (entry into force). This is a necessary condition. Independence and target mapping are **not** derived from it.
- **Props 4.5 / 4.6.** Sufficient conditions on an additional observable that would exclude H-B, or (symmetrically) H-D. No such observable is claimed to exist (H14).
- **Def 5.1 / Conj 5.5.** *Performative ambiguity*: formally, the fibre of O contains histories where a record constitutes the act and histories where it reports it.
  - Conj 5.5 ties it to the coding failure, with falsifiers F-a/F-b/F-c fixed in advance (κ ≥ 0.67 rule; a sealed S_mode).
  - **It is a conjecture, not established.**
- **R5 spec:** a finite universe U (about 4.5e6 raw states) with checks K1–K12 and expected outcomes. The generic statements cannot be tested in U (§7.3).

## Remaining limitations (non-blocking)
- one dead step in the proof of 4.1(i′);
- the wording of one Table 4.6 row;
- a changelog typo fixed only by an erratum;
- **not yet verified by R5.**

## Status
**REVIEWED DRAFT, not verified, not validated, not canonical.** Next: **R5, an independent implementation of §7 and exhaustive checks** (verifier choice: human gate).
