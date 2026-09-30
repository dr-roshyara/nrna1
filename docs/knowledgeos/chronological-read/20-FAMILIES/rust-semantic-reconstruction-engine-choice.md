# rust-semantic-reconstruction-engine-choice

**Scope(s):** IMPLEMENTATION · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** EpistemicStatus enum, Rust, UnresolvedReference · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0019 · scope IMPLEMENTATION: The decision to recommend Rust (optionally with Tree-sitter for structural parsing) as the implementation technology for the Semantic Reconstruction/parsing engine, chosen for memory safety, determinism, and the ability to represent a typed semantic structure that preserves unresolved/ambiguous elements explicitly rather than silently guessing; paired with the constraint that an LLM used within this pipeline is an optional analytical provider, never itself a source of truth (LLM != Knowledge).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0773 §"Rust is a very good choice for this purpose... deterministic where possible; structurally explicit; fast; memory-safe...able to preserve uncertainty rather than silently resolve it. C-type parser \neq parser implemented in C."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0773 §"Rust is a very good choice for this purpose... deterministic where possible; structurally explicit; fast; memory-safe...able to preserve uncertainty rather than silently resolve it. C-type parser \neq parser implemented in C."]

## Lifecycle
last_seen: S0773. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (DORMANT) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0773 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0773 |
| dependencies | PRESENT | S0773 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Recommends Rust as the implementation language for the core Semantic Reconstruction/parser engine (over literal C), explicitly separating 'C-type parser' as an architectural/philosophical lens (lexer->parser->AST) from 'implemented in C' as a technology choice; proposes an explicit typed semantic representation (structs for entities/actions/relations/modalities/unresolved elements) and an EpistemicStatus enum (Unknown/Observed/Reported/Inferred/Assumed/Conflicting/Unresolved) so ambiguity is preserved rather than silently guessed away, and suggests Tree-sitter for the structural layer [S0773].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0773] types=[GOVERNANCE, ARGUMENT] scope=OBJECT — "Recommends Rust as the implementation language for the core Semantic Reconstruction/parser engine (over literal C), explicitly separating 'C-type parser' as an architectural/philosophical lens (lexer->parser->AST) from 'implemented in C' as a technology choice; proposes an explicit typed semantic representation (structs for entities/actions/relations/modalities/unresolved elements) and an EpistemicStatus enum (Unknown/Observed/Reported/Inferred/Assumed/Conflicting/Unresolved) so ambiguity is preserved rather than silently guessed away, and suggests Tree-sitter for the structural layer." (anchor: "Rust is a very good choice for this purpose... deterministic where possible; structurally explicit; fast; memory-safe...able to preserve uncertainty rather than silently resolve it. C-type parser \neq parser implemented in C.")
- [S0773] types=[CONSTRAINT, INVARIANT] scope=THEORY-LEVEL — "States that an LLM used as an optional analytical provider within Semantic Reconstruction is not itself a source of truth (LLM != Knowledge); KnowledgeOS must still preserve the distinction between what was parsed, inferred, left unresolved, and evidenced." (anchor: "LLM \neq Knowledge ... An LLM can propose a semantic interpretation, but KnowledgeOS should preserve what was parsed, what was inferred, what remains unresolved, and what evidence supports it.")

## Notes for P3
(none beyond what is captured above)
