"""Tests for p3b_v1_instrument.py (V1 instrument-validation tooling). Synthetic data only; reads no corpus."""
import importlib.util
import os
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
spec = importlib.util.spec_from_file_location("v1", os.path.join(HERE, "..", "p3b_v1_instrument.py"))
v1 = importlib.util.module_from_spec(spec)
spec.loader.exec_module(v1)
COMMIT = "0123abcd" + "0" * 32


def text():
    return ("preamble\n\n# One\nalpha beta\n\n```\n# fenced, not a heading\n```\n\n## Two\n"
            + "".join(f"paragraph {i} " * 40 + "\n\n" for i in range(40)) + "### Three\nlast\n\n  \n")


class Segmentation(unittest.TestCase):
    def test_tiles_is_deterministic_and_bounded(self):
        t = text()
        s = v1.segment(t)
        self.assertTrue(v1.tiles(s, len(t)))
        self.assertEqual(s, v1.segment(t))
        self.assertTrue(all(x["char_end"] - x["char_start"] <= v1.MAX_SEG for x in s))

    def test_fence_heading_ignored_and_kinds(self):
        s = v1.segment(text())
        kinds = [x["kind"] for x in s]
        self.assertEqual(kinds[:3], ["PREAMBLE", "HEADING", "HEADING"])
        self.assertIn("SPLIT", kinds)
        self.assertEqual(sum(k == "HEADING" for k in kinds), 3)

    def test_long_paragraphless_text_hard_cut(self):
        t = "x" * (v1.MAX_SEG * 2 + 7)
        s = v1.segment(t)
        self.assertTrue(v1.tiles(s, len(t)))
        self.assertEqual(len(s), 3)

    def test_whitespace_only_segment_merged(self):
        t = "# A\ntext\n# B\n   \n"
        s = v1.segment(t)
        self.assertTrue(v1.tiles(s, len(t)))
        self.assertTrue(all(t[x["char_start"]:x["char_end"]].strip() for x in s))

    def test_map_pages(self):
        t = "a" * 45000
        m = v1.segment_map("S0001", t)
        self.assertEqual(m[0]["page_first"], 1)
        self.assertEqual(m[-1]["page_last"], 3)


class Checks(unittest.TestCase):
    def setUp(self):
        self.t = text()
        self.map = v1.segment_map("S0001", self.t)
        self.texts = {"S0001": self.t}

    def rec(self, seg, quote):
        return {"segment_id": seg["segment_id"], "source_id": "S0001", "result": "PROPOSITIONS",
                "propositions": [{"proposition_id": "P1", "proposition_type": "CLAIM", "status": "ASSERTED",
                                  "statement": "s", "quote": quote, "register_decision": "PROMOTE"}]}

    def test_full_coverage_and_quote_range(self):
        recs = []
        for seg in self.map:
            q = self.t[seg["char_start"]:seg["char_end"]].strip()[:10]
            recs.append(self.rec(seg, q))
        r = v1.check_inventory(recs, self.map, self.texts)
        self.assertEqual(r["coverage"], {"S0001": 1.0})
        self.assertEqual(v1.miss_rate(r["quotes"]), 0.0)

    def test_missing_segment_and_out_of_range(self):
        recs = [self.rec(self.map[1], "preamble")]                 # quote lives in segment 1, not 2
        r = v1.check_inventory(recs, self.map, self.texts)
        self.assertLess(r["coverage"]["S0001"], 1.0)
        self.assertEqual(r["quotes"], {"OUT-OF-RANGE": 1})

    def test_no_substantive_needs_reason_and_schema(self):
        bad = {"segment_id": self.map[0]["segment_id"], "source_id": "S0001",
               "result": "NO-SUBSTANTIVE-PROPOSITION", "reason": ""}
        r = v1.check_inventory([bad], self.map, self.texts)
        self.assertTrue(r["schema"])
        rr = self.rec(self.map[0], "preamble")
        rr["propositions"][0]["proposition_type"] = "OTHER-SUBSTANTIVE"
        self.assertTrue(v1.check_inventory([rr], self.map, self.texts)["schema"])

    def test_repair(self):
        out = v1.with_repair([{"item_id": "a", "q": 1}, {"item_id": "b"}],
                             [{"item_id": "a", "q": 2}, {"item_id": "b", "withdrawn": True}, {"item_id": "c"}],
                             "item_id")
        self.assertEqual(out, [{"item_id": "a", "q": 2}, {"item_id": "c"}])


class Matching(unittest.TestCase):
    def test_equalize_blinds_and_is_deterministic(self):
        outs = {s: [{"orig_id": f"{s}-1", "source_id": "S0001", "statement": "x", "quote": "q"}]
                for s in v1.SOURCES}
        l1, k1 = v1.equalize(outs, COMMIT)
        l2, k2 = v1.equalize(outs, COMMIT)
        self.assertEqual((l1, k1), (l2, k2))
        self.assertEqual(sorted(l1), ["X", "Y", "Z"])
        for items in l1.values():
            self.assertEqual(set(items[0]), {"item_id", "list", "source_id", "statement", "quote"})

    def test_sample_strata(self):
        cl = [{"cluster_id": f"C{i}", "list": "X", "source_id": "S0001"} for i in range(30)]
        cl += [{"cluster_id": f"D{i}", "list": "Y", "source_id": "S0001"} for i in range(3)]
        s = v1.draw_sample(cl, COMMIT)
        self.assertEqual(sum(x.startswith("C") for x in s), 9)
        self.assertEqual(sum(x.startswith("D") for x in s), 3)
        self.assertEqual(s, v1.draw_sample(cl, COMMIT))

    def test_kappa(self):
        d1 = [{"cluster_id": "C1", "other_list": "Y", "status": "MATCH", "target_cluster": "D1"},
              {"cluster_id": "C2", "other_list": "Y", "status": "NONE", "target_cluster": None}]
        same = v1.kappas(d1, d1, ["C1", "C2"])
        self.assertEqual(same["kappa_target"], 1.0)
        d2 = [dict(d1[0], target_cluster="D9"), d1[1]]
        diff = v1.kappas(d1, d2, ["C1", "C2"])
        self.assertLess(diff["kappa_target"], 1.0)
        self.assertEqual(diff["kappa_status"], 1.0)

    def test_capture(self):
        L = {"SEG": "X", "E1": "Y", "E2": "Z"}
        cl = [{"cluster_id": "Y1", "list": "Y"}, {"cluster_id": "Y2", "list": "Y"}]
        d = [{"cluster_id": "Y1", "other_list": "Z", "status": "MATCH"},
             {"cluster_id": "Y1", "other_list": "X", "status": "PARTIAL"},
             {"cluster_id": "Y2", "other_list": "Z", "status": "MATCH"},
             {"cluster_id": "Y2", "other_list": "X", "status": "NONE"}]
        c = v1.capture(cl, d, L)
        self.assertEqual((c["agreed"], c["strict"], c["lenient"]), (2, 0.0, 0.5))


class Decision(unittest.TestCase):
    base = {"integrity_ok": True, "schema_ok": True, "coverage_min": 1.0, "quote_miss_max": 0.0,
            "kappa_target": 0.7, "capture_lenient": 0.9}

    def d(self, **kw):
        return v1.decide(dict(self.base, **kw))[0]

    def test_rules(self):
        self.assertEqual(self.d(), "INSTRUMENTS-VALID")
        self.assertEqual(self.d(kappa_target=0.5), "NEEDS-REVISION")
        self.assertEqual(self.d(kappa_target=0.3), "NOT-USABLE")
        self.assertEqual(self.d(coverage_min=0.97), "NEEDS-REVISION")
        self.assertEqual(self.d(coverage_min=0.9), "NOT-USABLE")
        self.assertEqual(self.d(quote_miss_max=0.01), "NEEDS-REVISION")
        self.assertEqual(self.d(capture_lenient=0.4), "NOT-USABLE")
        self.assertEqual(self.d(schema_ok=False), "NEEDS-REVISION")
        self.assertEqual(self.d(integrity_ok=False), "NEEDS-REVISION")

    def test_guard_refuses_without_authorization(self):
        self.assertEqual(v1.main(["segment", "--commit", COMMIT]), 1)
        self.assertEqual(v1.main(["segment"]), 2)

    def test_canary_unique(self):
        self.assertEqual(len({v1.canary(COMMIT, r) for r in v1.RUNS}), len(v1.RUNS))


if __name__ == "__main__":
    unittest.main()
