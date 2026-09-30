#!/usr/bin/env python3
"""F-Series per-file mechanical audit (§F-8 Step G; C13 §2 v1.2). Re-derives every claim of the F-ID's current state.
The AUDITED / AUDIT-FAILED transition calls `run_audit()` itself (F-07): a hand-written AUDIT.json is never trusted.

  python3 f_audit.py F3082 --run FR-F3082-001     # dry audit: prints the result, writes AUDIT.json + AUDIT-LOG.jsonl

Checks: approval (F-20) · ledger hash chain (F-07) · all freezes in force (F-01) · identity · stage checks for the state
reached (read coverage + integrity record, isolation attestation, content inventory, independent L1 audit when due,
Level-1 records, Level-2 analysis, Level-3 research) · exception states carry a valid human reference (F-08).
Repository changes outside the lane since the baseline are recorded with attribution UNKNOWN (§F-11).
"""
import json
import os
import sys

import f_checks as K
import f_common as C
import f_integrity as I

EXCEPTIONS_NEED_REF = {"READ-PARTIAL", "READ-FAILED", "PLACEHOLDER", "CONTENT-EXTRACTION-UNRESOLVED",
                       "RECONSTRUCTION-UNRESOLVED", "ANALYSIS-UNRESOLVED", "RESEARCH-UNRESOLVED"}


def run_audit(fid, run):
    man = C.manifest()
    row, state = man[fid], C.current_state(fid)
    checks, findings, detail = {}, [], {}

    def check(name, ok, detail_=None):
        checks[name] = "PASS" if ok else "FAIL"
        if not ok:
            findings.append(f"{name}: {detail_}")

    try:
        I.require_approval()
        check("approval", True)
    except RuntimeError as ex:
        check("approval", False, str(ex))
    ok, f = I.verify_chain()
    check("state-ledger-chain", ok, "; ".join(f[:5]))
    ok, f = I.verify_frozen(fid)
    check("frozen-artifacts", ok, "; ".join(f[:5]))
    last = C.events(fid)[-1] if C.events(fid) else {}
    check("run-consistency", last.get("run_id") == run or state in I.F_P0_STATES or state == "EXACT-DUPLICATE",
          f"the last event's run is {last.get('run_id')}, not {run}")
    if row.get("content_sha256"):
        p = os.path.join(C.REPO, row["resolved_path"])
        check("identity", os.path.isfile(p) and C.sha256_file(p) == row["content_sha256"], "bytes differ from manifest")
    if state == "RESEARCHED":
        cov = K.coverage(fid, run)
        check("read-coverage", cov["complete"], json.dumps(cov))
        integ = [r for r in C.read_jsonl(C.INTEGRITY) if r["f_id"] == fid and r["run_id"] == run
                 and r["disposition"] == "READ-COMPLETE" and r["complete"]]
        check("integrity-ledger", bool(integ), "no complete READ-COMPLETE record in F-READ-INTEGRITY.jsonl")
        ok, f = K.check_isolation_attestation(fid, run)
        check("isolation-attestation", ok, "; ".join(f))
        ok, f, stats = K.check_inventory(fid)
        check("content-inventory", ok, "; ".join(f[:10]))
        detail["content-inventory"] = stats
        import f_compare_inventory as CMP
        if CMP.is_due(fid):
            ok, f = CMP.verify(fid, run)
            check("independent-l1-audit", ok, "; ".join(f[:10]))
        ok, f = K.check_reconstruction(fid)
        check("phase1-records", ok, "; ".join(f[:10]))
        ok, f, stats = K.check_analysis(fid)
        check("level2-analysis", ok, "; ".join(f[:10]))
        detail["level2-analysis"] = stats
        rsd = [e for e in C.events(fid) if e["state"] == "RESEARCHED"][-1]
        ok, f = K.check_research(fid, allow_empty_reason=rsd["evidence"].get("empty_reason"))
        check("research-records", ok, "; ".join(f[:10]))
    elif state == "EMPTY":
        check("empty", row["size_bytes"] == 0, "size is not 0")
    elif state in ("BINARY", "NON-TEXT"):
        check("kind", row["kind"] == state, f"manifest kind {row['kind']}")
    elif state == "RESOLUTION-FAILED":
        check("still-unresolvable", not os.path.isfile(os.path.join(C.REPO, row["listed_path"])), "path now exists")
    elif state == "EXACT-DUPLICATE":
        first = row["dup_within_f"]
        check("duplicate", bool(first) and man[first]["content_sha256"] == row["content_sha256"]
              and C.current_state(first) == "AUDITED", "first occurrence missing, different, or not AUDITED")
    elif state in EXCEPTIONS_NEED_REF:
        ref = (last.get("evidence") or {}).get("human_ref")
        check("human-ref", I.human_ref_ok(ref, fid), f"exception state without a valid human reference ({ref}) (F-08)")
        if state in ("READ-PARTIAL", "READ-FAILED"):
            integ = [r for r in C.read_jsonl(C.INTEGRITY) if r["f_id"] == fid and r["run_id"] == run
                     and r["disposition"] == state]
            check("integrity-ledger", bool(integ), f"no {state} record for this run")
        if state == "PLACEHOLDER":
            check("read-coverage", K.coverage(fid, run)["complete"], "placeholder judgement needs complete reading")
    elif state in ("FIREWALL-LIMITED", "SELF-CITATION-EXCLUDED"):
        check("recorded", True)
    else:
        check("auditable-state", False, f"state {state} is not auditable (AUDIT-FAILED is re-entered, never audited)")
    outside = K.writes_outside_lane()
    verdict = "PASS" if all(v == "PASS" for v in checks.values()) else "FAIL"
    return {"f_id": fid, "run_id": run, "audited_state": state, "utc": C.utc(), "verdict": verdict, "checks": checks,
            "checks_detail": detail, "findings": findings, "head": C.head_commit(),
            "outside_changes_since_baseline": {"attribution": "UNKNOWN", "count": len(outside), "lines": outside[:50]}}


def persist(a):
    fid = a["f_id"]
    os.makedirs(os.path.join(C.LEDGER, fid), exist_ok=True)
    with open(C.guard(K.ledger_path(fid, "AUDIT.json")), "w", encoding="utf-8") as f:
        json.dump(a, f, indent=1, ensure_ascii=False)
    C.append_jsonl(K.ledger_path(fid, "AUDIT-LOG.jsonl"), a)


def main(argv):
    if len(argv) != 3 or argv[1] != "--run":
        print(__doc__, file=sys.stderr)
        return 2
    a = run_audit(argv[0], argv[2])
    persist(a)
    print(json.dumps(a, indent=1, ensure_ascii=False))
    return 0 if a["verdict"] == "PASS" else 1


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
