"""TDD suite for the commit governance rule.

A valid test session id is a fixture UUID (11111111-1111-1111-1111-111111111111),
never this actual session's real CLAUDE_CODE_SESSION_ID -- tests should not
embed a real identifier into committed source.
"""
import sys
from pathlib import Path

import pytest

sys.path.insert(0, str(Path(__file__).resolve().parents[3]))

from knowledgeos.governance.commit_rule.domain.governance_rule import evaluate
from knowledgeos.governance.commit_rule.adapters.claude.session_id_provider import (
    ENV_VAR_NAME,
    get_session_id,
)

SID = "11111111-1111-1111-1111-111111111111"


class TestValidExamples:
    def test_feat_with_ticket(self):
        r = evaluate(f"feat({SID}):[KOS-742] implement evidence resolver")
        assert r.conforms, r.explain()
        assert r.parsed.commit_type == "feat"
        assert r.parsed.ticket == "KOS-742"

    def test_fix_with_ticket(self):
        r = evaluate(f"fix({SID}):[KOS-742] correct temporal validation")
        assert r.conforms, r.explain()

    def test_refactor_without_ticket(self):
        r = evaluate(f"refactor({SID}): simplify evidence projection")
        assert r.conforms, r.explain()
        assert r.parsed.ticket is None

    def test_research_without_ticket(self):
        r = evaluate(f"research({SID}): investigate evidence relation")
        assert r.conforms, r.explain()

    def test_pbdigit_style_ticket(self):
        # this repo's own real ticket shape (CLAUDE.md), not Jira -- see
        # domain/commit_message.py module docstring for the disclosed
        # substitution.
        r = evaluate(f"feat({SID}):[PBDIGIT-42] add newsletter opt-out")
        assert r.conforms, r.explain()

    def test_unicode_description(self):
        r = evaluate(f"docs({SID}): korrigiert Übersetzung für Wähler")
        assert r.conforms, r.explain()


class TestInvalidExamples:
    def test_missing_session(self):
        r = evaluate("feat: implement resolver")
        assert not r.conforms

    def test_empty_parens(self):
        r = evaluate("feat(): implement resolver")
        assert not r.conforms

    def test_no_colon_no_description(self):
        r = evaluate(f"feat({SID})")
        assert not r.conforms

    def test_colon_no_description(self):
        r = evaluate(f"feat({SID}):")
        assert not r.conforms

    def test_missing_type(self):
        r = evaluate(f"({SID}): implement resolver")
        assert not r.conforms

    def test_illustrative_session123_is_invalid(self):
        # The task prompt's own illustrative examples use "session123" as a
        # placeholder. The REAL, observed session-id shape in this
        # environment is a 36-char UUID (CLAUDE_CODE_SESSION_ID) -- so
        # "session123" is correctly rejected as a malformed session id, not
        # silently accepted because the prompt used it as an example.
        r = evaluate("feat(session123): implement resolver")
        assert not r.conforms
        assert any(v.code == "MALFORMED" for v in r.violations)


class TestMalformedTicket:
    def test_ticket_missing_closing_bracket(self):
        r = evaluate(f"feat({SID}):[KOS-742 implement resolver")
        assert not r.conforms

    def test_ticket_lowercase_prefix(self):
        r = evaluate(f"feat({SID}):[kos-742] implement resolver")
        assert not r.conforms

    def test_ticket_empty_brackets(self):
        r = evaluate(f"feat({SID}):[] implement resolver")
        assert not r.conforms


class TestEmptyAndWhitespaceDescription:
    def test_empty_description(self):
        r = evaluate(f"feat({SID}): ")
        assert not r.conforms

    def test_whitespace_only_description(self):
        r = evaluate(f"feat({SID}):    ")
        assert not r.conforms

    def test_leading_whitespace_in_description(self):
        # regex requires exactly one space separator; extra leading space in
        # the description group itself should be rejected.
        r = evaluate(f"feat({SID}):  double space before description")
        assert not r.conforms


class TestNewlineInjection:
    def test_subject_with_embedded_newline_rejected(self):
        r = evaluate(f"feat({SID}): description\nmalicious second line")
        assert not r.conforms
        assert any(v.code == "MALFORMED" for v in r.violations)

    def test_subject_with_embedded_carriage_return_rejected(self):
        r = evaluate(f"feat({SID}): description\rinjected")
        assert not r.conforms


class TestSessionIdBoundaries:
    def test_session_id_too_short(self):
        r = evaluate("feat(1111111-1111-1111-1111-111111111111): description")
        assert not r.conforms

    def test_session_id_wrong_hyphenation(self):
        r = evaluate("feat(111111111111-1111-1111-1111111111111): description")
        assert not r.conforms

    def test_session_id_uppercase_rejected(self):
        # observed CLAUDE_CODE_SESSION_ID is lowercase; uppercase hex is not
        # the observed shape and is rejected rather than silently normalized.
        r = evaluate("feat(11111111-1111-1111-1111-111111111111".upper() + "): description")
        assert not r.conforms

    def test_session_id_non_hex_rejected(self):
        r = evaluate("feat(gggggggg-1111-1111-1111-111111111111): description")
        assert not r.conforms


class TestExcessivelyLongValues:
    def test_description_over_max_length_rejected(self):
        long_desc = "a" * 300
        r = evaluate(f"feat({SID}): {long_desc}")
        assert not r.conforms
        assert any(v.code == "DESCRIPTION_TOO_LONG" for v in r.violations)

    def test_description_at_max_length_accepted(self):
        desc = "a" * 200
        r = evaluate(f"feat({SID}): {desc}")
        assert r.conforms, r.explain()


class TestUnknownCommitType:
    def test_unrecognized_type_rejected(self):
        r = evaluate(f"wibble({SID}): does something")
        assert not r.conforms
        assert any(v.code == "UNKNOWN_TYPE" for v in r.violations)

    def test_all_derived_real_types_accepted(self):
        # every type in TYPES is derived from this repo's own real commit
        # history (git log --format=%s), not invented.
        from knowledgeos.governance.commit_rule.domain.commit_message import TYPES

        for t in TYPES:
            r = evaluate(f"{t}({SID}): example description")
            assert r.conforms, f"{t} unexpectedly rejected: {r.explain()}"


class TestEpistemicBoundary:
    def test_conforming_result_carries_no_authorization_claim(self):
        """The single most important test in this suite: a conforming Result
        must never be mistaken for CommitAuthorized. This test doesn't check
        code behavior so much as document, in an executable form, the
        distinction the domain/governance_rule.py module docstring requires.
        """
        r = evaluate(f"feat({SID}):[KOS-742] implement evidence resolver")
        assert r.conforms
        # Result has exactly one boolean claim -- 'conforms'. It has no
        # 'authorized' field, no 'attested' field, no 'work_authorized'
        # field. This assertion fails loudly if anyone ever adds one without
        # also updating the module docstring's epistemic-boundary warning.
        assert set(vars(r).keys()) == {"conforms", "violations", "parsed"}


class TestSessionIdProvider:
    def test_returns_none_when_env_var_absent(self, monkeypatch):
        monkeypatch.delenv(ENV_VAR_NAME, raising=False)
        assert get_session_id() is None

    def test_returns_value_when_present(self, monkeypatch):
        monkeypatch.setenv(ENV_VAR_NAME, SID)
        assert get_session_id() == SID

    def test_does_not_fabricate_when_empty_string(self, monkeypatch):
        monkeypatch.setenv(ENV_VAR_NAME, "")
        assert get_session_id() is None


if __name__ == "__main__":
    raise SystemExit(pytest.main([__file__, "-v"]))
