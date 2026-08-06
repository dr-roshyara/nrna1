# PBDIGIT-35 — Production code still reads retired global voter flags

**Type:** Defect (broken · partly silent) · **Epic:** `PBDIGIT-EPIC-02` Membership Management · **Created:** 2026-08-06
**Found by:** `PBDIGIT-00` finding **F-1** — the first defect the end-to-end run surfaced

| | |
|---|---|
| **Status** | **OPEN — not authorised.** Found by verification; **repairing it is a separate decision** |
| **Customer impact** | **Voter eligibility answers "no" for everyone, silently**, wherever the legacy flags are consulted; three admin commands abort with a database error |
| **Confirmed by** | Product Owner: *"they are not valid anymore — old legacy columns"* |

---

## What is true

`users.is_voter`, `users.can_vote`, `users.wants_to_vote` and `users.has_voted` **exist in no database** — verified against **both** the development database and a database freshly built from `database/migrations/`. They are identical, so this is not environment drift.

**Voter status lives in the `voters` table, and it is organisation- and election-scoped:**

```
voters: id, organisation_id, member_id, election_id, status,
        ineligibility_reason, has_voted, voted_at, voter_number, ...
```

**`app/Models/User.php` still carries a parallel, global eligibility surface** — ~20 references, including `$fillable`, `isEligibleToVote()`, three query scopes, and setters that assign `$this->is_voter = 1`.

## Why this is a design finding, not a missing column

**A global `is_voter` flag on `users` cannot express eligibility in a multi-tenant product.** Eligibility is per **organisation** and per **election** — the same person may be an eligible voter in one organisation and not in another, and eligible in one election but already voted in the next.

> **The columns were not lost. They were outgrown.**

**⛔ Re-adding them would be the wrong repair** — it would re-introduce the single-organisation assumption the `voters` table exists to replace.

## Two failure modes, and the quiet one is worse

| Access | Behaviour | Visibility |
|---|---|---|
| **Read** `$user->is_voter` | Eloquent returns **`null`** for an absent attribute — it does **not** throw | 🔴 **silent.** `isEligibleToVote()` (`User.php:404`) evaluates `(bool) null && (bool) null` → **false for every user, with no error anywhere** |
| **`WHERE`** on the column | `SQLSTATE[42703] Undefined column` | loud — aborts |
| **Write** `$this->is_voter = 1; save()` | `SQLSTATE[42703]` | loud — aborts |

## Confirmed sites

**Hard failures — these abort when run:**

| Site | Statement |
|---|---|
| `app/Console/Commands/BulkApproveVoters.php:47,55,56,57` | `User::where('is_voter', true)`, `where('can_vote', …)` |
| `app/Console/Commands/BulkDisapproveVoters.php:46,54,55,56,57,58` | same, plus `where('has_voted', true)` — **also retired on `users`** (it lives on `voters`) |
| `app/Console/Commands/ResetElection.php:197` | `DB::table('users')->where('can_vote', 1)` |

**Silent failure — runs on every Inertia request:**

| Site | Effect |
|---|---|
| `app/Http/Middleware/HandleInertiaRequests.php:99-100` | shares `is_voter` and `can_vote` to **every page**, always `null`. Any frontend gating on these props treats **every user as a non-voter** |

**Dead code — no callers found:** `User::scopeVoters()` (`:812`), `scopeCustomers()` (`:857`), `scopePendingVoters()` (`:868`). They would error if ever called.

**⚠️ Not fully enumerated.** Ten controllers reference these names (`UserController`, `VoterDashboardController`, `ElectionProcessController`, `DemoCodeController`, `DeligateVoteController`, `DeligateCodeController`, `SmsController`, `SitemapController`, `Admin/VotingSecurityController`, plus the middleware). **Each needs classifying as read (silent) or query/write (hard) before any repair** — this list is evidence, not a complete work breakdown.

**`codes.can_vote_now` EXISTS and is unrelated** — `EndVotingPeriod.php:63` is correct. Recorded so it is not swept into the repair by name similarity.

## What this story does NOT decide

* **Whether to delete the legacy surface or route it to `voters`** — that is design, and it needs the eligibility-authority question settled first (below).
* **The frontend consequence** — whether any Vue component actually gates on the two always-null Inertia props. **Not investigated.**

## 🔗 The connection that makes this urgent

`PBDIGIT-32` (B10) already carries an unresolved question: **which eligibility mechanism is authoritative** — `User::getActiveElection()`, or `VoterEligibilityService`/`EligibilityEvaluator`/`EligibilitySnapshot` (`PBDIGIT-EPIC-02` MB-5).

**This finding adds a third and fourth answer to that question:** the `voters` table, and the retired `User` flags. **Four surfaces claim to answer "may this person vote?" and one of them silently answers "no" for everyone.** Settling authority should therefore precede repairing sites — otherwise the repair picks a winner by accident.

---

**Traceability:** `PBDIGIT-00` §Result (a) F-1 · Product Owner confirmation 2026-08-06 · `app/Models/User.php` · `app/Http/Middleware/HandleInertiaRequests.php:99-100` · `app/Console/Commands/{BulkApproveVoters,BulkDisapproveVoters,ResetElection}.php` · `voters` table schema · `PBDIGIT-EPIC-02` MB-5 · `PBDIGIT-32` §B10 prerequisite
