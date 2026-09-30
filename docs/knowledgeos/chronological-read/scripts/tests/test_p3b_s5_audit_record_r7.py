"""Tests (EG-9, G-LOG-0106 part (g)): the §21 audit record for a contract-revision-7 batch (`<B>-R7`).

scripts/p3b_s5_audit_record.py accepts exactly `<B>-R7` (besides the unchanged R2 forms) and binds the findings to
  (i)   the §21 sample (`--sample`, the p3b_s5_audit_sample.py output: header, body sha, batch, input hashes),
  (ii)  the verified assembly bytes `ledger-p3b-r2/<B>-R7/{objects,register,p1-gap-capture}.jsonl` (sha256 recorded),
  (iii) the batch's VERIFIED state in P3B-STATE at its current attempt (evidenced BATCH-PASS entry).
Dispositions are recorded; AUDIT-UPHELD produces an append-only rev3 §12.3 correction entry `P3B-COR-####` in
`P3B-CORRECTIONS.jsonl` (CR root) and is never written into the assembly (R-I, H-EG9-6). Coverage of the sample is REPORTED, not enforced (H-EG9-4 undecided).
Tests E9-01…E9-12 of audit-p3b/eg9/EG9-SPEC-A.md (E9-08/E9-09 live in test_p3b_s5_accept_tranche.py).
Synthetic fixtures only, all under /tmp/s5ops-build/.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_audit_record_r7
"""
import io
import json
import os
import re
import unittest
from contextlib import redirect_stderr, redirect_stdout

import p3b_s5_ops_testlib as T
from test_p3b_s5_state_r7_evidence import r7_state

stm = T.ops.load_script("p3b_s5_state")
ar = T.ops.load_script("p3b_s5_audit_record")
AS = T.ops.load_script("p3b_s5_audit_sample")
c = T.c
B, RUN = "OB0004", "OB0004-R7"
ERRORS = (c.S5Error, ar.c.S5Error)
# rev3 §12.3 fields + the R-I status + the run identity (G-LOG-0106; R-S4 M1)
COR_FIELDS = ("cor_id", "target_record", "target_artifact", "reason", "evidence", "new_record_ref", "author_role", "date",
              "status", "batch_id", "run_id", "attempt")


def quiet(fn, *a, **kw):
    with redirect_stdout(io.StringIO()), redirect_stderr(io.StringIO()):
        return fn(*a, **kw)


def write_assembly(d, batch=B):
    run = f"{batch}-R7"
    adir = os.path.join(d, "ledger-p3b-r2", run)
    objs = [{"batch_id": batch, "run_id": run, "working_label": f"zz-syn-{i}",
             "semantic_status": "CONTESTED" if i == 0 else "RESOLVED"} for i in range(6)]
    reg = [{"batch_id": batch, "run_id": run, "rs_id": f"RS-{1001 + i}",
            "kind": "HYPOTHESIS" if i == 0 else "OBSERVATION"} for i in range(4)]
    T.write_jsonl(os.path.join(adir, "objects.jsonl"), objs)
    T.write_jsonl(os.path.join(adir, "register.jsonl"), reg)
    T.write_jsonl(os.path.join(adir, "p1-gap-capture.jsonl"), [{"batch_id": batch, "gap_id": "G-1001"}])
    return adir


def draw_sample(d, adir, batch=B, name=None, index=0):
    p = os.path.join(d, "audit-p3b", name or f"S5-AUDIT-SAMPLE-{batch}-R7.json")
    rc = quiet(AS.main, ["sample", adir, "--index", str(index), "--n-batches", "3", "--batch-id", batch, "--out", p])
    assert rc == 0
    return p


def sampled_ids(sample_path):
    b = json.load(open(sample_path))["body"]
    return sorted(set(b["mandatory_contested_or_homonym_split"] + b["mandatory_register"] + b["other_register_sample"]
                      + b["seeded_records"]["objects"] + b["seeded_records"]["register"]))


def findings_for(sample_path, disposition="ORIGINAL-UPHELD", skip=0):
    ids = sampled_ids(sample_path)[skip:]
    return {"auditor_model_id": "claude-opus-5-5", "audit_group": "G00",
            "sample_sha256": json.load(open(sample_path))["header"]["output_sha256"],
            "records_rederived": len(ids), "register_reviewed": 4,
            "dispositions": [{"record": r, "field": "semantic_status", "disposition": disposition, "note": "n"}
                             for r in ids]}


def to_verified(st_path, d, batch=B):
    st = stm.load(st_path)
    stm.transition(st, batch, "DISPATCHED")
    stm.transition(st, batch, "PROPOSED", reason="assembly + freeze-final complete")
    ev = T.write_report(os.path.join(d, "reports"), f"S5-VERIFY-{batch}-R7.json", batch, f"{batch}-R7", result="BATCH-PASS")
    stm.transition(st, batch, "VERIFIED", evidence=ev)
    stm.save(st, st_path)
    return ev


def tree(d):
    out = {}
    for root, _, files in os.walk(d):
        for f in files:
            p = os.path.join(root, f)
            out[os.path.relpath(p, d)] = T.ops.sha256_path(p)
    return out


class R7Fixture(unittest.TestCase):
    def setUp(self):
        self.d, self.st = r7_state()
        self.verify_report = to_verified(self.st, self.d)
        self.adir = write_assembly(self.d)
        self.sample = draw_sample(self.d, self.adir)
        self.out = os.path.join(self.d, "audit-p3b", f"S5-AUDIT-{RUN}.json")
        self.cor = os.path.join(self.d, "P3B-CORRECTIONS.jsonl")

    def fp(self, f, name="findings.json"):
        p = os.path.join(self.d, name)
        with open(p, "w") as fh:
            json.dump(f, fh)
        return p

    def run_ar(self, f=None, run=RUN, batch=B, sample="default", assembly=None, out=None, state=None):
        args = ["--batch", batch, "--run", run, "--findings", self.fp(f or findings_for(self.sample))]
        if sample == "default":
            sample = self.sample
        if sample is not None:
            args += ["--sample", sample]
        args += ["--state", state or self.st, "--assembly", assembly or self.adir, "--out", out or self.out,
                 "--corrections", self.cor]
        return quiet(ar.main, args)

    def assertRefused(self, rc):
        self.assertEqual(rc, 2)
        self.assertFalse(os.path.exists(self.out))
        self.assertFalse(os.path.exists(self.cor))


class E901ValidR7(R7Fixture):
    def test_report_written_pass_and_bound(self):
        rc = self.run_ar()
        self.assertEqual(rc, 0)
        doc = json.load(open(self.out))
        body = doc["body"]
        self.assertEqual(body["result"], "PASS")
        self.assertEqual((body["batch_id"], body["run_id"], body["revision"]), (B, RUN, 7))
        self.assertEqual(doc["header"]["script_name"], "scripts/p3b_s5_audit_record.py")
        bind = body["binding"]
        smp = json.load(open(self.sample))
        self.assertEqual(bind["sample"]["output_sha256"], smp["header"]["output_sha256"])
        self.assertEqual(bind["sample"]["file_sha256"], T.ops.sha256_path(self.sample))
        for n in ("objects.jsonl", "register.jsonl", "p1-gap-capture.jsonl"):
            self.assertEqual(bind["assembly"]["files"][n], T.ops.sha256_path(os.path.join(self.adir, n)))
        st = stm.load(self.st)
        ver = [e for e in st["history"] if e["run_id"] == RUN and e["to"] == "VERIFIED"][-1]
        self.assertEqual(bind["verified"]["state_entry_sha256"], ver["entry_sha256"])
        self.assertEqual(bind["verified"]["verify_report_sha256"], T.ops.sha256_path(self.verify_report))
        self.assertEqual(bind["verified"]["attempt"], 1)
        self.assertIsNotNone(stm.check_evidence("AUDITED", self.out, B, RUN))

    def test_written_once(self):
        self.assertEqual(self.run_ar(), 0)
        self.assertEqual(self.run_ar(), 2)


class E902Grammar(R7Fixture):
    def test_non_assembly_r7_ids_refused(self):
        for run in ("OB0004-R7-L01", "OB0004-R7-L01S", "OB0004-R7-L01U02", "OB0004-R7.A2", "OB0004-R7S",
                    "OB0005-R7", "OB0004-R7 ", "OB0004-R77"):
            self.assertRefused(self.run_ar(run=run))

    def test_r2_forms_unchanged(self):
        f = {"auditor_model_id": "m", "audit_group": "G01", "sample_sha256": "a" * 64, "records_rederived": 1,
             "register_reviewed": 1, "dispositions": [{"record": "x", "field": "f", "disposition": "JUDGMENT-CALL",
                                                       "note": "n"}]}
        for run in ("OB0004-R2", "OB0004-R2.2", "OB0004-R2S"):
            out = os.path.join(self.d, f"S5-AUDIT-{run}.json")
            rc = quiet(ar.main, ["--batch", B, "--run", run, "--findings", self.fp(f), "--out", out])
            self.assertEqual(rc, 0, run)
            body = json.load(open(out))["body"]
            self.assertEqual(set(body), {"batch_id", "run_id", "comparison", "findings", "protocol_violations", "result"})
            self.assertEqual(body["comparison"], run.endswith("R2S"))
        self.assertEqual(quiet(ar.main, ["--batch", B, "--run", "OB0005-R2", "--findings", "/dev/null"]), 2)


class E903SampleBinding(R7Fixture):
    def test_missing_sample_refused(self):
        self.assertRefused(self.run_ar(sample=None))

    def test_sample_sha_mismatch_refused(self):
        f = findings_for(self.sample)
        f["sample_sha256"] = "b" * 64
        self.assertRefused(self.run_ar(f))

    def test_sample_for_another_batch_refused(self):
        other = draw_sample(self.d, self.adir, batch="OB0005", name="S5-AUDIT-SAMPLE-OB0005-R7.json")
        self.assertRefused(self.run_ar(findings_for(other), sample=other))

    def test_tampered_sample_body_refused(self):
        doc = json.load(open(self.sample))
        doc["body"]["seeded_records"]["objects"] = []
        p = os.path.join(self.d, "audit-p3b", "tampered.json")
        json.dump(doc, open(p, "w"))
        self.assertRefused(self.run_ar(findings_for(self.sample), sample=p))

    def test_sample_not_from_the_sample_tool_refused(self):
        doc = json.load(open(self.sample))
        doc["header"]["script_name"] = "scripts/handmade.py"
        p = os.path.join(self.d, "audit-p3b", "handmade.json")
        json.dump(doc, open(p, "w"))
        self.assertRefused(self.run_ar(findings_for(self.sample), sample=p))

    def test_sample_over_another_directory_refused(self):
        d2 = T.tmpdir("othr-asm")
        a2 = write_assembly(d2)                         # identical bytes, other place: the input keys differ
        s2 = draw_sample(d2, a2)
        self.assertRefused(self.run_ar(findings_for(s2), sample=s2))


class E904AssemblyBytes(R7Fixture):
    def test_assembly_changed_after_sample_refused(self):
        for n in ("objects.jsonl", "register.jsonl"):
            p = os.path.join(self.adir, n)
            orig = T.read(p)
            with open(p, "a") as f:
                f.write("\n")
            self.assertRefused(self.run_ar())
            with open(p, "w") as f:
                f.write(orig)
        self.assertEqual(self.run_ar(), 0)

    def test_missing_assembly_file_refused(self):
        os.remove(os.path.join(self.adir, "p1-gap-capture.jsonl"))
        self.assertRefused(self.run_ar())


class E905VerifiedState(unittest.TestCase):
    def build(self, states, attempt=None):
        d, st_path = r7_state()
        st = stm.load(st_path)
        for s in states:
            ev = None
            if s == "VERIFIED":
                ev = T.write_report(os.path.join(d, "reports"), "v.json", B, RUN, result="BATCH-PASS")
            if s == "AUDITED":
                ev = T.write_report(os.path.join(d, "reports"), "AUDITED.json", B, RUN, result="PASS")
            kw = {"failure_class": "D"} if s == "FAILED" else {}      # EG-6: an R7 FAILED names its class
            stm.transition(st, B, s, reason="cause" if s == "FAILED" else "", evidence=ev, **kw)
        if attempt is not None:                        # the attempt field, wherever the state tool keeps it
            st["batches"][B]["attempt"] = attempt
            st["batches"][B]["runs"][-1]["attempt"] = attempt
        stm.save(st, st_path)
        adir = write_assembly(d)
        sample = draw_sample(d, adir)
        fp = os.path.join(d, "f.json")
        json.dump(findings_for(sample), open(fp, "w"))
        out = os.path.join(d, "audit-p3b", "S5-AUDIT-OB0004-R7.json")
        rc = quiet(ar.main, ["--batch", B, "--run", RUN, "--findings", fp, "--sample", sample, "--state", st_path,
                             "--assembly", adir, "--out", out, "--corrections", os.path.join(d, "cor.jsonl")])
        return rc, out

    def test_not_verified_refused(self):
        for states in ([], ["DISPATCHED"], ["DISPATCHED", "PROPOSED"], ["DISPATCHED", "PROPOSED", "FAILED"],
                       ["DISPATCHED", "PROPOSED", "VERIFIED", "AUDITED"]):
            rc, out = self.build(states)
            self.assertEqual(rc, 2, states)
            self.assertFalse(os.path.exists(out))

    def test_verified_of_another_attempt_refused(self):
        rc, out = self.build(["DISPATCHED", "PROPOSED", "VERIFIED"], attempt=2)
        self.assertEqual(rc, 2)
        self.assertFalse(os.path.exists(out))
        rc, out = self.build(["DISPATCHED", "PROPOSED", "VERIFIED"], attempt=1)
        self.assertEqual(rc, 0)

    def test_verified_without_evidence_refused(self):
        d, st_path = r7_state()
        to_verified(st_path, d)
        raw = json.loads(T.read(st_path))
        for e in raw["history"]:
            e.pop("evidence", None)
        prev = None
        for e in raw["history"]:
            e["prev_sha256"] = prev
            e["entry_sha256"] = T.ops.canon_hash({k: v for k, v in e.items() if k != "entry_sha256"})
            prev = e["entry_sha256"]
        raw["history_head_sha256"] = prev
        with open(st_path, "w") as f:
            json.dump(raw, f)
        adir = write_assembly(d)
        sample = draw_sample(d, adir)
        fp = os.path.join(d, "f.json")
        json.dump(findings_for(sample), open(fp, "w"))
        out = os.path.join(d, "out.json")
        rc = quiet(ar.main, ["--batch", B, "--run", RUN, "--findings", fp, "--sample", sample, "--state", st_path,
                             "--assembly", adir, "--out", out, "--corrections", os.path.join(d, "cor.jsonl")])
        self.assertEqual(rc, 2)
        self.assertFalse(os.path.exists(out))

    def test_r2_batch_state_refused_for_r7_run(self):
        """A state whose batch is still at revision 2 cannot be audited as <B>-R7."""
        d = T.tmpdir("r2st")
        man, st_path = os.path.join(d, "m.jsonl"), os.path.join(d, "P3B-STATE.json")
        T.write_manifest(man, ["OB0004"])
        stm.init(man, st_path)
        adir = write_assembly(d)
        sample = draw_sample(d, adir)
        fp = os.path.join(d, "f.json")
        json.dump(findings_for(sample), open(fp, "w"))
        rc = quiet(ar.main, ["--batch", B, "--run", RUN, "--findings", fp, "--sample", sample, "--state", st_path,
                             "--assembly", adir, "--out", os.path.join(d, "o.json"), "--corrections",
                             os.path.join(d, "c.jsonl")])
        self.assertEqual(rc, 2)


class E906Coverage(R7Fixture):
    def test_coverage_reported_not_enforced(self):
        rc = self.run_ar(findings_for(self.sample, skip=1))
        self.assertEqual(rc, 0)
        cov = json.load(open(self.out))["body"]["coverage"]
        self.assertFalse(cov["enforced"])
        self.assertFalse(cov["complete"])
        self.assertEqual(cov["uncovered"], sampled_ids(self.sample)[:1])
        self.assertTrue(cov["manifest_index_matches_state"])

    def test_full_coverage_reported_complete(self):
        self.assertEqual(self.run_ar(), 0)
        cov = json.load(open(self.out))["body"]["coverage"]
        self.assertTrue(cov["complete"])
        self.assertEqual(cov["uncovered"], [])
        self.assertEqual(cov["sampled_records"], len(sampled_ids(self.sample)))


class E907StateAfterAudit(R7Fixture):
    def test_pass_report_is_audited_evidence(self):
        self.assertEqual(self.run_ar(), 0)
        st = stm.load(self.st)
        stm.transition(st, B, "AUDITED", evidence=self.out)
        self.assertEqual(st["batches"][B]["state"], "AUDITED")

    def test_fail_report_refused_then_failed_class_h_no_rerun(self):
        rc = self.run_ar(findings_for(self.sample, disposition="PROTOCOL-VIOLATION"))
        self.assertEqual(rc, 1)
        self.assertEqual(json.load(open(self.out))["body"]["result"], "FAIL")
        st = stm.load(self.st)
        with self.assertRaises(ERRORS):
            stm.transition(st, B, "AUDITED", evidence=self.out)
        stm.transition(st, B, "FAILED", reason="§21 FAIL (class H)")
        self.assertEqual(st["batches"][B]["state"], "FAILED")
        self.assertEqual(st["history"][-1]["failure_class"], "H")     # EG-6: a failure after VERIFIED is class H
        with self.assertRaises(ERRORS):
            stm.new_run(st, B, "re-run")
        assert_no_new_attempt(self, st, B)


def assert_no_new_attempt(tc, st, batch):
    """EG-6 part (d) `new_attempt`: refused once any attempt reached VERIFIED (class H is never re-run)."""
    if not hasattr(stm, "new_attempt"):
        return
    for cls in ("H", "X", "A1", "A2"):
        with tc.assertRaises(ERRORS):
            stm.new_attempt(st, batch, cls, {"path": "x", "sha256": "0" * 64}, may_start=lambda b: (True, ""))


class E910Firewall(unittest.TestCase):
    def test_sample_independent_of_a_frozen_sample_file(self):
        d = T.tmpdir("fw")
        adir = write_assembly(d)
        s1 = json.load(open(draw_sample(d, adir, name="s1.json")))["body"]
        with open(os.path.join(d, "FREEZE-2-RECORD.json"), "w") as f:       # a synthetic frozen sample naming the labels
            json.dump({"sample": [f"zz-syn-{i}" for i in range(6)]}, f)
        with open(os.path.join(adir, "FREEZE-2-RECORD.json"), "w") as f:
            json.dump({"sample": [f"zz-syn-{i}" for i in range(6)]}, f)
        s2 = json.load(open(draw_sample(d, adir, name="s2.json")))["body"]
        self.assertEqual(c.canon(s1), c.canon(s2))

    def test_static_no_freeze2_reference(self):
        banned = re.compile(r"FREEZE-2-RECORD|p3b_s5_r7_stats|estimate_v7|e3f3ed76|8e0d9cb4|"
                            r"128527203382009690619999539329395210354|200059834720872615579209696013017923603", re.I)
        for name in ("p3b_s5_audit_sample.py", "p3b_s5_audit_record.py"):
            src = T.read(os.path.join(T.SCRIPTS, name))
            self.assertIsNone(banned.search(src), name)


class E911NonCorrective(R7Fixture):
    def test_writes_only_the_report(self):
        self.fp(findings_for(self.sample))             # the auditor's findings file exists before the tool runs
        before = tree(self.d)
        self.assertEqual(self.run_ar(), 0)
        after = tree(self.d)
        self.assertEqual(set(after) - set(before), {os.path.relpath(self.out, self.d)})
        self.assertEqual({k: v for k, v in after.items() if k in before}, before)

    def test_audit_upheld_corrections_recorded_apart_from_assembly(self):
        f = findings_for(self.sample)
        f["dispositions"][0]["disposition"] = "AUDIT-UPHELD"
        f["dispositions"][0]["note"] = "the audit's reading differs"
        self.fp(f)
        before = tree(self.d)
        self.assertEqual(self.run_ar(f), 0)                  # AUDIT-UPHELD is not a protocol violation
        after = tree(self.d)
        self.assertEqual(set(after) - set(before),
                         {os.path.relpath(self.out, self.d), os.path.relpath(self.cor, self.d),
                          os.path.relpath(self.cor + ".lock", self.d)})           # the advisory-lock sidecar
        self.assertEqual({k: v for k, v in after.items() if k in before}, before)   # assembly + state untouched
        cors = T.read_jsonl(self.cor)
        self.assertEqual(len(cors), 1)
        r = cors[0]
        # rev3 §12.3: a P3B-COR-#### entry with exactly the §12.3 fields, plus the R-I status and the run identity
        self.assertEqual(set(r), set(COR_FIELDS))
        self.assertEqual(r["cor_id"], "P3B-COR-0001")
        self.assertEqual((r["batch_id"], r["run_id"], r["attempt"], r["target_record"]),
                         (B, RUN, 1, f["dispositions"][0]["record"]))
        self.assertEqual(r["status"], "RECORDED-NOT-APPLIED")
        self.assertIsNone(r["new_record_ref"])
        self.assertEqual(r["reason"], "the audit's reading differs")
        self.assertRegex(r["date"], r"^\d{4}-\d{2}-\d{2}$")
        self.assertEqual(r["evidence"]["assembly_sha256"]["objects.jsonl"],
                         T.ops.sha256_path(os.path.join(self.adir, "objects.jsonl")))
        self.assertEqual(r["evidence"]["field"], "semantic_status")
        body = json.load(open(self.out))["body"]
        self.assertEqual(body["corrections"]["count"], 1)
        self.assertFalse(body["corrections"]["applied"])
        self.assertEqual(body["corrections"]["ids"], ["P3B-COR-0001"])
        self.assertEqual(r["evidence"]["audit_report_output_sha256"], json.load(open(self.out))["header"]["output_sha256"])

    def test_next_free_id_append_only(self):
        T.write_jsonl(self.cor, [{"cor_id": "P3B-COR-0007", "run_id": "OB0009-R2", "target_record": "x"},
                                 {"cor_id": "P3B-COR-0003", "run_id": "OB0008-R2", "target_record": "y"}])
        before = T.read(self.cor)
        f = findings_for(self.sample)
        for d in f["dispositions"][:2]:
            d["disposition"] = "AUDIT-UPHELD"
        self.assertEqual(self.run_ar(f), 0)
        after = T.read(self.cor)
        self.assertTrue(after.startswith(before))              # never rewrites an existing line
        self.assertEqual([r["cor_id"] for r in T.read_jsonl(self.cor)[2:]], ["P3B-COR-0008", "P3B-COR-0009"])
        self.assertEqual(json.load(open(self.out))["body"]["corrections"]["ids"], ["P3B-COR-0008", "P3B-COR-0009"])

    def test_malformed_existing_id_refused(self):
        T.write_jsonl(self.cor, [{"cor_id": "COR-7", "run_id": "OB0009-R2"}])
        before = T.read(self.cor)
        self.assertEqual(self.run_ar(), 2)
        self.assertFalse(os.path.exists(self.out))
        self.assertEqual(T.read(self.cor), before)

    def test_corrections_for_this_run_already_present_refused(self):
        T.write_jsonl(self.cor, [{"run_id": RUN, "cor_id": "P3B-COR-0001"}])
        before = T.read(self.cor)
        self.assertEqual(self.run_ar(), 2)
        self.assertFalse(os.path.exists(self.out))
        self.assertEqual(T.read(self.cor), before)

    def test_default_corrections_log_is_the_rev3_artifact(self):
        self.assertEqual(ar.CORRECTIONS, "P3B-CORRECTIONS.jsonl")
        self.assertNotIn("S5-AUDIT-CORRECTIONS", T.read(os.path.join(T.SCRIPTS, "p3b_s5_audit_record.py")))
        self.assertTrue(T.ops.check_paths([ar.CORRECTIONS]))            # allowlisted (G-LOG-0106)

    def upheld(self, n=2):
        f = findings_for(self.sample)
        for d in f["dispositions"][:n]:
            d["disposition"] = "AUDIT-UPHELD"
        return f

    def test_crash_after_corrections_append_then_rerun_completes(self):
        """Ordering: corrections are appended BEFORE the write-once report, so no committed artifact references a
        missing record; a re-run after a crash between the two completes with the same ids and no duplicates."""
        f = self.upheld()
        orig = ar.ops.write_new

        def crash(*a, **kw):
            raise OSError("simulated crash before the report write")
        ar.ops.write_new = crash
        try:
            with self.assertRaises(OSError):
                self.run_ar(f)
        finally:
            ar.ops.write_new = orig
        self.assertFalse(os.path.exists(self.out))
        first = T.read(self.cor)
        self.assertEqual([r["cor_id"] for r in T.read_jsonl(self.cor)], ["P3B-COR-0001", "P3B-COR-0002"])
        self.assertEqual(self.run_ar(f), 0)                               # recovery: completes by writing the report
        self.assertEqual(T.read(self.cor), first)                         # no duplicate, no rewrite
        body = json.load(open(self.out))["body"]
        self.assertEqual(body["corrections"]["ids"], ["P3B-COR-0001", "P3B-COR-0002"])
        hdr = json.load(open(self.out))["header"]
        for r in T.read_jsonl(self.cor):
            self.assertEqual(r["evidence"]["audit_report_output_sha256"], hdr["output_sha256"])
        self.assertEqual(self.run_ar(f), 2)                               # complete: a further run is refused

    def test_crash_then_rerun_with_different_findings_refused(self):
        orig = ar.ops.write_new
        ar.ops.write_new = lambda *a, **kw: (_ for _ in ()).throw(OSError("crash"))
        try:
            with self.assertRaises(OSError):
                self.run_ar(self.upheld(2))
        finally:
            ar.ops.write_new = orig
        before = T.read(self.cor)
        for f in (self.upheld(1), findings_for(self.sample)):             # would-be entries differ from the logged ones
            self.assertEqual(self.run_ar(f), 2)
            self.assertFalse(os.path.exists(self.out))
            self.assertEqual(T.read(self.cor), before)


def _worker(cor_ap, tag, rounds, go):
    go.wait()
    for r in range(rounds):
        run = f"OB9{tag}{r:02d}-R7"
        ar.append_corrections(cor_ap, run, lambda first, date, run=run: [
            {"cor_id": f"P3B-COR-{first + k:04d}", "run_id": run, "k": k, "date": date} for k in range(3)])


class CorrectionLogConcurrency(unittest.TestCase):
    """R-S4 v2: id allocation + append is atomic across processes (fcntl.flock on the `.lock` sidecar; Linux)."""

    def test_concurrent_appenders_get_unique_contiguous_ids(self):
        import multiprocessing as mp
        import time
        ctx = mp.get_context("fork")
        d = T.tmpdir("corlock")
        cor = os.path.join(d, "P3B-CORRECTIONS.jsonl")
        seam = ar._BETWEEN_READ_AND_APPEND
        ar._BETWEEN_READ_AND_APPEND = lambda: time.sleep(0.003)       # widen the race window (inherited on fork)
        try:
            go = ctx.Event()
            ps = [ctx.Process(target=_worker, args=(cor, t, 8, go)) for t in range(4)]
            for p in ps:
                p.start()
            go.set()
            for p in ps:
                p.join(60)
            self.assertEqual([p.exitcode for p in ps], [0, 0, 0, 0])
        finally:
            ar._BETWEEN_READ_AND_APPEND = seam
        rows = T.read_jsonl(cor)
        ids = [r["cor_id"] for r in rows]
        self.assertEqual(len(rows), 4 * 8 * 3)
        self.assertEqual(ids, [f"P3B-COR-{i:04d}" for i in range(1, len(rows) + 1)])   # unique, contiguous, in order
        for i in range(0, len(rows), 3):                                                # one run's entries stay together
            self.assertEqual(len({r["run_id"] for r in rows[i:i + 3]}), 1)
            self.assertEqual([r["k"] for r in rows[i:i + 3]], [0, 1, 2])

    def test_duplicate_id_after_append_detected(self):
        d = T.tmpdir("cordup")
        cor = os.path.join(d, "P3B-CORRECTIONS.jsonl")
        ar.append_corrections(cor, "OB9000-R7", lambda first, date: [{"cor_id": f"P3B-COR-{first:04d}",
                                                                       "run_id": "OB9000-R7", "date": date}])
        with self.assertRaises(ERRORS):                   # a builder that re-uses an id already present is caught
            ar.append_corrections(cor, "OB9001-R7", lambda first, date: [{"cor_id": "P3B-COR-0001",
                                                                           "run_id": "OB9001-R7", "date": date}])


class E912Resume(unittest.TestCase):
    def test_next_action_for_r7_verified(self):
        d, st_path = r7_state()
        to_verified(st_path, d)
        plan = stm.resume(stm.load(st_path), os.path.join(d, "new.jsonl"))
        row = [p for p in plan["batches"] if p["batch_id"] == B][0]
        self.assertEqual((row["run_id"], row["state"], row["next"]), (RUN, "VERIFIED", "run §21 audit"))


if __name__ == "__main__":
    unittest.main()
