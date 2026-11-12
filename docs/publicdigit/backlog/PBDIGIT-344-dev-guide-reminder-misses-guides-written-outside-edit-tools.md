# PBDIGIT-344 — The developer-guide reminder misses guides written outside the Write/Edit tools

| | |
|---|---|
| **Kind** | Tooling defect (engineering automation; no product story) |
| **Found** | 2026-11-12, PBDIGIT-343 acceptance reconciliation |
| **Status** | Backlog, not scheduled |

**Observation (fact):**
- `.claude/scripts/dev-guide-reminder.sh` compares code areas with guide areas using `.claude/runtime/<date>-files.log`.
- That log is written only by the `Write|Edit` hook.
- So a guide created by a shell command or committed from elsewhere is invisible to it.

**Effect:** on 2026-11-12 it warned that `developer_guide/database/` was missing, although `00_index.md` and `01_postgresql_lock_waits.md` were committed that day (`ba4c1f5c9`). A false positive weakens trust in a real gap.

**Candidate fix (not designed):** also take the day's changed paths from git (`git log --since=<today> --name-only` plus the working tree). It is a reminder, never a gate.

**Note:** the methodology is frozen (2026-08-01). This is a tooling defect, not a protocol change.
