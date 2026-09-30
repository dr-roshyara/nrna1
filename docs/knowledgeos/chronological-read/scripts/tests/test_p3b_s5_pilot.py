"""Decomposition pilot (G-LOG-0052): the partition is deterministic, never splits a file, respects the budget except for
single oversize files, and covers the required set exactly (no duplicate, no omission)."""
import random
import unittest

import p3b_s5_ops_testlib  # noqa: F401  (sets the import path)
import p3b_s5_pilot as PI


class Partition(unittest.TestCase):
    def test_deterministic_exact_cover_budget(self):
        rnd = random.Random(7)
        sizes = {f"S{1000 + i:04d}": rnd.randint(1_000, 400_000) for i in range(60)}
        sizes["S9999"] = 900_000                                         # one file larger than the budget
        files = sorted(sizes)
        a, b = PI.partition(files, sizes), PI.partition(list(reversed(files)), sizes)
        self.assertEqual(a, b)                                            # input order does not matter
        flat = [s for u in a for s in u]
        self.assertEqual(sorted(flat), files)                             # exact cover, no duplicate, no omission
        for u in a:
            self.assertTrue(sum(sizes[s] for s in u) <= PI.BUDGET or len(u) == 1)
        self.assertIn(["S9999"], a)


if __name__ == "__main__":
    unittest.main()
