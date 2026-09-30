#!/usr/bin/env python3
"""A COMPLETE, VALID synthetic R7 batch for full-path tests through p3b_s5_verify.verify (test helper).

Objects: the committed historical S4 R2.2 batch PB04 (T.nonhub_ctx; read-only material), rewritten to R7 and put through
a documented BASELINE SANITATION (below) so that the positive control satisfies the frozen R7 contract — sanitation is
not an attack. Source bytes are SYNTHETIC (a marker sentence per S-id; the label with the most files is served large, so
it is genuinely DECOMPOSED); transcripts are SYNTHETIC harness transcripts (r7_execution_fixture). No corpus, no reader
process, no network, no repository write (temp dirs only). The hold-out sets are the synthetic testlib sets.

Baseline sanitation (per label): births citing non-row sources → NOT-EVIDENCED-IN-CAPTURE (S2); timeline restricted to
the label's files with quotes set to the marker; timeline_summary recomputed (first_* = births; contradicted_by /
rejected_by from CONTRADICTS / RETRACTS points; later_* empty; lifecycle without SUPERSEDED and no superseded sources);
edges R1-STRUCTURAL on a row source with the marker quote; census disagreements kept only for the label's files, marker
quote; status_basis / hindsight sources restricted to cited sources; S-id ranges split (S3).
"""
import copy
import hashlib
import json
import os
import re
import shutil
import sys
import tempfile

HERE = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(HERE)
CR = os.path.dirname(SCRIPTS)
sys.path.insert(0, HERE)
sys.path.insert(0, SCRIPTS)
import test_p3b_s5_verify as T                      # noqa: E402
import p3b_s5_ops_testlib as testlib                # noqa: E402
import r7_execution_fixture as xf                   # noqa: E402
import p3b_s5_r7_universe as U                      # noqa: E402
import p3b_s5_r7_witness as W                       # noqa: E402

v, c, prep = T.v, T.c, T.prep
r5 = U.r5
PB = "PB04"
OB = T.PB_TO_OB[PB]
R2, R7 = f"{OB}-R2", f"{OB}-R7"
REG, CONS, _ = U.load_frozen_contract(CR)
BIG = set()
CURRENT = {}                                         # the temp root of the verify() in progress (for hooks)


def marker(sid):
    return f"FACT-MARKER {sid} states the evidence"


CONTENT_OVERRIDE = {}                                # per-test unique bytes for byte-offset anchoring cases


def synth(sid):
    if sid in CONTENT_OVERRIDE:
        return CONTENT_OVERRIDE[sid]
    unit = f"synthetic {sid} ä€ {marker(sid)}\n".encode("utf-8")
    n = 250_000 if sid in BIG else 3_000
    return (unit * (n // len(unit) + 1))[:n].decode("utf-8", errors="ignore").encode("utf-8")


class Fake:
    def __init__(self):
        self.rows = {}

    def read_many(self, sids):
        return {s: synth(s) for s in sids}


def toks(sid):
    b = synth(sid)
    return [r5.ack_token(hashlib.sha256(b[a:e]).hexdigest()) for a, e in r5.byte_pages(b)]


FIELDS = {"TIMELINE": lambda cl, o: {"change_candidate": next((p["change_vs_previous"] for p in o["timeline"]
                                                                 if p["source_id"] == cl["source"]), "EXTENDS")},
          "BIRTH": lambda cl, o: dict(cl["fields"]), "ABSENCE": lambda cl, o: dict(cl["fields"]),
          "DEPENDENCY": lambda cl, o: dict(cl["fields"]), "CONTRADICTION": lambda cl, o: {"contradicts_source": cl["fields"].get("contradicts_source") or "S0000"},
          "LIFECYCLE": lambda cl, o: {"lifecycle": "SUPERSEDED"}, "CENSUS": lambda cl, o: dict(cl["fields"]),
          "TWO-CONCEPT": lambda cl, o: {"concepts": ["a", "b"]}}


def sanitize(o, L):
    files, rows = set(L["files"]), set(L["row_sources"])
    for k, val in list(o["births"].items()):
        if any(x not in rows for x in U.SID.findall(str(val))):
            o["births"][k] = "NOT-EVIDENCED-IN-CAPTURE"
    tl = [p for p in o.get("timeline") or [] if p.get("source_id") in files]
    for p in tl:
        p["states"] = {"epistemic_class": "SOURCE", "quote": marker(p["source_id"])}
    if tl and tl[0].get("change_vs_previous") in ("CONTRADICTS", "RETRACTS"):
        tl[0]["change_vs_previous"] = "FIRST"
    o["timeline"] = tl
    cb = [{"source_id": p["source_id"], "contradicts": tl[i - 1]["source_id"]} for i, p in enumerate(tl)
          if i and p["change_vs_previous"] == "CONTRADICTS"]
    rb = [p["source_id"] for p in tl if p["change_vs_previous"] == "RETRACTS"]
    o["timeline_summary"] = dict({f"first_{k}": (U.SID.findall(str(o["births"].get(k) or "")) or [None])[0] for k in U.KINDS},
                                 later_support=[], later_refinement=[], contradicted_by=cb, rejected_by=rb,
                                 current_lifecycle="ACTIVE (baseline fixture)")
    o["superseded_by_sources"] = []
    for ed in o.get("dependency_edges") or []:
        ed["edge_class"] = "R1-STRUCTURAL"
        ed["source_id"] = sorted(rows)[0]
        ed["quote"] = marker(ed["source_id"])
    o["census_reading_disagreements"] = [dict(x, quote=marker(x["source_id"])) for x in o.get("census_reading_disagreements") or []
                                         if isinstance(x, dict) and x.get("source_id") in files]
    for a in (o.get("absences") or {}).values():
        sb = a.get("supplied_by") if isinstance(a, dict) else None
        if isinstance(sb, dict) and sb.get("source_id"):
            sb["quote"] = marker(sb["source_id"])
    # (v2.4, G-LOG-0089: the former NDB-1/DC-1 sanitation of reason / historical_position / escalation field is removed;
    # those locations are typed META, so real-shaped text with S-id mentions stays in the positive control.)
    cited = {cl["source"] for cl in U.claims(REG, o)}
    for sb in (o.get("status_basis") or {}).values():
        if isinstance(sb, dict):
            sb["sources"] = [s for s in sb.get("sources") or [] if s in cited]
    o["hindsight_dependency"] = [s for s in o.get("hindsight_dependency") or [] if s in cited]
    return o


EMPTY_LAB = "rejection-produces-hypothesis-space-not-truth"


def make_empty(ctx, lab):
    """Make one label genuinely EMPTY (required set = ∅; plan re-derived): no stage-2 files, no row sources, no own
    label hits; the object resolves NOT-EVIDENCED-IN-CAPTURE with an EMPTY-REQUIRED-SET escalation (R17)."""
    sl = ctx["slices"][lab]
    sl["stage2_files"] = []
    sl.setdefault("bundle", {})["rows_verbatim"] = []
    for r in sl.get("search_records") or []:
        if r.get("record") == "LABEL-HITS" and r.get("label") == lab:
            r["raw_hits"], r["ledger_hits"] = {}, {}
    o = next(x for x in ctx["objs"] if x["working_label"] == lab)
    o["births"] = {k: "NOT-EVIDENCED-IN-CAPTURE" for k in U.KINDS}
    o["timeline"], o["dependency_edges"], o["stage2_dispositions"] = [], [], []
    o["census_reading_disagreements"], o["superseded_by_sources"] = [], []
    o["semantic_evidence"] = {"d4": [], "d5": []}
    for dim, a in (o.get("absences") or {}).items():
        o["absences"][dim] = {"resolution": "ESCALATED", "negative_label": a.get("negative_label"),
                              "population_basis": a.get("population_basis"), "supplied_by": None,
                              "reason": "empty required set"}
    o["escalations"] = [{"field": "absences", "reason": "OTHER", "detail": "EMPTY-REQUIRED-SET (R17): no required file"}]
    for sb in (o.get("status_basis") or {}).values():
        if isinstance(sb, dict):
            sb["sources"] = []
    o["hindsight_dependency"] = []


def base(pre_obj_mut=None, overrides=None):
    """The valid state: ctx (objects), plan, contents, records per run, claim maps, outputs per run, read logs.
    pre_obj_mut(ctx): mutate objects BEFORE evidence is generated (positive controls); overrides: {sid: bytes}."""
    global BIG
    CONTENT_OVERRIDE.clear()
    CONTENT_OVERRIDE.update(overrides or {})
    ctx = T.nonhub_ctx(PB)
    ctx = json.loads(json.dumps(ctx, ensure_ascii=False).replace(f'"{R2}"', f'"{R7}"').replace(f"{R2}:", f"{R7}:"))
    ctx["run"] = R7
    labels = ctx["labels"]
    big = max(labels, key=lambda l: len(U.r6.slice_required(ctx["slices"][l])[0]))
    make_empty(ctx, EMPTY_LAB)
    BIG = set(U.r6.slice_required(ctx["slices"][big])[0])
    contents = Fake().read_many(sorted({s for l in labels for s in U.r6.slice_required(ctx["slices"][l])[0]}))
    plan = U.plan_derive_v7(OB, labels, ctx["slices"], contents, c.files_meta())
    unr = lambda m: " and ".join(re.findall(r"S\d{4}", m.group(0)))
    for key in ("objs", "reg", "gap"):
        ctx[key] = [json.loads(U.r6.RANGE6.sub(unr, json.dumps(x, ensure_ascii=False))) for x in ctx[key]]
    st = {"ctx": ctx, "plan": plan, "contents": contents, "big": big, "records": {}, "claims": {}, "outputs": {},
          "readlogs": {}, "overrides": dict(overrides or {})}
    for lab in labels:
        sanitize(next(x for x in ctx["objs"] if x["working_label"] == lab), plan["labels"][lab])
    if pre_obj_mut:
        pre_obj_mut(ctx, plan)
    for lab in labels:
        L = plan["labels"][lab]
        o = next(x for x in ctx["objs"] if x["working_label"] == lab)
        reading = [r for r, d in L["runs"].items() if d["role"] in ("UNIT", "SINGLE")]
        owner_of = {s: r for r in reading for s in L["runs"][r]["files"]}
        recs = {r: {s: {"source_id": s, "reading_state": "WHOLE-FILE", "ack_tokens": toks(s), "facts": [],
                        "pair_evidence_checks": []} for s in L["runs"][r]["files"]} for r in reading}
        cmap, n = {}, 0
        for cl in U.claims(REG, o):
            s = cl["source"]
            if s not in owner_of:
                continue
            n += 1
            f = dict({"fact_id": f"F{n}", "kind": cl["kind"], "quote": marker(s)}, **FIELDS[cl["kind"]](cl, o))
            recs[owner_of[s]][s]["facts"].append(f)
            cmap[cl["id"]] = [{"run": owner_of[s], "source_id": s, "fact_id": f["fact_id"]}]
        need = set()
        for p in ctx["slices"][lab].get("p3a_pairs") or []:
            if lab in (p.get("a"), p.get("b")):
                cited = set(r5.SID.findall(json.dumps(p.get("what_says_this"), ensure_ascii=False) + json.dumps(p.get("basis"))))
                need |= {(p["pair_id"], s) for s in cited & set(L["files"])}
        for pid, s in sorted(need):
            recs[owner_of[s]][s]["pair_evidence_checks"].append({"pair_id": pid, "supports": "YES", "quote": marker(s)})
        for r in reading:
            st["records"][r] = [recs[r][s] for s in sorted(recs[r])]
        final = next((r for r, d in L["runs"].items() if d["role"] in ("SYNTHESIS", "SINGLE")), None)
        if final:
            st["claims"][final] = cmap
    return st


def objs_of(st, lab):
    return next(x for x in st["ctx"]["objs"] if x["working_label"] == lab)


def run_outputs(st):
    out = {}
    for lab, L in st["plan"]["labels"].items():
        for run, d in L["runs"].items():
            files = {}
            if d["role"] in ("UNIT", "SINGLE"):
                files[f"{U.LEDGER}/{run}/file-reading-records.jsonl"] = "".join(
                    json.dumps(x, sort_keys=True, ensure_ascii=False) + "\n" for x in st["records"][run])
            if d["role"] in ("SYNTHESIS", "SINGLE"):
                o = objs_of(st, lab)
                reg = [x for x in st["ctx"]["reg"] if x.get("working_label") == lab]
                gap = [x for x in st["ctx"]["gap"] if x.get("working_label") == lab]
                files[f"{U.LEDGER}/{run}/objects.jsonl"] = json.dumps(o, sort_keys=True, ensure_ascii=False) + "\n"
                files[f"{U.LEDGER}/{run}/register.jsonl"] = "".join(json.dumps(x, sort_keys=True) + "\n" for x in reg)
                files[f"{U.LEDGER}/{run}/p1-gap.jsonl"] = "".join(json.dumps(x, sort_keys=True) + "\n" for x in gap)
                files[f"{U.LEDGER}/{run}/claim-evidence.json"] = json.dumps(st["claims"].get(run, {}), sort_keys=True)
                files[f"{U.LEDGER}/{run}/S3-LINT.json"] = json.dumps(
                    st.get("lint_override", {}).get(run) or {"result": "PASS", "records_sha256": U.sha(U.canon([o] + reg + gap))})
            out[run] = files
    return out


ASSEMBLY_FILES = ("objects.jsonl", "register.jsonl", "p1-gap-capture.jsonl")


def assembled_prints(root):
    """The `<sha256>  <relpath>` lines the ASSEMBLED marker command prints (v2.8 §2.7): the assembly files now on disk."""
    rels = [f"{U.LEDGER}/{R7}/{n}" for n in ASSEMBLY_FILES]
    return "".join(f"{U.fsha(os.path.join(root, r))}  {r}\n" for r in rels if os.path.isfile(os.path.join(root, r)))


def with_assembled_prints(root, hooks):
    """hooks with a "main" hook that first sets the ASSEMBLED and FINAL-VALIDATED (`--check`) marker outputs to
    assembled_prints(root) (evaluated when the transcript is built, i.e. after T.write and the run outputs), then
    applies the test's own "main" hook."""
    hooks = dict(hooks or {})
    user = hooks.get("main")

    def main(items):
        text = assembled_prints(root)
        for it in items:
            if it.get("kind") == "marker" and text and any(
                    it["command"].startswith(f": S5-ORCH {m} batch={OB};") for m in ("ASSEMBLED", "FINAL-VALIDATED")):
                it["out"] = text
        return user(items) if user else items
    hooks["main"] = main
    return hooks


def reader_logs(st, root, info):
    """READ-LOG.jsonl per reading run, as the reader writes them (one line per witnessed page; W6 reconciles)."""
    for run, (lab, role, files) in U.run_owner(st["plan"]).items():
        if role == "SYNTHESIS":
            continue
        rows = []
        for s in sorted(files):
            raw = st["contents"][s]
            for k in range(1, len(r5.byte_pages(raw)) + 1):
                _, rec = r5.byte_page_record(s, raw, k, hashlib.sha256(raw).hexdigest())
                rows.append({"utc": "2026-10-01T09:00:00Z", "run_id": run, "batch_id": OB, "working_label": lab, "step": 7,
                             "source_ids": [s], "refused": False, "bytes": {s: rec["byte_end"] - rec["byte_start"]},
                             "page": rec, "mode": r5.READER_MODE})
        if st.get("readlog_mut"):
            rows = st["readlog_mut"](run, rows)
        xf.put(root, f"{U.LEDGER}/{run}/READ-LOG.jsonl", "".join(json.dumps(x, sort_keys=True) + "\n" for x in rows))


def verify(state, mutate=None):
    """Materialize a (mutated) copy of the state and run the PRODUCTION verifier. Returns the report body."""
    st = copy.deepcopy(state)
    CONTENT_OVERRIDE.clear()
    CONTENT_OVERRIDE.update(st.get("overrides") or {})
    if mutate:
        mutate(st)
    ctx, plan = st["ctx"], st["plan"]
    root = tempfile.mkdtemp(prefix="r7full-")
    arch = tempfile.mkdtemp(prefix="r7arch-")
    orig = (c.dio.discovery_resolver, v.qs.count_holdout_sids, v.R7_ARCHIVE[0])
    try:
        for f in (U.REV3_CONTRACT_PATH, U.ADDENDUM_PATH):
            shutil.copy(os.path.join(CR, f), xf.put(root, f, b""))
        psha = st.get("plan_sha_override") or U.sha(U.canon(plan))
        ctx["log"] = []
        header = {"contract": {"revision": 7, "sha256": U.ADDENDUM_SHA256, "path": U.ADDENDUM_PATH},
                  "r7_reader_abs": xf.READER_ABS, "r7_activation_commit": xf.COMMIT}
        header.update(st.get("header_extra") or {})
        empty_legacy = hashlib.sha256().hexdigest()
        T.write(root, [ctx], header_extra=header,
                entry_hook=lambda ob, e: e.update(dict({"run_id": R7, "r7_plan_sha256": psha,
                                                        "legacy_ledger_sha256": empty_legacy, "legacy_dirs": []},
                                                       **(st.get("entry_extra") or {}))))
        for lab in ctx["labels"]:                          # v2.7 EG-2: the derived SLICE-VIEW beside each frozen slice
            xf.put(root, f"{prep.SLICE_ROOT}/{OB}/{lab}.view.txt",
                   U.slice_view(T.canon(ctx["slices"][lab])).encode("utf-8"))
        if st.get("view_mut"):
            st["view_mut"](root)
        xf.put(root, f"{prep.SLICE_ROOT}/{OB}.R7-PLAN.json", U.canon(st.get("plan_file", plan)))
        adir = os.path.join(arch, OB)
        CURRENT.update(root=root, arch=adir)
        info = xf.build(root, adir, OB, plan, st["contents"], prep.SLICE_ROOT, run_outputs(st),
                        hooks=with_assembled_prints(root, st.get("hooks")))
        reader_logs(st, root, info)
        manifests = info["manifests"]
        if st.get("manifest_mut"):
            st["manifest_mut"](manifests)
        xf.put(root, f"{U.LEDGER}/{R7}/INPUT-MANIFESTS.json", json.dumps(manifests, sort_keys=True))
        if st.get("pre_freeze"):
            st["pre_freeze"](root, adir, info)
        # the orchestrator's freezes: the final witness, and the unit-validation witness (its unit-agent records)
        c.dio.discovery_resolver = Fake
        with testlib.fake_holdout():
            wres = W.witness(OB, plan, os.path.join(adir, "main.jsonl"), os.path.join(adir, "subagents"),
                             os.path.join(adir, "tool-results"), xf.READER_ABS, xf.COMMIT, manifests, root, st["contents"],
                             v.R7_SEAL_CHECK[0], v.R7_CORPUS_CHECK[0])
        wb = W.witness_bytes(wres["records"])
        agents = [a["agent_id"] for a in wres["agents"].values()]
        dg = W.digests(os.path.join(adir, "main.jsonl"), os.path.join(adir, "subagents"), agents, wb)
        units = {r for r, (lab, role, _) in U.run_owner(plan).items() if role == "UNIT"}
        unit_aids = {info["agents"][r] for r in units if r in info["agents"]}
        urecs = [r for r in wres["records"] if r.get("run") in units or r.get("agent_id") in unit_aids
                 or (r.get("kind") == "orchestrator" and r.get("marker") == "UNITS-VALIDATED")]
        udg = {"decoder_sha256": dg["decoder_sha256"], "extractor_sha256": dg["extractor_sha256"],
               "transcripts": {k: h for k, h in dg["transcripts"].items() if any(a in k for a in unit_aids)},
               "witness_sha256": U.sha(W.witness_bytes(urecs))}
        adr = f"{U.LEDGER}/{R7}"
        xf.put(root, f"{adr}/WITNESS.jsonl", wb)
        xf.put(root, f"{adr}/WITNESS-DIGESTS.json", json.dumps(dg))
        xf.put(root, f"{adr}/WITNESS-UNIT.jsonl", W.witness_bytes(urecs))
        xf.put(root, f"{adr}/WITNESS-UNIT-DIGESTS.json", json.dumps(udg))
        if st.get("post"):
            st["post"](root, adir, info)
        v.qs.count_holdout_sids = lambda sids: 0
        v.R7_ARCHIVE[0] = None if st.get("no_archive") else arch
        if st.get("no_archive"):
            v.R7_ARCHIVE[0] = os.path.join(arch, "absent")
        with testlib.fake_holdout():
            body, _ = v.verify(OB, root=root)
        body["_witness_failures"] = wres["failures"]
        return body
    finally:
        c.dio.discovery_resolver, v.qs.count_holdout_sids, v.R7_ARCHIVE[0] = orig
        shutil.rmtree(root, ignore_errors=True)
        shutil.rmtree(arch, ignore_errors=True)


def first_run(st, role):
    return next(r for r, (lab, rl, _) in sorted(U.run_owner(st["plan"]).items()) if rl == role)
