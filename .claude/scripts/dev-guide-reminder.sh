#!/usr/bin/env bash
# Stop hook — non-blocking reminder that a Developer Guide is part of the
# Definition of Done for every implementation step (see .claude/CLAUDE.md,
# "Developer Guide — Definition of Done").
#
# Fires ONLY when today's file-log shows implementation code changed
# (app/ or database/migrations/) but NO developer_guide/ file was touched.
# Discussion-only or docs-only sessions stay completely silent. Once any
# developer_guide/ file is edited today, this stays silent for the rest of
# the day. Mirrors session-log-reminder.sh (bash wrapper + php parsing).
set -u
cd "$(dirname "$0")/../.." || exit 0

today=$(date +%F)
log=".claude/runtime/$today-files.log"

# No file-log today -> nothing changed -> silence.
[ -f "$log" ] || exit 0

php -r '
  $log   = $argv[1];
  $lines = array_filter(array_map("trim", file($log) ?: []));

  $codeTouched  = false;
  $guideTouched = false;
  foreach ($lines as $f) {
      if (preg_match("~^app/~", $f) || preg_match("~^database/migrations/~", $f)) {
          // ignore pure test edits under app/ (there are none by convention)
          $codeTouched = true;
      }
      if (preg_match("~^developer_guide/~", $f)) {
          $guideTouched = true;
      }
  }

  if ($codeTouched && !$guideTouched) {
      $msg = "Developer Guide (Definition of Done): implementation code changed today "
           . "but no developer_guide/ file was touched.\n"
           . "  -> Write or update the step guide under developer_guide/<area>/ "
           . "before this step is Done (see .claude/CLAUDE.md).\n"
           . "  (non-blocking; modified files: $log)";
      echo json_encode(["systemMessage" => $msg]);
  }
' "$log" 2>/dev/null
exit 0
