#!/usr/bin/env python3
"""
Independent gap-discovery session — executable reference kernel for KnowledgeOS.

Purpose: produce EXECUTED evidence where the corpus has only PROSE.
Nothing here is architecture. Nothing here is authoritative. It is a witness.

The kernel implements the corpus's TERMINAL formal model, taken from
Steps 262/263/265/267/269:

    K = (A, R)          A = set of Assertion
                        R subset of A x A x RelationType
    Assertion = (id, P, e, c, t, Pi)      (Step 267 s267.6)
    Pi = provenance, placed INSIDE the assertion by Step 265's verdict.

It then runs falsification experiments against that model. Each experiment
states its hypothesis, its construction, and its observed result. No result is
declared PASS unless the code produced it.
"""
from __future__ import annotations
from dataclasses import dataclass, field, replace
from typing import FrozenSet, Tuple, Callable, Iterable
import itertools, json

# ---------------------------------------------------------------- primitives

@dataclass(frozen=True, order=True)
class Proposition:
    """Step 262: a proposition is (entity, dimension, value)."""
    entity: str
    dimension: str
    value: str
    def __str__(self): return f"{self.entity}.{self.dimension}={self.value}"

@dataclass(frozen=True, order=True)
class Provenance:
    """Step 265: (source, method, timestamp). Placed inside the assertion."""
    source: str
    method: str
    at: int
    def __str__(self): return f"<{self.source}/{self.method}@{self.at}>"

@dataclass(frozen=True)
class Assertion:
    """A = (id, P, e, c, t, Pi)."""
    id: str
    P: Proposition
    e: FrozenSet[str]          # evidence ids
    c: str                     # context
    t: int                     # assertion time
    Pi: Provenance
    sigma: str = "Supported"   # epistemic status
    def __str__(self): return f"{self.id}:{self.P} {self.Pi} sigma={self.sigma}"

RelationType = str  # 'derives', 'supersedes', 'contradicts', 'refers'

@dataclass(frozen=True)
class K:
    """K = (A, R). The corpus's terminal knowledge state."""
    A: FrozenSet[Assertion]
    R: FrozenSet[Tuple[str, str, RelationType]]   # (from_id, to_id, type)
    def by_id(self, i): return next((a for a in self.A if a.id == i), None)
    def props(self): return sorted(str(a.P) for a in self.A)

def mk(assertions: Iterable[Assertion], rels=()) -> K:
    return K(frozenset(assertions), frozenset(rels))

# ------------------------------------------------------- candidate equalities
# The corpus never fixes ONE. Each of these is defensible from some step.

def eq_structural(k1: K, k2: K) -> bool:
    "Every field of every assertion, plus R. (Step 261 'structural equality'.)"
    return k1.A == k2.A and k1.R == k2.R

def eq_content(k1: K, k2: K) -> bool:
    "Propositions only. (The 'content-only abstraction' of Step 255.)"
    return k1.props() == k2.props()

def eq_content_status(k1: K, k2: K) -> bool:
    "Propositions + epistemic status. (Q6-era reading.)"
    return (sorted((str(a.P), a.sigma) for a in k1.A)
            == sorted((str(a.P), a.sigma) for a in k2.A))

def eq_semantic_id(k1: K, k2: K) -> bool:
    "Assertion identity only. (Identity-based membership.)"
    return {a.id for a in k1.A} == {a.id for a in k2.A} and k1.R == k2.R

EQUALITIES = {
    "structural":      eq_structural,
    "content-only":    eq_content,
    "content+status":  eq_content_status,
    "identity-only":   eq_semantic_id,
}

# ------------------------------------------------------------------ operations
# 'Mandatory operations' O. The corpus (Step 266) never closes this set.
# We therefore define TWO defensible closures and show the answer depends on it.

def op_query(k: K, prop_str: str) -> bool:
    return prop_str in k.props()

def op_explain(k: K, prop_str: str):
    "Return the provenance chain for a proposition. Step 265 s265.16 'explanation test'."
    return sorted(str(a.Pi) for a in k.A if str(a.P) == prop_str)

def op_withdraw_source(k: K, source: str) -> K:
    """Retract everything traceable to a source, then cascade over 'derives'.
    Step 025m / Step 265 s265.10 persistence+withdrawal."""
    dead = {a.id for a in k.A if a.Pi.source == source}
    changed = True
    while changed:
        changed = False
        for (f, to, typ) in k.R:
            if typ == "derives" and to in dead and f not in dead:
                dead.add(f); changed = True
    A2 = frozenset(a for a in k.A if a.id not in dead)
    R2 = frozenset(r for r in k.R if r[0] not in dead and r[1] not in dead)
    return K(A2, R2)

def op_contest_count(k: K, prop_str: str) -> int:
    """How many distinct assertions currently contradict this proposition?
    A governance-plausible operation: escalation policies count contestations."""
    ids = {a.id for a in k.A if str(a.P) == prop_str}
    return len({f for (f, to, typ) in k.R if typ == "contradicts" and to in ids})

OPS_MINIMAL = {"query": op_query, "explain": op_explain}
OPS_FULL    = {"query": op_query, "explain": op_explain,
               "withdraw": op_withdraw_source, "contest": op_contest_count}

# ------------------------------------------------------------------ transform
@dataclass(frozen=True)
class Op:
    name: str
    payload: object

def T(k: K, o: Op) -> K:
    "K_{t+1} = T(K_t, o). Deterministic, total on the ops defined here."
    if o.name == "assert":
        return K(k.A | {o.payload}, k.R)
    if o.name == "relate":
        return K(k.A, k.R | {o.payload})
    if o.name == "withdraw":
        return op_withdraw_source(k, o.payload)
    if o.name == "restatus":
        aid, s = o.payload
        a = k.by_id(aid)
        return K((k.A - {a}) | {replace(a, sigma=s)}, k.R)
    raise ValueError(o.name)

def replay(k0: K, ops) -> K:
    for o in ops: k0 = T(k0, o)
    return k0
