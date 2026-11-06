# DR-07a: Membership technical discovery, M-1 to M-5

> **This report is technical discovery and recommendation. It does not approve an architecture change, freeze a legacy model, amend a ratified decision or authorise implementation.**

| | |
|---|---|
| **Status** | **TECHNICAL DISCOVERY, DRAFT EVIDENCE PACKAGE.** Prepared for the technical Membership owner. The answers to M-1…M-5 become the owner's answers only when the owner adopts them (possibly amended). They require an **independent review** before any architectural decision relies on them (§11). |
| **Authority** | generated (Claude; software architecture, DDD and requirements roles). Not an independent review. |
| **Authorisation** | [D] **PO-ORG-MEM-01 Decision B**, "DR-07a M-1–M-5 AUTHORISED" (Principal / PO, 3 November 2026; stated in session, not yet entered in a decision record). Constraints of that decision are respected: no code, schema or test change; no change to ratified architecture, ADRs, BDR, context map or Platform Rules Index; no migration or freeze; no commit. |
| **Technical owner** | Nab (stated by the Principal, 2026-11-03; preparation document §4.4). |
| **Date** | 2026-11-03 |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0). |

**Labels.** [F] verified fact · [D] existing decision · [P] proposal or recommendation · [OQ] open question · [EO] engineering observation · [A] assumption. **Finding classes:** VERIFIED · PARTIALLY VERIFIED · NOT VERIFIED · NOT IMPLEMENTED · CONFLICT · OPEN QUESTION.

**Paths.** M/ = `app/Contexts/Membership/` · G/ = `app/Contexts/Governance/` · DR07 = `docs/publicdigit/reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md` · ACT = `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md` · RAT = `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md`.

---

## 1. Executive summary

1. **M-1.** DR-07 §4's inventory of the committee write model is **correct in what it states, but incomplete**.
   - It omits three live write paths (committee update; committee create that bypasses the aggregate; a "canonical" create endpoint that persists nothing).
   - It omits four repository implementations (one a no-op), bound-but-unrouted handlers, and seven committee tables.
   - Four facts change how the inventory should be read:
     - (a) the **only web path that writes `committee_assignments` is broken** at the DTO boundary;
     - (b) the domain **role limits are dead code**, while a database index allows exactly one holder per role path;
     - (c) **nothing reads `committee_assignments`** except its own write repository;
     - (d) **no committee write path records authority evidence or audit**.
2. **M-2.** In code, `CommitteeAssociation` is a tenant-scoped, append-only status record linking a member to a `committees` row, with a join reason (residence / exception / manual) and status (active / suspended / terminated). It has **no role, position, term or authority**.
   - The strongest evidence points to **belonging to an organisational unit** (branch or wing), matching the candidate "UnitAffiliation".
   - The code cannot settle M-2, because one `committees` table models both organisational units and organs. That needs business confirmation.
   - The only live writer (application approval) shows a **double write that, read statically, should fail**.
3. **M-3** [P, for the owner's adoption]. Recommend **option A** (Governance owns governance organs and their positions, appointments and authority), applied to **organs only**, with organisational units handled under DR-07b.
   - The current implementation both **contradicts** the boundary: governance-authority logic sits in Membership, Governance depends on Membership types, and there are three membership stores.
   - It also **represents it only incompletely**: the Governance model is a stub.
4. **M-4** [P]. Distinguish three freeze actions:
   - **freeze new functionality and dependencies: recommended now**;
   - **freeze the committee schema except security and bug fixes: recommended**;
   - **stop write paths and migrate: not now**.
   - **Important:** the legacy model is **not a reliable behavioural reference** for assignments (broken assign path, dead limits, non-persisted governance state, history-corrupting save). Option B's "legacy as oracle" premise needs re-scoping by the ARB (ARB-6).
5. **M-5.** Twenty risks are registered (§7). The most significant:
   - the **live dashboard UI writes committee members through the Governance API, which has no authorisation check** (already tracked as PBDIGIT-266 item 3; the UI dependency is new evidence);
   - the L-3 history overwrite;
   - three divergent membership stores;
   - database constraints that exist **only on PostgreSQL**, while the production engine is not established.
6. **Everything here is static reading.** No test, query or request was run. Production usage and row counts are **NOT VERIFIED**.

---

## 2. Repository snapshot and verification method

| Item | Value |
|---|---|
| Branch / HEAD | [F] `election-flow-updated` / `b95fe8fc4` (2 KnowledgeOS commits after `508283527`; no change under `app`, `database`, `routes`, `docs/publicdigit`) |
| HEAD references in the preparation document | [F] resolved: `f61553a5d` was HEAD when r1 was written (09:02), `508283527` when r2 was written (09:05); both are ancestors of HEAD. They are two snapshots, not an error. The preparation document's §2 still shows the r1 snapshot; it is not edited here |
| Working tree | [F] other sessions' uncommitted changes (`.claude/`, `developer_guide/election/ip_voting/*`, KnowledgeOS scratch, `bootstrap/cache/packages.php`); untracked review documents and `tests/Isolated/Pbdigit274/...`. Not touched |
| Method | three parallel read-only evidence sweeps (M-1; M-2; M-4 / M-5), then direct verification by the author of every finding used for a recommendation or a risk rated high (listed in §11). Static reading only; `php artisan route:list` was the only command run, and it only reads |
| Limits | no runtime execution; no database access; cached route list may be stale (dated 2026-10-19); dynamic container resolution, queued jobs and seeders may hide further write sites; `.claude/worktrees/` hits ignored |
| Scope of M-1 | per PO-ORG-MEM-01 Decision B ("Current Membership technical structure"): the committee write model in full; the rest of the Membership context as a structural map (§3.1) |

---

## 3. M-1: Membership technical structure and committee write-model inventory

### 3.1 Structural map of the Membership context

| Part | Size | Status | Evidence |
|---|---|---|---|
| Ratified kernel (`Domain/Organisational`, `Application/Organisational`, `Infrastructure/Organisational`) | ≈ 60 files | implemented and accepted slice by slice (PBDIGIT-258…329); **bound to nothing** in providers or routes | [F] earlier baseline report §2; this session's sweeps |
| Committee write model (`Domain/Committee`, `Application/Committee`, `Application/CommitteeStructure`) | 188 + 38 + 3 files | legacy; subject of DR-07 | §3.2 |
| Committee association / lineage (`Domain/Membership/*`, `Application/Membership/*Committee*`) | — | legacy; subject of M-2 | §4 |
| Other legacy (members, fees, applications, models, controllers) | remainder of 555 files | live in production | [F] earlier sweeps |
| Kernel ↔ committee code | **0 references in either direction** (`grep -rni committee` over the three `Organisational` folders) | VERIFIED: the ratified kernel is already free of committee concerns, as act §10 requires | [F] |

### 3.2 Committee domain inventory (`M/Domain/Committee/`, 188 files)

| Folder | Files | Holds | Note |
|---|---|---|---|
| root | 17 | `Committee` aggregate (1,155 lines), `CommitteeAssignment`, `ConstitutionalCommittee` (a second aggregate), structure registry and activation, `GovernanceState`, `GeoPolicy` / `GeoScope`, creation policy | [EO] two committee aggregates |
| Constitutional | 66 | arbitration kernel, doctrine, replay, snapshot, timeline, lineage, `GovernanceDecisionStore` | [EO] governance-authority logic (ACT §11: Governance's competence) |
| Geo | 26 | authority resolution, conflict detection, delegation graph, precedence, `GovernanceDecisionKernel` | [EO] same |
| Capability | 19 | capability policy engine (`ActorChairsCommitteeRule`, `ActorHasAuthorityRule`, …) | [EO] same |
| ValueObjects | 15 | includes a second `CommitteeId` (UUID; the other is ULID), `TermPeriod`, `GovernanceAssignment`, `Jurisdiction` | [EO] two `CommitteeId` types, bridged |
| Strategies | 7 | committee-type strategies with `canAssignRole` and `ROLE_LIMITS` | see §3.5 |
| Policies, Exceptions, Context, Events, Services, Ports, Factories, Repositories, others | 44 | — | — |

**111 of 188 files (Constitutional + Geo + Capability)** hold governance-authority logic inside the Membership context [F counts; EO classification]. This supports recorder's note N-6 ("a known legacy boundary defect", not binding) with code evidence.

### 3.3 Write paths

| # | Entry point | Application path | Persistence | Authority check | Audit / evidence | Finding |
|---|---|---|---|---|---|---|
| W1 | `POST organisations/{o}/committees/{c}/members` (`routes/committee/committeeRoutes.php:58`) → `app/Http/Controllers/Committee/CommitteeMemberController.php:24-57` | `AssignMemberToCommittee` → `Committee::assignMember` | `committee_assignments` | `manageCommittee` (owner / admin, `app/Policies/OrganisationPolicy.php:46-60`) | event dispatched, **no listener**; `appointed_by_user_id` never set | **CONFLICT, broken.** The controller passes strings where `AssignMemberDto` requires `MemberId` and `NominationType` objects (`:40, :42`; DTO `:21, :23`): a TypeError, caught by `catch (\Throwable)` (`:54-56`) and returned as a redirect with an error. It also validates `member_id` as a **user** id (`uuid\|exists:users,id`, `:30`) for a `varchar(26)` column. VERIFIED (static) |
| W2 | `DELETE …/members/{assignmentId}` (`:61`) → `::remove` (`:59-76`) | `RemoveMemberFromCommittee` → `Committee::endAssignment` | `committee_assignments`; triggers L-3 | `manageCommittee` | `endAssignment` records no event | VERIFIED |
| W3 | `PATCH …/committees/{c}` (`:54`) → `CommitteeManagementController::update` (`:142-193`) | `UpdateCommitteeDetails` → plain setters, no invariant | `committees`; the full save also triggers L-3 | `manageCommittee` | none | VERIFIED; **not in §4** |
| W4 | `POST …/committees` (`:50`) → `CommitteeManagementController::store` (`:91-121`) | **none**: direct `CommitteeModel::create` (`:103`) | `committees` | `manageCommittee` | none | VERIFIED bypass of the aggregate; **not in §4** |
| W5 | `POST organisations/{o}/committees/api/create-canonical` (`routes/organisations.php:406-411`) → `CreateCommitteeController::store` | `CreateCommitteeHandler` → `ConstitutionalCommittee::establish` | **nothing**: the bound repository `M/Infrastructure/Repositories/CommitteeRepository.php:14-18` has an empty `save()`; the event has no listener | `auth()->check()` only | none | VERIFIED (static): returns 201 with an id that exists nowhere; **not in §4** |
| W6 | `POST /api/v1/governance/committees/{id}/members` (`routes/api_v1.php:52-53`) and the `routes/governance-api.php` mount → `app/Http/Controllers/Api/Governance/CommitteeMemberController.php` store | Governance `Committee::addMember` on a **blank aggregate** (`G/Infrastructure/Repositories/EloquentCommitteeRepository.php:31-48`) | `committee_member_projection` (`updateOrCreate`) | route middleware only (authenticated, verified, organisation membership); the injected `$guard` (`:44`) is never used | none | VERIFIED; in §4 (G-7). **Used by the live UI** (§6) |
| W7 | `DELETE …/governance/committees/{id}/members/{memberId}` → destroy | `removeMember` (unconditional) | projection hard delete, no tenant filter | as W6 | none | VERIFIED |
| W8 | `POST …/committee/membership/apply` and `/review` (`committeeRoutes.php:78-81`) | `ApplyForCommitteeMembershipHandler`, `ReviewMembershipApplicationHandler` | `committee_membership_applications`; `committee_associations` (double write, §4) | `ReviewMembershipApplicationRequest::authorize` = authenticated and verified email (`:11-14`); no check in the handler; route group `auth, verified` only (`committeeRoutes.php:26`) | actor columns on `committee_associations` | VERIFIED; already tracked as **PBDIGIT-266 item 2** |
| W9 | console `governance:rebuild-projections` | `GovernanceProjectionRebuilder` | Governance projection | CLI | rebuild-run table | PARTIALLY VERIFIED |

**Bound but not reachable from HTTP** (no production caller found): `CreateCommitteeUseCase` / `InternalCreateCommittee`; `AssignMemberToCommitteeHandler` (writes `committee_associations`, not `committee_assignments`); `StoreCommitteeUseCase`; `GovernanceSetupController` (no route); `GovernanceReplayService` / `GovernanceDecisionStore`; aggregate methods `updateMemberRole`, `removeMember`, `suspend`, `restore`, `dissolve`, `attachToParent`, `startTerm`, `extendTerm`; `CommitteeAssignment::reactivate`. VERIFIED by grep.

**Repository implementations** (four, all bound):

| Interface → implementation | Bound at | Persists |
|---|---|---|
| `Domain\Repositories\CommitteeRepositoryInterface` → `Infrastructure/Repositories/EloquentCommitteeRepository` | `MembershipServiceProvider.php:234-237` | `committees`, `committee_assignments` |
| `Domain\Committee\Repositories\CommitteeRepositoryInterface` → `EloquentCommitteeAggregateRepository` | `:244-247` | `committees` only; loads no assignments |
| `Application\Committee\Ports\CommitteeRepositoryPort` → `Infrastructure/Repositories/CommitteeRepository` | `AppServiceProvider.php:47-50` | **no-op** |
| `Infrastructure\Persistence\CommitteeRepositoryPort` → `Persistence/EloquentCommitteeRepository` | `RepositoryServiceProvider.php:24` | used only by the uncalled `StoreCommitteeUseCase` |

**Read paths:**
- The dashboard member list reads `committee_associations` (`app/Contexts/Committee/Infrastructure/Persistence/EloquentCommitteeMembershipReadModelAdapter.php:19-53`).
- The Governance index reads `committee_member_projection`.
- **No read model or UI reads `committee_assignments`** (VERIFIED by grep).

### 3.4 Tables

| Table | Tenant column | FK to organisations | Code references outside migrations |
|---|---|---|---|
| `committees` | `organisation_id string(50)` | no | yes |
| `committee_assignments` | `organisation_id uuid` (type mismatch with `committees`) | no | write repository only |
| `committee_structures` / `_levels` | uuid / via FK | no | yes |
| `committee_associations` | uuid | yes (cascade) | yes |
| `committee_membership_applications` | `string(50)` | no | yes |
| `committee_member_projection` | `tenant_id uuid` | no | yes |
| `committee_role_mappings` | `tenant_id string(50)` | no | **none** |
| `role_hierarchy`, `constitutional_committees` | **no tenant column** | — | **none** |
| `governance_decisions` | **no tenant column** | — | Governance decision store (unrouted) |

### 3.5 Invariants enforced today

| ID | Invariant | Domain | Database | Test evidence | Finding |
|---|---|---|---|---|---|
| I-1 | central has no geography; geographic types need geography | `Committee.php:127-151, 847, 895` | `chk_central_committee_no_geography`, `chk_non_central_must_have_geography` (**PostgreSQL only**, `…000002…:108-130`) | `tests/Unit/Contexts/Membership/Domain/Committee/CommitteeTest.php:28-56` | VERIFIED domain; DB conditional (I-9) |
| I-2 | assign only to an active committee | `:515-517` | — | none found | domain only |
| I-3 | not assigned to a dissolved committee | `:520-522` | — | `GovernanceStateTest.php` (state object only) | **PARTIALLY VERIFIED: `GovernanceState` is not persisted** by the Eloquent repository (`serialize :300-329`), so dissolution does not survive a reload |
| I-4 | one active assignment per member per committee | `:525-527` | `unique_active_assignment` partial index (PostgreSQL only) | happy path only | VERIFIED; no negative test |
| I-5 | role path allowed by the committee strategy | `:530-539` | — | none found | domain only |
| I-6 | role limits per position | `:541, :769-800` | `unique_active_role_per_committee (committee_id, role_path) WHERE is_active` allows **one** holder per path (PostgreSQL only) | `CommitteeTest.php:107-122` reads the array only | **CONFLICT, dead code.** `ROLE_LIMITS` is keyed by names (`'chairperson' => 1`, `'vice_chairperson' => 2`, …, `CentralCommitteeStructure.php:36-44`), while `RolePath` must be numeric (`RolePath.php:66`) and the lookup uses the numeric value; no `.*` pattern keys exist. Domain intent (2 vice-chairs, 5 coordinators) vs DB (1 per path) vs enforcement (none) |
| I-7 | member geography covered | `:544-560` | — | none found | domain only; W1 never passes geography |
| I-8 | assignment dates (elected ⇒ election date; term end ≥ election; left ≥ joined) | `CommitteeAssignment.php:119-126, 212-214, 325-337` | three CHECKs (PostgreSQL only) | — | both |
| I-9 | the database constraints above exist | — | created only when `config('database.default')` is `pgsql` or `tenant_test` (`…000002…:108`; `…000003…:103`); default is `env('DB_CONNECTION','mysql')` (`config/database.php:18`) | — | **OPEN QUESTION**: production engine not established |
| I-10 | historical `left_date` preserved | — (load keeps only active rows, `EloquentCommitteeRepository.php:52-54`) | none | none; `CommitteeRepositoryTest.php` asserts active counts only | **CONFLICT (L-3).** Every save rewrites `left_date = now()` on every row of the committee not in the aggregate, without an "already ended" guard or tenant filter (`:143-149`). W1, W2 and W3 all save, so even a committee rename overwrites tenure history |
| I-11 | `updateMemberRole` checks the role | `:716` passes a `RolePath` to a `string` parameter under `strict_types` | — | none | CONFLICT (latent; no caller) |

No test asserts any invariant message of `Committee::assignMember` (grep). `CommitteeManagementHttpTest.php:243-257` accepts 200, 302 or 422 for the assign route, so it passes although W1 fails.

### 3.6 M-1 answer [P, for the owner]

§4's statements check out against the code. It is **not complete**: it omits W3, W4, W5, the W8 authorisation gap, three of the four repository bindings, the unrouted handlers, seven tables, and the facts in §1 item 1 (a)–(d). Its inventory should be amended before ARB-5/6 rely on it. Amending it is for the paper's owner; the paper is not edited here.

---

## 4. M-2: `CommitteeAssociation`

| Aspect | Current technical behaviour | Apparent business meaning | Evidence | Uncertainty |
|---|---|---|---|---|
| Structure | value object: association id, member id, committee id, reason (residence / exception / manual), associated at, status, optional actor / transition data | a person's standing in a "committee" and why they joined; **no role, position, term or authority** | `M/Domain/Membership/CommitteeAssociation.php:16-35`; `ValueObjects/ApplicationReason.php` | VERIFIED |
| Table | `committee_associations`: tenant uuid (FK cascade), member id string (no FK), committee id (FK cascade), reason, status, audit columns; `lineage_id` NOT NULL (`…2026_05_14_000002…:13-14`); **no unique constraint** for "one active per member and committee" | tenant-scoped relation | `database/migrations/2026_05_14_000001_create_committee_associations_table.php:11-42` | VERIFIED |
| Lifecycle | active → suspended / terminated; suspended → active / terminated; terminated final; episodes grouped by `MembershipLineage` | lifecycle of a membership with institutional reasons | `CommitteeAssociationLifecyclePolicy.php:19-78`; `MembershipLineage.php:21-97` | VERIFIED |
| Live writer | membership-application approval: lineage save (writes an active row), then a second "backward compatibility (Elections context queries)" save that checks for a duplicate active row and inserts **without `lineage_id`** | joining = an approved application to that "committee" | `ReviewMembershipApplicationHandler.php:47-60`; `EloquentCommitteeAssociationRepository.php:27-50`; policy `:44-57` | **CONFLICT (static):** the second save should throw `DuplicateActiveAssociationException` or violate NOT NULL `lineage_id`. Not run; the feature test `tests/Feature/Contexts/Membership/CommitteeMembershipApplicationTest.php:144-166` asserts 200, possibly through test doubles. **NOT VERIFIED at runtime** |
| "Elections context" consumer | none found in Election or Elections code | — | grep | the comment's claim is unsupported |
| Other writers | `AssignMemberToCommitteeHandler` (geo-validated direct assignment; maps appointed → manual, elected → exception, volunteered → residence); suspend / restore / terminate handlers | appointment and election folded into "reason" | `AssignMemberToCommitteeHandler.php:50-56, 88-102, 154-162` | **no production caller**; docblock claims it "wraps both" stores, code writes one: CONFLICT |
| Readers | dashboard member list (role hard-coded "member"); Membership eligibility and "my committees" queries; the application re-apply gate | displayed as "committee members" | `EloquentCommitteeMembershipReadModelAdapter.php:19-87`; `ApplyForCommitteeMembershipHandler.php:45` | VERIFIED |
| Authorisation / eligibility use | gates re-application only; `VotingEligibilityPolicy::evaluate(MembershipLineage)` claims "the ONLY place where voting eligibility is determined" but has no production caller | intended basis of voting rights (not wired) | `M/Domain/Voting/VotingEligibilityPolicy.php:12, 16-65` | PARTIALLY VERIFIED; conflicts with ACT §11 (Election owns eligibility) if ever wired |
| What "committee" is | `committees.type` ∈ ward, district, province, central, youth, women, student; level, geography, parent; migration calls it "political party organization structure" | the FK target looks like an **organisational unit** | `M/Infrastructure/Database/Migrations/Tenant/2026_01_16_000002_create_committees_table.php:9-80` | **OPEN QUESTION:** one entity models both the unit (branch) and its executive organ |

**Concepts combined** [EO]:
- person membership (vocabulary: `MembershipStatus`, "membership suspended");
- organisational-unit belonging (residence-based eligibility, unit types; strongest signal);
- organ participation (names and UI label only);
- appointment / election (lossy mapping to "reason").

It has **no** position, role, jurisdiction record or authority.

### 4.1 M-2 answer [P, for the owner; business part open]

1. **What exists:** an append-only belonging-and-status record with three parallel "member in committee" stores beside it (`committee_assignments`: position and term; `committee_member_projection`: Governance roles), none reconciled.
2. **What it appears to mean:** belonging to an organisational unit (UnitAffiliation candidate), not holding a seat in an organ.
3. **What needs business confirmation** (organisational representative / PO, OQ-DR07-1):
   - **Q1:** is a ward / district / province / wing `committees` row the unit or its organ?
   - **Q2:** is a residence-based accepted application "membership of the branch", distinct from appointment or election to its committee?
   - **Q3:** which store is authoritative for "who sits in committee X"?
   - **Q4:** should unit belonging ever feed voting eligibility? (ACT §11 places eligibility in Election.)

---

## 5. M-3: technical position on Governance ownership [P, for the owner's adoption]

### 5.1 Binding frame

- [D] ACT §11: Governance "owns competence, organs, authority and organisational governance rules" (`:319-321`).
- [D] ACT §10: the Membership aggregate must not absorb committee participation (`:300-305`).
- [D] PO-DR07-01: governance committees are organs; organisational units are distinct.
- [D] PO-ORG-MEM-01 Decision A: competent organs and office holders decide; PublicDigit records facts and evidence.
- [D] RAT §8: Membership stores authority evidence only.

### 5.2 Findings bearing on the position

| # | Finding | Effect on the boundary |
|---|---|---|
| F-1 | 111 of 188 committee-domain files are governance-authority logic inside Membership (§3.2) | **contradicts** ACT §11 |
| F-2 | Governance imports Membership types 50 times (Domain included); Membership imports Governance 0 times | **contradicts**: dependency runs from the owner of organs to Membership |
| F-3 | the Governance committee model is a member→role map on a blank aggregate (W6) | **incomplete representation**: Governance holds the live path but not a model |
| F-4 | three unreconciled member-in-committee stores | **contradicts** "one write model as source of truth" (ADR-0001 CQRS principle) |
| F-5 | the ratified kernel has 0 committee references | **conforms**: Membership's ratified part already respects act §10 |
| F-6 | `committees` models units and organs at once (M-2 Q1) | ownership cannot be assigned per table; it needs O-16 classification first |
| F-7 | no committee write records authority evidence (§3.3) | neither context today supplies the evidence RAT §8 expects |

**Answer to "contradicts or incompletely represents?":** both. Code placement (F-1, F-2, F-4) contradicts the ratified boundary. The Governance model (F-3, F-7) is incomplete. The kernel (F-5) conforms.

### 5.3 Alternatives

| Option | Content | Fit with binding frame | Risks |
|---|---|---|---|
| **A** | Governance owns organs, positions, appointments, tenure and authority; Membership owns membership facts and stores evidence | consistent with ACT §10–§11, PO-DR07-01, PO-ORG-MEM-01 | large migration; Governance model must be built; Mandate placement depends on ARB-8 |
| **C** | Membership keeps committee ownership (ADR-0001 as is) | conflicts with ACT §11 "organs" → Change Request against the ratified architecture | keeps F-1/F-2 coupling; L-3 and the three stores must be fixed anyway |
| **A + DR-07b split** | A for organs; organisational units (and `CommitteeAssociation` if Q1/Q2 confirm the unit reading) classified under DR-07b / O-16 | as A; avoids moving unit belonging into Governance | needs O-16 evidence and the M-2 business answers first |

### 5.4 Recommendation [P]

**A for governance organs, with organisational units and `CommitteeAssociation` deferred to DR-07b / O-16.**

- **Remains in Membership:** membership facts and lifecycle; authority evidence on facts; Membership invariants; acceptance or refusal of facts (G\*).
- **Not to move into Membership:** organs, positions, appointment, tenure, mandate, delegation, competence; the 111 governance-authority files belong outside it.
- **Interfaces needed:**
  - Membership → Governance: the AuthorityCheck port (ratified; content OPEN, P11 / R-08);
  - Governance → Membership: published membership facts, if O-9 makes seats depend on membership.
  - Neither interface is designed here.

**Questions for PO / ARB:**
- ARB-2 and ARB-5 (A vs C);
- ARB-8 (whether Governance is BDR-03's "Appointment (Authority)");
- O-16 and M-2 Q1–Q4 (units vs organs);
- O-9 (whether seats require membership).

---

## 6. M-4: legacy committee model and freeze [P, for the owner's adoption]

### 6.1 Current usage

| Area | Current usage | Depends on | Evidence |
|---|---|---|---|
| Committee CRUD UI | Index, Create, Edit, Dashboard pages; create (W4) and update (W3) | `CommitteeModel`, `UpdateCommitteeDetails` | `routes/committee/committeeRoutes.php:26-55`; `resources/js/Pages/Committee/*` |
| **Committee member management UI** | `CommitteeMemberManager.vue` (mounted in `Pages/Committee/Dashboard.vue:95`) calls `/governance/committees/{id}/members` GET / POST / DELETE (`:377, :455, :485`) with `baseURL: '/api/v1'` (`resources/js/services/api.js:20`) | **Governance path W6 / W7** | VERIFIED |
| Legacy member routes (W1, W2) | only caller `Components/Committee/AssignMemberModal.vue:218`, imported nowhere | — | VERIFIED: no UI caller |
| Membership applications | apply / review (W8) | `committee_associations` | VERIFIED |
| Governance read API | hierarchy and projections, reading the legacy `committees` table | `CommitteeModel` | `routes/api/governance_v1.php:12-14` |
| Election | **no dependency**: election committee is a different concept (`election_officers`; `ElectionConstitution` roles chief / deputy) | — | VERIFIED: no FK or code path |
| New dependencies | **0 commits since 2026-09-01** on committee and Governance paths; committee work since then is deletion only | — | `git log --since=2026-09-01` |

### 6.2 The three freeze actions

| Action | What it means here | Recommendation | Decision authority |
|---|---|---|---|
| (a) Freeze new functionality and new dependencies | no new imports of `M\Domain\Committee` or `CommitteeModel` from other contexts; no new features on the legacy model. Today nothing enforces it; the 50 Governance → Membership imports would need a baseline allow-list | **recommend now**. Cost is low; no new dependency has been added since September | ARB (ARB-6); a fitness test would need PO authorisation |
| (b) Freeze the committee schema | no migrations on `committees`, `committee_assignments`, `committee_associations`, structure, role and decision tables except security and bug fixes | **recommend** | ARB (ARB-6) |
| (c) Stop write paths and migrate data | retire W1–W8 in favour of a Governance model | **not now**: stopping W6 / W7 breaks the only live member UI; requires the Governance model, O-16, M-2 answers, isolated test database (O-4) | ARB (ARB-7) + PO per slice |

### 6.3 Is the legacy model a valid behavioural reference? [P]

Option B makes the legacy write model the "behavioural reference until verified parity". For committee **assignments**, the evidence says it is not a reliable oracle:
- W1 cannot succeed;
- the role limits are dead (I-6);
- dissolution is not persisted (I-3);
- every save corrupts history (I-10);
- nothing reads `committee_assignments`;
- the live UI writes a different store.

**Recommendation for ARB-6:** keep the freeze, but define parity against **decided business rules and characterisation tests of intended behaviour**, not against the legacy model's actual output. Record the known defects explicitly as "not to be reproduced".

### 6.4 Draft freeze wording [P]

> "The legacy committee write model in `app/Contexts/Membership/Domain/Committee`, `Application/Committee*` and its tables is frozen: no new functionality, no new dependencies from other contexts, no schema changes, except security fixes and defect fixes approved per story. It is a behavioural reference only where its behaviour matches decided business rules; recorded defects (L-3, W1, I-3, I-6) are not reference behaviour. The Governance member API remains in service until a replacement is accepted."

---

## 7. M-5: migration and cutover risk register

**Status:** C = confirmed (static) · P = potential · B = depends on business requirements.

| ID | Risk | Cause | Affected | Impact | Mitigation [P] | Dependency | Owner | Status |
|---|---|---|---|---|---|---|---|---|
| R-1 | Any organisation member can add or remove committee members | W6 / W7 have no authorisation; live UI uses them | Governance member API | unauthorised changes to committee composition | authorise per PBDIGIT-266 item 3 | PBDIGIT-266 | PO | C (tracked) |
| R-2 | Any verified user can approve committee-membership applications | W8 authorisation is authenticated + verified only | application review | unauthorised admissions to units / committees | per PBDIGIT-266 item 2 | PBDIGIT-266 | PO | C (tracked) |
| R-3 | Canonical create returns success and persists nothing; authenticated-only | W5 no-op repository | `create-canonical` | phantom ids; misleading clients | disable or fix (separate story) | — | PO | C (static) |
| R-4 | Tenure history overwritten on every save | L-3 (I-10) | `committee_assignments` | loss of appointment history; corrupt evidence | fix with a test that pins historical `left_date` | — | PO (story) | C (static); corrupted row count NOT VERIFIED |
| R-5 | Legacy assign path broken | W1 TypeError | legacy member routes | no assignments written via web; errors hidden | none needed if path retired; record | — | PO / ARB | C (static) |
| R-6 | Three divergent member-in-committee stores | no single source of truth | all three | parity cannot be judged from one table; cutover reconciliation required | O-16 + M-2 answers first | O-16, OQ-DR07-1 | PO + ARB | C; meaning B |
| R-7 | Approval double write likely fails | §4 live writer | application approval | admissions may fail or duplicate | runtime check, then fix | — | PO (story) | C (static); runtime NOT VERIFIED |
| R-8 | Database constraints exist only on PostgreSQL | conditional migrations (I-9) | `committees`, `committee_assignments` | if production is not PostgreSQL, invariants I-1, I-4, I-6, I-8 are domain-only | confirm production engine | Ops | PO / Ops | B / NOT VERIFIED |
| R-9 | Dissolution not persisted | I-3 | `Committee` | dissolved committees accept assignments after reload | fix in replacement model | — | ARB | C (static) |
| R-10 | Role-limit rules unenforced and inconsistent | I-6 | positions | wrong capacity semantics | statutes (O-8, κ) | statutes | PO | C; capacity B |
| R-11 | Inconsistent tenant columns and missing FKs | §3.4 | committee tables | migration needs casts; weak isolation | design in migration | INV-ORG-SCOPE-1 | ARB | C |
| R-12 | Global tables without tenant column | `role_hierarchy`, `constitutional_committees`, `governance_decisions` | isolation | cross-organisation data if used | decide intended scope | OQ-9 (preparation document) | ARB | C; intent B |
| R-13 | No authority evidence or audit on committee writes | §3.3 | all paths | appointments cannot be reconstructed or proven | part of the Governance model; evidence contract OQ-12 | P11 | ARB | C |
| R-14 | Governance aggregate always blank | W6 | Governance | duplicate check never fires; projection overwrites roles | real Governance model | DR-07a | ARB | C |
| R-15 | Projection delete without tenant filter; rebuild reads across tenants | `CommitteeMemberProjectionListener.php:53-58`; `CommitteeGovernanceProjector.php:79` | Governance projections | cross-tenant effects in edge cases | tenant filters | INV-ORG-SCOPE-1 | ARB | P |
| R-16 | Route mounted several times with different middleware; debug routes live | `governance-api.php` via `RouteServiceProvider.php:51-53` and `organisations.php:414-417`; `api_v1.php` | routing | inconsistent tenancy; information exposure | consolidate (separate story) | — | PO | C (routes) |
| R-17 | Migration `down()` not reversible | `make_committees_type_nullable`, projection id casts; cascade from `committees` | rollback | rollback fails with real data | rollback plan per migration | — | ARB | C (static) |
| R-18 | Characterisation tests check less than their names | `CommitteeManagementHttpTest.php:243-257`; Governance API test hits unmounted URIs and has a placeholder POST test | tests | false confidence during cutover | rewrite as characterisation tests | O-4 | PO (story) | C (static) |
| R-19 | Governance → Membership compile-time coupling; Membership ↔ Committee ↔ Geography import cycle | F-2; earlier import graph | code structure | moving code breaks imports | baseline allow-list (freeze (a)) | ARB-6 | ARB | C |
| R-20 | Live users and data volume unknown | no logs or database access | cutover | blast radius unknown | read-only production query (authorised) | Ops | PO / Ops | NOT VERIFIED |

No migration plan is proposed. Its prerequisites are DR-07a, O-16, the M-2 answers, the statutes and the isolated test database.

---

## 8. Evidence gaps and open questions

| # | Gap or question | Who can close it |
|---|---|---|
| E-1 | Production database engine (decides whether constraints I-1, I-4, I-6, I-8 exist) | Ops |
| E-2 | Rows in `committee_assignments`, `committee_associations`, `committee_member_projection`; rows with overwritten `left_date` | Ops (read-only query, authorised) |
| E-3 | Runtime behaviour of the approval double write (R-7) and of W1 | a test run on the isolated database |
| E-4 | Whether the providers listed in `config/app.php` all load under the current bootstrap | engineering (runtime check) |
| E-5 | Actual production use of the Governance member API and the legacy routes | Ops (logs) |
| Q-1…Q-4 | M-2 business meaning (§4.1) | organisational representative / PO |
| Q-5 | Are `role_hierarchy`, `constitutional_committees`, `governance_decisions` meant to be platform-global? | PO / ARB |

---

## 9. PO decisions required

1. Enter PO-ORG-MEM-01 (Decisions A and B) in a decision record. It is currently stated in session only.
2. Prioritise PBDIGIT-266 items 2 and 3 in light of the new evidence: the live UI depends on the unauthorised Governance member API (R-1, R-2). The PO decides whether this is security triage now.
3. Decide whether the canonical-create endpoint (R-3), the L-3 history overwrite (R-4) and the approval double write (R-7) become backlog stories. Searches found no existing story for R-3's no-op, the dead role limits or the double write; a fuller check of existing stories is part of creating them.
4. Name the organisational representative for M-2 Q1–Q4, and authorise the read-only production checks E-1, E-2 and E-5.

## 10. ARB decisions or validation required

1. ARB-1…4 and ARB-8 (unchanged; readings of ratified and frozen texts).
2. ARB-5 (ADR-0001 location clause), informed by §5. Recommendation A for organs, units deferred.
3. ARB-6: the freeze rule. Freeze (a) and (b) recommended. Parity is to be defined against decided rules, with recorded defects excluded from the behavioural reference (§6.3–§6.4).
4. ARB-7: conditions before cutover. Update with E-1…E-5, O-16, the M-2 answers and R-6. G-7's production relevance is now evidenced through the UI.
5. The DR-07 §4 inventory needs amendment per §3.6 (by the paper's owner) before ARB-5/6 rely on it.
6. Open evidence-contract questions OQ-12 and OQ-13 (preparation document §10.1) remain with the ARB.

## 11. Independent review requirements

**This report is not the independent review.** Its author also wrote the briefs for the evidence sweeps it used.

**Findings to verify independently (highest weight first):**

| Finding | Reproduce with |
|---|---|
| W6 / W7 used by the live UI; no authorisation | `grep -n "governance/committees" resources/js/Components/CommitteeMemberManager.vue`; `grep -n baseURL resources/js/services/api.js`; `grep -nE "authorize\|Gate::\|->can\(" app/Http/Controllers/Api/Governance/CommitteeMemberController.php` |
| L-3 (I-10) | `sed -n 44,56p` and `sed -n 140,150p app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php` |
| W1 TypeError | `sed -n 28,56p app/Http/Controllers/Committee/CommitteeMemberController.php`; `grep -n "MemberId\|NominationType" app/Contexts/Membership/Application/Committee/DTOs/AssignMemberDto.php` |
| I-6 dead role limits | `sed -n 34,46p app/Contexts/Membership/Domain/Committee/Strategies/CentralCommitteeStructure.php`; `grep -n preg_match app/Contexts/Membership/Domain/ValueObjects/RolePath.php`; `sed -n 765,800p app/Contexts/Membership/Domain/Committee/Committee.php` |
| Approval double write | `sed -n 44,62p` of `ReviewMembershipApplicationHandler.php`; `sed -n 10,16p database/migrations/2026_05_14_000002_add_lineage_id_to_committee_associations.php`; lifecycle policy `:44-57` |
| W5 no-op | `sed -n 14,18p app/Contexts/Membership/Infrastructure/Repositories/CommitteeRepository.php` |
| PostgreSQL-only constraints | `sed -n 100,110p` of the two Tenant committee migrations; `config/database.php:18` |
| 111 governance-authority files | `find app/Contexts/Membership/Domain/Committee/{Constitutional,Geo,Capability} -type f \| wc -l` |
| No new dependency since September | `git log --since=2026-09-01 --oneline -- app/Contexts/Membership/Domain/Committee app/Contexts/Governance app/Contexts/Committee` |

**Tests to check independently:**
- `tests/Feature/Contexts/Membership/CommitteeMembershipApplicationTest.php:144-166` (does it run against the real repositories?);
- `tests/Feature/Membership/CommitteeManagementHttpTest.php:243-257`;
- `tests/Feature/Governance/Api/CommitteeMemberApiTest.php` (mounted URIs?).

**Conclusions that rest on judgement, not direct evidence:**
- the M-2 "unit belonging" reading;
- M-3 recommendation A with the DR-07b split;
- the M-4 judgement that the legacy model is not a reliable oracle;
- severity ordering in §7.

---

## 12. Recommended next steps

1. **Technical owner (Nab):** review this draft and adopt, amend or reject each answer (M-1…M-5).
2. **Independent reviewer:** verify §11 findings before the ARB relies on them.
3. **PO:** decide §9 items 1–4, starting with R-1 / R-2 security triage under PBDIGIT-266.
4. **Organisational representative:** answer M-2 Q1–Q4.
5. **ARB:** ARB-5 / ARB-6 / ARB-7 per §10, after items 1 and 2.

Implementation does not start from this report. The open question it was meant to answer is now evidenced: the existing implementation is **structurally inconsistent** with the ratified boundary in placement and stores, **incomplete** in the Governance model, and **defective** in several live paths. These three need separating before any migration design.

---

## Traceability

- Decisions: PO-ORG-MEM-01 A and B (session, 2026-11-03); PO-DR07-01 (DR07 §15); ACT §10–§11; RAT §8.
- DR-07 questions: DR07 §16 (`:301-306`); ARB items DR07 §17 and ARB pack §3.
- Related tracking: PBDIGIT-266 (items 2 and 3); register G-7, G-9, L-3.
- Preparation documents: `2026-11-03-organisation-membership-constitutional-authority-discovery-preparation.md` (§4.4, §10.1); `2026-11-03-publicdigit-po-arb-decision-action-package.md` (§4.3 template).
- No code, schema, test, ADR, ratified document, context map, BDR, Platform Rules Index or DR-07 paper was changed. Nothing was committed.
