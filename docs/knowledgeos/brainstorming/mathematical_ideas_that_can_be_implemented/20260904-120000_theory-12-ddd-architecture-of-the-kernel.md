# Theory 12 — **The DDD Architecture the Experiments Imply**

**Document 12 of 15** · 2026-09-04
**Status: `[DESIGN]` — a strategic model, NOT an adopted architecture.** Kernel **NOT SELECTED**.

> Written in the DDD-architect role. **This document models; it decides nothing.** Its purpose
> is to make the ownership questions *askable*, because the standing rule is: **resolve
> ownership of every invariant BEFORE protecting it.**

---

## 1. The ubiquitous language, fixed

Terms are load-bearing. Where the experiments disambiguated a word, the disambiguation is
binding on the model.

| Term | Means | Does **not** mean |
|---|---|---|
| **Inquiry** $Q$ | the business question, a function of the data | a query string, a report |
| **Frame** $(Q, C, O)$ | inquiry + constraints + **a decoder fixed in advance** | a filter |
| **Transformation** $T$ | a total map from data to representation | a "cleanup step" |
| **Representation** $R$ | $T(D)$ | a serialization format |
| **Observable** $\Pi$ | what `Zero` is allowed to look at | a projection of the answer |
| **Eliminability** (`Zero`) | $\Pi(T(D)) = \Pi(T(E_S(D)))$ for a **specific** $S$, $T$, $\Pi$, contract | "this field is redundant" |
| **Adequacy** | $Q$ is determined by $R$ **over a population** | the consumer can read it |
| **Realization** | **this** decoder recovers $Q$ | $Q$ is recoverable in principle |
| **Boundary information** | provenance, uncertainty, scope, contradiction state | metadata |

`[EXP]` The last row is not a naming preference: **one elimination in six is blocked by
boundary information alone.** Calling it metadata would model it as removable, and the data say
it is not.

---

## 2. Candidate bounded contexts

```
┌──────────────────────────────────────────────────────────────────────┐
│                    CONSTITUTIONAL KERNEL  (shared)                    │
│  the three predicates · the type discipline · read-disjointness       │
│  NO context owns these.  EVERY context preserves them.                │
└──────────────────────────────────────────────────────────────────────┘
     ▲              ▲               ▲                ▲             ▲
┌────┴────┐  ┌──────┴──────┐  ┌─────┴──────┐  ┌──────┴─────┐ ┌─────┴──────┐
│ INQUIRY │  │TRANSFORMATION│  │ELIMINABILITY│  │PRESERVATION│ │ EVIDENCE & │
│         │  │              │  │             │  │ ASSESSMENT │ │CONTRADICTION│
│ owns    │  │ owns         │  │ owns        │  │ owns       │ │ owns        │
│ Q, C, O │  │ T, provenance│  │ Zero, E_S   │  │ adequacy   │ │ φ, contra-  │
│ frames  │  │ the catalogue│  │ the ladder  │  │ N_viol, Ĥ  │ │ diction,    │
│         │  │              │  │             │  │            │ │ composition │
└─────────┘  └──────────────┘  └─────────────┘  └────────────┘ └─────────────┘
                                                        ▲
                                                 ┌──────┴──────┐
                                                 │ REALIZATION │
                                                 │ owns decoder│
                                                 │ execution   │
                                                 └─────────────┘
```

### The five strategic questions, answered per context

| Context | Owns | Affects | Published Language? | Ownership changes? |
|---|---|---|---|---|
| **Inquiry** | $Q$, $C$, $O$, the frame lifecycle | all downstream | **yes** — the frame is the contract | no |
| **Transformation** | $T$, the provenance map, the family kind (sequential/parallel) | Eliminability, Preservation | yes — provenance is published | no |
| **Eliminability** | `Zero`, typed $E_S$, the elimination process | nothing downstream | yes | no |
| **Preservation Assessment** | adequacy, $N_{\text{viol}}$, $\hat H$ | Realization | yes | no |
| **Realization** | decoder execution, constraint enforcement at use | nothing | yes | no |
| **Evidence & Contradiction** | $\varphi$, evaluation structure, composition | Inquiry (supplies $Q$-inputs) | **`[OPEN]` — blocked on `DECISION-02`** | **unresolved** |

> ⚠️ **`Evidence & Contradiction` has unresolved ownership** because `DECISION-02` — *is
> $\varphi$ a semantic evaluation frame or an evidence partition?* — is open. **The model must
> not proceed to tactical work there.** A frame belongs to Inquiry; a partition belongs to
> Evidence. The answer decides which context owns $\varphi$, and no experiment can decide it.

---

## 3. The ownership rule the experiments force

> ### Read-disjointness is a **constitutional invariant**. **No context owns it. Every context preserves it.**

Read-disjointness relates $\Pi$ (Eliminability), $Q$ (Inquiry) and $T$ (Transformation)
**simultaneously**. It cannot be owned by any one of them:

- Inquiry cannot own it — it does not know $\Pi$.
- Eliminability cannot own it — it does not know $Q$.
- Transformation cannot own it — it knows neither, but its **provenance map is what makes the
  invariant checkable.**

`[EXP]` This is exactly the shape of the anonymity invariant in the sibling platform: a
**constitutional** invariant that a context *preserves* rather than *owns*. The experiments
reached the same structure independently, which is mild evidence the shape is real.

`[REC]` **Transformation must publish its provenance map as part of its Published Language**,
and that map must be **empirically verified**, not declared. This is the tactical consequence of
`FR-003`.

---

## 4. Aggregates and value objects

| Element | Kind | Invariant it protects |
|---|---|---|
| `InquiryFrame` | **aggregate root** | $(Q, C, O)$ change together; $O$ is immutable once the frame is issued |
| `TransformationSpec` | **aggregate root** | the transformation, its provenance map, and its family kind are one unit |
| `EliminationAssessment` | **aggregate root** | a `Zero` verdict is inseparable from its $(S, T, \Pi, \text{contract}, \text{reference})$ |
| `Representation` | **entity** | identity = the transformation applied + the source case |
| `ProvenanceMap` | **value object** | output field → originating fields, **including arity** |
| `MultiplicityProfile` | **value object** | the multiplicity partition of a case |
| `AdequacyVerdict` | **value object** | carries its **population**, because adequacy is population-relative |
| `EpistemicStatus` | **value object** | one of the ten tags; a statement without one is invalid |

### The aggregate boundary that the data forced

`[EXP]` **`EliminationAssessment` must carry its contract and reference.** `Zero` is
contract-relative (495 / 4 049 verdicts flip) and reference-relative (541 / 4 260). An
assessment stored without them is **not a fact about the data** and will be wrong when reused.

`[NEG]` **There is no `EliminableCore` aggregate.** $\mathcal Z$ is not generated by its
minimal elements (286 / 826 failures). Any design that caches minimal eliminable sets and closes
upward is refuted by the data.

---

## 5. Domain events

| Event | Emitted by | Meaning |
|---|---|---|
| `FrameIssued` | Inquiry | $(Q,C,O)$ fixed; $O$ now immutable |
| `TransformationRegistered` | Transformation | with a **verified** provenance map |
| `ProvenanceVerificationFailed` | Transformation | **a declared map disagreed with perturbation** — this must be an event, not a log line |
| `EliminationAssessed` | Eliminability | a `Zero` verdict, with its full index |
| `AdequacyEvaluated` | Preservation | with its population |
| `RealizationFailed` | Realization | decoder or constraint failed on an *adequate* representation |
| `StatusPromoted` | Governance | e.g. `[CONJ] → [EXP]`; **never `[EXP] → [THM]` without a proof artifact** |

`[REC]` `ProvenanceVerificationFailed` and `RealizationFailed` are the two events the programme
learned it needed. Both name failures that were **invisible** in the first designs.

---

## 6. Ports

```
Eliminability  →  ZeroObservablePort      (Π; must NOT expose Q)
Preservation   →  InquiryPort             (Q; must NOT expose Π)
Realization    →  DecoderPort             (O, fixed)  +  ConstraintPort (C)
Transformation →  ProvenancePort          (the verified map)
all            →  EpistemicStatusPort     (tagging is mandatory, not advisory)
```

`[REC]` **The port structure enforces read-disjointness by construction.** If
`ZeroObservablePort` cannot see $Q$ and `InquiryPort` cannot see $\Pi$, the circularity that
produced the false $MH\text{-}RD = -0.155$ is unrepresentable — but **only** if
`ProvenancePort` is consulted, because the leak came through $T$, not through the ports.

---

## 7. Anti-patterns, each earned

| Anti-pattern | Why it is wrong |
|---|---|
| Treating "eliminate everything eliminable" as well-defined | $L$ is **non-confluent**; the normal form depends on order |
| Caching minimal eliminable sets and closing upward | $\mathcal Z$ is not generated by its minimal elements |
| Storing a `Zero` verdict without its contract and reference | `Zero` is contract- and reference-relative |
| A single "reduction size" scalar | reduction is multi-dimensional; bytes were flat while cardinality fell 8× |
| Re-fitting the decoder after seeing $R$ | collapses Realization into Adequacy |
| Declaring a field read-set instead of verifying it | two hand-written maps were wrong |
| Modelling boundary/provenance as removable metadata | it blocks 1 elimination in 6 |
| Letting a ticket accrete into architecture | **architecture produces tickets; tickets do not become architecture** |

---

## 8. What this model does **not** do

- It does **not** select a kernel. `[OPEN]`
- It does **not** declare a carrier. `[OPEN]` — and without a carrier the tactical model cannot
  be completed.
- It does **not** resolve `DECISION-02`, and therefore leaves `Evidence & Contradiction`
  deliberately unowned.
- It does **not** promote `FR-003`; that needs a second adopter (`ES-006.1`).

`[REC]` **The correct next strategic act is a carrier decision, not more tactical modelling.**
Every unresolved item above is downstream of it.
