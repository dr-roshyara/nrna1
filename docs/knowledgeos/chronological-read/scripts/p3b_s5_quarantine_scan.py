#!/usr/bin/env python3
"""P3b S5 quarantine scanner (H-19 addendum v1.5 §6; plan v2.3.2 §M item 4; deliverable O-19) and the O-22
`09-ORCHESTRATOR-FLAGS` discovery extract builder (plan §B.6 (a); G-LOG-0041 "orchestrator flags").

This module is the reviewed SCANNER and FILTER of the H-19 protection. It is the only S5 operations module allowed to
obtain the seal's hold-out lists (through `p3b_s5_common`) and the only one allowed to name the quarantine file. It
never prints, logs or writes a hold-out label or file name: every output carries counts only.

  scan PATH [PATH ...] [--quarantine Q] [--context TEXT] [--no-write]
      Scan agent inputs / discovery records. `.jsonl`: one record per line (record index = line index, 0-based);
      `.json`: the whole document is record 0; any other file: prose, one record per line. A record is a hit when it
      names a hold-out label (addendum §3 name matching over `json.dumps(record, ensure_ascii=False)` for JSON, the
      raw line for prose; for every record a match is also tested after removing one trailing `.`) or cites a
      hold-out S-id outside the addendum §4 listed mentions (the mentions are allowed only for the record's own
      label: `working_label` / `label`, else the JSON file's stem). Hits are appended to the quarantine file
      (default `P3B-HOLDOUT-QUARANTINE.jsonl`, append-only) as {path, record_index, counts}. Exit 1 on any hit.

  extract-flags --out PATH [--src 09-ORCHESTRATOR-FLAGS.md]
      O-22: write the discovery-filtered extract by dropping every line that names a hold-out label or cites a
      hold-out S-id (no §4 mention is allowed: the flags file has no label context), then scan the extract; it must
      scan clean. Prints line counts and the extract sha256 only. The operational destination is
      `_batch_input_r2/s5/09-ORCHESTRATOR-FLAGS.discovery.md`.

The seal is asserted SEALED at the start and the end of every command (plan §M item 2).
Exit codes: 0 clean · 1 hit / refusal · 2 usage.
"""
import argparse
import json
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, _HERE)
import p3b_s5_ops_lib as ops  # noqa: E402

c = ops.load_common()
QUARANTINE = "P3B-HOLDOUT-QUARANTINE.jsonl"
FLAGS = "09-ORCHESTRATOR-FLAGS.md"
FLAGS_EXTRACT = "_batch_input_r2/s5/09-ORCHESTRATOR-FLAGS.discovery.md"
_LABEL_CH = "A-Za-z0-9._-"


def _sets(sets=None):
    """(H, HF, allowed_mentions). Filter/scanner use only; never printed."""
    return sets if sets is not None else c.holdout_sets()


def _dot_pattern(H):
    """Addendum §3: for §6 quarantine of prose, a match is also tested after removing one trailing '.'."""
    alts = "|".join(map(re.escape, sorted(H, key=len, reverse=True)))
    return re.compile(rf"(?<![{_LABEL_CH}])({alts})\.(?![{_LABEL_CH}])")


def scan_text(text, label=None, sets=None):
    """Counts only: (hold-out label names, hold-out S-ids cited outside the §4 mentions allowed for `label`)."""
    # the single definition of addendum §3 matching (incl. the trailing-'.' variant) lives in p3b_s5_common
    return c.quarantine_hits(text, label, _sets(sets))


def _record_label(rec, path):
    if isinstance(rec, dict):
        for k in ("working_label", "label"):
            if isinstance(rec.get(k), str):
                return rec[k]
    if path.endswith(".json"):
        return os.path.splitext(os.path.basename(path))[0]
    return None


def iter_records(path):
    """(record_index, text, label) for one file."""
    with open(path, encoding="utf-8", errors="replace") as f:
        raw = f.read()
    if path.endswith(".jsonl"):
        for i, line in enumerate(raw.split("\n")):
            if not line.strip():
                continue
            try:
                rec = json.loads(line)
                yield i, json.dumps(rec, ensure_ascii=False), _record_label(rec, path)
            except json.JSONDecodeError:
                yield i, line, None
    elif path.endswith(".json"):
        try:
            rec = json.loads(raw)
            yield 0, json.dumps(rec, ensure_ascii=False), _record_label(rec, path)
        except json.JSONDecodeError:
            yield 0, raw, None
    else:
        for i, line in enumerate(raw.split("\n")):
            yield i, line, None


def expand(paths):
    out = []
    for p in paths:
        ap = ops.abspath(p)
        if os.path.isdir(ap):
            for root, dirs, files in os.walk(ap):
                dirs.sort()
                out += [os.path.join(root, f) for f in sorted(files)]
        else:
            out.append(ap)
    return out


def refuse_unlisted(files, purpose=None):
    """M2 (G-LOG-0042): every input must pass `check_paths`; a refusal names only `<refused path #n>` (n = 1-based
    position in the expanded input list), never the path itself (it could be a hold-out label name)."""
    refused = []
    for n, fp in enumerate(files, 1):
        try:
            ops.check_paths([fp], purpose=purpose)
        except c.S5Error:
            refused.append(f"<refused path #{n}>")
    if refused:
        raise c.S5Error(f"{len(refused)} input path(s) refused: {' '.join(refused)}")


def scan_paths(paths, sets=None):
    """Returns (n_records_scanned, hits) with hits = [{path, record_index, n_label_names, n_holdout_sids}]. `path` is
    the repository-relative path for an allowlisted file, else `<unlisted path #n>` (outside-repository copies)."""
    files = expand(paths)
    refuse_unlisted(files)
    sets = _sets(sets)
    n, hits = 0, []
    for k, fp in enumerate(files, 1):
        for i, text, label in iter_records(fp):
            n += 1
            nl, nf = scan_text(text, label, sets)
            if nl or nf:
                hits.append({"path": ops.display(fp, k), "record_index": i, "n_label_names": nl, "n_holdout_sids": nf})
    return n, hits


def quarantine_records(hits, context):
    blob = ops.script_blob(__file__)
    return [{"utc": ops.utc_now(), "artifact": QUARANTINE, "seal_id": c.SEAL_ID, "scanner": "scripts/p3b_s5_quarantine_scan.py",
             "scanner_version": blob, "context": context, **h} for h in hits]


# ---------------------------------------------------------------- helpers for other S5 tools (counts / discovery only)
def count_holdout_sids(sids, sets=None):
    """Number of the given S-ids that are hold-out files (the ids themselves are never returned)."""
    _, HF, _ = _sets(sets)
    return sum(1 for s in sids if s in HF)


def discovery_only(sids, sets=None):
    """The given S-ids minus hold-out files, and the count dropped."""
    _, HF, _ = _sets(sets)
    keep = [s for s in sids if s not in HF]
    return keep, len(sids) - len(keep)


def labels_in_holdout(labels, sets=None):
    H, _, _ = _sets(sets)
    return sum(1 for x in labels if x in H)


# ---------------------------------------------------------------- O-22 extract
def build_flags_extract(src_text, sets=None):
    """(extract_text, n_lines, n_kept, n_dropped). A line is dropped if it names a hold-out label or cites a hold-out
    S-id (label context None: no §4 mention applies to the flags file)."""
    sets = _sets(sets)
    lines = src_text.split("\n")
    kept = [ln for ln in lines if scan_text(ln, None, sets) == (0, 0)]
    return "\n".join(kept), len(lines), len(kept), len(lines) - len(kept)


def cmd_scan(a):
    c.assert_sealed()
    n, hits = scan_paths(a.paths)
    if hits and not a.no_write:
        ops.append_jsonl(a.quarantine, quarantine_records(hits, a.context))
    c.assert_sealed()
    print(json.dumps({"records_scanned": n, "records_hit": len(hits),
                      "label_names": sum(h["n_label_names"] for h in hits),
                      "holdout_sids": sum(h["n_holdout_sids"] for h in hits),
                      "quarantine_written": bool(hits and not a.no_write)}, sort_keys=True))
    for h in hits:
        print(f"HIT {h['path']}#{h['record_index']}: label_names={h['n_label_names']} holdout_sids={h['n_holdout_sids']}")
    return 1 if hits else 0


def cmd_extract(a):
    c.assert_sealed()
    ops.check_paths([a.src], purpose="o22-extract")
    if ops.inside_cr(a.out) and not a.allow_repo_write:
        raise c.S5Error("the extract is written inside the repository only with --allow-repo-write (dispatch time)")
    with open(ops.abspath(a.src), encoding="utf-8") as f:
        src = f.read()
    text, n, k, d = build_flags_extract(src)
    ops.write_atomic(a.out, text)
    n2, hits = scan_paths([a.out])
    c.assert_sealed()
    out = {"source_lines": n, "kept_lines": k, "dropped_lines": d, "extract_sha256": ops.sha256_path(ops.abspath(a.out)),
           "source_sha256": c.sha256_file(a.src), "post_scan_records": n2, "post_scan_hits": len(hits)}
    print(json.dumps(out, sort_keys=True))
    return 1 if hits else 0


def main(argv=None):
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    sub = ap.add_subparsers(dest="cmd", required=True)
    s = sub.add_parser("scan")
    s.add_argument("paths", nargs="+")
    s.add_argument("--quarantine", default=QUARANTINE)
    s.add_argument("--context", default="pre-dispatch")
    s.add_argument("--no-write", action="store_true")
    e = sub.add_parser("extract-flags")
    e.add_argument("--src", default=FLAGS)
    e.add_argument("--out", required=True)
    e.add_argument("--allow-repo-write", action="store_true")
    a = ap.parse_args(argv)
    try:
        return cmd_scan(a) if a.cmd == "scan" else cmd_extract(a)
    except c.S5Error as ex:
        print(f"REFUSED: {ex}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
