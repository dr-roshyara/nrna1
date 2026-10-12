# PBDIGIT-274 gate G4: read-only verification of the isolated database, 2026-10-12

**Authority:** Human PO, 2026-10-12 ("run the G4 checks yourself"). **Scope:** read-only `SELECT`s inside `pbdigit274_isolated_test`, as `pbdigit274_test_owner`. **No other database was contacted;** reachability of other databases was assessed by privilege functions only. No password appears in any file here; credentials came from the git-ignored `.pbdigit274.env`.

**Files:** `g4-checks.sql` (the queries), `g4-checks.out` (output, unchanged except the scratch path), `env.txt` (command and time).

| # | G4 requirement | Result | Verdict |
|---|---|---|---|
| 1 | the database exists and is the one connected to | `current_database()` = `pbdigit274_isolated_test`, user `pbdigit274_test_owner`; PostgreSQL 18.6 | ✅ met |
| 2 | dedicated role without elevated rights | `rolsuper`, `rolcreatedb`, `rolcreaterole`, `rolreplication`, `rolbypassrls` all `f`; `rolcanlogin` `t` | ✅ met |
| 3 | no membership in other roles | 0 rows | ✅ met |
| 4 | database owned by the role, `PUBLIC` removed | owner `pbdigit274_test_owner`; ACL `{pbdigit274_test_owner=CTc/…}`, no `PUBLIC` entry | ✅ met |
| 5 | the role owns / can create in the schema | `public` owned by `pg_database_owner` (= the database owner, PostgreSQL 15+); `CREATE` = `t` | ✅ met |
| 6 | can create `plpgsql` functions | `plpgsql` present, `USAGE` = `t` | ✅ met |
| 7 | fresh database | 0 user tables | ✅ met |
| 8 | **the role cannot connect to any other database** | **`CONNECT` = `t` on `nrna_test`, `nrna_test_adjudication`, `postgres`, `publicdigit`, `publicdigit_db`, `template1`** (PostgreSQL's default `CONNECT` for `PUBLIC`) | ❌ **NOT met at privilege level** |
| 9 | `pg_hba` restricts the role to its database | `permission denied for view pg_hba_file_rules`: needs a superuser | ⚪ NOT VERIFIED |

**G4 status: NOT MET** (requirement 8; requirement 9 unverified).

**Smallest correction (operator, superuser; not done by Claude).** In `pg_hba.conf`, above the general rules, then reload PostgreSQL:
```
host   pbdigit274_isolated_test  pbdigit274_test_owner  127.0.0.1/32  scram-sha-256
host   all                       pbdigit274_test_owner  0.0.0.0/0     reject
host   all                       pbdigit274_test_owner  ::/0          reject
local  all                       pbdigit274_test_owner                reject
```
The `local … reject` line goes **after** any `local` line the tests need. This leaves other applications unchanged. (Revoking `CONNECT` from `PUBLIC` on the other databases would also work, but it touches their other users.)

**How to verify the correction (needs an authorisation decision):**
- (a) A superuser pastes `SELECT line_number, type, database, user_name, address, auth_method FROM pg_hba_file_rules ORDER BY line_number;`.
- or (b) Claude attempts a connection as `pbdigit274_test_owner` to the maintenance database `postgres` (**never `nrna_test`**) and expects `pg_hba.conf rejects connection`. That is a refused login, with no session in that database.
