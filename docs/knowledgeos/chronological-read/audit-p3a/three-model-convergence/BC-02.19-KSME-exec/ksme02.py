#!/usr/bin/env python3
"""
KSME-02 -- Real Behavioral Contract Extraction (BC-02.19 follow-on)

Unlike KSME-01 (BC-02.19), which used a purpose-built SYNTHETIC world, this experiment
grounds D_real / T_real / O_real directly in already-verified, source-cited KnowledgeOS
corpus content:

  Sub-experiment 1 (the main new contribution): S0881's own five-valued Status enum and
  its FOUR real, named, consuming computations (Coverage, EpistemicDebt, Ready,
  CriticalGaps), plus S2377's binary Sat/pi_1 collapse -- extending BC-02.7's HAND-DERIVED
  insufficiency argument (Certificates 1-3) into an EXHAUSTIVE, EXECUTED, quantified
  re-verification, and computing the TRUE oracle-minimal quotient for the first time
  (BC-02.7 never computed it, only showed one candidate's insufficiency).

  Sub-experiment 2: canonical-construction's own EXECUTED bandtest.py NEEDS table
  (re-used verbatim from the actual source file, not reconstructed from memory), used
  honestly for what it is -- confirmed to be an INTENSIONAL/declarative necessity test,
  not an extensional/behavioral one (already established BC-02.17) -- and used here only
  to test K=(A,R)-style representations for INFORMATION LOSS under the real dependency
  structure, not to manufacture a false joint-interaction claim it cannot support.

PROVENANCE DISCIPLINE (per commissioning Section 23): every constant below is tagged.
"""

import itertools, json

CODE_VERSION = "ksme02-0.1.0"

# ---------------------------------------------------------------------------
# SUB-EXPERIMENT 1: S0881 Status_5 x n-requirements -> real named behaviors
# ---------------------------------------------------------------------------
#
# SOURCE-ESTABLISHED (S0881, BC-02.4, full-file read, verbatim formula
# EpistemicGap(K,P) = R(P) - SatisfiedRequirements(K), section 39):
#   Status in {Satisfied, Unsatisfied, Unknown, Conflicted, NotApplicable}   -- 5-valued
#
# SOURCE-ESTABLISHED (S2377, BC-02.3/6/7/13, full-file read):
#   Sat(K_t,r) in {0,1}                                                      -- 2-valued
#   pi_1 : Status_5 -> Sat_2,  pi_1(Satisfied)=1, else 0   (BC-02.6/7's own constructed,
#          non-source-attested collapse -- reused here as the one CANDIDATE projection
#          this whole investigation has repeatedly tested)
#
# DERIVED (this experiment's own formalization of BC-02.7's Certificates 1-3 --
# the NAMES HardSatisfied / ConflictAcceptable / CriticalUncertaintyAcceptable and the
# QUALITATIVE finding "Ready/CriticalGaps need more than {0,1}" are SOURCE-ESTABLISHED
# from BC-02.7; the EXACT per-requirement Boolean formulas below are this experiment's
# OWN reconstruction, not verbatim source quotes -- flagged honestly, not oversold):

STATUS5 = ["Satisfied", "Unsatisfied", "Unknown", "Conflicted", "NotApplicable"]
N_REQ = 3                       # r1 = "hard", r2 = "critical", r3 = ordinary
HARD_IDX = 0
CRITICAL_IDX = 1

def all_status_vectors():
    for combo in itertools.product(STATUS5, repeat=N_REQ):
        yield combo

# --- O_real: the REAL, NAMED S0881 consuming computations -------------------

def Coverage(e):
    """SOURCE-ESTABLISHED shape (S0881/BC-02.7): |Satisfied| / |R(P)|."""
    sat = sum(1 for s in e if s == "Satisfied")
    return sat / N_REQ

def EpistemicDebt_set(e):
    """SOURCE-ESTABLISHED shape (BC-02.4 §39): R(P) - SatisfiedRequirements(K),
    i.e. the SET of unsatisfied requirement indices (NotApplicable excluded, since a
    requirement that does not apply is not a debt -- S0881's own distinction)."""
    return frozenset(i for i, s in enumerate(e) if s not in ("Satisfied", "NotApplicable"))

def Ready(e):
    """
    DERIVED reconstruction of BC-02.7's Certificate 1 (HardSatisfied ^ ConflictAcceptable
    ^ CriticalUncertaintyAcceptable). NOT a verbatim source formula -- the three named
    conjuncts and the qualitative "needs >2 values" finding ARE source-established; this
    exact Boolean wiring is this experiment's own, disclosed reconstruction.
    """
    hard_satisfied = (e[HARD_IDX] == "Satisfied")
    conflict_acceptable = all(s != "Conflicted" for s in e)
    critical_uncertainty_acceptable = (e[CRITICAL_IDX] != "Unknown")
    return hard_satisfied and conflict_acceptable and critical_uncertainty_acceptable

def CriticalGaps_set(e):
    """DERIVED reconstruction: the set of CRITICAL/HARD requirement indices in a
    non-satisfied, non-N/A state (BC-02.7's Certificate 2/3 territory: Unknown and
    Conflicted must remain distinguishable from each other and from Unsatisfied here)."""
    critical_positions = {HARD_IDX, CRITICAL_IDX}
    return frozenset(i for i in critical_positions if e[i] not in ("Satisfied", "NotApplicable"))

O_REAL = {"Coverage": Coverage, "EpistemicDebt_set": EpistemicDebt_set,
          "Ready": Ready, "CriticalGaps_set": CriticalGaps_set}

def real_signature(e):
    return tuple(O_REAL[name](e) for name in sorted(O_REAL))

# --- Candidate representations (rho) ----------------------------------------

def rho_pi1_binary(e):
    """S2377's own binary Sat/pi_1 collapse, applied per-requirement (SOURCE-CLAIMED
    projection direction; the collapse function itself is this investigation's own
    constructible pi_1, per BC-02.6/7 -- reused unchanged, not reinvented)."""
    return tuple(1 if s == "Satisfied" else 0 for s in e)

def rho_three_valued(e):
    """A CANDIDATE intermediate collapse this experiment proposes and tests (not
    source-attested): {Satisfied} / {Unknown,NotApplicable} / {Unsatisfied,Conflicted}.
    Tests whether 3 values (instead of 2 or 5) might already suffice."""
    def band(s):
        if s == "Satisfied": return "OK"
        if s in ("Unknown", "NotApplicable"): return "SOFT"
        return "HARD_FAIL"  # Unsatisfied, Conflicted
    return tuple(band(s) for s in e)

def rho_full_status5(e):
    return e  # the full 5-valued vector, unchanged -- the "full-state" baseline

def evaluate_rep(name, rho, note):
    true_parts = {}
    for e in all_status_vectors():
        true_parts.setdefault(real_signature(e), []).append(e)
    true_part_of = {e: sig for sig, members in true_parts.items() for e in members}
    rho_parts = {}
    for e in all_status_vectors():
        rho_parts.setdefault(rho(e), []).append(e)
    false_equiv = 0
    for key, members in rho_parts.items():
        true_sigs = set(true_part_of[m] for m in members)
        if len(true_sigs) > 1:
            false_equiv += (len(members) - 1)
    sufficiency = (false_equiv == 0)
    return {"representation": name, "sufficiency(no_false_equivalence)": sufficiency,
            "false_equivalence_count": false_equiv, "num_rho_classes": len(rho_parts),
            "num_true_classes": len(true_parts), "note": note}

# --- Which named behaviors specifically does pi_1 fail on? (re-verifies/quantifies
# BC-02.7's Certificates 1-3, exhaustively rather than by hand-picked example) --------

def per_behavior_sufficiency(rho, rho_name):
    out = {}
    for oname, ofunc in O_REAL.items():
        rho_parts = {}
        for e in all_status_vectors():
            rho_parts.setdefault(rho(e), []).append(e)
        false_equiv = 0
        for key, members in rho_parts.items():
            true_vals = set(ofunc(m) for m in members)
            if len(true_vals) > 1:
                false_equiv += (len(members) - 1)
        out[oname] = {"sufficient_for_this_behavior_alone": false_equiv == 0,
                      "false_equivalence_count": false_equiv}
    return out

# ---------------------------------------------------------------------------
# SUB-EXPERIMENT 2: canonical-construction's REAL, EXECUTED NEEDS table
# (verbatim from docs/.../verification/canonical-construction/exec/bandtest.py,
#  re-read directly this pass, NOT reconstructed from memory)
# ---------------------------------------------------------------------------

LOWER = ["Add","Assess","Authorize","Derive","Determine","Promote","Qualify",
         "Reject","Remove","Replay","Revise","Supersede","Validate","Withdraw"]
BAND  = ["Transform","Merge","Split","Reintroduce"]
NEEDS = {
 "Add":        {"A","Dt"},          "Remove":   {"A"},
 "Revise":     {"A","PI","T"},      "Supersede":{"A","R","T"},
 "Reject":     {"A","S"},           "Withdraw": {"A","EL","S"},
 "Promote":    {"A","S"},           "Assess":   {"A","EL","S"},
 "Validate":   {"A","R","EL"},      "Authorize":{"A","S"},
 "Qualify":    {"EL"},              "Determine":{"A","S","PI"},
 "Derive":     {"A","R","PI"},      "Replay":   {"H"},
 "Transform":  {"A","R","PI","T"},  "Merge":    {"A","R","PI","EL"},
 "Split":      {"A","R","PI"},      "Reintroduce":{"A","S","T"},
}
ALL_OPS = LOWER + BAND
ALL_COMPONENTS = sorted(set().union(*NEEDS.values()))

def canonical_construction_analysis():
    """
    HONEST CHARACTERIZATION (per BC-02.17's own finding, reconfirmed here by direct
    inspection of the actual re-read source): this test is INTENSIONAL (a static
    component in NEEDS[op] dependency-table lookup), not EXTENSIONAL (no operation is
    actually executed on two differing states to observe a behavioral divergence). It
    therefore CANNOT support a joint/interaction-relevance claim the way sub-experiment
    1 can -- doing so would require inventing operation semantics beyond what the
    source provides, which this experiment explicitly declines to do.
    What it CAN support: an honest information-loss count for any candidate
    representation that drops one or more of the 8 real components.
    """
    results = {}
    for comp in ALL_COMPONENTS:
        broken = [o for o in ALL_OPS if comp in NEEDS[o]]
        forced_broken = [o for o in LOWER if comp in NEEDS[o]]
        results[comp] = {"ops_broken_if_removed": len(broken),
                          "forced_ops_broken": len(forced_broken),
                          "verdict": "REQUIRED" if forced_broken else "band-only/OPTIONAL"}
    # K=(A,R)-style representation: keeps only {A,R}, drops {S,EL,Dt,PI,T,H}
    dropped = set(ALL_COMPONENTS) - {"A", "R"}
    ops_losing_definition = sorted(o for o in ALL_OPS if NEEDS[o] & dropped)
    forced_losing_definition = sorted(o for o in LOWER if NEEDS[o] & dropped)
    return {
        "per_component_necessity": results,
        "note": "INTENSIONAL test (static dependency lookup), not extensional/behavioral "
                "-- honestly labeled per BC-02.17, no joint-interaction claim made here.",
        "K_AR_representation_information_loss": {
            "dropped_components": sorted(dropped),
            "operations_losing_a_well-definedness_requirement": ops_losing_definition,
            "of_which_forced(14)": forced_losing_definition,
            "count_forced_affected": len(forced_losing_definition),
            "count_total_affected": len(ops_losing_definition),
        }
    }

# ---------------------------------------------------------------------------
def main():
    report = {"code_version": CODE_VERSION}

    all_vecs = list(all_status_vectors())
    report["sub_experiment_1"] = {
        "domain": {"status_values": STATUS5, "n_requirements": N_REQ,
                   "total_status_vectors": len(all_vecs),
                   "hard_requirement_index": HARD_IDX, "critical_requirement_index": CRITICAL_IDX},
        "oracle_minimal_quotient": {
            "num_true_classes": len(set(real_signature(e) for e in all_vecs)),
            "num_raw_states": len(all_vecs),
        },
        "representation_evaluation": [
            evaluate_rep("pi_1 (S2377 binary Sat collapse, per-requirement)", rho_pi1_binary,
                         "SOURCE-CLAIMED projection direction; reused unchanged from BC-02.6/7"),
            evaluate_rep("3-valued collapse (OK/SOFT/HARD_FAIL)", rho_three_valued,
                         "CANDIDATE, this experiment's own proposal, not source-attested"),
            evaluate_rep("full Status_5 vector (baseline)", rho_full_status5, "retains everything"),
        ],
        "per_behavior_sufficiency_of_pi1": per_behavior_sufficiency(rho_pi1_binary, "pi_1"),
        "per_behavior_sufficiency_of_3valued": per_behavior_sufficiency(rho_three_valued, "3-valued"),
    }

    report["sub_experiment_2_canonical_construction"] = canonical_construction_analysis()

    print(json.dumps(report, indent=2, default=str))

if __name__ == "__main__":
    main()
