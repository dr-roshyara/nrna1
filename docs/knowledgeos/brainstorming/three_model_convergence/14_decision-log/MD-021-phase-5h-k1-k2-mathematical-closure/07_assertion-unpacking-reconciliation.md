# Phase 5H — Assertion-Unpacking Reconciliation (this phase's central finding)

## The three prose/code variants, all raw-source confirmed

| Source | Unpacking |
|---|---|
| **D285-1 §2** (prose) | `Assertion → {Proposition, Entity, Evidence, Context, Time, Provenance}` (6 named conceptual fields) |
| **D285-6 §3** (prose) | `Assertion → {Proposition, Entity, Observation} + {id, c, t, Π}` (3 conceptual + 4 technical fields, includes `Observation`, excludes `Evidence`/`Context`/`Provenance` as named terms) |
| **`exec/t285_reconcile.py`** (executable code) | `ASSERTION_CONTAINS = {"Proposition","Entity","Evidence","Context","Time","Provenance"}` — **matches D285-1's prose exactly** |
| **`exec/t285_equality.py`** (executable code) | `UNPACK = {"Proposition","Entity","Observation"}` (comment: *"Assertion = (id,P,e,c,t,Pi): P←Proposition/Entity, e←Observation(after Qualify)"*) — **matches D285-6's prose exactly** |
| **`exec/e_equality.py`** (executable code) | `A(P, e, c, t, Pi)` — a **5-parameter constructor**: `P` (proposition-shaped tuple), `e` (evidence-shaped tuple), `c` (context string), `t` (time tuple), `Pi` (policy/provenance string) — **a fourth variant**, naming neither `Entity` nor `Observation` as independent top-level parameters at all (Entity appears nested inside `P`'s own tuple structure, per D285-6's own gloss) |

## The finding, stated precisely

**This is not a two-way prose disagreement (Phase 5G's own framing) — it is a disagreement that runs
through the corpus's own executable code as well, and the code's two scripts disagree with each
other in the same pattern as the two prose documents** (`t285_reconcile.py` ↔ D285-1;
`t285_equality.py` ↔ D285-6). `e_equality.py` supplies a third, structurally different parameterization
again.

## Testing the required reconciliation questions (per the authorization's §10)

1. **Does one supersede the other?** No supersession statement was found in either prose document or
   either script.
2. **Are they scope-specific?** Plausible for the *prose* pair (D285-1 doing a name-level comparison;
   D285-6 doing a formal equality test) — but this explanation **does not extend to the code pair**,
   since both scripts are doing the *same kind* of formal analysis (`t285_reconcile.py`: primitive
   comparison and projection well-definedness; `t285_equality.py`: which equality relation holds) —
   if they were merely scope-specific, they should agree, and they do not.
3. **Do they represent different versions?** Plausible, and this phase's own best-supported hypothesis:
   `t285_equality.py`'s own docstring explicitly frames itself as a **correction** of a prior
   overclaim ("D285-6's own claim... was NOT a well-formed proposition... CORRECT: ...=_semantic...").
   This suggests `t285_equality.py` may be a **later refinement** than `t285_reconcile.py` — but
   neither script's own header states this, and `t285_reconcile.py` is not explicitly marked
   superseded anywhere.
4. **Are they semantically equivalent under a mapping?** Not tested; no document proposes a mapping
   between the two unpacking schemes themselves.
5. **Are they contradictory?** **Yes, at face value** — one scheme includes `Evidence/Context/
   Provenance` and excludes `Observation`; the other does the reverse.
6. **Is the relationship unresolved?** **Yes — this is the governing verdict.**

## Verdict

**AUDIT FINDING — FROZEN SOURCE, EXTENDED.** This is not merely a documentation inconsistency (which
might be dismissed as sloppy prose) — it is a **substantive inconsistency in the corpus's own
executable, machine-verifiable research artifacts**, which is a stronger form of evidence than prose
and correspondingly a more serious finding. **This directly and materially affects every claim in this
reconstruction that has relied on "unpacking Assertion"** — Phase 5F/5G's own "semantic correspondence"
verdicts (`03` shared-primitive audit; `06`/`09` in Phase 5G) are built on *some* version of this
unpacking, and this phase's own finding shows the corpus itself has not settled which version is
authoritative. **No repair is made** — this is reported as a SOURCE-RESEARCH GAP (`11`), the clearest
and most consequential one this phase found.
