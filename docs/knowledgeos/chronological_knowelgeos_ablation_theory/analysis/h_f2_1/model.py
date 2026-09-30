"""H-F2-1 finite-state model: exhaustive verification and axiom ablation.

Analysis instrument for prompts/KNOWLEDGEOS-H-F2-1-FORMAL-LOGIC-MINIMALITY-ATTACK.md.
No corpus input. Deterministic. Standard library only.

State x = (p, s, e, g, u):
  p in P = {gen, der}            provenance
  s in S = {auth, prov, hist}    standing
  e in E                         evidential position in a finite poset (E, <=)
  g in {0, 1}                    grant present (abstraction of the append-only act log)
  u subset of E                  the promotion bar currently in force (state, so that
                                 bar mutability can be ablated; A6 fixes it)
Kinds of atomic step: GOV, EVID (non-refutation), EVIDREF (refutation), WORK, COMP.

Every proposition is universal over trajectories and every axiom only removes steps or bars,
so propositions are monotone in the axiom set: checking the MAXIMAL permitted step relation is
exact, single-removal failure identifies necessary axioms, and if the necessary set suffices it
is the unique minimal set.
"""
from __future__ import annotations

import itertools
import json
import sys
from collections import deque

P = ("gen", "der")
S = ("auth", "prov", "hist")
KINDS = ("GOV", "EVID", "EVIDREF", "WORK", "COMP")
EVID_KINDS = {"EVID", "EVIDREF"}

AXIOMS = ("A0", "A1", "A2e", "A3g", "A3s", "A3m", "A4", "A5e", "A5g", "A5s", "A6")
AXIOM_TEXT = {
    "A0": "admissible bars are up-sets of (E,<=)",
    "A1": "every step keeps provenance: p'=p",
    "A2e": "GOV steps keep evidential position: e'=e",
    "A3g": "EVID/EVIDREF steps keep the grant: g'=g",
    "A3s": "EVID/EVIDREF steps keep standing: s'=s",
    "A3m": "EVID steps are monotone (e<=e'); EVIDREF steps only decrease (e'<=e)",
    "A4": "no atomic COMPOSITE step (composites are sequences of atomic steps)",
    "A5e": "WORK steps keep e", "A5g": "WORK steps keep g", "A5s": "WORK steps keep s",
    "A6": "the bar is constant: u'=u",
}


def posets():
    """Return {name: (elements, leq)} for the tested evidential orders."""
    def closure(elems, pairs):
        leq = {(a, a) for a in elems} | set(pairs)
        changed = True
        while changed:
            changed = False
            for (a, b), (c, d) in itertools.product(list(leq), repeat=2):
                if b == c and (a, d) not in leq:
                    leq.add((a, d)); changed = True
        return leq
    return {
        "chain3": (("n0", "n1", "n2"), closure(("n0", "n1", "n2"), {("n0", "n1"), ("n1", "n2")})),
        "V": (("bot", "a", "b"), closure(("bot", "a", "b"), {("bot", "a"), ("bot", "b")})),
        "diamond": (("bot", "a", "b", "top"),
                    closure(("bot", "a", "b", "top"), {("bot", "a"), ("bot", "b"), ("a", "top"), ("b", "top")})),
        "antichain2": (("a", "b"), closure(("a", "b"), set())),
    }


def all_bars(elems):
    return [frozenset(c) for r in range(len(elems) + 1) for c in itertools.combinations(elems, r)]


def is_upset(u, leq):
    return all(b in u for a in u for (x, b) in leq if x == a)


class Model:
    def __init__(self, elems, leq, active):
        self.elems, self.leq, self.active = elems, leq, set(active)
        self.bars = all_bars(elems)
        self.family = [u for u in self.bars if ("A0" not in self.active) or is_upset(u, leq)]
        self._famset = set(self.family)
        self.states = [(p, s, e, g, u) for p in P for s in S for e in elems for g in (0, 1) for u in self.bars]
        self._succ = {}

    def allowed(self, x, k, y):
        a = self.active
        p, s, e, g, u = x
        p2, s2, e2, g2, u2 = y
        if "A1" in a and p2 != p: return False
        if "A6" in a and u2 != u: return False
        # v1.1 (2026-09-26): A0 restricts EVERY state's bar, not only start states. In v1.0 a
        # bar-changing step (A6 removed) could reach a non-up-set bar; conclusions were unaffected,
        # but the reported -A6 countermodels were inadmissible under A0.
        if "A0" in a and u2 not in self._famset: return False
        if k == "COMP" and "A4" in a: return False
        if k == "GOV" and "A2e" in a and e2 != e: return False
        if k in EVID_KINDS:
            if "A3g" in a and g2 != g: return False
            if "A3s" in a and s2 != s: return False
            if "A3m" in a:
                if k == "EVID" and (e, e2) not in self.leq: return False
                if k == "EVIDREF" and (e2, e) not in self.leq: return False
        if k == "WORK":
            if "A5e" in a and e2 != e: return False
            if "A5g" in a and g2 != g: return False
            if "A5s" in a and s2 != s: return False
        return True

    def succ(self, x, kinds):
        key = (x, kinds)
        if key not in self._succ:
            self._succ[key] = [(k, y) for k in kinds for y in self.states if self.allowed(x, k, y)]
        return self._succ[key]


def promote(x):
    _, _, e, g, u = x
    return e in u and g == 1


def bfs_find(m, starts, kinds, target, flags_ok=None):
    """Shortest path from any start to a state satisfying target.
    If flags_ok is given, track (hasEVIDup, hasEVIDany, hasGOV) and require that the
    reached target violates flags_ok(flags). Returns a path (list of (kind, state)) or None."""
    track = flags_ok is not None
    seen = set()
    q = deque()
    for x0 in starts:
        f0 = (False, False, False) if track else None
        seen.add((x0, f0)); q.append((x0, f0, [("start", x0)]))
    while q:
        x, f, path = q.popleft()
        if len(path) > 1 and target(x) and (not track or not flags_ok(f)):
            return path
        for k, y in m.succ(x, kinds):
            nf = None
            if track:
                nf = (f[0] or k == "EVID", f[1] or k in EVID_KINDS, f[2] or k == "GOV")
            if (y, nf) not in seen:
                seen.add((y, nf)); q.append((y, nf, path + [(k, y)]))
    return None


def fmt(path):
    def st(x):
        p, s, e, g, u = x
        return f"(p={p},s={s},e={e},g={g},U={{{','.join(sorted(u))}}})"
    return " -> ".join(st(x) if k == "start" else f"[{k}] {st(x)}" for k, x in path)


def check(m):
    """Return {prop: (holds, witness_or_None)}."""
    res = {}
    fam = set(m.family)
    below = [x for x in m.states if x[4] in fam and x[2] not in x[4]]
    # D1: from e not in the (current) bar, GOV-only sequences never reach e in bar.
    w = bfs_find(m, below, ("GOV",), lambda y: y[2] in y[4])
    res["D1"] = (w is None, w)
    # D2: from g=0, EVID/EVIDREF/WORK-only sequences never reach g=1.
    w = bfs_find(m, [x for x in m.states if x[4] in fam and x[3] == 0], ("EVID", "EVIDREF", "WORK"),
                 lambda y: y[3] == 1)
    res["D2"] = (w is None, w)
    starts = [x for x in below if x[3] == 0]
    # D3: every trajectory reaching promotion contains >=1 evidential step and >=1 GOV step.
    w = bfs_find(m, starts, KINDS, promote, flags_ok=lambda f: f[1] and f[2])
    res["D3"] = (w is None, w)
    # D3+: ... and the evidential step includes a NON-refutation (earned) step.
    w = bfs_find(m, starts, KINDS, promote, flags_ok=lambda f: f[0] and f[2])
    res["D3+"] = (w is None, w)
    # D5: promotion is stable under non-refutation evidential steps.
    bad = None
    for x in m.states:
        if x[4] in fam and promote(x):
            for k, y in m.succ(x, ("EVID",)):
                if not promote(y):
                    bad = [("start", x), (k, y)]; break
        if bad: break
    res["D5"] = (bad is None, bad)
    # D6: provenance constant along every trajectory (single-step suffices).
    bad = None
    for x in m.states:
        for k, y in m.succ(x, KINDS):
            if y[0] != x[0]:
                bad = [("start", x), (k, y)]; break
        if bad: break
    res["D6"] = (bad is None, bad)
    # Non-vacuity: promotion reachable from a below-bar, ungranted state.
    w = bfs_find(m, starts, KINDS, promote)
    res["NONVACUOUS"] = (w is not None, w)
    return res


def d4():
    """Count injective maps P x S -> P disjoint-union S (the single-field encoding)."""
    dom = list(itertools.product(P, S))
    cod = list(P) + list(S)
    total = injective = 0
    for f in itertools.product(cod, repeat=len(dom)):
        total += 1
        if len(set(f)) == len(dom):
            injective += 1
    return {"domain_size": len(dom), "codomain_size": len(cod), "maps": total, "injective": injective}


def main():
    out = {"axioms": AXIOM_TEXT, "posets": {}, "d4": d4()}
    props = ("D1", "D2", "D3", "D3+", "D5", "D6", "NONVACUOUS")
    for name, (elems, leq) in posets().items():
        rec = {"elements": list(elems),
               "leq": sorted([list(t) for t in leq if t[0] != t[1]]),
               "state_count": len(Model(elems, leq, AXIOMS).states)}
        full = check(Model(elems, leq, AXIOMS))
        rec["full"] = {k: v[0] for k, v in full.items()}
        rec["full_witness_nonvacuous"] = fmt(full["NONVACUOUS"][1]) if full["NONVACUOUS"][1] else None
        abl = {}
        for ax in AXIOMS:
            r = check(Model(elems, leq, [a for a in AXIOMS if a != ax]))
            abl[ax] = {k: {"holds": v[0], "countermodel": (fmt(v[1]) if (v[1] and k != "NONVACUOUS" and not v[0]) else None)}
                       for k, v in r.items()}
        rec["ablation"] = abl
        minimal = {}
        for prop in props[:-1]:
            if not full[prop][0]:
                minimal[prop] = {"necessary": None, "sufficient": False, "note": "fails under full axioms"}
                continue
            nec = [ax for ax in AXIOMS if not abl[ax][prop]["holds"]]
            ok = check(Model(elems, leq, nec))[prop][0]
            minimal[prop] = {"necessary": nec, "necessary_set_suffices": ok}
        rec["minimal"] = minimal
        out["posets"][name] = rec
        print(f"[{name}] states={rec['state_count']} full={rec['full']}", file=sys.stderr)
    json.dump(out, sys.stdout, indent=1, ensure_ascii=False)


if __name__ == "__main__":
    main()
