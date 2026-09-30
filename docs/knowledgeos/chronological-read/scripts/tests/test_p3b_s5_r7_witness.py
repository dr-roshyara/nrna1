#!/usr/bin/env python3
"""B2 WITNESS (R7 v2.3 §5, W1–W8, §7): tests on synthetic harness executions (r7_execution_fixture).
Positive control first; then one minimal mutation per invariant. No corpus; temp dirs only.
  cd scripts/tests && PYTHONPATH=.:.. python3 -m unittest test_p3b_s5_r7_witness
"""
import copy
import json
import os
import re
import shutil
import sys
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
sys.path.insert(0, os.path.dirname(HERE))
import r7_harness_fixture as hf                     # noqa: E402
import r7_execution_fixture as xf                   # noqa: E402
import p3b_s5_r7_universe as U                      # noqa: E402
import p3b_s5_r7_witness as W                       # noqa: E402

BATCH, SR = "OB9030", "sr"
PLAN = {"batch_id": BATCH, "revision": 7, "labels": {
    "lab-single": {"index": 1, "path": "SINGLE", "files": ["S0101"], "runs": {f"{BATCH}-R7-L01": {"role": "SINGLE", "files": ["S0101"]}}},
    "lab-dec": {"index": 2, "path": "DECOMPOSED", "files": ["S0201", "S0202"], "runs": {
        f"{BATCH}-R7-L02U01": {"role": "UNIT", "files": ["S0201"]}, f"{BATCH}-R7-L02U02": {"role": "UNIT", "files": ["S0202"]},
        f"{BATCH}-R7-L02S": {"role": "SYNTHESIS", "files": []}}},
    "lab-empty": {"index": 3, "path": "EMPTY", "files": [], "runs": {}}}}
CONTENTS = {"S0101": ("synthetic S0101 line\n" * 150).encode(), "S0201": ("synthetic S0201 line ä\n" * 3000).encode(),
            "S0202": ("synthetic S0202 line\n" * 200).encode()}
L01, U1, U2, SYN = f"{BATCH}-R7-L01", f"{BATCH}-R7-L02U01", f"{BATCH}-R7-L02U02", f"{BATCH}-R7-L02S"


def outputs():
    rec = lambda s: json.dumps({"source_id": s, "reading_state": "WHOLE-FILE", "ack_tokens": [], "facts": []}) + "\n"
    fin = {"objects.jsonl": "{}\n", "register.jsonl": "", "p1-gap.jsonl": "", "claim-evidence.json": "{}", "S3-LINT.json": "{}"}
    o = {L01: {f"{U.LEDGER}/{L01}/file-reading-records.jsonl": rec("S0101")},
         U1: {f"{U.LEDGER}/{U1}/file-reading-records.jsonl": rec("S0201")},
         U2: {f"{U.LEDGER}/{U2}/file-reading-records.jsonl": rec("S0202")}, SYN: {}}
    for r in (L01, SYN):
        o[r].update({f"{U.LEDGER}/{r}/{n}": t for n, t in fin.items()})
    return o


class Case(unittest.TestCase):
    def run_case(self, hooks=None, mutate_fs=None, persisted=None):
        root = tempfile.mkdtemp(prefix="w-root-")
        arch = tempfile.mkdtemp(prefix="w-arch-")
        try:
            for f in (U.REV3_CONTRACT_PATH, U.ADDENDUM_PATH):
                xf.put(root, f, b"frozen text " + f.encode())
            xf.put(root, f"{SR}/{BATCH}.R7-PLAN.json", U.canon(PLAN))
            for lab in PLAN["labels"]:
                xf.put(root, f"{SR}/{BATCH}/{lab}.json", json.dumps({"working_label": lab, "note": "synthetic slice"}))
            info = xf.build(root, arch, BATCH, PLAN, CONTENTS, SR, outputs(), hooks=hooks)
            if mutate_fs:
                mutate_fs(root, arch, info)
            res = W.witness(BATCH, PLAN, info["main"], os.path.join(arch, "subagents"), os.path.join(arch, "tool-results"),
                            xf.READER_ABS, xf.COMMIT, info["manifests"], root, CONTENTS, holdout_tokens={"S9999", "holdout-label"},
                            corpus_tokens={"/corpus/"})
            return res, info
        finally:
            shutil.rmtree(root, ignore_errors=True)
            shutil.rmtree(arch, ignore_errors=True)

    def fails(self, res, needle):
        self.assertEqual(res["value"], "F", res["failures"][:5])
        self.assertTrue(any(needle in x for x in res["failures"]), res["failures"][:8])


class Positive(Case):
    def test_valid_execution_is_witnessed_true(self):
        res, info = self.run_case()
        self.assertEqual(res["failures"], [])
        self.assertEqual(res["value"], "T")
        n_pages = sum(len(U.r5.byte_pages(CONTENTS[s])) for s in CONTENTS)
        self.assertEqual(len(res["reads"]), n_pages)
        self.assertTrue(any(r["kind"] == "read-input" and r["run"] == SYN for r in res["records"]))
        st = res["stages"]["lab-dec"]
        self.assertLess(st["units_validated"]["t_result"], st["synthesis_dispatched"])
        self.assertEqual(W.witness_bytes(res["records"]), W.witness_bytes(copy.deepcopy(res["records"])))


def drop_last_read(calls):
    idx = max(i for i, c in enumerate(calls) if c["name"] == "Bash")
    return calls[:idx] + calls[idx + 1:]


class Negative(Case):
    def test_T19_synthesis_reader_call(self):
        cmd = xf.reader_cmd(SYN, BATCH, "lab-dec", "S0201", 1)
        res, _ = self.run_case(hooks={SYN: lambda cs: cs[:1] + [hf.bash(cmd, "x")] + cs[1:]})
        self.fails(res, "W8")

    def test_T20_T21_T22_wrong_batch_run_label(self):
        for old, new in ((f"--batch {BATCH}", "--batch OB9031"), (f"--run {U1}", f"--run {U2}"), ("--label lab-dec", "--label lab-single")):
            hook = lambda cs, o=old, n=new: [dict(c, input=dict(c["input"], command=c["input"]["command"].replace(o, n)))
                                             if c["name"] == "Bash" else c for c in cs]
            res, _ = self.run_case(hooks={U1: hook})
            self.fails(res, "W2")

    def test_T49_T50_T51_T52_unauthorized_tools(self):
        for bad, needle in ((hf.bash("cat /corpus/S0201.txt", "..."), "SEAL"), (hf.bash("ls", "x"), "W8"),
                            (hf.bash(xf.reader_cmd(U1, BATCH, "lab-dec", "S0201", 1) + " > /tmp/x", ""), "noncanonical"),
                            (hf.write("/tmp/elsewhere.json", "{}"), "W8"), (hf.other("Grep", {"pattern": "x"}), "W8"),
                            (hf.read("/repo/app/Models/User.php", "x"), "W8")):
            res, _ = self.run_case(hooks={U1: lambda cs, b=bad: cs[:1] + [b] + cs[1:]})
            self.fails(res, needle)

    def test_T53_T88_holdout_is_seal_stop(self):
        res, _ = self.run_case(hooks={U1: lambda cs: cs[:1] + [hf.read("/x/holdout-label.json", "x")] + cs[1:]})
        self.fails(res, "SEAL")

    def test_refused_malformed_denied_are_stop_conditions(self):
        cmd = xf.reader_cmd(U1, BATCH, "lab-dec", "S0201", 1)
        for bad, needle in ((hf.bash(cmd, "REFUSED: x", exit_code=1), "W4 refused"),
                            (hf.bash(cmd, "usage: x", exit_code=2), "W4 malformed"), (hf.denied_bash(cmd), "W4 denied")):
            res, _ = self.run_case(hooks={U1: lambda cs, b=bad: cs[:1] + [b] + cs[1:]})
            self.fails(res, needle)

    def test_W3_header_trailer_and_page_hash(self):
        def edit(fn):
            return lambda cs: [dict(c, out=fn(c["out"])) if c["name"] == "Bash" else c for c in cs]
        for fn, needle in ((lambda o: o.split("\n", 1)[1], "header"), (lambda o: o.rsplit("=== END", 1)[0], "trailer"),
                           (lambda o: re.sub(r"page sha256 ([0-9a-f])", lambda m: "page sha256 " + ("0" if m.group(1) != "0" else "1"),
                                             o, count=1), "page sha256")):
            res, _ = self.run_case(hooks={U1: edit(fn)})
            self.fails(res, needle)

    def test_T56_zero_tool_and_declined(self):
        res, _ = self.run_case(hooks={U2: lambda cs: []})
        self.fails(res, "W1-ZERO-TOOL")
        res, _ = self.run_case(hooks={U2: lambda cs: [cs[-1]]})
        self.fails(res, "W1-DECLINED")

    def test_T54_T55_T57_T59_notifications_and_transcripts(self):
        def main_hook(fn):
            return {"main": fn}
        aid = lambda info_run: None
        res, _ = self.run_case(hooks={"main": lambda its: [i for i in its if not (i["kind"] == "notification" and U2[-3:] in "")]
                                      })
        self.assertEqual(res["value"], "T")                                        # sanity: hook identity keeps T
        res, info = self.run_case(mutate_fs=lambda r, a, i: os.remove(os.path.join(a, "subagents", f"agent-{i['agents'][U2]}.jsonl")))
        self.fails(res, "W1-TRANSCRIPT-MISSING")

        def no_count(its):
            for i in its:
                if i["kind"] == "notification" and i["tool_use_id"].startswith("toolu_"):
                    i["tool_uses"] = None
                    break
            return its
        res, _ = self.run_case(hooks={"main": no_count})
        self.fails(res, "W1-HARNESS-COUNT-MISSING")

        def twice(its):
            n = next(i for i in its if i["kind"] == "notification")
            return its + [dict(n, t=n["t"] + 50)]
        res, _ = self.run_case(hooks={"main": twice})
        self.fails(res, "W1-NOTIFICATION-MULTIPLE")

        def none(its):
            n = next(i for i in its if i["kind"] == "notification")
            return [i for i in its if i is not n]
        res, _ = self.run_case(hooks={"main": none})
        self.fails(res, "W1-NOTIFICATION-ABSENT")

    def test_T24_T25_T26_T27_transcript_integrity(self):
        def trunc(r, a, i):
            p = os.path.join(a, "subagents", f"agent-{i['agents'][U1]}.jsonl")
            lines = open(p).read().splitlines()
            open(p, "w").write("\n".join(lines[: len(lines) // 2]) + "\n")
        res, _ = self.run_case(mutate_fs=trunc)
        self.fails(res, "W1-COUNT-MISMATCH")

        def midline(r, a, i):
            p = os.path.join(a, "subagents", f"agent-{i['agents'][U1]}.jsonl")
            b = open(p, "rb").read()
            open(p, "wb").write(b[:-40])
        res, _ = self.run_case(mutate_fs=midline)
        self.fails(res, "W1-PARSE")

        def dup(r, a, i):
            p = os.path.join(a, "subagents", f"agent-{i['agents'][U1]}.jsonl")
            lines = open(p).read().splitlines()
            open(p, "w").write("\n".join(lines + lines[1:3]) + "\n")
        res, _ = self.run_case(mutate_fs=dup)
        self.fails(res, "W1-CHAIN")

        def swap(r, a, i):
            p = os.path.join(a, "subagents", f"agent-{i['agents'][U1]}.jsonl")
            lines = open(p).read().splitlines()
            lines[1], lines[2] = lines[2], lines[1]
            open(p, "w").write("\n".join(lines) + "\n")
        res, _ = self.run_case(mutate_fs=swap)
        self.fails(res, "W1-CHAIN")

    def test_T58_duplicate_transcript_for_one_agent(self):
        def dupfile(r, a, i):
            p = os.path.join(a, "subagents", f"agent-{i['agents'][U1]}.jsonl")
            shutil.copy(p, os.path.join(a, "subagents", f"agent-{i['agents'][U1]}-copy.jsonl"))
        res, _ = self.run_case(mutate_fs=dupfile)
        self.fails(res, "W1-TRANSCRIPT-DUPLICATE")

    def test_binding_line_and_canary(self):
        def bad_canary(its):
            for i in its:
                if i["kind"] == "dispatch":
                    i["prompt"] = i["prompt"].replace("canary=", "canary=f", 1)[:-1] if False else \
                        i["prompt"].replace(i["prompt"].split("canary=")[1][:16], "0" * 16, 1)
                    break
            return its
        res, _ = self.run_case(hooks={"main": bad_canary})
        self.fails(res, "W2")

    def test_T85_T86_T87_T92_T97_input_reads(self):
        other_unit = os.path.join("ROOT", U.LEDGER, U2, "file-reading-records.jsonl")
        for bad, run in ((hf.read("/repo/README.md", "x"), SYN),):
            res, _ = self.run_case(hooks={run: lambda cs, b=bad: cs[:1] + [b] + cs[1:]})
            self.fails(res, "W8 read outside I(run)")

    def test_T90_displayed_content_mismatch(self):
        def tamper(cs):
            c = dict(cs[0])
            c["out"] = c["out"].replace("synthetic slice", "tampered slice")
            return [c] + cs[1:]
        res, _ = self.run_case(hooks={U1: tamper})
        self.fails(res, "content_match")

    def test_T16_T18_stage_order_from_harness_time(self):
        def late_read(its):
            for i in its:
                if i["kind"] == "marker" and "UNITS-VALIDATED" in i["command"]:
                    i["t"] = 101.0                                     # validation before the unit reads
            return its
        res, _ = self.run_case(hooks={"main": late_read})
        self.fails(res, "W5")


class Preservation(unittest.TestCase):
    def test_T60_T61_T62_monotonicity(self):
        a = [{"kind": "dispatch", "run": "r1"}, {"kind": "read", "run": "r1", "page": 1}]
        b = a + [{"kind": "dispatch", "run": "r2"}]
        ua, fa = W.witness_bytes(a), W.witness_bytes(b)
        ud = {"decoder_sha256": "d", "extractor_sha256": "e", "transcripts": {"agent-1": "x"}}
        fd = {"decoder_sha256": "d", "extractor_sha256": "e", "transcripts": {"agent-1": "x", "agent-2": "y", "main": "m"}}
        self.assertEqual(W.monotonicity_violations(ua, fa, ud, fd), [])
        self.assertTrue(W.monotonicity_violations(ua, W.witness_bytes(b[1:]), ud, fd))                     # removed
        self.assertTrue(W.monotonicity_violations(ua, W.witness_bytes([dict(a[0], run="rX")] + b[1:]), ud, fd))   # modified
        self.assertTrue(W.monotonicity_violations(ua, fa, ud, dict(fd, extractor_sha256="e2")))             # extractor changed
        self.assertTrue(W.monotonicity_violations(ua, fa, ud, dict(fd, transcripts={"agent-1": "z"})))       # unit transcript changed


if __name__ == "__main__":
    unittest.main()
