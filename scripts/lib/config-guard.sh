# ═══════════════════════════════════════════════════════════════
# config-guard.sh — fail-closed validation for gate configuration
# ═══════════════════════════════════════════════════════════════
#
# EG-002a (docs/plans/20260726-2056-engineering-platform-repair-plan.md):
# a governance gate must REFUSE to run when it cannot read its policy.
# Before this guard, an empty/garbage parse made every numeric test
# error out as "false" and the gates passed without measuring anything
# (audit: docs/implementation/20260726_PrePush_Governance_Gate_Audit.md,
# F-GATE-2).
#
# Usage (after parsing a numeric config value with jq):
#   source "$SCRIPT_DIR/lib/config-guard.sh"
#   require_int ".baseline" "$BASELINE"
#
# Integers may be negative (target: -1 = informational is legal).
# ═══════════════════════════════════════════════════════════════

require_int() {
  local name="$1" value="${2-}"
  if [[ "$value" =~ ^-?[0-9]+$ ]]; then
    return 0
  fi
  {
    echo ""
    echo "❌ CONFIG PARSE FAILURE (fail-closed): ${name} must be an integer, got '${value}'."
    echo "   Likely causes: jq missing or shadowed by the npm impostor (self-check in"
    echo "   scripts/README.md), or invalid JSON in the gate's config file."
    echo "   This gate refuses to run on unreadable policy — it does NOT pass by default."
  } >&2
  exit 1
}
