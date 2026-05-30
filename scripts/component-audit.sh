#!/bin/bash

# Component Audit Script v1
# Scans .vue files for raw HTML tags that should use design system components.
# Reads component definitions from scripts/ui-components.json.
# Tracks baseline counts — regressions must never increase raw tag counts.
#
# Usage:
#   ./scripts/component-audit.sh              # standard check
#   ./scripts/component-audit.sh --strict     # exit 1 on any regression

set -euo pipefail

# Require jq
if ! command -v jq &>/dev/null; then
  echo "❌ jq is required but not installed."
  echo "   Install: sudo apt install jq -y  (Linux)  or  brew install jq  (macOS)"
  exit 1
fi

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
CONFIG_FILE="$SCRIPT_DIR/ui-components.json"
SCAN_DIR="$PROJECT_DIR/resources/js/Pages"
STRICT_MODE="${1:-normal}"

if [ ! -f "$CONFIG_FILE" ]; then
  echo "❌ ui-components.json not found at $CONFIG_FILE"
  exit 1
fi

if [ ! -d "$SCAN_DIR" ]; then
  echo "⚠️  Scan directory $SCAN_DIR does not exist."
  exit 0
fi

echo "🔧 Design System Component Audit"
echo "================================"
echo "  Config:  $CONFIG_FILE"
echo "  Scan:    $SCAN_DIR"
echo "  Strict:  $STRICT_MODE"
echo ""

# ──────────────────────────────────────────────
# Read baseline
# ──────────────────────────────────────────────
BASELINE_BUTTONS=$(jq -r '.baseline.raw_button_tags // 0' "$CONFIG_FILE")
BASELINE_INPUTS=$(jq -r '.baseline.raw_input_tags // 0' "$CONFIG_FILE")
BASELINE_SELECTS=$(jq -r '.baseline.raw_select_tags // 0' "$CONFIG_FILE")
BASELINE_TEXTAREAS=$(jq -r '.baseline.raw_textarea_tags // 0' "$CONFIG_FILE")

EXCLUDED_DIRS=$(jq -r '.excluded_dirs[] // empty' "$CONFIG_FILE")
EXCLUDED_FILES=$(jq -r '.excluded_files[] // empty' "$CONFIG_FILE")

# Build grep exclusion args
GREP_EXCLUDE=""
for d in $EXCLUDED_DIRS; do
  GREP_EXCLUDE="$GREP_EXCLUDE --exclude-dir=$d"
done
for f in $EXCLUDED_FILES; do
  GREP_EXCLUDE="$GREP_EXCLUDE --exclude=$f"
done

# ──────────────────────────────────────────────
# Count raw HTML tags
# ──────────────────────────────────────────────
echo "📊 Raw HTML Tag Counts (target: 0, must not exceed baseline):"
echo "────────────────────────────────────────────────────────────"

RAW_BUTTONS=$(grep -rn '<button[[:space:]>]' "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | wc -l)
RAW_INPUTS=$(grep -rn '<input[[:space:]>]' "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | wc -l)
RAW_SELECTS=$(grep -rn '<select[[:space:]>]' "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | wc -l)
RAW_TEXTAREAS=$(grep -rn '<textarea' "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | wc -l)

# Tag counts without baseline enforcement (informational for <Card>, <Modal> etc pattern matching)
RAW_FIXED_DIVS=$(grep -rn 'class="[^"]*\(fixed\|inset-0\)' "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | wc -l)

print_tag_count() {
  local label="$1"
  local count="$2"
  local baseline="$3"
  local symbol=""

  if [ "$count" -le "$baseline" ]; then
    symbol="✅"
  else
    symbol="❌ REGRESSION"
  fi

  printf "  %-30s %4d  (baseline: %4d)  %s\n" "$label:" "$count" "$baseline" "$symbol"
}

print_tag_count "Raw <button>" "$RAW_BUTTONS" "$BASELINE_BUTTONS"
print_tag_count "Raw <input>" "$RAW_INPUTS" "$BASELINE_INPUTS"
print_tag_count "Raw <select>" "$RAW_SELECTS" "$BASELINE_SELECTS"
print_tag_count "Raw <textarea>" "$RAW_TEXTAREAS" "$BASELINE_TEXTAREAS"

echo ""
echo "  ℹ️  Inline modal divs (fixed/inset-0): $RAW_FIXED_DIVS (no baseline)"

# ──────────────────────────────────────────────
# Count design system component usage
# ──────────────────────────────────────────────
echo ""
echo "✅ Design System Component Usage:"
echo "────────────────────────────────"

COMPONENT_COUNT=$(jq '.components | length' "$CONFIG_FILE")
TOTAL_COMPONENT_USAGE=0

for ((i = 0; i < COMPONENT_COUNT; i++)); do
  NAME=$(jq -r ".components[$i].name // \"Component_$i\"" "$CONFIG_FILE")
  PATH_REL=$(jq -r ".components[$i].path // \"\"" "$CONFIG_FILE")
  TARGET=$(jq -r ".components[$i].target_usage // 0" "$CONFIG_FILE")
  NOTES=$(jq -r ".components[$i].notes // \"\"" "$CONFIG_FILE")

  # Count <ComponentName> usage across all Pages
  COUNT=$(grep -rn "<$NAME[[:space:]>]" "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | wc -l)
  TOTAL_COMPONENT_USAGE=$((TOTAL_COMPONENT_USAGE + COUNT))

  printf "  %-20s %4d usages  (target: %4d)  %s\n" "<$NAME>:" "$COUNT" "$TARGET" "$NOTES"
done

# ──────────────────────────────────────────────
# Summary
# ──────────────────────────────────────────────
echo ""
echo "📈 Summary:"
echo "──────────"
echo "  Total raw tag violations:  $((RAW_BUTTONS + RAW_INPUTS + RAW_SELECTS + RAW_TEXTAREAS))"
echo "  Total component usages:    $TOTAL_COMPONENT_USAGE"
echo "  Component adoption ratio:  $((RAW_BUTTONS > 0 ? TOTAL_COMPONENT_USAGE * 100 / (TOTAL_COMPONENT_USAGE + RAW_BUTTONS) : 0))%"
echo ""

# ──────────────────────────────────────────────
# Top offenders
# ──────────────────────────────────────────────
echo "📋 Top Raw <button> Offenders:"
echo "────────────────────────────"
grep -rn '<button[[:space:]>]' "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | grep -v "<!--" | sed 's/:[^:]*$//' | sort | uniq -c | sort -rn | head -10 | while read count file; do
  printf "  %4d  %s\n" "$count" "$(echo "$file" | sed "s|$PROJECT_DIR/||")"
done

# ──────────────────────────────────────────────
# New file audit — new .vue files MUST use design system components
# ──────────────────────────────────────────────
NEW_FILE_AUDIT=$(jq -r '.new_file_audit.enabled // false' "$CONFIG_FILE")

if [ "$NEW_FILE_AUDIT" = "true" ]; then
  echo ""
  echo "🆕 New File Audit (git-tracked but never-before-scanned .vue files):"
  echo "───────────────────────────────────────────────────────────────"

  # Find new .vue files in this branch (not on main)
  NEW_VUE_FILES=$(git diff --name-only HEAD --diff-filter=A 2>/dev/null | grep '\.vue$' || true)

  if [ -z "$NEW_VUE_FILES" ]; then
    echo "  No new .vue files detected."
  else
    NEW_FILE_VIOLATIONS=0
    RULE_COUNT=$(jq '.new_file_audit.rules | length' "$CONFIG_FILE")

    for ((r = 0; r < RULE_COUNT; r++)); do
      PATTERN=$(jq -r ".new_file_audit.rules[$r].pattern // \"\"" "$CONFIG_FILE")
      MESSAGE=$(jq -r ".new_file_audit.rules[$r].message // \"\"" "$CONFIG_FILE")
      SEVERITY=$(jq -r ".new_file_audit.rules[$r].severity // \"warning\"" "$CONFIG_FILE")

      [ -z "$PATTERN" ] && continue

      for F in $NEW_VUE_FILES; do
        if grep -qE "$PATTERN" "$PROJECT_DIR/$F" 2>/dev/null; then
          NEW_FILE_VIOLATIONS=$((NEW_FILE_VIOLATIONS + 1))
          echo "  ❌ [$SEVERITY] $(basename "$F") — $MESSAGE"
        fi
      done
    done

    if [ "$NEW_FILE_VIOLATIONS" -gt 0 ]; then
      ALLOW_REGRESSION=true
    fi
  fi
fi

# ──────────────────────────────────────────────
# Adoption enforcement — component usage must not regress
# ──────────────────────────────────────────────
ADOPTION_REQUIRED=$(jq -r '.enforcement.adoption_required // false' "$CONFIG_FILE")

if [ "$ADOPTION_REQUIRED" = "true" ]; then
  echo ""
  echo "📊 Adoption Enforcement (targets from ui-components.json):"
  echo "───────────────────────────────────────────────────────"
  ADOPTION_FAILURES=0

  for ((i = 0; i < COMPONENT_COUNT; i++)); do
    NAME=$(jq -r ".components[$i].name // \"Component_$i\"" "$CONFIG_FILE")
    PRIORITY=$(jq -r ".components[$i].adoption_priority // \"low\"" "$CONFIG_FILE")
    TARGET=$(jq -r ".components[$i].target_usage // 0" "$CONFIG_FILE")

    # Re-count to get current actual usage
    CURRENT=$(grep -rn "<$NAME[[:space:]>]" "$SCAN_DIR" --include="*.vue" $GREP_EXCLUDE 2>/dev/null | wc -l)

    if [ "$PRIORITY" = "critical" ] && [ "$CURRENT" -lt "$TARGET" ]; then
      shortfall=$((TARGET - CURRENT))
      echo "  ⏳ <$NAME>: $CURRENT/$TARGET usages (${shortfall} short — adoption_priority: critical)"
      ADOPTION_FAILURES=$((ADOPTION_FAILURES + 1))
    fi
  done

  if [ "$ADOPTION_FAILURES" -gt 0 ] && [ "$STRICT_MODE" = "--strict" ]; then
    echo ""
    echo "  ℹ️  Note: Adoption gaps are tracked but not blocking in strict mode."
    echo "  ℹ️  Only REGRESSIONS (raw tag counts exceeding baseline) block the gate."
  fi
fi

# ──────────────────────────────────────────────
# Enforcement
# ──────────────────────────────────────────────
ALLOW_REGRESSION=false

if [ "$RAW_BUTTONS" -gt "$BASELINE_BUTTONS" ]; then
  echo ""
  echo "❌ REGRESSION: Raw <button> count ($RAW_BUTTONS) exceeds baseline ($BASELINE_BUTTONS)."
  echo "   Use <Button variant=\"...\"> instead of <button>."
  ALLOW_REGRESSION=true
fi

if [ "$RAW_INPUTS" -gt "$BASELINE_INPUTS" ]; then
  echo ""
  echo "❌ REGRESSION: Raw <input> count ($RAW_INPUTS) exceeds baseline ($BASELINE_INPUTS)."
  echo "   Use <Input /> component instead."
  ALLOW_REGRESSION=true
fi

if [ "$STRICT_MODE" = "--strict" ] && [ "$ALLOW_REGRESSION" = "true" ]; then
  echo ""
  echo "❌ Component audit FAILED — regressions detected."
  exit 1
fi

if [ "$ALLOW_REGRESSION" = "true" ]; then
  echo ""
  echo "⚠️  Regressions detected but not blocked (non-strict mode)."
  echo "   Re-run with --strict to enforce."
  exit 0
fi

echo "✅ Component audit passed — no regressions."
exit 0
