# R1 constructors — placement determination (A–E)

**Context:** `KOS-CONTRACT-NEUTRALITY-001` → `KOS-PYTHON-RULE-VALIDATION` branch, Rule 1
**Grant basis:** continuation of `G-KOS-PYTHON-RULE-VALIDATION-R1-CONSTRUCTORS`'s report step —
design analysis only. **No code, test, or contract file touched. `MethodFacts` is not
designed here — only where the fix belongs.**

---

## The question being answered

> Is constructor/destructor exclusion part of the canonical semantic representation (L3),
> or an analytical classification that happens between extraction and graph construction?

Answered by reading the codebase's own existing, already-accepted precedent for exactly
this shape of problem, rather than designing from first principles.

## The precedent that decides it: `EdgeRules` vs. `GraphBuilder`'s node exclusion

Both are L4. They currently look nothing alike, and the difference is the whole answer:

| | `EdgeRules::verdict()` | `GraphBuilder`'s node exclusion (today) |
|---|---|---|
| Input | `BehaviourReference`'s **closed-vocabulary** facts (`qualifierKind`, `determinability`, `targetUnitRelation`, `referenceMode`) | `MethodFacts::methodIdentity` — an **open-vocabulary string**, matched against a literal name list |
| Docblock claim | "LANGUAGE-NEUTRAL IN SEMANTIC RESPONSIBILITY... knows nothing about PHP" | No such claim; none would currently be true |
| Spelling ever inspected? | Never — every branch dispatches on an enum | Yes — the only branch there is |

`EdgeRules` is the accepted model for "how does this codebase decide something is a
declared *kind* of thing, generically." It works by never looking at a name — a fact is
captured once, at extraction, as a closed-vocabulary value (`QualifierKind`,
`TargetUnitRelation`, etc.), and every later decision dispatches on that value, not on
spelling. `GraphBuilder`'s constructor exclusion is the **one place in this whole capability
that violates that pattern** — confirmed by grepping the full `Domain` directory (9 enums:
`UnitKind`, `QualifierKind`, `TargetUnitRelation`, `Determinability`, `ReferenceMode`,
`AccessMode`, `ExclusionReason`, plus `Interpretation`/`UnitEligibility`; zero of them are
consulted by name-matching — `methodIdentity` is the only string ever compared against a
literal list anywhere in `Domain`).

This reframes the question precisely: it is not "does Python need special-casing" — it is
"this one decision was never given a closed-vocabulary L3 fact to consult, unlike every
sibling decision in the same layer." That is a **representational gap**, which is exactly
what classification C already named — this placement analysis explains *why* C is the
right classification, from the architecture's own internal consistency, not just from the
PHP/Python divergence.

## Working through A–E on that basis

- **E — an existing field already suffices.** Ruled out by direct read: `MethodFacts` has
  exactly `methodIdentity`, `hasBody`, `stateAccesses`, `behaviourReferences` (confirmed,
  prior report). `hasBody` is the only other bool-shaped field in this capability, and the
  dependency matrix (`2026-09-27-KOS-L3-L4-L5-dependency-matrix.md`) already proved it is
  read nowhere — not a usable precedent for "a bool already carries this."

- **D — the rule receives metadata via a dedicated rule class**, i.e., extracting
  `GraphBuilder`'s node-admission check into an `EdgeRules`-sibling (`NodeRules::verdict()`
  or similar). Architecturally clean in the abstract, but **not evidenced today**:
  `EdgeRules` earns its existence by adjudicating ten `qualifierKind` values across nine
  branches; node admission currently has exactly one decision (lifecycle exclusion) with
  two cases sharing one outcome. Splitting it into a new class now would be structure
  without a second decision to justify it — the same "minimal architecture, extend only
  when evidence demands it" standard already applied to reflection and diamond `MRO` in
  this branch. **Deferred, not adopted** — worth revisiting only if node-level exclusion
  logic grows a second, differently-reasoned case.

- **A — `MethodFacts` gets a lifecycle-specific field** (e.g. a raw boolean or two booleans,
  `isConstructor`/`isDestructor`). Directionally the right layer (L3), but the pinned
  decision itself (`expected.json`, `constructors`) treats `__construct`/`__destruct` as one
  undifferentiated bucket, and nothing downstream — confirmed by the dependency matrix —
  ever branches on which of the two it is. Two separate facts would encode a distinction
  the analysis never consumes, which is exactly the kind of unconsumed-field problem the
  `hasBody`/`accessMode` findings already flagged as a standing minor gap elsewhere in this
  same capability. Also inconsistent in *kind* with every other real decision-driver in
  `Domain`, which are closed enums, never booleans.

- **C — L3 carries "language-neutral lifecycle facts."** Same layer as A, plural framing
  not evidenced by the pinned decision (one bucket, not two) — effectively A's granularity
  problem restated.

- **B — L3 carries a general semantic method-role fact, closed-vocabulary, extensible.**
  This is the one option that matches the established precedent exactly: a new small enum
  on `MethodFacts` (name/cases not designed here), analogous in *kind* — not content — to
  `QualifierKind` on `BehaviourReference`: a closed set of values describing **how the
  method was declared to behave**, populated once by each language's own adapter according
  to its own convention (PHP: `__construct`/`__destruct`; Python: `__init__`/`__del__`),
  consulted by `GraphBuilder` instead of a name list. Two cases would suffice today
  (ordinary vs. lifecycle) — extensible later only if a second real distinction is ever
  evidenced, same discipline applied everywhere else in this investigation.

## Determination

**B.** L3 (`MethodFacts`) is the correct layer; the fact must be closed-vocabulary, not a
name or a raw boolean; and no dedicated rule class (D) is currently justified — the
consuming check stays in `GraphBuilder`, just switched from a string match to an enum
check. E is excluded on direct evidence; A/C are subsumed by B at the correct granularity;
D is a legitimate future refactor, not a present requirement.

**Still not authorized or attempted:** the field's name, its case list, its exact
population rule in each adapter, and the `GraphBuilder` change itself. This report answers
*where*, not *what* — per your instruction, `MethodFacts` design comes next, separately.

**Traceability:** `EdgeRules.php`, `GraphBuilder.php`, `MethodFacts.php`, `ExclusionReason.php`,
`UnitKind.php` (read directly, this analysis) · `2026-09-27-KOS-minimal-semantic-contract.md`,
`2026-09-27-KOS-L3-L4-L5-dependency-matrix.md` (prior reports, same directory, establishing
the consumed/unconsumed field precedent relied on above) ·
`2026-09-27-KOS-PYTHON-RULE-VALIDATION-R1-constructors.md` (prior report, this branch).
