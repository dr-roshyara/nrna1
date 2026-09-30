#!/usr/bin/env bash
# PreToolUse hook (Write|Edit) — deterministic CONTEXT routing by TARGET PATH.
#
# Rule (see .claude/CLAUDE.md, "CONTEXT routing"):
#   a Write/Edit to a file under app/  => PublicDigit work
#                                      => working-state file is .claude/CONTEXT-publicdigit.md
#   everything else                    => .claude/CONTEXT.md (unchanged default)
#
# Unlike context-router.py (SessionStart, git-history based, suggestion only), this
# fires at the moment of the edit, on the file actually being changed — so the
# routing no longer depends on what the previous session touched.
#
# Non-blocking. Fires at most ONCE PER SESSION (marker keyed by session_id; falls
# back to the date) — the routing only needs to be stated once, not per file.
set -u
cd "$(dirname "$0")/../.." || exit 0

# Tool input arrives on STDIN or as $1 (CLAUDE_TOOL_INPUT), as for the other hooks.
input="$(cat 2>/dev/null)"
[ -z "$input" ] && input="${1:-}"
[ -z "$input" ] && exit 0

php -r '
  $raw = $argv[1];
  $in  = json_decode($raw, true);
  $file = is_array($in) ? ($in["tool_input"]["file_path"] ?? ($in["file_path"] ?? "")) : "";
  if ($file === "" && preg_match("/\"file_path\"\s*:\s*\"((?:[^\"\\\\]|\\\\.)*)\"/", $raw, $m)) {
      $file = str_replace([chr(92).chr(34), chr(92).chr(92)], [chr(34), chr(92)], $m[1]);
  }
  if ($file === "") exit(0);

  $file = str_replace(chr(92), "/", $file);
  $cwd  = str_replace(chr(92), "/", getcwd());
  if (stripos($file, $cwd . "/") === 0) $file = substr($file, strlen($cwd) + 1);
  if (strpos($file, "./") === 0) $file = substr($file, 2);

  if (!preg_match("~^app/~", $file)) exit(0);

  $session = is_array($in) ? preg_replace("/[^A-Za-z0-9_-]/", "", (string) ($in["session_id"] ?? "")) : "";
  $key     = $session !== "" ? $session : date("Y-m-d");
  $marker  = $argv[2] . "/context-route-publicdigit-" . $key . ".flag";
  if (is_file($marker)) exit(0);

  $msg = "CONTEXT routing: $file is under app/ => this is PublicDigit work.\n"
       . "  -> Working-state file: .claude/CONTEXT-publicdigit.md (read it; record PublicDigit state/next actions THERE).\n"
       . "  -> .claude/CONTEXT.md stays the KnowledgeOS context — do not log PublicDigit work in it.\n"
       . "  (non-blocking, once per session; see .claude/CLAUDE.md \"CONTEXT routing\")";

  @mkdir($argv[2], 0777, true);
  @touch($marker);

  echo json_encode([
      "systemMessage" => $msg,
      "hookSpecificOutput" => [
          "hookEventName" => "PreToolUse",
          "additionalContext" => $msg,
      ],
  ]);
' "$input" ".claude/runtime" 2>/dev/null
exit 0
