#!/usr/bin/env python3
"""P3b S5 contract revision 5 (§26; G-LOG-0082): the deterministic machinery of the S5 technical decisions A–I, B′.

Drafted, implemented and tested behind the revision-5 gate. **Not activated:** activation (manifest regeneration with
the revision-5 contract, the production plan, the binary decision records and the audit sample) is a separate human
act after the independent audit. This module reads no corpus content by itself. Functions that take content bytes
(binary pre-classification, archive member seal check, byte-page coverage) are pure functions of their arguments;
only the activation runbook, under authorization, feeds them corpus bytes through the seal-aware resolver.

Items of the act (numbers as in G-LOG-0082) and where they live here:
  1–3  two-path dispatch, decomposition only above the budget, label-local row-first + FFD fill   dispatch_path, partition
  4    all-blob sizing (git objects and PRESERVED-AUDIT-COPY blobs)                                 all_blob_sizes
  5    packing key with NO chronological standing                                                   packing_key
  6–7  binary pre-classification; member-level seal check for archives                              binary_preclassify,
                                                                                                    archive_member_seal
  8–9  versioned byte-bounded pages (UTF-8, 24,000 B), binary refusal, acknowledgement token          byte_pages, is_binary,
                                                                                                    ack_token, byte_page_record
  10   reading_state and the one-directional rule                                                    reading_state_violations
  11   edge classes R1-STRUCTURAL / R2-EVIDENCED                                                     edge_class_violations
  12   four-level scan taxonomy                                                                      scan_level
  13   S1 unit-level FILE CONTENT <-> PAIR CLAIM checks                                              required_pair_checks,
                                                                                                    s1_violations
  14   S2 birth row-membership                                                                       s2_violations
  15   S3 synthesis-output lint                                                                      s3_lint
  16   empty-required-set treatment (R17)                                                            empty_label_violations
  17–18 pre-registered probability audit; ML queues excluded from the estimate                        draw_audit_sample,
                                                                                                    ht_estimate, zero_bound
"""
import hashlib
import json
import math
import os
import random
import re

REVISION = 5
BUDGET = 600_000                      # decision A/B: context budget per reading unit (bytes)
PAGE_BYTES = 24_000                   # decision D: byte-bounded page (headroom under the ~30 KB display limit)
READER_MODE = "bytes-r5"
READING_STATES = ("WHOLE-FILE", "READ-PARTIAL", "READ-FAILED", "NOT-CONSUMED")
EDGE_CLASSES = ("R1-STRUCTURAL", "R2-EVIDENCED")
PAIR_SUPPORT = ("YES", "NO", "NOT-DETERMINABLE")
BINARY_DECISIONS = ("FALSE-HIT", "NOT-CONSUMED-ESCALATED", "EXTRACT")
EMPTY_MARK = "EMPTY-REQUIRED-SET"
VEC = "VERDICT-EVIDENCE-CONFLICT"
SIG = {b"\x89PNG\r\n\x1a\n": "PNG", b"PK\x03\x04": "ZIP", b"GIF8": "GIF", b"\xff\xd8\xff": "JPEG", b"%PDF": "PDF"}
SID = re.compile(r"\bS\d{4}\b")
SID_RANGE = re.compile(r"\bS\d{4}\s*(?:–|—|-|to|\.\.)\s*S\d{4}\b")


def sha(b):
    return hashlib.sha256(b).hexdigest()


# ------------------------------------------------------------------ 1–5 dispatch, partition, sizing, packing key
def dispatch_path(n_files, req_bytes, budget=BUDGET):
    """EMPTY (no required file: item 16), SINGLE (R(L) <= budget: existing single-context procedure), DECOMPOSED."""
    if n_files == 0:
        return "EMPTY"
    return "SINGLE" if req_bytes <= budget else "DECOMPOSED"


def packing_key(sid, meta):
    """Partition-only ordering; NO chronological standing (v3.5 R1; contract §14 / A.10 unchanged). Group 0: file-level
    dated-position candidates (EXPLICIT basis, order_evidence not exactly SOURCE_ID, no BULK block), by date; group 1:
    all others. S-id breaks ties. Never written into a historical record."""
    m = meta or {}
    date = re.match(r"\d{4}-\d{2}-\d{2}", str(m.get("best_historical_date") or ""))
    dated = (m.get("best_historical_date_basis") == "EXPLICIT" and str(m.get("order_evidence")) != "SOURCE_ID" and
             not re.match(r"^BULK-", str(m.get("mtime_block") or "")) and date is not None)
    return (0, date.group(0), sid) if dated else (1, "", sid)


def partition(files, rows, sizes, key, budget=BUDGET):
    """Label-local row-first + FFD fill (decision B). Row sources are packed next-fit in packing-key order (contiguous);
    the remaining files are placed first-fit decreasing (ties by S-id) into existing units, else new units. Files are
    never split; a file larger than the budget occupies its own unit (binary files: decision C)."""
    units = [[]]
    for s in sorted(rows, key=key):
        if units[-1] and sum(sizes[x] for x in units[-1]) + sizes[s] > budget:
            units.append([])
        units[-1].append(s)
    units = [u for u in units if u]
    for s in sorted(set(files) - set(rows), key=lambda x: (-sizes[x], x)):
        for u in units:
            if sum(sizes[x] for x in u) + sizes[s] <= budget:
                u.append(s)
                break
        else:
            units.append([s])
    return [sorted(u) for u in units]


def row_adjacencies(rows, units, key):
    uo = {s: i for i, u in enumerate(units) for s in u}
    seq = sorted(rows, key=key)
    return sum(1 for a, b in zip(seq, seq[1:]) if uo[a] != uo[b])


def all_blob_sizes(manifest_rows, repo_root, git_sizes):
    """Item 4: sizes for every blob type. GIT-OBJECT sizes come from git; PRESERVED-AUDIT-COPY sizes from the preserved
    file (metadata only). A missing size is an error, never a silent zero."""
    out = dict(git_sizes)
    for r in manifest_rows:
        if r.get("blob_spec_type") == "PRESERVED-AUDIT-COPY" and r.get("source_id") not in out:
            out[r["source_id"]] = os.path.getsize(os.path.join(repo_root, r["blob_spec"]))
    return out


def label_plan(label, files, rows, sizes, meta, budget=BUDGET):
    missing = sorted(s for s in files if s not in sizes)
    if missing:
        raise ValueError(f"{label}: unsized required file(s) {missing[:3]}")
    req_bytes = sum(sizes[s] for s in files)
    path = dispatch_path(len(files), req_bytes, budget)
    key = lambda s: packing_key(s, meta.get(s))
    units = partition(files, rows, sizes, key, budget) if path == "DECOMPOSED" else ([sorted(files)] if files else [])
    return {"label": label, "path": path, "required_bytes": req_bytes, "n_files": len(files), "units": units,
            "row_adjacencies": row_adjacencies(rows, units, key) if units else 0}


# ------------------------------------------------------------------ 8–9 byte pages, binary refusal, acknowledgement
def is_binary(b):
    """Decision C/D: binary content is never paged as text. Returns the reason or None."""
    for sig, name in SIG.items():
        if b.startswith(sig):
            return f"SIGNATURE-{name}"
    if b"\x00" in b:
        return "NUL-BYTE"
    try:
        b.decode("utf-8", errors="strict")
    except UnicodeDecodeError:
        return "NOT-UTF-8"
    return None


def byte_pages(b, page_bytes=PAGE_BYTES):
    """Deterministic byte spans on UTF-8 code-point boundaries, each <= page_bytes; tiles [0, len) exactly."""
    spans, i, n = [], 0, len(b)
    if n == 0:
        return [(0, 0)]
    while i < n:
        j = min(n, i + page_bytes)
        while j < n and j > i and (b[j] & 0xC0) == 0x80:        # never split a multibyte sequence
            j -= 1
        if j == i:
            raise ValueError("page_bytes smaller than one code point")
        spans.append((i, j))
        i = j
    return spans


def ack_token(page_sha256):
    """Decision D: printed in the page trailer; the agent lists the tokens of every page it relies on."""
    return "ACK-" + page_sha256[-12:]


def byte_page_record(sid, b, k, content_sha):
    spans = byte_pages(b)
    a, e = spans[k - 1]
    chunk = b[a:e]
    ps = sha(chunk)
    return chunk, {"source_id": sid, "page": k, "n_pages": len(spans), "byte_start": a, "byte_end": e,
                   "page_sha256": ps, "content_sha256": content_sha, "ack_token": ack_token(ps), "mode": READER_MODE}


def byte_page_coverage(entries, contents):
    """Verifier support for the new mode (old character logs are verified by the existing verifier, unchanged).
    contents: {sid: bytes}, supplied by the caller through the seal-aware resolver."""
    by = {}
    for e in entries:
        pg = e.get("page") if isinstance(e.get("page"), dict) else None
        if pg and pg.get("mode") == READER_MODE and not e.get("refused") and pg.get("source_id") in (e.get("source_ids") or []):
            by.setdefault(pg["source_id"], []).append(pg)
    out = {}
    for sid, pgs in by.items():
        spans = byte_pages(contents[sid])
        good, bad = set(), 0
        for pg in pgs:
            k = pg.get("page")
            ok = isinstance(k, int) and 1 <= k <= len(spans) and pg.get("n_pages") == len(spans) and \
                (pg.get("byte_start"), pg.get("byte_end")) == spans[k - 1] and \
                pg.get("page_sha256") == sha(contents[sid][spans[k - 1][0]:spans[k - 1][1]]) and \
                pg.get("ack_token") == ack_token(pg.get("page_sha256") or "")
            good.add(k) if ok else None
            bad += 0 if ok else 1
        out[sid] = {"n": len(spans), "have": good, "complete": good == set(range(1, len(spans) + 1)), "bad": bad,
                    "tokens": {ack_token(sha(contents[sid][a:e])) for a, e in spans}}
    return out


def ack_violations(record_tokens, coverage, relied_on):
    """Every relied-upon file must list the acknowledgement token of every one of its pages."""
    out = []
    for sid in relied_on:
        c = coverage.get(sid)
        if not c or not c["complete"]:
            out.append((sid, "relied-upon file not completely read in byte mode"))
        elif not c["tokens"] <= set(record_tokens.get(sid, [])):
            out.append((sid, "acknowledgement tokens missing for relied-upon pages"))
    return out


# ------------------------------------------------------------------ 6–7 binary pre-classification, archive seal check
def char_to_byte_offsets(b, char_offsets):
    """Map decoded-text character offsets (decode with errors='replace', as the stage-1 search saw the text) to byte
    offsets, deterministically, by incremental decoding."""
    import codecs
    dec = codecs.getincrementaldecoder("utf-8")(errors="replace")
    want = sorted(set(char_offsets))
    out, chars, wi = {}, 0, 0
    for i in range(len(b)):
        chars += len(dec.decode(b[i:i + 1]))
        while wi < len(want) and want[wi] < chars:
            out[want[wi]] = i                                       # the byte at which that character is produced
            wi += 1
    chars += len(dec.decode(b"", final=True))
    for c in want[wi:]:
        out[c] = len(b)
    return out


def _png_region(b, off):
    i = 8
    while i + 8 <= len(b):
        n = int.from_bytes(b[i:i + 4], "big")
        typ = b[i + 4:i + 8]
        if i <= off < i + 12 + n:
            return "TEXT" if typ in (b"tEXt", b"iTXt") else "NONTEXT"
        i += 12 + n
    return "NONTEXT"


def _zip_region(b, off):
    i = 0
    while i + 30 <= len(b) and b[i:i + 4] == b"PK\x03\x04":
        method = int.from_bytes(b[i + 8:i + 10], "little")
        csize = int.from_bytes(b[i + 18:i + 22], "little")
        nlen = int.from_bytes(b[i + 26:i + 28], "little")
        xlen = int.from_bytes(b[i + 28:i + 30], "little")
        name_end = i + 30 + nlen
        data_end = name_end + xlen + csize
        if i + 30 <= off < name_end:
            return "TEXT"                                           # member file name
        if name_end + xlen <= off < data_end:
            return "MEMBER-DATA-STORED" if method == 0 else "NONTEXT"
        i = data_end
    return "NONTEXT"


def binary_preclassify(b, hit_char_offsets):
    """Item 6: mechanical pre-classification of a required binary file. Returns {format, hits: {offset: region},
    candidate}. candidate is only a proposal for the per-file human decision record (FALSE-HIT when every hit lies in
    non-text bytes; otherwise HUMAN-REVIEW). Never extracts content."""
    fmt = is_binary(b) or "TEXT"                                  # hit offsets are stage-1 character offsets
    boffs = char_to_byte_offsets(b, hit_char_offsets)
    regions = {}
    for c, o in boffs.items():
        if fmt == "SIGNATURE-PNG":
            regions[c] = _png_region(b, o)
        elif fmt == "SIGNATURE-ZIP":
            regions[c] = _zip_region(b, o)
        else:
            w = b[max(0, o - 64):o + 64]
            printable = sum(1 for x in w if 32 <= x < 127 or x in (9, 10, 13)) / max(1, len(w))
            regions[c] = "TEXT-LIKE" if printable >= 0.9 else "NONTEXT"
    cand = "FALSE-HIT" if regions and all(r == "NONTEXT" for r in regions.values()) else "HUMAN-REVIEW"
    return {"format": fmt, "hits": regions, "candidate": cand}


def archive_member_seal(zip_bytes, holdout_content_shas, corpus_content_shas):
    """Item 7: member-level seal check for an archive. Returns [(member, sha256, class)] with class HOLDOUT-MATCH /
    CORPUS-MATCH / UNKNOWN. Any HOLDOUT-MATCH forbids extraction of the archive (members never bypass the resolver)."""
    import io
    import zipfile
    out = []
    with zipfile.ZipFile(io.BytesIO(zip_bytes)) as z:
        for info in z.infolist():
            if info.is_dir():
                continue
            h = sha(z.read(info))
            cls = "HOLDOUT-MATCH" if h in holdout_content_shas else "CORPUS-MATCH" if h in corpus_content_shas else "UNKNOWN"
            out.append((info.filename, h, cls))
    return out


def extraction_permitted(decision_record, member_classes):
    """EXTRACT only if explicitly authorized for that file, with a validated extractor and defined provenance, and (for
    archives) no hold-out member."""
    return (decision_record.get("decision") == "EXTRACT" and decision_record.get("authorized_by") and
            decision_record.get("extractor_validated") is True and decision_record.get("provenance") and
            not any(c == "HOLDOUT-MATCH" for _, _, c in member_classes))


# ------------------------------------------------------------------ 10 reading state; 16 empty labels
WHOLE_ONLY = ("FOUND", "GENUINELY-UNDEFINED-AFTER-CENSUS")


def reading_state_violations(obj, states):
    """Item 10, one-directional: only WHOLE-FILE supports FOUND / GENUINELY-UNDEFINED-AFTER-CENSUS / births / change
    points. states: {sid: reading_state}. Returns a list of violations."""
    out = []
    for sid, st in states.items():
        if st not in READING_STATES:
            out.append(f"{sid}: reading_state {st!r} off-scale")
    whole = {s for s, st in states.items() if st == "WHOLE-FILE"}
    for d in obj.get("stage2_dispositions") or []:
        sid = d.get("source_id")
        if sid in whole:
            continue
        if d.get("method") != "NOT-CONSUMED-ESCALATED":
            out.append(f"{sid}: non-WHOLE-FILE disposition without method NOT-CONSUMED-ESCALATED")
        if any(v != "ESCALATED" for v in (d.get("by_dimension") or {}).values()):
            out.append(f"{sid}: non-WHOLE-FILE disposition with a non-ESCALATED dimension")
    for dim, a in (obj.get("absences") or {}).items():
        sb = ((a or {}).get("supplied_by") or {}).get("source_id") if isinstance(a, dict) else None
        if isinstance(a, dict) and a.get("resolution") == "FOUND" and sb and sb not in whole:
            out.append(f"absences.{dim}: FOUND supplied by a non-WHOLE-FILE source {sb}")
        if isinstance(a, dict) and a.get("resolution") == "GENUINELY-UNDEFINED-AFTER-CENSUS" and \
                any(st != "WHOLE-FILE" for st in states.values()):
            out.append(f"absences.{dim}: GENUINELY-UNDEFINED-AFTER-CENSUS with an incomplete census")
    for kind, v in (obj.get("births") or {}).items():
        for s in SID.findall(str(v)):
            if s not in whole:
                out.append(f"births.{kind}: cites non-WHOLE-FILE source {s}")
    for p in obj.get("timeline") or []:
        if isinstance(p, dict) and str(p.get("change_vs_previous", "")).startswith("CHANGES-") and \
                p.get("source_id") not in whole:
            out.append(f"timeline: change point on non-WHOLE-FILE source {p.get('source_id')}")
    return out


def empty_label_violations(obj):
    """Item 16 (R17): a label with an empty required set resolves NOT-EVIDENCED-IN-CAPTURE: every birth
    NOT-EVIDENCED-IN-CAPTURE; no FOUND and no GENUINELY-UNDEFINED-AFTER-CENSUS; an escalation naming EMPTY-REQUIRED-SET.
    (Distinct from not-read and from not-found.)"""
    out = []
    for kind, v in (obj.get("births") or {}).items():
        if v != "NOT-EVIDENCED-IN-CAPTURE":
            out.append(f"births.{kind} must be NOT-EVIDENCED-IN-CAPTURE for an empty required set")
    for dim, a in (obj.get("absences") or {}).items():
        if isinstance(a, dict) and a.get("resolution") in WHOLE_ONLY:
            out.append(f"absences.{dim}: {a.get('resolution')} impossible with an empty required set")
    if not any(EMPTY_MARK in str(e.get("detail", "")) for e in obj.get("escalations") or [] if isinstance(e, dict)):
        out.append(f"missing escalation naming {EMPTY_MARK}")
    if obj.get("stage2_dispositions"):
        out.append("stage-2 dispositions present for an empty required set")
    return out


# ------------------------------------------------------------------ 11 edge classes
def edge_class_violations(obj, row_sources):
    out = []
    for e in obj.get("dependency_edges") or []:
        if not isinstance(e, dict):
            continue
        c = e.get("edge_class")
        if c not in EDGE_CLASSES:
            out.append(f"edge to {str(e.get('target_label'))[:40]}: edge_class {c!r} not in {EDGE_CLASSES}")
        elif c == "R2-EVIDENCED" and not (e.get("quote") and e.get("source_id")):
            out.append(f"R2-EVIDENCED edge to {str(e.get('target_label'))[:40]} without a quote and source_id")
        elif c == "R1-STRUCTURAL" and e.get("source_id") not in row_sources:
            out.append(f"R1-STRUCTURAL edge to {str(e.get('target_label'))[:40]} not grounded in a row source")
    return out


# ------------------------------------------------------------------ 12 scan taxonomy
READER = re.compile(r"p3b_read_source\.py(?P<args>[^;&|\n]*)")
OUTSIDE = re.compile(r"\bgit\s+(show|cat-file|log\s+[^;&|\n]*-p|grep)\b|\bp3b_discovery_io\b|\bread_many\s*\(|"
                     r"\bdiscovery_resolver\s*\(")
GLOB = re.compile(r"docs/knowledgeos/[^\s'\"]*[*?\[]")


def scan_level(call, run, batch, corpus_tokens, holdout_tokens):
    """Item 12. Returns (level, violation or None) for one tool call (inputs only):
    1 reader invocation (no S-id: e.g. --help) · 2 source-access attempt (reader with an S-id; or git show / cat-file /
    log -p / grep, a corpus glob, a direct resolver import, or a named corpus path or blob spec outside the reader).
    Levels 3 (successful read) and 4 (corpus-content exposure) come from the read log, see log_level. The resolver's
    seal refusal stays the primary hold-out protection; this scan is secondary and not complete."""
    text = json.dumps(call.get("input"), ensure_ascii=False)
    if any(t in text for t in holdout_tokens):
        return 2, "SEAL-BREACH-ATTEMPT"
    outside = bool(OUTSIDE.search(text) or GLOB.search(text) or any(t in text for t in corpus_tokens))
    if outside:
        return 2, "ACCESS-OUTSIDE-READER"
    m = [x.group("args") for x in READER.finditer(text)]
    if not m:
        return 0, None
    for a in m:
        if not SID.search(a):
            continue                                                # level 1: a reader invocation that reads nothing
        r, b = re.search(r"--run\s+(\S+)", a), re.search(r"--batch\s+(\S+)", a)
        if not r or not b or r.group(1) != run or b.group(1) != batch:
            return 2, "READER-FOREIGN-RUN-OR-BATCH"
        return 2, None
    return 1, None


def log_level(entry, permitted, holdout_sids):
    """Levels 3/4 from a read-log entry: 3 successful read of a permitted file; 4 corpus-content exposure (a successful
    read of a non-permitted file, or any hold-out file)."""
    if entry.get("refused"):
        return 2, None
    sids = set(entry.get("source_ids") or [])
    if sids & set(holdout_sids):
        return 4, "HOLDOUT-EXPOSURE"
    if sids - set(permitted):
        return 4, "NON-PERMITTED-EXPOSURE"
    return 3, None


# ------------------------------------------------------------------ 13–15 safeguards S1, S2, S3
def required_pair_checks(pairs, label, files):
    """S1: every P3a pair touching the label whose basis cites a file in R(L) needs a check of that file.
    Returns {(pair_id, sid)}."""
    need = set()
    for p in pairs:
        if label not in (p.get("a"), p.get("b")):
            continue
        cited = set(SID.findall(json.dumps(p.get("what_says_this"), ensure_ascii=False) + json.dumps(p.get("basis"))))
        for s in cited & set(files):
            need.add((p["pair_id"], s))
    return need


def s1_violations(need, records, obj, register):
    """S1: each required (pair, file) has a unit-level check {pair_id, supports, quote}; a NO needs a
    VERDICT-EVIDENCE-CONFLICT register record citing the pair and an object escalation (H-02); the roll-up is not
    adjusted (contract §11.5)."""
    got = {}
    for r in records:
        for c in r.get("pair_evidence_checks") or []:
            got[(c.get("pair_id"), r.get("source_id"))] = c
    out = [f"S1: no pair-evidence check for pair {p} in {s}" for p, s in sorted(need - set(got))]
    for key, c in sorted(got.items()):
        if c.get("supports") not in PAIR_SUPPORT:
            out.append(f"S1: pair {key[0]} in {key[1]}: supports {c.get('supports')!r} off-scale")
        if c.get("supports") in ("YES", "NO") and not c.get("quote"):
            out.append(f"S1: pair {key[0]} in {key[1]}: {c.get('supports')} without a quote")
        if c.get("supports") == "NO":
            reg = any(VEC in json.dumps(x.get("topics", []) + [x.get("kind")]) and key[0] in json.dumps(x)
                      for x in register)
            esc = any(VEC in str(e.get("detail", "")) and key[0] in str(e.get("detail", ""))
                      for e in obj.get("escalations") or [] if isinstance(e, dict))
            if not reg:
                out.append(f"S1: pair {key[0]}: NO without a {VEC} register record")
            if not esc:
                out.append(f"S1: pair {key[0]}: NO without a {VEC} escalation")
    return out


def s2_violations(obj, row_sources):
    """S2: births cite only row sources of the label (mechanical enforcement of §11.2); applies to every path."""
    return [f"S2: births.{k} cites {s}, not a row source of the label"
            for k, v in (obj.get("births") or {}).items() for s in SID.findall(str(v)) if s not in row_sources]


def s3_lint(records, label, run, batch, quarantine_hits=None, sets=None):
    """S3 pre-submit lint (repeated by the verifier): S-id ranges; layer-A register ids or pointers; quarantine hits.
    records: the synthesized object, register and P1-gap records."""
    out = []
    pointer = re.compile(r"\bsee (the )?register\b|\bresearch register\b|\bregister (record|id|entry)\b|\brs_id\b")
    rsid = re.compile(r"\b(?:" + re.escape(run) + r":)?" + re.escape(batch) + r":\d+\b")
    for i, r in enumerate(records):
        txt = json.dumps(r, ensure_ascii=False)
        if SID_RANGE.search(txt):
            out.append(f"S3: record {i}: S-id range")
        if r.get("working_label") and "timeline" in r:                   # layer-A object record
            body = json.dumps({k: v for k, v in r.items() if k not in ("run_id", "batch_id")}, ensure_ascii=False)
            if pointer.search(body) or rsid.search(body):
                out.append(f"S3: record {i}: register id or pointer in layer A")
        if quarantine_hits is not None:
            nl, nf = quarantine_hits(txt, label, sets)
            if nl or nf:
                out.append(f"S3: record {i}: quarantine hit ({nl} label name(s), {nf} file id(s))")
    return out


# ------------------------------------------------------------------ 17–18 pre-registered probability audit
def draw_audit_sample(strata, seed, rates):
    """Item 17: stratified probability sample drawn with a frozen seed BEFORE any S5 output exists.
    strata: {name: [labels]}; rates: {name: fraction in (0, 1] or 'CENSUS'}. Returns {name: {labels, N, n, pi}} with
    inclusion probability pi = n/N per stratum (simple random sampling without replacement within stratum)."""
    rng = random.Random(seed)
    out = {}
    for name in sorted(strata):
        pop = sorted(strata[name])
        rate = rates[name]
        n = len(pop) if rate == "CENSUS" else min(len(pop), max(1, math.ceil(rate * len(pop)))) if pop else 0
        pick = sorted(rng.sample(pop, n)) if n else []
        out[name] = {"labels": pick, "N": len(pop), "n": n, "pi": (n / len(pop)) if pop else 0.0}
    return out


def ht_estimate(sample, discordant):
    """Item 17: stratified Horvitz–Thompson estimate of the number and share of labels with adjudicated discordance.
    discordant: set of sampled labels adjudicated discordant. ML-prioritized review items are NOT in `sample` (item 18);
    passing a label that is not in the probability sample raises."""
    sampled = {l for s in sample.values() for l in s["labels"]}
    stray = set(discordant) - sampled
    if stray:
        raise ValueError(f"{len(stray)} discordant label(s) outside the probability sample (ML queues never enter)")
    total, N, per = 0.0, 0, {}
    for name, s in sample.items():
        d = sum(1 for l in s["labels"] if l in discordant)
        est = d / s["pi"] if s["pi"] else 0.0
        per[name] = {"n": s["n"], "N": s["N"], "discordant": d, "ht_total": est,
                     "wilson95": wilson(d, s["n"]) if s["n"] else None}
        total += est
        N += s["N"]
    return {"strata": per, "ht_total": total, "ht_share": total / N if N else 0.0}


def wilson(k, n, z=1.96):
    p = k / n
    d = 1 + z * z / n
    c = (p + z * z / (2 * n)) / d
    h = z * math.sqrt(p * (1 - p) / n + z * z / (4 * n * n)) / d
    return (max(0.0, c - h), min(1.0, c + h))


def zero_bound(n, N=None, alpha=0.05):
    """One-sided (1 - alpha) upper bound on the discordant share when 0 of n sampled labels are discordant: binomial
    1 - alpha^(1/n); with a finite population N (hypergeometric), the smallest D/N such that P(0 | D) < alpha."""
    if N is None:
        return 1 - alpha ** (1 / n)
    last = 0
    for D in range(0, N + 1):
        p0 = math.comb(N - D, n) / math.comb(N, n) if N - D >= n else 0.0
        if p0 < alpha:
            break
        last = D                                                    # largest D still consistent with observing 0
    return last / N


# ------------------------------------------------------------------ revision-5 gate: aggregated checks
def r5_checks(obj, register, records, path, row_sources, files, pairs, states, run, batch,
              quarantine_hits=None, sets=None):
    """All revision-5 object-level rules for one label (called by the verifier only when the batch's contract
    revision >= 5; earlier revisions and historical logs are verified exactly as before).
    path: EMPTY | SINGLE | DECOMPOSED (from the frozen plan). records: file-reading records (units, or the single-context
    agent's per-file records). states: {sid: reading_state}."""
    label = obj.get("working_label")
    F = []
    if path == "EMPTY":
        return empty_label_violations(obj)
    F += reading_state_violations(obj, states)
    F += edge_class_violations(obj, row_sources)
    F += s1_violations(required_pair_checks(pairs, label, files), records, obj, register)
    F += s2_violations(obj, row_sources)
    F += s3_lint([obj] + list(register), label, run, batch, quarantine_hits, sets)
    return F


# ------------------------------------------------------------------ CLI (metadata only)
def _population():
    import sys
    sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
    import p3b_s5_common as c
    import p3b_s5_prepare as P
    ctx = P.Context()
    man = [r for r in c.jl("P3B-IDENTITY-MANIFEST.jsonl") if "source_id" in r]
    sizes = all_blob_sizes(man, c.REPO_ROOT, ctx.size)
    fm = c.files_meta()
    out = {}
    for lab in ctx.plan:
        s2 = set() if lab in ctx.hubs else {s for r in ([ctx.hits[lab]] if lab in ctx.hits else []) + ctx.dims.get(lab, [])
                                            if r["record"] == "LABEL-HITS"
                                            for s in list(r.get("raw_hits") or {}) + list(r.get("ledger_hits") or {})}
        rows = {s["source_id"] for s in ctx.bi[lab]["sources"]}
        out[lab] = label_plan(lab, s2 | rows, rows, sizes, fm)
    return out, ctx


def main(argv=None):
    import sys
    a = argv if argv is not None else sys.argv[1:]
    if a[:2] != ["plan", "--dry-run"]:
        print("usage: p3b_s5_r5.py plan --dry-run   (aggregates only; the production plan is written at activation)",
              file=sys.stderr)
        return 2
    plans, ctx = _population()
    import collections
    paths = collections.Counter(p["path"] for p in plans.values())
    dec = [p for p in plans.values() if p["path"] == "DECOMPOSED"]
    print(json.dumps({"revision": REVISION, "budget": BUDGET, "labels": len(plans), "paths": dict(paths),
                      "decomposed_units": sum(len(p["units"]) for p in dec),
                      "row_adjacencies": sum(p["row_adjacencies"] for p in plans.values()),
                      "labels_with_row_adjacency": sum(1 for p in plans.values() if p["row_adjacencies"]),
                      "total_units": sum(len(p["units"]) for p in plans.values()),
                      "max_unit_bytes": max((sum(ctx.size.get(s, 0) for s in u) for p in plans.values()
                                             for u in p["units"]), default=0)}, indent=1))
    return 0


if __name__ == "__main__":
    import sys
    sys.exit(main())
