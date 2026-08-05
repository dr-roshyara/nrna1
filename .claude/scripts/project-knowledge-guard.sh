#!/usr/bin/env bash
# PreToolUse hook (Write|Edit) — BLOCKING guard for the project-memory rule.
#
# Rule (.claude/CLAUDE.md, "Planning, Memory and Session Management"):
#   "The project repository is the single source of truth. Do not use Claude's
#    global project memory (~/.claude/...) for project-specific plans, progress,
#    or memory whenever it can be stored inside this repository."
#
# WHY THIS EXISTS: that rule was instruction-only, and instruction-only rules
# depend on the assistant reading and following them. On 2026-07-28 an assistant
# wrote a project convention into the GLOBAL auto-memory directory
# (~/.claude/projects/<slug>/memory/) despite the rule — caught by the human, not
# by the repo. Instructions are a request; a hook is a gate. This is the gate.
#
# Blocks Write/Edit whose target resolves under:
#   $HOME/.claude/projects/**   (per-project auto-memory + tool results)
#   $HOME/.claude/plans/**      (global plans — plansDirectory is ./.claude/plans)
#   $HOME/.claude/memory/**     (global memory)
#
# Deliberately NOT blocked (legitimate global config the user may ask for):
#   $HOME/.claude/CLAUDE.md · $HOME/.claude/settings*.json · $HOME/.claude/*.json
#   anything inside THIS repo, including the repo's own .claude/memory/
#
# Companion control: "autoMemoryEnabled": false in .claude/settings.json turns the
# auto-memory system off entirely (read AND write). This hook catches the rest.
#
# Hook semantics: only exit 2 blocks a PreToolUse call (stderr becomes the reason
# shown to the model). Exit 1 is a NON-blocking warning — the write still happens.
# See db-safety-check.sh, which had exactly that bug until 2026-07-12.
set -u

# Tool input arrives on STDIN or as $1 (CLAUDE_TOOL_INPUT).
input="$(cat 2>/dev/null)"
[ -z "$input" ] && input="${1:-}"
[ -z "$input" ] && exit 0

reason="$(php -r '
  $raw = $argv[1];
  $home = rtrim(str_replace(chr(92), "/", $argv[2]), "/");
  if ($home === "") exit(0);

  $in   = json_decode($raw, true);
  $file = is_array($in) ? ($in["tool_input"]["file_path"] ?? ($in["file_path"] ?? "")) : "";
  if ($file === "" && preg_match("/\"file_path\"\s*:\s*\"((?:[^\"\\\\]|\\\\.)*)\"/", $raw, $m)) {
      $file = str_replace([chr(92).chr(34), chr(92).chr(92)], [chr(34), chr(92)], $m[1]);
  }
  if ($file === "") exit(0);

  $file = str_replace(chr(92), "/", $file);
  // Literal "~/" never reaches the filesystem as home, but normalize it anyway.
  if (strpos($file, "~/") === 0) $file = $home . substr($file, 1);
  // Relative paths resolve inside the repo (cwd) — always allowed.
  if ($file[0] !== "/" && !preg_match("~^[A-Za-z]:/~", $file)) exit(0);

  $guarded = ["/.claude/projects/", "/.claude/plans/", "/.claude/memory/"];
  foreach ($guarded as $dir) {
      if (strpos($file, $home . $dir) === 0) {
          echo $file . "\n" . rtrim($dir, "/");
          exit(0);
      }
  }
' "$input" "$HOME" 2>/dev/null)"

[ -z "$reason" ] && exit 0

target="$(printf '%s' "$reason" | sed -n 1p)"
dir="$(printf '%s' "$reason" | sed -n 2p)"

{
  printf '\n'
  printf '⛔ BLOCKED: write to GLOBAL Claude storage, outside the repository.\n'
  printf '   Target: %s\n' "$target"
  printf '   Guarded area: $HOME%s/\n' "$dir"
  printf '\n'
  printf '   This project keeps ALL project knowledge in-repo (.claude/CLAUDE.md):\n'
  printf '     "The project repository is the single source of truth."\n'
  printf '\n'
  printf '   Write to the in-repo equivalent instead:\n'
  printf '     stable project knowledge  -> .claude/MEMORY.md\n'
  printf '     current working state     -> .claude/CONTEXT.md\n'
  printf '     daily session log         -> .claude/sessions/YYYY-MM-DD.md\n'
  printf '     runtime work plan         -> .claude/plans/\n'
  printf '     governed engineering plan -> docs/plans/YYYYMMDD-HHMM-<what>-plan.md (ES-004.2)\n'
  printf '\n'
  printf '   Everything in .claude/ is version-controlled, reviewable, and shared with\n'
  printf '   every developer and AI assistant. Global storage is none of those things.\n'
  printf '\n'
} 1>&2
exit 2
