#!/usr/bin/env python3
"""F-Series controlled reader (§F-7). The only sanctioned way to read an F-file's content.

  python3 f_read_source.py --run FR-F3082-001 --info F3082          # page count and identity; logs an INFO line
  python3 f_read_source.py --run FR-F3082-001 --page K F3082        # prints page K; logs the page

Design REUSED from chronological-read/scripts/p3b_read_source.py (contract revision 3, G-LOG-0050); its page model
and stdout check are compiled from its committed, sha256-pinned blob (f_common.py):
deterministic pages of PAGE_CHARS characters of the decoded text; page identity, span and hash logged by the reader
itself; refused when stdout is redirected to a regular file (the page must reach the reader of the output).
F-specific: F-IDs are resolved only through F-MANIFEST.jsonl; a file whose bytes no longer match the manifest sha256
is refused (IDENTITY-DRIFT). Log: ledger/<F-ID>/READ-LOG.jsonl.

Known limit (recorded, not solved, as in G-LOG-0050): page hashes prove DELIVERY of every page to stdout, not that the
consuming agent attended to it. Consumption is additionally evidenced by the per-page digest the agent must write
(contract §C-3), which the audit checks against the page text.
"""
import os
import re
import sys

import f_common as C


def log(fid, rec):
    C.append_jsonl(os.path.join(C.LEDGER, fid, "READ-LOG.jsonl"), dict(rec, utc=C.utc(), f_id=fid))


def main(argv):
    opts, ids, i = {}, [], 0
    while i < len(argv):
        if argv[i] in ("--run", "--page") and i + 1 < len(argv):
            opts[argv[i][2:]] = argv[i + 1]
            i += 2
        elif argv[i] == "--info":
            opts["info"] = True
            i += 1
        else:
            ids.append(argv[i])
            i += 1
    if len(ids) != 1 or not re.fullmatch(r"F\d{4}", ids[0]) or not re.fullmatch(r"FR-F\d{4}-\d{3}", opts.get("run", "")) \
            or ("page" in opts) == ("info" in opts) or ("page" in opts and not re.fullmatch(r"[1-9]\d{0,4}", opts["page"])):
        print("usage: f_read_source.py --run FR-F####-NNN (--info | --page K) F####", file=sys.stderr)
        return 2
    fid, run, kind = ids[0], opts["run"], C.stdout_kind()
    if not opts["run"].startswith(f"FR-{fid}-"):
        print(f"REFUSED: run id must be FR-{fid}-NNN", file=sys.stderr)
        return 2
    row = C.manifest().get(fid)
    if not row or row["resolve"] not in ("RESOLVED", "REPAIRED") or row["kind"] != "TEXT":
        log(fid, {"run_id": run, "refused": True, "refusal": "NOT-A-READABLE-TEXT-ENTRY", "stdout": kind})
        print(f"REFUSED: {fid} is not a resolved text entry in the manifest", file=sys.stderr)
        return 1
    import f_integrity as I
    try:
        I.require_approval()                                           # F-20: no reading under an unapproved protocol
    except RuntimeError as e:
        log(fid, {"run_id": run, "refused": True, "refusal": "PROTOCOL-NOT-APPROVED", "stdout": kind})
        print(f"REFUSED: {e}", file=sys.stderr)
        return 1
    nxt = C.next_fid()
    if fid != nxt and C.current_state(fid) != "AUDITED":               # F-13: the reader enforces processing order
        log(fid, {"run_id": run, "refused": True, "refusal": "NOT-ELIGIBLE-LOOK-AHEAD", "next_f_id": nxt, "stdout": kind})
        print(f"REFUSED: {fid} is neither the next F-ID ({nxt}) nor AUDITED (no look-ahead, F-13)", file=sys.stderr)
        return 1
    try:
        text = C.content_text(row)
    except RuntimeError as e:
        log(fid, {"run_id": run, "refused": True, "refusal": "IDENTITY-DRIFT", "stdout": kind})
        print(f"REFUSED: {e}", file=sys.stderr)
        return 1
    pages = C.pages_of(text)
    if "info" in opts:
        log(fid, {"run_id": run, "refused": False, "info": True, "n_pages": len(pages), "stdout": kind,
                  "content_sha256": row["content_sha256"]})
        print(f"{fid} {row['resolved_path']} | content sha256 {row['content_sha256']} | chars {len(text)} | "
              f"pages {len(pages)} of {C.PAGE_CHARS} chars")
        return 0
    if kind == "file":
        log(fid, {"run_id": run, "refused": True, "refusal": "STDOUT-REDIRECTED-TO-FILE", "stdout": kind})
        print("REFUSED: paged output must not be redirected to a file (read the page itself)", file=sys.stderr)
        return 3
    k = int(opts["page"])
    if k > len(pages):
        print(f"usage: {fid} has {len(pages)} page(s)", file=sys.stderr)
        return 2
    a, b = pages[k - 1]
    chunk = text[a:b]
    rec = {"f_id": fid, "page": k, "n_pages": len(pages), "char_start": a, "char_end": b,
           "page_sha256": C.sha256_bytes(chunk.encode("utf-8")), "content_sha256": row["content_sha256"]}
    log(fid, {"run_id": run, "refused": False, "page": rec, "stdout": kind})
    try:
        print(f"=== {fid} page {k}/{len(pages)} chars {a}-{b} (content sha256 {row['content_sha256']}; "
              f"page sha256 {rec['page_sha256']}) ===")
        print(chunk)
    except BrokenPipeError:
        os.dup2(os.open(os.devnull, os.O_WRONLY), sys.stdout.fileno())
    return 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
