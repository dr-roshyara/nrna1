# Operational Qualification Test — Live Developer Experience

**Status: OPEN — evidence partial.** Commissioned by review 2026-08-04
("an ACTIVE state is only a declaration without an execution trace").
Implemented ≠ operationally validated; this protocol closes that gap
step by step, with evidence, and states plainly what remains.

## The distinction under test

Every ACTIVE/Implemented claim in the register is an *implementation* claim.
This OQT tests *participation in the real execution chain*: every arrow of
`save → trigger → ChangeSet → runtime → collectors → recommendations →
presentation` executed in a real session, per adapter.

## Adapter purity (regression guard) — ✅ PASSED 2026-08-04

Audit: `grep -cE "Lcom4Collector::collect|RecommendationEngine::|calculateLCOM|TestPresenceCollector::classify"`
across all six adapters (`.husky/post-commit` · canonical hook ·
`claude-code-trigger.sh` · `extension.js` · `watch.php` · `dev.php`): **0 hits
each**. `ObservationRuntime.php` alone holds the three collector/engine calls.
No adapter calculates anything.

## Execution-chain evidence, per adapter

| Adapter | Chain executed for real? | Evidence |
|---|---|---|
| **Commit trigger** | ✅ YES — every commit today | test-presence observations appended per commit (stream); unified path demonstrated on real commit `95c7d2cab` → R2 in 39ms (after the incomplete-migration fix `b7239aa75`) |
| **watch.php (poller)** | ✅ YES — live background session | real save of `Election.php` detected in poll window → trace line: `event=file-save → changeset=1 → runtime=91ms → collectors: lcom4(1obs,1rec,90ms) test_presence(1obs,1rec,0ms) → recommendations=2 → presented=terminal`; `watch-usage.jsonl` records |
| **`knowledgeos dev`** | ✅ YES — **including the USER'S real VS Code session** | headless: VERIFY → PROVE (110ms) → OBSERVE with live save traced · ⭐ operational: the folderOpen task auto-started the session in the user's own VS Code — chain PROVEN there (283ms, 2 collectors, 2 recommendations) |
| **Claude Code trigger** | 🟡 SCRIPT-VERIFIED, session-pending | simulated hook stdin → advisories for `Election.php`, silence for non-app. ⚠️ Hooks load at session START — the wired hook fires in the NEXT Claude session, not the one that installed it |
| **VS Code extension** | ❌ NOT YET — the open item | extension is source-only (`doctor --live`: the single ✗). Requires the human: F5 dev-mode or `npx @vscode/vsce package` + install |

## The OQT itself (the reviewer's five steps — run when the extension is installed)

1. Open VS Code on this repo → the folderOpen task starts `knowledgeos dev`
   automatically (verify → prove → observe visible in the terminal panel).
2. Confirm `doctor --live` reports 6/6 ✓ (extension check goes green on install).
3. Edit a class under `app/`, press Save.
4. Within a few hundred ms: the KnowledgeOS toast appears; "Details" shows the
   advisories; the terminal shows the chain trace.
0. ⚠️ FIRST resolve any "content of the file is newer" dialogs (choose Compare, not Overwrite — Claude/git wrote the file while the editor held an old buffer). A FAILED save produces NO mtime change, so watcher silence after a failed save is CORRECT behavior, not a defect.
5. Attach the trace lines + a screenshot/paste of the toast to this report,
   change Status to **PASSED**, and record the OE entry (first live IDE
   feedback in a real session = the behavioral-milestone evidence source).

## What passing means — and what it does not

Passing closes the *operational* gap for the live experience. It does NOT yet
evidence the behavioral milestone ("first developer changes code because of a
warning") — that requires a developer acting on what they saw, which this
report's OE entry would begin tracking.

---
*Evidence sources: session log 2026-08-04 · watch-usage.jsonl · test-presence.jsonl · commits `b7239aa75`, `822ad8d23`, `431c8101b`, `e90e40e31`. The register's ACTIVE rows remain implementation claims until their row in the table above says YES.*
