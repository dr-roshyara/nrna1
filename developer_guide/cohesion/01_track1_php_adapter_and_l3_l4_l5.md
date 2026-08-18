# Track 1 — PHP adapter · L3 fact model · PHP L4/L5

**Purpose.** Compute LCOM4 from PHP source through an explicit, language-neutral
conformance boundary, so the cohesion rules can be stated, tested and evidenced
independently of PHP.

## Where it fits

```
scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/
  Infrastructure/Php/PhpFactExtractor.php   B3 — the ONLY place PHP is understood
  Domain/                                   L3 vocabulary + L4 semantics + L5 metric
  Application/AnalyseCohesion.php           drives L3 → L4 → L5, emits 3 evidence levels
```

`EngineeringKnowledge\` is PSR-4 (`scripts/lib/EngineeringKnowledge/`), so no
`require` is needed.

## The one rule to keep

> **`Domain/` and `Application/` must never learn anything about PHP.**

`L4` receives closed-vocabulary facts only — no source text, spelling, tokens,
AST nodes, parser objects or provenance. This is not a convention; it is checked:

```bash
grep -rn "Infrastructure\|token_get_all\|T_[A-Z]" .../Cohesion/Domain/   # must be empty
php vendor/bin/phpunit tests/Unit/Cohesion/CohesionSemanticsTest.php     # runs with no PHP input
```

`CohesionSemanticsTest` constructs L3 facts by hand. If a cohesion rule ever
needed a spelling or a token, that file could not be written — which is the
point of writing it that way.

## How it works

**Extraction (`PhpFactExtractor`)** reads the language's own lexer, so the
grammar is exact rather than approximated. Attributes are `T_ATTRIBUTE` (not
comments), heredoc/nowdoc are delimited, interpolated code yields real facts,
post-`?>` text is not code, `$class` is a variable, and the five name kinds are
distinct token kinds.

**Two orthogonal facts per call site** — `QualifierKind` (how it was spelled) and
`TargetUnitRelation` (whether it denotes this unit). A single "is own class?"
boolean cannot express both; fusing them is how the earlier scanner lost the
distinction.

**`EdgeRules`** is Decision 13.3 as a first-match table. `ExclusionReason` keeps
`NotTheAnalysedUnit` and `NotDeterminable` as separate values — the contract
forbids merging them, and here merging requires editing an enum.

**`GraphBuilder` → `Lcom4`** produce the node set, the edge set and the value.
All three are emitted, because final-metric equality alone cannot detect
compensating errors.

## Extending it

Adding a language means adding a binding that produces the same `FactSet`.
Nothing in `Domain/` changes. That is future scope and not built.

## Pitfalls

- **Do not** add a field to `Domain/` that no decided rule ranges over — L3 is a
  conformance model, not a DTO.
- **Do not** generate expected evidence from this implementation's output and
  then assert against it: the implementation would become its own oracle.
- Case folding is **ASCII-only** on purpose. PHP folds ASCII case in type names
  and not non-ASCII; a locale-sensitive `strtolower` corrupts the relation
  exactly where non-ASCII identifiers live.
- A heredoc's `$this->b()` is a **property read** (`()` is literal text), not a
  call. The lexer says so; trust it.

**Traceability:** Decisions 13.1/13.3/13.5/13.7 · OQ-1 (declaration-path identity)
· OQ-2 (one file) · `G-KOS-CONTRACT-IMPL-TRACK1` + AMD1 (PHP L4/L5 exception).
