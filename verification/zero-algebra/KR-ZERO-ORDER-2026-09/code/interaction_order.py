r"""KR-ZERO-ORDER-2026-09 — the INTERACTION ORDER of eliminability.

Commissioned question:

    What is the smallest k such that the Zero status of S CANNOT be determined
    from all subsets of S of size <= k ?

FORMALIZATION, and the one design decision that matters:

    determination is tested WITHIN A FIXED CONTEXT (D, T, Pi).

Rationale: `KR-ZERO-GROUP` established that Zero is context-dependent (case I, robust,
267/746).  A cross-context test would therefore be answering a question already settled
and would report `k = infinity` trivially.  The live question is whether, HOLDING THE
CONTEXT FIXED, low-order data suffices.  The cross-context variant is run separately and
labelled as the strictly stronger requirement it is.

    sigma_k(S) = the Zero-status of every PROPER subset T of S with |T| <= k,
                 canonically relabelled to positions 0..|S|-1 so that two subsets of the
                 same size are comparable.

    (D,T,Pi) is k-DETERMINED at size m  iff  no two S, S' of size m share sigma_k
                                            while differing in Zero(S).

Subsets are PROPER, so sigma_k never contains S itself; k ranges over 1..m-1.
If determination fails at every k <= m-1, the subset is IRREDUCIBLE at order m:
its eliminability is not a function of any proper-subset information.
"""
import itertools, collections
from zero_algebra import TRANSFORMS, CONTRACTS, zero, contract_is_vacuous

def canonical_sig(D, t, p, S, k):
    """Zero-status of every proper subset of S of size <= k, indexed by POSITION IN S."""
    Ss = sorted(S); m = len(Ss)
    pos = {e: i for i, e in enumerate(Ss)}
    out = []
    for size in range(1, min(k, m - 1) + 1):
        for T in itertools.combinations(Ss, size):
            out.append((size, tuple(pos[e] for e in T), zero(D, t, p, set(T))))
    return tuple(sorted(out))

def minimal_k(D, t, p, m):
    """Smallest k in 1..m-1 that determines Zero for all size-m subsets of D.
    Returns (k, None) or (None, witness) when irreducible at this order."""
    n = len(D)
    if m < 2 or m > n: return None, None
    subs = [frozenset(c) for c in itertools.combinations(range(n), m)]
    if len(subs) < 2: return None, None            # nothing to compare against
    for k in range(1, m):
        groups = collections.defaultdict(set)
        rep = {}
        for S in subs:
            sig = canonical_sig(D, t, p, S, k)
            groups[sig].add(zero(D, t, p, S))
            rep.setdefault(sig, []).append(S)
        bad = [sig for sig, v in groups.items() if len(v) > 1]
        if not bad:
            return k, None
        if k == m - 1:
            sig = bad[0]
            return None, {"size_m": m, "k_exhausted": k,
                          "colliding_subsets": [sorted(x) for x in rep[sig][:3]]}
    return None, None

def context_profile(D, t, p, max_n=6):
    """Minimal k per subset size, plus irreducibility."""
    n = len(D)
    if not (2 <= n <= max_n) or contract_is_vacuous(D, t, p): return None
    prof, irre = {}, []
    for m in range(2, n + 1):
        k, w = minimal_k(D, t, p, m)
        if k is not None: prof[m] = k
        elif w is not None: prof[m] = "irreducible"; irre.append(w)
    return {"profile": prof, "irreducible": irre} if prof else None
