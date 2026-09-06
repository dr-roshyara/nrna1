#!/usr/bin/env python3
r"""KR-BRIDGE-02 AUDIT — adversarial checks against my own experiment.

A1  Is the PROVENANCE MAP correct? It is hand-written. If one entry is wrong the
    level-aware gate is wrong, and the gate is what produced the verdict.
    -> tested EMPIRICALLY by perturbation, not by reading the code.
A2  Effect homogeneity across strata (the MH estimator assumes it).
A3  Are the "negligible" cells statistically distinguishable from zero at all?
A4  How much of the grid was actually informative, and why was the rest not?
"""
import sys, os, json, math, random, collections, itertools
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from dataclasses import replace
from bridge import Case, Rec, VALUES, SOURCES, TAGS, TIMES, N_REC, TRANSFORMS
from observables import PIS, QS, grid
from provenance import PROVENANCE, PI_OUTPUT_READS
from run_bridge02 import gen, SEED_TRAIN, ROOT

# ---------------------------------------------------------------- A1 provenance, empirically
def observed_provenance(n=4000, seed=7):
    """For each T and each OUTPUT field f, which ORIGINAL fields can change f?
    Perturb one original field at a time and watch the output field's value column."""
    rng = random.Random(seed)
    obs = {t: {f: set() for f in ("v","src","tag","t","rank")} for t in TRANSFORMS}
    for _ in range(n):
        D = Case(tuple(Rec(v=rng.choice(VALUES), src=rng.choice(SOURCES),
                           tag=rng.choice(TAGS), t=rng.choice(TIMES))
                       for _ in range(N_REC)))
        for src_field in ("v","src","tag","t"):
            i = rng.randrange(N_REC)
            pool = {"v":VALUES,"src":SOURCES,"tag":TAGS,"t":TIMES}[src_field]
            newv = rng.choice([x for x in pool if x != getattr(D.recs[i], src_field)])
            D2 = Case(tuple(replace(r, **{src_field: newv}) if j == i else r
                            for j, r in enumerate(D.recs)))
            for t, (T, _, _) in TRANSFORMS.items():
                R1, R2 = T(D), T(D2)
                for f in ("v","src","tag","t","rank"):
                    c1 = sorted([getattr(r,f) for r in R1.recs], key=lambda x:(x is None,x))
                    c2 = sorted([getattr(r,f) for r in R2.recs], key=lambda x:(x is None,x))
                    if c1 != c2: obs[t][f].add(src_field)
    return obs

def check_provenance():
    obs = observed_provenance()
    rows, ok = [], True
    for t in TRANSFORMS:
        for f in ("v","src","tag","t","rank"):
            declared = PROVENANCE[t].get(f, set())
            observed  = obs[t][f]
            # SOUNDNESS direction that matters: nothing observed may be UNdeclared,
            # otherwise the gate under-reports Pi's effective reads.
            missing = observed - declared
            if missing: ok = False
            if declared or observed:
                rows.append({"T": t, "output_field": f,
                             "declared": sorted(declared), "observed": sorted(observed),
                             "UNDECLARED_DEPENDENCY": sorted(missing),
                             "over_declared_conservative": sorted(declared - observed)})
    return {"PASS": ok, "rule": ("an observed dependency that is NOT declared makes the "
            "gate unsound; an over-declaration is merely conservative"), "rows": rows}

# ---------------------------------------------------------------- A2/A3 per-stratum spread + CI
def rd_ci(cells):
    a,b,c,d = (cells.get(k,0) for k in ("Z+A","Z+I","NZ+A","NZ+I"))
    if a+b == 0 or c+d == 0: return None
    p1, p2 = a/(a+b), c/(c+d)
    se = math.sqrt(p1*(1-p1)/(a+b) + p2*(1-p2)/(c+d))
    return {"RD": round(p1-p2,6), "se": round(se,6),
            "ci95": [round(p1-p2-1.96*se,6), round(p1-p2+1.96*se,6)],
            "excludes_zero": abs(p1-p2) > 1.96*se}

def homogeneity(adj):
    out = {}
    for k, v in adj["cells"].items():
        if v.get("status") not in ("SURVIVES STRATIFICATION",): continue
        strat = v["train"]["informative_strata"]
        rds = [s["RD"] for s in strat]
        cis = [rd_ci(s["cells"]) for s in strat]
        out[k] = {"n_strata": len(strat), "per_stratum_RD": rds,
                  "spread": round(max(rds)-min(rds), 5) if rds else None,
                  "signs_agree": len({r > 0 for r in rds if r != 0}) <= 1,
                  "per_stratum_ci_excludes_zero": [c["excludes_zero"] if c else None
                                                   for c in cis],
                  "MH_RD_train": v["train"]["MH_risk_difference"],
                  "MH_RD_test":  v["test"]["MH_risk_difference"]}
    return out

if __name__ == "__main__":
    adj = json.load(open(f"{ROOT}/results/adjudication02.json"))
    prov = check_provenance()
    hom  = homogeneity(adj)
    n_signs_disagree = sum(1 for v in hom.values() if not v["signs_agree"])
    n_no_ci = sum(1 for v in hom.values()
                  if not any(x for x in v["per_stratum_ci_excludes_zero"] if x))
    out = {"A1_provenance_map_verified_empirically": prov,
           "A2_A3_effect_homogeneity_and_CIs": hom,
           "A2_summary": {"n_surviving_cells_examined": len(hom),
                          "n_with_disagreeing_stratum_signs": n_signs_disagree,
                          "n_with_NO_stratum_CI_excluding_zero": n_no_ci,
                          "reading": ("a surviving cell whose per-stratum RDs change sign, "
                                      "or whose every stratum CI covers zero, is NOT a "
                                      "conditional bridge -- it is noise that survived a "
                                      "sign-only replication rule")},
           "A4_grid_informativeness": {
              "n_cells": adj["summary"]["n_cells"],
              "n_no_informative_stratum": adj["summary"]["n_no_informative_stratum"],
              "n_excluded_gate_leak": adj["summary"]["n_excluded_gate_leak"],
              "n_actually_searched": (adj["summary"]["n_cells"]
                                      - adj["summary"]["n_no_informative_stratum"]
                                      - adj["summary"]["n_excluded_gate_leak"])}}
    json.dump(out, open(f"{ROOT}/results/audit02.json","w"), indent=1, default=str)

    print(f"A1 provenance map verified empirically : "
          f"{'PASS' if prov['PASS'] else 'FAIL'}")
    for r in prov["rows"]:
        if r["UNDECLARED_DEPENDENCY"]:
            print(f"    FAIL {r['T']}.{r['output_field']} undeclared {r['UNDECLARED_DEPENDENCY']}")
    print(f"    (T_D_relational.rank declared={PROVENANCE['T_D_relational']['rank']} "
          f"observed={sorted(next(x for x in prov['rows'] if x['T']=='T_D_relational' and x['output_field']=='rank')['observed'])})")
    print(f"\nA2/A3 surviving cells examined        : {len(hom)}")
    print(f"    with disagreeing per-stratum signs : {n_signs_disagree}")
    print(f"    with NO stratum CI excluding zero  : {n_no_ci}")
    print(f"\nA4 cells actually searched            : {out['A4_grid_informativeness']['n_actually_searched']} of 112")
    print("\nsurviving cells, per-stratum detail:")
    for k, v in sorted(hom.items(), key=lambda x: -abs(x[1]["MH_RD_train"] or 0)):
        print(f"  {k:56s} MH={v['MH_RD_train']:+.5f} strataRD={v['per_stratum_RD']} "
              f"signs_agree={v['signs_agree']} ci_excl_zero={v['per_stratum_ci_excludes_zero']}")
