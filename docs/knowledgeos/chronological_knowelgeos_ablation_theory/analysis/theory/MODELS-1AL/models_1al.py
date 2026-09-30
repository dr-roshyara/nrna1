"""1al: competing formal models on the strict core (committed before its first run). Deterministic; no corpus read.
Data: the strict semantic observations of SEMOBS r4 (36 events; UNK never filled). Three analyses:
 (1) PER-OPERATION GUARD SIGNATURES. For operation o, a variable set V is CONSISTENT iff no two legality events of o with
     known, equal values on V have opposite outcomes; an event with an UNK on V can never create a conflict (open world).
     The minimal consistent sets (by inclusion) are the surviving guard signatures of o. Operations without both outcomes
     are UNCONSTRAINED (every set is consistent, including ∅).
 (2) UNTESTED DIMENSIONS. For each operation, an applicable variable that takes a single known value across its events never
     varied in the data: its effect is untested. Each is a template for a discriminating observation.
 (3) BISIMULATION of the ruling-status LTS (M3 sub-model B, imported read-only): partition refinement over reachable states
     with observations = enabled operation labels; reports the reachable vs the minimal (quotient) state count.
Usage: python3 models_1al.py [--selftest]"""
import importlib.util
import itertools
import json
import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__)); TH = os.path.dirname(HERE)


def load(name, path):
    s = importlib.util.spec_from_file_location(name, path); m = importlib.util.module_from_spec(s); s.loader.exec_module(m); return m


def r4_observations():
    # loader fix (disclosed): the marker also occurs inside semobs_r4's own source, so split at its LAST occurrence
    src = open(os.path.join(TH, "SEMOBS", "semobs_r4.py")).read().rsplit("st, po = sm.pairs(obs)", 1)[0]
    ns = {"__file__": os.path.join(TH, "SEMOBS", "semobs_r4.py")}; exec(compile(src, "semobs_r4.py", "exec"), ns)
    return ns["sm"], ns["obs"]


def conflict(evs, V, U):
    for p, q in itertools.combinations(evs, 2):
        if (p["outcome"] == "PERFORMED") == (q["outcome"] == "PERFORMED"): continue
        if all(p[v] == q[v] and p[v] != U for v in V): return (p["event_id"], q["event_id"])
    return None


def signatures(sm, obs):
    U = sm.U; out = {}
    L = [e for e in obs if e["ground"] != "CHOICE"]
    for o in sorted({e["o"] for e in L}):
        evs = [e for e in L if e["o"] == o]
        cand = sorted((set("r") | set(sm.APPL.get(o, ""))) - {"o"})
        both = len({e["outcome"] == "PERFORMED" for e in evs}) == 2
        res = {"events": len(evs), "both_outcomes": both, "applicable": cand}
        if not both:
            res["signatures"] = "UNCONSTRAINED (only one outcome observed)"
        else:
            cons = [set(c) for n in range(len(cand) + 1) for c in itertools.combinations(cand, n) if conflict(evs, c, U) is None]
            minimal = [sorted(c) for c in cons if not any(d < c for d in cons)]
            res["signatures"] = minimal
            res["empty_set_conflict"] = conflict(evs, (), U)
        res["untested"] = sorted(v for v in cand if len({e[v] for e in evs if e[v] not in (U, "n/a")}) <= 1)
        out[o] = res
    return out


def bisimulation():
    m3 = load("m3", os.path.join(TH, "m3_check.py"))
    seen, trans = m3.bfs(m3.B0, m3.B_ops())
    states = list(seen); succ = {k: [] for k in states}
    for s, lab, t, _ in trans: succ[tuple(sorted(s.items()))].append((lab.split("(")[0], tuple(sorted(t.items()))))
    part = {k: frozenset(l for l, _ in succ[k]) for k in states}          # initial: enabled-label sets
    while True:
        sig = {k: (part[k], frozenset((l, part[t]) for l, t in succ[k])) for k in states}
        ids = {}; new = {k: ids.setdefault(sig[k], len(ids)) for k in states}
        if len(set(new.values())) == len(set(part.values())): break
        part = new
    return {"reachable_states": len(states), "bisimulation_classes": len(set(part.values()))}


def selftest():
    U = "UNK"
    ev = [dict(event_id="p", outcome="PERFORMED", a="A", s="x"), dict(event_id="q", outcome="REFUSED", a="A", s="y"), dict(event_id="u", outcome="REFUSED", a="A", s=U)]
    ok = [("conflict on {a}", conflict(ev, ("a",), U) is not None), ("no conflict on {a, s}", conflict(ev, ("a", "s"), U) is None),
          ("UNK never conflicts", conflict([ev[0], ev[2]], ("a", "s"), U) is None)]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    sm, obs = r4_observations()
    sig = signatures(sm, obs)
    print(json.dumps({"labels": "FORMAL RESULTS over strict EMPIRICAL observations (SEMOBS r4); not theory", "n_events": len(obs),
                      "guard_signatures": sig, "ruling_status_bisimulation": bisimulation()}, indent=1, ensure_ascii=False))
