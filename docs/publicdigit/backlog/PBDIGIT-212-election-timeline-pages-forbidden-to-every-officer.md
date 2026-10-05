# PBDIGIT-212 — The election timeline pages return 403 to every user, chief officers included

**Type:** Defect (production, authorization) · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-10-01
**Found by:** the controlled closure pass, resolving the last UNDETERMINED test (`ElectionGracePeriodUITest`) (`docs/publicdigit/reviews/2026-10-01-backlog-completion-review.md` §9)
**Raised by:** `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🔴 **OPEN — production-code defect, runtime-confirmed in the test environment** (Gate probe against `nrna_test`; the live production environment was **not** tested). **Not authorised.** |
| **Problem** | Routes `elections.timeline` and `elections.timeline-view` (`routes/election/electionRoutes.php:360-370`) carry `->can('manageSettings', 'election')`. Their actions declare **`string\|Election $election`** (`ElectionManagementController.php:1393`, `:1418`). **Laravel's implicit route-model binding binds only a parameter typed as a single model class**, so with a union type the parameter stays the **slug string**. The `Authorize` middleware then calls the Gate with a string, no `ElectionPolicy` method can apply, and **the request is denied for every user**. The controller's own string fallback (`:1395-1397`) is never reached. |
| **Business consequence** | No election officer can open the election timeline (voting window, grace period, auto-transition settings) or its read-only view. The Management page links to both (`resources/js/Pages/Election/Management.vue:489`, `:498`; `TimelineView.vue:21`). The GET pages that present the timeline form are unreachable. *(Whether the separate PATCH `updateTimeline`, which `PBDIGIT-60:83` relies on as a recovery path, can still be reached by another route is not established here; it has no route-level `can:`.)* |
| **Origin (git blame on `main`)** | **Present on `main`; not a recent regression.** `timeline-view` got its `->can('manageSettings', 'election')` on **2026-04-22** (`34e7c236c`), and the `string\|Election` signature arrived the same afternoon (`6a5285fdc`). `timeline` got its `can:` on **2026-05-20** (`158d3f27a`). **So both pages have denied every user since April/May 2026.** |

## Evidence

- **Gate probe (2026-10-01, scratch test against `nrna_test`)**, for an active chief officer of the election: `Gate::allows('manageSettings', $election)` called directly → **`true`**. The same user through the route → `Gate::after` records `ability=manageSettings args=['<slug>'] result=NULL` → `AuthorizationException: This action is unauthorized.`
- **Sorted route middleware** (`router->gatherRouteMiddleware`): `… TenantContext → SubstituteBindings → … → Authenticate → EnsureEmailIsVerified → Authorize:manageSettings,election`. Binding runs before `Authorize`, but **binds nothing for a union-typed parameter**.
- **Scope of the pattern:** a reflection scan of **every** route with a `can:` middleware for a parameter that is a union, a builtin or missing → **exactly these two routes**. `updateTimeline` (PATCH) has no route-level `can:` and authorises in the controller, so it is not affected by this mechanism.
- **Test:** `tests/Feature/Election/ElectionGracePeriodUITest.php` (fails with 403).

## Existing-story search

`git grep -i timeline` over every `PBDIGIT-*.md`, the 76–157 backlog and the 159–211 specs, filtered for 403, binding, `manageSettings`, union. The nearest stories own other things:
- `PBDIGIT-151` aligns ElectionPolicy gates with the resolver;
- `152` concerns the route-level operation matrix;
- `154` concerns the ungoverned `updateDates`;
- `139` covers capability-test drift.

**None names this binding failure. No owner.**

## Scope (when authorised)

RED: `ElectionGracePeriodUITest`, with its assertions unchanged, plus one test per route asserting **200 for an active chief/deputy and 403 for a non-officer**. GREEN, the smallest change that binds the model before `can:`:
- type the parameter as `Election`, and resolve the scope deliberately where the controller needs `withoutGlobalScopes()`; **or**
- move the check into the controller. **Which one is decided in the RED, consistent with `PBDIGIT-151`'s direction.**

## Out of scope

The capability model for editing the timeline (`PBDIGIT-139`/`144`/`151`) · `updateDates` (`PBDIGIT-154`) · route-matrix duplication (`PBDIGIT-152`).

## Acceptance criteria

- An active chief or deputy reaches both pages, and they render the election's timeline settings. **Business state:** the right election's values are shown.
- A non-officer gets 403.
- The reflection scan reports 0 routes with a route-level `can:` on a parameter that cannot bind.

## Dependencies / required authority

PO authorisation. No business or architecture decision is needed beyond staying consistent with `PBDIGIT-151`.

## Verification method

The tests above, plus a manual check as a chief officer in a test organisation: Management → Timeline.

**Traceability:** `routes/election/electionRoutes.php:360-370` · `ElectionManagementController.php:1393, :1418, :1395-1397` · `app/Policies/ElectionPolicy.php:46-53` · commits `34e7c236c`, `6a5285fdc`, `158d3f27a` · `Management.vue:489, :498` · `PBDIGIT-60:83`.
