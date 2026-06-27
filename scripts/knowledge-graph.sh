#!/bin/bash

# Engineering Knowledge Platform (EKP) — knowledge-graph generator wrapper.
# Regenerates the Mermaid knowledge graph under docs/knowledge/portal/graph/.
#
# Usage: ./scripts/knowledge-graph.sh

set -euo pipefail
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
exec php "$DIR/scripts/knowledge-graph.php"
