#!/usr/bin/env python3
"""Tests for scripts/p3b_s5_verify.py (S5 batch verifier; infrastructure only, G-LOG-0041).

Fixtures are built under /tmp/s5verify-build/ by converting the committed S4 R2.2 material into the S5 layout (read-only
use of: ledger-p3b-r2/S4-R22/PB02..PB05, _batch_input_r2/s4-pilot-r22, _batch_input_r2/P3B-S4-R22-MANIFEST.json and
ledger-p3b-r2/S4-PILOT-R2-002/READ-LOG.jsonl): PB02..PB05 → OB9002..OB9005 (non-hub), plus a synthetic hub batch
OB9101 built by relabelling the PB04 label relation-8-tuple-vs-triple-reduction as a real listed hub label. No corpus
file, no sealed artifact and no hold-out list is read or printed; the quarantine case monkeypatches a FAKE hold-out set.

  python3 -m unittest discover -s docs/knowledgeos/chronological-read/scripts/tests -p 'test_p3b_s5_verify*.py'
"""
import copy
import hashlib
import importlib.util
import io
import json
import os
import shutil
import sys
import tempfile
import unittest
from contextlib import redirect_stdout, redirect_stderr

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(HERE)
_spec = importlib.util.spec_from_file_location("p3b_s5_verify", os.path.join(SCRIPTS, "p3b_s5_verify.py"))
v = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(v)
c, prep, CR = v.c, v.prep, v.CR
sys.path.insert(0, HERE)
import p3b_s5_ops_testlib as testlib  # noqa: E402  (synthetic fake-seal context; shares the verifier's common instance)
assert testlib.c is c, "the verifier and the test fixtures must share one common instance"
BUILD = "/tmp/s5verify-build"
S4_SLICES = os.path.join(CR, "_batch_input_r2", "s4-pilot-r22")
S4_OUT = os.path.join(CR, "ledger-p3b-r2", "S4-R22")
S4_LOG = os.path.join(CR, "ledger-p3b-r2", "S4-PILOT-R2-002", "READ-LOG.jsonl")
CONTRACT = hashlib.sha256(b"s5-verify-fixture-contract").hexdigest()
PB_TO_OB = {"PB02": "OB9002", "PB03": "OB9003", "PB04": "OB9004", "PB05": "OB9005"}
HUB_SRC_PB, HUB_SRC_LABEL, HUB_OB = "PB04", "relation-8-tuple-vs-triple-reduction", "OB9101"


def load(path):
    with open(path, encoding="utf-8") as f:
        return json.load(f)


S4_MAN = load(os.path.join(CR, "_batch_input_r2", "P3B-S4-R22-MANIFEST.json"))


def text(path):
    with open(path, encoding="utf-8") as f:
        return f.read()


def jl(path):
    with open(path, encoding="utf-8") as f:
        return [json.loads(l) for l in f if l.strip()]


def canon(x):
    return c.canon(x)


def hub_label():
    hubs = c.hubs()
    return sorted(l for l, r in hubs.items() if r.get("tier") == "Z" and not r.get("in_checklist"))[0], hubs


def s4_material(pb):
    labels = S4_MAN["batches"][pb]
    slices = {lab: load(os.path.join(S4_SLICES, f"{lab}.json")) for lab in labels}
    objs = jl(os.path.join(S4_OUT, pb, "objects.jsonl"))
    reg = jl(os.path.join(S4_OUT, pb, "register.jsonl"))
    gap = jl(os.path.join(S4_OUT, pb, "p1-gap-capture.jsonl"))
    log = [e for e in jl(S4_LOG) if e["batch_id"] == pb]
    return labels, slices, objs, reg, gap, log


def relabel(x, old, new):
    return json.loads(json.dumps(x, ensure_ascii=False).replace(old, new))


def to_s5(ob, labels, slices, objs, reg, gap, log, pb):
    """Convert one S4 R2.2 batch into S5 form (contract fields, hub flags, semantic rows, F3 offsets, ids)."""
    run = f"{ob}-R2"
    pre_old, pre_new = f"S4-PILOT-R2-002:{pb}:", f"{run}:{ob}:"
    out_sl = {}
    for lab in labels:
        sl = copy.deepcopy(slices[lab])
        sl.update(batch_id=ob, run_id=run, contract_sha256=CONTRACT, hub=False, hub_record=None)
        sl["source_tracks"] = {s: {"track_tag": t["track_tag"], "d23_track": prep.TRACK_MAPPING_2.get(t["track_tag"], "UNCLASSIFIED")}
                               for s, t in sl["source_tracks"].items()}
        sl["semantic_status_mechanical"] = prep.mechanical_semantic(sl["tier"], sl["p3a_pairs"])
        out_sl[lab] = sl
    out_o = []
    for o in objs:
        o = relabel(o, pre_old, pre_new)
        mech = out_sl[o["working_label"]]["semantic_status_mechanical"]
        o.update(run_id=run, batch_id=ob, contract_sha256=CONTRACT, hub=False, semantic_status_rule=mech["rule"])
        if mech["rule"] == "ROW-0":
            o["semantic_status"], o["semantic_status_note"] = None, prep.TIER_Z_NOTE
        else:
            o["semantic_status"], o["semantic_status_note"] = mech["value"], "slice semantic_status_mechanical (rows 3/4)"
        o["escalations"] = [{"field": x["field"], "reason": "SCHEMA-LIMITATION", "detail": x.get("reason")} for x in o["escalations"]]
        for d in o["stage2_dispositions"]:
            if d["method"] == "STAGE-2A-2B":
                d["offsets"] = [d["hit_key"]] if d["hit_kind"] == "raw" else []
        out_o.append(o)
    out_r = []
    for r in reg:
        r = relabel(r, pre_old, pre_new)
        r.update(run_id=run, batch_id=ob, contract_sha256=CONTRACT)
        out_r.append(r)
    out_g = []
    for g in gap:
        g = relabel(g, pre_old, pre_new)
        g.update(run_id=run, batch_id=ob, contract_sha256=CONTRACT)
        g.setdefault("generation_parameters", "default")      # contract revision 2: every record carries it (§B, schema E)
        out_g.append(g)
    out_l = [dict(e, run_id=run, batch_id=ob) for e in log]
    return {"ob": ob, "run": run, "labels": list(labels), "slices": out_sl, "objs": out_o, "reg": out_r, "gap": out_g,
            "log": out_l, "hub_batch": False}


def predicted(ctx):
    size = {}
    for e in ctx["log"]:
        for s, b in (e.get("bytes") or {}).items():
            size[s] = max(size.get(s, 0), b)
    disp = s2 = s1 = 0
    for lab, sl in ctx["slices"].items():
        lh = [r for r in sl["search_records"] if r["record"] == "LABEL-HITS"]
        dims = [r for r in sl["search_records"] if r["record"] == "DIMENSION"]
        keys = {(s, k, json.dumps(x)) for r in lh for k in ("raw_hits", "ledger_hits") for s, occ in (r.get(k) or {}).items() for x in occ}
        if not ctx["hub_batch"]:
            disp += len(keys) * len(dims)
            s2 += sum(size.get(s, 0) for s in sl["stage2_files"])
        s1 += sum(size.get(s, 0) for s in sl["source_tracks"])
    return {"dispositions": disp, "stage2_bytes": s2, "stage1_bytes": s1}


def write(root, ctxs, entry_hook=None, header_extra=None):
    """Write slices, manifest (header + entries), outputs and read logs for the given batch contexts."""
    lines = []
    for ctx in ctxs:
        ob, run = ctx["ob"], ctx["run"]
        sdir = os.path.join(root, prep.SLICE_ROOT, ob)
        os.makedirs(sdir, exist_ok=True)
        hashes = {}
        for lab in ctx["labels"]:
            t = canon(ctx["slices"][lab])
            hashes[lab] = c.sha256_bytes(t.encode("utf-8"))
            with open(os.path.join(sdir, f"{lab}.json"), "w", encoding="utf-8") as f:
                f.write(t + "\n")
        entry = {"batch_id": ob, "run_id": f"{ob}-R2", "tier": ctx["slices"][ctx["labels"][0]]["tier"], "hub_batch": ctx["hub_batch"],
                 "labels": ctx["labels"], "checklist": sum(1 for l in ctx["labels"] if ctx["slices"][l]["in_checklist"]),
                 "weights": {l: 1 for l in ctx["labels"]}, "predicted": predicted(ctx), "slice_sha256": hashes,
                 "contract_sha256": CONTRACT, "input_manifest_sha256": ctx["slices"][ctx["labels"][0]]["input_manifest_sha256"]}
        if entry_hook:
            entry_hook(ob, entry)
        lines.append(entry)
        odir = os.path.join(root, "ledger-p3b-r2", run)
        os.makedirs(odir, exist_ok=True)
        for name, recs in (("objects.jsonl", ctx["objs"]), ("register.jsonl", ctx["reg"]), ("p1-gap-capture.jsonl", ctx["gap"]),
                           ("READ-LOG.jsonl", ctx["log"])):
            with open(os.path.join(odir, name), "w", encoding="utf-8") as f:
                f.write("".join(canon(r) + "\n" for r in recs))
    body = "\n".join(canon(x) for x in lines) + "\n"
    with open(os.path.join(root, prep.MANIFEST), "w", encoding="utf-8") as f:
        f.write(canon({"header": dict({"artifact": prep.MANIFEST, "output_sha256": c.sha256_bytes(body.encode()), "fixture": True},
                                      **(header_extra or {}))}) + "\n" + body)


def as_comparison(ctx):
    """The same batch as a plan §K COMPARISON rerun (run id OB####-R2S); slices stay the primary run's frozen slices."""
    ob, old, new = ctx["ob"], ctx["run"], ctx["ob"] + "-R2S"
    for k in ("objs", "reg", "gap", "log"):
        ctx[k] = json.loads(json.dumps(ctx[k], ensure_ascii=False).replace(f'"{old}"', f'"{new}"').replace(f"{old}:", f"{new}:"))
    ctx["run"] = new
    return ctx


def nonhub_ctx(pb):
    return to_s5(PB_TO_OB[pb], *s4_material(pb), pb)


def hub_ctx():
    """Synthetic hub batch: the PB04 label relation-8-tuple-vs-triple-reduction relabelled as a listed hub label."""
    H, hubs = hub_label()
    labels, slices, objs, reg, gap, log = s4_material(HUB_SRC_PB)
    ctx = to_s5(HUB_OB, [HUB_SRC_LABEL], {HUB_SRC_LABEL: slices[HUB_SRC_LABEL]},
                [o for o in objs if o["working_label"] == HUB_SRC_LABEL],
                [r for r in reg if r["working_label"] == HUB_SRC_LABEL], [],
                [e for e in log if e["working_label"] == HUB_SRC_LABEL and e["step"] != 7], HUB_SRC_PB)
    ctx = relabel(ctx, HUB_SRC_LABEL, H)
    sl = ctx["slices"][H]
    sl["hub"], sl["hub_record"] = True, hubs[H]
    ctx["hub_batch"] = True
    o = ctx["objs"][0]
    o["hub"] = True
    o["stage2_dispositions"] = []
    dims = [r for r in sl["search_records"] if r["record"] == "DIMENSION"]
    for d in dims:
        o["absences"][d["dimension"]] = {"resolution": "ESCALATED", "negative_label": d["negative_label"],
                                         "population_basis": c.POPULATION_BASIS, "supplied_by": None,
                                         "reason": "hub label: stage 2 not performed (v1.7 §11.4); ESCALATED (LOAD)"}
        o["escalations"].append({"field": f"absences.{d['dimension']}", "reason": "LOAD",
                                 "detail": "v1.7 §11.4 hub exception", "hub_record": hubs[H]})
    for r in ctx["reg"]:
        if r["kind"] == "GAP":
            r["gap_status"] = "NOT-FOUND-LOAD-ESCALATED"
    return ctx


class Base(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        os.makedirs(BUILD, exist_ok=True)

    def setUp(self):
        self.root = tempfile.mkdtemp(prefix="case-", dir=BUILD)

    def tearDown(self):
        shutil.rmtree(self.root, ignore_errors=True)

    def run_v(self, batch, **kw):
        body, _ = v.verify(batch, root=self.root, **kw)
        return body

    def assertTag(self, body, tag, contains=None):
        hits = [f for f in body["failures"] if f.startswith(tag + " ") and (contains is None or contains in f)]
        self.assertTrue(hits, f"expected a {tag} failure{' containing ' + repr(contains) if contains else ''}; got {body['failures'][:8]}")
        self.assertEqual(body["result"], "FAIL")

    def main_quiet(self, argv):
        out, err = io.StringIO(), io.StringIO()
        with redirect_stdout(out), redirect_stderr(err):
            rc = v.main(argv)
        return rc, out.getvalue(), err.getvalue()


class Positive(Base):
    def test_converted_s4_batches_pass(self):
        ctxs = [nonhub_ctx(pb) for pb in PB_TO_OB] + [hub_ctx()]
        write(self.root, ctxs)
        for ctx in ctxs:
            body = self.run_v(ctx["ob"])
            if ctx["ob"] == "OB9005":
                # G-LOG-0045 item 3 (strict range rule): the real S4 R2.2 PB05 output writes an S-id range whose interval
                # spans hold-out ids; that is an implicit hold-out citation, so QUARANTINE (and only QUARANTINE) fails.
                self.assertEqual(body["result"], "FAIL")
                self.assertEqual(len(body["failures"]), 1, body["failures"][:5])
                self.assertTrue(body["failures"][0].startswith("QUARANTINE outputs carry 0 hold-out label name(s)"))
            else:
                self.assertEqual(body["result"], "PASS", f"{ctx['ob']}: {body['failures'][:10]}")
            self.assertEqual(body["counts"]["objects"], len(ctx["labels"]))
            self.assertEqual(set(body["gates"]) >= {"G-01", "G-12", "HUB", "F3", "QUARANTINE"}, True)

    def test_main_writes_report_with_header_and_refuses_overwrite(self):
        write(self.root, [nonhub_ctx("PB03")])
        rc, out, err = self.main_quiet(["--batch", "OB9003", "--root", self.root])
        self.assertEqual(rc, 0, err + out)
        path = os.path.join(self.root, "audit-p3b", "S5-VERIFY-OB9003.json")
        rep = load(path)
        for k in ("script_name", "script_version", "parameters", "input_hashes", "output_sha256", "python_version",
                  "platform", "protocol_sha256", "plan_sha256", "population_basis", "run_timestamp_utc"):
            self.assertIn(k, rep["header"])
        self.assertEqual(rep["header"]["output_sha256"], c.sha256_bytes(canon(rep["body"]).encode("utf-8")))
        rc2, _, err2 = self.main_quiet(["--batch", "OB9003", "--root", self.root])
        self.assertEqual(rc2, 2)
        self.assertIn("append-only", err2)

    def test_determinism(self):
        write(self.root, [nonhub_ctx("PB02")])
        a, b = os.path.join(self.root, "a.json"), os.path.join(self.root, "b.json")
        self.assertEqual(self.main_quiet(["--batch", "OB9002", "--root", self.root, "--out", a])[0], 0)
        self.assertEqual(self.main_quiet(["--batch", "OB9002", "--root", self.root, "--out", b])[0], 0)
        ra, rb = load(a), load(b)
        self.assertEqual(ra["header"]["output_sha256"], rb["header"]["output_sha256"])
        self.assertEqual(canon(ra["body"]), canon(rb["body"]))

    def test_alert_only_case_still_passes(self):
        ctx = nonhub_ctx("PB03")
        lab = ctx["labels"][0]
        outside = "S0001" if "S0001" not in json.dumps(ctx["slices"][lab]) else "S0002"
        ctx["log"].append({"batch_id": ctx["ob"], "bytes": {outside: 950000}, "refused": False, "run_id": ctx["run"],
                           "sha256": {outside: "0" * 64}, "source_ids": [outside], "step": 10, "utc": "2026-09-25T00:00:00Z",
                           "working_label": lab})
        write(self.root, [ctx], entry_hook=lambda ob, e: e["predicted"].update(stage2_bytes=1000, stage1_bytes=1000))
        body = self.run_v(ctx["ob"])
        self.assertEqual(body["result"], "PASS", body["failures"][:6])
        joined = "\n".join(body["alerts"])
        self.assertIn("LOAD stage2_bytes", joined)
        self.assertIn("LOAD stage1_bytes", joined)
        self.assertIn("RESEARCH-READ step-10 bytes", joined)
        self.assertIn("CONTRACT-DEVIATION", joined)

    def test_comparison_run_r2s(self):
        ctx = as_comparison(nonhub_ctx("PB03"))
        write(self.root, [ctx])
        body = self.run_v(ctx["ob"], run=ctx["run"])
        self.assertEqual(body["result"], "PASS", body["failures"][:6])
        self.assertTrue(body["comparison"])
        self.assertFalse(body["acceptance_evidence"])
        rc, out, err = self.main_quiet(["--batch", ctx["ob"], "--run", ctx["run"], "--root", self.root])
        self.assertEqual(rc, 0, err)
        rep = load(os.path.join(self.root, "audit-p3b", f"S5-VERIFY-{ctx['run']}.json"))
        self.assertTrue(rep["body"]["comparison"])
        primary = self.run_v(ctx["ob"], run=f"{ctx['ob']}-R2")                  # primary run has no outputs here
        self.assertFalse(primary["comparison"])
        self.assertTrue(primary["acceptance_evidence"])

    def test_comparison_run_same_checks(self):
        ctx = as_comparison(nonhub_ctx("PB03"))
        ctx["objs"][0]["type_status"] = "SORT-OF-CLOSED"
        ctx["reg"][0]["rs_id"] = ctx["reg"][0]["rs_id"].replace("-R2S:", "-R2:")   # a primary-run id inside a COMPARISON run
        write(self.root, [ctx])
        body = self.run_v(ctx["ob"], run=ctx["run"])
        self.assertTag(body, "G-05", "type_status")
        self.assertTag(body, "G-07", "rs_id")

    def test_rerun_id_and_bad_ids_refused(self):
        with self.assertRaises(v.Refused):
            v.verify("PB02", root=self.root)
        with self.assertRaises(v.Refused):
            v.verify("OB9002", root=self.root, run="OB9003-R2")
        for bad in ("OB9002-R2T", "OB9002-R2S.1", "OB9002-R3"):
            with self.assertRaises(v.Refused):
                v.verify("OB9002", root=self.root, run=bad)


class NonHubNegative(Base):
    def build(self, pb, hook=None, entry_hook=None):
        ctx = nonhub_ctx(pb)
        if hook:
            hook(ctx)
        write(self.root, [ctx], entry_hook)
        return ctx

    def test_wrong_slice_hash(self):
        ctx = self.build("PB03")
        p = os.path.join(self.root, prep.SLICE_ROOT, ctx["ob"], ctx["labels"][0] + ".json")
        sl = json.loads(text(p))
        sl["family_md"] += " "
        with open(p, "w", encoding="utf-8") as f:
            f.write(canon(sl) + "\n")
        self.assertTag(self.run_v(ctx["ob"]), "SLICES", "hash differs")

    def test_contract_sha_inconsistent(self):
        def h(ctx):
            ctx["reg"][0]["contract_sha256"] = "f" * 64
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "G-07")

    def test_bad_enum(self):
        def h(ctx):
            ctx["objs"][0]["type_status"] = "SORT-OF-CLOSED"
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "G-05", "type_status")

    def test_schema_extra_key(self):
        def h(ctx):
            ctx["objs"][0]["confidence"] = 0.9
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "SCHEMA", "outside the schema")

    def test_missing_object_label(self):
        def h(ctx):
            ctx["objs"].pop()
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "G-02")

    def test_model_rule(self):
        def h(ctx):
            ctx["objs"][0]["model_id"] = "some-other-model"
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "G-07", "model rule")

    def test_model_id_exact(self):
        self.assertTrue(v.model_ok("claude-opus-5-5"))
        self.assertTrue(v.model_ok("claude-opus-5-5[1m]"))
        for bad in ("claude-opus-5-5-20260901", "claude-opus-5-5[2m]", "claude-opus-5-5 ", "claude-opus-5-5[1m]x", None):
            self.assertFalse(v.model_ok(bad), bad)

        def h(ctx):
            ctx["gap"][0]["model_id"] = "claude-opus-5-5-20260901"
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "G-07", "provenance ids")

    def _track_b_found(self, ctx, with_timing):
        for o in ctx["objs"]:
            sl = ctx["slices"][o["working_label"]]
            if not o["timeline"] or any(t["d23_track"] == "B" for t in sl["source_tracks"].values()):
                continue                                  # only the designated source becomes Track B
            first = o["timeline"][0]["source_id"]
            for dim, a in sorted(o["absences"].items()):
                if a["resolution"] == "FOUND" and a["supplied_by"]["source_id"] != first:
                    b = a["supplied_by"]["source_id"]
                    sl["source_tracks"][first] = {"track_tag": "TRACK-A-PHASE-MEASURE", "d23_track": "A"}
                    sl["source_tracks"][b] = {"track_tag": "TRACK-B-GAP-DISCOVERY", "d23_track": "B"}
                    if with_timing:
                        for a2 in o["absences"].values():
                            if a2["resolution"] == "FOUND" and a2["supplied_by"]["source_id"] == b:
                                a2["relative_timing"] = "LATER-THAN-FIRST-APPEARANCE"
                    return o["working_label"], dim
        self.skipTest("no FOUND on a source other than the first timeline point")

    def test_d23_found_track_b_needs_relative_timing(self):
        found = {}
        body = self.run_v(self.build("PB04", lambda ctx: found.update(r=self._track_b_found(ctx, False)))["ob"])
        self.assertTag(body, "D-23", "relative_timing")

    def test_d23_found_track_b_with_relative_timing_passes(self):
        found = {}
        body = self.run_v(self.build("PB04", lambda ctx: found.update(r=self._track_b_found(ctx, True)))["ob"])
        # FOUND with relative_timing passes (§14.5); births / edges / statuses on the same source still fail D-23, correctly
        self.assertFalse([f for f in body["failures"] if f.startswith("SCHEMA ") or (f.startswith("D-23 ") and ": FOUND " in f)],
                         body["failures"])
        self.assertIn(body["relative_timing_key_source"], ("SCHEMA", "§14.5 (fallback)"))

    def test_step10_cap_exhaustion_needs_no_register_note(self):
        def h(ctx):
            lab = ctx["labels"][0]
            s = sorted(v.SID.findall(json.dumps(ctx["slices"][lab])))[0]
            ctx["log"].append({"batch_id": ctx["ob"], "bytes": {s: 900000}, "refused": False, "run_id": ctx["run"],
                               "sha256": {s: "0" * 64}, "source_ids": [s], "step": 10, "utc": "2026-09-25T00:00:00Z",
                               "working_label": lab})
        body = self.run_v(self.build("PB03", h)["ob"])
        self.assertEqual(body["result"], "PASS", body["failures"][:6])      # no register note is expected for exhaustion
        self.assertTrue(body["research_reads"]["cap_reached"])

    def test_f3_coverage_gap(self):
        def h(ctx):
            o = next(o for o in ctx["objs"] if any(d["method"] == "STAGE-2A-2B" and d["offsets"] for d in o["stage2_dispositions"]))
            d = next(d for d in o["stage2_dispositions"] if d["method"] == "STAGE-2A-2B" and d["offsets"])
            d["offsets"] = []
        self.assertTag(self.run_v(self.build("PB02", h)["ob"]), "F3", "missing 1")

    def test_f3_offsets_missing_key(self):
        def h(ctx):
            o = next(o for o in ctx["objs"] if any(d["method"] == "STAGE-2A-2B" for d in o["stage2_dispositions"]))
            del next(d for d in o["stage2_dispositions"] if d["method"] == "STAGE-2A-2B")["offsets"]
        self.assertTag(self.run_v(self.build("PB02", h)["ob"]), "F3", "without an `offsets` list")

    def test_f1_null_order_without_escalation(self):
        def h(ctx):
            o = next(o for o in ctx["objs"] if any(tp["order"] is None for tp in o["timeline"]))
            tp = next(tp for tp in o["timeline"] if tp["order"] is None)
            o["escalations"] = [x for x in o["escalations"] if x["field"] != f"timeline[{tp['source_id']}].order"]
        self.assertTag(self.run_v(self.build("PB02", h)["ob"]), "F1")

    def test_wrong_mechanical_semantic_value(self):
        def h(ctx):
            o = next(o for o in ctx["objs"] if o["semantic_status_rule"] == "ROW-3")
            o["semantic_status"] = "IDENTITY-UNWITNESSED"
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "SEMANTIC", "semantic_status_mechanical")

    def test_tier_z_note(self):
        def h(ctx):
            o = next(o for o in ctx["objs"] if o["tier"] == "Z")
            o["semantic_status_note"] = "NO-PAIR-EVIDENCE"
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "SEMANTIC", "Tier Z")

    def test_row1_without_d4_evidence(self):
        def h(ctx):
            o = next(o for o in ctx["objs"] if o["tier"] == "U")
            o["semantic_status"], o["semantic_status_rule"] = "CONTESTED", "ROW-1"
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "SEMANTIC", "ROW-1")

    def test_g08_birth_on_secondary(self):
        def h(ctx):
            o = next(o for o in ctx["objs"] if any(m.get("provenance") == "SECONDARY-SYNTHESIS" and m.get("status") == "CONTENT"
                                                   for m in ctx["slices"][o["working_label"]]["source_meta"].values()))
            meta = ctx["slices"][o["working_label"]]["source_meta"]
            s = sorted(k for k, m in meta.items() if m["provenance"] == "SECONDARY-SYNTHESIS" and m["status"] == "CONTENT")[0]
            o["births"]["formal"] = f'MOVED[{s}, "x"]'
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "G-08", "SECONDARY-SYNTHESIS")

    def test_g08_unknown_provenance_sole_basis(self):
        def h(ctx):
            o = ctx["objs"][0]
            sl = ctx["slices"][o["working_label"]]
            s = next(tp["source_id"] for tp in o["timeline"])
            del sl["source_meta"][s]                     # an S-id without source_meta counts as UNKNOWN (G-LOG-0023 c)
            o["births"]["formal"] = f'MOVED[{s}, "x"]'
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "G-08", "UNKNOWN")

    def test_d23_birth_on_track_b(self):
        state = {}

        def h(ctx):
            o = next(o for o in ctx["objs"] if o["working_label"] == "invariant-universe-not-one-mathematical-object")
            sl = ctx["slices"][o["working_label"]]
            first = o["timeline"][0]["source_id"]
            sl["source_tracks"][first] = {"track_tag": "TRACK-A-PHASE-MEASURE", "d23_track": "A"}
            s = sorted(k for k, m in sl["source_meta"].items() if m["provenance"] == "PRIMARY" and k != first)[0]
            sl["source_tracks"][s] = {"track_tag": "TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED", "d23_track": "B"}
            o["births"]["formal"] = f'MOVED[{s}, "x"]'
            state["s"] = s
        body = self.run_v(self.build("PB03", h)["ob"])
        self.assertTag(body, "D-23", state["s"])
        self.assertFalse([f for f in body["failures"] if f.startswith("G-08 ")], body["failures"])

    def test_found_without_whole_file_read(self):
        def h(ctx):
            o = next(o for o in ctx["objs"] if any(a["resolution"] == "FOUND" for a in o["absences"].values()))
            s = next(a["supplied_by"]["source_id"] for a in o["absences"].values() if a["resolution"] == "FOUND")
            ctx["log"] = [e for e in ctx["log"] if not (e["working_label"] == o["working_label"] and s in e["source_ids"])]
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "G-04", "no whole-file reader read")

    def test_disposition_coverage(self):
        def h(ctx):
            o = next(o for o in ctx["objs"] if o["stage2_dispositions"])
            o["stage2_dispositions"].append(copy.deepcopy(o["stage2_dispositions"][0]))
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "G-04", "repeated 1")

    def test_citation_not_in_slice_or_reads(self):
        def h(ctx):
            ids = set()
            for sl in ctx["slices"].values():
                ids |= set(v.SID.findall(json.dumps(sl)))
            ids |= {s for e in ctx["log"] for s in e["source_ids"]}
            s = next(f"S{n:04d}" for n in range(1, 3000) if f"S{n:04d}" not in ids)
            ctx["reg"][0]["statement"] += f" (compare {s})"
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "AUDIT")

    def test_non_hub_load_escalation(self):
        def h(ctx):
            ctx["objs"][0]["escalations"].append({"field": "absences.x", "reason": "LOAD", "detail": "x"})
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "HUB", "non-hub")

    def test_research_hypothesis_below_test_defined(self):
        def h(ctx):
            r = next(r for r in ctx["reg"] if r["kind"] in ("HYPOTHESIS", "STRUCTURE-CANDIDATE"))
            r["lifecycle_stage"] = "HYPOTHESIS-STATED"
        body = self.run_v(self.build("PB02", h)["ob"]) if any(
            r["kind"] in ("HYPOTHESIS", "STRUCTURE-CANDIDATE") for r in nonhub_ctx("PB02")["reg"]) else None
        if body is None:
            for pb in ("PB03", "PB04", "PB05"):
                if any(r["kind"] in ("HYPOTHESIS", "STRUCTURE-CANDIDATE") for r in nonhub_ctx(pb)["reg"]):
                    shutil.rmtree(self.root)
                    os.makedirs(self.root)
                    body = self.run_v(self.build(pb, h)["ob"])
                    break
        self.assertTag(body, "G-09", "not at TEST-DEFINED")

    def test_research_status_field(self):
        def h(ctx):
            ctx["reg"][0]["STATUS"] = "SUPPORTED-IN-CORPUS"
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "G-09", "STATUS")

    def test_g12_register_id_in_layer_a(self):
        def h(ctx):
            ctx["objs"][0]["timeline_summary"]["later_support"] = [f"{ctx['run']}:{ctx['ob']}:1"]
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "G-12", "register id")

    def test_quarantine_hit_fake_holdout(self):
        fake_label, fake_sid = sorted(testlib.FAKE_H)[0], sorted(testlib.FAKE_HF)[0]

        def h(ctx):
            ctx["reg"][0]["statement"] += f" Compare {fake_label} and {fake_sid}."
            ctx["objs"][0]["timeline_summary"]["later_support"] = [fake_sid]
        ctx = self.build("PB03", h)
        with testlib.fake_holdout():                     # synthetic sets on the shared common instance; real seal untouched
            body = self.run_v(ctx["ob"])
            self.assertTag(body, "QUARANTINE", "outputs carry")
            rc, out, err = self.main_quiet(["--batch", ctx["ob"], "--root", self.root])
        self.assertEqual(rc, 1, err)
        rep = text(os.path.join(self.root, "audit-p3b", f"S5-VERIFY-{ctx['ob']}.json"))
        for token in (fake_label, fake_sid):             # counts only: the fake hold-out names are withheld everywhere
            self.assertNotIn(token, json.dumps(body))
            self.assertNotIn(token, rep)
            self.assertNotIn(token, out)

    def test_quarantine_hit_in_slice_directory(self):
        fake_label = sorted(testlib.FAKE_H)[0]

        def h(ctx):
            ctx["slices"][ctx["labels"][0]]["family_md"] += f"\nsee {fake_label}\n"     # re-hashed: SLICES still passes
        ctx = self.build("PB03", h)
        with testlib.fake_holdout():
            body = self.run_v(ctx["ob"])
        self.assertTag(body, "QUARANTINE", "slice-directory")
        self.assertFalse([f for f in body["failures"] if f.startswith("SLICES ")], body["failures"])
        self.assertNotIn(fake_label, json.dumps(body))

    def test_quarantined_family_md_must_be_empty(self):
        def h(ctx):
            ctx["slices"][ctx["labels"][0]]["family_md_status"] = "QUARANTINED (addendum §6)"
        self.assertTag(self.run_v(self.build("PB03", h)["ob"]), "QUARANTINE", "family_md is not empty")


class HubNegative(Base):
    def build(self, hook=None, slice_hook=None):
        ctx = hub_ctx()
        if hook:
            hook(ctx)
        write(self.root, [ctx])
        return ctx

    def H(self, ctx):
        return ctx["labels"][0]

    def test_hub_positive(self):
        body = self.run_v(self.build()["ob"])
        self.assertEqual(body["result"], "PASS", body["failures"][:8])
        self.assertEqual(body["load"]["predicted"]["stage2b_occurrences"], 0)

    def test_hub_with_stage2_dispositions(self):
        src = [o for o in s4_material(HUB_SRC_PB)[2] if o["working_label"] == HUB_SRC_LABEL][0]

        def h(ctx):
            ctx["objs"][0]["stage2_dispositions"] = [copy.deepcopy(src["stage2_dispositions"][0])]
        self.assertTag(self.run_v(self.build(h)["ob"]), "HUB", "stage2_dispositions on a hub")

    def test_hub_missing_load(self):
        def h(ctx):
            o = ctx["objs"][0]
            first = next(x for x in o["escalations"] if x["reason"] == "LOAD")
            o["escalations"].remove(first)
        self.assertTag(self.run_v(self.build(h)["ob"]), "HUB", "without a LOAD escalation")

    def test_hub_load_wrong_hub_record(self):
        def h(ctx):
            x = next(x for x in ctx["objs"][0]["escalations"] if x["reason"] == "LOAD")
            x["hub_record"] = dict(x["hub_record"], threshold=1)
        self.assertTag(self.run_v(self.build(h)["ob"]), "HUB", "hub_record")

    def test_found_on_hub_dimension(self):
        def h(ctx):
            o = ctx["objs"][0]
            dim = sorted(o["absences"])[0]
            s = ctx["slices"][self.H(ctx)]["stage2_files"][0]
            o["absences"][dim].update(resolution="FOUND", supplied_by={"source_id": s, "anchor": "a", "quote": "q"})
        self.assertTag(self.run_v(self.build(h)["ob"]), "HUB", "FOUND on hit-bearing hub dimension")

    def test_step7_read_for_hub(self):
        def h(ctx):
            s = ctx["slices"][self.H(ctx)]["stage2_files"][0]
            ctx["log"].append({"batch_id": ctx["ob"], "bytes": {s: 10}, "refused": False, "run_id": ctx["run"], "sha256": {s: "0" * 64},
                               "source_ids": [s], "step": 7, "utc": "2026-09-25T00:00:00Z", "working_label": self.H(ctx)})
        self.assertTag(self.run_v(self.build(h)["ob"]), "HUB", "step-7")

    def test_stage2a_scan_for_hub(self):
        def h(ctx):
            s = ctx["slices"][self.H(ctx)]["stage2_files"][0]
            ctx["log"].append({"batch_id": ctx["ob"], "bytes": {s: 10}, "occurrences": {s: [1]}, "refused": False, "run_id": ctx["run"],
                               "sha256": {s: "0" * 64}, "source_ids": [s], "step": 7, "term": "x", "tool": "p3b-stage2a/1",
                               "utc": "2026-09-25T00:00:00Z", "working_label": self.H(ctx)})
        self.assertTag(self.run_v(self.build(h)["ob"]), "HUB", "Stage-2A")

    def test_hub_step10_hit_file_not_read_at_step1(self):
        def h(ctx):
            sl = ctx["slices"][self.H(ctx)]
            step1 = {s for e in ctx["log"] if e["step"] == 1 for s in e["source_ids"]}
            s = sorted(set(sl["stage2_files"]) - step1)[0]
            ctx["log"].append({"batch_id": ctx["ob"], "bytes": {s: 10}, "refused": False, "run_id": ctx["run"], "sha256": {s: "0" * 64},
                               "source_ids": [s], "step": 10, "utc": "2026-09-25T00:00:00Z", "working_label": self.H(ctx)})
        self.assertTag(self.run_v(self.build(h)["ob"]), "HUB", "step-10 read")

    def test_hub_gap_status(self):
        def h(ctx):
            dim = sorted(ctx["objs"][0]["absences"])[0]
            g = next(r for r in ctx["reg"] if r["kind"] == "GAP") if any(r["kind"] == "GAP" for r in ctx["reg"]) else None
            if g is None:
                g = copy.deepcopy(ctx["reg"][0])
                g.update(kind="GAP", rs_id=f"{ctx['run']}:{ctx['ob']}:999", output_layer="B", lifecycle_stage="OBSERVED")
                ctx["reg"].append(g)
            g["statement"] = f"The {dim} dimension is missing for this label."
            g["gap_status"] = "NOT-FOUND-AFTER-CENSUS"
        self.assertTag(self.run_v(self.build(h)["ob"]), "HUB", "GAP on hit-bearing hub dimension")

    def test_hub_flag_differs_from_list(self):
        def h(ctx):
            sl = ctx["slices"][self.H(ctx)]
            sl["hub"], sl["hub_record"] = False, None
        self.assertTag(self.run_v(self.build(h)["ob"]), "HUB", "list membership")

    def test_non_hub_label_flagged_as_hub(self):
        ctx = nonhub_ctx("PB03")
        lab = ctx["labels"][0]
        ctx["slices"][lab]["hub"] = True
        write(self.root, [ctx])
        self.assertTag(self.run_v(ctx["ob"]), "HUB", "list membership")



class ContractRevision2(unittest.TestCase):
    """Contract revision 2 (G-LOG-0048): P1-gap records must carry generation_parameters; a raw hit_key must be an
    integer offset (a string is refused even when its value matches); the slice root comes from the manifest header."""

    def setUp(self):
        self.root = tempfile.mkdtemp(prefix="s5v-rev2-", dir="/tmp")

    def tearDown(self):
        shutil.rmtree(self.root, ignore_errors=True)

    def test_p1_gap_without_generation_parameters_fails(self):
        ctx = nonhub_ctx("PB03")
        for g in ctx["gap"]:
            g.pop("generation_parameters", None)
        write(self.root, [ctx])
        body = v.verify(ctx["ob"], root=self.root)[0]
        self.assertEqual(body["result"], "FAIL")
        self.assertTrue(any("missing ['generation_parameters']" in f for f in body["failures"]))

    def test_string_raw_hit_key_fails_even_if_value_matches(self):
        ctx = nonhub_ctx("PB03")
        for o in ctx["objs"]:
            for d in o["stage2_dispositions"]:
                if d["hit_kind"] == "raw":
                    d["hit_key"] = str(d["hit_key"])
        write(self.root, [ctx])
        body = v.verify(ctx["ob"], root=self.root)[0]
        self.assertEqual(body["result"], "FAIL")
        self.assertTrue(any("is str, not an integer offset" in f for f in body["failures"]))



def page_entries(ctx, sids, label, drop=None, forge=None):
    """Paged read-log entries (contract revision 3) for whole files, built from the real content through the
    discovery resolver and the reader's own page definition; `drop` = (sid, page) omitted, `forge` = (sid, page)
    logged with a wrong hash."""
    data = c.dio.discovery_resolver().read_many(sorted(sids))
    out = []
    for s in sorted(sids):
        text = data[s].decode("utf-8", errors="replace")
        for k, (a, b) in enumerate(v.reader.pages_of(text), 1):
            if drop == (s, k):
                continue
            chunk = text[a:b]
            ph = "0" * 64 if forge == (s, k) else c.sha256_bytes(chunk.encode("utf-8"))
            out.append({"run_id": ctx["run"], "batch_id": ctx["ob"], "working_label": label, "step": 7, "source_ids": [s],
                        "refused": False, "bytes": {s: len(chunk.encode("utf-8"))}, "sha256": {s: c.sha256_bytes(chunk.encode("utf-8"))},
                        "stdout": "pipe", "page": {"source_id": s, "page": k, "n_pages": len(v.reader.pages_of(text)),
                                                   "char_start": a, "char_end": b, "page_sha256": ph, "content_sha256": "x"}})
    return out


class ContractRevision3ReadCoverage(unittest.TestCase):
    """Contract revision 3 (G-LOG-0050): delivery is not reading. With a revision-3 manifest, every file that grounds
    whole-file evidence (stage-2 dispositions, FOUND supplied_by, births, change-type timeline points) and every
    OMQ-14 source (CONTRADICTION/CORRECTION/RETRACTION rows) must have every page logged with a verified hash; a
    missing or forged page FAILS; an OMQ-14 source may instead carry a CONTRACT-DEVIATION escalation."""
    REV3 = {"contract": {"revision": 3}}

    def setUp(self):
        self.root = tempfile.mkdtemp(prefix="s5v-rev3-", dir="/tmp")

    def tearDown(self):
        shutil.rmtree(self.root, ignore_errors=True)

    def needed(self, ctx):
        write(self.root, [ctx], header_extra=self.REV3)
        body = v.verify(ctx["ob"], root=self.root)[0]
        need = set()
        for f in body["failures"]:
            m = __import__("re").match(r"READ-COVERAGE (\S+): (?:OMQ-14 source )?(S\d{4})", f)
            if m:
                need.add((m.group(1), m.group(2)))
        return body, need

    def test_delivery_only_fails_and_full_pages_pass(self):
        ctx = nonhub_ctx("PB03")
        body, need = self.needed(ctx)                           # legacy whole-mode reads only: delivery, not reading
        self.assertEqual(body["result"], "FAIL")
        self.assertTrue(need, "a revision-3 run with no paged reads must fail READ-COVERAGE")
        shutil.rmtree(self.root); os.makedirs(self.root)
        ctx["log"] = ctx["log"] + page_entries(ctx, {s for _, s in need}, ctx["labels"][0])
        write(self.root, [ctx], header_extra=self.REV3)
        body = v.verify(ctx["ob"], root=self.root)[0]
        self.assertFalse([f for f in body["failures"] if f.startswith("READ-COVERAGE")], body["failures"][:5])

    def test_missing_page_and_forged_hash_fail(self):
        ctx = nonhub_ctx("PB03")
        _, need = self.needed(ctx)
        sids = {s for _, s in need}
        data = c.dio.discovery_resolver().read_many(sorted(sids))
        multi = next(s for s in sorted(sids) if len(v.reader.pages_of(data[s].decode("utf-8", errors="replace"))) > 1) \
            if any(len(v.reader.pages_of(data[s].decode("utf-8", errors="replace"))) > 1 for s in sids) else sorted(sids)[0]
        for kw, tag in (({"drop": (multi, 1)}, "rests on whole-file evidence"), ({"forge": (multi, 1)}, "fail hash / span")):
            shutil.rmtree(self.root); os.makedirs(self.root)
            c2 = nonhub_ctx("PB03")
            c2["log"] = c2["log"] + page_entries(c2, sids, c2["labels"][0], **kw)
            write(self.root, [c2], header_extra=self.REV3)
            body = v.verify(c2["ob"], root=self.root)[0]
            self.assertEqual(body["result"], "FAIL")
            self.assertTrue(any(f.startswith("READ-COVERAGE") and (tag in f or multi in f) for f in body["failures"]),
                            body["failures"][:6])

    def test_omq14_contract_deviation_escape(self):
        ctx = nonhub_ctx("PB03")
        _, need = self.needed(ctx)
        omq = [(lab, s) for lab, s in need if any(s == json.loads(r)["source_id"] and set(json.loads(r).get("types") or []) & v.OMQ14_ROW_TYPES
                                                  for r in ctx["slices"][lab]["bundle"]["rows_verbatim"])]
        if not omq:
            self.skipTest("fixture has no OMQ-14-only source")
        lab, s = omq[0]
        shutil.rmtree(self.root); os.makedirs(self.root)
        c2 = nonhub_ctx("PB03")
        for o in c2["objs"]:
            if o["working_label"] == lab:
                o["escalations"] = o["escalations"] + [{"field": "timeline", "reason": "CONTRACT-DEVIATION",
                                                        "detail": f"{s}: whole-file reading not completed"}]
        write(self.root, [c2], header_extra=self.REV3)
        body = v.verify(c2["ob"], root=self.root)[0]
        self.assertFalse([f for f in body["failures"] if f"OMQ-14 source {s}" in f and lab in f])


class ContractRevision3Stage2AB(unittest.TestCase):
    """Review fix (rev-3 review blocker): frozen §11.4 permits Stage 2A/2B instead of a whole-file reading for terms of
    <= 2 code points; only a FOUND needs the whole file. The OMQ-14 escape requires the S-id in the escalation detail."""
    REV3 = {"contract": {"revision": 3}}

    def setUp(self):
        self.root = tempfile.mkdtemp(prefix="s5v-rev3ab-", dir="/tmp")

    def tearDown(self):
        shutil.rmtree(self.root, ignore_errors=True)

    def cov_fails(self, ctx):
        shutil.rmtree(self.root); os.makedirs(self.root)
        write(self.root, [ctx], header_extra=self.REV3)
        return [f for f in v.verify(ctx["ob"], root=self.root)[0]["failures"] if f.startswith("READ-COVERAGE")]

    def test_stage2ab_non_found_needs_no_pages_but_found_does(self):
        ctx = nonhub_ctx("PB03")

        def other_reasons(lab):                  # files needed for a reason other than a stage-2 disposition
            ob = next(x for x in ctx["objs"] if x["working_label"] == lab)
            s = {a["supplied_by"]["source_id"] for a in ob["absences"].values()
                 if isinstance(a, dict) and a.get("resolution") == "FOUND" and isinstance(a.get("supplied_by"), dict)}
            s |= {v.birth_basis(b) for b in ob["births"].values()} - {None}
            s |= {tp["source_id"] for tp in ob["timeline"] if tp.get("change_vs_previous") in v.READ_CHANGE_POINTS}
            s |= {json.loads(r)["source_id"] for r in ctx["slices"][lab]["bundle"]["rows_verbatim"]
                  if set(json.loads(r).get("types") or []) & v.OMQ14_ROW_TYPES}
            return s
        only_disp = [f for f in self.cov_fails(ctx) if "(stage-2 disposition)" in f
                     and f.split(" ")[2] not in other_reasons(f.split(" ")[1].rstrip(":"))]
        if not only_disp:
            self.skipTest("fixture has no file needed only by a stage-2 disposition")
        lab, sid = only_disp[0].split(" ")[1].rstrip(":"), only_disp[0].split(" ")[2]
        for mode, expect in (("FALSE-HIT", False), ("FOUND", True)):
            c2 = nonhub_ctx("PB03")
            for o in c2["objs"]:
                if o["working_label"] == lab:
                    for d in o["stage2_dispositions"]:
                        if d["source_id"] == sid:
                            d["method"] = "STAGE-2A-2B"
                            d["by_dimension"] = {k: ("FALSE-HIT" if mode == "FALSE-HIT" else "FOUND") for k in d["by_dimension"]}
            got = [f for f in self.cov_fails(c2) if f.startswith(f"READ-COVERAGE {lab}: {sid} ")]
            self.assertEqual(bool(got), expect, (mode, got))
            if expect:
                self.assertIn("FOUND from a STAGE-2A-2B file", got[0])

    def test_escape_needs_sid_in_detail(self):
        ctx = nonhub_ctx("PB03")
        omq = [f for f in self.cov_fails(ctx) if "OMQ-14 source" in f]
        if not omq:
            self.skipTest("fixture has no OMQ-14 obligation")
        lab, sid = omq[0].split(" ")[1].rstrip(":"), omq[0].split(" ")[4]
        for where, escaped in (("field", False), ("detail", True)):
            c2 = nonhub_ctx("PB03")
            for o in c2["objs"]:
                if o["working_label"] == lab:
                    e = {"field": "timeline", "reason": "CONTRACT-DEVIATION", "detail": "whole-file reading not completed"}
                    e[where] = f"{e[where]} {sid}"
                    o["escalations"] = o["escalations"] + [e]
            still = [f for f in self.cov_fails(c2) if f"OMQ-14 source {sid}" in f and f" {lab}:" in f]
            self.assertEqual(not still, escaped, (where, still))


class SchemaV4NotConsumed(unittest.TestCase):
    """Schema revision 4 delta (G-LOG-0052): NOT-CONSUMED-ESCALATED is accepted only for revision >= 4, only with every
    dimension ESCALATED and a CONTRACT-DEVIATION escalation naming the S-id in its detail; never under revision 3."""

    def setUp(self):
        self.root = tempfile.mkdtemp(prefix="s5v-v4-", dir="/tmp")

    def tearDown(self):
        shutil.rmtree(self.root, ignore_errors=True)

    def run_case(self, rev, all_escalated=True, named=True):
        shutil.rmtree(self.root); os.makedirs(self.root)
        ctx = nonhub_ctx("PB03")
        o = next(x for x in ctx["objs"] if x["stage2_dispositions"])
        d = o["stage2_dispositions"][0]
        d["method"] = "NOT-CONSUMED-ESCALATED"
        d["by_dimension"] = {k: ("ESCALATED" if all_escalated else "FOUND") for k in d["by_dimension"]}
        if named:
            o["escalations"] = o["escalations"] + [{"field": "stage2_dispositions", "reason": "CONTRACT-DEVIATION",
                                                    "detail": f"{d['source_id']} not consumed (capacity)"}]
        write(self.root, [ctx], header_extra={"contract": {"revision": rev}})
        f = v.verify(ctx["ob"], root=self.root)[0]["failures"]
        return [x for x in f if "method" in x or "NOT-CONSUMED" in x]

    def test_gating_and_conditions(self):
        self.assertTrue(any("method 'NOT-CONSUMED-ESCALATED'" in x for x in self.run_case(3)))   # not in revision 3
        self.assertEqual(self.run_case(4), [])
        self.assertTrue(any("NOT-CONSUMED-ESCALATED" in x for x in self.run_case(4, all_escalated=False)))
        self.assertTrue(any("NOT-CONSUMED-ESCALATED" in x for x in self.run_case(4, named=False)))


if __name__ == "__main__":
    unittest.main()
