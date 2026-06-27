# Round 17 — Codebase Map

**Date:** 2026-06-07

**Status:** Phase 0 Repository Reconnaissance — Complete

**Purpose:** Catalog what exists in the repository without interpretation. Foundation for investigation stream adjustment.

---

## 1. Repository Overview

```
Primary working directory: C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu

Repository structure:
├── app/                     — Application code (models, controllers, services, domain)
├── bootstrap/               — Laravel bootstrap configuration
├── config/                  — Application configuration
├── database/
│   ├── migrations/          — Database schema changes
│   ├── seeders/             — Data seeding classes
│   └── factories/           — Model factories
├── routes/                  — Route definitions
├── resources/               — Vue components, views, language files
├── storage/                 — Application logs, caches, uploads
├── tests/                   — Test suites (unit, feature, integration)
├── docs/                    — Project documentation
├── .claude/                 — AI assistant configuration
└── claude/plans/            — Implementation planning documents

Git: Active repository on branch 'enhance-election-only', tracking remote 'main'
Database: Multi-tenant with organisation_id isolation
Platform: Windows 11, Laravel 11+ with Inertia 2.0
```

---

## 2. Module Inventory

### Core Modules (app/ structure)

**Domain Layer (Pure PHP, no Laravel dependencies):**
- `app/Domain/Election/` — Election state machine, constitution, security events
- `app/Domain/Election/Security/` — Trust evaluation, policy sequences, divergence tracking
- `app/Domain/Election/Constitution/` — Constitutional articles, threshold interpreters
- `app/Domain/Election/Replay/` — Evidence envelopes, replay events
- `app/Domain/Committee/` — Constitutional committee structures
- `app/Domain/Voting/` — Voting semantics, governance semantic definitions
- `app/Contexts/Governance/Domain/` — Governance decisions, authority, replay services
- `app/Contexts/Membership/Domain/` — Membership lineage, constitutional membership concepts

**Application Layer (Limited Laravel, orchestration):**
- `app/Application/Election/` — Lifecycle engines, transition guards, capability snapshots
- `app/Application/Election/Security/` — Policy sequences, trust evaluators, overlay aggregators
- `app/Application/Election/Capabilities/` — Capability policies, election constitution registry
- `app/Application/Election/Monitoring/` — Constitutional metrics, drift monitors
- `app/Contexts/Governance/Application/` — Service handlers, approval managers, DTOs
- `app/Contexts/Elections/Application/` — Voter assignment commands and handlers

**Infrastructure Layer (Laravel allowed):**
- `app/Models/` — Eloquent models (Election, Vote, Result, Code, VoterSlug, etc.)
- `app/Http/Controllers/` — HTTP request handlers (Demo, Election, Vote, Governance)
- `app/Http/Middleware/` — Request filtering (authentication, tenant context, validation)
- `app/Exceptions/` — Custom exception classes
- `app/Services/` — Infrastructure services (DemoElectionCreationService, Constitutional services)
- `app/Providers/` — Service provider registrations
- `app/Console/Commands/` — CLI commands (setup, migration, cleanup, reporting)
- `app/Observers/` — Eloquent event observers
- `app/Enums/` — Enumeration types
- `app/Traits/` — Reusable traits (HasAuditFields, BelongsToTenant)

**Context-Named Directories Located:**
- `app/Contexts/Elections/` — Contains election management code (voters, assignments)
- `app/Contexts/Governance/` — Contains governance code (decisions, authority)
- `app/Contexts/Membership/` — Contains membership code (members, committees)
- `app/Contexts/Geography/` — Contains geographic code (regions, countries)

---

## 3. Controller Inventory

**Enumerated Controllers:**

| Controller | Location | Purpose |
|-----------|----------|---------|
| VoteController | `app/Http/Controllers/` | Vote submission and verification |
| DemoVoteController | `app/Http/Controllers/Demo/` | Demo mode voting endpoints |
| ElectionManagementController | `app/Http/Controllers/Election/` | Election administration |
| VoterVerificationController | `app/Http/Controllers/Election/` | Voter verification workflow |
| CandidacyApplicationController | `app/Http/Controllers/` | Candidacy management |
| ArticleController | `app/Http/Controllers/` | Navigation and articles |
| CountryController | `app/Contexts/Geography/Http/Controllers/` | Geography operations |

**Not yet searched:** Specific endpoint routing (see Section 6 for route definitions)

---

## 4. Model Inventory

**Core Business Models (Eloquent):**

| Model | Location | Purpose | Observations |
|-------|----------|---------|--------------|
| Election | `app/Models/` | Election configuration and state | Central aggregate |
| Post | `app/Models/` | Voting positions (roles) | Multi-tenanted |
| Candidacy | `app/Models/` | Candidate submissions | Linkage to Post |
| Vote | `app/Models/` | Recorded votes (production) | Base class: BaseVote |
| DemoVote | `app/Models/` | Demo mode votes | Shadow table for testing |
| Result | `app/Models/` | Election results (derived) | Base class: BaseResult |
| DemoResult | `app/Models/` | Demo mode results | Shadow table |
| Code | `app/Models/` | Voter authentication codes | Two-code system |
| DemoCode | `app/Models/` | Demo voter codes | Shadow for testing |
| Voter | `app/Models/` | Voter registry | Linked to elections |
| VoterSlug | `app/Models/` | Voter session identifiers | Workflow state tracking |
| VoterSlugsStep | `app/Models/` | Voter step progression | 5-step voting workflow |
| User | `app/Models/` | Platform users | Multi-tenant |
| Organisation | `app/Models/` | Tenant organizations | Root tenant entity |
| ElectionMembership | `app/Models/` | Voter eligibility per election | Voter assignment |
| ElectionOfficer | `app/Models/` | Election officers (chief, deputy, commissioner) | Authority model |
| Member | `app/Models/` | Organizational members | Membership management |
| Region | `app/Models/` | Geographic regions | State/province filtering |

**Security & Governance Models:**

| Model | Location | Purpose |
|-------|----------|---------|
| ElectionSecurityEvent | `app/Models/` | Security event recording |
| SovereigntyDivergenceSummary | `app/Models/` | Divergence tracking |
| DivergenceObservationWindow | `app/Models/` | Temporal observation windows |

**Context-Named Directory Models:**
- `app/Contexts/Elections/Domain/` — Elections domain models
- `app/Contexts/Governance/` — Governance decision models
- `app/Contexts/Membership/` — Membership lineage models
- `app/Contexts/Geography/` — Country/region models

---

## 5. Migration Inventory

**Enumerated Migrations (100+ total, arranged by date):**

### Core Tables (2026-03-05)
- `2026_03_05_000001` — organisations (UUID, multi-tenant root)
- `2026_03_05_000002` — users (UUID, platform users)
- `2026_03_05_000003` — user_organisation_roles (bridge table)
- `2026_03_05_000004` — elections (UUID, election configuration)
- `2026_03_05_000005` — posts (UUID, voting positions)
- `2026_03_05_000006` — candidacies (UUID, candidate submissions)
- `2026_03_05_000008` — voter_registrations (UUID)
- `2026_03_05_000009` — voter_slugs (UUID, session identifiers)
- `2026_03_05_000010` — voter_slug_steps (step progression)
- `2026_03_05_000011` — votes (UUID, anonymous votes)
- `2026_03_05_000012` — results (UUID, election results)
- `2026_03_05_000013` — demo_* tables (shadow schema for testing)
- `2026_03_05_000014` — Laravel standard tables (users, sessions, jobs, etc.)
- `2026_03_05_000015` — Spatie permission tables (roles, permissions)

### Voting System Updates (2026-03-06 to 2026-03-10)
- `2026_03_06_111241` — demo_votes receipt_hash and participation_proof
- `2026_03_06_add_position_order_to_demo_posts` — Candidate ordering
- `2026_03_07_000001` — Priority 1 tables (members, voters, organisation_participants)
- `2026_03_07_145528_145530` — Organisation/member/voter user assignments
- `2026_03_07_152934` onwards — Voting state columns, session tracking, device fingerprint
- `2026_03_08_104633_104658` — session_name and voting_slug to codes tables
- `2026_03_08_113000` — step_data JSON column to voter_slug_steps
- `2026_03_08_140000` — Missing columns to demo_candidacies
- `2026_03_08_163734` — demo_results table creation
- `2026_03_08_171418_192306` — Vote data alignment, candidate column size fixes
- `2026_03_10_000000_000002` — Final vote column fixes, step tracking

### Features (2026-03-11 onwards)
- `2026_03_11_000000` — no_vote option to results
- `2026_03_16_155012` — member_import_jobs table
- `2026_03_17_213211_220000` — Election slug uniqueness, membership indexes
- `2026_03_20_205442_205443` — results_published flag, legacy commission role conversion
- `2026_03_22_213421` — Suspension proposal columns
- `2026_03_23_170035_224518` — Candidacy applications with photos
- `2026_03_25_210316` — code1 nullable (two-code flexibility)
- `2026_03_27_190121` — no_vote_option to votes table
- `2026_03_28_185033` — Logo to organisations
- `2026_04_02` onwards — Organisation invitations, membership types, fees, renewals
- `2026_04_05_000001_000004` — Membership participants, grants_voting_rights flag
- `2026_04_06_000001_000002` — User name fields, newsletter system
- `2026_04_12_000001_000004` — Election settings, voter verification modes

**Total Migrations Located:** 100+

---

## 6. Route Inventory

**Route Files Located:**

| File | Purpose |
|------|---------|
| `routes/api.php` | Main API routes |
| `routes/api_v1.php` | Versioned API v1 |
| `routes/web.php` | Web application routes |
| `routes/platform.php` | Platform-level routes |
| `routes/organisations.php` | Organisation management |
| `routes/user/userRoutes.php` | User account and profile |
| `routes/user/googleRoutes.php` | Google OAuth integration |
| `routes/election/electionRoutes.php` | Election management |
| `routes/vote/` | Vote submission and voting workflow |
| `routes/api/governance.php` | Governance API endpoints |
| `routes/api/governance_v1.php` | Versioned governance API |
| `routes/governance-api.php` | Alternative governance routes |
| `routes/committee/committeeRoutes.php` | Committee operations |
| `routes/geography/geographyApiRoutes.php` | Geographic region APIs |
| `routes/finance/financeRoutes.php` | Financial operations (fees, payments) |
| `routes/security/security_routes.php` | Security-related endpoints |
| `routes/jetstream.php` | Jetstream authentication |
| `routes/channels.php` | Broadcasting channels |
| `routes/console.php` | Console command routing |
| `routes/test-email.php` | Email testing |
| `routes/acadamy/acadamyRoutes.php` | Academy/learning content |
| `routes/openion/openionRoutes.php` | Opinion/poll system |

**Total Route Files:** 21

**Not yet searched:** Specific route definitions (endpoints, parameters, controller bindings)

---

## 7. Service / Helper Inventory

**Infrastructure Services Located:**

| Service | Location | Purpose |
|---------|----------|---------|
| DemoElectionCreationService | `app/Services/` | Demo election provisioning |
| VoterSlugService | `app/Services/` | Voter session management |
| ElectionLifecycleEngineImpl | `app/Application/Election/Services/` | Election state transitions |
| ConstitutionalTransitionGuard | `app/Application/Election/Services/` | Governance precondition validation |
| TrustPolicyEvaluator | `app/Application/Election/Security/` | Trust assessment |
| TrustSnapshotAssembler | `app/Application/Election/Security/` | Trust evidence collection |
| SnapshotAssembler | `app/Application/Election/Security/` | State snapshot creation |
| OverlayAggregator | `app/Application/Election/Security/` | Security observation aggregation |
| ConstitutionalDriftMonitor | `app/Application/Election/Monitoring/` | Constitution compliance monitoring |
| GovernanceStateReconstructionService | `app/Contexts/Governance/Domain/Replay/` | State replay from events |
| CommitteeHierarchyBuilder | `app/Contexts/Governance/Application/Services/` | Committee structure building |
| DivergenceLogger | `app/Services/Constitutional/` | Divergence observation logging |
| DivergenceObserver | `app/Services/Constitutional/` | Divergence detection |

**Not yet searched:** Complete inventory of all helper classes and utilities

---

## 8. Governance Artifact Inventory

**Constitutional/Governance Files Located:**

| Type | Files | Purpose |
|------|-------|---------|
| Constitution | `app/Domain/Election/Constitution/` | Constitutional rules and threshold interpretation |
| Transition Guards | `app/Application/Election/Services/ConstitutionalTransitionGuard.php` | Precondition validation for state transitions |
| Authority Models | `app/Models/ElectionOfficer.php`, `app/Contexts/Governance/Domain/Authority/` | Officer roles and authority delegation |
| Governance Decisions | `app/Contexts/Governance/Domain/GovernanceDecision.php`, `app/Contexts/Governance/Domain/Events/GovernanceDecisionRecorded.php` | Decision recording and events |
| Constitutional Policies | `app/Domain/Election/Security/Simplified/ConstitutionalPolicy.php`, `app/Contexts/Membership/Domain/Committee/Policies/ConstitutionalLegitimacyPolicy.php` | Legitimacy and governance enforcement |
| Trust Policies | `app/Application/Election/Capabilities/Policies/TrustCapabilityPolicy.php`, `app/Application/Election/Capabilities/Policies/EvidenceCapabilityPolicy.php` | Trust and evidence capability constraints |
| State Machine | `app/Domain/Election/StateMachine/TransitionMatrix.php` | Election state transitions and guards |
| Security Events | `app/Domain/Election/Security/Event/` | Constitutional events (LegitimacyGranted, ConstitutionalDenialIssued, etc.) |
| Committees | `app/Contexts/Membership/Domain/Committee/CommitteeGovernanceInterpreter.php`, `app/Contexts/Governance/Domain/Committee/` | Committee authority and governance |
| Constitutional Basis | `app/Contexts/Governance/Domain/ValueObjects/ConstitutionalBasis.php` | Constitutional value objects |

**Constitutional Artifacts Located:**
- Multiple constitutional value objects, policies, and decision types
- State machine with governance constraints
- Trust evaluation pipelines with evidence collection capability
- Committee governance structures with legitimacy policies
- Security event recording for constitutional decisions

---

## 9. Audit Artifact Inventory

**Audit/Logging/Replay Files Located:**

| Type | Files | Purpose |
|------|-------|---------|
| Audit Traits | `app/Traits/HasAuditFields.php` | Audit field mixins for models |
| Replay Services | `app/Contexts/Governance/Domain/Replay/GovernanceStateReconstructionService.php` | State reconstruction from events |
| Replay Events | `app/Domain/Election/Replay/Event/` (ReplaySessionOpened, ReplayCertificationIssued, ReplayDivergenceDetected) | Event-sourced audit trail |
| Replay Evidence | `app/Domain/Election/Replay/ReplayEvidenceEnvelope.php` | Audit evidence structure |
| Divergence Tracking | `app/Services/Constitutional/DivergenceLogger.php`, `app/Services/Constitutional/DivergenceObserver.php` | Constitutional divergence observation |
| Divergence Models | `app/Models/SovereigntyDivergenceSummary.php`, `app/Models/DivergenceObservationWindow.php` | Divergence persistence |
| Divergence Types | `app/Domain/Election/Security/DivergenceType.php`, `app/Domain/Election/Security/DivergenceSeverity.php`, `app/Domain/Election/Security/DivergenceCategory.php` | Divergence classification |
| Middleware Logging | `app/Http/Middleware/LogDivergenceObservations.php` | Request-level divergence tracking |
| Security Events | `app/Models/ElectionSecurityEvent.php` | Security event recording |
| Commands | `app/Console/Commands/AuditCleanup.php` | Audit maintenance |

**Audit and Replay Artifacts Catalogued:** Infrastructure for governance, security event tracking, and state divergence monitoring.

---

## 10. Documentation Sources

**Located Documentation Files:**

| Category | Files | Location |
|----------|-------|----------|
| Architecture Decision Records (ADRs) | 15+ files | `docs/adr/`, `docs/architecture/decisions/` |
| Design Documents | Multiple | `docs/design/`, `docs/ARCHITECTURE.md`, `docs/GEO-3*.md` |
| Developer Guides | Setup, authentication, election management | `docs/election/`, `docs/election_management/`, `docs/developer_guide/` |
| Project Plans | 20+ timestamped plans | `docs/plans/`, `claude/plans/` |
| Business Case | Executive summary, competitive analysis, feature matrix | `docs/business_case/` |
| Membership System | Management guide, member import guide | `docs/membership/` |
| Technical Reports | Testing, validation, implementation summaries | `docs/TESTING_*.md`, `docs/PHASE_*.md` |
| Round 16 Discovery | Candidate assessment, ARB decisions, findings | `docs/architecture/discovery/` |
| Round 17 Discovery | Charter, investigation plan, hypothesis register | `docs/architecture/discovery/Round17_*` |

**Not yet catalogued:** Specific content of all ADRs and their decision rationale

---

## 11. Test Structure

**Test Categories Located:**

| Category | Count | Location | Purpose |
|----------|-------|----------|---------|
| Feature Tests | 80+ | `tests/Feature/` | End-to-end workflow testing |
| Unit Tests | 100+ | `tests/Unit/` | Component-level testing |
| Architecture Tests | 10+ | `tests/Architecture/` | Architectural constraint validation |
| Integration Tests | 15+ | `tests/Integration/` | Multi-component integration |
| Replay Tests | 2+ | `tests/Replay/` | State replay determinism |

**Test Coverage Areas:**

| Area | Status | Example |
|------|--------|---------|
| Authentication | Tested | Login, registration, OAuth |
| Multi-tenancy | Tested | Tenant isolation, consistency |
| Voting Workflow | Tested | Complete flow, state transitions |
| Constitutional Rules | Tested | Transition validation, suspension |
| Governance | Tested | Authority, legitimacy, decisions |
| Replay Determinism | Tested | State reconstruction |
| Security Policies | Tested | Device binding, network binding |
| Audit | Tested | Divergence tracking |
| Membership | Tested | Eligibility, assignments |
| Geography | Tested | Region filtering |

**Not yet searched:** Specific test implementations and coverage percentages

---

## 12. Known Gaps and Unknown Areas

### Not Yet Searched

| Area | Status | Reason |
|------|--------|--------|
| Route-level endpoint definitions | Not Yet Searched | Route files located but content not analyzed |
| Specific migration schema details | Not Yet Searched | Migration files located, full schema not extracted |
| Complete service/helper classes | Not Yet Searched | Partial inventory only; comprehensive scan pending |
| API endpoint parameters and payloads | Not Yet Searched | Controllers located but request/response schemas not documented |
| Specific ADR decisions and rationale | Not Yet Searched | ADR files located but content not reviewed |
| Full test implementation details | Not Yet Searched | Test structure located but specific assertions not analyzed |

### Not Yet Located

| Concept | Search Scope | Finding |
|---------|--------------|---------|
| Explicit Dispute/Challenge handling | Repository-wide keyword search | Only 3 files found; minimal dispute-related code |
| Challenge authority definition | Controllers, services, models | No dedicated challenge authority implementation found |
| Dispute resolution processes | Code and documentation | No explicit dispute resolution workflow discovered |
| Challenge evidence standards | Models, policies | Standards not explicitly documented in located files |

### Evidence of Absence

| Concept | Search Method | Confidence |
|---------|--------------|-----------|
| Coercion-prevention mechanisms | Keyword search ("coercion", "override", "force") | No evidence found (Low search confidence) |
| Constitutional amendments process | Constitutional files, policies | No amendment mechanism found (Medium search confidence) |
| Formal challenge procedures | Comprehensive keyword search | Minimal evidence; structure may be implicit (Medium search confidence) |

**Status Classification:**

```
Not Yet Searched:  Areas we haven't examined yet
Not Yet Located:   Areas we've searched but found minimal evidence
Evidence of Absence: Comprehensive searches with no evidence found
```

---

## 13. Reconnaissance Summary

### Artifacts Located

**Constitutional and Governance:**

✅ ConstitutionalTransitionGuard, ElectionConstitution.php, legitimacy and trust policies

✅ Organisation_id isolation throughout schema, models, and application layer

✅ Separate Vote and Result tables; votes without user_id linkage

✅ Divergence tracking, security events, replay evidence envelopes, audit trails

✅ Demo_* shadow tables for testing

✅ GovernanceDecisionRecorded, LegitimacyGranted, ConstitutionalDenialIssued events

✅ TrustPolicyEvaluator, TrustCapabilityPolicy, policy sequences

✅ GovernanceStateReconstructionService, ReplayEvidenceEnvelope, state reconstruction services

✅ voter_slug_steps table with step progression

✅ ElectionOfficer model with chief, deputy, commissioner, platform_admin roles

✅ Election state transitions with guards and preconditions

**Context-Named Directories:**

🟡 `app/Contexts/Elections/`, `app/Contexts/Governance/`, `app/Contexts/Membership/`, `app/Contexts/Geography/` located in file system

**Security-Related:**

🟡 OverlayAggregator, ParticipationDensityObservation, NetworkContinuityObservation classes located

🟡 Sovereignty-related classes and tests located in Domain/Election/Security/

**Limited Quantity or Not Located:**

⚠️ Dispute/Challenge procedures: Only 3 files found with minimal dispute-related code

⚠️ Coercion prevention: No implementations found in keyword search

⚠️ Constitutional amendments: No process found in code

**Ambiguous Relationships:**

❓ Voting/Tallying: Vote and Result tables exist separately; timing and sequencing of updates requires investigation

❓ Audit structure: Operational logging and governance replay both present; unified or separate requires investigation

❓ Evidence's architecture: Evidence mechanisms present (votes, decisions, replay); structural role requires investigation

❓ Legitimacy: Enforcement policies present in code; organizational definition requires investigation

---

## Phase 0 Completion Status

**✅ COMPLETE**

Reconnaissance has produced a comprehensive inventory of repository artifacts without interpretation. 

**Next Step:** Confirm that the six investigation streams still align with the repository reality discovered in Phase 0. If necessary, adjust stream evidence sources before Phase 1 begins.

---

## Evidence Classification

**This codebase map is Tier 2 evidence (Implementation Evidence from Code Structure).**

This inventory documents code structure, file locations, and artifact presence. It is not Tier 1 (Observed Runtime Behavior).

Catalog only: No architectural interpretations, no hypothesis validation, no conclusions about boundaries, roles, or meanings.

---

**Prepared for:** Round 17 Step 2 Investigation Execution

**Date:** 2026-06-07

