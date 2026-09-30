"""Parsing a commit-message subject line into its governed components.

Pure domain logic: no filesystem, no git, no environment access. A CommitMessage
is a value object describing what the subject line *says*, not whether the
underlying action was authorized -- see governance_rule.py for that distinction.
"""
from __future__ import annotations

import re
from dataclasses import dataclass
from typing import Optional


# Grammar, derived from this repository's own real commit history (`git log
# --format=%s`), not invented:
#   - TYPE:        one of the types this repo actually uses (feat/fix/docs/
#                   chore/refactor/test/style/exec/govern/research/perf/build/ci).
#   - SESSION:      a Claude Code session id. Observed shape in this
#                   environment (CLAUDE_CODE_SESSION_ID): a 36-character UUID
#                   (8-4-4-4-12 lowercase hex). Not assumed -- measured.
#   - TICKET:       optional. This repository does not use Jira anywhere
#                   (verified: zero occurrences in CLAUDE.md/MEMORY.md/
#                   session archives); its real ticket-shaped identifiers are
#                   PBDIGIT-nn (product stories) and KOS-<name>-nnn
#                   (KnowledgeOS work items). TICKET is therefore a general
#                   PROJECT-IDENTIFIER pattern, not a literal Jira pattern --
#                   a deliberate, disclosed substitution for this repo.
#   - DESCRIPTION:  non-empty, single-line, no control characters.
TYPES = frozenset({
    "feat", "fix", "docs", "chore", "refactor", "test", "style",
    "exec", "govern", "research", "perf", "build", "ci",
})

_SESSION_RE = r"[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
_TICKET_RE = r"[A-Z][A-Z0-9]*-[A-Za-z0-9][A-Za-z0-9-]*"
_TYPE_RE = r"[a-zA-Z]+"

MAX_DESCRIPTION_LEN = 200

_PATTERN = re.compile(
    rf"^(?P<type>{_TYPE_RE})"
    rf"\((?P<session>{_SESSION_RE})\):"
    rf"(?:\[(?P<ticket>{_TICKET_RE})\])?"
    rf" (?P<description>.+)$"
)


@dataclass(frozen=True)
class CommitMessage:
    """The parsed shape of a subject line -- a syntactic fact, nothing more."""

    commit_type: str
    session_id: str
    ticket: Optional[str]
    description: str

    @property
    def has_ticket(self) -> bool:
        return self.ticket is not None


class CommitMessageParseError(Exception):
    """Raised when a subject line does not match the governed grammar at all."""


def parse(subject_line: str) -> CommitMessage:
    """Parse a single-line commit subject. Raises CommitMessageParseError on
    structural mismatch; does NOT validate semantic rules (allowed type,
    session format legitimacy, ticket format) -- see governance_rule.py.
    This function only recognizes the *shape* TYPE(SESSION):[TICKET] DESC.
    """
    if "\n" in subject_line or "\r" in subject_line:
        raise CommitMessageParseError("subject line must not contain newlines")

    match = _PATTERN.match(subject_line)
    if not match:
        raise CommitMessageParseError(
            f"subject line does not match TYPE(SESSION):[TICKET] DESCRIPTION "
            f"or TYPE(SESSION): DESCRIPTION: {subject_line!r}"
        )

    description = match.group("description")
    if description.strip() != description:
        raise CommitMessageParseError("description has leading/trailing whitespace")
    if not description.strip():
        raise CommitMessageParseError("description must not be empty")

    return CommitMessage(
        commit_type=match.group("type"),
        session_id=match.group("session"),
        ticket=match.group("ticket"),
        description=description,
    )
