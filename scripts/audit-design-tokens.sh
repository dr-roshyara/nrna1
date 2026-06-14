#!/usr/bin/env bash

# ═══════════════════════════════════════════════════════════════
# audit-design-tokens.sh — Design Token Audit v2
# ═══════════════════════════════════════════════════════════════
#
# Architecture Reference:
#   ADR-003 Shell Visual Identity
#   Architecture Baseline v1
#   Governance Transparency Developer Guide
#
# Scans Vue files and reports any raw Tailwind color tokens
# that should be replaced with semantic equivalents.
#
# This is a READ-ONLY audit. No files are modified.
#
# Usage:
#   ./scripts/audit-design-tokens.sh file1.vue [file2.vue ...]
#   ./scripts/audit-design-tokens.sh resources/js/Pages/Vote/DemoVote/Guide.vue
#
# Exit codes: 0 = clean, 1 = violations found
# ═══════════════════════════════════════════════════════════════

set -euo pipefail

# ── Color families known to appear in this codebase ──────
COLOR_FAMILIES=(
  slate gray stone zinc neutral
  indigo purple violet
  green emerald
  red rose
  orange amber yellow
  cyan sky teal
  lime
  pink fuchsia
)

# ── Map color → possible semantic token (suggestion only) ─
suggest_semantic() {
  local shade="$1"
  case "$shade" in
    slate|gray|stone|zinc|neutral) echo "→ consider neutral (review context)" ;;
    indigo)                        echo "→ consider primary (review context)" ;;
    purple|violet)                 echo "→ consider accent (review context)" ;;
    green|emerald|lime)            echo "→ consider success (review context)" ;;
    red|rose)                      echo "→ consider danger (review context)" ;;
    orange|amber|yellow)           echo "→ consider warning (review context)" ;;
    cyan|sky|teal)                 echo "→ consider primary or info (review context)" ;;
    pink|fuchsia)                  echo "→ manual review required" ;;
    *)                             echo "→ manual review required" ;;
  esac
}

TOTAL_VIOLATIONS=0
TOTAL_FILES=0

# ── Process each file argument ────────────────────────────
for TARGET in "$@"; do
  [ ! -f "$TARGET" ] && continue

  FILE_VIOLATIONS=0
  TOTAL_FILES=$((TOTAL_FILES + 1))

  echo "🔬 $TARGET"

  for shade in "${COLOR_FAMILIES[@]}"; do
    count=$(grep -Ec "bg-${shade}-[0-9]|text-${shade}-[0-9]|border-${shade}-[0-9]|from-${shade}-|to-${shade}-" "$TARGET" 2>/dev/null || true)
    if [ "$count" -gt 0 ]; then
      suggestion=$(suggest_semantic "$shade")
      echo "  ❌ $count x raw \"$shade-*\" $suggestion"
      FILE_VIOLATIONS=$((FILE_VIOLATIONS + count))
    fi
  done

  if [ "$FILE_VIOLATIONS" -eq 0 ]; then
    echo "  ✅ Clean — no raw Tailwind color tokens"
  fi

  TOTAL_VIOLATIONS=$((TOTAL_VIOLATIONS + FILE_VIOLATIONS))
  echo ""
done

if [ "$TOTAL_FILES" -eq 0 ]; then
  echo "❌ No valid .vue files provided"
  exit 1
fi

echo "═══ Result: $TOTAL_FILES file(s), $TOTAL_VIOLATIONS token(s) to review ═══"
exit $(( TOTAL_VIOLATIONS > 0 ? 1 : 0 ))
