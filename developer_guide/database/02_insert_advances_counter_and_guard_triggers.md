# 02: Counters advanced by the insert, and guard triggers (the Reference Geography migrations)

**Origin:** PBDIGIT-346 G-4 (2026-11-13). **General database behaviour** that another context's append-only stream could reuse. The Geography design itself is documented in `developer_guide/reference_geography/` (guides 13 to 19).

## The migrations (central database, PostgreSQL only)

| Migration | What it adds |
|---|---|
| `2026_11_13_120000_create_refgeo_publication_stream.php` | the one-row `refgeo_sequence`; `refgeo_publications`, `refgeo_claims`, `refgeo_roster_entries`; guard and append triggers |
| `2026_11_13_130000_create_refgeo_revisions.php` | the revision aggregate tables; append-only triggers on the history tables; the `refgeo_claims (kind, value)` index |
| `2026_11_13_140000_freeze_refgeo_claims_with_before_images.php` | `refgeo_claims.before_image jsonb NOT NULL` |
| `2026_11_13_150000_close_published_refgeo_revisions.php` | a trigger refusing UPDATE or DELETE of a published revision's row |

**Release gate:** like G-0's Identity migration, these run only on test databases until the Principal approves their rollout. It is a convention in the file header, not code.

## Reusable patterns

1. **Let the insert advance the counter.**
   - A `BEFORE INSERT` trigger locks the counter row (`SELECT … FOR UPDATE`), requires `NEW.position = last + 1`, and updates the counter itself.
   - **A second trigger refuses any direct change of the counter** unless `pg_trigger_depth() ≥ 2`, meaning it comes from inside the append trigger.
   - **Why:** if the code advanced the counter and inserted separately, a forgotten insert leaves a gap and a stray insert sits ahead of the counter. Here neither can happen, whatever the application does.
2. **Refuse, never adjust, a timestamp.** The append trigger refuses a reading below the previous one, or at or below a closed boundary. A boundary may only move forward, and never past `clock_timestamp()`.
3. **Immutability by trigger.** One function, `refgeo_refuse_change()`, refuses UPDATE and DELETE on every insert-only table.
4. **Close a parent by refusing updates to its row.**
   - Every save of the aggregate updates its root row first, so a `BEFORE UPDATE OR DELETE` trigger on that row (published → refuse) closes the whole aggregate to every writer.
   - **Add a row lock in the competing transaction** (here, publication locks the revision row) so a racing save waits and then meets the trigger.
5. **Functions use `CREATE OR REPLACE`:** `migrate:fresh` drops tables but not functions (the G-0 lesson).

## Testing a guard

**Assert the refusal is a guard's:** SQLSTATE `P0001` (raised) or class `23` (integrity). Any other error (`42P01` missing table, …) must fail the test, otherwise the test passes vacuously (the G-4a lesson). See `tests/Feature/Contexts/ReferenceGeography/RefgeoSchemaGuardsTest.php`.

**Inject a failure part-way with a temporary trigger** (create, run, drop in `finally`) to prove a multi-statement write is atomic. See `PostgresRevisionRepositoryTest`, `PostgresStewardRosterStoreTest`.

## Pitfall: connection exhaustion in the full test run

Each test boots a new application, and its connections stay open until PHP collects it. The full multi-suite run hit "too many clients already". **Close connections in `tearDown`**, as `RefgeoPostgresTestCase` does.
