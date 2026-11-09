# PublicDigit as-is baseline architecture: assessment report

| | |
|---|---|
| **Status** | **REPORT.** It records evidence. It decides, accepts, ratifies and authorises nothing. No decision status in any other record is changed by this document. |
| **Authority** | generated (Claude, senior enterprise architect / DDD architecture analyst role). Not authoritative without Principal review. |
| **Date** | 2026-11-03 |
| **Snapshot** | branch `election-flow-updated`; HEAD `4d4c005b0` at start, `a3454e17c` at end. Other sessions committed KnowledgeOS work and PBDIGIT-329 Membership COR-4 work during the review; none of their edits touched the evidence used here, except that COR-4 code moved from uncommitted to committed. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); sits next to `2026-11-03-publicdigit-architecture-assessment.md` in `architecture/reviews/`. |
| **Commission** | "Establish the PublicDigit Baseline Architecture" (read-only discovery; no strategic design). |
| **Method** | four parallel evidence sweeps (application/technical, data, integration, Membership), each with confidence levels, plus direct verification of every security-relevant and status-critical claim. |
| **Not used as a source** | `docs/publicdigit/architecture/platform-rules-index.md` (PROPOSED; open review findings M-01…M-10). |

**Status labels used:** IMPLEMENTED · RATIFIED · APPROVED · ADOPTED · PROPOSED · OPEN · HISTORICAL · UNKNOWN. Source wording is quoted where it differs (e.g. "DECIDED by the PO", "FROZEN", "ACCEPTED"). **Confidence:** HIGH / MEDIUM / LOW. **ASSUMPTION** marks an inference.

---

## Part 1: Executive baseline

**What PublicDigit is today.** The product backlog describes it as "an Organisation Governance Platform whose first major capability is running elections" (`docs/publicdigit/backlog/README.md:3`). It is one Laravel 12 + Inertia 2 + Vue 3 application on one database. Organisations are separated by an `organisation_id` column on each row (IMPLEMENTED, HIGH).

**What is implemented.** The code supports the whole intended journey: organisations, members, committees, elections, candidates, a five-step voting flow, results, demo mode, finance, geography and newsletters. But working is not the same as verified:
- **PBDIGIT-00** (CLOSED 2026-08-06): "the journey is verified. **Result: it does not complete**"; "There is still NO automated end-to-end coverage of a complete election".
- All six epics read "IMPLEMENTED — NOT VERIFIED".
- Whether later evidence changed this was not established (**UNKNOWN**).

**What is formally decided.**
- **RATIFIED:** the Membership architecture and the context-ownership statements in act §11, 2026-10-08:
  - Identity owns person identity;
  - Membership owns membership truth;
  - Governance owns organs and governance rules;
  - Election owns ElectionMembership, entitlement and eligibility;
  - Finance owns settlement;
  - Audit consumes published information.
- **ACCEPTED:** the EPIC-002 evidence and integrity contexts (ARB 2026-07-25).
- **FROZEN:** the programme architecture (Architecture Release 1.0, the Boundary Decision Register, PushB Blueprint, Canonical Event Catalog, Implementation Constitution).
- **CONFIRMED / DECIDED by the PO:** the platform business rules (BR-1…14, K′, PO-DR07-01).
- **ADOPTED rows** of the Election Manifesto, a catalogue that is itself PROPOSED (EM-OPEN-017).

**What remains uncertain.**
- Ownership of Organisation, Identity details, affiliation storage, committees, authorisation and vocabulary: all **PROPOSED or OPEN**.
- Which "baseline" document governs: four compete, and no record ranks them.
- Production facts: database engine, which migrations are deployed, hosting.

**Where design and implementation diverge most:**
1. The **election core** runs in legacy code (`app/Models/Election.php`, `app/Domain/Election`, the `Elections` module, a 4,388-line `VoteController`). The governed greenfield `Election` context is wired up but sits on no HTTP path.
2. **Production uses the legacy Membership representation.** The ratified Membership kernel is built and tested but bound to nothing.
3. **Vote anonymity:** the schema is anonymous, but the vote-casting code creates voter↔vote links in stored step data, in logs and in the audit trail. Two of these are recorded known gaps; the third, the audit receipt hash, is not recorded in any committed record found (G-1).

---

## Part 2: Architecture landscape (seven areas)

### A. Business architecture

| Statement | Status | Evidence | Conf. |
|---|---|---|---|
| Customer journey: organisation → configuration → committees → member import and approval → election → candidates → verification → voting → count → results → audit → archive | product backlog (PO-owned work items) | `docs/publicdigit/backlog/README.md:9-17` | HIGH |
| Six epics (Organisation, Membership, Election, Candidate, Voting, Results & Audit) | "IMPLEMENTED — NOT VERIFIED", gated by PBDIGIT-00 | `backlog/README.md:21-27` | HIGH; the epic statuses are likely stale, since PBDIGIT-00 is CLOSED |
| End-to-end journey does not complete; no automated E2E of a full election | measured 2026-08-06 | `backlog/PBDIGIT-00-verify-the-journey-end-to-end.md:7-8` | HIGH (as of August); current state UNKNOWN |
| Capabilities found in code: organisation management; legacy membership (fees, renewals, applications, import); committees; election state machine, posts, candidacies, codes, voter slugs, votes and results; demo mode; voter and IP verification; newsletters; finance (incomes, manual payment gateway); geography | IMPLEMENTED (not verified) | data sweep §1; application sweep §2–§3 | HIGH |
| Contestation and adjudication (challenges, determinations) | IMPLEMENTED as greenfield code, **not routed** (no HTTP entry) | 0 references outside the context in `app/` or `routes/` | HIGH |
| Business rules: Election Manifesto, 99 EM rules | rows ADOPTED; catalogue PROPOSED (EM-OPEN-017) | `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md:4` | HIGH |
| Person / account / affiliation rules BR-1…14 | Confirmed / Decided by the PO; BR-9 Open | `docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md:23-40` | HIGH |
| Committee statutes, incompatibility and tenure rules | OPEN (O-8, O-9, O-10); statutes not yet requested | register `docs/publicdigit/reviews/2026-11-02-organisation-governance-strategic-design-status-assessment.md` | HIGH |

### B. Domain architecture

See Part 3.

### C. Application architecture

| Statement | Status | Evidence | Conf. |
|---|---|---|---|
| One application: PHP ^8.3, Laravel **v12.70**, Inertia 2, Vue 3, Vite. (`CLAUDE.md` still says "Laravel 11"; the upgrade to 12 was ACCEPTED in `docs/publicdigit/adr/ADR_20261015_2346_Laravel_12_And_Excel_4_Upgrade.md`) | IMPLEMENTED | `composer.lock:1489` | HIGH |
| 1,769 app files; 1,044 in `app/Contexts`; plus legacy `app/Models` (72), `app/Services` (52), `app/Domain` (137), `app/Application` (71), `app/Http/Controllers` (125) | IMPLEMENTED | file counts | HIGH |
| Routed context code: only Geography, the Governance API and one Membership controller. Adjudication, Contestation and Election (singular) have providers (`config/app.php:211-213`) but no route or controller users | IMPLEMENTED | route list; grep of usages | HIGH |
| **Two election modules:** `Contexts/Election` (greenfield; deptrac/PHPStan-guarded; not on HTTP paths) and `Contexts/Elections` (no provider; bindings in `AppServiceProvider.php:129-172`; used by `VoteController`, `CodeController` and others) | IMPLEMENTED | `deptrac.yaml:61-65` | HIGH |
| Voting authorisation has a single entry point: `VotingAuthorization::forEntry` / `forCast`, guarded by `tests/Architecture/VotingEntitlementSinglePointTest.php` | IMPLEMENTED | `CodeController.php:727`, `VoteController.php:1661` | HIGH |
| Large legacy controllers: `VoteController` 4,388 lines, `DemoVoteController` 4,956 | IMPLEMENTED | line counts | HIGH |
| 475 routes (from a cached route file dated 2026-10-19, possibly stale); debug and test closures are live (e.g. `/elections/{slug}/debug-tenant` at `routes/election/electionRoutes.php:719`; `/test-auth` at `routes/api_v1.php:19`) | IMPLEMENTED | `php artisan route:list` | MEDIUM (cache) |
| Possible double registration of `web.php` / `api.php` (framework route loading plus `RouteServiceProvider::boot`) | ASSUMPTION | `bootstrap/app.php:10-23`; `app/Providers/RouteServiceProvider.php:37-57` | MEDIUM |
| Patterns: hexagonal layering with ports in the greenfield contexts (enforced by architecture tests and deptrac for 4 directories); classic Laravel MVC plus Eloquent elsewhere | IMPLEMENTED | `tests/Architecture/*`; `deptrac.yaml:43-47` | HIGH |

### D. Data architecture

| Statement | Status | Evidence | Conf. |
|---|---|---|---|
| One database; row-level tenancy via `organisation_id` (NOT NULL with FK on most business tables); no schema-per-tenant; no hostname tenancy | IMPLEMENTED | `config/database.php`; migrations | HIGH |
| The engine in production is not established: config default is `mysql` (`config/database.php:18`, `.env.example:15`), while tests, CI and the Membership ledger are PostgreSQL-only | **UNKNOWN** | `phpunit.xml:63-90`; CI `postgres:15` | MEDIUM |
| D-1 tenancy direction (option C) | "a PO direction, not a confirmed target architecture" | `docs/publicdigit/reviews/2026-11-02-organisation-xd15-tenancy-and-data-ownership-decision-proposal.md` §8a | HIGH |
| The demo convention "organisation_id NULL" (project `CLAUDE.md`) is not what the code does: most tenant columns are NOT NULL, and `BelongsToTenant` falls back to an organisation with slug `platform`, while the seeded slug is `publicdigit` | conflict | `app/Traits/BelongsToTenant.php:46-62`; `database/migrations/2026_03_05_000001_*:49`; `database/seeders/OrganisationSeeder.php:29` | HIGH (code); MEDIUM (runtime effect) |
| Three affiliation stores: `user_organisation_roles` (unique(user_id, organisation_id)), `organisation_users` (unique(organisation_id, user_id); role enum owner/admin/staff/member), `organisation_participants` (staff / guest / election_committee; no unique) | IMPLEMENTED; target storage OPEN (BR-9) | `database/migrations/2026_03_05_000003_*:12-30`; `2026_03_07_145528_*:14-33`; `2026_04_05_000003_*:11-30` | HIGH |
| `votes` / `results` / `demo_votes` have **no user_id**; `demo_votes` keeps `voter_ip` | IMPLEMENTED | `database/migrations/2026_03_05_000011_*`, `_000012_*:17`, `_000013_*:156`; `tests/Feature/VoteAnonymityTest.php` | HIGH |
| The Membership ledger (`org_memberships`, `org_membership_facts`, `org_membership_commands`) is append-only **only in code**: no triggers, REVOKE or RLS anywhere in migrations. Its repository is used only by tests | IMPLEMENTED (not wired) | `database/migrations/2026_10_09_100000_*`; `PostgresMemberRepository.php:155-200` | HIGH |
| "Has voted" is stored in up to 5 places (`codes`, `voter_slugs`, `election_memberships`, `voters`, `codes.vote_submitted`) | IMPLEMENTED | `VoteController.php:1599, 1825-1831` | HIGH |
| Cross-context FKs: `voters.member_id → members ON DELETE CASCADE` (V3′); `members.organisation_user_id → organisation_users`; committees / users / members → `geo_administrative_units` | IMPLEMENTED | `2026_03_07_145529_create_voters_table.php:30`; data sweep §7 | HIGH |
| Code uses `tenant` / `landlord` DB connections that are not defined in config; Geography tenant migrations are probably not loaded; a table literally named `false` is created | IMPLEMENTED (defects) | `GeographyMirrorService.php:130-314`; `AppServiceProvider.php:413`; `2026_05_19_215036_*:14` | HIGH / MEDIUM |
| Transaction hot spots: vote cast uses a manual transaction with a conditional single-use `UPDATE codes … WHERE has_voted=false` plus an advisory lock for the IP limit; the Membership ledger uses compare-and-set on `version` and refuses nesting | IMPLEMENTED | `VoteController.php:1480-1941`; `IpVoteLimit.php:131`; `PostgresMemberRepository.php:161-200` | HIGH |
| Audit storage: DB tables (`election_audit_logs`, `election_state_transitions`, `election_security_events`) plus JSONL / per-voter file logs (`app/Services/ElectionAuditService.php:60-166`) | IMPLEMENTED | as cited | HIGH |

### E. Integration architecture

| Statement | Status | Evidence | Conf. |
|---|---|---|---|
| Transactional outbox → relay → inbox, scheduled every minute; 4 inbox consumers, all in the Adjudication–Contestation–Election loop | IMPLEMENTED | `app/Contexts/Shared/Infrastructure/Outbox/OutboxEventProcessor.php:24-62`; `routes/console.php:55,58` | HIGH |
| Elsewhere: synchronous in-process Laravel listeners (none `ShouldQueue`; queue default `sync`); no message broker | IMPLEMENTED | `app/Providers/EventServiceProvider.php:35-92`; `config/queue.php:16` | HIGH |
| `CreateIncomeForMembershipFee` registered twice for `MembershipFeePaid`; an `'outbox.event'` listener that nothing dispatches | IMPLEMENTED (defects) | `EventServiceProvider.php:50-54, 59-61, 84` | HIGH (registration); MEDIUM (double-income effect) |
| Synchronous cross-context reads: `Elections` reads `members`, `membership_types`, `organisation_users` (V2); Governance imports about 50 Membership domain types plus Membership infrastructure models | IMPLEMENTED | `app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php:91,108-121`; Governance imports | HIGH |
| The legacy election flow and voting emit no domain events; auditing is done by direct `ElectionAuditService::log` calls from controllers | IMPLEMENTED | `VoteController:641,1951`; `CodeController:379,624` and others | HIGH |
| APIs: about 56 API routes; only 6 use `auth:sanctum`; `api/v1` uses the session guard; no OpenAPI; `POST /api/tokens/create` has no auth middleware (likely errors rather than issuing a token) | IMPLEMENTED | `routes/api.php:32-36`; `bootstrap/app.php:19-22` | HIGH (config); ASSUMPTION (effect) |

**The three constraints named in the commission, as their sources state them** (reported, not reinterpreted):

| Constraint as stated | Authoritative source and status | Implementation | Conf. |
|---|---|---|---|
| **VO-1** "voter identity must not appear in Vote events" | `docs/architecture/discovery/Round29_Invariant_Catalog.md:251-266` (VO-1, "A Vote Must Not Be Linkable to the Voter Who Cast It"; Round 29 "APPROVED AND CLOSED"); FROZEN derivatives: Blueprint CI-5 (`docs/implementation/PushB_Architecture_Blueprint.md:39`), ADR-T11 (`docs/adr/ADR-T-LOG-Tactical-Implementation.md:18`), `docs/implementation/Canonical_Event_Catalog_v1.0.md:34` | No Vote event class exists, so the event wording is met trivially. Code linkage outside events: (1) the vote id stored in `voter_slug_steps.step_data`; (2) the vote id logged with slug and IP. Both are recorded known gaps (`tests/Feature/Characterization/Phase1/BallotAnonymityCharacterizationTest.php:145-176`). (3) **Audit receipt hash:** `VoteController.php:1950-1965` logs `sha256(vote_id . user_id . election_id)` with the user's identity (verified). The only coverage found is an **untracked** test, `tests/Isolated/Pbdigit274/Database/VoteAnonymityLinkageTest.php:149`. The greenfield anonymity test (`tests/Architecture/GreenfieldCoreArchitectureTest.php:33-84`) does not scan `VoteController` | HIGH |
| **BR-5** "contexts obtain authoritative facts at evaluation time (pull)" | `docs/architecture/design/Round35C_Event_Flow_Analysis.md:480` (Round 35C "APPROVED WITH 2 OBSERVATIONS"). Self-scoped: "describes the currently discovered model"; future contexts "may legitimately require derived state". **The ID collides:** at least 4 other documents define a different BR-5, including the CONFIRMED platform rule BR-5 (`business_rules/20261101_1739_…:27`) | Membership→Finance and Governance projection listeners maintain derived state, but those contexts were outside Round 35C's scope | HIGH |
| **"Audit is the sole event consumer"** | Originates in the same Round 35C BR-5 sentence, scoped as above. The later FROZEN Blueprint §3/§10 (`PushB…:111, 251`) and the Event Catalog (`:34`) say "Audit consumes **all**" and list other consumers. BDR-09 (`docs/architecture/design/Round49-06_Boundary_Decision_Register.md:22`): Audit is "Infrastructure / Platform Capability", not a bounded context | In code Audit consumes **no** events; there is no Audit inbox handler | HIGH |

Integration rules vs code (reported only): Blueprint §11 "NO synchronous cross-context call anywhere in the loop" is honoured by the loop code, apart from the ACL read of the legacy `elections` table (`LegacyElectionExistenceAdapter.php:35`). The C4 legend (`ContextMap.puml:34-35`) applies the rule platform-wide, and code outside the loop contradicts it. This is already recorded as context-map C-2 / Q-new-2 (O-18, ARB).

### F. Governance architecture

| Statement | Status | Evidence | Conf. |
|---|---|---|---|
| Decision authorities in the records: PO, Human ARB, Principal Reviewer, Membership owner; engineering supplies evidence only | practised; their rule text (ES-001…006, STANDARDS_INDEX) is **PROPOSED**; Implementation Process v1.0 FROZEN; EP-rules in the v1.1 DRAFT | `engineering/governance/ES-00x-*.md:3`; `docs/implementation/Implementation_Process_v1.0.md` | HIGH |
| Methodology freeze (no documentation-architecture evolution unless a deficiency is shown) | binding project instruction | `.claude/CLAUDE.md` | HIGH |
| Constitutional authority in software: an election-constitution engine (GEO-3.x), `ConstitutionalBasis`, constitutional committees, constitutional policy freeze (ADR-B / ADR-C APPROVED CONDITIONALLY; ADR-A PROPOSED r2) | IMPLEMENTED / mixed | `docs/CONSTITUTIONAL_GOVERNANCE_ARCHITECTURE.md`; `docs/publicdigit/adr/ADR_20261014_1138_*` | MEDIUM |
| Membership authority: the `AuthorityCheckPort` exists; its only implementation, `InterimRoleAuthority` (Admit for owner/admin; End denied pending GOV-END), is **not bound**; Governance implements no port | IMPLEMENTED (not wired); GOV-END OPEN | `app/Contexts/Membership/Application/Organisational/Ports/AuthorityCheckPort.php`; `Infrastructure/Organisational/InterimRoleAuthority.php:17-41` | HIGH |
| Election eligibility: act §11 says Election owns it. Code has the `VotingAuthorization` single point, plus `Elections` adapters reading legacy Membership tables (V2), plus a Membership `VotingEligibilityPolicy` that calls itself "the ONLY place where voting eligibility is determined" (`app/Contexts/Membership/Domain/Voting/VotingEligibilityPolicy.php:12`) and is referenced by Membership committee code (`EligibleCommitteeQueryService`, `MembershipLineage`). AD-2 (ARB) is still recorded as open | RATIFIED ownership / conflicting implementation | `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratification-act.md:323-332`; `docs/publicdigit/reviews/2026-08-15-session3-implementation-record-and-outstanding-work.md:123` | HIGH |
| Committee ownership: PO-DR07-01 says committees are organs ("PO business decision … recorded 2026-11-02"); DR-07a/b OPEN; ADR-0001 ("Accepted", March 2025) puts the committee write model in Membership | conflict recorded (O-6) | `docs/publicdigit/reviews/2026-11-02-governance-dr07-committee-ownership-decision-paper.md` §15; `docs/adr/ADR-0001-committee-read-write-separation.md:20-21` | HIGH |

### G. Technical architecture

| Statement | Status | Evidence | Conf. |
|---|---|---|---|
| Authentication: custom session login plus email verification (`MustVerifyEmail`); Sanctum (stateful); `DashboardResolver` priority chain after login | IMPLEMENTED | `app/Http/Controllers/Auth/LoginController.php:71-94`; `app/Services/DashboardResolver.php:48-735` | HIGH |
| Google login probably broken at runtime: imports a missing `App\Actions\Fortify\CreateNewUser`, wrongly namespaced `Exception` catch with `dd()`, no `services.google` config | IMPLEMENTED (defect) | `GoogleLoginController.php:8,60-70`; `config/services.php:17-27` | MEDIUM-HIGH |
| Authorisation: Spatie roles without teams, not used on any route; organisation roles in app tables; 3 mapped policies; `can:admin` used with no gate defined | IMPLEMENTED | `bootstrap/app.php:79-81`; `AuthServiceProvider`; `routes/election/electionRoutes.php:137` | HIGH |
| Tenancy middleware trusts the `X-Tenant-Id` header without a membership check (verified). Already recorded: V-7 / PBDIGIT-266 item 5, refined as "contained on the election-management group by per-election officer policies", with a residual on the debug route | IMPLEMENTED; recorded | `app/Http/Middleware/TenantContext.php:49-56`; `docs/publicdigit/reviews/2026-11-02-inv-org-scope-1-stage1-static-investigation.md:36,150` | HIGH |
| 397 `withoutGlobalScope(s)` calls in 110 files (the record counts 379; classification OQ-OS-3 is not authorised) | IMPLEMENTED | grep of `app/` | HIGH |
| Static `TenantContext` holder may leak state between jobs in long-running workers | ASSUMPTION | `app/Services/TenantContext.php` | MEDIUM |
| CI: 6 workflows. Two use PHP 8.2 against a ^8.3 lock (`membership-architecture.yml:22`, `regression-detector.yml:21`). The merge-gate deptrac path is probably wrong (`composer.json:76` → `vendor/qossmic/deptrac-shim`, not installed). No frontend CI. Husky pre-commit never blocks | IMPLEMENTED | `.github/workflows/*`; `.husky/*` | HIGH (config); ASSUMPTION (failure effects) |
| Deployment: no Docker, Procfile, Envoy or CD workflow. The only evidence is a developer guide describing manual SSH plus `git pull` on a single host | **UNKNOWN** beyond that document | `developer_guide/DEPLOY_TO_PRODUCTION.md` | LOW |
| Operations: no monitoring/APM, no backup tooling; file-based log channels with retention (`config/logging.php:108-150`) | IMPLEMENTED (absence) | `composer.lock`; logging config | HIGH |
| Secrets: `.env.back_20260419_0138`, `.env.backup-laravel12`, two `.sql` dumps and `database.sqlite` are **tracked in git** (verified by file name; contents not opened). A hardcoded test DB password (`config/database.php:146`) and APP_KEY (`phpunit.xml:91`). Already recorded: **PBDIGIT-270** (OPEN · not authorised) and **PBDIGIT-37**. Secrets written to logs: **PBDIGIT-317** (open) | recorded, open | `git ls-files`; `docs/publicdigit/backlog/` | HIGH |
| Dev-database wipe guards (test DB allow-list, boot guards, fitness tests) | IMPLEMENTED | `tests/Support/TestDatabaseSafety.php:23-56`; `tests/CreatesApplication.php:33-41` | HIGH |
| Test suites: Unit 622, Feature 435, Architecture 42, GreenfieldCore 78, EngineeringKnowledge 36 files; isolated Membership PostgreSQL suite (`phpunit.membership-pg.xml`) and PBDIGIT-274 isolated suite | IMPLEMENTED | `phpunit*.xml` | HIGH |

---

## Part 3: Domain landscape

| Area | Boundary status | Responsibilities as recorded | Code home(s) | Open |
|---|---|---|---|---|
| **Membership** | **RATIFIED** bounded context, FROZEN FOR IMPLEMENTATION | membership truth, periods, facts, lifecycle; no entitlement | `app/Contexts/Membership` (kernel 60 files + about 495 legacy files) | committee code inside it (N-6, DR-07a) |
| **Identity** | ownership of person identity / equivalence **RATIFIED** (act §11) | Person; Account, PersonRef, provenance **PROPOSED** (XD-3a/b/c, XD-PROV) | no context directory; `users` table plus auth | K4, OQ-3a-3, O-13 |
| **Election** | ownership **RATIFIED** (ElectionMembership, VotingEntitlement, eligibility, exercisability); "Election lifecycle" FROZEN as a supporting subdomain (BDR-08) | as stated | **three homes:** `Contexts/Election` (greenfield, unrouted), `Contexts/Elections` (live), legacy `app/Domain` + `app/Models` | placement `docs/publicdigit/adr/ADR_20260813_1722_*` **PROPOSED**; AD-2 |
| **Governance** | ownership **RATIFIED** (competence, organs, authority, governance rules) | candidate vocabulary PROPOSED (register P-7) | `Contexts/Governance` + committee code in Membership | DR-07a/b, ARB-8 |
| **Appointment (Authority)** | "Confirmed BC — Operational" in the IMMUTABLE BDR-03 | owns Mandate truth | not established | ARB-8 |
| **Finance** | ownership **RATIFIED** (settlement, accounting) | FeePaid direction is recorder's note N-5 (a reading, not a decision) | `Contexts/Finance` (1 file) + legacy | — |
| **Audit** | act §11: a consumer "bounded context"; BDR-09: "Infrastructure / Platform Capability" | consumes information | `ElectionAuditService` (imperative calls) | classification conflict (context map C-6) |
| **Authorization** | BDR-07 FROZEN: supporting subdomain / domain service, "owns no truth" | — | `VotingAuthorization`, policies | XD-4, XD-7 |
| **Evidence & integrity** (Collection & Aggregation, Record-Fixing, Custodial, Self-Verifying, Adjudication) | **ACCEPTED** (EPIC-002, ARB 2026-07-25) | per `docs/implementation/EPIC-002_Canonical_Context_Map.md` | `Contexts/Adjudication`, `Contexts/Contestation` (unrouted) | naming reconciliation deferred |
| **Organisation** | **candidate** (PROPOSED) | registry, affiliation, units | legacy models; three affiliation stores | BR-9, INV-AFF-1, O-16 |
| **Geography** | **candidate** (PROPOSED) | geo units | `Contexts/Geography` | XD-5, O-11 |
| **Communication** | **candidate**; product choice open | newsletters, forum | legacy | O-15 |
| **Platform / tenancy** (Working Organisation) | rules **APPROVED** (PBDIGIT-30); no context owner | routing, scoping | `DashboardResolver`, `TenantContext` | owner UNRESOLVED |

Code directories are not evidence of bounded contexts. `Committee`, `Trust`, `Shared` and `Elections` have no decision record establishing them as contexts. The context map that consolidates these relationships (`docs/publicdigit/architecture/context-map.md`) is **PROPOSED**.

---

## Part 4: Membership position (baseline only)

- **Authoritative documents:**
  - `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md`, the canonical artifact;
  - `…-ratification-act.md`, with recorder's notes N-1…N-6;
  - the final verdict and register.
  - Status: RATIFIED by the Principal Reviewer, 2026-10-08; FROZEN FOR IMPLEMENTATION; changes only through an Architecture Decision / Change Request.
- **Established responsibilities:**
  - one Membership per (Person, Organisation);
  - append-only facts (Admit, Renew, ChangeType, Suspend/Restore, End, Annul, Void, Correct);
  - pure resolution with two-valued predicates;
  - invariants INV-M, INV-1…14 and INV-COV; no voting entitlement (INV-11);
  - the aggregate must not absorb identity, governance competence, entitlement, accounting or committee participation (act §10).
- **Relationships:**
  - Election receives IsMember / Suspended / CurrentType / tenure, referenced by (MembershipId, asOf); "Membership itself never becomes the VotingEntitlement engine" (act §12).
  - Governance is reached through the AuthorityCheck port, with Membership storing only the evidence.
  - Finance: fees reference periods.
  - Identity: person equivalence.
  - Membership↔Election option A is **DECIDED by the PO** (2026-11-02; `docs/publicdigit/reviews/2026-11-02-membership-election-separation-decision-record.md` §1): separate persistence ownership within one application.
- **Implemented:**
  - Greenfield kernel: Domain 30, Application 18, Infrastructure 12 files, with Admit, End, Annul, Void and Correct.
  - Ledger persistence on PostgreSQL.
  - Extensive architecture, property and PostgreSQL tests.
  - Accepted slices: PBDIGIT-258, 259, 260 (conditionally), 264, 268, 271, 324, 327; PBDIGIT-329 COR-4 committed, its acceptance status line uncommitted at the time of review.
  - **Not implemented:** Renew, Suspend/Restore, ChangeType, Standing, numbering.
  - **The kernel is bound to nothing:** no routes, UI or container bindings. `MembershipServiceProvider` binds only legacy code. Production runs the legacy representation.
- **Discrepancies (all recorded, all still present in code):**
  - V1 (`app/Models/Member.php:184-189`), V1b, V1c, V1d, V2, V3′ (PBDIGIT-318; S1b guard ACCEPTED, S2–S4 gated);
  - X-1…X-9; PBDIGIT-320; G-7; L-3/G-9;
  - ADR-0001 vs act §10/§11 and N-6.
- **Discrepancies not found in the November records** (engineering observations):
  - the Membership `VotingEligibilityPolicy` claiming to be "the ONLY place" for eligibility;
  - Governance depending on Membership's committee value objects and models (about 15 to 50 imports), which runs against the ratified direction.
- **Genuinely open:**
  - GOV-END (who may end a membership); BD-REV-ER (N-4);
  - OQ-ME-1…4; DR-07a / M-1…M-5 and CommitteeAssociation; O-9 tenure;
  - PersonRef (XD-3a); FeePaid implementation (N-5);
  - COR-3 commands; PBDIGIT-266 security tests (condition K-1 of ACC-260);
  - the ledger migration release gate; whether the kernel tables exist in production (D-j / OQ-01).
- **Outdated record:** `docs/publicdigit/reviews/2026-11-02-membership-ddd-status.md` §4–§5 lags the code (V-2 fixed; Correct exists; Annul/Void persisted).

---

## Part 5: Gaps and conflicts

| # | Finding | Evidence | Status | Significance | Who must resolve |
|---|---|---|---|---|---|
| G-1 | **Audit receipt hash links voter and vote** (`sha256(vote_id . user_id . election_id)` logged with the user) | `app/Http/Controllers/VoteController.php:1950-1965` (verified) | engineering observation; only an **untracked** isolated test covers it | breaks the anonymity invariant (VO-1 / CI-5 / ADR-T11 family) | PO (triage); Election owner. A backlog item is recommended; none was created |
| G-2 | Election core outside every governed context; three code homes | `docs/publicdigit/adr/ADR_20260813_1722_*` PROPOSED | PROPOSED / OPEN | the core capability has no architectural owner in code | PO / ARB |
| G-3 | Ratified Membership kernel not wired; legacy in production, with the V1…V3′ violations | Part 4 | RATIFIED design vs IMPLEMENTED legacy | ratified boundary not yet effective | per-slice PO approval (W5–W9; S2–S4) |
| G-4 | Four competing "baselines"; eight competing entry points | `docs/publicdigit/architecture/reviews/2026-11-03-publicdigit-architecture-assessment.md` §1.1 | conflict | no review can say "the baseline" | ARB |
| G-5 | Eligibility has several authorities: `VotingAuthorization`, `Elections` adapters, Membership `VotingEligibilityPolicy`; AD-2 open | Part 2 §F | conflict | entitlement correctness | ARB (AD-2) |
| G-6 | Committee write model in Membership (ADR-0001 Accepted) vs Governance owns organs (RATIFIED) plus PO-DR07-01; second write path in Governance (G-7) | register O-6 | OPEN | wrong-owner writes; `left_date` overwrite (L-3) | Membership owner + ARB |
| G-7 | "Audit is the sole event consumer" and BR-5 as stated vs their scoped Round 35C source, the later FROZEN Blueprint, and code where Audit consumes nothing | Part 2 §E | conflicting statements | constraints quoted beyond their recorded scope | ARB (Q-new-2 / O-18 covers integration scope) |
| G-8 | BR-5 identifier used by at least 5 different rules | Part 2 §E | collision | traceability errors | owners of the records |
| G-9 | Production DB engine and deployed migrations unknown; config default `mysql` vs PostgreSQL-only code | Part 2 §D | UNKNOWN | ledger and constraints may not exist in production | PO / Ops |
| G-10 | Demo "organisation_id NULL" convention vs NOT NULL columns and a `platform` slug that does not exist | Part 2 §D | conflict | demo / tenant isolation behaviour unclear | PO + ARB (OQ-C-4) |
| G-11 | Three affiliation stores; unique constraints block multiple roles (vs DC-R5) | Part 2 §D | BR-9 OPEN | role model cannot express the proposed rules | ARB (OQ-C-5) |
| G-12 | Tenancy: unvalidated `X-Tenant-Id`; 397 scope bypasses | Part 2 §G | recorded (V-7, PBDIGIT-266, OQ-OS-3) | isolation assurance | PO + ARB + Security |
| G-13 | Secrets and dumps tracked in git; secrets in logs | Part 2 §G | PBDIGIT-270, -317, -37 OPEN | data protection | PO / Ops |
| G-14 | Enforcement partial: deptrac covers 4 of 11 context directories with a probably-wrong script path; CI PHP mismatch; no frontend CI | Part 2 §G | IMPLEMENTED | "protected" claims overstate coverage | engineering, on PO authorisation |
| G-15 | No deployment, monitoring or backup architecture recorded | Part 2 §G | UNKNOWN / absent | operational risk | PO / Ops |
| G-16 | Undefined DB connections used in code; Geography tenant migrations probably not loaded; `false` table; double-registered fee listener | Part 2 §D, §E | defects | runtime failures; possible duplicate income rows | engineering (backlog) |
| G-17 | Audit: act §11 calls it a bounded context; BDR-09 a platform capability | context map C-6 | conflict | ownership of audit | ARB |
| G-18 | "Has voted" in 5 stores | Part 2 §D | IMPLEMENTED | consistency of the single-vote guarantee | Election owner |
| G-19 | PBDIGIT-00 epics stale; no current E2E evidence | Part 2 §A | UNKNOWN now | capability status unverified | PO |

---

## Part 6: Baseline views (from evidence only)

**1. System context** (partly established):

```
 Voters, members, organisation admins/owners, election officers, platform admins
                │  browser (Inertia/Vue)        │ API (session; 6 Sanctum routes)
                ▼                               ▼
   ┌──────────────── PublicDigit (one Laravel 12 app) ────────────────┐
   │  web + api routes → controllers → legacy models / contexts       │
   └──────────────────────────────────────────────────────────────────┘
      │ SMTP mail     │ Google OAuth (probably broken)   │ ip-api geolocation
      │ Google Calendar (library missing)  │ no payment provider (manual gateway)
      ▼ one database (PostgreSQL per tests/CI; production engine UNKNOWN) + file logs
```

**Missing:** hosting topology, external identity providers in use, and the mail provider in production.

**2. Capability view.** Organisation Management · Membership · Committees · Election Management · Candidates · Voting · Results & Audit · Contestation / Adjudication · Finance · Geography · Communication.
- Status: all IMPLEMENTED-not-verified, except Contestation / Adjudication (implemented, unrouted).
- Decisions exist for Membership (RATIFIED) and for evidence / integrity (ACCEPTED); the rest are candidates.

**3. Domain landscape.** Part 3.

**4. Application landscape:**

```
 HTTP: VoteController · CodeController · DemoVoteController · Election/* · Membership/* · Committee/*
   │            │                                   │
   ▼            ▼                                   ▼
 Contexts/Elections (live)   app/Models, app/Services, app/Domain|Application/Election (legacy core)
 Contexts/Membership: legacy (live) | kernel (tests only)     Contexts/Governance ◄─imports── Membership VOs
 Contexts/Geography (routed)   Contexts/Election + Adjudication + Contestation (outbox/inbox loop; unrouted)
 Contexts/Shared (outbox, inbox, messaging; used widely)
```

**5. Data ownership** (only where the records establish it):
- **Authoritative per ratified decisions:** membership truth → Membership ledger (not live); ElectionMembership / eligibility → Election.
- **Live authoritative stores:** legacy `members`, the three affiliation tables, `election_memberships`.
- **Projections:** `member_directories`, `committee_member_projection`, `committee_governance_projections`.
- **Copies:** "has voted" ×5; committee geo cache; election security-articles snapshot.
- **Cross-context access:** V2 reads, the V3′ FK cascade, Governance → Membership models.

**6. Integration:**
- **Asynchronous:** outbox → relay → inbox (loop events ChallengeRouted, DeterminationIssued, ElectionCorrectionApplied; a FeePaid hydrator).
- **Synchronous:** in-process listeners (`MembershipFeePaid` registered twice; projections); direct cross-context table reads; ports (AuthorityCheck, unbound; ElectionExistence ACL).
- **Contracts:** Canonical Event Catalog (FROZEN) plus the integration-event envelope; no API specification.
- **Constraints:** Part 2 §E.

**7. Deployment and technology.** Stack established (Part 2 §G). **Not established:** servers, environments, CD, queue workers, cron, backups, monitoring. The single-host manual deploy in a developer guide is LOW-confidence evidence.

---

## Part 7: Confidence and limitations

- **Well established (HIGH):**
  - application structure, stack, routes and wiring;
  - data schema and constraints;
  - integration mechanisms;
  - the ratified Membership design and its implementation state;
  - the recorded decisions and their statuses.
- **Partly established:**
  - business capability status (code exists; end-to-end verification dates from August);
  - governance authority in software;
  - domain boundaries outside Membership and EPIC-002 (candidates only).
- **Unknown:**
  - production engine, deployed schema and migrations, hosting, operations;
  - current E2E journey state;
  - whether the tracked `.env` / `.sql` files contain live secrets (deliberately not opened);
  - runtime effects marked ASSUMPTION (Google login, double route registration, `can:admin`, double income listener).
- **Limitations:**
  - the route count comes from a cache dated 2026-10-19;
  - `.env` files were not read;
  - HEAD moved during the review;
  - sweep counts (files, events) are MEDIUM where pattern-based.

---

## Part 8: Recommended next steps (not started)

1. **Principal review of this baseline.** Confirm or correct the classifications in Parts 2–5.
2. **PO triage of the new anonymity finding G-1** as its own backlog item, separate from any architecture work. Also review the status of the existing security items (PBDIGIT-266, -270, -317).
3. **Ops facts:** confirm the production database engine, which migrations are applied (incl. the Membership ledger, D-j / OQ-01) and the deployment topology. Several baseline statements depend on these.
4. **ARB: which baseline artefact governs**, and where the election core belongs (ADR_20260813_1722 PROPOSED).
5. **Before Membership strategic design, settle or explicitly bound:**
   - DR-07a (committee ownership vs ADR-0001);
   - O-14 (vocabulary);
   - BR-9 / INV-AFF-1 (affiliation);
   - XD-3a/b (PersonRef);
   - AD-2 (eligibility ownership);
   - OQ-ME-1…4;
   - GOV-END.

---

## Final decision gate

**BASELINE STATUS: PARTIALLY ESTABLISHED.**
- **Established:** the as-is application, data and integration architecture, and the decision state, all with HIGH-confidence code and record evidence.
- **Not established:**
  - the production and operational architecture (G-9, G-15);
  - current verification of the business journey (G-19);
  - domain boundaries beyond Membership and EPIC-002, which remain candidates;
  - one governing baseline artefact (G-4).

**Answers:**
1. **Do we have enough evidence for the baseline?**
   - Yes, for what the repository implements and what has been decided.
   - No, for what runs in production and whether the journey works end to end.
2. **Sufficiently understood:** application, data, integration, Membership position, the decision and status landscape, the technical stack and CI.
3. **Needs further investigation:**
   - production database, deployment and operations;
   - current E2E state;
   - the election core's architectural placement;
   - the runtime effects of tenancy and auth defects.
4. **Must be clarified before Membership strategic design:**
   - DR-07a / ADR-0001; O-14; BR-9 / INV-AFF-1 / OQ-C-5; XD-3a/b (PersonRef);
   - AD-2 and the Membership `VotingEligibilityPolicy` conflict;
   - OQ-ME-1…4; GOV-END;
   - whether the kernel tables are deployed.
5. **Ratified or frozen decisions to protect:**
   - the Membership architecture (2026-10-08) incl. act §10–§12 and N-1…N-6;
   - act §11 ownership statements;
   - the Membership–Election option A decision;
   - the EPIC-002 accepted contexts;
   - the BDR (IMMUTABLE);
   - the FROZEN Blueprint, Event Catalog and Implementation Constitution, incl. VO-1 / CI-5 / ADR-T11;
   - PBDIGIT-30 B1–B3 and B10;
   - BR-1…14 as confirmed.
6. **Smallest sensible next step:** Principal review of this baseline, plus a PO decision to open a backlog item for G-1 (the audit receipt-hash linkage). Nothing else should start until that review.

Membership DDD strategic design has not started.

---

## Traceability

- Commission: "Engineering Commission: Establish the PublicDigit Baseline Architecture" (2026-11-03).
- Related report (input, not authority): `docs/publicdigit/architecture/reviews/2026-11-03-publicdigit-architecture-assessment.md` (REPORT).
- Evidence was gathered read-only; no source record, code, migration or configuration was changed.
