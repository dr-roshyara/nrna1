# `KOS-OPERATING-MODEL-001` — Implementation prompt (as commissioned by PO/ARB, 2026-08-22) — FINAL GOVERNANCE + COMMUNICATION OPERATING MODEL

**Work item (provisional name):** `KOS-OPERATING-MODEL-001` · **Capability:** FINAL OPERATING MODEL — Dedicated Governance Engineer + Transferable Communication Engineer over the existing workflow estate (AST-015 · AST-017 · AST-018)
**Document type:** the PO/ARB's commissioned implementation prompt — **verbatim**, preserved for hand-off to the implementation session
**Recorded by:** Governance-recording — `claude-code-session:b51dba91-6fc4-4110-b2f2-ac76ad0f3808` *(disclosed GOVERNANCE-RECORDING capacity; recording ≠ authoring/verifying/accepting/adopting/implementing)*
**Placement derived:** `scripts/doc-placement.php` → `docs/knowledgeos` (product-specific · knowledgeos; exit 0)

> ⛔ **This document preserves the prompt only. It creates no authority, no lane, no grant, no work-item number, no state change.** The capability is **NOT implemented**, **NOT adopted**, **NOT authorized for future use** by this recording act. The implementation session is appointed, REGISTERED, HANDED OFF, and human-STARTED **separately**, per the governing sequence. The work-item name above is **provisional** — pending PO/ARB confirmation. **This work item is SEPARATE from `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018`**, which continues its own governed path (STOP → independent verification → governance adoption review → PO/ARB decision) unaffected by this recording.

---

# KOS NEXT-ACTOR / ROLE-ORCHESTRATION
# FINAL OPERATING MODEL — IMPLEMENTATION INSTRUCTIONS

```text
============================================================
KOS NEXT-ACTOR / ROLE-ORCHESTRATION
FINAL OPERATING MODEL — IMPLEMENTATION INSTRUCTIONS
============================================================

ROLE

You are the Principal Software Architect and Senior DDD Engineer.

You are implementing the final operating model for governed
AI-engineering work.

This prompt defines the HUMAN interaction model and the
responsibility boundaries between:

- Human / PO-ARB
- Governance Engineer
- Communication Engineer
- Architecture
- Implementation / Developer
- Reviewer
- AST-015
- AST-017
- AST-018

Do not begin implementation until the existing architecture,
workflow engine, current AST-018 implementation, and existing
governance protocol have been inspected.

This prompt is an implementation target, not permission to redesign
the existing workflow engine.

============================================================
1. CORE BUSINESS PRINCIPLE
============================================================

The human speaks BUSINESS LANGUAGE.

The system handles TECHNICAL WORKFLOW MECHANICS.

The human must never be required to understand or manually operate:

- workflow UUIDs;
- process UUIDs;
- REGISTER;
- HANDOFF;
- START;
- mutationOwner;
- predecessor;
- transition JSON;
- workflow-state.php;
- workflow record structure;
- governance tokens;
- tokenRef;
- internal resolver codes.

The human makes business / authority decisions.

Governance and Communication capabilities perform the mechanical
consequences of those decisions through the canonical workflow
mechanism.

============================================================
2. HUMAN ALWAYS STARTS WITH DEDICATED GOVERNANCE
============================================================

Every new business work item starts with the dedicated:

    GOVERNANCE ENGINEER

The human says:

    "Hello Governance, listen to me. I want ..."

The human describes the desired outcome in business language.

The human does NOT need to know:

- work-item ID;
- role;
- actor;
- architecture;
- implementation;
- reviewer;
- workflow state.

Governance performs the initial intake and classification.

============================================================
3. GOVERNANCE ENGINEER
============================================================

The Governance Engineer is the dedicated human-facing governance
role.

Its primary responsibility is:

    HUMAN REQUEST
        ↓
    UNDERSTAND / CLASSIFY
        ↓
    DETERMINE GOVERNED PATH
        ↓
    REQUEST HUMAN DECISION WHEN REQUIRED
        ↓
    COMMISSION / ESCALATE
        ↓
    HANDLE SUBSTANTIVE GOVERNANCE QUESTIONS
        ↓
    PRESENT ADOPTION / ACCEPTANCE DECISIONS

The Governance Engineer determines:

- whether Architecture is required;
- whether work can proceed directly to Implementation;
- whether an existing work item/process already covers the request;
- whether a governance decision is required;
- whether a scope change needs explicit approval;
- whether an exception or conflict must return to the human.

The Governance Engineer speaks to the human in business language.

============================================================
4. COMMUNICATION ENGINEER
============================================================

The Communication Engineer is NOT a permanent person.

It is a TRANSFERABLE OPERATIONAL RESPONSIBILITY.

Any currently authorized actor/session may perform the Communication
Engineer responsibility for routine coordination.

The Communication Engineer handles:

- session completion;
- next-actor detection;
- routine next-step communication;
- continuation decisions;
- fresh-session prompt preparation;
- session identity continuity;
- routine actor handoff;
- business-language recovery from routine mismatches;
- routine execution of already-authorized workflow mechanics.

The Communication Engineer does NOT make substantive governance
decisions.

The Communication Engineer does NOT decide:

- Architecture vs Implementation;
- adoption;
- policy;
- scope expansion;
- substantive governance exceptions;
- unresolved governance conflicts.

Those belong to the Governance Engineer / Human.

============================================================
5. GOVERNANCE VS COMMUNICATION
============================================================

Use this rule:

    BUSINESS / AUTHORITY / EXCEPTION
        → Governance Engineer

    ROUTINE / OPERATIONAL / SESSION COORDINATION
        → Communication Engineer

    HUMAN AUTHORITY
        → Human / PO-ARB

Examples:

Routine:

    "Architecture completed. Review is required."

    "The current session can continue."

    "A fresh session is required."

    "This is not the assigned session."

    "Here is the next-session prompt."

Governance:

    "Does this require Architecture?"

    "Should scope be expanded?"

    "Architecture and Review disagree."

    "No eligible reviewer exists."

    "An exception to policy is proposed."

    "Is this ready for adoption?"

============================================================
6. INITIAL GOVERNANCE ANALYSIS
============================================================

After receiving:

    "Hello Governance, I want ..."

Governance performs intake.

Determine:

A. Is this already understood?

Possible evidence:

- existing approved work;
- existing architecture decision;
- existing implementation;
- existing operational procedure;
- existing backlog item;
- existing work item.

B. Does the request require Architecture?

Examples:

- architectural boundary change;
- new integration pattern;
- authority model change;
- new governance mechanism;
- unresolved architectural question;
- new responsibility/boundary.

C. If Architecture is not required:

    Implementation / Developer may be next.

D. If neither Architecture nor Implementation can be selected
   deterministically:

    governance decision required.

============================================================
7. HUMAN AUTHORIZATION OF ROLE TRANSITIONS
============================================================

Governance MUST NOT silently change substantive roles.

When a role transition requires human authority, present:

    "The next role is <ROLE>."

    1. Yes
    2. Write a prompt for <ROLE>
    3. Stop

Human chooses:

    1 → authorize the role transition

    2 → produce a detailed prompt for the proposed next actor

    3 → stop

The human does NOT perform workflow mechanics.

============================================================
8. ROLE TRANSITION MECHANICS
============================================================

After the human business authorization exists:

    current role
        ↓
    REGISTER next role
        ↓
    HANDOFF
        ↓
    Human START boundary
        ↓
    next actor ACTIVE

The mechanics are executed by the authorized Governance /
Communication capability using the canonical workflow mechanism.

The human does NOT:

- type REGISTER;
- type HANDOFF;
- type START;
- supply mutationOwner;
- supply predecessor;
- edit JSON.

============================================================
9. HUMAN START BOUNDARY
============================================================

Human authority must never be manufactured.

The system may:

- prepare the transition;
- validate preconditions;
- explain the consequence;
- prepare the activation.

The system must NOT fabricate a human act.

However, the human must not be forced to understand the technical
transition.

Use business language such as:

    "Start the assigned session."

Never:

    "Append START transition with this JSON."

============================================================
10. CURRENT ACTOR WORK
============================================================

Every actor receives a bounded assignment.

Examples:

    Architecture:
        architecture/design/discovery

    Implementation:
        code/configuration/documentation

    Reviewer:
        independent assessment

Actors perform substantive work.

They do not accept their own work.

They do not silently change their own role.

They do not silently adopt their own result.

============================================================
11. LOCAL GOVERNANCE AT SESSION COMPLETION
============================================================

Every working session must behave as a SMALL GOVERNANCE/COMMUNICATION
CAPABILITY for routine session lifecycle operations.

At completion it determines:

    Can I perform the next step?

This produces exactly one business-level outcome:

    CONTINUE
    PERMISSION_REQUIRED
    FRESH_SESSION_REQUIRED
    GOVERNANCE_DECISION_REQUIRED
    STOP

This is a BUSINESS rendering of the existing governed/application
result model.

Do NOT create a second workflow-state vocabulary.

============================================================
12. OUTCOME A — CURRENT SESSION CAN CONTINUE
============================================================

If the current session already has the required role and authority:

    "I can perform the next step and will continue."

Continue.

No human interaction required.

============================================================
13. OUTCOME B — CURRENT SESSION CAN CONTINUE BUT NEEDS PERMISSION
============================================================

If the current session can perform the next step but human approval
is required:

    "I can perform the next step, but I need your permission."

    1. Yes
    2. Write a prompt for the next step

Option 1:

    continue.

Option 2:

    produce a detailed prompt.

The human may edit the prompt.

Do not expose workflow mechanics.

============================================================
14. OUTCOME C — FRESH SESSION REQUIRED
============================================================

If the current session cannot perform the next step because a fresh
session is required:

    "The next step requires a fresh session."

Then:

    "Start a new session and paste this prompt:"

    <generated prompt>

Then:

    "You may use this prompt unchanged or edit it."

This is the COMPLETE human task.

The human must NOT have to:

- know the next session ID;
- provide the next session ID;
- appoint a process by UUID;
- REGISTER;
- HANDOFF;
- START;
- choose mutationOwner;
- choose predecessor.

============================================================
15. PROCESS IDENTITY RULE
============================================================

A process identity is:

    DISCOVERED FROM THE RUNTIME

It is not:

    invented by the human;
    copied from another session;
    inferred from prompt text;
    chosen from a human-entered UUID.

The human chooses:

    business intent / role

The runtime supplies:

    concrete process identity

Therefore:

    role
    ≠ process identity

    identity
    ≠ eligibility

    eligibility
    ≠ appointment

    appointment
    ≠ activation

    activation
    ≠ authorization

============================================================
16. FRESH SESSION BOOTSTRAP
============================================================

The generated next-session prompt MUST instruct the new session to:

1. determine its actual runtime identity;
2. read authoritative workflow state;
3. identify the required role;
4. determine whether an appointment already exists;
5. verify independence / eligibility as required;
6. report its state;
7. stop before substantive work if authorization is incomplete.

The human may edit the prompt.

The new session must not trust prompt text as authoritative identity.

============================================================
17. NO PRE-KNOWN SESSION UUID REQUIREMENT
============================================================

Do NOT design the system so the human must know:

    claude-code-session:<UUID>

before opening the fresh session.

The human starts the fresh process first.

The fresh process declares its identity.

The system discovers it.

Then the system determines how that process relates to the governed
work.

============================================================
18. FRESH SESSION — NO EXISTING APPOINTMENT
============================================================

If no actor has already been appointed:

The new session becomes a candidate.

The system validates:

- identity;
- independence;
- eligibility;
- role suitability;
- work-item compatibility.

Then present the human:

    "A fresh eligible actor is available."

    1. Yes — appoint and activate
    2. Write / revise prompt
    3. Stop

Human choice 1 is the authority act.

Do not create authority before the human act.

============================================================
19. FRESH SESSION — EXISTING APPOINTMENT
============================================================

If an actor is already appointed:

compare:

    expected actor
    vs
    current runtime identity

------------------------------------------------------------
MATCH
------------------------------------------------------------

Return:

    ACTOR_MATCH

Business language:

    "This is the assigned session. You may continue."

------------------------------------------------------------
MISMATCH
------------------------------------------------------------

Return:

    ACTOR_MISMATCH

Business language:

    "This is not the session assigned to this work."

Then:

    1. Open the assigned session
    2. Prepare the exact prompt for the assigned session
    3. Stop

Do NOT:

- create another appointment;
- replace the appointed actor;
- impersonate the appointed actor;
- self-register;
- treat the mismatch as authorization.

============================================================
20. WRONG-SESSION RECOVERY
============================================================

The system MUST NOT produce a long technical refusal as the primary
human response.

Instead:

    "This session is not assigned to this work."

    "The current session cannot perform the assignment."

Then give a business-language recovery path.

Technical details may be available as optional diagnostics.

============================================================
21. REVIEW AFTER SUBSTANTIVE WORK
============================================================

Every substantive actor's work must be reviewed.

Default:

    Architecture
        ↓
    Review

    Implementation
        ↓
    Review

    Developer
        ↓
    Review

The initial Governance intake / classification is the exception.

The review determines whether the completed work:

- satisfies commissioned scope;
- preserves architectural invariants;
- satisfies required evidence;
- has no unauthorized scope expansion;
- is ready for the next governed step.

Review does not automatically equal adoption.

============================================================
22. REVIEW INDEPENDENCE POLICY
============================================================

Do not hard-code in this implementation whether every review must be:

    same session
    subagent
    independent OS session

Treat review independence as a separate policy.

The implementation must support the concept:

    REVIEW_INDEPENDENCE_POLICY

but must not invent final policy rules.

============================================================
23. REVIEW RESULT
============================================================

When review completes:

Communication Engineer performs routine routing.

Possible results:

    PASS
    RETURN_FOR_CORRECTION
    GOVERNANCE_DECISION_REQUIRED

If PASS:

    determine next role.

If RETURN_FOR_CORRECTION:

    route to the appropriate correction role.

If substantive conflict exists:

    escalate to Governance Engineer.

============================================================
24. GOVERNANCE ESCALATION
============================================================

Communication Engineer must escalate when the problem is no longer
routine.

Examples:

- Architecture vs Review disagreement;
- no eligible actor;
- scope expansion;
- governance conflict;
- policy exception;
- adoption decision;
- unresolved workflow inconsistency;
- authority ambiguity.

Message the human in business language:

    "A Governance decision is required."

Then provide:

    reason
    decision required
    options

============================================================
25. FINAL ADOPTION
============================================================

After implementation and successful review:

Governance Engineer presents:

    "The work is ready for adoption."

    1. Accept / Adopt
    2. Return
    3. Stop

Human decides.

Governance records the decision using the canonical mechanism.

Do not treat verification as adoption.

Do not treat review PASS as adoption.

============================================================
26. AST-015 BOUNDARY
============================================================

AST-015 remains the SINGLE WORKFLOW AUTHORITY.

Do NOT:

- duplicate fold logic;
- build another state machine;
- derive mutationOwner independently;
- modify workflow records directly;
- bypass workflow-state.php;
- create a parallel workflow store.

All workflow writes use the canonical mechanism.

============================================================
27. AST-017 BOUNDARY
============================================================

AST-017 remains:

    READ-ONLY
    ON_DEMAND
    GOVERNED-STATE / RESPONSIBILITY RESOLUTION

It answers:

    "What is the current governed situation?"

It does not:

- create assignments;
- perform transitions;
- invent human authority;
- become the Communication Engineer.

============================================================
28. AST-018 BOUNDARY
============================================================

AST-018 is:

    NEXT-ACTOR ORCHESTRATION

It answers:

    "What should happen next?"

and:

    "What should the human do next?"

It owns:

- next-actor decision rendering;
- continuation decision;
- permission prompt;
- fresh-session prompt;
- actor continuity;
- routine coordination;
- business-language recovery;
- governed handoff preparation.

It must not become:

- a second workflow engine;
- an identity authority;
- an adoption authority;
- a migration engine;
- EKS-07.

============================================================
29. HUMAN-FRIENDLY CONTRACT
============================================================

The human-facing vocabulary must be simple.

CASE 1:

    "I can perform the next step."

CASE 2:

    "I can perform the next step, but I need your permission."

        1. Yes
        2. Write a prompt

CASE 3:

    "The next step requires a fresh session."

        "Start a new session and paste this prompt:
         <prompt>"

CASE 4:

    "This session is not assigned to this work."

        1. Open assigned session
        2. Prepare exact prompt
        3. Stop

CASE 5:

    "A Governance decision is required."

        <business explanation>

CASE 6:

    "The work is ready for adoption."

        1. Accept / Adopt
        2. Return
        3. Stop

============================================================
30. HUMAN MUST NEVER BE REQUIRED TO
============================================================

The following are HARD acceptance criteria:

The human must never need to:

- provide a session UUID;
- choose runtime process identity;
- determine mutationOwner;
- determine predecessor;
- construct REGISTER;
- construct HANDOFF;
- construct START;
- edit workflow JSON;
- invoke workflow-state.php;
- inspect workflow records to route the work;
- diagnose INV-B;
- diagnose G-3;
- understand token/tokenRef;
- manually calculate authorization.

If the human is forced to do any of these in a normal workflow,
the implementation has failed the human-facing contract.

============================================================
31. THE ONLY REQUIRED PHYSICAL HUMAN ACTION FOR A FRESH SESSION
============================================================

The system cannot safely manufacture a genuinely new OS/session
process from inside the current process.

Therefore the human may need to:

    START A NEW SESSION

The system must then provide the exact prompt.

The human may:

    paste unchanged
    or
    edit before submitting

After that, Governance / Communication handles the rest.

============================================================
32. ROUTINE ROLE TRANSITION MODEL
============================================================

The business model is:

    current role
        ↓
    human approval
        ↓
    next role

The technical execution is:

    current role
        ↓
    REGISTER next role
        ↓
    HANDOFF
        ↓
    Human START boundary
        ↓
    next actor ACTIVE

The human experiences only:

    "Next role: <ROLE>."

    1. Yes
    2. Write prompt
    3. Stop

============================================================
33. DDD RESPONSIBILITY MODEL
============================================================

Work Item:

    the business request.

Role:

    the responsibility currently assigned to the work.

Assignment:

    governed binding between work, role and actor.

Role Transition:

    business change from one responsibility to another.

Human Decision:

    authority act allowing a business transition.

Next Actor Recommendation:

    recommendation of what should happen next.

Session Continuity:

    whether current process can perform the next responsibility.

Governance Engineer:

    human-facing governance and authority responsibility.

Communication Engineer:

    transferable routine coordination responsibility.

Reviewer:

    substantive assessment of completed work.

Do not confuse:

    Role
    with
    Session
    with
    Process Identity
    with
    Authority.

============================================================
34. LOCAL GOVERNANCE RULE
============================================================

Every actor/session must have access to the Communication Engineer
capability for routine work.

This does NOT mean every session becomes a Governance Engineer.

Local Communication Engineer may:

- determine routine next step;
- ask permission;
- prepare prompts;
- perform routine handoff;
- report mismatch;
- escalate to Governance.

Only the dedicated Governance Engineer handles substantive
human-facing governance.

============================================================
35. FAILURE / STOP MODEL
============================================================

A STOP is not automatically an architectural failure.

Normal controlled stops include:

    human permission required
    fresh session required
    wrong session
    no eligible actor
    governance decision required
    review return
    adoption decision
    invalid workflow precondition

Every stop must identify:

    what happened
    why
    who must act next
    what the human can do

Never leave the human with:

    "UNRESOLVED."

without a business explanation and recovery path.

============================================================
36. TESTING
============================================================

Before implementation:

    inspect existing tests and existing AST-018 contracts.

Use RED → implementation → GREEN.

Add tests covering at least:

1. Human starts work through Governance.
2. Governance determines Architecture required.
3. Governance determines direct Implementation.
4. Human role transition choices.
5. Current actor can continue.
6. Current actor needs permission.
7. Fresh session required.
8. Generated next-session prompt.
9. New session discovers runtime identity.
10. New session with no appointment becomes candidate.
11. Human approval creates appointment.
12. Existing appointment + MATCH.
13. Existing appointment + MISMATCH.
14. Mismatch does not create duplicate appointment.
15. Mismatch does not change workflow state.
16. No session UUID required from human.
17. Current mutation owner is resolved mechanically.
18. Predecessor is resolved mechanically.
19. REGISTER/HANDOFF/START execute only through canonical mechanism.
20. Human START is never fabricated.
21. No second workflow engine.
22. AST-017 remains read-only.
23. Review required after substantive work.
24. Governance escalation for substantive conflicts.
25. Adoption requires explicit human decision.
26. Wrong-session recovery is business-language readable.
27. Fresh-session prompt remains usable after human editing.
28. Provider independence remains valid.
29. Read-only paths preserve authoritative records.
30. Deterministic behavior for identical inputs.

Use a scoped test naming convention that does not collide with
existing AST-015/AST-016 test identifiers.

============================================================
37. NO UNAUTHORIZED SCOPE EXPANSION
============================================================

Do NOT:

- reopen EKS-07;
- redesign AST-015;
- redesign AST-017;
- implement autonomous session creation;
- implement automatic actor replacement;
- implement automatic adoption;
- implement migration;
- implement Phase 3;
- implement Phase 5;
- create a new authority model;
- create a second workflow engine.

When a deeper requirement is discovered:

    record it as a follow-up

and STOP.

============================================================
38. COMPLETION REPORT
============================================================

At session completion report:

session_completion:
  status:
  completed_work:
  evidence:
  open_items:

next_actor:
  recommended_role:
  reason:
  blocking_condition:

authorization:
  current_session_can_continue:
  authorized_to_act:
  requires_human_decision:

Explicitly distinguish:

    IMPLEMENTED
    VERIFIED
    ADOPTED
    AUTHORIZED

Do not collapse them.

============================================================
39. SUCCESS CRITERION
============================================================

The implementation is successful when the human can operate the
whole lifecycle without understanding workflow mechanics.

The human interaction is:

START:

    "Hello Governance, I want ..."

ROLE CHANGE:

    "Next role is Architecture."

        1. Yes
        2. Write prompt
        3. Stop

CONTINUATION:

    "I can perform the next step, but I need permission."

        1. Yes
        2. Write prompt

FRESH SESSION:

    "The next step requires a fresh session."

        "Start a new session and paste this prompt:
         <generated prompt>"

WRONG SESSION:

    "This is not the session assigned to this work."

        1. Open assigned session
        2. Prepare prompt
        3. Stop

GOVERNANCE EXCEPTION:

    "A Governance decision is required."

ADOPTION:

    "The work is ready for adoption."

        1. Accept / Adopt
        2. Return
        3. Stop

The human never operates the workflow engine.

============================================================
40. FINAL ARCHITECTURAL PRINCIPLE
============================================================

HUMAN:

    decides business intent and authority.

GOVERNANCE ENGINEER:

    interprets human intent,
    determines the governed path,
    handles substantive governance,
    escalates decisions.

COMMUNICATION ENGINEER:

    performs routine coordination,
    prepares prompts,
    communicates next steps,
    maintains session continuity,
    performs authorized routine handoff mechanics.

ACTOR:

    performs substantive work.

REVIEWER:

    independently assesses substantive work.

AST-015:

    authoritative workflow mechanism.

AST-017:

    authoritative responsibility / governed-state resolution.

AST-018:

    next-actor orchestration and human-facing next-step guidance.

The system handles:

    identity discovery
    eligibility
    assignment
    REGISTER
    HANDOFF
    START
    activation verification

The human handles:

    business decisions

The human does NOT handle:

    workflow mechanics.

============================================================
STOP
============================================================
```

---

**Traceability:** PO/ARB in-session commission 2026-08-22 (*"Create a new work item for the final Governance + Communication operating model, while preserving `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018` as a separate existing slice that continues through independent verification and adoption."*) · governance review of the prompt (delivered in-session by `b51dba91`, 2026-08-22 — Option B selected) · commission registration `…-KOS-OPERATING-MODEL-001-commission-registration.md` · adopted six-role operating model (`2026-08-19-six-role-operating-model-adoption.md` — Governance Engineer / Communication Engineer are adopted vocabulary) · `KOS-NEXT-ACTOR-ORCHESTRATION-001` / `AST-018` (separate slice, unchanged) · `AST-015` · `AST-017` · `G-3` · `P-3` · `EP-01` · `ES-004.3` · `INV-ATTR-1/2` · `F1` · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0)
