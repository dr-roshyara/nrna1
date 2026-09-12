"""Reach(S): the set of carrier kinds constructible by valid typed
composition of the operators in S, from the ambient carriers.

Composition rule (explicit, per Part VI / XIX):
  a step applies ONE operator o to the currently available carriers; the
  step introduces exactly o.atoms; the produced kinds are whatever the
  carrier derivation table yields for (available_kinds, o.atoms).
Reach = least fixpoint of that step relation.

This is monotone, terminating, and independent of operator NAMES.
"""
from .carriers import AMBIENT, derive_kinds

def reach(ops, ambient=None):
    """ops: iterable of Op. Returns (kinds, witness) where witness[kind] is
    the list of (operator_name, round) that first produced it."""
    avail = set(ambient if ambient is not None else AMBIENT)
    witness = {k: ("ambient", 0) for k in avail}
    r = 0
    while True:
        r += 1
        added = False
        for o in ops:
            for k in derive_kinds(avail, o.atoms):
                if k not in avail:
                    avail.add(k); witness[k] = (o.name, r); added = True
        if not added:
            return avail, witness

def atom_pool(ops):
    p = set()
    for o in ops: p |= set(o.atoms)
    return p

def exclusive_atoms(ops):
    """atoms held by exactly one operator in ops"""
    from collections import Counter
    c = Counter()
    for o in ops:
        for a in o.atoms: c[a] += 1
    return {o.name: sorted(a for a in o.atoms if c[a] == 1) for o in ops}
