#!/usr/bin/env python3
"""Context router -- suggests which CONTEXT*.md file is relevant to the current
session, based on which parts of the repo recent work has actually touched, and
always points to where older history lives. Run by the SessionStart hook
(inject-context.sh), right after MEMORY.md/CONTEXT.md/CONTEXT-publicdigit.md are
injected -- this is a SUGGESTION for a human or Claude to read, never an automatic
context switch (Claude Code has no reliable signal for "which project" at session
start; recently-touched paths are evidence, not certainty).

Created 2026-09-28, alongside the CONTEXT.md / CONTEXT-publicdigit.md /
CONTEXT-ARCHIVE-2026-07-08.md split.
"""
import subprocess
import sys
from pathlib import Path

PUBLICDIGIT_PREFIXES = ("app/",)
KNOWLEDGEOS_PREFIXES = (
    "scripts/lib/EngineeringKnowledge/",
    "docs/knowledgeos/",
    ".claude/",
)


def repo_root() -> Path:
    try:
        out = subprocess.run(
            ["git", "rev-parse", "--show-toplevel"],
            capture_output=True, text=True, check=True,
        )
        return Path(out.stdout.strip())
    except Exception:
        return Path.cwd()


def touched_paths(root: Path) -> list[str]:
    """Uncommitted changes (staged + unstaged + untracked) plus the last commit's
    files -- a broader, more forgiving signal than uncommitted changes alone, since a
    session may start right after a commit with nothing yet uncommitted."""
    paths: list[str] = []
    try:
        status = subprocess.run(
            ["git", "-C", str(root), "status", "--porcelain"],
            capture_output=True, text=True, check=True,
        ).stdout
        for line in status.splitlines():
            p = line[3:].strip().strip('"')
            if p:
                paths.append(p)
    except Exception:
        pass
    try:
        last_commit = subprocess.run(
            ["git", "-C", str(root), "diff-tree", "--no-commit-id", "--name-only", "-r", "HEAD"],
            capture_output=True, text=True, check=True,
        ).stdout
        paths.extend(p.strip() for p in last_commit.splitlines() if p.strip())
    except Exception:
        pass
    return paths


def classify(paths: list[str]) -> tuple[int, int, int]:
    pd = sum(1 for p in paths if p.startswith(PUBLICDIGIT_PREFIXES))
    kos = sum(1 for p in paths if p.startswith(KNOWLEDGEOS_PREFIXES))
    other = len(paths) - pd - kos
    return pd, kos, other


def main() -> None:
    root = repo_root()
    paths = touched_paths(root)
    pd, kos, other = classify(paths)

    print("=== CONTEXT ROUTER (suggestion only -- not an automatic switch) ===")
    if not paths:
        print("No recent (uncommitted or last-commit) changes detected to base a suggestion on.")
    elif pd and not kos:
        print(f"Recent changes ({pd} file(s)) are in PublicDigit paths (app/).")
        print("Suggested primary context: .claude/CONTEXT-publicdigit.md")
    elif kos and not pd:
        print(f"Recent changes ({kos} file(s)) are in KnowledgeOS paths (scripts/lib/EngineeringKnowledge/, docs/knowledgeos/, .claude/).")
        print("Suggested primary context: .claude/CONTEXT.md")
    elif pd and kos:
        print(f"Recent changes touch BOTH PublicDigit ({pd} file(s)) and KnowledgeOS ({kos} file(s)) paths.")
        print("Suggested: check both .claude/CONTEXT-publicdigit.md and .claude/CONTEXT.md.")
    else:
        print(f"Recent changes ({other} file(s)) are outside both known path groups -- no suggestion.")

    print()
    print("Older KnowledgeOS history (entries dated 2026-08-30 and earlier) lives in")
    print(".claude/CONTEXT-ARCHIVE-2026-07-08.md -- consult it only if CONTEXT.md doesn't")
    print("have what you need; it is NOT auto-injected (large, rarely needed).")


if __name__ == "__main__":
    sys.exit(main() or 0)
