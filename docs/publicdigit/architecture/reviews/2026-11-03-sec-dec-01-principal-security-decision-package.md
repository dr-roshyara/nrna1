# SEC-DEC-01: Principal security decision package (PBDIGIT-266, R-1 and R-2)

| | |
|---|---|
| **Status** | **DECISION PACKAGE.** Prepares decisions P1–P5 for the Principal. It decides, approves and authorises nothing. No code, route, policy, schema, test, permission or production data was changed. |
| **Authority** | generated (Claude; security architecture and DDD analysis). The author also wrote DR-07a and PO-SEC-TRIAGE-01. The independent review is IR-SEC-01. |
| **Date** | 2026-11-03 |
| **Snapshot** | branch `election-flow-updated`, HEAD `f826d275b`. No change under `app`, `routes`, `database`, `resources`, `config`, `bootstrap` or `docs/publicdigit/backlog` since IR-SEC-01 was written. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0) |
| **Sources** | IR-SEC-01 `docs/publicdigit/architecture/reviews/2026-11-03-ir-sec-01-independent-review.md` (INDEPENDENT REVIEW; "CONFIRMED WITH CORRECTIONS") · PO-SEC-TRIAGE-01 `…/2026-11-03-po-security-triage-pbdigit-266-r1-r2.md` · DR-07a `…/2026-11-03-dr07a-membership-technical-discovery-m1-m5.md` · PBDIGIT-266 `docs/publicdigit/backlog/PBDIGIT-266-live-routes-without-a-found-authorisation-guard.md` · PO-ORG-MEM-01 (Principal / PO, 3 November 2026; **stated in session; no repository decision record exists yet**) · ACT §11 `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md:319-321` · D-4 `docs/publicdigit/reviews/2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md:293` |

**Labels.** [F] fact (static source) · [D] existing decision · [P] proposal · [I] inference · [OQ] open question. All technical findings are **static**. Nothing was run, requested or queried. No exploitation is claimed.

---

## 1. Executive security assessment

1. **R-1 is the immediate, working exposure.** [F] The add-member POST lets any verified user with any `user_organisation_roles` row for the organisation assign any committee role, including `chair` and `deputy`. It also silently changes an existing member's role on a repeat call. It is the only working committee-member path in the UI.
2. **R-1 removal is a latent exposure.** [F] DELETE has the same missing authority check, but it currently fails with 500 because `RemoveCommitteeMemberHandler.php:10` imports a non-existent `App\Contexts\Governance\Domain\Committee\MemberId`. A one-line repair of that import would activate it.
3. **R-2 is a latent but complete authority vulnerability.** [F] Application review has no organisation-membership, role or self-review check at any layer. It currently cannot persist anything, because the application repository always INSERTs an existing primary key. **Any repair of that persistence would activate the vulnerability.**
4. **Attribution is missing.** [F] R-1 records no acting user. R-2's `reviewed_by` / `reviewed_at` are never persisted.
5. [P] **Principle for every decision below:** close who can *execute* a sensitive operation now with labelled interim controls. Leave who is *constitutionally entitled* to decide to statutes and Governance. Never repair a blocked path before its protection is in place.

---

## 2. Corrected technical status

### 2.1 R-1: Governance committee-member management

| Aspect | Status | Evidence |
|---|---|---|
| POST add member | **working unauthorised write** | IR-SEC-01 §A (a), §D.1; PO-SEC-TRIAGE-01 §3.2 |
| Chair / deputy assignment | **possible for any organisation member** (`CommitteeRole::from(request role)`; enum chair, deputy, member, observer) | `app/Http/Controllers/Api/Governance/CommitteeMemberController.php` store; `…/Governance/Domain/Committee/Enums/CommitteeRole.php:9-12` |
| Repeat POST | **overwrites the role and returns 201** (blank aggregate; `updateOrCreate`) | IR-SEC-01 finding F7 |
| DELETE remove member | **authority gap present; path blocked**: non-existent `MemberId` import → generic 500. PBDIGIT-266's 2026-10-09 revision already records "destroy always 500s" | `app/Contexts/Governance/Application/Handlers/RemoveCommitteeMemberHandler.php:10, :24`; IR-SEC-01 §A (1) |
| Existing `manageCommittee` policy | owner / admin row in `user_organisation_roles`; **not applied** on this path | `app/Policies/OrganisationPolicy.php:46-60` |
| Injected `CommitteeAssociationLifecyclePolicy` | never invoked, and not an authority control even if it were | IR-SEC-01 |
| Tenant isolation | **holds**: committee and member are both checked against the header organisation | IR-SEC-01 §D.1; PO-SEC-TRIAGE-01 §3.2 |
| Actor attribution | **none durable**: events and projection carry no actor; `store` logs without user id; `destroy` logs nothing | IR-SEC-01 |
| Live UI | `Pages/Committee/Dashboard.vue` → `Components/CommitteeMemberManager.vue` → `/api/v1/governance/committees/{id}/members` | PO-SEC-TRIAGE-01 §3.3 |
| Slug-based mounts | writes return 400 (no `TenantContext`) | IR-SEC-01 |
| Other | failed store returns exception message and class; `tenant.api` runs before `auth` (guest behaviour unverified) | IR-SEC-01 F12, F14 |

### 2.2 R-2: committee-membership application review

| Aspect | Status | Evidence |
|---|---|---|
| Organisation-membership check | **missing** (route group `auth, verified` only) | `routes/committee/committeeRoutes.php:26, :80` |
| Role / authority check | **missing** at request, controller, handler and domain | `…/Requests/Desktop/ReviewMembershipApplicationRequest.php:11-14`; IR-SEC-01 §D.2 |
| Self-review restriction | **missing** in handler and domain | `app/Contexts/Membership/Domain/Membership/MembershipApplication.php:102-125` |
| Application lookup scoping | explicit `organisation_id` = route organisation **plus** the `BelongsToTenant` global scope (session / `TenantContext`), so cross-organisation review most likely fails with not-found. **Partly confirmed only** | IR-SEC-01 §A (3) |
| Persistence defect | `saveForTenant` always `Model::create()` on the application's existing ULID primary key (`string('id', 26)`), so review fails on the first write, **approve and reject alike**; nothing persists | `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeMembershipApplicationRepository.php:19-31`; migration `…/Tenant/2026_05_14_000001_create_committee_membership_applications_table.php:13` |
| Duplicate association persistence | real (lineage repository and association repository both write), **unreachable** until the first write is repaired, and then itself failing (duplicate-active check; `lineage_id` NOT NULL) | IR-SEC-01 §A (2); DR-07a §4 |
| Reviewer attribution | `reviewed_by` / `reviewed_at` **never persisted** | IR-SEC-01 §A (4) |
| Activation risk | **repairing persistence before authority activates the full vulnerability** | IR-SEC-01 §A, "Priority consequence" |
| UI | no frontend caller found | PO-SEC-TRIAGE-01 §4.1 |

---

## 3. Decision register P1–P5

### P1: R-1 priority

| Field | Content |
|---|---|
| Question | Should the working unauthorised committee-member POST path receive immediate remediation priority? |
| Why needed | It is a working path by which any organisation member can change committee composition and office (chair, deputy) |
| Evidence | §2.1; IR-SEC-01 §A (a) |
| Options | **YES**: schedule containment (P3) and tests (P5) now. **NO**: accept the risk until the permanent Governance model exists (DR-07a migration) |
| Recommendation [P] | **YES** |
| Consequences | YES: one interim story under PBDIGIT-266. NO: the exposure stays open for the duration of strategic work, with no timeline |
| Dependencies | P3, P5(a) |
| Implementation implications | none by itself; priority authorises nothing |
| Approval authority | Principal / PO |

### P2: R-2 priority

| Field | Content |
|---|---|
| Question | Should the R-2 authority vulnerability receive immediate security priority, although its persistence is currently defective? |
| Why needed | The vulnerability is complete in authority terms; only an unrelated defect blocks it, and any routine persistence fix activates it |
| Evidence | §2.2; IR-SEC-01 §A (2), "Priority consequence" |
| Options | **YES**: protect first (P3), then any persistence repair. **NO**: leave as is and rely on the defect |
| Recommendation [P] | **YES.** [P] A defect is not a control: "NO" depends on nobody fixing the persistence |
| Consequences | YES: protection must precede persistence work. NO: a persistence fix by any session would expose self-approval |
| Dependencies | P3, P5 |
| Implementation implications | **Priority does not authorise persistence repair** |
| Approval authority | Principal / PO |

### P3: interim containment

#### P3-R1: R1-a

| Field | Content |
|---|---|
| Question | Apply the existing `manageCommittee` policy as interim containment on the Governance committee-member endpoints? |
| Scope proposed [P] | `store` (POST) **and** `destroy` (DELETE), both now. See note |
| Not in scope | repairing the missing `MemberId` import; role-specific rules for chair / deputy; attribution (R1-b) |
| Label | **"interim platform-access restriction"**. Owner / admin is not constitutional authority (PO-ORG-MEM-01) |
| Operational consequence | owners and admins keep the dashboard function; ordinary members lose an ability they should not have; DELETE stays non-functional (500) until separately repaired |
| Review condition [P] | replaced when Governance authority (P4) is decided; review at each PBDIGIT-266 slice acceptance |
| Approval authority | Principal / PO |

**Note on DELETE.** The commission proposed guarding DELETE "when that path is addressed". This package recommends guarding it **at the same time as POST**, for the same reason as R-2: the import defect is a one-line fix, and if it is repaired without a guard, removal becomes a live exposure. Guarding `destroy` does not require repairing the import. This is a recommendation; the Principal decides.

#### P3-R2: R2-a or explicit feature-off

| Option | Content | Operational consequence |
|---|---|---|
| **R2-a** | require organisation membership (e.g. `ensure.organisation`); require the selected interim owner / admin restriction; prohibit self-review (reviewer ≠ applicant); all checks **before** any persistence change | no UI uses review, so low disruption; review remains broken until a separately authorised persistence repair |
| **Explicit feature-off** | review refused deliberately (e.g. a guarded "not available" response) with a test asserting it | strongest; no functional loss today (the feature cannot persist anyway) |
| Not acceptable as a control [P] | relying on the current persistence failure | any persistence fix silently activates the exposure |

- **Recommendation [P]:** R2-a. If R2-a cannot be implemented and verified before any persistence work, then explicit feature-off.
- **D-4 scope:** extending owner / admin to committee-association review is **a new interim decision**, not an application of D-4 (§3, P4).
- **Approval authority:** Principal / PO.

### P4: permanent authority source

| Question | Needs | Who |
|---|---|---|
| Who may appoint committee members? | statutes (O-10 / CQ-2) | organisational representative; PO records |
| Who may remove committee members? | statutes | same |
| Who may appoint chairs and deputies? | statutes (position-specific) | same |
| Who may approve or reject committee-membership applications? | statutes (CQ-3) | same |
| What does `CommitteeAssociation` represent? | business evidence (M-2 Q1–Q4; OQ-DR07-1) | organisational representative / PO |
| Does D-4 extend to committee-association approval? | **explicit PO decision**. [F] D-4 covers Membership "Authority to admit and end" via an interim adapter mirroring owner / admin, "replaceable by the Governance-owned implementation" (`…slice2-decision-closure.md:293`). IR-SEC-01 §E.3: on current evidence it does **not** extend | PO |
| What evidence must be retained (actor, organ, decision reference, rule, version)? | ARB (evidence contract; OQ-12 / OQ-13 of the preparation document) | ARB |
| What recusal rules apply (e.g. applicant, conflicts of interest)? | statutes (CQ-7) | organisational representative |
| Where permanent authority is enforced | ARB (DR-07a ARB-5; P11 / R-08; XD-4) | ARB |

**Not invented here:** no rule above is decided. Interim controls do not answer any of them.

### P5: separate implementation authorisations

Each is an independent request. Approval of one is not approval of another.

**P5(a): test authorisation (RED)**

| Item | Content |
|---|---|
| Scope | PBDIGIT-266 feature tests for R-1 (POST, DELETE) and R-2 (approve, reject), per IR-SEC-01 §G.1–§G.2 |
| Cases | same and other organisation; ordinary member; owner / admin; chair / deputy assignment; repeat POST role overwrite; self-review; approval and rejection; current persistence defects (R-2 INSERT; R-1 DELETE import) |
| Database | **isolated test database only**; never development or production |
| **Gate** | [F] ARB-SEC-01: tests authorised only after isolated-database readiness; that gate **failed on 2026-10-09** and has not been re-checked (IR-SEC-01 §G). [F] An isolated PostgreSQL container exists for Membership tests (PBDIGIT-324); [OQ] whether it may host these Governance / committee feature tests, or O-4 must be satisfied first |
| R-2 test design note [P] | while persistence is broken, a "403 expected" test fails with 500 for the wrong reason. R-2 tests must assert the **reason** for refusal and the **persisted state**, so authority evidence is not confounded by the persistence defect |
| Approval authority | Principal / PO; gate per ARB-SEC-01 |

**P5(b): containment implementation**

| Item | Content |
|---|---|
| Scope | R1-a (POST and DELETE guards); R2-a **or** explicit feature-off |
| Method | TDD under PBDIGIT-266: the RED tests from P5(a), then minimal GREEN |
| Ordering constraint | **No R-2 persistence repair before verified R2-a or explicit feature-off** |
| Persistence repairs (R-2 INSERT; duplicate association; transaction; R-1 DELETE import) | **separate scope, separate approval**, after containment is verified |
| Excluded | any permanent authority rule; changes to D-4; changes to ratified Membership architecture |
| Approval authority | Principal / PO, per slice |

**P5(c): production exposure checks**

| Item | Content |
|---|---|
| Scope | read-only production logs (calls to `api/v1/governance/committees/*/members` and `…/committee/membership/review`); read-only data (projection rows with chair / deputy and their creation; applications whose reviewer equals the applicant, if any persisted) |
| Conditions | explicit Operations authorisation; privacy and access restrictions; no production writes; no remediation |
| Approval authority | Principal / PO and Operations |

---

## 4. Interim containment comparison

| | R1-a | R1-b | R1-c | R2-a | R2-b | R-2 feature-off |
|---|---|---|---|---|---|---|
| What | `manageCommittee` on POST + DELETE | + actor attribution; chair / deputy restriction | Governance authority per statutes | membership + owner / admin + no self-review | + persistence repair (transaction; single association write) | explicit refusal |
| Closes the working exposure | yes (POST) | yes | yes | yes (latent path) | — | yes |
| UI impact | members lose add / remove; owners / admins keep it | as R1-a | migration | none (no UI) | enables review for authorised users | none today |
| Authority assumption | interim platform access | interim; role restriction needs statutes | statutes | interim; new PO decision on D-4 scope | as R2-a | none |
| Reversible | yes | yes | n/a | yes | yes | yes |
| Ordering | first | after R1-a | after P4 | **before any persistence repair** | **only after R2-a verified** | alternative to R2-a |
| Approval | PO | PO | ARB + PO | PO | PO (separate) | PO |

---

## 5. Permanent authority questions

See P4. [P] Contained by interim controls; answered only by statutes (organisational representative), PO decisions and ARB acts. [D] PO-ORG-MEM-01 makes the boundary explicit: technical ownership and platform access permissions do not constitute organisational authority.

---

## 6. Implementation authorisation boundaries

| May proceed only after | Activity |
|---|---|
| P1 / P2 | prioritisation in the backlog |
| P3 | design of the selected containment |
| P5(a) + gate | writing and running the RED tests on the isolated database |
| P5(b) | containment code |
| P5(b) persistence scope (separate) | R-2 INSERT fix; duplicate association removal; transaction; R-1 DELETE import fix |
| P5(c) | production read-only checks |
| P4 outcomes + ARB | permanent Governance authority |

---

## 7. Dependency and sequencing analysis

```text
 P1, P2 (priorities)
   └─► P3 (select interim containment: R1-a; R2-a or feature-off)
         └─► P5(a) authorisation + ARB-SEC-01 gate (isolated DB)
               └─► RED tests establish the defects (authority; persistence)
                     └─► P5(b) containment implemented and verified (GREEN)
                           └─► [separately authorised] persistence repairs
                                 R-2 INSERT · duplicate association · transaction · R-1 DELETE import
 P5(c) production exposure checks ── independent, read-only, any time after approval
 P4 statutes / PO / ARB ── independent; replaces interim controls when decided
```

**Safety constraint (mandatory):** R-2 persistence repair must not be deployed before R2-a or explicit feature-off.

[P] **Extension (recommended):** the same constraint for the R-1 DELETE import repair: no repair before the `destroy` guard.

**Possible safer variant [P, not authorised]:** if the isolated-database gate (ARB-SEC-01) delays the RED tests, the PO may consider explicit R-2 feature-off as an immediate, test-independent measure. It removes no working function. R-1 containment cannot be done this way without losing the only member-management UI.

---

## 8. Test scenarios and expected outcomes

The decision-ready matrix is IR-SEC-01 §G.1 (R-1) and §G.2 (R-2). It is not duplicated here. Key expected outcomes after containment:
- ordinary member → 403 for POST (any role), repeat POST and DELETE;
- other organisation → 403 or 404;
- owner / admin → 201 / 202;
- R-2: applicant reviewing own → 403;
- non-member → 403;
- owner / admin → 200 only after separately authorised persistence repair;
- failure injection → all-or-nothing.

[P] Additionally: an explicit test that the R-1 DELETE guard refuses an ordinary member **independently of** the import defect, e.g. by asserting the refusal occurs before the handler is reached.

---

## 9. Residual risks after interim containment

| # | Risk | Remains because |
|---|---|---|
| RR-1 | owner / admin (platform access) can still change committees and review applications without constitutional authority | interim by design; ends with P4 |
| RR-2 | no durable actor record for committee changes | R1-b not yet selected |
| RR-3 | historical exposure unknown | P5(c) not yet run |
| RR-4 | R-2 and R-1 DELETE functionality broken | persistence repairs separately scoped |
| RR-5 | other routes in PBDIGIT-266 (item 1 election approval, "the most severe"; item 4 finance) | out of scope; separate investigation needed |
| RR-6 | tests under the existing names keep false confidence | IR-SEC-01 §G.3; rewritten in P5(a) |

---

## 10. Decisions awaiting the Principal

1. **P1**: R-1 priority (YES / NO).
2. **P2**: R-2 priority (YES / NO).
3. **P3-R1**: R1-a scope: POST only, or POST **and** DELETE (recommended).
4. **P3-R2**: R2-a or explicit feature-off; and the new interim decision whether owner / admin is used for committee-association review (not an extension of D-4 by inference).
5. **P4**: commission the authority evidence (statutes; M-2 answers) and route the evidence-contract questions to the ARB.
6. **P5(a)**: authorise RED tests; decide which isolated database satisfies ARB-SEC-01 (PBDIGIT-324 container or O-4).
7. **P5(b)**: authorise containment implementation; confirm that persistence repairs are a separate later approval.
8. **P5(c)**: whether and when to run read-only production exposure checks.
9. **Record keeping**: enter PO-ORG-MEM-01 and these decisions in a decision record.

---

## 11. Source inconsistencies and unverified claims

| Item | Note |
|---|---|
| PO-SEC-TRIAGE-01 corrections | superseded on six points by IR-SEC-01 §A (DELETE blocked; R-2 persists nothing; cross-organisation partly; `reviewed_by` not persisted; ULID not UUID; sequencing risk). PO-SEC-TRIAGE-01 is not edited |
| PBDIGIT-266 | its 2026-10-09 revision records the DELETE 500; item 2 / item 3 text predates the IR-SEC-01 findings |
| Unverified (runtime) | every HTTP outcome in §2 and §8; guest behaviour under `tenant.api`-before-`auth`; whether any production call or row exists |
| PO-ORG-MEM-01 | no repository record yet |

---

## 12. Traceability

- IR-SEC-01 §A, §D–§H; PO-SEC-TRIAGE-01 §3–§11; DR-07a §3–§4, §7.
- PBDIGIT-266 (items 2, 3; required handling).
- PO-ORG-MEM-01 (session, 3 November 2026); ACT §11; D-4; ARB-SEC-01 (as cited in IR-SEC-01 §G).

No code, tests, schema, permissions, routes, policies, ADRs, BDRs, ratified records or production data were changed. Nothing was committed.
