# activate-commissioned-fresh-session-ast019

**Scope(s):** OBJECT · **Row count:** 6 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `AST-019`, `ActivateCommissionedFreshSession`, `activate-commissioned-fresh-session.php`
**Aliases:** "BindRuntimeToRequestedResponsibility"
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0009, scope OBJECT: "Lets a fresh session self-register by binding its runtime-discovered identity to a role/work-item already commissioned by a human business order or an existing AST-018 commission; never self-chooses role/scope/work-item/authority; never writes CONTINUATION; the only allowed write is REGISTER{session=own identity, role=commissioned role}."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0353 §"the ONLY allowed write: REGISTER { session = own runtime identity, role = commissioned role } ↓ governed HANDOFF ↓ human START (G-3)"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0353 §(same anchor)]
- CANDIDATE-OPERATIONAL-BIRTH: [S0353 §(same anchor)]
- CANDIDATE-GOVERNANCE-BIRTH: [S0354 §"VERIFIED ⛔ — NOT VERIFIED independently. The GO-01..GO-25 GREEN is the engineering slice's self-verification; per R-34/EP-02 the producer never accepts its own work."]

## Lifecycle
last_seen: S0411 (2026-08-23). Candidate lifecycle: DORMANT. Evidence: `lifecycle_evidence` records no retracted_by, no superseded_by, and no self-contradiction flag. However, the last row in this family (S0411) records that an independent verification returned a FAIL verdict which PO/ARB accepted as authoritative — a confirmed correctness defect (F-1: `analyze()` returns no fold key, forcing `$owner` to null and mis-deriving REGISTER.predecessor/HANDOFF.from). This FAIL is not reflected in the mechanical `lifecycle_evidence` fields (no `retracted_by`/`superseded_by` entry names it), so the DORMANT classification here is a pure recency heuristic and should not be read as "healthy but idle" — see Notes for P3.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0355 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0353, S0353 |
| type_signature | PRESENT | S0353 |
| invariants | PRESENT | S0353, S0354, S0355 |
| dependencies | PRESENT | S0353, S0353, S0354, S0355 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0354, S0355 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0355 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The one row classified as rationale-evidence (RESTATEMENT/ANALYSIS) explains why AST-019 was built: the exact scenario that motivated its design — a human ordering the "Governance adoption review" with no pre-existing lane for the reviewer, which must not produce "no lane therefore refuse" — became the live blocking case this capability was built to solve [S0355]. The formalization row (S0353) frames AST-019's problem more precisely: given a runtime identity, a commissioned work item/role, a fresh-session declaration, passed eligibility/independence checks and no conflicting assignment, the capability's only legal action is `REGISTER{session=own identity, role=commissioned role}` followed by governed HANDOFF and human START — it never self-chooses role/scope/authority and never writes CONTINUATION [S0353]. This closes the gap where a fresh session with a legitimate commission had no way to bind itself to that commission without either overreaching (self-appointing) or being stuck refusing for lack of an existing lane [S0353, S0355].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S0353] types=[FORMALIZATION, IMPLEMENTATION] scope=OBJECT — "AST-019's constrained contract: given current runtime identity, commissioned work item, commissioned role, a valid fresh-session declaration, passed eligibility/independence checks, and no existing conflicting assignment, the ONLY allowed write is REGISTER{session=own identity, role=commissioned role}, followed by governed HANDOFF and human START; it never writes CONTINUATION, never calls AST-018's appoint, and never writes the raw record directly." Type signature: domain=(runtime identity, commissioned work item, commissioned role, fresh-session declaration=UNRESOLVED, eligibility, no conflict), codomain=REGISTER|refusal(exit 64/65), arity=6, total=TOTAL, deterministic=YES. Dependencies: `unified-runtime-human-binding-invariant`, `next-actor-orchestration-ast018`. Invariant: G-3. (anchor as above; file `...AMENDMENT-001-ActivateCommissionedFreshSession.md`, 2026-08-22)
- [S0353] types=[FORMALIZATION] scope=OBJECT — "The commission-resolution table maps every AST-018 next-actor result to a specific AST-019 behaviour: an authoritative next role must match the requested role exactly; an empty-session-set HUMAN_DECISION_REQUIRED is treated as the first-binding case, requiring the role to be named in the recorded human act; a STOPPED work item always fails closed since the capability never writes CONTINUATION." Dependency: `next-actor-orchestration-ast018`. Carries an embedded experiment: hypothesis "GO-01..GO-25 correctly enforce the constrained self-registration contract without weakening AST-015/016/017/018"; method RED-then-GREEN TDD + source-inspection + regression re-run; result "25/25 passed, 270 assertions; full WorkflowEngine suite 147/1577 green; four canonical assets byte-unchanged vs HEAD"; conclusion "IMPLEMENTED — NOT VERIFIED, NOT ADOPTED, NOT AUTHORIZED". (anchor: "next-actor.result NEXT_ACTOR_REQUIRED → role field = the authoritative next role...")
- [S0354] types=[RESTATEMENT, GOVERNANCE] scope=OBJECT — "The AMENDMENT-001 completion report reiterates the four-state separation specifically for AST-019, explicitly naming the next step as the very capability this slice builds: a fresh eligible governance session binds via AST-019 itself to perform the Governance adoption review." Dependency: `activate-commissioned-fresh-session-ast019` (self-reference). Invariants: R-34, EP-02. (anchor: "VERIFIED ⛔ — NOT VERIFIED independently...")
- [S0355] types=[EXPERIMENTAL-RESULT, VALIDATION] scope=OBJECT — "Live behavior confirms AST-019's read-only check command correctly reports NOT POSSIBLE while the work item is STOPPED, honestly naming the human as next actor rather than attempting or implying any self-continuation." Dependency: `activate-commissioned-fresh-session-ast019` (self-reference). Invariant: Inv E. (anchor: "AST-019 check (read-only) → NOT POSSIBLE — 'The work item is stopped...'")
- [S0355] types=[RESTATEMENT, ANALYSIS] scope=CROSS-OBJECT — "The exact scenario that motivated AST-019's design (a human ordering the Governance adoption review with no pre-existing lane for the reviewer) is now the live case this appointment sets up — showing the newly built capability's success criterion applied to the concrete blocking case that produced it." Also labeled `unified-runtime-human-binding-invariant` (not part of this label's own family). (anchor: "the capability whose success criterion is verbatim this scenario...", 2026-08-23)
- [S0411] types=[VALIDATION, GOVERNANCE] scope=OBJECT — "PO/ARB accepts an independent verification's FAIL verdict on AST-019 as authoritative and final (not reopened, re-run, or rewritten); F-1 (a reachable correctness defect on the production write path) is confirmed independently at source: analyze() at lines 377-385 returns no fold key, so writeActivation() at lines 401-402 forces $owner to null, mis-deriving both REGISTER.predecessor and HANDOFF.from." Also labeled `ast019-repair-001-episode` (not part of this label's own family). (anchor: "The FAIL verdict is ACCEPTED"; file `...AMENDMENT-001-REPAIR-001-AUTHORIZATION.md`, 2026-08-23)

## Notes for P3
**Flag for P3:** the last row in this family (S0411, 2026-08-23) records a PO/ARB-accepted independent FAIL verdict against AST-019, naming a specific confirmed correctness defect (F-1) on the production write path. The mechanical `lifecycle_candidate` heuristic reports this label as DORMANT (not CONTESTED or SOURCE-CLAIMED-RETRACTED) because `lifecycle_evidence.retracted_by`/`superseded_by` are empty — but a FAIL-and-accepted verdict reads, in plain terms, as this specific implementation being found broken and sent for repair, which is a much stronger signal than mere dormancy. P3 should treat this label as "implementation known-defective as of last capture, repair status not evidenced in this family" rather than simply idle. The co-occurring label `ast019-repair-001-episode` (also on S0411) likely carries the repair follow-up and was not merged here per R5/R12.
