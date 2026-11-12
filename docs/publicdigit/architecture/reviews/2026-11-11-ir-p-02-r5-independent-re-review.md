<!-- Recorder note (Claude, 2026-11-11):
- What follows: GPT-6's IR-P-02-R5 formal report (ChatGPT), as pasted by the Principal; extracted verbatim from the session transcript; body unaltered (plain-text table layout as pasted).
- The Principal also pasted GPT-6's chat summary of the same review; it is appended below the report for provenance. GPT-6's own saved file (~30 KB) was not received. -->

IR-P-02-R5 — Focused Independent Re-Review: Platform Revision 9
    
Reviewer    GPT-6 in ChatGPT
Review date    2026-11-11
Subject    docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md, revision 9
Subject SHA-256    4e131bfb0e28a61d8baa4244517e5f6e930ce1eeb8a6581293dd27e1dcc8ba18
Status    Proposed, not accepted; no implementation authorised
Prior involvement    Author of IR-P-02 and IR-P-02-R through IR-P-02-R4; this is a focused re-review, not a blind review


1. Integrity verification
I opened the uploaded ZIP, read the commission first, and independently recalculated every file hash listed in MANIFEST.sha256.
- Manifest entries: 21.
- Files verified: 21/21.
- Manifest mismatches: 0.
- Revision-9 subject SHA-256 recalculated: 4e131bfb0e28a61d8baa4244517e5f6e930ce1eeb8a6581293dd27e1dcc8ba18.
- Expected and recalculated subject fingerprints match exactly.
- Revision-8 snapshot SHA-256 recalculated: 28cfecdfbf11874ae9af9e935d25a91cda962ea1d0b8708ac8de987611dc5006, matching the commission.
- The revision-9 proof register is present and cites the exact revision-9 subject fingerprint.
Package integrity is verified. It does not establish architectural correctness or execution of proof obligations. No source file, decision, design or proof register was modified.
2. Question 1 — R9-1: AuthorityCheck mapping and M-1
2.1 Act kinds: designate-platform-contacts and give-case-grant
Conclusion: neither act kind is established by the accepted Governance AuthorityCheck contract. They are extensions, not mere clarifications.

Evidence:
- File 16, Governance thin slice §6.2, lines 119–134, enumerates the AuthorityCheck query and its supported act kinds.
- Subsequent accepted additions are explicit: K-1 / D-154 adds decide-structural-change and place-member (file 16, lines 192–196); D-163 adds renew-membership, suspend-membership and restore-membership (line 223).
- Correction note 4 adds requestRef for subject-specific acts (line 229); it does not authorise arbitrary new act kinds.
- Neither designate-platform-contacts nor give-case-grant appears in the accepted thin slice.
- R9-1 itself acknowledges that designate-platform-contacts is new and says its addition note is offered for a later acceptance act (subject, lines 1079–1080). The note is not present in file 16.
- No accepted source defines give-case-grant as an AuthorityCheck act kind.
D-174 permits designated platform contacts to give case grants through Platform while the organisation database cannot record the grant. D-174 also says that, when the database works, the normal AuthorityCheck route applies. That does not itself define a give-case-grant act kind or its competence semantics.

Therefore, the existing AuthorityCheck mechanism is reusable, but the two act kinds and their authority semantics are not already accepted.

Classification: [c] Principal decision / Governance contract extension.

Required change:
1. Define designate-platform-contacts, with Platform as consumer, the designation as subject, the decision part and designated person as binding identifiers, and the applicable competence/evidence requirements.
2. Define whether and how give-case-grant is checked through Governance on the normal route, including its actor, case subject, scope, business date and competence requirements.
3. Define the relationship between the normal Governance route and the outage route under D-174, without making Platform the source of constitutional authority.
4. Add the act-kind definitions to the accepted Governance thin slice and obtain the required Governance ratification.
5. Obtain the Principal's authorisation for the contract extension to Platform. Do not defer this silently to Platform acceptance.
One precise Principal question: Does the Principal authorise extending the Governance AuthorityCheck contract to Platform with designate-platform-contacts and give-case-grant, subject to their explicit definition and Governance ratification?
2.2 evaluatedFor
R9-1 corrects R8-1. The accepted contract defines evaluatedFor as the business date, not the designated person's identity (file 16, correction note 2, lines 200–207; file 18, §3, lines 66–75). D-12 fixes competence evaluation to the decision date.

For the designation check, using the decision date is correct.

Classification: [d] resolved.

This conclusion concerns the designation evaluation. R9-1 does not define the give-case-grant act's own evaluation date; that must be part of the new act-kind contract.
2.3 Subject identifier
The proposed subject (decisionRef, Resolution part, designated PersonRef) distinguishes the specific designation. The accepted correction note 4 says requestRef carries subject and request identifiers (file 16, line 229). The accepted thin slice also establishes Resolution parts and competence evaluation per part (file 16, §6.1, lines 114–116).

The proposed mapping is conceptually consistent with that contract. However, the accepted contract does not prescribe the exact composite identifier representation or encoding.

Classification: [b] proof obligation, with [TU] on the exact representation.

The implementation must prove that decision, part and person are all bound and that no ambiguous or cross-person match is possible. It must not create a second AuthorityCheck schema.
2.4 Request identifier and replay protection
A unique reconciliation request identifier, consumed once, is consistent with correction note 4. Matching the answer to the exact request is the right replay defence.

Classification: [d] design direction; [b] proof obligation.

The proof must demonstrate atomic request consumption and promotion under concurrent reconciliation, including duplicate delivery and retry.
2.5 PersonRef → Account
M-2 remains unresolved.
- XD-3 is ratified (file 20, §2, lines 18–25). Identity alone may resolve an organisation's PersonRef to the canonical Person; Person and Account are separate; there is no automatic account creation, takeover or linking by email/name matching.
- D-113 and XD-3 establish the identity boundaries, not the exact PersonRef-to-Account resolution operation required by R9-1.
- The detailed ReferenceIssuer / PersonRef-to-Account mechanism appears in proposed I-TDD-01 (file 19).
- I-TDD-02 is only a scope document (file 21).
Classification: [UNKNOWN].

The design must fail closed until an accepted Identity contract specifies the operation. Email matching and mere Account–organisation association are not valid substitutes.
2.6 ruleRef and policyVersion
R9-1 adds the equality check against the citation and rule-document version associated with the exact Resolution part's competence evaluation (subject, lines 1071–1077). This addresses the omission identified in R4 A-1.

The accepted AuthorityCheck answer shape includes ruleRef and policyVersion (file 16, correction note A-2, line 198; file 18, §2–3, lines 33–56). The accepted thin slice states that Resolution has parts and competence evaluation per part and is immutable (file 16, §6.1, lines 114–116).

However, the accepted text supplied does not explicitly establish that the persisted competence-evaluation data for each Resolution part contains and fixes both ruleRef and policyVersion. The more detailed Part and competence-evaluation model is in file 17, which is proposed, not ratified.

Classification: [UNKNOWN] for the complete per-part persistence contract; [b] for the equality/replay proof.
2.7 Other acceptance checks
Requirement    Assessment
No reuse across person, decision, part or request    [b]
Fail closed on mismatch    [d] design rule
Fail closed on missing evidence    [d] design rule
Reject replay    [b]
Reject NotAuthorised    [d]
Reject Undetermined    [d]
No second schema    [d] design constraint
No new Governance fields    [d], subject to the act-kind contract extension


Question 1 verdict: R9-1 materially improves the mapping but does not close M-1, M-2, M-3 or M-4. The new act kinds are an extension requiring explicit authorisation and Governance ratification.
3. Question 2 — R9-2: Handover version and serialisation
R8-2 specifies one central transaction: lock ServedOrganisation, restore routing, lock each relevant grant, set handoverState = handover, insert the outbox record and commit (subject, lines 952–969). Admission takes a shared lock on the grant and checks the handover state. This is a coherent per-grant serialisation design and is consistent with D-187.

R9-2 fixes the initial version omission: the handover transition increments PendingCaseGrant.version, the outbox event carries the new version, an absent copy is version zero, and later outbox events carry the version of the state change (subject, lines 1082–1086).

The lifecycle is coherent at the central transaction boundary:
1. The central handover transition changes state and increments version.
2. The outbox record is written in the same central transaction.
3. Admissions after the handover transition see handover and are refused until a present, active organisation copy is checked.
4. Delivery is intended to apply only higher versions.
5. Terminal states are intended never to reopen.
Finding R5-04 — [a] Atomic inbox update is unspecified
R8-3/R9-2 says the organisation inbox applies a copy only if its version is higher. It does not specify that the comparison and update are atomic.

Failure scenario:
- Existing copy is version 4.
- Concurrent delivery A (version 5) and delivery B (version 6) both read version 4.
- B commits version 6.
- A commits version 5 afterwards.
The copy can regress despite the higher-version-only rule.
- Evidence: subject R8-3, lines 971–980; R9-2, lines 1082–1086.
- Decision/contract: D-187 and the monotonic-copy rule.
- Minimum correction: define an atomic compare-and-apply transaction (for example, row locking or an atomic conditional update) and enforce terminal-state protection in that same transaction.
Lifecycle assessment
Requirement    Assessment
Handover state/version increment atomic    [d] specified by R8-2 + R9-2
Outbox in same transaction    [d] specified
Admission serialised against handover    [d] specified
Admission during handover refused    [d] specified
Missing copy refused    [d] specified
Duplicate delivery    [b]
Delayed delivery    [b]
Reordered delivery    [a] atomic inbox application missing
Retried delivery    [b]
Terminal states never reactivate    [b]
Withdrawal during handover    [b]
No race among grant, copy and outbox    [b]


Question 2 verdict: Central handover is substantially coherent. Organisation-side inbox application still has a design defect: version comparison and update must be atomic. The relevant tests remain unexecuted.
4. Question 3 — R9-3: Operation identity and start claim
4.1 Global uniqueness and immutable binding
R9-3 replaces the earlier (grant_id, operation_id) uniqueness key with a central OperationRegistry unique on operation_id (subject, lines 1088–1100). The immutable binding includes grant, operator, operation kind, scope digest and operator request ID. Identical binding returns the existing operation; any difference is refused.

The organisation admission row is also unique on operation_id and carries the grant ID.

Classification: [d] resolved at design level.

This corrects R4 D-1. The central registry supplies global uniqueness across organisation databases, while the organisation admission record remains local.
4.2 started claim and execution lifecycle
The conditional update admitted → started only where state is admitted is the correct intended one-executor claim. A concurrent retry finding started must not execute.

R8-5's lifecycle remains correct in principle:
- Before admission commit: no admission; refused.
- After admission commit but before started: same-ID retry may proceed after a fresh grant check and within the original deadline.
- After started without outcome: never re-execute; report “access may have occurred”.
- No exactly-once execution claim.
Classification: [d] design rule; [b] execution proof.

The proof must demonstrate concurrent single-winner behaviour, durable started state, restart behaviour, and no second execution after a lost acknowledgement or missing outcome.
Finding R5-08 — [a] Registry-only failure lifecycle is unspecified
R9-3 writes the central registry in the gateway's first committed step, before the organisation-side admission. It says an identical retry returns the existing operation and does not create a second admission.

The design does not specify what happens if the central registry commit succeeds but the organisation admission does not commit. It does not say whether a same-binding retry resumes the uncommitted admission attempt or whether the registry entry becomes terminally refused.
- Evidence: subject R9-3, lines 1090–1100; R8-5, lines 996–1001.
- Failure scenario: gateway crashes after registry commit but before organisation admission; retry finds the registry row.
- Consequence: an operation may be stranded or its retry semantics may be interpreted inconsistently.
- Minimum correction: define the registry-to-admission state transition and idempotent recovery rule. The retry must not create a second execution or silently create a second admission.
4.3 Central / organisation database boundary
The registry stores platform-operation metadata, not organisation content. The scope is represented by a digest rather than the underlying data. This is consistent with D-164, D-173, D-178 and INV-ACCESS-1.

Classification: [d] resolved at design level; [b] proof obligation.

Question 3 verdict: Global operation identity is a sound correction. The no-exactly-once position is correct. The registry-only failure path must be specified, and the concurrency/restart proofs remain unexecuted.
5. Question 4 — R9-4: D-189 deadline
5.1 Correct elements
R9-4 defines:

D = min(grant expiry, t_admit + limit)

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
Finding R5-05 — [a] Timer-start gap
R9-4 calculates remaining duration from D − t_admit, then starts the gateway's monotonic timer. It later subtracts monotonic elapsed time from that duration (subject, lines 1104–1110).

The design does not account for elapsed time between the central database's t_admit reading and the gateway's monotonic timer start.

Failure scenario: the central admission response is delayed, or the gateway is descheduled before starting its timer. Those seconds are not subtracted, so effective work can run beyond the authoritative deadline.
- Decision: D-189.
- Minimum correction: define a mechanism that accounts for the complete interval between central time acquisition and gateway enforcement. The monotonic measurement must encompass the database round trip/admission timing or otherwise conservatively account for it. A timer started only after the central response is insufficient.
Finding R5-06 — [a] Live termination fallback incomplete
R9-4 specifies cancellation and backend termination for live-database operations but does not specify the containment path if termination cannot be confirmed. The backup-copy path has an explicit destruction-and-report fallback; the live-database path does not.

Failure scenario: the gateway timer expires, cancellation is issued, but the database backend continues work or termination cannot be confirmed.
- Evidence: subject R9-4, lines 1111–1119.
- Minimum correction: define positive termination confirmation, session containment, a fail-closed response when confirmation is unavailable, and an auditable incident report. A timeout instruction is not proof that work stopped.
Deadline assessment
Requirement    Assessment
Central clock at admission    [d]
min(expiry, limit)    [d]
Remaining-duration propagation    [a] timing gap
Restart cannot extend deadline    [b]
Retry cannot extend deadline    [b]
Queue delay cannot extend deadline    [b]
Clock differences cannot extend deadline    [d] design approach; [b] proof
Work actually stops    [a] live termination fallback incomplete
No result after deadline    [b]
Backup-copy work bounded by deadline    [b]
Unconfirmed backup termination → destruction and report    [d] design rule; [b] proof
Unconfirmed live termination    [UNKNOWN]
Refusal when time cannot be established    [d]


D-4 should additionally test timer-start delay, gateway failover/restart, delayed dispatch, live backend termination failure, inability to confirm termination, and backup-copy destruction evidence.

Question 4 verdict: R9-4 does not yet establish the D-189 guarantee. The timer-start gap and live termination fallback must be corrected before acceptance.
6. Question 5 — R9-5: D-188 reporting
R9-5 now names the failed designation (decision, part, person and outcome), every grant suspended because of it, and every operation under those grants, including “access may have occurred” cases (subject, lines 1122–1129). This corrects R4 F-1's omission of the failed designation.

Classification: [d] earlier omission corrected.
Finding R5-07 — [a] Report content is not fully specified
The commission requires each operation to be reported with its status and uncertainty and the audit evidence. R9-5 names operations but does not define the per-operation report fields.

At minimum, the report must include:
- operation identifier;
- operation status and outcome;
- whether access completed;
- whether access may have occurred;
- termination status where applicable;
- reference to the corresponding audit evidence.
The report must be complete and traceable to the designation, suspended grants and operation records.

Minimum correction: define the per-operation report record and its audit-evidence reference. This implements D-188; it does not reopen D-188.
Delivery and durability
The organisation inbox, idempotency on event ID and D-179 notices to current contacts are appropriate design choices. The substantive report should remain in the organisation inbox; D-179 notices must remain within their permitted account-security-notification purpose.

Classification: [b] proof obligation.

Prove durable report creation, retry after failure, duplicate delivery, completeness after interrupted reconciliation, and traceability to the original evidence.

Question 5 verdict: The designation-reporting omission is corrected. Report content and durable delivery remain incomplete.
7. Question 6 — R9-6: Proof register
7.1 Integrity and coverage
The revision-9 register (file 03) names the exact subject fingerprint. It supersedes the revision-7-named historical register and preserves the historical record.
Section    Count    Status
A — concurrency matrix    14    NOT EXECUTED
B — other design obligations    10    NOT EXECUTED
C — revision-8 additions    9    NOT EXECUTED
D — revision-9 additions    6    NOT EXECUTED
Total    39    NOT EXECUTED


A and B are carried forward; C remains; D is added. No obligation is represented as executed.

Classification: [d] register identity corrected; [b] all proof obligations outstanding.
7.2 Coverage gaps
The six D-series tests cover the principal revision-9 changes but do not explicitly cover all the defects found in this review.

Required additions:
1. R9-2 inbox concurrency: concurrent version-5 and version-6 deliveries; prove version 5 cannot overwrite version 6.
2. R9-3 registry-only failure: crash after central registry commit but before organisation admission; prove deterministic retry/refusal semantics.
3. R9-3 restart: registry and started state survive restart without a second execution.
4. R9-4 timer-start gap: delay between central time acquisition and gateway timer start.
5. R9-4 live termination: termination cannot be confirmed; prove containment and reporting.
6. R9-5 reporting: status, uncertainty and audit-evidence completeness.
7. Identity integration: prove PersonRef-to-Account resolution through the accepted Identity-owned mechanism, with no email matching or unauthorised association inference.
Classification: [a] where the missing proof corresponds to an incomplete design rule; [b] for the additional execution tests.

Question 6 verdict: R9-6 correctly fixes the register's identity. It is a sound carried-forward baseline, but needs the above additions. All 39 existing obligations remain unexecuted build gates.
8. Question 7 — Additional duties
8.1 Re-check of every R4 finding
R4 finding    Revision-9 result
A-1 — AuthorityCheck subject/request mapping and rule checks    Partially corrected; M-1, M-2 and M-4 remain
A-2 — per-designation proof    C-3 and D-1 retained; unexecuted
A-3 — Governance owns authority    Preserved
B-1 — recovery/admission concurrency    C-1 and C-7 retained; unexecuted
B-2 — D-187 handover pause    Preserved
C-1 — initial handover version    Corrected by R9-2; inbox concurrency defect remains
C-2 — delayed/reordered delivery    C-2 and C-9 retained; insufficient for concurrent inbox updates
C-3 — organisation record ownership    Preserved
D-1 — global operation identity    Corrected by R9-3
D-2 — started claim    Corrected at design level; proof outstanding
E-1 — deadline clock    Improved; timer-start and live termination defects remain
E-2 — deadline proof    C-6 and D-4 retained; unexecuted
E-3 — D-189 policy    Preserved
F-1 — failed designation reporting    Designation omission corrected; report completeness remains
F-2 — D-188 / D-186 distinction    Preserved
G-1 — register identity    Corrected
G-2 — proof execution    All 39 remain unexecuted
G-3 — INV-ACCESS-1    Preserved


No accepted decision D-164 through D-189 is reopened.
8.2 Ownership and authority
The accepted ownership allocation remains:
- Governance owns organisational authority and its decisions.
- Organisation owns its organisational records and its decisions to grant.
- Platform owns the organisation registry, locator, platform status, service-operation grant record and pending case-grant records.
- Membership owns membership facts.
- Finance owns Finance facts.
- Identity owns Person, Account and identity bindings.
R9 does not formally transfer these business ownership responsibilities to Platform.

Platform must not treat its contact registry as an independent source of constitutional authority. The designation must be established by the organisation's competent decision and validated through Governance on recovery.

Classification: [d] for accepted ownership allocation; [c] for the proposed AuthorityCheck extension.
8.3 Central metadata minimisation
The central OperationRegistry stores platform-operation metadata rather than organisation content. The scope is represented by a digest.

The contact model also stores designation decision references and dates. D-174 says Platform holds only account/PersonRef references centrally. D-184 later requires outage-period precedence by the dates of the organisation decisions cited by registrations.

The precise permitted central representation needs explicit reconciliation. This report does not reopen either decision.

Classification: [UNKNOWN].
8.4 No implied content access
The following boundaries remain essential and are consistent with the accepted baseline:
- Service-operation grants do not permit human reading of organisation content.
- A platform contact is not automatically a member or organisational office-holder.
- Account association does not establish organisational authority.
- A backup copy does not itself grant content-access permission.
- Recovery does not authorise unrestricted reading.
- The Platform operation registry does not become an organisation business record.
Classification: [d] for accepted ownership principles; [b] for isolation and access-control proofs.
8.5 M-1 to M-4 dependency assessment
Dependency    Finding    Classification
M-1 — designate-platform-contacts and give-case-grant    Not defined by the accepted AuthorityCheck act-kind list    [c]
M-2 — PersonRef to bound Account    Ratified identity principles exist; exact resolution operation is not accepted    [UNKNOWN]
M-3 — GD-1, GD-11, GD-12    Accepted thin slice expressly states Resolution immutability and references DecisionLog rules; detailed GD-11/GD-12 semantics remain in the proposed model    [UNKNOWN] for detailed rules; [d] for basic Resolution immutability
M-4 — ruleRef / policyVersion per Resolution part    Plausible interpretation, but accepted text does not explicitly establish their fixed persistence in each part's competence evaluation    [UNKNOWN]


None of these dependencies may be silently treated as resolved.
9. Consolidated findings register
ID    Finding    Classification    Priority
R5-01    Governance does not yet support the two proposed act kinds    [c]    Critical
R5-02    PersonRef-to-Account resolution contract not established    [UNKNOWN]    Critical
R5-03    Detailed GD-11/GD-12 semantics and M-4 persistence assumption unresolved    [UNKNOWN]    High
R5-04    Organisation inbox lacks atomic version compare-and-apply    [a]    Critical
R5-05    Deadline timer can omit elapsed time before timer start    [a]    Critical
R5-06    Live-database termination failure has no explicit containment fallback    [a]    Critical
R5-07    D-188 operation report lacks defined status and audit-evidence fields    [a]    High
R5-08    Registry-only crash/retry lifecycle before organisation admission is unspecified    [a]    High
R5-09    Additional execution and concurrency proofs required    [b]    High
R5-10    Exact central contact metadata allowance needs reconciliation    [UNKNOWN]    Medium
R5-11    Composite subject identifier and Identity integration need proof    [TU]    High
R5-12    Actual backend termination and isolation guarantees require technical verification    [TU]    Critical
R5-13    Earlier R4 findings and obligations    [d] / [b]    Tracked


10. Overall architectural assessment
Revision 9 makes meaningful progress:
1. Global operation identity is better defined.
2. The started claim is more precise.
3. Handover has an explicit version transition.
4. Deadline enforcement has a more sophisticated clock model.
5. D-188 reporting explicitly identifies the failed designation.
6. The proof register has been correctly re-stamped.
Nevertheless, the remaining defects are not cosmetic.

Three independent conditions must be satisfied:
- Authority: Governance establishes that the person is entitled to designate contacts or approve a case grant.
- Identity: Identity establishes that the designated PersonRef corresponds to the correct Account.
- Execution: Platform proves that access stops within the authorised time and that uncertainty is reported accurately.
Revision 9 has improved the execution design but has not fully established all three contracts.
Business explanation for the Principal
The Platform can maintain the list of people permitted to act during an outage. But:
- Governance must establish that the original designation was legitimate.
- Identity must establish that the designated person corresponds to the correct login account.
- Platform must prove that support access ends when permission expires or is withdrawn.
- If a designation is invalid, the organisation must receive a complete and trustworthy report of what happened.
Until these conditions are specified, the design should not be accepted.
11. Verdict
NOT READY
Revision 9 is not ready for Principal acceptance.

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
12. Reviewer identity and prior involvement
Reviewer: GPT-6 in ChatGPT.

Prior involvement: Author of IR-P-02, IR-P-02-R, IR-P-02-R2, IR-P-02-R3 and IR-P-02-R4. This is a focused independent re-review of revision 9, not a blind review. The revision-9 author is Claude.

Review completed: 2026-11-11.

End of report.

---

## Appendix (provenance): GPT-6's chat summary of the same review, as pasted

# IR-P-02-R5 — Focused Independent Re-Review: Platform Revision 9

Integrity verified

Design not accepted

Reviewer: GPT-6 (ChatGPT)\
Review date: 11 November 2026\
Subject: PublicDigit Platform (tenancy), revision 9\
Review basis: The complete 21-file handover package, including the commission, revision 8 snapshot, revision 9 design, previous independent reviews, accepted decision records and Governance/Identity materials.

## 1. Integrity verification

I independently opened the uploaded ZIP and recalculated the SHA-256 hashes of all 21 files listed in `MANIFEST.sha256`.

| Verification                   | Result                                                             |
| ------------------------------ | ------------------------------------------------------------------ |
| Files in manifest              | 21                                                                 |
| Files successfully verified    | 21/21                                                              |
| Manifest mismatches            | 0                                                                  |
| Subject revision               | 9                                                                  |
| Expected subject SHA-256       | `4e131bfb0e28a61d8baa4244517e5f6e930ce1eeb8a6581293dd27e1dcc8ba18` |
| Recalculated subject SHA-256   | Exact match                                                        |
| Revision 8 comparison snapshot | Included and available                                             |
| Proof register                 | Revision 9 register included                                       |

Subject fingerprint confirmed

The review subject is exactly the revision commissioned. I have not modified any source file, decision, design or proof register.

Important distinction: Package integrity is verified. This does not establish architectural correctness or successful execution of any proof obligation.

## 2. Question 1 — R9-1: AuthorityCheck mapping

Design acceptance blocked

This is the most consequential finding in this review.

### 2.1 Does the accepted Governance contract support `designate-platform-contacts`?

My conclusion: NO. It is an extension of the accepted AuthorityCheck contract, not merely a clarification.

The evidence is particularly clear:

- Governance thin slice §6.2 (file 16) enumerates the supported AuthorityCheck act kinds.
- Subsequent additions explicitly introduce further act kinds:
  - K-1 / D-154: `decide-structural-change`, `place-member`.
  - D-163: `renew-membership`, `suspend-membership`, `restore-membership`.
- Correction note 4 adds `requestRef` for subject-specific acts, but does not introduce arbitrary new act kinds.
- Neither `designate-platform-contacts` nor `give-case-grant` appears in the accepted Governance thin slice.

The fact that AuthorityCheck is an extensible contract does not mean every consumer can unilaterally introduce a new act kind.

The existing contract establishes the mechanism. It does not establish the authority semantics of these two new operations.

Classification: [c] Principal decision / Governance ratification required.

The minimum required action is:

1. Define the two act kinds in the Governance contract.
2. Specify their consumer, subject, scope, competence requirements and evidence.
3. Establish the corresponding answer semantics.
4. Obtain the required Governance ratification and Principal decision before treating them as accepted.

This cannot be deferred silently to the Platform acceptance act.

### 2.2 `evaluatedFor`

R9-1 correctly changes the earlier R8-1 mapping.

The accepted contract defines `evaluatedFor` as the business date (D-12), not the designated person's identity.

For a designation made by a historical decision, the decision date is therefore the appropriate evaluation date.

[d] Resolved

### 2.3 Subject identifier

The proposed tuple:

`(decisionRef, Resolution part, designated PersonRef)`

is conceptually appropriate for distinguishing individual designations.

The accepted Governance contract supports a subject-specific `requestRef`, and §6 establishes Resolution parts with competence evaluated per part.

However, the exact composite identifier representation is not prescribed by the accepted contract.

Classification: [b] Proof obligation, with a contract-definition dependency under M-1.

### 2.4 Request identifier and replay protection

The unique reconciliation request identifier, consumed once, is consistent with correction note 4.

The requirement that an answer must correspond to the particular request prevents cross-request replay.

However, the design still needs executable proof that consumption and promotion are atomic under concurrent reconciliation.

Classification: [b] Proof obligation.

### 2.5 PersonRef → Account

The design correctly rejects email matching and does not rely merely on an Account–organisation association.

However, M-2 is genuine.

File 19 is proposed, not accepted. File 21 is a scope document, not a completed Identity binding contract.

D-113 and the ratified XD-3 rules do not, by themselves, establish the complete resolution operation required by R9-1.

Classification: [UNKNOWN] for the actual resolution contract.

The Platform design must not claim this dependency is settled.

### 2.6 `ruleRef` and `policyVersion`

R9-1's intended protection is sound: the evidence must correspond to the competence evaluation of the exact Resolution part.

But the accepted thin slice's correction note A-2 establishes the answer shape, while the detailed immutability rules GD-1, GD-11 and GD-12 are defined in the proposed governance model (file 17).

Consequently, the fixed-data interpretation in R9-1 is not fully established by the supplied accepted sources.

Classification: [UNKNOWN] for the complete immutability contract; [b] for verification of the mapping and replay protection.

### 2.7 Other checks

| Requirement                                       | Assessment                                  |
| ------------------------------------------------- | ------------------------------------------- |
| No reuse across person, decision, part or request | [b]                                         |
| Fail closed on mismatch                           | [d] as a design rule                        |
| Fail closed on missing evidence                   | [d] as a design rule                        |
| Reject replay                                     | [b]                                         |
| Reject `NotAuthorised`                            | [d]                                         |
| Reject `Undetermined`                             | [d]                                         |
| No second schema                                  | [d]                                         |
| No new Governance fields                          | [d], subject to the new act-kind definition |

Question 1 verdict: R9-1 improves the mapping substantially, but it does not resolve the Governance contract dependency. The act-kind extension and Identity binding remain material acceptance blockers.

## 3. Question 2 — R9-2: Handover version and serialisation

Partially resolved

### 3.1 What is correct

R9-2 corrects an important weakness in revision 8.

The handover transition now increments `PendingCaseGrant.version`, and the outbox event carries the new version.

The combination of R8-2, R8-3 and R9-2 provides the following intended lifecycle:

1. Platform locks the organisation and grant.
2. It changes the grant to `handover`.
3. It increments the version.
4. It writes the corresponding outbox record.
5. Both changes commit in one central transaction.
6. Organisation-side delivery applies only a higher version.
7. Missing copies are treated as version zero.
8. Terminal states cannot be reopened by delayed older deliveries.

This is consistent with D-187 and the accepted multi-database separation.

### 3.2 Remaining defect: concurrent inbox delivery

R9-2 states that the organisation inbox applies a copy only if its version is higher.

However, it does not specify the transaction or locking mechanism that makes this comparison and update atomic.

Consider:

- Delivery A contains version 5.
- Delivery B contains version 6.
- Both read the existing version 4.
- B writes version 6.
- A subsequently writes version 5.

The organisation could end up with an older state despite the higher-version rule.

The comparison must be serialised, for example through a row lock or an atomic conditional update.

Classification: [a] Design defect.

- Location: R8-3, as refined by R9-2.
- Contract: D-187 and the monotonic-version requirement.
- Failure: an older concurrent delivery overwrites a newer state.
- Minimum correction: define an atomic compare-and-apply operation, including terminal-state protection, in the organisation inbox transaction.

### 3.3 Full lifecycle assessment

| Lifecycle requirement                       | Assessment                           |
| ------------------------------------------- | ------------------------------------ |
| Handover state and version increment atomic | [d] Design specified                 |
| Outbox in same transaction                  | [d] Design specified                 |
| Admission serialised against handover       | [d] Design specified                 |
| Admission during handover refused           | [d] Design specified                 |
| Missing copy refused                        | [d] Design specified                 |
| Duplicate delivery                          | [b]                                  |
| Delayed delivery                            | [b]                                  |
| Reordered delivery                          | [a] Atomic inbox application missing |
| Retried delivery                            | [b]                                  |
| Terminal states never reactivate            | [b]                                  |
| Withdrawal during handover                  | [b]                                  |
| No new race between grant, copy and outbox  | [b]                                  |

The proof register retains the earlier concurrency tests, but it does not explicitly add the concurrent inbox-update race described above.

Question 2 verdict: The central handover design is substantially improved. The organisation-side version application still requires a precise atomicity rule and corresponding proof.

## 4. Question 3 — R9-3: Operation identity and start claim

Design substantially coherent

### 4.1 Global uniqueness

R9-3 replaces the earlier `(grant_id, operation_id)` uniqueness boundary with a central `OperationRegistry` having a unique `operation_id`.

This is a material improvement.

The registry binds:

- grant ID;
- operator;
- operation kind;
- scope digest;
- operator request ID.

An identical retry retrieves the existing operation. A different binding is refused.

The organisation-side admission row is also unique on `operation_id`.

Classification: [d] Resolved at design level.

### 4.2 Started claim

The conditional state transition:

`admitted → started`

only where the current state is `admitted` is the correct intended concurrency mechanism.

One executor can win the transition. A concurrent retry must not execute after the state has become `started`.

This preserves the earlier R8-5 rule:

- failure before admission commit: refused;
- failure after admission commit but before `started`: same-ID retry may proceed under the original deadline and fresh checks;
- failure after `started`: no second execution;
- missing outcome: reconciliation reports that access may have occurred.

The design correctly makes no exactly-once execution claim.

Classification: [d] Design rule; [b] execution proof.

### 4.3 Central / organisation database boundary

The central registry contains platform-operation metadata, not organisation content.

The scope is represented by a digest, not by the organisation's actual data.

The organisation-side admission record holds the grant and operation identifiers.

This is consistent with D-164, D-173, D-178 and INV-ACCESS-1.

Platform does not acquire ownership of organisation records or business facts through the registry.

Classification: [d] Resolved at design level.

### 4.4 Remaining proof requirements

The proof must establish:

1. Two concurrent executors cannot both claim `started`.
2. A committed `started` claim survives process restart.
3. Reusing an ID with a different binding is refused.
4. A lost acknowledgement does not cause another execution.
5. An operation interrupted after `started` remains uncertain rather than being reported as unsuccessful without evidence.
6. Registry uniqueness holds across concurrent gateway instances and restarts.

The existing C-4, C-5, D-2 and D-3 obligations provide a reasonable basis for these tests.

Question 3 verdict: R9-3 is a sound design correction. It does not establish exactly-once execution, nor should it claim to. The proof register correctly leaves execution unverified.

## 5. Question 4 — R9-4: D-189 deadline

Design defect identified

This is the second major blocker.

### 5.1 What is correct

R9-4 establishes a sensible time model:

\\[ D=\min(\text{grant expiry},\ t\_{\text{admit}}+\text{limit}) \\]

It correctly specifies:

- central database time as the authoritative admission clock;
- a maximum of 15 minutes, or a shorter grant-specific limit;
- a stored deadline;
- remaining-duration enforcement rather than absolute-time comparison across hosts;
- dispatch refusal when remaining duration cannot be established;
- statement, transaction and lock timeouts for live database access;
- isolated backup-copy workers;
- no result relayed after the deadline.

This is consistent with D-189.

### 5.2 Defect: the monotonic timer does not necessarily start at admission

R9-4 says:

1. Central database establishes `t_admit`.
2. Remaining duration is calculated.
3. The gateway starts its monotonic timer.
4. The gateway subtracts monotonic elapsed time from the remaining duration.

There is a timing gap between steps 1 and 3.

For example:

- Central database establishes the admission deadline.
- The response takes several seconds to reach the gateway.
- The gateway then starts its timer.
- Those intervening seconds are not subtracted.

Consequently, the effective execution deadline can exceed the authoritative D-189 deadline.

This is not merely a test concern. It is a defect in the specified timing mechanism.

Classification: [a] Design defect.

- Location: R9-4, authoritative time and enforcement.
- Decision: D-189.
- Failure scenario: delayed admission response or gateway scheduling delay before timer start.
- Consequence: operation receives more time than authorised.
- Minimum correction: define a mechanism that accounts for all elapsed time between the central admission-time reading and gateway enforcement. A monotonic elapsed-time measurement must begin before or encompass the central time acquisition, with a conservative bound for the database round trip and admission commit.

### 5.3 Live-database termination

The design states that the gateway cancels and then terminates the backend.

However, it does not adequately specify the outcome when backend termination cannot be confirmed.

The backup-copy path has an explicit fallback: destroy the isolated copy and report the incident.

The live-database path needs an equally explicit containment and reporting rule.

A timeout is not evidence that the database operation stopped.

Classification: [a] Design defect.

Minimum correction:

- define positive termination confirmation;
- define the containment action if confirmation is unavailable;
- prevent further access through the affected session;
- preserve evidence of the uncertain operation;
- report the incident to the organisation.

### 5.4 Deadline assessment

| Requirement                                             | Assessment                                   |
| ------------------------------------------------------- | -------------------------------------------- |
| Central clock at admission                              | [d]                                          |
| Minimum of expiry and operation limit                   | [d]                                          |
| Remaining-duration propagation                          | [a] Timing gap                               |
| Restart cannot extend deadline                          | [b]                                          |
| Retry cannot extend deadline                            | [b]                                          |
| Queue delay cannot extend deadline                      | [b]                                          |
| Clock difference cannot extend deadline                 | [d] Design approach                          |
| Work actually stops                                     | [a] Live termination confirmation incomplete |
| No result after deadline                                | [b]                                          |
| Backup-copy work bounded by deadline                    | [b]                                          |
| Unconfirmed backup termination → destruction and report | [d] Design specified; [b] proof              |
| Unconfirmed live termination                            | [UNKNOWN]                                    |
| Refuse when time cannot be established                  | [d]                                          |

### 5.5 Required proof extension

D-4 should additionally test:

- delay between the central time read and gateway timer start;
- delay between admission commit and dispatch;
- process suspension and restart;
- gateway failover;
- live backend termination failure;
- inability to confirm termination;
- destruction and audit evidence for the backup-copy fallback.

Question 4 verdict: R9-4 does not yet establish the D-189 guarantee. The timing gap must be corrected before acceptance.

## 6. Question 5 — R9-5: D-188 reporting

Partially resolved

### 6.1 What R9-5 fixes

R8-7 did not explicitly require the failed designation itself to appear in the report.

R9-5 corrects this.

The proposed report identifies:

1. The failed designation: decision, part, person and outcome.
2. Every grant suspended because of that designation.
3. Every operation under those grants, including cases where access may have occurred.

This addresses the central reporting requirement of D-188.

[d] Earlier reporting omission corrected

### 6.2 Remaining reporting defect

The commission requires more than a list of operations.

For each operation, the report must establish:

- operation identifier;
- operation status;
- whether access completed;
- whether access may have occurred;
- whether termination was confirmed;
- the associated audit evidence.

R9-5 says every operation is included, but does not explicitly define these reporting fields.

Nor does it explicitly establish how the report links each operation to its durable audit record.

Classification: [a] Design defect.

Minimum correction: define a per-operation reporting record with status, outcome, access uncertainty, termination status where applicable, and an audit-evidence reference.

### 6.3 Delivery and durability

The organisation inbox and event-id idempotency are appropriate.

D-179 notices to current contacts are also consistent with the permitted security-notification channel, provided that the report's substantive organisational content remains in the organisation's inbox.

However, the following remain unproved:

- durable report creation;
- retry after delivery failure;
- duplicate delivery;
- report completeness when reconciliation is interrupted;
- traceability from the report to the original designation, grant and operation records.

Classification: [b] Proof obligation.

Question 5 verdict: The design corrects the missing designation reporting, but the report's content and delivery guarantees need further specification.

## 7. Question 6 — R9-6: Proof register

Fingerprint and register identity verified

### 7.1 Register integrity

The new register:

`2026-11-11-platform-proof-obligation-register.md`

correctly identifies revision 9 and the exact subject fingerprint.

The old revision-7 register is retained as historical material.

The register contains:

| Section                  | Obligations | Status       |
| ------------------------ | ----------- | ------------ |
| A — Concurrency matrix   | 14          | NOT EXECUTED |
| B — Other obligations    | 10          | NOT EXECUTED |
| C — Revision 8 additions | 9           | NOT EXECUTED |
| D — Revision 9 additions | 6           | NOT EXECUTED |
| Total                    | 39          | NOT EXECUTED |

39 obligations accounted for

No test is marked as executed or passed.

### 7.2 Earlier obligations

The earlier obligations have not been silently discarded:

- A and B are carried forward.
- C remains applicable.
- D adds the revision-9 obligations.
- The previous concurrency and failure-injection obligations remain relevant.

This is the correct approach.

### 7.3 Coverage gaps

The six D-series obligations cover the principal revision-9 changes. However, the new review findings expose additional tests that are not explicitly represented.

| Missing or insufficient coverage | Required addition                                                      |
| -------------------------------- | ---------------------------------------------------------------------- |
| R9-2 concurrent inbox updates    | Competing versions 5 and 6; prove version 5 cannot overwrite version 6 |
| R9-3 registry-only failure       | Crash after central registry commit but before organisation admission  |
| R9-3 restart                     | Registry and `started` state survive restart without second execution  |
| R9-4 timer-start gap             | Delay between central time acquisition and gateway timer start         |
| R9-4 live termination            | Termination cannot be confirmed; prove containment and reporting       |
| R9-5 reporting                   | Status, uncertainty and audit-evidence completeness                    |

The register should also include an explicit integration proof for Identity's `PersonRef` to Account resolution.

Classification: [a] for the missing atomic inbox rule and incomplete design specifications; [b] for the additional proof coverage.

### 7.4 D-series assessment

| Obligation                    | Assessment                                              |
| ----------------------------- | ------------------------------------------------------- |
| D-1 AuthorityCheck predicate  | Appropriate, but depends on unresolved M-1, M-2 and M-4 |
| D-2 `started` claim           | Appropriate, but needs durability and restart evidence  |
| D-3 global operation identity | Appropriate                                             |
| D-4 deadline                  | Incomplete for timer-start gap and live termination     |
| D-5 handover version          | Incomplete for concurrent inbox application             |
| D-6 D-188 reporting           | Incomplete for status, uncertainty and audit evidence   |

Question 6 verdict: R9-6 fixes the register's identity defect. The register is substantially complete as a carried-forward baseline, but it must be extended to cover the newly identified gaps.

Most importantly, all 39 obligations remain unexecuted. This is not an implementation-readiness declaration.

## 8. Question 7 — Additional duties

### 8.1 Re-check of R4 findings

| R4 finding                                                   | Revision 9 assessment                                              |
| ------------------------------------------------------------ | ------------------------------------------------------------------ |
| A-1 — AuthorityCheck subject/request mapping and rule checks | Partially corrected; M-1, M-2 and M-4 remain                       |
| A-2 — Per-designation proof                                  | Retained as C-3 and D-1; unexecuted                                |
| A-3 — Governance owns authority                              | Preserved                                                          |
| B-1 — Recovery/admission concurrency                         | Retained as C-1 and C-7; unexecuted                                |
| B-2 — D-187 handover pause                                   | Preserved                                                          |
| C-1 — Initial handover version                               | Corrected by R9-2; new inbox atomicity gap                         |
| C-2 — Delayed/reordered delivery                             | Retained as C-2 and C-9; insufficient for concurrent inbox updates |
| C-3 — Organisation ownership                                 | Preserved                                                          |
| D-1 — Global operation identity                              | Corrected by R9-3                                                  |
| D-2 — Started claim                                          | Corrected at design level; proof outstanding                       |
| E-1 — Deadline clock                                         | Improved by R9-4; timing and termination defects remain            |
| E-2 — Deadline proof                                         | Retained as C-6 and D-4; unexecuted                                |
| E-3 — D-189 policy                                           | Preserved                                                          |
| F-1 — Failed designation reporting                           | Corrected by R9-5; report completeness remains                     |
| F-2 — D-188 and D-186 distinction                            | Preserved                                                          |
| G-1 — Register identity                                      | Corrected by R9-6                                                  |
| G-2 — Proof execution                                        | All 39 remain unexecuted                                           |
| G-3 — INV-ACCESS-1                                           | Preserved                                                          |

### 8.2 Governance ownership

The architecture must retain the following separation:

- Governance owns organisational authority and its decisions.
- Organisation owns its organisational records and decisions to grant.
- Platform owns the organisation registry, database locator, platform status, service-operation grant record and pending case-grant records.
- Membership owns membership facts.
- Finance owns Finance facts.
- Identity owns person/account identity and bindings.

R9 does not formally transfer any of these business ownership responsibilities to Platform.

However, the new AuthorityCheck act kinds require the contract extension described in section 2. Platform must not interpret its contact registry as an independent source of constitutional authority.

Classification: [d] for the accepted ownership allocation; [c] for the proposed Governance contract extension.

### 8.3 Central metadata minimisation

The central operation registry is appropriately limited to operation metadata.

The central platform-contact information needs one additional clarification.

D-174 says Platform holds only account/PersonRef references centrally. D-184 subsequently requires outage-period precedence to be based on the dates of the organisation decisions cited by contact registrations.

R9's earlier PlatformContacts model stores designation decision references and dates.

These are useful provenance metadata, but the exact permitted central representation should be explicitly reconciled with D-174 and D-184.

Classification: [UNKNOWN] regarding the precise permitted metadata representation.

This is not a request to reopen D-174 or D-184.

### 8.4 No implied content access

The following principles remain essential:

- A service-operation grant does not permit human reading of organisation content.
- A platform contact is not automatically a member or organisational office-holder.
- An Account association does not establish organisational authority.
- A backup copy does not grant content-access permission.
- A recovery operation does not authorise unrestricted reading.
- A Platform operation registry does not become an organisation business record.

R9-3 and the gateway architecture preserve these boundaries at design level.

Classification: [d] for the accepted ownership principles; [b] for isolation and access-control proofs.

### 8.5 Missing and unresolved dependencies

| Dependency                                    | Independent assessment                                              | Classification |
| --------------------------------------------- | ------------------------------------------------------------------- | -------------- |
| M-1 — New Governance act kinds                | Not supported by the accepted act-kind list                         | [c]            |
| M-2 — PersonRef to Account                    | Ratified identity principles exist; exact operation is not accepted | [UNKNOWN]      |
| M-3 — GD-1, GD-11, GD-12                      | Detailed definitions remain in a proposed model                     | [UNKNOWN]      |
| M-4 — Rule/version stored per Resolution part | Plausible interpretation, not fully established by accepted text    | [UNKNOWN]      |

None should be silently treated as resolved.

## 9. Consolidated findings register

The following is the independent classification, rather than the author's proposed status table.

| ID    | Finding                                                                            | Classification | Priority |
| ----- | ---------------------------------------------------------------------------------- | -------------- | -------- |
| R5-01 | Governance does not yet support the two proposed act kinds                         | [c]            | Critical |
| R5-02 | PersonRef-to-Account resolution contract not established                           | [UNKNOWN]      | Critical |
| R5-03 | GD-11/GD-12 detailed semantics and M-4 persistence assumption unresolved           | [UNKNOWN]      | High     |
| R5-04 | Organisation inbox lacks atomic version compare-and-apply                          | [a]            | Critical |
| R5-05 | Deadline timer can omit elapsed time before timer start                            | [a]            | Critical |
| R5-06 | Live-database termination failure has no explicit containment fallback             | [a]            | Critical |
| R5-07 | D-188 operation report lacks defined status and audit-evidence fields              | [a]            | High     |
| R5-08 | Registry-only crash/retry lifecycle before organisation admission is unspecified   | [a]            | High     |
| R5-09 | Additional execution and concurrency proofs required                               | [b]            | High     |
| R5-10 | Exact central contact metadata allowance needs reconciliation                      | [UNKNOWN]      | Medium   |
| R5-11 | Composite subject identifier encoding and Identity integration need proof          | [TU]           | High     |
| R5-12 | Actual backend termination and isolation guarantees require technical verification | [TU]           | Critical |
| R5-13 | Earlier R4 findings and obligations                                                | [d] / [b]      | Tracked  |

### Evidence and required action

R5-01 — [c] Governance contract extension

- Evidence: file 16, §6.2, lines 119–134; additions at lines 192–229; file 12, D-157 and D-163.
- Failure scenario: Platform submits a designation or case-grant request using an act kind that the accepted Governance contract does not define.
- Consequence: there is no accepted basis for Governance to answer that request under the existing contract.
- Minimum action: obtain the necessary Principal authorization and Governance ratification for the new contract elements.

One precise Principal question:

Does the Principal authorize extending the Governance AuthorityCheck contract to Platform with `designate-platform-contacts` and `give-case-grant`, subject to their explicit definition and Governance ratification?

R5-02 — [UNKNOWN] Identity contract

- Evidence: file 20, XD-3 §2; file 19, proposed I-TDD-01; file 21, I-TDD-02 scope.
- Consequence: the design cannot yet demonstrate that the designated PersonRef resolves to the correct Account.
- Minimum action: complete and accept the Identity resolution contract. Preserve the prohibition on email matching and unauthorised cross-organisation inference.

R5-03 — [UNKNOWN] Governance decision-log dependencies

- Evidence: file 16, §6.1; file 17, §20.1.
- Finding: the accepted thin slice explicitly establishes Resolution immutability and per-part competence evaluation. However, the detailed GD-11/GD-12 knowledge-position rules remain in the proposed model.
- M-4: the supplied accepted material does not explicitly establish that each Resolution part permanently stores `ruleRef` and `policyVersion` as fixed competence-evaluation data.
- Minimum action: establish these details in an accepted Governance contract before relying on them as settled facts.

R5-04 — [a] Concurrent inbox delivery

- Evidence: subject R8-3 and R9-2, lines 971–980 and 1082–1086.
- Failure: an older delivery commits after a newer delivery.
- Minimum correction: atomic version comparison and update, with terminal-state protection.

R5-05 — [a] Deadline enforcement

- Evidence: subject R9-4, lines 1102–1110; D-189, file 12, §11.88.
- Failure: elapsed time between the central admission timestamp and gateway timer initiation is omitted.
- Minimum correction: account for the complete elapsed interval using a bounded monotonic-time mechanism.

R5-06 — [a] Live backend termination

- Evidence: subject R9-4, lines 1111–1119.
- Failure: the live backend continues working or termination cannot be confirmed.
- Minimum correction: define positive termination confirmation and an explicit fail-closed containment and reporting procedure.

R5-07 — [a] D-188 report completeness

- Evidence: subject R9-5, lines 1122–1129; D-188, file 12, §11.87.
- Failure: the report identifies affected operations without defining their individual status, uncertainty and audit references.
- Minimum correction: define a complete per-operation report record and durable traceability.

R5-08 — [a] Registry-only failure

- Evidence: subject R9-3, lines 1088–1100; R8-5, lines 990–1001.
- Failure: the central registry commits, but the organisation-side admission has not yet committed. A retry finds an existing operation, but the design does not specify whether admission resumes or the operation becomes terminally refused.
- Minimum correction: specify the registry-to-admission lifecycle and idempotent recovery semantics. Do not allow an ambiguous retry to start a second execution.

R5-09 — [b] Proof coverage

- Evidence: file 03, sections A–D.
- Required tests: concurrent inbox updates, registry-only crash, restart, delayed timer start, failed live termination, complete reporting and Identity integration.
- Evidence needed: real transaction tests, failure injection, restart tests and durable audit records.

R5-10 — [UNKNOWN] Central metadata

- Evidence: file 12, D-174, D-176 and D-184; subject §3.2.
- Finding: the exact representation of decision references and dates alongside central contact references needs explicit reconciliation.
- Minimum action: document the minimum metadata necessary to enforce the already accepted outage-precedence rules without expanding Platform's ownership.

R5-11 — [TU] Subject identifier

- Evidence: file 16, correction note 4; subject R9-1, lines 1061–1079.
- Required evidence: demonstrate that the subject identifier binds the exact decision, part and PersonRef and that a request identifier cannot be reused.

R5-12 — [TU] Actual termination

- Evidence: subject R9-4; file 13, INV-ACCESS-1.
- Required evidence: demonstrate that cancellation and termination stop actual backend work, that backup-copy destruction is effective, and that no result escapes after the deadline.

R5-13 — [d] / [b] Earlier findings

- Evidence: file 05, IR-P-02-R4, §§3–5; subject R9-1 through R9-6; file 03.
- R4's corrected design defects are not reopened where R9 genuinely resolves them.
- The corresponding unexecuted proof obligations remain build gates.

## 10. Overall architectural assessment

The revision-9 work is meaningful. In particular:

1. Global operation identity is better defined.
2. The `started` claim is more precise.
3. Handover now has an explicit version transition.
4. The deadline model is more sophisticated.
5. D-188 reporting explicitly identifies the failed designation.
6. The proof register has been properly re-stamped.

Nevertheless, the remaining defects are not cosmetic.

The most important architectural distinction is between three separate questions:

- Authority: Is the person entitled to designate contacts or approve a case grant?
- Identity: Is this exactly the person/account represented by the organisation's designation?
- Execution: Can the Platform guarantee that access stops within the authorised time?

Revision 9 has improved the execution design but has not fully established all three contracts.

The Governance and Identity gaps are especially important because the Platform must never manufacture organisational authority or infer identity through an unauthorised association.

### Business explanation for the Principal

The practical situation is this:

- The Platform can maintain the list of people permitted to act during an outage.
- But Governance must establish that the original designation was legitimate.
- Identity must establish that the designated person corresponds to the correct login account.
- The Platform must prove that support access ends when permission expires or is withdrawn.
- If a designation is invalid, the organisation must receive a complete and trustworthy report of what happened.

Until these conditions are specified, the design should not be accepted.

## 12. Final verdict

# NOT READY

Revision 9 must not be accepted in its present form.

The Governance extension, Identity dependency, inbox atomicity, deadline enforcement and reporting defects require resolution.

### Report file

2026-11-11-ir-p-02-r5-independent-re-review\.md

Complete independent review · Markdown · 30 KB

Created

Download the complete IR-P-02-R5 report

The report has been created using the requested relative directory structure under `/mnt/data`. The original repository was not included, so I could not place it directly into the repository.

Review boundaries respected:

- All 21 manifest entries verified.
- Revision 9 fingerprint independently confirmed.
- No source or decision modified.
- D-164 through D-189 not reopened.
- No implementation authorised.
- All 39 proof obligations remain unexecuted.

Reviewer: GPT-6, ChatGPT.

Review stopped.
