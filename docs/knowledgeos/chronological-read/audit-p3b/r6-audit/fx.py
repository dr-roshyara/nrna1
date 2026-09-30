#!/usr/bin/env python3
"""Revision-6 independent re-audit (G-LOG-0084): the auditor's OWN fixture library.

Independence: the batch id (OB9011), the source material (S4 PB03, not the implementer's PB05), the synthetic bytes,
the byte sizes, the page/ack derivation, the plan derivation, the claim enumeration and every expected outcome are
written here from the contract TEXT (rev3 contract + R5/R6 addenda), not from the implementation. Only GENERIC
fixture-writing infrastructure is reused from the historical test module (T.s4_material, T.to_s5, T.write), as the
brief permits.

SAFETY: before ANY verify call the discovery resolver is replaced by FakeResolver (synthetic bytes only) and
qs.count_holdout_sids by a stub returning 0; the synthetic hold-out sets of the testlib are active. No corpus file is
opened, the reader CLI is never executed, and every write goes to a temp dir under the auditor's scratch area.
Label names are printed only as aliases (L1..Ln) so that no label text reaches an output file.
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

CR = os.path.abspath(os.path.join(os.path.dirname(os.path.abspath(__file__)), "..", ".."))
SCRIPTS = os.path.join(CR, "scripts")
TESTS = os.path.join(SCRIPTS, "tests")
for p in (TESTS, SCRIPTS):
    if p not in sys.path:
        sys.path.insert(0, p)
import test_p3b_s5_verify as T          # noqa: E402  generic infrastructure only (s4_material, to_s5, write)
import p3b_s5_ops_testlib as testlib    # noqa: E402

v, c, prep = T.v, T.c, T.prep
SCRATCH = os.environ.get("R6A_TMP", "/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-"
                         "personal-nrna1/0d960477-0e02-4543-b54c-09bd6efa1d6f/scratchpad/r6reaudit-tmp")
os.makedirs(SCRATCH, exist_ok=True)

PB, OB = "PB03", "OB9011"
R6 = f"{OB}-R6"
BUDGET = 600_000            # R5 addendum decision A/B (context budget per reading unit)
PAGE = 24_000               # R5 addendum decision D (byte page)
MODE = "bytes-r5"
T_READ, T_UV, T_SD, T_PROD = "2026-11-02T08:15:00Z", "2026-11-02T09:00:00Z", "2026-11-02T09:30:00Z", "2026-11-02T10:45:00Z"
WHOLE_NOT_REQUIRED = {"FIRST", "RESTATES", "EXTENDS", "NOT-COMPARABLE"}   # R6 addendum, R row
SIDRE = re.compile(r"\bS\d{4}\b")


# ------------------------------------------------------------------ own primitives (from the contract text)
def sha(b):
    return hashlib.sha256(b).hexdigest()


def canon(x):
    return json.dumps(x, sort_keys=True, separators=(",", ":"), ensure_ascii=False).encode("utf-8")


def evidence_sentence(sid):
    return f"Evidence clause {sid}: here the notion is fixed and its dependency is declared explicitly."


BIG = {}                    # sid -> size for the DECOMPOSED labels (set by build)


def synth_bytes(sid, size=None):
    """Deterministic synthetic content: pseudo-random words (seeded by the S-id), multibyte glyphs, and one evidence
    clause in the middle of the file. Never corpus text."""
    n = size or BIG.get(sid) or (4_000 + int(sha(sid.encode())[:4], 16) % 9_000)
    seed = sha(("r6-audit-" + sid).encode())
    words = ["lorem", "ßeta", "Ωmega", "grün", "漢字", "delta", "kappa", "ρho", "zeta", "échelle"]
    out, i, tot = [], 0, 0
    h = int(seed[:16], 16)
    while tot < n:
        h = (h * 6364136223846793005 + 1442695040888963407) % (1 << 64)     # LCG seeded by the S-id hash
        w = words[(h >> 33) % len(words)]
        out.append(w)
        tot += len(w.encode()) + 1
        i += 1
    body = " ".join(out).encode("utf-8")
    mid = len(body) // 2
    while mid < len(body) and (body[mid] & 0xC0) == 0x80:
        mid += 1
    ev = ("\n" + evidence_sentence(sid) + "\n").encode("utf-8")
    b = body[:mid] + ev + body[mid:]
    cut = n
    while cut < len(b) and (b[cut] & 0xC0) == 0x80:
        cut += 1
    b = b[:max(cut, mid + len(ev))]
    return b


def pages(b):
    """Own byte paging: spans of <= 24,000 bytes, never splitting a UTF-8 sequence, tiling [0, len)."""
    if not b:
        return [(0, 0)]
    out, i = [], 0
    while i < len(b):
        j = min(len(b), i + PAGE)
        while j < len(b) and (b[j] & 0xC0) == 0x80:
            j -= 1
        out.append((i, j))
        i = j
    return out


def ack(page_sha):
    return "ACK-" + page_sha[-12:]


def page_entry(run, batch, label, sid, content, k, utc, step):
    sp = pages(content)
    a, e = sp[k - 1]
    ps = sha(content[a:e])
    rec = {"source_id": sid, "page": k, "n_pages": len(sp), "byte_start": a, "byte_end": e, "page_sha256": ps,
           "content_sha256": sha(content), "ack_token": ack(ps), "mode": MODE}
    return {"utc": utc, "run_id": run, "batch_id": batch, "working_label": label, "step": step, "source_ids": [sid],
            "refused": False, "bytes": {sid: e - a}, "sha256": {sid: ps}, "stdout": "pipe", "page": rec, "mode": MODE}


def all_tokens(content):
    return [ack(sha(content[a:e])) for a, e in pages(content)]


def packing_key(sid, meta):
    """Own reading of the R5 addendum packing key: group 0 = file-level dated candidates (EXPLICIT basis, order evidence
    not exactly SOURCE_ID, no BULK mtime block, a YYYY-MM-DD date), by date; group 1 = the rest; S-id breaks ties."""
    m = meta or {}
    d = re.match(r"\d{4}-\d{2}-\d{2}", str(m.get("best_historical_date") or ""))
    dated = m.get("best_historical_date_basis") == "EXPLICIT" and str(m.get("order_evidence")) != "SOURCE_ID" \
        and not str(m.get("mtime_block") or "").startswith("BULK-") and d is not None
    return (0, d.group(0), sid) if dated else (1, "", sid)


def own_partition(files, rows, sizes, order):
    """Own reading: row sources next-fit in packing order (contiguous); other files first-fit decreasing (ties by S-id)
    into existing units, else a new unit; files never split."""
    units = [[]]
    for s in order:
        if units[-1] and sum(sizes[x] for x in units[-1]) + sizes[s] > BUDGET:
            units.append([])
        units[-1].append(s)
    units = [u for u in units if u]
    for s in sorted(set(files) - set(rows), key=lambda x: (-sizes[x], x)):
        for u in units:
            if sum(sizes[x] for x in u) + sizes[s] <= BUDGET:
                u.append(s)
                break
        else:
            units.append([s])
    return [sorted(u) for u in units]


def required(sl):
    rows = {json.loads(r)["source_id"] for r in ((sl.get("bundle") or {}).get("rows_verbatim") or [])}
    s2 = set() if sl.get("hub") else set(sl.get("stage2_files") or [])
    return s2 | rows, rows


def own_plan(batch, labels, slices, contents, meta):
    out = {"batch_id": batch, "revision": 6, "labels": {}}
    for i, lab in enumerate(labels, 1):
        req, rows = required(slices[lab])
        sizes = {s: len(contents[s]) for s in sorted(req)}
        tot = sum(sizes.values())
        path = "EMPTY" if not req else ("SINGLE" if tot <= BUDGET else "DECOMPOSED")
        L = f"{batch}-R6-L{i:02d}"
        e = {"index": i, "path": path, "files": sorted(req), "row_sources": sorted(rows), "sizes": sizes,
             "packing_order": [], "units": [], "runs": {}}
        if path == "SINGLE":
            e["units"] = [sorted(req)]
            e["runs"] = {L: {"role": "SINGLE", "files": sorted(req)}}
        elif path == "DECOMPOSED":
            order = sorted(rows, key=lambda s: packing_key(s, meta.get(s)))
            units = own_partition(req, rows, sizes, order)
            e["packing_order"], e["units"] = order, units
            e["runs"] = {f"{L}U{k:02d}": {"role": "UNIT", "files": u} for k, u in enumerate(units, 1)}
            e["runs"][f"{L}S"] = {"role": "SYNTHESIS", "files": []}
        out["labels"][lab] = e
    return out


def own_claims(obj):
    """Own enumeration of the source-requiring claims named by the R6 addendum (timeline points; FOUND absences; FOUND
    stage-2 values; S-id-bearing births; R2 edges). The claim-id spelling is not defined by the contract text; it is
    taken from the implementation (recorded as a contract gap)."""
    out = []
    for p in obj.get("timeline") or []:
        if isinstance(p, dict) and p.get("source_id"):
            out.append((f"timeline:{p['source_id']}", "TIMELINE", p["source_id"], {},
                        p.get("change_vs_previous") not in WHOLE_NOT_REQUIRED))
    for dim, a in (obj.get("absences") or {}).items():
        if isinstance(a, dict) and a.get("resolution") == "FOUND":
            out.append((f"absence:{dim}", "ABSENCE", (a.get("supplied_by") or {}).get("source_id"),
                        {"dimension": dim, "finding": "DEFINES"}, True))
    for d in obj.get("stage2_dispositions") or []:
        for dim, val in (d.get("by_dimension") or {}).items():
            if val == "FOUND":
                cid = f"stage2:{d.get('source_id')}:{d.get('hit_kind')}:{json.dumps(d.get('hit_key'))}:{d.get('term_index')}:{dim}"
                out.append((cid, "ABSENCE", d.get("source_id"), {"dimension": dim, "finding": "DEFINES"}, True))
    for k, val in (obj.get("births") or {}).items():
        for s in SIDRE.findall(str(val)):
            out.append((f"birth:{k}:{s}", "BIRTH", s, {"birth_kind": k}, not str(val).startswith("BIRTH-UNRESOLVED")))
    for e in obj.get("dependency_edges") or []:
        if isinstance(e, dict) and e.get("edge_class") == "R2-EVIDENCED":
            out.append((f"edge:{e.get('target_label')}:{e.get('source_id')}", "DEPENDENCY", e.get("source_id"),
                        {"target_label": e.get("target_label")}, False))
    return out


class FakeResolver:
    CONTENTS = {}

    def __init__(self):
        self.rows = {}

    def read_many(self, sids):
        missing = [s for s in sids if s not in FakeResolver.CONTENTS]
        if missing:
            raise RuntimeError(f"fake resolver: no synthetic bytes for {len(missing)} id(s)")   # never falls through
        return {s: FakeResolver.CONTENTS[s] for s in sids}


# ------------------------------------------------------------------ build a VALID revision-6 batch (own construction)
def _sanitize(ctx, plan):
    """Bring the historical S4 objects into revision-6 form WITHOUT attacking anything: births only on row sources;
    timeline only on required files; edges R1 on a row source (plus one R2 edge per label with >= 2 rows); ranges spelled
    out; nothing else changed."""
    rng6 = re.compile(r"\bS\d{4}\s*(?:–|—|-|−|‒|―|~|\.\.\.?|…|to|through|thru|until|till|bis)\s*(?:S?\d{2,4})\b", re.I)
    for key in ("objs", "reg", "gap"):
        ctx[key] = [json.loads(rng6.sub(lambda m: " and ".join(SIDRE.findall(m.group(0))) or m.group(0),
                                        json.dumps(x, ensure_ascii=False))) for x in ctx[key]]
    for o in ctx["objs"]:
        L = plan["labels"][o["working_label"]]
        rows, files = set(L["row_sources"]), set(L["files"])
        for k, val in list(o["births"].items()):
            if any(s not in rows for s in SIDRE.findall(str(val))):
                o["births"][k] = "NOT-EVIDENCED-IN-CAPTURE"
        o["timeline"] = [p for p in o.get("timeline") or [] if p.get("source_id") in files]
        for ed in o.get("dependency_edges") or []:
            ed["edge_class"] = "R1-STRUCTURAL"
            ed["source_id"] = sorted(rows)[0]


def build():
    ctx = T.to_s5(OB, *T.s4_material(PB), PB)
    ctx = json.loads(json.dumps(ctx, ensure_ascii=False).replace(f'"{OB}-R2"', f'"{R6}"').replace(f"{OB}-R2:", f"{R6}:"))
    ctx["run"] = R6
    labels = ctx["labels"]
    req = {l: required(ctx["slices"][l])[0] for l in labels}
    # own size assignment: the two labels with the most required files are made genuinely large (DECOMPOSED)
    big = sorted(labels, key=lambda l: (-len(req[l]), l))[:2]
    BIG.clear()
    for j, l in enumerate(big):
        for k, s in enumerate(sorted(req[l])):
            BIG[s] = (130_000 + 37_000 * ((k * 3 + j) % 5)) if j == 0 else (95_000 + 41_000 * (k % 4))
    contents = {s: synth_bytes(s) for l in labels for s in req[l]}
    meta = c.files_meta()
    plan = own_plan(OB, labels, ctx["slices"], contents, meta)
    _sanitize(ctx, plan)
    st = {"ctx": ctx, "contents": contents, "plan": plan, "plan_file": None, "plan_sha": None, "runs": {}, "prov": [],
          "extra": {}, "hdr": {"contract": {"revision": 6}}, "entry": {}, "alias": {l: f"L{i}" for i, l in enumerate(labels, 1)}}
    for lab in labels:
        L = plan["labels"][lab]
        o = next(x for x in ctx["objs"] if x["working_label"] == lab)
        rows = set(L["row_sources"])
        sl = ctx["slices"][lab]
        # one R2-EVIDENCED edge (own positive content) when the label has a row source
        if rows:
            r2src = sorted(rows)[-1]
            o.setdefault("dependency_edges", [])
            o["dependency_edges"].append({"target_label": "synthetic-target-concept", "kind": "DEFINITIONAL",
                                          "source_id": r2src, "quote": evidence_sentence(r2src),
                                          "edge_class": "R2-EVIDENCED"})
        reading = [r for r, d in L["runs"].items() if d["role"] in ("UNIT", "SINGLE")]
        owner = {s: r for r in reading for s in L["runs"][r]["files"]}
        recs = {r: {s: {"source_id": s, "reading_state": "WHOLE-FILE", "ack_tokens": all_tokens(contents[s]), "facts": [],
                        "pair_evidence_checks": []} for s in L["runs"][r]["files"]} for r in reading}
        cmap, n = {}, 0
        for cid, kind, src, fields, whole in own_claims(o):
            if src not in owner:
                raise RuntimeError(f"fixture: claim {cid} on a non-required source")
            n += 1
            f = dict({"fact_id": f"{st['alias'][lab]}-f{n:03d}", "kind": kind, "quote": evidence_sentence(src)}, **fields)
            if kind == "TIMELINE":
                cls = next((p.get("change_vs_previous") for p in o["timeline"] if p.get("source_id") == src), None)
                f["change_candidate"] = cls or "EXTENDS"
            recs[owner[src]][src]["facts"].append(f)
            cmap.setdefault(cid, []).append({"run": owner[src], "source_id": src, "fact_id": f["fact_id"]})
        files = set(L["files"])
        for p in sl.get("p3a_pairs") or []:
            if lab not in (p.get("a"), p.get("b")):
                continue
            cited = set(SIDRE.findall(json.dumps(p.get("what_says_this"), ensure_ascii=False) + json.dumps(p.get("basis"))))
            for s in sorted(cited & files):
                recs[owner[s]][s]["pair_evidence_checks"].append({"pair_id": p["pair_id"], "supports": "YES",
                                                                  "quote": evidence_sentence(s)})
        for r in reading:
            log = []
            for s in L["runs"][r]["files"]:
                step = 1 if s in rows else 7
                log += [page_entry(r, OB, lab, s, contents[s], k, T_READ, step) for k in range(1, len(pages(contents[s])) + 1)]
            st["runs"][r] = {"log": log, "records": [recs[r][s] for s in sorted(recs[r])]}
        final = next((r for r, d in L["runs"].items() if d["role"] in ("SYNTHESIS", "SINGLE")), None)
        if final:
            st["runs"].setdefault(final, {"log": None, "records": None})
            st["runs"][final].update({"claim": cmap, "lint": "auto", "manifest": {"run_id": final, "produced_utc": T_PROD,
                                                                                  "input_record_hashes": "auto"},
                                      "label": lab})
            if L["path"] == "DECOMPOSED":
                st["prov"] += [{"stage": "units-validated", "label": lab, "utc": T_UV, "record_hashes": "auto"},
                               {"stage": "synthesis-dispatched", "label": lab, "utc": T_SD}]
    return st


def obj(st, lab):
    return next(x for x in st["ctx"]["objs"] if x["working_label"] == lab)


def label_of(st, path, k=0):
    return [l for l in st["ctx"]["labels"] if st["plan"]["labels"][l]["path"] == path][k]


def _dump_jl(path, rows):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "w", encoding="utf-8") as f:
        f.write("".join(json.dumps(r, sort_keys=True, ensure_ascii=False) + "\n" for r in rows))


def _fsha(path):
    with open(path, "rb") as f:
        return sha(f.read())


def run(st, rev_hdr=None):
    """Write the state into a fresh temp root and run the PRODUCTION verifier (p3b_s5_verify.verify)."""
    st = copy.deepcopy(st)
    ctx, plan = st["ctx"], st["plan"]
    root = tempfile.mkdtemp(prefix="r6a-", dir=SCRATCH)
    try:
        pfile = st["plan_file"] if st["plan_file"] is not None else plan
        psha = st["plan_sha"] or sha(canon(pfile))
        ctx["log"] = []
        T.write(root, [ctx], header_extra=st["hdr"],
                entry_hook=lambda ob, e: (e.update(run_id=R6, r6_plan_sha256=psha), e.update(st["entry"])))
        sroot = st["hdr"].get("slice_root", prep.SLICE_ROOT)
        os.makedirs(os.path.join(root, sroot), exist_ok=True)
        with open(os.path.join(root, sroot, f"{OB}.R6-PLAN.json"), "wb") as f:
            f.write(canon(pfile))
        led = os.path.join(root, "ledger-p3b-r2")
        rh = {}
        for r, d in st["runs"].items():
            if d.get("log") is not None:
                _dump_jl(os.path.join(led, r, "READ-LOG.jsonl"), d["log"])
            if d.get("records") is not None:
                p = os.path.join(led, r, "file-reading-records.jsonl")
                _dump_jl(p, d["records"])
                rh[r] = _fsha(p)
        for r, d in st["runs"].items():
            if "claim" not in d:
                continue
            lab = d["label"]
            reading = [x for x, y in plan["labels"][lab]["runs"].items() if y["role"] in ("UNIT", "SINGLE")]
            os.makedirs(os.path.join(led, r), exist_ok=True)
            if d["claim"] is not None:
                with open(os.path.join(led, r, "claim-evidence.json"), "w", encoding="utf-8") as f:
                    json.dump(d["claim"], f, ensure_ascii=False)
            if d["lint"] is not None:
                lint = d["lint"]
                if lint == "auto":
                    recs = [next(x for x in ctx["objs"] if x["working_label"] == lab)] + \
                           [x for x in ctx["reg"] if x.get("working_label") == lab] + \
                           [x for x in ctx["gap"] if x.get("working_label") == lab]
                    lint = {"result": "PASS", "records_sha256": sha(canon(recs))}
                with open(os.path.join(led, r, "S3-LINT.json"), "w", encoding="utf-8") as f:
                    json.dump(lint, f)
            if d["manifest"] is not None:
                m = dict(d["manifest"])
                if m.get("input_record_hashes") == "auto":
                    m["input_record_hashes"] = {x: rh.get(x) for x in reading}
                with open(os.path.join(led, r, "RUN-MANIFEST.json"), "w", encoding="utf-8") as f:
                    json.dump(m, f)
        prov = []
        for p in st["prov"]:
            p = dict(p)
            if p.get("record_hashes") == "auto":
                p["record_hashes"] = {x: rh.get(x) for x, y in plan["labels"][p["label"]]["runs"].items() if y["role"] == "UNIT"}
            prov.append(p)
        _dump_jl(os.path.join(led, R6, "R6-PROVENANCE.jsonl"), prov)
        for rel, rows in st["extra"].items():
            _dump_jl(os.path.join(root, rel), rows)
        FakeResolver.CONTENTS = st["contents"]
        orig_res, orig_q = c.dio.discovery_resolver, v.qs.count_holdout_sids
        c.dio.discovery_resolver = FakeResolver
        v.qs.count_holdout_sids = lambda sids: 0
        try:
            with testlib.fake_holdout():
                body, _ = v.verify(OB, root=root)
        finally:
            c.dio.discovery_resolver, v.qs.count_holdout_sids = orig_res, orig_q
        return body
    finally:
        shutil.rmtree(root, ignore_errors=True)


def alias(st, text):
    for lab, a in sorted(st["alias"].items(), key=lambda x: -len(x[0])):
        text = text.replace(lab, a)
    return text


def summary(st, body, n=3):
    fl = [alias(st, x)[:150] for x in body["failures"]]
    r6f = [x for x in fl if x.startswith("R6 ")]
    key = (r6f or fl)[:n]
    return body["result"], body["gates"].get("R6"), len(fl), key
