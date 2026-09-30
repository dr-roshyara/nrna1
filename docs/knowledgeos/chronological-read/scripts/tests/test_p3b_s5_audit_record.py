"""Tests for p3b_s5_audit_record.py (the §21 audit report) and for the link-1 binding in p3b_s5a_generators.
Temp files only."""
import importlib.util
import json
import os
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))


def load(name):
    spec = importlib.util.spec_from_file_location(name, os.path.join(HERE, "..", name + ".py"))
    m = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(m)
    return m


ar = load("p3b_s5_audit_record")
st = load("p3b_s5_state")
G = load("p3b_s5a_generators")
SAMPLE = "a" * 64


def findings(disps):
    return {"auditor_model_id": "claude-opus-5-5", "audit_group": "G01", "sample_sha256": SAMPLE,
            "records_rederived": 5, "register_reviewed": 6, "dispositions": disps}


class AuditRecord(unittest.TestCase):
    def run_case(self, f, run="OB0004-R2"):
        d = tempfile.mkdtemp()
        fp, out = os.path.join(d, "f.json"), os.path.join(d, "S5-AUDIT.json")
        json.dump(f, open(fp, "w"))
        return ar.main(["--batch", "OB0004", "--run", run, "--findings", fp, "--out", out]), out

    def test_pass_and_state_accepts_it_as_audited_evidence(self):
        rc, out = self.run_case(findings([{"record": "x", "field": "births", "disposition": "JUDGMENT-CALL", "note": "n"}]))
        self.assertEqual(rc, 0)
        doc = json.load(open(out))
        self.assertEqual(doc["body"]["result"], "PASS")
        self.assertEqual(doc["header"]["script_name"], "scripts/p3b_s5_audit_record.py")
        self.assertIsNotNone(st.check_evidence("AUDITED", out, "OB0004", "OB0004-R2"))
        with self.assertRaises(st.c.S5Error):                    # an audit report is not VERIFIED evidence
            st.check_evidence("VERIFIED", out, "OB0004", "OB0004-R2")

    def test_protocol_violation_fails(self):
        rc, out = self.run_case(findings([{"record": "x", "field": "f", "disposition": "PROTOCOL-VIOLATION", "note": "n"}]))
        self.assertEqual(rc, 1)
        self.assertEqual(json.load(open(out))["body"]["result"], "FAIL")

    def test_vocabulary_and_keys_enforced(self):
        with self.assertRaises(ar.c.S5Error):
            self.run_case(findings([{"record": "x", "field": "f", "disposition": "OK", "note": "n"}]))
        bad = findings([])
        bad["extra"] = 1
        with self.assertRaises(ar.c.S5Error):
            self.run_case(bad)

    def test_bad_ids_refused(self):
        self.assertEqual(ar.main(["--batch", "OB0004", "--run", "OB0005-R2", "--findings", "/dev/null"]), 2)


class Link1Binding(unittest.TestCase):
    def test_binding(self):
        d = tempfile.mkdtemp()
        run = os.path.join(d, "ledger-p3b-r2", "OB0004-R2")
        os.makedirs(run)
        obj = os.path.join(run, "objects.jsonl")
        open(obj, "w").write('{"working_label":"a"}\n')
        body = {"accepted_batches": [{"batch_id": "OB0004", "run_id": "OB0004-R2",
                                      "files": {"ledger-p3b-r2/OB0004-R2/objects.jsonl": G.C.sha256_file(obj)}}]}
        snap = os.path.join(d, "snap.json")
        json.dump({"header": {"output_sha256": G.C.sha256_bytes(G.C.canon(body).encode())}, "body": body}, open(snap, "w"))
        self.assertTrue(G.bind_to_snapshot(snap, [obj], False))
        with self.assertRaises(G.C.S5Error):
            G.bind_to_snapshot(None, [obj], False)
        open(obj, "a").write('{"working_label":"b"}\n')           # changed after the snapshot
        with self.assertRaises(G.C.S5Error):
            G.bind_to_snapshot(snap, [obj], False)


if __name__ == "__main__":
    unittest.main()
