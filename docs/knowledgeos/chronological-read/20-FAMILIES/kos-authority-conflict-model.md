# kos-authority-conflict-model

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Authority1 != Authority2`; `GovernedAuthority > GeneratedContext > AgentMemory` · **Aliases:** DuplicateAuthority finding
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0025, scope OBJECT: "Step 133's DuplicateAuthority/AuthorityConflict finding type (multiple sources disagreeing about which decision is current) and the requirement for an explicitly governed precedence order, not agent judgment."

No `single_candidate_flags` recorded.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1033 §"KnowledgeOS says D42 is current; AGENTS.md says D17 is current; Claude memory says D31 is current."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1033. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is entirely empty. This DORMANT classification is a heuristic based on recency of last use (by source_id), not a confirmed retirement.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1033 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1033 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (`rationale_evidence` is empty; `rationale_truncated_count` is 0).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S1033] types=[COUNTEREXAMPLE, DEFINITION] scope=OBJECT — "Defines the 'DuplicateAuthority' finding (Authority1 ≠ Authority2 for the same semantic fact) as particularly dangerous, worked example with three sources disagreeing about which decision is current, labeled AuthorityConflict — not merely a documentation inconsistency; requires the architecture to define explicit precedence (tentatively GovernedAuthority > GeneratedContext > AgentMemory, but this must be explicitly governed, not left to the agent's judgment about which source 'feels newer')." (anchor: "KnowledgeOS says D42 is current; AGENTS.md says D17 is current; Claude memory says D31 is current.")

## Notes for P3
Single-row label with a clear worked counterexample (three sources disagreeing on current decision) that motivates a tentative precedence ordering (`GovernedAuthority > GeneratedContext > AgentMemory`) explicitly flagged in the source itself as needing to be governed, not agent-decided. This is thematically close to `authority-claims-audit-zero-locatable` (also about governance/authority recording gaps) but the two labels have no group_ids linking them — no identity or relationship should be inferred beyond both concerning authority/governance recording.
