#!/usr/bin/env python3
"""P3b Stage 2A occurrence scan — v1.6.6 §11.4 (G-LOG-0022). For a hit term of <= 2 Unicode code points after A.4
normalization, scan a whole hit file mechanically and print every occurrence with its offset and a context window,
for Stage 2B review. Content is read only through the seal-aware discovery_resolver(); the call is logged by the same
reader log as p3b_read_source.py (step 7), so stage-2 coverage stays auditable.

  python3 p3b_stage2a_scan.py --run R --batch B --label L --step 7 --term <term> S#### [S#### ...]

Algorithm (name/version recorded in every output): A.4 normalization (NFKC, then the S3 LaTeX table; casefold for
multi-character terms, case-sensitive for single characters) and the A.4 boundary rule (the characters before and
after a match are not \\w). Offsets are into the normalized text, as in the S3 search records.
Every call writes its own line to the run's READ-LOG.jsonl with `tool: "p3b-stage2a/1"`, the normalized `term` and
`occurrences: {S####: [offsets]}` (the persisted 2A record, v1.6.6 §11.4); a scan is therefore distinguishable from a
whole-file read. Output per file: `=== S#### (sha256 <content_sha256>) algorithm p3b-stage2a/1 term <t> occurrences <n> ===`, then one
line per occurrence: `@<offset> …<context ±200 chars>…`.
"""
import importlib.util
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("p3b_read_source", os.path.join(_HERE, "p3b_read_source.py"))
rs = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(rs)
dio, s3 = rs.dio, rs.dio.s3
ALGORITHM = "p3b-stage2a/1"
CONTEXT = 200


def main():
    argv = sys.argv[1:]
    term = None
    if "--term" in argv:
        i = argv.index("--term")
        term = argv[i + 1] if i + 1 < len(argv) else None
        argv = argv[:i] + argv[i + 2:]
    opts, sids = rs.parse(argv)
    nterm = s3.norm_base(term).strip() if term else ""
    if not term or len(nterm) > 2 or len(nterm) == 0 or set(opts) != {"run", "batch", "label", "step"} or opts["step"] != "7" \
            or not re.fullmatch(r"S4-PILOT-R2-\d{3}|OB\d{4}-R2(\.\d+)?|OB\d{4}-R2S", opts["run"]) \
            or not re.fullmatch(r"PB\d{2}|B\d{4}|OB\d{4}", opts["batch"]) or not re.fullmatch(r"[A-Za-z0-9][A-Za-z0-9._-]*", opts["label"]) \
            or not sids or any(not re.fullmatch(r"S\d{4}", s) for s in sids) or opts.get("run") in rs.CLOSED_RUNS:
        print("usage: p3b_stage2a_scan.py --run R --batch B --label L --step 7 --term <<=2-char term> S#### [...]", file=sys.stderr)
        return 2
    resolver = dio.discovery_resolver()
    try:
        data = resolver.read_many(sids)
    except s3.IdentityError as e:
        rs.log(opts, sids, True)
        print(f"REFUSED: {e}", file=sys.stderr)
        return 1
    single = len(nterm) == 1
    needle = nterm if single else nterm.casefold()
    texts, occs = {}, {}
    for sid in sids:
        base = s3.norm_base(data[sid].decode("utf-8", errors="replace"))
        text = base if single else base.casefold()            # offsets and contexts both from this text
        occ, pos = [], text.find(needle)
        while pos != -1:
            if s3.bounded(text, pos, pos + len(needle)):
                occ.append(pos)
            pos = text.find(needle, pos + 1)
        texts[sid], occs[sid] = text, occ
    path = os.path.join(s3.CR, "ledger-p3b-r2", opts["run"], "READ-LOG.jsonl")
    os.makedirs(os.path.dirname(path), exist_ok=True)
    rec = {"utc": __import__("datetime").datetime.now(__import__("datetime").timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ"),
           "run_id": opts["run"], "batch_id": opts["batch"], "working_label": opts["label"], "step": 7,
           "source_ids": sids, "refused": False, "tool": ALGORITHM, "term": needle, "occurrences": occs,
           "bytes": {s: len(b) for s, b in data.items()}, "sha256": {s: s3.sha256_bytes(b) for s, b in data.items()}}
    with open(path, "a", encoding="utf-8") as f:
        f.write(__import__("json").dumps(rec, sort_keys=True, separators=(",", ":"), ensure_ascii=False) + "\n")
    try:
        for sid in sids:
            print(f"=== {sid} (sha256 {resolver.rows[sid]['content_sha256']}) algorithm {ALGORITHM} term {nterm} "
                  f"occurrences {len(occs[sid])} ===")
            for o in occs[sid]:
                ctx = texts[sid][max(0, o - CONTEXT):o + len(needle) + CONTEXT].replace("\n", " ⏎ ")
                print(f"@{o} …{ctx}…")
    except BrokenPipeError:
        os.dup2(os.open(os.devnull, os.O_WRONLY), sys.stdout.fileno())
    return 0


if __name__ == "__main__":
    sys.exit(main())
