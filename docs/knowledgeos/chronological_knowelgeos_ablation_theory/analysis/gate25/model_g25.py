"""Gate 2.5 reference implementation of SPEC-G25-r1.md (Claude; SECONDARY_REVIEW). Deterministic; standard library.

Exactness: maximal step relation per axiom set (component-wise enumeration + full step predicate); universal
properties decided by exhaustive traversal, existential ones witnessed on the maximal relation (as Gate 2 / R-2).
Declared readings: (R1) each element of an ordered pattern is a distinct step, strictly after the previous one;
(R2) intermediate PromEvents are allowed except where the spec forbids them (T6: none before the EVID step);
(R3) TOCTOU's "state with not-EF" may be the target of the AuthEvent itself or any later state before the PromEvent;
(R4) t_a / t_p are the source states of the pattern's AuthEvent / final PromEvent;
(R5) universal FAILS witnesses: the shortest fresh-to-event trajectory among all failing items.
"""
import itertools
import json
import sys
from collections import deque

P = ("gen", "der"); S = ("auth", "prov", "hist"); KINDS = ("GOV", "EVID", "EVIDREF", "WORK", "COMP"); EV = {"EVID", "EVIDREF"}
BASE = ("A0", "A1", "A2e", "A3g", "A3s", "A3m", "A4", "A5e", "A5g", "A5s", "A6")
MECH = ("T-G1", "T-AM", "T-PROM")
EXTRA = {"MT0": MECH, "MT1-P0": MECH + ("T-AUTH", "T-P0"), "MT1-P1": MECH + ("T-AUTH", "T-P1"),
         "MT2-P0": MECH + ("T-AUTH", "T-EVENT", "T-P0"), "MT2-P1": MECH + ("T-AUTH", "T-EVENT", "T-P1")}


def closure(el, pairs):
    leq = {(a, a) for a in el} | set(pairs); ch = True
    while ch:
        ch = False
        for (a, b), (c, d) in itertools.product(list(leq), repeat=2):
            if b == c and (a, d) not in leq: leq.add((a, d)); ch = True
    return leq


POSETS = {"chain3": (("n0", "n1", "n2"), closure(("n0", "n1", "n2"), {("n0", "n1"), ("n1", "n2")})),
          "V": (("bot", "a", "b"), closure(("bot", "a", "b"), {("bot", "a"), ("bot", "b")})),
          "diamond": (("bot", "a", "b", "top"), closure(("bot", "a", "b", "top"), {("bot", "a"), ("bot", "b"), ("a", "top"), ("b", "top")}))}


class Sys:
    def __init__(self, inst, axioms):
        self.ax = set(axioms); self.E, self.leq = POSETS[inst]
        self.bot = [a for a in self.E if all((b, a) not in self.leq or b == a for b in self.E)][0]
        allb = [frozenset(c) for r in range(len(self.E) + 1) for c in itertools.combinations(self.E, r)]
        self.bars = [u for u in allb if all(b in u for a in u for (x, b) in self.leq if x == a)] if "A0" in self.ax else allb
        self.states = [(p, s, e, g, u, au, ad, rv) for p in P for s in S for e in self.E for g in (0, 1)
                       for u in self.bars for au in (0, 1) for ad in (0, 1) for rv in (0, 1)]
        self.adj = {x: self._succ(x) for x in self.states}

    def E0(self, x): return x[2] != self.bot
    def EB(self, x): return x[2] in x[4]
    def EF(self, x): return self.EB(x) or self.E0(x)

    def ok(self, x, k, y):
        a = self.ax; p, s, e, g, u, au, ad, rv = x; p2, s2, e2, g2, u2, au2, ad2, rv2 = y
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
        if "T-G1" in a and (au2 != au or rv2 != rv or (ad == 0 and ad2 == 1)) and k != "GOV": return False
        if "T-AM" in a and au2 < au: return False
        if "T-PROM" in a and ad == 0 and ad2 == 1 and au2 != 1: return False
        if "T-AUTH" in a and au == 0 and au2 == 1 and not self.EF(x): return False
        if "T-EVENT" in a and ad == 0 and ad2 == 1 and not self.EF(x): return False
        if "T-P1" in a:
            if ad == 1 and ad2 == 0 and not (k == "GOV" and rv2 == 1): return False
            if rv == 0 and rv2 == 1 and not (ad == 1 and ad2 == 0): return False
            if rv2 < rv: return False
        if "T-P0" in a:
            if ad2 == 1 and not self.EF(y): return False
            if ad == 1 and ad2 == 0 and self.EF(y): return False
            if rv2 != 0 or rv != 0: return False
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
            for y in itertools.product(ps, ss, es, gs, us, (0, 1), (0, 1), (0, 1)):
                if self.ok(x, k, y): out.append((k, y))
        return out


def fresh(x): return x[5:] == (0, 0, 0)
def auth_ev(x, y): return x[5] == 0 and y[5] == 1
def prom_ev(x, y): return x[6] == 0 and y[6] == 1


def fmt(path): return None if path is None else ["|".join("{" + ",".join(sorted(v)) + "}" if isinstance(v, frozenset) else str(v) for v in st) for st in path]


def bfs_states(sy, starts, kinds, target_step=None, target_state=None):
    """Shortest path (list of states) to a target step (x,k,y) or target state."""
    par = {x: None for x in starts}; q = deque(starts)
    while q:
        x = q.popleft()
        if target_state and par[x] is not None and target_state(x): return trace(par, x)
        for k, y in sy.adj[x]:
            if k not in kinds: continue
            if target_step and target_step(x, k, y): return trace(par, x) + [y]
            if y not in par: par[y] = x; q.append(y)
    return None


def trace(par, x):
    p = []
    while x is not None: p.append(x); x = par[x]
    return list(reversed(p))


def reach(sy):
    par = {x: None for x in sy.states if fresh(x)}; q = deque(par)
    while q:
        x = q.popleft()
        for k, y in sy.adj[x]:
            if y not in par: par[y] = x; q.append(y)
    return par


def univ(items, pred):
    if not items: return ("VACUOUS", None)
    bad = [w for it, w in items if not pred(it)]
    return ("FAILS", min(bad, key=len)) if bad else ("HOLDS", None)


def phase_bfs(sy, starts, kinds, steps, state_adv=None, forbid_prom_before=None):
    """Shortest trajectory in which the step predicates `steps` fire in order on distinct steps (R1).
    state_adv[i]: optional state predicate that also completes phase i when true of the entered state (R3).
    forbid_prom_before: phase index before whose completion no PromEvent may occur (T6).
    Returns (path, kinds, fire_indices) or (None, None, None)."""
    n = len(steps); state_adv = state_adv or {}
    def adv(ph, y):
        while ph < n and ph in state_adv and state_adv[ph](y): ph += 1
        return ph
    start = [(x, 0) for x in starts]; par = {s: None for s in start}; q = deque(start)
    while q:
        node = q.popleft(); x, ph = node
        for k, y in sy.adj[x]:
            if k not in kinds: continue
            fire = ph < n and steps[ph](x, k, y)
            if forbid_prom_before is not None and ph <= forbid_prom_before and prom_ev(x, y): continue
            nph = adv(ph + 1, y) if fire else ph
            nn = (y, nph)
            if nn in par: continue
            par[nn] = (node, k, fire)
            if nph == n:
                path, ks, fires = [], [], []; cur = nn
                while cur is not None:
                    path.append(cur[0]); pr = par[cur]
                    if pr: ks.append(pr[1]); fires.append(pr[2]); cur = pr[0]
                    else: cur = None
                path.reverse(); ks.reverse(); fires.reverse()
                return path, ks, [i for i, f in enumerate(fires) if f]
            q.append(nn)
    return None, None, None


def properties(sy):
    o = {}
    w = bfs_states(sy, [x for x in sy.states if x[2] not in x[4]], {"GOV"}, target_state=lambda y: y[2] in y[4]); o["D1"] = ("HOLDS", None) if w is None else ("FAILS", w)
    w = bfs_states(sy, [x for x in sy.states if x[3] == 0], {"EVID", "EVIDREF", "WORK"}, target_state=lambda y: y[3] == 1); o["D2"] = ("HOLDS", None) if w is None else ("FAILS", w)
    b6 = next(([x, y] for x in sy.states for k, y in sy.adj[x] if y[0] != x[0]), None); o["D6"] = ("HOLDS", None) if b6 is None else ("FAILS", b6)
    R = reach(sy)
    auths = [((x, y), trace(R, x) + [y]) for x in R for k, y in sy.adj[x] if auth_ev(x, y)]
    proms = [((x, y), trace(R, x) + [y]) for x in R for k, y in sy.adj[x] if prom_ev(x, y)]
    for nm, evs in (("AUTH", auths), ("EVENT", proms)):
        base = "AUTH" if nm == "AUTH" else "EVENT"
        o[f"{base}-{'SAFETY' if nm == 'AUTH' else 'FLOOR-SAFETY'}"] = univ(evs, lambda xy: sy.EF(xy[0]))
        o[f"{base}-EVIDENCE-SAFETY"] = univ(evs, lambda xy: sy.E0(xy[0]))
        o[f"{base}-EVIDENCE-SAFETY-scoped"] = univ([(xy, w) for xy, w in evs if not sy.EB(xy[0])], lambda xy: sy.E0(xy[0]))
        o[f"{base}-ELIGIBILITY-SAFETY"] = univ(evs, lambda xy: sy.EB(xy[0]))
    fr = [x for x in sy.states if fresh(x)]
    path, ks, _ = phase_bfs(sy, fr, set(KINDS), [lambda x, k, y: auth_ev(x, y), lambda x, k, y: not sy.EF(y), lambda x, k, y: prom_ev(x, y)],
                            state_adv={1: lambda y: not sy.EF(y)})
    o["TOCTOU"] = ("REACHABLE", path) if path else ("NOT_REACHABLE", None)
    ps = next((x for x in R if x[6] == 1 and not sy.EF(x)), None); o["PERSIST"] = ("REACHABLE", trace(R, ps)) if ps else ("NOT_REACHABLE", None)
    o["AUTO-INVAL"] = univ([((x, y), trace(R, x) + [y]) for x in R if x[6] == 1 for k, y in sy.adj[x] if not sy.EF(y)], lambda xy: xy[1][6] == 0)
    o["REVOC-EXPLICIT"] = univ([((x, k, y), trace(R, x) + [y]) for x in R if x[6] == 1 for k, y in sy.adj[x] if y[6] == 0],
                               lambda xky: xky[1] == "GOV" and xky[2][7] == 1)
    rs = [x for x in R if x[5] == 1 and x[6] == 0 and not sy.EF(x)]
    wa = bfs_states(sy, rs, {"GOV", "EVIDREF", "WORK", "COMP"}, target_step=lambda x, k, y: prom_ev(x, y)) if rs else None
    wb = bfs_states(sy, rs, set(KINDS), target_step=lambda x, k, y: prom_ev(x, y)) if rs else None
    o["REVAL-a"] = ("REACHABLE", wa) if wa else ("NOT_REACHABLE", None)
    o["REVAL-b"] = ("REACHABLE", wb) if wb else ("NOT_REACHABLE", None)
    gs = [x for x in sy.states if fresh(x) and x[2] == sy.bot and x[2] not in x[4]]
    w = bfs_states(sy, gs, {"GOV", "EVIDREF", "WORK", "COMP"}, target_state=lambda y: y[6] == 1) if gs else None
    o["P-GUARD"] = ("VACUOUS", None) if not gs else (("HOLDS", None) if w is None else ("FAILS", w))
    counts = {"states": len(sy.states), "reachable": len(R), "reachable_ad1": sum(1 for x in R if x[6] == 1)}
    return {k: {"class": v[0], "len": (len(v[1]) - 1) if v[1] else None, "witness": fmt(v[1])} for k, v in o.items()}, counts


def scenarios(mdl):
    full = BASE + EXTRA[mdl]; sy = Sys("chain3", full); U0 = frozenset({"n2"})
    def st(e, s): return [x for x in s.states if x[2] == e and x[3] == 0 and x[4] == U0 and fresh(x)]
    A = lambda x, k, y: auth_ev(x, y); PR = lambda x, k, y: prom_ev(x, y)
    rep = bfs_states(sy, st("n1", sy), {"GOV", "WORK"}, target_state=lambda y: y[6] == 1)
    out = {"REP": {"reachable": rep is not None, "witness": fmt(rep)}}
    defs = {"T1": ("n1", {"GOV", "WORK"}, [A, PR], sy),
            "T2": ("n1", set(KINDS), [A, lambda x, k, y: k == "EVID" and (x[2], y[2]) in sy.leq and x[2] != y[2], PR], sy),
            "T3": ("n2", set(KINDS), [A, lambda x, k, y: k == "EVIDREF" and y[2] == "n1", PR], sy),
            "T4": ("n1", set(KINDS), [A, lambda x, k, y: k == "EVIDREF" and y[2] == "n0", PR], sy),
            "T6": ("n1", set(KINDS), [A, lambda x, k, y: k == "EVID" and y[2] in y[4], PR], sy)}
    s5 = Sys("chain3", [a for a in full if a != "A6"])
    defs["T5"] = ("n1", set(KINDS), [A, lambda x, k, y: y[4] != x[4], PR], s5)
    for tid in ("T1", "T2", "T3", "T4", "T5", "T6"):
        e0, kinds, steps, sysx = defs[tid]
        path, ks, fi = phase_bfs(sysx, st(e0, sysx), kinds, steps, forbid_prom_before=1 if tid == "T6" else None)
        if path is None: out[tid] = {"reachable": False}; continue
        ia, ip = fi[0], fi[-1]
        ta, tp = path[ia], path[ip]
        out[tid] = {"reachable": True, "len": len(path) - 1, "witness": fmt(path), "kinds": ks,
                    "t_a": {"e": ta[2], "u": sorted(ta[4]), "E0": sysx.E0(ta), "EB": sysx.EB(ta)},
                    "t_p": {"e": tp[2], "u": sorted(tp[4]), "E0": sysx.E0(tp), "EB": sysx.EB(tp)},
                    "auth_guard_in_model": "T-AUTH" in sysx.ax, "event_guard_in_model": "T-EVENT" in sysx.ax}
    return out


def main():
    res = {}
    for mdl, extra in EXTRA.items():
        full = BASE + extra; res[mdl] = {"role": "control" if mdl == "MT0" else "primary", "added_axioms": list(extra),
                                         "scenarios": scenarios(mdl), "instances": {}}
        for inst in POSETS:
            pv, counts = properties(Sys(inst, full))
            abl = {ax: {k: v["class"] for k, v in properties(Sys(inst, [a for a in full if a != ax]))[0].items()} for ax in full}
            subs = {sub: {k: v["class"] for k, v in properties(Sys(inst, BASE + sub))[0].items()}
                    for r_ in range(len(extra) + 1) for sub in itertools.combinations(extra, r_)}
            mins = {}
            for k, v in pv.items():
                if v["class"] not in ("HOLDS", "VACUOUS"): continue
                okk = [set(s) for s, t in subs.items() if t.get(k) in ("HOLDS", "VACUOUS")]
                mins[k] = sorted(sorted(s) for s in okk if not any(o < s for o in okk))
            used = set(a for L in mins.values() for s in L for a in s)
            res[mdl]["instances"][inst] = {"counts": counts, "properties": pv, "ablation": abl, "minimal_added_sets": mins,
                                           "redundant_added_axioms": sorted(set(extra) - used)}
            print(f"[{mdl}/{inst}] {counts}", file=sys.stderr)
    json.dump(res, sys.stdout, indent=1, default=str)


if __name__ == "__main__":
    main()
