#!/usr/bin/env python3
"""B7 READER (R7 v2.3 §2.4, §5.4, §9): the revision-7 grammar, default-deny for batches with an activated R7 plan
(closes the legacy -R2* path, M2), the plan read from the VERIFIER's path and hash-checked against the manifest (m8), and
the self-describing header with a reader-generated invocation id (FD-5′). In-process; fake resolver with synthetic
bytes; temp root; the log writer writes into the temp root only. No corpus.
  cd scripts/tests && PYTHONPATH=.:.. python3 -m unittest test_p3b_read_source_r7
"""
import contextlib
import hashlib
import importlib.util
import io
import json
import os
import re
import shutil
import sys
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(HERE)
sys.path.insert(0, SCRIPTS)
import p3b_s5_r7_universe as U                      # noqa: E402

B = "OB9060"
PLAN = {"batch_id": B, "revision": 7, "labels": {
    "lab-a": {"index": 1, "path": "DECOMPOSED", "files": ["S0601", "S0602"], "runs": {
        f"{B}-R7-L01U01": {"role": "UNIT", "files": ["S0601"]}, f"{B}-R7-L01U02": {"role": "UNIT", "files": ["S0602"]},
        f"{B}-R7-L01S": {"role": "SYNTHESIS", "files": []}}}}}
RAW = {"S0601": ("synthetic S0601 text\n" * 2000).encode(), "S0602": b"synthetic S0602\n" * 10}


def load():
    cwd = os.getcwd()
    os.chdir(SCRIPTS)
    try:
        s = importlib.util.spec_from_file_location("rs_r7", os.path.join(SCRIPTS, "p3b_read_source.py"))
        m = importlib.util.module_from_spec(s)
        s.loader.exec_module(m)
    finally:
        os.chdir(cwd)
    return m


class Fake:
    def __init__(self):
        self.rows = {s: {"content_sha256": hashlib.sha256(b).hexdigest()} for s, b in RAW.items()}

    def read_many(self, sids):
        return {s: RAW[s] for s in sids}


class Reader(unittest.TestCase):
    def setUp(self):
        self.root = tempfile.mkdtemp(prefix="rs7-")
        self.rs = load()
        self.orig = (self.rs.dio.discovery_resolver, self.rs.dio.s3.CR)
        self.rs.dio.discovery_resolver = Fake
        self.rs.dio.s3.CR = self.root
        self.write_manifest(activated=True)

    def tearDown(self):
        self.rs.dio.discovery_resolver, self.rs.dio.s3.CR = self.orig
        shutil.rmtree(self.root, ignore_errors=True)

    def write_manifest(self, activated=True, plan_sha=None, plan=None):
        os.makedirs(os.path.join(self.root, "_batch_input_r2/s5"), exist_ok=True)
        with open(os.path.join(self.root, f"_batch_input_r2/s5/{B}.R7-PLAN.json"), "wb") as f:
            f.write(U.canon(plan or PLAN))
        entry = {"batch_id": B, "labels": ["lab-a"]}
        if activated:
            entry["r7_plan_sha256"] = plan_sha or U.sha(U.canon(PLAN))
        with open(os.path.join(self.root, "_batch_manifest_p3b_r2.jsonl"), "w") as f:
            f.write(json.dumps({"header": {"slice_root": "_batch_input_r2/s5", "contract": {"revision": 7}}}) + "\n")
            f.write(json.dumps(entry) + "\n")
            f.write(json.dumps({"batch_id": "OB9061", "labels": ["x"]}) + "\n")

    def run_reader(self, run, sid, label="lab-a", page="1", batch=B, mode=True):
        argv = ["--run", run, "--batch", batch, "--label", label, "--step", "7", "--page", page] + \
               (["--mode", "bytes"] if mode else []) + [sid]
        out, err, old = io.StringIO(), io.StringIO(), sys.argv
        sys.argv = ["p3b_read_source.py"] + argv
        try:
            with contextlib.redirect_stdout(out), contextlib.redirect_stderr(err):
                rc = self.rs.main()
        finally:
            sys.argv = old
        return rc, out.getvalue(), err.getvalue()

    def log(self, run):
        p = os.path.join(self.root, "ledger-p3b-r2", run, "READ-LOG.jsonl")
        return [json.loads(l) for l in open(p)] if os.path.exists(p) else []

    def test_r7_read_prints_self_describing_header_and_trailer(self):
        run = f"{B}-R7-L01U01"
        rc, out, err = self.run_reader(run, "S0601")
        self.assertEqual(rc, 0, err)
        first, last = out.rstrip("\n").split("\n")[0], out.rstrip("\n").split("\n")[-1]
        m = re.match(r"^=== S0601 page 1/\d+ bytes \d+-\d+ \(content sha256 [0-9a-f]{64}; page sha256 ([0-9a-f]{64})\) "
                     rf"run {run} batch {B} label lab-a inv ([0-9a-f]{{16}}) ===$", first)
        self.assertTrue(m, first)
        self.assertEqual(last, f"=== END S0601 page 1/{len(U.r5.byte_pages(RAW['S0601']))} ACK-{m.group(1)[-12:]} ===")
        e = self.log(run)[0]
        self.assertEqual(e["invocation_id"], m.group(2))
        self.assertFalse(e["refused"])

    def test_invocation_ids_are_reader_generated_and_distinct(self):
        run = f"{B}-R7-L01U01"
        a = self.run_reader(run, "S0601")[1].split(" inv ")[1][:16]
        b = self.run_reader(run, "S0601")[1].split(" inv ")[1][:16]
        self.assertNotEqual(a, b)

    def test_default_deny_legacy_and_withdrawn_grammars_for_an_activated_batch(self):
        for run, mode in ((f"{B}-R2", False), (f"{B}-R2.1", False), (f"{B}-R2S", False), (f"{B}-R6-L01U01", True)):
            rc, out, err = self.run_reader(run, "S0601", mode=mode)
            self.assertNotEqual(rc, 0, run)
            self.assertIn("REFUSED", err + out, run)
            self.assertEqual(out.strip(), "", run)                                   # no bytes served

    def test_legacy_grammar_unaffected_for_a_non_activated_batch(self):
        rc, out, err = self.run_reader("OB9061-R2", "S0601", label="x", batch="OB9061", mode=False)
        self.assertEqual(rc, 0, err)                                                 # historical behaviour unchanged

    def test_plan_refusals(self):
        cases = ((f"{B}-R7-L01S", "S0601", "lab-a", "synthesis"), (f"{B}-R7-L01U01", "S0602", "lab-a", "not permitted"),
                 (f"{B}-R7-L01U01", "S0601", "lab-b", "--label"), (f"{B}-R7-L09U01", "S0601", "lab-a", "not in the frozen plan"))
        for run, sid, lab, needle in cases:
            rc, out, err = self.run_reader(run, sid, label=lab)
            self.assertNotEqual(rc, 0, (run, sid, lab))
            self.assertIn(needle, err, (run, sid, lab))

    def test_plan_hash_must_match_the_manifest(self):
        self.write_manifest(activated=True, plan_sha="0" * 64)
        rc, out, err = self.run_reader(f"{B}-R7-L01U01", "S0601")
        self.assertNotEqual(rc, 0)
        self.assertIn("r7_plan_sha256", err)

    def test_r7_run_refused_without_activation(self):
        self.write_manifest(activated=False)
        rc, out, err = self.run_reader(f"{B}-R7-L01U01", "S0601")
        self.assertNotEqual(rc, 0)
        self.assertIn("no activated R7 plan", err)

    def test_r7_requires_byte_mode(self):
        rc, out, err = self.run_reader(f"{B}-R7-L01U01", "S0601", mode=False)
        self.assertNotEqual(rc, 0)


if __name__ == "__main__":
    unittest.main()
