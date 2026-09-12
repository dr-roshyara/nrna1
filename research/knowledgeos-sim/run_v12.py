#!/usr/bin/env python3
"""KR-SIM-2026-09-02-B — KnowledgeOS Theory v1.2 experiment. [EXP]"""
import json, os, sys, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from kos12 import EXPERIMENT_ID, MODEL, THEORY, STATUS, STATUSES
from kos12.experiment import (E1_zero_readings, E2_equality, E3_carryover,
                              E4_observation_open, E5_zero_ontology)
R = "results"; os.makedirs(R, exist_ok=True)
META = dict(experiment_id=EXPERIMENT_ID, model=MODEL, theory=THEORY, status=STATUS,
            date=datetime.date.today().isoformat(), python=platform.python_version(),
            seed=20260902, status_vocabulary=STATUSES)
def dump(n, o):
    json.dump(dict(_meta=META, **o), open(f"{R}/{n}.json","w"), indent=1, default=str)
    print("wrote", f"{R}/{n}.json")
if __name__ == "__main__":
    dump("v12_E1_zero_readings",   E1_zero_readings())
    dump("v12_E2_equality",        E2_equality())
    dump("v12_E3_carryover",       E3_carryover())
    dump("v12_E4_observation_open",E4_observation_open())
    dump("v12_E5_zero_ontology",   E5_zero_ontology())
    print("\nDONE.")
