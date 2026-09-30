# step183-typed-lineage-not-generic-links

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** chronological proximity is not causality: t1<t2 does not imply CausalInfluence(t1,t2), semantic lineage, not merely connectivity, typed relations: Supports/Contradicts/DerivedFrom/Corrects/Supersedes/Constrains/Justifies/Authorizes/Implements/Observes · **Aliases:** causality vs chronology; typed relationship requirement
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0033`, scope `THEORY-LEVEL`: Step 183 warns against confusing chronological proximity with causal relevance (an architecture document created one day before a decision does not prove Document->Decision; t1<t2 does not imply CausalInfluence(t1,t2)), formally noting Reconstruct(D,t) = Subgraph(Ancestors_t(D) union {D}) is too naive since not every ancestor is causally relevant. Requires typed semantic relationships rather than a generic 'related_to' link: Supports, Contradicts, DerivedFrom, Corrects, Supersedes, Constrains, Justifies, Authorizes, Implements, Observes -- 'a graph without relationship semantics becomes another document repository with arrows'; the required property is 'semantic lineage, not merely connectivity.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1377] §"t_1<t_2 does not imply: CausalInfluence(t_1,t_2). ... Supports Contradicts DerivedFrom Corrects Supersedes Constrains Justifies Authorizes Implements Observes. ... A graph without relationship semantics becomes another document repository with arrows. Our architecture requires: Semantic lineage, not merely connectivity."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1377] §"t_1<t_2 does not imply: CausalInfluence(t_1,t_2). ... Supports Contradicts DerivedFrom Corrects Supersedes Constrains Justifies Authorizes Implements Observes. ... A graph without relationship semantics becomes another document repository with arrows. Our architecture requires: Semantic lineage, not merely connectivity."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1377. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S1377), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1377 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1377 |
| dependencies | PRESENT | S1377 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1377 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1377] types=[WARNING, FORMALIZATION] scope=THEORY-LEVEL — "Warns chronological proximity is not causality (t1<t2 does not imply CausalInfluence(t1,t2), e.g. a document created a day before a decision does not prove it influenced the decision), noting Reconstruct(D,t)=Subgraph(Ancestors_t(D) union {D}) is too naive since not every ancestor is causally relevant. Requires ten typed semantic relationships (Supports/Contradicts/DerivedFrom/Corrects/Supersedes/Constrains/Justifies/Authorizes/Implements/Observes) instead of a generic 'related_to' link -- 'a graph without relationship semantics becomes another document repository with arrows'; the required property is semantic lineage, not merely connectivity." (anchor: "t_1<t_2 does not imply: CausalInfluence(t_1,t_2). ... Supports Contradicts DerivedFrom Corrects Supersedes Constrains Justifies Authorizes Implements Observes. ... A graph without relationship semantics becomes another document repository with arrows. Our architecture requires: Semantic lineage, not merely connectivity.")

## Notes for P3
- Agent observation: this label has only 1 recorded row(s); evidence base is thin and the classification above should be read as provisional pending further capture.
