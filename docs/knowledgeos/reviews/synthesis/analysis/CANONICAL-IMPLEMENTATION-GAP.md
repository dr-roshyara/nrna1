# CANONICAL IMPLEMENTATION GAP

**Authority:** HPA mandate 2026-08-31 ("Canonical Implementation Gap: Operations & Transformations"),
recorded GN-77. **Read-only analysis.** No operation set selected · no Σ/`Q_t`/`𝒪_core` defined ·
no semantics invented · no ratification · no book edit · GC-1 untouched.

**Four layers kept separate throughout (mandate §5):**
`1 RATIFIED CANON` · `2 FORMAL / RESEARCH-DERIVED CANDIDATE` · `3 IMPLEMENTATION PROPOSAL` ·
`4 HUMAN / NORMATIVE DECISION`. **Nothing in layers 2–3 is promoted to layer 1 anywhere below.**

---

## STEP 1 — Re-verification of GN-76

### 1A · Operations — does any RATIFIED or AUTHORIZED artifact define a canonical operation set?

**Surface searched (governed only):** `model/canonical-architecture-v0.2.md` (AUTHORIZED) ·
`model/canonical-architecture.md` (v0.1, superseded) · `final-architecture/FA-1…FA-9` (RATIFIED,
GN-31) · the repository architecture corpus under `docs/knowledgeos/architecture/`.
**Excluded as instructed:** verification-lane candidates · research artifacts · Steps 256/259
candidate lists · 272A/272B/273 results · self-attested "ACCEPTED"/"RATIFIED"/"HPA Ruling" strings
inside research artifacts.

| Category | Count | Evidence |
|---|---|---|
| **Merely mentioned** | 6 apparent hits, **all false positives** | "merge" appears only in the terminology sense — *"No semantic merge is asserted"* (FA-3), *"three names, no merge"* (FA-8, FA-9), *"no-silent-merge rule"* (FA-7, a lane rule); "split" appears once as a noun in v0.2 §8 — *"policy-as-content vs policy-in-force **split**"*. Each was inspected individually. |
| **Formally defined** (name + signature + semantics) | **0** | A search for operation signatures (`Name(` form) across the whole governed architecture corpus returned **0**. The single arrow-pattern line found anywhere is `Direction: CLI→Infra→App→Domain→Shared` — a dependency-direction note in a **PROPOSED · NON-AUTHORITATIVE** baseline. |
| **Authorized** | **0** | — |
| **Ratified** | **0** | — |

> ### CONFIRMED — **the ratified and authorized architecture defines ZERO operations.**
> Not one operation is named as an operation, typed, given a signature, or given semantics
> anywhere in the governed surface.

### 1B · Transformations — does any governed artifact define executable state-transition semantics?

| Element | mentioned | specified | authorized | ratified | executable | Evidence |
|---|---|---|---|---|---|---|
| **Preconditions** | ✅ once | ❌ | ❌ | ❌ | ❌ | exactly one hit in the entire governed surface: v0.1 §1's concept row *"**Authorization** — precondition constraint filled via governance, never a processing step"*. That describes **what Authorization is**, not the preconditions **of any operation**. |
| **Postconditions** | ❌ **0 occurrences** | ❌ | ❌ | ❌ | ❌ | no hit anywhere in `model/` or `final-architecture/`. |
| **Legal transition rules** | ✅ | **partial — constraints only** | ✅ | ✅ | ❌ | canon ratifies **I-12** (the ladder is a covering relation; skipping excluded) and **A6** (the Accepted→Committed boundary is crossed by an authority act, never by evidence). **These are legality *constraints on a transition relation that is itself never defined*.** |
| **Preservation obligations** | ❌ | ❌ | ❌ | ❌ | ❌ | no mapping anywhere of *which operation must preserve which invariant*; no closed invariant register exists. |
| **Rejection / invalid-transition semantics** | ❌ | ❌ | ❌ | ❌ | ❌ | canon defines no rejection type, no failure vocabulary, no "what happens when a transition is illegal". |

> ### CONFIRMED — **no canonical pre/post-condition specification and no executable transition
> semantics exist.** The canon states *when a transition would be illegal* without ever defining
> *what a transition is*.

**Both GN-76 findings hold, and the check is now stronger than GN-76's** (whole governed surface,
not v0.2 alone; every apparent hit individually inspected).

```
Operations          = BLOCKED / NOT CANONICAL
Transformations     = BLOCKED / NOT CANONICAL
Implementation Spec = PARTIALLY BLOCKED
```

---

## STEP 2 — The canonical implementation chain, with provenance

| # | Link | Classification | Provenance (governed only) |
|---|---|---|---|
| 1 | **Canonical Objects** | **PARTIAL** | the 8 primitives {Entity, State, Event, Observation, Proposition, Relation, Policy, Action} are *named* — v0.1 §1 → v0.2 §5. They are not typed: no fields, no cardinalities, no identity rule. |
| 2 | **Canonical State** | **PARTIAL** | `K_t` = state over those primitives, graded TESTED-within-scope (v0.1 §1; v0.2). No schema, no serialization, no state equality. |
| 3 | **Canonical Invariants** | **PARTIAL (2 TESTED)** | I-1…I-12 with grades — v0.2 §4. **I-5, I-6 TESTED** (EXP-01; GN-27 caveat: the CSV is UNRELIABLE-WITHOUT-ITS-GENERATOR). 8 READ, 2 REQUIRED-BY-COHERENCE (I-11, I-12). No executable predicate form; **no closed register** — completeness of the twelve is not asserted. |
| 4 | **Canonical Operations** | 🔴 **NOT ESTABLISHED / BLOCKED** | Step 1A: zero defined, zero authorized, zero ratified. |
| 5 | **Canonical Transformations** | 🔴 **NOT ESTABLISHED / BLOCKED** | Step 1B: legality constraints (I-12, A6) ratified; the transition relation they constrain is undefined; no pre/post; no rejection semantics. |
| 6 | **Evidence** | **PARTIAL** | I-5 (duplicates must not amplify) and I-6 (corroboration must; dependency first) are ratified and TESTED. The Evidence **object** is not canon; the qualification predicate is not canon; the aggregation operator is **OPEN BY RULING** (OQ-3 — deliberately unselected, GN-27). |
| 7 | **Authority / Governance** | **PARTIAL, with an open collision** | Ratified: A6 · I-4 (authority determines commitment, not evidential truth) · DC 6-tuple · Authorization as a precondition constraint filled via governance · the decision interlock (knowledge informs action, does not execute it) · policy stratification + **I-11**. Missing: the authority→gate binding; an authority-act object; an admissibility conjunction matching the ratified 6-tuple (registered). **GC-1 OPEN** — two unreconciled terminations of the policy loop stand. |
| 8 | **Persistence** | 🔴 **NOT ESTABLISHED** | canon defines no serialization, no storage model, no durability requirement. Nothing to classify. |
| 9 | **Replay** | 🔴 **NOT ESTABLISHED** | canon is silent; whether history is inside or outside the state is not settled in canon. |
| 10 | **Tests** | **OPEN** | EG-05 formal suites are **SPECIFIED and unexercised** — FA-6 OQ-5. No conformance criteria; no statement of what a pass would establish. |

**Chain verdict:** links 1–3 are canonically supported (partially); **links 4 and 5 are severed**;
links 6–7 are partial; links 8–9 have no canonical content at all; link 10 is specified but unrun.

---

## STEP 4 — The minimum missing canonical contract

**Question:** what is the *smallest* additional canonical contract that must be ratified before an
engineer can implement KnowledgeOS without inventing semantics?

**Test of the proposed shape.** The mandate proposes:
`Operation + Precondition + Postcondition + Invariant preservation + Evidence effect + Authority effect + Replay effect`.
That shape is **necessary but not sufficient**. Three components must be added, each because the
canon's own ratified content demands them:

| Additional component | Why the canon forces it |
|---|---|
| **(i) A closed operation registry, with a membership criterion** | Per-operation contracts do not answer *"is this the whole set?"* Canon asserts no closure over operations, and I-12's covering relation is only meaningful over a *known* transition set. Without membership, an implementation is complete by accident, not by construction. |
| **(ii) Typed rejection / failure semantics** | "Failure semantics" as a per-operation field presumes a rejection vocabulary. Canon has none — it does not distinguish a structural refusal from a policy refusal from an authority refusal. Without the type, every rejection is an implementer's invention. |
| **(iii) An identity and equality rule for the state** | Preconditions and postconditions are predicates over states; "the postcondition holds" is unanswerable until `K₁ = K₂` is decidable. Canon defines neither identity nor equality (see the source map). Replay effects inherit the same dependency. |

**Therefore the minimum canonical contract is:**

```
Operation registry   (membership + criterion + closure claim)
  └─ per operation:  Precondition
                     Postcondition
                     Invariant-preservation obligations   (requires a closed invariant register)
                     Evidence effect
                     Authority effect
                     Replay effect
                     Typed failure semantics
  plus:              State identity + equality rule
```

Nothing smaller suffices, because each added component is presupposed by one of the mandate's own
seven. **This document specifies the shape and fills none of it.**

---

## STEP 6 — Where the programme actually stands

**A · What is already known (layer 1, ratified).** The eight primitives and `K_t` · I-1…I-12 with
their grades, two of them TESTED · the epistemic ladder with its no-skip law · A6 as the authority
boundary · the DC 6-tuple and the decision interlock · Authorization as a governance-filled
precondition constraint · the policy stratification and I-11 · the measured fact that no executable
counterpart exists for any formal object (CF-015).

**B · What is formally derivable (layer 2 — derivable, not thereby canonical).** Much of the
missing material has candidate derivations in the verification lane: operation universes, a status
structure, an inquiry register, equality families, replay as a fold. **A derivation produces a
candidate; only a ratification produces canon.** None of these carries a governance act.

**C · What is still genuinely open.** Operations · transformations · pre/post-conditions ·
rejection semantics · identity · equality · replay · persistence · the Evidence object · the
qualification predicate · measurement · OQ-1…OQ-12 (open by ruling) · what a passing EG-05 suite
would establish.

**D · What requires an HPA / normative decision.** GC-1 (which termination of the policy loop
stands) · whether any candidate operation universe is ratified, marked HYPOTHESIS, or left open ·
whether Σ / `Q_t` / `𝒪_core` may enter the book, and in which layer · the self-attested authority
strings in the research track · OQ-3's operator selection · whether implementation may proceed
before governance ratification.

**E · What an engineer cannot safely implement yet.** **Anything that changes the state.** An
engineer can build a state over the eight primitives, carry the twelve invariants as constraints
at their stated grades, enforce the policy stratification and the authority boundary, and make
evidence composition obey I-5 and I-6. The moment they need to *assert, retract, supersede, merge,
qualify, authorize or commit* anything, they must invent semantics — because the canon defines
none.

**F · The smallest next decision that unlocks implementation.**

> **Ratify a closed operation registry with a membership criterion — or explicitly rule that none
> exists yet and mark the implementation contract BLOCKED at that point.**

That single act unblocks links 4 and 5 of the chain and, through them, the per-operation
pre/post-conditions, the invariant-preservation obligations, and the replay effects that depend on
them. Everything else on list D is important but does not stand between the current canon and a
buildable specification. **No broad gap-discovery pass is indicated:** the gap is not
under-analysed, it is **undecided** — the analysis exists in the verification lane; what is absent
is a governance act.

---

## STEP 7 — Book boundary

> **IMPLEMENTATION BLOCKER — CANONICAL SEMANTICS NOT ESTABLISHED**
> *Operations and Transformations are unratified. No candidate semantics may be written into any
> implementation chapter as though they were canon.*

The book may **describe this blocker**, cite its evidence, and name the lane that owns it. The book
may not supply the missing contract.

---
## STEP 8 — Disposition (added 2026-08-31, GN-79)
Step 6F's smallest unlocking act has been **acted on**: the HPA selected Option 1 and commissioned
the derivation to the architecture/theory lane (`COMMISSION-operation-registry-derivation.md`).
**The gap is unchanged by this act** — Operations and Transformations remain BLOCKED/NOT CANONICAL.
What changed is ownership: the gap now has a commissioned owner, an acceptance standard (C-1…C-8),
and a required executed minimality test (T-2) that had never been run. Ratification remains a
separate future act; the book's V.5/V.6 blocker stands until then.
