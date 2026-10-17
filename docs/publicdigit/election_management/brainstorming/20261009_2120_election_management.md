# PublicDigit: Architecture analysis and next-step instructions for Claude

My recommendation is to continue with a controlled, evidence-driven election engineering process, rather than immediately implementing additional election features.

Your goal is to build a digitalisation platform for organisations and political parties, with online voting as a core capability. That means the platform must do more than record votes: it must establish who is entitled to participate, which election rules apply, when each action is permitted, how decisions are authorised, and how the outcome can be independently verified without compromising ballot secrecy.

The attached material gives us two important perspectives:

- PBDIGIT-256: correcting election test fixtures so they represent the actual, derived election lifecycle.
- The wider PublicDigit audit: identifying the difference between implemented code, tested capabilities, documented intentions, unresolved governance decisions, and genuinely missing functionality.

These must be treated as related but distinct workstreams. The next engineering step should not silently expand the scope of PBDIGIT-256 into a redesign of the election platform.

## 1. What the attached material tells us

PBDIGIT-256 — Lifecycle fixture corrections

Reported GREEN

The report states that 19 failing tests were corrected through fixture changes, without production-code changes or weakened assertions. The fixes establish approved-candidate, voting-open, lifecycle-phase, authenticated-chief and tenant-context facts required by the existing rules.

Derived lifecycle versus persisted state

Unresolved

The election lifecycle engine derives state from facts, while the audit's `from_state` is reportedly read from the persisted state column. When time advances the derived state without a persisted transition, the audit can become stale. This is a separate production-design question.

Committee dispute resolution

Incomplete capability

The attached investigation reports a tested dispute/adjudication process and a separate committee acceptance-gate voting engine, but no implemented connection between them. Collective dispute decisions, quorum, majority and recusal remain unresolved.

Candidate agents and observers

Design gap

The report says candidate representation is described in the manifesto but lacks the corresponding implemented relationship, enforcement, candidate-group model and complete access rules. It also identifies an unresolved distinction between an observer and a representative with decision rights.

These are findings from the supplied reports, not independent verification of the current Git tree. In particular, the material does not establish that the reported test changes are committed or that all relevant suites pass on the current repository state.

## 2. Architectural assessment: what should we do next?

From a senior DDD and requirements-engineering perspective, I would separate the work into three levels.

Level 1 — Establish a trustworthy baseline

Immediate priority

Verify the actual Git state, PBDIGIT-256's test evidence, the active five-story plan and the current project instructions. Resolve any mismatch between the report and the repository before beginning another implementation.

Level 2 — Establish lifecycle and audit correctness

Next technical investigation

Investigate the difference between derived lifecycle state and persisted state, including the audit `from_state` finding. Determine whether the defect is reproducible, which invariant is violated, and whether an existing story owns it.

Level 3 — Build the complete election process

Strategic direction

Connect eligibility, election setup, nominations, voting, counting, results, challenges and authoritative decisions through explicit business rules and verified application paths. Respect existing constitutional decisions and dependencies.

The most important principle is that a green test suite is evidence of correctness only to the extent that the tests exercise the correct business invariants and real execution paths.

The supplied audit reinforces this: PBDIGIT-207 reports that some feature tests asserted HTTP success without verifying the resulting business state. PBDIGIT-208 also reports a substantial failing legacy test estate. Those are reasons to improve evidence quality, not reasons to weaken assertions or attempt a broad, uncontrolled repair.

## 3. Copy-ready instructions for Claude Code

The following prompt directs Claude to establish the next legitimate step, investigate the lifecycle/audit discrepancy and continue the authorised work without crossing governance boundaries.

# PublicDigit — Next Engineering Commission

## Election lifecycle integrity, test evidence and DDD-guided progression

### 1. Your role

Act as a senior Domain-Driven Design architect, requirements engineer, election-process researcher, software verification specialist, computer-logic researcher, mathematician and statistician.

We are developing PublicDigit, a digitalisation platform for organisations and political parties. Online voting is a core business capability.

Your responsibility is to improve the correctness, security, explainability and operational completeness of the election process. Treat the existing repository, domain models, tests, architectural decisions, constitutional rules, implementation plans and backlog as an evidence corpus.

Do not treat brainstorming notes, generated audit reports, proposed rules or unratified manifesto statements as automatically binding business requirements.

Work from evidence. Distinguish established facts, derived conclusions, hypotheses, unresolved questions and decisions requiring human authority.

### 2. Primary objective

Continue the existing election engineering programme from its actual repository state.

The immediate objective is to determine the next authorised engineering step following PBDIGIT-256 — Election test module, story 2: lifecycle fixtures — and then perform only the work authorised by the existing plan and governing instructions.

Investigate the reported production finding concerning derived election lifecycle state and persisted audit state. Determine whether it is reproducible, how it affects election correctness and auditability, and which existing story or decision owns it.

The long-term objective is a practically implementable election process with:

- explicit eligibility and authorisation;
- a coherent, fact-derived election lifecycle;
- constitutional transition rules;
- correct opening and closing of voting;
- ballot secrecy and voter anonymity;
- reliable counting and result publication;
- traceable administrative decisions;
- contestation and adjudication;
- tenant isolation;
- reliable event publication and persistence;
- meaningful automated verification;
- operationally usable interfaces and procedures.

Do not attempt to implement all these capabilities in this commission.

### 3. Phase 0 — Establish the actual repository state

Before changing anything, read the applicable repository instructions, including CLAUDE.md files, CONTEXT files, active plans, the session log, backlog entries, relevant ADRs and architectural rulings.

Inspect at least:

- docs/plans/20261007-0006-election-test-module-green-plan.md
- the PBDIGIT-256 backlog story and adjacent stories in the same plan;
- ElectionLifecycleEngineImpl;
- ElectionConstitution;
- ConstitutionalTransitionGuard;
- the election model and its current-state accessors;
- the relevant audit implementation;
- tests changed by PBDIGIT-256;
- the repository's current Git state.

First inspect `git status --short`, the current HEAD, recent relevant commits and the diff of every file involved in PBDIGIT-256.

The supplied report states that PBDIGIT-256 corrected 19 failing tests by fixing fixture facts, without production changes or weakened assertions. Treat this as a claim to verify, not as proof of the current repository state.

A separate session may have committed membership changes and left unrelated work uncommitted. Do not assume that the reported working tree or commit hash remains current.

Establish:

1. Which PBDIGIT-256 changes are committed and which remain uncommitted.
2. Whether the reported 19 test failures are resolved on the current tree.
3. Whether the test changes preserve their original assertions and intended business rules.
4. Whether the shared scenario factory produces valid, explicit election facts.
5. Which story in the five-story plan is actually next.
6. Which dependencies, approvals and verification gates apply.

Do not reset, stash, discard, amend or overwrite anyone's changes. Do not include unrelated membership or backlog changes in an election commit.

If PBDIGIT-256 still requires Product Owner approval to commit, do not commit it. Report the evidence and stop before any unauthorised commit.

### 4. Phase 1 — Validate the lifecycle model

Reconstruct the actual lifecycle from the implementation and its tests.

Inspect how the following facts influence the derived state:

- election approval;
- approved candidacies;
- nomination and setup completion;
- explicit opening of voting, including the `voting_locked` fact;
- voting-window start and end;
- suspension and resumption;
- counting;
- results publication.

Do not assume that the list above represents every required fact. Derive the complete rule set from the authoritative code and adopted decisions.

Build a lifecycle decision table containing:

| Item               | Required evidence                                                  |
| ------------------ | ------------------------------------------------------------------ |
| Source facts       | Persisted attributes, related records, clock and tenant context    |
| Preconditions      | Facts that must hold before a transition or derived state is valid |
| Derived state      | Exact state produced by the lifecycle engine                       |
| Authoritative rule | Constitutional rule, ADR or adopted business decision              |
| Negative case      | Missing, contradictory or invalid facts                            |
| Test evidence      | Existing tests and missing coverage                                |
| Unresolved issue   | Owner and decision needed                                          |

Check especially that a negative test actually exercises the rule it claims to verify.

For example, EM-VOT-002 must be tested with voting otherwise eligible to open, so that the absence of an approved candidate is the deciding reason that prevents VotingActive. An election that cannot enter VotingActive for an unrelated reason does not establish that EM-VOT-002 works.

Use explicit scenario facts rather than relying on factory defaults or ambient state.

Do not duplicate business rules in fixtures, production services or tests. Keep fixture construction separate from the authority that decides lifecycle state.

### 5. Phase 2 — Investigate the derived-state/audit discrepancy

The supplied PBDIGIT-256 report records this unresolved finding:

`Election::getCurrentStateAttribute` reportedly reads the persisted `state` column, while authorisation uses the state derived by the lifecycle engine.

If time advances an election from voting to counting without a persisted transition, an audit record may contain a stale `from_state`.

Investigate this as a potential correctness and audit-integrity defect.

#### Required analysis

A. Trace every relevant read and write path.

Establish which component:

- derives the current lifecycle state;
- reads the persisted state;
- authorises a transition;
- creates an audit record;
- persists a transition;
- handles clock-driven state changes;
- emits domain events or outbox records.

B. Construct a minimal reproducible scenario.

Use a controlled clock and explicit election facts to show:

1. The election's derived state before the voting deadline.
2. The derived state after the deadline.
3. The persisted state at each point.
4. The state supplied to the authorisation decision.
5. The actual audit `from_state` and `to_state`.
6. Whether an audit record is produced at all if no explicit transition occurs.

Do not claim a defect is reproduced merely because the code appears suspicious.

C. Determine the intended semantics.

Answer the following separately:

- Does the audit record describe an explicit command, an actual persisted transition or an observed lifecycle change?
- Is a time-derived state change itself an auditable event under the adopted constitution?
- Can `from_state` and `to_state` truthfully describe a transition if the previous state was never persisted?
- Does the implementation permit two competing authorities for lifecycle state?
- Are state names and transitions consistent with the constitutional vocabulary?

D. Evaluate alternative designs without choosing one prematurely.

Consider, where supported by the existing architecture:

1. A canonical derived-state query used consistently by authorisation and audit.
2. Explicitly persisted transitions with transactional audit records.
3. An audit model that distinguishes commanded transitions from time-derived observations.
4. A scheduled transition mechanism, but only if the domain rules require one.

For each option, explain its invariants, failure modes, concurrency implications, transaction requirements, migration consequences and testing obligations.

Do not silently introduce a scheduler, change the meaning of existing audit records, or persist derived state merely to make tests pass.

E. Find the owning story or decision.

Search the current backlog, plans, ADRs and governance records. Avoid duplicate tickets.

If no existing story owns the defect, prepare a proposed story with evidence, business impact, acceptance criteria, dependencies and the required Product Owner or architecture decision. Do not implement it without authorisation.

### 6. Phase 3 — Election correctness and formal reasoning

Use formal reasoning where it improves the quality of the design.

Express the critical lifecycle rules as predicates or preconditions. For example:

VotingActive implies that the election is explicitly opened, the applicable voting window is valid, an approved candidate exists where required, and no blocking suspension or constitutional prohibition applies.

Treat this as an illustrative invariant, not a substitute for the actual adopted rules.

For each invariant:

1. Identify its authoritative source.
2. Express the positive condition.
3. Construct counterexamples that violate one condition at a time.
4. Verify that the lifecycle engine rejects invalid combinations.
5. Check boundary times immediately before, at and after relevant deadlines.
6. Check contradictory and missing facts.
7. Test tenant and authenticated-actor boundaries.
8. Identify whether the property belongs in the domain, application, persistence or access-control layer.

Where practical, propose property-based tests and mutation tests to show that a test suite detects meaningful violations.

Use probability, statistics or machine learning only when they offer a measurable benefit. Do not use an ML model to decide constitutional eligibility, authorisation, vote validity or the election result when these require deterministic, explainable rules.

### 7. Phase 4 — Protect voting secrecy and audit integrity

When analysing any lifecycle or audit change, trace its implications for the existing anonymity constraints.

Verify that:

- voter identity is not introduced into vote events or anonymous ballot records;
- election and organisation boundaries are enforced;
- unauthorised users cannot invoke privileged transitions;
- audit evidence is truthful, immutable where required and attributable to the correct authority;
- duplicate requests and concurrent transitions cannot produce inconsistent outcomes;
- events and their required persistence obey the established transactional boundaries.

Do not introduce voter IP addresses, identity-to-ballot links, new tracking fields or additional personal data as a side effect of this work.

Do not assume that every audit requirement permits recording every available datum.

### 8. Phase 5 — Respect the existing architecture and backlog

Read the current backlog before proposing work.

The supplied completeness audit records that:

- PBDIGIT-190 concerns the future Voting migration and verifiability epic, whose opening depends on the epic-numbering decision and the stated constitutional-runtime prerequisites;
- PBDIGIT-182 is deferred pending the correction-loop dependencies and gate conditions;
- PBDIGIT-196 requires a Product Owner decision on challenge standing and the filing contract;
- PBDIGIT-207 concerns write-path tests that may report HTTP success without proving a business outcome;
- PBDIGIT-208 concerns the unowned remainder of the legacy test failures.

Re-verify these statuses against current files before relying on them.

Do not open a new epic, widen a ticket, bypass a blocked gate or treat a generated audit report as implementation authority.

Use capability and authority to determine where code belongs. Directory names alone do not determine bounded-context ownership.

Preserve the current architecture and methodology freezes unless an authorised decision explicitly changes them.

### 9. Required engineering workflow

For every authorised code change, follow the repository's required sequence:

RED → GREEN → independent verification → developer guide update.

1. Establish the original failing behaviour or write the smallest meaningful failing test.
2. Change only what is required to satisfy the accepted contract.
3. Preserve meaningful assertions. Never make a test green by weakening its expected behaviour.
4. Run the targeted test and relevant neighbouring tests.
5. Run the prescribed broader regression and architecture gates where feasible.
6. Report actual commands, exit statuses, failures and environmental limitations.
7. Update the relevant developer guide when required by the repository's Definition of Done.
8. Update the session log and active context or plan as required.
9. Inspect the final diff for scope contamination.
10. Stop at the required approval boundary.

Do not report a suite as green unless it was actually executed successfully on the stated tree.

If a broader suite fails, classify the failure before repairing it. Distinguish fixture drift, schema drift, obsolete behaviour, order dependence and genuine production defects.

### 10. Required deliverables

Produce a concise but evidence-rich engineering report containing:

**A. Current-state verification**

- HEAD and working-tree summary.
- PBDIGIT-256 commit and test status.
- Active plan and the actual next story.
- Applicable authorisations and blockers.

**B. Lifecycle specification**

- State/fact decision table.
- Critical invariants and their sources.
- Positive and negative test coverage.
- Identified inconsistencies.

**C. Audit discrepancy investigation**

- Source-level call-path evidence.
- Reproduction steps and actual results.
- Whether the defect is confirmed, falsified or inconclusive.
- Design options and consequences.
- Existing owner or proposed ticket.

**D. Risk assessment**

- Impact on election correctness.
- Impact on audit integrity and legal traceability.
- Impact on anonymity and tenant isolation.
- Concurrency, timing and persistence risks.

**E. Next-step decision**\
Recommend one next action, explain why it has priority, list prerequisites and define measurable acceptance criteria.

**F. Implementation evidence**\
If and only if authorised work is completed, report the changed files, tests run, results, documentation updates and remaining failures.

**G. Updated project state**\
Update the appropriate context, session log, plan and developer guide only as permitted by the repository instructions and current authorisation.

### 11. Stop conditions

Stop without implementing further changes if:

- the current tree does not match the reported state;
- the next story or its scope cannot be established;
- a Product Owner or architecture decision is required;
- the finding belongs to a blocked or deferred work package;
- a change could alter ballot secrecy or constitutional semantics without an accepted decision;
- unrelated working-tree changes cannot be safely isolated;
- the existing acceptance criteria are ambiguous.

In such cases, provide the evidence, the precise unanswered question, the decision owner and a proposed next action.

Do not commit, push or claim approval unless explicitly authorised.

### 12. Final instruction

Think like a researcher and a senior DDD architect: observe the corpus, reconstruct the actual domain behaviour, challenge assumptions with counterexamples, distinguish evidence from hypotheses, and improve the architecture through small, verifiable increments.

The goal is not merely to make tests pass or to increase the amount of code. The goal is a trustworthy election process whose rules, implementation, evidence and operational behaviour agree.

Begin with Phase 0. Establish the actual state, then proceed only as far as the existing authorisation permits.

## 4. How this fits the wider election roadmap

The prompt intentionally keeps the immediate investigation separate from future capability development.

| Workstream           | Objective                                         | Next action                                                                   |
| -------------------- | ------------------------------------------------- | ----------------------------------------------------------------------------- |
| Election test module | Trustworthy lifecycle tests                       | Verify PBDIGIT-256 and identify the next authorised story in its plan         |
| Lifecycle and audit  | One coherent account of state and transitions     | Reproduce and classify the stale `from_state` finding                         |
| Voting correctness   | Correct eligibility, casting and counting         | Respect the existing constitutional-runtime prerequisites                     |
| Contestation         | Challenge, adjudication and correction            | Follow the existing blocked and deferred decisions                            |
| Governance           | Candidate representation and collective decisions | Resolve the distinction between observers and decision-making representatives |
| Verification         | Tests that prove actual business outcomes         | Address the relevant existing test-debt stories without duplicating ownership |

The audit's proposed Voting migration epic, PBDIGIT-190, should not be treated as automatically open for implementation. Its supplied specification identifies prerequisites, including the A2 authority switch at PBDIGIT-117 and the v1.0 freeze at PBDIGIT-132, subject to any later authoritative ruling.

## 5. My recommendation

Give Claude the prompt above and require it to complete Phase 0 before touching production code. Then let the evidence determine whether the lifecycle/audit discrepancy is the next authorised item or whether the existing five-story test plan takes precedence.

This preserves your core engineering principle: the business rules, domain model, implementation, tests and audit evidence must all describe the same election process. It also prevents an apparently small test-fixing task from accidentally changing constitutional behaviour.

The supplied reports provide a useful starting corpus, but they do not independently establish the current repository state. The next step must close that evidence gap before any broader architectural conclusion is treated as verified.