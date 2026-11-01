# Decision C: the default PublicDigit organisation and the User affiliation, decision record

**Date:** 2026-11-01 · **Recorded by:** Claude (DDD architect / requirements engineer, strategic discovery)
**Status:** **PROPOSED, pending governance approval (PO + ARB).**
- The business rule in §1 is the Principal's clarification of 2026-11-01.
- Items marked [R] are the architect's recommendations, not decisions.
- This record is **not** an approval to implement anything, and it changes no ratified decision.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.
**Baseline:** branch `election-flow-updated`, HEAD `576934d29`.
**Related:** XD-3a, XD-3b, XD-3c (`2026-11-01-identity-xd3*-decision-record.md`, all PROPOSED).

**Revision history (all on 2026-11-01; the file was never committed between versions):**

| Version | Content | Superseded by |
|---|---|---|
| Draft 1 | option A (affiliation with PublicDigit), from a Principal Reviewer summary | the Principal's instruction "Choose A′" |
| Draft 2 | option A′ (a platform-level service relationship, no affiliation) | the Principal's clarification below |
| **This version** | **option A with the role User** | — |

---

## 1. Decision (Principal clarification, 2026-11-01)

**Selected option: A.**

**PublicDigit:**
- PublicDigit is a real organisation within the platform, configured as the **default organisation**.
- When a person registers, they become **affiliated with the PublicDigit organisation in the role User**.
- This is a genuine business relationship, **not** a technical fallback and **not** only a platform-level service relationship.

**User is not Member:**

| Role | Meaning |
|---|---|
| **User** | A person affiliated with PublicDigit who can use the platform, subject to the applicable access rules. |
| **Member** | A person who has met PublicDigit's separate requirements for formal membership: Membership, under rules still to be discovered and specified. |
| **Worker, Guest, Observer** | Further affiliation capacities. Observer may also exist per process, in the owning context. |

**Kept distinct:** Person · User Account · OrganisationAffiliation (including the User role) · Membership · process-specific Participation · Governance Appointment.

**Not selected:**
- **A′** (only a platform-level service relationship): the relationship with PublicDigit is an organisational affiliation.
- **B** (every Person must belong to an organisation).
- **C** (no relationship).

## 2. Rules (proposed)

| # | Rule |
|---|---|
| DC-R1 | Registration creates an OrganisationAffiliation with the PublicDigit organisation, role **User**. |
| DC-R2 | Registration never creates Membership in PublicDigit or anywhere else. PublicDigit Membership has its own eligibility and approval rules (OQ-C-6). |
| DC-R3 | Membership keeps its ratified meaning. It is never "any relationship with an organisation". |
| DC-R4 | OrganisationAffiliation keeps its ratified meaning (EM-VOC-002: access and roles). "User" is one of its roles, so no ratified decision changes. No generic `OrganisationRelationship` abstraction is introduced without approval. |
| DC-R5 | Roles are **not mutually exclusive.** A person may hold several capacities in one organisation (e.g. User and Member; Worker and Member). |
| DC-R6 | The demo environment and the technical default-tenant fallback are kept separate from PublicDigit's real organisational relationships and data. |
| DC-R7 | Joining or leaving any organisation never creates or deletes the central account (XD-3c). |
| DC-R8 [R] | **The User affiliation is not a login gate.** Authentication is decided by Identity from the account's status (XD-3c). This matches current behaviour (§4, E-3). It keeps the PublicDigit organisation from controlling every account (R-XD3c-1), and keeps login independent of PublicDigit's organisation database. |
| DC-R9 [R] | Under the multi-database target, the User affiliation is stored in **PublicDigit's organisation database** (XD-3a C-3). That database records only PublicDigit's own relationships, never other organisations' affiliations or memberships (C-4). |

## 2a. Conflict with an approved business decision (escalated)

**PBDIGIT-30 B1** (approved by the PO, 2026-08-06; `docs/publicdigit/backlog/PBDIGIT-30-active-organisation-business-lifecycle-discovery.md`, "B1"):
- PublicDigit is a **bootstrap organisation** that "is **never** a user's working organisation".
- It exists to provide public capabilities and to satisfy "every user belongs to at least one organisation".

**Consistent with B1:**
- DC-R1 (registration creates the User affiliation) and DC-R2.
- Registration-time belonging to PublicDigit (B1.1).

**Not consistent with B1 as written:** this record's description of PublicDigit as a real organisation with its own Workers and Members (§1, DC-R5 examples, OQ-C-6).

**OQ-C-7 (PO):** does B1 stand, which means PublicDigit has no working organisational life on the platform and the Worker / Member examples for PublicDigit are dropped? Or is B1 amended?

**PO direction, 2026-11-01:**
- PublicDigit is a **real organisation** and the default organisation, **not merely a bootstrap organisation**.
- It has its own Users, Members, Workers, Guests and other legitimate organisational relationships.
- Registration creates a User affiliation, not Membership.
- Demo stays distinguishable from PublicDigit's real data.

**This requires a formal amendment of approved B1 (PBDIGIT-30).** This record does not perform it, and PBDIGIT-30 is unchanged.

**Parts of B1 affected:**
- the "bootstrap organisation" definition;
- "It is never a user's working organisation";
- B1.1 ("membership is infrastructure");
- B1.3 (PublicDigit is ignored when counting real organisations), because a PublicDigit Worker or Member must be able to work inside PublicDigit.

**Candidate amendment wording [R], for the PO to approve or reject:**
> *"B1′. PublicDigit is a real organisation and the default organisation. Registration creates a User affiliation with PublicDigit (not Membership). When determining the Working Organisation at sign-in (B1.3), an affiliation with PublicDigit counts as a real organisation only if it is more than the User role (e.g. Member, Worker, Guest); a person whose only PublicDigit relationship is User is routed as today."*

Until that amendment is approved, B1 remains the approved rule.

## 3. Open questions

| # | Question | Owner |
|---|---|---|
| OQ-C-0 | Confirm DC-R8: the User affiliation does not gate authentication | PO + ARB |
| OQ-C-2 | Does ending a User affiliation affect anything else? It must not end the account (XD-3c). | PO + ARB |
| OQ-C-4 | How demo and the fallback are separated (DC-R6). [R]: a separate demo organisation; the fallback's data connects to multi-database decision D4. | PO + ARB |
| OQ-C-5 | Which affiliation store, or new store, represents roles consistently, including several roles per person per organisation (see F-C-2). Goes with OQ-3a-3. | ARB |
| OQ-C-6 | PublicDigit's own membership rules (eligibility, approval) | PO |
| OQ-3a-4 (unchanged) | **May one person belong to several organisations in the future?** Not resolved here. | PO |

## 4. Evidence: how registration and login create, read and check the affiliation today

**E-1, password registration** (`app/Http/Controllers/Auth/RegisterController.php`):
- It validates `terms` but does not store it (`:38`).
- It sets `users.organisation_id` to the default organisation (`:41-49`).
- It creates the account (`:50`) and fires `Registered` (`:52`).
- Only **afterwards**, in a separate transaction, it creates a `user_organisation_roles` row with **`role = 'member'`** (`:58-63`). The code comment says "platform membership".
- Account creation and the affiliation are **not atomic**.

**E-2, a second writer:** listener `app/Listeners/CreateUserOrganisationRole.php`, on `Registered` (`app/Providers/EventServiceProvider.php:38`).
- It inserts the same row, also with `role = 'member'`, using a raw insert **without `id`**.
- It fails on the UUID primary key, and the error is caught and logged (`:27-67`; `PBDIGIT-310`, open).

**E-3, login:**
- `app/Http/Controllers/Auth/LoginController.php` authenticates, then hands over to `DashboardResolver` only for routing (`:86-94`).
- **No affiliation is checked at authentication.**
- Access to an organisation's routes is checked afterwards by `EnsureOrganisationMember` (`ensure.organisation`, `bootstrap/app.php:100`). It treats **any** `user_organisation_roles` row, whatever the role, as "member" (`app/Http/Middleware/EnsureOrganisationMember.php:119-122`).

**E-4, `DashboardResolver`:**
- Treats `role = 'member'` in **non-default** organisations as "active membership" (`app/Services/DashboardResolver.php:755-765`).
- Explicitly excludes the default organisation so that registrants are not counted (`:760`; also `:490-493`).

**E-5, Google sign-up** (`app/Http/Controllers/GoogleController.php`):
- It looks up the account by `google_id` (`:35`). Otherwise it calls `User::updateOrCreate(['email' => …])` **without `organisation_id`** (`:43-50`).
- It fires no `Registered` event and creates **no affiliation**.
- No migration that makes `users.organisation_id` nullable was found. A new Google account therefore probably fails the NOT NULL constraint [I, not executed].
- It also sets `password` to `encrypt('123456dummy')` on an **existing** account with the same email (`:49`), and shows errors with `dd()` (`:57-58`).

## 5. Can the existing stores represent the User role? (point 4 of the clarification)

| Store | Role representation | Several roles per person per organisation? | Can it hold "User"? |
|---|---|---|---|
| `user_organisation_roles` (`database/migrations/2026_03_05_000003_…:15,29`) | free string, default `'member'` | **No:** `unique(user_id, organisation_id)` | as a string, yes, but then a User cannot also be a Member or Worker in the same row set |
| `organisation_users` (`2026_03_07_145528_…:18-19,31`) | enum `owner / admin / staff / member`; status `active / inactive / suspended` | **No:** `unique(organisation_id, user_id)` | **No** `user` value; the enum would need a change |
| `organisation_participants` | enum `staff / guest / election_committee`; `expires_at` | no unique constraint found | no `user` value; guest-type capacities only |

**Conclusion [F/I]:** no existing store represents the User role consistently together with the non-exclusive roles of DC-R5. Several further problems:
- **`'member'` means three things today:**
  - the registrant (which, under this decision, is the User role) (E-1, E-2);
  - a stand-in for Membership in other organisations (E-4);
  - any affiliation at all (E-3).
- **The two main stores allow only one role per person per organisation.**
- Membership itself lives in a different store (`members`; the ratified Membership kernel).

Which store, or what new structure, should hold the User role is **an architecture decision (OQ-C-5)**, not something this record settles.

## 6. Findings (for separately scoped decisions, requirements or backlog items)

| # | Finding | Evidence |
|---|---|---|
| F-C-1 | Acceptance of the registration terms is not stored | E-1 |
| F-C-2 | One role per person per organisation in both main stores; contradicts DC-R5 | §5 |
| F-C-3 | The string `member` is overloaded with three meanings | E-1…E-4 |
| F-C-4 | Registration is not atomic and has two writers (one always fails) | E-1, E-2; PBDIGIT-310 |
| F-C-5 | Google sign-up creates no User affiliation; probably fails for new accounts; overwrites the password of an existing account matched by email; shows errors with `dd()` | E-5 |

## 7. Consequences

- **Default-organisation data:** under DC-R9, PublicDigit's organisation database holds one User affiliation per account holder. Acceptable if it records nothing about other organisations (C-4); PublicDigit is not a political organisation.
- **Consistency:** account creation is central (Identity) and the User affiliation is in PublicDigit's database. Because the affiliation is not a login gate (DC-R8), a delayed or failed write does not block login and can be retried.
- **Migration:** existing `role = 'member'` rows in the default organisation become User affiliations. Real PublicDigit workers or members, if any, cannot currently be told apart from registrants [I]; that needs a data review.
- **Demo and fallback (DC-R6):** today the `publicdigit` organisation hosts demo elections (`app/Console/Commands/SetupDemoElection.php:15`; `SetupPublicDemoElection.php:23`). It also receives data created with no organisation through `app/Traits/BelongsToTenant.php:48-58,70-80` (slug `platform` vs seed `publicdigit`; PBDIGIT-309).
- **XD-3c §4** is amended accordingly: for account holders, the User affiliation is a business rule.

## 8. Traceability

- Strategic discovery conversation, 2026-11-01:
  - the Principal's instruction "Choose A′";
  - the Principal's later clarification that the intended rule is option A, with the User role.
- `docs/publicdigit/brainstorming/multi-database-system/15-open-questions-and-decisions.md` (D2, D4).
