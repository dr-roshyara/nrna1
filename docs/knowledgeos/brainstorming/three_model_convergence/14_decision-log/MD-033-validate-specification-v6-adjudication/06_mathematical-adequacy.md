# Mathematical Adequacy Test

## Question

Is there enough source-grounded specification, from admissible evidence alone, to define a
mathematically meaningful object for `Validate` without importing executable semantics?

## Answer: yes, for a partial function; no, for a total specification with defined failure behavior

**The minimal formal object that admissible evidence supports:**

```
Validate : D ⇀ C
D = { {Claim, Evidence}, {Hypothesis, Evidence} }
C = { Verdict }
Validate(x) = Verdict  for x ∈ D
Validate has no state effect (established negatively, 03/05)
Validate is undefined outside D (no "else" clause is stated anywhere in the admissible population)
```

This is exactly the same formal object MD-031's `09` already constructed for the *executable*
baseline rule (`V0`) — restated here as independently reconstructible from admissible narrative
evidence alone, without reference to that prior mathematical audit's own reasoning (this study
re-derives it fresh, per its own cold-read discipline).

## What is established

- Domain, codomain, and the mapping rule itself: fully stated, source-grounded, unambiguous within
  the admissible population.
- Partiality: honestly represented — no invented "else" branch, no invented error value, no invented
  `⊥`. The admissible sources simply do not address the out-of-domain case, and this study does not
  manufacture one.
- Well-definedness on the stated domain: yes — the rule maps every element of `D` to the single value
  `Verdict`, consistently, with no admissible source suggesting otherwise.
- One further clean fact, negative but genuine: no state effect.

## What is NOT established, and blocks a full formal specification (not a composition test per se)

A composition test in the sense this whole MD-02x/03x sequence has used ("Pair 1: B `Validate` ↔ C1
P-3," MD-024/029) requires mapping `Validate`'s own contract onto another model's own construct
(P-3's `Confidence` property, per MD-029). That kind of test does not, strictly, require
preconditions/postconditions/failure semantics to be stated — MD-029 already ran exactly this test
using less information than is now available (it had only the output carrier, not even the input
carriers) and reached a defensible, appropriately-hedged result (`FUNCTIONAL ANALOGY`, level 3/6).
**So the missing preconditions/postconditions/failure semantics are not, strictly, a blocker for a
*repeat* of that specific kind of test** — they would matter for a *stronger* claim (e.g., a formal
equivalence or structural correspondence test, which the six-level ladder reserves for claims this
reconstruction has consistently found itself unable to support with the evidence available).

## Smallest missing specification element, if a stronger (not merely repeat-Pair-1) test were
attempted

Failure/error semantics — specifically, what `Validate` does or returns when given evidence
insufficient to produce a determinate `Verdict` (a real, recurring theme in this whole research
programme's own DDD analyses of "Confidence"/"Verdict"-shaped concepts) — is the single item most
likely to matter for any test attempting to go beyond `FUNCTIONAL ANALOGY` toward
`STRUCTURAL CORRESPONDENCE` or higher, since structural correspondence typically requires showing
preserved behavior across edge cases, not merely the successful path.
