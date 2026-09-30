# KOS minimal semantic contract — what `L4`/`L5` actually consume (requirements-mining)

**Context:** `KOS-CONTRACT-NEUTRALITY-001` adapter-architecture investigation, continued
**Date:** 2026-09-27 · **Performer (self-declared, not attestable):** `claude-code-session:e8f324f1-...`

> ⛔ Read-only analysis. No production code touched. Derived entirely from grepping the
> actual `EdgeRules`/`GraphBuilder`/`Lcom4` source, not from documentation or inference.

---

## Why this analysis, now

Confirming and sharpening the methodological point: the recent experiment series
(`@property` through diamond `MRO`) was **already** comparing Python source→Python adapter
against PHP source→PHP adapter, each analysing its own native language — never asking one
language's adapter to understand the other's syntax. That is the correct shape and hasn't
changed. What *has* changed is the question: rather than continuing construct-by-construct,
mine the actual consuming code for the **true minimal semantic contract**, then check both
existing adapters against it directly — the diamond `STOP`'s own Gate-A conclusion
("does the analysis actually need exact MRO identity? No") generalizes into a method, not
just a one-off answer.

## The contract, field by field, evidence-grounded

| Type | Field | Consumed? | By what, exactly | Consequence |
|---|---|---|---|---|
| `DeclaredUnit` | `kind` | ✅ | `UnitEligibility::isAnalysedUnit()` (gates whether the unit is analysed at all) | Required |
| `DeclaredUnit` | `identity` | ✅ (indirectly) | Only via `UnitIdentity::toString()`, for the reported unit name | Required |
| `DeclaredUnit` | `declaredName` | **not read as a raw field anywhere outside `UnitIdentity`'s own construction** | — | Likely redundant with `identity` for current consumers — not confirmed unused, but no direct read site found |
| `MethodFacts` | `methodIdentity` | ✅ | Node-set construction, exclusion-by-name (`__construct`/`__destruct`), edge endpoints | Required |
| `MethodFacts` | `hasBody` | ❌ **confirmed unread** | Grepped every file in the capability — zero reads outside `MethodFacts`'s own constructor and the two adapters that populate it | **Currently dead weight in the consumed pipeline** — required by the type, consumed by nothing |
| `StateAccess` | `propertyName` | ✅ | `GraphBuilder`'s property-owner co-touch tracking | Required |
| `StateAccess` | `accessMode` | ❌ **confirmed unread** | Not referenced in `EdgeRules`/`GraphBuilder`/`Lcom4` at all | Collected, never consulted — matches the existing accepted test `test_a_nullsafe_property_read_still_creates_a_state_edge`, which exists specifically to prove this is a no-op |
| `BehaviourReference` | `referenceMode` | ✅ | `EdgeRules` line 27 — checked first, short-circuits everything if `CallableReference` | Required |
| `BehaviourReference` | `determinability` | ✅ | `EdgeRules` line 32 — checked second, short-circuits most later branches | Required |
| `BehaviourReference` | `qualifierKind` | ✅ | `EdgeRules` — the primary dispatch key, all ten values branched on | Required |
| `BehaviourReference` | `targetUnitRelation` | ✅, **but only for 3 of 10 qualifier kinds** | `EdgeRules` line 56-58 — only reached for `UnqualifiedName`/`FullyQualifiedName`/`RelativeName` | Conditionally required |
| `BehaviourReference` | `targetMethodName` | ✅, but **not by `EdgeRules`** | `GraphBuilder` — node-set matching (determines edge vs. `TargetNotDeclaredHere`) and the audit-trail `'target'` field | Required, but only downstream of the include/exclude decision, not part of it |
| `BehaviourReference` | `accessMode` | ❌ **confirmed unread** | Same as `StateAccess::accessMode` | Collected, never consulted |
| `BehaviourReference` | `factId` | ❌ **confirmed unread anywhere** | Reserved for future one-way provenance joining, per its own docblock | Not yet exercised by any consumer |

## Direct answer to "does each adapter provide what `LCOM4` actually needs?"

**Yes, both already do**, and the diamond `MRO` finding generalizes exactly as predicted:
neither adapter has ever needed to populate `accessMode` meaningfully (both default to
`Direct`), neither has needed `factId`, and `hasBody` is set to a constant `true` by both —
none of this is a shortfall, because **nothing downstream reads any of it**. The domain
types require these fields for totality (`INV-L3-5` — every attribute mandatory, no
default) even though the *current* consuming logic doesn't use all of them yet. That's a
real, if narrow, gap between "what totality requires an adapter to supply" and "what the
current analysis actually consumes" — worth naming precisely, not treated as a defect in
either the adapters or the model.

## Consequence for the diamond `MRO` Gate-A conclusion

This independently reconfirms it, from a different angle: `targetMethodName`'s only
downstream use is matching against the analysed unit's *own* node set and audit-trail
labeling — never resolving *which ancestor* a `ParentKeyword` reference points to. Full `C3`
MRO resolution would produce information with **no consumer anywhere in this pipeline**,
confirmed now systematically rather than case-by-case.

## What this changes about the research method going forward

Rather than "test the next Python construct and compare," the sharper question per
construct is now: **does this construct produce a value in one of the fields the table above
marks "not read"?** If so, no experiment is needed at all — any adapter's choice there is
unobservable to `L4`/`L5`, by evidence, not by assumption. If a construct would need a *new*
value in an already-consumed field (as `@property`, callable-reference, and parent-dispatch
all did), that remains the right kind of experiment to run.

**Traceability:** `EdgeRules.php`, `GraphBuilder.php`, `Lcom4.php`, `MethodFacts.php`,
`StateAccess.php`, `BehaviourReference.php`, `DeclaredUnit.php`, `UnitIdentity.php` (grepped
directly, this session) · `CohesionSemanticsTest.php` (existing accepted test confirming
`accessMode`'s no-op status) · prior reports in this directory (diamond `MRO` `STOP`,
parent-dispatch, inheritance A/B).
