#!/usr/bin/env python3
"""P3b S5 BATCH VERIFIER (plan v2.3.2 §A.2, §A.3, §B.3, §B.8, §C.1, §E; ops spec §1 item 3; core v1.7 §20; G-LOG-0023,
G-LOG-0027 F1–F3, G-LOG-0041). Infrastructure only: it verifies one batch's outputs; it dispatches nothing, reads no
corpus text and never reads a sealed artifact. Exit 0 = PASS, 1 = FAIL, 2 = refused / usage.

  python3 p3b_s5_verify.py --batch OB#### [--run OB####-R2.2|OB####-R2S] [--root DIR] [--out FILE]

Run ids: OB####-R2 (primary), OB####-R2.<n> (re-run, §20), OB####-R2S (plan §K COMPARISON rerun: same checks; the
report carries comparison: true and acceptance_evidence: false; a COMPARISON run is never acceptance evidence).

Inputs (relative to --root, default the repository C; the plan §M item 6 allowlist is applied to each logical path):
  _batch_manifest_p3b_r2.jsonl                        the batch's manifest entry (slice hashes, contract, predicted load)
  _batch_input_r2/s5/<batch>/<label>.json             the materialized slices
  ledger-p3b-r2/<run>/{objects,register,p1-gap-capture}.jsonl and READ-LOG.jsonl
Frozen inputs read from C through p3b_s5_common: 02-FILES (discovery rows), the hub list (body hash checked), the seal
(asserted SEALED at start and end; hold-out lists used by the sanitizer and scanner only, never printed).
Output: audit-p3b/S5-VERIFY-<batch>.json (S5-VERIFY-<run>.json for a re-run), {"header": §19.5 header, "body": report};
the header's output_sha256 is the sha256 of the canonical body, which is deterministic for identical inputs. The file is
written once (audit-p3b is append-only); an existing file is never overwritten.

Checks (any FAIL fails the batch; the tag prefixes each failure line):
  SLICES     manifest slice hashes = materialized slice hashes; slice set = manifest labels; contract / input-manifest
             sha consistent across manifest, slices, snapshot and records (also the G-10 slice-reproducibility part).
  SCHEMA     exact object / P1-gap key sets (contract section E), register core + kind profile keys, nested shapes.
  G-01       cited S-ids ∈ 02-FILES; no F-id outside quotes; no FIREWALL-LIMITED file used as evidence.
  G-02       exactly the assigned labels, once each; register / P1-gap records only for assigned labels.
  G-04       every DIMENSION resolved; one stage-2 disposition per DISTINCT hit key (by_dimension covering every
             dimension); GENUINELY-UNDEFINED only with every hit FALSE-HIT/UNSUPPLIED or NEGATIVE-CENSUS; FOUND has a
             FOUND hit, a supplied_by, and a whole-file reader read (non-refused, step 1 or 7); no FOUND on
             NEGATIVE-CENSUS; census–reading disagreement → ESCALATED; stage-2 coverage; §11.2 birth minimums.
  G-05       closed values (ENUMS), birth patterns, pair_breakdown = consumed pairs, tier = slice tier.
  G-07       run_id / batch_id / contract_sha256 / model_id on every record (+ input_manifest_sha256,
             generation_parameters on objects); rs_id / gap_id forms; model rule (claude-opus-5-5).
  G-08       FOUND / ESTABLISHED / MOVED / BIRTH-UNRESOLVED bases PRIMARY per the slice's source_meta (an S-id without
             source_meta is UNKNOWN and never a sole basis); ESCALATED[G-08] names the true provenance; CONTESTED needs
             a PRIMARY D4 source; anti_projection present in status_basis.
  D-23       (G-08, OMQ-07 mapping (2)) when the object's earliest evidence has d23_track A: no birth, FOUND, edge,
             status or CONTESTED rests solely on a d23_track-B source (FOUND allowed only with relative_timing
             LATER-THAN-FIRST-APPEARANCE, §14.5).
  G-09       no STATUS; register lifecycle ≤ TEST-DEFINED; HYPOTHESIS / STRUCTURE-CANDIDATE profile at TEST-DEFINED
             with test_plan_sha256; GAP has a §13.4 gap_status; SUGGESTION / DEFICIENCY execution_impact; null field ⇒
             SCHEMA-LIMITATION escalation + SCHEMA-LIMITATION record (G-LOG-0023 b); checklist_examined 1..23.
  G-11       record_status PROPOSED.
  G-12       no register id, pointer, research_time or register statement text in layer-A fields; no research
             epistemic class in status_basis; PROPOSED:* topics defined.
  HUB        v1.7 §11.4: slice hub flag = list membership and hub_record = the list line; hub: stage2_dispositions
             empty, every hit-bearing dimension ESCALATED with a LOAD escalation carrying the slice's hub_record,
             never FOUND / GENUINELY-UNDEFINED, no step-7 or Stage-2A read, no step-10 read of a hit file not read at
             step 1, GAP records NOT-FOUND-LOAD-ESCALATED; non-hub: no LOAD escalation, no NOT-FOUND-LOAD-ESCALATED.
  F1         a NOT-CONFIRMED timeline point keeps an order value (null only with a rule-(b) escalation).
  F3         STAGE-2A-2B dispositions carry `offsets`; per (file, term) the offsets cover the logged Stage-2A
             occurrences exactly once, and cite no unlogged offset.
  SEMANTIC   Tier Z: null, ROW-0, the exact H-11a note; Tier U: ROW-1..ROW-4 (ROW-0 only when the slice's mechanical
             rule is ROW-0); ROW-3/ROW-4 = slice semantic_status_mechanical; ROW-1/ROW-2 need d4/d5 evidence.
  AUDIT      cited S-ids only from the slice or non-refused reads of this batch.
  QUARANTINE outputs and every file of the materialized slice directory scanned with c.quarantine_hits; non-refused
             reads counted with the scanner's count_holdout_sids; a slice with family_md_status "QUARANTINED
             (addendum §6)" must carry an empty family_md. Counts only; any hit fails (→ P3B-ESC). This module never
             obtains the hold-out lists itself (H-19 guard): report strings are sanitized through the same helpers.
Alerts (never failures, plan §B.3 / §C.1; §20 unchanged): actual/predicted > 1.5× for stage-2 bytes, step-1 bytes,
reader calls and Stage-2B occurrences; step-10 bytes > 900,000; step-10 reads outside the label's slice files and reads
for unassigned labels (contract deviations: §22 routes them to the audit, which alone can find a PROTOCOL-VIOLATION).
"""
import collections
import importlib.util
import json
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))


def _load(name, fname):
    spec = importlib.util.spec_from_file_location(name, os.path.join(_HERE, fname))
    mod = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(mod)
    return mod


sys.path.insert(0, _HERE)
import p3b_s5_ops_lib as ops  # noqa: E402  (shared common instance: the scanner and the test fixtures use the same one)
import p3b_s5_quarantine_scan as qs  # noqa: E402  (count-only hold-out helpers; this module never touches the lists)

c = ops.load_common()
prep = _load("p3b_s5_prepare", "p3b_s5_prepare.py")
reader = _load("p3b_read_source", "p3b_read_source.py")      # page definition shared with the reader (single source)
CR, canon, sha256_bytes, s3 = c.CR, c.canon, c.sha256_bytes, c.s3
E, SCHEMA = prep.ENUMS, prep.SCHEMA
TIER_Z_NOTE = prep.TIER_Z_NOTE

MANIFEST = prep.MANIFEST
SLICE_ROOT = prep.SLICE_ROOT
LEDGER = "ledger-p3b-r2"
ALERT_RATIO = 1.5                         # plan §B.3
STEP10_CAP = 900000                       # plan §C.1 item 2
KINDS = ("lexical", "conceptual", "formal", "operational", "governance")
REG_KINDS = {"OBSERVATION": "B", "GAP": "B", "SUGGESTION-RESEARCH": "C", "SUGGESTION-METHOD": "C", "HYPOTHESIS": "C",
             "STRUCTURE-CANDIDATE": "C", "SCHEMA-LIMITATION": "B", "METHODOLOGICAL-DEFICIENCY": "B"}   # §13.2
PROFILE = ("falsification_condition", "validation_question", "competing_hypotheses", "contradicting_evidence",
           "temporal_scope", "claim_type", "evidence_level")                                          # §13.6
EPISTEMIC = {"SOURCE", "INFERENCE", "RESEARCH-OBSERVATION", "DOMAIN-INTERPRETATION", "RESEARCH-SUGGESTION",
             "HYPOTHESIS", "THEORY-CANDIDATE", "EXTERNAL-THEORY-COMPARISON", "VERIFIED", "GOVERNANCE"}  # §11.1
LAYER_BC_CLASSES = {"RESEARCH-OBSERVATION", "RESEARCH-SUGGESTION", "HYPOTHESIS", "THEORY-CANDIDATE"}
UNDEF_OK = {"FALSE-HIT", "UNSUPPLIED-DIMENSION"}
SID = re.compile(r"\bS\d{4}\b")
FID = re.compile(r"\bF\d{4}\b")
POINTER = re.compile(r"\bsee (the )?register\b|\bresearch register\b|\bregister (record|id|entry)\b|\brs_id\b|"
                     r"\bregister (OBSERVATION|GAP|HYPOTHESIS|STRUCTURE-CANDIDATE|SCHEMA-LIMITATION|METHODOLOGICAL-DEFICIENCY|SUGGESTION-\w+)\b", re.I)
HEX64 = re.compile(r"[0-9a-f]{64}")
MODEL_IDS = (c.MODEL_ID, c.MODEL_ID + "[1m]")           # model rule: exactly these two served id strings
RUN_RE = r"-R2(?:\.\d+|S)?"                              # primary · re-run (§20) · §K COMPARISON rerun
# R7 SEAL / corpus-path checks for W8 (count-only common helpers: this module never obtains the hold-out lists) and the
# transcript archive root. Mutable one-element holders so the synthetic test fixture can substitute them.
R7_CORPUS_RX = re.compile(r"\bgit\s+(show|cat-file|log\s+[^;&|\n]*-p|grep)\b|\bp3b_discovery_io\b|\bread_many\s*\(|"
                          r"\bdiscovery_resolver\s*\(|docs/knowledgeos/[^\s'\"]*[*?\[]|\bopen\s*\(")   # R5 OUTSIDE/GLOB + R6
R7_SEAL_CHECK = [lambda text: any(c.quarantine_hits(text))]
R7_CORPUS_CHECK = [lambda text: bool(R7_CORPUS_RX.search(text))]
R7_ARCHIVE = [None]
# §14.5: the one optional absence key; read from the imported contract schema E when it documents it
REL_TIMING = "relative_timing"
REL_TIMING_SOURCE = "SCHEMA" if REL_TIMING in json.dumps(SCHEMA.get("object_record", {}).get("absences", "")) else "§14.5 (fallback)"
AB_OPTIONAL = {REL_TIMING}


def model_ok(v):
    return isinstance(v, str) and v in MODEL_IDS


class Refused(RuntimeError):
    pass


# ----------------------------------------------------------------------------------------------------- helpers
def birth_ok(kind, v):
    pats = [rf"ESTABLISHED-{kind.upper()}-BIRTH\[S\d{{4}}\]", r"MOVED\[S\d{4}, .+\]", r"UNORDERED-BLOCK\[BULK-\d+\]",
            r"BIRTH-UNRESOLVED-MTIME-ONLY\[S\d{4}\]", r"ESCALATED\[TIMESTAMP-ANOMALY: .+\]",
            r"ESCALATED\[G-08: sole basis S\d{4} is (SECONDARY-SYNTHESIS|PROVENANCE-UNRESOLVED|UNKNOWN)\]", r"NOT-EVIDENCED-IN-CAPTURE"]
    return isinstance(v, str) and any(re.fullmatch(p, v, re.S) for p in pats)


# Contract revision 3 (G-LOG-0050): whole-file reading is proven only by complete, hash-verified page coverage.
READ_CHANGE_POINTS = {"NARROWS", "CHANGES-DEFINITION", "CHANGES-TYPE", "CHANGES-TERM", "CONTRADICTS", "RETRACTS"}
OMQ14_ROW_TYPES = {"CONTRADICTION", "CORRECTION", "RETRACTION"}
# Schema revision 4 delta (G-LOG-0052; decomposition pilot only until a production contract adopts it): a required file
# that was NOT consumed is disposed `NOT-CONSUMED-ESCALATED`: every dimension ESCALATED and a CONTRACT-DEVIATION
# escalation whose detail names the S-id. It never counts as reading, never supports FOUND / NOT-FOUND / whole-file
# evidence, and does not relieve G-04 (the absence procedure is never thinned).
SCHEMA_V4_METHODS = ("NOT-CONSUMED-ESCALATED",)


def page_coverage(entries):
    """{sid: {"n": pages, "have": set, "complete": bool, "bad": int}} from non-refused paged read-log entries; every
    logged page hash is re-computed from the content through the discovery resolver (a checker read, not an agent
    read). A file counts as read only if pages 1..n are all logged with verified hashes; delivery alone never counts."""
    by = collections.defaultdict(list)
    for e in entries:
        pg = e.get("page") if isinstance(e.get("page"), dict) else None
        if pg and not e.get("refused") and pg.get("source_id") in (e.get("source_ids") or []):
            by[pg["source_id"]].append(pg)
    out = {}
    if not by:
        return out
    data = c.dio.discovery_resolver().read_many(sorted(by))
    for sid, pgs in by.items():
        text = data[sid].decode("utf-8", errors="replace")
        spans = reader.pages_of(text)
        good, bad = set(), 0
        for pg in pgs:
            k = pg.get("page")
            ok = isinstance(k, int) and 1 <= k <= len(spans) and pg.get("n_pages") == len(spans) and \
                (pg.get("char_start"), pg.get("char_end")) == spans[k - 1] and \
                pg.get("page_sha256") == sha256_bytes(text[spans[k - 1][0]:spans[k - 1][1]].encode("utf-8"))
            if ok:
                good.add(k)
            else:
                bad += 1
        out[sid] = {"n": len(spans), "have": good, "complete": good == set(range(1, len(spans) + 1)), "bad": bad}
    return out


def birth_basis(v):
    """The S-id a birth value rests on: ESTABLISHED, MOVED (first bracket argument) and BIRTH-UNRESOLVED-MTIME-ONLY."""
    m = re.match(r"(?:ESTABLISHED-[A-Z]+-BIRTH|MOVED|BIRTH-UNRESOLVED-MTIME-ONLY)\[(S\d{4})", v if isinstance(v, str) else "")
    return m.group(1) if m else None


def strip_quotes(x):
    if isinstance(x, dict):
        return {k: (None if k == "quote" else strip_quotes(v)) for k, v in x.items()}
    if isinstance(x, list):
        return [strip_quotes(v) for v in x]
    return x


def strings(x):
    if isinstance(x, dict):
        for v in x.values():
            yield from strings(v)
    elif isinstance(x, list):
        for v in x:
            yield from strings(v)
    elif isinstance(x, str):
        yield x


def read_text(path):
    with open(path, encoding="utf-8") as f:
        return f.read()


def keys_of(x):
    out = set()
    if isinstance(x, dict):
        for k, v in x.items():
            out.add(k)
            out |= keys_of(v)
    elif isinstance(x, list):
        for v in x:
            out |= keys_of(v)
    return out


def read_jsonl(path):
    out = []
    if os.path.exists(path):
        with open(path, encoding="utf-8") as f:
            for n, line in enumerate(f, 1):
                if line.strip():
                    try:
                        out.append(json.loads(line))
                    except json.JSONDecodeError as e:
                        out.append({"__parse_error__": f"{os.path.basename(path)}:{n}: {e.msg}", "__raw__": line})
    return out


def norm_term(term):
    """The Stage-2A scanner's needle for a term (A.4 normalization; casefold for multi-character terms)."""
    t = s3.norm_base(term).strip() if isinstance(term, str) else ""
    return t if len(t) <= 1 else t.casefold()


def short_term(term):
    return isinstance(term, str) and 0 < len(s3.norm_base(term).strip()) <= 2


def shape(descr):
    d = descr.strip()
    return list if d.startswith("[") else dict if d.startswith("{") else str if d == "str" else bool if d.startswith("bool") else None


def logical(root, relpath):
    """The allowlist is applied to the logical path inside C, whatever the root (tests point --root at /tmp)."""
    c.check_inputs([os.path.join(CR, relpath)])
    if any(os.path.basename(relpath) == os.path.basename(f.rstrip("/")) for f in c.FORBIDDEN if not f.endswith("/")):
        raise c.S5Error(f"forbidden input {relpath}")
    return os.path.join(root, relpath)


class Sanitizer:
    """Keeps hold-out S-ids and label names out of every report string (counts only, addendum §5). It never sees the
    hold-out lists: S-ids are tested one by one with the scanner's count-only helper, and a message that still names a
    hold-out label (c.quarantine_hits, the single addendum §3 matcher) is replaced by its tag and a withheld marker."""

    def __init__(self):
        self._hf = {}

    def is_hf(self, s):
        if s not in self._hf:
            self._hf[s] = qs.count_holdout_sids([s]) > 0
        return self._hf[s]

    def __call__(self, msg):
        msg = SID.sub(lambda m: "S####(withheld)" if self.is_hf(m.group(0)) else m.group(0), msg)
        if any(c.quarantine_hits(msg)):
            return msg.split(" ", 1)[0] + " (message withheld: it names a hold-out label; counts only)"
        return msg

    def ids(self, ids, limit=8):
        ids = sorted(ids)
        keep = [s for s in ids if not self.is_hf(s)]
        w = len(ids) - len(keep)
        return keep[:limit] + ([f"+{w} withheld"] if w else [])


# ----------------------------------------------------------------------------------------------------- verification
def load_manifest_entry(root, batch):
    path = logical(root, MANIFEST)
    if not os.path.exists(path):
        raise Refused(f"manifest {MANIFEST} not found under the root")
    lines = [l for l in read_text(path).split("\n") if l.strip()]
    hdr = json.loads(lines[0]).get("header") if lines else None
    entries = [json.loads(l) for l in lines[1:]]
    hit = [e for e in entries if e.get("batch_id") == batch]
    if len(hit) != 1:
        raise Refused(f"batch {batch}: {len(hit)} manifest entries (expected exactly 1)")
    return hdr or {}, hit[0], path


def verify(batch, root=None, run=None):
    """Return (report_body, input_paths). Raises Refused on unusable inputs."""
    root = os.path.abspath(root or CR)
    if not re.fullmatch(r"OB\d{4}", batch or ""):
        raise Refused(f"bad batch id {batch!r}")
    run_given = run
    run = run or f"{batch}-R2"
    if not re.fullmatch(re.escape(batch) + RUN_RE, run) and run not in (f"{batch}-R5", f"{batch}-R6", f"{batch}-R7"):
        raise Refused(f"bad run id {run!r} for {batch}")                                  # R5 withdrawn; R6 superseded
    san = Sanitizer()
    files = c.files_meta()                    # discovery rows only (the common module applies the filter)
    firewall = {s for s, f in files.items() if f.get("status") == "FIREWALL-LIMITED"}
    hub_list = c.hubs()
    mhdr, entry, mpath = load_manifest_entry(root, batch)
    inputs = [mpath]
    F, A, N = [], [], []                 # failures, alerts, notes (sanitized at the end)
    labels = list(entry.get("labels") or [])
    contract = entry.get("contract_sha256")
    ims = entry.get("input_manifest_sha256")
    revn_raw = (mhdr.get("contract") or {}).get("revision") or 1
    revn = revn_raw if type(revn_raw) is int else 0                       # R7 §2.5: a non-integer revision FAILS, never raises
    if revn == 0:
        F.append(f"R7-U binding: manifest contract.revision {revn_raw!r} is not an integer")
    rev3 = revn >= 3                                                      # contract revision 3: page-coverage gate
    rev4 = revn >= 4                                                      # schema revision 4 delta (pilot)
    rev6 = revn == 6                                                      # contract revision 6 (G-LOG-0083): SUPERSEDED
    rev7 = revn >= 7                                                      # contract revision 7 (G-LOG-0088)
    if revn == 5:                                                         # revision 5: never activated, withdrawn
        F.append("R6 contract revision 5 is withdrawn (superseded by revision 6; G-LOG-0083)")
    if rev6:                                                              # R7 addendum §10
        F.append("R7-U contract revision 6 is superseded by revision 7 (G-LOG-0088)")
    if rev6 and run_given is None:
        run = f"{batch}-R6"
    if rev7 and run_given is None:
        run = f"{batch}-R7"
    if run.endswith("-R6") != rev6 or run.endswith("-R5"):
        F.append(f"R6 run {run!r} does not match contract revision {revn}")
    if run.endswith("-R7") != rev7:
        F.append(f"R7-U run {run!r} does not match contract revision {revn}")
    run0 = f"{batch}-R7" if rev7 else f"{batch}-R6" if rev6 else f"{batch}-R2"
    if entry.get("run_id") not in (None, run0):
        F.append(f"SLICES manifest run_id {entry.get('run_id')!r} ≠ {run0}")
    # ---------------------------------------------------------------- 1. slices
    sdir_rel = f"{mhdr.get('slice_root', SLICE_ROOT)}/{batch}"          # revision-specific root (contract rev >= 2)
    sdir = logical(root, sdir_rel + "/")
    slices, slice_text, slice_ids = {}, {}, {}
    present = sorted(f[:-5] for f in os.listdir(sdir) if f.endswith(".json") and f != "P3B-BATCH-SNAPSHOT.json") \
        if os.path.isdir(sdir) else []
    if sorted(present) != sorted(labels):
        F.append(f"SLICES slice files ({len(present)}) ≠ manifest labels ({len(labels)}): "
                 f"missing {len(set(labels) - set(present))}, extra {len(set(present) - set(labels))}")
    for lab in labels:
        p = logical(root, f"{sdir_rel}/{lab}.json")
        if not os.path.exists(p):
            continue
        inputs.append(p)
        raw = read_text(p)
        body = raw[:-1] if raw.endswith("\n") else raw
        if sha256_bytes(body.encode("utf-8")) != (entry.get("slice_sha256") or {}).get(lab):
            F.append(f"SLICES {lab}: slice hash differs from the manifest")
        try:
            sl = json.loads(body)
        except json.JSONDecodeError:
            F.append(f"SLICES {lab}: slice is not JSON")
            continue
        slices[lab], slice_text[lab] = sl, body
        slice_ids[lab] = set(SID.findall(body))
        for k, want in (("working_label", lab), ("batch_id", batch), ("run_id", run0), ("contract_sha256", contract),
                        ("input_manifest_sha256", ims)):
            if sl.get(k) != want:
                F.append(f"SLICES {lab}: slice {k} ≠ manifest ({str(sl.get(k))[:20]!r})")
        # hub flag = list membership (plan §A.2)
        is_hub = lab in hub_list
        if sl.get("hub") is not is_hub:
            F.append(f"HUB {lab}: slice hub={sl.get('hub')!r} but list membership is {is_hub}")
        if (sl.get("hub_record") or None) != (hub_list.get(lab) if is_hub else None):
            F.append(f"HUB {lab}: slice hub_record differs from the hub list line")
        if sl.get("family_md_status") == "QUARANTINED (addendum §6)" and sl.get("family_md") != "":
            F.append(f"QUARANTINE {lab}: family_md_status QUARANTINED (addendum §6) but family_md is not empty")
    # addendum §6, independent re-detection over the materialized slice directory (every file; counts only)
    sq_l = sq_f = sq_n = 0
    for fname in (sorted(os.listdir(sdir)) if os.path.isdir(sdir) else []):
        fp = os.path.join(sdir, fname)
        if not os.path.isfile(fp):
            continue
        stem = fname[:-5] if fname.endswith(".json") else None
        nl, nf = c.quarantine_hits(read_text(fp), stem if stem in labels else None)
        sq_l, sq_f, sq_n = sq_l + nl, sq_f + nf, sq_n + (1 if nl or nf else 0)
    if sq_l or sq_f:
        F.append(f"QUARANTINE {sq_n} materialized slice-directory file(s) carry {sq_l} hold-out label name(s) and {sq_f} "
                 f"hold-out file id(s) outside the §4 mentions: seal breach → P3B-ESC (counts only)")
    if slices and bool(entry.get("hub_batch")) != all(s.get("hub") is True for s in slices.values()):
        F.append("HUB manifest hub_batch flag disagrees with the slices' hub flags")
    snap = os.path.join(sdir, "P3B-BATCH-SNAPSHOT.json")
    if os.path.exists(snap):
        inputs.append(snap)
        try:
            sb = json.loads(read_text(snap)).get("body") or {}
            if sb.get("slices") != entry.get("slice_sha256") or sb.get("contract_sha256") != contract:
                F.append("SLICES batch snapshot disagrees with the manifest entry")
        except (json.JSONDecodeError, AttributeError):
            F.append("SLICES batch snapshot unreadable")
    # ---------------------------------------------------------------- outputs and read log
    odir_rel = f"{LEDGER}/{run}"
    paths = {n: logical(root, f"{odir_rel}/{n}") for n in ("objects.jsonl", "register.jsonl", "p1-gap-capture.jsonl", "READ-LOG.jsonl")}
    for n, p in paths.items():
        if os.path.exists(p):
            inputs.append(p)
        elif n != "READ-LOG.jsonl" and n != "p1-gap-capture.jsonl" and n != "register.jsonl":
            F.append(f"G-02 {n} missing")
    objs, reg, gap = (read_jsonl(paths[n]) for n in ("objects.jsonl", "register.jsonl", "p1-gap-capture.jsonl"))
    readlog = read_jsonl(paths["READ-LOG.jsonl"])
    F += ["SCHEMA parse " + r["__parse_error__"] for r in objs + reg + gap + readlog if "__parse_error__" in r]
    unparsed = [r["__raw__"] for r in objs + reg + gap + readlog if "__raw__" in r]
    objs = [o for o in objs if "__parse_error__" not in o]
    reg = [r for r in reg if "__parse_error__" not in r]
    gap = [g for g in gap if "__parse_error__" not in g]
    rlog = [e for e in readlog if "__parse_error__" not in e]
    r7res = None
    if rev7 and not run.endswith("-R7"):         # mismatch already recorded as an R7 failure; probe no path
        r6_fail, r6_cov, blog = [], {}, []
        foreign = list(rlog)
    elif rev7:                                   # contract revision 7: the composer of bounded-context predicates
        _r7s = importlib.util.spec_from_file_location("p3b_s5_r7_verify", os.path.join(_HERE, "p3b_s5_r7_verify.py"))
        r7v = importlib.util.module_from_spec(_r7s)
        _r7s.loader.exec_module(r7v)
        r7res = r7v.verify_r7(batch, root, mhdr, entry, labels, slices, objs, reg, gap, c.dio.discovery_resolver, files,
                              logical, c.quarantine_hits, R7_SEAL_CHECK[0], R7_CORPUS_CHECK[0], R7_ARCHIVE[0],
                              mhdr.get("slice_root", SLICE_ROOT))
        r6_fail, r6_cov = [], r7res["coverage_by_label"]
        # historical gates consume WITNESSED corpus-reader reads (never the agent READ-LOG, never input reads)
        blog = [{"run_id": e["run"], "batch_id": e["batch"], "working_label": e["label"], "step": e["step"],
                 "source_ids": [e["source_id"]], "refused": False,
                 "bytes": {e["source_id"]: e["byte_end"] - e["byte_start"]},
                 "page": {k: e[k] for k in ("page", "n_pages", "byte_start", "byte_end", "page_sha256")}}
                for e in r7res["reads"]]
        foreign = []
    elif rev6 and not run.endswith("-R6"):       # mismatch already recorded as an R6 failure; probe no path
        r6_fail, r6_cov, blog = [], {}, []
        foreign = list(rlog)
    elif rev6:                                   # contract revision 6: independent derivation (p3b_s5_r6_verify)
        _r6s = importlib.util.spec_from_file_location("p3b_s5_r6_verify", os.path.join(_HERE, "p3b_s5_r6_verify.py"))
        r6v = importlib.util.module_from_spec(_r6s)
        _r6s.loader.exec_module(r6v)
        r6_fail, blog, r6_cov = r6v.verify_r6(batch, root, mhdr.get("slice_root", SLICE_ROOT), entry, labels, slices,
                                              objs, reg, gap, c.dio.discovery_resolver, files, logical,
                                              c.quarantine_hits, None)
        foreign = []
    else:
        r6_fail, r6_cov = [], {}
        foreign = [e for e in rlog if e.get("batch_id") != batch or e.get("run_id") != run]
        blog = [e for e in rlog if e.get("batch_id") == batch and e.get("run_id") == run]
    if foreign:
        A.append(f"READ-LOG {len(foreign)} entries name another batch or run (ignored)")
    ok_reads = [e for e in blog if not e.get("refused")]
    cov = page_coverage(ok_reads) if rev3 and not (rev6 or rev7) else {}
    if rev3 and not (rev6 or rev7) and any(v["bad"] for v in cov.values()):
        F.append(f"READ-COVERAGE {sum(v['bad'] for v in cov.values())} logged page(s) fail hash / span verification")
    unassigned = collections.Counter(e.get("working_label") for e in blog if e.get("working_label") not in labels)
    if unassigned:
        A.append(f"CONTRACT-DEVIATION {sum(unassigned.values())} read-log entries for labels not assigned to the batch "
                 f"(§22: for the auditor)")
    # ---------------------------------------------------------------- 2. G-02
    olabs = [o.get("working_label") for o in objs]
    if sorted(l for l in olabs if isinstance(l, str)) != sorted(labels) or len(olabs) != len(labels):
        dup = sum(c_ - 1 for c_ in collections.Counter(olabs).values() if c_ > 1)
        F.append(f"G-02 object labels differ from the assignment (missing {len(set(labels) - set(olabs))}, "
                 f"unknown {len(set(olabs) - set(labels))}, repeated {dup})")
    for r in reg + gap:
        if r.get("working_label") not in labels:
            F.append(f"G-02 {r.get('rs_id') or r.get('gap_id')}: working_label not assigned to the batch")
    # register support sets
    sl_labels = {r.get("working_label") for r in reg if r.get("kind") == "SCHEMA-LIMITATION"}
    statements = [" ".join(r["statement"].split()) for r in reg if isinstance(r.get("statement"), str) and len(r["statement"].strip()) >= 40]
    gaps_by_label = collections.defaultdict(list)
    for r in reg:
        if r.get("kind") == "GAP":
            gaps_by_label[r.get("working_label")].append(r)
    obj_keys = [k for k in SCHEMA["object_record"] if k != "checklist_examined"]
    cost, disp_total, occ_actual, occ_pred = {}, collections.Counter(), 0, 0
    step_label_notes = []
    for o in objs:
        lab = o.get("working_label")
        if lab not in slices:
            continue
        sl = slices[lab]
        meta = sl.get("source_meta") or {}
        tracks = sl.get("source_tracks") or {}
        prov = lambda s: (meta.get(s) or {}).get("provenance") or "UNKNOWN"
        hub = sl.get("hub") is True
        # ------------------------------------------------ SCHEMA (exact keys + shapes)
        keys = set(o)
        miss = [k for k in obj_keys if k not in keys]
        extra = sorted(keys - set(SCHEMA["object_record"]))
        if miss:
            F.append(f"SCHEMA {lab}: missing {miss}")
        if extra:
            F.append(f"SCHEMA {lab}: keys outside the schema {extra[:6]}")
        for k, descr in SCHEMA["object_record"].items():
            t = shape(descr)
            if t and k in o and o[k] is not None and not isinstance(o[k], t):
                F.append(f"SCHEMA {lab}: {k} is not a {t.__name__}")
        if o.get("hub") is not sl.get("hub"):
            F.append(f"HUB {lab}: record hub={o.get('hub')!r} ≠ slice hub={sl.get('hub')!r}")
        ck = o.get("checklist_examined")
        if sl.get("in_checklist"):
            if not isinstance(ck, list) or sorted(set(x for x in ck if isinstance(x, int))) != list(range(1, 24)):
                F.append(f"G-09 {lab}: in_checklist but checklist_examined is not the full 1..23")
        elif ck:
            F.append(f"SCHEMA {lab}: checklist_examined on a label not in the checklist")
        # ------------------------------------------------ G-07 / G-11
        if (o.get("run_id"), o.get("batch_id"), o.get("contract_sha256"), o.get("input_manifest_sha256")) != \
                (run, batch, contract, sl.get("input_manifest_sha256")) or "generation_parameters" not in o:
            F.append(f"G-07 {lab}: provenance ids")
        if not model_ok(o.get("model_id")):
            F.append(f"G-07 {lab}: model_id {str(o.get('model_id'))[:30]!r} is not {c.MODEL_ID} (model rule)")
        if o.get("record_status") != "PROPOSED":
            F.append(f"G-11 {lab}: record_status {o.get('record_status')!r}")
        for fld, val in (("proposed_by", "AI-AGENT"), ("evidence_presentation", "V1-PLUS-ROWS")):
            if o.get(fld) != val:
                F.append(f"G-05 {lab}: {fld}={o.get(fld)!r}")
        if any(k in o for k in ("status", "STATUS", "corpus_status")):
            F.append(f"G-09 {lab}: STATUS field")
        # ------------------------------------------------ escalations
        escs = [x for x in (o.get("escalations") or []) if isinstance(x, dict)]
        if len(escs) != len(o.get("escalations") or []):
            F.append(f"SCHEMA {lab}: escalations entry not an object")
        for x in escs:
            if not {"field", "reason", "detail"} <= set(x) or set(x) - {"field", "reason", "detail", "hub_record"}:
                F.append(f"SCHEMA {lab}: escalation keys {sorted(x)}")
            if x.get("reason") not in E["escalations[].reason"]:
                F.append(f"G-05 {lab}: escalation reason {str(x.get('reason'))[:40]!r}")
        esc_by_field = collections.defaultdict(list)
        for x in escs:
            esc_by_field[x.get("field")].append(x)
        sl_esc = {x.get("field") for x in escs if x.get("reason") == "SCHEMA-LIMITATION"}
        if sl_esc and lab not in sl_labels:
            F.append(f"G-09 {lab}: SCHEMA-LIMITATION escalation without a SCHEMA-LIMITATION register record")
        load_esc = [x for x in escs if x.get("reason") == "LOAD"]
        if load_esc and not hub:
            F.append(f"HUB {lab}: LOAD escalation on a non-hub label")
        # ------------------------------------------------ G-05 statuses
        for fld in ("type_status", "mathematical_status"):
            v = o.get(fld)
            if v not in E[fld] or (v is None and fld not in sl_esc):
                F.append(f"G-05 {lab}: {fld}={v!r}" + (" (null without a SCHEMA-LIMITATION escalation, G-LOG-0023 b)" if v is None else ""))
        for fld, key in (("primary_layer", "primary_layer"), ("tier", "tier")):
            if o.get(fld) not in E[key]:
                F.append(f"G-05 {lab}: {fld}={o.get(fld)!r}")
        if o.get("tier") != sl.get("tier"):
            F.append(f"G-05 {lab}: tier {o.get('tier')!r} ≠ slice tier {sl.get('tier')!r}")
        for role in o.get("secondary_roles") or []:
            if role not in E["secondary_roles[]"]:
                F.append(f"G-05 {lab}: secondary_role {role!r}")
        for ed in o.get("dependency_edges") or []:
            if not isinstance(ed, dict) or ed.get("kind") not in E["dependency_edges[].kind"]:
                F.append(f"G-05 {lab}: edge {str(ed)[:60]}")
        pair_ids = {p.get("pair_id") for p in sl.get("p3a_pairs") or []} | set(sl.get("pairs_touching_missing") or [])
        want_pb = dict(collections.Counter(f"{p.get('relationship')}|{p.get('basis')}" for p in sl.get("p3a_pairs") or []))
        if o.get("pair_breakdown") != want_pb:
            F.append(f"G-05 {lab}: pair_breakdown differs from the slice's consumed pairs")
        if set(o.get("tier_causing_pair_ids") or []) - pair_ids:
            F.append(f"G-05 {lab}: tier_causing_pair_ids not among the slice's pairs")
        # ------------------------------------------------ timeline, F1
        tl = [tp for tp in (o.get("timeline") or []) if isinstance(tp, dict)]
        tl_keys = {"source_id", "historical_position", "date_basis", "order", "states", "change_vs_previous", "date_applies_to_file"}
        for tp in tl:
            sid = tp.get("source_id")
            if set(tp) != tl_keys:
                F.append(f"SCHEMA {lab}: timeline[{sid}] keys {sorted(set(tp) ^ tl_keys)}")
            st = tp.get("states")
            if not isinstance(st, dict) or st.get("epistemic_class") not in ("SOURCE", "INFERENCE"):
                F.append(f"G-05 {lab}: timeline[{sid}].states.epistemic_class not SOURCE|INFERENCE")
            for fld, key in (("order", "timeline[].order"), ("date_applies_to_file", "timeline[].date_applies_to_file"),
                             ("change_vs_previous", "timeline[].change_vs_previous")):
                v = tp.get(fld)
                escaped = v is None and f"timeline[{sid}].{fld}" in sl_esc          # rule (b), G-LOG-0023
                if v not in E[key] and not escaped:
                    F.append(f"G-05 {lab}: timeline[{sid}] {fld}={v!r}")
            if tp.get("date_applies_to_file") == "NOT-CONFIRMED" and tp.get("order") is None \
                    and f"timeline[{sid}].order" not in sl_esc:
                F.append(f"F1 {lab}: NOT-CONFIRMED point {sid} has no order value and no rule-(b) escalation")
        confirmed = {tp.get("source_id") for tp in tl if tp.get("date_applies_to_file") == "CONFIRMED"}
        # ------------------------------------------------ births, G-08, §11.2 minimums
        births = o.get("births") if isinstance(o.get("births"), dict) else {}
        if set(births) != set(KINDS):
            F.append(f"SCHEMA {lab}: births keys {sorted(births)}")
        for kind in KINDS:
            v = births.get(kind)
            if not birth_ok(kind, v):
                F.append(f"G-05 {lab}: births.{kind}={str(v)[:70]!r}")
            s = birth_basis(v)
            if s and prov(s) != "PRIMARY":
                F.append(f"G-08 {lab}: births.{kind} rests on {s} ({prov(s)}; slice source_meta)")
            if s and str(v).startswith("ESTABLISHED") and s not in confirmed:
                F.append(f"G-04 {lab}: ESTABLISHED birth {kind} on {s} without a CONFIRMED timeline point (§14.2)")
            if s and str(v).startswith("BIRTH-UNRESOLVED"):
                m = meta.get(s) or {}
                if m.get("best_historical_date_basis") != "MTIME" or str(m.get("mtime_block") or "").startswith("BULK-") \
                        or s in confirmed:
                    F.append(f"G-04 {lab}: BIRTH-UNRESOLVED-MTIME-ONLY[{s}] without MTIME basis / NOT-CONFIRMED / no BULK block (§11.2)")
            g8 = re.fullmatch(r"ESCALATED\[G-08: sole basis (S\d{4}) is (\S+)\]", str(v))
            if g8 and g8.group(2) != prov(g8.group(1)):
                F.append(f"G-08 {lab}: births.{kind} names {g8.group(1)} as {g8.group(2)}, slice source_meta says {prov(g8.group(1))}")
        for fld, sb in (o.get("status_basis") or {}).items():
            if not isinstance(sb, dict) or not sb.get("anti_projection"):
                F.append(f"G-08 {lab}: status_basis.{fld} without an anti_projection answer")
            elif sb.get("epistemic_class") in LAYER_BC_CLASSES:
                F.append(f"G-12 {lab}: status_basis.{fld} rests on layer-B/C class {sb.get('epistemic_class')}")
        # ------------------------------------------------ G-12
        txt, ptxt = json.dumps(o, ensure_ascii=False), json.dumps(strip_quotes(o), ensure_ascii=False)
        rsid = re.compile(r"\b(?:" + re.escape(run) + r":)?" + re.escape(batch) + r":\d+\b")
        m = rsid.search(txt) or POINTER.search(ptxt)
        if m:
            F.append(f"G-12 {lab}: register id or pointer in layer A ({m.group(0)!r})")
        if "research_time" in keys_of(o):
            F.append(f"G-12 {lab}: research_time in a layer-A record")
        nq = [" ".join(s.split()) for s in strings(strip_quotes(o))]
        for st in statements:
            if any(st in s for s in nq):
                F.append(f"G-12 {lab}: register statement text in a layer-A field")
                break
        mf = FID.search(ptxt)
        if mf:
            F.append(f"G-01 {lab}: F-id {mf.group(0)} outside a quote")
        # ------------------------------------------------ semantic_status
        mech = sl.get("semantic_status_mechanical") or {}
        ss, rule, note = o.get("semantic_status"), o.get("semantic_status_rule"), o.get("semantic_status_note")
        sev = o.get("semantic_evidence") if isinstance(o.get("semantic_evidence"), dict) else {}
        if set(sev) != {"d4", "d5"}:
            F.append(f"SCHEMA {lab}: semantic_evidence keys {sorted(sev)}")
        if rule not in E["semantic_status_rule"]:
            F.append(f"G-05 {lab}: semantic_status_rule {rule!r}")
        if sl.get("tier") == "Z":
            if ss is not None or rule != "ROW-0" or note != TIER_Z_NOTE:
                F.append(f"SEMANTIC {lab}: Tier Z must be null / ROW-0 / the H-11a note")
        else:
            if rule == "ROW-0" and mech.get("rule") != "ROW-0":
                F.append(f"SEMANTIC {lab}: Tier U with rule ROW-0 (slice mechanical rule {mech.get('rule')})")
            elif rule == "ROW-0" and (ss is not None or note != mech.get("note")):
                F.append(f"SEMANTIC {lab}: ROW-0 value/note differ from the slice's mechanical result")
            if rule in ("ROW-3", "ROW-4") and (rule != mech.get("rule") or ss != mech.get("value")):
                F.append(f"SEMANTIC {lab}: {rule} value differs from the slice's semantic_status_mechanical")
            for r_, want, ev in (("ROW-1", "CONTESTED", "d4"), ("ROW-2", "HOMONYM-SPLIT", "d5")):
                if rule == r_:
                    items = [x for x in sev.get(ev) or [] if isinstance(x, dict) and x.get("source_id") and x.get("quote")]
                    if ss != want or not items:
                        F.append(f"SEMANTIC {lab}: {r_} needs value {want} and {ev} evidence with source and quote")
        if ss == "CONTESTED":
            d4s = [x.get("source_id") for x in sev.get("d4") or [] if isinstance(x, dict)]
            if not any(prov(s) == "PRIMARY" for s in d4s):
                F.append(f"G-08 {lab}: CONTESTED rests only on non-PRIMARY or unknown-provenance sources")
        # ------------------------------------------------ G-04 dispositions, F3
        srec = sl.get("search_records") or []
        dims = {r["dimension"]: r for r in srec if r.get("record") == "DIMENSION"}
        lhs = {r.get("label"): r for r in srec if r.get("record") == "LABEL-HITS"}
        own = lhs.get(lab) or {"terms": [], "raw_hits": {}, "ledger_hits": {}}
        terms = own.get("terms") or []

        def hit_bearing(dim):
            r = dims[dim]
            ref = lhs.get(r.get("hits_ref") or lab)
            if ref is None:
                return r.get("negative_label") is None
            return any((ref.get("raw_hits") or {}).values()) or any((ref.get("ledger_hits") or {}).values())
        hitdims = {d for d in dims if hit_bearing(d)}
        expect = collections.Counter()
        for s, hits in (own.get("raw_hits") or {}).items():
            for off, ti in hits:
                expect[(s, "raw", json.dumps(off), ti)] += 1
                if short_term(terms[ti] if isinstance(ti, int) and 0 <= ti < len(terms) else None):
                    occ_pred += 0 if hub else 1
        for s, hits in (own.get("ledger_hits") or {}).items():
            for anc, ti in hits:
                expect[(s, "ledger", json.dumps(anc), ti)] += 1
        le = [e for e in blog if e.get("working_label") == lab]
        lok = [e for e in le if not e.get("refused")]
        scan_occ = collections.defaultdict(set)
        for e in lok:
            if e.get("tool"):
                for s, offs in (e.get("occurrences") or {}).items():
                    scan_occ[(s, e.get("term"))] |= set(offs or [])
        whole = {s for e in lok if not e.get("tool") and e.get("step") in (1, 7) for s in e.get("source_ids", [])}
        whole1 = {s for e in lok if not e.get("tool") and e.get("step") == 1 for s in e.get("source_ids", [])}
        whole7 = {s for e in lok if not e.get("tool") and e.get("step") == 7 for s in e.get("source_ids", [])}
        got, found_hits, dim_vals = collections.Counter(), collections.defaultdict(set), collections.defaultdict(list)
        covered = collections.defaultdict(collections.Counter)
        d2ab = set()
        disp_keys = {"source_id", "hit_kind", "hit_key", "term_index", "method", "by_dimension", "reason"}
        s2d = o.get("stage2_dispositions") or []
        if hub and s2d:
            F.append(f"HUB {lab}: {len(s2d)} stage2_dispositions on a hub label (stage 2 not performed, v1.7 §11.4)")
        for d in s2d:
            if not isinstance(d, dict):
                F.append(f"SCHEMA {lab}: stage2 entry not an object")
                continue
            if not disp_keys <= set(d) or set(d) - disp_keys - {"offsets"}:
                F.append(f"SCHEMA {lab}: disposition keys {sorted(set(d) ^ disp_keys)}")
            key = (d.get("source_id"), d.get("hit_kind"), json.dumps(d.get("hit_key")), d.get("term_index"))
            got[key] += 1
            hk = d.get("hit_key")                                    # contract revision 2: exact key and JSON type
            if d.get("hit_kind") == "raw" and (type(hk) is not int):
                F.append(f"SCHEMA {lab}: raw hit_key {str(hk)[:20]!r} is {type(hk).__name__}, not an integer offset")
            if type(d.get("term_index")) is not int:
                F.append(f"SCHEMA {lab}: term_index {str(d.get('term_index'))[:10]!r} is not an integer")
            bd = d.get("by_dimension") if isinstance(d.get("by_dimension"), dict) else {}
            if set(bd) != set(dims):
                F.append(f"G-04 {lab}: hit {d.get('source_id')}@{str(d.get('hit_key'))[:30]} by_dimension keys ≠ dimensions")
            for dim, v in bd.items():
                if v not in E["stage2_dispositions[].by_dimension.<dimension>"]:
                    F.append(f"G-05 {lab}: disposition {v!r}")
                dim_vals[dim].append(v)
                disp_total[v] += 1
                if v == "FOUND":
                    found_hits[dim].add(d.get("source_id"))
            if d.get("method") not in list(E["stage2_dispositions[].method"]) + (list(SCHEMA_V4_METHODS) if rev4 else []) \
                    + (["BINARY-DECIDED"] if rev7 else []):       # v2.6-DC3: the conformance check is R7-E's (G-LOG-0099)
                F.append(f"G-05 {lab}: method {d.get('method')!r}")
            if rev4 and d.get("method") == "NOT-CONSUMED-ESCALATED":
                named = {m for x in escs if x.get("reason") == "CONTRACT-DEVIATION"
                         for m in re.findall(r"\bS\d{4}\b", str(x.get("detail") or ""))}
                if any(v != "ESCALATED" for v in (d.get("by_dimension") or {}).values()) or d.get("source_id") not in named:
                    F.append(f"G-05 {lab}: NOT-CONSUMED-ESCALATED {d.get('source_id')} needs every dimension ESCALATED and a "
                             f"CONTRACT-DEVIATION escalation naming it")
            if d.get("method") == "STAGE-2A-2B":
                ti = d.get("term_index")
                term = terms[ti] if isinstance(ti, int) and 0 <= ti < len(terms) else None
                if "FOUND" in bd.values():
                    F.append(f"G-04 {lab}: FOUND inside a STAGE-2A-2B entry ({d.get('source_id')})")
                if not short_term(term):
                    F.append(f"G-04 {lab}: STAGE-2A-2B on a term of more than 2 code points ({str(term)[:20]!r})")
                    continue
                tk = (d.get("source_id"), norm_term(term))
                if tk not in scan_occ:
                    F.append(f"G-04 {lab}: STAGE-2A-2B without a scanner log entry ({tk[0]}, {term!r})")
                offs = d.get("offsets")
                if not isinstance(offs, list) or not all(isinstance(x, int) for x in offs):
                    F.append(f"F3 {lab}: STAGE-2A-2B disposition {d.get('source_id')}@{d.get('hit_key')} without an `offsets` list")
                    continue
                d2ab.add(tk)
                covered[tk].update(offs)
        for tk in sorted(d2ab):
            logged, cov = scan_occ.get(tk, set()), covered[tk]
            miss_o, rep, unl = logged - set(cov), [x for x, n in cov.items() if n > 1], set(cov) - logged
            if miss_o or rep or unl:
                F.append(f"F3 {lab}: {tk[0]} term {tk[1]!r}: offsets cover {len(logged & set(cov))} of {len(logged)} logged "
                         f"occurrences (missing {len(miss_o)}, repeated {len(rep)}, unlogged {len(unl)})")
        for tk, logged in sorted(scan_occ.items()):
            occ_actual += len(logged)
            if logged and tk not in d2ab:
                N.append(f"{lab}: Stage-2A scan of {tk[0]} for {tk[1]!r} ({len(logged)} occurrences) has no STAGE-2A-2B disposition")
        if not hub and (set(got) != set(expect) or any(n != 1 for n in got.values())):
            F.append(f"G-04 {lab}: dispositions cover {len(set(got) & set(expect))} of {len(set(expect))} distinct hit keys "
                     f"(missing {len(set(expect) - set(got))}, extra {len(set(got) - set(expect))}, repeated {sum(1 for n in got.values() if n != 1)})")
        # ------------------------------------------------ absences
        ab = o.get("absences") if isinstance(o.get("absences"), dict) else {}
        if set(dims) - set(ab):
            F.append(f"G-04 {lab}: unresolved dimensions {sorted(set(dims) - set(ab))[:4]}")
        if set(ab) - set(dims):
            F.append(f"G-04 {lab}: absences for dimensions without a search record {sorted(set(ab) - set(dims))[:4]}")
        disagree = {x.get("dimension") for x in o.get("census_reading_disagreements") or [] if isinstance(x, dict)}
        earliest = earliest_track(tl, tracks, meta)
        ab_keys = {"resolution", "negative_label", "population_basis", "supplied_by", "reason"}
        for dim, a in ab.items():
            if not isinstance(a, dict):
                F.append(f"SCHEMA {lab}: absences.{dim} not an object")
                continue
            if not ab_keys <= set(a) or set(a) - ab_keys - AB_OPTIONAL:
                F.append(f"SCHEMA {lab}: absences.{dim} keys {sorted(set(a) ^ ab_keys)}")
            res = a.get("resolution")
            if res not in E["absences.<dimension>.resolution"]:
                F.append(f"G-05 {lab}: absences.{dim}.resolution={res!r}")
            if dim not in dims:
                continue
            if a.get("population_basis") != c.POPULATION_BASIS:
                F.append(f"G-09 {lab}: absences.{dim}.population_basis {str(a.get('population_basis'))[:30]!r}")
            neg = dims[dim].get("negative_label") == "NEGATIVE-CENSUS"
            if a.get("negative_label") != dims[dim].get("negative_label"):
                F.append(f"G-04 {lab}: absences.{dim}.negative_label ≠ the stage-1 search record")
            if dim in disagree and res != "ESCALATED":
                F.append(f"G-04 {lab}: census–reading disagreement on {dim} but resolution {res}")
            if hub and dim in hitdims:
                if res != "ESCALATED":
                    F.append(f"HUB {lab}: hit-bearing dimension {dim} resolves {res} (must be ESCALATED, LOAD)")
                if res in ("FOUND", "GENUINELY-UNDEFINED-AFTER-CENSUS"):
                    F.append(f"HUB {lab}: {res} on hit-bearing hub dimension {dim}")
                loads = [x for x in esc_by_field.get(f"absences.{dim}", []) if x.get("reason") == "LOAD"]
                if not loads:
                    F.append(f"HUB {lab}: hit-bearing dimension {dim} without a LOAD escalation")
                elif not any(hub_rec_eq(x.get("hub_record"), sl.get("hub_record")) for x in loads):
                    F.append(f"HUB {lab}: LOAD escalation for {dim} does not carry the slice's hub_record")
                continue
            if res == "FOUND":
                sb_ = a.get("supplied_by")
                if neg:
                    F.append(f"G-04 {lab}: FOUND on NEGATIVE-CENSUS dimension {dim}")
                if not (isinstance(sb_, dict) and sb_.get("source_id") and sb_.get("anchor") and sb_.get("quote")):
                    F.append(f"G-04 {lab}: FOUND {dim} without supplied_by source/anchor/quote (§11.2)")
                    continue
                s = sb_["source_id"]
                if prov(s) != "PRIMARY":
                    F.append(f"G-08 {lab}: FOUND {dim} rests on {s} ({prov(s)}; slice source_meta)")
                if s not in found_hits.get(dim, set()):
                    F.append(f"G-04 {lab}: FOUND {dim} from {s} without a FOUND hit disposition for it")
                if s not in whole:
                    F.append(f"G-04 {lab}: FOUND {dim} source {s} has no whole-file reader read (step 1 or 7)")
                elif s not in whole7:
                    step_label_notes.append(f"{lab}: FOUND source {s} read whole at step 1, not re-logged at step 7")
                if earliest == "A" and (tracks.get(s) or {}).get("d23_track") == "B" \
                        and a.get(REL_TIMING) != "LATER-THAN-FIRST-APPEARANCE":
                    F.append(f"D-23 {lab}: FOUND {dim} rests solely on Track-B source {s} over Track-A earliest evidence "
                             f"(no relative_timing LATER-THAN-FIRST-APPEARANCE)")
            if res == "GENUINELY-UNDEFINED-AFTER-CENSUS" and not neg and any(v not in UNDEF_OK for v in dim_vals.get(dim, [])):
                F.append(f"G-04 {lab}: GENUINELY-UNDEFINED {dim} but a hit is not FALSE-HIT/UNSUPPLIED")
            if res == "GENUINELY-UNDEFINED-AFTER-CENSUS" and not neg and not dim_vals.get(dim):
                F.append(f"G-04 {lab}: GENUINELY-UNDEFINED {dim} with stage-1 hits but no stage-2 dispositions")
        # ------------------------------------------------ D-23 (OMQ-07 mapping (2))
        if earliest == "A":
            b = lambda s: (tracks.get(s) or {}).get("d23_track") == "B"
            for kind in KINDS:
                s = birth_basis(births.get(kind))
                if s and not str(births.get(kind)).startswith("BIRTH-UNRESOLVED") and b(s):
                    F.append(f"D-23 {lab}: births.{kind} on Track-B source {s} for a Track-A-earliest object")
            for ed in o.get("dependency_edges") or []:
                if isinstance(ed, dict) and b(ed.get("source_id")):
                    F.append(f"D-23 {lab}: dependency edge to {str(ed.get('target_label'))[:40]} rests solely on Track-B {ed.get('source_id')}")
            for fld, sb in (o.get("status_basis") or {}).items():
                srcs = [s for s in (sb.get("sources") or []) if isinstance(s, str)] if isinstance(sb, dict) else []
                if srcs and all(b(s) for s in srcs):
                    F.append(f"D-23 {lab}: status_basis.{fld} rests solely on Track-B sources")
            if ss == "CONTESTED":
                d4s = [x.get("source_id") for x in sev.get("d4") or [] if isinstance(x, dict)]
                if d4s and all(b(s) for s in d4s):
                    F.append(f"D-23 {lab}: CONTESTED rests solely on Track-B sources")
        # ------------------------------------------------ hub read discipline
        need = set(sl.get("stage2_files") or [])
        if hub:
            bad7 = [e for e in le if e.get("step") == 7 or e.get("tool")]
            if bad7:
                F.append(f"HUB {lab}: {len(bad7)} step-7 / Stage-2A read-log entries (stage 2 not performed)")
            s10 = {s for e in lok if e.get("step") == 10 for s in e.get("source_ids", [])}
            if (s10 & need) - whole1:
                F.append(f"HUB {lab}: step-10 read of {len((s10 & need) - whole1)} stage-1 hit file(s) not read at step 1 (plan §C.1)")
            for g in gaps_by_label.get(lab, []):
                gtxt = json.dumps(g, ensure_ascii=False)
                named = {d for d in hitdims if re.search(r"(?<![A-Za-z0-9_])" + re.escape(d) + r"(?![A-Za-z0-9_])", gtxt)}
                neg_dims = set(dims) - hitdims
                if named and g.get("gap_status") != "NOT-FOUND-LOAD-ESCALATED":
                    F.append(f"HUB {g.get('rs_id')}: GAP on hit-bearing hub dimension(s) {sorted(named)[:3]} with gap_status {g.get('gap_status')!r}")
                elif g.get("gap_status") == "NOT-FOUND-AFTER-CENSUS" and not neg_dims:
                    F.append(f"HUB {g.get('rs_id')}: NOT-FOUND-AFTER-CENSUS on a hub without NEGATIVE-CENSUS dimensions")
        else:
            anyread = {s for e in lok if e.get("step") in (1, 7) and ((not e.get("tool")) or e.get("step") == 7)
                       for s in e.get("source_ids", [])}
            if r7res is not None:                 # v2.6-DC3: a CONFORMING decided binary is decided, not missing
                anyread |= set((r7res.get("binary_decided") or {}).get(lab) or [])
            if need - anyread:
                F.append(f"G-04 {lab}: {len(need - anyread)} of {len(need)} stage-2 hit files neither read whole nor scanned")
            s7 = {s for e in lok if e.get("step") == 7 for s in e.get("source_ids", [])}
            if (need - s7) & anyread:
                step_label_notes.append(f"{lab}: {len((need - s7) & anyread)} hit file(s) read whole at step 1, not re-logged at step 7")
            for g in gaps_by_label.get(lab, []):
                if g.get("gap_status") == "NOT-FOUND-LOAD-ESCALATED":
                    F.append(f"HUB {g.get('rs_id')}: NOT-FOUND-LOAD-ESCALATED on a non-hub label")
        if rev6 or rev7:                                # R5-15: re-derived here, after the STAGE-2A-2B block
            cov = r6_cov.get(lab, {})                   # revisions 6/7: only this label's own planned runs count
        # ------------------------------------------------ READ-COVERAGE (contract revision 3; G-LOG-0050)
        if rev3:
            need_cov = {}                                        # sid -> first reason it must be read whole
            for d in s2d if isinstance(s2d, list) else []:
                # frozen §11.4 / §A.6: a WHOLE-FILE disposition needs the whole file; a STAGE-2A-2B disposition (terms of
                # <= 2 code points) is the permitted alternative, except that a FOUND always needs the whole file
                if not isinstance(d, dict):
                    continue
                vals = (d.get("by_dimension") or {}).values()
                if d.get("method") == "STAGE-2A-2B":
                    if any(v == "FOUND" for v in vals):
                        need_cov.setdefault(d.get("source_id"), "FOUND from a STAGE-2A-2B file")
                elif rev7 and d.get("method") == "BINARY-DECIDED" and d.get("source_id") in \
                        set(((r7res or {}).get("binary_decided") or {}).get(lab) or []):
                    continue                   # v2.6-DC3: a CONFORMING decided binary rests on its decision, not on pages
                elif d.get("method") == "WHOLE-FILE" or any(v != "ESCALATED" for v in vals):
                    need_cov.setdefault(d.get("source_id"), "stage-2 disposition")
            for dim, a in (ab or {}).items():
                sb_ = a.get("supplied_by") if isinstance(a, dict) else None
                if isinstance(a, dict) and a.get("resolution") == "FOUND" and isinstance(sb_, dict) and sb_.get("source_id"):
                    need_cov.setdefault(sb_["source_id"], f"absences.{dim} FOUND")
            for kind in KINDS:
                v = births.get(kind)
                s = birth_basis(v)
                if s and not str(v).startswith("BIRTH-UNRESOLVED"):
                    need_cov.setdefault(s, f"births.{kind}")
            for tp in tl:
                if tp.get("change_vs_previous") in READ_CHANGE_POINTS:
                    need_cov.setdefault(tp.get("source_id"), f"timeline {tp.get('change_vs_previous')}")
            for s, why in sorted(need_cov.items(), key=lambda x: str(x[0])):
                cv = cov.get(s)
                if not (cv and cv["complete"]):
                    F.append(f"READ-COVERAGE {lab}: {s} rests on whole-file evidence ({why}) but pages "
                             f"{len(cv['have']) if cv else 0}/{cv['n'] if cv else '?'} are logged and verified")
            omq14 = sorted({json.loads(r)["source_id"] for r in (sl.get("bundle") or {}).get("rows_verbatim") or []
                            if set(json.loads(r).get("types") or []) & OMQ14_ROW_TYPES})
            cd_ids = {m for x in escs if x.get("reason") == "CONTRACT-DEVIATION"
                      for m in re.findall(r"\bS\d{4}\b", str(x.get("detail") or ""))}   # the S-id named in the detail
            for s in omq14:
                cv = cov.get(s)
                if not (cv and cv["complete"]) and s not in cd_ids:
                    F.append(f"READ-COVERAGE {lab}: OMQ-14 source {s} (CONTRADICTION/CORRECTION/RETRACTION rows) neither "
                             f"read whole (pages {len(cv['have']) if cv else 0}/{cv['n'] if cv else '?'}) nor escalated CONTRACT-DEVIATION")
        # ------------------------------------------------ step-10 label locality (alert)
        s10 = [e for e in lok if e.get("step") == 10]
        outside = {s for e in s10 for s in e.get("source_ids", [])} - slice_ids.get(lab, set())
        if outside:
            has_cd = any(x.get("reason") == "CONTRACT-DEVIATION" for x in escs)
            A.append(f"CONTRACT-DEVIATION {lab}: step-10 read of {len(outside)} file(s) outside the label's slice "
                     f"{san.ids(outside, 4)} (plan §C.1 item 1; §22: for the auditor; label "
                     f"{'carries' if has_cd else 'does not carry'} a CONTRACT-DEVIATION escalation)")
        cost[lab] = {f"step{st}": sum(sum((e.get("bytes") or {}).values()) for e in lok if e.get("step") == st) for st in (1, 7, 10)}
        cost[lab]["scanner_calls"] = sum(1 for e in lok if e.get("tool"))
        cost[lab]["file_reads_1_7"] = sum(len(e.get("source_ids", [])) for e in lok if e.get("step") in (1, 7))
        cost[lab]["predicted_file_reads"] = (0 if hub else len(need)) + len(tracks)
    # ---------------------------------------------------------------- register records (G-05/G-07/G-09/G-12)
    reg_core = [k for k in SCHEMA["register_record"] if not k.startswith("profile") and k not in ("gap_status", "proposed_topic_definitions")]
    seen_ids = collections.Counter()
    kinds = collections.Counter()
    for r in reg:
        rid = r.get("rs_id")
        seen_ids[rid] += 1
        kinds[r.get("kind")] += 1
        if not (isinstance(rid, str) and re.fullmatch(re.escape(run) + ":" + re.escape(batch) + r":\d+", rid)):
            F.append(f"G-07 {str(rid)[:40]}: rs_id not <run_id>:<batch_id>:<n>")
        miss = [k for k in reg_core if k not in r]
        if miss:
            F.append(f"SCHEMA {rid}: missing {miss}")
        for k, descr in SCHEMA["register_record"].items():
            t = shape(descr)
            if t and k in r and r[k] is not None and not isinstance(r[k], t):
                F.append(f"SCHEMA {rid}: {k} is not a {t.__name__}")
        kind = r.get("kind")
        if kind not in REG_KINDS:
            F.append(f"G-05 {rid}: kind {kind!r} (§13.2)")
        elif r.get("output_layer") != REG_KINDS[kind]:
            F.append(f"G-05 {rid}: output_layer {r.get('output_layer')!r} for kind {kind} (§13.2)")
        for fld, key in (("lifecycle_stage", "lifecycle_stage (register)"), ("lens", "lens (register)"),
                         ("scale", "scale (register)"), ("output_layer", "output_layer (register)")):
            if r.get(fld) not in E[key]:
                F.append(f"G-09 {rid}: {fld}={r.get(fld)!r}")
        if r.get("epistemic_class") not in EPISTEMIC:
            F.append(f"G-05 {rid}: epistemic_class {r.get('epistemic_class')!r}")
        if r.get("origin") != "P3B":
            F.append(f"G-05 {rid}: origin {r.get('origin')!r}")
        for ev in r.get("supporting_evidence") or []:
            if not isinstance(ev, dict) or ev.get("evidence_kind") not in ("CORPUS", "EXTERNAL-THEORY"):
                F.append(f"G-05 {rid}: supporting_evidence entry without evidence_kind CORPUS|EXTERNAL-THEORY")
                break
        if (r.get("run_id"), r.get("batch_id"), r.get("contract_sha256")) != (run, batch, contract) \
                or not model_ok(r.get("model_id")) or "generation_parameters" not in r:
            F.append(f"G-07 {rid}: provenance ids")
        if any(k in r for k in ("status", "STATUS", "corpus_status")):
            F.append(f"G-09 {rid}: STATUS field")
        if kind == "GAP" and r.get("gap_status") not in E["gap_status (register kind GAP)"]:
            F.append(f"G-09 {rid}: GAP without a §13.4 gap_status ({r.get('gap_status')!r})")
        if kind in ("HYPOTHESIS", "STRUCTURE-CANDIDATE"):
            if r.get("lifecycle_stage") != "TEST-DEFINED":
                F.append(f"G-09 {rid}: {kind} not at TEST-DEFINED")
            m2 = [k for k in PROFILE if k not in r]
            if m2:
                F.append(f"G-09 {rid}: missing {m2}")
        if kind in ("SUGGESTION-RESEARCH", "SUGGESTION-METHOD", "METHODOLOGICAL-DEFICIENCY") and "execution_impact" not in r:
            N.append(f"{rid}: {kind} without execution_impact (G-09; field not in contract section E, reported only)")
        if r.get("lifecycle_stage") == "TEST-DEFINED" and not (isinstance(r.get("test_plan_sha256"), str) and HEX64.fullmatch(r["test_plan_sha256"])):
            F.append(f"G-09 {rid}: TEST-DEFINED without a sha256 test_plan_sha256")
        defs = r.get("proposed_topic_definitions") or {}
        for tp in r.get("topics") or []:
            if isinstance(tp, str) and tp.startswith("PROPOSED:") and not (isinstance(defs, dict) and defs.get(tp)):
                F.append(f"G-12 {rid}: {tp} without a definition")
        if not r.get("historical_anchor") or not r.get("research_time"):
            F.append(f"G-12 {rid}: historical_anchor and research_time both required")
    if any(n > 1 for n in seen_ids.values()):
        F.append("G-07 repeated rs_id values")
    # ---------------------------------------------------------------- P1-gap records
    gap_keys = set(SCHEMA["p1_gap_record"])
    gseen = collections.Counter()
    for g in gap:
        gid = g.get("gap_id")
        gseen[gid] += 1
        if set(g) != gap_keys:
            F.append(f"SCHEMA {gid}: keys differ from the schema (missing {sorted(gap_keys - set(g))}, extra {sorted(set(g) - gap_keys)[:4]})")
        if not (isinstance(gid, str) and re.fullmatch(re.escape(run) + ":" + re.escape(batch) + r":G\d+", gid)):
            F.append(f"G-07 {str(gid)[:40]}: gap_id not <run_id>:<batch_id>:G<n>")
        if (g.get("run_id"), g.get("batch_id"), g.get("contract_sha256")) != (run, batch, contract) \
                or not model_ok(g.get("model_id")):
            F.append(f"G-07 {gid}: provenance ids")
        if g.get("found_via") not in ("STAGE-2", "STEP-1-READING"):
            F.append(f"G-05 {gid}: found_via {g.get('found_via')!r}")
        lab = g.get("working_label")
        if lab in slices and slices[lab].get("hub") is True and g.get("found_via") == "STAGE-2":
            F.append(f"HUB {gid}: P1-gap found via STAGE-2 on a hub label")
    if any(n > 1 for n in gseen.values()):
        F.append("G-07 repeated gap_id values")
    # ---------------------------------------------------------------- G-01 / AUDIT citations
    cited = set()
    for r in objs + reg + gap:
        cited |= set(SID.findall(json.dumps(r)))
    all_slice_ids = set().union(*slice_ids.values()) if slice_ids else set()
    read_ok = {s for e in ok_reads for s in e.get("source_ids", [])}
    if cited - all_slice_ids - read_ok:
        F.append(f"AUDIT cited S-ids neither in the batch's slices nor in its non-refused reads: {san.ids(cited - all_slice_ids - read_ok)}")
    unknown = {s for s in cited if s not in files}
    if unknown:
        F.append(f"G-01 S-ids not in 02-FILES (discovery rows): {san.ids(unknown)}")
    ev = set()

    def ev_walk(x, key=None):
        if isinstance(x, dict):
            if x.get("source_id") and ("quote" in x or key in ("supplied_by", "timeline", "semantic_evidence", "d4", "d5",
                                                               "dependency_edges", "supporting_evidence")):
                ev.add(x["source_id"])
            for k, v in x.items():
                ev_walk(v, k)
        elif isinstance(x, list):
            for v in x:
                ev_walk(v, key)
    for r in objs + reg + gap:
        ev_walk(r)
        for v in (r.get("births") or {}).values() if isinstance(r.get("births"), dict) else []:
            b_ = birth_basis(v)
            if b_:
                ev.add(b_)
    if ev & firewall:
        F.append(f"G-01 FIREWALL-LIMITED file used as evidence: {san.ids(ev & firewall)}")
    # ---------------------------------------------------------------- QUARANTINE (counts only)
    ql = qf = 0
    for r in objs + reg + gap:
        nl, nf = c.quarantine_hits(json.dumps(r, ensure_ascii=False), r.get("working_label"))
        ql, qf = ql + nl, qf + nf
    for raw in unparsed:                                  # lines that are not JSON are scanned as text, no label context
        nl, nf = c.quarantine_hits(raw, None)
        ql, qf = ql + nl, qf + nf
    qr = qs.count_holdout_sids(sorted(read_ok))
    if ql or qf or qr:
        F.append(f"QUARANTINE outputs carry {ql} hold-out label name(s) and {qf} hold-out file id(s); {qr} non-refused "
                 f"read(s) of hold-out files: seal breach → P3B-ESC (counts only)")
    # ---------------------------------------------------------------- alerts (plan §B.3, §C.1)
    pred = entry.get("predicted") or {}
    act = {"stage2_bytes": sum(sum((e.get("bytes") or {}).values()) for e in ok_reads if e.get("step") == 7),
           "stage1_bytes": sum(sum((e.get("bytes") or {}).values()) for e in ok_reads if e.get("step") == 1),
           "reader_calls": sum(len(e.get("source_ids", [])) for e in ok_reads if e.get("step") in (1, 7)),
           "stage2b_occurrences": occ_actual}
    predicted = {"stage2_bytes": pred.get("stage2_bytes"), "stage1_bytes": pred.get("stage1_bytes"),
                 "reader_calls": sum(v["predicted_file_reads"] for v in cost.values()), "stage2b_occurrences": occ_pred}
    ratios = {}
    for k in ("stage2_bytes", "stage1_bytes", "reader_calls", "stage2b_occurrences"):
        p_, a_ = predicted[k], act[k]
        ratios[k] = round(a_ / p_, 4) if isinstance(p_, (int, float)) and p_ else None
        if isinstance(p_, (int, float)) and ((p_ and a_ / p_ > ALERT_RATIO) or (not p_ and a_)):
            A.append(f"LOAD {k}: actual {a_} vs predicted {p_} (ratio {ratios[k] if ratios[k] is not None else 'n/a'} > {ALERT_RATIO}; plan §B.3)")
    s10_entries = [e for e in ok_reads if e.get("step") == 10]
    s10_bytes = sum(sum((e.get("bytes") or {}).values()) for e in s10_entries)
    s10_files = [s for e in s10_entries for s in e.get("source_ids", [])]
    if s10_bytes > STEP10_CAP:
        A.append(f"RESEARCH-READ step-10 bytes {s10_bytes} > the {STEP10_CAP}-byte cap (plan §C.1 item 2; alert only)")
    F += r6_fail                                                 # empty for revisions < 6
    preds, verdict = None, None
    if rev7:                                                     # R7 §0: compose predicates; the historical gates are
        by_pred = {"U": [], "W": [], "E": [], "R": []}           # assigned to the context that owns them
        no_witness = r7res is not None and r7res["predicates"]["W"] == "U"
        for x in F:
            t = x.split(" ", 1)[0]
            if no_witness and t in ("G-04", "READ-COVERAGE", "AUDIT"):
                N.append("UNDETERMINED (no witness): " + x)          # read-dependent gate without its input → U, never F
                continue
            p = "U" if t in ("SLICES", "SCHEMA", "G-01", "G-02", "G-07", "R7-U") else \
                "E" if t in ("READ-COVERAGE", "AUDIT") else "R"
            by_pred[p].append(x)
        if r7res is None:
            by_pred["U"].append("R7-U run/contract mismatch: the R7 contexts were not evaluated")
            preds = {k: ("F" if v else "U") for k, v in by_pred.items()}
        else:
            preds = dict(r7res["predicates"])
            for k in by_pred:
                by_pred[k] = r7res["failures"][k] + by_pred[k]
                if by_pred[k] and not (k == "W" and preds[k] == "U" and all("UNVERIFIABLE-WITNESS" in y for y in by_pred[k])):
                    preds[k] = "F"
        verdict = r7v.compose(preds) if r7res is not None else "BATCH-FAIL"
        F = [x for k in ("U", "W", "E", "R") for x in by_pred[k]]
    # ---------------------------------------------------------------- report
    F = [san(x) for x in F]
    A = [san(x) for x in A]
    N = list(dict.fromkeys(san(x) for x in N + step_label_notes))
    tags = ["SLICES", "SCHEMA", "G-01", "G-02", "G-04", "G-05", "G-07", "G-08", "D-23", "G-09", "G-11", "G-12", "HUB",
            "F1", "F3", "SEMANTIC", "AUDIT", "QUARANTINE"]
    gates = {t: ("FAIL" if any(x.startswith(t + " ") for x in F) else "PASS") for t in tags}
    gates.update({"G-03": "NOT-SCRIPT-CHECKED (no mechanical duplicate-corroboration field at batch level)",
                  "G-06": "AUDIT (§21)", "G-10": "PARTIAL (slice hashes = SLICES)", "G-13": "PASS-LEVEL (S5a/S5b)"})
    if revn in (5, 6):                                           # the key exists only for revision 5/6 reports
        gates["R6"] = "FAIL" if any(x.startswith("R6 ") for x in F) else "PASS"
    if rev7:
        gates["R7"] = verdict
    comparison = run.endswith("-R2S")
    result = verdict if rev7 else ("PASS" if not F else "FAIL")      # R7: no bare PASS (DR-15)
    body = {"batch_id": batch, "run_id": run, "comparison": comparison, "acceptance_evidence": not comparison,
            "relative_timing_key_source": REL_TIMING_SOURCE, "result": result, "failures": F, "alerts": A,
            "notes": N, "gates": gates, "labels": labels,
            "counts": {"objects": len(objs), "research_records": len(reg), "p1_gap_records": len(gap),
                       "records_by_kind": dict(sorted((str(k), v) for k, v in kinds.items())),
                       "read_log_entries": len(blog), "refused_reads": sum(1 for e in blog if e.get("refused")),
                       "dispositions_per_hit_dimension": dict(sorted(disp_total.items()))},
            "load": {"actual": act, "predicted": predicted, "ratios": ratios, "alert_ratio": ALERT_RATIO,
                     "manifest_predicted_dispositions": pred.get("dispositions")},
            "research_reads": {"step10_bytes": s10_bytes, "cap": STEP10_CAP,
                               # exhaustion is recorded here (batch report), never expected as a register
                               # value (contract; plan §C.1 item 5)
                               "cap_reached": s10_bytes >= STEP10_CAP,
                               "step10_file_reads": len(s10_files),
                               "step10_distinct_files": len(set(s10_files)),
                               "repeat_ratio": round(len(s10_files) / len(set(s10_files)), 4) if s10_files else None},
            "reading_bytes": cost, "manifest_output_sha256": mhdr.get("output_sha256")}
    if rev7:
        body["predicates"] = preds
    return body, inputs


def hub_rec_eq(v, rec):
    if isinstance(v, dict):
        return v == rec
    if isinstance(v, str):
        try:
            return json.loads(v) == rec
        except json.JSONDecodeError:
            return False
    return False


def earliest_track(tl, tracks, meta):
    """d23_track of the object's earliest evidence: the first timeline point (§14.3 historical order); without a
    timeline, the earliest-dated source among the slice's source_tracks (ties by S-id)."""
    first = next((tp.get("source_id") for tp in tl if tp.get("source_id")), None)
    if first is None:
        dated = sorted((c.to_date((meta.get(s) or {}).get("best_historical_date")), s) for s in tracks
                       if c.to_date((meta.get(s) or {}).get("best_historical_date")))
        first = dated[0][1] if dated else None
    return (tracks.get(first) or {}).get("d23_track") if first else None


def out_path(root, batch, run):
    name = f"S5-VERIFY-{batch}.json" if run == f"{batch}-R2" else f"S5-VERIFY-{run}.json"
    return os.path.join(root, "audit-p3b", name)


def main(argv=None):
    argv = list(sys.argv[1:] if argv is None else argv)
    opts = {}
    i = 0
    while i < len(argv):
        if argv[i] in ("--batch", "--run", "--root", "--out") and i + 1 < len(argv):
            opts[argv[i][2:]] = argv[i + 1]
            i += 2
        else:
            print(f"unknown argument {argv[i]!r}\n" + __doc__.split("\n\n")[1], file=sys.stderr)
            return 2
    if "batch" not in opts:
        print("usage: p3b_s5_verify.py --batch OB#### [--run OB####-R2.2|OB####-R2S] [--root DIR] [--out FILE]", file=sys.stderr)
        return 2
    root = os.path.abspath(opts.get("root") or CR)
    batch = opts["batch"]
    run = opts.get("run") or f"{batch}-R2"
    try:
        c.verify_frozen()
        c.assert_sealed()
        body, inputs = verify(batch, root=root, run=run)
        out = os.path.abspath(opts.get("out") or out_path(root, batch, run))
        if out.startswith(CR + os.sep):
            c.check_inputs([out])
        if os.path.exists(out):
            raise Refused(f"{out} exists (audit-p3b is append-only; pass --out for a new name)")
        body_text = canon(body)
        if any(c.quarantine_hits(body_text)):
            raise Refused("report text failed the quarantine scan; nothing written")
        rel_inputs = [os.path.relpath(p, CR) if os.path.abspath(p).startswith(CR + os.sep) else os.path.abspath(p) for p in inputs]
        rel_inputs += ["02-FILES.jsonl", c.HUBS, "P3B-HOLDOUT-SEAL.json", c.PROTOCOL, c.PLAN]
        hdr = c.header(__file__, sorted(set(rel_inputs)), {"batch": batch, "run": run, "alert_ratio": ALERT_RATIO,
                                                           "step10_cap": STEP10_CAP, "root": "C" if root == CR else "EXTERNAL"},
                       body_text, {"artifact": os.path.basename(out), "result": body["result"]})
        c.assert_sealed()
        os.makedirs(os.path.dirname(out), exist_ok=True)
        with open(out, "x", encoding="utf-8") as f:
            f.write(json.dumps({"header": hdr, "body": body}, ensure_ascii=False, indent=1, sort_keys=True) + "\n")
    except (Refused, c.S5Error, OSError) as e:
        print(f"REFUSED: {e}", file=sys.stderr)
        return 2
    print(f"S5 VERIFY {batch} run {run}: {body['result']} — {len(body['failures'])} failure(s), {len(body['alerts'])} alert(s); "
          f"objects {body['counts']['objects']} research {body['counts']['research_records']} gaps {body['counts']['p1_gap_records']}; "
          f"report {out} (body sha {hdr['output_sha256'][:16]})")
    for x in body["failures"][:20]:
        print("   FAIL", x)
    for x in body["alerts"][:10]:
        print("   ALERT", x)
    return 0 if body["result"] in ("PASS", "BATCH-PASS") else 1


if __name__ == "__main__":
    sys.exit(main())
