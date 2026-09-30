"""Gate 2 r2 reference implementation of SPEC-G2-r2.md (Claude; SECONDARY_REVIEW). Deterministic; standard library.

Exactness: the step relation is the MAXIMAL relation permitted by the axiom set (constructed by enumerating, per
component, every value the axioms leave open, then filtering with the full step predicate). Universal properties
are decided on it by exhaustive traversal (monotone in the relation); existential ones are witnessed by it.
"""
import itertools
import json
import sys
from collections import deque

P = ("gen", "der"); S = ("auth", "prov", "hist"); KINDS = ("GOV", "EVID", "EVIDREF", "WORK", "COMP"); EV = {"EVID", "EVIDREF"}
BASE = ("A0", "A1", "A2e", "A3g", "A3s", "A3m", "A4", "A5e", "A5g", "A5s", "A6")
EXTRA = {"M0": (), "M0b": ("B6",), "M1": ("X1", "X2", "X3", "X4"), "M2": ("Y1", "Y2", "Y2b", "Y3", "Y4"),
         "M3a": ("W1", "W3", "W4"), "M3b": ("W1", "W2", "W3", "W4")}
FLOOR_MODELS = {"M1", "M2", "M3b"}


def closure(el, pairs):
    leq = {(a, a) for a in el} | set(pairs); ch = True
    while ch:
        ch = False
        for (a, b), (c, d) in itertools.product(list(leq), repeat=2):
            if b == c and (a, d) not in leq: leq.add((a, d)); ch = True
    return leq


POSETS = {"chain3": (("n0", "n1", "n2"), closure(("n0", "n1", "n2"), {("n0", "n1"), ("n1", "n2")})),
          "V": (("bot", "a", "b"), closure(("bot", "a", "b"), {("bot", "a"), ("bot", "b")})),
          "diamond": (("bot", "a", "b", "top"), closure(("bot", "a", "b", "top"), {("bot", "a"), ("bot", "b"), ("a", "top"), ("b", "top")})),
          "antichain2": (("a", "b"), closure(("a", "b"), set()))}


class Sys:
    def __init__(self, mdl, inst, axioms):
        self.mdl, self.ax = mdl, set(axioms)
        self.E, self.leq = POSETS[inst]
        mins = [a for a in self.E if all((b, a) not in self.leq or b == a for b in self.E)]
        self.bot = mins[0] if len(mins) == 1 else None
        allb = [frozenset(c) for r in range(len(self.E) + 1) for c in itertools.combinations(self.E, r)]
        self.ups = [u for u in allb if all(b in u for a in u for (x, b) in self.leq if x == a)]
        self.bars = self.ups if "A0" in self.ax else allb
        self.extdoms = {"M0": [], "M0b": [self.bars], "M1": [(0, 1), (0, 1)], "M2": [("NONE", "RULE", "EXC"), (0, 1)],
                        "M3a": [(0, 1), (0, 1)], "M3b": [(0, 1), (0, 1)]}[mdl]
        self.states = [(p, s, e, g, u) + ext for p in P for s in S for e in self.E for g in (0, 1) for u in self.bars
                       for ext in itertools.product(*self.extdoms)]
        self.adj = {x: self._succ(x) for x in self.states}

    # --- predicates
    def N(self, x):
        e, g, u = x[2], x[3], x[4]; m = self.mdl
        if m == "M0": return e in u and g == 1
        if m == "M0b": return e in x[5] and g == 1
        if m == "M1": return (e in u and g == 1) or (x[5] == 1 and g == 1)
        return x[6] == 1

    def PS(self, x): return x[2] in x[4] and x[3] == 1
    def record(self, x): return (self.mdl == "M1" and x[5] == 1) or (self.mdl == "M2" and x[5] == "EXC")

    def fresh(self, x):
        m = self.mdl
        if m == "M0": return True
        if m == "M0b": return x[5] == x[4]
        if m == "M1": return x[5:] == (0, 0)
        if m == "M2": return x[5:] == ("NONE", 0)
        return x[5:] == (0, 0)

    def ok(self, x, k, y):
        a = self.ax; p, s, e, g, u = x[:5]; p2, s2, e2, g2, u2 = y[:5]
        if "A1" in a and p2 != p: return False
        if "A6" in a and u2 != u: return False
        if k == "COMP" and "A4" in a: return False
        if k == "GOV" and "A2e" in a and e2 != e: return False
        if k in EV:
            if "A3g" in a and g2 != g: return False
            if "A3s" in a and s2 != s: return False
            if "A3m" in a:
                if k == "EVID" and (e, e2) not in self.leq: return False
                if k == "EVIDREF" and (e2, e) not in self.leq: return False
        if k == "WORK":
            if "A5e" in a and e2 != e: return False
            if "A5g" in a and g2 != g: return False
            if "A5s" in a and s2 != s: return False
        m = self.mdl; xe, ye = x[5:], y[5:]
        below = (self.bot is not None and e != self.bot and e not in u)
        if m == "M0b":
            if "B6" in a and ye[0] != xe[0]: return False
        elif m == "M1":
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
        elif m in ("M3a", "M3b"):
            (xu, xa), (yu, ya) = xe, ye
            if "W1" in a and (yu != xu or ya != xa) and k != "GOV": return False
            if "W2" in a and xu == 0 and yu == 1 and not (e in u or (self.bot is not None and e != self.bot)): return False
            if "W3" in a and xa == 0 and ya == 1 and yu != 1: return False
            if "W4" in a and (yu < xu or ya < xa): return False
        return True

    def _succ(self, x):
        a = self.ax; p, s, e, g, u = x[:5]; out = []
        for k in KINDS:
            if k == "COMP" and "A4" in a: continue
            ps = [p] if "A1" in a else P
            ss = [s] if ((k in EV and "A3s" in a) or (k == "WORK" and "A5s" in a)) else S
            es = [e] if ((k == "GOV" and "A2e" in a) or (k == "WORK" and "A5e" in a)) else self.E
            gs = [g] if ((k in EV and "A3g" in a) or (k == "WORK" and "A5g" in a)) else (0, 1)
            us = [u] if "A6" in a else self.bars
            for y in itertools.product(ps, ss, es, gs, us, *self.extdoms):
                if self.ok(x, k, y): out.append((k, y))
        return out


def bfs(sy, starts, kinds, target, bad=None, filt=None, need_gov=False):
    seen = {}; q = deque()
    for x0 in starts:
        key = (x0, (False, False, False)); seen[key] = None; q.append(key)
    while q:
        x, f = q.popleft()
        if seen[(x, f)] is not None and target(x) and (bad is None or bad(f)) and (not need_gov or f[2]):
            path = []; key = (x, f)
            while key is not None:
                par = seen[key]; path.append(key[0]); key = par[0] if par else None
            return list(reversed(path))
        for k, y in sy.adj[x]:
            if k not in kinds or (filt and not filt(x, k, y)): continue
            nf = (f[0] or k in EV, f[1] or k == "EVID", f[2] or k == "GOV")
            if (y, nf) not in seen: seen[(y, nf)] = ((x, f), k); q.append((y, nf))
    return None


def reach(sy):
    R = {x: None for x in sy.states if sy.fresh(x)}; q = deque(R)
    while q:
        x = q.popleft()
        for k, y in sy.adj[x]:
            if y not in R: R[y] = x; q.append(y)
    return R


def path_to(R, x):
    p = []
    while x is not None: p.append(x); x = R[x]
    return list(reversed(p))


def fmt(path): return None if path is None else ["|".join("{" + ",".join(sorted(v)) + "}" if isinstance(v, frozenset) else str(v) for v in st) for st in path]


def universal(items, pred):
    """items: list of (state/edge, witness_path); returns class + witness."""
    if not items: return "VACUOUS", None
    for it, wp in items:
        if not pred(it): return "FAILS", wp
    return "HOLDS", None


def props(sy):
    o = {}; N = sy.N
    w = bfs(sy, [x for x in sy.states if x[2] not in x[4]], {"GOV"}, lambda y: y[2] in y[4]); o["D1"] = ("HOLDS", None) if w is None else ("FAILS", w)
    w = bfs(sy, [x for x in sy.states if x[3] == 0], {"EVID", "EVIDREF", "WORK"}, lambda y: y[3] == 1); o["D2"] = ("HOLDS", None) if w is None else ("FAILS", w)
    bad6 = next(([x, y] for x in sy.states for k, y in sy.adj[x] if y[0] != x[0]), None); o["D6"] = ("HOLDS", None) if bad6 is None else ("FAILS", bad6)
    starts = [x for x in sy.states if sy.fresh(x) and x[2] not in x[4] and x[3] == 0 and not N(x)]
    for tag, f in (("N", N), ("PS", sy.PS)):
        st = [x for x in starts if not f(x)]
        if not st: o[f"D3-history[{tag}]"] = ("VACUOUS", None); continue
        w = bfs(sy, st, set(KINDS), f, bad=lambda fl: not (fl[0] and fl[2])); o[f"D3-history[{tag}]"] = ("HOLDS", None) if w is None else ("FAILS", w)
    w = bfs(sy, starts, set(KINDS), N, bad=lambda fl: not (fl[1] and fl[2])); o["D3+[N]"] = ("VACUOUS", None) if not starts else (("HOLDS", None) if w is None else ("FAILS", w))
    bad5 = next(([x, y] for x in sy.states if N(x) for k, y in sy.adj[x] if k == "EVID" and not N(y)), None)
    o["D5[N]"] = ("HOLDS", None) if bad5 is None else ("FAILS", bad5)
    w = bfs(sy, starts, set(KINDS), N); o["NV[N]"] = ("HOLDS", w) if w else ("FAILS", None)
    R = reach(sy)
    events = [((x, y), path_to(R, x) + [y]) for x in R for k, y in sy.adj[x] if k == "GOV" and not N(x) and N(y)]
    o["D3-state-bar-event"] = universal(events, lambda xy: xy[1][2] in xy[1][4])
    fe = [(xy, wp) for xy, wp in events if xy[1][2] not in xy[1][4]]
    o["D3-state-floor-event"] = universal(fe, lambda xy: xy[1][2] != sy.bot)
    nstates = [(x, path_to(R, x)) for x in R if N(x)]
    o["D3-state-bar-inv"] = universal(nstates, lambda x: x[2] in x[4])
    o["D3-state-floor-inv"] = universal([(x, wp) for x, wp in nstates if x[2] not in x[4]], lambda x: x[2] != sy.bot)
    gs = [x for x in sy.states if sy.fresh(x) and x[2] == sy.bot and x[2] not in x[4] and not N(x)]
    if not gs: o["P-GUARD"] = ("VACUOUS", None)
    else:
        w = bfs(sy, gs, {"GOV", "EVIDREF", "WORK", "COMP"}, N); o["P-GUARD"] = ("HOLDS", None) if w is None else ("FAILS", w)
    o["P-BAR"] = universal([(x, wp) for x, wp in nstates if x[2] not in x[4]], sy.record)
    pe = [((x, y), path_to(R, x) + [y]) for x in R if N(x) for k, y in sy.adj[x] if k == "EVIDREF"]
    o["P-PERSIST"] = universal(pe, lambda xy: N(xy[1]))
    counts = {"states": len(sy.states), "reachable": len(R), "reachable_N": sum(1 for x in R if N(x))}
    return {k: {"class": v[0], "witness": fmt(v[1]), "len": (len(v[1]) - 1) if v[1] else None} for k, v in o.items()}, counts


def scenarios(mdl):
    full = BASE + EXTRA[mdl]; sy = Sys(mdl, "chain3", full); N = sy.N; U0 = frozenset({"n2"})
    def st(e, s=sy): return [x for x in s.states if x[2] == e and x[3] == 0 and x[4] == U0 and s.fresh(x)]
    const = lambda x, k, y: y[4] == x[4] and (mdl != "M0b" or y[5] == x[5])
    GW = {"GOV", "WORK"}; r = {}
    r["S0"] = fmt(bfs(sy, st("n2"), set(KINDS), N, filt=const))
    r["S1"] = fmt(bfs(sy, st("n1"), GW, N, filt=lambda x, k, y: const(x, k, y) and not sy.record(y)))
    r["S2:N"] = fmt(bfs(sy, st("n1"), GW, N, filt=const, need_gov=True))
    r["S2:PS"] = fmt(bfs(sy, st("n1"), GW, sy.PS, filt=const, need_gov=True))
    r["S3"] = fmt(bfs(sy, st("n0"), GW, N, filt=const))
    r["S4:u"] = any(y[4] != x[4] for x in sy.states for k, y in sy.adj[x])
    if mdl == "M0b": r["S4:b"] = any(y[5] != x[5] for x in sy.states for k, y in sy.adj[x])
    if mdl in ("M0", "M0b"):
        drop = "A6" if mdl == "M0" else "B6"; s2 = Sys(mdl, "chain3", [a for a in full if a != drop])
        f2 = None if mdl == "M0" else (lambda x, k, y: y[4] == x[4])
        r["S4-T"] = fmt(bfs(s2, st("n1", s2), GW, s2.N, filt=f2, need_gov=True))
    r["S5"] = fmt(bfs(sy, st("n1"), GW, lambda y: N(y) and y[6] == 1, filt=const)) if mdl == "M1" else "NOT_REPRESENTABLE"
    r["S6"] = fmt(bfs(sy, st("n2"), set(KINDS), lambda y: N(y) and y[2] not in y[4], filt=const))
    return r


def main():
    res = {}
    for mdl in EXTRA:
        full = BASE + EXTRA[mdl]; res[mdl] = {"instances": {}, "scenarios": scenarios(mdl),
                                               "added_components": {"M0": 0, "M0b": 1, "M1": 2, "M2": 2, "M3a": 2, "M3b": 2}[mdl],
                                               "added_axioms": list(EXTRA[mdl])}
        for inst in POSETS:
            if inst == "antichain2" and mdl in FLOOR_MODELS:
                res[mdl]["instances"][inst] = "NOT_APPLICABLE"; continue
            pv, counts = props(Sys(mdl, inst, full))
            abl = {ax: {k: v["class"] for k, v in props(Sys(mdl, inst, [a for a in full if a != ax]))[0].items()} for ax in full}
            subs = {sub: {k: v["class"] for k, v in props(Sys(mdl, inst, BASE + sub))[0].items()}
                    for r_ in range(len(EXTRA[mdl]) + 1) for sub in itertools.combinations(EXTRA[mdl], r_)}
            mins = {}
            for k, v in pv.items():
                if v["class"] != "HOLDS": continue
                okk = [set(s) for s, t in subs.items() if t.get(k) == "HOLDS"]
                mins[k] = sorted(sorted(s) for s in okk if not any(o < s for o in okk))
            used = set(a for L in mins.values() for s in L for a in s)
            res[mdl]["instances"][inst] = {"counts": counts, "properties": pv, "ablation": abl, "minimal_added_sets": mins,
                                           "redundant_added_axioms": sorted(set(EXTRA[mdl]) - used)}
            print(f"[{mdl}/{inst}] {counts}", file=sys.stderr)
    json.dump(res, sys.stdout, indent=1, default=str)


if __name__ == "__main__":
    main()
