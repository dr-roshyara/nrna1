# IR-P-02-R3: focused independent re-review of the Platform (tenancy) design, revision 7

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11 |
| **Reviewer** | **GPT-6 in ChatGPT.** Not Claude, not DeepSeek, not run inside Claude Code. **Prior involvement:** author of IR-P-02, IR-P-02-R and IR-P-02-R2. Not a blind review |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, SHA-256 **`6de2d7393ce5fad6c154ae9d4699ccceacabd3789f36dabbe6ec5b81148636ee`**. **Revision 7 (R7-1 … R7-5) governs**, then revision 6, revision 5 (part 2 over part 1), and revisions 4 … 1, where they differ. **Revision 7 was not changed while this package was prepared** |
| **Status** | **Proposed, not accepted. No implementation authorised.** Claude's self-checks are not independent evidence. **No proof obligation has been executed** (file 03) |
| **Scope** | **Focused** on R7-1, R7-3, R7-4, R7-5. Report anything else only if it is a **new high-severity contradiction** |
| **Not in scope** | Reopening accepted decisions (**D-186 and D-187 included**); other bounded contexts; implementation |

## 1. Files (`MANIFEST.sha256` authoritative)

| # | File | Role |
|---|---|---|
| 01 | this commission | start here |
| 02 | **subject**, revision 7 | read "Revision 7" first |
| 03 | proof-obligation register | compiled from the subject without changing it; **every entry NOT EXECUTED**; the subject governs where they differ |
| 04 | IR-P-02-R2 report (yours, verbatim) | the findings revision 7 answers |
| 05 | decision record | **binding:** D-164 (§11.69), D-166 (§11.71), D-170 (§11.73), D-173 … D-187 (§11.75 … §11.86); **new: D-186 (§11.85), D-187 (§11.86)** |
| 06 | multi-database design, accepted (D-178) | ISO-1 … ISO-11 (ISO-11 in its conformance note); INV-ACCESS-1 release-1 criteria (§16 M-6) |
| 07 | Governance thin slice, accepted (D-157) | AuthorityCheck (outcomes; six fields + basis; "never assume authorised") |

## 2. Binding (do not reopen)

**Binding, not to be reopened:**
- D-113, D-164, D-166, D-167, D-170, D-173 … D-187;
- INV-ACCESS-1; ISO-1 … ISO-11;
- INV-ID-3 as narrowed by D-179;
- the Governance authority model.

**Do not invent business rules.** If a rule is missing, name it as a Principal question.

## 3. Questions

1. **R7-1:**
   - Is Governance `Authorised` bound to **each individual designation**: the AuthorityCheck on the specific designating decision (act kind, subject, officer, rule, version), carried with each copied or promoted contact entry? Or could a general authorisation suffice?
   - **Grants given with the participation of a contact whose designation later fails validation:** revision 7 says only "flagged". Do they remain in force until withdrawn or expired, or are they suspended?
     - Determine whether an accepted decision (D-176, D-181, D-186, D-187) already answers this.
     - **The author suspects no decision answers it, which would make it a Principal question.**
     - Note that D-187 requires the organisation's copy for any access after recovery. Does that already settle it?
2. **R7-3:**
   - Do **recovery recognition** (routing restored / "able to answer") and **creation of the outbox record** form one safe transition, with **no interval** in which a protected operation is admitted under the central grant after recovery?
   - **The author flags a suspected defect:** R7-3 does not state that these happen in **one central transaction** (or that admission checks recovery status, not only the outbox record). Confirm or refute it, and state the correct rule.
3. **R7-4:**
   - Does the `backup-copy` admission protocol serialise admission, organisation-side withdrawal and expiry? The protocol: `FOR SHARE` on the copy row → check → `admission` row with operation id, committed; withdrawal `FOR UPDATE`.
   - **Missing copy row:** the author's claim is that **no lock is needed, because absence always means refusal**. A concurrent insert can only cause a spurious refusal, never an admission. Is that correct?
   - Does the `admission` row need a unique operation id?
   - Connection failure after admission but before completion.
   - Can an admitted operation run past its deadline?
4. **R7-5 and file 03:**
   - Are all concurrency, failure and recovery cases adequately specified?
   - Is any case missing? Is any test insufficient as specified?
   - **Confirm that nothing in file 03 is presented as executed or resolved.**
5. **New contradictions:** has revision 7 introduced any **new high-severity** contradiction with an accepted decision or with ISO-1 … ISO-11?

## 4. Report

**Title:** "IR-P-02-R3 — Focused Re-Review: Platform Revision 7".

1. **Integrity:** verify `MANIFEST.sha256` and the subject fingerprint.
2. **Per question:** RESOLVED · PARTIAL · NOT RESOLVED · NEW, with exact references.
3. **Findings, each classified as:**
   - (a) design defect Claude can correct;
   - (b) proof obligation remaining;
   - (c) genuine Principal question, with the exact choice;
   - (d) already resolved by an accepted decision, named.

   Give each a severity and its business consequence.
4. **Verdict:** READY FOR PRINCIPAL ACCEPTANCE · READY WITH EXPLICIT PRINCIPAL DECISION · NOT READY.
   - **Acceptance of the design is distinct from readiness to build:** proof obligations remain build gates either way.
5. **Reviewer identity and prior involvement.**

**Rules:**
- decide nothing for the Principal;
- modify nothing;
- reopen no accepted decision;
- claim no acceptance;
- **stop after the report.**
