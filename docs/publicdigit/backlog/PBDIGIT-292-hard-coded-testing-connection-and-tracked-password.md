# PBDIGIT-292: A hard-coded `testing` database connection carries a plaintext password in a tracked file

**Type:** Security / configuration hygiene · **Epic:** none · **Created:** 2026-10-14 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from PBDIGIT-283 R6 negative-evidence preparation. **Index row:** added 2026-10-14.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the Principal 2026-10-14 (Option A decision) |
| **Classification** | **verified in source** (read-only). Whether the credential is real, current, or shared with another environment is **not checked** |
| **Scope** | **outside PBDIGIT-283.** PBDIGIT-283 R6 accepts this connection as a narrowly scoped exception (Option A) and changes nothing here |

## Finding (verified in source)
- **`config/database.php:140-150`** defines a connection named `testing`:
  - driver `pgsql`, host `127.0.0.1`, port `5432`, database `nrna_test`, username `nrna`;
  - **a password written in plain text**;
  - **no environment variable** controls any of these values.
- **The file is tracked in git.** The connection was introduced by `e53d8a321` ("Configure test database infrastructure with RefreshDatabase"), so **the password is also in the repository history**, not only the current file.
- **Nothing uses the connection:** no code in `app/`, `tests/`, `config/`, `routes/` or `database/` selects `testing` (search 2026-10-14).
- **The secret isn't reproduced here.** It appeared once in a PBDIGIT-283 session's terminal output (a configuration dump, 2026-10-14) before the finding was made; it isn't in any document or commit message written since.

## Risk
- **Credential exposure:** anyone with read access to the repository or its history has a database login. The impact depends on whether `nrna` with this password is valid anywhere (local, CI, a shared or production-like server) and what it can access [U].
- **A reachable target that configuration can't neutralise:** a test or script selecting `testing` reaches `127.0.0.1:5432/nrna_test` whatever `.env`, `phpunit.xml` or the PBDIGIT-283 pins say. `nrna_test` is a test database, so the database-safety impact is bounded. This is why PBDIGIT-283 could accept it narrowly (Option A).

## Why it stays unchanged in PBDIGIT-283
- **PBDIGIT-283 changes test infrastructure only.** Editing production configuration and handling credentials need their own scope, review and authorisation.
- **R6 negative evidence** accepts exactly this connection (driver, host, port, database and username all compared; password never read). Any difference stops the run (`tests/Support/NegativeEvidence::OPTION_A_EXCEPTION`). Removing the connection later only removes that exception.

## Required work (nothing decided)
1. **Review the credential:**
   - is `nrna`/(the tracked password) valid on any server?
   - which environments share it?
   - what can it access?
2. **Rotate it if it's valid anywhere,** before or together with removal: the history keeps the old value regardless.
3. **Remove the hard-coded `testing` connection** from `config/database.php`, or make it fully environment-driven with no default secret.
4. **Update PBDIGIT-283's Option A exception:** remove `OPTION_A_EXCEPTION` and the `testing` entry in `NegativeEvidence::KNOWN_CONNECTIONS`.
5. **Decide** whether history rewriting is warranted. That's normally not needed once rotated; it's a separate decision.

## Acceptance criteria
1. **No database password appears in any tracked file** in the current tree. A check or test enforces this for `config/`.
2. **The `testing` connection no longer exists,** or none of its values is hard-coded; a test proves no configured connection has a literal password or host.
3. **Credential review recorded:** valid or not, where, which access, with the rotation done or the reason it isn't needed. The secret never appears in the record.
4. **PBDIGIT-283's Option A exception is removed,** and its pre-run check then requires every connection to be environment-driven. `NegativeEvidenceTest` is updated test first.
5. **No test or script relies on the `testing` connection** (search evidence attached).
6. **Database-safety evidence still passes** after removal: the PBDIGIT-283 database-free suite (C-1 command), and R6 negative if it's re-run.

## Related
- `PBDIGIT-283` R6: `tests/Evidence/Pbdigit283/negative-evidence.php`, `tests/Support/NegativeEvidence.php`; plan §6 R6 (Option A); guide `developer_guide/testing/06_negative_evidence.md`.
