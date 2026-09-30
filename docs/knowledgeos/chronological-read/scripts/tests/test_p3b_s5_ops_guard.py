"""Tests: H-19 guard (scripts/p3b_s5_h19_guard.py). Violation fixtures are assembled at run time in /tmp so this test
file itself stays clean under the guard's own grep."""
import io
import json
import os
import unittest
from contextlib import redirect_stdout, redirect_stderr

import p3b_s5_ops_testlib as T

g = T.ops.load_script("p3b_s5_h19_guard")
c = T.c
RES = "Content" + "Resolver"
QFILE = "P3B-HOLDOUT-" + "QUARANTINE.jsonl"
HLIST = "holdout" + "_sets"
OWN = ["scripts/p3b_s5_common.py", "scripts/p3b_discovery_io.py", "scripts/p3b_read_source.py"] + \
      [f"scripts/p3b_s5_{m}.py" for m in ("ops_lib", "quarantine_scan", "h19_guard", "freeze", "state",
                                          "audit_sample", "accept", "testpass", "stability")] + \
      [f"scripts/tests/{t}.py" for t in ("p3b_s5_ops_testlib", "test_p3b_s5_ops_quarantine", "test_p3b_s5_ops_guard",
                                         "test_p3b_s5_ops_state", "test_p3b_s5_ops_freeze", "test_p3b_s5_ops_audit_sample",
                                         "test_p3b_s5_ops_testpass", "test_p3b_s5_ops_stability",
                                         "test_p3b_s5_ops_read_source")]


def run(argv):
    out, err = io.StringIO(), io.StringIO()
    with redirect_stdout(out), redirect_stderr(err):
        rc = g.main(argv)
    return rc, out.getvalue(), err.getvalue()


class Seal(unittest.TestCase):
    def test_sealed_passes(self):
        self.assertEqual(run(["seal"])[0], 0)

    def test_not_sealed_aborts_in_memory_patch(self):
        with T.seal_state("UNSEALED"):
            self.assertEqual(run(["seal"])[0], 1)

    def test_not_sealed_aborts_temp_copy(self):
        with T.temp_seal_copy("OPEN"):
            rc, _, err = run(["seal"])
        self.assertEqual(rc, 1)
        self.assertIn("not SEALED", err)

    def test_every_mode_aborts_when_not_sealed(self):
        with T.seal_state("UNSEALED"):
            self.assertEqual(run(["grep"])[0], 1)


class Grep(unittest.TestCase):
    def test_default_scope_covers_tooling_and_tests(self):
        files = g.default_scope()
        for f in OWN:
            self.assertIn(f, files)

    def test_this_build_and_committed_modules_are_clean(self):
        # The full default scope also holds S5 tools built in parallel by other sessions; their findings are reported by
        # the CLI run, not asserted here.
        files, viol, allowed = g.grep(OWN)
        self.assertEqual(viol, [])
        committed = [a for a in allowed if a[0] in ("scripts/p3b_s5_common.py", "scripts/p3b_discovery_io.py")]
        pinned = sum(len(v) for (f, _), v in g.LINE_ALLOW.items() if f in ("scripts/p3b_s5_common.py",
                                                                          "scripts/p3b_discovery_io.py"))
        self.assertEqual(len(committed), pinned)                  # every pinned committed line still matches

    def test_scope_additions_and_pre_seal_section(self):
        files = g.default_scope()
        for f in ("scripts/p3b_stage2a_scan.py", "scripts/p3b_s5a_pass_plan.py", "scripts/p3b_s5a_pass_contract.py",
                  "scripts/tests/s5a_test_fixtures.py"):
            if os.path.isfile(os.path.join(os.path.dirname(T.SCRIPTS), f)):
                self.assertIn(f, files)
        self.assertEqual(g.pre_seal_scope(), list(g.PRE_SEAL_MODULES))
        self.assertTrue(set(g.PRE_SEAL_MODULES).isdisjoint(files))
        rc, out, _ = run(["grep"])
        summary = json.loads(out.strip().split("\n")[-1])
        self.assertIn("pre_seal_modules", summary)
        self.assertGreater(summary["pre_seal_modules"]["violations"], 0)        # reported, never whitelisted

    def test_mentions_key_is_a_holdout_list_reference(self):
        root, rel = self._fixture("scripts/p3b_s5_x.py", "m = seal['hf_sid_mentions_in_discovery_" + "bundles']\n")
        self.assertEqual([v[2] for v in g.grep([rel], root)[1]], ["HOLDOUT-LISTS"])

    def _fixture(self, rel, text):
        root = T.tmpdir("grep")
        p = os.path.join(root, rel)
        os.makedirs(os.path.dirname(p), exist_ok=True)
        with open(p, "w") as f:
            f.write(text)
        return root, rel

    def test_violations_reported_with_file_line(self):
        text = "\n".join([
            "x = 1",
            f"open('{c.FORBIDDEN[0]}')",
            f"r = s3.{RES}(rows)",
            f"r = s3.{RES}.from_manifest(None)",
            f"q = '{QFILE}'",
            f"H = c.{HLIST}()",
            "y = 'ledger-p3b-r2/OB0004-R2/objects.jsonl'",
        ])
        root, rel = self._fixture("scripts/p3b_s5_evil.py", text)
        _, viol, _ = g.grep([rel], root)
        self.assertEqual([(v[1], v[2]) for v in viol], [(2, "FORBIDDEN-PATH"), (3, "BASE-RESOLVER"),
                                                        (4, "BASE-RESOLVER"), (5, "QUARANTINE-FILE"),
                                                        (6, "HOLDOUT-LISTS")])
        rc, out, _ = run(["grep", "--root", root, "--paths", rel])
        self.assertEqual(rc, 1)
        self.assertIn("scripts/p3b_s5_evil.py:2: FORBIDDEN-PATH", out)

    def test_each_forbidden_name_detected(self):
        text = "\n".join(f"p = '{f}'" for f in c.FORBIDDEN)
        root, rel = self._fixture("scripts/p3b_s5a_evil.py", text)
        _, viol, _ = g.grep([rel], root)
        self.assertEqual(len(viol), len(c.FORBIDDEN))

    def test_whitelist_is_exact_line_and_fails_closed(self):
        root, rel = self._fixture("scripts/p3b_s5_common.py", f"def {HLIST}():\ndef {HLIST}():  # edited\n")
        _, viol, allowed = g.grep([rel], root)
        self.assertEqual([v[1] for v in viol], [2])
        self.assertEqual([a[1] for a in allowed], [1])

    def test_scanner_may_name_quarantine_file_others_may_not(self):
        root, rel = self._fixture("scripts/p3b_s5_quarantine_scan.py", f"Q = '{QFILE}'\nH = c.{HLIST}()\n")
        self.assertEqual(g.grep([rel], root)[1], [])
        root2, rel2 = self._fixture("scripts/p3b_s5_freeze.py", f"Q = '{QFILE}'\n")
        self.assertEqual(len(g.grep([rel2], root2)[1]), 1)

    def test_resolver_construction_outside_dio_flagged(self):
        root, rel = self._fixture("scripts/p3b_discovery_io.py", f"x = s3.{RES}(rows)\n")
        self.assertEqual(len(g.grep([rel], root)[1]), 1)          # only the committed exact lines are whitelisted


class Refusals(unittest.TestCase):
    def setUp(self):
        self.d = T.tmpdir("refusals")
        self.run_dir = os.path.join(self.d, "OB0004-R2")
        base = {"run_id": "OB0004-R2", "batch_id": "OB0004", "working_label": "disc-a", "step": 1, "bytes": {}, "sha256": {}}
        T.write_jsonl(os.path.join(self.run_dir, "READ-LOG.jsonl"), [
            {**base, "source_ids": ["S1234"], "refused": False},
            {**base, "source_ids": ["S9990"], "refused": True},
            {**base, "source_ids": ["S5555"], "refused": True}])
        self.clean = os.path.join(self.d, "OB0005-R2")
        T.write_jsonl(os.path.join(self.clean, "READ-LOG.jsonl"), [{**base, "source_ids": ["S1234"], "refused": False}])

    def test_refusals_counted_and_escalated_without_names(self):
        with T.fake_holdout():
            rc, out, _ = run(["refusals", self.run_dir])
        self.assertEqual(rc, 1)
        rep = json.loads(out)[0]
        self.assertEqual((rep["calls"], rep["refused_calls"], rep["refused_calls_naming_holdout_files"]), (3, 2, 1))
        self.assertEqual(rep["refused_line_numbers"], [2, 3])
        self.assertIn("P3B-ESC", rep["action"])
        self.assertNotIn("S9990", out)

    def test_clean_log(self):
        with T.fake_holdout():
            rc, out, _ = run(["refusals", self.clean])
        self.assertEqual(rc, 0)
        self.assertEqual(json.loads(out)[0]["action"], "none")


class Inputs(unittest.TestCase):
    def setUp(self):
        self.d = T.tmpdir("inputs")

    def _art(self, name, keys, jsonl=False):
        p = os.path.join(self.d, name)
        hdr = {"input_hashes": {k: "0" * 64 for k in keys}}
        with open(p, "w") as f:
            f.write(json.dumps({"header": hdr}) + ("\n{\"x\":1}\n" if jsonl else "\n"))
        return p

    def test_allowlisted_inputs_pass(self):
        p = self._art("ok.json", ["P3B-SAMPLE-PLAN.jsonl", "ledger-p3b-r2/OB0004-R2/objects.jsonl"])
        q = self._art("ok.jsonl", ["P3B-S5-HUBS.jsonl"], jsonl=True)
        self.assertEqual(run(["inputs", p, q])[0], 0)

    def test_forbidden_and_unlisted_inputs_fail(self):
        p = self._art("bad.json", ["P3B-SAMPLE-PLAN.jsonl", c.FORBIDDEN[3], "30-RECONCILIATION.md"])
        rc, out, _ = run(["inputs", p])
        self.assertEqual(rc, 1)
        self.assertEqual(len(json.loads(out)[0]["not_allowlisted"]), 2)

    def test_missing_header_fails(self):
        p = os.path.join(self.d, "nohdr.json")
        with open(p, "w") as f:
            f.write("{\"body\": 1}\n")
        self.assertEqual(run(["inputs", p])[0], 1)


if __name__ == "__main__":
    unittest.main()
