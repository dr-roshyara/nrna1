# PublicDigit Membership — Senior DDD architecture review

My recommendation is to make the next step an independent acceptance review of C-1 (`PBDIGIT-268`), followed by a controlled decision on the remaining findings. We should not start C-2 yet.

The attached report changes our project status significantly: Claude reports that C-1 has been implemented, committed, tested, and documented. However, Claude explicitly says the work is awaiting review and has not been accepted.

## 1. What has been achieved?

C-1 implementation reported complete

Annul and Void added to the existing Membership domain kernel.

Awaiting acceptance

Membership unit tests

# 117 passing

Architecture tests

# 12 passing

Bounded differential checks

# 15,530

Decisions compared

Mutation score

# 92%

95% on covered code

Source: Claude's implementation review dated 10 October 2026. These results are reported in the attachment; I have not independently rerun the tests.

The reported evidence is encouraging. The implementation preserves the append-only correction approach, introduces explicit refusal cases, tests restoration and invariant enforcement, and compares the kernel against a bounded oracle.

But the report also identifies important review questions. A passing test suite does not by itself establish that the implementation conforms to the approved plan, that its type contracts are sound, or that it is ready for acceptance.

## 2. My three principal review findings

Finding 1 — The TDD process deviated from the plan

The plan required RED → GREEN for each behaviour. Claude reports that steps 7–9, 11 and 12 had no RED phase because earlier implementation already provided the behaviour. Mutation testing showed that the tests could detect selected regressions.

That is useful evidence, but mutation sensitivity is not identical to demonstrating that a test failed before the corresponding behaviour was implemented.

Recommendation: require Claude to document each deviation and its rationale. Then review whether the existing tests independently establish the contract's required behaviours. Do not demand unnecessary code churn simply to manufacture a RED phase after the behaviour already exists.

Finding 2 — The `Fact` interface and persistence adapter disagree

The refactor moved `effectiveAt()` from `Fact` to `EffectiveDatedFact`, but `PostgresMemberRepository::encode(Fact $fact)` still calls `effectiveAt()` on `Fact`. Claude reports that an ad-hoc PHPStan run flags this.

This is a real type-contract inconsistency even if the current runtime only passes `Admit` and `End`.

Recommendation: investigate the call sites and typing before deciding the fix. Do not silently change the adapter under the existing scope restriction. Determine whether a narrow signature correction is safe now or whether it must be handled explicitly in C-4. The known analysis finding must remain tracked either way.

Finding 3 — Naming and backlog bookkeeping

`VoidFact` is a reasonable PHP representation because `Void` is a reserved language keyword. This appears to be a technical naming accommodation, not a change to the domain meaning.

The `PBDIGIT-268` backlog index entry is still pending because the README contains other sessions' uncommitted work.

Recommendation: confirm `VoidFact` and preserve the domain vocabulary “Void” in documentation. Add the backlog entry through a safe, isolated edit when the shared file can be updated without overwriting other work.

## 3. The next step: independent acceptance review

The most efficient next action is a read-only, evidence-driven review of the actual implementation and its commits. We need to resolve the two substantive engineering questions before deciding whether C-1 can be accepted.

The PostgreSQL feature suite was not run because the shared test database is subject to the existing safety restriction. That limitation should remain explicit; we must not imply that persistence integration has been verified.

## 4. Prompt instructions for Claude — next step

# PublicDigit Membership — Independent C-1 Acceptance Review

**Story:** PBDIGIT-268\
**Scope:** C-1, Annul/Void in the existing Membership kernel\
**Mode:** Independent review and evidence gathering only

## Objective

Prepare an evidence-based acceptance recommendation for C-1. The developer's implementation report is evidence to inspect, not proof of acceptance. Engineering must not accept its own work.

Do not start C-2, C-3 or C-4. Do not make production-code, persistence, schema, database, cutover or live-write changes during this review.

## 1. Establish repository state

Inspect the current branch, HEAD, working tree, staged and unstaged changes, and the C-1 commit range. Preserve unrelated work from other sessions.

Read the approved semantic contract, approved C-1 plan and its conditions, story PBDIGIT-268, implementation review, developer guide, relevant decision records and applicable engineering governance rules.

Verify that the reviewed implementation corresponds to the reported commits. Do not assume the current repository state matches the earlier report.

## 2. Review domain semantics

Assess the implementation against the approved contract and report evidence for:

- Append-only fact history and fact liveness.
- Annul and Void target validation and refusal ordering.
- `TargetNotFound`, `TargetNotLive`, `TargetHasNoDecisionProvenance`, `AnnulOfVoidRefused` and `DecidedAtRequired`.
- Restoration behaviour and applicable G\* invariants.
- Re-entry and overlap protection.
- Valid-time and `knownAt` reconstruction.
- Preservation of existing Admit/End behaviour.
- Scope exclusions and unresolved CQ-9.

Identify any mismatch between the contract, implementation, tests and developer guide.

## 3. Review the TDD deviation

The implementation report states that plan steps 7–9, 11 and 12 did not have a RED phase.

For each affected step:

1. Inspect the original approved plan, tests and relevant commit history.
2. Determine whether the test existed before the behaviour it verifies was implemented.
3. Assess whether mutation evidence establishes test sensitivity, and explain what it does not establish.
4. Determine whether the deviation affects confidence in acceptance or is justified by behaviour already implemented in an earlier approved slice.
5. Recommend acceptance, a targeted test correction, or another proportionate action.

Do not rewrite history or claim strict RED/GREEN compliance where the commit evidence does not support it.

## 4. Resolve finding F-1: interface and adapter typing

Inspect `Fact`, `EffectiveDatedFact`, `PostgresMemberRepository::encode()`, all relevant call sites, static-analysis configuration and the reported PHPStan result.

Determine:

- Whether the reported type error is reproducible.
- Whether any current call site passes a value typed only as `Fact`.
- Whether narrowing `encode()` to `EffectiveDatedFact` is type-safe at every call site.
- Whether this would prevent future persistence of `Annul`/`Void` facts or introduce another contract inconsistency.
- Whether the correction belongs in a narrowly authorised current fix or in C-4.
- What verification would be required for each option.

Do not change the adapter or production code. Provide the smallest technically sound options, their consequences and your recommendation. Clearly separate the current static-analysis defect from future C-4 persistence requirements.

## 5. Resolve finding F-2: VoidFact

Verify that `Void` is reserved in the applicable PHP version and that `VoidFact` preserves the approved domain vocabulary and semantics.

Recommend confirmation unless evidence identifies a real problem. Do not rename the class merely for stylistic consistency.

## 6. Verify test and quantitative evidence

Where feasible and safe, reproduce the reported unit and architecture test commands. Record actual commands and results.

Inspect the oracle fixture, PHP port, bounds, differential assertions and mutation report. Assess the strength and limitations of the evidence; do not present bounded enumeration as a mathematical proof.

Do not run the PostgreSQL feature suite against the unsafe shared test database. Report it as unverified unless the required isolated database gate is independently confirmed.

Do not claim tests were rerun if only historical output was inspected.

## 7. Backlog and documentation integrity

Verify that PBDIGIT-268, the implementation review, developer guide, plan and project context agree on status and outstanding decisions.

Do not modify the shared backlog README while unrelated edits could be overwritten. Report the smallest safe index update and its prerequisites.

Do not create new story IDs during this review. Identify any follow-up obligations and check existing stories before proposing new ones.

## 8. Deliver the review

Produce a review document containing:

- Executive recommendation: ACCEPT, ACCEPT WITH CONDITIONS, or RETURN FOR CORRECTION.
- Contract-to-code and acceptance-criteria traceability.
- Evidence for all reproduced checks and limitations.
- Explicit analysis of the TDD deviations.
- F-1 options and recommendation.
- F-2 disposition.
- Backlog/documentation follow-up.
- Outstanding risks, blockers and required human decisions.
- A final list of permitted next actions.

The developer must not mark C-1 accepted on the strength of their own review. Acceptance remains with the designated reviewer and PO under the applicable governance rules.

## Stop condition

Stop after producing the review and recommendation. Do not implement F-1, start C-2, change persistence, or perform cutover. Wait for the required review and human decisions.

## 5. How far are we from the Membership implementation goal?

My assessment separates implementation progress from acceptance and overall programme completion.

## Current milestone status

Correction contract and C-1 plan

Contract approved; implementation plan approved with conditions.

C-1 kernel implementation

Reported implemented and committed; tests and bounded differential evidence reported.

C-1 independent acceptance

Pending review of TDD deviations, interface typing and the complete evidence.

C-2, C-3 and C-4

Not authorised by the current C-1 scope; separate decisions and plans are required.

I would not assign a precise overall completion percentage from this report alone. C-1 is a bounded kernel slice, not the whole Membership implementation. The broader goal includes application and authority integration, persistence, safe migration, legacy compatibility, governance controls and eventual cutover.

## 6. Remaining TODOs

### Immediate — C-1

- Run the independent acceptance review using the prompt above.
- Evaluate the TDD deviations for steps 7–9, 11 and 12 against the approved plan and commit history.
- Resolve F-1: decide the safe treatment of the `Fact` / `EffectiveDatedFact` adapter typing mismatch.
- Confirm `VoidFact` as the implementation name while preserving the domain term “Void”.
- Add the `PBDIGIT-268` README index entry when it can be done without overwriting other sessions' edits.
- Record the review outcome and obtain formal C-1 acceptance from the designated authorities.

### Next correction capabilities — require separate authorisation

- Plan C-2 for `Correct` and correction-generated consequences.
- Resolve the applicable correction-policy questions, including D-COR-1, D-COR-3, D-COR-4, BD-REV-ER and D-COR-5(B), where still open.
- Resolve CQ-3 and G-AUTH-1 concerning authority integration.
- Keep CQ-9 and its legal/business implications explicitly unresolved until the proper decision authority settles them.
- Plan C-3 for application integration, command wiring, authority and events.
- Plan C-4 for persistence and serialization of correction facts, including a coherent design for the `EffectiveDatedFact` / reversal-fact distinction.

### Broader Membership implementation and production readiness

- Maintain continuous traceability between approved domain decisions, architecture, requirements, stories, acceptance criteria and tests.
- Reconcile Membership backlog stories and dependencies before creating additional story IDs.
- Resolve the existing Slice 1b acceptance and outstanding related decisions identified in the project context.
- Complete the authorised `PBDIGIT-264` and `PBDIGIT-267` work only after their respective gates are satisfied.
- Complete `PBDIGIT-266` security tests only after a dedicated, isolated test database is confirmed safe.
- Verify tenant isolation, persistence round trips, concurrency and all relevant legacy readers/writers.
- Complete migration/reverse-projection and single-writer consistency planning.
- Satisfy ARB-MEM-01: correction capability must be ready before cutover or live writes are enabled.
- Run the required integration and regression tests in an approved environment before production transition.

### Research and quality improvement

- Preserve the distinction between ratified rules, derived invariants, bounded-model evidence, empirical test results and open policy questions.
- Use property-based and differential testing where it adds evidence beyond example-based tests.
- Apply formal logic and exhaustive computation within explicitly stated bounds, without treating finite exploration as a proof of all possible histories.
- Introduce machine learning only if a concrete Membership problem, defensible data and measurable benefit justify it; deterministic Membership invariants must remain authoritative.

Bottom line: our next milestone is not another broad discovery round. It is to independently review and accept—or return for targeted correction—the implemented C-1 slice. Once that is resolved, we can authorise the next correction capability in a controlled sequence while continuing to maintain the complete Membership architecture and story backlog.&#x20;