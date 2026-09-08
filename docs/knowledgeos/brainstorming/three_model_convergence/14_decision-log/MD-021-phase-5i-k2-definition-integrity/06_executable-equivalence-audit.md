# Phase 5I — Executable Equivalence Audit (5 required relations, per the authorization's §6)

## 1. Structural equality (do the three scripts construct the same data structures?)

**Definition**: identical field sets/types across all three scripts' own representations of `Assertion`.
**Test**: compare `ASSERTION_CONTAINS` (`t285_reconcile.py`) vs. `UNPACK` (`t285_equality.py`) vs. the
`A(P,e,c,t,Pi)` parameter list (`e_equality.py`).
**Result**: **FALSE.** `ASSERTION_CONTAINS = {Proposition,Entity,Evidence,Context,Time,Provenance}` ≠
`UNPACK = {Proposition,Entity,Observation}` ≠ `A`'s own params `{P,e,c,t,Pi}` (which don't even use
the same field names as either set literal). **Evidence**: direct code comparison, this phase.

## 2. Representational equivalence (do they encode the same information differently?)

**Definition**: a stated, checkable transformation exists mapping one representation onto another
without loss.
**Test**: is there a mapping from `t285_reconcile.py`'s field names to `e_equality.py`'s `A(...)`
parameters?
**Result**: **UNRESOLVED — no transformation is stated or evidenced anywhere.** A plausible informal
correspondence exists (`e`↔Evidence, `c`↔Context, `t`↔Time, `Pi`↔Provenance-or-Policy) but this phase
does not assert it as established, since D2/D4's own gloss calls `Π` "Policy" while `e_equality.py`'s
worked example populates `Pi` with provenance-shaped content (`"origin:scan"`) — **a fifth
discrepancy**: `Π`/`Pi` is glossed as "Policy" in D285-6/`t285_equality.py`'s own comment but used as
a provenance-tag value in `e_equality.py`'s own worked example.

## 3. Semantic equivalence (do they establish the same conceptual result?)

**Definition**: same conclusion about K-1↔K-2 (or, for `e_equality.py`, about K-2's own internal
equality structure).
**Test**: compare the three scripts' own stated conclusions.
**Result**: **PARTIALLY.** `t285_reconcile.py` and `t285_equality.py` reach a compatible high-level
conclusion (a semantic-only, lossy projection holds) — but `t285_reconcile.py`'s own T-A computation
(`03`) is inconsistent with the Sañjaya-revised prose both scripts nominally build on, while
`t285_equality.py` does not address `Observation`'s special status at all. `e_equality.py` addresses a
different question entirely (K-2's own internal equality relations, not the K-1→K-2 projection) — not
directly comparable.

## 4. Observational equivalence (do they answer the same queries the same way?)

**Definition**: same truth values for the same defined query set.
**Test**: compare `t285_equality.py`'s hardcoded `QUERIES` dict against `e_equality.py`'s executed
`eq_obs`.
**Result**: **NOT COMPARABLE** — `t285_equality.py`'s queries are named strings with hardcoded
booleans (`member/contradicts/...`); `e_equality.py`'s queries are executable lambdas over concrete
state sets (`len`, and a dead-code vacuous comparator). They do not share a query vocabulary or
implementation.

## 5. Behavioral equivalence over the implemented query set

**Definition**: running the actual code of each script produces mutually consistent output.
**Test**: re-executed all three scripts' own core computations this phase (`00`, `03`, `04`, `05`).
**Result**: **FALSE, with one specific, disclosed inconsistency** — `t285_reconcile.py`'s own T-A
output (`Observation` in the "ABSENT" bucket) is inconsistent with its own T-C output (`Observation`
assigned a non-dropped image in the `pi` dict) — an intra-script inconsistency — and is further
inconsistent with the Sañjaya-revised prose both D285-1 and D285-6 nominally incorporate.

## Overall verdict

**The three executable scripts are NOT mathematically equivalent under any of the five tested
relations**, except a qualified, partial semantic-equivalence between two of the three. **No
transformation bridging their different field sets is evidenced anywhere** — per the authorization's
own instruction (§6), this is reported as **UNRESOLVED**, not forced into a false reconciliation.
