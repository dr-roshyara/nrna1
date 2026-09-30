#!/usr/bin/env python3
"""Git `commit-msg` hook adapter.

Deterministic only: no LLM, no ML, no network, no Jira API call. Invoked as:

    python3 commit_msg_hook.py <path-to-commit-msg-file>

ENFORCEMENT IS OPT-IN, BY DESIGN, NOT YET ACTIVE BY DEFAULT:
Wiring this into .husky/commit-msg would immediately start rejecting every
future commit in this repository -- including a human's own manual commits
and any other session's -- unless it already uses the new
TYPE(SESSION):[TICKET] DESCRIPTION format this repo has never used before
(verified: `git log --format=%s` shows zero prior commits in this shape).
That is a real, hard-to-reverse change to shared workflow, not something this
adapter should activate unilaterally.

So: this script always reports (prints the CommitMessageConforms verdict and
explanation) but only BLOCKS the commit (non-zero exit) when the environment
variable KOS_ENFORCE_COMMIT_RULE=1 is set. Unset or any other value:
advisory-only, exit 0 always -- matching this repository's own established
pattern for its other advisory hooks (scripts/observations/git-hooks/post-commit).
"""
from __future__ import annotations

import os
import sys
from pathlib import Path

_THIS_DIR = Path(__file__).resolve().parent
_COMMIT_RULE_ROOT = _THIS_DIR.parent.parent  # .../commit_rule
sys.path.insert(0, str(_COMMIT_RULE_ROOT.parent.parent.parent))  # .../scripts

from knowledgeos.governance.commit_rule.application.validate_commit import (  # noqa: E402
    validate_commit_message_file,
)

ENFORCE_ENV_VAR = "KOS_ENFORCE_COMMIT_RULE"


def main(argv: list[str]) -> int:
    if len(argv) != 2:
        print("usage: commit_msg_hook.py <path-to-commit-msg-file>", file=sys.stderr)
        return 2

    msg_path = Path(argv[1])
    outcome = validate_commit_message_file(msg_path)
    print(outcome.result.explain())

    enforce = os.environ.get(ENFORCE_ENV_VAR) == "1"
    if not outcome.accepted:
        if enforce:
            print(
                f"\nBLOCKED ({ENFORCE_ENV_VAR}=1). To bypass: git commit --no-verify",
                file=sys.stderr,
            )
            return 1
        print(
            f"\nADVISORY ONLY -- not blocking (set {ENFORCE_ENV_VAR}=1 to enforce).",
            file=sys.stderr,
        )
    return 0


if __name__ == "__main__":
    raise SystemExit(main(sys.argv))
