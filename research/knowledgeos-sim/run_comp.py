#!/usr/bin/env python3
"""KR-COMP-2026-09 — composition x frame qualification x standing aggregation. [EXP]"""
import json, os, sys, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from kos12 import comp
R = "results/comp"; os.makedirs(R, exist_ok=True)
META = dict(experiment_id="KR-COMP-2026-09", theory="v1.2 UNCHANGED", theory_v13=False,
            production_boundary="app/ untouched", adopted="nothing",
            date=datetime.date.today().isoformat(), python=platform.python_version(),
            deterministic=True, seed=None)
SUITE = [("S1_sweep", comp.sweep), ("S2_criterion_pressure", comp.criterion_pressure),
         ("S3_coupling_evidence", comp.coupling_evidence),
         ("S4_robustness_without_C5", comp.robustness_without_C5),
         ("S5_what_determines_success", comp.what_determines_success),
         ("SEP1_separating_witness", comp.SEP1_separating_witness),
         ("SEP2_order_invariance", comp.SEP2_order_invariance),
         ("SEP3_criteria_with_witnesses", comp.SEP3_criteria_with_witnesses_added),
         ("SEP4_frame_refinement", comp.SEP4_frame_refinement_invariance)]
if __name__ == "__main__":
    for n, fn in SUITE:
        o = fn()
        json.dump(dict(_meta=META, **o), open(f"{R}/{n}.json","w"), indent=1, default=str)
        print("wrote", n)
    print("DONE.")
