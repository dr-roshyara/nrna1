# PublicDigit Membership — next step as Principal DDD Architect and Reviewer

My recommendation is to close the C-1 acceptance gaps through a small, explicitly planned test-only correction, then complete formal acceptance. Do not start C-2, C-3 or C-4 yet.

I have reviewed the attached `2026-10-10-membership-c1-acceptance-review.md` as the current evidence report. It changes the previous assessment: the reviewer reports that the core Annul/Void semantics are correct, but one approved contract criterion, K-E1, has no test. It also identifies three untested refusal-order pairs and a gap in property-level testing of `knownAt`.

The recommendation in the report is therefore RETURN FOR TARGETED CORRECTION, then ACCEPT WITH CONDITIONS.

## 1. What we know now

Core C-1 semantics

The review reports correct fact-liveness behaviour, restoration, invariant enforcement and separation of Membership periods from fact liveness.

Acceptance evidence incomplete

K-E1 lacks a unit test. Three adjacent refusal-order pairs are untested, and K-H1 is tested by example rather than as the full stated property.

Engineering evidence reproduced

The report records 117 passing Membership unit tests, 12 passing architecture tests, a reproduced bounded differential check over 15,530 decisions, and a 92% mutation score.

Persistence integration remains unverified

The PostgreSQL feature suite was not run because an isolated test database is not available. The existing database safety restriction must remain in force.

These results are reported in the attached review, not independently rerun by me. The quantitative evidence supports continued review, but it does not replace the missing contract-level test or formal acceptance.

## 2. My recommendations on the six outstanding decisions

| Finding                        | My recommendation                                             | Reason                                                                                                                                                                                                                         |
| ------------------------------ | ------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| H-1 — Targeted test correction | Authorise preparation of a plan                               | Close K-E1, test refusal precedence, and strengthen the `knownAt` property. Review the plan before implementation.                                                                                                             |
| H-2 — TDD deviation            | Accept as a documented deviation, subject to final acceptance | The general liveness implementation was broader than the initial failing test. The existing independent checks support confidence, but the historical process should not be described as strict RED/GREEN for every criterion. |
| H-3 — Adapter typing F-1       | Track to C-4                                                  | The reported guard fix touches the adapter and lacks isolated-database verification. Preserve the current C-1 scope and explicitly track the type inconsistency for C-4.                                                       |
| H-4 — `VoidFact`               | Confirm                                                       | PHP reserves `Void` as a class name. `VoidFact` is a technical accommodation; the domain fact remains “Void.”                                                                                                                  |
| H-5 — Documentation            | Authorise minimal corrections                                 | Correct the implementation-review and guide wording, accurately qualify the TDD claim, and add the missing backlog index entry without overwriting unrelated work.                                                             |
| H-6 — Formal C-1 acceptance    | Defer until H-1 is complete                                   | The missing K-E1 evidence must be supplied and reviewed before the acceptance decision.                                                                                                                                        |

These are my recommendations as Principal Architect and Reviewer. The PO/Human ARB retains the formal decisions; I am not treating any recommendation as an approval already given.

## 3. Why K-E1 is the priority

K-E1 requires an accepted Annul/Void decision to carry the required fact data. According to the report, the implementation has the data, but no unit test verifies that all required fields are preserved.

The targeted correction should verify at least:

- Fact ID.
- Target fact ID.
- Decision provenance.
- `decidedAt`.
- `basedOnVersion`.

The test should fail if any required field is missing or incorrect. In particular, a mutation that drops or changes `decidedAt` must be detected.

The three refusal-order tests should verify the expected result when two guards could both apply. The `knownAt` test should be strengthened from a single example to the contract's stated property over the relevant bounded histories, where feasible.

This is a focused improvement to evidence quality, not a reason to redesign the Membership domain model.

## 4. The next Claude task: plan first, implement only after approval

The following prompt follows our standing workflow: domain and requirements review first, a concrete plan second, human review and approval third, and only then TDD implementation.

# PublicDigit Membership — Plan the C-1 Acceptance Correction

**Story:** PBDIGIT-268\
**Current status:** Implemented, awaiting acceptance\
**Mode:** Planning and evidence reconciliation only\
**Next milestone:** Close the identified test-evidence gaps and prepare C-1 for final acceptance

## 1. Roles and authority

### Human PO / ARB — Dr. Nab Raj Roshyara

- Owns reserved business and architectural decisions.
- Approves the targeted correction plan before implementation.
- Approves any change to the authorised scope.
- Participates in formal C-1 acceptance under the applicable governance process.

### ChatGPT — Principal Architect and Reviewer

- Reviews the domain model, requirements, invariants, correction semantics, formal reasoning, test adequacy and implementation evidence.
- Challenges assumptions and identifies gaps.
- Recommends acceptance or further correction.
- Does not claim repository inspection or test execution it has not performed.
- Does not replace the human authority for reserved decisions.

### Claude — Developer

- Inspects the actual repository and authoritative artefacts.
- Prepares the targeted correction plan and presents it for approval.
- Implements only the approved scope, using TDD.
- Reports actual commands, outcomes, deviations and remaining risks.
- Does not accept its own work or silently expand the scope.

## 2. Establish the authoritative state

Inspect the current branch, HEAD, working tree, C-1 commit history, approved semantic contract, implementation plan, story PBDIGIT-268, acceptance review, developer guide, backlog and relevant governance rules.

Reconcile any contradictory statuses before proceeding. Preserve unrelated work from other sessions.

Use the existing correction acceptance review as the starting evidence. Do not restart broad Membership discovery or redesign the C-1 domain semantics without new evidence.

## 3. Prepare a minimal correction plan

Prepare a separate, reviewable plan covering the following items.

### A. K-E1 unit test

Add a test for an accepted Annul decision and an accepted Void decision, verifying all required fact data:

- fact ID;
- target ID;
- provenance;
- `decidedAt`;
- `basedOnVersion`.

Include a meaningful sensitivity check showing that a mutant dropping or changing `decidedAt` is detected. Verify the actual decision object and its observable data, not an assertion that is guaranteed by immutability.

### B. Refusal-order tests

Add focused tests for the three missing adjacent guard-order pairs:

1. `DecidedAtRequired` versus target-type checks.
2. `AnnulOfVoidRefused` versus `TargetHasNoDecisionProvenance`.
3. `TargetHasNoDecisionProvenance` versus `TargetNotLive`.

Use inputs that make both guards applicable and assert the exact expected refusal according to the approved contract. Do not change guard order unless the contract or a newly approved decision requires it.

### C. K-H1 `knownAt` property

Strengthen the example-based test to exercise the contract's stated prefix-stability property over relevant bounded histories, if the current test architecture permits this without disproportionate complexity.

The property must use a reference calculation that does not simply call the production `knownAt` or liveness implementation. State the domain and temporal bounds explicitly. Do not claim a universal proof from finite enumeration.

### D. Test strategy and TDD record

For every new behaviour, use RED → GREEN → REFACTOR:

1. Write the test.
2. Run it and record the meaningful failure.
3. Implement the minimum change, expected to be test-only.
4. Run the test and the required regression checks.

If a test passes before any change because the implementation already satisfies the behaviour, document this honestly and demonstrate meaningful sensitivity. Do not manufacture a production change or rewrite history solely to claim strict TDD compliance.

## 4. Preserve the existing boundaries

The plan must explicitly exclude:

- production-code changes;
- adapter changes or persistence fixes;
- schema or database changes;
- Correct and correction-generated consequences;
- application command wiring and event emission;
- legacy replacement, cutover and live-write enablement;
- new story IDs or unrelated backlog restructuring.

F-1 (`Fact` / `EffectiveDatedFact` adapter typing) remains an explicitly tracked C-4 obligation unless the PO separately authorises a narrowly scoped change.

`VoidFact` remains the proposed PHP class name for the domain fact kind Void.

## 5. Define the plan's Definition of Done

The plan is complete when:

- all targeted tests exist and exercise the intended contract;
- K-E1's required data is verified;
- all three refusal-order pairs are tested;
- K-H1 has appropriately strengthened property-level evidence;
- the tests demonstrate meaningful sensitivity;
- existing Membership unit and architecture regression tests pass;
- the existing bounded differential and oracle evidence remain correctly characterised;
- no prohibited scope has changed;
- the review, guide and story wording accurately describe the TDD deviation and remaining limitations;
- all actual commands and outcomes are recorded.

The PostgreSQL feature suite must not be run against the unsafe shared database. Report persistence integration as unverified until the required isolated database gate is satisfied.

## 6. Approval gate

Present the plan, affected files, test cases, risk assessment and expected evidence to the PO and Principal Reviewer.

**Stop after preparing the plan. Do not implement the correction until the plan is explicitly approved.**

After approval, implement only the authorised test and documentation corrections. Then stop for independent review and the formal C-1 acceptance decision.

## 7. Final report

Report:

- the proposed plan and its scope;
- the exact files and tests expected to change;
- unresolved decisions and dependencies;
- how each test demonstrates the contract;
- the distinction between verified evidence, bounded results and assumptions;
- the acceptance criteria that will remain after the correction.

Do not mark C-1 accepted, begin C-2, or claim production readiness as part of this task.

## 5. What should you do now?

I recommend that you authorise Claude to prepare the targeted correction plan, while recording your decisions on H-2 through H-5. You do not need to make a new Membership domain-policy decision to proceed with this planning step.

You can send Claude this decision statement:

**PO / Human ARB Decision — C-1 Acceptance Review**

Date: 2026-10-10

1. **H-1:** I authorise preparation of a focused test-only correction plan for K-E1, the three refusal-order pairs, and the K-H1 property. This is authorisation to prepare the plan, not to implement it. Claude must present the plan for approval before making changes.
2. **H-2:** I accept the reported TDD deviation as a documented process deviation, subject to final acceptance review. The implementation and documentation must not claim strict RED/GREEN for behaviours whose implementation preceded their tests.
3. **H-3:** I select Option A: track the `Fact` / `EffectiveDatedFact` adapter typing issue into C-4. No adapter change is authorised under C-1.
4. **H-4:** I confirm `VoidFact` as the PHP class name for the domain fact kind Void.
5. **H-5:** I authorise minimal, evidence-based documentation corrections and the PBDIGIT-268 backlog index entry, provided unrelated work is preserved and no additional implementation scope is introduced.
6. **H-6:** Formal C-1 acceptance is deferred until the targeted correction is completed and independently reviewed.

Claude must now prepare the plan, reconcile the relevant artefacts, and stop for review. No implementation is authorised by this statement.

## 6. How far are we from the overall Membership implementation goal?

The project has progressed beyond theoretical modelling into implementation of the correction kernel. However, C-1 is not yet accepted, and the overall Membership implementation is not production-ready.

| Area                                      | Current position                                                                                                   |
| ----------------------------------------- | ------------------------------------------------------------------------------------------------------------------ |
| Membership domain theory and architecture | Substantially developed; continue validating against practical requirements and the authoritative corpus.          |
| Correction semantics                      | C-1 contract approved; D-COR-5 Option A established.                                                               |
| Annul/Void kernel                         | Implemented according to the report; awaiting targeted evidence correction and acceptance.                         |
| Automated verification                    | Strong reported unit, architecture, bounded differential and mutation evidence; identified gaps remain.            |
| Application and authority integration     | Not completed under C-1; requires separate authorised work.                                                        |
| Persistence and migration                 | Not verified end to end; isolated database readiness remains an important dependency.                              |
| Legacy consistency and cutover            | Still requires controlled migration, single-writer assurance, regression and governance approval.                  |
| Membership story traceability             | Must continue across the existing backlog; new stories should be created only for genuinely uncovered obligations. |

I would not assign an overall completion percentage without a fresh, evidence-based inventory of all Membership capabilities, stories and acceptance conditions. C-1's test metrics cannot be used as a proxy for the whole programme.

## 7. Remaining TODOs

### Immediate — C-1 acceptance

- Record the PO decisions H-1 through H-5.
- Have Claude prepare the targeted test-only correction plan.
- Review and approve the plan before implementation.
- Add K-E1 tests for accepted Annul/Void decision data.
- Add the three refusal-order tests.
- Strengthen K-H1's `knownAt` property verification.
- Re-run the authorised regression suite and record actual evidence.
- Correct the implementation review, guide, story and backlog index as authorised.
- Obtain independent review and formal C-1 acceptance.

### Next correction and integration capabilities

- Track F-1 into C-4 and resolve the interface/persistence contract coherently.
- Prepare separate, approved plans for C-2 (Correct and correction-generated consequences), C-3 (application and authority integration), and C-4 (persistence).
- Resolve outstanding correction questions: D-COR-1, D-COR-3, D-COR-4, BD-REV-ER and D-COR-5(B), where still open.
- Resolve CQ-3/G-AUTH-1 and keep CQ-9 explicitly unresolved until its proper business/legal decision is made.
- Continue the existing authorised Membership work without bypassing its gates.

### Broader Membership implementation

- Audit Membership stories against approved domain decisions and implementation obligations.
- Maintain traceability from business requirements through architecture, stories, acceptance criteria, tests and evidence.
- Resolve outstanding Slice 1b acceptance and related decisions.
- Complete PBDIGIT-264 and PBDIGIT-267 only after their applicable approvals.
- Establish a dedicated isolated test database before the gated PBDIGIT-266 tests or other persistence verification.
- Verify tenant isolation, persistence round trips, concurrency and legacy readers/writers.
- Complete migration, reverse-projection and single-writer consistency planning.
- Enforce ARB-MEM-01 before cutover or live-write enablement.
- Run full authorised integration and regression verification before production transition.

### Research and engineering quality

- Maintain the distinction between ratified rules, derived invariants, hypotheses, bounded-model evidence and empirical results.
- Use formal reasoning, property-based testing and differential testing to expose counterexamples and strengthen confidence.
- Treat finite exhaustive enumeration as evidence within its stated bounds, not as a universal proof.
- Use machine learning only when a concrete problem, suitable data and measurable benefit justify it; deterministic Membership invariants must remain authoritative.

My final recommendation: the next milestone is a narrowly scoped, plan-first correction to C-1's missing test evidence. Once the plan is approved, Claude can implement it using TDD, after which we can complete formal acceptance. That is the shortest controlled route forward without reopening settled domain decisions or compromising the wider Membership architecture.