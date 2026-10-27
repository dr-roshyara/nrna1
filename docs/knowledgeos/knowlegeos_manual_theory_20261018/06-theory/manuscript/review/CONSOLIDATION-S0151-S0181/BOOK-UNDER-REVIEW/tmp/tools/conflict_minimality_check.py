#!/usr/bin/env python3
"""Brute-force check for Ch. 2 §2.3 (d): global vs pointwise minimal conflicting sets (turn 0, REPORT-A A-4, REPORT-B B-3).
Outcomes x = (p, q) ∈ {0,1}²; contexts c1, c2. R1: MUST (p ∨ q) everywhere. R2: MUST ¬p at c1, MUST ¬p ∧ ¬q at c2.
R3: MUST ¬q everywhere. All apply everywhere. A set is in conflict at c if no x satisfies all its effects at c.
Usage: python3 -I conflict_minimality_check.py"""
from itertools import product, combinations
X = list(product([0, 1], [0, 1]))
C = ['c1', 'c2']
R = {'R1': lambda c, x: x[0] or x[1],
     'R2': lambda c, x: (not x[0]) if c == 'c1' else (not x[0] and not x[1]),
     'R3': lambda c, x: not x[1]}
def conflict_at(S, c): return not any(all(R[r](c, x) for r in S) for x in X)
def conflict(S): return any(conflict_at(S, c) for c in C)
subs = [frozenset(s) for k in range(1, 4) for s in combinations(R, k)]
glob = [S for S in subs if conflict(S) and not any(conflict(T) for T in subs if T < S)]
pw = {c: [S for S in subs if conflict_at(S, c) and not any(conflict_at(T, c) for T in subs if T < S)] for c in C}
print('global minimal sets, with the contexts where each is in conflict:', [(sorted(S), [c for c in C if conflict_at(S, c)]) for S in glob])
print('pointwise minimal sets per context:', {c: [sorted(S) for S in v] for c, v in pw.items()})
assert [sorted(S) for S in glob] == [['R1', 'R2']] and [c for c in C if conflict_at(glob[0], c)] == ['c2']
assert [sorted(S) for S in pw['c1']] == [['R1', 'R2', 'R3']]
print('the conflict at c1 is reported only by the pointwise definition; all assertions passed')
