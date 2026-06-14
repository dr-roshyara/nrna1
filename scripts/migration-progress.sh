#!/bin/bash

# Design System Migration Progress Tracker v2
# Reads configuration from design-rules.json — single source of truth.
# Shows detailed progress toward design system enforcement goals.

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CONFIG_FILE="$SCRIPT_DIR/design-rules.json"
SCAN_DIR="$(cd "$SCRIPT_DIR/.." && pwd)/resources/js/Pages"

# Require jq
if ! command -v jq &>/dev/null; then
  echo "❌ jq is required but not installed."
  echo "   Install: sudo apt install jq -y  (Linux)  or  brew install jq  (macOS)"
  exit 1
fi

if [ ! -f "$CONFIG_FILE" ]; then
  echo "❌ design-rules.json not found at $CONFIG_FILE"
  exit 1
fi

echo "📈 Design System Migration Progress"
echo "==================================="
echo ""

# ──────────────────────────────────────────────
# Read configuration from design-rules.json
# ──────────────────────────────────────────────
BASELINE=$(jq -r '.baseline // 613' "$CONFIG_FILE")
THRESHOLD=$(jq -r '.enforcement.current_threshold // 150' "$CONFIG_FILE")
ACTIVE_PHASE=$(jq -r '.active_phase // "unknown"' "$CONFIG_FILE")

# Build grep exclusion args
EXCLUDED_DIRS=$(jq -r '.excluded_dirs[] // empty' "$CONFIG_FILE" | tr -d '\r')
EXCLUDED_FILES=$(jq -r '.excluded_files[] // empty' "$CONFIG_FILE" | tr -d '\r')
GREP_EXCLUDE=""
for d in $EXCLUDED_DIRS; do
  GREP_EXCLUDE="$GREP_EXCLUDE --exclude-dir=$d"
done
for f in $EXCLUDED_FILES; do
  GREP_EXCLUDE="$GREP_EXCLUDE --exclude=$f"
done

# ──────────────────────────────────────────────
# Count current violations using ALL rules from config
# ──────────────────────────────────────────────
RULE_COUNT=$(jq '.rules | length' "$CONFIG_FILE")
CURRENT=0

for ((i = 0; i < RULE_COUNT; i++)); do
  PATTERN=$(jq -r ".rules[$i].pattern // \"\"" "$CONFIG_FILE")
  TARGET=$(jq -r ".rules[$i].target // 0" "$CONFIG_FILE")

  [ -z "$PATTERN" ] && continue
  # Skip informational-only rules (target = -1)
  [ "$TARGET" -lt 0 ] && continue

  count=$(grep -rn "$PATTERN" "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | wc -l || true)
  CURRENT=$((CURRENT + count))
done

# Also count raw <button> tags (component bypass)
RAW_BUTTONS=$(grep -rn '<button[[:space:]>]' "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | wc -l || true)
CURRENT=$((CURRENT + RAW_BUTTONS))

# ──────────────────────────────────────────────
# Read phase targets from JSON
# ──────────────────────────────────────────────
# Extract phase_targets as an ordered list of key=value pairs
PHASE_KEYS=()
PHASE_TARGETS=()
while IFS='=' read -r key value; do
  [ -z "$key" ] && continue
  PHASE_KEYS+=("$key")
  PHASE_TARGETS["$key"]=$value
done < <(jq -r '.phase_targets | to_entries[] | "\(.key)=\(.value)"' "$CONFIG_FILE")

# Calculate metrics
COMPLETED=$((BASELINE - CURRENT))
PERCENT=0
if [ "$BASELINE" -gt 0 ]; then
  PERCENT=$((COMPLETED * 100 / BASELINE))
fi

# ──────────────────────────────────────────────
# Progress bar
# ──────────────────────────────────────────────
progress_bar() {
  local current=$1
  local total=$2
  local percent=0
  [ "$total" -gt 0 ] && percent=$((current * 50 / total))

  printf "["
  for ((i = 0; i < 50; i++)); do
    [ "$i" -lt "$percent" ] && printf "#" || printf "."
  done
  printf "] %d%%\n" $((percent * 2))
}

# Display current state
echo "Current State:"
echo "─────────────"
echo "Violations:    $CURRENT / $BASELINE"
echo "Completed:     $COMPLETED"
echo "Threshold:     $THRESHOLD"
echo "Active Phase:  $ACTIVE_PHASE"
progress_bar $COMPLETED $BASELINE
echo ""

# Display phase progression
echo "Phase Progression:"
echo "─────────────────"
echo ""

for phase_key in "${PHASE_KEYS[@]}"; do
  target="${PHASE_TARGETS[$phase_key]}"
  phase_label=$(echo "$phase_key" | tr '_' ' ' | sed 's/[a-z]/\u&/g')

  if [ "$CURRENT" -le "$target" ]; then
    status="✅ DONE"
    symbol="✓"
  else
    remaining=$((CURRENT - target))
    status="⏳ PENDING ($remaining left)"
    symbol="→"
  fi

  printf "  %s %-30s %3d violations  %s\n" "$symbol" "$phase_label:" "$target" "$status"
done

echo ""
echo "Summary:"
echo "────────"

# Determine current phase based on violations
if [ "$CURRENT" -gt 500 ]; then
  CURRENT_PHASE_LABEL="4.1-4.3"
elif [ "$CURRENT" -gt 450 ]; then
  CURRENT_PHASE_LABEL="4.4-4.5"
elif [ "$CURRENT" -gt 100 ]; then
  CURRENT_PHASE_LABEL="4.6"
elif [ "$CURRENT" -gt 50 ]; then
  CURRENT_PHASE_LABEL="5"
else
  CURRENT_PHASE_LABEL="COMPLETE"
fi

echo "Configuration source: $CONFIG_FILE"
echo "Current Phase:        $CURRENT_PHASE_LABEL"
if [ "$BASELINE" -gt 50 ]; then
  PHASE5_PROGRESS=$((100 * (BASELINE - CURRENT) / (BASELINE - 50)))
  echo "Progress to Phase 5:  $PHASE5_PROGRESS%"
fi

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
