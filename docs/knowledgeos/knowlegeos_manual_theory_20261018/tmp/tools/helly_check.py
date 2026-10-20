#!/usr/bin/env python3
"""Finite checks for Ch. 9 §9.14 (theory turn 5): when does checking pairs of rules suffice for §2.3 (d)'s
set-wise conflict test? A toy over small finite domains, not KnowledgeOS.

Effect sets: a rule's mandatory effect at a context c is the set E(r, c) of outcomes x that satisfy it.
The Helly number h of a class of sets: the largest size of a minimal subfamily with empty intersection
(every proper subfamily has a common point). Families are multisets of members of the class.

Checks:
 (1) Helly numbers of the book's single-attribute classes, by exhaustive search (required value, forbidden value,
     Boolean literal, threshold interval, forbidden range, allowed set), and of multi-attribute classes (boxes,
     partial assignments, clauses, lattice half-planes);
 (2) the product lemma: h(products) <= max over attributes of h(factors), on random factor classes;
 (3) the reduction: on random rule sets with context-dependent applicability, every pointwise-minimal conflicting set
     has at most h members; for h = 2 classes the union of pair reports equals the set-wise report; for h > 2 classes a
     set the pairs miss is found;
 (4) the bound h <= |X| on random set classes over a finite outcome space, and its tightness;
 (5) three real half-planes (x >= 0, y >= 0, x + y <= -1): pairs satisfiable (witnesses), all three not (Farkas sum).
Usage: python3 -I helly_check.py
"""
import random
from itertools import combinations, product
from fractions import Fraction


def inter(fam, X):
    s = set(X)
    for A in fam:
        s &= A
    return s


def minimal_empty(fam, X):
    return not inter(fam, X) and all(inter(fam[:i] + fam[i + 1:], X) for i in range(len(fam)))


def helly(cls, X, cap=None):
    """Largest minimal empty-intersection subfamily drawn from cls (members distinct; a repeated member never helps
    minimality). Exhaustive up to cap members."""
    cls = [frozenset(A) for A in dict.fromkeys(frozenset(A) for A in cls)]
    cap = cap or len(cls)
    best = 0
    for k in range(1, min(cap, len(cls)) + 1):
        found = any(minimal_empty(list(f), X) for f in combinations(cls, k))
        if found:
            best = k
    return best


def interval_class(n):
    return [set(range(a, b + 1)) for a in range(n) for b in range(a, n)]


rng = random.Random(20261020)

if __name__ == '__main__':
    # (1) single-attribute classes
    res = {}
    for k in (2, 3, 4):
        V = list(range(k))
        req = [{v} for v in V] + [set(V)]
        forb = [set(V) - {v} for v in V] + [set(V)]
        allowed = [set(s) for r in range(0, k + 1) for s in combinations(V, r)]
        res[f'required value, k={k}'] = helly(req, V)
        res[f'forbidden value, k={k}'] = helly(forb, V)
        res[f'required or forbidden value, k={k}'] = helly(req + forb, V)
        res[f'allowed set (any subset), k={k}'] = helly(allowed, V)
    n = 6
    D = list(range(n))
    ivals = interval_class(n)
    res[f'threshold interval on 0..{n - 1}'] = helly(ivals, D)
    res[f'forbidden range (complement of an interval) on 0..{n - 1}'] = helly([set(D) - I for I in ivals], D, cap=6)
    res[f'interval or forbidden range on 0..{n - 1}'] = helly(ivals + [set(D) - I for I in ivals], D, cap=6)
    print('(1) single-attribute classes: Helly number h')
    for name, h in res.items():
        print(f'    {name}: {h}')
    for k in (2, 3, 4):
        assert res[f'required value, k={k}'] == 2
        assert res[f'forbidden value, k={k}'] == k
        assert res[f'required or forbidden value, k={k}'] == max(2, k)
        assert res[f'allowed set (any subset), k={k}'] == k
    assert res[f'threshold interval on 0..{n - 1}'] == 2
    assert res[f'forbidden range (complement of an interval) on 0..{n - 1}'] >= 3
    # multi-attribute classes
    X2 = list(product(range(4), range(4)))
    boxes = [{(x, y) for (x, y) in X2 if x in I and y in J} for I in interval_class(4) for J in interval_class(4)]
    hb = helly(boxes, X2, cap=4)
    B3 = list(product((0, 1), repeat=3))
    partial = [{x for x in B3 if all(x[i] == v for i, v in enumerate(p) if v is not None)}
               for p in product((None, 0, 1), repeat=3)]
    hp = helly(partial, B3, cap=4)
    B2 = list(product((0, 1), repeat=2))
    lits = [(i, v) for i in range(2) for v in (0, 1)]
    clauses = [{x for x in B2 if any(x[i] == v for i, v in cl)} for r in (1, 2) for cl in combinations(lits, r)]
    hc = helly(clauses, B2)
    grid = list(product(range(4), range(4)))
    halfplanes = {frozenset(p for p in grid if a * p[0] + b * p[1] <= c)
                  for a in (-2, -1, 0, 1, 2) for b in (-2, -1, 0, 1, 2) if (a, b) != (0, 0) for c in range(-8, 9)}
    hl = helly(list(halfplanes), grid, cap=5)
    print(f'    boxes on a 4x4 grid (per-attribute thresholds): {hb}')
    print(f'    partial assignments on 3 Boolean attributes (conjunctions of literals): {hp}')
    print(f'    clauses on 2 Boolean attributes (disjunctions): {hc}  (|X| = 4)')
    print(f'    lattice half-planes on a 4x4 grid (linear inequalities, integer points): {hl}  (Doignon bound 2^2 = 4)')
    assert hb == 2 and hp == 2 and hc == 4 and 3 <= hl <= 4
    # (2) the product lemma on random factor classes
    worst = 0
    for _ in range(60):
        k = rng.choice([2, 3])
        V = list(range(k))
        f1 = [set(s) for s in (rng.sample(V, rng.randint(1, k)) for _ in range(4))] + [set(V)]
        f2 = [set(s) for s in (rng.sample(V, rng.randint(1, k)) for _ in range(4))] + [set(V)]
        X = list(product(V, V))
        prods = [{(a, b) for a in A for b in B} for A in f1 for B in f2]
        h1, h2, hpr = helly(f1, V), helly(f2, V), helly(prods, X, cap=4)
        assert hpr <= max(h1, h2), (f1, f2)
        worst = max(worst, hpr - max(h1, h2))
    print('(2) product lemma on 60 random pairs of factor classes: h(products) <= max h(factors) in every case')
    # (3) the reduction, end to end
    def setwise_report(rules, contexts, X):
        """{(frozenset S, c)}: S minimal in conflict at context c (pointwise, §2.3 (d))."""
        out = set()
        for c in contexts:
            app = [i for i, (A, E) in enumerate(rules) if c in A]
            for k in range(1, len(app) + 1):
                for S in combinations(app, k):
                    if minimal_empty([rules[i][1][c] for i in S], X):
                        out.add((frozenset(S), c))
        return out
    def pairs_report(rules, contexts, X):
        return {(S, c) for (S, c) in setwise_report(rules, contexts, X) if len(S) <= 2}
    def random_rules(cls, n, contexts):
        return [({c for c in contexts if rng.random() < 0.7}, {c: rng.choice(cls) for c in contexts}) for _ in range(n)]
    ctx = ['c1', 'c2', 'c3']
    classes = {'required value k=3 (h=2)': ([{v} for v in range(3)] + [set(range(3))], list(range(3)), 2),
               'threshold intervals 0..5 (h=2)': (interval_class(6), list(range(6)), 2),
               'forbidden value k=3 (h=3)': ([set(range(3)) - {v} for v in range(3)], list(range(3)), 3),
               'clauses on 2 attributes (h=4)': (clauses, B2, 4)}
    for name, (cls, X, h) in classes.items():
        misses = biggest = 0
        for _ in range(300):
            rules = random_rules(cls, rng.randint(2, 6), ctx)
            sw = setwise_report(rules, ctx, X)
            biggest = max([biggest] + [len(S) for S, _ in sw])
            misses += sw != pairs_report(rules, ctx, X)
        print(f'(3) {name}: 300 random rule sets; largest pointwise-minimal conflicting set {biggest}; '
              f'rule sets where pairs miss a conflict: {misses}')
        assert biggest <= h
        assert (misses == 0) == (h <= 2)
    # (4) h <= |X| on random classes, and tightness
    for _ in range(200):
        m = rng.randint(2, 4)
        X = list(range(m))
        cls = [set(rng.sample(X, rng.randint(0, m))) for _ in range(rng.randint(2, 6))]
        assert helly(cls, X) <= m
    tight = all(helly([set(range(m)) - {v} for v in range(m)], list(range(m))) == m for m in (2, 3, 4, 5))
    print('(4) h <= |X| on 200 random classes; complements of points give h = |X| for |X| = 2..5:', tight)
    assert tight
    # (5) three real half-planes: pairwise satisfiable, jointly not
    H = [((-1, 0), 0), ((0, -1), 0), ((1, 1), -1)]          # a.x <= b : -x <= 0, -y <= 0, x + y <= -1
    sat = lambda p, hs: all(Fraction(a[0]) * p[0] + Fraction(a[1]) * p[1] <= b for a, b in hs)
    wit = {(0, 1): (0, 0), (0, 2): (0, -1), (1, 2): (-1, 0)}
    ok = all(sat(wit[(i, j)], [H[i], H[j]]) for i, j in wit)
    farkas = [sum(a[d] for a, _ in H) for d in (0, 1)] == [0, 0] and sum(b for _, b in H) < 0
    print('(5) x >= 0, y >= 0, x + y <= -1: each pair has a witness:', ok, '; the three sum to 0 <= -1 (Farkas):', farkas)
    assert ok and farkas
    print('all assertions passed')
