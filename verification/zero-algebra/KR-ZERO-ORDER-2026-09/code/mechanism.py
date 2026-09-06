"""The decisive 2x2: is irreducibility ever produced when BOTH T and Pi are element-wise?

RELATIONAL T  = its output depends on relations BETWEEN elements
                T2_dedup (equality with an earlier element)
                T4_context (adjacency)
                T8_interacting (both)
ELEMENT-WISE T = a per-element predicate / function / projection
                T1 T3 T5 T6 T7

CANCELLING Pi = P9_balance: a numeric aggregate over the multiset that admits cancellation
                and discards position.  Every other contract is position-preserving.
"""
import itertools, random, collections, json, os
from zero_algebra import *
from generators import corpus
from interaction_order import minimal_k
from experiments import SEED, N, MAXN, TSET, PSET

RELATIONAL_T = {"T2_dedup", "T4_context", "T8_interacting"}
CANCELLING_P = {"P9_balance"}

def run(n_cases=900, seed_off=555):
    rng = random.Random(SEED + seed_off)
    cells = collections.defaultdict(lambda: {"tests": 0, "irre": 0})
    witnesses = collections.defaultdict(list)
    ctxs = [c for c in corpus(n_cases, SEED + seed_off) if 2 <= len(c["rep"]) <= MAXN]
    for c in ctxs:
        D = c["rep"]
        for t in TSET:
            for p in PSET:
                if contract_is_vacuous(D, t, p): continue
                cell = ("relational_T" if t in RELATIONAL_T else "elementwise_T",
                        "cancelling_Pi" if p in CANCELLING_P else "noncancelling_Pi")
                for m in range(2, len(D) + 1):
                    if len(list(itertools.combinations(range(len(D)), m))) < 2: continue
                    k, w = minimal_k(D, t, p, m)
                    cells[cell]["tests"] += 1
                    if k is None and w is not None:
                        cells[cell]["irre"] += 1
                        if len(witnesses[cell]) < 5:
                            witnesses[cell].append({
                              "T": t, "Pi": p, "m": m, "n": len(D),
                              "tokens": [i.token for i in D.items],
                              "polarities": [i.polarity for i in D.items],
                              "colliding": w["colliding_subsets"]})
    out = {}
    for k, v in cells.items():
        out[f"{k[0]} x {k[1]}"] = {**v,
            "rate": round(v["irre"]/v["tests"], 5) if v["tests"] else None}
    return {"design": "exhaustive over T x Pi for every generated context -- fully crossed",
            "n_contexts": len(ctxs), "seed": SEED + seed_off,
            "cells": out,
            "witnesses_per_cell": {f"{k[0]} x {k[1]}": v for k, v in witnesses.items()},
            "decisive_question": ("is the elementwise_T x noncancelling_Pi cell EMPTY? "
                                  "if yes: irreducibility requires relational structure "
                                  "in T or in Pi. if no: the hypothesis is falsified.")}

if __name__ == "__main__":
    r = run()
    ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    json.dump(r, open(os.path.join(ROOT, "mechanism-2x2.json"), "w"), indent=1, default=str)
    print(f"contexts: {r['n_contexts']}   (fully crossed over 8 T x 9 Pi)\n")
    print(f"{'cell':44s} {'irreducible':>12s} {'tests':>9s} {'rate':>9s}")
    for k, v in sorted(r["cells"].items()):
        print(f"  {k:42s} {v['irre']:12d} {v['tests']:9d} {str(v['rate']):>9s}")
