#!/usr/bin/env python3
r"""KR-BRIDGE-03 — the bridge question re-run with MORE INFORMATIVE STRATA.

TWO regimes are run, because the calibration showed the two candidate levers are NOT
equally effective and the comparison is the point:

  R1_wider_records   n_rec=6, 4 values, UNIFORM        -- redundancy distribution UNCHANGED
  R8_balanced_n6v4   n_rec=6, 4 values, BALANCED       -- redundancy distribution FLATTENED

STRATUM = (multiplicity PARTITION, |S|)   -- finer than the redundancy COUNT used by
KR-BRIDGE-02. Finer strata are both a better confounder control and, because Zero depends
on WHICH record is eliminated, still informative.

Adjudication target, floor and gate are UNCHANGED from KR-BRIDGE-02:
  - within-stratum Mantel-Haenszel RD (never the marginal RD)
  - substantive iff |RD| >= 0.01 on BOTH splits
  - level-aware read-disjointness gate on the TRIPLE (Pi, Q, T)
Nothing about the adjudication was retuned for this generator. Only the generator changed.
"""
import sys, os, json, math, itertools, collections, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from bridge import TRANSFORMS, E_S
from observables import PIS, QS, grid
from provenance import admissible_triple
from generator import make_generator, multiplicity_partition, redundancy

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
SEED_TRAIN, SEED_TEST = 20260904, 88020260904
N_CASES = 8000
MIN_STRATUM_N = 200
SUBSTANTIVE   = 0.01

def rep_key(R): return tuple((r.v, r.src, r.tag, r.rank) for r in R.recs)

def build(cases, subsets):
    zf, af = {}, {}
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
    return zf, af

def tables(cases, subsets, zf, af):
    strat = [multiplicity_partition(D) for D in cases]
    ADM, _ = grid(); out = {}
    for pr in ADM:
        p, q = pr["Pi"], pr["Q"]
        for t in TRANSFORMS:
            ok, shared, _, _ = admissible_triple(p, q, t)
            key = f"{p}|{q}|{t}"
            if not ok: out[key] = {"EXCLUDED_gate_leak": shared}; continue
            S_ = collections.defaultdict(collections.Counter)
            for ci in range(len(cases)):
                a = af[t][q][ci]
                for si, Sx in enumerate(subsets):
                    z = zf[t][p][ci][si]
                    S_[(strat[ci], len(Sx))]["Z+A" if z and a else "Z+I" if z else
                                             "NZ+A" if a else "NZ+I"] += 1
            out[key] = {"strata": {f"{k[0]}|S{k[1]}": dict(v) for k, v in S_.items()},
                        "zero_signature": hash(tuple(tuple(r) for r in zf[t][p]))}
    return out

def mh(strata):
    num = den = wsum = rdsum = 0.0; used = []
    for r, c in sorted(strata.items()):
        a, b, cc, d = (c.get(k, 0) for k in ("Z+A", "Z+I", "NZ+A", "NZ+I"))
        n = a + b + cc + d
        if n < MIN_STRATUM_N or (a + b) == 0 or (cc + d) == 0: continue
        num += a*d/n; den += b*cc/n
        rd = a/(a+b) - cc/(cc+d); w = (a+b)*(cc+d)/n
        wsum += w; rdsum += w*rd
        p1, p2 = a/(a+b), cc/(cc+d)
        se = math.sqrt(p1*(1-p1)/(a+b) + p2*(1-p2)/(cc+d))
        used.append({"stratum": r, "n": n, "RD": round(rd, 6),
                     "ci95": [round(rd-1.96*se, 6), round(rd+1.96*se, 6)],
                     "excludes_zero": abs(rd) > 1.96*se})
    return {"MH_odds_ratio": round(num/den, 5) if den else None,
            "MH_risk_difference": round(rdsum/wsum, 6) if wsum else None,
            "n_informative_strata": len(used),
            "n_strata_ci_excludes_zero": sum(1 for u in used if u["excludes_zero"]),
            "informative_strata": used}

def run(regime):
    g = make_generator(regime); n_rec = g.n_rec
    subsets = [S for m in (1, 2) for S in itertools.combinations(range(n_rec), m)]
    tr, te = g(N_CASES, SEED_TRAIN), g(N_CASES, SEED_TEST)
    Ttr = tables(tr, subsets, *build(tr, subsets))
    Tte = tables(te, subsets, *build(te, subsets))
    res, surviving, excluded = {}, [], []
    for k in Ttr:
        if "EXCLUDED_gate_leak" in Ttr[k]:
            excluded.append(k); res[k] = {"status": "EXCLUDED — gate leak",
                                          "shared": Ttr[k]["EXCLUDED_gate_leak"]}; continue
        a, b = mh(Ttr[k]["strata"]), mh(Tte[k]["strata"])
        rec = {"train": a, "test": b, "zero_signature": Ttr[k]["zero_signature"]}
        res[k] = rec
        rdA, rdB = a["MH_risk_difference"], b["MH_risk_difference"]
        if a["n_informative_strata"] == 0: rec["status"] = "NO INFORMATIVE STRATUM"; continue
        if rdA is None or abs(rdA) < 1e-9: rec["status"] = "RD_within = 0"; continue
        repl = (rdB is not None and abs(rdB) > 1e-9 and (rdA > 0) == (rdB > 0)
                and abs(rdA - rdB) < 0.05)
        rec["status"] = "SURVIVES STRATIFICATION" if repl else "NON-REPLICATING"
        if repl: surviving.append((k, rdA, rdB, a["n_informative_strata"],
                                  a["n_strata_ci_excludes_zero"], Ttr[k]["zero_signature"]))
    subst = [x for x in surviving if abs(x[1]) >= SUBSTANTIVE and abs(x[2]) >= SUBSTANTIVE]
    border = [x for x in surviving if (abs(x[1]) >= SUBSTANTIVE) != (abs(x[2]) >= SUBSTANTIVE)]
    groups = collections.defaultdict(list)
    for k, *_r in surviving: groups[(_r[4], k.split("|")[1])].append(k)
    tot_inf = sum(v["train"]["n_informative_strata"] for v in res.values()
                  if isinstance(v.get("train"), dict))
    summary = {"regime": regime, "n_rec": n_rec, "n_cases": N_CASES,
      "stratum_definition": "(multiplicity partition, |S|)",
      "n_cells": len(res), "n_excluded_gate_leak": len(excluded),
      "n_no_informative_stratum": sum(1 for v in res.values()
                                      if v.get("status") == "NO INFORMATIVE STRATUM"),
      "n_RD_within_zero": sum(1 for v in res.values() if v.get("status") == "RD_within = 0"),
      "n_non_replicating": sum(1 for v in res.values() if v.get("status") == "NON-REPLICATING"),
      "n_surviving": len(surviving), "n_substantive_both_splits": len(subst),
      "n_borderline": len(border),
      "total_informative_strata_train": tot_inf,
      "n_distinct_mechanisms_after_Pi_collapse": len(groups),
      "substantive_cells": [{"cell": x[0], "MH_RD_train": x[1], "MH_RD_test": x[2],
                             "n_strata": x[3], "n_strata_ci_excl_zero": x[4]} for x in subst],
      "borderline_cells": [{"cell": x[0], "MH_RD_train": x[1], "MH_RD_test": x[2],
                            "n_strata": x[3], "n_strata_ci_excl_zero": x[4]} for x in border],
      "VERDICT": ("H2 SUPPORTED — a conditional bridge survives finer stratification"
                  if subst else
                  "H1 NOT REFUTED — no admissible (Pi,Q,T) shows a within-stratum RD above "
                  "the substantive floor on both splits, at finer strata and higher power")}
    return summary, res

if __name__ == "__main__":
    allout = {}
    for regime in ("R1_wider_records", "R8_balanced_n6v4"):
        print(f"running {regime}…", flush=True)
        s, r = run(regime); allout[regime] = {"summary": s, "cells": r}
        print(f"  cells={s['n_cells']} excl={s['n_excluded_gate_leak']} "
              f"dead={s['n_no_informative_stratum']} RD0={s['n_RD_within_zero']} "
              f"nonrepl={s['n_non_replicating']} surviving={s['n_surviving']} "
              f"(subst {s['n_substantive_both_splits']}, borderline {s['n_borderline']})")
        print(f"  TOTAL informative strata (train) = {s['total_informative_strata_train']}")
        print(f"  VERDICT: {s['VERDICT']}")
        for c in s["substantive_cells"]:
            print(f"    SUBSTANTIVE {c['cell']:56s} RD_tr={c['MH_RD_train']:+.5f} "
                  f"RD_te={c['MH_RD_test']:+.5f} strata={c['n_strata']} "
                  f"ci_excl0={c['n_strata_ci_excl_zero']}")
        for c in s["borderline_cells"]:
            print(f"    borderline  {c['cell']:56s} RD_tr={c['MH_RD_train']:+.5f} "
                  f"RD_te={c['MH_RD_test']:+.5f} strata={c['n_strata']}")
    META = dict(experiment_id="KR-BRIDGE-03-GENERATOR-REGIME-2026-09",
                extends="KR-BRIDGE-02-ZERO-PRESERVATION-PI-Q-2026-09",
                theory="v1.2 FROZEN", kernel="NOT SELECTED",
                date=datetime.date.today().isoformat(), python=platform.python_version())
    json.dump({"_meta": META, "regimes": allout},
              open(f"{ROOT}/results/bridge03.json", "w"), indent=1, default=str)
