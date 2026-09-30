# four-session-role-model

**Scope(s):** METHODOLOGICAL · **Row count:** 7 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `S1 Verification`, `S2 Governance`, `S3 Implementation`, `S4 Architecture`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0039: [`four-session-role-model` · `six-role-model`] — explicit agent-stated uncertainty: 'four-session-role-model' POSSIBLY relates to 'six-role-model' (batch B0005). Note: A refinement treating sessions as role-bound workflow assignments (resolved from authoritative state, never from terminal identity) rather than fixed session-to-terminal mappings; conditional architecture activation; 'no role activates itself'.

## Sources (how this label entered the ledger)
- **PROPOSAL**, batch B0005, scope METHODOLOGICAL (relation_to_existing: POSSIBLY:six-role-model): A refinement treating sessions as role-bound workflow assignments (resolved from authoritative state, never from terminal identity) rather than fixed session-to-terminal mappings; conditional architecture activation; 'no role activates itself'.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0165 §"A session assignment is a role-bound assignment in a governed work item ... The physical terminal/process is irrelevant."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0165 §"Governance A — Intake/Authorization ... Governance B — Routing/Registration ... Governance C — Closure"]
- CANDIDATE-FORMAL-BIRTH: [S0165 §"A session can know how to perform something without being authorized to perform it ... S3 role + S3 ACTIVE + mutation ownership + implementation grant + scope match = authorized mutation"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0165 §"Do not ask Session 4 to design this now. We're still inside the OQ whose purpose is to prove the existing mechanism."]

## Lifecycle
last_seen: S0175. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: true (this label's own rows include a CONTRADICTION-typed row or an explicit retraction/supersession lineage claim).

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0165 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0165 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0165 |
| examples | PRESENT | S0165 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0165] types=['PRINCIPLE', 'CORRECTION'] scope=THEORY-LEVEL — "Corrects a 'session = fixed terminal forever' mental model: sessions are role-bound assignments within a governed work item, so one terminal can execute whichever assignment the authoritative workflow record currently authorizes." (anchor: "A session assignment is a role-bound assignment in a governed work item ... The physical terminal/process is irrelevant.")
- [S0165] types=['INVARIANT'] scope=OBJECT — "States 'no role activates itself' as a central architectural principle: every role follows REGISTERED → (predecessor HANDOFF) → CREATED → (human START) → ACTIVE → HANDOFF/STOP/COMPLETE, with no prose or prompt able to substitute for the mechanism." (anchor: "HANDOFF ≠ ACTIVE / HUMAN START + HANDOFF = ACTIVE")
- [S0165] types=['PRINCIPLE', 'EXAMPLE'] scope=OBJECT — "Uses the KOS-OQ-001 precedent (Session 4/Architecture was correctly never activated because Governance determined no architectural work was required) to argue Architecture is conditional, not a fixed sequential step." (anchor: "Architecture is activated by a genuine architectural need, not by a fixed workflow position.")
- [S0165] types=['DISTINCTION', 'FORMALIZATION'] scope=OBJECT — "Separates capability (knowing how) from authority (being permitted), formalizing an authorized-mutation condition as the conjunction of role, active state, mutation ownership, an implementation grant, and scope match." (anchor: "A session can know how to perform something without being authorized to perform it ... S3 role + S3 ACTIVE + mutation ownership + implementation grant + scope match = authorized mutation")
- [S0165] types=['CONCEPT', 'EXTENSION'] scope=OBJECT — "Reframes Governance from a single 'gatekeeper' step into three distinct responsibilities occurring at multiple points in the lifecycle: intake/authorization, routing/registration of the next role, and closure/registration of the final qualification decision." (anchor: "Governance A — Intake/Authorization ... Governance B — Routing/Registration ... Governance C — Closure")
- [S0165] types=['CONSTRAINT', 'GOVERNANCE'] scope=METHODOLOGICAL — "Refuses to let the desired future capability (automatic session/role discovery) be designed inside the currently-running operational qualification, to avoid contaminating the qualification being measured; assigns it to a new, separately-governed work item routed through Governance→Architecture→Implementation→Verification." (anchor: "Do not ask Session 4 to design this now. We're still inside the OQ whose purpose is to prove the existing mechanism.")
- [S0175] types=['CONTRADICTION'] scope=CROSS-OBJECT — "Surfaces T1: the corpus's own role-model proposals and its own external-research critique of role-first modeling coexist unreconciled." (anchor: "Six/four-session role-first modeling (this corpus) vs the same corpus's research warning that role-first can couple the operating model to the domain model — the two positions coexist in the corpus without a ruling.")

## Notes for P3
(none beyond what is noted above)
