#!/usr/bin/env python3
"""P3b agent reader: print the CONTENT text of sources, by source_id, through the seal-aware `discovery_resolver()`
(G-LOG-0018). This is the only way an S4/S5 agent reads corpus text. Every call, including a refused one, is
appended by the reader itself to the run's read log, so reading cost and read discipline are measured, not
self-reported.

  python3 p3b_read_source.py --run S4-PILOT-R2-001 --batch PB01 --label <working_label> --step <1|7|10> S#### [...]

  --step 1   §9.8 step 1 (timeline / whole-file reading of birth, change or contradiction sources)
  --step 7   §9.8 step 7 (stage-2 whole-file reading of a stage-1 hit file)
  --step 10  §9.8 step 10 (research: discovery loop, checklist, disconfirmation searches)

Output: per source, a header `=== S#### (sha256 <content_sha256>) ===`, then the manifest-identified content decoded
as UTF-8 with replacement (binary content appears as decoded bytes, exactly as the A.4 search saw it). A sealed
hold-out file is refused while SEALED (exit 1). Log: ledger-p3b-r2/<run>/READ-LOG.jsonl, one JSON line per call:
{utc, run_id, batch_id, working_label, step, source_ids, refused, bytes, sha256}.

Paged mode (contract revision 3, G-LOG-0050): `--page K` with exactly one S-id prints page K of the content, split into
deterministic pages of PAGE_CHARS characters of the decoded text: header `=== S#### page K/N chars A-B (content
sha256 C; page sha256 P) ===`. The log entry adds {page: {source_id, page, n_pages, char_start, char_end,
page_sha256, content_sha256}, stdout} and `bytes` counts the page's UTF-8 bytes. Whole-file reading is proven only by
every page 1..N logged for the run (the verifier re-computes each page hash). The kind of stdout is logged
(`stdout`); the former refusal of a regular-file stdout is WITHDRAWN (S5 decomposition-pilot ruling, G-LOG-0052): the
harness captures stdout into a file, so the refusal blocked legitimate reads and could not detect pipes anyway.

Decomposition pilot (non-production, G-LOG-0052): run ids `PX####-U##` (reading unit), `PX####-S##` (synthesis),
`PX####-A##` (audit), batch `PX####`; their logs go to `pilot-s5-decomp/<run>/READ-LOG.jsonl`, never to the production
ledger. Optional `--session N` (paged mode) records the execution session of a resumable reading unit.

Byte mode (contract revision 5, G-LOG-0082; versioned, opt-in): `--mode bytes` with `--page K` pages the content bytes
into deterministic spans of at most 24,000 bytes on UTF-8 code-point boundaries (`p3b_s5_r5.byte_pages`). Binary
content (signature, NUL byte, or strict UTF-8 failure) is refused with `BINARY-CONTENT` and never paged as text. The
page trailer prints the acknowledgement token derived from the page hash. The log entry's page record carries
{byte_start, byte_end, page_sha256, content_sha256, ack_token, mode: "bytes-r5"}. Without `--mode`, behaviour is the
revision-3 character paging, unchanged; historical logs are never rewritten. Revision-5 production run ids:
`OB####-R5-L##` (single-context label), `OB####-R5-L##U##` (reading unit), `OB####-R5-L##S` (synthesis).

Contract revision 7 (G-LOG-0088; addendum §2.4, §5.4, §9): run ids `OB####-R7-L##[U##|S]`, byte mode only. For a batch
whose manifest entry carries `r7_plan_sha256`, every non-R7 run id is refused (default-deny: no R2/R5/R6 rerun inside an
activated R7 batch); an R7 read needs the plan at the VERIFIER's path `<slice_root>/<batch>.R7-PLAN.json`, hash-equal to
the manifest, and passes the plan check (planned reading run, permitted file, --label = owner, no synthesis read). The R7
page header also prints `run R batch B label L inv I` (I = a reader-generated invocation id, a convenience join key
only). These are defence in depth: the harness witness stays the authoritative execution record.
"""
import datetime
import importlib.util
import json
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("p3b_discovery_io", os.path.join(_HERE, "p3b_discovery_io.py"))
dio = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(dio)
STEPS = {"1", "7", "10"}
PAGE_CHARS = 20000                                              # contract revision 3: fixed page size (characters)
CLOSED_RUNS = {"S4-PILOT-R2-001"}                              # committed logs: never appended to


def parse(argv):
    opts, sids, i = {}, [], 0
    while i < len(argv):
        a = argv[i]
        if a in ("--run", "--batch", "--label", "--step", "--page", "--session", "--mode") and i + 1 < len(argv):
            opts[a[2:]] = argv[i + 1]
            i += 2
        else:
            sids.append(a)
            i += 1
    return opts, sids


def pages_of(text):
    """Deterministic pages of the decoded content: [(char_start, char_end), ...] (at least one page)."""
    n = max(1, -(-len(text) // PAGE_CHARS))
    return [(i * PAGE_CHARS, min(len(text), (i + 1) * PAGE_CHARS)) for i in range(n)]


def page_record(sid, text, k, content_sha):
    a, b = pages_of(text)[k - 1]
    chunk = text[a:b]
    return chunk, {"source_id": sid, "page": k, "n_pages": len(pages_of(text)), "char_start": a, "char_end": b,
                   "page_sha256": dio.s3.sha256_bytes(chunk.encode("utf-8")), "content_sha256": content_sha}


def stdout_kind():
    import stat
    try:
        m = os.fstat(sys.stdout.fileno()).st_mode
    except (OSError, ValueError):
        return "unknown"
    return "file" if stat.S_ISREG(m) else "pipe" if stat.S_ISFIFO(m) else "tty" if stat.S_ISCHR(m) else "other"


PILOT_RUN = r"PX\d{4}-[USA]\d{2}"
PILOT_ROOT = "pilot-s5-decomp"


def log(opts, sids, refused, data=None, extra=None):
    root = PILOT_ROOT if re.fullmatch(PILOT_RUN, opts["run"]) else "ledger-p3b-r2"      # pilot logs are isolated
    path = os.path.join(dio.s3.CR, root, opts["run"], "READ-LOG.jsonl")
    os.makedirs(os.path.dirname(path), exist_ok=True)
    rec = {"utc": datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ"),
           "run_id": opts["run"], "batch_id": opts["batch"], "working_label": opts["label"], "step": int(opts["step"]),
           "source_ids": sids, "refused": refused,
           "bytes": {s: len(b) for s, b in data.items()} if data else {},
           "sha256": {s: dio.s3.sha256_bytes(b) for s, b in data.items()} if data else {}}
    rec.update(extra or {})
    with open(path, "a", encoding="utf-8") as f:
        f.write(json.dumps(rec, sort_keys=True, separators=(",", ":"), ensure_ascii=False) + "\n")


def main():
    opts, sids = parse(sys.argv[1:])
    if "page" in opts:
        return main_paged(opts, sids)
    if set(opts) != {"run", "batch", "label", "step"} or opts["step"] not in STEPS or not sids or \
            not re.fullmatch(r"S4-PILOT-R2-\d{3}|OB\d{4}-R2(\.\d+)?|OB\d{4}-R2S|OT\d{4}-R2|OA\d{4}-R2", opts["run"]) or \
            not re.fullmatch(r"PB\d{2}|B\d{4}|OB\d{4}|OT\d{4}|OA\d{4}", opts["batch"]) or \
            not re.fullmatch(r"[A-Za-z0-9][A-Za-z0-9._-]*", opts["label"]) or \
            any(not re.fullmatch(r"S\d{4}", s) for s in sids) or opts.get("run") in CLOSED_RUNS:
        print("usage: p3b_read_source.py --run R --batch B --label L --step 1|7|10 S#### [S#### ...]", file=sys.stderr)
        return 2
    why = r7_refusal(opts, sids)                                  # R7 default-deny (activated batches only)
    if why:
        log(opts, sids, True)
        print(f"REFUSED: {why}", file=sys.stderr)
        return 1
    resolver = dio.discovery_resolver()
    try:
        data = resolver.read_many(sids)
    except dio.s3.IdentityError as e:
        log(opts, sids, True)
        print(f"REFUSED: {e}", file=sys.stderr)
        return 1
    log(opts, sids, False, data)
    try:
        for sid in sids:
            print(f"=== {sid} (sha256 {resolver.rows[sid]['content_sha256']}) ===")
            print(data[sid].decode("utf-8", errors="replace"))
    except BrokenPipeError:                                   # piped into head etc.: stop quietly
        os.dup2(os.open(os.devnull, os.O_WRONLY), sys.stdout.fileno())
    return 0


R5_RUN = r"OB\d{4}-R5-L\d{2}(?:U\d{2}|S)?"                        # revision 5: WITHDRAWN (G-LOG-0083); not accepted
R6_RUN = r"OB\d{4}-R6-L\d{2}(?:U\d{2}|S)?"                        # contract revision 6 production run ids
R6_PLAN = "_batch_input_r2/s5/rev6/{batch}.R6-PLAN.json"         # the frozen, manifest-bound revision-6 plan
R7_RUN = r"OB\d{4}-R7-L\d{2}(?:U\d{2}|S)?"                        # contract revision 7 run ids (G-LOG-0088)
MANIFEST = "_batch_manifest_p3b_r2.jsonl"


def r7_activation(batch):
    """(header, entry) when the batch's manifest entry carries r7_plan_sha256 (an activated R7 batch), else None."""
    p = os.path.join(dio.s3.CR, MANIFEST)
    if not re.fullmatch(r"OB\d{4}", batch or "") or not os.path.exists(p):
        return None
    with open(p, encoding="utf-8") as f:
        lines = [l for l in f.read().split("\n") if l.strip()]
    header = (json.loads(lines[0]).get("header") or {}) if lines else {}
    for l in lines[1:]:
        e = json.loads(l)
        if e.get("batch_id") == batch:
            return (header, e) if e.get("r7_plan_sha256") else None
    return None


def r7_refusal(opts, sids):
    """R7 addendum §2.4: default-deny for activated batches (only R7 run ids), and for an R7 run the plan read from
    the VERIFIER's path (<slice_root>/<batch>.R7-PLAN.json) and hash-checked against the manifest. -> reason or None."""
    act = r7_activation(opts.get("batch"))
    is_r7 = re.fullmatch(R7_RUN, opts.get("run") or "") is not None
    if act is None:
        return "no activated R7 plan for this batch" if is_r7 else None
    if not is_r7:
        return "the batch has an activated R7 plan: only R7 run ids are accepted (default-deny; a rerun needs a new plan)"
    header, entry = act
    _r6spec = importlib.util.spec_from_file_location("p3b_s5_r6", os.path.join(_HERE, "p3b_s5_r6.py"))
    r6 = importlib.util.module_from_spec(_r6spec)
    _r6spec.loader.exec_module(r6)
    ppath = os.path.join(dio.s3.CR, f"{header.get('slice_root', '_batch_input_r2/s5')}/{opts['batch']}.R7-PLAN.json")
    plan = json.load(open(ppath, encoding="utf-8")) if os.path.exists(ppath) else None
    if plan is None or r6.sha(r6.canon_bytes(plan)) != entry.get("r7_plan_sha256"):
        return "the R7 plan is missing or its sha256 ≠ the manifest's r7_plan_sha256"
    ok, why = r6.reader_plan_check(plan, opts["run"], opts["batch"], sids[0], opts["label"])
    return None if ok else why


def main_paged(opts, sids):
    if not {"run", "batch", "label", "step", "page"} <= set(opts) or \
            set(opts) - {"run", "batch", "label", "step", "page", "session", "mode"} or \
            opts.get("mode", "bytes") != "bytes" or \
            (re.fullmatch(R6_RUN + "|" + R7_RUN, opts["run"]) is not None) != (opts.get("mode") == "bytes") or \
            opts["step"] not in STEPS or len(sids) != 1 or not re.fullmatch(r"[1-9]\d{0,4}", opts["page"]) or \
            ("session" in opts and not re.fullmatch(r"[1-9]\d{0,2}", opts["session"])) or \
            not re.fullmatch(r"OB\d{4}-R2(\.\d+)?|OB\d{4}-R2S|OT\d{4}-R2|OA\d{4}-R2|" + R6_RUN + "|" + R7_RUN + "|" + PILOT_RUN,
                             opts["run"]) or \
            not re.fullmatch(r"OB\d{4}|OT\d{4}|OA\d{4}|PX\d{4}", opts["batch"]) or \
            (re.fullmatch(PILOT_RUN, opts["run"]) is None) != (re.fullmatch(r"PX\d{4}", opts["batch"]) is None) or \
            not re.fullmatch(r"[A-Za-z0-9][A-Za-z0-9._-]*", opts["label"]) or not re.fullmatch(r"S\d{4}", sids[0]):
        print("usage: p3b_read_source.py --run R --batch B --label L --step 1|7|10 --page K S####", file=sys.stderr)
        return 2
    sid, k, kind = sids[0], int(opts["page"]), stdout_kind()
    session = {"session": int(opts["session"])} if "session" in opts else {}
    why7 = r7_refusal(opts, sids)                                  # R7: default-deny + plan (defence in depth; the
    if why7:                                                       # witness stays authoritative)
        log(opts, sids, True, extra=dict({"stdout": kind, "refusal": "PLAN", "reason": why7}, **session))
        print(f"REFUSED: {why7}", file=sys.stderr)
        return 1
    if re.fullmatch(R6_RUN, opts["run"]):                          # revision 6: the frozen plan decides (defence in depth)
        _r6spec = importlib.util.spec_from_file_location("p3b_s5_r6", os.path.join(_HERE, "p3b_s5_r6.py"))
        r6 = importlib.util.module_from_spec(_r6spec)
        _r6spec.loader.exec_module(r6)
        ppath = os.path.join(dio.s3.CR, R6_PLAN.format(batch=opts["batch"]))
        plan = json.load(open(ppath, encoding="utf-8")) if os.path.exists(ppath) else None
        ok, why = r6.reader_plan_check(plan, opts["run"], opts["batch"], sid, opts["label"])
        if not ok:
            log(opts, sids, True, extra=dict({"stdout": kind, "refusal": "PLAN", "reason": why}, **session))
            print(f"REFUSED: {why}", file=sys.stderr)
            return 1
    resolver = dio.discovery_resolver()
    try:
        data = resolver.read_many([sid])
    except dio.s3.IdentityError as e:
        log(opts, sids, True, extra=dict({"stdout": kind}, **session))
        print(f"REFUSED: {e}", file=sys.stderr)
        return 1
    if opts.get("mode") == "bytes":
        return page_bytes_mode(opts, sids, sid, k, kind, session, data[sid], resolver.rows[sid]["content_sha256"])
    text = data[sid].decode("utf-8", errors="replace")
    if k > len(pages_of(text)):
        print(f"usage: {sid} has {len(pages_of(text))} page(s)", file=sys.stderr)
        return 2
    chunk, rec = page_record(sid, text, k, resolver.rows[sid]["content_sha256"])
    log(opts, sids, False, {sid: chunk.encode("utf-8")}, extra=dict({"page": rec, "stdout": kind}, **session))
    try:
        print(f"=== {sid} page {k}/{rec['n_pages']} chars {rec['char_start']}-{rec['char_end']} "
              f"(content sha256 {rec['content_sha256']}; page sha256 {rec['page_sha256']}) ===")
        print(chunk)
    except BrokenPipeError:
        os.dup2(os.open(os.devnull, os.O_WRONLY), sys.stdout.fileno())
    return 0


def page_bytes_mode(opts, sids, sid, k, kind, session, raw, content_sha):
    """Contract revision 5 byte mode: binary refusal, byte-bounded UTF-8 pages, acknowledgement token."""
    _r5spec = importlib.util.spec_from_file_location("p3b_s5_r5", os.path.join(_HERE, "p3b_s5_r5.py"))
    r5 = importlib.util.module_from_spec(_r5spec)
    _r5spec.loader.exec_module(r5)
    why = r5.is_binary(raw)
    if why:
        log(opts, sids, True, extra=dict({"stdout": kind, "refusal": "BINARY-CONTENT", "binary_reason": why,
                                          "mode": r5.READER_MODE}, **session))
        print(f"REFUSED: BINARY-CONTENT ({why}); decided per file under contract revision 5 (binary policy)",
              file=sys.stderr)
        return 1
    n = len(r5.byte_pages(raw))
    if k > n:
        print(f"usage: {sid} has {n} page(s)", file=sys.stderr)
        return 2
    chunk, rec = r5.byte_page_record(sid, raw, k, content_sha)
    r7 = re.fullmatch(R7_RUN, opts["run"]) is not None
    inv = {}
    if r7:     # FD-5′: a reader-generated invocation id — a convenience join key only (never evidence, order or trust)
        import hashlib
        import time
        inv = {"invocation_id": hashlib.sha256(f"{opts['run']}|{sid}|{k}|{time.time_ns()}|{os.getpid()}".encode()).hexdigest()[:16]}
    log(opts, sids, False, {sid: chunk}, extra=dict({"page": rec, "stdout": kind, "mode": r5.READER_MODE}, **session, **inv))
    try:
        tail = f" run {opts['run']} batch {opts['batch']} label {opts['label']} inv {inv['invocation_id']}" if r7 else ""
        print(f"=== {sid} page {k}/{n} bytes {rec['byte_start']}-{rec['byte_end']} "
              f"(content sha256 {content_sha}; page sha256 {rec['page_sha256']}){tail} ===")
        print(chunk.decode("utf-8"))
        print(f"=== END {sid} page {k}/{n} {rec['ack_token']} ===")
    except BrokenPipeError:
        os.dup2(os.open(os.devnull, os.O_WRONLY), sys.stdout.fileno())
    return 0


if __name__ == "__main__":
    sys.exit(main())
