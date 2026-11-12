<!-- Recorder note (Claude, 2026-11-11): GPT-6's IR-P-02-R2 focused re-review (ChatGPT), as pasted by the Principal; extracted verbatim from the session transcript. Body unaltered. -->

# IR-P-02-R2 — Focused Re-Review: Platform Revision 6

**Date:** 11 November 2026  
**Commissioning authority:** The Principal (D-94)  
**Reviewer:** GPT-6 in ChatGPT  
**Prior involvement:** Author of IR-P-02 and IR-P-02-R. This is not a blind review.  
**Subject:** `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`  
**Subject SHA-256:** `d23d93ea5af08559ca54b9ac72c5945c686e13c88ae7c6c9422bafbfdc4b9ece`  
**Verdict: NOT READY**

---

## 1. Integrity

The supplied review package was inspected.

- All seven files listed in `MANIFEST.sha256` match their recorded SHA-256 hashes.
- All entries in the outer `SHA256SUMS` match.
- The subject revision 6 fingerprint exactly matches the commissioning document.
- The previous IR-P-02 and IR-P-02-R reports were available and used as the finding baseline.
- The accepted decision record, multi-database design and Governance thin slice were consulted.

Revision 6 was reviewed as proposed, not accepted. Claude's self-checks were not treated as independent evidence.

No source files were modified.

## 2. Executive assessment

Revision 6 substantially improves the design.

**Correctly implemented or substantially resolved:**
- D-183 prevents directly added contacts from approving emergency grants.
- D-184 provides an explicit decision-date precedence rule.
- D-185 establishes `pending` as a Platform registry status.
- Emergency-route operation is distinguished from constitutional validity.
- The verified counterparty remains a transmission channel, not an authority-holder.
- The revised approval transaction has a coherent lock order for the listed approval and contact-update paths.
- The design avoids a cross-database atomic transaction.

**Still unresolved:**

1. Whether the first approver must remain current until the second approval.
2. Whether Organisation-side withdrawal is effective during the period after database recovery but before the grant copy reaches the Organisation database.
3. Whether a direct contact is promoted to `organisation-record` only after Governance returns `Authorised`.
4. Whether the backup-copy route fails closed when delivery has begun but the Organisation-side grant copy is not yet present.
5. Some concurrency and recovery proof obligations remain unexecuted or insufficiently specified.

These issues prevent Principal acceptance.

---

## 3. Assessment by commissioning question

### Question 1 — R6-1: D-182 and D-183

**Status: PARTIAL**

**References:** R6-1 §§684–703; D-180, D-182, D-183.

#### What is resolved

D-183 is implemented correctly in principle.

- An approver must be a current `organisation-record` contact.
- A `direct` contact cannot approve, regardless of the 24-hour period.
- A direct contact may receive notices and withdraw a grant, as D-183 permits.
- Fewer than two current Organisation-recorded contacts means emergency granting is unavailable.
- A direct entry can be promoted after recovery when the Organisation's records confirm the designation.

The accomplice-capture scenario is closed as an access-grant attack:

X can remove Y and Z and add A, but A cannot approve merely because 24 hours have elapsed. X alone cannot grant access.

The consequence is denial of emergency access until the Organisation's records can be consulted. This is the consequence expressly accepted in D-183.

No minimum membership duration is introduced. That is correct.

Initial contacts during provisioning are also conceptually sound: their designations must be recorded in the Organisation database. This does not create an additional one-month membership rule.

#### Finding R2-1 — First approver must remain current at second approval

**Classification: (c) Genuine Principal question**  
**Severity: Medium**

R6-1 §703 adds this requirement:

> At the second approval, the first approver must still be a current `organisation-record` entry; otherwise the request lapses.

This is a conservative interpretation, but it is stricter than the explicit wording in D-182:

> Both approvers must be current and grant-capable at the moment of each approval.

Two readings are possible:

**Choice A:** Each person's eligibility is checked when that person gives their approval. If the first approver is subsequently removed, their already-given approval remains valid.

**Choice B:** Both approvers must still be current and Organisation-recorded when the second approval is given. If the first approver has been removed, the request lapses.

The design currently implements Choice B.

Business consequence: Choice B may cause more emergency grant requests to lapse, but ensures that two currently authorised contacts support the grant when it becomes `given`.

I do not treat this as automatically authorised merely because it narrows access. It changes the conditions under which a grant can be completed.

**Principal clarification required.**

#### Finding R2-2 — Promotion must explicitly require Governance validation

**Classification: (a) Design defect Claude can correct**  
**Severity: High**

R6-1 §694 says a `direct` entry becomes `organisation-record` if the Organisation's records designate that person.

R6-2 §§715–721 says constitutional validity belongs to Governance and that `NotAuthorised` and `Undetermined` do not establish authority.

These rules should be connected explicitly.

A record appearing in the Organisation database must not, by itself, promote an emergency contact if the underlying designation decision has not been validated.

**Required correction:** promotion must require the relevant designation to be confirmed through the recovery process with Governance returning `Authorised`. `NotAuthorised` or `Undetermined` must not promote the entry.

Business consequence if left ambiguous: a contact could acquire approval eligibility from a designation whose authority has not been established.

This is a tactical clarification, not a new Principal decision.

---

### Question 2 — R6-2: provisional operation and constitutional validity

**Status: RESOLVED**

**References:** R6-2 §§705–722; D-175, D-176, D-181; Governance AuthorityCheck.

The distinction is correctly made:

| Provisional emergency-route operation | Constitutional validity |
|---|---|
| Platform applies mechanical registration checks during the outage | Governance determines whether the Organisation decision was valid |
| Sender, evidence metadata and D-184 precedence | AuthorityCheck on recovery |
| Temporary effect on the emergency route only | Only `Authorised` establishes authority |

The verified service counterparty is correctly preserved as a transmission channel. Its act of sending a registration does not confer authority or validate the underlying decision.

`NotAuthorised` and `Undetermined` never establish authority.

The design also correctly requires reconciliation and flags grants given under a registration that is not validated.

**Classification: (d) Already resolved by accepted decisions D-176 and D-181, correctly represented in R6-2.**

No further Principal decision is required.

---

### Question 3 — R6-3: D-184 precedence

**Status: RESOLVED**

**References:** R6-3 §§724–733; D-184; revision 5 part 2 F-03.

The rules are coherent for the listed cases.

| Scenario | Assessment |
|---|---|
| Older decision arrives later | Refused as stale |
| Newer decision arrives earlier | Newer cited date prevails |
| Duplicate | Idempotent |
| Retry | Idempotent |
| Delayed delivery | Duplicate or stale according to cited date |
| Future-dated decision | Refused |
| Same date, different decisions, first already applied | First stays; second refused; both flagged |
| Same date, concurrent decisions | `ServedOrganisation` linearisation determines which commits first |
| Same decision, same digest | Idempotent |
| Same decision, different digests | Both refused; an already-applied conflicting entry is withdrawn, notified and flagged |
| Recovery | Organisation's authoritative records replace the Platform list |

The different-digest rule is consistent with the earlier revision 5 part 2 evidence-integrity rule. It does not mean that two conflicting document versions are accepted as equivalent.

D-184 is not reopened. The cited decision date is the accepted precedence rule.

**Classification: (d) Already resolved by D-184 and correctly represented in R6-3.**

The associated concurrency and recovery tests remain [TU]; this does not mean the decision itself is unresolved.

---

### Question 4 — R6-4: Organisation-side withdrawal, expiry and P-3

**Status: NOT RESOLVED**

**References:** R6-4 §§735–757; D-164, D-166, D-170; revision 5 part 2 F-05.

#### What is technically sound

For a `live-database` operation:

- The protected operation runs in the Organisation database.
- The Organisation's grant-copy row is read with a shared lock when present.
- Organisation-side withdrawal takes that row exclusively.
- An operation already admitted may complete.
- A later operation must observe the withdrawal and be refused.

For a `backup-copy` operation:

- Admission checks the Organisation's copy status after copy delivery has begun.
- An unreachable Organisation database results in refusal.
- A withdrawal after admission affects the operation already in flight; the next operation is refused.

The expiry admission rule is coherent in principle:

- The gateway obtains the Platform shared lock.
- It reads `t_admit`.
- Admission is allowed only when `t_admit < expiry`.
- The operation has a deadline at expiry.
- No result may be delivered after expiry.

#### Distributed transaction assessment

**R6-4 does not introduce a distributed transaction in the atomic-commit sense.**

The Platform grant transaction and Organisation database transaction are separate. There is no two-phase commit or atomic commit across both databases.

However, the design does use a cross-database coordination protocol: a central shared lock is held while an Organisation-side operation runs and its local grant state is checked.

That is a valid architectural distinction, but it creates failure and recovery cases that must be proved. In particular, an Organisation operation may have occurred even if the central outcome record remains `attempted` without a final result. The existing conservative reconciliation rule must remain.

D-164, D-166 and INV-ACCESS-1 are not inherently contradicted by this mechanism, provided the Organisation-side read is limited to grant metadata and all content access remains behind the named case grant and operator scope.

#### Finding R2-3 — The recovery-to-copy handoff leaves a withdrawal gap

**Classification: (c) Genuine Principal question**  
**Severity: High**

R6-4 §§741–748 does not fully establish the claimed zero-delay withdrawal guarantee.

It expressly states that before the grant is copied into the Organisation database, the Organisation has no copy and cannot withdraw it; the Platform row continues to govern.

That creates a handoff interval:

1. The Organisation database becomes available again.
2. The Platform begins delivering the grant copy through the outbox.
3. The grant copy has not yet arrived or been acknowledged.
4. The Platform grant can still govern access.
5. The Organisation does not yet have the local grant row against which R6-4 says it can withdraw.

The time between recovery and copy acknowledgement has no specified maximum.

The statement “propagation latency: zero” is therefore true only after the Organisation-side grant copy exists and the local withdrawal can be committed. It does not establish zero delay across the entire recovery handoff.

The backup-copy case has a related omission: once an outbox record exists, admission reads the Organisation copy status, but R6-4 does not explicitly state what happens if the database is reachable and the grant-copy row is not yet present.

**The Principal must choose:**

- **Choice A:** The central D-170 pending grant remains effective until the Organisation-side copy is acknowledged. The Organisation cannot withdraw through its local grant row during that interval.
- **Choice B:** Once the Organisation database is available and copy delivery has begun, protected operations fail closed until the Organisation-side grant state is present and can be checked. This may temporarily deny access during the handoff, but avoids continued access under an unconfirmed local state.

My recommendation is Choice B because it avoids an undefined revocation interval. However, this is a business/security availability trade-off, not something I can accept for the Principal.

**P-3 is not yet resolved without a Principal decision.**

#### Finding R2-4 — Missing Organisation grant-copy state needs an explicit rule

**Classification: (a) Design defect Claude can correct**  
**Severity: High**

For the `backup-copy` case, R6-4 §§743–747 specifies:

- withdrawn → refused;
- Organisation database unreachable → refused.

It does not explicitly specify the outcome when the database is reachable but the grant-copy row is absent because delivery has not completed.

The safe rule is to refuse admission until the copy is present and its state is confirmed. The design must state this explicitly and make it consistent with the Principal's P-3 choice.

Business consequence if left undefined: different implementations could either fail closed or continue using the central grant, producing inconsistent revocation behaviour.

---

### Question 5 — R6-5: concurrency matrix and proof sufficiency

**Status: PARTIAL**

**References:** R6-5 §§759–783; particularly rows 1, 2, 2a, 3, 3a–3c, 5, 6 and 8.

#### Lock-order assessment

The described order is:

`ServedOrganisation` → `PendingCaseGrant` → Organisation-side grant copy.

The listed operations do not introduce a lock-order cycle:

- Approval: `ServedOrganisation` shared, then `PendingCaseGrant` exclusive.
- Contact update: `ServedOrganisation` exclusive.
- Grant withdrawal, close and expiry: `PendingCaseGrant` exclusive.
- Organisation-side withdrawal: Organisation grant-copy row exclusive.
- Protected operation: Platform grant row shared, then Organisation grant-copy row shared.

No listed path acquires these locks in reverse order.

The approval transaction checks both contact authority and grant state. Row 2a appropriately tests the three-way race between second approval, contact removal and withdrawal.

#### Row assessment

| Row | Assessment |
|---|---|
| 1 — Contact removal vs approval | Correct structure; both orders specified |
| 2 — Second approval vs withdrawal | Correct structure; first approver recheck depends on P-1 clarification |
| 2a — Three-way race | Correctly identifies all six serial orders |
| 3 — Platform withdrawal vs use | Consistent with the central shared/exclusive lock model |
| 3a — Organisation withdrawal vs live use | Correct structure; both orders specified |
| 3b — Organisation withdrawal vs backup use | Incomplete proof specification |
| 3c — Expiry | Admission boundary is coherent; proof cases are appropriate |
| 5 — Duplicate grant | Correct uniqueness rule; different scope must be rejected |
| 6 — Contact-update ordering | Consistent with D-184 |
| 8 — Evidence reconciliation | Correctly includes `Undetermined` and grant flagging |

#### Finding R2-5 — Backup-copy concurrency proof is incomplete

**Classification: (b) Remaining proof obligation [TU]**  
**Severity: Medium–High**

Row 3b specifies that an unreachable Organisation database results in refusal. It does not fully specify the concurrency proof for withdrawal versus admission.

The proof must cover:
- admission before withdrawal;
- withdrawal before admission;
- withdrawal after the admission read but before execution;
- missing grant-copy row while delivery is pending;
- database failure during admission;
- confirmation that the next operation after withdrawal is refused.

The row must demonstrate that a read and a concurrent withdrawal are serialised consistently. A bare status read is not sufficient evidence of this guarantee.

#### Other proof obligations

All revision 6 proof tests remain unexecuted [TU].

In particular:
- row 2a's six-order test;
- row 3a's local withdrawal race;
- row 3c's expiry boundary, delayed execution and no-result-after-expiry assertion;
- row 8's `Undetermined` reconciliation;
- the central/Organisation failure window where an operation may have occurred but its outcome was not recorded.

These are proof obligations, not findings that the design is necessarily wrong. They remain release/build gates.

---

### Question 6 — R6-6: D-185 and lifecycle

**Status: RESOLVED**

**References:** R6-6 §§785–790; D-175, D-166, D-178, D-185.

The lifecycle is consistent with D-185:

| Status | Meaning |
|---|---|
| `pending` | Application recorded; no technical operation |
| `provisioning` | Counterparty verified and service-operation grant recorded |
| `operational` | As previously defined in revision 5 |

The authorised clarification note on D-175 explains that “stays provisioning until verified” means no technical operation before verification, realised by `pending` preceding `provisioning`.

This also agrees with the D-178 conformance note.

**Classification: (d) Already resolved by D-185.**

No further Principal decision is required.

---

### Question 7 — New high-severity contradictions

**Status: NEW — two material gaps identified**

1. **R6-1 promotion condition:** the text should explicitly require Governance `Authorised` before an emergency contact is promoted from `direct` to `organisation-record`. Otherwise the interaction with D-181 is ambiguous.
2. **R6-4 recovery handoff:** the design claims immediate Organisation-side withdrawal but leaves the period before grant-copy delivery without a complete revocation rule.

Neither finding requires reopening D-183, D-184 or D-185. They concern how revision 6 implements the accepted rules.

No separate contradiction with D-164, D-166, ISO-1 … ISO-11 or INV-ACCESS-1 was established from the supplied material. The cross-database lock coordination is not itself an atomic distributed transaction.

---

## 4. Findings register

| Finding | Classification | Severity | Principal decision? |
|---|---|---|---|
| R2-1 — First approver remains current at second approval | (c) Genuine Principal question | Medium | Yes |
| R2-2 — Promotion requires Governance `Authorised` | (a) Design defect | High | No |
| R2-3 — Recovery-to-copy withdrawal gap | (c) Genuine Principal question | High | Yes |
| R2-4 — Missing Organisation grant-copy state | (a) Design defect | High | No |
| R2-5 — Backup-copy concurrency proof | (b) Proof obligation [TU] | Medium–High | No |
| R2-6 — Remaining concurrency and recovery proofs | (b) Proof obligation [TU] | Medium–High | No |
| D-183 approver eligibility | (d) Already resolved by accepted decision | — | No |
| D-184 precedence | (d) Already resolved by accepted decision | — | No |
| D-185 lifecycle status | (d) Already resolved by accepted decision | — | No |
| R6-2 provisional vs constitutional validity | (d) Already resolved by D-176 and D-181 | — | No |

---

## 5. Genuine Principal questions remaining

Only two questions require Principal clarification.

### P-1 — First approver revalidation

Should the first approver still be a current `organisation-record` contact when the second approval is given?

- **A:** Eligibility is checked separately at each person's approval time.
- **B:** Both must still be current at the second approval; otherwise the request lapses.

Revision 6 currently implements B.

### P-3 — Recovery-to-copy handoff

Should the central D-170 pending grant remain effective until its Organisation-side copy is acknowledged, or should protected operations fail closed during the handoff?

- **A:** Central grant remains effective until copy acknowledgement.
- **B:** Protected operations are refused until the Organisation-side grant state is present and checked.

Revision 6 currently leaves the live-database Platform row governing before the local copy exists. The backup-copy missing-row case is not explicit.

---

## 6. Verdict

# NOT READY

Revision 6 is a substantial improvement and correctly implements the main intent of D-183, D-184 and D-185.

However, it is not ready for Principal acceptance because:

1. The first-approver revalidation rule needs a Principal clarification.
2. P-3 remains unresolved: the recovery-to-copy handoff is not covered by the claimed zero-delay withdrawal guarantee.
3. The promotion from `direct` to `organisation-record` needs an explicit Governance `Authorised` condition.
4. The backup-copy missing-row and concurrency behaviour needs correction and proof.

The next revision should address these points without reopening accepted decisions.

No implementation is authorised by this review.

---

## 7. Reviewer identity and prior involvement

**Reviewer:** GPT-6 in ChatGPT.  
**Commissioning authority:** The Principal (D-94).  
**Prior involvement:** Author of IR-P-02 and IR-P-02-R.

This review is not blind. It independently checks Claude's revision 6 against the previous findings and the supplied accepted decisions. Claude's self-check was not treated as independent evidence.

**No files were modified. No decision was made for the Principal. No acceptance is claimed.**

**End of report.**
