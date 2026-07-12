#!/usr/bin/env bash
# Database Safety Check Hook
# Blocks destructive database commands unless targeting the test database.
#
# Intercepted patterns:
#   migrate:fresh, migrate:refresh, db:seed, migrate --seed
#
# Allowed when ANY of:
#   - APP_ENV=testing is set
#   --env=testing is passed to artisan
#   DB_DATABASE contains "test"
set -euo pipefail

COMMAND="$1"

# Patterns that are DANGEROUS to run on the main database
DANGEROUS_PATTERNS=(
  "migrate:fresh"
  "migrate:refresh"
  "db:seed"
  "migrate --seed"
)

for pattern in "${DANGEROUS_PATTERNS[@]}"; do
  if echo "$COMMAND" | grep -qiE "$pattern"; then
    # Check if testing environment is explicitly specified
    if echo "$COMMAND" | grep -qiE "(APP_ENV=testing|--env=testing|DB_DATABASE.*test)"; then
      exit 0
    fi

    # Claude Code hook semantics: only exit code 2 blocks a PreToolUse tool
    # call (stderr becomes the reason shown to the model). Exit 1 is a
    # NON-blocking warning — the command still runs. This branch previously
    # used `exit 1`, so the "BLOCKED" message was cosmetic only; the
    # destructive command executed regardless. Fixed 2026-07-12.
    {
      echo ""
      echo "⚠️  BLOCKED: Destructive database command detected"
      echo "   Command: $COMMAND"
      echo "   Pattern: $pattern"
      echo ""
      echo "   This targets the MAIN database. To proceed:"
      echo "     ✅ Add --env=testing  (e.g. php artisan migrate:fresh --env=testing)"
      echo "     ✅ Set APP_ENV=testing (e.g. APP_ENV=testing php artisan migrate:fresh)"
      echo ""
      echo "   Tip: Use 'php artisan test' instead — it handles DB setup automatically."
      echo ""
    } 1>&2
    exit 2
  fi
done

# No dangerous pattern matched — explicit success. Without this, the
# script's exit code falls through to the last executed command (the final
# failed grep match), which is non-zero and would surface as a spurious
# non-blocking warning on every ordinary command.
exit 0
