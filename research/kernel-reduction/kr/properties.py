"""Property-based randomized experiment (Part XI).

A random epistemic world varies WHICH AMBIENT CARRIERS THE SITUATION SUPPLIES
and which epistemic features are present.  Properties are then checked
against what the operator set can and cannot do in that world.

Properties are written so that a property can FAIL - including failing by
OVERREACH (the system produces a commitment it has no power to qualify),
not only by underreach.
"""
import random
from .carriers import *
from .reach import reach, atom_pool
from .capabilities import achieved
from .atoms import *

OPTIONAL_AMBIENT = [POLICY, RULE, IDEAL_STATE, OBJECTIVE, ACTION_SET]

def make_world(rng):
    amb = [WORLD, CONTEXT, INQUIRY, K_STATE]
    for a in OPTIONAL_AMBIENT:
        if rng.random() < 0.6: amb.append(a)
    return dict(
        ambient=amb,
        n_entities=rng.randint(1, 20), n_observations=rng.randint(1, 20),
        n_claims=rng.randint(1, 20), n_dimensions=rng.randint(1, 10),
        n_evidence=rng.randint(1, 10), n_hypotheses=rng.randint(1, 5),
        missing_evidence=rng.random() < 0.5,
        conflicting=rng.random() < 0.5,
        temporal_change=rng.random() < 0.5,
        provenance_differs=rng.random() < 0.5,
        context_differs=rng.random() < 0.5,
        indistinguishable=rng.random() < 0.4,
    )

def check(ops, w):
    kinds, _ = reach(ops, ambient=w["ambient"])
    pool = atom_pool(ops)
    A = lambda c: achieved(c, kinds, pool)
    v = {}
    # P1 revision representable when evidence contradicts an existing claim
    v["P1"] = (not w["conflicting"]) or (A("C11") and A("C5"))
    # P2 must not fabricate a unique determination when H1/H2 indistinguishable
    v["P2"] = (not w["indistinguishable"]) or (not A("C12")) or A("C20")
    # P3 provenance preserved  (structurally guaranteed -> non-discriminating)
    v["P3"] = True
    # P4 temporal: t2 change must not erase t1
    v["P4"] = (not w["temporal_change"]) or A("C15")
    # P5 interpretations remain distinguishable when evidence does not resolve
    v["P5"] = (not w["context_differs"]) or (A("C21") and A("C22"))
    # P6 adequacy is not "some answer was generated": a Determination requires
    #    a normative target
    v["P6"] = (not A("C12")) or (IDEAL_STATE in w["ambient"])
    # P7 no information creation: Observation only via world-contact
    v["P7"] = (OBSERVATION not in kinds) or (A_WORLD_CONTACT in pool)
    # P8 alternatives survive unless evidence eliminates them
    v["P8"] = (w["n_hypotheses"] < 2) or (A("C6") and A("C19"))
    # P9 same observation, different relevance under different inquiries
    v["P9"] = (not w["context_differs"]) or A("C14")
    # P10 unchallenged != validated
    v["P10"] = (not A("C10")) or A("C9")
    return v

def run(ops, seed, trials):
    rng = random.Random(seed)
    fails = {f"P{i}": 0 for i in range(1, 11)}
    for _ in range(trials):
        for k, ok in check(ops, make_world(rng)).items():
            if not ok: fails[k] += 1
    return fails

# ------------------------------------------------- degeneracy / vacuity audit
def guard_active(ops, w):
    """Was each property's antecedent actually ACTIVE in this world?
    A property that passes only because its guard is false is a VACUOUS pass
    and must not be reported as evidence of correctness."""
    kinds, _ = reach(ops, ambient=w["ambient"])
    pool = atom_pool(ops)
    A = lambda c: achieved(c, kinds, pool)
    return dict(
        P1=w["conflicting"], P2=w["indistinguishable"] and A("C12"),
        P3=False, P4=w["temporal_change"], P5=w["context_differs"],
        P6=A("C12"), P7=(OBSERVATION in kinds), P8=w["n_hypotheses"] >= 2,
        P9=w["context_differs"], P10=A("C10"),
    )

def run_with_vacuity(ops, seed, trials):
    rng = random.Random(seed)
    fails = {f"P{i}": 0 for i in range(1, 11)}
    active = {f"P{i}": 0 for i in range(1, 11)}
    for _ in range(trials):
        w = make_world(rng)
        g = guard_active(ops, w)
        for k, ok in check(ops, w).items():
            if g[k]: active[k] += 1
            if not ok: fails[k] += 1
    return fails, active
