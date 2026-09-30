#!/usr/bin/env python3
"""B3 EVIDENCE (R7 v2.3 §6, W6, HD-5): coverage from witnessed reads; byte-exact anchoring; claim → fact resolution;
reading rule; READ-LOG reconciliation. Synthetic bytes only.
  cd scripts/tests && PYTHONPATH=.:.. python3 -m unittest test_p3b_s5_r7_evidence
"""
import hashlib
import os
import sys
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, os.path.dirname(HERE))
import p3b_s5_r7_evidence as E                      # noqa: E402
import p3b_s5_r7_universe as U                      # noqa: E402

r5 = U.r5
RAW = ("alpha beta gamma. The   quoted\n  sentence spans lines. " + "".join(f"filler text line ä {i:05d}\n" for i in range(2500))
       + "filler text line ä repeated\n" * 3 + "tail marker here\n").encode()
PAGES = r5.byte_pages(RAW)


def reads(run, sid, pages):
    out = []
    for k in pages:
        _, rec = r5.byte_page_record(sid, RAW, k, hashlib.sha256(RAW).hexdigest())
        out.append({"kind": "read", "run": run, "source_id": sid, "page": k, "n_pages": len(PAGES),
                    "byte_start": rec["byte_start"], "byte_end": rec["byte_end"], "page_sha256": rec["page_sha256"],
                    "verified": True, "step": 7})
    return out


class Anchoring(unittest.TestCase):
    def iv(self, pages, run="R1"):
        return E.witnessed_intervals(reads(run, "S1", pages), run, "S1")

    def test_exact_on_witnessed_page(self):
        self.assertTrue(E.anchored("alpha beta gamma", RAW, self.iv([1])))

    def test_T29_not_witnessed(self):
        self.assertFalse(E.anchored("alpha beta gamma", RAW, self.iv([2])))

    def test_T63_multiple_occurrences_one_witnessed(self):
        q = "filler text line ä repeated"
        last = len(PAGES)
        self.assertTrue(E.anchored(q, RAW, self.iv([last])))
        self.assertFalse(E.anchored("alpha beta gamma", RAW, self.iv([last])))

    def test_nws_normalization_with_offset_map(self):
        self.assertTrue(E.anchored("The quoted sentence spans lines.", RAW, self.iv([1])))

    def test_T64_non_permitted_normalization(self):
        self.assertFalse(E.anchored("ALPHA BETA GAMMA", RAW, self.iv([1])))

    def test_T65_T66_T67_cross_page(self):
        a, b = PAGES[0][1] - 6, PAGES[0][1] + 6
        q = RAW[a:b].decode("utf-8", errors="strict")
        self.assertTrue(E.anchored(q, RAW, self.iv([1, 2])))
        self.assertFalse(E.anchored(q, RAW, self.iv([1])))
        mixed = E.witnessed_intervals(reads("R1", "S1", [1]) + reads("R2", "S1", [2]), "R1", "S1")
        self.assertFalse(E.anchored(q, RAW, mixed))

    def test_coverage_complete_only_with_all_verified_pages(self):
        cov = E.coverage(reads("R1", "S1", range(1, len(PAGES) + 1)))
        self.assertTrue(cov[("R1", "S1")]["complete"])
        cov = E.coverage(reads("R1", "S1", range(1, len(PAGES))))
        self.assertFalse(cov[("R1", "S1")]["complete"])


class Reconciliation(unittest.TestCase):
    def test_T14_T15_readlog_vs_witness(self):
        w = reads("R1", "S1", [1, 2])
        log = [{"run_id": "R1", "source_ids": ["S1"], "refused": False, "page": {"page": e["page"], "page_sha256": e["page_sha256"]}}
               for e in w]
        self.assertEqual(E.readlog_violations(w, log, 0), [])
        self.assertTrue(E.readlog_violations(w, log[:1], 0))                    # deleted line
        self.assertTrue(E.readlog_violations(w[:1], log, 0))                    # fabricated line
        self.assertTrue(E.readlog_violations(w, log + [{"run_id": "R1", "refused": True, "source_ids": ["S1"]}], 0))  # refusal residue


class Claims(unittest.TestCase):
    def test_claim_resolution_and_whole_file(self):
        w = reads("R1", "S1", range(1, len(PAGES) + 1))
        cov = E.coverage(w)
        rec = {"source_id": "S1", "reading_state": "WHOLE-FILE",
               "facts": [{"fact_id": "F1", "kind": "TIMELINE", "change_candidate": "CONTRADICTS", "quote": "alpha beta gamma"}]}
        claim = {"id": "timeline:S1", "kind": "TIMELINE", "source": "S1", "fields": {}, "whole": True}
        idx = E.facts_index({"R1": [rec]}, {"S1": RAW}, w)
        ok = E.claim_violations([claim], {"timeline:S1": [{"run": "R1", "source_id": "S1", "fact_id": "F1"}]}, idx,
                                {"S1": "WHOLE-FILE"}, cov, {"R1"}, "lab")
        self.assertEqual(ok, [])
        self.assertTrue(E.claim_violations([claim], {}, idx, {"S1": "WHOLE-FILE"}, cov, {"R1"}, "lab"))
        partial = E.coverage(w[:-1])
        self.assertTrue(E.claim_violations([claim], {"timeline:S1": [{"run": "R1", "source_id": "S1", "fact_id": "F1"}]},
                                           idx, {"S1": "READ-PARTIAL"}, partial, {"R1"}, "lab"))

    def test_T96_input_read_is_never_evidence(self):
        rec = {"source_id": "S1", "reading_state": "WHOLE-FILE",
               "facts": [{"fact_id": "F1", "kind": "TIMELINE", "change_candidate": "EXTENDS", "quote": "alpha beta gamma"}]}
        inputs_only = [{"kind": "read-input", "run": "R1", "path": "sr/OB/lab.json"}]
        idx = E.facts_index({"R1": [rec]}, {"S1": RAW}, inputs_only)
        self.assertFalse(idx[("R1", "S1", "F1")]["_anchored"])


class DecidedBinaryRowSource(unittest.TestCase):
    """Activation dry-run finding AF-1 (G-LOG-0101): a decided binary that is only a ROW source of a label (no stage-2
    hit there) has no disposition to give. Dispositions are required only for a decided file among the label's stage-2
    files; the other conditions (unread, NOT-CONSUMED, not cited, CONTRACT-DEVIATION for NOT-CONSUMED-ESCALATED) stay."""

    def obj(self, escalate=True, timeline_on_it=False):
        o = {"working_label": "lab", "stage2_dispositions": [], "timeline": [], "escalations": []}
        if escalate:
            o["escalations"].append({"field": "reading", "reason": "CONTRACT-DEVIATION", "detail": "S0675 not consumed (binary)"})
        return o

    def test_row_only_decided_file_passes_without_disposition(self):
        F, ok, fh = E.binary_decision_violations(self.obj(), {"S0675": "NOT-CONSUMED-ESCALATED"}, {"S0675": "NOT-CONSUMED"},
                                                 [], {"R1"}, [], stage2_files=set())
        self.assertEqual((F, ok), ([], {"S0675"}))

    def test_stage2_decided_file_still_needs_a_disposition(self):
        F, ok, _ = E.binary_decision_violations(self.obj(), {"S0675": "NOT-CONSUMED-ESCALATED"}, {"S0675": "NOT-CONSUMED"},
                                                [], {"R1"}, [], stage2_files={"S0675"})
        self.assertTrue(any("no disposition" in x for x in F))

    def test_row_only_decided_file_still_needs_escalation_and_no_citation(self):
        F, _, _ = E.binary_decision_violations(self.obj(escalate=False), {"S0675": "NOT-CONSUMED-ESCALATED"},
                                               {"S0675": "NOT-CONSUMED"}, [], {"R1"}, [], stage2_files=set())
        self.assertTrue(any("CONTRACT-DEVIATION" in x for x in F))
        F, _, _ = E.binary_decision_violations(self.obj(), {"S0675": "NOT-CONSUMED-ESCALATED"}, {"S0675": "NOT-CONSUMED"},
                                               [], {"R1"}, [{"source": "S0675"}], stage2_files=set())
        self.assertTrue(any("cited" in x for x in F))


if __name__ == "__main__":
    unittest.main()
