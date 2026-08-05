# Discovery: Election Domain Inventory

**Date:** 2026-06-13  
**Phase:** Phase 2 — Backend Domain Discovery (Round 1)  
**Status:** Complete — evidence collected, no modeling decisions yet

## Core Models

### Election (app/Models/Election.php — 2279 lines)

**Relationships:** Post, Candidacy, Vote, Result, Code, VoterSlug, Organisation, ElectionMembership, ElectionOfficer, VoterRegistration, CandidacyApplication

**State fields (78 fillable columns):**
- Lifecycle: `state`, `status`, `is_active`
- Phase flags: `administration_completed`, `nomination_completed`, `voting_locked`, `results_locked`, `results_published`
- Timelines: `voting_starts_at`, `voting_ends_at`, `administration_suggested_start/end`, `nomination_suggested_start/end`
- Approval: `submitted_for_approval_at`, `approved_at`, `rejected_at`, `approval_notes`, `rejection_reason`
- Suspension: `suspended_at`, `suspended_lifecycle_context`, `suspended_reason`, `suspension_category`
- Security: `ip_restriction_enabled`, `voter_verification_mode`, `selection_constraint_type/min/max`, `no_vote_option_enabled`
- Constitutional: `security_articles_snapshot`, `constitutional_hash`, `security_articles_version`

**Business methods (36 identified):**
- `requiresApproval()`, `canAcceptVoters()`, `assertCanAcceptVoters()` — capacity rules
- `canEnterAdministrationPhase()`, `canEnterNominationPhase()`, `canEnterVotingPhase()`, `canEnterCountingPhase()`, `canEnterResultsPhase()` — lifecycle guards
- `submitForApproval()`, `approve()`, `reject()`, `processAutoApproval()`, `processManualApproval()` — approval workflow
- `completeAdministration()`, `completeNomination()`, `forceCloseNomination()` — phase transitions
- `openVoting()`, `closeVoting()`, `canExtendVoting()`, `canPostpone()` — voting controls
- `transitionTo()`, `validateTransitionRules()`, `validateOpenVoting()`, `validateCloseVoting()` — state machine
- `whyCannotOpenVoting()`, `whyCannotCompleteAdministration()`, `getVotingPhaseBlockedReason()` — pre-flight checks
- `getStateInfo()`, `getProgress()`, `getStatistics()`, `getCapacityInfo()` — read models
- `isCurrentlyActive()`, `isPendingApproval()`, `wasRejected()`, `isDemo()`, `isReal()` — query methods

**Invariants enforced:**
- Constitutional fields immutable after creation (booted hook)
- `transitionTo()` is the single entry point for ALL state changes (protected by lock + authorization context)
- State write barrier: direct `state` column mutations are blocked unless authorized via `ElectionStateWriteContext`
- Timeline validation: chronological order (admin → nomination → voting), minimum phase durations (24h)

### Post (app/Models/Post.php)

**Fillable:** `organisation_id`, `election_id`, `name`, `nepali_name`, `is_national_wide`, `state_name`, `required_number`, `position_order`

**Key field:** `required_number` — the per-post selection limit (1 or 2 for NRNA elections). This is the database-level authoritative source for what CreateVote.vue hardcodes.

### Candidacy (app/Models/Candidacy.php)

**Statuses:** `draft`, `pending`, `approved`, `rejected`, `withdrawn`
**Business methods:** `approve()`, `reject()`, `withdraw()`
**Key invariant:** Candidacy status changes trigger cache invalidation and sync `candidates_count` / `pending_candidacies_count` on the Election model
**Relationship:** Belongs to Post → Election (no direct `election_id` column)

### Vote (app/Models/BaseVote.php + Vote.php)
Vote model extends BaseVote. Demo votes have a separate `DemoVote` model in physically separate table for logical separation from real voting data.

### Organisation (app/Models/Organisation.php)
Multi-tenant root. Notable fields: `committee_structure`, `geographic_scope`, `allowed_countries`, `geographic_levels`, `governance_status`.

## Existing Backend Domain Layer (app/Domain/)

| Subdirectory | Files | Purpose |
|-------------|-------|---------|
| `Election/Constitution/` | 3 | Constitutional articles, rules, thresholds |
| `Election/Enum/` | 6 | Lifecycle states, actions, roles |
| `Election/Events/` | 10 | Domain events for lifecycle transitions |
| `Election/StateMachine/` | 5 | Transitions, matrix, triggers |
| `Election/Policies/` | 2 | NominationWindow, VotingWindow policies |
| `Election/Security/` | 70+ | Trust evaluation, sovereignty, divergence detection |
| `Election/Services/` | 1 | ElectionLifecycleEngine interface |
| `Voting/` | 15 | Quorum rules, vote aggregation, eligibility, semantics |

## Key Observation

The backend already has a rich, well-structured Domain layer with:
- Constitutional state machine (`ElectionStateMachine`, `ConstitutionalTransitionGuard`)
- Domain events for every lifecycle transition
- Capability resolver (`ElectionCapabilityResolver`) producing snapshots consumed by frontend
- Security/trust evaluation layer
- Voting bounded context with quorum rules, eligibility evaluation

Election is the center of gravity — almost every other model orbits it. Whether it should be a single aggregate or decomposed requires further investigation of the state machine and invariant ownership.

## Not Yet Investigated (Round 2)

- `app/Domain/Election/Constitution/ElectionConstitution.php` — SSOT for rules
- `app/Domain/Election/StateMachine/ElectionStateMachine.php` — state machine logic
- `app/Application/Election/Services/ConstitutionalTransitionGuard.php` — authorization
- `app/Domain/Election/Services/ElectionLifecycleEngine.php` — lifecycle engine
- `app/Domain/Voting/Service/EligibilityEvaluator.php` — voting eligibility
- `app/Policies/*` — Laravel authorization policies
