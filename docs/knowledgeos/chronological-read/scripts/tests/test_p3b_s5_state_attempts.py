"""Tests (EG-6 STATE slice, G-LOG-0106; v2.8 package §14 part (d); EG6-SPEC-A-v2 §1/§3/§4, -v3 §2):
attempt-aware history, `new_attempt`, attempt-aware evidence, failure classes, RR-7 and the W-SYS watchdog in
scripts/p3b_s5_state.py.

Synthetic only: temp state files built from fixture manifests, synthetic reports and synthetic retirement records
under /tmp/s5ops-build/. No production state, manifest, ledger, archive or hold-out artifact is read or written.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_state_attempts
"""
import hashlib
import io
import json
import os
import unittest
from contextlib import redirect_stderr, redirect_stdout

import p3b_s5_ops_testlib as T
from test_p3b_s5_ops_state import rev_manifest

stm = T.ops.load_script("p3b_s5_state")
c = T.c
BIDS = ["OB0004", "OB0005", "OB0006"]
SIG1, SIG2, SIG3 = "a" * 64, "b" * 64, "c" * 64
OK = lambda b: (True, "ok")                                     # noqa: E731  (a satisfied may_start_attempt guard)


def r7_state(bids=BIDS):
    """A synthetic state at revision 7 (init from an R2 fixture manifest, then the governed R2→R7 transition)."""
    d = T.tmpdir("eg6st")
    prev, new, st_path = os.path.join(d, "prev.jsonl"), os.path.join(d, "new.jsonl"), os.path.join(d, "P3B-STATE.json")
    rev_manifest(prev, bids)
    rev_manifest(new, bids, "-R7")
    stm.init(prev, st_path)
    st = stm.load(st_path)
    stm.revision_transition(st, new, prev, "EG-6 test")
    stm.save(st, st_path)
    return d, st_path, st


def sha(p):
    with open(p, "rb") as f:
        return hashlib.sha256(f.read()).hexdigest()


def retirement(d, batch, m, **over):
    """A synthetic COMPLETE retirement record pair in <d>/ledger-p3b-r2/<B>-R7.A<m>/ (the retire tool's S1/S6 shape)."""
    tgt = os.path.join(d, "ledger-p3b-r2", f"{batch}-R7.A{m}")
    os.makedirs(tgt, exist_ok=True)
    ip = os.path.join(tgt, "RETIREMENT-INTENT.json")
    with open(ip, "w", encoding="utf-8") as f:
        json.dump({"batch": batch, "attempt": m, "state": "PENDING", "ledger": {}, "archive": {}}, f)
    rec = {"batch": batch, "attempt": m, "state": "COMPLETE", "intent_sha256": sha(ip), "verified_files": 0}
    rec.update(over)
    cp = os.path.join(tgt, "RETIREMENT-COMPLETE.json")
    with open(cp, "w", encoding="utf-8") as f:
        json.dump(rec, f)
    return {"path": cp, "sha256": sha(cp)}


def report(d, batch, name, result="BATCH-PASS", **kw):
    return T.write_report(os.path.join(d, "reports"), name, batch, f"{batch}-R7", result=result, **kw)


def fail(st, batch, cls="X", sig=SIG1, at="PROPOSED"):
    """Walk the current attempt PREPARED → … → FAILED with a recorded class and signature."""
    path = {"DISPATCHED": ["DISPATCHED"], "PROPOSED": ["DISPATCHED", "PROPOSED"]}[at]
    for s in path:
        stm.transition(st, batch, s)
    stm.transition(st, batch, "FAILED", reason="BATCH-FAIL", failure_class=cls, failure_signature=sig)


def incomplete(st, batch, cls=None):
    stm.transition(st, batch, "DISPATCHED")
    stm.transition(st, batch, "INCOMPLETE", reason="interrupted", failure_class=cls)


def advance(st, d, batch, cls="X", sig=SIG1):
    """Fail the current attempt and open the next one (sequential)."""
    m = stm.current_attempt(st, batch)
    fail(st, batch, cls, sig)
    stm.new_attempt(st, batch, cls, retirement(d, batch, m), may_start=OK)
    return m + 1


class FailureClassOnTransition(unittest.TestCase):

    def setUp(self):
        self.d, self.st_path, self.st = r7_state()

    def test_failed_r7_requires_a_valid_class(self):
        stm.transition(self.st, "OB0004", "DISPATCHED")
        stm.transition(self.st, "OB0004", "PROPOSED")
        for bad in (None, "", "Z", "x", "A3"):
            with self.assertRaises(stm.StateError, msg=bad):
                stm.transition(self.st, "OB0004", "FAILED", reason="r", failure_class=bad, failure_signature=SIG1)
        self.assertEqual(self.st["batches"]["OB0004"]["state"], "PROPOSED")
        stm.transition(self.st, "OB0004", "FAILED", reason="r", failure_class="A2", failure_signature=SIG1)
        e = self.st["history"][-1]
        self.assertEqual((e["failure_class"], e["failure_signature"], e["attempt"]), ("A2", SIG1, 1))

    def test_every_class_is_recordable(self):
        for cls in sorted(set(stm.FAILURE_CLASSES) - {"H"}):                 # H is post-VERIFIED only
            d, p, st = r7_state()
            fail(st, "OB0005", cls, SIG1)
            self.assertEqual(st["history"][-1]["failure_class"], cls)
        with self.assertRaises(stm.StateError):
            fail(st, "OB0006", "H", SIG1)                                       # no audit/acceptance before VERIFIED

    def test_rerunnable_failed_requires_a_hex_signature(self):
        stm.transition(self.st, "OB0004", "DISPATCHED")
        for bad in (None, "", "abc", "G" * 64, "A" * 64):
            with self.assertRaises(stm.StateError, msg=bad):
                stm.transition(self.st, "OB0004", "FAILED", reason="r", failure_class="X", failure_signature=bad)
        self.assertEqual(self.st["batches"]["OB0004"]["state"], "DISPATCHED")

    def test_incomplete_defaults_to_a1_the_mechanical_row(self):
        incomplete(self.st, "OB0004")
        self.assertEqual(self.st["history"][-1]["failure_class"], "A1")
        with self.assertRaises(stm.StateError):
            incomplete(self.st, "OB0005", cls="Q")

    def test_incomplete_always_carries_a_signature(self):
        """RR-7 on INCOMPLETE (revision round item 3): a supplied signature must be hex64; absent, it is derived
        mechanically from the recorded cause, so an R7 INCOMPLETE never lacks one."""
        incomplete(self.st, "OB0004")
        e = self.st["history"][-1]
        self.assertEqual(e["failure_signature"], stm.incomplete_signature("interrupted"))
        self.assertRegex(e["failure_signature"], r"^[0-9a-f]{64}$")
        stm.transition(self.st, "OB0005", "DISPATCHED")
        for bad in ("", "abc", "A" * 64):
            with self.assertRaises(stm.StateError, msg=bad):
                stm.transition(self.st, "OB0005", "INCOMPLETE", reason="r", failure_signature=bad)
        stm.transition(self.st, "OB0005", "INCOMPLETE", reason="r", failure_signature=SIG2)
        self.assertEqual(self.st["history"][-1]["failure_signature"], SIG2)

    def test_two_identical_incompletes_are_rr7_class_d(self):
        incomplete(self.st, "OB0004")
        stm.new_attempt(self.st, "OB0004", "A1", retirement(self.d, "OB0004", 1), may_start=OK)
        incomplete(self.st, "OB0004")                                          # the same cause again
        self.assertIn("class D", stm.rr7_reason(self.st, "OB0004"))
        with self.assertRaisesRegex(stm.StateError, "RR-7"):
            stm.new_attempt(self.st, "OB0004", "A1", retirement(self.d, "OB0004", 2), may_start=OK)
        d, p, st = r7_state()                                                  # different causes: allowed
        incomplete(st, "OB0004")
        stm.new_attempt(st, "OB0004", "A1", retirement(d, "OB0004", 1), may_start=OK)
        stm.transition(st, "OB0004", "DISPATCHED")
        stm.transition(st, "OB0004", "INCOMPLETE", reason="session limit")
        self.assertIsNone(stm.rr7_reason(st, "OB0004"))

    def test_post_verified_failure_defaults_to_h(self):
        stm.transition(self.st, "OB0004", "DISPATCHED")
        stm.transition(self.st, "OB0004", "PROPOSED")
        stm.transition(self.st, "OB0004", "VERIFIED", evidence=report(self.d, "OB0004", "v.json"))
        with self.assertRaises(stm.StateError):             # a post-VERIFIED failure is never re-runnable (X/A1/A2)
            stm.transition(self.st, "OB0004", "FAILED", reason="audit FAIL", failure_class="X", failure_signature=SIG1)
        stm.transition(self.st, "OB0004", "FAILED", reason="audit FAIL")
        self.assertEqual(self.st["history"][-1]["failure_class"], "H")

    def test_class_is_refused_outside_r7_and_outside_failures(self):
        d = T.tmpdir("eg6r2")
        man, p = os.path.join(d, "m.jsonl"), os.path.join(d, "P3B-STATE.json")
        T.write_manifest(man, BIDS)
        st = stm.init(man, p)
        stm.transition(st, "OB0004", "DISPATCHED")
        with self.assertRaises(stm.StateError):
            stm.transition(st, "OB0004", "FAILED", reason="r", failure_class="X", failure_signature=SIG1)
        stm.transition(st, "OB0004", "FAILED", reason="r")                 # revision < 7 unchanged
        self.assertNotIn("failure_class", st["history"][-1])
        self.assertNotIn("attempt", st["history"][-1])
        with self.assertRaises(stm.StateError):
            stm.transition(self.st, "OB0005", "DISPATCHED", failure_class="X")


class NewAttempt(unittest.TestCase):

    def setUp(self):
        self.d, self.st_path, self.st = r7_state()

    def test_happy_path(self):
        fail(self.st, "OB0004", "A2", SIG1)
        n = len(self.st["history"])
        ref = retirement(self.d, "OB0004", 1)
        stm.new_attempt(self.st, "OB0004", "A2", ref, actor="orchestrator", may_start=OK)
        B = self.st["batches"]["OB0004"]
        self.assertEqual((B["state"], B["run_id"], B["revision"]), ("PREPARED", "OB0004-R7", 7))
        self.assertEqual(B["runs"][-1], {"run_id": "OB0004-R7", "state": "PREPARED", "revision": 7, "attempt": 2})
        self.assertEqual(B["runs"][-2]["state"], "FAILED")                     # attempt 1 stays recorded
        e = self.st["history"][-1]
        self.assertEqual(len(self.st["history"]), n + 1)
        self.assertEqual((e["run_kind"], e["run_id"], e["from"], e["to"], e["attempt"]),
                         ("NEW-ATTEMPT", "OB0004-R7", "FAILED", "PREPARED", 2))
        self.assertEqual(e["cause_class"], "A2")
        self.assertEqual(e["retirement"], {"path": T.ops.display(ref["path"], 1), "sha256": ref["sha256"]})
        self.assertEqual(stm.current_attempt(self.st, "OB0004"), 2)
        stm.save(self.st, self.st_path)
        stm.verify_chain(stm.load(self.st_path))
        stm.transition(self.st, "OB0004", "DISPATCHED")                         # attempt 2 runs under the same run id
        self.assertEqual(self.st["history"][-1]["attempt"], 2)
        self.assertEqual([e["to"] for e in stm.run_history(self.st, "OB0004", "OB0004-R7", attempt=2)],
                         ["PREPARED", "DISPATCHED"])
        self.assertEqual(len(stm.run_history(self.st, "OB0004", "OB0004-R7", attempt=1)), 4)

    def test_from_incomplete(self):
        incomplete(self.st, "OB0005")
        stm.new_attempt(self.st, "OB0005", "A1", retirement(self.d, "OB0005", 1), may_start=OK)
        self.assertEqual(stm.current_attempt(self.st, "OB0005"), 2)

    def test_refused_unless_failed_or_incomplete(self):
        ref = retirement(self.d, "OB0004", 1)
        for s in ("PREPARED", "DISPATCHED", "PROPOSED"):
            if s != "PREPARED":
                stm.transition(self.st, "OB0004", s)
            with self.assertRaises(stm.StateError, msg=s):
                stm.new_attempt(self.st, "OB0004", "X", ref, may_start=OK)

    def test_refused_outside_revision_7(self):
        d = T.tmpdir("eg6r2")
        man, p = os.path.join(d, "m.jsonl"), os.path.join(d, "P3B-STATE.json")
        T.write_manifest(man, BIDS)
        st = stm.init(man, p)
        stm.transition(st, "OB0004", "DISPATCHED")
        stm.transition(st, "OB0004", "FAILED", reason="r")
        with self.assertRaises(stm.StateError):
            stm.new_attempt(st, "OB0004", "X", retirement(d, "OB0004", 1), may_start=OK)

    def test_refused_after_any_verified_audited_accepted(self):
        stm.transition(self.st, "OB0004", "DISPATCHED")
        stm.transition(self.st, "OB0004", "PROPOSED")
        stm.transition(self.st, "OB0004", "VERIFIED", evidence=report(self.d, "OB0004", "v.json"))
        stm.transition(self.st, "OB0004", "FAILED", reason="audit FAIL", failure_class="H")
        for cls in ("X", "A1", "A2", "H"):
            with self.assertRaises(stm.StateError, msg=cls):
                stm.new_attempt(self.st, "OB0004", cls, retirement(self.d, "OB0004", 1), may_start=OK)
        self.assertEqual(stm.current_attempt(self.st, "OB0004"), 1)

    def test_verified_in_an_earlier_attempt_blocks_forever(self):
        """Even a history in which an earlier attempt passed (constructed by hand) is refused: at most one PASS."""
        advance(self.st, self.d, "OB0004")
        stm.transition(self.st, "OB0004", "DISPATCHED")
        stm.transition(self.st, "OB0004", "PROPOSED")
        stm.transition(self.st, "OB0004", "VERIFIED",
                       evidence=report(self.d, "OB0004", "S5-VERIFY-OB0004-R7.A2.json"))
        stm.transition(self.st, "OB0004", "FAILED", reason="H-06 rejected")
        with self.assertRaises(stm.StateError):
            stm.new_attempt(self.st, "OB0004", "X", retirement(self.d, "OB0004", 2), may_start=OK)

    def test_refused_for_non_rerunnable_classes(self):
        for cls in ("D", "S", "U", "H", "Z", "", None):                      # H after VERIFIED: see above
            d, p, st = r7_state()
            fail(st, "OB0004", cls if cls in ("D", "S", "U") else "X", SIG1)
            with self.assertRaises(stm.StateError, msg=cls):
                stm.new_attempt(st, "OB0004", cls, retirement(d, "OB0004", 1), may_start=OK)
            self.assertEqual(stm.current_attempt(st, "OB0004"), 1)

    def test_u_is_restore_and_reverify(self):
        fail(self.st, "OB0004", "U", SIG1)
        with self.assertRaisesRegex(stm.StateError, "restore"):
            stm.new_attempt(self.st, "OB0004", "U", retirement(self.d, "OB0004", 1), may_start=OK)

    def test_cause_class_must_match_the_recorded_class(self):
        fail(self.st, "OB0004", "A2", SIG1)
        with self.assertRaises(stm.StateError):
            stm.new_attempt(self.st, "OB0004", "X", retirement(self.d, "OB0004", 1), may_start=OK)
        fail(self.st, "OB0005", "D", SIG1)                                     # a recorded D can never be relabelled
        with self.assertRaises(stm.StateError):
            stm.new_attempt(self.st, "OB0005", "X", retirement(self.d, "OB0005", 1), may_start=OK)

    def test_m_cap(self):
        self.assertEqual(stm.M_MAX_ATTEMPTS, 4)
        sigs = iter([SIG1, SIG2, SIG1, SIG2])                                  # never two identical in a row
        for want in (2, 3, 4):
            self.assertEqual(advance(self.st, self.d, "OB0004", "X", next(sigs)), want)
        fail(self.st, "OB0004", "X", next(sigs))
        with self.assertRaisesRegex(stm.StateError, "M = 4"):
            stm.new_attempt(self.st, "OB0004", "X", retirement(self.d, "OB0004", 4), may_start=OK)
        self.assertEqual(stm.current_attempt(self.st, "OB0004"), 4)

    def test_rr7_identical_signature_is_class_d(self):
        advance(self.st, self.d, "OB0004", "X", SIG1)
        fail(self.st, "OB0004", "A1", SIG1)                                   # attempt 2: same failure signature
        with self.assertRaisesRegex(stm.StateError, "RR-7.*class D|class D.*RR-7"):
            stm.new_attempt(self.st, "OB0004", "A1", retirement(self.d, "OB0004", 2), may_start=OK)
        self.assertIn("D", stm.rr7_reason(self.st, "OB0004"))
        d, p, st = r7_state()                                                  # different signatures: allowed
        advance(st, d, "OB0004", "X", SIG1)
        fail(st, "OB0004", "X", SIG2)
        self.assertIsNone(stm.rr7_reason(st, "OB0004"))
        stm.new_attempt(st, "OB0004", "X", retirement(d, "OB0004", 2), may_start=OK)

    def test_guard_is_required_and_consulted(self):
        fail(self.st, "OB0004")
        ref = retirement(self.d, "OB0004", 1)
        with self.assertRaises(stm.StateError):
            stm.new_attempt(self.st, "OB0004", "X", ref)                        # no guard supplied: fail closed
        with self.assertRaisesRegex(stm.StateError, "PENDING"):
            stm.new_attempt(self.st, "OB0004", "X", ref, may_start=lambda b: (False, "retirement PENDING"))
        self.assertEqual(stm.current_attempt(self.st, "OB0004"), 1)

    def test_retirement_record_is_validated(self):
        fail(self.st, "OB0004")
        good = retirement(self.d, "OB0004", 1)
        bad = [None, "path-only", {"path": good["path"]}, {"sha256": good["sha256"]},
               dict(good, extra=1), dict(good, sha256="0" * 64), dict(good, sha256="xyz"),
               {"path": os.path.join(self.d, "absent.json"), "sha256": good["sha256"]},
               retirement(self.d, "OB0004", 2),                                # wrong attempt
               retirement(self.d, "OB0005", 1),                                # another batch
               retirement(T.tmpdir("eg6ret"), "OB0004", 1, state="PENDING"),
               retirement(T.tmpdir("eg6ret"), "OB0004", 1, intent_sha256="0" * 64)]
        for ref in bad:
            with self.assertRaises(stm.StateError, msg=str(ref)):
                stm.new_attempt(self.st, "OB0004", "X", ref, may_start=OK)
        # a record whose file name / directory is not the governed <B>-R7.A<m>/RETIREMENT-COMPLETE.json
        other = os.path.join(self.d, "elsewhere.json")
        with open(good["path"], "rb") as f, open(other, "wb") as g:
            g.write(f.read())
        with self.assertRaises(stm.StateError):
            stm.new_attempt(self.st, "OB0004", "X", {"path": other, "sha256": sha(other)}, may_start=OK)
        self.assertEqual(stm.current_attempt(self.st, "OB0004"), 1)
        stm.new_attempt(self.st, "OB0004", "X", good, may_start=OK)

    def test_retirable_reports_the_preconditions(self):
        fail(self.st, "OB0004", "A2", SIG3)
        self.assertEqual(stm.retirable(self.st, "OB0004"),
                         {"batch": "OB0004", "attempt": 1, "state": "FAILED", "failure_class": "A2",
                          "failure_signature": SIG3})
        with self.assertRaises(stm.StateError):
            stm.retirable(self.st, "OB0005")                                    # PREPARED: nothing to retire


class AttemptAwareEvidence(unittest.TestCase):

    def setUp(self):
        self.d, self.st_path, self.st = r7_state()

    def test_attempt_2_requires_the_a2_report_name(self):
        advance(self.st, self.d, "OB0004")
        stm.transition(self.st, "OB0004", "DISPATCHED")
        stm.transition(self.st, "OB0004", "PROPOSED")
        for name in ("S5-VERIFY-OB0004-R7.json", "S5-VERIFY-OB0004-R7.A1.json", "S5-VERIFY-OB0004-R7.A3.json",
                     "ok.json"):
            with self.assertRaises(stm.StateError, msg=name):
                stm.transition(self.st, "OB0004", "VERIFIED", evidence=report(self.d, "OB0004", name))
        stm.transition(self.st, "OB0004", "VERIFIED", evidence=report(self.d, "OB0004", "S5-VERIFY-OB0004-R7.A2.json"))
        self.assertEqual(self.st["history"][-1]["attempt"], 2)

    def test_attempt_1_name_unconstrained(self):
        stm.transition(self.st, "OB0004", "DISPATCHED")
        stm.transition(self.st, "OB0004", "PROPOSED")
        stm.transition(self.st, "OB0004", "VERIFIED", evidence=report(self.d, "OB0004", "S5-VERIFY-OB0004-R7.json"))

    def test_report_sha_reuse_across_attempts_refused(self):
        stm.transition(self.st, "OB0004", "DISPATCHED")
        stm.transition(self.st, "OB0004", "PROPOSED")
        first = report(self.d, "OB0004", "S5-VERIFY-OB0004-R7.json")
        stm.transition(self.st, "OB0004", "VERIFIED", evidence=first)
        used = stm.batch_evidence_shas(self.st, "OB0004")
        self.assertIn(T.ops.sha256_path(first), used)
        # the same bytes under the A<m> name (reuse by copy) are refused by check_evidence
        dup = os.path.join(self.d, "reports", "S5-VERIFY-OB0004-R7.A2.json")
        with open(first, "rb") as f, open(dup, "wb") as g:
            g.write(f.read())
        with self.assertRaisesRegex(stm.StateError, "already"):
            stm.check_evidence("VERIFIED", dup, "OB0004", "OB0004-R7", attempt=2, used_shas=used)

    def test_reuse_refused_through_transition(self):
        advance(self.st, self.d, "OB0004")
        stm.transition(self.st, "OB0004", "DISPATCHED")
        stm.transition(self.st, "OB0004", "PROPOSED")
        name = "S5-VERIFY-OB0004-R7.A2.json"
        ev = report(self.d, "OB0004", name)
        # hand-built: plant its sha in an earlier entry of the batch as if it had been used before
        self.st["history"][-3]["evidence"] = {"path": "x", "sha256": T.ops.sha256_path(ev), "kind": "k"}
        with self.assertRaisesRegex(stm.StateError, "already"):
            stm.transition(self.st, "OB0004", "VERIFIED", evidence=ev)

    def test_eg7_still_batch_pass_only(self):
        advance(self.st, self.d, "OB0004")
        stm.transition(self.st, "OB0004", "DISPATCHED")
        stm.transition(self.st, "OB0004", "PROPOSED")
        with self.assertRaises(stm.StateError):
            stm.transition(self.st, "OB0004", "VERIFIED",
                           evidence=report(self.d, "OB0004", "S5-VERIFY-OB0004-R7.A2.json", result="PASS"))


class WsysThresholds(unittest.TestCase):

    def test_constants(self):
        self.assertEqual((stm.WSYS_K, stm.WSYS_ALPHA, stm.WSYS_Q_STAR), (20, "0.01", "0.12771"))
        self.assertEqual(stm.CANARY_BATCHES, ("OB0012", "OB0114"))
        self.assertEqual(set(stm.WSYS_COUNTED), {"X", "A1", "A2"})

    def test_threshold_table_exact(self):
        for n, f in ((20, 7), (40, 11), (100, 22), (200, 38), (400, 68), (800, 126)):
            self.assertEqual(stm.wsys_threshold(n), f, msg=n)
            self.assertTrue(stm.wsys_triggers(n, f), msg=n)
            self.assertFalse(stm.wsys_triggers(n, f - 1), msg=n)


def wsys_state(n_pass, n_fail, bids_extra=(), cls="X"):
    """A synthetic R7 state with one completed attempt per batch: n_pass VERIFIED first, then n_fail FAILED (class cls),
    so the only look with failures is the last one."""
    bids = [f"OB{9000 + i:04d}" for i in range(n_pass + n_fail)] + list(bids_extra)
    d, p, st = r7_state(bids)
    for i, b in enumerate(bids[:n_pass + n_fail]):
        if i >= n_pass:
            fail(st, b, cls, SIG1)
        else:
            stm.transition(st, b, "DISPATCHED")
            stm.transition(st, b, "PROPOSED")
            stm.transition(st, b, "VERIFIED", evidence=report(d, b, f"v-{b}.json"))
    return d, st, bids


class WsysStatus(unittest.TestCase):

    def test_n20_at_and_below_threshold(self):
        _, st, _ = wsys_state(13, 7)
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"], s["stop"], s["threshold"]), (20, 7, True, 7))
        _, st, _ = wsys_state(14, 6)
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"], s["stop"]), (20, 6, False))

    def test_n100_at_and_below_threshold(self):
        _, st, _ = wsys_state(78, 22)
        self.assertEqual(stm.wsys_status(st)["stop"], True)
        self.assertEqual(stm.wsys_status(st)["threshold"], 22)
        _, st, _ = wsys_state(79, 21)
        self.assertEqual(stm.wsys_status(st)["stop"], False)

    def test_no_look_before_k(self):
        _, st, _ = wsys_state(0, 19)
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"], s["stop"], s["threshold"]), (19, 19, False, None))

    def test_canary_attempts_excluded(self):
        _, st, _ = wsys_state(13, 6, bids_extra=("OB0012", "OB0114"))
        for b in ("OB0012", "OB0114"):
            fail(st, b, "X", SIG1)
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"], s["stop"]), (19, 6, False))

    def test_d_s_h_u_not_counted(self):
        for cls in ("D", "S", "U"):
            _, st, _ = wsys_state(0, 5, cls=cls)
            self.assertEqual((stm.wsys_status(st)["n"], stm.wsys_status(st)["f"]), (0, 0), msg=cls)
        d, st, bids = wsys_state(1, 0)
        stm.transition(st, bids[0], "FAILED", reason="audit FAIL")               # H after VERIFIED: the pass stands
        self.assertEqual((stm.wsys_status(st)["n"], stm.wsys_status(st)["f"]), (1, 0))

    def test_n_f_monotone_under_later_audit_stage_entries(self):
        """Audit-independence: appending audit-stage outcomes (AUDITED, FAILED H / D / S after VERIFIED) never
        decreases n or f and never changes the stop decision."""
        d, st, bids = wsys_state(13, 7)
        before = stm.wsys_status(st)
        self.assertEqual((before["n"], before["f"], before["stop"]), (20, 7, True))
        for i, b in enumerate(bids[:13]):
            if i % 3 == 0:
                stm.transition(st, b, "FAILED", reason="§21 FAIL")                                  # H
            elif i % 3 == 1:
                stm.transition(st, b, "AUDITED", evidence=report(d, b, f"AUDITED-{b}.json", result="PASS"))
                stm.transition(st, b, "FAILED", reason="H-06 rejected", failure_class="S")
            else:
                stm.transition(st, b, "FAILED", reason="audit found a design defect", failure_class="D")
            s = stm.wsys_status(st)
            self.assertEqual((s["n"], s["f"], s["stop"]), (20, 7, True), msg=b)

    def test_pending_attempts_not_counted_and_attempts_counted_individually(self):
        d, st, bids = wsys_state(0, 0, bids_extra=("OB0004", "OB0005"))
        advance(st, d, "OB0004", "X", SIG1)                                    # attempt 1 failed
        fail(st, "OB0004", "A2", SIG2)                                         # attempt 2 failed
        stm.transition(st, "OB0005", "DISPATCHED")                              # not completed
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"]), (2, 2))

    def test_rr7_triggering_attempt_is_d_not_counted(self):
        d, st, bids = wsys_state(0, 0, bids_extra=("OB0004",))
        advance(st, d, "OB0004", "X", SIG1)
        fail(st, "OB0004", "X", SIG1)                                          # identical signature → D
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"]), (1, 1))

    def test_stop_blocks_new_attempts(self):
        d, st, bids = wsys_state(13, 7)
        self.assertTrue(stm.wsys_status(st)["stop"])
        fail_b = bids[-1]                                                      # a failed X batch: attempt refused
        with self.assertRaisesRegex(stm.StateError, "W-SYS"):
            stm.new_attempt(st, fail_b, "X", retirement(d, fail_b, 1), may_start=OK)

    def test_stop_record_t146(self):
        """T146: at n = 100, f = threshold − 1 → continue, nothing recorded; f = threshold → STOP, a class-D WSYS-STOP
        entry with the status snapshot is recorded once (idempotent), and no new attempt is admitted."""
        d, st, bids = wsys_state(79, 21)
        n0 = len(st["history"])
        self.assertFalse(stm.wsys_stop(st, "orchestrator", "look"))
        self.assertEqual(len(st["history"]), n0)
        d, st, bids = wsys_state(78, 22)
        self.assertTrue(stm.wsys_stop(st, "orchestrator", "look at n = 100"))
        e = st["history"][-1]
        self.assertEqual((e["run_kind"], e["batch_id"], e["failure_class"]), ("WSYS-STOP", "*", "D"))
        self.assertEqual(e["wsys"], {"n": 100, "f": 22, "threshold": 22, "look": 100})
        n1 = len(st["history"])
        self.assertFalse(stm.wsys_stop(st, "orchestrator", "again"))             # idempotent
        self.assertEqual(len(st["history"]), n1)
        self.assertTrue(stm.wsys_stopped(st))
        with self.assertRaisesRegex(stm.StateError, "W-SYS"):
            stm.new_attempt(st, bids[-1], "X", retirement(d, bids[-1], 1), may_start=OK)
        stm.verify_chain(st)

    def test_recorded_stop_blocks_even_if_status_would_not(self):
        """The durable record governs by itself (e.g. after a hand-built history where the status is below threshold)."""
        d, st, bids = wsys_state(0, 1)
        stm._append(st, "*", None, None, "WSYS-STOP", "hand-built", "t", kind="WSYS-STOP", extra={"failure_class": "D"})
        self.assertFalse(stm.wsys_status(st)["stop"])
        with self.assertRaisesRegex(stm.StateError, "W-SYS"):
            stm.new_attempt(st, bids[-1], "X", retirement(d, bids[-1], 1), may_start=OK)

    def test_resume_requires_a_stop_and_a_glog_and_reopens_attempts(self):
        d, st, bids = wsys_state(13, 7)
        with self.assertRaises(stm.StateError):
            stm.wsys_resume(st, "G-LOG-0107", "human:owner", "diagnosed")      # nothing recorded yet
        stm.wsys_stop(st, "orchestrator", "look")
        for bad in (None, "", "G-LOG-107", "GLOG-0107", "G-LOG-0107x", "g-log-0107"):
            with self.assertRaises(stm.StateError, msg=bad):
                stm.wsys_resume(st, bad, "human:owner", "diagnosed")
        with self.assertRaises(stm.StateError):
            stm.wsys_resume(st, "G-LOG-0107", "human:owner", "")               # a reason is required
        with self.assertRaises(stm.StateError):
            stm.wsys_resume(st, "G-LOG-0107", "human:owner", "diagnosed", count_from="zero")
        stm.wsys_resume(st, "G-LOG-0107", "human:owner", "diagnosed; RR-6 repair")
        e = st["history"][-1]
        self.assertEqual((e["run_kind"], e["glog"], e["count_from"]), ("WSYS-RESUME", "G-LOG-0107", "all"))
        self.assertFalse(stm.wsys_stopped(st))
        s = stm.wsys_status(st)                          # default: cumulative (no reset); the pre-resume look is spent
        self.assertEqual((s["n"], s["f"], s["stop"]), (20, 7, False))
        stm.new_attempt(st, bids[-1], "X", retirement(d, bids[-1], 1), may_start=OK)
        with self.assertRaises(stm.StateError):
            stm.wsys_resume(st, "G-LOG-0108", "human:owner", "again")          # no active stop


def complete(st, d, bids, n_pass, n_fail):
    """Complete one attempt on each of the given (fresh) batches: n_pass VERIFIED, then n_fail FAILED X."""
    for i, b in enumerate(bids[:n_pass + n_fail]):
        if i < n_pass:
            stm.transition(st, b, "DISPATCHED")
            stm.transition(st, b, "PROPOSED")
            stm.transition(st, b, "VERIFIED", evidence=report(d, b, f"v-{b}.json"))
        else:
            fail(st, b, "X", SIG1)


class WsysResumeCounting(unittest.TestCase):
    """Revision round 2: §2.8 (G-LOG-0106) says only "resumption requires a human act"; it pre-registers no reset.
    Counting stays cumulative unless the resume act states count_from="resume"; after a resume, the state is stopped
    again iff a look completed after the resume point triggers."""

    EXTRA = tuple(f"OB{8000 + i:04d}" for i in range(20))

    def stopped_and_resumed(self, count_from=None):
        d, st, bids = wsys_state(13, 7, bids_extra=self.EXTRA)
        self.assertTrue(stm.wsys_stop(st, "orchestrator", "look"))
        kw = {} if count_from is None else {"count_from": count_from}
        stm.wsys_resume(st, "G-LOG-0107", "human:owner", "diagnosed", **kw)
        return d, st

    def test_cumulative_restops_at_the_next_failing_look(self):
        d, st = self.stopped_and_resumed()
        complete(st, d, list(self.EXTRA), 13, 7)                             # cumulative n = 40, f = 14 ≥ 11
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"], s["stop"], s["stop_at_n"]), (40, 14, True, 40))
        self.assertTrue(stm.wsys_stop(st, "orchestrator", "look at n = 40"))
        self.assertEqual(st["history"][-1]["wsys"], {"n": 40, "f": 14, "threshold": 11, "look": 40})
        self.assertTrue(stm.wsys_stopped(st))

    def test_cumulative_continues_when_the_next_look_passes(self):
        d, st = self.stopped_and_resumed("all")
        complete(st, d, list(self.EXTRA), 20, 0)                             # cumulative n = 40, f = 7 < 11
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"], s["stop"]), (40, 7, False))
        self.assertFalse(stm.wsys_stop(st, "orchestrator", "look"))

    def test_count_from_resume_restarts_the_count(self):
        d, st = self.stopped_and_resumed("resume")
        self.assertEqual(st["history"][-1]["count_from"], "resume")
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"], s["stop"]), (0, 0, False))
        complete(st, d, list(self.EXTRA), 14, 6)                             # fresh n = 20, f = 6 < 7
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"], s["stop"]), (20, 6, False))

    def test_count_from_resume_restops_on_its_own_look(self):
        d, st = self.stopped_and_resumed("resume")
        complete(st, d, list(self.EXTRA), 13, 7)                             # fresh n = 20, f = 7 ≥ 7
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"], s["stop"], s["stop_at_n"]), (20, 7, True, 20))

    def test_cli_count_from(self):
        d, st, bids = wsys_state(13, 7)
        p = os.path.join(d, "P3B-STATE.json")
        stm.wsys_stop(st, "orchestrator", "look")
        stm.save(st, p)
        out, err = io.StringIO(), io.StringIO()
        with redirect_stdout(out), redirect_stderr(err):
            rc = stm.main(["--state", p, "wsys-resume", "--glog", "G-LOG-0107", "--reason", "r", "--actor",
                           "human:owner", "--count-from", "resume"])
        self.assertEqual(rc, 0, err.getvalue())
        self.assertEqual(stm.load(p)["history"][-1]["count_from"], "resume")

    def test_stop_found_at_an_earlier_look_persists(self):
        """A stop at the n = 20 look is sticky: later passes (n = 40, f = 7, below that look's threshold) do not undo it."""
        bids = [f"OB{9000 + i:04d}" for i in range(40)]
        d, p, st = r7_state(bids)
        for b in bids[:7]:
            fail(st, b, "X", SIG1)
        for b in bids[7:40]:
            stm.transition(st, b, "DISPATCHED")
            stm.transition(st, b, "PROPOSED")
            stm.transition(st, b, "VERIFIED", evidence=report(d, b, f"v-{b}.json"))
        s = stm.wsys_status(st)
        self.assertEqual((s["n"], s["f"]), (40, 7))
        self.assertFalse(stm.wsys_triggers(40, 7))
        self.assertTrue(s["stop"])
        self.assertEqual(s["stop_at_n"], 20)

    def test_pure_no_mutation(self):
        _, st, _ = wsys_state(13, 7)
        before = json.dumps(st, sort_keys=True)
        stm.wsys_status(st)
        self.assertEqual(json.dumps(st, sort_keys=True), before)


class Cli(unittest.TestCase):

    def run_cli(self, *argv):
        out, err = io.StringIO(), io.StringIO()
        with redirect_stdout(out), redirect_stderr(err):
            rc = stm.main(list(argv))
        return rc, out.getvalue(), err.getvalue()

    def test_wsys_prints_status(self):
        d, p, st = r7_state()
        rc, out, _ = self.run_cli("--state", p, "wsys")
        self.assertEqual(rc, 0)
        s = json.loads(out)
        self.assertEqual((s["n"], s["f"], s["stop"]), (0, 0, False))

    def test_wsys_record_cli(self):
        d, st, bids = wsys_state(13, 7)
        p = os.path.join(d, "P3B-STATE.json")
        stm.save(st, p)
        rc, out, err = self.run_cli("--state", p, "wsys", "--record")
        self.assertEqual(rc, 0, err)
        self.assertEqual(stm.load(p)["history"][-1]["run_kind"], "WSYS-STOP")
        n = len(stm.load(p)["history"])
        rc, out, err = self.run_cli("--state", p, "wsys", "--record")        # idempotent
        self.assertEqual((rc, len(stm.load(p)["history"])), (0, n))
        rc, out, err = self.run_cli("--state", p, "wsys-resume", "--glog", "G-LOG-0107", "--reason", "diagnosed",
                                    "--actor", "human:owner")
        self.assertEqual(rc, 0, err)
        self.assertEqual(stm.load(p)["history"][-1]["run_kind"], "WSYS-RESUME")

    def test_new_attempt_cli(self):
        d, p, st = r7_state()
        fail(st, "OB0004", "X", SIG1)
        stm.save(st, p)
        ref = retirement(d, "OB0004", 1)
        orig = stm.load_guard
        stm.load_guard = lambda: OK
        try:
            rc, out, err = self.run_cli("--state", p, "new-attempt", "OB0004", "--cause-class", "X",
                                        "--retirement", ref["path"])
        finally:
            stm.load_guard = orig
        self.assertEqual(rc, 0, err)
        self.assertEqual(json.loads(out)["run_kind"], "NEW-ATTEMPT")
        self.assertEqual(stm.current_attempt(stm.load(p), "OB0004"), 2)

    def test_new_attempt_cli_refuses_without_guard(self):
        d, p, st = r7_state()
        fail(st, "OB0004", "X", SIG1)
        stm.save(st, p)
        ref = retirement(d, "OB0004", 1)
        orig = stm.load_guard
        stm.load_guard = lambda: None
        try:
            rc, _, err = self.run_cli("--state", p, "new-attempt", "OB0004", "--cause-class", "X",
                                      "--retirement", ref["path"])
        finally:
            stm.load_guard = orig
        self.assertEqual(rc, 1)
        self.assertIn("REFUSED", err)
        self.assertEqual(stm.current_attempt(stm.load(p), "OB0004"), 1)

    def test_transition_cli_takes_class_and_signature(self):
        d, p, st = r7_state()
        stm.transition(st, "OB0004", "DISPATCHED")
        stm.save(st, p)
        rc, out, err = self.run_cli("--state", p, "transition", "OB0004", "FAILED", "--reason", "r",
                                    "--failure-class", "X", "--failure-signature", SIG1)
        self.assertEqual(rc, 0, err)
        self.assertEqual(json.loads(out)["failure_class"], "X")


if __name__ == "__main__":
    unittest.main()
