#!/usr/bin/env python3
"""POST-HOC exploratory follow-up to o5_experiment.py (declared as post hoc; not part of the predeclared protocol).
X1: machines where NO mu-optimal N=2 realization is quotient-type (approximation needs non-kernel information).
X2: print the smallest such machine with its best quotient-type loss vs global optimum, for hand verification."""
import random, sys
sys.path.insert(0, ".")
from o5_experiment import draw, optimum, quotient_type, loss, candidates, SEED, EPS

rnd = random.Random(SEED)
machines = [draw(rnd) for _ in range(40)]
none_q, ex = 0, None
for M in machines:
    L, A = optimum(M, 2, 0.5)
    if not any(quotient_type(M, C) for C, _ in A):
        none_q += 1
        bq = min(loss(M, C, f, 0.5) for C, f in candidates(2) if quotient_type(M, C))
        if ex is None or len(M[0]) < len(ex[0][0]):
            ex = (M, L, bq, A[0])
print(f"X1 no optimum is quotient-type: {none_q}/40")
if ex:
    (tab, out), L, bq, (C, f) = ex
    print("X2 machine table", tab, "outputs", out)
    print(f"   global optimum {L:.6f} by C={C} f={f}; best quotient-type {bq:.6f}")
