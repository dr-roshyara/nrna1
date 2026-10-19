"""workerC finite checks K1..K6 per PROTOCOL.md (sha256 70cbe131...9c98a)."""
import itertools, random, math, sys, platform

def clog2(x):
    assert x >= 1
    return (x - 1).bit_length()

def all_hist(s, n):
    out = []
    for L in range(n + 1):
        out.extend(itertools.product(range(s), repeat=L))
    return out

def same_kernel(r1, r2, H):
    # equal iff r1(h)=r1(h') <=> r2(h)=r2(h'), within equal lengths
    return subset(r1, r2, H) and subset(r2, r1, H)

def subset(r1, r2, H):
    # ker r1 subset ker r2
    m = {}
    for h in H:
        key = (len(h), r1[h])
        if key in m:
            if m[key] != r2[h]:
                return False
        else:
            m[key] = r2[h]
    return True

def rand_D(s, n, m, k, rng):
    H = all_hist(s, n)
    D = {}
    for g in H:  # increasing length order
        for t in range(len(g) + 1):
            if k is None or len(g) - t <= k:
                D[(g, t)] = rng.randrange(m)
            else:
                D[(g, t)] = D[(g[:t + k], t)]
    return H, D

def policy(D, H, k):
    return {(g, t): D[(g[:min(len(g), t + k)], t)] for g in H for t in range(len(g) + 1)}

def vec(D, g):
    return tuple(D[(g, t)] for t in range(len(g) + 1))

def full_rec(D, H):
    return {h: tuple(vec(D, h[:u]) for u in range(len(h) + 1)) for h in H}

def win_rec(D, H, k, size=None):
    size = k + 1 if size is None else size
    def W(g):
        u = len(g)
        lo = max(0, u - size + 1)
        return tuple(D[(g, t)] for t in range(lo, u + 1))
    return {h: tuple(W(h[:u]) for u in range(len(h) + 1)) for h in H}

def children(rec, H, s, n):
    ch = {}
    for h in H:
        if len(h) < n:
            ch.setdefault(rec[h], set()).update(rec[h + (a,)] for a in range(s))
    return ch

def node_cost(rec, ch, h):
    return sum(clog2(len(ch[rec[h[:t]]])) for t in range(len(h)))

def gw(ch):
    return clog2(max(len(v) for v in ch.values()))

def Ncount(rec, H, t):
    return len({rec[h] for h in H if len(h) == t})

out = []
def say(*a):
    line = " ".join(str(x) for x in a)
    out.append(line); print(line)

say("python", sys.version.replace("\n", " "), platform.platform())

# ---------------- K1
rng = random.Random(20261018 + 1)
s, m, n = 2, 2, 4
for k in (0, 1, 2):
    mism = 0; power = 0
    for _ in range(300):
        H, D = rand_D(s, n, m, k, rng)
        F = full_rec(D, H); Wr = win_rec(D, H, k)
        if not same_kernel(F, Wr, H):
            mism += 1
        if k >= 1:
            Wt = win_rec(D, H, k, size=k)
            if not same_kernel(F, Wt, H):
                power += 1
    say(f"K1 k={k}: window==full mismatches {mism}/300; truncated-window power mismatches {power}/300" + (" (n/a k=0)" if k == 0 else ""))

# ---------------- K2 + K3
rng = random.Random(20261018 + 2)
viol = 0; eqfail = 0
k3 = {"gw_up": 0, "maxnode_up": 0, "somehist_up": 0, "Nn_up": 0, "pairs": 0}
for _ in range(300):
    H, D = rand_D(s, n, m, None, rng)
    recs = {}
    for k in range(0, 5):
        recs[k] = full_rec(policy(D, H, k), H)
    for k in range(5):
        for k2 in range(k + 1, 5):
            if not subset(recs[k2], recs[k], H):
                viol += 1
    if not same_kernel(recs[4], full_rec(D, H), H):
        eqfail += 1
    Hn = [h for h in H if len(h) == n]
    for k in range(4):
        fine, coarse = recs[k + 1], recs[k]
        chf, chc = children(fine, H, s, n), children(coarse, H, s, n)
        k3["pairs"] += 1
        if gw(chc) > gw(chf): k3["gw_up"] += 1
        if max(node_cost(coarse, chc, h) for h in Hn) > max(node_cost(fine, chf, h) for h in Hn): k3["maxnode_up"] += 1
        if any(node_cost(coarse, chc, h) > node_cost(fine, chf, h) for h in Hn): k3["somehist_up"] += 1
        if Ncount(coarse, H, n) > Ncount(fine, H, n): k3["Nn_up"] += 1
say(f"K2: refinement violations {viol} (of 300*10 pairs); D^4 != D kernel: {eqfail}/300")
say(f"K3 (exploratory): {k3}")

# ---------------- K4
def K_member(s, h):
    if s == 2:
        return all(not (h[i] == 1 and h[i + 1] == 1) for i in range(len(h) - 1))
    return all(x != 1 for x in h)

k4fail = 0
for s4, nmax in ((2, 9), (3, 6)):
    for n4 in range(1, nmax + 1):
        H = all_hist(s4, n4)
        lab = lambda g: ("K", len(g)) if K_member(s4, g) else g
        rec = {h: tuple(lab(h[:t]) for t in range(len(h) + 1)) for h in H}
        ch = children(rec, H, s4, n4)
        brute = node_cost(rec, ch, (0,) * n4)
        Kc = [sum(1 for h in H if len(h) == t and K_member(s4, h)) for t in range(n4 + 1)]
        formula = sum(clog2(s4 * Kc[t] - Kc[t + 1] + 1) for t in range(n4))
        idrec = {h: h for h in H}
        idcost = node_cost(idrec, children(idrec, H, s4, n4), (0,) * n4)
        # GW example
        grec = {h: (("T", len(h)) if len(h) < n4 else h) for h in H}
        grec = {h: tuple(grec[h[:t]] for t in range(len(h) + 1)) for h in H}
        gwv = gw(children(grec, H, s4, n4)); gwid = gw(children(idrec, H, s4, n4))
        ok = (brute == formula and idcost == n4 * clog2(s4) and gwv == math.ceil(n4 * math.log2(s4) - 1e-12) and gwid == clog2(s4))
        if not ok: k4fail += 1
        say(f"K4 s={s4} n={n4}: spine NODE pi'_K brute={brute} formula={formula} identity={idcost} | GW total-then-identity={gwv} identity GW={gwid} ok={ok}")
say(f"K4 failures: {k4fail}")

# ---------------- K5
rng = random.Random(20261018 + 5)
v = [0] * 6
for s5, n5 in ((2, 4), (3, 3)):
    H = all_hist(s5, n5)
    Hn = [h for h in H if len(h) == n5]
    for _ in range(300):
        r1 = rng.randint(1, 3); r2 = rng.randint(1, 3)
        rho1 = {(h, a): rng.randrange(r1) for h in H if len(h) < n5 for a in range(s5)}
        rho2 = {(h, a): rng.randrange(r2) for h in H if len(h) < n5 for a in range(s5)}
        recC = {h: tuple(rho1[(h[:i], h[i])] for i in range(len(h))) for h in H}
        recF = {h: tuple((rho1[(h[:i], h[i])], rho2[(h[:i], h[i])]) for i in range(len(h))) for h in H}
        if not subset(recF, recC, H): v[0] += 1
        chC = children(recC, H, s5, n5); chF = children(recF, H, s5, n5)
        T = sum(Ncount(recC, H, t) for t in range(1, n5 + 1)); Nn = Ncount(recC, H, n5)
        ub = sum(math.ceil((t + 1) * math.log2(s5) - 1e-12) for t in range(n5))
        for h in Hn:
            cs = [len(chC[recC[h[:t]]]) for t in range(n5)]
            if node_cost(recC, chC, h) > ub: v[1] += 1
            if sum(cs) > T: v[2] += 1
            if sum(c - 1 for c in cs) + 1 > Nn: v[3] += 1
        if gw(chC) > clog2(max(Ncount(recF, H, t + 1) for t in range(n5))): v[4] += 1
        # (vi)
        groups = {}
        for h in H:
            if len(h) < n5:
                groups.setdefault(recC[h], set()).add(recF[h])
        for Cc, fines in groups.items():
            if len(chC[Cc]) > sum(len(chF[f]) for f in fines): v[5] += 1
say(f"K5 violations (i..vi): {v}")

# ---------------- K6
k6 = {"a_fail": 0, "b_fail": 0, "b_equal_positions": [], "c_out_of_bounds": 0, "c_VL_gt_FL": 0, "c_VL_lt_FL": 0, "c_VL_eq_FL": 0, "d_fail": 0}
def vl_list(D, g):
    u = len(g)
    C = [t for t in range(u) if D[(g, t)] != D[(g[:-1], t)]] if u >= 1 else []
    b = 1
    return clog2(u + 1) + len(C) * (clog2(u) if u >= 1 else 0) + len(C) * b + b, len(C)
for n6 in range(1, 9):
    H = [tuple([0] * L) for L in range(n6 + 1)]
    D = {(g, t): (len(g) + t) % 2 for g in H for t in range(len(g) + 1)}
    for u in range(1, n6 + 1):
        size, c = vl_list(D, (0,) * u)
        exp = clog2(u + 1) + u * (clog2(u) + 1) + 1
        if not (c == u and size == exp and size > u + 1): k6["a_fail"] += 1
for m6 in (2, 3):
    for u in range(5):
        cnt = 0
        for r in range(u + 1):
            for C in itertools.combinations(range(u), r):
                cnt += m6 ** len(C) * m6
        if cnt != m6 * (m6 + 1) ** u: k6["b_fail"] += 1
        wd, wf = clog2(cnt), clog2(m6 ** (u + 1))
        if wd < wf: k6["b_fail"] += 1
        if wd == wf: k6["b_equal_positions"].append((m6, u))
rng = random.Random(20261018 + 6)
for _ in range(300):
    H, D = rand_D(2, 5, 2, None, rng)
    F = full_rec(D, H)
    for g in H:
        u = len(g)
        if u == 0: continue
        size, c = vl_list(D, g)
        lo = clog2(u + 1) + 1; hi = clog2(u + 1) + u * (clog2(u) + 1) + 1
        if not (lo <= size <= hi): k6["c_out_of_bounds"] += 1
        fl = u + 1
        if size > fl: k6["c_VL_gt_FL"] += 1
        elif size < fl: k6["c_VL_lt_FL"] += 1
        else: k6["c_VL_eq_FL"] += 1
    # (d)
    nxt_full, nxt_diff = {}, {}
    for g in H:
        if len(g) == 0: continue
        p = g[:-1]
        de = tuple((t, D[(g, t)]) for t in range(len(g)) if D[(g, t)] != D[(p, t)]) + (D[(g, len(g))],)
        nxt_full.setdefault(F[p], set()).add(vec(D, g))
        nxt_diff.setdefault(F[p], set()).add(de)
    for key in nxt_full:
        if len(nxt_full[key]) != len(nxt_diff[key]): k6["d_fail"] += 1
say(f"K6: {k6}")

with open("RESULTS.txt", "w") as f:
    f.write("\n".join(out) + "\n")
