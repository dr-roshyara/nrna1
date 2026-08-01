
 Here is Claude's plan:
╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌
 Election State Machine: Temporal Governance Engine — Refactoring Plan

 Type: DDD-First Architecture Refactoring (replaces Election-Only Mode plan — separate concern)
 Scope: Three structural constitutional defects in Election lifecycle governance
 Approach: TDD-First · Strangler Fig per stream · Zero Regression
 Date: 2026-05-18

 ---
 Context

 Ganesh ji's analysis identified three architectural problems in the election state machine. These are not cosmetic bugs — they are constitutional defects affecting the legality, fairness, and auditability of
  elections.

 ┌───────────────────────┬─────────────────────────────────────────────────────────────────────────────────────────┐
 │        Problem        │                                   Why Constitutional                                    │
 ├───────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────┤
 │ Timezone              │ "Voting starts at 9 AM" means different things → illegal elections                      │
 ├───────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────┤
 │ Dual truth            │ Votes can be cast outside declared voting window → democratic integrity breach          │
 ├───────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────┤
 │ No verification gates │ Elections can transition to voting without completing required setup → invalid election │
 └───────────────────────┴─────────────────────────────────────────────────────────────────────────────────────────┘

 Codebase Exploration Findings

 Problem 1 (Timezone): CONFIRMED — CRITICAL
 - Zero timezone columns exist on organisations or elections tables
 - 19+ raw now() calls in Election.php with no timezone context
 - Zero time-freezing tests exist — temporal logic is completely untestable
 - Controllers parse user dates via Carbon::createFromFormat() without timezone
 - No ElectionClockService or Clock abstraction exists anywhere

 Problem 2 (Dual Truth): CONFIRMED — WORSE THAN DESCRIBED (6 sources of truth, not 2)
 1. election.state (state machine) — only EnsureElectionState middleware uses it
 2. election.status (old enum) — ElectionVotingController + Voter::canVote() check this
 3. election.is_active (boolean) — ElectionMiddleware + VoteController fallback
 4. voting_starts_at/voting_ends_at — only in model methods, NOT vote submission path
 5. VoterSlug.is_active + expires_at — actual runtime gate for voting flow
 6. config('election.is_active') — config-file boolean in VoteEligibility middleware
 - VoteController (actual vote submission) checks NONE of: state, starts_at, ends_at, status

 Problem 3 (Verification): CONFIRMED — NO SYSTEM EXISTS
 - election_phase_verifications table does NOT exist
 - Only binary administration_completed/nomination_completed booleans
 - No ElectionPhaseVerification model
 - Business counters exist but no checkpoint policy

 ---
 Target: Temporal Governance Engine

 Five architectural concepts become first-class (not helper methods in Eloquent model):

 TEMPORAL GOVERNANCE ENGINE
 ├─ State        → lifecycle stage (TransitionMatrix) ← exists
 ├─ Time         → clock abstraction (ElectionClockService) ← NEW Stream 1
 ├─ Lifecycle    → single source of truth (ElectionLifecyclePolicy) ← NEW Stream 2
 ├─ Verification → checkpoint completion (PhaseVerificationPolicy) ← NEW Stream 3
 ├─ Authorization → who may act (TransitionMatrix roles) ← exists
 └─ Audit        → complete history (ElectionStateTransition) ← exists

 GOLDEN RULE:
   STORE: UTC
   DISPLAY: organisation timezone
   COMPUTE: ElectionClockService (context-aware clock)
   VOTE GATE: ElectionLifecyclePolicy (single source of truth)
   PHASE GATE: ElectionPhaseVerificationPolicy (checkpoint system)

 ---
 Stream 1: Temporal Abstraction (Priority 1 — Execute First)

 Goal: All temporal decisions flow through one abstraction. No raw now() in domain logic.

 Architecture

 Domain Layer (pure PHP):
   app/Domain/Election/Contracts/Clock.php           ← NEW interface
     └── currentTime(): CarbonImmutable

 Application Layer:
   app/Services/ElectionClockService.php             ← NEW
     ├── getElectionTime(Election): CarbonImmutable  // uses timezone resolver
     ├── isVotingOpen(Election): bool
     ├── hasVotingEnded(Election): bool
     └── isAdministrationWindowActive(Election): bool

 Infrastructure Layer:
   app/Infrastructure/Clock/SystemClock.php          ← NEW (production)
   app/Infrastructure/Clock/TestClock.php            ← NEW (test injection)
   app/Infrastructure/Election/ElectionTimezoneResolver.php ← NEW
     └── resolve(Election): string                   // election.tz → org.tz → UTC

 Database Migration

 File: database/migrations/2026_05_19_000001_add_timezone_to_organisations_and_elections.php

 Schema::table('organisations', fn($t) => $t->string('timezone', 50)->default('UTC')->after('country_code'));
 Schema::table('elections', fn($t) => $t->string('timezone', 50)->nullable()->after('voting_ends_at'));

 Phase 1 RED Tests (Write First — All Failing)

 tests/Unit/Domain/Election/Clock/ClockContractTest.php
 - clock_returns_carbon_immutable
 - test_clock_allows_time_freeze

 tests/Unit/Domain/Election/ElectionClockServiceTest.php (uses TestClock)
 - is_voting_open_true_within_window
 - is_voting_open_false_before_window_starts
 - is_voting_open_false_after_window_ends
 - is_voting_open_uses_election_timezone_not_server_time
 - is_voting_open_falls_back_to_organisation_timezone
 - has_voting_ended_true_after_window
 - get_election_time_returns_time_in_election_timezone

 tests/Unit/Infrastructure/ElectionTimezoneResolverTest.php
 - resolver_returns_election_timezone_when_set
 - resolver_falls_back_to_organisation_timezone
 - resolver_falls_back_to_utc_when_both_null

 Phase 1 Key Modifications

 ┌────────────────────────────────────────┬──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
 │                  File                  │                                                                              Change                                                                              │
 ├────────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ app/Models/Election.php                │ Replace $currentTime = now() (line 1558) with ElectionClockService; replace now()->lt(...) guards (lines 990, 1103, 1373, 1457, 1675, 1947, 1964) with clock     │
 │                                        │ service calls                                                                                                                                                    │
 ├────────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ app/Providers/AppServiceProvider.php   │ Bind Clock::class → SystemClock::class                                                                                                                           │
 ├────────────────────────────────────────┼──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ database/factories/ElectionFactory.php │ Add withTimezone(string $tz) factory state                                                                                                                       │
 └────────────────────────────────────────┴──────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

 Phase 1 New Files

 app/Domain/Election/Contracts/Clock.php
 app/Infrastructure/Clock/SystemClock.php
 app/Infrastructure/Clock/TestClock.php
 app/Infrastructure/Election/ElectionTimezoneResolver.php
 app/Services/ElectionClockService.php
 database/migrations/2026_05_19_000001_add_timezone_to_organisations_and_elections.php
 tests/Unit/Domain/Election/Clock/ClockContractTest.php
 tests/Unit/Domain/Election/ElectionClockServiceTest.php
 tests/Unit/Infrastructure/ElectionTimezoneResolverTest.php

 ---
 Stream 2: Lifecycle Policy — Single Source of Truth (Priority 2)

 Goal: Replace 6 independent voting eligibility checks with one policy consumed by all subsystems.

 Architecture

 Domain Layer:
   app/Domain/Election/Policies/ElectionLifecyclePolicy.php   ← NEW
     ├── canAcceptVotes(Election): bool       // consults state + clock
     ├── canPublishResults(Election): bool
     ├── canModifyCandidates(Election): bool
     └── canImportVoters(Election): bool

 Before vs After

 BEFORE (6 sources):
   VoteController              → Code.can_vote_now flag
   ElectionVotingController    → election.status === 'active' + start/end dates (inline)
   EnsureElectionState         → state machine action check
   VoteEligibility middleware  → config('election.is_active')
   Voter::canVote()            → election.status === 'active'
   ValidateVoterSlugWindow     → election.end_date vs now()

 AFTER (1 source):
   All of the above            → ElectionLifecyclePolicy::canAcceptVotes($election)

 Phase 2 RED Tests (Write First — All Failing)

 tests/Unit/Domain/Election/ElectionLifecyclePolicyTest.php
 - can_accept_votes_true_when_voting_state_and_window_active
 - can_accept_votes_false_in_administration_state
 - can_accept_votes_false_in_results_pending_state
 - can_accept_votes_false_before_voting_window_starts
 - can_accept_votes_false_after_voting_window_ends
 - can_publish_results_true_only_in_results_pending_state
 - can_import_voters_true_only_in_administration_state
 - policy_uses_clock_service_not_raw_now

 tests/Feature/Election/VoteSubmissionLifecyclePolicyTest.php (integration)
 - vote_rejected_outside_voting_window
 - vote_accepted_within_voting_window
 - vote_rejected_in_results_pending_state

 Phase 2 Key Modifications

 ┌───────────────────────────────────────────────────┬────────────────────────────────────────────────────────────────────────────────┐
 │                       File                        │                                     Change                                     │
 ├───────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────┤
 │ app/Http/Controllers/VoteController.php           │ Replace Code.can_vote_now check with ElectionLifecyclePolicy::canAcceptVotes() │
 ├───────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────┤
 │ app/Http/Controllers/ElectionVotingController.php │ Replace inline $canVote (lines 48-52) with policy                              │
 ├───────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────┤
 │ app/Http/Middleware/VoteEligibility.php           │ Remove config('election.is_active') fallback                                   │
 ├───────────────────────────────────────────────────┼────────────────────────────────────────────────────────────────────────────────┤
 │ app/Models/Voter.php                              │ canVote() delegates to policy                                                  │
 └───────────────────────────────────────────────────┴────────────────────────────────────────────────────────────────────────────────┘

 Phase 2 New Files

 app/Domain/Election/Policies/ElectionLifecyclePolicy.php
 app/Http/Middleware/EnsureVotingAllowed.php
 tests/Unit/Domain/Election/ElectionLifecyclePolicyTest.php
 tests/Feature/Election/VoteSubmissionLifecyclePolicyTest.php

 ---
 Stream 3: Phase Verification System (Priority 3)

 Goal: Replace binary administration_completed boolean with normalized workflow checkpoints. Transitions blocked until checkpoints pass.

 Architecture

 Database:
   election_phase_verifications table              ← NEW
   ├── id, election_id
   ├── phase (administration | nomination | voting)
   ├── verification_type (posts_verified | voters_verified |
   │                      committee_verified | candidates_verified | voting_lock_confirmed)
   ├── verified_by (uuid, nullable — null = system auto-verified)
   ├── verified_at (timestamp)
   └── metadata (json, nullable)

 Domain:
   app/Models/ElectionPhaseVerification.php        ← NEW
   app/Domain/Election/Policies/ElectionPhaseVerificationPolicy.php ← NEW
     ├── isAdministrationComplete(Election): bool
     ├── isNominationReady(Election): bool
     └── isReadyToOpenVoting(Election): bool
   app/Domain/Election/ValueObjects/CheckpointRequirement.php ← NEW

 Required Checkpoints

 ┌─────────────────────────────┬───────────────────────────────────────────────────────────────┐
 │         Transition          │                     Required Checkpoints                      │
 ├─────────────────────────────┼───────────────────────────────────────────────────────────────┤
 │ administration → nomination │ posts_verified + voters_verified + committee_verified         │
 ├─────────────────────────────┼───────────────────────────────────────────────────────────────┤
 │ nomination → voting         │ candidates_verified (no pending apps + ≥1 approved candidate) │
 └─────────────────────────────┴───────────────────────────────────────────────────────────────┘

 Phase 3 RED Tests (Write First — All Failing)

 tests/Unit/Domain/Election/ElectionPhaseVerificationPolicyTest.php
 - is_administration_complete_true_when_all_three_verified
 - is_administration_complete_false_when_committee_missing
 - is_nomination_ready_false_when_pending_applications_exist
 - is_nomination_ready_true_when_candidates_verified

 tests/Feature/Contexts/Elections/ElectionPhaseVerificationTest.php
 - transition_to_nomination_blocked_until_all_checkpoints_pass
 - transition_to_nomination_allowed_when_all_checkpoints_pass
 - verification_record_written_with_actor_and_timestamp
 - open_voting_blocked_without_candidates_verified_checkpoint
 - multiple_verifications_do_not_duplicate

 Phase 3 Migration

 File: database/migrations/2026_05_20_000001_create_election_phase_verifications_table.php

 Schema::create('election_phase_verifications', function (Blueprint $table) {
     $table->uuid('id')->primary();
     $table->uuid('election_id');
     $table->string('phase', 50);
     $table->string('verification_type', 80);
     $table->uuid('verified_by')->nullable();
     $table->timestamp('verified_at');
     $table->json('metadata')->nullable();
     $table->timestamps();
     $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
     $table->unique(['election_id', 'phase', 'verification_type']); // one per type
     $table->index(['election_id', 'phase']);
 });

 Phase 3 Key Modifications

 ┌───────────────────────────────────────────────────────┬─────────────────────────────────────────────────────────────────────────────────────────────────┐
 │                         File                          │                                             Change                                              │
 ├───────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ app/Models/Election.php                               │ validateCompleteAdministration() consults policy; validateOpenVoting() adds isNominationReady() │
 ├───────────────────────────────────────────────────────┼─────────────────────────────────────────────────────────────────────────────────────────────────┤
 │ app/Domain/Election/StateMachine/TransitionMatrix.php │ Add checkpoint policy validation layer (or delegate to guard methods)                           │
 └───────────────────────────────────────────────────────┴─────────────────────────────────────────────────────────────────────────────────────────────────┘

 Phase 3 New Files

 database/migrations/2026_05_20_000001_create_election_phase_verifications_table.php
 app/Models/ElectionPhaseVerification.php
 app/Domain/Election/Policies/ElectionPhaseVerificationPolicy.php
 app/Domain/Election/ValueObjects/CheckpointRequirement.php
 tests/Unit/Domain/Election/ElectionPhaseVerificationPolicyTest.php
 tests/Feature/Contexts/Elections/ElectionPhaseVerificationTest.php

 ---
 TDD Execution Per Stream

 RED  → Write contract tests (all failing)
 GREEN → Minimum production code to pass
 REFACTOR → Clean up + zero-regression check

 Zero-Regression Baseline (Run Before Every Stream)

 php artisan test \
   tests/Feature/ElectionStateMachineTest.php \
   tests/Feature/Election/VotingButtonsStateMachineTest.php \
   tests/Unit/Domain/Election/ \
   --no-coverage
 # All 50 tests must remain GREEN throughout

 Test Commands Per Stream

 # Stream 1
 php artisan test tests/Unit/Domain/Election/Clock/ tests/Unit/Domain/Election/ElectionClockServiceTest.php tests/Unit/Infrastructure/ElectionTimezoneResolverTest.php

 # Stream 2
 php artisan test tests/Unit/Domain/Election/ElectionLifecyclePolicyTest.php tests/Feature/Election/VoteSubmissionLifecyclePolicyTest.php

 # Stream 3
 php artisan test tests/Unit/Domain/Election/ElectionPhaseVerificationPolicyTest.php tests/Feature/Contexts/Elections/ElectionPhaseVerificationTest.php

 # Full regression check after each stream
 php artisan test --no-coverage

 ---
 Definition of Done

 Stream 1

 - Clock + ElectionClockService + Resolver — all tests GREEN
 - Zero raw now() calls in app/Domain/Election/ layer
 - Election::transitionTo() uses ElectionClockService not now()
 - All 50 existing state machine tests GREEN

 Stream 2

 - ElectionLifecyclePolicy tests GREEN
 - VoteController uses policy not code flag
 - ElectionVotingController uses policy not inline check
 - VoteEligibility middleware uses policy not config flag
 - All 50 existing state machine tests GREEN

 Stream 3

 - PhaseVerification policy tests GREEN
 - Migration runs cleanly
 - complete_administration transition blocked without all 3 checkpoints
 - open_voting transition blocked without candidates_verified checkpoint
 - Full test suite GREEN (zero regressions)

 ---
 Anti-Patterns Explicitly Avoided

 ┌────────────────────────────────────────────┬───────────────────────────────────────────────┐
 │                Anti-Pattern                │                   Solution                    │
 ├────────────────────────────────────────────┼───────────────────────────────────────────────┤
 │ Scattered now()->tz(...) in domain         │ ElectionClockService abstraction              │
 ├────────────────────────────────────────────┼───────────────────────────────────────────────┤
 │ Nullable timestamp explosion (20+ columns) │ Normalized election_phase_verifications table │
 ├────────────────────────────────────────────┼───────────────────────────────────────────────┤
 │ Config-file boolean for live voting        │ ElectionLifecyclePolicy                       │
 ├────────────────────────────────────────────┼───────────────────────────────────────────────┤
 │ election.status parallel to election.state │ Policy consults state only                    │
 ├────────────────────────────────────────────┼───────────────────────────────────────────────┤
 │ Removing Code.can_vote_now blindly         │ Deprecate, then remove after policy stable    │
 ├────────────────────────────────────────────┼───────────────────────────────────────────────┤
 │ Big-bang refactor (all at once)            │ Three independent streams, each shippable     │
 └────────────────────────────────────────────┴───────────────────────────────────────────────┘

 ---
 Context

 Six structural defects identified by DDD Architecture Verification Report (2026-05-18). The original big-bang rewrite plan scored 85/100 and was rated "architecturally correct but operationally too
 aggressive for a running monolith" by two independent senior architecture reviews.

 Key concern from both reviews:

 ▎ During migration, both old model logic AND new handler/policy logic will coexist. Without strangler control, this creates dual-source-of-truth, behavioral drift, and regression risk in a live election
 ▎ system.

 Decision: Strangler Fig, not Big Bang.

 ---
 Root Cause Summary (From DDD Verification Report)

 ┌─────┬───────────────────────────────────────────────────────────────────────────────┬──────────┐
 │  #  │                                    Defect                                     │ Severity │
 ├─────┼───────────────────────────────────────────────────────────────────────────────┼──────────┤
 │ 1   │ Composite DB FK to user_organisation_roles blocks election-only mode entirely │ CRITICAL │
 ├─────┼───────────────────────────────────────────────────────────────────────────────┼──────────┤
 │ 2   │ Three eligibility code paths give inconsistent results for same user          │ CRITICAL │
 ├─────┼───────────────────────────────────────────────────────────────────────────────┼──────────┤
 │ 3   │ No tenancy validation on write (cross-tenant voter assignment possible)       │ CRITICAL │
 ├─────┼───────────────────────────────────────────────────────────────────────────────┼──────────┤
 │ 4   │ No SoftDeletes — re-import of removed voter hits unique constraint            │ HIGH     │
 ├─────┼───────────────────────────────────────────────────────────────────────────────┼──────────┤
 │ 5   │ Cache keys missing organisation_id prefix                                     │ HIGH     │
 ├─────┼───────────────────────────────────────────────────────────────────────────────┼──────────┤
 │ 6   │ Audit logging missing on assignment/approval/suspension                       │ MEDIUM   │
 └─────┴───────────────────────────────────────────────────────────────────────────────┴──────────┘

 ---
 Strangler Migration Strategy

 Phase A — FREEZE    │ Contract tests lock current behavior. Zero production change.
                     │ Introduce ElectionMode enum + Policy interface.
                     ▼
 Phase B — PARALLEL  │ Policy layer runs alongside existing model logic.
                     │ All eligibility reads route through policy (adapter pattern).
                     │ Model methods remain — they delegate to policy internally.
                     ▼
 Phase C — STRANGLE  │ Gradual extraction: eligibility → assignment → bulk.
                     │ Model logic deprecated and removed incrementally.
                     │ FK drop only after full handler coverage + stability window.

 Anti-dual-write rule: At any point in time, exactly one path governs any given write operation. We move ownership cleanly — never let both paths run simultaneously.

 ---
 Fourth Architecture Review Amendments (A- → A)

 Review grade: A- → estimated A after amendments.
 Source: Senior architect post-plan analysis.

 P0 Amendments (Must apply before Phase C execution)

 P0.1 — VoterIntegrityGuardService (before FK removal)
 Dropping the DB FK to user_organisation_roles removes database-enforced integrity. Any bug in VoterQualificationPolicy, any missing test coverage, or future developer bypass creates a silent data corruption
 risk. An application-level guard must exist before the FK drop executes.

 New file: app/Services/VoterIntegrityGuardService.php
 final class VoterIntegrityGuardService
 {
     /**
      * Validates that a user-organisation pair is valid at the persistence layer.
      * Called from EloquentVoterRepository::create() and restoreAndUpdate()
      * BEFORE any write. Replaces the DB FK that will be dropped in C.1.
      */
     public function assertValid(string $userId, string $organisationId, ElectionMode $mode): void
     {
         if ($mode->isElectionOnly()) {
             $exists = DB::table('organisation_users')
                 ->where('user_id', $userId)
                 ->where('organisation_id', $organisationId)
                 ->where('status', 'active')
                 ->whereNull('deleted_at')
                 ->exists();
         } else {
             $exists = DB::table('user_organisation_roles')
                 ->where('user_id', $userId)
                 ->where('organisation_id', $organisationId)
                 ->exists();
         }

         if (! $exists) {
             throw new \RuntimeException(
                 "VoterIntegrityGuard: user [{$userId}] is not a valid member of organisation [{$organisationId}] in mode [{$mode->value}]"
             );
         }
     }
 }

 Injected into EloquentVoterRepository. Called inside every write transaction. This is the application-level replacement for the dropped DB FK.

 ---
 P0.2 — Rename Policy to EligibilityQueryService
 The "VoterQualificationPolicy" is actually a query orchestration layer (reads organisation_users, members, fees, expiry logic). Naming it "Policy" will cause it to slowly accumulate rule-enforcement logic
 and become a God class. Rename to VoterEligibilityQueryService to reflect its true role.

 - VoterQualificationPolicyInterface → VoterEligibilityQueryServiceInterface
 - EloquentVoterQualificationPolicy → EloquentVoterEligibilityQueryService
 - Method qualifies() → isEligible() (matches existing VoterEligibilityService naming)
 - Method qualifyingSubset() → eligibleSubset()

 All references in handlers, service, and controller updated accordingly.

 ---
 P0.3 — Move Cache Invalidation OUT of Model
 The model's booted() $invalidate closure means the model has knowledge of cache infrastructure. In a CQRS-lite system with handlers owning writes, the model should be pure persistence. Handlers are the only
 callers of ElectionCacheService::forgetVoterKeys().

 Procedure:
 1. Remove $invalidate closure from ElectionMembership::booted()
 2. Remove static::saved($invalidate) and static::deleted($invalidate) calls
 3. Add ElectionCacheService::forgetVoterKeys(...) to:
   - AssignVoterHandler (after commit)
   - BulkAssignVotersHandler (after all chunks)
   - ElectionVoterController::approve(), suspend(), confirmSuspension(), destroy() (interim, until these also get handlers)

 Note: During Phase B (before handlers exist), keep the model invalidation temporarily as a safety net. Remove it at the same time as controller actions are wired to handlers in Phase C.

 ---
 P1 Amendments (Strongly recommended)

 P1.1 — ElectionContext Value Object
 ElectionMode::fromOrganisation($org) assumes mode is org-level and stable. If future elections allow mode overrides, this breaks. Introduce a value object that carries all relevant context:

 New file: app/Contexts/Elections/Domain/ElectionContext.php
 final class ElectionContext
 {
     public function __construct(
         public readonly ElectionMode $mode,
         public readonly string $organisationId,
         public readonly string $electionId,
     ) {}

     public static function fromElection(Election $election): self
     {
         return new self(
             mode:           ElectionMode::fromOrganisation($election->organisation),
             organisationId: $election->organisation_id,
             electionId:     $election->id,
         );
     }
 }

 Commands use ElectionContext instead of separate ElectionMode + organisationId parameters. This is a refactor within Phase C.2 — update AssignVoterCommand and BulkAssignVotersCommand to accept
 ElectionContext instead of ElectionMode $mode + string $organisationId.

 ---
 P1.2 — ElectionApplicationService (Orchestration Boundary)
 The controller currently builds commands and calls handlers directly. An Application Service layer prevents controllers from becoming orchestration logic over time.

 New file: app/Contexts/Elections/Application/ElectionVoterApplicationService.php
 final class ElectionVoterApplicationService
 {
     public function __construct(
         private readonly AssignVoterHandler      $assignHandler,
         private readonly BulkAssignVotersHandler $bulkHandler,
     ) {}

     public function assignVoter(Election $election, string $userId, string $assignedBy): ElectionMembership
     {
         return $this->assignHandler->handle(new AssignVoterCommand(
             userId:     $userId,
             context:    ElectionContext::fromElection($election),
             assignedBy: $assignedBy,
         ));
     }

     public function bulkAssignVoters(Election $election, array $userIds, string $assignedBy, ?string $idempotencyKey = null): array
     {
         return $this->bulkHandler->handle(new BulkAssignVotersCommand(
             userIds:        $userIds,
             context:        ElectionContext::fromElection($election),
             assignedBy:     $assignedBy,
             idempotencyKey: $idempotencyKey,
         ));
     }
 }

 Controller injects ElectionVoterApplicationService and delegates to it. Controller becomes thin: validate request → build context → call service → return response.

 ---
 P1.3 — Row-Level Pre-Validation for Bulk DLQ Granularity
 The current bulk handler sends entire chunks to the DLQ on failure. A single bad record in a chunk of 500 loses all 500 rows. Pre-validate rows BEFORE the transaction so only truly unwritable rows go to DLQ.

 Updated bulk handler Phase 2+:
 Phase 2b — Per-row guard check (BEFORE chunking):
   $guardsValid = [];
   $guardsInvalid = [];
   foreach ($newIds as $userId):
       try:
           $guard->assertValid($userId, $cmd->context->organisationId, $cmd->context->mode)
           $guardsValid[] = $userId
       catch RuntimeException:
           $guardsInvalid[] = $userId
           DeadLetterEntry::create([
               'queue_name' => 'voter_bulk_assign_integrity',
               'payload'    => json_encode(['user_id' => $userId]),
               'error_message' => $e->getMessage(),
               ...
           ])

 Phase 3 — Chunked inserts of $guardsValid only
 // No try/catch needed around the insert — only integrity-verified rows enter

 This changes DLQ granularity from "per chunk" to "per row". Integrity failures logged individually; DB transactions only contain clean data.

 ---
 Phase A: Freeze & Foundation (~4 hours)

 Goal: Lock current behavior. Build foundation without touching production logic.

 A.0 — Baseline Snapshot

 php artisan test 2>&1 | tail -5
 Record pass/fail count. This is the regression baseline.

 ---
 A.1 — Contract Tests (Write FIRST — all RED)

 File: tests/Unit/Contracts/ElectionMembershipContractTest.php

 These tests lock the CURRENT behavior of the model before we touch anything. If any existing test breaks during later stages, these catch it.

 Tests:
 - test_assign_voter_creates_election_membership_with_correct_fields
 - test_assign_voter_throws_when_user_not_in_user_organisation_roles (documents current behavior)
 - test_assign_voter_reactivates_inactive_membership
 - test_assign_voter_throws_on_duplicate_active_voter
 - test_bulk_assign_voters_returns_correct_counts
 - test_bulk_assign_voters_skips_non_members_in_full_membership_mode
 - test_scope_eligible_excludes_expired_memberships
 - test_scope_eligible_excludes_inactive_memberships
 - test_voter_count_cache_is_invalidated_on_save
 - test_voter_count_cache_is_invalidated_on_delete

 ▎ These tests document the CURRENT (buggy) behavior too. They are NOT the target behavior — they are the behavioral snapshot before we change anything.

 ---
 A.2 — ElectionMode Enum (Write RED tests first)

 Test file: tests/Unit/Domain/Election/ElectionModeTest.php

 Tests:
 - test_full_membership_org_returns_full_membership_mode
 - test_election_only_org_returns_election_only_mode
 - test_is_election_only_returns_correct_value
 - test_label_returns_human_readable_string

 Production file: app/Domain/Election/Enum/ElectionMode.php

 <?php
 namespace App\Domain\Election\Enum;

 use App\Models\Organisation;

 enum ElectionMode: string
 {
     case FullMembership = 'full_membership';
     case ElectionOnly   = 'election_only';

     public static function fromOrganisation(Organisation $org): self
     {
         return $org->uses_full_membership
             ? self::FullMembership
             : self::ElectionOnly;
     }

     public function isElectionOnly(): bool  { return $this === self::ElectionOnly; }
     public function isFullMembership(): bool { return $this === self::FullMembership; }

     public function label(): string
     {
         return match($this) {
             self::ElectionOnly   => 'Election-Only',
             self::FullMembership => 'Full Membership',
         };
     }
 }

 Pattern: follows ElectionState.php, ElectionRole.php in same directory.

 ---
 A.3 — Domain Interfaces Only (Write RED contract tests first)

 No implementation in Phase A. Interfaces only — pure PHP, no Eloquent, no DB.

 Test file: tests/Unit/Contracts/VoterQualificationPolicyContractTest.php
 Uses hand-written test doubles. Verifies interface shape only.

 Files to create:

 app/Contexts/Elections/Domain/Policies/VoterQualificationPolicyInterface.php
 interface VoterQualificationPolicyInterface
 {
     /**
      * Single eligibility check. Read-only. No side effects.
      * - ElectionOnly: checks organisation_users (status=active, not deleted)
      * - FullMembership: checks members + fees + voting rights
      */
     public function qualifies(
         string $userId,
         string $organisationId,
         ElectionMode $mode
     ): bool;

     /**
      * Bulk eligibility filter — returns only qualifying user IDs.
      * Single DB query per mode. No N+1.
      */
     public function qualifyingSubset(
         array $userIds,
         string $organisationId,
         ElectionMode $mode
     ): array;
 }

 app/Contexts/Elections/Domain/Exceptions/VoterNotEligibleException.php — extends \DomainException
 app/Contexts/Elections/Domain/Exceptions/DuplicateVoterException.php — extends \DomainException

 End of Phase A: Run full test suite. All previously passing tests still green. New A.1 contract tests green (they document current behavior). A.3 interface tests green (no implementation required for
 interface-shape tests).

 ---
 Phase B: Parallel Policy Layer (~4 hours)

 Goal: Policy runs alongside model. Eligibility reads route through policy. Model writes unchanged.

 Anti-dual-write rule for Phase B: Policy governs reads/decisions only. Model governs all writes. No overlap.

 ---
 B.1 — EloquentVoterQualificationPolicy (Write RED tests first)

 Test file: tests/Feature/Contexts/Elections/EloquentVoterQualificationPolicyTest.php

 Tests (all use RefreshDatabase):
 - test_election_only_user_qualifies_when_active_in_organisation_users
 - test_election_only_user_does_not_qualify_when_not_in_organisation_users
 - test_election_only_user_does_not_qualify_when_status_inactive
 - test_election_only_user_does_not_qualify_when_soft_deleted_from_org_users
 - test_full_membership_user_qualifies_with_active_member_and_paid_fees
 - test_full_membership_user_does_not_qualify_without_member_record
 - test_full_membership_user_does_not_qualify_with_unpaid_fees
 - test_qualifying_subset_returns_only_eligible_user_ids
 - test_qualifying_subset_empty_array_returns_empty

 Production file: app/Contexts/Elections/Infrastructure/Policies/EloquentVoterQualificationPolicy.php

 // Election-only: organisation_users table ONLY
 private function checkElectionOnly(array $userIds, string $orgId): array
 {
     return DB::table('organisation_users')
         ->whereIn('user_id', $userIds)
         ->where('organisation_id', $orgId)
         ->where('status', 'active')
         ->whereNull('deleted_at')
         ->distinct()
         ->pluck('user_id')
         ->toArray();
 }

 // Full membership: members + fees + voting rights (unchanged from existing logic)
 private function checkFullMembership(array $userIds, string $orgId): array
 {
     return DB::table('members')
         ->join('organisation_users', 'members.organisation_user_id', '=', 'organisation_users.id')
         ->leftJoin('membership_types', 'members.membership_type_id', '=', 'membership_types.id')
         ->whereIn('organisation_users.user_id', $userIds)
         ->where('members.organisation_id', $orgId)
         ->where('members.status', 'active')
         ->whereIn('members.fees_status', ['paid', 'exempt'])
         ->where(fn ($q) => $q->whereNull('members.membership_type_id')
                              ->orWhere('membership_types.grants_voting_rights', true))
         ->where(fn ($q) => $q->whereNull('members.membership_expires_at')
                              ->orWhere('members.membership_expires_at', '>', now()))
         ->whereNull('members.deleted_at')
         ->distinct()
         ->pluck('organisation_users.user_id')
         ->toArray();
 }

 Register in AppServiceProvider::register():
 $this->app->bind(
     VoterQualificationPolicyInterface::class,
     EloquentVoterQualificationPolicy::class
 );

 ---
 B.2 — Route Eligibility Reads Through Policy (Adapter Pattern)

 Modify VoterEligibilityService — inject policy, delegate (no logic duplication):

 final class VoterEligibilityService
 {
     public function __construct(
         private readonly VoterQualificationPolicyInterface $policy
     ) {}

     public function isEligibleVoter(Organisation $org, User $user): bool
     {
         return $this->policy->qualifies(
             $user->id,
             $org->id,
             ElectionMode::fromOrganisation($org)
         );
     }

     public function unassignedEligibleQuery(Organisation $org, array $excludeUserIds = []): Builder
     {
         $mode        = ElectionMode::fromOrganisation($org);
         $allOrgUsers = DB::table('organisation_users')
             ->where('organisation_id', $org->id)
             ->pluck('user_id')
             ->toArray();

         $eligibleIds = $this->policy->qualifyingSubset($allOrgUsers, $org->id, $mode);
         $finalIds    = array_diff($eligibleIds, $excludeUserIds);

         return DB::table('users')
             ->whereIn('id', $finalIds)
             ->select('users.id', 'users.name', 'users.email')
             ->orderBy('users.name');
     }
 }

 Regression tests to run after B.2:
 php artisan test tests/Feature/Election/ElectionOnlyModeTest.php
 php artisan test tests/Feature/Election/VoterEligibilityTest.php

 ElectionOnlyModeTest eligibility tests should now go GREEN (policy checks organisation_users). Assignment tests remain RED until Phase C.

 ---
 B.3 — Cross-Tenant Guard (Controller Level)

 No handler yet. Just add the abort_if guard to the controller.

 Modify ElectionVoterController — add to EVERY action after election lookup:
 $election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
 abort_if($election->organisation_id !== $organisation->id, 404);  // cross-tenant guard

 Actions to update: index, store, bulkStore, destroy, approve, suspend, export, proposeSuspension, confirmSuspension, cancelProposal (10 actions).

 Test file: tests/Feature/Election/CrossTenantElectionAccessTest.php

 Tests:
 - test_voter_index_returns_404_for_election_in_different_organisation
 - test_voter_store_returns_404_for_election_in_different_organisation
 - test_voter_destroy_returns_404_for_election_in_different_organisation

 End of Phase B: Run full test suite. Phase A+B tests all green. Assignment tests still use model methods (no regression).

 ---
 Phase C: Strangler Migration (~7 hours)

 Goal: Gradually extract write logic out of model. Model logic deprecated stage by stage. FK dropped only at the very end, after full handler coverage and a stability window.

 Anti-dual-write rule for Phase C: Each stage moves one write path. As soon as a handler takes ownership of a write, the model method is deprecated (doc block only — not deleted yet). Deletion comes in Phase
 C.5 after full coverage.

 ---
 C.1 — Critical DB Migration (Prerequisite for Correct Election-Only Behavior)

 Write first:

 Test file: tests/Feature/Contexts/Elections/ElectionMembershipsMigrationTest.php

 Tests:
 - test_election_memberships_table_has_deleted_at_column
 - test_partial_unique_index_allows_same_user_election_when_first_is_soft_deleted
 - test_voter_can_be_assigned_with_only_organisation_user_record_no_user_org_role ← THE ROOT BUG

 This third test is the critical regression guard. It creates a user with OrganisationUser only (no UserOrganisationRole), then asserts assignment succeeds. Currently fails because of FK constraint.

 Migration: database/migrations/2026_05_19_000001_harden_election_memberships_for_election_only_mode.php

 public function up(): void
 {
     Schema::table('election_memberships', function (Blueprint $table) {
         // Step 1: Add soft deletes
         $table->softDeletes();

         // Step 2: Drop composite FK to user_organisation_roles
         // This FK blocks election-only mode: users only have OrganisationUser records,
         // not UserOrganisationRole records. Application-level policy takes over this check.
         $table->dropForeign(['user_id', 'organisation_id']);

         // Step 3: Drop hard unique constraint — replaced with partial index
         $table->dropUnique('unique_user_election');
     });

     // Step 4: Partial unique index (PostgreSQL 18.1 confirmed — WHERE clause supported)
     // Allows same (user_id, election_id) pair when previous row is soft-deleted
     DB::statement('
         CREATE UNIQUE INDEX uq_user_election_active
         ON election_memberships (user_id, election_id)
         WHERE deleted_at IS NULL
     ');
 }

 Note on FK drop: This removes DB-level referential integrity for user_organisation_roles. Application-level responsibility transfers to VoterQualificationPolicy (which validates organisation_users for
 election-only, members for full membership). The FK to elections table (election_id, organisation_id) is intentionally kept.

 Update model: Add SoftDeletes trait + wire restored event in booted():
 use HasFactory, HasUuids, BelongsToTenant, SoftDeletes;

 // In booted():
 static::restored($invalidate);  // ADD THIS

 ---
 C.2 — VoterRepository + AssignVoterHandler (Single Assignment)

 Write first:

 tests/Feature/Contexts/Elections/EloquentVoterRepositoryTest.php:
 - test_find_with_trashed_finds_soft_deleted_membership
 - test_create_inserts_new_membership
 - test_restore_and_update_undeletes_and_updates_row
 - test_existing_voter_ids_excludes_soft_deleted_rows

 tests/Unit/Contexts/Elections/AssignVoterHandlerTest.php (mocked policy + repo):
 - test_throws_voter_not_eligible_when_policy_rejects
 - test_restores_soft_deleted_membership_instead_of_inserting_duplicate
 - test_throws_duplicate_voter_exception_when_already_active
 - test_reactivates_inactive_membership
 - test_creates_new_membership_for_new_voter
 - test_throws_when_election_org_id_does_not_match_command_org_id
 - test_voter_assigned_domain_event_is_dispatched_on_success

 Production files:

 app/Contexts/Elections/Domain/Repositories/VoterRepositoryInterface.php:
 interface VoterRepositoryInterface
 {
     public function findWithTrashed(string $userId, string $electionId): ?ElectionMembership;
     public function create(array $attributes): ElectionMembership;
     public function restoreAndUpdate(ElectionMembership $membership, array $attributes): ElectionMembership;
     public function bulkInsert(array $rows): void;
     public function existingVoterIds(string $electionId): array;
 }

 app/Contexts/Elections/Infrastructure/Repositories/EloquentVoterRepository.php:
 public function findWithTrashed(string $userId, string $electionId): ?ElectionMembership
 {
     return ElectionMembership::withoutGlobalScopes()
         ->withTrashed()
         ->where('user_id', $userId)
         ->where('election_id', $electionId)
         ->lockForUpdate()
         ->first();
 }

 app/Contexts/Elections/Application/Commands/AssignVoterCommand.php:
 final readonly class AssignVoterCommand
 {
     public function __construct(
         public string  $userId,
         public string  $electionId,
         public string  $organisationId,   // explicit tenancy — NOT from TenantContext
         public ElectionMode $mode,
         public ?string $assignedBy = null,
         public array   $metadata   = [],
     ) {}
 }

 app/Contexts/Elections/Application/Handlers/AssignVoterHandler.php — write path:
 1. policy->qualifies($cmd->userId, $cmd->organisationId, $cmd->mode) → throws VoterNotEligibleException if false
 2. DB::transaction(retries: 3):
    a. repo->findWithTrashed($userId, $electionId)  [lockForUpdate]
    b. If trashed: repo->restoreAndUpdate(...)  — no unique constraint collision
    c. If active: throw DuplicateVoterException
    d. If inactive/removed: repo->restoreAndUpdate(...)
    e. Otherwise: repo->create(...)
 3. event(new VoterAssignedToElection(...))   — OUTSIDE transaction, after commit
 4. Log::channel('voter_audit')->info(...)

 app/Domain/Election/Events/VoterAssignedToElection.php:
 final class VoterAssignedToElection
 {
     public function __construct(
         public readonly string $userId,
         public readonly string $electionId,
         public readonly string $organisationId,
         public readonly ?string $assignedBy,
         public readonly \DateTimeImmutable $occurredAt,
     ) {}
 }

 Wire controller store() to handler:
 // ElectionVoterController::store()
 $handler = app(AssignVoterHandler::class);
 try {
     $handler->handle(new AssignVoterCommand(
         userId:         $request->user_id,
         electionId:     $election->id,
         organisationId: $organisation->id,
         mode:           ElectionMode::fromOrganisation($organisation),
         assignedBy:     auth()->id(),
     ));
 } catch (VoterNotEligibleException | DuplicateVoterException $e) {
     return back()->withErrors(['user_id' => $e->getMessage()]);
 }

 Deprecate (not delete) ElectionMembership::assignVoter():
 /** @deprecated Use AssignVoterHandler. Retained for transition period. */
 public static function assignVoter(...): self { ... }

 ---
 C.3 — BulkAssignVotersHandler

 Write first:

 tests/Unit/Contexts/Elections/BulkAssignVotersHandlerTest.php:
 - test_filters_ineligible_users_via_single_policy_query
 - test_excludes_already_assigned_users
 - test_returns_correct_success_existing_invalid_failed_counts
 - test_chunks_large_input_into_batches_of_500
 - test_each_chunk_is_its_own_transaction
 - test_failed_chunk_is_written_to_dead_letter_queue
 - test_remaining_chunks_continue_after_one_fails

 tests/Feature/Contexts/Elections/BulkAssignVotersIntegrationTest.php:
 - test_1000_voter_bulk_assign_completes_within_acceptable_time
 - test_duplicate_bulk_import_with_idempotency_key_returns_cached_result

 Production files:

 app/Contexts/Elections/Application/Commands/BulkAssignVotersCommand.php:
 final readonly class BulkAssignVotersCommand
 {
     public function __construct(
         public array $userIds,
         public string $electionId,
         public string $organisationId,
         public ElectionMode $mode,
         public ?string $assignedBy = null,
         public int $chunkSize = 500,         // overridable in tests
         public ?string $idempotencyKey = null,
     ) {}
 }

 app/Contexts/Elections/Application/Handlers/BulkAssignVotersHandler.php:
 Phase 1 — Idempotency check (before ANY queries):
   if ($cmd->idempotencyKey):
       $cached = Cache::get("bulk_assign.{$cmd->organisationId}.{$cmd->idempotencyKey}")
       if $cached: return $cached  [early exit]

 Phase 2 — Pre-validation (single queries each, no writes):
   $validIds    = policy->qualifyingSubset($cmd->userIds, $cmd->organisationId, $cmd->mode)
   $existingIds = repo->existingVoterIds($cmd->electionId)
   $newIds      = array_diff($validIds, $existingIds)

 Phase 3 — Chunked inserts:
   foreach array_chunk($newIds, $cmd->chunkSize) as $chunk:
       try:
           DB::transaction(fn() => repo->bulkInsert(buildRows($chunk)))
           $successCount += count($chunk)
       catch Exception $e:
           Log::channel('voter_failures')->error(...)
           DeadLetterEntry::create([...])  // see Amendment 5
           $failedCount += count($chunk)

 Phase 4 — Cache invalidation (dual-key):
   ElectionCacheService::forgetVoterKeys($cmd->organisationId, $cmd->electionId)

 Phase 5 — Domain event + audit:
   event(new BulkVotersAssignedToElection(...))
   Log::channel('voter_audit')->info(...)

 Phase 6 — Idempotency cache result:
   if ($cmd->idempotencyKey):
       Cache::put("bulk_assign.{$cmd->organisationId}.{$cmd->idempotencyKey}", $result, minutes: 10)

 return ['success' => $successCount, 'already_existing' => count($existingIds ∩ $validIds),
         'invalid' => $invalidCount, 'failed_chunks' => $failedCount]

 Wire controller bulkStore() to handler. Deprecate ElectionMembership::bulkAssignVoters().

 ---
 C.4 — Cache Key Migration + ElectionCacheService

 Write first:

 Tests (add to existing test files):
 - test_voter_count_cache_key_includes_organisation_id
 - test_old_cache_key_is_also_forgotten_during_transition
 - test_election_cache_service_forget_clears_all_known_keys

 New file: app/Services/ElectionCacheService.php

 final class ElectionCacheService
 {
     public static function keyFor(string $organisationId, string $electionId, string $suffix): string
     {
         return "org.{$organisationId}.election.{$electionId}.{$suffix}";
     }

     /**
      * Forget all voter-related keys for an election.
      * Dual-forget handles transition from legacy key format.
      * File driver is active — cache tags are NOT used (incompatible).
      */
     public static function forgetVoterKeys(string $organisationId, string $electionId): void
     {
         // New tenant-isolated keys
         Cache::forget(self::keyFor($organisationId, $electionId, 'voter_count'));
         Cache::forget(self::keyFor($organisationId, $electionId, 'voter_stats'));
         Cache::forget(self::keyFor($organisationId, $electionId, 'eligible_voters'));

         // Legacy keys (kept during transition, self-expire at 5-min TTL)
         Cache::forget("election.{$electionId}.voter_count");
         Cache::forget("election.{$electionId}.voter_stats");
     }
 }

 Update ElectionMembership::booted() $invalidate closure:
 $invalidate = function (self $membership) {
     ElectionCacheService::forgetVoterKeys($membership->organisation_id, $membership->election_id);
     Cache::forget("user.{$membership->user_id}.voter.{$membership->election_id}");
 };
 static::saved($invalidate);
 static::deleted($invalidate);
 static::restored($invalidate);

 Update Election::getVoterStatsAttribute():
 // Old: "election.{$this->id}.voter_stats"
 // New: ElectionCacheService::keyFor($this->organisation_id, $this->id, 'voter_stats')

 ---
 C.5 — Audit Logging + Rate Limiting

 Update ElectionVoterController:
 - destroy(): Log::channel('voting_security')->info('Voter removed', [...])
 - approve(): Log::channel('voting_security')->info('Voter approved', [...])
 - suspend(): Log::channel('voting_security')->info('Voter suspended', [...])

 Route updates for rate limiting:

 app/Http/Middleware/ThrottleByOrganisation.php:
 protected function resolveRequestSignature($request): string
 {
     return sha1($request->route('organisation') . '|' . $request->ip());
 }

 Apply to voter bulk endpoint:
 // throttle:10,1 = 10 requests per minute per organisation
 Route::post('elections/{election}/voters/bulk', ...)
      ->middleware(['throttle.organisation:10,1']);

 ---
 C.6 — Dead-Letter Queue (Amendment 5)

 Migration: database/migrations/2026_05_20_000001_create_dead_letter_queue_table.php

 Schema::create('dead_letter_queue', function (Blueprint $table) {
     $table->uuid('id')->primary();
     $table->string('queue_name');
     $table->json('payload');
     $table->text('error_message');
     $table->string('error_class');
     $table->uuid('organisation_id')->nullable();
     $table->uuid('election_id')->nullable();
     $table->timestamp('failed_at');
     $table->timestamp('retried_at')->nullable();
     $table->timestamps();
     $table->index(['queue_name', 'failed_at']);
 });

 Model: app/Models/DeadLetterEntry.php

 ---
 C.7 — Remove Deprecated Model Methods (End of Phase C)

 Only after:
 - All existing tests still green
 - Handler integration tests green
 - 48-hour (or 1 sprint) stability window confirmed
 - No "old method called" warnings in logs

 Then:
 - Delete ElectionMembership::assignVoter()
 - Delete ElectionMembership::bulkAssignVoters()
 - Update existing test factories that call these methods directly

 ---
 Full File Change List

 New Files (Including All Amendment Files)

 ┌─────────────────────────────────────────────────────────────────────────────────────────┬────────────────────┐
 │                                          File                                           │       Phase        │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Domain/Election/Enum/ElectionMode.php                                               │ A.2                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Domain/ElectionContext.php                                       │ C.2 (P1.2)         │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Domain/Policies/VoterEligibilityQueryServiceInterface.php        │ A.3 (renamed P0.2) │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Domain/Repositories/VoterRepositoryInterface.php                 │ C.2                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Domain/Exceptions/VoterNotEligibleException.php                  │ A.3                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Domain/Exceptions/DuplicateVoterException.php                    │ A.3                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php │ B.1 (renamed P0.2) │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Infrastructure/Repositories/EloquentVoterRepository.php          │ C.2                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Application/Commands/AssignVoterCommand.php                      │ C.2                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Application/Commands/BulkAssignVotersCommand.php                 │ C.3                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Application/Handlers/AssignVoterHandler.php                      │ C.2                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Application/Handlers/BulkAssignVotersHandler.php                 │ C.3                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Contexts/Elections/Application/ElectionVoterApplicationService.php                  │ C.2 (P1.2)         │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Services/VoterIntegrityGuardService.php                                             │ C.1 (P0.1)         │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Services/ElectionCacheService.php                                                   │ C.4 (P0.3)         │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Domain/Election/Events/VoterAssignedToElection.php                                  │ C.2                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Domain/Election/Events/VoterRemovedFromElection.php                                 │ C.5                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Domain/Election/Events/BulkVotersAssignedToElection.php                             │ C.3                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Models/DeadLetterEntry.php                                                          │ C.6                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ app/Http/Middleware/ThrottleByOrganisation.php                                          │ C.5                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ database/migrations/2026_05_19_000001_harden_election_memberships.php                   │ C.1                │
 ├─────────────────────────────────────────────────────────────────────────────────────────┼────────────────────┤
 │ database/migrations/2026_05_20_000001_create_dead_letter_queue_table.php                │ C.6                │
 └─────────────────────────────────────────────────────────────────────────────────────────┴────────────────────┘

 Modified Files

 ┌──────────────────────────────────────────────────┬─────────────────┬───────────────────────────────────────────────────────────────────────┐
 │                       File                       │      Phase      │                                Change                                 │
 ├──────────────────────────────────────────────────┼─────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ app/Models/ElectionMembership.php                │ C.1/C.4         │ +SoftDeletes, +restored event, cache key fix, deprecate write methods │
 ├──────────────────────────────────────────────────┼─────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ app/Models/Election.php                          │ C.4             │ New cache key format                                                  │
 ├──────────────────────────────────────────────────┼─────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ app/Services/VoterEligibilityService.php         │ B.2             │ Inject + delegate to policy                                           │
 ├──────────────────────────────────────────────────┼─────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ app/Http/Controllers/ElectionVoterController.php │ B.3/C.2/C.3/C.5 │ Cross-tenant guard, wire handlers, audit logging                      │
 ├──────────────────────────────────────────────────┼─────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ app/Providers/AppServiceProvider.php             │ B.1/C.2         │ Bind 2 interface pairs                                                │
 ├──────────────────────────────────────────────────┼─────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ database/factories/OrganisationFactory.php       │ A               │ Add electionOnly() state                                              │
 ├──────────────────────────────────────────────────┼─────────────────┼───────────────────────────────────────────────────────────────────────┤
 │ database/factories/ElectionMembershipFactory.php │ A               │ Add forElection() state                                               │
 └──────────────────────────────────────────────────┴─────────────────┴───────────────────────────────────────────────────────────────────────┘

 ---
 Stage-by-Stage Test Commands

 # Phase A complete
 php artisan test tests/Unit/Contracts/
 php artisan test tests/Unit/Domain/Election/ElectionModeTest.php
 php artisan test  # full suite — must equal baseline

 # Phase B complete
 php artisan test tests/Feature/Contexts/Elections/EloquentVoterQualificationPolicyTest.php
 php artisan test tests/Feature/Election/ElectionOnlyModeTest.php     # eligibility tests GREEN
 php artisan test tests/Feature/Election/CrossTenantElectionAccessTest.php
 php artisan test  # full suite

 # Phase C complete
 php artisan test tests/Feature/Election/ElectionOnlyModeTest.php     # ALL tests GREEN
 php artisan test tests/Feature/Election/VoterEligibilityTest.php
 php artisan test tests/Feature/Voter/VoterImportElectionOnlyTest.php
 php artisan test tests/Unit/Models/ElectionMembershipTest.php
 php artisan test  # full suite — exits 0

 ---
 Estimated Timeline

 ┌───────┬────────────────────────────────────┬──────────┐
 │ Phase │                Work                │ Estimate │
 ├───────┼────────────────────────────────────┼──────────┤
 │ A     │ Freeze + ElectionMode + Interfaces │ 4 h      │
 ├───────┼────────────────────────────────────┼──────────┤
 │ B     │ Policy layer + Controller guard    │ 4 h      │
 ├───────┼────────────────────────────────────┼──────────┤
 │ C.1   │ Critical migration                 │ 1 h      │
 ├───────┼────────────────────────────────────┼──────────┤
 │ C.2   │ Repository + AssignVoterHandler    │ 2.5 h    │
 ├───────┼────────────────────────────────────┼──────────┤
 │ C.3   │ BulkAssignVotersHandler            │ 2.5 h    │
 ├───────┼────────────────────────────────────┼──────────┤
 │ C.4   │ Cache service                      │ 1 h      │
 ├───────┼────────────────────────────────────┼──────────┤
 │ C.5   │ Audit logging + rate limiting      │ 1 h      │
 ├───────┼────────────────────────────────────┼──────────┤
 │ C.6   │ Dead-letter queue                  │ 1 h      │
 ├───────┼────────────────────────────────────┼──────────┤
 │ C.7   │ Remove deprecated model methods    │ 1 h      │
 ├───────┼────────────────────────────────────┼──────────┤
 │ Total │                                    │ ~18 h    │
 └───────┴────────────────────────────────────┴──────────┘

 ---
 Final Architecture Diagram

 Controller
     ↓
 ElectionVoterApplicationService   ← P1.2: orchestration boundary
     ↓                  ↓
     ↓                  ↓
 AssignVoterHandler  BulkAssignVotersHandler
     ↓                  ↓
 VoterIntegrityGuardService         ← P0.1: replaces dropped DB FK
     ↓                  ↓
 EloquentVoterRepository
     ↓
 ElectionMembership (pure persistence, no cache logic)

 Eligibility reads only:
 Handler → VoterEligibilityQueryService → DB (read models)   ← P0.2: renamed from Policy

 Cache:
 Handler → ElectionCacheService → Cache::forget()            ← P0.3: moved OUT of model

 Events (after commit):
 Handler → VoterAssignedToElection → (listeners)

 ---
 Definition of Done

 - Phase A contract tests pass (current behavior locked)
 - VoterIntegrityGuardService active before FK drop (P0.1)
 - Only ONE eligibility service (VoterEligibilityQueryServiceInterface) — correctly named (P0.2)
 - Cache invalidation owned exclusively by handlers, not model booted() (P0.3)
 - Election-only mode works without user_organisation_roles or members
 - ElectionOnlyModeTest.php all tests GREEN (currently RED)
 - No duplicate eligibility logic across services/controllers/models
 - Tenancy validated explicitly in every write (ElectionContext.organisationId vs election)
 - All voter assignments through ApplicationService → Handler → Repository pipeline
 - Soft-deleted voters restored on re-import (no unique constraint collision)
 - Cache keys use ElectionCacheService.keyFor() with organisation_id
 - Domain events dispatched for all voter mutations (after transaction commit)
 - Idempotency key supported for bulk operations (10-minute deduplication)
 - Bulk endpoint rate-limited at 10 req/min per organisation
 - Failed rows written to dead_letter_queue at row-level granularity (P1.3)
 - All voter mutations audit-logged to voter_audit channel
 - Model write methods deprecated then deleted (no partial adoption)
 - php artisan test exits 0 (zero regressions)
╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌╌

─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────
 Claude has written up a plan and is ready to execute. Would you like to proceed?

 > 1. Yes, auto-accept edits
   2. Yes, manually approve edits
   3. No, refine with Ultraplan on Claude Code on the web
   4. Tell Claude what to change
      shift+tab to approve with this feedback