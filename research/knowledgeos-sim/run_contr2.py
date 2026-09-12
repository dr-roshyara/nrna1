#!/usr/bin/env python3
"""KR-CONTR-2026-09 — Contradiction, Evaluation Domain and Zero. [EXP]
Protocol authored by the KnowledgeOS research programme; this lane is the executor."""
import json, os, sys, datetime, platform
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from kos12 import contr2 as k
R = "results/contr2"; os.makedirs(R, exist_ok=True)
META = dict(experiment_id="KR-CONTR-2026-09", protocol_author="KnowledgeOS research programme",
            executor="Claude Code CLI (this lane)", theory="v1.2 UNCHANGED", theory_v13=False,
            fr001="frozen, not reopened", factivity_track="independent; not depended on",
            date=datetime.date.today().isoformat(), python=platform.python_version())
TESTS = [("E1_adequacy_and_minimum_domain", k.E1_adequacy_and_minimum_domain),
         ("E2_collapse_witnesses",          k.E2_collapse_witnesses),
         ("E3_zero_interaction",            k.E3_zero_interaction),
         ("E4_minimality",                  k.E4_minimality),
         ("E5_internal_structure",          k.E5_contradiction_internal_structure),
         ("E6_vacuity_guards",              k.E6_vacuity_guards),
         ("E7_stress",                      k.E7_stress),
         ("E8_kernel_analysis",             k.E8_kernel_analysis),
         ("E9_invariants",                  k.E9_invariants),
         ("E10_cardinality_vs_semantics",   k.E10_cardinality_is_not_the_obstruction),
         ("E11_required_set_sensitivity",   k.E11_required_set_sensitivity),
         ("E12_reason_expressivity_guard",  k.E12_reason_expressivity_guard),
         ("E13_reason_decomposition",       k.E13_reason_decomposition)]
if __name__ == "__main__":
    for n, fn in TESTS:
        o = fn()
        json.dump(dict(_meta=META, **o), open(f"{R}/{n}.json","w"), indent=1, default=str)
        print(f"wrote {n}")
    print("DONE.")
