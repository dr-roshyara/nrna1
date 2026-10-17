# PublicDigit Membership — final plan review

I agree with the overall direction of Claude's latest plan. It has improved materially: it now separates three approval gates, accounts for the existing decision register, and includes explicit safeguards for the real Git index and other sessions' work. Claude reports that no files were edited, no stories were created, and no commits were made.&#x20;

Eingefügter Text.txt



My recommendation: approve G1, the documentation/backlog plan, with one important clarification; decide G2 separately. Do not treat either approval as formal C-1 acceptance.

## 1. What the three gates mean

| Gate                       | What it authorises                                                                                                     | My recommendation                                               |
| -------------------------- | ---------------------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------- |
| G1 — Plan approval         | Implementing the approved documentation and backlog changes, excluding contract adoption unless G2 is also approved    | Approve with the clarification below                            |
| G2 — Contract annotation   | Adding the C-3/C-4 criteria to contract §14.5 and recording the decision in the authoritative register and §16 summary | Decide separately; approval must be explicit                    |
| G3 — Formal C-1 acceptance | Acceptance of PBDIGIT-268 through the agreed PR and Human PO/ARB process                                               | Keep pending until the resulting diff and evidence are reviewed |

This separation is correct and essential. The C-1 correction's independent review recommends acceptance with explicit conditions; it does not itself authorise the next implementation slice.&#x20;

Eingefügter Text.txt



## 2. One clarification before G1 approval

The plan refers to `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md` as the location for the §14.5 annotation. The filename calls it a draft, while the plan describes the annotation as a change to an approved contract. That status must be verified rather than assumed.

Before implementing F2, Claude should establish whether this document is the ratified contract, an approved working baseline, or still a draft. If it is a draft, the decision record must identify precisely what is being adopted and which authority makes it binding. The annotation must not silently elevate a draft to ratified policy.

The other safeguards in the plan are appropriate: stop on concurrent changes, preserve staged renames, inspect the complete staged patch, and review every status-search result in context.&#x20;

Eingefügter Text.txt



## 3. What to do now

I recommend this sequence:

1. Ask Claude to resolve the contract-status clarification in the plan only. No file edits.
2. Approve G1 separately once the exact scope is clear. G1 may authorise the acceptance documentation and the proposed PBDIGIT-269 backlog entry, but it must not authorise runner implementation.
3. Decide G2 separately. If you adopt the §14.5 annotation, require the corresponding authoritative decision-register entry and §16 summary. Otherwise, retain the proposed criteria in the acceptance review without changing the contract.
4. After implementation, have ChatGPT review the actual diff and evidence.
5. Complete G3 through the PR and Human PO/ARB formal acceptance process.

Here is the next prompt for Claude.

# PublicDigit Membership — Resolve Contract Status Before G1/G2

The principal review agrees with the final plan's overall structure and its separation of G1, G2 and G3.

One clarification remains before implementation: the proposed §14.5 annotation targets a file named `2026-10-09-membership-correction-slice-semantic-contract-draft.md`, while the plan refers to it as an approved contract.

## Task: plan clarification only

1. Inspect the document's own status metadata, the authoritative gate decision record, the §16 summary and relevant ratification evidence.
2. Establish whether the target document is:
   - a ratified contract;
   - an approved working baseline; or
   - a draft without final ratification.
3. Do not infer ratification from the filename, presence of a decision register, or previous assistant statements.
4. Explain what G2 would legally and procedurally adopt within the project's own governance model, which record is authoritative, and how the §16 summary relates to it.
5. If the status cannot be established from repository evidence, state that explicitly and identify the exact Human PO/ARB decision required.
6. Update the plan in your response only. No file changes.

## Preserve the existing scope

- G1, G2 and G3 remain separate.
- PBDIGIT-269 remains a proposed OPEN, unauthorised tooling story until G1 is explicitly approved.
- G2 must explicitly authorise any contract adoption and corresponding decision-register/§16 changes.
- G3 formal C-1 acceptance remains pending.
- Preserve the independent review verbatim and all unrelated work.
- Do not implement runner changes or begin another Membership slice.

## Required response

Return the verified contract status, the evidence supporting it, the implications for G2, and the exact revised approval wording.

Label the result:

**CONTRACT-STATUS CLARIFICATION — PLAN ONLY**

Stop and await ChatGPT review and explicit Human PO/ARB approval.

## 4. How far are we from the Membership implementation goal?

We have credible evidence for the narrow C-1 correction, but the overall Membership implementation is not yet demonstrated to be production-ready. The current task is still a governance and acceptance checkpoint.

### Remaining TODOs

Immediate

- Verify the actual status and authority of the semantic-contract document.
- Review Claude's plan clarification.
- Approve G1 for the exact documentation/backlog scope.
- Make a separate explicit G2 decision on the §14.5 annotation.
- Implement only the approved changes and inspect the complete diff.
- Complete G3 formal C-1 acceptance.

Subsequent implementation

- Plan C-2, C-3 and C-4 separately.
- Resolve CQ-3/G-AUTH-1 and the meaning of provenance `reason`.
- Preserve F-1 and verify Annul/Void persistence round trips on an isolated PostgreSQL database.
- Re-measure and address the wider Membership test errors.
- Verify projections, tenant isolation, concurrency, legacy compatibility and migration/reverse-projection controls.
- Obtain ARB-MEM-01 approval before cutover or live writes.
- Run full regression in a safe environment and document remaining exceptions.

Research and assurance

- Maintain traceability between ratified domain rules, contracts, invariants, tests and implementation.
- Extend property and differential testing where it addresses concrete gaps.
- Keep bounded evidence distinct from mathematical proof.
- Use machine learning only where a justified data-driven problem exists; retain deterministic Membership rules as explicit domain logic.

Bottom line: do not ask Claude to implement yet. Resolve the contract-status ambiguity first, then approve G1 and G2 separately. Formal C-1 acceptance remains a later, independent Human PO/ARB decision.&#x20;
