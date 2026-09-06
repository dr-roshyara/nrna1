---
artifact: I · FINAL-THEORY-GAP-REGISTER
mandate: 20260830_1918 §14, §18
date: 2026-08-30
status: **9 gaps · 2 blocking · dependency chain otherwise CLOSED**
---

# Final Theory Gap Register

## Dependency-driven search — each arrow interrogated

```
primitive inputs ──①──► assertion/proposition ──②──► knowledge state ──③──► transformation
   ──④──► resulting state ──⑤──► invariants ──⑥──► assessment/validation
   ──⑦──► governance/authority ──⑧──► observable evidence
```

| Arrow | What is required | Present? |
|---|---|---|
| ① | `ℰ`, `𝒟` with ValueSpace + scale, `V_D`; `WellFormed(P)` | **YES** — Q14; executed |
| ② | `id`, membership, `StructuralValid`, `SemanticallyValid` | **YES** — executed |
| ③ | `T` signature; guards; rejection kinds | **YES** — derived, executed |
| ④ | closure, determinism, congruence | **YES** — executed |
| ⑤ | 4 validity predicates; acyclicity; contradiction | **YES** — executed |
| ⑥ | `Assessment` with **Policy semantics** | **NO — G-2** |
| ⑦ | `Γ` value set; authority model | **YES for `Γ`** (`authorities.yaml`); **authority model partial** |
| ⑧ | observation→evidence qualification rule | **PARTIAL — G-4** |

> **Six of eight arrows are closed and executed. The chain breaks at ⑥.**

## The register

| ID | Question | Missing definition/theorem | Why it matters | Depends on | Source evidence | Math status | Eng status | Blocking? | Resolution route | Test required | Verdict |
|---|---|---|---|---|---|---|---|---|---|---|---|
| **G-1** | What is `Σ`? | a signed ordinal scale, or Q14's vector, or neither | status is derived on every read; the wrong shape corrupts every assessment | `e` polarity | Q14 §6; my 3-state **refuted** | **OPEN** — both candidates refuted on different axes | absent | **YES** | derive from a policy-parameterised assessment once G-2 closes | 10-case audit re-run | **BLOCKING** |
| **G-2** | What is a Policy, formally? | `Policy` as a mathematical object: how it maps an evidence set to a support level | `Assessment` is well-typed but **uncomputable** without it; G-1 depends on it | `e` | executed: same evidence + different policy → different `Σ` | **OPEN** | `knowledge-schema.yaml` **is** an executing policy — a model exists | **YES** | generalise from the EKP's schema-as-policy | run assessment under ≥2 policies | **BLOCKING** |
| **G-3** | What is uncertainty? | a representation; **no `(Ω,ℱ,P)` in 1468 files** | step 246 asserts `KnowledgeOS = Probability Distribution` with no measurable space | G-1 | 199.19 rules out a scalar | **OPEN** | absent | no — `Σ` works without it | **decide or drop.** Ordinal `str` may suffice | construct `(Ω,ℱ,P)` or retract the claim | **OPEN — leaning DROP** |
| **G-4** | When does an observation become evidence? | the qualification predicate in `Evidence = QualifiedObservation` | 253 names the concept; nothing says what qualifies | ① | 253 (independent) | **OPEN** | `EvidenceSet` (other BC) requires non-blank, non-duplicate refs — a **partial** rule | no | derive from the EKP + `EvidenceSet` discipline | inject an unqualified observation | **OPEN** |
| **G-5** | Is `Q` (epistemic qualification) inside `K`? | a decision procedure between two consistent normalisations | Claude derives external; step 253 puts it inside | G-1 | 253 vs executed | **OPEN — both consistent** | EKP stores lifecycle but derives nothing | no | **falsifier exists:** does stored status diverge from recomputed? | artifact I test 12 | **OPEN — genuinely undecided** |
| **G-6** | Acyclicity of `supersedes` | none — theorem proven; **implementation does not enforce it** | a cycle makes "which is current?" undefined | ⑤ | proven; EKP `circular_dependency` is a *warning* over `requires`/`depends_on` only | **PROVEN** | **NOT CONFORMANT** | no — currently vacuous (0 edges) | extend the lint rule; raise to error | close a supersession cycle and lint | **ACTIONABLE ENGINEERING FIX** |
| **G-7** | `retract` breaks `F∘T̂ = T∘F` | either exclude `retract` or add tombstones | step 254's own adequacy criterion fails on one operation | ③ | 254 (independent) + executed cascade lossiness | **OPEN** | absent | no | adopt append-only (matches `ReplayAssertion`'s immutability invariant) | commuting-square test on `retract` | **OPEN — recommend append-only** |
| **G-8** | ~500 invariant IDs, zero crosswalks | a crosswalk | the corpus's invariants are unreconciled with the 18 executable ones | — | my own standing gap G2 | not attempted | 18 executable rules exist | no | crosswalk corpus invariants against the 18 | — | **OPEN — verifier's own debt** |
| **G-9** | `E`/`V` symbol collision | disambiguation | `E`=Entity/Events, `V`=Value/Vertices | ① | executed scan | terminology | n/a | no | rename in the canonical vocabulary | — | **OPEN — terminology** |

## Closed since the previous phase

| Was | Now |
|---|---|
| **CB-1 Assurance contradictory** | **CLOSED — split into `JustificationStrength` / `Traceability` / `Risk` / `GovernanceValid`** (artifact B) |
| **CB-2 `P=(E,D,V)` undefined** | **CLOSED — Entity/Dimension/Value, Q14** |
| **CB-4 theory disconnected from EKP** | **CLOSED — triangulated; 7 predicates engineeringally verified** |
| **CB-7 state/history boundary** | **CLOSED — SUFFICIENT UNDER ASSUMPTIONS, structurally** |

## §15 — Normative decisions

**None are put to you.** For each candidate I performed the five required attempts:

- **G-1/G-2** — not normative: G-2 is derivable from the EKP's schema-as-policy, and G-1 follows. **Derivation not yet done, not impossible.**
- **G-3** — not yet normative: the evidence *leans against* the probabilistic reading (no measurable space; ordinal scale). **A derivation may still refute it outright.**
- **G-5** — the only candidate that may become genuinely normative. **But a falsifier exists** (does stored status diverge from recomputed?), so it is an **empirical** question first. **Asking now would be premature.**
- **G-6** — an engineering fix with a proven theorem behind it, not a choice.

> **I am asking you nothing this phase.** Every open item has a derivation route or a test that has not yet
> been attempted.

## §18 — FINAL DECISION GATE

> *Can the KnowledgeOS theory now be constructed, instantiated, transformed, compared, assessed/validated,
> and checked for validity without undefined foundational objects or circular definitions?*

> ## **FORMALLY INCOMPLETE**

**Not `FORMALLY INCONSISTENT`:** no contradiction survives in the canonical model. The two that existed —
`Assurance`'s self-reference and the `Σ` bidirectional refutation — are respectively **split** and **held
open**, not carried as inconsistencies.

**Not `FORMALLY CLOSED`:** **constructed ✓ · instantiated ✓ · transformed ✓ · compared ✓ · checked for
validity ✓ — but ASSESSED ✗.** `Assessment` is well-typed and **uncomputable**, because `Policy` is
undefined (G-2), and `Σ`'s shape depends on it (G-1). **One of the six required capabilities fails.**

**No circular definitions remain.** The dependency graph was re-examined: `Σ` derives from `Assessment`,
never from `K`; `contradicts` reads `ℛ` but `ℛ` is stored; `Valid` is structural and does not invoke
`contradicts`. **Acyclic.**

**No undefined foundational objects remain** — `P`, `Entity`, `Dimension`, `Value`, `Evidence`, `Assertion`,
`K`, `T`, `History`, `Γ` are all defined. **`Policy` is the last undefined foundational object, and it is
the one that blocks.**

> *Can the theory be demonstrated against the running KnowledgeOS/EKP implementation?*

> ## **PARTIALLY ENGINEERINGALLY CONFORMANT**

7 predicates enforced and executed · 1 non-conformant (unguarded acyclicity, G-6) · 6 absent · 3
implementation invariants exceed the theory.

**The two verdicts are deliberately not collapsed:** the theory is *more* complete mathematically than the
EKP implements, and the EKP enforces *more* governance invariants than the theory has. **Neither dominates.**
