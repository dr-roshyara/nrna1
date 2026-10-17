# PublicDigit Membership — Principal Review and Next Step

I have reviewed the attached report as an independent Principal Reviewer, senior DDD architect, requirements engineer, and researcher. My assessment is that the work is progressing in the right direction, but we should resolve the outstanding architecture and governance decisions before authorizing the next implementation increment.

The immediate technical question is how to handle dates that the domain model can represent but the persistence layer cannot store. The broader goal remains unchanged: derive a robust Membership theory from the research corpus and turn it into a correct, testable, maintainable implementation.

## 1. Principal review of Claude’s report

What I agree with

- The domain `TimePoint` abstraction should not be restricted merely to match a database date representation.
- The report correctly separates technical storage limits from business calendar rules, historical plausibility, and backdating or scheduling policy.
- The in-memory repository is a test double, not a second production adapter. The verified discrepancy is therefore a production-versus-test-double contract gap, rather than a conflict between two production implementations.
- A planned test matrix must not be presented as evidence that tests have passed.
- The PostgreSQL rollback behaviour remains unverified by the evidence described in the report.

What I recommend

Investigate and prepare Option E as the next technical increment. It aims to make the repository contract explicit and consistent without creating an unapproved business rule.

Keep any business calendar or plausibility policy as a separate Human PO/ARB decision.

What still needs verification

- Whether the proposed range belongs in the repository port contract or in a shared persistence-contract abstraction.
- Whether every relevant date-bearing fact is checked before any state change.
- Whether the new `AppendOutcome` is exhaustively handled, including by the application retry loop.
- Whether tests can demonstrate unchanged repository state after rejection.
- Whether the chosen failure type clearly distinguishes a technical persistence limitation from a domain refusal.

### My important architectural qualification

Option E is a strong technical candidate, not yet a fully verified solution.

A repository port can declare a storage capability when that capability is genuinely part of its contract. But we should avoid leaking PostgreSQL implementation details into the domain model or turning a storage limitation into a business rule by accident.

The intended separation is:

| Layer                              | Responsibility                                                                                            |
| ---------------------------------- | --------------------------------------------------------------------------------------------------------- |
| Domain                             | Defines temporal concepts and Membership invariants.                                                      |
| Repository contract                | Makes persistence limitations explicit and testable.                                                      |
| PostgreSQL adapter and test double | Honour the same relevant persistence contract.                                                            |
| Application                        | Maps persistence outcomes to explicit application-level results; never mislabels them as domain refusals. |
| Human PO/ARB                       | Decides whether Membership needs an additional calendar or plausibility rule.                             |

One further issue deserves special attention: Claude identified a `DecideAndAppend::run` switch without a default. The next increment should make the mapping exhaustive so a new outcome cannot silently turn into a misleading `ConcurrentModification` result.

The objective is not to add layers or abstractions unnecessarily. It is to establish one explicit contract, one understandable failure outcome, and evidence that the contract holds.

## 2. What should we do now?

I recommend the following controlled sequence.

## 1

Prepare the Option E implementation plan

Claude inspects the exact repository boundaries and designs the smallest safe change. This is planning only; no code changes or tests.

Next action

## 2

Independent ChatGPT review

I challenge the proposed design, result semantics, layering, edge cases, and test plan. Claude revises the plan where necessary.

## 3

Human PO/ARB decision

Finalize the technical contract and record any separate business-policy decision. Also resolve outstanding formal acceptance verdicts when the required evidence is available.

## 4

Implement only after explicit authorization

Claude writes failing tests first, implements the approved change, runs the authorized regression tests, captures actual exit codes and evidence, and reports any remaining failures without concealing them.

## 3. Prompt for Claude — next step

Copy the following prompt to Claude. It deliberately authorizes planning only. Implementation will require the plan to be reviewed and the architecture to be finalized first.

# PublicDigit Membership — Option E: Implementation Design and Readiness Plan

## 1. Your role

Act as a senior Domain-Driven Design (DDD) architect, principal software engineer, requirements engineer, persistence architect, test engineer, and research analyst.

Work on the PublicDigit Membership workstream only.

Treat the existing research corpus, domain decisions, architecture documents, code, tests, and recorded evidence as the authoritative working material. Do not invent business rules to simplify implementation.

Your objective is to establish a robust Membership theory and translate approved decisions into the smallest correct, testable, maintainable implementation.

## 2. Collaboration and decision authority

The work has three distinct roles:

**Claude — Investigator and implementer**

- Inspect the repository and existing evidence.
- Identify problems, investigate alternatives, and prepare implementation plans.
- After architecture and requirements have been finalized and explicitly approved, implement only the authorized scope.
- Report actual evidence, uncertainty, and unresolved failures honestly.

**ChatGPT — Independent Principal Reviewer**

- Challenge the design independently.
- Check DDD boundaries, requirements completeness, invariants, temporal semantics, persistence contracts, failure semantics, test coverage, and hidden assumptions.
- Identify alternatives and contradictions rather than merely endorsing Claude's proposal.
- Do not treat Claude's recommendation as an approved business decision.

**Human PO/ARB — Decision authority**

- Decide business policy and approve architecture where required.
- Record the final verdicts in the appropriate decision or gate register.
- Explicitly authorize implementation after reviewing the final design.

No architectural recommendation, review, or earlier authorization automatically approves a new increment.

## 3. Current task: investigate and plan Option E

The latest investigation reports that:

- `TimePoint` represents a general temporal abstraction and deliberately has no representable-calendar bounds.
- Domain operations `decideAdmit` and `decideEnd` currently enforce ordering but do not enforce the persistence calendar range.
- `DateOrdinal::toDate()` protects the PostgreSQL conversion boundary.
- PostgreSQL is the only production repository adapter.
- `InMemoryMemberRepository` is a test double.
- The production adapter and test double share the repository contract test suite.
- The test double currently lacks the relevant date-representability check.
- `MemberRepositoryPort::append()` exposes an `AppendOutcome` result vocabulary.
- `DecideAndAppend::run` maps append outcomes to application outcomes, but its switch has no default or otherwise verified exhaustive handling for future outcomes.
- PostgreSQL rollback and the complete behaviour of the proposed fix have not yet been verified by the authorized integration evidence.

These are findings from the previous report. Verify the relevant code and current repository state before relying on them.

### Objective

Design the smallest safe solution to Option E:

Make the persistence representability contract explicit and testable, so the PostgreSQL adapter and its test double agree, without imposing a new business calendar rule or changing the general temporal abstraction.

Distinguish these five concerns throughout the analysis:

- **L1 — Technical representability:** whether a date can be represented by the persistence conversion.
- **L2 — Persistence-contract consistency:** whether the production adapter and test double honour the same contract.
- **L3 — Domain calendar validity:** whether Membership business rules permit the date.
- **L4 — Business plausibility:** whether the date makes sense relative to organizational history or other authoritative facts.
- **L5 — Backdating and scheduling:** whether an effective date may precede or follow a decision date.

Option E addresses L1/L2. Do not silently decide L3/L4/L5.

## 4. Mandatory phase: PLAN ONLY

Do not implement anything in this phase.

Do not:

- Edit source code, tests, documentation, configuration, or plans.
- Run tests, scripts, migrations, database commands, or provisioning commands.
- Access or change a database.
- Stage, commit, push, or create branches.
- Update project-state files such as `CONTEXT.md` or `CLAUDE.md`.
- Claim that planned tests have passed.

Read-only repository inspection is permitted. State the repository HEAD and working-tree status if available through safe inspection. Clearly distinguish observed facts from inferences and recommendations.

## 5. Investigate the exact design boundary

Inspect the relevant repository port, result types, adapters, test doubles, application handler, domain types, and existing contract tests.

Answer these questions with exact file and symbol references:

1. Where should the storage representability contract live, and why?
2. Can both the PostgreSQL adapter and in-memory test double enforce the contract without modifying `TimePoint` or weakening temporal-algebra tests?
3. Should the range be expressed through a shared contract definition, a result type, a validation abstraction, or another existing pattern? Prefer existing project conventions and avoid unnecessary abstractions.
4. What exact range is supported by the actual persistence conversion, and what evidence establishes its inclusive boundaries?
5. At what point must the check occur to guarantee that a rejected append does not change the member version, event log, or other persisted state?
6. How should the result be represented so a technical persistence limitation is not mistaken for a domain refusal?
7. How will every `AppendOutcome` be mapped exhaustively?
8. Can an unhandled outcome still become `ConcurrentModification` or trigger a misleading retry? Design a test that prevents this.
9. Are there other append paths, date-bearing facts, or persistence entry points that need the same contract?
10. Which assumptions remain unverified until PostgreSQL integration testing?

Do not assume the repository port is the correct location merely because it is convenient. Compare alternatives and justify the selected boundary.

## 6. Requirements and failure semantics

Specify the behaviour for:

- Dates immediately outside the representable range.
- The first and last supported dates.
- `effectiveAt` on admission.
- A finite `initialTermUntil` on admission.
- `effectiveAt` on ending membership.
- Any other date-bearing fact accepted by the same persistence operation.
- Infinity or other general temporal values, where applicable.
- A rejected append, including the required absence of state changes.
- Every possible `AppendOutcome`.
- The distinction between a domain refusal, a technical persistence-contract refusal, a concurrency conflict, and an unexpected infrastructure failure.

Use the existing result and error conventions where suitable. Do not invent user-facing wording or new domain terminology without justification.

## 7. Test-first implementation plan

Produce a precise, ordered test plan before proposing implementation steps.

Include:

1. A contract test that demonstrates the current test-double discrepancy.
2. Tests for both lower and upper representability boundaries.
3. Tests for all relevant date-bearing fields.
4. Tests proving a rejected operation leaves repository version and log unchanged.
5. Application tests for mapping the new result to an explicit application-level outcome.
6. Exhaustive coverage of every append outcome, preventing misleading retries or concurrency errors.
7. Regression tests for existing domain, application, architecture, and temporal-algebra behaviour.
8. PostgreSQL integration tests reserved for the separately authorized database phase.

For every test, state:

- The requirement or invariant it protects.
- The current expected failure, if any.
- The expected result after implementation.
- Whether PostgreSQL is required.
- The evidence to capture, including the actual process exit code.

Do not report test success until a test has actually been run and its result verified.

## 8. Keep business decisions separate

Prepare a comparison of:

- A — Retain the current infrastructure conversion guard only.
- E — Explicit persistence representability contract shared by the production adapter and test double.
- B — Add an explicit domain calendar-range rule.
- C — Add a business plausibility rule based on authoritative organizational history.
- D — Adopt B now and consider C later.

Explain that E can address L1/L2 without automatically deciding L3/L4. If B or C remains desirable, specify the evidence and Human PO/ARB decision required.

Do not place this decision under Slice 2's D-5(b) if that identifier concerns backdating or scheduling. Propose a separate decision-register identifier only as a proposal, pending approval.

## 9. Migration and re-entry implications

Keep legacy-data interpretation separate from Option E.

Explain why a valid and storable date such as `9999-12-31` might still represent a legacy open-ended placeholder and potentially block re-entry under Membership invariants.

Do not automatically reinterpret, correct, annul, or void historical facts.

Specify the future migration evidence needed:

- Candidate placeholder values and their frequencies.
- Source provenance and ambiguity.
- A dry-run report for registrar review.
- An approved migration contract for finite ends, open-ended terms, and ambiguous values.
- Explicit tests protecting period overlap and re-entry invariants.

Do not access production or real user data in this planning phase.

## 10. Research and machine-learning discipline

Treat the Membership research corpus as brainstorming and evidence, not as an unquestionable collection of approved requirements.

If useful, propose a later offline analysis that clusters related concepts, identifies duplicate or contradictory statements, and traces candidate invariants to implementation and tests.

Machine-learning output must remain exploratory:

- It may identify patterns, omissions, and candidate contradictions.
- It must not decide Membership policy, assign legal authority, or silently resolve contradictory requirements.
- Every material finding must be checked against source documents and recorded decisions by a human reviewer.

Do not undertake broad corpus processing in this increment unless it is necessary to answer the Option E design question.

## 11. Required deliverable

Return a plan containing:

1. Executive summary and recommended next action.
2. Verified repository facts with file/symbol references.
3. Assumptions and open questions.
4. Alternatives for locating and enforcing the persistence contract.
5. Proposed contract and failure semantics.
6. Test-first implementation sequence.
7. Acceptance criteria and required evidence.
8. Regression and PostgreSQL integration boundaries.
9. Risks, dependencies, and explicit non-goals.
10. Human PO/ARB decision sheet, with every undecided verdict left blank.
11. A proposed implementation scope that can be authorized as one small increment.
12. A short statement of what must be reviewed by ChatGPT before implementation.

Mark each material statement as **Observed**, **Inferred**, **Recommendation**, or **Decision Required** where useful.

## 12. Stop condition

Stop after producing the plan.

Make no edits, run no tests, access no database, and create no commits.

Wait for ChatGPT's independent review, required plan revisions, and explicit Human PO/ARB architecture and implementation authorization before beginning any implementation.

The next implementation must be a separate, explicitly authorized phase.

## 4. Where we are in the Membership implementation

Based on the attached report and the previously available project status, the most accurate summary is:

The Membership rules core is built and tested to a meaningful degree, but the operational Membership product is still ahead of us. Repository persistence, complete application workflows, integration, migration, and UI remain substantial work.

The report estimates 4 of 17 planned slices implemented in whole or in part. That is an inventory estimate, not a defensible percentage of total implementation completion. The report also states that zero slices have been formally accepted, integrated, or put into production. Those status statements should be rechecked against the repository and gate register before being treated as current.

## 5. Remaining todos, in dependency order

### Immediate: architecture and governance

- Have Claude produce the Option E implementation plan under the read-only restrictions above.
- Independently review the plan and revise it if the persistence boundary, error semantics, or test strategy is inadequate.
- Human PO/ARB to finalize the Option E architecture and explicitly authorize the implementation increment.
- Decide separately whether to adopt a domain calendar rule (B), a business plausibility rule (C), both in sequence (D), or neither.
- Record the formal G3 verdict for C-1, and formal acceptance verdicts for Slice 0 and Slice 1a, based on their required evidence.
- Decide the order and ownership of the outstanding Slice 2 requirements decisions, including consent, record/recovery semantics, and backdating/scheduling.

### Technical correctness and persistence

- Implement the approved Option E change test-first, if authorized.
- Ensure every append outcome is exhaustively mapped and cannot be misreported as a concurrency conflict.
- Preserve temporal algebra and the general `TimePoint` abstraction.
- Complete the isolated PostgreSQL safety/provisioning prerequisites before authorizing Part 2.
- Verify PostgreSQL append behaviour, rollback, and version consistency with captured evidence.
- Complete PBDIGIT-264 F-2 through F-5 and then reconsider the disposition of Slice 1b.
- Triage wider-suite failures with actual output, exit codes, root-cause classification where possible, and explicit dispositions.
- Resolve PBDIGIT-266 and determine the disposition of optional PBDIGIT-267/269 based on their current plans and dependencies.

### Domain and application implementation

- Complete Slice 2 command and requirements decisions.
- Complete Slice 3 and Slice 8 in the approved dependency order.
- Implement remaining correction semantics, including C-2, C-3, and C-4, with explicit invariants and tests.
- Implement the required application queries and workflows.
- Complete integration across Slices 9–12, including the approved interactions with other authoritative contexts.
- Ensure Membership does not itself grant voting entitlement; keep that distinction enforced in domain rules and integrations.

### Migration, UI, and release readiness

- Define and approve the Slice 13 migration contract, including legacy open-ended placeholders and ambiguous historical values.
- Produce a migration dry-run report and ensure ambiguous records require an authorized registrar decision rather than automatic reinterpretation.
- Protect period overlap and re-entry invariants through migration and application tests.
- Implement Slice 14 UI and operational workflows.
- Complete Slice 15, full regression, security review, acceptance evidence, and cutover readiness.
- Update `CONTEXT.md`, active plans, and project-state documentation only through an explicitly authorized state-sync step.
- Establish a measurable completion rubric based on accepted requirements, tested invariants, integrated workflows, migration readiness, and release gates.

### Research and theory development

- Maintain traceability from corpus statements to candidate requirements, approved decisions, domain invariants, tests, and implementation.
- Use offline clustering or contradiction detection only where it provides a concrete research benefit.
- Validate machine-learning findings against original source material and human decisions.
- Convert unresolved research questions into explicit decision requests instead of allowing assumptions to become code.

My recommendation: start with the Option E planning prompt above. It addresses a specific verified technical gap while preserving the distinction between engineering constraints and Membership policy. Do not start the next implementation until the architecture has been independently reviewed, finalized, and explicitly authorized.