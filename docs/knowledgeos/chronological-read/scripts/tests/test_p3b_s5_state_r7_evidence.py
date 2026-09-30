"""Tests (EG-7): VERIFIED evidence for a contract-revision-7 run (scripts/p3b_s5_state.py `check_evidence`).

The R7 verifier emits only BATCH-PASS | BATCH-FAIL | BATCH-UNDETERMINED (addendum §0, §10; T80), never a bare "PASS".
For a revision-7 run (`<B>-R7`) VERIFIED therefore requires exactly `result == "BATCH-PASS"`; every other revision
keeps exactly the historical rule (`result == "PASS"`). AUDITED is unchanged (its producer, p3b_s5_audit_record.py,
is outside this change). All on /tmp copies; no production state or manifest is touched.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_state_r7_evidence
"""
import json
import os
import unittest

import p3b_s5_ops_testlib as T
from test_p3b_s5_ops_state import BIDS, rev_manifest, walk

stm = T.ops.load_script("p3b_s5_state")
c = T.c
VERIFY_SCRIPT = os.path.join(c.CR, "scripts", "p3b_s5_verify.py")


def r7_state():
    """A synthetic state at revision 7: init from an R2 manifest, then the governed R2→R7 revision transition."""
    d = T.tmpdir("r7ev")
    prev, new, st_path = os.path.join(d, "prev.jsonl"), os.path.join(d, "new.jsonl"), os.path.join(d, "P3B-STATE.json")
    rev_manifest(prev, BIDS)
    rev_manifest(new, BIDS, "-R7")
    stm.init(prev, st_path)
    st = stm.load(st_path)
    stm.revision_transition(st, new, prev, "EG-7 test")
    stm.save(st, st_path)
    return d, st_path


def real_header_report(dirpath, name, batch, run, result):
    """A report written the way p3b_s5_verify.main writes it: the real §19.5 header function over the canonical body."""
    body = {"batch_id": batch, "run_id": run, "result": result, "comparison": False, "acceptance_evidence": True}
    body_text = c.canon(body)
    hdr = c.header(VERIFY_SCRIPT, [], {"batch": batch, "run": run}, body_text, {"artifact": name, "result": result})
    os.makedirs(dirpath, exist_ok=True)
    p = os.path.join(dirpath, name)
    with open(p, "w", encoding="utf-8") as f:
        f.write(json.dumps({"header": hdr, "body": body}, ensure_ascii=False, indent=1, sort_keys=True) + "\n")
    return p


class R7VerifiedEvidence(unittest.TestCase):

    def setUp(self):
        self.d, self.st_path = r7_state()
        walk(self.st_path, "OB0004", ["DISPATCHED", "PROPOSED"])
        self.st = stm.load(self.st_path)
        self.assertEqual(self.st["batches"]["OB0004"]["run_id"], "OB0004-R7")

    def rep(self, name, result="BATCH-PASS", **kw):
        return T.write_report(os.path.join(self.d, "reports"), name, kw.pop("batch", "OB0004"), kw.pop("run", "OB0004-R7"),
                              result=result, **kw)

    def test_r7_batch_pass_accepted(self):
        ev = self.rep("ok.json")
        self.assertIsNotNone(stm.check_evidence("VERIFIED", ev, "OB0004", "OB0004-R7"))
        stm.transition(self.st, "OB0004", "VERIFIED", evidence=ev)
        self.assertEqual(self.st["batches"]["OB0004"]["state"], "VERIFIED")
        self.assertEqual(self.st["history"][-1]["evidence"]["sha256"], T.ops.sha256_path(ev))

    def test_r7_refuses_every_other_result(self):
        for res in ("PASS", "BATCH-FAIL", "BATCH-UNDETERMINED", "FAIL", None):
            with self.assertRaises(c.S5Error, msg=res):
                stm.transition(self.st, "OB0004", "VERIFIED", evidence=self.rep(f"r-{res}.json", result=res))
        self.assertEqual(self.st["batches"]["OB0004"]["state"], "PROPOSED")

    def test_r7_other_checks_still_apply(self):
        bad = [self.rep("other-batch.json", batch="OB0005", run="OB0005-R7"),
               self.rep("unit-run.json", run="OB0004-R7-L01"),
               self.rep("r2-run.json", run="OB0004-R2"),
               self.rep("nohdr.json", header=False),
               self.rep("handmade.json", script="fixture"),
               self.rep("badhash.json", bad_hash=True),
               self.rep("audit-as-verify.json", script="scripts/p3b_s5_audit_record.py"),
               self.rep("comparison.json", comparison=True),
               None]
        for ev in bad:
            with self.assertRaises(c.S5Error, msg=str(ev)):
                stm.transition(self.st, "OB0004", "VERIFIED", evidence=ev)
        self.assertEqual(self.st["batches"]["OB0004"]["state"], "PROPOSED")

    def test_end_to_end_real_header_proposed_to_verified(self):
        refused = real_header_report(os.path.join(self.d, "reports"), "S5-VERIFY-OB0004-R7-fail.json", "OB0004",
                                     "OB0004-R7", "BATCH-FAIL")
        with self.assertRaises(c.S5Error):
            stm.transition(self.st, "OB0004", "VERIFIED", evidence=refused)
        ok = real_header_report(os.path.join(self.d, "reports"), "S5-VERIFY-OB0004-R7.json", "OB0004", "OB0004-R7",
                                "BATCH-PASS")
        stm.transition(self.st, "OB0004", "VERIFIED", evidence=ok)
        stm.save(self.st, self.st_path)
        again = stm.load(self.st_path)
        self.assertEqual(again["batches"]["OB0004"]["state"], "VERIFIED")
        self.assertEqual(again["history"][-1]["to"], "VERIFIED")
        self.assertEqual(again["history"][-1]["evidence"]["kind"], "S5 verify report")

    def test_audited_unchanged_for_r7(self):
        """AUDITED keeps the audit tool's vocabulary (PASS | FAIL); EG-7 does not change it."""
        self.assertIsNotNone(stm.check_evidence("AUDITED", self.rep("AUDITED-ok.json", result="PASS"), "OB0004", "OB0004-R7"))
        with self.assertRaises(c.S5Error):
            stm.check_evidence("AUDITED", self.rep("AUDITED-bp.json", result="BATCH-PASS"), "OB0004", "OB0004-R7")


class NonR7Unchanged(unittest.TestCase):

    def test_r2_pass_accepted_batch_pass_refused(self):
        d = T.tmpdir("r2ev")
        for run in ("OB0004-R2", "OB0004-R2.2"):
            ok = T.write_report(d, f"{run}-ok.json", "OB0004", run, result="PASS")
            self.assertIsNotNone(stm.check_evidence("VERIFIED", ok, "OB0004", run))
            bp = T.write_report(d, f"{run}-bp.json", "OB0004", run, result="BATCH-PASS")
            with self.assertRaises(c.S5Error):
                stm.check_evidence("VERIFIED", bp, "OB0004", run)

    def test_comparison_run_unchanged(self):
        d = T.tmpdir("r2sev")
        ok = T.write_report(d, "c.json", "OB0006", "OB0006-R2S", result="PASS", comparison=True)
        self.assertIsNotNone(stm.check_evidence("VERIFIED", ok, "OB0006", "OB0006-R2S", comparison=True))
        bp = T.write_report(d, "cbp.json", "OB0006", "OB0006-R2S", result="BATCH-PASS", comparison=True)
        with self.assertRaises(c.S5Error):
            stm.check_evidence("VERIFIED", bp, "OB0006", "OB0006-R2S", comparison=True)


if __name__ == "__main__":
    unittest.main()
