---
artifact: 10 · STEP-280-COMPLETE-REPORT
date: 2026-08-30T23:24:02+02:00
env: Python 3.13.2 · repo 57d93b0e
status: EXECUTED — EC NOT ACHIEVED
---

# Step 280 — Complete Report

```text
STEP 280 — END-TO-END EMPIRICAL CLOSURE TEST

Implementation status:          COMPLETE (Step 279 components 1-15 implemented and executed)

Cases selected:                 36
Cases executed:                 36
Real KnowledgeOS cases:          8
Synthetic cases:                28
Positive cases:                 22
Negative/boundary cases:        14  (39%, minimum 20%)

End-to-End Tests (E1-E24):
    Executed: 24 / 24
    Passed:   22 / 24
    Failed:    1 / 24   (E4 Missingness)
    Blocked:   1 / 24   (E20 Statistical Calibration)

Falsification Tests (F1-F13 +F4b):
    Executed: 14 / 14
    Passed:   14 / 14
    Failed:    0 / 14
    Blocked:   0 / 14

Evidence level distribution (E-tests):
    Level 5 (real KnowledgeOS):     8 / 24
    Level 4 (controlled test):     13 / 24
    Level <=3 (below substantive):  3 / 24   (E17=3, E19=2, E20=0)

Replay Tests:
    Historical replay:              PASS   (E8, F10 — PolicyAt(t_old)=v1, not v2)
    Policy version preservation:    PASS   (F9 — duplicate version identity rejected; stored state unchanged)
    Authority history preservation: PASS   (F7 — historical Permit at t0, Deny at t1 after revocation)

Reproducibility:                    PASS
    knowledge-graph.php run twice -> byte-identical
    Replay(H) == Replay(H)
    all fixtures deterministic; no Date.now/random in the harness

Evidence Artifacts:
    Collected: 7 / 7
    OUT-CORPUS.txt · OUT-E-EXECUTION.txt · OUT-E-RESULTS.json · OUT-F-EXECUTION.txt
    OUT-F-RESULTS.json · OUT-REAL-AND-STATS.txt · OUT-TIMESTAMP.txt

Formal closure:                     CONFIRMED
Computational closure:              ACHIEVED  (Step 279)
Empirical closure:                  NOT ACHIEVED
Governance closure:                 NOT CLAIMED
Theory completeness:                NOT CLAIMED

Critical failures:                  1 / 10   (#7 missingness converted to a substantive value)

Remaining theoretical gaps:
    T-1  'not asked' and 'absent' are indistinguishable in 𝒜            [E4, category T]
    T-2  'orphan' (asserted-but-unconnected) has no K representation      [E4, category T]
    T-3  no probability space; statistical calibration inexecutable       [E20, BLOCKED]
    T-4  non-identifiability remains inexpressible                        [carried forward]

Remaining implementation gaps:
    I-1  15 of 24 constructs NOT OBSERVABLE in the real EKP
    I-2  circular_dependency is a warning over 2 of 6 relation families   [carried forward]
    I-3  no Authorize() runtime; authorities.yaml is an enum, not an evaluator

Remaining empirical gaps:
    Ε-1  16 PASSes are Level 4 (my own implementation of the spec), not Level 5
    Ε-2  no multi-node deployment exists — E17/F12 propagation is SIMULATED (Level 3)
    Ε-3  no measurement executor — E19 is Level 2 (declared, not executed)

Remaining normative decisions:      NONE reached in Step 280

Theory revisions required:          YES — one, category T, located but NOT applied

Failed tests:                       E4 (Missingness)
Blocked tests:                      E20 (Statistical Calibration — no probability space)

Next:
    STEP 281 — GAP CLOSURE VERIFICATION
    STEP 282 — THEORY CLOSURE DECISION
```

## The closure matrix — populated with OBSERVED evidence

**Legend:** `Formal` from Steps 272–278 · `Computational` = executed against the Step 279 reference
implementation · `Empirical` = executed against the **real** KnowledgeOS/EKP · `Governance` not claimed.
**Every cell below names the test and the observation that produced it. No cell is inferred.**

| Foundation | Formal | Computational | Empirical (observed) | Gov | **Final** |
|---|---|---|---|---|---|
| **K** | CONFIRMED | E1 PASS | **L5 — 37 real docs → (𝒜,ℛ); 6/6 assertions representable; lint exit 0** | — | **CLOSED** |
| **Identity** | CONFIRMED | E2 PASS | **L5 — `knowledge_id` unique, enforced; graph byte-identical ×2** | — | **CLOSED** |
| **Equality** | CONFIRMED | E2 PASS | **L5 — reordering-invariant on real graph output** | — | **CLOSED** |
| **Σ** | CONFIRMED | E3 PASS | **NOT OBSERVABLE — no epistemic status field in the EKP** | — | **PARTIAL** |
| **Evidence** | CONFIRMED | E5,E6 PASS | **NOT OBSERVABLE — no evidence field in the schema** | — | **PARTIAL** |
| **Qualification** | CONFIRMED | E5 PASS | **NOT OBSERVABLE — no qualification step exists** | — | **PARTIAL** |
| **T** | CONFIRMED | E7 PASS | **NOT OBSERVABLE — docs are hand-edited; no guarded transition** | — | **PARTIAL** |
| **History** | CONFIRMED | E8,E11 PASS | **L1 only — git holds history; the platform does not model it** | — | **PARTIAL** |
| **Provenance** | CONFIRMED | E9 PASS | **NOT OBSERVABLE — `authority` is trust rank, not origin** | — | **PARTIAL** |
| **Lineage** | CONFIRMED | E10 PASS | **L5 — typed `derived_from`/`requires` edges present in the real graph** | — | **CLOSED** |
| **Policy** | CONFIRMED | E12,E13,F1-F6,F10,F11 PASS | **L5 PARTIAL — `knowledge-lint` IS a policy evaluator: 18 rules, 11 error / 7 warning, exit 0** | — | **PARTIAL** |
| **Authority** | CONFIRMED | E14,F2,F4,F7 PASS | **L5 PARTIAL — `authorities.yaml` enum enforced; no `Authorize()` runtime** | NOT CLAIMED | **PARTIAL** |
| **Authorization** | CONFIRMED | F2,F4,F4b,F7,F8 PASS | **NOT OBSERVABLE — no authorization runtime in the EKP** | NOT CLAIMED | **PARTIAL** |
| **Measurement** | CONFIRMED | E19 L2 · E20 **BLOCKED** | **NOT OBSERVABLE — no measurement executor, no probability space** | — | **BLOCKED** |
| **Replay** | CONFIRMED | E8,F10 PASS | **NOT OBSERVABLE as a platform concept (git only)** | — | **PARTIAL** |
| **Missingness** | **DEFECT** | **E4 FAIL** | **L5 PARTIAL — EKP's `orphan_document` exists; the THEORY has no representation for it** | — | **FAILED** |

**Tally: 3 CLOSED · 11 PARTIAL · 1 BLOCKED · 1 FAILED.**

> **Not one foundation reaches CLOSED on empirical grounds alone.** The three that close do so because a
> real Level-5 observation exists **and** the computational test passes. **Eleven are PARTIAL for exactly
> one reason: the real KnowledgeOS does not implement them**, so there is nothing to observe — an
> implementation limitation, not a theory defect. **One is FAILED on a theory defect.**

## Agreement matrix

| Domain | N | Exact | Semantic | Mismatch |
|---|---:|---:|---:|---:|
| K | 1 | 1 | 0 | 0 |
| Identity | 1 | 1 | 0 | 0 |
| Equality | 1 | 1 | 0 | 0 |
| Sigma | 1 | 1 | 0 | 0 |
| Evidence | 2 | 2 | 0 | 0 |
| Transformation | 1 | 1 | 0 | 0 |
| Policy | 2 | 2 | 0 | 0 |
| Authority | 1 | 1 | 0 | 0 |
| Replay | 1 | 1 | 0 | 0 |
| Measurement | 2 | 1 | 0 | 0 (1 blocked) |
| **Missingness** | **1** | **0** | **0** | **1** |

## HPA-facing statement

**What was tested:** 24 empirical tests, 14 falsification tests, 36 stratified cases, 39% negative controls,
against both a faithful Step 279 reference implementation and the running EKP.

**What passed:** 22/24 E-tests, 14/14 F-tests, full reproducibility, FP=0 across all negative controls.

**What failed:** E4 — missingness. **Critical Failure #7 fired.**

**What remains open:** 15 of 24 constructs have no real-environment observation; measurement and statistical
calibration are unexecutable; one theory revision is located but deliberately not applied.

**Empirical closure status: NOT ACHIEVED.**

> **Governing principle honoured:** *do not make the empirical data fit the theory.* The data did not fit,
> and the theory is reported as answering to it.
