"""Tests for p3b_s5_quotes.py — a fake resolver only; no corpus content is read."""
import importlib.util
import json
import os
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
spec = importlib.util.spec_from_file_location("q", os.path.join(HERE, "..", "p3b_s5_quotes.py"))
q = importlib.util.module_from_spec(spec)
spec.loader.exec_module(q)


class FakeResolver:
    def __init__(self, texts, sealed=()):
        self.texts, self.sealed = texts, set(sealed)

    def read_many(self, sids):
        if any(s in self.sealed for s in sids):
            raise q.c.s3.IdentityError("sealed")
        return {s: self.texts[s].encode() for s in sids}


TEXTS = {"S0001": "Alpha beta gamma.\nThe  quick brown fox.", "S0002": "Other text here."}


class QuoteCheck(unittest.TestCase):
    def run_case(self, records, sealed=()):
        return q.check(records, FakeResolver(TEXTS, sealed))

    def test_exact_whitespace_miss(self):
        recs = [{"timeline": [{"source_id": "S0001", "states": {"quote": "beta gamma"}}]},
                {"source_id": "S0001", "quote": "The quick brown fox."},
                {"supplied_by": {"source_id": "S0002", "anchor": "x", "quote": "not present"}}]
        r = self.run_case(recs)
        self.assertEqual((r["exact"], r["whitespace"], len(r["miss"])), (1, 1, 1))

    def test_nearest_source_id_binding(self):
        recs = [{"source_id": "S0002", "inner": {"source_id": "S0001", "quote": "Alpha beta"}}]
        self.assertEqual(self.run_case(recs)["exact"], 1)

    def test_no_source_id_counted_not_failed(self):
        r = self.run_case([{"quote": "Alpha"}])
        self.assertEqual((r["no_source_id"], len(r["miss"])), (1, 0))

    def test_sealed_read_is_failure(self):
        r = self.run_case([{"source_id": "S0001", "quote": "Alpha"}], sealed=("S0001",))
        self.assertEqual((r["refused"], len(r["miss"])), (1, 1))
        self.assertEqual(r["miss"][0]["source_id"], "S####(refused)")

    def test_main_end_to_end_and_append_only(self):
        with tempfile.TemporaryDirectory() as root:
            run = "OB0004-R2"
            d = os.path.join(root, "ledger-p3b-r2", run)
            os.makedirs(d)
            with open(os.path.join(d, "objects.jsonl"), "w") as f:
                f.write(json.dumps({"source_id": "S0001", "quote": "Alpha beta"}) + "\n")
            out = os.path.join(root, "out.json")
            rc = q.main(["--batch", "OB0004", "--root", root, "--out", out], resolver=FakeResolver(TEXTS))
            self.assertEqual(rc, 0)
            body = json.load(open(out))["body"]
            self.assertEqual((body["exact"], body["result"]), (1, "PASS"))
            self.assertEqual(q.main(["--batch", "OB0004", "--root", root, "--out", out], resolver=FakeResolver(TEXTS)), 2)

    def test_bad_batch_refused(self):
        self.assertEqual(q.main(["--batch", "PB01"]), 2)


if __name__ == "__main__":
    unittest.main()
