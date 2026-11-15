# PBDIGIT-351: Backup and temporary-copy protections for the restore drill and recovery (M-6, INV-ACCESS-1)

**Status:** OPEN (created 2026-11-15) · **go-live blocker** · not authorised · **Context:** Platform · **Kind:** missing control (accepted requirement)
**Found by:** PBDIGIT-345 task 13, D-166 review (`docs/publicdigit/platform/evidence/2026-11-15-pbdigit345-task13/02-d166-backup-review.md`).

## Finding

The task 12 restore drill (`platform:operate … restore-drill`) and the in-place recovery (D-234) meet D-166 (1) for **authority**: they are technical operations under the joining grant, recorded and reported, and read technical metadata only. They do **not** meet the accepted M-6 release-1 criteria for INV-ACCESS-1 (multi-database boundary design, accepted under D-178):

1. the dump file and the scratch database are **not encrypted** (PostgreSQL 15 has no encryption at rest; the storage is not checked);
2. **no key-use log** (no keys at all);
3. leftover copies **do not expire** if the process is killed (`finally` does not run);
4. the in-place recovery's restore is a **manual `pg_restore`**, not "restore only by the automated process";
5. not test-proven that the drill reads no business table **on the copy** (T-16's counter method is used only for the repair);
6. the cleanup after a **failing** `pg_dump` / `pg_restore` is proven only with a unit fake.

## Business impact

**A complete copy of an organisation's members, decisions and messages could remain readable** by whoever holds the application's operating-system account or database login, without the organisation's case grant. That is exactly what D-166 exists to prevent. **Until this is closed, the drill and the in-place recovery are development and test only** (administrator notes, task 12 section).

## Decisions needed before implementation (Principal)

- **D-a, where the scratch copy lives:** (a) a dedicated, storage-encrypted, non-routable **drill cluster** (recommended); or (b) the organisation's cluster on encrypted storage (also covers live data).
- **D-b, key custody for backup encryption and the key-use log:** who holds the keys (a key-management service, or a platform-held key per organisation), and where key use is logged.
- *(Related but separate: the backup schedule, retention and off-site storage; guide 12 limitations.)*

## Follows from M-6 directly (no new decision; test first)

- the dump encrypted with a per-run key that is never written to disk;
- an automatic **sweep** of `scratch_restore_*` databases and drill files older than a bound, recorded as a technical operation;
- an **automated in-place restore** operation (`restore-in-place`) from an encrypted backup, replacing the manual `pg_restore` step; it ends only through `migration-repair` (D-234);
- tests: `pg_stat_user_tables` counters on the copy (no business read by the drill); cleanup after a failing `pg_dump` / `pg_restore` on PostgreSQL; a killed drill is swept.

**Traceability:** D-166 · D-178 (M-6) · D-234 · D-193 register B-10 · PBDIGIT-345 tasks 12 and 13.
