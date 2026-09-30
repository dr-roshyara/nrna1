#!/usr/bin/env python3
"""Revision-6 verifier tests (G-LOG-0083): a positive control through the FULL production verifier and every
revision-5 audit attack as a permanent regression (each former FALSE PASS must now FAIL).

Fixture: the historical converted S4 R2.2 batch (T.nonhub_ctx, committed material, read-only) rewritten to the
revision-6 layout. SAFETY: the resolver is a FAKE serving SYNTHETIC bytes (a marker phrase per S-id; one label is made
large so that it is genuinely DECOMPOSED by its real byte sizes); hold-out sets are the synthetic testlib sets;
qs.count_holdout_sids is stubbed to 0. No corpus content, no reader invocation, no repository write (temp dirs only).
  cd scripts/tests && PYTHONPATH=.:.. python3 -m unittest test_p3b_s5_r6_verify
"""
import copy
import hashlib
import importlib.util
import json
import os
import re
import shutil
import sys
import tempfile
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(HERE)
sys.path.insert(0, HERE)
sys.path.insert(0, SCRIPTS)
import test_p3b_s5_verify as T                      # noqa: E402
import p3b_s5_ops_testlib as testlib                # noqa: E402
v, c, prep = T.v, T.c, T.prep
_s = importlib.util.spec_from_file_location("p3b_s5_r6", os.path.join(SCRIPTS, "p3b_s5_r6.py"))
r6 = importlib.util.module_from_spec(_s)
_s.loader.exec_module(r6)
r5 = r6.r5
PB = "PB05"
OB = T.PB_TO_OB[PB]
R2, R6 = f"{OB}-R2", f"{OB}-R6"
BIG = set()                                          # S-ids served large (the DECOMPOSED label); set by build()


def marker(sid):
    return f"FACT-MARKER {sid} states the evidence"


def synth(sid):
    unit = f"synthetic {sid} ä€ {marker(sid)}\n".encode("utf-8")
    n = 250_000 if sid in BIG else 3_000
    return (unit * (n // len(unit) + 1))[:n].decode("utf-8", errors="ignore").encode("utf-8")


class Fake:
    def __init__(self):
        self.rows = {}

    def read_many(self, sids):
        return {s: synth(s) for s in sids}


def byte_log(run, label, sid, utc="2026-10-01T09:00:00Z", step=7):
    b = synth(sid)
    out = []
    for k in range(1, len(r5.byte_pages(b)) + 1):
        _, rec = r5.byte_page_record(sid, b, k, hashlib.sha256(b).hexdigest())
        out.append({"utc": utc, "run_id": run, "batch_id": OB, "working_label": label, "step": step,
                    "source_ids": [sid], "refused": False, "bytes": {sid: rec["byte_end"] - rec["byte_start"]},
                    "sha256": {}, "stdout": "pipe", "page": rec, "mode": r5.READER_MODE})
    return out


def toks(sid):
    b = synth(sid)
    return [r5.ack_token(hashlib.sha256(b[a:e]).hexdigest()) for a, e in r5.byte_pages(b)]


def dump_jl(path, rows):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "w", encoding="utf-8") as f:
        f.write("".join(json.dumps(r, sort_keys=True, ensure_ascii=False) + "\n" for r in rows))


def dump_js(path, obj):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "w", encoding="utf-8") as f:
        json.dump(obj, f, sort_keys=True, ensure_ascii=False)


def fsha(path):
    with open(path, "rb") as f:
        return hashlib.sha256(f.read()).hexdigest()


def fact_for(spec, sid, n):
    f = {"fact_id": f"F{n}", "kind": spec["kind"], "quote": marker(sid)}
    f.update(spec["fields"])
    if spec["kind"] == "TIMELINE":
        f["change_candidate"] = "EXTENDS"
    return f


def build():
    """A VALID revision-6 batch: returns a state dict {ctx, plan, runs{run: {log, records, extra files}}, prov}."""
    global BIG
    ctx = T.nonhub_ctx(PB)
    ctx = json.loads(json.dumps(ctx, ensure_ascii=False).replace(f'"{R2}"', f'"{R6}"').replace(f"{R2}:", f"{R6}:"))
    ctx["run"] = R6
    labels = ctx["labels"]
    # make the label with the most files genuinely DECOMPOSED by real byte size
    sizes_lab = {lab: len(r6.slice_required(ctx["slices"][lab])[0]) for lab in labels}
    big_lab = max(labels, key=lambda l: sizes_lab[l])
    BIG = set(r6.slice_required(ctx["slices"][big_lab])[0])
    contents = Fake().read_many(sorted({s for l in labels for s in r6.slice_required(ctx["slices"][l])[0]}))
    plan = r6.plan_derive(OB, labels, ctx["slices"], contents, c.files_meta())
    state = {"ctx": ctx, "plan": plan, "runs": {}, "prov": [], "big_lab": big_lab}
    for lab in labels:
        L = plan["labels"][lab]
        sl = ctx["slices"][lab]
        o = next(x for x in ctx["objs"] if x["working_label"] == lab)
        rows = set(L["row_sources"])
        files = set(L["files"])
        # baseline sanitation of the historical S4 object (not an attack): S2, S3, edge classes, out-of-R(L) claims
        for k, val in list(o["births"].items()):
            if any(x not in rows for x in r5.SID.findall(str(val))):
                o["births"][k] = "NOT-EVIDENCED-IN-CAPTURE"
        for ed in o.get("dependency_edges") or []:
            ed["edge_class"] = "R1-STRUCTURAL"
            ed["source_id"] = sorted(rows)[0]
        o["timeline"] = [p for p in o.get("timeline") or [] if p.get("source_id") in files]
        unr = lambda m: " and ".join(re.findall(r"S\d{4}", m.group(0)))
        for key in ("objs", "reg", "gap"):
            ctx[key] = [json.loads(r6.RANGE6.sub(unr, json.dumps(x, ensure_ascii=False))) if x.get("working_label") == lab
                        else x for x in ctx[key]]
        o = next(x for x in ctx["objs"] if x["working_label"] == lab)
        # who reads which file
        reading = [r for r, d in L["runs"].items() if d["role"] in ("UNIT", "SINGLE")]
        owner_of = {s: r for r in reading for s in L["runs"][r]["files"]}
        recs = {r: {} for r in reading}
        for r in reading:
            for s in L["runs"][r]["files"]:
                recs[r][s] = {"source_id": s, "reading_state": "WHOLE-FILE", "ack_tokens": toks(s), "facts": [],
                              "pair_evidence_checks": []}
        claim_map, n = {}, 0
        for cid, spec in r6.enumerate_claims(o):
            s = spec["source"]
            if s not in owner_of:
                continue
            n += 1
            f = fact_for(spec, s, n)
            recs[owner_of[s]][s]["facts"].append(f)
            claim_map[cid] = [{"run": owner_of[s], "source_id": s, "fact_id": f["fact_id"]}]
        need = set()
        for p in sl.get("p3a_pairs") or []:
            if lab in (p.get("a"), p.get("b")):
                cited = set(r5.SID.findall(json.dumps(p.get("what_says_this"), ensure_ascii=False) + json.dumps(p.get("basis"))))
                need |= {(p["pair_id"], s) for s in cited & files}
        for pid, s in sorted(need):
            recs[owner_of[s]][s]["pair_evidence_checks"].append({"pair_id": pid, "supports": "YES", "quote": marker(s)})
        for r in reading:
            state["runs"][r] = {"log": [e for s in L["runs"][r]["files"] for e in byte_log(r, lab, s)],
                                "records": [recs[r][s] for s in sorted(recs[r])]}
        final = next((r for r, d in L["runs"].items() if d["role"] in ("SYNTHESIS", "SINGLE")), None)
        if final:
            reg = [x for x in ctx["reg"] if x.get("working_label") == lab]
            gap = [x for x in ctx["gap"] if x.get("working_label") == lab]
            state["runs"].setdefault(final, {"log": [], "records": None})
            state["runs"][final].update({"claim": claim_map, "lint_records": [o] + reg + gap})
            if L["path"] == "DECOMPOSED":
                state["prov"] += [{"stage": "units-validated", "label": lab, "utc": "2026-10-01T10:00:00Z"},
                                  {"stage": "synthesis-dispatched", "label": lab, "utc": "2026-10-01T11:00:00Z"}]
            state["runs"][final]["produced_utc"] = "2026-10-01T12:00:00Z"
            state["runs"][final]["label"] = lab
    return state


def verify(state, mutate=None, manifest_rev=6):
    st = copy.deepcopy(state)
    if mutate:
        mutate(st)
    ctx, plan = st["ctx"], st["plan"]
    root = tempfile.mkdtemp(prefix="r6full-")
    try:
        psha = st.get("plan_sha_override") or r6.sha(r6.canon_bytes(plan))
        ctx["log"] = []
        T.write(root, [ctx], header_extra={"contract": {"revision": manifest_rev}},
                entry_hook=lambda ob, e: e.update(run_id=R6, r6_plan_sha256=psha))
        with open(os.path.join(root, prep.SLICE_ROOT, f"{OB}.R6-PLAN.json"), "wb") as f:
            f.write(r6.canon_bytes(st.get("plan_file", plan)))
        led = os.path.join(root, "ledger-p3b-r2")
        rec_hash = {}
        for run, d in st["runs"].items():
            if d.get("log") is not None and (d["log"] or d.get("records") is not None):
                dump_jl(os.path.join(led, run, "READ-LOG.jsonl"), d["log"])
            if d.get("records") is not None:
                p = os.path.join(led, run, "file-reading-records.jsonl")
                dump_jl(p, d["records"])
                rec_hash[run] = fsha(p)
        for run, d in st["runs"].items():
            if "claim" not in d:
                continue
            lab = d["label"]
            reading = [r for r, x in plan["labels"][lab]["runs"].items() if x["role"] in ("UNIT", "SINGLE")]
            dump_js(os.path.join(led, run, "claim-evidence.json"), d["claim"])
            lr = [next(x for x in ctx["objs"] if x["working_label"] == lab)] + \
                 [x for x in ctx["reg"] if x.get("working_label") == lab] + [x for x in ctx["gap"] if x.get("working_label") == lab]
            dump_js(os.path.join(led, run, "S3-LINT.json"),
                    d.get("lint_override") or {"result": "PASS", "records_sha256": r6.sha(r6.canon_bytes(lr))})
            dump_js(os.path.join(led, run, "RUN-MANIFEST.json"),
                    {"run_id": run, "produced_utc": d["produced_utc"],
                     "input_record_hashes": d.get("inputs_override") or {r: rec_hash.get(r) for r in reading}})
        prov = []
        for p in st["prov"]:
            p = dict(p)
            if p["stage"] == "units-validated" and "record_hashes" not in p:
                lab = p["label"]
                p["record_hashes"] = {r: rec_hash.get(r) for r, x in plan["labels"][lab]["runs"].items() if x["role"] == "UNIT"}
            prov.append(p)
        dump_jl(os.path.join(led, R6, "R6-PROVENANCE.jsonl"), prov)
        for extra in st.get("extra_dirs", []):
            dump_jl(os.path.join(led, extra[0], "READ-LOG.jsonl"), extra[1])
        orig_res, orig_q = c.dio.discovery_resolver, v.qs.count_holdout_sids
        c.dio.discovery_resolver = Fake
        v.qs.count_holdout_sids = lambda sids: 0
        try:
            with testlib.fake_holdout():
                body, _ = v.verify(OB, root=root)
        finally:
            c.dio.discovery_resolver, v.qs.count_holdout_sids = orig_res, orig_q
        return body
    finally:
        shutil.rmtree(root, ignore_errors=True)


STATE = None


def base():
    global STATE
    if STATE is None:
        STATE = build()
    return STATE


def first(st, path):
    return next(l for l, L in st["plan"]["labels"].items() if L["path"] == path)


class R6Case(unittest.TestCase):
    def fails(self, mutate, needle):
        body = verify(base(), mutate)
        self.assertEqual(body["result"], "FAIL", "former false PASS still passes")
        self.assertTrue(any(needle in x for x in body["failures"]), [x[:140] for x in body["failures"][:10]])
        return body


class PositiveControl(unittest.TestCase):
    def test_valid_revision_6_batch_passes_r6_but_revision_6_is_superseded(self):
        """G-LOG-0088 (addendum v2.3 §10): revision 6 is superseded by revision 7. The R6 machinery still accepts the
        valid batch (gate R6 = PASS: nothing in R6 is weakened); the batch fails ONLY on the supersession rule."""
        body = verify(base())
        self.assertEqual(body["failures"], ["R7-U contract revision 6 is superseded by revision 7 (G-LOG-0088)"])
        self.assertEqual(body["result"], "FAIL")
        self.assertEqual(body["gates"].get("R6"), "PASS")
        paths = {L["path"] for L in base()["plan"]["labels"].values()}
        self.assertIn("DECOMPOSED", paths)
        self.assertIn("SINGLE", paths)

    def test_deterministic(self):
        a, b = verify(base()), verify(base())
        self.assertEqual(json.dumps(a["failures"]), json.dumps(b["failures"]))


# ---------------------------------------------------------------- B: plan integrity (R5-05)
class PlanIntegrity(R6Case):
    def test_B1_declared_size_differs_from_bytes(self):
        def m(st):
            p = copy.deepcopy(st["plan"])
            lab = first(st, "SINGLE")
            s = p["labels"][lab]["files"][0]
            p["labels"][lab]["sizes"][s] = 702_000
            st["plan_file"] = p
            st["plan_sha_override"] = r6.sha(r6.canon_bytes(p))
        self.fails(m, "frozen plan ≠ independent derivation")

    def test_B1b_declared_decomposed_for_small_label(self):
        def m(st):
            p = copy.deepcopy(st["plan"])
            lab = first(st, "SINGLE")
            p["labels"][lab]["path"] = "DECOMPOSED"
            st["plan_file"] = p
            st["plan_sha_override"] = r6.sha(r6.canon_bytes(p))
        self.fails(m, "frozen plan ≠ independent derivation")

    def test_B2_permuted_packing_order(self):
        def m(st):
            p = copy.deepcopy(st["plan"])
            lab = st["big_lab"]
            p["labels"][lab]["packing_order"] = list(reversed(p["labels"][lab]["packing_order"]))
            st["plan_file"] = p
            st["plan_sha_override"] = r6.sha(r6.canon_bytes(p))
        self.fails(m, "packing_order")

    def test_B3_arbitrary_plan_hash(self):
        def m(st):
            st["plan_sha_override"] = "0" * 64
        self.fails(m, "plan hash ≠ the manifest-bound")


# ---------------------------------------------------------------- C: temporal (R5-02)
class Temporal(R6Case):
    def stage(self, st, name, utc):
        lab = st["big_lab"]
        for p in st["prov"]:
            if p["label"] == lab and p["stage"] == name:
                p["utc"] = utc

    def test_C1_timezone_offset(self):
        self.fails(lambda st: self.stage(st, "synthesis-dispatched", "2026-10-01T11:00:00+05:00"),
                   "synthesis not strictly after unit validation")

    def test_C2_non_iso(self):
        self.fails(lambda st: self.stage(st, "synthesis-dispatched", "later"), "not strict RFC 3339")

    def test_C3_fractional_seconds_order_still_enforced(self):
        def m(st):
            self.stage(st, "units-validated", "2026-10-01T10:00:00.500000Z")
            self.stage(st, "synthesis-dispatched", "2026-10-01T10:00:00.100000Z")
        self.fails(m, "synthesis not strictly after unit validation")

    def test_C4_unit_reads_after_validation(self):
        def m(st):
            for run, d in st["runs"].items():
                if run.startswith(f"{OB}-R6-") and "U" in run.split("-")[-1]:
                    for e in d["log"]:
                        e["utc"] = "2026-10-01T11:30:00Z"
        self.fails(m, "unit reads logged after units-validated")

    def test_C_record_changed_after_validation(self):
        def m(st):
            lab = st["big_lab"]
            for p in st["prov"]:
                if p["label"] == lab and p["stage"] == "units-validated":
                    p["record_hashes"] = {"x": "y"}
        self.fails(m, "unit record files changed after validation")


# ---------------------------------------------------------------- D: reading state / ownership (R5-04, R5-06, R5-11/12/14)
class Reading(R6Case):
    def any_run(self, st, role):
        lab = first(st, "SINGLE") if role == "SINGLE" else st["big_lab"]
        return lab, next(r for r, d in st["plan"]["labels"][lab]["runs"].items() if d["role"] == role)

    def test_D1_partial_file_as_contradicts_point(self):
        def m(st):
            lab, run = self.any_run(st, "SINGLE")
            o = next(x for x in st["ctx"]["objs"] if x["working_label"] == lab)
            tp = o["timeline"][0]
            tp["change_vs_previous"] = "CONTRADICTS"
            for r in st["runs"][run]["records"]:
                if r["source_id"] == tp["source_id"]:
                    r["reading_state"] = "READ-PARTIAL"
        self.fails(m, "point on non-WHOLE-FILE source")

    def test_D1_all_non_change_exempt_classes_only(self):
        for cls in ("NARROWS", "RETRACTS"):
            self.assertNotIn(cls, r6.WHOLE_NOT_REQUIRED)
        self.assertNotIn("SOME-FUTURE-CLASS", r6.WHOLE_NOT_REQUIRED)

    def test_D2_label_spoof_in_log(self):
        def m(st):
            lab, run = self.any_run(st, "SINGLE")
            st["runs"][run]["log"][0]["working_label"] = "some-other-label"
        self.fails(m, "working_label ≠ the run's plan owner")

    def test_D2_foreign_run_reads_file_for_another_label(self):
        def m(st):
            lab, run = self.any_run(st, "SINGLE")
            other = next(r for r, d in st["plan"]["labels"][st["big_lab"]]["runs"].items() if d["role"] == "UNIT")
            s = st["plan"]["labels"][lab]["files"][0]
            if s not in st["plan"]["labels"][st["big_lab"]]["runs"][other]["files"]:
                st["runs"][other]["log"] += byte_log(other, st["big_lab"], s)
        self.fails(m, "read of a file not permitted for this run")

    def test_D3_extra_or_duplicate_ack(self):
        def m(st):
            lab, run = self.any_run(st, "SINGLE")
            st["runs"][run]["records"][0]["ack_tokens"] = st["runs"][run]["records"][0]["ack_tokens"] * 2
        self.fails(m, "acknowledgement tokens ≠ the page tokens")

    def test_D4_entry_without_page(self):
        def m(st):
            lab, run = self.any_run(st, "SINGLE")
            e = dict(st["runs"][run]["log"][0])
            e.pop("page")
            st["runs"][run]["log"].append(e)
        self.fails(m, "without a byte-mode page record")

    def test_D5_not_consumed_without_contract_deviation(self):
        def m(st):
            lab, run = self.any_run(st, "SINGLE")
            r = st["runs"][run]["records"][-1]
            r["reading_state"] = "NOT-CONSUMED"
            st["runs"][run]["log"] = [e for e in st["runs"][run]["log"] if e["page"]["source_id"] != r["source_id"]]
        self.fails(m, "without a CONTRACT-DEVIATION escalation naming it")

    def test_whole_file_without_coverage(self):
        def m(st):
            lab, run = self.any_run(st, "SINGLE")
            s = st["runs"][run]["records"][0]["source_id"]
            st["runs"][run]["log"] = [e for e in st["runs"][run]["log"] if e["page"]["source_id"] != s]
        self.fails(m, "WHOLE-FILE without complete byte coverage")

    def test_character_mode_page(self):
        def m(st):
            lab, run = self.any_run(st, "SINGLE")
            st["runs"][run]["log"][0]["page"]["mode"] = "chars-r3"
        self.fails(m, "character-mode page read")


# ---------------------------------------------------------------- E: execution (R5-03, R5-13)
class Execution(R6Case):
    def test_E1_synthesis_reads(self):
        def m(st):
            lab = st["big_lab"]
            syn = next(r for r, d in st["plan"]["labels"][lab]["runs"].items() if d["role"] == "SYNTHESIS")
            st["runs"][syn]["log"] = byte_log(syn, lab, st["plan"]["labels"][lab]["files"][0])
        self.fails(m, "records-only synthesis performed a read")

    def test_E2_undeclared_run_directory(self):
        def m(st):
            lab = first(st, "SINGLE")
            st["extra_dirs"] = [(f"{OB}-R6-L09S", byte_log(f"{OB}-R6-L09S", lab, st["plan"]["labels"][lab]["files"][0]))]
        self.fails(m, "not a planned run")

    def test_E3_foreign_batch_entry(self):
        def m(st):
            run = next(r for r in st["runs"] if st["runs"][r]["log"])
            st["runs"][run]["log"][0]["batch_id"] = "OB0001"
        self.fails(m, "names another batch or run")

    def test_E4_refused_attempt(self):
        def m(st):
            run = next(r for r in st["runs"] if st["runs"][r]["log"])
            e = dict(st["runs"][run]["log"][0], refused=True)
            e.pop("page")
            st["runs"][run]["log"].append(e)
        self.fails(m, "refused read (runbook stop condition 16)")

    def test_withdrawn_revision_5_run_directory(self):
        def m(st):
            st["extra_dirs"] = [(f"{OB}-R5-L01", [])]
        self.fails(m, "revision-5 runs are withdrawn")

    def test_reader_plan_check(self):
        st = base()
        lab = first(st, "SINGLE")
        run = next(iter(st["plan"]["labels"][lab]["runs"]))
        s = st["plan"]["labels"][lab]["files"][0]
        self.assertEqual(r6.reader_plan_check(st["plan"], run, OB, s, lab), (True, None))
        self.assertFalse(r6.reader_plan_check(st["plan"], run, OB, s, "other")[0])
        self.assertFalse(r6.reader_plan_check(st["plan"], f"{OB}-R6-L09", OB, s, lab)[0])
        self.assertFalse(r6.reader_plan_check(st["plan"], run, OB, "S0000", lab)[0])
        syn = next(r for r, d in st["plan"]["labels"][st["big_lab"]]["runs"].items() if d["role"] == "SYNTHESIS")
        self.assertFalse(r6.reader_plan_check(st["plan"], syn, OB, s, st["big_lab"])[0])

    def test_scan_all_reader_calls(self):
        call = {"name": "Bash", "input": {"command":
                "python3 p3b_read_source.py --run R --batch B --page 1 S0001 && python3 p3b_read_source.py --run X --batch B --page 1 S0002"}}
        self.assertEqual(r6.scan_level_v6(call, "R", "B", set(), set())[1], "READER-FOREIGN-RUN-OR-BATCH")
        self.assertEqual(r6.scan_level_v6({"name": "Bash", "input": {"command": "python3 -c \"open('x')\""}},
                                          "R", "B", set(), set())[1], "ACCESS-OUTSIDE-READER")


# ---------------------------------------------------------------- F: S1 (R5-07, R5-10)
class S1(R6Case):
    def with_check(self, st):
        for run, d in st["runs"].items():
            for r in d.get("records") or []:
                if r["pair_evidence_checks"]:
                    return run, r
        return None, None

    def test_F1_fabricated_yes_quote(self):
        if self.with_check(base())[0] is None:
            self.skipTest("fixture has no S1 pair")

        def m(st):
            run, r = self.with_check(st)
            r["pair_evidence_checks"][0]["quote"] = "FABRICATED - NOT IN FILE"
        self.fails(m, "quote not found in the authoritative bytes")

    def test_F3_no_satisfied_by_other_pair_text(self):
        if self.with_check(base())[0] is None:
            self.skipTest("fixture has no S1 pair")

        def m(st):
            run, r = self.with_check(st)
            ck = r["pair_evidence_checks"][0]
            ck["supports"] = "NO"
            lab = st["plan"]["labels"]
            label = next(l for l, L in lab.items() if run in L["runs"])
            o = next(x for x in st["ctx"]["objs"] if x["working_label"] == label)
            o["escalations"].append({"field": "semantic_status", "reason": "OTHER",
                                     "detail": f"VERDICT-EVIDENCE-CONFLICT: {ck['pair_id']}9"})
        self.fails(m, "NO without")

    def test_F4_wrong_escalation_reason(self):
        if self.with_check(base())[0] is None:
            self.skipTest("fixture has no S1 pair")

        def m(st):
            run, r = self.with_check(st)
            ck = r["pair_evidence_checks"][0]
            ck["supports"] = "NO"
            label = next(l for l, L in st["plan"]["labels"].items() if run in L["runs"])
            o = next(x for x in st["ctx"]["objs"] if x["working_label"] == label)
            o["escalations"].append({"field": "x", "reason": "LOAD", "detail": f"VERDICT-EVIDENCE-CONFLICT: {ck['pair_id']}"})
        self.fails(m, "NO without an escalation")

    def test_exact_tokens_unit(self):
        self.assertEqual(r6.PAIR_TOKEN.findall("RP0751 and RP07519"), ["RP0751", "RP07519"])


# ---------------------------------------------------------------- H: S3 and EMPTY (R5-08, R5-09)
class S3AndEmpty(R6Case):
    def test_H1_range_variants(self):
        for t in ("S9511 through S9513", "S9511…S9513", "S9511−S9513", "S9511‒S9513", "S9511―S9513", "S9511-13",
                  "S9511 bis S9513", "S9511~S9513", "S9511 until S9513", "S9511 to S9513"):
            self.assertTrue(r6.s3_lint_v6([{"x": t}], "l", "R", "B"), t)
        self.assertEqual(r6.s3_lint_v6([{"x": "Step 253–254 and S9511, S9513"}], "l", "R", "B"), [])
        self.assertEqual(r6.s3_lint_v6([{"x": "S0957/S2361 (an enumeration, not a range)"}], "l", "R", "B"), [])

    def test_H1_pointer_variants(self):
        for t in ("See the Register record 3", "cf. register", "research-register"):
            self.assertTrue(r6.s3_lint_v6([{"working_label": "l", "timeline": [], "note": t}], "l", "R", "B"), t)

    def test_H1_range_in_object_fails_full_path(self):
        def m(st):
            lab = first(st, "SINGLE")
            o = next(x for x in st["ctx"]["objs"] if x["working_label"] == lab)
            o["timeline"][0]["note"] = "sources S9511 through S9513"
        self.fails(m, "S-id range")

    def test_H2_empty_with_content(self):
        o = {"working_label": "e", "births": {"lexical": "NOT-EVIDENCED-IN-CAPTURE"}, "absences": {},
             "stage2_dispositions": [], "escalations": [{"reason": "OTHER", "detail": "EMPTY-REQUIRED-SET (R17)"}],
             "timeline": [{"source_id": "S9999", "change_vs_previous": "FIRST"}],
             "dependency_edges": [{"target_label": "x", "source_id": "S9999"}]}
        v6 = r6.empty_violations_v6(o)
        self.assertTrue(any("timeline" in x for x in v6))
        self.assertTrue(any("dependency edges" in x for x in v6))
        self.assertTrue(r6.claim_evidence_violations(o, {}, {}, {}, {}, set()))
        self.assertTrue(r5.edge_class_violations(o, set()))
        self.assertTrue(r6.s3_lint_v6([dict(o, note="S9511 through S9513")], "e", "R", "B"))


# ---------------------------------------------------------------- P: provenance and composite (R5-01, FP-*)
class Provenance(R6Case):
    def test_FP1_synthesis_only_claim_without_fact(self):
        def m(st):
            lab = st["big_lab"]
            o = next(x for x in st["ctx"]["objs"] if x["working_label"] == lab)
            new = sorted(st["plan"]["labels"][lab]["row_sources"])[-1]
            o["timeline"].append({"source_id": new, "historical_position": "x", "date_basis": "MTIME", "order": "ORDERED",
                                  "states": {"epistemic_class": "INFERENCE", "step": "synthesis only"},
                                  "change_vs_previous": "CHANGES-DEFINITION", "date_applies_to_file": "NOT-ASSESSED"})
            syn = next(r for r, d in st["plan"]["labels"][lab]["runs"].items() if d["role"] == "SYNTHESIS")
            st["runs"][syn]["claim"].pop(f"timeline:{new}", None)
        self.fails(m, "has no valid evidence path")

    def test_claim_cannot_cite_fact_of_wrong_kind_or_unknown_fact(self):
        def m(st):
            lab = first(st, "SINGLE")
            run = next(iter(st["plan"]["labels"][lab]["runs"]))
            for cid in list(st["runs"][run]["claim"]):
                st["runs"][run]["claim"][cid] = [{"run": run, "source_id": "S0000", "fact_id": "F999"}]
        self.fails(m, "has no valid evidence path")

    def test_fact_quote_must_be_in_bytes(self):
        def m(st):
            lab = first(st, "SINGLE")
            run = next(iter(st["plan"]["labels"][lab]["runs"]))
            for r in st["runs"][run]["records"]:
                for f in r["facts"]:
                    f["quote"] = "INVENTED QUOTE"
        self.fails(m, "quote not found in the authoritative source bytes")

    def test_FP6_composite(self):
        """The audit's FP-6, in revision-6 form: declared oversize plan, timezone R19 trick, unit reads after
        synthesis dispatch, a synthesis read disguised under a unit id, and a synthesis-only change claim."""
        def m(st):
            lab = st["big_lab"]
            L = st["plan"]["labels"][lab]
            p = copy.deepcopy(st["plan"])
            p["labels"][lab]["sizes"] = {s: 300_000 for s in L["files"]}
            st["plan_file"] = p
            st["plan_sha_override"] = r6.sha(r6.canon_bytes(p))
            for q in st["prov"]:
                if q["label"] == lab and q["stage"] == "synthesis-dispatched":
                    q["utc"] = "2026-10-01T11:00:00+05:00"
            units = [r for r, d in L["runs"].items() if d["role"] == "UNIT"]
            for r in units:
                for e in st["runs"][r]["log"]:
                    e["utc"] = "2026-10-01T11:30:00Z"
            st["runs"][units[0]]["log"] += byte_log(units[0], lab, st["runs"][units[-1]]["records"][0]["source_id"],
                                                    "2026-10-01T11:45:00Z")
            o = next(x for x in st["ctx"]["objs"] if x["working_label"] == lab)
            o["timeline"][-1]["change_vs_previous"] = "CHANGES-DEFINITION"
            syn = next(r for r, d in L["runs"].items() if d["role"] == "SYNTHESIS")
            st["runs"][syn]["claim"] = {}
        body = verify(base(), m)
        self.assertEqual(body["result"], "FAIL")
        tags = {x.split()[1] for x in body["failures"] if x.startswith("R6 ")}
        self.assertTrue({"L", "T", "E", "P"} <= tags, tags)          # several independent invariants each catch it

    def test_pass_requires_every_invariant(self):
        """Removing any single component of the valid batch makes it NOT-PASS."""
        breaks = {
            "P": lambda st: [d.__setitem__("claim", {}) for d in st["runs"].values() if "claim" in d],
            "E": lambda st: st.__setitem__("extra_dirs", [(f"{OB}-R6-L09S", [])]),
            "R": lambda st: [r.__setitem__("ack_tokens", []) for d in st["runs"].values() for r in d.get("records") or []],
            "T": lambda st: [q.__setitem__("utc", "bad") for q in st["prov"]],
            "L": lambda st: st.__setitem__("plan_sha_override", "1" * 64),
            "S": lambda st: [d.__setitem__("lint_override", {"result": "FAIL"}) for d in st["runs"].values() if "claim" in d],
        }
        for tag, m in breaks.items():
            body = verify(base(), m)
            self.assertEqual(body["result"], "FAIL", tag)


# ---------------------------------------------------------------- audit traceability: every preserved attack by name
class AuditTraceability(R6Case):
    """Attacks the audit preserved under names not used above (attacks.py J1/D5b/E6/H3; full_path.py FP-4/FP-5),
    each as a permanent full-path regression in revision-6 form."""

    def test_FP4_reads_under_undeclared_grammar_runs(self):
        def m(st):
            lab = st["big_lab"]
            s = st["plan"]["labels"][lab]["files"][0]
            st["extra_dirs"] = [(f"{OB}-R6-L02U99", byte_log(f"{OB}-R6-L02U99", lab, s)),   # L02U09 is planned here
                                (f"{OB}-R6-L09", byte_log(f"{OB}-R6-L09", lab, s))]
        body = self.fails(m, "not a planned run")
        self.assertEqual(sum("not a planned run" in x for x in body["failures"]), 2)

    def test_FP5_fabricated_yes_quote_full_path(self):
        run, _ = S1.with_check(None, base())
        if run is None:
            self.skipTest("fixture has no S1 pair")

        def m(st):
            r = S1.with_check(None, st)[1]
            for ck in r["pair_evidence_checks"]:
                ck["supports"], ck["quote"] = "YES", "a sentence that is nowhere in the file"
        self.fails(m, "quote not found in the authoritative bytes")

    def test_E6_second_label_uses_first_labels_run(self):
        def m(st):
            singles = [l for l, L in st["plan"]["labels"].items() if L["path"] == "SINGLE"]
            a, b = singles[0], singles[1]
            run_a = next(iter(st["plan"]["labels"][a]["runs"]))
            st["runs"][run_a]["log"] += byte_log(run_a, b, st["plan"]["labels"][b]["files"][0])
        self.fails(m, "working_label ≠ the run's plan owner")

    def test_D5b_unread_unit_row_source_object_silent(self):
        def m(st):
            lab = st["big_lab"]
            unit = next(r for r, d in st["plan"]["labels"][lab]["runs"].items() if d["role"] == "UNIT")
            r = st["runs"][unit]["records"][-1]
            r["reading_state"], r["ack_tokens"] = "NOT-CONSUMED", []
            st["runs"][unit]["log"] = [e for e in st["runs"][unit]["log"] if e["page"]["source_id"] != r["source_id"]]
        self.fails(m, "without a CONTRACT-DEVIATION escalation naming it")

    def test_H3_pointer_in_object_without_layer_a_keys(self):
        stripped = {"working_label": "l", "note": "see the register record 4"}
        self.assertEqual(r6.s3_lint_v6([stripped], "l", "R", "B"), [])          # key sniffing alone misses it
        self.assertTrue(r6.s3_lint_v6([stripped], "l", "R", "B", layer_a={0}))  # the verifier's position binding

        def m(st):
            lab = first(st, "SINGLE")
            o = next(x for x in st["ctx"]["objs"] if x["working_label"] == lab)
            for k in ("timeline", "births", "absences"):
                o.pop(k, None)
            o["note"] = "cf. register"
        self.fails(m, "register id or pointer in layer A")

    def test_J1_decomposed_synthesis_claims_without_record_provenance(self):
        def m(st):
            lab = st["big_lab"]
            o = next(x for x in st["ctx"]["objs"] if x["working_label"] == lab)
            src = sorted(st["plan"]["labels"][lab]["row_sources"])[0]
            o.setdefault("absences", {})["j1-dimension"] = {"resolution": "FOUND",
                                                           "supplied_by": {"source_id": src, "anchor": "L1", "quote": "x"}}
            o.setdefault("dependency_edges", []).append({"target_label": "invented-label", "kind": "USAGE",
                                                         "edge_class": "R2-EVIDENCED", "source_id": src})
        body = self.fails(m, "has no valid evidence path")
        bad = [x for x in body["failures"] if "has no valid evidence path" in x]
        self.assertTrue(any("absence:j1-dimension" in x for x in bad), bad)
        self.assertTrue(any("edge:invented-label" in x for x in bad), bad)


class RevisionBoundary(unittest.TestCase):
    def test_revision_5_manifest_is_withdrawn(self):
        body = verify(base(), manifest_rev=5)
        self.assertEqual(body["result"], "FAIL")
        self.assertTrue(any("revision 5 is withdrawn" in x for x in body["failures"]))

    def test_strict_time_parser(self):
        self.assertIsNotNone(r6.parse_utc("2026-10-01T10:00:00Z"))
        self.assertIsNotNone(r6.parse_utc("2026-10-01T10:00:00.25+02:00"))
        for bad in ("2026-10-01", "2026-10-01 10:00:00", "later", "2026-10-01T10:00:00", None, 5):
            self.assertIsNone(r6.parse_utc(bad), bad)


class Statistics(unittest.TestCase):
    def test_strata_exclusive_exhaustive_and_empty_stratum(self):
        labels = {"h": {"hub": True, "path": "DECOMPOSED"}, "e": {"path": "EMPTY"}, "m": {"path": "DECOMPOSED", "multirow": True},
                  "d": {"path": "DECOMPOSED"}, "s": {"path": "SINGLE"}}
        st = r6.assign_strata(labels)
        self.assertEqual({k: v for k, v in st.items() if v}, {"HUB": ["h"], "EMPTY": ["e"], "MULTIROW": ["m"],
                                                              "DECOMPOSED": ["d"], "SINGLE": ["s"]})
        self.assertEqual(r6.validate_strata(list(labels), st), [])
        self.assertTrue(r6.validate_strata(list(labels), dict(st, SINGLE=["s", "d"])))
        with self.assertRaises(ValueError):
            r6.draw_sample_v6(list(labels), dict(st, SINGLE=["s", "d"]), 1, {})

    def test_zero_bound_edge_cases(self):
        self.assertEqual(r6.zero_bound_v6(0, 10), 1.0)
        self.assertEqual(r6.zero_bound_v6(10, 10), 0.0)
        self.assertAlmostEqual(r6.zero_bound_v6(1), 0.95)

    def test_ht_variance_and_ml_exclusion(self):
        pop = [f"s{i}" for i in range(100)]
        st = {h: [] for h in r6.STRATUM_PRECEDENCE}
        st["SINGLE"] = pop
        smp = r6.draw_sample_v6(pop, st, 7, {"SINGLE": 0.2})
        est = r6.ht_with_variance(smp, {smp["SINGLE"]["labels"][0]})
        self.assertAlmostEqual(est["ht_total"], 5.0)
        self.assertGreater(est["var_total"], 0)
        self.assertLessEqual(est["ci95_share"][0], est["share"])
        with self.assertRaises(ValueError):
            r6.ht_with_variance(smp, {"ml-queue-item"})
        with self.assertRaises(ValueError):
            r6.ht_with_variance(smp, set(), frozen_sha="a", sample_sha="b")

    def test_rate_zero_draws_nothing(self):
        st = {h: [] for h in r6.STRATUM_PRECEDENCE}
        st["SINGLE"] = ["a", "b"]
        self.assertEqual(r6.draw_sample_v6(["a", "b"], st, 1, {"SINGLE": 0})["SINGLE"]["n"], 0)

    def test_freeze_record(self):
        rec = r6.freeze_sample_record("spec", 1, {"SINGLE": {"labels": ["a"]}})
        self.assertIn("python", rec)
        self.assertEqual(len(rec["sample_sha256"]), 64)


if __name__ == "__main__":
    unittest.main()
