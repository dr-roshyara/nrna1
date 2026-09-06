r"""KR-BRIDGE-02 — the Π × Q grid, with READ-DISJOINTNESS enforced as a gate.

KR-BRIDGE-01 held Π and Q fixed, leaving Q4/Q5/Q10 unanswered. This varies both.

⚠️ THE ADMISSIBILITY GATE, and it is not a formality:
    Π and Q must read DISJOINT FIELDS. If they share a field, Zero and preservation
    are no longer independently defined and the bridge is partly built into the
    definitions — the exact circularity KR-BRIDGE-01 §9 forbids.
    Inadmissible pairs are EXCLUDED and REPORTED, never silently tested.
"""
import collections
from bridge import Case, Rec

# ---------------------------------------------------------------- Π  (Zero's observable)
def Pi_multiset(R):  return ("mset",) + tuple(sorted(r.v for r in R.recs if r.v is not None)) \
                            if any(r.v is not None for r in R.recs) else \
                            ("mset",) + tuple(sorted(r.rank for r in R.recs if r.rank is not None))
def Pi_set(R):       return ("set",)  + tuple(sorted({r.v for r in R.recs if r.v is not None})) \
                            if any(r.v is not None for r in R.recs) else \
                            ("set",)  + tuple(sorted({r.rank for r in R.recs if r.rank is not None}))
def Pi_tagmset(R):   return ("tags",) + tuple(sorted(r.tag for r in R.recs if r.tag is not None))
def Pi_srcmset(R):   return ("srcs",) + tuple(sorted(r.src for r in R.recs if r.src is not None))
def Pi_arity(R):     return ("arity", len(R.recs))

PIS = {
 "Pi_value_multiset": (Pi_multiset, frozenset({"v"}),   "sorted value multiset"),
 "Pi_value_set":      (Pi_set,      frozenset({"v"}),   "DISTINCT values — dedup-insensitive"),
 "Pi_tag_multiset":   (Pi_tagmset,  frozenset({"tag"}), "sorted tag multiset"),
 "Pi_source_multiset":(Pi_srcmset,  frozenset({"src"}), "sorted source multiset"),
 "Pi_arity":          (Pi_arity,    frozenset(),        "record count only — degenerate control"),
}

# ---------------------------------------------------------------- Q  (preservation target)
def Q_argmax_ntags(D):
    vals=[r.v for r in D.recs]; m=max(vals)
    w={D.recs[i].src for i,v in enumerate(vals) if v==m}
    return (next(iter(w)) if len(w)==1 else "TIE", len({r.tag for r in D.recs}))
def Q_srcset(D):     return tuple(sorted({r.src for r in D.recs}))
def Q_majtag_total(D):
    c=collections.Counter(r.tag for r in D.recs); top=c.most_common()
    maj = top[0][0] if len(top)==1 or top[0][1]!=top[1][1] else "TIE"
    return (maj, sum(r.v for r in D.recs))
def Q_maxval(D):     return (max(r.v for r in D.recs),)
def Q_tagset(D):     return tuple(sorted({r.tag for r in D.recs}))

QS = {
 "Q_argmax_ntags":  (Q_argmax_ntags, frozenset({"src","tag"}), "argmax source + #distinct tags"),
 "Q_source_set":    (Q_srcset,       frozenset({"src"}),       "set of sources present"),
 "Q_majtag_total":  (Q_majtag_total, frozenset({"tag","v"}),   "majority tag + total"),
 "Q_max_value":     (Q_maxval,       frozenset({"v"}),         "maximum value"),
 "Q_tag_set":       (Q_tagset,       frozenset({"tag"}),       "set of tags present"),
}

def admissible(pi_name, q_name):
    """READ-DISJOINTNESS GATE — the anti-circularity requirement."""
    _, pf, _ = PIS[pi_name]; _, qf, _ = QS[q_name]
    return len(pf & qf) == 0, sorted(pf & qf)

def grid():
    ok, blocked = [], []
    for p in PIS:
        for q in QS:
            a, shared = admissible(p, q)
            (ok if a else blocked).append({"Pi": p, "Q": q, "shared_fields": shared})
    return ok, blocked
