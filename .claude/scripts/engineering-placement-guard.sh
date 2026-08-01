#!/usr/bin/env bash
# PreToolUse hook (Write|Edit) — NON-BLOCKING checkpoint for canonical placement.
#
# Fires when a NEW file is about to be created under engineering/.
#
# WHY THIS EXISTS. On 2026-08-01 a methodology module was written straight into
# engineering/knowledge/methodology/ — the canon directory — while sitting at the
# Research stage of the ES-006.1 promotion ladder, never piloted and never
# qualified. Three successive commissions then searched for the "missing rule"
# that would have prevented it. There was none: ES-006.1 governs maturity and
# ES-005.3 governs placement, and both already said what to do.
#
# The defect was NOT absent governance. It was a governed action performed
# without consulting its governing standards. Rules cannot fix that — the rules
# were already correct. Only the workflow can, which is what this is.
#
# Placement of this guard, by its own litmus: it is runtime session tooling, so
# ES-005.3 puts it in the runtime mount (.claude/scripts/), NOT in engineering/.
#
# NON-BLOCKING by design (exit 1, never 2): creating a file under engineering/ is
# legitimate when governed. The hook cannot evaluate the answers to its own
# questions — only the author can — and a wall that is always dismissed teaches
# less than a checkpoint that is read. Same posture as
# discipline-gate-reminder.sh: "a checkpoint, not a wall".
set -u

input="$(cat 2>/dev/null)"
[ -z "$input" ] && input="${1:-}"
[ -z "$input" ] && exit 0

target="$(php -r '
  $raw = $argv[1];
  $in   = json_decode($raw, true);
  $file = is_array($in) ? ($in["tool_input"]["file_path"] ?? ($in["file_path"] ?? "")) : "";
  if ($file === "" && preg_match("/\"file_path\"\s*:\s*\"((?:[^\"\\\\]|\\\\.)*)\"/", $raw, $m)) {
      $file = str_replace([chr(92).chr(34), chr(92).chr(92)], [chr(34), chr(92)], $m[1]);
  }
  if ($file === "") exit(0);
  $file = str_replace(chr(92), "/", $file);
  if (strpos($file, "/engineering/") === false && strpos($file, "engineering/") !== 0) exit(0);
  // Only NEW files: an edit to something already placed is not a placement act.
  if (file_exists($file)) exit(0);
  echo $file;
' "$input" 2>/dev/null)"

[ -z "$target" ] && exit 0

# Patterns must match BOTH absolute and repo-relative paths — the tool sends either.
case "$target" in
  engineering/verification/reports/*|*/engineering/verification/reports/*)
      exit 0 ;;                                     # verification output, not a governed placement
  engineering/knowledge/methodology/*|*/engineering/knowledge/methodology/*)
      canon=1 ;;
  *)  canon=0 ;;
esac

{
  printf '\n'
  printf 'CANONICAL PLACEMENT CHECKPOINT — new file under engineering/\n'
  printf '   %s\n' "$target"
  if [ "$canon" = "1" ]; then
    printf '\n   *** This is the CANON directory. It is for ADOPTED Engineering Standards. ***\n'
  fi
  printf '\n'
  printf '   Placement is a GOVERNED ACTION. Answer all four before writing:\n'
  printf '     1. Which standard governs MATURITY?    -> ES-006.1 promotion ladder\n'
  printf '        Research -> Pilot -> Qualification -> Engineering Standard -> Stable Capability\n'
  printf '     2. Which standard governs PLACEMENT?   -> ES-005.3 Placement Litmus\n'
  printf '     3. Does this location COMPLY?\n'
  printf '        ES-005.3: "Research artifacts remain project-side (docs/implementation/)\n'
  printf '                   until promoted through qualification (ES-006 ladder)."\n'
  printf '        Maturity governs, not reusability: domain-independent but UNPROVEN is\n'
  printf '        still Research, and Research does not belong in engineering/.\n'
  printf '     4. If it does not comply, is there an APPROVED EXCEPTION on the record?\n'
  printf '        Precedent: R-39 — ruled, named as an exception, given a validation\n'
  printf '        expectation. All three, or it is not an exception.\n'
  printf '\n'
  printf '   Record one outcome: COMPLIANT / APPROVED EXCEPTION / REQUIRES ARB DECISION.\n'
  printf '   If the answer is the third, stop and refer it.\n'
  printf '\n'
  printf '   Provenance: on 2026-08-01 a Research-stage module was written into the canon\n'
  printf '   directory. The rules already forbade it; they were simply not consulted.\n'
  printf '   (non-blocking checkpoint — the obligation is ES-005.3 and ES-006.1 themselves)\n'
  printf '\n'
} 1>&2
exit 1
