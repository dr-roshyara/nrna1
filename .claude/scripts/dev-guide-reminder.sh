#!/usr/bin/env bash
# Stop hook — non-blocking reminder that a Developer Guide is part of the
# Definition of Done for every implementation step (see .claude/CLAUDE.md,
# "Developer Guide — Definition of Done").
#
# AREA-AWARE (fixed 2026-07-08): compares the CODE AREAS changed today against
# the developer_guide AREAS touched today, and nudges per code-area that has no
# matching guide activity. This means a guide written for an UNRELATED track
# (e.g. developer_guide/ai_platform/ while Contestation code changed) no longer
# silences the reminder for the area that actually changed.
#
# Area derivation:
#   app/Contexts/<X>/...      -> <x>            (the bounded context)
#   app/<seg>/...             -> <seg>
#   database/migrations/...   -> database
#   scripts/<seg>/...         -> <seg>          (engineering tooling — blind spot fixed 2026-08-04, see OE-KOS-3)
#   developer_guide/<area>/.. -> <area>         (guide areas touched)
# Alias map handles non-obvious code->guide folder names (e.g. Shared messaging
# lives under developer_guide/audit_system/). Non-blocking; docs-only/discussion
# sessions stay silent. Mirrors session-log-reminder.sh (bash wrapper + php).
set -u
cd "$(dirname "$0")/../.." || exit 0

today=$(date +%F)
log=".claude/runtime/$today-files.log"

# No file-log today -> nothing changed -> silence.
[ -f "$log" ] || exit 0

php -r '
  $log   = $argv[1];
  $lines = array_filter(array_map("trim", file($log) ?: []));

  // Known non-obvious code-area -> guide-folder aliases.
  $alias = [
      "shared"       => "audit_system",
      // engineering observation tooling (2026-08-04): both script areas share one guide area
      "metrics"      => "engineering_observations",
      "observations" => "engineering_observations",
  ];

  $codeAreas  = [];   // expected guide-area => true (from code changes)
  $guideAreas = [];   // guide-area => true (from developer_guide/ changes)

  foreach ($lines as $f) {
      if (preg_match("~^app/Contexts/([^/]+)/~", $f, $m)) {
          $a = strtolower($m[1]);
          $codeAreas[$alias[$a] ?? $a] = true;
      } elseif (preg_match("~^app/([^/]+)/~", $f, $m)) {
          $a = strtolower($m[1]);
          $codeAreas[$alias[$a] ?? $a] = true;
      } elseif (preg_match("~^database/migrations/~", $f)) {
          $codeAreas["database"] = true;
      } elseif (preg_match("~^scripts/([^/]+)/~", $f, $m)) {
          // Engineering tooling is implementation too (blind spot fixed 2026-08-04).
          $a = strtolower($m[1]);
          $codeAreas[$alias[$a] ?? $a] = true;
      }
      if (preg_match("~^developer_guide/([^/]+)/~", $f, $m)) {
          $guideAreas[strtolower($m[1])] = true;
      }
  }

  // Code areas with no matching developer_guide/ activity today.
  $missing = array_keys(array_diff_key($codeAreas, $guideAreas));

  if ($missing) {
      $list = implode(", ", array_map(fn($a) => "developer_guide/$a/", $missing));
      $msg = "Developer Guide (Definition of Done): implementation code changed today "
           . "in area(s) with NO matching developer_guide/ update: " . implode(", ", $missing) . ".\n"
           . "  -> Write or update the step guide under: " . $list . "\n"
           . "  (area-aware; an unrelated track''s guide no longer silences this. "
           . "Non-blocking; see .claude/CLAUDE.md · modified files: $log)";
      echo json_encode(["systemMessage" => $msg]);
  }
' "$log" 2>/dev/null
exit 0
