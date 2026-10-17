# PublicDigit Membership — independent Principal Reviewer review

My assessment is that Claude has produced a strong decision-readiness package. I agree with its overall direction, but I recommend correcting four points before the Human PO/ARB makes the next decisions. We should not start implementation yet.

The package correctly separates the existing implementation from formal acceptance, identifies the mismatch between the in-memory and PostgreSQL adapters, and keeps database verification behind explicit safety gates.&#x20;

Eingefügter Text.txt



## 1. What I agree with

- Resolve business decisions before implementing the next increment. The effective-date rule, G3 verdict, Slice 0 verdict, and Slice 1a verdict remain open.

- Keep the `TimePoint` abstraction general. The package explains that it supports infinity and that existing tests use extreme ordinals. A calendar restriction should not be added to this general-purpose temporal type without a demonstrated domain requirement.&#x20;

  Eingefügter Text.txt

- Use test-first implementation if option B or D is approved. The tests should demonstrate the expected refusal before the domain guard is introduced.

- Keep PostgreSQL verification separate. The database tests need the approved isolation, provisioning evidence, no-connect safeguard, and separate authorization.&#x20;

  Eingefügter Text.txt

- Keep machine learning optional. It can help find similar statements or potential contradictions in the research corpus, but it cannot decide business policy.

## 2. Four refinements I recommend

Refinement 1 — distinguish technical limits from business rules

Option B proposes a domain-level refusal for dates outside 0001-01-01 to 9999-12-31. That can make behaviour consistent across adapters, but we must establish whether this range is an approved domain rule or merely a current technical limitation.

The Human PO/ARB should decide the business policy; the architecture review should then determine the correct enforcement layer.

Refinement 2 — adapter parity testing needs more than one comparison

A differential test is useful, but it is not sufficient on its own. Test the domain decision outcomes at both date boundaries, immediately outside each boundary, and for finite `initialTermUntil`. Also verify that all relevant application paths enforce the same rule.

PostgreSQL-specific persistence and rollback assertions must wait for Part 2.

Refinement 3 — protect historical data and re-entry

The package correctly flags `9999-12-31` as a possible legacy placeholder for “no end date.” A new range guard will not detect that semantic misuse because the date is within the permitted range.

The migration contract must explicitly handle legacy placeholders, and the correction and re-entry rules must remain consistent. Do not silently reinterpret existing facts.

Refinement 4 — strengthen the regression-triage exit criterion

The proposed criterion says regression confidence is sufficient when every wider-suite error is classified and none touches `Organisational/`. That is too weak: a classified failure in another context can still break application workflows or integration.

Each failure should be assessed for impact on the next increment, its dependencies, and the relevant acceptance criteria. Classification alone does not mean a failure is harmless.

These refinements follow from Claude’s own decision sheet, legacy-data analysis, and triage proposal.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



## 3. Prompt for Claude — revise the decision package, then stop

The next action is not coding. Ask Claude to incorporate the independent review into its decision package, without editing project files. This gives the Human PO/ARB a clearer basis for deciding.

# PublicDigit Membership — Incorporate Principal Review and Finalize Decision Package

## Objective

Continue exclusively on the PublicDigit Membership workstream.

Incorporate ChatGPT's independent Principal Reviewer findings into the existing decision-readiness package. Prepare the final decision material for the Human PO/ARB.

This remains a **planning-only task**.

## Roles and authority

- **Claude:** repository investigator, domain researcher, requirements engineer, and implementation planner.
- **ChatGPT:** independent Principal Reviewer challenging domain correctness, architecture, logic, test strategy, and evidence.
- **Human PO/ARB:** final authority for business policy, architecture acceptance, residual-risk acceptance, and implementation authorization.

Neither Claude's recommendation nor ChatGPT's review constitutes Human PO/ARB approval.

## Required revisions to the package

### 1. Effective-date range decision

Retain options A, B, C, and D. Improve the analysis to distinguish:

- technical representability;
- consistent behaviour across storage adapters;
- domain-level calendar validity;
- business plausibility, including organisational founding dates;
- backdating and scheduling policy.

Do not assume that a technical storage limitation automatically defines business policy.

Explain the consequences of each option and recommend an approach for Human PO/ARB consideration, without selecting the policy on its behalf.

### 2. Test strategy

If option B or D is eventually approved, propose test-first implementation with:

- lower and upper valid calendar boundaries;
- values immediately outside each boundary;
- `effectiveAt` and finite `initialTermUntil`;
- admission and termination decision paths;
- all future operations that can introduce or change an effective date;
- consistent domain and application outcomes regardless of storage adapter.

Explain which tests can be performed without PostgreSQL and which require the separately authorized Part 2 environment.

A differential adapter test is necessary evidence of consistency, but do not present it as complete proof of all persistence or rollback properties.

### 3. Historical data and re-entry

Retain the finding that `9999-12-31` may be a legacy placeholder for an open-ended period.

Specify the migration question separately from the date-range question. Explain how migration must preserve the meaning of open-ended periods and how a mistaken finite end date can affect re-entry.

Do not reinterpret or change existing facts without an approved migration contract.

### 4. Regression triage

Strengthen the wider-suite triage plan.

For every failure, require:

- captured output and exit code, once execution is authorized;
- classification as known, new, or unclassified;
- relevant introducing commit or cause where evidence permits;
- affected component and dependent workflows;
- impact on the next increment and acceptance criteria;
- an explicit disposition or remediation plan.

Do not treat “classified” or “outside Organisational” as sufficient evidence that a failure is harmless.

Do not run tests under this instruction.

### 5. PostgreSQL safety gate

Keep all existing prerequisites:

- isolated database and dedicated role;
- accepted provisioning evidence;
- verified inability of the test role to connect to `nrna_test`;
- separate authorization for Part 2.

State precisely which evidence must be reviewed before testing begins. Do not access or provision a database.

### 6. Acceptance decisions

Prepare a concise decision sheet for:

1. G3 (C-1).
2. Slice 0.
3. Slice 1a.
4. Effective-date calendar-range policy.
5. Infrastructure request for Part 2.
6. Ordering of the outstanding Slice 2 decisions.

Keep the verdict fields blank. Clearly distinguish decisions that belong to the Human PO/ARB from technical recommendations that ChatGPT can review.

## Final deliverables

Return:

- the revised decision package;
- the four principal review refinements and how they were addressed;
- any remaining disagreements or missing evidence;
- the exact decisions required from Human PO/ARB;
- the proposed next implementation increment, conditional on approval;
- the dependency-ordered remaining backlog.

Do not modify the existing package or any other repository file. Do not run tests, scripts, or database operations. Do not stage, commit, or push.

**Stop after returning the revised package. Wait for Human PO/ARB decisions and explicit implementation authorization.**

## 4. How far are we from the Membership goal?

The current evidence supports the following assessment:

## Membership delivery status

# 4 of 17

Planned slices reported as implemented in whole or in part

# 0

Slices formally accepted, integrated, or in production use, according to the report

Business interpretation: The rules core exists and has substantial test evidence. The operational workflows, external integrations, migration, user interface, and formal acceptance remain ahead. These figures describe the current slice inventory; they are not a percentage of total business value delivered.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



### Remaining todos

- Incorporate the four independent-review refinements into the decision package.
- Obtain formal G3, Slice 0, and Slice 1a verdicts.
- Decide the effective-date calendar-range policy and record it under a new, verified decision identifier.
- Resolve the ordering and prerequisites of the Slice 2 business decisions.
- Request and review PostgreSQL provisioning evidence, including the no-connect safeguard, before separately authorizing Part 2.
- Verify PostgreSQL rollback behaviour and complete the outstanding PBDIGIT-264 F-2 to F-5 work under their respective approvals.
- Triage the wider-suite failures and determine their effect on acceptance and subsequent implementation.
- Implement Slice 2 commands and Slice 3 queries after the required decisions.
- Implement the remaining membership-application and correction workflows.
- Integrate with Governance, Election, Finance, and Audit while preserving the rule that Membership does not independently grant voting rights.
- Define and execute the migration and pilot-cutover plan, including legacy open-ended-date placeholders.
- Deliver the user interface and operational workflows.
- Complete regression, security, integration, and acceptance verification before any production-readiness declaration.
- Synchronize the project context, active plan, and decision records once their updates are authorized.
- Consider ML-assisted research-corpus analysis as an optional supporting activity, with all candidate contradictions reviewed by humans.

My recommendation: Have Claude revise the decision package and stop. Then we can review the final decision sheet and the Human PO/ARB can make explicit choices. Only after those decisions are recorded should Claude plan the next test-first implementation increment.&#x20;