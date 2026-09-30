#!/usr/bin/env python3
"""Track B pre-registered instrument (research/prereg_v1.py): each Model-0 generator preserves EXACTLY what it claims,
and the multiple-testing procedures behave as defined. Synthetic data only.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_research_prereg
"""
import collections
import importlib.util
import os
import random
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
_s = importlib.util.spec_from_file_location("prereg_v1", os.path.join(HERE, "..", "..", "research", "prereg_v1.py"))
P = importlib.util.module_from_spec(_s)
_s.loader.exec_module(P)


class NullModels(unittest.TestCase):
    def test_h1_keeps_first_and_each_multiset(self):
        rng = random.Random(1)
        seqs = [["FIRST", "EXTENDS", "RESTATES", "EXTENDS"], ["FIRST", "NARROWS", "EXTENDS"]]
        for _ in range(200):
            out = P.m0_h1(seqs, rng)
            for s, o in zip(seqs, out):
                self.assertEqual(o[0], "FIRST")
                self.assertEqual(collections.Counter(s), collections.Counter(o))

    def test_h5_curveball_preserves_row_and_column_sums(self):
        rng = random.Random(2)
        dims = [f"d{i}" for i in range(6)]
        rows = [set(rng.sample(dims, rng.randint(0, 5))) for _ in range(15)]
        col = collections.Counter(d for r in rows for d in r)
        for _ in range(100):
            out = P.m0_h5(rows, rng)
            self.assertEqual([len(r) for r in out], [len(r) for r in rows])
            self.assertEqual(collections.Counter(d for r in out for d in r), col)

    def test_h5_curveball_reaches_other_matrices(self):
        rng = random.Random(3)
        rows = [{"a"}, {"b"}, {"a", "b"}, set()]
        seen = {tuple(frozenset(r) for r in P.m0_h5(rows, rng)) for _ in range(200)}
        self.assertGreater(len(seen), 1)                     # not stuck at the observed matrix

    def test_h3_swaps_preserve_degrees_and_simplicity(self):
        rng = random.Random(4)
        nodes = list(range(12))
        edges = sorted({(a, b) for a in nodes for b in nodes if a != b and rng.random() < 0.2})
        outd, ind = collections.Counter(a for a, _ in edges), collections.Counter(b for _, b in edges)
        for _ in range(50):
            E = P.m0_h3(edges, rng)
            self.assertEqual(collections.Counter(a for a, _ in E), outd)
            self.assertEqual(collections.Counter(b for _, b in E), ind)
            self.assertEqual(len(set(E)), len(E))
            self.assertFalse(any(a == b for a, b in E))

    def test_h4_permutes_positions_within_object(self):
        rng = random.Random(5)
        dated = [{"lexical": "2026-01-01", "formal": "2026-02-01", "governance": "2026-03-01"}]
        self.assertEqual(P.t_h4(dated), 3)                   # perfectly ordered: 3 concordant pairs
        for _ in range(50):
            o = P.m0_h4(dated, rng)[0]
            self.assertEqual(sorted(o.values()), sorted(dated[0].values()))
            self.assertEqual(set(o), set(dated[0]))

    def test_permutation_p_is_valid(self):
        """Under the null (observed drawn like the null draws), P(p ≤ α) ≤ α (checked by simulation)."""
        rng = random.Random(6)
        hits = 0
        for _ in range(2000):
            draws = [rng.random() for _ in range(20)]
            hits += P.perm_p(draws[0], draws[1:]) <= 0.1
        self.assertLessEqual(hits / 2000, 0.1 + 0.02)


class MultipleTesting(unittest.TestCase):
    def test_holm(self):
        adj = P.holm({"a": 0.01, "b": 0.04, "c": 0.03})
        self.assertAlmostEqual(adj["a"], 0.03)
        self.assertAlmostEqual(adj["c"], 0.06)
        self.assertAlmostEqual(adj["b"], 0.06)             # monotone step-down

    def test_bh(self):
        # thresholds q·i/m = .0125, .025, .0375, .05 → the largest i with p_(i) ≤ q·i/m is 2 (0.04 > 0.0375)
        self.assertEqual(P.bh({"a": 0.001, "b": 0.02, "c": 0.04, "d": 0.5}, q=0.05), {"a", "b"})
        self.assertEqual(P.bh({"a": 0.001, "b": 0.02, "c": 0.03, "d": 0.5}, q=0.05), {"a", "b", "c"})
        self.assertEqual(P.bh({"a": 0.2, "b": 0.3}, q=0.05), set())


if __name__ == "__main__":
    unittest.main()
