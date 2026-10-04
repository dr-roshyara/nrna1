# V1b: sealed predictions and planted controls (non-M readings only)

**SEALED. Withhold from the V1b implementer until the V1b run has produced and frozen its outputs.**

| | |
|---|---|
| Inputs (only) | `FORMAL-THEORY-R4.md` (§7.1 universe U, §7.2 checks); `HYPOTHESES.md`; `R6-VALIDATION-MATRIX.md` (V1b); `V1A-LEMMA.md` (readings and lemmas, cited as V-ids) |
| Author | the R4 / V1a analyst (same family). Sealed, **not independent** (matrix, unresolved risk 3) |
| Scope | the readings that Lemma M does not cover: **R-c, R-d, R-g** (not-M), and **R-h for (iii) only** (M holds, T fails). M readings (R-a, R-b, R-e, R-f, and R-h's (i) and (ii)) are not predicted here. Their expected outcome follows from Lemma M (V1A Rem V3.0a). R-a is **UNTESTABLE IN U** in any case (one target) |

**Labels.** Every prediction is a **Conj** (a sealed claim about the enumeration outcome). Its basis is given: a V1a lemma (proved in 𝓗, specialized to U) or a hand computation in U. Definitions are **Def**. No count is predicted.

---

## 0. Definitions of the checks (U, with a reading substituted)

**Def P0.1 (U with a reading).** U, its frame (F1, F2), its order (R-1) and its observations are as in R4 §7.1. For a reading 𝐑, H-D^𝐑, H-B^𝐑 and R_B^𝐑 are computed by replacing the clauses of R4 Def 3.1 as in V1A Def V1.3:
- **R-c:** B-conf := "w1 present and conforms"; B-rec := D-rec;
- **R-d:** D-st := D-st⁺ (an act with Adopted(s, t) for some t ∈ T must have f(s) ≠ ∞);
- **R-g:** B-chg := B-chg ∧ "σ(0) ≠ σ(1) and w1 present with t(w1) = 0" (in U this is exactly the existential conjunct of V1A Def V1.3 R-g);
- **R-h:** B-rec := D-rec ∧ B-recW ∧ "every present act slot and w1 (if present) is in Ref".

**Def P0.2 (forms of each check).** For a reading 𝐑:
- **(i)-S** = K4 with 𝐑's classes: for every h ∈ R_B^𝐑 there is h* ∈ H-D^𝐑 ∩ H-B^𝐑 with h* ≈_{σ, f} h. In U, (i)-S is equivalent to (i) for O_¬σf.
- **(i)-O** = for base O = (r, c): O(H-D^𝐑 ∩ R_B^𝐑) = O(H-B^𝐑) = O(H-D^𝐑 ∩ H-B^𝐑) = O(R_B^𝐑).
- **(ii)-S** = K6 with 𝐑's classes: for every h ∈ R_B^𝐑 with w1 referenced there are h_v ∈ H-D^𝐑 ∖ H-B^𝐑 (σ_v constant, w1 present and violating) and h_c ∈ H-D^𝐑 ∩ H-B^𝐑 (w1 conforming), both ≈_{σ, f} h.
- **(ii)-O** = the same with "same O" instead of ≈_{σ, f}.
- **(iii)-L** = K7 with 𝐑's classes. The scope is h ∈ H-D^𝐑 ∩ R_B^𝐑 with a2 absent and w1 violating, and h′ must be in H-B^𝐑, with K7's literal constraints (σ changes only at t(w1); f free; a2 optionally added as an unreferenced iss act with prep 1 and con none).
- **(ii)** and **(iii)** for a reading are said to **hold** iff the S-form and the L-form hold. Where only the O-form is predicted to hold, this is stated.

"Least counterexample" is the least h in R-1 order, as in R4 §7.1. Only its **characteristics** are predicted. Coordinates not named are expected at their least admissible value. Features in **bold** are essential (§3).

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
| Cap on the intersection | the only Cap value occurring in H-D ∩ H-B^g is {(x, iss), (x, adp)}; in H-B^g and in H-D ∩ R_B all four Cap values occur | V1A Lem V3.9(d) |

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

### 1.5 Combinations and untestable readings
- **Conj P1.8 (R-c ∘ R-d)** (V1A Rem V4.1): (i)-O **HOLDS**; (i)-S **FAILS**, with least counterexample equal to P1.1's (w1 absent precedes every case of P1.2 in the order).
- **R-a** and **R-a ∘ R-g**: **UNTESTABLE IN U** (one target).
- **Conj P1.9 (R-g at N = 0)** (V1A Lem V3.9(b), where even the base pair fails): **UNTESTABLE IN U** (N = 1). In a T = {0} slice (V2), H-B^g would be reported empty.

---

## 2. Planted positive controls

Each control has a required output. **If any control is not detected, the V1b run is invalid for that reading.** Its predictions are then not scored.

### 2.1 Common controls (every reading in §1: R-c, R-d, R-g; R-h for (iii))

**C-U5 (A5 broken).** Frame U₅ ("c(ρ1) = c2 ⇒ σ ≡ q") with the reading's classes.
- *R-c, R-d, R-g:* (i)-O must **FAIL**. **Required output:** (0, c2) and (1, c2) are both reported in O(H-D^𝐑 ∩ R_B^𝐑) ∖ O(H-B^𝐑), and no (r, c1) value is in that difference. Reasons: under R-c, H-B^c needs a conforming w1, impossible at σ ≡ q; under R-d, H-B is unchanged and w1 violates; under R-g, σ ≡ q has no change point.
- *R-h:* (iii)-L must **FAIL** in U₅, with a reported counterexample that has c(ρ1) = c2. (Conj: the least one has σ = (q, q), a1 = (iss, d = 0, v = p, prep 0, f = ∞), Cap = ∅, w1 referenced at t = 0, r = 0, c = c2. It precedes P1.7 in the order.)

**C-O1 (O₁ sees σ).** O₁ = (O, σ) with the reading's classes. The control requires separation exactly where the reading leaves a σ-difference. **Required output, for every o:**
- *R-c:* O₁(H-D^c ∩ R_B^c) ∖ O₁(H-B^c) = {σ = (q, q)}.
- *R-d:* O₁(H-D⁺ ∩ R_B) ∖ O₁(H-B) = {σ = (q, q)}, and O₁(H-D⁺ ∩ H-B) = O₁(H-B) (= the three other σ values).
- *R-g:* O₁(H-B^g) = O₁(H-D ∩ H-B^g) = {σ = (p, q)}, and O₁(H-D ∩ R_B) = O₁(R_B) = all four σ values. So the difference is {(p, p), (q, p), (q, q)}. **The presence of (p, p) in the difference is diagnostic:** if (p, p) is reported in O₁(H-B^g), R-g's existential conjunct was not implemented.
- *R-h:* O₁(H-D ∩ R_B^h) ∖ O₁(H-B^h) = {σ = (q, q)} (as R4 K8.1).

### 2.2 Reading-specific controls

**C-c (R-c: the W = ∅ break).**
- Planted history H_c: σ = (q, q); a1 iss, v = q, prep 1, f = ∞, d = 0, referenced; a2 absent; Cap = ∅; w1 absent; Ref = {(ρ1, a1)}; any r and c (R4's CM-A3a instance in U). **Required output:** H_c ∈ R_B^c, and the (i)-S check reports **no** h* for H_c.
- Under O₄ (presence of w1). **Required output**, for every o: (o, absent) ∈ O₄(H-D^c ∩ R_B^c) ∖ O₄(H-B^c), and (o, present) is in both. Under R4's original classes (K8.4) this value separates H-D from H-B but lies outside R_B. Under R-c it lies inside R_B^c, so (i) breaks for an observation that satisfies A1 and A2.

**C-d (R-d: adoption without entry into force).**
- Planted history H_d: σ = (p, p); a1 absent; a2 iss, d = 0, v = q, prep 0, f = ∞; Cap = {(x, adp)}; w1 at t = 0; Ref = {(ρ1, a2), (ρ1, w1)}; con none; any r and c. **Required output:** (i)-S reports no h* ≈_{σ, f} H_d, **and** K4b reports an h** ≈_{σ, f, Cap} H_d (with Cap = ∅). Both outputs are required. Together they show that the run distinguishes D-st⁺ from D-st and implements R4's Cap repair.
- Planted history H_d2 (P1.4 shape): a1 iss, d = 0, v = p, prep 0, f = 0; a2 iss, d = 0, v = q, prep 0, f = ∞; Cap = {(x, adp)}; w1 at 0, referenced; Ref = {(ρ1, a2), (ρ1, w1)}; σ = (p, p). **Required output:** H_d2 ∈ H-B, and no h′ ≈_{σ, f} H_d2 is in H-D⁺.

**C-g (R-g: a change demanded).**
- **Required output:** no h ∈ R_B with t(w1) = 1 has any h′ ≈_{σ, f} h in H-B^g. Equivalently, the O_¬σf difference O_¬σf(R_B) ∖ O_¬σf(H-B^g) is reported as exactly the t(w1) = 1 values.
- Planted history H_g: σ = (p, q); a1 iss, d = 0, v = q, prep 0, f = 1; a2 absent; Cap = {(x, iss), (x, adp)}; w1 at t = 0, referenced; Ref = {(ρ1, a1), (ρ1, w1)}. **Required output:** H_g ∈ H-D ∩ H-B^g. This is a positive membership check: if H-B^g or the intersection is reported empty, the reading was mis-implemented.
- The same H_g with Cap = {(x, adp)}. **Required output:** in H-B^g, not in H-D.

**C-h (R-h: (iii) at I7).**
- **Required output:** (iii)-L under R-h reports **I7** (R4 §7.1) as a counterexample. The CM-A7(b)-form witness (σ′ = (q, p); a2 iss, v = p, prep 1, d = f = 1, **referenced**, con none) is reported to exist, to lie in H-B^h and to have O equal to O(I7).

---

## 3. Scoring rule (fixed before the run)

- **Valid run for a reading:** every control in §2 for that reading is detected exactly as stated.
- **Falsifier (matrix V1b):** in a valid run, any outcome in §1 differs from the sealed prediction. This includes a HOLDS/FAILS flip, a different exact difference set where one is stated as "exactly", or a least counterexample that lacks a characteristic marked essential (**bold**). A mismatch only in a non-bold coordinate of a least counterexample is a **minor deviation**. It is reported, and it does not falsify the formal claim.
- **Consequences:** a falsification under R-c, R-d or R-g bears on V1A's Lem V3.3–V3.10. A failure under an M reading (R-b, R-e, R-f; R-h's (i) and (ii); R-a is untestable) bears on Lemma M itself (V1A Rem V3.0a). Either goes to the human, under the matrix's rule that a reading-level break of D1 needs a decision on revising R4.
- **Not predicted:** counts of any kind; outcomes for observations not named here.
