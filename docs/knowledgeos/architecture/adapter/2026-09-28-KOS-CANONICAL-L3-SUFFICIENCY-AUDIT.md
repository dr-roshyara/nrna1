# Canonical L3 sufficiency audit

**Context:** synthesizes R1–R6, OWD-1–7, and the pre-existing minimal-semantic-contract/
dependency-matrix reports from the earlier frozen adapter-architecture branch ·
**Date:** 2026-09-28
**Phase:** synthesis only. **No production code changed.**

---

## Purpose

Not a new experiment — a consolidation. Every prior slice tested one construct; this
audit asks the five questions OWD-7's review named, over the FULL accumulated evidence:

1. What information has empirical evidence shown must cross the adapter boundary?
2. What information is deliberately abstracted?
3. Which fields are actually consumed by L4/L5?
4. Which distinctions change the metric?
5. Which distinctions only improve evidence/edge precision?

## 1. The full L3 field inventory, current shape (read directly, this session)

```php
DeclaredUnit(UnitKind $kind, UnitIdentity $identity, ?string $declaredName, list<MethodFacts> $methods)
MethodFacts(string $methodIdentity, bool $hasBody, list<StateAccess>, list<BehaviourReference>, MethodRole $methodRole = Ordinary)
StateAccess(string $propertyName, AccessMode $accessMode)
BehaviourReference(string $targetMethodName, QualifierKind, TargetUnitRelation, ReferenceMode, AccessMode, Determinability, ?string $factId)
MethodRole { Ordinary, Lifecycle }                      — added R1
```

## 2. Per-field consumption status, updated with everything learned since the original dependency matrix

| Field | Status | Evidence |
|---|---|---|
| `DeclaredUnit::kind` | **Required** | Gates `UnitEligibility::isAnalysedUnit()` |
| `DeclaredUnit::identity` | Required (reporting only) | Unit label in every observation |
| `DeclaredUnit::declaredName` | **Confirmed unconsumed** | **CORRECTION (OWD-11, 2026-09-28)**: this row was wrong when first written — an already-existing test (`DeclaredNameCharacterizationTest.php`) already proved non-consumption byte-for-byte; not consumed by never having any downstream reader, exactly the `hasBody`/`accessMode`/`factId` bucket. See `2026-09-28-KOS-OWD11-declaredname-resolution.md` |
| `MethodFacts::methodIdentity` | **Required** | Node identity — R1 (lifecycle exclusion), OWD-3 (must be occurrence-keyed, not name-keyed, once collisions are possible) |
| `MethodFacts::hasBody` | **Confirmed unconsumed** | Dependency matrix, unchanged since; no slice in this entire investigation ever gave it a consumer |
| `MethodFacts::methodRole` | **Required** | R1 — the one confirmed, implemented L3 extension this whole investigation ever justified from first principles |
| `StateAccess::propertyName` | **Required** | Every state-edge determination |
| `StateAccess::accessMode` | **Confirmed unconsumed** | Dependency matrix, unchanged |
| `BehaviourReference::targetMethodName` | **Required**, but only downstream of the include/exclude verdict (`GraphBuilder` node-matching), never part of `EdgeRules`'s decision itself | Unchanged since the dependency matrix; OWD-3 additionally proved this field alone is insufficient for target resolution once names collide (needed occurrence-aware matching in `GraphBuilder`, not a new L3 field) |
| `BehaviourReference::qualifierKind` | **Required** | Primary `EdgeRules` dispatch key; R3/OWD-3 added new *values reached* (`UnqualifiedName`/`AliasedName` from Python) but no new *kind* |
| `BehaviourReference::targetUnitRelation` | **Required**, conditionally (3 of 10 qualifier kinds) | Unchanged |
| `BehaviourReference::referenceMode` | **Required** | Unchanged |
| `BehaviourReference::accessMode` | **Confirmed unconsumed** | Dependency matrix, unchanged |
| `BehaviourReference::determinability` | **Required** | Unchanged; D-1's entire classification rests on this field |
| `BehaviourReference::factId` | **Confirmed unconsumed**, reserved | Dependency matrix, unchanged |

**Net change since the original dependency matrix, across R1–R6 and OWD-1–7: exactly one
field added (`MethodRole`), zero fields removed, zero previously-unconsumed fields
became consumed.** Every other correction in this entire investigation (R3, R5, OWD-3,
OWD-5) was achieved by changing *what populates or how `Domain` uses* the existing
fields — never by adding a new one. OWD-4 and OWD-7 (both still open) are the first
candidates since R1 that might need something new — see §5.

## 3. Answering the five questions

### Q1 — what must cross the adapter boundary (confirmed necessary, by evidence)

Exactly the fields marked Required above, nothing more. This has been remarkably
stable: one addition (`MethodRole`) across the entire investigation, despite testing
well over a dozen distinct Python-specific constructs.

### Q2 — what is deliberately abstracted

Recovered from primary pinned-decision text, not inferred, earlier in this investigation
(the `KOS-CONTRACT-NEUTRALITY-001` D-1 work): dynamic/computed dispatch
(`$this->$name()`, `getattr(self, name)()`), first-class callable references, magic-method
runtime-triggered invocation (R4), inherited-member resolution, trait/mixin composition
(R2 — later found to be the SAME invariant as inheritance, not a separate one). All
textually grounded in `expected.json`'s pinned decisions, not behavior-derived.

### Q3 — which fields are actually consumed by L4/L5

See §2's Required rows. Three fields (`hasBody`, both `accessMode` fields, `factId`)
remain confirmed dead weight in the currently-implemented pipeline, unchanged by any
finding in this entire investigation — a standing, minor, already-disclosed gap between
"what totality requires an adapter to supply" and "what the current analysis consumes."

### Q4 — which distinctions change the metric (materiality-confirmed)

Every one of these was measured, not assumed: R1 (constructor exclusion, PHP2/Python1→
both 2), R3 (bare own-class-name call, PHP2/Python3→both 2), R5 (class-level state
access, both LCOM4 wrong by 1), OWD-3 (property identity collision, LCOM4 off by exactly
the collision count), OWD-5 (nested closure misattribution, LCOM4 off by exactly the
false-edge count), OWD-7 (property call-form, same shape as OWD-3/decorator case,
**not yet corrected**).

### Q5 — which distinctions only improve evidence/edge precision (no metric-value change)

The frozen inheritance-evidence-precision correction (`TargetNotDeclaredHere` vs.
`NotDeterminable`, pre-R1) and R5's aliased-state-access finding (`AliasedSpelling`
exclusion reason, correct now instead of silent) — both real corrections, zero LCOM4
value change in the cases tested.

## 4. The invariant OWD-7's review named, restated as a standing methodological result, not a one-off

> Semantically equivalent constructs should converge to the same canonical
> representation whenever the current analytical contract treats their semantics as
> equivalent.

This is not new to OWD-7 — it is retroactively the unifying description of **three**
corrections this investigation already made, independently discovered, now visibly the
same pattern:

- **R1**: PHP `__construct`/Python `__init__` — different spelling, same lifecycle
  semantics → unified under `MethodRole::Lifecycle`.
- **OWD-3**: decorator-form getter/setter — same declared-method semantics as any two
  ordinarily-named methods → unified by fixing node identity to be occurrence-based,
  not name-based.
- **OWD-7 (open)**: decorator-form vs. call-form property construction — same runtime
  descriptor semantics, currently different L3 outcomes (recognized vs. invisible).

OWD-7 is not a new principle; it is the third confirmed instance of one, which is
stronger evidence for treating it as a standing design property of this system than any
single instance would be.

## 5. Current open gaps, named precisely, neither closed nor abandoned

- **OWD-4** (property read/write target resolution): semantically resolved (Python's
  descriptor protocol is deterministic), representation not yet decided. No new L3
  field clearly required — most likely resolved by adding read/write (`Load`/`Store`)
  context to what the adapter already reports for a property access, consumed by
  `GraphBuilder`'s existing target-matching, not by a new `Domain` concept.
- **OWD-7** (property call-form): classification D, adapter-only, existing vocabulary
  sufficient in principle — but implementing it well requires *also* touching OWD-4's
  question (a call-form property's reads and writes have the identical read/write
  ambiguity a decorator-form property does). These two open items are therefore not
  independent — a correction addressing one properly should address both, or
  explicitly punt on the read/write axis for both identically (the "connect to every
  candidate" fallback), which is the more conservative, smaller, and currently more
  defensible option given neither has been authorized yet.

## 6. The minimum L3 contract, stated positively

A canonical unit is: a kind, an identity, and a list of methods. A canonical method is:
an identity, a lifecycle role (two-valued), a list of state touches (each a bare
property name), and a list of behaviour references (each a target name, a
closed-vocabulary *how-it-was-written* qualifier, a closed-vocabulary *does-it-denote-
this-unit* relation, whether it's an invocation or a bare reference, and whether the
target is determinable at all). Two access-mode fields and one provenance-join field
exist for totality but are not yet exercised. This vocabulary, unchanged since one
addition in R1, has now absorbed over a dozen independently-tested Python-specific
constructs — evidence it is closer to sufficient than exhaustive feature coverage would
have suggested, and evidence that when it *has* needed to change, the change was small,
targeted, and empirically forced rather than anticipated.

## 7. Recommendation

**Audit complete. No new experiment started.** The next genuinely open decision is not
a research question but an implementation-sequencing one: whether to author a single,
combined correction addressing OWD-4 and OWD-7 together (since they share the same
read/write axis), or to close OWD-7 alone first using the "connect to every candidate"
fallback (matching OWD-3's own precedent for its analogous ambiguity) and leave OWD-4's
sharper resolution for later. That choice is yours to make, not a research finding this
audit can resolve on its own.

**Traceability:** every R1–R6 and OWD-1–7 implementation/characterization report, this
directory · the pre-existing `2026-09-27-KOS-minimal-semantic-contract.md` and
`2026-09-27-KOS-L3-L4-L5-dependency-matrix.md` (the earlier frozen branch's own version
of this audit, extended here rather than duplicated) · `MethodFacts.php`,
`BehaviourReference.php`, `StateAccess.php`, `DeclaredUnit.php` (re-read directly, this
session, to ground §1–2 in current code, not memory).
