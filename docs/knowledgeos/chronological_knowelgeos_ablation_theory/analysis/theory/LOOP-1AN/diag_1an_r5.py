"""1an-r5: START single-guard LOCO diagnostic (committed before its first run). SECONDARY FORMAL DIAGNOSTIC; not a rescue.
Justification: review B-r4 (start_coverage_assessment) judged a guard-restricted {s}-only LOCO more informative than r4's
refit-and-vote LOCO, provided it is frozen first. r4's outputs and verdicts stand.

DATA: START legality events (PERFORMED or RULE-grounded; CHOICE excluded) of each r3 variant (BASE, REV, REV-TYPE).
For a FIXED guard G in { {s}, {s,t} } (no refitting, no voting), and for each cluster C (leave-one-cluster-out):
  train = START events not in C.  table_G: value tuple on G -> set of outcomes, over train events with all G known.
  A held-out event is PREDICTED iff all G known and its tuple is in table_G with exactly ONE outcome; else ABSTAIN
  (reason recorded: UNK-on-G / unseen-tuple / conflicting-tuple).
  BASELINE: majority outcome of train (ties -> ABSTAIN), scored on (i) all held-out events, (ii) the G-predicted subset.
Report per G and variant: held-out, predicted, correct, wrong, abstentions by reason, coverage, baseline accuracy (i) and (ii).
Interpretation rule (frozen): G is DIAGNOSTICALLY PREDICTIVE on this data iff wrong = 0, predicted >= 4 across >= 3 folds,
and correct > baseline correct on the same predicted subset. Otherwise NOT SHOWN. Neither is empirical proof.
Usage: python3 diag_1an_r5.py [--selftest]"""
import json
import os
import sys
import importlib.util

HERE = os.path.dirname(os.path.abspath(__file__)); TH = os.path.dirname(HERE)


def load(name, path):
    s = importlib.util.spec_from_file_location(name, path); m = importlib.util.module_from_spec(s); s.loader.exec_module(m); return m


r3 = load("r3", os.path.join(HERE, "minimize_1an_r3.py"))


def P(e): return e["outcome"] == "PERFORMED"


def diag(evs, G, U):
    clusters = sorted({e["cluster"] for e in evs})
    res = {"held_out": 0, "predicted": 0, "correct": 0, "wrong": 0, "abstain": {"UNK-on-G": 0, "unseen-tuple": 0, "conflicting-tuple": 0},
           "folds_with_prediction": 0, "baseline_all_correct": 0, "baseline_all_scored": 0, "baseline_on_predicted_correct": 0, "per_fold": []}
    for C in clusters:
        train = [e for e in evs if e["cluster"] != C]; test = [e for e in evs if e["cluster"] == C]
        tab = {}
        for t in train:
            if all(r3.isknown(t[v], U) for v in G): tab.setdefault(tuple(t[v] for v in G), set()).add(P(t))
        npos = sum(P(t) for t in train); nneg = len(train) - npos; base = None if npos == nneg else (npos > nneg)
        fold = {"cluster": C, "n": len(test), "pred": 0, "correct": 0}
        for e in test:
            res["held_out"] += 1
            if base is not None: res["baseline_all_scored"] += 1; res["baseline_all_correct"] += (base == P(e))
            if not all(r3.isknown(e[v], U) for v in G): res["abstain"]["UNK-on-G"] += 1; continue
            key = tuple(e[v] for v in G)
            if key not in tab: res["abstain"]["unseen-tuple"] += 1; continue
            if len(tab[key]) != 1: res["abstain"]["conflicting-tuple"] += 1; continue
            pred = next(iter(tab[key])); res["predicted"] += 1; fold["pred"] += 1
            ok = pred == P(e); res["correct"] += ok; res["wrong"] += (not ok); fold["correct"] += ok
            if base is not None: res["baseline_on_predicted_correct"] += (base == P(e))
        if fold["pred"]: res["folds_with_prediction"] += 1
        res["per_fold"].append(fold)
    res["coverage"] = round(res["predicted"] / res["held_out"], 3) if res["held_out"] else None
    res["verdict"] = ("DIAGNOSTICALLY PREDICTIVE" if res["wrong"] == 0 and res["predicted"] >= 4 and res["folds_with_prediction"] >= 3
                      and res["correct"] > res["baseline_on_predicted_correct"] else "NOT SHOWN")
    return res


def selftest():
    U = "UNK"
    ev = [dict(event_id=str(i), cluster=f"c{i}", outcome=o, s=s, t=f"t{i}") for i, (o, s) in
          enumerate([("PERFORMED", "x"), ("REFUSED", "y"), ("PERFORMED", "x"), ("REFUSED", "y"), ("REFUSED", "y")])]
    d = diag(ev, ("s",), U); dt = diag(ev, ("s", "t"), U)
    ok = [("{s} predicts all 5", d["predicted"] == 5 and d["wrong"] == 0), ("{s,t} lookup never generalizes", dt["predicted"] == 0),
          ("baseline computed (3 of 5 folds tie -> 2 scored)", d["baseline_all_scored"] == 2), ("verdict predictive", d["verdict"] == "DIAGNOSTICALLY PREDICTIVE")]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    m1 = load("m1al", os.path.join(TH, "MODELS-1AL", "models_1al.py")); sm, obs = m1.r4_observations()
    out = {"labels": "SECONDARY FORMAL DIAGNOSTIC on strict START observations; not empirical proof; not a rescue", "variants": {}}
    for v in ("BASE", "REV", "REV-TYPE"):
        evs = [e for e in r3.legal(r3.variant(obs, v)) if e["o"] == "START"]
        out["variants"][v] = {"n_events": len(evs), "n_clusters": len({e["cluster"] for e in evs}),
                              "G={s}": diag(evs, ("s",), sm.U), "G={s,t}": diag(evs, ("s", "t"), sm.U)}
    print(json.dumps(out, indent=1, ensure_ascii=False))
