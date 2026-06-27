#!/usr/bin/env bash

# ═══════════════════════════════════════════════════════════════
# audit-page-structure.sh — Public Page Structure Audit v1
# ═══════════════════════════════════════════════════════════════
#
# Architecture Reference:
#   Architecture Baseline v1
#   ADR-003 Shell Visual Identity
#   Governance Transparency Developer Guide
#
# Audits a Vue page for structure and token conformance against
# the expected pattern for its page type.
#
# This is a READ-ONLY audit. No files are modified.
#
# Usage:
#   ./scripts/audit-page-structure.sh <file> --page-type=<type>
#
# Page types:
#   public    — PublicDigitLayout + gradient hero + semantic tokens
#   election  — ElectionLayout (less strict on hero)
#   admin     — AdminLayout (layout only)
#   auto      — Infer from layout in file (default)
#
# Exit codes: 0 = all checks pass, 1 = issues found
# ═══════════════════════════════════════════════════════════════

set -euo pipefail

TARGET="${1:-}"
PAGE_TYPE="auto"
ISSUES=0

# ── Parse arguments ──────────────────────────────────────
for arg in "$@"; do
  case "$arg" in
    --page-type=*) PAGE_TYPE="${arg#--page-type=}" ;;
  esac
done

if [ ! -f "$TARGET" ]; then
  echo "❌ File not found: $TARGET"
  echo "Usage: $0 <path/to/Page.vue> [--page-type=public|election|admin|auto]"
  exit 1
fi

echo "🔍 Page Structure Audit"
echo "═══════════════════════"
echo "  File: $TARGET"
echo "  Page type: $PAGE_TYPE"
echo ""

# ── Detect layout used ──────────────────────────────────
CUR_LAYOUT=""
if grep -q "PublicDigitLayout" "$TARGET" 2>/dev/null; then
  CUR_LAYOUT="PublicDigitLayout"
elif grep -q "ElectionLayout" "$TARGET" 2>/dev/null; then
  CUR_LAYOUT="ElectionLayout"
elif grep -q "AdminLayout" "$TARGET" 2>/dev/null; then
  CUR_LAYOUT="AdminLayout"
elif grep -q "NrnaLayout\|SocialLayout\|AppLayout" "$TARGET" 2>/dev/null; then
  CUR_LAYOUT=$(grep -o "NrnaLayout\|SocialLayout\|AppLayout" "$TARGET" | head -1)
fi

# Infer page type from layout if auto
if [ "$PAGE_TYPE" = "auto" ]; then
  case "$CUR_LAYOUT" in
    PublicDigitLayout) PAGE_TYPE="public" ;;
    ElectionLayout)    PAGE_TYPE="election" ;;
    AdminLayout)       PAGE_TYPE="admin" ;;
    *)                 PAGE_TYPE="public" ;; # default
  esac
fi

# ── Check 1: Layout ──────────────────────────────────────
echo "📐 Layout"
echo "─────────"
case "$PAGE_TYPE" in
  public)
    if [ "$CUR_LAYOUT" = "PublicDigitLayout" ]; then
      echo "  ✅ PublicDigitLayout (correct for public pages)"
    elif [ "$CUR_LAYOUT" = "ElectionLayout" ]; then
      echo "  ✅ ElectionLayout (acceptable for election-adjacent pages)"
    else
      echo "  ⚠️  Layout: $CUR_LAYOUT — PublicDigitLayout expected for public pages"
      ISSUES=$((ISSUES + 1))
    fi
    ;;
  election)
    if [ "$CUR_LAYOUT" = "ElectionLayout" ] || [ "$CUR_LAYOUT" = "PublicDigitLayout" ]; then
      echo "  ✅ $CUR_LAYOUT (correct for election pages)"
    else
      echo "  ⚠️  Layout: $CUR_LAYOUT — ElectionLayout or PublicDigitLayout expected"
      ISSUES=$((ISSUES + 1))
    fi
    ;;
  admin)
    if [ "$CUR_LAYOUT" = "AdminLayout" ]; then
      echo "  ✅ AdminLayout (correct for admin pages)"
    else
      echo "  ⚠️  Layout: $CUR_LAYOUT — AdminLayout expected"
      ISSUES=$((ISSUES + 1))
    fi
    ;;
esac
echo ""

# ── Check 2: Hero section (public pages only) ────────────
if [ "$PAGE_TYPE" = "public" ]; then
  echo "🎨 Hero Section"
  echo "──────────────"
  if grep -q "from-primary-200 via-primary-300" "$TARGET" 2>/dev/null; then
    echo "  ✅ Reference gradient (from-primary-200 via-primary-300)"
  elif grep -q "bg-gradient" "$TARGET" 2>/dev/null; then
    echo "  ⚠️  Has gradient — verify it matches primary-200/300/100 pattern"
  else
    echo "  ℹ️  No gradient hero detected"
  fi
  echo ""
fi

# ── Check 3: Raw Tailwind color tokens ───────────────────
echo "🎯 Design Tokens"
echo "────────────────"

# Expanded color families to audit
RAW_FAMILIES="slate gray indigo green emerald purple orange amber red pink teal cyan sky lime yellow violet fuchsia rose"
RAW_TOTAL=0

for shade in $RAW_FAMILIES; do
  count=$(grep -c "bg-${shade}-[0-9]\|text-${shade}-[0-9]\|border-${shade}-[0-9]\|from-${shade}-\|to-${shade}-" "$TARGET" 2>/dev/null || echo 0)
  if [ "$count" -gt 0 ]; then
    echo "  ⚠️  $count x raw \"$shade-*\""
    # Only provide semantic mapping hints for known families
    case "$shade" in
      slate|gray)     echo "       Possible mapping: → neutral (review context)" ;;
      indigo)         echo "       Possible mapping: → primary (review context)" ;;
      green|emerald)  echo "       Possible mapping: → success (review context)" ;;
      purple)         echo "       Possible mapping: → accent (review context)" ;;
      orange|amber)   echo "       Possible mapping: → warning (review context)" ;;
      red)            echo "       Possible mapping: → danger (review context)" ;;
      *)              echo "       Manual review required" ;;
    esac
    RAW_TOTAL=$((RAW_TOTAL + count))
  fi
done

if [ "$RAW_TOTAL" -eq 0 ]; then
  echo "  ✅ No raw Tailwind color tokens detected"
else
  ISSUES=$((ISSUES + 1))
fi
echo ""

# ── Check 4: Section background alternation ──────────────
echo "📋 Section Backgrounds"
echo "──────────────────────"
FG_COUNT=$(grep -c "bg-neutral-50\|bg-primary-50\|bg-white" "$TARGET" 2>/dev/null || echo 0)
if [ "$FG_COUNT" -ge 2 ]; then
  echo "  ✅ $FG_COUNT alternating backgrounds detected"
else
  echo "  ℹ️  Few alternating backgrounds — verify sections are visually differentiated"
fi
echo ""

# ── Summary ───────────────────────────────────────────────
echo "═══════════════════════"
if [ "$ISSUES" -eq 0 ]; then
  echo "✅ All checks passed — $TARGET"
else
  echo "⚠️  $ISSUES area(s) need review — $TARGET"
fi
exit $ISSUES
