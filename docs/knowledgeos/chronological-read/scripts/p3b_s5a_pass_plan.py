#!/usr/bin/env python3
"""P3b S5a pass plan (plan §G, §H, §I; deliverable O-12). Generated FROM the committed engine's constants, so the frozen
plan and the implementation cannot drift. Infrastructure; G-LOG-0041.

  p3b_s5a_pass_plan.py            writes audit-p3b/S5A-PASS-PLAN.json ({header: §19.5, body}); refuses to overwrite

The body is the O-12 candidate: O-12a (specification) plus the O-25 control-availability hash (O-12b content). Plan §O
requires the pass plan to be **human-approved before the first batch**; this file is PROPOSED until a governance
entry approves it by its output_sha256.
"""
import importlib.util
import json
import os
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))


def _load(name):
    spec = importlib.util.spec_from_file_location(name, os.path.join(_HERE, name + ".py"))
    m = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(m)
    return m


c = _load("p3b_s5_common")
G = _load("p3b_s5a_generators")
K = _load("p3b_s5a_controls")
CELLS = _load("p3b_s5a_cells")
STAB = _load("p3b_s5_stability")
AUD = _load("p3b_s5_audit_sample")
OUT = "audit-p3b/S5A-PASS-PLAN.json"
AVAIL = "audit-p3b/S5A-CONTROL-AVAILABILITY.json"

DEFINITIONS = {   # plan §G.1 (v2.3.2), verbatim
    "STATE-MACHINE-CANDIDATE": "states plus transitions with stated triggers across members",
    "ORDER-OR-LATTICE-CANDIDATE": "a stated preorder, partial order or lattice relation over members' elements",
    "EQUIVALENCE-CLASS": "members stated to be the same concept or relation: a sameness claim between members",
    "COMPOSITIONAL-STRUCTURE": "members built from parts by a stated operation (product, sum, composition)",
    "PROBABILISTIC-STRUCTURE": "a probability, measure or random-variable structure over members' elements",
    "MEASUREMENT-SCALE": "a nominal, ordinal, interval or ratio scale claim for a quantity in the members",
    "LOGICAL-ENTAILMENT": "a stated inference, entailment or implication chain between members' statements",
    "DDD-BOUNDARY-CANDIDATE": "a bounded-context or aggregate boundary with stated ownership",
    "IDENTITY-STATE-CONFLATION": "each member exhibits the object-scale pattern of §1D, §9D and §13.7: the object's identity is conflated with one of its state representations",
    "SHARED-INVARIANT": "one invariant stated to hold across members",
    "SHARED-TRANSITION": "the same transition type occurring in members",
    "MISSING-TRANSITION": "a transition implied by members' structure but absent (absence-based)",
    "MISSING-INVARIANT": "an invariant implied but absent (absence-based)",
    "DEPENDENCY-CHAIN": "members linked by stated dependencies into a chain or DAG",
}
ISC_ROUTING = ("A claim that two or more members are states or versions of one object, or are the same object, is a "
               "sameness claim between members: recorded as EQUIVALENCE-CLASS (for P3a-judged pairs only as "
               "VERDICT-EVIDENCE-CONFLICT, RC-13), never as IDENTITY-STATE-CONFLATION.")


def main():
    c.assert_sealed()
    c.verify_frozen()
    if tuple(DEFINITIONS) != tuple(CELLS.CLASSES):
        raise c.S5Error("vocabulary in the engine differs from plan §G.1")
    cells = CELLS.registered_cells()
    if len(cells) != 64 or CELLS.K_REGISTERED != 64:
        raise c.S5Error("registered cells must be K = 64 (G-LOG-0041)")
    avail = json.load(open(os.path.join(c.CR, AVAIL), encoding="utf-8"))
    if not avail["header"].get("script_committed_unmodified"):
        raise c.S5Error("O-25 output was not produced by a committed script")
    body = {
        "status": "APPROVED SUBJECT TO M4-A (G-LOG-0045); bound by the output_sha256 recorded in the governance log",
        "plan_sha256": c.PLAN_SHA256, "protocol_sha256": c.PROTOCOL_SHA256, "K": 64,
        "vocabulary": [{"class": k, "definition": v, "identity_class": k in CELLS.IDENTITY_CLASSES,
                        "absence_based": k in CELLS.ABSENCE_CLASSES} for k, v in DEFINITIONS.items()],
        "isc_routing_rule": ISC_ROUTING,
        "descriptive_cells": {g: list(v) for g, v in CELLS.DESCRIPTIVE.items()},
        "registered_cells": cells,
        "registration_rule": "structurally impossible cells unregistered; empty, one-candidate and underpowered cells registered with p = 1; tested cells contribute exact raw p (plan §G.2)",
        "generators": {g: {"index": G.GEN_INDEX[g], "version": G.VERSIONS[g], "defining_property": G.DEFINING.get(g),
                           "inputs_ai_produced": G.AI_INPUT.get(g), "population_rule": G.POPULATION_RULE.get(g),
                           "budget": "full list" if g in G.FULL_LIST_GENERATORS else G.BUDGET,
                           "budget_seed_int": G.budget_seed(g) if g in G.BUDGETED_GENERATORS else None}
                       for g in G.GENERATORS},
        "self_derived": list(G.SELF_DERIVED), "omitted": ["G-TIMELINE-SIM"],
        "p2a_kinds": list(G.P2A_KINDS), "filtered_kinds": list(G.FILTERED_KINDS),
        "type_sim_table": {"version": G.TYPE_TABLE_VERSION, "sha256": G.type_table_sha256(),
                           "classes": list(G.TYPE_CLASSES)},
        "arities": list(G.ARITIES), "r": K.R, "band_variables": list(K.BAND_VARS),
        "relaxation_order": list(K.RELAX_ORDER), "never_relaxed": ["arity", K.FORMAL_VAR + " (G-TYPE-SIM)"],
        "band_definitions": {"row": "1 / 2-3 / 4-9 / >=10 distinct rows", "pair": "0 / 1 / >=2 discovery P3a pairs",
                             "prov": "all-PRIMARY / mixed / none-PRIMARY over row files",
                             "span": "<1d / 1-7d / >7d between row files' best dates (epoch MTIME values as UTC dates)",
                             "degree": "max row-file degree over the 2,452 discovery labels: <=5 / 6-10 / >10",
                             "set_band": "sorted multiset of member bands per variable"},
        "h18": {"degree_cap": G.DEGREE_CAP, "files_above_cap": list(G.H18_FILES_ABOVE_CAP)},
        "test": {"name": "label-cluster permutation test (frozen §9E.2 item 6 alternative)",
                 "p_value": "exact: P(sum_b X_b >= T), X_b ~ Hypergeometric(N_b, K_b, n_C,b), by convolution; no Monte-Carlo, no seed",
                 "blocks": "label component x arity (x has-formal-rows for G-TYPE-SIM), built after symmetric removal",
                 "gate": "TESTED iff m >= 20 and x_min >= 3 and m_inf >= 1 and blinding FULL; else p = 1",
                 "multiplicity": "BH q = 0.10 over the 64 registered cells; BY sensitivity only"},
        "bcdd": {"level": "q/K = 0.10/64 and 0.05", "root": CELLS.BCDD_ROOT, "grid": str(CELLS.BCDD_GRID),
                 "tables_per_step": CELLS.BCDD_TABLES, "power": CELLS.BCDD_POWER},
        "seed_roots": {"generator_budget": G.BUDGET_ROOT, "blind_shuffle": K.BLIND_ROOT, "controls": K.CONTROL_ROOT,
                       "bcdd": CELLS.BCDD_ROOT, "reanalysis": 20260929, "test_pass": 20260927, "rerun": 20260928,
                       "bootstrap": 20260930, "audit": 20261100},
        "o25_control_availability": {"artifact": AVAIL, "output_sha256": avail["header"]["output_sha256"]},
        "frozen_parameters": {   # G-LOG-0045 items 4-9, read from the engine constants
            "type_sim_keyword_table": G.TYPE_TABLE_VERSION,
            "control_draw": {"r": K.R, "relaxation": "only when the matched pool is exhausted, in relaxation_order",
                             "one_disjoint_control": "kept, r_s = 1", "none": "NO-CONTROL-AVAILABLE",
                             "adaptation": "none"},
            "bootstrap_B": STAB.BOOT_B,
            "reanalysis_n": f"ceil({STAB.REANALYSIS_NUM}/{STAB.REANALYSIS_DEN} x N) = ceil(0.20 x N)",
            "audit_floor": f"max({AUD.MIN_RECORDS}, floor({AUD.SHARE} x N))",
            "audit_run_id": "OA####-R2"},
        "blinding": {"m4a": "in every blind unit with a G-SHARED-GROUP role (candidate or control), evidence pointers "
                            "to a source cited by two or more of the unit's members are stripped (frozen §9E.2 item 4); "
                            "enforced at build time by check_m4a; the §9E.2 item 6 token rule is unchanged",
                     "strip_generators": list(K.SHARED_SOURCE_STRIP_GENERATORS),
                     "m4r1": {"annex": "audit-p3b/20260925_0905_s5a-m4r1-blinding-annex.md",
                              "approved_identity": dict(K.M4R1_APPROVED),
                              "rule": "every unit with a candidate or control role of any generator: withheld (symmetric "
                                      "removal WITHHELD-M4R1 in every cell containing it) if a member has no pointer after "
                                      "M4-A, otherwise exactly one pointer per member, the lowest (source_id, row_line)",
                              "canonicalization": K.M4R1_CANONICALIZATION,
                              "binding": "the identity (with withheld_units, withheld_sets_sha256) in the blind and reveal "
                                         "headers and the PRE-ANALYSIS and LINK6 pass-record entries; any mismatch refuses",
                              "pre_release_gate": {
                                  "registered": "G-LOG-0045 (human ruling)",
                                  "condition": "for every generator with a testable S5a cell, the best measured "
                                               "reviewer-visible feature AUC on the actual S5a pass inputs is <= 0.55",
                                  "threshold": 0.55,
                                  "testable": "blinding_level FULL and a registered cell with pre-reveal m >= 20 (the "
                                              "outcome-free part of the registered test gate)",
                                  "features": ["any_empty", "min_ptrs", "total_ptrs", "distinct_sources", "shared_src"],
                                  "procedure": "scripts/p3b_s5a_m4_residual.py gate on the blind and reveal files as "
                                               "written (bound to the pass record), after the pass snapshot, before "
                                               "release, no outcomes; PASS releases the blind file; FAIL stops for a "
                                               "human ruling; never tuned, populations and cells never modified to pass",
                                  "nature": "blinding-integrity gate, not a hypothesis-test significance threshold"},
                              "limitation": "removes the measured pointer-derived role cues; does not establish that no "
                                            "role cue exists (label-name semantics and anchors unmeasured)"}},
        "h19": {"seal_id": c.SEAL_ID, "state": "SEALED", "s5c": "PROHIBITED (P3B-ESC-0001, G-LOG-0045 ruling H-19 = A); "
                "requires a separate human governance decision", "holdout_in_production_path": False},
    }
    txt = c.canon(body)
    out = os.path.join(c.CR, OUT)
    if os.path.exists(out):
        print(f"REFUSED: {OUT} exists", file=sys.stderr)
        return 2
    hdr = c.header(__file__, [AVAIL, c.PROTOCOL, c.PLAN], {"generated_from": ["p3b_s5a_generators", "p3b_s5a_controls", "p3b_s5a_cells"]},
                   txt, {"engine_blobs": {m: c.git("hash-object", os.path.join(_HERE, m + ".py"))
                                           for m in ("p3b_s5a_generators", "p3b_s5a_controls", "p3b_s5a_cells",
                                                     "p3b_s5_stability", "p3b_s5_audit_sample")}})
    with open(out, "w", encoding="utf-8") as f:
        f.write(c.canon({"header": hdr, "body": body}) + "\n")
    print(f"S5A PASS PLAN: K={len(cells)} cells; output_sha256 {hdr['output_sha256'][:16]} (PROPOSED; human approval required)")
    c.assert_sealed()
    return 0


if __name__ == "__main__":
    sys.exit(main())
