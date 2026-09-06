# OPERATION CONTRACT GAP

**Authority:** HPA mandate 2026-08-31 §3, recorded GN-77. **No operation is selected, named as
canon, or defined here.** The purpose is the opposite: to state precisely what must be decided.

## The distinction this document exists to preserve

> **"The ratified theory REQUIRES this capability"** ≠ **"The canonical theory HAS DEFINED this
> operation."**

The first is a reading of ratified invariants. The second would be an act of authorship. Every row
below is of the first kind. **The canon defines zero operations** (re-verified across v0.2, v0.1,
FA-1…FA-9 and the governed architecture corpus — see `CANONICAL-IMPLEMENTATION-GAP.md` §1A).

## A · Capabilities the ratified canon REQUIRES but does not define

Each row cites the ratified element that *forces* the capability to exist. None of these is an
operation definition, and none may be treated as one.

| # | Capability the canon requires | Forced by (ratified) | Is an operation defined? | Candidate exists outside canon? |
|---|---|---|---|---|
| C-1 | move an item one rung up the ladder | ladder + **I-12** (covering relation; skipping excluded) | **NO** | yes — layer 2, unratified |
| C-2 | attach `Committed` to an Accepted item **by an authority act** | **A6**, **I-4** | **NO** — though canon *does* fix this capability's authority requirement | yes — layer 2 |
| C-3 | make a Determination (`Supported → Accepted` under AcceptancePolicy) | v0.1 §1 concept row, grade READ | **NO** — canon names the *transition*, never an operation with pre/post | yes — layer 2 |
| C-4 | change an in-force policy version through a governed decision | **I-11**, §3 stratification loop (R-1) | **NO** | yes — layer 2 · **GC-1 open** |
| C-5 | compose evidence so duplicates do not amplify and corroboration does | **I-5, I-6** (the two TESTED invariants) | **NO** — and the operator is **OPEN BY RULING** (OQ-3) | yes — refuted candidates recorded |
| C-6 | compute the gap between state and requirement | `Zero(K, EC)`, `EC = η(G, IdealState)` (R-2) | **NO** — canon gives the function's signature, not an operation that invokes or updates it | partial |
| C-7 | turn an observation into evidence | Source/Semantic observation split; Evidence concept row | **NO** — the qualification predicate is undefined in canon | yes — layer 2, recorded bodiless |
| C-8 | evaluate a decision contract for admissibility | DC 6-tuple (Pre, Inv, Auth, Post, Temporal, Evidence) | **NO** — and **no ratified conjunction over exactly those six exists** (registered) | yes — layer 2, mismatched arity |
| C-9 | act, and observe the result | the action loop; the decision interlock | **NO** — the far side is **OPEN BY RULING** (OQ-4) | — |

**Nine capabilities are canonically required. Zero operations are canonically defined.** That
sentence is the whole gap.

## B · Per-required-property status (mandate §3)

Applied to every capability above. `?` = the canon says nothing; a filled cell means the canon
*does* fix that property even though it defines no operation.

| Required property | C-1 | C-2 | C-3 | C-4 | C-5 | C-6 | C-7 | C-8 | C-9 |
|---|---|---|---|---|---|---|---|---|---|
| Name | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| Purpose | canon-implied | canon-implied | canon-implied | canon-implied | canon-implied | canon-implied | canon-implied | canon-implied | canon-implied |
| Input state | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| Preconditions | **partial** — predecessor rung must hold (I-12) | **partial** — item must be Accepted (A6) | **partial** — policy must be in force (I-11) | **partial** — routed via DC + BC_Governance | ? | ? | ? | ? | ? |
| Postconditions | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| Invariants preserved | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| Evidence generated/changed | ? | **canon: none** — evidence cannot cross the boundary (A6) | ? | ? | ? | ? | ? | ? | ? |
| Authority required | ? | **canon: YES — an authority act** (A6, I-4) | **partial** — via AcceptancePolicy | **canon: YES — governed approval** (I-11) | ? | ? | ? | **partial** — DC.Auth is a slot | ? |
| Replay semantics | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| Failure semantics | ? | ? | ? | ? | ? | ? | ? | ? | ? |
| Ratification status | **NONE** | **NONE** | **NONE** | **NONE** | **NONE** | **NONE** | **NONE** | **NONE** | **NONE** |

**Reading:** the canon fixes *fragments* — a precondition here, an authority requirement there —
around operations it never defines. Eleven properties × nine capabilities = 99 cells; **9 are
partially fixed by canon, 2 are fully fixed (C-2's authority requirement and its evidence
non-effect), and 88 are empty.** No capability has a complete contract. Not one.

## C · What must be decided (not answered here)

1. **Membership** — which capabilities become operations, and is the set closed? Three
   non-agreeing candidate lists exist in layer 2; none is ratified; the minimality test that would
   discriminate them has never been executed by anyone.
2. **Granularity** — do C-1…C-9 map one-to-one onto operations, or many-to-one? Layer-2 work
   explicitly records that operations do *not* map one-to-one onto state fields.
3. **Per-operation contracts** — the eleven properties above, for each member of the ratified set.
4. **Typed failure semantics** — the vocabulary in which a rejection is expressed.
5. **The C-2 asymmetry** — canon already fixes that authority crosses the boundary and evidence
   does not. Any operation definition must preserve that, and it is a ratified constraint on the
   answer, not part of the question.

## D · Boundary

No operation named as canon · no membership chosen · no property filled from layer 2 · no
candidate promoted · OQ-3 and OQ-4 untouched · GC-1 untouched.
