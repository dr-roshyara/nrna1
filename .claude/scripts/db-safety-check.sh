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
    exit 1
  fi
done
