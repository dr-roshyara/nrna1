r"""§12 confounding analysis: is the within-T_C association explained by REDUNDANCY?

Hypothesis H3: redundancy is a COMMON CAUSE.
  more duplicates -> S more likely a duplicate      -> Zero more likely
  more duplicates -> dedup collapses more           -> larger fiber -> inadequate more likely
If so, holding redundancy FIXED should remove the association.
"""
import json, os, sys, itertools, collections, random
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from bridge import *
from run_bridge import gen, rep_key, adequacy_map, SEED_TRAIN, N_CASES, MAXS

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

def redundancy(D):
    """declared stratifier: number of records MINUS number of distinct values"""
    return len(D.recs) - len({r.v for r in D.recs})

def run(tname):
    cases = gen(N_CASES, SEED_TRAIN)
    adeq, keys, fib = adequacy_map(cases, tname)
    strat = collections.defaultdict(collections.Counter)
    for ci, D in enumerate(cases):
        r = redundancy(D)
        for m in range(1, MAXS+1):
            for S in itertools.combinations(range(N_REC), m):
                z = zero(D, tname, set(S)); a = adeq[ci]
                strat[r][("Z" if z else "NZ") + ("+A" if a else "+I")] += 1
    out = {}
    for r in sorted(strat):
        c = strat[r]
        nz = c["Z+A"]+c["Z+I"]; nnz = c["NZ+A"]+c["NZ+I"]
        pz  = c["Z+A"]/nz  if nz  else None
        pnz = c["NZ+A"]/nnz if nnz else None
        out[str(r)] = {"cells": dict(c), "n": sum(c.values()),
                       "P_A_given_Z": round(pz,5) if pz is not None else None,
                       "P_A_given_NZ": round(pnz,5) if pnz is not None else None,
                       "risk_difference": (round(pz-pnz,5)
                                           if None not in (pz,pnz) else None)}
    return out

if __name__ == "__main__":
    res = {t: run(t) for t in ("T_C_dedup", "T_E_dedup_lossy")}
    json.dump(res, open(f"{ROOT}/results/stratified.json","w"), indent=1, default=str)
    for t, o in res.items():
        print(f"\n=== {t} — stratified by REDUNDANCY (n_recs - n_distinct_values) ===")
        print(f"{'redund':>7s} {'n':>9s} {'P(A|Z)':>9s} {'P(A|NZ)':>9s} {'RD':>9s}   cells")
        for r, v in o.items():
            print(f"{r:>7s} {v['n']:9d} {str(v['P_A_given_Z']):>9s} "
                  f"{str(v['P_A_given_NZ']):>9s} {str(v['risk_difference']):>9s}   {v['cells']}")
