#!/usr/bin/env python3
"""KR-ZERO-GROUP-2026-09 runner."""
import json, os, sys, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import experiments as E
from experiments import SEED, N, MAXN
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
WIT  = os.path.join(ROOT, "witnesses"); os.makedirs(WIT, exist_ok=True)
META = dict(experiment_id="KR-ZERO-GROUP-2026-09", theory="v1.2 UNCHANGED", theory_v13=False,
            kernel="NOT SELECTED", ratified="nothing", algebra="none introduced",
            base_seed=SEED, cases_per_family=N, powerset_bound=MAXN,
            definition_status="[PROP], not [DEF]",
            date=datetime.date.today().isoformat(), python=platform.python_version())
SUITE = [("G1_closure_properties", E.G1_closure_properties),
         ("G2_minimal_structure",  E.G2_minimal_structure),
         ("G3_generated_by_minimal", E.G3_generated_by_minimal),
         ("G4_matroid_circuits",   E.G4_matroid_circuits),
         ("G5_pairwise_determination", E.G5_pairwise_determination),
         ("G6_subset_vs_sequential", E.G6_subset_vs_sequential),
         ("G7_overlapping",        E.G7_overlapping_zero_subsets),
         ("G8_redundancy_vs_cancellation", E.G8_redundancy_vs_cancellation),
         ("G9_regime_adjudication", E.G9_regime_adjudication),
         ("G10_contract_class_robustness", E.G10_contract_class_robustness)]
if __name__ == "__main__":
    allres = {}
    for n, fn in SUITE:
        o = fn(); w = o.pop("witnesses", None)
        if w: json.dump(w, open(f"{WIT}/{n}_witnesses.json","w"), indent=1, default=str)
        allres[n] = o; print("ran", n, flush=True)
    json.dump(dict(_meta=META, **allres),
              open(os.path.join(ROOT,"property-results.json"),"w"), indent=1, default=str)
    json.dump({"base_seed":SEED, "per_family_offsets":{n:SEED+i+1 for i,(n,_) in enumerate(SUITE)},
               "cases_per_family":N, "powerset_bound":MAXN, "deterministic":True},
              open(os.path.join(ROOT,"seeds.json"),"w"), indent=1)
    print("\nDONE.")
