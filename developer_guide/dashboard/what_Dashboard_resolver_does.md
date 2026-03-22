
  What It Is

  app/Services/DashboardResolver.php is the post-login routing engine. It is called by LoginResponse and resolves where a user lands after authentication based on their current state.

  ---
  Actual Priority Stack (9 priorities — documentation says 6, code has 9)

  ┌──────────┬────────────────────────────────┬─────────────────────────────┬────────────────────────────────────────────────────────────┬──────────────────────────────────┐
  │ Priority │            Trigger             │         Destination         │                           Route                            │ Safe from ensure.election.voter? │
  ├──────────┼────────────────────────────────┼─────────────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────────┤
  │ P1       │ Email not verified             │ /email/verify               │ verification.notice                                        │ ✅                               │
  ├──────────┼────────────────────────────────┼─────────────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────────┤
  │ P2       │ Active voting slug in progress │ voting.portal               │ ⚠️ ROUTE DOES NOT EXIST                                    │ —                                │
  ├──────────┼────────────────────────────────┼─────────────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────────┤
  │ P3       │ Active real election for user  │ 1 election → /election      │ election.dashboard (1 election) or organisations.show      │ ✅ No membership middleware      │
│          │ (count-based — see guide 07)  │ 2+ elections → /org/{slug}  │ (2+ elections)                                             │                                  │
  ├──────────┼────────────────────────────────┼─────────────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────────┤
  │ P4       │ No active organisations        │ handleMissingOrganisation() │ organisations.show or dashboard.welcome                    │ ✅                               │
  ├──────────┼────────────────────────────────┼─────────────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────────┤
  │ P5       │ Has organisation, no election  │ /organisations/{slug}       │ organisations.show                                         │ ✅                               │
  ├──────────┼────────────────────────────────┼─────────────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────────┤
  │ P6       │ First-time user                │ /dashboard/welcome          │ dashboard.welcome                                          │ ✅                               │
  ├──────────┼────────────────────────────────┼─────────────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────────┤
  │ P7       │ Multiple roles                 │ /dashboard/roles            │ role.selection                                             │ ✅                               │
  ├──────────┼────────────────────────────────┼─────────────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────────┤
  │ P8       │ Single role                    │ role-specific page          │ organisations.show / commission.dashboard / vote.dashboard │ ✅                               │
  ├──────────┼────────────────────────────────┼─────────────────────────────┼────────────────────────────────────────────────────────────┼──────────────────────────────────┤
  │ P9       │ Fallback                       │ /dashboard                  │ dashboard                                                  │ ✅                               │
  └──────────┴────────────────────────────────┴─────────────────────────────┴────────────────────────────────────────────────────────────┴──────────────────────────────────┘

  ---
  Compatibility with ensure.election.voter

  ensure.election.voter lives only on v/{vslug} routes (the actual voting steps). Every destination the DashboardResolver sends users to is outside that group:

  DashboardResolver destinations:
    /election          → auth + verified only       ✅ SAFE
    /dashboard         → auth + verified only       ✅ SAFE
    /dashboard/welcome → auth + verified only       ✅ SAFE
    /dashboard/roles   → auth + verified only       ✅ SAFE
    /vote              → auth + verified only       ✅ SAFE
    /organisations/... → auth + verified + ensure.organisation  ✅ SAFE

  v/{vslug}/* routes (voting steps):
    → has ensure.election.voter                     🔒 GUARDED

  There is no redirect loop between the DashboardResolver and ensure.election.voter. The middleware blocks at the voting step level; the resolver never lands users directly inside v/{vslug}.

  ---
  One Pre-Existing Bug Found

  Priority 2 references route('voting.portal') which does not exist in the route table.

  // DashboardResolver.php line 93-94
  return redirect()->route('voting.portal', ['voter_slug' => $activeVoterSlug->slug]);

  voting.portal is not registered. If Priority 2 ever fires (user has an active slug with code_to_open_voting_form_used_at set), this will throw a RouteNotFoundException. This is a pre-existing bug unrelated
  to the ensure.election.voter work — getActiveVoterSlug() wraps its query in a try-catch but the route() call is outside it.

  In practice this does not trigger because getActiveVoterSlug() queries columns (code_to_open_voting_form_used_at, has_agreed_to_vote_at, vote_submitted_at) that likely do not exist in the current voter_slugs
   schema — the query silently returns null via the catch block, skipping Priority 2 entirely.

  ---
  Cache Strategy (Actual)

  - Key: config('login-routing.cache.cache_key_prefix') . $user->id
  - TTL: config('login-routing.cache.dashboard_resolution_ttl', 300) — 5 minutes default
  - Bypassed if: session is not fresh (based on last_activity_at column, 60-second threshold)
  - last_activity_at column: gracefully skipped if missing (returns true = assume fresh)

  ---
  What Was Confirmed Working

  ┌─────────────────────────────────────────┬───────────────────────────────────────────────────────────────┐
  │                Component                │                            Status                             │
  ├─────────────────────────────────────────┼───────────────────────────────────────────────────────────────┤
  │ P1 Email check                          │ ✅ Works, enforced before anything else                       │
  ├─────────────────────────────────────────┼───────────────────────────────────────────────────────────────┤
  │ P3 Active election → election.dashboard │ ✅ Works with count-based routing (see 07_ELECTION_PRIORITY_3_ROUTING.md) │
  ├─────────────────────────────────────────┼───────────────────────────────────────────────────────────────┤
  │ P5 Org redirect                         │ ✅ Works                                                      │
  ├─────────────────────────────────────────┼───────────────────────────────────────────────────────────────┤
  │ P6 Welcome page                         │ ✅ Works                                                      │
  ├─────────────────────────────────────────┼───────────────────────────────────────────────────────────────┤
  │ P9 Fallback dashboard                   │ ✅ Works                                                      │
  ├─────────────────────────────────────────┼───────────────────────────────────────────────────────────────┤
  │ Interaction with ensure.election.voter  │ ✅ No conflict — different route layers                       │
  ├─────────────────────────────────────────┼───────────────────────────────────────────────────────────────┤
  │ voting.portal route                     │ ⚠️ Does not exist — pre-existing dead reference in Priority 2 │
  └─────────────────────────────────────────┴───────────────────────────────────────────────────────────────┘