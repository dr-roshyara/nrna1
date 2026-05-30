#!/bin/bash

# Design System Compliance Check v2
# Consumes scripts/design-rules.json for dynamic rule enforcement.
# Non-blocking during migration phase; becomes hard-blocking when violations drop below threshold.
#
# Usage:
#   ./scripts/design-check.sh              # standard check
#   ./scripts/design-check.sh --strict     # exit 1 on any violation

set -euo pipefail

# Require jq
if ! command -v jq &>/dev/null; then
  echo "❌ jq is required but not installed."
  echo "   Install: sudo apt install jq -y  (Linux)  or  brew install jq  (macOS)"
  exit 1
fi

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
CONFIG_FILE="$SCRIPT_DIR/design-rules.json"
SCAN_DIR="$PROJECT_DIR/resources/js/Pages"
STRICT_MODE="${1:-normal}"

if [ ! -f "$CONFIG_FILE" ]; then
  echo "❌ design-rules.json not found at $CONFIG_FILE"
  exit 1
fi

if [ ! -d "$SCAN_DIR" ]; then
  echo "⚠️  Scan directory $SCAN_DIR does not exist — nothing to check."
  exit 0
fi

# ──────────────────────────────────────────────
# Parse configuration from JSON
# ──────────────────────────────────────────────
BASELINE=$(jq -r '.baseline // 613' "$CONFIG_FILE")
THRESHOLD=$(jq -r '.enforcement.current_threshold // 150' "$CONFIG_FILE")
EXIT_ON_FAIL=$(jq -r '.enforcement.exit_on_fail // false' "$CONFIG_FILE")
ACTIVE_PHASE=$(jq -r '.active_phase // "unknown"' "$CONFIG_FILE")

if [ "$STRICT_MODE" = "--strict" ]; then
  EXIT_ON_FAIL="true"
fi

echo "🎨 Design System Compliance Check v2"
echo "======================================"
echo "  Config:       $CONFIG_FILE"
echo "  Baseline:     $BASELINE violations"
echo "  Threshold:    $THRESHOLD violations"
echo "  Phase:        $ACTIVE_PHASE"
echo "  Strict:       $STRICT_MODE"
echo "  Scan dir:     $SCAN_DIR"
echo ""

# ──────────────────────────────────────────────
# Build exclusion arguments for grep
# ──────────────────────────────────────────────
EXCLUDED_DIRS=$(jq -r '.excluded_dirs[] // empty' "$CONFIG_FILE")
GREP_EXCLUDE=""
for d in $EXCLUDED_DIRS; do
  GREP_EXCLUDE="$GREP_EXCLUDE --exclude-dir=$d"
done

# ──────────────────────────────────────────────
# Iterate through each rule
# ──────────────────────────────────────────────
RULE_COUNT=$(jq '.rules | length' "$CONFIG_FILE")
TOTAL_VIOLATIONS=0
declare -a FAILED_RULES=()
declare -A RULE_VIOLATIONS

echo "📊 Rule Violations:"
echo "────────────────────────"

for ((i = 0; i < RULE_COUNT; i++)); do
  ID=$(jq -r ".rules[$i].id // \"rule_$i\"" "$CONFIG_FILE")
  LABEL=$(jq -r ".rules[$i].label // \"Rule $i\"" "$CONFIG_FILE")
  PATTERN=$(jq -r ".rules[$i].pattern // \"\"" "$CONFIG_FILE")
  SEVERITY=$(jq -r ".rules[$i].severity // \"info\"" "$CONFIG_FILE")
  TARGET=$(jq -r ".rules[$i].target // 0" "$CONFIG_FILE")

  # Skip rules with empty patterns
  if [ -z "$PATTERN" ]; then
    continue
  fi

  # Count violations
  COUNT=0
  if [ "$TARGET" -ge 0 ]; then
    # Target > 0 means we're counting down (phase tracking)
    # Target = 0 means zero tolerance
    COUNT=$(grep -rn "$PATTERN" "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | wc -l)
  fi

  RULE_VIOLATIONS["$ID"]=$COUNT
  TOTAL_VIOLATIONS=$((TOTAL_VIOLATIONS + COUNT))

  # Color-code by severity and count
  if [ "$COUNT" -eq 0 ]; then
    printf "  ✅ %-30s %4d violations  (target: %d)\n" "$LABEL:" "$COUNT" "$TARGET"
  elif [ "$SEVERITY" = "error" ]; then
    printf "  ❌ %-30s %4d violations  (target: %d)\n" "$LABEL:" "$COUNT" "$TARGET"
    FAILED_RULES+=("$ID ($COUNT violations)")
  elif [ "$SEVERITY" = "warning" ]; then
    printf "  ⚠️  %-30s %4d violations  (target: %d)\n" "$LABEL:" "$COUNT" "$TARGET"
  else
    printf "  ℹ️  %-30s %4d occurrences  (informational)\n" "$LABEL:" "$COUNT"
  fi
done

# ──────────────────────────────────────────────
# Count raw <button> tags (legacy component bypass)
# ──────────────────────────────────────────────
echo ""
echo "🔧 Component Violations:"
echo "───────────────────────"
RAW_BUTTONS=$(grep -rn '<button[[:space:]>]' "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | wc -l)
echo "  Raw <button>: $RAW_BUTTONS (target: 0)"
TOTAL_VIOLATIONS=$((TOTAL_VIOLATIONS + RAW_BUTTONS))

# ──────────────────────────────────────────────
# Check approved exceptions
# ──────────────────────────────────────────────
echo ""
echo "📋 Approved Exceptions:"
echo "──────────────────────"
if [ -f "$PROJECT_DIR/design-system.exceptions.json" ]; then
  EXCEPTION_COUNT=$(jq '.tracking.total_exceptions // 0' "$PROJECT_DIR/design-system.exceptions.json" 2>/dev/null || echo "0")
  ACTIVE=$(jq '.tracking.active_exceptions // 0' "$PROJECT_DIR/design-system.exceptions.json" 2>/dev/null || echo "0")
  PERMANENT=$(jq '.tracking.permanent_exceptions // 0' "$PROJECT_DIR/design-system.exceptions.json" 2>/dev/null || echo "0")
  echo "  Total:     $EXCEPTION_COUNT"
  echo "  Active:    $ACTIVE"
  echo "  Permanent: $PERMANENT"
else
  echo "  ⚠️  design-system.exceptions.json not found"
fi

# ──────────────────────────────────────────────
# Progress calculation
# ──────────────────────────────────────────────
echo ""
echo "📈 Migration Progress:"
echo "────────────────────"
COMPLETED=$((BASELINE - TOTAL_VIOLATIONS))
[ "$BASELINE" -gt 0 ] && PERCENT=$((COMPLETED * 100 / BASELINE)) || PERCENT=0

echo "  Baseline:     $BASELINE violations"
echo "  Current:      $TOTAL_VIOLATIONS violations"
echo "  Completed:    $COMPLETED / $BASELINE ($PERCENT%)"
echo ""

# Progress bar
bar_width=50
filled=$((PERCENT * bar_width / 100))
printf "  "
for ((j = 0; j < bar_width; j++)); do
  [ "$j" -lt "$filled" ] && printf "█" || printf "░"
done
printf " %d%%\n" "$PERCENT"

# ──────────────────────────────────────────────
# Phase target check
# ──────────────────────────────────────────────
echo ""
echo "🎯 Phase Targets:"
echo "───────────────"
jq -r '.phase_targets | to_entries[] | "\(.key)=\(.value)"' "$CONFIG_FILE" 2>/dev/null | while IFS='=' read -r phase target; do
  [ -z "$target" ] && continue
  phase_label=$(echo "$phase" | tr '_' ' ' | sed 's/[a-z]/\u&/g')

  if [ "$TOTAL_VIOLATIONS" -le "$target" ]; then
    echo "  ✅ $phase_label: ON TRACK ($TOTAL_VIOLATIONS ≤ $target)"
  else
    remaining=$((TOTAL_VIOLATIONS - target))
    echo "  ⏳ $phase_label: Need $remaining fewer violations"
  fi
done

# ──────────────────────────────────────────────
# Enforcement decision
# ──────────────────────────────────────────────
echo ""
echo "🔐 Enforcement Status:"
echo "────────────────────"

if [ "$TOTAL_VIOLATIONS" -gt "$THRESHOLD" ]; then
  echo "  Status:    ⚠️  MIGRATION IN PROGRESS ($TOTAL_VIOLATIONS > $THRESHOLD)"
  echo "  Blocking:  No (warnings only, violations below threshold)"
elif [ "$TOTAL_VIOLATIONS" -gt 50 ]; then
  echo "  Status:    🚀 POST-MIGRATION"
  echo "  Blocking:  Soft (pre-commit warnings)"
else
  echo "  Status:    ✅ STRICT ENFORCEMENT READY"
  echo "  Blocking:  Yes (all violations fail)"
fi

echo ""

# ──────────────────────────────────────────────
# Final verdict
# ──────────────────────────────────────────────
if [ "$EXIT_ON_FAIL" = "true" ] && [ "$TOTAL_VIOLATIONS" -gt "$THRESHOLD" ]; then
  echo "❌ FAIL: $TOTAL_VIOLATIONS violations exceed threshold of $THRESHOLD."
  echo "   Use design tokens: <Button variant=\"primary|danger|...\">, <Card>, or CSS variables."
  for rule in "${FAILED_RULES[@]}"; do
    echo "   - Failed rule: $rule"
  done
  exit 1
fi

if [ "$TOTAL_VIOLATIONS" -gt "$THRESHOLD" ]; then
  echo "⚠️  WARNING: $TOTAL_VIOLATIONS violations. Threshold: $THRESHOLD."
  echo "   Not blocked (migration phase). Run 'npm run design:fix' for auto-fixes."
else
  echo "✅ All checks passed! ($TOTAL_VIOLATIONS violations, threshold: $THRESHOLD)"
fi

exit 0
