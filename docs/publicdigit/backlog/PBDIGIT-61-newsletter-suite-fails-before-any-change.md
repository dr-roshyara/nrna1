# PBDIGIT-61 — The newsletter test suite fails at HEAD, before any change

**Type:** Defect (test harness / feature) · **Epic:** `PBDIGIT-EPIC-05` Member Communication · **Created:** 2026-08-07
**Found by:** `PBDIGIT-48`, attempting to migrate `OrganisationNewsletterController`'s election predicate under the TDD rule *tests must pass after the edit*

| | |
|---|---|
| **Status** | 🔴 OPEN |
| **Symptom** | `tests/Feature/Organisation/NewsletterCreationTest.php` — **6 of 6 fail at HEAD**, with no working-tree changes (stash-verified twice) |
| **Consequence** | **Member Communication has no working safety net.** Any change to the newsletter controller ships unverifiable — which is why a `PBDIGIT-48` edit to it was reverted rather than shipped |

## Verified facts

* **F1 — the failures pre-exist.** 6/6 fail with the working tree clean at `54a4424c`, and identically with/without the attempted one-line edit.
* **F2 — one layer was masking the rest.** The loudest error, `Log [login] is not defined`, was an unrelated infrastructure gap (channel named by `login-routing` config, never defined in `logging.php`) — fixed standalone with its own regression suite (`tests/Feature/Logging/LoginAnalyticsChannelTest.php`, commit `f6946102`). **The suite still fails after that fix**, so the channel was noise, not cause.
* **F3 — the remaining failures are routing/authorization-shaped, not election-state-shaped:** `assertRedirect` receives a non-redirect; the non-admin test expects `403` and receives `404`; a `null is not null` on a created resource. The store route runs `web, auth, verified, EnsureOrganisationMember` and the controller resolves the organisation by slug with `firstOrFail`.
* **F4 — eliminated so far:** the org factory does set `slug`; `Organisation::getRouteKeyName()` is `slug`; `EnsureOrganisationMember` redirects (302) rather than 404s when the org is missing — so the 404's origin is not yet established.

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
