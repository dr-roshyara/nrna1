# Membership: what should we do now?

My recommendation as Principal DDD Architect and independent reviewer is to finish the safety and approval gates first, then implement the smallest independently testable increment. We should not start the PostgreSQL verification or broader Membership implementation yet.

The attached plan is now at Revision 3 — PROPOSED, NOT APPROVED FOR EXECUTION. It addresses important weaknesses in earlier versions: it distinguishes inspected connection paths from assumptions, introduces an independent database-permission boundary, requires provisioning evidence before the PostgreSQL run, and makes the containment check fail closed.&#x20;

Eingefügtes Markdown.md



## 1. My review: where we stand

Architecture and domain model

The core Membership architecture and principal domain distinctions have been established. The current increment is a bounded infrastructure/date-conversion defect, not permission to reopen the overall domain design.

Targeted test evidence

The previously reported Domain, Application and Architecture test groups passed. That evidence does not establish that the wider Membership suite passes or that PostgreSQL persistence is verified.

Revision 3: close the remaining plan-level questions

Before execution, confirm the plan file is internally consistent and that its proposed database role has the permissions it needs on the isolated test database while remaining unable to damage `nrna_test`. The attached execution transcript contains signs of overlapping edits in intermediate output, so the final repository version should be checked explicitly.&#x20;

Eingefügter Text (2).txt



Formal decisions remain pending

G3 and the Slice 0/Slice 1a dispositions are not recorded as accepted. Slice 1b remains deferred. A technical recommendation from me is not a Human PO/ARB approval.

### One additional technical requirement

The plan says the dedicated role must be able to run the isolated PostgreSQL tests, including migrations, while lacking destructive privileges on `nrna_test`. Before execution, the provisioning specification should state exactly what ownership and schema permissions the role needs in the isolated database. Otherwise, the test may be safely isolated but unable to create, migrate or drop its own test schema.

Keep the two security questions separate:

- Can the test role run the required migrations in the isolated database?
- Can that role be prevented from connecting to or destructively modifying the shared `nrna_test` database?

The second must be established by independently checked permissions—not merely by trusting the Laravel connection guard. The plan already recognises the need for a separate provisioning-evidence review.&#x20;

Eingefügtes Markdown.md



## 2. Prompt for Claude: finalise the plan before implementation

Send Claude the following instruction. It deliberately authorises plan review and correction only, not execution.

# Next Step — Membership: Finalise Revision 3 Before Execution

## Roles and decision authority

- **Claude:** Repository investigator and implementation engineer. Inspect evidence, correct the plan, identify technical risks, and implement only after explicit approval.
- **ChatGPT:** Independent Principal DDD Architect and requirements reviewer. Critically review the proposed plan, domain boundaries, invariants, database isolation and test strategy. Do not treat recommendations as formal acceptance.
- **Human PO / ARB:** Own scope, risk acceptance, approval gates and permission to execute.

## Objective

Finalise the existing plan:

`docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md`

Baseline:

`ce8bf3109db3acfcc7551c6baa2f609078b2f659`

The plan concerns PBDIGIT-264 F-1, the far-date defect, PostgreSQL verification and evidence for reconsidering Slice 1b.

Do not redesign the Membership domain or expand the scope.

## Phase 1 — Plan integrity and technical review only

1. Inspect the actual current plan and its repository diff. Verify that the final document is internally consistent, has no duplicated or truncated text, and accurately describes Revision 3. Repair only the existing plan if necessary.
2. Verify the proposed isolated PostgreSQL role requirements:
   - It can create, migrate and clean up the schema in its dedicated test database.
   - Its ownership, schema privileges and role memberships are specified explicitly.
   - It cannot destructively modify the shared `nrna_test` database.
   - The `CONNECT` restriction, including the effect of any `PUBLIC` grant, is explicitly verified.
   - Infrastructure responsibilities and unresolved decisions are clearly identified.
3. Review the proposed isolation mechanism critically:
   - Distinguish inspected code paths from inferred behaviour and unverified assumptions.
   - Examine the effect of environment overrides, Laravel configuration, connection resolvers, reconnects, forked processes, read/write connections and possible bypass paths.
   - Confirm that the plan does not rely on the resolver as the sole protection.
   - Ensure no destructive test command can run until the independent isolation boundary and provisioning evidence have been accepted.
   - Never read `.env`, reveal credentials or record secrets.
4. Verify that Part 3 requires explicit authorisation for each named database, correct database identity, verified SELECT-only permissions and enforced read-only transaction mode. Any uncertainty must produce `NOT VERIFIED`, never a misleading zero result.
5. Preserve the approved scope and existing decisions. Do not reopen unrelated Membership architecture decisions, C-1 acceptance, C-2 to C-4, or Slice 1b.
6. Keep the far-date correction test-first:
   - RED: add boundary tests and capture the failing result.
   - GREEN: add the numeric range guard in `DateOrdinal::toDate()`.
   - Regression: run only the explicitly approved targeted test groups.
   - PostgreSQL verification remains a separate gate.

## Phase 2 — Report and stop

Update only the existing plan document if corrections are required. Report:

- The exact changes made and why.
- The remaining technical risks and infrastructure prerequisites.
- Whether the dedicated role can both run migrations in isolation and be prevented from damaging `nrna_test`.
- Which claims are inspected facts, inferences or unverified assumptions.
- The separate approvals required for Part 1, Part 2 and Part 3.
- The precise next decision required from ChatGPT and the Human PO / ARB.

## Strict limits

Until further explicit approval:

- Do not run tests, scripts, migrations or database queries.
- Do not connect to any database.
- Do not change application code, test code, schema or tracked configuration.
- Do not implement the far-date fix.
- Do not modify the review/disposition document or record any acceptance.
- Do not commit, push or start another implementation increment.

Leave the plan marked **PROPOSED — NOT APPROVED FOR EXECUTION**.

Wait for independent ChatGPT review and explicit Human PO / ARB authorisation before proceeding.

## 3. What happens after Claude responds?

Use this sequence. Do not combine the gates.

### 1

Claude finalises the plan

Plan-only work; no test runs, database connections or code changes.

### 2

ChatGPT reviews the corrected plan

I check the actual plan and diff, isolation requirements, test-first strategy and remaining assumptions.

### 3

Human PO / ARB authorises Part 1

Only then may Claude implement the far-date boundary tests and numeric guard, with RED → GREEN evidence and the specifically authorised regression tests.

### 4

Separate PostgreSQL gate

Provision the isolated database and role, independently verify the permissions and identity, review the evidence, and obtain separate authorisation before Part 2.

### 5

Reassess Slice 1b

Use actual PostgreSQL evidence and the applicable outstanding acceptance conditions to make a new Human PO / ARB decision. Do not infer acceptance from passing tests alone.

## 4. How far are we from the Membership implementation goal?

These are qualitative planning estimates, not measured completion percentages.

| Workstream                                                | Current assessment                                     |
| --------------------------------------------------------- | ------------------------------------------------------ |
| Core domain understanding and DDD architecture            | Largely established                                    |
| Core Membership domain and application implementation     | Substantially implemented, but not fully accepted      |
| Targeted unit/application/architecture evidence           | Reported passing                                       |
| Full Membership regression confidence                     | Incomplete; wider-suite errors remain unresolved       |
| PostgreSQL persistence verification                       | Not yet established on a verified isolated database    |
| Far-date defect                                           | Identified; proposed fix awaits approval and execution |
| Security, persistence and remaining acceptance conditions | Outstanding                                            |
| Overall production-ready Membership implementation        | Not yet demonstrated                                   |

My overall assessment: We have progressed beyond domain discovery into implementation and verification, but we are not at a point where the Membership implementation can be declared complete. A rough planning estimate is around two-thirds of the way through the overall goal, with lower confidence in persistence and production readiness than in the domain design. This is an estimate, not an audited metric.

## 5. Remaining todos

### Immediate priorities

- Ask Claude to finalise Revision 3 and resolve the remaining plan-level questions.
- Review the actual final plan and repository diff for consistency and correctness.
- Obtain explicit Human PO / ARB approval for Part 1.
- Implement the far-date boundary tests and guard using RED → GREEN.
- Capture test outputs, exit codes, environment/version evidence and hashes as specified in the approved plan.

### Persistence and security

- Provision a dedicated PostgreSQL test database and role.
- Verify that the role can perform required migrations in its own database.
- Verify independently that the role cannot destructively modify `nrna_test`; resolve the `CONNECT` restriction with infrastructure.
- Review provisioning evidence before authorising PostgreSQL tests.
- Run the specifically approved PostgreSQL verification only after the safety gates pass.
- Perform the separately authorised read-only containment check, reporting `CONTAINED`, `NOT CONTAINED` or `NOT VERIFIED`.

### Acceptance and implementation completion

- Resolve or explicitly disposition the outstanding wider Membership test failures.
- Reassess Slice 1b against its recorded re-entry criteria.
- Address the remaining PBDIGIT-264 items in their approved increments, without silently expanding the far-date fix.
- Complete the applicable persistence and security acceptance conditions.
- Record the formal G3, Slice 0 and Slice 1a decisions; keep them pending until the Human PO / ARB records the verdicts.
- Continue with the next Membership implementation increment only after its architecture, requirements, plan and acceptance criteria are finalised.

Research and mathematical-method recommendation: For this work, use explicit invariants, boundary-value analysis, property-based tests and independent differential checks. Machine learning may help explore or classify a large research corpus, but it should not decide whether a membership invariant holds or whether a database safety gate has passed. Those decisions need deterministic, reproducible evidence.

The immediate next action is therefore simple: send the prompt to Claude, review its corrected plan, and wait for the appropriate human approval before implementation.&#x20;