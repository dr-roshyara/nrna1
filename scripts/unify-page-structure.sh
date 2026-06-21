#!/usr/bin/env bash

# ═══════════════════════════════════════════════════════════════
# unify-page-structure.sh — Public Page Structure Unification
# ═══════════════════════════════════════════════════════════════
#
# Standardizes public information pages to match ElectionArchitecture.vue:
#   1. Replace raw NrnaLayout/AppLayout → PublicDigitLayout
#   2. Replace gradient hero with from-primary-200/300/100 + blur
#   3. Convert raw color tokens to semantic equivalents
#   4. Standardize section background alternation
#
# Usage:
#   ./scripts/unify-page-structure.sh <path/to/Page.vue>
#
# Example:
#   ./scripts/unify-page-structure.sh resources/js/Pages/Vote/DemoVote/Guide.vue
#
# Dry run mode:
#   DRY_RUN=1 ./scripts/unify-page-structure.sh <file>
# ═══════════════════════════════════════════════════════════════

set -euo pipefail

TARGET="$1"
DRY_RUN="${DRY_RUN:-0}"

if [ ! -f "$TARGET" ]; then
  echo "❌ File not found: $TARGET"
  echo "Usage: $0 <path/to/Page.vue>"
  exit 1
fi

echo "🔧 Unifying: $TARGET"
echo ""

# ── Step 1: Layout replacement ────────────────────────────
echo "  Step 1: Replacing layout..."
if grep -q '<nrna-layout' "$TARGET" 2>/dev/null; then
  sed -i 's/<nrna-layout>/<PublicDigitLayout>/g; s/<\/nrna-layout>/<\/PublicDigitLayout>/g; s|NrnaLayout|PublicDigitLayout|g' "$TARGET"
  echo "    ✅ Replaced NrnaLayout → PublicDigitLayout"
else
  echo "    ⏭️  No NrnaLayout found"
fi

# ── Step 2: Add PublicDigitLayout import ──────────────────
if ! grep -q "import PublicDigitLayout" "$TARGET" 2>/dev/null; then
  # Add import after script/setup opening
  if grep -q "<script setup>" "$TARGET" 2>/dev/null; then
    sed -i '/<script setup>/a import PublicDigitLayout from '"'"'@\/Layouts\/PublicDigitLayout.vue'"'"'' "$TARGET"
    echo "    ✅ Added PublicDigitLayout import"
  elif grep -q "<script>" "$TARGET" 2>/dev/null; then
    sed -i '/<script>/a import PublicDigitLayout from '"'"'@\/Layouts\/PublicDigitLayout.vue'"'"'' "$TARGET"
    echo "    ✅ Added PublicDigitLayout import"
  fi
fi

# ── Step 3: Raw color token conversion ─────────────────────
echo "  Step 2: Converting color tokens..."
sed -i \
  -e 's/bg-slate-/bg-neutral-/g' \
  -e 's/text-slate-/text-neutral-/g' \
  -e 's/border-slate-/border-neutral-/g' \
  -e 's/from-slate-/from-neutral-/g' \
  -e 's/to-slate-/to-neutral-/g' \
  -e 's/bg-gray-/bg-neutral-/g' \
  -e 's/text-gray-/text-neutral-/g' \
  -e 's/border-gray-/border-neutral-/g' \
  -e 's/bg-indigo-/bg-primary-/g' \
  -e 's/text-indigo-/text-primary-/g' \
  -e 's/border-indigo-/border-primary-/g' \
  -e 's/from-indigo/from-primary/g' \
  -e 's/to-indigo-/to-primary-/g' \
  -e 's/bg-green-/bg-success-/g' \
  -e 's/text-green-/text-success-/g' \
  -e 's/border-green-/border-success-/g' \
  -e 's/from-green-/from-success-/g' \
  -e 's/to-green-/to-success-/g' \
  -e 's/bg-emerald-/bg-success-/g' \
  -e 's/text-emerald-/text-success-/g' \
  -e 's/border-emerald-/border-success-/g' \
  -e 's/from-emerald-/from-success-/g' \
  -e 's/to-emerald-/to-success-/g' \
  -e 's/bg-purple-/bg-accent-/g' \
  -e 's/text-purple-/text-accent-/g' \
  -e 's/border-purple-/border-accent-/g' \
  -e 's/from-purple-/from-accent-/g' \
  -e 's/to-purple-/to-accent-/g' \
  -e 's/bg-orange-/bg-warning-/g' \
  -e 's/text-orange-/text-warning-/g' \
  -e 's/border-orange-/border-warning-/g' \
  -e 's/from-orange-/from-warning-/g' \
  -e 's/to-orange-/to-warning-/g' \
  -e 's/bg-amber-/bg-warning-/g' \
  -e 's/text-amber-/text-warning-/g' \
  -e 's/border-amber-/border-warning-/g' \
  "$TARGET"
echo "    ✅ Colors converted to semantic tokens"

# ── Step 4: Hero gradient standardization ──────────────────
echo "  Step 3: Standardizing hero section..."
if grep -q "bg-gradient-to-br from-slate-50\|bg-gradient-to-b from-slate-50" "$TARGET" 2>/dev/null; then
  sed -i \
    -e 's/bg-gradient-to-br from-slate-50 to-blue-50/bg-gradient-to-br from-primary-200 via-primary-300 to-primary-100/g' \
    -e 's/bg-gradient-to-b from-slate-50 to-blue-50/bg-gradient-to-br from-primary-200 via-primary-300 to-primary-100/g' \
    "$TARGET"
  echo "    ✅ Hero gradient standardized"
fi

# ── Step 5: Check for remaining raw Tailwind colors ───────
echo "  Step 4: Checking for remaining raw colors..."
REMAINING=$(grep -cP 'bg-(slate|gray|indigo|green|purple|orange|red|pink|teal|cyan|violet|fuchsia|rose|yellow|lime|emerald)-[0-9]' "$TARGET" 2>/dev/null || echo 0)
if [ "$REMAINING" -gt 0 ]; then
  echo "    ⚠️  $REMAINING raw color tokens may remain — manual review recommended"
else
  echo "    ✅ No raw color tokens detected"
fi

echo ""
echo "✅ Done: $TARGET"
echo "   Review manually: git diff $TARGET"
