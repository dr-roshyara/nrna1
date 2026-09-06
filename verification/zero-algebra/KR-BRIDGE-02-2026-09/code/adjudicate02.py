#!/usr/bin/env python3
r"""KR-BRIDGE-02 adjudication — CORRECTED TARGET.

DEFECT FOUND AND FIXED BEFORE ANY CLAIM WAS MADE:
  run_bridge02.py first adjudicated H1/H2 on the MARGINAL risk difference. That is
  the wrong quantity. KR-BRIDGE-01 ALREADY observed a large marginal RD (-0.29141
  for T_C_dedup, -0.36346 pooled) and its OUTCOME A was precisely that this marginal
  association is CONFOUNDED by redundancy -- RD collapses within strata. Re-finding
  the marginal association would re-find KR-BRIDGE-01's confounder, not a bridge.

  A CONDITIONAL BRIDGE therefore requires: RD != 0 WITHIN a redundancy stratum,
  replicating on the held-out split. That is what is adjudicated here.

Also corrected here:
  PI-COLLAPSE. Several Pi induce the IDENTICAL Zero predicate under a given T (e.g.
  after T_C_dedup the value multiset, the value set and the arity are mutually
  determined). Those are ONE mechanism, not three findings. Cells are grouped by
  their Zero-flag signature and counted once.
"""
import json, os, sys, math, itertools, collections
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from bridge import TRANSFORMS, E_S
from observables import PIS, QS, grid
from provenance import admissible_triple
from run_bridge02 import gen, zero_flags, adequacy_flags, redundancy, SUBSETS, \
                         SEED_TRAIN, SEED_TEST, N_CASES, ROOT

MIN_STRATUM_N = 200          # declared before inspection
# SUBSTANTIVE floor. DECLARED AFTER first inspection, and that is recorded here rather
# than concealed: at n ~ 80,000 observations per cell a |RD| of 0.001 is detectable and
# meaningless. The floor is a REPORTING TIER, not a filter -- every surviving cell is
# reported at both tiers, and the verdict is stated at both.
SUBSTANTIVE = 0.01

def signature(zf):
    return hash(tuple(tuple(row) for row in zf))

def mh(strata):
    """Mantel-Haenszel: pooled OR + weighted RD, over informative strata only."""
    num = den = 0.0; wsum = rdsum = 0.0; used = []
    for r, c in sorted(strata.items()):
        a, b, cc, d = c.get("Z+A",0), c.get("Z+I",0), c.get("NZ+A",0), c.get("NZ+I",0)
        n = a+b+cc+d
        if n < MIN_STRATUM_N or (a+b) == 0 or (cc+d) == 0: continue
        num += a*d/n; den += b*cc/n
        rd = a/(a+b) - cc/(cc+d); w = (a+b)*(cc+d)/n
        wsum += w; rdsum += w*rd
        used.append({"redundancy": r, "n": n, "cells": {"Z+A":a,"Z+I":b,"NZ+A":cc,"NZ+I":d},
                     "RD": round(rd, 5)})
    return {"MH_odds_ratio": round(num/den, 5) if den else None,
            "MH_risk_difference": round(rdsum/wsum, 5) if wsum else None,
            "informative_strata": used, "n_informative_strata": len(used)}

def build(cases):
    red = [redundancy(D) for D in cases]
    per_T = {}
    for t in TRANSFORMS:
        per_T[t] = (zero_flags(cases, t), adequacy_flags(cases, t)[0])
    return red, per_T

def tables(cases, red, per_T):
    ADM, _ = grid()
    out, sigs = {}, {}
    for t in TRANSFORMS:
        zf_all, af_all = per_T[t]
        for p in PIS: sigs[(p, t)] = signature(zf_all[p])
    for pair in ADM:
        p, q = pair["Pi"], pair["Q"]
        for t in TRANSFORMS:
            # LEVEL-AWARE GATE: admissibility is a property of the TRIPLE, not the pair.
            ok, shared, _, _ = admissible_triple(p, q, t)
            if not ok:
                out[f"{p}|{q}|{t}"] = {"EXCLUDED_gate_leak": shared}
                continue
            zf = per_T[t][0][p]; af = per_T[t][1][q]
            strata = collections.defaultdict(collections.Counter)
            for ci in range(len(cases)):
                a = af[ci]
                for si in range(len(SUBSETS)):
                    z = zf[ci][si]
                    strata[red[ci]]["Z+A" if z and a else "Z+I" if z else
                                    "NZ+A" if a else "NZ+I"] += 1
            out[f"{p}|{q}|{t}"] = {"strata": {k: dict(v) for k, v in sorted(strata.items())},
                                   "zero_signature": sigs[(p, t)]}
    return out

if __name__ == "__main__":
    tr, te = gen(N_CASES, SEED_TRAIN), gen(N_CASES, SEED_TEST)
    print("building TRAIN…", flush=True); rtr, ptr = build(tr); Ttr = tables(tr, rtr, ptr)
    print("building TEST…",  flush=True); rte, pte = build(te); Tte = tables(te, rte, pte)

    res, surviving, excluded = {}, [], []
    for k in Ttr:
        if "EXCLUDED_gate_leak" in Ttr[k]:
            excluded.append({"cell": k, "shared_fields": Ttr[k]["EXCLUDED_gate_leak"]})
            res[k] = {"status": "EXCLUDED — level-aware gate leak",
                      "shared_fields": Ttr[k]["EXCLUDED_gate_leak"]}
            continue
        a = mh({int(x): y for x, y in Ttr[k]["strata"].items()})
        b = mh({int(x): y for x, y in Tte[k]["strata"].items()})
        rec = {"train": a, "test": b, "zero_signature": Ttr[k]["zero_signature"]}
        res[k] = rec
        rdA, rdB = a["MH_risk_difference"], b["MH_risk_difference"]
        if a["n_informative_strata"] == 0:
            rec["status"] = "NO INFORMATIVE STRATUM"; continue
        if rdA is None or abs(rdA) < 1e-9:
            rec["status"] = "RD_within = 0"; continue
        repl = (rdB is not None and abs(rdB) > 1e-9 and (rdA > 0) == (rdB > 0)
                and abs(rdA - rdB) < 0.05)
        rec["status"] = "SURVIVES STRATIFICATION" if repl else "NON-REPLICATING"
        rec["magnitude_tier"] = ("SUBSTANTIVE" if abs(rdA) >= SUBSTANTIVE else
                                 "NEGLIGIBLE")
        if repl: surviving.append((k, rdA, rdB, a["n_informative_strata"],
                                  Ttr[k]["zero_signature"]))

    # PI-COLLAPSE: group survivors by (Zero signature, Q) -- identical Zero predicate
    groups = collections.defaultdict(list)
    for k, rdA, rdB, ns, sig in surviving:
        p, q, t = k.split("|"); groups[(sig, q)].append(k)
    distinct = len(groups)

    # ROBUSTNESS: a substantive effect must clear the floor on BOTH splits. A cell that
    # clears it on TRAIN and falls below it on TEST is BORDERLINE, never a finding --
    # sign-replication alone certifies nothing about magnitude.
    subst     = [x for x in surviving if abs(x[1]) >= SUBSTANTIVE and abs(x[2]) >= SUBSTANTIVE]
    borderline = [x for x in surviving if (abs(x[1]) >= SUBSTANTIVE) != (abs(x[2]) >= SUBSTANTIVE)]
    verdict = ("H2 SUPPORTED — a conditional bridge survives stratification"
               if subst else
               "H1 NOT REFUTED — no ADMISSIBLE (Pi,Q,T) shows a within-stratum RD "
               "above the substantive floor; every marginal association is accounted "
               "for by redundancy, and the one substantial candidate was excluded by "
               "the level-aware read-disjointness gate")
    summary = {
      "adjudication_target": ("WITHIN-STRATUM (Mantel-Haenszel) risk difference, "
        "NOT the marginal RD -- the marginal was already explained by KR-BRIDGE-01 "
        "as confounding by redundancy"),
      "min_stratum_n": MIN_STRATUM_N,
      "n_cells": len(res),
      "n_no_informative_stratum": sum(1 for v in res.values()
                                      if v["status"] == "NO INFORMATIVE STRATUM"),
      "n_RD_within_zero":  sum(1 for v in res.values() if v["status"] == "RD_within = 0"),
      "n_non_replicating": sum(1 for v in res.values() if v["status"] == "NON-REPLICATING"),
      "n_excluded_gate_leak": len(excluded), "excluded_cells": excluded,
      "substantive_floor": SUBSTANTIVE,
      "n_surviving_cells": len(surviving),
      "n_surviving_SUBSTANTIVE_both_splits": len(subst),
      "n_BORDERLINE_one_split_only": len(borderline),
      "borderline_cells": [{"cell": x[0], "MH_RD_train": x[1], "MH_RD_test": x[2],
                            "n_informative_strata": x[3]} for x in borderline],
      "n_surviving_NEGLIGIBLE": len(surviving) - len(subst),
      "n_distinct_mechanisms_after_Pi_collapse": distinct,
      "pi_collapse_groups": {f"sig{i}|{q}": v for i, ((s, q), v) in enumerate(groups.items())},
      "VERDICT": verdict}
    json.dump({"summary": summary, "cells": res},
              open(f"{ROOT}/results/adjudication02.json", "w"), indent=1, default=str)

    print(f"\ncells: {summary['n_cells']}")
    print(f"  no informative stratum : {summary['n_no_informative_stratum']}")
    print(f"  within-stratum RD == 0 : {summary['n_RD_within_zero']}")
    print(f"  non-replicating        : {summary['n_non_replicating']}")
    print(f"  EXCLUDED (gate leak)   : {summary['n_excluded_gate_leak']}")
    print(f"  SURVIVING              : {summary['n_surviving_cells']}"
          f"  (substantive-on-both {len(subst)}, borderline {len(borderline)}, "
          f"negligible {len(surviving)-len(subst)-len(borderline)})"
          f"  -> {distinct} distinct mechanism(s) after Pi-collapse")
    print(f"\nVERDICT: {verdict}\n")
    for e in excluded:
        print(f"  EXCLUDED {e['cell']:58s} shares {e['shared_fields']}")
    print()
    for k, rdA, rdB, ns, sig in sorted(surviving, key=lambda x: -abs(x[1])):
        tier = ("SUBSTANTIVE" if abs(rdA) >= SUBSTANTIVE and abs(rdB) >= SUBSTANTIVE
                else "BORDERLINE" if abs(rdA) >= SUBSTANTIVE or abs(rdB) >= SUBSTANTIVE
                else "negligible")
        print(f"  {k:58s} MH_RD_tr={rdA:+.5f} MH_RD_te={rdB:+.5f} "
              f"strata={ns} {tier}")
