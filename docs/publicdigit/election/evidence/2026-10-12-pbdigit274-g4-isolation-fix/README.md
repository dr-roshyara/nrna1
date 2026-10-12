# PBDIGIT-274 gate G4: isolation correction and verification, 2026-10-12

**Starting point:** `../2026-10-12-pbdigit274-g4-check/`. G4 NOT MET because `pbdigit274_test_owner` could reach every database.

**Authority:**
- **The change:** the `pg_hba.conf` change and reload were made by the Human PO / administrator as a superuser. Claude has no superuser access.
- **Read-only checks:** "run the G4 checks yourself".
- **The login attempt:** one attempt to `postgres` was authorised ("yes", "i authorize").

No password appears in any file here.

| # | File | What | Result |
|---|---|---|---|
| 01 | `01-before-hba-rules.txt` | step A before the change (pasted by the PO) | lines 115 / 117 `host all all 127.0.0.1 / ::1 scram-sha-256` let **every** role reach **every** database over TCP |
| 04 | `04-after-hba-rules.txt` | step C after the reload (raw output pasted by the PO; reload itself reported `t`) | lines 112–113 allow the role only to `pbdigit274_isolated_test` (IPv4 / IPv6, `scram-sha-256`); 114–116 `reject` the role on IPv4 `0.0.0.0/0`, IPv6 `::/0` and the socket; then the six old rules. **Compared rule by rule with file 01:** old rules unchanged apart from their line numbers; the five new rules come first and name only `pbdigit274_test_owner`; the `error` column is empty everywhere |
| 02 | `02-after-target-checks.{sql,out,exit}` | the same read-only queries, over TCP `127.0.0.1`, in `pbdigit274_isolated_test` | exit 0. Login still allowed; role flags, memberships (none), owner, ACL, `public` CREATE, `plpgsql` all **unchanged**; 0 user tables. `pg_hba_file_rules` still refused to this role (expected) |
| 03 | `03-rejected-login-postgres.out` | **one** login attempt as the role to `postgres` (never `nrna_test`) over `127.0.0.1` | exit 2: `FATAL: pg_hba.conf rejects connection for host "127.0.0.1", user "pbdigit274_test_owner", database "postgres", no encryption`. Rejected by the login rules before a session existed |

**Two layers, kept apart:**
- **SQL privileges are unchanged** (on purpose). `has_database_privilege(..., 'CONNECT')` is still `true` for the other databases, so their ACLs and other roles are untouched.
- **Isolation now rests on the login rules (`pg_hba`).** File 03 proves them for IPv4 / TCP, the path the tests use (`PBDIGIT274_DB_HOST=127.0.0.1`).

**Proved by the active rules (file 04), not by a login attempt:** the rejection over IPv6 and the Unix socket. Only the IPv4 path, which the tests use, was also tested with a real login (file 03, one authorised attempt).

**G4 status: MET (2026-10-12).**
- the database exists and is dedicated;
- the role is unprivileged, has no memberships, owns the database and the `public` schema, and can use `plpgsql`;
- the database starts empty;
- the role is confined to `pbdigit274_isolated_test` by the active `pg_hba` rules on every path (IPv4, IPv6, socket), and a real login to another database (`postgres`) was refused;
- other roles' rules are unchanged.

**Not part of G4, still open:**
- `APPROVED_NAME` (a separate, reviewable commit);
- the authorisation for the database part of S2;
- G5–G8.
