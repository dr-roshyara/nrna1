# R6 — `static_methods`: no architecture change, research branch closed

**Context:** `KOS-PYTHON-RULE-VALIDATION`, Rule 6 · **Date:** 2026-09-27
**Result:** characterization only — 2 new tests, 0 production files touched, 134/134
Cohesion tests green.

---

## R6 hypothesis

The pinned `static_methods` rule ("statics are nodes; an isolated static inflates LCOM4 —
known, pinned, revisitable") generalizes to a genuine Python `@staticmethod` — including
the case a `@staticmethod` has no `self`/`cls` receiver at all and can only reference a
sibling method via the bare class name.

## Why this, and why now

The eight pinned decisions are now fully covered except this one. The existing
`PythonClassStaticDispatchExperimentTest.php` (pre-R1) already tests a `@staticmethod`
being *called* via `self.`/`cls.` — but never in isolation, and never a true
`@staticmethod` *calling another method itself* (a real Python staticmethod receives no
implicit first argument at all, so `self.`/`cls.` are unavailable inside its own body —
the only way it can name a sibling is the bare class name, which R3 fixed).

## Evidence (real execution, unmodified post-R5 implementation)

**Case 1 — isolated static** (matches `static-methods.php` exactly): a static touching
neither `self`/`cls` nor any property is an isolated node, alongside a normal instance
method. PHP LCOM4 = 2 (golden value); Python: identical nodes, identical LCOM4.

**Case 2 — true static-to-static chain** (matches `static-call-chain.php` exactly): two
`@staticmethod`s, neither with a `self`/`cls` parameter, one calling the other via the
bare class name (`A.beta()`), converge with PHP's `self::beta()` equivalent field-for-
field: same edge (`[['alpha','beta','behaviour']]`), same LCOM4 (1).

**Historical note, not re-verified by reverting code**: before R3, this second case was
provably unreachable — the bare-class-name call produced zero facts (established multiple
times this session), so `alpha`/`beta` would have wrongly reported as two isolated
components (LCOM4 = 2, not the correct 1). `static_methods` therefore could not have been
validated for a genuine Python static method until R3 existed — R6 was blocked on R3 by
construction, not by oversight.

## L3 / L4 / L5

No new field, no new branch beyond what R1/R3/R5 already built. Both cases converge using
only the existing `MethodRole::Ordinary` default (statics are never lifecycle methods),
the existing `self`/`cls` receiver mechanism, and R3's bare-own-name mechanism. Nothing
in this rule required anything new.

## Classification: **A — semantic convergence**

Confirmed, not assumed: the theory was falsifiable (either case could have diverged) and
didn't. This closes the rule with the strongest classification available, using
machinery entirely inherited from three already-frozen prior slices.

## Production changes

**None.**

## Remaining note

The pinned decision's own "revisitable" annotation (whether an isolated static *should*
inflate LCOM4 at all) is a PO/ARB-level design question, not a cross-language question —
explicitly out of this branch's scope, same disclaimer pattern as `D-1`'s incorporation
question.

## Theoretical consequence

All eight originally pinned `expected.json` decisions have now been individually
rule-validated against Python:

| Rule | Classification |
|---|---|
| `constructors` | C → fixed (R1) |
| `intra_class_calls` | covered by the pre-existing frozen branch (D-1, evidence-precision correction) |
| `first_class_callables` | A (pre-existing frozen branch) |
| `static_methods` | **A (R6)** |
| `trait_methods` | A/B (R2) |
| `inherited_methods` | covered by the pre-existing frozen branch |
| `magic_methods` | B/F (R4) |
| `own_class_name_resolution` | D → fixed (R3), extended to `StateAccess` E → fixed (R5) |

**Every pinned decision has now been independently exercised against a genuinely
different language.** Two real, material gaps were found and fixed (R1, R3/R5, both
symmetric or Python-specific as evidenced, never assumed); the rest converged or were
confirmed as already-correct, deliberate abstractions.

## One highest-information next question (not started)

With every *named* pinned rule now validated, the next genuinely high-information
question is no longer "which pinned rule is untested" — it's: **does a real Python
corpus (not hand-written fixtures) surface any construct outside all eight named rules
entirely?** Everything in R1–R6 was synthetic, by necessity (no Python production corpus
exists here). This is the one remaining gap every report in this branch has flagged and
deferred. It requires either sourcing an external Python corpus or reconsidering whether
this project has one — a resourcing question, not a research one, and therefore not
something to decide unilaterally here.

**No production change justified. Research branch closed.**

**Traceability:** `expected.json` (`static_methods`), `static-methods.php`,
`static-call-chain.php` (golden fixtures) · `StaticMethodsRuleValidationTest.php` (new,
2/2 green) · `PythonClassStaticDispatchExperimentTest.php` (pre-existing, not duplicated)
· `2026-09-27-KOS-PYTHON-RULE-VALIDATION-R3-implementation.md` (the fix this rule's
Case 2 depends on).
