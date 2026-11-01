# XD-3c: Account lifecycle and organisational authority, decision record

**Date:** 2026-11-01 · **Recorded by:** Claude (DDD architect / requirements engineer, strategic discovery)
**Status:** **PROPOSED, pending governance approval (PO + ARB; retention and erasure aspects also need legal / data-protection review).**
- The Principal selected option **C** on 2026-11-01.
- This record is **not** an approval to implement anything.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.
**Baseline:** branch `election-flow-updated`, HEAD `576934d29`.
**Related:**
- `2026-11-01-identity-xd3a-person-identity-scope-decision-record.md` (XD-3a, PROPOSED; conditions C-1…C-5);
- `2026-11-01-identity-xd3b-person-vs-account-decision-record.md` (XD-3b, PROPOSED; B2).

---

## 1. Decision

**Question.** If a person is removed from their only organisation, what happens to their central login account?

**Selected option (proposed): C.** The account **remains active** unless:
- the person requests deletion; or
- an explicit, Identity-owned account-security policy requires suspension.

Ending a membership never determines the account lifecycle by itself.

**Not selected:**

| Option | Reason |
|---|---|
| **A** (always remains active, no security policy) | C includes A and adds a governed suspension path. |
| **B** (automatic suspension when the last membership ends) | Incompatible with XD-3a conditions C-3 and C-4. Identity would need a central index of every person's memberships across organisation databases. For political organisations that index discloses party affiliation. |

## 2. Rule (proposed)

> **R-XD3c-1.** An organisation may manage a person's membership and access within its own organisational boundary. It may not independently delete or modify the person's central login account.
>
> **R-XD3c-2.** Account suspension and deletion are governed by Identity-owned lifecycle rules, with explicit authority, a recorded reason, an audit trail, and the applicable privacy and retention requirements.
>
> **R-XD3c-3.** An organisation may *request* an account review or suspension from Identity (e.g. a security concern). The right to request is not the authority to execute. Emergency suspension is allowed only under an approved security policy and never silently becomes deletion.
>
> **R-XD3c-4.** Changes to an account never automatically delete the Person (XD-3b) or erase organisational records (Membership history, appointments, governance records).
>
> **R-XD3c-5.** Ending a Membership, revoking organisational access and deleting an account are three separate operations. None triggers another automatically.

**Ownership:** these are Identity-owned rules. Membership and governance facts stay with their owning contexts (XD-3a C-3).

## 3. Clarification on access after a membership ends

Access that depends on Membership is not revoked by a second write. The authorisation decision checks current Membership each time it is made (ratified: Membership alone grants no entitlement), so once the membership has ended the next decision denies.

This is consistent with the derived-effectiveness rule R3 of simulation g14 S1 (`docs/publicdigit/membership/architecture/models/g14_cross_context_consistency.py`). Revoking an *explicit* grant (a role, a guest pass) remains a separate act by the owner of affiliation, which is still open (OQ-3a-3).

## 4. Consequence for Decision C (affiliation)

> **Amended 2026-11-01 by Decision C, option A with the User role** (`2026-11-01-organisation-dc-default-organisation-relationship-decision-record.md`; final version of that day):
> - Registration creates an OrganisationAffiliation with the default PublicDigit organisation, **role User**. For account holders this is a **business rule**, not merely an onboarding convention.
> - It is **not** Membership, and it is not a login gate (DC-R8, recommended).
> - Unchanged: a Person in general (e.g. without an account, XD-3b) needs no organisation relationship.
> - An account stays active after its last other organisation.
>
> Earlier same-day amendments (option A without the User role; option A′) were superseded. The original text below is kept as history.

Under option C, an account, and therefore a Person, may exist with **no** organisation relationship. As a consequence (recommendation, to be confirmed in Decision C):
- the platform-organisation affiliation every new user receives today (`app/Http/Controllers/Auth/RegisterController.php:42-63`) is an **onboarding convention, not a domain invariant**;
- the required `users.organisation_id` (`database/migrations/2026_03_05_000002_create_uuid_users_table.php:13,23`) is a technical constraint to retire during migration, not an invariant to preserve.

## 5. Open questions

| # | Question | Owner |
|---|---|---|
| OQ-3c-1 | Content of the Identity account-security policy: grounds, who may trigger an emergency suspension, review period, appeal | PO + Security |
| OQ-3c-2 | Identity's handling of an organisation's suspension request: authority checks, evidence, notification of the person | PO + ARB |
| OQ-3b-2 (carried) | Retention vs erasure. Today's self-deletion is a soft delete: history survives, but it is not erasure. | PO + legal |
| — (carried) | Account linking by email: Google login takes over any account with the same email, including import placeholders (`app/Http/Controllers/GoogleController.php:44`). It needs an explicit Identity matching rule under XD-3b. | ARB |

## 6. Evidence (current implementation)

- The only account-deletion path is self-service with password confirmation: `app/Http/Controllers/Auth/DeleteAccountController.php:15-35`, route `DELETE /user` (`routes/web.php:172`).
- It is a soft delete (`app/Models/User.php:41`, `SoftDeletes`), so database cascades do not run.
- No organisation-admin path that deletes or edits an account was found (bounded search over `app/`; not a proof of absence).
- **R-XD3c-1 therefore describes current behaviour for deletion.** The record keeps it from being lost in the multi-database design.

## 7. Traceability

- Strategic discovery conversation, 2026-11-01:
  - the Principal Reviewer's account-authority analysis;
  - the Principal's selection of option C and instruction to record XD-3c.
