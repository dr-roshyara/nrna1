#!/usr/bin/env python3
"""Independent exhaustive verifier for SPEC-G25-r1 (standard library only).

Every item is decided by explicit enumeration of the finite state space and
of the maximal step relation admitted by the axiom set, followed by
breadth-first search (shortest witnesses).  See METHOD.md.
"""
import json
import sys
from collections import deque
from itertools import combinations

GOV, EVID, EVIDREF, WORK, COMP = "GOV", "EVID", "EVIDREF", "WORK", "COMP"
KINDS = [GOV, EVID, EVIDREF, WORK, COMP]
EVK = (EVID, EVIDREF)
P_VALS = ["gen", "der"]
S_VALS = ["auth", "prov", "hist"]

BASE_AXIOMS = ["A0", "A1", "A2e", "A3g", "A3s", "A3m", "A4", "A5e", "A5g", "A5s", "A6"]
ADDED_AXIOMS = ["T-G1", "T-AM", "T-PROM", "T-AUTH", "T-EVENT", "T-P1", "T-P0"]

MODELS = {
    "MT0": ["T-G1", "T-AM", "T-PROM"],
    "MT1-P0": ["T-G1", "T-AM", "T-PROM", "T-AUTH", "T-P0"],
    "MT1-P1": ["T-G1", "T-AM", "T-PROM", "T-AUTH", "T-P1"],
    "MT2-P0": ["T-G1", "T-AM", "T-PROM", "T-AUTH", "T-EVENT", "T-P0"],
    "MT2-P1": ["T-G1", "T-AM", "T-PROM", "T-AUTH", "T-EVENT", "T-P1"],
}

UNIVERSAL = [
    "AUTH-SAFETY", "AUTH-EVIDENCE-SAFETY", "AUTH-EVIDENCE-SAFETY-scoped",
    "AUTH-ELIGIBILITY-SAFETY", "EVENT-FLOOR-SAFETY", "EVENT-EVIDENCE-SAFETY",
    "EVENT-EVIDENCE-SAFETY-scoped", "EVENT-ELIGIBILITY-SAFETY",
    "AUTO-INVAL", "REVOC-EXPLICIT", "P-GUARD", "D1", "D2", "D6",
]
EXISTENTIAL = ["TOCTOU", "PERSIST", "REVAL-a", "REVAL-b", "REP"]
PROP_ORDER = [
    "AUTH-SAFETY", "AUTH-EVIDENCE-SAFETY", "AUTH-EVIDENCE-SAFETY-scoped",
    "AUTH-ELIGIBILITY-SAFETY", "EVENT-FLOOR-SAFETY", "EVENT-EVIDENCE-SAFETY",
    "EVENT-EVIDENCE-SAFETY-scoped", "EVENT-ELIGIBILITY-SAFETY", "TOCTOU",
    "PERSIST", "AUTO-INVAL", "REVOC-EXPLICIT", "REVAL-a", "REVAL-b", "REP",
    "P-GUARD", "D1", "D2", "D6",
]


# ---------------------------------------------------------------- instances
def make_instance(name, elems, covers):
    n = len(elems)
    leq = [[i == j for j in range(n)] for i in range(n)]
    for (a, b) in covers:
        leq[elems.index(a)][elems.index(b)] = True
    for k in range(n):  # reflexive-transitive closure (Warshall)
        for i in range(n):
            for j in range(n):
                if leq[i][k] and leq[k][j]:
                    leq[i][j] = True
    minimal = [i for i in range(n) if all(not leq[j][i] or j == i for j in range(n))]
    assert len(minimal) == 1
    bot = minimal[0]
    all_subsets = list(range(1 << n))
    upsets = [m for m in all_subsets
              if all(not ((m >> i) & 1) or ((m >> j) & 1)
                     for i in range(n) for j in range(n) if leq[i][j])]
    return {"name": name, "elems": elems, "n": n, "leq": leq, "bot": bot,
            "upsets": upsets, "all_subsets": all_subsets}


INSTANCES = [
    make_instance("chain3", ["n0", "n1", "n2"], [("n0", "n1"), ("n1", "n2")]),
    make_instance("V", ["bot", "a", "b"], [("bot", "a"), ("bot", "b")]),
    make_instance("diamond", ["bot", "a", "b", "top"],
                  [("bot", "a"), ("a", "top"), ("bot", "b"), ("b", "top")]),
]


# ---------------------------------------------------------------- model
class Model:
    """State space + maximal step relation for one instance and axiom set."""

    def __init__(self, inst, sigma):
        self.inst = inst
        self.sigma = frozenset(sigma)
        S = self.sigma
        n = inst["n"]
        leq = inst["leq"]
        self.bot = inst["bot"]
        self.bars = inst["upsets"] if "A0" in S else inst["all_subsets"]
        bars = self.bars
        nb = len(bars)
        # base tuples (p, s, e, g, ui)
        base = [(p, s, e, g, ui) for p in range(2) for s in range(3)
                for e in range(n) for g in range(2) for ui in range(nb)]
        flags = [(au, ad, rv) for au in range(2) for ad in range(2) for rv in range(2)]
        self.states = [b + f for b in base for f in flags]
        self.index = {st: i for i, st in enumerate(self.states)}

        def EF(e, ui):
            return ((bars[ui] >> e) & 1) == 1 or e != self.bot

        self.EFf = EF

        # base relation per kind
        def base_ok(k, b, b2):
            p, s, e, g, ui = b
            p2, s2, e2, g2, ui2 = b2
            if "A1" in S and p2 != p: return False
            if "A2e" in S and k == GOV and e2 != e: return False
            if "A3g" in S and k in EVK and g2 != g: return False
            if "A3s" in S and k in EVK and s2 != s: return False
            if "A3m" in S:
                if k == EVID and not leq[e][e2]: return False
                if k == EVIDREF and not leq[e2][e]: return False
            if "A4" in S and k == COMP: return False
            if "A5e" in S and k == WORK and e2 != e: return False
            if "A5g" in S and k == WORK and g2 != g: return False
            if "A5s" in S and k == WORK and s2 != s: return False
            if "A6" in S and ui2 != ui: return False
            return True

        base_next = {}
        for k in KINDS:
            for b in base:
                p, s, e, g, ui = b
                ps = [p] if "A1" in S else range(2)
                us = [ui] if "A6" in S else range(nb)
                lst = []
                for p2 in ps:
                    for s2 in range(3):
                        for e2 in range(n):
                            for g2 in range(2):
                                for ui2 in us:
                                    b2 = (p2, s2, e2, g2, ui2)
                                    if base_ok(k, b, b2):
                                        lst.append(b2)
                base_next[(k, b)] = lst

        def added_ok(k, e, ui, f, e2, ui2, f2):
            au, ad, rv = f
            au2, ad2, rv2 = f2
            prom = ad == 0 and ad2 == 1
            if "T-G1" in S and (au2 != au or rv2 != rv or prom) and k != GOV: return False
            if "T-AM" in S and au2 < au: return False
            if "T-PROM" in S and prom and au2 != 1: return False
            if "T-AUTH" in S and au == 0 and au2 == 1 and not EF(e, ui): return False
            if "T-EVENT" in S and prom and not EF(e, ui): return False
            if "T-P1" in S:
                if ad == 1 and ad2 == 0 and not (k == GOV and rv2 == 1): return False
                if rv == 0 and rv2 == 1 and not (ad == 1 and ad2 == 0): return False
                if rv2 < rv: return False
            if "T-P0" in S:
                if ad2 == 1 and not EF(e2, ui2): return False
                if ad == 1 and ad2 == 0 and EF(e2, ui2): return False
                if not (rv2 == rv == 0): return False
            return True

        added_cache = {}
        adj = []
        idx = self.index
        for st in self.states:
            b = st[:5]
            f = st[5:]
            e, ui = st[2], st[4]
            out = []
            for k in KINDS:
                for b2 in base_next[(k, b)]:
                    e2, ui2 = b2[2], b2[4]
                    key = (k, e, ui, f, e2, ui2)
                    fl = added_cache.get(key)
                    if fl is None:
                        fl = [f2 for f2 in flags if added_ok(k, e, ui, f, e2, ui2, f2)]
                        added_cache[key] = fl
                    for f2 in fl:
                        out.append((k, idx[b2 + f2]))
            adj.append(out)
        self.adj = adj
        self.fresh = [i for i, st in enumerate(self.states) if st[5:] == (0, 0, 0)]

    # helpers
    def EF(self, i):
        st = self.states[i]
        return self.EFf(st[2], st[4])

    def E0(self, i):
        return self.states[i][2] != self.bot

    def EB(self, i):
        st = self.states[i]
        return ((self.bars[st[4]] >> st[2]) & 1) == 1

    def fmt(self, i):
        p, s, e, g, ui, au, ad, rv = self.states[i]
        u = self.bars[ui]
        return {"p": P_VALS[p], "s": S_VALS[s], "e": self.inst["elems"][e], "g": g,
                "u": [self.inst["elems"][j] for j in range(self.inst["n"]) if (u >> j) & 1],
                "au": au, "ad": ad, "rv": rv}

    def traj(self, path, kinds):
        return {"length": len(kinds), "states": [self.fmt(i) for i in path], "kinds": kinds}


def is_auth(m, i, j):
    return m.states[i][5] == 0 and m.states[j][5] == 1


def is_prom(m, i, j):
    return m.states[i][6] == 0 and m.states[j][6] == 1


# ---------------------------------------------------------------- search
def bfs(m, sources, kind_ok=lambda k: True, edge_ok=None):
    """Multi-source BFS; returns (order, parent).  parent[x] = (prev, kind) or None."""
    parent = {}
    order = []
    dq = deque()
    for s in sources:
        if s not in parent:
            parent[s] = None
            order.append(s)
            dq.append(s)
    while dq:
        x = dq.popleft()
        for (k, y) in m.adj[x]:
            if not kind_ok(k): continue
            if edge_ok is not None and not edge_ok(x, k, y): continue
            if y not in parent:
                parent[y] = (x, k)
                order.append(y)
                dq.append(y)
    return order, parent


def path_to(parent, x):
    path, kinds = [x], []
    while parent[x] is not None:
        x, k = parent[x]
        path.append(x)
        kinds.append(k)
    return path[::-1], kinds[::-1]


def universal_steps(m, order, parent, relevant, good):
    """Over reachable steps (x -k-> y, x reachable): relevant(x,k,y) => good(x,k,y)."""
    any_rel = False
    for x in order:  # BFS order => first violation found is a shortest one
        for (k, y) in m.adj[x]:
            if relevant(x, k, y):
                any_rel = True
                if not good(x, k, y):
                    path, kinds = path_to(parent, x)
                    return {"class": "FAILS", "witness": m.traj(path + [y], kinds + [k])}
    return {"class": "HOLDS" if any_rel else "VACUOUS", "witness": None}


def pattern_search(m, sources, pattern, allowed=None, forbidden=None, same_step=False):
    """Shortest trajectory realising an ordered pattern (0-1 BFS on product).

    pattern: list of ("step", pred(x,k,y)) or ("state", pred(x)).
    allowed(ph, k): kind allowed while in phase ph.
    forbidden(ph, x, k, y): step forbidden while in phase ph.
    Returns None or dict with trajectory and the step indices of step elements.
    """
    L = len(pattern)
    if allowed is None:
        allowed = lambda ph, k: True
    dist = {}
    parent = {}
    dq = deque()

    def eps_close(node, d, front):
        # apply state-conditions (0-cost); nondeterministic: also keep node
        x, ph = node
        while ph < L and pattern[ph][0] == "state" and pattern[ph][1](x):
            nxt = (x, ph + 1)
            if nxt in dist and dist[nxt] <= d:
                return
            dist[nxt] = d
            parent[nxt] = (node, None, None)
            dq.appendleft(nxt) if front else dq.append(nxt)
            node = nxt
            ph += 1

    for s in sources:
        node = (s, 0)
        if node not in dist:
            dist[node] = 0
            parent[node] = None
            dq.append(node)
    for s in sources:
        eps_close((s, 0), 0, False)
    done = set()
    goal = None
    while dq:
        node = dq.popleft()
        if node in done: continue
        done.add(node)
        x, ph = node
        d = dist[node]
        if ph == L:
            goal = node
            break
        # 0-cost state transitions
        if pattern[ph][0] == "state":
            if pattern[ph][1](x):
                nxt = (x, ph + 1)
                if nxt not in dist or dist[nxt] > d:
                    dist[nxt] = d
                    parent[nxt] = (node, None, None)
                    dq.appendleft(nxt)
        for (k, y) in m.adj[x]:
            if not allowed(ph, k): continue
            if forbidden is not None and forbidden(ph, x, k, y): continue
            targets = [(y, ph)]
            if pattern[ph][0] == "step" and pattern[ph][1](x, k, y):
                nph = ph + 1
                if same_step:
                    while nph < L and pattern[nph][0] == "step" and pattern[nph][1](x, k, y):
                        nph += 1
                targets.append((y, nph))
            for nxt in targets:
                if nxt not in dist or dist[nxt] > d + 1:
                    dist[nxt] = d + 1
                    parent[nxt] = (node, k, None)
                    dq.append(nxt)
    if goal is None:
        return None
    # reconstruct
    seq = []
    node = goal
    while parent[node] is not None:
        prev, k, _ = parent[node]
        seq.append((prev, k, node))
        node = prev
    seq.reverse()
    path = [node[0]]
    kinds = []
    marks = {}  # pattern element index -> step index or state index
    for (prev, k, nxt) in seq:
        if k is None:
            # state condition satisfied at current state
            for ph in range(prev[1], nxt[1]):
                marks[ph] = ("state", len(path) - 1)
        else:
            kinds.append(k)
            path.append(nxt[0])
            for ph in range(prev[1], nxt[1]):
                marks[ph] = ("step", len(kinds) - 1)
    return {"path": path, "kinds": kinds, "marks": marks}


# ---------------------------------------------------------------- properties
def evaluate(m, with_witness=True):
    inst = m.inst
    res = {}
    order, parent = bfs(m, m.fresh)
    reach = set(order)
    res["_counts"] = {"states": len(m.states), "reachable": len(reach),
                      "reachable_ad1": sum(1 for i in reach if m.states[i][6] == 1)}

    A = lambda x, k, y: is_auth(m, x, y)
    Pm = lambda x, k, y: is_prom(m, x, y)
    res["AUTH-SAFETY"] = universal_steps(m, order, parent, A, lambda x, k, y: m.EF(x))
    res["AUTH-EVIDENCE-SAFETY"] = universal_steps(m, order, parent, A, lambda x, k, y: m.E0(x))
    res["AUTH-EVIDENCE-SAFETY-scoped"] = universal_steps(
        m, order, parent, lambda x, k, y: A(x, k, y) and not m.EB(x), lambda x, k, y: m.E0(x))
    res["AUTH-ELIGIBILITY-SAFETY"] = universal_steps(m, order, parent, A, lambda x, k, y: m.EB(x))
    res["EVENT-FLOOR-SAFETY"] = universal_steps(m, order, parent, Pm, lambda x, k, y: m.EF(x))
    res["EVENT-EVIDENCE-SAFETY"] = universal_steps(m, order, parent, Pm, lambda x, k, y: m.E0(x))
    res["EVENT-EVIDENCE-SAFETY-scoped"] = universal_steps(
        m, order, parent, lambda x, k, y: Pm(x, k, y) and not m.EB(x), lambda x, k, y: m.E0(x))
    res["EVENT-ELIGIBILITY-SAFETY"] = universal_steps(m, order, parent, Pm, lambda x, k, y: m.EB(x))

    # TOCTOU
    r = pattern_search(m, m.fresh, [("step", A), ("state", lambda x: not m.EF(x)), ("step", Pm)])
    res["TOCTOU"] = exist_result(m, r, ["AuthEvent", "notEF_state", "PromEvent"])

    # PERSIST
    tgt = [x for x in order if m.states[x][6] == 1 and not m.EF(x)]
    if tgt:
        path, kinds = path_to(parent, tgt[0])
        res["PERSIST"] = {"class": "REACHABLE", "witness": m.traj(path, kinds)}
    else:
        res["PERSIST"] = {"class": "NOT_REACHABLE", "witness": None}

    res["AUTO-INVAL"] = universal_steps(
        m, order, parent, lambda x, k, y: m.states[x][6] == 1 and not m.EF(y),
        lambda x, k, y: m.states[y][6] == 0)
    res["REVOC-EXPLICIT"] = universal_steps(
        m, order, parent, lambda x, k, y: m.states[x][6] == 1 and m.states[y][6] == 0,
        lambda x, k, y: k == GOV and m.states[y][7] == 1)

    ycond = lambda x: m.states[x][5] == 1 and m.states[x][6] == 0 and not m.EF(x)
    r = pattern_search(m, m.fresh, [("state", ycond), ("step", Pm)],
                       allowed=lambda ph, k: ph == 0 or k != EVID)
    res["REVAL-a"] = exist_result(m, r, ["start_state", "PromEvent"])
    r = pattern_search(m, m.fresh, [("state", ycond), ("step", Pm)])
    res["REVAL-b"] = exist_result(m, r, ["start_state", "PromEvent"])

    # REP (chain3 only)
    if inst["name"] == "chain3":
        n1 = inst["elems"].index("n1")
        u_n2 = 1 << inst["elems"].index("n2")
        src = [i for i in m.fresh if m.states[i][2] == n1 and m.states[i][3] == 0
               and m.bars[m.states[i][4]] == u_n2]
        o2, p2 = bfs(m, src, kind_ok=lambda k: k in (GOV, WORK),
                     edge_ok=lambda x, k, y: m.states[x][4] == m.states[y][4])
        t = [x for x in o2 if m.states[x][6] == 1]
        if t:
            path, kinds = path_to(p2, t[0])
            res["REP"] = {"class": "REACHABLE", "witness": m.traj(path, kinds)}
        else:
            res["REP"] = {"class": "NOT_REACHABLE", "witness": None}
    else:
        res["REP"] = {"class": "N/A", "witness": None}

    # P-GUARD
    src = [i for i in m.fresh if m.states[i][2] == m.bot and not m.EB(i)]
    o2, p2 = bfs(m, src, kind_ok=lambda k: k != EVID)
    t = [x for x in o2 if m.states[x][6] == 1]
    if not src:
        res["P-GUARD"] = {"class": "VACUOUS", "witness": None}
    elif t:
        path, kinds = path_to(p2, t[0])
        res["P-GUARD"] = {"class": "FAILS", "witness": m.traj(path, kinds)}
    else:
        res["P-GUARD"] = {"class": "HOLDS", "witness": None}

    # D1
    src = [i for i in range(len(m.states)) if not m.EB(i)]
    o2, p2 = bfs(m, src, kind_ok=lambda k: k == GOV)
    t = [x for x in o2 if m.EB(x)]
    res["D1"] = ({"class": "FAILS", "witness": m.traj(*path_to(p2, t[0]))} if t else
                 {"class": "HOLDS" if src else "VACUOUS", "witness": None})
    # D2
    src = [i for i in range(len(m.states)) if m.states[i][3] == 0]
    o2, p2 = bfs(m, src, kind_ok=lambda k: k in (EVID, EVIDREF, WORK))
    t = [x for x in o2 if m.states[x][3] == 1]
    res["D2"] = ({"class": "FAILS", "witness": m.traj(*path_to(p2, t[0]))} if t else
                 {"class": "HOLDS" if src else "VACUOUS", "witness": None})
    # D6
    res["D6"] = {"class": "HOLDS", "witness": None}
    for x in range(len(m.states)):
        hit = next(((k, y) for (k, y) in m.adj[x] if m.states[y][0] != m.states[x][0]), None)
        if hit:
            res["D6"] = {"class": "FAILS", "witness": m.traj([x, hit[1]], [hit[0]])}
            break

    if not with_witness:
        for key in PROP_ORDER:
            w = res[key]["witness"]
            res[key] = {"class": res[key]["class"],
                        "witness_length": None if w is None else w["length"]}
    return res


def exist_result(m, r, labels):
    if r is None:
        return {"class": "NOT_REACHABLE", "witness": None}
    w = m.traj(r["path"], r["kinds"])
    w["pattern_positions"] = {labels[ph]: {"type": t, "index": i}
                              for ph, (t, i) in sorted(r["marks"].items())}
    return {"class": "REACHABLE", "witness": w}


def holds(cls):
    return cls in ("HOLDS", "VACUOUS")


# ---------------------------------------------------------------- scenarios
def scenarios(inst, added):
    assert inst["name"] == "chain3"
    el = inst["elems"]
    n0, n1, n2 = el.index("n0"), el.index("n1"), el.index("n2")
    full = set(BASE_AXIOMS) | set(added)
    out = {}
    specs = {
        "T1": (n1, full, lambda m: [("step", lambda x, k, y: is_auth(m, x, y)),
                                    ("step", lambda x, k, y: is_prom(m, x, y))],
               lambda ph, k: k in (GOV, WORK), None),
        "T2": (n1, full, lambda m: [("step", lambda x, k, y: is_auth(m, x, y)),
                                    ("step", lambda x, k, y: k == EVID and m.states[x][2] != m.states[y][2]
                                     and inst["leq"][m.states[x][2]][m.states[y][2]]),
                                    ("step", lambda x, k, y: is_prom(m, x, y))], None, None),
        "T3": (n2, full, lambda m: [("step", lambda x, k, y: is_auth(m, x, y)),
                                    ("step", lambda x, k, y: k == EVIDREF and m.states[y][2] == n1),
                                    ("step", lambda x, k, y: is_prom(m, x, y))], None, None),
        "T4": (n1, full, lambda m: [("step", lambda x, k, y: is_auth(m, x, y)),
                                    ("step", lambda x, k, y: k == EVIDREF and m.states[y][2] == n0),
                                    ("step", lambda x, k, y: is_prom(m, x, y))], None, None),
        "T5": (n1, full - {"A6"}, lambda m: [("step", lambda x, k, y: is_auth(m, x, y)),
                                             ("step", lambda x, k, y: m.bars[m.states[x][4]] != m.bars[m.states[y][4]]),
                                             ("step", lambda x, k, y: is_prom(m, x, y))], None, None),
        "T6": (n1, full, lambda m: [("step", lambda x, k, y: is_auth(m, x, y)),
                                    ("step", lambda x, k, y: k == EVID and m.EB(y)),
                                    ("step", lambda x, k, y: is_prom(m, x, y))], None,
               "no_prom_before_mid"),
    }
    for tid, (e0, sigma, pat_f, allowed, forb) in specs.items():
        m = get_model(inst, sigma)
        u_n2 = 1 << n2
        src = [i for i in m.fresh if m.states[i][2] == e0 and m.states[i][3] == 0
               and m.bars[m.states[i][4]] == u_n2]
        pat = pat_f(m)
        fb = None
        if forb == "no_prom_before_mid":
            fb = lambda ph, x, k, y, m=m: ph <= 1 and is_prom(m, x, y)
        entry = {"axioms_removed": sorted(set(BASE_AXIOMS) | set(added) - sigma)}
        for variant, same in (("strict_order", False), ("same_step_allowed", True)):
            r = pattern_search(m, src, pat, allowed=allowed, forbidden=fb, same_step=same)
            if r is None:
                entry[variant] = {"exists": False}
                continue
            w = m.traj(r["path"], r["kinds"])
            ia = r["marks"][0][1]
            ip = r["marks"][len(pat) - 1][1]
            ev = {}
            for lab, si in (("t_a", ia), ("t_p", ip)):
                x = r["path"][si]
                st = m.fmt(x)
                ev[lab] = {"step_index": si, "e": st["e"], "u": st["u"],
                           "E0": m.E0(x), "EB": m.EB(x), "EF": m.EF(x)}
            if len(pat) == 3:
                ev["mid_step_index"] = r["marks"][1][1]
            entry[variant] = {"exists": True, "witness": w, "events": ev}
        out[tid] = entry
    return out


# ---------------------------------------------------------------- driver
_MODEL_CACHE = {}
_EVAL_CACHE = {}


def get_model(inst, sigma):
    key = (inst["name"], frozenset(sigma))
    if key not in _MODEL_CACHE:
        _MODEL_CACHE[key] = Model(inst, sigma)
    return _MODEL_CACHE[key]


def get_eval(inst, sigma):
    key = (inst["name"], frozenset(sigma))
    if key not in _EVAL_CACHE:
        m = Model(inst, sigma)
        _EVAL_CACHE[key] = evaluate(m, with_witness=True)
    return _EVAL_CACHE[key]


def strip(res):
    out = {"counts": res["_counts"]}
    for p in PROP_ORDER:
        w = res[p]["witness"]
        out[p] = {"class": res[p]["class"], "witness_length": None if w is None else w["length"]}
    return out


def main():
    results = {"spec": "SPEC-G25-r1", "kinds": KINDS, "base_axioms": BASE_AXIOMS,
               "property_order": PROP_ORDER, "models": {}}
    for mname, added in MODELS.items():
        results["models"][mname] = {"added_axioms": added, "instances": {}}
        for inst in INSTANCES:
            sys.stderr.write("%s %s\n" % (mname, inst["name"]))
            full = set(BASE_AXIOMS) | set(added)
            res = get_eval(inst, full)
            entry = {"counts": res["_counts"],
                     "properties": {p: res[p] for p in PROP_ORDER}}
            if inst["name"] == "chain3":
                entry["scenarios"] = scenarios(inst, added)
                _MODEL_CACHE.clear()
            # ablation
            abl = {}
            for ax in BASE_AXIOMS + added:
                abl[ax] = strip(get_eval(inst, full - {ax}))
            entry["ablation"] = abl
            # minimal sets
            subsets = [frozenset(c) for r in range(len(added) + 1)
                       for c in combinations(added, r)]
            sub_res = {T: get_eval(inst, set(BASE_AXIOMS) | T) for T in subsets}
            mins = {}
            mins_exist = {}
            for p in PROP_ORDER:
                cls = res[p]["class"]
                if p in UNIVERSAL and holds(cls):
                    good = [T for T in subsets if holds(sub_res[T][p]["class"])]
                elif p in EXISTENTIAL and cls == "NOT_REACHABLE":
                    good = [T for T in subsets if sub_res[T][p]["class"] == "NOT_REACHABLE"]
                else:
                    continue
                minimal = [T for T in good if not any(S < T for S in good)]
                lst = sorted([sorted(T, key=added.index) for T in minimal],
                             key=lambda l: (len(l), [added.index(a) for a in l]))
                (mins if p in UNIVERSAL else mins_exist)[p] = lst
            entry["minimal_sets"] = mins
            entry["minimal_sets_existential_not_reachable_supplementary"] = mins_exist
            used = set(a for l in mins.values() for T in l for a in T)
            entry["redundant_added_axioms"] = [a for a in added if a not in used]
            used2 = used | set(a for l in mins_exist.values() for T in l for a in T)
            entry["redundant_added_axioms_including_supplementary"] = [
                a for a in added if a not in used2]
            results["models"][mname]["instances"][inst["name"]] = entry
    with open(sys.argv[1] if len(sys.argv) > 1 else "results.json", "w") as fh:
        json.dump(results, fh, indent=1, sort_keys=False)
        fh.write("\n")


if __name__ == "__main__":
    main()
