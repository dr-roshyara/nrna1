#!/usr/bin/env python3
"""R7 properties (exhaustive where the state space is finite; seeded randomized otherwise):
  • strong-Kleene batch composition over all 3^4 predicate assignments (DR-15);
  • PROGRAM-ACCEPTED over every small verdict configuration;
  • anchoring: a quote taken from inside witnessed byte intervals anchors; a unique quote from outside does not;
  • precedence is a strict partial order (see test_p3b_s5_r7_reconstruction, exhaustive over its metadata).
  cd scripts/tests && PYTHONPATH=.:.. python3 -m unittest test_p3b_s5_r7_properties
"""
import hashlib
import itertools
import os
import random
import sys
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, os.path.dirname(HERE))
import p3b_s5_r7_verify as V7                       # noqa: E402
import p3b_s5_r7_evidence as E                      # noqa: E402

r5 = E.r5


class Kleene(unittest.TestCase):
    def test_composition_exhaustive(self):
        for vals in itertools.product("TFU", repeat=4):
            p = dict(zip("UWER", vals))
            got = V7.compose(p)
            want = "BATCH-FAIL" if "F" in vals else "BATCH-UNDETERMINED" if "U" in vals else "BATCH-PASS"
            self.assertEqual(got, want, p)
        self.assertEqual(sum(V7.compose(dict(zip("UWER", v))) == "BATCH-PASS" for v in itertools.product("TFU", repeat=4)), 1)

    def test_program_accepted_exhaustive(self):
        batch_vals = ("BATCH-PASS", "BATCH-FAIL", "BATCH-UNDETERMINED")
        stats_vals = ("STATISTICS-VALID", "STATISTICS-NOT-ESTIMABLE", "STATISTICS-REJECTED")
        for n in (1, 2, 3):
            for bs in itertools.product(batch_vals, repeat=n):
                for s in stats_vals:
                    got = V7.program_accepted({f"OB{i}": b for i, b in enumerate(bs)}, s)
                    self.assertEqual(got, all(b == "BATCH-PASS" for b in bs) and s == "STATISTICS-VALID")


class CapabilityClosure(unittest.TestCase):
    def test_T94_every_tool_call_maps_to_exactly_one_class_or_a_violation(self):
        import p3b_s5_r7_witness as W
        import p3b_transcript_syntax as ts
        plan = {"labels": {"lab": {"runs": {"OB9070-R7-L01": {"role": "SINGLE", "files": ["S0701"]}}}}}
        owners = W.U.run_owner(plan)
        rx = W.U.canonical_reader("/abs/p3b_read_source.py")
        tools = [("Bash", {"command": "ls"}), ("Bash", {"command": "python3 -B /abs/p3b_read_source.py --run X"}),
                 ("Read", {"file_path": "/nowhere"}), ("Write", {"file_path": "/nowhere", "content": ""}),
                 ("Grep", {"pattern": "x"}), ("Glob", {"pattern": "*"}), ("Edit", {"file_path": "/x"}), ("Agent", {}),
                 ("WebFetch", {"url": "x"}), ("NotebookEdit", {}), (W.U.HANDBACK_TOOL, {"report": "x"})]
        for name, inp in tools:
            F = []
            c = ts.Call(id="t", name=name, input=inp, t_call="2026-10-01T09:00:00.000Z", t_result="2026-10-01T09:00:01.000Z",
                        text="ok", is_error=False, exit_code=0, index=0)
            ev, _ = W._classify([c], "OB9070-R7-L01", "OB9070", owners, {"entries": [], "persisted_outputs": "ALLOWED"},
                                rx, "/root", {}, "/tr", (), (), "a1", F)
            if name == W.U.HANDBACK_TOOL:
                self.assertEqual((ev, F), ([], []))                                   # the handback: allowed, no event
            else:
                self.assertEqual(len(ev), 1, name)                                    # exactly one classification
                self.assertTrue(F, name)                                              # every other call here is a violation


class AnchoringProperty(unittest.TestCase):
    def test_random_quotes(self):
        rng = random.Random(20260927)
        raw = "".join(f"unique line {i:06d} carries token {rng.randrange(10**9):09d}\n" for i in range(4000)).encode()
        pages = r5.byte_pages(raw)
        for trial in range(200):
            witnessed = sorted(rng.sample(range(1, len(pages) + 1), rng.randint(1, len(pages))))
            reads = [{"kind": "read", "run": "R", "source_id": "S", "page": k, "byte_start": pages[k - 1][0],
                      "byte_end": pages[k - 1][1], "verified": True} for k in witnessed]
            iv = E.witnessed_intervals(reads, "R", "S")
            a, b = pages[rng.choice(witnessed) - 1]
            i = rng.randrange(a, max(a + 1, b - 30))
            q = raw[i: i + 25].decode("utf-8", errors="ignore").strip()
            if q and raw.count(q.encode()) == 1:
                self.assertTrue(E.anchored(q, raw, iv))
            unw = [k for k in range(1, len(pages) + 1) if k not in witnessed]
            if unw:
                a, b = pages[rng.choice(unw) - 1]
                line_start = raw.find(b"\n", a) + 1
                q2 = raw[line_start: raw.find(b"\n", line_start)].decode()
                if line_start < b and raw.find(b"\n", line_start) <= b and raw.count(q2.encode()) == 1:
                    self.assertFalse(E.anchored(q2, raw, iv), (witnessed, q2))


if __name__ == "__main__":
    unittest.main()
