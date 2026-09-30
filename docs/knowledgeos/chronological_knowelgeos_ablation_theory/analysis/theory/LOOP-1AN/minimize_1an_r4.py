"""1an-r4: generalization instrument over the r3 separating semantics (committed before its first run).
Imports minimize_1an_r3 read-only (variants, D, hitting, analyse_op). r3's outputs stand.

ALL RESULTS ARE FORMAL / MODEL-RELATIVE; generalization is measured on the strict observations only; never empirical proof.

DATA = r3 variants BASE · REV · REV-TYPE, each also run WITH the R-44 SUPERSEDE event (scope fix, review B-r3 D-N5), coded
exactly as frozen in MODELS-1AL/SPEC-1AM-SUPERSEDE.json: o SUPERSEDE, r RULING, a ARB, k 'mechanism', e UNK, x UNK,
outcome PERFORMED, cluster 'R-44'. Variant names: V and V+R44.

1. FORCED-PAIR RECURRENCE (the reviewer's criterion, B-r3 §identity_flag_assessment). For operation o (MF pool), variable v
   FORMAL-REQUIRED, and each forced pair (p,q) with D(p,q) = {v}:
     p-side recurs iff another legality event of o in a DIFFERENT cluster from p has p[v] (known) and p's outcome; q likewise.
     GENERALIZING (both recur) · HALF-LOOKUP (one) · LOOKUP (neither).
   Operation-level: INSUFFICIENT FOR GENERALIZATION if o has < 3 distinct clusters among its legality events.
2. LEAVE-ONE-CLUSTER-OUT (LOCO) prediction, for operations with >= 3 clusters. For each cluster C:
     train = o's legality events not in C; G ranges over train's minimum hitting guards (MF pool);
     table_G = {values on G -> outcome} from train events with all G known (a value tuple with both outcomes is dropped);
     each held-out event: PREDICT if all G known and tuple in table_G, else ABSTAIN; score correct / wrong.
   Report per operation: folds, predictions, correct, wrong, abstentions, coverage = predictions / held-out events.
   Also report guard STABILITY: the fraction of folds whose train minimum guards include the full-data minimum guard.
3. DISJOINT SUPPORT per variable v (per data variant, MF, across operations): the maximum number of forced pairs of v
   whose cluster sets are pairwise disjoint (a within-cluster pair uses one cluster). Exhaustive search.
4. OPERATION TEST (fix of B-r3 D-N2): for a cross-operation opposite-outcome pair, S = (APPL[o_p] ∩ APPL[o_q]) ∪ {r};
   the pair is COMPARABLE iff every v in S is known on both; DECISIVE iff comparable and equal on all of S.
   power = number of comparable pairs. power = 0 -> operation UNTESTABLE; >= 1 decisive -> FORMAL-REQUIRED under MO;
   power >= 1 and 0 decisive -> MODEL-COND-REDUNDANT under MO.
Usage: python3 minimize_1an_r4.py [--selftest]"""
import itertools
import json
import os
import sys
import importlib.util

HERE = os.path.dirname(os.path.abspath(__file__)); TH = os.path.dirname(HERE)


def load(name, path):
    s = importlib.util.spec_from_file_location(name, path); m = importlib.util.module_from_spec(s); s.loader.exec_module(m); return m


r3 = load("r3", os.path.join(HERE, "minimize_1an_r3.py"))
R44 = {"event_id": "SUPERSEDE mechanism by R-44 (reported in R-57)", "cluster": "R-44", "o": "SUPERSEDE", "r": "RULING", "a": "ARB",
       "k": "mechanism", "s": "n/a", "t": "n/a", "e": "UNK", "x": "UNK", "h": "UNK", "c": "UNK", "outcome": "PERFORMED",
       "ground": "n/a", "genre": "register-row", "speech_act": "reported+performed"}


def recurrence(evs, v, p, q, U):
    def rec(a): return any(e is not a and e["cluster"] != a["cluster"] and r3.isknown(e[v], U) and e[v] == a[v]
                           and (e["outcome"] == "PERFORMED") == (a["outcome"] == "PERFORMED") for e in evs)
    n = rec(p) + rec(q)
    return ["LOOKUP", "HALF-LOOKUP", "GENERALIZING"][n]


def loco(evs, pool, U):
    clusters = sorted({e["cluster"] for e in evs})
    if len(clusters) < 3: return {"status": "INSUFFICIENT (< 3 clusters)", "clusters": len(clusters)}
    full = r3.analyse_op(evs, pool, U); fullmins = [frozenset(g) for g in full.get("minimum_guards", [])]
    pred = cor = wrong = abst = held = stable = 0
    for C in clusters:
        train = [e for e in evs if e["cluster"] != C]; test = [e for e in evs if e["cluster"] == C]; held += len(test)
        a = r3.analyse_op(train, pool, U)
        mins = [frozenset(g) for g in a.get("minimum_guards", [])] if a.get("status") != "UNCONSTRAINED" else []
        if fullmins and any(g in mins for g in fullmins): stable += 1
        for e in test:
            votes = set()
            for G in mins:
                tab = {}
                for t in train:
                    if all(r3.isknown(t[v], U) for v in G):
                        tab.setdefault(tuple(t[v] for v in sorted(G)), set()).add(t["outcome"] == "PERFORMED")
                key = tuple(e[v] for v in sorted(G))
                if all(r3.isknown(e[v], U) for v in G) and key in tab and len(tab[key]) == 1: votes |= tab[key]
            if len(votes) == 1:
                pred += 1; cor += (votes == {e["outcome"] == "PERFORMED"}); wrong += (votes != {e["outcome"] == "PERFORMED"})
            else: abst += 1
    return {"status": "RUN", "folds": len(clusters), "held_out": held, "predictions": pred, "correct": cor, "wrong": wrong,
            "abstentions": abst, "coverage": round(pred / held, 3) if held else None, "guard_stability": f"{stable}/{len(clusters)}"}


def disjoint_support(pairs_by_var):
    out = {}
    for v, prs in pairs_by_var.items():
        sets = [frozenset(p) for p in {tuple(sorted(x)) for x in prs}]
        best = 0
        for n in range(len(sets), 0, -1):
            if any(all(not (a & b) for a, b in itertools.combinations(c, 2)) for c in itertools.combinations(sets, n)): best = n; break
        out[v] = best
    return out


def operation_test(sm, obs):
    U = sm.U; L = r3.legal(obs); comp = dec = 0; decisive = []
    for p, q in r3.pairs(L):
        if p["o"] == q["o"]: continue
        S = (set(sm.APPL.get(p["o"], "")) & set(sm.APPL.get(q["o"], ""))) | {"r"}
        if all(r3.isknown(p[v], U) and r3.isknown(q[v], U) for v in S):
            comp += 1
            if all(p[v] == q[v] for v in S): dec += 1; decisive.append((p["event_id"], q["event_id"]))
    verdict = ("UNTESTABLE" if comp == 0 else "FORMAL-REQUIRED under MO" if dec else "MODEL-COND-REDUNDANT under MO")
    return {"power_comparable_pairs": comp, "decisive_pairs": decisive, "operation": verdict}


def run(sm, obs):
    U = sm.U; L = r3.legal(obs); res = {}; forced_all = {}
    for o in sorted({e["o"] for e in L}):
        evs = [e for e in L if e["o"] == o]; pool = set(sm.APPL.get(o, "")) | {"r"}
        a = r3.analyse_op(evs, pool, U); rec = {"clusters": len({e["cluster"] for e in evs}), "guard": a}
        if a.get("status") in ("SEPARABLE", "PARTIAL"):
            fr = [v for v, c in a["variable_class"].items() if c == "FORMAL-REQUIRED"]
            pr = r3.pairs(evs); gen = {}
            for v in fr:
                fp = [(p, q) for p, q in pr if r3.D(p, q, pool, U) == frozenset({v})]
                gen[v] = [{"pair": [p["event_id"], q["event_id"]], "class": recurrence(evs, v, p, q, U)} for p, q in fp]
                forced_all.setdefault(v, []).extend([(p["cluster"], q["cluster"]) for p, q in fp])
            rec["recurrence"] = gen if rec["clusters"] >= 3 else "INSUFFICIENT FOR GENERALIZATION (< 3 clusters)"
            rec["recurrence_raw"] = gen
        rec["loco"] = loco(evs, pool, U)
        res[o] = rec
    return {"operations": res, "disjoint_support": disjoint_support(forced_all), "operation_test": operation_test(sm, obs)}


def selftest():
    U = "UNK"
    ev = [dict(event_id=f"e{i}", cluster=c, outcome=o, s=s, a="A", r=U) for i, (c, o, s) in
          enumerate([("c1", "PERFORMED", "x"), ("c2", "REFUSED", "y"), ("c3", "PERFORMED", "x"), ("c4", "REFUSED", "y")])]
    lo = loco(ev, {"s", "a", "r"}, U)
    ok = [("recurrence GENERALIZING when both sides recur", recurrence(ev, "s", ev[0], ev[1], U) == "GENERALIZING"),
          ("recurrence LOOKUP when neither recurs", recurrence(ev[:2], "s", ev[0], ev[1], U) == "LOOKUP"),
          ("LOCO predicts all 4 correctly", lo["correct"] == 4 and lo["wrong"] == 0),
          ("disjoint support counts non-overlapping pairs", disjoint_support({"s": [("c1", "c2"), ("c2", "c3"), ("c3", "c4")]})["s"] == 2)]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    m1 = load("m1al", os.path.join(TH, "MODELS-1AL", "models_1al.py")); sm, obs = m1.r4_observations()
    out = {"labels": "FORMAL / MODEL-RELATIVE generalization measures over strict observations; not empirical proof", "variants": {}}
    for v in ("BASE", "REV", "REV-TYPE"):
        base = r3.variant(obs, v)
        out["variants"][v] = run(sm, base)
        out["variants"][v + "+R44"] = run(sm, base + [dict(R44)])
    print(json.dumps(out, indent=1, ensure_ascii=False, default=str))
