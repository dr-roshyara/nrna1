#!/usr/bin/env bash
# SessionStart hook — the ".claude project OS" bootstrap:
#   1. ensures the .claude structure exists (self-healing)
#   2. injects MEMORY.md + CONTEXT.md + today's session log into context
# so the CLAUDE.md "read at session start" rule is mechanical, not optional.
set -u
cd "$(dirname "$0")/../.." || exit 0

mkdir -p .claude/sessions .claude/plans

echo "=== AUTO-INJECTED BY SessionStart HOOK (per CLAUDE.md; also read the active plan in .claude/plans/ before working) ==="

for f in .claude/MEMORY.md .claude/CONTEXT.md; do
  if [ -f "$f" ]; then
    echo; echo "--- $f ---"
    cat "$f"
  else
    echo; echo "--- $f MISSING — create it per CLAUDE.md before finishing this session ---"
  fi
done

# Active plan: CONTEXT.md is the AUTHORITY. Preferred: the structured
# "Plan: <path>" line in the Active Work block. Legacy: first plan path in
# prose. Mtime heuristic is only the last-resort fallback.
plan=$(sed -n 's/^Plan:[[:space:]]*//p' .claude/CONTEXT.md 2>/dev/null | head -1)
src="declared in CONTEXT.md (Plan: line)"
if [ -z "${plan:-}" ] || [ ! -f "$plan" ]; then
  plan=$(grep -o '\.claude/plans/[A-Za-z0-9._-]*\.md' .claude/CONTEXT.md 2>/dev/null | head -1)
  src="declared in CONTEXT.md"
fi
if [ -z "${plan:-}" ] || [ ! -f "$plan" ]; then
  plan=$(ls -t .claude/plans/*.md 2>/dev/null | head -1)
  src="fallback: most recently modified — declare it in CONTEXT.md"
fi
if [ -n "${plan:-}" ] && [ -f "$plan" ]; then
  echo; echo "--- $plan (active plan — $src) ---"
  cat "$plan"
fi

today=".claude/sessions/$(date +%F).md"
if [ -f "$today" ]; then
  echo; echo "--- $today (today's session log — APPEND to it, don't recreate) ---"
  cat "$today"
fi

exit 0
