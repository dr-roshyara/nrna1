"""Tests: test-pass tooling (scripts/p3b_s5_testpass.py). Synthetic fixtures under /tmp only."""
import io
import json
import os
import random
import unittest
from contextlib import redirect_stdout, redirect_stderr

import p3b_s5_ops_testlib as T

tp = T.ops.load_script("p3b_s5_testpass")
c = T.c


def reg(n=23):
    recs = []
    for i in range(n):
        recs.append({"rs_id": f"OB{4 + i % 3:04d}-R2:OB{4 + i % 3:04d}:{i}", "run_id": f"OB{4 + i % 3:04d}-R2",
                     "lifecycle_stage": "TEST-DEFINED", "scale": "OBJECT" if i % 2 else "CROSS-OBJECT",
                     "test_plan_sha256": f"{i:064x}"})
    recs += [
        {"rs_id": "C:1", "lifecycle_stage": "TEST-DEFINED", "scale": "CORPUS", "test_plan_sha256": "a" * 64},
        {"rs_id": "R:1", "run_id": "OB0004-R2S", "lifecycle_stage": "TEST-DEFINED", "scale": "OBJECT",
         "test_plan_sha256": "b" * 64},
        {"rs_id": "RA:1", "kind": "REANALYSIS-DISPOSITION", "lifecycle_stage": "TEST-DEFINED", "scale": "OBJECT",
         "test_plan_sha256": "c" * 64},
        {"rs_id": "O:1", "lifecycle_stage": "ANALYSED", "scale": "OBJECT"}]
    return recs


class Frame(unittest.TestCase):
    def test_frame_content_sorted_and_hashed(self):
        fr = tp.build_frame(reg())
        ids = [r["rs_id"] for r in fr["rows"]]
        self.assertEqual(fr["n"], 23)
        self.assertEqual(ids, sorted(ids))
        self.assertFalse({"C:1", "R:1", "RA:1", "O:1"} & set(ids))
        self.assertEqual(fr["frame_sha256"], T.ops.canon_hash(fr["rows"]))
        self.assertEqual(tp.build_frame(list(reversed(reg())))["frame_sha256"], fr["frame_sha256"])

    def test_missing_test_plan_refused(self):
        bad = reg() + [{"rs_id": "Z:1", "lifecycle_stage": "TEST-DEFINED", "scale": "OBJECT"}]
        with self.assertRaises(c.S5Error):
            tp.build_frame(bad)

    def test_permutation_is_the_frozen_rule(self):
        fr = tp.build_frame(reg())
        perm = tp.permute(fr)
        ids = sorted(r["rs_id"] for r in fr["rows"])
        random.Random(20260927).shuffle(ids)
        self.assertEqual(perm["order"], ids)
        self.assertEqual(perm["permutation_sha256"], tp.permute(fr)["permutation_sha256"])

    def test_prefix_size_exact_integer_ceiling(self):
        self.assertEqual([tp.prefix_size(n) for n in (0, 1, 5, 10, 11, 15, 23, 100, 101)], [0, 1, 1, 2, 3, 3, 5, 20, 21])


class Plan(unittest.TestCase):
    def setUp(self):
        self.fr = tp.build_frame(reg())
        self.pre = tp.prefix(tp.permute(self.fr))

    def test_load_record_keeps_place(self):
        loads = {rid: (5000 if i in (0, 3) else 10) for i, rid in enumerate(self.pre)}
        pl = tp.plan(self.fr, loads)
        self.assertEqual([e["rs_id"] for e in pl["prefix"]], self.pre)          # nothing dropped or replaced
        self.assertEqual([e["decision"] for e in pl["prefix"]], ["LOAD", "RUN", "RUN", "LOAD", "RUN"])
        self.assertTrue(all(e["keeps_prefix_place"] for e in pl["prefix"] if e["decision"] == "LOAD"))
        self.assertEqual(pl["summary"]["LOAD"], 2)

    def test_threshold_boundary(self):
        self.assertEqual(tp.decide("x", 4081)["decision"], "RUN")
        self.assertEqual(tp.decide("x", 4082)["decision"], "LOAD")

    def test_corpus_never_skipped_overload_escalates(self):
        corpus = {"C:1": {"load": 9000, "predicted_read_bytes": 10}, "C:2": {"load": 10, "predicted_read_bytes": 9_000_000},
                  "C:3": {"load": 10, "predicted_read_bytes": 10}}
        pl = tp.plan(self.fr, {}, corpus)
        self.assertEqual([e["decision"] for e in pl["corpus"]], ["ESCALATE-BEFORE-RUN", "ESCALATE-BEFORE-RUN", "RUN-FULL"])
        self.assertFalse(any(e["skipped"] for e in pl["corpus"]))
        esc = tp.escalation(pl["corpus"][0])
        self.assertEqual((esc["escalation"], esc["reason"]), ("P3B-ESC", "LOAD"))

    def test_cli_plan_writes_escalations_and_requires_path(self):
        d = T.tmpdir("testpass")
        frame_p, loads_p = os.path.join(d, "frame.json"), os.path.join(d, "loads.json")
        with open(frame_p, "w") as f:
            json.dump(self.fr, f)
        with open(loads_p, "w") as f:
            json.dump({**{rid: 1 for rid in self.pre}, "corpus": {"C:1": {"load": 9999}}}, f)
        with redirect_stdout(io.StringIO()), redirect_stderr(io.StringIO()):
            self.assertEqual(tp.main(["plan", frame_p, "--loads", loads_p, "--out", os.path.join(d, "p1.json")]), 1)
            esc = os.path.join(d, "ESC.jsonl")
            self.assertEqual(tp.main(["plan", frame_p, "--loads", loads_p, "--escalations", esc,
                                      "--out", os.path.join(d, "p2.json")]), 0)
        self.assertEqual(len(T.read_jsonl(esc)), 1)
        self.assertFalse(os.path.exists(os.path.join(d, "p1.json")))


class LoadAndBudget(unittest.TestCase):
    def test_distinct_union(self):
        b = [{"source_id": "S0001", "offset": 10, "term": "x"}, {"source_id": "S0001", "offset": 10, "term": "x"},
             {"source_id": "S0002", "anchor": "## A", "term": "x"}]
        d = [{"source_id": "S0001", "offset": 10, "term": "x"}, {"source_id": "S0001", "offset": 10, "term": "y"}]
        with T.fake_holdout():
            self.assertEqual(tp.test_load(b, d), 3)

    def test_holdout_file_in_results_refused(self):
        with T.fake_holdout(), self.assertRaises(c.S5Error):
            tp.test_load([{"source_id": "S9990", "offset": 1, "term": "x"}], [])

    def test_read_cap_partial(self):
        rb = tp.ReadBudget()
        self.assertTrue(rb.read("S0001", 8_000_000))
        self.assertTrue(rb.read("S0002", 633_127))                 # exactly the cap
        self.assertFalse(rb.read("S0003", 1))
        self.assertEqual((rb.outcome(), rb.used), ("LOAD-PARTIAL", 8_633_127))

    def test_corpus_budget_escalates_not_truncates(self):
        rb = tp.ReadBudget(corpus=True)
        rb.read("S0001", 8_633_127)
        with self.assertRaises(tp.EscalationRequired):
            rb.read("S0002", 1)
        ok = tp.ReadBudget(corpus=True, authorized_budget=20_000_000)
        self.assertTrue(ok.read("S0001", 15_000_000))


class Record(unittest.TestCase):
    def test_section_written_once(self):
        d = T.tmpdir("manifest")
        man = os.path.join(d, "P3B-INPUT-MANIFEST.json")
        with open(man, "w") as f:
            json.dump({"header": {"x": 1}, "body": {"y": 2}}, f)
        fr = tp.build_frame(reg())
        pl = tp.plan(fr, {})
        sec = tp.record_plans(pl, fr, man)
        self.assertEqual(sec["prefix_n"], 5)
        self.assertEqual(set(sec["test_plan_sha256"]), {e["rs_id"] for e in pl["prefix"]})
        doc = json.loads(T.read(man))
        self.assertEqual((doc["header"], doc["body"]), ({"x": 1}, {"y": 2}))
        with self.assertRaises(c.S5Error):
            tp.record_plans(pl, fr, man)

    def test_cli_refuses_when_not_sealed(self):
        d = T.tmpdir("tpseal")
        p = os.path.join(d, "reg.jsonl")
        T.write_jsonl(p, reg())
        with T.seal_state("UNSEALED"), redirect_stdout(io.StringIO()), redirect_stderr(io.StringIO()):
            self.assertEqual(tp.main(["frame", p, "--out", os.path.join(d, "f.json")]), 1)
        self.assertFalse(os.path.exists(os.path.join(d, "f.json")))


if __name__ == "__main__":
    unittest.main()
