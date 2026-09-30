#!/usr/bin/env python3
"""G-LOG-0102: after R7 activation, `prepare --check` verifies the recorded revision-3 BASE (historical base identity ≠
current execution revision identity) and that the R7 entries differ from the base ONLY in the R7 fields.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_prepare_r7base
"""
import copy
import os
import sys
import unittest

sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
import p3b_s5_prepare as P                         # noqa: E402

BASE = [{"batch_id": b, "run_id": f"{b}-R2", "tier": "S", "labels": ["x", "y"], "slice_sha256": {"x": "a" * 64},
         "contract_sha256": "c" * 64} for b in ("OB0001", "OB0002")]


def r7(rows):
    out = copy.deepcopy(rows)
    for r in out:
        r.update(run_id=f"{r['batch_id']}-R7", rev3_slice_sha256=r["slice_sha256"], slice_sha256={"x": "b" * 64},
                 r7_plan_sha256="d" * 64, legacy_ledger_sha256="e" * 64, legacy_dirs=[])
    return out


class R7Base(unittest.TestCase):
    def test_consistent(self):
        self.assertEqual(P.r7_base_consistency(BASE, r7(BASE)), [])

    def test_violations(self):
        cases = {
            "order": lambda rs: rs.reverse(),
            "missing batch": lambda rs: rs.pop(),
            "wrong run id": lambda rs: rs[0].update(run_id="OB0001-R8"),
            "rev3 slice hash changed": lambda rs: rs[0].update(rev3_slice_sha256={"x": "f" * 64}),
            "composition changed": lambda rs: rs[1].update(labels=["x"]),
            "contract changed": lambda rs: rs[1].update(contract_sha256="0" * 64),
        }
        for name, mut in cases.items():
            rows = r7(BASE)
            mut(rows)
            self.assertTrue(P.r7_base_consistency(BASE, rows), name)


if __name__ == "__main__":
    unittest.main()
