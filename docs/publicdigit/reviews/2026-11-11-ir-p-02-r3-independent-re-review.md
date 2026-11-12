# IR-P-02-R3 — Focused Re-Review: Platform Revision 7

**Reviewer:** GPT-6 in ChatGPT  
**Commissioning authority:** The Principal (D-94)  
**Review date:** 2026-11-11  
**Subject:** `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, revision 7  
**Subject SHA-256:** `6de2d7393ce5fad6c154ae9d4699ccceacabd3789f36dabbe6ec5b81148636ee`  
**Prior involvement:** Author of IR-P-02, IR-P-02-R and IR-P-02-R2. This is not a blind review.

---

## 1. Executive conclusion

**Verdict: NOT READY.**

Revision 7 resolves important parts of the prior review and correctly incorporates the accepted decisions D-186 and D-187. Neither decision is reopened here.

However, revision 7 still has correctable design defects and one genuine Principal question:

1. R7-1 requires a Governance `Authorised` result, but does not specify a sufficiently explicit, non-reusable binding of that result to each individual contact designation.
2. R7-1 says grants associated with a designation that fails validation are flagged, but does not say whether those grants remain active or are suspended. D-176, D-181, D-186 and D-187 do not settle that consequence.
3. R7-3 does not specify a safe common linearisation point between recovery recognition, the grant's handover state and outbox creation. Its current admission table permits the central grant to govern whenever no outbox record exists, while the recovery declaration and outbox record are not explicitly atomic or mutually serialised with admission.
4. R7-3 also needs to prevent a platform withdrawal during handover from being overwritten by a later-delivered active copy.
5. R7-4 improves the organisation-side admission/withdrawal race, but leaves operation-ID uniqueness, post-admission connection failure and the operation deadline insufficiently specified. The referenced `operation limit` is not defined in the supplied Platform design.
6. The proof-obligation register correctly marks all listed obligations NOT EXECUTED. It does not, however, include all additional proof cases exposed by R7-3 and R7-4.

These defects are correctable without reopening accepted decisions. The unresolved effect on grants flagged after a contact designation fails validation is a genuine Principal question. No proof obligation has been executed, and no implementation readiness is established.

---

## 2. Integrity and scope

### 2.1 Manifest verification

All seven entries in `MANIFEST.sha256` were independently recalculated against the corresponding uploaded file contents. All seven SHA-256 values match.

| File | Manifest verification |
|---|---|
| 01 — commission | PASS |
| 02 — Platform tenancy tactical design, revision 7 | PASS |
| 03 — proof-obligation register | PASS |
| 04 — IR-P-02-R2 report | PASS |
| 05 — Organisation decision record | PASS |
| 06 — multi-database boundary tactical design | PASS |
| 07 — Governance thin-slice minimum package | PASS |

The subject fingerprint in file 01 matches file 02 exactly:

`6de2d7393ce5fad6c154ae9d4699ccceacabd3789f36dabbe6ec5b81148636ee`

**Integrity result: PASS.** The correct revision 7 subject was reviewed.

### 2.2 Scope respected

The review is limited to R7-1, R7-3, R7-4 and R7-5, plus only new high-severity contradictions that arise from those changes.

D-186 and D-187 are treated as binding accepted decisions. D-176, D-181, D-183, D-184, D-185, D-178, ISO-1 … ISO-11, INV-ACCESS-1, INV-ID-3 as narrowed by D-179, and the Governance authority model are not reopened.

The supplied proof register states that no code, test or database exists for the Platform context and that every entry is NOT EXECUTED [TU]. This review accepts that status. No proof obligation is treated as executed or resolved merely because its expected behaviour is specified.

---

## 3. Assessment by commissioning question

## Question 1 — R7-1: Governance validation and grants associated with invalid designations

**Status: PARTIAL**

**References:** Platform design §R7-1 (lines 829–839); §R7-2 (lines 841–845); R6-2; Governance thin-slice package §6.2 and correction note (file 07, lines 119–134 and 200–212); D-176, D-181, D-183, D-186 and D-187 (file 05, §§11.77, 11.80, 11.82, 11.85 and 11.86).

### 1A. Is Governance `Authorised` bound to each individual designation?

R7-1 makes the right substantive correction: a contact entry is not promoted merely because the organisation's records contain a designation. Promotion requires Governance's `AuthorityCheck` on the designating decision to return `Authorised`; `NotAuthorised` and `Undetermined` do not promote the entry. R7-1 also says the copied contact entry carries the AuthorityCheck outcome and applies the condition to every contact, including initial contacts.

That resolves the broad defect identified in R2-2. It does not yet fully specify the evidence-binding contract.

The Governance package defines AuthorityCheck as a request involving an organisation, acting `PersonRef`, act kind, scope and evaluation time, and requires the answer and its basis to be retained. Its ratified evidence shape includes the outcome, evaluated date, competence kind, decision reference and evidence, with the actor/capacity/rule/version/check-time information described in its correction notes. A subject-specific answer must carry a request reference and may be accepted only for that request (file 07, lines 119–134, 198–212, 225–229).

For this use case, the Platform design should state that each contact entry is promoted only on evidence tied to that exact designation, not on an organisation-level or decision-level `Authorised` result that could be reused for multiple contacts. The stored evidence must identify, at minimum:

- the act kind (`designate-platform-contacts`);
- the individual designated contact (`PersonRef`) as the subject of that designation;
- the decision and, where the decision has multiple parts, the exact designation part/request;
- the decision-maker/actor and relevant office or capacity;
- the cited rule provision and rule-document version;
- the evaluation date/time and the Governance outcome.

The exact field mapping should conform to the Governance contract rather than inventing a second AuthorityCheck schema. The essential point is that the AuthorityCheck result must be specific to one individual designation and must not be replayable as authority for another contact or another designation.

R7-1's phrase “AuthorityCheck on the designating decision” plus “every contact entry” points in the right direction, but does not explicitly state the subject-specific, non-replayable binding. A general `Authorised` result for a decision or organisation must not be sufficient where the decision designates more than one person or contains multiple parts.

**Finding R3-1 — Individual designation evidence is not explicitly bound and made non-reusable.**

- **Classification:** (a) Correctable design defect.
- **Severity:** High.
- **Business consequence:** A broad or incorrectly reused `Authorised` result could promote a person whose own designation was not established as competent. That person could then become eligible to participate in emergency grant approvals.
- **Required correction:** Specify one AuthorityCheck evidence instance per individual designation, bound to the designated `PersonRef` and exact decision/part/request, using the existing Governance contract. Reject mismatched or replayed evidence. Add this to the recovery-promotion proof tests.
- **Principal decision:** Not required. This is enforcement of the accepted Governance authority model, not a new business rule.

### 1B. What happens to grants given with the participation of a contact whose designation later fails validation?

R7-1 states that when the designation returns `NotAuthorised` or `Undetermined`, the contact is not promoted, the entry is removed during reconciliation, and grants given while the entry stood are flagged (Platform design §R7-1, lines 831–838).

The design does not say whether a flagged grant:

- remains in force until withdrawn or expired; or
- is suspended automatically pending review or a further decision.

The accepted decisions do not settle this:

- **D-176** says grants made through the emergency route are reported and copied on recovery, and that the organisation's own records govern once its database works. It does not define the effect on a grant already given when a participating contact's designation is later found invalid (file 05, §11.77).
- **D-181** establishes that `Undetermined` establishes no authority. It does not define the lifecycle of a grant already given before that validation result (file 05, §11.80).
- **D-186** settles the eligibility of both approvers at the moment the second approval is given, and expressly says a grant already `given` is not undone by a later removal. It does not decide the distinct case in which a designation itself fails Governance validation (file 05, §11.85).
- **D-187** requires the organisation's own copy to be present and checked before access resumes after recovery. It determines the handover access gate, not the validity or suspension status of a grant flagged after an AuthorityCheck failure (file 05, §11.86).

Therefore D-187 does not answer this question. Requiring the organisation's copy for access after recovery does not determine whether the grant represented by that copy remains active or is suspended.

**Finding R3-2 — The effect of failed contact-designation validation on already-given grants is unspecified.**

- **Classification:** (c) Genuine Principal question.
- **Severity:** High.
- **Business consequence:** The system cannot determine whether a flagged grant may continue to authorise access after recovery. Continuing it may preserve access approved with invalid authority; suspending it may interrupt an emergency operation that was valid under the platform's outage-time rules.
- **Exact Principal choice required:**
  - **Choice A — Remain in force:** A grant already `given` remains active until withdrawn or expired. The failed designation and associated grant are flagged and reported, but flagging alone does not suspend access.
  - **Choice B — Suspend:** A grant associated with a designation that returns `NotAuthorised` or `Undetermined` is suspended on reconciliation, and no further access is permitted unless and until the organisation authorises or reissues it through the proper route.
- **Reviewer recommendation:** Do not silently choose either. The consequences are materially different and the supplied accepted decisions do not determine the choice.

This is the only genuine Principal question identified in the focused review.

---

## Question 2 — R7-3: Recovery recognition and outbox creation as one safe transition

**Status: NOT RESOLVED**

**References:** Platform design §§3.4 and 4 (lines 114–130, 145–176); R6-4 (lines 735–757); R7-3 (lines 847–864); R7-5 row 3d (line 883); D-176 and D-187 (file 05, §§11.77 and 11.86).

R7-3 correctly states the D-187 rule once the outbox record exists:

- before handover, the central Platform grant row governs;
- once an outbox record exists, an absent or unreadable organisation copy causes refusal;
- once the copy is present, its state governs;
- the same refusal gate applies to `live-database` and `backup-copy` operations.

This is the right intended outcome. However, R7-3 does not establish that recovery recognition and the outbox marker form one safe transition, or that admission checks a recovery/handover state rather than relying only on the existence of the outbox record.

The table at lines 853–859 says that when there is no outbox record, the organisation's database cannot answer and the Platform row governs. The handover marker at line 849 is created when the database is declared able to answer. But the design does not say that:

1. the declaration that routing has been restored and the per-grant outbox record are committed in one central transaction; and
2. admission is serialised against that transition, so an operation cannot observe “no outbox record” and be admitted under the central grant after recovery has already been recognised.

The general rule at §4 that each aggregate change is one central transaction and organisation-database work is a separate idempotent step (lines 169–171) is not enough by itself. R7-3 must identify the relevant aggregate transition and the admission-side lock/check that make the recovery boundary safe.

**Finding R3-3 — Recovery-to-handover transition is not serialised with protected-operation admission.**

- **Classification:** (a) Correctable design defect.
- **Severity:** Critical.
- **Business consequence:** There may be an interval after recovery is recognised but before the outbox marker is committed in which an operation still sees “no outbox record” and is admitted under the central grant. This defeats D-187's intended pause during recovery handover.
- **Required correction:** Define a common linearisation point for recovery/handover and admission. A sufficient design is to update the central grant's handover state and insert its outbox record in the same central transaction, under the grant's exclusive lock; protected-operation admission must acquire the corresponding shared lock and check that state. Thus an admission linearised before the handover transition may proceed under the pre-handover rule, while every admission linearised after the transition is refused until the organisation copy is present and checked. The implementation must preserve the existing global lock order and avoid acquiring locks in reverse order.
- **Principal decision:** Not required. D-187 already decides the business outcome: pause access during handover. The missing part is tactical enforcement.

### Platform withdrawal during handover

R7-3 says platform withdrawal by any current contact remains immediate throughout (line 862). R7-5 row 3d includes a test that platform withdrawal during handover is immediate (line 883). Neither clause says how the later-delivered organisation copy preserves that withdrawal.

If a contact withdraws the central grant after the outbox event has been created but before the copy arrives, a previously queued copy could still be delivered with its earlier active state unless the delivery/reconciliation protocol carries or rechecks the later withdrawal. Once the copy exists, R7-3 says the organisation copy governs. A stale active copy must not thereby restore access to a centrally withdrawn grant.

**Finding R3-4 — A stale handover copy could undo a platform withdrawal.**

- **Classification:** (a) Correctable design defect.
- **Severity:** Critical.
- **Business consequence:** A grant withdrawn immediately on the Platform during handover could become usable again when an earlier active copy is delivered and begins governing access.
- **Required correction:** Make copy delivery/reconciliation monotonic with respect to the latest central grant state. A withdrawal committed during handover must be reflected in the delivered copy or cause delivery to be rejected/reconciled as withdrawn. The copy must never become active after a later central withdrawal. Define the ordering/idempotency rule and test withdrawal both before and after copy creation, including delivery after withdrawal.
- **Principal decision:** Not required. D-187 expressly preserves immediate platform withdrawal throughout handover.

---

## Question 3 — R7-4: Backup-copy admission, withdrawal, missing row, uniqueness and failures

**Status: PARTIAL**

**References:** Platform design R6-4 (lines 739–757); R7-4 (lines 866–876); R7-5 row 3b (line 882); rev 5 part 2 F-05; proof register rows 3b and B-1.

### 3A. Does the admission protocol serialise admission, organisation-side withdrawal and expiry?

The new protocol is a material improvement over a bare status read:

1. it takes `FOR SHARE` on the organisation grant-copy row;
2. checks the copy;
3. records an admission row in the same organisation-database transaction;
4. commits that admission;
5. organisation-side withdrawal takes `FOR UPDATE` on the same copy row.

For an existing grant-copy row, this serialises admission against organisation-side withdrawal. An admission committed before withdrawal is part of the in-flight set; an admission after withdrawal sees the withdrawn state. The failure-during-admission rule is also correct: if the database is lost before the admission transaction commits, no admission is recorded and the operation is refused.

However, this only establishes the serialisation of the admission decision. It does not by itself specify uniqueness of the operation, what happens after a connection failure following the admission commit, or a complete enforceable operation deadline.

### 3B. Missing copy row: is no lock needed?

**The author's claim is correct, with an explicit fail-closed condition.**

If the copy row is absent, the admission must refuse and must not insert an admission row. A concurrent delivery/insert may then cause a spurious refusal for that request, but cannot turn that already-refused request into an admission. There is no existing row on which `FOR SHARE` could lock, and a lock on a nonexistent row is not necessary to prevent admission if absence is unconditionally refusal.

This conclusion depends on the implementation not treating a concurrently arriving row as permission to continue the same request after the absence check. The request must terminate as refused; a new attempt can retry and perform a fresh locked check.

**Classification:** (d) Already resolved by the fail-closed rule in R7-3, lines 855–859, together with R7-4, lines 868–876. No separate Principal decision is required. Add a test for concurrent copy insertion to demonstrate that the only possible effect is a spurious refusal, never an admission.

### 3C. Does the admission row need a unique operation id?

**Yes.**

R7-4 says the admission row contains `(grant id, operation id)` but does not explicitly require a uniqueness constraint. The operation id must be unique at least within the relevant grant (or be globally unique), and retries with the same operation id must resolve to the existing admission rather than create a second logical admission.

Without this, a retry after a lost response can create multiple admission records for one requested operation. That undermines the claim that the admission table defines the in-flight set and complicates withdrawal, audit and outcome reconciliation.

**Finding R3-5 — Admission operation id is not explicitly unique/idempotent.**

- **Classification:** (a) Correctable design defect.
- **Severity:** High.
- **Business consequence:** Retries may create duplicate admission records for one operation, making the in-flight set and audit trail ambiguous.
- **Required correction:** Enforce a unique key on `(grant_id, operation_id)` (or an equivalently strong globally unique operation id), define same-id retry behaviour, and reject reuse of the same id for a different operation/request scope.
- **Principal decision:** Not required.

### 3D. Connection failure after admission commit but before completion

R7-4 defines failure during the admission transaction: no commit means refusal. It does not define the different case where the admission transaction has committed but the connection fails before the caller receives confirmation or before the operation has a recorded outcome.

In that case, the caller may not know whether admission committed, and the operation may or may not have reached the protected data. It must not be retried as a fresh operation merely because the response was lost. The design needs to define how the durable admission row and the existing `attempted`-without-outcome reconciliation rule work together.

**Finding R3-6 — Post-commit connection failure has no complete outcome/retry rule.**

- **Classification:** (a) Correctable design defect.
- **Severity:** High.
- **Business consequence:** A retry may duplicate an operation, or the system may incorrectly report that no access occurred when access may have occurred.
- **Required correction:** Define the durable admission lifecycle and its relationship to the central access record. After an ambiguous post-commit failure, the same operation id must be reconciled, never silently re-admitted as a new operation. If execution may have started but no conclusive outcome exists, report “access may have occurred”, consistent with the existing conservative F-05/R7-5 rule. Do not claim exactly-once execution without a mechanism that proves it.
- **Proof obligation:** Add failure injection at (i) after local admission commit but before acknowledgement, (ii) after execution begins but before outcome persistence, and (iii) during retry/reconciliation.

### 3E. Can an admitted operation run past its deadline?

R7-4 says an admitted operation may complete up to `min(expiry, operation limit)` (line 875). R6-4 defines the grant expiry deadline and requires no result after expiry (lines 753–757). But the supplied Platform design does not define an `operation limit` for protected operations; the only other use of “limit” found in the design concerns notification retries, not operation duration.

The phrase “up to their own deadline” is therefore incomplete. The design must specify the maximum operation duration, how its deadline is established, and how it is enforced in both the live-database and backup-copy paths.

An operation must not deliver a result after the grant expiry or its operation deadline. If it cannot be stopped safely at the deadline, the design must state what is guaranteed (at minimum, no result is returned after the deadline) and how the remaining work is terminated or isolated. The existing R6-4 statement about statement timeout and gateway abort is useful but does not define the missing operation limit for R7-4.

**Finding R3-7 — `operation limit` is undefined and deadline enforcement is incomplete.**

- **Classification:** (a) Correctable design defect.
- **Severity:** High.
- **Business consequence:** The maximum period for which an admitted operation may continue after withdrawal or towards expiry is not determinable or consistently enforceable.
- **Required correction:** Define the operation-duration limit and its source/configuration, calculate the deadline as the minimum of grant expiry and that limit, and specify enforcement/termination and result suppression for both case types.
- **Principal decision:** Not required unless the business chooses the actual maximum duration. The tactical design must identify the policy value or explicitly raise that value as a Principal question rather than refer to an undefined limit.

---

## Question 4 — R7-5 and file 03: concurrency, failure and recovery proof obligations

**Status: PARTIAL**

**References:** Platform design R6-5 (lines 759–783), R7-1 (lines 829–839), R7-3 (lines 847–864), R7-4 (lines 866–876), R7-5 (lines 878–894); proof register file 03, sections A and B.

### 4A. Is file 03 presented as executed or resolved?

**No. The register is correctly represented.**

File 03 explicitly says:
- no proof obligation has been executed;
- no code, test or database exists for the Platform context;
- every entry is NOT EXECUTED [TU];
- none is resolved merely by being specified;
- each is a build gate requiring tests against real transactions on the target database.

Every row in sections A and B is marked NOT EXECUTED. This review confirms that no item in the register may be described as passed, executed or resolved.

The register correctly includes the revised row 3b, new row 3d, new row 9, the `Undetermined` evidence outcome, and the attempted-without-outcome rule. Its B-1 through B-10 entries also preserve the stated session, notification, capture, lifecycle, bootstrap, failure-scenario and INV-ACCESS-1 obligations.

### 4B. Are the concurrency cases adequately specified?

The listed matrix is a good baseline, but it is not yet complete for revision 7. The following additional proof cases are required.

1. **Recovery recognition vs admission, before outbox commit.** Race an operation admission against the transition that declares the organisation database able to answer and moves the grant to handover. Prove that no operation can be admitted under the central grant after the handover transition linearises. Current row 3d starts at “between outbox record and copy arrival” and does not test the earlier recovery-recognition/outbox-commit gap.
2. **Platform withdrawal during handover followed by delayed copy delivery.** Withdraw the grant after outbox creation but before copy arrival; then deliver/retry the older copy. Prove the copy remains withdrawn and never restores access.
3. **Per-designation AuthorityCheck binding.** Test multiple contact designations in one decision, different outcomes for different contacts, mismatched subject/request references, wrong decision part, wrong rule version and replay of one contact's `Authorised` result against another contact.
4. **Admission operation idempotency.** Submit the same operation id concurrently and sequentially; prove one admission only. Reuse the same id with a different request/scope and prove refusal.
5. **Post-admission commit failure.** Lose the response after the admission row commits; separately interrupt execution after it may have begun; prove retries do not execute a second operation and reconciliation reports “access may have occurred” when that is the only safe conclusion.
6. **Operation deadline.** Prove that an operation admitted just before withdrawal/expiry is bounded by the defined operation deadline and grant expiry, that it cannot return a result after either deadline, and that the backup-copy path terminates or isolates work consistently.
7. **Missing-copy insert race.** While admission checks for a copy row and finds none, concurrently insert/deliver the row. Prove the current request is refused and only a new request can be admitted after a fresh check.
8. **Invalid-designation grant consequence.** Once the Principal has answered R3-2, prove that grants associated with `NotAuthorised` and `Undetermined` designations follow the selected remain-in-force or suspend rule, without promoting the invalid contact.

**Finding R3-8 — Revision 7 introduces proof cases not represented in the register.**

- **Classification:** (b) Remaining proof obligation.
- **Severity:** High.
- **Business consequence:** A design may pass the currently listed tests while still admitting access during the recovery race, resurrecting a withdrawn grant, replaying authority evidence, duplicating an operation or mishandling an ambiguous operation outcome.
- **Required correction:** Extend the proof register with the cases above. They must remain NOT EXECUTED [TU] until implemented and run against real transactions on the target database.
- **Principal decision:** Only the grant suspension rule in test 8 depends on R3-2. The other proof cases are technical obligations.

### 4C. Are any current tests insufficient?

Yes:

- **Row 3d** is insufficient because “operation between outbox record and copy arrival” does not cover recovery recognition before the outbox transaction commits, and its platform-withdrawal test does not require delayed delivery of a stale copy after withdrawal.
- **Row 9** is insufficient to prove subject-specific AuthorityCheck binding. It tests the three outcome categories but not that each result belongs to the exact individual designation and cannot be replayed.
- **Row 3b** does not test duplicate operation ids, post-admission commit/acknowledgement failure, or the undefined operation deadline.
- **B-1** is useful but should be explicitly connected to the new post-admission failure path; the test must distinguish failure before admission commit from failure after admission commit and from failure after execution may have begun.

The other listed concurrency cases remain required and unexecuted. Their presence in the register is not evidence that the implementation will satisfy them.

---

## Question 5 — New high-severity contradictions with accepted decisions or ISO-1 … ISO-11

**Status: NEW**

### 5A. D-187: handover refusal is not fully enforced at the recovery transition

D-187 requires every protected operation to be refused once the organisation database can answer again and copying of the grant has begun, until the organisation's copy is present and checked. R7-3 implements refusal after the outbox record exists but does not establish atomicity/serialisation between recovery recognition and that marker.

This is a **high-severity implementation contradiction/gap against D-187's required behaviour**: the design's “no outbox record” branch can still authorise under the Platform row unless recovery recognition and admission are coordinated. It is not a reason to reopen D-187. It is a defect in the tactical implementation of D-187.

**Classification:** (a) Correctable design defect.  
**Severity:** Critical.  
**Reference:** R7-3 lines 849–859; D-187, file 05 §11.86.

### 5B. D-187: platform withdrawal must remain effective after delayed copy delivery

D-187 expressly preserves immediate platform withdrawal throughout handover. R7-3 repeats that rule, but the copy-delivery rule does not say how a withdrawal committed after outbox creation is reflected in a copy delivered later. A stale active copy could conflict with the requirement that platform withdrawal remains immediate.

**Classification:** (a) Correctable design defect.  
**Severity:** Critical.  
**Reference:** R7-3 lines 861–864; R7-5 row 3d, line 883; D-187, file 05 §11.86.

### 5C. Other accepted decisions and multi-database invariants

No additional high-severity contradiction with D-164, D-166, D-170, D-173 … D-186, ISO-1 … ISO-11 or INV-ACCESS-1 is established by the supplied artifacts.

In particular:

- R7-4's organisation-side admission/withdrawal transaction is local to the organisation database. It does not itself create a distributed atomic transaction across the central and organisation databases.
- The outbox/copy handover remains a central transaction followed by a separate idempotent organisation-side step, consistent with the multi-database design's separation of database transactions.
- The case-grant requirement for operator access remains consistent with ISO-8 and ISO-11, provided the operation continues to be gated by the grant, operator, case scope and organisation context.
- ISO-1 … ISO-7 tenant isolation rules and ISO-9 … ISO-10 event/logging rules are not contradicted by the R7 changes as specified. Their proof obligations remain outstanding.

This is not a claim that those invariants have been proven. The multi-database design's own isolation and INV-ACCESS-1 release criteria remain build gates.

---

## 4. Findings register

| ID | Finding | Classification | Severity | Principal decision? |
|---|---|---|---|---|
| R3-1 | Individual designation AuthorityCheck evidence is not explicitly bound and non-reusable | (a) Correctable design defect | High | No |
| R3-2 | Effect on already-given grants when a participating designation fails validation is unspecified | (c) Genuine Principal question | High | Yes |
| R3-3 | Recovery-to-handover transition is not serialised with admission | (a) Correctable design defect | Critical | No; D-187 governs |
| R3-4 | Delayed copy delivery could undo platform withdrawal during handover | (a) Correctable design defect | Critical | No; D-187 governs |
| R3-5 | Admission operation id is not explicitly unique/idempotent | (a) Correctable design defect | High | No |
| R3-6 | Connection failure after admission commit has no complete retry/outcome rule | (a) Correctable design defect | High | No |
| R3-7 | `operation limit` is undefined and deadline enforcement incomplete | (a) Correctable design defect | High | Only the policy value may need a Principal choice |
| R3-8 | New concurrency, failure and recovery proof cases are absent or insufficiently represented | (b) Remaining proof obligation | High | No, except R3-2 consequence |
| Missing-copy row lock | Absence means refusal; concurrent insertion can only cause a spurious refusal if the current request terminates | (d) Already resolved by R7-3 fail-closed rule | — | No |
| D-186 revalidation | Both approvers current at second approval; already-given grant not undone by later removal | (d) Already resolved by D-186 and R7-2 | — | No |
| D-187 handover pause | No protected operation until organisation copy present and checked | (d) Already resolved by D-187; R7-3 enforcement defect remains | — | No |
| Governance `Undetermined` | Does not establish authority; no promotion | (d) Already resolved by D-181 and R7-1 | — | No |
| D-183 approver eligibility | Only organisation-recorded contacts approve | (d) Already resolved by D-183 | — | No |
| D-184 precedence | Cited decision date and recovery precedence | (d) Already resolved by D-184 | — | No |
| D-185 lifecycle | `pending` before `provisioning`; no technical operation before required verification/grant | (d) Already resolved by D-185 | — | No |

---

## 5. Exact Principal question remaining

### P-R3-1 — Status of grants after a participating contact's designation fails Governance validation

When recovery validation returns `NotAuthorised` or `Undetermined` for a contact designation, R7-1 removes the contact from the organisation-recorded contact list and flags grants given while that designation stood.

Should those already-given grants:

- **A — Remain in force** until withdrawn or expired, with the grant and designation flagged/reported; or
- **B — Be suspended** on reconciliation, with no further access until the organisation authorises or reissues the grant?

D-176, D-181, D-186 and D-187 do not answer this. The Principal must decide; the reviewer does not decide it.

---

## 6. Verdict

# NOT READY

Revision 7 is not ready for Principal acceptance in its current form.

The verdict is driven by correctable design defects, not by a need to reopen accepted decisions:

1. Individual contact-designation AuthorityCheck evidence must be explicitly bound to each designated person and exact decision part/request.
2. Recovery recognition, per-grant handover state and outbox creation must be serialised against admission.
3. A delayed copy must not restore a grant withdrawn on the Platform during handover.
4. Backup-copy admission needs unique operation identity, a post-commit failure/retry rule and a defined operation deadline.
5. The proof-obligation register must be extended for the newly identified races and failure cases.

In addition, one genuine Principal decision remains: whether a grant already given with the participation of a contact whose designation later fails Governance validation remains in force or is suspended.

**Acceptance of the design is distinct from readiness to build.** Even after the design defects are corrected and the Principal answers the outstanding question, every proof obligation remains a build/release gate. File 03 correctly records that none has been executed. No code, test or database evidence is supplied, and no implementation is authorised by this review.

---

## 7. Reviewer identity and prior involvement

**Reviewer:** GPT-6 in ChatGPT.  
**Commissioning authority:** The Principal (D-94).  
**Prior involvement:** Author of IR-P-02, IR-P-02-R and IR-P-02-R2. This review is therefore not blind; it is a focused independent re-review of revision 7 against the commission, the previous report, the accepted decision record and the supplied architecture contracts.

The manifest and subject fingerprint were verified. No supplied file was modified. No accepted decision was reopened. No decision was made for the Principal. No acceptance is claimed.

**End of report.**
