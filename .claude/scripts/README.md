# .claude/scripts — Workflow Hooks

Wired in `.claude/settings.json`. All non-blocking. Windows/Git-Bash notes: local `jq` is a broken Node shim → scripts parse JSON with PHP; use `printf` not `echo` for paths (Git Bash echo interprets backslash escapes).

| Script | Hook | Purpose |
|--------|------|---------|
| `db-safety-check.sh` | PreToolUse (Bash, PowerShell) | blocks destructive DB commands (pre-existing) |
| `discipline-gate-reminder.sh` | PreToolUse (Write\|Edit) | non-blocking tripwire for **Business → DDD → Architecture → Tests → Implementation**; fires ONLY when a NEW `tests/**` or `app/**.php` file is about to be created, reminding to confirm upstream artifacts (DDD model / Architecture Decision) exist first; silent on edits/docs; reads STDIN or `$CLAUDE_TOOL_INPUT` |
| `inject-context.sh` | SessionStart | self-heals `.claude/` structure; injects MEMORY.md → CONTEXT.md → **active plan (the first `.claude/plans/*.md` path CONTEXT.md declares — deterministic; mtime is only the fallback)** → today's session log |
| `session-changes-logger.sh` | PostToolUse (Write\|Edit) | appends modified files to `runtime/YYYY-MM-DD-files.log` (deduped, repo-relative) and maintains `runtime/YYYY-MM-DD-state.json` (filesModified, files[], planTouched, contextTouched, memoryTouched); also refreshes a plan's `**Last Updated:**` line when a plan doc is modified (update-only — never inserts; sed is not a tool call → no hook recursion) |
| `session-log-reminder.sh` | Stop | sync report ONLY when files were modified today AND state is out of date (session log missing/stale 45min, CONTEXT untouched); reads the state json |
| `dev-guide-reminder.sh` | Stop | **area-aware** reminder (Developer Guide = Definition of Done; see `.claude/CLAUDE.md`): derives the code AREAS changed today (`app/Contexts/<X>` → `<x>`, other `app/<seg>` → `<seg>`, `database/migrations` → `database`; alias `shared`→`audit_system`) and nudges per code-area with **no matching `developer_guide/<area>/` update today**. An unrelated track's guide (e.g. `ai_platform`) no longer silences a real gap. Non-blocking; docs-only/discussion sessions stay silent |

Separation of concerns: `sessions/` = documentation (daily logs, committed) · `.claude/runtime/` = runtime metadata (per-day state, gitignored) · plan headers (`Created/Last Updated/Status`) = the plan's own lifecycle documentation. Session-log freshness stays mtime-based (the logger deliberately skips `sessions/` and `runtime/`).
