<!-- Recorder note (Claude, 2026-11-11):
- What follows: GPT-6's IR-P-02-R6 formal report, as pasted by the Principal; extracted verbatim from the session transcript; body unaltered.
- GPT-6's chat summary is appended for provenance. GPT-6's own saved file (426 lines) was not received. -->

# IR-P-02-R6 — Focused Re-Review: Platform Revision 10

| | |
|---|---|
| **Reviewer** | GPT-6 in ChatGPT (OpenAI) |
| **Review date** | 2026-11-11 |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, revision 10 |
| **Subject SHA-256** | `30ecb53eb9d2acd526ef55ce743cbbd320e89d1071ab691ce88c98c179f54aeb` |
| **Proof register** | `2026-11-11-platform-proof-obligation-register.md`, SHA-256 `6711114e57a0bdb17fe8090380c5556799aacedc773176454f1c9366ad0161ea` |
| **Status** | Review only. Revision 10 remains proposed and not accepted. No implementation is authorised by this report. |

## 1. Integrity and review method

I opened the supplied `ir-p-02-r6-review-handover.zip` and recalculated the checksums from the extracted contents.

| Check | Result |
|---|---|
| ZIP SHA-256 | `217ba1b3e2b85e8ad6242b2d3b4ba96eae192a1f7eea73180385489839be7df5` — matches |
| `MANIFEST.sha256` | 26/26 files match |
| Subject revision 10 | SHA-256 matches the commissioned fingerprint |
| Proof register | SHA-256 matches the commissioned fingerprint |
| Proof obligations | 47 entries: A 14, B 10, C 9, D 6, E 8 |
| Duplicate identifiers | None |
| Execution status | All 47 remain `NOT EXECUTED`; none is execution evidence |

I read file 01 (the commission) first and treated its reproduced Principal commission (§§4–8) as governing. I reviewed the actual revision 10 source (file 02), not the author's change trace (file 04). I used file 14 as the binding decision record, file 18 as the accepted Governance thin slice, file 15 as the accepted multi-database design, file 16 as the strategic baseline acceptance act, and the supplied Identity sources to establish the external contract status. Historical reports were used to map earlier findings, not as substitutes for the current sources.

This is not a blind review. I previously authored IR-P-02 and IR-P-02-R through R5. The revision 10 author is Claude. I have not treated the author's assertions or change trace as evidence of resolution.

**Line references below refer to the supplied revision 10 subject unless a different file is named.**

## 2. Executive conclusion

# NOT READY

Revision 10 makes material improvements. In particular:

- R10-2's central contact metadata list is consistent with D-191 and the earlier D-174/D-180 fields.
- R10-3 specifies an atomic, monotonic organisation-inbox application algorithm.
- R10-4 fixes the original timer-start gap for the initial admission path.
- R10-5 introduces a defined response to unconfirmed termination rather than treating gateway timeout as proof that work stopped.
- R10-6 substantially improves per-operation reporting and report durability.
- R10-7 describes the previously missing registry-only retry path.
- The proof register is correctly re-stamped for revision 10 and retains all 47 obligations as unexecuted.

However, revision 10 still contains material design defects and unresolved external contracts:

1. **R10-1 does not fully map the two AuthorityCheck act kinds to the accepted six-field-plus-basis contract.** In particular, actor/capacity semantics are not specified for the two different act shapes. The proposed per-Resolution-part persistence of `ruleRef` and `policyVersion` is not established by the accepted Governance sources and is not authorised merely by D-190.
2. **R10-4's failover formula does not subtract the elapsed monotonic time between the pre-request bracket and receipt/dispatch.** The stated `D − t_now` can therefore overstate the remaining duration.
3. **R10-5's fallback does not demonstrate that an already-running backend operation stops if the backend ignores both cancellation and termination.** Revoking login and closing the gateway network path do not, by themselves, prove that server-side work already executing has ceased.
4. **R10-6 omits fields explicitly required by the commission:** an explicit grant identifier, a distinct outcome, and an explicit per-operation relationship to the failed designation and suspended grant.
5. **R10-7 leaves an unresolved race between admission and abandonment.** It does not specify atomic arbitration between a retry/admission attempt and the reconciler changing `registered` to `abandoned`. The abandonment window is also unspecified.
6. **The confirmation window in R10-5 is unspecified.** The design cannot be implemented or tested deterministically until its value and timing semantics are defined.
7. **X-1, X-2 and X-3 remain genuine external acceptance dependencies.** X-1 is not satisfied by D-190 alone; X-2 has no accepted Identity operation; X-3's DecisionLog semantics and the per-part evidence persistence rule remain unaccepted.

These are not all separable external conditions. The failover, containment, report-schema and registry-lifecycle issues are defects or incompleteness within the Platform design itself. Therefore **READY WITH EXPLICIT EXTERNAL CONDITIONS** is not appropriate.

## 3. Detailed findings

### F-01 — R10-1: AuthorityCheck addition is not yet a complete contract mapping

**Classification:** [a] Design defect; [EXT] Governance acceptance dependency; [UNKNOWN] for per-part persistence semantics.

**Location:** Subject §§R10-1, lines 1170–1183. Relevant accepted sources: decision record file 14 §11.89 (D-190); Governance thin slice file 18 §§6.1–6.2 and correction notes; AuthorityCheck review file 19 §§2–5.

**Evidence and assessment**

1. **D-190 does authorise the two act kinds.** The binding wording authorises `designate-platform-contacts` and `give-case-grant`, with the latter limited to a case grant given through the organisation's normal route while its database works. Both are answered from the organisation's own rules on the decision date. Rules-silent outcomes are `Undetermined`; Platform remains a consumer and creates no authority. (File 14 §11.89.)
2. **The designation subject is correctly narrowed to one designation.** `(Resolution, part, designated PersonRef)` is consistent with D-190's one-designation subject and the accepted `requestRef` rule in Governance correction note 4.
3. **The request identifier and `evaluatedFor` are directionally correct.** A consumer's single-use request identifier is consistent with correction note 4's replay protection. The Resolution decision date is consistent with D-12 and the accepted basis field `evaluatedFor`.
4. **The draft does not complete the actor/capacity mapping.** The accepted AuthorityCheck review (file 19 §3 and §5) distinguishes:
   - a committee decision: `actor` is the organ and `capacity` is the organ/sitting;
   - an office-holder act: `actor` is the acting `PersonRef` and `capacity` is the relevant tenure.

   R10-1 identifies the competent body or office in a table column, but it does not say which actor and capacity are carried in the answer for either act kind. The designated PersonRef is the subject of the designation; it must not be confused with the actor who made the Resolution. For `give-case-grant`, the grant-giving person and their capacity must be represented, not merely the class of body or office that may be competent.
5. **The rule-silent outcome is correct.** `Undetermined` until a competent body is designated is consistent with D-167 and D-181. The consequence that an organisation with silent rules must make the designation at joining, otherwise it has no platform contacts and no emergency route, is already stated by D-190. This is not a question to return to the Principal.
6. **The emergency route is correctly kept separate.** D-190 is limited to the normal `give-case-grant` route. R10-1 correctly says emergency grants under D-174/D-176 are not evaluated using `give-case-grant`; the approvers' designations are checked at recovery under D-183/D-188.
7. **The draft correctly says six fields plus basis, but does not cure the actor/capacity omission by naming the shape.** The accepted AuthorityCheck correction material provides the shape; the addition still needs to map the values for each new act kind.
8. **`ruleRef` and `policyVersion` require a separate conclusion.** The accepted Governance sources establish that:
   - `RuleProvision` contains rules documents and versions;
   - a `Resolution` has parts, with a competence evaluation per part;
   - Resolutions are immutable and competence is evaluated on the decision date;
   - `ruleRef` and `policyVersion` are fields in the accepted authority-evidence shape.

   They do **not**, in the supplied accepted thin slice, expressly establish the exact persistence rule that each Resolution part permanently stores the specific `ruleRef` and `policyVersion` used for that part's competence evaluation. The detailed DecisionLog rules GD-11/GD-12 are in proposed file 20. The R10-1 sentence is therefore an additional Governance persistence semantic, not something that D-190 automatically authorises. D-190 authorises the two act kinds; it does not itself settle GD-11/GD-12 or the M-4 persistence rule.
9. **The draft's X-3 statement is too strong.** R10-9 says the per-part values are closed by X-1 if ratified. That is not supported. X-1 can ratify the two act kinds and the contents expressly included in the Governance addition. It cannot silently ratify a persistence rule that D-190 did not decide and the accepted Governance model does not yet establish.

**Failure scenario:** Governance returns an AuthorityCheck answer for a designation or normal case grant, but Platform cannot determine whether the actor/capacity fields represent the actual decision-maker or whether the cited rule/version was the immutable per-part evaluation. A replayed or incorrectly attributed answer could be treated as authority evidence without the intended traceability.

**Consequence:** Platform would consume an incompletely specified authority contract and could preserve a rule citation/version that Governance has not committed to persist in that form. This risks Platform inventing or assuming Governance semantics.

**Minimum correction:**
- Amend the proposed Governance addition to map `actor` and `capacity` separately for each act kind:
  - designation by Resolution: actor = deciding organ; capacity = the relevant sitting/Resolution context; designated PersonRef remains the subject;
  - normal case grant: actor = grant-giving PersonRef; capacity = the competent office/tenure or organ/sitting appropriate to the actual act.
- State the exact evidence/basis mapping for each act kind without creating a second AuthorityCheck schema.
- Keep the per-part `ruleRef`/`policyVersion` persistence rule explicitly open under X-3 until Governance accepts it. Do not claim D-190 alone closes it.
- Ratify the corrected addition into the Governance thin slice together with Platform acceptance, as D-190 requires.

### F-02 — R10-2: D-191 central contact metadata

**Classification:** [d] Design conforms to the accepted decision; [b] implementation proof remains.

**Location:** Subject §§R10-2, lines 1185–1196. Binding source: file 14 §11.90 (D-191), read with D-174, D-180 and D-184.

**Evidence and assessment**

R10-2 lists:

- Account reference;
- `source` (`organisation-record` or `direct`);
- cited decision reference and date;
- document digests;
- sender;
- validation outcome and evidence reference;
- `grantableFrom`.

This is consistent with the exact D-191 additions: cited decision reference/date, document digests (not documents), sender, validation outcome and evidence reference. The Account reference and source are part of the existing D-174/D-176 contact model; `grantableFrom` reflects D-180's 24-hour safeguard. The design expressly excludes decision texts, minutes and rules. It also states that the organisation's own records replace the central entries on recovery, consistent with D-176 and D-184.

I find no additional central Organisation business content in the R10-2 field list. D-191 does not authorise any wider central data allowance, and R10-2 does not expressly claim one.

**Failure scenario:** An implementation adds document contents, minutes, rules or unrelated Organisation records to the central contact entry, or treats outage metadata as the permanent authority source after recovery.

**Consequence:** It would exceed D-191 and violate the ownership/minimisation boundary.

**Minimum correction:** No design-text correction is required for the listed fields. Add implementation proof that the central schema is an allow-list of exactly the permitted fields and that recovery replaces the outage metadata with the Organisation's authoritative records. Keep that proof unexecuted until tested.

**R5-10 mapping:** Resolved at design level by D-191/R10-2 [d]. Implementation and recovery proof remain [b].

### F-03 — R10-3: Atomic Organisation inbox compare-and-apply

**Classification:** [d] Design algorithm is specified; [b] database/concurrency proof remains.

**Location:** Subject §§R10-3, lines 1198–1206; earlier governing mechanics at §§R8-3, R9-2 and R7-4.

**Evidence and assessment**

R10-3 places event-id deduplication, row creation if absent, row locking, version comparison, terminal-state protection and update in one Organisation-database transaction. It uses the same grant-copy row that admission locks `FOR SHARE`, while delivery takes `FOR UPDATE`. The unique key and insert-then-lock sequence address the absent-row race. A lower incoming version is ignored; a terminal copy cannot be replaced; a terminal incoming state can replace a non-terminal copy when its version is higher.

The algorithm is materially sufficient at the specification level. It does not rely on a bare status read. The existing global order remains `ServedOrganisation` → `PendingCaseGrant` → Organisation grant copy; the inbox transaction does not acquire the central locks in reverse order.

**Failure scenario:** Two deliveries race with versions 5 and 6, or delivery races admission/withdrawal. If the implementation does not use the same row and transaction boundary, an older state could overwrite a newer state or admission could use a state inconsistent with the copy.

**Consequence:** Stale active copies or reopened terminal grants could permit an operation that should be refused.

**Minimum correction:** No additional design correction is presently required. Execute E-1 and the carried-forward C-2/C-9/D-5 tests against the target database, including both delivery orders, duplicate event ids, missing-copy insertion races, terminal-state permutations and delivery/admission serialization. All remain NOT EXECUTED.

**R5-04 mapping:** Resolved at design level [d]; not proven in implementation [b].

### F-04 — R10-4: Failover recomputation can overstate remaining time

**Classification:** [a] Design defect.

**Location:** Subject §R10-4, lines 1208–1217, particularly line 1216. Governing deadline: D-189, file 14 §11.88.

**Evidence and assessment**

For the original admission path, R10-4 takes monotonic reading `m0` before sending the admission request and computes:

`remaining = (D − t_admit) − (monotonic now − m0)`

This is conservative because `m0` precedes the authoritative admission time. It accounts for the time spent in the admission round trip and subsequent scheduling up to the calculation.

The failover path is not equivalently specified. It says the new instance reads `t_now` from the central clock, with a monotonic reading taken before the request, but gives the recomputation as:

`remaining = D − t_now`

That formula does not subtract the elapsed monotonic time from the pre-request reading to receipt of the clock value/dispatch. Merely saying that the clock read is bracketed does not account for the bracket's elapsed time. A delayed central-clock request or scheduling delay can therefore cause the new gateway to set a timeout that expires after D.

The server-side timeout does not cure this if the timeout duration itself was calculated from a stale `D − t_now` value and starts after the omitted delay.

**Failure scenario:** A gateway restarts near the deadline. The new instance reads `t_now`, the central call or scheduling is delayed, and it dispatches using the unadjusted `D − t_now`. The server receives a duration greater than the true remaining time.

**Consequence:** The effective end of the operation can exceed the D-189 deadline. That violates the accepted maximum-duration rule and can permit access beyond the grant's effective deadline.

**Minimum correction:** Define the failover calculation explicitly using two monotonic readings around the central-clock request, for example:

`remaining = (D − t_now) − (m_after − m_before)`

where `m_before` is taken before the request that obtains `t_now` and `m_after` is taken after the response, with any subsequent time to dispatch also deducted. Specify how the value is propagated so that every delay between clock read and actual enforcement is conservatively accounted for. Refuse dispatch if the conservative remaining duration is non-positive or cannot be established. Extend E-4 to prove this exact calculation under injected request and scheduling delays.

**R5-05 mapping:** The original timer-start gap is corrected for the initial path, but the failover case remains defective [a].

### F-05 — R10-5: Unconfirmed termination fallback does not prove backend work has stopped

**Classification:** [a] Design defect; [TU] backend-specific termination and isolation behaviour; [b] proof obligation.

**Location:** Subject §R10-5, lines 1219–1231; related R9-4 lines 1111–1120; multi-database design file 15 §16 M-6 and ISO-11.

**Evidence and assessment**

R10-5 improves the prior design by defining positive confirmation as the tagged session no longer appearing in the Organisation database's session list after cancel and terminate. If confirmation does not arrive within a window, it:

1. revokes `LOGIN`/`CONNECT` from the grant's role;
2. terminates every session of that role;
3. closes the grant's network path from the gateway;
4. marks the grant `contained`, terminal for admission and reported;
5. records `termination-unconfirmed` and "access may have occurred";
6. sends an incident report.

This is a useful containment response and correctly avoids claiming that the gateway timer alone proves termination. However, the commission specifically asks whether the controls prevent continued access if the backend ignores cancellation or termination.

**They do not yet establish that guarantee.** Revoking login prevents new logins; it does not necessarily terminate an existing backend session. A second terminate attempt against a backend that ignores termination is not a demonstrated stop. Closing the gateway's network path can prevent further gateway communication, but the design does not establish that already-running server-side work is killed or fenced. A query may continue executing within the database after the client path is closed. The design also does not specify a fallback if the containment actions themselves cannot be confirmed.

The state `contained` is described as terminal for admission. It must not be interpreted as proof that the in-flight backend operation has stopped. The operation correctly remains `termination-unconfirmed`, but the design still needs a technically effective control for the continuing-work scenario.

The confirmation window is also a platform setting with no value. The design does not specify its duration, start point, clock, or exact boundary semantics. Without these, the behaviour cannot be implemented or tested deterministically.

**Failure scenario:** The database ignores both cancellation and termination. The session remains active and its query continues. The gateway revokes login and closes its own network path, but the backend operation continues running. The grant is marked `contained` even though the in-flight operation has not been shown to stop.

**Consequence:** The system may prevent new admissions and gateway-mediated communication but cannot claim that the existing access has been stopped. This is material under D-166 and D-189.

**Minimum correction:**
- Specify an enforceable backend-level or infrastructure-level fencing action that stops or isolates the already-running operation even when ordinary cancel/terminate is ignored (for example, an independently enforceable database-side session/process kill or an infrastructure isolation/destruction mechanism appropriate to the target deployment).
- Define the failure outcome if that stronger action cannot be confirmed. Preserve `termination-unconfirmed` and "access may have occurred"; do not equate `contained` with confirmed termination.
- Define the confirmation-window value/configuration, start event, clock, and boundary behaviour.
- Prove that session-list inspection is authoritative for the target database and that the containment action prevents further reads/writes, not merely new logins or gateway delivery.
- Extend E-5 to test a backend that ignores cancel and terminate and to demonstrate that the fallback actually stops or isolates the running work. Merely asserting that revoke/terminate/network-close commands were issued is insufficient.

**R5-06 / R5-12 mapping:** The explicit fallback is an improvement, but R5-06 is not fully resolved [a]. Whether the actual backend and isolation controls achieve the required result remains [TU], and E-5 remains unexecuted [b].

### F-06 — R10-6: D-188 per-operation report schema is incomplete

**Classification:** [a] Design defect; [b] report completeness and durability proof.

**Location:** Subject §R10-6, lines 1233–1249; commission §4 R10-6.

**Evidence and assessment**

R10-6 now defines operation identity, kind, operator, case type, admission time/deadline, lifecycle state, access completion, access uncertainty, termination status and audit references. It also defines completeness by enumerating all registry rows bound to each suspended grant, includes a count, and specifies versioned/idempotent inbox delivery with re-emission after interruption. D-179 notices are correctly restricted to security notices and do not carry report content.

However, the commission requires each affected operation to identify:

- Operation ID;
- **Grant ID**;
- operation status;
- **Outcome**;
- access completion;
- access-may-have-occurred;
- termination confirmation;
- audit references;
- relationship to the failed designation and suspended grant.

The R10-6 per-operation field table does not explicitly include `grantId`, a distinct `outcome`, or explicit references to the failed designation and suspended grant. The statement that the report lists every registry row "bound to each suspended grant" establishes enumeration, but does not define these required per-operation fields or their referential representation. Lifecycle state is useful, but the commission separately asks for status and outcome; they should not be left to an unstated interpretation.

**Failure scenario:** An Organisation receives a per-operation row with an operation ID and lifecycle state but cannot unambiguously identify the grant, the failed designation that caused suspension, or the operation's distinct outcome.

**Consequence:** The report may be complete as a count of registry rows but insufficient for the Organisation to reconstruct which authority failure affected which grant and what happened under it.

**Minimum correction:** Add explicit `grantId`, `outcome`, `failedDesignationRef` (or the exact decision/part/person/outcome tuple), and `suspendedGrantRef`/relationship to each per-operation record. Define outcome values separately from lifecycle state, and define how a `registered` operation represents fields such as `admittedAt` that do not yet exist. Preserve the existing count, registry enumeration, versioning, idempotency and D-179 restriction. Update E-6 to assert each required field and relationship, not merely "every field present".

**R5-07 mapping:** Substantially improved but not resolved [a] until the required per-operation fields are explicit. Durability and idempotency remain [b].

### F-07 — R10-7: Registry/admission/abandonment race and undefined abandonment window

**Classification:** [a] Design defect; [TU] operational parameter; [b] concurrency and failure-injection proof.

**Location:** Subject §R10-7, lines 1251–1262; register E-2 and E-3.

**Evidence and assessment**

R10-7 correctly preserves the global `operationId`, immutable binding and unique Organisation admission row. It handles the lost-acknowledgement case by looking up the Organisation admission row and reusing its stored deadline. If no row exists, it permits a fresh admission attempt with a new admission time. It also avoids an exactly-once claim and refuses the same id after abandonment.

The missing point is the race between:

- a retry that sees no Organisation admission and proceeds to admission; and
- reconciliation that sees a `registered` registry row with no admission after the abandonment window and marks it `abandoned`.

The design does not specify a central conditional state transition, lock, lease, fencing token or equivalent arbitration that makes `registered → admission-in-progress/admitted` mutually exclusive with `registered → abandoned`. The Organisation admission is in a separate database, so a simple cross-database transaction cannot be presumed. A unique Organisation admission key prevents a second admission row, but it does not by itself prevent the central registry from being marked abandoned while a valid admission attempt is in flight or from leaving registry and Organisation records in contradictory states.

The abandonment window is also unspecified. File 04 notes this; the subject itself gives no value or definition. It does not say when the window starts, which clock governs it, or whether an admission request already in flight at expiry is allowed to finish.

**Failure scenario:** Reconciliation marks a registry row abandoned while a same-binding retry is between its absence check and Organisation admission commit. The Organisation admission may then commit after the central operation has become abandoned, or a legitimate retry may be refused despite an admission attempt already in flight.

**Consequence:** The global operation state and Organisation admission state can disagree. A late or racing admission may be orphaned, or a valid retry may be denied. The lifecycle is not yet deterministic.

**Minimum correction:**
- Define an atomic central arbitration step for admission-attempt ownership versus abandonment, using a conditional transition/lease/fencing token or another explicitly specified mechanism.
- Define what the Organisation admission transaction must validate before commit and how a stale/abandoned attempt is refused.
- Define the abandonment window's value or configuration, unit, start event, authoritative clock, and boundary semantics.
- State how retries, reconciliation, worker restarts and late acknowledgements behave when the lease/window expires.
- Extend E-2 with the concurrent retry-versus-abandonment race and late-commit cases; keep E-3's restart durability proof.

**R5-08 mapping:** The previously absent registry-only failure path is now described, but the race and abandonment parameter remain design defects [a].

### F-08 — R10-8: Proof register integrity and coverage

**Classification:** [d] Register integrity; [b] unexecuted proof obligations and coverage amendments required.

**Location:** Subject §R10-8, lines 1264–1266; proof register file 03, sections A–E.

**Evidence and assessment**

The register fingerprint matches the subject. It contains 47 unique obligations: A 14, B 10, C 9, D 6, E 8. Sections A–D are carried forward and remain relevant to the revision 10 design. Section E adds eight revision 10 obligations. All 47 are marked NOT EXECUTED. E-7 is blocked on X-2; E-8 is blocked on X-1.

The register correctly avoids treating this review or a specification as execution evidence. The E-1 inbox concurrency, E-2 registry-only crash, E-3 restart durability, E-4 timer gap, E-5 live termination, E-6 report, E-7 Identity and E-8 Governance obligations map to the revision 10 areas.

However, the new defects and omissions identified in this report are not yet fully represented in the proof obligations:

- E-4 must test the exact failover formula and subtract the full monotonic bracket/dispatch elapsed time.
- E-5 must prove that an already-running operation is stopped or isolated when the backend ignores cancellation and termination, not merely that containment commands were issued.
- E-6 must assert the grant ID, distinct outcome and explicit designation/grant relationships.
- E-2 must test the admission-versus-abandonment race, including late commit/acknowledgement.
- The confirmation and abandonment windows need defined values/semantics before meaningful tests can be parameterised.
- E-8 is marked blocked only on X-1, but its per-part `ruleRef`/`policyVersion` assertion also depends on X-3 unless Governance explicitly establishes that persistence rule in an accepted contract. D-190 does not settle that question.

**Failure scenario:** A proof suite passes while omitting one of the identified failure paths, or E-8 is treated as executable even though the per-part evidence source is not accepted.

**Consequence:** The register would overstate proof coverage or permit an invalid test conclusion.

**Minimum correction:** Retain all 47 existing obligations and their NOT EXECUTED status. Add or refine the listed proof cases, mark E-8 blocked on both X-1 and X-3 as applicable, and keep every test unexecuted until run against the target database/backend with retained evidence.

**R5-09 mapping:** The proof register is correctly expanded and re-stamped [d], but coverage needs the additions above and all proof execution remains [b].

### F-09 — R10-9: External dependencies

**Classification:** [EXT] for all three dependencies; [UNKNOWN] where the supplied accepted sources do not establish the contract.

**Location:** Subject §R10-9, lines 1268–1276. Source statuses are stated in commission file 01 §§1 and 3.

#### X-1 — Governance addition under D-190

- **Owner:** Governance; acceptance/ratification process.
- **Authoritative sources:** D-190, decision record file 14 §11.89; accepted Governance thin slice file 18.
- **Current status:** D-190 authorises the two act kinds, but the addition note is not yet ratified into the thin slice. R10-1 is a draft only.
- **Exact missing contract:** a corrected addition note defining the two act kinds against the existing AuthorityCheck six fields plus basis, including actor/capacity mapping, followed by ratification with Platform acceptance.
- **Platform behaviour while unresolved:** no promotion of contact designations and no normal-route case-grant acceptance based on an unratified act-kind extension; fail closed.
- **Gate:** Governance contract acceptance condition, not a reason to invent a Platform-side authority model.

#### X-2 — Identity PersonRef-to-Account resolution

- **Owner:** Identity.
- **Authoritative sources:** accepted XD-3 act and records (files 21–24), D-113; proposed I-TDD-01 (file 25); I-TDD-02 scope proposal only (file 26).
- **Current status:** no accepted Identity operation/contract resolving an organisation's `PersonRef` to its bound Account is supplied. I-TDD-02 itself is not written.
- **Exact missing contract:** an accepted Identity operation that resolves the exact organisation `PersonRef` to its bound Account, with no email matching and with the required failure/uncertainty semantics.
- **Platform behaviour while unresolved:** R9-1 predicate 3 fails closed; no contact promotion and no emergency approver. The emergency route remains unavailable rather than guessing an Account.
- **Gate:** external Identity contract/build gate. Platform must consume the accepted operation; it must not implement Identity's resolution logic itself.

#### X-3 — GD-11/GD-12 and per-part evidence persistence

- **Owner:** Governance.
- **Authoritative sources:** accepted Governance thin slice file 18 §6.1 (DecisionLog and Resolution); proposed strategic context map/Governance tactical model file 20.
- **Current status:** the detailed GD-11/GD-12 semantics remain only in proposed file 20. The accepted thin slice establishes Resolution immutability and competence evaluation per part, but the supplied accepted material does not fully establish the exact persistence rule asserted in R10-1 for each part's `ruleRef` and `policyVersion`.
- **Exact missing contract:** accepted DecisionLog semantics and an explicit accepted rule for storing/retrieving the fixed rule citation and rule-document version for the relevant Resolution part/act.
- **Platform behaviour while unresolved:** Platform cannot assume the values are fixed and available from a Governance decision-log record. It must fail closed where its acceptance predicate cannot be established.
- **Gate:** external Governance contract/build gate. X-1 does not automatically close X-3.

## 4. Explicit mapping of IR-P-02-R5 findings

| R5 finding | Revision 10 assessment | Classification |
|---|---|---|
| **R5-01 — Governance act-kind extension** | D-190 now authorises the two act kinds. The addition note remains unratified and its actor/capacity mapping is incomplete. | [d] for D-190 authorisation; [a] for incomplete draft mapping; [EXT] X-1 |
| **R5-02 — PersonRef → Account** | No accepted Identity resolution operation exists in the supplied sources. R10-9 correctly fails closed. | [EXT] X-2; [UNKNOWN] until Identity contract exists |
| **R5-03 — GD-11/GD-12 and M-4** | Detailed DecisionLog rules remain proposed. Per-part `ruleRef`/`policyVersion` persistence is not established by D-190. | [EXT] X-3; [UNKNOWN] for exact persistence semantics |
| **R5-04 — atomic inbox update** | R10-3 specifies a single transaction, unique event id, row lock, monotonic version check and terminal-state protection. | [d] design; [b] proof |
| **R5-05 — timer-start gap** | Initial path corrected; failover formula omits elapsed time after the pre-request monotonic bracket. | [a] |
| **R5-06 — live termination and containment** | Fallback added, but it does not demonstrate that an already-running backend operation stops when the backend ignores cancel/terminate. | [a] and [TU] |
| **R5-07 — D-188 report content** | Improved, but grant ID, distinct outcome and explicit failed-designation/suspended-grant links are missing from the per-operation schema. | [a]; [b] durability proof |
| **R5-08 — registry-only failure lifecycle** | Retry and lost-acknowledgement cases are described; abandonment race and window remain unresolved. | [a]; [b] proof |
| **R5-09 — additional proofs** | Register has 47 unique obligations, all NOT EXECUTED; some new cases need expansion. | [d] register integrity; [b] execution/coverage |
| **R5-10 — central metadata conflict** | D-191 resolves the permitted metadata list; R10-2 conforms. | [d]; [b] implementation proof |
| **R5-11 — subject identifier / Identity integration** | `requestRef` mapping is present; PersonRef-to-Account operation remains external and unaccepted. | [d] for requestRef shape; [EXT] X-2; [TU]/[b] proof |
| **R5-12 — backend termination/isolation guarantees** | R10-5 defines actions but not a demonstrated hard stop under backend refusal. | [a] and [TU]; [b] proof |
| **R5-13 — earlier findings/proofs** | Earlier concurrency, expiry, lifecycle, withdrawal, backup and isolation obligations remain carried forward in A–D. They remain unexecuted. | [d] carried forward; [b] proof |

## 5. Additional architectural checks

### 5.1 Accepted decisions and ownership boundaries

| Decision / boundary | Assessment |
|---|---|
| **D-164 — separate database per Organisation** | No revision 10 change undermines the separate-database boundary. Organisation inbox/copy operations remain inside the relevant Organisation database. |
| **D-166 — technical operations do not read content; content access needs a case grant** | The design preserves the distinction. R10-5's termination weakness remains a material enforcement defect, not a change in ownership. |
| **D-167 / D-181 — no inferred authority; silence is Undetermined** | R10-1 states the correct rule-silent outcome. Its actual Governance contract remains subject to X-1. |
| **D-170 — emergency pending grant metadata only** | R10-2 and the existing pending-grant model remain metadata-oriented; no business content is added centrally. |
| **D-173 — accepted strategic baseline** | Platform ownership remains within the accepted registry/locator/status/service-grant/pending-grant boundary. Tactical acceptance remains separate. |
| **D-174–D-188 — contacts, emergency grants, recovery, suspension and reporting** | R10-1 correctly separates normal and emergency routes. R10-3 preserves versioned copy application. R10-5/R10-6 require the corrections identified above to make termination and reporting adequate. |
| **D-189 — maximum operation duration** | Initial timer path is improved, but failover recomputation still permits an unaccounted interval unless corrected. |
| **D-190 — AuthorityCheck act kinds** | Authorised, not yet ratified into Governance. It does not itself authorise all adjacent persistence semantics. |
| **D-191 — central contact metadata** | R10-2 conforms to the exact metadata allowance and recovery replacement rule. |

### 5.2 Bounded-context ownership

The design continues to respect the strategic ownership model:

- Governance owns organisational authority and its decisions.
- Organisation owns its business records and organisational decisions.
- Platform owns the registry, locator, platform status, service-operation grant records and permitted pending case-grant metadata.
- Identity owns Person, Account, authentication and identity bindings.
- Membership owns membership facts.
- Finance owns Finance facts.

No revision 10 provision authorises Platform to become a general Organisation administration context or to read Organisation content outside a case grant. The identified AuthorityCheck and Identity gaps must remain external dependencies rather than being solved inside Platform.

## 6. Four gates — kept separate

1. **Tactical design coherence:** **Not ready.** R10-1, R10-4, R10-5, R10-6 and R10-7 have the defects/incompleteness identified above.
2. **Governance and Identity contract acceptance:** **Not complete.** X-1 is unratified; X-2 does not exist as an accepted operation; X-3 remains proposed/undetermined.
3. **Implementation authorisation:** **Not granted.** This review grants no authority to implement and changes no Principal decision.
4. **Proof execution and release readiness:** **Not ready.** All 47 proof obligations remain NOT EXECUTED. The required tests must run against the target database/backend and retain evidence before release.

## 7. Required corrections before another focused review

1. Correct R10-1's actor/capacity mapping for both act kinds and keep per-part `ruleRef`/`policyVersion` persistence under X-3 unless separately accepted by Governance.
2. Correct R10-4's failover formula to subtract the full monotonic interval from the pre-request clock-read bracket through dispatch.
3. Strengthen R10-5 with an enforceable backend/infrastructure stop or isolation mechanism for already-running work when cancellation and termination are ignored; define the confirmation window.
4. Complete R10-6's per-operation report schema with grant ID, distinct outcome and explicit failed-designation/suspended-grant relationships.
5. Define atomic arbitration between admission attempts and abandonment in R10-7; define the abandonment window and its clock/boundary semantics.
6. Update the relevant proof obligations without deleting any of the 47 existing obligations; keep all tests NOT EXECUTED until actually run.
7. Complete X-1, X-2 and X-3 in their owning contexts. Do not solve them in Platform.

## 8. Final verdict

# NOT READY

Revision 10 is not ready for Principal consideration in its current form. The remaining Platform design defects are material and cannot be treated merely as separable external conditions. The external Governance and Identity dependencies also remain open.

This verdict does not reopen D-164 through D-191, does not decide anything for the Principal, and does not authorise implementation. D-190 and D-191 are applied exactly within their recorded scope. No additional Principal question is raised where the decision record already supplies the answer.

## 9. Reviewer identity

**Reviewer:** GPT-6 in ChatGPT (OpenAI).  
**Prior involvement:** Author of IR-P-02 and IR-P-02-R through IR-P-02-R5. This is a focused re-review, not a blind review.  
**Review completed:** 2026-11-11.

**End of report.**

---

## Appendix (provenance): GPT-6's chat summary, as pasted

# IR-P-02-R6 — Focused Re-Review: Platform Revision 10

Reviewer: GPT-6 (OpenAI)\
Review date: 11 November 2026\
Subject: PublicDigit Platform (tenancy) tactical design, revision 10.



Integrity verification passed

Review verdict pending



## 1. Integrity and source verification

I have opened the uploaded ZIP and inspected its contents.

| Verification                | Result                                                                     |
| --------------------------- | -------------------------------------------------------------------------- |
| ZIP SHA-256                 | Matches `217ba1b3e2b85e8ad6242b2d3b4ba96eae192a1f7eea73180385489839be7df5` |
| Manifest entries            | 26 of 26 verified                                                          |
| Subject revision            | Revision 10                                                                |
| Subject SHA-256             | Matches `30ecb53eb9d2acd526ef55ce743cbbd320e89d1071ab691ce88c98c179f54aeb` |
| Proof register SHA-256      | Matches `6711114e57a0bdb17fe8090380c5556799aacedc773176454f1c9366ad0161ea` |
| Proof obligations           | 47, all NOT EXECUTED                                                       |
| Duplicate proof identifiers | None                                                                       |

I read the Principal's commission first, including its governing sections 4–8. I distinguish the accepted decision record, the authorised but unratified Governance addition, the proposed tactical design and unexecuted proof obligations.

The revision 10 subject and proof register match the supplied fingerprints. The author's change trace is treated only as a navigation aid, not as evidence.

Important: This is not a blind review. I previously authored IR-P-02 and the R-series reviews. I have therefore assessed revision 10 against the supplied primary sources rather than relying on my earlier conclusions.

## 3. Final review decision

Independent review verdict

NOT READY

Revision 10 requires further corrections before Principal consideration.

The principal blockers are:

1. Governance AuthorityCheck mapping and ratification.
2. Identity PersonRef-to-Account contract.
3. Failover deadline calculation.
4. Effective backend termination and containment.
5. Complete D-188 reporting.
6. Registry admission-versus-abandonment race.
7. GD-11/GD-12 and per-part evidence persistence.

## 4. Report file

The complete 426-line review has been saved, preserving the commissioned report filename and directory structure.

2026-11-11-ir-p-02-r6-independent-re-review\.md

Markdown · 426 lines · SHA-256 verified

Download the complete R6 review report

Repository note: The report is saved in the supplied working-file environment, not directly in your local PublicDigit Git repository. Copy it to the corresponding repository path.

Review stopped. No source files, proof register or decision records were modified. No Principal decision was made, and no implementation was authorised.
