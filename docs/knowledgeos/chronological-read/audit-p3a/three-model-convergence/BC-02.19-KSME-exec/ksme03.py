#!/usr/bin/env python3
"""
BC-02.20 / KSME-03 -- Real Behavioral Semantics & Transformational Kernel
Experiment. Corrects KSME-02's mislabeled O-only quotient (K_{T,O} was
actually K_O, no T ever applied). This script:

  PART 1 -- reproduces KSME-02 Sub-experiment 1's K_O (SOURCE-ESTABLISHED /
            DERIVED, per BC-02.19-KSME02 tier table) as a sanity baseline.
  PART 2 -- searches for a SOURCE-ESTABLISHED T:ExC->E anywhere in the
            corpus material already surfaced this session, and reports the
            negative result plus its evidentiary basis (see accompanying
            .md, section "Objective 1 verdict").
  PART 3 -- constructs a HYPOTHESIS-tier T (NOT source-established; see
            disclosure block below) over a small carrier built only from
            REAL, named, corpus-cited operations (Add/Remove/Withdraw/
            Promote/Revise/Supersede/Validate -- all appear in oderive.py's
            FORCES table and/or bandtest.py's NEEDS table, both real,
            already-read corpus artifacts). Only each operation's EFFECT
            (not its existence, not its name) is HYPOTHESIS-tier.
  PART 4 -- computes K_O and K_B as two SEPARATE quotients on that carrier,
            using proper fixed-point (bisimulation-style) refinement so
            that "for all T in T*" (arbitrary composed sequences, per the
            user's corrected formula) is honoured, not just one-step T.
  PART 5 -- congruence check of K_O (expected: FAILS) and K_B (expected:
            holds, by construction) against every operation.
  PART 6 -- joint/marginal relevance check on the observation set, per the
            KSME-01 XOR lesson generalised to Relevant(S).

No historical Kernel candidate is tested here (deferred: only after K_B
material like this is judged sufficient, per the commissioning's own
sequencing rule). No canonicalization/ranking/merging performed.
"""
import itertools, json, hashlib

# ============================================================
# PART 1 -- K_O reproduction on the REAL S0881/S2377 carrier
#   (SOURCE-ESTABLISHED: Status5, Coverage; DERIVED: Ready, CriticalGaps
#    -- exact tier labels as assigned in BC-02.19-KSME02, unchanged here)
# ============================================================
STATUS5 = ["Satisfied", "Unsatisfied", "Unknown", "Conflicted", "NotApplicable"]
N_REQ = 3
HARD_IDX = 0
CRITICAL_IDX = 1

def s0881_all_states():
    # encoding: 0=Satisfied,1=Unsatisfied,2=Unknown,3=Conflicted,4=NotApplicable
    # (verbatim re-derivation from ksme02.py's actual string-keyed source, not
    #  from memory -- an earlier draft of this Part 1 mis-recalled Coverage's
    #  denominator and Ready's conjunct scopes; fixed here after re-reading
    #  ksme02.py directly, per the standing "no reproduction from memory" rule)
    return list(itertools.product(range(5), repeat=N_REQ))

def Coverage(e):
    """Verbatim match of ksme02.py: sat/N_REQ (fixed denominator, NOT
    excluding NotApplicable -- ksme02.py's actual Coverage() body)."""
    sat = sum(1 for s in e if s == 0)
    return sat / N_REQ

def EpistemicDebt_set(e):
    return frozenset(i for i in range(N_REQ) if e[i] not in (0, 4))

def Ready(e):
    """Verbatim match of ksme02.py's Ready(): three conjuncts with DIFFERENT
    scopes -- hard_satisfied over HARD_IDX only, conflict_acceptable over
    ALL positions, critical_uncertainty_acceptable over CRITICAL_IDX only."""
    hard_satisfied = (e[HARD_IDX] == 0)
    conflict_acceptable = all(s != 3 for s in e)
    critical_uncertainty_acceptable = (e[CRITICAL_IDX] != 2)
    return hard_satisfied and conflict_acceptable and critical_uncertainty_acceptable

def CriticalGaps_set(e):
    return frozenset(i for i in (HARD_IDX, CRITICAL_IDX) if e[i] not in (0, 4))

def s0881_signature(e):
    return (Coverage(e), EpistemicDebt_set(e), Ready(e), CriticalGaps_set(e))

def build_s0881_KO():
    classes = {}
    for e in s0881_all_states():
        classes.setdefault(s0881_signature(e), []).append(e)
    return classes

# ============================================================
# PART 2 -- documented negative search result (see .md for full citation
#   trail). Recorded here as data, not re-derived computationally.
# ============================================================
NEGATIVE_SEARCH_RESULT = {
    "question": "Does the admissible primary corpus define, for ANY real "
                 "named KnowledgeOS operation, a concrete, source-verbatim, "
                 "unconditional postcondition/effect T:ExC->E?",
    "answer": "NO",
    "evidence": [
        ("Step 259 (20260830-192058_step_259_congruence-matrix.md, 1054 "
         "lines, read in full)",
         "Provides a CONGRUENCE-TESTING METHODOLOGY, not operation bodies. "
         "Section 259.18: 'We cannot perform a valid global congruence "
         "proof until the mandatory transformation family is itself "
         "sufficiently closed.' Every concrete test in 259.10-259.14 is "
         "explicitly marked CONDITIONAL ('we must not upgrade it to "
         "PROVEN'). Section 259.23 explicitly lists what is NOT "
         "established: K=K_A, K=K_C, K=H/=, provenance-in-K, history-in-K."),
        ("S0881 (Step 23, 20260827-162545_..., 2159 lines, read in full "
         "per BC-02.4) and S2377 (2762 lines, read in full per BC-02.3)",
         "Define OBSERVATIONS (Sat, Coverage, CriticalGaps, EpistemicDebt) "
         "-- functions FROM a state TO a value. Neither defines an "
         "operation that maps a state TO a new state. No update rule."),
        ("verification/canonical-construction/exec/bandtest.py",
         "NEEDS table is a PRECONDITION table (which components an "
         "operation requires to be well-defined / applicable). It is "
         "intensional, never executed as a state transformation, and "
         "never specifies what the operation produces."),
        ("verification/canonical-construction/exec/oderive.py",
         "Derives which operations MUST EXIST (via corpus non-collapse "
         "laws, e.g. 'Remove != Withdraw' forces both to exist) -- this "
         "is EXISTENCE/necessity, not EFFECT. Its own RESULT 3 explicitly "
         "states set-membership itself is 'NOT derivable' from the laws "
         "alone; effects are not even attempted."),
    ],
    "conclusion": "Objective 1 of the KSME-03 commission (does a real "
                  "T:ExC->E exist) resolves to Stop Condition #1 for the "
                  "SOURCE-ESTABLISHED tier: transformation semantics "
                  "cannot be established from the admissible corpus as it "
                  "stands. This is reported, not silently repaired.",
}

# ============================================================
# PART 3 -- HYPOTHESIS-tier T construction
#   DISCLOSURE: every operation NAME and its claim to MANDATORY EXISTENCE
#   is real (oderive.py FORCES table, cited above). The EFFECT below is an
#   analyst-chosen, minimal-assumption interpretation consistent with (but
#   not entailed by) the cited non-collapse law. A different, equally
#   consistent effect is possible. Tier: HYPOTHESIS. This model is never
#   claimed to be the real KnowledgeOS Kernel.
# ============================================================
PRESENT = (0, 1)
TIER = ("Candidate", "Knowledge")
CONTENT = (0, 1, 2)
IDV = (0, 1, 2)
WITHDRAWN = (0, 1)
VALIDATED = ("NA", "Pass", "Fail")

ABSENT_STATE = (0, "None", 0, 0, 0, "NA")

def hyp_all_states():
    out = {ABSENT_STATE}
    for tier in TIER:
        for content in CONTENT:
            for id_ in IDV:
                for withdrawn in WITHDRAWN:
                    for validated in VALIDATED:
                        out.add((1, tier, content, id_, withdrawn, validated))
    return sorted(out)

def op_Add(e, c=None):
    present, tier, content, id_, withdrawn, validated = e
    if present == 1:
        return e  # precondition fails: HYPOTHESIS choice = no-op (disclosed)
    return (1, "Candidate", 0, 0, 0, "NA")

def op_Remove(e, c=None):
    present, tier, content, id_, withdrawn, validated = e
    if present == 0:
        return e
    return ABSENT_STATE

def op_Withdraw(e, c=None):
    present, tier, content, id_, withdrawn, validated = e
    if present == 0:
        return e
    return (1, tier, content, id_, 1, validated)

def op_Promote(e, c=None):
    present, tier, content, id_, withdrawn, validated = e
    if present == 0 or tier != "Candidate":
        return e
    return (1, "Knowledge", content, id_, withdrawn, validated)

def op_Revise(e, c):
    present, tier, content, id_, withdrawn, validated = e
    if present == 0:
        return e
    return (1, tier, c, id_, withdrawn, validated)

def op_Supersede(e, c):
    present, tier, content, id_, withdrawn, validated = e
    if present == 0:
        return e
    return (1, tier, content, c, withdrawn, validated)

def op_Validate(e, c):
    present, tier, content, id_, withdrawn, validated = e
    if present == 0:
        return e
    result = "Pass" if content == c else "Fail"
    return (1, tier, content, id_, withdrawn, result)

OPS = {
    "Add": (op_Add, [None]),
    "Remove": (op_Remove, [None]),
    "Withdraw": (op_Withdraw, [None]),
    "Promote": (op_Promote, [None]),
    "Revise": (op_Revise, list(CONTENT)),
    "Supersede": (op_Supersede, list(IDV)),
    "Validate": (op_Validate, list(CONTENT)),
}

# Observation set O_THIN: deliberately does NOT observe `content` or `id`
# directly. This is the probe: does hiding content/id from O still let
# behavioral equivalence detect them (via Validate's content-dependent
# effect), even though observational equivalence cannot?
def obs_signature_thin(e):
    present, tier, content, id_, withdrawn, validated = e
    return (present, tier, withdrawn, validated)

# ============================================================
# PART 4 -- K_O and K_B as two separate quotients, via fixed-point
#   partition refinement (bisimulation-style). This correctly implements
#   quantification over T* (arbitrary composed operation sequences), which
#   a single-step check does NOT: K_B must be a fixed point, not one pass.
# ============================================================
def build_KO(states):
    classes = {}
    for e in states:
        classes.setdefault(obs_signature_thin(e), []).append(e)
    return list(classes.values())

def refine_to_fixpoint(states, initial_classes):
    classes = [list(c) for c in initial_classes]
    rounds = 0
    while True:
        rounds += 1
        state_to_cid = {}
        for cid, cls in enumerate(classes):
            for e in cls:
                state_to_cid[e] = cid
        new_classes = []
        changed = False
        for cls in classes:
            buckets = {}
            for e in cls:
                sig = []
                for name, (fn, cs) in sorted(OPS.items()):
                    for c in cs:
                        e2 = fn(e, c)
                        sig.append((name, c, state_to_cid[e2]))
                buckets.setdefault(tuple(sig), []).append(e)
            if len(buckets) > 1:
                changed = True
            new_classes.extend(buckets.values())
        classes = new_classes
        if not changed:
            return classes, rounds

def congruence_check(states, classes):
    state_to_cid = {}
    for cid, cls in enumerate(classes):
        for e in cls:
            state_to_cid[e] = cid
    violations = []
    for cid, cls in enumerate(classes):
        for name, (fn, cs) in OPS.items():
            for c in cs:
                images = set(state_to_cid[fn(e, c)] for e in cls)
                if len(images) > 1:
                    violations.append({
                        "class_id": cid, "class_repr": cls[0], "op": name,
                        "context": c, "resulting_classes": sorted(images),
                    })
    return violations

# ============================================================
# PART 6 -- joint/marginal relevance of observations (KSME-01 XOR lesson,
#   generalised to Relevant(S) for the observation SET, not just single O)
# ============================================================
def relevance_probe(states):
    obs_names = ["present", "tier", "withdrawn", "validated"]
    def proj(e, keep):
        full = obs_signature_thin(e)
        return tuple(v if i in keep else None for i, v in enumerate(full))

    results = {}
    full_classes = {}
    for e in states:
        full_classes.setdefault(obs_signature_thin(e), []).append(e)
    n_full = len(full_classes)

    for i, name in enumerate(obs_names):
        keep = set(range(4)) - {i}
        marginal = {}
        for e in states:
            marginal.setdefault(proj(e, keep), []).append(e)
        results[name] = {
            "marginal_drop_alone": "distinguishable" if len(marginal) < n_full else "not distinguishable",
            "classes_without_it": len(marginal),
        }
    # joint test: drop {tier, withdrawn} together vs each alone
    keep = {0, 3}  # present, validated only
    joint = {}
    for e in states:
        joint.setdefault(proj(e, keep), []).append(e)
    results["JOINT(tier,withdrawn)"] = {
        "classes_dropping_both": len(joint),
        "classes_full": n_full,
    }
    return results

# ============================================================
# RUN
# ============================================================
def main():
    report = {}

    ko_s0881 = build_s0881_KO()
    report["part1_s0881_KO_reproduction"] = {
        "n_states": len(s0881_all_states()),
        "n_classes": len(ko_s0881),
        "matches_ksme02_reported_27": len(ko_s0881) == 27,
    }

    report["part2_negative_search_result"] = NEGATIVE_SEARCH_RESULT

    states = hyp_all_states()
    KO_classes = build_KO(states)
    KB_classes, rounds = refine_to_fixpoint(states, KO_classes)

    report["part3_4_hypothesis_model"] = {
        "tier": "HYPOTHESIS (operation existence real; effect analyst-chosen)",
        "n_states": len(states),
        "n_operations": len(OPS),
        "K_O_class_count": len(KO_classes),
        "K_B_class_count": len(KB_classes),
        "K_O_strictly_coarser_than_K_B": len(KO_classes) < len(KB_classes),
        "refinement_rounds_to_fixpoint": rounds,
    }

    viol_KO = congruence_check(states, KO_classes)
    viol_KB = congruence_check(states, KB_classes)
    report["part5_congruence_check"] = {
        "K_O_violations": len(viol_KO),
        "K_O_sample_violation": viol_KO[0] if viol_KO else None,
        "K_B_violations": len(viol_KB),
        "K_B_is_congruence": len(viol_KB) == 0,
    }

    report["part6_relevance_probe"] = relevance_probe(states)

    # concrete counterexample certificate: two K_O-equivalent, K_B-distinct
    # states, with the exact witnessing operation+context+observation
    ko_sig_to_states = {}
    for e in states:
        ko_sig_to_states.setdefault(obs_signature_thin(e), []).append(e)
    kb_state_to_cid = {}
    for cid, cls in enumerate(KB_classes):
        for e in cls:
            kb_state_to_cid[e] = cid

    certificate = None
    for sig, es in ko_sig_to_states.items():
        cids = set(kb_state_to_cid[e] for e in es)
        if len(cids) > 1:
            e1 = es[0]
            e2 = next(e for e in es if kb_state_to_cid[e] != kb_state_to_cid[e1])
            for name, (fn, cs) in sorted(OPS.items()):
                for c in cs:
                    o1 = obs_signature_thin(fn(e1, c))
                    o2 = obs_signature_thin(fn(e2, c))
                    if o1 != o2:
                        certificate = {
                            "e1": e1, "e2": e2,
                            "K_O_equal": True,
                            "witness_operation": name, "witness_context": c,
                            "O(T(e1,c))": o1, "O(T(e2,c))": o2,
                            "conclusion": "e1 ~_O e2 holds but e1 ~_B e2 fails",
                        }
                        break
                if certificate:
                    break
            break
    report["counterexample_certificate"] = certificate

    out_path = "/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/e80b6590-f013-40ab-b3b7-5adc71f528e7/scratchpad/results03.json"
    with open(out_path, "w") as f:
        json.dump(report, f, indent=2, default=str)

    print(json.dumps(report, indent=2, default=str))

if __name__ == "__main__":
    main()
