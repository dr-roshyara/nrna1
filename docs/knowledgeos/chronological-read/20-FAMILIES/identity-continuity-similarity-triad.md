# identity-continuity-similarity-triad

**Scope(s):** THEORY-LEVEL · **Row count:** 9 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "Continuity", "Identity", "Similarity" · **Aliases:** "three different questions"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 195's three-way distinction between whether two things are the same entity (Identity), whether their histories connect meaningfully (Continuity), and how much they resemble each other (Similarity), with invariants I_49 (name reuse doesn't establish continuity) and the Continuity=f(IdentityRelation,TransitionLineage,DomainRules,Evidence) formalization."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1412 §"Similarity\not\Rightarrow Identity."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1412 §"Continuity(x,y) = f(IdentityRelation,TransitionLineage,DomainRules,Evidence). It is not simply: x=y."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1412. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S1412), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1412, S1412 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1412, S1412 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1412, S1412, S1412, S1412, S1412, S1412 |
| examples | PRESENT | S1412 |
| warnings | PRESENT | S1412 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1412] types=[INVARIANT, DISTINCTION] scope=OBJECT — "Same software, configuration, purpose, or hostname pattern do not by themselves establish that two systems are the same entity: Similarity does not imply Identity." (anchor: "Similarity\not\Rightarrow Identity.")
- [S1412] types=[DISTINCTION] scope=OBJECT — "When x is retired and y takes over its role, Replaces(y,x) does not imply SameIdentity(x,y); replacement can be modeled as y-replaces->x while x!=y still holds, preserving historical identity." (anchor: "SameIdentity(x,y) from: Replaces(y,x). The latter does not imply the former.")
- [S1412] types=[DEFINITION, DISTINCTION] scope=OBJECT — "Continuity is broader than identity: two non-identical entities (OldSystem replacedBy NewSystem) can have operational Continuity(x,y)=True while Identity(x)!=Identity(y); Similarity, Identity, and Continuity are three separate concepts that should not collapse, ordered with Similarity potentially providing evidence for either Identity or Continuity." (anchor: "Continuity(x,y)=True but: Identity(x)\neq Identity(y).")
- [S1412] types=[PRINCIPLE] scope=THEORY-LEVEL — "Applies the Chapter-4 lens precisely to identity: a current representation may lack the information to reconstruct S_t from S_{t+1}, so continuity across a transition requires preserved lineage, not merely a stable current identity." (anchor: "Continuity requires lineage, not merely current identity.")
- [S1412] types=[DISTINCTION, EXTENSION] scope=THEORY-LEVEL — "Three combinations are all possible and must be distinguished: same identity with different states over time (x_t->x_{t+1}), different identity with a continuity relation (x->y), and different identity with no continuity at all (x not~ y) -- Identity and Lineage are independent dimensions." (anchor: "Identity and: Lineage are independent dimensions.")
- [S1412] types=[WARNING, EXAMPLE] scope=OBJECT — "Warns against a classic historical-corruption source: a name used for entity x in 2025 and reused for a different entity y in 2026 must not be naively merged by a knowledge system, or historical queries become wrong." (anchor: "NameReuse must not imply IdentityContinuity.")
- [S1412] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_49: a reused name/identifier/address/representation must not by itself establish identity continuity." (anchor: "I_49: A reused name, identifier, address, or representation must not by itself establish identity continuity.")
- [S1412] types=[RESTATEMENT] scope=THEORY-LEVEL — "Deepens the Chapter-4 insight once more: if a new state lacks historical identity/lineage context, a current observer cannot determine why the current state exists, so the architecture needs CurrentState plus HistoricalLineage jointly, never current state alone." (anchor: "CurrentState + HistoricalLineage rather than current state alone.")
- [S1412] types=[FORMALIZATION] scope=OBJECT — "Formalizes the identity-continuity equation Continuity(x,y)=f(IdentityRelation,TransitionLineage,DomainRules,Evidence), explicitly more than bare equality x=y; restates the three-question distinction: Identity asks 'is it the same entity?', Continuity asks 'does the history of one connect meaningfully to the other?', Similarity asks 'how much does it resemble the other?' -- three questions that may produce three different answers." (anchor: "Continuity(x,y) = f(IdentityRelation,TransitionLineage,DomainRules,Evidence). It is not simply: x=y.")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- Rows for this label were captured under more than one scope tag (['OBJECT', 'THEORY-LEVEL']) — this may reflect genuine cross-scope relevance (e.g. an OBJECT used at THEORY-LEVEL) rather than a labeling error, but P3 may want to confirm.
