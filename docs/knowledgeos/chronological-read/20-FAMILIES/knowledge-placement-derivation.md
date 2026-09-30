# knowledge-placement-derivation

**Scope(s):** METHODOLOGICAL · **Row count:** 7 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0002, scope METHODOLOGICAL): The rule and mechanism (doc-placement.php) deriving canonical document placement from subject/product/domain, never from producer/session context.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0042 §"Placement is derived, never chosen. Do not hard-code this path — resolve it."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0047 §"From 2026-08-16 onward, KnowledgeOS work products — commissions, registrations, reviews, verification reports, decision requests — are placed under docs/knowledgeos/reviews/."]

## Lifecycle
last_seen: S0058. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0048 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0042, S0047, S0048 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0058 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The evidence base attributes the following rationale to this object (as recorded, cited to its source):

- **[ANALYSIS]** [S0048]: The ARB elevates the placement-drift incident from documentation hygiene to a product-boundary and knowledge-ownership architecture problem, since the three documentation roots physically represent ownership and product boundaries.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0042] types=[PRINCIPLE] scope=METHODOLOGICAL — "Documentation placement for the KnowledgeOS domain must be derived via the resolver (doc-placement.php --scope=product-specific --domain=knowledgeos), never hard-coded or chosen by convenience." (anchor: "Placement is derived, never chosen. Do not hard-code this path — resolve it.")
- [S0047] types=[GOVERNANCE] scope=METHODOLOGICAL — "From 2026-08-16, all new KnowledgeOS governance work products are placed under docs/knowledgeos/reviews/ rather than the prior de-facto location docs/publicdigit/reviews/." (anchor: "From 2026-08-16 onward, KnowledgeOS work products — commissions, registrations, reviews, verification reports, decision requests — are placed under docs/knowledgeos/reviews/.")
- [S0047] types=[PRINCIPLE] scope=METHODOLOGICAL — "Placement must be derived through the chain subject->product/domain->knowledge space->artifact type->canonical location, never inherited from the current terminal or the previous session's path (consistency-by-repeated-configuration)." (anchor: "Knowledge subject → Product/Domain → Knowledge Space → Artifact type → Canonical location ... not: Current terminal → previous path → same folder again")
- [S0048] types=[ANALYSIS] scope=THEORY-LEVEL — "The ARB elevates the placement-drift incident from documentation hygiene to a product-boundary and knowledge-ownership architecture problem, since the three documentation roots physically represent ownership and product boundaries." (anchor: "This is not merely a folder problem; it is a product-boundary and knowledge-ownership problem.")
- [S0048] types=[PRINCIPLE] scope=THEORY-LEVEL — "A candidate future-EKS architecture requirement is registered: placement derivation must be first-class and machine-readable, keyed on subject/product/domain identity, never on producer or session context." (anchor: "Knowledge placement must be derived from knowledge subject / product / domain identity, never inherited from producer or session context.")
- [S0048] types=[LIMITATION] scope=THEORY-LEVEL — "A maturity table shows placement policy, derivation, and human awareness all exist, but mechanical enforcement is weak or absent, demonstrated by the very session that detected the drift misplacing ~20 documents itself the same day." (anchor: "Mechanical enforcement | ❌ weak/absent — the rule exists, but the workflow does not make it hard enough to violate.")
- [S0058] types=[WARNING] scope=OBJECT — "For a work item whose subject is evidence integrity, its own evidence chain is observed to be split unresolved across two documentation roots (docs/publicdigit/reviews and docs/knowledgeos/reviews), with the placement resolver returning no matching rule for this case; recorded as an observation only, with no files moved." (anchor: "This work item's evidence chain now spans two documentation roots ... php scripts/doc-placement.php returns no matching rule.")

## Notes for P3
Single-candidate attribution uncertainty was flagged during P2a for this label:
  - [S0202] (batch B0006): RAG-governance operational checklist; not clearly the same object as the corpus's own doc-placement/knowledge-distribution mechanisms but adjacent in concern.
