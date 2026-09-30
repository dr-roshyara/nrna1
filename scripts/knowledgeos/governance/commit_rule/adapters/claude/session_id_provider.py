"""Where does the Claude session id actually come from?

RESEARCH FINDING (2026-09-29, this environment, verified directly with `env`):
`CLAUDE_CODE_SESSION_ID` is a real, populated environment variable inside a
Claude Code session -- a 36-character lowercase UUID
(`[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}`), observed
directly, not invented or assumed from the task prompt's illustrative
"session123" examples.

WHAT THIS DOES AND DOES NOT ESTABLISH:
- It reliably identifies *which Claude Code session process* is running when
  a commit is made from inside that session's shell.
- It does NOT identify a human actor, does NOT prove the session's actions
  were authorized, and does NOT survive outside a Claude Code process --  a
  human running `git commit` directly, or a CI job, will not have this
  variable set at all. This is exactly INV-ATTR-1/INV-ATTR-2's point:
  self-declared session identity is evidential, never attestable, and here
  it is not even always *present*.

WHAT HAPPENS IF IT'S ABSENT: this provider returns None. It does not
fabricate a session id, does not fall back to a placeholder, and does not
silently treat a missing session as an error the caller must handle -- the
governance rule (domain/governance_rule.py) requires a syntactically valid
session id in the commit message itself; whether that id came from this
provider, was typed by a human copying it, or is absent entirely is a
question this adapter cannot answer and does not pretend to.
"""
from __future__ import annotations

import os
from typing import Optional

ENV_VAR_NAME = "CLAUDE_CODE_SESSION_ID"


def get_session_id() -> Optional[str]:
    """Return the current process's Claude Code session id, or None if this
    process is not running inside a Claude Code session (e.g. a human's
    direct `git commit`, or a CI runner)."""
    value = os.environ.get(ENV_VAR_NAME)
    return value if value else None
