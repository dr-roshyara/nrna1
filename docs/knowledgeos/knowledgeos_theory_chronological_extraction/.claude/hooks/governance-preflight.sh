#!/usr/bin/env bash
# governance-preflight.sh — THE DOOR. Nothing else.
#
# TEMPORARY KnowledgeOS research governance, scoped to this workspace.
# Not wired into any settings.json: wiring is a change to project-root
# configuration and is a human act. Invoke it at research-session entry
# and at checkpoints.
#
# This script contains NO governance rules.
#   gates.yaml       is the rule book
#   gate-runner.py   is the measuring instrument
#   a reviewer       judges the REVIEW-class gates
#   the human        decides what cannot be automated
#
# Exit 0 = CLEAR : all applicable ACTIVATED automated preconditions passed.
#                  NOT a statement that the research is valid.
# Exit 2 = BLOCK : the session may not proceed under the currently ACTIVE
#                  and ACTIVATED rules. NOT a statement that the theory
#                  is wrong.
# Exit 3 = GOVERNANCE_INOPERATIVE: the door or the instrument is broken
#          (runner missing, interpreter absent, runner exit 3 or any other
#          non-verdict exit). Never reported as BLOCK, never as CLEAR.

set -uo pipefail

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
WORKSPACE="$(cd "$HERE/../.." && pwd)"
RUNNER="$WORKSPACE/governance/gate-runner.py"

STAGE="${1:-}"
TIMING="${2:-}"

if [[ ! -f "$RUNNER" ]]; then
  printf 'governance-preflight: runner not found at %s\n' "$RUNNER" >&2
  exit 3
fi

if ! command -v python3 >/dev/null 2>&1; then
  printf 'governance-preflight: python3 not available\n' >&2
  exit 3
fi

args=()
[[ -n "$STAGE"  ]] && args+=(--stage "$STAGE")
[[ -n "$TIMING" ]] && args+=(--timing "$TIMING")

printf 'GOVERNANCE PREFLIGHT — temporary KnowledgeOS research mechanism\n'
printf -- '----------------------------------------------------------------\n'
out="$(python3 "$RUNNER" "${args[@]}")"
status=$?
printf '%s\n' "$out"
printf -- '----------------------------------------------------------------\n'

if [[ $status -eq 0 ]]; then
  if printf '%s' "$out" | grep -q 'NO_ACTIVE_GOVERNANCE_CONTROLS'; then
    # No control was exercised, so nothing was cleared. The word CLEAR must
    # not appear here: a reader skimming for it would take absence of control
    # for approval.
    # IR-G2 (L0-DEC-21): true whether nothing is activated or the activated
    # gates do not apply to the requested stage/timing.
    printf 'Research may continue because NO ACTIVATED CONTROL WAS EXERCISED\n'
    printf '(none is activated, or none applies to the requested stage/timing).\n'
    printf '\n'
    printf 'THIS IS NOT GOVERNANCE APPROVAL.\n'
    printf 'No gate was exercised. Nothing here was accepted, reviewed or cleared.\n'
  else
    printf 'CLEAR — every applicable ACTIVATED gate passed.\n'
    printf '\n'
    printf 'This states process conformance against the activated rules only.\n'
    printf 'It is NOT a statement that the research is valid or the theory right.\n'
  fi
  exit 0
fi

if [[ $status -eq 1 ]]; then
  printf 'BLOCK — may not proceed under the currently activated rules.\n' >&2
  exit 2
fi

# Exit 3 (or anything else: a crash, a usage error) means the instrument
# could not produce a trustworthy verdict. It is NOT a BLOCK verdict about the
# research and NOT a CLEAR (IC-6). Research does not proceed either way.
printf 'GOVERNANCE_INOPERATIVE — the governance mechanism itself is not operable (runner exit %s).\n' "$status" >&2
printf 'This is NOT a gate verdict. Research may not proceed until it is repaired or re-activated.\n' >&2
exit 3
