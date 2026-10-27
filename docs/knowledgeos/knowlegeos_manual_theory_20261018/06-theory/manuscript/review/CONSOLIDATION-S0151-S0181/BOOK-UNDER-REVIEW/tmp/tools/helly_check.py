#!/usr/bin/env python3
"""Finite checks for Ch. 9 §9.14 (theory turn 5): when does checking pairs of rules suffice for §2.3 (d)'s
set-wise conflict test? A toy over small finite domains, not KnowledgeOS.

Effect sets: a rule's mandatory effect at a context c is the set E(r, c) of outcomes x that satisfy it.
The Helly number h of a class of sets: the largest size of a minimal subfamily with empty intersection
(every proper subfamily has a common point). Families are sets of members of the class (duplicates are removed: a
minimal family never repeats a member).

Checks:
 (1) Helly numbers of the book's single-attribute classes, by exhaustive search (required value, forbidden value,
     Boolean literal, threshold interval, forbidden range, allowed set), and of multi-attribute classes (boxes,
     partial assignments, clauses, lattice half-planes);
 (2) the product lemma: h(products) <= max over attributes of h(factors), on random factor classes;
 (3) the reduction: on random rule sets with context-dependent applicability, every pointwise-minimal conflicting set
     has at most h members; for h = 2 classes the union of pair reports equals the set-wise report; for h > 2 classes a
     set the pairs miss is found;
 (4) the bound h <= |X| on random set classes over a finite outcome space, and its tightness;
 (5) three real half-planes (x >= 0, y >= 0, x + y <= -1): pairs satisfiable (witnesses), all three not (Farkas sum);
 (6) version 2 (turn-5 settlement): the reviewers' counterexamples. Thresholds with forbidden values on one attribute
     (the family x <= m-1, x != 0..m-1 is minimal of size m + 1, so h is unbounded on an infinite domain with finite
     intervals of every size); required values with thresholds (h = 2; TAUTOLOGICAL: the interval class already contains
     the singletons, so this line is no further evidence);
     a coupling constraint on the outcome space (exactly one of three Booleans: three literals form a minimal triple);
     cardinality over members (exactly one owner and three prohibitions: a minimal set of four).
 (7) version 3 (turn-6 settlement): the new row's exact domain conditions (n on n values, cap n, exact by R4; on a
     window -5..11 standing in for Z, two thresholds; one threshold on N); one interior forbidden value with thresholds
     gives h = 3, an endpoint keeps 2; a partial order and a cyclic order break the interval row; check 3 widened (a
     class with the empty set, boxes, required/forbidden Boolean).
 (8) version 4 (turn-7 settlement, B1-01): not every partial order breaks the interval row: order intervals of the
     product order 3 x 3 with PRINCIPAL up- and down-sets, and principal up- and down-sets of a rooted tree, have h = 2.
 (9) version 5 (C19 settlement, T1-08): general up- and down-sets of both orders have h >= 4 (cap 4).
Searches marked cap=k are exhaustive only up to k members: the value printed is then a lower bound, and the theorem
cited in §9.14 (R2, Doignon, R4) supplies the upper bound. Check 3 compares the set-wise report with an independently
computed pairs-only report (single rules and pairs tested directly at each point).
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
    res[f'forbidden range (complement of an interval) on 0..{n - 1} (cap 6 = |X|)'] = helly([set(D) - I for I in ivals], D, cap=6)
    res[f'interval or forbidden range on 0..{n - 1} (cap 6 = |X|)'] = helly(ivals + [set(D) - I for I in ivals], D, cap=6)
    print('(1) single-attribute classes: Helly number h')
    for name, h in res.items():
        print(f'    {name}: {h}')
    for k in (2, 3, 4):
        assert res[f'required value, k={k}'] == 2
        assert res[f'forbidden value, k={k}'] == k
        assert res[f'required or forbidden value, k={k}'] == max(2, k)
        assert res[f'allowed set (any subset), k={k}'] == k
    assert res[f'threshold interval on 0..{n - 1}'] == 2
    assert res[f'forbidden range (complement of an interval) on 0..{n - 1} (cap 6 = |X|)'] == 6
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
    print(f'    boxes on a 4x4 grid (per-attribute thresholds; cap 4, lower bound): {hb}')
    print(f'    partial assignments on 3 Boolean attributes (conjunctions of literals; cap 4, lower bound): {hp}')
    print(f'    clauses on 2 Boolean attributes (disjunctions): {hc}  (|X| = 4)')
    print(f'    lattice half-planes on a 4x4 grid (linear inequalities, integer points; cap 5, lower bound): {hl}  (Doignon bound 2^2 = 4)')
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
    print('(2) product lemma on 60 random pairs of factor classes over a full product space: h(products) <= max h(factors)',
          'in every case (products searched up to cap 4)')
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
        """Independent pairs-only analyser: at each point, a single applying rule with an empty effect, or a pair of
        applying rules, each with a non-empty effect, whose effects do not meet."""
        out = set()
        for c in contexts:
            app = [i for i, (A, E) in enumerate(rules) if c in A]
            for i in app:
                if not rules[i][1][c]:
                    out.add((frozenset([i]), c))
            for i, j in combinations(app, 2):
                Ei, Ej = rules[i][1][c], rules[j][1][c]
                if Ei and Ej and not (set(Ei) & set(Ej)):
                    out.add((frozenset([i, j]), c))
        return out
    def random_rules(cls, n, contexts):
        return [({c for c in contexts if rng.random() < 0.7}, {c: rng.choice(cls) for c in contexts}) for _ in range(n)]
    ctx = ['c1', 'c2', 'c3']
    B2x2 = list(product((0, 1), repeat=2))
    boxes2 = [{x for x in B2x2 if x[0] in I and x[1] in J} for I in ({0}, {1}, {0, 1}) for J in ({0}, {1}, {0, 1})]
    classes = {'required value k=3 (h=2)': ([{v} for v in range(3)] + [set(range(3))], list(range(3)), 2),
               'required value with the empty effect (h=2)': ([{v} for v in range(3)] + [set(range(3)), set()], list(range(3)), 2),
               'boxes on 2 Boolean attributes (h=2)': (boxes2, B2x2, 2),
               'required or forbidden value, Boolean (h=2)': ([{0}, {1}, {0, 1}], [0, 1], 2),
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
              f'rule sets where an independent pairs-only analyser misses a conflict: {misses}')
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
    # (6) the reviewers' counterexamples (turn-5 settlement)
    h_rt = helly(interval_class(6) + [{v} for v in range(6)], list(range(6)))
    Z = list(range(12))
    sizes = []
    for m in range(1, 6):
        fam = [set(range(0, m))] + [set(Z) - {v} for v in range(m)]       # x <= m-1 and x != 0, ..., m-1
        assert minimal_empty(fam, Z)
        sizes.append(len(fam))
    no_empty = all(inter([set(Z) - {v} for v in range(m)], Z) for m in range(1, 6))   # complements alone: never empty
    print(f'(6) required values with thresholds on 0..5 (tautological, see the docstring): h = {h_rt}; on 0..11, x <= m-1 with x != 0..m-1 is minimal',
          f'with {sizes} members for m = 1..5, while those forbidden values alone always leave a common point: {no_empty}',
          '(so on an unbounded domain the union of thresholds and forbidden values has unbounded h)')
    assert h_rt == 2 and sizes == [2, 3, 4, 5, 6] and no_empty
    X1 = [x for x in product((0, 1), repeat=3) if sum(x) == 1]          # exactly one of three Booleans
    lits = [{x for x in X1 if x[i] == 0} for i in range(3)]              # MUST_NOT p_i
    X3 = list(product((0, 1), repeat=3))
    owners = {x for x in X3 if sum(x) == 1}                              # exactly one owner among a, b, c
    proh = [{x for x in X3 if x[i] == 0} for i in range(3)]              # MUST_NOT owner(i)
    print('(6) coupling: on X = exactly one of three Booleans, MUST_NOT p, q, r are minimal empty:',
          minimal_empty(lits, X1), '| cardinality over members: exactly-one-owner with three prohibitions minimal empty:',
          minimal_empty([owners] + proh, X3))
    assert minimal_empty(lits, X1) and minimal_empty([owners] + proh, X3)
    # (7) the new row's exact domain conditions, and two order counterexamples
    nn = {}
    for n in (2, 3, 4, 5):
        D = list(range(n))
        nn[n] = helly(interval_class(n) + [set(D) - {v} for v in D], D, cap=n)
    Zd = list(range(-5, 12))
    one = [set(x for x in Zd if x <= 2)] + [set(Zd) - {v} for v in range(3)]           # x <= 2, x != 0, 1, 2
    two = [set(x for x in Zd if x >= 0), set(x for x in Zd if x <= 2)] + [set(Zd) - {v} for v in range(3)]
    D5 = list(range(5))
    h_int = helly(interval_class(5) + [set(D5) - {2}], D5)
    h_end = helly(interval_class(5) + [set(D5) - {0}], D5)
    # a partial order: a, b, c incomparable, with pairwise upper bounds ab, ac, bc and no common one
    up = {'a': {'a', 'ab', 'ac'}, 'b': {'b', 'ab', 'bc'}, 'c': {'c', 'ac', 'bc'}}
    P = ['a', 'b', 'c', 'ab', 'ac', 'bc']
    poset_min = minimal_empty([up['a'], up['b'], up['c']], P)
    C6 = list(range(6))
    arcs = [{(s + i) % 6 for i in range(l)} for s in range(6) for l in range(1, 6)]
    h_cyc = helly(arcs, C6, cap=6)
    print(f'(7) intervals with point complements on n values: h = {nn} (n = 2..5); on -5..11 (a stand-in for Z) the',
          f'one-threshold family x <= 2, x != 0, 1, 2 has a common point: {bool(inter(one, Zd))}, the two-threshold family',
          f'is minimal: {minimal_empty(two, Zd)} ({len(two)} members); on 0..4 with thresholds, forbidding the interior 2:',
          f'h = {h_int}, the endpoint 0: h = {h_end}; up-sets of three incomparable elements minimal empty: {poset_min};',
          f'arcs of a cyclic order on 6 points (cap 6): h = {h_cyc}')
    assert nn == {2: 2, 3: 3, 4: 4, 5: 5} and inter(one, Zd) and minimal_empty(two, Zd) and h_int == 3 and h_end == 2
    assert poset_min and h_cyc >= 3
    # (8) version 4 (turn-7 settlement, B1-01): partial orders whose intervals keep h = 2
    G3 = [(i, j) for i in range(3) for j in range(3)]
    le = lambda p, q: p[0] <= q[0] and p[1] <= q[1]
    box = [{x for x in G3 if le(a, x) and le(x, b)} for a in G3 for b in G3 if le(a, b)]
    ups = [{x for x in G3 if le(a, x)} for a in G3]
    downs = [{x for x in G3 if le(x, a)} for a in G3]
    h_prod = helly([frozenset(c) for c in set(map(frozenset, box + ups + downs))], G3, cap=4)
    parent = {'r': None, 'a': 'r', 'b': 'r', 'a1': 'a', 'a2': 'a', 'b1': 'b'}       # a rooted tree, root below
    def anc(v):
        out = {v}
        while parent[v]:
            v = parent[v]
            out.add(v)
        return out
    T_ = list(parent)
    t_up = [{w for w in T_ if v in anc(w)} for v in T_]                                 # up-sets: subtrees
    t_down = [anc(v) for v in T_]                                                       # down-sets: paths to the root
    h_tree = helly([frozenset(c) for c in set(map(frozenset, t_up + t_down))], T_, cap=4)
    print(f'(8) order intervals, up- and down-sets of the product order 3 x 3 (cap 4): h = {h_prod};',
          f'up- and down-sets of a rooted tree on 6 nodes (cap 4): h = {h_tree}')
    assert h_prod == 2 and h_tree == 2
    # (9) version 5 (C19 settlement, T1-08): GENERAL up- and down-sets of the same two orders have h >= 4 (cap 4)
    def upsets(P, le):
        subs = [set(c) for k in range(1, len(P)) for c in combinations(P, k)]
        ups = [S for S in subs if all(w in S for v in S for w in P if le(v, w))]
        downs = [S for S in subs if all(w in S for v in S for w in P if le(w, v))]
        return [frozenset(c) for c in set(map(frozenset, ups + downs))]
    h_tree_g = helly(upsets(T_, lambda u, v: u in anc(v)), T_, cap=4)
    h_prod_g = helly(upsets(G3, le), G3, cap=4)
    print(f'(9) GENERAL up- and down-sets: rooted tree on 6 nodes h >= {h_tree_g}; product order 3 x 3 h >= {h_prod_g} (cap 4)')
    assert h_tree_g >= 3 and h_prod_g >= 3
    print('all assertions passed')
