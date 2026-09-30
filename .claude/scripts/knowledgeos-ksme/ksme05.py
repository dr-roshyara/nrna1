#!/usr/bin/env python3
"""
BC-02.22 / KSME-05 -- Exact Robust Behavioral Kernel, Minimality and
Candidate Representation.

Continues directly from KSME-04. Corrects one real error the user found in
KSME-04's writeup: the 13-of-256-model result was mislabeled an "upper
bound" on the full-model K_R's class count. It is the reverse -- testing
FEWER models imposes FEWER constraints on ~R, so it can only UNDER-split
(produce AT MOST as many classes as the full computation). K_R^(13) is a
LOWER bound on K_R^(256), not an upper bound. Fixed here by computing the
TRUE full-model result exactly (see below), which makes the bound question
moot for this run -- but the terminology correction is recorded in the
report, not just silently fixed in code.

KEY OPTIMIZATION (this script's real contribution, not asserted but
EMPIRICALLY VERIFIED below before being relied on): so_model.py's own
op_add/op_remove/op_withdraw/op_reject each accept a `variant` parameter
but never read it -- only op_revise/op_transform/op_supersede/op_merge
branch on "blind" vs "sensitive". This means the nominal |M_T|=2^8=256
model space has EXACTLY 2^4=16 behaviorally distinct members (verified
empirically over the full 208-state SPACE: 0 blind/sensitive mismatches
for Add/Remove/Withdraw/Reject across every seed state and argument,
against >0 mismatches for each of the other four). Computing "all 256
models" therefore means computing these 16, once, exactly -- not sampling,
not estimating, not approximating.

Everything reused from ksme04.py (E, OPERATIONS, refine_to_fixpoint, the
sort-order bug fix) is imported, not reimplemented, per the commissioning's
own "do not reinvent the existing real model" instruction.
"""
from __future__ import annotations
import sys, os, itertools, json, time
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import ksme04
from ksme04 import (OPERATIONS, OP_NAMES, ABSTRACTIONS, SPACE, args_for,
                     apply_op, reachable_closure, obs_partition,
                     refine_to_fixpoint, class_id_map, F_content_status,
                     F_assertion, F_AR, State, Obj)

VARIANT_SENSITIVE_OPS = ["Revise", "Transform", "Supersede", "Merge"]
VARIANT_INVARIANT_OPS = ["Add", "Remove", "Withdraw", "Reject"]

def verify_model_space_reduction():
    """Empirical proof (not an assumption) that only 4 of 8 operations are
    variant-sensitive, over the full 208-state SPACE, both variants,
    every generated argument -- the basis for reducing 256 -> 16."""
    results = {}
    for op in OP_NAMES:
        fn, _cite = OPERATIONS[op]
        checked = mismatches = 0
        for s in SPACE:
            for a in args_for(op, s):
                checked += 1
                if fn(s, a, "blind") != fn(s, a, "sensitive"):
                    mismatches += 1
        results[op] = {"checked": checked, "mismatches": mismatches,
                        "variant_sensitive": mismatches > 0}
    claimed_invariant = set(VARIANT_INVARIANT_OPS)
    claimed_sensitive = set(VARIANT_SENSITIVE_OPS)
    actual_invariant = {op for op, r in results.items() if not r["variant_sensitive"]}
    actual_sensitive = {op for op, r in results.items() if r["variant_sensitive"]}
    assert claimed_invariant == actual_invariant, (claimed_invariant, actual_invariant)
    assert claimed_sensitive == actual_sensitive, (claimed_sensitive, actual_sensitive)
    return results

def distinct_models():
    """The TRUE 2^4=16 behaviorally distinct models. Invariant ops fixed at
    'blind' (provably irrelevant which value -- verified above)."""
    base = {op: "blind" for op in OP_NAMES}
    for bits in itertools.product(("blind", "sensitive"), repeat=len(VARIANT_SENSITIVE_OPS)):
        m = dict(base)
        for op, v in zip(VARIANT_SENSITIVE_OPS, bits):
            m[op] = v
        yield m

def model_key(model):
    return tuple(model[op] for op in VARIANT_SENSITIVE_OPS)

def main():
    t_start = time.time()
    report = {"code_version": "ksme05-0.1.0",
              "reused_from": {"ksme04.py": "post-sort-fix version (this session)"}}

    verify = verify_model_space_reduction()
    report["model_space_reduction_proof"] = {
        "nominal_M_T_size": 2 ** len(OP_NAMES),
        "true_distinct_M_T_size": 2 ** len(VARIANT_SENSITIVE_OPS),
        "variant_sensitive_ops": VARIANT_SENSITIVE_OPS,
        "variant_invariant_ops": VARIANT_INVARIANT_OPS,
        "per_op_verification": verify,
        "note": "Empirically verified (not assumed): every blind/sensitive "
                "pair for Add/Remove/Withdraw/Reject produced IDENTICAL "
                "results over all 208 seed states and every generated "
                "argument (0 mismatches each); Revise/Transform/Supersede/"
                "Merge each showed >0 mismatches. So the 256 nominal model "
                "assignments collapse to exactly 16 behaviorally distinct "
                "ones -- computing 'all 256' means computing these 16.",
    }

    E = reachable_closure(SPACE)
    report["closure"] = {"seed_space_size": len(SPACE), "reachable_closure_size": len(E)}

    models = list(distinct_models())
    report["models_tested"] = {"count": len(models), "exact_not_sampled": True}

    O_TESTED = {"F2 content+status (thin)": F_content_status,
                "F3 id+content+status (no prov)": F_assertion,
                "F4 K=(A,R) [TERMINAL]": F_AR}

    per_O = {}
    for oname, O in O_TESTED.items():
        t_o = time.time()
        K_O_classes = obs_partition(E, O)
        s2c_by_model = {}
        class_counts = {}
        # incremental partition intersection (Optimization D): track the
        # running combined-signature partition and its class count as each
        # model is added, producing the model-space sensitivity curve
        # (Part 13) as a side effect of the same loop, not a second pass.
        running_sig = {s: () for s in E}
        sensitivity_curve = []
        for i, model in enumerate(models):
            key = model_key(model)
            classes, rounds = refine_to_fixpoint(E, model, O)
            m = class_id_map(classes)
            s2c_by_model[key] = m
            class_counts[key] = len(classes)
            for s in E:
                running_sig[s] = running_sig[s] + (m[s],)
            n_running_classes = len(set(running_sig.values()))
            sensitivity_curve.append({
                "step": i + 1, "model_key": key,
                "this_model_class_count": len(classes),
                "running_K_R_class_count": n_running_classes,
                "rounds": rounds,
            })

        K_R_classes_count = sensitivity_curve[-1]["running_K_R_class_count"]

        # counterexample + robust-vs-model-dependent certificates
        sig_groups = {}
        for s in E:
            sig_groups.setdefault(running_sig[s], []).append(s)

        # model-dependence certificate: an O-equal pair distinguished by
        # SOME but not ALL models (i.e. genuinely model-dependent, not
        # robustly necessary, not robustly irrelevant)
        model_dependence_cert = None
        robust_necessary_cert = None
        for cls in K_O_classes:
            if len(cls) < 2: continue
            for s1, s2 in itertools.combinations(cls, 2):
                per_model_agree = [s2c_by_model[model_key(m)][s1] == s2c_by_model[model_key(m)][s2]
                                    for m in models]
                if not all(per_model_agree) and any(per_model_agree) and model_dependence_cert is None:
                    model_dependence_cert = {
                        "s1": str(sorted(map(str, s1.objs))), "s2": str(sorted(map(str, s2.objs))),
                        "agrees_under_n_of_16_models": sum(per_model_agree),
                        "conclusion": "model-dependent: some admissible models merge this pair, others split it",
                    }
                if not any(per_model_agree) and robust_necessary_cert is None:
                    robust_necessary_cert = {
                        "s1": str(sorted(map(str, s1.objs))), "s2": str(sorted(map(str, s2.objs))),
                        "conclusion": "robustly distinguished: EVERY admissible model splits this O-equal pair",
                    }
                if model_dependence_cert and robust_necessary_cert:
                    break
            if model_dependence_cert and robust_necessary_cert:
                break

        per_O[oname] = {
            "K_O_classes": len(K_O_classes),
            "per_model_class_counts": {str(k): v for k, v in class_counts.items()},
            "K_R_exact_classes": K_R_classes_count,
            "K_R_strictly_finer_than_K_O": K_R_classes_count > len(K_O_classes),
            "sensitivity_curve": sensitivity_curve,
            "model_dependence_certificate": model_dependence_cert,
            "robust_necessary_certificate": robust_necessary_cert,
            "wall_time_seconds": round(time.time() - t_o, 1),
        }

    report["per_observation_result"] = per_O

    # F4 representation test: does F4's own partition (so_model.py's
    # candidate K=(A,R) abstraction) COINCIDE with the exact K_R computed
    # for F4 as the starting observation? (F4 is both an O choice above and
    # the candidate representation under test -- if K_O(F4) == K_R(F4),
    # F4 is already robust; the gap between them is F4's shortfall.)
    f4 = per_O["F4 K=(A,R) [TERMINAL]"]
    report["F4_representation_test"] = {
        "K_O(F4)_classes": f4["K_O_classes"],
        "K_R(F4)_classes": f4["K_R_exact_classes"],
        "disposition": ("ISOMORPHIC (F4 already robust)" if f4["K_O_classes"] == f4["K_R_exact_classes"]
                         else "COARSENS (F4 loses robustly-necessary distinctions -- see certificate)"),
        "note": "F4 is so_model.py's own 'K=(A,R) [TERMINAL]' abstraction -- the same "
                "notation already registered as KO-007b/KO-009 in BC-02.14's K-object "
                "registry (non-arbitrary correspondence, established prior to this run).",
    }

    report["total_wall_time_seconds"] = round(time.time() - t_start, 1)

    out_path = "/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/e80b6590-f013-40ab-b3b7-5adc71f528e7/scratchpad/results05.json"
    with open(out_path, "w") as f:
        json.dump(report, f, indent=2, default=str)
    print(json.dumps(report, indent=2, default=str))

if __name__ == "__main__":
    main()
