# PBDIGIT-41 — Demo provisioning has two paths and the documented one is broken

**Type:** Defect (documentation + duplication) · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-06
**Found by:** `PBDIGIT-00` part (b), while trying to seed a demo election

| | |
|---|---|
| **Status** | **OPEN — not authorised** |
| **Customer impact** | **A customer or operator following the guide cannot create a demo election.** Demo mode is how the platform is evaluated before purchase |

---

## Two provisioning paths, and they disagree

| Path | State |
|---|---|
| `php artisan db:seed --class=DemoElectionSeeder` — **what `docs/DEMO_ELECTION_GUIDE.md` documents, marked "✅ WORKING"** | 🔴 **BROKEN** |
| `php artisan demo:setup` | ✅ **WORKS** — created 1 active election, 10 posts (2 national · 8 regional), 30 candidates |

**The guide documents the broken one and does not mention the working one.**

## Why the seeder fails

```
SQLSTATE[42703]: Undefined column: column "post_id" does not exist
  select * from "posts" where ("post_id" = 'president-a26fd85e-…')
```

`database/seeders/DemoElectionSeeder.php:36,51,66` calls `firstOrCreate(['post_id' => 'president-' . $election->id], …)`. The `posts` table has **no `post_id` column** — its key is `id`. `post_id` is the *foreign* key on other tables (`candidacies.post_id`, `results.post_id`), which `app/Models/Post.php` uses correctly.

**This is the same defect class as `PBDIGIT-36` F-2b**, where `Phase6EndToEndIntegrationTest` fails on the identical mistake. **Two independent callers made the same wrong assumption about the same table**, which points at the documentation as the source:

⚠️ **The root `CLAUDE.md` domain model documents `POST` with a `post_id` attribute.** The schema says otherwise. **The documentation is wrong, and it has now demonstrably misled two pieces of code.** Correcting it is part of this story.

## Smaller items found alongside — same area, worth one slice

| Item | Detail |
|---|---|
| **`demo:setup` prints a stale URL** | It reports `Access URL: http://localhost/election/demo/start`. The real route is **`/organisations/{organisation_slug}/demo/start`** (`routes/web.php:210`). An operator following the printed URL gets a 404 |
| **Duplicate route name `election.select`** | Registered twice — `routes/election/electionRoutes.php:41` (`VotingElectionController@selectElection`) and `routes/web.php:205` (`ElectionController@selectElection`). The later registration silently wins, so `route('election.select')` may not reach the controller a reader expects. **Relevant to `PBDIGIT-32`**, which needs an organisation/election selection surface and would otherwise build on an ambiguous name |

## Acceptance criteria

* [ ] Decide which provisioning path is canonical. **`ES-005.4`: consume or extend one — do not maintain two.**
* [ ] The non-canonical path is removed, or fixed and explicitly documented as an alias.
* [ ] `docs/DEMO_ELECTION_GUIDE.md` documents the path that works, and its "✅ WORKING" claim is verified rather than asserted.
* [ ] The root `CLAUDE.md` domain model matches the `posts` schema (`id`, not `post_id`).
* [ ] `demo:setup` prints a URL that resolves.
* [ ] The `election.select` name collision is resolved, and the intended target recorded.

## The pattern worth noting

**Three of this story's items are documentation that was wrong in a way code trusted:** a guide recommending a broken seeder, a domain model naming a column that does not exist, and a command printing a route that 404s. **`PBDIGIT-37` Part 3 is a fourth** (a safety comment that is false on PostgreSQL). None was caught by any gate, because **no gate tests documentation against reality** — and every one of them cost time in this single commission.

---

**Traceability:** `PBDIGIT-00` §Result (b) · `database/seeders/DemoElectionSeeder.php:36,51,66` · `docs/DEMO_ELECTION_GUIDE.md` §"Method 2: Create via Seeder ✅ WORKING" · `app/Console/Commands/` (`demo:setup`) · `routes/web.php:205,210` · `routes/election/electionRoutes.php:41` · root `CLAUDE.md` domain model · `PBDIGIT-36` F-2b · `PBDIGIT-37` Part 3 · ES-005.4
