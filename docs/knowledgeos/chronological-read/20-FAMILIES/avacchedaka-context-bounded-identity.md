# avacchedaka-context-bounded-identity

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Avacchedaka, Knowledge Identity = (Entity, Property, Context, Relation, Time, Authority) · **Aliases:** the missing type system
**Candidate group membership (NOT an identity claim):**
- G0742: [`avacchedaka-context-bounded-identity` · `navya-nyaya-relational-ontology`] — labels share the notation 'Avacchedaka'
- G0909: [`avacchedaka-context-bounded-identity` · `bounded-context`] — working_label token overlap Jaccard=0.50 (shared tokens: ['bounded', 'context'])
- G0910: [`avacchedaka-context-bounded-identity` · `identity-context-dependence-bounded-contexts`] — working_label token overlap Jaccard=0.50 (shared tokens: ['bounded', 'context', 'identity'])

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0006 · scope OBJECT: S0226's claim that a knowledge relation/property is meaningless without an explicit context/property/relation-mode boundary, formalized as a six-part Knowledge Identity tuple replacing plain Entity+Text.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0226 §"A relation always requires: Entity + Property Boundary + Context Boundary + Relation Mode"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0226 §"A relation always requires: Entity + Property Boundary + Context Boundary + Relation Mode"]
- CANDIDATE-FORMAL-BIRTH: [S0226 §"A relation always requires: Entity + Property Boundary + Context Boundary + Relation Mode"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0226. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (DORMANT) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | PRESENT | S0226 |
| formal_definition | PRESENT | S0226 |
| type_signature | PRESENT | S0226 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0226 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0226] types=[CONCEPT, FORMALIZATION, WARNING] scope=OBJECT — "Avacchedaka (the delimitor/scope of a property in Navya-Nyaya) is presented as 'the Missing Type System': a database-style {entity, attribute, value} triple (e.g. {System, Status, Active}) is insufficient because a bare property like 'Active' is meaningless without a bounding context (software lifecycle vs user account vs legal contract vs infrastructure context). Proposes replacing a flat {"property":"active"} JSON object with one carrying bounded_by:{domain, lifecycle, authority} and a relation field (e.g. "state-of")." (anchor: "A relation always requires: Entity + Property Boundary + Context Boundary + Relation Mode")
- [S0226] types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Formalizes 'Avacchedaka becomes Context-Bounded Identity': Knowledge Identity is defined as the 6-tuple (Entity, Property, Context, Relation, Time, Authority), explicitly contrasted with a naive 'Entity + Text' identity model. The file frames this as directly connecting to 'your earlier discovery: Semantic Context is missing' (a prior finding not identified by name or source within this file)." (anchor: "Knowledge Identity = (Entity, Property, Context, Relation, Time, Authority)")

## Notes for P3
(none beyond what is captured above)
