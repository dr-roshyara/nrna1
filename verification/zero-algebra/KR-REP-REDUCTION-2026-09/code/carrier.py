r"""KR-REP-REDUCTION-2026-09 — carrier, source representation D, and the inquiry Q.

Implements design §3-§5.  Q is defined HERE, before any transformation module exists,
and reads only D.  (Design §5, FT-1.)
"""
import itertools, bisect
from dataclasses import dataclass, replace
from typing import Tuple, Optional

# ============================================================ design S4: the alphabets
# V is chosen so that 3-significant-digit and 2-significant-digit rounding actually
# COLLAPSE values -- otherwise T4/T3 would be identity maps and the chain would be inert.
V = (11510, 11520, 11530,      # 3sf -> 11500      2sf -> 12000
     12310, 12320,             # 3sf -> 12300      2sf -> 12000
     23410, 23420,             # 3sf -> 23400      2sf -> 23000
     34510, 34520,             # 3sf -> 34500      2sf -> 35000
     45610, 45620,             # 3sf -> 45600      2sf -> 46000
     56710)                    # 3sf -> 56700      2sf -> 57000
SOURCES = ("A", "B", "C")
TIMES   = tuple(range(8))
N_REC   = 3                    # records per case

@dataclass(frozen=True)
class Rec:
    v: Optional[int] = None        # magnitude   (levels D, R5, R4, R3)
    s: str = "A"                   # source      (all levels)
    t: Optional[int] = None        # timestamp   (level D only)
    rank: Optional[int] = None     # within-case rank (level R2 only)

@dataclass(frozen=True)
class Case:
    recs: Tuple[Rec, ...]
    def numeric(self):
        """the numeric field PRESENT at this level -- v, or rank if v is absent"""
        return tuple(r.v if r.v is not None else r.rank for r in self.recs)

# ============================================================ design S5: the inquiry Q
# TOTALS is enumerated ANALYTICALLY from (V, N_REC).  No sample, no population, no split.
TOTALS = sorted({sum(c) for c in itertools.combinations_with_replacement(V, N_REC)})

def decile(x, ref=TOTALS):
    """design S5.1 -- decile of x within the analytically enumerated total-set"""
    return min(9, (bisect.bisect_left(ref, x) * 10) // len(ref))

def argmax_source(case):
    """source of the record with the largest magnitude; ties -> 'TIE'"""
    vals = [r.v for r in case.recs]
    m = max(vals)
    winners = {case.recs[i].s for i, v in enumerate(vals) if v == m}
    return next(iter(winners)) if len(winners) == 1 else "TIE"

def Q(D):
    """Q : X -> Answers.  Reads ONLY D.  Defined before T5..T2 exist."""
    return (argmax_source(D), decile(sum(r.v for r in D.recs)))
