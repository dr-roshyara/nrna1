#!/usr/bin/env python3
"""H-F2-1-R independent verifier (implemented from SPEC.md only).

Method: explicit finite-state enumeration of the MAXIMAL step relation R_max(Sigma)
(every atomic step x ->k x' between admissible states that satisfies every axiom in
Sigma), with bitset reachability. Every proposition D1..D6 is a universal statement of
the form "no (finite, chained) trajectory / step in R has property Bad"; since every
R satisfying Sigma is a subset of R_max, and any finite trajectory of R_max is itself a
trajectory of the R consisting of exactly its steps (which satisfies Sigma), the
proposition holds for every R iff it holds for R_max. NV is existential and is likewise
decided on R_max (witness R = the steps of the witnessing trajectory).

Standard library only; deterministic (all iteration in fixed index order).
"""
import itertools
import json
import os
import sys

AXIOMS = ["A0", "A1", "A2e", "A3g", "A3s", "A3m", "A4", "A5e", "A5g", "A5s", "A6"]
KINDS = ["GOV", "EVID", "EVIDREF", "WORK", "COMP"]
P = ["gen", "der"]
S = ["auth", "prov", "hist"]
G = [0, 1]

INSTANCES = {
    "chain3": (["n0", "n1", "n2"], [("n0", "n1"), ("n1", "n2")]),
    "V": (["bot", "a", "b"], [("bot", "a"), ("bot", "b")]),
    "diamond": (["bot", "a", "b", "top"], [("bot", "a"), ("a", "top"), ("bot", "b"), ("b", "top")]),
    "antichain2": (["a", "b"], []),
}

PROPS = ["D1", "D2", "D3", "D3+", "D5", "D6", "NV"]
UNIVERSAL = ["D1", "D2", "D3", "D3+", "D5", "D6"]


def leq_closure(E, strict):
    n = len(E)
    idx = {x: i for i, x in enumerate(E)}
    le = [[i == j for j in range(n)] for i in range(n)]
    for a, b in strict:
        le[idx[a]][idx[b]] = True
    for k in range(n):
        for i in range(n):
            for j in range(n):
                if le[i][k] and le[k][j]:
                    le[i][j] = True
    return le


def bits(m):
    while m:
        low = m & -m
        yield low.bit_length() - 1
        m ^= low


class Instance:
    def __init__(self, name, E, strict):
        self.name = name
        self.E = E
        self.nE = len(E)
        self.le = leq_closure(E, strict)
        all_bars = list(range(1 << self.nE))  # bar as bitmask over E
        up = [u for u in all_bars if all(
            (not (u >> a & 1)) or (u >> b & 1)
            for a in range(self.nE) for b in range(self.nE) if self.le[a][b])]
        self.spaces = {}
        for a0 in (False, True):
            U = up if a0 else all_bars
            states = list(itertools.product(range(2), range(3), range(self.nE), range(2), range(len(U))))
            self.spaces[a0] = {"U": U, "states": states, "n": len(states)}
        self.cache = {}

    def idx(self, a0, p, s, e, g, ui):
        nU = len(self.spaces[a0]["U"])
        return (((p * 3 + s) * self.nE + e) * 2 + g) * nU + ui

    def state_repr(self, a0, i):
        p, s, e, g, ui = self.spaces[a0]["states"][i]
        u = self.spaces[a0]["U"][ui]
        return {"p": P[p], "s": S[s], "e": self.E[e], "g": g,
                "u": [self.E[k] for k in range(self.nE) if u >> k & 1]}

    # ---- component successor lists (per kind, per axiom set) ----
    def comp_key(self, kind, ax):
        """Axioms that influence this kind's relation (exact projection)."""
        rel = {"GOV": ["A0", "A1", "A2e", "A6"],
               "EVID": ["A0", "A1", "A3g", "A3s", "A3m", "A6"],
               "EVIDREF": ["A0", "A1", "A3g", "A3s", "A3m", "A6"],
               "WORK": ["A0", "A1", "A5e", "A5g", "A5s", "A6"],
               "COMP": ["A0", "A1", "A4", "A6"]}[kind]
        return (kind,) + tuple(a in ax for a in rel)

    def succ_masks(self, kind, ax):
        key = self.comp_key(kind, ax)
        if key in self.cache:
            return self.cache[key]
        a0 = "A0" in ax
        sp = self.spaces[a0]
        nU = len(sp["U"])
        res = [0] * sp["n"]
        if kind == "COMP" and "A4" in ax:
            self.cache[key] = res
            return res
        full_u = (1 << nU) - 1
        for i, (p, s, e, g, ui) in enumerate(sp["states"]):
            ps = [p] if "A1" in ax else range(2)
            if (kind in ("EVID", "EVIDREF") and "A3s" in ax) or (kind == "WORK" and "A5s" in ax):
                ss = [s]
            else:
                ss = range(3)
            if kind == "GOV" and "A2e" in ax:
                es = [e]
            elif kind == "EVID" and "A3m" in ax:
                es = [x for x in range(self.nE) if self.le[e][x]]
            elif kind == "EVIDREF" and "A3m" in ax:
                es = [x for x in range(self.nE) if self.le[x][e]]
            elif kind == "WORK" and "A5e" in ax:
                es = [e]
            else:
                es = range(self.nE)
            if (kind in ("EVID", "EVIDREF") and "A3g" in ax) or (kind == "WORK" and "A5g" in ax):
                gs = [g]
            else:
                gs = range(2)
            um = (1 << ui) if "A6" in ax else full_u
            m = 0
            for p2 in ps:
                for s2 in ss:
                    for e2 in es:
                        for g2 in gs:
                            m |= um << (((p2 * 3 + s2) * self.nE + e2) * 2 + g2) * nU
            res[i] = m
        self.cache[key] = res
        return res

    # ---- state predicates as masks ----
    def pred_mask(self, a0, f):
        sp = self.spaces[a0]
        m = 0
        for i, (p, s, e, g, ui) in enumerate(sp["states"]):
            u = sp["U"][ui]
            if f(p, s, e, g, (u >> e) & 1):
                m |= 1 << i
        return m

    def combined(self, ax, kinds):
        a0 = "A0" in ax
        n = self.spaces[a0]["n"]
        out = [0] * n
        for k in kinds:
            sm = self.succ_masks(k, ax)
            for i in range(n):
                out[i] |= sm[i]
        return out

    def reach(self, succ, src):
        r = src
        f = src
        while f:
            nf = 0
            for i in bits(f):
                nf |= succ[i]
            f = nf & ~r
            r |= f
        return r

    def shortest(self, ax, kinds, src, tgt):
        """Shortest chained trajectory from src-set to tgt-set using kinds; BFS, deterministic."""
        a0 = "A0" in ax
        if src & tgt:
            i = next(bits(src & tgt))
            return {"start": self.state_repr(a0, i), "steps": []}
        per = [(k, self.succ_masks(k, ax)) for k in kinds]
        parent = {}
        visited = src
        frontier = sorted(bits(src))
        while frontier:
            nxt = []
            for i in frontier:
                for k, sm in per:
                    new = sm[i] & ~visited
                    for j in bits(new):
                        parent[j] = (i, k)
                        visited |= 1 << j
                        nxt.append(j)
                        if tgt >> j & 1:
                            path = []
                            c = j
                            while c in parent:
                                pi, pk = parent[c]
                                path.append((pi, pk, c))
                                c = pi
                            path.reverse()
                            return {"start": self.state_repr(a0, path[0][0]),
                                    "steps": [{"kind": pk, "to": self.state_repr(a0, c2)} for _, pk, c2 in path]}
            frontier = sorted(nxt)
        return None

    # ---- propositions ----
    def evaluate(self, ax, want_cex=False):
        a0 = "A0" in ax
        n = self.spaces[a0]["n"]
        not_in = self.pred_mask(a0, lambda p, s, e, g, eu: not eu)
        in_u = self.pred_mask(a0, lambda p, s, e, g, eu: eu)
        g0 = self.pred_mask(a0, lambda p, s, e, g, eu: g == 0)
        g1 = self.pred_mask(a0, lambda p, s, e, g, eu: g == 1)
        prom = self.pred_mask(a0, lambda p, s, e, g, eu: eu and g == 1)
        start3 = not_in & g0
        res, cex = {}, {}

        def reach_check(name, kinds, src, tgt):
            key = (name, tuple(self.comp_key(k, ax) for k in kinds))
            if key in self.cache:
                ok = self.cache[key]
            else:
                ok = not (self.reach(self.combined(ax, kinds), src) & tgt)
                self.cache[key] = ok
            return ok

        # D1: GOV-only from e notin u to e in u
        res["D1"] = reach_check("D1", ["GOV"], not_in, in_u)
        if want_cex and not res["D1"]:
            cex["D1"] = self.shortest(ax, ["GOV"], not_in, in_u)
        # D2
        k2 = ["EVID", "EVIDREF", "WORK"]
        res["D2"] = reach_check("D2", k2, g0, g1)
        if want_cex and not res["D2"]:
            cex["D2"] = self.shortest(ax, k2, g0, g1)
        # D3 / D3+: fails iff Promote reachable from start3 avoiding all evidential kinds
        # (resp. avoiding EVID) OR avoiding GOV.
        for name, evid in (("D3", ["EVID", "EVIDREF"]), ("D3+", ["EVID"])):
            k_noevid = [k for k in KINDS if k not in evid]
            k_nogov = [k for k in KINDS if k != "GOV"]
            ok1 = reach_check(name + "a", k_noevid, start3, prom)
            ok2 = reach_check(name + "b", k_nogov, start3, prom)
            res[name] = ok1 and ok2
            if want_cex and not res[name]:
                cands = []
                if not ok1:
                    cands.append(self.shortest(ax, k_noevid, start3, prom))
                if not ok2:
                    cands.append(self.shortest(ax, k_nogov, start3, prom))
                cex[name] = min(cands, key=lambda t: len(t["steps"]))
        # D5: every EVID step from a Promote state lands in a Promote state
        sm = self.succ_masks("EVID", ax)
        bad5 = None
        for i in bits(prom):
            b = sm[i] & ~prom
            if b:
                bad5 = (i, next(bits(b)))
                break
        res["D5"] = bad5 is None
        if want_cex and bad5:
            cex["D5"] = {"start": self.state_repr(a0, bad5[0]),
                         "steps": [{"kind": "EVID", "to": self.state_repr(a0, bad5[1])}]}
        # D6: no step (any kind) changes p. Every state is a possible start, so this is
        # exactly "no step in R_max changes p".
        pm = [self.pred_mask(a0, lambda p, s, e, g, eu, q=q: p == q) for q in range(2)]
        bad6 = None
        for k in KINDS:
            skm = self.succ_masks(k, ax)
            for i in range(n):
                p = self.spaces[a0]["states"][i][0]
                b = skm[i] & ~pm[p]
                if b:
                    bad6 = (i, k, next(bits(b)))
                    break
            if bad6:
                break
        res["D6"] = bad6 is None
        if want_cex and bad6:
            cex["D6"] = {"start": self.state_repr(a0, bad6[0]),
                         "steps": [{"kind": bad6[1], "to": self.state_repr(a0, bad6[2])}]}
        # NV: exists trajectory (any kinds) from start3 to Promote
        allk = KINDS
        res["NV"] = bool(self.reach(self.combined(ax, allk), start3) & prom)
        if want_cex:
            if res["NV"]:
                cex["NV_witness"] = self.shortest(ax, allk, start3, prom)
            else:
                cex["NV"] = None  # existential failure: no trajectory countermodel exists
        return res, cex


def main():
    out = {"method": None, "interpretations": None, "instances": {}}
    full = frozenset(AXIOMS)
    for name, (E, strict) in INSTANCES.items():
        inst = Instance(name, E, strict)
        r = {"elements": E, "strict_order": strict,
             "state_count_full_axioms_A0": inst.spaces[True]["n"],
             "state_count_without_A0": inst.spaces[False]["n"],
             "admissible_bars_A0": [[E[k] for k in range(len(E)) if u >> k & 1] for u in inst.spaces[True]["U"]]}
        fr, fc = inst.evaluate(full, want_cex=True)
        r["full_axiom_set"] = fr
        r["full_axiom_set_NV_witness"] = fc.get("NV_witness")
        r["single_removal"] = {}
        for a in AXIOMS:
            ax = full - {a}
            rr, cc = inst.evaluate(ax, want_cex=True)
            r["single_removal"]["without_" + a] = {
                "truth": rr,
                "failing": [p for p in PROPS if not rr[p]],
                "shortest_countermodels": {p: cc.get(p) for p in PROPS if not rr[p]},
            }
        # all subsets
        table = {}
        for mask in range(1 << len(AXIOMS)):
            ax = frozenset(AXIOMS[i] for i in range(len(AXIOMS)) if mask >> i & 1)
            table[mask], _ = inst.evaluate(ax)
        minimal = {}
        monotone = {}
        for p in UNIVERSAL:
            holds = [m for m in range(1 << len(AXIOMS)) if table[m][p]]
            hs = set(holds)
            mins = [m for m in holds if not any((h & m) == h and h != m for h in holds)]
            minimal[p] = sorted([[AXIOMS[i] for i in range(len(AXIOMS)) if m >> i & 1] for m in mins],
                                key=lambda l: (len(l), [AXIOMS.index(x) for x in l]))
            # upward-closure check (reported, not assumed)
            monotone[p] = all((m | (1 << i)) in hs for m in holds for i in range(len(AXIOMS)))
        r["minimal_axiom_sets"] = minimal
        r["upward_closed_check"] = monotone
        r["NV_true_subset_count"] = sum(1 for m in table if table[m]["NV"])
        out["instances"][name] = r
    out["method"] = (
        "Explicit enumeration. For each instance and axiom set Sigma, the state space is "
        "P x S x E x G x U(Sigma) (U = up-sets iff A0 in Sigma). The maximal step relation "
        "R_max(Sigma) = all (x, kind, x') with x, x' admissible states and the step satisfying "
        "every axiom of Sigma (COMP steps absent iff A4 in Sigma) is built exactly as bitsets. "
        "Exactness: D1, D2, D3, D3+ are 'no finite chained trajectory of R with property Bad', "
        "D5, D6 are 'no step (resp. trajectory) of R with property Bad'. Any R satisfying Sigma is "
        "a subset of R_max, so a bad trajectory of R is one of R_max; conversely a bad trajectory "
        "of R_max is a bad trajectory of the relation R consisting of exactly its steps, which "
        "satisfies Sigma. Hence 'for every R' <=> 'for R_max', decided by reachability: "
        "D1 = no GOV-path from {e notin u} to {e in u}; D2 = no {EVID,EVIDREF,WORK}-path from "
        "{g=0} to {g=1}; D3 fails iff Promote is reachable from {e notin u, g=0} using only "
        "kinds without EVID/EVIDREF, or only kinds without GOV (a trajectory missing one class "
        "lies in one of these two sub-relations, and vice versa); D3+ likewise with 'without EVID'; "
        "D5 = every EVID edge of R_max from a Promote state ends in a Promote state; D6 = no edge "
        "of R_max changes p (every state is a start, so trajectories = edges for this purpose); "
        "NV = Promote reachable from {e notin u, g=0} in R_max (witness R = that trajectory's "
        "steps). Reachability is a fixed-point over a finite graph, so each decision is exact. "
        "Memoization keys use only the axioms that syntactically affect the kinds involved "
        "(an exact projection: the relation is identical). Minimal sets: brute force over all "
        "2^11 subsets; a set is minimal iff it holds and no proper subset holds (upward closure "
        "is checked and reported, not assumed). Shortest countermodels by BFS in fixed order."
    )
    out["interpretations"] = [
        "A trajectory is a finite chained sequence x0 ->k1 x1 ->k2 ... ; the empty trajectory is allowed (it never violates any proposition since start and target sets are disjoint).",
        "'From any state' ranges over ALL admissible states of the state space, not only states reachable from some initial state.",
        "A0 changes the state space itself: without A0 bars range over all 2^|E| subsets; with A0 only up-sets; steps must stay within admissible states.",
        "Each kind's step relation under Sigma is the product of per-component constraints stated by the axioms; components not constrained for that kind may change arbitrarily (including staying the same).",
        "COMP is a kind like any other when A4 is absent; it is neither GOV nor EVID/EVIDREF, so a COMP step does not count toward D3/D3+'s required step classes; with A4 there are no COMP steps.",
        "D3/D3+ quantify over trajectories starting in a state with e notin u and g = 0 and reaching a Promote state at any point (the first Promote hit suffices; prefixes are trajectories).",
        "D5 is read as a single-step property over every EVID step of R from any Promote state; D6 as 'no trajectory ever changes p', equivalently no step changes p.",
        "'State counts under the full axiom set' = |P|*|S|*|E|*|G|*|up-sets| (A0 in force); 'without A0' = same with all subsets. Other axioms constrain steps, not states.",
        "NV is existential; when NV fails no countermodel trajectory exists, so null is reported. When NV holds a shortest witness is reported. NV is not included in the minimal-set computation (the SPEC lists only D1..D6 there).",
        "Shortest countermodel = fewest atomic steps; ties broken deterministically by state-index BFS order (state index order: p, s, e, g, bar-index lexicographic; kind order GOV, EVID, EVIDREF, WORK, COMP).",
    ]
    out["files_read"] = ["docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/h_f2_1_verifier/SPEC.md"]
    path = os.path.join(os.path.dirname(os.path.abspath(__file__)), "verifier_results.json")
    with open(path, "w") as f:
        json.dump(out, f, indent=2)
    print("wrote", path)


if __name__ == "__main__":
    sys.exit(main())
