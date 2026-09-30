"""RED-first: written before running, to verify the application layer and
the CLI hook adapter actually behave as specified -- not retrofitted onto
already-passing code, unlike domain/governance_rule.py's suite (disclosed
process deviation, see the accompanying architecture report)."""
import subprocess
import sys
from pathlib import Path

import pytest

sys.path.insert(0, str(Path(__file__).resolve().parents[3]))

from knowledgeos.governance.commit_rule.application.validate_commit import (
    validate_commit_message_file,
    validate_commit_message_text,
)

SID = "11111111-1111-1111-1111-111111111111"
HOOK = Path(__file__).resolve().parents[1] / "adapters" / "git" / "commit_msg_hook.py"


class TestValidateCommitMessageFile:
    def test_valid_message_from_file(self, tmp_path):
        p = tmp_path / "COMMIT_EDITMSG"
        p.write_text(f"feat({SID}):[KOS-1] add thing\n\nbody text here\n")
        outcome = validate_commit_message_file(p)
        assert outcome.accepted
        assert outcome.exit_code == 0

    def test_invalid_message_from_file(self, tmp_path):
        p = tmp_path / "COMMIT_EDITMSG"
        p.write_text("just a plain subject line\n\nbody\n")
        outcome = validate_commit_message_file(p)
        assert not outcome.accepted
        assert outcome.exit_code == 1

    def test_only_first_line_is_evaluated(self, tmp_path):
        # a multi-line commit message file's body must not be treated as
        # part of the subject -- only the first line is the governed record.
        p = tmp_path / "COMMIT_EDITMSG"
        p.write_text(f"feat({SID}): valid subject\n\nsome body\nwith more lines\n")
        outcome = validate_commit_message_file(p)
        assert outcome.accepted


class TestValidateCommitMessageText:
    def test_matches_file_based_result(self):
        text = f"fix({SID}):[KOS-2] correct thing"
        outcome = validate_commit_message_text(text)
        assert outcome.accepted


class TestCommitMsgHookCLI:
    """End-to-end subprocess tests -- the actual contract git invokes."""

    def _run(self, msg_text: str, enforce: bool, tmp_path) -> subprocess.CompletedProcess:
        msg_file = tmp_path / "COMMIT_EDITMSG"
        msg_file.write_text(msg_text)
        env = {"PATH": "/usr/bin:/bin"}
        if enforce:
            env["KOS_ENFORCE_COMMIT_RULE"] = "1"
        return subprocess.run(
            [sys.executable, str(HOOK), str(msg_file)],
            capture_output=True,
            text=True,
            env=env,
        )

    def test_valid_message_exits_zero_regardless_of_enforcement(self, tmp_path):
        for enforce in (False, True):
            result = self._run(f"feat({SID}): ok message", enforce, tmp_path)
            assert result.returncode == 0, result.stdout + result.stderr
            assert "CommitMessageConforms: TRUE" in result.stdout

    def test_invalid_message_advisory_by_default_exits_zero(self, tmp_path):
        result = self._run("not a valid subject", False, tmp_path)
        assert result.returncode == 0
        assert "CommitMessageConforms: FALSE" in result.stdout
        assert "ADVISORY ONLY" in result.stderr

    def test_invalid_message_blocked_when_enforced(self, tmp_path):
        result = self._run("not a valid subject", True, tmp_path)
        assert result.returncode == 1
        assert "BLOCKED" in result.stderr

    def test_missing_argument_exits_two(self, tmp_path):
        result = subprocess.run(
            [sys.executable, str(HOOK)], capture_output=True, text=True
        )
        assert result.returncode == 2


if __name__ == "__main__":
    raise SystemExit(pytest.main([__file__, "-v"]))
