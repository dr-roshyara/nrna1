#!/usr/bin/env bash
# ClaudeCodeTrigger — the Claude Code adapter of the ObservationTrigger port
# (commission 2026-08-04: "Claude edits a class → metrics appear automatically").
#
# PostToolUse hook (Write|Edit): when Claude modifies a production class,
# run the observation pipeline on THAT file and surface advisories into the
# session. Detects and delegates — no metric logic, no thresholds here.
# Ephemeral (same publication rule as file-save: displays, never writes the
# evidence streams; commit remains the stream writer). Always exits 0 —
# advisory, never blocking.
set -u
cd "$(dirname "$0")/../.." || exit 0

# Parse + repo-relativize (same approach as session-changes-logger.sh).
rel=$(php -r '
  $raw = stream_get_contents(STDIN);
  $in = json_decode($raw, true);
  $file = $in["tool_input"]["file_path"] ?? "";
  if ($file === "" && preg_match("/\"file_path\"\s*:\s*\"((?:[^\"\\\\]|\\\\.)*)\"/", $raw, $m)) {
      $file = str_replace([chr(92).chr(34), chr(92).chr(92)], [chr(34), chr(92)], $m[1]);
  }
  if ($file === "") exit(0);
  $file = str_replace(chr(92), "/", $file);
  $cwd  = str_replace(chr(92), "/", getcwd());
  if (stripos($file, $cwd . "/") === 0) $file = substr($file, strlen($cwd) + 1);
  echo $file;
' 2>/dev/null)

[ -z "$rel" ] && exit 0

# Production classes only — identical scope to every other trigger adapter.
case "$rel" in
  app/*.php) ;;
  *) exit 0 ;;
esac
case "$rel" in
  *.blade.php) exit 0 ;;
esac
[ -f "$rel" ] || exit 0   # deletions produce nothing to observe

out=$(php scripts/observations/observe.php --source=claude-code "$rel" 2>/dev/null)
# Surface only when there are advisories — silence is the good case.
if printf '%s' "$out" | grep -q '⚠'; then
  echo "KnowledgeOS (ClaudeCodeTrigger — advisory, you decide):"
  printf '%s\n' "$out" | tail -n +2
fi
exit 0
