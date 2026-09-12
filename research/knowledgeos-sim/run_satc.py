#!/usr/bin/env python3
"""KR-SIM-2026-09-02-D — Sat_c Semantic Closure Experiment (Phases A, B, C). [EXP]"""
import json, os, sys, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from kos12.satc_spec import SPEC, CLASSES, EXECUTABLE_NOW, BLOCKED, ALL_U_REASONS
from kos12.phaseB import run as runB
from kos12.phaseC import run as runC
R="results"; os.makedirs(R, exist_ok=True)
META=dict(experiment_id="KR-SIM-2026-09-02-D",
          model="Sat_c Formal Candidate Specification v0.1",
          date=datetime.date.today().isoformat(), python=platform.python_version(),
          note="Phase A is SPECIFICATION, not implementation. Phase B tests the "
               "specification against the four deterministic cases already found. "
               "No new randomized trials.")
def dump(n,o):
    json.dump(dict(_meta=META, **o), open(f"{R}/{n}.json","w"), indent=1, default=str)
    print("wrote", f"{R}/{n}.json")
if __name__=="__main__":
    dump("satc_phaseA_spec", dict(spec=SPEC, classes=CLASSES,
         executable_now=EXECUTABLE_NOW, blocked=BLOCKED, u_reasons=ALL_U_REASONS))
    dump("satc_phaseB_adversarial", runB())
    dump("satc_phaseC_zero", runC())
    print("\nDONE.")
