# Membership implementation — review and next steps

My assessment of the attached Claude report is that the next step is to finalise the C-1 status-reconciliation plan, obtain explicit Human PO/ARB approval for the exact wording, and only then allow Claude to implement the approved documentation changes. We should not move to C-2 or C-3 yet.

The report indicates that Claude has revised the plan but has not changed either target document. The proposed plan remains uncommitted, and Claude is waiting for independent review and implementation clearance. Source: attached report, especially lines 67–70 and 189–191.

## 1. Senior reviewer assessment

What is correct

The plan distinguishes four different states that must not be conflated:

- C-1 plan approved in principle, with conditions.
- Start gate subsequently re-evaluated, with all applicable conditions closed and C-1 eligible to start.
- C-1 implementation recorded under `PBDIGIT-268`.
- Formal acceptance under G3 still PENDING.

What must be controlled

The reconciliation must not rewrite history or turn conditional approval into unconditional approval. Preserve the old entries and add explicit superseding notes. Do not label C-1 formally accepted merely because it was implemented.

What remains unapproved

The proposed identifier `C-STATUS-1` is not yet an established decision ID. The plan must also avoid recording a Human PO/ARB decision as approved unless that decision was actually given. A plan can document an approval, but it cannot manufacture one.

### My decision on Claude's revised plan

Recommended: approve the plan for the next review stage, with two conditions.

1. The Human PO/ARB must explicitly confirm the status-reconciliation decision, including whether to use `C-STATUS-1`.
2. Before implementation, I must review the final exact wording and verify that the patch scope is limited to the authorised documentation changes.

One detail deserves special attention: the plan proposes putting both the status reconciliation and the deferred C-AMEND-1 clarification decision into the same new register row. That is possible if the Human PO/ARB explicitly authorises it, but it risks coupling two distinct decisions. My preference is to record the status reconciliation separately and leave the C-AMEND-1 clarification deferred, as the plan already intends. Do not amend C-AMEND-1 or resolve CQ-3 as part of this task.

## 2. Prompt instructions for Claude — next step

Use the following prompt. It instructs Claude to finish the plan first, stop for independent review, and implement only after the required authorisation.

# Membership Workstream — C-1 Status Reconciliation: Finalise Plan, Review, Then Implement

## 1. Roles and decision authority

Work exclusively on the PublicDigit Membership workstream.

- **Claude — implementation and evidence engineer:** Inspect repository evidence, prepare the plan, implement only explicitly authorised changes, run the specified checks, and report verifiable results.
- **ChatGPT — independent Principal DDD Architect and requirements reviewer:** Review semantic correctness, consistency, decision provenance, governance boundaries, and the proposed patch. ChatGPT does not grant Human PO/ARB authority.
- **Human PO/ARB — decision authority:** Explicitly approve the final decision wording, the decision identifier, and implementation scope. Do not infer approval from a recommendation or from the existence of a plan.

The membership corpus is research and brainstorming material unless a decision is explicitly recorded as authoritative. Do not silently promote hypotheses, suggestions, or implementation reports into accepted domain rules.

## 2. Current baseline

Use the repository state reported in the latest evidence:

- Branch: `knowelegeos-modelling`
- Reported HEAD: `21a2ff22f1de5a6bb5e39f5e2aaf4e9930fd7cd2`
- Active plan: `docs/plans/20261010-1410-membership-c1-start-gate-status-reconciliation-plan.md`
- The plan is revised but uncommitted.
- The two target documents are reported unchanged.
- Other-session staged renames and any unrelated working-tree changes must be preserved.

Re-check the real repository state before doing anything. If HEAD, branch, target-file state, staged changes, or relevant evidence differs from this baseline, stop and report the difference before proceeding.

## 3. Phase A — plan only

Do not edit target documents yet.

Review the current plan and its supporting evidence. Produce a final, precise implementation plan covering the following:

1. The C-1 row in the contract's Decision register.
2. Row 4 (`C-1-AUTH`) in the C-1 gate decision record.
3. A new gate-record entry for the status reconciliation, if explicitly approved.
4. A matching status entry in contract §16.
5. The stale housekeeping note about the PBDIGIT-268 README index row, only if separately authorised.

Preserve all historical text. Use additive superseding notes rather than deleting or rewriting prior decisions.

The wording must preserve this state sequence exactly:

- The C-1 plan was approved in principle by the PO, with conditions.
- The subsequent start-gate re-evaluation determined that all applicable conditions were closed and C-1 was eligible to start.
- C-1 was subsequently implemented under `PBDIGIT-268`, according to the recorded repository evidence.
- Formal acceptance under G3 remains PENDING.

Never use “approved” without specifying what was approved. Never equate start eligibility, implementation completion, and formal acceptance.

### Decision-ID handling

`C-STATUS-1` is a proposed identifier, not an already established decision. Mark it as proposed until the Human PO/ARB explicitly confirms it or chooses another identifier.

Do not record a Human PO/ARB approval in the repository before it has actually been granted. If approval is conditional on independent wording review, record that qualification accurately.

### Explicit exclusions

Do not:

- Amend C-AMEND-1 or §14.5.
- Resolve CQ-3 or infer the meaning of provenance, reason, or authority evidence.
- Implement PBDIGIT-269 or modify any sensitivity runner.
- Change application code, tests, database schemas, configuration, routes, resources, or runtime behaviour.
- Authorise C-2, C-3, C-4, migration/cutover, or G3 acceptance.
- Push changes or claim formal acceptance.

## 4. Phase B — independent review and Human authorisation

Return the complete proposed wording for every affected section, not just a summary.

For each change, show:

- Current text.
- Proposed text.
- Evidence supporting each status statement.
- Decision authority and approval state.
- Files affected.
- Preservation risks and validation checks.

Then stop. Do not edit target documents or commit.

Wait for:

1. ChatGPT's independent review of the exact wording.
2. Explicit Human PO/ARB approval of the final scope and decision ID.
3. Explicit implementation clearance after those conditions are satisfied.

A prior recommendation, an in-principle approval of the original C-1 plan, or a proposed plan is not sufficient authorisation for this separate reconciliation.

## 5. Phase C — implement only after approval

After all required approvals are present:

1. Re-check branch, HEAD, index, working tree, and target-file state.
2. Abort if target files have unexpected staged or unstaged changes.
3. Preserve all other-session changes, including the three staged renames reported previously.
4. Apply only the authorised documentation edits using exact-match assertions. Stop if an anchor matches zero or multiple times.
5. Inspect the complete staged diff and verify that every changed line is within the approved scope.
6. Check Markdown table column counts and review every changed occurrence of “BLOCKED”, “eligible”, “approved”, “accepted”, and “PENDING” in context.
7. Verify that no application, test, database, configuration, route, resource, or runner file changed.
8. Run the previously specified targeted sanity tests, where feasible:
   - Organisational Domain: reported baseline of 89 tests and 112,487 assertions.
   - Organisational Application: reported baseline of 34 tests and 93 assertions.
   - Architecture tests: reported baseline of 12 tests and 18 assertions.

Do not claim these tests passed until the actual commands and exit statuses confirm it. Report any environment limitation or difference from the baseline.

9. Commit only the approved reconciliation changes. Do not include unrelated staged changes. Do not push.
10. Report the commit hash, exact files changed, validation commands and outcomes, remaining risks, and confirmation that G3 remains pending.

If safe isolation of the approved patch from concurrent changes cannot be demonstrated, stop rather than risk overwriting or committing another session's work.

## 6. Required final report

Return:

- Decision and approval traceability.
- Before/after status of each reconciled entry.
- Commit hash, if a commit was authorised and completed.
- Test evidence and limitations.
- Explicit statement that G3 acceptance has not been granted.
- Updated Membership progress and prioritised TODOs.

Stop after this task. Do not begin the next Membership workstream automatically.

## 3. What should the Human PO/ARB decide?

The Human PO/ARB should make a narrow decision now—not approve more of the Membership architecture than necessary.

Decision A — C-1 status reconciliation

Approve the additive correction of stale status entries, subject to the wording review above. Confirm whether `C-STATUS-1` is the chosen identifier. Historical wording must remain intact, and G3 must remain pending.

Decision B — C-AMEND-1 clarification

Keep deferred. Do not change the approved contract wording or resolve CQ-3 within this task.

Decision C — README housekeeping note

Authorise correcting the stale note only if the evidence confirms the README index row was already added in commit `d90bf8af9`. Keep this change separate in scope from any domain-rule decision.

Decision boundary

None of these decisions constitutes G3 acceptance or authorises C-2, C-3, C-4, the sensitivity runner, or production cutover.

The Human PO/ARB should record its actual decision explicitly. The prompt above deliberately does not treat the proposed plan or my recommendation as that approval.

## 4. How far are we from the Membership implementation goal?

The Membership domain model and governance approach are substantially developed, but a mature theory and a documented C-1 implementation are not the same as a production-ready Membership platform.

| Workstream                                   | Current assessment                                                                                 |
| -------------------------------------------- | -------------------------------------------------------------------------------------------------- |
| Membership concepts and domain boundaries    | Well developed; some business/legal questions remain open                                          |
| Membership facts, periods and temporal truth | Established design direction; implementation and persistence evidence still matter                 |
| C-1 Annul/Void correction slice              | Reported implemented under PBDIGIT-268; formal G3 acceptance pending                               |
| Contract and decision governance             | Improved through G1, G2 and G2-b; current status reconciliation still needs approval and execution |
| Automated tests                              | Targeted sanity results reported; broader suite has known errors that need investigation           |
| PostgreSQL persistence                       | Not independently verified in the supplied evidence                                                |
| PBDIGIT-269 sensitivity runner               | Backlog item only; implementation not authorised                                                   |
| C-2, C-3 and C-4                             | Not authorised to proceed                                                                          |
| Legacy migration and production cutover      | Still a separate governance and implementation task                                                |

I would not give a single overall completion percentage: the evidence does not support a defensible percentage for production readiness. The important distinction is that the design and governance work is ahead of the demonstrated persistence, regression, and cutover evidence.

## 5. Remaining TODOs — prioritised

### Immediate

- Human PO/ARB explicitly decides the C-1 status-reconciliation scope and confirms or replaces `C-STATUS-1`.
- ChatGPT reviews the final exact wording before implementation.
- Claude implements only the approved reconciliation, preserving all historical text and unrelated working-tree changes.
- Verify the actual diff, commit, and targeted test outcomes; do not rely solely on Claude's narrative report.
- Keep G3 formal acceptance pending until the required evidence and acceptance review are complete.

### Next: establish trustworthy implementation evidence

- Investigate the known errors in the wider Membership test suite and distinguish regressions from existing failures.
- Execute PostgreSQL persistence and round-trip verification, including temporal and correction scenarios.
- Verify invariants for non-overlapping Membership periods, re-entry, fact liveness, and reconstruction of what was known at a given time.
- Verify that annulment and voiding affect the targeted fact's liveness only, with no implicit period consequences, in accordance with the approved C-1 contract.
- Review provenance and authority-evidence handling without inventing unresolved business semantics.

### Then: controlled progression

- Complete the separate authorisation and test plan for PBDIGIT-269 before implementing its runner.
- Define and authorise C-2 only after C-1 evidence and the relevant acceptance gate have been reviewed.
- Resolve CQ-3 before any C-3 planning or implementation that depends on its meaning.
- Define C-3 and C-4 entry criteria and contracts, respecting the approved C-AMEND-1 constraints.
- Plan legacy-to-ledger migration, reverse projections, single-writer enforcement, and cutover as a separate governed workstream.
- Validate cross-context boundaries so that Membership does not automatically confer voting entitlement or access.
- Complete regression, architecture, persistence, security, and operational evidence before production-readiness claims.

Next action: finalise the reconciliation plan, obtain explicit Human PO/ARB approval, and let Claude implement only after the exact wording has passed independent review. That is the safest progression from the current state without confusing documentation cleanup with domain acceptance.