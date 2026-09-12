#!/usr/bin/env python3
"""KR-CONTR-2026-09 — separating the three contradiction models. [EXP]"""
import json, os, sys, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from kos12 import contr
R = "results/contr"; os.makedirs(R, exist_ok=True)
META = dict(experiment_id="KR-CONTR-2026-09", theory="v1.2 (unchanged)",
            factivity="DECIDED R1: K_t -> A_t", status="[EXP]",
            date=datetime.date.today().isoformat(), python=platform.python_version(),
            deterministic=True, seed=None)
TESTS = [("C1_base_readings",      contr.C1_base_readings),
         ("C2_extended_readings",  contr.C2_extended_readings),
         ("C3_value_multiset",     contr.C3_value_multiset_identity),
         ("C4_pair_separation",    contr.C4_pair_separation),
         ("C5_fourth_value",       contr.C5_fourth_value_necessity),
         ("C6_d0_control",         contr.C6_d0_control),
         ("C7_bucket_sweep",       contr.C7_bucket_sweep),
         ("C8_masking",           contr.C8_masking),
         ("C9_isomorphism",       contr.C9_m3_m4_isomorphism),
         ("C10_composition",      contr.C10_composition_algebra)]
if __name__ == "__main__":
    for n, fn in TESTS:
        o = fn()
        json.dump(dict(_meta=META, **o), open(f"{R}/{n}.json", "w"), indent=1, default=str)
        print(f"\n{'='*70}\n{n}\n{'='*70}")
        print(json.dumps(o, indent=1, default=str)[:2200])
    print("\nDONE.")
