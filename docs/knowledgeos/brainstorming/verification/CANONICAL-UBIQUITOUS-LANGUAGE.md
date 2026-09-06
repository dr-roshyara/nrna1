---
artifact: J · CANONICAL-UBIQUITOUS-LANGUAGE
mandate: 20260830_1918 §13
date: 2026-08-30
status: FROZEN where derived · PROPOSED where merely argued · **written last, as instructed**
---

# Canonical Ubiquitous Language

**Written after the mathematics stabilised, per §13.** Terms are `FROZEN` only where the definition is
derived or corpus-established **and** nothing in this phase contests it.

| Term | Canonical meaning | Mathematical object | DDD role | Bounded context | Synonyms | **Forbidden synonym** | Evidence |
|---|---|---|---|---|---|---|---|
| **Knowledge** | *undefined* — only `Information ⊇ Knowledge` | — | — | — | — | **≠ Knowledge State** | 252 · **OPEN** |
| **Knowledge State** | what is held at a point in time | `K = (𝒜, ℛ)` | Aggregate | Knowledge | "K", "substrate" | **≠ Knowledge · ≠ History** | derived · **FROZEN** |
| **Assertion** | a proposition committed to, with support and origin | `(id,P,e,c,t,Π)` | Entity (has identity) | Knowledge | "claim" | **≠ Proposition · ≠ Truth** | Q7/Q14 · **FROZEN** |
| **Proposition** | a claim that an entity has a value on a dimension | `P=(E,D,V)` | Value Object | Knowledge | — | **≠ Assertion · ≠ Claim** | Q14 §5 · **FROZEN** |
| **Entity** (`E`) | anything that can be the subject of knowledge | element of `ℰ` | Value Object | Knowledge | "subject" | **≠ Evidence** (symbol `E` is doubly bound — G-9) | Q14 §2 · **FROZEN** |
| **Dimension** (`D`) | a semantic axis of variation, carrying ValueSpace **and scale type** | `(ID,Name,ValueSpace,Type,Domain)` | Value Object | Knowledge | "attribute", "predicate" | **≠ Domain** (domain is a *field of* `D`) | Q14 §3 · **FROZEN** |
| **Value** (`V`) | a position on a dimension | element of `V_D` | Value Object | Knowledge | — | **≠ Validation** (symbol `V` doubly bound — G-9) | Q14 §4 · **FROZEN** |
| **Observation** | a sensor reading at a time | `(sensor, at, reading)` | Value Object | Evidence | "reading" | **≠ Evidence** | 252 · **FROZEN** |
| **Evidence** | a **qualified** observation, held by reference | `(ref, polarity, state)` | Value Object | Evidence | "support" | **≠ Observation · ≠ Proof** | 253 + ADR-T16 · **FROZEN** (qualification rule OPEN — G-4) |
| **Context** | the scope in which a claim is asserted | `c` | Value Object | Knowledge | "scope" | **≠ Bounded Context** (though EKP uses that field for it) | 025a-2 · **FROZEN** |
| **Epistemic Status** | how well supported, **derived not stored** | `Σ = (dir, str)`, `str` ORDINAL | derived attribute | Knowledge | "status", "confidence" | **≠ Governance Status · ≠ Truth · ≠ Uncertainty** | **PROPOSED — G-1 open** |
| **Governance Status** | standing in the governance regime | `Γ` | attribute | Governance | "authority", "approval" | **⊥ Σ** | `authorities.yaml` · **FROZEN** |
| **Authority** | competence to perform a transformation | a `T` parameter | Policy | Governance | — | **≠ Policy · ≠ Truth · ≠ actor** | 252 · **FROZEN** (doubly bound with trust-rank — recorded) |
| **Policy** | the constraint applied to an operation | a `T` parameter; **object undefined** | Policy | Governance | "rule", "schema" | **≠ Governance · ≠ Authority** | 252 · **OPEN — G-2** |
| **Governance** | deciding admissibility | `Policy × Transformation → Admissible` | Domain Service | Governance | — | **≠ Policy** | 252 · **FROZEN** |
| **Validation** | checking against a standard; **four distinct predicates** | `Structural/Semantically/Epistemically/GovernanceValid` | Domain Service | Knowledge | "checking" | **≠ Assessment · ≠ Verification · `Valid(K)` ≠ `Valid(A,t,C,M)`** | 252, 232.4 · **FROZEN** |
| **Assessment** | judging support | `P×Evidence×Context×Policy → Σ` | Domain Service | Knowledge | "evaluation" | **≠ Validation · ≠ Decision** | 232.4 · **FROZEN** (uncomputable — G-2) |
| **Determination** | an authority's fixed finding | `Assessment × Authority → ruling` | Aggregate | Adjudication | "ruling" | **≠ Decision** | 252 · **FROZEN** |
| **Decision** | commitment to act | `Determination → commitment` | Domain Event | Governance | — | **≠ Determination · ≠ Action** | 252 · **FROZEN** |
| **Transformation** | the only thing that changes `K` | `𝕂×Op×Policy×Authority ⇀ 𝕂×Outcome` | Domain Service | Knowledge | "transition", "T" | **≠ Event · ≠ Assessment · ≠ Governance action** | derived · **FROZEN** |
| **Event** | a record that a transformation occurred | projection of `T` | Domain Event | Knowledge | — | **≠ Transformation** | 253 · **FROZEN** |
| **Provenance** | the origin of an assertion | `Π` | attribute | Knowledge | "source" | **≠ Lineage · ≠ EventProvenance** | derived · **FROZEN** |
| **Lineage** | ancestry of an assertion | reachability in `ℛ_der ∪ ℛ_ref` | derived relation | Knowledge | "trace" | **≠ Provenance · ≠ History** | implemented, 47 tests · **FROZEN** |
| **History** | the ordered record of transformations, **external to `K`** | `𝕂 → Histories` | Aggregate | Knowledge | "log", "journal" | **≠ Knowledge State · ≠ Lineage** | executed · **FROZEN** |
| **Identity** | which assertion this is | `id`, content-addressed | — | Knowledge | "key" | **≠ Equality** | executed + `knowledge_id` · **FROZEN** |
| **Equality** | when two states are the same | structural / semantic / observational / history | — | Knowledge | — | **≠ Identity** | executed · **FROZEN** |
| **Contradiction** | two claims that cannot both hold here and now | `𝕂×𝒜×𝒜→Bool`; symmetric, irreflexive, **non-transitive** | derived relation | Knowledge | "inconsistency" | **≠ Conflict · no contradiction classes** | executed · **FROZEN** |
| **Conflict** | one claim pulled both ways by its own evidence | `supporting(e)≠∅ ∧ contradicting(e)≠∅` | derived predicate | Knowledge | — | **≠ Contradiction** | executed · **FROZEN** |
| **Supersession** | a newer claim replaces an older, **which is retained** | `ℛ_sup`: transitive, **acyclic**, authority-gated | relation | Knowledge | "replacement" | **≠ Deletion · ≠ Contradiction · ≠ Invalidation · ≠ Deprecation** | 218.21 + implemented · **FROZEN** |
| **Deprecation** | no longer recommended | lifecycle marker | attribute | Governance | "retired" | **≠ Refuted · ≠ Superseded** | 218.22 · **FROZEN** |
| **Invalidation** | declared no longer admissible | governance act | operation | Governance | "revocation" | **≠ Refutation · ≠ Withdrawal** | derived · **FROZEN** |
| **JustificationStrength** | how well justified a claim is | `Claim → ORDINAL {prose,reviewed,tested,executable,proven}` | derived attribute | Knowledge | *replaces* "Assurance" | **≠ Risk · ≠ Traceability · ≠ Σ** | artifact B · **PROPOSED** |
| **Assurance** | — | **NONE** | **REMOVED as a formal term** | — | umbrella word only | **must not appear in any signature** | artifact B · **REFUTED** |
| **Uncertainty** | — | **NOT DEFINED** | — | — | "confidence" | **≠ Epistemic Status** | **OPEN — G-3** |
| **Missingness** | — | **NOT DEFINED** | — | — | — | — | dead after step 184 · **OPEN** |

## The ten mandated enforcements — all satisfied

`Knowledge ≠ KnowledgeState` ✓ · `Evidence ≠ Observation` ✓ · `Claim ≠ Proposition` ✓ ·
`Validation ≠ Assessment` ✓ · `Provenance ≠ Lineage` ✓ · `Identity ≠ Equality` ✓ ·
`Policy ≠ Authority` ✓ · `Decision ≠ Action` ✓ · `State ≠ History` ✓ ·
`Epistemic Status ≠ Governance Status` ✓ **(the last is enforced by a running linter)**

## Terms whose status changed this phase

| Term | Change |
|---|---|
| **Assurance** | **REMOVED** from the formal vocabulary — split four ways |
| **JustificationStrength · Traceability · Risk** | **NEW** — the split's products |
| **Entity · Dimension · Value** | **NEW as first-class terms** — previously hidden inside an opaque `P` |
| **Conflict** | **SEPARATED** from Contradiction |
| **`ℛ_rel` (related_to)** | **NEW** — symmetric family, learned from the implementation |
| **Epistemic Status** | **DEMOTED** from FROZEN to PROPOSED — the three-state model was refuted |
