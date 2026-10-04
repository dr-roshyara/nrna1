# V1b: sealed predictions and planted controls (non-M readings only)

**SEALED. Withhold from the V1b implementer until the V1b run has produced and frozen its outputs.**

| | |
|---|---|
| Inputs (only) | `FORMAL-THEORY-R4.md` (§7.1 universe U, §7.2 checks); `HYPOTHESES.md`; `R6-VALIDATION-MATRIX.md` (V1b); `V1A-LEMMA.md` r1 (readings and lemmas, cited as V-ids); for r1 also `REVIEW.md` / `.json` |
| Revision | **r1** (targeted fix; changes mapped in `CHANGELOG-r1.md` and marked "(r1)") |
| Author | the R4 / V1a analyst (same family). Sealed, **not independent** (matrix, unresolved risk 3) |
| Scope | the readings that Lemma M does not cover: **R-c, R-d, R-g**, (r1) **R-i, R-i′, R-j, R-g⁺** (not-M), and **R-h for (iii) only** (M holds, T fails). M readings (R-a, R-b, R-e, R-f, and R-h's (i) and (ii)) are not predicted here. Their expected outcome follows from Lemma M (V1A Rem V3.0a). R-a is **UNTESTABLE IN U** in any case (one target). (r1) R-k is REJECTED (V1A Rem V3.18a) and not predicted |

**Labels.** Every prediction is a **Conj** (a sealed claim about the enumeration outcome). Its basis is given: a V1a lemma (proved in 𝓗, specialized to U) or a hand computation in U. Definitions are **Def**. (r1) Each control is a **Def** (what is planted and evaluated), and its required output is a **Conj** (id suffix ".R"). The scoring rule is a **Def**. No count is predicted.

---

## 0. Definitions of the checks (U, with a reading substituted)

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
- **(ii)** and **(iii)** for a reading are said to **hold** iff the S-form and the L-form hold. Where only the O-form is predicted to hold, this is stated.

"Least counterexample" is the least h in R-1 order, as in R4 §7.1. Only its **characteristics** are predicted. Coordinates not named are expected at their least admissible value. Features in **bold** are essential (§3).

**Def P0.3 (r1: output fields).** The run reports, per reading 𝐑 and frame F ∈ {U, U₅}, the following fields. `[𝐑 | F] field = value` names one reported value. A planted history H is given by all its enumerated coordinates.
- `member(C, H)` ∈ {true, false}: whether H ∈ C, for C one of R_B^𝐑, H-D^𝐑, H-B^𝐑;
- `iS(H)` ∈ {true, false}: whether some h* ∈ H-D^𝐑 ∩ H-B^𝐑 has h* ≈_{σ, f} H;
- `K4b(H)` ∈ {true, false}: whether some h** ∈ H-D⁺ ∩ H-B has h** ≈_{σ, f, Cap} H;
- `iiO_c(H)` ∈ {true, false}: whether some h_c ∈ H-D^𝐑 ∩ H-B^𝐑 with w1 conforming has O(h_c) = O(H);
- `iiiL(H)` ∈ {true, false}: whether some h′ as in (iii)-L exists for H (H must be in the (iii)-L scope);
- `image(Obs; A)`: the set Obs(A) for a class A, and `diff(Obs; A, B)` := Obs(A) ∖ Obs(B), each reported as a set of values.

A field evaluated **at a named history** does not depend on which counterexample is least.

---

## 1. Predictions per reading

### 1.1 R-c (existential B-conf, B-recW dropped)

| Check | Prediction | Basis |
|---|---|---|
| (i)-S | **FAILS** | V1A Lem V3.3(b) |
| (i)-O | **HOLDS**; all four sets equal all four (r, c) values | V1A Lem V3.4(c); a witness in H-D ∩ H-B^c exists for every (r, c) |
| (ii)-S, (ii)-O | **HOLD** | V1A Lem V3.4(a) (Lemma M (ii) with M_p^W) |
| (iii)-L | **HOLDS** | V1A Lem V3.4(b) |
| O_¬σf | O_¬σf(H-B^c) ⊊ O_¬σf(H-D^c ∩ R_B^c) (= O_¬σf(H-D)). The difference is **exactly** the values with w1 absent | hand computation: for h ∈ H-D with w1 present (referenced or not), h* is in H-B^c; with w1 absent, no member of H-B^c shares the value; for h ∈ H-B^c, h* is in H-D |

**Conj P1.1 (least counterexample to (i)-S under R-c).** It is the least member of R_B^c (the D-rec class) overall. Characteristics:
- **w1 absent** (essential: no history ≈_{σ, f} h has a work event, so "some work conforms" fails);
- σ = (p, p); a1 absent; a2 present as the least admissible issue act (d = 0, v = p, prep 0, f = 0), and referenced; Ref = {(ρ1, a2)};
- Cap = ∅; con none; r(ρ1) = 0; c(ρ1) = c1.

This h is not even in H-D (D-st fails: f(a2) = 0 with no adp-capability). It is a counterexample because (i) quantifies over R_B^c, not over H-D.

### 1.2 R-d (D-st⁺, biconditional status)

| Check | Prediction | Basis |
|---|---|---|
| (i)-S | **FAILS** | V1A Lem V3.6(b) |
| (i)-O | **HOLDS** (all four (r, c)); this is R4's K4b in image form | R4 Lem 3.7; V1A Lem V3.7(a) |
| K4b as written in R4 (≈_{σ, f, Cap}) | **HOLDS** | R4 Lem 3.7 |
| (ii)-S | **FAILS** | V1A Lem V3.6(b) |
| (ii)-O, and (ii) with ≈_{σ, f, Cap} | **HOLD** | V1A Lem V3.7(b) |
| (iii)-L | **HOLDS** | V1A Lem V3.7(c); the scope shrinks (H-D⁺ ⊆ H-D) and the proof does not use the D side |
| O_¬σf, first equality | O_¬σf(H-D⁺ ∩ R_B) ⊊ O_¬σf(H-B) (strict: a σ,f-blind observation separates) | ⊆: for h ∈ H-D⁺ ∩ R_B, h[σ ≡ p, f ≡ ∞] ∈ H-B (H-B does not read adoption) and has the same O_¬σf value. ≠: V1A Lem V3.6(d) |

**Conj P1.2 (least counterexample to (i)-S under R-d).** Characteristics:
- **exactly one issue act, which is adopted** (prep 0 with (x, adp) ∈ Cap) and has **value q ∉ P**, d = 0, and f = ∞ in h (forced by F1 at σ = (p, p));
- **(x, iss) ∉ Cap** (expected Cap = {(x, adp)});
- **w1 present and referenced**, at t = 0;
- σ = (p, p); a1 absent and the act in slot a2; Ref = {(ρ1, a2), (ρ1, w1)}; con none; r = 0; c = c1.

*Why:* D-st⁺ forces f(a2) ∈ {0, 1}. f = 0 makes w1 violate. f = 1 creates a change at 1 that no iss-capable act explains.

**Conj P1.3 (least counterexample to (ii)-S under R-d).** It precedes P1.2's in the order. Characteristics:
- **one adopted issue act** (prep 0, Cap = {(x, adp)}) **with value p ∈ P**, d = 0 (f = 0 in h);
- w1 referenced at t = 0; σ = (p, p); the act in slot a2; Ref = {(ρ1, a2), (ρ1, w1)}.

The **h_v half** fails: σ_v ≡ q is impossible, because the adopted act must enter into force with value p. The h_c half exists for this h.

**Conj P1.4 (least witness for O_¬σf(H-B) ⊄ O_¬σf(H-D⁺ ∩ R_B)).** Characteristics:
- **two adopted issue acts** (both prep 0, d = 0) **with different values** (a1 with v = p, a2 with v = q);
- **Cap = {(x, adp)}** (no iss-capability);
- w1 referenced at t = 0; σ = (p, p); f(a1) = 0, f(a2) = ∞ in h.

No single-act value is a witness.

### 1.3 R-g (existential work-responsive change)

In U, H-B^g consists exactly of the histories with σ = (p, q), w1 present, referenced and at t = 0, and some referenced act (B-chg holds through w1).

| Check | Prediction | Basis |
|---|---|---|
| (i)-S | **FAILS** | V1A Lem V3.9(a), (c) |
| (i)-O | **HOLDS** (all four (r, c)) | V1A Lem V3.10(a) with N = 1, P ≠ V |
| (ii)-S | **FAILS** (only the h_c half fails) | V1A Lem V3.9; Lemma M (ii-v) holds |
| (ii)-O | **HOLDS** | V1A Lem V3.10(b) |
| (iii)-L | **FAILS** | V1A Lem V3.9(e) |
| O_¬σf | O_¬σf(H-B^g) ⊊ O_¬σf(H-D ∩ R_B) = O_¬σf(R_B). The difference is **exactly** the values with t(w1) = 1. Also O_¬σf(H-D ∩ H-B^g) ⊊ O_¬σf(H-B^g): every value of the intersection has Cap = {(x, iss), (x, adp)} and a present iss act with v = q that is adopted (prep 0, or the object of the other slot's adp act) | V1A Lem V3.9(c), (d); hand computation |
| Cap on the intersection | the only Cap value occurring in H-D ∩ H-B^g is {(x, iss), (x, adp)}; in H-B^g and in H-D ∩ R_B all four Cap values occur | V1A Lem V3.9(d) (base-frame form: Cap contains an iss pair and an adp pair), specialized to 𝒜 = {x} |

**Conj P1.5 (least counterexample to (i)-S and to (ii)-S under R-g).** It is the least member of R_B overall. Characteristics:
- **Cap = ∅** (no iss-capable act exists, so under D-chg σ cannot change, while B-chg^g demands a change);
- σ = (p, p); a1 absent; a2 the least admissible issue act (d = 0, v = p, prep 0, f = 0); w1 referenced at t = 0; Ref = {(ρ1, a2), (ρ1, w1)}; con none; r = 0; c = c1.

**Conj P1.6 (least counterexample to (iii)-L under R-g).** Characteristics:
- **w1 violating at t = 1 = N** (essential: no change point can follow the only work event, whatever σ′ is);
- σ = (p, q); a1 the act that explains the change in H-D: iss, d = 0, v = q, prep 0, f = 1; Cap = {(x, iss), (x, adp)}; a2 absent;
- Ref = {(ρ1, a1), (ρ1, w1)}; con none; r = 0; c = c1.

### 1.4 R-h, (iii) only (universal reference)

| Check | Prediction | Basis |
|---|---|---|
| (i)-S, (i)-O, (ii)-S, (ii)-O | **HOLD** (Lemma M; listed for completeness, not a sealed prediction) | V1A Lem V3.11 |
| (iii)-L | **FAILS**; in particular it fails at **I7**, where R4 K8.7 says (iii) holds in U | V1A Lem V3.12(b) |
| (iii) with a2 added **referenced** (CM-A7(b) form) | **HOLDS** for every h in K7's scope | V1A Lem V3.12(c) |

**Conj P1.7 (least counterexample to (iii)-L under R-h).** Characteristics:
- **σ = (q, q), w1 violating at t = 1**, w1 referenced;
- **the only act (slot a1) has value q**, so no act of h can be retimed to carry the repair value p: iss, d = 0, prep 0, f = 0, adopted through Cap = {(x, adp)};
- a2 absent; Ref = {(ρ1, a1), (ρ1, w1)}; con none; r = 0; c = c1.

Histories whose only act has value p are **not** counterexamples: f′(a1) := 1 carries the change.

### 1.5 (r1) R-i and R-i′ (record clause E ≠ ∅, or none)

| Check | Prediction (R-i and R-i′ alike) | Basis |
|---|---|---|
| (i)-S | **FAILS** | V1A Lem V3.13(b) |
| (i)-O | **HOLDS** (all four (r, c)); the base-pair failure at o = ∅ is **UNTESTABLE IN U** (ρ1 always present) | V1A Lem V3.13(c), V3.14(a), Rem V3.14a |
| (ii)-S | **FAILS** (both halves, at every h in scope with no act referenced) | V1A Lem V3.14(b) |
| (ii)-O | **HOLDS** | V1A Lem V3.14(b) (U satisfies A3, A4, A7 for (+act), (+ref)) |
| (iii)-L | **HOLDS** (scope H-D^𝐑 ∩ R_B^𝐑 = H-D, a2 absent, w1 violating) | V1A Lem V3.14(c) |
| O₃ = (O, Ref) | O₃(H-D ∩ R_B^𝐑) ⊊ O₃(H-B^𝐑). The difference is **exactly** the values whose Ref contains no act pair | V1A Lem V3.13(d); hand computation: from h ∈ H-B^𝐑 with an act referenced, h[σ ≡ p, f ≡ ∞] ∈ H-D with the same O₃; with no act referenced, D-rec fails throughout the O₃-fibre |

**Conj P1.10 (least counterexample to (i)-S under R-i).** It is the least member of R_B^i overall. Characteristics:
- **no present act slot is in Ref** (essential: D-rec fails throughout the ≈_{σ, f}-class); **some act slot is present**;
- σ = (p, p); a1 absent; a2 the least admissible issue act (d = 0, v = p, prep 0, f = 0); Cap = ∅; w1 absent; Ref = ∅; con none; r = 0; c = c1.

**Conj P1.11 (least counterexample to (ii)-S under R-i).** Characteristics:
- **w1 referenced, and no present act slot in Ref**;
- σ = (p, p); a1 absent; a2 as in P1.10; Cap = ∅; w1 at t = 0; Ref = {(ρ1, w1)}; con none; r = 0; c = c1.

**Conj P1.12 (least counterexamples under R-i′).** For (i)-S: the least history of U: **no present act slot in Ref**; σ = (p, p); a1 and a2 absent; Cap = ∅; w1 absent; Ref = ∅; r = 0; c = c1. For (ii)-S: **w1 referenced and no present act slot in Ref**; σ = (p, p); a1 and a2 absent; Cap = ∅; w1 at t = 0; Ref = {(ρ1, w1)}; r = 0; c = c1.

### 1.6 (r1) R-j (D-side descriptive change)

| Check | Prediction | Basis |
|---|---|---|
| (i)-S | **FAILS** | V1A Lem V3.15(b) |
| (i)-O | **HOLDS** (all four (r, c)) | V1A Lem V3.16(a) with N = 1, P ≠ V |
| (ii)-S | **FAILS** (the h_v half fails at every h in scope) | V1A Lem V3.15(d) |
| (ii)-O | **FAILS** (the h_v half fails at every h in scope: H-D^j has no constant σ) | V1A Lem V3.15(d) |
| (iii)-L | **HOLDS** | V1A Lem V3.16(b) |
| Cap on H-D^j | the only Cap value occurring in H-D^j is {(x, iss), (x, adp)} | V1A Lem V3.16(c), specialized to 𝒜 = {x} |
| O_¬σf | O_¬σf(H-D^j ∩ R_B) ⊊ O_¬σf(H-B) | V1A Lem V3.16(c) |

**Conj P1.13 (least counterexample to (i)-S, (ii)-S and (ii)-O under R-j).** It is the least member of R_B overall (the history of P1.5). Characteristics:
- **Cap = ∅** for (i)-S (no iss-capable act, so σ cannot change in H-D^j); for (ii)-S and (ii)-O every h in scope is a counterexample, so the least one is the least member of R_B with w1 referenced;
- σ = (p, p); a1 absent; a2 (iss, d = 0, v = p, prep 0, f = 0); w1 referenced at t = 0; Ref = {(ρ1, a2), (ρ1, w1)}; con none; r = 0; c = c1.

### 1.7 (r1) R-g⁺ (R-g with its act-existential conjunct)

In U, H-B^g⁺ consists exactly of the members of H-B^g in which some present iss slot has f = 1 and v = q.

| Check | Prediction | Basis |
|---|---|---|
| (i)-S | **FAILS**; least counterexample as P1.5 | V1A Lem V3.17 |
| (i)-O | **HOLDS** (all four (r, c)) | V1A Lem V3.17 (V3.10(a)) |
| (ii)-S | **FAILS** (only the h_c half fails); least counterexample as P1.5 | V1A Lem V3.17 |
| (ii)-O | **HOLDS** | V1A Lem V3.17 (V3.10(b)) |
| (iii)-L | **FAILS**; least counterexample as P1.6 (K7's third constraint already forces an act entering at 1 with the new value) | V1A Lem V3.17 |
| O_¬σf | O_¬σf(H-B^g⁺) ⊊ O_¬σf(R_B). The difference is **exactly** the values with t(w1) = 1 **or** with no present iss slot of value q | hand computation: with w1 at 0 and an iss slot s of value q, set σ′ = (p, q), f′(s) = 1 and f′ = ∞ elsewhere |

### 1.8 Combinations and untestable readings
- **Conj P1.8 (R-c ∘ R-d)** (V1A Lem V4.1(d)): (i)-O **HOLDS**; (i)-S **FAILS**, with least counterexample equal to P1.1's. (r1, corrected reason) R-c ∘ R-d has the record base R_B^c (the D-rec class), P1.1's history is the least member of that class, and it is a counterexample because w1 is absent, so no history ≈_{σ, f} it satisfies B-conf^b.
- **R-a** and **R-a ∘ R-g**: **UNTESTABLE IN U** (one target).
- **Conj P1.9 (R-g at N = 0)** (V1A Lem V3.9(b)): (r1, reframed) at N = 0 R-g is **inconsistent** (H-B^g = ∅); this is not a D1 failure. **UNTESTABLE IN U** (N = 1). In a T = {0} slice (V2), H-B^g would be reported empty. (r1) The same holds for R-j (H-D^j = ∅ at N = 0; V1A Lem V3.15(c)).
- (r1) **R-i and R-i′ at o = ∅**: **UNTESTABLE IN U** (V1A Rem V3.14a).

---

## 2. Planted positive controls

Each control has a required output, given as fields of Def P0.3 with exact values. **If any control is not detected, the V1b run is invalid for that reading.** Its predictions are then not scored. (r1) No control depends on which counterexample is least.

### 2.1 Common controls (every reading in §1)

**Def C-U5 (A5 broken).** Frame U₅ ("c(ρ1) = c2 ⇒ σ ≡ q") with the reading's classes, evaluated as below.

**Conj C-U5.R (required output).**
- *R-c, R-d, R-g, R-g⁺:* (i)-O must **FAIL**: `[𝐑 | U₅] diff(O; H-D^𝐑 ∩ R_B^𝐑, H-B^𝐑) = {(0, c2), (1, c2)}`. Reasons: under R-c, H-B^c needs a conforming w1, impossible at σ ≡ q; under R-d, H-B is unchanged and w1 violates; under R-g and R-g⁺, σ ≡ q has no change point.
- *(r1) R-j:* `[R-j | U₅] diff(O; R_B, H-D^j ∩ H-B) = {(0, c2), (1, c2)}` (σ ≡ q has no change point, so H-D^j has no c2 history).
- *(r1, rewritten) R-h:* a **per-history evaluation** of (iii)-L at the named history H_U5h: σ = (q, q); a1 iss, d = 0, v = q, prep 1, f = ∞, con none; a2 absent; Cap = ∅; w1 at t = 0, referenced; Ref = {(ρ1, a1), (ρ1, w1)}; r = 0; c = c2. **Required:** `[R-h | U] iiiL(H_U5h) = true` **and** `[R-h | U₅] iiiL(H_U5h) = false`. (In U, σ′ = (q → p at 0) = (p, q) with f′(a1) = 1 carries the change and needs no added act; in U₅, σ′ = (p, q) is excluded at c2.)
- *(r1) R-i, R-i′:* a per-history evaluation of (ii)-O at H_U5i: σ = (q, q); a1 iss, d = 0, v = q, prep 1, f = ∞, con none; a2 absent; Cap = ∅; w1 at t = 0, referenced; Ref = {(ρ1, a1), (ρ1, w1)}; r = 0; c = c2. **Required:** `[𝐑 | U₅] iiO_c(H_U5i) = false` **and** `[𝐑 | U₅] iiO_c(H_U5i[c := c1]) = true`. ((i)-O is not broken by U₅ under these readings, because H-B^𝐑 admits σ ≡ q with w1 absent; the contrast (ii) is.)

**Def C-O1 (O₁ sees σ).** O₁ = (O, σ) with the reading's classes.

**Conj C-O1.R (required output, for every o; the σ-component is listed).**
- *R-c:* `diff(O₁; H-D^c ∩ R_B^c, H-B^c) = {(q, q)}`.
- *R-d:* `diff(O₁; H-D⁺ ∩ R_B, H-B) = {(q, q)}` and `image(O₁; H-D⁺ ∩ H-B) = image(O₁; H-B) = {(p, p), (p, q), (q, p)}`.
- *R-g:* `image(O₁; H-B^g) = image(O₁; H-D ∩ H-B^g) = {(p, q)}` and `image(O₁; H-D ∩ R_B) = image(O₁; R_B) =` all four σ values. **The presence of (p, p) in `diff(O₁; H-D ∩ R_B, H-B^g)` is diagnostic:** if (p, p) is in `image(O₁; H-B^g)`, R-g's existential conjunct was not implemented.
- *R-h:* `diff(O₁; H-D ∩ R_B^h, H-B^h) = {(q, q)}` (as R4 K8.1).
- *(r1) R-g⁺:* as R-g. It diagnoses the work conjunct, not the act conjunct (C-g⁺ does that).
- *(r1) R-i, R-i′:* `diff(O₁; H-D ∩ R_B^𝐑, H-B^𝐑) = ∅` (H-B^𝐑 contains σ = (q, q) with w1 absent). Diagnostic: R4's classes give {(q, q)}.
- *(r1) R-j:* `image(O₁; H-D^j ∩ R_B) = {(p, q), (q, p)}`. Diagnostic: no constant σ value.

(r1) **C-O1 is not diagnostic of the reading for R-c, R-d and R-h:** for these readings its required output equals the output under R4's own classes (R4 K8.1). A pass shows only that O₁ is implemented; it is not evidence that the reading's classes were implemented. For these readings validity rests on C-U5 and the reading-specific control.

### 2.2 Reading-specific controls

**Def C-c (R-c: the W = ∅ break).** Planted history H_c: σ = (q, q); a1 iss, v = q, prep 1, f = ∞, d = 0, referenced; a2 absent; Cap = ∅; w1 absent; Ref = {(ρ1, a1)}; con none; r = 0; c = c1 (R4's CM-A3a instance in U). Observation O₄ (presence of w1).

**Conj C-c.R.** `[R-c | U] member(R_B^c, H_c) = true`; `[R-c | U] iS(H_c) = false`; and, for every o, `diff(O₄; H-D^c ∩ R_B^c, H-B^c)` contains (o, absent) and not (o, present). Under R4's original classes (K8.4) the value "absent" separates H-D from H-B but lies outside R_B. Under R-c it lies inside R_B^c, so (i) breaks for an observation that satisfies A1 and A2.

**Def C-d (R-d: adoption without entry into force).**
- H_d: σ = (p, p); a1 absent; a2 iss, d = 0, v = q, prep 0, f = ∞; Cap = {(x, adp)}; w1 at t = 0; Ref = {(ρ1, a2), (ρ1, w1)}; con none; r = 0; c = c1.
- H_d2 (P1.4 shape): a1 iss, d = 0, v = p, prep 0, f = 0; a2 iss, d = 0, v = q, prep 0, f = ∞; Cap = {(x, adp)}; w1 at 0, referenced; Ref = {(ρ1, a2), (ρ1, w1)}; σ = (p, p); con none; r = 0; c = c1.

**Conj C-d.R.** `[R-d | U] iS(H_d) = false` **and** `[R-d | U] K4b(H_d) = true` (both required: together they show that the run distinguishes D-st⁺ from D-st and implements R4's Cap repair); `[R-d | U] member(H-B, H_d2) = true` and "no h′ ≈_{σ, f} H_d2 is in H-D⁺" reported as `[R-d | U] exists(H-D⁺, ≈_{σ, f} H_d2) = false`.

**Def C-g (R-g: a change demanded).**
- H_g: σ = (p, q); a1 iss, d = 0, v = q, prep 0, f = 1; a2 absent; Cap = {(x, iss), (x, adp)}; w1 at t = 0, referenced; Ref = {(ρ1, a1), (ρ1, w1)}; con none; r = 0; c = c1.
- H_g′: H_g with Cap = {(x, adp)}.

**Conj C-g.R.** `[R-g | U] diff(O_¬σf; R_B, H-B^g)` = exactly the values with t(w1) = 1; `[R-g | U] member(H-D, H_g) = true` and `member(H-B^g, H_g) = true` (a positive check: if H-B^g or the intersection is reported empty, the reading was mis-implemented); `[R-g | U] member(H-B^g, H_g′) = true` and `member(H-D, H_g′) = false`.

**Def C-h (r1, rewritten: R-h, (iii) evaluated at I7).** Evaluate (iii)-L at the named history I7 (R4 §7.1) with r(ρ1) = 0 and c(ρ1) = c1, under R-h and under R4's classes 𝐑₀. Also evaluate the named history W7: σ = (q, p); a1 as in I7 (iss, d = 0, v = q, prep 1, f = ∞, referenced, con none); a2 iss, d = 1, v = p, prep 1, f = 1, **referenced**, con none; w1 at t = 1, referenced; Ref = {(ρ1, a1), (ρ1, a2), (ρ1, w1)}; Cap = ∅; r = 0; c = c1 (the CM-A7(b)-form witness).

**Conj C-h.R.** `[R-h | U] iiiL(I7) = false`; `[𝐑₀ | U] iiiL(I7) = true` (R4 K8.7: (iii) holds at I7 in U); `[R-h | U] member(H-B^h, W7) = true`; and O(W7) = O(I7) = (0, c1). No output about the least counterexample is part of this control.

**(r1) Def C-i (R-i, R-i′: a record base outside D-rec).** H_i: σ = (p, p); a1 iss, d = 0, v = p, prep 1, f = ∞, unreferenced, con none; a2 absent; Cap = ∅; w1 at t = 0, referenced; Ref = {(ρ1, w1)}; r = 0; c = c1. H_∅: the history with a1, a2 and w1 absent, σ = (p, p), Cap = ∅, Ref = ∅, r = 0, c = c1.

**Conj C-i.R.** For 𝐑 ∈ {R-i, R-i′}: `[𝐑 | U] member(R_B^𝐑, H_i) = true`, `member(H-B^𝐑, H_i) = true`, `member(H-D, H_i) = false`, `iS(H_i) = false`; `[𝐑₀ | U] member(R_B, H_i) = false`. Distinguishing the two: `[R-i | U] member(R_B^i, H_∅) = false` and `[R-i′ | U] member(H-B^{i′}, H_∅) = true`.

**(r1) Def C-j (R-j: a change demanded on the D side).** H_j: σ = (p, p); a1 absent; a2 iss, d = 0, v = p, prep 0, f = 0; Cap = {(x, iss), (x, adp)}; w1 at t = 0, referenced; Ref = {(ρ1, a2), (ρ1, w1)}; con none; r = 0; c = c1. H_g as in C-g.

**Conj C-j.R.** `[𝐑₀ | U] member(H-D, H_j) = true` and `[R-j | U] member(H-D^j, H_j) = false`; `[R-j | U] member(H-D^j, H_g) = true` and `member(H-B, H_g) = true` (positive membership); `[R-j | U] image(Cap; H-D^j) = {{(x, iss), (x, adp)}}`.

**(r1) Def C-g⁺ (R-g⁺: the act conjunct).** H_g⁺: σ = (p, q); a1 iss, d = 0, v = q, prep 1, f = ∞; a2 absent; Cap = ∅; w1 at t = 0, referenced; Ref = {(ρ1, a1), (ρ1, w1)}; con none; r = 0; c = c1. H_g as in C-g.

**Conj C-g⁺.R.** `[R-g | U] member(H-B^g, H_g⁺) = true` and `[R-g⁺ | U] member(H-B^g⁺, H_g⁺) = false` (no act enters at 1); `[R-g⁺ | U] member(H-D, H_g) = true` and `member(H-B^g⁺, H_g) = true`.

---

## 3. Scoring rule (fixed before the run)

**Def P3.1 (scoring rule).**
- **Detected control:** a control is detected iff every field listed in its ".R" Conj is reported with exactly the listed value.
- **Valid run for a reading:** every control in §2 for that reading (C-U5, C-O1 and the reading-specific control) is detected.
- **Falsifier (matrix V1b):** in a valid run, any outcome in §1 differs from the sealed prediction. This includes a HOLDS/FAILS flip, a different exact difference set where one is stated as "exactly", or a least counterexample that lacks a characteristic marked essential (**bold**). A mismatch only in a non-bold coordinate of a least counterexample is a **minor deviation**. It is reported, and it does not falsify the formal claim.
- **Consequences:** a falsification under R-c, R-d, R-g, (r1) R-i, R-i′, R-j or R-g⁺ bears on V1A's Lem V3.3–V3.10 and (r1) V3.13–V3.17. A failure under an M reading (R-b, R-e, R-f; R-h's (i) and (ii); R-a is untestable) bears on Lemma M itself (V1A Rem V3.0a). Either goes to the human, under the matrix's rule that a reading-level break of D1 needs a decision on revising R4.
- **Not predicted:** counts of any kind; outcomes for observations not named here.
