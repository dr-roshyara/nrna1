#!/usr/bin/env python3
"""
KSME-15 -- Synthetic Validation Suite (SYNTHETIC, exact/exhaustive).

Three independent finite test systems with known ground truth, per the
commission's Phase 17 ("do not overfit to Track-B/Lane-B"):

  S1 -- pure deterministic transition system, no aliasing, no congruence
        violation. Ground truth: behavioral partition == observational
        partition == structural-equality partition (all coincide).
  S2 -- system with OBSERVATIONAL ALIASING: two states are structurally
        distinct but produce identical observations under every reachable
        operation sequence. Ground truth: behavioral partition is STRICTLY
        COARSER than structural equality.
  S3 -- system where observational equivalence is NOT a congruence: two
        states agree on all direct observations, but a transition applied
        to each produces post-states that DISAGREE. Ground truth: the
        naive observational partition is not congruent; BSE's behavioral
        partition (which refines past this) must be strictly finer than
        the naive observational one, and BSE.congruence_test(naive
        observation-equality) must fail with a real counterexample.

Every model here is SYNTHETIC. Tier: SYNTHETIC throughout. No Track-A/B/
Lane-B state shape, transition, or numeric result used or referenced.
"""
import json
from bse import Regime, component_minimality


# ============================================================ S1: no aliasing, no congruence issue
def build_S1():
    # E = Z/6, T = +1 mod 6 ("inc"), O = value mod 6 (identity observation)
    E = list(range(6))
    T = {"inc": (lambda e, c: (e + c) % 6, [1])}
    O = {"val": lambda e: e}
    return Regime(E, T, O, H=6, name="S1")


def validate_S1():
    r = build_S1()
    bp = r.behavioral_partition()
    op = r.observational_partition()
    ground_truth_classes = 6  # every state observationally and behaviorally distinct
    return {
        "system": "S1 (no aliasing)",
        "expected_n_classes": ground_truth_classes,
        "observational_n_classes": len(op),
        "behavioral_n_classes": bp["n_classes"],
        "observational_matches_ground_truth": len(op) == ground_truth_classes,
        "behavioral_matches_ground_truth": bp["n_classes"] == ground_truth_classes,
        "observational_equals_behavioral": len(op) == bp["n_classes"],
    }


# ============================================================ S2: observational aliasing
def build_S2():
    # E = {0,1,2,3}; states 0 and 2 are structurally distinct but produce the
    # SAME observation under every operation sequence (a genuine hidden twin).
    # inc: +1 mod 4 on the "public" value; O reveals value mod 2 only (a real
    # information-losing observation -- 0/2 alias, 1/3 alias).
    E = [0, 1, 2, 3]
    T = {"inc": (lambda e, c: (e + c) % 4, [1])}
    O = {"parity": lambda e: e % 2}
    return Regime(E, T, O, H=4, name="S2")


def validate_S2():
    r = build_S2()
    bp = r.behavioral_partition()
    struct_classes = 4  # 0,1,2,3 all structurally distinct
    ground_truth_behavioral = 2  # {0,2} and {1,3} -- parity is invariant under +1 mod 4? check
    # NOTE: +1 mod 4 changes parity every step, so parity alone does NOT collapse into a
    # stable 2-class behavioral partition under repeated +1 -- verify by exact computation,
    # do not assert the "expected" figure without checking it.
    classes_desc = sorted(sorted(c) for c in bp["classes"])
    return {
        "system": "S2 (candidate observational aliasing)",
        "structural_classes": struct_classes,
        "behavioral_n_classes": bp["n_classes"],
        "behavioral_classes": classes_desc,
        "note": ("parity is preserved as a STATE property only if the operation doesn't "
                 "flip it; +1 mod 4 flips parity each step, so this system's true "
                 "behavioral partition is computed exactly below, not assumed"),
    }


def build_S2b():
    # A genuine aliasing example: E={0,1,2,3}; op "noop"; O reveals only e%2.
    # No transition ever changes state, so behavior = pure observation.
    # Ground truth: exactly 2 behavioral classes, {0,2} and {1,3}.
    E = [0, 1, 2, 3]
    T = {"noop": (lambda e, c: e, [None])}
    O = {"parity": lambda e: e % 2}
    return Regime(E, T, O, H=1, name="S2b")


def validate_S2b():
    r = build_S2b()
    bp = r.behavioral_partition()
    classes_desc = sorted(sorted(c) for c in bp["classes"])
    expected = sorted([sorted([0, 2]), sorted([1, 3])])
    return {
        "system": "S2b (genuine observational aliasing, no-op transitions)",
        "behavioral_n_classes": bp["n_classes"],
        "behavioral_classes": classes_desc,
        "expected_classes": expected,
        "matches_ground_truth": classes_desc == expected,
        "structural_classes": 4,
        "behavioral_strictly_coarser_than_structural": bp["n_classes"] < 4,
    }


# ============================================================ S3: observational equiv. not a congruence
# E = {A,B,C,D}. direct_obs(A)=direct_obs(B)="same" (A,B look identical pre-transition);
# direct_obs(C)="X", direct_obs(D)="Y" (X != Y -- C,D look DIFFERENT). Transition "step":
# A->C, B->D, C->C, D->D (idempotent). So F=direct_obs has F(A)=F(B) but
# F(step(A))=F(C)="X" != F(step(B))=F(D)="Y" -- a genuine congruence violation with a
# real counterexample, not merely asserted.
def build_S3():
    E = ["A", "B", "C", "D"]

    def step(e, c):
        return {"A": "C", "B": "D", "C": "C", "D": "D"}[e]

    T = {"step": (step, [None])}

    def direct_obs(e):
        return {"A": "same", "B": "same", "C": "X", "D": "Y"}[e]

    O = {"direct": direct_obs}
    return Regime(E, T, O, H=2, name="S3")


def validate_S3():
    r = build_S3()
    naive_partition = r.observational_partition()

    def F_naive(e):
        return {"A": "same", "B": "same", "C": "X", "D": "Y"}[e]

    is_congruence, cert = r.congruence_test(F_naive, name="naive_direct_observation")

    bp = r.behavioral_partition()
    classes_desc = sorted(sorted(c) for c in bp["classes"])
    expected_behavioral = sorted([["A"], ["B"], ["C"], ["D"]])

    return {
        "system": "S3 (observational equivalence is NOT a congruence)",
        "naive_observational_classes": sorted(sorted(c) for c in naive_partition),
        "naive_is_congruence": is_congruence,
        "expected_naive_is_congruence": False,
        "matches_ground_truth_on_congruence": is_congruence == False,
        "counterexample": cert.to_dict() if cert else None,
        "true_behavioral_classes": classes_desc,
        "expected_behavioral_classes": expected_behavioral,
        "matches_ground_truth_on_partition": classes_desc == expected_behavioral,
        "behavioral_strictly_finer_than_naive_observational": bp["n_classes"] > len(naive_partition),
    }


def main():
    results = {
        "S1": validate_S1(),
        "S2_exploratory": validate_S2(),
        "S2b_ground_truth": validate_S2b(),
        "S3": validate_S3(),
    }
    print(json.dumps(results, indent=2, default=str))
    with open("ksme15_synthetic_results.json", "w") as f:
        json.dump(results, f, indent=2, default=str)


if __name__ == "__main__":
    main()
