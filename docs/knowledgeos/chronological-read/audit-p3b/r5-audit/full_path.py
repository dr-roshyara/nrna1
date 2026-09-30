#!/usr/bin/env python3
"""End-to-end false-PASS probe through the FULL production verifier p3b_s5_verify.verify (revision-5 gate).
Fixture: the historical converted S4 R2.2 batch OB9002 (T.nonhub_ctx, committed ledger material, read-only), rewritten
to the revision-5 layout (run OB9002-R5, header contract revision 5, R5-BATCH.json, byte-mode read log).
SAFETY: c.dio.discovery_resolver is replaced by a FAKE resolver serving SYNTHETIC bytes for every S-id (no corpus
content is read); hold-out sets are the synthetic testlib sets; qs.count_holdout_sids is stubbed to 0 (the synthetic
reads are not hold-out reads). All files are written under a temp dir in /tmp. No repo write.
Run: cd <CR>/scripts/tests && PYTHONPATH=.:.. python3 -B /tmp/r5-audit/full_path.py
"""
import copy
import hashlib
import json
import os
import shutil
import sys
import tempfile

sys.dont_write_bytecode = True
import test_p3b_s5_verify as T          # noqa: E402
import p3b_s5_ops_testlib as testlib    # noqa: E402

v, c = T.v, T.c
r5v_spec = __import__("importlib.util").util.spec_from_file_location("r5x", os.path.join(T.SCRIPTS, "p3b_s5_r5.py"))
r5 = __import__("importlib.util").util.module_from_spec(r5v_spec)
r5v_spec.loader.exec_module(r5)
PB = os.environ.get("PB", "PB05")
OB = T.PB_TO_OB[PB]
R2, R5 = f"{OB}-R2", f"{OB}-R5"


def synth(sid, n=3000):
    unit = f"synthetic {sid} ä€ line\n".encode("utf-8")
    return (unit * (n // len(unit) + 1))[:n].decode("utf-8", errors="ignore").encode("utf-8")


class Fake:
    def __init__(self):
        self.rows = {}

    def read_many(self, sids):
        return {s: synth(s) for s in sids}


def byte_log(run, label, sid, step=7, utc="2026-10-01T09:00:00Z"):
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


def lint(o, reg):
    return {"result": "PASS", "records_sha256": hashlib.sha256(json.dumps([o] + reg, sort_keys=True,
                                                                          ensure_ascii=False).encode()).hexdigest()}


def build():
    ctx = T.nonhub_ctx(PB)
    ctx = json.loads(json.dumps(ctx, ensure_ascii=False).replace(f'"{R2}"', f'"{R5}"').replace(f"{R2}:", f"{R5}:"))
    ctx["run"] = R5
    import re as _re
    for o in ctx["objs"]:
        rows_ = {json.loads(r)["source_id"] for r in ctx["slices"][o["working_label"]]["bundle"]["rows_verbatim"]}
        for k, val in list(o["births"].items()):
            if any(x not in rows_ for x in _re.findall(r"\bS\d{4}\b", str(val))):
                o["births"][k] = "NOT-EVIDENCED-IN-CAPTURE"      # baseline sanitation: S2 (historical S4 output)
    unrange = lambda m: " and ".join(_re.findall(r"S\d{4}", m.group(0)))      # baseline sanitation: S3 ranges
    ctx["objs"] = [json.loads(r5.SID_RANGE.sub(unrange, json.dumps(o, ensure_ascii=False))) for o in ctx["objs"]]
    ctx["reg"] = [json.loads(r5.SID_RANGE.sub(unrange, json.dumps(r, ensure_ascii=False))) for r in ctx["reg"]]
    log, labels_desc = [], {}
    tools = [e for e in ctx["log"] if e.get("tool")]
    for i, lab in enumerate(ctx["labels"], 1):
        sl = ctx["slices"][lab]
        rows = {json.loads(r)["source_id"] for r in (sl.get("bundle") or {}).get("rows_verbatim") or []}
        req = rows | set(sl.get("stage2_files") or [])
        run = f"{OB}-R5-L{i:02d}"
        o = next(x for x in ctx["objs"] if x["working_label"] == lab)
        for ed in o.get("dependency_edges") or []:
            ed["edge_class"] = "R1-STRUCTURAL"
            ed["source_id"] = sorted(rows)[0]
        need = r5.required_pair_checks(sl.get("p3a_pairs") or [], lab, req)
        recs = []
        for sid in sorted(req):
            log.extend(byte_log(run, lab, sid, 7 if sid in (sl.get("stage2_files") or []) else 1))
            recs.append({"source_id": sid, "reading_state": "WHOLE-FILE", "ack_tokens": toks(sid),
                         "pair_evidence_checks": [{"pair_id": p, "supports": "YES", "quote": "q"}
                                                  for p, s in sorted(need) if s == sid]})
        log.extend(dict(e, run_id=run) for e in tools if e.get("working_label") == lab)
        reg = [r for r in ctx["reg"] if r["working_label"] == lab]
        labels_desc[lab] = {"path": "SINGLE", "files": sorted(req), "row_sources": sorted(rows),
                            "sizes": {s: len(synth(s)) for s in req},
                            "runs": {"single": run, "units": [], "synthesis": None}, "records": {run: recs},
                            "lint": lint(o, reg)}
    ctx["log"] = log
    batch = {"batch_id": OB, "revision": 5, "labels": labels_desc}
    return ctx, batch


def verify(ctx, batch):
    root = tempfile.mkdtemp(prefix="r5full-", dir="/tmp/r5-audit")
    try:
        T.write(root, [ctx], header_extra={"contract": {"revision": 5}},
                entry_hook=lambda ob, e: e.update(run_id=R5))
        with open(os.path.join(root, "ledger-p3b-r2", R5, "R5-BATCH.json"), "w", encoding="utf-8") as f:
            json.dump(batch, f)
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


def show(tag, body, expect):
    obs = body["result"]
    print(f"{tag:5s} expected {expect} observed {obs} R5-gate {body['gates'].get('R5')}"
          f"{'  <-- FALSE PASS' if expect == 'FAIL' and obs == 'PASS' else ''}")
    for x in body["failures"][:12]:
        print("      -", x[:170])
    for x in body["alerts"][:3]:
        print("      alert:", x[:150])


if __name__ == "__main__":
    ctx0, b0 = build()
    show("BASE", verify(ctx0, b0), "PASS")
    labs = ctx0["labels"]
    print("labels:", len(labs))

    # pick a label with a WHOLE-FILE row source to abuse
    lab = labs[0]
    L = b0["labels"][lab]
    o_idx = next(i for i, x in enumerate(ctx0["objs"]) if x["working_label"] == lab)

    # FP-1 (J): synthesis-type claim with no record provenance: a definition-change point grounded as INFERENCE
    ctx, b = copy.deepcopy(ctx0), copy.deepcopy(b0)
    o = ctx["objs"][o_idx]
    tl = o["timeline"]
    tl[-1]["change_vs_previous"] = "CHANGES-DEFINITION"
    tl[-1]["states"] = {"epistemic_class": "INFERENCE", "step": "synthesis inference; no record states this"}
    b["labels"][lab]["lint"] = lint(o, [r for r in ctx["reg"] if r["working_label"] == lab])
    print("FP-1 point:", tl[-1]["source_id"], "(records carry only reading_state/ack_tokens/pair checks)")
    show("FP-1", verify(ctx, b), "FAIL")

    # FP-2 (D1): a timeline point on a file whose record says READ-PARTIAL, change value CONTRADICTS
    done = False
    for lab2 in labs:
        oi = next(i for i, x in enumerate(ctx0["objs"]) if x["working_label"] == lab2)
        for ti, tp in enumerate(ctx0["objs"][oi]["timeline"]):
            ctx, b = copy.deepcopy(ctx0), copy.deepcopy(b0)
            o = ctx["objs"][oi]
            sid = tp["source_id"]
            o["timeline"][ti]["change_vs_previous"] = "CONTRADICTS"
            for r in b["labels"][lab2]["records"][b["labels"][lab2]["runs"]["single"]]:
                if r["source_id"] == sid:
                    r["reading_state"] = "READ-PARTIAL"
            b["labels"][lab2]["lint"] = lint(o, [r for r in ctx["reg"] if r["working_label"] == lab2])
            body = verify(ctx, b)
            if body["result"] == "PASS":
                print(f"FP-2 target: label #{labs.index(lab2)} timeline point {ti} ({sid}) -> CONTRADICTS, record READ-PARTIAL")
                show("FP-2", body, "FAIL")
                done = True
                break
        if done:
            break
    if not done:
        print("FP-2: no target found in this fixture")

    # FP-4 (E2): an undeclared R5-grammar run reads files: alert only
    ctx, b = copy.deepcopy(ctx0), copy.deepcopy(b0)
    ctx["log"] += byte_log(f"{OB}-R5-L{len(labs)+1:02d}S", lab, L["files"][0], 10)
    show("FP-4", verify(ctx, b), "FAIL")

    # FP-5 (F1): S1 YES with a fabricated quote
    anyp = [(l_, r_) for l_, d in b0["labels"].items() for r_ in d["records"][d["runs"]["single"]] if r_["pair_evidence_checks"]]
    print("labels with S1 checks:", len({x[0] for x in anyp}))
    if anyp:
        ctx, b = copy.deepcopy(ctx0), copy.deepcopy(b0)
        for l_, d in b["labels"].items():
            for rr in d["records"][d["runs"]["single"]]:
                for ck in rr["pair_evidence_checks"]:
                    ck["quote"] = "FABRICATED - NOT IN FILE"
        show("FP-5", verify(ctx, b), "FAIL")

    # FP-6 COMPOSITE: make the label with the most files DECOMPOSED by DECLARED sizes (real synthetic content is ~3 KB
    # per file), R19 stages with a timezone trick (synthesis 4 h BEFORE unit validation), unit reads logged AFTER synthesis
    # dispatch, one synthesis read disguised under a unit run id, and a synthesis-only definition-change claim.
    big = max(labs, key=lambda x: len(b0["labels"][x]["files"]))
    ctx, b = copy.deepcopy(ctx0), copy.deepcopy(b0)
    Lb = b["labels"][big]
    idx = labs.index(big) + 1
    files, rows = Lb["files"], Lb["row_sources"]
    sizes = {s_: 300_000 for s_ in files}
    order = sorted(rows)
    units = r5.partition(set(files), set(rows), sizes, lambda x: order.index(x))
    if len(files) * 300_000 <= 600_000:
        print("FP-6: label too small for a DECOMPOSED declaration; skipped")
    else:
        uruns = [f"{OB}-R5-L{idx:02d}U{i:02d}" for i in range(1, len(units) + 1)]
        ctx["log"] = [e for e in ctx["log"] if e.get("run_id") != Lb["runs"]["single"] or e.get("tool")]
        for e in ctx["log"]:
            if e.get("tool") and e.get("run_id") == Lb["runs"]["single"]:
                e["run_id"] = next(u for u, uf in zip(uruns, units) if set(e.get("source_ids") or []) <= set(uf))
        old = Lb["records"][Lb["runs"]["single"]]
        recs = {}
        sl = ctx["slices"][big]
        for u, uf in zip(uruns, units):
            recs[u] = [r for r in old if r["source_id"] in uf]
            for s_ in uf:
                ctx["log"] += byte_log(u, big, s_, 7 if s_ in (sl.get("stage2_files") or []) else 1,
                                       utc="2026-10-01T12:30:00Z")          # AFTER synthesis dispatch
        ctx["log"] += byte_log(uruns[0], big, units[0][0], 1, utc="2026-10-01T11:30:00Z")   # synthesis read, unit run id
        Lb.update(path="DECOMPOSED", sizes=sizes, packing_order=order, units=units, records=recs,
                  runs={"single": None, "units": uruns, "synthesis": f"{OB}-R5-L{idx:02d}S"},
                  stages={"units_validated_utc": "2026-10-01T10:00:00Z",
                          "synthesis_dispatched_utc": "2026-10-01T11:00:00+05:00"})
        o = next(x for x in ctx["objs"] if x["working_label"] == big)
        o["timeline"][-1]["change_vs_previous"] = "CHANGES-DEFINITION"
        o["timeline"][-1]["states"] = {"epistemic_class": "INFERENCE", "step": "synthesis-only claim"}
        Lb["lint"] = lint(o, [r for r in ctx["reg"] if r["working_label"] == big])
        print(f"FP-6 label #{labs.index(big)}: {len(files)} files, real bytes {sum(len(synth(s_)) for s_ in files)}, "
              f"declared {sum(sizes.values())}; units {len(units)}")
        show("FP-6", verify(ctx, b), "FAIL")
