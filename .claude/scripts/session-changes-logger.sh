#!/usr/bin/env bash
# PostToolUse hook (Write|Edit): records every modified file into a per-day
# scratch list (.claude/sessions/YYYY-MM-DD-files.log, gitignored) so the
# session log's "Completed" section can be written from facts, not memory.
# Deliberately does NOT touch the session log itself — that would defeat the
# Stop-hook staleness check and fill the log with noise.
set -u
cd "$(dirname "$0")/../.." || exit 0

# Parse + repo-relativize entirely in PHP (jq here is a broken Node shim; PHP
# handles Windows paths natively). Regex fallback covers escaping oddities.
rel=$(php -r '
  $raw = stream_get_contents(STDIN);
  $in = json_decode($raw, true);
  $file = $in["tool_input"]["file_path"] ?? "";
  if ($file === "" && preg_match("/\"file_path\"\s*:\s*\"((?:[^\"\\\\]|\\\\.)*)\"/", $raw, $m)) {
      // Fallback for malformed escaping: unescape ONLY \" and \\ — never
      // stripcslashes(), which would turn \n /\t path segments into control chars.
      $file = str_replace([chr(92).chr(34), chr(92).chr(92)], [chr(34), chr(92)], $m[1]);
  }
  if ($file === "") exit(0);
  $file = str_replace(chr(92), "/", $file);   // chr(92) = backslash — no escaping ambiguity
  $cwd  = str_replace(chr(92), "/", getcwd());
  if (stripos($file, $cwd . "/") === 0) $file = substr($file, strlen($cwd) + 1);
  echo $file;
' 2>/dev/null)

[ -z "$rel" ] && exit 0

# Skip session/runtime artifacts themselves (avoid self-recursion noise).
case "$rel" in
  .claude/sessions/*|.claude/runtime/*) exit 0 ;;
esac

# Plan lifecycle stamping (documentation, distinct from runtime state):
# if a plan document with a "Last Updated:" line was modified, refresh the date.
# Only UPDATES an existing line — never inserts, so harness-generated plan
# files without the header are left untouched. sed here is not a tool call,
# so it cannot re-trigger this hook (no recursion).
case "$rel" in
  .claude/plans/*.md)
    if grep -q '^\*\*Last Updated:\*\*' "$rel" 2>/dev/null; then
      sed -i "s/^\*\*Last Updated:\*\*.*/\*\*Last Updated:\*\* $(date +%F)/" "$rel"
    fi
    ;;
esac

log=".claude/runtime/$(date +%F)-files.log"
state=".claude/runtime/$(date +%F)-state.json"
mkdir -p .claude/runtime
# printf, not echo: Git Bash echo interprets backslash escapes in paths.
grep -qxF "$rel" "$log" 2>/dev/null || printf '%s\n' "$rel" >> "$log"

# Shared session state (chief-architect suggestion): derived from the files
# list so hooks make informed decisions instead of guessing.
php -r '
  $log = $argv[1]; $state = $argv[2];
  $files = is_file($log) ? array_values(array_filter(array_map("trim", file($log)))) : [];
  $touched = fn(string $needle) => (bool) array_filter($files, fn($f) => stripos($f, $needle) !== false);
  file_put_contents($state, json_encode([
      "schema"         => 1,
      "session"        => date("Y-m-d"),
      "filesModified"  => count($files),
      "files"          => $files,
      "planTouched"    => $touched(".claude/plans/"),
      "contextTouched" => $touched(".claude/CONTEXT.md"),
      "memoryTouched"  => $touched(".claude/MEMORY.md"),
      "updatedAt"      => date("c"),
  ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
' "$log" "$state" 2>/dev/null
exit 0
