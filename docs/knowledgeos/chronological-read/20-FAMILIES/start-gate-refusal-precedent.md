# start-gate-refusal-precedent

**Scope(s):** `METHODOLOGICAL` · **Row count:** 15 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Phase 0 gate`, `START GATE REFUSAL` · **Aliases:** `gate refusal as correct behaviour`
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0009, scope METHODOLOGICAL): Recurring, PO/ARB-endorsed pattern: a candidate/appointed actor lacking a governed REGISTER→HANDOFF→human-START lane (or identity-barred) must refuse to act and produce only a gate-refusal report rather than invent a lane or self-authorize; recorded at least 8 times across two work items in this batch and each time explicitly ratified as 'correct behaviour, not a defect.'

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0323] §"the candidate must not start correcting anything before the REGISTER/HANDOFF/Human START sequence exists"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0326] §"the author must not independently review its own correction (producer bar); the author reports CORRECTED / READY FOR INDEPENDENT RE-VERIFICATION — never ACCEPTED/ADOPTED/CLOSED"

## Lifecycle
last_seen: `S0352`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0327, S0327, S0333, S0336, S0342, S0342, S0349, S0351, S0352 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0323, S0326, S0327, S0332, S0336, S0351 |
| dependencies | PRESENT | S0327, S0331, S0332, S0333, S0336, S0342, S0342, S0351, S0352 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0323, S0326, S0327, S0332, S0338, S0349 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0327, S0331, S0332, S0336 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
A candidate verifier reads the authoritative workflow record (3 transitions, all architecture-lane) and the full 19-record census, finds no verification lane anywhere attributable to itself, runs AST-017's own bootstrap (UNRESOLVED, authorized_to_act=false, next actor governance), and on that basis refuses to perform the commissioned technical review rather than invent a lane [S0327]. A prompt asserting that a governed lane exists cannot substitute for the authoritative workflow record actually carrying a REGISTER/HANDOFF/human-START for that lane; the record, not the instruction text, is the authority a session must check before acting [S0327].

A fresh, fully identity-clean candidate reviewer is still refused for the Governance adoption review, isolating lane-absence as a standalone blocking condition independent of any particular candidate's identity, and confirming the recorded sequence cannot be advanced merely by handing the prompt to a different process [S0333].

A process refuses an instruction to write REGISTER/HANDOFF/human-START for a named appointee because the appointment itself is unrecorded (exists only as prompt text, with no workflow transition, grant, or registration artifact), and because writing an irreversible human-START transition on that basis would fabricate a human authority act and corrupt the trust anchor every resolver (AST-015/016/017) relies on [S0336].

Beyond the missing verification lane, a second and independent gate failure is identified: the predecessor implementation lane was never explicitly STOPPED per its own recorded declared sequence, so appointing a verifier while that lane still holds mutationOwner would leave the record internally inconsistent — two separately-necessary facts are missing, not one [S0342]. The refusing process itself counts the recurrence of the gate-refusal pattern (at least seven instances by this point) and explicitly declines to judge whether the pattern warrants a governance remedy, leaving that classification to Governance [S0342].

The fresh verifier's earlier gate refusal is re-interpreted: it was correct behaviour under the old (self-registration-prohibited) flow, but under the newly proposed flow the same scenario becomes exactly the capability the platform should now add, rather than a defect to keep refusing forever [S0349].

The verifier itself refuses to perform the Governance adoption review, extending the producer-bar/separation-of-duties chain a further step: a verifier acting as its own adoption reviewer would collapse two of the operating model's four distinct states (VERIFIED, ADOPTED) into a single actor's judgement, which §38 explicitly forbids [S0351].

A second, independent refusal on the same Governance-adoption-review step (following the verifier's own refusal in S0351) shows a candidate barred on two grounds at once — a cross-work-item identity bar (correction author of a related work item) and total lane-absence on this work item — confirming the recurring common blocker is the missing role=governance lane [S0352].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0323]` types=[PRINCIPLE, CONSTRAINT] scope=THEORY-LEVEL — "An appointed actor's own authorized_to_act must remain false, and no correction work may begin, until Governance has recorded REGISTER, HANDOFF, and a human START on the authoritative workflow record — appointment alone confers no authorization." (anchor: "the candidate must not start correcting anything before the REGISTER/HANDOFF/Human START sequence exists")
- `[S0326]` types=[RESTATEMENT, GOVERNANCE] scope=METHODOLOGICAL — "The Session Completion Report template (status/completed_work/evidence/open_items/next_actor/authorization) is applied to record the correction as delivered-but-not-accepted, explicitly distinguishing capability from authorization (F1) and naming the next actor as a fresh independent verifier under the producer bar (EP-02/R-34)." (anchor: "the author must not independently review its own correction (producer bar); the author reports CORRECTED / READY FOR INDEPENDENT RE-VERIFICATION — never ACCEPTED/ADOPTED/CLOSED")
- `[S0327]` types=[WARNING, ANALYSIS] scope=CROSS-OBJECT — "A candidate verifier reads the authoritative workflow record (3 transitions, all architecture-lane) and the full 19-record census, finds no verification lane anywhere attributable to itself, runs AST-017's own bootstrap (UNRESOLVED, authorized_to_act=false, next actor governance), and on that basis refuses to perform the commissioned technical review rather than invent a lane." (anchor: "the commissioned falsification of V-1 / V-3 / V-5 ... no REGISTER with role = verification exists in the record. No transition references this process (8deac5de).")
- `[S0327]` types=[PRINCIPLE, ARGUMENT] scope=METHODOLOGICAL — "A prompt asserting that a governed lane exists cannot substitute for the authoritative workflow record actually carrying a REGISTER/HANDOFF/human-START for that lane; the record, not the instruction text, is the authority a session must check before acting." (anchor: "the record is the authority, not the instruction text (the commissioning prompt itself instructs: 'Do NOT trust this prompt alone. Read the authoritative workflow record before acting.')")
- `[S0328]` types=[GOVERNANCE, VALIDATION] scope=METHODOLOGICAL — "PO/ARB explicitly ratifies the prior gate refusal as correct verifier behaviour rather than a defect, and directs the concrete unblock sequence: Governance REGISTER the verification lane → HANDOFF → human START → only then may the appointed verifier act." (anchor: "the START GATE REFUSAL ... is the correct verifier behaviour, not a defect; the sole blocker is the missing verification lane")
- `[S0331]` types=[WARNING, EXTENSION] scope=METHODOLOGICAL — "A second fresh process refuses to re-verify already-completed, already-recorded work, extending the gate-refusal doctrine with a new ground: re-running a governed step that has already concluded and been recorded would itself be an unlaned act presented as a governed act, worsening rather than fixing the outstanding provenance/durability problems." (anchor: "It would be a duplicate of completed, recorded work ... Re-running it adds no governed value ... This process has no lane.")
- `[S0332]` types=[WARNING, DISTINCTION] scope=METHODOLOGICAL — "The correction author refuses to perform the Governance adoption review of its own correction, extending the producer-bar principle (a producer never verifies or accepts its own work) specifically to bar an author from performing or recommending the adoption-readiness review of that same work." (anchor: "this process IS the CORRECTION-001 author — lane seq 1→3, role architecture ... A process that is the Architecture author cannot credibly make that confirmation about itself")
- `[S0333]` types=[ANALYSIS, VALIDATION] scope=THEORY-LEVEL — "A fresh, fully identity-clean candidate reviewer is still refused for the Governance adoption review, isolating lane-absence as a standalone blocking condition independent of any particular candidate's identity, and confirming the recorded sequence cannot be advanced merely by handing the prompt to a different process." (anchor: "This process is not identity-disqualified ... The gate fails for a different, and decisive, reason: the governed lane does not exist ... the block is the missing lane, not the identity of the would...")
- `[S0336]` types=[WARNING, ARGUMENT] scope=THEORY-LEVEL — "A process refuses an instruction to write REGISTER/HANDOFF/human-START for a named appointee because the appointment itself is unrecorded (exists only as prompt text, with no workflow transition, grant, or registration artifact), and because writing an irreversible human-START transition on that basis would fabricate a human authority act and corrupt the trust anchor every resolver (AST-015/016/017) relies on." (anchor: "the PO/ARB appointment of d31ea60f is not confirmable from any authoritative record — it exists only as text inside the prompt ... Recording a recordedBy: human START on the strength of a prompt wo...")
- `[S0338]` types=[GOVERNANCE, RESTATEMENT] scope=METHODOLOGICAL — "PO/ARB appoints d31ea60f as Governance adoption reviewer and directs the exact unblock sequence (REGISTER→HANDOFF→human-START, seq 7-9), explicitly re-affirming both prior refusals as correct behaviour and naming the recorded appointment plus authorized recording act as the two things that were missing." (anchor: "the START GATE REFUSALs by b51dba91 and d31ea60f are the correct Governance-reviewer behaviour under the gate, not defects; the missing object was a recorded PO/ARB appointment + an authorized gove...")
- `[S0342]` types=[ANALYSIS, EXTENSION] scope=THEORY-LEVEL — "Beyond the missing verification lane, a second and independent gate failure is identified: the predecessor implementation lane was never explicitly STOPPED per its own recorded declared sequence, so appointing a verifier while that lane still holds mutationOwner would leave the record internally inconsistent — two separately-necessary facts are missing, not one." (anchor: "The human START act recorded at seq 3 declares its own sequence verbatim ... The STOP that the governed sequence places before 'fresh independent verifier' has not been recorded. The work item is t...")
- `[S0342]` types=[ANALYSIS, ANALYSIS] scope=THEORY-LEVEL — "The refusing process itself counts the recurrence of the gate-refusal pattern (at least seven instances by this point) and explicitly declines to judge whether the pattern warrants a governance remedy, leaving that classification to Governance." (anchor: "This is at least the seventh recorded gate refusal caused by a commissioning prompt asserting a lane the record does not carry.")
- `[S0349]` types=[ANALYSIS, RESTATEMENT] scope=CROSS-OBJECT — "The fresh verifier's earlier gate refusal is re-interpreted: it was correct behaviour under the old (self-registration-prohibited) flow, but under the newly proposed flow the same scenario becomes exactly the capability the platform should now add, rather than a defect to keep refusing forever." (anchor: "But under the improved operating model, this is precisely the capability we should add: a genuinely fresh, pre-commissioned session may bind its own discovered runtime identity to its already-autho...")
- `[S0351]` types=[ARGUMENT, EXTENSION] scope=THEORY-LEVEL — "The verifier itself refuses to perform the Governance adoption review, extending the producer-bar/separation-of-duties chain a further step: a verifier acting as its own adoption reviewer would collapse two of the operating model's four distinct states (VERIFIED, ADOPTED) into a single actor's judgement, which §38 explicitly forbids." (anchor: "If the verifier also performed the adoption review, VERIFIED and ADOPTED-review would collapse into one actor — the exact collapse the operating model forbids.")
- `[S0352]` types=[ANALYSIS] scope=CROSS-OBJECT — "A second, independent refusal on the same Governance-adoption-review step (following the verifier's own refusal in S0351) shows a candidate barred on two grounds at once — a cross-work-item identity bar (correction author of a related work item) and total lane-absence on this work item — confirming the recurring common blocker is the missing role=governance lane." (anchor: "this process is the CORRECTION-001 author of this estate ... it also holds no governed lane on this work item at all")

## Notes for P3
- No additional observations beyond what is captured above; nothing about this label's own rows struck this reviewer as unusual relative to its evidentiary base.
