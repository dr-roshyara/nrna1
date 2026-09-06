#!/usr/bin/env python3
"""
Second-order investigation — shared model for the congruence matrix.

Implements the corpus's OWN specified test (Step 259 s259.15):

    F(H1) = F(H2)   =>   F(T_hat(H1,c)) = F(T_hat(H2,c))     for all T in T_mandatory

The corpus DEFINES this test in Steps 255, 257 (s257.28-31) and 259 (s259.9-15)
and leaves EVERY cell of its three dependency matrices unresolved:
    s256.28  operation x information      (9 x 6, all ? or -)
    s257.22  four-operation dependency    (4 x 8, mostly ?)
    s259.9   congruence matrix            (17 rows, all UNRESOLVED)

This module makes those cells computable over a bounded domain.
Nothing here is architecture.
"""
from __future__ import annotations
from dataclasses import dataclass, replace
from typing import FrozenSet, Tuple, Optional

@dataclass(frozen=True, order=True)
class Obj:
    """Fields span exactly the dimensions the corpus's matrices ask about:
    identity, content, provenance, epistemic status (+ supersession via State)."""
    id: str
    content: str
    origin: str
    status: str = "Proposed"
    def __str__(self): return f"{self.id}:{self.content}@{self.origin}/{self.status}"

@dataclass(frozen=True)
class State:
    objs: FrozenSet[Obj]
    sup: FrozenSet[Tuple[str, str]] = frozenset()                    # (new_id, old_id)
    merged: FrozenSet[Tuple[str, FrozenSet[str]]] = frozenset()      # (result_id, origins)
    def by_id(self, i) -> Optional[Obj]:
        return next((o for o in self.objs if o.id == i), None)

EMPTY = State(frozenset())

# ------------------------------------------- candidate abstractions (259.6)
def F_content(s):        return tuple(sorted(o.content for o in s.objs))
def F_content_status(s): return tuple(sorted((o.content, o.status) for o in s.objs))
def F_assertion(s):      return tuple(sorted((o.id, o.content, o.status) for o in s.objs))
def F_AR(s):             return (tuple(sorted((o.id, o.content, o.origin, o.status) for o in s.objs)),
                                 tuple(sorted(s.sup)))
def F_AR_merge(s):       return (F_AR(s), tuple(sorted((r, tuple(sorted(ss))) for r, ss in s.merged)))
def F_full(s):           return (tuple(sorted(map(str, s.objs))), tuple(sorted(s.sup)),
                                 tuple(sorted((r, tuple(sorted(ss))) for r, ss in s.merged)))

ABSTRACTIONS = {
    "F1 content-only":         F_content,
    "F2 content+status":       F_content_status,
    "F3 id+content+status":    F_assertion,
    "F4 K=(A,R) [TERMINAL]":   F_AR,
    "F5 K=(A,R)+mergesrc":     F_AR_merge,
    "F6 full state [control]": F_full,
}

# ------------------------------------------------------------- operations
# Two variants each, because the corpus leaves the dependency '?' and
# s259.11/12/13 say the counterexample is CONDITIONAL on establishing it.
APPROVED = {"vendor"}

def op_add(s, x, v):
    if s.by_id(x.id): return None
    return replace(s, objs=s.objs | {x})

def op_revise(s, args, v):
    old_id, new_content = args
    o = s.by_id(old_id)
    if o is None: return None
    if v == "sensitive" and o.origin not in APPROVED: return None      # s259.11
    return replace(s, objs=(s.objs - {o}) | {replace(o, content=new_content)})

def op_supersede(s, args, v):
    old_id, new = args
    o = s.by_id(old_id)
    if o is None or s.by_id(new.id): return None
    if v == "sensitive":                                                # s259.12
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
    if v == "sensitive":                                                # s256.9 / s257.20
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
