# IMPLEMENTATION-SPECIFICATION SOURCE MAP

**Authority:** HPA mandate 2026-08-31 (`prompts/20260831_0144_prompts.md`), recorded GN-77.
**Scope:** book-lane analysis only. **No theory derived, no operation set chosen, no construct
defined, no gap resolved, no book file edited.** Where a definition is absent it is recorded as
absent — never inferred.

**Status vocabulary (never collapsed):** `RATIFIED` · `AUTHORIZED` · `FORMAL` · `TESTED` ·
`INTERPRETIVE` · `OPEN` · `NOT ESTABLISHED` · `NON-CANONICAL PROGRAMME HISTORY`.

---

## 0 · Re-verification of GN-76's two blocking findings (mandate §5)

Both were re-tested this turn against the **whole ratified surface**, not just v0.2, and both hold
— with the check now stronger than GN-76's.

**Finding 1 — no ratified artifact names an operation.** A search for operation names
(`Assert · Retract · Supersede · Merge · Split · Revise · Withdraw · Reintroduce · Qualify ·
Authorize() · Replay · Trace · Dedup`) across `canonical-architecture-v0.2.md`,
`canonical-architecture.md` (v0.1) and **FA-1…FA-9** returned six hits. **All six are false
positives**, inspected individually:

| Hit | Actual context | Operation? |
|---|---|---|
| FA-4 §5 "Zero" row | terminology register, `Zero(K,EC)` naming | no |
| FA-3 line 36 | *"**No semantic merge is asserted**"* — about naming senses | no |
| FA-8 line 14 | *"D-FA-3 (three names, no merge)"* | no |
| FA-7 lines 8–9 | *"no-silent-merge rule"* — a governance rule about lanes | no |
| FA-9 D-FA-3 row | *"three names … no merge"* | no |
| v0.2 §8 R-1 row | *"policy-as-content vs policy-in-force **split**"* — a noun, the R-1 repair | no |

> **CONFIRMED: the ratified architecture defines ZERO operations.** Not one operation is named,
> typed, or given a signature anywhere in the governed surface.

**Finding 2 — no canonical pre/post-condition specification.** A search for
`precondition|postcondition|pre-condition|post-condition` across `model/` and
`final-architecture/` returned **exactly one hit**: v0.1 §1's concept row —
*"**Authorization** | precondition constraint filled via governance, never a processing step |
COMPOSITION"*. That is a **description of Authorization's role**, not a pre/post-condition
specification for any operation, and it sits in the superseded v0.1 (carried into v0.2 by §5's
"as v0.1").

> **CONFIRMED: no canonical pre/post-condition specification exists.**

Therefore, as mandated:

```
Operations          = BLOCKED / NOT CANONICAL
Transformations     = BLOCKED / NOT CANONICAL
Implementation Spec = PARTIALLY BLOCKED
```

---

## A · What can an engineer implement TODAY from canonical material?

Canonical = ratified/authorized only. Verification-lane content appears nowhere in the
"Canonical definition" column; where the canon is silent, the row says so.

| # | Construct | Canonical definition (ratified only) | Source | Status | Implementable? | Missing contract |
|---|---|---|---|---|---|---|
| 1 | **Objects** | the **8 primitives** {Entity, State, Event, Observation, Proposition, Relation, Policy, Action} | v0.1 §1 → v0.2 §5 ("as v0.1") | RATIFIED (TESTED within scope) | **PARTIAL** — the primitives are *named*, not *typed* | per-primitive field list, types, cardinalities, identity rule |
| 2 | **State** | `K_t` = state over those 8 primitives | v0.1 §1; v0.2 | RATIFIED | **PARTIAL** — a state object can be declared; it cannot be serialized or compared | state schema; serialization; what changes atomically |
| 3 | **Invariants** | I-1…I-12, each with its grade (**2 TESTED** I-5/I-6 · 8 READ · 2 REQUIRED-BY-COHERENCE I-11/I-12) | v0.2 §4; III.10 | RATIFIED | **PARTIAL** — stated as properties, not as executable predicates | executable predicate form; **no closed invariant register exists**; no mapping of which operation must preserve which invariant |
| 4 | **Identity** | *(canon is silent — no `id` function, no identity rule)* | — | **NOT ESTABLISHED** | **NO** | the identity function itself, and what it may hash |
| 5 | **Equality** | *(canon is silent)* | — | **NOT ESTABLISHED** | **NO** | which equality (or family of equalities) governs `K₁ = K₂` |
| 6 | **Lineage** | *(canon is silent — "Lineage" occurs 0× in governed artifacts)* | — | **NOT ESTABLISHED** in canon; **implemented in the estate** (36 production files, a passing test suite) | **implemented, not canonically documented** | a canonical definition to which the running implementation could be conformed |
| 7 | **Replay** | *(canon is silent)* | — | **NOT ESTABLISHED** | **NO** | replay semantics; whether history is inside or outside the state |
| 8 | **Evidence** | evidence "carries dependency structure and roles; duplicates must not amplify; corroboration must" — **I-5, I-6** | v0.1 §1, §4; v0.2 | **TESTED** (the only TESTED pair) — with GN-27's caveat that EXP-01's CSV is UNRELIABLE-WITHOUT-ITS-GENERATOR | **PARTIAL** — the two laws are implementable as constraints | the Evidence *object* (fields); the qualification predicate (observation → evidence); aggregation operator deliberately unselected (OQ-3) |
| 9 | **Measurement** | *(canon is silent — 0 occurrences)* | — | **NOT ESTABLISHED** | **NO** | scale typing; admissibility; whether a probability space is required (OQ-adjacent) |
| 10 | **Policy** | policy-as-content **vs** policy-in-force; change routed `DC + BC_Governance`; **I-11** | v0.2 §1, §3, §4, §8 (R-1, GN-19) | RATIFIED (I-11 = REQUIRED-BY-COHERENCE) | **PARTIAL** — the stratification and the change-route are implementable | the Policy *object* (gates, validity interval, resolution behaviour); evaluation semantics. **GC-1 open**: a second, unreconciled termination of the same loop exists |
| 11 | **Authority** | "Authorization — precondition constraint filled via governance, never a processing step" (COMPOSITION) · **I-4** "authority determines commitment, not evidential truth" (READ) · **A6** as the explicit boundary-crossing rule | v0.1 §1, §4; v0.2 R-3 | RATIFIED | **PARTIAL** — implementable as a *gate that must be filled*, not as an evaluator | the authority→gate binding; an authority-act object; what an authorization *returns* |
| 12 | **Governance** | DC 6-tuple (Pre, Inv, Auth, Post, Temporal, Evidence) · BC_Governance · the decision interlock · "knowledge informs action, it does not execute it" | v0.2 §1; FA; Constitution Art. 4 | RATIFIED | **PARTIAL** — boundaries and the interlock are implementable | **no ratified admissibility conjunction matches the ratified 6-tuple** (registered MV-F-5); BC_Governance's membership undetermined (registered DDD-F-2) |
| 13 | **Software / runtime correspondence** | 3C CF-015: **no executable counterpart exists for any formal object**; the real footprint is read-only/diagnostic | 3C register; GN-23 ruled conclusion | RATIFIED **as a finding** | n/a — this is a fact about the estate, not a buildable item | nothing to supply; it is the measurement of the gap |
| 14 | **Tests** | EG-05 formal suites — **SPECIFIED, unexercised** (OQ-5) | FA-6 OQ-5 | AUTHORIZED-as-specified · OPEN | **NO** — nothing to run | suite execution; conformance criteria; what a passing suite would establish |

**Reading of the table:** four rows are canonically silent (Identity, Equality, Replay,
Measurement), one is implemented-but-undocumented (Lineage), and every remaining row is PARTIAL —
the canon supplies *constraints and boundaries*, not *constructions*.

---

## B · The critical implementation chain — link by link (mandate §4)

For each arrow: (1) what canonical material defines it · (2) implementable today · (3) only
conceptual · (4) missing · (5) owning lane · (6) can the book write it now without creating canon?

| Arrow | 1 · Canonical basis | 2 · Implementable | 3 · Conceptual only | 4 · Missing | 5 · Lane | 6 · Book may write now? |
|---|---|---|---|---|---|---|
| **OBJECTS → STATE** | the 8 primitives; `K_t` over them (v0.2) | declare a state over named primitives | the primitives' internal structure | field/type spec; identity rule | **architecture** | **YES** — as canon, at ratified strength |
| **STATE → INVARIANTS** | I-1…I-12 with grades (v0.2 §4) | assert the two TESTED laws as constraints | the eight READ invariants read as properties | executable predicate form; closed register | **architecture** | **YES** — with grades shown, never flattened |
| **INVARIANTS → OPERATIONS** | **nothing** — canon names zero operations (§0) | — | — | the operation universe itself | **theory → architecture (ratification)** | **NO — RED** |
| **OPERATIONS → TRANSFORMATIONS** | **nothing** — no pre/post-condition spec (§0) | — | — | the transformation algebra; legality rules | **theory → architecture** | **NO — RED** |
| **TRANSFORMATIONS → EVIDENCE** | I-5, I-6 (TESTED) bound how evidence composes | the two laws | the evidence object | evidence schema; qualification predicate; aggregation operator (OQ-3, unselected by ruling) | **theory** (object) · **governance** (OQ-3) | **PARTIAL — AMBER**: the laws yes, the object no |
| **EVIDENCE → GOVERNANCE/AUTHORITY** | A6; DC.Auth as a constraint; I-4; the interlock | the boundary and the gate | the authority evaluator | authority→gate binding; authority-act object; admissibility conjunction for the ratified 6-tuple | **architecture** · **governance** (GC-1) | **PARTIAL — AMBER**, GC-1 stated open |
| **GOVERNANCE → RUNTIME** | CF-015 (the footprint fact) | reporting the real estate honestly | correspondence for formal objects | — (this link is a *measured absence*, not a missing definition) | **engineering** | **YES** — as a finding, held at NOT ESTABLISHED |
| **RUNTIME → TESTS** | EG-05 SPECIFIED (OQ-5) | nothing to run | the suites | execution; conformance criteria | **engineering** | **PARTIAL — AMBER**: may describe the specified suites and their unexercised status |
| **TESTS → ASSURANCE** | II.2's evidence discipline; the four status dimensions | the reading method | — | what a passing suite would establish about the theory | **governance** | **YES** — method only |

**Chain verdict:** the chain is **severed between INVARIANTS and OPERATIONS, and again between
OPERATIONS and TRANSFORMATIONS.** Everything upstream of the first break is canonically supported;
everything downstream is reachable only through material that is not ratified.

---

## C · What a future canonical operation specification must contain (fields only)

Per mandate §5 — **the required shape, with no values invented.** Every field below is empty by
design; filling any of them is a theory/architecture act, not a book act.

```
Operation
  name                        —
  purpose                     —
  input state                 —
  preconditions               —
  state transition            —
  postconditions              —
  invariant obligations       —   (which of I-1..I-12 this operation must preserve)
  evidence requirements       —
  authority requirements      —
  replay semantics            —
  failure semantics           —   (what a rejection is, and of which kind)
  determinism requirements    —
  implementation mapping      —
  executable tests            —
```

Two structural notes the book may record without supplying content: the field
**"invariant obligations"** cannot be filled while no closed invariant register exists, and
**"failure semantics"** presupposes a typed rejection vocabulary that the canon does not define.

---

## D · Minimum canonical closure required for implementation (mandate §8)

Not *"is the theory complete?"* but *"what is the minimum an engineer needs to build safely?"*

**Already available (canonical):** the 8 primitives and `K_t` · I-1…I-12 with grades, including
the two TESTED evidence laws · the policy stratification and its change route (I-11) · the
authority boundary (A6, I-4, DC.Auth as a constraint) · the decision interlock and the
inform-not-execute rule · the honest measurement of the current estate (CF-015).

**Missing but merely editorial** *(no decision needed — someone must write it down)*: a
reader-facing statement of what book acceptance does and does not mean; the four status dimensions
as an apparatus; the traceability convention for implementation requirements.

**Missing formal definition** *(theory lane)*: identity · equality · replay · the Evidence object ·
the qualification predicate · measurement/scale typing · pre- and post-conditions.

**Missing architecture decision** *(architecture lane)*: **the operation universe** (`𝒪_core` is
not ratified, carries three non-agreeing membership lists, and its own latest artifact says it
*must not be frozen as-is*) · **the transformation algebra** · the authority→gate binding · an
admissibility conjunction that matches the ratified DC 6-tuple · BC_Governance's membership.

**Missing governance decision** *(HPA)*: **GC-1 / I-11** — which termination of the policy loop
stands · whether Σ, `Q_t`, `𝒪_core` may enter the book at all and in which layer · the
self-attested authority strings in the research track · OQ-3's operator selection (open by ruling)
· OQ-5's suite execution.

**Missing implementation** *(engineering)*: any executable counterpart for a formal object;
`Authorize()` runtime; a measurement executor; an inquiry register — none of which exist in the
estate today.

**Missing empirical observation** *(engineering)*: real-environment witnesses. The recorded
measurements are **15 of 24 tests** with no real-environment observation and, on a different
denominator, **16 of 25 constructs** with no real-environment witness. These two figures must
never be merged.

**The minimum, stated plainly:** an engineer can safely build **a state over the eight primitives,
carrying the twelve invariants as constraints at their stated grades, with the policy
stratification and the authority boundary enforced, and with evidence composition obeying I-5 and
I-6.** They cannot safely build *anything that changes that state*, because no legal operation and
no legal transformation is canonically defined.

---

## E · What this document did not do

No operation derived or chosen · no Σ defined · no storage-vs-derivation decided · no `Q_t`
defined · no transformation defined · no pre/post-condition invented · GC-1 untouched · no OQ moved
· no verification finding promoted · no claim that the theory is complete or incomplete · no book
file edited.
