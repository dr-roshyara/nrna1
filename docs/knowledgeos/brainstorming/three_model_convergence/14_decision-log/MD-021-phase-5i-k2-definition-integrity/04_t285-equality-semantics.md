# Phase 5I — `t285_equality.py` Executable-Semantics Audit

## A. Object construction

Only label sets again (`RATIFIED`, `IMAGE`, `DROPPED`, `TARGET`) — no data-record instances.

## B. Equality

**Test 1 (structural)**: `IMAGE == TARGET`, genuine Python set equality — **computed, not hardcoded.**
**Test 2 (semantic)**: `UNPACK <= IMAGE`, genuine Python subset test — **computed, not hardcoded.**
**Test 3 (observational)**: the `QUERIES` dict `{"member":True,"contradicts":True,...,"replay":False,
"policy-eval":False,"authorize":False}` is a **hardcoded dictionary of boolean literals** — the script
does not execute any query against any data structure to derive these truth values; they are asserted
directly, then merely partitioned via a list comprehension (`ok`/`no`) — a real computation over a
fake (non-derived) input.

## C. Identity

Not addressed directly; the script's own conclusion explicitly distinguishes structural/semantic/
observational as **different, non-interchangeable relations** (its own closing text: *"a property
stated without naming its equality is not a well-formed proposition"*), which is itself a genuine,
disciplined mathematical statement — but it never constructs or claims object identity.

## D. Projection

Uses `IMAGE` (hardcoded to `{"Proposition","Entity","Relation","State","Observation"}`, i.e., the 8
minus the 3 explicitly dropped) as a stand-in for "π's non-dropped images" — **this set is authored
directly, not computed from `t285_reconcile.py`'s own `pi` dict or `ASSERTION_CONTAINS`/
`VERIF_EXPANDED` machinery** — the two scripts do not share code or import from each other.

## E. Query space

The three "equality tests" above constitute the entire query space this script implements. Test 3's
own query set (`member/contradicts/supersede/lineage` vs. `replay/policy-eval/authorize`) is **named
but never operationalized** — no function computes whether e.g. `replay` is actually answerable; the
verdict is simply declared.

## F. Information loss

Re-derives the same three-way verdict already reported in Phase 5F/5G/5H (structural FALSE, semantic
TRUE-after-unpacking, observational FALSE) — this phase's own contribution is the **Test-2-vs-Test-3
computation/assertion distinction** (headline finding #2, `00`): Test 2 is a genuine, checkable
computation; Test 3 is not.

## G. Failure behavior

No error handling; if `UNPACK` or `IMAGE` were ever inconsistent with the primitive lists used
elsewhere (as `03` shows they in fact are, relative to `t285_reconcile.py`'s own sets), nothing in
this script would detect or flag it — the two scripts have no shared validation.

## H. Hidden assumptions

1. That `UNPACK = {"Proposition","Entity","Observation"}` correctly represents what `Assertion`
   unpacks to — a hand-authored constant, matching D285-6's prose but **not** `t285_reconcile.py`'s
   own `ASSERTION_CONTAINS`.
2. That the `QUERIES` dict's boolean literals correctly represent what is/is not answerable in
   `(𝒜,ℛ)` — asserted, not derived.
3. That `IMAGE`'s membership (which primitives are "non-dropped") is self-evident from the primitive
   list minus the 3 explicitly-dropped ones — this is a reasonable inference, but it is **authored**,
   not computed from any shared source of truth with `t285_reconcile.py`.

## Verdict

**The script's own genuinely rigorous discipline (Test 1 and Test 2 are real computations; the
closing "not a well-formed proposition" finding is a real methodological correction) coexists with one
un-computed test (Test 3) presented with the same formatting and confidence as the computed ones.**
This is disclosed precisely, not used to discredit the script's genuine contributions.
