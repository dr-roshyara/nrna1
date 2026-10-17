# PublicDigit Membership — review of Claude's revised plan

My verdict: the plan is close to approval, but I recommend two targeted corrections before you authorise implementation.

Claude followed the requested workflow: the revised plan is presented as plan-only, no repository files were edited, and no story or commit was created. It incorporates your decisions D-A1 through D-A6, including keeping the provenance question open and separating PBDIGIT-269 from C-1.&#x20;

Eingefügter Text.txt



The overall approach is sound. My remaining concerns are about governance accuracy and avoiding accidental changes to the approved contract, rather than the basic direction.

## 1. What is good in the plan

- Evidence integrity: the independent review remains verbatim, and the acceptance record distinguishes manually verified mutation failures from what the runner guarantees.
- Scope control: runner hardening is separate from PBDIGIT-268, and PBDIGIT-269 remains unauthorised.
- Domain semantics: the meaning of `reason` stays open under CQ-3; Claude does not silently map it to an existing attribute.
- Persistence assurance: F-1 and isolated PostgreSQL round-trip verification remain C-4 obligations.
- Traceability: the proposed acceptance packet records commits, test results, limitations, and the fact that formal C-1 acceptance is pending.
- Preservation of unrelated work: Claude explicitly excludes the strategy document, traceability audit, MEMORY, and other sessions' work.&#x20;

  Eingefügter Text.txt



## 2. Two corrections before approval

### Correction A — contract §14.5 must not become approved merely because the plan is approved

Claude correctly labels the proposed annotation as awaiting approval. However, the plan says the annotation is to be included in the first documentation commit if approved. We need an explicit decision that distinguishes:

1. Approval of the documentation plan.
2. Approval of the contract annotation itself.
3. Formal C-1 acceptance later through the PR process.

These are separate gates. The proposed criteria for C-3 and C-4 must not become ratified contract rules simply because the documentation changes are merged.

My recommendation: approve the annotation only as an explicitly additive, traceable contract change, with the approval recorded in the repository's established decision format. If the established process does not allow the annotation to be adopted in this way, put it in the acceptance review as proposed criteria until the appropriate contract decision is made.

### Correction B — commit boundaries and shared files need stronger protection

The plan proposes two commits using a temporary Git index. This is reasonable, but `.claude/CONTEXT-publicdigit.md` and the session log contain other sessions' work. A temporary index alone is not sufficient proof that those changes will be preserved correctly.

Require Claude to verify the actual staged patch against the approved paths and intended hunks, preserve all unrelated staged and unstaged changes, and stop if the resulting commit would include unrelated material.

Also, the proposed check for phrases such as `C-1.*accepted` and `persistence.*verified` is only a screening check. It cannot establish semantic correctness by itself; every relevant match must be reviewed in context.

## 3. Prompt for Claude — final plan revision

# PublicDigit Membership — Final Pre-Implementation Plan Revision

## Roles

- **Claude:** Developer and evidence preparer. No implementation until the final plan is explicitly approved.
- **ChatGPT:** Principal DDD Architect and Reviewer. Reviews the revised plan and resulting diff independently.
- **Human PO/ARB:** Owns final scope approval, contract decisions and formal C-1 acceptance.

## Objective

Prepare the final, implementation-ready plan for closing the conditional acceptance gate for PBDIGIT-268.

The independent review recommends ACCEPT WITH EXPLICIT CONDITIONS. The correction evidence is positive, but formal C-1 acceptance remains PENDING.

## Required corrections

### 1. Separate the three approval gates

The plan must distinguish:

1. Approval of the implementation plan.
2. Explicit adoption or authorisation of the proposed additive §14.5 contract annotation.
3. Formal C-1 acceptance through the agreed PR and Human PO/ARB process.

Do not infer one approval from another. The §14.5 annotation must not be described as ratified merely because the implementation plan is approved.

If the contract annotation is not explicitly authorised, omit that contract edit and record the criteria in the acceptance review as proposed, not adopted.

### 2. Protect shared and unrelated work

Before implementation:

- Record the starting HEAD and complete working-tree status, including staged, unstaged and untracked changes.
- Identify changes belonging to other sessions.
- Use the agreed temporary-index approach only if its effect on existing staged content is understood.
- Verify each proposed commit's complete staged patch, not merely its file list.
- Stop if any unrelated change would be included, overwritten, unstaged, or lost.
- Do not push.

### 3. Preserve the approved scope

Use the agreed D-A1 through D-A6 decisions:

- Record the sensitivity-kit deviations and manual verification evidence.
- Keep runner hardening separate in proposed PBDIGIT-269, OPEN and unauthorised.
- Keep CQ-3 and the provenance `reason` question open.
- Preserve F-1 and isolated PostgreSQL verification as C-4 obligations.
- Reference PBDIGIT-167 without editing its README row.
- Include the independent-review document unchanged, with the developer's note separate.

### 4. Verify the resulting evidence semantically

The planned phrase-search checks are screening aids, not proof. Review each relevant occurrence in context to ensure:

- C-1 is still explicitly PENDING.
- Persistence is not represented as verified.
- The C-3/C-4 annotation has the correct approval status.
- The independent reviewer’s report is unchanged.
- The 27 Domain and 29 Application errors are described as wider-suite errors that were not re-investigated, rather than conclusively attributed to PBDIGIT-167.
- No claim suggests the full Membership suite passes.

### 5. Final plan output

Return:

- Exact file-by-file changes.
- Explicit approval gates and their owners.
- Contract annotation wording and the consequence if it is not approved.
- Commit boundaries and preservation checks.
- Verification commands and expected outcomes.
- Risks, limitations and out-of-scope work.

Label the response:

**FINAL PLAN — AWAITING CHATGPT REVIEW AND EXPLICIT HUMAN PO/ARB APPROVAL**

Stop after presenting the revised plan. Do not edit files, create PBDIGIT-269, commit, push, implement runner changes, begin another Membership slice, or mark C-1 accepted.

After the revised plan is reviewed and approved, implement only the authorised changes. Then present the complete diff and evidence for independent review. Formal C-1 acceptance remains a separate Human PO/ARB decision.

## 4. How far are we from the Membership implementation goal?

The latest report demonstrates progress on a narrow correction slice. It does not establish end-to-end production readiness, and I would not assign a new overall completion percentage from this evidence alone.

C-1 correction

Independent review completed; targeted evidence reproduced.

Current gate

Final plan review, explicit authorisation, documentation changes, PR review and formal acceptance remain.

Domain and contract completion

C-2/C-3/C-4 remain separate future slices; provenance semantics and authority questions are unresolved.

Production readiness

Persistence, broader regression, tenant isolation, concurrency, legacy compatibility and migration/cutover assurance remain.

### Remaining TODOs

Immediate

- Claude incorporates the two corrections and returns the final plan.
- ChatGPT reviews the plan.
- Human PO/ARB approves the exact scope and separately decides whether to adopt the §14.5 annotation.
- Claude implements only the authorised documentation/backlog changes.
- ChatGPT reviews the resulting diff and evidence.
- Human PO/ARB completes formal C-1 acceptance through the agreed PR process.

Next implementation phases

- Plan and approve C-2, C-3 and C-4 independently.
- Resolve CQ-3/G-AUTH-1 and the meaning of provenance `reason`.
- Preserve F-1 and verify Annul/Void persistence round trips on an isolated PostgreSQL test database.
- Track and re-measure the wider Membership test failures under the appropriate work item.
- Verify projections, tenant isolation, concurrency, legacy compatibility and migration/reverse-projection controls.
- Obtain ARB-MEM-01 approval before cutover or live writes.
- Run full regression in a safe environment and document outstanding exceptions.

Research and assurance

- Maintain traceability between ratified domain rules, invariants, contracts, tests and implementation.
- Expand property and differential testing when it addresses concrete gaps.
- Treat bounded testing as evidence within stated limits, not as a universal proof.
- Keep deterministic Membership rules in explicit domain logic; use machine learning only where a justified data-driven problem exists.

My final recommendation: send Claude the revision prompt above. We are at a governance and acceptance checkpoint, not ready to begin another implementation slice.