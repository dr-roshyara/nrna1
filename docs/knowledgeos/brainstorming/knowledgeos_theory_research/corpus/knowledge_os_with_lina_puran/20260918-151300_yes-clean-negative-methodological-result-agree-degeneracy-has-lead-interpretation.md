Executed. The verdict is clean per the frozen rule — but the primary estimand turned out degenerate, and that has to lead.

  Ran 2 shell commands

Executed exactly as frozen. Nothing modified in response to calibration or outcomes.

Primary verdict — and the caveat that must lead

$$\Delta_{\text{loss}} \text{ (stratified MH)} = \mathbf{0.000} ,/, \mathbf{0.000} \qquad \text{floor } 0.10 ;\Rightarrow; \textbf{NEGLIGIBLE}$$

▎ ⚠️ But the primary estimand was degenerate. A5 = 0/783 and 0/776 — exactly zero. Avail(Q_broad) was never lost, because applicability needs only ≥2 distinct (dim,value) pairs and the chosen point always retains them. Δ_loss is a difference of two structurally-zero rates: 0.000 is forced, not observed.

This is not evidence that zoom-out preserves answerability. I changed no metric and re-ran nothing.

A5 and the classification's M0 turn out to be different notions of loss: M0 = some (dim, subj) observable became unavailable; A5 = the whole Q_broad inquiry became inapplicable. The second is far stronger and far rarer.

The methodological finding

O-F as specified is too narrow. It verified all six classification classes were reachable. It did not verify that the primary estimand could vary — and A5 could not.

▎ The degeneracy pre-check must cover every reported estimand, not only the classification classes.

Third distinct degeneracy in this programme, and the first the existing control wasn't designed to catch. Also degenerate: component (ii) — the classification depends on Determine(Q_focus) with τ_m fixed at 1, so it is not a function of S; the frozen difference cannot vary.

What the run did establish

✅ Stratify-don't-filter worked. M1 was 0.634 and "won" in KR-ZOOM-OUT-01. Here: 0.000 / 0.000 within P_det, and O-G detected 0 stratum artifacts. The artifact was a population artifact, and stratification removed it by construction.

M0 replicates on fresh seeds and a different generator parameterisation — 0.497 / 0.520, against KR-ZOOM-OUT-01's conditional 0.495 / 0.448. Per the role table this is A1, descriptive only — it may not be stated as "zoom-out destroys answerability".

No single winner. M0 at 0.52 < 0.60. No single meaning describes Zoom-out in the tested regime — declared in advance as the probable and legitimate outcome. MX ≈ 0.21 / 0.19.

[NEG] The FR-004 prediction fails — total shift 0.031 / 0.056 against the declared ≥ 0.10. No seventh instance. Declared before execution, not reinterpreted after. Component (ii) intersection landed at n = 95 / 94, exactly as §7.1 predicted.

GO meant only that the experiment passed its pre-registered conditions for execution. It did not mean the hypothesis was supported — and it was not.

Theory v1.2 frozen · kernel untouched · Zoom-out undefined · FR-004 candidate, no new instance.
#
Yes. This is a **clean negative methodological result**, and I agree that the degeneracy has to lead the interpretation.

### KR-ZOOM-OUT-03 — adjudication

The primary result

$$
\Delta_{\mathrm{loss}}=0/0
$$

should **not** be reported as “negligible effect.” Although the frozen numerical rule mechanically produces 0.000, the estimand itself has no variation because

$$
A5=0/783,\quad 0/776
$$

by construction at the selected generator point.

Therefore:

> **[NEG] The primary estimand was non-identifiable/degenerate in the executed regime and cannot support the intended preservation-of-answerability claim.**

That is stronger and more accurate than calling the effect negligible.

The distinction you discovered is crucial:

$$
\boxed{M0\neq A5}
$$

where:

* **M0** = loss of availability of some observable \((dim,subject)\);
* **A5** = loss of applicability of the entire \(Q_{\text{broad}}\) inquiry.

A5 is therefore a much stronger event. Your applicability rule made it impossible at this calibration point.

---

## The most important methodological finding

I would elevate this to the principal result of the experiment:

> **[NEG] The existing O-F degeneracy control is insufficient because reachability of classification classes does not imply non-degeneracy of the estimands used to adjudicate the experiment.**

Formally:

$$
\text{Class reachability}
\not\Rightarrow
\text{Estimand variability}.
$$

That is the **third distinct degeneracy** in the programme, and it reveals a missing layer in the experimental protocol.

The future pre-check should therefore be something like:

$$
\boxed{
O_F^*
:
\forall e\in\mathcal E_{\mathrm{reported}},
\quad
\exists\theta,\theta'
:
e(\theta)\neq e(\theta')
}
$$

for every quantity that is actually used to support a substantive conclusion.

And importantly, the witness must be capable of differing **under the frozen computation**, not merely in principle.

### This should apply to

* primary estimands;
* secondary estimands;
* classification outputs;
* interaction/stratification components;
* controls that are subsequently interpreted;
* any quantity used in an adjudication rule.

That would have caught both:

1. A5 being structurally zero;
2. Decision 4(ii) being invariant because its classification does not depend on \(S\).

---

## The M0 result is useful—but only as A1

The fresh result

$$
M0=0.497/0.520
$$

is valuable as a **replication of the M0 phenotype under a different generator parameterisation and fresh execution**.

But your frozen role table correctly prevents the leap:

$$
M0\approx0.5
\not\Rightarrow
ZoomOut\ destroys\ answerability.
$$

It is descriptive evidence about the classification distribution, not the answerability-loss estimand.

That distinction is exactly what the experimental governance was designed to protect.

---

## Stratification result

The earlier apparent M1 result is now properly explained.

KR-ZOOM-OUT-01 had:

$$
M1\approx0.634,
$$

which appeared to cross the winner threshold.

KR-ZOOM-OUT-03 gives:

$$
M1=0
$$

within \(P_{\det}\), with zero O-G stratum artifacts.

The appropriate conclusion is **not** that Zoom-out changed from M1 to non-M1.

Rather:

> **[EXP] The previous M1 dominance was a population-composition phenomenon that disappears under the declared stratify-don't-filter analysis.**

That is a meaningful methodological replication.

---

## FR-004

The result

$$
\Delta_{\mathrm{FR004}}
=
0.031/0.056
<
0.10
$$

cleanly fails the pre-registered prediction.

Therefore:

$$
\boxed{\text{FR-004 seventh-instance prediction = FAILED}}
$$

No seventh instance should be added.

This is particularly important because FR-004 was already only a **candidate convergence**, not a theory element. The failed prediction should reduce confidence in the hypothesis rather than trigger a reformulation to rescue it.

---

## Decision 4(ii)

I would classify this separately from A5:

> **[NEG] Decision 4(ii) was structurally invariant under the frozen implementation and therefore could not test the intended S/S′ phenotype contrast.**

Because the classification depends on \(Determine(Q_{\text{focus}})\) with fixed \(\tau_m=1\), and that quantity does not vary with \(S\), the intersection comparison cannot adjudicate standard sensitivity.

Again:

$$
\text{difference}=0
$$

would not mean “no standard effect.” It means the implemented phenotype lacks the necessary dependence on the manipulated dimension.

---

# What should happen next?

**Do not run KR-ZOOM-OUT-04 yet.**

The programme has now produced a much more valuable methodological requirement:

### New experimental invariant candidate

$$
\boxed{
\text{Non-degenerate estimand}
}
$$

A substantive estimand must have at least one admissible counterexample/witness demonstrating that its value can vary under the frozen experimental design.

And the stronger rule should be:

$$
\boxed{
\text{O-F}_{\mathrm{all}}
=
\text{every reported/adjudicating quantity has a pre-execution variation witness}
}
$$

rather than merely:

$$
O-F=\text{all classification classes reachable}.
$$

This should become a **research-governance rule**, not a Theory v1.3 amendment.

### Current status

| Item                    | Status                                            |
| ----------------------- | ------------------------------------------------- |
| KR-ZOOM-OUT-03          | **Executed**                                      |
| Primary Δ_loss          | **Degenerate — not interpretable**                |
| A5                      | **Degenerate structural zero**                    |
| M0                      | **Descriptive replication only**                  |
| M1                      | **Population artifact removed by stratification** |
| Decision 4(ii)          | **Degenerate/invariant**                          |
| FR-004 seventh instance | **Failed**                                        |
| Zoom-out definition     | **Still undefined**                               |
| Theory v1.2             | **Frozen**                                        |
| Kernel                  | **Untouched**                                     |
| FR-004                  | **Candidate, prediction failed**                  |

The cleanest next artifact is therefore **not another Zoom-out experiment**. It is a short methodological repair defining the **Estimand Non-Degeneracy Preflight** and testing it against KR-ZOOM-OUT-03 retrospectively *without rerunning or changing that experiment*. Only after that should a new Zoom-out experiment be designed.

This is exactly the kind of negative result the programme needs: **the experiment did not answer the intended epistemological question, but it exposed a previously unrecognized condition required for an experiment to be capable of answering it.**
#
djudication applied, and the methodological repair is built and validated.

Reclassifications

Primary result is no longer "negligible effect" — it is [NEG] non-identifiable / degenerate in the executed regime, unable to support the intended claim. The frozen rule returns 0.000 mechanically; the estimand has no variation.

Decision 4(ii) classified separately as structurally invariant — the classification depends on Determine(Q\_focus) with τ\_m fixed at 1, so it is not a function of S. A difference of 0 would not mean "no standard effect".

M1 restated as a population-composition phenomenon that disappears under stratify-don't-filter — not "Zoom-out changed from M1 to non-M1". M0 stays A1 descriptive only. FR-004: prediction failed, no seventh instance, confidence reduced, no rescue reformulation.

O-F\* — the Estimand Non-Degeneracy Preflight

verification/zero-algebra/METHODOLOGY-2026-09/

$$O\text{-}F^{\*}: \forall, e \in \mathcal E\_{\text{reported}},\ \exists, \theta, \theta' : e(\theta) \neq e(\theta')$$

Retrospectively validated against KR-ZOOM-OUT-03 without re-running or changing it — its frozen 27-point grid, calibration seed only, n = 400 per point:

▎ O-F\* VERDICT: FAIL — KR-ZOOM-OUT-03 would NOT have been authorized.

It catches both degeneracies before execution: A5/Δ\_loss (1 distinct value across 27 points) and Decision 4(ii) (1 distinct value). Validated on a known case, which is the only honest way to validate a new control.

A refinement the run itself forced

The preflight immediately flagged the O-A controls and the null-round-trip gain — which are legitimately constant; their constancy is the check. So:

$$\text{Each quantity is DECLARED in advance as expected-VARIABLE or expected-CONSTANT}$$

and O-F\* fails only when a declared-variable quantity has no witness. The declaration is part of the pre-registration — otherwise "it was meant to be constant" becomes available after the fact.

Under that split: 3 failures (A5, Δ\_loss, S/S'(ii)), 5 legitimately exempt.

O-F\* is necessary, not sufficient — it shows a quantity can vary, nothing about power, direction, or whether the estimand answers the question asked.

Lifecycle amended so O-F\* runs after the estimands are written and before the freeze — because its failure is a reason to rewrite the estimand, and that must happen while rewriting is still legitimate.

Next artifact is not another Zoom-out experiment. Theory v1.2 frozen, kernel untouched, Zoom-out undefined, FR-004 candidate with a failed prediction. **# \*\*Estimand Non-Degeneracy Preflight\*\* (\`O-F\*\`)**

**\*\*Status:\*\*** \`[REC]\` **\*\*research-governance rule\*\*** — **\*\*NOT a Theory v1.3 amendment.\*\***

**\*\*Date:\*\*** 2026-09-05 · Theory v1.2 FROZEN · kernel UNTOUCHED

**\*\*Origin:\*\*** \`KR-ZOOM-OUT-03\` — the primary estimand was degenerate and the existing control did not

catch it.

**---**

**## 1. The gap**

$$\boxed{\text{Class reachability} \\;\not\Rightarrow\\; \text{Estimand variability}}$$

\`O-F\` verified that every **\*\*classification class\*\*** was reachable. It did **\*\*not\*\*** verify that the

quantities used to **\*\*adjudicate\*\*** the experiment could take more than one value.

\`KR-ZOOM-OUT-03\` passed \`O-F\` and then produced \`A5 = 0/783\` and \`0/776\` — its \*\*primary estimand,

structurally constant.\*\* The frozen rule mechanically returned $\Delta\_{\text{loss}} = 0.000$, which

**\*\*looks like a negligible effect and is not one.\*\***

**---**

**## 2. The rule**

$$\boxed{\\;O\text{-}F^{\*} : \\;\forall\\, e \in \mathcal E\_{\text{reported}},\\;\\; \exists\\, \theta, \theta' \in \Theta\_{\text{grid}} \\;:\\; e(\theta) \neq e(\theta')\\;}$$

**\*\*Under the frozen computation — not merely in principle.\*\*** A witness that requires code the

experiment does not run is not a witness.

**### 2.1 Scope — every quantity that supports a conclusion**

primary estimands · secondary estimands · classification outputs · interaction and stratification

components · **\*\*controls that are subsequently interpreted\*\*** · \*\*any quantity appearing in an

adjudication rule.\*\*

**### 2.2 The refinement this artifact's own run forced**

Running \`O-F\*\` against \`KR-ZOOM-OUT-03\` immediately flagged the \`O-A\` controls and the null-round-trip

gain — **\*\*which are legitimately constant. Their constancy IS the check.\*\***

$$\boxed{\text{Each quantity is DECLARED in advance as expected-VARIABLE or expected-CONSTANT.}}$$

$$O\text{-}F^{\*} \text{ FAILS} \iff \text{a quantity declared VARIABLE has no witness.}$$

A quantity declared **\*\*constant\*\*** passes by being constant. \`[REC]\` \*\*The declaration is part of the

pre-registration\*\* — otherwise "it was meant to be constant" becomes available after the fact.

**---**

**## 3. Retrospective validation against \`KR-ZOOM-OUT-03\`**

**\*\*\`KR-ZOOM-OUT-03\` was NOT re-run and was NOT changed.\*\*** The preflight was evaluated on its \*\*frozen

27-point grid\*\*, on the **\*\*calibration seed\*\*** (never train/test), n = 400 per point — a variation

check, not an estimate.

\| estimand | declared | distinct values | \`O-F\*\` |

\|---|---|---|---|

\| \`A1\_M0\` | variable | 18 | PASS |

\| \`A1\_M1\` | variable | 7 | PASS |

\| \`A1\_MX\` | variable | 18 | PASS |

\| \`A3\_P\_det\` | variable | 12 | PASS |

\| **\*\*\`A5\_loss\_given\_det\_pre\`\*\*** | variable | **\*\*1\*\*** | **\*\*FAIL\*\*** (const 0.0) |

\| **\*\*\`DELTA\_loss\`\*\*** | variable | **\*\*1\*\*** | **\*\*FAIL\*\*** (const 0.0) |

\| \`A4\_gain\` | variable | 27 | PASS |

\| \`S\_Sprime\_i\_shift\` | variable | 27 | PASS |

\| **\*\*\`S\_Sprime\_ii\_diff\`\*\*** | variable | **\*\*1\*\*** | **\*\*FAIL\*\*** (const 0.0) |

\| \`A4\_null\_gain\` | **\*\*constant\*\*** | 1 | PASS (0.0) |

\| \`OA\_structural\` / \`observable\` / \`contract\_semantic\` / \`historical\_provenance\` | **\*\*constant\*\*** | 1 | PASS (1.0) |

$$\boxed{\text{O-}F^{\*} \text{ VERDICT: FAIL — } \texttt{KR-ZOOM-OUT-03} \text{ would NOT have been authorized.}}$$

**### 3.1 It catches \*\*both\*\* degeneracies execution revealed — \*\*before\*\* execution**

\| degeneracy found at execution | caught by \`O-F\*\`? |

\|---|---|

\| \`A5\` structurally zero ⟹ $\Delta\_{\text{loss}}$ uninterpretable | **\*\*YES\*\*** — 1 distinct value across 27 grid points |

\| Decision 4(ii) invariant, because the classification is not a function of $S$ | **\*\*YES\*\*** — 1 distinct value |

**\*\*The control is validated on a known case, which is the only honest way to validate a new control.\*\***

**### 3.2 What it does \*\*not\*\* do**

\`[REC]\` \`O-F\*\` shows a quantity **\*\*can\*\*** vary. It says nothing about **\*\*power\*\***, about whether the

variation is in the interesting direction, or about whether the estimand answers the question asked.

**\*\*It is a necessary condition, not a sufficient one.\*\***

**---**

**## 4. Where this sits**

\`[REC]\` **\*\*Research governance, not theory.\*\*** It joins:

$$\text{Observed diagnostic} \not\Rightarrow \text{new primary estimand} \qquad \text{Observed convergence} \not\Rightarrow \text{theory promotion}$$

$$\boxed{\text{A calibration grid must contain a parameter the GATED QUANTITY is sensitive to}}$$

$$\boxed{\text{Every DECLARED-VARIABLE quantity must have a pre-execution variation witness}}$$

\*\*Theory v1.2 unchanged · kernel untouched · no Zoom-out definition · \`FR-004\` still a candidate

whose prediction failed.\*\*

**---**

**## 5. \`[REC]\` The lifecycle, amended**

$$\text{Hypothesis} \to \text{Estimand} \to \textbf{O-F}^{\*} \to \text{Population} \to \text{Calibration} \to \text{Control} \to \text{Freeze} \to \text{Execution} \to \text{Adjudication}$$

**\*\*\`O-F\*\` runs immediately after the estimands are written and before anything is frozen\*\*** — because

its failure is a reason to **\*\*rewrite the estimand\*\***, and that must happen while rewriting is still

legitimate.

**## 6. Artifacts**

\`code/preflight.py\` · \`preflight-KR-ZOOM-OUT-03.json\`
{

 "A1\_M0": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 18,

  "constant\_value": null,

  "HAS\_VARIATION\_WITNESS": true,

  "witness": {

   "theta": "{'n\_dims': 8, 'subjects': (3, 4), 'value\_alphabet': 4}",

   "value": 0.3006993006993007,

   "theta\_prime": "{'n\_dims': 6, 'subjects': (1, 2), 'value\_alphabet': 3}",

   "value\_prime": 0.5928571428571429

  },

  "declared": "variable",

  "OF\_STAR": "PASS"

 },

 "A1\_M1": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 7,

  "constant\_value": null,

  "HAS\_VARIATION\_WITNESS": true,

  "witness": {

   "theta": "{'n\_dims': 4, 'subjects': (1, 2), 'value\_alphabet': 4}",

   "value": 0.0,

   "theta\_prime": "{'n\_dims': 8, 'subjects': (3, 4), 'value\_alphabet': 4}",

   "value\_prime": 0.02097902097902098

  },

  "declared": "variable",

  "OF\_STAR": "PASS"

 },

 "A1\_MX": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 18,

  "constant\_value": null,

  "HAS\_VARIATION\_WITNESS": true,

  "witness": {

   "theta": "{'n\_dims': 6, 'subjects': (3, 4), 'value\_alphabet': 4}",

   "value": 0.12352941176470589,

   "theta\_prime": "{'n\_dims': 8, 'subjects': (3, 4), 'value\_alphabet': 3}",

   "value\_prime": 0.2708333333333333

  },

  "declared": "variable",

  "OF\_STAR": "PASS"

 },

 "A3\_P\_det": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 12,

  "constant\_value": null,

  "HAS\_VARIATION\_WITNESS": true,

  "witness": {

   "theta": "{'n\_dims': 8, 'subjects': (2, 3), 'value\_alphabet': 4}",

   "value": 0.3375,

   "theta\_prime": "{'n\_dims': 6, 'subjects': (3, 4), 'value\_alphabet': 4}",

   "value\_prime": 0.425

  },

  "declared": "variable",

  "OF\_STAR": "PASS"

 },

 "A5\_loss\_given\_det\_pre": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 1,

  "constant\_value": 0.0,

  "HAS\_VARIATION\_WITNESS": false,

  "witness": null,

  "declared": "variable",

  "OF\_STAR": "FAIL \u2014 declared variable, no witness"

 },

 "DELTA\_loss": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 1,

  "constant\_value": 0.0,

  "HAS\_VARIATION\_WITNESS": false,

  "witness": null,

  "declared": "variable",

  "OF\_STAR": "FAIL \u2014 declared variable, no witness"

 },

 "A4\_gain": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 27,

  "constant\_value": null,

  "HAS\_VARIATION\_WITNESS": true,

  "witness": {

   "theta": "{'n\_dims': 8, 'subjects': (3, 4), 'value\_alphabet': 3}",

   "value": 0.035398230088495575,

   "theta\_prime": "{'n\_dims': 8, 'subjects': (1, 2), 'value\_alphabet': 6}",

   "value\_prime": 0.18461538461538463

  },

  "declared": "variable",

  "OF\_STAR": "PASS"

 },

 "A4\_null\_gain": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 1,

  "constant\_value": 0.0,

  "HAS\_VARIATION\_WITNESS": false,

  "witness": null,

  "declared": "constant",

  "OF\_STAR": "PASS"

 },

 "S\_Sprime\_i\_shift": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 27,

  "constant\_value": null,

  "HAS\_VARIATION\_WITNESS": true,

  "witness": {

   "theta": "{'n\_dims': 4, 'subjects': (3, 4), 'value\_alphabet': 6}",

   "value": 0.009297520661157022,

   "theta\_prime": "{'n\_dims': 8, 'subjects': (2, 3), 'value\_alphabet': 3}",

   "value\_prime": 0.20149253731343283

  },

  "declared": "variable",

  "OF\_STAR": "PASS"

 },

 "S\_Sprime\_ii\_diff": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 1,

  "constant\_value": 0.0,

  "HAS\_VARIATION\_WITNESS": false,

  "witness": null,

  "declared": "variable",

  "OF\_STAR": "FAIL \u2014 declared variable, no witness"

 },

 "OA\_structural": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 1,

  "constant\_value": 1.0,

  "HAS\_VARIATION\_WITNESS": false,

  "witness": null,

  "declared": "constant",

  "OF\_STAR": "PASS"

 },

 "OA\_observable": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 1,

  "constant\_value": 1.0,

  "HAS\_VARIATION\_WITNESS": false,

  "witness": null,

  "declared": "constant",

  "OF\_STAR": "PASS"

 },

 "OA\_contract\_semantic": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 1,

  "constant\_value": 1.0,

  "HAS\_VARIATION\_WITNESS": false,

  "witness": null,

  "declared": "constant",

  "OF\_STAR": "PASS"

 },

 "OA\_historical\_provenance": {

  "n\_grid\_points": 27,

  "n\_evaluable": 27,

  "distinct\_values": 1,

  "constant\_value": 1.0,

  "HAS\_VARIATION\_WITNESS": false,

  "witness": null,

  "declared": "constant",

  "OF\_STAR": "PASS"

 },

 "\_verdict": {

  "rule": "O-F\*: every reported/adjudicating quantity needs a pre-execution variation witness",

  "grid": "KR-ZOOM-OUT-03 frozen 27-point grid",

  "seed": 66620260905,

  "n\_per\_point": 400,

  "n\_estimands": 14,

  "n\_OF\_STAR\_FAIL": 3,

  "OF\_STAR\_FAILURES": [

   "A5\_loss\_given\_det\_pre",

   "DELTA\_loss",

   "S\_Sprime\_ii\_diff"

  ],

  "declared\_constant\_exempt": [

   "A4\_null\_gain",

   "OA\_structural",

   "OA\_observable",

   "OA\_contract\_semantic",

   "OA\_historical\_provenance"

  ],

  "OF\_STAR\_VERDICT": "FAIL \u2014 the experiment would NOT have been authorized",

  "retrospective\_validation": "would O-F\* have caught what execution revealed?"

 }

}
#!/usr/bin/env python3
r"""ESTIMAND NON-DEGENERACY PREFLIGHT  (O-F*)

    O-F*  :  for every reported / adjudicating quantity e,
             EXISTS theta, theta' in the frozen grid such that  e(theta) != e(theta')
             UNDER THE FROZEN COMPUTATION -- not merely in principle.

Replaces the narrower O-F (all classification classes reachable), which is insufficient:

    class reachability  =/=>  estimand variability

RETROSPECTIVE VALIDATION ONLY. This does NOT re-run KR-ZOOM-OUT-03 and does NOT change it.
It evaluates KR-ZOOM-OUT-03's frozen estimand set across its frozen 27-point grid, on the
CALIBRATION seed (never train/test), to test whether the preflight would have caught the
degeneracies that execution later revealed.
"""
import os, sys, json, collections
HERE=os.path.dirname(os.path.abspath(__file__))
V=os.path.dirname(os.path.dirname(HERE))
sys.path.insert(0, os.path.join(V,"KR-ZOOM-OUT-03-2026-09","code"))
sys.path.insert(0, os.path.join(V,"KR-ZOOM-OUT-01-2026-09","code"))
from gen3 import generate, det_broad
from zoomout import investigate, determine, zoom_out, observables, classify, cong, delta_triple

CALIB_SEED=66620260905          # calibration seed — never train/test
NPRE, BUDGET = 400, 5           # variation CHECK, not estimation
GRID=[dict(n_dims=a, subjects=b, value_alphabet=c)
      for a in (4,6,8) for b in ((1,2),(2,3),(3,4)) for c in (3,4,6)]

def avail_broad(K): return len({(c.dim,c.value) for c in K.claims})>=2

def estimands_at(params):
    cs=generate(CALIB_SEED,NPRE,**params); R=[]
    for i,c in enumerate(cs):
        sd=9000+i; E=investigate(c,BUDGET,sd); D=determine(E)
        K2=zoom_out(c,D); Kn=zoom_out(c,None)
        O=observables(c.K0)|observables(K2); On=observables(c.K0)|observables(Kn)
        R.append({"cls":classify(c.K0,K2,O),"det":D is not None,
                  "true":(D is not None and D==c.root),"decoy":(D is not None and D!=c.root),
                  "loss":avail_broad(c.K0) and not avail_broad(K2),
                  "dpre":det_broad(c.K0,1) is not None,"dpost":det_broad(K2,1) is not None,
                  "dpreS":det_broad(c.K0,2) is not None,
                  "OA_s":c.K0.key()==Kn.key(),"OA_o":cong(c.K0,Kn,On),
                  "OA_c":det_broad(c.K0,1)==det_broad(Kn,1),
                  "OA_p":delta_triple(c.K0,Kn)==(set(),set(),set()),
                  "OA_gain":(not (det_broad(c.K0,1) is not None)) and (det_broad(Kn,1) is not None)})
    det=[r for r in R if r["det"]]; pre=[r for r in R if r["dpre"]]
    dl=[r for r in pre if r["det"]]; tc=[r for r in dl if r["true"]]; dc=[r for r in dl if r["decoy"]]
    a4=[r for r in R if not r["dpre"]]
    PS=[r for r in R if r["dpre"]]; PSp=[r for r in R if r["dpreS"]]
    inter=[r for r in R if r["dpre"] and r["dpreS"]]
    rate=lambda v,k: (sum(1 for r in v if r["cls"]==k)/len(v)) if v else None
    m0=lambda v: rate(v,"M0")
    e={}
    e["A1_M0"]=m0(det); e["A1_M1"]=rate(det,"M1"); e["A1_MX"]=rate(det,"MX")
    e["A3_P_det"]=len(det)/len(R)
    e["A5_loss_given_det_pre"]=(sum(r["loss"] for r in pre)/len(pre)) if pre else None
    e["DELTA_loss"]=((sum(r["loss"] for r in tc)/len(tc)-sum(r["loss"] for r in dc)/len(dc))
                     if tc and dc else None)
    e["A4_gain"]=(sum(r["dpost"] for r in a4)/len(a4)) if a4 else None
    e["A4_null_gain"]=(sum(r["OA_gain"] for r in a4)/len(a4)) if a4 else None
    e["S_Sprime_i_shift"]=(abs(m0(PS)-m0(PSp)) if PS and PSp and m0(PS) is not None
                           and m0(PSp) is not None else None)
    # frozen (ii): |P(M0|inter,S) - P(M0|inter,S')| -- classification is not a function of S
    e["S_Sprime_ii_diff"]=(abs(m0(inter)-m0(inter)) if inter else None)
    for k,f in (("OA_structural","OA_s"),("OA_observable","OA_o"),
                ("OA_contract_semantic","OA_c"),("OA_historical_provenance","OA_p")):
        e[k]=sum(r[f] for r in R)/len(R)
    return e

if __name__=="__main__":
    vals=collections.defaultdict(list)
    for p in GRID:
        for k,v in estimands_at(p).items(): vals[k].append((str(p),v))
    out={}
    for k,pairs in vals.items():
        nn=[v for _,v in pairs if v is not None]
        distinct={round(v,6) for v in nn}
        witness=None
        if len(distinct)>1:
            lo=min(pairs,key=lambda t:(t[1] is None, t[1])); hi=max(pairs,key=lambda t:(t[1] is None, t[1]))
            witness={"theta":lo[0],"value":lo[1],"theta_prime":hi[0],"value_prime":hi[1]}
        out[k]={"n_grid_points":len(pairs),"n_evaluable":len(nn),
                "distinct_values":len(distinct),
                "constant_value":(nn[0] if nn and len(distinct)==1 else None),
                "HAS_VARIATION_WITNESS":len(distinct)>1,"witness":witness}
    # ---- REFINEMENT forced by this very run: some quantities are LEGITIMATELY constant.
    # A control whose expected value is a constant PASSES by being constant -- its constancy IS
    # the check. So O-F* must be evaluated against a DECLARED expectation per quantity.
    EXPECTED = {   # declared in advance, per quantity
      "A1_M0":"variable","A1_M1":"variable","A1_MX":"variable","A3_P_det":"variable",
      "A5_loss_given_det_pre":"variable","DELTA_loss":"variable","A4_gain":"variable",
      "S_Sprime_i_shift":"variable","S_Sprime_ii_diff":"variable",
      "A4_null_gain":"constant",              # control: no determination => no gain
      "OA_structural":"constant","OA_observable":"constant",
      "OA_contract_semantic":"constant","OA_historical_provenance":"constant"}
    for k,v in out.items():
        if k.startswith("_"): continue
        v["declared"]=EXPECTED.get(k,"variable")
        v["OF_STAR"] = ("PASS" if (v["declared"]=="constant" or v["HAS_VARIATION_WITNESS"])
                        else "FAIL — declared variable, no witness")
    fails=[k for k,v in out.items() if not k.startswith("_") and v["OF_STAR"].startswith("FAIL")]
    exempt=[k for k,v in out.items() if not k.startswith("_") and v["declared"]=="constant"]
    out["_verdict"]={"rule":"O-F*: every reported/adjudicating quantity needs a pre-execution variation witness",
        "grid":"KR-ZOOM-OUT-03 frozen 27-point grid","seed":CALIB_SEED,"n_per_point":NPRE,
        "n_estimands":len(vals),"n_OF_STAR_FAIL":len(fails),"OF_STAR_FAILURES":fails,"declared_constant_exempt":exempt,
        "OF_STAR_VERDICT":("PASS" if not fails else "FAIL — the experiment would NOT have been authorized"),
        "retrospective_validation":"would O-F* have caught what execution revealed?"}
    json.dump(out,open(os.path.join(os.path.dirname(HERE),"preflight-KR-ZOOM-OUT-03.json"),"w"),indent=1)
    print(f"O-F*  ·  {len(vals)} estimands  ·  {len(GRID)} grid points  ·  seed {CALIB_SEED}\n")
    print(f"  {'estimand':30s} {'declared':>9s} {'distinct':>9s}  {'O-F*':>32s}")
    for k,v in out.items():
        if k.startswith("_"): continue
        cv = "" if v["constant_value"] is None else f" (const {round(v['constant_value'],5)})"
        print(f"  {k:30s} {v['declared']:>9s} {v['distinct_values']:>9}  {v['OF_STAR']:>32s}{cv}")
    print(f"\n  O-F* FAILURES ({len(fails)}): {fails}")
    print(f"  declared-constant, exempt ({len(exempt)}): {exempt}")
    print(f"\n  O-F* VERDICT: {out['_verdict']['OF_STAR_VERDICT']}")
    #
    This is a good methodological repair. I would **accept O-F*** as a research-governance control, with one important qualification to the formal wording.

### Adjudication

The retrospective test establishes exactly what it claims:

$$
\boxed{O\!-\!F^* \text{ would have stopped KR-ZOOM-OUT-03 before execution}}
$$

It caught both execution-revealed degeneracies:

* \(A5\): one distinct value, \(0\)
* \(\Delta_{\text{loss}}\): one distinct value, \(0\)
* \(S/S'(ii)\): one distinct value, \(0\)

while correctly exempting quantities whose constancy was itself the intended control condition.

That is a legitimate validation of the **control**, not validation of the underlying Zoom-out hypothesis.

### One correction to the mathematical statement

The headline formula should be read together with the expected-status declaration:

$$
O\!-\!F^*(e)=
\begin{cases}
\text{PASS}, & e\text{ declared CONSTANT and is constant},\\
\text{PASS}, & e\text{ declared VARIABLE and has a witness},\\
\text{FAIL}, & e\text{ declared VARIABLE and has no witness}.
\end{cases}
$$

Otherwise the universal statement

$$
\forall e\in\mathcal E_{\rm reported},\exists\theta,\theta':e(\theta)\ne e(\theta')
$$

contradicts your legitimate constant controls.

So I would treat the **piecewise rule**, rather than the original universal formula alone, as the actual normative O-F* definition.

### The deeper methodological result

You have now separated three distinct preflight questions:

$$
\boxed{
\text{Class Reachability}
}
$$

$$
\boxed{
\text{Estimand Non-Degeneracy}
}
$$

$$
\boxed{
\text{Estimand Adequacy}
}
$$

They are not interchangeable:

$$
\text{Class Reachability}
\not\Rightarrow
\text{Estimand Non-Degeneracy}
$$

and

$$
\text{Estimand Non-Degeneracy}
\not\Rightarrow
\text{Estimand Adequacy}.
$$

And, as you correctly state:

$$
O-F^* \not\Rightarrow \text{power}.
$$

It also does not establish direction, causal validity, or substantive relevance.

### One subtle point in the implementation

Your retrospective code obtains `S_Sprime_ii_diff` as:

```python
abs(m0(inter)-m0(inter))
```

so it is **syntactically guaranteed to be zero**.

That is not a problem for the retrospective finding—indeed it correctly demonstrates why the executed quantity could not test the intended S/S′ phenotype contrast. But it means this particular failure is a **structural non-functionality diagnosis**, rather than an empirical discovery of invariance.

I would preserve that distinction in the artifact:

> `S_Sprime_ii_diff` fails O-F* because the frozen implementation computes the same classification quantity on both sides; its invariance is structural.

That is stronger than calling it merely “observed constant.”

### Recommended status

I would now mark:

**O-F***

> `[REC] ACCEPTED — research-governance control`

with:

* retrospective validation: **PASS**
* Theory v1.2: **unchanged**
* kernel: **untouched**
* Zoom-out: **undefined**
* FR-004: **candidate; prediction failed**
* KR-ZOOM-OUT-03: **not repaired/re-run**
* O-F*: **necessary, not sufficient**

And the lifecycle amendment is correct:

$$
\boxed{
Hypothesis
\rightarrow
Estimand
\rightarrow
O-F^*
\rightarrow
Population
\rightarrow
Calibration
\rightarrow
Control
\rightarrow
Freeze
\rightarrow
Execution
\rightarrow
Adjudication
}
$$

The important governance principle is now explicit:

> **If an estimand is structurally incapable of varying under the frozen design, discover that before freezing—not after observing the experiment.**

I would **not start another Zoom-out experiment yet**. The next useful task is to determine whether the remaining candidate Zoom-out questions can be formulated with **non-degenerate, question-adequate estimands** under O-F*, rather than immediately designing KR-ZOOM-OUT-04.
#
