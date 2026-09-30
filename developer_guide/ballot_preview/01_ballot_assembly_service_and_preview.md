# Step 1: BallotAssemblyService extraction & Ballot Preview

**Commit:** `4865caf15` — `feat(voting): add Ballot Preview feature via BallotAssemblyService extraction`

## Purpose

Voters can only see the real ballot once they're inside the full code/agreement/verification
pipeline (`VoteController::create()`), which is correct for casting a real vote but means nobody —
not a voter, not an election chief — could see what the ballot looks like before voting opens.
Ballot Preview is a **read-only, interactive-but-non-submittable** view of the same ballot, reachable
via one shared (non-personal) link per election, available from `ReadyForVoting` through
`VotingActive`.

## Where it fits

```
app/Services/BallotAssemblyService.php      ← Infrastructure layer (Eloquent allowed)
app/Http/Controllers/BallotPreviewController.php
app/Http/Controllers/VoteController.php     ← real vote flow, now calls the service
app/Models/Election.php                     ← canBePreviewed()
app/Http/Middleware/OperationCapabilityMapper.php  ← 'preview_ballot' capability
routes/organisations.php                    ← organisations.elections.ballot-preview
resources/js/Pages/Vote/BallotPreview.vue   ← thin wrapper
resources/js/Pages/Vote/CreateVotingPage.vue ← previewMode prop (shared component)
```

## Key files

- `app/Services/BallotAssemblyService.php` — `buildNationalPosts(Election)`, `buildRegionalPosts(Election, string $region)`, `buildElectionProp(Election)`. Pure query/mapping extraction of what `VoteController::create()` used to build inline for real (non-demo) elections. Demo-election assembly is intentionally **not** touched — it stays inline in `VoteController`, out of scope for this service.
- `app/Http/Controllers/BallotPreviewController.php` — `index(Organisation, Election)`: 404s on demo elections, authorizes via `ChecksElectionAccess::canAccessElection()`, then renders `Vote/BallotPreview` using the service.
- `app/Traits/ChecksElectionAccess.php` (pre-existing, reused) — grants org `owner|admin|commission` roles OR an active `ElectionMembership` for that election (any role — voters and officers alike, since a shared preview link is meant for anyone registered).

## Design decisions

1. **Extraction is behavior-preserving, not a redesign.** `VoteController::create()`'s real-election branches now call `app(BallotAssemblyService::class)->buildNationalPosts($election)` / `buildRegionalPosts($election, $auth_user->region)` instead of building the query inline — same query, same mapping, same output shape. Verified via `tests/Feature/Vote/VoteControllerBallotAssemblyRegressionTest.php`, a characterization test written *before* the extraction landed, against a self-contained fixture (not the shared `ElectionScenarioFactory::votingActive()`, to avoid conflating this work with an unrelated fixture fix — see below).
2. **Region handling matches the real flow exactly.** The preview calls `buildRegionalPosts($election, (string) $user->region)` — the same single-region method the real vote flow uses, for the *viewing user's own* region. No multi-region rendering mode was introduced.
3. **Capability gate follows existing precedent.** `OperationCapabilityMapper::isOperationAllowed()` already has direct state-predicate entries (e.g. `apply_candidacy`, `view_results`); `'preview_ballot' => $snapshot->state->isVotingPhase()` follows the same pattern. `Election::canBePreviewed()` is a thin, self-documenting wrapper over the same `isVotingPhase()` enum method the mapper calls — one source of truth either way.
4. **Denial message is deliberately generic** (`"This preview isn't available right now."`) rather than naming the lifecycle phase — the audience reaching this route is any organisation member, not just this election's registered voters, so lifecycle phase isn't leaked to them.
5. **`previewMode` is the single authoritative gate** in `CreateVotingPage.vue` for anything submission-related: it swaps the agreement/submit UI for a banner, guards `requestSubmit()`/`confirmSubmit()` to no-op (belt-and-braces alongside the template `v-if`), and — critically — **fully skips** the draft-autosave `localStorage` read/write (no interval started, no restore-on-mount attempted), so a preview visit can never read or corrupt a real in-progress draft for the same user+election.
6. **`Vote/BallotPreview.vue` is a separate, thin wrapper component**, not a set of extra props on `CreateVotingPage.vue` passed from the backend. It hardcodes `preview-mode` — the only place that ever sets it — and never passes `slug`/`useSlugPath`, letting `CreateVotingPage`'s own defaults (`null`/`false`) apply.

## How it works (request flow)

```
GET /organisations/{org}/elections/{election}/ballot-preview
  → election.state:preview_ballot middleware (EnsureElectionState + OperationCapabilityMapper)
  → BallotPreviewController::index()
      → abort_if(demo, 404)
      → abort_unless(canAccessElection(...), 403)
      → BallotAssemblyService::buildNationalPosts/buildRegionalPosts/buildElectionProp
  → Inertia::render('Vote/BallotPreview', [...])
      → <CreateVotingPage ... preview-mode />
```

No `VoterSlug`, `Code`, session tenant-scoping, or IP check anywhere in this path — org access + lifecycle state is sufficient, matching the existing `organisations.elections.candidates` precedent (also read-only, multi-phase-accessible, gated the same way).

## Testing

- `tests/Unit/BallotAssemblyServiceTest.php` — shape/content contract for the service in isolation.
- `tests/Feature/Vote/VoteControllerBallotAssemblyRegressionTest.php` — proves the real vote flow's rendering is unchanged post-extraction.
- `tests/Feature/Election/BallotPreviewTest.php` — the full feature suite: lifecycle reachability (blocked before `ReadyForVoting`, reachable through `VotingActive`, blocked once `Counting` begins), an exhaustive authorization matrix (owner/admin/commission/active-voter-membership allowed; removed-membership/non-member denied), region data-shape (including the no-region notice case), demo-election 404, and — the highest-value tests — side-effect/idempotency proof (`VoterSlug`/`Code`/`Vote` row counts unchanged across repeated GETs) and a direct-POST-to-`vote.submit` rejection proving there is structurally no path to persist a vote from a preview-only visit.
- `tests/Feature/OrganisationVoterHubTest.php` — `can_preview_ballot` true/false per lifecycle state.

## Pitfalls

- `ElectionScenarioFactory::votingActive()` requires `voting_locked = true` (a separate, already-approved lifecycle-invariant fix bundled into this same commit as a prerequisite — see the commit message). If a new test using this factory unexpectedly derives `ReadyForVoting` instead of `VotingActive`, this is why.
- Don't add `user_region`/`slug`/`useSlugPath` props to `BallotPreview.vue` "for consistency" — the whole point of the wrapper is that `CreateVotingPage`'s own defaults handle their absence correctly.

## Traceability

Plan: `.claude/plans/encapsulated-hopping-hamster.md`. Commit: `4865caf15`.
