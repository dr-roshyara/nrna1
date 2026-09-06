r"""Transformations T5..T2 (design S8), contract C (S6), fixed decoder O (S7),
and the typed per-level elimination operator E_S^(n) (S14).

Every Tn consumes the PREVIOUS representation.  None is defined on D except T5.
"""
from dataclasses import replace
from carrier import Case, Rec, decile, N_REC

# ============================================================ level schemas (FT-3)
LEVEL_FIELDS = {
    "D":  frozenset({"v", "s", "t"}),
    "R5": frozenset({"v", "s"}),
    "R4": frozenset({"v", "s"}),
    "R3": frozenset({"v", "s"}),
    "R2": frozenset({"rank", "s"}),
}
CHAIN = ["D", "R5", "R4", "R3", "R2"]

def populated_fields(rec):
    return frozenset(f for f in ("v", "s", "t", "rank") if getattr(rec, f) is not None)

# ============================================================ S8: the chain
def T5(D):
    """drop timestamps"""
    return Case(tuple(Rec(v=r.v, s=r.s) for r in D.recs))

def T4(R5):
    """round magnitude to 3 significant digits"""
    return Case(tuple(replace(r, v=round(r.v, -2)) for r in R5.recs))

def T3(R4):
    """round magnitude to 2 significant digits"""
    return Case(tuple(replace(r, v=round(r.v, -3)) for r in R4.recs))

RANK_CONVENTION = "ascending"      # "ascending": rank 3 = largest | "descending": rank 1 = largest

def T2(R3, convention=None):
    """replace magnitude by within-case RANK.

    ⚠️ The design left the rank DIRECTION unspecified, and it is load-bearing: the fixed
    decoder O takes an argmax of the numeric field, so
        ascending  (rank 3 = largest) -> O's argmax SUCCEEDS
        descending (rank 1 = largest) -> O's argmax FAILS
    The direction carries NO information about D -- it is a pure encoding convention.
    Both are therefore run as a declared factor (design DECISION-01: semantic evaluation
    must be invariant under non-evidential variation of the representation).

    TIE-BREAK, declared: ties broken by FIRST OCCURRENCE.
    """
    conv = convention or RANK_CONVENTION
    if conv == "ascending":
        order = sorted(range(len(R3.recs)), key=lambda i: (R3.recs[i].v, i))
    else:
        order = sorted(range(len(R3.recs)), key=lambda i: (-R3.recs[i].v, i))
    rank = {idx: k + 1 for k, idx in enumerate(order)}
    return Case(tuple(Rec(rank=rank[i], s=r.s) for i, r in enumerate(R3.recs)))

TRANSFORMS = {"R5": T5, "R4": T4, "R3": T3, "R2": T2}

def build_chain(D, rank_convention=None):
    """R5=T5(D), R4=T4(R5), R3=T3(R4), R2=T2(R3) -- each consumes the previous OUTPUT"""
    out = {"D": D}
    prev = D
    for lvl in ("R5", "R4", "R3", "R2"):
        prev = T2(prev, rank_convention) if lvl == "R2" else TRANSFORMS[lvl](prev)
        out[lvl] = prev
    return out

# ============================================================ S6: contract C
def C(R, level, sources_in_D):
    """admissible iff  (i) well-typed at the level schema
                       (ii) every source present in D still represented
                       (iii) arity preserved"""
    if len(R.recs) != N_REC: return 0
    want = LEVEL_FIELDS[level]
    for r in R.recs:
        if populated_fields(r) != want: return 0
    if {r.s for r in R.recs} != sources_in_D: return 0
    return 1

# ============================================================ S7: fixed decoder O
def O(R):
    """FIXED and deliberately naive: argmax of the numeric field present, and the
    decile of its SUM.  At R2 the numeric field is a RANK, so the sum is not a
    magnitude -- the decile component must fail while argmax may still succeed."""
    nums = R.numeric()
    m = max(nums)
    winners = {R.recs[i].s for i, x in enumerate(nums) if x == m}
    src = next(iter(winners)) if len(winners) == 1 else "TIE"
    return (src, decile(sum(nums)))

# ============================================================ S14: typed elimination
def E_S(R, level, S):
    """E_S^(n) : R_n -> R_n.  Removes indices S and RE-INDEXES.
    At R2 the numeric field is a within-case rank, so removal CHANGES the survivors'
    ranks -- set subtraction is INVALID there and ranks are recomputed."""
    kept = tuple(r for i, r in enumerate(R.recs) if i not in S)
    if not kept: return Case(())
    if level == "R2":
        order = sorted(range(len(kept)), key=lambda i: (kept[i].rank, i))
        newrank = {idx: k + 1 for k, idx in enumerate(order)}
        return Case(tuple(Rec(rank=newrank[i], s=r.s) for i, r in enumerate(kept)))
    return Case(kept)
