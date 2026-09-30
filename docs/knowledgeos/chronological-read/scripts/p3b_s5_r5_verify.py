#!/usr/bin/env python3
"""P3b S5 contract revision 5: batch-level verification (called by p3b_s5_verify.verify ONLY when the manifest's
contract revision >= 5; revisions < 5 never reach this module). G-LOG-0082; not activated.

A revision-5 batch is verified through its ASSEMBLED batch directory `ledger-p3b-r2/<batch>-R5/` (runbook step 13):
objects/register/p1-gap of each label's final run, the union of the batch's revision-5 read logs (entries keep their
own run ids), and `R5-BATCH.json`:
  {"batch_id", "revision": 5, "plan_sha256",
   "labels": {label: {"path": SINGLE|DECOMPOSED|EMPTY, "files": [...], "row_sources": [...], "sizes": {sid: bytes},
                      "packing_order": [...row sources; PACKING ORDER — NO CHRONOLOGICAL MEANING...],
                      "units": [[...], ...], "runs": {"single": run | null, "units": [run, ...], "synthesis": run | null},
                      "records": {run: [file-reading records]},
                      "lint": {"result": "PASS", "records_sha256": ...},
                      "stages": {"units_validated_utc": ..., "synthesis_dispatched_utc": ...}}}}
Rules (items of the addendum): path/plan consistency and two-path structure (1–3), sizing present for every file (4),
packing order is a pure engineering order (5), byte mode only (8), acknowledgement tokens (9), reading_state bound to
byte coverage (10), r5_checks: edges (11), S1 (13), S2 (14), S3 (15), EMPTY (16); read-log exposure levels (12).
Failures are tagged "R5 ". Content bytes for byte coverage come only through the seal-aware resolver.
"""
import collections
import hashlib
import importlib.util
import json
import os
import re

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("p3b_s5_r5", os.path.join(_HERE, "p3b_s5_r5.py"))
r5 = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(r5)

LABEL_RUN = re.compile(r"OB\d{4}-R5-L\d{2}$")
UNIT_RUN = re.compile(r"OB\d{4}-R5-L\d{2}U\d{2}$")
SYN_RUN = re.compile(r"OB\d{4}-R5-L\d{2}S$")


def load_batch(odir):
    p = os.path.join(odir, "R5-BATCH.json")
    if not os.path.exists(p):
        return None
    with open(p, encoding="utf-8") as f:
        return json.load(f)


def runs_of(bdesc):
    out = set()
    for L in (bdesc.get("labels") or {}).values():
        R = L.get("runs") or {}
        out |= {x for x in [R.get("single"), R.get("synthesis")] + list(R.get("units") or []) if x}
    return out


def mode_failures(entries):
    """Item 8: every page read in a revision-5 run is byte mode; mode comes from the log metadata, never from content."""
    bad = [e for e in entries if isinstance(e.get("page"), dict) and not e.get("refused") and
           e["page"].get("mode") != r5.READER_MODE]
    return [f"R5 {len(bad)} character-mode page read(s) in revision-5 runs (byte mode is mandatory)"] if bad else []


def coverage_by_label(entries, resolver_factory):
    """Byte coverage per label (only that label's runs count), content via the seal-aware resolver."""
    by_lab = collections.defaultdict(list)
    for e in entries:
        pg = e.get("page") if isinstance(e.get("page"), dict) else None
        if pg and pg.get("mode") == r5.READER_MODE and not e.get("refused"):
            by_lab[e.get("working_label")].append(e)
    sids = sorted({e["page"]["source_id"] for es in by_lab.values() for e in es})
    contents = resolver_factory().read_many(sids) if sids else {}
    return {lab: r5.byte_page_coverage(es, contents) for lab, es in by_lab.items()}


def structure_failures(lab, L, batch):
    """Items 1–5: path/plan consistency and the two-path structure."""
    F = []
    files, rows, sizes = set(L.get("files") or []), set(L.get("row_sources") or []), L.get("sizes") or {}
    miss = sorted(files - set(sizes))
    if miss:
        F.append(f"R5 {lab}: unsized required file(s) {miss[:3]} (all blob types must be sized)")
        return F
    req = sum(sizes[s] for s in files)
    want = r5.dispatch_path(len(files), req)
    path = L.get("path")
    if path != want:
        F.append(f"R5 {lab}: declared path {path} ≠ plan path {want} (R(L) = {req} bytes)")
    R = L.get("runs") or {}
    units, syn, single = list(R.get("units") or []), R.get("synthesis"), R.get("single")
    if path == "SINGLE":
        if units or syn:
            F.append(f"R5 {lab}: SINGLE path with decomposition runs")
        if not single or not LABEL_RUN.match(single) or not single.startswith(batch):
            F.append(f"R5 {lab}: SINGLE path without a valid single-context run ({single!r})")
    elif path == "DECOMPOSED":
        if single:
            F.append(f"R5 {lab}: DECOMPOSED path with a single-context run")
        if not units or not all(UNIT_RUN.match(u) and u.startswith(batch) for u in units):
            F.append(f"R5 {lab}: DECOMPOSED path without valid unit runs")
        if not syn or not SYN_RUN.match(syn) or not syn.startswith(batch):
            F.append(f"R5 {lab}: DECOMPOSED path without a valid synthesis run")
        order = list(L.get("packing_order") or [])
        if sorted(order) != sorted(rows):
            F.append(f"R5 {lab}: packing order ≠ row sources")
        else:
            pos = {s: i for i, s in enumerate(order)}
            expect = r5.partition(files, rows, sizes, lambda s: pos[s])
            if sorted(map(sorted, L.get("units") or [])) != sorted(map(sorted, expect)):
                F.append(f"R5 {lab}: units ≠ row-first + FFD fill partition of the plan")
            if len(units) != len(L.get("units") or []):
                F.append(f"R5 {lab}: {len(units)} unit run(s) for {len(L.get('units') or [])} planned unit(s)")
        st = L.get("stages") or {}
        if not (st.get("units_validated_utc") and st.get("synthesis_dispatched_utc") and
                st["units_validated_utc"] < st["synthesis_dispatched_utc"]):
            F.append(f"R5 {lab}: synthesis not strictly after unit validation (R19 ordering)")
    elif path == "EMPTY":
        if files or single or units or syn:
            F.append(f"R5 {lab}: EMPTY path with required files or runs")
    else:
        F.append(f"R5 {lab}: unknown path {path!r}")
    return F


def records_and_states(L):
    """Records of the label's reading runs (single, or units) and reading_state per file (records carry it)."""
    R = L.get("runs") or {}
    reading = [R.get("single")] if R.get("single") else list(R.get("units") or [])
    recs = [r for run in reading for r in (L.get("records") or {}).get(run, [])]
    return recs, {r.get("source_id"): r.get("reading_state") for r in recs}


def label_failures(lab, L, obj, register, cov, batch, quarantine_hits=None, sets=None):
    F = []
    path = L.get("path")
    rows, files = set(L.get("row_sources") or []), set(L.get("files") or [])
    recs, states = records_and_states(L)
    if path in ("SINGLE", "DECOMPOSED"):
        seen = collections.Counter(r.get("source_id") for r in recs)
        if set(seen) != files or any(n != 1 for n in seen.values()):
            F.append(f"R5 {lab}: file-reading records ≠ exactly one per required file")
        if path == "DECOMPOSED":
            R = L.get("runs") or {}
            for run, ufiles in zip(R.get("units") or [], L.get("units") or []):
                got = {r.get("source_id") for r in (L.get("records") or {}).get(run, [])}
                if got != set(ufiles):
                    F.append(f"R5 {lab}: unit {run} records ≠ its planned files")
        for sid, st in states.items():                          # item 10 bound to byte coverage (item 8)
            c = cov.get(sid)
            if st == "WHOLE-FILE" and not (c and c["complete"]):
                F.append(f"R5 {lab}: {sid} reading_state WHOLE-FILE without complete byte-mode coverage")
        toks = {r.get("source_id"): r.get("ack_tokens") or [] for r in recs}
        whole = [s for s, st in states.items() if st == "WHOLE-FILE"]
        F += [f"R5 {lab}: {s}: {why}" for s, why in r5.ack_violations(toks, cov, whole)]
        lint = L.get("lint") or {}
        final = [obj] + list(register)
        if lint.get("result") != "PASS" or lint.get("records_sha256") != \
                hashlib.sha256(json.dumps(final, sort_keys=True, ensure_ascii=False).encode()).hexdigest():
            F.append(f"R5 {lab}: S3 pre-submit lint report missing, not PASS, or not for the submitted records")
    run_final = (L.get("runs") or {}).get("synthesis") or (L.get("runs") or {}).get("single") or f"{batch}-R5"
    F += [f"R5 {lab}: {x}" for x in r5.r5_checks(obj, register, recs, path, rows, files, L.get("pairs") or [], states,
                                                 run_final, batch, quarantine_hits, sets)]
    return F


def exposure_failures(entries, bdesc):
    """Item 12, levels 3/4 from the read log: a successful read of a file outside the reading run's permitted set.
    (Hold-out files are refused by the resolver: a successful hold-out read cannot occur.)"""
    permitted = {}
    for L in (bdesc.get("labels") or {}).values():
        R = L.get("runs") or {}
        if R.get("single"):
            permitted[R["single"]] = set(L.get("files") or [])
        for run, uf in zip(R.get("units") or [], L.get("units") or []):
            permitted[run] = set(uf)
        if R.get("synthesis"):
            permitted[R["synthesis"]] = set()                       # records-only synthesis: no reads
    F = []
    for e in entries:
        lvl, why = r5.log_level(e, permitted.get(e.get("run_id"), set()), set())
        if why:
            F.append(f"R5 run {e.get('run_id')}: {why} ({len(e.get('source_ids') or [])} file(s))")
    return F


def slice_required(sl):
    """R(L) from the hash-verified slice (authoritative): stage-2 files ∪ all row sources; hubs have no stage 2."""
    rows = {json.loads(r)["source_id"] for r in ((sl.get("bundle") or {}).get("rows_verbatim") or [])}
    s2 = set() if sl.get("hub") else set(sl.get("stage2_files") or [])
    return s2 | rows, rows


def verify_r5(batch, odir, objs, reg, rlog, resolver_factory, quarantine_hits=None, sets=None, slices=None):
    """Returns (failures, blog, cov_by_label). blog = the batch's revision-5 log entries. slices: {label: slice} from
    the verifier (hash-verified); R(L), row sources and P3a pairs are taken from the slice, and R5-BATCH.json is
    cross-checked against it (the batch file is never trusted for them)."""
    bdesc = load_batch(odir)
    if bdesc is None or bdesc.get("revision") != 5 or bdesc.get("batch_id") != batch:
        return [f"R5 R5-BATCH.json missing or not for {batch} revision 5"], [], {}
    runs = runs_of(bdesc)
    blog = [e for e in rlog if e.get("batch_id") == batch and e.get("run_id") in runs]
    F = mode_failures(blog)
    cov = coverage_by_label([e for e in blog if not e.get("refused")], resolver_factory)
    F += exposure_failures(blog, bdesc)
    by_obj = {o.get("working_label"): o for o in objs}
    reg_by = collections.defaultdict(list)
    for r in reg:
        reg_by[r.get("working_label")].append(r)
    slices = slices or {}
    if sorted(bdesc.get("labels") or {}) != sorted(slices):
        F.append("R5 R5-BATCH.json labels ≠ the batch's slices")
    for lab, L in sorted((bdesc.get("labels") or {}).items()):
        sl = slices.get(lab)
        if sl is None:
            F.append(f"R5 {lab}: no slice")
            continue
        req, rows = slice_required(sl)
        if set(L.get("files") or []) != req or set(L.get("row_sources") or []) != rows:
            F.append(f"R5 {lab}: R5-BATCH.json files/row sources ≠ the slice's R(L)")
            L = dict(L, files=sorted(req), row_sources=sorted(rows))
        L = dict(L, pairs=sl.get("p3a_pairs") or [])
        F += structure_failures(lab, L, batch)
        if lab not in by_obj:
            F.append(f"R5 {lab}: no object in the assembled batch")
            continue
        F += label_failures(lab, L, by_obj[lab], reg_by.get(lab, []), cov.get(lab, {}), batch, quarantine_hits, sets)
    return F, blog, cov
