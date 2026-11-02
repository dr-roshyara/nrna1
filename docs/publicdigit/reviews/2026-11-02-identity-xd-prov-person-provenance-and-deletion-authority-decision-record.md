# XD-PROV: Person provenance and deletion authority, decision record

**Date:** 2026-11-02 · **Recorded by:** Claude (DDD architect / requirements engineer, strategic discovery)
**Status:** **PROPOSED, pending governance approval (PO + ARB; retention aspects also legal / data-protection review).**
- The Principal selected **organisation-local provenance** on 2026-11-02.
- Items marked [R] are recommendations; items marked [D] are derived from earlier records and are only as approved as those records.
- This record is not an approval to implement anything.
- It is **separate from INV-ORG-SCOPE-1**, which concerns organisation-scoped access; this one concerns provenance, lifecycle and deletion authority.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.
**Baseline:** branch `election-flow-updated`, HEAD `6c955a22d`.
**Related:**
- XD-3a / XD-3b / XD-3c, Decision C, INV-AFF-1 (all PROPOSED);
- INV-ORG-SCOPE-1 (principle approved by the PO);
- business rules BR-1…BR-12;
- ratified Membership architecture (2026-10-08).

---

## 1. Decision (Principal, 2026-11-02)

**A Person's provenance is recorded organisation-locally.**
- The organisation that imports or creates a person records that fact **in its own database**, e.g. "imported by us on date D for purpose P".
- **Central Identity records no organisation in provenance.**
- At most, Identity holds what its own processes need, such as "created via import" vs "created via registration", **without naming any organisation**.

**Reason:** for a political organisation, "imported by organisation X" *is* the political affiliation. A central provenance naming the organisation would contradict:
- the XD-3b §2 condition (a central record of a person without an account must not show which organisation created it);
- conflict K4;
- INV-AFF-1 (no persisted cross-organisation register).

**Not selected:** central provenance naming the organisation.

## 2. Four distinct facts (not one field)

| Fact | Question | Owner / location |
|---|---|---|
| **Provenance** | which process first established this person's record in an organisation? | the organisation, locally (§1) |
| **Identity claim** | has an individual established control of an account or claimed an existing Person (claim code)? | Identity (XD-3b §2a) |
| **Affiliation lifecycle** | which relationships with which organisation are active? | each organisation (INV-AFF-1) |
| **Deletion eligibility** | may this record be removed, under its owner's rules? | the context that owns the record (§4) |

Provenance is **not changed** by a later registration or claim. An imported person who later registers keeps the importing organisation's import record unchanged (Scenario B).

## 3. Authority (derived [D] from existing records)

| Thing | Owner | May an importing organisation A remove it? | Basis |
|---|---|---|---|
| A's own voter, affiliation, local person and provenance records | Organisation A | **yes**, within A's authority and subject to §4 | INV-ORG-SCOPE-1 (principle approved) |
| the PublicDigit **User** affiliation | the PublicDigit organisation; created by **registration**, never by import | **no**: A is not the owning organisation | BR-2, BR-10, Decision C DC-R1; INV-ORG-SCOPE-1 |
| another organisation's records | that organisation | **no** | INV-ORG-SCOPE-1 |
| the login account | Identity | **no**: an organisation may at most *request* review or suspension | XD-3c R-XD3c-1, R-XD3c-3 |
| the global Person | Identity | **no** | XD-3b; XD-3a C-5 |

**Consequence:** A ending its relationship with a person never changes the PublicDigit User affiliation, the account or the global Person.

## 4. What "remove" means per context

**Membership [D]:**
- Membership facts are **append-only** (ratified 2026-10-08).
- A Membership is ended or annulled by a new fact, **never deleted**.
- For Membership, "remove" therefore means **end, then anonymise only if the law requires it** (OQ-PROV-2).

**Election records [O]:**
- voter records, codes, votes, audit evidence: retention is owned by Election.
- Removing a person must not invalidate historical evidence or election integrity.
- **Votes carry no voter identity (ADR-T11). That stays untouched.**

**Organisation-local affiliation and provenance records [R]:** may be ended and, where no retention obligation applies, deleted or anonymised by the owning organisation.

**Audit records [O]:** follow the retention rules of their owner.

**Not generalised:**
- The append-only rule is Membership's own (ratified). It is **not** assumed for other contexts; each context's own model decides.
- Ending a Membership does **not** automatically end an affiliation; each follows its own lifecycle.

**Ending a relationship, deleting an account, deleting an identity and erasing personal data are four different operations.**

**Rule [R]:** if any record with a continuing obligation remains, no unrestricted deletion takes place. Instead, end the relationship, deactivate, anonymise what is eligible, and retain what is required.

## 5. The global Person (recommendation [R])

- Under XD-3b OQ-3b-1 option B, the central Person is a **bare identifier with no personal data**.
- Under INV-AFF-1, Identity does **not know** which organisations still refer to it.
- So "delete the global Person when no references remain" **cannot be checked centrally** without asking every organisation.

**Rule:** the global Person is **never deleted as a side effect of an organisation's action**. Only an explicit Identity act removes it, e.g. on the person's own erasure request (XD-3c lifecycle; OQ-3b-2).

An orphaned bare identifier holds no information about anyone.

## 6. Scenarios (from the 2026-11-02 analysis)

| Scenario | Result under this record |
|---|---|
| A: imported voter, never registers | A may end or remove its own eligible records; the bare global Person remains unless removed by an explicit Identity act |
| B: imported voter, later registers | A's provenance record unchanged; A may end its relationship; account, User affiliation and Person untouched |
| C: person also affiliated with B | A acts only on its own records; B's records untouched |
| D: only the PublicDigit User affiliation remains | A has nothing further to remove; the User affiliation belongs to PublicDigit and is governed by its rules and XD-3c |

## 7. Open questions

| # | Question | Owner |
|---|---|---|
| OQ-PROV-1 | Per-context retention rules: organisation-local records, Election records (voter records, codes, audit), Membership facts | PO + context owners + legal |
| OQ-PROV-2 | Anonymisation vs retention for append-only Membership facts and election evidence (with XD-3b OQ-3b-2) | PO + legal + ARB |
| OQ-PROV-3 | Does Identity need the "created via import / via registration" kind at all? E.g. for claim-binding vs duplicate-merge handling (INV-AFF-1 evaluation, item 6) | ARB |
| OQ-PROV-4 | Who in an organisation has the authority to remove records (role, approval, audit trail)? | PO + ARB (with INV-ORG-SCOPE-1 OQ-OS-2) |

## 8. Current implementation (evidence, not changed)

- **The live system has no provenance record** distinguishing an imported person from a self-registered one. Imports create accounts with random passwords (`app/Services/OrganisationUserImportService.php:322`; `app/Services/VoterImportService.php:274`).
- **Deleting a login account today is a soft delete** (`app/Http/Controllers/Auth/DeleteAccountController.php:30`; `app/Models/User.php:41`).
- **Legacy foreign keys cascade on hard deletion**, e.g. `members.organisation_user_id` (`database/migrations/2026_03_07_145529_create_members_table.php:29`) and `membership_applications.user_id` (`database/migrations/2026_04_03_155710_create_membership_applications_table.php:17`). A hard delete would erase history, contrary to §4.

## 9. Traceability

- Strategic discovery conversation, 2026-11-02:
  - the Principal Reviewer's provenance and deletion analysis;
  - the Principal's selection "organisation-local provenance, record XD-PROV".
