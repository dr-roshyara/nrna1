# IR-GM-01 — Independent Review: Governance Completion rev 4, M-TDD-01 rev 3, Ledger-Clock Amendment

**Review date:** 12 November 2026  
**Reviewer:** GPT-6 (OpenAI), ChatGPT  
**Review type:** Independent tactical DDD and architecture review  
**Commission:** IR-GM-01, Principal (D-94), 2026-11-12

## 1. Integrity and evidence

The commission (file 01) was read first. `MANIFEST.sha256` was independently recalculated against the ZIP contents, and the three subject fingerprints were independently recalculated.

| Check | Result |
|---|---|
| Manifest entries | 25/25 verified |
| S1 — Governance completion rev 4 | Exact fingerprint match |
| S2 — M-TDD-01 rev 3 | Exact fingerprint match |
| S3 — Ledger-clock amendment | Exact fingerprint match |
| Subject modifications detected | None |

Verified fingerprints:

- S1: `7903c0286971cd4f02e0d901c47eb468b4a7b0b12a3d2366b8fefba74a762f31`
- S2: `b033127607673cfc6332d69ce8bfbb68f144790b551439fa7a33c0d7f1287324`
- S3: `2e8cd2bcd68e93758b64a702bc8727bbb63b078d7bc5b2092d01a73a568b5219`

File 05 was treated as navigation only, not independent evidence. The review is based on the three subjects and the binding/supporting sources supplied in the package. Accepted and ratified decisions were not reopened.

**Proof status:** No database, concurrency, integration or architecture proof was executed by this review. Anything that only tests can establish is classified [B].

## 2. Overall assessment

The three documents are substantially coherent with the accepted PublicDigit architecture. The author has addressed the difficult interactions between scheduled resignation, Membership's temporal ledger, Governance authority, Organisation belonging, and release-1 communications.

Two design defects require correction before acceptance:

1. **S1 §2.5:** a tribunal determination remedy is incorrectly grouped under Category A as a Governance decision subject to D-202. D-202 distinguishes tribunal remedies from Resolutions, and S1 §3.3 itself describes remedies as separately applied facts.
2. **S3 §2:** the free-lock `closedThrough` path does not reject a database clock reading earlier than `last_recorded_at` when there are no due scheduled resignations to execute. It can advance `closed_through` during clock regression, contrary to Reference Geography PO-6.

No additional demonstrated design defect was found. Several proof obligations and external dependencies remain open.

## 3. Questions 1–10

### Q1. Scheduled resignation — D-204, D-205

**Assessment: [E] Design logic resolved; [B] implementation proof outstanding.**

S2 §5.2 and S3 §2 establish the right fundamental mechanism:

1. A scheduled resignation is held outside the Membership fact ledger.
2. No future-dated End is published.
3. Execution happens on or after the effective date.
4. The End is recorded before `closedThrough` advances beyond the effective date.
5. If execution fails, the transaction rolls back and the old boundary remains.
6. Consumers querying beyond that boundary refuse rather than assume membership.

This is consistent with D-201, D-204 and D-205.

**Execution versus withdrawal:** Both operations acquire the organisation's ledger-clock lock, check the declaration state, and use a compare-and-set to transition it. The transaction records either `executed` or `withdrawn`, not both. The lock order is consistent with the accepted Membership specification's correction note: clock lock first, membership version compare-and-set second.

**D-205 guard:** The exception is appropriately narrow: only `End(resignation)` from a scheduled declaration, only execution on its effective date. The original declaration date remains `decidedAt`. Every other Membership fact retains the existing effective-date guard. This does not authorise general future-dated facts.

**Availability cost:** The fail-closed trade-off is justified. If a due resignation cannot be executed, C2 refuses queries beyond the last completed boundary. Historical queries within the existing boundary can still be answered. The relevant outstanding proofs are MI-1, MI-2, MI-3, MI-4 and MI-10, plus the amended ledger-clock proofs.

**Qualification:** S3's clock-regression omission must be corrected before claiming full conformance with PO-6.

### Q2. Category A / Category B and `ownAct`

**Assessment: [E], subject to finding A-01.**

S1 correctly distinguishes:

| Situation | Governance decision record? |
|---|---|
| Member resigns | No |
| Death recorded from evidence | No |
| Competent organ excludes a member | Yes |
| Competent organ annuls a decision | Yes |
| Officer corrects a record | No |
| Officer voids a record | No |
| Tribunal remedy applied | Separate remedy fact, not a Resolution |

The `ownAct` addition is consistent with D-203 and RA's decision-provenance requirement. It means the member acts in their own capacity; it creates no authority for another person.

Exclusion is correctly organ-only. An officer may record or transmit the organ's decision but cannot exercise the exclusion competence merely by holding an office.

The pull/push distinction is correct:
- Self-resignation and recording acts: pull.
- Organ exclusion: push with its Resolution part.
- Annul by a competent body: push with its new decision.
- Tribunal remedy: separately applied fact.

**A-01 — correction required:** S1 §2.5, line 121, groups both competent-body Annul and tribunal remedy under Category A. This contradicts D-202 item 8, S1 §3.0 (D-202 applies to Resolutions and their parts), and S1 §3.3 (tribunal remedies are `RemedyApplied` / `RemedyInapplicable` facts with their own log positions).

Minimum correction: split the two routes. Only a competent body's Annul Resolution is Category A. A tribunal remedy must be recorded as its own applied fact, citing the Determination, without manufacturing a Resolution or applying the Resolution competence-evaluation mechanism to it. The Membership Annul must still receive the decision provenance required by RA §9.

### Q3. Corrections — D-206 and RA §9

**Assessment: [E] Resolved at design level; [B] proof outstanding.**

S1 §2.5 and S2 §5.4 respect the distinction:

| Operation | Authority |
|---|---|
| Correct | Recording authority plus evidence |
| Void | Recording authority plus evidence; reported and challengeable |
| Annul | Competent body for original decision, or tribunal remedy |

No proposed change to the ratified correction algebra was found.

- Correct does not become a new decision.
- Void does not become a new decision.
- Annul remains restricted to decision provenance.
- A pending challenge does not itself reverse a fact.
- Adjudication does not write Governance or Membership facts.

The application of a tribunal remedy belongs to Governance, not Adjudication. Finding A-01 concerns the classification and record treatment of that remedy, not the authority D-206 grants.

### Q4. Decision-record guarantees and X-3

**Assessment: [E] Design coverage present; [B] proof outstanding.**

S1 §3 provides this mapping:

| D-202 guarantee | Record carrying it |
|---|---|
| 1. Organ and sitting/method | Resolution |
| 2. Competence per part | CompetenceEvaluation |
| 3. Rules and versions | CompetenceEvaluation |
| 4. Supporting evidence | Resolution and CompetenceEvaluation |
| 5. Decision and effective dates | Resolution and Part |
| 6. What was decided | Part |
| 7. Knowledge and recording order | DecisionLog |
| 8. Corrections and challenges | Correction and remedy records |

The three GD rules are appropriately defined:

- **GD-1:** immutable original Resolution, parts and evaluations.
- **GD-11:** gap-free committed positions.
- **GD-12:** database clock, refusal on regression, no timestamp adjustment.

GD-12 is consistent with the Geography contract in principle.

GC-8 should verify retrieval of per-part `ruleRef` and `policyVersion` for every competence-evaluation kind, including gap-declared and contradiction cases, after later rule changes.

**X-3:** Per-part rule/version evidence closes the identified Platform and Identity dependency at design level. It does not close the implementation or proof gate.

### Q5. Ledger-clock amendment

**Assessment: [A] Specific correction required; [B] proofs outstanding.**

The proposed amendment is mechanism-only in its intended scope:

- C1/C2 response shapes remain unchanged.
- Refusal semantics remain unchanged.
- `closedThrough` retains its accepted meaning.
- One database clock replaces the application clock.
- Timestamp adjustment is removed.

It aligns with PO-6 and PO-7 for the append path.

**A-02 — missing regression guard:** S3 §2, line 20, describes the free-lock `closedThrough` path: read `t`, execute due resignations if any, otherwise advance `closed_through` using `max(closed_through, t)`. The path does not independently reject `t < last_recorded_at`.

Example:

| Value | Time |
|---|---|
| Existing `closed_through` | 10:00 |
| Existing `last_recorded_at` | 10:05 |
| Database clock after regression | 10:03 |

If there is no due scheduled resignation, the proposed algorithm advances `closed_through` to 10:03. That is a clock-regression operation without refusal, contrary to PO-6.

**Minimum correction:** Before advancing `closed_through`, require `t >= last_recorded_at`. If the condition fails, do not advance the boundary; roll back or return the stored boundary unchanged, flag the anomaly and retry later. Equality is permissible; regression is not.

**D-1 coupling:** The coupling itself is sound:
- NOWAIT contention returns the previously committed boundary.
- A failed scheduled execution rolls back the entire closure transaction.
- The boundary cannot pass the due date until the End has been committed.
- A1–A3 retain their intended transaction-ordering basis.

However, A1–A3 must be rerun against the corrected implementation. They have not been proved.

### Q6. Organisation integration

**Assessment: [E] Design consistent; [B] proof outstanding.**

S2 §6 is consistent with accepted Organisation §49–§50.

- Admission can name a unit.
- Placement follows admission through an idempotent integration.
- The admission fact is the basis.
- The root-unit default protects the member until placement is applied.
- A non-member placement cannot create membership.
- Workers and guests retain their own capacity.
- An applicant has no belonging merely because an application exists.
- Belonging history is retained after membership ends.
- Membership coverage determines when a membership-dependent belonging counts.

Historical answers use C2, not the C1 projection. This is also consistent with accepted Organisation §38.5.

The key test is MI-12: admission followed by exactly one placement, including retries, failures and the root default.

### Q7. Newsletter delivery

**Assessment: [E] Design-level protocol coherent; [B] and [C] outstanding.**

S2 §7 provides a substantially complete delivery protocol.

| Concern | Assessment |
|---|---|
| One send per issue | Correct |
| Audience snapshot | Correct |
| One τ | Correct |
| Per-recipient records | Correct |
| No read tracking | Correct |
| Late opt-out | Correct |
| Contact source | Organisation profile |
| Partial source failure | Refuse entire send |
| Retry | Non-accepted records only |
| Crash recovery | Outbox and persistent delivery records |

The protocol respects D-151, D-158, D-161 and D-168.

Two qualifications:

1. **[C] Provider dependency:** the mail provider must genuinely support the required idempotency key and retain its deduplication guarantee over the retry period. The design cannot guarantee this merely by supplying a key.
2. **[B] Proof:** MI-11 must exercise the crash-after-provider-acceptance scenario, retry behaviour, late opt-out races and duplicate-send prevention.

Newsletter legal basis and retention remain matters for counsel, not architectural defects.

### Q8. Forum

**Assessment: [E] Design consistent; [B] proof outstanding.**

S2 §8 respects the accepted boundaries:

- Effective belonging determines reading.
- Workers and guests read where placed.
- Applicants read only when placed as Guest.
- Lapsed and suspended members retain reading access.
- Posting requires reading eligibility, the participation rule and an open forum.
- A moderator's valid designation, not online presence, determines whether the forum is open.
- Committee forums remain a later-release requirement.

D-153 and D-161 are correctly reflected, with the D-168 rule override retained.

The proof suite should explicitly exercise workers and guests under the default read-only rule and under an applicable written-rule override. MI-7 and MI-14 do not yet make that coverage explicit.

### Q9. Reference Geography

**Assessment: [E] Release boundary correct; [C] later integration dependency.**

S2 §9 correctly keeps Reference Geography out of release 1.

The later-release design retains:
- Stable `ReferenceUnitId`.
- Valid time versus knowledge time.
- `knownAtPosition`.
- Coverage and belonging review after geographical revisions.
- No automatic movement of members.
- No inference of membership, entitlement or belonging from geography.

PO-1 through PO-8 and the four-point Identity contract remain visible.

The platform-level person-reference dependency is correctly identified as unresolved.

The Organisation document fingerprint drift is also correctly disclosed. It must be reconciled under the existing change-control rule at the next acceptance act; it does not invalidate the substantive review of the supplied current text.

### Q10. New high-severity contradictions

Two demonstrated design defects were found:

1. S1's classification of tribunal remedies under Category A.
2. S3's missing clock-regression guard in the `closedThrough` path.

No further high-severity contradiction with the supplied binding sources was found.

## 4. Findings register

### [A] Design defects

| ID | Subject / reference | Finding | Minimum correction |
|---|---|---|---|
| A-01 | S1 §2.5 line 121; §3.0 line 133; §3.3 lines 163–164; D-202 item 8; D-206 | Tribunal determination remedy is grouped with Category A Governance decisions, although D-202 and S1 §3.3 require tribunal remedies to be separate applied facts. | Split competent-body Annul Resolution from tribunal RemedyApplied/RemedyInapplicable. Do not manufacture a Resolution for a tribunal remedy. Preserve RA §9 provenance for Membership Annul. |
| A-02 | S3 §2 line 20; Geography v3.11 §6 PO-6 | The free-lock `closedThrough` path can advance the boundary when `closed_through < t < last_recorded_at`, if no due resignation causes an append guard to run. | Before advancing, require `t >= last_recorded_at`; otherwise leave the boundary unchanged, flag and retry. |

### [B] Proof obligations

All proofs listed below remain **NOT EXECUTED**.

| ID | Subject / reference | Required evidence |
|---|---|---|
| B-01 | S1 §7 GC-1–GC-16 | Execute the Governance authority, immutability, log ordering, corrections, remedy, placement and challenge tests. |
| B-02 | S1 GC-6, GC-11; GD-11/GD-12 | Concurrent recordings, rollback/no-gap behaviour, equal timestamps, clock regression refusal and no adjusted timestamps. |
| B-03 | S1 GC-8 | Retrieve per-part rule/version evidence after rule changes for all competence-evaluation kinds. |
| B-04 | S2 §14 MI-1–MI-14 | Execute the scheduled resignation, withdrawal, correction, Organisation, Newsletter, Forum and D-207 tests. |
| B-05 | S2 MI-10 and S3 LC-4 | Prove that the completeness boundary cannot pass a due resignation date before its End is committed, including failure rollback. |
| B-06 | S2 MI-12 | Prove admission-to-placement exactly once, including duplicate delivery, crash/retry, root default and ordering interleavings. |
| B-07 | S2 MI-7 / MI-14 | Explicitly test workers and guests as read-only under the default, applicants without Guest placement, and the applicable written-rule override. |
| B-08 | S2 MI-11 | Test provider acceptance followed by process crash, retry, idempotency, late opt-out and race interleavings. |
| B-09 | S3 §5 LC-1–LC-5 | Prove no adjusted timestamp, regression refusal, one database clock, D-1 closure and A1–A3 under the amended mechanism. |
| B-10 | S2 §14 A1–A3 | Rerun the accepted C1/C2 transaction-ordering proofs after the S3 correction. |

These are proof obligations, not additional paper design defects.

### [C] External dependencies

| ID | Reference | Dependency |
|---|---|---|
| C-01 | S2 §7; D-151 | Counsel confirms newsletter legal basis and retention. |
| C-02 | S2 §5.2; D-201 | Counsel confirms the legal effective time of resignation and any applicable notice rules. |
| C-03 | S2 §7 delivery protocol | The selected mail provider supports the required idempotency semantics and retention period. |
| C-04 | S3 §4 | Operational monitoring and alerting for stuck scheduled-resignation execution and clock anomalies. |
| C-05 | S2 §9; Geography v3.11 §8 | Later-release platform-level person-reference contract remains open; I-TDD-02 does not supply it. |
| C-06 | S2 §6; D-156 / D-173 | Organisation model fingerprint drift must be reconciled at the next acceptance act under the existing change-control rule. |

### [D] Principal decisions

| ID | Decision remaining with the Principal / ARB |
|---|---|
| D-01 | Accept or reject Governance completion rev 4 after A-01 is corrected. Acceptance is also the ratification route for D-203 `ownAct`. |
| D-02 | Accept or reject M-TDD-01 rev 3 after its dependency on the corrected ledger-clock mechanism is resolved. |
| D-03 | ARB acceptance or rejection of the S3 ledger-clock mechanism amendment after A-02 is corrected. |

No additional business-rule decision is required by the reviewed designs. The reviewer makes none of these decisions.

### [E] Resolved

| Subject | Resolution |
|---|---|
| Self-resignation authority | D-194 and D-203; `ownAct` is restricted to self-service resignation where actor equals subject. |
| Future-dated resignation | D-201 and D-204; scheduled declaration outside the ledger. |
| Narrow future-effective End exception | D-205; only scheduled resignation execution. |
| Correction authority | D-206; Correct/Void by recording authority with evidence; Annul by competent body or tribunal remedy. |
| Applicant forum access | D-207; Guest placement required. |
| Standing for Newsletter and Forum | D-161 and D-168. |
| Organisation integration | Accepted §49–§50; D-114 and associated decisions. |
| Reference Geography release boundary | D-93 and D-147; no release-1 dependency. |
| C1/C2 contract | Accepted D-162; S3 proposes a mechanism amendment, not a contract change. |

## 5. Verdicts

### S1 — Governance completion tactical design rev 4

**Verdict: READY AFTER SPECIFIC CORRECTIONS**

Required:
1. Correct A-01 so tribunal remedies are separate applied facts, not Category A Resolutions.
2. Complete the proof obligations GC-1–GC-16 before implementation authorisation.

The remaining Governance design is consistent with D-194, D-201–D-203, D-206, D-207 and the ratified Membership architecture.

### S2 — M-TDD-01 revision 3

**Verdict: READY AFTER SPECIFIC CORRECTIONS**

Required:
1. Resolve its dependency on S3 by correcting A-02 and obtaining the required ARB acceptance.
2. Execute MI-1–MI-14 and the applicable A1–A3 proofs.
3. Confirm the external legal and mail-provider dependencies before launch.

The Membership, Organisation, Newsletter and Forum integration is coherent at design level. No additional business-rule decision is required.

### S3 — C1/C2 ledger-clock amendment

**Verdict: READY AFTER SPECIFIC CORRECTIONS**

Required:
1. Add the missing regression guard to the free-lock `closedThrough` path.
2. Recalculate and publish the amended subject fingerprint.
3. Rerun LC-1–LC-5 and A1–A3.
4. Obtain ARB acceptance before treating Membership's PO-8 conformance as resolved.

The amendment is mechanism-only in intent, but its present text does not fully satisfy PO-6.

## 6. Reviewer identity and prior involvement

**Reviewer:** GPT-6 (OpenAI), ChatGPT.

**Prior involvement:** I previously reviewed PublicDigit Platform tenancy revisions 1–11 and Identity I-TDD-02 revisions 1–12. I had not previously reviewed these three subjects in their submitted revisions.

This report is an independent review of the supplied package, not an implementation review. No code was changed, no decision was accepted, and no implementation was authorised.

**Review complete. Stop.**
