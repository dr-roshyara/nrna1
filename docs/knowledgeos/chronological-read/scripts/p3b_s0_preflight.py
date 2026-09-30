#!/usr/bin/env python3
"""P3b S0 PREFLIGHT — Appendix A.1 of the frozen P3b operating protocol v1.6.4
(`prompts/20260924_1242_p3b-phase1-continuation-protocol-v1.6.4.md`, sha256 08f10e74…; G-LOG-0001).

Read-only verification of the §5 existing-state assumptions before any P3b step:

  1. Reference list = every path of `git ls-tree -r --name-only c9e76918b -- docs/knowledgeos/chronological-read`,
     excluding `prompts/` and the P3b output paths named in A.1 (P3B-*, AFFECTED.jsonl, B-REPRODUCED.jsonl,
     _batch_manifest_p3b_r2.jsonl, _batch_input_r2/, ledger-p3b-r2/, audit-p3b/, 30-*, 32-*).
     For each path: sha256(`git show c9e76918b:<path>`) must equal sha256(working-tree file).
  2. Counts: 2,779 records in 02-FILES.jsonl; 27,906 in 03-CONTRIBUTIONS.jsonl; 2,497 labels in
     _derived.json["reconciliation_objects"]; 1,793 pairs in 31-RECONCILIATION-PAIRS.jsonl; OB0001/2/3 with 80/80/81.
  3. Working tree clean for chronological-read/ (§19.1 S0 row). Interpretation (human-reviewed): no tracked file under
     the directory is modified, deleted or renamed, and the ONLY untracked file permitted is S0's own output
     `P3B-PREFLIGHT.json` (explicit allowlist S0_OWNED_OUTPUTS). Every other untracked file is a failure.

Writes `P3B-PREFLIGHT.json` = {"header": {...§19.5...}, "body": {...}}. `output_sha256` is the sha256 of the canonical
JSON of "body" (sort_keys=True, separators=(",", ":"), ensure_ascii=False), i.e. the body excluding the header (§19.5).

It never modifies a reference file. Any mismatch → exit 1 and STOP ALL EXECUTION, human escalation (§22).

Usage:  python3 p3b_s0_preflight.py            # verify and write P3B-PREFLIGHT.json
        python3 p3b_s0_preflight.py --dry-run  # verify and print; write nothing
Exit:   0 PASS · 1 FAIL (stop, §22) · 2 environment/usage error
"""
import datetime
import fnmatch
import hashlib
import json
import os
import platform
import subprocess
import sys

REF_COMMIT = "c9e76918b"
EXPECTED_COUNTS = {
    "02-FILES.jsonl records": 2779,
    "03-CONTRIBUTIONS.jsonl records": 27906,
    "_derived.json reconciliation_objects labels": 2497,
    "31-RECONCILIATION-PAIRS.jsonl pairs": 1793,
    "ledger-p3b/OB0001/objects.jsonl records": 80,
    "ledger-p3b/OB0002/objects.jsonl records": 80,
    "ledger-p3b/OB0003/objects.jsonl records": 81,
}
# A.1 exclusions, relative to docs/knowledgeos/chronological-read/
EXCLUDE_PREFIXES = ("prompts/", "_batch_input_r2/", "ledger-p3b-r2/", "audit-p3b/")
EXCLUDE_GLOBS = ("P3B-*", "AFFECTED.jsonl", "B-REPRODUCED.jsonl", "_batch_manifest_p3b_r2.jsonl", "30-*", "32-*")
# Untracked files permitted by check 3: S0's own output only (exact paths relative to chronological-read/).
S0_OWNED_OUTPUTS = frozenset({"P3B-PREFLIGHT.json"})

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR_REL = "docs/knowledgeos/chronological-read"
CR = os.path.join(REPO_ROOT, CR_REL)
OUT = os.path.join(CR, "P3B-PREFLIGHT.json")


def git(*args, binary=False):
    out = subprocess.run(["git", "-C", REPO_ROOT, *args], capture_output=True, check=True)
    return out.stdout if binary else out.stdout.decode("utf-8")


def excluded(rel):
    """rel is relative to chronological-read/. A path is excluded if it is under an excluded prefix, or if its
    top-level name matches an excluded glob (A.1 names these as paths at the directory's root)."""
    if rel.startswith(EXCLUDE_PREFIXES):
        return True
    top = rel.split("/", 1)[0]
    return any(fnmatch.fnmatchcase(top, g) for g in EXCLUDE_GLOBS)


def sha256_bytes(b):
    return hashlib.sha256(b).hexdigest()


def count_jsonl(path):
    with open(path, encoding="utf-8") as f:
        return sum(1 for line in f if line.strip())


def main():
    dry_run = "--dry-run" in sys.argv[1:]
    unknown = [a for a in sys.argv[1:] if a != "--dry-run"]
    if unknown:
        print(f"usage error: unknown arguments {unknown}", file=sys.stderr)
        return 2
    try:
        git("cat-file", "-e", REF_COMMIT + "^{commit}")
    except subprocess.CalledProcessError:
        print(f"environment error: reference commit {REF_COMMIT} not found", file=sys.stderr)
        return 2

    # ---- 1. reference-list hash comparison ----
    listed = git("ls-tree", "-r", "--name-only", REF_COMMIT, "--", CR_REL).splitlines()
    reference, skipped = [], []
    for full in listed:
        rel = full[len(CR_REL) + 1:]
        (skipped if excluded(rel) else reference).append(full)
    mismatches, missing, file_hashes = [], [], {}
    for full in reference:
        ref_sha = sha256_bytes(git("show", f"{REF_COMMIT}:{full}", binary=True))
        wt_path = os.path.join(REPO_ROOT, full)
        if not os.path.isfile(wt_path):
            missing.append(full)
            continue
        with open(wt_path, "rb") as f:
            wt_sha = sha256_bytes(f.read())
        file_hashes[full] = wt_sha
        if wt_sha != ref_sha:
            mismatches.append({"path": full, "reference_sha256": ref_sha, "working_tree_sha256": wt_sha})

    # ---- 2. counts ----
    observed = {}
    try:
        observed["02-FILES.jsonl records"] = count_jsonl(os.path.join(CR, "02-FILES.jsonl"))
        observed["03-CONTRIBUTIONS.jsonl records"] = count_jsonl(os.path.join(CR, "03-CONTRIBUTIONS.jsonl"))
        with open(os.path.join(CR, "20-FAMILIES", "_derived.json"), encoding="utf-8") as f:
            observed["_derived.json reconciliation_objects labels"] = len(json.load(f)["reconciliation_objects"])
        observed["31-RECONCILIATION-PAIRS.jsonl pairs"] = count_jsonl(os.path.join(CR, "31-RECONCILIATION-PAIRS.jsonl"))
        for b in ("OB0001", "OB0002", "OB0003"):
            observed[f"ledger-p3b/{b}/objects.jsonl records"] = count_jsonl(os.path.join(CR, "ledger-p3b", b, "objects.jsonl"))
    except (OSError, KeyError, json.JSONDecodeError) as e:
        print(f"FAIL: cannot read a count input: {e}", file=sys.stderr)
        # Deterministic: record the error type only (the message can contain machine-specific absolute paths).
        observed.setdefault("_error", type(e).__name__)
    count_failures = {k: {"expected": v, "observed": observed.get(k)} for k, v in EXPECTED_COUNTS.items()
                      if observed.get(k) != v}

    # ---- 3. working tree clean (interpretation in the docstring) ----
    status = git("status", "--porcelain", "--untracked-files=all", "--", CR_REL).splitlines()
    dirty_tracked, untracked_disallowed = [], []
    for line in status:
        code, path = line[:2], line[3:]
        rel = path[len(CR_REL) + 1:] if path.startswith(CR_REL + "/") else path
        if code == "??":
            if rel not in S0_OWNED_OUTPUTS:
                untracked_disallowed.append(path)
        else:
            dirty_tracked.append(line)

    ok = not (mismatches or missing or count_failures or dirty_tracked or untracked_disallowed)

    body = {
        "reference_commit": REF_COMMIT,
        "reference_files_checked": len(reference),
        "reference_files_excluded_by_A1": len(skipped),
        "hash_mismatches": mismatches,
        "missing_files": missing,
        "counts_observed": observed,
        "count_failures": count_failures,
        "tracked_changes_in_chronological_read": dirty_tracked,
        "untracked_non_output_files": untracked_disallowed,
        "reference_file_sha256": file_hashes,
        "result": "PASS" if ok else "FAIL",
    }
    body_json = json.dumps(body, sort_keys=True, separators=(",", ":"), ensure_ascii=False)
    script_path = os.path.abspath(__file__)
    header = {
        "script_name": "p3b_s0_preflight.py",
        "script_version": git("hash-object", script_path).strip(),
        "script_committed_unmodified": git("status", "--porcelain", "--", script_path).strip() == "",
        "protocol": "prompts/20260924_1242_p3b-phase1-continuation-protocol-v1.6.4.md",
        "protocol_sha256_expected": "08f10e74d9bb1820ef986390475c40fa0fff7634dd052d737e81c8eefa1dec06",
        "parameters": {"reference_commit": REF_COMMIT, "expected_counts": EXPECTED_COUNTS,
                       "exclude_prefixes": list(EXCLUDE_PREFIXES), "exclude_globs": list(EXCLUDE_GLOBS)},
        "seed": None,
        "input_hashes": {"reference_list_sha256": sha256_bytes("\n".join(reference).encode("utf-8"))},
        "output_sha256": sha256_bytes(body_json.encode("utf-8")),
        "python_version": platform.python_version(),
        "platform": platform.platform(),
        # Run metadata lives in the header only, never in the hashed body.
        "run_timestamp_utc": datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ"),
    }
    proto = os.path.join(CR, header["protocol"])
    with open(proto, "rb") as f:
        header["protocol_sha256_observed"] = sha256_bytes(f.read())
    if header["protocol_sha256_observed"] != header["protocol_sha256_expected"]:
        ok = False
        body["result"] = "FAIL"
        body["protocol_hash_mismatch"] = True
        body_json = json.dumps(body, sort_keys=True, separators=(",", ":"), ensure_ascii=False)
        header["output_sha256"] = sha256_bytes(body_json.encode("utf-8"))

    print(f"S0 PREFLIGHT: reference files checked {len(reference)} (excluded {len(skipped)}); "
          f"mismatches {len(mismatches)}; missing {len(missing)}; count failures {len(count_failures)}; "
          f"tracked changes {len(dirty_tracked)}; untracked non-output {len(untracked_disallowed)}; "
          f"protocol hash {'OK' if header['protocol_sha256_observed'] == header['protocol_sha256_expected'] else 'MISMATCH'}")
    for m in mismatches[:20]:
        print(f"  MISMATCH {m['path']}")
    for m in missing[:20]:
        print(f"  MISSING  {m}")
    for k, v in count_failures.items():
        print(f"  COUNT    {k}: expected {v['expected']}, observed {v['observed']}")
    for l in dirty_tracked[:20]:
        print(f"  TRACKED CHANGE {l}")
    for l in untracked_disallowed[:20]:
        print(f"  UNTRACKED      {l}")

    if not dry_run:
        with open(OUT, "w", encoding="utf-8") as f:
            json.dump({"header": header, "body": body}, f, ensure_ascii=False, indent=1, sort_keys=True)
            f.write("\n")
        print(f"wrote {os.path.relpath(OUT, REPO_ROOT)}")
    print("S0 RESULT:", "PASS" if ok else "FAIL — STOP ALL EXECUTION; human escalation (§22)")
    return 0 if ok else 1


if __name__ == "__main__":
    sys.exit(main())
