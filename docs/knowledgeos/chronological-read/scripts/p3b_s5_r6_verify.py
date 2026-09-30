#!/usr/bin/env python3
"""P3b S5 contract revision 6: batch-level verification (called by p3b_s5_verify.verify ONLY when the manifest's
contract revision >= 6; revision 5 is withdrawn and rejected; revisions < 5 never reach this module). G-LOG-0083.

Every critical state is DERIVED here from authoritative inputs; declarations are compared against it:
  L plan         p3b_s5_r6.plan_derive(manifest label order, hash-verified slices, resolver bytes, 02-FILES) must equal
                 the frozen plan `<slice_root>/<batch>.R6-PLAN.json`, whose sha256 must equal the manifest entry's
                 `r6_plan_sha256`
  E execution    every `ledger-p3b-r2/<batch>-R6-*` directory is a planned run; each planned run's OWN READ-LOG.jsonl
                 is read; entries carry run = directory, batch = batch, working_label = plan owner, byte mode, a page;
                 synthesis runs have zero entries; reads ⊆ permitted files; any refused entry is a stop condition
  R reading      one record per permitted file; WHOLE-FILE ⇔ complete byte coverage in the owning run; ack tokens equal
                 the page tokens; the reading rule over all change classes; CONTRACT-DEVIATION for non-WHOLE files
  P provenance   record facts' quotes occur in the resolver's bytes; every verifier-enumerated claim resolves through
                 claim-evidence.json to a fact of the right kind in a reading run of the label
  T temporal     strict RFC 3339; reads ≤ units-validated < synthesis-dispatched ≤ produced; record files bound by hash
  S synthesis    S1 (exact tokens, verified quotes), S2, S3 (object + register + P1-gap; report bound to the hash),
                 edge classes, EMPTY with full checks
Failures are tagged "R6 ". Content bytes come only through the seal-aware resolver.
"""
import collections
import hashlib
import importlib.util
import json
import os

_HERE = os.path.dirname(os.path.abspath(__file__))
_s = importlib.util.spec_from_file_location("p3b_s5_r6", os.path.join(_HERE, "p3b_s5_r6.py"))
r6 = importlib.util.module_from_spec(_s)
_s.loader.exec_module(r6)
r5 = r6.r5
LEDGER = "ledger-p3b-r2"


def _jl(path):
    if not path or not os.path.exists(path):
        return None
    with open(path, encoding="utf-8") as f:
        return [json.loads(x) for x in f if x.strip()]


def _js(path):
    if not path or not os.path.exists(path):
        return None
    with open(path, encoding="utf-8") as f:
        return json.load(f)


def _fsha(path):
    if not path or not os.path.exists(path):
        return None
    with open(path, "rb") as f:
        return hashlib.sha256(f.read()).hexdigest()


def verify_r6(batch, root, slice_root, entry, labels, slices, objs, reg, gap, resolver_factory, files_meta, logical,
              quarantine_hits=None, sets=None):
    """Returns (failures, blog, cov_by_label). blog = all entries of the planned runs' own logs."""
    F = []
    need = sorted({s for lab in labels if lab in slices for s in r6.slice_required(slices[lab])[0]})
    contents = resolver_factory().read_many(need) if need else {}
    derived = r6.plan_derive(batch, [l for l in labels if l in slices], slices, contents, files_meta)
    declared = _js(logical(root, f"{slice_root}/{batch}.R6-PLAN.json"))
    F += r6.plan_violations(declared, derived, None, entry.get("r6_plan_sha256"))
    owners = r6.run_owner(derived)                                    # authoritative from here on
    # ---------------------------------------------------------------- E: run set and per-run logs
    ldir = os.path.join(root, LEDGER)
    present = sorted(d for d in (os.listdir(ldir) if os.path.isdir(ldir) else []) if d.startswith(f"{batch}-R"))
    for d in present:
        if d.startswith(f"{batch}-R5"):
            F.append(f"R6 E run directory {d}: revision-5 runs are withdrawn")
        elif d.startswith(f"{batch}-R6-") and d not in owners:
            F.append(f"R6 E run directory {d}: not a planned run")
    logs, blog = {}, []
    for run, (lab, role, files) in sorted(owners.items()):
        es = _jl(logical(root, f"{LEDGER}/{run}/READ-LOG.jsonl"))
        if es is None:
            if role != "SYNTHESIS":
                F.append(f"R6 E {run}: planned reading run has no READ-LOG.jsonl")
            es = []
        logs[run] = es
        blog += es
        for e in es:
            if e.get("batch_id") != batch or e.get("run_id") != run:
                F.append(f"R6 E {run}: log entry names another batch or run")
            if e.get("working_label") != lab:
                F.append(f"R6 E {run}: log entry working_label ≠ the run's plan owner")
            if e.get("refused"):
                F.append(f"R6 E {run}: refused read (runbook stop condition 16)")
                continue
            if role == "SYNTHESIS":
                F.append(f"R6 E {run}: records-only synthesis performed a read")
                continue
            pg = e.get("page")
            if not isinstance(pg, dict):
                F.append(f"R6 E {run}: log entry without a byte-mode page record")
                continue
            if pg.get("mode") != r6.READER_MODE:
                F.append(f"R6 E {run}: character-mode page read (byte mode is mandatory)")
            if set(e.get("source_ids") or []) - files:
                F.append(f"R6 E {run}: read of a file not permitted for this run")
    cov_run = {run: r5.byte_page_coverage([e for e in es if not e.get("refused")], contents)
               for run, es in logs.items()}
    for run, cv in cov_run.items():
        bad = sum(v["bad"] for v in cv.values())
        if bad:
            F.append(f"R6 E {run}: {bad} logged page(s) fail hash / span verification")
    # ---------------------------------------------------------------- provenance stages (assembled dir)
    prov = _jl(logical(root, f"{LEDGER}/{batch}-R6/R6-PROVENANCE.jsonl")) or []
    stages = collections.defaultdict(dict)
    for p in prov:
        stages[p.get("label")][p.get("stage")] = p
    by_obj = {o.get("working_label"): o for o in objs}
    reg_by, gap_by = collections.defaultdict(list), collections.defaultdict(list)
    for r in reg:
        reg_by[r.get("working_label")].append(r)
    for g in gap:
        gap_by[g.get("working_label")].append(g)
    cov_by_label = {}
    for lab, L in sorted(derived["labels"].items()):
        obj = by_obj.get(lab)
        if obj is None:
            F.append(f"R6 S {lab}: no object in the assembled batch")
            continue
        runs = {r: v["role"] for r, v in L["runs"].items()}
        reading = [r for r, role in runs.items() if role in ("UNIT", "SINGLE")]
        final = next((r for r, role in runs.items() if role in ("SYNTHESIS", "SINGLE")), None)
        rows, files = set(L["row_sources"]), set(L["files"])
        # ---------------------------------------------------------------- R / P: records and facts
        records, states, cov_ok, facts_index, rec_hash, cov_lab = [], {}, {}, {}, {}, {}
        for run in reading:
            permitted = set(L["runs"][run]["files"])
            rpath = logical(root, f"{LEDGER}/{run}/file-reading-records.jsonl")
            recs = _jl(rpath) or []
            rec_hash[run] = _fsha(rpath)
            seen = collections.Counter(x.get("source_id") for x in recs)
            if set(seen) != permitted or any(n != 1 for n in seen.values()):
                F.append(f"R6 R {run}: file-reading records ≠ exactly one per permitted file")
            for x in recs:
                sid = x.get("source_id")
                if sid not in permitted:
                    continue
                c = cov_run.get(run, {}).get(sid)
                cov_lab[sid] = c
                st = x.get("reading_state")
                states[sid] = st
                cov_ok[sid] = bool(c and c["complete"])
                if st not in r6.READING_STATES:
                    F.append(f"R6 R {run} {sid}: reading_state {st!r} off-scale")
                if st == "WHOLE-FILE" and not cov_ok[sid]:
                    F.append(f"R6 R {run} {sid}: WHOLE-FILE without complete byte coverage in the owning run")
                if st == "WHOLE-FILE":
                    F += r6.ack_equal_violations(run, sid, x.get("ack_tokens"), c, contents.get(sid))
                F += r6.fact_violations(run, x, contents.get(sid))
                for f in x.get("facts") or []:
                    facts_index[(run, sid, f.get("fact_id"))] = dict(f, source_id=sid)
                records.append(x)
        cov_by_label[lab] = {s: v for s, v in cov_lab.items() if v}
        final_records = [obj] + reg_by.get(lab, []) + gap_by.get(lab, [])
        # ---------------------------------------------------------------- S: synthesis-level checks (all paths)
        F += [f"R6 S {lab}: {x}" for x in r5.edge_class_violations(obj, rows)]
        F += [f"R6 {x}" for x in r5.s2_violations(obj, rows)]
        F += r6.s3_lint_v6(final_records, lab, final or f"{batch}-R6", batch, quarantine_hits, sets, layer_a={0})
        if L["path"] == "EMPTY":
            F += r6.empty_violations_v6(obj)
            F += r6.claim_evidence_violations(obj, {}, {}, {}, {}, set())
            continue
        claim_map = _js(logical(root, f"{LEDGER}/{final}/claim-evidence.json")) or {}
        F += r6.claim_evidence_violations(obj, claim_map, facts_index, states, cov_ok, set(reading))
        F += r6.reading_rule_violations(obj, states)
        F += r6.s1_violations_v6(slices[lab].get("p3a_pairs") or [], lab, files, records, contents, obj,
                                 reg_by.get(lab, []))
        lint = _js(logical(root, f"{LEDGER}/{final}/S3-LINT.json")) or {}
        if lint.get("result") != "PASS" or lint.get("records_sha256") != r6.sha(r6.canon_bytes(final_records)):
            F.append(f"R6 S3 {lab}: pre-submit lint report missing, not PASS, or not bound to the submitted records")
        man = _js(logical(root, f"{LEDGER}/{final}/RUN-MANIFEST.json")) or {}
        F += r6.temporal_violations(lab, L["path"], runs, {r: logs.get(r, []) for r in reading},
                                    stages.get(lab, {}), {final: man}, rec_hash)
    return F, blog, cov_by_label
