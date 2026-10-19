"""Worker A finite checks for O-10f1..f4. See PROTOCOL.md (v1)."""
import itertools, random

def determines(worlds, view, phi):
    seen = {}
    for w in worlds:
        k = view(w); v = phi(w)
        if k in seen and seen[k] != v:
            return False
        seen.setdefault(k, v)
    return True

def partitions(seq):
    seq = list(seq)
    if not seq:
        yield []
        return
    first, rest = seq[0], seq[1:]
    for p in partitions(rest):
        for i in range(len(p)):
            yield p[:i] + [[first] + p[i]] + p[i+1:]
        yield [[first]] + p

def part_map(p):
    m = {}
    for i, blk in enumerate(p):
        for z in blk:
            m[z] = i
    return m

# ---------------- K1, K2 ----------------
X = [0, 1, 2]; O = [0, 1, 2]
pairs = [(x, o) for x in X for o in O]
parts = [part_map(p) for p in partitions(X)]
k1_mis = 0; k2_mis = 0; k2_back_fail = 0; n = 0; n_nontriv_cond_false = 0
for Ft in itertools.product(O, repeat=3):
    F = dict(zip(X, Ft))
    for mask in range(1 << 9):
        W = [pairs[i] for i in range(9) if mask >> i & 1]
        WS = set(W)
        XW = sorted({x for x, _ in W})
        def confusable(x, y):
            return F[x] != F[y] and any((x, o) in WS and (y, o) in WS for o in (F[x], F[y]))
        all_conf = all(confusable(x, y) for x in XW for y in XW if x < y and F[x] != F[y])
        equiv_all_e = True
        for e in parts:
            n += 1
            ver = determines(W, lambda w: (e[w[0]], w[1]), lambda w: w[1] == F[w[0]])
            char = not any(e[x] == e[y] and confusable(x, y) for x in XW for y in XW if x < y)
            if ver != char:
                k1_mis += 1
            det = determines(XW, lambda x: e[x], lambda x: F[x])
            if det and not ver:
                k2_back_fail += 1
            if ver != det:
                equiv_all_e = False
        # but partitions of X, not of XW: identification of x outside XW irrelevant. fine.
        if equiv_all_e != all_conf:
            k2_mis += 1
        if not all_conf:
            n_nontriv_cond_false += 1
print(f"K1: evaluated {n} (F,W,e) triples; mismatches = {k1_mis}  (expected 0)")
print(f"K2: (F,W) pairs = {27*512}; mismatches = {k2_mis} (expected 0); "
      f"(F,W) where condition fails = {n_nontriv_cond_false}; (<=) failures = {k2_back_fail} (expected 0)")

# ---------------- K3 ----------------
F = {0: 0, 1: 1}
W = [(x, F[x] + k) for x in (0, 1) for k in (0, 2)]
ver = determines(W, lambda w: (0, w[1]), lambda w: w[1] == F[w[0]])
det = determines([0, 1], lambda x: 0, lambda x: F[x])
print(f"K3a: F=id, o in {{F,F+2}}, empty evidence: Ver={ver} (exp True), determines F={det} (exp False)")
F = {0: 0, 1: 2}
W = [(x, F[x] + k) for x in (0, 1) for k in (0, 2)]
ver = determines(W, lambda w: (0, w[1]), lambda w: w[1] == F[w[0]])
print(f"K3b: F=2x, o in {{F,F+2}}, empty evidence: Ver={ver} (exp False)")

# ---------------- K4 ----------------
rng = random.Random(1017)
H = [(a, b) for a in (0, 1) for b in (0, 1)]            # x-histories
worlds_all = [(xh, oh) for xh in H for oh in H]          # (x-history, o-history)
hparts = [part_map(p) for p in partitions(H)]
m1 = m2 = 0; s_inst = 0
for _ in range(20000):
    W = [w for w in worlds_all if rng.random() < 0.5]
    if not W:
        continue
    F2 = {h: rng.randint(0, 1) for h in H}
    G = rng.choice(hparts)
    ver = determines(W, lambda w: (G[w[0]], w[1]), lambda w: w[1][1] == F2[w[0]])
    XW = sorted({w[0] for w in W})
    adm = {}
    for xh, oh in W:
        adm.setdefault(xh, set()).add(oh)
    def conf(a, b):
        if F2[a] == F2[b]:
            return False
        return any(oh[1] in (F2[a], F2[b]) for oh in adm[a] & adm[b])
    char = not any(G[a] == G[b] and conf(a, b) for a in XW for b in XW if a < b)
    if ver != char:
        m1 += 1
    smulti = all(conf(a, b) for a in XW for b in XW if a < b and F2[a] != F2[b])
    if smulti:
        s_inst += 1
        det = determines(XW, lambda h: G[h], lambda h: F2[h])
        if det != ver:
            m2 += 1
print(f"K4: characterization mismatches = {m1} (exp 0); S-multi instances = {s_inst}, equivalence mismatches = {m2} (exp 0)")
# named instance: all honest or all inverted
W = []
for xh in H:
    W.append((xh, xh))
    W.append((xh, (1 - xh[0], 1 - xh[1])))
F2 = {h: h[1] for h in H}
G = {h: h[0] for h in H}
ver = determines(W, lambda w: (G[w[0]], w[1]), lambda w: w[1][1] == F2[w[0]])
det = determines(H, lambda h: G[h], lambda h: F2[h])
adm = {}
for xh, oh in W:
    adm.setdefault(xh, set()).add(oh)
conf = lambda a, b: F2[a] != F2[b] and any(oh[1] in (F2[a], F2[b]) for oh in adm[a] & adm[b])
smulti = all(conf(a, b) for a in H for b in H if a < b and F2[a] != F2[b])
marg = sorted({(w[0][1], w[1][1]) for w in W})
print(f"K4 named: Ver_2={ver} (exp True); G determines d_2={det} (exp False); S-multi={smulti} (exp False); "
      f"marginal (x2,o2) set={marg} (exp all 4)")

# ---------------- K5 ----------------
# (a) F1=x1, F2=o1
Wd = [((x1, x2), (o1, o2)) for x1 in (0, 1) for x2 in (0, 1) for o1 in (0, 1) for o2 in (0, 1)]
loc = determines(Wd, lambda w: w[1], lambda w: w[1][1] == w[1][0])
glob = determines(Wd, lambda w: w[1], lambda w: w[1][1] == w[0][0])
print(f"K5a: local Ver={loc} (exp True), global Ver={glob} (exp False)")
# (b) x1=0, x2=(a,b); F2 = a if o1==0 else b; evidence e1=x1, e2=a
Wb = [((0, (a, b)), (o1, o2)) for a in (0, 1) for b in (0, 1) for o1 in (0, 1) for o2 in (0, 1)]
def F2b(w):
    (x1, (a, b)), (o1, o2) = w
    return a if o1 == 0 else b
dstar1 = lambda w: w[0][0]
def dstar2(w):
    (x1, (a, b)), _ = w
    return a if dstar1(w) == 0 else b
view = lambda w: (w[0][0], w[0][1][0], w[1])
glob = determines(Wb, view, lambda w: w[1][1] == dstar2(w))
loc = determines(Wb, view, lambda w: w[1][1] == F2b(w))
print(f"K5b: global Ver={glob} (exp True), local Ver={loc} (exp False)")
# (c) view (o,c)
Wc = [(x, o) for x in (0, 1, 2) for o in (0, 1, 2)]
ver = determines(Wc, lambda w: (w[1], w[1] == w[0]), lambda w: w[1] == w[0])
detd = determines(Wc, lambda w: (w[1], w[1] == w[0]), lambda w: w[0])
print(f"K5c: (o,c) Ver={ver} (exp True), (o,c) determines d={detd} (exp False)")

# ---------------- K6 ----------------
Wa = [(x, a, o) for x in (0, 1) for a in (0, 1) for o in (0, 1)]
aud = determines([w for w in Wa if w[1] == w[0]], lambda w: (w[1], w[2]), lambda w: w[2] == w[0])
par = determines(Wa, lambda w: (w[1], w[2]), lambda w: w[2] == w[0])
print(f"K6a: auditor Ver={aud} (exp True), party contest={par} (exp False)")
Wr = [(xp, xq, o) for xp in (0, 1, 2) for xq in (0, 1, 2) for o in (0, 1)]
c = lambda w: w[2] == int(w[0] > w[1])
aud = determines(Wr, lambda w: w, c)
par = determines(Wr, lambda w: (w[0], w[2]), c)
print(f"K6b: auditor Ver={aud} (exp True), party contest={par} (exp False)")
Wp = [(x, o) for x in (0, 1) for o in (0, 1)]
par = determines(Wp, lambda w: (w[0], w[1]), lambda w: w[1] == w[0])   # party knows own x privately
aud = determines(Wp, lambda w: w[1], lambda w: w[1] == w[0])
print(f"K6c: party (private x) contest={par} (exp True), auditor Ver={aud} (exp False)")

# ---------------- K7 ----------------
Wk = [(0, 1, 0), (1, 1, 0)]       # (x_recorded_at_t, x_current, D(h_t,t)); F=id
ch = lambda w: w[2] == w[0]
cur = determines(Wk, lambda w: (w[1], w[2]), ch)
bi = determines(Wk, lambda w: w, ch)
print(f"K7a: current-evidence-only Ver={cur} (exp False), bitemporal Ver={bi} (exp True)")
# versions: v1 = id, v2 = not. world (version_at_t, version_current, x, D)
Fv = {1: lambda x: x, 2: lambda x: 1 - x}
Wv = [(1, 2, 0, 0), (2, 2, 0, 0)]
ch = lambda w: w[3] == Fv[w[0]](w[2])
cur = determines(Wv, lambda w: (w[1], w[2], w[3]), ch)
bi = determines(Wv, lambda w: w, ch)
print(f"K7b: current-version-only Ver={cur} (exp False), bitemporal-version Ver={bi} (exp True)")
