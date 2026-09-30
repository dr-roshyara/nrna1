#!/usr/bin/env python3
"""SLICE-VIEW (R7 v2.7, EG-2, G-LOG-0104): a deterministic, LOSSLESS, multi-line rendering of a frozen single-line slice
so that an agent's Read displays it exactly (RI-1: one long line is silently dropped by the harness).
Properties: parse(view(s)) = s and canon(parse(view(s))) = the slice bytes; every line short; no raw line-separator
code points; deterministic; any tampering is detected.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_slice_view
"""
import json
import os
import random
import sys
import unittest

sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
import p3b_s5_r7_universe as U                   # noqa: E402

canon = lambda o: json.dumps(o, sort_keys=True, separators=(",", ":"), ensure_ascii=False)
HAZARD = "a\"\\\n\t\r  \u0085\x0b\x0c\x1c\x00é∀𝔸 "


def rand_json(rng, depth=0):
    r = rng.random()
    if depth > 3 or r < 0.35:
        return rng.choice([None, True, False, 0, -7, 3.25, 10 ** 20, "",
                           "".join(rng.choice(HAZARD) for _ in range(rng.randint(0, 3000)))])
    if r < 0.65:
        return {rng.choice(["0", "a", "k.b", "[x]", "é", " ", "", "~/"]) + str(i): rand_json(rng, depth + 1)
                for i in range(rng.randint(0, 4))}
    return [rand_json(rng, depth + 1) for _ in range(rng.randint(0, 4))]


class SliceView(unittest.TestCase):
    def test_roundtrip_property(self):
        rng = random.Random(20260928)
        for _ in range(400):
            x = rand_json(rng)
            text = canon(x)
            v = U.slice_view(text)
            self.assertEqual(U.slice_view_parse(v), x)
            self.assertEqual(canon(U.slice_view_parse(v)), text)

    def test_lines_are_short_and_have_no_raw_separators(self):
        x = {"family_md": "".join(HAZARD[i % len(HAZARD)] for i in range(250_000)), "rows": ["r" * 5000] * 3, "e": {}, "l": []}
        v = U.slice_view(canon(x))
        lines = v.split("\n")
        self.assertLessEqual(max(len(l) for l in lines), U.SLICE_VIEW_MAX_LINE)
        for ch in ("\r", " ", " ", "\u0085", "\x0b", "\x0c", "\x1c", "\x1d", "\x1e"):
            self.assertNotIn(ch, v)
        self.assertEqual(U.slice_view_parse(v), x)

    def test_deterministic(self):
        x = {"b": [1, {"c": "x" * 2500}], "a": ""}
        self.assertEqual(U.slice_view(canon(x)), U.slice_view(canon(x)))

    def test_tampering_is_detected(self):
        x = {"a": "x" * 2500, "b": [1, 2]}
        v = U.slice_view(canon(x)).split("\n")
        chunk_lines = [i for i, l in enumerate(v) if "\tS " in l]
        mutants = [
            v[:chunk_lines[1]] + v[chunk_lines[1] + 1:],                                   # a chunk removed
            v[:chunk_lines[0]] + [v[chunk_lines[1]], v[chunk_lines[0]]] + v[chunk_lines[1] + 1:],   # chunks reordered
            [l.replace('"xxxx', '"yxxx', 1) for l in v],                                    # content changed
            v[:1] + v[2:],                                                                  # a leaf removed
            ["# not a slice view"] + v[1:],                                                 # wrong header
        ]
        for m in mutants:
            try:
                ok = U.slice_view_parse("\n".join(m)) == x
            except ValueError:
                ok = False
            self.assertFalse(ok)

    def test_verifier_check(self):
        t = canon({"a": "x" * 1500})
        self.assertEqual(U.slice_view_violations(t, U.slice_view(t), "OB0001/lab"), [])
        self.assertTrue(U.slice_view_violations(t, U.slice_view(t).replace("x", "y", 1), "OB0001/lab"))
        self.assertTrue(U.slice_view_violations(t, None, "OB0001/lab"))

    def test_input_manifest_carries_the_view(self):
        self.assertIn("SLICE-VIEW", U.CATEGORIES)


if __name__ == "__main__":
    unittest.main()
