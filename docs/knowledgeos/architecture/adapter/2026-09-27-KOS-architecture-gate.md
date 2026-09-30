# KOS architecture gate — before implementation authorization

**Context:** `KOS-CONTRACT-NEUTRALITY-001` adapter-architecture investigation, continued
**Date:** 2026-09-27 · **Performer (self-declared, not attestable):** `claude-code-session:e8f324f1-...`

> ⛔ **Gate review only.** No production code, no test code, no interface created, no Python
> adapter, no `expected.json`, no `deptrac.yaml` change, no PO/ARB request. Everything below
> is design, and two real corrections to the prior design are made rather than defended.

---

## 1 · Decision summary

**The prior proposal's Slice 1 (interface + façade + one delegation test) would have proven
polymorphism exists, not that anything depends on it — the exact trap it named but didn't
fully avoid.** This gate corrects that, and one more thing: port ownership. Port ownership
is **Application, not Domain** (§6) — a genuine revision, not a restatement. A concrete,
already-adopted architecture-enforcement mechanism exists in this repository and should be
extended, not invented (§10).

## 2 · Evidence, and one new fact this gate adds

All four prior reports stand. **New, checked directly for this gate:**
`AnalyseCohesion::observe(FactSet $facts): array` — it already takes a **`FactSet`, not a
source string.** The composition `AnalyseCohesion::observe(PhpFactExtractor::extract($source))`
currently happens **inline inside a test's private helper method**
(`tests/Unit/Cohesion/CohesionPipelineTest.php:17`), not anywhere in Application or
Infrastructure production code. This is the composition-root problem stated precisely: it
isn't merely "no orchestrator exists" — the exact wiring that *would* become the composition
root already exists, verbatim, but only inside a test.

## 3 · Critical distinction, held throughout this gate

Functional boundary (`FactSet` already separates concerns) ≠ dependency-inversion boundary
(something in Application/Core must be constructed against the *interface*, substitutable in
a test with a fake, not merely delegate-tested against the one real implementation).

## 4 · Port input, reviewed field-by-field (none added automatically)

| Candidate addition | Required by current KOS semantics? | Verdict |
|---|---|---|
| filename/path | No — `FactSet`'s own `OQ-2` identity model is declaration-name/ordinal-based, not path-based; `PhpFactExtractor::extract()` already takes none | Not added |
| language tag | No — redundant: the *implementation type itself* (`PhpSemanticFactProvider` vs. a future `PythonSemanticFactProvider`) already carries this | Not added |
| provenance | No — provenance is already a separate, one-way `factId`-joined record (existing `BehaviourReference::$factId`), not port input | Not added |
| multi-file scope | No — explicitly out of current scope (`OQ-2`); would be a future, separate port revision if ever authorized, not a reason to generalize now | Not added |
| diagnostics/error channel | **Genuinely unknown, flagged not assumed** — `PhpFactExtractor::extract()`'s behavior on malformed/unparseable source was not checked in any prior report and is not checked here (out of this gate's scope); **the RED test in §9 must pin down current behavior on at least one malformed input before the interface signature is treated as final**, rather than assume it needs an error channel or assume it doesn't | Open, resolved by the RED test itself |
| configuration/options | No evidence any exists or is needed | Not added |

**`string $source` stands**, with one caveat now made explicit rather than silently assumed:
malformed-input behavior must be pinned by a test before this signature is called settled.

## 5 · Naming

`SemanticFactProvider` already avoids `Parser`/`AST`/`Php`-flavored naming — confirmed
adequate on review, no change proposed.

## 6 · Port ownership — corrected

**Application owns the port, not Domain.** Reasoning: `GraphBuilder`/`EdgeRules`/`Lcom4`
never call anything resembling extraction — they are complete and self-sufficient given an
already-constructed `DeclaredUnit`; Domain has *zero* coupling to "how do I obtain one."
The need for "turn source into facts" arises exactly at the orchestration point
(`AnalyseCohesion`, Application) — that is where the port is actually *consumed*, and
"which inner component defines the abstraction Infrastructure must implement" is answered by
identifying the actual consumer, not by where the output *type* happens to be defined. `FactSet`
and its constituent types remain Domain-owned (they always were); **the port *interface*
itself is Application-owned.** This corrects the prior proposal's `Ports/` top-level folder
and its "Domain owns it" claim — both revised below (§7).

## 7 · Package placement (revised)

```
Capabilities/Cohesion/
├── Domain/              (unchanged)
├── Application/
│   ├── AnalyseCohesion.php        (existing — gains one new method, §9)
│   └── Ports/
│       └── SemanticFactProvider.php   ← NEW, interface, Application-owned
└── Infrastructure/
    └── Php/
        ├── PhpFactExtractor.php   (unchanged)
        └── PhpSemanticFactProvider.php  ← NEW, façade, implements the Application-owned port
```

`Ports` nested under `Application`, not a repository-wide top-level folder — corrected from
the prior proposal, following the ownership finding in §6, not a folder-naming preference.

## 8 · Composition root — the actual smallest point (design only)

**Not a new "Application Service" class merely to satisfy a diagram** (explicitly rejected,
per the brief's own instruction). The smallest genuine composition point is **one new method
on the already-existing `AnalyseCohesion`**, taking the port as a parameter:

```php
// Application/AnalyseCohesion.php — ONE new method, design only, not written:
public static function observeFromSource(SemanticFactProvider $provider, string $source): array
{
    return self::observe($provider->extract($source));
}
```

This is the exact inline composition already present in the test (§2), moved to a place a
real caller could depend on **the interface**, injected — not the concrete `PhpFactExtractor`.
It changes nothing about `observe()` or anything downstream; `GraphBuilder`/`EdgeRules`/
`Lcom4` are untouched, exactly as required.

## 9 · First TDD vertical slice (design only — not written)

**RED**, before any production code: two tests, not one, because a single delegation-equality
test (the prior proposal's whole plan) *cannot* distinguish "an interface exists" from "the
interface is actually depended on polymorphically" — that is precisely the gap this gate
exists to close.

1. **Behavior-preservation test**: `AnalyseCohesion::observeFromSource(new PhpSemanticFactProvider(), $source)`
   produces output identical to the existing `AnalyseCohesion::observe(PhpFactExtractor::extract($source))`,
   for at least one existing fixture. Proves nothing regressed.
2. **Genuine substitutability test**: construct a small in-test **fake** implementing
   `SemanticFactProvider` (returning a hand-built `FactSet`, no PHP extraction involved at
   all) and assert `observeFromSource()` produces the correct observation from *that* fake —
   proving `observeFromSource()` depends only on the interface's contract, not on anything
   `PhpFactExtractor`-specific. **This is the test that actually proves dependency inversion**;
   test 1 alone would not.
3. **The malformed-input case from §4**: at least one test exercising `extract()` on invalid
   source, to pin current behavior (exception? empty `FactSet`? something else?) before the
   signature is treated as finished — currently unknown, not assumed either way.

**GREEN**: the two new files (§7) plus the one new method (§8) — nothing else.
**REFACTOR**: none anticipated; the slice is deliberately too small to need it, but the rule
(re-run the full relevant suite, no behavior drift) applies regardless.

## 10 · Architecture enforcement — extend what already exists, don't invent

**`deptrac.yaml` already exists in this repository** and already enforces exactly
`Domain ← Application ← Infrastructure` — for the `Contestation`/`Adjudication`/`Election`/
`Shared` bounded contexts, via `directory` collectors per layer. **`scripts/lib/EngineeringKnowledge/`
is not in its `paths` list today.** The smallest reliable mechanism is not a new tool — it is
a fourth context block in this same file: `CohesionDomain`/`CohesionApplication`/
`CohesionInfrastructure` collectors pointed at `scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/{Domain,Application,Infrastructure}/.*`,
with the identical inward-dependency rule already used for the other three contexts.

**One thing this gate flags rather than decides:** `deptrac.yaml`'s own header states its
rules are themselves an **ARB-governed artifact** ("rules are derived from the APPROVED
ARCHITECTURE... never from the incidental filesystem/package layout"). Extending it is
therefore not a pure-engineering action even though it looks like "just config" — whether
that requires its own sign-off, separate from whatever authorizes Slice 1's code, is a
governance question this gate surfaces and does not resolve.

## 11 · Adapter conformance & 12 · Differential testing

Unchanged from the prior design report — both remain design-only, seeded by the existing
19-case `CohesionSemanticsTest`, representation-before-metric, per the `@property` evidence.
Not repeated in full here to respect the "don't inflate documentation" discipline.

## 13 · `D-1` placement

Unchanged: canonical `L3`/Domain. Not implemented in this or any slice defined here.

## 14 · DDD ownership, resolved precisely (not forced into aggregates)

| Type | Classification | Why |
|---|---|---|
| `FactSet` | **Immutable domain result / snapshot**, not an aggregate | No identity of its own; `readonly`; exists only as the output of one analysis pass |
| `DeclaredUnit` | **Immutable entity** | The only type with real, documented identity (`UnitIdentity`, "stable and unique within the analysis scope") — identity without mutable lifecycle is a recognized DDD pattern, not a contradiction |
| `MethodFacts` | **Child value object** | Its `methodIdentity` is unique only within its owning `DeclaredUnit`, never referenced independently |
| `BehaviourReference`, `StateAccess` | **Value objects** | Fully defined by their attribute values; `factId` is a correlation key for one-way provenance joining, not DDD identity |

**Not forced:** no aggregate root is declared merely for ceremony; `FactSet` is explicitly
*not* called an aggregate, since nothing about it has the transactional-consistency-boundary
role that word implies in DDD.

## 15 · Risks (unchanged from prior report, one added)

Added: **extending `deptrac.yaml` may itself require the same governance process that
approved its existing rules** (§10) — a process/authorization risk, not a technical one, and
distinct from the implementation-authorization question this whole gate exists to prepare
for.

## 16 · Machine learning

Unchanged: no ML in Slice 1 or in the conformance/differential design; documented future uses
(fixture generation, anomaly detection, coverage prioritization, clustering) remain exactly
as recorded in the prior two reports, not repeated in full.

## 17 · Architecture acceptance criteria — checked against this gate

Port semantics ✅ (§4-5) · port ownership ✅ corrected (§6) · adapter responsibility ✅
(established, prior reports) · application/core consumer ✅ now designed, not missing (§8) ·
composition point ✅ identified precisely, smallest possible (§8) · dependency direction ✅
(§7, §10) · DDD ownership ✅ (§14) · first TDD test ✅ two tests, not one, with the reasoning
for why one is insufficient (§9) · first minimal production implementation: **defined, not
written** (§9, §19) · preservation of existing behavior: test 1 in §9 exists exactly for this
· adapter conformance strategy ✅ (unchanged, §11) · architecture enforcement strategy ✅
concrete, existing tool, not invented (§10).

## 18 · (folded into §17 per this report's own economy — not restated as a separate checklist)

## 19 · Implementation authorization proposal — exact file list, not created

If and when a separate act authorizes Slice 1, exactly these files change:

**New:**
- `scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/Application/Ports/SemanticFactProvider.php`
- `scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/Infrastructure/Php/PhpSemanticFactProvider.php`
- `tests/Unit/Cohesion/AnalyseCohesionFromSourceTest.php` (or added to an existing test file —
  final choice deferred to implementation time, not decided here)

**Modified:**
- `scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/Application/AnalyseCohesion.php`
  (one new method only, §8)

**Possibly, pending §10's governance question:**
- `deptrac.yaml` (one new context block)

**Not touched:** `PhpFactExtractor.php`, `GraphBuilder.php`, `EdgeRules.php`, `Lcom4.php`,
`CohesionGraph.php`, any `Domain/` file, `expected.json`, any fixture, any unrelated file.

None of the above is created by this document.

---

**Traceability:** all prior reports in this directory · `AnalyseCohesion.php`,
`CohesionPipelineTest.php`, `deptrac.yaml` (read directly for this gate, not from memory) ·
`.claude/runtime/workflow/KOS-CONTRACT-NEUTRALITY-001.json` (unchanged).
