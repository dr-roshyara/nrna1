# Kernel Expressiveness Test — run BEFORE any code

**Question:** is the proposed Phase-2 conceptual model expressive enough to
represent what F0001–F0025 actually contains?

**Answer: NO — 34 % coverage.** Model corrected before the system was built.

## Method

The proposed 8-concept kernel (`Evidence` `TheoryObject` `TheoryThread`
`Derivation` `TheoryEvolution` `Gap` `Verification` `Provenance`) was mapped
against all 14 Step-1 registries and scored by row volume.

## Result

| | rows |
|---|---|
| Representable | 66 |
| **Not representable** | **127** |
| **Coverage** | **34 %** |

### Two genuinely missing primitives — not collapsible

| Missing | Rows | Why |
|---|---|---|
| `Relationship` | 19 | An edge is not a node. Without it the entire relational structure is unrepresentable — and relations are what a theory *is*. |
| `Event` (≠ `Source`) | 15 + 24 | 8 of 25 files carry >1 typed date event; `OC-0003` proved a file occupies multiple positions in the order. A `Source` cannot be the temporal unit. |

### Five that collapse once those exist

`Contradiction` → `Relationship(kind=CONTRADICTS)`
`OrderingConstraint` → `Relationship(kind=PRECEDES)` over `Event`s
`ArchitectureObject` → `TheoryObject(type=ARCHITECTURE)`
`IdentifierCollision` → `Relationship(kind=NAME_COLLIDES)`
`IntraFileRevision` → `Event(kind=REVISION)`

### One in the wrong layer

`BaselineComparison` (P3A) compares against an **external system** — a driven-adapter
concern, not domain. The hexagonal test caught a concept sitting in the wrong layer.

## Corrected kernel — 10 primitives

```
Source · Event · Evidence · TheoryObject · Relationship
Derivation · TheoryThread · Gap · Verification · Provenance
(+ TheoryEvolution, Obligation, Maturity as first-class records)
```

## Why this matters

Protocol §3A.2 predicted that implementing the model would test the methodology.
It did so before a line of code existed: the model was found insufficient and
changed. Had the 8-concept kernel been built first, the engine would have been
structurally unable to hold 127 of 193 rows — and would likely have been
"fixed" by distorting the data to fit the schema.

**Re-run this test whenever the kernel or the registries change (`Q30`).**
