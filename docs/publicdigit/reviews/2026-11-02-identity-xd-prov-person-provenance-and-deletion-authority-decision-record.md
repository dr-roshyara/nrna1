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
| the global Person | Identity | **no** (*amended by AM-1, §5a: the original importer may ask Identity to delete a never-registered Person under strict conditions*) | Person owned by Identity (ratified `2026-10-08-membership-architecture-ratified.md:41`; XD-3b); XD-3c R-XD3c-4 (account changes never delete the Person). XD-3a C-5 governs **merges** only. |

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

## 5a. Amendment AM-1 (Principal, 2026-11-02): who may delete a global Person (PROPOSED)

**Principal's selection:** "A + B with rule 1 narrowed".

**What it changes:**
- It amends §3 (row "the global Person") and §5 ("never deleted as a side effect of an organisation's action") with **one narrow importer exception**.
- It adds the person's own rights.
- It is a **change to Identity's deletion authority**, so it needs **ARB approval**. The selection alone does not settle it.

| # | Who | May do what | Conditions |
|---|---|---|---|
| AM-1.1 | **The person** | end their own membership or affiliation in **any** organisation | through each organisation's process. For Membership this is the ratified ending kind `resignation` (`app/Contexts/Membership/Domain/Organisational/EndKind.php:15`), recorded as a new fact (§4). **Membership's ending model is not assumed for affiliation**: each context owns its own lifecycle rules. Ending one relationship deletes nothing else. |
| AM-1.2 | **The person** | ask Identity to delete their **own** account and global Person, **regardless of who imported them** | Identity executes it; erasure and retention rules apply (OQ-3b-2, OQ-PROV-2); consistent with XD-3c |
| AM-1.3 | **The original importing organisation** | ask Identity to delete an imported global Person | **all** of: (a) the Person **never registered**, i.e. has no account (otherwise R-XD3c-1 and XD-3b apply); (b) the importer proves its status with an **importer capability** (below); (c) **complete** discovery confirms that no organisation still recognises the Person. **If any organisation cannot be checked, the request stays pending or is rejected; it never proceeds on the assumption that no affiliation remains** (fail closed; INV-AFF-1); (d) no retention obligation prevents it; (e) the organisation's internal authority and audit requirements are met (OQ-PROV-4) |
| AM-1.4 | **Any other organisation** | nothing beyond its own records | INV-ORG-SCOPE-1; §3 |
| AM-1.5 | **Identity** | validate and **execute** authorised lifecycle operations on accounts and global Persons | the only executor. It preserves account–Person consistency (XD-3b: every account belongs to exactly one Person) and records an audit trail. **The right to request a deletion (AM-1.2, AM-1.3) is not the authority to execute it.** No organisation deletes a global Person directly. |

> **Superseded in part by AM-1 r2 (below):** in AM-1.3, condition (b) "importer capability" is replaced by the person's explicit confirmation. The capability text below is kept as history.

**Importer capability (mechanism direction [R], needs design and ARB approval):**
- At import, Identity issues the importing organisation a one-time secret, which that organisation stores **in its own database**.
- Identity keeps only a **fingerprint** linked to the Person, **never to an organisation**.
- Presenting the secret proves "I am the importer" without Identity ever recording who imported the Person. Organisation-local provenance (§1) and the XD-3b §2 condition stay intact.

**In every case:**
- Identity executes the deletion and records it in the audit trail.
- Records under continuing obligations (election evidence, Membership facts, audit) stay under their owners' rules (§4).
- Deleting the bare Person leaves organisations' own records as references to no one.
- The original importer is **not** the owner of the Person; AM-1.3 is a conditional right only.

**Why option C (only the importer may delete) was not selected:**
- A self-registered Person has no importer, so under C nobody, the person included, could ever delete them.
- It would also deny a registered person a route to erase their own identity (OQ-3b-2; XD-3c).

### AM-1 r2 (Principal, 2026-11-02): deletion by confirmed request (PROPOSED)

**Principal's selection:** the recommended set.
1. The person's confirmation **replaces** the importer capability.
2. The confirmation link is delivered by the **importing organisation** to contact details it already holds.
3. If the person does not respond: **reminders, then expiry; silence is never consent**.

**Where the authority now comes from:**
- For a never-registered imported Person, the deletion is authorised by **the person's explicit confirmation**. In substance it is the person's own decision (AM-1.2).
- The original importing organisation only **initiates** the request. It must hold a record of the Person in its own database (INV-ORG-SCOPE-1). Identity still never records which organisation imported the Person (§1).
- **Identity alone executes** (AM-1.5).

**Process** (a deletion-request process, not a direct deletion command):

| Step | Actor | What happens |
|---|---|---|
| 1 Request | Organisation A | initiates a deletion request for a Person it holds a record of, within its own internal authority (OQ-PROV-4) |
| 2 Token | Identity | issues a **single-use, expiring confirmation token** bound to the Person and to this request. Identity stores **no contact data**: a never-registered Person is a bare identifier (OQ-3b-1 B). |
| 3 Delivery | Organisation A | delivers the token to contact details **it already holds**. The message states who initiated the request, what is proposed, the consequences, and how to confirm or reject. |
| 4 Confirmation | the person | must confirm **explicitly**; opening the link deletes nothing. Rejection cancels the request. |
| 5 Eligibility | Identity | checks that: the token is valid, unexpired and unused; **no account has been registered or linked** to the Person since the request began; **complete** discovery shows no other organisation still recognises the Person (otherwise pending or rejected; protects other organisations' identity links and claim codes); no retention obligation prevents it |
| 6 Execution | Identity | deletes the global Person and records an audit trail. Organisation A handles its own local records under its retention rules. Other organisations' records and Membership facts are not touched. |

**Scenarios:**

| Scenario | Outcome |
|---|---|
| A imported P; P never registered; P confirms; all checks pass | deletion may proceed |
| A imported P; P is also known to Organisation B | blocked while B still recognises P |
| A imported P; P registers (before or during the request) | this path no longer applies; P's own deletion request follows Identity's rules (AM-1.2) |
| P does not respond | reminders, then expiry; the Person remains |
| P rejects | request cancelled; no deletion |
| an organisation cannot be checked | blocked: eligibility unproven |
| P has records under mandatory retention | only legally permissible deletion or anonymisation; confirmation does not override retention |

**Caveats:**
- **Control of an email address is not proof of identity.** If Organisation A holds outdated contact details, someone else could confirm. For a never-registered bare identifier the harm is limited, but it is not zero (it breaks future claim-binding).
- **The person's confirmation does not override legal retention obligations**, and does not guarantee that every record can be erased (OQ-PROV-1, OQ-PROV-2, OQ-3b-2).

**Status:** the process is a **business-policy proposal**. The token, delivery, reminders, expiry and the complete-discovery check are **unresolved design proposals for ARB review**.

**Open, added by AM-1:**

| # | Question | Owner |
|---|---|---|
| OQ-PROV-5 | **Unresolved design proposal, for ARB review:** the importer capability. Issuance, storage, loss, rotation, revocation when the Person registers, expiry, retries, audit. Can a transferred or replayed capability allow unauthorised deletion? **Possession of a secret proves possession only, not that deletion is still appropriate.** | ARB + Security |
| OQ-PROV-6 | **Unresolved design proposal, for ARB review:** complete discovery for deletion. Who runs it, timeouts, retry, how long a request may stay pending; the same scale questions as the INV-AFF-1 evaluation (OQ-E-1) | ARB |
| OQ-PROV-7 | The Person's own deletion lifecycle in Identity: identity verification of the requester, grace period, effect on organisations' references | PO + ARB + legal |
| OQ-PROV-8 | (AM-1 r2) Confirmation token design: lifetime, number and timing of reminders, rejection handling, audit of request, delivery and confirmation; whether an organisation may resend; message content requirements | ARB + PO + data protection |

**Note on OQ-PROV-5:** under AM-1 r2 the importer capability is no longer required. OQ-PROV-5 is **largely obsolete**, but stays listed until the ARB confirms r2.

## 6. Scenarios (from the 2026-11-02 analysis)

| Scenario | Result under this record |
|---|---|
| A: imported voter, never registers | A may end or remove its own eligible records; the bare global Person remains unless removed by an explicit Identity act. *Under AM-1.3 (§5a), A may ask Identity for that deletion if the conditions are met.* |
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

**Amendment AM-1 (2026-11-02, Principal: "A + B with rule 1 narrowed", PROPOSED):**
- §5a added; §3 row and Scenario A annotated.
- The original §5 text is kept as history; AM-1 narrows it.
- New open questions: OQ-PROV-5, -6, -7.
- **Refined the same day (Principal Reviewer safeguards):**
  - request ≠ execution (AM-1.5);
  - incomplete discovery → the request stays pending or is rejected;
  - Membership's ending model is not assumed for affiliation;
  - the importer capability and complete discovery are explicitly **unresolved design proposals for ARB review**;
  - OQ-3b-2 and the retention questions stay open.
- **AM-1 r2 (Principal accepted the recommended set):**
  - the person's confirmation replaces the importer capability;
  - the importing organisation delivers an Identity-issued single-use token to contact details it already holds;
  - reminders, then expiry; silence is never consent.
  - Added: the process, scenarios and caveats; OQ-PROV-8. OQ-PROV-5 is marked largely obsolete.

**All six evidence references in §8 were re-verified unchanged:** `OrganisationUserImportService.php:322`, `VoterImportService.php:274`, `DeleteAccountController.php:30`, `User.php:41`, `create_members_table.php:29`, `create_membership_applications_table.php:17`.

## 10. Traceability

- Strategic discovery conversation, 2026-11-02:
  - the Principal Reviewer's provenance and deletion analysis;
  - the Principal's selection "organisation-local provenance, record XD-PROV".
