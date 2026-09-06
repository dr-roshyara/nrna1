# AM — Measurement-Theory Register

**Status:** CURRENT as of Wave 3 (core = TV-F-017's SNF audit + the corpus's other measured quantities). For each quantity: construct · estimand · scale · arithmetic legitimacy · calibration · verdict.

| Quantity | Construct | Estimand declared? | Scale established | Arithmetic legitimacy | Calibration | Verdict |
|---|---|---|---|---|---|---|
| SNF `CR` collision rate | false-merge proportion | yes (proportion) | **ratio** | proportions aggregate | target CR→0 well-formed | **the one clean metric** |
| SNF abstention accuracy | abstention vs human judgment | yes | ratio (counts) | yes | needs inter-rater reliability (absent) | CONDITIONAL |
| SNF `d` | structural overlap | no | ordinal at best (identity axiom unverified) | means NOT legitimate | — | structural index, not semantic distance |
| SNF `C_snf`, `NC_snf`, `T_snf` | "invariance"/"non-collapse"/"stability" | **no estimand** | inherits d | NOT legitimate (means over ordinal) | thresholds self-demoted to experimental | UNVERIFIED / DESIGN CHOICE (thresholds) |
| SNF `H_sem` | interpretation-posterior spread | entropy of P(θ|E,C) | fine *given* distribution | entropy arithmetic fine; cross-n comparability NOT justified | distribution unconstructed | DERIVABLE-CONDITIONAL |
| SNF `Q_snf` composite | — | — | mixed | harmonic mean over mixed scales | — | **REJECTED** (concurs with in-corpus critique) |
| Evidence strength `s∈[0,1]` | policy-interpreted contribution | deliberately NOT universal (`c_ρ→𝒬`, step-005) | policy-relative — the corpus *refuses* a universal scale (correctly) | operator-specific; edge holes (MV-F-18) | never calibrated | scale-relativity is a design decision, sound; any cross-policy comparison ILLEGITIMATE until declared |
| `Readiness(K)` | contract sufficiency | **UNDEFINED symbol in a load-bearing inequality** (025a-1 §18) | — | — | — | UNDEFINED — BLOCKING for its inequality |
| Zero projections (GapCount, Coverage, WeightedGapScore) | gap-vector summaries | yes (counts/ratios) | counts: ratio; WeightedGapScore: weights unjustified | counts fine; weighted score inherits the composite problem | — | projections legitimate AS LABELED (corpus labels them projections — correct); weighted variant EXPERIMENTAL |
| Probability P(H|E,M) | model-relative uncertainty | model-declared | [0,1] measure *given* (Ω,𝓕,P) — never constructed in-corpus | Bayes arithmetic valid given model | calibration designed, unexecuted | usable in constructible-likelihood pockets only (TV-F-014B) |
| Utility U(a,s) | preference intensity | policy-owned | cardinal scale ASSUMED, never justified (HA-S08) | EU sums assume cardinality+comparability | — | UNVERIFIED as measurement; legitimate as declared policy artifact |
| Uncertainty vectors (5 taxonomies) | five different constructs (TV-F-015) | component domains undefined | none established | additive forms stated-then-withdrawn (027 §52) | — | representation proposals, not measurements |

**Register rule (from the corpus's own standard, enforced):** *no arithmetic without an established scale; no estimator without an estimand; no confidence number without calibration semantics.* The corpus states this standard (v1 §11/§31 Roberts-style; 082 §65) and violates it only in the SNF review half and the weighted projections — both already self-flagged in-corpus and now formally audited.
