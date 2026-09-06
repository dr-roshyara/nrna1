#!/usr/bin/env python3
r"""KR-BRIDGE-01 runner.  Declared BEFORE execution; no post-hoc filtering."""
import json, os, sys, math, random, itertools, collections, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from bridge import *

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SEED_TRAIN, SEED_TEST = 20260904, 88020260904
N_CASES = 25000
MAXS    = 2                      # elimination subset sizes swept: |S| in {1,2}

def gen(n, seed):
    rng = random.Random(seed)
    return [Case(tuple(Rec(v=rng.choice(VALUES), src=rng.choice(SOURCES),
                           tag=rng.choice(TAGS), t=rng.choice(TIMES))
                       for _ in range(N_REC))) for _ in range(n)]

def rep_key(R):
    return tuple((r.v, r.src, r.tag, r.rank) for r in R.recs)

def adequacy_map(cases, tname):
    """Adequate(D,T) iff D's R-fiber is Q-HOMOGENEOUS over the population.
    Uses Q only; Zero uses only Pi.  The two are computed by disjoint code paths."""
    T = TRANSFORMS[tname][0]
    fib = collections.defaultdict(set)
    keys = []
    for D in cases:
        k = rep_key(T(D)); keys.append(k); fib[k].add(Q(D))
    return [len(fib[k]) == 1 for k in keys], keys, fib

def analyse(cases, split):
    out = {"split": split, "n_cases": len(cases), "by_transform": {}, "rows": []}
    for tname in TRANSFORMS:
        adeq, keys, fib = adequacy_map(cases, tname)
        cell = collections.Counter()
        by_size = collections.defaultdict(collections.Counter)
        for ci, D in enumerate(cases):
            for m in range(1, MAXS+1):
                for S in itertools.combinations(range(N_REC), m):
                    z = zero(D, tname, set(S))
                    a = adeq[ci]
                    c = ("Z+A" if z and a else "Z+I" if z else "NZ+A" if a else "NZ+I")
                    cell[c] += 1; by_size[m][c] += 1
                    if ci < 400:          # persisted sample of full rows
                        out["rows"].append({"case": ci, "split": split, "T": tname,
                            "S": list(S), "zero": z, "adequate": a, "cell": c,
                            "Q": list(Q(D)), "Pi_TD": list(Pi(TRANSFORMS[tname][0](D)))})
        n = sum(cell.values())
        nz = cell["Z+A"]+cell["Z+I"]; nnz = cell["NZ+A"]+cell["NZ+I"]
        pA_given_Z  = cell["Z+A"]/nz  if nz  else None
        pA_given_NZ = cell["NZ+A"]/nnz if nnz else None
        out["by_transform"][tname] = {
            "cells": dict(cell), "n_obs": n,
            "adequacy_rate": round(sum(adeq)/len(adeq), 5),
            "zero_rate": round(nz/n, 5) if n else None,
            "P_A_given_Z":  round(pA_given_Z,5)  if pA_given_Z  is not None else None,
            "P_A_given_NZ": round(pA_given_NZ,5) if pA_given_NZ is not None else None,
            "risk_difference": (round(pA_given_Z - pA_given_NZ,5)
                                if None not in (pA_given_Z,pA_given_NZ) else None),
            "by_S_size": {str(k): dict(v) for k,v in by_size.items()},
            "n_distinct_representations": len(fib)}
    return out

def pooled(res):
    c = collections.Counter()
    for t,v in res["by_transform"].items(): c.update(v["cells"])
    nz = c["Z+A"]+c["Z+I"]; nnz = c["NZ+A"]+c["NZ+I"]; n = sum(c.values())
    pz  = c["Z+A"]/nz if nz else None
    pnz = c["NZ+A"]/nnz if nnz else None
    # odds ratio with Haldane correction
    a,b,cc,d = c["Z+A"]+.5, c["Z+I"]+.5, c["NZ+A"]+.5, c["NZ+I"]+.5
    orat = (a*d)/(b*cc)
    se = math.sqrt(1/a+1/b+1/cc+1/d)
    return {"cells": dict(c), "n": n,
            "P_A_given_Z": round(pz,5) if pz is not None else None,
            "P_A_given_NZ": round(pnz,5) if pnz is not None else None,
            "risk_difference": round(pz-pnz,5) if None not in (pz,pnz) else None,
            "odds_ratio": round(orat,4),
            "log_or_ci95": [round(math.exp(math.log(orat)-1.96*se),4),
                            round(math.exp(math.log(orat)+1.96*se),4)],
            "note": "Haldane +0.5 correction; CI is Woolf on log-OR"}

# ============================================================ AMENDMENT 1 (pre-execution)
# The labels "preserving" / "lossy" / "redundancy-removing" are PRE-REGISTERED DESIGN
# CLASSIFICATIONS, not established facts.  They are verified against observed behaviour
# after execution.  A mismatch is recorded as a control/design FINDING -- the
# transformation is never silently redefined to match its label.
PREREGISTERED = {
 "T_A_preserving":  {"expect_adequacy": "high",   "expect_zero": "rare"},
 "T_B_lossy":       {"expect_adequacy": "low",    "expect_zero": "rare"},
 "T_C_dedup":       {"expect_adequacy": "varies", "expect_zero": "common"},
 "T_D_relational":  {"expect_adequacy": "varies", "expect_zero": "varies"},
 "T_E_dedup_lossy": {"expect_adequacy": "low",    "expect_zero": "common"},
 "T_F_destroying":  {"expect_adequacy": "low",    "expect_zero": "varies"},
 "T_G_recoding":    {"expect_adequacy": "high",   "expect_zero": "rare"},
}
def verify_labels(by_transform):
    """Did each transformation behave as its pre-registered label claims?"""
    out = {}
    for t, pre in PREREGISTERED.items():
        a = by_transform[t]["adequacy_rate"]; z = by_transform[t]["zero_rate"]
        exp_a = pre["expect_adequacy"]; exp_z = pre["expect_zero"]
        ok_a = (exp_a == "high" and a > 0.95) or (exp_a == "low" and a < 0.05) or exp_a == "varies"
        ok_z = (exp_z == "rare" and z < 0.05) or (exp_z == "common" and z > 0.05) or exp_z == "varies"
        out[t] = {"observed_adequacy": a, "expected": exp_a, "adequacy_label_holds": ok_a,
                  "observed_zero_rate": z, "expected_zero": exp_z, "zero_label_holds": ok_z,
                  "LABEL_MISMATCH": not (ok_a and ok_z)}
    return out

def controls(cases):
    r = {}
    adeqA,_,_ = adequacy_map(cases, "T_A_preserving")
    adeqF,_,_ = adequacy_map(cases, "T_F_destroying")
    adeqG,_,_ = adequacy_map(cases, "T_G_recoding")
    r["CONTROL_A_preserving_is_adequate"] = {"rate": round(sum(adeqA)/len(adeqA),5),
        "PASS": sum(adeqA)/len(adeqA) > 0.95}
    r["CONTROL_B_destroying_is_inadequate"] = {"rate": round(sum(adeqF)/len(adeqF),5),
        "PASS": sum(adeqF)/len(adeqF) < 0.05}
    r["CONTROL_C_invertible_recoding_matches_A"] = {
        "A_rate": round(sum(adeqA)/len(adeqA),5), "G_rate": round(sum(adeqG)/len(adeqG),5),
        "PASS": abs(sum(adeqA)-sum(adeqG)) == 0,
        "note": "T_G shifts every value by +100 -- an invertible recoding; adequacy must be identical"}
    r["CONTROL_E_preservation_test_detects_loss"] = {
        "PASS": (sum(adeqA)/len(adeqA)) - (sum(adeqF)/len(adeqF)) > 0.5,
        "note": "the adequacy test must separate the preserving from the destroying control"}
    zr = collections.Counter()
    for D in cases[:3000]:
        for tname in TRANSFORMS:
            for i in range(N_REC): zr[tname] += zero(D, tname, {i})
    r["CONTROL_F_zero_test_detects_differences"] = {
        "zero_counts_by_T": dict(zr),
        "PASS": len({v>0 for v in zr.values()}) == 2,
        "note": "Zero must fire for some transformations and not others"}
    r["CONTROL_D_zero_varies_independently"] = {
        "note": "assessed from the four-way table: all four cells non-empty",
        "PASS": None}
    return r

if __name__ == "__main__":
    tr, te = gen(N_CASES, SEED_TRAIN), gen(N_CASES, SEED_TEST)
    print("controls…", flush=True); ctl = controls(tr)
    print("analysing TRAIN…", flush=True); a_tr = analyse(tr, "train")
    print("analysing TEST…",  flush=True); a_te = analyse(te, "test")
    p_tr, p_te = pooled(a_tr), pooled(a_te)
    ctl["CONTROL_D_zero_varies_independently"]["PASS"] = all(
        p_tr["cells"].get(k,0) > 0 for k in ("Z+A","Z+I","NZ+A","NZ+I"))
    META = dict(experiment_id="KR-BRIDGE-01-ZERO-PRESERVATION-2026-09",
                theory="v1.2 FROZEN", kernel="NOT SELECTED", algebra="none",
                seeds={"train":SEED_TRAIN,"test":SEED_TEST}, n_cases=N_CASES,
                S_sizes=list(range(1,MAXS+1)),
                date=datetime.date.today().isoformat(), python=platform.python_version())
    labels_tr = verify_labels(a_tr["by_transform"])
    labels_te = verify_labels(a_te["by_transform"])
    # AMENDMENT 2: natural imbalance is EVIDENCE. Cells are reported, never engineered.
    imbalance = {"achieved_cells_train": p_tr["cells"],
                 "achieved_cells_test":  p_te["cells"],
                 "empty_cells": [k for k in ("Z+A","Z+I","NZ+A","NZ+I")
                                 if p_tr["cells"].get(k,0)==0],
                 "rule": ("the generator was NOT tuned to balance cells; imbalance is reported "
                          "with its structural cause, not corrected")}
    json.dump({"_meta":META,"controls":ctl,"label_verification_train":labels_tr,
               "label_verification_test":labels_te,"cell_imbalance":imbalance,
               "train":a_tr["by_transform"],
               "test":a_te["by_transform"],"pooled_train":p_tr,"pooled_test":p_te},
              open(f"{ROOT}/results/bridge.json","w"), indent=1, default=str)
    with open(f"{ROOT}/corpus/rows.jsonl","w") as f:
        for r in a_tr["rows"]+a_te["rows"]: f.write(json.dumps(r,separators=(",",":"))+"\n")
    with open(f"{ROOT}/corpus/cases.jsonl","w") as f:
        for sp,cs,sd in (("train",tr,SEED_TRAIN),("test",te,SEED_TEST)):
            for i,D in enumerate(cs):
                f.write(json.dumps({"cid":i,"sp":sp,"seed":sd,
                    "D":[[r.v,r.src,r.tag,r.t] for r in D.recs],
                    "Q":list(Q(D)),"Pi_D":list(Pi(D))},separators=(",",":"))+"\n")
    print("\nLABEL VERIFICATION (amendment 1) — pre-registered vs observed:")
    for t,v in labels_tr.items():
        flag = "  ⚠ MISMATCH" if v["LABEL_MISMATCH"] else ""
        print(f"   {t:18s} adeq={v['observed_adequacy']:.4f} (exp {v['expected']:8s})  "
              f"zero={v['observed_zero_rate']:.4f} (exp {v['expected_zero']}){flag}")
    print("\nCONTROLS:")
    for k,v in ctl.items(): print(f"  {'PASS' if v.get('PASS') else 'FAIL'}  {k}")
    print(f"\nPOOLED TRAIN four-way: {p_tr['cells']}")
    print(f"  P(A|Z)={p_tr['P_A_given_Z']}  P(A|NZ)={p_tr['P_A_given_NZ']}  "
          f"RD={p_tr['risk_difference']}  OR={p_tr['odds_ratio']} CI{p_tr['log_or_ci95']}")
    print(f"POOLED TEST  four-way: {p_te['cells']}")
    print(f"  P(A|Z)={p_te['P_A_given_Z']}  P(A|NZ)={p_te['P_A_given_NZ']}  "
          f"RD={p_te['risk_difference']}  OR={p_te['odds_ratio']}")
