#!/usr/bin/env bash
# Design System Token Enforcement Script
# Fails if raw Tailwind colors (bg-blue-*, bg-indigo-*, etc.) exceed threshold.
# Components should use <Button variant="..."> or token-based classes instead.

violations=$(grep -rn \
  "bg-blue-[0-9]\{3\}\|bg-indigo-[0-9]\{3\}\|bg-gray-[0-9]\{3\}\|bg-slate-[0-9]\{3\}" \
  resources/js/Pages/ \
  --include="*.vue" 2>/dev/null | wc -l)

echo "Design token violations: $violations"
echo "Baseline: 613 | Target: <50 | Final target: 0"

# Start permissive, tighten after each Phase 4 iteration
threshold=150

if [ "$violations" -gt "$threshold" ]; then
  echo "FAIL: Too many raw color violations ($violations > $threshold)."
  echo "Use <Button variant=\"primary|danger|...\">, <Card>, or design token classes."
  exit 1
fi

echo "PASS: Violations within acceptable range."
exit 0
