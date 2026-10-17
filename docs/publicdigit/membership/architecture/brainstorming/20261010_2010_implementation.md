# Membership — independent Principal Reviewer assessment

I have reviewed Claude’s latest execution report. PBDIGIT-264 F-1, Part 1 has now been committed, and its evidence has been preserved. We should not treat that as completion of the overall Membership implementation.&#x20;

Eingefügter Text.txt



The most important decision now is what to do next without confusing a completed technical correction with acceptance of the wider business architecture.

## 1. Where we stand now

PBDIGIT-264 F-1 — Part 1

Committed

The boundary tests, production correction, and evidence/plan changes were committed in three separate commits. The report says the evidence manifest verifies 13 of 13 captured files. Nothing was pushed.&#x20;

Eingefügter Text.txt



Domain decision: supported date range

Unresolved

We have a technical guard, but have not decided whether the business domain should reject unsupported `effectiveAt` dates with an explicit business refusal. Claude correctly did not assign this question to Slice 2 pack D-5(b), which concerns a different question.&#x20;

Eingefügter Text.txt



PostgreSQL verification — Part 2

Not started

The actual database rollback behaviour remains unverified. Part 2 requires the provisioning evidence, the approved safety conditions—including a test role unable to connect to `nrna_test`—and separate authorization.&#x20;

Eingefügter Text.txt



G3 and implementation-slice decisions

Pending

These remain separate review and acceptance decisions. They must not be silently accepted as a side effect of the date fix.&#x20;

Eingefügter Text.txt



Claude also reported that it corrected a temporary staging-index problem after the third commit and verified the unrelated changes remained unchanged. This is a disclosed process deviation, not a reason to assume corruption; it is worth keeping in the audit record.&#x20;

Eingefügter Text.txt



## 2. What should the next step be?

My recommendation is a Membership next-step planning and decision-readiness review—not another implementation task yet.

We need Claude to inspect the current repository and existing decision documents, then prepare a small, evidence-based plan answering four questions:

1. What is actually complete? Distinguish implemented, tested, independently reviewed, formally accepted, and still unverified work.
2. Which unresolved decision blocks the next increment? Clarify the supported calendar range of `effectiveAt`, while keeping it separate from the existing backdating/scheduling decision.
3. What is the next safe increment? Evaluate the pending G3/Slice decisions and the PostgreSQL verification prerequisites, without assuming that either is authorized.
4. What remains to reach the Membership business goal? Identify the implementation gaps, dependencies, acceptance criteria, and tests that prove the business rules.

This gives us a reliable basis for choosing the next increment. It also prevents Claude from taking a convenient technical task and treating it as the next business priority.

## 3. Prompt instructions for Claude — next Membership step

Copy the following prompt into Claude. It deliberately authorizes investigation and planning only, so that we can review and finalize the architecture before implementation begins.

# PublicDigit Membership — Next-Step Planning and Architecture Readiness

## 1. Your role and objective

Continue work exclusively on the **PublicDigit Membership workstream**.

Act as a senior DDD architect, requirements engineer, domain researcher, mathematical modeller, statistician, expert in computer logic, and implementation planner.

Your objective is to help establish a robust, internally consistent Membership theory that can be implemented and verified in practical software.

Treat the existing research corpus as **brainstorming and discovery material**. Extract useful hypotheses, business rules, concepts, counterexamples, contradictions, and unresolved questions. Do not assume that every statement in the corpus is authoritative or approved.

Optimize for business correctness, architectural coherence, explainability, maintainability, testability, and minimal unnecessary complexity.

## 2. Current state to verify

The latest report says that PBDIGIT-264 F-1 Part 1 has been committed in three separate commits:

- C1: boundary tests;
- C2: date-conversion guard;
- C3: captured evidence and plan correction.

The evidence was preserved and its SHA256SUMS manifest verified. No push occurred.

The following remain unresolved or unverified:

- The domain-level policy for unsupported `effectiveAt` dates.
- The formal G3 and implementation-slice verdicts.
- PostgreSQL verification of transaction rollback.
- The prerequisites and authorization for Part 2.
- The overall remaining Membership implementation backlog.

First inspect the current repository state and relevant documents. Verify these claims rather than relying solely on this prompt.

Review the current `CONTEXT.md`, the active plan, the relevant decision packs, the G3 and slice reviews, the PBDIGIT-264 evidence, and the existing Membership architecture and development strategy.

Use the repository's actual paths and current versions. Do not infer missing decisions or reconstruct missing evidence.

## 3. Mandatory workflow: plan before implementation

This task is **PLAN ONLY**.

Do not edit source files, alter decisions, implement new behaviour, stage or commit files, push changes, run tests, or access any database.

You may perform the read-only inspection needed to prepare a reliable plan. Do not launch experiments, scripts, or computational jobs without separate authorization.

Produce a decision-ready proposal. ChatGPT will independently review the proposal. The Human PO/ARB will decide policy, scope, architecture acceptance, residual risk, and implementation authorization.

Only after the architecture and requirements are finalized and the Human PO/ARB approves the next increment may you prepare and execute an implementation plan.

Do not treat silence, a recommendation, a previous approval for another increment, or a successful test as approval for new work.

## 4. Investigate the current Membership architecture

Establish what is already implemented and what remains only a proposal.

Use evidence from the repository to assess:

- Membership identity and uniqueness per `(organisation, person)`.
- Membership periods, termination, re-entry, and overlapping periods.
- Valid-time dates versus recorded and decision instants.
- Membership facts and their correction, annulment, and voiding.
- Append-only history and the resolution of current truth.
- Member, period, appointment, mandate, authority, duty, and voting-entitlement distinctions.
- Aggregate boundaries, invariants, repositories, application services, and domain events.
- Transactional consistency, messaging, projections, auditability, and idempotency where implemented.
- Existing test coverage and the limits of what the tests prove.

Do not redesign established and approved architecture merely to produce a new design. Identify actual defects, missing decisions, contradictions, and implementation gaps.

For every material conclusion, state the evidence source and distinguish observed fact from inference.

## 5. Investigate the unresolved `effectiveAt` range

Treat this as a separate domain-policy question, not an automatic extension of PBDIGIT-264.

Determine:

1. What the current domain model permits.
2. What the infrastructure conversion supports.
3. What business meaning `effectiveAt` has.
4. Whether the business needs a defined calendar range, and what the authoritative basis for that range would be.
5. Whether invalid values should be refused by the domain, application boundary, infrastructure, or more than one layer for different reasons.
6. What refusal reason and business-facing explanation would be appropriate if a domain refusal is required.
7. What invariants, boundary cases, and regression tests would prove the chosen policy.
8. Whether the question already has a decision identifier in the current decision packs.

Do not map the question to Slice 2 pack D-5(b) without explicit evidence. The latest report indicates that D-5(b) concerns whether `effectiveAt` may be before or after the decision date, not the supported calendar range.

Present the alternatives and their consequences. Do not select a business policy on behalf of the Human PO/ARB.

## 6. Research and formal reasoning

Use the existing research corpus to develop and challenge the Membership theory.

Where useful, apply:

- Set theory and relational constraints to identity, uniqueness, and periods.
- Temporal reasoning to valid time, record time, and decision time.
- Formal logic to distinguish necessary conditions, sufficient conditions, implications, and exceptions.
- State-transition analysis to examine membership lifecycle and re-entry.
- Counterexamples and property-based reasoning to find inconsistent rules.
- Statistical methods only where meaningful empirical data exists.
- Machine-learning techniques for clustering research statements, identifying similar concepts, surfacing possible contradictions, or prioritizing material for human review.

Machine learning must not determine authoritative business truth, invent requirements, or silently resolve contradictions. Every proposed rule must be traceable to source evidence or an explicit human decision.

Do not add mathematical formality or ML merely for its own sake. Use these techniques when they improve correctness, discovery, or verification.

For each significant proposed invariant, provide:

- Its business meaning.
- A precise logical or mathematical formulation where appropriate.
- A counterexample that would violate it.
- The implementation location where it should be enforced.
- The test or evidence needed to verify it.

## 7. Assess the next safe increment

Compare the realistic next-step candidates, including:

- Closing the open domain-policy question.
- Preparing G3 and implementation-slice decisions for Human PO/ARB review.
- Preparing the PostgreSQL verification prerequisites and safety evidence.
- Selecting the next implementation slice based on dependencies and business value.

Do not assume that PostgreSQL verification is automatically the next step merely because it appears in the existing plan. Check its prerequisites and existing authorization.

Recommend one next action and explain why it has priority over the alternatives. Identify its dependencies, risks, completion criteria, and what must remain out of scope.

Do not start Part 2 or any other implementation increment.

## 8. Produce a complete but concise planning package

Return the following sections.

### A. Executive status

Summarize the current Membership state in business language.

### B. Verified implementation inventory

Use the statuses:

- Implemented.
- Tested with recorded evidence.
- Independently reviewed.
- Formally accepted.
- Unverified.
- Not started.

Do not treat these statuses as interchangeable.

### C. Architecture and requirements findings

List only material findings, each with evidence and impact.

### D. Open decision register

For every unresolved question, provide:

- Question and business impact.
- Existing evidence.
- Alternatives and trade-offs.
- Decision owner.
- Dependencies.
- Proposed acceptance criteria.

Preserve existing decision identifiers and do not invent mappings.

### E. Recommended next increment

Define its objective, scope, exclusions, prerequisites, deliverables, and measurable completion criteria.

### F. Implementation plan — conditional

Outline the likely implementation sequence, tests, migration or data implications, and rollback considerations. Label this as a proposal, not authorization to execute.

### G. Remaining Membership backlog

Organize work by dependency and business outcome rather than generating an arbitrary large task list. Identify blockers and distinguish required work from optional improvement.

### H. Project-state synchronization

Check whether `CONTEXT.md` and the active plan correctly reflect the current ticket, committed work, outstanding decisions, and next action. Report discrepancies and propose exact updates, but do not edit them during this planning task.

### I. Questions for ChatGPT and Human PO/ARB

Separate:

- Technical questions for independent ChatGPT review.
- Business-policy questions for Human PO/ARB.
- Decisions that can wait.

### J. Final recommendation

State exactly what should happen next, what must be decided first, and what you are not authorized to do.

## 9. Required review gates

The workflow is:

1. Claude investigates and proposes the plan.
2. ChatGPT independently challenges the requirements, domain model, invariants, mathematical reasoning, dependencies, and test strategy.
3. Human PO/ARB decides business policy and formally approves or rejects the architecture and scope.
4. Claude updates the approved plan and implements only the authorized increment.
5. Evidence is reviewed before the work is considered complete.

Do not skip directly from research to code.

**Stop condition:** Return the planning package and wait. Make no code, decision-record, test, database, staging, commit, or push changes under this instruction.

## 4. My assessment of progress toward the Membership goal

The project has established a substantial foundation: the core domain concepts, important invariants, correction behaviour, and parts of the implementation and testing strategy. But a robust core is not the same as a complete operational Membership system.

Based on the project status previously reported, I would use approximately 20–25% as a provisional estimate of overall implementation progress, not as a measured completion metric. We should refine that estimate after Claude inventories the current repository and acceptance evidence.&#x20;

### Remaining todos

- Establish the verified baseline: reconcile repository state, active ticket, `CONTEXT.md`, implementation evidence, and existing decisions.
- Resolve the supported `effectiveAt` calendar range: keep it separate from backdating/scheduling policy and obtain the appropriate Human PO/ARB decision.
- Complete the G3 and implementation-slice reviews: establish which slices are accepted, rejected, or still awaiting evidence.
- Prepare PostgreSQL verification: satisfy provisioning, test-role isolation, and all other documented safety gates before requesting authorization.
- Verify transactional rollback: prove that a rejected date leaves no persisted membership fact, command, or version change.
- Continue implementation from the approved architecture: prioritize remaining domain behaviour, application workflows, persistence, messaging, and projections according to actual gaps.
- Complete consistency and integrity verification: test identity uniqueness, membership periods, re-entry, correction rules, and temporal invariants.
- Complete the remaining integration and operational work: determine the actual API, UI, migration, audit, and deployment gaps from the repository rather than assuming their status.
- Build a traceable acceptance suite: connect business requirements to domain rules, implementation, tests, and recorded evidence.
- Perform final regression and acceptance: resolve relevant failures, review residual risks, and obtain formal acceptance before declaring production readiness.

The immediate next action is the planning package in the prompt above. It should give us an evidence-based answer to the question that matters most: which next increment delivers the greatest business value while respecting the architecture and the decisions still owned by the Human PO/ARB?