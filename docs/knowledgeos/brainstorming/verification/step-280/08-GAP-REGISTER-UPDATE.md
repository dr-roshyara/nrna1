# 08 — Gap Register Update (empirical evidence)

| Gap | Prior status | Empirical evidence from Step 280 | New status |
|---|---|---|---|
| **Missingness** (not-asked vs absent) | known limitation | **E4 FAIL — reproduced deterministically; Critical Failure #7 fired** | **CONFIRMED THEORY DEFECT (T)** |
| **Orphan / asserted-but-unconnected** | not in the register | **E4 — EKP's `orphan_document` exists; theory has no representation** | **NEW GAP (T)** |
| **Uncertainty / probability space** | open | **E20 BLOCKED — no `(Ω,ℱ,P)`; calibration inexecutable** | **CONFIRMED BLOCKED** |
| **Non-identifiability** | open | not testable — no construct to exercise | **UNCHANGED** |
| **`circular_dependency` scoped to 2 of 6 families** | recommendation | F11/E22 show the theory enforces what the EKP does not | **CONFIRMED (I)** |
| **No Authorize() runtime in EKP** | inferred | **E14 — `authorities.yaml` is an enum, not an evaluator** | **CONFIRMED (I)** |
| **15 constructs NOT OBSERVABLE** | suspected | **measured: 15/24** | **QUANTIFIED (I)** |
| **Policy-change authorisation (G-P1)** | closed by corpus ("records, does not grant") | not exercised — no real policy-change event available | **UNCHANGED — untested** |

## Error taxonomy (§36) — every mismatch classified
| Mismatch | Category | Justification |
|---|---|---|
| E4 missingness | **T** — theory defect | the model, not the code, lacks the distinction |
| E20 calibration | **T** — theory defect | no probability space is defined anywhere |
| 15 NOT OBSERVABLE | **I** — implementation limitation | the EKP was built for governed documents, not evidence-bearing claims |
| E17/F12 simulated | **E** — empirical anomaly | no multi-node deployment exists to observe |

**No mismatch was discarded as an implementation detail without classification.**
