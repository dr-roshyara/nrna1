#!/usr/bin/env python3
"""
KSME — Kernel Sufficiency, Behavioral Equivalence & Minimality Experiment (BC-02.19)

This is a RESEARCH INSTRUMENT, not KnowledgeOS production code and not historical evidence.
It implements the formal model:

    W --Omega--> O --f--> E --rho--> R
                          E x C --T--> E
                          E --O_req--> observable outcome

and tests, computationally, whether a behavioral-equivalence quotient of E forms a
well-defined, congruent, minimal Kernel under an explicitly bounded, versioned
operation/observation universe.

Every claim this script prints is either:
  - a DEFINITION (stipulated by this script, not derived from any source), or
  - an EXPERIMENTALLY-ESTABLISHED result (computed, deterministic, reproducible from the
    printed seed/config), or
  - explicitly marked NOT COMPARABLE / STOP CONDITION when the required precondition fails.

Nothing here is proven correct for the *actual* KnowledgeOS; it is proven (or refuted)
correct for THIS explicit synthetic world under THIS explicit operation/observation universe.
"""

import itertools
import json
import random
import sys
from dataclasses import dataclass, field, replace
from typing import Callable, Dict, List, Tuple, Any, Optional, FrozenSet

CODE_VERSION = "ksme-0.1.0"
SEED = 20260921

# ---------------------------------------------------------------------------
# L0/L1/L2 — World, Observation, Epistemic State
# ---------------------------------------------------------------------------
#
# We do NOT model an unbounded real world. We model the smallest world rich
# enough to instantiate worlds A-H from the commissioning. Every property has
# a small finite domain so the whole reachable state space is enumerable —
# this is what makes exhaustive (not sampled) equivalence/congruence testing
# possible, per the commissioning's own "smallest useful formal model" task.
#
# Properties and their STIPULATED (by this script) ground-truth role:
#
#   p1  RELEVANT-GLOBAL     : affects every required operation's observable output
#   p2  IRRELEVANT          : affects no required operation's observable output
#   p3  REDUNDANT_A         : with p4, jointly carry exactly 1 behaviorally relevant bit
#   p4  REDUNDANT_B         :   (redundant per Task 12, World E)
#   p5  CONTEXTUAL          : behaviorally relevant ONLY when supplied as an explicit
#                              transformation parameter c; the STATE's own copy of it
#                              is never read by any transformation (World F)
#   p6  OP_DEPENDENT        : relevant to Qualify, irrelevant to Revise/Supersede/Merge
#                              (World H)
#   p7  UNOBSERVABLE        : Omega collapses two distinct world values of p7 onto the
#                              SAME observation (World B) — genuine non-identifiability
#   p8  FUTURE_RELEVANT     : irrelevant under the CURRENT operation universe T_req^(1),
#                              becomes relevant once operation "Contest" is ADDED
#                              (T_req^(2)) — World G / regime-dependence probe
#
# Ground truth K* is DEFINED HERE, by construction, independently of any detection
# algorithm (Task 9). The detection algorithm (relevance/minimality test, Section 3
# below) is a SEPARATE computation whose job is to try to RECOVER this same set from
# behavioral observations alone — agreement or disagreement between the two is itself
# an experimental result, not an assumption.

PROPERTY_NAMES = ["p1", "p2", "p3", "p4", "p5", "p6", "p7", "p8"]
DOMAIN = [0, 1]  # every property is boolean-valued; keeps the state space small & exhaustive

GROUND_TRUTH_ROLE = {
    "p1": "RELEVANT-GLOBAL",
    "p2": "IRRELEVANT",
    "p3": "REDUNDANT-PAIR",
    "p4": "REDUNDANT-PAIR",
    "p5": "CONTEXTUAL",
    "p6": "OP-DEPENDENT(Qualify only)",
    "p7": "UNOBSERVABLE(world-level, hidden by Omega)",
    "p8": "FUTURE-RELEVANT(regime-dependent: irrelevant under T_req^(1), relevant under T_req^(2))",
}

@dataclass(frozen=True)
class World:
    """L0. The 'real world' — includes p7's TRUE value, which Omega will hide."""
    vals: Tuple[int, ...]  # order = PROPERTY_NAMES, but p7 here is the TRUE hidden value

def all_worlds():
    for combo in itertools.product(DOMAIN, repeat=len(PROPERTY_NAMES)):
        yield World(combo)

def Omega(w: World) -> Tuple[int, ...]:
    """
    L1. Observation function W -> O.
    Collapses p7 to a CONSTANT (0) regardless of its true value — this is the
    non-injectivity required by 31.19: Omega(w1)=Omega(w2) for w1 != w2 whenever
    they differ only in p7. Every other property passes through unchanged
    (this experiment does not need coarsening on them to make its point).
    """
    idx = {n: i for i, n in enumerate(PROPERTY_NAMES)}
    out = list(w.vals)
    out[idx["p7"]] = 0  # p7's true value is DESTROYED by observation, not merely noised
    return tuple(out)

@dataclass(frozen=True)
class EpistemicState:
    """
    L2. E = f(O). In this experiment f = identity (the epistemic state IS the
    observation, unfiltered) — a stipulated, explicitly-flagged simplification.
    We do NOT claim E == K_t or E == any historical object; E is this
    experiment's own, minimal, purpose-built carrier.
    """
    vals: Tuple[int, ...]  # order = PROPERTY_NAMES; p7 is always 0 here (post-Omega)

    def get(self, name: str) -> int:
        return self.vals[PROPERTY_NAMES.index(name)]

    def set(self, name: str, value: int) -> "EpistemicState":
        idx = PROPERTY_NAMES.index(name)
        v = list(self.vals)
        v[idx] = value
        return EpistemicState(tuple(v))

def f_identity(o: Tuple[int, ...]) -> EpistemicState:
    return EpistemicState(o)

def all_epistemic_states():
    """All reachable E, i.e. f(Omega(w)) for every world w (p7 always collapsed to 0)."""
    seen = set()
    for w in all_worlds():
        e = f_identity(Omega(w))
        seen.add(e.vals)
    for v in sorted(seen):
        yield EpistemicState(v)

# ---------------------------------------------------------------------------
# L4 — Transformations (the operation registry, "B0..B6")
# ---------------------------------------------------------------------------
# Each transformation is T: E x C -> E (Merge is E x E x C -> E).
# CONTEXT C is a small dict; only some transformations read from it.
#
# Ground-truth relevance is encoded HERE, in which properties each function's
# BODY actually reads — this is the independent ground truth (Task 9).

@dataclass(frozen=True)
class Ctx:
    policy_threshold: int = 0     # used by Qualify
    external_p5: Optional[int] = None  # the CONTEXTUAL supply channel for p5

def op_revise(e: EpistemicState, c: Ctx, new_p1: int) -> EpistemicState:
    """Revise: overwrite p1 (the RELEVANT-GLOBAL field). Reads: p1 only (writes p1)."""
    return e.set("p1", new_p1)

def op_supersede(e: EpistemicState, c: Ctx, new_p1: int) -> EpistemicState:
    """
    Supersede: like Revise but ALSO reads p3,p4 (the redundant pair) to decide
    whether the supersession is a genuine change (XOR) — this makes Supersede
    a DIFFERENT operation from Revise, not an alias, and makes p3/p4 jointly
    (not individually) relevant to it.
    """
    changed_flag = e.get("p3") ^ e.get("p4")
    e2 = e.set("p1", new_p1)
    return e2.set("p3", changed_flag).set("p4", changed_flag)  # collapses the pair to its XOR

def op_merge(e1: EpistemicState, e2: EpistemicState, c: Ctx) -> EpistemicState:
    """
    Merge: reads p1 from both (conflict if they differ -> keep e1's), and reads
    the REDUNDANT PAIR's XOR from both (keep e1's XOR-bit). Does not read p2,p5,p6,p8.
    """
    p1 = e1.get("p1") if e1.get("p1") == e2.get("p1") else e1.get("p1")
    xor1 = e1.get("p3") ^ e1.get("p4")
    return EpistemicState(e1.vals).set("p1", p1).set("p3", xor1).set("p4", xor1)

def op_qualify(e: EpistemicState, c: Ctx) -> EpistemicState:
    """
    Qualify: admits/rejects based on p6 (OP-DEPENDENT: relevant HERE, nowhere else)
    AND on p5 -- but ONLY via the CONTEXT channel c.external_p5, NEVER via e.get("p5").
    This is the experimental test of "contextual, not stored" (World F / Task 11).
    Writes the qualification result into p2's slot is FORBIDDEN (p2 must stay inert) —
    instead we return an observation-only outcome via O_req, not by mutating e.
    """
    # Qualify does not mutate p1,p3,p4,p7,p8 at all. It reads p6 (state) and
    # c.external_p5 (context, NOT e.get("p5")) to decide an outcome that is
    # only visible via the O_qualification observation, not stored back into E.
    return e  # Qualify's *state* effect is a no-op; its behavioral effect lives in O_req.

def op_retract(e: EpistemicState, c: Ctx, field_name: str) -> EpistemicState:
    """Retract: sets the given field to 0 (models 'Unknown'/removed)."""
    return e.set(field_name, 0)

def op_contest(e: EpistemicState, c: Ctx) -> EpistemicState:
    """
    Contest: the FUTURE operation used only in the regime-dependence probe
    (World G). Reads p8 (and only p8) to decide whether a contest is upheld.
    NOT part of T_req^(1); IS part of T_req^(2).
    """
    return e.set("p8", e.get("p8"))  # identity on state; its behavioral signal is via O_req

# ---------------------------------------------------------------------------
# L5 — Observations of behavior (O_req)
# ---------------------------------------------------------------------------

def O_query_p1(e: EpistemicState, c: Ctx) -> int:
    return e.get("p1")

def O_conflict_status(e: EpistemicState, c: Ctx) -> int:
    return e.get("p3") ^ e.get("p4")

def O_qualification_status(e: EpistemicState, c: Ctx) -> int:
    """
    Reads e.get('p6') (state, OP-DEPENDENT-for-Qualify) and c.external_p5
    (context channel) — deliberately NEVER e.get('p5'). This is what makes
    p5 "contextual, not in-state" testable: if we zero out e.get('p5')
    entirely and the behavior is unchanged (because only c.external_p5 is
    read), that is the experimental signature of a contextual property.
    """
    p6 = e.get("p6")
    ext = c.external_p5 if c.external_p5 is not None else 0
    return 1 if (p6 == 1 and ext >= c.policy_threshold) else 0

def O_contest_outcome(e: EpistemicState, c: Ctx) -> int:
    return e.get("p8")

# ---------------------------------------------------------------------------
# Operation / Observation UNIVERSE VERSIONS (explicitly bounded, per commissioning
# Task 3: "do not attempt to freeze the entire architecture")
# ---------------------------------------------------------------------------

CONTEXTS = [Ctx(policy_threshold=0, external_p5=0), Ctx(policy_threshold=0, external_p5=1),
            Ctx(policy_threshold=1, external_p5=0), Ctx(policy_threshold=1, external_p5=1)]
REVISE_ARGS = [0, 1]

def apply_unary(op_name: str, e: EpistemicState, c: Ctx):
    if op_name == "Revise":
        return [op_revise(e, c, v) for v in REVISE_ARGS]
    if op_name == "Supersede":
        return [op_supersede(e, c, v) for v in REVISE_ARGS]
    if op_name == "Qualify":
        return [op_qualify(e, c)]
    if op_name == "Retract_p1":
        return [op_retract(e, c, "p1")]
    if op_name == "Contest":
        return [op_contest(e, c)]
    raise ValueError(op_name)

T_REQ_V1 = ["Revise", "Supersede", "Qualify"]              # KSME-01B/C/D/E/F baseline universe
T_REQ_V2 = ["Revise", "Supersede", "Qualify", "Contest"]   # regime-2, adds Contest (World G)
O_REQ = ["O_query_p1", "O_conflict_status", "O_qualification_status"]
O_REQ_V2 = O_REQ + ["O_contest_outcome"]

OBS_FUNCS = {
    "O_query_p1": O_query_p1,
    "O_conflict_status": O_conflict_status,
    "O_qualification_status": O_qualification_status,
    "O_contest_outcome": O_contest_outcome,
}

def signature(e: EpistemicState, t_req: List[str], o_req: List[str]) -> Tuple:
    """
    THE behavioral-equivalence signature (Section 4/8): the tuple of every
    required observation, of every result of every required unary operation
    (over every enumerated context and argument), PLUS every required
    observation of e itself (the T=identity / 'observation only' case, KSME-01A).
    """
    sig = []
    # KSME-01A component: direct observations of e with no transformation
    for oname in o_req:
        sig.append(("id", oname, OBS_FUNCS[oname](e, CONTEXTS[0])))
    for op_name in t_req:
        for c in CONTEXTS:
            results = apply_unary(op_name, e, c)
            for e2 in results:
                for oname in o_req:
                    sig.append((op_name, c.policy_threshold, c.external_p5, oname,
                                OBS_FUNCS[oname](e2, c)))
    return tuple(sig)

def signature_merge_pairs(e1: EpistemicState, e2: EpistemicState) -> Tuple:
    m = op_merge(e1, e2, CONTEXTS[0])
    return tuple(OBS_FUNCS[o](m, CONTEXTS[0]) for o in O_REQ)

# ---------------------------------------------------------------------------
# Equivalence / partition machinery
# ---------------------------------------------------------------------------

def build_partition(states: List[EpistemicState], t_req, o_req) -> Dict[Tuple, List[EpistemicState]]:
    parts: Dict[Tuple, List[EpistemicState]] = {}
    for e in states:
        sig = signature(e, t_req, o_req)
        parts.setdefault(sig, []).append(e)
    return parts

def property_relevance_by_sensitivity(states: List[EpistemicState], t_req, o_req) -> Dict[str, bool]:
    """
    THE DETECTION ALGORITHM (independent of ground truth): for each property p,
    is there ANY pair of states differing ONLY in p whose signatures differ?
    If yes -> p is DETECTED-RELEVANT. If no -> DETECTED-IRRELEVANT.
    This directly implements Step 258's Relevant(p) criterion (Task 11),
    generalized to "exists T in T_req, O in O_req, c, e1,e2 differing only in p
    such that O(T(e1,c)) != O(T(e2,c))" -- signature() already ranges over all
    T,O,c, so equality of full signatures IS exactly that existential test's
    negation.
    """
    by_val: Dict[str, Dict[Tuple, List[EpistemicState]]] = {}
    detected = {}
    for p in PROPERTY_NAMES:
        idx = PROPERTY_NAMES.index(p)
        groups: Dict[Tuple, List[EpistemicState]] = {}
        for e in states:
            key = tuple(v for i, v in enumerate(e.vals) if i != idx)
            groups.setdefault(key, []).append(e)
        relevant = False
        for key, members in groups.items():
            sigs = set(signature(m, t_req, o_req) for m in members)
            if len(sigs) > 1:
                relevant = True
                break
        detected[p] = relevant
    return detected

def check_equivalence_relation_properties(states: List[EpistemicState], t_req, o_req):
    """Task 8: verify reflexivity, symmetry, transitivity of ~_B EXHAUSTIVELY over
    the full (small) state space -- not sampled, since the space is enumerable."""
    sigs = {e.vals: signature(e, t_req, o_req) for e in states}
    refl = all(sigs[e.vals] == sigs[e.vals] for e in states)  # trivially true; recorded anyway
    sym_failures = []
    trans_failures = []
    items = list(states)
    for e1 in items:
        for e2 in items:
            eq12 = sigs[e1.vals] == sigs[e2.vals]
            eq21 = sigs[e2.vals] == sigs[e1.vals]
            if eq12 != eq21:
                sym_failures.append((e1.vals, e2.vals))
    for e1 in items:
        for e2 in items:
            for e3 in items:
                eq12 = sigs[e1.vals] == sigs[e2.vals]
                eq23 = sigs[e2.vals] == sigs[e3.vals]
                eq13 = sigs[e1.vals] == sigs[e3.vals]
                if eq12 and eq23 and not eq13:
                    trans_failures.append((e1.vals, e2.vals, e3.vals))
    return {
        "reflexive": refl,
        "symmetric": len(sym_failures) == 0,
        "symmetry_failures": sym_failures[:5],
        "transitive": len(trans_failures) == 0,
        "transitivity_failures": trans_failures[:5],
    }

def apply_unary_single(op_name: str, e: EpistemicState, c: Ctx, arg):
    """Like apply_unary but returns exactly ONE image for ONE concrete argument —
    required for a well-posed congruence test (T must be held fixed, including its
    argument, not aggregated over several arguments at once)."""
    if op_name == "Revise":
        return op_revise(e, c, arg)
    if op_name == "Supersede":
        return op_supersede(e, c, arg)
    if op_name == "Qualify":
        return op_qualify(e, c)
    if op_name == "Retract_p1":
        return op_retract(e, c, "p1")
    if op_name == "Contest":
        return op_contest(e, c)
    raise ValueError(op_name)

OP_ARGS = {"Revise": REVISE_ARGS, "Supersede": REVISE_ARGS, "Qualify": [None],
           "Retract_p1": [None], "Contest": [None]}

def check_congruence(states: List[EpistemicState], t_req, o_req):
    """
    Task 9 (Section numbering in commissioning: 'Fifth task').
    e1 ~_B e2  =>  T(e1,c) ~_B T(e2,c)  for every CONCRETE (T,arg,c) instance.
    Checked EXHAUSTIVELY (not sampled) since the reachable state space is finite.
    CORRECTNESS NOTE (recorded, not hidden): an earlier version of this function
    aggregated Revise's two argument branches (new_p1=0 and new_p1=1) into a single
    'operation', which manufactures spurious violations (of course revising two
    different states to DIFFERENT target values need not land in the same class —
    that was never a meaningful congruence question). Fixed by holding the argument
    fixed, per the commissioning's own T:ExC->E signature, where C bundles the
    argument.
    """
    parts = build_partition(states, t_req, o_req)
    part_of = {}
    for sig, members in parts.items():
        for m in members:
            part_of[m.vals] = sig
    violations = []
    for sig, members in parts.items():
        if len(members) < 2:
            continue
        for op_name in t_req:
            for c in CONTEXTS:
                for arg in OP_ARGS[op_name]:
                    images_partitions = set()
                    for m in members:
                        e2 = apply_unary_single(op_name, m, c, arg)
                        images_partitions.add(part_of[e2.vals])
                    if len(images_partitions) > 1:
                        violations.append({
                            "class_repr": members[0].vals,
                            "class_size": len(members),
                            "operation": op_name,
                            "arg": arg,
                            "context": (c.policy_threshold, c.external_p5),
                            "resulting_partitions": len(images_partitions),
                        })
    return violations

# ---------------------------------------------------------------------------
# Adversarial counterexample search (Section 17, C1-C8)
# ---------------------------------------------------------------------------

def adversarial_search(states, t_req_current, o_req_current, t_req_expanded, o_req_expanded):
    findings = {}
    # C1 / hidden operation (C3): states equivalent under CURRENT universe but
    # distinguished once Contest / O_contest_outcome is ADDED (regime 2).
    parts_v1 = build_partition(states, t_req_current, o_req_current)
    hidden_op_breaks = []
    for sig, members in parts_v1.items():
        if len(members) < 2:
            continue
        sub_sigs = set(signature(m, t_req_expanded, o_req_expanded) for m in members)
        if len(sub_sigs) > 1:
            hidden_op_breaks.append({"v1_class_repr": members[0].vals, "v1_class_size": len(members),
                                      "v2_subpartitions": len(sub_sigs)})
    findings["C3_hidden_operation_breaks_equivalence"] = hidden_op_breaks

    # C5 / observation loss: p7 differs between two worlds but is UNRECOVERABLE
    # post-Omega -- by construction (Omega always writes p7:=0), so EVERY pair of
    # worlds differing only in p7 collapses to the identical epistemic state.
    # This is checked directly against the World-level (pre-Omega) values.
    worlds = list(all_worlds())
    idx7 = PROPERTY_NAMES.index("p7")
    collapsed_pairs = 0
    total_pairs_checked = 0
    for w1 in worlds:
        for w2 in worlds:
            if w1.vals == w2.vals:
                continue
            differs_only_in_p7 = all(w1.vals[i] == w2.vals[i] for i in range(len(PROPERTY_NAMES)) if i != idx7) \
                and w1.vals[idx7] != w2.vals[idx7]
            if differs_only_in_p7:
                total_pairs_checked += 1
                if Omega(w1) == Omega(w2):
                    collapsed_pairs += 1
    findings["C5_observation_loss_p7"] = {
        "pairs_differing_only_in_p7": total_pairs_checked,
        "pairs_collapsed_by_Omega": collapsed_pairs,
        "identifiable_p7": collapsed_pairs == 0,
    }

    # C4 / context dependency: does zeroing e.get('p5') (never reading it) still
    # reproduce correct O_qualification_status, confirming p5 is exercised ONLY
    # via context and is genuinely EXTERNALIZABLE without loss, given the current
    # operation set? We test: for all e, all c, does O_qualification_status(e,c)
    # depend on e.get('p5') at all?
    dep_on_state_p5 = False
    for e in states:
        for c in CONTEXTS:
            e_p5_0 = e.set("p5", 0)
            e_p5_1 = e.set("p5", 1)
            if O_qualification_status(e_p5_0, c) != O_qualification_status(e_p5_1, c):
                dep_on_state_p5 = True
    findings["C4_context_property_p5"] = {
        "state_copy_of_p5_affects_behavior": dep_on_state_p5,
        "conclusion": "REMOVABLE FROM STATE, MUST REMAIN AVAILABLE AS CONTEXT" if not dep_on_state_p5
                      else "STATE COPY IS LOAD-BEARING, CANNOT BE EXTERNALIZED AS CURRENTLY WIRED",
    }

    # C7 / non-congruence: reuse check_congruence but on a DELIBERATELY WRONG
    # coarser relation (ignore p3 and p4 individually, not their XOR) to show
    # that AN ARBITRARY choice of "irrelevant-looking" fields to drop CAN break
    # congruence, even though the XOR-based partition (built into signature())
    # does not. This demonstrates the difference between representation choice
    # and behavioral minimality (Section 10).
    def naive_drop_p3_p4(e: EpistemicState):
        return tuple(v for i, v in enumerate(e.vals) if PROPERTY_NAMES[i] not in ("p3", "p4"))
    naive_parts: Dict[Tuple, List[EpistemicState]] = {}
    for e in states:
        naive_parts.setdefault(naive_drop_p3_p4(e), []).append(e)
    naive_violations = []
    for key, members in naive_parts.items():
        if len(members) < 2:
            continue
        true_sigs = set(signature(m, t_req_current, o_req_current) for m in members)
        if len(true_sigs) > 1:
            naive_violations.append({"naive_class_repr": key, "naive_class_size": len(members),
                                      "true_subpartitions": len(true_sigs)})
    findings["C7_naive_field_drop_breaks_behavioral_equivalence"] = naive_violations[:5]
    findings["C7_naive_violation_count"] = len(naive_violations)

    return findings

# ---------------------------------------------------------------------------
# Candidate representation evaluation
# ---------------------------------------------------------------------------

def evaluate_candidate(name: str, rho: Optional[Callable[[EpistemicState], Tuple]],
                        states, t_req, o_req, note: str):
    if rho is None:
        return {"candidate": name, "status": "NOT COMPARABLE UNDER CURRENT FORMALIZATION", "reason": note}
    true_parts = build_partition(states, t_req, o_req)
    true_part_of = {}
    for sig, members in true_parts.items():
        for m in members:
            true_part_of[m.vals] = sig
    rho_parts: Dict[Tuple, List[EpistemicState]] = {}
    for e in states:
        rho_parts.setdefault(rho(e), []).append(e)
    false_equivalence = 0  # rho merges states with DIFFERENT true behavior
    for key, members in rho_parts.items():
        true_sigs = set(true_part_of[m.vals] for m in members)
        if len(true_sigs) > 1:
            false_equivalence += (len(members) - 1)
    false_distinction = 0  # rho separates states with the SAME true behavior
    for sig, members in true_parts.items():
        rho_keys = set(rho(m) for m in members)
        if len(rho_keys) > 1:
            false_distinction += (len(rho_keys) - 1)
    sufficiency = false_equivalence == 0
    return {
        "candidate": name, "status": "TESTED",
        "sufficiency (no false equivalence)": sufficiency,
        "false_equivalence_count": false_equivalence,
        "false_distinction_count": false_distinction,
        "num_rho_classes": len(rho_parts),
        "num_true_classes": len(true_parts),
        "note": note,
    }

# K=(A,R)-analogous representation, ANALYST-CONSTRUCTED, NOT source-derived:
# interpretation used: 'A' (assertions) <-> p1 (the asserted value) + p3,p4 (relation
# support/refute flags, kept RAW not XOR'd); 'R' (relations) <-> p6 (a relation-type-like
# tag). Projects away p2 (never modeled by A/R at all), p5 (context, correctly dropped),
# p7 (unobservable anyway), p8 (future-relevant, not yet in scope of A/R's own task).
def rho_K_AR_analyst(e: EpistemicState) -> Tuple:
    return (e.get("p1"), e.get("p3"), e.get("p4"), e.get("p6"))

# ---------------------------------------------------------------------------
# Main experiment driver
# ---------------------------------------------------------------------------

def main():
    random.seed(SEED)
    report: Dict[str, Any] = {"code_version": CODE_VERSION, "seed": SEED}

    states = list(all_epistemic_states())
    report["state_space"] = {
        "num_worlds": len(list(all_worlds())),
        "num_reachable_epistemic_states": len(states),
        "properties": PROPERTY_NAMES,
        "ground_truth_roles": GROUND_TRUTH_ROLE,
    }

    # KSME-01A: observation only -- what is identifiable from Omega alone?
    ident = {}
    for p in PROPERTY_NAMES:
        idx = PROPERTY_NAMES.index(p)
        worlds = list(all_worlds())
        groups = {}
        for w in worlds:
            key = tuple(v for i, v in enumerate(w.vals) if i != idx)
            groups.setdefault(key, []).append(w)
        identifiable = True
        for key, members in groups.items():
            obs = set(Omega(m) for m in members)
            vals_of_p = set(m.vals[idx] for m in members)
            if len(vals_of_p) > 1 and len(obs) == 1:
                identifiable = False
                break
        ident[p] = identifiable
    report["KSME_01A_identifiability_from_Omega"] = ident

    # KSME-01B/C: behavioral relevance detection under T_req_v1, full O_req
    detected_v1 = property_relevance_by_sensitivity(states, T_REQ_V1, O_REQ)
    report["KSME_01B_01C_detected_relevance_v1"] = detected_v1

    # KSME-01D: congruence under T_req_v1
    violations_v1 = check_congruence(states, T_REQ_V1, O_REQ)
    report["KSME_01D_congruence_violations_v1"] = violations_v1

    # Equivalence-relation sanity (reflexive/symmetric/transitive), exhaustive
    eq_props = check_equivalence_relation_properties(states, T_REQ_V1, O_REQ)
    report["equivalence_relation_properties_v1"] = eq_props

    # KSME-01E: minimality -- ground truth vs detected
    ground_truth_relevant = {p: (GROUND_TRUTH_ROLE[p] not in ("IRRELEVANT",)) for p in PROPERTY_NAMES}
    # p3,p4 are jointly relevant (as XOR) but each INDIVIDUALLY is NOT sensitivity-relevant
    # in isolation once its partner is free to vary -- this is exactly the "redundant pair"
    # case (World E) and is reported, not hidden.
    report["KSME_01E_minimality"] = {
        "ground_truth_relevant_(by_construction)": ground_truth_relevant,
        "detected_relevant_(sensitivity_test)": detected_v1,
        "agreement": {p: (ground_truth_relevant[p] == detected_v1[p]) for p in PROPERTY_NAMES},
    }

    # KSME-01F: context (p5) -- Task 11 / World F
    ctx_findings = adversarial_search(states, T_REQ_V1, O_REQ, T_REQ_V2, O_REQ_V2)
    report["KSME_01F_context_p5"] = ctx_findings["C4_context_property_p5"]

    # KSME-01G: adversarial hidden distinction / regime dependence (World G, p8)
    report["KSME_01G_adversarial_regime_dependence"] = {
        "hidden_operation_findings": ctx_findings["C3_hidden_operation_breaks_equivalence"],
        "p8_detected_relevant_under_v1": detected_v1["p8"],
        "p8_detected_relevant_under_v2": property_relevance_by_sensitivity(states, T_REQ_V2, O_REQ_V2)["p8"],
    }

    # World B / C5: observation loss (p7)
    report["World_B_C5_observation_loss"] = ctx_findings["C5_observation_loss_p7"]

    # C7: naive-field-drop congruence break (representation-dependence demo)
    report["C7_naive_representation_congruence_break"] = {
        "violation_count": ctx_findings["C7_naive_violation_count"],
        "sample": ctx_findings["C7_naive_field_drop_breaks_behavioral_equivalence"],
    }

    # KSME-01H: existing candidate representations
    candidates = []
    candidates.append(evaluate_candidate(
        "K=(A,R) [ANALYST-CONSTRUCTED mapping, NOT source-derived]",
        rho_K_AR_analyst, states, T_REQ_V1, O_REQ,
        "Interpretation: A~p1 (asserted value), relation-support/refute~p3,p4 kept RAW "
        "(not XOR-reduced), R~p6. This is ONE analyst-chosen interpretation among many "
        "possible ones; a different, equally defensible interpretation could yield a "
        "different verdict. Reported as HYPOTHESIS-level evidence about this specific "
        "interpretation, not as a verdict on the historical object itself."))
    for name, reason in [
        ("K_t^11", "BC-02.11/16: 11 fields have no stated type, no construction rule, no "
                    "consuming computation anywhere in the corpus -- no non-arbitrary rho exists."),
        ("K_min^4", "BC-02.16: fields never individually typed; O_core itself downgraded to "
                     "'candidate, not proven minimal' by its own primary source (S1792 Pass 2)."),
        ("canonical-construction K", "BC-02.16/17: tested only against ITS OWN 18-operation NEEDS "
                     "table, a different, non-overlapping-except-for-2-ops universe from this "
                     "experiment's T_req; translating one universe into the other is itself an "
                     "unproven, arbitrary research act, not a formalization."),
        ("ratified 8-primitive K_t", "BC-02.13/16: self-described as a primitive VOCABULARY/ontology "
                     "underlying state construction, not a state instance -- categorically not a "
                     "rho:E->R in this experiment's sense."),
    ]:
        candidates.append(evaluate_candidate(name, None, states, T_REQ_V1, O_REQ, reason))
    report["KSME_01H_candidate_evaluation"] = candidates

    # Oracle minimal representation = the TRUE partition itself (by definition minimal:
    # zero false equivalence, zero false distinction, by construction).
    true_parts = build_partition(states, T_REQ_V1, O_REQ)
    report["oracle_minimal_representation"] = {
        "num_classes": len(true_parts),
        "num_reachable_states": len(states),
        "cardinality_reduction_vs_full_state": f"{len(states)} states -> {len(true_parts)} classes",
    }
    # Full-state baseline
    full_state_eval = evaluate_candidate("FULL-STATE baseline", lambda e: e.vals, states, T_REQ_V1, O_REQ,
                                          "retains everything")
    # Empty-state baseline
    empty_state_eval = evaluate_candidate("EMPTY-STATE baseline", lambda e: (), states, T_REQ_V1, O_REQ,
                                           "retains nothing")
    report["baselines"] = {"full_state": full_state_eval, "empty_state": empty_state_eval}

    print(json.dumps(report, indent=2, default=str))

if __name__ == "__main__":
    main()
