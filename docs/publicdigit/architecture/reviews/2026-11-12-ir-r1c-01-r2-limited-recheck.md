# IR-R1C-01-R2 — Limited Re-check

**Date:** 2026-11-12  
**Reviewer:** GPT-6 in ChatGPT  
**Commission:** `01-2026-11-12-ir-r1c-01-r2-limited-recheck-commission.md`  
**Frozen subject:** Release-1 tactical completion and implementation blueprint, revision 4  
**Subject SHA-256:** `c08ebc3563db4d7da76d3182c42ffa405471cd869fdff2a42d3fa92fd8b87855`

## 1. Integrity and scope

The uploaded handover ZIP was read and checked.

- ZIP SHA-256: `343beca7b5233cd12c2913c7fb48c3699bde6ab5569013a299b75c89732b5e6f`.
- `MANIFEST.sha256`: **12/12 files verified**.
- Outer `SHA256SUMS`: **14/14 files verified**.
- Frozen subject file 03 matches the commissioned SHA-256 exactly: `c08ebc3563db4d7da76d3182c42ffa405471cd869fdff2a42d3fa92fd8b87855`.
- Diff file 05 was reviewed against revision 3.
- Repository evidence file 06 was reviewed.
- Commission file 01 was read in full.

The scope is limited to T-1, T-4, G-1d, B-05, and new high-severity contradictions introduced by the revision 3 → 4 edits. D-214, D-215, T-2 and K-C1 are not reopened. No tests were run, no implementation was authorised, no files were modified.

## 2. Executive verdict

| Subject | Verdict |
|---|---|
| T-1 — shared Governance completeness boundary | **READY FOR ARB ACCEPTANCE** |
| T-4 — delivery-time contact resolution | **READY FOR ARB ACCEPTANCE** |
| G-1d — retry-loop requirement | **RESOLVED AS A DESIGN REQUIREMENT; IMPLEMENTATION GATE REMAINS** |
| B-05 — repository evidence | **SUFFICIENT FOR THE CLAIMS AS BOUNDED IN REVISION 4** |

Revision 4 resolves the two remaining design defects identified in IR-R1C-01-R1. No new high-severity contradiction was found in the commissioned edits.

The two contracts are ready for your ARB consideration. Their proof obligations remain unexecuted build gates; this report does not certify implementation.

## 3. T-1 — G-Q1 shared Governance boundary

### 3.1 One boundary, close then read

**Pass.** Revision 4 §4 T-1 lines 85–112 defines one per-organisation `governanceClosedThrough` on the Governance DecisionLog head and applies it to all three relevant queries:

- `OfficeCoverageAsAt` (T-1);
- `AuthorityCheck`;
- `ParticipationRuleAsAt` (K-7).

The close-then-read sequence is explicit: the query first runs the closing step; the boundary is committed; the answer is read in a new statement after that commit. This is the necessary serialization/completeness protocol. The same persisted per-organisation boundary is shared; the queries do not invent independent watermarks.

The proposed close mechanism remains additive to GD-12:
- every recording takes the per-organisation head lock;
- record time is `clock_timestamp()`;
- the existing refusal on `t < lastRecordTime` remains unchanged;
- the added refusal on `t ≤ closedThrough` prevents a later append from appearing inside an already closed interval;
- the close takes the head row `FOR UPDATE NOWAIT`;
- if busy, it uses the previously committed boundary;
- if free, it advances only when the database clock has not regressed below `lastRecordTime`;
- no timestamp is adjusted.

This preserves GD-11's gap-free positions and GD-12's refusal-on-regression/no-adjustment semantics. It does not change the meaning of Governance's accepted decision or authority rules.

### 3.2 Query-specific indeterminate results

**Pass.** Revision 4 §4 T-1 lines 103–111 correctly preserves each contract's own result:

| Query | Beyond-boundary result | Required consequence |
|---|---|---|
| T-1 | `Refused(BOUNDARY_NOT_CLOSED)` | Never `NotCovered` |
| AuthorityCheck | `Unknown(BOUNDARY_NOT_CLOSED)` | Consumer proceeds no further; no `NotAuthorised` finding is recorded; act waits |
| K-7 | Refused | Never "no rule" or an unverified platform default |

This is consistent with the accepted principle that an indeterminate authority result is not a negative authority finding. The consumer may fail closed operationally, but it must not create a `NotAuthorised` business fact from incomplete Governance knowledge.

Terminology note: the original AuthorityCheck §6.2 shape uses `Unknown(reason)`, while the later corrected evidence shape in the same thin-slice source uses `Undetermined(reason)` as the `outcome` value. Revision 4 follows the §6.2 query-result label and preserves the same indeterminate meaning. During implementation, the adapter must map the boundary result to the canonical AuthorityCheck outcome without turning it into `NotAuthorised`.

### 3.3 Proof coverage

**Pass as proof obligations.** The added cases address the previous gap:

- T1-5: a NOWAIT-busy close uses the stored boundary; a regressed clock does not advance it; concurrent recording cannot produce a false `NotCovered`.
- GQ-1: AuthorityCheck beyond the boundary returns `Unknown`, never `NotAuthorised`; an in-boundary answer reads through the same boundary.
- GQ-2: K-7 beyond the boundary refuses rather than treating absence of a visible rule as the D-161 default.
- GQ-3: T-1, AuthorityCheck and K-7 use the same per-organisation `governanceClosedThrough` at a given τ.

These cases cover the additions required by IR-R1C-01-R1 §3.4. They are not executed; their implementation and test results remain build gates.

### 3.4 T-1 verdict

**READY FOR ARB ACCEPTANCE.**

The shared boundary and query-specific refusal behavior are now specified. No business rule has been changed. The implementation must preserve the new close-then-read protocol and the canonical indeterminate AuthorityCheck mapping.

## 4. T-4 — contact slot and delivery-time resolution

### 4.1 Snapshot behavior

**Pass.** Revision 4 §4 T-4 lines 148–169 and newsletter flow §5.1 steps 9–10 now match D-210 §7 items 3 and 8:

- the snapshot stores a stable slot `(personRef, channel: email)`;
- no Organisation contact call is made at snapshot time;
- no endpoint is stored;
- absence of a current email at snapshot time does not exclude the recipient;
- eligibility, inclusion basis, membership/belonging and opt-out remain fixed at τ as required.

The slot identifies the person's current email channel in the Organisation profile, not a particular historical contact record. This is the right reference semantics for delivery-time resolution.

### 4.2 Delivery behavior

**Pass.** `ResolveDeliveryContact(organisationId, contactSlot)` is called at each delivery attempt. It returns the current endpoint and a `contactRecordRef`, or `NoContact`, or `Unavailable`.

This resolves the three required changes:
- contact added after the snapshot but before delivery: the current contact is used;
- contact replaced after the snapshot: the replacement is used;
- contact removed after the snapshot: delivery-time `NoContact` is returned.

The endpoint is transient for the attempt. The delivery record stores the contact record reference used, not the address. The design prohibits use of Identity's login address.

### 4.3 NoContact and Unavailable

**Pass.** `NoContact { reason: NO_CURRENT_CONTACT }` is only returned at delivery. It excludes the recipient from that issue and records the reason, matching D-210 §7 item 9.

`Unavailable` is expressly not an exclusion. The record remains queued and is retried, consistent with D-210 §7 items 5–6. The newsletter flow applies these outcomes at step 11, not during audience snapshot construction.

### 4.4 Multiple current contacts

**The [UNR] is an honest and correctly located gap, not a T-4 defect.**

The profile's own designation determines which current contact is returned. If Organisation's profile design does not designate one contact for a channel where several are current, the missing selection rule belongs to Organisation's profile/contact tactical design. T-4 should not invent a ranking, choose an arbitrary address, or transfer contact ownership to Communication.

This is a real pre-implementation dependency: Organisation must define or guarantee the profile designation behavior before a production delivery adapter can resolve ambiguous multiple contacts. It does not invalidate T-4's bounded contract because the contract explicitly leaves the unresolved profile rule open rather than silently deciding it.

### 4.5 Proof coverage

**Pass as proof obligations.**

- T4-2 covers absent-at-snapshot then added-before-delivery, replacement, and removal with delivery-time `NoContact`.
- T4-3 covers `Unavailable` staying queued and never becoming excluded.
- T4-4 prohibits endpoint persistence in the snapshot and delivery record; only the slot and `contactRecordRef` are retained.
- T4-5 protects accepted deliveries from resend and retains the provider idempotency key `(issueId, recipient)`.

The existing T4-1 login-address prohibition remains applicable. None of these proofs has been executed.

### 4.6 T-4 verdict

**READY FOR ARB ACCEPTANCE.**

The contract now reads the Organisation profile at delivery, not at snapshot. It preserves the D-210 snapshot boundary and delivery semantics. The multiple-contact designation issue is honestly deferred to Organisation and must be resolved before implementation of that profile case.

## 5. G-1d — retry-loop requirement

**Pass as a design requirement.**

Revision 4 §6 G-1d correctly states the present fact: the bounded three-attempt loop exists but retries only `ConcurrencyConflict`; clock and boundary refusals do not enter it today.

The required implementation change is precise:
1. Add an explicit retryable append outcome for clock/boundary refusal, including `t ≤ closed_through`, `t < last_recorded_at`, and K-C1 same-Membership equality.
2. Route that outcome into the existing bounded retry loop.
3. Each retry must reload state and re-decide; it must not reuse a stale decision.
4. Exhaustion must refuse the command (fail closed).
5. No timestamp adjustment is permitted.
6. Existing idempotency must prevent duplicate acceptance.

G1d-1 expresses the corresponding integration proof: retryable refusal enters the loop, fresh load/re-decision occurs, exhaustion refuses, no adjusted time is written, and no duplicate acceptance occurs.

The evidence does not claim this implementation already exists. The requirement is correct, but the implementation and integration test remain a build gate. No code was changed or executed during this review.

## 6. B-05 — repository evidence

**Sufficient for the bounded G-1c and G-3 statements in revision 4.**

### G-1c — no ledger-clock state

The evidence file provides raw `git grep` output across `app`, `database`, `tests`, `routes`, `config` and `bootstrap` for `closed_through`, `last_recorded_at`, `ledger_clock` and `closedThrough`. It also lists the Membership-related migrations and the three `org_membership*` tables created by the current Membership migrations.

This supports the bounded statement that no such ledger-clock state is present in the searched repository paths at the recorded HEAD/working-tree snapshot. It does not claim the code has already implemented the proposed ledger clock. The untracked file listed in the evidence is a Vote anonymity test and does not contain the searched ledger-clock state.

### G-3 — interim authority binding

The evidence lists the references to `InterimRoleAuthority` and `AuthorityCheckPort`, and supplies the binding search over providers, bootstrap and the Membership context. Within those searched paths and binding forms, no container binding for the interim authority is found. The architecture-test requirement in revision 4 is appropriate to prevent accidental future binding.

The evidence is appropriately bounded to the captured repository state and search paths. It is not a proof that no possible dynamic or indirect binding could ever be introduced; the proposed architecture test is the ongoing safeguard.

### G-1d — retry behavior today

The raw evidence lists the current `AppendOutcome` cases and the `DecideAndAppend` loop. It supports the corrected statement that the loop is bounded (`MAX_ATTEMPTS = 3`) and retries only `ConcurrencyConflict`; there is no clock/boundary refusal outcome today. Revision 4 no longer claims otherwise.

## 7. New high-severity contradictions

**None identified** in the revision 3 → 4 edits within the commissioned scope.

The specific issues from IR-R1C-01-R1 are resolved:
- T-1 now gives AuthorityCheck and K-7 the same Governance completeness boundary as T-1.
- T-4 no longer checks contact availability or excludes recipients at snapshot time.
- `NoContact` is a delivery-time result; `Unavailable` remains queued.
- G-1d is explicitly an implementation requirement rather than a claim about existing code.

The unresolved multiple-contact designation is explicitly identified as an Organisation tactical gap and does not introduce a contradictory decision.

## 8. Findings register

| ID | Class | Subject | Finding | Treatment |
|---|---|---|---|---|
| R2-01 | [E] | T-1 | Shared Governance boundary and close-then-read protocol now cover T-1, AuthorityCheck and K-7. | Resolved; preserve exact protocol in implementation. |
| R2-02 | [E] | T-1 | Query-specific indeterminate results preserve business meaning and prevent false negative authority/coverage/rule answers. | Resolved; map `Unknown` to the canonical indeterminate AuthorityCheck outcome. |
| R2-03 | [E] | T-1 proofs | T1-5 and GQ-1 … GQ-3 cover the prior missing cases. | Proofs remain unexecuted build gates. |
| R2-04 | [E] | T-4 | Snapshot stores a stable profile/channel slot only; contact is resolved at each delivery attempt. | Resolved. |
| R2-05 | [E] | T-4 | `NoContact` and `Unavailable` are correctly distinguished at delivery. | Resolved. |
| R2-06 | [UNR] | Organisation profile | Multiple current contacts without a profile designation have no selection rule. | Honest Organisation tactical gap; resolve before implementing ambiguous multi-contact profiles. |
| R2-07 | [E] | T-4 proofs | T4-2 … T4-5 cover addition, replacement, removal, queueing, non-persistence and no resend. | Proofs remain unexecuted build gates. |
| R2-08 | [E] | G-1d | Retry loop is accurately described as existing only for concurrency conflicts; clock refusal integration is an implementation requirement. | Implement and prove in the relevant slice; no design correction. |
| R2-09 | [E] | B-05 | Repository evidence supports the bounded G-1c/G-3 statements at the captured state. | Retain scope and capture metadata; architecture test for G-3. |

## 9. Principal decision guidance

No new business-policy decision is required by this re-check.

- **T-1:** the business protection is that PublicDigit must not say "no moderator", "not authorised", or "no participation rule" when Governance has not established a complete knowledge boundary. A refusal or unknown result means wait, not a negative finding.
- **T-4:** newsletter eligibility is fixed at the snapshot, but the contact address is read from the Organisation profile at delivery. This allows a newly added or replaced email to be used without changing the frozen audience decision.
- **Multiple emails:** Organisation, not Communication, must define which current contact is designated when more than one exists. The blueprint has correctly not invented that rule.

D-214 and D-215 remain unchanged. T-2 and K-C1 are not reopened. No implementation is authorised by this review.

## 10. Stop

This report completes the commissioned second limited re-check. T-1 and T-4 are each READY FOR ARB ACCEPTANCE. Proofs remain unexecuted; no implementation was authorised; no repository file was modified. Stop.
