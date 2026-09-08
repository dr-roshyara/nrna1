# Phase 5E — Provenance Reconciliation

## KERNEL-OBJ-04 reconciliation record (per the authorization's §12 — evidence-preserving, not a repair)

| Field | Value |
|---|---|
| Phase-5C claim | KERNEL-OBJ-04's "first evidenced occurrence" is seq 0150, source documents 0150 and 0157 |
| Cited source (0150) | `kernel/20260823-110950-kernel-domain-level-brainstorming-admission-hypotheses.md` — DeepSeek's four Admission-Boundary-family hypotheses; contains 0 occurrences of a six-part invariant or "KnowledgeAggregate" |
| Actual source (0156) | `kernel/20260823-114358-deepseek-kernel-twelve-fundamental-questions.md` — raw text (lines 507, 561, 1025): *"The smallest consistency boundary is the KnowledgeAggregate, which contains: Identity, Claim, Evidence References, Justification, Epistemic State, Confidence, History, Relationships"* |
| Falsification source (0157) | `kernel/20260823-114530-aggregate-hypothesis-falsification-atomicity-vs-relatedness.md` — raw text (lines 19–20): *"Does the proposed six-part invariant — Identity + Evidence References + Justification + Epistemic State + Confidence + History — actually require one aggregate?"* — falsifies it |
| Exact nature of the attribution error | Phase 5C's register cites the wrong predecessor document (0150 instead of 0156) for KERNEL-OBJ-04's origin. The six-part list in 0157 is a strict 6-of-8 subset of 0156's own eight-field KnowledgeAggregate (excluding `Claim` and `Relationships`) — this is the object 0157 falsifies |
| Does the object survive independently of the citation error? | **Yes.** KERNEL-OBJ-04 (the six-part aggregate invariant, falsified) is a real, well-evidenced object regardless of which seq is cited as its origin — the *object's own status* (tested, falsified) is unaffected; only its *cited provenance pointer* is wrong |
| Corrected provenance reconstruction | 0156 (KnowledgeAggregate proposed, 8 fields) → 0157 (six-part subset falsified) — a `DEFINES`-then-`FALSIFIES` chain, not involving 0150 at all |
| Impact on any Phase-5C conclusion | **None on KERNEL-OBJ-04's own adjudicated status** (still falsified, still correctly excluded from any surviving-candidate list). **Some impact on provenance-graph accuracy**: any future phase citing "seq 0150" as evidence for the six-part aggregate would be citing the wrong document, and should use this reconciliation record instead |

**Per the authorization: Phase 5C's own `02_kernel-object-register.md` is NOT modified.** This
reconciliation record is the authoritative correction pointer for any future phase.

## Provenance graph extension (new edges from P2/P3, source-supported)

| Source | Target | Relationship | Evidence | Confidence | Level |
|---|---|---|---|---|---|
| 1006 (K-1..K-7 table) | 1008 (consequence matrix) | `DEFINES`→`DERIVES_FROM` (1008 presupposes 1006's own labels) | Both documents share identical K-1..K-7/K-2/K-3/K-6/K-7 labels; 1008 is the later-numbered "D285-7," 1006 the earlier "D285-1," inside the same package | High | 2 |
| 0156 (KnowledgeAggregate) | 0157 (falsification) | `FALSIFIES` | Verbatim six-of-eight-field subset match (§ above) | **High** (raw-source confirmed, both directions) | 1 |
| 2309 (evolving-dimension-space) | 2315 (topological space) | `SUPERSEDES` | 2315's own text explicitly replaces the vector-space/evolving-dimension starting point | High | 2 |
| 2315 (topological space) | 2318 (measure-theoretic space) | `SUPERSEDES` | 2318's own text explicitly grounds KnowledgeOS in measure theory, replacing the topological framing | High | 2 |
| 0327 (Measure Theory v0.1) | 0340 (Relational/Logical Structure as Core) | `SUPERSEDES` | 0340's own text states it replaces the prior measure-first position | High | 2 |
| 1112/1114/1116 (Gita-track programme-scheduling/candidate-carrier documents) | 2260/2293 (Gītā-lens culminating formalizations) | `POSSIBLE_RELATIONSHIP` | Same overall Gītā-lens research arc, not independently confirmed as direct derivation | Low | 3 |

**No edge is added between the `kernel/` family and the `phase_measure_theory/` family** — consistent
with Phase 5B/5C/5D's own repeatedly-restated correct framing ("no cross-family derivation evidenced in
the investigated relationship types," never "the families are independent").

## `kernel/`'s own native provenance finding (seq 0311, already computed by the corpus itself)

Raw-source confirmed: seq 0311 (`kernel/classification/dependency-map.md`) states the `kernel/`
corpus (141 of its own documents, its own scope statement) is **"almost entirely UNLINKED — only 1 of
141 documents cites another corpus document by ID"** (0.7%), with 43 of 141 (30%) carrying informal
backward-reference phrasing and 98 of 141 (70%) carrying no backward reference at all. **This is a
corpus-native finding, not one this reconstruction derived** — reused here as strong, already-computed
evidence supporting this reconstruction's own repeated observation that the Kernel-candidate population
is highly non-integrated (proliferating candidates rarely cite or build on each other explicitly).
