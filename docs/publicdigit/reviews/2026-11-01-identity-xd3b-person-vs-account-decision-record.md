# XD-3b: Person vs login account, decision record

**Date:** 2026-11-01 · **Recorded by:** Claude (DDD architect / requirements engineer, strategic discovery)
**Status:** **PROPOSED, pending governance approval.**
- The business part ("must people without an account be supported?") was answered by the Principal on 2026-11-01: **yes**.
- The architectural part (B2, and what `PersonRef` means) needs ARB approval.
- This record is **not** an approval to implement anything.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.
**Baseline:** branch `election-flow-updated`, HEAD `576934d29`.
**Related:** `2026-11-01-identity-xd3a-person-identity-scope-decision-record.md` (XD-3a, PROPOSED; its option A is a candidate pending OQ-3a-4). XD-3b is the mapping half of XD-3. It holds independently of XD-3a's final scope.

---

## 1. Decision

**Question.** Must PublicDigit be able to record a person (member, applicant, appointee, external adviser) who has no login account?

**Answer (Principal, 2026-11-01): yes. Selected option (proposed): B2.**
- **Person** (a human) and **Account** (a credential used to authenticate) are separate concepts, both owned by Identity.
- A Person has **zero or one** Account; every Account belongs to exactly one Person.
- **`PersonRef` = a `PersonId` issued by Identity, never the account id.**
- Whether a person needs an account to vote or to act online is a rule of the owning domain (e.g. Election), not of Identity.

**Not selected:**
- **B1** (Person = Account) is rejected.
- **B3** (several accounts per Person) needs a separate later decision.

## 1a. Reconciliation with the preferred direction "option C" (2026-11-01; not decided)

**K3: amendment to §1, applies if option C is adopted.**
- `PersonRef` becomes **a per-organisation opaque reference issued by Identity**, resolvable to the internal `PersonId` **only by Identity**. It is no longer "the `PersonId` itself".
- The account still never serves as the identity.

**Import (Principal clarification, 2026-11-01):**
- When an organisation imports a person who has not registered, Identity creates or resolves a Person and issues that organisation's `PersonRef`.
- **No User affiliation with PublicDigit is created at import.** It is created at registration (BR-2, BR-8, Decision C DC-R1).
- At registration, the account is linked to the existing Person, through the organisation-issued claim code of §2a.

**Duplicates:**
- Independent imports of the same human by two organisations produce **separate Persons**. Under §2a Identity holds no descriptive data, so it cannot recognise them at import.
- Resolution and merging follow an **explicit Identity process** with sufficient verification (C-5).
- **Election-related records need special care**, e.g. two voter records for one human in the same election must never yield two votes. That rule belongs to Election.
- **Open policy:** when one account redeems claim codes belonging to two different Persons, is that sufficient evidence for a merge, or does it go to review? [R]: review, at least where election records are affected.

**K4: GENUINE CONFLICT (open, owner PO + ARB + data protection).**
- §2 says the central registration of a person without an account must **not** record which organisation created them.
- Under option C with Person creation **at import**, Identity's mapping `person_id → (organisation, PersonRef)` records exactly that, for people who never registered or consented.
- **Resolution options:**
  - **(i) Eager:** creation at import, as clarified. The §2 condition is dropped, and the mapping of unregistered persons goes to data-protection review.
  - **(ii) Lazy:** the organisation holds a local reference at import, and Identity creates the Person and the mapping only when the person redeems the claim code, i.e. registers. This contradicts the import clarification.
  - **(iii) Eager, but the mapping for unregistered persons is held so that it cannot be used for sign-in routing or listing.** Mechanism to be designed.
- **Proposed change (2026-11-01, INV-AFF-1, PROPOSED):**
  - Under mechanism **L2** (each organisation's `PersonRef` computed from the internal `person_id` and the organisation id with a secret key), Identity can create the Person at import and give the organisation its reference **without storing which organisation it is for**.
  - That would satisfy both the import clarification (eager) and the §2 condition.
  - **K4 stays open** until a mechanism is chosen.
- **Evaluation finding relevant here:** with "registration creates a Person when no claim has bound one", **every claim made after registration triggers a merge** (g15 D6: 100 of the 100 enumerated sequences that contain a successful claim).
  - Merges would be routine, not exceptional.
  - The explicit merge process (BR-11) therefore needs to separate **claim-binding** (the account proves possession of the organisation's code) from a **duplicate merge** (two imported Persons). Alternatively, registration could start from the claim code, so no second Person is created.
  - See the evaluation, item 6.

## 2. Conditions and constraints

- The five mandatory conditions of XD-3a (C-1…C-5) apply.
- **Identity matching:**
  - Equal email addresses or similar names do **not** establish that two records are the same human.
  - Person equivalence and merges happen only through the explicit Identity act (C-5, ratified DS-3).
  - The live import rule "skip when the email exists" (`docs/publicdigit/membership/MEMBER_IMPORT_GUIDE.md:120`) is therefore an identity-matching policy. It needs an explicit Identity rule before it is carried forward.
- **Registering a person without an account centrally must not record which organisation created them**; otherwise the affiliation index forbidden by C-4 is rebuilt. Option D (XD-3a) is relevant here.
- **Migration:** each existing account may initially be given its own `PersonId` as a *mapping*. That does not make the account id the identity.
- No change to, or duplication of, the Membership implementation is authorised. Membership already refers only to `PersonRef`.

## 2a. OQ-3b-1: where personal data lives (answered 2026-11-01, PROPOSED)

**Selected: B, organisation-local personal data.**
- The central Identity record of a Person is a **bare `PersonId`**.
- Name, contact details and other descriptive data live in each organisation's database. That organisation is the source of its members' data.
- An Account holds only its own credential data (e.g. a login email).
- **Linking an account to an existing Person** uses an **organisation-issued one-time claim code or invitation** tied to the `PersonId`. Name or email matching and central search are not used.
- **Option C** (minimum attributes held centrally) needs a later decision that names the Identity function requiring each attribute (e.g. legal identity verification, OQ-3b-3), with data-protection review per attribute.
- **Option A** (a central Person record with name and contact) is not selected: it conflicts with conditions C-2 and C-4 of XD-3a.

**Accepted trade-off:** a person in two organisations has two separately maintained profiles. Consent-based profile sharing may be decided later.

**Migration consequence:** all personal data sits on `users` today: name parts, gender, region, country, state, street, house number, postcode (`users` migrations). `members` and `organisation_users` hold none.
- Option B moves the source of personal data into organisation databases. This is a real data migration, not a column change.
- It reverses the earlier multi-database plan, in which organisation databases hold read-only copies of central user data (`docs/publicdigit/brainstorming/multi-database-system/07-authentication-and-identity.md:16-17`).

## 3. Open questions

| # | Question | Owner |
|---|---|---|
| OQ-3b-1 | What minimum data, if any, does the central record of a person without an account hold? | **Answered (Principal, 2026-11-01): option B, PROPOSED.** See §2a. |
| OQ-3b-2 | How do membership-record retention and a person's request to delete their account interact? | PO + legal |
| OQ-3b-3 | Do some organisations require verified identity (identity checks) before membership or appointment? | PO |
| OQ-3b-4 | When, if ever, is B3 (several accounts per person) needed? | PO / ARB |

## 4. Evidence

**Ratified:**
- Person = a human, owned by Identity (`2026-10-08-membership-architecture-ratified.md:41`).
- Membership refers to people only through `PersonRef` (`2026-10-08-membership-final-implementation-blueprint.md:24,64`).
- No ratified document defines `PersonRef` as an account id.

**Earlier theory, not ratified:** Membership persists when the account is removed (INV-7, `2026-10-08-membership-architecture-final.md:297`); a public application channel without an account (`2026-10-08-e0a2-membership-development-report.md:89`).

**Live implementation (a person is an account today):**
- `users.email` is unique and `password` is required (`database/migrations/2026_03_05_000002_create_uuid_users_table.php:16,18`).
- `members.organisation_user_id` is required and deleted along with its parent (`2026_03_07_145529_create_members_table.php:17,29`).
- `membership_applications.user_id` is required and deleted along with the account (`2026_04_03_155710_create_membership_applications_table.php:17`).
- `organisation_participants.user_id` (staff / guest / election committee) is required.
- Committee assignment validates `exists:users,id` (`app/Http/Controllers/Committee/CommitteeMemberController.php:30`).

**The need is already visible:** imports create placeholder accounts with random passwords (`app/Services/OrganisationUserImportService.php:322`; `app/Services/VoterImportService.php:274`; `app/Contexts/Membership/Application/Services/TenantUserProvisioningService.php:191-194`).

## 5. Consequences

- **Business:** members without email or internet access, and external advisers, can be recorded. Closing a login does not erase membership history (subject to OQ-3b-2).
- **DDD:** Identity owns two concepts, Person and Account. Membership is unaffected.
- **Multi-database:** the central record of a person without an account can be a bare identifier; descriptive data stays in the organisation database.
- **Security:** placeholder accounts (dormant credentials that can be activated by a password reset) stop being necessary.

## 6. Traceability

- Strategic discovery conversation, 2026-11-01 (Principal answer to XD-3b; Principal Reviewer review of XD-3b agreeing with B2).
- `docs/publicdigit/reviews/2026-10-11-cross-domain-decision-round-1-sheet.md` (XD-3).
