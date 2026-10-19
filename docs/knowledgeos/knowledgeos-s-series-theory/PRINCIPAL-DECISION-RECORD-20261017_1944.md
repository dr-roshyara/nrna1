# Principal / ARB decision record: KnowledgeOS S-Series (copy for the theory workspace)

| Field | Value |
|---|---|
| Date | 2026-10-17 (copied 19:44, clock) |
| Status | **Confirmed by the Principal in session** ("I strongly accept the recommendation"). The text below is the record the Principal accepted, restated faithfully. |
| Nature of this file | A workspace copy for traceability. **It is not a governance-log entry.** Recording it in the governance log is for the Principal. |
| Supersedes | `PRINCIPLES-D9-D10-20261017_1931.md`, where they differ |

## Decisions

**D1, safety review.**
- Authorized: an independent review of the filter specification, source code and tests, using fake fixtures only.
- A **separate human tester** executes and signs the human test sheet, on a **non-root** account.
- The reviewer inspects the specification before the implementation, derives test cases independently, identifies residual risks and issues a numbered verdict.
- The current 39 passing tests are **preliminary developer evidence, not proof of safety**. Full hashes and the final expected test count must be confirmed.
- **No production use is authorized by D1.**

**D2, sealed-list rule.**
- The pause is maintained. The written clarification request goes to the owner of G-LOG-0141 item 7, who must answer all five questions in writing.
- Until then: **no use of sealed lists, no count-only check, no primary-source read.**
- Preferred option, subject to the rule owner: the **seal custodian** operates the approved mechanism and provides only the permitted output.
- **Earlier disclosed runs:** the record is preserved; they are assessed under the written clarification; any remediation the rule owner directs is followed. **No conclusion either way until then.**

**D6, independent scientific review.**
- Authorized: send the packet to a reviewer who did not take part in this cycle.
- A **qualified human mathematician or theoretical computer scientist is preferred.** Another AI model family may supplement this review but does not replace it.
- No claim of independent validation is accepted until the findings have been received and assessed.

**D9, authoritative kernel (provisional principle).**
- Critical decisions are designated explicitly. Their authoritative results are never silently replaced by approximate projections, and approximations are labelled, with their permitted use defined.
- **Still to be agreed with stakeholders:** the list of critical decisions, the correctness criteria, and the projection boundaries.
- This is an architectural and governance choice, not a conclusion forced by the mathematics.

**D10, event recording (provisional principle).**
- Record the actual, decision-relevant outcomes required for audit, explanation and reproduction, subject to privacy, minimization, access and retention rules.
- Do not assume everything is recorded, or that the system can answer questions about paths not taken: those require a stakeholder requirement.
- The data-minimization result still needs review.

**D4, manuscript.**
- v0.4 remains an unapproved draft; v0.3 is preserved.
- The correction proposal is held, not applied, until the external review has been considered and the Principal explicitly approves.

**Permitted while reviews are pending.** Synthetic-only research, including **O5-12 under its fixed protocol**, and the analytical questions. No restricted material, and no production filter.

## Gate rule: the gate is BLOCKED until all four conditions hold

1. The D1 review is PASS, or PASS WITH CONDITIONS with every condition satisfied.
2. The separate human fake-fixture test is signed PASS.
3. The rule owner has answered D2 in writing, permitting the mechanism.
4. The Principal records the gate as passed.

After that, only the specifically authorized count-only check and the source reads may proceed, with the named operator and the required logging.

## Open appointments

| Item | Status |
|---|---|
| D1 reviewer | to be appointed |
| D1 human tester | to be appointed |
| D2 rule-owner response | pending |
| D6 external reviewer | to be appointed |
| **Gate status** | **BLOCKED** |
