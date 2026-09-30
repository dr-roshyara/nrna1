# abhava-structured-absence-model

**Scope(s):** THEORY-LEVEL · **Row count:** 10 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** H-KOS-Abhava-001, Prāgabhāva / Pradhvaṃsābhāva / Atyantābhāva / Anyonyābhāva · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0006, scope THEORY-LEVEL): S0231's central claim that absence must be a first-class, structured epistemic object (not NULL/false/missing-row), decomposed into four Nyaya absence types (prior/destroyed/absolute-impossible/difference), each carrying identity, locus, relation, temporal scope and provenance; applied across graph, type-system, AI-reasoning, temporal, DDD-boundary and governance-policy lenses.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0231] §"Absence SHALL be represented as a first-class epistemic object, not as missing data or Boolean negation."
- CANDIDATE-CONCEPTUAL-BIRTH: [S0231] §"Every transition creates a different absence."
- CANDIDATE-FORMAL-BIRTH: [S0231] §"Abhāva is not: ¬Knowledge. It is: Knowledge about absence"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0231. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0231 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0231 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0231 |
| dependencies | PRESENT | S0231 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0231 |
| examples | PRESENT | S0231 |
| warnings | PRESENT | S0231 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
[S0231] (EXAMPLE/ANALYSIS): Worked AI-reasoning example: an agent searching for whether an API supports OAuth and finding nothing must not collapse to 'No' but must classify among four non-collapsible outcomes -- Unknown (no documentation found), True absence (architecture explicitly forbids OAuth), Historical absence (OAuth removed in version 3), Future absence (OAuth planned but not implemented) -- via a proposed pipeline Observation -> No evidence found -> Abhava classifier -> Determine absence type -> Reason.

[S0231] (RESTATEMENT/ANALYSIS): States this as 'the biggest lesson from Abhava' and assesses the Abhava research as 'one of the strongest foundations for the Unknown/Uncertainty/Contradiction parts of the KnowledgeOS kernel', explicitly claiming it answers a question the author 'previously had': 'How do we represent what we do not know?' -- with the answer 'Do not represent it as nothing. Represent the structure of the absence.'

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S0231] types=['DEFINITION', 'INVARIANT', 'WARNING'] scope=THEORY-LEVEL — "Critiques the modern default of representing absence as SQL NOT EXISTS / boolean false / null (example: 'Customer has no active contract' -> {"activeContract": false}), arguing this loses the epistemic distinction between never-existed, deleted, exists-elsewhere, forbidden, unknown, and impossible. States invariant H-KOS-Abhava-001 (first stated form)." (anchor: "Absence SHALL be represented as a first-class epistemic object, not as missing data or Boolean negation.")
- [S0231] types=['DISTINCTION', 'FORMALIZATION', 'EXAMPLE'] scope=OBJECT — "Distinguishes four Nyaya absence types with dedicated KnowledgeOS schemas, all illustrating the statement 'The production database does not contain customer X': Pragabhava/prior absence (customer never existed; schema type PRIOR_ABSENCE with entity + valid_until:creation_event); Pradhvamsabhava/destroyed absence (customer existed then deleted; schema type POSTERIOR_ABSENCE with cause:deletion_event, provenance:audit_record); Atyantabhava/absolute absence (an impossible state, e.g. a Kubernetes pod cannot exist on bare metal without the runtime, modeled as a Constraint); Anyonyabhava/difference (e.g. 'A Service is not a Database', modeled as an IdentityBoundary, called 'extremely important for DDD')." (anchor: "Abhāva is not: ¬Knowledge. It is: Knowledge about absence")
- [S0231] types=['FORMALIZATION', 'DISTINCTION', 'WARNING'] scope=OBJECT — "Proposes a KnowledgeObject<T> with a Presence State and an Absence State (reason: NeverCreated/Deleted/Forbidden/Unknown), warning that LLMs confuse 'I found no evidence' with 'Evidence proves non-existence', and that KnowledgeOS must keep Unknown, Absent, and False as three distinct states rather than collapsing them." (anchor: "Unknown ≠ Absent ≠ False")
- [S0231] types=['EXAMPLE', 'ANALYSIS'] scope=OBJECT — "Worked AI-reasoning example: an agent searching for whether an API supports OAuth and finding nothing must not collapse to 'No' but must classify among four non-collapsible outcomes -- Unknown (no documentation found), True absence (architecture explicitly forbids OAuth), Historical absence (OAuth removed in version 3), Future absence (OAuth planned but not implemented) -- via a proposed pipeline Observation -> No evidence found -> Abhava classifier -> Determine absence type -> Reason." (anchor: "Does API support OAuth?")
- [S0231] types=['CONCEPT', 'EXTENSION'] scope=OBJECT — "Applies the absence typology temporally: an entity's lifecycle (Idea -> Implemented -> Deprecated -> Removed) generates a Pragabhava (before creation) and a Pradhvamsa (after destruction) at different points, supporting architecture evolution, ADR history, deprecated APIs, and replaced concepts." (anchor: "Every transition creates a different absence.")
- [S0231] types=['DEFINITION', 'INVARIANT'] scope=OBJECT — "Applies Anyonyabhava (difference) to DDD bounded contexts: 'Identity = what something is; Difference = what something is not' (example: Customer Context NOT Billing Context); derives invariant H-KOS-Boundary-001, the anchor quote." (anchor: "A domain identity SHALL include explicitly preserved non-identities that define its boundaries.")
- [S0231] types=['CONCEPT', 'EXAMPLE'] scope=THEORY-LEVEL — "Reframes governance policy as a forbidden-state absence rather than a boolean flag: 'Production database changes cannot happen without approval' is currently modeled as approval_required:true, but should instead be modeled as an Absence 'UnapprovedProductionChange must not exist'; policy becomes 'Allowed states + Forbidden states'." (anchor: "KnowledgeOS protects: What exists AND What must never exist")
- [S0231] types=['DEFINITION', 'INVARIANT', 'EXTENSION'] scope=THEORY-LEVEL — "Argues absence requires five simultaneous dimensions (absent thing + where absent + under which relation + during which time + why absent), making it 'not a table row' but 'a relationship structure'; the anchor is the resulting structural requirement, framed as strongly supporting an 'earlier hypergraph conclusion' from prior work (unnamed within this file)." (anchor: "KnowledgeOS SHALL represent absence as a graph-native epistemic relation, not as missing records.")
- [S0231] types=['DEFINITION', 'INVARIANT', 'RESTATEMENT'] scope=THEORY-LEVEL — "Restates H-KOS-Abhava-001 in full/expanded form (the anchor), explicitly positioning absence not as a seventh dimension alongside Identity/Evidence/Authority/Context/Temporal/Unknown but as a dual state of the Knowledge State model: Presence OR Structured Absence OR Unknown, with an updated KnowledgeObject state model (Present/Absent[Never existed, Destroyed, Impossible, Different]/Unknown)." (anchor: "KnowledgeOS SHALL preserve both existence and non-existence as structured epistemic objects. Absence SHALL carry identity, context, relation, temporal scope, and provenance. Negation SHALL never be reduced to missing information.")
- [S0231] types=['RESTATEMENT', 'ANALYSIS'] scope=THEORY-LEVEL — "States this as 'the biggest lesson from Abhava' and assesses the Abhava research as 'one of the strongest foundations for the Unknown/Uncertainty/Contradiction parts of the KnowledgeOS kernel', explicitly claiming it answers a question the author 'previously had': 'How do we represent what we do not know?' -- with the answer 'Do not represent it as nothing. Represent the structure of the absence.'" (anchor: "A trustworthy knowledge system must know the shape of ignorance.")

## Notes for P3
- No internal tension, evidentiary anomaly, or lifecycle-flag discrepancy observed in this label's own rows beyond what the completeness roll-up above already shows.
