# step174-snapshot-vs-live-reference-resolved

**Scope(s):** THEORY-LEVEL · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "Consequential decisions require stable epistemic references"; `D=<Conclusion,EvidenceState,Method,Context,Time,Actor,Version>`; `Decision1 --basedOn--> Determination1 --basedOn--> KnowledgeSnapshot_1`
**Aliases:** "the temporal experiment (resolved)"; "versioning is an epistemic fact, not a technical detail"
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Step 174 resolves the live-vs-snapshot open question (raised in Steps 163/165/173) definitively for consequential decisions: a naive design that re-queries getCurrentKnowledge(subject) to redisplay an old decision produces the wrong relation Decision1<-K2 instead of the historically true Decision1<-K1; the correct architecture anchors Decision1--basedOn-->Determination1--basedOn-->KnowledgeSnapshot_1 permanently, requiring either a pinned KnowledgeVersion (e.g. 4711:v3) or an immutable KnowledgeSnapshot_t1 rather than a bare live reference -- 'consequential decisions require stable epistemic references.' States versioning is not merely technical (version=3 in a database) but represents the epistemic fact 'this decision was made against this state of knowledge' -- a domain concept. Refines the Determination tuple to D=<Conclusion,EvidenceState,Method,Context,Time,Actor,Version> (a bare conclusion like 'Non-compliant' is judged weak without Rule_v4 + Evidence_v7 + timestamp + actor), drawing a statistical analogy to a model/dataset-versioned estimate that cannot be honestly reported as generated from today's data. Explicitly clarifies this requires an auditable justification structure (Evidence+Method+DecisionBasis+Provenance), NOT storing an AI's private internal reasoning/chain-of-thought."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1367 §"getCurrentKnowledge(subject) ... produces: Decision_1 ← K_2. But historically: Decision_1 ← K_1. This is wrong. ... Consequential decisions require stable epistemic references. ... Versioning represents an epistemic fact ... That is a domain concept."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1367 §"D = <Conclusion, EvidenceState, Method, Context, Time, Actor, Version>. ... We need an auditable justification structure, not unrestricted model internals."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1367. Candidate lifecycle: DORMANT. Evidence: no retraction, no superseding row, no self-contradiction flag; DORMANT here is a heuristic based on how long ago (by source_id ordering) this label was last touched, not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1367 |
| type_signature | PRESENT | S1367 |
| invariants | PRESENT | S1367 |
| dependencies | PRESENT | S1367, S1367 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (family.rationale_evidence is empty for this label; the CORRECTION/FORMALIZATION rows below carry the substantive content but were not classified into the rationale_evidence bucket).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S1367] types=[CORRECTION, INVARIANT] scope=THEORY-LEVEL — "Definitively resolves the recurring snapshot-vs-live-reference question for consequential decisions: a naive design that re-queries current knowledge to redisplay an old decision produces the historically wrong relation Decision1<-K2 instead of the true Decision1<-K1; the correct architecture permanently anchors Decision1--basedOn-->Determination1--basedOn-->KnowledgeSnapshot_1, via a pinned version (e.g. 4711:v3) or immutable snapshot -- 'consequential decisions require stable epistemic references.' States that versioning is not merely a technical field but represents the epistemic fact that a decision was made against a specific state of knowledge -- a domain concept, not an implementation detail." (anchor: "getCurrentKnowledge(subject) ... produces: Decision_1 ← K_2. But historically: Decision_1 ← K_1. This is wrong. ... Decision_1 --basedOn--> Determination_1 and: Determination_1 --basedOn--> KnowledgeSnapshot_1. ... Consequential decisions require stable epistemic references. ... Versioning represents an epistemic fact: This decision was made against this state of knowledge. That is a domain concept.")
  - Invariant recorded: "consequential decisions require stable (versioned/snapshot) epistemic references."
  - Dependencies: `step165-historical-chain-and-dual-trace-assurance`, `step173-three-zone-strategic-architecture`.
  - Lineage claim: SOURCE-CLAIMED-EXTENSION → "the temporal experiment posed in Step 173" (quote: "Now we reach the difficult part.").
- [S1367] types=[FORMALIZATION, CONSTRAINT] scope=THEORY-LEVEL — "Refines the Determination tuple to D=<Conclusion,EvidenceState,Method,Context,Time,Actor,Version>, arguing a bare conclusion like 'Non-compliant' is too weak without a specific Rule version, Evidence version, timestamp, and actor (drawing a statistical analogy to reporting an estimate without its dataset/model version). Explicitly clarifies this requires an auditable justification structure (Evidence+Method+DecisionBasis+Provenance), NOT storing an AI's private internal reasoning or chain-of-thought." (anchor: "D = <Conclusion, EvidenceState, Method, Context, Time, Actor, Version>. ... A conclusion without method is weak. Consider: 'Non-compliant.' That is not enough. ... this does not mean storing chain-of-thought. ... We need an auditable justification structure, not unrestricted model internals.")
  - Type signature: domain="knowledge, evidence, method, context", codomain="D tuple", arity=7, total=UNSTATED, deterministic=UNSTATED.
  - Dependency: `step161-semantic-contract-set`.

## Notes for P3
Both captured rows share source_id S1367, from `.../20260829-014253_step_174_context-map-and-domain-contract-experiment.md`. `family.files_touching`, however, additionally lists `S1368` even though no row body for S1368 appears in this label's own `rows` list — worth a data-quality check by P3 (my own observation; I have not fabricated any S1368 content since none was provided). The Determination tuple `D=<Conclusion,EvidenceState,Method,Context,Time,Actor,Version>` here looks closely related to the corpus's broader "Determination"/"Evidence" vocabulary (cf. the `determination-tuple-with-lineage` label seen elsewhere in the corpus, outside this batch) — no group_id links them in this label's own metadata, so no relationship is asserted, just flagged for P3's awareness.
