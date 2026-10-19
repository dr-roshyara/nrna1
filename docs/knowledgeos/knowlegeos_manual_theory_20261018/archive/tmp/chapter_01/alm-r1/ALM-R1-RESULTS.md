# ALM-r1: results, proof status and evidence classes (2026-10-18)

**Subject:** `ALM-R1-SPEC.md` implemented in `alm_r1.py`, checked by `oracle_r1.py` (written separately; it does not import `alm_r1`; brute-force fixed points).

**Status:** research candidate, **pending independent review 2**. Not validated.

## 1. Reproduction

```
cd docs/knowledgeos/knowlegeos_manual_theory_20261018/tmp/chapter_01/alm-r1
python3 -B tests_r1_scenarios.py      # 28/28   -> run-scenarios.txt
python3 -B tests_r1_exhaustive.py     # ~36 s   -> run-exhaustive.json, th5-rows.json
python3 -B ml_discovery_r1.py         # discovery aid -> ml-discovery.json
```

- Python 3.13.2; scikit-learn 1.9.0 (discovery aid only).
- Repository HEAD `1b45cc50…`. The `tmp/` folder is untracked; hashes are in `MANIFEST-R1.md`.

**Bounds of the exhaustive checks:**
- 13 act templates: GRANT ×5 (including finite period, proviso, delegated with bars), REVOKE ×2, CONFIRM, ADOPT, END, PERFORM implement and verify, ACCEPT;
- holders {R, a, b}; root R with scope `(*, *)`;
- every subset of size 1–3; τ ∈ {1, 2, 3}; 8,463 histories;
- 8 validity configurations (C10 × δ × C14), 16 currency configurations (RD 1 × BASIS × claim convention × C6);
- **every recording order** (all permutations) for the recognition checks;
- random property tests: 3,000 trials, sizes 4–6, τ ∈ {1..4}, seed 20261018, with delta-debugging minimization.

**Not covered by the bounds:**
- larger histories (except randomly);
- multiple subjects or works;
- contradictory or ⊥ fields: exercised only in scenario tests;
- C1 = a: only in Pr-10 and in the scenarios;
- BAR modes: scenarios only.

## 2. Results by evidence class

| Claim | Class | Evidence |
|---|---|---|
| **Th-1** existence and uniqueness of V | **mathematically derived** (S-10) **+ bounded evidence** | 67,704 checks: the oracle found **exactly one** fixed point every time; 0 disagreements; 3,000 random larger cases with 0 disagreements |
| Currency agrees with the oracle | bounded evidence | 121,152 checks, 0 disagreements |
| **Th-2** locality (repaired: END must follow its ADOPT) | **derived + bounded** | 270,816 checks, 0 violations |
| **Th-3** soundness under completeness (repairs **P-1**, BLOCKER) | **derived + bounded** | 363,558 checks over all recording orders, 0 violations. **The machine-learning count independently confirms it:** of 7,153 closed-world disagreements, **0** occur when Comp(k, τ(e)) holds |
| **Th-4** non-monotone recognition | **derived (construction) + bounded** | flips found: T→F 28 (λ⁻ = 0), T→U 28 (λ⁻ = ∞, i.e. r1.1), F→T 5,292, U→T 102,544, U→F 93,217. **Minimal T→F/T→U example = the hand-derived one** (gRa, gab, late-recorded rRa). **Under r1.1 (λ⁻ = ∞) no T→F and no U→F occur** (by S-6.1) |
| **Th-5** open-world negatives are defeasible, except in characterized cases | **derived; the characterization is bounded evidence** | 11,920 invalid acts; 8,532 cured by a late root completion (2,934 of them despite a recorded revocation). **Characterization: a negative is non-defeasible iff it rests on ill-formedness or the IND-1 restriction.** It matches 11,920/11,920, but **only after the completion family was widened** (§4, finding R1-F3). *Not a proof*: the completion family is finite (two grant shapes) |
| **Th-6** positives are presumptive | derived | follows from Th-3 + Th-4 |
| **Th-7** determinism and termination of R | derived | finite worlds |
| **Th-8** reproducibility | **derived conditionally** (AX-AppendOnly unverifiable on BC-7) | scenario `S-8_no_clock…`: with no dated witness, **U**; with one witness, determined |
| **Pr-9** layers | derived | scenarios `S0092_given_unrecorded`, `usable_from_position` |
| **Pr-10** C1 = a and C1 = b are incomparable | **bounded evidence (existence by example = a proof of non-implication)** | 638,235 checks: valid under a but not b **80**; under b but not a **100,499**. **The examples are proofs of non-implication:** a_not_b: an unrecorded revocation is ignored under C1-a; b_not_a: an unrecorded grant |
| r1.1 recognition = (λ⁺ = 0, λ⁻ = ∞) (repairs **F-1**, BLOCKER; C-8) | derived (by definition) + scenario | `REG_CE6`, `r11_never_F_even_with_recorded_revocation` |
| ML discovery: when do records mislead? | **discovery aid only** | balanced decision tree: "an earlier grant not yet recorded" → disagreement (recall 0.978, precision 0.156). The unbalanced tree was uninformative: it predicted the majority class, and its accuracy equalled the base rate. **Template-mix dependent; says nothing about real frequencies** |

## 3. Review-1 findings in scope: disposition

**Classes:** R = repaired · NA = proven not applicable in scope · A = explicit assumption · P = reserved policy parameter · U = unresolved.

| Finding | Disp. | Where |
|---|---|---|
| P-1 (BLOCKER) | **R** | S-4.1 END after ADOPT; Th-2/Th-3; `REG_CE7`; bounded 0/363,558 |
| P-2 | **R + A** | the order is a *recorded* field `ord` (C10 = given) or excluded (C10 = no_same_instant). An unrecorded order gives U: `REG_CE8` |
| P-3 | **R** | Th-5 restated; the "recorded bar" category error removed (bars belong to Elig) |
| P-4 | **R** | Th-8 replaces the consequence; ADR-C6 claim withdrawn |
| P-5 | **R** | Th-4 premises stated; minimal example reproduced |
| P-6, P-7, P-9 | **R** | S-3 wording; the point "time t" is defined (C16 inclusive); Prop 8 withdrawn |
| P-8 | **R** | Pr-9 |
| P-10 | **R** | names (AX-…); `now(k)` defined; `from ≥ τ` enforced (`REG_CE10`) |
| C-1 | **R + P (RD 1, BASIS)** | S-4.8; `REG_CE1` |
| C-2 | **P (RD 1 δ)** | `REG_CE3` |
| C-3 | **R** | target-specific atoms; `REG_CE4` |
| C-4 | **R** | `REG_CE2`, `REG_V1_CASE12` |
| C-5 | **P (BAR [Cand])** | `REG_CE5` |
| C-6 | **R** | ⊥ fields and contradictions → worlds → U |
| C-7 | **R** | single R definition |
| C-8 | **R** | two horizons |
| C-9 | **A** | `Proc` is supplied externally; observed BC-7 = `Eff_obs` (S-7) |
| C-10 | **A / P (C12)** | static root only, flagged |
| C-11 | **R + P (C14, claim convention [Cand])** | `REG_CE12` |
| T-1…T-8 | **R** | expanded alphabet (CONFIRM, END, finite periods, bars, ACCEPT); independent oracle; conformance and policy tests separated; mislabels removed |
| A-1…A-5 | **R/P** | as above |
| A-6 | **R** | holder vs performer (S-1; INV-ATTR-1) |
| A-7 | **R** | S-6.1 |
| F-1 (BLOCKER) | **R** | S-6.1; r1.1 = (λ⁺ = 0, λ⁻ = ∞) |
| F-2 | **R + P** | RD 1 per claim kind |
| F-3 | **R + P (C1)** | C1 = a implemented (S-4.7). Pr-10 shows the options are incomparable |
| F-4 | **A/P** | U = C6 implemented; narrowing excluded (unsourced, as in M2 G-10) |
| E-1, E-2, E-3 | **evidence downgrade carried** | RD 1 brief (no default) |
| E-4 | **R** | conditional-act evidence is n = 1 (S0052) |
| E-5 | **R** | relational IND-2: `REG_IND2` |
| E-6 | **R** | = A-6 |
| E-7 | **A** | AX-AppendOnly an assumption; O = target, `Eff_obs` = observed |
| E-8 | **R + U** | IND-1 as authority (`REG_IND1`). **REVOKE still has no governance-side instance**: kept as a modelled primitive, flagged **U (evidence)** |

## 4. New findings in this round (author)

| Id | Finding | Kind |
|---|---|---|
| **R1-F1** | 5 of 13 review-1 regression tests were label placeholders, not behavioural (Phase A) | test defect (fixed) |
| **R1-F2** | The default grant period `(0, ∞)` made **every** grant anachronistic under the new well-formedness rule: the CE10 class, recurring in the author's own fixtures. Fixed by the spec rule "no stated period = [τ, ∞)" | semantic / implementation (fixed). **This is G-16's pattern again: the 6th time an author encoding hid a defect, caught here by the scenario tests** |
| **R1-F3** | My Th-5 characterization looked false in 190 cases. Cause: **the completion search was too narrow** (granting "everything" also validated an unauthorized implementation and triggered IND-1). With need-only completions: 0 mismatches | method defect in a check (fixed; the lesson is recorded) |
| **R1-F4** | **r1.1's "never 'not authorized'" is stricter than soundness requires.** Negatives resting on ill-formedness (e.g. an anachronistic grant) or the IND-1 restriction are sound even under an open world | **an input to any future r1.2 decision; not adopted** |
| **R1-F5** | **C1 options are incomparable** (Pr-10). Neither is a "safe superset" | **an input to the C1 brief** |
| **R1-F6** | C1 = a is **not computable on the BC-7 store** (no record times) without a date→position map | architecture consequence |
| **R1-F7** | Under r1.1, recognition retractions are T→U only; under a finite λ⁻ they can be T→F. **A recognition change must be reported as an event**, never back-dated | architecture consequence |

## 5. Scope limits (kept visible, not solved)

The **7 v1 rows that cannot be expressed** remain out of scope: citations (C2), suspension, C5, annulment with an effective time (RD 2), consumption (C13), RD 6 υ, and joint authority (C4). Also IND-4 and IND-6 as mechanisms.

## 6. Independence limits

- The oracle has the same author as the implementation. Shared misreadings of the spec are possible.
- Review 2 is a same-family model in a fresh context.
- Neither is external.
