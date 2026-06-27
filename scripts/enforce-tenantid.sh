#!/usr/bin/env bash

# TenantId Governance Enforcement Gate
# Prevents regression of canonical identity model
# Part of Architecture Guard Framework (Phase 1: Static Enforcement)

set -euo pipefail

echo "🔍 TenantId Governance Enforcement..."

# Get all PHP files in Membership context
MEMBERSHIP_DIR="app/Contexts/Membership"

if [ ! -d "$MEMBERSHIP_DIR" ]; then
  echo "⚠️  Membership context directory not found at $MEMBERSHIP_DIR — skipping."
  exit 0
fi

FILES=$(find "$MEMBERSHIP_DIR" -name "*.php" -type f 2>/dev/null)

if [ -z "$FILES" ]; then
  echo "✅ No PHP files found in Membership context — nothing to check."
  exit 0
fi

# Check for forbidden legacy TenantId usage
VIOLATIONS=$(echo "$FILES" | xargs grep -l "Membership\\\\Domain\\\\ValueObjects\\\\TenantId" 2>/dev/null || true)

if [ -n "$VIOLATIONS" ]; then
  echo ""
  echo "❌ GOVERNANCE VIOLATION: Forbidden TenantId Usage"
  echo ""
  echo "Membership context MUST use only: App\Contexts\Shared\Domain\ValueObjects\TenantId"
  echo ""
  echo "Violations found in:"
  echo "$VIOLATIONS"
  echo ""
  exit 1
fi

# Check for unsafe constructor calls
UNSAFE=$(echo "$FILES" | xargs grep -l "new TenantId(" 2>/dev/null || true)

if [ -n "$UNSAFE" ]; then
  echo ""
  echo "❌ GOVERNANCE VIOLATION: Unsafe TenantId Constructor"
  echo ""
  echo "TenantId must be constructed through canonical factories:"
  echo "  - TenantId::fromString()"
  echo "  - TenantId::fromOrganisationId()"
  echo ""
  echo "Unsafe usage found in:"
  echo "$UNSAFE"
  echo ""
  exit 1
fi

echo "✅ TenantId governance check passed"
exit 0
