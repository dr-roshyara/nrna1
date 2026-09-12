#!/usr/bin/env python3
"""KR-REITER-2026-09 — mathematical audit (mandate S17). [EXP]"""
import json, os, sys, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from kos12 import reiter as R
OUT = "results/reiter"; os.makedirs(OUT, exist_ok=True)
META = dict(experiment_id="KR-REITER-2026-09", source="Reiter 2001, Knowledge in Action",
            source_role="[EXT] independent formal-methods source -- NEVER an authority",
            theory="v1.2 UNCHANGED", adopted="nothing", deterministic=True,
            date=datetime.date.today().isoformat(), python=platform.python_version())
S = [("A1_history_state_collision", R.A1_history_state_collision),
     ("A2_successor_state_axiom",   R.A2_successor_state_axiom),
     ("A3_regression_progression",  R.A3_regression_progression_equivalence),
     ("A4_poss_executability",      R.A4_poss_and_executability),
     ("A5_observational_equivalence",R.A5_observational_equivalence)]
if __name__ == "__main__":
    for n, fn in S:
        o = fn(); json.dump(dict(_meta=META, **o), open(f"{OUT}/{n}.json","w"), indent=1, default=str)
        print("wrote", n)
    print("DONE.")
