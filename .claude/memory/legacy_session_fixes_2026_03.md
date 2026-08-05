---
name: legacy-session-fixes-2026-03
description: "Archive of completed March 2026 fixes and TDD phases moved out of MEMORY.md index (voting flow, slugs, dashboard resolver, candidate names)"
metadata: 
  node_type: memory
  type: project
  originSessionId: d5e01507-9e85-498a-bf95-02711af72d39
---

# Legacy Session Fixes Archive (2026-03) — all COMPLETE, in git history

## 4-Part Voting Flow Fix (2026-03-10)
1. 60 candidate columns VARCHAR(36)→TEXT (`2026_03_10_000000_...`) — JSON didn't fit UUID-sized columns
2. `step_data` JSON column added to demo_voter_slug_steps (`..._000001_...`)
3. `id` auto-increment PK added to demo_voter_slug_steps (`..._000002_...`)
4. DemoVoteController:1642 `$hashed_key`→`$private_key` undefined-variable fix

## Voter Slugs — Unlimited Demo Voting (2026-03-08)
- ElectionController.startDemo(): `getOrCreateSlug($user, $election, true)` forces fresh slug
- VoterSlugService: forceNew=true HARD-deletes old slugs (`forceDelete()`) to avoid unique-constraint violations

## Session & Candidate Fixes (2026-03-08, commits f1d434b44/c8fe75fec/cda77a38e)
- Empty candidates: non-existent DemoCandidacy fields + BelongsToTenant global scope filtering — fixed field mapping + `withoutGlobalScopes()` in candidacies eager-load closures (lines 373/410)
- `session_name`/`voting_slug` columns added to codes + demo_codes; models' fillable updated

## Voter Slug Expiration — Phases 2+3 (2026-03-08) — 16/16 tests
- VoterSlugService: getOrCreateSlug/validateSlugOwnership/getValidatedSlug/createNewSlug/cleanupExpiredSlugs
- Boot hooks auto-mark expired slugs inactive; status ENUM extended ('expired','abstained')
- EnsureVoterSlugWindow middleware validates ownership (AccessDeniedHttpException on mismatch)
- `voting:clean-expired-slugs` command (--hours, --detailed)
- Guarantees: no cross-user/cross-election slug transfer; demo always fresh; real reuses active

## HasActiveElection — Phase C.3 (2026-03-06) — 23/23 tests
- User: hasActiveElection()/getActiveElection()/countActiveElections() — tenant orgs only, status='active', date range, excludes status='voted' in voter_slugs
- Election: voterSlugs() HasMany with withoutGlobalScopes()
- Bug fix: checked non-existent `vote_completed_at` → use voter_slugs.status='voted'

## DashboardResolver + EnsureOrganisationMember + HasOwnOrganisation (2026-03-06) — 16/16 tests
- TenantContext injected; getPlatformOrgId() cached (type='platform' AND is_default=true) — replaced hardcoded org_id=1 (UUID compatibility)
- User: hasOwnOrganisation()/getOwnOrganisation()/isOwnerOf()
- Routing: tenant org → /organisations/{slug}; platform new → /dashboard/welcome; onboarded → /dashboard

## Candidate Name Display Bug (2026-03-08) — TDD, GREEN phase was in progress
- DemoCandidacy::user() removed ->select() breaking eager loading; controller ->with('user'); getCandidateNameAttribute prioritizes user_name
- Open at time of archive: tests failing "Not a valid Inertia response"

## Older archives
- UUID Multi-Tenancy Phases 1-3,7: 17/17 tests
- Phase B & C Voting Models (C.1-C.2): 33 tests
- Security Page Redesign: 16/16 tests
