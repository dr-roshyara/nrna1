# capability-c19-communication-composition

**Scope(s):** OBJECT · **Row count:** 7 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `C-19` · **Aliases:** `communication composition`
**Candidate group membership (NOT an identity claim):**
- G0026: [`capability-c19-communication-composition` · `governance-communication-candidate`] — explicit agent-stated uncertainty: 'governance-communication-candidate' POSSIBLY relates to 'capability-c19-communication-composition' (batch B0003). Note: An untracked C4 actor/component naming a communication concern inside Governance; disposed of as a 'concurring sketch, not evidence' and explicitly not converted into governed architecture by the six-role adoption act.
- G1124: [`capability-c10-knowledge-distribution` · `capability-c19-communication-composition`] — labels co-occur in the same contribution's labels[] 6 separate times across the corpus
- G1126: [`capability-c14-policy-enforcement` · `capability-c19-communication-composition`] — labels co-occur in the same contribution's labels[] 4 separate times across the corpus
- G1128: [`capability-c19-communication-composition` · `capability-c5-separation-attestation`] — labels co-occur in the same contribution's labels[] 4 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0003, scope OBJECT): Existence=YES, category=stewardship/cross-cutting expression concern, fails the ten-part bounded-context test on six of ten criteria.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0091] §"Four capabilities have NO OWNER: C-5 separation attestation, C-10 knowledge distribution, C-14 policy enforcement, C-19 communication composition. INFERRED: these four, not the six roles, are the substance of ADR-AIP-04."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0102] §"The four are not four candidate contexts. They are one CONTROL LOOP with four distinct capabilities in it. policy/knowledge DEFINED (BC-1) -> C-10 DELIVERS it to the gated session -> C-14 GATES the act -> C-19 EXPRESSES the outcome <- C-5 ATTESTS who acted"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0234. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0091, S0102, S0168, S0234 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0102 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0091 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0168 |
| experiments | PRESENT | S0102 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Headline finding: of a 20-capability inventory (C-1..C-20) derived from evidence of what the platform actually does, four capabilities (C-5 separation attestation, C-10 knowledge distribution, C-14 policy enforcement, C-19 communication composition) have no declared owner -- proposed as the real substance of ADR-AIP-04, in contrast to the six proposed roles [S0091]. 'Communication' as commonly discussed is proposed to be two distinct capabilities: C-10 Knowledge Distribution (a genuine unowned mechanism, evidenced by EKS-01) and C-19 Communication Composition (a stewardship, failing the bounded-context test on language and reason-to-change); a single 'Communication Engineer' role would wrongly bind a mechanism gap and a reporting duty into one actor [S0091]. Structural finding: C-5, C-10, C-14, C-19 are not four independent candidate bounded contexts but four capabilities within one control loop -- BC-1's policy/knowledge is delivered by C-10 to a gated session, C-14 gates the act, C-19 expresses the outcome, and C-5 attests who acted and how separately -- while still having four distinct reasons to change, so the relationship, not a merged boundary, is what explains them [S0102]. C-19 fails a ten-part bounded-context test on six of ten criteria (own language, independent lifecycle, independent authority, authoritative state, distinct reason to change, audience/consent/channel concepts) and is classified a stewardship/cross-cutting expression concern; the one property that could give it authoritative state (delivery-with-acknowledgement) actually belongs to C-10, so realizing C-10 reduces rather than increases the case for a Communication bounded context [S0102]. Challenges creating a Communication bounded context merely because a Communication Engineer role exists; a Communication context is defensible only if messages/audiences/consent/delivery/retention carry independent domain invariants and lifecycle, not merely presentation/rendering [S0168]. Records four capabilities with no bounded-context owner as one control loop (policy/knowledge DEFINED in BC-1 -> C-10 DELIVERS -> C-14 GATES -> C-19 EXPRESSES <- C-5 ATTESTS): C-5 Separation attestation (existence YES, cross-context control-plane assurance capability, NOT a bounded context); C-10 Knowledge distribution (existence NOT YET ESTABLISHED, deferred); C-14 Policy enforcement (existence CONTESTED, deferred); C-19 Communication composition (existence YES, stewardship/cross-cutting expression concern, NOT a bounded context). Flagged as a proposal finding, not adopted architecture; C-10/C-14 existence remain UNDECIDED [S0234].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0091] types=[ANALYSIS] scope=THEORY-LEVEL — "Headline finding: of a 20-capability inventory (C-1..C-20) derived from evidence of what the platform actually does, four capabilities (C-5 separation attestation, C-10 knowledge distribution, C-14 policy enforcement, C-19 communication composition) have no declared owner -- proposed as the real substance of ADR-AIP-04, in contrast to the six proposed roles." (anchor: "Four capabilities have NO OWNER: C-5 separation attestation, C-10 knowledge distribution, C-14 policy enforcement, C-19 communication composition. INFERRED: these four, not the six roles, are the substance of ADR-AIP-04.")
- [S0091] types=[ANALYSIS, DISTINCTION] scope=CROSS-OBJECT — "'Communication' as commonly discussed is proposed to be two distinct capabilities: C-10 Knowledge Distribution (a genuine unowned mechanism, evidenced by EKS-01) and C-19 Communication Composition (a stewardship, failing the bounded-context test on language and reason-to-change); a single 'Communication Engineer' role would wrongly bind a mechanism gap and a reporting duty into one actor." (anchor: "Communication is two capabilities wearing one name. C-10 Knowledge Distribution -- a genuine, evidenced, unowned MECHANISM ... C-19 Communication Composition -- a STEWARDSHIP")
- [S0101] types=[VALIDATION] scope=CROSS-OBJECT — "Independent verification confirms the existence/category split was applied without the forbidden collapse for all four capabilities: C-5 exists (control-plane function), C-10's existence is NOT YET ESTABLISHED (category deferred), C-14's existence is CONTESTED (category deferred), C-19 exists (stewardship/cross-cutting), each argued from positive properties rather than derived from the other axis." (anchor: "C-5: YES existence, control-plane function category. C-10: NOT YET ESTABLISHED, deferred category. C-14: CONTESTED, deferred. C-19: YES, stewardship/cross-cutting category. The forbidden collapse is absent.")
- [S0102] types=[ANALYSIS, FORMALIZATION] scope=CROSS-OBJECT — "Structural finding: C-5, C-10, C-14, C-19 are not four independent candidate bounded contexts but four capabilities within one control loop -- BC-1's policy/knowledge is delivered by C-10 to a gated session, C-14 gates the act, C-19 expresses the outcome, and C-5 attests who acted and how separately -- while still having four distinct reasons to change, so the relationship, not a merged boundary, is what explains them." (anchor: "The four are not four candidate contexts. They are one CONTROL LOOP with four distinct capabilities in it. policy/knowledge DEFINED (BC-1) -> C-10 DELIVERS it to the gated session -> C-14 GATES the act -> C-19 EXPRESSES the outcome <- C-5 ATTESTS who acted")
- [S0102] types=[ANALYSIS, EXPERIMENTAL-RESULT] scope=OBJECT — "C-19 fails a ten-part bounded-context test on six of ten criteria (own language, independent lifecycle, independent authority, authoritative state, distinct reason to change, audience/consent/channel concepts) and is classified a stewardship/cross-cutting expression concern; the one property that could give it authoritative state (delivery-with-acknowledgement) actually belongs to C-10, so realizing C-10 reduces rather than increases the case for a Communication bounded context." (anchor: "fails on 6 of 10 => a STEWARDSHIP / cross-cutting expression concern ... the only state that could make a Communication context real -- delivery-with-acknowledgement -- is C-10's receipt, not C-19's. building C-10 would REDUCE the case for a Communication context.")
- [S0168] types=[ARGUMENT, WARNING] scope=OBJECT — "Challenges creating a Communication bounded context merely because a Communication Engineer role exists; a Communication context is defensible only if messages/audiences/consent/delivery/retention carry independent domain invariants and lifecycle, not merely presentation/rendering." (anchor: "DDD does not define a bounded context by noun category or organizational role ... Communication becomes a bounded context when it has its own domain language ... a reason to evolve separately.")
- [S0234] types=[ANALYSIS] scope=CROSS-OBJECT — "Records four capabilities with no bounded-context owner as one control loop (policy/knowledge DEFINED in BC-1 -> C-10 DELIVERS -> C-14 GATES -> C-19 EXPRESSES <- C-5 ATTESTS): C-5 Separation attestation (existence YES, cross-context control-plane assurance capability, NOT a bounded context); C-10 Knowledge distribution (existence NOT YET ESTABLISHED, deferred); C-14 Policy enforcement (existence CONTESTED, deferred); C-19 Communication composition (existence YES, stewardship/cross-cutting expression concern, NOT a bounded context). Flagged as a proposal finding, not adopted architecture; C-10/C-14 existence remain UNDECIDED." (anchor: "The four unowned capabilities (corrected state)")

## Notes for P3
Carries 4 candidate group membership(s); P3 should prioritize resolving whether these reflect the same underlying object. Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
