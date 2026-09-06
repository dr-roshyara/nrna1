r"""KR-ZERO-ALGEBRA-2026-09 — core.

    Zero_{T,Pi}(x)  iff  Pi(T(D)) = Pi(T(D \ x))

NOTHING here assumes the decomposition is a direct sum, that L is a projection, or
that idempotence / commutativity / monotonicity / convergence hold.  Those are the
objects under test.

Elements are addressed BY POSITION, so two occurrences of the same token are distinct
elements.  This matters: it is what makes the duplicate witnesses meaningful.
"""
from dataclasses import dataclass, replace
from typing import Tuple, Any, Optional, Callable, FrozenSet
import itertools

# ============================================================ representations
@dataclass(frozen=True)
class Item:
    """One element of a representation.  Fields beyond `token` are metadata; a
    representation class decides which are populated."""
    token: str
    source: Optional[str] = None          # provenance
    uncertainty: Optional[float] = None
    scope: Optional[str] = None
    polarity: Optional[bool] = None       # for claim representations
    node: Optional[str] = None            # for graph representations
    edge_to: Optional[str] = None

@dataclass(frozen=True)
class Rep:
    """A representation D: an ordered tuple of items, plus its class tag."""
    cls: str                              # R1 | R2 | R3 | R4
    items: Tuple[Item, ...] = ()
    def without(self, idxs):
        s = set(idxs) if hasattr(idxs, "__iter__") else {idxs}
        return replace(self, items=tuple(it for i, it in enumerate(self.items)
                                         if i not in s))
    def __len__(self): return len(self.items)

# ============================================================ transformations  T : Rep -> Rep
STOPWORDS = {"the", "a", "of", "and", "is"}

def T1_stopword(D):
    return replace(D, items=tuple(i for i in D.items if i.token not in STOPWORDS))

def T2_dedup(D):
    seen, out = set(), []
    for i in D.items:
        if i.token not in seen:
            seen.add(i.token); out.append(i)
    return replace(D, items=tuple(out))

def T3_normalize(D):
    return replace(D, items=tuple(replace(i, token=i.token.lower()) for i in D.items))

def T4_context(D):
    """context-dependent: drop an item whose IMMEDIATE PREDECESSOR has the same token"""
    out = []
    for i, it in enumerate(D.items):
        if i > 0 and D.items[i-1].token == it.token: continue
        out.append(it)
    return replace(D, items=tuple(out))

REFERENCE_A = {"ref1", "ref2"}
REFERENCE_B = {"ref2", "ref3"}
def T5_reference(D, ref=None):
    r = REFERENCE_A if ref is None else ref
    return replace(D, items=tuple(i for i in D.items if i.token not in r))

def T6_meta_preserving(D):
    """normalize the token, keep every metadata field"""
    return replace(D, items=tuple(replace(i, token=i.token.upper()) for i in D.items))

def T7_meta_destroying(D):
    """strip ALL metadata -- deliberately included to expose vacuous contracts"""
    return replace(D, items=tuple(Item(token=i.token) for i in D.items))

def T8_interacting(D):
    return T2_dedup(T1_stopword(D))

TRANSFORMS = {"T1_stopword": T1_stopword, "T2_dedup": T2_dedup,
              "T3_normalize": T3_normalize, "T4_context": T4_context,
              "T5_reference": T5_reference, "T6_meta_preserving": T6_meta_preserving,
              "T7_meta_destroying": T7_meta_destroying, "T8_interacting": T8_interacting}
META_DESTROYING = {"T7_meta_destroying"}

# ============================================================ contracts  Pi : Rep -> comparable
def P1_result(D):        return tuple(i.token for i in D.items)
def P2_len(D):           return (P1_result(D), len(D.items))
REQUIRED_TOKENS = {"key", "must"}
def P3_required(D):      return (P1_result(D),
                                 frozenset(t for t in P1_result(D) if t in REQUIRED_TOKENS))
def P4_provenance(D):    return (P1_result(D), frozenset(i.source for i in D.items
                                                         if i.source is not None))
def P5_uncertainty(D):
    us = [i.uncertainty for i in D.items if i.uncertainty is not None]
    return (P1_result(D), round(max(us), 3) if us else None)
def P6_scope(D):         return (P1_result(D), frozenset(i.scope for i in D.items
                                                         if i.scope is not None))
def P7_contradiction(D):
    pos = any(i.polarity is True  for i in D.items)
    neg = any(i.polarity is False for i in D.items)
    return (P1_result(D), {(True,False):"positive", (False,True):"negative",
                           (True,True):"conflicting",(False,False):"none"}[(pos,neg)])
def P9_balance(D):
    """net evidential balance -- a contract that PRESERVES CANCELLATION.
    Included because it is the natural place a group-Zero-without-individual-Zero
    witness can live (adversarial case J)."""
    net = sum(1 for i in D.items if i.polarity is True) - \
          sum(1 for i in D.items if i.polarity is False)
    return ("balance", net)
def P8_composite(D):
    return (P1_result(D), P4_provenance(D)[1], P5_uncertainty(D)[1],
            P6_scope(D)[1], P7_contradiction(D)[1])

CONTRACTS = {"P1_result": P1_result, "P2_len": P2_len, "P3_required": P3_required,
             "P4_provenance": P4_provenance, "P5_uncertainty": P5_uncertainty,
             "P6_scope": P6_scope, "P7_contradiction": P7_contradiction,
             "P8_composite": P8_composite, "P9_balance": P9_balance}
# contracts that read a metadata field a metadata-destroying T would erase
METADATA_CONTRACTS = {"P4_provenance","P5_uncertainty","P6_scope","P7_contradiction",
                      "P8_composite","P9_balance"}

# ============================================================ Zero  (PATH A -- formal)
def zero(D, tname, pname, idxs):
    r"""Zero_{T,Pi}(x)  iff  Pi(T(D)) = Pi(T(D \ x)).  `idxs` may be a set (group Zero)."""
    T, P = TRANSFORMS[tname], CONTRACTS[pname]
    return P(T(D)) == P(T(D.without(idxs)))

def zero_elementwise(D, tname, pname):
    return [i for i in range(len(D)) if zero(D, tname, pname, {i})]

# ============================================================ PATH B -- declared rule
def rule_eliminable(D, tname, pname, i):
    """The DECLARED heuristic: an item is eliminable if it is a stop-word, or a
    repeat of an earlier token.  Deliberately NOT the counterfactual definition --
    the point is to measure where they disagree."""
    it = D.items[i]
    if it.token in STOPWORDS: return True
    return any(D.items[j].token == it.token for j in range(i))

# ============================================================ the L operator
def L_simultaneous(D, tname, pname):
    """Remove EVERY individually-Zero element at once."""
    return D.without(set(zero_elementwise(D, tname, pname)))

def L_sequential(D, tname, pname, order="forward"):
    """Remove one Zero element at a time, RE-CHECKING after each removal."""
    cur = D
    guard = 0
    while guard < 200:
        guard += 1
        zs = zero_elementwise(cur, tname, pname)
        if not zs: break
        pick = zs[0] if order == "forward" else zs[-1]
        cur = cur.without({pick})
    return cur

def L_rule(D, tname, pname):
    return D.without({i for i in range(len(D))
                      if rule_eliminable(D, tname, pname, i)})

OPERATORS = {"L_simultaneous": L_simultaneous, "L_sequential": L_sequential,
             "L_rule": L_rule}

# ============================================================ vacuity guard
def contract_is_vacuous(D, tname, pname):
    """A contract is VACUOUS for this (D,T) if it cannot distinguish ANY sub-selection
    -- e.g. a provenance contract after a metadata-destroying transformation.  Then
    'everything is Zero' is an artefact of the pairing, not a finding."""
    if len(D) == 0: return False
    allz = all(zero(D, tname, pname, {i}) for i in range(len(D)))
    reads_meta = pname in METADATA_CONTRACTS
    destroyed = tname in META_DESTROYING
    return allz and reads_meta and destroyed
