#!/bin/bash

# Design System Migration Progress Tracker
# Shows detailed progress toward design system enforcement goals

echo "📈 Design System Migration Progress"
echo "==================================="
echo ""

# Configuration
BASELINE=613

# Get current violation count (same as design-check.sh)
BLUES=$(grep -rn 'bg-blue-[0-9]' resources/js/Pages/ --include="*.vue" 2>/dev/null | grep -v "<!--" | wc -l)
REDS=$(grep -rn 'bg-red-[0-9]' resources/js/Pages/ --include="*.vue" 2>/dev/null | grep -v "<!--" | wc -l)
GRAYS=$(grep -rn 'bg-gray-[0-9]' resources/js/Pages/ --include="*.vue" 2>/dev/null | grep -v "<!--" | wc -l)
TEXT_BLUES=$(grep -rn 'text-blue-[0-9]' resources/js/Pages/ --include="*.vue" 2>/dev/null | grep -v "<!--" | wc -l)
TEXT_REDS=$(grep -rn 'text-red-[0-9]' resources/js/Pages/ --include="*.vue" 2>/dev/null | grep -v "<!--" | wc -l)
TEXT_GRAYS=$(grep -rn 'text-gray-[0-9]' resources/js/Pages/ --include="*.vue" 2>/dev/null | grep -v "<!--" | wc -l)

CURRENT=$((BLUES + REDS + GRAYS + TEXT_BLUES + TEXT_REDS + TEXT_GRAYS))

# Calculate metrics
COMPLETED=$((BASELINE - CURRENT))
PERCENT=$(( (COMPLETED * 100) / BASELINE ))

# Phase targets
declare -A PHASE_TARGETS=(
  ["Phase 4 Iteration 1"]="592"
  ["Phase 4 Iteration 2"]="580"
  ["Phase 4 Iteration 3"]="545"
  ["Phase 4 Iteration 4"]="529"
  ["Phase 4 Iteration 5"]="511"
  ["Phase 4 Iteration 6"]="485"
  ["Phase 5 Complete"]="50"
  ["Final Target"]="0"
)

# Progress bar
progress_bar() {
  local current=$1
  local total=$2
  local percent=$((current * 50 / total))

  printf "["
  for ((i = 0; i < 50; i++)); do
    if [ $i -lt $percent ]; then
      printf "█"
    else
      printf "░"
    fi
  done
  printf "] %d%%\n" $((percent * 2))
}

# Display current state
echo "Current State:"
echo "─────────────"
echo "Violations:    $CURRENT / $BASELINE"
echo "Completed:     $COMPLETED"
progress_bar $COMPLETED $BASELINE
echo ""

# Display phase progression
echo "Phase Progression:"
echo "─────────────────"
echo ""

PHASES=(
  "Phase 4 Iteration 1"
  "Phase 4 Iteration 2"
  "Phase 4 Iteration 3"
  "Phase 4 Iteration 4"
  "Phase 4 Iteration 5"
  "Phase 4 Iteration 6"
  "Phase 5 Complete"
  "Final Target"
)

for phase in "${PHASES[@]}"; do
  target=${PHASE_TARGETS[$phase]}

  if [ "$CURRENT" -le "$target" ]; then
    status="✅ DONE"
    symbol="✓"
  else
    remaining=$((CURRENT - target))
    status="⏳ PENDING ($remaining left)"
    symbol="→"
  fi

  printf "  %s %-30s %3d violations  %s\n" "$symbol" "$phase:" "$target" "$status"
done

echo ""
echo "Summary:"
echo "────────"

# Determine current phase
if [ "$CURRENT" -gt 500 ]; then
  CURRENT_PHASE="4.1-4.3"
elif [ "$CURRENT" -gt 450 ]; then
  CURRENT_PHASE="4.4-4.5"
elif [ "$CURRENT" -gt 100 ]; then
  CURRENT_PHASE="4.6"
elif [ "$CURRENT" -gt 50 ]; then
  CURRENT_PHASE="5"
else
  CURRENT_PHASE="COMPLETE"
fi

echo "Current Phase:      $CURRENT_PHASE"
echo "Progress to Phase 5: $((100 * (592 - CURRENT) / (592 - 50)))%"
echo "Time estimate:      ~8-10 hours (from start)"
echo ""

# Recommendations
echo "Next Steps:"
echo "──────────"

if [ "$CURRENT" -gt 500 ]; then
  echo "• Continue Phase 4 iterations"
  echo "• Target: Get violations below 100"
  echo "• Run: npm run design-check"
elif [ "$CURRENT" -gt 50 ]; then
  echo "• Activate pre-commit hook (will warn only)"
  echo "• Prepare for Phase 5 layout consolidation"
  echo "• Enable GitHub Actions for metrics"
else
  echo "• All phases complete! 🎉"
  echo "• Enable strict enforcement mode"
  echo "• Schedule design system review"
fi

echo ""
echo "📊 Last updated: $(date)"
echo "ℹ️  Run 'npm run design-check' for detailed metrics"
