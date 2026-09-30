# knowledge-atom-formalization

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "KA=(E,D,V,tau,Sigma,Evidence,Provenance)" · **Aliases:** "Knowledge Atom"
**Candidate group membership (NOT an identity claim):**
- G1058: [`knowledge-atom-formalization` · `knowledge-state-formalization`] — working_label token overlap Jaccard=0.50 (shared tokens: ['formalization', 'knowledge'])

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0019, scope OBJECT: "The proposed smallest meaningful unit of KnowledgeOS knowledge (Entity,Dimension,Value,TemporalValidity,EpistemicStatus,Evidence,Provenance) with Compare/Challenge/Update/Preserve operations; superseded within the same file by the proposition-assertion-knowledge-hierarchy, which reclassifies it as an Assertion rather than raw Knowledge."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0775 §"A Knowledge Atom is a claim that an entity has a specific value on a specific dimension, at a specific time, with a specific epistemic status, supported by specific evidence, with a specific provenance. KA = (Entity, Dimension, Value, tau, Sigma, E, P)"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0775 §"A Knowledge Atom is a claim that an entity has a specific value on a specific dimension, at a specific time, with a specific epistemic status, supported by specific evidence, with a specific provenance. KA = (Entity, Dimension, Value, tau, Sigma, E, P)"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0775. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S0775), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0775, S0775 |
| type_signature | PRESENT | S0775 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0775, S0775, S0775 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0775] types=[DEFINITION, FORMALIZATION] scope=OBJECT — "Defines the Knowledge Atom as the smallest unit KnowledgeOS can know/compare/challenge/update/preserve: KA=(Entity,Dimension,Value,TemporalValidity,EpistemicStatus,Evidence,Provenance), with a minimal form KA_min=(E,D,V) and a fully expanded complete form." (anchor: "A Knowledge Atom is a claim that an entity has a specific value on a specific dimension, at a specific time, with a specific epistemic status, supported by specific evidence, with a specific provenance. KA = (Entity, Dimension, Value, tau, Sigma, E, P)")
- [S0775] types=[FORMALIZATION] scope=OBJECT — "Defines four core operations on the Knowledge Atom -- Compare, Challenge, Update, Preserve -- as the operational surface the rest of KnowledgeOS (Zero/Lord/Sarathi/DDD) must act through." (anchor: "Compare(KA1,KA2) -> {Identical, Similar, Different, Conflicting, Unrelated} ... Challenge(KA) -> {Status, Evidence, Counterarguments} ... Update(KA,NewEvidence) -> KA_new ... Preserve(KA) -> KA + Provenance")
- [S0775] types=[VALIDATION] scope=OBJECT — "Formally verifies minimality of the (Entity,Dimension,Value) core against five degenerate combinations (entity alone, dimension alone, value alone, entity+dimension, entity+value), each ruled insufficient, and completeness against observed facts/relationships/inferences/value judgments/hypotheses (questions explicitly excluded as inquiry triggers, not knowledge atoms)." (anchor: "Entity + Dimension + Value | No—needs a value. ... Entity + Dimension + Value | Yes—minimal knowledge. Assessment: Minimal.")

## Notes for P3
- This label participates in 1 candidate group(s) (listed above) — none decided here; each is a candidate relationship for P3 to adjudicate.
- family.files_touching lists ['S2228'] in addition to the source_ids that appear in family.rows — no row from ['S2228'] appears in this label's row list. Noted as a data-completeness oddity for P3, consistent with a pattern seen in other labels processed in this batch.
- single_candidate_flags records an explicit capture-time uncertainty from [S2228] (batch B0054): This file independently proposes a 'Knowledge Atom' KA=(proposition,dimension,value,context,evidence,time,provenance) as the smallest useful unit, without citing the earlier B0019 knowledge-atom-formalization object (KA=(Entity,Dimension,Value,TemporalValidity,EpistemicStatus,Evidence,Provenance)); the concept name and general shape (a 7-tuple smallest knowledge unit) are similar though the specific fields differ, so it is unclear whether this is the same object being independently re-derived or a genuinely distinct proposal.
