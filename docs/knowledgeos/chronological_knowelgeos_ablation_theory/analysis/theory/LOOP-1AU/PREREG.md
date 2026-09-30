# 1au: schema-r2 decision package (frozen before the drafter runs)

- **Purpose:** a decision package for the human on the norm-statement vs act-observation distinction (F-LOG-0163). **No adoption, no recoding.** After a PASS or PASS_WITH_LIMITATIONS review, **STOP for human authorization** (frozen-protocol change).
- **Inputs:** SCHEMA v1, the 1ar audit (worker + review + reconciliation), the 1aq reconciliation, EVENTS.json.
- **Reviewer focus:**
  - necessity (an analysis-time filter vs a schema change);
  - the **dual-nature** problem (a ruling is an act *and* a norm);
  - circularity / hindsight;
  - minimality;
  - fair options.

**Sealed expectation:** the reviewer finds that a single `observation_type` is insufficient. It will want a **two-level representation**: the record's own act (e.g. a ruling performing ADOPT) vs the norm content it states about other acts (e.g. "7B is not authorized"). That is the dual-nature problem, because the 1ar classes already separate them implicitly. Moderate confidence.
