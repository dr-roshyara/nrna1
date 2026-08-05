#!/usr/bin/env bash
# Stop hook — non-blocking project-state sync report, driven by the shared
# session state (runtime/YYYY-MM-DD-state.json) maintained by the changes
# logger. Prints a checklist ONLY when files were modified today AND state is
# out of sync. Discussion-only sessions stay completely silent.
set -u
cd "$(dirname "$0")/../.." || exit 0

today=$(date +%F)
log=".claude/sessions/$today.md"
state=".claude/runtime/$today-state.json"

# No modifications today -> nothing to sync -> silence.
[ -f "$state" ] || exit 0

php -r '
  $today  = $argv[1];
  $log    = ".claude/sessions/$today.md";
  $state  = json_decode(file_get_contents(".claude/runtime/$today-state.json"), true) ?: [];
  $n      = $state["filesModified"] ?? 0;
  if ($n === 0) exit(0);

  $issues = [];
  if (!is_file($log)) {
      $issues[] = "x Session log $log missing ($n files modified today)";
  } elseif (time() - filemtime($log) > 45 * 60) {
      $issues[] = "x Session log $log not updated in 45+ min ($n files modified today)";
  }
  if (empty($state["contextTouched"]) && is_file(".claude/CONTEXT.md")
      && date("Y-m-d", filemtime(".claude/CONTEXT.md")) !== $today) {
      $issues[] = "x CONTEXT.md not updated today - refresh active ticket / next action if they changed";
  }

  if ($issues) {
      // Informational lines (never issues on their own):
      $issues[] = ($state["planTouched"] ?? false)
          ? "o plan touched today"
          : "o no plan file touched today (fine for small fixes; update the active plan for ticket work)";
      $msg = "Project-state sync (CLAUDE.md):\n  " . implode("\n  ", $issues)
           . "\n  (non-blocking; modified files: .claude/runtime/$today-files.log)";
      echo json_encode(["systemMessage" => $msg]);
  }
' "$today" 2>/dev/null
exit 0
