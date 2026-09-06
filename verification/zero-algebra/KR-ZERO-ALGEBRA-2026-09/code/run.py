#!/usr/bin/env python3
"""KR-ZERO-ALGEBRA-2026-09 runner."""
import json, os, sys, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import experiments as E
from experiments import SEED, N
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
WIT  = os.path.join(ROOT, "witnesses"); os.makedirs(WIT, exist_ok=True)
META = dict(experiment_id="KR-ZERO-ALGEBRA-2026-09", theory="v1.2 UNCHANGED",
            theory_v13=False, kernel="NOT SELECTED", ratified="nothing",
            base_seed=SEED, cases_per_family=N,
            date=datetime.date.today().isoformat(), python=platform.python_version())
SUITE = [("E0_vacuity", E.E0_vacuity), ("E1_path_a_vs_b", E.E1_path_a_vs_path_b),
         ("E2_idempotence", E.E2_idempotence), ("E3_order_independence", E.E3_order_independence),
         ("E4_fixed_point", E.E4_fixed_point), ("E6_elimination_order", E.E6_elimination_order),
         ("E7_contract_sensitivity", E.E7_contract_sensitivity),
         ("E8_boundary_preservation", E.E8_boundary_preservation),
         ("E9_group_zero", E.E9_group_zero), ("E10_reference", E.E10_reference_relativity),
         ("E11_invariant_vs_zero", E.E11_invariant_vs_zero), ("E12_remainder", E.E12_remainder),
         ("E13_regimes", E.E13_reasoning_regimes)]
if __name__ == "__main__":
    allres = {}
    for n, fn in SUITE:
        o = fn()
        w = o.pop("witnesses", None)
        for k in ("case_I_witnesses","case_J_witnesses"):
            if k in o: json.dump(o[k], open(f"{WIT}/{n}_{k}.json","w"), indent=1, default=str)
        if w: json.dump(w, open(f"{WIT}/{n}_witnesses.json","w"), indent=1, default=str)
        allres[n] = o
        print("ran", n)
    json.dump(dict(_meta=META, **allres),
              open(os.path.join(ROOT, "property-results.json"), "w"), indent=1, default=str)
    json.dump({"base_seed": SEED, "per_family_offsets": {n: SEED+i for i,(n,_) in enumerate(SUITE)},
               "cases_per_family": N, "generator": "generators.corpus(n, seed)",
               "deterministic": True},
              open(os.path.join(ROOT, "seeds.json"), "w"), indent=1)
    print("\nDONE -> property-results.json, seeds.json, witnesses/")
