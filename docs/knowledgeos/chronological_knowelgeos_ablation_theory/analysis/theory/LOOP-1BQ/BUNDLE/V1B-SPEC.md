# V1B-SPEC: reading-robustness enumeration (implementer's spec; NO predictions and NO expected values are included)

The base definitions are in R5-SPEC.md: universe U (§7.1), its order, frames U5/U6/U7/U9, observations, and instance I7. This file adds the readings (alternative clause definitions), the check forms, the output fields and the named histories to evaluate.

**Readings to run:** R0 (R4's own classes), R-b, R-c, R-d, R-e, R-f, R-g, R-g+, R-h, R-i, R-i', R-j.
- R-a needs two targets and is not run.
- R-k is not run.

## A. Readings (replace clauses of R4 Def 3.1 as stated)
**Def V1.3 (the readings).** Each reading below replaces, drops or adds clauses of 𝐑₀ as stated. Everything not mentioned is as in 𝐑₀. 

| Id | Change to 𝐑₀ |
|---|---|
| **R-a** | B-chg → **B-chg^a**: for every (t, τ) ∈ Chg(h), either some a ∈ E^iss has τ(a) = τ, f(a) = t and v(a) = σ(t)(τ), or some w ∈ W has **τ(w) = τ** and t(w) < t |
| **R-b** | B-conf → **B-conf^b**: some w ∈ W conforms |
| **R-c** | R-b, and B-rec → **B-rec^c** := D-rec (B-recW dropped) |
| **R-d** | D-st → **D-st⁺** (R4 Lem 3.7): D-st, and if Adopted(a, t) holds for some t, then f(a) < ∞ |
| **R-e** | D-chg → **D-chg^e**: D-chg without "(α(a), iss) ∈ Cap" |
| **R-f** | B-rec → **B-rec^f** := D-rec (B-recW dropped); B-conf stays universal |
| **R-g** *(added)* | B-chg → **B-chg^g** := B-chg ∧ "there are (t, τ) ∈ Chg(h) and w ∈ W with t(w) < t" |
| **R-h** *(added)* | B-rec → **B-rec^h** := D-rec ∧ B-recW ∧ "every a ∈ E and every w ∈ W is referenced by some record" |
| **R-i** *(r1, review M1)* | B-rec → **B-rec^i** := "E ≠ ∅" |
| **R-i′** *(r1, review M1)* | 𝔅_rec := ∅ (no B-side record clause) |
| **R-j** *(r1, review M2)* | D-chg → **D-chg^j** := D-chg ∧ "Chg(h) ≠ ∅" |
| **R-g⁺** *(r1, review M2)* | B-chg → **B-chg^g⁺** := B-chg^g ∧ "some (t, τ) ∈ Chg(h) has a ∈ E^iss with τ(a) = τ, f(a) = t, v(a) = σ(t)(τ)" |
| **R-k** *(r1, review M3)* | B-chg's act disjunct additionally requires **Adopted(a, t)** (variant R-k^st) or **(α(a), iss) ∈ Cap** (variant R-k^cap) |

## B. Definitions of the checks (U, with a reading substituted)

**Def P0.1 (U with a reading).** U, its frame (F1, F2), its order (R-1) and its observations are as in R4 §7.1. For a reading 𝐑, H-D^𝐑, H-B^𝐑 and R_B^𝐑 are computed by replacing the clauses of R4 Def 3.1 as in V1A Def V1.3:
- **R-c:** B-conf := "w1 present and conforms"; B-rec := D-rec;
- **R-d:** D-st := D-st⁺ (an act with Adopted(s, t) for some t ∈ T must have f(s) ≠ ∞);
- **R-g:** B-chg := B-chg ∧ "σ(0) ≠ σ(1) and w1 present with t(w1) = 0" (in U this is exactly the existential conjunct of V1A Def V1.3 R-g);
- **R-h:** B-rec := D-rec ∧ B-recW ∧ "every present act slot and w1 (if present) is in Ref";
- (r1) **R-i:** B-rec := "some act slot is present";
- (r1) **R-i′:** no B-side record clause (R_B^{i′} = U);
- (r1) **R-j:** D-chg := D-chg ∧ "σ(0) ≠ σ(1)";
- (r1) **R-g⁺:** B-chg := R-g's B-chg ∧ "some present iss slot s has f(s) = 1 and v(s) = σ(1)".

**Def P0.2 (forms of each check).** For a reading 𝐑:
- **(i)-S** = K4 with 𝐑's classes: for every h ∈ R_B^𝐑 there is h* ∈ H-D^𝐑 ∩ H-B^𝐑 with h* ≈_{σ, f} h. In U, (i)-S is equivalent to (i) for O_¬σf.
- **(i)-O** = for base O = (r, c): O(H-D^𝐑 ∩ R_B^𝐑) = O(H-B^𝐑) = O(H-D^𝐑 ∩ H-B^𝐑) = O(R_B^𝐑).
- **(ii)-S** = K6 with 𝐑's classes: for every h ∈ R_B^𝐑 with w1 referenced there are h_v ∈ H-D^𝐑 ∖ H-B^𝐑 (σ_v constant, w1 present and violating) and h_c ∈ H-D^𝐑 ∩ H-B^𝐑 (w1 conforming), both ≈_{σ, f} h.
- **(ii)-O** = the same with "same O" instead of ≈_{σ, f}.
- **(iii)-L** = K7 with 𝐑's classes. The scope is h ∈ H-D^𝐑 ∩ R_B^𝐑 with a2 absent and w1 violating, and h′ must be in H-B^𝐑, with K7's literal constraints (σ changes only at t(w1); f free; a2 optionally added as an unreferenced iss act with prep 1 and con none).
- **(ii)** and **(iii)** for a reading are said to **hold** iff the S-form and the L-form hold.

"Least counterexample" is the least h in R-1 order, as in R4 §7.1. 

**Def P0.3 (r1: output fields).** The run reports, per reading 𝐑 and frame F ∈ {U, U₅}, the following fields. `[𝐑 | F] field = value` names one reported value. A planted history H is given by all its enumerated coordinates.
- `member(C, H)` ∈ {true, false}: whether H ∈ C, for C one of R_B^𝐑, H-D^𝐑, H-B^𝐑;
- `iS(H)` ∈ {true, false}: whether some h* ∈ H-D^𝐑 ∩ H-B^𝐑 has h* ≈_{σ, f} H;
- `K4b(H)` ∈ {true, false}: whether some h** ∈ H-D⁺ ∩ H-B has h** ≈_{σ, f, Cap} H;
- `iiO_c(H)` ∈ {true, false}: whether some h_c ∈ H-D^𝐑 ∩ H-B^𝐑 with w1 conforming has O(h_c) = O(H);
- `iiiL(H)` ∈ {true, false}: whether some h′ as in (iii)-L exists for H (H must be in the (iii)-L scope);
- `image(Obs; A)`: the set Obs(A) for a class A, and `diff(Obs; A, B)` := Obs(A) ∖ Obs(B), each reported as a set of values.

A field evaluated **at a named history** does not depend on which counterexample is least.

---


## C. Named histories and planted frames

The following named histories and planted frames must be evaluated.

### Common controls (every reading in §1)

**Def C-U5.** Frame U₅ ("c(ρ1) = c2 ⇒ σ ≡ q") with the reading's classes, evaluated as below.

**Def C-O1.** O₁ = (O, σ) with the reading's classes.

**Def C-c (R-c).** Planted history H_c: σ = (q, q); a1 iss, v = q, prep 1, f = ∞, d = 0, referenced; a2 absent; Cap = ∅; w1 absent; Ref = {(ρ1, a1)}; con none; r = 0; c = c1. Observation O₄ (presence of w1).

**Def C-d (R-d).**
- H_d: σ = (p, p); a1 absent; a2 iss, d = 0, v = q, prep 0, f = ∞; Cap = {(x, adp)}; w1 at t = 0; Ref = {(ρ1, a2), (ρ1, w1)}; con none; r = 0; c = c1.
- H_d2: a1 iss, d = 0, v = p, prep 0, f = 0; a2 iss, d = 0, v = q, prep 0, f = ∞; Cap = {(x, adp)}; w1 at 0, referenced; Ref = {(ρ1, a2), (ρ1, w1)}; σ = (p, p); con none; r = 0; c = c1.

**Def C-g (R-g).**
- H_g: σ = (p, q); a1 iss, d = 0, v = q, prep 0, f = 1; a2 absent; Cap = {(x, iss), (x, adp)}; w1 at t = 0, referenced; Ref = {(ρ1, a1), (ρ1, w1)}; con none; r = 0; c = c1.
- H_g′: H_g with Cap = {(x, adp)}.

**Def C-h (r1, rewritten: R-h, (iii) evaluated at I7).** Evaluate (iii)-L at the named history I7 (R4 §7.1) with r(ρ1) = 0 and c(ρ1) = c1, under R-h and under R4's classes 𝐑₀. Also evaluate the named history W7: σ = (q, p); a1 as in I7 (iss, d = 0, v = q, prep 1, f = ∞, referenced, con none); a2 iss, d = 1, v = p, prep 1, f = 1, **referenced**, con none; w1 at t = 1, referenced; Ref = {(ρ1, a1), (ρ1, a2), (ρ1, w1)}; Cap = ∅; r = 0; c = c1.

**(r1) Def C-i (R-i, R-i′).** H_i: σ = (p, p); a1 iss, d = 0, v = p, prep 1, f = ∞, unreferenced, con none; a2 absent; Cap = ∅; w1 at t = 0, referenced; Ref = {(ρ1, w1)}; r = 0; c = c1. H_∅: the history with a1, a2 and w1 absent, σ = (p, p), Cap = ∅, Ref = ∅, r = 0, c = c1.

**(r1) Def C-j (R-j).** H_j: σ = (p, p); a1 absent; a2 iss, d = 0, v = p, prep 0, f = 0; Cap = {(x, iss), (x, adp)}; w1 at t = 0, referenced; Ref = {(ρ1, a2), (ρ1, w1)}; con none; r = 0; c = c1. H_g as in C-g.

**(r1) Def C-g⁺ (R-g⁺).** H_g⁺: σ = (p, q); a1 iss, d = 0, v = q, prep 1, f = ∞; a2 absent; Cap = ∅; w1 at t = 0, referenced; Ref = {(ρ1, a1), (ρ1, w1)}; con none; r = 0; c = c1. H_g as in C-g.

## D. What to compute, for every reading run, in frames U and U5
1. (i)-S, (i)-O, (ii)-S, (ii)-O and (iii)-L: report TRUE/FALSE, plus the least counterexample (R4 §7.1 order) where FALSE.
2. For each observation O, O1, O3, O4, O_notsigmaf and Cap:
   - image(Obs; A) for A in {R_B^R, H-D^R, H-B^R, H-D^R ∩ R_B^R, H-D^R ∩ H-B^R};
   - diff(Obs; H-D^R ∩ R_B^R, H-B^R), and diff(Obs; R_B^R, H-B^R), and diff(Obs; R_B^R, H-D^R ∩ H-B^R).
3. Every field of Def P0.3 at every named history in C, under every reading for which that history is defined, and under R0 where the text names R0. Also report exists(H-D+, ≈_{σ,f} H_d2) (some h in H-D+ with h ≈_{σ,f} H_d2), O(W7) and O(I7).
