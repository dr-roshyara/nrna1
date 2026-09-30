"""Tests for p3b_s5a_snapshot.py (link 1). Temp directories only; the real seal is only asserted, never modified."""
import importlib.util
import json
import os
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
spec = importlib.util.spec_from_file_location("snap", os.path.join(HERE, "..", "p3b_s5a_snapshot.py"))
snap = importlib.util.module_from_spec(spec)
spec.loader.exec_module(snap)


def write(root, rel, lines):
    p = os.path.join(root, rel)
    os.makedirs(os.path.dirname(p), exist_ok=True)
    with open(p, "w") as f:
        f.write("".join(json.dumps(x) + "\n" for x in lines))


class Snapshot(unittest.TestCase):
    def setUp(self):
        self.t = tempfile.TemporaryDirectory()
        self.root = self.t.name
        write(self.root, "ledger-p3b-r2/OB0004-R2/objects.jsonl", [{"working_label": "a"}])
        write(self.root, "ledger-p3b-r2/OB0004-R2/register.jsonl", [{"rs_id": "x"}])
        write(self.root, "ledger-p3b-r2/OB0005-R2.2/objects.jsonl", [{"working_label": "b"}])

    def tearDown(self):
        self.t.cleanup()

    def acc(self, recs):
        write(self.root, "audit-p3b/S5-ACCEPTANCE.jsonl", recs)

    def test_only_accepted_primary_runs(self):
        self.acc([{"batch_id": "OB0004", "run_id": "OB0004-R2", "outcome": "ACCEPTED", "decided_by": "human"},
                  {"batch_id": "OB0005", "run_id": "OB0005-R2.2", "outcome": "ACCEPTED", "decided_by": "human"},
                  {"batch_id": "OB0006", "run_id": "OB0006-R2", "outcome": "NOT-ACCEPTED", "decided_by": "human"}])
        out = os.path.join(self.root, "snap.json")
        self.assertEqual(snap.main(["--pass", "OA0001", "--root", self.root, "--out", out]), 0)
        body = json.load(open(out))["body"]
        self.assertEqual([b["batch_id"] for b in body["accepted_batches"]], ["OB0004", "OB0005"])
        self.assertEqual(body["counts"]["files"], 3)
        self.assertEqual(snap.main(["--pass", "OA0001", "--root", self.root, "--out", out]), 2)   # written once

    def test_comparison_run_refused(self):
        self.acc([{"batch_id": "OB0004", "run_id": "OB0004-R2S", "outcome": "ACCEPTED", "decided_by": "human"}])
        with self.assertRaises(snap.c.S5Error):
            snap.build(self.root, "audit-p3b/S5-ACCEPTANCE.jsonl")

    def test_duplicate_batch_refused(self):
        self.acc([{"batch_id": "OB0004", "run_id": "OB0004-R2", "outcome": "ACCEPTED"},
                  {"batch_id": "OB0004", "run_id": "OB0004-R2", "outcome": "ACCEPTED"}])
        with self.assertRaises(snap.c.S5Error):
            snap.build(self.root, "audit-p3b/S5-ACCEPTANCE.jsonl")

    def test_bad_pass_id(self):
        self.assertEqual(snap.main(["--pass", "OB0001"]), 2)


if __name__ == "__main__":
    unittest.main()
