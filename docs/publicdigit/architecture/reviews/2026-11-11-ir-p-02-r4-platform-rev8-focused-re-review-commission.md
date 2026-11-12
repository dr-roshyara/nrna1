# IR-P-02-R4: focused independent re-review of the Platform (tenancy) design, revision 8

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11. The scope below follows the Principal's commission text "IR-P-02-R4 — Revision 8 Handover Preparation and Independent Re-Review Commission" |
| **Reviewer** | **GPT-6 in ChatGPT.** Not Claude, not DeepSeek, not run inside Claude Code. **Prior involvement:** author of IR-P-02, IR-P-02-R, IR-P-02-R2 and IR-P-02-R3. Not a blind review |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, SHA-256 **`28cfecdfbf11874ae9af9e935d25a91cda962ea1d0b8708ac8de987611dc5006`**. **Revision 8 (R8-1 … R8-7) governs**, then revisions 7, 6, 5 (part 2 over part 1), and 4 … 1, where they differ. **Frozen: not changed while this package was prepared** |
| **Status** | **Proposed, not accepted. No implementation authorised.** Claude's statement that the findings are fixed is **not independent verification**. **No proof obligation has been executed** |
| **Scope** | R8-1 … R8-7 against IR-P-02-R3 (R3-1 … R3-8) and the accepted decisions; the proof register; **new high-severity contradictions only** beyond that |
| **Not in scope** | Reopening D-164, D-166, D-170, D-173 … D-189; other bounded contexts; implementation |

## 1. Files (`MANIFEST.sha256` authoritative)

| # | File | Role |
|---|---|---|
| 01 | this commission | start here |
| 02 | **subject**, revision 8 | read "Revision 8" first |
| 03 | proof-obligation register, sections A, B, C | **every entry NOT EXECUTED [TU]**; see §5 limitation L-1 |
| 04 | IR-P-02-R3 (your report, verbatim from the Principal's paste) | findings R3-1 … R3-8 |
| 05 | IR-P-02-R2 (your report, verbatim) | earlier findings R2-1 … R2-6 |
| 06 | decision record | **binding:**<br>- D-164 (§11.69), D-166 (§11.71), D-170 (§11.73), D-173 … D-189 (§11.75 … §11.88);<br>- **new:** **D-188 (§11.87)**, **D-189 (§11.88)** |
| 07 | multi-database design, accepted (D-178) | ISO-1 … ISO-11 (ISO-11 in its conformance note); INV-ACCESS-1 release-1 criteria (§16 M-6) |
| 08 | Governance thin slice, accepted (D-157) | AuthorityCheck contract and correction notes; **correction note 4 (`requestRef`: subject and request identifiers; accepted only for that request)** |
| 09 | I-TDD-01 Identity design (superseded where D-113 applies) | `PersonRef` → Account resolution used by R8-1 (rev 4 M-5) |

## 2. Binding

**Binding, not to be reopened, weakened or reinterpreted:**
- D-113, D-164, D-166, D-167, D-170, D-173 … D-189, **including D-188 and D-189**;
- INV-ACCESS-1; ISO-1 … ISO-11;
- INV-ID-3 as narrowed by D-179;
- the Governance authority model.

**No new authority for platform staff. No weakening of the organisation's ownership of its records or of Governance's ownership of authority.**

## 3. Questions

**A. R8-1: individual AuthorityCheck binding.** Check that:
- each individual designation receives its own request;
- the request is tied to the exact `PersonRef`, act kind, decision reference, **decision part** and request id;
- the answer uses the accepted Governance contract (file 08, incl. correction note 4) **with no second schema**;
- the request id is unique and not replayable;
- evidence for one contact never promotes another;
- a different decision, part, rule version or request is refused;
- initial contacts and recovery promotions follow the same rule;
- the outcome is retained sufficiently for audit and reconciliation.

**`Authorised` alone is not proof.**

**B. R8-2: recovery transition and admission (critical).** Is there a single safe linearisation point for:
- recognition that the organisation database can answer;
- each grant's transition to `handover`;
- outbox creation;
- admission?

Can any operation be admitted under the central grant after the transition linearises? Inspect both sides:
- the recovery transaction's locks and state changes;
- the admission-side lock and `handoverState` check.

Check the global lock order (`ServedOrganisation` → `PendingCaseGrant` → organisation copy) against file 07. **Name any reverse acquisition, deadlock or failure window.**

**C. R8-3: versioned copies and terminal states.** Check:
- unambiguous versioning;
- delayed copies cannot overwrite newer state;
- platform withdrawal during handover survives delivery;
- retries and duplicates are idempotent;
- no stale active copy reactivates a withdrawn grant;
- expired, suspended, withdrawn, closed and used-up states are terminal and monotonic;
- reconciliation cannot reactivate an invalid grant.

**Test all failure and retry sequences, not only normal delivery.**

**D. R8-4, R8-5: operation identity and admission lifecycle.** Check:
- uniqueness scope;
- same id, same request → existing operation;
- same id, different request, grant, scope or kind → refused;
- admission durable before execution;
- failure before commit → refused;
- failure after commit before acknowledgement → ambiguous, handled;
- after `started`: no silent re-execution;
- the `attempted`-without-outcome rule preserved;
- "access may have occurred" reported;
- **no exactly-once claim.**

**Distinguish admission idempotency from execution idempotency.**

**E. R8-6: D-189 deadline.** Check:
- 15 minutes default; the grant may shorten, never lengthen;
- deadline = min(expiry, limit);
- **established at admission from an unambiguous clock**;
- the same policy for `live-database` and `backup-copy`;
- no result after the deadline;
- long work stopped or isolated;
- longer tasks need new admissions;
- an admitted operation cannot bypass a later withdrawal or expiry.

**Do not confuse the operation limit with the grant's validity period.**

**F. R8-7: D-188 suspension.** Check that:
- the contact is not promoted;
- affected grants are suspended on reconciliation;
- no new admission;
- in-flight operations end per D-189;
- the grant, the designation and possible access are reported;
- no reactivation by stale delivery;
- a new grant needs the normal route.

**Keep this distinct from D-186.**

**G. Proof register (file 03):**
- Are C-1 … C-9 present, correctly described and traceable?
- Do sections A and B still contain all earlier obligations?
- **Confirm every entry is NOT EXECUTED [TU].** A specification, test description or code review is not execution evidence.

## 4. Report

**Title:** "IR-P-02-R4 — Focused Re-Review: Platform Revision 8".

1. **Integrity:** verify `MANIFEST.sha256` and the subject fingerprint.
2. **Per question A–G:** RESOLVED · PARTIAL · NOT RESOLVED · NEW, with exact references.
3. **Findings, each classified as:**
   - (a) correctable design defect;
   - (b) remaining proof obligation [TU];
   - (c) genuine Principal decision, with the exact choice;
   - (d) already resolved by an accepted decision, named.

   **Do not transfer technical questions to the Principal unnecessarily.**
4. **Verdict:** READY FOR PRINCIPAL ACCEPTANCE · READY WITH EXPLICIT PRINCIPAL DECISION · NOT READY.
   - Design acceptance is distinct from build readiness: proof obligations remain build gates.
5. **Reviewer identity and prior involvement.**

**Rules:**
- decide nothing for the Principal;
- modify nothing;
- reopen no accepted decision;
- claim no acceptance;
- **stop after the report.**

## 5. Known limitations of this package (reported by the packager)

| # | Limitation |
|---|---|
| **L-1** | **The proof register's filename and header still name revision 7** (`…-platform-rev7-proof-obligation-register.md`; header fingerprint `6de2d739…`). **Section C was appended for revision 8 and cites `28cfecdfbf11874ae9af9e935d25a91cda962ea1d0b8708ac8de987611dc5006`.** Sections A and B were not re-stamped. The register says the design governs where they differ |
| **L-2** | **IR-P-02-R3 (file 04) is the text the Principal pasted.** GPT-6 reported saving its own copy (413 lines) in its ChatGPT workspace; that copy was not received and could not be compared |
| **L-3** | **R8-1's mapping of the decision *part* into `requestRef`** relies on correction note 4's general wording ("the subject and request identifiers"). **Whether that is within the accepted contract or an extension is for the reviewer to judge** |
| **L-4** | Earlier reports IR-P-02 and IR-P-02-R are **not** included (superseded as finding baselines by R2 and R3). Available on request |
