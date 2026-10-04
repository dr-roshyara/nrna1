# V1b supplementary spec (two named histories omitted from V1B-SPEC by the main analyst's extraction error; no expected values)

Evaluate the following with the **existing** program's definitions (readings, frames, Def P0.3 fields). Add the two histories to its named-history set and report the fields. **Do not change any other code path.**

- **H_U5h:**
  - σ = (q, q); a2 absent; Cap = ∅;
  - a1: iss, d = 0, v = q, prep 1, f = ∞, con none;
  - w1 at t = 0, referenced;
  - Ref = {(ρ1, a1), (ρ1, w1)}; r = 0; c = c2.

  **Report:** `iiiL(H_U5h)` under reading R-h, in frame U and in frame U5.
- **H_U5i:** coordinates identical to H_U5h.

  **Report**, under readings R-i and R-i′, in frame U5:
  - `iiO_c(H_U5i)`;
  - `iiO_c(H_U5i with c := c1)`, i.e. the same history with c(ρ1) = c1.
