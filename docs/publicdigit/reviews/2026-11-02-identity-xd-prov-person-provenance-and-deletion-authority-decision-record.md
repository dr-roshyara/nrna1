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
- Identity may record a generic creation channel ("created via import" vs "created via registration") **only if a demonstrated Identity-owned requirement justifies it**, and **without naming any organisation**. Whether such a requirement exists is open (OQ-PROV-3).

**Reason:** for a political organisation, "imported by organisation X" *is* the political affiliation. A central provenance naming the organisation would contradict:
- the XD-3b §2 condition (a central record of a person without an account must not show which organisation created it);
- INV-AFF-1 (no persisted cross-organisation register).

It would also deepen the **open conflict K4** (XD-3b §1a). This record does **not** resolve K4.

**Not selected:** central provenance naming the organisation.

## 2. Four distinct facts (not one field)

| Fact | Question | Owner / location |
|---|---|---|
| **Provenance** | which process first established this person's record in an organisation? | the organisation, locally (§1) |
| **Identity claim** | has an individual established control of an account or claimed an existing Person (claim code)? | Identity (XD-3b §2a) |
| **Affiliation lifecycle** | which relationships with which organisation are active? | each organisation (INV-AFF-1) |
| **Deletion eligibility** | may this record be removed, under its owner's rules? | the context that owns the record (§4) |

Provenance is **not changed** by a later registration or claim. An imported person who later registers keeps the importing organisation's import record unchanged (Scenario B).

**Rejected representations [D from §1]:**
- **A global `Person.is_imported` flag.** It cannot say *by whom* or *when*, and it reads like a current affiliation. Clearing it when the person leaves erases history; keeping it misleads.
- **A global `imported_by_organisation_id`.** It would persist organisation-specific provenance centrally, contrary to §1 and INV-AFF-1.

**What is historical, and what is a lifecycle:**
- **Provenance** is historical, and recorded per organisation; a later import by another organisation adds that organisation's own record and overwrites nothing.
- **Affiliation** is a lifecycle, per organisation.
- **Identity** is a separate ownership concern.

None substitutes for another.

## 3. Authority (derived [D] from existing records)

| Thing | Owner | May an importing organisation A remove it? | Basis |
|---|---|---|---|
| A's own voter, affiliation, local person and provenance records | Organisation A | **yes**, within A's authority and subject to §4 | INV-ORG-SCOPE-1 (principle approved) |
| the PublicDigit **User** affiliation | the PublicDigit organisation; created by **registration**, never by import | **no**: A is not the owning organisation | BR-2, BR-10, Decision C DC-R1; INV-ORG-SCOPE-1 |
| another organisation's records | that organisation | **no** | INV-ORG-SCOPE-1 |
| the login account | Identity | **no**: an organisation may at most *request* review or suspension | XD-3c R-XD3c-1, R-XD3c-3 |
| the global Person | Identity | **no** | Person owned by Identity (ratified `2026-10-08-membership-architecture-ratified.md:41`; XD-3b); XD-3c R-XD3c-4 (account changes never delete the Person). XD-3a C-5 governs **merges** only. |

**Consequence:** A ending its relationship with a person never changes the PublicDigit User affiliation, the account or the global Person.

## 4. What "remove" means per context

**Membership [D]:**
- Membership facts are **append-only** (ratified 2026-10-08: `2026-10-08-membership-architecture-ratified.md:54`; INV-6 / INV-14 "nothing deleted or rewritten", `:124`; ratification act `:253`).
- A Membership is ended or annulled by a new fact, **never deleted**.
- This applies to the ratified Membership model. The legacy `members` table is a mutable row (e.g. `Member::endMembership` updates its status, `app/Models/Member.php:172-180`).
- For Membership, "remove" therefore means **end, then anonymise only if the law requires it** (OQ-PROV-2).

**Election records [O]:**
- Voter records, codes, votes and audit evidence are governed by Election's own rules.
- **No recorded decision establishes who owns election-record retention.** The ratified boundary is "Election owns entitlement and eligibility" (`2026-10-08-membership-final-ratification-verdict.md:46`); retention stays open under OQ-PROV-1.
- Removing a person must not invalidate historical evidence or election integrity.
- **Votes carry no voter identity:** ADR-T11 (`docs/adr/ADR-T-LOG-Tactical-Implementation.md:18`, "no voter↔vote linkage"). That stays untouched.

**Organisation-local affiliation and provenance records [R]:** may be ended and, where no retention obligation applies, deleted or anonymised by the owning organisation.

**Audit records [O]:** follow the retention rules of their owner.

**Not generalised:**
- The append-only rule is Membership's own (ratified). It is **not** assumed for other contexts; each context's own model decides.
- Ending a Membership does **not** automatically end an affiliation; each follows its own lifecycle. Basis: the ratified ontology separates them and gives them different owners (OrganisationAffiliation → Organisation, Membership → Membership; `2026-10-08-membership-architecture-ratified.md:42-43`).

**Ending a relationship, deleting an account, deleting an identity and erasing personal data are four different operations.**

**Rule [R]:** if any record with a continuing obligation remains, no unrestricted deletion takes place. Instead, end the relationship, deactivate, anonymise what is eligible, and retain what is required.

## 5. The global Person (recommendation [R])

- Under XD-3b OQ-3b-1 option B, the central Person is a **bare identifier with no personal data**.
- Under INV-AFF-1, Identity does **not know** which organisations still refer to it.
- So "delete the global Person when no references remain" **cannot be checked centrally** without asking every organisation.

**Rule:** the global Person is **never deleted as a side effect of an organisation's action**. Only an explicit Identity act removes it, e.g. on the person's own erasure request (OQ-3b-2).
- **No recorded decision yet defines the Person's own deletion lifecycle.** XD-3c covers the *account* lifecycle and says only that account changes never delete the Person (R-XD3c-4).

An orphaned bare identifier holds no information about anyone.

## 6. Scenarios (from the 2026-11-02 analysis)

| Scenario | Result under this record |
|---|---|
| A: imported voter, never registers | A may end or remove its own eligible records; the bare global Person remains unless removed by an explicit Identity act |
| B: imported voter, later registers | A's provenance record unchanged; A may end its relationship; account, User affiliation and Person untouched |
| C: person also affiliated with B | A acts only on its own records; B's records untouched |
| D: only the PublicDigit User affiliation remains | A has **no authority over the User affiliation** merely because it imported the person. A's own remaining eligible records (e.g. its provenance record) follow §4. The User affiliation belongs to PublicDigit; the account and Person follow Identity's rules (XD-3c; §5). |

**Scenario E: imported by A, registers, leaves A, joins B** (an illustration, not a schema):

| Time | Event | Organisation A (own database) | Organisation B (own database) | Identity |
|---|---|---|---|---|
| T1 | A imports P | provenance: import at T1; affiliation active; reference `ref_A` | — | Person `person_id` |
| T2 | P registers, redeems A's claim code | unchanged | — | account linked to `person_id` (claim-binding, XD-3b §2a) |
| T3 | P leaves A | provenance **unchanged**; affiliation **ended** at T3 | — | unchanged |
| T4 | P joins B | unchanged | its own records with **its own** reference `ref_B` (preferred direction, XD-3a option D; not decided); provenance only if B itself imported P | unchanged |

**Rules shown:**
- Leaving A and joining B do not change A's historical provenance.
- A's provenance gives A no authority over B's records or the global Person (§3).
- B does not need, and does not receive, A's provenance.
- If B must establish that a person it imports is the same human as an existing Person, that is an **identity-resolution / claim-binding** question for Identity (XD-3b §2a; INV-AFF-1 evaluation, item 6; BR-11). It is **not** a reason to expose A's provenance.

## 7. Open questions

| # | Question | Owner |
|---|---|---|
| OQ-PROV-1 | Per-context retention rules: organisation-local records, Election records (voter records, codes, audit), Membership facts | PO + context owners + legal |
| OQ-PROV-2 | Anonymisation vs retention for append-only Membership facts and election evidence (with XD-3b OQ-3b-2) | PO + legal + ARB |
| OQ-PROV-3 | Does Identity need the "created via import / via registration" kind at all? E.g. for claim-binding vs duplicate-merge handling (INV-AFF-1 evaluation, item 6) | ARB |
| OQ-PROV-4 | Who in an organisation has the authority to remove records (role, approval, audit trail)? | PO + ARB (with INV-ORG-SCOPE-1 OQ-OS-2) |

## 8. Current implementation (evidence, not changed)

- **The live system has no per-person provenance record** distinguishing an imported person from a self-registered one. Imports create accounts with random passwords (`app/Services/OrganisationUserImportService.php:322`; `app/Services/VoterImportService.php:274`).
- **Job-level records do exist**, and are organisation-scoped: `member_import_jobs` stores organisation, initiator, file, counts and status, with **no link to the persons imported** (`database/migrations/2026_03_16_155012_create_member_import_jobs_table.php:14-30`).
- `membership_applications.source` (`internal` / `public`) records an *application* channel, not person provenance (`database/migrations/2026_04_06_200002_make_public_fields_nullable_on_membership_applications.php:19`).
- **Deleting a login account today is a soft delete** (`app/Http/Controllers/Auth/DeleteAccountController.php:30`; `app/Models/User.php:41`).
- **Legacy foreign keys cascade on hard deletion**, e.g. `members.organisation_user_id` (`database/migrations/2026_03_07_145529_create_members_table.php:29`) and `membership_applications.user_id` (`database/migrations/2026_04_03_155710_create_membership_applications_table.php:17`). A hard delete would erase history, contrary to §4.

## 9. Consistency review (2026-11-02, HEAD `a1e808049`)

Documentation-only corrections. Status, selection, recommendations and open questions are unchanged.

| # | Correction | Source verified |
|---|---|---|
| 1 | §1: the generic creation channel is allowed **only with a demonstrated Identity-owned requirement**, open under OQ-PROV-3 (was "at most what its processes need") | Principal's selection, 2026-11-02 |
| 2 | §1: K4 is an **open** conflict (XD-3b §1a) that this record does not resolve (was listed as something contradicted) | XD-3b §1a (K4 open) |
| 3 | §3 / §5: the global-Person basis corrected. C-5 governs merges only (XD-3a §2). The basis is the ratified Person ownership, XD-3b and R-XD3c-4. No recorded decision yet defines the Person's deletion lifecycle. | XD-3a:48; XD-3c:41; ratified:41 |
| 4 | §4 Membership: ratified citations added; scope limited to the ratified model, the legacy `members` row is mutable | ratified:54,124; act:253; `app/Models/Member.php:172-180` |
| 5 | §4 Election: "retention is owned by Election" was not supported by any record. Replaced by "governed by Election's rules; retention ownership not established (OQ-PROV-1)". ADR-T11 cited. | verdict:46; ADR-T log:18 |
| 6 | §6 Scenario D: "A has nothing further to remove" was too strong; A's own remaining eligible records follow §4 | §3, §4 |
| 7 | §8: evidence refined: job-level organisation-scoped import records exist (`member_import_jobs`, no per-person link); `membership_applications.source` is an application channel | migrations cited in §8 |

**Addition (same day, Principal Reviewer analysis):**
- §2: explicit rejection of a global `is_imported` flag and of `imported_by_organisation_id`.
- §6: Scenario E (imported by A → registers → leaves A → joins B). Its pseudocode uses separate per-organisation references, as the preferred direction records.
- No status, selection or open question changed.

**All six evidence references in §8 were re-verified unchanged:** `OrganisationUserImportService.php:322`, `VoterImportService.php:274`, `DeleteAccountController.php:30`, `User.php:41`, `create_members_table.php:29`, `create_membership_applications_table.php:17`.

## 10. Traceability

- Strategic discovery conversation, 2026-11-02:
  - the Principal Reviewer's provenance and deletion analysis;
  - the Principal's selection "organisation-local provenance, record XD-PROV".
