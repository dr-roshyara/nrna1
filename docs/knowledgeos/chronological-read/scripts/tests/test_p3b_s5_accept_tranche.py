"""Tests (EG-9, G-LOG-0106 part (g)): H-06 acceptance of a revision-7 batch bound to a committed, sha-anchored tranche
file (`check_tranche` in scripts/p3b_s5_accept.py; audit-p3b/eg9/EG9-SPEC-A-v2.md §1 and v3 §1).

Checks: (a) the tranche file is tracked in git and unmodified; (b′) the `## <reference> —` heading occurs exactly once in
the governance log; (b) that section has exactly one `H06-TRANCHE <path> sha256 <h>` line matching the file; (c) the
tranche's reference / decider equal the arguments and the decider passes the human-only check; (d) the batch is listed
exactly once with its current run id and attempt; (e) the listed verify / audit report shas equal the live VERIFIED /
AUDITED evidence; (f) the listed outcome equals --outcome. The record captures tranche_path, tranche_sha256,
glog_section_sha256, glog_commit, tranche_commit (the log must be committed and clean). Revision < 7 is unchanged.
Tests T-01…T-17 (T-16 via the real EG-6 `new_attempt`), E9-08, E9-09, and the R-S4 M2 allowlist gate on --tranche /
--glog. Every git operation runs in a temporary
repository created under /tmp/s5ops-build/; the real repository's git state is never touched.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_accept_tranche
"""
import hashlib
import io
import json
import os
import subprocess
import unittest
from contextlib import redirect_stderr, redirect_stdout

import p3b_s5_ops_testlib as T
from test_p3b_s5_ops_state import fresh, walk
from test_p3b_s5_state_r7_evidence import r7_state
from test_p3b_s5_audit_record_r7 import assert_no_new_attempt

stm = T.ops.load_script("p3b_s5_state")
acc = T.ops.load_script("p3b_s5_accept")
c = T.c
HUMAN = "Dr. N. R. Roshyara"
REF = "G-LOG-0200"
TPATH = "audit-p3b/H06-TRANCHE-0001.json"
HISTORICAL_KEYS = {"artifact", "utc", "batch_id", "run_id", "outcome", "decision_text", "decided_by", "reference", "h06",
                   "state_entry_sha256", "seal_id", "recorder", "recorder_version"}
TRANCHE_KEYS = {"tranche_path", "tranche_sha256", "glog_section_sha256", "glog_commit", "tranche_commit"}


def git(root, *a):
    r = subprocess.run(["git", "-C", root, "-c", "user.name=Fixture", "-c", "user.email=f@example.invalid",
                        "-c", "commit.gpgsign=false", "-c", "core.hooksPath=/dev/null", *a],
                       capture_output=True, text=True)
    if r.returncode:
        raise AssertionError(r.stderr)
    return r.stdout.strip()


def sha(p):
    return hashlib.sha256(open(p, "rb").read()).hexdigest()


def to_audited(st_path, d, batch):
    st = stm.load(st_path)
    run = f"{batch}-R7"
    stm.transition(st, batch, "DISPATCHED")
    stm.transition(st, batch, "PROPOSED", reason="assembly + freeze-final complete")
    stm.transition(st, batch, "VERIFIED", evidence=T.write_report(os.path.join(d, "reports"), f"S5-VERIFY-{run}.json",
                                                                   batch, run, result="BATCH-PASS"))
    stm.transition(st, batch, "AUDITED", evidence=T.write_report(os.path.join(d, "reports"), f"AUDITED-{run}.json",
                                                                  batch, run, result="PASS"))
    stm.save(st, st_path)


def live_shas(st_path, batch):
    st = stm.load(st_path)
    ents = stm.run_history(st, batch, st["batches"][batch]["run_id"])
    return {e["to"]: e["evidence"]["sha256"] for e in ents if e.get("evidence")}


def glog_text(ref=REF, lines=None, extra_sections=""):
    body = "\n".join(lines or [])
    return (f"# P3B governance log (synthetic fixture)\n\n## G-LOG-0199 — an earlier entry\n\n| Field | Value |\n|---|---|\n"
            f"| Decider | someone |\n\n## {ref} — Human act: H-06 tranche 0001\n\n| Field | Value |\n|---|---|\n"
            f"| Decider | {HUMAN} |\n\n{body}\n\n{extra_sections}")


class TrancheFixture(unittest.TestCase):
    BATCHES = ("OB0004", "OB0005")

    def setUp(self):
        self.d, self.st = r7_state()
        for b in self.BATCHES:
            to_audited(self.st, self.d, b)
        self.out = os.path.join(self.d, "audit-p3b", "S5-ACCEPTANCE.jsonl")
        self.glog = os.path.join(self.d, "P3B-GOVERNANCE-LOG.md")
        self.tranche = os.path.join(self.d, TPATH)
        git(self.d, "init", "-q")
        self.commit_tranche(self.default_tranche())

    def entry(self, batch, outcome="ACCEPTED", **kw):
        s = live_shas(self.st, batch)
        e = {"batch_id": batch, "run_id": f"{batch}-R7", "attempt": 1, "verify_report_sha256": s["VERIFIED"],
             "audit_report_sha256": s["AUDITED"], "outcome": outcome, "exceptions": []}
        e.update(kw)
        return e

    def default_tranche(self, **kw):
        t = {"schema": "H06-TRANCHE v1", "tranche_id": "0001", "reference": REF, "decided_by": HUMAN,
             "batches": [self.entry(b) for b in self.BATCHES]}
        t.update(kw)
        return t

    def write_tranche(self, t, path=None):
        p = path or self.tranche
        os.makedirs(os.path.dirname(p), exist_ok=True)
        with open(p, "w", encoding="utf-8") as f:
            f.write(c.canon(t) + "\n")
        return p

    def commit_tranche(self, t, glog_lines=None, ref=REF, extra_sections="", path=None, rel=TPATH, add_tranche=True):
        p = self.write_tranche(t, path)
        lines = glog_lines if glog_lines is not None else [f"H06-TRANCHE {rel} sha256 {sha(p)}"]
        with open(self.glog, "w", encoding="utf-8") as f:
            f.write(glog_text(ref, lines, extra_sections))
        git(self.d, "add", "P3B-GOVERNANCE-LOG.md", *([p] if add_tranche else []))
        git(self.d, "commit", "-q", "-m", "fixture")

    def rec(self, batch="OB0004", outcome="ACCEPTED", decided_by=HUMAN, ref=REF, tranche="default"):
        return acc.record(batch, outcome, "accepted as listed in the tranche", decided_by, ref, self.st, self.out,
                          tranche=self.tranche if tranche == "default" else tranche, gl_path=self.glog)

    def snapshot(self):
        return T.read(self.st), (T.read(self.out) if os.path.exists(self.out) else None)

    def assertRefused(self, **kw):                     # T-13: every refusal leaves state and acceptance log identical
        before = self.snapshot()
        with self.assertRaises((c.S5Error, acc.c.S5Error)):
            self.rec(**kw)
        self.assertEqual(self.snapshot(), before)


class T01Valid(TrancheFixture):
    def test_recorded_and_accepted(self):
        r = self.rec()
        self.assertEqual(stm.load(self.st)["batches"]["OB0004"]["state"], "ACCEPTED")
        self.assertEqual(set(r), HISTORICAL_KEYS | TRANCHE_KEYS)
        self.assertEqual(r["tranche_sha256"], sha(self.tranche))
        self.assertEqual(r["tranche_path"], TPATH)
        self.assertEqual(T.read_jsonl(self.out), [r])

    def test_cli_wiring(self):
        with redirect_stdout(io.StringIO()), redirect_stderr(io.StringIO()):
            rc = acc.main(["--batch", "OB0005", "--outcome", "ACCEPTED", "--decision", "as listed", "--decided-by", HUMAN,
                           "--reference", REF, "--state", self.st, "--out", self.out, "--tranche", self.tranche,
                           "--glog", self.glog])
        self.assertEqual(rc, 0)
        self.assertEqual(T.read_jsonl(self.out)[0]["tranche_sha256"], sha(self.tranche))


class T02RequiredAtR7(TrancheFixture):
    def test_missing_tranche_refused(self):
        self.assertRefused(tranche=None)
        with redirect_stdout(io.StringIO()), redirect_stderr(io.StringIO()):
            rc = acc.main(["--batch", "OB0004", "--outcome", "ACCEPTED", "--decision", "x", "--decided-by", HUMAN,
                           "--reference", REF, "--state", self.st, "--out", self.out, "--glog", self.glog])
        self.assertEqual(rc, 1)
        self.assertFalse(os.path.exists(self.out))


class T03T04Listing(TrancheFixture):
    def test_batch_not_listed(self):
        self.commit_tranche(self.default_tranche(batches=[self.entry("OB0005")]))
        self.assertRefused()

    def test_batch_listed_twice(self):
        self.commit_tranche(self.default_tranche(batches=[self.entry("OB0004"), self.entry("OB0004"), self.entry("OB0005")]))
        self.assertRefused()


class T05RunAndAttempt(TrancheFixture):
    def test_wrong_run_id(self):
        self.commit_tranche(self.default_tranche(batches=[self.entry("OB0004", run_id="OB0004-R2")]))
        self.assertRefused()

    def test_wrong_attempt(self):
        self.commit_tranche(self.default_tranche(batches=[self.entry("OB0004", attempt=2)]))
        self.assertRefused()


class T06EvidenceShas(TrancheFixture):
    def test_verify_or_audit_sha_differs(self):
        for k in ("verify_report_sha256", "audit_report_sha256"):
            self.commit_tranche(self.default_tranche(batches=[self.entry("OB0004", **{k: "0" * 64})]))
            self.assertRefused()
        swapped = self.entry("OB0004")
        swapped["verify_report_sha256"], swapped["audit_report_sha256"] = \
            swapped["audit_report_sha256"], swapped["verify_report_sha256"]
        self.commit_tranche(self.default_tranche(batches=[swapped]))
        self.assertRefused()


class T07TrackedUnmodified(TrancheFixture):
    def test_edited_after_commit(self):
        with open(self.tranche, "a") as f:
            f.write("\n")
        self.assertRefused()

    def test_edited_and_log_line_matching_but_uncommitted_tranche(self):
        t = self.default_tranche(tranche_id="0002")
        self.write_tranche(t)                          # log committed with the old sha; file edited, not committed
        self.assertRefused()

    def test_untracked(self):
        p = os.path.join(self.d, "audit-p3b", "H06-TRANCHE-0009.json")
        self.commit_tranche(self.default_tranche(), path=p, rel="audit-p3b/H06-TRANCHE-0009.json", add_tranche=False)
        self.assertRefused(tranche=p)

    def test_staged_but_not_committed(self):
        p = os.path.join(self.d, "audit-p3b", "H06-TRANCHE-0008.json")
        self.commit_tranche(self.default_tranche(), path=p, rel="audit-p3b/H06-TRANCHE-0008.json", add_tranche=False)
        git(self.d, "add", p)
        self.assertRefused(tranche=p)


class T08GovernanceLine(TrancheFixture):
    def test_line_missing(self):
        self.commit_tranche(self.default_tranche(), glog_lines=["no anchor here"])
        self.assertRefused()

    def test_line_names_another_sha(self):
        self.commit_tranche(self.default_tranche(), glog_lines=[f"H06-TRANCHE {TPATH} sha256 {'1' * 64}"])
        self.assertRefused()

    def test_line_names_another_path(self):
        p = self.write_tranche(self.default_tranche())
        self.commit_tranche(self.default_tranche(), glog_lines=[f"H06-TRANCHE audit-p3b/H06-TRANCHE-0002.json sha256 {sha(p)}"])
        self.assertRefused()

    def test_two_lines(self):
        p = self.write_tranche(self.default_tranche())
        line = f"H06-TRANCHE {TPATH} sha256 {sha(p)}"
        self.commit_tranche(self.default_tranche(), glog_lines=[line, line])
        self.assertRefused()
        self.commit_tranche(self.default_tranche(), glog_lines=[line, f"| Tranche | {line} |"])
        self.assertRefused()

    def test_line_only_in_another_section(self):
        p = self.write_tranche(self.default_tranche())
        other = f"## G-LOG-0201 — later entry\n\nH06-TRANCHE {TPATH} sha256 {sha(p)}\n"
        self.commit_tranche(self.default_tranche(), glog_lines=["no anchor"], extra_sections=other)
        self.assertRefused()


class T09ReferenceAndDecider(TrancheFixture):
    def test_reference_mismatch(self):
        self.commit_tranche(self.default_tranche(reference="G-LOG-0201"))
        self.assertRefused()
        self.commit_tranche(self.default_tranche(), ref="G-LOG-0201")
        self.assertRefused()                           # the heading for --reference G-LOG-0200 is absent too

    def test_decider_mismatch(self):
        self.commit_tranche(self.default_tranche(decided_by="Another Human"))
        self.assertRefused()

    def test_tranche_decider_non_human(self):
        self.commit_tranche(self.default_tranche(decided_by="Claude orchestrator"))
        before = self.snapshot()
        with self.assertRaisesRegex((c.S5Error, acc.c.S5Error), "human"):
            self.rec()
        self.assertEqual(self.snapshot(), before)


class T10Outcome(TrancheFixture):
    def test_outcome_mismatch_both_ways(self):
        self.commit_tranche(self.default_tranche(batches=[self.entry("OB0004", outcome="NOT-ACCEPTED"),
                                                          self.entry("OB0005")]))
        self.assertRefused(batch="OB0004", outcome="ACCEPTED")
        self.assertRefused(batch="OB0005", outcome="NOT-ACCEPTED")


class T11NotAccepted(TrancheFixture):
    def test_not_accepted_fails_class_h(self):
        self.commit_tranche(self.default_tranche(batches=[self.entry("OB0004", outcome="NOT-ACCEPTED"),
                                                          self.entry("OB0005")]))
        r = self.rec(outcome="NOT-ACCEPTED")
        self.assertEqual(r["outcome"], "NOT-ACCEPTED")
        st = stm.load(self.st)
        self.assertEqual(st["batches"]["OB0004"]["state"], "FAILED")
        self.assertEqual(st["history"][-1]["failure_class"], "H")     # EG-6: an H-06 rejection is class H
        with self.assertRaises((c.S5Error, acc.c.S5Error)):
            stm.new_run(st, "OB0004", "re-run")
        assert_no_new_attempt(self, st, "OB0004")


class T12RevisionBelow7(unittest.TestCase):
    def test_r2_without_tranche_unchanged(self):
        d, man, st = fresh()
        out = os.path.join(d, "S5-ACCEPTANCE.jsonl")
        walk(st, "OB0004", ["DISPATCHED", "PROPOSED", "VERIFIED", "AUDITED"])
        r = acc.record("OB0004", "ACCEPTED", "accepted as verified", HUMAN, "G-LOG-9999", st, out)
        self.assertEqual(set(r), HISTORICAL_KEYS)
        self.assertEqual(stm.load(st)["batches"]["OB0004"]["state"], "ACCEPTED")
        self.assertEqual(T.read_jsonl(out), [r])
        with redirect_stdout(io.StringIO()), redirect_stderr(io.StringIO()):
            walk(st, "OB0005", ["DISPATCHED", "PROPOSED", "VERIFIED", "AUDITED"])
            rc = acc.main(["--batch", "OB0005", "--outcome", "NOT-ACCEPTED", "--decision", "no", "--decided-by", HUMAN,
                           "--reference", "G-LOG-9999", "--state", st, "--out", out])
        self.assertEqual(rc, 0)
        self.assertEqual(set(T.read_jsonl(out)[1]), HISTORICAL_KEYS)


class T14HeadingUnique(TrancheFixture):
    def test_heading_absent(self):
        self.assertRefused(ref="G-LOG-0300")

    def test_heading_twice(self):
        dup = f"## {REF} — a duplicate later append\n\nnothing\n"
        self.commit_tranche(self.default_tranche(), extra_sections=dup)
        self.assertRefused()


class T15Capture(TrancheFixture):
    def test_capture_fields(self):
        r = self.rec()
        head = git(self.d, "rev-parse", "HEAD")
        self.assertEqual(r["glog_commit"], head)
        self.assertEqual(r["tranche_commit"], git(self.d, "log", "-1", "--format=%H", "--", TPATH))
        at_commit = subprocess.run(["git", "-C", self.d, "show", f"{r['glog_commit']}:P3B-GOVERNANCE-LOG.md"],
                                   capture_output=True, text=True, check=True).stdout
        section = acc.glog_section(at_commit, REF)
        self.assertEqual(hashlib.sha256(section.encode("utf-8")).hexdigest(), r["glog_section_sha256"])
        self.assertTrue(section.startswith(f"## {REF} —"))
        self.assertNotIn("## G-LOG-0199", section)
        # a later append of another entry leaves the captured section sha comparable
        with open(self.glog, "a") as f:
            f.write("## G-LOG-0201 — later\n\ntext\n")
        self.assertEqual(hashlib.sha256(acc.glog_section(T.read(self.glog), REF).encode()).hexdigest(),
                         r["glog_section_sha256"])


class T17LogClean(TrancheFixture):
    def test_log_modified_uncommitted(self):
        with open(self.glog, "a") as f:
            f.write("\nuncommitted note\n")
        self.assertRefused()

    def test_log_untracked(self):
        git(self.d, "rm", "-q", "--cached", "P3B-GOVERNANCE-LOG.md")
        git(self.d, "commit", "-q", "-m", "untrack")
        self.assertRefused()


class M2AllowlistGate(TrancheFixture):
    """R-S4 M2: --tranche and --glog go through ops.check_paths (plan §M item 6) like every other S5 input."""

    def test_allowlisted_tranche_and_log_names_pass_the_gate(self):
        for p in ("audit-p3b/H06-TRANCHE-0001.json", "audit-p3b/H06-TRANCHE-0080.json", "P3B-GOVERNANCE-LOG.md"):
            self.assertTrue(T.ops.check_paths([p]), p)
        # an allowlisted name that does not exist is refused later, by existence, not by the gate
        with self.assertRaisesRegex((c.S5Error, acc.c.S5Error), "does not exist"):
            self.rec(tranche=os.path.join(c.CR, "audit-p3b", "H06-TRANCHE-9999.json"))

    def test_non_allowlisted_tranche_refused(self):
        for p in ("audit-p3b/H06-TRANCHE-1.json", "audit-p3b/H06-TRANCHE-0001.jsonl", "audit-p3b/OTHER-0001.json",
                  "H06-TRANCHE-0001.json"):
            before = self.snapshot()
            with self.assertRaisesRegex((c.S5Error, acc.c.S5Error), "allowlist"):
                self.rec(tranche=os.path.join(c.CR, p))
            self.assertEqual(self.snapshot(), before)

    def test_non_allowlisted_glog_refused(self):
        before = self.snapshot()
        with self.assertRaisesRegex((c.S5Error, acc.c.S5Error), "allowlist"):
            acc.record("OB0004", "ACCEPTED", "x", HUMAN, REF, self.st, self.out, tranche=self.tranche,
                       gl_path=os.path.join(c.CR, "OTHER-GOVERNANCE-LOG.md"))
        self.assertEqual(self.snapshot(), before)

    def test_forbidden_name_outside_the_repository_refused(self):
        p = os.path.join(self.d, "P3B-TIERS.jsonl")               # a forbidden census name in a scratch copy
        with open(p, "w") as f:
            f.write("{}\n")
        self.assertRefused(tranche=p)


def retirement(d, batch, m):
    """A synthetic COMPLETE retirement record pair in <d>/ledger-p3b-r2/<B>-R7.A<m>/ (the shape state.check_retirement
    reads; the EG-6 retire tool is out of this slice)."""
    tgt = os.path.join(d, "ledger-p3b-r2", f"{batch}-R7.A{m}")
    os.makedirs(tgt, exist_ok=True)
    ip = os.path.join(tgt, "RETIREMENT-INTENT.json")
    with open(ip, "w", encoding="utf-8") as f:
        json.dump({"batch": batch, "attempt": m, "state": "PENDING", "ledger": {}, "archive": {}}, f)
    cp = os.path.join(tgt, "RETIREMENT-COMPLETE.json")
    with open(cp, "w", encoding="utf-8") as f:
        json.dump({"batch": batch, "attempt": m, "state": "COMPLETE", "intent_sha256": sha(ip), "verified_files": 0}, f)
    return {"path": cp, "sha256": sha(cp)}


class T16AttemptRebinding(TrancheFixture):
    """T-16 (EG-6 part (d) landed): attempt 2 of OB0004 via the real `new_attempt`; the tranche must list attempt 2."""
    BATCHES = ("OB0005",)

    def setUp(self):
        super().setUp()
        st = stm.load(self.st)
        stm.transition(st, "OB0004", "DISPATCHED")
        stm.transition(st, "OB0004", "PROPOSED")
        stm.transition(st, "OB0004", "FAILED", reason="BATCH-FAIL", failure_class="X", failure_signature="a" * 64)
        stm.new_attempt(st, "OB0004", "X", retirement(self.d, "OB0004", 1), may_start=lambda b: (True, "ok"))
        stm.transition(st, "OB0004", "DISPATCHED")
        stm.transition(st, "OB0004", "PROPOSED")
        stm.transition(st, "OB0004", "VERIFIED", evidence=T.write_report(
            os.path.join(self.d, "reports"), "S5-VERIFY-OB0004-R7.A2.json", "OB0004", "OB0004-R7", result="BATCH-PASS"))
        stm.transition(st, "OB0004", "AUDITED", evidence=T.write_report(
            os.path.join(self.d, "reports"), "AUDITED-OB0004-R7.A2.json", "OB0004", "OB0004-R7", result="PASS"))
        stm.save(st, self.st)
        self.assertEqual(stm.current_attempt(stm.load(self.st), "OB0004"), 2)

    def test_listing_attempt_1_refused(self):
        self.commit_tranche(self.default_tranche(batches=[self.entry("OB0004", attempt=1), self.entry("OB0005")]))
        before = self.snapshot()
        with self.assertRaisesRegex((c.S5Error, acc.c.S5Error), "attempt 1, not the current OB0004-R7 attempt 2"):
            self.rec()
        self.assertEqual(self.snapshot(), before)

    def test_listing_attempt_2_accepted(self):
        self.commit_tranche(self.default_tranche(batches=[self.entry("OB0004", attempt=2), self.entry("OB0005")]))
        r = self.rec()
        self.assertEqual(stm.load(self.st)["batches"]["OB0004"]["state"], "ACCEPTED")
        self.assertEqual(r["tranche_sha256"], sha(self.tranche))


class E908E909AcceptR7(TrancheFixture):
    def test_refused_at_verified(self):
        d, st_path = r7_state()
        st = stm.load(st_path)
        stm.transition(st, "OB0006", "DISPATCHED")
        stm.transition(st, "OB0006", "PROPOSED")
        stm.transition(st, "OB0006", "VERIFIED", evidence=T.write_report(os.path.join(d, "r"), "v.json", "OB0006",
                                                                          "OB0006-R7", result="BATCH-PASS"))
        stm.save(st, st_path)
        with self.assertRaises((c.S5Error, acc.c.S5Error)):
            acc.record("OB0006", "ACCEPTED", "x", HUMAN, REF, st_path, os.path.join(d, "A.jsonl"),
                       tranche=self.tranche, gl_path=self.glog)
        self.assertFalse(os.path.exists(os.path.join(d, "A.jsonl")))

    def test_one_reference_two_batches_two_records_once_each(self):
        r1, r2 = self.rec("OB0004"), self.rec("OB0005")
        self.assertEqual((r1["reference"], r2["reference"]), (REF, REF))
        self.assertEqual([r["batch_id"] for r in T.read_jsonl(self.out)], ["OB0004", "OB0005"])
        st = stm.load(self.st)
        self.assertEqual({st["batches"][b]["state"] for b in self.BATCHES}, {"ACCEPTED"})
        self.assertRefused(batch="OB0004")             # the same (batch, run, attempt) decided twice


if __name__ == "__main__":
    unittest.main()
