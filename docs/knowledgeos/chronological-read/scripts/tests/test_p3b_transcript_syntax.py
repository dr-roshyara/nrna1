#!/usr/bin/env python3
"""B0 neutral transcript syntax decoder (R7 addendum §5.6, DR-09): tests on SYNTHETIC harness-format transcripts only.
The record shapes mirror those observed in the Model-D readiness experiment (audit-p3b/20260926_MODEL-D-READINESS-RESULT.md).
  cd scripts/tests && PYTHONPATH=.:.. python3 -m unittest test_p3b_transcript_syntax
"""
import json
import os
import shutil
import sys
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
sys.path.insert(0, os.path.dirname(HERE))
import p3b_transcript_syntax as ts                  # noqa: E402
import r7_harness_fixture as hf                     # noqa: E402


class Decode(unittest.TestCase):
    def setUp(self):
        self.d = tempfile.mkdtemp(prefix="ts-")

    def tearDown(self):
        shutil.rmtree(self.d, ignore_errors=True)

    def agent(self, calls):
        return hf.write_agent(self.d, "a0000000000000001", "toolu_D1", calls)

    def test_calls_paired_in_order_with_times_and_results(self):
        p = self.agent([hf.bash("echo hi", "hi\n"), hf.write("x/y.json", "{}"), hf.handback("done")])
        tr = ts.decode(p)
        cs = ts.calls(tr)
        self.assertEqual([c.name for c in cs], ["Bash", "Write", "SubagentHandback"])
        self.assertEqual(cs[0].input["command"], "echo hi")
        self.assertEqual(cs[0].text, "hi\n")
        self.assertFalse(cs[0].is_error)
        self.assertEqual(cs[0].exit_code, 0)
        self.assertLess(cs[0].t_call, cs[0].t_result)
        self.assertEqual(ts.tool_use_count(tr), 3)
        self.assertEqual(ts.chain(tr), {"parent_missing": [], "dup_uuid": []})
        self.assertEqual(tr.parse_errors, [])
        self.assertEqual(ts.ids(tr), (["a0000000000000001"], [hf.SESSION]))

    def test_error_result_exit_code_and_string_tool_use_result(self):
        cs = ts.calls(ts.decode(self.agent([hf.bash("x", "REFUSED: no", exit_code=1)])))
        self.assertTrue(cs[0].is_error)
        self.assertEqual(cs[0].exit_code, 1)
        self.assertIn("REFUSED: no", cs[0].text)

    def test_denied_call_has_no_exit_code(self):
        cs = ts.calls(ts.decode(self.agent([hf.denied_bash("cat S0001")])))
        self.assertTrue(cs[0].is_error)
        self.assertIsNone(cs[0].exit_code)

    def test_missing_result_is_none(self):
        p = self.agent([hf.bash("echo hi", "hi")])
        lines = open(p).read().splitlines()
        open(p, "w").write("\n".join(l for l in lines if '"tool_result"' not in l) + "\n")
        c = ts.calls(ts.decode(p))[0]
        self.assertIsNone(c.t_result)
        self.assertIsNone(c.text)

    def test_parse_error_and_chain_break_and_duplicate_uuid(self):
        p = self.agent([hf.bash("a", "1"), hf.bash("b", "2")])
        lines = open(p).read().splitlines()
        open(p, "w").write("\n".join(lines[:2] + [lines[2][:-4]] + lines[3:]) + "\n")
        tr = ts.decode(p)
        self.assertEqual(tr.parse_errors, [3])
        self.assertTrue(ts.chain(tr)["parent_missing"])
        open(p, "w").write("\n".join(lines + [lines[-1]]) + "\n")
        self.assertTrue(ts.chain(ts.decode(p))["dup_uuid"])

    def test_reorder_breaks_chain(self):
        p = self.agent([hf.bash("a", "1"), hf.bash("b", "2")])
        lines = open(p).read().splitlines()
        lines[1], lines[2] = lines[2], lines[1]
        open(p, "w").write("\n".join(lines) + "\n")
        self.assertTrue(ts.chain(ts.decode(p))["parent_missing"])

    def test_persisted_output_resolved_from_root(self):
        big = "Z" * 50
        p = self.agent([hf.bash("gen", big, persist_as="pers1.txt")])
        root = os.path.join(self.d, "tool-results")
        os.makedirs(root, exist_ok=True)
        open(os.path.join(root, "pers1.txt"), "w").write(big)
        c = ts.calls(ts.decode(p), persisted_root=root)[0]
        self.assertEqual(c.text, big)
        self.assertTrue(c.persisted_path.endswith("pers1.txt"))
        self.assertEqual(ts.persisted_announced(ts.calls(ts.decode(p)))[0][1], "pers1.txt")

    def test_notifications_only_enqueue_task_notifications(self):
        m = hf.write_main(self.d, [hf.main_notification("a1", "toolu_1", 3), hf.main_notification("a1", "toolu_1", 3, op="remove"),
                                   hf.main_agent_message("a1")])
        ns = ts.notifications(ts.decode(m))
        self.assertEqual([(n["task_id"], n["tool_use_id"], n["status"], n["tool_uses"]) for n in ns], [("a1", "toolu_1", "completed", 3)])

    def test_meta(self):
        self.agent([hf.handback("x")])
        self.assertEqual(ts.read_meta(os.path.join(self.d, "subagents", "agent-a0000000000000001.meta.json"))["toolUseId"], "toolu_D1")

    def test_decoder_is_s5_agnostic(self):
        src = open(os.path.join(os.path.dirname(HERE), "p3b_transcript_syntax.py"), encoding="utf-8").read()
        for word in ("p3b_v1_2_4_instrument", "S5-RUN-BINDING", "R7", "OB####", "reader"):
            self.assertNotIn(word, src.split('"""', 2)[2])


if __name__ == "__main__":
    unittest.main()
