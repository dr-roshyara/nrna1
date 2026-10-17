I recommend making this a standing development strategy for PublicDigit Membership. It formalises the approach we have been applying and ensures that we do not jump directly from a business problem to code without first understanding the domain and requirements.

The key principle is: we decide what the domain needs and why; Claude documents the architecture and requirements; we review the implementation plan before Claude writes code; and TDD provides evidence that the implementation meets the approved design.

## PublicDigit Membership — Future Development Strategy

1\. DDD & Requirements Discussion

Human PO/ARB + Principal Architect and Reviewer

Business need, domain language, rules, invariants, edge cases and requirements

2\. Architecture & Requirements Artefacts

Claude writes or updates the existing authoritative documents

Domain model, contracts, ADRs, requirements, stories and traceability

3\. Implementation Plan

Claude prepares the story-specific plan, tests, sequence and scope boundaries

4\. Human Review & Approval

We review domain correctness, architecture, scope, risks and Definition of Done

No implementation before approval and gate closure

5\. Test-Driven Implementation

Claude follows RED → GREEN → REFACTOR

Failing test, minimal implementation, passing test, regression and evidence

6\. Completion & Traceability Review

Verify acceptance criteria, tests, story status, architecture deviations and remaining work

7\. Controlled Evolution

Update the architecture and backlog when justified by approved decisions or implementation evidence

## 1. Responsibilities

| Participant                           | Responsibility                                                                                                                                 |
| ------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| You — PO / Human ARB                  | Decide business policy and reserved architectural decisions; approve contracts, requirements and implementation plans where required.          |
| Me — Principal Architect and Reviewer | Challenge assumptions, analyse domain semantics, review DDD models, formal logic, invariants, requirements completeness and design trade-offs. |
| Claude — Developer                    | Inspect the repository, maintain artefacts, prepare stories and plans, implement approved scope, run tests and report evidence.                |

Claude may recommend an architectural change, but must not silently turn a hypothesis into a ratified rule or an implementation proposal into approval.

## 2. Rules for every future Membership change

- Start with the domain problem. Establish the business capability, actors, intent, domain events, rules, invariants and failure cases before choosing a technical solution.
- Update existing artefacts first. Reconcile the authoritative architecture, contracts, ADRs and Membership stories before creating duplicates.
- Trace every requirement. Link business intent → domain rule → architecture decision → story → acceptance criteria → tests → implementation evidence.
- Review the plan before coding. Claude must present the implementation sequence, test strategy, affected components, exclusions and risks.
- Apply TDD. Demonstrate the failing test before the production change, then prove the minimal implementation passes and run the required regression tests.
- Keep scope controlled. If implementation reveals a genuine architectural gap, Claude must document the evidence and stop for review when the change exceeds its authorisation.
- Maintain the backlog continuously. Reconcile Membership stories and dependencies after each delivery slice, without automatically creating new stories for every observation.
- Close with evidence. Report what changed, which tests ran, what remains unresolved and whether the architecture or backlog needs an authorised update.

This is not a requirement to reopen the entire architecture for every small fix. Existing approved architecture remains authoritative unless new evidence or an approved decision justifies changing it.

## 3. What to ask Claude to do now

I recommend recording this as a durable project-level workflow in the existing PublicDigit instructions and Membership context, rather than leaving it only in our conversation. The instruction should preserve the existing governance, approval gates and current C-1 status.

# PublicDigit Membership — Standing Development Strategy

## Purpose

All future Membership development must follow a DDD-driven, requirements-engineering-led workflow with explicit human approval gates and test-first implementation.

## Phase A — Domain and Requirements

Before implementation, discuss the problem through a Domain-Driven Design and requirements-engineering lens.

Establish:

- Business capability and stakeholder need
- Actors, ubiquitous language and domain boundaries
- Business rules, invariants, lifecycle transitions and edge cases
- Commands, facts, events and resulting domain state
- Temporal semantics, authority boundaries and failure/refusal conditions
- Acceptance criteria, non-functional requirements and unresolved decisions
- Dependencies on other bounded contexts

Distinguish ratified decisions, derived rules, hypotheses, evidence and open questions.

## Phase B — Architecture and Artefacts

Claude inspects the existing repository and authoritative documents, then writes or updates the relevant architecture and requirements artefacts.

Prefer reconciliation over duplication. Maintain traceability between business need, domain model, architecture decisions, requirements, backlog stories and tests.

Create new artefacts or story IDs only when justified and permitted by the applicable governance rules.

Present substantive domain or architectural decisions for review by the appropriate human authority. Claude must not invent approvals or ratify business policy.

## Phase C — Implementation Planning

Before implementation, Claude prepares a story-specific plan containing:

- Story ID, scope and Definition of Done
- Relevant approved domain rules and contracts
- Files and components expected to change
- Test cases and RED/GREEN/REFACTOR sequence
- Regression and additional verification strategy
- Dependencies, risks and excluded work
- Baseline verification and expected evidence
- Any required persistence, migration or compatibility implications

Claude presents the plan for review and approval by the appropriate authority.

## Phase D — Approval Gate

No implementation begins until:

1. Required domain and architectural decisions are resolved or explicitly bounded.
2. The relevant contract and requirements have the required approvals.
3. The implementation story and plan meet the applicable Definition of Ready.
4. All required gate conditions are closed.
5. The authorised scope and exclusions are explicit.

Plan approval does not override higher-level governance or authorise excluded work.

## Phase E — Test-Driven Implementation

Claude follows RED → GREEN → REFACTOR:

1. Verify the baseline and record existing failures.
2. Write a test for one approved behaviour.
3. Run it and record the failure.
4. Implement the smallest change needed to pass.
5. Refactor while preserving passing tests.
6. Repeat for each required behaviour.
7. Run regression and other required checks.
8. Record actual commands, outcomes, limitations and evidence.

Tests, oracles and models provide evidence; they do not supersede ratified domain rules.

## Phase F — Completion and Traceability

Claude reports:

- Implemented behaviour and files changed
- Acceptance criteria satisfied or outstanding
- Tests actually executed and their results
- Architecture deviations and any additional decisions required
- Story and backlog updates
- Remaining risks, blockers and follow-up obligations

Reconcile Membership stories and dependencies after each delivery slice. Do not silently broaden the current story or create duplicate backlog items.

## Phase G — Controlled Architecture Evolution

Keep approved architecture stable unless a genuine gap is demonstrated by implementation evidence, an operational defect, a newly approved business requirement, or another recognised governance trigger.

When a gap is found, document it, assess its impact, and obtain the required review before changing the governing architecture or implementation scope.

## Standing rule

DDD and requirements engineering determine what should be built and why. Approved architecture constrains how it may be built. Human review authorises the plan. TDD and verification provide implementation evidence. Completion review updates the backlog and identifies the next justified step.

Apply this workflow to future PublicDigit Membership changes while preserving existing governance, artefact ownership, approval rules and current project state.

My recommendation: ask Claude to integrate this into the existing PublicDigit-specific instructions and context, check for conflicts with already ratified workflows, and report exactly which files were updated. Do not ask Claude to restart discovery or change the current C-1 gate as part of documenting this strategy.