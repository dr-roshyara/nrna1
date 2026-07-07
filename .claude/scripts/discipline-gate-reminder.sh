#!/usr/bin/env bash
# PreToolUse hook (Write|Edit) — NON-BLOCKING tripwire for the project discipline:
#   Business -> DDD -> Architecture -> Tests -> Implementation
# (see .claude/CLAUDE.md, "Development Discipline").
#
# Fires ONLY when a BRAND-NEW file is about to be created that is either a TEST
# (tests/**) or PRODUCTION code (app/**.php) — i.e. the "Tests" or "Implementation"
# stages. It injects a reminder to confirm the upstream artifacts exist first
# (a DDD model / architecture decision), so we never encode assumptions in a test
# or class before the model justifies them. Edits to existing files stay silent
# (you are already past the gate). Never blocks — it is a checkpoint, not a wall.
set -u
cd "$(dirname "$0")/../.." || exit 0

# Tool input arrives on STDIN (like the changes logger) or as $1 (CLAUDE_TOOL_INPUT).
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

  // Only NEW files trigger — an edit/overwrite of an existing file is past the gate.
  if (is_file($file)) exit(0);

  $isTest = (bool) preg_match("~^tests/~", $file);
  $isCode = (bool) preg_match("~^app/.*\.php$~", $file);
  if (!$isTest && !$isCode) exit(0);

  $stage = $isTest ? "TEST" : "IMPLEMENTATION";
  $msg = "Discipline check (Business -> DDD -> Architecture -> Tests -> Implementation): "
       . "about to create a new $stage file ($file).\n"
       . "  -> Confirm the upstream artifacts exist FIRST: business need · DDD model/ownership · "
       . "an approved Architecture Decision.\n"
       . "  -> For a discovered gap use: Finding -> Architecture Decision -> RED -> GREEN -> Certification. "
       . "Do not encode assumptions in a test/class before the model justifies them.\n"
       . "  (non-blocking checkpoint; see .claude/CLAUDE.md)";

  echo json_encode([
      "systemMessage" => $msg,
      "hookSpecificOutput" => [
          "hookEventName" => "PreToolUse",
          "additionalContext" => $msg,
      ],
  ]);
' "$input" 2>/dev/null
exit 0
