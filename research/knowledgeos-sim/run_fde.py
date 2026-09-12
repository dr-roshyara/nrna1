#!/usr/bin/env python3
"""KR-CONTR-FDE-2026-09 — representation-comparison harness. [EXP]
Experimental package. Nothing here is a KnowledgeOS primitive."""
import json, os, sys, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from kos12.fde import tests as T, EXPERIMENT_ID
R = "results/fde"; os.makedirs(R, exist_ok=True)
META = dict(experiment_id=EXPERIMENT_ID, theory="v1.2 UNCHANGED", theory_v13=False,
            production_boundary="research/knowledgeos-sim only; app/ untouched",
            adopted="nothing", date=datetime.date.today().isoformat(),
            python=platform.python_version(), deterministic=True)
SUITE = [("T1_distinction_preservation", T.DistinctionPreservationTest),
         ("T2_collapse",                 T.CollapseTest),
         ("T3_contr",                    T.ContrTest),
         ("T4_zero",                     T.ZeroTest),
         ("T5_invariant",                T.InvariantTest),
         ("G_E12_reason_guard",          T.E12_guard),
         ("G_E13_repr_equivalence",      T.E13_representation_equivalence),
         ("P_composition_probe",         T.CompositionProbe)]
if __name__ == "__main__":
    for n, fn in SUITE:
        o = fn()
        json.dump(dict(_meta=META, **o), open(f"{R}/{n}.json","w"), indent=1, default=str)
        print("wrote", n)
    print("DONE.")
