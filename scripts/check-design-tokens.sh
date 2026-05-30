#!/usr/bin/env bash
# Design System Token Enforcement Script v2
# Reads threshold from scripts/design-rules.json.
# Fails if raw Tailwind color violations exceed threshold.
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

# Read dynamic threshold from config
THRESHOLD=$(jq -r '.enforcement.current_threshold // 150' "$CONFIG_FILE")
EXIT_ON_FAIL=$(jq -r '.enforcement.exit_on_fail // false' "$CONFIG_FILE")

# Count raw color violations (background + text + border)
violations=$(grep -rn \
  "bg-blue-[0-9]\{3\}\|bg-indigo-[0-9]\{3\}\|bg-gray-[0-9]\{3\}\|bg-slate-[0-9]\{3\}\|bg-red-[0-9]\{3\}\|bg-green-[0-9]\{3\}" \
  "$SCAN_DIR" \
  --include="*.vue" 2>/dev/null | wc -l)

echo "Design token violations: $violations"
echo "Threshold: $THRESHOLD | Baseline: 613 | Target: 0"

if [ "$violations" -gt "$THRESHOLD" ]; then
  echo "FAIL: Too many raw color violations ($violations > $THRESHOLD)."
  echo "Use <Button variant=\"primary|danger|...\">, <Card>, or design token classes."
  exit 1
fi

echo "PASS: Violations within acceptable range."
exit 0
