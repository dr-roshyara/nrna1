# M-TDD-01: Membership participant operations, tactical design

| | |
|---|---|
| **Status** | **PROPOSED tactical design.** Authorises no implementation. Changes no ratified record. Not independently reviewed (Claude family). |
| **Commission** | M-TDD-01, Principal, 2026-11-09 (tactical design only; implementation NOT authorised). |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `membership/architecture/`, the existing Membership architecture folder |
| **Snapshot** | branch `election-flow-updated`, HEAD `bad1202d0`. Read-only. |
| **Authoritative inputs** | ratified act `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md` (cited **RA:line**); ratification act recorder's notes `…-ratification-act.md:16-19` (N-3…N-6); decision register `…-final-decision-register.md` (**REG:line**); cutover gate and roadmap `docs/publicdigit/reviews/2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md` §4, §6 (**GATE**); Slice 2 plan `docs/plans/20261011-0010-membership-slice2-command-rules-plan.md` |

**Labels:** **RATIFIED** · **ACCEPTED WD** · **FACT** (code, file:line) · **PROPOSED** · **PRINCIPAL** (decision required) · **UNKNOWN**.

## 0. Disagreements and corrections (stated first)

1. **The commission treats these as three new operations. They already sit on a ratified roadmap.**
   - FACT: the ratified model is implemented as a kernel in `app/Contexts/Membership/Domain/Organisational/` (Admit, End, Correct, Annul, Void, AuthorityEvidence, Resolution, InvariantSet), with handlers `Application/Organisational/AdmitMemberHandler.php`, `EndMembershipHandler.php`, an `AuthorityCheckPort` and a Postgres repository.
   - FACT: **no controller or route calls it** (no caller outside `Application/Organisational`). Every participant path runs on the legacy `members` / `membership_applications` models.
   - RATIFIED / accepted roadmap (GATE §6): Slice 3 cutover machinery (C-1…C-5, C-8) → dry runs and parity → **Slice 4 HTTP/UI: "Admit on application approval"** → **step 6 lifecycle slices: "renew, type, suspend/restore, …, one reviewed slice each"**. ARB-MEM-01: correction before cutover is a mandatory gate.
   - **Consequence:** approval and renewal are designed here **as inputs to Slice 4 and the Renew slice**, not as a parallel track. Building them channel-neutral on the legacy model would build each operation twice (decision PD-M1).
2. **"FeePaid is a Finance fact" is a recorder's note, not act text.** The act says "Finance … own their own truths" (RA:52) and "settlement is Finance's" (RA:155). N-5 records that Blueprint §5 is ambiguous and that **EP-01 treats FeePaid as a Finance fact consumed by Membership** (ratification act `:18`). This design follows N-5. *Correction to my Gate F report §6.3, which said "no ratified direction": there is a recorded operative interpretation for EP-01; its owner-level ratification is still not shown.*
3. **PD-D was misread, so its rejection rejects something not proposed.** `PBDIGIT-318`'s baseline is a list of **boundary violations to remove** (V1, V1b, V2, V3′). Adding the unused Membership `VotingEligibilityPolicy` to it would mark it **for removal**, which is exactly what the review wants ("a possible architectural inconsistency or obsolete code"). It would not make eligibility a Membership responsibility. Recorded as: rejected as worded; **clarification for the Principal**, not re-asked here.
4. **DD-15 does not by itself require extraction now.** A participant operation served by a responsive Inertia page **already works on a phone browser**. Extract-before-expose (D-7) is required **before a second channel** exists. The urgency is set by cutover and by any second channel, not by DD-15 alone.

**Recorded Principal outcomes on the reconciliation report (2026-11-09):** PD-A accepted with modification (Election owns the objection *process*; the competent deciding authority is identified separately). PD-B: online payment deferred; showing instructions is **not** assumed to satisfy the requirement. PD-C: principle accepted; each criterion to be verified against organisation rules. PD-D: rejected (see 0.3). **PD-E: ACCEPTED WD**: channel requirements belong to operations.

## 1. Executive conclusion

- **M-1 Submit application** can be made **one channel-neutral operation now**, because a submitted application is **not a membership fact**. It is the person's **declaration** (RA:45 "organisation decision + person declaration"; REG:43 BD-CONSENT), held until a decision. The two current paths apply **different business rules** and must converge into one.
- **The decision on an application** (desktop) becomes the ratified **Admit** with decision provenance and authority evidence from Governance's `AuthorityCheckPort`, at **Slice 4**. Until then legacy approval continues.
- **M-2 Renew** is **not** "update the expiry date". RATIFIED: Renew is an append-only fact granting a **term segment** to an open period; it never changes coverage; a late renewal preserves the lapse gap (RA:59, RA:92, INV-12). Payment is a separate Finance matter, and dues standing is a separate predicate. **The kernel has no Renew fact yet** (FACT). Who may renew, and whether payment or acceptance must come first, are **organisation rules** not yet known.
- **M-3 Read own status** is a **query with no subject parameter**: the subject is the authenticated person. It reads resolved predicates (after cutover) or the legacy record (before), through one port.
- **Three findings need attention regardless of mobile:** an unverified public e-mail can attach an application to **someone else's existing account** at approval (consent); duplicate checks differ between paths and are case-sensitive; Membership creates fee records at approval and renewal, contrary to N-5.
- **Principal decisions:** PD-M1 (sequencing), PD-M2 (consent of public applicants), PD-M3 (renewal rule for NRNA), PD-M4 (applicant status access).

## 2. Repository evidence

### 2.1 M-1 Submit application: two paths, two rule sets (FACT)

| | Public path | Authenticated path |
|---|---|---|
| Entry | `PublicMembershipApplicationController::store` (`app/Http/Controllers/Membership/PublicMembershipApplicationController.php:28`) | `MembershipApplicationController::store` (`app/Http/Controllers/Membership/MembershipApplicationController.php:56`) |
| Write | Eloquent `MembershipApplication::create` in the controller (`:66-77`), `user_id` null, `source` public, `expires_at` +60 days | use case `SubmitMembershipApplication::execute` → `Application::submit` (`app/Contexts/Membership/Application/Application/UseCases/SubmitMembershipApplication.php`; `Domain/Application/Application.php:46`) |
| Already a member? | **not checked** | checked in the controller (`:68-81`) |
| Open application? | by e-mail, statuses submitted/under_review (`:56-64`) | by user **or** e-mail, statuses draft/submitted/under_review (`:84-96`), in the controller |
| Membership type | none; chosen by the approver (`:190-194`) | required, checked in the controller (`:99-106`) |
| Abuse controls | 3 per IP per hour (`:31-35`), honeypot (`:51-53`) | — |
| Notifications | mail queued in the controller (`:80-85`, `notifyAdmins`) | event bus |
| Idempotency | none | none |
| Transaction | none | `DB::transaction` inside the use case (a facade in the application layer) |

- **E-mail comparisons are exact** (`where('applicant_email', $data['email'])`, `:57`). On PostgreSQL this is case-sensitive, so `A@x.org` and `a@x.org` pass the duplicate guard. PROPOSED finding; not runtime-tested.
- **Approval** (`MembershipApplicationController::approve`, `:169-327`): authorisation `approveApplication` = owner/admin (`:175`); for public applications, **reuses an existing account with the same e-mail** or creates a new `User` in the controller (`:220-243`); creates `OrganisationUser` (`:253`); then `ApproveMembershipApplication` creates a legacy `Member` **and a `Fee`** in Membership (`UseCases/ApproveMembershipApplication.php`, steps 2–3). **No authority evidence is recorded.**
- Tests: `tests/Feature/Membership/PublicApplicationSubmissionTest.php`, `PublicApplicationRateLimitTest.php`, `MembershipApplicationTest.php`, `ApplicationApprovedToVoterEligibilityTest.php`.

### 2.2 M-2 Renew (FACT)

- Entry `MembershipRenewalController::store` (`app/Http/Controllers/Membership/MembershipRenewalController.php:20`), route `organisations.php:209`.
- Authorisation: `MembershipPolicy::initiateRenewal` (`app/Policies/MembershipPolicy.php:68-80`): owner/admin for anyone, a member for themselves.
- Self-renewal window: `Member::canSelfRenew` (`app/Models/Member.php:158-167`): status `active`, not lifetime, expiry after now − **90 days** (`config('membership.self_renewal_window_days', 90)`, **platform-wide**).
- In one transaction in the controller (`:54-94`): new expiry = max(old expiry, now) + type duration (**consistent with INV-12**); creates a **`MembershipFee` (pending)** and a `MembershipRenewal`; **updates `members.membership_expires_at` in place**; fires `MembershipRenewed`.
- **The renewal takes effect immediately, before payment, with no organisation decision.**
- Accepts a `membership_type_id` and charges that type's fee, but **does not change the member's type**.
- Kernel: **no Renew fact** (no `Renew` in `Domain/Organisational/`); `MembershipAct` has only `Admit`, `End` (`Application/Organisational/Ports/MembershipAct.php`).
- Tests: `tests/Feature/Membership/MembershipRenewalTest.php` (7 tests), `tests/Unit/Models/MembershipRenewalTest.php`.

### 2.3 M-3 Read own status (FACT)

- `MembershipDashboardController::index` → `getMemberData` (`app/Http/Controllers/Membership/MembershipDashboardController.php:140-184`), computed in the controller.
- Subject: `auth()->id()` (`:145`, `:149`), so no cross-person parameter. Good.
- Returns: stored `status`, `expires_at`, days left, **pending fees sum** (`:182`), `can_self_renew`, own applications (`user_id` only, `:149`).
- **An organisation user without an owner/admin/commission/member role gets 403** (`default => abort(403)`, `:101`), so an applicant who is not yet a member may be unable to see their own application.
- **A public applicant has no account, so no status access** until approval links one.

### 2.4 Kernel facts relevant to all three (FACT)

- `AdmitMember` carries `declarationRef` (nullable), `idempotencyKey`, `decisionDate`, `actor` (`Application/Organisational/AdmitMember.php`). `AdmitMemberHandler` records `Provenance::decision(actor, evidence, declarationRef)` (`AdmitMemberHandler.php:54`). **No guard requires a declaration** (BD-CONSENT, REG:43, a product decision REG:158).
- `AuthorityCheckPort::check(tenant, actor, act)`: "Competence is Governance's (DS-4). Membership only records the returned evidence" (`Ports/AuthorityCheckPort.php`).
- `InterimRoleAuthority`: Admit mirrors legacy owner/admin; **End is denied for every actor** (G-1 interim) (`Infrastructure/Organisational/InterimRoleAuthority.php`).
- `IdempotencyKey`: "a retry with the same key must not record a second fact" (`Application/Organisational/IdempotencyKey.php`).

## 3. M-1 Submit membership application

**A. Contract (PROPOSED)**

| | |
|---|---|
| Operation | `SubmitMembershipApplication` (command; one handler for both paths and every channel) |
| Purpose | a person declares the wish to join and supplies the information the organisation asks for |
| Actor | applicant: an authenticated person **or** an unauthenticated contact |
| Inputs | tenant · applicant (`PersonRef`, or contact e-mail plus PD-M2 confirmation state) · requested membership type (optional; required only if the organisation lets applicants choose) · application data · **idempotency key** |
| Output | application reference · status `submitted` (or `awaiting_confirmation`, PD-M2) |
| Preconditions | organisation uses full membership (FACT: Election-Only organisations have no membership, `VoterSourceStrategy.php:18-28`) · the applicant is not currently a member · no open application by the same applicant |
| Postconditions | one application recorded; `ApplicationSubmitted` raised; **no membership fact** |
| Failures | `AlreadyMember` · `OpenApplicationExists` · `TypeNotOffered` · `InvalidData` · `IdempotencyKeyConflict` (same key, different content) |

**B. Domain model**
- **Aggregate:** the existing `Application` (`Domain/Application/Application.php`). It needs the applicant to be either a `PersonRef` or a contact (today it requires a user id, so the public path cannot use it).
- **Invariants owned here:** legitimate transitions only (submitted → approved | rejected | expired); a decided application is immutable.
- **"One open application per applicant per organisation"** is a set rule across aggregates, enforced by the handler under a uniqueness guarantee (database constraint on normalised e-mail or `PersonRef` plus open status). No new aggregate.
- **"Already a member"** is read from Membership's own facts: `IsMember(p,o,now)` (RA:104) after cutover, the legacy record before.
- **Not in the domain:** rate limits, honeypot, CAPTCHA, CSRF, sessions. These are channel abuse controls.

**C. Application layer**
- **One handler** replaces the controller logic of both paths. Controllers only map the request to the command and the outcome to a response.
- Transaction: the application row and the outbox entry for `ApplicationSubmitted`. Mail is sent by **listeners**, outside the transaction.
- The transaction is opened by a port (unit-of-work), not by a facade in the application layer (CLAUDE.md layer rules).

**D. Persistence and consistency**
- Membership owns the application record, including the declaration text and its timestamp.
- Identity owns accounts. **Submission creates no account.**
- Idempotency: the key is stored with the application; the same key returns the same reference. This matters on phones: retries on unstable networks.

**E. The decision (desktop; defines what the participant later sees)**
```
Officer ─▶ DecideApplication(approve, type, effectiveAt, key)
          ├─▶ AuthorityCheckPort.check(tenant, actor, Admit) ──▶ Governance (interim: owner/admin)
          │      denied ─▶ refused; evidence kept for audit
          ├─▶ Identity: PersonRef for the applicant (existing verified person, or a new one)
          ├─▶ AdmitMemberHandler(AdmitMember{declarationRef = applicationId, actor, …})  [Slice 4]
          └─▶ Application.approve(factId)   — same transaction as the Admit (BD-SYNC transactional)
Finance ◀─ event (period opened) — Finance decides any charge; Membership creates no fee
```
- **Rejection** is also a decision. PROPOSED: the same port, with a new act (`RejectApplication`), records authority evidence on the application. Who may reject is Governance's (unknown; interim mirrors legacy).

**F. Failures and edge cases**
- A retry after a network drop gives the same reference.
- Same e-mail with different capitalisation is treated as a duplicate.
- An application submitted while the person is already a member is refused.
- An application expires after the organisation's period: legacy uses 60 days fixed (`PublicMembershipApplicationController.php:75`), which is an organisation rule (D-1b), UNKNOWN for NRNA.
- **Consent:** a public application in someone else's e-mail must never become that person's declaration (PD-M2).

**G. Audit:** who declared what and when (the declaration, `recordedAt`); who decided, under which authority evidence (RA:72: actor, capacity, ruleRef, policyVersion, result, checkedAt).

## 4. M-2 Renew membership

**A. Business meaning (RATIFIED + UNKNOWN)**
- RATIFIED: Renew "grants a term segment" to a **period** (RA:59).
  - It never changes BaseClaim, so never coverage (RA:92).
  - A late renewal preserves the lapse gap (INV-12, RA:123).
  - Lapse is not an ending (INV-3).
  - After an End there is nothing to renew: re-entry is a new Admit (BD-1, REG:36).
- UNKNOWN (organisation rules, D-1b):
  - who may renew;
  - whether a member's renewal takes effect on request, on payment, or on acceptance by the organisation;
  - the renewal window;
  - the term length per type.
  - The register lists **no authority** for renewal (REG:104, guard "—").

**B. Contract (PROPOSED)**, split into the participant act and the fact:

| | `RequestRenewal` (participant, phone) | `RecordRenewal` → Renew fact |
|---|---|---|
| Actor | the member, for their own membership | the rule (automatic), or an officer (desktop), depending on the organisation rule |
| Inputs | tenant · own `PersonRef` (implicit) · idempotency key | membership · period · term from/until · provenance (**rule** or **decision**) · key |
| Preconditions | `InLifetime` of the current period (not Ended) · inside the organisation's window | same, under the expected aggregate version (RA:136) |
| Output | `renewed` (term until) **or** `pending` (awaiting payment or acceptance) | appended fact; `TermRenewed` event |
| Failures | `NotAMember` · `PeriodEnded` (re-apply instead) · `OutsideWindow` · `RenewalPending` | `ConcurrentModification` (reload, recompute, retry) · `NotAuthorised` |

- **If the organisation rule is "immediate":** `RequestRenewal` records Renew directly with **rule provenance** (ratified provenance kinds: decision | rule | attested, RA:72). This reproduces today's behaviour correctly.
- **If payment or acceptance must come first:** the request is held, and the Renew is recorded when Finance reports settlement, or when the officer decides.
- **A type change is not part of Renew.** It is `ChangeType` (BD-TYPE). Today's renewal form, which accepts a type it never applies, is a defect to remove.

**C. Fees and FeePaid (RATIFIED / N-5)**
- Renew creates **no** fee. Finance derives charges from term segments ("fees reference periods", RA:155) and owns settlement.
- `Standing` is Membership's **derived** predicate from Finance evidence (RA:46, RA:109).
- So "renewed but not paid" is a legitimate state: `InForce` with Standing not met. Whether Election cares is Election's rule (`PBDIGIT-336`).
- Payment pending, rejected, exempt or recorded later changes **Standing**, never the term. Exception: an organisation whose rule makes the Renew wait for settlement.

**D. Consistency:** BD-SYNC transactional (RA:172); one Membership aggregate, version-checked; idempotency key per request.

**E. Gap:** the kernel needs a Renew fact and an authority act (if the rule is a decision) in the step-6 slice. **Until then the legacy renewal stays as it is.** It already works in a phone browser; extending it would be rework (PD-M1).

## 5. M-3 Read own membership status

**A. Contract (PROPOSED):** `GetMyMembershipStatus(tenant)`. The subject is the authenticated `PersonRef` supplied by the channel's authentication; **there is no member id parameter**. Cross-person access is impossible by construction.

**B. Content and sources**

| Shown | Source (owner) |
|---|---|
| member yes/no; in force, lapsed, suspended, ended | resolved predicates `IsMember`, `InForce`, `Lapsed`, `Suspended`, `Ended` (Membership, RA:98-104) |
| current term until; current type; member since | `TermCovered` segments; `CurrentType`; `CurrentAdmissionDate` (display = current admission, BD-TEN, REG:44) |
| own open or decided applications | Membership application record (own only, matched by `PersonRef`) |
| whether self-renewal is possible now | the organisation's renewal rule (unknown; legacy: 90-day platform setting) |
| dues standing, amount due | **Finance** evidence, shown as Finance-provided |

**Not shown:**
- voting eligibility (Election's; R-11 is an Election query);
- unit belonging (Organisation's);
- who decided, beyond what the organisation chooses to disclose;
- internal ids and authority-evidence internals;
- any other person's data.

**C. Source switch:** one port, two adapters.
- Legacy `members` before cutover.
- The C-5 projection after cutover. It is rebuildable, and maps resolved predicates to the status vocabulary (GATE C-5).
- Chosen per organisation by the C-1 flag.
- This is a **projection read**, not a command-side load.

**D. Edge cases:**
- an applicant with no organisation role must still see their own application (today 403);
- a public applicant without an account: PD-M4;
- an ended member sees "ended" and the way to re-apply;
- a lapsed member sees "lapsed" and the renewal option.

## 6. Cross-operation model and consistency

- **No new aggregate.**
  - Membership (D1, RA:138) holds facts.
  - `Application` holds the declaration and its outcome.
  - The status query is a read model.
- **Every write** is one handler, one transaction, an idempotency key, and the aggregate version for Membership facts (RA:136).
- **Events:** `ApplicationSubmitted`, `ApplicationDecided`, period opened, `TermRenewed`. Consumers are notifications, Finance and the C-5 projection; each is justified by a consumer.
- **Channel neutrality:** an Inertia controller and any future channel adapter call the same handlers. Channels own authentication, CSRF, rate limits, presentation and retry keys.

## 7. Context ownership and integration

| Context | Provides to these operations | Must not be done by Membership |
|---|---|---|
| Governance | `AuthorityCheckPort` (Admit, Reject, Renew if a decision) | deciding competence (interim adapter only) |
| Identity | `PersonRef`; verified e-mail; account creation | creating `User` rows in Membership or controllers |
| Finance | charges from periods and terms; settlement; standing evidence | creating `Fee`/`MembershipFee` (today: approval and renewal) |
| Organisation | none for these operations; address ≠ belonging (DD-6) | — |
| Election | consumes `IsMember`, `Suspended`, `CurrentType`, tenure (RA:154) | eligibility (RA:110) |

## 8. Security, audit, failure handling

- **Authentication:** participant operations need an authenticated person, except public submission. Its abuse controls are channel concerns.
- **Mobile networks:** **3 submissions per IP per hour** (`PublicMembershipApplicationController.php:31-35`) will refuse legitimate applicants who share a carrier IP (carrier-grade NAT; see the IP problem in PBDIGIT-315). That is a channel concern to revisit, not a domain rule.
- **Consent (PD-M2):** today a public application in an existing user's e-mail is attached to that user at approval (`MembershipApplicationController.php:220-221`).
- **Data protection:** the status query returns only the subject's data; application data is visible to the applicant and to authorised officers only.
- **Audit:** every decision keeps authority evidence, granted or denied (`InterimRoleAuthority` records both). Every participant act keeps who, what and when.
- **Failures** are returned as typed outcomes (`CommandOutcome` / refusal codes, as the kernel does), never as exception text. Today's paths return `$e->getMessage()` to the user (`MembershipApplicationController.php:121,319,367`).

## 9. Test strategy (invariants, not controller responses)

| Level | Test |
|---|---|
| domain | application transitions; a decided application is immutable |
| application | one open application per applicant (both paths, case-insensitive) · already-member refused · same key returns the same reference, different content conflicts · no account is created on submission · a mail failure does not lose the application (outbox) |
| application (Slice 4) | approval without granted authority is refused and evidence is stored · Admit carries `declarationRef = applicationId` · application approved and Admit committed atomically · **Membership writes no Finance table** (architecture test) |
| kernel (Renew slice) | late renewal keeps the gap (INV-12) · Renew never changes coverage · Renew on an ended period refused · concurrent renewals: one wins, the other recomputes · rule vs decision provenance |
| query | the subject comes only from authentication (no id parameter) · two tenants, same person: isolation (CLAUDE.md rule 9) · an applicant without a role sees their own application · no eligibility, no other person's data in the output · legacy and projection adapters give the same answer for parity fixtures (GATE C-7) |
| channel parity | controllers for these operations contain no Eloquent writes (architecture test) |

## 10. Unresolved business questions

| Question | Why it matters | Options | Recommendation | If left open |
|---|---|---|---|---|
| Does a member's renewal take effect on request, on payment, or on the organisation's acceptance? (PD-M3) | defines Renew's provenance and whether a pending state exists | immediate (rule) / after payment / after decision | ask for NRNA; model all three as provenance variants | the Renew slice cannot be specified |
| Renewal window and term per type | organisation rules (D-1b); the window is platform-wide today | per organisation, per type | per organisation | NRNA's rule fixed for everyone |
| Must a public applicant confirm the e-mail before the application counts? (PD-M2) | consent (BD-CONSENT); account takeover by e-mail | confirm first / confirm at approval / no | **confirm first** | membership without consent stays possible |
| How does a public applicant see the status? (PD-M4) | R-1 on a phone; no account exists | account linked by a verified e-mail / a signed link by e-mail / decision e-mail only | **account linked by a verified e-mail** | applicants cannot follow their application |
| Who may reject, and does a rejection need reasons? | authority (Governance) | — | interim: mirror legacy; Phase 3 | rejections carry no authority evidence |
| Application expiry period, and the required application data | organisation rules | — | per organisation | 60 days and fixed fields for everyone |
| Applicant chooses the type, or the approver? | the paths differ today | — | organisation rule | inconsistent paths |

## 11. Architecture risks and contradictions

| # | Item | Class |
|---|---|---|
| X-1 | Membership creates fee records at approval and renewal; contrary to N-5 and RA:155 | contradiction with a recorded interpretation |
| X-2 | renewal mutates expiry in place; the ratified model is append-only (INV-6/14) | legacy, retired at cutover (GATE C-3) |
| X-3 | the kernel `AdmitMember` accepts no declaration; BD-CONSENT is not enforced | open product decision (REG:43,158) |
| X-4 | the two submission paths enforce different rules | defect |
| X-5 | a facade transaction in the application layer (`SubmitMembershipApplication`) | layering rule |
| X-6 | the platform-wide self-renewal window | D-1b |
| X-7 | the renewal type parameter is charged but not applied | defect |
| X-8 | the unused Membership `VotingEligibilityPolicy` | boundary (see 0.3) |

**No contradiction requires changing the ratified architecture.** This design stops at none.

## 12. Recommended sequence (recommendation only)

1. **M-1 intake:** one channel-neutral submission handler for both paths, on the application record. It does not touch membership facts, so it is independent of cutover. Precondition: PD-M2.
2. **M-3 port** with the legacy adapter, plus the applicant-without-role fix.
3. **Approval → Admit:** at Slice 4, after Slice 3 and parity (GATE §6), with Identity and Finance moved out of the controller.
4. **Renew:** the step-6 lifecycle slice, after PD-M3; the legacy renewal is left unchanged until then.
5. Each step needs its own EP-01 plan and approval.

## 13. Principal decisions required

| # | Decision | Recommendation |
|---|---|---|
| **PD-M1** | Sequencing: build approval and renewal channel-neutral on the **ratified kernel** (wait for cutover), or on the **legacy model now** | **kernel**; only intake (M-1) and the status port (M-3) now |
| **PD-M2** | A public applicant must confirm the e-mail before the application counts as their declaration (BD-CONSENT) | **yes** |
| **PD-M3** | NRNA's renewal rule: immediate, after payment, or after the organisation's acceptance | Principal knowledge needed |
| **PD-M4** | How a public applicant follows the status | **account linked by a verified e-mail** |

## Correction note (2026-11-09, MOBILE-STRATEGIC-01)
- **PD-M4 is withdrawn.** Its recommendation ("account linked by a verified e-mail") conflicts with **D-75** (`docs/publicdigit/reviews/2026-11-09-identity-xd3-arb-act.md` §2.6): no linking by matching e-mail; linking needs the organisation-issued claim code. A public applicant follows the application after claiming an account with that code.
- **§7, Identity row, corrected:** Identity supplies the organisation-specific `PersonRef` and accounts only. Contact details, including the applicant's e-mail, are **organisation-local** (Session B model §31; owner pending INV-AFF-1). E-mail confirmation (PD-M2) proves control of a contact address, not identity.
- **§2.1 finding strengthened:** approval reusing an existing account by e-mail (`MembershipApplicationController.php:220-221`) conflicts with D-75, not only with BD-CONSENT.
Text above unchanged.
