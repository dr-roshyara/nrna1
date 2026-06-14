#!/usr/bin/env bash

# ═══════════════════════════════════════════════════════════════
# check-domain-purity.sh — Domain Layer Purity Gate v2
# ═══════════════════════════════════════════════════════════════
#
# Governance Layer: Domain Architecture
# Orchestrator:     verify.sh (warning-only)
#
# Verifies that resources/js/Domain/ contains NO framework,
# transport, browser, or routing dependencies, protecting the
# core domain model from coupling.
#
# Forbidden categories:
#   Framework:   vue, pinia, inertia, vue-router
#   Transport:   axios, fetch, XMLHttpRequest, laravel-echo
#   Browser:     window, document, localStorage, sessionStorage
#   Routing:     ziggy-js, route(
#
# Usage:
#   ./scripts/check-domain-purity.sh
#
# Exit codes: 0 = pure, 1 = violations found
# ═══════════════════════════════════════════════════════════════

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"
DOMAIN_DIR="$PROJECT_DIR/resources/js/Domain"

echo "🔬 Domain Purity Check"
echo "═══════════════════════"
echo "  Domain dir: $DOMAIN_DIR"
echo ""

if [ ! -d "$DOMAIN_DIR" ]; then
  echo "⚠️  Domain directory does not exist yet — nothing to check."
  echo "   Create resources/js/Domain/ to start using domain-driven frontend."
  exit 0
fi

# ──────────────────────────────────────────────
# Find all TypeScript source files in Domain/
# ──────────────────────────────────────────────
FILES=$(find "$DOMAIN_DIR" -type f \( -name "*.ts" -o -name "*.js" \) 2>/dev/null)

if [ -z "$FILES" ]; then
  echo "ℹ️  No TypeScript/JavaScript files found in Domain/."
  echo "   Add .ts files to Domain/ to start building your domain model."
  exit 0
fi

echo "📄 Files in Domain/:"
echo "$FILES" | while read f; do
  echo "  - ${f#$PROJECT_DIR/}"
done
echo ""

# ──────────────────────────────────────────────
# Define forbidden patterns
# ──────────────────────────────────────────────
FORBIDDEN_PATTERNS=(
  # Framework
  "from ['\"].*vue['\"]"
  "from ['\"].*pinia['\"]"
  "from ['\"].*inertia['\"]"
  "from ['\"].*vue-router['\"]"
  "import.*from ['\"]vue"
  "import.*inertia"

  # Transport
  "from ['\"].*axios['\"]"
  "import axios"
  "fetch("
  "XMLHttpRequest"
  "from ['\"].*laravel-echo['\"]"
  "Echo\."

  # Browser
  "window\."
  "document\."
  "localStorage\."
  "sessionStorage\."

  # Routing
  "from ['\"].*ziggy-js['\"]"
  "route("
)

VIOLATIONS=0

echo "🔍 Scanning for framework dependencies..."
echo "═══════════════════════════════════════════"

for pattern in "${FORBIDDEN_PATTERNS[@]}"; do
  while IFS= read -r match; do
    if [ -n "$match" ]; then
      echo "  ❌ $match"
      VIOLATIONS=$((VIOLATIONS + 1))
    fi
  done < <(grep -rn "$pattern" "$DOMAIN_DIR" --include="*.ts" --include="*.js" 2>/dev/null || true)
done

echo ""

# ──────────────────────────────────────────────
# Report
# ──────────────────────────────────────────────
if [ "$VIOLATIONS" -eq 0 ]; then
  echo "✅ Domain layer is pure — no framework dependencies found."
  exit 0
else
  echo "❌ $VIOLATIONS framework dependency/ies found in Domain/."
  echo ""
  echo "   Domain/ must not import or depend on:"
  echo "     Framework:   vue, pinia, inertia, vue-router"
  echo "     Transport:   axios, fetch, XMLHttpRequest, laravel-echo"
  echo "     Browser:     window, document, localStorage, sessionStorage"
  echo "     Routing:     ziggy-js, route()"
  echo ""
  echo "   Move framework-dependent code to Application/UseCases/ or keep it in Pages/."
  exit 1
fi
