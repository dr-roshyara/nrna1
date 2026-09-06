# CANONICAL IMPLEMENTATION CONTRACT — TEMPLATE

**Authority:** HPA mandate 2026-08-31 §6, recorded GN-77. **This is a TEMPLATE, not a theory
decision and not a specification.** It supplies the structure the theory/architecture lane must
eventually fill. **Every value slot is deliberately empty.** Filling any of them from the
verification lane, from the corpus, or from inference would manufacture canon — the failure this
template exists to prevent.

**Per-section status vocabulary:** `DEFINED` · `PARTIAL` · `OPEN` · `NOT ESTABLISHED`.
Status shown below is **the current state of the ratified record**, not a target.

---

## 6.1 Domain objects — status: **PARTIAL**

*Canon supplies eight named primitives; it does not type them.*

```
For each domain object:
  name                    —   [canon: 8 primitives are named]
  purpose                 —
  fields and types        —   [MISSING]
  cardinality             —   [MISSING]
  identity rule           —   [NOT ESTABLISHED]
  lifecycle               —   [MISSING]
  bounded-context owner   —   [PARTIAL: only BC_Governance is named, and its membership is undetermined]
  source + status class   —
```

## 6.2 State model — status: **PARTIAL**

```
  state object                —   [canon: K_t over the 8 primitives]
  what is inside vs outside   —   [OPEN: history's placement is not canonically settled]
  serialization               —   [MISSING]
  what changes atomically     —   [MISSING]
  equality of two states      —   [NOT ESTABLISHED]
  source + status class       —
```

## 6.3 Status model — status: **DEFINED (canon) / competing candidates exist outside canon**

```
  status values               —   [canon: Candidate ⋖ Supported ⋖ Accepted, + Committed at the boundary]
  ordering / no-skip rule     —   [canon: I-12 covering relation]
  boundary-crossing rule      —   [canon: A6 — authority, not evidence, crosses it]
  who may assign each value   —   [PARTIAL]
  derived or stored           —   [OPEN for any non-canon status structure]
  source + status class       —
NOTE: any status structure other than the ratified ladder is NON-CANONICAL PROGRAMME HISTORY
      until a governance act says otherwise.
```

## 6.4 Invariant registry — status: **PARTIAL**

```
For each invariant:
  id                          —   [canon: I-1 … I-12]
  statement                   —   [canon]
  grade                       —   [canon: 2 TESTED · 8 READ · 2 REQUIRED-BY-COHERENCE]
  executable predicate        —   [MISSING for all twelve]
  which operations must preserve it —   [MISSING — depends on 6.5]
  what a violation looks like —   [MISSING]
  test that would detect it   —   [MISSING]
NOTE: no closed invariant register exists; completeness of I-1..I-12 is not asserted.
```

## 6.5 Operation registry — status: **NOT ESTABLISHED**

*The ratified architecture defines zero operations (re-verified 2026-08-31 across v0.2, v0.1 and
FA-1…FA-9: six apparent hits, all false positives).*

```
For each operation:
  name                        —
  purpose                     —
  input state                 —
  preconditions               —
  state transition            —
  postconditions              —
  invariant obligations       —
  evidence requirements       —
  authority requirements      —
  replay semantics            —
  failure semantics           —
  determinism requirements    —
  implementation mapping      —
  executable tests            —

Registry-level questions that must also be answered:
  membership of the registry   —   [BLOCKED: three non-agreeing candidate lists exist outside canon]
  minimality criterion + result—   [BLOCKED: the criterion is stated somewhere; the test has never been run]
  primitive vs derived         —
  closure under composition    —
```

## 6.6 Transformation algebra — status: **NOT ESTABLISHED**

```
  transformation signature     —   [MISSING]
  what makes a transformation LEGAL  —   [MISSING]
  rejection kinds (typed)      —   [MISSING]
  composition rules            —   [MISSING]
  associativity/commutativity/idempotence claims —   [MISSING, and none may be assumed]
  partiality: when is a transformation undefined —   [MISSING]
  source + status class        —
```

## 6.7 Evidence model — status: **PARTIAL**

```
  evidence object fields       —   [MISSING]
  qualification: observation → evidence  —   [MISSING]
  dependency structure         —   [canon: I-5, I-6, both TESTED]
  duplicates must not amplify  —   [canon: I-5]
  corroboration must amplify   —   [canon: I-6]
  aggregation operator         —   [OPEN BY RULING — OQ-3; deliberately unselected]
  evidence identity            —   [MISSING]
  temporal validity / staleness—   [MISSING]
  source + status class        —
```

## 6.8 Governance / authority model — status: **PARTIAL, with an open collision**

```
  authority as a constraint    —   [canon: precondition filled via governance, never a processing step]
  authority ≠ evidential truth —   [canon: I-4]
  the boundary-crossing act    —   [canon: A6]
  decision contract            —   [canon: DC 6-tuple]
  admissibility conjunction over exactly those 6 —   [MISSING — registered]
  authority → gate binding     —   [MISSING]
  authority-act object         —   [NOT ESTABLISHED in canon]
  policy-as-content vs in-force—   [canon: R-1, I-11]
  policy change route          —   [canon: DC + BC_Governance]
  ⚠ policy-loop termination    —   [OPEN: two unreconciled resolutions stand — GC-1]
  who may never decide (software) —   [canon: knowledge informs action, it does not execute it]
```

## 6.9 Replay model — status: **NOT ESTABLISHED**

```
  is history inside or outside the state —   [OPEN]
  replay function              —   [MISSING]
  determinism guarantee        —   [MISSING]
  order dependence             —   [MISSING]
  what replay must reproduce   —   [MISSING]
```

## 6.10 Runtime correspondence — status: **PARTIAL (measured, not designed)**

```
For each canonical construct:
  construct                    —
  runtime artifact (if any)    —   [canon finding: no executable counterpart exists for ANY formal object]
  conformance evidence         —
  divergence, if any           —
  correspondence class         —   [must distinguish: exact · implemented-untested ·
                                    implemented-differently · not-implemented]
NOTE: the real estate today is read-only/diagnostic; recording that honestly is part of the contract.
```

## 6.11 Test / assurance contract — status: **OPEN**

```
  suites that exist            —   [canon: EG-05 SPECIFIED, unexercised — OQ-5]
  what each suite establishes  —   [MISSING]
  evidence level of a pass     —   [MISSING — must distinguish reference-implementation
                                    passes from real-environment observation]
  conformance criteria         —   [MISSING]
  what a failing suite means for the theory —   [MISSING — a governance question, not a test question]
```

## 6.12 Traceability — status: **DEFINED (method)**

```
For every implementation requirement:
  requirement id               —
  source artifact + location   —
  source class                 —   [RATIFIED · AUTHORIZED · FORMAL · TESTED · INTERPRETIVE · OPEN]
  governance act (if any)      —
  invariant(s) it serves       —
  test(s) that demonstrate it  —
  real-environment witness     —   [or explicitly: none]
RULE: a requirement whose source class cannot be named is not a requirement — it is a proposal.
```

---

## How this template must be filled

1. **Sections 6.5 and 6.6 are the blocking pair.** Nothing downstream of them can be completed
   first; several other sections' empty slots (invariant obligations, replay semantics, failure
   semantics) are empty *because* 6.5 is empty.
2. **A section is filled by a governance act, not by a good argument.** A derivation, however
   sound, produces a candidate; only a ratification produces canon.
3. **Filled values carry their status class inline.** A `DEFINED` section containing a `FORMAL`
   row is normal and honest; a `DEFINED` section that hides a `FORMAL` row is the failure mode.
4. **The book documents this contract; it never authors it.**
