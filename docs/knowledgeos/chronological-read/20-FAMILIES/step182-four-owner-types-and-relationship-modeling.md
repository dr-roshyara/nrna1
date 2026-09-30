# step182-four-owner-types-and-relationship-modeling

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Knowledge steward / Domain authority / Decision authority / Operational owner`, `StewardOf(a,Artifact), ResponsibleFor(a,Process), AuthorizedToDecide(a,DecisionType), AccountableFor(a,Outcome)` · **Aliases:** `four kinds of owner; owner-of-what discipline`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 182 identifies four distinct 'owner' concepts that may or may not coincide: Knowledge steward (maintains the artifact), Domain authority (establishes a determination within a domain), Decision authority (makes a binding organizational decision), Operational owner (executes/maintains resulting operational state) -- worked architecture-decision example: Architect prepares analysis != Board decides != implementation team executes != product owner remains accountable, all distinct participants in one lifecycle. Prescribes replacing a generic owner_id field with explicit domain relationships StewardOf(a,KnowledgeArtifact), ResponsibleFor(a,Process), AuthorizedToDecide(a,DecisionType), AccountableFor(a,Outcome) -- classic DDD relationship modeling asking 'owner of what?' rather than treating ownership as one relation.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1375 §"Knowledge steward ... Domain authority ... Decision authority ... Operational owner. These may be: SameActor in simple cases. But they need not be. ... Architect ≠ Board ≠ ImplementationTeam ≠ ProductOwner. ... StewardOf(a,KnowledgeArtifact) ResponsibleFor(a,Process) AuthorizedToDecide(a,DecisionType) AccountableFor(a,Outcome)."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S1375 §"Knowledge steward ... Domain authority ... Decision authority ... Operational owner. These may be: SameActor in simple cases. But they need not be. ... Architect ≠ Board ≠ ImplementationTeam ≠ ProductOwner. ... StewardOf(a,KnowledgeArtifact) ResponsibleFor(a,Process) AuthorizedToDecide(a,DecisionType) AccountableFor(a,Outcome)."]
- CANDIDATE-FORMAL-BIRTH: [S1375 §"Knowledge steward ... Domain authority ... Decision authority ... Operational owner. These may be: SameActor in simple cases. But they need not be. ... Architect ≠ Board ≠ ImplementationTeam ≠ ProductOwner. ... StewardOf(a,KnowledgeArtifact) ResponsibleFor(a,Process) AuthorizedToDecide(a,DecisionType) AccountableFor(a,Outcome)."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1375. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S1375) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1375 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1375 |
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

- `[S1375]` types=[CONCEPT, FORMALIZATION] scope=THEORY-LEVEL — "Identifies four distinct 'owner' concepts -- Knowledge steward, Domain authority, Decision authority, Operational owner -- that may coincide in simple cases but need not (worked architecture-decision example: Architect prepares analysis != Board decides != implementation team executes != product owner remains accountable, all participating in one lifecycle). Prescribes replacing a generic owner_id field with explicit domain relationships StewardOf(a,Artifact), ResponsibleFor(a,Process), AuthorizedToDecide(a,DecisionType), AccountableFor(a,Outcome)." (anchor: "Knowledge steward ... Domain authority ... Decision authority ... Operational owner. These may be: SameActor in simple cases. But they need not be. ... Architect ≠ Board ≠ ImplementationTeam ≠ ProductOwner. ... StewardOf(a,KnowledgeArtifact) ResponsibleFor(a,Process) AuthorizedToDecide(a,DecisionType) AccountableFor(a,Outcome).")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
