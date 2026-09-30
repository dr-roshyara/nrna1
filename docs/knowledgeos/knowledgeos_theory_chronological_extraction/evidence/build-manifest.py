#!/usr/bin/env python3
"""
build-manifest.py - derive the authoritative evidence baseline from the
canonical corpus log. STEP 3 of the RCI remediation.

    python3 evidence/build-manifest.py            # build / rebuild
    python3 evidence/build-manifest.py --verify   # exit 0 iff current

WHAT THIS IS
------------
docs/knowledgeos/list_of_files_to_read.log is the canonical registry: its
first column IS the file_id (Master Protocol §1123, §1225, §4509). This script
READS it and derives:

    CORPUS-MANIFEST.jsonl   file_id, canonical_path, timestamp, sha256, exists
    MANIFEST-HASH.txt       corpus_manifest_hash = sha256 of the manifest

WHAT THIS IS NOT
----------------
⛔ It NEVER writes to the canonical log. The log is read-only input.
⛔ It assigns no identifiers. Every file_id is COPIED from the log's first
   column, never minted — which is the rule ID-ROOTCAUSE-01 records as broken.
⛔ It repairs nothing. FILE-REGISTRY.jsonl is not read, written or consulted.

The manifest hash exists so that every research unit can bind itself to a
specific corpus version. The Master Protocol specified this (§3955) and it was
never implemented; its absence is why the F0031 divergence went undetected.
"""

import argparse
import hashlib
import json
import os
import re
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
EXTRACTION = os.path.dirname(HERE)                      # …/docs/knowledgeos/<extraction>
KNOWLEDGEOS = os.path.dirname(EXTRACTION)               # …/docs/knowledgeos
REPO = os.path.dirname(os.path.dirname(KNOWLEDGEOS))    # repo root

# The log sits beside the extraction tree; derive it from there rather than
# rebuilding the path from the repo root, which is one join away from silently
# pointing somewhere plausible-but-wrong.
CANONICAL_LOG = os.path.join(KNOWLEDGEOS, "list_of_files_to_read.log")
MANIFEST = os.path.join(HERE, "CORPUS-MANIFEST.jsonl")
HASH_FILE = os.path.join(HERE, "MANIFEST-HASH.txt")

# Verified against the file itself, not assumed (cat -A):
#   F0001<TAB>Aug 5 15:44 docs/knowledgeos/<path>
# TWO tab-fields; the timestamp and path are space-separated inside the second.
ROW = re.compile(r"^(F\d{4})\t([A-Z][a-z]{2}\s+\d{1,2}\s+[\d:]+)\s+(\S.*?)\s*$")


def sha256_file(path):
    h = hashlib.sha256()
    try:
        with open(path, "rb") as fh:
            for chunk in iter(lambda: fh.read(65536), b""):
                h.update(chunk)
    except OSError:
        return None
    return h.hexdigest()


def read_canonical():
    """Parse the canonical log. Refuses on any unparseable or duplicate row:
    a manifest built from a partially-understood source is worse than none."""
    if not os.path.exists(CANONICAL_LOG):
        sys.exit(f"STOP: canonical log absent at {CANONICAL_LOG}")
    rows, seen_id, seen_path, fatal = [], {}, {}, []
    with open(CANONICAL_LOG, encoding="utf-8") as fh:
        for n, line in enumerate(fh, 1):
            if not line.strip():
                continue
            m = ROW.match(line.rstrip("\n"))
            if not m:
                fatal.append(f"{n}:unparseable")
                continue
            fid, ts, path = m.group(1), m.group(2).strip(), m.group(3).strip()
            # A duplicate file_id destroys identity itself -> FATAL.
            if fid in seen_id:
                fatal.append(f"{n}:duplicate id {fid} (first at {seen_id[fid]})")
            # A duplicate PATH does not destroy identity. It is RECORDED, not
            # fatal: the log's paths are space-truncated for filenames
            # containing spaces, so two rows can share a prefix. Refusing to
            # build over a 0.6% path defect would discard a sound identity
            # column. Fail-closed belongs at ADMISSION, not here.
            dup_path = path in seen_path
            seen_id[fid] = n
            seen_path.setdefault(path, n)
            rows.append({"file_id": fid, "canonical_path": path,
                         "timestamp": ts, "sequence": len(rows) + 1,
                         "duplicate_path": dup_path})
    if fatal:
        sys.exit(f"STOP: canonical log identity is not sound: {fatal[:10]}"
                 f"{' …' if len(fatal) > 10 else ''}")
    return rows


def build(rows):
    present = missing = 0
    for r in rows:
        abs_path = os.path.join(REPO, r["canonical_path"])
        digest = sha256_file(abs_path)
        r["sha256"] = digest
        r["exists"] = digest is not None
        if digest is None:
            # Recorded, never silently dropped. A row whose path does not
            # resolve is UNUSABLE for admission and must say so.
            r["defect"] = "PATH_DOES_NOT_RESOLVE"
            r["defect_note"] = ("suspected space-truncated filename in the "
                                "canonical log; the row cannot identify a file")
        present += bool(digest)
        missing += (digest is None)
    return present, missing


def manifest_bytes(rows):
    """Canonical serialization — sorted keys, one row per line, so the hash is
    reproducible on any machine."""
    return "".join(
        json.dumps(r, sort_keys=True, ensure_ascii=False) + "\n" for r in rows
    ).encode("utf-8")


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--verify", action="store_true",
                    help="exit 0 iff the stored manifest matches a fresh build")
    a = ap.parse_args()

    rows = read_canonical()
    present, missing = build(rows)
    blob = manifest_bytes(rows)
    digest = hashlib.sha256(blob).hexdigest()

    if a.verify:
        if not (os.path.exists(MANIFEST) and os.path.exists(HASH_FILE)):
            print("STATUS: MANIFEST_ABSENT")
            return 3
        stored_hash = open(HASH_FILE, encoding="utf-8").read().strip().split()[0]
        stored_blob = open(MANIFEST, "rb").read()
        same_hash = stored_hash == digest
        same_blob = stored_blob == blob
        print(f"entries      : {len(rows)}")
        print(f"present/miss : {present}/{missing}")
        print(f"stored hash  : {stored_hash[:16]}…")
        print(f"fresh  hash  : {digest[:16]}…")
        print("STATUS: " + ("CURRENT" if (same_hash and same_blob)
                            else "STALE_OR_CORPUS_CHANGED"))
        return 0 if (same_hash and same_blob) else 3

    with open(MANIFEST, "wb") as fh:
        fh.write(blob)
    with open(HASH_FILE, "w", encoding="utf-8") as fh:
        fh.write(digest + "  corpus_manifest_hash\n")
    print(f"entries      : {len(rows)}")
    print(f"present      : {present}")
    print(f"missing      : {missing}")
    print(f"corpus_manifest_hash: {digest}")
    return 0


if __name__ == "__main__":
    sys.exit(main())
