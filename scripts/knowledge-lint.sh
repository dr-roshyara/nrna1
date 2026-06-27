#!/bin/bash

# Engineering Knowledge Platform (EKP) — knowledge-lint wrapper.
# "PHPStan for knowledge." Thin wrapper over scripts/knowledge-lint.php.
#
# Usage:
#   ./scripts/knowledge-lint.sh            # report; non-blocking (warn-only)
#   ./scripts/knowledge-lint.sh --strict   # exit 1 on any ERROR (use in CI once clean)
#   ./scripts/knowledge-lint.sh --json     # machine-readable

set -euo pipefail
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
exec php "$DIR/scripts/knowledge-lint.php" "$@"
