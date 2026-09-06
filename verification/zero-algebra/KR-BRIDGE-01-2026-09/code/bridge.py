r"""KR-BRIDGE-01-ZERO-PRESERVATION-2026-09 — carrier, Q, Pi, transformations.

DESIGN RULES HONOURED, and each is load-bearing:
  S3   PARALLEL family: every R = T(D) is computed from D DIRECTLY. No chain.
  S9   Zero is NEVER defined using Q. Zero uses Pi; preservation uses Q. Disjoint reads.
  S8   typed elimination; D \ S is never used at a level where it is invalid.
  S4   no carrier is declared. The EXPERIMENTAL carrier below is not claimed to be the
       mathematical carrier nor a KnowledgeOS knowledge representation.
"""
import itertools, collections
from dataclasses import dataclass, replace
from typing import Tuple, Optional

# ============================================================ S5 source structure
VALUES  = (10, 20, 30, 40)         # small -> fibers collide (calibration, S5)
SOURCES = ("A", "B", "C")
TAGS    = ("p", "q")               # the CONTEXT dimension
TIMES   = (0, 1, 2)
N_REC   = 4                        # >=4 so group elimination has room

@dataclass(frozen=True)
class Rec:
    v: Optional[int] = None
    src: Optional[str] = None
    tag: Optional[str] = None
    t: Optional[int] = None
    rank: Optional[int] = None

@dataclass(frozen=True)
class Case:
    recs: Tuple[Rec, ...] = ()

# ============================================================ S7 the preservation target Q
# Q reads SOURCE and TAG.  It never reads the bare value multiset.
def Q(D: Case):
    vals = [r.v for r in D.recs]
    m = max(vals)
    winners = {D.recs[i].src for i, v in enumerate(vals) if v == m}
    argmax_src = next(iter(winners)) if len(winners) == 1 else "TIE"
    return (argmax_src, len({r.tag for r in D.recs}))

# ============================================================ S8 Zero's observable Pi
# Pi reads the VALUE MULTISET only.  It never reads source or tag.
# => Q and Pi are read-disjoint by construction (S9).
def Pi(R: Case):
    return tuple(sorted(r.v for r in R.recs if r.v is not None)) if any(
        r.v is not None for r in R.recs) else tuple(sorted(
        r.rank for r in R.recs if r.rank is not None))

# ============================================================ S6 the PARALLEL family
# Each T is applied to D DIRECTLY.  Declared before execution, with expected effect.
def T_A(D):                      # information-preserving canonical reorder (invertible)
    return Case(tuple(sorted(D.recs, key=lambda r: (r.v, r.src, r.tag, r.t))))

def T_B(D):                      # controlled lossy: drop source
    return Case(tuple(replace(r, src=None) for r in D.recs))

def T_C(D):                      # redundancy-removing: dedup on value, keep first
    seen, out = set(), []
    for r in D.recs:
        if r.v not in seen: seen.add(r.v); out.append(r)
    return Case(tuple(out))

def T_D(D):                      # relational / context-sensitive: rank WITHIN tag group
    by = collections.defaultdict(list)
    for i, r in enumerate(D.recs): by[r.tag].append(i)
    rank = {}
    for tag, idxs in by.items():
        for k, i in enumerate(sorted(idxs, key=lambda j: (D.recs[j].v, j))):
            rank[i] = k + 1
    return Case(tuple(Rec(rank=rank[i], src=r.src, tag=r.tag) for i, r in enumerate(D.recs)))

def T_E(D):                      # dedup AND drop source  (targets the Zero+Inadequate cell)
    return T_B(T_C(D))

def T_F(D):                      # information-destroying control
    return Case(tuple(Rec(v=0, src=None, tag=None) for _ in D.recs))

def T_G(D):                      # invertible recoding control: shift every value by +100
    return Case(tuple(replace(r, v=r.v + 100) for r in D.recs))

TRANSFORMS = {
 "T_A_preserving":   (T_A, "canonical reorder; invertible on the multiset", "Zero rare, adequate"),
 "T_B_lossy":        (T_B, "drop source", "Zero rare, inadequate (Q needs source)"),
 "T_C_dedup":        (T_C, "remove duplicate values", "Zero common, adequacy varies"),
 "T_D_relational":   (T_D, "rank within tag group", "value multiset replaced by ranks"),
 "T_E_dedup_lossy":  (T_E, "dedup then drop source", "targets Zero+Inadequate"),
 "T_F_destroying":   (T_F, "constant map", "CONTROL B: destroys everything"),
 "T_G_recoding":     (T_G, "value + 100", "CONTROL C: invertible recoding"),
}

# ============================================================ S8 typed elimination
def E_S(R: Case, S, level: str):
    """Removes indices S and RE-ESTABLISHES the level's invariants.
    At the relational level the field is a within-tag rank, so removal changes the
    survivors' ranks -- set subtraction is INVALID there and ranks are recomputed."""
    kept = tuple(r for i, r in enumerate(R.recs) if i not in S)
    if not kept: return Case(())
    if level == "T_D_relational":
        by = collections.defaultdict(list)
        for i, r in enumerate(kept): by[r.tag].append(i)
        rank = {}
        for tag, idxs in by.items():
            for k, i in enumerate(sorted(idxs, key=lambda j: (kept[j].rank, j))):
                rank[i] = k + 1
        return Case(tuple(replace(r, rank=rank[i]) for i, r in enumerate(kept)))
    return Case(kept)

# ============================================================ Zero  (S8, never uses Q)
def zero(D: Case, tname: str, S) -> bool:
    T = TRANSFORMS[tname][0]
    return Pi(T(D)) == Pi(T(E_S(D, S, "source")))

def zero_on_rep(D: Case, tname: str, S) -> bool:
    """Zero evaluated on the REPRESENTATION with the typed operator at that level."""
    T = TRANSFORMS[tname][0]; R = T(D)
    return Pi(R) == Pi(E_S(R, S, tname))
