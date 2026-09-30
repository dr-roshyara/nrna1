#!/usr/bin/env python3
"""
KSME-15 -- Minimality Machinery Validation (SYNTHETIC, exact/exhaustive).

Validates component_minimality, partition_minimality, and representation_
minimality against a synthetic system with a KNOWN-CORRECT minimal answer,
constructed so that ground truth is not in doubt:

  State = (a, b, c), each in {0,1}. Only 'a' is behaviorally relevant:
  observation O(state) = a; transition "flip_b" flips b only, "flip_c"
  flips c only (neither touches a or O). So {b} and {c} are behaviorally
  IRRELEVANT by construction, and the unique minimal sufficient component
  set is S* = {'a'}, |S*| = 1.
"""
import json
from bse import Regime, component_minimality, partition_minimality, representation_minimality


def build_system():
    E = [(a, b, c) for a in (0, 1) for b in (0, 1) for c in (0, 1)]
    T = {
        "flip_b": (lambda e, ctx: (e[0], 1 - e[1], e[2]), [None]),
        "flip_c": (lambda e, ctx: (e[0], e[1], 1 - e[2]), [None]),
    }
    O = {"observe_a": lambda e: e[0]}
    return Regime(E, T, O, H=2, name="minimality_test")


def extract(e, component):
    return {"a": e[0], "b": e[1], "c": e[2]}[component]


def main():
    r = build_system()

    # --- component minimality ---
    cm = component_minimality(r.E, ["a", "b", "c"], extract, r)
    component_result = {
        "minimal_S": cm["minimal_S"], "size": cm["size"],
        "expected_S": ("a",), "matches_ground_truth": cm["minimal_S"] == ("a",),
        "n_subsets_tested_before_success": len(cm["all_tested"]),
    }

    # --- partition minimality ---
    pm = partition_minimality(r)
    # ground truth: exactly 2 behavioral classes (a=0 group of 4 states, a=1 group of 4)
    partition_result = {
        "n_classes": pm["n_classes"], "expected_n_classes": 2,
        "matches_ground_truth": pm["n_classes"] == 2,
        "class_sizes": pm["classes_sizes"], "expected_class_sizes": [4, 4],
    }

    # --- representation minimality ---
    def F_a(e):
        return e[0]

    def F_ab(e):
        return (e[0], e[1])

    def F_full(e):
        return e

    def F_wrong(e):
        return e[1]  # only b -- should NOT be sufficient (fails)

    candidates = {"pi_a": F_a, "pi_ab": F_ab, "pi_full": F_full, "pi_b_only": F_wrong}
    cost = {"pi_a": 1, "pi_ab": 2, "pi_full": 3, "pi_b_only": 1}
    rm = representation_minimality(candidates, r, lambda name: cost[name])
    representation_result = {
        "best": rm["best"]["name"] if rm["best"] else None,
        "expected_best": "pi_a",
        "matches_ground_truth": (rm["best"] is not None and rm["best"]["name"] == "pi_a"),
        "pi_b_only_correctly_rejected": next(
            e for e in rm["all_candidates"] if e["name"] == "pi_b_only")["sufficient"] == False,
        "all_candidates": rm["all_candidates"],
    }

    out = {
        "component_minimality": component_result,
        "partition_minimality": partition_result,
        "representation_minimality": representation_result,
        "all_three_notions_validated": (
            component_result["matches_ground_truth"]
            and partition_result["matches_ground_truth"]
            and representation_result["matches_ground_truth"]
            and representation_result["pi_b_only_correctly_rejected"]
        ),
    }
    print(json.dumps(out, indent=2, default=str))
    with open("ksme15_minimality_results.json", "w") as f:
        json.dump(out, f, indent=2, default=str)


if __name__ == "__main__":
    main()
