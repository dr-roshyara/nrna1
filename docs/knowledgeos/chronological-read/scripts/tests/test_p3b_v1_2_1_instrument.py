"""Tests for p3b_v1_2_1_instrument.py (V1.2.1 Phase-1 extraction-instrumentation validation tooling). Synthetic data and temp files only; reads
no corpus and no harness transcript."""
import importlib.util
import json
import os
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
spec = importlib.util.spec_from_file_location("v121", os.path.join(HERE, "..", "p3b_v1_2_1_instrument.py"))
v = importlib.util.module_from_spec(spec)
spec.loader.exec_module(v)
COMMIT = "0123abcd" + "0" * 32          # 40 hex
NONCE_A, NONCE_B = "a" * 32, "b" * 32
V1ABS = "/repo/docs/knowledgeos/chronological-read/pilot-s5-decomp/v1"
CWD = "/repo"


def text():
    return ("preamble\n\n# One\nalpha beta\n\n```\n# fenced, not a heading\n```\n\n## Two\n"
            + "".join(f"paragraph {i} " * 40 + "\n\n" for i in range(40)) + "### Three\nlast\n\n  \n")


class Segmentation(unittest.TestCase):
    def test_tiles_deterministic_bounded(self):
        t = text()
        s = v.segment(t)
        self.assertTrue(v.tiles(s, len(t)))
        self.assertEqual(s, v.segment(t))
        self.assertEqual([x["kind"] for x in s][:3], ["PREAMBLE", "HEADING", "HEADING"])

    def test_edge_branches(self):                               # m6: branches not covered in v1
        cases = {"leading-ws": "\n\n\n# A\ntext\n", "heading-first": "# A\ntext\n",
                 "unclosed-fence": "# A\n```\n# B\n" + "z\n\n" * 4000, "crlf": "# A\r\ntext\r\n\r\n# B\r\nmore\r\n",
                 "seven-hash": "####### not\n# real\nx\n", "no-newline": "plain", "empty": ""}
        for name, t in cases.items():
            s = v.segment(t)
            self.assertEqual(s, v.segment(t), name)
            if t:
                self.assertTrue(v.tiles(s, len(t)), name)
        self.assertEqual([x["kind"] for x in v.segment(cases["leading-ws"])], ["HEADING"])
        self.assertEqual([x["kind"] for x in v.segment(cases["seven-hash"])], ["PREAMBLE", "HEADING"])
        self.assertEqual(len(v.segment(cases["crlf"])), 2)
        self.assertTrue(all(x["kind"] in ("HEADING", "SPLIT") for x in v.segment(cases["unclosed-fence"])))

    def test_map_ids_stable(self):
        m = v.segment_map("S0001", text())
        self.assertEqual(m[0]["segment_id"], "S0001-g001")
        self.assertEqual(m, v.segment_map("S0001", text()))


def prop(pid, quote="alpha beta", **kw):
    p = {"proposition_id": pid, "proposition_type": "CLAIM", "status": "ASSERTED", "statement": "s", "quote": quote,
         "register_decision": "PROMOTE"}
    p.update(kw)
    return p


class Checks(unittest.TestCase):
    def setUp(self):
        self.t = text()
        self.map = v.segment_map("S0001", self.t)
        self.texts = {"S0001": self.t}

    def full(self):
        return [{"segment_id": s["segment_id"], "source_id": "S0001", "result": "PROPOSITIONS",
                 "propositions": [prop(f"P{i}", self.t[s["char_start"]:s["char_end"]].strip()[:12])]}
                for i, s in enumerate(self.map)]

    def test_full_inventory_passes(self):
        r, f = v.check_inventory(self.full(), self.map, self.texts)
        self.assertEqual(r["coverage"], {"S0001": 1.0})
        self.assertEqual(f, [])

    def test_failures_are_keyed(self):
        recs = self.full()[1:]                                   # first segment missing
        recs[0]["propositions"][0]["quote"] = "preamble"          # quote from another segment
        recs[1]["propositions"].append(prop(recs[0]["propositions"][0]["proposition_id"]))   # duplicate id
        _, f = v.check_inventory(recs, self.map, self.texts)
        classes = {x["class"] for x in f}
        self.assertIn("MISSING-SEGMENT", classes)
        self.assertIn("QUOTE-OUT-OF-SEGMENT", classes)
        self.assertIn("SCHEMA:duplicate-proposition_id", classes)

    def test_vacuous_inventory_has_full_coverage(self):          # the reason capture must stay a VALID gate (R1)
        vac = [{"segment_id": s["segment_id"], "source_id": "S0001", "result": "NO-SUBSTANTIVE-PROPOSITION",
                "reason": "x"} for s in self.map]
        r, f = v.check_inventory(vac, self.map, self.texts)
        self.assertEqual((r["coverage"]["S0001"], f), (1.0, []))

    def test_validate_mode_skips_quotes(self):
        recs = self.full()
        recs[0]["propositions"][0]["quote"] = "not in the file at all"
        _, f = v.check_inventory(recs, self.map, None)
        self.assertEqual(f, [])

    def test_open_checks(self):
        recs = [{"item_id": "i1", "source_id": "S0001", "statement": "s", "quote": "alpha beta"},
                {"item_id": "i1", "source_id": "S0001", "statement": "s", "quote": "zzz"},
                {"item_id": "i3", "source_id": "S9999", "statement": "s", "quote": "q"}]
        _, f = v.check_open(recs, {"S0001"}, self.texts)
        self.assertEqual(sorted(x["class"] for x in f), ["QUOTE-MISS", "SCHEMA:item_id", "SCHEMA:source_id"])

    def test_length_summary(self):
        self.assertEqual(v.length_summary([3, 1, 2])["median"], 2)
        self.assertIsNone(v.length_summary([]))


class Repair(unittest.TestCase):                                   # R4
    def seg_rec(self, props):
        return {"segment_id": "g1", "source_id": "S0001", "result": "PROPOSITIONS", "propositions": props}

    def test_unflagged_key_rejected(self):
        out, d = v.apply_repair([{"item_id": "a"}, {"item_id": "b", "q": 1}], [{"item_id": "b", "q": 2}],
                                [{"key": "a", "proposition_id": None, "class": "QUOTE-MISS"}], "item_id")
        self.assertEqual(d, [{"key": "b", "decision": "REJECTED", "reason": "UNFLAGGED-KEY"}])
        self.assertIn({"item_id": "b", "q": 1}, out)

    def test_flagged_item_replace_and_withdraw(self):
        fl = [{"key": "a", "proposition_id": None, "class": "QUOTE-MISS"},
              {"key": "b", "proposition_id": None, "class": "QUOTE-MISS"}]
        out, d = v.apply_repair([{"item_id": "a", "q": 0}, {"item_id": "b"}],
                                [{"item_id": "a", "q": 9}, {"item_id": "b", "withdrawn": True}], fl, "item_id")
        self.assertEqual(out, [{"item_id": "a", "q": 9}])
        self.assertEqual([x["decision"] for x in d], ["ACCEPTED", "ACCEPTED"])

    def test_seg_unflagged_proposition_must_not_change(self):
        orig = [self.seg_rec([prop("p1"), prop("p2")])]
        fl = [{"key": "g1", "proposition_id": "p1", "class": "QUOTE-MISS"}]
        changed = self.seg_rec([prop("p1", "new"), prop("p2", "EDITED")])
        _, d = v.apply_repair(orig, [changed], fl, "segment_id")
        self.assertEqual(d[0]["reason"], "CHANGED-UNFLAGGED")
        ok = self.seg_rec([prop("p1", "new"), prop("p2")])
        out, d = v.apply_repair(orig, [ok], fl, "segment_id")
        self.assertEqual(d[0]["decision"], "ACCEPTED")
        self.assertEqual(out[0]["propositions"][0]["quote"], "new")

    def test_seg_no_added_propositions(self):
        orig = [self.seg_rec([prop("p1")])]
        fl = [{"key": "g1", "proposition_id": "p1", "class": "QUOTE-MISS"}]
        _, d = v.apply_repair(orig, [self.seg_rec([prop("p1"), prop("p9")])], fl, "segment_id")
        self.assertEqual(d[0]["reason"], "ADDED-PROPOSITION")

    def test_seg_drop_flagged_only(self):
        orig = [self.seg_rec([prop("p1"), prop("p2")])]
        fl = [{"key": "g1", "proposition_id": "p1", "class": "QUOTE-MISS"}]
        out, d = v.apply_repair(orig, [self.seg_rec([prop("p2")])], fl, "segment_id")
        self.assertEqual((d[0]["decision"], [p["proposition_id"] for p in out[0]["propositions"]]),
                         ("ACCEPTED", ["p2"]))

    def test_missing_segment_added_and_duplicates_rejected(self):
        fl = [{"key": "g2", "proposition_id": None, "class": "MISSING-SEGMENT"}]
        new = {"segment_id": "g2", "source_id": "S0001", "result": "NO-SUBSTANTIVE-PROPOSITION", "reason": "r"}
        out, d = v.apply_repair([], [new], fl, "segment_id")
        self.assertEqual(out, [new])
        _, d = v.apply_repair([], [new, dict(new)], fl, "segment_id")
        self.assertTrue(all(x["reason"] == "DUPLICATE-REPAIR" for x in d))

    def test_original_not_mutated(self):
        orig = [{"item_id": "a", "q": 0}]
        v.apply_repair(orig, [{"item_id": "a", "q": 1}], [{"key": "a", "proposition_id": None, "class": "X"}],
                       "item_id")
        self.assertEqual(orig, [{"item_id": "a", "q": 0}])


class Blinding(unittest.TestCase):                                 # R5
    outs = {s: [{"orig_id": f"{s}-1", "source_id": "S0001", "statement": "x", "quote": "q"}] for s in v.SOURCES}

    def test_nonce_required_and_deterministic(self):
        with self.assertRaises(v.V1Error):
            v.equalize(self.outs, "")
        self.assertEqual(v.equalize(self.outs, NONCE_A), v.equalize(self.outs, NONCE_A))

    def test_mapping_depends_on_nonce_not_commit(self):
        maps = {tuple(v.equalize(self.outs, n * 32)[1]["letter_of_source"].values()) for n in "abcdef0123456789"}
        self.assertGreater(len(maps), 1)
        ids_a = {i["item_id"] for L in v.equalize(self.outs, NONCE_A)[0].values() for i in L}
        ids_b = {i["item_id"] for L in v.equalize(self.outs, NONCE_B)[0].values() for i in L}
        self.assertFalse(ids_a & ids_b)

    def test_lists_carry_no_source_fields(self):
        for items in v.equalize(self.outs, NONCE_A)[0].values():
            self.assertEqual(set(items[0]), {"item_id", "list", "source_id", "statement", "quote"})


def rec(run, calls, models=None, persisted=()):
    role, alias, _ = v.RUNS[run]
    return {"cwd": CWD, "api_models": models or [v.MODEL_IDS[alias]], "persisted_outputs": list(persisted),
            "tool_calls": [{"ts": "2026-09-25T12:00:00.000Z", "id": f"t{i}", "name": n, "input": inp}
                           for i, (n, inp) in enumerate(calls)]}


class ToolCallAudit(unittest.TestCase):                            # R2
    def reader(self, run, sid, label, page=1):
        return (f"cd /repo/docs/knowledgeos/chronological-read && python3 scripts/p3b_read_source.py --run {run} "
                f"--batch PX0106 --label {label} --step 10 --page {page} {sid}")

    def test_allowed_extractor_run(self):
        r = rec("PX0106-U01", [("Bash", {"command": self.reader("PX0106-U01", "S1022", v.LABEL_C)}),
                               ("Read", {"file_path": V1ABS + "/SEGMENT-MAP.json"}),
                               ("Write", {"file_path": V1ABS + "/OUT-PX0106-U01.jsonl"}),
                               ("Bash", {"command": "python3 scripts/p3b_v1_2_1_instrument.py validate --run PX0106-U01"}),
                               ("SubagentHandback", {})])
        self.assertEqual(v.audit_calls("PX0106-U01", r, V1ABS), [])

    def test_m2_reading_m1_decisions_is_detected(self):
        for call in (("Read", {"file_path": V1ABS + "/DECISIONS-A01.jsonl"}),
                     ("Bash", {"command": "cat pilot-s5-decomp/v1/DECISIONS-A01.jsonl"}),
                     ("Bash", {"command": "git show HEAD:x/DECISIONS-A01.jsonl"}),
                     ("Bash", {"command": "python3 -c \"open('DEC'+'ISIONS-A01.jsonl')\""}),
                     ("Grep", {"pattern": "MATCH", "path": V1ABS}),
                     ("Read", {"file_path": V1ABS + "/SOURCE-KEY.json"})):
            self.assertTrue(v.audit_calls("PX0106-A02", rec("PX0106-A02", [call]), V1ABS), call)

    def test_m1_cannot_read_m2_material(self):
        r = rec("PX0106-A01", [("Read", {"file_path": V1ABS + "/DECISIONS-A02.jsonl"})])
        self.assertTrue(v.audit_calls("PX0106-A01", r, V1ABS))

    def test_extractor_cannot_read_other_outputs_or_wrong_files(self):
        self.assertTrue(v.audit_calls("PX0106-U03", rec("PX0106-U03", [
            ("Read", {"file_path": V1ABS + "/OUT-PX0106-U05.jsonl"})]), V1ABS))
        self.assertTrue(v.audit_calls("PX0106-U03", rec("PX0106-U03", [
            ("Bash", {"command": self.reader("PX0106-U03", "S1413", v.LABEL_S)})]), V1ABS))      # other label
        self.assertTrue(v.audit_calls("PX0106-U03", rec("PX0106-U03", [
            ("Bash", {"command": self.reader("PX0106-U05", "S1022", v.LABEL_C)})]), V1ABS))      # other run id
        self.assertTrue(v.audit_calls("PX0106-U03", rec("PX0106-U03", [
            ("Bash", {"command": self.reader("PX0106-U03", "S1022", v.LABEL_C) + " > /tmp/x"})]), V1ABS))

    def test_persisted_output_of_own_call_readable(self):
        p = "/home/u/.claude/projects/x/tool-results/abc.txt"
        r = rec("PX0106-U03", [("Read", {"file_path": p})], persisted=[p])
        self.assertEqual(v.audit_calls("PX0106-U03", r, V1ABS), [])
        r = rec("PX0106-U03", [("Read", {"file_path": p})])
        self.assertTrue(v.audit_calls("PX0106-U03", r, V1ABS))

    def test_writes_limited_to_own_outputs(self):
        self.assertTrue(v.audit_calls("PX0106-A02", rec("PX0106-A02", [
            ("Write", {"file_path": V1ABS + "/DECISIONS-A01.jsonl"})]), V1ABS))

    def test_parse_transcript(self):
        with tempfile.TemporaryDirectory() as d:
            sub = os.path.join(d, "sess", "subagents")
            os.makedirs(sub)
            tr = os.path.join(d, "sess", "tool-results")
            lines = [{"cwd": CWD, "agentId": "a1", "sessionId": "s1", "timestamp": "2026-09-25T12:00:00.100Z",
                      "message": {"role": "user", "content": "run PX0106-U03 canary " + v.canary(COMMIT, "PX0106-U03")}},
                     {"agentId": "a1", "sessionId": "s1", "timestamp": "2026-09-25T12:00:00.500Z",
                      "message": {"model": "claude-fable-5-1", "content": [
                          {"type": "tool_use", "id": "t1", "name": "Write",
                           "input": {"file_path": "/x/OUT.jsonl", "content": "big"}},
                          {"type": "tool_use", "id": "t2", "name": "Bash", "input": {"command": "x"}},
                          {"type": "tool_use", "id": "t3", "name": "Read", "input": {"file_path": "/x/M.json"}}]}},
                     {"agentId": "a1", "sessionId": "s1", "timestamp": "2026-09-25T12:00:01.000Z", "message": {"content": [
                         {"type": "tool_result", "tool_use_id": "t2",
                          "content": f"<persisted-output>\nOutput too large. Full output saved to: {tr}/q.txt\n"},
                         {"type": "tool_result", "tool_use_id": "t3",          # agent-authored text via Read: ignored
                          "content": f"<persisted-output> Full output saved to: {tr}/evil.txt"},
                         {"type": "tool_result", "tool_use_id": "t2",          # outside tool-results: ignored
                          "content": "<persisted-output> Full output saved to: /repo/DECISIONS-A01.jsonl"}]}},
                     {"agentId": "a1", "sessionId": "s1", "timestamp": "2026-09-25T12:00:02.000Z",
                      "message": {"model": "<synthetic>", "content": "x"}}]
            path = os.path.join(sub, "agent-a1.jsonl")
            with open(path, "w") as f:
                f.write("\n".join(json.dumps(x) for x in lines) + "\n")
            r = v.parse_transcript(path)
            self.assertEqual(r["api_models"], ["claude-fable-5-1"])
            self.assertEqual(r["persisted_outputs"], [os.path.join(tr, "q.txt")])          # X4
            self.assertIn("content_sha256", r["tool_calls"][0]["input"])
            self.assertEqual(v.linkage_breaches("PX0106-U03", r, "a1", COMMIT), [])         # X5
            self.assertTrue(v.linkage_breaches("PX0106-U03", r, "a2", COMMIT))
            self.assertTrue(v.linkage_breaches("PX0106-U05", r, "a1", COMMIT))
            with open(path, "a") as f:
                f.write('{"agentId": "a1", "trunc')                                         # truncated tail
            r2 = v.parse_transcript(path)
            self.assertTrue(r2["parse_errors"])
            self.assertTrue(any("unparseable" in w for _, w in v.linkage_breaches("PX0106-U03", r2, "a1", COMMIT)))


class Kappa(unittest.TestCase):                                     # m1–m3
    cl = [{"cluster_id": "x1", "list": "X"}, {"cluster_id": "y1", "list": "Y"}, {"cluster_id": "z1", "list": "Z"}]

    def dec(self, cid, other, status, target=None):
        return {"cluster_id": cid, "other_list": other, "status": status, "target_cluster": target}

    def full(self):
        return [self.dec("x1", "Y", "MATCH", "y1"), self.dec("x1", "Z", "NONE"),
                self.dec("y1", "X", "MATCH", "x1"), self.dec("y1", "Z", "PARTIAL", "z1")]

    def test_identical_and_direction(self):
        k = v.kappas(self.full(), self.full(), ["x1", "y1"], self.cl)
        self.assertEqual((k["kappa_target"], k["units_expected"]), (1.0, 4))
        self.assertIn("X->Y", k["kappa_target_by_direction"])

    def test_missing_unit_not_computable(self):
        d2 = self.full()[:-1]
        k = v.kappas(self.full(), d2, ["x1", "y1"], self.cl)
        self.assertIsNone(k["kappa_target"])
        self.assertEqual(k["missing_in_m2"], 1)
        k = v.kappas(self.full()[1:], self.full(), ["x1", "y1"], self.cl)
        self.assertEqual(k["missing_in_m1"], 1)

    def test_degenerate_not_one(self):
        d = [self.dec("x1", "Y", "NONE"), self.dec("x1", "Z", "NONE")]
        k = v.kappas(d, d, ["x1"], self.cl)
        self.assertIsNone(k["kappa_target"])
        self.assertIn("p_e", k["not_computable_reason"])

    def test_status_disagreement_lowers_kappa(self):
        d2 = self.full()
        d2[0] = self.dec("x1", "Y", "PARTIAL", "y1")
        k = v.kappas(self.full(), d2, ["x1", "y1"], self.cl)
        self.assertLess(k["kappa_target"], 1.0)

    def test_invalid_target_or_duplicate_not_computable(self):        # minor m1 (V1.1 audit)
        d2 = self.full()
        d2[0] = self.dec("x1", "Y", "MATCH", "y9")                     # target does not exist in list Y
        self.assertIsNone(v.kappas(self.full(), d2, ["x1", "y1"], self.cl)["kappa_target"])
        d3 = self.full()
        d3[0] = self.dec("x1", "Y", "MATCH", "z1")                     # target in the wrong list
        self.assertIsNone(v.kappas(self.full(), d3, ["x1", "y1"], self.cl)["kappa_target"])
        d4 = self.full() + [self.dec("x1", "Y", "NONE")]                # duplicate unit
        self.assertIsNone(v.kappas(self.full(), d4, ["x1", "y1"], self.cl)["kappa_target"])


class Capture(unittest.TestCase):                                   # R1
    L = {"SEG": "X", "E1": "Y", "E2": "Z"}

    def data(self, n, seg_status):
        cl = [{"cluster_id": f"y{i}", "list": "Y"} for i in range(n)]
        d = [{"cluster_id": f"y{i}", "other_list": "Z", "status": "MATCH"} for i in range(n)]
        d += [{"cluster_id": f"y{i}", "other_list": "X", "status": seg_status(i)} for i in range(n)]
        return cl, d

    def test_small_agreed_set_not_computable(self):
        cl, d = self.data(19, lambda i: "MATCH")
        c = v.capture(cl, d, self.L)
        self.assertIsNone(c["lenient"])
        self.assertEqual(c["lenient_observed"], 1.0)

    def test_computable_at_minimum(self):
        cl, d = self.data(20, lambda i: "PARTIAL" if i % 2 else "NONE")
        c = v.capture(cl, d, self.L)
        self.assertEqual((c["strict"], c["lenient"]), (0.0, 0.5))


class Decision(unittest.TestCase):                                  # R1, R3
    base = {"run_invalid": [], "seg_exhausted": [], "other_failed": [], "coverage_completed": {"PX0106-U01": 1.0},
            "coverage_all_one": True, "seg_quote_miss": {"PX0106-U01": 0.0}, "ref_quote_miss": {"PX0106-U03": 0.0},
            "schema_ok": True, "kappa": 0.7, "kappa_reason": None, "capture": 0.9, "capture_reason": None,
            "capture_observed": 0.9, "agreed": 30}

    def d(self, **kw):
        return v.decide(dict(self.base, **kw))[:2]

    def test_valid(self):
        self.assertEqual(self.d(), ("INSTRUMENTS-VALID", None))

    def test_run_invalid_dominates(self):
        self.assertEqual(self.d(run_invalid=["x"], kappa=0.1), ("NEEDS-REVISION", "RUN-INVALID"))

    def test_capture_never_not_usable(self):
        self.assertEqual(self.d(capture=0.1), ("NEEDS-REVISION", "INSTRUMENT"))
        self.assertEqual(self.d(capture=None, agreed=3), ("NEEDS-REVISION", "INSTRUMENT-UNDETERMINED"))

    def test_kappa_states(self):
        self.assertEqual(self.d(kappa=0.3), ("NOT-USABLE", None))
        self.assertEqual(self.d(kappa=0.5), ("NEEDS-REVISION", "INSTRUMENT"))
        self.assertEqual(self.d(kappa=None, kappa_reason="r"), ("NEEDS-REVISION", "INSTRUMENT-UNDETERMINED"))

    def test_coverage_and_quotes(self):
        self.assertEqual(self.d(coverage_completed={"PX0106-U01": 0.9}, coverage_all_one=False), ("NOT-USABLE", None))
        self.assertEqual(self.d(coverage_completed={"PX0106-U01": 0.97}, coverage_all_one=False),
                         ("NEEDS-REVISION", "INSTRUMENT"))
        self.assertEqual(self.d(seg_quote_miss={"PX0106-U01": 0.2}), ("NOT-USABLE", None))
        self.assertEqual(self.d(seg_quote_miss={"PX0106-U01": 0.01}), ("NEEDS-REVISION", "INSTRUMENT"))
        self.assertEqual(self.d(schema_ok=False), ("NEEDS-REVISION", "INSTRUMENT"))

    def test_failed_runs(self):                                      # §17 vs §18 resolved
        self.assertEqual(self.d(seg_exhausted=["PX0106-U02"], coverage_completed={}),
                         ("NEEDS-REVISION", "INSTRUMENT"))
        self.assertEqual(self.d(other_failed=["PX0106-A02"], kappa=None, kappa_reason="r"),
                         ("NEEDS-REVISION", "INSTRUMENT-UNDETERMINED"))

    def test_low_capture_reports_causes(self):
        reasons = v.decide(dict(self.base, capture=0.2))[2]
        self.assertTrue(any("possible causes" in r for r in reasons))


class Order(unittest.TestCase):
    def prov(self, gap=None):
        out = []
        for i, (s, files) in enumerate(v.STAGES):
            if s == gap:
                continue
            out.append({"stage": s, "utc": f"2026-09-25T12:{i:02d}:00.000000Z",
                        "files": {f: "h" for f in files}})
        return out

    def test_clean_order(self):
        recs = {"PX0106-A01": {"first_ts": "2026-09-25T12:04:30.000Z", "last_ts": "2026-09-25T12:04:50.000Z"}}
        self.assertEqual(v.order_breaches(self.prov(), recs), [])

    def test_violations(self):
        self.assertTrue(v.order_breaches(self.prov(gap="lists"), {}))
        recs = {"PX0106-A02": {"first_ts": "2026-09-25T12:05:10.000Z", "last_ts": "2026-09-25T12:05:20.000Z"}}
        self.assertTrue(any("sample" in w for _, w in v.order_breaches(self.prov(), recs)))
        recs = {"PX0106-U03": {"first_ts": "2026-09-25T12:01:10.000Z", "last_ts": "2026-09-25T12:01:20.000Z",
                               "tool_calls": [{"name": "Write", "ts": "2026-09-25T12:01:30.000Z",
                                               "input": {"file_path": "/v1/OUT-PX0106-U03.repair.jsonl"}}]}}
        self.assertTrue(any("repair file" in w for _, w in v.order_breaches(self.prov(), recs)))

    def test_seal_hash_must_match(self):
        p = self.prov()
        p[-1]["files"]["SOURCE-KEY.json"] = "other"
        self.assertTrue(any("changed between" in w for _, w in v.order_breaches(p, {})))

    def test_ts_parsing(self):
        self.assertLess(v.ts("2026-09-25T12:00:00.123Z"), v.ts("2026-09-25T12:00:00.123456Z"))


class RepairsX(unittest.TestCase):
    base = Decision.base

    def test_x1_reference_quote_misses_never_condemn_instrument(self):
        for rate in (0.01, 0.2, 1.0):
            o, q, r = v.decide(dict(self.base, ref_quote_miss={"PX0106-U03": rate}))
            self.assertEqual((o, q), ("NEEDS-REVISION", "INSTRUMENT-UNDETERMINED"), rate)
            self.assertTrue(any("reference-extractor" in x for x in r))
        o, q, _ = v.decide(dict(self.base, ref_quote_miss={"PX0106-U03": 0.2}, kappa=0.5))
        self.assertEqual((o, q), ("NEEDS-REVISION", "INSTRUMENT"))                 # instrument evidence still counts

    def test_x2_shell_metacharacters_rejected(self):
        L = v.LABEL_C
        tail = f"/scripts/p3b_read_source.py --run PX0106-A02 --batch PX0106 --label {L} --step 10 --page 1 S1022"
        for pre in ("$(cat${IFS}f>&2)", "`cat${IFS}f`", "${HOME}", "a;b", "a|b", "a&b", "a>b", "a<b", "*", "a'b", 'a"b',
                    "~", "a\\b", "$HOME"):
            self.assertFalse(v.bash_allowed("python3 " + pre + tail, "PX0106-A02"), pre)
        self.assertTrue(v.bash_allowed("python3 /repo/docs/knowledgeos/chronological-read" + tail, "PX0106-A02"))
        self.assertTrue(v.bash_allowed("python3 scripts/p3b_read_source.py" + tail.split("p3b_read_source.py")[1],
                                       "PX0106-A02"))
        self.assertFalse(v.bash_allowed("python3 $(cat${IFS}f>&2)/scripts/p3b_v1_2_1_instrument.py validate --run PX0106-U03",
                                        "PX0106-U03"))

    def test_x6_capture_not_computable_when_extractor_incomplete(self):
        cap = {"agreed": 30, "strict": 0.3, "lenient": 0.3}
        ok = {r: "COMPLETED" for r in v.RUNS}
        self.assertEqual(v.capture_gate(cap, ok)[0]["lenient"], 0.3)
        bad = dict(ok, **{"PX0106-U02": "FAILED"})
        c2, reason = v.capture_gate(cap, bad)
        self.assertIsNone(c2["lenient"])
        self.assertIn("incomplete", reason)
        self.assertEqual(v.decide(dict(self.base, other_failed=["PX0106-U02"], capture=None,
                                       capture_reason=reason))[1], "INSTRUMENT-UNDETERMINED")

    def test_x7_provenance_rehash_and_dispatch_entries(self):
        with tempfile.TemporaryDirectory() as d:
            os.makedirs(os.path.join(d, v.V1_DIR))
            c = type("C", (), {"CR": d})

            def w(name, txt):
                with open(os.path.join(d, v.V1_DIR, name), "w") as f:
                    f.write(txt)
            w("FAILURES-PX0106-U01.json", "orig")
            w("V1-DISPATCH.json", json.dumps({"PX0106-U01": {"status": "COMPLETED"}}))
            import hashlib
            h = lambda t: hashlib.sha256(t.encode()).hexdigest()
            prov = [{"stage": "check-1", "files": {"FAILURES-PX0106-U01.json": h("orig"),
                                                   "V1-DISPATCH.json": h(json.dumps({"PX0106-U01": {"status": "COMPLETED"}}))},
                     "dispatch_entries": {"PX0106-U01": v.sha(json.dumps({"status": "COMPLETED"}, sort_keys=True))}}]
            self.assertEqual(v.provenance_breaches(c, prov), [])
            w("FAILURES-PX0106-U01.json", "widened")                                # altered failure list
            self.assertTrue(any("FAILURES" in x for _, x in v.provenance_breaches(c, prov)))
            w("FAILURES-PX0106-U01.json", "orig")
            w("V1-DISPATCH.json", json.dumps({"PX0106-U01": {"status": "FAILED"}}))     # re-labelled run status
            br = v.provenance_breaches(c, prov)
            self.assertTrue(any("dispatch entry changed" in x for _, x in br))


def m1_fixture():
    """Three lists over one file; E1 and E2 agree on 60 propositions; SEG captured half of them."""
    lists = {"X": [], "Y": [], "Z": []}
    clusters, dec = [], []
    for i in range(60):
        for L in "YZ":
            lists[L].append({"item_id": f"i{L}{i}", "source_id": "S1022"})
            clusters.append({"cluster_id": f"{L}{i}", "list": L, "source_id": "S1022", "members": [f"i{L}{i}"]})
        if i < 30:
            lists["X"].append({"item_id": f"iX{i}", "source_id": "S1022"})
            clusters.append({"cluster_id": f"X{i}", "list": "X", "source_id": "S1022", "members": [f"iX{i}"]})
    ids = {c["cluster_id"]: c for c in clusters}
    for c in clusters:
        L, i = c["cluster_id"][0], int(c["cluster_id"][1:])
        for o in "XYZ":
            if o == L:
                continue
            t = f"{o}{i}"
            dec.append({"cluster_id": c["cluster_id"], "other_list": o,
                        "status": "MATCH" if t in ids else "NONE", "target_cluster": t if t in ids else None})
    return lists, clusters, dec


class N1(unittest.TestCase):
    LK = {"SEG": "X", "E1": "Y", "E2": "Z"}
    OK = {r: "COMPLETED" for r in v.RUNS}

    def gate(self, lists, cl, dec):
        return v.gated_metrics(lists, cl, dec, dec, [], self.LK, self.OK)

    def test_complete_m1_computable(self):
        lists, cl, dec = m1_fixture()
        self.assertEqual(v.m1_completeness(lists, cl, dec), [])
        _, cap, _, _ = self.gate(lists, cl, dec)
        self.assertEqual((cap["agreed"], cap["lenient"]), (60, 0.5))

    def violates(self, mutate):
        lists, cl, dec = m1_fixture()
        mutate(lists, cl, dec)
        viol = v.m1_completeness(lists, cl, dec)
        k, cap, reason, _ = self.gate(lists, cl, dec)
        self.assertTrue(viol)
        self.assertIsNone(cap["lenient"])
        self.assertIsNone(k["kappa_target"])
        self.assertIn("M1 output incomplete", reason)
        return viol

    def test_missing_cluster(self):
        self.violates(lambda l, c, d: c.pop(0))

    def test_duplicate_member(self):
        self.violates(lambda l, c, d: c[1]["members"].append(c[0]["members"][0]))

    def test_unknown_member(self):
        self.violates(lambda l, c, d: c[0]["members"].append("ghost"))

    def test_inconsistent_source(self):
        self.violates(lambda l, c, d: c[0].update(source_id="S1021"))

    def test_inconsistent_list(self):
        self.violates(lambda l, c, d: c[0].update(list="X"))

    def test_missing_decision(self):
        self.violates(lambda l, c, d: d.pop(0))

    def test_duplicate_decision(self):
        self.violates(lambda l, c, d: d.append(dict(d[0])))

    def test_invalid_target(self):
        self.violates(lambda l, c, d: d[0].update(target_cluster="nonexistent"))

    def test_decision_for_unknown_cluster(self):
        self.violates(lambda l, c, d: d.append({"cluster_id": "zz", "other_list": "X", "status": "NONE",
                                                "target_cluster": None}))

    def test_no_seg_clusters(self):
        def drop_seg(l, c, d):
            c[:] = [x for x in c if x["list"] != "X"]
            d[:] = [x for x in d if not x["cluster_id"].startswith("X") and x["other_list"] != "X"]
        viol = self.violates(drop_seg)
        self.assertTrue(any("in no cluster" in x for x in viol))

    def test_incomplete_m1_cannot_inflate_capture(self):
        """M1 omits the 30 E1/E2 propositions SEG missed: an unchecked capture would rise from 0.5 to 1.0 (computable,
        agreed set 30 >= 20) and could pass the VALID gate."""
        lists, cl, dec = m1_fixture()
        drop = {f"{L}{i}" for L in "YZ" for i in range(30, 60)}
        cl2 = [c for c in cl if c["cluster_id"] not in drop]
        dec2 = [d for d in dec if d["cluster_id"] not in drop and d.get("target_cluster") not in drop]
        raw = v.capture(cl2, dec2, self.LK)
        self.assertEqual((raw["agreed"], raw["lenient"]), (30, 1.0))    # the inflation an unchecked pipeline reports
        self.assertEqual(v.decide(dict(Decision.base, capture=1.0))[0], "INSTRUMENTS-VALID")   # ...and its danger
        _, cap, reason, viol = self.gate(lists, cl2, dec2)
        self.assertIsNone(cap["lenient"])
        self.assertTrue(viol)
        base = dict(Decision.base, capture=cap["lenient"], capture_reason=reason)
        self.assertEqual(v.decide(base)[1], "INSTRUMENT-UNDETERMINED")

    def test_incomplete_m1_cannot_deflate_into_instrument(self):
        """M1 omits SEG decisions: the raw capture would fall; the gate makes it NOT-COMPUTABLE instead."""
        lists, cl, dec = m1_fixture()
        dec2 = [d for d in dec if d["other_list"] != "X"]
        _, cap, _, viol = self.gate(lists, cl, dec2)
        self.assertIsNone(cap["lenient"])
        self.assertTrue(viol)


class F1(unittest.TestCase):
    def test_reference_schema_never_condemns_instrument(self):
        for n in (1, 5, 100):
            o, q, r = v.decide(dict(Decision.base, ref_schema={"PX0106-U05": n}))
            self.assertEqual((o, q), ("NEEDS-REVISION", "INSTRUMENT-UNDETERMINED"))
            self.assertTrue(any("schema" in x and "F1" in x for x in r))

    def test_seg_schema_still_instrument(self):
        self.assertEqual(v.decide(dict(Decision.base, schema_ok=False))[1], "INSTRUMENT")


class Guard(unittest.TestCase):
    def test_refuses_without_authorization(self):
        self.assertEqual(v.main(["segment", "--commit", COMMIT]), 1)
        self.assertEqual(v.main(["result"]), 2)

    def fake(self, prereg=b"PREREG", tool=b"TOOL", auth_commit=COMMIT, auth_sha=None):   # X3
        d = tempfile.mkdtemp()
        os.makedirs(os.path.join(d, v.V1_DIR))
        os.makedirs(os.path.join(d, "audit-p3b"))
        os.makedirs(os.path.join(d, "scripts"))
        import hashlib
        for dep in v.DEPENDENCIES:
            os.makedirs(os.path.dirname(os.path.join(d, dep)), exist_ok=True)
        for rel, data in ((v.PREREG, prereg), (v.TOOL_REL, tool)) + tuple((dep, b"DEP") for dep in v.DEPENDENCIES):
            with open(os.path.join(d, rel), "wb") as f:
                f.write(data)
        with open(os.path.join(d, v.V1_DIR, v.AUTH), "w") as f:
            json.dump({"prereg_commit": auth_commit, "prereg_sha256": auth_sha or hashlib.sha256(prereg).hexdigest()}, f)

        def sha_file(p):
            with open(os.path.join(d, p), "rb") as f:
                return hashlib.sha256(f.read()).hexdigest()
        return type("C", (), {"CR": d, "REPO_ROOT": d, "sha256_file": staticmethod(sha_file)})

    def test_guard_binds_commit_prereg_tool(self):
        committed = dict({v.PREREG: b"PREREG", v.TOOL_REL: b"TOOL"}, **{dep: b"DEP" for dep in v.DEPENDENCIES})
        show = lambda cm, rel: committed[rel] if cm == COMMIT else (_ for _ in ()).throw(v.V1Error("not committed"))
        v.authorized(self.fake(), COMMIT, show)                                       # exact match passes
        with self.assertRaises(v.V1Error):
            v.authorized(self.fake(), "deadbeef", show)                               # not 40-hex
        with self.assertRaises(v.V1Error):
            v.authorized(self.fake(auth_commit="f" * 40), COMMIT, show)               # authorization names another commit
        with self.assertRaises(v.V1Error):
            v.authorized(self.fake(prereg=b"MODIFIED"), COMMIT, show)                 # modified working prereg
        with self.assertRaises(v.V1Error):
            v.authorized(self.fake(tool=b"MODIFIED"), COMMIT, show)                   # modified working tool
        with self.assertRaises(v.V1Error):
            v.authorized(self.fake(auth_sha="0" * 64), COMMIT, show)                  # wrong prereg hash
        with self.assertRaises(v.V1Error):
            v.authorized(self.fake(auth_commit="a" * 40), "a" * 40, show)            # commit lacking the files

    def test_n2_dependencies_bound(self):
        committed = dict({v.PREREG: b"PREREG", v.TOOL_REL: b"TOOL"}, **{dep: b"DEP" for dep in v.DEPENDENCIES})
        show = lambda cm, rel: committed[rel]
        v.authorized(self.fake(), COMMIT, show)                                        # exact dependencies accepted
        for dep in v.DEPENDENCIES:                                                     # reader, common, resolver
            c = self.fake()
            with open(os.path.join(c.CR, dep), "wb") as f:
                f.write(b"MODIFIED")
            with self.assertRaises(v.V1Error) as e:
                v.authorized(c, COMMIT, show)                                          # matching authorization, modified dep
            self.assertIn(dep, str(e.exception))
        self.assertEqual(set(v.DEPENDENCIES), {"scripts/p3b_read_source.py", "scripts/p3b_s5_common.py",
                                               "scripts/p3b_discovery_io.py"})

    def test_canaries_unique(self):
        self.assertEqual(len({v.canary(COMMIT, r) for r in v.RUNS}), len(v.RUNS))


if __name__ == "__main__":
    unittest.main()
