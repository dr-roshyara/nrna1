# `KOS-NEXT-ACTOR-ORCHESTRATION-001` — Implementation prompt (as commissioned by PO/ARB, 2026-08-22)

**Work item (provisional name):** `KOS-NEXT-ACTOR-ORCHESTRATION-001` · **Capability:** NEXT-ACTOR ORCHESTRATION / BUSINESS-LANGUAGE HANDOFF
**Document type:** the PO/ARB's commissioned implementation prompt — **verbatim**, preserved for hand-off to the implementation session
**Recorded by:** Governance-recording — `claude-code-session:b51dba91-6fc4-4110-b2f2-ac76ad0f3808` *(disclosed GOVERNANCE-RECORDING capacity; recording ≠ authoring/verifying/accepting/adopting/implementing)*
**Placement derived:** `scripts/doc-placement.php` → `docs/knowledgeos` (product-specific · knowledgeos; exit 0)

> ⛔ **This document preserves the prompt only. It creates no authority, no lane, no grant, no work-item number, no state change.** The capability is **NOT implemented**, **NOT adopted**, **NOT authorized for future use** by this recording act. The implementation session is appointed, REGISTERED, HANDED OFF, and human-STARTED **separately**, per the governing sequence. The work-item name above is **provisional** — pending PO/ARB confirmation.

---

# GOVERNANCE ARCHITECTURE IMPLEMENTATION
# NEXT-ACTOR ORCHESTRATION / BUSINESS-LANGUAGE HANDOFF

## ROLE

You are the Principal Software Architect and Senior DDD Engineer
implementing a narrowly bounded Governance Architecture capability.

The capability addresses an observed operational problem:

Human users understand business decisions such as:

    "Appoint a fresh independent reviewer."

but are currently forced to understand workflow mechanics such as:

    REGISTER
    HANDOFF
    START
    predecessor
    mutation owner
    transition JSON
    workflow-state.php

That mechanical burden must be removed without weakening
governance or human authority.

## BUSINESS OUTCOME

The system should be able to tell a human in business language:

    "The current work is complete.
     The next required actor is a fresh independent reviewer.
     Should I appoint one now?"

The human should be able to respond:

    1. Yes — handle the governed setup automatically.
    2. Write the reviewer prompt.
    3. Stop.

The human should NOT need to know:

- UUIDs;
- REGISTER syntax;
- HANDOFF syntax;
- predecessor identifiers;
- mutation-owner identifiers;
- workflow-state.php commands;
- transition JSON.

## ARCHITECTURAL PRINCIPLE

Use DDD separation of concerns.

Business authority:

    HUMAN / PO/ARB

Governance domain:

    Governance / assignment / authority concepts

Application orchestration:

    Governance Architecture

Workflow mechanism:

    workflow-state.php / AST-015

Responsibility resolution:

    AST-017

AI actor:

    performs the assigned business/engineering work

The orchestrator translates a human decision into governed
mechanics.

It MUST NOT invent authority.

It MUST NOT become the authority model.

It MUST NOT duplicate AST-015.

It MUST NOT become a second workflow engine.

## DDD BOUNDARY

Discover the current domain language before adding classes.

Do NOT begin by inventing generic classes such as:

    WorkflowManager
    UniversalOrchestrator
    AIController
    SessionManager

unless existing ubiquitous language and responsibilities justify
them.

First identify:

- business concepts;
- commands;
- policies;
- facts;
- decisions;
- application services;
- infrastructure adapters.

Candidate domain language to investigate:

    NextActor
    Appointment
    Reviewer
    Assignment
    AuthorityDecision
    Handoff
    Start
    Eligibility
    Authorization
    Candidate
    ReviewRequest

Do not automatically treat these as domain objects.
Validate them against the existing system.

## CRITICAL DOMAIN DISTINCTIONS

Preserve:

    Responsibility ≠ Authority
    Eligibility ≠ Authorization
    Recommendation ≠ Appointment
    Appointment ≠ Activation
    Activation ≠ Work completion
    Workflow state ≠ Business decision
    Evidence ≠ Authority
    Identity ≠ Authority

The system may recommend:

    "A fresh independent reviewer is required."

That is NOT an appointment.

The human may decide:

    "Yes, appoint one."

That is the authority act.

Governance Architecture then executes the governed mechanical
consequences.

## USE CASE 1 — NEXT-ACTOR DECISION

Implement a business-language use case:

    DetermineNextActorAction

Input:

    authoritative workflow state

Output should be a business-level result such as:

    NEXT_ACTOR_REQUIRED

with:

    role
    reason
    business explanation
    required_human_decision
    available options

Example:

    Next actor:
        Fresh Independent Governance Reviewer

    Reason:
        Technical verification is complete and adoption review
        is the next governed step.

    Human decision required:
        YES

    Options:
        APPOINT
        DRAFT_PROMPT
        STOP

Do NOT expose raw transition mechanics as the primary interface.

## USE CASE 2 — APPOINT REVIEWER

Implement a business-language command:

    "Appoint a fresh independent reviewer."

This command represents a human decision.

The application layer must then:

1. resolve candidate identity;
2. verify independence;
3. verify eligibility;
4. verify no conflicting role;
5. identify current mutation owner;
6. determine required predecessor;
7. prepare the governed transition sequence;
8. execute REGISTER;
9. execute HANDOFF;
10. require/record the human START according to the authority model;
11. verify resulting ACTIVE state;
12. report the result in business language.

## HUMAN AUTHORITY BOUNDARY

The application MUST NOT manufacture the human decision.

The following are NOT valid:

- inferring "Yes" because the user asked for a reviewer prompt;
- treating an AI message as humanAct without an explicit human act;
- auto-appointing because one candidate exists;
- auto-starting a session merely because it was selected.

The human decision remains authoritative.

The orchestrator automates the mechanical consequences only after
the authority act exists.

## OPTION 1 — YES — HANDLE GOVERNED SETUP

When the human selects:

    1. Yes — handle the governed setup automatically.

The system should:

    resolve candidate
    → verify eligibility
    → prepare appointment
    → REGISTER
    → HANDOFF
    → record human START according to G-3
    → verify ACTIVE
    → report success

Do NOT expose shell commands to the human.

Business-language result:

    Reviewer appointed successfully.
    Role: Governance
    Assignment: AST-017 adoption review
    State: ACTIVE
    Next action: Governance reviewer performs the adoption review.

## OPTION 2 — WRITE REVIEWER PROMPT

When the human selects:

    2. Write the reviewer prompt.

Do NOT appoint anyone.

Do NOT register a lane.

Do NOT perform HANDOFF.

Do NOT START.

Instead produce a complete reviewer prompt derived from:

- current governed state;
- actual work item;
- actual scope;
- actual candidate;
- current governance constraints;
- independence requirements.

Then STOP.

This branch is advisory.

## OPTION 3 — STOP

No state change.

No appointment.

No transition.

No authority.

## CANDIDATE SELECTION

Candidate discovery must be deterministic and fail-closed.

0 candidates:

    NO_ELIGIBLE_CANDIDATE

1 candidate:

    candidate can be proposed

>1 candidates:

    AMBIGUOUS

Never silently choose.

A candidate must satisfy:

- independence;
- role eligibility;
- non-participation constraints;
- current workflow constraints.

Candidate identity must come from the runtime mechanism /
authoritative record.

Never manufacture process identity.

## AST-017 BOUNDARY

AST-017 remains:

    READ-ONLY
    ON_DEMAND
    responsibility/resolution capability

Do NOT modify AST-017 to perform workflow transitions.

AST-017 answers:

    "What is the current governed situation?"

The new Governance Architecture capability answers:

    "Given the human decision, what governed action should now be
     executed?"

## AST-015 BOUNDARY

AST-015 remains:

    SINGLE WORKFLOW AUTHORITY

The new capability MUST NOT:

- implement its own fold;
- reconstruct workflow state independently;
- parse raw workflow JSON for state;
- duplicate mutation-owner logic;
- duplicate authorization logic.

Use AST-015 as the application boundary for workflow state and
transition enforcement.

## TRANSITION EXECUTION

The application layer may invoke the existing workflow mechanism.

It MUST NOT bypass it.

All transition writes MUST go through the canonical governed
mechanism.

Do NOT:

- edit JSON directly;
- use Python to bypass the transition engine;
- mutate workflow state directly;
- create a second transition store.

## APPLICATION / DOMAIN / INFRASTRUCTURE

Prefer a clean DDD/hexagonal structure.

Example conceptual separation:

Domain:

    AppointmentDecision
    ReviewerEligibility
    NextActorRecommendation

Application:

    DetermineNextActorAction
    AppointReviewer
    PrepareReviewerPrompt

Ports:

    WorkflowStatePort
    CandidateResolutionPort
    GovernanceTransitionPort
    HumanDecisionPort

Infrastructure:

    AST-015 adapter
    AST-017 adapter
    workflow-state.php adapter
    session/process identity adapter
    prompt renderer

Do NOT create all of these merely because the names are suggested.

Create only the concepts justified by existing architecture and
the actual use cases.

## TDD

Before implementation:

identify the failing properties.

Create RED tests first.

Minimum tests:

1. No human decision → no appointment.
2. Human "Yes" → governed appointment path.
3. Candidate unavailable → no transition.
4. Multiple candidates → AMBIGUOUS, no selection.
5. Ineligible candidate → no transition.
6. Human STOP → no transition.
7. "Write reviewer prompt" → no transition.
8. REGISTER uses current mutation owner.
9. HANDOFF uses correct predecessor/current owner.
10. START requires humanAct.
11. Transition failure → fail closed.
12. Resulting lane must resolve ACTIVE.
13. AST-015 remains the only workflow interpreter.
14. No raw workflow-state parsing outside the bounded interfaces.
15. Business-language result hides implementation mechanics.

## IMPORTANT REGRESSION

Use the current real-world sequence as a regression scenario:

    Architecture completes
       ↓
    Independent verification completes
       ↓
    Governance adoption review required

The system should produce:

    "A fresh independent Governance reviewer is required."

It should then ask:

    "Should I appoint a fresh independent reviewer now?"

Options:

    1. Yes — handle the governed setup automatically.
    2. Write the reviewer prompt.
    3. Stop.

## BUSINESS-LANGUAGE CONTRACT

The human-facing output MUST describe:

- what happened;
- what is needed;
- why it is needed;
- whether a human decision is required;
- available options;
- what will happen after the choice.

Avoid exposing:

    REGISTER
    HANDOFF
    START
    mutationOwner
    transition sequence

unless displaying technical details is explicitly requested.

## GOVERNANCE SAFETY

The capability MUST fail closed.

If:

- identity cannot be resolved;
- candidate eligibility is ambiguous;
- authority is missing;
- workflow state is ambiguous;
- predecessor is unknown;
- human decision is missing;
- transition precondition fails;

then:

    NO TRANSITION

and report:

    What is missing
    Why it matters
    Who must act next
    What the human can choose

## NO EKS-07 REOPEN

This is a narrow operational improvement.

Do NOT implement:

- autonomous multi-agent coordination;
- generic agent marketplace;
- general-purpose actor scheduler;
- autonomous authority transfer;
- identity attestation;
- new bounded contexts;
- full EKS-07.

EKS-07 remains future architecture exploration.

If a deeper requirement is discovered:

    record FOLLOW-UP

Do not implement it in this slice.

## NO MIGRATION IMPACT

Do NOT modify:

- KOS-AIP-GOV-STATE-DURABILITY migration;
- Phase 3;
- Phase 4b;
- Phase 5;
- migration authorization;
- DV-1…DV-7;
- RV-1…RV-7.

## ADOPTION STATUS

This is a SEPARATE operational improvement.

Do not claim:

    adopted
    accepted architecture
    migration requirement

unless an explicit Governance / PO/ARB path already exists for this
new work item.

The current work only implements the capability.

## DELIVERABLES

Produce:

1. Architecture implementation.
2. Domain/application design notes.
3. TDD regression coverage.
4. Business-language interaction contract.
5. Developer guide update.
6. Architecture traceability.
7. Session Completion Report.

## SESSION COMPLETION REPORT

At completion report:

    status
    completed_work
    evidence
    open_items

    next_actor
    reason
    blocking_condition

    current_session_can_continue
    authorized_to_act
    requires_human_decision

The report MUST distinguish:

    implemented

from:

    adopted

and:

    authorized for future use.

## STOP CONDITION

After implementation and verification:

STOP.

Do NOT automatically:

- appoint a reviewer;
- adopt the capability;
- start migration;
- implement EKS-07.

The next actor must be derived from the authoritative workflow
state.

## SUCCESS CRITERION

The implementation is successful when a human can say:

    "Yes."

and the system can safely handle the mechanical governance work
without requiring the human to understand:

    REGISTER
    HANDOFF
    START
    UUIDs
    mutation owners
    transition JSON

while preserving:

    human authority
    AST-015 authority
    AST-017 read-only semantics
    fail-closed behavior
    DDD boundaries
    provenance
    deterministic governance.

---

**Traceability:** PO/ARB in-session framing + commission 2026-08-22 (small DDD-oriented Governance Architecture capability · separate from AST-017 · separate from migration) · design principle: *"The domain decides what authority means; the orchestration layer translates a human business decision into governed workflow mechanics."* · recorded by governance-recording (b51dba91) — recording ≠ implementing/accepting/adopting · placement: `scripts/doc-placement.php` (product-specific · knowledgeos → `docs/knowledgeos`, exit 0) · companion: `…-KOS-NEXT-ACTOR-ORCHESTRATION-001-commission-registration.md`
