"""The governance rule: does a commit message CONFORM to the required record
format?

CRITICAL EPISTEMIC BOUNDARY (do not weaken this file to blur it):
This module establishes exactly one proposition:

    CommitMessageConforms(subject_line) : bool

It does NOT establish, and must never be read as establishing:

    CommitAuthorized       -- whether the underlying change was authorized
    ActorAttested          -- whether the session id names a real, verified actor
    WorkAuthorized         -- whether a cited ticket means the work was approved

A conforming commit message is a *record-format* fact. Per this repository's
own INV-ATTR-1/INV-ATTR-2 (self-declared session identity is evidential, never
attestable) and the empirical findings in
docs/knowledgeos/reviews/2026-09-29-KOS-EVIDENCE-DETERMINATION-FAILURE-MODES.md,
a session id being present and well-formed says nothing about whether that
session's actions were authorized. Any caller that treats a `Result` here as
authorization evidence is making an error this module explicitly warns against.
"""
from __future__ import annotations

from dataclasses import dataclass, field
from typing import List

from .commit_message import (
    TYPES,
    MAX_DESCRIPTION_LEN,
    CommitMessage,
    CommitMessageParseError,
    parse,
)


@dataclass(frozen=True)
class Violation:
    code: str
    message: str


@dataclass(frozen=True)
class Result:
    """The ONLY claim this carries is CommitMessageConforms. See module
    docstring: this is never evidence of authorization, attestation, or
    approved work -- only of record-format conformance."""

    conforms: bool
    violations: List[Violation] = field(default_factory=list)
    parsed: "CommitMessage | None" = None

    def explain(self) -> str:
        if self.conforms:
            return "CommitMessageConforms: TRUE"
        lines = ["CommitMessageConforms: FALSE"]
        for v in self.violations:
            lines.append(f"  - [{v.code}] {v.message}")
        return "\n".join(lines)


def evaluate(subject_line: str) -> Result:
    """Evaluate a subject line against the governed record-format grammar.
    Pure function: no I/O, no environment access, no git access."""
    violations: List[Violation] = []

    try:
        parsed = parse(subject_line)
    except CommitMessageParseError as exc:
        return Result(
            conforms=False,
            violations=[Violation("MALFORMED", str(exc))],
            parsed=None,
        )

    if parsed.commit_type not in TYPES:
        violations.append(
            Violation(
                "UNKNOWN_TYPE",
                f"{parsed.commit_type!r} is not an allowed commit type "
                f"(allowed: {', '.join(sorted(TYPES))})",
            )
        )

    if len(parsed.description) > MAX_DESCRIPTION_LEN:
        violations.append(
            Violation(
                "DESCRIPTION_TOO_LONG",
                f"description is {len(parsed.description)} characters, "
                f"exceeds the {MAX_DESCRIPTION_LEN}-character limit",
            )
        )

    return Result(conforms=not violations, violations=violations, parsed=parsed)
