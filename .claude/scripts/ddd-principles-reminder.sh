#!/usr/bin/env bash
# PreToolUse hook (Write|Edit) — NON-BLOCKING reminder of the DDD Tactical
# Governance Principles (AST-014; ordered by explicit DA ruling 2026-07-26,
# recorded with early-promotion exception R-39).
#
# Fires when a file on the TACTICAL DDD SURFACE is about to be created or
# edited: app/Contexts/<X>/Domain/** or app/Domain/** (aggregates, VOs,
# domain events, repository interfaces, domain services). Other paths are
# silent — the platform stays methodology-agnostic; these principles bind
# only tactical DDD work (see the canonical module).
#
# POINTS, NEVER RESTATES (rules live once): the reminder names the seven
# principles and the canonical home; the rule text lives ONLY in
#   engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md
# (PublicDigit binding: docs/architecture/governance/DDD_PRINCIPLES.md).
#
# STATE-AWARE: fires at most once per session-day (same marker pattern as
# discipline-gate-reminder.sh). Never blocks — a checkpoint, not a wall.
set -u
cd "$(dirname "$0")/../.." || exit 0

marker=".claude/runtime/$(date +%F)-ddd-principles-reminded.flag"
[ -f "$marker" ] && exit 0

# Tool input arrives on STDIN or as $1 (same contract as the sibling hooks).
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

  // Tactical DDD surface only: Domain layer of any bounded context.
  $isTactical = preg_match("~^app/Contexts/[^/]+/Domain/~", $file)
             || preg_match("~^app/Domain/~", $file);
  if (!$isTactical) exit(0);

  $msg = "DDD Tactical Governance (active for this file — tactical DDD surface: $file):\n"
       . "  The 7 principles bind this work: Methodological Fitness Rule | APP (aggregates protect\n"
       . "  properties, not objects) | VODP (VOs derive from invariants) | ASP (absence is a recorded\n"
       . "  decision) | ADP (derive from the preceding frozen artifact) | DMT (classify dormant\n"
       . "  mechanisms by evidence) | RMSP (repository surface = invariant enforcement only).\n"
       . "  -> Canonical (rule text lives ONLY here): engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md\n"
       . "  -> Project binding: docs/architecture/governance/DDD_PRINCIPLES.md\n"
       . "  (non-blocking checkpoint, once per session; AST-014)";

  @mkdir($argv[2], 0777, true);
  @touch($argv[3]);

  echo json_encode([
      "systemMessage" => $msg,
      "hookSpecificOutput" => [
          "hookEventName" => "PreToolUse",
          "additionalContext" => $msg,
      ],
  ]);
' "$input" ".claude/runtime" "$marker" 2>/dev/null
exit 0
