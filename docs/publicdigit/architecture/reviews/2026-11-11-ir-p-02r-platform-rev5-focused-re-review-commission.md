# IR-P-02-R: focused independent re-review of the Platform (tenancy) design, revision 5

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11 |
| **Reviewer** | **GPT-6 in ChatGPT** (the IR-P-02 reviewer). It is not Claude, not DeepSeek, and not run inside Claude Code. **Prior involvement:** wrote IR-P-02 and its findings F-01 … F-07 |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, SHA-256 `29006aaa9dc55e91241de98746986483ff43b302a16c1da411cbf8d436d4cfe5`. **Revision 5 governs**, and within it **part 2 governs part 1**; then revisions 4, 3, 2, 1. **The design was not changed while this package was prepared** |
| **Scope** | **Focused:** whether **D-182** is correctly implemented and whether **F-02 … F-07** of IR-P-02 are resolved. Report anything new only if it is high severity |
| **Not in scope** | Implementation; reopening accepted decisions; other contexts |
| **Basis of revision 5** | written by Claude, with a self-check (part 2). **Not independent.** Part 2 corrects an error in part 1 (a cross-database transaction claim in F-05) |

## 1. Files

| # | File | Role |
|---|---|---|
| 01 | this commission | start here |
| 02 | **subject**, revision 5 | read "Revision 5" parts 1 and 2 first |
| 03 | your IR-P-02 report, saved verbatim (recorder note at top) | the findings to check |
| 04 | decision record (D-164, D-166, D-170, D-173 … D-182; D-182 at §11.81) | binding |
| 05 | multi-database design, accepted (D-178), ISO-1 … ISO-11 | binding |
| 06 | Governance thin slice (accepted, D-157): AuthorityCheck | binding |

## 2. Binding (do not reopen)

**Binding, not to be reopened:**
- decisions D-113, D-164, D-166, D-167, D-170, D-173 … D-182;
- INV-ACCESS-1; ISO-1 … ISO-11;
- INV-ID-3 as narrowed by D-179;
- the Governance authority model.

**If a contradiction cannot be resolved within these, name it as a Principal question.**

## 3. Questions

1. **D-182 (F-01):** re-run your capture scenario (X removes Y and Z, then grants).
   - Is the two-approver rule enforced at each approval (current, grant-capable, distinct)?
   - Is a single-contact organisation refused?
   - Does the D-180 delay combine correctly?
   - Is anything still unilateral?
2. **F-02:**
   - Are receipt, registration, temporary effect, validation and challenge distinct?
   - Are these cases covered: missing, mismatched or not-found evidence, and D-181?
   - **Is the claim that no change to D-176 is needed correct?**
3. **F-03:**
   - Is the precedence model coherent for every case in your list?
   - **Answer the [OQ]:** is ordering by cited decision date a business rule needing the Principal?
4. **F-04:** is the per-recipient lifecycle complete? Is any "delivered" claimed without evidence? Is D-179 kept narrow?
5. **F-05 (part 2 gateway):**
   - Does the shared-lock / exclusive-lock mechanism guarantee that **no operation starts after withdrawal or expiry, without a distributed transaction?**
   - Is the "attempted without outcome" handling honest?
   - Is P-W1 (organisation-side withdrawal delay) acceptable as a stated bound, or a defect?
6. **F-06:** for each matrix row, is the linearisation point correct, and are both orders and recovery right? Is any proof test insufficient?
7. **F-07:** do the lifecycle semantics match D-175 and D-178? Is the bootstrap exception narrow?

## 4. Report

**Title:** "IR-P-02-R — Focused Re-Review: Platform Revision 5".

1. **Integrity:** verify `MANIFEST.sha256` and the subject fingerprint.
2. **Per question:** RESOLVED · PARTIAL · NOT RESOLVED · NEW, with exact section references.
3. **Findings:** severity, business consequence, whether a Principal decision is needed.
4. **Verdict:** READY FOR PRINCIPAL ACCEPTANCE · READY WITH EXPLICIT PRINCIPAL DECISION · NOT READY.
5. **Reviewer identity and prior involvement.**

**Rules:**
- decide nothing for the Principal;
- modify nothing;
- claim no acceptance;
- **stop after the report.**
