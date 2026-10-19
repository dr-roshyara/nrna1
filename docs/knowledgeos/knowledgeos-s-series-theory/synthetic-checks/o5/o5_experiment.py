#!/usr/bin/env python3
"""O-5 synthetic experiment (D7: synthetic only). PROTOCOL DECLARED BEFORE RUNNING — do not change after seeing results.

Population: random minimal Moore machines M over Sigma={0,1}, n_min in {3,4} states, binary output d, start 0,
            drawn with seed 20261017; machines that are not minimal+trim are rejected and redrawn.
Loss (declared): L_mu(s,f) = (1/(T+1)) * sum_{t=0..T} P_mu[ f(s(H_t)) != d(H_t) ], T = 6,
            H_t = first t inputs, inputs i.i.d. with P(0)=p. Primary mu: p=0.5. Shift mu': p=0.8.
Candidates: ALL recursive realizations with N states (start 0, any transition table, any decoder N->{0,1}); N in {2,3}.
Questions (declared):
 Q1 non-uniqueness: among mu-optimal N=2 realizations, are there >=2 distinct induced partitions of histories (len<=T)?
 Q2 quotient-type: is every mu-optimal N=2 realization a function of the minimal machine state on all histories len<=T?
 Q3 shift: are the mu-optimal and mu'-optimal sets of induced partitions (N=2) disjoint?
 Q4 nesting: does some mu-optimal N=3 partition refine some mu-optimal N=2 partition?
Ties: losses equal within 1e-12.
Limits: bounded horizon, tiny machines, one distribution family; results describe this synthetic population only.
"""
import itertools, random

SEED, T, K = 20261017, 6, 2
EPS = 1e-12

def run(table, s0, h):
    s = s0
    for a in h:
        s = table[s][a]
    return s

def is_minimal_trim(tab, out):
    n = len(tab)
    seen, st = {0}, [0]
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

def draw(rnd):
    while True:
        n = rnd.choice((3, 4))
        tab = [tuple(rnd.randrange(n) for _ in range(K)) for _ in range(n)]
        out = [rnd.randrange(2) for _ in range(n)]
        if is_minimal_trim(tab, out):
            return tab, out

def loss(M, C, f, p):
    (mt, mo), ct = M, C
    dist = {(0, 0): 1.0}; tot = 0.0
    pa = (p, 1 - p)
    for t in range(T + 1):
        tot += sum(w for (m, c), w in dist.items() if f[c] != mo[m])
        if t == T:
            break
        nd = {}
        for (m, c), w in dist.items():
            for a in range(K):
                key = (mt[m][a], ct[c][a]); nd[key] = nd.get(key, 0.0) + w * pa[a]
        dist = nd
    return tot / (T + 1)

HIST = [h for L in range(T + 1) for h in itertools.product(range(K), repeat=L)]

def partition(C):
    lab = {}; ids = {}
    for h in HIST:
        s = run(C, 0, h)
        lab[h] = ids.setdefault(s, len(ids))
    return tuple(lab[h] for h in HIST)

def refines(p_fine, p_coarse):
    m = {}
    for a, b in zip(p_fine, p_coarse):
        if m.setdefault(a, b) != b:
            return False
    return True

def candidates(N):
    for flat in itertools.product(range(N), repeat=N * K):
        C = [tuple(flat[s * K:(s + 1) * K]) for s in range(N)]
        for f in itertools.product((0, 1), repeat=N):
            yield C, f

def optimum(M, N, p):
    best, arg = None, []
    for C, f in candidates(N):
        L = loss(M, C, f, p)
        if best is None or L < best - EPS:
            best, arg = L, [(C, f)]
        elif abs(L - best) <= EPS:
            arg.append((C, f))
    return best, arg

def quotient_type(M, C):
    mt, _ = M; g = {}
    for h in HIST:
        if g.setdefault(run(mt, 0, h), run(C, 0, h)) != run(C, 0, h):
            return False
    return True

if __name__ == "__main__":
    rnd = random.Random(SEED)
    machines = [draw(rnd) for _ in range(40)]
    q1 = q2_all = q3 = q4 = 0; n4 = 0; exact2 = 0
    for i, M in enumerate(machines):
        L2, A2 = optimum(M, 2, 0.5)
        parts2 = {partition(C) for C, _ in A2}
        if L2 <= EPS:
            exact2 += 1
        q1 += len(parts2) >= 2
        q2_all += all(quotient_type(M, C) for C, _ in A2)
        _, A2s = optimum(M, 2, 0.8)
        q3 += parts2.isdisjoint({partition(C) for C, _ in A2s})
        if i < 15:
            n4 += 1
            _, A3 = optimum(M, 3, 0.5)
            parts3 = {partition(C) for C, _ in A3}
            q4 += any(refines(a, b) for a in parts3 for b in parts2)
    n = len(machines)
    print(f"machines={n} (n_min in 3,4), T={T}; exact at N=2: {exact2}")
    print(f"Q1 non-unique optimal partition (N=2): {q1}/{n}")
    print(f"Q2 all optima quotient-type (N=2):     {q2_all}/{n}")
    print(f"Q3 mu vs mu' optimal sets disjoint:    {q3}/{n}")
    print(f"Q4 some N=3 optimum refines an N=2 optimum: {q4}/{n4}")
