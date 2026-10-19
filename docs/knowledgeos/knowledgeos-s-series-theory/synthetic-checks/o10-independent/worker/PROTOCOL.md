# PROTOCOL v1 — independent check of C2 / C4 (worker B)

Frozen before any code is written. Any change after results = new version file (PROTOCOL-v2.md), this file untouched.

## Objects
- Σ={0,1}, n=3, L = all binary strings of length 0..3 (15 histories). Fixed index order: by length, then lexicographic
  (ε, 0, 1, 00, 01, 10, 11, 000, ..., 111).
- A relation on L that is length-preserving is encoded as a bitmask over the 35 unordered same-length pairs
  (layer sizes 1,2,4,8 give 0+1+6+28 = 35 pairs). Pair (a,b) bit set ⇔ a R b.

## Enumeration method of PC (differs from parent-class/child-grouping)
1. For each layer k=0..3, enumerate ALL set partitions of the layer independently (restricted-growth strings);
   counts expected Bell(1),Bell(2),Bell(4),Bell(8) = 1,2,15,4140.
2. Form the full Cartesian product of layer partitions (1*2*15*4140 = 124200 candidates).
3. Keep a candidate iff it is prefix-closed, verified by EXPLICIT PAIRWISE CHECK: for every same-length pair
   (h,h') related in the candidate and EVERY prefix length t in 0..|h|, check h_t π h'_t. (All t checked,
   not just t=|h|-1.)
4. Each candidate is an equivalence relation by construction (union of per-layer partitions), length-preserving
   by construction. Additionally assert reflexivity/symmetry/transitivity explicitly on each kept π.
- |PC| is reported and compared with the claimed 7176 (mismatch is recorded, not corrected).

## Derived quantities per decision vector Dv
- bad(Dv) = mask of same-length pairs with Dv(h)≠Dv(h').
- F(Dv) = {π∈PC : π & bad(Dv) = 0}.
- E* = mask of ≡*, computed directly from the definition (|h|=|h'| and Dv(h_t)=Dv(h'_t) ∀ t≤|h|).
- C2 holds on Dv ⇔ E* ∈ PC-set AND E*∈F AND every π∈F satisfies π & ~E* = 0.
- C4 truth (independent of ≡*): "∃π∈F not determining s" ⇔ ∃π∈F: π & bad(s) ≠ 0 ⇔ (OR of all π∈F) & bad(s) ≠ 0
  (exact identity, since determination is a pairwise condition). C4 holds on Dv ⇔ for ALL 2^15 functions
  s:L→{0,1}, [U & bad(s) ≠ 0] == [E* & bad(s) ≠ 0], U = OR of F. Exhaustive over all s on every instance.

## Strata
- S0 (bonus, reported): C2 and C4 over ALL 2^15 binary single-decision Dv.
- S1: exhaustive over ALL 2^15 binary single-decision Dv (Dv(h)=(b_h,)), restricted to those with |F(Dv)| ≤ 9.
  (Exhaustive, so no sampling; seed unused.)
- S2: seed 20261021, Python random.Random(20261021), single generator used in order q=0.1, 0.3, 0.5;
  100 instances per q; instance i even → single decision (b,), i odd → double decision (b1,b2);
  each bit independently 1 with probability q (random() < q), drawn in history index order
  (for double: b1 then b2 per history). NO size-based rejection.
- S3: named instances: (a) constant Dv; (b) Dv = first symbol (ε ↦ special value '-'); (c) Dv = parity of last
  symbol, i.e. last symbol (ε ↦ '-'). Also for (b),(c) the variant where ε maps to 0 is included.

## Negative candidates (must be detected wherever they differ from ≡*)
For relation candidates R (in place of ≡*), "detected on instance" ⇔ C2 with R in place of ≡* FAILS, i.e.
R not an element of PC, or R∉F, or some π∈F with π ⊄ R. "Differs" ⇔ mask(R) ≠ E*.
- N1 last step only: h R h' ⇔ |h|=|h'| ∧ Dv(h)=Dv(h'). Differs whenever two same-length histories agree at
  the end but disagree at an earlier prefix (e.g. Dv = parity of last symbol: 01 vs 11 agree at t=2 but
  disagree at t=1).
- N2 last two steps only: t∈{|h|-1,|h|}. Differs when disagreement only at t ≤ |h|-2, e.g. Dv=last symbol at
  length 3: 001 vs 101 (differ only at t=1).
- N3 identity. Differs whenever ≡* relates two distinct histories (e.g. constant Dv).
- N4 ≡* with the t=1 condition omitted (t=0 kept, t≥2 kept). Differs when two histories disagree only at t=1,
  e.g. Dv = last symbol: 01 vs 11? (t=1: 0 vs 1 differ; t=2: 1=1) → related by N4 but not by ≡*.
- N5 (C4): predicted verdict(s) = [N1 & bad(s) ≠ 0]. "Differs" ⇔ ∃ s where N5 verdict ≠ ≡* verdict;
  "detected" ⇔ ∃ s where N5 verdict ≠ C4 truth (U-based). Exhaustive over s.
Expected: when C2/C4 hold, detected count == differs count for each candidate, and each candidate differs on
at least one S3 instance (argued above), so tests are non-vacuous.

## Failure criteria
- Any instance where C2 or C4 fails ⇒ claim refuted (instance preserved in RESULTS.txt).
- Any negative candidate that differs from ≡* on an instance but is not detected ⇒ harness defect.
- A negative candidate that never differs on any instance across all strata ⇒ vacuity, reported.
- |PC| ≠ 7176 ⇒ reported as mismatch with the claim.

## Reporting
RESULTS.txt: python version, platform, |PC| and Bell counts, per-stratum instance counts, C2/C4 pass/fail counts,
distribution of |F| per stratum, per-candidate (differs, detected) counts per stratum.
