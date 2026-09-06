---
artifact: 05 · CANONICAL-CONSTRUCT-REGISTRY
date: 2026-08-31 · snapshot 57d93b0e
authority: |
  This registers CONSTRUCTS. `verification/THEORY-GAP-REGISTER.md` (TG-1..TG-21) registers GAPS.
  They are COMPLEMENTARY, not competing. Where they overlap, the TG-register is authoritative on
  gap status and this registry is authoritative on construct definition. No entry is duplicated.
evidence_classes: "[F] formal · [E] executed · [R] real-environment · [D] derived · [N] normative · [I] inferred · [U] unresolved"
---

# Canonical Construct Registry

**The question this answers, per construct:**
> *what exactly is missing between mathematical definition → executable implementation → real-world
> observation → governed acceptance?*

**No status is upgraded across lanes. `Formal ✓` never implies `Real ✓`.**

| # | Construct | Formal definition | Required operations | Impl. status | Executable test | Real-env observability | Evidence | Governance dep. | **Remaining blocker** |
|---|---|---|---|---|---|---|---|---|---|
| 1 | **K** | `(𝒜, ℛ)` | membership, equality, merge | reference only | E1 PASS | **L5** — 37 real docs | `[R]` | none | *none formal;* no production impl |
| 2 | **𝒜** | `Set(Assertion)` | ∈, ∪, ∖ | reference | E1 | **L5** | `[R]` | none | production impl |
| 3 | **ℛ** | 3 DAGs ⊎ 2 edge sets ⊎ 1 symmetric | relate, acyclicity | reference + **EKP typed edges** | E10 PASS | **L5** | `[R]` | none | acyclicity unenforced in EKP (I-2 eng.) |
| 4 | **Assertion** | `(id,P,e,c,t,Π)` | assert, retract | reference | E1,E7 | ✗ | `[E]` | none | EKP has no evidence/`t`/`Π` fields |
| 5 | **P** | `(E,D,V)`, `V∈V_D` | WellFormed | reference | E7 PASS | ✗ | `[F][E]` | none | EKP `title` is prose |
| 6 | **Dimension** | `(ID,Name,ValueSpace,Type,Domain)` | `admits` | reference + **EKP vocab files** | E7 | **L5 partial** | `[R]` | none | scale type absent from EKP |
| 7 | **Evidence** | `(ref,source,obs,ctx,time,method,prov,polarity,state)` | Qualify, Support, Refute | reference | E5,E6 PASS | ✗ | `[E]` | none | **not implemented anywhere** |
| 8 | **Qualification** | `Observation × Policy ⇀ Evidence` | Qualify | reference | E5 PASS | ✗ | `[E]` | Policy | **content is policy-parametric by design** |
| 9 | **Σ** | **`(D,S)`, `D ≅ {0,1}²`, `S` ORDINAL** | Assess | reference | E3 PASS | ✗ | `[E]` | none | **`\|D\|=4` reconciled this pass**; no impl |
| 10 | **Γ** | `Assertion × GovCtx → GovState` | — | **EKP `authorities.yaml`** | E14 | **L5 partial** | `[R]` | **yes** | no `Authorize()` runtime |
| 11 | **Identity** | `id = H(P,e,c,t,Π)` | eq, dedup | reference **(C-NEW fixed)** | E2 PASS | **L5** `knowledge_id` | `[R]` | none | *none* |
| 12 | **Equality** | structural / semantic / observational / history | Compare | reference | E2 PASS | **L5** | `[R]` | none | *none* |
| 13 | **Q_t** | `Q_t ⊆ P`, event-derived projection | Ask | reference | **10/10** F17/18 | ✗ | `[E]` | none | **`unask` semantics ND-282-1** |
| 14 | **T** | `𝕂×Op×Policy×Authority ⇀ 𝕂×Outcome` | 13 ops | reference | E7,E24 | ✗ | `[E]` | Policy, Authority | no guarded transition in EKP |
| 15 | **Policy** | `(id,ver,Gates,Validity,Resolution)` | Apply, Supersede | ref + **EKP schema** | F1–F11 PASS | **L5 partial** — lint *is* an evaluator | `[R]` | **yes** | GC-1 collision |
| 16 | **Authority** | relation `Auth(a,r,c,p)` | — | **EKP enum only** | E14 PASS | **L5 partial** | `[R]` | **yes** | `Authorize_runtime` absent |
| 17 | **Authorization** | `c_t = Authorize(N_t,a_t,Policy_t)` | Authorize | formal only | F2,F4,F7 PASS | ✗ | `[E]` | **yes** | **runtime absent** |
| 18 | **History** | `𝕂 → Histories`, external | append, fold | reference (git in EKP) | E8,E11 PASS | **L1** | `[E]` | none | not a platform concept |
| 19 | **Replay** | `fold(T,∅,H)` | Replay | reference | E8,F10 PASS | ✗ | `[E]` | none | no platform replay |
| 20 | **Provenance Π** | intrinsic, **t=0-safe** | — | reference | E9 PASS | ✗ | `[E]` | none | EKP `authority` ≠ origin |
| 21 | **Lineage** | `Π ∘ ℛ_der*` | Trace | ref + **`GovernanceLineageGraph`** | E10, **47 tests** | **L5** | `[R]` | none | *none* |
| 22 | **Missingness** | 7 states via `Q_t` | Ask, Assess | reference | **7/7** E4-R | ✗ | `[E]` | none | not observable |
| 23 | **Orphan** | `∄(f,t,ty)∈ℛ : a.id∈{f,t}` | — | ref + **EKP `orphan_document`** | E4-R7 PASS | **L5** | `[R]` | none | *none* |
| 24 | **Measurement model** | scale-typed Dimension, ORDINAL | compare only | declared | E19 **L2** | ✗ | `[F]` | none | **executor absent** |
| 25 | **𝒪_core** | taxonomy CLOSED / **kernel OPEN** | — | — | not executed | ✗ | `[U]` | none | **minimality unproven (see 02)** |

## Lane totals
```
Formal defined            25 / 25
Executable test exists    22 / 25      (𝒪_core minimality, measurement executor, Authorize runtime absent)
Real-environment (L5)      9 / 25      K, 𝒜, ℛ, Dimension*, Γ*, Identity, Equality, Policy*, Lineage, Orphan
Governance dependency      4 / 25      Γ, Policy, Authority, Authorization
Zero remaining blocker     4 / 25      Identity, Equality, Lineage, Orphan
```
`*` partial.

> **Only 4 of 25 constructs are clear in every lane.** **9 have real-environment evidence.**
> **The dominant constraint is not theory — it is that 16 constructs have no real-environment witness,
> and most of those have none because the EKP does not implement them.**
