# PBDIGIT-37 — Committed test credentials, and a safety claim that is false on PostgreSQL

**Type:** Defect (hygiene · security · false documentation) · **Epic:** cross-cutting · **Created:** 2026-08-06
**Found by:** `PBDIGIT-00` finding **F-4**, plus what unblocking the run exposed

| | |
|---|---|
| **Status** | **OPEN — not authorised.** One part was already repaired *because the run could not proceed without it* (below) |
| **Customer impact** | None directly. **The risk is to the development database**, and to anyone trusting a comment that is wrong |

---

## Part 1 · The credential lived in three invisible places — REPAIRED inside `PBDIGIT-00`

**Repaired only because the suites could not run otherwise.** Recorded here so the change is auditable, not hidden in a verification story.

The test password existed in **three** places, all carrying the same stale value:

| Place | Disposition |
|---|---|
| `tests/bootstrap-test-database.php:23` | **removed** — this copy won every time (see below) |
| `phpunit.xml:72` | **removed** |
| `.env.testing` | **kept — the single home** |

**Why the staleness survived so long, and it is worth understanding:** the bootstrap sets `$_SERVER`, `$_ENV` **and** `putenv()` before Laravel boots, and Laravel's env repository is **immutable**. So the bootstrap copy overrode everything, and **fixing either of the other two changed nothing** — the fix appeared not to work, which is the most discouraging possible failure mode.

> **Three homes for one value is not redundancy. It is one home plus two decoys.**

The stale value's origin: it is the **CI Postgres service container's** `POSTGRES_PASSWORD`. Someone copied a CI-only credential into local config, where it was never valid.

## Part 1b · 🔴 `.env.testing` IS TRACKED BY GIT — the single home is a committed file

**Found while preparing the `PBDIGIT-00` commit, and it changes Part 1's conclusion.**

`.gitignore:6` ignores **`.env`** only. **`.env.testing` is tracked** (`git ls-files` confirms). So consolidating the password into `.env.testing` moved it into *one* place — **and that place is version control.**

**Consequence, stated plainly:** the `PBDIGIT-00` run wrote the **live development database password** into `.env.testing`. **That change was deliberately NOT committed.** It remains an uncommitted working-tree modification, so no live credential entered history.

### ✅ RESOLVED 2026-08-06 — the Product Owner authorised it by adding `.env.testing` to `.gitignore`

**Done:**

1. ✅ `git rm --cached .env.testing` — untracked; the local file is kept.
2. ✅ `.gitignore` carries `.env.testing` (trailing whitespace trimmed).
3. ✅ **`.env.testing.example`** added — tracked, 10 keys, **`DB_PASSWORD` and `APP_KEY` blank**; only non-sensitive environment-defining values are filled in.

**⚠️ One consequence every developer must know:** because a tracked file was removed, **the next `git pull` deletes `.env.testing` from their working tree.** Each developer must run `cp .env.testing.example .env.testing` once and fill in the two blanks. **This is expected, not a fault** — but it is silent, and it will look like a broken checkout to anyone not told.

**History note:** `.env.testing`'s committed history contains only the **stale CI throwaway** value, never the live password (the live value was never committed). So untracking is sufficient; **no history rewrite is indicated.**

**Still outstanding:** `.env.back_20260419_0138` remains **tracked** — see Part 2. `.gitignore` does not untrack an already-tracked file, so ignoring `.env.*` patterns would not remove it.

⚠️ **History note:** `.env.testing`'s committed history already contains the **stale CI throwaway** value, not a live secret — so untracking is sufficient and a history rewrite is not indicated.

## Part 2 · Credentials committed to the repository — NOT repaired

**Still present, deliberately left for a decision:**

| File | Content |
|---|---|
| `.github/workflows/greenfield-merge-gate.yml:33` | `POSTGRES_PASSWORD: 'Rudolfvogt%27%'` |
| `.github/workflows/greenfield-quality-tier.yml:33` | same |
| `.github/workflows/role-permission-verification.yml:35,52` | same, plus `DB_PASSWORD` |
| 🔴 `.env.back_20260419_0138:39,52` | **a tracked `.env` backup** holding `DB_PASSWORD` and `DB_ADMIN_PASSWORD` |

**Honest severity assessment — the string is not a live secret.** In CI it is the password the *ephemeral* `postgres:15` service container is created with, self-consistent within each run. The **live** development credential is different, so nothing currently in production is exposed by it.

**It is still a defect, for two reasons that do not depend on the value being live:**

1. **A tracked `.env` backup is a pattern that leaks the next time.** This one happens to hold a throwaway; the practice guarantees a real one eventually.
2. It is how the stale credential propagated into local config in the first place.

**Not repaired here because the changes are not equivalent:**

* deleting `.env.back_20260419_0138` from the working tree is trivial — **removing it from history is not**, and that is a decision with force-push consequences;
* changing the CI password means changing the **service** and every **client** reference in three workflows together. A partial change breaks the merge gate — and `composer merge-gate` is a blocking gate.

**Recommendation:** move CI credentials to GitHub Actions secrets, delete the tracked `.env` backup, and decide separately whether history rewriting is warranted (**probably not**, since the value is a throwaway).

## Part 3 · 🔴 A safety comment that is false on PostgreSQL

`tests/TestCase.php:60-68` states:

> *"SAFETY GUARANTEE: ✓ Each test runs in its own transaction ✓ Transaction is rolled back after test completes ✓ **ZERO data corruption possible, even if wrong database is targeted**"*
> *"Still configure .env.testing with DB_DATABASE=nrna_test **for clarity** … This is **documentation, not a hard requirement**."*

**Both claims are false for PostgreSQL, and this project runs on PostgreSQL.** `TestCase::beginDatabaseTransaction()` (`:28`) **returns early for the `pgsql` driver** — transaction isolation is explicitly skipped — and `RefreshDatabase` therefore falls back to **`migrate:fresh`**, which **drops every table**.

**So the truth is the exact inverse of the comment:** pinning `DB_DATABASE` is not "for clarity" — **it is the only thing standing between a test run and the destruction of whatever database is configured.**

**This is why `PBDIGIT-00` deliberately KEPT the `DB_DATABASE` forcing in the bootstrap while removing the password from it.** The two lines look alike and are opposites: one is a stale duplicate, the other is load-bearing safety.

### Why this matters more than a wrong comment usually would

A future developer reading *"ZERO data corruption possible, even if wrong database is targeted"* could reasonably delete the bootstrap's database forcing as redundant. **That single deletion would arm `migrate:fresh` against the development database.**

**Fix:** correct the comment to state what is actually true, and add an assertion that fails loudly if the configured database is not the test database — **a guarantee that is executable rather than asserted.**

## Acceptance criteria

* [ ] `tests/TestCase.php` comment corrected — no claim of transaction isolation on PostgreSQL.
* [ ] An **executable** guard: tests abort unless the connected database is the designated test database.
* [ ] CI credentials moved to GitHub Actions secrets, service and clients changed **together**.
* [ ] `.env.back_20260419_0138` removed from the working tree; history decision recorded separately.
* [ ] `.gitignore` covers `.env.*` backup patterns so the next one cannot be committed.
* [ ] `phpunit-pgsql.xml:30` reviewed — it hardcodes `postgres` and was not part of this investigation.

---

**Traceability:** `PBDIGIT-00` §Result (a) F-4 · §Environment repairs · `tests/bootstrap-test-database.php` (as amended) · `phpunit.xml:65-81` · `tests/TestCase.php:20-36,60-68` · `.github/workflows/{greenfield-merge-gate,greenfield-quality-tier,role-permission-verification}.yml` · `.env.back_20260419_0138` · root `CLAUDE.md` §Database Testing Best Practices
