# Backend DDD Discovery Phase — Complete

**Date:** 2026-06-13  
**Status:** Phase complete — ready for Strategic DDD Mapping (Round 6)

## Summary

| Metric | Count |
|--------|-------|
| Discovery documents | 7 |
| Architectures proven | 5 |
| Architectures rejected | 3 |
| Candidates carried forward | 3 |

## Discoveries in Full

| Round | Document | Target | Verdict |
|-------|----------|--------|---------|
| 1 | `election-domain-inventory` | Models (Election, Candidacy, Vote, Post) | ✅ Election is domain-rich (2279 lines, 36 methods) |
| 2 | `election-state-machine-analysis` | Constitution + Guard + Lifecycle Engine | ✅ Constitution is SSOT for lifecycle governance |
| 3 | `lifecycle-engine-analysis` | EngineImpl, Snapshot, CapabilityResolver | ✅ State derived from business facts, not stored |
| 4A | `capability-language-analysis` | 14 constitutional actions vs frontend | ✅ Capabilities = ubiquitous language |
| 4B | `constitution-analysis` | ElectionConstitution deep-dive | ✅ 6 candidate context groups identified |
| 4C | `language-drift-analysis` | PHP enum vs Constitution vs Frontend | ⚠️ 5 backend enum cases missing |
| 5B | `trustworthiness-discovery` | Replay, Evidence, Trust subsystems | ✅ Trustworthiness architecture exists |
| 5C | `governance-context-map` | Trust ↔ Governance ownership | ✅ Trust = Supporting Subdomain |
| 5D | `aggregate-boundary-analysis` | 4-criterion aggregate classification | ✅ Election = Aggregate Root |

## Proven

| Finding | Confidence | Evidence |
|---------|-----------|----------|
| Election is the aggregate root | High | `transitionTo()` + Guard + locking + state write barrier (4/4 criteria) |
| Constitution is SSOT | High | `ElectionConstitution::RULES` is the single authority read by Guard |
| Capability language = ubiquitous language | High | 14 actions shared by Constitution + frontend `ElectionActions` |
| Lifecycle truth derived from facts | High | `ElectionLifecycleEngineImpl` ignores state column, re-derives from business facts |
| Backend → Frontend capability bridge | High | Full chain: Constitution → Guard → Engine → Resolver → Snapshot → Frontend |
| Trustworthiness architecture exists | High | Replay certification, SHA256 hashing, evidence classification, trust policies |
| Trust is a Supporting Subdomain | High | All trust artifacts depend on Election Governance models + live under `Domain/Election/` |

## Rejected

| Hypothesis | Rejection Reason |
|-----------|-----------------|
| VotingSession is an Aggregate | 0.5/4 criteria — immutable, readonly, no commands, no state changes. Classified as Domain Snapshot / Decision Artifact. |
| Trust is a peer bounded context | Artifacts depend on Election Governance models. `Contexts/Trust/` contains 1 event. All evaluation logic lives under `Domain/Election/`. |
| Election is a God Object | Most of 2279 lines are delegation — 36 methods, only 5-6 contain non-delegated rules. Constitution + Guard + Engine carry the real logic. |

## Candidates (Awaiting Resolution)

| Candidate | Blocking Question |
|-----------|------------------|
| Voting bounded context | Who owns eligibility? Is `EligibilitySnapshot` published by Governance and consumed by Voting, or does Voting reach back into Governance models? |
| ReplaySession as aggregate | Has state machine + invariants (2.5/4 criteria), but needs persistence boundary + cross-process locking to be a true aggregate. |
| ElectionAction PHP enum | 5 actions missing (`begin_setup`, `revise_and_resubmit`, `complete_nomination`, `apply_candidacy`, `archive`). Needs syncing with Constitution. |

## ADRs Still Blocked

| ADR | Blocked By |
|-----|-----------|
| ADR: Voting Bounded Context | Eligibility ownership unresolved |
| ADR: Constitutional Context Map | Voting + Membership boundaries unresolved |
| ADR: Trustworthiness Context | Currently a Supporting Subdomain — may later justify separation |

## Architecture Artifacts Created

| Artifact | Location |
|----------|----------|
| Election Domain Inventory | `discoveries/20260613-election-domain-inventory.md` |
| State Machine Analysis | `discoveries/20260613-election-state-machine-analysis.md` |
| Lifecycle Engine Analysis | `discoveries/20260613-lifecycle-engine-analysis.md` |
| Capability Language Analysis | `discoveries/20260613-capability-language-analysis.md` |
| Constitution Analysis | `discoveries/20260613-constitution-analysis.md` |
| Language Drift Analysis | `discoveries/20260613-language-drift-analysis.md` |
| Trustworthiness Discovery | `discoveries/20260613-trustworthiness-discovery.md` |
| Governance Context Map | `discoveries/20260613-governance-context-map.md` |
| Aggregate Boundary Analysis | `discoveries/20260613-aggregate-boundary-analysis.md` |

## Next Phase: Strategic DDD Mapping (Round 6)

The project has moved beyond code discovery into strategic design. Round 6 should produce a formal context map for:

- Election Governance (proven aggregate root)
- Voting (candidate, awaiting eligibility ownership)
- Trustworthiness Support (supporting subdomain)
- Organisation (multi-tenant root)
- Membership (voter eligibility source)

Required before any ADRs or refactoring: eligibility ownership analysis + ReplaySession persistence boundary.
