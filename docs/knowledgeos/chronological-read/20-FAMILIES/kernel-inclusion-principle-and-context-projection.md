# kernel-inclusion-principle-and-context-projection

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Kernel != UniversalDomainModel`, `Projection(H,C) -> Context_C`, `SystemContext ⊇ ActorContext`, `kernel: small, stable, authoritative` · **Aliases:** `context projection to an agent`, `what belongs in the kernel`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope OBJECT): Step 162's principle that the KnowledgeOS kernel should contain only concepts requiring true system-wide consistency (Identity, Provenance reference, Lineage reference, Authority reference, Integrity, Version, Correlation, TemporalValidity), explicitly excluding Wisdom/Decision/Inquiry/business-specific Action/domain-specific Evidence or Knowledge unless proven otherwise (Kernel != UniversalDomainModel; every kernel addition increases coupling, so the burden of proof for inclusion is high). Pairs this with a 'context projection' idea: an agent receives Projection(H,C) -> Context_C, a task-relevant slice of the full historical lineage H, under the design principle SystemContext ⊇ ActorContext (the actor need not possess the complete history) and a new invariant that context supplied to an actor must be distinguishable from complete historical state, else the actor may wrongly assume it has been given everything that exists. Reinterprets the Gita 'only Krishna knows' insight architecturally as GlobalHistoricalContext (system of record) vs intentionally incomplete LocalActorContext, restated as 'the system preserves the lineage necessary to reconstruct relevant prior states', not 'the system remembers everything' -- and argues a complete-memory-for-every-agent model would be expensive, hard to govern, unsafe, and hard to invalidate/reason about.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1355 §"The kernel should probably contain only concepts that truly require system-wide consistency: Identity Provenance reference Lineage reference Authority reference Integrity Version Correlation ... Kernel ≠ UniversalDomainModel. ... The kernel should be: small, stable, authoritative. Every concept added to the kernel increases coupling. Therefore the burden of proof for kernel inclusion should be high. ... Wisdom; Decision; Inquiry; Business-specific Action; domain-specific Evidence; domain-specific Knowledge. Those should remain context-owned unless proven otherwise."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S1355 §"The kernel should probably contain only concepts that truly require system-wide consistency: Identity Provenance reference Lineage reference Authority reference Integrity Version Correlation ... Kernel ≠ UniversalDomainModel. ... The kernel should be: small, stable, authoritative. Every concept added to the kernel increases coupling. Therefore the burden of proof for kernel inclusion should be high. ... Wisdom; Decision; Inquiry; Business-specific Action; domain-specific Evidence; domain-specific Knowledge. Those should remain context-owned unless proven otherwise."]
- CANDIDATE-FORMAL-BIRTH: [S1355 §"The system preserves the lineage necessary to reconstruct relevant prior states. ... GlobalHistoricalContext may be available to the system of record while LocalActorContext is intentionally incomplete. Therefore: SystemContext ⊇ ActorContext can be a design principle. ... Projection(H,C) → Context_C. The agent receives a context projection appropriate to its task. ... Context supplied to an actor must be distinguishable from complete historical state. Otherwise the actor may incorrectly assume: 'What I was given is everything that exists.'"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1356. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S1356) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1355, S1356 |
| type_signature | PRESENT | S1355, S1356 |
| invariants | PRESENT | S1355, S1356 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1355, S1356 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1355]` types=[PRINCIPLE, CONCEPT] scope=OBJECT — "Kernel-inclusion principle: the KnowledgeOS kernel should contain only concepts requiring true system-wide consistency (candidates: Identity, Provenance reference, Lineage reference, Authority reference, Integrity, Version, Correlation, TemporalValidity), while domain-specific meaning stays within contexts (Kernel != UniversalDomainModel); the kernel should be small, stable, authoritative, since every addition increases coupling, so the burden of proof for kernel inclusion should be high. Explicitly excludes from the kernel: Wisdom, Decision, Inquiry, business-specific Action, domain-specific Evidence, domain-specific Knowledge -- these remain context-owned unless proven otherwise." (anchor: "The kernel should probably contain only concepts that truly require system-wide consistency: Identity Provenance reference Lineage reference Authority reference Integrity Version Correlation ... Kernel ≠ UniversalDomainModel. ... The kernel should be: small, stable, authoritative. Every concept added to the kernel increases coupling. Therefore the burden of proof for kernel inclusion should be high. ... Wisdom; Decision; Inquiry; Business-specific Action; domain-specific Evidence; domain-specific Knowledge. Those should remain context-owned unless proven otherwise.")
- `[S1355]` types=[FORMALIZATION, EXTENSION, INVARIANT] scope=OBJECT — "Reframes the Gita continuity insight architecturally: not 'the system remembers everything' but 'the system preserves the lineage necessary to reconstruct relevant prior states'; models GlobalHistoricalContext (system of record) vs intentionally incomplete LocalActorContext under the design principle SystemContext ⊇ ActorContext, formalized as a context-projection function Projection(H,C) -> Context_C giving an agent only a task-relevant slice of history. Adds a new invariant: context supplied to an actor must be distinguishable from complete historical state, else the actor may wrongly assume it was given everything that exists. Argues a complete-memory-for-every-agent design would be expensive, hard to govern, potentially unsafe, and hard to invalidate/reason about, so Agent -> RelevantContext is preferred over Agent -> Everything." (anchor: "The system preserves the lineage necessary to reconstruct relevant prior states. ... GlobalHistoricalContext may be available to the system of record while LocalActorContext is intentionally incomplete. Therefore: SystemContext ⊇ ActorContext can be a design principle. ... Projection(H,C) → Context_C. The agent receives a context projection appropriate to its task. ... Context supplied to an actor must be distinguishable from complete historical state. Otherwise the actor may incorrectly assume: 'What I was given is everything that exists.'")
- `[S1356]` types=[PRINCIPLE, FORMALIZATION] scope=THEORY-LEVEL — "Bounded-context principle: a downstream context receives the minimum semantic contract necessary to perform its responsibility, not the full upstream model -- formalized as a projection Projection_GO: GovernanceModel -> OperationalCommand, where Operations receives only ActionID/Target/Parameters/AuthorizationReference/Constraints rather than the entire Governance model. Also gives the safety property that Recommendation->Execution must never occur without passing through required governance/authorization boundaries: 'AI fluency must never substitute for authority.'" (anchor: "A downstream context receives the minimum semantic contract necessary to perform its responsibility. ... Projection_{GO}: GovernanceModel → OperationalCommand. The Operations context receives: ActionID Target Parameters AuthorizationReference Constraints rather than the entire Governance model.")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
