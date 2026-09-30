#!/usr/bin/env python3
"""B4 RECONSTRUCTION (R7 v2.3 §4, DR-01/02; FD-1′a/b/c, FD-2′, FD-3′): summary relations, contradiction relation +
computed precedence (A.10 dated positions only), lifecycle, S3 with the canonical matcher, EMPTY. Synthetic metadata.
  cd scripts/tests && PYTHONPATH=.:.. python3 -m unittest test_p3b_s5_r7_reconstruction
"""
import copy
import itertools
import os
import sys
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, os.path.dirname(HERE))
import p3b_s5_r7_reconstruction as C                # noqa: E402

META = {  # S0001 < S0002 dated; S0003 undated (MTIME); S0004 SOURCE_ID-only; S0005 BULK; S0006 same day as S0002
    "S0001": {"best_historical_date_basis": "EXPLICIT", "best_historical_date": "2024-01-01", "order_evidence": "FILENAME-DATESTAMP", "mtime_block": None},
    "S0002": {"best_historical_date_basis": "EXPLICIT", "best_historical_date": "2024-06-01", "order_evidence": "FILENAME-DATESTAMP|SOURCE_ID", "mtime_block": None},
    "S0003": {"best_historical_date_basis": "MTIME", "best_historical_date": 1700000000, "order_evidence": "SOURCE_ID", "mtime_block": None},
    "S0004": {"best_historical_date_basis": "EXPLICIT", "best_historical_date": "2024-02-01", "order_evidence": "SOURCE_ID", "mtime_block": None},
    "S0005": {"best_historical_date_basis": "EXPLICIT", "best_historical_date": "2024-03-01", "order_evidence": "STEP-NUMBER", "mtime_block": "BULK-01"},
    "S0006": {"best_historical_date_basis": "EXPLICIT", "best_historical_date": "2024-06-01", "order_evidence": "INTERNAL-TIMESTAMP", "mtime_block": None}}


def pt(s, cls, conf="CONFIRMED"):
    return {"source_id": s, "change_vs_previous": cls, "date_applies_to_file": conf}


def obj(tl, **summary):
    ts_ = {"later_support": [], "later_refinement": [], "contradicted_by": [], "rejected_by": [], "current_lifecycle": "SOURCE-CLAIMED-ACTIVE"}
    ts_.update(summary)
    return {"working_label": "lab", "timeline": tl, "timeline_summary": ts_, "superseded_by_sources": []}


class Precedence(unittest.TestCase):
    def test_established_only_by_dated_positions(self):
        tl = {p["source_id"]: p for p in [pt("S0001", "FIRST"), pt("S0002", "EXTENDS"), pt("S0003", "EXTENDS"),
                                          pt("S0004", "EXTENDS"), pt("S0005", "EXTENDS"), pt("S0006", "EXTENDS")]}
        P = lambda a, b: C.precedence(a, b, tl, META)
        self.assertEqual(P("S0001", "S0002"), "ESTABLISHED")
        unconf = dict(tl, S0002=pt("S0002", "EXTENDS", conf="NOT-CONFIRMED"))
        self.assertEqual(C.precedence("S0001", "S0002", unconf, META), "NOT-ESTABLISHED")   # file-dated, point not confirmed
        self.assertEqual(P("S0002", "S0001"), "NOT-ESTABLISHED")
        for undated in ("S0003", "S0004", "S0005"):
            self.assertEqual(P("S0001", undated), "NOT-ESTABLISHED", undated)
        self.assertEqual(P("S0002", "S0006"), "NOT-ESTABLISHED")                   # same day: not established

    def test_precedence_is_a_strict_partial_order_exhaustively(self):
        tl = {s: pt(s, "EXTENDS") for s in META}
        rel = {(a, b) for a, b in itertools.product(META, META) if C.precedence(a, b, tl, META) == "ESTABLISHED"}
        for a in META:
            self.assertNotIn((a, a), rel)                                          # irreflexive
        for a, b in rel:
            self.assertNotIn((b, a), rel)                                          # asymmetric
        for (a, b), (b2, c) in itertools.product(rel, rel):
            if b == b2:
                self.assertIn((a, c), rel)                                         # transitive

    def test_never_array_order(self):
        tl = [pt("S0003", "FIRST", "NOT-CONFIRMED"), pt("S0004", "EXTENDS")]
        self.assertEqual(C.precedence("S0003", "S0004", {p["source_id"]: p for p in tl}, META), "NOT-ESTABLISHED")


class SummaryRelations(unittest.TestCase):
    def test_T72_S4_restates_S3_may_contradict_S1(self):
        o = obj([pt("S0001", "FIRST"), pt("S0002", "CONTRADICTS"), pt("S0006", "RESTATES")],
                contradicted_by=[{"source_id": "S0002", "contradicts": "S0001"}, {"source_id": "S0006", "contradicts": "S0001"}])
        self.assertEqual(C.summary_violations(o, META), [])

    def test_T73_contradicts_point_missing_from_contradicted_by(self):
        o = obj([pt("S0001", "FIRST"), pt("S0002", "CONTRADICTS")])
        self.assertTrue(any("CONTRADICTS point S0002" in x for x in C.summary_violations(o, META)))

    def test_retracts_obligation_and_first_point_without_predecessor(self):
        o = obj([pt("S0001", "FIRST"), pt("S0002", "RETRACTS")])
        self.assertTrue(any("rejected_by" in x for x in C.summary_violations(o, META)))
        o = obj([pt("S0001", "CONTRADICTS")])
        self.assertTrue(any("no predecessor" in x for x in C.summary_violations(o, META)))

    def test_T74_lateness_requires_established_precedence(self):
        ok = obj([pt("S0001", "FIRST"), pt("S0002", "EXTENDS")], later_refinement=["S0002"])
        self.assertEqual(C.summary_violations(ok, META), [])
        bad = obj([pt("S0001", "FIRST"), pt("S0003", "EXTENDS")], later_refinement=["S0003"])
        self.assertTrue(any("ESTABLISHED" in x for x in C.summary_violations(bad, META)))

    def test_FD1b_extends_belongs_to_later_refinement_not_support(self):
        o = obj([pt("S0001", "FIRST"), pt("S0002", "EXTENDS")], later_support=["S0002"])
        self.assertTrue(any("later_support" in x for x in C.summary_violations(o, META)))

    def test_T75_summary_entry_not_a_timeline_point(self):
        o = obj([pt("S0001", "FIRST"), pt("S0002", "RESTATES")], later_support=["S0009"])
        self.assertTrue(any("not a timeline point" in x for x in C.summary_violations(o, META)))

    def test_T44_T46_T47_T48_contradiction_relation(self):
        und = obj([pt("S0003", "FIRST", "NOT-CONFIRMED"), pt("S0004", "CONTRADICTS", "NOT-CONFIRMED")],
                  contradicted_by=[{"source_id": "S0004", "contradicts": "S0003"}])
        self.assertEqual(C.summary_violations(und, META), [])                      # NOT-ESTABLISHED contradiction: valid
        for rel, needle in (({"source_id": "S0002", "contradicts": "S0009"}, "not a timeline point"),
                            ({"source_id": "S0002", "contradicts": "S0002"}, "itself")):
            o = obj([pt("S0001", "FIRST"), pt("S0002", "CONTRADICTS")],
                    contradicted_by=[{"source_id": "S0002", "contradicts": "S0001"}, rel])
            self.assertTrue(any(needle in x for x in C.summary_violations(o, META)), needle)


class Lifecycle(unittest.TestCase):
    def test_FD3_and_derived_position(self):
        o = obj([pt("S0001", "FIRST")], current_lifecycle="SOURCE-CLAIMED-SUPERSEDED")
        self.assertTrue(C.lifecycle_violations(o, META))
        o["superseded_by_sources"] = [{"source_id": "S0002", "position": "2024-06-01"}]
        self.assertEqual(C.lifecycle_violations(o, META), [])
        o["superseded_by_sources"] = [{"source_id": "S0003", "position": "2023-11-14"}]
        self.assertTrue(C.lifecycle_violations(o, META))                           # MTIME source must be UNDATED
        o["superseded_by_sources"] = [{"source_id": "S0003", "position": "UNDATED"}]
        self.assertEqual(C.lifecycle_violations(o, META), [])
        o2 = obj([pt("S0001", "FIRST")])
        o2["superseded_by_sources"] = [{"source_id": "S0002", "position": "2024-06-01"}]
        self.assertTrue(C.lifecycle_violations(o2, META))                          # list without SUPERSEDED lifecycle


class S3(unittest.TestCase):
    def test_canonical_matcher_ranges_and_slash_enumeration(self):
        for t in ("S0101-S0120", "S0101 through S0120", "S0101..S0120", "S0101–S0120", "between S0101 and S0120",
                  "S0101 up to S0120", "S0101‐S0120"):
            self.assertTrue(C.s3_violations([{"x": t}], "lab", "R", "B"), t)
        for t in ("S0957/S2361", "S0957 / S2361", "S0957, S2361"):
            self.assertEqual(C.s3_violations([{"x": t}], "lab", "R", "B"), [], t)

    def test_pointer_variants_normalized(self):
        for t in ("see  the register", "see the register", "rs-id 4", "cf. register", "B：3"):
            self.assertTrue(C.s3_violations([{"working_label": "lab", "note": t}], "lab", "R", "B", layer_a={0}), t)


class FirstEqualsBirths(unittest.TestCase):
    def test_first_kind_derived_from_births(self):
        o = obj([pt("S0001", "FIRST")], first_lexical="S0001")
        o["births"] = {"lexical": "ESTABLISHED-LEXICAL-BIRTH[S0001]"}
        self.assertEqual(C.summary_violations(o, META), [])
        o["timeline_summary"]["first_lexical"] = "S0002"
        self.assertTrue(any("first_lexical" in x for x in C.summary_violations(o, META)))


if __name__ == "__main__":
    unittest.main()
