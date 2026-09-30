# identity-merge-governed-transition

**Scope(s):** OBJECT · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** I_50, IdentityGraph G_I, IdentityMerge · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0787** [`identity-merge-governed-transition` · `seam-identity-continuity-012-038-195`] — labels share the notation 'I_50'


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0034`, scope `OBJECT`: Step 195's treatment of identity merge/split/reassignment as a high-impact governed transition (invariant I_50) requiring explicit representation and lineage preservation, plus the separate typed IdentityGraph (sameAs/replaces/represents/hosts/derivedFrom/renamedTo edges).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1412] §"IdentityMerge should be treated as a governed transition. ... Risk(identityMerge) > Risk(attributeUpdate)."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1412] §"G_I=(V,E_I). ... sameAs, replaces, represents, hosts, derivedFrom, renamedTo. ... It is an identity/continuity graph."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1412. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S1412), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1412 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1412 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1412 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1412, S1412 |
| examples | PRESENT | S1412 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S1412] (PRINCIPLE/ARGUMENT) Identity merge is a high-impact, specially-governed transition because a wrong merge can contaminate History, Evidence, Decisions, Ownership, Compliance, and Causality all at once, effectively rewriting the meaning of the knowledge graph -- a much higher risk than a simple wrong attribute update.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1412] types=[PRINCIPLE, ARGUMENT] scope=THEORY-LEVEL — "Identity merge is a high-impact, specially-governed transition because a wrong merge can contaminate History, Evidence, Decisions, Ownership, Compliance, and Causality all at once, effectively rewriting the meaning of the knowledge graph -- a much higher risk than a simple wrong attribute update." (anchor: "IdentityMerge should be treated as a governed transition. ... Risk(identityMerge) > Risk(attributeUpdate).")
- [S1412] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_50, flagged as likely constitutional-level: identity merge, split, and reassignment must be explicitly represented and lineage-preserving." (anchor: "I_50: Identity merge, split, and reassignment must be explicitly represented and lineage-preserving.")
- [S1412] types=[EXAMPLE, PRINCIPLE] scope=OBJECT — "Identity split example: when one entity x is discovered to have actually represented two distinct entities y and z, the correct model is x-resolvedAs->{y,z}, never a silent edit of x, so historical records referencing x remain historically valid references to the former (now-split) representation -- another instance of non-destructive correction." (anchor: "x \xrightarrow{resolvedAs} \{y,z\}. Historical records referring to x remain historically valid as references to the former representation.")
- [S1412] types=[DEFINITION, FORMALIZATION] scope=OBJECT — "Defines a separate typed IdentityGraph G_I=(V,E_I) with edge types sameAs, replaces, represents, hosts, derivedFrom, renamedTo -- distinct from a generic knowledge graph, and itself requiring temporal qualification (e.g. NameAssignment(nexus,x,[t0,t1]) then NameAssignment(nexus,y,[t1,t2])) since Name->Identity is a temporal relation; replacement causality (DecisionToReplace->ReplacementAction) is likewise kept separate, since Replacement itself does not establish sameAs." (anchor: "G_I=(V,E_I). ... sameAs, replaces, represents, hosts, derivedFrom, renamedTo. ... It is an identity/continuity graph.")

## Notes for P3
- No unusual internal tensions or notable evidentiary anomalies observed while compiling this file.
