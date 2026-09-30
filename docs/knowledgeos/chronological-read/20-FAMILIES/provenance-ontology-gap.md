# provenance-ontology-gap

**Scope(s):** CROSS-OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0587: [`epistemic-lineage-provenance-candidate` · `provenance-ontology-gap`] — explicit agent-stated uncertainty: 'epistemic-lineage-provenance-candidate' POSSIBLY relates to 'provenance-ontology-gap' (batch B0061). Note: Distinguishes ordinary provenance (where an artifact came from) from epistemic provenance (why a determination exists: source, acquisition, originating evidence, transforming process, accepting evaluator, context); grounded in the memory/source-monitoring 'forgotten evidence' problem (a determination can persist after its original supporting evidence becomes inaccessible); current inaccessibility of evidence must not be equated with it never having supported the determination. Status: [STRONG DERIVATION], belongs close to core theory.
- G0673: [`provenance` · `provenance-ontology-gap`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0006, source S0203) named these as alternative candidates for one piece of evidence. why_uncertain: General cross-system provenance-weakness finding; may relate to the existing 'provenance-ontology-gap' externally-sourced finding or to the corpus's plain 'provenance' object, but neither match is confirmed.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0002, scope CROSS-OBJECT): The externally sourced finding that provenance/assertion ontologies lack a standard and under-model human authority.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0046] §"the assertion/evidence side has NO PROV-equivalent standard; the authors call for one"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0168. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0046 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0168 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0168 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
[S0046] (ANALYSIS): A fetched provenance-ontology survey (23 ontologies including PROV, ECO, SEPIO) finds provenance itself is standardized on PROV but the assertion/evidence side has no PROV-equivalent standard, with the survey's own authors calling for one.

[S0046] (ANALYSIS): The provenance-ontology survey's own literature confirms that governance-over-evidence (validation workflows, curation, human authority roles) is under-modeled in the field, which the document claims is precisely the layer KnowledgeOS occupies.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S0046] types=['ANALYSIS'] scope=CROSS-OBJECT — "A fetched provenance-ontology survey (23 ontologies including PROV, ECO, SEPIO) finds provenance itself is standardized on PROV but the assertion/evidence side has no PROV-equivalent standard, with the survey's own authors calling for one." (anchor: "the assertion/evidence side has NO PROV-equivalent standard; the authors call for one")
- [S0046] types=['ANALYSIS'] scope=THEORY-LEVEL — "The provenance-ontology survey's own literature confirms that governance-over-evidence (validation workflows, curation, human authority roles) is under-modeled in the field, which the document claims is precisely the layer KnowledgeOS occupies." (anchor: "no systematic discussion of validation workflows, curation processes, or human authority roles")
- [S0168] types=['PRINCIPLE', 'WARNING'] scope=OBJECT — "Applies W3C PROV's Entity/Activity/Agent model to argue evidence identity must separate stable logical identity, immutable version, current/historical location, content hash, source system, activity, and responsible agent, warning that path-based identity breaks under file moves/repository restructuring." (anchor: "a physical path, session name, role label, and filename are not durable semantic identities. Provenance should model entities, activities, agents, versions, and derivations.")

## Notes for P3
- No internal tension, evidentiary anomaly, or lifecycle-flag discrepancy observed in this label's own rows beyond what the completeness roll-up above already shows.
