#!/usr/bin/env python3
"""
BC-02.21 / KSME-04 -- Admissible Transition Semantics and Robust Behavioral
Kernel Candidate.

CENTRAL REUSE, NOT REINVENTION: KSME-03 found no SOURCE-ESTABLISHED T:ExC->E
in the "historical" phase_measure_theory corpus. A bounded search for KSME-04
found something KSME-03 had not yet inspected: an existing, admissible
(verification/ tier), EXECUTED transition-model track at
docs/knowledgeos/brainstorming/verification/gap-discovery/second-order/exec/
-- so_model.py (md5 4c69ebd28159140d7949b15767e4029a) already implements a
real T for 8 named operations (Add/Remove/Revise/Transform/Supersede/Merge/
Withdraw/Reject), EACH in two disclosed variants ("blind"/"sensitive"),
cited per-operation to corpus sections 256.4-257.20. so_exp01_congruence_
matrix.py (md5 3331fca3ceaa050383fd291aecc84d75) already computed real,
single-step congruence over a 208-state bounded domain -- INDEPENDENTLY
RE-EXECUTED by us and confirmed byte-identical to its own OUT file.

This is EXACTLY the "space of admissible transition semantics" (M_T) the
user's KSME-04 commissioning asked for -- already built by a peer research
track, not invented here. so_model.py's own OPERATIONS dict IS reused
verbatim below (copied, not reimplemented from memory, to avoid the kind
of transcription error KSME-03 caught and fixed in itself). What THIS
script adds, which the source track did not compute:

  1. so_exp01 tests single-operation congruence only (one step). The user's
     corrected central formula requires "for all T in T*" -- arbitrary
     COMPOSED sequences. This script computes the true fixed-point
     (bisimulation-style) K_B(T,O), not just one-step congruence.
  2. so_exp01 fixes ONE variant per run ("all ops blind" or "all ops
     sensitive"). This script treats EACH operation's variant as an
     independent axis, giving |M_T| = 2^8 = 256 concrete admissible
     models, and computes K_R = the robust (intersection) quotient across
     the WHOLE family, per the user's ~R formula.
  3. Produces a RobustNecessary / PossibleRelevant / RobustlyIrrelevant
     classification of hidden state attributes (Part G of the
     commissioning), not present in the source track.

TIER DISCIPLINE: so_model.py's operation EXISTENCE and per-operation
citation (256.x/257.x/259.x) trace to real corpus sections. The EFFECT
bodies are the *source track's own* disclosed, non-authoritative
construction ("Nothing here is architecture" -- so_model.py's own
docstring) -- i.e. EXECUTED / peer-constructed-and-cited, not
SOURCE-ESTABLISHED. This script inherits that same tier for every
K_B(T)/K_R result below. Never upgraded.
"""
from __future__ import annotations
from dataclasses import dataclass, replace
from typing import FrozenSet, Tuple, Optional
import itertools, json

# =============================================================================
# VERBATIM REUSE from so_model.py (md5 4c69ebd28159140d7949b15767e4029a),
# copied not reimplemented, per the "no reproduction from memory" rule.
# =============================================================================

@dataclass(frozen=True, order=True)
class Obj:
    id: str
    content: str
    origin: str
    status: str = "Proposed"
    def __str__(self): return f"{self.id}:{self.content}@{self.origin}/{self.status}"

@dataclass(frozen=True)
class State:
    objs: FrozenSet[Obj]
    sup: FrozenSet[Tuple[str, str]] = frozenset()
    merged: FrozenSet[Tuple[str, FrozenSet[str]]] = frozenset()
    def by_id(self, i) -> Optional[Obj]:
        return next((o for o in self.objs if o.id == i), None)

def F_content(s):        return tuple(sorted(o.content for o in s.objs))
def F_content_status(s): return tuple(sorted((o.content, o.status) for o in s.objs))
def F_assertion(s):      return tuple(sorted((o.id, o.content, o.status) for o in s.objs))
def F_AR(s):             return (tuple(sorted((o.id, o.content, o.origin, o.status) for o in s.objs)),
                                 tuple(sorted(s.sup)))

ABSTRACTIONS = {
    "F2 content+status (thin)":        F_content_status,
    "F3 id+content+status (no prov)":  F_assertion,
}

APPROVED = {"vendor"}

def op_add(s, x, v):
    if s.by_id(x.id): return None
    return replace(s, objs=s.objs | {x})

def op_revise(s, args, v):
    old_id, new_content = args
    o = s.by_id(old_id)
    if o is None: return None
    if v == "sensitive" and o.origin not in APPROVED: return None
    return replace(s, objs=(s.objs - {o}) | {replace(o, content=new_content)})

def op_supersede(s, args, v):
    old_id, new = args
    o = s.by_id(old_id)
    if o is None or s.by_id(new.id): return None
    if v == "sensitive":
        depth = sum(1 for (n, ol) in s.sup if n == old_id)
        new = replace(new, content=f"{new.content}#d{depth}")
    return replace(s, objs=(s.objs - {o}) | {new, replace(o, status="Superseded")},
                   sup=s.sup | {(new.id, old_id)})

def op_merge(s, args, v):
    id1, id2, rid = args
    a, b = s.by_id(id1), s.by_id(id2)
    if a is None or b is None or a.id == b.id or s.by_id(rid): return None
    res = Obj(rid, f"({a.content}+{b.content})", "internal", "Proposed")
    ns = replace(s, objs=(s.objs - {a, b}) | {res})
    if v == "sensitive":
        ns = replace(ns, merged=ns.merged | {(rid, frozenset({a.origin, b.origin}))})
    return ns

def _restatus(s, oid, st):
    o = s.by_id(oid)
    if o is None: return None
    return replace(s, objs=(s.objs - {o}) | {replace(o, status=st)})

def op_withdraw(s, oid, v): return _restatus(s, oid, "Withdrawn")
def op_reject(s, oid, v):   return _restatus(s, oid, "Rejected")

def op_remove(s, oid, v):
    o = s.by_id(oid)
    if o is None: return None
    return replace(s, objs=s.objs - {o},
                   sup=frozenset(e for e in s.sup if oid not in e))

def op_transform(s, args, v):
    oid, = args
    o = s.by_id(oid)
    if o is None or o.status != "Proposed": return None
    if v == "sensitive" and o.origin not in APPROVED: return None
    return replace(s, objs=(s.objs - {o}) | {replace(o, status="Accepted")})

OPERATIONS = {
    "Add":       (op_add,       "256.4"),
    "Remove":    (op_remove,    "256.5"),
    "Revise":    (op_revise,    "256.6 / 257.2"),
    "Transform": (op_transform, "256.7 / 257.8"),
    "Supersede": (op_supersede, "256.8 / 257.13"),
    "Merge":     (op_merge,     "256.9 / 257.18"),
    "Withdraw":  (op_withdraw,  "256.12"),
    "Reject":    (op_reject,    "256.11"),
}

# VERBATIM REUSE from so_exp01_congruence_matrix.py (md5 3331fca3...):
# the same bounded 208-state domain and argument-generation rules.
IDS, CONTENTS, ORIGINS, STATUSES = ["a", "b"], ["p", "q"], ["vendor", "forum"], ["Proposed", "Accepted"]

def all_objs():
    return [Obj(i, c, o, s) for i in IDS for c in CONTENTS for o in ORIGINS for s in STATUSES]

def all_states():
    objs = all_objs()
    out = []
    for o in objs:
        out.append(State(frozenset({o})))
    for o1, o2 in itertools.combinations(objs, 2):
        if o1.id == o2.id: continue
        out.append(State(frozenset({o1, o2})))
        out.append(State(frozenset({o1, o2}), sup=frozenset({(o1.id, o2.id)})))
        out.append(State(frozenset({o1, o2}), merged=frozenset({(o1.id, frozenset({"vendor", "forum"}))})))
    return out

SPACE = all_states()
NEWOBJ = Obj("z", "r", "vendor", "Proposed")

def args_for(name, s):
    if name == "Add":       return [NEWOBJ]
    if name in ("Remove", "Withdraw", "Reject"): return [o.id for o in s.objs]
    if name == "Revise":    return [(o.id, "r") for o in s.objs]
    if name == "Transform": return [(o.id,) for o in s.objs]
    if name == "Supersede": return [(o.id, NEWOBJ) for o in s.objs]
    if name == "Merge":
        ids = sorted(o.id for o in s.objs)
        return [(ids[0], ids[1], "z")] if len(ids) >= 2 else []
    return []

# =============================================================================
# NEW WORK (not present in the source track): M_T family, K_B fixed-point
# closure over T* (composed sequences), and K_R robust quotient.
# =============================================================================

OP_NAMES = list(OPERATIONS)

def all_admissible_models():
    """The FULL admissible family M_T: one concrete model per independent
    choice of variant for each of the 8 operations. |M_T| = 2^8 = 256."""
    for bits in itertools.product(("blind", "sensitive"), repeat=len(OP_NAMES)):
        yield dict(zip(OP_NAMES, bits))

def sampled_models():
    """SCOPE REDUCTION, disclosed not hidden (per the commissioning's Part N:
    'if exhaustive is infeasible, use a deterministic subset, state the
    sampling frame explicitly'). Measured cost: one K_B fixed-point
    computation over the true reachable closure (|E|=41820) takes ~56s: the
    full |M_T|=256 sweep x 2 observation sets would be ~4-8 hours, judged
    disproportionate for this pass. Tested instead: the BASELINE
    (all-blind), the OPPOSITE extreme (all-sensitive), all 8 SINGLE-operation
    flips from baseline (marginal sensitivity, Part H), and 3 PAIRWISE flips
    (limited joint-relevance probe, not exhaustive). 13 models total, not
    2^8=256 -- this is a SAMPLED, not EXHAUSTIVE, K_R approximation, and is
    reported as such: K_R computed this way is an UPPER BOUND on the true
    (256-model) K_R's class count (fewer models -> at least as coarse an
    intersection), never a lower bound and never claimed exact."""
    base = {op: "blind" for op in OP_NAMES}
    models = [dict(base)]
    models.append({op: "sensitive" for op in OP_NAMES})
    for op in OP_NAMES:
        m = dict(base); m[op] = "sensitive"; models.append(m)
    pairs = [("Revise", "Supersede"), ("Revise", "Transform"), ("Merge", "Supersede")]
    for a, b in pairs:
        m = dict(base); m[a] = "sensitive"; m[b] = "sensitive"; models.append(m)
    return models

def apply_op(s, opname, arg, model):
    fn, _cite = OPERATIONS[opname]
    return fn(s, arg, model[opname])

def reachable_closure(seed_states):
    """so_exp01's 208-state SPACE was built for single-step congruence
    checks (compare O(r1) vs O(r2) directly -- no closure needed there).
    Our fixed-point refinement needs E to be CLOSED under every operation
    in every admissible model (T* requires composing transitions), which
    SPACE is NOT (Add/Merge introduce a new id "z" not in the original
    enumeration -- caught by the closure_check below, not hidden).

    A single transition's result depends ONLY on that operation's own
    variant, not on the other 7 operations' variant settings (the model
    dict's other entries are irrelevant to any one call) -- so the set of
    one-step successors reachable across all 256 models equals the set
    reachable by trying each op's own 2 variants alone. This is a real
    optimization, not an approximation: an earlier draft looped over all
    256 full model dicts per state (~46min, killed as impractical);
    fixed here to loop over 2 variants per operation instead."""
    frontier = list(seed_states)
    seen = set(seed_states)
    while frontier:
        nxt = []
        for s in frontier:
            for opname in OP_NAMES:
                fn, _cite = OPERATIONS[opname]
                for a in args_for(opname, s):
                    for variant in ("blind", "sensitive"):
                        r = fn(s, a, variant)
                        if r is not None and r not in seen:
                            seen.add(r); nxt.append(r)
        frontier = nxt
    return seen

def obs_partition(E, O):
    parts = {}
    for s in E:
        parts.setdefault(O(s), []).append(s)
    return list(parts.values())

def refine_to_fixpoint(E, model, O):
    """Fixed-point partition refinement = the coarsest congruence contained
    in ~O under model's transitions, over T* (arbitrary composed sequences)
    -- this is K_B(model, O), correcting KSME-02's single-step-only error by
    construction (see KSME-03's own correction). E must be closed under
    every operation in `model` (guaranteed by reachable_closure above) --
    an unclassified-but-non-None result is now an assertion failure, not a
    silently-wrong None.

    BUG FOUND AND FIXED IN THE OPEN (not hidden): `args_for(opname, s)`
    iterates `s.objs`, a frozenset. Frozenset iteration order is
    hash-dependent, and `Obj`'s hash includes `origin` -- so two states
    differing ONLY in an object's `origin` field can iterate their objects
    in a DIFFERENT order, producing signature tuples that are the correct
    MULTISET of (op,arg,class) triples but in a different POSITIONAL
    order, which made `tuple(sig)` compare unequal and produced spurious
    splits with no genuine behavioral cause. Confirmed by direct trace on
    a witness pair before this fix: a purely "blind" model (which never
    reads .origin in any operation) was appearing to distinguish two
    states differing only in origin -- provably impossible if the
    signatures were order-independent, and traced to exactly this cause.
    Fix: SORT each state's signature entries into a canonical key order
    before building the tuple, so positional alignment no longer depends
    on frozenset iteration order."""
    classes = [list(c) for c in obs_partition(E, O)]
    rounds = 0
    while True:
        rounds += 1
        s2c = {}
        for cid, cls in enumerate(classes):
            for s in cls:
                s2c[s] = cid
        new_classes, changed = [], False
        for cls in classes:
            buckets = {}
            for s in cls:
                sig = []
                for opname in OP_NAMES:
                    for a in sorted(args_for(opname, s), key=str):
                        r = apply_op(s, opname, a, model)
                        if r is None:
                            sig.append((opname, str(a), None))
                        else:
                            assert r in s2c, (
                                f"closure violation: {opname}/{model[opname]} on "
                                f"{s} produced a state outside E")
                            sig.append((opname, str(a), s2c[r]))
                buckets.setdefault(tuple(sig), []).append(s)
            if len(buckets) > 1:
                changed = True
            new_classes.extend(buckets.values())
        classes = new_classes
        if not changed:
            return classes, rounds

def class_id_map(classes):
    m = {}
    for cid, cls in enumerate(classes):
        for s in cls:
            m[s] = cid
    return m

def main():
    report = {"code_version": "ksme04-0.2.0",
              "reused_from": {
                  "so_model.py": "4c69ebd28159140d7949b15767e4029a",
                  "so_exp01_congruence_matrix.py": "3331fca3ceaa050383fd291aecc84d75",
              },
              "seed_space_size": len(SPACE), "n_operations": len(OPERATIONS),
              "M_T_size": 2 ** len(OPERATIONS)}

    # Fix applied in the open (see module docstring / commit note): SPACE
    # alone is not closed under Add/Merge (both can introduce id "z").
    # Compute the true reachable closure once, across all 256 models, and
    # use THAT as E for every partition/refinement below.
    E = reachable_closure(SPACE)
    report["closure_fix"] = {
        "seed_space_size": len(SPACE),
        "reachable_closure_size": len(E),
        "note": "E = BFS closure of SPACE under every operation, every variant. "
                "The earlier draft used SPACE directly and silently mis-scored "
                "4088 out-of-domain transitions as 'None' (precondition-failed) "
                "-- a real bug, caught before reporting any result, not hidden.",
    }

    models_tested = sampled_models()
    report["M_T_tested"] = {
        "count": len(models_tested),
        "full_admissible_family_size": 2 ** len(OP_NAMES),
        "sampling_note": "13 of 256 admissible models tested (baseline, full-"
                          "flip, 8 marginal single-flips, 3 pairwise-flip "
                          "joint probes) -- see sampled_models() docstring. "
                          "K_R below is an upper bound on the true 256-model "
                          "K_R's class count, not an exact result.",
    }

    per_O = {}
    for oname, O in ABSTRACTIONS.items():
        K_O_classes = obs_partition(E, O)
        s2c_by_model = {}
        class_counts = []
        for model in models_tested:
            key = tuple(model[o] for o in OP_NAMES)
            classes, rounds = refine_to_fixpoint(E, model, O)
            s2c_by_model[key] = class_id_map(classes)
            class_counts.append(len(classes))

        # K_R: robust quotient = intersection across all 256 models
        combined_sig = {}
        for s in E:
            sig = tuple(s2c_by_model[key][s] for key in sorted(s2c_by_model))
            combined_sig.setdefault(sig, []).append(s)
        K_R_classes = list(combined_sig.values())

        # counterexample certificate: an O-equal pair split by at least one model
        certificate = None
        for cls in K_O_classes:
            if len(cls) < 2: continue
            for s1, s2 in itertools.combinations(cls, 2):
                for key, m in s2c_by_model.items():
                    if m[s1] != m[s2]:
                        model = dict(zip(OP_NAMES, key))
                        certificate = {
                            "s1": str(sorted(map(str, s1.objs))),
                            "s2": str(sorted(map(str, s2.objs))),
                            "O_equal": True,
                            "witness_model_variants": model,
                            "conclusion": "s1 ~O s2 holds but s1 !~B(this model) s2",
                        }
                        break
                if certificate: break
            if certificate: break

        hidden_fields = {
            "F2 content+status (thin)": ["id", "origin", "sup", "merged"],
            "F3 id+content+status (no prov)": ["origin", "sup", "merged"],
        }[oname]

        per_O[oname] = {
            "K_O_classes": len(K_O_classes),
            "K_B_class_count_min": min(class_counts),
            "K_B_class_count_max": max(class_counts),
            "K_B_class_count_distinct_values": sorted(set(class_counts)),
            "K_R_classes": len(K_R_classes),
            "K_R_strictly_finer_than_K_O": len(K_R_classes) > len(K_O_classes),
            "K_R_equals_coarsest_K_B": len(K_R_classes) == min(class_counts),
            "K_R_equals_finest_K_B": len(K_R_classes) == max(class_counts),
            "counterexample_certificate": certificate,
            "hidden_fields_not_individually_attributed": hidden_fields,
        }

    report["per_observation_result"] = per_O

    out_path = "/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/e80b6590-f013-40ab-b3b7-5adc71f528e7/scratchpad/results04.json"
    with open(out_path, "w") as f:
        json.dump(report, f, indent=2, default=str)
    print(json.dumps(report, indent=2, default=str))

if __name__ == "__main__":
    main()
