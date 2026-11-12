# Identity context: architecture maturity and remaining work (review)

| | |
|---|---|
| **Status** | **Review report (research).** Decides nothing; ratifies nothing; does not approve I-TDD-01. |
| **Commission** | Principal DDD Architect commission "Identity Context — Architecture Maturity and Remaining Work Review", accepted by the Principal on 2026-11-10. |
| **Independence (stated first)** | **This is not an independent review.** The reviewer (Claude, same session) wrote the participant-access strategic discovery (2026-11-09) and I-TDD-01 (2026-11-09). The review is as critical as possible, but a review from outside the Claude family is still needed before anything here is ratified (as recommended earlier for Gate F, PD-2). |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `architecture/reviews/` |
| **Git state** | branch `election-flow-updated`; HEAD `754144edb`; 8 modified tracked files and 11 untracked files, none of them Identity code (mostly older session logs, other sessions' Reference Geography documents, `tests/Isolated/Pbdigit274/Database/VoteAnonymityLinkageTest.php`). I-TDD-01 and this report are uncommitted. |
| **Tests run for this review** | **None.** Database tests cannot run in the main tree (a cached configuration makes the PBDIGIT-283 guard refuse), and the clean-worktree route needs the main `.env`, which the reviewer may not use. Test results quoted below come from earlier runs on 2026-11-09, as stated. |

**Labels:** **RAT** formally ratified (an ARB act; under D-94 the Principal is the ARB) · **WD** accepted working decision · **P** proposal · **REC** recommendation · **UNKNOWN** · **PRINCIPAL** decision required · **FACT** verified in the repository on 2026-11-10.

---

## 1. Executive assessment

| Area | Verdict |
|---|---|
| **Strategic DDD** | **Substantially complete, with limited but important open decisions.** The core boundary and privacy model are ratified: the Membership act §11 says Identity "owns Person identity and identity equivalence", and the XD-3 act (D-75) adds the canonical Person, a per-organisation `PersonRef` and Person ≠ Account. Still open: K4, affiliation discovery (INV-AFF-1), the recovery and account-security policy, XD-PROV (proposed), the Identity → Election contract (context map R-03, "not established"), and a new consumer from D-90 (Geography asks whether two stewards are the same natural person). |
| **Tactical DDD** | **Early: one proposed iteration covering the participant-access slice only.** I-TDD-01 walks the full derivation chain for Person, Account and Claim, but leaves 4 seams and 4 open decisions. It does not cover merges, account lifecycle procedures, equivalence queries for other contexts, transaction and concurrency rules, error contracts, audit, or the migration of today's `users`. Not reviewed independently. |
| **Technical architecture** | **Not started.** No decision on code placement (`app/Contexts/Identity` does not exist), persistence, migration of personal data out of `users`, or the authentication integration. |
| **Implementation** | **Legacy only, and partly conflicting.** Laravel authentication works (session guard, password reset, e-mail verification, self-service soft deletion). Several live flows conflict with ratified rules: e-mail matching (`PBDIGIT-341`), registration that creates a platform-organisation role, invitation acceptance that creates a Membership record, and invitations that bind to whoever is signed in. Social sign-in was removed (`PBDIGIT-340`, `955380667` + `754144edb`). |
| **Implementation readiness** | **Not ready for the first useful slice (claim codes).** It needs P-I1, a code-placement decision, and an EP-01 plan. Two small safe steps can start now (§14). |

**Answer to the Principal's central question:** before the first useful Identity functionality (organisations giving members a claim code to get their account) can be built safely, the Principal must decide:
1. **P-I1**, when a person record is created for someone an organisation imports; the review recommends **at import** (§11);
2. where Identity code lives (**P-I11**);
3. the **interim treatment of e-mail matching** in live flows (**P-I10**).

Everything else can follow in stages.

## 2. Identity in business language

Identity answers one question: **who is this human, and can they prove it?**
- It keeps one record per real person across all organisations on PublicDigit, holding almost nothing: no name, no address, no list of organisations.
- It gives each organisation a different private reference for the same person.
- It lets a person sign in, and lets an organisation hand its own member a one-time code to connect their account.

It never decides whether someone is a member, a voter, an officer, or where they belong.

## 3. Why PublicDigit needs a separate Identity context

1. **One human, several organisations, no cross-visibility.** A diaspora member may belong to a party branch, a regional association and a federation. Party membership can be politically sensitive. D-75 forbids any context except Identity from finding a person's other organisations.
2. **Accounts are not people.** Imported members, applicants and voters may have no account; a person must not be created or matched from an e-mail address (D-75 §2.6). Shared family e-mail addresses are common, so an address cannot identify a human.
3. **One officer must not control a member's identity.** Organisations may manage their own members, never the person's login (R-XD3c-1).
4. **D-90 already depends on it.** Reference Geography needs to know, after the fact, whether an approver and a preparer were the same natural person. Only Identity can answer that question.

## 4. Responsibilities and boundaries

### 4.1 Expected ownership versus evidence
| Responsibility | Owner | Status | Evidence |
|---|---|---|---|
| Continuous human identity, equivalence | Identity | **RAT** | Membership act §11 (`2026-10-08-membership-architecture-ratification-act.md:309-313`); XD-3 §2.1 |
| Account and authentication | Identity | **RAT** (Person ≠ Account; 0..1 Account per Person) | XD-3 §2.6; XD-3b (B2) |
| Account security and lifecycle | Identity | **RAT** for the separation of acts (R-XD3c-1…5 via XD-3 §2.7); procedures open | XD-3c; XD-3 §3 (OQ-3c-1, OQ-3c-2 open) |
| Organisation-issued claim and redemption | Identity | **RAT** as the only linking mechanism; mechanics P | XD-3 §2.6, §6; XD-3b §2a; I-TDD-01 (P) |
| Organisation affiliation | Organisation (owning organisation) | **P**: INV-AFF-1, "referred to the ARB" | `strategic-design-status.md:60, :82`; status assessment P-5 |
| Names, contact details | organisation-local | **RAT** that they are local (XD-3 §2.5); owner P (INV-AFF-1) | MAP §31 |
| Membership facts and lifecycle | Membership | **RAT** | RA |
| Belonging and structure | Organisation | **WD** (D-3, D-22, D-80) | DR §11 |
| Election eligibility | Election | **RAT** (RA:110) | — |
| Voting | Voting | **RAT** VO-1 | — |
| Authority and appointments | Governance | **WD** | MAP §33 |
| Provenance and deletion authority | organisation-local provenance | **P** (XD-PROV; Principal selected 2026-11-02; AM-1 r2 accepted) | `2026-11-02-identity-xd-prov-…-decision-record.md` |

### 4.2 Is Identity drifting into something it must not be?
| Risk | In the design (I-TDD-01) | In the code (FACT) |
|---|---|---|
| central membership register | no | **yes, in a legacy flow:** accepting an organisation invitation creates a Membership `Member` record (`InvitationAcceptanceTest::test_accepting_invitation_creates_member_record`), bypassing the ratified admission rule (RA:72) |
| central affiliation database | no (Affiliation rejected, §2.4) | **yes:** `users.organisation_id` and `user_organisation_roles` are central tables listing a person's organisations |
| person-profile database | no (Person is bare) | **yes:** name parts, gender, region, address on `users` (XD-3b §2a, migration consequence) |
| voter register | no | voter import pre-creates accounts by e-mail (`VoterImportService.php:217-226`) |
| authorisation or role management | no (WorkingContext grants nothing) | `user_organisation_roles` mixes platform access with membership: registration makes every new account a `member` of the platform organisation (`RegisterController.php:41-63`) |
| a central authority on belonging | no | registration decides belonging to the platform organisation |

### 4.3 Seven concepts that must not be merged
| Concept | Owner | Conflated today (FACT) |
|---|---|---|
| 1 Person identity | Identity | with the Account (one `users` row) |
| 2 Account authentication | Identity | with the person profile |
| 3 Organisation affiliation | Organisation | with platform roles (`user_organisation_roles`) and `users.organisation_id` |
| 4 Membership | Membership | with invitation acceptance (creates `Member`) |
| 5 Organisational access | owning organisation | with the role `member` (person C in the 2026-11-09 journey had the role and no membership) |
| 6 Permission for an operation | each context's policy | per route middleware (`EnsureOrganisationMember`) on platform roles |
| 7 Governance authority | Governance | separate (AuthorityCheckPort) |

**The design keeps them apart; the code does not.** The gap is a migration problem, not a modelling gap.

## 5. Strategic DDD maturity

| Item | Classification | Evidence |
|---|---|---|
| Business purpose | complete, ratified | act §11; XD-3 |
| Bounded-context boundary | complete, ratified (core); detail open | act §11; context map R-01 |
| Relationship with Organisation | proposed (INV-AFF-1) | context map R-02 **stale**: it still says PROPOSED pending XD-3a/b/c (last updated 2026-11-02; XD-3 accepted 2026-11-09) |
| Relationship with Membership | ratified (ownership); `PersonRef` form ratified via XD-3 §2.2 | R-01; XD-3 |
| Relationship with Governance | partly: officers issue claims with authority (P); R-XD3c-3 requests (RAT) | I-TDD-01 §5 |
| Relationship with Election and Voting | **open**: R-03 "not established"; Voting must never receive identity (D-44, RAT VO-1) | context map R-03 |
| Person vs Account | complete, ratified | XD-3 §2.6 |
| `PersonRef` and cross-organisation privacy | complete, ratified (principle); mechanism open (L1/L2) | XD-3 §2.2–§2.4, §3 |
| Affiliation discovery | partly investigated (evaluation 2026-11-01); open | INV-AFF-1 |
| Working organisation selection | proposed | discovery A-2; I-TDD-01 |
| Registration and onboarding | proposed (claim-first) | I-TDD-01 §0.2 |
| Imported persons who never registered | **open (K4)** | XD-3 §3; XD-3b K4 |
| Account recovery | partly (own address: P); without address: **open** | P-I3 |
| Account security and lifecycle | separation ratified; policy open | OQ-3c-1, OQ-3c-2 |
| Shared devices | proposed | I-TDD-01 A-6; P-I3 |
| Unregistered applicants and voters | partly (applicants: claim after admission, P; voters: K4) | — |
| Suspension and deletion | separation ratified; deletion authority proposed (XD-PROV) | R-XD3c-2…5 |
| Duplicates and merges | principle ratified (C-5); review policy open | XD-3b §1 |
| Legal and privacy responsibilities | **open** (OQ-3a-1, OQ-3b-2) | XD-3 §3 |
| Context mapping and integration contracts | R-01 ratified outline; R-02 stale; R-03 open; **D-90 equivalence query not mapped** | context map |
| Identity in responsive web and PWA journeys | proposed | mobile baseline; discovery |

**Verdict: substantially complete, with limited open decisions** (K4, INV-AFF-1, the recovery and security policy, XD-PROV, R-03, the D-90 equivalence contract, legal). The main boundary being identified is not the same as completion. The four relationship rows are where the remaining strategic work sits.

## 6. Tactical DDD maturity (critical review of I-TDD-01)

### 6.1 What it does well
- It walks the full derivation chain for three aggregates, with rejected candidates recorded (ASP).
- It identifies the main takeover path (INV-ID-6) and separates authentication from e-mail matching (INV-ID-3).
- It isolates open decisions as seams instead of guessing.

### 6.2 Aggregates
| Aggregate | Assessment |
|---|---|
| Person | Justified (APP: protects continuity under merge, C-5). Thin by design. Whether `anchored` belongs to it is **P-I7**. |
| Account | Justified (protects 0..1 binding, credential authenticity, lifecycle authority). **Gap:** no rule for what happens to the Account when its Person is merged (does the surviving Person keep both? one must go, given 0..1). |
| Claim | Justified (single use, expiry, revocation, no re-binding). **Gap:** the issuer's authority to issue for a given person is delegated to Governance's port without saying which competence (who in NRNA may issue codes). |
| WorkingContext (rejected) | Sound rejection. |
| Affiliation (rejected) | Sound; follows C-4. |
| Merge (deferred) | Acceptable for the first slice; **must exist before eager import is used at scale** (eager creates duplicates). |

### 6.3 Invariants
| Invariant | Finding |
|---|---|
| INV-ID-1 / -2 | Consistent with C-4 and C-5. **Gap:** INV-ID-2 says references "resolve to the surviving Person", but under L2 (references derived from `person_id`) a merge changes nothing an organisation holds; the resolution rule depends on P-I2. |
| INV-ID-3 | Correct and enforceable (a repository surface that offers no lookup except authentication). |
| INV-ID-4 | Consistent; requires that plain registration create an **unanchored** Person (P-I7). |
| INV-ID-5 | Consistent with R-XD3c-2. **Gap:** self-service deletion (`DeleteAccountController`, FACT) is an act with authority (the holder) but no recorded reason or audit; the design does not say whether the holder's own request needs a reason. |
| INV-ID-6 | **The most important rule; enforceable only if "Person already has an Account" is known at redemption.** It is under eager K4 (subject = `PersonId`); under lazy K4 Identity cannot know before creating the Person, so the rule changes shape. P-I1 therefore affects INV-ID-6 directly. |
| INV-ID-7 … -9 | Standard and enforceable. **Gap:** no rule on how the code reaches the person (organisation channel), and no expiry length (organisation rule or platform default? D-1b says organisation rules are not platform defaults). |

**Missing invariants (REC to add, each with its business reason):**
- one active, unexpired Claim per (organisation, subject), so a forgotten code doesn't leave a live duplicate;
- a merge never joins two Persons that hold rights in the same election without Election's check (XD-3b: "two voter records … must never yield two votes");
- an equivalence answer for D-90 discloses only "same / not same", only for a recorded governance purpose, and is logged.

### 6.4 Value objects, events, commands, repositories, services
- **Value objects:** each is tied to an invariant (VODP holds). `SessionPolicy` is a policy object, not domain truth; acceptable as an application-level value.
- **Events:** private by audience, as required. **Missing:** an event or record for **equivalence queries** (D-90).
- **Commands:**
  - missing `ConfirmPersonsDistinct` / `AnswerEquivalenceQuery` for D-90;
  - `RecoverAccess` undefined (P-I3);
  - the holder's own deletion request is not distinguished from Identity's deletion.
- **Repositories:** minimal (RMSP holds). `findForAuthentication` must be the only address-based method.
- **Ports:** `ReferenceIssuer` and `AffiliationDiscovery` are named, but **contracts are missing**: inputs, outputs, failure behaviour (what the person sees when discovery fails), timeouts.

### 6.5 Not addressed at all (tactical TODO)
- transaction boundaries (redemption across Claim, Account and Person in one unit);
- concurrency (two redemptions of the same code; two claims for one Person at once);
- error contract (one neutral message for every failure, including unknown codes, expired codes, already-bound Persons and throttling);
- audit (which acts are audited, where, retention);
- cross-context failure handling (Governance authority check unavailable; the organisation context unavailable);
- the mapping of today's `users` rows to Account and Person (XD-3b migration note), and where personal data goes.

**Verdict: tactical design is a first proposed iteration of one slice, not a completed tactical architecture.** It needs a second iteration after P-I1, and an independent review.

## 7. Technical architecture and Keycloak

### 7.1 Today (FACT)
- **Laravel session authentication:**
  - `LoginController.php:38-73`: e-mail and password, a rate limiter, a verified e-mail required;
  - `PasswordResetController`: reset links by e-mail;
  - `VerificationController`;
  - `PasswordUpdateController`;
  - `DeleteAccountController.php:15-33`: password-confirmed soft delete, then logout.
- **Session:** 120 minutes, not ended on close (`config/session.php:34-36`).
- **No `app/Contexts/Identity`**, no Identity domain code.

### 7.2 Keycloak
| Question | Answer |
|---|---|
| What must stay in PublicDigit Identity whatever the technology | Person and `PersonId`; `PersonRef` issuance and resolution; claim codes and INV-ID-6; merges (C-5); the equivalence answer (D-90); no cross-organisation disclosure (C-1…C-4); Account ↔ Person binding; lifecycle authority and audit (R-XD3c-2) |
| What could be delegated to Keycloak | credential storage and checking, password policy, password reset, a second factor, session and token issuance (OIDC), brute-force protection |
| Integration boundary needed | an authentication port: Keycloak answers "this credential belongs to subject S"; PublicDigit maps S to an Account; nothing about Persons, organisations or claims goes to Keycloak |
| Does it solve a business-domain problem? | **No.** None of the open decisions (K4, INV-AFF-1, recovery, merges) is about credential technology. |
| Risks it would add | Keycloak's "first broker login" and e-mail-based user matching **must be switched off** (D-75). Its organisation feature would create a central affiliation index (C-4) if used. |
| Cost for a solo developer | a separate Java service, its own database, upgrades and security patches, backups, theme and translation work (de, en, np), redirect-based login inside the PWA, and logout and session coordination with Laravel |
| Recommendation | **Exclude for now, and keep authentication behind a port** so it can be swapped. Re-evaluate only on a concrete need: a second factor for officers at scale, single sign-on with outside systems, or federation. |

## 8. Existing implementation and evidence

| Mechanism | Evidence (FACT) | Classification |
|---|---|---|
| E-mail and password sign-in | `LoginController.php:38-73` | implemented; tests exist (`tests/Feature/Auth/*`, `AuthenticationTest`), **not run in this review** |
| Password reset by own address | `PasswordResetController`; `PasswordResetTest` (5 tests) | implemented; not run here; consistent with INV-ID-3 |
| E-mail verification | `VerificationController`; `EmailVerificationTest` | implemented; not run here |
| Registration | `RegisterController.php:41-78`: sets `organisation_id` to the platform organisation and creates a `member` role | implemented; **conflicts** with Person ≠ affiliation (§4.2); pinned by `RegistrationAndLoginTest`, `LoginOrganisationAccessTest` |
| Self-service account deletion | `DeleteAccountController.php:15-33` (soft delete); `DeleteAccountTest` | implemented; no reason or audit; Account = Person row |
| Suspension, reinstatement | none | not implemented |
| Current organisation | `users.organisation_id`; session `current_organisation_id`; login landing (2026-11-09 journey: lands on the organisation page) | legacy; becomes `WorkingContext` |
| Voter invitation | `VoterInvitationController.php:13-62`: single-use, expiring token on a pre-created account; `VoterInvitationSetPasswordTest` (2 tests) | partly the claim intention; **conflicts** with INV-ID-4 (account pre-created by e-mail) |
| Participant invitation | `ParticipantInvitationController.php:103-125`: binds to whoever is signed in; `ParticipantInvitationAcceptanceTest` (6) | partial; lacks INV-ID-6 |
| Member invitation | `OrganisationMemberInvitationController.php:110-190`: e-mail comparison; creates a `Member` (`InvitationAcceptanceTest`, 6) | **conflicts** with INV-ID-3 and with Membership admission |
| Membership approval | `MembershipApplicationController.php:221-224`: reuse or create by e-mail | **conflicts** (`PBDIGIT-341`) |
| Voter import | `VoterImportService.php:121, :172, :217-226` | **conflicts**; discloses "Existing user" (`PBDIGIT-341`) |
| Participant add | `OrganisationParticipantController.php:73` | **conflicts** (`PBDIGIT-341`) |
| `ImportUsers` command | `ImportUsers.php:565` | **conflicts** (`PBDIGIT-341`) |
| Social sign-in | removed (`955380667`); guarded by `tests/Feature/Routes/SocialSignInRemovedTest.php` (`754144edb`) | **removed; test RED → GREEN 2026-11-09** in a clean worktree |
| Personal data | on `users` (XD-3b §2a) | legacy; conflicts with organisation-local data (RAT §2.5) |

**Test coverage of the ratified rules (D-75, R-XD3c):** none. The existing tests **pin legacy behaviour that conflicts** with them (registration role, invitation-created membership, pre-created voter accounts). They will need deliberate replacement, not "fixing", when the flows change.

## 9. Participant journeys

| # | Journey | Owner(s) | Identity's part | Today | Missing | Risk | Design covers? |
|---|---|---|---|---|---|---|---|
| 1 | returning participant signs in | Identity | authenticate | works (browser, 390 px verified) | — | session on shared phones | yes |
| 2 | several organisations | Identity + each organisation | discovery, selection | one "current" organisation | discovery (P-I2) | cross-organisation leak | partly (seam) |
| 3 | imported, never registered | organisation + Identity | claim | pre-created account + token | claim flow; K4 | e-mail matching | **blocked on P-I1** |
| 4 | approved applicant | Membership + Identity | claim after admission | e-mail reuse | claim issuance at approval | e-mail matching | yes, after P-I1 |
| 5 | forgot password | Identity | reset by own address | works | — | — | yes |
| 6 | lost the login address | Identity | recovery | none | `RecoverAccess` | takeover via an organisation | **open (P-I3)** |
| 7 | shared family phone | Identity (sessions) | session policy | 120-minute session, remember-me | policy | next user sees data | open (P-I3) |
| 8 | switching organisations | Identity + organisations | selection | `/my-organisations` | discovery | leak | partly |
| 9 | leaving an organisation | organisation + Membership | none (R-XD3c-5) | membership end in the kernel (unrouted) | organisation access revocation | access lingers | yes (separation) |
| 10 | account suspended | Identity | lifecycle with authority | none | everything | — | designed, not built |
| 11 | account deleted | Identity | lifecycle | self-service soft delete | reason, audit, retention (XD-PROV, OQ-3b-2) | records lost or kept wrongly | partly |
| 12 | duplicate persons | Identity (+ Election check) | merge | none | merge act, review policy | double votes | **deferred** |
| 13 | member, applicant, voter, candidate | other contexts | one Person, one Account | one `users` row per account | — | role confusion | yes (Identity grants nothing) |
| 14 | through a PWA | channel | same as browser | not built | iOS storage UNKNOWN | cached personal data | yes (mobile baseline §5.3) |

## 10. Security and privacy

| Item | State |
|---|---|
| E-mail-based account matching in live flows | **open, live** (`PBDIGIT-341`) |
| "Existing user" disclosure in the import preview | **open, live**; the smallest fix available |
| Invitation links bound to whoever is signed in | open, live (participant invitations) |
| Social sign-in defects | **closed** (`PBDIGIT-340`) |
| Personal data stored centrally on `users` | open (migration to organisation-local, owner pending) |
| Account takeover through organisation re-claims | designed against (INV-ID-6); not built |
| Account lifecycle authority and audit | not built |
| Session length on shared phones | open (P-I3) |
| Legal basis, controllers and processors | open (OQ-3a-1) |
| Member's own data served from a shared cache | **closed** (`PBDIGIT-339`, `454ae8ea7`) |

## 11. Principal decision register

| ID | Question | Current position | Options | Business consequence | Architectural consequence | Privacy and security | Depends on | REC | Blocks | Owner |
|---|---|---|---|---|---|---|---|---|---|---|
| **P-I1 (K4)** | When an organisation imports someone who has never registered, is the Person created then or when they claim? | open (XD-3 §3); Principal's import clarification of 2026-11-01 implies **at import** | **eager** (at import) · **lazy** (at claim) · eager with a mapping unusable for discovery (K4 iii) | **eager:** imported members are real Membership and Election subjects at once (Membership needs a `PersonRef` per organisation, XD-3 §2.2). **lazy:** imported people have no `PersonRef` until they claim, so their Membership facts need an organisation-local placeholder and later re-keying of recorded facts | **eager:** duplicates across independent imports, so a merge process is needed. **lazy:** INV-ID-6 changes shape; no import duplicates | **eager:** Identity knows a Person exists without consent; whether it also knows the organisation depends on P-I2 (L2 avoids it). **lazy:** less data held | **P-I2** (L2 makes eager privacy-compatible) | **eager**, with the organisation mapping decided under P-I2; accept that a merge process must follow before large imports | the claim slice; journeys 3, 4 | Principal |
| **P-I2 (INV-AFF-1)** | Who owns affiliation, and how does a person's organisation list appear at sign-in (L1 stored mapping, L2 derived)? | referred to the ARB (= Principal, D-94); L2 feasibility not established | L1 · L2 · L3 (with a B1 amendment) | journey 2 | `ReferenceIssuer`, `AffiliationDiscovery` | central index risk (C-4) | evaluation facts: organisation count, topology, peak load | obtain the evaluation's three facts before choosing; design single-organisation first | journey 2 | Principal (ARB) |
| **P-I3** | Recovery when the login address is lost; participant session policy | open (OQ-3c-1, -2) | Identity-run recovery with stronger evidence · no recovery (new account and new claims) · organisation-assisted (must not re-bind, INV-ID-6) | journeys 6, 7 | `RecoverAccess`, `SessionPolicy` | takeover; shared phones | partly P-I2 | Identity-run recovery; shorter participant sessions; no remember-me by default | journeys 6, 7 | Principal |
| **P-I7** | Is the one-bit `anchored` flag compatible with C-4? | new (I-TDD-01) | allow · forbid (then the first claim after plain registration is a merge) | none visible | registration path | one bit, no organisation | P-I1 | allow, recorded as a C-4 interpretation | absorbing a plain registration | Principal (ARB) |
| **P-I8 (new)** | The Identity → Election contract (context map R-03) | not established | `PersonRef` per organisation only · an election-specific reference | voters | Election's reference model | must not reach Voting (D-44) | P-I1 | `PersonRef` only; Election never asks for equivalence except for the double-vote check | Election tactical work | Principal |
| **P-I9 (new)** | Who may ask Identity "are these two the same natural person?" (D-90, merges) | D-90 assumes Identity can say | only for recorded governance purposes · never outside merges | Geography's approval check; election double-vote check | an equivalence query contract | disclosure risk | — | allowed only for named purposes, logged, answer "same / not same" only | D-90 enforcement | Principal |
| **P-I10 (new)** | What to do with live e-mail matching before the claim flow exists | `PBDIGIT-341` open | leave until replaced · interim: remove the "Existing user" disclosure and stop new cross-organisation reuse | privacy now | small change | live leak | — | interim fix now | nothing | Principal (authorisation) |
| **P-I11 (new)** | Where Identity code lives | none exists | `app/Contexts/Identity` (like other contexts) · keep in `app/Models` | — | layer rules (domain without Laravel) | — | — | `app/Contexts/Identity` | any slice | Principal |
| **P-I12 (new)** | What happens to the platform organisation and its automatic `member` role at registration | live (`RegisterController.php:41-63`); used for the demo | keep as demo access · replace by an Account without affiliation | demo onboarding | registration | affiliation without consent | P-I2 | keep only as demo access, never as an affiliation; decide in the claim slice | — | Principal |
| XD-PROV | Ratify organisation-local provenance and deletion authority | P | ratify · amend | deletion and retention | lifecycle | retention law | OQ-3b-2 | ratify after legal input | journey 11 | Principal |
| Merge policy | Review always, or only where election records are affected | open (XD-3b [R]) | — | duplicates | merge act | double votes | P-I1 | review where election records are affected | eager imports at scale | Principal |
| Legal | OQ-3a-1, OQ-3b-2 | open | — | — | — | — | — | before production use | production | Principal + data protection |

**Dependencies:** P-I1 ↔ P-I2 (eager is privacy-safe only with L2 or K4 iii) · P-I7 ← P-I1 · P-I3 partly ← P-I2 (recovery must know the binding) · P-I8, P-I9 ← P-I1.

## 12. Remaining TODOs

| Pri | TODO | Reason | Depends | Outcome | Principal? | Blocks build? |
|---|---|---|---|---|---|---|
| 1 | Decide P-I1 | the Claim subject and INV-ID-6 depend on it | — | Claim design fixed | yes | **yes** |
| 1 | Decide P-I11 (code placement) | layer rules | — | where code goes | yes | **yes** |
| 1 | Decide P-I10 (interim e-mail matching) | live leak | — | small privacy fix authorised | yes | no |
| 2 | Update context map R-02 (stale since XD-3) and specify R-03 | conflicting records | P-I8 | one consistent map | record update | no |
| 2 | Decide the equivalence-query rule (P-I9) | D-90 depends on it | — | contract | yes | for D-90 |
| 2 | Ratify or amend XD-PROV | deletion authority | legal | lifecycle rules | yes | for journey 11 |
| 2 | Decide P-I2 using the evaluation's three facts | multi-organisation sign-in | infrastructure facts | mechanism | yes | for journey 2 |
| 3 | I-TDD-02: transactions, concurrency, error contract, audit, port contracts, merge-and-Account rule, missing invariants (§6.3) | the tactical chain is incomplete | P-I1 | validated slice design | review | **yes**, for the claim slice |
| 3 | Independent review of I-TDD-01/02 (outside the Claude family) | self-review only | I-TDD-02 | review findings | yes (appointing a reviewer) | REC before ratifying |
| 4 | Mapping of `users` → Account and Person; migration plan for personal data | conflicting storage | P-I1, INV-AFF-1 owner | migration ADR | yes | for later slices |
| 4 | Authentication port (Laravel now, swappable) | keeps the domain independent of the technology (§7) | P-I11 | ADR | yes | no |
| 5 | Slice S-0: remove the "Existing user" disclosure | privacy | P-I10 | test: the preview never says whether an account exists | authorise | no |
| 5 | Slice S-1: pin the legal behaviour (password reset never binds; deletion is the holder's act) with tests | baseline | — | characterisation tests | no | no |
| 5 | Slice S-2: the Claim slice (single organisation) | the first member value | P-I1, P-I11, I-TDD-02, EP-01 plan | journeys 3 and 4 for one organisation | yes | — |

## 13. Maturity scorecard
No percentages: there is no defensible measure, and a number would suggest false precision.

| Area | Status | Completed | Remaining | Critical gap | Confidence |
|---|---|---|---|---|---|
| A Strategic DDD | substantially complete, limited open decisions | purpose, boundary, Person ≠ Account, `PersonRef`, privacy rules, lifecycle separation | K4, INV-AFF-1, recovery and security policy, XD-PROV, R-03, D-90 contract, legal | K4 | high (ratified records read directly) |
| B Tactical DDD | first proposed iteration of one slice | Person, Account, Claim chain; seams | transactions, concurrency, errors, audit, contracts, merges, equivalence, migration mapping; review | INV-ID-6 depends on P-I1 | medium (self-reviewed) |
| C Technical architecture | not started | — | placement, persistence, authentication port, migration | no Identity code location | high |
| D Implementation | legacy, partly conflicting | Laravel authentication; social sign-in removed | claim flow; lifecycle | e-mail matching live | high (code read) |
| E Tests | legacy behaviour pinned; ratified rules unpinned | `SocialSignInRemovedTest` | tests for D-75 rules | tests pin conflicting behaviour | medium (not run in this review) |
| F Security and privacy | mixed | 339, 340 closed | 341, invitations, central personal data | 341 | high |
| G Participant journeys | 1, 5, 13 usable; 3, 4, 6, 7, 10, 12 not | — | see §9 | journeys 3 and 4 (first member value) | high |
| H Implementation readiness | not ready for the claim slice | — | P-I1, P-I11, I-TDD-02, EP-01 plan | P-I1 | high |

## 14. Recommended next steps (small, verifiable stages)

| Stage | Content | Entry | Exit |
|---|---|---|---|
| 0 | Principal decides P-I1, P-I11, P-I10 | this report | three recorded decisions |
| 1 | Slice S-0 (import-preview disclosure) and S-1 (characterisation tests) | P-I10 authorised; EP-01 plan | tests green; no new failures; no e-mail-existence disclosure |
| 2 | I-TDD-02: a second tactical iteration for the Claim slice (§6.3, §6.5) | P-I1 | design ready for independent review |
| 3 | Independent review of I-TDD-02 | stage 2 | findings resolved; Principal accepts the slice design |
| 4 | Claim slice (single organisation; officer issues on desktop, member redeems on a phone) | stage 3; EP-01 plan | INV-ID-6…-9 pinned by tests; no e-mail lookup on the path; synthetic browser journey at 390 px |
| 5 | Membership approval issues claims (with the Membership cutover) | stage 4 | G-2 closed |
| 6 | P-I2 and multi-organisation discovery; PWA stage 2 | facts for P-I2 | journey 2 |

**Mobile:** Identity serves the phone through claim-first registration and session policy. There is no Mobile context and no mobile identity system (CP-1, I-9). The PWA should wait until stage 4, so members are not invited to install something whose sign-in still matches accounts by e-mail.

## 15. Principal decisions required now
1. **P-I1:** create the Person record **at import** (recommended) or **at claim**.
2. **P-I11:** Identity code in `app/Contexts/Identity` (recommended).
3. **P-I10:** authorise the interim fix: the import preview stops revealing whether an e-mail already has an account.

## 16. Final architectural conclusion
Identity's **strategy is substantially settled and ratified at its core**: one bare Person, a per-organisation reference, Person ≠ Account, no e-mail matching, and organisations never seeing each other. Its **tactical design is one proposed iteration** of the participant-access slice, self-reviewed and incomplete on transactions, errors, audit, contracts and merges. **Nothing of it is implemented**; the live code mixes identity, affiliation, membership and access, and still matches accounts by e-mail. The shortest safe path to the first member value (an organisation gives a member a code; the member connects on their phone) is: P-I1 → a second tactical iteration → an independent review → one small slice.

---

## Evidence appendix
- **Decisions and records:**
  - Membership act §11 (`docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md:309-313`);
  - XD-3 act (`docs/publicdigit/reviews/2026-11-09-identity-xd3-arb-act.md`, D-75);
  - XD-3a (`:106` note), XD-3b (§1 K4, §2, §2a; `:130` note), XD-3c (R-XD3c-1…5; `:91` note);
  - XD-PROV (`2026-11-02-identity-xd-prov-…`, PROPOSED);
  - affiliation evaluation (`2026-11-01-identity-affiliation-discovery-evaluation.md` §4);
  - DR §11 D-75, D-80, D-88…D-99 (D-90, D-94 relevant);
  - MAP §31, §32;
  - context map (`docs/publicdigit/architecture/context-map.md` R-01…R-03, last committed `feccc988d` 2026-11-02);
  - `strategic-design-status.md:60, :82, :102`;
  - participant-access discovery (2026-11-09);
  - I-TDD-01 (2026-11-09, uncommitted);
  - mobile baseline;
  - `PBDIGIT-339`, `-340`, `-341`;
  - `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md`.
- **Code:**
  - `app/Http/Controllers/Auth/{LoginController,RegisterController,DeleteAccountController,PasswordResetController}.php`;
  - `app/Models/User.php` (`HasUuids`, `SoftDeletes`, casts `:141`);
  - `config/session.php:34-36`;
  - `VoterInvitationController.php`; `Membership/ParticipantInvitationController.php`; `Organisation/OrganisationMemberInvitationController.php`;
  - `Membership/MembershipApplicationController.php:221-224`; `Services/VoterImportService.php`; `Membership/OrganisationParticipantController.php:73`; `Console/Commands/ImportUsers.php:565`;
  - routes list of 2026-11-09 (`DELETE user`, `login`, `register`, `forgot-password`, invitation routes).
- **Tests (read, not run here):**
  - `tests/Feature/Auth/{VoterInvitationSetPasswordTest,LoginOrganisationAccessTest,RegistrationAndLoginTest,LoginFallbackChainTest,LoginResponseTest}.php`;
  - `tests/Feature/Organisation/{InvitationAcceptanceTest,ParticipantInvitationAcceptanceTest}.php`;
  - `tests/Feature/{DeleteAccountTest,PasswordResetTest,EmailVerificationTest}.php`;
  - `tests/Feature/Routes/SocialSignInRemovedTest.php` (run 2026-11-09: RED → GREEN).
- **Git:** `754144edb` (HEAD), `955380667`, `7a8f35f17`, `bda1373a3`, `454ae8ea7`.
