# Investigation — the `Failed asserting that false is true` cluster

**Date:** 2026-08-07 · **Status:** INVESTIGATION ONLY — **no production code, test or fixture changed**
**Commissioned by:** the Product Owner, as the last unexplained cluster of the Election Failure Estate Assessment
**Predecessor:** eligibility cluster CLOSED (`b6c82ee8`) — not revisited

---

## Headline: this is not a cluster, and the message is not the failure

**`Failed asserting that false is true` is PHPUnit's generic message for a failed HTTP assertion** (`assertRedirect`, `assertSessionHas`, …). **The real cause is always printed on the line above it**, and reading that line dissolves the "22 unknowns" into four unrelated causes.

**22 occurrences across 20 distinct tests** (2 tests emit it twice — counted properly this time, after the eligibility cluster taught that lesson).

| Real message (the line above) | Count | Nature |
|---|---|---|
| `Expected response status code […] but received 403` | **7** | authorisation |
| `Session is missing expected key [errors]` | **7** | **almost certainly the same root** — a 403 short-circuits before validation, so no error bag is ever flashed |
| `The expected [ElectionReadyForActivation] notification was not sent` | **3** | notification |
| `User should be assigned to election in election-only mode` | **2** | **same family as the just-closed eligibility cluster** |
| `Session is missing expected key [success]` / `[info]` | **2** | flash messaging, downstream of the above |
| *(residual)* | 1 | `ElectionModelLockAndAuditTest` — not yet read |

> **Nothing in this cluster involves `status`, `is_active`, or any legacy election-state representation.** No test here was reached by the `PBDIGIT-48` migration.

## Sub-cause A — the 403 group (7 + 7 = **14 of 22**)

**Where it happens:** `POST organisations/{organisation}/elections` → `ElectionManagementController::store()`.

**Two candidate gates, one eliminated:**

| Gate | Behaviour | Verdict |
|---|---|---|
| `EnsureOrganisationMember` middleware | checks `$user->organisationRoles()` — the same table the fixture populates — and **redirects** for non-members (403 only when `expectsJson`) | **eliminated** — the tests are not JSON requests, and a redirect would satisfy `assertRedirect` |
| `$this->authorize('create', [Election::class, $organisation])` → `ElectionPolicy::create()` | requires a `UserOrganisationRole` with role `owner`/`admin` | **the remaining candidate** |

**But the fixture satisfies that policy on inspection:** `createUserWithRoleInOrg()` creates the user with `email_verified_at` set and a `UserOrganisationRole` with the required role and organisation; `UserOrganisationRole` carries **no global scopes** (checked at runtime, because the `PBDIGIT-62` defect was exactly a hidden scope).

**⚠️ CLASSIFICATION: G — UNKNOWN.** The fixture and the policy both look correct, and the discrepancy is not yet explained. **I will not guess between "stale fixture" and "production defect" for an authorisation gate** — that direction is precisely what the Product Owner warned must be read, never assumed.

**Named next probe:** assert `ElectionPolicy::create()` directly against the fixture's user and organisation, then compare with what `authorize()` resolves at request time (policy registration, route-model binding of `$organisation`, and the `Election::class` vs instance form of the `authorize` call). That isolates fixture-vs-production in one step, without changing anything.

## Sub-cause B — election-only assignment (**2 of 22**)

`ElectionOnlyModeTest` — *"User should be assigned to election in election-only mode"*. **Same shape as the cluster closed in `b6c82ee8`**: a test intending election-only mode against an organisation whose factory default is full-membership. **Classification: C — invalid fixture (high confidence, unverified).** Likely repaired by the same one-line mode declaration; **not attempted here**.

## Sub-cause C — notifications (**3 of 22**)

`ElectionActivationTest` — `ElectionReadyForActivation` not dispatched. **Classification: G — UNKNOWN.** Could be a fixture that never reaches the dispatch condition, or a genuine notification regression. **Unrelated to election state**; needs its own trace.

## Sub-cause D — flash messaging (**2 of 22**)

Missing `success` / `info` keys. **Almost certainly downstream of sub-cause A** — a request that 403s flashes nothing. Re-assess after A is resolved.

## Classification against the commission's categories

| Category | Finding |
|---|---|
| **A — caused by `PBDIGIT-48` / legacy-state removal** | **0.** No legacy representation appears anywhere in this cluster |
| **B — stale test expectation** | **0 confirmed** |
| **C — invalid fixture** | **2 likely** (sub-cause B) |
| **D — genuine production defect** | **0 confirmed** — 14 remain possible via sub-cause A |
| **E — unrelated infrastructure** | **0 confirmed** |
| **G — UNKNOWN** | **17** (14 in A, 3 in C) |

## DECISION REQUIRED — none yet

**No Product Owner decision is required by this investigation.** No conflict between a legacy representation and `ElectionLifecycle` was found, because **no legacy representation is involved in this cluster at all**.

## Recommended next step

**Isolate sub-cause A**, because it is 14 of 22 and the only one whose two possible answers differ in severity (fixture nuisance vs authorisation defect). One targeted probe, described above, decides it. **Sub-causes B, C and D should not be touched until A is known**, since D is probably downstream of A.

## STOP

**Nothing modified.** The 22 "unknowns" are now **17 unknowns across three unrelated causes**, with one sub-cause reduced to a single named question and 5 attributed.

**The durable lesson: an aggregate error message is not a root cause.** Clustering by PHPUnit's generic assertion text created a 22-test mystery that did not exist; the real messages were one line away the whole time.
