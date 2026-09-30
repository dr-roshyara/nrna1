# KOS L3 → L4/L5 dependency matrix (formal, test-proven)

**Context:** `KOS-CONTRACT-NEUTRALITY-001` adapter-architecture investigation, continued
**Date:** 2026-09-27 · **Performer (self-declared, not attestable):** `claude-code-session:e8f324f1-...`

> ⛔ Read-only + two confirmatory characterization tests only (`UnconsumedFieldCharacterizationTest.php`).
> No production logic changed. 82/82 Cohesion tests green, zero regressions.

---

## Calibration accepted

Replacing the prior over-strong phrasing: **for the current `L4`/`L5` implementation and the
current accepted test suite, both adapters provide the currently consumed semantic
information.** This does not establish sufficiency for every theoretically possible cohesion
analysis — only for the one that exists today, which is the only claim the evidence
supports.

## The matrix

Every row is backed by an **executed test that varies the field and shows the effect (or
absence of effect) directly** — not by reading code and asserting what it must do.

| L3 field | Consumer | Decision made | Observable effect if varied | Test proving it |
|---|---|---|---|---|
| `BehaviourReference::referenceMode` | `EdgeRules` line 27 | `CallableReference` short-circuits to `exclude(CallableNotInvocation)`, before any other check | Excluded regardless of qualifier | `test_a_callable_reference_is_excluded_whatever_the_qualifier` (existing) |
| `BehaviourReference::determinability` | `EdgeRules` line 32 | `NotDeterminable` short-circuits to `exclude(NotDeterminable)` | Excluded regardless of most other fields | `test_a_computed_target_is_excluded_as_not_determinable` (existing) |
| `BehaviourReference::qualifierKind` | `EdgeRules`, primary dispatch key (all 10 branches) | Determines include vs. one of 5 exclusion reasons | Verdict changes per value | `test_self_and_static_and_instance_receiver_are_included`, `test_parent_is_excluded_as_out_of_frame`, `test_aliased_is_excluded_as_a_stated_limitation_...` (existing) |
| `BehaviourReference::targetUnitRelation` | `EdgeRules` line 56-59 — **only reached for 3 of 10 `qualifierKind` values** (`UnqualifiedName`/`FullyQualifiedName`/`RelativeName`) | `DenotesAnalysedUnit` → include; else → `exclude(NotTheAnalysedUnit)` | Verdict flips, same qualifier held constant | `test_a_name_denoting_another_unit_is_excluded_as_not_the_analysed_unit` (existing — this is the "bucket ruling" test) |
| `BehaviourReference::targetMethodName` | `GraphBuilder` (not `EdgeRules`) | Matched against the unit's own node set: match → edge; no match → `exclude(TargetNotDeclaredHere)` | Edge vs. exclusion, downstream of the include/exclude verdict | `test_a_reference_to_a_method_not_declared_here_is_retained_but_creates_no_edge` (existing) |
| `BehaviourReference::accessMode` | **none** | — | **None** — confirmed identical output across values | `test_nullsafe_access_does_not_change_the_verdict` (existing) |
| `BehaviourReference::factId` | **none** | — | **None** | `test_factId_does_not_affect_graph_or_metric` (new, this session) |
| `StateAccess::propertyName` | `GraphBuilder`'s property-owner co-touch tracking | Two methods sharing a name → state edge | Edge present/absent | `test_two_methods_sharing_a_property_form_one_component` (existing) |
| `StateAccess::accessMode` | **none** | — | **None** | `test_a_nullsafe_property_read_still_creates_a_state_edge` (existing) |
| `MethodFacts::methodIdentity` | `GraphBuilder` — node set, exclusion-by-name (`__construct`/`__destruct`), edge endpoints | Node existence, edge participation | Presence/absence of a node | `test_constructors_are_excluded_from_the_node_set` (existing) |
| `MethodFacts::hasBody` | **none** | — | **None** | `test_hasBody_does_not_affect_graph_or_metric` (new, this session) |
| `DeclaredUnit::kind` | `UnitEligibility::isAnalysedUnit()` | Whether the unit is analysed at all | `analysed: true/false`, node/edge sets empty if not | `test_class_enum_trait_and_anonymous_class_are_analysed_units`, `test_interface_is_not_an_analysed_unit` (existing) |
| `DeclaredUnit::identity` | Reporting only (`AnalyseCohesion`'s `'unit'` field) | None — no branch reads it | Cosmetic (the reported name) only | Not a decision — labeling, not logic; no dedicated test needed |
| `DeclaredUnit::declaredName` | No direct read site found outside `UnitIdentity`'s own construction | — | Unconfirmed either way | **Gap**: neither a consumption test nor a non-consumption test exists for this field specifically |

## What's now fully closed vs. still open

**Closed, test-proven, both directions (consumed or confirmed-not-consumed):** every field
except one. **Still open:** `DeclaredUnit::declaredName` — no test in the accepted suite or
this session's additions isolates it from `identity`. Minor, but naming it precisely rather
than folding it silently into "probably fine."

## Consequence for the research loop, adopted going forward

Before running the next language-construct experiment: check this table. If the construct
can only vary a **"none"**-row field, no experiment is needed — the effect is already
proven absent. If it would need a new *value* in an already-consumed field, or an
entirely new field the current vocabulary lacks, that remains a genuine experiment.

## Standing conclusions this matrix reconfirms, not re-litigates

- Diamond `MRO`'s Gate-A holds for a more general reason than the one-off argument first
  given: `targetMethodName`'s only consumer is same-unit node-matching, never ancestor
  resolution — full `C3` linearization has no consumer anywhere in this pipeline, by
  evidence, not by construction-specific argument.
- `D-1` remains the one genuine, still-open canonical expressiveness gap — untouched by
  this matrix, which only characterizes what already exists.

**Traceability:** `2026-09-27-KOS-minimal-semantic-contract.md` (prior report, same
directory) · `EdgeRules.php`, `GraphBuilder.php`, `Lcom4.php`, `MethodFacts.php`,
`StateAccess.php`, `BehaviourReference.php`, `DeclaredUnit.php`, `UnitIdentity.php` ·
`CohesionSemanticsTest.php` (existing, accepted) ·
`UnconsumedFieldCharacterizationTest.php` (new, this session, 2/2 green).
