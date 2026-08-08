# PBDIGIT-61 — The newsletter test suite fails at HEAD, before any change

**Type:** Defect (test harness / feature) · **Epic:** `PBDIGIT-EPIC-05` Member Communication · **Created:** 2026-08-07
**Found by:** `PBDIGIT-48`, attempting to migrate `OrganisationNewsletterController`'s election predicate under the TDD rule *tests must pass after the edit*

| | |
|---|---|
| **Status** | ✅ **RESOLVED 2026-08-07 — all five newsletter suites green (25 passed, 45 assertions).** Two **production feature defects** found, both previously invisible |
| **🔴 Headline finding** | **Newsletter dispatch has never worked.** `NewsletterService::dispatch()` inserted `'id' => Str::uuid()` into `newsletter_recipients.id`, a **bigint sequence** — `SQLSTATE[22P02]` on the first insert, every time, in production too. **No newsletter could ever be sent** |
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

## ✅ Residual RESOLVED — and it was a second production defect

**The two dispatch tests were correct all along; they were reporting a broken feature.**

Investigated exactly as instructed — **assert the audience query directly against the fixture rows, change nothing first.** That immediately exonerated two suspects and found the real one:

| Measurement | Result |
|---|---|
| `queryAllMembers` against the fixture | **1** — the audience query is **correct** |
| `newsletter.audience_type` in the **database** | `'all_members'` (column is `NOT NULL DEFAULT 'all_members'`) |
| → the earlier `NULL` reading | an **unhydrated in-memory model**, not stored data — which is why the speculative fixture edit changed nothing, and why reverting it was right |
| `send` status | **500** |
| raw exception | `QueryException 25P02` — *"current transaction is aborted"* |

**`25P02` is a secondary error.** `dispatch()` wraps the insert in `retry(2, …)`; the *first* insert failed, aborting the transaction, and the retry then reported only the abort. **The retry masked the real error** — which is why this survived undiagnosed.

**The real error, proven by direct insert:**

```
SQLSTATE[22P02]: invalid input syntax for type bigint: "d2556557-4ee2-40fb-a83b-402199ba2659"
```

`newsletter_recipients.id` is **`bigint DEFAULT nextval(...)`**, and `NewsletterRecipient` is a plain `Model` — no `HasUuids`, no custom `$keyType`. **The schema and the model agree; only `dispatch()` disagreed**, generating a UUID for an auto-increment key.

**Fix:** drop the `'id'` key from the recipient rows and let the sequence assign it. **All five suites: 25 passed, 45 assertions.**

### Recorded, not fixed (out of scope)

**`retry(2, …)` around a transactional insert converts a precise failure into an opaque one.** Retrying a statement inside an aborted Postgres transaction can only ever produce `25P02`. That pattern hid a total feature outage; worth a look wherever else `retry()` wraps a statement inside `DB::transaction()`.

## ⚠️ Original residual note (superseded by the section above)

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
