#!/bin/bash

# Design System Compliance Check
# Non-blocking metrics collection during migration phase
# Becomes hard-blocking after Phase 4 (violations < 100)

set -e

echo "🎨 Design System Compliance Check"
echo "=================================="

# Configuration
BASELINE=613
CURRENT_COUNT=0

# Helper function to count violations
count_violations() {
  local pattern=$1
  local label=$2

  # Get count - use proper grep with word boundaries
  # Limit search to current state files only (not node_modules, dist, etc.)
  local count=$(timeout 5 grep -rn "$pattern" resources/js/Pages/ --include="*.vue" 2>/dev/null | \
    grep -v "<!--" | grep -v "node_modules" | \
    wc -l)

  echo "$count"
  return $count
}

# 1. Count raw color violations
echo ""
echo "📊 Color Token Violations:"
echo "─────────────────────────"

BLUES=$(count_violations 'bg-blue-[0-9]' "Blue background")
REDS=$(count_violations 'bg-red-[0-9]' "Red background")
GRAYS=$(count_violations 'bg-gray-[0-9]' "Gray background")
TEXT_BLUES=$(count_violations 'text-blue-[0-9]' "Blue text")
TEXT_REDS=$(count_violations 'text-red-[0-9]' "Red text")
TEXT_GRAYS=$(count_violations 'text-gray-[0-9]' "Gray text")

TOTAL_COLORS=$((BLUES + REDS + GRAYS + TEXT_BLUES + TEXT_REDS + TEXT_GRAYS))

echo "  bg-blue-*:     $BLUES violations"
echo "  bg-red-*:      $REDS violations"
echo "  bg-gray-*:     $GRAYS violations"
echo "  text-blue-*:   $TEXT_BLUES violations"
echo "  text-red-*:    $TEXT_REDS violations"
echo "  text-gray-*:   $TEXT_GRAYS violations"
echo "  ───────────────────────────────"
echo "  Total colors:  $TOTAL_COLORS violations"

# 2. Count component violations
echo ""
echo "🔧 Component Violations:"
echo "───────────────────────"

BUTTONS=$(grep -rn '<button[[:space:>]' resources/js/Pages/ --include="*.vue" 2>/dev/null | \
  grep -v "<!--" | \
  wc -l)

echo "  Raw <button>:  $BUTTONS violations"

# 3. Check exceptions
echo ""
echo "📋 Approved Exceptions:"
echo "──────────────────────"

if [ -f "design-system.exceptions.json" ]; then
  EXCEPTION_COUNT=$(jq '.tracking.total_exceptions // 0' design-system.exceptions.json 2>/dev/null || echo "0")
  ACTIVE=$(jq '.tracking.active_exceptions // 0' design-system.exceptions.json 2>/dev/null || echo "0")
  PERMANENT=$(jq '.tracking.permanent_exceptions // 0' design-system.exceptions.json 2>/dev/null || echo "0")

  echo "  Total:         $EXCEPTION_COUNT"
  echo "  Active:        $ACTIVE"
  echo "  Permanent:     $PERMANENT"
else
  echo "  ⚠️  design-system.exceptions.json not found"
fi

# 4. Calculate progress
echo ""
echo "📈 Migration Progress:"
echo "────────────────────"

CURRENT_TOTAL=$((TOTAL_COLORS + BUTTONS))
COMPLETED=$((BASELINE - CURRENT_TOTAL))
PERCENT=$(( (COMPLETED * 100) / BASELINE ))

echo "  Baseline:      $BASELINE violations"
echo "  Current:       $CURRENT_TOTAL violations"
echo "  Completed:     $COMPLETED / $BASELINE ($PERCENT%)"
echo ""

# 5. Phase targets
echo "🎯 Phase Targets:"
echo "───────────────"

declare -A PHASES=(
  ["Phase 4 Complete"]="100"
  ["Phase 5 Complete"]="50"
  ["Final Target"]="0"
)

for phase in "Phase 4 Complete" "Phase 5 Complete" "Final Target"; do
  target=${PHASES[$phase]}
  if [ "$CURRENT_TOTAL" -le "$target" ]; then
    echo "  ✅ $phase: ON TRACK ($CURRENT_TOTAL ≤ $target)"
  else
    remaining=$((CURRENT_TOTAL - target))
    echo "  ⏳ $phase: Need $remaining fewer violations"
  fi
done

# 6. Enforcement status
echo ""
echo "🔐 Enforcement Status:"
echo "────────────────────"

if [ "$CURRENT_TOTAL" -gt 150 ]; then
  echo "  Status:        ⚠️  MIGRATION IN PROGRESS"
  echo "  Blocking:      No (warnings only)"
  echo "  Next step:     Continue Phase 4 iterations"
elif [ "$CURRENT_TOTAL" -gt 50 ]; then
  echo "  Status:        🚀 POST-MIGRATION"
  echo "  Blocking:      Soft (pre-commit warnings)"
  echo "  Next step:     Activate pre-commit hook"
else
  echo "  Status:        ✅ STRICT ENFORCEMENT READY"
  echo "  Blocking:      Yes (all violations fail)"
  echo "  Next step:     Production-ready"
fi

echo ""
echo "ℹ️  Run 'npm run design:fix' to auto-fix linting issues"
echo "ℹ️  See design-system.exceptions.json for approved deviations"
echo "✅ Metrics collected successfully"
