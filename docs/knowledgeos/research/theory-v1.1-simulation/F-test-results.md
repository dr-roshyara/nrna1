# F — Test Results

## Deterministic suite (10 scenarios × 20 properties)

`(vac)` = the property's guard was inactive; the pass is **vacuous** and carries no evidence.

| Property | A | B | C | D | E | F | G | H | I | J | Verdict |
|---|---|---|---|---|---|---|---|---|---|---|---|
| P1 | ✔ | (vac) | ✔ | – | ✔ | ✔ | ✔ | (vac) | ✔ | (vac) | **PASS** |
| P2 | ✔ | ✔ | ✔ | – | ✔ | ✔ | ✔ | ✔ | ✔ | ✔ | **PASS** |
| P3 | (vac) | (vac) | ✔ | (vac) | (vac) | (vac) | (vac) | (vac) | (vac) | (vac) | **DEFINITIONAL** |
| P4 | ✔ | ✔ | ✔ | – | – | ✔ | ✔ | ✔ | ✔ | ✔ | **DEFINITIONAL** |
| P5 | – | – | – | – | ✔ | – | – | – | – | – | **PASS** |
| P6 | (vac) | (vac) | (vac) | – | (vac) | (vac) | (vac) | (vac) | ✔ | (vac) | **PASS** (1 active) |
| P7 | ✔ | – | – | – | – | – | – | – | – | – | **PARTIAL** (CIRC-5) |
| P8 | – | – | – | – | – | – | ✔ | – | – | – | **PASS** |
| P9 | – | – | – | – | – | – | – | ✔ | – | – | **PASS** |
| P10 | ✔ | ✔ | ✔ | – | ✔ | ✔ | ✔ | ✔ | ✔ | ✔ | **PASS** |
| **P11** | ✔ | (vac) | (vac) | – | ✔ | (vac) | (vac) | (vac) | (vac) | (vac) | **PASS here, FAILS adversarially** |
| P12 | – | – | – | ✔ | – | – | – | – | – | – | **PASS** |
| P13 | – | – | ✔ | – | – | – | – | – | – | ✔ | **PASS** |
| P14 | – | – | – | – | ✔ | – | – | – | – | – | **PASS** |
| P15 | ✔ | – | – | – | – | – | – | – | – | – | **PASS** |
| P16 | ✔ | – | – | – | – | – | – | – | – | – | **PASS** |
| P17 | ✔ | – | – | – | – | – | – | – | – | – | **PASS** |
| P18 | ✔ | ✔ | ✔ | – | ✔ | ✔ | ✔ | ✔ | ✔ | ✔ | **PASS** |
| P19 | – | – | – | – | – | – | – | ✔ | – | – | **PASS** |
| P20 | – | – | – | – | ✔ | – | – | – | – | – | **PASS** |

**19 PASS / 1 PARTIAL / 0 FAIL in the deterministic suite** — and §30 warns that this is the
*weakest* outcome, not the strongest. It is why the adversarial suite exists.

## Adversarial suite (§20)

| Case | Probe | Attributed | Result |
|---|---|---|---|
| **AD-1** highly probable but **false** claim | P11 | `os = RHEL9.8` (truth `RHEL8.6`) | **FAIL — CE-1** |
| AD-2 true claim, weak evidence | no-attribution | — | PASS (correctly withheld) |
| AD-3 duplicated evidence as two sources | P8 | — | PASS (copy dropped, corroboration unmet) |
| AD-4 correlated evidence, one upstream feed | P8 | — | PASS |
| AD-6 wrong model, excellent fit | P9 | — | PASS (`R²=0.899`, `causal_status=not-identified`) |
| AD-7 correct model, sparse evidence | no-fabrication | — | PASS (no fabrication in either direction) |
| AD-8 rejected hypothesis reinstated | history | `os = Ubuntu22.04` | PASS (reinstatement recorded, prior state kept) |
| AD-9 ambiguous observation | ambiguity | — | PASS (3 readings preserved, underdetermined) |
| **AD-18** conclusion with no supporting evidence | P10 | `os = RHEL9.8` | **FAIL — CE-2 (positive control)** |

## Randomized layer — 10 000 trials, seeds `1, 7, 13, 101, 2718` × 2 000

Reported as guard activation + **conditional** failure rate, never a bare pass rate.

| Property | guard rate (95 % CI) | failures | **P(fail │ guard)** | vacuous |
|---|---|---|---|---|
| P2 | 1.0000 [1.000, 1.000] | 0 | 0.0000 | no |
| P3 | **0.0000** [0.000, 0.000] | 0 | — | **YES — untested here** |
| P8 | 0.1328 [0.126, 0.140] | 0 | 0.0000 | no |
| P10 | 1.0000 [1.000, 1.000] | 0 | 0.0000 | no |
| **P11** | 0.3394 [0.330, 0.349] | **420** | **0.1237** | no |
| P13 | 0.3737 [0.364, 0.383] | 0 | 0.0000 | no |
| P20 | 0.5010 [0.491, 0.511] | 0 | 0.0000 | no |

`P3`'s guard never activated — the randomized generator never rejects a hypothesis. **Its 100 % pass
is vacuous and must not be cited** (§22). It is tested in scenario C instead.

### Factivity diagnosis

Conditional on an attribution having been made:

| condition | violations | rate |
|---|---|---|
| a source rated reliable reported a **false** value | **414 / 665** | **0.6226** |
| no such source | **0 / 2 607** | 0.0000 |

**Every violation is explained by the epistemic state being unable to distinguish a reliable-looking
false report from a true one.** That is not a tuning problem — it is the witness in `A`, at scale.
