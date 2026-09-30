# 1ar: pre-registration (frozen before either subagent runs)

- **Question:** after F-LOG-0162 (10/11 START events are permission statements), which of the 36 strict legality events are **attested acts**, and which recorded strict witnesses survive when restricted to attested acts?
- **Sources:**
  - the register rows referenced by the event clusters (R-36 … R-94);
  - two session-log windows (S0804-L576, S0815-L483, both previously read);
  - git commit 6a67da5d7;
  - ADR-MP and register-numbering, with no source text: UNCLEAR by rule.

| Reconciled outcome | Consequence |
|---|---|
| a witness pair FAILS | that variable's strict support is reduced accordingly; **SUPPORTED requires ≥ 2 surviving disjoint pairs** |
| norm-making operations (ADOPT, AUTHORIZE, REGISTER, SUPERSEDE, ANNOTATE) mostly ACT-PERFORMED / ACT-REFUSED, and START mostly PERMISSION-STATEMENT | **H-DEONTIC is supported**: the corpus attests **norm-making acts** and their permissions, not the governed actions |

**Sealed main-analyst expectation (bias check):**
- a: both pairs survive (the ADOPT pair: the declined Chief adoption vs the DA adoption; AUTHORIZE-IMPL R-70 vs R-89, both rulings). **a stays SUPPORTED.**
- k: the OPEN-WORK pair (R-60: refused note vs ruling) survives; the REGISTER pair is UNCLEAR (session log). **k → WEAK.**
- s: both pairs FAIL (ACT-NOT-ATTESTED, per 1aq). **s → NOT DEMONSTRATED on attested acts.**
- h: FAILS (generic practice).
- c: survives (R-86 / R-91 are rulings).

Moderate confidence.
