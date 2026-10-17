# PublicDigit Membership — architecture review and next steps

My recommendation is to review and approve the proposed C-1 acceptance-correction plan before implementation. The uploaded file shows that Claude has now prepared a concrete plan and stopped at the approval gate. This is the correct workflow: the architecture and acceptance criteria must be agreed before further implementation.

The immediate objective is not to expand Membership functionality. It is to close the identified evidence gaps in the existing Annul/Void correction slice, preserve the scope boundary, and then make a formal acceptance decision.

## 1. What the new file establishes

The file reports that Claude created the proposed plan:

`docs/plans/20261010-1144-membership-c1-acceptance-correction-plan.md`

Plan prepared

The proposed plan covers the missing contract tests, reproducible sensitivity checks, and stronger bounded `knownAt` verification.

Awaiting your approval

The plan is marked PROPOSED. The H-1–H-6 decisions in the earlier document are recommendations, not recorded Human ARB decisions.

No implementation authorised

The report says no production code, tests, story, documentation, or backlog index changes have been implemented under this new plan. It records the plan and session log only.

The reported baseline remains 117 Membership unit tests and 12 architecture tests passing, with bounded differential and mutation evidence. These are results reported by Claude in the attachment, not tests independently rerun by me. The PostgreSQL feature suite remains unverified because a safe, isolated test database is not available.

## 2. Senior DDD review of Claude’s proposed plan

I agree with the direction. I recommend approving it with a few explicit conditions.

| Area                | My assessment                                                                                                            |
| ------------------- | ------------------------------------------------------------------------------------------------------------------------ |
| Domain semantics    | Preserve the approved C-1 semantics. Do not reopen settled decisions without new evidence.                               |
| K-E1 contract gap   | High priority. Accepted Annul/Void decisions must demonstrably carry the required fact data.                             |
| Refusal precedence  | Test the three missing guard-order pairs against the approved contract.                                                  |
| `knownAt` property  | Strengthen verification using a reference calculation independent of the production implementation.                      |
| Sensitivity testing | Good improvement. Stored mutation patches and a working canary make the evidence reproducible.                           |
| TDD claims          | Claude is right that tests for already-existing behaviour cannot honestly be described as having a historical RED phase. |
| Scope control       | Keep production code, adapters, schema, database, application wiring, and cutover out of this correction.                |
| Acceptance          | Keep C-1 awaiting formal review until the evidence is available.                                                         |

### Important distinction: TDD versus sensitivity

Claude has identified a genuine methodological issue. If the implementation already exists, writing a new test against it may produce a passing test immediately. That is not a RED → GREEN sequence.

The proposed alternative is defensible:

1. Run the new test against the unchanged baseline.
2. Introduce a deliberately incorrect implementation in a disposable scratch copy.
3. Verify that the test detects the specific defect.
4. Preserve the mutation patch, exact commands, output, and revert/disposal procedure.
5. Verify that the canary fails, proving the test runner is exercising the mutated copy rather than the original code.

This establishes test sensitivity to the specified counterexample. It does not prove that the test captures every possible defect or that the original implementation was developed test-first. Those are separate claims and should remain separate in the evidence.

One more mathematical qualification: the `knownAt` property is proposed for bounded histories. It can provide strong evidence within the stated bounds, but it is not a universal proof of the property.

## 3. What should you decide now?

As Human PO / ARB, you do not need to redesign the Membership domain model. You need to decide whether the proposed correction plan is sufficiently safe and precise to authorise.

I recommend the following decisions:

H-1 — Approve the correction plan

Authorise the bounded test-evidence correction only, subject to the conditions below. Do not authorise production changes.

H-2 — Record the TDD deviation accurately

Accept the historical deviation as documented, subject to final acceptance. Do not reconstruct a false RED/GREEN history.

H-3 — Defer the adapter issue to C-4

Keep the `Fact` / `EffectiveDatedFact` typing inconsistency as a tracked persistence obligation. Do not modify the adapter in C-1.

H-4 — Confirm `VoidFact`

Retain `VoidFact` as the PHP technical class name for the domain fact kind Void.

H-5 — Authorise minimal documentation updates

Allow the review addendum, guide clarification, story wording, and missing backlog index entry, while preserving unrelated work. Do not make these changes until this authorisation is recorded.

H-6 — Defer formal acceptance

Accept C-1 only after the correction evidence has been independently reviewed and the applicable PR/PO acceptance gate has been completed.

I also recommend explicitly approving CP-1, the proposed use of reproducible sensitivity tests instead of a claimed RED phase for already-implemented behaviour. Require Claude to retain the mutation patches, commands, and actual outputs, and to stop if any required mutant survives.

## 4. Claude prompt for the next step

The plan already exists, so the next prompt should not ask Claude to rediscover the architecture or write another plan from scratch. It should ask Claude to wait for the Human ARB decision and, once approved, execute only the authorised correction.

# PublicDigit Membership — Execute the Approved C-1 Acceptance Correction

**Story:** PBDIGIT-268\
**Plan:** `docs/plans/20261010-1144-membership-c1-acceptance-correction-plan.md`\
**Scope:** C-1 acceptance evidence only\
**Current status:** Proposed plan; implementation is not authorised until the Human PO / ARB explicitly approves it.

## 1. Authority and responsibilities

### Human PO / ARB

The Human PO / ARB owns reserved business and architectural decisions, approves the plan and any scope changes, and participates in formal acceptance.

### ChatGPT — Principal Architect and Reviewer

ChatGPT independently reviews the domain semantics, requirements, invariants, test strategy, mathematical reasoning, and evidence. It may recommend acceptance or further correction but cannot replace the Human ARB.

### Claude — Developer

Claude inspects the repository and authoritative artefacts, implements only the explicitly approved scope, runs the required tests, preserves evidence, and reports deviations. Claude must not approve its own implementation.

## 2. Approval gate

Before modifying anything:

1. Verify that the Human PO / ARB has explicitly approved the plan and CP-1, the sensitivity-based strategy for behaviour that already exists.
2. Verify which documentation changes are authorised under H-5.
3. Confirm that the approved plan still matches the current branch, working tree, and authoritative contract.
4. Preserve unrelated work from other sessions.
5. If approval is absent or ambiguous, stop without implementation.

## 3. Implement only the approved correction

Follow the approved plan:

- K-E1: test that accepted Annul and Void decisions expose the required fact ID, target ID, provenance, `decidedAt`, and `basedOnVersion`.
- Refusal precedence: test all three identified guard-order pairs against the approved semantic contract.
- K-H1: strengthen `knownAt` verification using an independent reference calculation and the specified bounded histories.
- Sensitivity: store the deliberate mutation patches and exact reproducible commands. Execute them only against a disposable scratch copy, never by modifying the working production tree.
- Canary: demonstrate that the sensitivity runner actually executes the mutated copy. Abort if the canary survives.
- Regression: run the authorised Membership unit and architecture suites and record actual results.
- Mutation testing: report the measured scope, score, killed mutants, and any survivors.

Do not claim that the new tests followed RED/GREEN when the behaviour already existed. Record baseline passing tests and sensitivity results accurately.

If a required mutant survives, stop and report the counterexample. Do not repair production code or alter the contract under this plan.

## 4. Scope exclusions

Unless the Human PO / ARB separately authorises a new plan, do not:

- change production code under `app/`;
- modify adapters, persistence, schemas, migrations, or databases;
- implement Correct or correction-generated consequences;
- add application command wiring or event emission;
- replace legacy behaviour, enable live writes, or perform cutover;
- create new story IDs or restructure unrelated backlog items;
- run the PostgreSQL feature suite against the unsafe shared database.

Keep the `Fact` / `EffectiveDatedFact` adapter issue tracked for C-4. Retain `VoidFact` as the PHP technical class name.

## 5. Documentation and traceability

Make only the documentation changes explicitly authorised under H-5. Preserve existing content and append review evidence rather than rewriting historical conclusions. Correct the TDD wording and clearly distinguish event data carried by a decision from event emission.

Keep the story, approved contract, tests, plan, review report, and backlog index consistent. Do not mark C-1 accepted.

## 6. Required final report

Report:

1. Exact files changed and commits created.
2. Tests executed, commands, exit codes, and actual results.
3. Mutation patches tested and verbatim sensitivity outcomes.
4. Canary outcome.
5. Regression and mutation-testing results.
6. Confirmation that prohibited production and persistence scope remained untouched.
7. Limitations of bounded enumeration and independent-oracle coverage.
8. Any unresolved counterexample, risk, or acceptance criterion.
9. Updated story and traceability status, where authorised.

After implementation, stop for independent review and the formal C-1 acceptance decision. Do not begin C-2, C-3, or C-4 as part of this task.

Important: send the approval decision alongside the prompt. The prompt itself is not approval. If you approve the plan, Claude can implement the test-evidence correction; if you do not, Claude must remain stopped.

## 5. How far are we from the overall Membership implementation goal?

The current evidence supports saying that the project has moved beyond conceptual modelling into a partially implemented correction kernel. It does not support saying that Membership is production-ready.

## Current milestone assessment

Domain modelling and correction contract

Substantially developed for the current slice. Approved semantics must remain authoritative unless new evidence justifies a decision change.

C-1 Annul/Void

Implemented according to the reported review; targeted evidence correction and formal acceptance remain outstanding.

Application, authority and event integration

Not completed by C-1. Requires separately approved scope and implementation plans.

Persistence, migration and legacy compatibility

End-to-end behaviour remains unverified. Safe database infrastructure, migration evidence, and single-writer controls are still required.

Production readiness

Not established. Integration, concurrency, tenant isolation, migration, regression, and governance gates remain.

I would not assign a programme-wide completion percentage without an up-to-date inventory of all Membership capabilities, stories, acceptance criteria, and implementation evidence. C-1 test counts and mutation scores are not a meaningful proxy for overall completion.

## 6. Remaining TODOs

### Immediate — C-1 acceptance

- Record Human PO / ARB decisions for H-1 through H-5 and explicit approval of CP-1.
- Approve or revise the existing correction plan before implementation.
- Add K-E1 tests for Annul/Void fact data.
- Add the three refusal-order tests.
- Strengthen the bounded `knownAt` property verification.
- Preserve reproducible mutation patches and prove the scratch runner with its canary.
- Run the authorised regression suite and record actual evidence.
- Make authorised documentation and backlog index corrections without disturbing unrelated work.
- Obtain independent review and formal C-1 acceptance.

### Next correction and integration capabilities

- Track the adapter typing issue into C-4 and resolve it within the persistence contract.
- Prepare separate plans for C-2 (Correct and correction-generated consequences), C-3 (application and authority integration), and C-4 (persistence).
- Resolve outstanding correction questions, including D-COR-1, D-COR-3, D-COR-4, BD-REV-ER, and D-COR-5(B), wherever they remain open.
- Resolve CQ-3 / G-AUTH-1 through the appropriate authority decision process.
- Keep CQ-9 explicitly unresolved until its business/legal question receives an authorised decision.

### Broader Membership implementation

- Audit existing Membership stories against approved domain decisions and implementation obligations.
- Maintain traceability from requirements to domain invariants, architecture, stories, tests, and acceptance evidence.
- Resolve outstanding Slice 1b acceptance and related decisions.
- Complete PBDIGIT-264 and PBDIGIT-267 only after their applicable gates.
- Establish a dedicated isolated test database before gated persistence testing, including PBDIGIT-266.
- Verify tenant isolation, persistence round trips, concurrency, and legacy readers/writers.
- Complete migration and reverse-projection planning, with enforceable single-writer consistency.
- Enforce ARB-MEM-01 before cutover or live-write enablement.
- Run authorised integration and full regression tests before production transition.

### Research and engineering quality

- Keep ratified rules, derived invariants, hypotheses, bounded-model results, and empirical evidence explicitly distinguished.
- Continue using property-based, differential, and mutation testing to expose counterexamples.
- State bounds and assumptions for exhaustive computation; do not present finite enumeration as universal proof.
- Use machine learning only where a concrete data-driven problem and measurable benefit justify it. Membership eligibility, correction semantics, and other deterministic invariants must remain governed by explicit domain rules.

Next milestone: approve the C-1 correction plan, close the evidence gaps, and complete independent acceptance. Only then should the team move to the next separately governed implementation slice.