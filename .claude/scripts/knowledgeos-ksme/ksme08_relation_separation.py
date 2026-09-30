#!/usr/bin/env python3
"""
KSME-08 -- Relation-Separation Experiment (SYNTHETIC, exact/exhaustive).

Tests whether a directional semantic relation (preorder/partial order,
"semantic ordering") and behavioral indistinguishability (~B, necessarily
an equivalence relation) can coexist as independent layers on the same
carrier E, and under what condition the order relation descends to the
quotient E/~B.

Every model here is SYNTHETIC (constructed to test the mathematical
question, not derived from or claiming to represent real KnowledgeOS
semantics). Tier: SYNTHETIC throughout. Track-A firewall: no Track-B file,
state shape, or numeric result used or referenced.

~B is computed the same way as KSME-04/05's K_B/K_R (fixed-point partition
refinement over observations + operations), which is the mathematically
correct way to honor "no admissible T* sequence distinguishes them" --
reused here as a known-correct primitive, not re-derived.
"""
from __future__ import annotations
import itertools, json

def is_reflexive(E, R):
    return all((x, x) in R for x in E)

def is_symmetric(R):
    return all((y, x) in R for (x, y) in R)

def is_transitive(E, R):
    for x, y in R:
        for y2, z in R:
            if y == y2 and (x, z) not in R:
                return False
    return True

def is_antisymmetric(R):
    return all((y, x) not in R for (x, y) in R if x != y)

def relation_profile(E, R, name):
    return {
        "name": name,
        "size": len(R),
        "reflexive": is_reflexive(E, R),
        "symmetric": is_symmetric(R),
        "transitive": is_transitive(E, R),
        "antisymmetric": is_antisymmetric(R),
        "classification": classify(E, R),
    }

def classify(E, R):
    refl, sym, trans, anti = is_reflexive(E, R), is_symmetric(R), is_transitive(E, R), is_antisymmetric(R)
    if refl and sym and trans:
        return "equivalence"
    if refl and trans and anti:
        return "partial_order"
    if refl and trans and not anti:
        return "preorder"
    return "other/unclassified"

def build_behavioral_equivalence(E, obs_funcs, ops):
    """~B via fixed-point partition refinement. ops: dict name -> (fn, contexts)."""
    parts = {}
    for e in E:
        key = tuple(o(e) for o in obs_funcs)
        parts.setdefault(key, []).append(e)
    classes = list(parts.values())
    rounds = 0
    while True:
        rounds += 1
        e2c = {}
        for cid, cls in enumerate(classes):
            for e in cls:
                e2c[e] = cid
        new_classes, changed = [], False
        for cls in classes:
            buckets = {}
            for e in cls:
                sig = []
                for opname, (fn, ctxs) in sorted(ops.items()):
                    for c in ctxs:
                        r = fn(e, c)
                        sig.append((opname, c, e2c[r]))
                buckets.setdefault(tuple(sig), []).append(e)
            if len(buckets) > 1:
                changed = True
            new_classes.extend(buckets.values())
        classes = new_classes
        if not changed:
            return classes, rounds

def as_relation(E, classes):
    """Build ~B as an explicit pair-set from the partition."""
    e2c = {}
    for cid, cls in enumerate(classes):
        for e in cls:
            e2c[e] = cid
    return {(x, y) for x in E for y in E if e2c[x] == e2c[y]}, e2c

def order_descends(E, order_R, e2c):
    """Check: x~B x', y~B y', x<=y  =>  x'<=y'  (well-definedness of induced order)."""
    violations = []
    for (x, y) in order_R:
        cx, cy = e2c[x], e2c[y]
        for xp in E:
            if e2c[xp] != cx:
                continue
            for yp in E:
                if e2c[yp] != cy:
                    continue
                if (xp, yp) not in order_R:
                    violations.append({"witness_pair": (x, y), "x_prime": xp, "y_prime": yp,
                                        "reason": f"{x}~B{xp} and {y}~B{yp} and {x}<={y} but NOT {xp}<={yp}"})
    return len(violations) == 0, violations[:3]

def congruence_check(E, R, ops):
    """Is R (as classes) a congruence under ops? (should hold for ~B by construction)."""
    e2c = {}
    parts = {}
    for x in E:
        for y in E:
            if (x, y) in R and (y, x) in R:
                parts.setdefault(frozenset([x]) if x not in parts else None, None)
    # simpler: derive classes directly from R
    seen = set()
    classes = []
    for x in E:
        if x in seen:
            continue
        cls = [y for y in E if (x, y) in R and (y, x) in R]
        classes.append(cls)
        seen.update(cls)
    for cid, cls in enumerate(classes):
        for x in cls:
            e2c[x] = cid
    violations = []
    for opname, (fn, ctxs) in sorted(ops.items()):
        for c in ctxs:
            for cls in classes:
                imgs = set(e2c[fn(x, c)] for x in cls)
                if len(imgs) > 1:
                    violations.append({"op": opname, "context": c, "class_repr": cls[0]})
    return len(violations) == 0, violations[:3]

REPORT = {"tier": "SYNTHETIC", "models": []}

# ---------------------------------------------------------------------------
# MODEL 1 -- Pure equivalence, no order relation defined.
# ---------------------------------------------------------------------------
E1 = ["a", "b", "c", "d"]
def o1(e): return {"a": 0, "b": 0, "c": 1, "d": 1}[e]
classes1, rounds1 = build_behavioral_equivalence(E1, [o1], {})
R1, e2c1 = as_relation(E1, classes1)
REPORT["models"].append({
    "id": "M1_pure_equivalence",
    "E": E1,
    "behavioral_equivalence": relation_profile(E1, R1, "~B"),
    "order_relation": None,
    "note": "No order relation defined. Baseline: ~B is a genuine equivalence relation "
            "(reflexive/symmetric/transitive), classes = {a,b},{c,d}.",
})

# ---------------------------------------------------------------------------
# MODEL 2 -- Pure total order, trivial (discrete) behavioral equivalence.
# ---------------------------------------------------------------------------
E2 = [1, 2, 3, 4]
order2 = {(x, y) for x in E2 for y in E2 if x <= y}
def o2(e): return e  # identity observation -> every state distinguishable
classes2, rounds2 = build_behavioral_equivalence(E2, [o2], {})
R2, e2c2 = as_relation(E2, classes2)
desc2, viol2 = order_descends(E2, order2, e2c2)
REPORT["models"].append({
    "id": "M2_total_order_discrete_equivalence",
    "E": E2,
    "order_relation": relation_profile(E2, order2, "<="),
    "behavioral_equivalence": relation_profile(E2, R2, "~B"),
    "order_descends_to_quotient": desc2,
    "note": "Total order <= coexists with a fully-discrete ~B (identity observation). "
            "Order trivially descends since ~B is equality.",
})

# ---------------------------------------------------------------------------
# MODEL 3 -- Partial order + non-trivial ~B, COMPATIBLE (order descends cleanly).
#   E = (x, tag) with x in {0,1,2}, tag in {A,B}. Order depends only on x.
#   Observations see only x (tag is behaviorally invisible) -> ~B groups by x.
#   Since order also depends only on x, it descends cleanly.
# ---------------------------------------------------------------------------
E3 = [(x, t) for x in (0, 1, 2) for t in ("A", "B")]
order3 = {((x1, t1), (x2, t2)) for (x1, t1) in E3 for (x2, t2) in E3 if x1 <= x2}
def o3(e): return e[0]  # tag invisible to observation
classes3, rounds3 = build_behavioral_equivalence(E3, [o3], {})
R3, e2c3 = as_relation(E3, classes3)
desc3, viol3 = order_descends(E3, order3, e2c3)
REPORT["models"].append({
    "id": "M3_partial_order_compatible",
    "E": [str(e) for e in E3],
    "order_relation": relation_profile(E3, order3, "<=_x"),
    "behavioral_equivalence": relation_profile(E3, R3, "~B"),
    "order_descends_to_quotient": desc3,
    "violations": viol3,
    "note": "Order and ~B both depend only on the x-component (tag is behaviorally "
            "and order-wise invisible) -> CASE A, clean separation, order descends.",
})

# ---------------------------------------------------------------------------
# MODEL 4 -- Preorder (not antisymmetric) + ~B, still compatible.
#   Two distinct x-values (1 and 1b) are mutually <=  (a genuine preorder tie),
#   both behaviorally identical to each other too (same observation value),
#   so preorder ties coincide with ~B ties -- still descends cleanly.
# ---------------------------------------------------------------------------
E4 = ["x0", "x1", "x1b", "x2"]
OBS4 = {"x0": 0, "x1": 1, "x1b": 1, "x2": 2}  # x1 and x1b observationally identical
RANK4 = {"x0": 0, "x1": 1, "x1b": 1, "x2": 2}
order4 = {(a, b) for a in E4 for b in E4 if RANK4[a] <= RANK4[b]}  # preorder: x1<=x1b and x1b<=x1, not antisymmetric
def o4(e): return OBS4[e]
classes4, rounds4 = build_behavioral_equivalence(E4, [o4], {})
R4, e2c4 = as_relation(E4, classes4)
desc4, viol4 = order_descends(E4, order4, e2c4)
REPORT["models"].append({
    "id": "M4_preorder_compatible",
    "E": E4,
    "order_relation": relation_profile(E4, order4, "<=_rank"),
    "behavioral_equivalence": relation_profile(E4, R4, "~B"),
    "order_descends_to_quotient": desc4,
    "violations": viol4,
    "note": "Preorder (x1<=x1b and x1b<=x1, i.e. NOT antisymmetric) whose ties coincide "
            "exactly with ~B's ties -> still CASE A, descends cleanly, even though the "
            "order relation itself is not a partial order.",
})

# ---------------------------------------------------------------------------
# MODEL 5 -- Partial order INCOMPATIBLE with ~B: descent FAILS.
#   E = (x, tag), x in {0,1}, tag in {A,B}. Observations see only x (so ~B
#   groups {(0,A),(0,B)} and {(1,A),(1,B)}) -- SAME setup as Model 3 so far.
#   But now the order relation depends on TAG too, in a way that creates
#   disagreement between representatives of the same behavioral class:
#   (0,A) <= (1,B)  holds,  but  (0,B) <= (1,A)  does NOT hold.
# ---------------------------------------------------------------------------
E5 = [(x, t) for x in (0, 1) for t in ("A", "B")]
def o5(e): return e[0]
classes5, rounds5 = build_behavioral_equivalence(E5, [o5], {})
R5, e2c5 = as_relation(E5, classes5)
# base reflexive+within-x-monotone edges, PLUS the deliberately asymmetric cross-tag edge
order5 = {(e, e) for e in E5}
order5.add(((0, "A"), (1, "B")))   # allowed
# NOTE: ((0,"B"),(1,"A")) deliberately NOT added -- this is the incompatibility
order5 = {p for p in order5 if True}
# make it transitive-closed minimally (already is, only 1 non-trivial edge)
desc5, viol5 = order_descends(E5, order5, e2c5)
REPORT["models"].append({
    "id": "M5_partial_order_incompatible",
    "E": [str(e) for e in E5],
    "order_relation": relation_profile(E5, order5, "<=_tagged"),
    "behavioral_equivalence": relation_profile(E5, R5, "~B"),
    "order_descends_to_quotient": desc5,
    "violations": viol5,
    "note": "(0,A)<=(1,B) holds but (0,B)<=(1,A) does not, even though (0,A)~B(0,B) and "
            "(1,A)~B(1,B) (observations only see x). This is the compatibility FAILURE "
            "case: the order relation does not descend to E/~B -- CASE C for this model.",
})

print(json.dumps(REPORT, indent=2, default=str))
out = "/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/e80b6590-f013-40ab-b3b7-5adc71f528e7/scratchpad/ksme08_results.json"
with open(out, "w") as f:
    json.dump(REPORT, f, indent=2, default=str)
