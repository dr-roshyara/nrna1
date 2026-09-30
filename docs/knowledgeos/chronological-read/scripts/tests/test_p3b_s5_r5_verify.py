#!/usr/bin/env python3
"""Revision-5 verifier integration tests (Blocker 1, G-LOG-0082). Fully synthetic revision-5 fixtures: synthetic S-ids
(S95xx), synthetic labels, synthetic content bytes served by a FAKE resolver. No S5 corpus content, no reader
invocation, no hold-out list. Historical isolation is proven by comparing the patched verifier with the pre-change
verifier (git HEAD~ version of p3b_s5_verify.py) on the existing historical fixtures.
  cd scripts/tests && PYTHONPATH=.:.. python3 -m unittest test_p3b_s5_r5_verify
"""
import copy
import hashlib
import importlib.util
import json
import os
import shutil
import subprocess
import sys
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(HERE)
sys.path.insert(0, HERE)
sys.path.insert(0, SCRIPTS)
import test_p3b_s5_verify as T                       # noqa: E402  (historical fixture builders; shares the verifier)
v, c, prep = T.v, T.c, T.prep
_s = importlib.util.spec_from_file_location("p3b_s5_r5_verify", os.path.join(SCRIPTS, "p3b_s5_r5_verify.py"))
r5v = importlib.util.module_from_spec(_s)
_s.loader.exec_module(r5v)
r5 = r5v.r5
BATCH = "OB9501"


class FakeResolver:
    def __init__(self, contents):
        self.contents = contents

    def read_many(self, sids):
        return {s: self.contents[s] for s in sids}


def content(sid, n):
    """Synthetic bytes with multibyte characters (never corpus text)."""
    unit = f"synthetic {sid} ä€ line\n".encode("utf-8")
    return (unit * (n // len(unit) + 1))[:n].decode("utf-8", errors="ignore").encode("utf-8")


def byte_log(run, label, sid, b, mode=r5.READER_MODE):
    out = []
    for k in range(1, len(r5.byte_pages(b)) + 1):
        _, rec = r5.byte_page_record(sid, b, k, hashlib.sha256(b).hexdigest())
        if mode != r5.READER_MODE:
            rec["mode"] = mode
        out.append({"run_id": run, "batch_id": BATCH, "working_label": label, "step": 7, "source_ids": [sid],
                    "refused": False, "page": rec})
    return out


def tokens(b):
    return [r5.ack_token(hashlib.sha256(b[a:e]).hexdigest()) for a, e in r5.byte_pages(b)]


def slice_for(label, rows, s2, pairs=()):
    return {"hub": False, "stage2_files": sorted(s2), "p3a_pairs": list(pairs),
            "bundle": {"rows_verbatim": [json.dumps({"source_id": s}) for s in sorted(rows)]}}


def obj_for(label, rows, s2, found_sid=None):
    o = {"working_label": label, "timeline": [{"source_id": s, "change_vs_previous": "EXTENDS"} for s in sorted(rows)],
         "births": {"lexical": f"BIRTH-UNRESOLVED-MTIME-ONLY[{sorted(rows)[0]}]", "conceptual": "NOT-EVIDENCED-IN-CAPTURE"},
         "absences": {}, "escalations": [],
         "stage2_dispositions": [{"source_id": s, "hit_kind": "raw", "hit_key": 10, "term_index": 0,
                                  "method": "WHOLE-FILE", "by_dimension": {"d": "UNSUPPLIED-DIMENSION"}} for s in sorted(s2)],
         "dependency_edges": [{"target_label": "other", "kind": "USAGE", "edge_class": "R1-STRUCTURAL",
                               "source_id": sorted(rows)[0]}]}
    if found_sid:
        o["absences"]["d"] = {"resolution": "FOUND", "supplied_by": {"source_id": found_sid}}
    return o


def lint_of(obj, reg):
    return {"result": "PASS", "records_sha256": hashlib.sha256(json.dumps([obj] + reg, sort_keys=True,
                                                                           ensure_ascii=False).encode()).hexdigest()}


class Fixture:
    """A valid three-label revision-5 batch: SINGLE, DECOMPOSED, EMPTY."""

    def __init__(self):
        self.contents, self.log, self.objs, self.reg, self.slices, labels = {}, [], [], [], {}, {}
        # ---- SINGLE: 2 files, 1 row source, one pair to check
        lab, run = "syn-single", f"{BATCH}-R5-L01"
        rows, s2 = {"S9501"}, {"S9502"}
        sizes = {"S9501": 30_000, "S9502": 2_000}
        pairs = [{"pair_id": "RP9", "a": lab, "b": "other", "what_says_this": "basis S9501", "basis": "b"}]
        recs = []
        for sid in sorted(rows | s2):
            self.contents[sid] = content(sid, sizes[sid])
            self.log += byte_log(run, lab, sid, self.contents[sid])
            recs.append({"source_id": sid, "reading_state": "WHOLE-FILE", "ack_tokens": tokens(self.contents[sid]),
                         "pair_evidence_checks": ([{"pair_id": "RP9", "supports": "YES", "quote": "q"}]
                                                  if sid == "S9501" else [])})
        o = obj_for(lab, rows, s2, found_sid="S9502")
        self.objs.append(o)
        self.slices[lab] = slice_for(lab, rows, s2, pairs)
        labels[lab] = {"path": "SINGLE", "files": sorted(rows | s2), "row_sources": sorted(rows), "sizes": sizes,
                       "runs": {"single": run, "units": [], "synthesis": None}, "records": {run: recs},
                       "lint": lint_of(o, [])}
        # ---- DECOMPOSED: 4 files > 600 KB; rows S9511..S9513; units by row-first + FFD fill
        lab = "syn-decomp"
        rows, s2 = {"S9511", "S9512", "S9513"}, {"S9514"}
        sizes = {"S9511": 300_000, "S9512": 300_000, "S9513": 300_000, "S9514": 100_000}
        order = sorted(rows)
        units = r5.partition(rows | s2, rows, sizes, lambda s: order.index(s))
        uruns = [f"{BATCH}-R5-L02U{i:02d}" for i in range(1, len(units) + 1)]
        records = {}
        for run, uf in zip(uruns, units):
            records[run] = []
            for sid in uf:
                self.contents[sid] = content(sid, 5_000)               # content size need not equal the plan size
                self.log += byte_log(run, lab, sid, self.contents[sid])
                records[run].append({"source_id": sid, "reading_state": "WHOLE-FILE",
                                     "ack_tokens": tokens(self.contents[sid]), "pair_evidence_checks": []})
        o = obj_for(lab, rows, s2)
        self.objs.append(o)
        self.slices[lab] = slice_for(lab, rows, s2)
        labels[lab] = {"path": "DECOMPOSED", "files": sorted(rows | s2), "row_sources": sorted(rows), "sizes": sizes,
                       "packing_order": order, "units": units,
                       "runs": {"single": None, "units": uruns, "synthesis": f"{BATCH}-R5-L02S"}, "records": records,
                       "lint": lint_of(o, []),
                       "stages": {"units_validated_utc": "2026-10-01T10:00:00Z",
                                  "synthesis_dispatched_utc": "2026-10-01T11:00:00Z"}}
        # ---- EMPTY
        lab = "syn-empty"
        o = {"working_label": lab, "births": {"lexical": "NOT-EVIDENCED-IN-CAPTURE"},
             "absences": {"d": {"resolution": "ESCALATED"}}, "stage2_dispositions": [],
             "escalations": [{"reason": "OTHER", "detail": "EMPTY-REQUIRED-SET (R17: NOT-EVIDENCED-IN-CAPTURE)"}]}
        self.objs.append(o)
        self.slices[lab] = slice_for(lab, set(), set())
        labels[lab] = {"path": "EMPTY", "files": [], "row_sources": [], "sizes": {},
                       "runs": {"single": None, "units": [], "synthesis": None}, "records": {}}
        self.batch = {"batch_id": BATCH, "revision": 5, "labels": labels}

    def run(self, mutate=None):
        d = tempfile.mkdtemp(prefix="r5v-")
        try:
            b, objs, log = copy.deepcopy(self.batch), copy.deepcopy(self.objs), copy.deepcopy(self.log)
            slices = copy.deepcopy(self.slices)
            if mutate:
                mutate(b, objs, log, slices)
            with open(os.path.join(d, "R5-BATCH.json"), "w", encoding="utf-8") as f:
                json.dump(b, f)
            F, blog, cov = r5v.verify_r5(BATCH, d, objs, self.reg, log, lambda: FakeResolver(self.contents),
                                         None, None, slices)
            return F
        finally:
            shutil.rmtree(d, ignore_errors=True)


class ValidBatch(unittest.TestCase):
    def test_valid_single_decomposed_empty_pass(self):
        self.assertEqual(Fixture().run(), [])

    def test_deterministic(self):
        fx = Fixture()
        self.assertEqual(fx.run(), fx.run())


class PathStructure(unittest.TestCase):
    def has(self, F, text):
        self.assertTrue(any(text in x for x in F), F)

    def test_single_with_decomposition_records_fails(self):
        def m(b, o, l, s):
            b["labels"]["syn-single"]["runs"]["units"] = [f"{BATCH}-R5-L01U01"]
        self.has(Fixture().run(m), "SINGLE path with decomposition runs")

    def test_decomposed_without_unit_runs_fails(self):
        def m(b, o, l, s):
            b["labels"]["syn-decomp"]["runs"]["units"] = []
        self.has(Fixture().run(m), "DECOMPOSED path without valid unit runs")

    def test_synthesis_before_unit_validation_fails(self):
        def m(b, o, l, s):
            b["labels"]["syn-decomp"]["stages"]["synthesis_dispatched_utc"] = "2026-10-01T09:00:00Z"
        self.has(Fixture().run(m), "synthesis not strictly after unit validation")

    def test_empty_with_findings_fails(self):
        def m(b, o, l, s):
            o[2]["absences"]["d"]["resolution"] = "GENUINELY-UNDEFINED-AFTER-CENSUS"
        self.has(Fixture().run(m), "impossible with an empty required set")

    def test_declared_path_must_match_plan_600kb_boundary(self):
        def m(b, o, l, s):
            b["labels"]["syn-single"]["sizes"] = {"S9501": 598_000, "S9502": 2_001}     # 600,001 > 600 KB
        self.has(Fixture().run(m), "declared path SINGLE ≠ plan path DECOMPOSED")

    def test_exactly_600kb_is_single(self):
        self.assertEqual(r5.dispatch_path(2, 600_000), "SINGLE")

    def test_units_must_match_row_first_partition(self):
        def m(b, o, l, s):
            b["labels"]["syn-decomp"]["units"] = [["S9511", "S9514"], ["S9512", "S9513"]]
        self.has(Fixture().run(m), "units ≠ row-first + FFD fill partition")

    def test_packing_order_must_equal_rows(self):
        def m(b, o, l, s):
            b["labels"]["syn-decomp"]["packing_order"] = ["S9511", "S9512"]
        self.has(Fixture().run(m), "packing order ≠ row sources")

    def test_wrong_run_grammar(self):
        def m(b, o, l, s):
            b["labels"]["syn-single"]["runs"]["single"] = f"{BATCH}-R2"
        self.has(Fixture().run(m), "SINGLE path without a valid single-context run")

    def test_unsized_file_including_preserved_copy(self):
        def m(b, o, l, s):
            del b["labels"]["syn-single"]["sizes"]["S9502"]
        self.has(Fixture().run(m), "unsized required file")

    def test_batch_file_cannot_misstate_slice(self):
        def m(b, o, l, s):
            b["labels"]["syn-single"]["files"] = ["S9501"]
        self.has(Fixture().run(m), "files/row sources ≠ the slice's R(L)")

    def test_wrong_revision_batch_file(self):
        def m(b, o, l, s):
            b["revision"] = 4
        self.has(Fixture().run(m), "R5-BATCH.json missing or not for")


class ByteModeAndReading(unittest.TestCase):
    def has(self, F, text):
        self.assertTrue(any(text in x for x in F), F)

    def test_character_mode_read_fails(self):
        def m(b, o, l, s):
            l[0]["page"]["mode"] = "chars-r3"
        F = Fixture().run(m)
        self.has(F, "character-mode page read")

    def test_ack_mismatch_fails(self):
        def m(b, o, l, s):
            b["labels"]["syn-single"]["records"][f"{BATCH}-R5-L01"][0]["ack_tokens"] = ["ACK-000000000000"]
        self.has(Fixture().run(m), "acknowledgement tokens missing")

    def test_whole_file_without_complete_coverage_fails(self):
        def m(b, o, l, s):
            l[:] = [e for e in l if not (e["page"]["source_id"] == "S9501" and e["page"]["page"] == 2)]
        self.has(Fixture().run(m), "WHOLE-FILE without complete byte-mode coverage")

    def test_partial_failed_not_consumed_cannot_support_found(self):
        for st in ("READ-PARTIAL", "READ-FAILED", "NOT-CONSUMED"):
            def m(b, o, l, s, st=st):
                b["labels"]["syn-single"]["records"][f"{BATCH}-R5-L01"][1]["reading_state"] = st
            F = Fixture().run(m)
            self.has(F, "FOUND supplied by a non-WHOLE-FILE source")

    def test_binary_refusal_is_not_a_read(self):
        self.assertEqual(r5.is_binary(b"PK\x03\x04rest"), "SIGNATURE-ZIP")
        self.assertEqual(r5.log_level({"refused": True, "source_ids": ["S9501"]}, set(), set()), (2, None))

    def test_multibyte_page_boundary(self):
        b = content("S9599", 60_000)
        spans = r5.byte_pages(b)
        self.assertGreater(len(spans), 2)
        for a, e in spans:
            b[a:e].decode("utf-8")

    def test_synthesis_must_not_read(self):
        def m(b, o, l, s):
            l.extend(byte_log(f"{BATCH}-R5-L02S", "syn-decomp", "S9511", Fixture().contents["S9511"]))
        self.has(Fixture().run(m), "NON-PERMITTED-EXPOSURE")

    def test_unit_reading_outside_its_files(self):
        def m(b, o, l, s):
            fx = Fixture()
            l.extend(byte_log(f"{BATCH}-R5-L02U01", "syn-decomp", "S9514", fx.contents["S9514"]))
            b["labels"]["syn-decomp"]["units"]                   # unit 1 holds the row sources only
        F = Fixture().run(m)
        units = Fixture().batch["labels"]["syn-decomp"]["units"]
        if "S9514" not in units[0]:
            self.has(F, "NON-PERMITTED-EXPOSURE")


class Safeguards(unittest.TestCase):
    def has(self, F, text):
        self.assertTrue(any(text in x for x in F), F)

    def set_check(self, supports, quote="q"):
        def m(b, o, l, s):
            b["labels"]["syn-single"]["records"][f"{BATCH}-R5-L01"][0]["pair_evidence_checks"] = \
                [{"pair_id": "RP9", "supports": supports, "quote": quote}]
        return m

    def test_s1_yes_passes(self):
        self.assertEqual(Fixture().run(self.set_check("YES")), [])

    def test_s1_not_determinable_passes(self):
        self.assertEqual(Fixture().run(self.set_check("NOT-DETERMINABLE", None)), [])

    def test_s1_no_without_escalation_fails(self):
        self.has(Fixture().run(self.set_check("NO")), "NO without a VERDICT-EVIDENCE-CONFLICT")

    def test_s1_missing_check_fails(self):
        def m(b, o, l, s):
            b["labels"]["syn-single"]["records"][f"{BATCH}-R5-L01"][0]["pair_evidence_checks"] = []
        self.has(Fixture().run(m), "no pair-evidence check for pair RP9")

    def test_s2_invalid_birth_fails_single_path(self):
        def m(b, o, l, s):
            o[0]["births"]["lexical"] = "BIRTH-UNRESOLVED-MTIME-ONLY[S9502]"      # S9502 is stage-2 only, not a row
            b["labels"]["syn-single"]["lint"] = lint_of(o[0], [])
        self.has(Fixture().run(m), "S2: births.lexical cites S9502")

    def test_s2_valid_birth_passes(self):
        self.assertEqual(Fixture().run(), [])

    def test_s3_range_fails_and_lint_must_match_submission(self):
        def m(b, o, l, s):
            o[1]["timeline"][0]["note"] = "sources S9511 to S9513"
        F = Fixture().run(m)
        self.has(F, "S3: record 0: S-id range")
        self.has(F, "S3 pre-submit lint report missing, not PASS, or not for the submitted records")

    def test_s3_clean_passes(self):
        self.assertEqual(Fixture().run(), [])

    def test_edge_class_required(self):
        def m(b, o, l, s):
            del o[0]["dependency_edges"][0]["edge_class"]
            b["labels"]["syn-single"]["lint"] = lint_of(o[0], [])
        self.has(Fixture().run(m), "edge_class None")


class ScanTaxonomyInRunbook(unittest.TestCase):
    def test_levels(self):
        self.assertEqual(r5.scan_level({"name": "Bash", "input": {"command": "python3 p3b_read_source.py --help"}},
                                       "R", "B", set(), set()), (1, None))


# ------------------------------------------------------------------ historical isolation through the real verifier
def old_verifier():
    """The pre-change verifier (the commit before Blocker 1), loaded from git into a temp module file."""
    root = subprocess.run(["git", "-C", SCRIPTS, "rev-parse", "--show-toplevel"], capture_output=True, text=True).stdout.strip()
    rel = os.path.relpath(os.path.join(SCRIPTS, "p3b_s5_verify.py"), root)
    src = subprocess.run(["git", "-C", root, "show", f"2d12e96cb874357cc30bf6762bf869b8fba9d079:{rel}"],
                         capture_output=True, text=True).stdout
    tmpd = tempfile.mkdtemp(prefix="oldverify-")                 # R5-17: never write into the repository
    path = os.path.join(tmpd, "_old_p3b_s5_verify_for_test.py")
    with open(path, "w", encoding="utf-8") as f:
        f.write(src.replace("_HERE = os.path.dirname(os.path.abspath(__file__))", f"_HERE = {SCRIPTS!r}"))
    try:
        spec = importlib.util.spec_from_file_location("old_verify", path)
        mod = importlib.util.module_from_spec(spec)
        spec.loader.exec_module(mod)
    finally:
        shutil.rmtree(tmpd, ignore_errors=True)
    return mod


class HistoricalIsolation(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        cls.old = old_verifier()

    def setUp(self):
        self.root = tempfile.mkdtemp(prefix="r5iso-")

    def tearDown(self):
        shutil.rmtree(self.root, ignore_errors=True)

    def both(self, ctxs, rev, run=None):
        T.write(self.root, ctxs, header_extra=({"contract": {"revision": rev}} if rev else None))
        a = v.verify(ctxs[0]["ob"], root=self.root, run=run)[0]
        b = self.old.verify(ctxs[0]["ob"], root=self.root, run=run)[0]
        return a, b

    def test_revisions_1_to_4_byte_identical_reports(self):
        for rev in (None, 2, 3, 4):
            for ctx in (T.nonhub_ctx("PB02"), T.nonhub_ctx("PB03"), T.hub_ctx()):
                a, b = self.both([ctx], rev)
                self.assertEqual(json.dumps(a, sort_keys=True), json.dumps(b, sort_keys=True), (rev, ctx["ob"]))
                self.assertNotIn("R5", a["gates"])

    def test_r5_module_unreachable_for_historical_revisions(self):
        real = importlib.util.spec_from_file_location

        def guard(name, path, *a, **k):
            if str(path).endswith("p3b_s5_r5_verify.py"):
                raise AssertionError("revision-5 module loaded for a historical revision")
            return real(name, path, *a, **k)
        importlib.util.spec_from_file_location = guard
        try:
            for rev in (None, 3, 4):
                T.write(self.root, [T.nonhub_ctx("PB02")], header_extra=({"contract": {"revision": rev}} if rev else None))
                v.verify("OB9002", root=self.root)
        finally:
            importlib.util.spec_from_file_location = real

    # Revision 5 is WITHDRAWN (G-LOG-0083): the three tests below assert the withdrawal semantics.
    def test_historical_revision_rejects_r5_run(self):
        T.write(self.root, [T.nonhub_ctx("PB02")], header_extra={"contract": {"revision": 3}})
        body = v.verify("OB9002", root=self.root, run="OB9002-R5")[0]
        self.assertTrue(any(x.startswith("R6 run") for x in body["failures"]))

    def test_revision_5_batch_rejects_r2_run(self):
        T.write(self.root, [T.nonhub_ctx("PB02")], header_extra={"contract": {"revision": 5}})
        body = v.verify("OB9002", root=self.root, run="OB9002-R2")[0]
        self.assertTrue(any("revision 5 is withdrawn" in x for x in body["failures"]))

    def test_revision_5_gate_reached_and_reported(self):
        T.write(self.root, [T.nonhub_ctx("PB02")], header_extra={"contract": {"revision": 5}})
        body = v.verify("OB9002", root=self.root)[0]
        self.assertEqual(body["result"], "FAIL")
        self.assertEqual(body["gates"].get("R6"), "FAIL")         # a withdrawn revision can never PASS
        self.assertTrue(any("revision 5 is withdrawn" in x for x in body["failures"]))


if __name__ == "__main__":
    unittest.main()
