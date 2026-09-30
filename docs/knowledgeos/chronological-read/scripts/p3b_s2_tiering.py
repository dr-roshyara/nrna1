#!/usr/bin/env python3
"""P3b S2 TIERING — Appendix A.3 of the frozen P3b operating protocol v1.6.4 (G-LOG-0001); §9.3.

  Tier X = labels appearing as `a` or `b` in any AFFECTED pair (AFFECTED.jsonl, S1).
  Tier Z = labels with `pair_count = 0`.
  Tier U = the rest.
Output P3B-TIERS.jsonl with the causing pair ids.

Implementation decisions (stated for review):
  1. `pair_count` is read from 20-FAMILIES/_derived.json["reconciliation_objects"][label]["pair_count"] (the field A.3
     names). It is cross-checked against an independent count of the label's appearances in
     31-RECONCILIATION-PAIRS.jsonl; any disagreement → FAIL.
  2. Precedence X → Z → U. X ∩ Z must be empty (an affected label has ≥ 1 pair); otherwise FAIL.
  3. The label universe is the 2,497 labels of reconciliation_objects. An AFFECTED label outside it → FAIL.
  4. "Causing pair ids" exist only for Tier X: every AFFECTED pair touching the label, with its criteria. Tier Z and U
     records carry causing_pair_ids = [] and their pair_count.
  5. Expected sizes X 477 (S1), Z 1,120 (protocol §5.6), U 900 (= 2,497 − 477 − 1,120) are verification targets only.
     A mismatch → FAIL. No label list is embedded.

Gate: S1 PASS (AFFECTED.jsonl header result PASS; P3B-INPUT-MANIFEST.json result PASS; the AFFECTED body hash equals
both its header and the manifest); _derived.json and 31-RECONCILIATION-PAIRS.jsonl unchanged since S0; frozen protocol
hash.

Output (only on PASS, not with --dry-run): P3B-TIERS.jsonl — line 1 {"header": {...§19.5...}}; then one canonical-JSON
record per label, sorted by working_label: {working_label, tier, pair_count, causing_pair_ids: [{pair_id, criteria}]}.
output_sha256 = sha256 of the body bytes (the lines after the header, each ending "\\n").
Canonical JSON = json.dumps(obj, sort_keys=True, separators=(",", ":"), ensure_ascii=False), UTF-8.

Usage:  python3 p3b_s2_tiering.py [--dry-run]
Exit:   0 PASS · 1 FAIL (stop; human escalation, §22) · 2 usage error
"""
import collections
import datetime
import hashlib
import json
import os
import platform
import subprocess
import sys

EXPECTED = {"labels_total": 2497, "tier_X": 477, "tier_Z": 1120, "tier_U": 900}
PROTOCOL = "prompts/20260924_1242_p3b-phase1-continuation-protocol-v1.6.4.md"
PROTOCOL_SHA256 = "08f10e74d9bb1820ef986390475c40fa0fff7634dd052d737e81c8eefa1dec06"

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR_REL = "docs/knowledgeos/chronological-read"
CR = os.path.join(REPO_ROOT, CR_REL)
IN_DERIVED = "20-FAMILIES/_derived.json"
IN_PAIRS = "31-RECONCILIATION-PAIRS.jsonl"
IN_AFFECTED = "AFFECTED.jsonl"
IN_MANIFEST = "P3B-INPUT-MANIFEST.json"
IN_PREFLIGHT = "P3B-PREFLIGHT.json"
OUT_TIERS = "P3B-TIERS.jsonl"


def canon(obj):
    return json.dumps(obj, sort_keys=True, separators=(",", ":"), ensure_ascii=False)


def sha256_bytes(b):
    return hashlib.sha256(b).hexdigest()


def sha256_file(rel):
    with open(os.path.join(CR, rel), "rb") as f:
        return sha256_bytes(f.read())


def git(*args):
    return subprocess.check_output(["git", "-C", REPO_ROOT, *args], text=True)


def main():
    dry_run = "--dry-run" in sys.argv[1:]
    unknown = [a for a in sys.argv[1:] if a != "--dry-run"]
    if unknown:
        print(f"usage error: unknown arguments {unknown}", file=sys.stderr)
        return 2
    failures = []

    # ---- gate ----
    try:
        with open(os.path.join(CR, IN_AFFECTED), "rb") as f:
            aff_raw = f.read()
        with open(os.path.join(CR, IN_MANIFEST), encoding="utf-8") as f:
            manifest = json.load(f)
        with open(os.path.join(CR, IN_PREFLIGHT), encoding="utf-8") as f:
            pre = json.load(f)
    except OSError as e:
        print(f"FAIL: missing S0/S1 artifact ({type(e).__name__}) — S0 and S1 must PASS before S2", file=sys.stderr)
        return 1
    aff_lines = aff_raw.split(b"\n")
    aff_header = json.loads(aff_lines[0])["header"]
    aff_body_lines = [l for l in aff_lines[1:] if l]
    aff_body_sha = sha256_bytes(b"\n".join(aff_body_lines) + b"\n")
    if aff_header.get("result") != "PASS":
        failures.append("S1 AFFECTED.jsonl result is not PASS")
    if manifest.get("body", {}).get("result") != "PASS":
        failures.append("S1 manifest result is not PASS")
    if aff_body_sha != aff_header.get("output_sha256"):
        failures.append("AFFECTED.jsonl body hash differs from its header")
    if aff_body_sha != manifest.get("body", {}).get("outputs", {}).get(IN_AFFECTED, {}).get("output_sha256"):
        failures.append("AFFECTED.jsonl body hash differs from the S1 manifest")
    s0_hashes = pre.get("body", {}).get("reference_file_sha256", {})
    input_hashes = {}
    for rel in (IN_DERIVED, IN_PAIRS):
        h = sha256_file(rel)
        input_hashes[rel] = h
        if s0_hashes.get(f"{CR_REL}/{rel}") != h:
            failures.append(f"{rel} is not identical to its S0-recorded hash")
    for rel in (IN_AFFECTED, IN_MANIFEST, IN_PREFLIGHT):
        input_hashes[rel] = sha256_file(rel)
    proto_sha = sha256_file(PROTOCOL)
    if proto_sha != PROTOCOL_SHA256:
        failures.append("frozen protocol hash mismatch")

    # ---- inputs ----
    with open(os.path.join(CR, IN_DERIVED), encoding="utf-8") as f:
        objects = json.load(f)["reconciliation_objects"]
    with open(os.path.join(CR, IN_PAIRS), encoding="utf-8") as f:
        pairs = [json.loads(l) for l in f if l.strip()]
    affected = [json.loads(l) for l in aff_body_lines]
    universe = set(objects)

    # ---- pair_count: named field, cross-checked ----
    counted = collections.Counter()
    for p in pairs:
        counted[p["a"]] += 1
        counted[p["b"]] += 1
    pc_mismatch = sorted(l for l in universe if objects[l].get("pair_count") != counted.get(l, 0))
    if pc_mismatch:
        failures.append(f"pair_count field disagrees with 31-RECONCILIATION-PAIRS for {len(pc_mismatch)} labels "
                        f"(first: {pc_mismatch[:3]})")

    # ---- tiers ----
    causing = collections.defaultdict(list)
    for r in affected:
        for lab in (r["a"], r["b"]):
            causing[lab].append({"pair_id": r["pair_id"], "criteria": r["criteria"]})
    outside = sorted(set(causing) - universe)
    if outside:
        failures.append(f"{len(outside)} AFFECTED labels are outside the label universe (first: {outside[:3]})")
    records, tiers = [], collections.Counter()
    x_and_z = []
    for lab in sorted(universe):
        pc = objects[lab].get("pair_count")
        if lab in causing:
            tier = "X"
            if pc == 0:
                x_and_z.append(lab)
        elif pc == 0:
            tier = "Z"
        else:
            tier = "U"
        tiers[tier] += 1
        records.append({"working_label": lab, "tier": tier, "pair_count": pc,
                        "causing_pair_ids": sorted(causing.get(lab, []), key=lambda c: c["pair_id"])})
    if x_and_z:
        failures.append(f"{len(x_and_z)} labels are both affected and pairless (first: {x_and_z[:3]})")

    observed = {"labels_total": len(records), "tier_X": tiers["X"], "tier_Z": tiers["Z"], "tier_U": tiers["U"]}
    for k, v in EXPECTED.items():
        if observed[k] != v:
            failures.append(f"{k}: expected {v}, observed {observed[k]}")

    ok = not failures
    body_lines = [canon(r) for r in records]
    body_bytes = ("\n".join(body_lines) + "\n").encode("utf-8")
    script_path = os.path.abspath(__file__)
    header = {
        "artifact": OUT_TIERS,
        "script_name": "p3b_s2_tiering.py",
        "script_version": git("hash-object", script_path).strip(),
        "script_committed_unmodified": git("status", "--porcelain", "--", script_path).strip() == "",
        "protocol": PROTOCOL, "protocol_sha256_observed": proto_sha,
        "parameters": {"precedence": "X > Z > U", "pair_count_source": IN_DERIVED + " reconciliation_objects.pair_count",
                       "pair_count_crosscheck": IN_PAIRS, "expected": EXPECTED},
        "seed": None,
        "input_hashes": input_hashes,
        "s1_link": {"affected_output_sha256": aff_body_sha,
                    "manifest_output_sha256": manifest.get("header", {}).get("output_sha256")},
        "counts": observed,
        "result": "PASS" if ok else "FAIL",
        "failures": failures,
        "output_sha256": sha256_bytes(body_bytes),
        "python_version": platform.python_version(), "platform": platform.platform(),
        "run_timestamp_utc": datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ"),
    }

    print(f"S2 TIERING: labels {observed['labels_total']} · X {tiers['X']} · Z {tiers['Z']} · U {tiers['U']} · "
          f"pair_count cross-check mismatches {len(pc_mismatch)} · X∩Z {len(x_and_z)} · AFFECTED labels outside universe {len(outside)}")
    for f_ in failures:
        print(f"  FAIL: {f_}")
    if ok and not dry_run:
        with open(os.path.join(CR, OUT_TIERS), "wb") as f:
            f.write((canon({"header": header}) + "\n").encode("utf-8") + body_bytes)
        print(f"wrote {CR_REL}/{OUT_TIERS}")
    print("S2 RESULT:", "PASS" if ok else "FAIL — STOP; human escalation (§22)")
    return 0 if ok else 1


if __name__ == "__main__":
    sys.exit(main())
