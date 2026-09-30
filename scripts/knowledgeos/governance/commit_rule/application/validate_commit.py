"""Orchestration: validate a commit-message file the way a `commit-msg` git
hook receives it (a path to a file containing the message). No git access
here either -- reads the file it's given, nothing more. Adapters own I/O
sources; this layer owns the sequencing.
"""
from __future__ import annotations

from dataclasses import dataclass
from pathlib import Path

from ..domain.governance_rule import Result, evaluate


@dataclass(frozen=True)
class ValidationOutcome:
    result: Result
    exit_code: int  # 0 = accept, 1 = reject -- deterministic, no ML/network.

    @property
    def accepted(self) -> bool:
        return self.exit_code == 0


def validate_commit_message_file(path: Path) -> ValidationOutcome:
    """Read the first line (the subject) of a commit-message file and
    evaluate it against the governance rule."""
    text = path.read_text(encoding="utf-8")
    subject_line = text.split("\n", 1)[0]
    result = evaluate(subject_line)
    return ValidationOutcome(result=result, exit_code=0 if result.conforms else 1)


def validate_commit_message_text(subject_line: str) -> ValidationOutcome:
    """Same as above, for a subject line already in hand (used by tests and
    by any caller that doesn't have a file, e.g. a CI step reading from a
    pull-request title)."""
    result = evaluate(subject_line)
    return ValidationOutcome(result=result, exit_code=0 if result.conforms else 1)
