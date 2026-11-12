<!-- Recorder note (Claude, 2026-11-11):
- What follows: GPT-6's IR-P-02-R4 focused re-review (ChatGPT), as pasted by the Principal; extracted verbatim from the session transcript; body unaltered. GPT-6's own saved copy (23 KB) was not received.
- Discrepancy: the report prints the subject fingerprint as `28cfecdfbf11874ae9af9e935d25a91cda962ea1d0b8708acde987611dc5006`, which has 63 hex characters. The true fingerprint of revision 8 is `28cfecdfbf11874ae9af9e935d25a91cda962ea1d0b8708ac8de987611dc5006` (64): one '8' is missing after '...8708ac'. The report states that the manifest and fingerprint verification passed; the printed value is most likely a transcription error. Recorded, not corrected. -->

# IR-P-02-R4 — Focused Re-Review: Platform Revision 8

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11 |
| **Reviewer** | **GPT-6 in ChatGPT** |
| **Prior involvement** | Author of IR-P-02, IR-P-02-R, IR-P-02-R2 and IR-P-02-R3. This is not a blind review. |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, revision 8 |
| **Subject SHA-256** | `28cfecdfbf11874ae9af9e935d25a91cda962ea1d0b8708acde987611dc5006` |
| **Status** | Proposed, not accepted. No implementation authorised. |
| **Review basis** | The nine files in the supplied IR-P-02-R4 handover ZIP. |

## 1. Integrity and scope

### 1.1 Integrity

- `MANIFEST.sha256`: **9/9 files verified**.
- Outer `SHA256SUMS`: **11/11 entries verified**, including `MANIFEST.sha256`.
- Revision 8 subject fingerprint: **verified**, exactly matching the commission's fingerprint.
- ZIP archive: opened and all listed files read.

No integrity discrepancy was found.

### 1.2 Scope discipline

This review addresses R8-1 … R8-7, the R3 findings, the proof register and new high-severity contradictions apparent in the supplied materials. It does not reopen D-164, D-166, D-170, D-173 … D-189, including D-188 and D-189. It makes no Principal decision and does not authorise implementation.

The report uses the subject's own revision 8 section numbering and line references in the supplied file. The commission's L-1 … L-4 limitations are taken into account.

## 2. Executive conclusion

Revision 8 materially improves the design. In particular:

- the handover state is checked on the grant row rather than inferred from outbox existence;
- the grant copy has a monotonic versioning rule and terminal-state protection;
- admission is separated from execution, with a durable `started` transition and no exactly-once claim;
- D-189's 15-minute default and shorter-grant limit are incorporated;
- D-188's suspension consequence is incorporated.

However, **revision 8 is not ready for Principal acceptance**. Three design issues remain:

1. **R8-1:** the request binding does not fully specify conformance to the accepted AuthorityCheck contract. It does not define how the decision part is represented as the contract's subject identifier, and its acceptance checks do not compare the returned `ruleRef` and `policyVersion` with the expected rule/version for the designation.
2. **R8-4:** the stated uniqueness key `(grant_id, operation_id)` does not enforce the stated rule that reuse of the same `operationId` with a different grant is refused. The operation binding also omits an explicit request identity, although the commission requires a different request to be refused.
3. **R8-6:** the deadline is derived from `t_admit`, which has an inherited gateway-clock definition in R6-4, but the design does not finish specifying how that deadline is enforced consistently across gateway, organisation database and backup worker clocks. This needs a precise implementation contract and proof, not a new Principal policy.

There are also unexecuted proof obligations, which remain build gates, and a proof-register metadata defect (its title and header still identify revision 7).

**Verdict: NOT READY.** The identified design defects are technical and correctable; no new Principal decision is required by this review.

## 3. Questions A–G

### A. R8-1 — Individual AuthorityCheck binding

**Assessment: PARTIAL.**

**What is correct**

- R8-1 specifies one request per designated person, rather than allowing one person's positive result to promote another person's entry (subject §Revision 8, R8-1, lines 927–950).
- It binds the request to the act kind, designated `PersonRef`, decision reference and part, and a one-use reconciliation request id.
- It requires the answer's `evaluatedFor` to resolve through Identity to the Account of the entry being promoted.
- It refuses an answer for another person, decision, part, reused request id or unissued request.
- The per-person request design addresses R3-1's cross-person promotion/replay problem.

**Finding A-1 — (a) Correctable design defect: incomplete accepted-contract mapping**

Governance's accepted correction note 4 says a subject-specific AuthorityCheck answer carries `requestRef` containing the subject and request identifiers, and that the consumer accepts it only for that request (file 08, §Correction note 4, line 229). It does not define a new independent `requestRef` schema or expressly define the decision part as a separate field within that identifier.

R8-1 says `requestRef` is `(act kind, decision reference and part, designated PersonRef, reconciliation request id)` (subject §Revision 8, R8-1, line 935). This may be representable as the accepted subject/request identifiers, but the mapping is not demonstrated. In particular, the design does not say which component is the contract's subject identifier and how the decision part is included in that identifier without creating a second schema.

There is a further omission in the actual acceptance predicate. R8-1 lists the answer's `ruleRef` and `policyVersion` among the returned fields (lines 936–938), and C-3 expressly expects a rule-version mismatch to be tested (proof register, line 54). But the acceptance checks at lines 939–943 do not compare either `ruleRef` or `policyVersion` with the rule/version expected for the designation. The statement that a different rule version is refused is therefore not implemented by the written predicate.

**Required correction:** specify the exact mapping of the decision-part/person subject and request id into the already accepted `requestRef` meaning, and include equality checks for the expected `ruleRef` and `policyVersion` before promotion. Do not create a second AuthorityCheck schema.

**Finding A-2 — (b) Remaining proof obligation [TU]**

C-3 is appropriate and remains unexecuted. It must test multiple designations in one decision with distinct outcomes, wrong person, wrong request, wrong decision part, wrong rule version, cross-person replay and request-id reuse.

**Finding A-3 — (d) Already resolved by an accepted decision**

The requirement that Governance, not Platform, decides authority is binding under the Governance authority model and D-164. R8-1 correctly routes the check to Governance; this review does not reopen that allocation.

### B. R8-2 — Recovery transition and admission

**Assessment: PARTIAL — design sequence is coherent, execution proof remains outstanding.**

R8-2 uses one central transaction: lock `ServedOrganisation` X, restore routing, lock each `PendingCaseGrant` X, set `handoverState = handover`, insert the outbox record and commit (subject §Revision 8, R8-2, lines 952–969).

Admission takes the grant row shared lock and checks `handoverState`, rather than relying on whether an outbox record happens to be visible. For an admission holding the shared lock first, recovery waits; for recovery obtaining the exclusive lock first, a later admission sees `handover`. This is the correct per-grant serialisation mechanism.

**Lock-order assessment**

The global order remains `ServedOrganisation` → `PendingCaseGrant` → organisation copy (subject R6-5, lines 761–768). R8-2's recovery transaction follows the first two locks in that order. Admission takes the grant row and then, where required, the organisation copy, following the suffix of that order. The accepted multi-database design's organisation-side withdrawal locks its own copy row and does not synchronously acquire the central grant row while retaining that lock; propagation is an asynchronous cross-database step (file 07, accepted multi-database design; subject R6-4/R7-4, lines 739–752 and 866–876).

No reverse acquisition or lock cycle is specified in the supplied R8-2 path. No deadlock is established by the written sequence.

One important precision: the linearisation point is **per grant row**, not a single indivisible point for every grant belonging to the organisation. The transaction commits the routing restoration and all the per-grant transitions atomically as one central database commit, but each grant's admission race is serialised by that grant's lock. The text should preserve this distinction.

**Finding B-1 — (b) Remaining proof obligation [TU]**

C-1 must test admission against the recovery transaction for each grant, including both lock orders, multiple grants, transaction rollback and a failure while the recovery transaction is processing the grant set. It must demonstrate that no admission can use the central grant after that grant's handover transition has linearised.

C-7 must continue to prove the missing-copy refusal and fresh-request rule. The design does not establish execution evidence.

**Finding B-2 — (d) Already resolved by an accepted decision**

D-187's pause during handover remains binding. The absent-copy refusal rule is retained in R8-2/R7-3. No reopening of D-187 is proposed.

### C. R8-3 — Versioned copies and terminal states

**Assessment: PARTIAL.**

The central version, higher-version-only inbox application and terminal-state rules address the stale-delivery problem (subject §Revision 8, R8-3, lines 971–980). Requiring admission to find both the central grant and organisation copy active is particularly important: a stale active copy cannot override a terminal central state.

The rule that every state change during or after handover creates an outbox record in the same transaction is the correct direction. Organisation-side withdrawal is also required to propagate back to the central row.

**Finding C-1 — (a) Correctable design defect: define version treatment at handover**

R8-3 says `PendingCaseGrant.version` increases with every state change (line 973). R8-2 changes `handoverState` from `none` to `handover` and creates the first outbox record (lines 956–959), but does not expressly state whether this transition increments the version or what version the first outbox record carries.

Because the inbox applies only a higher version (R8-3, line 975), the initial handover version must be unambiguous. State explicitly that the handover transition increments the grant version and that its outbox record carries that new version, or explicitly define an equivalent version rule.

**Finding C-2 — (b) Remaining proof obligation [TU]**

C-2 and C-9 remain essential. They must exercise delayed, duplicated and reordered deliveries, including an older active copy arriving after withdrawal, every terminal state, and both lower- and higher-version messages. The proof must establish that no delivery reactivates a terminal grant.

The register correctly identifies these cases, but none has been executed.

**Finding C-3 — (d) Already resolved by an accepted decision**

D-187's handover pause and the accepted organisation ownership of its records remain binding. R8-3's monotonic copy rule is an implementation mechanism, not a change to that decision.

### D. R8-4 and R8-5 — Operation identity and admission lifecycle

**Assessment: NOT RESOLVED.**

#### R8-4

R8-4 says the gateway assigns an `operationId` bound to grant, operator, operation kind and scope digest, with uniqueness key `(grant_id, operation_id)` on both the organisation admission row and central access record (subject §Revision 8, R8-4, lines 982–988).

**Finding D-1 — (a) Correctable design defect: uniqueness scope contradicts the refusal rule**

The commission expressly requires that the same id with a different request, grant, scope or kind be refused.

The specified unique key is scoped by `grant_id`. Consequently, the same `operationId` used with a different grant does not collide with the first key: `(grant-A, operation-X)` and `(grant-B, operation-X)` are distinct keys. The text's statement that a different binding is refused is not enforced by the specified uniqueness constraint.

The binding also does not explicitly include a request identifier. A different request using the same grant, operator, operation kind and scope digest could therefore be indistinguishable under the stated binding unless the request identity is part of the scope digest by an expressly defined rule. That rule is absent.

**Required correction:** define a globally unique operation identity, or a uniqueness/registry mechanism that detects reuse of an operation id across grants and compares the complete immutable binding. Include the request identity explicitly in that binding. A same-id/different-grant or same-id/different-request attempt must be refused, not create another admission.

#### R8-5

The durable lifecycle `admitted` → `started` → terminal outcome, with `started` committed before execution, is a substantial improvement (subject §Revision 8, R8-5, lines 990–1001). The no-exactly-once statement is correct. The distinction between retry before `started` and no re-execution after `started` is also correct.

**Finding D-2 — (b) Remaining proof obligation [TU]**

C-5 and B-1 must prove that the transition to `started` is claimed atomically by only one executor, including concurrent retries. A crash after admission commit but before acknowledgement must not create two workers that both proceed to `started` or execute. A crash after `started` must never silently re-execute, and reconciliation must report "access may have occurred" where the outcome is absent.

The design's intended lifecycle is sound, but the required concurrency and failure-injection evidence does not exist.

### E. R8-6 — D-189 deadline

**Assessment: PARTIAL.**

R8-6 correctly applies `min(grant expiry, t_admit + limit)`, uses the D-189 15-minute default, permits a shorter grant limit and prohibits a longer one (subject §Revision 8, R8-6, lines 1003–1012). It applies to both `live-database` and `backup-copy`, and says no result is delivered after the deadline.

D-189 is binding and is not reopened (file 06, §11.88, lines 1257–1263).

**Finding E-1 — (a) Correctable design defect: deadline clock/enforcement contract is incomplete**

The earlier R6-4 text identifies `t_admit` as the time read by the gateway after acquiring the platform shared lock (subject R6-4, lines 753–757). R8-6 inherits that definition, so the admission instant is not wholly unspecified.

However, R8-6 says the organisation transaction receives statement and transaction timeouts "set to the deadline", and that the backup job is terminated at the deadline. It does not define the clock authority and clock-comparison mechanism by which the gateway, organisation database and backup worker enforce the same absolute deadline. Nor does it state how clock skew, delayed dispatch or a worker that fails to stop is handled. The gateway's no-late-result rule protects disclosure, but alone does not prove that long-running work is stopped or isolated.

**Required correction:** define the gateway's admission timestamp and deadline as the authoritative time basis, define how each execution environment enforces or safely bounds that deadline, and specify the fail-closed behaviour when it cannot establish that the deadline is still valid. Do not alter D-189's 15-minute default or the organisation's right to shorten it.

**Finding E-2 — (b) Remaining proof obligation [TU]**

C-6 must test just-before, at and after expiry; just-before withdrawal; delayed execution; shorter grant limits; no result after deadline; live transaction abort; backup worker termination; and disposal of partial output. The proof must include clock-boundary and delayed-dispatch cases. C-6 is NOT EXECUTED.

**Finding E-3 — (d) Already resolved by an accepted decision**

D-189 fixes the maximum operation duration and its default/shorter-only rule. No Principal decision is requested on that policy.

### F. R8-7 — D-188 suspension

**Assessment: PARTIAL.**

R8-7 suspends grants approved by a contact whose designation returns `NotAuthorised` or `Undetermined`, increments the grant version and emits an outbox record (subject §Revision 8, R8-7, lines 1014–1023). It says no new admission is allowed, in-flight work ends by its D-189 deadline, and access already taken is reported.

D-188 requires the grant **and the designation** to be reported to the organisation, as well as access already taken (file 06, §11.87, lines 1249–1255). R8-7 says that grants are reported and flagged and that access already taken is reported (subject §Revision 8, R8-7, lines 1016–1023), but does not expressly require the failed designation itself to be included in the report.

**Finding F-1 — (a) Correctable design defect: designation reporting is not explicit**

Add an explicit requirement that the failed designation, the affected grant(s), and any access already taken are reported to the organisation. This is implementation of D-188, not a new decision or a reopening of D-188.

**Finding F-2 — (b) Remaining proof obligation [TU]**

C-8 must prove that every grant approved by the failed designation is suspended, no new admission is possible, in-flight operations are bounded as specified, and the contact is not promoted. It must also prove the grant, failed designation and access already taken are reported to the organisation.

**Finding F-3 — (d) Already resolved by an accepted decision**

D-188 is binding. D-186's later-removal rule remains distinct and unchanged. This review does not reopen either decision.

No new Principal choice is identified.

### G. Proof register

**Assessment: PARTIAL — coverage is present; register metadata needs correction; all execution remains outstanding.**

The register contains:
- Section A: 14 concurrency cases (lines 10–27).
- Section B: 10 additional obligations (lines 29–44).
- Section C: 9 revision-8 obligations, C-1 … C-9 (lines 46–60).

Total: **33 proof obligations**. Every entry is marked **NOT EXECUTED**. The register's opening declaration also says no proof has been executed and no Platform code, test or database exists (file 03, lines 5–8). This is consistent with the commission's statement that no proof obligation has been executed.

**Finding G-1 — (a) Correctable design/documentation defect: stale register identity**

L-1 is confirmed. The register filename and heading still say revision 7, and its header identifies the revision-7 subject fingerprint `6de2d739…` (file 03, lines 1–6). Section C correctly identifies the revision-8 fingerprint and says its nine additions are still unexecuted (lines 46–60).

The register is therefore not wholly re-stamped as the revision-8 register. This is a metadata/provenance defect, not evidence that the listed tests have run or that their content is invalid. Correct the filename/title/header and explicitly identify sections A/B as carried-forward obligations reviewed for continued applicability to revision 8. Preserve all statuses as NOT EXECUTED unless actual execution evidence is later supplied.

**Finding G-2 — (b) Remaining proof obligation [TU]**

All 33 entries remain unexecuted build gates. C-1 … C-9 are present and traceable to R8-1 … R8-7. Sections A and B retain the earlier concurrency and operational proof obligations. The new C entries supplement rather than replace the earlier proof set.

A specification, test description, static review or code review is not execution evidence. No entry is marked passed by this review.

**Finding G-3 — (d) Already resolved by an accepted decision**

The accepted multi-database design's INV-ACCESS-1 release criteria remain build/go-live gates (file 07, §16 M-6; register B-10). No relaxation is proposed.

## 4. Consolidated findings and classification

| ID | Finding | Classification | Consequence |
|---|---|---|---|
| A-1 | `requestRef` subject/decision-part mapping is not fully defined; returned `ruleRef` and `policyVersion` are not checked | **(a) Correctable design defect** | Correct R8-1 before acceptance |
| A-2 | Per-designation binding/replay tests C-3 | **(b) Remaining proof obligation** | Build gate |
| A-3 | Governance owns authority; accepted AuthorityCheck contract is binding | **(d) Already resolved by accepted decision** | Do not reopen |
| B-1 | Recovery/admission concurrency proof C-1 and missing-copy proof C-7 | **(b) Remaining proof obligation** | Build gate |
| B-2 | Handover pause under D-187 | **(d) Already resolved by accepted decision** | Do not reopen |
| C-1 | Explicit version increment/value at first handover outbox event is missing | **(a) Correctable design defect** | Clarify R8-2/R8-3 |
| C-2 | Delayed, duplicate, reordered delivery and terminal-state proofs C-2/C-9 | **(b) Remaining proof obligation** | Build gate |
| C-3 | Organisation record ownership and D-187 boundary | **(d) Already resolved by accepted decision** | Do not reopen |
| D-1 | `(grant_id, operation_id)` does not reject same operation id across grants; request identity absent from binding | **(a) Correctable design defect** | Correct R8-4 |
| D-2 | Concurrent retry and failure-injection proof C-5/B-1 | **(b) Remaining proof obligation** | Build gate |
| E-1 | Clock authority/enforcement across gateway, DB and worker is incomplete | **(a) Correctable design defect** | Complete R8-6 without changing D-189 |
| E-2 | Deadline and termination proof C-6 | **(b) Remaining proof obligation** | Build gate |
| E-3 | D-189's duration policy | **(d) Already resolved by accepted decision** | Do not reopen |
| F-1 | D-188 suspension/reporting proof C-8 | **(b) Remaining proof obligation** | Build gate |
| F-2 | D-188 and D-186 distinction | **(d) Already resolved by accepted decisions** | Do not reopen |
| G-1 | Proof register filename/title/header remain revision 7 | **(a) Correctable documentation defect** | Re-stamp register |
| G-2 | All 33 proof obligations remain NOT EXECUTED | **(b) Remaining proof obligation** | Build gates |
| G-3 | INV-ACCESS-1 release criteria | **(d) Already resolved by accepted decision** | Preserve as release gate |

**Genuine Principal decisions: none identified in this review.** No finding is classified (c). The defects above concern technical specification and can be corrected without transferring engineering decisions to the Principal.

## 5. Verdict

# NOT READY

Revision 8 is not ready for Principal acceptance.

The blocking corrections are:

1. Complete the R8-1 AuthorityCheck request/answer binding against the existing Governance contract, including decision-part representation and `ruleRef`/`policyVersion` checks.
2. Correct R8-4 operation identity so the same operation id cannot be reused with a different request or grant, and bind the complete request identity.
3. Complete R8-6's deadline clock and enforcement contract across the gateway, organisation database and backup worker.
4. Make the initial handover version/outbox version rule explicit (R8-2/R8-3).
5. Correct the proof register's revision identity and fingerprint metadata.

Separately, all 33 proof obligations remain unexecuted. They are build gates and must not be confused with design acceptance.

No implementation is authorised by this report. No accepted decision is changed. No Principal decision is made or requested.

## 6. Reviewer identity and prior involvement

**Reviewer:** GPT-6 in ChatGPT  
**Prior involvement:** Author of IR-P-02, IR-P-02-R, IR-P-02-R2 and IR-P-02-R3. This review is independent of the revision-8 authoring, but not blind to the prior review history.

**Review completed:** 2026-11-11

**End of report.**
