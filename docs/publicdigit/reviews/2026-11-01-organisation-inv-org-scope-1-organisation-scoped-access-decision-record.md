# INV-ORG-SCOPE-1: organisation-scoped access to organisation-specific records, decision record

**Date:** 2026-11-01 · **Recorded by:** Claude (DDD architect / requirements engineer, strategic discovery)
**Status:** **Principle approved by the PO, 2026-11-01; exceptions and enforcement open.**
- **Not fully ratified:** ARB ratification is still required.
- Open: OQ-OS-1, OQ-OS-2, OQ-OS-3 (§4).
- The approval does **not** authorise the bypass investigation (OQ-OS-3), database tests, or any implementation change. The scoped investigation is a separate, later decision.
- The wording in §1 is the Principal's, 2026-11-01. The platform-level clause (§1a) is part of the approved principle.
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.
**Baseline:** branch `election-flow-updated`, HEAD `576934d29`.
**Related:**
- INV-AFF-1 (`2026-11-01-organisation-inv-aff-1-affiliation-ownership-decision-record.md`);
- XD-3a / XD-3b (organisation-specific `PersonRef`);
- Decision C (DC-R6, demo separation).

**Approved terminology used:** "Working Organisation" (PBDIGIT-30, 2026-08-06: *"the organisation within which the authenticated user is currently operating"*). "Owning Organisation" is the organisation to which a record belongs.

---

## 1. Invariant (Principal wording, 2026-11-01)

**Invariant.**
- An organisation-specific record may be selected, retrieved, modified, or addressed only within the Working Organisation that owns the record. For organisation-specific records:

  `Owning Organisation = Working Organisation`

- A missing or invalid Working Organisation must cause the organisation-specific operation to fail closed. It must never silently substitute the default PublicDigit organisation.
- Any operation that crosses this boundary requires an explicitly approved exception, appropriate authority, and an auditable justification.
- Platform-level operations, including authentication, organisation discovery, organisation selection, and create-or-join routing before a Working Organisation has been established, must not access organisation-specific person records.
- Official shared reference data may be accessed under separately defined rules.

## 1a. Platform-level clause (part of the approved principle)

> **Authentication, organisation discovery, organisation selection, and create-or-join routing may run before a Working Organisation exists. They must not access organisation-specific person records.**

This is the explicit exception to "every authenticated request executes inside exactly one Working Organisation" (approved PBDIGIT-30 B1). Sign-in routing (B1.3) itself precedes any Working Organisation, so the clause is consistent with B1 and does not amend it.

## 1b. Approval record

| Item | State |
|---|---|
| Principle of INV-ORG-SCOPE-1, including §1a | **Approved by the PO, 2026-11-01** |
| ARB ratification | **pending** |
| OQ-OS-1: rules for shared official reference data | open |
| OQ-OS-2: approved exceptions, authority, audit requirements | open |
| OQ-OS-3: classification of the 379 organisation-filter bypasses | open; investigation **not** started, not authorised by this approval |
| Enforcement (implementation, tests) | open; not authorised |

## 1c. Invariant, continued (Principal wording; part of the approved principle)

**Identity access.**
- An organisation-specific person reference is resolved within its organisation: `getPerson(organisation_id, person_ref)`.
- A `person_ref` alone is insufficient to identify an organisation-specific person record.

**Request context.**
- A client-provided organisation identifier expresses the requested context; it does not establish authorisation.
- The system must validate the organisation context and the user's authority before allowing organisation-specific access.

**Enforcement expectation.**
- The selected Working Organisation determines the organisation database in the target multi-database architecture.
- Where a record also carries an `organisation_id`, that value must be consistent with the Working Organisation.
- A mismatch must be rejected, subject only to approved exceptions.

## 2. Verification against the current implementation (2026-11-01)

The invariant is **partly met** today. It is an intended invariant, not a description of the system.

| # | Finding | Evidence | Kind |
|---|---|---|---|
| V-1 | **Met by the ratified Membership kernel:** every repository method takes `TenantId`; tables keyed by `(organisation_id, …)`; `unique(organisation_id, person_ref)` | `app/Contexts/Membership/Application/Organisational/Ports/MemberRepositoryPort.php:26-36`; `database/migrations/2026_10_09_100000_create_org_membership_tables.php:26-33` | conforms |
| V-2 | Legacy members and enrolment records are filtered by organisation (`BelongsToTenant`) | `app/Models/Member.php:17`; `app/Models/OrganisationUser.php:13` | partly conforms (see V-4, V-5) |
| V-3 | Login accounts are global, and also hold all personal data (names, address). 44 global look-ups by id or email. | `app/Models/User.php` (no organisation filter) | **outside the invariant today**: personal data is not organisation-specific in storage (cf. XD-3b OQ-3b-1 option B, which moves it into organisation databases) |
| V-4 | **Missing context is silently substituted by the default PublicDigit organisation**, for reads and writes | `app/Traits/BelongsToTenant.php:48-58,70-80` (looks up slug `platform` while the seed uses `publicdigit`, PBDIGIT-309) | **contradicts** "fail closed / never substitute" |
| V-5 | The organisation filter is bypassed in **379** places (`withoutGlobalScopes()` in `app/`) | repository search | each bypass needs classification: approved exception, platform-level operation, or defect |
| V-6 | Roles and access grants are read across all organisations without a filter, and cached per user | `app/Models/UserOrganisationRole.php`; `app/Models/User.php:1080-1093` | contradicts Working-Organisation scoping |
| V-7 | A client-provided `X-Tenant-Id` header is accepted as the organisation context | `app/Http/Middleware/TenantContext.php:49-56` | the "Request context" clause requires validation; whether membership is checked was **not** re-verified here (reported by the multi-database session, `SESSION_START.md` §3) |

## 3. Consequences

- **Demo mode** relies on a NULL organisation that is converted to the default organisation (V-4; `CLAUDE.md` "Two-Tier Deployment Model"). Under the invariant, demo operations need an explicit Working Organisation, i.e. the separate demo organisation of Decision C DC-R6 (OQ-C-4).
- **Earlier proposal:** the enforcement expectation subsumes INV-DB-3 ("rows carry `organisation_id`; a mismatch with the routed organisation stops the request"). That was proposed in conversation and is listed as open in the XD-3a record.
- **INV-AFF-1 compatibility:** discovery at sign-in is a platform-level operation. Each lookup to an organisation runs in that organisation's own context with its own reference, and no organisation-specific person data is returned to the platform beyond "this organisation recognises the person".
- **Approved B1 invariant** ("every authenticated request must execute inside exactly one Working Organisation"): sign-in routing (B1.3) itself runs before a Working Organisation exists. This record makes that **platform-level exception** explicit. It is consistent with B1 as written and does not amend it.

## 4. Open questions

| # | Question | Owner |
|---|---|---|
| OQ-OS-1 | Rules for official shared reference data (e.g. official geography units with no owning organisation) | ARB (with XD-5) |
| OQ-OS-2 | Exception catalogue: platform support acting for an organisation, platform administration; required authority and audit trail | PO + ARB + Security |
| OQ-OS-3 | Classification of the 379 bypasses (V-5): a later, separately authorised investigation | ARB |

## 5. Traceability

- Strategic discovery conversation, 2026-11-01 (Principal wording of the invariant).
- `CLAUDE.md` (project): multi-layer tenant isolation.
- `docs/publicdigit/backlog/PBDIGIT-30-active-organisation-business-lifecycle-discovery.md` (B1, Working Organisation).
- `docs/publicdigit/brainstorming/multi-database-system/SESSION_START.md` §3.
