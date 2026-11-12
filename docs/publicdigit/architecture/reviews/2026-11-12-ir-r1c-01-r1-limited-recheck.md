# IR-R1C-01-R1 — Limited Re-check

**Date:** 2026-11-12  
**Reviewer:** GPT-6 in ChatGPT  
**Commission:** `01-2026-11-12-ir-r1c-01-r1-limited-recheck-commission.md`  
**Subject:** Release-1 tactical completion and implementation blueprint, revision 3  
**Subject SHA-256:** `983d9cfea15fb174588ead482a069811b46c211f92e694e7a163a42e220db70f`

## 1. Integrity and scope

The uploaded ZIP was extracted and checked.

- ZIP SHA-256: `2f35f26b8bb19e382e9c584c0820831deabda6b392867bc65690386466bcaeeb`.
- `MANIFEST.sha256`: **14/14 files verified**.
- Outer `SHA256SUMS`: **16/16 files verified**.
- Frozen subject file 03 matches the commissioned fingerprint exactly: `983d9cfea15fb174588ead482a069811b46c211f92e694e7a163a42e220db70f`.
- Diff file 05 was reviewed against revision 2.
- Commission file 01 was read in full.

This is a limited re-check only. The strategic baseline and D-214 are not reopened. No files were modified, no tests were run, and no implementation was authorised.

## 2. Executive result

| Subject | Verdict |
|---|---|
| T-1 — office coverage | **NOT READY** |
| T-2 — non-member capacities | **READY FOR ARB ACCEPTANCE** |
| T-4 — delivery contact | **NOT READY** |
| K-C1 — strict per-Membership record time | **READY FOR ARB ACCEPTANCE** |

Revision 3 resolves A-02, A-03 and A-06. A-01 is directionally sound and additive to GD-12, but its expanded application to AuthorityCheck and K-7 is not yet expressed as a complete shared query rule or adequately covered by the proof set. A-04/A-05 are only partly resolved: endpoint resolution is moved to delivery, but `NoContact` can still be determined and excluded at snapshot time, which conflicts with D-210 §7 item 9. The B-05 claims are not all independently established by the supplied evidence.

## 3. A-01 — Governance knowledge boundary

### 3.1 Is the proposed mechanism sound?

**Yes, in principle.** The proposed Governance boundary is a coherent completeness mechanism:

- the per-organisation DecisionLog head retains `lastPosition` and `lastRecordTime` (D-208 §3.2, GD-11/GD-12);
- the same head row also retains `closedThrough`;
- a writer takes the head lock, reads `clock_timestamp()`, and refuses if the clock regresses below `lastRecordTime` or is at/below `closedThrough`;
- a close takes the head row with `FOR UPDATE NOWAIT`; when busy it reports only the previously committed boundary; when free it advances only if the database clock has not regressed below `lastRecordTime`;
- a query beyond the established boundary refuses rather than returning `NotCovered`.

This follows the accepted completeness meaning in CR-01 §3: facts at or before `closedThrough` are committed and visible, and none can appear later. It also follows the no-timestamp-adjustment rule.

### 3.2 Does it extend GD-12 without changing its meaning?

**Yes.** GD-12 remains: record time comes from the database clock, never decreases with DecisionLog position, and a regression causes refusal/retry; no timestamp is adjusted. The new `t ≤ closedThrough` condition is an additional write guard needed to preserve a closed interval. It does not change gap-free positions under GD-11 or the accepted record-time rule.

The close rule allowing equality with `lastRecordTime` is coherent with GD-12's non-decreasing (not strictly increasing) Governance record-time rule. After the boundary is committed, a later append at that same time is refused by the new `t ≤ closedThrough` guard.

### 3.3 AuthorityCheck and K-7

The consequence identified in revision 3 is correct: Governance's other queries already promise answers “as known at τ”. AuthorityCheck (Governance thin slice §6.2) and K-7 `ParticipationRuleAsAt(τ)` (D-210 §4) cannot provide complete historical answers beyond Governance's committed knowledge boundary.

This is **not a new business rule or a change to the accepted meaning** of AuthorityCheck or K-7. It is the missing completeness mechanism required to make their existing “as known at τ” promise reliable. A boundary refusal must remain a refusal/indeterminate source result; it must never be converted into “not authorised”, “no participation rule”, or a negative answer.

### 3.4 What remains incomplete

Revision 3 says that one Governance boundary serves T-1, AuthorityCheck and K-7, but it defines the query refusal protocol only for `OfficeCoverageAsAt`. It does not explicitly state the shared normative rule for AuthorityCheck and K-7, nor does the new proof list test their use of the same boundary.

**Minimum correction:**

1. Add a common Governance query rule: every Governance query evaluated “as known at τ” — including `OfficeCoverageAsAt`, `AuthorityCheck` and `ParticipationRuleAsAt` — must use the same per-organisation `governanceClosedThrough`; if `τ` is beyond the committed boundary after the close attempt, it returns the appropriate refusal/indeterminate result, never a negative business answer. Answers are read only after the relevant boundary commit.
2. Add proof cases for AuthorityCheck and K-7: a τ beyond the boundary refuses; a τ within it uses the same boundary; neither converts incomplete knowledge into a negative result.
3. Make T1-5's concurrency case explicit about the NOWAIT-busy path and the clock-regression/no-advance path, not only the final “no false NotCovered” outcome.

**A-01 is substantively sound but not completely specified for the three-query scope introduced by revision 3.**

## 4. A-02 — `validTenures`

**Resolved.** The response now returns `validTenures` only. The accompanying table defines validity in Governance: in term on `d`, has a Basis, organ has not ended, and basis is not annulled or voided as known at `τ`; a pending challenge does not change validity. Overlapping tenures remain valid under D-171's non-exclusive default, and the consumer does not re-evaluate appointment validity.

No further correction is required for A-02.

## 5. A-03 — T-2 date and knowledge-time alignment

**Resolved.** T-2 now says that, whenever combined with K-4, both queries use the same `τ`, and T-2's business date is the organisation-local date corresponding to that `τ` for current operations. K-4 remains unchanged as `MembersOfScopeAsAt(unit, τ)` / `BelongingWithAncestorsAsAt(person, τ)`.

One wording precision is recommended: K-4's published signature has no separate `d` parameter. The text should not imply that a new date argument is being added to K-4. State instead that T-2 derives its `d` from the organisation-local date corresponding to the shared `τ`, while K-4 is called with that same `τ` and retains its accepted signature.

The substantive temporal alignment is clear enough for ARB acceptance. T2-5 is the appropriate proof obligation; it remains unexecuted.

## 6. A-04 and A-05 — T-4 delivery contact

### 6.1 What is corrected

The split between snapshot and delivery is directionally correct and matches the two requirements in D-210 §7:

- item 3: the one-τ snapshot stores a contact reference, not the endpoint;
- item 9: contact is read at delivery from the Organisation person profile.

Resolving the current endpoint at delivery also avoids retaining a stale email endpoint in Communication. The prohibition on using Identity's login address remains correct.

The `Unavailable` outcome is a sensible fail-closed delivery state: Organisation cannot answer now, so the record is not treated as a recipient exclusion and remains queued for retry. This is consistent with the outbox/restart and non-accepted retry requirements in D-210 §7 items 5–6.

### 6.2 Remaining contradiction: `NoContact` is still decided at snapshot time

The API at lines 139–145 still allows the snapshot query to return `NoContact { reason }`:

`DeliveryContactRefsAsAt(...) → ... contactRef | NoContact { reason }`

The newsletter flow at §5.1 step 9 then excludes `NoContact` before the audience snapshot is committed. That means contact existence is evaluated at send/snapshot time. D-210 §7 item 9 instead says contact is read at delivery.

This creates a concrete edge case: if a recipient has no email address at snapshot time but adds one before the delivery attempt, revision 3 has already excluded that recipient. It never reaches `ResolveDeliveryContact`, so the current profile contact is not read at delivery. It also leaves the meaning of `contactRef` unclear when the underlying contact is replaced after the snapshot.

### 6.3 Minimum correction

- The snapshot must store a stable reference to the recipient's Organisation person-profile contact slot (person + channel, or an equivalent stable profile reference), not a snapshot of whether a contact endpoint exists.
- The snapshot step must not return `NoContact` or exclude a recipient because no endpoint exists at snapshot time.
- `ResolveDeliveryContact` resolves the current profile endpoint from that stable reference at each delivery attempt.
- Only delivery-time resolution may return `NoContact { reason }`; persist the reason on the delivery record and exclude that recipient from delivery.
- If Organisation is temporarily unavailable, retain `queued` and retry.
- Define replacement semantics: if the contact changes after the snapshot, delivery uses the current endpoint; if no current endpoint exists, return a profile-grounded `NoContact` reason.

The `NONE_RECORDED` and `CONTACT_ENDED` reasons are plausible profile-state reasons, not invented legal/policy exclusions. However, they belong to the delivery-time result, not to snapshot-time audience construction.

T4-2 should additionally test “no contact at snapshot, contact added before delivery → current contact is used”, and replacement/removal between snapshot and delivery. T4-3 should verify that a delivery-time `NoContact` reason is persisted and that `Unavailable` remains queued without marking the recipient excluded.

**A-04 is only partly resolved; A-05's reason model is acceptable but is attached to the wrong point in the flow.**

## 7. A-06 — scheduler cadence

**Resolved.** Revision 3 correctly distinguishes:

- accepted requirement: due scheduled resignations must be executed in the `closedThrough` step before the boundary passes their effective date; the step must run independently of user reads (D-209 and D-210 §5.2);
- proposed operational choice: a Laravel scheduler running the step every minute.

No source-attribution defect remains. The exact cadence is an operational parameter, not a new Principal business decision.

## 8. Added proofs and B-05 verification

### 8.1 B-01 — T-1 proofs

T1-5 through T1-7 add useful coverage for beyond-boundary refusal, concurrent recording, annulment/void knowledge time, pending challenge, containing-forum scope and holder-identity minimisation.

**Not yet complete for the expanded A-01 scope.** Add the AuthorityCheck and K-7 shared-boundary tests described in §3.4, and make the NOWAIT-busy and clock-regression close cases explicit. None of the proofs has been executed.

### 8.2 B-02 — T-2 proofs

T2-5 addresses the missing shared-τ/business-date alignment. T2-4 continues to cover deduplication when both routes qualify. The contract preserves the important distinction that a suspended member does not qualify through the member route, but may qualify independently through a named staff capacity under D-213.

The new proof is adequate for A-03 as a contract-level proof obligation. It remains unexecuted. Keep the no-Membership-dependency, empty-answer, read-versus-posting and independent-capacity cases from the existing proof set.

### 8.3 B-03 — T-4 proofs

T4-2 and T4-3 cover endpoint changes, reference-only snapshots, reason-bearing `NoContact` and `Unavailable` queueing. They do **not** cover the snapshot-time exclusion defect identified in §6.2. Add the “contact absent at snapshot but present at delivery” case and contact replacement/removal cases. Until T-4 is corrected, these proofs do not establish conformance with D-210 §7 item 9.

### 8.4 B-04 — K-C1 proof

**Adequate as a proposed proof.** KC1-2 directly protects the required distinction: equal database-clock readings for different Membership streams remain permitted where D-209's organisation-wide guard permits them, while K-C1 refuses equality within the same Membership stream to preserve V-5. KC1-1 and KC1-2 together cover both sides of the rule.

Neither proof has been executed.

### 8.5 B-05 — G-1c, G-1d and G-3

**G-1c — absence of ledger-clock state:** the blueprint reports a full search of `app/`, `database/`, `tests/`, `routes/` and `config/`, but the package does not include the search output or the complete schema/migration inventory. The assertion may be accurate, but this reviewer cannot independently verify repository-wide absence from the supplied evidence. Keep it marked “reported by repository search; independent evidence not included”, not independently verified.

**G-1d — bounded retry:** the supplied `DecideAndAppend.php` excerpt proves that a bounded three-attempt loop exists and re-loads/re-decides after `ConcurrencyConflict`. It does **not** show that a new clock/boundary refusal joins that loop:
- `AppendOutcome.php` contains no clock/boundary refusal case;
- `DecideAndAppend.php` retries only when the match result is `null`, currently mapped only from `ConcurrencyConflict`;
- all other listed outcomes return immediately.

Therefore the statement “the application already has a bounded retry loop” is supported; the statement that the new clock refusal already uses that loop is not established by the code supplied. The required implementation change must add an explicit retryable append outcome for clock/boundary conflict and map it to a fresh attempt in the existing bounded loop. Exhaustion must fail closed, with no timestamp adjustment and no duplicate acceptance. Add an integration proof for that mapping and the exhaustion path.

**G-3 — InterimRoleAuthority / AuthorityCheckPort bindings:** the blueprint reports that `InterimRoleAuthority` is referenced only by its unit test and that `AuthorityCheckPort` is not bound in any provider. No provider/binding inventory or raw search output is included in this package. The claim is not independently verifiable from the supplied files. Retain the desired architecture direction, but mark the repository-wide binding statement as reported, pending reproducible search evidence or an architecture test.

## 9. New high-severity contradictions introduced by revision 3

1. **T-4 snapshot-time `NoContact` exclusion conflicts with D-210 §7 item 9.** This is the principal new/remaining contract contradiction and must be corrected as in §6.3.
2. **G-1d overstates retry wiring if read as an existing code fact.** The code excerpt has no clock-refusal outcome or retry mapping. This is an evidence/conformance gap, not a reason to change D-209; the blueprint must state the required code change precisely.
3. No other new high-severity contradiction was identified in the limited scope.

## 10. Findings register

| ID | Class | Subject | Finding | Required treatment |
|---|---|---|---|---|
| R1-01 | [A] | T-1 / A-01 | Boundary mechanism is sound and additive, but the shared rule for AuthorityCheck and K-7 is not fully specified or tested. | State the common Governance query-boundary rule and add AuthorityCheck/K-7 proof cases; explicitly test NOWAIT contention and regression/no-advance. |
| R1-02 | [E] | T-1 / A-02 | `validTenures` resolves the ambiguous payload. | No correction. |
| R1-03 | [E] | T-2 / A-03 | Shared τ and organisation-local business date are specified without changing K-4. | Clarify that K-4 has no new `d` parameter; retain its accepted signature. |
| R1-04 | [A] | T-4 / A-04 | Snapshot still determines `NoContact` and excludes before delivery. | Store a stable profile/channel reference at snapshot; resolve presence and current endpoint only at delivery. |
| R1-05 | [A] | T-4 / A-05 | Reason-bearing `NoContact` is appropriate, but it must be a delivery-time outcome. | Move `NoContact` and its persisted reason to `ResolveDeliveryContact`; add absent-then-added and replacement tests. |
| R1-06 | [E] | G-2 / A-06 | Scheduler frequency is correctly labelled as a proposal. | No correction. |
| R1-07 | [B] | T-1 proofs | Added tests do not yet cover AuthorityCheck/K-7 boundary use and all close contention/regression paths. | Add the specified proof cases; execute before implementation acceptance gates. |
| R1-08 | [B] | T-4 proofs | Added tests miss a contact added after snapshot and replacement/removal semantics. | Add the specified proof cases. |
| R1-09 | [B] | G-1c / G-3 | Repository-wide absence/binding claims are not reproducibly evidenced in the handover. | Include search output/inventory or an architecture test. |
| R1-10 | [B] | G-1d / K-C1 implementation | Existing retry loop is proven, but new clock-refusal mapping is not present in supplied code. | Add a retryable append outcome, map it to the bounded fresh-decision loop, and prove exhaustion/fail-closed behavior. |
| R1-11 | [E] | K-C1 / B-04 | KC1-1 plus KC1-2 express same-stream strictness and cross-stream equality correctly. | No design correction; proof execution remains pending. |

## 11. Verdicts

### T-1 — NOT READY

The Governance boundary algorithm is sound and extends GD-12 without changing its accepted meaning. However, revision 3 expands the boundary to AuthorityCheck and K-7 without fully specifying the shared refusal rule or proving those two contracts use the same boundary. Add the common query rule and proof cases. No business decision is required.

### T-2 — READY FOR ARB ACCEPTANCE

The non-member capacity query remains Organisation-owned, does not call Membership, preserves K-4 unchanged, and aligns its business date with the shared organisation-local date of τ. T2-5 is an appropriate proof obligation. No new business policy decision is introduced.

### T-4 — NOT READY

The snapshot still excludes recipients for `NoContact` before delivery. D-210 §7 item 9 requires the profile contact to be read at delivery. Store a stable profile/channel reference at snapshot and move all contact-presence evaluation and `NoContact` exclusion to delivery-time resolution.

### K-C1 — READY FOR ARB ACCEPTANCE

K-C1 preserves COR-4 V-5 without timestamp adjustment and does not impose global strict increase across different Membership streams. KC1-1 and KC1-2 are the right complementary proof obligations. The implementation must add and test the retryable clock-refusal outcome; the supplied code does not yet show that mapping. This is an implementation proof gate, not a new business decision.

## 12. Principal decision guidance

No new business-policy decision is required by this re-check.

- **T-1:** the business rule remains “do not say no moderator when Governance cannot prove that it knows enough”. The technical boundary must apply consistently to forum coverage, actor authority and participation rules.
- **T-2:** Organisation may recognise a worker/guest through its own valid belonging without consulting Membership. This is not a decision to grant posting rights.
- **T-4:** Communication must not decide whether an email exists at newsletter-snapshot time. It stores a profile reference; Organisation supplies the current endpoint when delivery actually occurs.
- **K-C1:** one Membership's record times stay strictly increasing without artificially moving timestamps; separate Memberships may share a database-clock reading.

D-214 remains ratified. D-208, D-209, D-210, D-213 and the other accepted decisions are not reopened.

## 13. Stop

This report completes the commissioned limited re-check. No proof was executed, no implementation was authorised, no accepted decision was changed, and no repository file was modified. Stop.
