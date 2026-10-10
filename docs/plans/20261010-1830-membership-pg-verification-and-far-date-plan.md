# Plan: PostgreSQL verification + far-date defect (next Membership increment)

**Status:** `REVISION 3`. **Part 1 APPROVED** by the Human PO / ARB (2026-10-10, accepting the Principal Reviewer's recommendation), **executed and committed** (`ff18c2a4e`, `44348f99b`, `bab1a9fbb`). **Option E AUTHORISED** for implementation (see "Option E gate register"). **Parts 2 and 3 remain PROPOSED — NOT APPROVED FOR EXECUTION.** Corrected under **ARB-MEM-05** (2026-10-10); revision 3 closes the Principal Reviewer's isolation-coverage points. Awaiting ChatGPT review and explicit Human PO / ARB approval.
**Stories:** `PBDIGIT-264` (F-1 far-date defect); evidence for the Slice 1b re-disposition (`PBDIGIT-260`, ARB-MEM-02 / ARB-MEM-04 §5). **Baseline:** HEAD `ce8bf3109`.

## Changes in revision 3 (finalised 2026-10-10, plan integrity review)

- **The role's rights in its own (isolated) database are now specified** (Part 2, "Role R in the isolated database"), so the run can create, migrate and wipe its schema there.
- **`CONNECT` / `PUBLIC` is explicit:** by default `PUBLIC` holds `CONNECT` on every database, so without I-1 R *can* connect to `nrna_test`. Protection then rests on non-ownership, which is now backed by inspected Laravel behaviour.
- **PV extended** with the isolated-database ownership checks, the `nrna_test` ACL and the server version.
- **The gate is explicit:** no Part 2 command runs before PV is accepted.

## Earlier changes in revision 3

- **Connection coverage** is stated separately as inspected evidence and as assumptions (Part 2, "Connection coverage"). No universal coverage is claimed.
- **An independent isolation boundary** comes from database permissions (Part 2, "Independent isolation boundary"), so the resolver is no longer the only safeguard.
- **Provisioning evidence (PV)** is verified independently before Part 2 is authorised. The database comment is an **identity marker, not a secret**.
- **Part 3:** identity, read-only state **and** SELECT-only privileges are verified explicitly; any failure, missing table or unexpected identity → **NOT VERIFIED**.

## Changes in revision 2

Closes Principal Reviewer finding **F-1** (under ARB-MEM-05): revision 1 verified the database on a *separate* PDO preflight, not on the connection `migrate:fresh` actually uses.
- Part 2: identity is now verified **on the actual Laravel connection**, via a `Connection::resolverFor('pgsql', …)` hook, with several independent identity checks (not the name alone), fail closed, plus server-side defence in depth.
- Part 3: the containment check runs only under **verified, enforced** read-only mode and reports a tri-state result; a missing table is never reported as zero.

## What changed from revision 0

Revision 0 isolated the database with an environment override (`DB_DATABASE=… vendor/bin/phpunit …`). That **does not work**:
- `tests/bootstrap-test-database.php` force-sets `DB_DATABASE='nrna_test'` in `$_SERVER`, `$_ENV` and `putenv`;
- `phpunit.xml` sets it unconditionally through `<server>`;
- the test base relies on `migrate:fresh`, which drops every table.

So the run would have hit the **shared** `nrna_test`. Revision 1 **enforces** isolation instead. It reuses the precedent of the Adjudication remediation gate §10.6.

## Why this increment

1. **Far-date defect (PBDIGIT-264 F-1).** `DateOrdinal::toDate()` formats any ordinal. 2932897 becomes `10000-01-01`, which `fromDate()` rejects, so the **Member becomes unloadable**.
2. **Persistence is unverified on a safe database** (ARB-SEC-01).

## Architecture (no new decision)

- Rule from 264 (already dispositioned by the Principal Reviewer): a fact with a date outside 0001-01-01 … 9999-12-31 is refused **before any write**; nothing is persisted. *(This is the story's wording; how the implementation achieves it is corrected in the next point.)*
- The guard is in the infrastructure mapping (`DateOrdinal`).
- **How the rule is met (corrected 2026-10-10, independent review):** the guard is **not** literally before every write.
  - Inside `db->transaction()` (`PostgresMemberRepository.php:136`), `appendInTransaction` first runs the version compare-and-set `UPDATE org_memberships` (lines ~154 – 159).
  - Only then is `encode()` / `toDate()` called (lines ~181 – 186, 218, 229), which is still before the fact and command `INSERT`s.
  - On an out-of-range date `toDate()` throws, and the **transaction rollback is expected** to undo the version update, leaving no fact, command or version change.
  - The guarantee therefore rests on the rollback, not on the position of the check.
  - This is inspected code plus Laravel's documented transaction behaviour; it is **not yet observed** on PostgreSQL (Part 2).
- A domain-level effectiveAt range, giving a business refusal reason, is a separate **open** decision and is not done here. It stays **unresolved**.
  - It is **not** mapped to *Slice 2 pack D-5(b)*. That item asks whether `effectiveAt` may lie before or after the decision date (backdating / scheduling) (`2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md:174 – 178`), not which calendar range is representable.
  - CONTEXT lists "D-1 … D-5(b) (… effectiveAt range)" without an explicit mapping, so the identifier is left unassigned and the ambiguity is reported to the Human PO / ARB.

## Steps, test-first

### Part 1: the far-date fix (no database)

| Step | Action | Evidence |
|---|---|---|
| 1 RED | new `tests/Unit/Contexts/Membership/Infrastructure/Organisational/DateOrdinalTest.php`: `toDate(TimePoint::of(2932896))` = `9999-12-31` and round-trips; `toDate(TimePoint::of(-719162))` = `0001-01-01` and round-trips; `toDate(TimePoint::of(2932897))` and `toDate(TimePoint::of(-719163))` throw `\InvalidArgumentException` | the failing run recorded (today it returns `10000-01-01`); commit `test(membership): … RED (PBDIGIT-264)` |
| 2 GREEN | `DateOrdinal::toDate()`: compare the ordinal **numerically** with `[-719162, 2932896]` before any date arithmetic (this also avoids `DateTime::modify` overflow for huge ordinals); throw `\InvalidArgumentException` outside the range. `fromDate()` unchanged | the passing run; commit `fix(membership): … GREEN (PBDIGIT-264)` |
| 3 regression | the three targeted groups (Domain, Application, Architecture) plus `DateOrdinalTest`, with the exit-code capture procedure (`.out` / `.exit` / `env.txt` / SHA-256) | exit 0 for each |

### Part 2: PostgreSQL verification (only after the isolated database exists and is verified)

**Gate:** no Part 2 command (and therefore no `migrate:fresh`) may run until P-DB is provisioned, the PV output is accepted (ChatGPT review plus Human PO / ARB acceptance), I-1 is decided, and Part 2 is separately approved.

**Role R in the isolated database (what the run needs).** Inspected: `database/migrations` contains no `CREATE EXTENSION`, no other schema and no other connection; every pgsql connection uses `'schema' => 'public'` (`config/database.php` lines 81, 109, 123, 149). So R needs, **only in `nrna_test_membership`**:
- ownership of the database (or `CONNECT`, `CREATE` and `TEMP` on it);
- ownership of schema `public` there (`ALTER SCHEMA public OWNER TO R`), or `USAGE` plus `CREATE` on it. PostgreSQL 15 and later no longer grants `CREATE` on `public` to `PUBLIC`;
- no superuser and no special role attributes; no extension privileges are needed.

R then owns every object the migrations create. `migrate:fresh` (`db:wipe`) drops only those objects.

**Prerequisite P-DB (provisioned by you or infrastructure; values via shell environment only, never committed, never written to files; I never read `.env`):**
- an isolated database, e.g. `nrna_test_membership`, owned by or usable by a **dedicated role**, e.g. `nrna_membership_test`;
- that role has `CONNECT` on the isolated database **only**, and **no** `CONNECT` on `nrna_test` (revoke from `PUBLIC` as needed). A mis-pin is then refused by the server itself (defence in depth);
- an **identity marker** as a database-level comment: `COMMENT ON DATABASE nrna_test_membership IS '<marker>'`. It survives `migrate:fresh`, which drops tables, not database comments. It is **not a secret**: any role with catalog read access can see it, so it identifies the database but authenticates nothing;
- the expected values handed to the run as environment variables: database name, role, marker, the database OID (`SELECT oid FROM pg_database WHERE datname = …`, recorded at provisioning), server address and port (or an explicit statement that a unix socket is expected).

**Isolation mechanism (enforced; scratch files only, in the session scratchpad, never in the repo):**
1. **Pin and self-check (`<scratch>/bootstrap-membership.php`).** A copy of `tests/bootstrap-test-database.php` that pins **every pgsql connection's** parameters to the isolated database and the dedicated role, in `$_SERVER`, `$_ENV` and `putenv`. That covers `DB_*` (used by `pgsql` and `testing`), `DB_AUDIT_*` (`pgsql_audit` falls back to `DB_*`) and `DB_PGSQL_GEO_*` (`pgsql_geo` otherwise defaults to database `publicdigit` as `publicdigit_user`; `config/database.php` lines ~99 – 120). Values set before Laravel loads are not overwritten by `.env.testing` (the repository bootstrap's own note: Laravel's environment repository is immutable). So no other credential is used, even if a dotenv file contains one. It then **aborts** (exit ≠ 0) before Laravel loads if any of the three sources differ, or the pinned name is `nrna_test` or empty.
2. **Identity check on the actual Laravel connection.** The same bootstrap registers `Illuminate\Database\Connection::resolverFor('pgsql', $closure)`. This is a static registry; `ConnectionFactory::createConnection()` consults it (`if ($resolver = Connection::getResolver($driver))`) for **every** pgsql connection the process builds, including the one `RefreshDatabase` / `migrate:fresh` uses and reconnects (`DatabaseManager::reconnect` → `makeConnection` → factory). The closure receives `($connection, $database, $prefix, $config)`, where `$connection` is a `\PDO` or (normally) a `\Closure` that produces the PDO. The closure:
   - resolves the PDO (calls the closure if needed);
   - refuses (fails closed) a config with `read` / `write` keys, because a separate read PDO is attached later via `setReadPdo()` and would bypass the check;
   - runs **read-only** identity queries, all of which must pass:
     - `current_database()` = expected name;
     - `current_user` = the dedicated role;
     - `inet_server_addr()` / `inet_server_port()` = expected address/port. On a unix socket `inet_server_addr()` is `NULL`: fail closed unless the environment explicitly declares a socket is expected;
     - the current database OID (`SELECT oid FROM pg_database WHERE datname = current_database()`) = the recorded OID;
     - `shobj_description(<oid>, 'pg_database')` = the expected identity marker;
   - only if all pass, returns `new \Illuminate\Database\PostgresConnection($pdo, $database, $prefix, $config)`;
   - otherwise writes a clear message (which check failed; no credentials) to `STDERR` and `exit(1)`, before any statement such as `DROP` runs.
   This applies to every pgsql connection, including `pgsql_audit` (defaults to `DB_DATABASE`) and `pgsql_geo` (a different database); a connection that does not pass aborts the run. That is intended.
3. **`<scratch>/phpunit-membership.xml`.** A copy of `phpunit.xml` whose `bootstrap` points to the scratch bootstrap and whose `<server name="DB_DATABASE">` is the isolated name. **No tracked file changes.**
4. **The run log records** the pin result, each identity-check result (pass/fail per check, with the observed identity values), HEAD and the PHP / PHPUnit versions.

**Connection coverage (what the resolver intercepts).**

| Path | Covered? | Basis |
|---|---|---|
| every connection built by `ConnectionFactory` for driver `pgsql`, in the PHPUnit process: `pgsql`, `testing`, `pgsql_audit`, `pgsql_geo`; including `RefreshDatabase` / `migrate:fresh` run in-process and `DatabaseManager::reconnect` | **yes** | **inspected:** `Connection::resolverFor` / `getResolver` (Connection.php ~1683 – 1694); `ConnectionFactory::createConnection` (~269); `DatabaseManager::reconnect` → `makeConnection` (~333 – 376) |
| forked children in `PostgresConcurrencyRaceTest` (`pcntl_fork`, then `DB::purge()` and a fresh connection) | **yes** | **inspected:** the test's `race()` (lines ~62 – 95). **Inferred:** a static registration is inherited across `fork` (process memory copy) |
| raw `new PDO(...)` in `app/` or `tests/` | none found | **inspected:** `grep` found no `new PDO(` in `app/` or `tests/` |
| a second `Connection::resolverFor('pgsql', …)` that overwrites the guard | none found in `app/` / `tests/` | **inspected:** `grep`; **not inspected** in `vendor/` |
| `setPdo` / `setReadPdo` swapping the PDO after construction | none found in `app/` / `tests/`; `read` / `write` configs refused by the guard | **inspected:** `grep` |
| raw PDO or a custom resolver inside **vendor** packages | **unknown** | **assumption:** not inspected |
| separate processes started with `exec` / `Process` (e.g. `php artisan` subprocesses) | not covered | **inspected:** none in the 4 Membership PostgreSQL test files; **assumption** for other code paths |
| non-pgsql drivers (`sqlite`, `mysql`, `sqlsrv`) | not covered | they cannot connect to a PostgreSQL database |

Paths the resolver does not cover are handled by the **independent isolation boundary** below, not by the resolver.

**Independent isolation boundary (database permissions; holds even if a connection bypasses the resolver).**
- **The only credentials in the run's process environment are those of the dedicated role R** (all pgsql connections are pinned to R; see item 1). An unguarded connection can therefore only act as R.
- **R must be unable to modify `nrna_test` destructively.** Required properties, each verified in PV (below):
  - not superuser; no `CREATEDB`, `CREATEROLE`, `REPLICATION` or `BYPASSRLS`;
  - not a member of any role that owns objects in `nrna_test`;
  - owns no object in `nrna_test`, so `DROP` / `ALTER` / `TRUNCATE` are impossible;
  - no `CREATE` on `nrna_test` or on any of its schemas;
  - no `INSERT` / `UPDATE` / `DELETE` / `TRUNCATE` on any table in `nrna_test`.
- **Why non-ownership is sufficient against destruction (inspected):** Laravel's `dropAllTables()` issues **one** statement, `drop table <all tables> cascade` (`PostgresBuilder::dropAllTables`, line ~96; `PostgresGrammar::compileDropAllTables`, line 445). PostgreSQL rejects the whole statement if the role does not own any listed table, so **nothing is dropped**. Views and types are wiped by similar statements (inferred from `dropAllViews` / `dropAllTypes`; not read line by line).
- **`CONNECT` and `PUBLIC` (explicit):** by default `PUBLIC` has `CONNECT` (and `TEMP`) on every database. **Unless I-1 is applied, R can connect to `nrna_test`.** It could then read data visible to `PUBLIC` and create temporary objects, but not drop or alter tables it does not own. On PostgreSQL versions before 15, `PUBLIC` may also hold `CREATE` on `nrna_test`'s `public` schema, so the PV must show it is revoked or that R lacks it.
- **Required for Part 2** (Human PO / ARB, 2026-10-10, accepting the Principal Reviewer's condition): R has **no `CONNECT`** on `nrna_test`. Non-ownership alone is not enough. This may require revoking `CONNECT` from `PUBLIC` on `nrna_test` and granting it explicitly to its current users. That affects other users of `nrna_test`, so it is an **infrastructure decision (prerequisite I-1)**, not assumed.

**Provisioning evidence (PV): verified independently before Part 2 is authorised.**
- Infrastructure or the PO runs **read-only** verification queries and supplies their output (no passwords):
  - on the isolated database: `SELECT current_database(), oid, shobj_description(oid,'pg_database') FROM pg_database WHERE datname = current_database()`; `SELECT inet_server_addr(), inet_server_port()`;
  - the attributes of R: `SELECT rolsuper, rolcreatedb, rolcreaterole, rolreplication, rolbypassrls FROM pg_roles WHERE rolname = 'R'`;
  - R's memberships: `pg_auth_members`;
  - the server version (`SHOW server_version`), which governs the default `public`-schema privileges;
  - on the isolated database: the database owner (`pg_database.datdba`), the owner of schema `public` (`pg_namespace.nspowner`), and `has_database_privilege('R','nrna_test_membership','CREATE')`;
  - the `nrna_test` ACL (`SELECT datacl FROM pg_database WHERE datname = 'nrna_test'`), which shows whether `PUBLIC` still holds `CONNECT`;
  - on `nrna_test`: `has_database_privilege('R','nrna_test','CONNECT')`; the number of objects owned by R (`pg_class` joined to `pg_roles`) = 0; `has_schema_privilege('R', <each schema>, 'CREATE')` = false; table privileges of R (`information_schema.role_table_grants`) = none.
- **Who checks:** ChatGPT reviews the output against this list, and the Human PO / ARB accepts it. **Only then may Part 2 be authorised.** The verified values become the expected values for the identity checks.

| Step | Action | Evidence |
|---|---|---|
| 4 | `vendor/bin/phpunit --configuration <scratch>/phpunit-membership.xml tests/Feature/Contexts/Membership/Organisational/` | pin result, identity-check results, HEAD, versions, full output, exit code, SHA-256. Supporting evidence only: `nrna_test` table count before and after, taken on a **separate read-only** connection |
| 5 RED → GREEN | a far-date feature test: appending a fact at ordinal 2932897 fails, and **no** row exists in `org_membership_facts` or `org_membership_commands`. RED is shown against the pre-fix code by checking out the step-1 commit in a **scratch copy**, never the working tree | both runs captured |
| 6 | report: results; evidence for the Slice 1b re-disposition | a short result note |

### Part 3: read-only containment check (separate permission per database)

PostgreSQL `date` accepts years beyond 9999, so out-of-range values may already be stored wherever the 1b tables exist. Developer guide 03 says the migration is not rolled out yet, so the risk is low but unproven.
- **Query (read-only):**
  ```sql
  SELECT count(*) FROM org_membership_facts
  WHERE effective_at > '9999-12-31' OR effective_at < '0001-01-01'
     OR initial_term_until > '9999-12-31' OR initial_term_until < '0001-01-01';
  ```
  first checking that the table exists (`to_regclass('org_membership_facts') IS NOT NULL`).
- **Run only** on a database you name explicitly (e.g. the development database), with your permission for that database, per database.
- **Identity before the query:** `current_database()`, `inet_server_addr()` / `inet_server_port()` and `current_user` must equal the values you named for that database. If not → NOT VERIFIED.
- **SELECT-only privileges (verified, not assumed):** for the checking role, `rolsuper` = false, and `has_table_privilege(current_user, 'org_membership_facts', 'INSERT')`, `'UPDATE'`, `'DELETE'` and `'TRUNCATE'` are all false (only if the table exists). If not → NOT VERIFIED.
- **Enforced read-only mode (precondition):** a role with `SELECT`-only privileges **and** a read-only transaction (`BEGIN; SET TRANSACTION READ ONLY;`), or `PGOPTIONS='-c default_transaction_read_only=on'`; in either case `SHOW transaction_read_only` must return `on` before the query. If read-only cannot be verified, the query is **not** run.
- **Result (tri-state):**
  - **CONTAINED:** read-only verified, table exists, count = 0;
  - **NOT CONTAINED:** count > 0 → STOP and report; remediation is a separate decision;
  - **NOT VERIFIED:** table missing (`to_regclass` is `NULL`), unexpected identity, SELECT-only not verified, read-only not verified, a permission, connection or query error, or any other failure. A missing table is **never** reported as zero.

## Exclusions (separate increments)

- PBDIGIT-264 F-2 to F-5 (including the F-4b FK **migration**);
- C-1 F-1 (`encode()` typing; stays in C-4, H-3);
- the domain effectiveAt range;
- PBDIGIT-266, PBDIGIT-267, PBDIGIT-269;
- C-2 to C-4; Slices 2 – 15;
- any change to `phpunit.xml`, `tests/bootstrap-test-database.php` or schema;
- pushes.

## Risks

| Risk | Mitigation |
|---|---|
| the run hits `nrna_test` | scratch pin plus self-check (all pgsql connections); identity checks on the actual Laravel connection fail closed; **independently**, R has no destructive privileges on `nrna_test` (verified in PV), and preferably no `CONNECT` (I-1) |
| a path the resolver does not cover (vendor raw PDO, subprocesses) | covered by the permission boundary, since only R's credentials are present; vendor code not inspected (assumption) |
| the isolated database is missing, misnamed or recreated | name, OID, identity-marker, host/port and role checks fail; the run aborts; nothing runs |
| identity rests on provisioning being correct | PV: independent read-only verification output, reviewed by ChatGPT and accepted by the Human PO / ARB before Part 2 |
| credentials leak | environment only; never written to tracked or scratch files |
| an infrastructure guard surfaces as an error, not a refusal | accepted for now; the business refusal waits for the effectiveAt-range decision |
| pre-existing PostgreSQL failures unrelated to F-1 | reported separately, not fixed here |

## Approval gates

1. ChatGPT reviews this revision.
2. Human PO / ARB approval of Part 1 (it needs no database).
3. P-DB provisioned → PV output supplied, reviewed by ChatGPT, accepted by the Human PO / ARB (and the I-1 decision recorded) → then a separate approval of Part 2.
4. Permission per named database for Part 3 (identity values, a SELECT-only role and read-only mode named for that database).

## Done when

- `DateOrdinalTest` is RED → GREEN, with the failing run recorded;
- the targeted regression exits 0;
- on the verified isolated database, the PostgreSQL suite and the far-date feature test are captured, with the identity-check evidence;
- the containment result (CONTAINED / NOT CONTAINED / NOT VERIFIED) is recorded per permitted database;
- one commit per RED and per GREEN (PBDIGIT-264);
- a short result note; no push.

## Progress

- 2026-10-10 19:53: **Part 1 executed** (test-first; no database, no commit, per the approval).
  - **RED:** new `tests/Unit/Contexts/Membership/Infrastructure/Organisational/DateOrdinalTest.php` (5 tests). The 3 refusal tests failed with "exception … is not thrown"; the 2 boundary round-trips passed, confirming the bounds. Exit 1.
  - **GREEN:** `DateOrdinal::toDate()` gained a numeric guard for `[-719162, 2932896]` before any date arithmetic (8 lines). Exit 0, 5 tests.
  - **Regression:** `DateOrdinalTest` 5 / 7, Domain 89 / 112487, Application 34 / 93, Architecture 12 / 18, **all exit 0**.
  - **Evidence:** outputs, exit files, `env.txt` and SHA-256 are in the session scratchpad (`…/scratchpad/part1/`).
  - The existing PostgreSQL round-trip test uses exactly the boundary ordinals, so it is unaffected.
  - Awaiting ChatGPT's review.
- 2026-10-10 (Human PO / ARB authorisation A, B, C1 – C3):
  - **Evidence preserved** in `docs/publicdigit/membership/evidence/2026-10-10-pbdigit-264-f1/`: the 13 captured files, copied unchanged, plus a `SHA256SUMS` manifest (verified 13 / 13).
  - The independent reviewer's own runs are **not** part of this evidence, because their exit codes were not captured.
  - "Before any write" wording corrected (rollback-based guarantee); the domain effectiveAt range is still open and not mapped to D-5(b).

## Option E: persistence representability of finite valid times (PBDIGIT-264 follow-on)

**Design:** the final Option E plan (session `42e0287e…`, 2026-10-10, reviewed by the Principal Reviewer in `docs/publicdigit/membership/architecture/brainstorming/20261010_2050_implemenation.md`). Scope: a technical persistence rule only; no business calendar, plausibility, backdating or scheduling rule; `TimePoint` unchanged.

### Option E gate register

Decisions supplied by the Human PO / ARB (Dr. Nab Raj Roshyara) in session `42e0287e…`, 2026-10-10, as answers to the decision sheet (option labels quoted verbatim).

| # | Decision | Recorded answer | Status |
|---|---|---|---|
| 1 | Append order: key lookup → date check → version → writes | "Approve all four (Recommended)" | APPROVED |
| 2 | R-1a-6: an unsupported finite valid time takes precedence over a stale version | same answer | APPROVED |
| 4 | Slice 1a / 1b contract amendments as proposed | same answer | APPROVED |
| 6 | Names and location: `Ports/StorableValidTime`, `ValidTimeNotStorable`, R-1a-6, tests E1–E5, E7–E10 | same answer | APPROVED |
| 3 | F-3 limitation (member-not-found ordering of the double) accepted for this increment; no full parity claimed | "Accept for this increment (Recommended)" | ACCEPTED (this increment only) |
| 5 | PostgreSQL delivery | "P1 with release gate (Recommended)": the check is added now; no acceptance, integration or production use until Part 2 evidence exists | APPROVED (P1) |
| 7 | Business calendar / plausibility rule | not decided; separate from Option E | OPEN |
| 8 | Implementation (baseline, failing-tests commit, implementation commit, regression, documents and evidence commit; no database, no push, no acceptance) | "Authorise implementation" | AUTHORISED |

Formal acceptance of Option E is **not** recorded here; it needs a separate Human PO / ARB verdict after the result review and Part 2.

### Option E progress

- 2026-10-10: **implemented test-first, no database.**
  - Baseline at `bab1a9fbb`: all suites exit 0.
  - RED `0e4e5ac7a`: 7 assertion failures as predicted (after one malformed-test run, recorded and fixed).
  - GREEN `af495c838`: all focused suites exit 0.
  - Regression: Domain 89, Application 49, `DateOrdinalTest` 5, architecture 4 + 3 + 5 + 1, all exit 0.
  - Contracts amended (Slice 1a R-1a-6; Slice 1b §3, §4, §6, §8); developer guide 06; evidence in `docs/publicdigit/membership/evidence/2026-10-10-option-e/`.
  - **Status:** implemented and locally tested. **PostgreSQL unverified** (Part 2). **Not accepted.** F-3 remains open.
  - Next: ChatGPT result review, then Human PO / ARB disposition; Part 2 needs its own authorisation.

### Option E correction (post-implementation review, `20261010_2110` / `20261010_2115`)

Decisions supplied by the Human PO / ARB in session `42e0287e…`, 2026-10-10 (option labels quoted verbatim):

| # | Decision | Recorded answer | Status |
|---|---|---|---|
| R1 | Contract wording: R-1a-6 precedence over `MemberNotFound` adapter-specific while F-3 is open; parity sentences; guide 06 | "Approve (Recommended)" | APPROVED |
| R2 | E4 renamed to its port semantics; E4-L legacy replay via a test-only seeding method in the double; sensitivity change instead of a failing phase | "Approve (Recommended)" | APPROVED |
| R3 | PostgreSQL variant of E4-L in Part 2 scope (does not authorise Part 2) | "Include in Part 2 scope (Recommended)" | IN SCOPE (Part 2 still gated) |
| R4 | Two correction commits; no production code, database, push or acceptance | "Authorise" | AUTHORISED |

- 2026-10-10: **correction done.** Tests `fb85c6e24` (E4 rename, E4-L, `seedPreOptionEAppend`). The sensitivity mutant made E4-L, E4 and E3 fail on assertions; reverted byte-identical. Regression all exit 0 (Domain 89, Application 50, DateOrdinal 5, architecture 13). Contracts and guide 06 corrected; evidence in `docs/publicdigit/membership/evidence/2026-10-10-option-e-correction/`. Option E still **not accepted**; PostgreSQL unverified.

## PBDIGIT-264 F-3: key first, MemberId collision (Path B)

**Plan:** the F-3 authorisation-gate report (session `42e0287e…`, 2026-10-10), reviewed in `docs/publicdigit/membership/architecture/brainstorming/20261010_2150_implementation.md`. Policy source: the Principal Reviewer's F-3 clarification (ratification pack §2 / §10; Slice 1a contract §7).

### F-3 gate register

Decisions supplied by the Human PO / ARB (Dr. Nab Raj Roshyara) in session `42e0287e…`, 2026-10-10 (option labels quoted verbatim):

| # | Decision | Recorded answer | Status |
|---|---|---|---|
| F3-1 | As PO, adopt the F-3 clarification: the idempotency-key lookup precedes every other check in every adapter; `create()` with a MemberId of another holder in the tenant is an infrastructure failure (`RuntimeException`) with no state change | "Adopt (Recommended)" | ADOPTED (PO) |
| F3-2 | Order of the remaining PBDIGIT-264 work | "Path B: F-3 before F-5 (Recommended)": F-3 now; F-5 after Part 2 is provisioned and authorised; then F-4 → F-2 | APPROVED (sequence deviation) |
| F3-3 | After F-3, R-1a-6 precedence over `MemberNotFound` universal again | "Approve (Recommended)" | APPROVED |
| F3-4 | F-3 increment: F3a, F3b-1, F3b-2, F3c; test-double fix; contracts, guide 07, gate register, evidence; three commits; no production code, database, push or acceptance | "Authorise" | AUTHORISED |

### F-3 progress

- 2026-10-10: **implemented test-first, no database.** Baseline at `240107642` (Application 50, E10 1, exit 0). RED `d908f93c4`: 4 assertion failures as predicted (F3a no exception; F3b-1, F3b-2, F3c `member_not_found`). GREEN `d2a3f2b17` (test double only). Regression all exit 0: Domain 89, Application 54 (incl. the reference-model property test), DateOrdinal 5, architecture 13.
  - **Deviation from the plan (assertion technique only):** the plan assumed PHPUnit's `fail()` is not a `RuntimeException`; it is, so F3a captures the exception and asserts after the `try`. Found before the first run; the test's intent is unchanged.
  - Contracts: Slice 1a R-1a-6 universal again (F3-3), §7 clause marked adopted and implemented; Slice 1b parity note and §8 F3a–c (Part 2). Guide 07 and index; guide 06 pitfall updated. Evidence in `docs/publicdigit/membership/evidence/2026-10-10-f3/`.
  - **Status:** implemented and tested on the double; PostgreSQL unverified (F3a–c join Part 2); not accepted. Next under Path B: F-5 after Part 2 is provisioned and authorised, then F-4 → F-2.
