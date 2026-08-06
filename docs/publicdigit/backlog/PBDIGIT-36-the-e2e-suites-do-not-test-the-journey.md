# PBDIGIT-36 — The "end-to-end" suites do not test the journey

**Type:** Defect (test coverage) · **Epic:** cross-cutting · **Created:** 2026-08-06
**Found by:** `PBDIGIT-00` findings **F-2** and **F-3**

| | |
|---|---|
| **Status** | **OPEN — not authorised** |
| **Customer impact** | **None directly — and that is the point.** Three suites named for the voting journey provided **zero** protection for it, while looking like they did |
| **Severity** | 🔴 High as *risk*: every "✅ implemented" claim in the backlog rested partly on these existing |

---

## What the run showed

| Suite | Tests | Outcome | Assertions |
|---|---:|---|---:|
| `tests/Feature/RealWorldVotingFlowTest.php` | 2 | 🔴 2 errors | **0** |
| `tests/Feature/Phase6EndToEndIntegrationTest.php` | 8 | 🔴 8 errors | **0** |
| `tests/Integration/CompleteVotingFlowIntegrationTest.php` | 11 | ⚠️ 10 pass, 1 fail | 23 |

**10 of 21 tests never reached a single assertion** — they died building fixtures. The 10 that pass exercise the **middleware chain** (session isolation, locale persistence, CSRF, auth persistence), which is worth having and is **not** an election.

> **No test in any of these three suites has ever cast a vote in this repository.**

## The three distinct causes

### F-2a · `RealWorldVotingFlowTest` — builds users from a retired column

`RealWorldVotingFlowTest.php:52` creates a user whose factory writes **`is_voter`**, a **retired legacy column** (`PBDIGIT-35`). Fails at fixture creation with `SQLSTATE[42703]`.

**The fix is not to re-add the column** — it is to build the fixture the way the product now models voters: an organisation- and election-scoped `voters` record. **So this suite cannot be repaired before `PBDIGIT-35` decides where eligibility lives.**

### F-2b · `Phase6EndToEndIntegrationTest` — writes `posts.post_id`, which never existed

`Phase6EndToEndIntegrationTest.php:67` writes **`post_id`** on `posts`. The table's key is **`id`**; `post_id` is the *foreign* key other tables use (`candidacies.post_id`, `results.post_id`) — which `app/Models/Post.php` gets right. All 8 tests die identically.

⚠️ **Note for whoever repairs this:** the root `CLAUDE.md` domain model documents `POST` with a `post_id` attribute. **The documentation and the schema disagree**, and the schema is what runs. Worth correcting the doc in the same slice.

### F-3 · `CompleteVotingFlowIntegrationTest::test_middleware_order_critical` — the test is wrong, the code is right

The test asserts `TenantContext` appears in the **`web` middleware group** (`:271`). It does not, **by deliberate design**:

* `bootstrap/app.php:38` — *"✅ TenantContext is registered as route middleware, NOT web prepend."*
* Ordering is enforced by **middleware priority** (`StartSession → TenantContext → SubstituteBindings`), not by group position.

**This is the most instructive failure of the three.** The test encodes an architecture the repository deliberately moved away from, and it failed *because the code is correct*. Repair = fix the test to assert the property that actually matters (priority order), not the class's position in a list.

> **This is `PBDIGIT-29` F-2 recurring: a test can lock in the wrong question.** Here it locked in the wrong *mechanism* — which is why the method rule says a test must name the rule it protects.

## Acceptance criteria

* [ ] `PBDIGIT-35` settles where voter eligibility lives — **prerequisite for F-2a**.
* [ ] `RealWorldVotingFlowTest` builds voters through the authoritative mechanism, and reaches its assertions.
* [ ] `Phase6EndToEndIntegrationTest` uses `posts.id`; the `CLAUDE.md` domain model corrected to match the schema.
* [ ] `test_middleware_order_critical` asserts **middleware priority**, and names the property it protects.
* [ ] **At least one test actually casts a vote end-to-end** and asserts the anonymity invariant (no voter↔vote linkage, ADR-T11). *(Now possible: `PBDIGIT-38` is fixed, and `tests/Feature/AuditEventTransactionIsolationTest.php` shows the shape.)*
* [ ] The suites' names match what they cover — **or they are renamed.** A suite called `…EndToEnd…` that tests middleware is a false signal, and false signals are worse than absent ones.

## Explicitly out of scope

**Do not repair these by loosening assertions or skipping tests.** A skipped test is an honest absence; a weakened test is a false claim. If a test cannot be made meaningful, delete it and record why.

---

**Traceability:** `PBDIGIT-00` §Result (a) F-2, F-3 · `PBDIGIT-35` (the retired flags — prerequisite) · `tests/Feature/RealWorldVotingFlowTest.php:52` · `tests/Feature/Phase6EndToEndIntegrationTest.php:67` · `tests/Integration/CompleteVotingFlowIntegrationTest.php:271` · `bootstrap/app.php:38,124-130` · `PBDIGIT-29` F-2 · root `CLAUDE.md` domain model
