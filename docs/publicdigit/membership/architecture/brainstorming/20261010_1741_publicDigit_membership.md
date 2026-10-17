# PublicDigit Membership — Principal DDD review and next step

## 1. My decision: do not accept C-1 yet

The audit report is positive: it reports that commit `76b1192de` conforms to the approved P1–P6 reconciliation instructions, that no unrelated changes were found in the commit, and that the three targeted test groups produced successful PHPUnit output. However, G3 remains pending, and PBDIGIT-268 is not formally accepted or closed. Those statuses must remain unchanged until the required review and Human PO/ARB decision.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



My recommendation is to take a small, controlled step before G3: resolve the authoritative-record traceability gap (SF-1), then prepare the formal C-1 acceptance decision.

This is a documentation and governance step, not permission to expand C-1, implement C-3/C-4, change the correction contract, or begin cutover.

## 2. What the audit establishes—and what it does not

Reported as verified

- Commit identity and parent match the audit's stated baseline.
- All 11 P1–P6 conformance checks passed.
- Reconstructing the two target documents from the parent plus the approved text reproduced the committed files exactly.
- The three other-session staged renames remained outside the commit.
- The audit found no changes in the excluded application, test, database, configuration, route, resource, model or ratification-act paths.&#x20;

  Eingefügter Text.txt



Still incomplete or limited

- SF-1: the authoritative register and §16 still contain wording that could be read as an outstanding Principal Reviewer condition, even though completion is recorded in the plan and session log.
- Two of the three targeted test groups have PHPUnit `OK` output but no captured exit code. The report explicitly distinguishes that output from a directly captured exit status.
- PostgreSQL persistence, a green full Membership suite, and several other evidence items remain unproven.&#x20;

  Eingefügter Text.txt



The key architectural distinction is that a test run, a completed wording review, implementation clearance, and formal G3 acceptance are four different facts. The authoritative record should make those distinctions auditable without implying that any one automatically establishes the others.

## 3. The next step: prepare SF-1 as a separate, minimal change

The audit proposes two additive clauses, without changing the existing approved wording:

1. In the authoritative register, row 6, record that the Principal Reviewer completed the wording review, Human PO/ARB cleared implementation, and commit `76b1192de` contains the implementation.
2. In §16, record that the wording review was completed and identify the implementation commit.

The audit recommends doing this before G3, but confirms that these changes have not been applied and require separate approval.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



I agree with the direction. Before changing an authoritative governance record, Claude should inspect the actual current text and prepare an exact, minimal plan. ChatGPT will review the plan; the Human PO/ARB will approve or reject it. Only then may Claude implement it.

## 4. Prompt for Claude — next step

# PublicDigit Membership — SF-1 Traceability Gap: Plan First, No Implementation Yet

## 1. Your role and the governance model

Work exclusively on the PublicDigit Membership workstream.

You are the implementation engineer and repository evidence investigator. ChatGPT acts as the independent Principal DDD Architect, requirements engineer, semantic reviewer, and reviewer of your proposed plan. The Human PO/ARB retains decision authority and formal acceptance authority.

Follow this sequence strictly:

1. Inspect the current repository state and authoritative records.
2. Prepare a minimal implementation plan for the SF-1 traceability gap.
3. Stop and submit the plan to ChatGPT for independent review.
4. Wait for ChatGPT's final review and explicit Human PO/ARB approval.
5. Implement only the approved plan.
6. Capture exact diffs and verification evidence.
7. Stop for the next review and formal decision.

**This assignment authorizes investigation and planning only. It does not authorize document edits, staging, commits, pushes, test reruns, or changes to implementation code.**

## 2. Current known baseline

The latest supplied audit reports:

- Branch: `knowelegeos-modelling`
- Current audited commit: `76b1192deb03a1ebfb101ac97c75b5f5fdbba21d`
- Parent: `21a2ff22f1de5a6bb5e39f5e2aaf4e9930fd7cd2`
- Reconciliation instructions P1–P6 reportedly conform to the approved plan revision 4.
- Commit `76b1192de` reportedly changes two target review documents, the reconciliation plan, and the session log.
- Three unrelated other-session staged renames must be preserved.
- G3 remains PENDING.
- PBDIGIT-268 is not formally accepted or closed.
- No C-3 or C-4 implementation is authorized by this task.

These are reported baseline facts. Verify the actual repository state before relying on them. If HEAD, branch, worktree, index, or target-document content differs, stop and report the discrepancy.

## 3. Task: investigate SF-1 and prepare a plan

The previous audit identified SF-1:

The authoritative register and §16 still contain the qualifier:

`subject to the Principal Reviewer's wording review`

The audit found that the wording review's completion and implementation clearance are recorded in the reconciliation plan and session log, but not explicitly in the authoritative register or its §16 summary. A reader of the authoritative record alone could therefore reasonably interpret the qualifier as an outstanding condition.

The audit proposed two additive updates, not yet approved or applied:

**A. Authoritative register, row 6 evidence cell**

Proposed appended wording:

`; wording review completed (the Principal Reviewer approved plan revision 4, 2026-10-10); implementation cleared by the Human PO / ARB, 2026-10-10; implemented in 76b1192de`

**B. §16 C-STATUS-1 row**

Proposed appended wording:

`Wording review completed; implemented in 76b1192de.`

Treat these as proposals, not instructions to edit. Verify the exact current cells and surrounding context before recommending them.

## 4. Required semantic analysis

Assess whether the proposed additions accurately distinguish:

1. Approval of the reconciliation plan and its conditions.
2. Completion of the Principal Reviewer's wording review.
3. Human PO/ARB authorization to implement the approved reconciliation.
4. Implementation in commit `76b1192de`.
5. Formal G3 acceptance, which remains pending.

Do not imply that implementation clearance constitutes G3 acceptance. Do not imply that C-1 has been accepted or closed.

Check whether the proposed text is factually supported by the existing authoritative records and approval history. If the evidence does not support a clause exactly as proposed, identify the missing evidence and recommend narrower wording. Do not silently reinterpret or fabricate an approval.

Preserve the historical qualifier unless an explicitly authorized decision changes it. Prefer additive traceability over rewriting the historical decision.

Do not create a new decision identifier unless there is a documented governance reason. Determine whether C-STATUS-1 is the correct existing reference.

## 5. Repository and scope safeguards

During this planning-only stage:

- Do not edit any file.
- Do not stage, unstage, commit, or push anything.
- Do not alter the three unrelated staged renames.
- Do not change application code, tests, migrations, database configuration, routes, resources, models, runner code, or ratification acts.
- Do not alter C-AMEND-1, §14.5, CQ-3, or the C-1 semantic contract.
- Do not begin C-2, C-3, C-4, PBDIGIT-269 implementation, migration, cutover, or reverse-projection work.
- Do not rerun tests in this assignment.
- Do not claim to have independently verified facts that you only found in a report.

Inspect only what is necessary to determine the exact proposed edit locations, current wording, provenance of the approvals, and impact of the two additions.

If the repository state is unsafe or ambiguous, stop rather than trying to repair it.

## 6. Deliverable: an implementation-ready plan

Create no files yet. Return the plan in your response, with these sections:

1. **Baseline verified** — actual branch, HEAD, worktree/index status, and target documents inspected.
2. **Evidence and provenance** — where the wording-review completion and implementation clearance are recorded; distinguish authoritative decisions from supporting traceability.
3. **SF-1 analysis** — why the current record is ambiguous and whether each proposed addition is supported.
4. **Exact proposed changes** — file, section/table row, existing text, proposed appended text, and rationale for each change.
5. **Semantic safeguards** — explicitly demonstrate that G3 remains PENDING and C-1 remains unaccepted/unclosed.
6. **Scope and preservation** — files permitted to change after approval, files explicitly excluded, and how unrelated staged work will be preserved.
7. **Verification plan** — exact diff checks, historical-text preservation checks, table-structure checks, status checks, and provenance checks to perform after implementation. Do not execute these checks now if they would require edits or test runs.
8. **Risks and open questions** — identify any uncertainty requiring ChatGPT or Human PO/ARB resolution.
9. **Approval gates** — state that ChatGPT must review the plan and the Human PO/ARB must approve it before implementation.
10. **Recommendation** — recommend approval, revision, or rejection with a concise rationale.

Keep the plan limited to SF-1. Do not bundle test reruns, unrelated cleanup, or new architecture decisions into it.

## 7. Stop condition

After presenting the plan, STOP.

Do not implement it until ChatGPT has reviewed the plan and the Human PO/ARB has explicitly approved the final version. Do not treat silence, a previous approval for P1–P6, or the existence of the proposed wording as authorization for this separate change.

## 5. What happens after Claude returns the plan?

The workflow should be:

## 1

Claude — plan and evidence

Inspect the actual register, §16 and approval provenance. Propose exact additive changes. No edits.

## 2

ChatGPT — independent review

Verify semantic correctness, authority, traceability, minimality and preservation of the distinction between implementation clearance and formal acceptance.

## 3

Human PO/ARB — approval

Approve, revise or reject the exact plan. Approval must be explicit.

## 4

Claude — implement approved changes

Make only the authorized edits, preserve unrelated work, and report the exact diff and verification evidence.

## 5

ChatGPT + Human PO/ARB — G3

Review the evidence package and make the formal C-1 acceptance decision. Documentation cleanup alone does not pass G3.

## 6. How far are we from the Membership implementation goal?

My assessment is that the C-1 correction slice is in the final evidence-and-acceptance stage, but the complete Membership implementation is not yet complete. The supplied audit supports a bounded C-1 implementation and targeted test results; it does not establish persistence correctness, whole-suite health or production readiness.

I would not assign a single overall percentage to the complete implementation from this evidence: the architecture, domain logic, persistence, integrations, migration and operational readiness have different completion criteria.

### Remaining TODOs

Immediate — C-1 and G3

- Resolve SF-1 with a separately approved, minimal traceability change.
- Keep the historical approval wording intact unless an explicit decision authorizes otherwise.
- Optionally rerun the three targeted test groups later, capturing exit codes and preserving the output as evidence.
- Prepare the G3 acceptance package, including the PR acceptance recommendation required by the contract.
- Review the residual risks and obtain the Human PO/ARB's explicit accept/reject decision.
- Only after acceptance, update PBDIGIT-268's status as authorized by the governance process.

Evidence and correctness

- Investigate the wider Membership suite errors (reported as 27 + 29); establish their ownership and root causes rather than assuming they are harmless.
- Run PostgreSQL persistence round-trip tests in an isolated test database under the proper C-4 authorization.
- Verify temporal reconstruction, membership-period non-overlap, re-entry semantics and fact-liveness invariants.
- Address the independent-oracle coverage gap for D-COR-2.
- Establish evidence for equal `recordedAt` values where required.
- Reassess the sensitivity runner and PBDIGIT-269 before relying on it as a trustworthy evidence mechanism.
- Distinguish developer-reported Infection results from independently reproduced results.

Outstanding domain and architecture decisions

- Resolve CQ-3, including the meaning and provenance of correction reason/authority evidence.
- Resolve BD-REV-ER and D-COR-3 through the established decision process.
- Plan C-2, C-3 and C-4 separately, with explicit entry criteria and approvals; do not infer their authorization from C-1.
- Preserve the existing rule that C-1 Annul/Void operations affect targeted fact liveness only and do not silently introduce Membership-period consequences.

Practical Membership implementation and rollout

- Validate the single-writer and reverse-projection strategy for the legacy mutable Membership model.
- Complete the migration and cutover plan under ARB-MEM-01, including rollback, reconciliation and operational evidence.
- Verify the boundaries between Membership, Governance, Election, Finance, Audit and service access.
- Demonstrate that Membership does not automatically grant voting entitlement or access, and that Election-Only mode remains valid where specified.
- Complete the integration, regression and operational-readiness evidence required before production rollout.

Next action now: send the planning-only prompt to Claude. Once Claude returns the exact proposed SF-1 changes, review that plan before authorizing any edits.