# R5 spot-check analysis (main analyst, from the R4 definitions; to be confirmed by the human)

Definitions used: FORMAL-THEORY-R4 Def 1.4–1.11, Def 3.1–3.2 and §7.1 (U: T = {0, 1}, 𝒯 = {τ}, V = {p, q}, P = {p}, 𝒜 = {x}, slots a1, a2, w1, ρ1).

## K8.1: O₁(H-B) ⊊ O₁(H-D ∩ R_B); the difference is exactly σ = (q, q) for every o

**1. (q, q) ∉ σ-values of H-B.**
- H-B needs B-recW, so the only work slot w1 is present and referenced.
- B-conf then needs σ(t(w1)) ∈ P = {p}.
- σ = (q, q) gives σ(t(w1)) = q ∉ P. So it is impossible.

**2. The other three values are in H-B.** In each case con = none (F2 vacuous) and r, c are free.

| σ | construction | why it works |
|---|---|---|
| (p, p) | a1 iss, f = ∞, referenced; w1 at 0, referenced | Chg = ∅, so B-chg is vacuous; F1 is vacuous |
| (p, q) | w1 at 0, referenced; a referenced act with f = ∞ | w1 conforms; the change at 1 has work at 0 < 1, so B-chg's work disjunct holds |
| (q, p) | issue act v = p, f = 1 (F1: σ(1) = p ✓); w1 at 1, referenced | the act satisfies B-chg's first disjunct (no capability needed); w1 conforms |

**3. All four values are in H-D ∩ R_B.**

| σ | construction | why it works |
|---|---|---|
| (q, q) | act with f = ∞, referenced; w1 referenced | Chg = ∅, so D-chg is vacuous; D-st is vacuous (f = ∞); H-D has no conformance clause |
| (p, p) | as for (q, q) | same |
| (p, q), (q, p) | issue act v = σ(1), f = 1, prep 0, d ≤ 1, Cap ⊇ {(x, iss), (x, adp)} | D-chg needs a capable act entering into force at 1 with the new value ✓; D-st ✓ |

**4. For every o:** O = (r(ρ1), c(ρ1)) is independent of every construction above (Lem 1.12), with con = none.

**Result: CORRECT.** The difference is {(q, q)} for each of the four o.

**Scope note:** the uniqueness rests on U's single work slot. In larger universes the difference set grows; that is an R6 item.

## K1: least h ∈ H-D ∩ H-B in the §7.1 order

**Candidate:**
- σ = (p, p); a1 absent;
- a2 = iss(d 0, v p, prep 0, f 0);
- Cap = {(x, adp)};
- w1 at t = 0;
- Ref = {(ρ1, a2), (ρ1, w1)};
- con none; r = 0; c = c1.

**Membership.**
- **Frame:**
  - F1: f(a2) = 0 ⇒ σ(0)(τ) = v(a2) = p ✓.
  - F2: vacuous ✓.
- **H-D:**
  - D-chg: Chg = ∅ ✓.
  - D-st: Adopted(a2, 0), since prep 0, (x, adp) ∈ Cap and d = 0 ≤ 0 ✓.
  - D-rec: (ρ1, a2) ✓.
- **H-B:**
  - B-chg: vacuous ✓.
  - B-conf: σ(0) = p ∈ P ✓.
  - B-recW: (ρ1, w1) ✓.
  - D-rec: ✓.

**Least in the order (σ0, σ1, a1, a2, Cap, w1, Ref, con, r, c).**
- **σ = (p, p) and a1 absent** are the minimal values.
- **a2 must be present**, because D-rec needs some act and a1 is absent. Its least code is iss (d 0, v p, prep 0, f 0). It has a feasible completion, so lexicographic minimality fixes it.
- **Cap.** Given that a2, D-st needs Adopted(a2, 0). With no adp act (a1 absent), that requires (x, adp) ∈ Cap.
  - ∅ fails.
  - {(x, iss)} fails.
  - {(x, adp)} is the least feasible value ✓.
- **w1.** Absent fails B-recW, so w1 is at t = 0, the least present value. It conforms, since σ(0) = p.
- **Ref.** Ref must contain (ρ1, w1) for B-recW. Code 1 = {(ρ1, w1)} alone fails D-rec (no act referenced). Code 2 = {(ρ1, a2)} alone fails B-recW. So code 3 = {(ρ1, a2), (ρ1, w1)} is least ✓.
- **con, r, c:** minimal values (none, 0, c1) are all feasible ✓.

**Result: CORRECT.** It is identical to the verifier's least witness.

## Corrections after the independent review (kos-theory-reviewer: K8.1 and K1 both CORRECT_WITH_GAPS; no result changes)

**G1. K8.1, H-B row (q, p):** the issue act must also be **referenced**, (ρ1, a) ∈ Ref, for D-rec ⊆ B-rec.

**G2. K8.1, H-D ∩ R_B rows (p, q) and (q, p):** complete each construction with:
- w1 present and referenced (B-recW, required by R_B);
- the act referenced (D-rec).

Either time for w1 works, because H-D has no conformance clause.

**G3. "For every o":** the right justification is that F1, F2 (vacuous with con = none) and every clause of Def 3.1 never mention r or c, so r and c are free in every construction. Lem 1.12 states the converse direction and was mis-cited.

**G4. K1 Ref:** code 0 (∅) fails trivially (neither D-rec nor B-recW holds).

**G5. K1 scope:** this check hand-verifies only "H-D ∩ H-B nonempty" (and so H-D, H-B nonempty). The remaining §7.2 K1 parts close from the verifier's witnesses:
- **H-D ∖ H-B:** the least H-D witness has w1 absent, so B-recW fails.
- **H-B ∖ H-D:** the least H-B witness has Cap = ∅, so D-st fails at f = 0.
- **Lem 3.6:** both classes require D-rec.

**Read "Result: CORRECT" for K1 as "partially hand-verified; the remaining parts follow from the verifier's witnesses".**

**Final:** K8.1 correct; K1 correct (partially hand-verified). Both match the verifier output. **Human confirmation is still required to close R5.**
