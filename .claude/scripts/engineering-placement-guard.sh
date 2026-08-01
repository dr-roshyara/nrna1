#!/usr/bin/env bash
# PreToolUse hook (Write|Edit) — NON-BLOCKING checkpoint for a governed action:
# creating a new artifact whose PLACEMENT is governed — under engineering/, or in
# one of the documentation locations the roots ADR measured as domain-mixed.
#
# Two branches, one purpose. Under engineering/ the governing rules are prose, so
# the hook asks generic questions and derives which standards answer them. Under
# docs/ the derivation is EXECUTABLE (scripts/doc-placement.php over the registry
# at docs/knowledge/schema/documentation-placement.yaml), so the hook points at
# the resolver instead of asking. Neither branch restates a rule.
#
# WHY THIS EXISTS. On 2026-08-01 a methodology module was written straight into
# the canon directory while still at the first stage of the promotion ladder.
# Three commissions then searched for the "missing rule" that would have
# prevented it. There was none — the governing standards already said what to do
# and were simply not consulted. The defect was not absent governance but a
# governed action performed without consulting its governing standards. Rules
# could not fix that; only the workflow can.
#
# ── DESIGN RULE THIS SCRIPT OBEYS ──────────────────────────────────────────
#   RUNTIME TOOLING ENFORCES GOVERNANCE. IT NEVER DUPLICATES GOVERNANCE.
#
#   So this hook asks GENERIC questions and DERIVES which standards answer them
#   by reading the canonical index at runtime. It quotes no rule text and hard-
#   codes no standard number. If a standard is renumbered, superseded or
#   rehomed, the hook follows automatically — because it reads canon rather than
#   restating it. An earlier version embedded the rule text and the ES numbers;
#   that made runtime tooling a second home for governance, which is the very
#   duplication the standards forbid.
#
#   Not registered in .claude/platform/registry.yaml: R-42 ruled the registry
#   governs platform assets only, and a project-side workflow hook has no AIP
#   lineage. Registering it would require fabricating traceability.
#
# Placement of this script, by the same litmus it enforces: runtime session
# tooling belongs in the runtime mount, not in engineering/.
#
# NON-BLOCKING (exit 1, never 2): creating under engineering/ is legitimate when
# governed, and the hook cannot evaluate the answers to its own questions — only
# the author can. A wall that is always dismissed teaches less than a checkpoint
# that is read. Same posture as discipline-gate-reminder.sh.
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
  if (file_exists($file)) exit(0);   // an edit is not a placement act

  if (preg_match("#(?:^|/)engineering/#", $file)) { echo "eng " . $file; exit(0); }

  // docs/ — only the locations the roots ADR measured as domain-mixed:
  //   docs/implementation/** and files DIRECTLY under docs/ (no subdirectory).
  // Anything already inside a declared root, or in another docs/ subtree, is quiet.
  if (preg_match("#(?:^|/)docs/(.*)$#", $file, $m)) {
      $rel = $m[1];
      if (strpos($rel, "implementation/") === 0 || strpos($rel, "/") === false) {
          echo "docsmix " . $file;
      }
  }
' "$input" 2>/dev/null)"

[ -z "$target" ] && exit 0
kind="${target%% *}"
target="${target#* }"

# ── docs/ branch: the derivation is executable, so point at the resolver ──────
# Scoped to where the roots ADR MEASURED domain mixing: docs/implementation/
# (89 PKS + 3 KnowledgeOS of 183 files) and docs/ root-level files ("mixed, no
# clear domain"). Elsewhere under docs/ the hook stays quiet until migration —
# a checkpoint that fires on every document is a checkpoint nobody reads.
if [ "$kind" = "docsmix" ]; then
      registry="${CLAUDE_PROJECT_DIR:-.}/docs/knowledge/schema/documentation-placement.yaml"
      {
        printf '\n'
        printf 'GOVERNED PLACEMENT — creating documentation in a domain-mixed location\n'
        printf '   %s\n\n' "$target"
        printf '   Placement is DERIVED from classification, and the derivation is executable:\n'
        printf '     php scripts/doc-placement.php --scope=... [--maturity=...] [--domain=...]\n'
        printf '     php scripts/doc-placement.php --list        # roots and rules\n\n'
        if [ -f "$registry" ]; then
          printf '   Declared roots (from the registry — this hook hardcodes none):\n'
          sed -n 's/^ *root: *\(.*\)$/     \1/p' "$registry"
          printf '\n'
        fi
        printf '   Exit code 2 means the classification is real but its placement is UNRULED.\n'
        printf '   Record PENDING and escalate — do not invent a destination.\n\n'
        printf '   Classification precedes placement. Placement is never evidence of\n'
        printf '   classification. (non-blocking checkpoint)\n\n'
      } 1>&2
      exit 1
fi

case "$target" in
  engineering/verification/reports/*|*/engineering/verification/reports/*)
      exit 0 ;;                                     # verification output, not a governed placement
  engineering/knowledge/methodology/*|*/engineering/knowledge/methodology/*)
      canon=1 ;;
  *)  canon=0 ;;
esac

# Derive the governing standards from the canonical index — never assert them.
index="${CLAUDE_PROJECT_DIR:-.}/engineering/governance/STANDARDS_INDEX.md"
derive() {  # $1 = concept named in the index's "Hosts" column
  [ -f "$index" ] || return 0
  grep -i -- "$1" "$index" 2>/dev/null \
    | grep -o '\[ES-[0-9]\{3\}\]([^)]*)' | head -1 \
    | sed 's/^\[\(ES-[0-9]*\)\](\(.*\))$/\1 — engineering\/governance\/\2/'
}
maturity="$(derive 'promotion ladder')"
placement="$(derive 'placement litmus')"

{
  printf '\n'
  printf 'GOVERNED ACTION — creating a new artifact under engineering/\n'
  printf '   %s\n' "$target"
  [ "$canon" = "1" ] && printf '\n   *** Canon directory: reserved for ADOPTED standards. ***\n'
  printf '\n'
  printf '   Answer all four before writing:\n'
  printf '     1. What governs the artifact MATURITY?%s\n' "${maturity:+  -> $maturity}"
  printf '     2. What governs its PLACEMENT?%s\n'        "${placement:+  -> $placement}"
  printf '     3. What EVIDENCE supports this location — at what maturity is this artifact,\n'
  printf '        and does that maturity belong here?\n'
  printf '     4. If it does not comply, what EXCEPTION authorizes it, and is that exception\n'
  printf '        ruled, named as an exception, and given a validation expectation?\n'
  if [ -z "$maturity$placement" ]; then
    printf '\n   (Could not read %s — consult it directly.)\n' "engineering/governance/STANDARDS_INDEX.md"
  fi
  printf '\n'
  printf '   Record one outcome: COMPLIANT / APPROVED EXCEPTION / REQUIRES ARB DECISION.\n'
  printf '   If it is the third, stop and refer it.\n'
  printf '\n'
  printf '   (non-blocking checkpoint — the obligation is the standards themselves, which\n'
  printf '    this hook points at and deliberately does not restate)\n'
  printf '\n'
} 1>&2
exit 1
