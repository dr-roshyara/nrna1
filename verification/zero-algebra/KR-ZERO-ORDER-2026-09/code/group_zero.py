r"""KR-ZERO-GROUP-2026-09 — the structure of group-level eliminability.

Under test, marked [PROP] and NOT [DEF]:

    Zero_{T,Pi}(S; D)   iff   Pi(T(D)) = Pi(T(D \ S))        S subset of D

The element-level form is the special case S = {x}.

The question is NOT "does an algebra exist".  It is: what is the minimal mathematical
structure of the family

    Z(D,T,Pi) = { S subset of D, S nonempty : Zero(S; D) }

Candidate regimes named in the commission -- rewriting systems, hypergraphs, closure
systems, lattices, or something else -- are TESTED, not assumed, and none is concluded
to be *the* correct framework.
"""
import itertools
from zero_algebra import (Rep, Item, TRANSFORMS, CONTRACTS, zero,
                          contract_is_vacuous, L_sequential)

def subsets(n, lo=1, hi=None):
    hi = n if hi is None else min(hi, n)
    for k in range(lo, hi + 1):
        for c in itertools.combinations(range(n), k):
            yield frozenset(c)

def zero_family(D, t, p, max_n=8):
    """Z = every non-empty subset that is Zero.  Exhaustive over the powerset."""
    n = len(D)
    if n == 0 or n > max_n: return None
    return {S for S in subsets(n) if zero(D, t, p, S)}

# ============================================================ structural predicates
def minimal(Z):
    """S in Z with no PROPER non-empty subset in Z."""
    return {S for S in Z if not any(T2 < S for T2 in Z)}

def maximal(Z):
    return {S for S in Z if not any(S < T2 for T2 in Z)}

def downward_closed(Z):
    """S in Z and T subset S, T nonempty  =>  T in Z"""
    for S in Z:
        for k in range(1, len(S)):
            for T2 in itertools.combinations(sorted(S), k):
                if frozenset(T2) not in Z: return False, (S, frozenset(T2))
    return True, None

def upward_closed_within(Z, n):
    """S in Z and S subset T  =>  T in Z   (T over the same ground set)"""
    allS = set(subsets(n))
    for S in Z:
        for T2 in allS:
            if S < T2 and T2 not in Z: return False, (S, T2)
    return True, None

def union_closed(Z):
    for A, B in itertools.combinations(Z, 2):
        if (A | B) not in Z: return False, (A, B, A | B)
    return True, None

def intersection_closed(Z):
    for A, B in itertools.combinations(Z, 2):
        I = A & B
        if I and I not in Z: return False, (A, B, I)
    return True, None

def generated_by_minimal(Z, n):
    """Is Z exactly {S : S contains some minimal Zero subset}?
    If NOT, the hypergraph of minimal Zero subsets does not determine Z."""
    M = minimal(Z)
    pred = {S for S in subsets(n) if any(m <= S for m in M)}
    return pred == Z, (pred - Z, Z - pred)

def matroid_circuit_exchange(M):
    """(C3) If C1 != C2 are circuits and e in C1 & C2, then (C1 u C2) - e contains a
    circuit.  Together with (C1) no empty circuit and (C2) no containment, this is the
    matroid circuit axiomatization.  A pass would put the family in matroid territory;
    a fail rules that out."""
    Ml = sorted(M, key=lambda s: (len(s), sorted(s)))
    for C1, C2 in itertools.combinations(Ml, 2):
        for e in (C1 & C2):
            U = (C1 | C2) - {e}
            if not any(c <= U for c in Ml):
                return False, (C1, C2, e, U)
    return True, None

def pairwise_signature(D, t, p, S):
    """The Zero-status of every singleton and pair INSIDE S."""
    sing = tuple(sorted((i, zero(D, t, p, {i})) for i in S))
    pair = tuple(sorted(((i, j), zero(D, t, p, {i, j}))
                        for i, j in itertools.combinations(sorted(S), 2)))
    return (sing, pair)
