"""Gate 2 reference implementation of SPEC-G2.md (Claude; SECONDARY_REVIEW). Deterministic; standard library.

Exactness: every §3 property is a reachability / universal-step statement over a finite state space; the step
relation is the MAXIMAL relation permitted by the axiom set (every universal property is monotone in the relation,
existential ones are witnessed by the maximal relation), exactly as in the SPEC.md reference (R-2 reproduced).
"""
import itertools
import json
import sys
from collections import deque

P = ("gen", "der"); S = ("auth", "prov", "hist"); E = ("n0", "n1", "n2"); RANK = {"n0": 0, "n1": 1, "n2": 2}
KINDS = ("GOV", "EVID", "EVIDREF", "WORK", "COMP"); EV = {"EVID", "EVIDREF"}
BASE = ("A0", "A1", "A2e", "A3g", "A3s", "A3m", "A4", "A5e", "A5g", "A5s", "A6")
ALL_BARS = [frozenset(c) for r in range(4) for c in itertools.combinations(E, r)]
UPSETS = [frozenset(), frozenset({"n2"}), frozenset({"n1", "n2"}), frozenset(E)]
U0 = frozenset({"n2"})

MODELS = {
    "M0": {"ext": [], "init": (), "extra": ()},
    "M1": {"ext": [("x", (0, 1)), ("v", (0, 1))], "init": (0, 0), "extra": ("X1", "X2", "X3", "X4")},
    "M2": {"ext": [("m", ("NONE", "RULE", "EXC")), ("a", (0, 1))], "init": ("NONE", 0), "extra": ("Y1", "Y2", "Y2b", "Y3", "Y4")},
    "M3": {"ext": [("a", (0, 1))], "init": (0,), "extra": ("Z1", "Z2")},
}


def ps(st): return st[2] in st[4] and st[3] == 1


def notion(mdl, st):
    ext = st[5:]
    if mdl == "M0": return ps(st)
    if mdl == "M1": return ps(st) or (ext[0] == 1 and st[3] == 1)
    return ext[1] == 1 if mdl == "M2" else ext[0] == 1


def record(mdl, st):
    return (mdl == "M1" and st[5] == 1) or (mdl == "M2" and st[5] == "EXC")


class Model:
    def __init__(self, mdl, axioms):
        self.mdl, self.ax = mdl, set(axioms)
        bars = UPSETS if "A0" in self.ax else ALL_BARS
        doms = [d for _, d in MODELS[mdl]["ext"]]
        self.states = [(p, s, e, g, u) + ext for p in P for s in S for e in E for g in (0, 1) for u in bars
                       for ext in itertools.product(*doms)]
        self._succ = {}

    def ok(self, x, k, y):
        a, m = self.ax, self.mdl
        p, s, e, g, u = x[:5]; p2, s2, e2, g2, u2 = y[:5]
        if "A1" in a and p2 != p: return False
        if "A6" in a and u2 != u: return False
        if k == "COMP" and "A4" in a: return False
        if k == "GOV" and "A2e" in a and e2 != e: return False
        if k in EV:
            if "A3g" in a and g2 != g: return False
            if "A3s" in a and s2 != s: return False
            if "A3m" in a:
                if k == "EVID" and RANK[e2] < RANK[e]: return False
                if k == "EVIDREF" and RANK[e2] > RANK[e]: return False
        if k == "WORK":
            if "A5e" in a and e2 != e: return False
            if "A5g" in a and g2 != g: return False
            if "A5s" in a and s2 != s: return False
        xe, ye = x[5:], y[5:]
        below = RANK[e] >= 1 and e not in u
        if m == "M1":
            (xx, xv), (yx, yv) = xe, ye
            if "X1" in a and (yx != xx or yv != xv) and k != "GOV": return False
            if "X2" in a and xx == 0 and yx == 1 and not below: return False
            if "X3" in a and xx == 0 and yx == 1 and yv != 1: return False
            if "X4" in a and (yx < xx or yv < xv): return False
        elif m == "M2":
            (xm, xa), (ym, ya) = xe, ye
            if "Y1" in a and (ym != xm or ya != xa) and k != "GOV": return False
            if "Y2" in a and xm == "NONE" and ym == "EXC" and not below: return False
            if "Y2b" in a and xm == "NONE" and ym == "RULE" and e not in u: return False
            if "Y3" in a and xa == 0 and ya == 1 and not ((ym == "RULE" and e in u) or ym == "EXC"): return False
            if "Y4" in a and (ya < xa or (xm != "NONE" and ym != xm)): return False
        elif m == "M3":
            (xa,), (ya,) = xe, ye
            if "Z1" in a and ya != xa and k != "GOV": return False
            if "Z2" in a and ya < xa: return False
        return True

    def succ(self, x, kinds):
        key = (x, kinds)
        if key not in self._succ:
            self._succ[key] = [(k, y) for k in kinds for y in self.states if self.ok(x, k, y)]
        return self._succ[key]

    def fresh(self, st): return st[5:] == MODELS[self.mdl]["init"]


def bfs(M, starts, kinds, target, bad_flags=None, step_filter=None, need_gov=False):
    """Shortest path from starts to a target state. Flags track (EVIDany, EVID, GOV).
    bad_flags(f): the path counts only if it returns True (e.g. a D3 violation). step_filter(x,k,y): allowed step."""
    seen, q = set(), deque()
    for x0 in starts:
        f0 = (False, False, False); seen.add((x0, f0)); q.append((x0, f0, [("start", x0)]))
    while q:
        x, f, path = q.popleft()
        if len(path) > 1 and target(x) and (bad_flags is None or bad_flags(f)) and (not need_gov or f[2]):
            return path
        for k, y in M.succ(x, kinds):
            if step_filter and not step_filter(x, k, y): continue
            nf = (f[0] or k in EV, f[1] or k == "EVID", f[2] or k == "GOV")
            if (y, nf) not in seen:
                seen.add((y, nf)); q.append((y, nf, path + [(k, y)]))
    return None


def fmt(path):
    if path is None: return None
    return [k + ":" + ",".join(str(v) if not isinstance(v, frozenset) else "{" + ",".join(sorted(v)) + "}" for v in st)
            for k, st in path]


def properties(M):
    mdl = M.mdl; fam = set(UPSETS) if "A0" in M.ax else set(ALL_BARS)
    adm = [x for x in M.states if x[4] in fam]
    out = {}
    w = bfs(M, [x for x in adm if x[2] not in x[4]], ("GOV",), lambda y: y[2] in y[4]); out["D1"] = w is None
    w = bfs(M, [x for x in adm if x[3] == 0], ("EVID", "EVIDREF", "WORK"), lambda y: y[3] == 1); out["D2"] = w is None
    starts = [x for x in adm if M.fresh(x) and x[2] not in x[4] and x[3] == 0]
    for tag, f in (("PS", ps), ("N", lambda y: notion(mdl, y))):
        st = [x for x in starts if not f(x)]
        out[f"D3[{tag}]"] = bfs(M, st, KINDS, f, bad_flags=lambda fl: not (fl[0] and fl[2])) is None
        out[f"D3+[{tag}]"] = bfs(M, st, KINDS, f, bad_flags=lambda fl: not (fl[1] and fl[2])) is None
        out[f"D5[{tag}]"] = all(f(y) for x in adm if f(x) for k, y in M.succ(x, ("EVID",)))
        out[f"NV[{tag}]"] = bfs(M, st, KINDS, f) is not None
    N = lambda y: notion(mdl, y)
    out["D6"] = all(y[0] == x[0] for x in adm for k, y in M.succ(x, KINDS))
    s0 = [x for x in starts if x[2] == "n0" and not N(x)]
    out["D3-scope"] = bfs(M, s0, KINDS, N, bad_flags=lambda fl: not (fl[0] and fl[2])) is None
    out["P-GUARD"] = bfs(M, [x for x in adm if M.fresh(x) and x[2] == "n0" and not N(x)],
                         ("GOV", "EVIDREF", "WORK", "COMP"), N) is None
    if mdl == "M0":
        out["P-BAR"] = "N/A"
    else:
        seen = set(x for x in adm if M.fresh(x)); q = deque(seen); bad = False
        while q:
            x = q.popleft()
            if N(x) and x[2] not in x[4] and not record(mdl, x): bad = True; break
            for k, y in M.succ(x, KINDS):
                if y not in seen: seen.add(y); q.append(y)
        out["P-BAR"] = not bad
    return out


def scenarios(mdl):
    M = Model(mdl, BASE + MODELS[mdl]["extra"]); N = lambda y: notion(mdl, y)
    init = MODELS[mdl]["init"]
    def st(e): return [(p, s, e, 0, U0) + init for p in P for s in S]
    ucon = lambda x, k, y: y[4] == x[4]
    noexc = lambda x, k, y: ucon(x, k, y) and not record(mdl, y)
    GW = ("GOV", "WORK")
    r = {}
    r["S-T:N"] = fmt(bfs(M, st("n1"), GW, N, step_filter=ucon, need_gov=True))
    r["S-T:PS"] = fmt(bfs(M, st("n1"), GW, ps, step_filter=ucon, need_gov=True))
    if mdl == "M1":
        r["S-T:N&v=1"] = fmt(bfs(M, st("n1"), GW, lambda y: N(y) and y[6] == 1, step_filter=ucon, need_gov=True))
    r["S0"] = fmt(bfs(M, st("n2"), KINDS, N, step_filter=ucon))
    r["S1"] = fmt(bfs(M, st("n1"), GW, N, step_filter=noexc))
    r["S3"] = fmt(bfs(M, st("n0"), GW, N, step_filter=ucon))
    r["S4"] = any(y[4] != x[4] for x in M.states for k, y in M.succ(x, KINDS))
    M6 = Model(mdl, [a for a in BASE + MODELS[mdl]["extra"] if a != "A6"])
    st6 = [(p, s, "n1", 0, U0) + init for p in P for s in S]
    r["S4-T"] = fmt(bfs(M6, st6, GW, N, need_gov=True))
    seen = set(st("n1")); q = deque(seen)
    while q:
        x = q.popleft()
        for k, y in M.succ(x, GW):
            if ucon(x, k, y) and y not in seen: seen.add(y); q.append(y)
    r["S5"] = all(x[4] == U0 for x in seen)
    return r


def main():
    res = {}
    for mdl in MODELS:
        full = BASE + MODELS[mdl]["extra"]
        M = Model(mdl, full)
        rec = {"state_count": len(M.states), "properties": properties(M), "scenarios": scenarios(mdl)}
        rec["ablation"] = {ax: properties(Model(mdl, [a for a in full if a != ax])) for ax in full}
        extra = MODELS[mdl]["extra"]; mins = {}
        held = [k for k, v in rec["properties"].items() if v is True]
        cache = {}
        for r_ in range(len(extra) + 1):
            for sub in itertools.combinations(extra, r_):
                cache[sub] = properties(Model(mdl, BASE + sub))
        for pk in held:
            ok = [set(sub) for sub in cache if cache[sub].get(pk) is True]
            mins[pk] = sorted(sorted(s) for s in ok if not any(o < s for o in ok))
        rec["minimal_extra_sets"] = mins
        res[mdl] = rec
        print(f"[{mdl}] states={rec['state_count']}", file=sys.stderr)
    json.dump(res, sys.stdout, indent=1, default=str)


if __name__ == "__main__":
    main()
