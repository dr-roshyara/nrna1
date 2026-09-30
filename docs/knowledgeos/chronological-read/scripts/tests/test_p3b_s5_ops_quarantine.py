"""Tests: quarantine scanner and O-22 extract builder (scripts/p3b_s5_quarantine_scan.py)."""
import io
import json
import os
import unittest
from contextlib import redirect_stdout, redirect_stderr

import p3b_s5_ops_testlib as T

qs = T.ops.load_script("p3b_s5_quarantine_scan")
c = T.c
S = T.FAKE_SETS


class ScanText(unittest.TestCase):
    def test_label_name_hit(self):
        self.assertEqual(qs.scan_text("see zz-fake-holdout-alpha here", None, S), (1, 0))

    def test_trailing_dot_prose_hit(self):
        self.assertEqual(qs.scan_text("the label zz-fake-holdout-alpha.", None, S), (1, 0))

    def test_label_with_inner_dot(self):
        self.assertEqual(qs.scan_text("x zz-fake-holdout.beta y", None, S), (1, 0))

    def test_longer_label_is_not_a_hit(self):
        self.assertEqual(qs.scan_text("zz-fake-holdout-alpha-2 and pre-zz-fake-holdout-alpha", None, S), (0, 0))

    def test_holdout_sid_hit_and_allowed_mention(self):
        self.assertEqual(qs.scan_text("cites S9990", None, S), (0, 1))
        self.assertEqual(qs.scan_text("cites S9991", "disc-label-x", S), (0, 0))   # listed §4 mention
        self.assertEqual(qs.scan_text("cites S9991", "other-label", S), (0, 1))
        self.assertEqual(qs.scan_text("cites S1234", None, S), (0, 0))


    def test_sid_range_spanning_holdout_counts(self):             # G-LOG-0045 item 3 (strict range rule)
        for s in ("S9980-S9995", "S9980 \u2013 S9995", "S9980\u2014S9995", "S9980..S9995", "S9980 to S9995",
                  "S9980-9995", "S9995-S9980"):
            self.assertEqual(qs.scan_text(f"see {s} here", None, S), (0, 2), s)
        self.assertEqual(qs.scan_text("S9990-S9990", None, S), (0, 1))            # a degenerate range is a citation
        self.assertEqual(qs.scan_text("S9980-S9989 and S9992-S9999", None, S), (0, 0))   # does not span
        self.assertEqual(qs.scan_text("S9980-S9995", "disc-label-x", S), (0, 1))  # the §4 mention stays allowed
        self.assertEqual(qs.scan_text("S9990 and S9980-S9995", None, S), (0, 2))  # explicit + range: counted once each
        self.assertEqual(qs.scan_text("XS9980-S9995", None, S), (0, 0))           # not an S-id token
        for s in ("S9980...S9995", "S9980\u2026S9995", "S9980--S9995", "S9980\u2212S9995", "S9980\u2011S9995",
                  "S9980\u2012S9995", "S9980\u2015S9995", "S9980 TO S9995", "S9980 through S9995", "S9980-95",
                  "S9980\\u2013S9995", "S9970-S9980-S9995"):                  # review round (G-LOG-0045): no false negatives
            self.assertEqual(qs.scan_text(f"see {s} here", None, S), (0, 2), s)
        self.assertEqual(qs.scan_text("S9980-85 and S9992-99", None, S), (0, 0))     # abbreviated, not spanning
        for s in ("S9980 - - S9995", "S9980 . . S9995", "S9980 up to S9995", "S9980 through to S9995", "S9980 bis S9995",
                  "S9980\u2014to\u2014S9995", "S9980~S9995", "S9980\u301cS9995", "S9980\uff5eS9995",
                  "between S9980 and S9995", "S9980 bis zu S9995", "S9980 BIS ZU S9995",
                  "from S9980 to S9995", "S9980\u00ad-S9995", "S9980\u200b-S9995", "S9980\u2043S9995",
                  "S9980\u2e17S9995", "S9980\\ufe58S9995", "S9980\\uff0dS9995", "S9980\\u2e3aS9995"):   # review round 2
            self.assertEqual(qs.scan_text(f"see {s} here", None, S), (0, 2), s)
        for s in ("S9980, S9995", "S9980 and S9995", "S9980; S9995", "S9980. S9995 opens", "S9980 S9995",
                  "S9980/S9995", "S9980:S9995", "S9980->S9995", "S9980=>S9995", "S9980→S9995"):
            # lists, and '/', ':' and arrows (NOT ranges by human ruling, G-LOG-0045): explicit citations only
            self.assertEqual(qs.scan_text(s, None, S), (0, 0), s)


class ScanFiles(unittest.TestCase):
    def setUp(self):
        self.d = T.tmpdir("qscan")
        T.write_jsonl(os.path.join(self.d, "recs.jsonl"), [
            {"working_label": "disc-a", "text": "clean"},
            {"working_label": "disc-b", "text": "mentions zz-fake-holdout-alpha"},
            {"working_label": "disc-label-x", "text": "cites S9991"},
            {"working_label": "disc-c", "rows": [{"source_id": "S9990"}]}])
        with open(os.path.join(self.d, "notes.md"), "w") as f:
            f.write("line one\nabout zz-fake-holdout.beta.\nline three\n")
        self.q = os.path.join(self.d, "Q.jsonl")

    def test_hits_counts_only_and_indices(self):
        n, hits = qs.scan_paths([self.d], S)
        self.assertEqual(n, 4 + 4)
        self.assertEqual([(h["path"], h["record_index"]) for h in hits],        # outside-repository paths redacted
                         [("<unlisted path #1>", 1), ("<unlisted path #2>", 1), ("<unlisted path #2>", 3)])

    def test_cli_exit_nonzero_and_quarantine_has_no_names(self):
        with T.fake_holdout(), redirect_stdout(io.StringIO()) as out:
            rc = qs.main(["scan", self.d, "--quarantine", self.q])
        self.assertEqual(rc, 1)
        text = T.read(self.q) + out.getvalue()
        for name in list(T.FAKE_H) + list(T.FAKE_HF):
            self.assertNotIn(name, text)
        recs = T.read_jsonl(self.q)
        self.assertEqual(len(recs), 3)
        self.assertTrue(all({"path", "record_index", "n_label_names", "n_holdout_sids"} <= set(r) for r in recs))

    def test_clean_exits_zero_and_writes_nothing(self):
        clean = os.path.join(self.d, "clean")
        T.write_jsonl(os.path.join(clean, "a.jsonl"), [{"working_label": "disc-a", "x": "S1234"}])
        with T.fake_holdout(), redirect_stdout(io.StringIO()):
            rc = qs.main(["scan", clean, "--quarantine", self.q])
        self.assertEqual(rc, 0)
        self.assertFalse(os.path.exists(self.q))

    def test_append_only(self):
        with T.fake_holdout(), redirect_stdout(io.StringIO()):
            qs.main(["scan", self.d, "--quarantine", self.q])
            first = T.read(self.q)
            qs.main(["scan", self.d, "--quarantine", self.q])
        self.assertTrue(T.read(self.q).startswith(first))

    def test_forbidden_input_refused_before_reading(self):
        forbidden = os.path.join(c.CR, c.FORBIDDEN[0])
        with self.assertRaises(c.S5Error):
            qs.scan_paths([forbidden], S)
        copy = os.path.join(self.d, os.path.basename(c.FORBIDDEN[1]))
        open(copy, "w").close()
        with self.assertRaises(c.S5Error):
            qs.scan_paths([copy], S)

    def test_deterministic(self):
        self.assertEqual(qs.scan_paths([self.d], S), qs.scan_paths([self.d], S))

    def test_seal_not_sealed_aborts(self):
        with T.seal_state("UNSEALED"), redirect_stdout(io.StringIO()):
            self.assertEqual(qs.main(["scan", self.d, "--quarantine", self.q]), 1)
        self.assertFalse(os.path.exists(self.q))


class PathLeak(unittest.TestCase):
    """M2 (G-LOG-0042): the scanner never prints or stores a path that could be a hold-out label name."""

    def test_dir_named_after_holdout_label_not_printed(self):
        name = sorted(T.FAKE_H)[0]
        d = os.path.join(T.tmpdir("m2"), name)
        T.write_jsonl(os.path.join(d, name + ".jsonl"), [{"working_label": "disc-a", "x": "mentions " + name}])
        q = os.path.join(os.path.dirname(d), "Q.jsonl")
        with T.fake_holdout(), redirect_stdout(io.StringIO()) as out, redirect_stderr(io.StringIO()) as err:
            rc = qs.main(["scan", d, "--quarantine", q])
        self.assertEqual(rc, 1)
        # the record text carries the name as its hit, but no output or stored path may show it
        self.assertNotIn(name, out.getvalue() + err.getvalue() + T.read(q))

    def test_refused_path_is_numbered_not_echoed(self):
        pop = c.s5_population()
        fam = os.path.join(c.CR, "20-FAMILIES")
        non_s5 = next((f for f in sorted(os.listdir(fam)) if f.endswith(".md") and f[:-3] not in pop), None)
        self.assertIsNotNone(non_s5)
        target = os.path.join(fam, non_s5)
        with redirect_stdout(io.StringIO()) as out, redirect_stderr(io.StringIO()) as err:
            rc = qs.main(["scan", os.path.join(c.CR, "P3B-SAMPLE-PLAN.jsonl"), target, "--no-write"])
        self.assertEqual(rc, 1)
        text = out.getvalue() + err.getvalue()
        self.assertIn("<refused path #2>", text)
        self.assertNotIn(non_s5[:-3], text)
        self.assertNotIn("20-FAMILIES", text)

    def test_tmp_symlink_to_forbidden_repo_file_refused(self):
        d = T.tmpdir("symlink")
        link = os.path.join(d, "innocent.jsonl")
        self.assertTrue(c.FORBIDDEN[4].endswith("TIERS.jsonl"))
        os.symlink(os.path.join(c.CR, c.FORBIDDEN[4]), link)          # the census tier file
        with self.assertRaises(c.S5Error) as cm:
            T.ops.check_paths([link])
        self.assertNotIn("TIERS", str(cm.exception))
        with self.assertRaises(c.S5Error):
            qs.scan_paths([link], S)
        self.assertTrue(T.ops.inside_cr(link))

    def test_tmp_symlink_to_allowlisted_repo_file_judged_by_target(self):
        d = T.tmpdir("symlink-ok")
        link = os.path.join(d, "hubs.jsonl")
        os.symlink(os.path.join(c.CR, c.HUBS), link)
        self.assertTrue(T.ops.check_paths([link]))
        self.assertEqual(T.ops.display(link, 1), c.HUBS)


class FlagsExtract(unittest.TestCase):
    def test_drop_lines_and_rescan_clean(self):
        src = "# flags\nok line S1234\nbad zz-fake-holdout-alpha\nbad S9991 (no mention context)\nlast.\n"
        text, n, k, d = qs.build_flags_extract(src, S)
        self.assertEqual((n, k, d), (6, 4, 2))
        self.assertEqual(qs.scan_text(text, None, S), (0, 0))
        self.assertNotIn("zz-fake", text)

    def test_real_extract_to_tmp_scans_clean(self):
        out = os.path.join(T.tmpdir("flags"), "09-ORCHESTRATOR-FLAGS.discovery.md")
        with redirect_stdout(io.StringIO()) as buf:
            rc = qs.main(["extract-flags", "--out", out])
        self.assertEqual(rc, 0)
        rep = json.loads(buf.getvalue())
        self.assertEqual(rep["post_scan_hits"], 0)
        self.assertEqual(rep["kept_lines"] + rep["dropped_lines"], rep["source_lines"])

    def test_repo_write_needs_flag(self):
        target = os.path.join(c.CR, qs.FLAGS_EXTRACT)
        before = os.stat(target).st_mtime_ns if os.path.exists(target) else None
        with redirect_stdout(io.StringIO()):
            self.assertEqual(qs.main(["extract-flags", "--out", target]), 1)
        self.assertEqual(os.stat(target).st_mtime_ns if os.path.exists(target) else None, before)


if __name__ == "__main__":
    unittest.main()
