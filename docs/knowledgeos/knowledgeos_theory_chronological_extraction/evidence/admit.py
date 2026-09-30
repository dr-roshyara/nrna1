#!/usr/bin/env python3
"""
admit.py - deterministic identity admission + read receipts. STEPS 4, 5, 6.

    python3 evidence/admit.py --resolve <path-or-file_id>     # step 4
    python3 evidence/admit.py --receipt <path-or-file_id> --unit U   # step 5
    python3 evidence/admit.py --audit                        # step 6

THE CHAIN THIS BUILDS
---------------------
    CANONICAL MANIFEST -> file_id, canonical_path, sha256
              |
              v
    IDENTITY ADMISSION      (--resolve)  ADMIT or STOP
              |
              v
    READ RECEIPT            (--receipt)  file_id, path, sha256_at_read,
              |                          bytes_read, manifest_hash, unit
              v
    ...source passages -> derivation -> theory claim   (NOT built here)

FAIL-CLOSED (step 6)
--------------------
Exit 0 = ADMIT. Exit 3 = STOP. There is no third outcome and no warning that
lets work continue. A file is admissible only when the manifest resolves it to
exactly ONE file_id AND the file exists AND its sha256 matches the manifest.
Anything else -- unknown path, ambiguous prefix, missing file, changed bytes,
stale manifest -- is STOP.

⛔ A manifest hash alone proves only "I worked against this corpus version".
   It does NOT prove a particular file was read. That is what the receipt is
   for, and neither substitutes for the other.

⛔ This tool REPAIRS NOTHING. It does not read, write or consult
   FILE-REGISTRY.jsonl. The F0031-F0040 divergence is untouched by design.
"""

import argparse
import hashlib
import json
import os
import sys
import datetime

HERE = os.path.dirname(os.path.abspath(__file__))
EXTRACTION = os.path.dirname(HERE)
KNOWLEDGEOS = os.path.dirname(EXTRACTION)
REPO = os.path.dirname(os.path.dirname(KNOWLEDGEOS))

MANIFEST = os.path.join(HERE, "CORPUS-MANIFEST.jsonl")
HASH_FILE = os.path.join(HERE, "MANIFEST-HASH.txt")
RECEIPTS = os.path.join(HERE, "READ-RECEIPTS.jsonl")

ADMIT, STOP = 0, 3


def stop(reason, **detail):
    print("STATUS: STOP")
    print(f"  reason: {reason}")
    for k, v in detail.items():
        print(f"  {k}: {v}")
    print("  ⛔ Not admissible. This grants no permission to read, cite or "
          "record this file as evidence.")
    return STOP


def sha256_file(path):
    h = hashlib.sha256()
    try:
        with open(path, "rb") as fh:
            for chunk in iter(lambda: fh.read(65536), b""):
                h.update(chunk)
    except OSError:
        return None
    return h.hexdigest()


def load_manifest():
    if not os.path.exists(MANIFEST) or not os.path.exists(HASH_FILE):
        return None, None
    rows = [json.loads(l) for l in open(MANIFEST, encoding="utf-8") if l.strip()]
    mhash = open(HASH_FILE, encoding="utf-8").read().strip().split()[0]
    return rows, mhash


def resolve(rows, token):
    """Resolve a token to EXACTLY ONE manifest row, or explain why not.

    Returns (row, None) on success, (None, reason) otherwise. A prefix that
    matches several rows is AMBIGUOUS, never 'the first match' -- the
    space-truncated paths in the canonical log make near-misses real.
    """
    by_id = [r for r in rows if r["file_id"] == token]
    if by_id:
        return by_id[0], None

    norm = token
    for prefix in (REPO + os.sep, "./"):
        if norm.startswith(prefix):
            norm = norm[len(prefix):]
    exact = [r for r in rows if r["canonical_path"] == norm]
    if len(exact) == 1:
        return exact[0], None
    if len(exact) > 1:
        return None, f"AMBIGUOUS: {len(exact)} manifest rows share this path"

    base = [r for r in rows if os.path.basename(r["canonical_path"]) ==
            os.path.basename(norm)]
    if len(base) == 1:
        return base[0], None
    if len(base) > 1:
        return None, (f"AMBIGUOUS: {len(base)} rows share basename "
                      f"{os.path.basename(norm)!r}")
    return None, "NOT_IN_MANIFEST: the canonical manifest contains no such file"


def check(row):
    """Full admission check for a resolved row."""
    if row.get("defect"):
        return None, f"MANIFEST_ROW_DEFECTIVE: {row['defect']} — {row.get('defect_note','')}"
    abs_path = os.path.join(REPO, row["canonical_path"])
    if not os.path.exists(abs_path):
        return None, "FILE_ABSENT: manifest row resolves to no file on disk"
    digest = sha256_file(abs_path)
    if digest != row["sha256"]:
        return None, (f"SHA_MISMATCH: file changed since the manifest was "
                      f"built (manifest {str(row['sha256'])[:12]}…, "
                      f"now {str(digest)[:12]}…)")
    return digest, None


def cmd_resolve(rows, mhash, token, quiet=False):
    row, why = resolve(rows, token)
    if why:
        return stop(why, token=token)
    digest, why = check(row)
    if why:
        return stop(why, file_id=row["file_id"], path=row["canonical_path"])
    if not quiet:
        print("STATUS: ADMIT")
        print(f"  file_id       : {row['file_id']}")
        print(f"  canonical_path: {row['canonical_path']}")
        print(f"  sha256        : {digest}")
        print(f"  manifest_hash : {mhash}")
    return ADMIT


def cmd_receipt(rows, mhash, token, unit):
    row, why = resolve(rows, token)
    if why:
        return stop(why, token=token)
    digest, why = check(row)
    if why:
        return stop(why, file_id=row["file_id"], path=row["canonical_path"])
    abs_path = os.path.join(REPO, row["canonical_path"])
    rec = {
        "file_id": row["file_id"],
        "canonical_path": row["canonical_path"],
        "sha256_at_read": digest,
        "bytes_read": os.path.getsize(abs_path),
        "manifest_hash": mhash,
        "unit": unit,
        "when": datetime.date.today().isoformat(),
    }
    with open(RECEIPTS, "a", encoding="utf-8") as fh:
        fh.write(json.dumps(rec, ensure_ascii=False) + "\n")
    print("STATUS: ADMIT")
    print(f"  receipt written: {rec['file_id']}  {rec['sha256_at_read'][:12]}…  unit={unit}")
    return ADMIT


RECEIPT_FIELDS = ("file_id", "canonical_path", "sha256_at_read",
                  "bytes_read", "manifest_hash", "unit")


def classify_receipt_rows(lines):
    """Split the receipt stream into (receipts, voids, unknown_line_numbers).

    L0-DEC-18. The stream holds two declared shapes:
      receipt — every RECEIPT_FIELDS key present (cmd_receipt writes these);
      VOID    — status starts with "VOID", correction_of present, NO
                sha256_at_read / manifest_hash, and file_id names a file with
                an EARLIER receipt (row 8, `cd845b6ab`).
    Anything else is unrecognised: reported as a binding failure by the
    caller, never skipped and never a crash. A receipt cannot be hidden by
    giving it a VOID status, because a VOID may carry no receipt fields.

    L0-DEC-21 (IR-A1): `lines` are the raw non-blank lines. A line that is not
    valid JSON, or a row whose `file_id` is not a string, is unrecognised too.
    L0-DEC-24: the lines are BYTES and each is decoded here, so a line that is
    not valid UTF-8 is unrecognised as well (UnicodeDecodeError is a ValueError).
    """
    receipts, voids, unknown = [], [], []
    for n, line in enumerate(lines, 1):
        try:
            r = json.loads(line.decode("utf-8"))
        except ValueError:
            unknown.append(n)
            continue
        if not isinstance(r, dict) or not isinstance(r.get("file_id"), str):
            unknown.append(n)
        elif all(k in r for k in RECEIPT_FIELDS) and "status" not in r:
            receipts.append(r)
        elif (str(r.get("status", "")).startswith("VOID")
              and r.get("correction_of") and r.get("file_id")
              and "sha256_at_read" not in r and "manifest_hash" not in r
              and any(x["file_id"] == r["file_id"] for x in receipts)):
            voids.append(r)
        else:
            unknown.append(n)
    return receipts, voids, unknown


def cmd_audit(rows, mhash):
    """Step 6 across everything recorded so far. Reports; never repairs."""
    print("EVIDENCE-BINDING AUDIT")
    print("-" * 64)
    by_id = {r["file_id"]: r for r in rows}
    failures = 0

    print(f"manifest entries : {len(rows)}")
    print(f"manifest hash    : {mhash[:24]}…")
    defective = [r for r in rows if r.get("defect")]
    print(f"defective rows   : {len(defective)}  "
          f"({'recorded, unusable for admission' if defective else 'none'})")

    if not os.path.exists(RECEIPTS):
        print("read receipts    : NONE — no read has yet been bound to the manifest")
        receipts, voids, unknown = [], [], []
    else:
        receipts, voids, unknown = classify_receipt_rows(
            [l for l in open(RECEIPTS, "rb") if l.strip()])
        print(f"read receipts    : {len(receipts)}")
        if voids:
            print(f"VOID annotations : {len(voids)} (voiding: "
                  f"{', '.join(v['file_id'] for v in voids)}; the voided receipts "
                  f"stay in the checks below)")
    if unknown:
        failures += 1
        print(f"  ⛔ {len(unknown)} unrecognised receipt-stream row(s) at line(s) "
              f"{unknown}: neither a receipt nor a declared VOID annotation")

    stale = [r for r in receipts if r.get("manifest_hash") != mhash]
    if stale:
        failures += 1
        print(f"  ⛔ {len(stale)} receipt(s) bound to a DIFFERENT manifest hash")

    drifted = []
    for r in receipts:
        row = by_id.get(r["file_id"])
        if not row or row["sha256"] != r["sha256_at_read"]:
            drifted.append(r["file_id"])
    if drifted:
        failures += 1
        print(f"  ⛔ {len(drifted)} receipt(s) whose file changed since reading: {drifted[:8]}")

    # The correspondence question the five internal-consistency gates never
    # asked. Reported as a FINDING; repairing it is not this tool's business.
    reg = os.path.join(EXTRACTION, "FILE-REGISTRY.jsonl")
    if os.path.exists(reg):
        diverge = []
        for l in open(reg, encoding="utf-8"):
            if not l.strip():
                continue
            d = json.loads(l)
            row = by_id.get(d.get("file_id"))
            if row and row["canonical_path"] != d.get("path"):
                diverge.append(d["file_id"])
        print(f"registry vs manifest: {len(diverge)} divergent file_id(s)")
        if diverge:
            failures += 1
            print(f"  ⛔ {diverge}")
            print("  ⛔ KNOWN AND UNREPAIRED — see ID-ROOTCAUSE-01 and B-13.")
            print("     Reported here so it can never again pass unnoticed.")

    print("-" * 64)
    print("STATUS: " + ("BINDING_FAILURES_PRESENT" if failures else "BOUND"))
    return STOP if failures else ADMIT


def main():
    ap = argparse.ArgumentParser()
    g = ap.add_mutually_exclusive_group(required=True)
    g.add_argument("--resolve", metavar="TOKEN")
    g.add_argument("--receipt", metavar="TOKEN")
    g.add_argument("--audit", action="store_true")
    ap.add_argument("--unit", default=None, help="research unit id, for receipts")
    ap.add_argument("--quiet", action="store_true")
    a = ap.parse_args()

    rows, mhash = load_manifest()
    if rows is None:
        return stop("MANIFEST_ABSENT: run evidence/build-manifest.py first")

    if a.audit:
        return cmd_audit(rows, mhash)
    if a.resolve:
        return cmd_resolve(rows, mhash, a.resolve, a.quiet)
    if not a.unit:
        return stop("RECEIPT_REQUIRES_UNIT: a receipt not bound to a research "
                    "unit cannot be audited")
    return cmd_receipt(rows, mhash, a.receipt, a.unit)


if __name__ == "__main__":
    sys.exit(main())
