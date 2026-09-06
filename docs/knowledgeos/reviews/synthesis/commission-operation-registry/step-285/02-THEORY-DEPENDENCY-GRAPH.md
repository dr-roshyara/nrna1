# 02 · THEORY DEPENDENCY GRAPH
> ## ⛔ NOTHING RATIFIED. Edge classes: `CLOSED · OPEN · BLOCKING · NON-BLOCKING · UNRATIFIED · CONFLICTING`.
> "CLOSED" is never earned because an artifact discusses the issue. Subsumes Step-284 deliverable 04.

## The chain, with the first broken edge marked

```
                    ┌─ CONSTITUTION STATUS ─┐   CONFLICTING  ◀── PRIOR TO EVERYTHING
                    │  3 records disagree   │   (hard stop, GN-91 §17)
                    └───────────┬───────────┘
                                ▼
   FOUNDATION ── 8 primitives, K_t ─────────────────────────  CLOSED (RATIFIED by incorporation)
        │
        ├─ identity ──────────────────────────────────────── UNRATIFIED · BLOCKING (TG-06)
        │       └─ equality ──────────────────────────────── UNRATIFIED · BLOCKING
        │              └─ needs closure of 𝒯 ─────────────── BLOCKING → routes to P-11
        │
        ├─ INVARIANTS I-1…I-12 ───────────────────────────── CLOSED (2 TESTED) · no executable form
        │       └─ closed invariant register ─────────────── OPEN · BLOCKING (no register exists)
        │
        ├══ OPERATIONS ════════════════════════════════════  ✖ FIRST BROKEN EDGE
        │       ├─ P-11a membership criterion ───────────── OPEN (never posed)
        │       ├─ P-11b source-admissibility ───────────── OPEN (unruled)
        │       └─ P-11c closure act ────────────────────── OPEN (would create its own surface)
        │
        ├── TRANSFORMATIONS ──────────────────────────────── DOWNSTREAM-BLOCKED behind operations
        │       └─ pre/postconditions ───────────────────── ABSENT (postcondition = 0 governed-wide)
        │
        ├─ EVIDENCE ──────────────────────────────────────── CLOSED for I-5/I-6 · OPEN for the object
        │       └─ Qualify ──────────────────────────────── OPEN · BLOCKING · CONFLICTING
        │
        ├─ GOVERNANCE / AUTHORITY ────────────────────────── CLOSED as constraint · OPEN as evaluator
        │       ├─ GC-1 policy loop ─────────────────────── CONFLICTING (two closures stand)
        │       ├─ AF-F-39 policy in/out of K ───────────── CONFLICTING (executed vs AUTHORIZED)
        │       └─ R-1 is A6 sole or exemplar ───────────── OPEN
        │
        ├─ PERSISTENCE ───────────────────────────────────── canonically silent · NOT ESTABLISHED
        ├─ REPLAY ────────────────────────────────────────── DOWNSTREAM-BLOCKED · E-cell FAIL
        └─ TESTS / ASSURANCE ─────────────────────────────── OPEN (EG-05 specified, unexercised)
```

## Edge table

| From → To | Class | Basis |
|---|---|---|
| Constitution status → Arts. 3/4/6/7/8/9/11 → D-FA-1 → REJECTED/CONFLICTED | **CONFLICTING** | three records disagree; OQ-10 ratified-as-open, unperformed. **Hard stop.** |
| 8 primitives → `K_t` | **CLOSED** | v0.1 §1 carried into v0.2, incorporated by FA-1 |
| `K_t` → state identity | **UNRATIFIED · BLOCKING** | 0 governed occurrences; TG-06 OPEN BLOCKING |
| identity → equality | **BLOCKING** | *"`K₁ = K₂` has no truth value in the theory as it stands"* |
| equality → closure of `𝒯` | **BLOCKING** | the quantifier ranges over an open collection = **P-11** |
| invariants → executable predicates | **OPEN** | no closed register; completeness of I-1…I-12 not asserted |
| invariants → **OPERATIONS** | ✖ **BLOCKING — first broken edge** | 0 operations governed-wide; 48 enumerations; **empty intersection** |
| operations → transformations | **DOWNSTREAM-BLOCKED** | no pre/post; nothing to contract for |
| transformations → evidence effect | DOWNSTREAM-BLOCKED | inherits |
| evidence → qualification | **OPEN · BLOCKING · CONFLICTING** | *"no body"* vs *"CORPUS ESTABLISHES"* vs *"a verifier construction, incorrectly labelled"* |
| governance → policy content | **CONFLICTING** | AF-F-39: v0.2 *"AT REST inside `K_t`"* vs executed *"`P ∉ 𝒜`"* |
| governance → the policy-change loop | **CONFLICTING** | GC-1, two closures, neither withdrawn |
| A6 → the other five governed transitions | **OPEN** | only A6 has a rule; sole-or-exemplar unstated (R-1) |
| runtime → tests | **OPEN** | EG-05 SPECIFIED, unexercised (OQ-5) |
| replay → observation | **DOWNSTREAM-BLOCKED + FAIL** | flag-setting defect (IR-F-2) |

**Reading rule applied:** transformations, persistence and replay are **not** "unfinished" — they are
**downstream-blocked** behind the operations edge, and saying otherwise would misattribute the fault.
