"""1an: formal minimization (committed before its first run). Deterministic; no corpus read.
RESULT CLASSES ARE MODEL-RELATIVE. Nothing here is empirical falsification. Every removal is reported as
"MODEL-CONDITIONAL REDUNDANCY under model M and the tested transition semantics"; every requirement as
"FORMAL REQUIREMENT under M". Empirical status is imported read-only from the F-LOG record and never changed here.

DATA
  SEMOBS r4 strict observations (36 events), loaded as in models_1al. Three data variants (frozen):
    BASE      r4 as frozen.
    REV       reviewed corrections applied (each cited): C1 L493-B actor PO/ARB -> UNK (F-LOG-0147);
              C2 the objects R-81..85 are kind "ruling" at the coarse grain for both of their ADOPT events, and R-86's
              conformance is "conformant" (F-LOG-0153).
    REV-TYPE  REV, but kind at the typed-header grain (F-LOG-0154): R-81..85 carry mixed header types -> k UNK;
              R-91 -> "determination-on-submitted-evidence".
  CHOICE-grounded events never enter legality (as in 1al). Identity tokens "same:X" equal only the identical token.

GUARD ANALYSIS (per operation o; the 1al semantics)
  A variable set V is CONSISTENT for o iff no two legality events of o with known, equal values on all of V have
  opposite outcomes (an UNK never creates a conflict). Minimal consistent subsets of a model's variable set are the
  surviving guard signatures of o under that model.
  Models (variable pools, intersected with the frozen applicability table APPL[o] plus route r):
    M0 = {a,k,s,t}   M1 = M0+{e}   M2 = M0+{c}   M4 = M0+{h}   MF = APPL[o] ∪ {r}
    M3 = GLOBAL: all operations pooled, pool MF-vars of all ops, operation label o REMOVED -> tests whether o is a
         formal requirement (a cross-operation conflict resolvable only by o).
  Per variable v and model M, class for operation o:
    FORMAL-REQUIRED        M consistent and v in every minimal consistent subset
    MODEL-COND-REDUNDANT   M consistent and some minimal consistent subset omits v
    VACUOUS                v takes <= 1 known value across o's legality events (untested, not redundant)
    MODEL-INADEQUATE       M itself inconsistent for o (M cannot represent o's data)
    UNCONSTRAINED          o has only one legality outcome observed
  Global minimum (under MF): choose one minimal signature per constrained operation minimizing the size of the union.

BISIMULATION (ruling-status LTS, m3 sub-model B, imported read-only)
  Partition refinement as in 1al. Then for each state-component family F in {iss, st, reg, text, ann, by, sup, deleg}:
  F is MODEL-COND-REDUNDANT iff any two reachable states equal on all non-F components are bisimilar; otherwise a
  counterexample pair (a minimal distinguishing situation) is reported.
Usage: python3 minimize_1an.py [--selftest]"""
import importlib.util
import itertools
import json
import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__)); TH = os.path.dirname(HERE)
M1AL_PATH = os.path.join(TH, "MODELS-1AL", "models_1al.py")


def load(name, path):
    s = importlib.util.spec_from_file_location(name, path); m = importlib.util.module_from_spec(s); s.loader.exec_module(m); return m


EMPIRICAL = {"a": "SUPPORTED", "k": "SUPPORTED", "s": "SUPPORTED (target-indexed)", "t": "index of s; possible witness only",
             "e": "WEAK (overloaded: repetition/breadth)", "c": "WEAK (coarse grain only)", "o": "NOT DEMONSTRATED",
             "r": "NOT DEMONSTRATED", "x": "NOT DEMONSTRATED", "h": "absorbed into status (formal)"}


def variant(obs, name):
    out = [dict(e) for e in obs]
    if name in ("REV", "REV-TYPE"):
        for e in out:
            if e["event_id"].startswith("RAISE L493-B"): e["a"] = "UNK"
            if e["event_id"].startswith("ADOPT R-81..85"): e["k"] = "ruling"
            if e["event_id"] == "ADOPT R-81..85 by the DA (R-86)": e["c"] = "conformant"
    if name == "REV-TYPE":
        for e in out:
            if e["event_id"].startswith("ADOPT R-81..85"): e["k"] = "UNK"
            if e["event_id"] == "ADOPT R-91 by the DA (held)": e["k"] = "determination-on-submitted-evidence"
    return out


def known(v, U): return v not in (U, "n/a", None)


def conflict(evs, V, U):
    for p, q in itertools.combinations(evs, 2):
        if (p["outcome"] == "PERFORMED") == (q["outcome"] == "PERFORMED"): continue
        if all(known(p[v], U) and p[v] == q[v] for v in V): return (p["event_id"], q["event_id"])
    return None


def minimal_sets(evs, pool, U):
    cons = [frozenset(c) for n in range(len(pool) + 1) for c in itertools.combinations(sorted(pool), n) if conflict(evs, c, U) is None]
    return [c for c in cons if not any(d < c for d in cons)]


MODELS = {"M0": set("akst"), "M1": set("akste"), "M2": set("akstc"), "M4": set("aksth")}


def guard_analysis(sm, obs):
    U = sm.U; L = [e for e in obs if e["ground"] != "CHOICE"]; res = {}
    for o in sorted({e["o"] for e in L}):
        evs = [e for e in L if e["o"] == o]; appl = set(sm.APPL.get(o, "")) | {"r"}
        both = len({e["outcome"] == "PERFORMED" for e in evs}) == 2
        pools = {m: (vs & appl) for m, vs in MODELS.items()}; pools["MF"] = appl
        rec = {"events": len(evs), "both_outcomes": both}
        for m, pool in pools.items():
            if not both:
                rec[m] = {"status": "UNCONSTRAINED"}; continue
            full = conflict(evs, sorted(pool), U)
            if full:
                rec[m] = {"status": "MODEL-INADEQUATE", "unresolved_conflict": full}; continue
            mins = minimal_sets(evs, pool, U)
            cls = {}
            for v in sorted(pool):
                if len({e[v] for e in evs if known(e[v], U)}) <= 1: cls[v] = "VACUOUS"
                elif all(v in s for s in mins): cls[v] = "FORMAL-REQUIRED"
                else: cls[v] = "MODEL-COND-REDUNDANT"
            rec[m] = {"status": "CONSISTENT", "minimal_signatures": [sorted(s) for s in mins], "variable_class": cls}
        res[o] = rec
    return res


def global_min(ga):
    choices = {o: [frozenset(s) for s in r["MF"]["minimal_signatures"]] for o, r in ga.items() if r["MF"].get("status") == "CONSISTENT"}
    best = None
    for combo in itertools.product(*choices.values()):
        u = frozenset().union(*combo)
        if best is None or len(u) < len(best[0]): best = (u, dict(zip(choices, combo)))
    ties = sorted({tuple(sorted(frozenset().union(*c))) for c in itertools.product(*choices.values()) if len(frozenset().union(*c)) == len(best[0])})
    return {"min_union_size": len(best[0]), "min_unions": [list(t) for t in ties]}


def m3_global(sm, obs):
    U = sm.U; L = [e for e in obs if e["ground"] != "CHOICE"]
    pool = sorted(set("akstexhcr"))
    c_without_o = conflict(L, pool, U)
    return {"conflict_without_operation_label": c_without_o,
            "operation_status": "FORMAL-REQUIRED under M3 (a cross-operation conflict needs o)" if c_without_o else
                                "MODEL-COND-REDUNDANT under M3 (no cross-operation conflict on the pooled variables)"}


FULL_LABELS = False


def bisim_components():
    m3 = load("m3", os.path.join(TH, "m3_check.py"))
    seen, trans = m3.bfs(m3.B0, m3.B_ops())
    key = lambda s: tuple(sorted(s.items()))
    states = [key(s) if isinstance(s, dict) else s for s in seen]
    succ = {k: [] for k in states}
    # r2 fix (disclosed defect in r1): the frozen docstring says "as in 1al" = operation-name labels; r1 used full labels.
    # FULL_LABELS=True reproduces r1 as a labelled variant.
    for s, lab, t, _ in trans: succ[key(s)].append((lab if FULL_LABELS else lab.split("(")[0], key(t)))
    part = {k: frozenset(l for l, _ in succ[k]) for k in states}
    while True:
        sig = {k: (part[k], frozenset((l, part[t]) for l, t in succ[k])) for k in states}
        ids = {}; new = {k: ids.setdefault(sig[k], len(ids)) for k in states}
        if len(set(new.values())) == len(set(part.values())): part = new; break
        part = new
    fams = sorted({k.split(".")[0] for k in m3.B0})
    out = {"reachable_states": len(states), "bisimulation_classes": len(set(part.values())), "families": {}}
    for F in fams:
        groups = {}
        for k in states: groups.setdefault(tuple((n, v) for n, v in k if n.split(".")[0] != F), []).append(k)
        cex = next(((g[i], g[j]) for g in groups.values() for i in range(len(g)) for j in range(i + 1, len(g)) if part[g[i]] != part[g[j]]), None)
        out["families"][F] = {"class": "MODEL-COND-REDUNDANT" if cex is None else "BEHAVIOUR-RELEVANT",
                              "counterexample": None if cex is None else [dict(cex[0]), dict(cex[1])]}
    return out


def selftest():
    U = "UNK"
    ev = [dict(event_id="p", outcome="PERFORMED", a="A", s="x"), dict(event_id="q", outcome="REFUSED", a="A", s="y")]
    ok = [("conflict on {a}", conflict(ev, ("a",), U) is not None), ("none on {a,s}", conflict(ev, ("a", "s"), U) is None),
          ("minimal set is {s}", minimal_sets(ev, {"a", "s"}, U) == [frozenset({"s"})]),
          ("token vs value never conflicts", conflict([dict(ev[0], a="same:X"), dict(ev[1], a="A", s="x")], ("a", "s"), U) is None)]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    m = load("m1al", M1AL_PATH); sm, obs = m.r4_observations()
    out = {"labels": "FORMAL, MODEL-RELATIVE results over strict observations; NOT empirical falsification; empirical status imported read-only",
           "empirical_status_imported": EMPIRICAL, "variants": {}}
    for v in ("BASE", "REV", "REV-TYPE"):
        ob = variant(obs, v); ga = guard_analysis(sm, ob)
        out["variants"][v] = {"guards": ga, "global_minimum_MF": global_min(ga), "M3_operation_test": m3_global(sm, ob)}
    out["bisimulation"] = bisim_components()
    FULL_LABELS = True; out["bisimulation_variant_full_labels_r1"] = bisim_components()
    print(json.dumps(out, indent=1, ensure_ascii=False, default=str))
