#!/usr/bin/env python3
"""F-Series state transition gate (§F-6, v1.2). A transition is an evidence-producing event, never a declaration:
this script re-derives the evidence and appends a hash-chained event only if it holds.

  python3 f_transition.py F3082 READING           --run FR-F3082-001
  python3 f_transition.py F3082 READ-COMPLETE     --run FR-F3082-001
  python3 f_transition.py F3082 CONTENT-EXTRACTED --run FR-F3082-001 [--human-ref F-LOG-####]   # re-extraction needs a ref
  python3 f_transition.py F3082 RECONSTRUCTED     --run FR-F3082-001
  python3 f_transition.py F3082 ANALYZED          --run FR-F3082-001
  python3 f_transition.py F3082 RESEARCHED        --run FR-F3082-001 [--empty-reason "…"]
  python3 f_transition.py F3082 AUDITED|AUDIT-FAILED --run FR-F3082-001   # runs the audit itself (F-07)
  python3 f_transition.py F3082 <exception> --run … --reason "…" --human-ref F-LOG-####       # F-08

Every stage event freezes its artifacts ({file: sha256}); every later gate re-verifies all freezes still in force
(F-01). The run id must be the run of the previous event (F-17). Exit 0 = appended; 1 = refused; 2 = usage.
"""
import json
import os
import sys

import f_checks as K
import f_common as C
import f_integrity as I

NEEDS_REASON = {"READ-PARTIAL", "READ-FAILED", "PLACEHOLDER", "EXACT-DUPLICATE", "CONTENT-EXTRACTION-UNRESOLVED",
                "RECONSTRUCTION-UNRESOLVED", "ANALYSIS-UNRESOLVED", "RESEARCH-UNRESOLVED"}
NEEDS_HUMAN_REF = {"READ-PARTIAL", "READ-FAILED", "PLACEHOLDER", "CONTENT-EXTRACTION-UNRESOLVED",
                   "RECONSTRUCTION-UNRESOLVED", "ANALYSIS-UNRESOLVED", "RESEARCH-UNRESOLVED"}   # F-08
SAME_RUN_STAGES = {"READ-COMPLETE", "READ-PARTIAL", "READ-FAILED", "CONTENT-EXTRACTED", "RECONSTRUCTED", "ANALYZED",
                   "RESEARCHED", "AUDITED", "AUDIT-FAILED", "PLACEHOLDER", "CONTENT-EXTRACTION-UNRESOLVED",
                   "RECONSTRUCTION-UNRESOLVED", "ANALYSIS-UNRESOLVED", "RESEARCH-UNRESOLVED"}


def arg(name, argv):
    return argv[argv.index(name) + 1] if name in argv and argv.index(name) + 1 < len(argv) else None


def main(argv):
    if len(argv) < 2:
        print(__doc__, file=sys.stderr)
        return 2
    fid, target, run = argv[0], argv[1], arg("--run", argv)
    reason, href = arg("--reason", argv), arg("--human-ref", argv)
    man = C.manifest()
    if fid not in man or not run or not run.startswith(f"FR-{fid}-"):
        print("usage: F-ID must be in the manifest and --run FR-<F-ID>-NNN is required", file=sys.stderr)
        return 2
    if target in NEEDS_REASON and not reason:
        print(f"{target} requires --reason", file=sys.stderr)
        return 2
    findings = []
    try:
        I.require_approval()                                           # F-20
    except RuntimeError as ex:
        print(f"REFUSED: {ex}", file=sys.stderr)
        return 1
    ok, f = I.verify_chain()                                           # F-07
    if not ok:
        print("REFUSED: state ledger chain broken:\n  " + "\n  ".join(f[:10]), file=sys.stderr)
        return 1
    evs = C.events(fid)
    prev = evs[-1] if evs else None
    cur = prev["state"] if prev else None
    if target not in C.ALLOWED_FROM or cur not in C.ALLOWED_FROM[target]:   # legality first, before any evidence work
        print(f"ILLEGAL-TRANSITION: {fid} {cur} -> {target}", file=sys.stderr)
        return 1
    if target in SAME_RUN_STAGES and (not prev or prev.get("run_id") != run):
        findings.append(f"run {run} is not the run of the previous event ({prev.get('run_id') if prev else None}) (F-17)")
    if target in NEEDS_HUMAN_REF and not I.human_ref_ok(href, fid):
        findings.append(f"{target} needs --human-ref F-LOG-#### naming {fid} in F-GOVERNANCE-LOG.md (F-08)")
    if target in ("READING", "EXACT-DUPLICATE"):                        # §F-2 processing order + F-16 checkpoints
        for other, r in man.items():
            if r["list_order"] >= man[fid]["list_order"]:
                break
            if C.current_state(other) != "AUDITED":
                findings.append(f"{other} (earlier in processing order) is {C.current_state(other)}, not AUDITED (§F-2)")
                break
        import f_checkpoint
        due = f_checkpoint.due_unopened()
        if due:
            findings.append(f"checkpoint {due} is due and not opened — run f_checkpoint.py open (F-16)")
    if cur and target not in ("READING", "AUDITED", "AUDIT-FAILED"):   # the audit itself re-verifies all freezes
        ok, f = I.verify_frozen(fid, before=target if target in I.STAGE_ORDER else None)   # F-01
        findings += f
    ev = {"run_id": run}
    if href:
        ev["human_ref"] = href
    if target == "READING":
        info = [r for r in C.read_jsonl(K.ledger_path(fid, "READ-LOG.jsonl")) if r.get("run_id") == run and r.get("info")]
        if not info:
            findings.append("no reader --info line for this run (the reader establishes n_pages)")
        else:
            ev["n_pages"] = info[-1]["n_pages"]
    elif target in ("READ-COMPLETE", "READ-PARTIAL", "READ-FAILED"):
        cov = K.coverage(fid, run)
        if target == "READ-COMPLETE" and not cov["complete"]:
            findings.append(f"coverage incomplete: {json.dumps(cov)}")
        if not findings:
            cov.update(utc=C.utc(), disposition=target, reason=reason)
            C.append_jsonl(C.INTEGRITY, cov)
            ev.update(coverage_pct=cov["coverage_pct"], expected_pages=cov["expected_pages"],
                      missing_pages=cov["missing_pages"], content_sha256=cov["content_sha256"])
    elif target == "CONTENT-EXTRACTED":
        if cur == "CONTENT-EXTRACTED" and not I.human_ref_ok(href, fid):
            findings.append("re-extraction (CONTENT-EXTRACTED → CONTENT-EXTRACTED) needs --human-ref (F-06)")
        ok, f = K.check_isolation_attestation(fid, run)                # C15, F-02
        findings += f
        ok, f, stats = K.check_inventory(fid)
        findings += f
        ev["inventory"] = stats
    elif target == "RECONSTRUCTED":
        ok, f, _ = K.check_inventory(fid)
        findings += f
        import f_compare_inventory as CMP
        if CMP.is_due(fid):                                            # F-06: L1 accepted before L2
            ok, f = CMP.verify(fid, run)
            findings += f
        ok, f = K.check_reconstruction(fid)
        findings += f
    elif target == "ANALYZED":
        ok, f = K.check_reconstruction(fid)
        findings += f
        ok, f, stats = K.check_analysis(fid)
        findings += f
        ev["analysis"] = stats
    elif target == "RESEARCHED":
        ok, f = K.check_research(fid, allow_empty_reason=arg("--empty-reason", argv))
        findings += f
        ev["empty_reason"] = arg("--empty-reason", argv)
    elif target in ("AUDITED", "AUDIT-FAILED"):
        import f_audit
        a = f_audit.run_audit(fid, run)                                # F-07: never trusts a hand-written AUDIT.json
        f_audit.persist(a)
        if target == "AUDITED" and a["verdict"] != "PASS":
            findings.append("audit FAIL: " + "; ".join(a["findings"][:10]))
        if target == "AUDIT-FAILED" and a["verdict"] != "FAIL":
            findings.append("AUDIT-FAILED requires an audit verdict FAIL")
        ev.update(audit_sha256=C.sha256_file(K.ledger_path(fid, "AUDIT.json")), verdict=a["verdict"],
                  audit_findings=a["findings"][:20])
    elif target == "EXACT-DUPLICATE":
        first = man[fid]["dup_within_f"]
        if not first or C.current_state(first) != "AUDITED":
            findings.append("EXACT-DUPLICATE needs dup_within_f pointing to an AUDITED earlier F-ID")
        else:                                                          # RL-03: hash identity re-verified from bytes
            try:
                C.content_text(man[fid]), C.content_text(man[first])
            except RuntimeError as ex:
                findings.append(str(ex))
            if man[fid]["content_sha256"] != man[first]["content_sha256"]:
                findings.append("manifest sha256 differs from the first copy")
        ev.update(duplicate_of=first, content_sha256=man[fid]["content_sha256"], path=man[fid]["resolved_path"])
    if target in I.STAGE_ARTIFACTS and not findings:
        ev["frozen"] = I.artifact_hashes(fid, target)                  # F-01
        missing = [k for k, v in ev["frozen"].items() if v is None]
        if missing:
            findings.append(f"artifacts to freeze are missing: {missing}")
    if findings:
        print("EVIDENCE INSUFFICIENT — nothing recorded:\n  " + "\n  ".join(findings), file=sys.stderr)
        return 1
    try:
        e = C.record_event(fid, target, ev, run_id=run, note=reason)
    except RuntimeError as ex:
        print(str(ex), file=sys.stderr)
        return 1
    print(f"{fid}: {e['from']} -> {e['state']} (seq {e['seq']})")
    return 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
