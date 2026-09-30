"""Tests: stability tooling (scripts/p3b_s5_stability.py). (a) on real pre-S5 data (P3B-SAMPLE-PLAN.jsonl and the hub
list only); (b)-(d) on synthetic fixtures."""
import random
import unittest

import p3b_s5_ops_testlib as T

sb = T.ops.load_script("p3b_s5_stability")
c = T.c


class RerunDraw(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.pop, cls.hubs = c.s5_population(), set(c.hubs())
        cls.d = sb.rerun_draw(cls.pop, cls.hubs)

    def test_97_labels_25_groups(self):
        self.assertEqual(self.d["n"], 97)
        self.assertEqual(self.d["n_groups"], 25)
        self.assertIn(sb.PURPOSIVE, self.d["groups"])

    def test_round_half_even_matters(self):
        sizes = [g["n_group"] for g in self.d["groups"].values()]
        self.assertEqual(sum(max(1, round(0.05 * n)) for n in sizes), 97)
        half_up = sum(max(1, int(0.05 * n + 0.5)) for n in sizes)
        self.assertEqual(half_up, 99)                                 # plan §K: round-half-up would give 99

    def test_population_assert_and_weights(self):
        labs = [x["working_label"] for x in self.d["labels"]]
        self.assertEqual(len(set(labs)), 97)
        self.assertTrue(all(l in self.pop and l not in self.hubs for l in labs))       # §M item 1
        pis = [g["inclusion_probability"] for g in self.d["groups"].values()]
        self.assertAlmostEqual(min(pis), 1 / 27)
        self.assertEqual(max(pis), 0.5)
        for x in self.d["labels"]:
            self.assertAlmostEqual(x["weight"] * x["inclusion_probability"], 1.0)

    def test_deterministic_and_seeded_rule(self):
        self.assertEqual(sb.rerun_draw(self.pop, self.hubs)["draw_sha256"], self.d["draw_sha256"])
        groups = sb.rerun_groups(self.pop, self.hubs)
        k0 = list(groups)[0]
        n0 = self.d["groups"][k0]["n_drawn"]
        expect = sorted(random.Random(c.int_seed(20260928, 25, 0)).sample(groups[k0], n0))
        self.assertEqual(sorted(x["working_label"] for x in self.d["labels"] if x["group"] == k0), expect)


def units(table, prefix="L", group="G"):
    """table: [(a, b, count)] -> one label per pair."""
    out, i = [], 0
    for a, b, n in table:
        for _ in range(n):
            out.append({"label": f"{prefix}{i:03d}", "pairs": [(a, b)], "group": group})
            i += 1
    return out


class Agreement(unittest.TestCase):
    def test_hand_computed_kappa_and_pabak(self):
        us = units([("Y", "Y", 40), ("Y", "N", 10), ("N", "Y", 5), ("N", "N", 45)])
        w = {u["label"]: 1.0 for u in us}
        g = {u["label"]: "G" for u in us}
        r = sb.agreement(us, g, w, B=200)
        self.assertAlmostEqual(r["weighted"]["agreement"], 0.85)
        self.assertAlmostEqual(r["weighted"]["kappa"], 0.70)
        self.assertAlmostEqual(r["weighted"]["pabak"], 0.70)
        self.assertAlmostEqual(r["unweighted"]["kappa"], 0.70)
        lo, hi = r["weighted"]["kappa_ci95"]
        self.assertTrue(lo < 0.70 < hi)
        self.assertIsNone(r["flag"])

    def test_design_weighting(self):
        us = units([("Y", "Y", 1)], "A", "GA") + units([("Y", "N", 1)], "B", "GB")
        w = {"A000": 3.0, "B000": 1.0}
        g = {"A000": "GA", "B000": "GB"}
        r = sb.agreement(us, g, w, B=50)
        self.assertAlmostEqual(r["weighted"]["agreement"], 0.75)
        self.assertAlmostEqual(r["unweighted"]["agreement"], 0.5)
        self.assertEqual(r["bootstrap"]["pooled_singletons"], 2)           # singleton groups pooled

    def test_dimensions_stay_with_label(self):
        us = [{"label": "L1", "pairs": [("F", "F"), ("F", "G")]}, {"label": "L2", "pairs": [("G", "G")]}]
        r = sb.agreement(us, {"L1": "G1", "L2": "G1"}, {"L1": 1.0, "L2": 1.0}, B=20)
        self.assertEqual((r["n_labels"], r["n_pairs"]), (2, 3))

    def test_deterministic_bootstrap_and_streams(self):
        us = units([("Y", "Y", 20), ("Y", "N", 7), ("N", "N", 13)])
        w, g = {u["label"]: 1.0 for u in us}, {u["label"]: ("G1" if i % 2 else "G2") for i, u in enumerate(us)}
        a = sb.agreement_all({"f1": us, "f2": us}, g, w, B=300)
        b = sb.agreement_all({"f1": us, "f2": us}, g, w, B=300)
        self.assertEqual(T.ops.canon_hash(a), T.ops.canon_hash(b))
        self.assertNotEqual(a["f1"]["weighted"]["kappa_ci95"], a["f2"]["weighted"]["kappa_ci95"])  # separate streams

    def test_method_instability_flag(self):
        self.assertEqual(sb.method_instability([0.0, 0.39]), "METHOD-INSTABILITY")
        self.assertIsNone(sb.method_instability([0.1, 0.40]))
        self.assertEqual(sb.method_instability(None), "KAPPA-NOT-COMPUTABLE")
        rng = random.Random(1)
        us = [{"label": f"L{i}", "pairs": [(rng.choice("AB"), rng.choice("AB"))]} for i in range(97)]
        r = sb.agreement(us, {u["label"]: f"G{i % 5}" for i, u in enumerate(us)}, {u["label"]: 1.0 for u in us}, B=500)
        self.assertEqual(r["flag"], "METHOD-INSTABILITY")

    def test_constant_field_kappa_undefined(self):
        us = units([("Y", "Y", 10)])
        r = sb.agreement(us, {u["label"]: "G" for u in us}, {u["label"]: 1.0 for u in us}, B=20)
        self.assertEqual(r["weighted"]["agreement"], 1.0)
        self.assertIsNone(r["weighted"]["kappa"])
        self.assertEqual(r["flag"], "KAPPA-NOT-COMPUTABLE")


class Reanalysis(unittest.TestCase):
    POP = {"lab-a": {}, "lab-b": {}, "lab-c": {}}

    def members(self, ids):
        return {u: ["lab-a", "lab-b"] if i % 2 else ["lab-c", "lab-a", "lab-b"] for i, u in enumerate(ids)}

    def test_draw_rule(self):
        ids = [f"U{i:04d}" for i in range(123)]
        d = sb.reanalysis_draw(ids, self.members(ids), self.POP)
        self.assertEqual(d["n_drawn"], 25)                                  # ceil(0.2 x 123)
        self.assertEqual(d["units"], sorted(random.Random(20260929).sample(sorted(ids), 25)))
        self.assertEqual(sb.reanalysis_draw(list(reversed(ids)), self.members(ids), self.POP)["draw_sha256"],
                         d["draw_sha256"])

    def test_blind_to_role(self):
        with self.assertRaises(c.S5Error):
            sb.reanalysis_draw([{"unit": "U1", "role": "CONTROL"}], {}, self.POP)
        with self.assertRaises(c.S5Error):
            sb.reanalysis_draw(["U1", "U1"], {"U1": ["lab-a"]}, self.POP)

    def test_population_assert(self):
        with self.assertRaises(c.S5Error):
            sb.reanalysis_draw(["U1", "U2"], {"U1": ["lab-a"], "U2": ["zz-fake-holdout-alpha"]}, self.POP)
        with self.assertRaises(c.S5Error) as cm:
            sb.reanalysis_draw(["U1", "U2"], {"U1": ["lab-a"]}, self.POP)       # a unit without members
        self.assertNotIn("U2", str(cm.exception))

    def test_population_assert_on_real_population(self):
        pop = c.s5_population()
        some = sorted(pop)[:6]
        d = sb.reanalysis_draw(["U1", "U2", "U3"], {"U1": some[:2], "U2": some[2:4], "U3": some[4:]})
        self.assertEqual(d["n_drawn"], 1)
        with self.assertRaises(c.S5Error):
            sb.reanalysis_draw(["U1"], {"U1": ["not-a-population-label-xyz"]})

    def test_wilson_and_jaccard(self):
        lo, hi = sb.wilson(5, 10)
        self.assertAlmostEqual(lo, 0.2366, places=4)
        self.assertAlmostEqual(hi, 0.7634, places=4)
        self.assertIsNone(sb.wilson(0, 0))
        self.assertEqual(sb.jaccard({"A", "B"}, {"B", "C"}), 1 / 3)
        self.assertIsNone(sb.jaccard(set(), set()))


if __name__ == "__main__":
    unittest.main()
