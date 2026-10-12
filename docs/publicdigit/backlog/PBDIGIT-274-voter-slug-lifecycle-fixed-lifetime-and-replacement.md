# PBDIGIT-274: Voter-slug lifecycle — fixed lifetime, same slug through every step, replacement by a new row

**Type:** Story (voting lifecycle) · **Epic:** none (voting) · **Created:** 2026-10-11 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the lifecycle commission of 2026-10-11 and the Human PO decisions recorded below. **Index row:** added 2026-10-11.

| | |
|---|---|
| **Status** | `IN PROGRESS` · plan approved by the Human PO / ARB on 2026-10-11 (`docs/plans/20261011-2302-voter-slug-lifecycle-plan.md`, rev 10 + R11-1; decisions in its section 2.1a). **Slice S1 implemented (database-free tests passing).** The S1 code and tests were committed as `a78d7c2f2`, removed from HEAD by the unrelated `118193320`, restored byte-identical in recovery commit `4d83dffc0` (no history rewrite, no push, no database operation). **S1 done ≠ PBDIGIT-274 done.** **G3 (S2 migration design) approved 2026-10-12** for the isolated test database only. **G4 met 2026-10-12** (`pbdigit274_isolated_test`, isolated by `pg_hba`); `APPROVED_NAME` committed in `bacef4220`. **G5 passed 2026-10-12** (first database run: tenant isolation of voter-slug steps, 4 tests). The S2 migration needs its own explicit authorisation. Slices S2 (migration) – S9, G6, G7 and G8 remain gated; production execution of the migration needs G8. The test-database rule below is unchanged: isolated database only |
| **Blocks** | deployment of `PBDIGIT-273` |

## Decisions already made (Human PO, 2026-10-11)
- **Fixed lifetime:** `expires_at = created_at + 30 min`, never extended in place.
- **Replacement creates a new row.** The old slug is soft-deleted and kept as a record that can never resolve again; the new row gets a new random string and its own `created_at + 30 min`.
- **One active slug per `(election_id, user_id)`.** The replacement model is accepted in principle. The schema change that supports it is **PROPOSED, not approved for execution** (see "Proposed migration").
- **Replacement happens only at the authenticated voting entries** (`/vote`, `elections.start`), after the existing eligibility checks. Protected steps only reject. A still-valid slug is returned unchanged.
- **Election-specific issuance:** only a voter valid for that organisation and election may receive a slug, using the existing entitlement source (`ElectionMembership` / `VotingEntitlementQuery`), never legacy flags.
- **The voter's experience on expiry (Human PO, 2026-10-11, in session):**
  - every voting step compares the session's creation time with now; at 30 minutes or more the step is refused;
  - the voter is sent to **the election's start page**, with the message "Your voting session has expired, please start again";
  - the **organisation page** is the fallback, used only when the election start page can't be determined.

  Today an expired voter lands on the general dashboard (the `VotingException` renderer in `bootstrap/app.php`), so this changes.
- **A fresh session on "start again" is a new record** (option B, reconfirmed 2026-10-11 against the simpler "reset the same record" option). The old session is archived (soft-deleted) and a new one gets a new link, a new creation time and its own 30 minutes. This needs the migration below (approval pending) and an isolated test database.
- **Why both are needed:** without a new creation time at "start again", the voter would loop between the start page and the refused step, because the current `start()` reuses the old record with its old creation time.

## Test-database rule (corrected 2026-10-11 by the Principal Reviewer; adopted by the Human PO)
- **Database-free unit tests** may run without connecting to a database.
- **Request-path and integration tests** run only on an **explicitly provisioned, isolated test database**, and only after its identity is verified: a bootstrap that pins it and aborts unless `current_database()` matches.
- **Any setup that could wipe or modify a shared database** (`RefreshDatabase`, `migrate:fresh`) stops before it executes. No migrations against shared or production databases.
- *Record:* an earlier in-session answer ("use `nrna_test` now") is **withdrawn**. It is superseded by the PO's adoption of this review, and no test was run on that basis.

## Known defects to fix
- **`ElectionVotingController::start:166-202`** reuses the old row: it resets the slug string and `expires_at` to now+30 and leaves `created_at` unchanged. It also matches soft-deleted rows and **does not restore them**. Under `PBDIGIT-273` both cases lock the voter out.
- **In-place extensions:** `CodeController::create:179`, `VoterSlugService::getOrCreateActiveSlug:183`, `VoterSlugService::extendSlugExpiry:132-141`.
- **Three duration sources:** the hard-coded `30`, `voting.time_in_minutes`, and the undefined `voting.slug_expiration_minutes`. `config/election.php` also has a stray default of 20.
- **`/vote` route** (`electionRoutes.php:87`): calls `new VoterSlugService()` without its constructor argument (reported; to verify).
- **Concurrency:** two simultaneous entries must not create two active slugs. The partial unique index plus a transaction or lock is the evidence.
- **The binding** (`RouteServiceProvider.php:103`) uses `isPast()` (strict) and resolves soft-deleted slugs. Align it with the guard's rule, or document the guard as the authoritative check.

## Acceptance (from the commission's mandatory scenarios)
1. Creation gives exactly +30 min.
2. Repeated `/vote`, `start` and code re-issue neither change nor extend the slug.
3. The same slug is used through every step while it is valid.
4. An expired slug is rejected at every step.
5. A replacement has a new value and its own 30 minutes.
6. The old slug stays invalid.
7. No replacement is made while a slug is valid.
8. Concurrent entries produce no conflicting assignments.
9. Another voter's slug can be neither used nor replaced.
10. Election, tenant and eligibility rules can't be bypassed through replacement.

## Proposed migration (PROPOSED, not approved for execution; for review in the 274 plan)

> **Superseded (2026-10-12):** the sample below is the early proposal. The current S2 design is plan section 11 with its clarifications in section 11.0 of `docs/plans/20261011-2302-voter-slug-lifecycle-plan.md`. One difference: on PostgreSQL the old rule is a **constraint**, checked in `pg_constraint`. The plan also adds preconditions, its own transaction, a drift-safe `down()` and a lock timeout.

**Engine:**
- The project and its test bootstrap use **PostgreSQL** (`tests/bootstrap-test-database.php` pins `pgsql`). PostgreSQL supports partial unique indexes (`CREATE UNIQUE INDEX … WHERE …`).
- `config/database.php` still falls back to `mysql` when `DB_CONNECTION` is unset, and MySQL has **no** partial indexes.
- **To verify before approval:** the production `DB_CONNECTION` (not read here: `.env` is off-limits). The migrations' existing convention is to guard with `DB::getDriverName()`.

**Current index:** `voter_slugs_election_id_user_id_unique`, the Laravel default name for `$table->unique(['election_id','user_id'])` (`2026_03_05_000009_create_uuid_voter_slugs_table.php:38`). It counts soft-deleted rows, so a replacement row can't be inserted.

```php
public function up(): void
{
    if (DB::getDriverName() !== 'pgsql') {
        throw new RuntimeException('voter_slugs partial unique index requires PostgreSQL');   // fail loudly, never silently skip
    }
    Schema::table('voter_slugs', fn (Blueprint $t) => $t->dropUnique('voter_slugs_election_id_user_id_unique'));
    DB::statement('CREATE UNIQUE INDEX voter_slugs_active_election_user_unique
                   ON voter_slugs (election_id, user_id) WHERE deleted_at IS NULL');
}

public function down(): void
{
    // Restoring the plain unique index is impossible once a voter has a soft-deleted and a live row for the same
    // election. Refuse rather than delete audit rows.
    $duplicates = DB::table('voter_slugs')->select('election_id', 'user_id')
        ->groupBy('election_id', 'user_id')->havingRaw('count(*) > 1')->exists();
    if ($duplicates) {
        throw new RuntimeException('Rollback blocked: replaced slugs exist; resolve them by an explicit, reviewed data decision.');
    }
    DB::statement('DROP INDEX IF EXISTS voter_slugs_active_election_user_unique');
    Schema::table('voter_slugs', fn (Blueprint $t) => $t->unique(['election_id', 'user_id']));
}
```

**Open points for the approval:**
- whether `demo_voter_slugs`, which has no unique `(election_id, user_id)` today, gets the same rule;
- whether existing data holds any `(election_id, user_id)` pair twice (impossible under the current index; verify on the isolated database);
- the transaction and lock boundaries for concurrent entries (the index is the final guard; the service should also lock).

## Added acceptance (2026-10-11, from the redirect decision)
11. An expired session on any voting step sends a web request to the election's start page (the organisation page if the election can't be determined) with the expiry message, not to the dashboard. JSON requests keep the 403 `ExpiredVoterSlugException`.
12. "Start again" from that page creates a new session record (new link, new creation time, its own 30 minutes). The voter can then complete the steps without being sent back.
