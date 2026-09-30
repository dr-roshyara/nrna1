# C08 — DDD / domain analysis · v1.1

**Treatment:** F-SPECIFIC first-class lens. Inherits unchanged: P3B §1D ("This looks like an aggregate boundary." is a
DDD HYPOTHESIS; "This is the canonical aggregate boundary." is not allowed), P3B §1E (existing DDD models are
hypotheses and candidates), the repository's DDD governance as a vocabulary only — never as a verdict on F content.

## Questions
Which domain concepts does the file name (entities, value objects, aggregates, domain events, policies, services)?
Which invariants does it state, and who would own them? Which bounded contexts does it imply or blur? Which
ubiquitous-language terms does it introduce, and are they used consistently? Which architecture components (C02
ARCHITECTURE-COMPONENT items) have interfaces, and which are only named?

## Recording
`ANALYSIS.jsonl`, `lens: "DDD"` (C06 §2). A proposed model *for KnowledgeOS* is Level 3 (C10,
`STRUCTURE-CANDIDATE`), never Level 2.
