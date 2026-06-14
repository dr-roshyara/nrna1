#!/bin/bash

# ═══════════════════════════════════════════════════════════════
# verify.sh — Master Design Governance Orchestrator v3
#
# Governance Architecture:
#   UI Layer
#   ├─ Gate 1: Design Token Compliance (design-check.sh --strict)
#   ├─ Gate 2: Quick Token Count      (check-design-tokens.sh)
#   └─ Gate 3: Component Audit        (component-audit.sh --strict)
#
#   Security Layer
#   └─ Gate 4: Role & Permission      (check_roles.php)
#
# Runs all gates sequentially. Exit code 0 only if ALL pass.
# ═══════════════════════════════════════════════════════════════

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PASS=0
FAIL=1
START_TIME=$(date +%s%N)

echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║  🎨 UI/UX Design Governance — Full Gate         ║"
echo "╚══════════════════════════════════════════════════╝"
echo ""

run_gate() {
  local name="$1"
  local command="$2"
  local severity="${3:-fail}"  # fail | warn
  local tmpfile
  local exit_code

  tmpfile=$(mktemp)

  echo "──────────────────────────────────────────────"
  echo "  🔍 Gate: $name"
  echo "──────────────────────────────────────────────"

  # Run the command, capturing both stdout and stderr
  set +e
  eval "$command" > "$tmpfile" 2>&1
  exit_code=$?
  set -e

  # Show gate output (indented)
  if [ -s "$tmpfile" ]; then
    while IFS= read -r line; do
      echo "    $line"
    done < "$tmpfile"
  fi

  if [ "$exit_code" -eq $PASS ]; then
    echo ""
    echo "  ✅ Gate passed: $name"
    echo ""
    rm -f "$tmpfile"
    return $PASS
  else
    echo ""
    if [ "$severity" = "fail" ]; then
      echo "  ❌ Gate FAILED: $name"
      echo ""
      rm -f "$tmpfile"
      return $FAIL
    else
      echo "  ⚠️  Gate WARNING (non-blocking): $name"
      echo ""
      rm -f "$tmpfile"
      return $PASS
    fi
  fi
}

OVERALL_STATUS=$PASS

# ── Gate 1: Design Token Compliance ──────────────────────
if ! run_gate "Design Token Compliance" \
  "bash \"$SCRIPT_DIR/design-check.sh\" --strict" \
  "fail"; then
  OVERALL_STATUS=$FAIL
fi

# ── Gate 2: Quick Token Count ───────────────────────────
if ! run_gate "Quick Token Count" \
  "bash \"$SCRIPT_DIR/check-design-tokens.sh\"" \
  "fail"; then
  OVERALL_STATUS=$FAIL
fi

# ── Gate 3: Component Audit ─────────────────────────────
if ! run_gate "Component Audit (anti-inline linting)" \
  "bash \"$SCRIPT_DIR/component-audit.sh\" --strict" \
  "fail"; then
  OVERALL_STATUS=$FAIL
fi

# ── Gate 4: Role & Permission Governance ────────────────
if ! run_gate "Role & Permission Governance" \
  "php \"$SCRIPT_DIR/check_roles.php\" --strict" \
  "fail"; then
  OVERALL_STATUS=$FAIL
fi

# ── Gate 5: Domain Purity (warning only) ────────────────
if ! run_gate "Domain Purity (warning only)" \
  "bash \"$SCRIPT_DIR/check-domain-purity.sh\"" \
  "warn"; then
  OVERALL_STATUS=$FAIL
fi

# ── Gate 6: DDD Structure Visibility (warning only) ─────
if ! run_gate "DDD Structure Visibility (warning only)" \
  "bash \"$SCRIPT_DIR/structure-check.sh\"" \
  "warn"; then
  OVERALL_STATUS=$FAIL
fi

# ── Future Gates (placeholder) ──────────────────────────
# Gate 4: Accessibility (Phase 4)
#   bash "$SCRIPT_DIR/a11y-check.sh"
# Gate 5: Visual Regression (Phase 3)
#   npx playwright test tests/Visual

# ── Elapsed time ────────────────────────────────────────
END_TIME=$(date +%s%N)
ELAPSED_MS=$(( (END_TIME - START_TIME) / 1000000 ))
ELAPSED_SEC=$(( ELAPSED_MS / 1000 ))
ELAPSED_REMAINDER=$(( ELAPSED_MS % 1000 ))

# ── Summary ─────────────────────────────────────────────
echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║  📊 Design Governance Summary                    ║"
echo "╚══════════════════════════════════════════════════╝"
echo ""
echo "  Duration: ${ELAPSED_SEC}.$(printf '%03d' $ELAPSED_REMAINDER)s"
echo ""

if [ "$OVERALL_STATUS" -eq $PASS ]; then
  echo "  ✅ ALL GATES PASSED — Design governance compliant."
  exit 0
else
  echo "  ❌ SOME GATES FAILED — Review output above."
  echo "     Run individual gates for detailed output:"
  echo "       ./scripts/design-check.sh"
  echo "       ./scripts/component-audit.sh"
  exit 1
fi
