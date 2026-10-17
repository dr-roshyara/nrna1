Yes — we should continuously maintain the PublicDigit Membership backlog, but through controlled traceability, not by creating new stories for every issue we discover.

The important distinction is between keeping stories accurate and complete, and expanding the authorised delivery scope. Our existing backlog governance requires preserving the authoritative `PBDIGIT-00…208` story set, with new IDs only for delivery obligations that are not already covered. The earlier strategic traceability audit also explicitly prohibited modifying stories or creating `PBDIGIT-209+` during that audit.&#x20;

For the current Membership work, we should apply that discipline continuously.

## 1. What should happen to Membership stories?

| Situation                                             | Required action                                                                                              |
| ----------------------------------------------------- | ------------------------------------------------------------------------------------------------------------ |
| A story no longer matches an approved domain decision | Update its requirements and acceptance criteria, preserving traceability.                                    |
| A story is missing a delivery obligation              | Search existing stories first; add a new story only if no existing story owns it and creation is authorised. |
| A new architectural question is discovered            | Record it as an open question or follow-up; do not silently add it to the active implementation scope.       |
| A story is implemented                                | Record status, commit, test evidence, and remaining limitations; then reassess its dependants.               |
| A story depends on an unresolved decision             | Mark the dependency and gate explicitly rather than pretending the story is ready.                           |
| A story overlaps another story                        | Reconcile ownership and dependencies before creating a duplicate.                                            |

We must continue to preserve the Membership domain distinctions, especially Registration ≠ Voter Admission ≠ Voting Entitlement. Terminology improvements should not become unapproved scope expansion.&#x20;

## 2. What is missing from the current workflow?

Claude has prepared `PBDIGIT-268` for C-1 and its implementation plan. That is useful, but the report does not establish that the complete Membership story inventory has been checked for consistency with the latest decisions.

I recommend adding a recurring Membership Story Traceability Check to our delivery process. Each implementation slice should verify:

- Which existing stories are affected by the decision?
- Are acceptance criteria, dependencies and status still correct?
- Are required follow-up stories already covered?
- Are there uncovered delivery obligations that genuinely require new stories?
- Do open questions and exclusions remain visible?
- Are the backlog index and project context synchronised without overwriting other sessions' work?

## 3. What I would ask Claude to do now

Start with a read-only audit of Membership stories. Do not interrupt the current C-1 approval gate or begin implementation as part of this audit.

# PublicDigit Membership — Continuous Story Traceability Audit

## Objective

Audit the existing PublicDigit Membership backlog against the currently approved Membership domain decisions, contracts, architectural constraints, and delivery obligations.

This is a backlog-consistency audit, not authorisation to implement additional scope.

## Instructions

1. Inspect the authoritative backlog and story index, all relevant Membership stories, the approved Membership contracts, decision records, open questions, current C-1 gate record, and C-1 plan.
2. Establish a traceability matrix containing:
   - Story ID and title
   - Current status
   - Relevant domain rule or decision
   - Acceptance-criteria consistency
   - Dependencies and blockers
   - Missing delivery obligations
   - Recommended disposition: KEEP, UPDATE, SPLIT, MERGE, CREATE FOLLOW-UP, or NO ACTION
   - Evidence supporting the recommendation
3. Check specifically that:
   - One Member per organisation/person is preserved.
   - Membership periods, re-entry, append-only facts, valid time, and known-at reconstruction remain consistent.
   - Membership, appointments, mandates, election registration, voting entitlement, and exercisability are not conflated.
   - D-COR-2 and D-COR-5 are reflected wherever relevant.
   - C-1 remains limited to Annul/Void in the existing Membership kernel.
   - Correct, correction-generated consequences, persistence changes outside the approved slice, cutover, and live-write enablement are not silently included in C-1.
   - CQ-9 and other unresolved decisions remain explicitly unresolved.
   - Dependencies, blockers, story status, and backlog-index entries agree with the evidence.
4. Search existing stories before recommending any new ID. Preserve the authoritative backlog and create new stories only for genuinely uncovered delivery obligations, following the applicable ID and approval rules.
5. Distinguish approved requirements from proposals, derived invariants, test evidence, and unresolved questions. Do not convert a recommendation into an approved requirement.

## Change control

For this first audit, make no story or backlog-index changes. Do not create new story IDs, change implementation scope, or start C-1.

Return the traceability matrix, concrete inconsistencies, proposed minimal changes, genuinely uncovered obligations, and any decisions requiring PO or ARB approval.

After review, wait for authorisation before applying backlog changes. Preserve unrelated work from other sessions.

## Ongoing rule

At the close of each Membership delivery slice, repeat a focused traceability check for the affected stories and their dependencies. Record evidence, update authorised story status and acceptance criteria, and identify uncovered obligations without expanding the active implementation scope.

## 4. My recommendation

We should make this part of the standard Membership workflow rather than treat it as a one-time cleanup.

The immediate sequence is:

1. Review and approve the C-1 plan, including the three design points.
2. Have Claude re-evaluate the gate.
3. Run the read-only Membership story traceability audit either before implementation begins or as a separately controlled task.
4. Resolve any genuine story inconsistencies and obtain approval for any newly identified delivery obligations.
5. Start C-1 only when its gate is closed.

That gives us two complementary controls: the implementation gate protects the current change, while continuous story traceability protects the integrity of the larger Membership architecture and backlog.