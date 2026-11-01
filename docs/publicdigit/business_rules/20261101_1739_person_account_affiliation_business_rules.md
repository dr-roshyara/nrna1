# Person, account, affiliation and voter: clarified business rules (BR-1…BR-9)

**Date:** 2026-11-01 · **Recorded by:** Claude (DDD architect / requirements engineer, strategic discovery)
**Status:** **Business rules as confirmed by the PO on 2026-11-01** (consolidated list provided in the strategic discovery conversation). BR-9 is **open**.
- These rules do not determine a schema or a bounded-context implementation.
- Nothing is authorised for implementation.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0). This folder's naming convention is `YYYYMMDD_HHMM_<topic>.md`.
**Related records (all PROPOSED):**
- `docs/publicdigit/reviews/2026-11-01-identity-xd3a-person-identity-scope-decision-record.md`;
- `…-xd3b-person-vs-account-…`;
- `…-xd3c-account-lifecycle-…`;
- `…-organisation-dc-default-organisation-relationship-…`.

**Existing approved business decisions this list must stay consistent with:** `docs/publicdigit/backlog/PBDIGIT-30-active-organisation-business-lifecycle-discovery.md` (Business Decision Record: B1, B10 approved 2026-08-06; terminology "Working Organisation"). See §3.

---

## 1. Rules

| ID | Business rule | Status |
|---|---|---|
| BR-1 | PublicDigit is the default organisation. | Confirmed |
| BR-2 | Every person who registers with PublicDigit becomes a **User** of the default PublicDigit organisation. | Confirmed |
| BR-3 | Being a User of PublicDigit does not automatically make someone a formal Member of PublicDigit. | Confirmed |
| BR-4 | One person can be associated with multiple organisations. | Confirmed (answers OQ-3a-4) |
| BR-5 | An organisation can record a person without that person having a login account. | Confirmed (consistent with XD-3b) |
| BR-6 | A person imported as a voter can be recorded as a voter before registering for PublicDigit. | Confirmed |
| BR-7 | A voter without a login account cannot cast a vote. They must register and authenticate first, then satisfy the applicable voting rules. | Confirmed (an Election rule; XD-3b leaves it to the owning domain) |
| BR-8 | Registration establishes a User affiliation with PublicDigit. The person keeps their voter relationship with the other organisation. | Confirmed |
| BR-9 | The target storage and ownership of OrganisationAffiliation. | **Open.** Proposed direction (2026-11-01, INV-AFF-1, PROPOSED): organisation-local storage, with discovery at sign-in and no persisted central register. Mechanism and feasibility not established (`docs/publicdigit/reviews/2026-11-01-identity-affiliation-discovery-evaluation.md`). |
| BR-10 | When an organisation imports a person who has not registered, Identity creates or resolves the Person and issues the reference for the organisation's record. **No User affiliation with PublicDigit is created at import.** | PO direction 2026-11-01. Conflicts with an XD-3b condition (K4, open) |
| BR-11 | Independent imports of the same human by two organisations are **not** automatically assumed to be the same person. Resolution and merging follow an explicit Identity process with sufficient verification, with special care for election-related records. | PO direction 2026-11-01 |
| BR-12 | PublicDigit is a **real organisation** and the default organisation, not merely a bootstrap organisation. It has its own Users, Members, Workers, Guests and other legitimate relationships. Demo stays distinguishable from its real data. | PO direction 2026-11-01. **Requires formal amendment of approved PBDIGIT-30 B1** (K7); until then B1 stands |
| — | Preferred direction (not a rule): a stable internal `person_id` in Identity and a different opaque `PersonRef` per organisation (XD-3a option D / "option C") | **Not decided** |

## 2. The imported-voter lifecycle (illustration of BR-5…BR-8)

| Stage | Person | Account | PublicDigit affiliation | Voter relationship (Association A) | May cast a vote? |
|---|---|---|---|---|---|
| 1. Imported by Association A | recorded | none | none | recorded | no (BR-7) |
| 2. Registers | **the same Person**, linked through the approved Identity process; **no second Person** | yes | User | retained | only after linking, authentication and eligibility checks |
| 3. Signs in, opens the election | — | authenticated | User | retained | if Election's eligibility rules are satisfied |

**Linking (XD-3b, OQ-3b-1 option B):**
- The account is linked to the existing Person through an organisation-issued claim code or invitation.
- Email equality alone is not proof of identity unless an Identity rule explicitly says so.

**Distinct concepts** (none substitutes for another): Person · User Account · OrganisationAffiliation · voter record · voting eligibility. An imported voter is not necessarily a Member: election-only organisations take voters with no Membership.

## 3. Consistency with approved PBDIGIT-30 B1 (2026-08-06)

**Consistent:**

| Today | B1 |
|---|---|
| BR-1 | the bootstrap organisation |
| BR-2 | B1.1: the registrant automatically belongs to PublicDigit |
| BR-4 | B1.3 / B1.4: 0, 1, or 2+ real organisations |

BR-3 refines B1.1's informal word "membership" ("that membership is infrastructure") to an affiliation with the User role, which is not Membership.

**Conflict (escalated, not resolved here):**
- B1 states that PublicDigit "is never a user's working organisation" and exists only to provide public capabilities and satisfy "every user belongs to at least one organisation".
- The Decision C record describes PublicDigit as a real organisation with its own workers, members and guests.
- **Both cannot stand unchanged:**
  - either B1 holds and PublicDigit has no organisational life of its own on the platform;
  - or B1 is amended by the PO.
- Owner: PO.
- **PO direction, 2026-11-01:** amend B1 (BR-12).
- The candidate amendment wording "B1′" is in the Decision C record, §2a.
- PBDIGIT-30 is not changed by this file. B1 stands until the amendment is approved.

## 4. Consequences for BR-9 (inputs only; nothing decided)

- **Approved B1.3** routes a signing-in user by the number of their **real** organisations (0 → create or join; 1 → enter; 2+ → choose).
- **Approved B1 invariant:** every authenticated request runs inside exactly one Working Organisation.
- So, at sign-in, the platform must be able to learn **which organisations an account may enter**. With one database per organisation, that means either:
  - a minimal central index; or
  - an alternative such as per-organisation sign-in hosts.

  That is XD-3a OQ-3a-3, under condition C-4 (minimised, data-protection review).
- The Working Organisation is also the natural selector of the organisation database (Decision D).

## 5. Current implementation vs BR-5 / BR-6 (evidence)

- **Imported voters get placeholder accounts today.** `app/Services/VoterImportService.php:274` creates users with random passwords.
- **Voter invitations require an account.** `voter_invitations.user_id` is required (`database/migrations/2026_04_15_204144_create_voter_invitations_table.php:17,28`).
- **A claim-style link already exists:** a unique `token` per invitation (`:19`), with `used_at` (`:23`). It is attached to an account rather than to a Person. This is a precedent for the claim-code linking of OQ-3b-1, not an implementation of it.

## 6. Traceability

- Strategic discovery conversation, 2026-11-01 (PO's consolidated business rules).
- `docs/publicdigit/business_rules/20260806_0818_how_many_organisation.md`; `docs/publicdigit/backlog/PBDIGIT-30-…` (approved B1, B10).
