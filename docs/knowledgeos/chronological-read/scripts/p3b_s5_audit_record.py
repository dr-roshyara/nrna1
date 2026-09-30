#!/usr/bin/env python3
"""P3b S5 §21 audit record (ops spec §1.4, §6 step 8). Infrastructure; G-LOG-0041.

Turns an independent auditor's findings file into the audit report that is the evidence for the AUDITED transition.
The auditor (an independent agent, §21 item 1) writes a findings JSON; this script validates it against the closed
§21 vocabulary and writes `audit-p3b/S5-AUDIT-<run>.json` ({header: §19.5, body}) once.

Findings file (JSON):
  {"auditor_model_id": str, "audit_group": str, "sample_sha256": <64 hex, the p3b_s5_audit_sample output sha>,
   "records_rederived": int, "register_reviewed": int,
   "dispositions": [{"record": str, "field": str,
                     "disposition": "ORIGINAL-UPHELD"|"AUDIT-UPHELD"|"JUDGMENT-CALL"|"PROTOCOL-VIOLATION", "note": str}],
   "cross_batch": {"checked": bool, "note": str}?}

result = PASS iff no disposition is PROTOCOL-VIOLATION (§21 item 2: a PROTOCOL-VIOLATION fails the batch).

  p3b_s5_audit_record.py --batch OB#### --run RUN --findings FILE [--out FILE]

Revision 7 (EG-9, G-LOG-0106 part (g)): RUN is exactly `OB####-R7` (the assembly id; unit, synthesis, `.A<m>` and
`-R7S` ids are refused) and the report is bound, before anything is written, to
  (i)   the §21 sample `--sample` (the p3b_s5_audit_sample.py output): its header output_sha256 equals the sha256 of its
        canonical body and the findings' sample_sha256, its batch_id is B, and its input hashes are exactly the CURRENT
        bytes of the assembly's objects.jsonl and register.jsonl (a post-sample change is refused);
  (ii)  the verified assembly `ledger-p3b-r2/<B>-R7/{objects,register,p1-gap-capture}.jsonl` (sha256 of each recorded);
  (iii) P3B-STATE: B at revision 7, current run B-R7, state VERIFIED, and the last history entry of the current attempt
        (the state tool's `current_attempt`, else the batch's `attempt` field, else 1) is an evidenced VERIFIED entry
        (BATCH-PASS was checked at that transition).
Dispositions are RECORDED, never applied (R-I, H-EG9-6): each AUDIT-UPHELD disposition becomes one entry of the
rev3 §12.3 mechanism (EG5-V2.8 package §17, G-LOG-0106): `P3B-CORRECTIONS.jsonl` (CR root, append-only; existing lines
are never rewritten), id `cor_id = P3B-COR-####` (the next free 4-digit id), with exactly the §12.3 fields
{target_record, target_artifact, reason, evidence, new_record_ref, author_role, date}, plus `status:
RECORDED-NOT-APPLIED` and batch_id / run_id / attempt. new_record_ref is null: under R-I a correction is applied only by
a separate human act authorizing a fresh witnessed S5 run, never by a bypass write.
Concurrency and crash order: id allocation and append run under an exclusive advisory lock (fcntl.flock on the
sidecar `P3B-CORRECTIONS.jsonl.lock`, created if absent; Linux/POSIX only) covering read → next free id → append →
fsync → verify (each new cor_id occurs exactly once). The steps are ordered (a) body + entries with ids reserved under
the lock, (b) entries appended, (c) the write-once report LAST, so no committed artifact references a missing record.
A re-run after a crash between (b) and (c) is idempotent: if the log already holds this run's entries and they are
identical to the would-be entries (same ids, same content, their own date), the report is written and nothing is
appended; if they differ, the run is refused. Nothing is written into the
assembly, whose bytes stay the estimation object. Sample coverage (every sampled record has a disposition) is REPORTED
in `coverage`, not enforced (H-EG9-4 undecided). The tool takes no Freeze 2 input (firewall).

  p3b_s5_audit_record.py --batch OB#### --run OB####-R7 --findings FILE --sample SAMPLE
                         [--state P3B-STATE.json] [--assembly ledger-p3b-r2/OB####-R7] [--out FILE]
                         [--corrections P3B-CORRECTIONS.jsonl]
"""
import contextlib
import fcntl
import importlib.util
import json
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("p3b_s5_common", os.path.join(_HERE, "p3b_s5_common.py"))
c = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(c)
sys.path.insert(0, _HERE)
import p3b_s5_ops_lib as ops  # noqa: E402

stm = ops.load_script("p3b_s5_state")

DISPOSITIONS = ("ORIGINAL-UPHELD", "AUDIT-UPHELD", "JUDGMENT-CALL", "PROTOCOL-VIOLATION")
KEYS = {"auditor_model_id", "audit_group", "sample_sha256", "records_rederived", "register_reviewed", "dispositions",
        "cross_batch"}
# revision 7 (EG-9)
LEDGER = "ledger-p3b-r2"
ASSEMBLY_FILES = ("objects.jsonl", "register.jsonl", "p1-gap-capture.jsonl")
SAMPLED_FILES = ("objects.jsonl", "register.jsonl")               # what p3b_s5_audit_sample.py reads
SAMPLE_SCRIPT = "scripts/p3b_s5_audit_sample.py"
CORRECTIONS = "P3B-CORRECTIONS.jsonl"                           # rev3 §12.3 correction log (CR root; G-LOG-0106)
COR_ID = re.compile(r"P3B-COR-(\d{4})")
LOCK_SUFFIX = ".lock"                          # the advisory-lock sidecar `P3B-CORRECTIONS.jsonl.lock` (never read)
READING = ("R-I (G-LOG-0106): the estimation object is the witness-verified assembly; corrections are stored and "
           "reported only; one is applied only by a separate human act authorizing a fresh witnessed S5 run")
_ERRORS = (c.S5Error, ops.load_common().S5Error)


def validate(f):
    if not isinstance(f, dict) or set(f) - KEYS or not {"auditor_model_id", "sample_sha256", "dispositions"} <= set(f):
        raise c.S5Error("findings: unknown or missing keys")
    if not re.fullmatch(r"[0-9a-f]{64}", str(f["sample_sha256"])):
        raise c.S5Error("findings: sample_sha256 must be 64 hex")
    for d in f["dispositions"]:
        if not isinstance(d, dict) or set(d) != {"record", "field", "disposition", "note"} or d["disposition"] not in DISPOSITIONS:
            raise c.S5Error("findings: a disposition is malformed or outside the §21 vocabulary")
    return True


# ---------------------------------------------------------------- revision 7 (EG-9)
def current_attempt(st, bid):
    """The batch's current attempt: the state tool's `current_attempt` (EG-6 part (d)) when present, else the batch's
    `attempt` field if present, else 1."""
    f = getattr(stm, "current_attempt", None)
    return f(st, bid) if f else stm._batch(st, bid).get("attempt", 1)


def attempt_history(st, bid, run, attempt):
    """History entries of the current run and attempt (an entry without an `attempt` field is attempt 1)."""
    return [e for e in stm.run_history(st, bid, run) if e.get("attempt", 1) == attempt]


def bind_verified_state(state_path, bid, run):
    st = stm.load(state_path)
    b = stm._batch(st, bid)
    if b.get("revision") != 7 or b.get("run_id") != run:
        raise c.S5Error(f"{bid}: the current run is not {run} at revision 7")
    if b.get("state") != "VERIFIED":
        raise c.S5Error(f"{bid} {run} is {b.get('state')}: the §21 audit needs VERIFIED")
    att = current_attempt(st, bid)
    ents = attempt_history(st, bid, run, att)
    last = ents[-1] if ents else {}
    ev = last.get("evidence") or {}
    if last.get("to") != "VERIFIED" or not ev.get("sha256") or ev.get("kind") != stm.EVIDENCE_REQUIRED["VERIFIED"]:
        raise c.S5Error(f"{bid} {run}: no evidenced VERIFIED (BATCH-PASS) entry at the current attempt {att}")
    return st, {"attempt": att, "state_entry_sha256": last["entry_sha256"], "verify_report_sha256": ev["sha256"],
                "verify_report_path": ev.get("path")}


def bind_assembly(asm, run):
    adir = ops.abspath(asm)
    if ops.inside_cr(adir) and c.rel(adir).replace(os.sep, "/") != f"{LEDGER}/{run}":
        raise c.S5Error(f"inside the repository the assembly is exactly {LEDGER}/{run}/")
    paths = {n: os.path.join(adir, n) for n in ASSEMBLY_FILES}
    if not all(os.path.isfile(p) for p in paths.values()):
        raise c.S5Error("the verified assembly lacks one of " + ", ".join(ASSEMBLY_FILES))
    ops.check_paths(list(paths.values()))
    return adir, paths, {n: ops.sha256_path(p) for n, p in paths.items()}


def bind_sample(sp, findings, bid, paths, files):
    ops.check_paths([sp])
    try:
        with open(ops.abspath(sp), encoding="utf-8") as f:
            doc = json.load(f)
    except (OSError, json.JSONDecodeError):
        raise c.S5Error("--sample is not a readable JSON sample")
    hdr, body = (doc.get("header"), doc.get("body")) if isinstance(doc, dict) else (None, None)
    if not isinstance(hdr, dict) or not isinstance(body, dict):
        raise c.S5Error("--sample lacks a §19.5 header or a body")
    if hdr.get("script_name") != SAMPLE_SCRIPT:
        raise c.S5Error(f"--sample was not produced by {SAMPLE_SCRIPT}")
    if hdr.get("output_sha256") != ops.canon_hash(body):
        raise c.S5Error("--sample header output_sha256 does not match its body")
    if findings["sample_sha256"] != hdr["output_sha256"]:
        raise c.S5Error("findings sample_sha256 is not the --sample output sha256")
    if body.get("batch_id") != bid:
        raise c.S5Error("--sample is for another batch")
    want = {ops.header_inputs([paths[n]])[0]: files[n] for n in SAMPLED_FILES}
    if hdr.get("input_hashes") != want:
        raise c.S5Error("--sample input hashes are not the current bytes of the verified assembly")
    return hdr, body


def sampled_records(sbody):
    s = sbody.get("seeded_records") or {}
    return set(sbody.get("mandatory_contested_or_homonym_split") or []) | set(sbody.get("mandatory_register") or []) | \
        set(sbody.get("other_register_sample") or []) | set(s.get("objects") or []) | set(s.get("register") or [])


def coverage(sbody, findings, st, bid):
    """Reported, not enforced (H-EG9-4 undecided): every sampled record should carry at least one disposition."""
    ids = sampled_records(sbody)
    uncovered = sorted(ids - {d["record"] for d in findings["dispositions"]})
    order = st.get("batch_order") or []
    return {"enforced": False, "sampled_records": len(ids), "dispositioned": len(ids) - len(uncovered),
            "uncovered": uncovered, "complete": not uncovered, "f3_labels": len(sbody.get("f3_stage2b") or {}),
            "manifest_index_matches_state": bid in order and sbody.get("manifest_index") == order.index(bid)
            and sbody.get("n_batches") == len(order)}


_BETWEEN_READ_AND_APPEND = None        # test seam only (widens the race window in the concurrency test); None in use


@contextlib.contextmanager
def _cor_lock(cor_ap):
    """Exclusive advisory lock on the sidecar `<log>.lock` (fcntl.flock; Linux/POSIX only). Every writer of the log
    goes through this tool, so the lock serializes read → next id → append → fsync → verify across processes."""
    os.makedirs(os.path.dirname(cor_ap), exist_ok=True)
    fd = os.open(cor_ap + LOCK_SUFFIX, os.O_CREAT | os.O_RDWR, 0o644)
    try:
        fcntl.flock(fd, fcntl.LOCK_EX)
        yield
    finally:
        fcntl.flock(fd, fcntl.LOCK_UN)
        os.close(fd)


def _read_log(cor_ap):
    """The log's entries (none if it does not exist); refuses an entry without a well-formed P3B-COR-#### id."""
    if not os.path.exists(cor_ap):
        return []
    rows = ops.read_jsonl(cor_ap)
    if any(not COR_ID.fullmatch(str(r.get("cor_id"))) for r in rows):
        raise c.S5Error("the correction log holds an entry without a P3B-COR-#### id")
    return rows


def append_corrections(cor_ap, run, build):
    """Under the lock: if the log already holds entries of `run`, they must be exactly `build(<their first id>,
    <their date>)` (crash recovery: nothing is appended; returns (entries, True)), else refuse. Otherwise allocate the
    next free ids, append `build(first, today)` (mode 'a', flush, fsync), and verify each new cor_id occurs exactly
    once in the log. `build(first, date)` returns the entries with ids P3B-COR-<first>, <first+1>, ..."""
    with _cor_lock(cor_ap):
        rows = _read_log(cor_ap)
        mine = [r for r in rows if r.get("run_id") == run]
        if mine:
            first = int(COR_ID.fullmatch(mine[0]["cor_id"]).group(1))
            would = build(first, mine[0].get("date"))
            if [ops.canon(r) for r in would] != [ops.canon(r) for r in mine]:
                raise c.S5Error("correction records for this run already exist and differ from this run's (append-only)")
            return would, True
        first = max((int(COR_ID.fullmatch(r["cor_id"]).group(1)) for r in rows), default=0) + 1
        would = build(first, ops.utc_now()[:10])
        present = {r["cor_id"] for r in rows}
        if [r.get("cor_id") for r in would] != [f"P3B-COR-{first + k:04d}" for k in range(len(would))] or \
                any(r.get("cor_id") in present for r in would):
            raise c.S5Error("new correction ids are not the next free contiguous P3B-COR-#### ids")
        if not would:
            return [], False
        if _BETWEEN_READ_AND_APPEND:
            _BETWEEN_READ_AND_APPEND()
        with open(cor_ap, "a", encoding="utf-8") as f:
            for r in would:
                f.write(ops.canon(r) + "\n")
            f.flush()
            os.fsync(f.fileno())
        after = [r["cor_id"] for r in _read_log(cor_ap)]
        if any(after.count(r["cor_id"]) != 1 for r in would):
            raise c.S5Error("after the append a new cor_id does not occur exactly once in the correction log")
        return would, False


def correction_records(findings, bid, run, attempt, first, date, asm_display, files, report_display, report_sha):
    """rev3 §12.3 entries (exactly its fields + R-I status + run identity), one per AUDIT-UPHELD disposition, ids
    P3B-COR-<first>... in disposition order; stored and reported, never applied (R-I: new_record_ref is null)."""
    ups = [d for d in findings["dispositions"] if d["disposition"] == "AUDIT-UPHELD"]
    if ups and first + len(ups) - 1 > 9999:
        raise c.S5Error("the P3B-COR-#### id space is exhausted")
    return [{"cor_id": f"P3B-COR-{first + k:04d}", "target_record": d["record"], "target_artifact": asm_display,
             "reason": d["note"],
             "evidence": {"field": d["field"], "disposition": "AUDIT-UPHELD", "audit_report": report_display,
                          "audit_report_output_sha256": report_sha, "assembly_sha256": files,
                          "auditor_model_id": findings["auditor_model_id"]},
             "new_record_ref": None, "author_role": "§21 auditor", "date": date,
             "status": "RECORDED-NOT-APPLIED", "batch_id": bid, "run_id": run, "attempt": attempt}
            for k, d in enumerate(ups)]


def _opt(a, k, default=None):
    return a[a.index(k) + 1] if k in a else default


def main_r7(a, bid, run, fp):
    try:
        sp = _opt(a, "--sample")
        state_path = _opt(a, "--state", stm.STATE)
        asm = _opt(a, "--assembly", f"{LEDGER}/{run}")
        out = _opt(a, "--out", os.path.join(c.CR, "audit-p3b", f"S5-AUDIT-{run}.json"))
        cor = _opt(a, "--corrections", CORRECTIONS)
    except IndexError:
        print(__doc__, file=sys.stderr)
        return 2
    if not sp:
        print("REFUSED: a revision-7 audit requires --sample (the p3b_s5_audit_sample.py output)", file=sys.stderr)
        return 2
    try:
        c.assert_sealed()
        with open(fp, encoding="utf-8") as f:
            findings = json.load(f)
        validate(findings)
        st, verified = bind_verified_state(state_path, bid, run)
        adir, paths, files = bind_assembly(asm, run)
        shdr, sbody = bind_sample(sp, findings, bid, paths, files)
        out_ap, cor_ap = ops.abspath(out), ops.abspath(cor)
        if os.path.exists(out_ap):
            raise c.S5Error("the audit report exists (append-only)")
        ops.check_paths([cor_ap])
        pv = sum(1 for d in findings["dispositions"] if d["disposition"] == "PROTOCOL-VIOLATION")
        n_up = sum(1 for d in findings["dispositions"] if d["disposition"] == "AUDIT-UPHELD")
        asm_display = ops.display(adir, 1)
        base = {"batch_id": bid, "run_id": run, "revision": 7, "comparison": False, "findings": findings,
                "protocol_violations": pv, "result": "PASS" if pv == 0 else "FAIL",
                "binding": {"sample": {"path": ops.display(sp, 2), "output_sha256": shdr["output_sha256"],
                                       "file_sha256": ops.sha256_path(ops.abspath(sp)),
                                       "manifest_index": sbody.get("manifest_index"), "n_batches": sbody.get("n_batches"),
                                       "audit_group": sbody.get("audit_group")},
                            "assembly": {"path": asm_display, "files": files},
                            "verified": verified},
                "coverage": coverage(sbody, findings, st, bid)}
        made = {}

        def build(first, date):
            """The report body for ids P3B-COR-<first>... and the entries that reference that body's sha."""
            body = dict(base, corrections={"reading": READING, "applied": False, "count": n_up,
                                           "ids": [f"P3B-COR-{first + k:04d}" for k in range(n_up)],
                                           "file": ops.display(cor_ap, 3)})
            made["body"], made["txt"] = body, c.canon(body)
            return correction_records(findings, bid, run, verified["attempt"], first, date, asm_display, files,
                                      ops.display(out_ap, 4), c.sha256_bytes(made["txt"].encode("utf-8")))
        # Ordering (crash safety): (a) body + entries with ids reserved under the lock, (b) entries appended + fsynced,
        # (c) the write-once report LAST, so no committed artifact references a record that does not exist.
        if n_up:
            cors, _recovered = append_corrections(cor_ap, run, build)
        else:
            if any(r.get("run_id") == run for r in _read_log(cor_ap)):
                raise c.S5Error("correction records for this run already exist and differ from this run's (append-only)")
            cors = build(1, None)
        body, txt = made["body"], made["txt"]
        inputs = ops.header_inputs(list(paths.values()) + [sp])
        hdr = c.header(__file__, inputs, {"batch_id": bid, "run_id": run, "revision": 7}, txt,
                       {"findings_sha256": c.sha256_bytes(open(fp, "rb").read()),
                        "sample_file_sha256": body["binding"]["sample"]["file_sha256"],
                        "state_entry_sha256": verified["state_entry_sha256"]})
        os.makedirs(os.path.dirname(out_ap), exist_ok=True)
        ops.write_new(out_ap, c.canon({"header": hdr, "body": body}) + "\n")
        c.assert_sealed()
    except _ERRORS as ex:
        print(f"REFUSED: {ex}", file=sys.stderr)
        return 2
    print(f"S5 AUDIT {run}: {len(findings['dispositions'])} dispositions, {pv} protocol violations, "
          f"{len(cors)} correction record(s) (not applied) → {body['result']}")
    return 0 if pv == 0 else 1


def main(argv=None):
    a = argv if argv is not None else sys.argv[1:]
    try:
        bid, run, fp = (a[a.index(k) + 1] for k in ("--batch", "--run", "--findings"))
    except (ValueError, IndexError):
        print(__doc__, file=sys.stderr)
        return 2
    rev7 = bool(re.fullmatch(r"OB\d{4}", bid)) and re.fullmatch(rf"{bid}-R7", run) is not None
    if not re.fullmatch(r"OB\d{4}", bid) or not (re.fullmatch(rf"{bid}-R2(\.\d+|S)?", run) or rev7):
        print("REFUSED: malformed batch or run id", file=sys.stderr)
        return 2
    if rev7:
        return main_r7(a, bid, run, fp)
    out = a[a.index("--out") + 1] if "--out" in a else os.path.join(c.CR, "audit-p3b", f"S5-AUDIT-{run}.json")
    c.assert_sealed()
    with open(fp, encoding="utf-8") as f:
        findings = json.load(f)
    validate(findings)
    pv = sum(1 for d in findings["dispositions"] if d["disposition"] == "PROTOCOL-VIOLATION")
    body = {"batch_id": bid, "run_id": run, "comparison": run.endswith("R2S"), "findings": findings,
            "protocol_violations": pv, "result": "PASS" if pv == 0 else "FAIL"}
    txt = c.canon(body)
    if os.path.exists(out):
        print("REFUSED: the audit report exists (append-only)", file=sys.stderr)
        return 2
    os.makedirs(os.path.dirname(out), exist_ok=True)
    hdr = c.header(__file__, [], {"batch_id": bid, "run_id": run}, txt, {"findings_sha256": c.sha256_bytes(open(fp, "rb").read())})
    with open(out, "w", encoding="utf-8") as f:
        f.write(c.canon({"header": hdr, "body": body}) + "\n")
    print(f"S5 AUDIT {run}: {len(findings['dispositions'])} dispositions, {pv} protocol violations → {body['result']}")
    c.assert_sealed()
    return 0 if pv == 0 else 1


if __name__ == "__main__":
    sys.exit(main())
