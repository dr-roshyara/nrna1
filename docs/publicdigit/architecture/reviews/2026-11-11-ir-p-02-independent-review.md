<!-- Recorder note (Claude, 2026-11-11):
- **What follows:** GPT-6's IR-P-02 report (ChatGPT), as pasted by the Principal into the Claude Code session.
- **How it was recovered:** extracted verbatim from the session transcript, between the paste markers.
- **What was changed:** nothing in the report body. It includes GPT-6's work instruction for Claude (§5) and its recommended sequence (§6).
- **Outcome:** F-01 was answered by D-182; F-02 … F-07 were addressed in Platform design revision 5 (proposed). -->

# Principal DDD Architect Review — IR-P-02

Subject: PublicDigit Platform (Tenancy) tactical design, revision 4\
Review date: 11 November 2026\
Reviewer: GPT-6\
Review basis: The supplied handover package, including the review commission, revision 4 design, strategic decision record, accepted multi-database design, Identity design, and Governance thin slice.



NOT READY

&#x20;Independent review verdict



## 1. Executive conclusion

Revision 4 is a substantial improvement over the earlier versions. Its central architectural direction is sound:

- Platform owns the Organisation registry, database locator, service-operation grants and emergency case-grant records.
- Each Organisation retains ownership of its own business data.
- Platform staff have a separate operational identity and cannot use ordinary member or workspace access to inspect Organisation data.
- The service-operation grant is recorded before provisioning.
- The founding registrar's first claim is treated as a narrow bootstrap exception.
- Contact removal and case-grant creation have an identified central transaction boundary.

I independently verified that the SHA-256 fingerprint of the supplied revision 4 design matches the fingerprint in the commission:

`468ebdf48621a0882028ba16365e567298d093c989aeb267549718255cd557fc`

However, I cannot recommend acceptance of revision 4 yet.

The most serious problem is in the emergency contact mechanism. D-180's 24-hour delay protects against a newly added contact immediately granting access. It does not protect against an already-authorised malicious contact removing the other contacts and then granting access while remaining authorised.

That is not a minor implementation detail. It is a weakness in the accepted business rule and requires a Principal-level risk decision.

There are also unresolved questions about the ordering of contact changes, evidence validation during outages, notification delivery, and the precise termination of operator access.

## 2. Detailed findings

The findings below use the commission's labels: [AD] accepted decision, [AC] architectural conclusion, [TD] tactical design, [TU] technical proof obligation, [OQ] Principal question, and [LQ] legal question.

| ID   | Severity    | Finding                                                                       | Principal decision?                                |
| ---- | ----------- | ----------------------------------------------------------------------------- | -------------------------------------------------- |
| F-01 | High        | Existing-contact capture and unilateral emergency grants                      | Yes                                                |
| F-02 | High        | Emergency changes take effect before their underlying decision can be checked | Potentially; depends on the accepted risk          |
| F-03 | Medium–High | Inconsistent ordering of contact updates from the two sources                 | No, unless the business precedence rule is changed |
| F-04 | Medium      | Notification is not the same as successful delivery or acknowledgement        | No                                                 |
| F-05 | Medium–High | Operator-session termination and one-case use semantics need precision        | No                                                 |
| F-06 | Medium–High | Concurrency and recovery proofs are incomplete                                | No                                                 |
| F-07 | Medium      | Lifecycle and bootstrap wording need final conformance clarification          | No, on the intended behaviour                      |

### F-01 — Existing contact can remove the other contacts and grant access

Evidence: Revision 4, D-180, M-11, M-12 and scenario 13; decision record D-174, D-176 and D-180.

The attack sequence specified in the commission is possible:

1. X, Y and Z are current platform contacts.
2. X is malicious or X's account is compromised.
3. X registers removal of Y and Z through the emergency route.
4. The removals take effect immediately.
5. X remains a current contact.
6. X gives an emergency case grant immediately.

The 24-hour rule does not stop this. It applies to contacts added through the emergency route. X is not a newly added contact; X already has the right to give a grant.

The contact-removal/grant transaction boundary does not prevent it either. That boundary correctly ensures that a contact removed in an earlier committed update cannot grant afterwards. But X has not been removed.

Notifications are useful detection, but they are not prevention. Even if Y and Z receive notices, the design does not establish that they can veto the grant or withdraw it after losing their current-contact status.

Business consequence: A single existing contact can become the only remaining contact and can independently authorise staff inspection of Organisation content. The other contacts may be informed, but their ability to stop the access is not established.

DDD consequence: `PlatformContacts` currently represents individually empowered grantors. It does not represent a multi-person approval rule. The domain model must not imply that having several contacts automatically provides dual control.

Principal decision required:

> Is one current platform contact alone sufficient to authorise an emergency content-access grant, even when that contact can remove the other contacts and act alone?

My architectural recommendation is to require independent approval for emergency content access where the Organisation has multiple active contacts. If only one active contact exists, the safe fallback should be to wait for the Organisation database and normal Governance authority route, rather than silently weakening the approval rule.

This recommendation changes the business rule represented by D-174/D-176 and therefore requires your explicit decision. It must not be implemented by Claude as an assumed correction.

### F-02 — Emergency contact changes can take effect before evidence validation

Evidence: Revision 4, H-2 correction and F-01 correction in revision 3; D-176; D-181.

Revision 4 correctly prevents central storage of Organisation decision texts, statutes and minutes. It stores metadata, including:

- decision reference and date;
- body name and provision reference;
- document digests;
- sender.

The documents remain with the sender and are submitted to the Organisation database after recovery.

A digest can help establish that later-submitted bytes match the bytes represented by the digest. It does not independently establish:

- that the underlying decision is genuine;
- that the cited body had competence;
- that the decision was actually adopted;
- that the sender's interpretation is correct;
- that the decision was not superseded.

The design allows a direct registration to take effect when registered, while its evidence is still described as asserted and not machine-checked. If the documents are missing or do not match after recovery, the registration and grants are flagged. The design does not define a time limit for submission or a fail-closed consequence if evidence never arrives.

There is also a conformance question with D-181: a declared competence gap establishes no authority and the act takes no effect. The design must explain what happens if an emergency registration is provisionally applied but later proves to have no competent decision behind it.

Business consequence: The platform may act on a contact-list change before the Organisation's own Governance records can establish that the change was authorised.

DDD consequence: The design must distinguish the registration of an assertion, its provisional operational effect, and its later validation by the Organisation. These are not necessarily the same state.

Disposition: A design correction is needed. If the correction changes whether an emergency update is effective immediately, that change requires a Principal decision because D-176 expressly establishes when registration takes effect.

### F-03 — Contact-update ordering is not one coherent precedence rule

Evidence: Revision 3 F-04; revision 4 M-6 and M-12; D-174 and D-176.

There are two update sources:

1. updates received from the Organisation database;
2. direct emergency registrations through the Platform.

The earlier design orders updates by the underlying Organisation decision's date and log position. Revision 4 says direct registrations are ordered by arrival on the Platform, not by the decision date they cite.

This creates a possible conflict:

- a newer Organisation decision is registered;
- an older, backdated decision is submitted later through the direct route;
- the later arrival may replace the newer designation.

Rejecting future-dated decisions does not prevent this. A decision dated in the past can still arrive after a more recent decision.

Business consequence: The active contact list may not represent the most recent valid Organisation decision.

DDD consequence: The model currently has different ordering semantics for two commands that update the same business concept. A single precedence rule and an explicit ordering key are required.

Disposition: Claude should propose a coherent ordering mechanism and test delayed, duplicated and reordered messages. Do not assume that arrival order and decision order are interchangeable.

### F-04 — Notification lifecycle is incomplete

Evidence: Revision 4 M-1, D-179 and D-180.

The design adds `reportedToContacts` and `copiedToOrganisation` flags, each with an attempts log. It also requires notifications to current and removed contacts and the counterparty.

This is a good start, but the commission correctly distinguishes reporting from delivery.

The model does not fully define:

- requested, queued, dispatched, delivered, failed and permanently failed;
- retries and retry exhaustion;
- per-recipient delivery status;
- what happens if a recipient's account has no verified login address;
- how counterparty notification is delivered;
- how a failed notification is escalated.

D-179 authorises the narrow use of verified login addresses for account-security notices. It does not authorise using login addresses for identity discovery, account creation or binding.

Business consequence: The system could record that a notice was requested or dispatched without proving that the removed contact received it.

Disposition: Tactical correction, no new Principal decision expected. The design must report honestly what it knows: requested, dispatched or delivered. It must not claim delivery based only on an attempt.

### F-05 — Operator access session must be bounded throughout its lifetime

Evidence: Revision 4 H-3, M-1, M-9 and M-11; multi-database design §16 and ISO-11.

The boundary is conceptually correct:

- `PlatformOperator` is a separate identity;
- the operator has no ordinary Organisation binding;
- access is through a case-grant session;
- the grant names the operator, case, data area and time window;
- backup inspection uses an isolated, expiring copy;
- general support access is not permitted.

The accepted multi-database design also requires no human standing credentials on Organisation business tables, non-interactive operator roles, encrypted per-Organisation backups, automated restores, encrypted and expiring temporary copies, and proof before go-live.

The remaining ambiguity concerns the active session:

- Is the grant checked only when the session opens, or on every protected operation?
- Does withdrawal immediately terminate an already-open session?
- Does expiry terminate an already-open session?
- What exactly does `used-up` mean?
- Can the operator perform several operations within the one approved case?
- When is the case considered complete and the grant closed?

Business consequence: A grant could be withdrawn or expire while a session remains open unless the design explicitly prevents continued access.

Disposition: Tactical correction. Define the session lifecycle and require revalidation or immediate termination when the grant is withdrawn, expires or otherwise closes.

### F-06 — Concurrency and recovery proof obligations are not complete

Evidence: Revision 3 F-04; revision 4 M-11, M-13 and L-1…L-5.

The design has two useful linearisation points:

- contact update versus grant creation: the `ServedOrganisation` row, with an exclusive lock for updates and a shared lock for grant creation;
- grant withdrawal versus use: the `PendingCaseGrant` row, with an exclusive lock.

These are plausible mechanisms, not proofs by themselves.

The commission asks for the following cases to be demonstrated:

| Case                              | Current position                         | Remaining proof                                          |
| --------------------------------- | ---------------------------------------- | -------------------------------------------------------- |
| Contact removal vs grant creation | Linearisation point identified           | Both commit orders, repeated concurrent tests            |
| Withdrawal vs use                 | Linearisation point identified           | Both commit orders and effect on an already-open session |
| Duplicate contact updates         | Not fully specified                      | Stable idempotency key and duplicate handling            |
| Duplicate grant commands          | Idempotency stated                       | Same-case retries and conflicting requests               |
| Delayed/reordered updates         | Arrival ordering introduced              | Demonstrate no stale update silently wins                |
| Recovery copy                     | Grant copy idempotent by grant ID        | Reconciliation of evidence, status and failed copies     |
| Cross-aggregate transaction       | Explicitly spans contact and grant state | Lock order, rollback behaviour and deadlock tests        |

Business consequence: The design may appear safe in sequential scenarios but behave differently under retries, concurrent commands or recovery.

DDD consequence: Aggregate boundaries and transaction boundaries must be justified by the invariant they protect. The fact that a database lock exists does not establish that the whole business invariant is preserved.

Disposition: Tactical correction and proof work; no Principal decision expected unless the proposed consistency rule changes the accepted business behaviour.

### F-07 — Lifecycle and bootstrap need final conformance clarification

Evidence: Revision 4 F-02, F-03, M-2, M-3, M-4 and M-8; D-113, D-148, D-175 and D-178; multi-database design §3–§4 and §16.

The important controls are present:

- the service-operation grant precedes provisioning;
- no technical operation starts before the counterparty is verified and the grant is recorded;
- `schemaState = migrating` blocks routing;
- the registrar's first claim is the only bootstrap exception to the normal officer-authorised claim path;
- the registrar's claim is issued before `operational`;
- the Organisation becomes `operational` only with a current schema and at least one active-account contact.

The unresolved wording is `pending` versus D-175's statement that registration stays `provisioning` until the counterparty is verified.

Revision 4 says `pending` refines the lifecycle and satisfies D-175 in substance. But the two labels are not identical. The design should define them unambiguously:

- `pending`: application recorded; no technical operation;
- `provisioning`: verified counterparty and service-operation grant recorded; technical provisioning may proceed;
- `operational`: schema current and required active contact exists.

This is a sensible interpretation of the latest design, but the decision record and accepted multi-database design must be made consistent with it.

The bootstrap exception is appropriately narrow. It must not become a general route for issuing claims without Governance authority.

Disposition: Conformance correction, no new Principal decision expected if the above lifecycle is the intended interpretation.

## 3. Principal decision required

There is one immediate business decision. It should be answered before asking Claude to finalise the next revision.

Principal decision required

Can one existing platform contact independently authorise an emergency case grant?

The current D-174/D-176 model allows this. D-180's 24-hour restriction on newly added contacts does not change that authority.

My recommendation is independent approval for emergency content access. If the Organisation has only one active contact, emergency access should wait until the normal Organisation/Governance route is available.

This does not mean that Claude should amend D-174, D-176 or D-180. You must decide the business rule first.

## 4. Final review report

# IR-P-02 — Independent Review: Platform (Tenancy) Revision 4

**Date:** 11 November 2026\
**Reviewer:** GPT-6 (ChatGPT)\
**Subject:** `2026-11-11-platform-tenancy-tactical-design.md`, revision 4\
**Subject SHA-256:** `468ebdf48621a0882028ba16365e567298d093c989aeb267549718255cd557fc`\
**Verdict: NOT READY**

## 1. Reviewer independence and integrity

The reviewer is not Claude, DeepSeek or Claude Code and did not author revision 4.

Prior involvement is disclosed: GPT-6 performed IR-P-01 on revision 2. The supplied package contains an extract of that review, not the full report. This review is independent of revision 4's authorship, but it is not a blind review.

The supplied `MANIFEST.sha256` was verified: all 11 listed files matched their recorded hashes. The revision 4 subject fingerprint also matched the commission exactly.

Revision 4 governs over earlier revisions where they differ. The DeepSeek review is treated only as non-independent input.

## 2. Business summary

The Platform context is correctly positioned as PublicDigit's organisation registry, database-routing capability and owner of platform-side operational records.

The design preserves the critical distinction between:

- PublicDigit operating an Organisation's technical service;
- an Organisation owning and governing its own business data;
- an Organisation granting case-specific access to its content.

The separation of the Platform operator identity from Organisation users is sound. The provisioning sequence, registrar bootstrap exception, database isolation and fail-closed routing are also substantially improved.

However, the emergency contact model has a material security weakness. An existing contact can remove other contacts and still independently authorise an emergency case grant. The 24-hour restriction on newly added contacts does not prevent this.

The evidence and ordering rules for emergency contact changes also need further work. Notification delivery and active operator-session termination are not sufficiently specified.

## 3. Findings

### F-01 — Existing-contact capture and unilateral grant

**Severity:** High\
**References:** Revision 4 D-180, M-11, M-12, scenario 13; D-174, D-176, D-180.

An existing contact X can remove Y and Z immediately, remain a current contact, and give a case grant immediately. The 24-hour delay applies only to newly added emergency contacts.

The concurrency rule does not prevent this because X remains authorised.

Notifications are not prevention. The design does not establish a veto or withdrawal right for removed contacts.

**Business consequence:** One compromised contact can become the only remaining contact and independently authorise staff inspection.

**DDD consequence:** The current model provides individual grant authority, not dual control.

**Principal decision:** Decide whether one current contact alone is sufficient to authorise an emergency content-access grant.

**Recommendation:** Require independent approval for emergency grants where multiple active contacts exist. If only one exists, fail closed and wait for the normal Organisation/Governance route.

### F-02 — Emergency evidence validation

**Severity:** High\
**References:** Revision 4 H-2; revision 3 F-01; D-176; D-181.

The Platform stores metadata and document digests, not Organisation decision texts. Digests do not prove the authority or authenticity of the underlying decision.

Direct registrations can take effect before their documents and competence are validated in the Organisation database. Missing or mismatched evidence is flagged, but no deadline or complete fail-closed consequence is specified.

The design must reconcile this with D-181, under which a declared competence gap establishes no authority and the act takes no effect.

**Business consequence:** An emergency contact change may be operational before its constitutional basis is established.

**Disposition:** Tactical correction. Any change to D-176's immediate-effect rule requires the Principal.

### F-03 — Contact-update ordering

**Severity:** Medium–High\
**References:** Revision 3 F-04; revision 4 M-6 and M-12; D-174 and D-176.

Organisation-database updates and direct registrations do not clearly share one precedence rule. Direct registrations use arrival order, while the earlier database-update rule used decision date and log position.

A later-arriving, older decision may therefore replace a newer contact designation.

**Business consequence:** The active contact list may not reflect the latest valid Organisation decision.

**Disposition:** Tactical correction. Define one precedence rule and prove it against delayed, duplicated and reordered updates.

### F-04 — Notification lifecycle

**Severity:** Medium\
**References:** Revision 4 M-1; D-179 and D-180.

The design records reporting flags and attempts, but does not fully distinguish requested, dispatched, delivered, failed and permanently failed notifications.

It also needs to define notification delivery for contacts without active Accounts and for the verified counterparty.

**Business consequence:** A recorded notification attempt may be mistaken for successful delivery.

**Disposition:** Tactical correction. Use per-recipient delivery records, retries and honest delivery status. Preserve D-179's narrow login-address exception.

### F-05 — Operator-session lifecycle

**Severity:** Medium–High\
**References:** Revision 4 H-3, M-1, M-9 and M-11; ISO-11; multi-database design §16.

The separate operator identity and case-grant session are correct. However, the design must specify what happens to an already-open session when a grant is withdrawn, expires or becomes used-up.

It must also define whether multiple operations are permitted within one case and when the case is complete.

**Business consequence:** A session could potentially remain active after the underlying permission ends.

**Disposition:** Tactical correction. Enforce scope, operator, data area and window throughout the session.

### F-06 — Concurrency and recovery proofs

**Severity:** Medium–High\
**References:** Revision 3 F-04; revision 4 M-11, M-13 and L-1…L-5.

The design identifies linearisation points for contact updates versus grants and withdrawal versus use. These are plausible but not sufficient evidence of correctness.

Proofs remain necessary for both commit orders, duplicate commands, delayed updates, concurrent operations, recovery copies and session termination.

**Business consequence:** Sequential scenarios may pass while concurrent or recovery scenarios violate the intended business rule.

**Disposition:** Tactical proof work. Define idempotency keys, lock order, rollback behaviour and recovery reconciliation.

### F-07 — Lifecycle and bootstrap conformance

**Severity:** Medium\
**References:** Revision 4 F-02, F-03, M-2, M-3, M-4 and M-8; D-113, D-148, D-175 and D-178.

The service-operation grant precedes provisioning. The `migrating` state is unroutable. The registrar's first claim is a narrow bootstrap exception, and an active contact is required before the Organisation becomes operational.

The remaining issue is the exact relationship between `pending` and D-175's statement that registration remains `provisioning` until verification.

**Disposition:** Clarify the lifecycle and align the decision record and accepted multi-database design. No new Principal decision is expected if the intended semantics are unchanged.

## 4. Principal decisions

**Immediate decision:**

Is one current platform contact alone sufficient to authorise an emergency content-access grant, even after removing other contacts?

The review recommends independent approval, with fail-closed behaviour if no second authorised contact exists.

No other new Principal decision is established by this review. Any change to the accepted immediate-effect rule for emergency contact updates must be submitted separately to the Principal.

## 5. Proof obligations

Before implementation, the design must demonstrate:

1. A malicious existing contact cannot bypass the chosen approval policy.
2. Emergency contact updates cannot silently override newer decisions.
3. Missing or mismatched evidence has a defined outcome.
4. Every notification has an honest per-recipient delivery state.
5. Withdrawal and expiry terminate active operator access.
6. Contact updates, grants, withdrawals and use are correct under concurrency.
7. Duplicate and delayed messages are idempotent.
8. Recovery copies reconcile correctly without changing Organisation authority.
9. Backup inspection is isolated, encrypted, expiring and auditable.
10. INV-ACCESS-1 and ISO-11 are demonstrably enforced.

## 6. Verdict

**NOT READY.**

Revision 4 has a sound architectural foundation, but F-01 is a material unresolved business-security risk. F-02 and F-03 also affect the trustworthiness of the emergency contact mechanism. F-04 to F-07 require tactical completion and proof.

Revision 4 must not be accepted for implementation.

The next step is a focused Principal decision on emergency grant approval, followed by a corrected tactical design and focused independent re-review.

**No implementation is authorised by this report.**

## 5. Prompt instructions for the next Claude session

The next session should not start another broad strategic discovery round. The strategic baseline is already accepted through D-173, and the relevant subsequent decisions must be preserved.

The task is to resolve the Platform tactical design findings without allowing Claude to make Principal decisions.

# Claude Work Instruction — IR-P-02 Findings and Platform Revision 5

## Role

Act as the PublicDigit Governance Engineer and tactical DDD designer.

The Principal DDD Architect has completed IR-P-02, an independent review of Platform (Tenancy) tactical design revision 4.

Your task is to investigate the findings and prepare the next design step.

The human Principal remains:

- Product Owner;
- solo developer;
- human ARB Chief;
- final authority for all Principal decisions.

There are no other developers to whom work can be delegated.

Do not make decisions for the Principal.

## 1. Binding baseline

Read and respect:

- D-113: PersonRef and claim semantics;
- D-164: central Platform database and one database per Organisation;
- D-166: Organisation-granted technical operations and case-specific content access;
- D-167 and D-168: no default authority and overridable product defaults;
- D-170: emergency case grants;
- D-173: accepted strategic baseline;
- D-174 and D-175: platform contacts and counterparty verification;
- D-176: registered contact list governs the emergency route;
- D-178: accepted multi-database design revision 5;
- D-179: narrow security-notice exception;
- D-180: 24-hour delay for newly added emergency contacts;
- D-181: declared competence gaps establish no authority.

Also respect:

- INV-ACCESS-1;
- ISO-1 … ISO-11;
- the accepted Identity boundary and XD-3;
- the Governance AuthorityCheck contract.

Do not reopen these decisions merely because a tactical design correction is needed.

## 2. Read the IR-P-02 report first

Use the complete report supplied by the Principal.

Findings:

- F-01: existing contact can remove other contacts and independently grant access;
- F-02: emergency evidence validation and immediate-effect semantics;
- F-03: inconsistent contact-update ordering;
- F-04: notification lifecycle;
- F-05: operator-session termination;
- F-06: concurrency and recovery proofs;
- F-07: lifecycle and bootstrap conformance.

Revision 4 remains the current subject. Do not silently edit it.

## 3. First task — F-01: prepare the Principal decision

Investigate this exact scenario:

1. X, Y and Z are current contacts.
2. X is malicious or compromised.
3. X removes Y and Z through the emergency route.
4. X remains a current contact.
5. X immediately gives a case grant.
6. Y and Z receive notifications but have no established veto or withdrawal power as removed contacts.

Explain clearly:

- why D-180's 24-hour delay does not prevent this;
- why the transaction lock does not prevent it;
- what power an individual platform contact currently has;
- whether the issue is unilateral grant authority, contact-list capture, or both;
- what the business consequences are.

Prepare one Principal question only:

**Is one current platform contact alone sufficient to authorise an emergency content-access grant?**

Present at least these alternatives:

A. Keep the current unilateral-contact model and explicitly accept the risk.

B. Require independent approval by two distinct authorised contacts for emergency content access. If only one active contact exists, the request waits for the normal Organisation/Governance route.

C. Propose another precisely defined control, with its operational consequences.

Recommend one option and explain why.

Do not append or amend D-174, D-176 or D-180. Do not implement an option. Stop and wait for the Principal's answer.

## 4. Second task — F-02: evidence and temporary effectiveness

After the Principal has answered F-01, investigate:

- what metadata is stored centrally;
- what the digest proves and does not prove;
- who retains the original evidence;
- what happens if documents are never submitted;
- what happens if a digest does not match;
- what happens if the cited decision is not found;
- how D-181 applies to a declared competence gap;
- whether an emergency registration may take effect before validation.

Distinguish:

1. receipt of an assertion;
2. registration of the assertion;
3. temporary operational effect;
4. validation by Organisation Governance;
5. challenge or correction.

Do not claim that a digest proves constitutional authority.

If the required behaviour would change D-176, prepare a separate Principal decision brief. Do not decide it.

## 5. Third task — F-03: one ordering rule

Analyse both update sources:

- Organisation-database updates;
- direct emergency registrations.

Determine whether arrival order, decision date and decision-log position can produce conflicting results.

Provide a coherent precedence model covering:

- older decision arriving later;
- newer decision arriving earlier;
- duplicate registration;
- retry;
- delayed delivery;
- conflicting evidence;
- recovery after an outage.

Identify the linearisation point and stable idempotency key.

Do not use arrival time as a substitute for decision authority without explaining the business consequence.

If a business precedence rule is needed, ask the Principal separately.

## 6. Fourth task — F-04: notification lifecycle

Define a per-recipient lifecycle that distinguishes:

- requested;
- queued;
- dispatched;
- delivered;
- failed;
- retrying;
- permanently failed.

Define:

- idempotency;
- retry behaviour;
- delivery evidence;
- removed-contact notifications;
- counterparty notifications;
- recipients without active Accounts;
- escalation when delivery fails.

Preserve D-179 exactly: verified login addresses are used only for the authorised security notices, never for identity discovery, account creation or binding.

Do not claim delivery when only dispatch has occurred.

## 7. Fifth task — F-05: operator access

Define the complete case-grant session lifecycle.

The grant must identify:

- the named PlatformOperator;
- the case;
- the Organisation;
- the permitted data area;
- the time window;
- the case type (`live-database` or `backup-copy`).

Explain how every protected operation verifies that the grant remains active.

Define the effect of:

- expiry;
- withdrawal;
- use-up;
- operator account disablement;
- session interruption;
- backup-copy expiry and destruction.

Withdrawal or expiry must not leave an existing session with continued access.

Preserve INV-ACCESS-1 and ISO-11. No standing human database credentials. No general support route.

## 8. Sixth task — F-06: concurrency and recovery

Prepare a concurrency matrix for:

- contact removal versus grant creation;
- grant withdrawal versus use;
- duplicate grant commands;
- duplicate contact updates;
- delayed and reordered updates;
- recovery copy into the Organisation database;
- evidence submission and reconciliation;
- an already-open operator session.

For every case identify:

- aggregate or aggregates involved;
- transaction boundary;
- linearisation point;
- idempotency key;
- lock order;
- both possible commit orders;
- recovery behaviour;
- proof test.

A lock or version field alone is not a proof.

Preserve the deliberate separation between the Platform database and Organisation database. Do not introduce a distributed transaction.

## 9. Seventh task — F-07: lifecycle and bootstrap

Clarify the exact lifecycle:

- `pending`;
- `provisioning`;
- `operational`;
- `suspended` and `closed` only as later-release states.

Align the meaning with D-175 and the accepted multi-database design.

Verify:

- counterparty verification before technical operation;
- service-operation grant before provisioning;
- `schemaState = migrating` blocks routing;
- at least one active-account contact before operational;
- registrar's first claim is the only bootstrap exception;
- the registrar's claim is issued before operational;
- redemption and routing follow D-113 and D-178.

Do not turn the bootstrap exception into a general claim-issuance mechanism.

## 10. Deliverable after Principal decisions

Once all necessary Principal decisions have been answered, prepare:

`docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`

as **revision 5 — proposed**, not accepted.

Preserve the full decision history. Clearly identify:

- which sections are superseded;
- which accepted decisions are implemented;
- which findings are resolved;
- which matters remain open;
- which proofs remain mandatory;
- which legal questions remain outside the architecture.

Do not modify the decision record or accepted multi-database design unless explicitly authorised.

## 11. Final coherence check

Before presenting revision 5, verify:

- D-164 physical separation;
- D-166 and INV-ACCESS-1;
- D-170 emergency grants;
- D-174 platform contacts;
- D-175 counterparty verification;
- D-176 contact-list authority;
- D-179 security notices;
- D-180 addition delay;
- D-181 no authority from a declared competence gap;
- ISO-1 … ISO-11;
- Identity's XD-3 boundary;
- Governance ownership of constitutional authority.

Check that no Platform capability silently creates Organisation authority.

## 12. Restrictions

- No code changes.
- No migrations.
- No implementation.
- No commits.
- No changes to accepted decisions.
- No changes to the accepted strategic baseline.
- No silent Principal decisions.
- No broad administrative project planning.
- Do not claim a design is independently reviewed merely because you reviewed your own changes.

The Principal is the only person who can approve the business rules and authorise implementation.

**First output only the F-01 Principal decision brief. Then stop and wait for the Principal.**

## 6. My recommended working sequence

1. You decide F-01 — whether a single contact can independently authorise emergency content access.
2. Claude investigates and resolves the technical findings without changing accepted decisions.
3. You review the proposed revision 5.
4. A fresh independent review checks revision 5.
5. Only after acceptance do we consider implementation authorisation and its proof gates.

My overall judgement: The strategic architecture is not the problem here. The Platform boundary is correctly identified. The remaining work is to make the emergency access mechanism enforce the Organisation's intended authority, including under compromise, outage, concurrency and recovery. That is the right next step before moving into implementation.
