#!/usr/bin/env python3
"""D7 synthetic-only checks (worker implementation A). No corpus input of any kind."""
import itertools, random

# ---------- helpers: trim pointed complete DFAs, BFS-canonical ----------
def trim_canonical(n, k, table):
    """table[s][a] -> state; start 0. Return True if all states reachable and numbering is BFS-canonical."""
    order, seen = [0], {0}
    i = 0
    while i < len(order):
        s = order[i]; i += 1
        for a in range(k):
            t = table[s][a]
            if t not in seen:
                seen.add(t); order.append(t)
    return len(order) == n and order == list(range(n))

def automata(n, k):
    cells = n * k
    for flat in itertools.product(range(n), repeat=cells):
        table = [flat[s * k:(s + 1) * k] for s in range(n)]
        if trim_canonical(n, k, table):
            yield table

def reach_from(table, s):
    seen, st = {s}, [s]
    while st:
        x = st.pop()
        for t in table[x]:
            if t not in seen:
                seen.add(t); st.append(t)
    return seen

# ---------- E2: cap policy over Σ={a,b,c}, constant decision ----------
def channels(table, k):
    ch = {s: set() for s in range(len(table))}
    for x in range(k):
        for s in reach_from(table, table[0][x]):
            ch[s].add(x)
    return ch

def e2():
    k = 3
    res = {}
    for n in (1, 2, 3):
        adm = [t for t in automata(n, k) if all(len(c) <= 2 for c in channels(t, k).values())]
        res[n] = adm
    return res

# ---------- E4 / E5: encodings with one-bit decoders ----------
def one_bit_functions(nbits):
    pts = list(itertools.product((0, 1), repeat=nbits))
    fs = [dict((p, c) for p in pts) for c in (0, 1)]
    for i in range(nbits):
        for neg in (0, 1):
            fs.append(dict((p, p[i] ^ neg) for p in pts))
    return pts, fs

def e4():
    pts, fs = one_bit_functions(2)
    d = {0: 0, 1: 1, 2: 1, 3: 0}
    encs = list(itertools.permutations(pts))           # e[class] = codeword
    adm = [e for e in encs if any(all(f[e[c]] == d[c] for c in range(4)) for f in fs)]
    # hyperoctahedral group on 2 bits
    G = []
    for perm in itertools.permutations(range(2)):
        for flips in itertools.product((0, 1), repeat=2):
            G.append(lambda p, perm=perm, flips=flips: tuple(p[perm[i]] ^ flips[i] for i in range(2)))
    orbit = lambda e: frozenset(tuple(g(x) for x in e) for g in G)
    return len(encs), len({orbit(e) for e in encs}), len({orbit(e) for e in adm})

def e5():
    out = {}
    for nbits in (2, 3):
        pts, fs = one_bit_functions(nbits)
        ok = 0
        for e in itertools.permutations(pts, 3):
            if all(any(all(f[e[c]] == int(c == X) for c in range(3)) for f in fs) for X in range(3)):
                ok += 1
        out[nbits] = ok
    return out

# ---------- R4(b): greatest right congruence inside ker(d) ∩ ker(d_rho) == Moore minimization ----------
def partitions(seq):
    if not seq:
        yield []; return
    first, rest = seq[0], seq[1:]
    for p in partitions(rest):
        for i in range(len(p)):
            yield p[:i] + [[first] + p[i]] + p[i + 1:]
        yield [[first]] + p

def moore_min_count(table, out):
    blk = {s: out[s] for s in range(len(table))}
    while True:
        sig = {s: (blk[s],) + tuple(blk[t] for t in table[s]) for s in blk}
        ids = {}; nb = {s: ids.setdefault(sig[s], len(ids)) for s in sig}
        if len(set(nb.values())) == len(set(blk.values())):
            return len(set(nb.values()))
        blk = nb

def brute_coarsest(table, out):
    n = len(table); best = None
    for p in partitions(list(range(n))):
        b = {s: i for i, B in enumerate(p) for s in B}
        if any(out[s] != out[B[0]] for B in p for s in B):
            continue
        if all(b[table[s][a]] == b[table[B[0]][a]] for B in p for s in B for a in range(len(table[0]))):
            if best is None or len(p) < best:
                best = len(p)
    return best

def r4b(trials=400, seed=20261017):
    rnd = random.Random(seed); mism = 0
    for _ in range(trials):
        k = 2
        n1, n2 = rnd.randint(1, 3), rnd.randint(1, 3)
        T1 = [[rnd.randrange(n1) for _ in range(k)] for _ in range(n1)]
        T2 = [[rnd.randrange(n2) for _ in range(k)] for _ in range(n2)]
        o1 = [rnd.randrange(2) for _ in range(n1)]; o2 = [rnd.randrange(3) for _ in range(n2)]
        # reachable product automaton with outputs (d, d_rho)
        idx, order, st = {(0, 0): 0}, [(0, 0)], [(0, 0)]
        while st:
            p = st.pop()
            for a in range(k):
                q = (T1[p[0]][a], T2[p[1]][a])
                if q not in idx:
                    idx[q] = len(order); order.append(q); st.append(q)
        table = [[idx[(T1[p[0]][a], T2[p[1]][a])] for a in range(k)] for p in order]
        out = [(o1[p[0]], o2[p[1]]) for p in order]
        if moore_min_count(table, out) != brute_coarsest(table, out):
            mism += 1
    return trials, mism

if __name__ == "__main__":
    r = e2()
    for n, adm in r.items():
        print(f"E2: admissible trim automata with {n} states: {len(adm)}")
    print("E2: admissible 3-state tables:")
    for t in r[3]:
        print("   ", t, "channels", {s: sorted(c) for s, c in channels(t, 3).items()})
    print("E4: encodings, orbits, admissible orbits =", e4())
    print("E5: admissible one-hot-decision encodings by bit count =", e5())
    print("R4(b): trials, mismatches (Moore vs brute-force coarsest congruence) =", r4b())
