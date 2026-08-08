# PBDIGIT-61 — The newsletter test suite fails at HEAD, before any change

**Type:** Defect (test harness / feature) · **Epic:** `PBDIGIT-EPIC-05` Member Communication · **Created:** 2026-08-07
**Found by:** `PBDIGIT-48`, attempting to migrate `OrganisationNewsletterController`'s election predicate under the TDD rule *tests must pass after the edit*

| | |
|---|---|
| **Status** | ✅ **RESOLVED 2026-08-07** — `NewsletterCreationTest` 6/6 green; root cause was a **feature defect**. ⚠️ **One residual, newly diagnosed:** 2 pre-existing failures in `NewsletterUnsubscribeTest` (see §Residual) |
| **Symptom** | `tests/Feature/Organisation/NewsletterCreationTest.php` — **6 of 6 fail at HEAD**, with no working-tree changes (stash-verified twice) |
| **Consequence** | **Member Communication has no working safety net.** Any change to the newsletter controller ships unverifiable — which is why a `PBDIGIT-48` edit to it was reverted rather than shipped |

## Verified facts

* **F1 — the failures pre-exist.** 6/6 fail with the working tree clean at `54a4424c`, and identically with/without the attempted one-line edit.
* **F2 — one layer was masking the rest.** The loudest error, `Log [login] is not defined`, was an unrelated infrastructure gap (channel named by `login-routing` config, never defined in `logging.php`) — fixed standalone with its own regression suite (`tests/Feature/Logging/LoginAnalyticsChannelTest.php`, commit `f6946102`). **The suite still fails after that fix**, so the channel was noise, not cause.
* **F3 — the remaining failures are routing/authorization-shaped, not election-state-shaped:** `assertRedirect` receives a non-redirect; the non-admin test expects `403` and receives `404`; a `null is not null` on a created resource. The store route runs `web, auth, verified, EnsureOrganisationMember` and the controller resolves the organisation by slug with `firstOrFail`.
* **F4 — eliminated so far:** the org factory does set `slug`; `Organisation::getRouteKeyName()` is `slug`; `EnsureOrganisationMember` redirects (302) rather than 404s when the org is missing — so the 404's origin is not yet established.

## ✅ Resolution (2026-08-07) — the feature was wrong, and so were two fixtures

**Baseline first, per the Product Owner's added step:** 6/6 reproduced on a clean tree at `45df1dc5`, each failing assertion recorded before any diagnosis.

### Root cause — a route/controller contract mismatch (feature defect)

The route prefix is **`organisations/{organisation:slug}`**, so Laravel *binds* and injects an `Organisation`. Every method of `OrganisationNewsletterController` declared **`string $slug`** and then re-queried `Organisation::where('slug', $slug)->firstOrFail()` — with a value that is no longer the slug. Hence `404` before any controller logic, which is why a non-admin got `404` instead of `403`.

**Proven, not inferred:** a diagnostic probe showed the controller succeeds when handed the slug **string** directly, and fails through the router; `bindingFields` reports `{"organisation":"slug"}`; and the exception surfaced at the controller's own `firstOrFail`. **Decisive comparison:** sibling controllers in the *same route group* — `ParticipantInvitationController`, `OrganisationRoleController` — already take `Organisation $organisation`. **This controller was the only one written against a contract that does not exist.**

**Fix:** all 13 methods now take `Organisation $organisation` (the name the binding requires), and the redundant lookup is gone.

### Two genuine test defects behind it

| Defect | Repair |
|---|---|
| Three `store` posts omitted **`audience_type`**, which the controller requires — validation failed, so the redirect happened but no row was created | supplied `audience_type` (adds required input; **weakens no assertion**) |
| `preview recipient count` created a member with **`status: 'inactive'`**, which `members_status_check` rejects (`active\|expired\|suspended\|ended`) | `'expired'` — preserves the test's intent (3 of 5 eligible) |

**Result: `NewsletterCreationTest` 6/6 green.** The deferred `PBDIGIT-48` predicate removal was then re-applied and the suites re-run — still green.

## ⚠️ Residual — 2 pre-existing failures, newly diagnosed, NOT introduced here

`NewsletterUnsubscribeTest::unsubscribed_members_excluded_from_recipients_on_dispatch` and `…bounced_members…` assert 1 recipient and get **0**. **Stash-verified as pre-existing** (identical with this ticket's changes removed).

**Established:** `send()` → `NewsletterService::dispatch()` is **synchronous** (no queue involved), requires `status='draft'` (the fixture sets it), and builds recipients from `resolveAudience()`. **Eliminated:** a missing `audience_type` on the fixture — supplying it changed nothing, so that speculative edit was reverted rather than left in the diff.

**Not yet established:** why `queryAllMembers` matches zero rows for a fixture that creates an active, subscribed member. **Next step:** assert the audience query directly against the fixture rows before touching either side.

## Scope

Diagnose why the suite's requests do not reach the controller happy path (binding? middleware? factory state?), repair **either the tests' fixtures or the feature** — whichever is actually wrong — and get the suite green at HEAD.

## Blocks

* The deferred `PBDIGIT-48` change to `OrganisationNewsletterController` (removal of the `status != 'deleted'` no-op predicate — nothing ever writes `'deleted'`, and deletion belongs to `SoftDeletes`). The edit is trivial; it waits only for a suite that can vouch for it.

## Acceptance criteria

- [ ] `NewsletterCreationTest` green at HEAD, with the cause named (test defect vs feature defect)
- [ ] The other newsletter suites (`NewsletterDispatchTest`, `NewsletterBatchJobTest`, `NewsletterKillSwitchTest`, `NewsletterUnsubscribeTest`) run and their status recorded
- [ ] The deferred `PBDIGIT-48` predicate removal re-applied and shown green

## Traceability

Found 2026-08-07 during `PBDIGIT-48` consumer migration · stash-verification in session log 2026-08-07 · logging fix `f6946102` · reverted edit described in session log
