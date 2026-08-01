# Round 6 — Evidence Register

**Date:** 2026-06-13  
**Status:** Gate document — all strategic claims must trace to this register

## PROVEN — High Confidence, Multiple Independent Evidence Sources

| Claim | Evidence | Source Documents |
|-------|----------|-----------------|
| Election is the aggregate root | 4/4 criteria: commands, state, invariants, consistency boundary via `transitionTo()` + `Cache::lock()` | aggregate-boundary-analysis.md |
| Constitution is SSOT for lifecycle governance | `ElectionConstitution::RULES` defines all transitions + roles + preconditions. Guard is sole enforcer. | election-state-machine-analysis.md |
| 14 constitutional actions form the ubiquitous language | Backend Constitution + frontend `ElectionActions` + `StateMachineContract` all agree | capability-language-analysis.md |
| Lifecycle truth is derived from business facts | `ElectionLifecycleEngineImpl::getState()` ignores state column, uses 12-step derivation | lifecycle-engine-analysis.md |
| Backend → Frontend capability bridge exists | Full chain: Constitution → Guard → Engine → Resolver → Snapshot → Inertia → `useElectionCapabilities()` | lifecycle-engine-analysis.md |
| VotingSession is NOT an aggregate | 0.5/4 criteria. Immutable, readonly, no commands, no state changes. Domain Snapshot. | aggregate-boundary-analysis.md |
| Frontend has one genuine domain policy | `ElectionApprovalPolicy` (40-voter threshold) extracted from Management.vue | election-management-analysis.md, FRONTEND_DISCOVERY_PHASE_COMPLETE.md |
| `CreateVote.vue` is dead code | Route commented out. All voting routes redirect to slug-based system. | createvote-contract-divergence.md |

## CANDIDATE — Evidence Exists, But Not Yet Definitive

| Claim | Evidence For | Evidence Against | Source Documents |
|-------|-------------|------------------|-----------------|
| Voting is a separate bounded context | Own language, services, value objects, persistence (`votes`, `results` tables) | No independent events. Eligibility ownership unresolved. | voting-domain-discovery.md |
| ReplaySession is an aggregate | State machine with guards (sealed→replayed→certified/diverged), owns invariants | 2.5/4 criteria. No persistence boundary or cross-process locking. | trustworthiness-discovery.md, aggregate-boundary-analysis.md |
| Organisation is a Generic Subdomain | Tenant root, CRUD lifecycle, generic multi-tenant pattern | No aggregate boundary analysis performed. | election-domain-inventory.md |
| Membership is a supporting subdomain | `ElectionMembership` model, eligibility logic, voter status lifecycle | No aggregate boundary analysis performed. No domain events discovered. | election-domain-inventory.md |
| ElectionAction PHP enum needs syncing | 5 actions missing from enum that exist in Constitution and frontend | Functional impact unknown — code may not rely on enum for decision-making | language-drift-analysis.md |

## UNRESOLVED — Competing Interpretations, Insufficient Evidence

| Question | Interpretation A | Interpretation B | Source Documents |
|----------|-----------------|-----------------|-----------------|
| Trustworthiness classification | **Supporting subdomain** — all artifacts under `Domain/Election/`, TrustCapabilityPolicy is layer 3 in Governance resolver chain, depends on Election models | **Independent subdomain candidate** — owns language (ReplaySession, ReplayCertification, EvidenceEnvelope), `Contexts/Trust/` directory exists, VerificationRevokedEvent | governance-context-map.md, trustworthiness-discovery.md |
| Voting bounded-context status | **Separate context** — own language, services, Value Objects, persistence (`votes`, `results` tables), VotingEngine orchestrates independently | **Supporting subdomain** — no independent events, eligibility owned by Election Governance, no aggregate root, consumes pre-computed snapshots | voting-domain-discovery.md, eligibility-ownership-analysis.md |
| Membership aggregate root | **ElectionMembership is aggregate** — owns voter status lifecycle, expiration, suspension | **Entity within Election Governance** — no aggregate boundary analysis performed, no domain events, queried directly by Guard and TrustPolicyEvaluator | election-domain-inventory.md |

## REJECTED — Evidence Contradicts Claim

| Claim | Rejection Evidence | Source Documents |
|-------|-------------------|-----------------|
| VotingSession is an aggregate | 0.5/4 criteria. `final readonly`, no commands, no state transitions, no consistency boundary. | aggregate-boundary-analysis.md |
| Trust is a peer bounded context | All artifacts depend on Election models. 1 event in `Contexts/Trust/`. TrustCapabilityPolicy is a layer in Governance resolver. | governance-context-map.md |
| Election is a God Object | 36 methods, most delegate. Only 5-6 contain non-delegated rules. Constitution + Guard + Engine carry real logic. | lifecycle-engine-analysis.md |
| CreateVote.vue contains domain knowledge | Hardcoded post IDs duplicated from backend `posts` table. Route is dead code. | createvote-contract-divergence.md |
| Apply.vue needs DDD extraction | 57% CSS, 10% script, all form validation. No business rules. | candidacy-apply-assessment.md |
| `handleDatesUpdated` needs extraction | Column mapping is persistence knowledge, not domain logic. | handleDatesUpdated-assessment.md |
| `phaseInfo` duplicates domain logic | Different output shapes, different purposes from `ElectionPhaseService::phaseFor()` | phase-service-assessment.md |
