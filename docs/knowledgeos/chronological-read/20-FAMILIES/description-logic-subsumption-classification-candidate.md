# description-logic-subsumption-classification-candidate

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Classify(x,C)->subsumption position`, `d1 ⊑ d2` · **Aliases:** `DL subsumption for taxonomy`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0061, scope OBJECT): Candidate relation for hierarchical concept classification (requirement taxonomy, boundary classification) borrowed from description-logic subsumption; explicitly rejects the extraction's stronger claim 'DL = Boundary taxonomy'.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2520] §"d_1\sqsubseteq d_2 as subsumption and identifies the resulting partial-order taxonomy"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2522. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2522 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S2520 |
| invariants | PRESENT | S2522 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S2522 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Cites the handbook's open-world reasoning point (absence of information in an ABox indicates lack of knowledge, not negation, unlike closed database queries) as direct support for Zero != CWA; also cites 'subsumption is the fundamental reasoning task, organizing knowledge into a taxonomy' and 'a concept description can be conceived as a query' to argue requirements are concept descriptions and queries [S2522].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2520] types=[EXTENSION, CORRECTION] scope=OBJECT — "DL subsumption d1 ⊑ d2 could help with requirement taxonomy, classification, bounded vocabulary, concept hierarchies, boundary classifications via a candidate operation Classify(x,C)->subsumption position; explicitly rejects the extraction's stronger claim 'Description logic = Boundary taxonomy'." (anchor: "d_1\sqsubseteq d_2 as subsumption and identifies the resulting partial-order taxonomy")
- [S2522] types=[ARGUMENT, EXAMPLE] scope=CROSS-OBJECT — "Cites the handbook's open-world reasoning point (absence of information in an ABox indicates lack of knowledge, not negation, unlike closed database queries) as direct support for Zero != CWA; also cites 'subsumption is the fundamental reasoning task, organizing knowledge into a taxonomy' and 'a concept description can be conceived as a query' to argue requirements are concept descriptions and queries." (anchor: "The fact that absence of information in an ABox only indicates lack of knowledge... is why queries are more complex than database queries. ... This is why Zero ≠ CWA. ... A concept description can also be conceived as a query ... requirements are just concept descriptions.")

## Notes for P3
Very thin evidentiary base (1-2 rows) — classification here is provisional and should be revisited if more contributions surface.
