"""Tests for contract revision 5 machinery (p3b_s5_r5.py) and the reader's byte mode. Synthetic data, plus read-only
checks against the sealed OB0018 pilot objects (pilot outputs, not corpus content)."""
import importlib.util
import io
import json
import os
import sys
import unittest
import zipfile

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(HERE)
CR = os.path.dirname(SCRIPTS)
sys.path.insert(0, SCRIPTS)
spec = importlib.util.spec_from_file_location("r5", os.path.join(SCRIPTS, "p3b_s5_r5.py"))
r5 = importlib.util.module_from_spec(spec)
spec.loader.exec_module(r5)
rspec = importlib.util.spec_from_file_location("reader", os.path.join(SCRIPTS, "p3b_read_source.py"))
reader = importlib.util.module_from_spec(rspec)
rspec.loader.exec_module(reader)


def jl(p):
    with open(p, encoding="utf-8") as f:
        return [json.loads(x) for x in f if x.strip()]


class DispatchAndPartition(unittest.TestCase):
    SIZES = {"S0001": 250_000, "S0002": 250_000, "S0003": 250_000, "S0004": 100_000, "S0005": 400_000}
    META = {}

    def key(self, s):
        return r5.packing_key(s, self.META.get(s))

    def test_dispatch_paths(self):
        self.assertEqual(r5.dispatch_path(0, 0), "EMPTY")
        self.assertEqual(r5.dispatch_path(3, 600_000), "SINGLE")
        self.assertEqual(r5.dispatch_path(3, 600_001), "DECOMPOSED")

    def test_row_first_contiguous_and_fill(self):
        units = r5.partition(self.SIZES, {"S0001", "S0002", "S0003"}, self.SIZES, self.key, 600_000)
        self.assertTrue({"S0001", "S0002"} <= set(units[0]))      # rows contiguous, next-fit (then FFD fill)
        self.assertIn("S0004", units[0])                           # the fill uses free space in the row unit
        self.assertIn("S0003", units[1])
        self.assertEqual(sorted(s for u in units for s in u), sorted(self.SIZES))
        self.assertTrue(all(sum(self.SIZES[x] for x in u) <= 600_000 for u in units))

    def test_adjacency_minimal(self):
        rows = {"S0001", "S0002", "S0003"}
        units = r5.partition(self.SIZES, rows, self.SIZES, self.key, 600_000)
        self.assertEqual(r5.row_adjacencies(rows, units, self.key), 1)

    def test_deterministic(self):
        a = r5.partition(self.SIZES, {"S0001", "S0003"}, self.SIZES, self.key)
        self.assertEqual(a, r5.partition(dict(reversed(list(self.SIZES.items()))), {"S0003", "S0001"},
                                         self.SIZES, self.key))

    def test_unsized_file_is_error(self):
        with self.assertRaises(ValueError):
            r5.label_plan("l", {"S0001", "S9999"}, {"S0001"}, self.SIZES, {})

    def test_all_blob_sizes_includes_preserved_copies(self):
        import tempfile
        d = tempfile.mkdtemp()
        try:
            with open(os.path.join(d, "copy.md"), "wb") as f:
                f.write(b"x" * 1234)
            out = r5.all_blob_sizes([{"source_id": "S0007", "blob_spec_type": "PRESERVED-AUDIT-COPY",
                                      "blob_spec": "copy.md"}], d, {"S0001": 5})
            self.assertEqual(out, {"S0001": 5, "S0007": 1234})
        finally:
            import shutil
            shutil.rmtree(d)


class PackingKey(unittest.TestCase):
    def test_dated_group_first_by_date(self):
        m = {"best_historical_date": "2026-08-30", "best_historical_date_basis": "EXPLICIT",
             "order_evidence": "FILENAME-DATESTAMP", "mtime_block": None}
        self.assertEqual(r5.packing_key("S0009", m), (0, "2026-08-30", "S0009"))

    def test_excluded_cases_group_one(self):
        for m in ({"best_historical_date": "2026-08-30", "best_historical_date_basis": "MTIME"},
                  {"best_historical_date": "2026-08-30", "best_historical_date_basis": "EXPLICIT",
                   "order_evidence": "SOURCE_ID"},
                  {"best_historical_date": "2026-08-30", "best_historical_date_basis": "EXPLICIT",
                   "order_evidence": "STEP-NUMBER", "mtime_block": "BULK-03"},
                  None):
            self.assertEqual(r5.packing_key("S0001", m)[0], 1, m)

    def test_key_never_emitted_into_records(self):
        plan = r5.label_plan("l", {"S0001"}, {"S0001"}, {"S0001": 10}, {})
        self.assertNotIn("packing_key", json.dumps(plan))


class BytePages(unittest.TestCase):
    def test_tiling_and_utf8_boundaries(self):
        b = ("ä€𝔸x" * 9000).encode("utf-8")
        spans = r5.byte_pages(b)
        self.assertEqual(spans[0][0], 0)
        self.assertEqual(spans[-1][1], len(b))
        self.assertTrue(all(a2 == b1 for (_, b1), (a2, _) in zip(spans, spans[1:])))
        self.assertTrue(all(e - a <= r5.PAGE_BYTES for a, e in spans))
        for a, e in spans:
            b[a:e].decode("utf-8")                                # never splits a code point

    def test_empty_and_small(self):
        self.assertEqual(r5.byte_pages(b""), [(0, 0)])
        self.assertEqual(r5.byte_pages(b"abc"), [(0, 3)])

    def test_binary_detection(self):
        self.assertEqual(r5.is_binary(b"\x89PNG\r\n\x1a\n...."), "SIGNATURE-PNG")
        self.assertEqual(r5.is_binary(b"PK\x03\x04...."), "SIGNATURE-ZIP")
        self.assertEqual(r5.is_binary(b"abc\x00def"), "NUL-BYTE")
        self.assertEqual(r5.is_binary(b"\xff\xfe\xfd"), "NOT-UTF-8")
        self.assertIsNone(r5.is_binary("plain text ä".encode()))

    def test_page_record_and_coverage_and_tokens(self):
        b = ("x" * 50_000).encode()
        recs = []
        for k in (1, 2, 3):
            _, rec = r5.byte_page_record("S0001", b, k, "c")
            recs.append({"source_ids": ["S0001"], "refused": False, "page": rec})
        cov = r5.byte_page_coverage(recs, {"S0001": b})
        self.assertTrue(cov["S0001"]["complete"])
        self.assertEqual(cov["S0001"]["bad"], 0)
        toks = sorted(cov["S0001"]["tokens"])
        self.assertEqual(r5.ack_violations({"S0001": toks}, cov, ["S0001"]), [])
        self.assertEqual(len(r5.ack_violations({"S0001": toks[:1]}, cov, ["S0001"])), 1)

    def test_forged_page_detected(self):
        b = ("y" * 30_000).encode()
        _, rec = r5.byte_page_record("S0001", b, 1, "c")
        rec["page_sha256"] = "0" * 64
        cov = r5.byte_page_coverage([{"source_ids": ["S0001"], "page": rec}], {"S0001": b})
        self.assertEqual(cov["S0001"]["bad"], 1)
        self.assertFalse(cov["S0001"]["complete"])

    def test_character_pages_unchanged(self):
        self.assertEqual(reader.PAGE_CHARS, 20000)
        self.assertEqual(reader.pages_of("a" * 45000), [(0, 20000), (20000, 40000), (40000, 45000)])

    def test_reader_parses_mode_and_r5_run_grammar(self):
        opts, sids = reader.parse(["--run", "OB0001-R5-L01U02", "--batch", "OB0001", "--mode", "bytes", "S0001"])
        self.assertEqual((opts["mode"], sids), ("bytes", ["S0001"]))
        import re
        self.assertTrue(re.fullmatch(reader.R5_RUN, "OB0001-R5-L01"))
        self.assertTrue(re.fullmatch(reader.R5_RUN, "OB0001-R5-L01S"))
        self.assertFalse(re.fullmatch(reader.R5_RUN, "OB0001-R2"))


class Binary(unittest.TestCase):
    def png(self):
        def chunk(t, d):
            return len(d).to_bytes(4, "big") + t + d + b"\x00\x00\x00\x00"
        return b"\x89PNG\r\n\x1a\n" + chunk(b"IHDR", b"\x00" * 13) + chunk(b"tEXt", b"Comment\x00b-prime") + \
            chunk(b"IDAT", b"\x78\x9c" + bytes(range(1, 200)))

    def test_png_hit_in_idat_is_nontext(self):
        b = self.png()
        off = len(b.decode("utf-8", errors="replace")) - 20
        res = r5.binary_preclassify(b, [off])
        self.assertEqual(res["format"], "SIGNATURE-PNG")
        self.assertEqual(res["candidate"], "FALSE-HIT")

    def test_png_hit_in_text_chunk_needs_review(self):
        b = self.png()
        txt = b.decode("utf-8", errors="replace")
        res = r5.binary_preclassify(b, [txt.index("b-prime")])
        self.assertEqual(res["candidate"], "HUMAN-REVIEW")

    def zipbytes(self, members):
        bio = io.BytesIO()
        with zipfile.ZipFile(bio, "w", zipfile.ZIP_DEFLATED) as z:
            for n, d in members.items():
                z.writestr(n, d)
        return bio.getvalue()

    def test_archive_member_seal(self):
        z = self.zipbytes({"a.md": b"public", "b.md": b"secret-holdout"})
        cls = r5.archive_member_seal(z, {r5.sha(b"secret-holdout")}, {r5.sha(b"public")})
        self.assertEqual(sorted(c for _, _, c in cls), ["CORPUS-MATCH", "HOLDOUT-MATCH"])
        rec = {"decision": "EXTRACT", "authorized_by": "human", "extractor_validated": True, "provenance": "x"}
        self.assertFalse(r5.extraction_permitted(rec, cls))
        self.assertTrue(r5.extraction_permitted(rec, [c for c in cls if c[2] != "HOLDOUT-MATCH"]))

    def test_extraction_requires_every_condition(self):
        for missing in ("authorized_by", "extractor_validated", "provenance"):
            rec = {"decision": "EXTRACT", "authorized_by": "human", "extractor_validated": True, "provenance": "x"}
            rec.pop(missing)
            self.assertFalse(r5.extraction_permitted(rec, []), missing)
        self.assertFalse(r5.extraction_permitted({"decision": "FALSE-HIT"}, []))


class ReadingStateAndEmpty(unittest.TestCase):
    def test_only_whole_file_supports_found_births_changes(self):
        obj = {"stage2_dispositions": [{"source_id": "S0002", "method": "WHOLE-FILE", "by_dimension": {"d": "FOUND"}}],
               "absences": {"d": {"resolution": "FOUND", "supplied_by": {"source_id": "S0002"}}},
               "births": {"lexical": "ESTABLISHED-LEXICAL-BIRTH[S0002]"},
               "timeline": [{"source_id": "S0002", "change_vs_previous": "CHANGES-DEFINITION"}]}
        v = r5.reading_state_violations(obj, {"S0001": "WHOLE-FILE", "S0002": "READ-PARTIAL"})
        self.assertEqual(len(v), 5)                                # method + non-ESCALATED dim + FOUND + birth + change
        self.assertEqual(r5.reading_state_violations(obj, {"S0002": "WHOLE-FILE"}), [])

    def test_census_incomplete_blocks_undefined(self):
        obj = {"absences": {"d": {"resolution": "GENUINELY-UNDEFINED-AFTER-CENSUS"}}}
        self.assertEqual(len(r5.reading_state_violations(obj, {"S0001": "NOT-CONSUMED"})), 1)

    def test_not_consumed_escalated_form(self):
        ok = {"stage2_dispositions": [{"source_id": "S0003", "method": "NOT-CONSUMED-ESCALATED",
                                       "by_dimension": {"d": "ESCALATED"}}]}
        self.assertEqual(r5.reading_state_violations(ok, {"S0003": "READ-FAILED"}), [])

    def test_off_scale_state(self):
        self.assertEqual(len(r5.reading_state_violations({}, {"S0001": "SKIMMED"})), 1)

    def test_empty_label(self):
        good = {"births": {k: "NOT-EVIDENCED-IN-CAPTURE" for k in ("lexical", "conceptual")},
                "absences": {"d": {"resolution": "ESCALATED"}},
                "escalations": [{"reason": "OTHER", "detail": "EMPTY-REQUIRED-SET (R17)"}]}
        self.assertEqual(r5.empty_label_violations(good), [])
        bad = {"births": {"lexical": "ESTABLISHED-LEXICAL-BIRTH[S0001]"},
               "absences": {"d": {"resolution": "GENUINELY-UNDEFINED-AFTER-CENSUS"}}, "escalations": []}
        self.assertEqual(len(r5.empty_label_violations(bad)), 3)


class Edges(unittest.TestCase):
    def test_edge_classes(self):
        obj = {"dependency_edges": [
            {"target_label": "a", "edge_class": "R2-EVIDENCED", "quote": "depends on a", "source_id": "S0001"},
            {"target_label": "b", "edge_class": "R1-STRUCTURAL", "source_id": "S0001"},
            {"target_label": "c", "edge_class": "R2-EVIDENCED"},
            {"target_label": "d", "edge_class": "R1-STRUCTURAL", "source_id": "S0099"},
            {"target_label": "e"}]}
        self.assertEqual(len(r5.edge_class_violations(obj, {"S0001"})), 3)


class ScanTaxonomy(unittest.TestCase):
    def c(self, cmd, name="Bash"):
        return {"name": name, "input": {"command": cmd} if name == "Bash" else {"file_path": cmd}}

    def test_help_is_level_1(self):
        self.assertEqual(r5.scan_level(self.c("python3 p3b_read_source.py --help"), "R", "B", set(), set()), (1, None))

    def test_conforming_read_attempt_level_2_clean(self):
        cmd = "python3 p3b_read_source.py --run OB0001-R5-L01 --batch OB0001 --mode bytes --page 1 S0001"
        self.assertEqual(r5.scan_level(self.c(cmd), "OB0001-R5-L01", "OB0001", set(), set()), (2, None))

    def test_foreign_run_violation(self):
        cmd = "python3 p3b_read_source.py --run OB0001-R5-L02 --batch OB0001 --page 1 S0001"
        self.assertEqual(r5.scan_level(self.c(cmd), "OB0001-R5-L01", "OB0001", set(), set())[1],
                         "READER-FOREIGN-RUN-OR-BATCH")

    def test_outside_reader_patterns(self):
        for cmd in ("git show HEAD:docs/x.md", "git cat-file -p abc123", "git log -p -- docs", "git grep foo",
                    "ls docs/knowledgeos/*.md", "python3 -c 'import p3b_discovery_io'",
                    "python3 -c 'r.read_many([\"S0001\"])'"):
            self.assertEqual(r5.scan_level(self.c(cmd), "R", "B", set(), set())[1], "ACCESS-OUTSIDE-READER", cmd)

    def test_corpus_path_and_holdout(self):
        self.assertEqual(r5.scan_level(self.c("/r/docs/secret.md", "Read"), "R", "B", {"docs/secret.md"}, set())[1],
                         "ACCESS-OUTSIDE-READER")
        self.assertEqual(r5.scan_level(self.c("cat hold.md"), "R", "B", set(), {"hold.md"})[1], "SEAL-BREACH-ATTEMPT")

    def test_log_levels(self):
        self.assertEqual(r5.log_level({"source_ids": ["S0001"]}, {"S0001"}, set()), (3, None))
        self.assertEqual(r5.log_level({"source_ids": ["S0002"]}, {"S0001"}, set())[1], "NON-PERMITTED-EXPOSURE")
        self.assertEqual(r5.log_level({"source_ids": ["S0003"]}, {"S0003"}, {"S0003"})[1], "HOLDOUT-EXPOSURE")
        self.assertEqual(r5.log_level({"refused": True, "source_ids": ["S0003"]}, set(), {"S0003"}), (2, None))


class Safeguards(unittest.TestCase):
    PAIRS = [{"pair_id": "RP1", "a": "lab", "b": "other", "what_says_this": "see S0001", "basis": "x"},
             {"pair_id": "RP2", "a": "zzz", "b": "other", "what_says_this": "S0001", "basis": "x"}]

    def test_s1_required_and_missing(self):
        need = r5.required_pair_checks(self.PAIRS, "lab", {"S0001", "S0002"})
        self.assertEqual(need, {("RP1", "S0001")})
        self.assertEqual(len(r5.s1_violations(need, [], {}, [])), 1)

    def test_s1_no_requires_register_and_escalation(self):
        need = {("RP1", "S0001")}
        recs = [{"source_id": "S0001", "pair_evidence_checks": [{"pair_id": "RP1", "supports": "NO", "quote": "q"}]}]
        self.assertEqual(len(r5.s1_violations(need, recs, {"escalations": []}, [])), 2)
        obj = {"escalations": [{"reason": "OTHER", "detail": "VERDICT-EVIDENCE-CONFLICT: RP1 (H-02)"}]}
        reg = [{"kind": "OBSERVATION", "topics": ["VERDICT-EVIDENCE-CONFLICT"], "statement": "RP1 ..."}]
        self.assertEqual(r5.s1_violations(need, recs, obj, reg), [])

    def test_s1_yes_needs_quote(self):
        recs = [{"source_id": "S0001", "pair_evidence_checks": [{"pair_id": "RP1", "supports": "YES"}]}]
        self.assertEqual(len(r5.s1_violations({("RP1", "S0001")}, recs, {}, [])), 1)

    def test_s2(self):
        obj = {"births": {"lexical": "MOVED[S0009, 'q']", "formal": "BIRTH-UNRESOLVED-MTIME-ONLY[S0001]",
                          "operational": "NOT-EVIDENCED-IN-CAPTURE"}}
        self.assertEqual(r5.s2_violations(obj, {"S0001"}), ["S2: births.lexical cites S0009, not a row source of the label"])

    def test_s3_lint(self):
        obj = {"working_label": "lab", "timeline": [], "note": "see the register record"}
        reg = [{"statement": "sources (S0001 to S0005) disagree"}, {"statement": "Step 253–254 only"}]
        out = r5.s3_lint([obj] + reg, "lab", "OB0001-R5-L01S", "OB0001")
        self.assertEqual(len(out), 2)                              # pointer in layer A; one S-id range ("Step" is not)


class Audit(unittest.TestCase):
    STRATA = {"multirow": ["m1", "m2", "m3", "m4", "m5"], "decomposed": [f"d{i}" for i in range(172)],
              "hubs": [f"h{i}" for i in range(66)], "single": [f"s{i}" for i in range(1790)]}
    RATES = {"multirow": "CENSUS", "decomposed": 0.3, "hubs": 0.2, "single": 0.02}

    def test_seeded_reproducible_and_census(self):
        a = r5.draw_audit_sample(self.STRATA, 20261100, self.RATES)
        self.assertEqual(a, r5.draw_audit_sample(self.STRATA, 20261100, self.RATES))
        self.assertEqual(a["multirow"]["n"], 5)
        self.assertEqual(a["multirow"]["pi"], 1.0)
        self.assertEqual(a["single"]["n"], 36)
        self.assertAlmostEqual(a["single"]["pi"], 36 / 1790)

    def test_ht_estimate_and_ml_exclusion(self):
        a = r5.draw_audit_sample(self.STRATA, 7, self.RATES)
        d = {a["single"]["labels"][0], "m1"}
        est = r5.ht_estimate(a, d)
        self.assertAlmostEqual(est["strata"]["single"]["ht_total"], 1790 / 36)
        self.assertEqual(est["strata"]["multirow"]["ht_total"], 1.0)
        with self.assertRaises(ValueError):
            r5.ht_estimate(a, {"not-sampled-ml-queue-item"})

    def test_zero_bounds(self):
        self.assertAlmostEqual(r5.zero_bound(1), 0.95)
        self.assertLess(r5.zero_bound(29), 0.10)
        self.assertLessEqual(r5.zero_bound(29, 172), r5.zero_bound(29))


class RealPilotObjects(unittest.TestCase):
    """S2/S3 against the sealed OB0018 objects, whose defects are known from the blind audit and the verifier."""
    ROOT = os.path.join(CR, "pilot-s5-decomp")

    def setUp(self):
        if not os.path.exists(os.path.join(self.ROOT, "ob0018", "OB0018-PLAN.json")):
            self.skipTest("OB0018 artifacts absent")
        self.rows = set(json.load(open(os.path.join(self.ROOT, "ob0018", "OB0018-PLAN.json")))["body"]["row_sources"])

    def obj(self, run):
        return jl(os.path.join(self.ROOT, run, "objects.jsonl"))[0]

    def test_s2_catches_the_audited_birth_errors(self):
        self.assertTrue(any("births.lexical" in v and "S1618" in v for v in r5.s2_violations(self.obj("PX0018-S00"),
                                                                                           self.rows)))
        for run in ("PX0018-S02", "PX0018-S03"):
            v = r5.s2_violations(self.obj(run), self.rows)
            self.assertTrue(any("births.operational" in x and "S1691" in x for x in v), run)
        self.assertEqual(r5.s2_violations(self.obj("PX0018-S01"), self.rows), [])

    def test_s3_catches_the_verifier_range_findings(self):
        for run, expect in (("PX0018-S01", True), ("PX0018-S03", True), ("PX0018-S02", False)):
            recs = [self.obj(run)] + jl(os.path.join(self.ROOT, run, "register.jsonl"))
            got = any("S-id range" in x for x in r5.s3_lint(recs, "l", run, "PX0018"))
            self.assertEqual(got, expect, run)


if __name__ == "__main__":
    unittest.main()
