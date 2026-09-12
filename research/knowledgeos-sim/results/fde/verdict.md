# `KR-CONTR-FDE-2026-09` — Verdict

**Experimental package.** Theory v1.2 **unchanged** · no v1.3 · **nothing adopted** ·
`app/` untouched · deterministic, no seed. **8 result files.**

---

## 1. The distinction matrix — 13 required distinctions

| representation | preserved | collapsed | **not-representable** | adequate |
|---|---|---|---|---|
| **Classical** `{P, ¬P}` | 1 | 1 | **11** | no |
| **K3** `{T,F,U}` | 3 | **10** | 0 | no |
| **FDE** `(S⁺,S⁻)` | **11** | **2** | 0 | **no** |
| **Structured** `Standing × Boundary × Context × Provenance` | **13** | 0 | 0 | **YES** |

**Outcomes are four-valued, not pass/fail**, and `not-representable` is reported **distinctly** from
`collapsed`: Classical does not *fail* eleven distinctions, it **cannot express** them.

## 2. The two results that matter most

### 2.1 The two-channel structure earns its place — it goes 10 collapses → 2

**K3 collapses all five `DirectContradiction | X` pairs.** FDE **preserves every one of them.** This
is exactly the useful part the spec extracted from FDE, and it is confirmed: representing positive and
negative support **independently** solves the contradiction-vs-unknown problem that a third value
cannot.

### 2.2 …and it fails on precisely the boundary family, as predicted

**FDE's two remaining collapses:**

```
NoEvidence            ≡  Absent
InsufficientEvidence  ≡  Underdetermined
```

**Both are boundary distinctions. Neither involves contradiction.** `Standing` has no channel for
*why*.

> ### ⚠️ Correction to the specification's own example
> The spec says **`(0,0)`** could mean *no evidence / not assessed / unobservable / underdetermined /
> theory incomplete*. **Measured, the ambiguity is not confined to `(0,0)` — it affects EVERY
> `Standing` class**, and three of the conditions the spec assigned to `(0,0)` are not there at all:
>
> | Standing | scenarios it covers |
> |---|---|
> | **`(1,0)`** | `PositiveEvidence` · **`NotAssessed`** · **`Underdetermined`** · `InsufficientEvidence` · **`TheoryIncomplete`** |
> | `(0,1)` | `NegativeEvidence` · `SupersededEvidence` |
> | `(1,1)` | the seven contradiction-family scenarios |
> | `(0,0)` | `NoEvidence` · `Unobservable` · `ScopeExclusion` · `Absent` |
>
> **`NotAssessed`, `Underdetermined` and `TheoryIncomplete` land in `(1,0)`** — evidence *is* present,
> it simply has not been evaluated, is insufficient, or has no evaluator. **A positive Standing is
> just as boundary-ambiguous as an empty one.** The separation of the two factors is therefore
> required more broadly than the spec's framing suggests.

## 3. `FDEConflict ≢ Contr` — and the reason is structural

| frame qualifier `φ` | equivalent? | divergence |
|---|---|---|
| **`∅`** (`Pos ∧ Neg` only) | **YES** | — |
| `{time}` | no | `TemporalConflict` |
| `{context}` | no | `ContextConflict` |
| `{time, context}` | no | both |
| `{time, context, layer}` | no | both |

> **`FDEConflict` is a detector over `Standing`, and `Standing` has no access to the frame.**
> It therefore **cannot implement any `φ` except the empty one** — and `KR-CONTR-EVAL` §15 already
> established that the empty `φ` **over-generates**, classifying supersession and temporal separation
> as contradiction.
>
> **So `FDEConflict` can equal `Contr` only under the one frame qualifier already shown to be wrong.**
> This is a structural limit of the detector, not a contingent mismatch. **Keeping it named
> `FDEConflictDetector` rather than `Contr` was the right call.**

## 4. Zero is a consumer — `FDE-like Standing ≠ Boundary ≠ Zero`

**`Zero` is not determinable from `Standing` alone.** Every non-conflicting `Standing` class covers
scenarios with **different boundaries** (§2.2). `Zero` must consume `Boundary` as well.

## 5. Invariants

| | Classical | K3 | FDE | Structured |
|---|---|---|---|---|
| `I1`–`I5` `Contr ≠ {NoEvidence, Absent, NotAssessed, Insufficient, Unobservable}` | **not-representable** | **collapsed** | preserved | preserved |
| **`I11`** `Contr ≠ Satisfied` `[PROP]` | **not-representable** | preserved | preserved | preserved |

> **Two complementary failure modes are now on record.** `K3` fails `I1`–`I5` but **passes** `I11`.
> Candidate `C` of `KR-CONTR-EVAL` **passed** `I1`–`I5` and **failed** `I11`. **Neither invariant
> subset alone is sufficient** — which is independent support for adding `I11`, still `[PROP]`.
>
> **Classical satisfies nothing**: a model that cannot express a situation has not satisfied an
> invariant about it.

## 6. Guards — re-run here, not cited

**E12** — all three reason representations: **10 distinct values over 18 scenarios** · **not** an
identity encoding · **36** pairs collapsed · **not adequate alone (11/13)**. The boundary component is
a genuine categorical field, reproduced independently of the earlier harness.

**E13** — `FlatReason`, `LocusModalityReason` and `StructuredBoundary` are **all equivalent under
`ℛ_req`** (pairwise, 3/3). **The factorization is free at this required set** — which is the third
independent time `ℛ_req` has turned out to be the thing that decides.

## 7. Composition probe — enumerated, nothing selected

| case | union (FDE-like) | majority | last-wins | strict |
|---|---|---|---|---|
| contradictory | **conflicting** | unsupported | negative-support | unsupported |
| **superseded** | **conflicting** | unsupported | negative-support | unsupported |

> **Only `union` can produce a conflicting `Standing` at all** — and **`union` also misclassifies
> supersession as conflict.** So the composition rule and the frame qualifier `φ` are **coupled**: the
> only enumerated rule that can represent contradiction is the one that over-generates it in exactly
> the way §3 identifies.
>
> **`KR-COMP` must treat them together.** No rule is adopted; rules were enumerated first and
> witnesses second, as `KR-CONTR-EVAL` §21.1 requires.

---

## Verdict

> **The FDE-inspired two-channel `Standing` is a GOOD candidate for the first factor and is NOT
> sufficient alone.** It carries the contradiction distinctions that defeat `K3`, and it carries none
> of the boundary distinctions. **The factorized model
> `Evaluation = Status/Polarity × Typed Boundary/Reason` is supported by every test here** — as the
> surviving tested candidate, **not** as an adopted representation.

**Not established, and not to be inferred:** that KnowledgeOS should adopt FDE · that `Standing` is a
primitive · that the boundary vocabulary is correct · that `φ` or `ℛ_req` are settled — both remain
**decisions** · that `Contr` is defined. **It is not.**
