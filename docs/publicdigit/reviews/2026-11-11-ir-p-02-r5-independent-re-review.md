# IR-P-02-R5 — Focused Independent Re-Review: Platform Revision 9

| | |
|---|---|
| Reviewer | GPT-6 in ChatGPT |
| Review date | 2026-11-11 |
| Subject | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, revision 9 |
| Subject SHA-256 | `4e131bfb0e28a61d8baa4244517e5f6e930ce1eeb8a6581293dd27e1dcc8ba18` |
| Status | Proposed, not accepted; no implementation authorised |
| Prior involvement | Author of IR-P-02 and IR-P-02-R through IR-P-02-R4; this is a focused re-review, not a blind review |

## 1. Integrity verification

I opened the uploaded ZIP, read the commission first, and independently recalculated every file hash listed in `MANIFEST.sha256`.

- Manifest entries: 21.
- Files verified: 21/21.
- Manifest mismatches: 0.
- Revision-9 subject SHA-256 recalculated: `4e131bfb0e28a61d8baa4244517e5f6e930ce1eeb8a6581293dd27e1dcc8ba18`.
- Expected and recalculated subject fingerprints match exactly.
- Revision-8 snapshot SHA-256 recalculated: `28cfecdfbf11874ae9af9e935d25a91cda962ea1d0b8708ac8de987611dc5006`, matching the commission.
- The revision-9 proof register is present and cites the exact revision-9 subject fingerprint.

Package integrity is verified. It does not establish architectural correctness or execution of proof obligations. No source file, decision, design or proof register was modified.

## 2. Question 1 — R9-1: AuthorityCheck mapping and M-1

### 2.1 Act kinds: `designate-platform-contacts` and `give-case-grant`

**Conclusion: neither act kind is established by the accepted Governance AuthorityCheck contract. They are extensions, not mere clarifications.**

Evidence:

- File 16, Governance thin slice §6.2, lines 119–134, enumerates the AuthorityCheck query and its supported act kinds.
- Subsequent accepted additions are explicit: K-1 / D-154 adds `decide-structural-change` and `place-member` (file 16, lines 192–196); D-163 adds `renew-membership`, `suspend-membership` and `restore-membership` (line 223).
- Correction note 4 adds `requestRef` for subject-specific acts (line 229); it does not authorise arbitrary new act kinds.
- Neither `designate-platform-contacts` nor `give-case-grant` appears in the accepted thin slice.
- R9-1 itself acknowledges that `designate-platform-contacts` is new and says its addition note is offered for a later acceptance act (subject, lines 1079–1080). The note is not present in file 16.
- No accepted source defines `give-case-grant` as an AuthorityCheck act kind.

D-174 permits designated platform contacts to give case grants through Platform while the organisation database cannot record the grant. D-174 also says that, when the database works, the normal AuthorityCheck route applies. That does not itself define a `give-case-grant` act kind or its competence semantics.

Therefore, the existing AuthorityCheck mechanism is reusable, but the two act kinds and their authority semantics are not already accepted.

**Classification: [c] Principal decision / Governance contract extension.**

Required change:

1. Define `designate-platform-contacts`, with Platform as consumer, the designation as subject, the decision part and designated person as binding identifiers, and the applicable competence/evidence requirements.
2. Define whether and how `give-case-grant` is checked through Governance on the normal route, including its actor, case subject, scope, business date and competence requirements.
3. Define the relationship between the normal Governance route and the outage route under D-174, without making Platform the source of constitutional authority.
4. Add the act-kind definitions to the accepted Governance thin slice and obtain the required Governance ratification.
5. Obtain the Principal's authorisation for the contract extension to Platform. Do not defer this silently to Platform acceptance.

**One precise Principal question:** Does the Principal authorise extending the Governance AuthorityCheck contract to Platform with `designate-platform-contacts` and `give-case-grant`, subject to their explicit definition and Governance ratification?

### 2.2 `evaluatedFor`

R9-1 corrects R8-1. The accepted contract defines `evaluatedFor` as the business date, not the designated person's identity (file 16, correction note 2, lines 200–207; file 18, §3, lines 66–75). D-12 fixes competence evaluation to the decision date.

For the designation check, using the decision date is correct.

**Classification: [d] resolved.**

This conclusion concerns the designation evaluation. R9-1 does not define the `give-case-grant` act's own evaluation date; that must be part of the new act-kind contract.

### 2.3 Subject identifier

The proposed subject `(decisionRef, Resolution part, designated PersonRef)` distinguishes the specific designation. The accepted correction note 4 says `requestRef` carries subject and request identifiers (file 16, line 229). The accepted thin slice also establishes Resolution parts and competence evaluation per part (file 16, §6.1, lines 114–116).

The proposed mapping is conceptually consistent with that contract. However, the accepted contract does not prescribe the exact composite identifier representation or encoding.

**Classification: [b] proof obligation, with [TU] on the exact representation.**

The implementation must prove that decision, part and person are all bound and that no ambiguous or cross-person match is possible. It must not create a second AuthorityCheck schema.

### 2.4 Request identifier and replay protection

A unique reconciliation request identifier, consumed once, is consistent with correction note 4. Matching the answer to the exact request is the right replay defence.

**Classification: [d] design direction; [b] proof obligation.**

The proof must demonstrate atomic request consumption and promotion under concurrent reconciliation, including duplicate delivery and retry.

### 2.5 PersonRef → Account

M-2 remains unresolved.

- XD-3 is ratified (file 20, §2, lines 18–25). Identity alone may resolve an organisation's `PersonRef` to the canonical Person; Person and Account are separate; there is no automatic account creation, takeover or linking by email/name matching.
- D-113 and XD-3 establish the identity boundaries, not the exact PersonRef-to-Account resolution operation required by R9-1.
- The detailed `ReferenceIssuer` / PersonRef-to-Account mechanism appears in proposed I-TDD-01 (file 19).
- I-TDD-02 is only a scope document (file 21).

**Classification: [UNKNOWN].**

The design must fail closed until an accepted Identity contract specifies the operation. Email matching and mere Account–organisation association are not valid substitutes.

### 2.6 `ruleRef` and `policyVersion`

R9-1 adds the equality check against the citation and rule-document version associated with the exact Resolution part's competence evaluation (subject, lines 1071–1077). This addresses the omission identified in R4 A-1.

The accepted AuthorityCheck answer shape includes `ruleRef` and `policyVersion` (file 16, correction note A-2, line 198; file 18, §2–3, lines 33–56). The accepted thin slice states that Resolution has parts and competence evaluation per part and is immutable (file 16, §6.1, lines 114–116).

However, the accepted text supplied does not explicitly establish that the persisted competence-evaluation data for each Resolution part contains and fixes both `ruleRef` and `policyVersion`. The more detailed Part and competence-evaluation model is in file 17, which is proposed, not ratified.

**Classification: [UNKNOWN]** for the complete per-part persistence contract; **[b]** for the equality/replay proof.

### 2.7 Other acceptance checks

| Requirement | Assessment |
|---|---|
| No reuse across person, decision, part or request | [b] |
| Fail closed on mismatch | [d] design rule |
| Fail closed on missing evidence | [d] design rule |
| Reject replay | [b] |
| Reject `NotAuthorised` | [d] |
| Reject `Undetermined` | [d] |
| No second schema | [d] design constraint |
| No new Governance fields | [d], subject to the act-kind contract extension |

**Question 1 verdict:** R9-1 materially improves the mapping but does not close M-1, M-2, M-3 or M-4. The new act kinds are an extension requiring explicit authorisation and Governance ratification.

## 3. Question 2 — R9-2: Handover version and serialisation

R8-2 specifies one central transaction: lock `ServedOrganisation`, restore routing, lock each relevant grant, set `handoverState = handover`, insert the outbox record and commit (subject, lines 952–969). Admission takes a shared lock on the grant and checks the handover state. This is a coherent per-grant serialisation design and is consistent with D-187.

R9-2 fixes the initial version omission: the handover transition increments `PendingCaseGrant.version`, the outbox event carries the new version, an absent copy is version zero, and later outbox events carry the version of the state change (subject, lines 1082–1086).

The lifecycle is coherent at the central transaction boundary:

1. The central handover transition changes state and increments version.
2. The outbox record is written in the same central transaction.
3. Admissions after the handover transition see `handover` and are refused until a present, active organisation copy is checked.
4. Delivery is intended to apply only higher versions.
5. Terminal states are intended never to reopen.

### Finding R5-04 — [a] Atomic inbox update is unspecified

R8-3/R9-2 says the organisation inbox applies a copy only if its version is higher. It does not specify that the comparison and update are atomic.

Failure scenario:

- Existing copy is version 4.
- Concurrent delivery A (version 5) and delivery B (version 6) both read version 4.
- B commits version 6.
- A commits version 5 afterwards.

The copy can regress despite the higher-version-only rule.

- **Evidence:** subject R8-3, lines 971–980; R9-2, lines 1082–1086.
- **Decision/contract:** D-187 and the monotonic-copy rule.
- **Minimum correction:** define an atomic compare-and-apply transaction (for example, row locking or an atomic conditional update) and enforce terminal-state protection in that same transaction.

### Lifecycle assessment

| Requirement | Assessment |
|---|---|
| Handover state/version increment atomic | [d] specified by R8-2 + R9-2 |
| Outbox in same transaction | [d] specified |
| Admission serialised against handover | [d] specified |
| Admission during handover refused | [d] specified |
| Missing copy refused | [d] specified |
| Duplicate delivery | [b] |
| Delayed delivery | [b] |
| Reordered delivery | [a] atomic inbox application missing |
| Retried delivery | [b] |
| Terminal states never reactivate | [b] |
| Withdrawal during handover | [b] |
| No race among grant, copy and outbox | [b] |

**Question 2 verdict:** Central handover is substantially coherent. Organisation-side inbox application still has a design defect: version comparison and update must be atomic. The relevant tests remain unexecuted.

## 4. Question 3 — R9-3: Operation identity and start claim

### 4.1 Global uniqueness and immutable binding

R9-3 replaces the earlier `(grant_id, operation_id)` uniqueness key with a central `OperationRegistry` unique on `operation_id` (subject, lines 1088–1100). The immutable binding includes grant, operator, operation kind, scope digest and operator request ID. Identical binding returns the existing operation; any difference is refused.

The organisation admission row is also unique on `operation_id` and carries the grant ID.

**Classification: [d] resolved at design level.**

This corrects R4 D-1. The central registry supplies global uniqueness across organisation databases, while the organisation admission record remains local.

### 4.2 `started` claim and execution lifecycle

The conditional update `admitted → started` only where state is `admitted` is the correct intended one-executor claim. A concurrent retry finding `started` must not execute.

R8-5's lifecycle remains correct in principle:

- Before admission commit: no admission; refused.
- After admission commit but before `started`: same-ID retry may proceed after a fresh grant check and within the original deadline.
- After `started` without outcome: never re-execute; report “access may have occurred”.
- No exactly-once execution claim.

**Classification: [d] design rule; [b] execution proof.**

The proof must demonstrate concurrent single-winner behaviour, durable `started` state, restart behaviour, and no second execution after a lost acknowledgement or missing outcome.

### Finding R5-08 — [a] Registry-only failure lifecycle is unspecified

R9-3 writes the central registry in the gateway's first committed step, before the organisation-side admission. It says an identical retry returns the existing operation and does not create a second admission.

The design does not specify what happens if the central registry commit succeeds but the organisation admission does not commit. It does not say whether a same-binding retry resumes the uncommitted admission attempt or whether the registry entry becomes terminally refused.

- **Evidence:** subject R9-3, lines 1090–1100; R8-5, lines 996–1001.
- **Failure scenario:** gateway crashes after registry commit but before organisation admission; retry finds the registry row.
- **Consequence:** an operation may be stranded or its retry semantics may be interpreted inconsistently.
- **Minimum correction:** define the registry-to-admission state transition and idempotent recovery rule. The retry must not create a second execution or silently create a second admission.

### 4.3 Central / organisation database boundary

The registry stores platform-operation metadata, not organisation content. The scope is represented by a digest rather than the underlying data. This is consistent with D-164, D-173, D-178 and INV-ACCESS-1.

**Classification: [d] resolved at design level; [b] proof obligation.**

**Question 3 verdict:** Global operation identity is a sound correction. The no-exactly-once position is correct. The registry-only failure path must be specified, and the concurrency/restart proofs remain unexecuted.

## 5. Question 4 — R9-4: D-189 deadline

### 5.1 Correct elements

R9-4 defines:

`D = min(grant expiry, t_admit + limit)`

The limit is 15 minutes by D-189 or a shorter grant-specific limit. It fixes the deadline at admission and stores it with both admission records. The central database clock is read after the grant row's shared lock. Execution environments receive a remaining duration rather than comparing absolute timestamps across hosts.

The design also specifies:

- statement, transaction and lock timeouts for live database work;
- gateway cancellation and backend termination at timer expiry;
- an isolated backup-copy worker with the gateway as its only output channel;
- worker termination and partial-output discard;
- destruction of the isolated copy and reporting if backup-worker termination cannot be confirmed;
- no result relayed after the gateway timer expires;
- refusal if central time or remaining duration cannot be established.

These are appropriate design directions and preserve D-189.

### Finding R5-05 — [a] Timer-start gap

R9-4 calculates remaining duration from `D − t_admit`, then starts the gateway's monotonic timer. It later subtracts monotonic elapsed time from that duration (subject, lines 1104–1110).

The design does not account for elapsed time between the central database's `t_admit` reading and the gateway's monotonic timer start.

Failure scenario: the central admission response is delayed, or the gateway is descheduled before starting its timer. Those seconds are not subtracted, so effective work can run beyond the authoritative deadline.

- **Decision:** D-189.
- **Minimum correction:** define a mechanism that accounts for the complete interval between central time acquisition and gateway enforcement. The monotonic measurement must encompass the database round trip/admission timing or otherwise conservatively account for it. A timer started only after the central response is insufficient.

### Finding R5-06 — [a] Live termination fallback incomplete

R9-4 specifies cancellation and backend termination for live-database operations but does not specify the containment path if termination cannot be confirmed. The backup-copy path has an explicit destruction-and-report fallback; the live-database path does not.

Failure scenario: the gateway timer expires, cancellation is issued, but the database backend continues work or termination cannot be confirmed.

- **Evidence:** subject R9-4, lines 1111–1119.
- **Minimum correction:** define positive termination confirmation, session containment, a fail-closed response when confirmation is unavailable, and an auditable incident report. A timeout instruction is not proof that work stopped.

### Deadline assessment

| Requirement | Assessment |
|---|---|
| Central clock at admission | [d] |
| `min(expiry, limit)` | [d] |
| Remaining-duration propagation | [a] timing gap |
| Restart cannot extend deadline | [b] |
| Retry cannot extend deadline | [b] |
| Queue delay cannot extend deadline | [b] |
| Clock differences cannot extend deadline | [d] design approach; [b] proof |
| Work actually stops | [a] live termination fallback incomplete |
| No result after deadline | [b] |
| Backup-copy work bounded by deadline | [b] |
| Unconfirmed backup termination → destruction and report | [d] design rule; [b] proof |
| Unconfirmed live termination | [UNKNOWN] |
| Refusal when time cannot be established | [d] |

D-4 should additionally test timer-start delay, gateway failover/restart, delayed dispatch, live backend termination failure, inability to confirm termination, and backup-copy destruction evidence.

**Question 4 verdict:** R9-4 does not yet establish the D-189 guarantee. The timer-start gap and live termination fallback must be corrected before acceptance.

## 6. Question 5 — R9-5: D-188 reporting

R9-5 now names the failed designation (decision, part, person and outcome), every grant suspended because of it, and every operation under those grants, including “access may have occurred” cases (subject, lines 1122–1129). This corrects R4 F-1's omission of the failed designation.

**Classification: [d] earlier omission corrected.**

### Finding R5-07 — [a] Report content is not fully specified

The commission requires each operation to be reported with its status and uncertainty and the audit evidence. R9-5 names operations but does not define the per-operation report fields.

At minimum, the report must include:

- operation identifier;
- operation status and outcome;
- whether access completed;
- whether access may have occurred;
- termination status where applicable;
- reference to the corresponding audit evidence.

The report must be complete and traceable to the designation, suspended grants and operation records.

**Minimum correction:** define the per-operation report record and its audit-evidence reference. This implements D-188; it does not reopen D-188.

### Delivery and durability

The organisation inbox, idempotency on event ID and D-179 notices to current contacts are appropriate design choices. The substantive report should remain in the organisation inbox; D-179 notices must remain within their permitted account-security-notification purpose.

**Classification: [b] proof obligation.**

Prove durable report creation, retry after failure, duplicate delivery, completeness after interrupted reconciliation, and traceability to the original evidence.

**Question 5 verdict:** The designation-reporting omission is corrected. Report content and durable delivery remain incomplete.

## 7. Question 6 — R9-6: Proof register

### 7.1 Integrity and coverage

The revision-9 register (file 03) names the exact subject fingerprint. It supersedes the revision-7-named historical register and preserves the historical record.

| Section | Count | Status |
|---|---:|---|
| A — concurrency matrix | 14 | NOT EXECUTED |
| B — other design obligations | 10 | NOT EXECUTED |
| C — revision-8 additions | 9 | NOT EXECUTED |
| D — revision-9 additions | 6 | NOT EXECUTED |
| **Total** | **39** | **NOT EXECUTED** |

A and B are carried forward; C remains; D is added. No obligation is represented as executed.

**Classification: [d] register identity corrected; [b] all proof obligations outstanding.**

### 7.2 Coverage gaps

The six D-series tests cover the principal revision-9 changes but do not explicitly cover all the defects found in this review.

Required additions:

1. **R9-2 inbox concurrency:** concurrent version-5 and version-6 deliveries; prove version 5 cannot overwrite version 6.
2. **R9-3 registry-only failure:** crash after central registry commit but before organisation admission; prove deterministic retry/refusal semantics.
3. **R9-3 restart:** registry and `started` state survive restart without a second execution.
4. **R9-4 timer-start gap:** delay between central time acquisition and gateway timer start.
5. **R9-4 live termination:** termination cannot be confirmed; prove containment and reporting.
6. **R9-5 reporting:** status, uncertainty and audit-evidence completeness.
7. **Identity integration:** prove PersonRef-to-Account resolution through the accepted Identity-owned mechanism, with no email matching or unauthorised association inference.

**Classification: [a]** where the missing proof corresponds to an incomplete design rule; **[b]** for the additional execution tests.

**Question 6 verdict:** R9-6 correctly fixes the register's identity. It is a sound carried-forward baseline, but needs the above additions. All 39 existing obligations remain unexecuted build gates.

## 8. Question 7 — Additional duties

### 8.1 Re-check of every R4 finding

| R4 finding | Revision-9 result |
|---|---|
| A-1 — AuthorityCheck subject/request mapping and rule checks | Partially corrected; M-1, M-2 and M-4 remain |
| A-2 — per-designation proof | C-3 and D-1 retained; unexecuted |
| A-3 — Governance owns authority | Preserved |
| B-1 — recovery/admission concurrency | C-1 and C-7 retained; unexecuted |
| B-2 — D-187 handover pause | Preserved |
| C-1 — initial handover version | Corrected by R9-2; inbox concurrency defect remains |
| C-2 — delayed/reordered delivery | C-2 and C-9 retained; insufficient for concurrent inbox updates |
| C-3 — organisation record ownership | Preserved |
| D-1 — global operation identity | Corrected by R9-3 |
| D-2 — started claim | Corrected at design level; proof outstanding |
| E-1 — deadline clock | Improved; timer-start and live termination defects remain |
| E-2 — deadline proof | C-6 and D-4 retained; unexecuted |
| E-3 — D-189 policy | Preserved |
| F-1 — failed designation reporting | Designation omission corrected; report completeness remains |
| F-2 — D-188 / D-186 distinction | Preserved |
| G-1 — register identity | Corrected |
| G-2 — proof execution | All 39 remain unexecuted |
| G-3 — INV-ACCESS-1 | Preserved |

No accepted decision D-164 through D-189 is reopened.

### 8.2 Ownership and authority

The accepted ownership allocation remains:

- Governance owns organisational authority and its decisions.
- Organisation owns its organisational records and its decisions to grant.
- Platform owns the organisation registry, locator, platform status, service-operation grant record and pending case-grant records.
- Membership owns membership facts.
- Finance owns Finance facts.
- Identity owns Person, Account and identity bindings.

R9 does not formally transfer these business ownership responsibilities to Platform.

Platform must not treat its contact registry as an independent source of constitutional authority. The designation must be established by the organisation's competent decision and validated through Governance on recovery.

**Classification: [d]** for accepted ownership allocation; **[c]** for the proposed AuthorityCheck extension.

### 8.3 Central metadata minimisation

The central `OperationRegistry` stores platform-operation metadata rather than organisation content. The scope is represented by a digest.

The contact model also stores designation decision references and dates. D-174 says Platform holds only account/PersonRef references centrally. D-184 later requires outage-period precedence by the dates of the organisation decisions cited by registrations.

The precise permitted central representation needs explicit reconciliation. This report does not reopen either decision.

**Classification: [UNKNOWN].**

### 8.4 No implied content access

The following boundaries remain essential and are consistent with the accepted baseline:

- Service-operation grants do not permit human reading of organisation content.
- A platform contact is not automatically a member or organisational office-holder.
- Account association does not establish organisational authority.
- A backup copy does not itself grant content-access permission.
- Recovery does not authorise unrestricted reading.
- The Platform operation registry does not become an organisation business record.

**Classification: [d]** for accepted ownership principles; **[b]** for isolation and access-control proofs.

### 8.5 M-1 to M-4 dependency assessment

| Dependency | Finding | Classification |
|---|---|---|
| M-1 — `designate-platform-contacts` and `give-case-grant` | Not defined by the accepted AuthorityCheck act-kind list | [c] |
| M-2 — PersonRef to bound Account | Ratified identity principles exist; exact resolution operation is not accepted | [UNKNOWN] |
| M-3 — GD-1, GD-11, GD-12 | Accepted thin slice expressly states Resolution immutability and references DecisionLog rules; detailed GD-11/GD-12 semantics remain in the proposed model | [UNKNOWN] for detailed rules; [d] for basic Resolution immutability |
| M-4 — `ruleRef` / `policyVersion` per Resolution part | Plausible interpretation, but accepted text does not explicitly establish their fixed persistence in each part's competence evaluation | [UNKNOWN] |

None of these dependencies may be silently treated as resolved.

## 9. Consolidated findings register

| ID | Finding | Classification | Priority |
|---|---|---|---|
| R5-01 | Governance does not yet support the two proposed act kinds | [c] | Critical |
| R5-02 | PersonRef-to-Account resolution contract not established | [UNKNOWN] | Critical |
| R5-03 | Detailed GD-11/GD-12 semantics and M-4 persistence assumption unresolved | [UNKNOWN] | High |
| R5-04 | Organisation inbox lacks atomic version compare-and-apply | [a] | Critical |
| R5-05 | Deadline timer can omit elapsed time before timer start | [a] | Critical |
| R5-06 | Live-database termination failure has no explicit containment fallback | [a] | Critical |
| R5-07 | D-188 operation report lacks defined status and audit-evidence fields | [a] | High |
| R5-08 | Registry-only crash/retry lifecycle before organisation admission is unspecified | [a] | High |
| R5-09 | Additional execution and concurrency proofs required | [b] | High |
| R5-10 | Exact central contact metadata allowance needs reconciliation | [UNKNOWN] | Medium |
| R5-11 | Composite subject identifier and Identity integration need proof | [TU] | High |
| R5-12 | Actual backend termination and isolation guarantees require technical verification | [TU] | Critical |
| R5-13 | Earlier R4 findings and obligations | [d] / [b] | Tracked |

## 10. Overall architectural assessment

Revision 9 makes meaningful progress:

1. Global operation identity is better defined.
2. The `started` claim is more precise.
3. Handover has an explicit version transition.
4. Deadline enforcement has a more sophisticated clock model.
5. D-188 reporting explicitly identifies the failed designation.
6. The proof register has been correctly re-stamped.

Nevertheless, the remaining defects are not cosmetic.

Three independent conditions must be satisfied:

- **Authority:** Governance establishes that the person is entitled to designate contacts or approve a case grant.
- **Identity:** Identity establishes that the designated PersonRef corresponds to the correct Account.
- **Execution:** Platform proves that access stops within the authorised time and that uncertainty is reported accurately.

Revision 9 has improved the execution design but has not fully established all three contracts.

### Business explanation for the Principal

The Platform can maintain the list of people permitted to act during an outage. But:

- Governance must establish that the original designation was legitimate.
- Identity must establish that the designated person corresponds to the correct login account.
- Platform must prove that support access ends when permission expires or is withdrawn.
- If a designation is invalid, the organisation must receive a complete and trustworthy report of what happened.

Until these conditions are specified, the design should not be accepted.

## 11. Verdict

# NOT READY

Revision 9 is **not ready for Principal acceptance**.

The blocking matters are:

1. The Governance AuthorityCheck extension has not been authorised and ratified.
2. The Identity PersonRef-to-Account resolution contract is not accepted.
3. The organisation inbox lacks an atomic version-application rule.
4. The D-189 deadline mechanism has a timer-start gap.
5. Live-database termination failure lacks an explicit containment fallback.
6. The D-188 report does not specify complete per-operation status, uncertainty and audit evidence.
7. The registry-only failure lifecycle is incomplete.
8. GD-11/GD-12 and M-4 remain unresolved dependencies.

The 39 proof obligations remain unexecuted. Additional tests are required. Design acceptance must not be confused with build readiness.

No implementation is authorised by this report. No accepted decision is changed. No accepted decision D-164 through D-189 is reopened.

## 12. Reviewer identity and prior involvement

**Reviewer:** GPT-6 in ChatGPT.

**Prior involvement:** Author of IR-P-02, IR-P-02-R, IR-P-02-R2, IR-P-02-R3 and IR-P-02-R4. This is a focused independent re-review of revision 9, not a blind review. The revision-9 author is Claude.

**Review completed:** 2026-11-11.

**End of report.**
