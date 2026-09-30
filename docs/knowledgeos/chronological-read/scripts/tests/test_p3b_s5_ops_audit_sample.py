"""Tests: §21 / OMQ-09 audit-sample draw (scripts/p3b_s5_audit_sample.py). The committed S4 R2.2 ledgers are read-only
fixtures; synthetic fixtures under /tmp."""
import io
import json
import os
import random
import unittest
from contextlib import redirect_stdout

import p3b_s5_ops_testlib as T

au = T.ops.load_script("p3b_s5_audit_sample")
c = T.c
R22 = [os.path.join(c.CR, "ledger-p3b-r2", "S4-R22", b) for b in ("PB02", "PB03", "PB04", "PB05")]


class OnR22(unittest.TestCase):
    def setUp(self):
        self.bodies = [au.sample_run(p, i, 4)[0] for i, p in enumerate(R22)]

    def test_mandatory_register_matches_measured_rate(self):
        self.assertEqual(sum(len(b["mandatory_register"]) for b in self.bodies), 23)     # plan §B.8: 23 per 20 labels

    def test_seeded_records_minimum_and_object(self):
        for b in self.bodies:
            s = b["seeded_records"]
            self.assertEqual(s["target"], 5)
            self.assertEqual(s["n"], 5)
            self.assertTrue(s["object_included"])
            self.assertTrue(set(s["register"]).isdisjoint(b["mandatory_register"] + b["other_register_sample"]))
            self.assertEqual(len(b["other_register_sample"]), 2)

    def test_f3_sample(self):
        f3 = self.bodies[0]["f3_stage2b"]
        self.assertTrue(f3)
        for v in f3.values():
            self.assertGreater(v["occurrences"], 100)
            self.assertEqual(len(v["sample"]), 30)
            self.assertEqual(len({json.dumps(x) for x in v["sample"]}), 30)

    def test_deterministic_and_stream_per_index(self):
        again = [au.sample_run(p, i, 4)[0] for i, p in enumerate(R22)]
        self.assertEqual([T.ops.canon_hash(b) for b in self.bodies], [T.ops.canon_hash(b) for b in again])
        self.assertNotEqual(au.batch_seed(0, 396), au.batch_seed(1, 396))
        self.assertEqual(au.batch_seed(3, 4), au.batch_seed(3, 396))    # a child stream depends on its index only
        self.assertEqual(au.batch_seed(0, 4), c.int_seed(20261100, 4, 0))

    def test_cli_header(self):
        out = os.path.join(T.tmpdir("audit"), "sample.json")
        with redirect_stdout(io.StringIO()):
            self.assertEqual(au.main(["sample", R22[1], "--index", "1", "--n-batches", "4", "--out", out]), 0)
        doc = json.loads(T.read(out))
        self.assertEqual(doc["header"]["output_sha256"], c.sha256_bytes(T.ops.canon(doc["body"]).encode()))
        self.assertEqual(sorted(doc["header"]["input_hashes"]),
                         ["ledger-p3b-r2/S4-R22/PB03/objects.jsonl", "ledger-p3b-r2/S4-R22/PB03/register.jsonl"])


class Synthetic(unittest.TestCase):
    def mk(self, n_obj, statuses, n_occ=0, offsets=False):
        objs = []
        for i in range(n_obj):
            disp = []
            if i == 0 and n_occ:
                if offsets:
                    disp = [{"method": "STAGE-2A-2B", "source_id": "S0001", "hit_kind": "raw", "hit_key": k,
                             "term_index": 0, "offsets": list(range(k * 10, k * 10 + 10))} for k in range(n_occ // 10)]
                else:
                    disp = [{"method": "STAGE-2A-2B", "source_id": "S0001", "hit_kind": "raw", "hit_key": k,
                             "term_index": 0} for k in range(n_occ)]
                disp.append({"method": "WHOLE-FILE", "source_id": "S0002", "hit_kind": "raw", "hit_key": 1, "term_index": 0})
            objs.append({"working_label": f"lab-{i:02d}", "semantic_status": statuses.get(i), "stage2_dispositions": disp})
        kinds = ["OBSERVATION"] * 40 + ["HYPOTHESIS", "SCHEMA-LIMITATION", "GAP"]
        reg = [{"rs_id": f"R:{j:03d}", "kind": k} for j, k in enumerate(kinds)]
        return objs, reg

    def test_contested_and_homonym_always_included(self):
        objs, reg = self.mk(5, {1: "CONTESTED", 3: "HOMONYM-SPLIT"})
        b = au.draw(objs, reg, 0, 10)
        self.assertEqual(b["mandatory_contested_or_homonym_split"], ["lab-01", "lab-03"])
        self.assertTrue(set(b["seeded_records"]["objects"]).isdisjoint({"lab-01", "lab-03"}))

    def test_ten_percent_floor_min_five(self):
        objs, reg = self.mk(20, {})                     # N = 63 -> floor(6.3) = 6
        b = au.draw(objs, reg, 2, 10)
        self.assertEqual(b["seeded_records"]["target"], 6)
        self.assertEqual(b["seeded_records"]["n"], 6)
        self.assertEqual(len(b["mandatory_register"]), 2)

    def test_f3_threshold_and_offset_expansion(self):
        objs, reg = self.mk(3, {}, n_occ=100)
        self.assertEqual(au.draw(objs, reg, 0, 10)["f3_stage2b"], {})              # exactly 100: not > 100
        objs, reg = self.mk(3, {}, n_occ=101)
        self.assertEqual(len(au.draw(objs, reg, 0, 10)["f3_stage2b"]["lab-00"]["sample"]), 30)
        objs, reg = self.mk(3, {}, n_occ=110, offsets=True)                          # 11 dispositions x 10 offsets
        self.assertEqual(au.draw(objs, reg, 0, 10)["f3_stage2b"]["lab-00"]["occurrences"], 110)

    def test_groups(self):
        gs = au.groups(396)
        self.assertEqual(len(gs), 80)
        self.assertEqual(gs[-1]["indices"], [395])
        self.assertTrue(au.group_of(4)["cross_batch_audit_due_after_this"])
        self.assertFalse(au.group_of(5)["cross_batch_audit_due_after_this"])

    def test_refusals(self):
        with self.assertRaises(c.S5Error):
            au.batch_seed(4, 4)
        with self.assertRaises(c.S5Error):
            au.sample_run(os.path.join(c.CR, "ledger"), 0, 4)          # the census ledger directory is forbidden

    def test_other_register_is_seeded_sample(self):
        objs, reg = self.mk(5, {})
        b = au.draw(objs, reg, 7, 10)
        rng = random.Random(au.batch_seed(7, 10))
        others = sorted(r["rs_id"] for r in reg if r["kind"] not in au.MANDATORY_KINDS)
        self.assertEqual(b["other_register_sample"], sorted(rng.sample(others, 2)))


if __name__ == "__main__":
    unittest.main()
