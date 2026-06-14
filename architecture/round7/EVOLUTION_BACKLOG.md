# Round 7: Evolution Backlog

**Date:** 2026-06-14  
**Purpose:** Prioritized list of architectural improvements supported by discovery evidence  
**Rule:** Only improvements with evidence. No speculative redesigns.

## P0 — Critical (governance integrity)

| Item | Evidence | Artefact |
|------|----------|----------|
| None identified | All governance mechanisms (Constitution, Guard, Engine, Resolver, Capability chain) are structurally sound and conformance-verified | ARCHITECTURE_CONFORMANCE_REPORT.md |

## P1 — Important (user-facing governance defects)

| # | Item | Evidence | Effort |
|---|------|----------|--------|
| 1 | Expose `denialDetail()` from `useElectionCapabilities` composable | Backend sends `denial_detail`. Frontend type defines it. Composables ignores it. 13/15 actions opaque. | Low |
| 2 | Show denial explanation for hidden governance actions in Management.vue | Only 2 of 15 actions show why they are unavailable. Users cannot distinguish denial reasons. | Medium |
| 3 | Migrate `VoteDenied.vue` from hardcoded denial strings to canonical `CapabilityDenialReason` enum | `VoteDenied.vue:59` branches on `denial_reason === 'ip_rate_limit'` — not in canonical enum | Low |

## P2 — Opportunistic (architecture hygiene)

| # | Item | Evidence | Effort |
|---|------|----------|--------|
| 1 | Sync `ElectionAction` PHP enum with Constitution (add 5 missing actions) | 5 actions missing. Enum used only by deprecated `TransitionMatrix`. No runtime impact. | Low |
| 2 | Replace `ElectionLifecycleEngineImpl::getAllowedActions()` with Constitution-derived data | EngineImpl re-lists allowed actions per state as inline arrays — duplicates Constitution | Medium |
| 3 | Remove deprecated `ElectionStateMachine` class | Explicitly marked deprecated, superseded by Constitution + Guard | Low |
| 4 | Remove dead code `CreateVote.vue` | Route commented out. All voting redirects to slug-based system. 1408 lines orphaned. | Low |
| 5 | Resolve `useElectionActions` / Inertia `router` gap | Composable exists but uses `fetch` instead of Inertia `router`. Pages cannot consume it. | Medium |

## P3 — Future (requires additional evidence)

| # | Item | Evidence | Trigger |
|---|------|----------|---------|
| 1 | Extract `SystemClock` from `ElectionPhaseService.ts` | Clock interface is valid domain concern. SystemClock is infrastructure. Requires verification of instantiation pattern. | Code review |
| 2 | Voting context re-evaluation | Candidate bounded context. Requires independent events and ownership analysis. | Round 8 |
| 3 | Trustworthiness context re-evaluation | Unresolved classification. Requires persistence boundary or significant `Contexts/Trust/` growth. | Growth trigger |
| 4 | Prefer `denialDetail()` over hardcoded `DENIAL_LABELS` map | Current labels are generic ("Invalid Lifecycle State"). Backend detail strings are more specific. | After P1 #1 |

## Summary

| Priority | Count |
|----------|-------|
| P0 — Critical | 0 |
| P1 — Important | 3 (all UX governance) |
| P2 — Opportunistic | 5 (architecture hygiene) |
| P3 — Future | 4 (requires evidence) |
