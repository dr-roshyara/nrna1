#!/usr/bin/env python3
"""O5-12: implements PROTOCOL.md (sha256 60c0b9566a91f35bc432e95873f91914eb9a1ed548c0ed33119706cc6e26564e) exactly.
Synthetic only (D7)."""
import itertools, random

SEED, K, NM, SAMPLE, EPS = 20261017, 2, 4, 60, 1e-12

def minimal_trim(tab, out):
    n = len(tab); seen, st = {0}, [0]
    while st:
        x = st.pop()
        for y in tab[x]:
            if y not in seen:
                seen.add(y); st.append(y)
    if len(seen) != n:
        return False
    blk = list(out)
    while True:
        sig = [(blk[s],) + tuple(blk[t] for t in tab[s]) for s in range(n)]
        ids = {}; nb = [ids.setdefault(x, len(ids)) for x in sig]
        if len(set(nb)) == len(set(blk)):
            return len(set(nb)) == n
        blk = nb

def partitions(seq):
    if not seq:
        yield []; return
    f, rest = seq[0], seq[1:]
    for p in partitions(rest):
        for i in range(len(p)):
            yield p[:i] + [[f] + p[i]] + p[i + 1:]
        yield [[f]] + p

def congruences(tab):
    n = len(tab); out = []
    for p in partitions(list(range(n))):
        b = {s: i for i, B in enumerate(p) for s in B}
        if all(len({b[tab[s][a]] for s in B}) == 1 for B in p for a in range(K)):
            out.append(b)
    return out

def loss_generic(M, step, start, dec, T):
    """Exact expected 0/1 loss: joint distribution over (M state, candidate state)."""
    mt, mo = M; dist = {(0, start): 1.0}; tot = 0.0
    for t in range(T + 1):
        tot += sum(w for (m, c), w in dist.items() if dec(c) != mo[m])
        if t == T:
            break
        nd = {}
        for (m, c), w in dist.items():
            for a in range(K):
                k = (mt[m][a], step(c, a)); nd[k] = nd.get(k, 0.0) + w / K
        dist = nd
    return tot / (T + 1)

def best_G(M, N, T):
    best = None
    for flat in itertools.product(range(N), repeat=N * K):
        C = [flat[s * K:(s + 1) * K] for s in range(N)]
        for f in itertools.product((0, 1), repeat=N):
            L = loss_generic(M, lambda c, a: C[c][a], 0, lambda c: f[c], T)
            best = L if best is None or L < best else best
    return best

def best_Q(M, N, T, congs):
    mt, _ = M; best = None
    for b in congs:
        nb = len(set(b.values()))
        if nb > N:
            continue
        for f in itertools.product((0, 1), repeat=nb):
            L = loss_generic(M, lambda c, a: mt[c][a], 0, lambda c: f[b[c]], T)
            best = L if best is None or L < best else best
    return best

def best_G_optima_in_Q(M, N, T, LG, LQ):
    return LQ <= LG + EPS          # some G-optimum lies in Q iff the best quotient attains the global optimum

def draw(rnd):
    while True:
        tab = [tuple(rnd.randrange(NM) for _ in range(K)) for _ in range(NM)]
        out = [rnd.randrange(2) for _ in range(NM)]
        if not minimal_trim(tab, out):
            continue
        congs = congruences(tab)
        sizes = sorted({len(set(b.values())) for b in congs if 2 <= len(set(b.values())) <= 3})
        if sizes:
            return (tab, out), congs, sizes[0]

if __name__ == "__main__":
    rnd = random.Random(SEED)
    rows = [draw(rnd) for _ in range(SAMPLE)]
    res = {}
    for T in (6, 10):
        q1 = q2 = 0; gaps = []; ex = None
        for M, congs, m in rows:
            LG, LQ = best_G(M, m, T), best_Q(M, m, T, congs)
            gaps.append(LQ - LG)
            if LG < LQ - EPS:
                q1 += 1
                if ex is None:
                    ex = (M, m, LG, LQ)
            q2 += best_G_optima_in_Q(M, m, T, LG, LQ)
        res[T] = (q1, q2, sum(gaps) / len(gaps), max(gaps), ex)
    budgets = [m for _, _, m in rows]
    print(f"population: {SAMPLE} minimal trim 4-state machines with a non-trivial congruence; "
          f"budget N=m*: N=2 x{budgets.count(2)}, N=3 x{budgets.count(3)}")
    for T, (q1, q2, mg, xg, ex) in res.items():
        tag = "Q1/Q2/Q3" if T == 6 else "Q4 (T=10)"
        print(f"T={T} [{tag}]: G strictly beats every coarsening: {q1}/{SAMPLE}; some optimum is a coarsening: {q2}/{SAMPLE}; "
              f"gap mean {mg:.6f}, max {xg:.6f}")
        if ex:
            (tab, out), m, LG, LQ = ex
            print(f"   first example: table={tab} outputs={out} N={m}  best G={LG:.6f}  best coarsening={LQ:.6f}")
