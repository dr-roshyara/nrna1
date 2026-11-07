# Cross-project strategic DDD discovery: social contribution, recognition and impact

> **This report is a read-only discovery of existing work. It does not approve a bounded context, assign ownership, propose an implementation, recommend a scoring algorithm or create a ticket.** It waits for the Principal's review.

| | |
|---|---|
| **Status** | DISCOVERY, DRAFT EVIDENCE PACKAGE. Not independently reviewed. |
| **Authority** | generated (Claude; DDD architect / software archaeologist roles). Not authoritative without human review. |
| **Authorisation** | Principal assignment "Cross-Project Strategic DDD Discovery: Social Contribution, Recognition and Impact" (2026-11-07). Read-only. Writing this report was explicitly authorised in session ("write the report in proper folder"). No code, schema, data, test or commit change. |
| **Date** | 2026-11-07 |
| **Placement** | Requested path `docs/reviews/` does not exist in this repository. `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); placed beside comparable discovery reports (e.g. DR-07a) in `docs/publicdigit/architecture/reviews/`. |

**Labels.** [F] verified fact (code or document, with path) · [I] architectural inference · [H] hypothesis requiring domain validation · [OQ] open question for the business domain expert.
**Status codes.** I implemented end-to-end · P partially implemented · D documented/designed only · B backlog/planned · N not found · U unclear.

**Project roots and abbreviations.**
- **nrna1** = `/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/nrna1` (PublicDigit, Laravel 11 + Vue 3/Inertia 2).
- **public-digit** = `.../personal/public-digit` (Nx monorepo; Angular 20 + Capacitor mobile; the Laravel backend is not present in this checkout).
- **election** = `.../personal/election` (Laravel 12 + Inertia 2/Vue TS, spatie multitenancy).
- All three paths resolve to `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/*`.

---

## 1. Executive summary

1. **[F] Something has been built, in nrna1 only.** It is a **Contribution Points module** (April 2026): `contributions` and `points_ledger` tables, a scoring formula (`GaneshStandardFormula`), a leaderboard, four Vue pages and service-level tests.
   - It is **partially implemented (P)**. A member can submit a self-reported contribution, and it stays `pending`.
   - **No route, controller or job verifies, approves, rejects or awards points.** `ContributionPointsService::awardPoints()` is called only from tests.
   - No evidence file is ever stored. The member sets their own proof type and outcome bonus.
   - No navigation links reach the pages.
2. **[F] There is a scoring formula**, and section 10 quotes it exactly. It turns hours, proof type, skill diversity, recurrence and a self-declared outcome bonus into points.
   - [I] It measures *effort weighted by claimed evidence*. It does not measure social outcome or impact.
   - It conflicts with the constraints of backlog item **PBDIGIT-330** ("no arbitrary universal numerical scores"; self-reported, verified and reviewed records "must remain distinguishable").
3. **[F] Committee role history exists in nrna1 (`committee_assignments`)**, but its save path **overwrites `left_date` of members who have already left**, on every save. Office-holder activity and performance recording (PBDIGIT-330) is **backlog only, with implementation not authorised**. Its ownership depends on the open item ARB-8.
4. **[F] public-digit contains no backend code.** Its Laravel backend is an empty git submodule pointer. It does hold the **richest design corpus**:
   - verified `member_activities` with `verification_status`/`verified_by`;
   - a polymorphic `documents` table with `file_hash` and verification status;
   - committee/role-assignment tables with terms;
   - several leadership-score and leaderboard formulas;
   - an explicit counter-principle: scores must be "derived, explainable, non-transferable … never authoritative".
5. **[F] election has nothing in this domain** beyond roadmap bullets and a dead "Volunteer Management" UI tile. It does have the best **implemented approval-with-history pattern**: the registration state machine plus an append-only transitions table.
6. **[F] None of the three projects has an implemented concept for:**
   - recognition, awards, appreciation certificates or honours;
   - beneficiaries, reach or outcome measurement;
   - impact assessment;
   - member biography or public contribution profile;
   - evidence attachment for a member activity.
7. **[I] The six distinctions asked for are mostly collapsed where anything exists.** These are activity performed, contribution claimed, contribution verified, recognition received, outcome achieved and impact demonstrated. nrna1's single `contributions.status` enum mixes "claimed" (pending) with "verified" and "approved". "Outcome" is a self-entered integer bonus. Recognition and impact do not exist.

---

## 2. Repository and project status

| Project | Repo root | Branch | HEAD | Working tree |
|---|---|---|---|---|
| nrna1 | `/home/d0f38614-…/roshyara/personal/nrna1` | `election-flow-updated` | `78f87094c` (2026-11-07) docs(membership): J-1/J-3/J-4 decision record… | 38 modified/untracked entries (session logs, docs, `bootstrap/cache/packages.php`), none touched by this discovery |
| public-digit | `/home/d0f38614-…/roshyara/personal/public-digit` | `main` | `a766ef5f2` (2026-01-09) branding | clean |
| election | `/home/d0f38614-…/roshyara/personal/election` | `main` | `50bb80e49` (2025-10-30) tenant log in | clean |

**[F] Stack facts that change how the evidence should be read:**
- **nrna1.** Laravel 11 / PHP 8.2 / Vue 3 + Inertia 2, multi-tenant via `organisation_id`. It mixes DDD contexts under `app/Contexts/*` (Membership, Governance …) with legacy Eloquent code under `app/Models`, `app/Services` and `app/Http/Controllers`.
- **public-digit.**
  - `packages/laravel-backend` is a gitlink (mode `160000`, commit `a5a9313…`): the directory is empty and there is no `.gitmodules`. Only one `.php` file is tracked, a branding script.
  - `apps/mobile` is Angular 20 + Capacitor. `domains/membership` is an empty Nx scaffold.
  - The remaining roughly 600 Markdown design files are under `architecture/`, `developer_guide/` and `mobile_architect/`.
  - **Every "implemented" claim in public-digit documentation is therefore unverifiable from this checkout (U).**
- **election.**
  - Laravel 12 with contexts `Election`, `Platform`, `Shared` and `TenantAuth` under `app/Contexts`. There is no Membership, Finance, Engagement or Project context.
  - Tenant provisioning runs `--path database/migrations/tenant`, which does not exist (`TenantProvisioningService.php:279`). Context migrations are run separately.

---

## 3. Search methodology and scope

- **Method.** One read-only search per project, then direct verification of every load-bearing claim against the source files. Verification covered the formula, callers of `awardPoints`, the routes, the controller `store`, the `committee_assignments` save path, PBDIGIT-330 text, the public-digit gitlink, and the election routes and volunteer tile.
- **Excluded from broad searches:** `vendor/`, `node_modules/`, build outputs (`public/build`, `dist`, `.angular`, `.nx`), `storage/`, `bootstrap/cache`, `*.zip`, `*.log`, and SQL backups (row data never read). In nrna1, `docs/knowledgeos/**` was searched, but hits are reported only where they concern contribution as a product concept; none did.
- **Layers searched in each project:** migrations (landlord, tenant and context), models and entities, services, controllers, route files, Vue/Angular components, locale files (en/de/np), tests, `docs/`, `architecture/`, `architect/`, `developer_guide/`, backlog, plans, and git history (`git log`).
- **English vocabulary.** All of the assignment's §3 groups A to E were searched, plus: points, score, scoring, rank, ranking, leaderboard, tier, badge, gamification, proof, attestation, effort, hours, donation, levy, fee, honorary, office holder, tenure, appointment, role history.
- **Nepali vocabulary.** योगदान, सामाजिक सेवा, समाजसेवा, कार्य, सम्मान, पुरस्कार, प्रशंसापत्र, प्रमाण, प्रमाणपत्र, सत्यापन, लाभार्थी, प्रभाव, उपलब्धि, स्वयंसेव, सदस्यता. **[F] There are no hits in this domain sense in any project.**
  - सत्यापन appears only for email or vote verification.
  - प्रमाणपत्र appears only for credentials or citizenship certificates.
  - सम्मान appears only as "regards".
- **Limit.** "Not found" means not found within this scope and vocabulary. It does not prove absence in, for example, a backend repository that is not checked out (public-digit) or an unapplied production database.

---

## 4. Findings for nrna1

### 4.1 Contribution Points module, status **P**

**Schema**
- [F] `database/migrations/2026_04_11_000001_create_contributions_tables.php`:
  - **`contributions`:**
    - uuid, `organisation_id`, `user_id` (references `users`, not `members`), `title`, `description`
    - `track` enum micro / standard / major (L20)
    - `status` enum draft / pending / verified / approved / rejected / appealed / completed (L21)
    - `effort_units` integer hours (L27)
    - `proof_type` enum (L34) and `proof_path` nullable (L37)
    - `team_skills` json, `is_recurring`, `outcome_bonus`, `calculated_points`
    - `verifier_notes`, `verified_by`/`verified_at`, `approved_by`/`approved_at`, `created_by`
    - soft deletes
  - **`points_ledger`:** `action` enum earned / spent / adjusted / appealed (L83), `reason`, `created_by`.
  - **`users.leaderboard_visibility`:** public / anonymous / private, default anonymous (L108).
- [F] `…/2026_04_11_000002_add_community_attestation_proof_type.php` adds proof type `community_attestation`.

**Models**
- [F] `app/Models/Contribution.php`, which uses `BelongsToTenant`, and `app/Models/PointsLedger.php`.
- The ledger is called "immutable" in comments only; nothing enforces it at the database or model level.

**Routes**
- [F] `routes/organisations.php` L212-219, inside `organisations/{organisation:slug}` with the `auth`, `verified` and `ensure.organisation` middleware:
  - `contributions` index / create / store / show
  - `/leaderboard`
- **There is no verify, approve, reject, appeal or admin-review route.**

**Controller**
- [F] `app/Http/Controllers/Contribution/ContributionController.php::store` (L62-90):
  - the authenticated member validates and submits `proof_type` (any of six values) and `outcome_bonus` (0-200) **themselves**;
  - status is set to `pending`;
  - no file is uploaded and `proof_path` is never written.
- `show` is owner-only. `index`/`create` call `pointsService->getWeeklyPoints()` only (L31, L48).

**Points service**
- [F] `app/Services/ContributionPointsService.php::awardPoints()` (L18-52) computes the points, writes `calculated_points`, and inserts a `points_ledger` row with action `earned`.
- **No production caller:** grep of `app/` and `routes/` finds only the constructor injection and `getWeeklyPoints` calls. Only `tests/Feature/Contribution/ContributionPointsServiceTest.php` calls it.

**Leaderboard**
- [F] `app/Services/LeaderboardService.php` sums `earned` ledger points per user per organisation, hides `private` users and labels `anonymous` users "Contributor #N".
- No UI sets `leaderboard_visibility`.

**Vue pages and tests**
- [F] `resources/js/Pages/Contributions/{Index,Create,Show,Leaderboard}.vue`.
  - `Create.vue` repeats the formula in JavaScript and says "With photo/document proof", but has no upload field.
  - Grep found no navigation link to these pages from outside the pages themselves.
  - The text is hard-coded English.
- [F] Tests (`tests/Feature/Contribution/{PointsCalculatorTest,ContributionPointsServiceTest,LeaderboardServiceTest}.php`) test the formula and services only. There are no HTTP or controller tests.

**Design trail**
- [F] `architecture_legacy/scoring_architecture/`: the implementation prompt, the formula update, a research paper on scoring, and research PDFs on volunteering and perceived social contribution.
- [F] `docs/superpowers/plans/2026-04-11-ganesh-formula-v2.md`, `developer_guide/contribution/` (23 files), and commit `a05b7d8b3` "feat(scoring): implement GaneshStandardFormula v2".

**The developer guide describes more than the code does (D)**
- [F] `developer_guide/contribution/admin/01-reviewing-contributions.md` describes admin review and approval. It does not exist in code.
- [F] `admin/02-outcome-bonus.md` says admins set the outcome bonus. In code, the member sets it.
- [F] `committee/01-leaderboard-guide.md` L22-37 and L79 describe Bronze (50+), Silver (150+) and Gold (300+) tiers and a "Gold Tier Award", and `committee/03-recognition-ceremonies.md` describes recognition ceremonies. **No tier, badge or award code exists.**

**Assessment against the §4 questions**

| Question | Answer |
|---|---|
| Business problem | Recognising volunteer effort by points |
| Who initiates | The member, as a self-report |
| What is captured | Title, description, hours, track, claimed proof type, skills, recurrence, self-claimed outcome bonus |
| Owning aggregate | **Not established.** It is a plain Eloquent model outside any DDD context; there is no ADR. |
| Evidence | A label only, with no stored artefact |
| Approval | Columns exist; there is no workflow and the authority is not defined |
| Linked to | Organisation and user; **not to member, committee or project** |
| History | `created_by`, soft deletes, and the ledger (not enforced as immutable) |

### 4.2 Committee assignment and role history, status **P (history is corrupted)**

**Table**
- [F] `app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_01_16_000003_create_committee_assignments_table.php`:
  - `role_path`, `joined_date`, `left_date`, `is_active`, `election_date`, `term_end_date`
  - `nomination_type` (elected / appointed / volunteered), `appointed_by_user_id`, `notes`, `metadata`
  - a partial unique index allows one active assignment per member per committee, and a check constraint requires `left_date ≥ joined_date`.

**Domain**
- [F] `app/Contexts/Membership/Domain/Committee/CommitteeAssignment.php`: `endAssignment`, `reactivate`, `updateRole`, `getDurationDays`.
- [F] `Application/Committee/RemoveMemberFromCommittee.php` ends an assignment instead of deleting it.

**Defect (verified)**
- [F] `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepository.php` loads **all** assignment rows (L45-49) but maps only the active ones into the aggregate (L53).
- On save, "Step 4" (L142-149) runs `update(['left_date' => now(), 'is_active' => false])` on **every** row whose id is not in the aggregate. That includes rows that were already inactive.
- **Each save therefore overwrites the historical leave dates of former members.**
- This corroborates GMAP finding G-9 ("appointment history is append-only — violated today") in `docs/publicdigit/reviews/2026-11-02-governance-domain-discovery-map-roadmap-and-readiness.md`.

**Other gaps**
- [F] A second store, `committee_member_projection` (migrations `2026_05_16_000000…` and `…000003`), has no history fields. GMAP records "three live assignment stores with different lifecycles".
- [F] No Vue page displays a member's role history.

### 4.3 Committee "Activities" page, status **N (empty stub)**

- [F] `resources/js/Pages/Committee/Activities.vue` renders only the heading "Activities".
- It is wired to `CommitteeController::activities()`, which supplies no data, through `routes/committee/committeeRoutes.php:22`.

### 4.4 PBDIGIT-330, office-holder responsibility, activity and performance recording, status **B**

- [F] `docs/publicdigit/backlog/PBDIGIT-330-committee-office-holder-responsibility-activity-and-performance-recording.md`.
  - Status `PROPOSED`, "implementation not authorised".
  - Candidate concepts, explicitly unapproved (§5, L90): CommitteePost, OfficeHolder, Responsibility, Delegation, TermObjective, Commitment, Activity, Deliverable, Outcome, Evidence, ProgressReview, PerformanceAssessment.
- [F] Constraints:
  - "support accountability without reducing performance to arbitrary numerical scores" (L35)
  - "Different posts must not be compared with arbitrary universal numerical scores" (L109)
  - "Self-reported activities, verified evidence and formal review decisions must remain distinguishable" (L110)
  - "performance scoring or ranking of office holders" is out of scope (L131)
- [F] It depends on ARB-8 (ownership of Appointment / Tenure / Mandate), which is open. **It does not reference the existing Contribution module (§4.1).**

### 4.5 Governance and organisation design concepts, status **D**

- [F] GMAP (`docs/publicdigit/reviews/2026-11-02-…`) proposes Position, Appointment, Tenure, EffectiveTenure and Mandate as [P] candidates.
- [F] ODA-01 v1.1 (`docs/publicdigit/architecture/reviews/2026-11-03-organisation-domain-architecture-baseline-oda-01-v1.1.md`) marks leadership and tenure-history ownership as [U] ARB-8.
- [F] `docs/publicdigit/architecture/strategic-design-status.md` contains **no** mention of contribution, recognition or activity. It mentions only O-9 "Membership ↔ tenure" and ARB-8.

### 4.6 Members-forum "contribution", status **D, and a different concept**

- [F] `docs/architecture/members-forum-context/20260605-0940-DOMAIN-EVENTS.md` (around L649-667) defines `MemberContributionRecorded`, with types PROBLEM_CREATION / IDEA_CREATION / COMMENT / EVIDENCE / DECISION_REQUEST, and says "Enables member reputation (future feature)".
- [I] Here "contribution" means a forum *deliberation input*, not social service. The **same word names two business concepts**.

### 4.7 Member profile and directory

- [F] `members.personal_info` json holds name, email and phone only.
- [F] The `member_directories` projection holds display name, email, status and membership type only.
- [F] There is no biography field on members or users, and no public profile.
- [F] The only biography is the election-scoped candidate statement (`resources/js/Pages/Election/Posts/Partials/CandidateForm.vue:34`).

### 4.8 Financial contribution, kept separate from social contribution

- [F] Membership fees and renewals are implemented (`routes/organisations.php` around L192-210: `fees.*`, `renew`, `mark-paid`, `waive-fees`). They were not traced end to end.
- [F] The legacy `incomes` table (`2022_10_02_113256_create_income_table.php`) has `event_contribution` and `deligate_contribution` monetary columns.
- [F] There is no donation or donor model.
- [I] "Contribution" in finance means **money**; in §4.1 it means **effort**. This is terminology overloading.

### 4.9 Certificates

- [F] No appreciation, award or recognition certificate exists.
- [F] The only "certificate" is the `esp-certificate` CSS block in `resources/js/Pages/Election/Show.vue` (a vote-confirmation panel).

### 4.10 Reusable mechanisms (outside this domain, noted for reuse only)

- [F] `org_membership_facts`, an append-only fact history (`2026_10_09_100000_create_org_membership_tables.php`).
- [F] `app/Traits/HasAuditFields.php`.
- [F] `election_audit_logs`, `newsletter_audit_logs`, and outbox `provenance` (`2026_07_10_000001…`).
- [F] `committee_associations.approved_by_actor_id/type`.
- [F] `newsletter_attachments`, the only generic attachment table.

---

## 5. Findings for public-digit

**[F] Nothing in scope is implemented in this checkout.** All entries below are D or B, except the mobile interfaces, which are P (types and a service with no consumer).

### 5.1 Activity, volunteering and participation (D)

**Tables designed in `architecture/backend/tenant_database/architectural_discussion/20251211_2056_tenant_templates_poltical_parties_nepal.md`:**
- **`member_activities`** (L575-596; verified):
  - `activity_type` ENUM meeting / rally / training / volunteer / donation / event / other
  - `activity_date`, `location`, **`hours_spent`**, **`verified_by`**, **`verification_status` ENUM pending / verified / rejected**, `points_awarded` ("for gamification/rewards"), `metadata`
  - a foreign key to `party_members`
- `member_skills` (L599-616): `is_certified`, `verified`.
- `campaign_activities` (L799-826): **`outcome_notes`**, **`photos_urls`**.
- `campaign_volunteers` (L828-851): `total_hours_served`, `performance_rating 1-5`.
- `party_meetings` / `meeting_participants` (L977-1032).
- `event_volunteers` (L1065-1084).

**Other designs**
- [F] `activity_logs` "immutable ledger" with action types EVENT_ATTENDANCE / DONATION / REFERRAL / VOLUNTEER_TASK and `points_earned`: `…/political_parties_nepal/20251217_1634_political_parties_schema.md` L607-631. A polymorphic variant appears in `…/20251217_1728_sql_schema_prompt_instructions.md` around L515-544.
- [F] Lobby / campaign module prompt with `target_outcome`: `…/20251217_1712_claude_cli_instructions_for_membership_digitilazation.md` L360-420.
- [F] `$member->recordParticipation($eventId)` updates a leadership score: `architecture/backend/membership-context/20260101_2356_definite_architecture_member_as_aggregate.md` L502.

### 5.2 Scoring and leaderboards (D/B; no code)

**Formulas found in documents (quoted for evidence only; none is implemented and none is recommended here):**
- [F] `LeadershipScoreCalculator`, `…/membership-context/brainstorming/20260101_2347_ddd_bounding_context_membership.md` L340-360:
  - tenure `min(years×10,100)`
  - recruitment `min(sponsored×5,150)`
  - participation `min(events×2,100)`
  - financial `min(contributions/1000,50)`
  - committee `positions×20`
  - total 0-500
- [F] Weighted pillars Growth 40 / Organisation 25 / Fiscal 20 / Engagement 15, with time decay and peer normalisation: `architecture/scoring/scoring_algorithm.md` L11-29. The LaTeX formula itself was stripped from the file.
- [F] Event-sourced points (sponsor +50, meeting +10, dues +25, approved policy +100, 30-day escrow): `architecture/scoring/scoring_algorithm_01.md` L25-54.
- [F] Rank tiers Diamond > 5000, Platinum > 2000, Gold > 1000, and SQL leaderboards by ward, district and province: `…/20251217_1634_political_parties_schema.md` L430-513 and L656-680.
- [F] "WEEK 4: Gamification" (`member_points`, `PointAwardService`): `…/20251217_1915_CORRECTED_IMPLEMENTATION_PLAN.md` L397-503. The acceptance checklist at L997 is unchecked (B).
- [F] Mobile: `apps/mobile/src/app/features/membership/models/member.models.ts` L138-145 has `engagement_score: number`, with no formula and no consumer (P).

**[F] Documented counter-principle:** `architecture/backend/membership-context/brainstorming/20260101_2309_architecture_review.md` L272-292, "Leadership Score Context (Do NOT Gamify Incorrectly)".
- The score must be "Derived, Explainable, Non-transferable".
- Good inputs include "Contribution (forums, volunteering)"; bad inputs include popularity and payments alone.
- `20260101_2313_membership_related_stuff.md` L36 adds that the Member aggregate "IS NOT … a leaderboard entry".

### 5.3 Evidence, verification and audit (D)

- [F] **Polymorphic `documents` table** (`20250612_0847_all_political_parties_scheme.md` L1087-1141):
  - `documentable_type/id`
  - `document_type` including certificate, recommendation_letter, report
  - `file_hash`, `visibility`, **status pending / verified / rejected**, `verified_by_id`, `verified_at`
  - [I] This is the strongest design-level evidence model in any of the three projects.
- [F] `media_files`, `donations.attachment_urls`, `audit_logs` (old/new values, actor, IP), and `membership_history` with `data_snapshot`. These are in the tenant-templates, base-schema and all-parties schema documents cited by the search.
- [F] Mobile `ProfileVerificationRequest` (`document_urls`; verification types email / phone / document / manual), `member.models.ts` L87-105. Status P, with no UI.
- [U] Membership approve/reject with domain events is claimed "✅ COMPLETE" in `developer_guide/laravel-backend/membership-context/20260104_0030_summary_guide_member_approval_rejection_system.md`, but the backend is absent from this checkout.

### 5.4 Recognition, impact and profile

- [F] There is no recognition, award or appreciation-certificate entity.
  - `membership_type 'honorary'` appears in the tenant-templates document (L465).
  - "Member Rank / Recognition of political merit" on the digital card appears in `membership-context/brainstorming/frontend_view.md` (around L506-546).
- [F] Impact and beneficiaries appear only in a tutorial NGO template (`ngo_projects`, `project_beneficiaries`) in `developer_guide/laravel-backend/multi-tenancy/02-template-system.md` L70-157 (D). There is no impact or reach metric.
- [F] Profile designs:
  - `member_profiles` (`bio`, `is_volunteer`, `availability_hours_per_week`, `is_public`): `political_parties_schema.md` L524-548.
  - Mobile `MemberProfile.bio`: `member.models.ts` L16-55 (P, no UI).
- [F] Role and committee history designs:
  - `committee_members` (`position`, `start/end_date`, `appointment_letter_url`).
  - `role_assignments` (term dates, assignment method, `assignment_document_path`, `performance_rating`).
  - These are in the tenant-templates, all-parties and base-schema documents.

---

## 6. Findings for election

- [F] **Nothing implemented for contribution, recognition, evidence-of-activity or impact.**
- [F] **Planning documents only (D/B):**
  - `architect/main-tasks_digitalization.md` L121-146 (`ProjectManagement`, `coordinateVolunteers`) and L305-312 (project management in Phase 3).
  - `architect/20251022_0847_business_case_study.md` L153-164 (Phase 3 "Advanced Membership", an `EngagementContext`, a member directory).
  - Member status "Honorary": `main-tasks_digitalization.md` L21.
- [F] **Dead UI stub:** `resources/js/pages/Domain/Election/CommitteeMemberDashboard.vue` L396-400, the tile "Manage volunteers and assignments" → `/committee/tools/volunteers`. **No such route exists** in `routes/` or `app/`. [I] It concerns election logistics, not member social service.
- [F] **Recognition appears as prose only:**
  - `docs/committee-member-management-guide.md` L434 and L458-462 ("Recognize achievements and contributions")
  - static email text "Heartfelt Appreciation" in `resources/views/domain/election/emails/election-completed.blade.php` L36
- [F] **"Certificate" means election result certification:** `buisness_case/20251022_0851_election_case_business_management.md` (`certified_at`, `certified_by`).
- [F] **Finance, Donation and Membership contexts are designed but absent:**
  - `architect/domain_driven/bounding_contexts/20250924_1912_bounding_context.md`
  - `architect/Contexts/20251023_0049_tenant_auth_vs_membership_contexts.md`
  - Roadmap ticks `[x] Donation management` in `20251022_IMPLEMENTATION_ROADMAP.md` L351-355 have no code behind them (treated as B).
- [F] **Scoring:** "Member engagement scoring" is an unchecked future bullet with no formula (`COMPREHENSIVE_TODO_LIST.md` L936). The only formulas in code are security risk scores (`SecurityAuditService.php` L144-170; `RequestApprovalService.php` around L393).
- [F] **Reusable implemented mechanisms (I):**
  - **Registration review workflow:** `tenant_user_registrations` (draft / submitted / under_review / approved / rejected, with `assigned_to`, `approved_by` and reasons) plus the append-only `tenant_user_registration_transitions` (`from_state`, `to_state`, `triggered_by`, `reason`, `transitioned_at`). Also `RegistrationStateMachine`, domain events, admin routes in `routes/tenant-auth.php` L65-77, Vue pages, and tests in `tests/Contexts/TenantAuth/**`.
  - **Mass-registration batch approval:** `MassRegistrationBatch` aggregate, routes in `…/Routes/mass-registration.php` L21-61. Weakness: `MassRegistrationController::approve` uses `auth()->id() ?? '1'` with a TODO.
  - **Audit and outbox:** `security_audit_logs` and `outbox_events` (with `correlation_id`).

---

## 7. Existing domain capability inventory

| Project | Capability | Status | Domain concept | Evidence | Dependencies | Limitations | Reusability |
|---|---|---|---|---|---|---|---|
| nrna1 | Record a member's contribution claim | P | `Contribution` (Eloquent) | `database/migrations/2026_04_11_000001…`; `ContributionController::store` L62-90 | Organisation, User | Self-report only; linked to user, not member/committee/project; no navigation link | Schema fields are a reference point only |
| nrna1 | Verify / approve a contribution | D (columns only) | `contributions.verified_by/approved_by` | migration; no route in `routes/organisations.php` L212-219 | — | No workflow, no authority, no route | None in code |
| nrna1 | Evidence for a contribution | N (label only) | `proof_type`, `proof_path` | `store` never writes `proof_path` | — | No upload, no hash, no storage | — |
| nrna1 | Points scoring | P (formula and tests; never invoked) | `GaneshStandardFormula`, `ContributionPointsService` | `app/Services/GaneshStandardFormula.php`; no production caller of `awardPoints` | — | Heuristic weights (per its own comments); member sets proof and bonus; conflicts with PBDIGIT-330 | — |
| nrna1 | Leaderboard | P (empty in practice [I]) | `LeaderboardService` | `app/Services/LeaderboardService.php` | `points_ledger` | Ledger never written in production; no visibility UI | — |
| nrna1 | Recognition tiers / awards / ceremonies | D | — | `developer_guide/contribution/committee/*` | — | No code | — |
| nrna1 | Committee role history | P (defective) | `CommitteeAssignment` | `EloquentCommitteeRepository.php` L45-53, L142-149 | Membership, Governance (ARB-8) | Overwrites `left_date`; three stores; no UI | Schema (term dates, nomination type) |
| nrna1 | Office-holder activity / performance | B | PBDIGIT-330 candidates | backlog file L8, L90, L109-110 | ARB-8, ODA-01 C-2, DR-07a | Not authorised | Its constraints are the strongest written domain rules |
| nrna1 | Committee activities page | N (stub) | — | `resources/js/Pages/Committee/Activities.vue` | — | Empty | — |
| nrna1 | Forum "contribution" | D | `MemberContributionRecorded` | `docs/architecture/members-forum-context/20260605-0940-DOMAIN-EVENTS.md` | Forum | Different concept | — |
| nrna1 | Member profile / biography | N | `members.personal_info` | migration `2026_05_03_140057…` | Membership | Contact data only | — |
| nrna1 | Financial contribution (fees) | I/P (not traced) | `membership_fees` | `routes/organisations.php` around L192-210 | Finance | Monetary, not social | Separate concept |
| nrna1 | Append-only fact history | I | `org_membership_facts` | `2026_10_09_100000…` | Membership | — | Pattern for history and provenance |
| public-digit | Verified member activity | D | `member_activities` | tenant-templates doc L575-596 | Members | Design only | Strongest activity / verification design |
| public-digit | Evidence documents | D | polymorphic `documents` | all-parties doc L1087-1141 | — | Design only | Strongest evidence design |
| public-digit | Leadership score / leaderboards | D/B | various | `architecture/scoring/*`; membership-context brainstorming | — | Several competing formulas | Counter-principle "derived, explainable" |
| public-digit | Member profile with bio / visibility | P (mobile types) / D | `MemberProfile` | `member.models.ts` L16-55 | Backend (absent) | No UI | — |
| public-digit | Role assignments with terms | D | `role_assignments`, `committee_members` | all-parties and tenant-templates docs | — | Design only | — |
| public-digit | Beneficiaries | D (tutorial) | `project_beneficiaries` | `developer_guide/laravel-backend/multi-tenancy/02-template-system.md` | — | Example only | — |
| election | Approval workflow with transition history | I | `RegistrationStateMachine`, `tenant_user_registration_transitions` | TenantAuth migrations; `routes/tenant-auth.php` L65-77 | TenantAuth | Scoped to registration | Best implemented approval-with-history pattern |
| election | Batch approval | I | `MassRegistrationBatch` | `mass-registration.php` L21-61 | TenantAuth | Approver identity TODO | Pattern |
| election | Volunteer management | N (dead tile) | — | `CommitteeMemberDashboard.vue` L396-400 | — | No route | — |
| election | Engagement / project / donation contexts | D/B | — | `architect/*` | — | No code | — |
| all | Recognition, awards, appreciation certificate | N | — | §3 vocabulary, all layers | — | — | — |
| all | Impact, beneficiaries, reach, outcome measurement | N (code) | — | §3 vocabulary | — | — | — |

---

## 8. Existing data model and workflow analysis

| Business concept | nrna1 | public-digit (design) | election |
|---|---|---|---|
| **Activity performed** | Not modelled separately; merged into `contributions` | `member_activities`, `campaign_activities`, `meeting_participants`, `event_volunteers` | — |
| **Contribution claimed** | `contributions.status = pending` | `verification_status = pending` | — |
| **Contribution verified** | Same enum (`verified`, then `approved`); no path reaches it | `verification_status = verified`, `verified_by` | (Pattern only: registration `under_review → approved`) |
| **Recognition received** | Docs only (tiers, ceremonies) | Rank tiers, "honorary" | Email prose |
| **Outcome achieved** | `outcome_bonus` integer, self-entered | `outcome_notes` text | — |
| **Impact demonstrated** | — | — | — |

- **[I] Collapsed lifecycle.** In nrna1, *claim*, *verification* and *approval* share one status enum on one row. There is no transition history, so neither who changed the status nor when is preserved. This is the opposite of PBDIGIT-330 L110 (keep these distinguishable).
- **[I] Wrong anchor.** nrna1 contributions hang off `user_id` and the organisation, while membership, committees and tenure are modelled in `app/Contexts/Membership` around `members`. A contribution profile cannot currently be joined to committee role history without crossing that model gap.
- **[F] History-preserving patterns exist elsewhere:**
  - nrna1 `org_membership_facts` (append-only)
  - election `tenant_user_registration_transitions` (append-only state history)
  - public-digit design `membership_history` (snapshot)

---

## 9. Evidence and verification analysis

- **[F] Implemented evidence storage for member activity: none in any project.** nrna1's `proof_path` is never written. election stores files only for CSV batches. public-digit's evidence is design only.
- **[F] Verification authority: undefined everywhere.**
  - nrna1 has `verified_by`/`approved_by` columns but no policy, role or route.
  - public-digit `member_activities.verified_by` references `users` with no rule about who that may be.
  - [OQ] Who may verify a member's social contribution: committee, office holder, peer attestation (the "community_attestation, 3+ members vouch" in the formula comments is not implemented), or an external institution?
- **[I] Evidence weight versus evidence existence.** nrna1 weights points by the *claimed* evidence type (self_report 0.5 to institutional 1.2) without holding the evidence. Claimed evidence and verified evidence are therefore conflated, and the claimant controls the multiplier.
- **[F] Reusable provenance mechanisms:**
  - election transitions table and `security_audit_logs`
  - nrna1 `HasAuditFields`, outbox `provenance`, `election_audit_logs`
  - public-digit `documents.file_hash` design
- **Anonymity.** [I] No finding touches voting anonymity (ADR-T11). Contribution records are personal by nature and must stay outside the `votes`/`results` model.

---

## 10. Contribution measurement analysis

**[F] An implemented scoring mechanism exists in nrna1**, but it is never invoked in production. Exact formula from `app/Services/GaneshStandardFormula.php`:

```
TRACK_CONFIG  micro:    base_rate 10, tier_bonus 0,   min_base 0,   weekly_cap 100
              standard: base_rate 10, tier_bonus 50,  min_base 31,  weekly_cap none
              major:    base_rate 10, tier_bonus 200, min_base 201, weekly_cap none
VERIFICATION_WEIGHTS  self_report 0.5 · photo 0.7 · document 0.8 · third_party 1.0
                      community_attestation 1.1 · institutional 1.2
effective  = h                        if h ≤ 20
           = 20 + log(h − 20 + 1) × 5 otherwise          (DIMINISHING_RETURNS_THRESHOLD = 20)
base       = effective × base_rate
tier       = base ≥ min_base ? tier_bonus : 0
synergy    = 1.0 (1 unique skill) · 1.2 (2) · 1.5 (≥3)
multiplier = min(synergy × verification × (is_recurring ? 1.2 : 1.0), 2.0)
points     = floor((base + tier) × multiplier) + outcome_bonus      (outcome_bonus 0–200, member-entered)
micro track: points = min(points, 100 − pointsAlreadyEarnedThisISOWeek)
```

- **[F] What it means, by its own comments:** the verification weights are "ordinal … cardinal values are heuristic"; the skill bonus is "a heuristic, NOT a true Shapley value"; the cap of 2.0 is heuristic. It includes TODOs to validate it empirically.

**Measurable dimensions captured:**

| Dimension | nrna1 | public-digit (design) | election |
|---|---|---|---|
| Number of activities | Count of rows (not used) | `member_activities` rows | — |
| Time invested | `effort_units` (hours, 1–100) | `hours_spent`, `total_hours_served` | — |
| Financial resources | Separate fees and income (not linked) | `donations`, financial pillar of score | — |
| Beneficiaries | — | Tutorial `project_beneficiaries` only | — |
| Geographic reach | — | Peer normalisation by geography in score design | — |
| Duration of initiative | — | `campaign_volunteers.start/end_date` | — |
| Outcomes | `outcome_bonus` integer | `outcome_notes` text | — |
| Independent recognition | — | — | — |
| Supporting evidence | Claimed type only | `documents`, `photos_urls` | — |

**Key distinctions** (preserved as the assignment requires; these are [I] observations about the existing designs):
- nrna1 points measure effort multiplied by claimed evidence. They do **not** measure outcome or impact.
- The public-digit leadership-score designs mix participation, recruitment, tenure, committee seats and money into one number. That is exactly the conflation the assignment warns against: participation is not leadership, and financial contribution is not social outcome.
- The public-digit counter-principle and PBDIGIT-330 both argue against an authoritative universal score.
- **This report does not evaluate or recommend any of these formulas.**

---

## 11. Cross-project comparison

| Domain capability | nrna1 | public-digit | election | Observations |
|---|---|---|---|---|
| Activity record | P (merged into contribution) | D (rich) | N | Only public-digit separates activity from contribution or points |
| Contribution claim | P | D | N | Independent models: `contributions` (user-anchored) versus `member_activities` (member-anchored) |
| Verification workflow | D (columns only) | D | Pattern only (registration) | No project implements verification of contributions |
| Evidence | N (label) | D (`documents` + hash) | N | — |
| Scoring / ranking | P (one formula) | D (at least five formulas) | N | Formulas differ in inputs and philosophy; one documented counter-principle; PBDIGIT-330 prohibits universal scores for office holders |
| Recognition / awards | D (guide only) | D (tiers, honorary) | N (prose) | No entity anywhere |
| Outcome / impact | P (bonus int) | D (notes) | N | No impact concept anywhere |
| Committee / role history | P (defective) | D | P (`election_committees` formed / dissolved; election only) | Three different models of committee membership |
| Member profile / bio | N | P (mobile types) / D | N (starter profile only) | — |
| Financial contribution | I/P (fees, income) | D | D/B | Same word "contribution" used for money |
| Approval-with-history pattern | `org_membership_facts` | `membership_history` (D) | **I (transitions table)** | election has the strongest implemented example |

**Terminology conflicts [F]**
- In nrna1, "contribution" means three things: volunteer effort (`contributions`), money (`incomes.event_contribution`) and forum input (`MemberContributionRecorded`).
- "Score" means a points total (nrna1), a leadership metric (public-digit) and a security risk score (election).
- "Certificate" means an election or vote confirmation, TLS, or citizenship. It never means an appreciation certificate.

**[I] Duplication and divergence.** Points/leaderboard designs were produced independently in public-digit (December 2025 to January 2026) and implemented differently in nrna1 (April 2026). Neither references the other, and nrna1 PBDIGIT-330 (November 2026) references neither.

**Architectural constraints [F]**
- nrna1's ratified multi-tenancy (`organisation_id`, `BelongsToTenant`).
- Open ownership of Appointment / Tenure / Mandate (ARB-8).
- The methodology freeze in `.claude/CLAUDE.md` (delivery focus WP-7C → WP-8 → EPIC-005).

This report does not recommend merging projects or moving code.

---

## 12. Strategic DDD assessment (discovery hypotheses, not approved contexts)

| Candidate | Existing evidence | Owner explicitly established? | Related entities | Upstream / downstream | Integration mechanisms | Missing domain rules | Cross-project duplication | Unresolved ownership |
|---|---|---|---|---|---|---|---|---|
| 1. Contribution Management | nrna1 `contributions` (P); public-digit `member_activities` (D) | **No.** nrna1 module sits outside any context; no ADR. | Contribution, PointsLedger, member_activities | Upstream: Membership (member identity), Organisation. Downstream: profile, recognition. | None (direct Eloquent) | Activity vs claim; anchor (member vs user); lifecycle states and history | Yes (nrna1 vs public-digit) | [OQ] Membership, Governance, or a new context? |
| 2. Evidence Management | public-digit `documents` (D); nrna1 `newsletter_attachments` (I, other domain) | No | documents, media_files | Consumed by verification | None | Retention, hashing, privacy of photographs of beneficiaries, who may see evidence | Design only | [OQ] Is evidence generic or per-domain? |
| 3. Recognition Management | Docs only (nrna1 guide; public-digit tiers) | No | — | Upstream: verified contributions or office tenure | None | What recognition is (decision, certificate, honorary status); who grants it; revocation | — | [OQ] |
| 4. Impact Assessment | None | No | — | — | — | All | — | [OQ] Is impact a member-level or an initiative-level concept? |
| 5. Contribution Verification | Columns (nrna1); `verification_status` (public-digit) | No | — | Upstream: claim + evidence | election registration state machine (pattern only) | Verifier authority; conflict of interest; appeal (`appealed` enum exists, no flow) | — | [OQ] Tied to Governance authority (ARB-8)? |
| 6. Member Contribution Profile | None (no biography or public profile anywhere) | No | `members`, `member_directories`, `committee_assignments` | Downstream read model of 1, 3, 7 | — | Consent and visibility (`leaderboard_visibility` exists but unused) | — | [OQ] Public versus members-only versus private |
| 7. Organisation and Committee Participation History | nrna1 `committee_assignments` (P, defective); GMAP / ODA-01 Tenure (D); PBDIGIT-330 (B) | **No: ARB-8 open** | CommitteeAssignment, Position, Appointment, Tenure | Membership ↔ Governance | Three stores | Append-only history (G-9 violated) | public-digit `role_assignments` (D); election `election_committees` | ARB-8 |

- **[I]** Candidate 7 is the only one already inside a running strategic-design process: GMAP, ODA-01, PBDIGIT-330 and ARB-8.
- **[H]** Candidates 1, 3 and 5 may be facets of one capability, or genuinely separate ones. The code cannot settle this; it needs domain validation.

---

## 13. Existing reusable capabilities (facts; no recommendation to reuse)

1. **[F]** election `RegistrationStateMachine` plus `tenant_user_registration_transitions`: an implemented submit / review / approve-or-reject flow with reasons and append-only history.
2. **[F]** nrna1 `org_membership_facts`: an implemented append-only fact history in the PublicDigit tenant model.
3. **[F]** nrna1 `committee_assignments` schema (term dates, nomination type, appointed_by), subject to the `left_date` defect.
4. **[F]** nrna1 audit and provenance infrastructure: `HasAuditFields`, outbox `provenance`, `*_audit_logs`.
5. **[F]** public-digit design artefacts: `member_activities`, polymorphic `documents` with `file_hash` and verification status, `role_assignments`. These are designs, not code.
6. **[F]** nrna1 PBDIGIT-330 constraint set and the public-digit "derived, explainable, non-transferable" principle: written domain rules about scoring.

---

## 14. Gaps and unresolved questions

**Gaps [F]**
- No implemented verification, recognition, evidence storage, outcome or impact concept in any project.
- No member biography or contribution profile.
- nrna1 contribution data is anchored to `users`, not `members`.
- nrna1 committee history is overwritten on save.
- public-digit backend is not in the checkout.

**Open questions [OQ] for the business domain expert**
1. What is the business purpose: accountability of office holders (PBDIGIT-330), recognition of volunteers, member engagement, or reporting social impact to outsiders? Each implies different concepts.
2. Is a "social contribution" performed by an individual member, by a committee, by an initiative or project, or by all three? Who are the beneficiaries?
3. Who has authority to verify a contribution and to grant recognition? Can a person verify their own committee's work?
4. Should the existing nrna1 Contribution Points module (self-reported, scored, leaderboard) be treated as an accepted product feature, an experiment, or legacy? It conflicts with PBDIGIT-330 in principle.
5. Is any ranking or numerical comparison of members acceptable to the organisation at all? public-digit and PBDIGIT-330 both caution against it.
6. What is the visibility and consent model for a member's contribution history (public directory, members only, private)?
7. Do "honorary membership" and "appreciation certificate" belong to Membership, Governance, or a recognition capability?
8. Does a separate public-digit backend repository exist that implements Membership approval (claimed "complete")? It is not in this checkout.

**Not verified in this discovery**
- Whether the nrna1 `contributions` migrations are applied in any database (no database was queried).
- The fee and renewal flows end to end.
- The public-digit backend.

---

## 15. Recommended next domain-discovery steps (discovery only, not implementation)

1. **Business-capability statement first (Operating Loop phase 1).** The Principal or PO states which business need(s) in OQ-1 are in scope. Without it, ownership cannot be resolved, and the loop says to stop and ask rather than infer.
2. **PO decision on the status of the existing nrna1 Contribution Points module (OQ-4)**, recorded as a decision, before any discovery treats it as either a foundation or legacy.
3. **Run this subject as part of, or explicitly beside, PBDIGIT-330 discovery.** Do not open a parallel one, because both concern activity, evidence, verification and history.
   **[Superseded 2026-11-07, C-10]** Principal ruling: the individual social contribution discovery (`PBDIGIT-331`) is kept separate from `PBDIGIT-330`. Shared concepts may be investigated; purposes and ownership are not merged.
4. **A ubiquitous-language workshop** separating: activity performed · contribution claimed · contribution verified · recognition granted · outcome achieved · impact demonstrated · financial contribution · forum contribution. Record the resulting glossary, then a context-map question.
5. **Resolve the dependency chain already on record** (ARB-8 Appointment / Tenure ownership; G-9 append-only history) before discovery on candidate 7 (participation history) can conclude.

No bounded context, ticket, scoring approach or code change is proposed. **Stop: awaiting the Principal's review.**

---

**Traceability.** Assignment: Principal, 2026-11-07. Evidence sources: three read-only search passes (one per project) plus direct verification of load-bearing claims in this session. Related: PBDIGIT-330 · GMAP 2026-11-02 · ODA-01 v1.1 · DR-07a · `docs/publicdigit/architecture/strategic-design-status.md`.
