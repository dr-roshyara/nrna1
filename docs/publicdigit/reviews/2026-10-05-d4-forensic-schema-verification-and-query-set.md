# D4.0 — Schema verification and the read-only forensic query set (VE-01)

**Date:** 2026-10-05 · **Gate:** D4.0 (schema verification) — **authorized by the PO 2026-10-05.**
**Status:** ✅ **D4.0 APPROVED (PO, 2026-10-05) · SQL validated on the empty test database (§7) · NOT EXECUTED against election data.** No query in this document has been run against any election data. Executing it is gate **D4.1** and needs a separate explicit authorization naming the environment.
**Supersedes:** the query *shapes* in `2026-10-05-ve01-ballot-integrity-design-and-adr-amendment-proposal.md` §9 (written from memory). This document is the verified version.
**Prepared by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering prepares; the PO decides.

D4 asks one question: **has the known VE-01 defect already affected stored real-election data?** No correction, deletion, migration or mutation.

---

## 1. Schema verified (source: migrations + models, [S])

| Table | Columns used | Facts that shape the queries |
|---|---|---|
| `elections` | `id`, `name`, `type` | `type` ∈ `demo`/`real` (`2026_03_05_000004`). Real ballots live only in `votes`/`results`; demo in `demo_*` → all queries filter `type = 'real'`. |
| `posts` | `id`, `election_id`, `is_national_wide`, `state_name`, `required_number`, `deleted_at` | `required_number` **NOT NULL integer** (`000005`). `state_name` nullable. Soft deletes. |
| `candidacies` | `id`, `post_id`, `status`, `deleted_at` | `status` string, default `pending`; values in code: `draft`, `pending`, `approved`, `rejected`, `withdrawn` (`Candidacy.php:22–26`). Soft deletes. |
| `votes` | `id`, `election_id`, `deleted_at` | No `user_id`. Also holds `receipt_hash`, `participation_proof`, `encrypted_vote`, `device_*`, `metadata`, `cast_at`, timestamps — **never selected** (§3). |
| `results` | `id`, `vote_id`, `election_id`, `post_id`, `candidacy_id`, `no_vote`, `position_order`, `deleted_at` | `candidacy_id` **nullable** since `2026_03_11` (NULL = abstention row, `no_vote = true`); `post_id`, `vote_id`, `election_id` NOT NULL with FKs; also holds `receipt_hash` — **never selected**. |
| `codes` | `election_id`, `user_id`, `has_voted`, `deleted_at` | Unique (`election_id`, `user_id`); `has_voted` is the E4 participation CAS flag. Voter-side — used only in F8b, aggregated. |
| `users` | `region` | Free text, nullable. Used only in F8b, aggregated. |

**How the rows are written (source):** `Vote::createResultsFromCandidates()` (`app/Models/Vote.php:61`) decodes each `candidate_NN` JSON column and creates one `results` row per `candidacy_id` (`no_vote = false`) or one abstention row (`candidacy_id = NULL`, `no_vote = true`); `post_id` and `candidacy_id` come **from the decoded session payload**; `election_id` is copied from the vote; `position_order` = the column number `NN`. It force-deletes existing rows for the vote first.

**How the tally reads them (source):** `ResultController::index` uses `DB::table('results')` filtered by `election_id`, `candidacy_id IS NOT NULL`, grouped by (`post_id`, `candidacy_id`) — **no `deleted_at` filter**, so soft-deleted result rows are counted in the published tally. The queries therefore count **all** rows and split live / soft-deleted.

**Engine:** the project documents PostgreSQL; `config/database.php` falls back to `mysql` when `DB_CONNECTION` is unset (`.env` not read, by rule). The queries avoid engine-specific aggregates (no `bool_or`, `FILTER`) and use `SUM(CASE …)`; only the transaction wrapper differs (§4).

## 2. Corrections to the §9 draft

| Draft | Correction |
|---|---|
| F1 compared `posts.election_id` with `results.election_id` only | kept, plus **F1b** `results.election_id` vs `votes.election_id` (should be impossible; sanity) |
| F7 used `bool_or` | portable `SUM(CASE …)` |
| — | **New F10:** the same post in more than one ballot position of one vote (T1 "same post twice" path) — detectable through distinct `position_order` |
| F5 status only | also split by soft-deleted candidacy / post |
| F8 single comparison | split into **two independent queries** (F8a ballot side, F8b voter side), **never joined**; compared by the reviewer |
| F9 | adds soft-deleted counts and `election_memberships`-independent participation from `codes` |

## 3. Anonymity review (binding — every query passed)

1. **No query joins** `votes`/`results` with `codes`, `users`, `election_memberships`, `voter_slugs` or any voter-side table. F8a (ballot side) and F8b (voter side) are separate statements.
2. **Never selected:** `vote_id`, `results.id`, `receipt_hash`, `vote_hash`, `participation_proof`, `encrypted_vote`, `device_fingerprint_hash`, `device_metadata_anonymized`, `metadata`, `cast_at`, `created_at`/`updated_at` of votes and results (timestamps could be correlated with `code1_used_at` / `voted_at`), `user_id`, any name or email.
3. **Output = counts only**, keyed by election (`id`, `name`) and, where needed, post / candidacy status / region. No row-level output. `vote_id` appears only inside sub-queries, never in a result column.
4. **Small cells:** F8b gives per-region participation counts (participation, not ballot content). In the written report, cells < 5 are shown as `<5`.
5. Per-vote region checks are **impossible by design** (would need a voter↔ballot link) — replaced by F8a/F8b.

## 4. Execution protocol (for D4.1 — not now)

- Run by a person with database access to the authorized environment (this session does not read `.env` and holds no staging/production credentials). Prefer a read-only database role.
- Use `psql` (or another plain SQL client), **not** Laravel / `artisan tinker` (§7 finding). First statement after the wrapper: `SHOW transaction_read_only;` — must return `on`, otherwise stop.
- **Never** a development or test database for D4.1: generated votes would make the result meaningless (§6).
- Wrapper (PostgreSQL): `BEGIN; SET TRANSACTION READ ONLY; <queries> ROLLBACK;` — MySQL: `START TRANSACTION READ ONLY; <queries> ROLLBACK;`
- Paste the output (counts) back; engineering writes the D4 report under the §3 rules.
- Environment order (PO): **staging first** if it holds a representative copy of real ballot data; production only after review.

## 5. The query set (PostgreSQL; portable except the wrapper)

```sql
BEGIN;
SET TRANSACTION READ ONLY;

-- F9  Reference totals per real election (each table aggregated separately; no cross-table join on voters)
SELECT e.id AS election_id, e.name,
  (SELECT COUNT(*) FROM votes v   WHERE v.election_id = e.id AND v.deleted_at IS NULL)     AS votes_live,
  (SELECT COUNT(*) FROM votes v   WHERE v.election_id = e.id AND v.deleted_at IS NOT NULL) AS votes_soft_deleted,
  (SELECT COUNT(*) FROM results r WHERE r.election_id = e.id AND r.deleted_at IS NULL)     AS results_live,
  (SELECT COUNT(*) FROM results r WHERE r.election_id = e.id AND r.deleted_at IS NOT NULL) AS results_soft_deleted,
  (SELECT COUNT(*) FROM codes c   WHERE c.election_id = e.id AND c.has_voted = true AND c.deleted_at IS NULL) AS participation_consumed
FROM elections e
WHERE e.type = 'real'
ORDER BY e.name;

-- F1  Result row whose post belongs to another election
SELECT r.election_id, COUNT(*) AS rows_post_of_other_election
FROM results r JOIN posts p ON p.id = r.post_id
JOIN elections e ON e.id = r.election_id AND e.type = 'real'
WHERE p.election_id <> r.election_id
GROUP BY r.election_id;

-- F1b Result row whose election differs from its vote's election (sanity; expected 0)
SELECT r.election_id, COUNT(*) AS rows_election_mismatch
FROM results r JOIN votes v ON v.id = r.vote_id
JOIN elections e ON e.id = r.election_id AND e.type = 'real'
WHERE v.election_id <> r.election_id
GROUP BY r.election_id;

-- F2  Candidacy that does not belong to the result row's post
SELECT r.election_id, COUNT(*) AS rows_candidacy_of_other_post
FROM results r JOIN candidacies c ON c.id = r.candidacy_id
JOIN elections e ON e.id = r.election_id AND e.type = 'real'
WHERE c.post_id <> r.post_id
GROUP BY r.election_id;

-- F3  Same candidacy more than once in one vote
SELECT d.election_id, COUNT(*) AS votes_with_duplicate_candidacy, SUM(d.n - 1) AS surplus_rows
FROM (SELECT r.election_id, r.vote_id, r.candidacy_id, COUNT(*) AS n
      FROM results r JOIN elections e ON e.id = r.election_id AND e.type = 'real'
      WHERE r.candidacy_id IS NOT NULL
      GROUP BY r.election_id, r.vote_id, r.candidacy_id
      HAVING COUNT(*) > 1) d
GROUP BY d.election_id;

-- F4  More candidacies than the post's maximum in one vote (current required_number)
SELECT s.election_id, s.post_id, COUNT(*) AS votes_over_maximum, SUM(s.n - s.max_n) AS surplus_rows
FROM (SELECT r.election_id, r.vote_id, r.post_id, COUNT(r.candidacy_id) AS n, MAX(p.required_number) AS max_n
      FROM results r JOIN posts p ON p.id = r.post_id
      JOIN elections e ON e.id = r.election_id AND e.type = 'real'
      GROUP BY r.election_id, r.vote_id, r.post_id
      HAVING COUNT(r.candidacy_id) > MAX(p.required_number)) s
GROUP BY s.election_id, s.post_id;

-- F5  Result rows on non-electable candidacies (CURRENT status; may differ from status at cast)
SELECT r.election_id, c.status,
       SUM(CASE WHEN c.deleted_at IS NOT NULL THEN 1 ELSE 0 END) AS rows_candidacy_soft_deleted,
       COUNT(*) AS rows_total
FROM results r JOIN candidacies c ON c.id = r.candidacy_id
JOIN elections e ON e.id = r.election_id AND e.type = 'real'
WHERE c.status <> 'approved' OR c.deleted_at IS NOT NULL
GROUP BY r.election_id, c.status;

-- F5b Result rows on soft-deleted posts
SELECT r.election_id, COUNT(*) AS rows_post_soft_deleted
FROM results r JOIN posts p ON p.id = r.post_id
JOIN elections e ON e.id = r.election_id AND e.type = 'real'
WHERE p.deleted_at IS NOT NULL
GROUP BY r.election_id;

-- F6  Void votes: a vote with no result rows at all
SELECT v.election_id, COUNT(*) AS votes_without_results
FROM votes v JOIN elections e ON e.id = v.election_id AND e.type = 'real'
WHERE NOT EXISTS (SELECT 1 FROM results r WHERE r.vote_id = v.id)
GROUP BY v.election_id;

-- F7  Abstention and candidates for the same post in one vote
SELECT a.election_id, COUNT(*) AS vote_posts_abstain_and_select
FROM (SELECT r.election_id, r.vote_id, r.post_id
      FROM results r JOIN elections e ON e.id = r.election_id AND e.type = 'real'
      GROUP BY r.election_id, r.vote_id, r.post_id
      HAVING SUM(CASE WHEN r.candidacy_id IS NULL THEN 1 ELSE 0 END) > 0
         AND SUM(CASE WHEN r.candidacy_id IS NOT NULL THEN 1 ELSE 0 END) > 0) a
GROUP BY a.election_id;

-- F10 Same post in more than one ballot position of one vote
SELECT m.election_id, COUNT(*) AS vote_posts_in_multiple_positions
FROM (SELECT r.election_id, r.vote_id, r.post_id
      FROM results r JOIN elections e ON e.id = r.election_id AND e.type = 'real'
      GROUP BY r.election_id, r.vote_id, r.post_id
      HAVING COUNT(DISTINCT r.position_order) > 1) m
GROUP BY m.election_id;

-- F8a BALLOT SIDE ONLY: votes touching each regional post
SELECT p.election_id, p.id AS post_id, p.state_name, COUNT(DISTINCT r.vote_id) AS votes_on_post
FROM posts p JOIN elections e ON e.id = p.election_id AND e.type = 'real'
LEFT JOIN results r ON r.post_id = p.id
WHERE p.is_national_wide = false
GROUP BY p.election_id, p.id, p.state_name;

-- F8b VOTER SIDE ONLY (separate statement, never joined with F8a): consumed participation per current region
SELECT c.election_id, TRIM(COALESCE(u.region, '')) AS region, COUNT(*) AS participation_consumed
FROM codes c JOIN users u ON u.id = c.user_id
JOIN elections e ON e.id = c.election_id AND e.type = 'real'
WHERE c.has_voted = true AND c.deleted_at IS NULL
GROUP BY c.election_id, TRIM(COALESCE(u.region, ''));

ROLLBACK;
```

**F8 reading rule (PO wording, 2026-10-05):** for each regional post P, if `F8a.votes_on_post(P)` > `F8b.participation_consumed(P.election_id, P.state_name)`, the result is a **regional inconsistency requiring investigation** — *evidence that the stored ballot-side participation count exceeds the current-region participation count* — **not** a confirmed historical VE-01 violation, because F8b uses the **current** `users.region`, which may differ from the region at the time of the cast. A voter may omit a regional post, so ≤ is expected; zero excess does not prove the absence of out-of-region votes. The comparison identifies no vote and no voter. (`BallotAssemblyService` matches `state_name = trim(users.region)`, case-sensitive.) F1–F7 and F10 give direct evidence; F8 does not.

## 6. Interpretation limits (state these in the D4 report)

- **Current, not historical:** candidacy status (F5), `required_number` (F4) and `users.region` (F8b) are today's values; a non-zero count is evidence to investigate, not proof of a VE-01 exploit.
- **Generated data:** console commands write real-table votes (`CreateRandomVotesCommand`, `CreateSampleVotesCommand`, `CreateFullElectionSimulationCommand`, …). Non-production environments may contain such data; a finding there may come from a generator, not from the voting flow.
- **Zero is not proof of absence** for selections that are individually valid but outside the voter's region (F8 detects only an excess).
- **Not checkable at all** without breaking anonymity: which voter cast which ballot.

## 7. SQL validation on the empty test database (PO-authorized, 2026-10-05)

**Scope:** syntax and plan only. The test database (`nrna_test`, connection from `phpunit.xml`); no staging, production or `.env`. Method: a temporary PHPUnit probe (deleted after the run, never committed) read the SQL block of this document **verbatim** and ran it. Query text unchanged.

| Check | Result |
|---|---|
| Engine | PostgreSQL 18.3 (`pgsql`), the engine of the test environment |
| Data present | 0 committed rows in `votes`, `results`, `codes`, `users`, `elections`, `posts`, `candidacies` — no election data read |
| `EXPLAIN` (plan only) | **13 / 13 SELECT statements planned without error** (F9, F1, F1b, F2, F3, F4, F5, F5b, F6, F7, F10, F8a, F8b); every table, column, join and aggregate resolves against the migrated schema |
| Verbatim script run | **16 / 16 statements executed** (`BEGIN`, `SET TRANSACTION READ ONLY`, 13 SELECTs, `ROLLBACK`) on the empty tables |
| Read-only enforcement | `transaction_read_only = on` inside the wrapper; a write attempt through raw PDO was **refused**: `SQLSTATE[25006] cannot execute UPDATE in a read-only transaction` (on an empty table — nothing could change) |
| `EXPLAIN` executes nothing | PostgreSQL `EXPLAIN` without `ANALYZE` plans only and does not run the statement (documented behaviour); `ANALYZE` was not used |
| Runtime | **Not estimable here.** The plans were built on empty tables (estimated costs 19–53 units are meaningless for real volumes). Every query scans `results` once or twice for real elections; expected scale is linear in `results` rows |

**Finding during validation (tooling, not query semantics):** inside the same read-only transaction, the probe's first write attempt through Laravel's `Connection::update()` returned without an error, while the identical statement through raw PDO was refused. A follow-up run of the Laravel path ended in `25P02 current transaction is aborted`. The cause was **not** investigated further (out of scope). PostgreSQL's enforcement itself is proven by the raw-PDO refusal. **Consequence for D4.1:** run the script with `psql` (or another plain SQL client) under a read-only role, **not** through Laravel / `artisan tinker`, and start the session with `SHOW transaction_read_only;` (expected `on`).

**MySQL:** not validated (no MySQL test database). Static differences only: the wrapper is `START TRANSACTION READ ONLY; … ROLLBACK;` (MySQL rejects `SET TRANSACTION READ ONLY` inside an open transaction); `true` is accepted as `1`; all other constructs (`COUNT(DISTINCT)`, scalar sub-queries, `SUM(CASE …)`, `TRIM`, `COALESCE`, `HAVING` on aggregates) are standard. The migrations branch on `pgsql`, and the project documents PostgreSQL, so MySQL is the unlikely case.

## 8. Expected output columns

| Query | Columns |
|---|---|
| F9 | `election_id`, `name`, `votes_live`, `votes_soft_deleted`, `results_live`, `results_soft_deleted`, `participation_consumed` |
| F1 / F1b / F2 / F5b / F6 / F7 / F10 | `election_id`, one count column (`rows_post_of_other_election` / `rows_election_mismatch` / `rows_candidacy_of_other_post` / `rows_post_soft_deleted` / `votes_without_results` / `vote_posts_abstain_and_select` / `vote_posts_in_multiple_positions`) |
| F3 | `election_id`, `votes_with_duplicate_candidacy`, `surplus_rows` |
| F4 | `election_id`, `post_id`, `votes_over_maximum`, `surplus_rows` |
| F5 | `election_id`, `status`, `rows_candidacy_soft_deleted`, `rows_total` |
| F8a | `election_id`, `post_id`, `state_name`, `votes_on_post` |
| F8b | `election_id`, `region`, `participation_consumed` |

A query with no anomalies returns **no rows** (F1–F7, F10) — reported as `0`.

## 9. Gate status

| Gate | Status |
|---|---|
| D4.0 schema verification | ✅ **APPROVED** by the PO (2026-10-05), F8 wording applied |
| SQL validation (`EXPLAIN`, test DB) | ✅ done (§7) — 13/13 planned, 16/16 executed, read-only proven via raw PDO |
| D4.1 read-only execution | ⛔ not authorized — needs PO authorization + named environment |
| D6 / PBDIGIT-236 | open, independent of D4 |
| ADR amendment (I1, I11–I13) | approved in principle; formal revision act pending |
| VE-01 GREEN · T2–T9 | not authorized / on hold |
