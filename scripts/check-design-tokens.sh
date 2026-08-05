#!/usr/bin/env bash
# Design System Token Enforcement Script v3
# Reads all token rules from scripts/design-rules.json.
# Fails if total violations exceed threshold.
# Quick summary version of design-check.sh — counts across all rule patterns.
# Components should use <Button variant="..."> or token-based classes instead.

set -euo pipefail

# Require jq
if ! command -v jq &>/dev/null; then
  echo "❌ jq is required but not installed."
  echo "   Install: sudo apt install jq -y  (Linux)  or  brew install jq  (macOS)"
  exit 1
fi

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CONFIG_FILE="$SCRIPT_DIR/design-rules.json"
SCAN_DIR="$(cd "$SCRIPT_DIR/.." && pwd)/resources/js/Pages"

if [ ! -f "$CONFIG_FILE" ]; then
  echo "❌ design-rules.json not found at $CONFIG_FILE"
  exit 1
fi

# Fail-closed: refuse to run on unreadable policy (EG-002a)
source "$SCRIPT_DIR/lib/config-guard.sh"

# Read config
THRESHOLD=$(jq -r '.enforcement.current_threshold // 150' "$CONFIG_FILE")
EXIT_ON_FAIL=$(jq -r '.enforcement.exit_on_fail // false' "$CONFIG_FILE")
BASELINE=$(jq -r '.baseline // 613' "$CONFIG_FILE")

require_int ".enforcement.current_threshold" "$THRESHOLD"
require_int ".baseline" "$BASELINE"

# Build grep exclusion args from config
EXCLUDED_DIRS=$(jq -r '.excluded_dirs[] // empty' "$CONFIG_FILE" | tr -d '\r')
EXCLUDED_FILES=$(jq -r '.excluded_files[] // empty' "$CONFIG_FILE" | tr -d '\r')
GREP_EXCLUDE=""
for d in $EXCLUDED_DIRS; do
  GREP_EXCLUDE="$GREP_EXCLUDE --exclude-dir=$d"
done
for f in $EXCLUDED_FILES; do
  GREP_EXCLUDE="$GREP_EXCLUDE --exclude=$f"
done

# Count violations across ALL rule patterns from design-rules.json (skip -1 informational)
total_violations=0
RULE_COUNT=$(jq '.rules | length' "$CONFIG_FILE")
require_int ".rules|length" "$RULE_COUNT"

for ((i = 0; i < RULE_COUNT; i++)); do
  PATTERN=$(jq -r ".rules[$i].pattern // \"\"" "$CONFIG_FILE")
  TARGET=$(jq -r ".rules[$i].target // 0" "$CONFIG_FILE")
  LABEL=$(jq -r ".rules[$i].label // \"Rule $i\"" "$CONFIG_FILE")
  require_int ".rules[$i].target" "$TARGET"

  [ -z "$PATTERN" ] && continue
  # Skip informational-only rules (target = -1)
  [ "$TARGET" -lt 0 ] && continue

  count=$(grep -rn "$PATTERN" "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | wc -l || true)
  total_violations=$((total_violations + count))
done

# Also count raw <button> tags
RAW_BUTTONS=$(grep -rn '<button[[:space:]>]' "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | wc -l || true)
total_violations=$((total_violations + RAW_BUTTONS))

echo "Design token violations: $total_violations"
echo "Threshold: $THRESHOLD | Baseline: $BASELINE | Target: 0"

if [ "$total_violations" -gt "$THRESHOLD" ]; then
  echo "FAIL: Too many raw color violations ($total_violations > $THRESHOLD)."
  echo "Use <Button variant=\"primary|danger|...\">, <Card>, or design token classes."
  exit 1
fi

echo "PASS: Violations within acceptable range."
exit 0
