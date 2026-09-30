#!/usr/bin/env python3
"""IV-1a (G-LOG-0095): verified re-basing of stage-1 NORMALIZED hit offsets to the RAW decoded coordinate that the frozen
binary_preclassify expects. Synthetic in-memory bytes only. The safety property under test:
    mapping uncertainty ⇒ HUMAN-REVIEW;  UNVERIFIED ∩ FALSE-HIT = ∅;  a verified mapping leaves the classifier unchanged.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_offset_rebase
"""
import importlib.util
import os
import random
import struct
import unittest
import zlib

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(HERE)


def _load(name):
    s = importlib.util.spec_from_file_location(name, os.path.join(SCRIPTS, name + ".py"))
    m = importlib.util.module_from_spec(s)
    s.loader.exec_module(m)
    return m


R = _load("p3b_s5_offset_rebase")
r5 = R.r5
s3 = R.s3


def stage1_hits(b, terms):
    """What stage 1 records: (offset, term) in the normalized coordinate (multi-char → casefolded)."""
    txt = b.decode("utf-8", errors="replace")
    base = s3.norm_base(txt)
    cf = base.casefold()
    out = []
    for t in terms:
        hay = cf if len(t) > 1 else base
        i = hay.find(t)
        while i != -1:
            out.append((i, t))
            i = hay.find(t, i + 1)
    return out


def raw_start(b, needle):
    return b.decode("utf-8", errors="replace").find(needle)


class Rebase(unittest.TestCase):
    def one(self, b, term, expect_raw_substring):
        hits = stage1_hits(b, [term])
        self.assertTrue(hits, "fixture must contain the term")
        res = R.rebase(b, hits)
        m = res["hits"][0]
        self.assertEqual(m["status"], "VERIFIED", m)
        self.assertEqual(m["raw_offsets"], [raw_start(b, expect_raw_substring)])
        return res

    def test_identity_ascii(self):
        self.one(b"plain text with kernel inside", "kernel", "kernel")

    def test_nfkc_contraction_before_hit(self):          # U+2474 '⑵' → '(2)' is expansion; 'ﬁ' → 'fi' expansion
        self.one("ﬁﬁﬁ then kernel".encode(), "kernel", "kernel")

    def test_nfkc_compat_and_fullwidth(self):
        self.one("ＡＢＣ ⑵ ² kernel".encode(), "kernel", "kernel")

    def test_nfkc_composition_contraction(self):          # 'e' + U+0301 → 'é' (2 → 1)
        self.one("cafe\u0301 cafe\u0301 kernel".encode(), "kernel", "kernel")

    def test_latex_command_shortening(self):              # '\alpha' → 'α' (6 → 1)
        self.one(b"\\alpha \\beta \\gamma kernel", "kernel", "kernel")

    def test_latex_argument_unwrapping(self):             # '\textbf{x}' → 'x'
        self.one(b"\\textbf{bold} \\emph{it} kernel", "kernel", "kernel")

    def test_casefold_expansion(self):                    # 'ß' → 'ss' in the casefolded coordinate
        self.one("Straße Maß kernel".encode(), "kernel", "kernel")

    def test_hit_inside_latex_argument(self):
        """'\\textbf{kernel}' → 'kernel': the raw span producing the hit is the WHOLE command (an unclosed '\\textbf{'
        stays literal, so no prefix-consistent start exists at the inner 'k'). The adapter reports that exact span."""
        b = b"x \\textbf{kernel} y"
        m = R.rebase(b, stage1_hits(b, ["kernel"]))["hits"][0]
        self.assertEqual(m["status"], "VERIFIED")
        self.assertEqual((m["raw_offsets"], m["raw_end"]), ([2], 17))
        self.assertEqual(s3.norm_base(b.decode()[2:17]), "kernel")

    def test_uppercase_hit_multi_char_casefold(self):
        self.one(b"the KERNEL here", "kernel", "KERNEL")

    def test_single_char_term_case_sensitive_base(self):
        b = "ﬁ α x Ω".encode()
        hits = stage1_hits(b, ["Ω"])
        res = R.rebase(b, hits)
        self.assertEqual(res["hits"][0]["status"], "VERIFIED")
        self.assertEqual(res["hits"][0]["raw_offsets"], [raw_start(b, "Ω")])

    def test_multiple_adjacent_repeated_and_edge_hits(self):
        b = "kernelkernel ß kernel\n\\alpha kernel".encode()
        hits = stage1_hits(b, ["kernel"])
        res = R.rebase(b, hits)
        txt = b.decode()
        want = sorted(i for i in range(len(txt)) if txt.startswith("kernel", i))
        self.assertEqual(sorted(h["raw_offsets"][0] for h in res["hits"]), want)
        self.assertTrue(all(h["status"] == "VERIFIED" for h in res["hits"]))
        self.one(b"kernel at start", "kernel", "kernel")
        self.one(b"ends with kernel", "kernel", "kernel")

    def test_invalid_utf8_and_replacement(self):
        b = b"\xff\xfe\x80 bad \xc3 kernel \xed\xa0\x80 x"
        self.one(b, "kernel", "kernel")
        self.assertTrue(R.decoder_consistent(b))

    def test_nul_near_hit_is_still_mapped(self):
        self.one(b"\x00\x00\x00kernel\x00\x00", "kernel", "kernel")

    def test_hit_starting_inside_an_expansion_is_unverified(self):
        """'ﬁ' → 'fi': a stage-1 hit on 'ile' starting at the 'i' of the ligature has no raw start → UNVERIFIED."""
        b = "ﬁle".encode()
        hits = stage1_hits(b, ["ile"])
        res = R.rebase(b, hits)
        self.assertEqual(res["hits"][0]["status"], "UNVERIFIED")

    def test_wrong_term_at_offset_is_unverified(self):
        b = b"some kernel text"
        res = R.rebase(b, [(0, "kernel")])                  # a forged/misaligned offset
        self.assertEqual(res["hits"][0]["status"], "UNVERIFIED")

    def test_out_of_range_offset_is_unverified(self):
        res = R.rebase(b"short", [(999, "kernel")])
        self.assertEqual(res["hits"][0]["status"], "UNVERIFIED")

    def test_empty_normalized_region(self):
        res = R.rebase(b"", [(0, "kernel")])
        self.assertEqual(res["hits"][0]["status"], "UNVERIFIED")

    def test_latex_spanning_whitespace_forces_coarser_chunks_but_stays_exact(self):
        b = b"\\textbf {a b} \\emph\n{c d} kernel"
        self.one(b, "kernel", "kernel")


def png(chunks):
    out = b"\x89PNG\r\n\x1a\n"
    for typ, data in chunks:
        out += struct.pack(">I", len(data)) + typ + data + struct.pack(">I", zlib.crc32(typ + data) & 0xffffffff)
    return out


class Classification(unittest.TestCase):
    def test_png_hit_in_text_chunk_after_drift_is_human_review(self):
        """Drift before the hit (ligatures in the tEXt keyword) must not push the offset out of the text chunk."""
        b = png([(b"IHDR", b"\x00" * 13), (b"tEXt", "Comment\x00ﬁﬁﬁﬁﬁﬁ \\alpha kernel".encode()),
                 (b"IDAT", bytes(random.Random(1).randrange(256) for _ in range(400))), (b"IEND", b"")])
        out = R.preclassify(b, stage1_hits(b, ["kernel"]))
        self.assertEqual(out["candidate"], "HUMAN-REVIEW")
        self.assertTrue(all(h["status"] == "VERIFIED" for h in out["hits"]))
        self.assertIn("TEXT", out["classifier"]["hits"].values())

    def test_verified_nontext_hit_gives_false_hit_as_the_frozen_classifier(self):
        data = bytes(random.Random(2).randrange(256) for _ in range(600)).replace(b"kernel", b"xxxxxx")
        data = data[:300] + b"kernel" + data[300:]
        b = png([(b"IHDR", b"\x00" * 13), (b"IDAT", data), (b"IEND", b"")])
        hits = stage1_hits(b, ["kernel"])
        out = R.preclassify(b, hits)
        frozen = r5.binary_preclassify(b, [h["raw_offsets"][0] for h in out["hits"] if h["status"] == "VERIFIED"])
        if all(h["status"] == "VERIFIED" for h in out["hits"]):
            self.assertEqual(out["candidate"], frozen["candidate"])     # the adapter does not redefine the classifier
            self.assertEqual(out["classifier"], frozen)

    def test_zip_member_name_and_stored_data(self):
        import io
        import zipfile
        bio = io.BytesIO()
        with zipfile.ZipFile(bio, "w", compression=zipfile.ZIP_STORED) as z:
            z.writestr("ﬁ-kernel.txt", "ß kernel body")
        b = bio.getvalue()
        out = R.preclassify(b, stage1_hits(b, ["kernel"]))
        self.assertEqual(out["candidate"], "HUMAN-REVIEW")                # text regions exist: never FALSE-HIT

    def test_safety_unverified_never_false_hit(self):
        b = png([(b"IHDR", b"\x00" * 13), (b"IDAT", bytes(200)), (b"IEND", b"")])
        out = R.preclassify(b, [(5, "kernel"), (10**6, "kernel")])     # unverifiable offsets
        self.assertEqual(out["candidate"], "HUMAN-REVIEW")
        self.assertTrue(any("UNVERIFIED" in r for r in out["reasons"]))

    def test_safety_property_fuzz(self):
        """Random byte strings with injected hazards: every VERIFIED hit re-normalizes to its term, and a file with any
        UNVERIFIED hit is never FALSE-HIT."""
        rng = random.Random(20260928)
        hazards = ["ﬁ", "ß", "\\alpha ", "\\textbf{", "}", "e\u0301", "Ω", "\x00", "kernel", "KERNEL", " ", "\n"]
        for _ in range(300):
            parts = []
            for _ in range(rng.randint(1, 25)):
                parts.append(rng.choice(hazards).encode() if rng.random() < 0.6 else bytes(rng.randrange(256) for _ in range(rng.randint(1, 8))))
            b = b"".join(parts)
            hits = stage1_hits(b, ["kernel"]) + [(rng.randrange(0, 50), "kernel")]
            out = R.preclassify(b, hits)
            txt = b.decode("utf-8", errors="replace")
            for h in out["hits"]:
                if h["status"] == "VERIFIED":
                    for o in h["raw_offsets"]:
                        self.assertEqual(s3.norm_base(txt[o:h["raw_end"]]).casefold(), h["term"], (b, h))
            if any(h["status"] != "VERIFIED" for h in out["hits"]):
                self.assertEqual(out["candidate"], "HUMAN-REVIEW")


if __name__ == "__main__":
    unittest.main()
