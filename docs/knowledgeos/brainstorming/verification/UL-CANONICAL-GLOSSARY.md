---
artifact: F · UL-CANONICAL-GLOSSARY
mandate: 20260830_1852 §13
date: 2026-08-30
status: DELIVERED — **closes self-audit gap G8, owed across four mandates**
evidence: 1468-file corpus scan · step 252 (independent) · Q14 · running EKP vocabularies
---

# Canonical Ubiquitous Language

**Kind** = object · relation · operation · property · assessment.
Rows marked **⚠** carry a live conflict that is **recorded, not reconciled**.

| Term | Mathematical meaning | Domain meaning | Lifecycle | Kind | Corpus synonyms | Forbidden conflations |
|---|---|---|---|---|---|---|
| **Observation** | `(sensor, time, reading)` | what was seen, by what, when | transient | object | "reading", "measurement" | **≠ Evidence** (252) |
| **Evidence** | `ref × polarity × state`, held **by reference** | a *qualified* observation bearing on a claim | active → withdrawn → invalidated | object | "evidence set", "envelope" | **≠ Observation** (253: `Evidence = QualifiedObservation`); **≠ Proof** |
| **Proposition** | `P = (E,D,V)`, `E∈ℰ, D∈𝒟, V∈V_D` | a claim that an entity has a value on a dimension | none — timeless | object | "claim" ⚠, "atom" | **≠ Assertion**; **≠ Claim** (253); **≠ Truth** |
| **Assertion** | `(id, P, e, c, t, Π)` | a proposition someone committed to, with support and origin | immutable once created | object | "claim" ⚠, "knowledge atom" | **≠ Proposition**; **≠ Truth** (252) |
| **Knowledge State** | `K = (𝒜, ℛ)` | everything held at a point in time | evolves by `T` | object | "K", "substrate", "corpus" | **≠ History**; **≠ Knowledge** |
| **Relation** | `ℛ`: 3 DAGs + 2 edge sets | how assertions stand to each other | stored (asserted) or computed (derived) | relation | "edge", "link", "relationship" | asserted **≠** derived |
| **Assessment** | `P × Evidence × Context × Policy → Σ` | judging how well a claim is supported | pure — no state change | operation | "evaluation", "appraisal" | **≠ Validation** (252); **≠ Decision** |
| **Determination** | `Assessment × Authority → ruling` | an authority's fixed finding | fixed at issuance | object | "ruling", "finding" | **≠ Decision** (252); ⚠ also used as an *aggregate* (196.26) |
| **Decision** | `Determination → commitment to act` | choosing to act | committed | operation | "resolution" ⚠ | **≠ Determination** (252) |
| **Validation** | `K × X → Assessment` | checking against a standard | pure | operation | "verification" ⚠, "checking" | **≠ Assessment** (252); **≠ Transformation** (232.4) |
| **Transformation** | `𝕂 × Op × Policy × Authority ⇀ 𝕂 × Outcome` | the only thing that changes `K` | appends History | operation | "transition", "T", "update" | **≠ Assessment**; **≠ Governance action** |
| **Event** | a projection of a transformation | something that happened | append-only | object | "domain event", "act" | **≠ Transformation** (an event *records*, it does not *effect*) |
| **Provenance** | `Π` — an assertion's origin | where this claim came from | fixed at creation | property | "source", "origin" | **⚠ ≠ Lineage; ≠ EventProvenance** — three objects, one word |
| **Lineage** | `History(T)` restricted to one assertion's ancestry | how this came to be | grows | relation | "trace", "chain" | **≠ Provenance**; **≠ History** |
| **History** | `𝕂 → Histories`, external to `K` | the full ordered record of transformations | append-only | object | "log", "journal", "audit trail" | **≠ Knowledge State** (executed: `History(K) ≠ K`) |
| **Authority** | a `T` parameter: competence to act | who may do this | granted/revoked | property | ⚠ also a *trust rank* in `authorities.yaml` | **≠ Truth** (252); **actor ≠ authority** |
| **Policy** | a `T` parameter: constraint on operations | the rule being applied | versioned | object | "rule", "constraint" | **≠ Governance** (252) |
| **Governance** | `Policy × Transformation → Admissible` | the regime deciding admissibility | ongoing | operation | "control", "oversight" | **≠ Policy** (252); **≠ Authority** |
| **Epistemic Status** | `Σ = (dir, str)`, **ORDINAL**, derived | how well supported | recomputed | assessment | "status" ⚠, "confidence" ⚠ | **≠ Governance Status**; **≠ Truth** (252); **never stored** |
| **Governance Status** | `Γ`, orthogonal to `Σ` | standing in the governance regime | lifecycle | property | "approval state" | **⊥ Σ** — enforced by `authorities.yaml` |
| **Uncertainty** | **NOT DEFINED** | — | — | — | "confidence" ⚠ | **≠ Epistemic Status**; **CB-3** |
| **Contradiction** | `𝕂 × 𝒜 × 𝒜 → Bool`; symmetric, irreflexive, **NOT transitive** | two claims that cannot both hold here and now | derived | relation | "conflict" ⚠, "inconsistency" | **≠ Conflict**; **no contradiction classes** |
| **Conflict** | `conflictsWith(a) ⟺ e has both polarities` | one claim pulled both ways by its own evidence | derived | property | "contradiction" ⚠ | **≠ Contradiction** — *between* assertions vs *within* one |
| **Supersession** | `ℛ_sup`, transitive, **acyclic**, authority-gated | a newer claim replaces an older one | permanent | relation | "replacement" | **≠ Deletion** (218.21); **≠ Contradiction**; **≠ Invalidation** |
| **Deprecation** | a lifecycle marker | no longer recommended | lifecycle | property | "retired", "obsolete" | **≠ Falsified** (218.22); **≠ Refuted** |
| **Invalidation** | a governance act | declared no longer admissible | terminal | operation | "revocation" | **≠ Refutation** — governance vs epistemic; **≠ Withdrawal** |

## The forbidden-conflation list — terms that MUST NOT be synonyms

**Corpus-established (step 252, independent, contamination-free):**
`Observation ≢ Evidence` · `Validation ≠ Assessment` · `Determination ≠ Decision` · `Assertion ≠ Truth` ·
`Authority ≠ Truth` · `Confidence ≠ Truth` · `Policy ≠ Governance` · `Command ≠ Action` ·
`Information ⊇ Knowledge` · `Claim ≡ Assertion` (252) but `Claim ≠ Proposition` (253)

**Derived in this programme:**
`Proposition ≠ Assertion` · `Knowledge State ≠ History` · `Provenance ≠ Lineage ≠ EventProvenance` ·
`Contradiction ≠ Conflict` · `Supersession ≠ Deletion ≠ Contradiction ≠ Invalidation` ·
`Deprecated ≠ Refuted` · `Σ ⊥ Γ` · **`Valid(A,t,C,M) ≠ Valid(K)`** · `Event ≠ Transformation` ·
`actor ≠ authority`

## Live conflicts — recorded, NOT reconciled

1. **`Provenance` names three distinct objects** — `Π` (assertion origin), `History(T)` (state lineage),
   `EventProvenance` (message causal chain, production code, Integration Events only). **Not three views of
   one thing.**
2. **`Authority` is doubly bound** — a competence to act (theory) and a **trust rank** (`authorities.yaml`:
   authoritative / derived / generated / historical / provisional).
3. **`Determination`** — a status (steps 186–189) and an **aggregate** (196.26). The TV-F-074 collision.
4. **`E` and `V` are each doubly bound** — Entity/Events, Value/Vertices. See artifact A.
5. **`supersedes` exists simultaneously as a relation and a lifecycle status** in the running EKP. **Both are
   legitimate; the status is a cached projection of the relation, never the source of truth.**
6. **`Confidence`** is used for both epistemic status and undefined uncertainty. **Since uncertainty is
   undefined (CB-3), every use of "confidence" is currently unanchored.**

## Abandoned corpus concepts — recorded, not revived

| Term | Files | Steps | Last seen |
|---|---|---|---|
| **Missingness** | 5 | 5 | step 184 |
| **Non-identifiability** | 3 | 2 | step 66 |

Both are named in the mandate's own glossary list. **Neither has a definition in the corpus sufficient to
place in this table.** They are listed here as **NOT_DEFINED**, not silently omitted.

## Classification

| Claim | Class |
|---|---|
| The 26-term table | **FORMALLY DERIVED** + corpus-anchored |
| Step 252's ten inequalities | **CORPUS ESTABLISHES** — independent, contamination-checked |
| The three-way `Provenance` collision | **EXECUTED** — source reads |
| `Σ ⊥ Γ` | **EMPIRICALLY SUPPORTED** — running linter |
| `Missingness`, `Non-identifiability` | **OPEN / NOT_DEFINED** |
| Glossary completeness | **OPEN** — 26 mandated terms covered; the corpus holds more |
