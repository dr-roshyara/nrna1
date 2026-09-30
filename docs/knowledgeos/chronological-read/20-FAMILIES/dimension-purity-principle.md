# dimension-purity-principle

**Scope(s):** THEORY-LEVEL · **Row count:** 10 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** `Dimension Purity and Relationship Preservation`, `dimension independence`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0006, scope THEORY-LEVEL): The rule that a change in one knowledge dimension (Semantic/Evidence/Authority/Temporal/Lifecycle) must not silently modify another dimension; warns against scalar collapse (e.g. 'Knowledge Quality = 0.87').

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0210 §"KnowledgeOS should not preserve knowledge objects only. It should preserve the relationships, identities, boundaries, and transformations through which knowledge remains trustworthy."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0806 §"Relationship = (Entity_1, RelationshipType, Entity_2)"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0806. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S0806) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0210, S0211, S0213 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0806 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0210, S0211 |
| dependencies | PRESENT | S0213 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0210, S0211, S0216, S0806 |
| examples | PRESENT | S0210, S0211, S0216 |
| warnings | PRESENT | S0210, S0211 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Synthesis of five prior conceptual analyses (Topology, Vani, Zero, Gita, Vedanta Tripuisi) converges on a single theme: KnowledgeOS should preserve relationships/identities/boundaries/transformations, not just knowledge objects. [S0210] Finalized/placed version of S0210's overarching thesis: KnowledgeOS should preserve relationships/identities/boundaries/transformations, not just knowledge objects. [S0211] 'The Most Important Discovery': the accumulated philosophical lenses are not adding new dimensions, they are revealing separation requirements between existing/candidate dimensions -- e.g. a naive Knowledge{content, confidence, score, metadata} object is wrong; the emerging model is Identity+Meaning+Evidence+Authority+Time+Transformation history+Unknown state+Relationships. [S0213]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S0210]` types=[ANALYSIS, PRINCIPLE] scope=THEORY-LEVEL — "Synthesis of five prior conceptual analyses (Topology, Vani, Zero, Gita, Vedanta Tripuisi) converges on a single theme: KnowledgeOS should preserve relationships/identities/boundaries/transformations, not just knowledge objects." (anchor: "KnowledgeOS should not preserve knowledge objects only. It should preserve the relationships, identities, boundaries, and transformations through which knowledge remains trustworthy.")
- `[S0210]` types=[CONSTRAINT, EXAMPLE] scope=THEORY-LEVEL — "Dimension Purity rule: a knowledge state is modelled as (Semantic, Evidence, Authority, Temporal, Lifecycle); a change in one dimension must not silently change another. Allowed example: Evidence improves -> Evidence state changes -> Authority review requested. Forbidden example: Evidence improves -> Authority automatically increases." (anchor: "The critical rule: A change in one knowledge dimension must not silently modify another dimension.")
- `[S0210]` types=[WARNING] scope=THEORY-LEVEL — "Warning against scalar collapse of knowledge dimensions into a single score (e.g. 'Knowledge Quality = 0.87'); instead each dimension (Semantic/Evidence/Authority/Temporal/Lifecycle) should carry its own independent state." (anchor: "Avoid: Knowledge Quality = 0.87 because this collapses dimensions.")
- `[S0211]` types=[ANALYSIS, PRINCIPLE] scope=THEORY-LEVEL — "Finalized/placed version of S0210's overarching thesis: KnowledgeOS should preserve relationships/identities/boundaries/transformations, not just knowledge objects." (anchor: "KnowledgeOS should not preserve knowledge objects only. It should preserve the relationships, identities, boundaries, and transformations through which knowledge remains trustworthy.")
- `[S0211]` types=[CONSTRAINT, EXAMPLE] scope=THEORY-LEVEL — "Dimension Purity rule restated with allowed/forbidden examples (Evidence improves->Authority review requested vs Evidence improves->Authority automatically increases)." (anchor: "The critical rule: A change in one knowledge dimension must not silently modify another dimension.")
- `[S0211]` types=[WARNING] scope=THEORY-LEVEL — "Warning against scalar collapse of knowledge quality restated, each dimension keeps its own state (Semantic: Known, Evidence: Partial, Authority: Unassigned, Temporal: Current, Lifecycle: Draft)." (anchor: "Avoid: Knowledge Quality = 0.87 because this collapses dimensions.")
- `[S0213]` types=[ANALYSIS] scope=THEORY-LEVEL — "'The Most Important Discovery': the accumulated philosophical lenses are not adding new dimensions, they are revealing separation requirements between existing/candidate dimensions -- e.g. a naive Knowledge{content, confidence, score, metadata} object is wrong; the emerging model is Identity+Meaning+Evidence+Authority+Time+Transformation history+Unknown state+Relationships." (anchor: "The philosophical lenses are not producing more dimensions. They are revealing separation requirements.")
- `[S0216]` types=[DISTINCTION, EXAMPLE] scope=THEORY-LEVEL — "AGM's Expansion/Contraction/Revision operations suggest a layer distinct from the existing lifecycle (Created/Reviewed/Approved/Active/Deprecated): Knowledge lifecycle != belief evolution, worked example: evidence added changes belief confidence while authority and lifecycle stay unchanged -- reinforcing dimension purity." (anchor: "Knowledge lifecycle != belief evolution. ... Evidence added -> Belief confidence changes -> Authority remains unchanged -> Lifecycle remains active")
- `[S0806]` types=[CORRECTION, DISTINCTION] scope=OBJECT — "Refines the 'Dimension as semantic axis' definition: examples like Relationship_To_Arjuna, Person, Grandfather are ambiguous — Person may be an Entity Type, Grandfather a value within a relationship classification. Introduces five separated categories (Entity Type, Dimension/Attribute, Relationship Type, Value, Predicate) that must not collapse into each other, illustrated by Person(Bhishma), Role(Bhishma)=Commander, GrandfatherOf(Bhishma,Arjuna) as three different semantic structures; without this the domain model becomes an untyped knowledge graph." (anchor: "Entity Type\nDimension / Attribute\nRelationship Type\nValue\nPredicate")
- `[S0806]` types=[FORMALIZATION, CORRECTION] scope=OBJECT — "Relationship should be formally separated from Dimension: Relationship = (Entity_1, RelationshipType, Entity_2) rather than (Entity_1, Entity_2, Dimension, Value); a relationship may itself carry attributes/dimensions (source, temporal validity, confidence/support, context), which is 'much cleaner from a DDD perspective'." (anchor: "Relationship = (Entity_1, RelationshipType, Entity_2)")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
