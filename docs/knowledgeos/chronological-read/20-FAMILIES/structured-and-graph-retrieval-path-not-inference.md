# structured-and-graph-retrieval-path-not-inference

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "Path(G,A,C) ⇏ Infer(A,C,Gamma)" · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope THEORY-LEVEL: "Structured-query precision vs epistemic correctness, and the graph-path-is-not-inference principle with relation-type composition constraints."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2783 §"DatabasePrecision != EpistemicCorrectness. ... Path(G,A,C) ⇏ Infer(A,C,Gamma). ... Supports(A,B) and Supports(B,C) [does not imply] Supports(A,C)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2783. Candidate lifecycle: ACTIVE. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S2783), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2783 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2783] types=[DISTINCTION, PRINCIPLE] scope=THEORY-LEVEL — "20.20-20.24: a structured query (SELECT x such that Status(x)=Established) can be precise but only as correct as the stored status and its defining contract (DatabasePrecision≠EpistemicCorrectness); for a typed knowledge graph G=(V,E), a path A->B->C does not establish A->C (path composition requires explicit semantic rules), formalized as the 'fundamental KnowledgeOS principle' Path(G,A,C)⇏Infer(A,C,Gamma); relation composition must be type-specific -- Supports(A,B)∧Supports(B,C) does not imply Supports(A,C), and DependsOn transitivity holds only if the relation contract defines it; graph traversal answers structural reachability, not epistemic justification, so Traversal≠Inference (a traversal may supply premises for a separately-evaluated inference)." (anchor: "DatabasePrecision != EpistemicCorrectness. ... Path(G,A,C) ⇏ Infer(A,C,Gamma). ... Supports(A,B) and Supports(B,C) [does not imply] Supports(A,C).")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- This label rests on a single captured row — the evidentiary base is thin by construction; P3 should treat any characterization here as provisional pending further corpus evidence, not as a settled account.
