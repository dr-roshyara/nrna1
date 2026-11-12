# IR-P-02-R5: focused independent re-review of the Platform (tenancy) design, revision 9

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11. The scope and duties below follow the Principal's commission text "IR-P-02-R5 — Revision 9 Handover Preparation and Focused Independent Re-Review Commission" |
| **Reviewer** | **GPT-6 in ChatGPT.** Not Claude, not DeepSeek, not in Claude Code. **Prior involvement:** author of IR-P-02 and IR-P-02-R … R4. Not blind |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, **revision 9**, SHA-256 **`4e131bfb0e28a61d8baa4244517e5f6e930ce1eeb8a6581293dd27e1dcc8ba18`**. **This value was recalculated from the repository before packaging; the subject was then frozen and not modified.** Revision 9 (R9-1 … R9-6) governs, then earlier revisions in reverse order |
| **Status** | **Proposed, not accepted. No implementation authorised.** The author's claims that the R4 findings are fixed are **not evidence**. **Review the source, not this summary** |
| **Not in scope** | Reopening D-164 … D-189; implementation; other contexts beyond the ownership checks in §4 |

## 1. Files (`MANIFEST.sha256` authoritative)

| # | File | Status of the source | Role |
|---|---|---|---|
| 01 | this commission | — | start here |
| 02 | **subject**, revision 9 | proposed | review subject; the file holds revisions 1–9 in order |
| 03 | proof register stamped for revision 9 (sections A–D) | author's register | must cite the subject fingerprint; every entry NOT EXECUTED |
| 04 | **revision 8 snapshot** (the exact R4 subject, `28cfecdf…8de987611dc5006`) | historical | **diff 04 → 02 to see exactly what revision 9 changed** (one correction inside R9-1 was made before fingerprinting: §5 → §6 citation) |
| 05 | IR-P-02-R4 (verbatim + recorder note on its misprinted 63-character fingerprint) | historical, not corrected | findings A-1 … G-3 |
| 06 | IR-P-02-R3 | historical | R3-1 … R3-8 |
| 07 | IR-P-02-R2 | historical | R2-1 … R2-6 |
| 08 | IR-P-02-R | historical | F-01-R … F-07-R1 |
| 09 | IR-P-02 | historical | F-01 … F-07 |
| 10 | IR-P-01 findings extract (full report never saved) | historical | F-01 … F-04 |
| 11 | superseded revision-7 register (a pointer line has since been appended, so its hash differs from the R3/R4 packages) | historical | trace carried-forward obligations |
| 12 | decision record | **binding** | D-12 (decision-date competence), D-110, D-111, **D-113**, D-164, D-166, D-167, D-170, **D-173 … D-189** |
| 13 | multi-database design | **accepted (D-178)** | ISO-1 … ISO-11, INV-ACCESS-1 (§16 M-6), conformance notes |
| 14 | strategic baseline acceptance act | **accepted (D-173)** | context ownership: Platform owns registry etc., never authority |
| 15 | H-1 analysis | accepted into the baseline (D-173) | INV-ACCESS-1 §H, D-166 controls §D |
| 16 | Governance thin slice | **accepted (D-157)** | **§6** `Resolution` (parts, competence per part, GD-1) and `DecisionLog` (GD-11, GD-12); AuthorityCheck correction notes 1–4 (**note 4: `requestRef`**); act-kind addition notes (K-1, D-163) |
| 17 | strategic context map and governance tactical model | **PROPOSED (working baseline, not ratified)** | where GD-1, GD-11, GD-12 are defined |
| 18 | AuthorityCheck contract review | review; its result was adopted into file 16 as correction notes | source of the field mapping |
| 19 | I-TDD-01 Identity design | **PROPOSED** (superseded where D-113 applies) | `ReferenceIssuer`; PersonRef ↔ Account binding |
| 20 | XD-3 ARB act | **RAT (ratified)** | Identity rules |
| 21 | I-TDD-02 scope | scope only (other session) | shows Identity claim/binding design is pending |

## 2. Known missing or unresolved dependencies (reported by the packager)

| # | Dependency | Finding |
|---|---|---|
| **M-1** | **AuthorityCheck act kinds `designate-platform-contacts` and `give-case-grant`** | **No accepted text defines them.**<br>- Revision 4 M-10 announced a "thin-slice conformance note"; **it was never written** (file 16 contains neither term).<br>- R9-1 offers it "for the acceptance act".<br>- **The Principal has directed that this must not be deferred silently. Determine (see §3, Q1).** |
| **M-2** | **Identity: resolution of an organisation `PersonRef` to a bound Account** (rev 4 M-5, R9-1 predicate 3) | **Only in PROPOSED I-TDD-01** (file 19).<br>- I-TDD-02 (claims and bindings) is at scope stage (file 21).<br>- **Ratified basis:** XD-3 (file 20) and D-110, D-111, D-113 (file 12).<br>- **No accepted Identity contract specifies this resolution operation.** |
| **M-3** | **GD-1, GD-11, GD-12** (Resolution immutability; decision-log positions) | Used by the accepted thin slice (file 16), but **defined only in a PROPOSED document** (file 17) |
| **M-4** | **`ruleRef` / `policyVersion` recorded per Resolution part** (R9-1 predicate 4) | **The accepted shape includes ruleRef and policyVersion in the answer (file 16, correction note A-2), and file 16 §6 records competence per part.** Whether a part's recorded competence evaluation retains those two values as fixed data is **the author's reading, to be verified** |

## 3. Questions

1. **R9-1, AuthorityCheck mapping (and M-1).** Verify against files 16, 18 and 12 (D-12):
   - **act kind;**
   - **`evaluatedFor` = decision date,** if and only if that matches the accepted meaning;
   - **subject identifier** = (decisionRef, Resolution part, designated PersonRef);
   - **request identifier:** unique, per reconciliation request, consumed once;
   - **PersonRef → Account through Identity:** no email matching, and no reliance on a mere Account–organisation association (D-113; M-2);
   - **`ruleRef` / `policyVersion`** checked against the cited part's recorded competence evaluation (M-4);
   - **no reuse** across person, decision, part or request;
   - **fail closed** on mismatch, missing evidence, replay, `NotAuthorised` or `Undetermined`;
   - **no second schema and no new Governance fields.**

   **Critical:** does the accepted Governance contract **already support** `designate-platform-contacts` (and `give-case-grant`)?
   - **If it is a clarification:** cite the exact provisions and state the note required.
   - **If it is an extension:** state the precise change and its consequences, flag any Principal decision or Governance ratification needed, and **do not treat it as accepted or deferrable to Platform acceptance.**
2. **R9-2, handover version and serialisation.** Check that:
   - the increment and the state change are atomic, with the outbox in the same transaction;
   - admission and recovery follow the lock protocol;
   - delayed, duplicated, reordered and retried deliveries cannot restore an older state;
   - terminal states never reactivate;
   - withdrawal during handover holds;
   - no new race appears between grant, copy and outbox.

   **Trace the full lifecycle.**
3. **R9-3, operation identity and start claim.** Check:
   - global uniqueness;
   - the registry binding (grant, request, scope, kind, operator);
   - reuse refused;
   - one executor per `started`;
   - durable, serialised `started`;
   - no silent second execution;
   - failure before and after the admission commit;
   - no exactly-once claim;
   - **the registry holds platform metadata only, no organisation content.**

   **Check the central / organisation database boundaries.**
4. **R9-4, D-189 deadline.** Check:
   - the central clock at admission;
   - min(expiry, limit);
   - only the remaining duration is passed on;
   - restart, retry, queue delay or clock difference cannot extend it;
   - **work actually stops**;
   - no result after the deadline;
   - backup-copy work is bound by the same deadline;
   - unconfirmed termination leads to destruction and a report, explicit and auditable;
   - refusal if the time cannot be established.

   **A timeout instruction is not proof that access stopped.**
5. **R9-5, D-188 reporting.** Check that the report covers:
   - the exact failed designation;
   - every suspended grant;
   - every operation that may have accessed data, with its status and uncertainty;
   - the audit evidence.

   Check that it is complete, durable and traceable, and uses permitted channels only. **Access already completed cannot be undone.**
6. **R9-6, proof register.** Compare the full design with the full register:
   - coverage of every design obligation;
   - no applicable earlier obligation lost;
   - D-series covers all R9 behaviour;
   - C-series still applicable;
   - nothing presented as executed;
   - the fingerprint is exact.
7. **Additional duties:**
   - re-check every R4 finding;
   - look for new contradictions with accepted decisions or ownership;
   - Platform takes no ownership of organisation authority, Governance decisions, Membership or Finance facts;
   - central metadata stays minimal;
   - no content access implied by service-operation grants, Account associations, contact status or recovery;
   - failure, retry, concurrency, stale-data and recovery paths;
   - **name unsupported assumptions and missing evidence, including M-1 … M-4.**

## 4. Classification and report

**Classifications:**

| Class | Meaning |
|---|---|
| **[a]** | design defect |
| **[b]** | proof obligation |
| **[c]** | Principal decision |
| **[d]** | resolved / accepted |
| **[TU]** | technical uncertainty |
| **[UNKNOWN]** | the sources do not support a conclusion |

**What each finding must give:**
- **[a]:** exact revision 9 section and line; the decision or contract reference; a concrete failure scenario; why the design fails; the minimum correction.
- **[b]:** the property; the register id; the test or failure-injection scenario; the evidence needed to mark it executed.
- **[c]:** the business consequence in plain language and **one precise question**. **Manufacture none.**

**Report** ("IR-P-02-R5 — Focused Re-Review: Platform Revision 9"):
1. integrity: manifest, and **the subject fingerprint recalculated by you**;
2. questions 1–7;
3. findings register;
4. verdict: READY FOR PRINCIPAL ACCEPTANCE · READY WITH EXPLICIT PRINCIPAL DECISION · NOT READY (design acceptance ≠ build readiness);
5. identity and prior involvement.

**Rules:**
- decide nothing for the Principal;
- modify nothing;
- reopen no accepted decision;
- claim no acceptance;
- **stop after the report.**
