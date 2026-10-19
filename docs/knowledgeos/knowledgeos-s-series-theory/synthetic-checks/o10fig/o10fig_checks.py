#!/usr/bin/env python3
"""Implements PROTOCOL.md (sha256 24b93f0896fe6dbdb92cf6cfba0ed18a09db50715edee3ea868987fb7ed783a7). Synthetic only."""
import heapq, itertools, math, random, sys, os
sys.path.insert(0, os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", "o10a"))
import o10a_checks as X

SEED = 20261020

# ---------------- O-10f ----------------
def f1():
    h1, h2 = ((0,), 0), ((1,), 0)                     # (x, o)
    corr = lambda h: int(h[1] == h[0][0])
    return h1[1] == h2[1] and corr(h1) != corr(h2)    # same r* = o, different correctness

def determines_on(letters, key, val):
    m = {}
    return all(m.setdefault(key(l), val(l)) == val(l) for l in letters)

def f2_f3(rnd, trials=300):
    mismatch, incomparable_multi = 0, 0
    for _ in range(trials):
        pts = list(itertools.product((0, 1), repeat=3))
        letters = [p for p in pts if rnd.random() < 0.6] or [pts[0]]
        F = {x: rnd.randrange(3) for x in letters}
        outs = range(3)
        hist = [(x, o) for x in letters for o in outs]           # single-step histories (x, o)
        minimal = []
        subsets = [S for r in range(4) for S in itertools.combinations(range(3), r)]
        for S in subsets:
            proj = lambda x, S=S: tuple(x[i] for i in S)
            verifiable = determines_on(hist, lambda h: (h[1], proj(h[0])), lambda h: int(h[1] == F[h[0]]))
            det = determines_on(letters, proj, lambda x: F[x])
            mismatch += verifiable != det
        det_sets = [S for S in subsets if determines_on(letters, lambda x, S=S: tuple(x[i] for i in S), lambda x: F[x])]
        minimal = [S for S in det_sets if not any(set(T) < set(S) for T in det_sets)]
        kern = lambda S: {x: tuple(x[i] for i in S) for x in letters}
        def sub(a, b):
            m = {}
            return all(m.setdefault(a[x], b[x]) == b[x] for x in letters)
        if any(not sub(kern(A), kern(B)) and not sub(kern(B), kern(A)) for A, B in itertools.combinations(minimal, 2)):
            incomparable_multi += 1
    return mismatch, incomparable_multi, trials

def f3_named():
    letters = {"a": (0, 0), "b": (0, 1), "c": (1, 2), "e": (1, 3), "f": (2, 2)}
    F = {k: int(v[0] >= 1) for k, v in letters.items()}
    hist = [(k, o) for k in letters for o in (0, 1)]
    ver = lambda S: determines_on(hist, lambda h: (h[1], tuple(letters[h[0]][i] for i in S)), lambda h: int(h[1] == F[h[0]]))
    return {S: ver(S) for S in [(), (0,), (1,), (0, 1)]}

# ---------------- O-10i ----------------
def i1(rnd, trials=300):
    L = X.all_hist(3)
    ok = 0
    for _ in range(trials):
        D = {(h, t): rnd.randrange(2) for h in L for t in range(len(h) + 1)}
        sig = {h: tuple(D[(h, t)] for t in range(len(h) + 1)) for h in L}     # what the record at |h| must determine
        feas = X.pc_feasible(L, sig)
        star = {h: tuple(sig[h[:u]] for u in range(len(h) + 1)) for h in L}
        greatest = (any(X.subset_rel(star, f, L) and X.subset_rel(f, star, L) for f in feas)
                    and all(X.subset_rel(f, star, L) for f in feas))
        ok += greatest
    return ok, trials

def i2():
    def first_corrected(h):
        v = int(h[0]) if h[0] in "01" else None
        for a in h[1:]:
            if a == "c" and v is not None:
                v = 1 - v
        return v
    def current(h):
        return first_corrected(h) if h[-1] == "c" else int(h[-1])
    h1, h2 = "1c", "10"
    cur = lambda h: tuple(current(h[:u]) for u in range(1, len(h) + 1))
    return cur(h1) == cur(h2) and first_corrected(h1) != first_corrected(h2), cur(h1), first_corrected(h1), first_corrected(h2)

def i3():
    vals = list(itertools.product(range(3), repeat=2))
    maxmerge = all(max(a, b) == max(b, a) for a, b in vals)
    lww_dep = any((b if True else a) != (a if True else b) for a, b in vals)     # order (a then b) -> b ; (b then a) -> a
    return maxmerge, lww_dep

# ---------------- O-10g ----------------
def pc_all(L):
    return X.pc_feasible(L, {h: (0,) for h in L})

def structure(pi, L):
    levels = {}
    for h in L:
        levels.setdefault(len(h), set()).add(pi[h])
    children = {}
    for h in L:
        if h:
            children.setdefault(pi[h[:-1]], set()).add(pi[h])
    return levels, children

def huff_len(k):
    """expected Huffman length for k equiprobable symbols (exact)."""
    if k <= 1:
        return 0.0
    h = [(1.0 / k, i) for i in range(k)]; heapq.heapify(h); total = 0.0; n = k
    while len(h) > 1:
        a, b = heapq.heappop(h), heapq.heappop(h)
        total += a[0] + b[0]; heapq.heappush(h, (a[0] + b[0], n)); n += 1
    return total

def measures(pi, L, n):
    levels, ch = structure(pi, L)
    glob = tuple(math.ceil(math.log2(len(levels[k]))) if len(levels[k]) > 1 else 0 for k in range(n + 1))
    width = max((math.ceil(math.log2(len(c))) if len(c) > 1 else 0) for c in ch.values()) if ch else 0
    cost = lambda node: math.ceil(math.log2(len(ch.get(node, ())))) if len(ch.get(node, ())) > 1 else 0
    per_hist = {h: sum(cost(pi[h[:t]]) for t in range(len(h))) for h in L if len(h) == n}
    # expected per-node Huffman length under uniform inputs: child-class probabilities from uniform histories
    exp = 0.0
    for h in (hh for hh in L if len(hh) == n):
        for t in range(n):
            node = pi[h[:t]]
            kids = [x for x in L if len(x) == t + 1 and pi[x[:-1]] == node]
            mass = {}
            for x in kids:
                mass[pi[x]] = mass.get(pi[x], 0) + 1
            exp += huff_len(len(mass)) if len(set(mass.values())) == 1 else _huff_weighted(list(mass.values()))
    exp /= sum(1 for hh in L if len(hh) == n)
    return glob, width, per_hist, exp

def _huff_weighted(ws):
    tot = sum(ws); h = [(w / tot, i) for i, w in enumerate(ws)]
    if len(h) <= 1:
        return 0.0
    heapq.heapify(h); total = 0.0; n = len(h)
    while len(h) > 1:
        a, b = heapq.heappop(h), heapq.heappop(h)
        total += a[0] + b[0]; heapq.heappush(h, (a[0] + b[0], n)); n += 1
    return total

def mask(pi, L):
    m, k = 0, 0
    for i, j in itertools.combinations(range(len(L)), 2):
        if pi[L[i]] == pi[L[j]]:
            m |= 1 << k
        k += 1
    return m

def g_all():
    n = 3; L = X.all_hist(n); P = pc_all(L)
    M = [mask(p, L) for p in P]
    meas = [measures(p, L, n) for p in P]
    g1_viol = g2_inc = g3_hist_inc = g3_max_inc = g3_exp_inc = pairs = 0
    ex2 = None
    for a in range(len(P)):
        ma = M[a]
        for b in range(len(P)):
            if a == b or ma & ~M[b]:
                continue                                  # need ker(P[a]) ⊆ ker(P[b]): P[b] coarser
            pairs += 1
            ga, wa, ha, ea = meas[a]; gb, wb, hb, eb = meas[b]
            g1_viol += any(y > x for x, y in zip(ga, gb))
            if wb > wa:
                g2_inc += 1
                ex2 = ex2 or (a, b, wa, wb)
            g3_hist_inc += any(hb[h] > ha[h] for h in ha)
            g3_max_inc += max(hb.values()) > max(ha.values())
            g3_exp_inc += eb > ea + 1e-12
    return dict(pc=len(P), pairs=pairs, g1_violations=g1_viol, g2_width_increase=g2_inc,
                g3_some_history_increase=g3_hist_inc, g3_max_increase=g3_max_inc, g3_expected_increase=g3_exp_inc)

def g2_named():
    n = 3; L = X.all_hist(n)
    pi = {h: (len(h), h) for h in L}                               # identity: level1 {0},{1}; each node 2 children
    pi2 = {h: ((1, "merged") if len(h) == 1 else (len(h), h)) for h in L}
    sub = X.subset_rel(pi, pi2, L)
    return sub, measures(pi, L, n)[1], measures(pi2, L, n)[1]

if __name__ == "__main__":
    rnd = random.Random(SEED)
    print("F1 outcome log not verifiable (named):", f1())
    mm, inc, tr = f2_f3(rnd)
    print(f"F2 verifiable <=> projection determines F: mismatches {mm} over {tr} instances x 8 subsets")
    print(f"F3 (exploratory) instances with incomparable minimal evidence sets: {inc}/{tr}; named P3 instance verifiable by subset: {f3_named()}")
    ok, tr = i1(rnd)
    print(f"I1 bitemporal ≡*_bi greatest: {ok}/{tr}")
    print("I2 current-only log loses corrected past decision (holds, cur log, D(h,1) for '1c','10'):", i2())
    print("I3 (max-merge order-independent, last-writer-wins order-dependent):", i3())
    print("G2 named (pi ⊆ pi2, width pi, width pi2):", g2_named())
    print("G1/G2/G3 exhaustive:", g_all())
