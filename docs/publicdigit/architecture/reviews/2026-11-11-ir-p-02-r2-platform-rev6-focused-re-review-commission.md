# IR-P-02-R2: focused independent re-review of the Platform (tenancy) design, revision 6

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11 |
| **Reviewer** | **GPT-6 in ChatGPT.** Not Claude, not DeepSeek, not run inside Claude Code. **Prior involvement:** author of IR-P-02 and IR-P-02-R. Not a blind review |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, SHA-256 **`d23d93ea5af08559ca54b9ac72c5945c686e13c88ae7c6c9422bafbfdc4b9ece`**. **Revision 6 (sections R6-1 … R6-6) governs**, then revision 5 part 2, part 1, and revisions 4 … 1, where they differ. **Revision 6 was not changed while this package was prepared** |
| **Status of the subject** | **Proposed, not accepted.** Written by Claude; its self-checks are **not independent evidence**. **No implementation is authorised** |
| **Scope** | **Focused:** revision 6 against IR-P-02-R and decisions D-183 … D-185. Report anything else only if it is a **new high-severity contradiction** |
| **Not in scope** | Reopening accepted decisions; other bounded contexts (Membership, Newsletter, Forum, Geography, Identity beyond XD-3's boundary); implementation |

## 1. Files (`MANIFEST.sha256` authoritative)

| # | File | Role |
|---|---|---|
| 01 | this commission | start here |
| 02 | **subject**, revision 6 | read "Revision 6" first |
| 03 | IR-P-02 report (yours, verbatim, recorder note at top) | finding baseline F-01 … F-07 |
| 04 | IR-P-02-R report (yours, verbatim, recorder note at top) | findings to check: F-01-R … F-07-R1, P-1 … P-4 |
| 05 | decision record | **binding:** D-164 (§11.69), D-166 (§11.71), D-170 (§11.73), D-173 … D-185 (§11.75 … §11.84). **New since IR-P-02-R:**<br>- **D-183** (§11.82), with a recorder note confirming it over a pasted advisor proposal;<br>- **D-184** (§11.83);<br>- **D-185** (§11.84), with the authorised clarification note on D-175 |
| 06 | multi-database design, accepted (D-178) | ISO-1 … ISO-11 (ISO-11 in its conformance note); INV-ACCESS-1 release-1 criteria (§16 M-6) |
| 07 | Governance thin slice, accepted (D-157) | AuthorityCheck: outcomes `Authorised` / `NotAuthorised` / `Undetermined`; "never assume authorised" |

## 2. Binding (do not reopen)

**Binding, not to be reopened:**
- D-113, D-164, D-166, D-167, D-170, D-173 … D-185;
- INV-ACCESS-1; ISO-1 … ISO-11;
- INV-ID-3 as narrowed by D-179;
- the Governance authority model.

**Answers already given by the Principal since IR-P-02-R:**

| Your question | Answered by |
|---|---|
| P-1 | D-183 |
| P-2 | D-184 |
| P-4 | D-185 |

**P-3 was not put to the Principal.** The author holds that R6-4 solves it technically. **Judge that claim.**

## 3. Questions

1. **R6-1 (D-182, D-183):**
   - Are approvers restricted to current `organisation-record` contacts at each approval?
   - Is a `direct` contact denied approval regardless of D-180's 24 hours?
   - Is the accomplice-capture sequence now closed?
   - **The added requirement that the first approver must still be current at the second approval:** is it already required by accepted decisions (D-182, D-183), or does it **need a Principal clarification**? It is stricter than D-182's literal "at each approval".
   - Is promotion from `direct` to `organisation-record` on recovery sound?
   - Is the treatment of initial contacts during `provisioning` sound?
2. **R6-2:**
   - Does it keep **temporary emergency-route operation** (D-176) distinct from **constitutional validity** (Governance only; D-181)?
   - Is the verified-counterparty sender route preserved as transmission only (D-175, D-176)?
   - Can `Undetermined` / `NotAuthorised` ever establish authority?
3. **R6-3:** does it implement **D-184** for every ordering case? Check in particular:
   - older-late;
   - newer-early;
   - duplicate;
   - retry;
   - delayed;
   - future-dated;
   - same date with different decisions (applied versus concurrent);
   - same decision with different digests (including withdrawal of one already applied);
   - recovery.
4. **R6-4:**
   - Is an **organisation-side withdrawal enforced immediately and on every protected operation**, for both `live-database` and `backup-copy` cases?
   - Is it fail-closed when the organisation database is unreachable after copy delivery has begun?
   - Does the expiry admission rule (`t_admit < expiry` under the shared lock; deadline = expiry; no result after expiry) hold?
   - Is anything a **distributed transaction** in disguise?
   - Are **D-164, D-166 and INV-ACCESS-1** preserved?
   - **Is P-3 genuinely resolved without a Principal decision?**
5. **R6-5:**
   - Is the single approval transaction (`ServedOrganisation` S → `PendingCaseGrant` X), with one global lock order, coherent for approval, withdrawal, expiry and recovery?
   - Is any lock-order cycle possible?
   - Are rows 1, 2, 2a, 3, 3a–3c, 5, 6 and 8 correct in both orders?
   - Are the proof tests sufficient as specified, including the 3-way row 2a and the `Undetermined` case?
6. **R6-6:** does it implement **D-185** and read D-175 consistently with its authorised clarification note and the D-178 conformance note?
7. **New contradictions:** has revision 6 introduced any **new high-severity** contradiction with an accepted decision or with ISO-1 … ISO-11?

## 4. Report

**Title:** "IR-P-02-R2 — Focused Re-Review: Platform Revision 6".

1. **Integrity:** verify `MANIFEST.sha256` and the subject fingerprint above.
2. **Per question (1–7):** RESOLVED · PARTIAL · NOT RESOLVED · NEW, with exact section references (R6-n, row numbers, decision numbers).
3. **Findings, each classified into exactly one of:**
   - **(a) design defect Claude can correct**, without a Principal decision;
   - **(b) proof obligation that remains** ([TU]; not a design defect);
   - **(c) genuine Principal question:** state the exact choice;
   - **(d) already resolved by an accepted decision:** name it.

   Give each finding a severity and its business consequence.
4. **Verdict:** READY FOR PRINCIPAL ACCEPTANCE · READY WITH EXPLICIT PRINCIPAL DECISION · NOT READY.
5. **Reviewer identity and prior involvement.**

**Rules:**
- decide nothing for the Principal;
- modify nothing;
- reopen no accepted decision;
- claim no acceptance;
- **stop after the report.**
