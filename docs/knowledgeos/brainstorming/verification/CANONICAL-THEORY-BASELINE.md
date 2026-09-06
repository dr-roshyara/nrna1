---
artifact: CANONICAL-THEORY-BASELINE
mandate: 20260830 §1
date: 2026-08-30
status: baseline for construction — no category upgraded
---

# Canonical Theory Baseline

## 1. Definitely established — `CORPUS ESTABLISHES`
`P = (E,D,V)` = Entity, Dimension, Value, with ValueSpace and **scale type on the Dimension** (Q14 §§2–5) ·
`P` excludes status, evidence, temporal validity, provenance (Q14 §5.3) · `WellFormed(P)` (Q14 §5.4) ·
`Policy = (Rules, ValidityInterval, ResolutionBehavior)` (57.47) ·
`Admissible = Pre ∧ Invariant ∧ Assurance ∧ Authorization`, **no averaging**, `Unknown → Block` (42.9–42.12) ·
`Evidence = QualifiedObservation` (253) · `Superseded` is a relation, not deletion (218.21) ·
`Deprecated ≠ Falsified` (218.22) · `EpistemicStrength ≠ GovernanceStatus` (194.19) ·
`Validation ≠ Assessment` (232.4, 252) · `Policy ≠ Authority ≠ Decision ≠ Governance` (202/240/206/252) ·
bitemporality (185) · `Unknown` irreducible (AFR-10) · assertion `id` (025a-2)

## 2. Formally derived — `FORMALLY DERIVED`
`K = (𝒜, ℛ)` · `Assertion = (id,P,e,c,t,Π)` · `e = Set(ref × polarity × state)` ·
`ℛ` = 3 DAGs ⊎ 2 non-transitive edge sets ⊎ 1 symmetric family ·
`T : 𝕂 × Op × Policy × Authority ⇀ 𝕂 × Outcome` (four parameters eliminated by distinguishability) ·
`Σ = (dir, str)`, `str` ORDINAL · `Γ` orthogonal · four validity predicates ·
`Lineage = Π ∘ ℛ_der*` · `Assurance` splits into JustificationStrength / Traceability / Risk / GovernanceValid

## 3. Theorems — `THEOREM`
`History(K) ≠ K` · `same proposition ≠ same assertion` · equality is an equivalence relation ·
`(𝕂, merge, ∅)` is a **join**-semilattice · `(Policy, ∧)` is a **meet**-semilattice ·
`contradicts` is a **tolerance relation** (symmetric, irreflexive, **non-transitive**) — so **contradiction
classes do not exist** · `supports` is **non-transitive** · `supersedes` must be **acyclic** ·
`retract` has **no total inverse** · `Split` is lossy on `ℛ` · `Merge` preserves structural but **not**
epistemic validity · congruence `F(H₁)=F(H₂) ⇒ F(T(H₁,i))=F(T(H₂,i))` holds **structurally**

## 4. Executed computation — `EXECUTED COMPUTATION`
`K₁ = δ(K₀, e₀)` computed end-to-end, **28 of 30 symbols resolved** · six `K` candidates constructed and
scored · provenance models A/B/C against nine scenarios (**A: 8/9, B: 3/9, C: 3/9**) ·
policy composition laws · policy equality (four notions) · Σ ten-case audit ·
**the corpus's own `ladder_dc_reference.py` executed — all checks pass**

## 5. Empirically observed — `EMPIRICALLY OBSERVED`
37 real governed documents parsed into `(𝒜, ℛ)` · `knowledge-lint` 37 docs exit 0 ·
`knowledge-graph` **byte-identical across two runs**, 39 nodes / 70 edges ·
`status`/`authority` **collinear in practice** despite being declared independent ·
one off-diagonal Σ×Γ document (`GRAPH-FULL`) · 47 lineage tests passing

## 6. Implementation evidence — `IMPLEMENTATION EVIDENCE`
`knowledge_id` unique (error) · `relationship_targets_exist` (error) · typed relation vocabulary with
inverses · `authorities.yaml` declares independence from `status` · 18 lint rules ·
`ReplayAssertion` (content-hash identity, immutable) · `EvidenceSet` (**holds no transport**, ADR-T16) ·
`EventProvenance` (a third provenance object)

## 7. Source claims, unverified — `SOURCE CLAIM`
`KnowledgeOS = Probability Distribution` (246) — **no `(Ω,ℱ,P)` in 1468 files** ·
`Assurance = f(Irreversibility,Impact,Criticality)` (092) · `Assurance = Strong BC Candidate` (127) ·
`Knowledge = Structure(X,R,Q,H,…)` (253) — `H ∈ K` contradicted by executed evidence

## 8. Refuted — `REFUTED`
`Σ = {Unknown,Supported,Refuted}` **(my own prior result — cannot express degree)** ·
Q14's `Σ=(A,S,R,V,C)` **(no negative pole — cannot express refutation)** ·
`Assurance = DeterministicAssurance` (230, self-referential) · `Policy = Decision` (207, self-contradictory) ·
`Policy = Allowed` (199, confuses policy with verdict) · `proposition = assertion` ·
version numbers as an **Interval** scale (Q14 §3.5) · `∨` as policy composition ·
`K = Set(Assertion)` alone · gates as an ordered tuple

## 9. Under-specified — `UNDER-SPECIFIED`
the **qualification predicate** (observation → evidence) · the **authority→gate binding** ·
policy **provenance and change-authorisation** · which admissibility law is canonical (4 / 5 / 6 conjuncts) ·
`Knowledge` itself (only `Information ⊇ Knowledge`)

## 10. Impossible to compute — and why
| Item | Reason |
|---|---|
| **Uncertainty** | no probability space anywhere; `str` is **ordinal**, so no measure |
| **Non-identifiability** | a property of the **evidence lattice**, not of `𝒜` or `ℛ` — no candidate can express it |
| **Missingness** (never-asserted vs never-asked) | indistinguishable in every candidate |
| **Semantic policy equality** | quantifies over an unbounded decision domain — **undecidable** |
| `GovernanceValid(K)` from `K` alone | requires History — a **boundary**, not a defect |

## 11. Representation choices, not ontology
Graph vs set-of-pairs for `ℛ` · transitive reduction vs closure for DAG families · content-addressed vs
assigned `id` · storing `Σ` (denormalisation — **and executed to be inferior**: it creates an update anomaly)

## 12. Genuinely normative — `NORMATIVE DECISION REQUIRED`
**None yet reached.** Every candidate has an untried derivation or an untried test. See §27 of the
canonical theory.
