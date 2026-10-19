#!/usr/bin/env python3
"""Implements PROTOCOL.md (sha256 a2c5d91f0d8317cd84f7fdc494fef7e730acd360dc288ce51b8154c8773138d4). Synthetic only."""
import itertools, random, platform

SEED, X, O = 20261022, (0, 1), (0, 1)

def set_partitions(items):
    if not items:
        yield []; return
    f, rest = items[0], items[1:]
    for p in set_partitions(rest):
        for i in range(len(p)):
            yield p[:i] + [[f] + p[i]] + p[i + 1:]
        yield [[f]] + p

def families(L1, L2):
    """All PC equivalences on {eps} ∪ L1 ∪ L2 (x-histories), as dicts node -> label."""
    out = []
    for p1 in set_partitions(list(L1)):
        lab1 = {h: i for i, B in enumerate(p1) for h in B}
        for p2 in set_partitions(list(L2)):
            ok = all(lab1[a[:1]] == lab1[b[:1]] for B in p2 for a in B for b in B)
            if ok:
                lab = dict(lab1); lab.update({h: 100 + i for i, B in enumerate(p2) for h in B})
                out.append(lab)
    return out

def det(view_key, val, items):
    m = {}
    return all(m.setdefault(view_key(i), val(i)) == val(i) for i in items)

def analyse(F1, F2, W):
    X2 = sorted({w[0] for w in W}); X1 = sorted({x[:1] for x in X2})
    W1 = sorted({(w[0][:1], w[1][:1]) for w in W})
    d = {1: lambda x: F1[x[0]], 2: lambda x: F2[x]}
    fam = families(X1, X2)
    J = True
    for G in fam:
        ver = (det(lambda w: (G[w[0]], w[1]), lambda w: int(w[1][0] == F1[w[0][0]]), W1) and
               det(lambda w: (G[w[0]], w[1]), lambda w: int(w[1][1] == F2[w[0]]), W))
        dets = det(lambda x: G[x], d[1], X1) and det(lambda x: G[x], d[2], X2)
        if ver != dets:
            J = False; break
    def confusable(s, a, b):
        if d[s](a) == d[s](b):
            return False
        Ws = W1 if s == 1 else W
        oa = {w[1] for w in Ws if w[0] == a}; ob = {w[1] for w in Ws if w[0] == b}
        return any(o[s - 1] in (d[s](a), d[s](b)) for o in oa & ob)
    Xs = {1: X1, 2: X2}
    s_multi = all(confusable(t, a, b) for t in (1, 2) for a, b in itertools.combinations(Xs[t], 2) if d[t](a) != d[t](b))
    def forced(t, a, b):
        return any(confusable(s, a[:s], b[:s]) for s in range(1, t + 1))
    s_joint = all(forced(t, a, b) for t in (1, 2) for a, b in itertools.combinations(Xs[t], 2) if d[t](a) != d[t](b))
    return J, s_joint, s_multi, len(fam)

def f28():
    F1 = {0: 0, 1: 1}; F2 = {x: x[1] for x in itertools.product(X, repeat=2)}
    W = [(x, x) for x in itertools.product(X, repeat=2)] + [(x, tuple(1 - v for v in x)) for x in itertools.product(X, repeat=2)]
    return analyse(F1, F2, W)

if __name__ == "__main__":
    rnd = random.Random(SEED)
    print("env:", platform.python_version(), platform.system(), platform.release())
    J, sj, sm, nf = f28()
    print(f"C-F2-8 control: J={J} S-joint={sj} S-multi={sm} families={nf}")
    worlds = [(x, o) for x in itertools.product(X, repeat=2) for o in itertools.product(O, repeat=2)]
    tot_mis = 0
    for p in (0.3, 0.6, 0.9):
        mis = strict = sj_n = sm_n = 0; ex = None
        for _ in range(2000):
            F1 = {x: rnd.randrange(2) for x in X}
            F2 = {x: rnd.randrange(2) for x in itertools.product(X, repeat=2)}
            present = [x for x in itertools.product(X, repeat=2) if rnd.random() < 0.9] or [(0, 0)]
            W = []
            for x in present:
                ws = [(x, o) for o in itertools.product(O, repeat=2) if rnd.random() < p]
                W += ws or [(x, rnd.choice(list(itertools.product(O, repeat=2))))]
            J, sj, sm, nf = analyse(F1, F2, W)
            mis += J != sj; sj_n += sj; sm_n += sm
            if sj and not sm:
                strict += 1
                if ex is None or len(W) < len(ex[2]):
                    ex = (F1, F2, sorted(W))
        tot_mis += mis
        print(f"p={p}: T1 mismatches (J vs S-joint) {mis}/2000; S-joint {sj_n}; S-multi {sm_n}; strict (S-joint & not S-multi) {strict}")
        if ex:
            print("   smallest strict instance:", ex)
    print("T1 total mismatches:", tot_mis)
