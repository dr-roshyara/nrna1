#!/usr/bin/env python3
"""EG-6 v2: quantitative rationale for the attempt cap M (synthetic arithmetic only; no data).
(1) P(PROGRAM-ACCEPTED) and expected never-passing batches under independent attempts with per-attempt batch-failure
probability q (396 batches; F2). (2) Effect of NOT-ASSESSABLE (worst case = discordant, EP-01 §7) on the exact
per-stratum upper bound U_h(d) (hypergeometric, alpha_h = 0.05/3; F2 §2 strata)."""
from fractions import Fraction
from math import comb

B, LABELS_SAMPLED = 396, 231
print("q     M  E[never-pass batches]  P(all 396 pass)  E[NOT-ASSESSABLE sampled labels]")
for q in (0.02, 0.05, 0.1, 0.2, 0.4):
    for M in (1, 2, 3, 4, 5):
        f = q ** M
        print(f"{q:<5} {M}  {B * f:21.2f}  {(1 - f) ** B:15.4f}  {LABELS_SAMPLED * f:10.2f}")


def U(N, n, d, alpha):
    """largest D with P(X <= d | N, D, n) >= alpha (exact)."""
    best = None
    for D in range(d, N + 1):
        p = sum(Fraction(comb(D, x) * comb(N - D, n - x), comb(N, n)) for x in range(0, min(d, n) + 1))
        if p >= alpha:
            best = D
        else:
            break
    return best


a = Fraction(5, 100) / 3
print("\nstratum      N    n   U(d=0) U(d=1) U(d=2) U(d=3)   (census strata EMPTY 7, MULTIROW 4: U = d exactly)")
for name, N, n in (("HUB", 66, 33), ("DECOMPOSED", 173, 87), ("SINGLE", 1725, 100)):
    print(f"{name:11s} {N:5d} {n:4d}  " + "  ".join(f"{U(N, n, d, a):5d}" for d in range(4)))
