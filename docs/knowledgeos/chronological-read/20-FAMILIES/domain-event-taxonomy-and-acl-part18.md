# domain-event-taxonomy-and-acl-part18

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "ACL: M_E -> M_K" · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope THEORY-LEVEL: "Five-way event taxonomy (domain/epistemic/observation/integration/audit) and the requirement that external messages be promoted to knowledge only via an explicit Anti-Corruption Layer contract."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2781 §"ObservationEvent ≢ EpistemicEvent ... IntegrationEvent ≢ DomainEvent ... ExternalMessage ≠ Knowledge ... ACL: M_E -> M_K must preserve the intended semantics"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2781. Candidate lifecycle: ACTIVE. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S2781), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2781 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2781] types=[DISTINCTION, CONSTRAINT] scope=THEORY-LEVEL — "18.14-18.16: distinguishes domain/epistemic/observation/integration/audit events as non-interchangeable (an observation may occur without changing epistemic determination; a technical message is not automatically a domain fact); requires event promotion through an explicit ACL/Contract chain e_ext -> e_domain -> (Evaluation) -> Evidence -> (Epistemic Process) -> KnowledgeState, so ExternalMessage≠Knowledge; formalizes the Anti-Corruption Layer ACL:M_E->M_K as required to explicitly determine identity, meaning, provenance, temporal semantics, authority, uncertainty, transformation and loss, illustrating that silently converting 'prediction' into 'fact' is semantically invalid." (anchor: "ObservationEvent ≢ EpistemicEvent ... IntegrationEvent ≢ DomainEvent ... ExternalMessage ≠ Knowledge ... ACL: M_E -> M_K must preserve the intended semantics")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- This label rests on a single captured row — the evidentiary base is thin by construction; P3 should treat any characterization here as provisional pending further corpus evidence, not as a settled account.
