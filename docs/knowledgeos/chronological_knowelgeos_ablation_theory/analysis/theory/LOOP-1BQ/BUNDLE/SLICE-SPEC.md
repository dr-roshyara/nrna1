# SLICE-SPEC: enumeration of a second finite universe (implementer's spec; no expected values)

**Definitions** are those of R5-SPEC.md (§§1–3, and §7.1 for the universe U, its frame, its order and its observation O) and of V1B-SPEC.md (the readings in §A and Def P0.1, and the check forms in Def P0.2). This file changes only the parameters and lists what to compute. Nothing in this file states or implies a result.

## 1. Frames
- **U:** exactly R5-SPEC §7.1.
- **U_P2:** U with two parameters changed, and nothing else:
  - V = {p, q, r}, ordered p < q < r;
  - P = {p, q}.

  Every other part of R5-SPEC §7.1 is kept: T, 𝒯, Σ, 𝒜, the slots, Cap, w1, Ref, con, r, c, the frame axioms F1 and F2, and the enumeration order R-1 with the value order extended to p < q < r. Both σ(0), σ(1) and every iss slot's v range over the new V. A work event conforms iff σ(t(w)) ∈ P (R5-SPEC Def 1.10).

## 2. Readings
R0 (R5-SPEC Def 3.1 unchanged), R-b, R-c, R-d, R-e, R-f, R-g, R-g+, R-h, R-i, R-i', R-j, exactly as in V1B-SPEC Def P0.1. Where Def P0.1 restates a reading "in U", use the same restatement in U_P2. These restatements refer only to T, the slots and w1, which are unchanged.

## 3. What to compute, for each frame F ∈ {U, U_P2} and each reading 𝐑
1. `n_frame_histories`: the number of histories in F. It does not depend on the reading.
2. `sizes`: |R_B^𝐑|, |H-D^𝐑|, |H-B^𝐑|, |H-D^𝐑 ∩ H-B^𝐑| and |H-D^𝐑 ∩ R_B^𝐑|, each computed over F.
3. For **(i)-S**, **(ii)-S** and **(iii)-L** (V1B-SPEC Def P0.2, with 𝐑's classes and with every quantifier ranging over F):
   - `holds`;
   - `scope_size`, the number of h in the form's scope:
     - (i)-S: h ∈ R_B^𝐑;
     - (ii)-S: h ∈ R_B^𝐑 with w1 referenced;
     - (iii)-L: h ∈ H-D^𝐑 ∩ R_B^𝐑 with a2 absent and w1 violating;
   - `n_counterexamples`, the number of h in scope for which the required witness(es) do not exist in F;
   - `least_counterexample`, the least such h in order R-1, or null.
4. For **(i)-O** and **(ii)-O**: `holds`.
