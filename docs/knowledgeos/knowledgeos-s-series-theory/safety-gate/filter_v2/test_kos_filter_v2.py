#!/usr/bin/env python3
"""Fake-fixture tests for filter v2 r4. Every seal, label, ID, pin and file here is FAKE. No real seal is ever loaded.

    python3 -I -W error::ResourceWarning test_kos_filter_v2.py      (run from this directory)
"""
import hashlib
import os
import sys
import tempfile
import unittest

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
import kos_filter_v2 as F  # noqa: E402

FAKE_SEALED_BYTES = b"FAKE sealed body\n"


def put(path, data):
    with open(path, "wb" if isinstance(data, bytes) else "w") as f:
        f.write(data)


def fake_rows():
    return [{"source_id": "S9001", "content_sha256": hashlib.sha256(FAKE_SEALED_BYTES).hexdigest(),
             "path": "docs/fake/sealed_one.md", "historical_path": "old/place/sealed_one.md"},
            {"source_id": "S9002", "content_sha256": "0" * 64, "path": ".hidden/sealed_two.md"}]


def fake_seal(labels=None, rows=None, **over):
    labels = ["Fake Hold Label", "zeta_secret_topic", "Тайна"] if labels is None else labels
    rows = fake_rows() if rows is None else rows
    seal = {"holdout_labels": labels, "holdout_files": rows,
            "holdout_label_list_sha256": F.label_list_hash(labels),
            "holdout_file_list_sha256": F.file_list_hash(rows)}
    seal.update(over)
    return seal


def pins_for(seal):
    return {"label_list_sha256": F.label_list_hash(seal["holdout_labels"]),
            "file_list_sha256": F.file_list_hash(seal["holdout_files"])}


def view(**kw):
    s = fake_seal(**kw)
    return F.verify_integrity(s, pins_for(s))


class Integrity(unittest.TestCase):
    def test_good_seal_passes(self):
        view()

    def test_F1_bad_label_hash_fails_closed(self):
        s = fake_seal(holdout_label_list_sha256="bad")
        with self.assertRaises(F.FailClosed):
            F.verify_integrity(s, pins_for(s))

    def test_bad_file_hash_fails_closed(self):
        s = fake_seal(holdout_file_list_sha256="bad")
        with self.assertRaises(F.FailClosed):
            F.verify_integrity(s, pins_for(s))

    def test_P9_truncated_seal_with_recomputed_hashes_fails_against_pin(self):
        full = fake_seal()
        pins = pins_for(full)
        truncated = fake_seal(rows=fake_rows()[:1])            # self-consistent hashes, wrong content
        with self.assertRaises(F.FailClosed):
            F.verify_integrity(truncated, pins)

    def test_missing_pin_fails_closed(self):
        with self.assertRaises(F.FailClosed):
            F.verify_integrity(fake_seal(), None)

    def test_F9_unsealed_fails_closed(self):
        for s in (fake_seal(rows=[]), fake_seal(labels=[])):
            with self.assertRaises(F.FailClosed):
                F.verify_integrity(s, pins_for(s))

    def test_P1_label_empty_after_normalization_fails_closed(self):
        for labels in (["???"], ["x"], ["Fake Hold Label", "--"]):
            s = fake_seal(labels=labels)
            with self.assertRaises(F.FailClosed):
                F.verify_integrity(s, pins_for(s))

    def test_P12_malformed_rows_fail_closed(self):
        bad = [{"source_id": "", "content_sha256": "0" * 64, "path": "a"},
               {"source_id": "S1", "path": "a"},
               {"source_id": "S1", "content_sha256": "xyz", "path": "a"},
               {"source_id": "S1", "content_sha256": "0" * 64}]
        for r in bad:
            s = fake_seal(rows=[r])
            with self.assertRaises(F.FailClosed):
                F.verify_integrity(s, pins_for(s))

    def test_production_refuses(self):
        with self.assertRaises(SystemExit):
            F.production_main([])


class Normalization(unittest.TestCase):
    def test_N1_dot_prefixed_paths_not_mangled(self):
        self.assertEqual(F.norm(".claude/x.md"), ".claude/x.md")
        self.assertEqual(F.norm("./a/b.md"), "a/b.md")
        self.assertNotEqual(F.norm(".a/x"), F.norm("a/x"))
        self.assertNotEqual(F.norm("../x"), "x")

    def test_N1_outside_root_fails_closed(self):
        with tempfile.TemporaryDirectory() as root, tempfile.TemporaryDirectory() as other:
            p = os.path.join(other, "x.md")
            put(p, "x")
            with self.assertRaises(F.FailClosed):
                F.rel_in_root(p, root)

    def test_F8_relative_output_path_fails_closed(self):
        with self.assertRaises(F.FailClosed):
            F.write_allowed("relative.tsv", ["a"], [])


class CorpusRule(unittest.TestCase):
    def setUp(self):
        self.v = view()

    def test_withheld_by_sha_even_if_moved(self):
        self.assertTrue(F.corpus_withheld(self.v, "docs/elsewhere/copy.md", hashlib.sha256(FAKE_SEALED_BYTES).hexdigest()))

    def test_P8_uppercase_sha_matches(self):
        self.assertTrue(F.corpus_withheld(self.v, "docs/x.md", hashlib.sha256(FAKE_SEALED_BYTES).hexdigest().upper()))

    def test_withheld_by_path_even_if_edited(self):
        self.assertTrue(F.corpus_withheld(self.v, "docs/fake/sealed_one.md", "f" * 64))

    def test_P11_withheld_by_historical_path(self):
        self.assertTrue(F.corpus_withheld(self.v, "old/place/sealed_one.md", "f" * 64))

    def test_hidden_dir_path_matches_exactly(self):
        self.assertTrue(F.corpus_withheld(self.v, ".hidden/sealed_two.md", "e" * 64))
        self.assertFalse(F.corpus_withheld(self.v, "hidden/sealed_two.md", "e" * 64))

    def test_F7_withheld_by_source_id_variants(self):
        for sid in ("s9002", "S-9002", "S09002", "Ｓ９００２"):
            self.assertTrue(F.corpus_withheld(self.v, "docs/other.md", "d" * 64, source_id=sid), sid)

    def test_P6_path_naming_a_label_is_withheld(self):
        self.assertTrue(F.corpus_withheld(self.v, "docs/fake-hold-label.md", "c" * 64))

    def test_content_quoting_id_or_label_is_withheld(self):
        self.assertTrue(F.corpus_withheld(self.v, "docs/ok.md", "c" * 64, text="see S9001"))
        self.assertTrue(F.corpus_withheld(self.v, "docs/ok.md", "c" * 64, text="about fake hold label"))

    def test_allowed_file(self):
        self.assertFalse(F.corpus_withheld(self.v, "docs/ok.md", "c" * 64, source_id="S1234", text="kernel notes"))

    def test_P14_scan_corpus_uses_source_id_and_encoding(self):
        with tempfile.TemporaryDirectory() as root:
            a = os.path.join(root, "ok.md"); put(a, "fine")
            b = os.path.join(root, "copy.md"); put(b, FAKE_SEALED_BYTES)
            c = os.path.join(root, "byid.md"); put(c, "fine too")
            d = os.path.join(root, "u16.md"); put(d, "clean".encode("utf-16"))
            allowed, withheld = F.scan_corpus(self.v, root, [(a, None), (b, None), (c, "S9001"), (d, None)])
            self.assertEqual(([r for r, _ in allowed], withheld), (["ok.md"], 3))


class FamilyRule(unittest.TestCase):
    def setUp(self):
        self.v = view()

    def test_F2_case_punctuation_unicode_variants(self):
        for t in ["the FAKE-HOLD-LABEL group", "fake_hold_label", "Fake  Hold\tLabel", "ZETA SECRET TOPIC",
                  "FakeHoldLabel", "ＦＡＫＥ ＨＯＬＤ ＬＡＢＥＬ", "Fa​ke Hold La­bel", "тайна here"]:
            self.assertTrue(F.family_withheld(self.v, "neutral.md", t), t)

    def test_F3_label_inside_stem_or_directory(self):
        self.assertTrue(F.family_withheld(self.v, "notes-fake-hold-label-2026.md", "neutral text"))
        self.assertTrue(F.family_withheld(self.v, os.path.join("fake-hold-label", "n.md"), "neutral text"))

    def test_F6_source_id_quotation_variants(self):
        for t in ["cites S9001 at row 4", "see s9002.", "(S9001_v2)", "xS9001", "Ｓ９００１",
                  "S​9001", "S 9001", "S-9001", "S09001"]:
            self.assertTrue(F.family_withheld(self.v, "neutral.md", t), t)

    def test_P5_source_id_in_filename(self):
        self.assertTrue(F.family_withheld(self.v, "S9001-notes.md", "clean"))

    def test_source_id_prefix_of_longer_id_not_matched(self):
        self.assertFalse(F.family_withheld(self.v, "neutral.md", "cites S90010 only"))

    def test_P4_undecodable_family_is_withheld(self):
        self.assertTrue(F.family_withheld(self.v, "u16.md", F.decode_or_none("Fake Hold Label".encode("utf-16"))))
        self.assertIsNone(F.decode_or_none(b"\xff\xfe\x00"))

    def test_clean_family_allowed(self):
        self.assertFalse(F.family_withheld(self.v, "kernel-notes.md", "about S1234 and labels"))

    def test_F4_recursive_scan_counts_subdirs(self):
        with tempfile.TemporaryDirectory() as root:
            fam = os.path.join(root, "fam")
            os.makedirs(os.path.join(fam, "sub", "deeper"))
            put(os.path.join(fam, "a.md"), "clean")
            put(os.path.join(fam, "sub", "b.md"), "quotes S9001")
            put(os.path.join(fam, "sub", "deeper", "c.md"), "clean too")
            allowed, withheld, subdirs = F.scan_families(self.v, fam, root)
            self.assertEqual((sorted(r for r, _ in allowed), withheld, subdirs),
                             (["a.md", os.path.join("sub", "deeper", "c.md")], 1, 2))

    def test_P7_family_sha_copy_withheld(self):
        with tempfile.TemporaryDirectory() as root:
            fam = os.path.join(root, "fam"); os.makedirs(fam)
            put(os.path.join(fam, "copy.md"), FAKE_SEALED_BYTES)
            self.assertEqual(F.scan_families(self.v, fam, root)[1], 1)

    def test_P7_symlink_file_outside_root_fails_closed(self):
        with tempfile.TemporaryDirectory() as root, tempfile.TemporaryDirectory() as other:
            fam = os.path.join(root, "fam"); os.makedirs(fam)
            target = os.path.join(other, "t.md"); put(target, "clean")
            os.symlink(target, os.path.join(fam, "link.md"))
            with self.assertRaises(F.FailClosed):
                F.scan_families(self.v, fam, root)

    def test_P10_symlinked_directory_fails_closed(self):
        with tempfile.TemporaryDirectory() as root:
            fam = os.path.join(root, "fam"); real = os.path.join(root, "real")
            os.makedirs(fam); os.makedirs(real)
            os.symlink(real, os.path.join(fam, "linkdir"))
            with self.assertRaises(F.FailClosed):
                F.scan_families(self.v, fam, root)


class R3Conditions(unittest.TestCase):
    def setUp(self):
        self.v = view()

    def test_N1_combining_marks_and_accents(self):
        for t in ["Fake Hold Labeĺ", "Fake Hold Labél", "Fake Hold Labél"]:
            self.assertTrue(F.family_withheld(self.v, "neutral.md", t), t)

    def test_N2_encoded_and_spread_forms(self):
        for t in ["Fake%20Hold%20Label", "Fake&#32;Hold&#32;Label", "S%209001", "S -- 9001", "S\n\n\n9001",
                  "S٩٠٠١"]:
            self.assertTrue(F.family_withheld(self.v, "neutral.md", t), t)

    def test_N3_unreadable_subdirectory_fails_closed(self):
        if hasattr(os, "geteuid") and os.geteuid() == 0:
            self.skipTest("root can read chmod-0 directories")
        with tempfile.TemporaryDirectory() as root:
            fam = os.path.join(root, "fam"); locked = os.path.join(fam, "locked")
            os.makedirs(locked)
            put(os.path.join(locked, "x.md"), "S9001")
            os.chmod(locked, 0)
            try:
                with self.assertRaises(F.FailClosed):
                    F.scan_families(self.v, fam, root)
            finally:
                os.chmod(locked, 0o700)

    def test_fifo_fails_closed(self):
        with tempfile.TemporaryDirectory() as root:
            fam = os.path.join(root, "fam"); os.makedirs(fam)
            os.mkfifo(os.path.join(fam, "pipe.md"))
            with self.assertRaises(F.FailClosed):
                F.scan_families(self.v, fam, root)

    def test_I2_missing_values_hash_as_empty_string(self):
        r = {"source_id": "S1", "content_sha256": "0" * 64, "path": "a"}
        self.assertEqual(F.file_list_hash([r]),
                         hashlib.sha256(F.json.dumps([["S1", "0" * 64, "a", "", ""]]).encode()).hexdigest())


class R4Conditions(unittest.TestCase):
    """Findings of the fresh r3 internal pre-review (FAIL, 7 fail-open paths)."""

    def setUp(self):
        self.v = view()

    def test_r3_1_json_qp_and_repeated_encodings(self):
        import json as _json
        for t in [_json.dumps({"k": "Тайна"}), "Fake\\u0020Hold Label", "Fake=20Hold=20Label",
                  "Fake%2520Hold%2520Label", "S%25209001", "Fake&#37;20Hold&#37;20Label"]:
            self.assertTrue(F.family_withheld(self.v, "neutral.md", t), t)

    def test_r3_2_hyphenated_id_prefix(self):
        rows = fake_rows() + [{"source_id": "F-LOG-0308", "content_sha256": "1" * 64, "path": "docs/x/y.md"}]
        v = view(rows=rows)
        for t in ["see F-LOG-0308", "flog308", "F LOG 0308"]:
            self.assertTrue(F.family_withheld(v, "neutral.md", t), t)
        self.assertTrue(F.corpus_withheld(v, "docs/F-LOG-0308-notes.md", "c" * 64))

    def test_r3_3_hangul_filler_and_braille_blank(self):
        for t in ["FakeㅤHold Label", "FakeᅠHold Label", "Fake⠀Hold Label"]:
            self.assertTrue(F.family_withheld(self.v, "neutral.md", t), repr(t))

    def test_r3_4_digit_before_id_now_matches(self):
        self.assertTrue(F.family_withheld(self.v, "neutral.md", "ref2S9001"))

    def test_r3_4_separator_boundary_is_8(self):
        self.assertTrue(F.family_withheld(self.v, "neutral.md", "S" + "-" * 8 + "9001"))
        self.assertFalse(F.family_withheld(self.v, "neutral.md", "S" + "-" * 9 + "9001"))   # declared residual R-3

    def test_r3_5_non_canonical_seal_paths_fail_closed(self):
        for bad in ["/abs/root/docs/s.md", "docs\\s.md", "docs/../s.md", unicodedata_nfd("docs/é.md")]:
            rows = [{"source_id": "S9001", "content_sha256": "1" * 64, "path": bad}]
            s = fake_seal(rows=rows)
            with self.assertRaises(F.FailClosed):
                F.verify_integrity(s, pins_for(s))

    def test_r3_6_no_name_in_exception_chain(self):
        import traceback
        with tempfile.TemporaryDirectory() as root:
            fam = os.path.join(root, "fam"); os.makedirs(fam)
            os.symlink(os.path.join(root, "missing"), os.path.join(fam, "Fake Hold Label.md"))   # dangling
            try:
                F.scan_families(self.v, fam, root)
                self.fail("expected FailClosed")
            except F.FailClosed as e:
                tb = "".join(traceback.format_exception(e))
                self.assertNotIn("Fake Hold Label", tb)
        if not (hasattr(os, "geteuid") and os.geteuid() == 0):
            with tempfile.TemporaryDirectory() as root:
                fam = os.path.join(root, "fam"); locked = os.path.join(fam, "fake-hold-label-dir")
                os.makedirs(locked)
                os.chmod(locked, 0)
                try:
                    F.scan_families(self.v, fam, root)
                    self.fail("expected FailClosed")
                except F.FailClosed as e:
                    self.assertNotIn("fake-hold-label-dir", "".join(traceback.format_exception(e)))
                finally:
                    os.chmod(locked, 0o700)

    def test_r3_7_write_allowed_no_overwrite_no_symlink_no_injection(self):
        with tempfile.TemporaryDirectory() as d:
            precious = os.path.join(d, "precious"); put(precious, "keep")
            link = os.path.join(d, "out.tsv"); os.symlink(precious, link)
            with self.assertRaises(F.FailClosed):
                F.write_allowed(link, ["a"], [])
            existing = os.path.join(d, "exists.tsv"); put(existing, "x")
            with self.assertRaises(F.FailClosed):
                F.write_allowed(existing, ["a"], [])
            with self.assertRaises(F.FailClosed):
                F.write_allowed(os.path.join(d, "new.tsv"), ["path"], [["bad\nrow"]])
            F.write_allowed(os.path.join(d, "ok.tsv"), ["path"], [["fine"]])
            with open(precious) as f:
                self.assertEqual(f.read(), "keep")

    def test_r3_8_non_regular_corpus_items_fail_closed(self):
        with tempfile.TemporaryDirectory() as root:
            sub = os.path.join(root, "adir"); os.makedirs(sub)
            fifo = os.path.join(root, "pipe"); os.mkfifo(fifo)
            for item in (sub, fifo):
                with self.assertRaises(F.FailClosed):
                    F.scan_corpus(self.v, root, [(item, None)])

    def test_r3_12_untested_rules(self):
        self.assertTrue(F.family_withheld(self.v, os.path.join("S9001", "n.md"), "clean"))       # ID in dir name
        self.assertTrue(F.corpus_withheld(self.v, "docs/S9001.md", "c" * 64))                  # ID in corpus path
        self.assertIsNone(F.decode_or_none("valid\x00utf8".encode()))                          # NUL in valid UTF-8
        rows = [{"source_id": "S009001", "content_sha256": hashlib.sha256(FAKE_SEALED_BYTES).hexdigest().upper(),
                 "path": "./docs/fake/sealed_one.md"}]
        v = view(rows=rows)
        self.assertTrue(F.corpus_withheld(v, "docs/fake/sealed_one.md", "f" * 64))             # ./ seal path
        self.assertTrue(F.corpus_withheld(v, "x.md", hashlib.sha256(FAKE_SEALED_BYTES).hexdigest()))   # upper sha
        self.assertTrue(F.family_withheld(v, "n.md", "S9001"))                                 # leading zeros
        with tempfile.TemporaryDirectory() as root:
            fam = os.path.join(root, "fam"); os.makedirs(os.path.join(fam, "docs", "fake"))
            put(os.path.join(fam, "docs", "fake", "sealed_one.md"), "edited content")
            self.assertEqual(F.scan_families(self.v, fam, os.path.join(fam))[1], 1)            # sealed path, edited


def unicodedata_nfd(s):
    import unicodedata
    return unicodedata.normalize("NFD", s)


class CountOnlyCheck(unittest.TestCase):
    def test_outputs_only_three_values(self):
        v = view()
        fams = {"f1": "clean", "f2": "quotes S9001", "f3": "s9002 here"}
        self.assertEqual(F.source_id_check(v, fams, opened_family_names=["f1"], opened_doc_texts=["clean doc"]),
                         (2, False, False))
        self.assertEqual(F.source_id_check(v, fams, ["f2"], ["doc S9002"]), (2, True, True))


if __name__ == "__main__":
    unittest.main(verbosity=1)
