#!/usr/bin/env python3
"""P3b S5 decomposition pilot (NON-PRODUCTION; human ruling "authorize decomposition pilot", G-LOG-0052).

Tests whether a label's mandatory whole-file reading can be split into deterministic, independently verifiable reading
units whose union proves complete coverage (Property A), and supplies the material for the research-comparability test
(Property B, judged by an independent auditor). Writes only under `pilot-s5-decomp/`; never touches production state,
manifest, slices, ledgers or batches. Pre-registration: `audit-p3b/20260925_1325_s5-decomposition-pilot-preregistration.md`.

  p3b_s5_pilot.py plan       write pilot-s5-decomp/PILOT-PLAN.json (refuses to overwrite)
  p3b_s5_pilot.py check      Property A report -> pilot-s5-decomp/PILOT-COVERAGE.json
  p3b_s5_pilot.py validate   run the production verifier on a relabelled temporary copy of each synthesized object

Mandatory reading set R(L) (superset of the frozen OMQ-14 set, so nothing is thinned): the label's `stage2_files`
(none for a hub) UNION every source of the label's rows, from the frozen revision-3 slice. Partition: first-fit
decreasing by size (ties by S-id) into units of at most BUDGET bytes; a file is never split; a file larger than BUDGET
forms its own unit (flagged). Unit run ids PX0004-U01.., synthesis PX0004-S01 (control) / -S02 (target), probe
PX0027-U01. Coverage: a file is read only if pages 1..N are logged with hashes re-verified (p3b_s5_verify.page_coverage).
"""
import collections
import json
import os
import shutil
import sys
import tempfile

_HERE = os.path.dirname(os.path.abspath(__file__))
if _HERE not in sys.path:
    sys.path.insert(0, _HERE)
import p3b_s5_common as C  # noqa: E402
import p3b_s5_prepare as P  # noqa: E402
import p3b_s5_verify as V  # noqa: E402

ROOT = "pilot-s5-decomp"
PLAN = f"{ROOT}/PILOT-PLAN.json"
COVERAGE = f"{ROOT}/PILOT-COVERAGE.json"
PREREG = "audit-p3b/20260925_1325_s5-decomposition-pilot-preregistration.md"
S5_BATCH, BATCH = "OB0004", "PX0004"
SLICES = "_batch_input_r2/s5/rev3/OB0004"
CONTROL, TARGET = "knowledgeos-architecture-constitution-v01", "step-verify-programme"
BUDGET = 600_000
PROBE = {"batch": "PX0027", "s5_batch": "OB0027", "label": "b-prime-relocation-decision", "source_id": "S2276",
         "run_id": "PX0027-U01"}
CONTINUATION_UNIT = "PX0004-U02"                  # the first target unit is executed in two sessions (pre-registered)


def slice_of(label):
    with open(os.path.join(C.CR, SLICES, f"{label}.json"), encoding="utf-8") as f:
        return json.load(f)


def required_set(label):
    sl = slice_of(label)
    s2 = set() if sl.get("hub") else set(sl.get("stage2_files") or [])
    rows = {json.loads(r)["source_id"] for r in sl["bundle"]["rows_verbatim"]}
    return sorted(s2 | rows), sorted(s2), sorted(rows)


def partition(files, sizes, budget=BUDGET):
    """Deterministic first-fit decreasing; files never split; returns [[sid, ...], ...] with each unit sorted by S-id."""
    units = []
    for s in sorted(files, key=lambda x: (-sizes[x], x)):
        for u in units:
            if sum(sizes[y] for y in u) + sizes[s] <= budget:
                u.append(s)
                break
        else:
            units.append([s])
    return [sorted(u) for u in units]


def build_plan(sizes):
    units, n = [], 0
    for label in (CONTROL, TARGET):
        req, s2, rows = required_set(label)
        for u in partition(req, sizes):
            n += 1
            units.append({"run_id": f"{BATCH}-U{n:02d}", "label": label, "files": u, "bytes": sum(sizes[s] for s in u),
                          "oversize": any(sizes[s] > BUDGET for s in u)})
    labels = {}
    for label in (CONTROL, TARGET):
        req, s2, rows = required_set(label)
        labels[label] = {"required": req, "stage2_files": s2, "row_sources": rows, "required_bytes": sum(sizes[s] for s in req),
                         "units": [u["run_id"] for u in units if u["label"] == label],
                         "synthesis_run": f"{BATCH}-S01" if label == CONTROL else f"{BATCH}-S02"}
    return {"artifact": PLAN, "status": "NON-PRODUCTION PILOT", "s5_batch": S5_BATCH, "batch": BATCH, "budget_bytes": BUDGET,
            "slices": SLICES, "labels": labels, "units": units, "continuation_unit": CONTINUATION_UNIT,
            "probe": dict(PROBE, bytes=sizes[PROBE["source_id"]]), "preregistration": PREREG,
            "partition_rule": "first-fit decreasing by size, ties by S-id; files never split; unit files sorted by S-id"}


def read_log(run):
    p = os.path.join(C.CR, ROOT, run, "READ-LOG.jsonl")
    if not os.path.exists(p):
        return []
    with open(p, encoding="utf-8") as f:
        return [json.loads(l) for l in f if l.strip()]


def coverage_report(plan):
    out = {"labels": {}, "units": {}, "probe": None}
    all_pages = collections.Counter()
    for u in plan["units"]:
        log = [e for e in read_log(u["run_id"]) if e.get("run_id") == u["run_id"] and e.get("batch_id") == BATCH]
        cov = V.page_coverage(log)
        pages = collections.Counter((e["page"]["source_id"], e["page"]["page"]) for e in log if e.get("page") and not e.get("refused"))
        all_pages.update(pages)
        done = [s for s in u["files"] if cov.get(s, {}).get("complete")]
        entry = {"label": u["label"], "files": len(u["files"]), "bytes": u["bytes"], "complete_files": len(done),
                 "missing_files": sorted(set(u["files"]) - set(done)), "hash_failures": sum(v["bad"] for v in cov.values()),
                 "page_reads": sum(pages.values()), "duplicate_page_reads": sum(n - 1 for n in pages.values() if n > 1),
                 "files_outside_unit": sorted({s for s, _ in pages} - set(u["files"])),
                 "pages_expected": sum(cov[s]["n"] for s in u["files"] if s in cov),
                 "sessions": sorted({e.get("session") for e in log if e.get("page")} - {None})}
        if u["run_id"] == plan["continuation_unit"]:
            by_s = collections.defaultdict(set)
            for e in log:
                if e.get("page") and not e.get("refused"):
                    by_s[e.get("session")].add((e["page"]["source_id"], e["page"]["page"]))
            ss = sorted(k for k in by_s if k is not None)
            cross = set.intersection(*[by_s[k] for k in ss]) if len(ss) > 1 else set()
            entry["continuation"] = {"sessions": ss, "pages_per_session": {str(k): len(by_s[k]) for k in ss},
                                     "cross_session_duplicate_pages": len(cross),
                                     "complete_after_resume": not entry["missing_files"]}
        out["units"][u["run_id"]] = entry
    for label, L in plan["labels"].items():
        done = set()
        for r in L["units"]:
            log = [e for e in read_log(r) if e.get("run_id") == r]
            done |= {s for s, v in V.page_coverage(log).items() if v["complete"]}
        req = set(L["required"])
        out["labels"][label] = {"required_files": len(req), "required_bytes": L["required_bytes"], "units": len(L["units"]),
                                "complete_files": len(req & done), "missing_files": sorted(req - done),
                                "coverage_complete": req <= done}
    p = plan["probe"]
    plog = [e for e in read_log(p["run_id"]) if e.get("run_id") == p["run_id"]]
    pcov = V.page_coverage(plog).get(p["source_id"])
    out["probe"] = {"source_id": p["source_id"], "bytes": p["bytes"], "pages_expected": pcov["n"] if pcov else None,
                    "pages_verified": len(pcov["have"]) if pcov else 0, "complete": bool(pcov and pcov["complete"]),
                    "hash_failures": pcov["bad"] if pcov else 0} if plog else {"source_id": p["source_id"], "executed": False}
    return out


def provenance_rows(plan):
    """S5 batch -> label -> required file -> partition -> page -> reading event (utc, page hash)."""
    rows = []
    for u in plan["units"]:
        for e in read_log(u["run_id"]):
            pg = e.get("page")
            if pg and not e.get("refused") and pg["source_id"] in u["files"]:
                rows.append({"s5_batch": S5_BATCH, "label": u["label"], "source_id": pg["source_id"], "partition": u["run_id"],
                             "page": pg["page"], "n_pages": pg["n_pages"], "utc": e.get("utc"), "page_sha256": pg["page_sha256"],
                             "session": e.get("session")})
    return rows


def validate(plan):
    """Production verifier on a TEMPORARY relabelled copy: the synthesized label object is placed as a one-label batch
    OB0004 run OB0004-R2 in a temp root (schema revision 4 header), with the union of the unit and synthesis read logs.
    Identifiers are rewritten only inside the temp copy; the pilot artifacts are untouched."""
    res = {}
    for label, L in plan["labels"].items():
        srun = L["synthesis_run"]
        sdir = os.path.join(C.CR, ROOT, srun)
        if not os.path.exists(os.path.join(sdir, "objects.jsonl")):
            res[label] = {"executed": False}
            continue
        tmp = tempfile.mkdtemp(prefix="s5-pilot-validate-", dir="/tmp")
        try:
            sl_text = open(os.path.join(C.CR, SLICES, f"{label}.json"), encoding="utf-8").read()
            sl = json.loads(sl_text)
            os.makedirs(os.path.join(tmp, SLICES))
            with open(os.path.join(tmp, SLICES, f"{label}.json"), "w", encoding="utf-8") as f:
                f.write(sl_text)
            body_sl = sl_text[:-1] if sl_text.endswith("\n") else sl_text
            man = [json.loads(l) for l in open(os.path.join(C.CR, P.MANIFEST), encoding="utf-8") if l.strip()]
            e0 = next(b for b in man[1:] if b["batch_id"] == S5_BATCH)
            entry = dict(e0, labels=[label], slice_sha256={label: C.sha256_bytes(body_sl.encode("utf-8"))},
                         in_checklist={label: e0["in_checklist"][label]}, weights={label: e0["weights"][label]},
                         checklist=int(bool(e0["in_checklist"][label])))
            body = C.canon(entry) + "\n"
            hdr = dict(man[0]["header"], output_sha256=C.sha256_bytes(body.encode()), pilot_validation_copy=True,
                       contract=dict(man[0]["header"]["contract"], revision=4))
            with open(os.path.join(tmp, P.MANIFEST), "w", encoding="utf-8") as f:
                f.write(C.canon({"header": hdr}) + "\n" + body)
            odir = os.path.join(tmp, "ledger-p3b-r2", f"{S5_BATCH}-R2")
            os.makedirs(odir)
            contract = entry["contract_sha256"]

            def relabel(rec):
                t = json.dumps(rec, ensure_ascii=False).replace(f'"{srun}:{BATCH}:', f'"{S5_BATCH}-R2:{S5_BATCH}:')
                r = json.loads(t)
                r.update(run_id=f"{S5_BATCH}-R2", batch_id=S5_BATCH, contract_sha256=contract)
                return r
            for n in ("objects.jsonl", "register.jsonl", "p1-gap-capture.jsonl"):
                p = os.path.join(sdir, n)
                recs = [json.loads(l) for l in open(p, encoding="utf-8") if l.strip()] if os.path.exists(p) else []
                with open(os.path.join(odir, n), "w", encoding="utf-8") as f:
                    f.write("".join(C.canon(relabel(r)) + "\n" for r in recs))
            logs = []
            for r in L["units"] + [srun]:
                logs += [dict(e, run_id=f"{S5_BATCH}-R2", batch_id=S5_BATCH, working_label=label) for e in read_log(r)]
            with open(os.path.join(odir, "READ-LOG.jsonl"), "w", encoding="utf-8") as f:
                f.write("".join(json.dumps(e, sort_keys=True, ensure_ascii=False) + "\n" for e in logs))
            body_v, _ = V.verify(S5_BATCH, root=tmp, run=f"{S5_BATCH}-R2")
            res[label] = {"result": body_v["result"], "failures": body_v["failures"], "alerts": body_v.get("alerts", []),
                          "counts": body_v.get("counts")}
        finally:
            shutil.rmtree(tmp, ignore_errors=True)
    return res


def main(argv=None):
    a = argv if argv is not None else sys.argv[1:]
    if not a or a[0] not in ("plan", "check", "validate"):
        print(__doc__, file=sys.stderr)
        return 2
    C.assert_sealed()
    os.makedirs(os.path.join(C.CR, ROOT), exist_ok=True)
    if a[0] == "plan":
        out = os.path.join(C.CR, PLAN)
        if os.path.exists(out):
            print(f"REFUSED: {PLAN} exists (the partition is frozen once)", file=sys.stderr)
            return 2
        body = build_plan(P.blob_sizes())
        txt = C.canon(body)
        hdr = C.header(__file__, [os.path.join(SLICES, f"{x}.json") for x in (CONTROL, TARGET)], {"budget": BUDGET}, txt)
        with open(out, "w", encoding="utf-8") as f:
            f.write(C.canon({"header": hdr, "body": body}) + "\n")
        print(json.dumps({"units": [(u["run_id"], u["label"][:20], len(u["files"]), u["bytes"]) for u in body["units"]],
                          "output_sha256": hdr["output_sha256"]}))
    else:
        plan = json.load(open(os.path.join(C.CR, PLAN), encoding="utf-8"))["body"]
        if a[0] == "check":
            body = {"coverage": coverage_report(plan), "provenance_rows": provenance_rows(plan)}
            txt = C.canon(body)
            hdr = C.header(__file__, [PLAN], {}, txt)
            with open(os.path.join(C.CR, COVERAGE), "w", encoding="utf-8") as f:
                f.write(C.canon({"header": hdr, "body": body}) + "\n")
            print(json.dumps(body["coverage"], indent=1))
        else:
            print(json.dumps(validate(plan), indent=1))
    C.assert_sealed()
    return 0


if __name__ == "__main__":
    sys.exit(main())
