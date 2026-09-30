# conservation-function-principle

**Scope(s):** THEORY-LEVEL · **Row count:** 8 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** P(S) provenance conservation, Q(S_t) subseteq Q(S_{t+1}) · **Aliases:** what must survive a transition
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0034`, scope `THEORY-LEVEL`: Step 197's conservation-function formalism distinguishing properties that must be preserved across transitions (provenance, identity, evidence, authority history -- invariants I_55, I_56) from properties (current status) that legitimately change; grounds 'knowledge evolution is not knowledge deletion.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1415] §"Q(S_t) \subseteq Q(S_{t+1}). ... What should be conserved is the history of the transition."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1415] §"\tau=(S_before,S_after,Actor,Authority,Evidence,Rule,Time,Reason)"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1415. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S1415), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1415, S1415, S1415 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1415, S1415, S1415, S1415 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1415, S1415, S1415 |
| examples | PRESENT | S1415 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1415] types=[DEFINITION, DISTINCTION] scope=OBJECT — "Introduces a conservation function Q(S) for properties that must survive a transition (Q(S_t) subseteq Q(S_{t+1})), explicitly distinguishing this from properties that legitimately do NOT persist as active truth (e.g. ClaimStatus Candidate->Confirmed->Refuted, where CurrentStatus changes but HistoricalLineage is conserved)." (anchor: "Q(S_t) \subseteq Q(S_{t+1}). ... What should be conserved is the history of the transition.")
- [S1415] types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Consolidates the transition provenance schema: every transition should answer who/what/why/based-on-what/under-which-rule/with-which-authority/when, formalized as tau=(S_before,S_after,Actor,Authority,Evidence,Rule,Time,Reason)." (anchor: "\tau=(S_before,S_after,Actor,Authority,Evidence,Rule,Time,Reason)")
- [S1415] types=[FORMALIZATION, CONSTRAINT] scope=OBJECT — "Defines conservation of provenance P(S): for domain-required auditability, P(S_t) subseteq P(S_{t+1}); explicitly does not require retaining every technical byte forever, only the semantic provenance the domain actually requires." (anchor: "P(S_t)\subseteq P(S_{t+1}) for information that the domain requires to remain auditable. ... the semantic provenance required by the domain must remain recoverable.")
- [S1415] types=[INVARIANT] scope=OBJECT — "Conservation of identity: an entity's Identity persists through state transitions by default; if identity itself must change, that must be an explicit, separately-represented domain event, not an implicit side effect." (anchor: "StateTransition \not\Rightarrow IdentityTransition. If identity changes, that must itself be an explicit domain event.")
- [S1415] types=[DISTINCTION, INVARIANT] scope=OBJECT — "Conservation of evidence: when a claim's status changes (e.g. to Refuted), the evidence that originally supported it must not disappear -- Evidence(e) and Assessment(c,e) are different objects, so a changed Assessment does not imply changed Evidence." (anchor: "Supports(e,c_1) remains historical truth, while the interpretation of the claim changes. ... Evidence(e) and: Assessment(c,e) are different objects.")
- [S1415] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_55: changing a claim's epistemic status must not silently alter or erase the evidence from which earlier assessments were derived." (anchor: "I_55: Changing the epistemic status of a claim must not silently alter or erase the evidence from which earlier assessments were derived.")
- [S1415] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_56 (conservation of authority history): a revoked authority grant must remain part of the historical record -- Revoked != NeverExisted; revocation changes future validity but preserves the historical existence and scope of the prior authorization." (anchor: "Revoked \neq NeverExisted. ... I_56: Revocation changes future validity but must preserve the historical existence and scope of the prior authorization.")
- [S1415] types=[PRINCIPLE, EXAMPLE] scope=THEORY-LEVEL — "Conservation of causality: a later-refuted causal claim is linked via refutedBy to its refuting assessment rather than erased, preserving the ability to reconstruct how organizational understanding evolved (Supported->Questioned->Refuted->Replaced); framed via the general principle that knowledge evolution is not knowledge deletion." (anchor: "CausalClaim_1 \xrightarrow{refutedBy} CausalAssessment_2. This allows us to reconstruct how organizational understanding evolved. ... Knowledge evolution is not knowledge deletion.")

## Notes for P3
- No unusual internal tensions or notable evidentiary anomalies observed while compiling this file.
