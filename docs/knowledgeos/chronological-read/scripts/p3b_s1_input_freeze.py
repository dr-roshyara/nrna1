#!/usr/bin/env python3
"""P3b S1 INPUT FREEZE — Appendix A.2 of the frozen P3b operating protocol v1.6.4 (G-LOG-0001), with H-13 = (i) A ∪ C
only under B-UNREPRODUCIBLE (G-LOG-0004).

Computes the affected set mechanically from its documented source artifacts. Contains no list of pairs or labels:
  A = pairs in 31-RECONCILIATION-PAIRS.jsonl whose unordered {a, b} equals the unordered {label_a, label_b} of any row of
      audit-p3a/P3A-UNDEFINED-RELATIONSHIP-PROVENANCE.jsonl (exact string match).
  C = pairs with relationship ∈ {SAME, REPLACEMENT, DERIVED-FROM}.
  B = not in the operative set (H-13 (i)). The header records B-UNREPRODUCIBLE with the search performed, and the 20
      historical ids, extracted from the KSME-21 report text as provenance only (never tagged, never counted).

Expected values (257 / 158 / 391 / 477) are verification targets taken from the protocol, not data. A mismatch → FAIL.

Gate: refuses to run unless P3B-PREFLIGHT.json (S0) exists with result PASS, and every S1 input that S0 hash-checked
still has the sha256 S0 recorded.

Outputs (§19.5 headers; written only on PASS and not with --dry-run):
  AFFECTED.jsonl            line 1 = {"header": {...}}; lines 2.. = one canonical-JSON record per affected pair,
                            sorted by pair_id: {pair_id, a, b, relationship, basis, criteria: ["A"|"C"...]}.
                            output_sha256 = sha256 of the body bytes (the lines after the header, each ending "\\n").
  P3B-INPUT-MANIFEST.json   {"header": {...}, "body": {inputs with sha256, S0 link}}.
                            output_sha256 = sha256 of canonical JSON of "body".
Canonical JSON = json.dumps(obj, sort_keys=True, separators=(",", ":"), ensure_ascii=False), UTF-8.

Usage:  python3 p3b_s1_input_freeze.py [--dry-run]
Exit:   0 PASS · 1 FAIL (stop; human escalation, §22) · 2 environment/usage error
"""
import datetime
import hashlib
import json
import os
import platform
import re
import subprocess
import sys

H13_DECISION = "i"                     # G-LOG-0004: A ∪ C only, B-UNREPRODUCIBLE
C_RELATIONSHIPS = frozenset({"SAME", "REPLACEMENT", "DERIVED-FROM"})
EXPECTED = {"A_pairs": 257, "C_pairs": 158, "affected_pairs": 391, "affected_labels": 477,
            "b_historical_ids_extracted": 20}
PROTOCOL = "prompts/20260924_1242_p3b-phase1-continuation-protocol-v1.6.4.md"
PROTOCOL_SHA256 = "08f10e74d9bb1820ef986390475c40fa0fff7634dd052d737e81c8eefa1dec06"

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR_REL = "docs/knowledgeos/chronological-read"
CR = os.path.join(REPO_ROOT, CR_REL)
IN_PAIRS = "31-RECONCILIATION-PAIRS.jsonl"
IN_PROV = "audit-p3a/P3A-UNDEFINED-RELATIONSHIP-PROVENANCE.jsonl"
IN_KSME21 = "audit-p3a/KSME-21-EVIDENCE-REPAIR-REPORT.md"
IN_PREFLIGHT = "P3B-PREFLIGHT.json"
OUT_AFFECTED = "AFFECTED.jsonl"
OUT_MANIFEST = "P3B-INPUT-MANIFEST.json"
# The B-procedure search (§5.3, repeated here mechanically): places a persisted B screen could live.
B_SEARCH_TERM = "NEGATIVE-CENSUS"
B_SEARCH_ROOTS = ("audit-p3a", "scripts")


def canon(obj):
    return json.dumps(obj, sort_keys=True, separators=(",", ":"), ensure_ascii=False)


def sha256_file(rel):
    with open(os.path.join(CR, rel), "rb") as f:
        return hashlib.sha256(f.read()).hexdigest()


def load_jsonl(rel):
    with open(os.path.join(CR, rel), encoding="utf-8") as f:
        return [json.loads(l) for l in f if l.strip()]


def git(*args):
    return subprocess.check_output(["git", "-C", REPO_ROOT, *args], text=True)


SELF_REL = "scripts/p3b_s1_input_freeze.py"   # this script mentions the term itself; excluded, and the exclusion recorded
HIT_CLASS_RULE = "by file extension: .py -> CODE; .json/.jsonl -> DATA; any other -> DOCUMENT"


def classify_hit(rel):
    if rel.endswith(".py"):
        return "CODE"
    if rel.endswith((".json", ".jsonl")):
        return "DATA"
    return "DOCUMENT"


def b_procedure_search():
    """Repeat the documented §5.3 search (unchanged rule): which files under audit-p3a/ and scripts/ mention
    NEGATIVE-CENSUS. Returns (classified hits sorted by path, self_reference_excluded). This script's own file is
    excluded because its source contains the term, which would contaminate the evidence inventory. The exclusion is
    reported, never silent."""
    hits, self_excluded = [], False
    for root in B_SEARCH_ROOTS:
        for dirpath, _, files in os.walk(os.path.join(CR, root)):
            if "__pycache__" in dirpath:
                continue
            for fn in sorted(files):
                p = os.path.join(dirpath, fn)
                try:
                    with open(p, encoding="utf-8") as f:
                        text = f.read()
                except (UnicodeDecodeError, OSError):
                    continue
                if B_SEARCH_TERM in text:
                    rel = os.path.relpath(p, CR)
                    if rel == SELF_REL:
                        self_excluded = True
                        continue
                    hits.append({"path": rel, "class": classify_hit(rel)})
    return sorted(hits, key=lambda h: h["path"]), self_excluded


def extract_b_historical_ids():
    """Extract the RP ids listed verbatim in the KSME-21 report's Phase 2 section (provenance only)."""
    with open(os.path.join(CR, IN_KSME21), encoding="utf-8") as f:
        text = f.read()
    start = text.index("## Phase 2")
    end = text.index("## Phase 3", start)
    return sorted(set(re.findall(r"\bRP\d{4}\b", text[start:end])))


def main():
    dry_run = "--dry-run" in sys.argv[1:]
    unknown = [a for a in sys.argv[1:] if a != "--dry-run"]
    if unknown:
        print(f"usage error: unknown arguments {unknown}", file=sys.stderr)
        return 2
    failures = []

    # ---- gate: S0 PASS, inputs unchanged since S0, protocol hash ----
    try:
        with open(os.path.join(CR, IN_PREFLIGHT), encoding="utf-8") as f:
            pre = json.load(f)
    except OSError:
        print("FAIL: P3B-PREFLIGHT.json not found — S0 must PASS before S1", file=sys.stderr)
        return 1
    if pre.get("body", {}).get("result") != "PASS":
        failures.append("S0 result is not PASS")
    s0_hashes = pre.get("body", {}).get("reference_file_sha256", {})
    input_hashes = {}
    for rel in (IN_PAIRS, IN_PROV, IN_KSME21):
        h = sha256_file(rel)
        input_hashes[rel] = h
        s0h = s0_hashes.get(f"{CR_REL}/{rel}")
        if s0h is None:
            failures.append(f"{rel} was not in the S0 reference set")
        elif s0h != h:
            failures.append(f"{rel} changed since S0 (S0 {s0h[:12]}…, now {h[:12]}…)")
    input_hashes[IN_PREFLIGHT] = sha256_file(IN_PREFLIGHT)
    proto_sha = sha256_file(PROTOCOL)
    if proto_sha != PROTOCOL_SHA256:
        failures.append("frozen protocol hash mismatch")

    # ---- A and C, mechanically ----
    pairs = load_jsonl(IN_PAIRS)
    prov = load_jsonl(IN_PROV)
    prov_keys = {frozenset((r["label_a"], r["label_b"])) for r in prov}
    A = {p["pair_id"] for p in pairs if frozenset((p["a"], p["b"])) in prov_keys}
    C = {p["pair_id"] for p in pairs if p["relationship"] in C_RELATIONSHIPS}
    affected_ids = A | C
    records = []
    for p in sorted(pairs, key=lambda x: x["pair_id"]):
        if p["pair_id"] not in affected_ids:
            continue
        crit = [c for c, s in (("A", A), ("C", C)) if p["pair_id"] in s]
        records.append({"pair_id": p["pair_id"], "a": p["a"], "b": p["b"], "relationship": p["relationship"],
                        "basis": p["basis"], "criteria": crit})
    labels = {x for r in records for x in (r["a"], r["b"])}

    # ---- B: B-UNREPRODUCIBLE record (not operative) ----
    b_hist = extract_b_historical_ids()
    search_hits, self_excluded = b_procedure_search()
    hit_class_counts = {c: sum(1 for h in search_hits if h["class"] == c) for c in ("DOCUMENT", "DATA", "CODE")}
    observed = {"A_pairs": len(A), "C_pairs": len(C), "affected_pairs": len(records), "affected_labels": len(labels),
                "b_historical_ids_extracted": len(b_hist)}
    for k, v in EXPECTED.items():
        if observed[k] != v:
            failures.append(f"{k}: expected {v}, observed {observed[k]}")

    ok = not failures
    now = datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")
    script_path = os.path.abspath(__file__)
    common = {
        "script_name": "p3b_s1_input_freeze.py",
        "script_version": git("hash-object", script_path).strip(),
        "script_committed_unmodified": git("status", "--porcelain", "--", script_path).strip() == "",
        "protocol": PROTOCOL, "protocol_sha256_observed": proto_sha,
        "seed": None,
        "python_version": platform.python_version(), "platform": platform.platform(),
        "run_timestamp_utc": now,
    }
    body_lines = [canon(r) for r in records]
    body_bytes = ("\n".join(body_lines) + "\n").encode("utf-8")
    affected_header = dict(common, **{
        "artifact": OUT_AFFECTED,
        "parameters": {"h13_decision": H13_DECISION, "c_relationships": sorted(C_RELATIONSHIPS),
                       "a_definition": "unordered {a,b} of 31-RECONCILIATION-PAIRS equals unordered {label_a,label_b} of any P3A-UNDEFINED-RELATIONSHIP-PROVENANCE row (exact string)",
                       "expected": EXPECTED},
        "input_hashes": input_hashes,
        "counts": observed,
        "b_status": "B-UNREPRODUCIBLE",
        "b_search_performed": {
            "documented_2026_09_24": "§5.3 of the frozen protocol: searching audit-p3a/** and scripts/ for NEGATIVE-CENSUS "
                                     "finds only enum declarations; no persisted procedure regenerates B",
            "repeated_at_s1": {"term": B_SEARCH_TERM, "roots": list(B_SEARCH_ROOTS),
                               "self_reference_excluded": self_excluded, "self_reference_path": SELF_REL,
                               "classification_rule": HIT_CLASS_RULE, "class_counts": hit_class_counts,
                               "files_mentioning_term": search_hits},
        },
        "b_historical_reference_ids": {
            "ids": b_hist, "source": IN_KSME21 + " (Phase 2 section, extracted by regex RP\\d{4})",
            "operative": False,
            "note": "Provenance only (H-13 (i), G-LOG-0004). NOT part of the affected set; 7 further historical B pairs "
                    "are unidentifiable. No inference that omitted pairs were unaffected.",
        },
        "result": "PASS" if ok else "FAIL",
        "failures": failures,
        "output_sha256": hashlib.sha256(body_bytes).hexdigest(),
    })
    manifest_body = {
        "step": "S1 INPUT FREEZE",
        "inputs": {rel: {"sha256": h} for rel, h in input_hashes.items()},
        "s0_link": {"preflight_output_sha256": pre.get("header", {}).get("output_sha256"),
                    "preflight_result": pre.get("body", {}).get("result")},
        "outputs": {OUT_AFFECTED: {"output_sha256": affected_header["output_sha256"], "records": len(records)}},
        "result": "PASS" if ok else "FAIL",
    }
    manifest_header = dict(common, **{"artifact": OUT_MANIFEST, "parameters": {"h13_decision": H13_DECISION},
                                      "input_hashes": input_hashes,
                                      "output_sha256": hashlib.sha256(canon(manifest_body).encode("utf-8")).hexdigest()})

    print(f"S1 INPUT FREEZE: A {len(A)} · C {len(C)} · A∩C {len(A & C)} · affected pairs {len(records)} · "
          f"labels {len(labels)} · B = B-UNREPRODUCIBLE (historical ids extracted {len(b_hist)}, not operative) · "
          f"B-search hits {len(search_hits)} {hit_class_counts} (self-reference excluded: {self_excluded})")
    for h in search_hits:
        print(f"  B-search [{h['class']}] {h['path']}")
    for f_ in failures:
        print(f"  FAIL: {f_}")
    if ok and not dry_run:
        with open(os.path.join(CR, OUT_AFFECTED), "wb") as f:
            f.write((canon({"header": affected_header}) + "\n").encode("utf-8") + body_bytes)
        with open(os.path.join(CR, OUT_MANIFEST), "w", encoding="utf-8") as f:
            json.dump({"header": manifest_header, "body": manifest_body}, f, ensure_ascii=False, indent=1, sort_keys=True)
            f.write("\n")
        print(f"wrote {CR_REL}/{OUT_AFFECTED} and {CR_REL}/{OUT_MANIFEST}")
    print("S1 RESULT:", "PASS" if ok else "FAIL — STOP; human escalation (§22)")
    return 0 if ok else 1


if __name__ == "__main__":
    sys.exit(main())
