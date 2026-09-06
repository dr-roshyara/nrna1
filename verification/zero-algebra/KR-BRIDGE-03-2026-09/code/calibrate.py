#!/usr/bin/env python3
r"""KR-BRIDGE-03 CALIBRATION — pick the generator regime by MEASUREMENT, not by argument.

Metric: how many (cell, stratum) pairs are INFORMATIVE, where a stratum is informative
iff BOTH Zero and non-Zero occur in it AND both Adequate and Inadequate occur.
Stratum = (multiplicity PARTITION, |S|)  -- finer than the redundancy count.

Guardrail: a regime that buys informative strata by saturating adequacy (-> 1.0) or by
making Zero constant is REJECTED, however good its stratum count looks.
"""
import sys, os, json, itertools, collections, time
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from bridge import TRANSFORMS, E_S
from observables import PIS, QS, grid
from provenance import admissible_triple
from generator import make_generator, REGIMES, redundancy, multiplicity_partition

MIN_STRATUM_N = 200
N_CAL = 1500

def rep_key(R): return tuple((r.v, r.src, r.tag, r.rank) for r in R.recs)

def evaluate(regime, n=N_CAL, seed=20260904):
    g = make_generator(regime); cases = g(n, seed); n_rec = g.n_rec
    subsets = [S for m in (1, 2) for S in itertools.combinations(range(n_rec), m)]
    strat_id = [(multiplicity_partition(D),) for D in cases]
    zf, af, adeq_rate = {}, {}, {}
    for t, (T, _, _) in TRANSFORMS.items():
        z = {p: [] for p in PIS}
        for D in cases:
            R0 = T(D); RS = [T(E_S(D, set(S), "source")) for S in subsets]
            for p, (f, _, _) in PIS.items():
                a = f(R0); z[p].append([a == f(r) for r in RS])
        zf[t] = z
        keys = [rep_key(T(D)) for D in cases]
        af[t] = {}
        for q, (qf, _, _) in QS.items():
            fib = collections.defaultdict(set)
            for k, D in zip(keys, cases): fib[k].add(qf(D))
            af[t][q] = [len(fib[k]) == 1 for k in keys]
            adeq_rate[(t, q)] = sum(af[t][q]) / len(cases)

    ADM, _ = grid()
    n_inf_pairs = 0; cells_with_k = collections.Counter(); cells = 0; excluded = 0
    for pr in ADM:
        p, q = pr["Pi"], pr["Q"]
        for t in TRANSFORMS:
            ok, _, _, _ = admissible_triple(p, q, t)
            if not ok: excluded += 1; continue
            cells += 1
            S_ = collections.defaultdict(collections.Counter)
            for ci in range(len(cases)):
                a = af[t][q][ci]
                for si, Sx in enumerate(subsets):
                    z = zf[t][p][ci][si]
                    S_[(strat_id[ci], len(Sx))]["Z+A" if z and a else "Z+I" if z else
                                               "NZ+A" if a else "NZ+I"] += 1
            k = 0
            for c in S_.values():
                n_ = sum(c.values())
                if n_ < MIN_STRATUM_N: continue
                if (c["Z+A"]+c["Z+I"]) == 0 or (c["NZ+A"]+c["NZ+I"]) == 0: continue
                if (c["Z+A"]+c["NZ+A"]) == 0 or (c["Z+I"]+c["NZ+I"]) == 0: continue
                k += 1
            n_inf_pairs += k; cells_with_k[k] += 1
    ar = list(adeq_rate.values())
    return {"regime": regime, "n_rec": n_rec, "n_admissible_cells": cells,
            "n_excluded": excluded, "n_informative_cell_stratum_pairs": n_inf_pairs,
            "cells_with_0_strata": cells_with_k[0],
            "cells_with_ge3_strata": sum(v for k, v in cells_with_k.items() if k >= 3),
            "mean_informative_strata_per_cell": round(n_inf_pairs / cells, 3) if cells else 0,
            "adequacy_min": round(min(ar), 4), "adequacy_max": round(max(ar), 4),
            "adequacy_saturated": sum(1 for x in ar if x > 0.99 or x < 0.01),
            "n_adequacy_cells": len(ar),
            "redundancy_dist": {str(k): round(v/len(cases), 4) for k, v in
                                sorted(collections.Counter(redundancy(D) for D in cases).items())}}

if __name__ == "__main__":
    out = []
    for r in REGIMES:
        t0 = time.time(); res = evaluate(r); res["secs"] = round(time.time()-t0, 1)
        out.append(res)
        print(f"{r:18s} n_rec={res['n_rec']}  cells={res['n_admissible_cells']:3d} "
              f"excl={res['n_excluded']:2d}  INFORMATIVE(cell,stratum)={res['n_informative_cell_stratum_pairs']:4d}  "
              f"dead_cells={res['cells_with_0_strata']:3d}  cells>=3strata={res['cells_with_ge3_strata']:3d}  "
              f"adeq[{res['adequacy_min']:.2f},{res['adequacy_max']:.2f}] "
              f"sat={res['adequacy_saturated']}/{res['n_adequacy_cells']}  ({res['secs']}s)", flush=True)
    json.dump(out, open(os.path.join(os.path.dirname(os.path.dirname(
        os.path.abspath(__file__))), "results", "calibration.json"), "w"), indent=1)
