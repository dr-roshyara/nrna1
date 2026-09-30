#!/usr/bin/env python3
"""R7 VERIFIER — a COMPOSER OF PREDICATES, not a source of truth (frozen addendum v2.3 §0, §10; DR-13, DR-15).

It evaluates the four batch predicates in their bounded contexts — U (Universe), W (Witness), E (Evidence), R
(Reconstruction) — and composes them by the strong-Kleene conjunction: BATCH-FAIL if any is F; otherwise
BATCH-UNDETERMINED if any is U; otherwise BATCH-PASS. It contains no epistemic rule of its own. It never emits a bare
PASS and never a statistics or programme verdict (STATISTICS-* comes only from p3b_s5_r7_stats.estimate_v7;
PROGRAM-ACCEPTED := ∀b BATCH-PASS(b) ∧ STATISTICS-VALID is a mechanical precondition for, never the act of, governance
acceptance). Its resolver reads are integrity-verification operations only; they create no Evidence, Reconstruction or
Theory object. Called by p3b_s5_verify.verify for contract revision 7.
"""
import importlib.util
import json
import os

_HERE = os.path.dirname(os.path.abspath(__file__))
CR = os.path.dirname(_HERE)


def _load(name):
    s = importlib.util.spec_from_file_location(name, os.path.join(_HERE, name + ".py"))
    m = importlib.util.module_from_spec(s)
    s.loader.exec_module(m)
    return m


U = _load("p3b_s5_r7_universe")
W = _load("p3b_s5_r7_witness")
E = _load("p3b_s5_r7_evidence")
C = _load("p3b_s5_r7_reconstruction")
LEDGER = U.LEDGER
DEFAULT_ARCHIVE = os.path.expanduser("~/knowledgeos-witness-archive")


def compose(values):
    """Strong-Kleene conjunction over {T, F, U}."""
    if "F" in values.values():
        return "BATCH-FAIL"
    if "U" in values.values():
        return "BATCH-UNDETERMINED"
    return "BATCH-PASS"


def program_accepted(batch_verdicts, statistics_verdict):
    """PROGRAM-ACCEPTED := (∀ b ∈ Batches(frame): BATCH-PASS(b)) ∧ STATISTICS-VALID — a mechanical precondition for,
    never the act of, governance acceptance (a human act recorded in the governance log)."""
    return bool(batch_verdicts) and all(v == "BATCH-PASS" for v in batch_verdicts.values()) \
        and statistics_verdict == "STATISTICS-VALID"


def _value(fails, undetermined=False):
    return "F" if fails else ("U" if undetermined else "T")


def _jl(path):
    if not path or not os.path.isfile(path):
        return None
    with open(path, encoding="utf-8") as f:
        return [json.loads(x) for x in f if x.strip()]


def _js(path):
    if not path or not os.path.isfile(path):
        return None
    with open(path, encoding="utf-8") as f:
        return json.load(f)


def verify_r7(batch, root, mhdr, entry, labels, slices, objs, reg_records, gap_records, resolver_factory, files_meta,
              logical, quarantine_hits=None, holdout_tokens=(), corpus_tokens=(), archive_root=None,
              slice_root="_batch_input_r2/s5"):
    """-> {"verdict", "predicates": {U,W,E,R}, "failures": {U,W,E,R: [...]}, "reads", "coverage_by_label"}."""
    FU, FW, FE, FR = [], [], [], []
    w_undetermined = False
    # ------------------------------------------------------------ Universe
    FU += U.revision_violations(mhdr)
    reg, cons, cf = U.load_frozen_contract(CR)
    FU += cf + U.consumer_violations(reg, cons)
    need = sorted({s for lab in labels if lab in slices for s in U.r6.slice_required(slices[lab])[0]})
    contents = resolver_factory().read_many(need) if need else {}                  # integrity read (not evidence)
    braw = None                                                                     # v2.6-DC3 (G-LOG-0099)
    if mhdr.get("r7_binary_decisions_sha256") is not None:            # read only when the manifest header binds it
        bpath = logical(root, U.BINARY_DECISIONS_PATH)
        braw = open(bpath, "rb").read() if bpath and os.path.isfile(bpath) else None
    decisions, brecs, bf = U.binary_decisions(braw, mhdr.get("r7_binary_decisions_sha256"),
                                              mhdr.get("r7_binary_decisions_authority"))
    FU += bf
    FU += U.binary_decision_integrity(brecs, contents, set(need))   # agreement with the resolver bytes; missing ones
    derived = U.plan_derive_v7(batch, [l for l in labels if l in slices], slices, contents, files_meta, decisions)
    declared = _js(logical(root, f"{slice_root}/{batch}.R7-PLAN.json"))
    FU += U.plan_violations(declared, derived, entry.get("r7_plan_sha256"))
    owners = U.run_owner(derived)
    ldir = os.path.join(root, LEDGER)
    FU += U.namespace_violations(ldir, batch, set(owners), entry.get("legacy_ledger_sha256"), entry.get("legacy_dirs") or [])
    committed_m = _js(os.path.join(ldir, f"{batch}-R7", "INPUT-MANIFESTS.json")) or {}
    for run in sorted(owners):
        m = committed_m.get(run)
        if m is None:
            FU.append(f"R7-U inputs: no frozen input manifest for planned run {run}")
            continue
        FU += U.input_manifest_violations(m, derived, batch, slice_root)
        want = U.input_manifest(root, batch, slice_root, derived, run, m.get("persisted_outputs") or "ALLOWED")
        if want is not None and [(e["category"], e["path"]) for e in want["entries"]] != \
                [(e.get("category"), e.get("path")) for e in m.get("entries") or []]:
            FU.append(f"R7-U inputs {run}: the frozen input manifest ≠ the Universe derivation of I(run)")
        for e in m.get("entries") or []:
            p = os.path.join(root, e.get("path") or "")
            if not os.path.isfile(p):          # RC-03 (G-LOG-0092): an absent file is a failed check, never a skipped one
                FU.append(f"R7-U inputs {run}: {e.get('path')} missing at verification (frozen-integrity failure)")
            elif U.fsha(p) != e.get("sha256"):
                FU.append(f"R7-U inputs {run}: {e.get('path')} bytes ≠ the frozen manifest sha256 (frozen-integrity failure)")
    for lab in sorted({o[0] for o in owners.values()}):                 # v2.7 EG-2: every read label's SLICE-VIEW
        sp, vp = os.path.join(root, slice_root, batch, f"{lab}.json"), os.path.join(root, slice_root, batch, f"{lab}.view.txt")
        st_ = open(sp, encoding="utf-8").read().rstrip("\n") if os.path.isfile(sp) else None
        vt_ = open(vp, encoding="utf-8").read() if os.path.isfile(vp) else None
        if st_ is not None:
            FU += U.slice_view_violations(st_, vt_, f"{batch}/{lab}")
    by_obj = {o.get("working_label"): o for o in objs}
    for o in objs:
        FU += U.validation_violations(o) + U.typing_violations(reg, o) + U.meta_violations(reg, o)
    # ------------------------------------------------------------ Witness
    arch = os.path.join(archive_root or mhdr.get("r7_archive") or DEFAULT_ARCHIVE, batch)
    main_path, sub_dir, tr_dir = os.path.join(arch, "main.jsonl"), os.path.join(arch, "subagents"), os.path.join(arch, "tool-results")
    reads, stages = [], {}
    if not os.path.isfile(main_path):
        w_undetermined = True                                                     # UNVERIFIABLE-WITNESS: W = U
        wres = {"reads": [], "records": [], "stages": {}, "failures": [], "agents": {}}
    else:
        declared_st = _jl(os.path.join(ldir, f"{batch}-R7", "R7-PROVENANCE.jsonl"))
        wres = W.witness(batch, derived, main_path, sub_dir, tr_dir, mhdr.get("r7_reader_abs"), mhdr.get("r7_activation_commit"),
                         committed_m, root, contents, holdout_tokens, corpus_tokens, declared_st)
        FW += wres["failures"]
        reads, stages = wres["reads"], wres["stages"]
        wb = W.witness_bytes(wres["records"])
        agents = [a["agent_id"] for a in wres["agents"].values()]
        dg = W.digests(main_path, sub_dir, agents, wb)
        adir = os.path.join(ldir, f"{batch}-R7")
        cw = open(os.path.join(adir, "WITNESS.jsonl"), "rb").read() if os.path.isfile(os.path.join(adir, "WITNESS.jsonl")) else None
        cd = _js(os.path.join(adir, "WITNESS-DIGESTS.json"))
        if cw != wb:
            FW.append("R7-W W7: the committed WITNESS.jsonl ≠ the witness re-derived from the archive")
        if cd != dg:
            FW.append("R7-W W7: the committed WITNESS-DIGESTS.json ≠ the digests of the archive and extractor")
        if any(L["path"] == "DECOMPOSED" for L in derived["labels"].values()):
            ub = open(os.path.join(adir, "WITNESS-UNIT.jsonl"), "rb").read() if os.path.isfile(os.path.join(adir, "WITNESS-UNIT.jsonl")) else None
            ud = _js(os.path.join(adir, "WITNESS-UNIT-DIGESTS.json"))
            if ub is None or ud is None:
                FW.append("R7-W W7: the unit-validation witness freeze is missing")
            else:
                FW += W.monotonicity_violations(ub, wb, ud, dg)
    # ------------------------------------------------------------ Evidence + Reconstruction (per label)
    cov = E.coverage(reads)
    cov_by_label = {}
    binary_decided = {}                               # v2.6-DC3: conforming decided binaries per label (G-04 coverage)
    all_logs = []
    for run in owners:
        all_logs += _jl(logical(root, f"{LEDGER}/{run}/READ-LOG.jsonl")) or []
    refusals = sum(1 for r in wres["records"] if r.get("kind") == "refused")
    FE += E.readlog_violations(reads, all_logs, refusals)
    reg_by, gap_by = {}, {}
    for r in reg_records:
        reg_by.setdefault(r.get("working_label"), []).append(r)
    for g in gap_records:
        gap_by.setdefault(g.get("working_label"), []).append(g)
    for lab, L in sorted(derived["labels"].items()):
        obj = by_obj.get(lab)
        if obj is None:
            FR.append(f"R7-R {lab}: no object in the assembled batch")
            continue
        runs = {r: d["role"] for r, d in L["runs"].items()}
        reading = {r for r, role in runs.items() if role in ("UNIT", "SINGLE")}
        final = next((r for r, role in runs.items() if role in ("SYNTHESIS", "SINGLE")), None)
        rows = set(L["row_sources"])
        claims = U.claims(reg, obj)
        final_records = [obj] + reg_by.get(lab, []) + gap_by.get(lab, [])
        FR += C.s3_violations(final_records, lab, final or f"{batch}-R7", batch, quarantine_hits, None, layer_a={0})
        FR += C.edge_and_s2_violations(obj, rows)
        FR += C.summary_violations(obj, files_meta) + C.lifecycle_violations(obj, files_meta)
        if L["path"] == "EMPTY":
            FR += C.empty_violations(obj, claims)
            continue
        records_by_run, states = {}, {}
        for run in sorted(reading):
            recs = _jl(logical(root, f"{LEDGER}/{run}/file-reading-records.jsonl")) or []
            records_by_run[run] = recs
            FE += E.record_violations(run, set(L["runs"][run]["files"]), recs, cov, contents, reads)
            for x in recs:
                if x.get("source_id") in set(L["runs"][run]["files"]):
                    states[x["source_id"]] = x.get("reading_state")
        cov_by_label[lab] = {s: {"complete": v["complete"], "have": v["have"], "n": v["n"], "bad": 0}
                             for (r, s), v in cov.items() if r in reading}
        idx = E.facts_index(records_by_run, contents, reads)
        claim_map = _js(logical(root, f"{LEDGER}/{final}/claim-evidence.json")) or {}
        FE += E.claim_violations(claims, claim_map, idx, states, cov, reading, lab)
        FE += E.quote_violations(obj, contents, reads, reading)
        s2f = set() if slices[lab].get("hub") else set(slices[lab].get("stage2_files") or [])     # AF-1 (G-LOG-0101)
        bdF, b_ok, b_fh = E.binary_decision_violations(obj, L.get("binary_decisions") or {}, states, reads, reading, claims,
                                                       s2f)
        FE += bdF
        binary_decided[lab] = sorted(b_ok)
        FE += E.reading_rule_violations(obj, states, claims, frozenset(b_fh))
        FE += E.census_violations(obj, states, cov, idx, reading, frozenset(b_fh))
        all_recs = [x for rs in records_by_run.values() for x in rs]
        FR += C.s1_violations(slices[lab].get("p3a_pairs") or [], lab, set(L["files"]), all_recs, contents, obj, reg_by.get(lab, []))
        lint = _js(logical(root, f"{LEDGER}/{final}/S3-LINT.json")) or {}
        if lint.get("result") != "PASS" or lint.get("records_sha256") != U.sha(U.canon(final_records)):
            FR.append(f"R7-R S3 {lab}: pre-submit lint report missing, not PASS, or not bound to the submitted records")
    if w_undetermined:
        # Evidence is derived from the witness: without it, E cannot be evaluated (U), never F from absent reads
        FE = []
    preds = {"U": _value(FU), "W": _value(FW, w_undetermined), "E": _value(FE, w_undetermined), "R": _value(FR)}
    if w_undetermined:
        FW.append("R7-W UNVERIFIABLE-WITNESS: the transcript archive is absent (W = U; E = U)")
    return {"verdict": compose(preds), "predicates": preds, "failures": {"U": FU, "W": FW, "E": FE, "R": FR},
            "reads": reads, "coverage_by_label": cov_by_label, "stages": stages, "binary_decided": binary_decided}
