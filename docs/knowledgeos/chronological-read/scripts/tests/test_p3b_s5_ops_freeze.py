"""Tests: S5 discovery-records freeze (scripts/p3b_s5_freeze.py). Fixtures under /tmp only."""
import io
import json
import os
import unittest
from contextlib import redirect_stdout, redirect_stderr

import p3b_s5_ops_testlib as T

fz = T.ops.load_script("p3b_s5_freeze")
stm = T.ops.load_script("p3b_s5_state")
c = T.c


def fixtures():
    d = T.tmpdir("freeze")
    ledger = os.path.join(d, "ledger")
    T.write_jsonl(os.path.join(ledger, "OB0004-R2", "objects.jsonl"), [
        {"run_id": "OB0004-R2", "batch_id": "OB0004", "working_label": "disc-a", "record_status": "PROPOSED"},
        {"run_id": "OB0004-R2", "batch_id": "OB0004", "working_label": "disc-b", "record_status": "PROPOSED"}])
    T.write_jsonl(os.path.join(ledger, "OB0004-R2", "register.jsonl"), [
        {"rs_id": "OB0004-R2:OB0004:1", "run_id": "OB0004-R2", "lifecycle_stage": "OBSERVED", "scale": "OBJECT"},
        {"rs_id": "OB0004-R2:OB0004:2", "run_id": "OB0004-R2", "lifecycle_stage": "TEST-DEFINED", "scale": "OBJECT"}])
    T.write_jsonl(os.path.join(ledger, "OB0004-R2S", "objects.jsonl"), [
        {"run_id": "OB0004-R2S", "batch_id": "OB0004", "working_label": "disc-a", "record_status": "PROPOSED"}])
    T.write_jsonl(os.path.join(ledger, "S5B", "register.jsonl"), [
        {"rs_id": "S5B:1", "run_id": "S5B-R2", "lifecycle_stage": "TEST-DEFINED", "scale": "CORPUS", "prediction_id": "P-1"},
        {"rs_id": "S5A:RA:1", "run_id": "S5A-R2", "kind": "REANALYSIS-DISPOSITION"},
        {"rs_id": "S5A:C:1", "run_id": "S5A-R2", "record_role": "COMPARISON"}])
    T.write_jsonl(os.path.join(ledger, "OB0005-R2", "objects.jsonl"), [
        {"run_id": "OB0005-R2", "batch_id": "OB0005", "working_label": "disc-c", "record_status": "PROPOSED"}])
    return d, ledger


class Freeze(unittest.TestCase):
    def test_primary_set_predictions_and_exclusions(self):
        d, ledger = fixtures()
        out = os.path.join(d, "S5-DISCOVERY-RECORDS-FREEZE.json")
        with T.fake_holdout():
            hdr, body = fz.freeze([ledger], out)
        self.assertEqual(body["primary_records"]["n"], 6)
        self.assertEqual(body["prediction_set"]["n"], 1)
        self.assertEqual(body["excluded_totals"], {"comparison": 2, "reanalysis": 1, "failed_or_incomplete_run": 0})
        doc = json.loads(T.read(out))
        self.assertEqual(doc["header"]["output_sha256"], c.sha256_bytes(T.ops.canon(doc["body"]).encode()))
        with T.fake_holdout(), self.assertRaises(c.S5Error):
            fz.freeze([ledger], out)                                # written once

    def test_deterministic(self):
        d, ledger = fixtures()
        with T.fake_holdout():
            _, b1 = fz.freeze([ledger], os.path.join(d, "f1.json"))
            _, b2 = fz.freeze([ledger], os.path.join(d, "f2.json"))
        self.assertEqual(b1["primary_records"]["set_sha256"], b2["primary_records"]["set_sha256"])
        self.assertEqual(b1["prediction_set"]["set_sha256"], b2["prediction_set"]["set_sha256"])
        self.assertEqual(T.ops.canon(b1["primary_records"]), T.ops.canon(b2["primary_records"]))

    def test_failed_runs_excluded_with_state(self):
        d, ledger = fixtures()
        man, st = os.path.join(d, "man.jsonl"), os.path.join(d, "P3B-STATE.json")
        T.write_manifest(man, ["OB0004", "OB0005"])
        stm.init(man, st)
        s = stm.load(st)
        for to in ("DISPATCHED", "PROPOSED"):
            stm.transition(s, "OB0005", to)
        stm.transition(s, "OB0005", "FAILED", reason="G-01")
        stm.save(s, st)
        with T.fake_holdout():
            _, body = fz.freeze([ledger], os.path.join(d, "f.json"), st)
        self.assertEqual(body["excluded_totals"]["failed_or_incomplete_run"], 1)
        self.assertEqual(body["primary_records"]["n"], 5)

    def test_refuses_when_not_sealed(self):
        d, ledger = fixtures()
        out = os.path.join(d, "f.json")
        with T.seal_state("UNSEALED"), redirect_stdout(io.StringIO()), redirect_stderr(io.StringIO()):
            self.assertEqual(fz.main([ledger, "--out", out]), 1)
        with T.temp_seal_copy("UNSEALED"), redirect_stdout(io.StringIO()), redirect_stderr(io.StringIO()):
            self.assertEqual(fz.main([ledger, "--out", out]), 1)
        self.assertFalse(os.path.exists(out))

    def test_refuses_quarantine_hit(self):
        d, ledger = fixtures()
        T.write_jsonl(os.path.join(ledger, "OB0006-R2", "register.jsonl"),
                      [{"rs_id": "X:1", "run_id": "OB0006-R2", "statement": "about zz-fake-holdout-alpha"}])
        with T.fake_holdout(), self.assertRaises(c.S5Error):
            fz.freeze([ledger], os.path.join(d, "f.json"))

    def test_refuses_forbidden_input(self):
        with self.assertRaises(c.S5Error):
            fz.build([os.path.join(c.CR, c.FORBIDDEN[0])])


if __name__ == "__main__":
    unittest.main()
