# Membership C-1 — revision 4 review and implementation clearance

My review outcome: revision 4 is approved for the narrowly scoped P1–P6 documentation implementation. The Human PO/ARB has now explicitly authorised implementation, and the revised P2 wording addresses the earlier review issue.

Based on the report you supplied:

- P1–P6 are defined in the plan.
- P2 uses the exact reviewer wording, with the historical status retained.
- P4 and P5 preserve the original approval qualification.
- P6 remains separately scoped to the verified README housekeeping correction.
- C-AMEND-1, §14.5, CQ-3, and the application implementation remain outside this change.
- G3 formal acceptance remains pending.

One semantic safeguard: preserve the wording-review qualification as part of the historical approval record, but do not interpret it as meaning that the review is still outstanding. The latest state should remain traceable to the review now completed and the implementation clearance just granted, without inventing a new decision or silently expanding P1–P6.

I have reviewed the reported plan and wording; I have not independently inspected the repository or executed the implementation. Claude must verify the actual repository state before changing files.

## Prompt for Claude — implement the approved P1–P6 patch

# PublicDigit Membership — Implement Approved C-1 Status Reconciliation (P1–P6)

## 1. Authority and roles

- **Human PO/ARB:** Has explicitly authorised implementation of final P1–P6.
- **ChatGPT:** Independent Principal DDD Architect and reviewer. Revision 4 wording has been reviewed and approved for the narrowly scoped documentation change.
- **Claude:** Implementation and evidence engineer. Implement the approved plan, preserve unrelated work, validate the diff, commit the authorised changes, and report the evidence.

This authorisation is limited to the approved P1–P6 documentation changes. It does not grant G3 acceptance or authorise other Membership workstreams.

## 2. Baseline and mandatory preflight

Reported baseline:

- Branch: `knowelegeos-modelling`
- HEAD: `21a2ff22f1de5a6bb5e39f5e2aaf4e9930fd7cd2`
- Active plan: `docs/plans/20261010-1410-membership-c1-start-gate-status-reconciliation-plan.md`
- Confirmed decision ID: `C-STATUS-1`
- G3: PENDING.

Before editing, verify the actual branch, HEAD, index, working tree, target-file hashes, and the three other-session staged renames.

**Stop immediately** if the baseline differs materially, a target file has unexpected staged or unstaged changes, or the approved patch cannot be isolated from unrelated work. Report the discrepancy instead of overwriting or committing another session's changes.

## 3. Implement exactly P1–P6

Use the final approved text in plan revision 4 as the implementation source of truth.

- **P1:** Append the superseding status note to the contract's Decision register C-1 row.
- **P2:** Append the exact reviewer-approved wording to gate-record row 4's Status cell, preserving the historical text unchanged.
- **P3:** Append the approved superseding note to gate-record row 4's Implication cell.
- **P4:** Add gate-record row 6 for `C-STATUS-1`, recording Decision A only.
- **P5:** Add the approved C-1 status summary to contract §16.
- **P6:** Correct the stale README housekeeping note using the separately authorised evidence for commit `d90bf8af9`.

Preserve the approved distinction between:

1. C-1 plan approval in principle with conditions.
2. Subsequent start-gate re-evaluation and eligibility to start.
3. Implementation under `PBDIGIT-268`.
4. Formal acceptance, which remains PENDING under G3.

Keep the original historical text intact. Do not upgrade conditional approval into unconditional approval. Do not rewrite the history to suggest that G3 acceptance has already occurred.

Retain the wording-review qualification as historical decision context. Do not imply that the review remains outstanding after the review and implementation clearance recorded in this conversation.

## 4. Strict scope exclusions

Do not:

- Modify application code, tests, database schemas, configuration, routes, resources, or sensitivity-runner files.
- Change C-AMEND-1, §14.5, or CQ-3.
- Implement PBDIGIT-269.
- Start C-2, C-3, C-4, migration, or production cutover.
- Modify the ratified act.
- Push changes.
- Claim G3 acceptance.

Do not silently expand P1–P6. If you believe another decision or document change is necessary, stop and request separate authorisation.

## 5. Safe implementation and preservation

1. Snapshot branch, HEAD, staged diff, unstaged diff, status, and target-file hashes.
2. Apply changes with exact-match assertions. Stop if any expected anchor matches zero or multiple times.
3. Isolate the approved changes using the preservation procedure in plan §7.5. Do not include the three other-session staged renames in the commit.
4. Inspect the complete staged patch.
5. Verify Markdown table column counts and review every changed occurrence of BLOCKED, eligible, approved, accepted, and PENDING in context.
6. Confirm that no excluded file or unrelated change entered the patch.
7. Verify the historical wording is preserved and P1–P6 match the approved plan.
8. Commit only the authorised P1–P6 changes using the repository's established commit conventions. Do not push.

If the approved changes cannot be safely isolated, stop without committing.

## 6. Validation

Run the targeted sanity tests specified in plan §7.5, where feasible. Report the actual commands, exit statuses, and results.

The previously reported baselines were:

- Organisational Domain: 89 tests / 112,487 assertions.
- Organisational Application: 34 tests / 93 assertions.
- Architecture tests: 12 tests / 18 assertions.

These are historical reported results, not proof that the current run passes. Do not claim success without actual execution evidence. If a test cannot be run, explain why and leave the result unverified.

## 7. Required final report

Return:

1. Actual branch and before/after HEAD.
2. Commit hash and exact files changed.
3. P1–P6 implementation summary.
4. Full diff review outcome.
5. Test commands and actual results.
6. Confirmation that the three other-session staged renames and unrelated changes were preserved.
7. Confirmation that no excluded files were changed and nothing was pushed.
8. Remaining risks and TODOs.
9. Explicit statement that G3 remains PENDING.

After reporting, stop. Do not start another Membership workstream automatically.

## Membership progress and remaining TODOs

The next milestone is now implementation of the approved reconciliation patch, followed by evidence review. The larger Membership goal remains a separate engineering and acceptance programme.

### Immediate

- Claude performs the preflight and implements P1–P6.
- Review the actual diff, commit, preservation checks, and test results.
- Conduct the G3 formal acceptance review for C-1; do not infer acceptance from documentation completion.

### Verification

- Investigate the reported wider Membership test-suite errors.
- Prepare an isolated PostgreSQL test database and verify persistence round trips.
- Validate temporal reconstruction, Membership-period non-overlap, re-entry, and Annul/Void liveness invariants.
- Verify the dependencies and scope of PBDIGIT-264, PBDIGIT-266, and PBDIGIT-167.
- Resolve CQ-3 and the BD-REV-ER / D-COR-3 blockers through their respective governance decisions.

### Later phases

- Authorise and plan C-2, C-3, and C-4 separately.
- Keep C-AMEND-1 and §14.5 unchanged until an explicit amendment is approved.
- Authorise PBDIGIT-269 separately before implementing the runner.
- Govern legacy migration, single-writer enforcement, reverse projections, and cutover under ARB-MEM-01.
- Complete regression, persistence, security, and operational evidence before claiming production readiness.

Current state: revision 4 has passed the reported wording review, and implementation clearance has been granted. The next deliverable is the verified P1–P6 patch and its commit evidence—not G3 acceptance.