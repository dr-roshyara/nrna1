<!-- Recorder note (Claude, 2026-11-11): GPT-6's IR-P-02-R focused re-review (ChatGPT), as pasted by the Principal into the Claude Code session; extracted verbatim from the session transcript. Body unaltered. -->

# IR-P-02-R — Focused Re-Review: Platform Revision 5

**Date:** 11 November 2026  
**Reviewer:** GPT-6 in ChatGPT  
**Role:** IR-P-02 reviewer  
**Prior involvement:** Author of IR-P-02 and findings F-01 … F-07  
**Subject:** `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`  
**Subject SHA-256:** `29006aaa9dc55e91241de98746986483ff43b302a16c1da411cbf8d436d4cfe5`  
**Review scope:** D-182 and IR-P-02 findings F-02 … F-07  
**Verdict: NOT READY**

---

## 1. Integrity and review basis

The supplied package was inspected.

- All entries in `MANIFEST.sha256` were independently recalculated and matched.
- The subject revision 5 fingerprint matches the commission.
- The package's `SHA256SUMS` entries also match.
- The prior IR-P-02 report was available and used as the finding baseline.
- The accepted multi-database design and Governance thin slice were consulted for the relevant boundary and authority checks.

Revision 5, part 2, governs part 1 where they differ. In particular, the correction removing the cross-database transaction claim in F-05 is recognised.

The subject was reviewed as proposed, not accepted. No implementation or acceptance is implied by this report.

## 2. Executive conclusion

Revision 5 is materially better than revision 4.

It correctly introduces:
- two distinct contacts for an emergency case grant under D-182;
- a provisional emergency-registration lifecycle and recovery validation;
- a common decision-date precedence approach;
- per-recipient notification states;
- a central access gateway with shared/exclusive grant locking;
- explicit recovery and idempotency handling;
- a more precise lifecycle and registrar-bootstrap description.

However, the review identifies four remaining material issues:

1. **Contact-list capture remains possible after the D-180 waiting period.** A malicious current contact can remove other contacts, add an accomplice, wait 24 hours, and then obtain a two-contact approval. D-182 prevents immediate unilateral granting, but does not fully prevent the capture sequence.
2. **The F-06 lock matrix does not consistently protect D-182's current-contact requirement.** The second-approval-versus-withdrawal row specifies a grant-only lock, whereas every approval must also be serialized against contact changes.
3. **Organisation-side withdrawal has no actual maximum propagation time.** Until the withdrawal command reaches the Platform, the central grant remains active.
4. **The precedence and lifecycle wording still contains unresolved business-semantic questions.** In particular, decision-date precedence is not explicitly established by D-176, and the `pending`/`provisioning` distinction needs precise conformance with D-175 and D-178.

These are not merely missing test executions. Some require a clearer invariant, a correction to the tactical design, or an explicit Principal risk decision.

---

## 3. Per-question assessment

### Question 1 — D-182 and F-01: two-contact emergency grants

**Status: PARTIAL**

**References:** Revision 5, D-182; F-02; F-05; F-06; D-176, D-180 and D-182.

#### What is correctly implemented

The design states that:

- the first contact creates a grant in `requested`;
- a second, different contact must approve the same unchanged grant;
- both approvers must be current and grant-capable at the moment of their respective approvals;
- a direct-added contact becomes grant-capable only after the D-180 24-hour delay;
- an Organisation with fewer than two grant-capable contacts is refused the emergency-grant route;
- an unapproved request expires.

This is consistent with D-182.

A single-contact Organisation cannot obtain an emergency case grant through this route. The design does not silently weaken the rule to one approver.

#### Re-run of the capture scenario

The immediate attack is now blocked:

1. X removes Y and Z.
2. X remains current.
3. X alone cannot approve an emergency grant.
4. X adds accomplice A.
5. A cannot approve for 24 hours.

However, the attack is not fully prevented:

6. If the counterparty does not register a correcting change during those 24 hours, A becomes grant-capable.
7. X and A are now two different current, grant-capable contacts.
8. They can approve the same emergency grant.

The D-180 delay therefore creates a recovery opportunity for the counterparty; it does not guarantee independent confirmation or prevent a captured list from becoming grant-capable after 24 hours.

**Unilateral action remains possible:** X can still submit contact-list changes unilaterally through the direct route. D-182 constrains emergency grants, not contact-list changes.

#### Finding F-01-R — Delayed contact-list capture remains possible

**Severity:** High  
**Principal decision required:** Yes, for residual-risk acceptance or a further safeguard.

**Business consequence:** One compromised current contact can remove the other contacts, introduce an accomplice, and—after the waiting period—obtain a two-contact emergency grant. The second approval is formally distinct but may not be independent of the original attacker.

This does not mean D-182 was incorrectly transcribed. It means the combination of D-180 and D-182 does not eliminate the original capture risk.

The Principal must decide whether the 24-hour opportunity for counterparty correction is sufficient, or whether a stronger independent safeguard is required. This review does not amend D-180 or D-182.

**Additional proof requirement:** Test the complete sequence, including the 24-hour boundary, not only the immediate attempt by X alone.

---

### Question 2 — F-02: provisional emergency changes and evidence validation

**Status: PARTIAL**

**References:** Revision 5, F-02; D-176; D-181; D-175; revision 4 evidence model.

#### What is resolved

The design now distinguishes:

1. **Received:** the direct registration arrives.
2. **Registered:** it passes the Platform's emergency-route checks and has temporary operational effect while the Organisation database cannot answer.
3. **Validated:** on recovery, the documents match their registered digests and Governance's AuthorityCheck returns `Authorised`.
4. **Not validated:** evidence is missing, mismatched or not found, or AuthorityCheck returns `NotAuthorised` or `Undetermined`.
5. **Challenged:** the Organisation's normal challenge route applies.

The specific cases are covered:

| Case | Revision 5 treatment | Assessment |
|---|---|---|
| Missing documents | Not validated; reconcile to Organisation records | Covered |
| Digest mismatch | Not validated; record both versions | Covered |
| Cited decision not found | Not validated | Covered |
| AuthorityCheck `NotAuthorised` | Not validated | Covered |
| AuthorityCheck `Undetermined` | Not validated; D-181 applies | Covered |
| Evidence later supplied and matched | Validate only with `Authorised` | Covered |

The design correctly states that a digest proves that later-submitted bytes match the earlier fingerprint. It does not prove that the decision was genuine or competent.

It also correctly avoids storing Organisation decision texts, rules or minutes centrally.

#### D-176 and D-181 require a sharper distinction

D-176 says that the Platform's registered contact list governs the emergency route and that registration makes a designation or removal effective for that route.

D-181 says that an act whose competence cannot be determined takes no effect until the rules are amended or a competent body is designated.

These can be reconciled if the design explicitly distinguishes:

- the **temporary operational effect of a Platform contact-list entry** under D-176; and
- the **constitutional validity and effect of the underlying Organisation decision**, which only Governance can establish.

The Platform must not imply that provisional registration establishes constitutional authority. On recovery, `Undetermined` must not be treated as a valid decision.

Revision 5 moves in this direction, but the wording “in effect for the emergency route” and “has no effect from recovery onwards” should make the distinction explicit. Otherwise, a reader could interpret the design as allowing an act that D-181 says takes no effect.

#### Additional omission: verified counterparty as a sender

D-176 permits direct registration through either:

- another current platform contact; or
- the verified service counterparty.

Revision 5 F-02 describes the sender check as “sender current”. That wording appears narrower than D-176 and does not explicitly preserve the verified-counterparty route.

The counterparty is a recognised transmission channel, not an authority-holder. That distinction must remain.

**Finding F-02-R — D-176 sender and provisional-effect semantics need clarification**

**Severity:** Medium–High  
**Principal decision required:** Not necessarily, if the accepted D-176 and D-181 rules can be reconciled in the design.

Required corrections:
- explicitly preserve the verified-counterparty registration route;
- distinguish provisional Platform operation from constitutional validity;
- state that an `Undetermined` result never establishes constitutional authority;
- preserve the existing recovery and reconciliation behaviour.

**Assessment of “no change to D-176 is needed”:** The claim is defensible only if the distinction above is made explicit. Revision 5 has not yet expressed it precisely enough.

---

### Question 3 — F-03: precedence of contact updates

**Status: PARTIAL**

**References:** Revision 5, F-03 and part 2 F-03; D-176.

#### What is improved

The design now distinguishes:

- Organisation-database ordering: `(decision date, decision-log position)`;
- direct-registration ordering: `(cited decision date, platform arrival sequence)`.

It also addresses:
- older decisions arriving later;
- newer decisions arriving earlier;
- duplicate and delayed delivery;
- future-dated decisions;
- conflicting evidence;
- recovery, when Organisation records replace the Platform list.

This is substantially better than revision 4's arrival-only ordering.

#### The open business question must be answered

**Yes: ordering by cited decision date is a business rule that requires a Principal decision.**

D-176 says that the Organisation's decisions govern and that the direct registration must cite the Organisation's decision. It does not explicitly establish that the cited decision date is the controlling precedence rule during an outage.

The choice between:
- decision date;
- decision-log position;
- receipt or arrival order;
- or a defined combination

determines which Organisation decision wins when the Platform cannot consult the Organisation's authoritative log.

That is not merely a technical sorting choice.

#### Remaining tactical ambiguity

Revision 5 does not fully define all same-date conflicts.

For example:
- two different decision references cite the same date;
- two contact updates from different decisions have the same date;
- a registration has already been applied before a conflicting same-date registration arrives.

The statement that conflicting registrations citing the same date are refused as ambiguous does not fully explain whether the first registration is retained, withdrawn or also rejected in every such case.

Part 2 explicitly says that two registrations citing the same decision but carrying different digests are both refused. That is useful, but it does not resolve every same-date conflict.

**Finding F-03-R — Decision-date precedence is not yet an accepted business rule**

**Severity:** Medium–High  
**Principal decision required:** Yes.

The Principal must answer the existing [OQ]. The tactical design must then define same-date conflict handling and the resulting Platform list state.

---

### Question 4 — F-04: per-recipient notification lifecycle

**Status: RESOLVED, subject to preserving the part 2 delivery rule**

**References:** Revision 5, F-04 and part 2 F-04; D-179 and D-180.

The per-recipient lifecycle is substantially complete:

- `requested`;
- `queued`;
- `dispatched`;
- `delivered`;
- `failed`;
- `retrying`;
- `permanently-failed`.

It includes an event-and-recipient idempotency key, retry handling, escalation and treatment of recipients without active Accounts.

#### Delivery evidence

Part 2 correctly tightens part 1:

- provider acceptance is recorded;
- delivery confirmation is required to claim delivery;
- without confirmation, the state remains `dispatched`.

Therefore, **provider acceptance alone must not be reported as delivery to the recipient**.

If a channel does not provide delivery confirmation, the design must remain honest and leave the notice as `dispatched`. That is preferable to inventing a delivered status.

#### D-179

The verified login address remains restricted to the account-security notices authorised by D-179. The design does not broaden the login identifier into an identity-discovery, record-creation or account-binding mechanism.

The separate commercial channel for the counterparty is also appropriate.

**No new Principal decision is required.**

Implementation and proof must preserve the rule that `delivered` requires actual delivery evidence, not merely a provider acceptance identifier.

---

### Question 5 — F-05: operator sessions, withdrawal and expiry

**Status: PARTIAL**

**References:** Revision 5, F-05 and part 2 F-05; D-164, D-166, D-170; ISO-11.

#### Cross-database transaction correction

Part 2 correctly withdraws part 1's statement that the grant check and Organisation operation occur in the same transaction.

The central grant row is in the Platform database. The protected operation occurs in the Organisation database. A single transaction spanning both would contradict the stated no-distributed-transaction design.

The corrected gateway mechanism is a central coordination lock held while the Organisation operation runs. That is not a distributed transaction: it does not atomically commit both databases.

#### Platform-side withdrawal

The shared/exclusive lock mechanism is conceptually sound:

- each protected operation obtains a shared lock on the central grant row;
- it checks the grant, operator, scope and window;
- withdrawal or closure obtains an exclusive lock;
- the exclusive operation waits for an already-admitted operation;
- after the exclusive lock linearises, later operations must be refused.

This can guarantee that no operation is admitted after a Platform-side withdrawal, provided the lock is held for the complete protected operation and every operation goes through the gateway.

#### Expiry needs a precise admission rule

Expiry is more complicated than withdrawal.

The design must define precisely when an operation starts for authorisation purposes. If the shared-lock admission and time-window check occur before expiry, but the actual Organisation operation is delayed until after the expiry time, the design must say whether that operation is considered admitted before expiry.

The proposed mechanism can be coherent if:
- the time-window check occurs after acquiring the shared lock;
- the shared-lock admission is the defined operation-admission/linearisation point;
- no later operation can be admitted after the expiry boundary.

The current text does not fully define the boundary between admission and execution. The expiry proof must test it explicitly.

#### “Attempted without outcome”

This treatment is honest.

If the process is interrupted after the Organisation operation may have occurred but before the outcome is recorded, the record remains `attempted` with no outcome. Reconciliation reports that access **may have occurred**, rather than falsely reporting either success or no access.

That is the correct conservative interpretation.

#### P-W1: Organisation-side withdrawal delay

**Finding F-05-R — Organisation-side withdrawal has no defined maximum propagation bound**

**Severity:** High  
**Principal decision required:** Yes, unless a concrete technical bound is established within the accepted rules.

Part 2 states that an Organisation-side withdrawal is delivered to the Platform as a command. Until it reaches the central grant row, the grant remains active.

The phrase “bounded by delivery” does not establish a maximum time. Delivery can be delayed indefinitely by a failed outbox, unavailable Platform, network failure or retry failure unless a concrete bound and enforcement mechanism exist.

Business consequence: An Organisation may withdraw access in its own records while the Platform gateway continues to admit operations under the still-active central grant.

P-W1 cannot be treated as a sufficient bound merely because delivery is a proof obligation.

The design needs either:
- a defined maximum withdrawal-propagation time with a proved enforcement mechanism; or
- a fail-closed rule that prevents continued access when timely propagation cannot be established.

The Principal must determine the acceptable revocation latency or risk if the existing accepted decisions do not already establish it.

---

### Question 6 — F-06: concurrency and recovery matrix

**Status: NOT RESOLVED**

**References:** Revision 5, part 2 F-06 matrix, rows 1–8; F-03; F-05; D-182.

Part 2 correctly removes the false cross-database transaction claim and gives a much more useful matrix. Nevertheless, the matrix contains a material inconsistency.

#### Finding F-06-R1 — Second approval does not consistently lock contact state

**Severity:** High

D-182 requires both approvers to be current and grant-capable at the moment of each approval.

For contact removal versus approval, row 1 specifies:
- `ServedOrganisation` shared lock for approval;
- `ServedOrganisation` exclusive lock for contact update;
- lock order `ServedOrganisation` → grant.

That is the appropriate protection for the contact-currentness invariant.

However, row 2, second approval versus withdrawal, specifies:
- aggregate: `PendingCaseGrant`;
- linearisation point: grant row;
- lock order: grant only.

The second approval must protect two independent conditions simultaneously:

1. the approver is still current and grant-capable;
2. the grant has not already been withdrawn.

A grant-only lock does not serialize the approval against a concurrent contact removal.

The same second-approval operation cannot use the `ServedOrganisation` lock in one race and omit it in another. The matrix must show one coherent transaction and lock order that protects both invariants.

**Required correction:** the second approval must acquire the appropriate contact-state lock and grant lock in a consistent order, while preserving the withdrawal serialization. The matrix must then test all relevant competing operations.

#### Finding F-06-R2 — Expiry proof is insufficient

**Severity:** Medium–High

Row 3 tests withdrawal during an operation, but the proof description does not explicitly test:
- an operation admitted immediately before expiry;
- an operation attempting admission at or after expiry;
- an operation delayed between the central time-window check and the Organisation-side execution;
- an expiry command waiting for an operation already admitted.

The proof must test the defined expiry linearisation point, not only a withdrawal command.

#### Finding F-06-R3 — Recovery proof omits an explicit `Undetermined` case

**Severity:** Medium

Row 8 lists missing, mismatched, not-found and `NotAuthorised` cases. F-02 also specifies `Undetermined`, and D-181 makes that outcome important.

The recovery proof must explicitly assert that `Undetermined`:
- does not validate the registration;
- does not establish authority;
- causes the Platform list to be reconciled according to the Organisation's authoritative records;
- flags grants already given under that registration.

#### Other matrix observations

- Row 5's unique case-reference key is appropriate for duplicate grant requests, provided conflicting requests with the same key but different scope are rejected rather than silently treated as equivalent.
- Row 7's separate Organisation inbox transaction and idempotent copy are consistent with the no-distributed-transaction rule.
- Row 8's separate Organisation validation and Platform reconciliation are also consistent with that rule, provided retries and acknowledgement are tested.
- Row 6 still depends on resolving the same-date precedence ambiguity in F-03.

**Proof tests remain necessary, but these are not merely unexecuted tests: the lock model and its matrix representation need correction first.**

---

### Question 7 — F-07: lifecycle and bootstrap

**Status: PARTIAL**

**References:** Revision 5, F-07 and part 2 F-07; D-175; D-178; multi-database design §3–§4 and its accepted conformance note.

#### Lifecycle

Revision 5 defines:

- `pending`: application and founding evidence recorded; no technical operation;
- `provisioning`: counterparty verified and service-operation grant recorded;
- `operational`: current schema and required active-account contact.

The important business safeguard is preserved: no technical operation starts before counterparty verification and the service-operation grant.

This matches the purpose of D-175 and the accepted D-178 conformance note that `pending` precedes `provisioning`.

However, D-175 literally says that registration stays `provisioning` until the counterparty is verified. Revision 5 instead introduces `pending` as a separate status before `provisioning`.

The accepted D-178 conformance note supports a `pending` stage, but the design still needs to define whether `pending` is:
- a pre-registration application state outside the Platform registry; or
- an actual Platform registry status.

If it is a registry status, the exact relationship to D-175's wording must be recorded clearly. The author's proposed recorder clarification note should not be treated as authorised until the Principal permits it.

**Finding F-07-R1 — `pending` and D-175's registration status need precise conformance wording**

**Severity:** Medium  
**Principal decision required:** Clarification/authorisation of the recorder note if the accepted wording cannot be reconciled without it.

#### Bootstrap exception

The registrar-bootstrap rule is appropriately narrow in principle:

- the founding registrar is named in the founding decision;
- the first claim is issued once;
- it occurs during provisioning, before operational status;
- it is the one accepted exception to the ordinary officer/AuthorityCheck route;
- subsequent claim issuance follows D-113 and D-178.

This is consistent with the accepted multi-database conformance note.

The exception must remain a one-time bootstrap operation. It must not become a general mechanism for issuing claims without Governance authority.

**Bootstrap assessment: RESOLVED.**

---

## 4. Findings summary

| Finding | Severity | Status | Principal decision? |
|---|---|---|---|
| F-01-R: delayed contact-list capture remains possible | High | Partial | Yes — risk acceptance or further safeguard |
| F-02-R: sender and provisional-effect semantics | Medium–High | Partial | Not necessarily |
| F-03-R: decision-date precedence | Medium–High | Partial | Yes |
| F-04: notification lifecycle | Medium | Resolved | No |
| F-05-R: Organisation-side withdrawal bound | High | Partial | Yes, unless a concrete bound is established |
| F-06-R1: approval lock inconsistency | High | Not resolved | No, tactical correction |
| F-06-R2: expiry proof gap | Medium–High | Not resolved | No, tactical correction |
| F-06-R3: missing `Undetermined` proof case | Medium | Not resolved | No, proof correction |
| F-07-R1: lifecycle wording | Medium | Partial | Clarification/authorisation may be needed |
| F-07: bootstrap exception | — | Resolved | No |

---

## 5. Genuine Principal questions

The following are the matters that cannot safely be decided by the tactical designer or reviewer.

### P-1 — Is the 24-hour contact-correction opportunity sufficient?

D-182 requires two different contacts. D-180 makes an emergency-added contact grant-capable after 24 hours.

The remaining attack is that X removes other contacts, adds an accomplice and waits until the accomplice becomes grant-capable.

Does the Principal accept this residual risk, or require an additional independent safeguard?

This question does not reopen D-180 or D-182. It asks whether the combined accepted rules provide sufficient protection.

### P-2 — Is cited decision date the authoritative precedence rule?

Does the Principal accept decision-date precedence for direct emergency registrations, including its implications for same-date conflicts?

D-176 does not explicitly answer this.

### P-3 — What is the acceptable Organisation-side withdrawal propagation bound?

Until a withdrawal reaches the Platform, the central grant remains active.

What maximum delay is acceptable, or should access fail closed when timely propagation cannot be established?

### P-4 — Does `pending` represent a Platform registry status?

The accepted D-178 conformance note introduces `pending` before `provisioning`, while D-175 uses the wording that registration stays `provisioning` until verified.

The design must not silently change the accepted status semantics. If a recorder clarification is required, it needs the Principal's authorisation.

---

## 6. Verdict

# NOT READY

Revision 5 is not ready for Principal acceptance.

The reasons are:

1. D-182 blocks immediate unilateral granting but does not fully prevent delayed contact-list capture through an accomplice.
2. F-06's second-approval lock specification does not consistently protect the D-182 current-contact invariant.
3. P-W1 does not provide an actual maximum Organisation-side withdrawal delay.
4. F-03 still contains an unresolved business precedence rule and same-date ambiguity.
5. F-02 needs a clearer distinction between provisional emergency-route operation and constitutional validity, and must explicitly preserve the verified-counterparty route.
6. F-07 needs precise status conformance wording.

The report does not recommend implementation or acceptance.

The tactical design should not be silently amended by the reviewer. The identified tactical corrections and the genuine Principal questions must remain separately visible.

---

## 7. Reviewer identity and prior involvement

**Reviewer:** GPT-6 in ChatGPT  
**Commissioning authority:** The Principal (D-94)  
**Prior involvement:** Author of IR-P-02 and findings F-01 … F-07.

This is a focused independent re-review of Claude-authored revision 5. It is not a blind review because the reviewer authored the earlier IR-P-02 findings. The revision 5 author self-check was not treated as independent evidence.

**No source files were modified. No decision was made for the Principal. No acceptance is claimed.**

**End of report.**
