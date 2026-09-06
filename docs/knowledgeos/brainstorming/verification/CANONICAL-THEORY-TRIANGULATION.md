---
artifact: A · CANONICAL-THEORY-TRIANGULATION
mandate: 20260830_1918 §3
date: 2026-08-30
status: DELIVERED — 26 concepts, three evidence streams
evidence_streams: corpus (INDEPENDENT) · mathematics (verifier) · running EKP (INDEPENDENT, third stream)
---

# Corpus ↔ Mathematics ↔ Running EKP

**Feedback-loop discipline (§16):** the **corpus** column is `INDEPENDENT CORPUS EVIDENCE` except where
noted; the **EKP** column is `INDEPENDENT` throughout — it predates and never cites this programme. The
**mathematics** column is verifier work and is never counted as independent confirmation of itself.

| Concept | Historical corpus | Derived mathematics | Running EKP | Canonical conclusion |
|---|---|---|---|---|
| **Knowledge** | `Information ⊇ Knowledge` (252) | not defined | — | **OPEN** — a boundary, not a definition |
| **Knowledge State** | `Structure(X,R,Q,H,…)` (253); Q7 6-tuple | `K=(𝒜,ℛ)` | governed docs + typed edges | **MATHEMATICALLY VERIFIED** for `(𝒜,ℛ)`; `Q`,`H` **CONTRADICTED** |
| **Assertion** | `A=(P,Σ,E,τ,Π)` (Q7); `Claim≡Assertion` (252) | `(id,P,e,c,t,Π)` | doc + frontmatter — **no `e`, no `t`, no `Π`** | **CORPUS ESTABLISHES**; EKP implements a **strict subset** |
| **Proposition** | `P=(E,D,V)` = Entity/Dimension/Value (Q14) | isomorphic to `(s,p,o)` | **ABSENT** — title is prose | **CORPUS ESTABLISHES**; **NOT ENGINEERINGALLY VERIFIED** |
| **Evidence** | `Evidence=QualifiedObservation` (253) | `ref × polarity × state` | **ABSENT** | **CORPUS ESTABLISHES**; ADR-T16 (`EvidenceSet`, other BC) confirms by-reference |
| **Observation** | `Observation ≢ Evidence` (252) | `(sensor,at,reading)` | **ABSENT** | **CORPUS ESTABLISHES** |
| **Context** | assertion-scoped (025a-2) | `c` | **`bounded_context`, enum-enforced** | **ENGINEERINGALLY VERIFIED** |
| **Epistemic Status** | `Σ=(A,S,R,V,C)` 2240 states (Q14) | `(dir,str)` signed ordinal | **ABSENT** | **OPEN** — both refuted (artifact B, prior phase) |
| **Authority** | a `T` parameter | `T` parameter | **`authorities.yaml`, 5 ranked values, enum-enforced** | **ENGINEERINGALLY VERIFIED** — but **doubly bound** (competence vs trust-rank) |
| **Validation** | `Validation ≠ Assessment` (252, 232.4) | 4 distinct predicates | **`knowledge-lint`, 18 rules** | **ENGINEERINGALLY VERIFIED** for structural validity |
| **Assessment** | `Prop×Evidence×Context×Policy→Σ` | executed; **Policy semantics missing** | **ABSENT** | **OPEN** — under-specified, not incomputable |
| **Provenance** | not separated from lineage | `Π`, intrinsic | **ABSENT** (`authority` is trust, not origin) | **CONTRADICTED** — three objects share the word |
| **Lineage** | conflated with provenance | `History(T)` restricted | graph edges | **MATHEMATICALLY VERIFIED**; `GovernanceLineageGraph` (other BC) implements it, 47 tests |
| **Identity** | `id` (025a-2); `Identity ≠ arbitrary Relation` (253) | content-addressed | **`knowledge_id`, unique + pattern, ERROR-enforced** | **ENGINEERINGALLY VERIFIED** — strongest triangulation in the table |
| **Equality** | `Equality depends on ontology` (246) | structural + semantic, both equivalence relations | **not computed** | **MATHEMATICALLY VERIFIED** |
| **Transformation** | `F∘T̂=T∘F` (254); `Action ∉ K` (253) | `𝕂×Op×Policy×Authority ⇀ 𝕂×Outcome` | **ABSENT** — editing is by hand | **MATHEMATICALLY VERIFIED**; **NOT** engineeringally |
| **History** | `H` inside `Structure` (253) | **outside `K`** (executed) | git; not modelled | **CONTRADICTED** — corpus vs mathematics; executed evidence favours mathematics |
| **Policy** | `Policy ≠ Governance` (252) | `T` parameter | **`knowledge-schema.yaml` IS a policy, executed by lint** | **ENGINEERINGALLY VERIFIED** |
| **Governance** | `Policy×Transformation→Admissible` | external to `K` | `single_authoritative`, `boundary_consistency` | **ENGINEERINGALLY VERIFIED** |
| **Decision** | `Determination ≠ Decision` (252) | `Determination×Authority→commitment` | **ABSENT** | **CORPUS ESTABLISHES** only |
| **Assurance** | **6 incompatible types** | undefinable as one term | **ABSENT** | **REFUTED as a single concept** — artifact B |
| **Invariant** | ~500 invariant IDs, no crosswalk | predicate over `𝕂` | **18 lint rules — executable invariants** | **ENGINEERINGALLY VERIFIED** for 18; the ~500 remain uncrosswalked |
| **Unknown** | AFR-10; irreducible | `dir=Neutral` | **ABSENT** | **CORPUS ESTABLISHES** |
| **Missingness** | 5 files, dead after step 184 | not modelled | **ABSENT** | **OPEN / NOT_DEFINED** |
| **Contradiction** | *"may be regime-relative"* (246) | `𝕂×𝒜×𝒜→Bool`, tolerance relation | **ABSENT** | **MATHEMATICALLY VERIFIED**; convergent with 246 independently |
| **Supersession** | relation not deletion (218.21) | transitive, **acyclic**, authority-gated | **`supersedes`/`superseded_by` + inverse; acyclicity NOT enforced** | **ENGINEERINGALLY VERIFIED** as a relation; **acyclicity is an unenforced gap** |

## The three disagreements, traced

**D-1 · Is History inside `K`?** Origin: step 253's `Structure(X,R,Q,H,…)`. Later material does **not**
supersede it — it is the corpus's most recent statement. Mathematically **incompatible** with executed
evidence: two states with identical content and different histories would be unequal, making `merge` and
equality incoherent. EKP does not model history at all (git does). **VERDICT: CONTRADICTED; executed
evidence stands, and the corpus claim is asserted rather than argued.**

**D-2 · Is epistemic qualification inside `K`?** Origin: Q14's `Σ` as an assertion component and 246's
"Epistemic Qualifications" layer. **Mathematically compatible either way** — it is a normalisation choice.
**EKP provides evidence: it stores `status` and `authority` but has no derived-status machinery at all**, so
it neither confirms nor refutes. **VERDICT: OPEN.** *(Falsifier: artifact I test 12.)*

**D-3 · Provenance vs lineage.** Origin: never separated in the corpus. Production code separates three
objects. **VERDICT: CONTRADICTED — the corpus is under-differentiated; a split is required.**

## What the EKP contributes that neither other stream had

1. **`Σ ⊥ Γ` as running configuration** — `authorities.yaml` states authority "is INDEPENDENT of status"
   with a worked cross-quadrant example, and a linter enforces both enums. **The strongest single piece of
   evidence in the programme, and it is independent of both other streams.**
2. **18 executable invariants** where the corpus has ~500 prose invariant IDs and zero crosswalks.
3. **A refutation by absence:** EKP has **no temporal validity, no evidence field, no provenance field, no
   `Σ`**. A working knowledge platform was built without them. **That is evidence about what is
   load-bearing in practice, and the theory must explain the omission rather than ignore it.**
