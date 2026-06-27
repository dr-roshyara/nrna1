#!/usr/bin/env bash

# ═══════════════════════════════════════════════════════════════
# structure-check.sh — Frontend DDD Structure Visibility v2
# ═══════════════════════════════════════════════════════════════
#
# Governance Layer: Domain Architecture
# Source of truth: scripts/frontend-architecture.json
# Orchestrator:    verify.sh (warning-only)
#
# Reads the architecture registry and checks each bounded context
# for Domain/ and Application/ directory coverage.
# Warning-only — never fails CI.
#
# Usage:
#   ./scripts/structure-check.sh
#
# Exit codes: always 0 (warning-only)
# ═══════════════════════════════════════════════════════════════

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
JS_DIR="$PROJECT_DIR/resources/js"
CONFIG_FILE="$SCRIPT_DIR/frontend-architecture.json"

echo "🏗️  Frontend DDD Structure Check"
echo "══════════════════════════════════"
echo ""

# Require jq
if ! command -v jq &>/dev/null; then
  echo "⚠️  jq is required but not installed — skipping."
  echo "   Install: sudo apt install jq -y  (Linux)  or  brew install jq  (macOS)"
  exit 0
fi

if [ ! -f "$CONFIG_FILE" ]; then
  echo "⚠️  frontend-architecture.json not found at $CONFIG_FILE"
  echo "   Create it to enable DDD structure tracking."
  exit 0
fi

# ──────────────────────────────────────────────
# Read strategy
# ──────────────────────────────────────────────
APPLY_TO_NEW_ONLY=$(jq -r '.strategy.apply_to_new_features_only // false' "$CONFIG_FILE")
if [ "$APPLY_TO_NEW_ONLY" = "true" ]; then
  echo "  Strategy: New features only ✅ (existing code preserved)"
fi
echo ""

# ──────────────────────────────────────────────
# Read contexts from config
# ──────────────────────────────────────────────
CONTEXT_COUNT=$(jq '.contexts | length' "$CONFIG_FILE")

if [ "$CONTEXT_COUNT" -eq 0 ]; then
  echo "  ℹ️  No bounded contexts defined in frontend-architecture.json."
  echo "     Add contexts to the 'contexts' array to track DDD adoption."
  exit 0
fi

echo "Bounded Context Coverage:"
echo "══════════════════════════"
echo ""

HAS_DOMAIN=false
HAS_APPLICATION=false

for ((i = 0; i < CONTEXT_COUNT; i++)); do
  NAME=$(jq -r ".contexts[$i].name // \"Context_$i\"" "$CONFIG_FILE")
  DOMAIN_PATH=$(jq -r ".contexts[$i].path // \"\"" "$CONFIG_FILE")
  APP_PATH=$(jq -r ".contexts[$i].application // \"\"" "$CONFIG_FILE")
  PAGES_PATH=$(jq -r ".contexts[$i].pages // \"\"" "$CONFIG_FILE")

  # Check Domain/
  domain_count=0
  domain_status="⬜"
  if [ -n "$DOMAIN_PATH" ] && [ -d "$PROJECT_DIR/$DOMAIN_PATH" ]; then
    domain_count=$(find "$PROJECT_DIR/$DOMAIN_PATH" -type f 2>/dev/null | wc -l || true)
    if [ "$domain_count" -gt 0 ]; then
      domain_status="✅"
      HAS_DOMAIN=true
    fi
  fi

  # Check Application/
  app_count=0
  app_status="⬜"
  if [ -n "$APP_PATH" ] && [ -d "$PROJECT_DIR/$APP_PATH" ]; then
    app_count=$(find "$PROJECT_DIR/$APP_PATH" -type f 2>/dev/null | wc -l || true)
    if [ "$app_count" -gt 0 ]; then
      app_status="✅"
      HAS_APPLICATION=true
    fi
  fi

  # Count Pages
  pages_count=0
  if [ -n "$PAGES_PATH" ] && [ -d "$PROJECT_DIR/$PAGES_PATH" ]; then
    pages_count=$(find "$PROJECT_DIR/$PAGES_PATH" -name "*.vue" -type f 2>/dev/null | wc -l || true)
  fi

  printf "  %-20s Domain: %s (%2d)  |  Application: %s (%2d)  |  Pages: %d\n" \
    "$NAME:" "$domain_status" "$domain_count" "$app_status" "$app_count" "$pages_count"
done

echo ""

# ──────────────────────────────────────────────
# ARCH rules check
# ──────────────────────────────────────────────
RULE_COUNT=$(jq '.rules | length' "$CONFIG_FILE")
if [ "$RULE_COUNT" -gt 0 ]; then
  echo "ARCH Rules:"
  echo "═══════════"
  for ((i = 0; i < RULE_COUNT; i++)); do
    RULE_ID=$(jq -r ".rules[$i].id // \"ARCH-??\"" "$CONFIG_FILE")
    RULE_SEV=$(jq -r ".rules[$i].severity // \"info\"" "$CONFIG_FILE")
    RULE_DESC=$(jq -r ".rules[$i].description // \"\"" "$CONFIG_FILE")
    [ -z "$RULE_DESC" ] && continue
    echo "  $RULE_ID [$RULE_SEV] — $RULE_DESC"
  done
  echo ""
fi

# ──────────────────────────────────────────────
# Summary
# ──────────────────────────────────────────────
echo "Summary:"
echo "════════"
printf "  Domain layer:       "
if [ "$HAS_DOMAIN" = true ]; then echo "✅ Growing — domain models present"; else echo "⬜ None yet"; fi
printf "  Application layer:  "
if [ "$HAS_APPLICATION" = true ]; then echo "✅ Growing — use cases present"; else echo "⬜ None yet"; fi
echo ""

if [ "$HAS_DOMAIN" = false ]; then
  echo "  💡 Add Domain/<Context>/*.ts for pure business logic."
fi
if [ "$HAS_DOMAIN" = true ] && [ "$HAS_APPLICATION" = false ]; then
  echo "  💡 Add Application/<Context>/*UseCase.ts for framework-dependent orchestration."
fi

echo ""
echo "  ℹ️  Visibility check only — no CI failures."
exit 0
