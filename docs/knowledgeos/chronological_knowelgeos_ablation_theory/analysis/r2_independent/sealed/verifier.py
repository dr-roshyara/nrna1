#!/usr/bin/env python3
# verifier.py -- independent verifier for SPEC.md (H-F2-1-R)
# Standard library only. Deterministic. No sampling.
#
# Reads nothing except its own definitions. Writes results.json.
# Run:  python verifier.py
# The runtime and results.json are produced by the operator's machine.

import json
import itertools
import time
from collections import deque

# ----------------------------------------------------------------------
# Axiom identifiers and step kinds
# ----------------------------------------------------------------------
AXIOMS = ["A0", "A1", "A2e", "A3g", "A3s", "A3m",
          "A4", "A5e", "A5g", "A5s", "A6"]
KINDS = ["GOV", "EVID", "EVIDREF", "WORK", "COMP"]

# ----------------------------------------------------------------------
# Posets (SPEC.md "Instances" table; strict relations only, closure added)
# ----------------------------------------------------------------------
def poset(name):
    if name == "chain3":
        E = ["n0", "n1", "n2"]
        strict = [("n0", "n1"), ("n0", "n2"), ("n1", "n2")]
    elif name == "V":
        E = ["bot", "a", "b"]
        strict = [("bot", "a"), ("bot", "b")]
    elif name == "diamond":
        E = ["bot", "a", "b", "top"]
        strict = [("bot", "a"), ("bot", "b"), ("a", "top"), ("b", "top")]
    elif name == "antichain2":
        E = ["a", "b"]
        strict = []
    else:
        raise ValueError(name)
    # reflexive-transitive closure
    leq = {(x, x) for x in E}
    for (x, y) in strict:
        leq.add((x, y))
    # transitive closure (posets above are small; iterate to fixpoint)
    changed = True
    while changed:
        changed = False
        for (a, b) in list(leq):
            for (c, d) in list(leq):
                if b == c and (a, d) not in leq:
                    leq.add((a, d)); changed = True
    return E, leq

def is_upset(u, E, leq):
    for a in u:
        for b in E:
            if (a, b) in leq and b not in u:
                return False
    return True

def all_bars(E, leq, use_A0):
    n = len(E)
    out = []
    for mask in range(1 << n):
        u = frozenset(E[i] for i in range(n) if (mask >> i) & 1)
        if use_A0 and not is_upset(u, E, leq):
            continue
        out.append(u)
    return out

def state_space(E, leq, use_A0):
    B = all_bars(E, leq, use_A0)
    return [(p, s, e, g, u)
            for p in ("gen", "der")
            for s in ("auth", "prov", "hist")
            for e in E
            for g in (0, 1)
            for u in B], B

# ----------------------------------------------------------------------
# Maximal admissible step relation R_max.
# succ(x, k, S, ...) = all x' with x --k--> x' satisfying every axiom in S.
# "A step not restricted by an axiom in force may change any component."
# ----------------------------------------------------------------------
def succ(x, k, S, E, leq, B):
    p, s, e, g, u = x
    A1  = "A1"  in S
    A2e = "A2e" in S
    A3g = "A3g" in S
    A3s = "A3s" in S
    A3m = "A3m" in S
    A4  = "A4"  in S
    A5e = "A5e" in S
    A5g = "A5g" in S
    A5s = "A5s" in S
    A6  = "A6"  in S

    # A4: no COMP steps at all
    if k == "COMP" and A4:
        return []

    # p' domain
    p_dom = (p,) if A1 else ("gen", "der")

    # s' and g' domains by kind
    if k in ("EVID", "EVIDREF"):
        s_dom = (s,) if A3s else ("auth", "prov", "hist")
        g_dom = (g,) if A3g else (0, 1)
    elif k == "WORK":
        s_dom = (s,) if A5s else ("auth", "prov", "hist")
        g_dom = (g,) if A5g else (0, 1)
    else:  # GOV, COMP: neither A3g nor A5g speaks here
        s_dom = ("auth", "prov", "hist")
        g_dom = (0, 1)

    out = []
    for pp in p_dom:
        for ss in s_dom:
            for e2 in E:
                if k == "GOV"     and A2e and e2 != e: continue
                if k == "EVID"    and A3m and (e, e2) not in leq: continue
                if k == "EVIDREF" and A3m and (e2, e) not in leq: continue
                if k == "WORK"    and A5e and e2 != e: continue
                for g2 in g_dom:
                    for u2 in B:
                        if A6 and u2 != u: continue
                        out.append((pp, ss, e2, g2, u2))
    return out

def promote(x):
    _, _, e, g, u = x
    return (e in u) and (g == 1)

def d(x):
    p, s, e, g, u = x
    return {"p": p, "s": s, "e": e, "g": g, "u": sorted(u)}

def trace_from(vis, node):
    """Reconstruct shortest path from any start to node."""
    path = []
    cur = node
    while vis[cur] is not None:
        pr, k = vis[cur]
        path.append((pr, k, cur))
        cur = pr
    path.reverse()
    return [{"from": d(a), "kind": k, "to": d(b)} for a, k, b in path]

# ----------------------------------------------------------------------
# D1: no GOV-only trajectory from e (not in u) to e (in u).
# ----------------------------------------------------------------------
def check_D1(E, leq, S):
    use_A0 = "A0" in S
    states, B = state_space(E, leq, use_A0)
    starts = [x for x in states if x[2] not in x[4]]
    if not starts:
        return True, None
    vis = {x: None for x in starts}
    q = deque(starts)
    while q:
        x = q.popleft()
        for y in succ(x, "GOV", S, E, leq, B):
            if y in vis: continue
            vis[y] = (x, "GOV")
            if y[2] in y[4]:
                return False, trace_from(vis, y)
            q.append(y)
    return True, None

# ----------------------------------------------------------------------
# D2: no {EVID,EVIDREF,WORK}-only trajectory from g=0 to g=1.
# ----------------------------------------------------------------------
def check_D2(E, leq, S):
    use_A0 = "A0" in S
    states, B = state_space(E, leq, use_A0)
    kinds = ("EVID", "EVIDREF", "WORK")
    starts = [x for x in states if x[3] == 0]
    if not starts:
        return True, None
    vis = {x: None for x in starts}
    q = deque(starts)
    while q:
        x = q.popleft()
        for k in kinds:
            for y in succ(x, k, S, E, leq, B):
                if y in vis: continue
                vis[y] = (x, k)
                if y[3] == 1:
                    return False, trace_from(vis, y)
                q.append(y)
    return True, None

# ----------------------------------------------------------------------
# D3 / D3+: every trajectory from (e not in u, g=0) to Promote contains
# >=1 EVID or EVIDREF step  AND  >=1 GOV step.
# D3+ additionally requires >=1 EVID (non-refutation) step.
# A failure is witnessed by a trajectory to Promote that avoids one of
# the required kinds entirely; we BFS each avoiding-kind-set separately
# and return the shortest such trajectory found.
# ----------------------------------------------------------------------
def check_D3(E, leq, S, plus):
    use_A0 = "A0" in S
    states, B = state_space(E, leq, use_A0)
    if not plus:
        avoid_sets = [
            ("no_EVIDREF", [k for k in KINDS if k not in ("EVID", "EVIDREF")]),
            ("no_GOV",     [k for k in KINDS if k != "GOV"]),
        ]
    else:
        avoid_sets = [
            ("no_EVID", [k for k in KINDS if k != "EVID"]),
            ("no_GOV",  [k for k in KINDS if k != "GOV"]),
        ]
    best = None
    best_len = 10 ** 9
    for _, ks in avoid_sets:
        starts = [x for x in states if x[2] not in x[4] and x[3] == 0]
        if not starts:
            continue
        vis = {x: None for x in starts}
        q = deque(starts)
        while q:
            x = q.popleft()
            for k in ks:
                for y in succ(x, k, S, E, leq, B):
                    if y in vis: continue
                    vis[y] = (x, k)
                    if promote(y):
                        tr = trace_from(vis, y)
                        if len(tr) < best_len:
                            best_len = len(tr); best = tr
                        # shortest for this avoid-set found; move to next
                        q.clear(); break
                    q.append(y)
                if not q and best is not None and len(best) == best_len:
                    # continue outer loop without reusing q
                    pass
        # (each avoid-set gets its own BFS)
    return (False, best) if best is not None else (True, None)

# ----------------------------------------------------------------------
# D5: every EVID step from a Promote state lands in a Promote state.
# ----------------------------------------------------------------------
def check_D5(E, leq, S):
    use_A0 = "A0" in S
    states, B = state_space(E, leq, use_A0)
    for x in states:
        if not promote(x):
            continue
        for y in succ(x, "EVID", S, E, leq, B):
            if not promote(y):
                return False, [{"from": d(x), "kind": "EVID", "to": d(y)}]
    return True, None

# ----------------------------------------------------------------------
# D6: every trajectory keeps p constant.
# ----------------------------------------------------------------------
def check_D6(E, leq, S):
    use_A0 = "A0" in S
    states, B = state_space(E, leq, use_A0)
    for x in states:
        for k in KINDS:
            for y in succ(x, k, S, E, leq, B):
                if y[0] != x[0]:
                    return False, [{"from": d(x), "kind": k, "to": d(y)}]
    return True, None

# ----------------------------------------------------------------------
# NV: there exists R and a trajectory from (e not in u, g=0) to Promote.
# ----------------------------------------------------------------------
def check_NV(E, leq, S):
    use_A0 = "A0" in S
    states, B = state_space(E, leq, use_A0)
    starts = [x for x in states if x[2] not in x[4] and x[3] == 0]
    if not starts:
        return False, None
    vis = {x: None for x in starts}
    q = deque(starts)
    while q:
        x = q.popleft()
        for k in KINDS:
            for y in succ(x, k, S, E, leq, B):
                if y in vis: continue
                vis[y] = (x, k)
                if promote(y):
                    return True, None
                q.append(y)
    return False, None

CHECKS = {
    "D1":  check_D1,
    "D2":  check_D2,
    "D3":  lambda E, l, S: check_D3(E, l, S, False),
    "D3+": lambda E, l, S: check_D3(E, l, S, True),
    "D5":  check_D5,
    "D6":  check_D6,
    "NV":  check_NV,
}

# ----------------------------------------------------------------------
# Minimal inclusion-minimal subsets, brute force over all 2^11 subsets.
# ----------------------------------------------------------------------
def minimal_sets(E, leq, prop):
    fn = CHECKS[prop]
    holding = []
    for r in range(len(AXIOMS) + 1):
        for sub in itertools.combinations(AXIOMS, r):
            S = set(sub)
            if fn(E, leq, S)[0]:
                holding.append(frozenset(sub))
    result = []
    for s in holding:
        if not any(t < s for t in holding):
            result.append(sorted(s))
    return result

# ----------------------------------------------------------------------
# Main driver
# ----------------------------------------------------------------------
def main():
    t0 = time.time()
    out = {}
    for inst in ("chain3", "V", "diamond", "antichain2"):
        E, leq = poset(inst)
        n = len(E)
        bars_with    = all_bars(E, leq, True)
        bars_without = all_bars(E, leq, False)
        count_with    = 2 * 3 * n * 2 * len(bars_with)
        count_without = 2 * 3 * n * 2 * len(bars_without)

        full_S = set(AXIOMS)
        full = {p: CHECKS[p](E, leq, full_S)[0]
                for p in ("D1", "D2", "D3", "D3+", "D5", "D6", "NV")}

        single_removal = {}
        for a in AXIOMS:
            S = set(AXIOMS) - {a}
            single_removal[a] = {
                p: {"holds": CHECKS[p](E, leq, S)[0],
                    "countermodel": CHECKS[p](E, leq, S)[1]}
                for p in ("D1", "D2", "D3", "D3+", "D5", "D6", "NV")
            }

        minimal = {p: minimal_sets(E, leq, p)
                   for p in ("D1", "D2", "D3", "D3+", "D5", "D6")}

        out[inst] = {
            "state_count_with_A0":    count_with,
            "state_count_without_A0": count_without,
            "full":            full,
            "single_removal":  single_removal,
            "minimal_sets":    minimal,
        }

    with open("results.json", "w") as f:
        json.dump(out, f, indent=2, sort_keys=False)

    dt = time.time() - t0
    print("Runtime: %.3f seconds" % dt)
    print("Wrote results.json")

if __name__ == "__main__":
    main()
