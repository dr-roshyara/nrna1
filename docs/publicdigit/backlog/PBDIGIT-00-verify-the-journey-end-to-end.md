# PBDIGIT-00 — Verify the journey end-to-end

**Type:** Verification · **Epic:** none — **it gates every epic** · **Created:** 2026-08-06 *(the story was referenced from `README.md` §PBDIGIT-00 from the start of the product phase; this file gives it a home)*

| | |
|---|---|
| **Status** | ✅ **(a) COMPLETE — the suites ran.** ⬜ **(b) NOT COMPLETE** — the authenticated walk needs a seeded demo election. **The environment block is lifted** |
| **Headline** | 🔴 **There is still NO automated end-to-end coverage of a complete election.** The three suites ran; **10 of 21 tests never reached a single assertion**. The journey is *still* unobserved — but now that is a measured fact rather than a suspicion |
| **Customer goal** | *"Prove that one complete journey works before anything is improved."* |
| **Why it is first** | all 24 journey stories are evidenced **in code** and **none has been observed working**. Verification converts 24 inferences into facts at near-zero cost; building on unverified ground does not |
| **Authority** | **Until `PBDIGIT-00` completes, no story may be marked `VERIFIED`** (`README.md`) |

---

## What must be verified

| | Verification | State |
|---|---|---|
| **(a)** | The three E2E suites run against a provisioned Postgres | ✅ **RAN 2026-08-06** — result below. **They did not run green, and that is the finding** |
| **(b)** | The Level 0 journey walked **once manually in demo mode**, recording at each step: page · controller · DB rows · events · log files | ⚠️ **PARTIAL** — app boots, routes exist and are correctly auth-gated; the **authenticated** walk needs a seeded demo election (see §What (b) still needs) |

**The three suites** (they exist; they have never been executed in a verified environment):

* `tests/Feature/Phase6EndToEndIntegrationTest.php`
* `tests/Feature/RealWorldVotingFlowTest.php`
* `tests/Integration/CompleteVotingFlowIntegrationTest.php`

**Demo mode (`organisation_id = NULL`) exists for exactly this purpose** — root `CLAUDE.md`; walkthrough in `docs/DEMO_ELECTION_GUIDE.md`.

---

## Environment — findings of 2026-08-06

The original status said *"no Postgres credentials in the review environment"*. **That was no longer accurate.** What is actually true:

| Fact | Evidence |
|---|---|
| PHP 8.5.8 · Composer 2.10.2 · Node 24.16.0 · `vendor/` present | version probes |
| **Postgres 18.3 is running and reachable** | `pg_isready` → `accepting connections` |
| **The development database works**: `publicdigit`, 95 tables, 4.66 MB | `php artisan db:show` |
| The test database `nrna_test` **did not exist**; `publicdigit_user` has **`rolcreatedb = false`, `rolsuper = false`**, and neither peer nor `postgres` superuser auth is available | `pg_roles` query · `psql` peer attempts |
| **`nrna_test` was created by the Product Owner** and access granted | user action, 2026-08-06 |
| 🔴 **`phpunit.xml` and `.env.testing` both carry the SAME stale password** — authentication fails for `publicdigit_user` against `nrna_test`. `.env` holds a *different*, working password for the *same role* | `artisan tinker` connection test; a length/equality comparison that printed no secret |

### 🔴 The remaining blocker — one value in one file

`phpunit.xml:72` sets `DB_PASSWORD` as a `<server>` entry, which **overrides `.env.testing`**, so the password must be right in **both** places or removed from the tracked one.

**Two defects here, and they are different:**

1. **Stale credential** — the test password does not match the role's actual password. *Operational.*
2. 🔒 **A plaintext database password is committed to a tracked file** (`phpunit.xml:72`). *Security.* A secret in version control is a defect independent of whether it currently works — and it is the reason the credential could go stale unnoticed in two places at once. **Recorded, not silently fixed:** removing it changes how every developer's test run resolves credentials, which is a decision, not a cleanup.

---

## What is NOT claimed

* **Nothing is verified yet.** This story is `IN PROGRESS`, not `COMPLETE`. No suite has run.
* **The tests existing is not evidence they pass.** They have never executed here.
* **A green suite would not complete (b).** Automated coverage and an observed journey are different evidence; the story requires both.
* **`PBDIGIT-25`/`26`/`27`/`33` remain unverified** — all four are code claims awaiting a browser.

---

## Acceptance criteria

* [ ] `nrna_test` reachable with the credentials the test runner actually uses.
* [ ] Migrations apply cleanly to `nrna_test` — **never to `publicdigit`** (root `CLAUDE.md`: *the development database is sacred*; no `migrate:fresh`, `migrate:refresh` or `test --seed` against it).
* [ ] `Phase6EndToEndIntegrationTest` — result recorded, pass **or** fail.
* [ ] `RealWorldVotingFlowTest` — result recorded.
* [ ] `CompleteVotingFlowIntegrationTest` — result recorded.
* [ ] The Level 0 journey walked once in demo mode, with page · controller · DB rows · events · log files recorded per step.
* [ ] **Failures are recorded as findings, not repaired inside this story.** A verification story that fixes what it finds stops being a verification story — each defect becomes its own `PBDIGIT-nn`.

**The last criterion is the one most likely to be violated**, and it is the whole point: this story's product is *evidence*, and evidence is not improved by being acted upon in the same breath.

---

## Result — (a) the three suites, run 2026-08-06

| Suite | Tests | Outcome | Assertions reached |
|---|---:|---|---:|
| `RealWorldVotingFlowTest` | 2 | 🔴 **2 errors** — `column "is_voter" of relation "users" does not exist` | **0** |
| `Phase6EndToEndIntegrationTest` | 8 | 🔴 **8 errors** — `column "post_id" of relation "posts" does not exist` | **0** |
| `CompleteVotingFlowIntegrationTest` | 11 | ⚠️ **10 pass, 1 fail** — `test_middleware_order_critical` | 23 |
| **Total** | **21** | **10 errored before asserting anything · 1 failed · 10 passed** | 23 |

**Read this precisely: the 10 passing tests exercise the middleware chain, not an election.** Session isolation, locale persistence, CSRF and authentication persistence pass. **No test in any of the three suites has ever cast a vote here.** The suites were named for a journey they do not currently reach.

### Why each failed — and none of it is a test-environment artefact

**The dev and fresh-migration schemas are byte-identical on every column involved** (both checked). So these are not "the test database is wrong":

| # | Finding | Class |
|---|---|---|
| **F-1** | `users.is_voter`, `users.can_vote`, `users.wants_to_vote`, `users.has_voted` **exist in NO database** — confirmed by the Product Owner as **retired legacy columns**. The authoritative home is the **organisation- and election-scoped `voters` table** (`organisation_id`, `election_id`, `status`, `has_voted`). **But production code still references the retired flags.** → **`PBDIGIT-35`** | 🔴 **broken** |
| **F-2** | Two of the three suites are **stale**: they build fixtures from the retired `is_voter` column and from `posts.post_id`, which never existed (the table's key is `id`). **Consequence: zero automated journey coverage**, and the naming hid it. → **`PBDIGIT-36`** | 🔴 **broken** |
| **F-3** | `test_middleware_order_critical` asserts `TenantContext` is in the **`web` middleware group**. It is not — `bootstrap/app.php:38` registers it as **route middleware** with an explicit comment (*"TenantContext is registered as route middleware, NOT web prepend"*) and enforces ordering via middleware **priority** instead. **The test contradicts a deliberate, documented decision: the test is wrong, the code is right.** → **`PBDIGIT-36`** | ⚠️ **inconsistent** |
| **F-4** | The stale test credential was the **CI Postgres container's** `POSTGRES_PASSWORD`, committed in 3 workflow files, plus a **tracked `.env` backup** (`.env.back_20260419_0138`) holding `DB_PASSWORD` and `DB_ADMIN_PASSWORD`. → **`PBDIGIT-37`** | 🔒 **hygiene** |

**F-1's failure mode is the dangerous one, because it is mostly silent.** Eloquent returns `null` for an absent attribute instead of throwing, so:

* **reads** — `$user->is_voter` → `null` → **`isEligibleToVote()` returns false for every user, with no error anywhere**;
* **writes and `WHERE` clauses** → hard `SQLSTATE[42703]`.

**Your organisation-scoping point is what makes this a design finding rather than a missing-column bug:** a *global* `is_voter` flag on `users` cannot express voter status in a multi-tenant product, because eligibility is per organisation **and** per election. The columns were not lost — they were **outgrown**. **Re-adding them would be the wrong repair.**

## Result — (b) the demo journey, walked 2026-08-06

**Provisioned with `php artisan demo:setup --env=testing`** (Product Owner): 1 active demo election, 10 posts (2 national · 8 regional), 30 candidates. Walked over real HTTP against `nrna_test` with a genuine authenticated session.

**`/organisations/publicdigit/demo/start` issues a voter slug and redirects to `/v/{vslug}/…` — the slug chain is the journey a real voter takes.** The non-slug routes are convenience aliases and correctly bounce back to the current step.

| Step | Route | Result | DB after |
|---|---|---|---|
| 0 | `POST /login` | ✅ 302 → `/dashboard/welcome` | — |
| 0b | `GET /organisations/publicdigit/demo/start` | ✅ 302 → `/v/{slug}/demo-code/create`; slug issued | `demo_voter_slugs` = 1, `current_step` = 1 |
| **1** | `GET /v/{s}/demo-code/create` | ✅ 200 · renders `Code/DemoCode/Create` | `demo_codes` row created, `code1` issued, usable |
| **1.5** | `POST /v/{s}/demo-code` | ✅ 302 → agreement | **`can_vote_now` → true** |
| **2** | `GET /v/{s}/demo-code/agreement` | ✅ 200 · renders `Code/DemoCode/Agreement` | — |
| **2.5** | `POST /v/{s}/demo-code/agreement` | ✅ 302 → vote form | **`has_agreed_to_vote` → true** |
| **3** | `GET /v/{s}/demo-vote/create` | ✅ 200 · renders `Vote/DemoVote/Create`, offering **2 national posts** with candidates | — |
| **3.5** | `POST /v/{s}/demo-vote/submit` | ✅ 302 → verify | **`vote_submitted` → true** |
| **4** | `GET /v/{s}/demo-vote/verify` | ✅ 200 · renders `Vote/DemoVote/Verify` | — |
| **4.5** | `POST /v/{s}/demo-vote/final` | 🔴 **302 back to verify — the vote is NOT saved** | **`demo_votes` = 0** |
| **5** | `GET /v/{s}/demo-vote/thank-you` | 🔴 **500** | — |

**Middleware evidence (from the logs, all passing):** `VerifyVoterSlug` → `ValidateVoterSlugWindow` (30-minute window, 29.98 min remaining) → `VerifyVoterSlugConsistency` → `EnsureVoterStepOrder` → `VoteEligibility` → `EnsureRealVoteOrganisation` → `EnsureDemoElection`. **The security chain works.** Step-order enforcement was observed rejecting out-of-order access repeatedly.

### 🔴 The headline: a vote cannot be cast

**Steps 1 through 4 all work. The vote is never persisted.** `demo_votes` = 0, `demo_results` = 0.

**Cause — `PBDIGIT-38`:** `election_security_events.overlay_influence_chain` is **`NOT NULL` with no default** (`2026_05_26_000001_create_election_security_events_table.php:23` — `$table->json(...)` without `->nullable()`), while every sibling overlay column *is* nullable. `SecurityEventRecorder:52` inserts without it → `SQLSTATE[23502]` → the surrounding transaction is poisoned → `SQLSTATE[25P02] In failed sql transaction` → `store()` aborts and redirects back. **A security-audit write failure silently blocks the business operation it was meant to observe.**

⚠️ **The recorder is not demo-specific** — its caller is `TrustPolicyEvaluator`, on the shared trust-evaluation path. **The real voting flow is therefore likely affected too. NOT VERIFIED** — no real-election vote was attempted, and it must not be assumed.

### Two further defects found by the walk

| # | Finding | Story |
|---|---|---|
| 🔴 **A blocked journey masquerading as a rate limit** | `config('app.max_use_clientIP')` reads `MAX_USE_IP_ADDRESS`, which was **unset**. `check_ip_address()` then evaluates `0 >= null`, which is **true** in PHP — so **every** voter was blocked at step 4 with *"Voting Limit Exceeded — There are already more than  votes cast from your IP"* (note the empty number). **Proved by causation, not inference:** setting `MAX_USE_IP_ADDRESS=25` changed step 4 from a redirect to a rendered page. Also `helpers.php:112-113` shows `>` was changed to `>=`, so a limit of *n* blocks the *n*-th vote | **`PBDIGIT-39`** |
| 🔴 **Step 5 always 500s** | `DemoVoteController::thankyou()` (`:3142`) renders `'vote' => $vote` where **`$vote` is never defined**. The route's `thankYou` resolves to it (PHP method names are case-insensitive), so the completion page has never worked | **`PBDIGIT-40`** |

### Minor observations — recorded, not stories of their own

* **Malformed vote payloads return 500, not 422.** Validation is `nullable|array`; `sanitize_selection()` (`DemoVoteController:1254`) then indexes elements as arrays, so a flat list of IDs throws `Cannot access offset of type string on string`. **This was my own bad payload, not an app defect** — but a 500 where a 422 belongs is a real robustness gap. → noted in `PBDIGIT-39`.
* **Two routes share the name `election.select`** (`routes/election/electionRoutes.php:41` and `routes/web.php:205`); the later registration silently wins. → noted in `PBDIGIT-41`.
* **`demo:setup` prints a stale access URL** (`/election/demo/start`; the real route is `/organisations/{slug}/demo/start`). → `PBDIGIT-41`.
* **`TWO_CODES_SYSTEM` is effectively off here** — the log says *"Using first verification code for second verification"*, `code_to_save_vote` stays `NULL`, and step 4.5 accepts code1. Consistent with configuration, **not** a defect; recorded because the two-code design in the root `CLAUDE.md` is not what this environment exercises.

### What I nearly reported and did not

**"The vote form offers zero posts."** It offers two. The `posts` prop is `{national: […], regional: […]}`, and my extractor looked for `national_posts`/`regional_posts`. **My bug, not the app's.** Likewise `voter_slugs` was empty because demo slugs live in `demo_voter_slugs`. Both were checked before reporting — the same discipline that the `is_voter` finding in (a) required, applied in the opposite direction.

**Regional posts legitimately offered 0** — the test user has `region = null`, and regional posts filter by voter region. Expected behaviour, not a gap.

## Environment additions needed for (b) — all in `.env.testing`, none committed

`phpunit.xml` supplies test config that `artisan serve` never reads, so serving the app in the testing environment required three keys `.env.testing` did not have: **`APP_KEY`** (without it every request 500s), **`MAIL_MAILER=log`** (it pointed at real Mailgun SMTP, so step 3.5's verification email failed), and **`MAX_USE_IP_ADDRESS`**. **This is the same split-configuration defect as the password** — one environment's truth living in a file only one runner reads. → `PBDIGIT-37`.

## Verdict

**(b) is COMPLETE as an observation and NEGATIVE as a result.** The journey was walked end to end for the first time in this repository. **Steps 1–4 work; the vote cannot be saved; the completion page cannot render.**

> **`PBDIGIT-00` has done its job: it converted "all 24 stories are implemented" into "the journey stops at step 4.5, for three specific reasons."** No story may be marked `VERIFIED` on the strength of this run.

## Superseded — the earlier partial probe (kept for the record)

**Verified by HTTP against a dev server on port 8123 (started and stopped within this story):**

| Step | Result |
|---|---|
| `GET /` | ✅ **200, 89 550 bytes** — the home page renders. *This also closes `PBDIGIT-27`'s last open box.* |
| `GET /login` | ✅ 200 |
| `/demo/code/create` · `/demo/code/agreement` · `/demo/vote/create` · `/demo/vote/verify` · `/demo/vote/thank-you` | ✅ all exist; all **302 → `/login`** unauthenticated — the gate works |
| `GET /dashboard` unauthenticated | **200, not a redirect** — renders `Welcome` with `user: null`. **Checked for exposure: none.** No `elections`, `organisations` or `stats` props; only public page scaffolding. **This is NOT an authorization defect** — it is a graceful guest fallback that answers 200 where 302 would be more conventional. Recorded so nobody re-discovers it as a vulnerability |

### What (b) still needs

**An authenticated walk requires a user and a seeded demo election.** Both available routes to that are currently closed:

* seeding the **dev** database would write to it — out of bounds without authorisation (root `CLAUDE.md`: *the development database is sacred*), and unnecessary;
* the **test** database is schema-only — `migrate` ran, no seeders.

**So (b) needs one authorised decision: seed `nrna_test` (or a third scratch database) with a demo election, then walk it.** That is the immediate next action and it is small.

## Environment repairs made inside this story — and why they were in scope

**These were not fixes to what the story found; they were what made running possible at all.** Recorded so the distinction is auditable:

1. **`nrna_test` created** by the Product Owner (`publicdigit_user` has `rolcreatedb = false`).
2. **The test password now lives in exactly one place** — `.env.testing`. It had been in **three**, all stale and mutually invisible: `phpunit.xml:72`, `tests/bootstrap-test-database.php:23`, and `.env.testing`. **The bootstrap copy won every time**, because it sets `$_SERVER`, `$_ENV` *and* `putenv()` before Laravel boots, and Laravel's env repository is immutable — so fixing either of the other two changed nothing, which is exactly why the staleness survived.
3. **`DB_DATABASE` forcing in the bootstrap was deliberately KEPT.** It is load-bearing *safety*: `tests/TestCase.php:28` skips transaction isolation for PostgreSQL and relies on `migrate:fresh`, **which drops every table**. Pinning the database before any config loads is what stops that from ever pointing at dev. *(The comment in `TestCase.php:60-68` claiming "ZERO data corruption possible, even if wrong database is targeted" is **false for PostgreSQL** for precisely this reason — recorded in `PBDIGIT-37`.)*
4. **Dev database confirmed untouched** — 95 tables before and after.

---

**Traceability:** `docs/publicdigit/backlog/README.md` §PBDIGIT-00 (the commissioning text and the `VERIFIED` gate) · §P-1 *"no recorded end-to-end run"* · root `CLAUDE.md` (database-testing rules, demo mode) · `docs/DEMO_ELECTION_GUIDE.md` · `phpunit.xml:65–81`
