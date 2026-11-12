# IR-GM-01-R1 — Limited Re-check

**Review date:** 12 November 2026  
**Reviewer:** GPT-6 (OpenAI), ChatGPT  
**Commission:** IR-GM-01-R1, Principal (D-94)  
**Scope:** Corrections A-01 and A-02, requested proof edits, and new high-severity contradictions introduced by those edits only.

## 1. Integrity verification

The commission (file 01) was read first. I independently recalculated the manifest and outer checksums from the uploaded ZIP, then independently recalculated all three current subject fingerprints.

| Check | Result |
|---|---:|
| `MANIFEST.sha256` | 11/11 passed |
| `SHA256SUMS` | 13/13 passed |
| S1 Governance completion rev 5 | Exact match |
| S3 ledger-clock amendment rev 2 | Exact match |
| S2 M-TDD-01 rev 4 | Exact match |
| ZIP SHA-256 | Exact match |

The package SHA-256 is:

`ae85b2c6527956b1c7db89330455b4095e74bbf4fdfea01204e843917e1d0df5`

Current subject fingerprints:

- S1: `04e41258a24bd1af1d4d901be32b121604dc476129f225440ada4e5f3c96b9e4`
- S3: `35739576bb2b8c7087233f24032805bf489f217242703bcd2ad5af6c249ef6d8`
- S2: `b4aba1687e3cfcbc3171bd731ef1248b01ce2b9d25400431fdb80659b790c137`

The previous versions and three unified diffs are present and match the manifest. The re-check was performed against the current subject documents and their supplied diffs, not against the author's summary alone.

## 2. Question 1 — A-01: separate Annul routes and RA §9 provenance

**Result: [E] A-01 resolved at design level. [B] One proof-coverage clarification remains.**

### Route 1 — competent body's Annul Resolution

S1 rev 5 §2.5 now describes the competent body's Annul as a new Resolution part (`annul-membership-act`, subject = the fact reference). It is explicitly Category A and D-202 applies. Competence is evaluated for the competent body for the original act on the new decision date.

The Membership Annul carries the Resolution part as its decision provenance, consistent with S1's revision note and S2 rev 4 §5.1–§5.2, which specify RA `Annul` with the part as provenance.

### Route 2 — tribunal determination remedy

S1 rev 5 §2.5 now describes a tribunal remedy as applied by Governance as a separate `RemedyApplied` fact, with its own DecisionLog position and references to the Determination and remedy part.

It expressly states:
- no Resolution is manufactured;
- no Resolution competence evaluation is applied;
- the remedy is not a Category A record under D-202 item 8;
- Membership's Annul carries the remedy application, including the Determination reference, as its RA §9 decision provenance.

S1 §3.3 independently retains the rule that tribunal remedies are applied by Governance as `RemedyApplied` / `RemedyInapplicable` facts with their own log positions, citing the Determination. S2 rev 4 §5.1–§5.2 likewise states that RA `Annul` receives the part or remedy application as decision provenance.

**Conclusion:** the two routes are now properly separated. The tribunal route is not represented as a Resolution and is not subjected to Resolution competence evaluation. The Membership Annul provenance is specified for both routes.

### Proof coverage qualification

GC-17 tests the tribunal route: no Resolution or competence evaluation, a separate remedy fact and log position, and the Determination reference on Membership's Annul.

However, GC-17 does not expressly state that the test must also verify RA §9 provenance for **both** routes, including the competent-body route's Resolution-part reference. The design states this correctly, but the test description should explicitly cover both paths and assert the provenance value delivered to Membership in each.

This is **[B] proof-specification coverage**, not a remaining design defect. Minimum test wording: extend GC-17 (or add a separate test) to exercise both routes and assert:
1. competent-body route: Resolution part is the Membership Annul's decision provenance;
2. tribunal route: `RemedyApplied` fact plus Determination/remedy-part reference is the Membership Annul's decision provenance;
3. tribunal route creates neither Resolution nor CompetenceEvaluation.

## 3. Question 2 — A-02: `closedThrough` clock-regression guard

**Result: [E] A-02 resolved at design level. [B] LC-6 remains unexecuted.**

S3 rev 2 §2 now places the regression guard directly in the free-lock `closedThrough` path, immediately after reading the database clock:

- if `t < last_recorded_at` **or** `t < closed_through`, do not advance;
- commit nothing;
- return the stored `closed_through` unchanged;
- flag the anomaly and retry later;
- equality is allowed.

This closes the rev 1 omission identified in A-02. The guard applies even when there are no due scheduled resignations, so the previous no-resignation bypass is removed.

The guard also preserves the intended ordering:
- when the guard passes, D-1 still executes every due scheduled resignation in the same closure transaction;
- an execution failure rolls back and leaves the stored boundary unchanged;
- only after due executions succeed may the boundary be advanced using `max(closed_through, t)`.

The equality rule is correctly expressed. In particular, `t = last_recorded_at` is not a regression and may pass the guard, provided `t` is also not earlier than `closed_through`.

**LC-6 coverage:** the stated scenario `closed_through < t < last_recorded_at`, with no due resignation, verifies no advancement; `t = last_recorded_at` verifies equality is permitted. This is the direct regression case requested in A-02.

The test remains **NOT EXECUTED**. It must verify that the persisted boundary is byte-for-byte/value-for-value unchanged on refusal, not merely that the operation returns a refusal status.

## 4. Question 3 — requested proof edits

### GC-8 — per-part rule/version evidence

**[E] Coverage wording corrected; [B] execution outstanding.**

GC-8 now explicitly covers every competence-evaluation kind: worked out, asserted, gap declared and contradiction. It also retains retrieval after later rule changes. This addresses the missing coverage identified in IR-GM-01.

The proof must establish the recorded per-part `ruleRef` and `policyVersion` remain retrievable as recorded after subsequent rule changes. GC-8 has not been executed.

### GC-17 — tribunal remedy route

**[B] Substantially covers the tribunal route, but needs the two-route provenance clarification described in §2.**

The no-Resolution/no-CompetenceEvaluation condition and separate remedy log position are now explicit. The test should also verify the competent-body route and both RA §9 provenance references, rather than testing only the tribunal route.

### LC-6 — `closedThrough` regression

**[E] Coverage wording corrected; [B] execution outstanding.**

LC-6 now tests the exact previously missed interval (`closed_through < t < last_recorded_at`) without due resignations and the equality case (`t = last_recorded_at`). It should assert the stored boundary remains unchanged on regression. No execution evidence is supplied.

### MI-11 — provider acceptance and crash

**[E] Coverage wording corrected; [B] execution outstanding.**

MI-11 now explicitly includes a crash after provider acceptance and requires no resend, alongside one send per issue, no duplicate acceptance, late opt-out including races, and no read tracking.

This is the relevant crash window: the provider has accepted the message, but the application may fail before its local delivery state is durably updated. The test should use the actual selected provider's idempotency behaviour (or a faithful provider contract test double) and verify that recovery does not cause a second delivery. The provider's idempotency guarantee remains an external dependency; the test description does not itself prove provider behaviour.

### MI-14 — worker and guest forum permissions

**[E] Coverage wording corrected; [B] execution outstanding.**

MI-14 now explicitly covers:
- an applicant without Guest placement reads nothing;
- workers and guests read where placed;
- workers and guests cannot post under the default;
- they can post when an organisation's written rule under D-168 allows it;
- non-member belongings count by their own validity.

This addresses the missing worker/guest read-only and written-rule override coverage identified in IR-GM-01. The test must exercise both default and override cases; no test has yet run.

## 5. Question 4 — new high-severity contradictions introduced by these edits

**Result: none found.**

Within the limited scope of the supplied changes:

- S1's two Annul routes now align with the D-202 distinction between Resolutions and tribunal remedies.
- S1's remedy facts retain their own DecisionLog positions and Determination references.
- S2's Membership Annul provenance wording is consistent with the two S1 routes and RA §9 as represented in the supplied design.
- S3's `closedThrough` regression guard closes the previously identified bypass and preserves the D-1 closure sequence.
- S2's dependency now names Governance rev 5 and ledger-clock amendment rev 2.
- MI-11 and MI-14 add the requested test scenarios without changing the underlying delivery or forum rules.

I found no new high-severity contradiction introduced by the edits.

The remaining GC-17 coverage clarification is classified [B], not [A], because the design rule is now explicit and only the completeness of the proof coverage remains to be demonstrated.

## 6. Findings register for this limited re-check

| Class | ID | Finding | Status |
|---|---|---|---|
| [A] | — | New or remaining design defect within this limited scope | None found |
| [B] | B-R1-01 | GC-17 should explicitly prove RA §9 provenance for both Annul routes, including the competent-body Resolution-part route | Open; amend test specification and execute |
| [B] | B-R1-02 | LC-6 must assert the stored boundary is unchanged on regression | Execution outstanding |
| [B] | B-R1-03 | GC-8, GC-17, LC-6, MI-11 and MI-14 remain unexecuted | Execution outstanding |
| [C] | C-R1-01 | Actual mail provider's idempotency and deduplication guarantee over the retry period must be confirmed | External dependency |
| [D] | — | No new Principal decision identified by these limited edits | — |
| [E] | E-R1-01 | A-01: two Annul routes separated at design level | Resolved |
| [E] | E-R1-02 | A-02: regression guard added to `closedThrough` path; equality permitted | Resolved |
| [E] | E-R1-03 | GC-8, LC-6, MI-11 and MI-14 wording now addresses requested scenarios | Resolved at test-specification level |

## 7. Verdicts

### S1 — Governance completion tactical design revision 5

**VERDICT: READY FOR PRINCIPAL CONSIDERATION**

A-01 is resolved. The competent-body Annul remains a Category A Resolution; the tribunal remedy is a separate applied fact with no Resolution and no Resolution competence evaluation. RA §9 provenance is specified for both routes.

Before implementation authorisation, extend GC-17 (or add a separate proof) to assert Membership Annul provenance for both routes. GC-8 and the other Governance proofs remain unexecuted.

### S3 — C1/C2 ledger-clock amendment proposal revision 2

**VERDICT: READY FOR PRINCIPAL CONSIDERATION**

A-02 is resolved. The free-lock `closedThrough` path now refuses on regression against either `last_recorded_at` or `closed_through`, allows equality, and leaves the stored boundary unchanged on refusal.

LC-6 and the other ledger-clock proofs remain unexecuted. ARB acceptance remains required; this review does not grant it.

### S2 — M-TDD-01 revision 4

**VERDICT: READY FOR PRINCIPAL CONSIDERATION**

The dependency now points to Governance rev 5 and ledger-clock amendment rev 2. MI-11 and MI-14 contain the requested crash/retry and worker/guest permission scenarios. The two Annul provenance routes are represented consistently with S1 and RA §9.

The proof suite remains unexecuted, and the mail-provider idempotency guarantee remains an external dependency. The design remains proposed and depends on the relevant Principal/ARB acceptance decisions.

## 8. Reviewer identity and stopping statement

**Reviewer:** GPT-6 (OpenAI), ChatGPT.  
**Prior involvement:** Author of IR-GM-01, the independent review being re-checked. This is a limited re-check of the specific corrections requested by the Principal, not a fresh full review.

No files were modified. No acceptance or ratification decision was made. No proof was executed. No implementation was authorised.

**Limited re-check complete. Stop.**
