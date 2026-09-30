#!/usr/bin/env python3
"""P3b S5 run state: `P3B-STATE.json` (protocol v1.7 §20, §22 "Session interruption"; v3.5 A3; plan v2.3.2 §B.7).
Infrastructure only: it records states; it dispatches, verifies, audits and accepts nothing.

Per batch (manifest order) the CURRENT RUN has one state:
  PREPARED -> DISPATCHED -> PROPOSED -> VERIFIED -> AUDITED -> ACCEPTED
  DISPATCHED -> INCOMPLETE (interrupted; output kept)       PROPOSED/VERIFIED/AUDITED -> FAILED (output kept, §20)
  FAILED or INCOMPLETE -> a NEW RUN (state PREPARED, same frozen slice): the first re-run is `OB####-R2.2` (§20), a
  further one `OB####-R2.3`, ... (the ordinal continues whatever caused the previous run to end).
ACCEPTED is written only by `p3b_s5_accept.py` (a recorded human H-06 decision), never by `transition`.
VERIFIED and AUDITED need EVIDENCE (G-LOG-0042 item 7): `--evidence` names the S5 verify report (for VERIFIED) or the
§21 audit report (for AUDITED): a JSON document with a §19.5 `header` and a `body` whose `result` is PASS and whose
`batch_id` / `run_id` are the batch's current run. The evidence path and its sha256 are stored in the history entry.
COMPARISON runs (§K object rerun, run id `OB####-R2S`) are kept apart under `comparison_runs`; they follow the same
states up to AUDITED and can never be ACCEPTED.

History is append-only and hash-chained (each entry carries the previous entry's sha256); every save verifies that the
stored history is a prefix of the new one. Resume reads only this file and the manifest (`resume`), never memory.

  init --manifest M [--state S]          create the state from the manifest (refuses if S exists)
  transition BATCH TO [--reason R] [--evidence REPORT]   one validated transition of the current run
  new-run BATCH --cause R                re-run after FAILED / INCOMPLETE
  add-comparison BATCH                   open the §K COMPARISON run OB####-R2S (after the batch is ACCEPTED)
  comparison BATCH TO [--reason R] [--evidence REPORT]   transition a COMPARISON run (never to ACCEPTED)
  rebind-manifest --previous P --reason R [--manifest M]   §26 contract revision: identical composition only
  resume [--manifest M]                  verify the manifest hash, print the next action per batch (counts + list)
  show
  new-attempt BATCH --cause-class C --retirement PATH [--retirement-sha256 H]
                                         EG-6 (G-LOG-0106): the next attempt of a FAILED/INCOMPLETE revision-7 batch
  wsys                                   EG-6 v3 F-02: print the W-SYS programme watchdog status

EG-6 attempts (revision 7 only; G-LOG-0106, v2.8 package §14): every attempt of a batch keeps the run id `<B>-R7`
(option (b): attempt m is retired into `ledger-p3b-r2/<B>-R7.A<m>/` by the orchestrator's `retire`, a separate tool).
runs[] and history entries carry `attempt` (an entry without it is attempt 1). A FAILED / INCOMPLETE transition of an R7
batch records its failure class (X A1 A2 H D S U) and, for a re-runnable FAILED, the failure signature (RR-7).
"""
import argparse
import json
import math
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, _HERE)
import p3b_s5_ops_lib as ops  # noqa: E402

c = ops.load_common()
STATE = "P3B-STATE.json"
MANIFEST = "_batch_manifest_p3b_r2.jsonl"
SCHEMA = "P3B-STATE/S5 v1"
STATES = ("PREPARED", "DISPATCHED", "PROPOSED", "VERIFIED", "AUDITED", "ACCEPTED", "FAILED", "INCOMPLETE")
TRANSITIONS = {
    "PREPARED": {"DISPATCHED"},
    "DISPATCHED": {"PROPOSED", "INCOMPLETE", "FAILED"},
    "PROPOSED": {"VERIFIED", "FAILED"},
    "VERIFIED": {"AUDITED", "FAILED"},
    "AUDITED": {"ACCEPTED", "FAILED"},
    "ACCEPTED": set(),
    "FAILED": set(),
    "INCOMPLETE": set(),
}
COMPARISON_TRANSITIONS = {k: v - {"ACCEPTED"} for k, v in TRANSITIONS.items()}
NEXT_ACTION = {"PREPARED": "dispatch", "DISPATCHED": "await output or mark INCOMPLETE", "PROPOSED": "run verifier",
               "VERIFIED": "run §21 audit", "AUDITED": "await human H-06 decision", "ACCEPTED": "none",
               "FAILED": "new run (re-run from the same frozen slice)", "INCOMPLETE": "new run (re-dispatch)"}
BATCH_RE = re.compile(r"OB\d{4}")
EVIDENCE_REQUIRED = {"VERIFIED": "S5 verify report", "AUDITED": "§21 audit report"}
EVIDENCE_SCRIPT = {"VERIFIED": "scripts/p3b_s5_verify.py", "AUDITED": "scripts/p3b_s5_audit_record.py"}
R7_BATCH_RUN_RE = re.compile(r"OB\d{4}-R7")            # the revision-7 batch-level run (the assembly id, addendum §2.1)

# ---- EG-6 (G-LOG-0106; v2.8 package §14; EG6-SPEC-A-v2 §3/§4, -v3 §2): pre-registered before the canary, never
# adjusted after S5 outputs exist.
M_MAX_ATTEMPTS = 4                                    # RR-5 (G-LOG-0106): at most M = 4 attempts per batch; a bounded-cost
                                                      # cap, not an inferential parameter
FAILURE_CLASSES = ("X", "A1", "A2", "H", "D", "S", "U")
RERUNNABLE_CLASSES = ("X", "A1", "A2")                # H, D, S stop for a human act; U = restore the archive and re-verify
POST_VERIFIED_CLASSES = ("H", "D", "S")               # a failure after VERIFIED is audit/acceptance-informed (H) or a stop
PASS_STATES = ("VERIFIED", "AUDITED", "ACCEPTED")     # RR-1/RR-3: at most one PASS per batch
HEX64_RE = re.compile(r"[0-9a-f]{64}")
CANARY_BATCHES = ("OB0012", "OB0114")                 # governed by the canary stop rule, never counted by W-SYS
WSYS_K = 20                                           # W-SYS look cadence (completed post-canary attempts)
WSYS_ALPHA = "0.01"                                   # α_w: a per-look trigger constant (exact decimal)
WSYS_Q_STAR = "0.12771"                               # q* = (1 − 0.90^(1/396))^(1/4), fixed to 5 decimals (exact decimal)
WSYS_COUNTED = ("X", "A1", "A2")                      # f counts these; D (incl. RR-7-triggered), S, H, U are not counted


def passing_result(to, run):
    """EG-7: the verify report's passing `result` value. The R7 verifier emits only BATCH-PASS | BATCH-FAIL |
    BATCH-UNDETERMINED (addendum §0, §10), so VERIFIED on a revision-7 run requires exactly BATCH-PASS; every other
    revision, and AUDITED (the audit tool's PASS | FAIL), keeps the historical PASS."""
    return "BATCH-PASS" if to == "VERIFIED" and R7_BATCH_RUN_RE.fullmatch(run or "") else "PASS"


class StateError(c.S5Error):
    pass


def run_id(batch, ordinal):
    return f"{batch}-R2" if ordinal == 1 else f"{batch}-R2.{ordinal}"


def manifest_batches(manifest_path):
    lines = ops.read_jsonl(manifest_path)
    body = [r for r in lines if "batch_id" in r]
    return [r["batch_id"] for r in body]


def _entry_hash(e):
    return ops.canon_hash({k: v for k, v in e.items() if k != "entry_sha256"})


def load(path=STATE):
    ap = ops.abspath(path)
    with open(ap, encoding="utf-8") as f:
        st = json.load(f)
    verify_chain(st)
    return st


def verify_chain(st):
    prev = None
    for i, e in enumerate(st["history"]):
        if e["seq"] != i or e["prev_sha256"] != prev or e["entry_sha256"] != _entry_hash(e):
            raise StateError(f"state history chain broken at entry {i}")
        prev = e["entry_sha256"]
    return True


def save(st, path=STATE):
    """Append-only: the history already on disk must be a prefix of the new history."""
    ap = ops.abspath(path)
    verify_chain(st)
    if os.path.exists(ap):
        with open(ap, encoding="utf-8") as f:
            old = json.load(f)
        if st["history"][:len(old["history"])] != old["history"]:
            raise StateError("refusing to save: stored history is not a prefix of the new history (append-only)")
    st["history_head_sha256"] = st["history"][-1]["entry_sha256"] if st["history"] else None
    ops.write_atomic(ap, json.dumps(st, indent=1, sort_keys=True, ensure_ascii=False) + "\n")


def check_evidence(to, evidence, batch, run, comparison=False, attempt=1, used_shas=()):
    """{path, sha256, kind} for a VERIFIED / AUDITED transition; raises unless the report is a PASS for this run.
    EG-6 (attempt-aware; the caller passes them for a revision-7 batch): a report whose sha256 is in `used_shas` (every
    evidence sha already in the batch's history, any attempt) is refused; for attempt m >= 2 the VERIFIED report must be
    named `S5-VERIFY-<B>-R7.A<m>.json` (the verifier never overwrites, EG6-SPEC-A-v2 §4 item 4)."""
    if to not in EVIDENCE_REQUIRED:
        return None
    if not evidence:
        raise StateError(f"{to} requires --evidence (the {EVIDENCE_REQUIRED[to]})")
    ops.check_paths([evidence])
    ap = ops.abspath(evidence)
    if to == "VERIFIED" and attempt >= 2 and os.path.basename(ap) != f"S5-VERIFY-{batch}-R7.A{attempt}.json":
        raise StateError(f"VERIFIED: attempt {attempt} evidence must be named S5-VERIFY-{batch}-R7.A{attempt}.json")
    if used_shas and os.path.isfile(ap) and ops.sha256_path(ap) in used_shas:
        raise StateError(f"{to}: this report was already used as evidence in the batch's history (no reuse across attempts)")
    try:
        with open(ap, encoding="utf-8") as f:
            doc = json.load(f)
    except (OSError, json.JSONDecodeError):
        raise StateError(f"{to}: evidence is not a readable JSON report")
    hdr, body = doc.get("header") if isinstance(doc, dict) else None, doc.get("body") if isinstance(doc, dict) else None
    if not isinstance(hdr, dict) or not isinstance(body, dict):
        raise StateError(f"{to}: evidence lacks a §19.5 header or a body")
    if hdr.get("script_name") != EVIDENCE_SCRIPT[to]:
        raise StateError(f"{to}: evidence was not produced by {EVIDENCE_SCRIPT[to]}")
    if hdr.get("output_sha256") != c.sha256_bytes(c.canon(body).encode("utf-8")):
        raise StateError(f"{to}: evidence header output_sha256 does not match its body")
    want = passing_result(to, run)
    if body.get("result") != want:
        raise StateError(f"{to}: evidence result is not {want}")
    if body.get("batch_id") != batch or body.get("run_id") != run:
        raise StateError(f"{to}: evidence is for another batch or run")
    if to == "VERIFIED" and bool(body.get("comparison", False)) != comparison:
        raise StateError("VERIFIED: evidence comparison flag does not match the run kind")
    return {"path": ops.display(ap, 1), "sha256": ops.sha256_path(ap), "kind": EVIDENCE_REQUIRED[to]}


def _append(st, batch, run, frm, to, reason, actor, kind="PRIMARY", evidence=None, attempt=None, extra=None):
    prev = st["history"][-1]["entry_sha256"] if st["history"] else None
    e = {"seq": len(st["history"]), "utc": ops.utc_now(), "batch_id": batch, "run_id": run, "run_kind": kind,
         "from": frm, "to": to, "reason": reason, "actor": actor, "prev_sha256": prev}
    if evidence:
        e["evidence"] = evidence
    if attempt is not None:                            # EG-6: revision-7 entries only (no field = attempt 1)
        e["attempt"] = attempt
    for k, v in (extra or {}).items():
        if v is not None:
            e[k] = v
    e["entry_sha256"] = _entry_hash(e)
    st["history"].append(e)


def init(manifest_path=MANIFEST, path=STATE):
    ap = ops.abspath(path)
    if os.path.exists(ap):
        raise StateError("state file exists; resume from it instead of re-initialising")
    ops.check_paths([manifest_path])
    bids = manifest_batches(manifest_path)
    if not bids or len(set(bids)) != len(bids) or any(not BATCH_RE.fullmatch(b) for b in bids):
        raise StateError("manifest batch ids missing, duplicated or not OB####")
    st = {"schema": SCHEMA, "seal_id": c.SEAL_ID, "plan_sha256": c.PLAN_SHA256,
          "manifest": os.path.basename(manifest_path), "manifest_sha256": ops.sha256_path(ops.abspath(manifest_path)),
          "batch_order": bids, "batches": {}, "comparison_runs": {}, "history": []}
    for b in bids:
        r = run_id(b, 1)
        st["batches"][b] = {"state": "PREPARED", "run_id": r, "ordinal": 1,
                            "runs": [{"run_id": r, "state": "PREPARED"}]}
        _append(st, b, r, None, "PREPARED", "init from manifest", "p3b_s5_state.py")
    save(st, path)
    return st


def _batch(st, batch):
    if batch not in st["batches"]:
        raise StateError(f"unknown batch {batch}")
    return st["batches"][batch]


def incomplete_signature(cause):
    """The mechanical RR-7 signature of an INCOMPLETE whose caller supplied none: sha256 of "INCOMPLETE:" + the cause."""
    return c.sha256_bytes(("INCOMPLETE:" + str(cause)).encode("utf-8"))


def _failure_fields(b, frm, to, failure_class, failure_signature, reason=""):
    """EG-6 (revision 7): the failure class (and RR-7 signature) recorded on the FAILED / INCOMPLETE transition.
    Mechanical defaults: INCOMPLETE (an interrupted dispatch) is A1; a failure after VERIFIED is H (the §21 audit FAIL or
    an H-06 rejection). A pre-VERIFIED FAILED must name its class; a re-runnable one (X/A1/A2) also its signature.
    INCOMPLETE always carries a signature (RR-7 covers repeated interruptions): the supplied one, else
    `incomplete_signature(cause)` derived mechanically from the recorded cause."""
    r7 = b.get("revision") == 7
    if not r7 or to not in ("FAILED", "INCOMPLETE"):
        if failure_class is not None or failure_signature is not None:
            raise StateError("a failure class / signature is recorded only on FAILED or INCOMPLETE of a revision-7 batch")
        return {}
    post_verified = frm in PASS_STATES
    if failure_class is None:
        failure_class = "A1" if to == "INCOMPLETE" else ("H" if post_verified else None)
    if failure_class not in FAILURE_CLASSES:
        raise StateError(f"{to} of an R7 batch requires --failure-class ∈ {'/'.join(FAILURE_CLASSES)}")
    if post_verified and failure_class not in POST_VERIFIED_CLASSES:
        raise StateError(f"a failure after VERIFIED is class H, D or S, not {failure_class} (RR-1: never re-run)")
    if not post_verified and failure_class == "H":
        raise StateError("class H (audit/acceptance-informed) exists only after VERIFIED")
    if failure_signature is not None and not HEX64_RE.fullmatch(str(failure_signature)):
        raise StateError("--failure-signature must be a lowercase sha256 hex digest")
    if to == "FAILED" and failure_class in RERUNNABLE_CLASSES and failure_signature is None:
        raise StateError("a re-runnable FAILED (X/A1/A2) requires --failure-signature (RR-7)")
    if to == "INCOMPLETE" and failure_signature is None:
        failure_signature = incomplete_signature(reason)
    return {"failure_class": failure_class, "failure_signature": failure_signature}


def transition(st, batch, to, reason="", actor="orchestrator", _allow_accept=False, evidence=None,
               failure_class=None, failure_signature=None):
    b = _batch(st, batch)
    if to not in STATES:
        raise StateError(f"unknown state {to}")
    if to == "ACCEPTED" and not _allow_accept:
        raise StateError("ACCEPTED is recorded only by p3b_s5_accept.py (human H-06 decision)")
    frm = b["state"]
    if to not in TRANSITIONS[frm]:
        raise StateError(f"invalid transition {frm} -> {to} for {batch}")
    if to in ("FAILED", "INCOMPLETE") and not reason:
        raise StateError(f"{to} requires a recorded cause (§20, §22)")
    fx = _failure_fields(b, frm, to, failure_class, failure_signature, reason)
    if b.get("revision") == 7:
        att = current_attempt(st, batch)
        ev = check_evidence(to, evidence, batch, b["run_id"], attempt=att, used_shas=batch_evidence_shas(st, batch))
    else:
        att = None
        ev = check_evidence(to, evidence, batch, b["run_id"])
    b["state"] = to
    b["runs"][-1]["state"] = to
    _append(st, batch, b["run_id"], frm, to, reason, actor, evidence=ev, attempt=att, extra=fx)
    return st


def new_run(st, batch, cause, actor="orchestrator"):
    b = _batch(st, batch)
    if b["state"] not in ("FAILED", "INCOMPLETE"):
        raise StateError(f"a new run needs FAILED or INCOMPLETE, not {b['state']}")
    if not cause:
        raise StateError("a new run requires the recorded cause")
    if b.get("revision") == 7:                      # AG-2: R7 re-run naming is not defined; it needs a human act
        raise StateError("a new run of an R7 batch is not defined (R7 re-run naming needs a human act, G-LOG-0102)")
    b["ordinal"] += 1
    r = run_id(batch, b["ordinal"])
    b["run_id"], b["state"] = r, "PREPARED"
    b["runs"].append({"run_id": r, "state": "PREPARED"})
    _append(st, batch, r, None, "PREPARED", f"new run: {cause}", actor)
    return st


def add_comparison(st, batch, actor="orchestrator"):
    b = _batch(st, batch)
    if b["state"] != "ACCEPTED":
        raise StateError("§K reruns start after the primary batch is accepted")
    if batch in st["comparison_runs"]:
        raise StateError("comparison run already open")
    r = f"{batch}-R2S"
    st["comparison_runs"][batch] = {"run_id": r, "state": "PREPARED"}
    _append(st, batch, r, None, "PREPARED", "§K COMPARISON run opened", actor, kind="COMPARISON")
    return st


def comparison_transition(st, batch, to, reason="", actor="orchestrator", evidence=None):
    if batch not in st["comparison_runs"]:
        raise StateError("no comparison run for this batch")
    cr = st["comparison_runs"][batch]
    if to not in COMPARISON_TRANSITIONS[cr["state"]]:
        raise StateError(f"invalid COMPARISON transition {cr['state']} -> {to} (COMPARISON runs are never ACCEPTED)")
    if to in ("FAILED", "INCOMPLETE") and not reason:
        raise StateError(f"{to} requires a recorded cause")
    ev = check_evidence(to, evidence, batch, cr["run_id"], comparison=True)
    _append(st, batch, cr["run_id"], cr["state"], to, reason, actor, kind="COMPARISON", evidence=ev)
    cr["state"] = to
    return st


def run_history(st, batch, run, attempt=None):
    """The history of one run; with `attempt`, of that attempt only (an entry without the field is attempt 1)."""
    return [e for e in st["history"] if e["batch_id"] == batch and e["run_id"] == run
            and (attempt is None or e.get("attempt", 1) == attempt)]


# ---- EG-6: attempts of a revision-7 batch (G-LOG-0106; EG6-SPEC-A-v2 §1, §3, §4; -v3 §2) -----------------------------

def current_attempt(st, batch):
    """The current attempt of a batch's run (runs[] entries without `attempt` are attempt 1)."""
    return _batch(st, batch)["runs"][-1].get("attempt", 1)


def _r7_entries(st, batch):
    return [e for e in st["history"] if e["batch_id"] == batch and e["run_id"] == f"{batch}-R7"]


def batch_evidence_shas(st, batch):
    """Every evidence sha256 already in the batch's history (any run, any attempt): none may be admitted again."""
    return frozenset(e["evidence"]["sha256"] for e in st["history"]
                     if e["batch_id"] == batch and isinstance(e.get("evidence"), dict) and e["evidence"].get("sha256"))


def _attempt_failure(st, batch, attempt):
    """The attempt's FAILED / INCOMPLETE history entry (the last one), or None."""
    ends = [e for e in _r7_entries(st, batch) if e.get("attempt", 1) == attempt and e["to"] in ("FAILED", "INCOMPLETE")]
    return ends[-1] if ends else None


def rr7_reason(st, batch):
    """RR-7: the current attempt m and attempt m-1 both ended with the same recorded failure signature → class D (a
    systematic failure; STOP, human). Returns the reason, or None."""
    m = current_attempt(st, batch)
    if m < 2:
        return None
    a, b = _attempt_failure(st, batch, m), _attempt_failure(st, batch, m - 1)
    sa, sb = (a or {}).get("failure_signature"), (b or {}).get("failure_signature")
    if sa and sa == sb:
        return f"RR-7: attempts {m - 1} and {m} of {batch} failed with an identical failure signature → class D (STOP, human)"
    return None


def retirable(st, batch):
    """S0 preconditions that the state alone decides (the retire tool calls this before its S1 intent record): the batch
    is at revision 7, its current attempt is FAILED or INCOMPLETE with a recorded class, and no attempt ever reached
    VERIFIED, AUDITED or ACCEPTED (RR-1/RR-3: at most one PASS). Returns {batch, attempt, state, failure_class,
    failure_signature}; the class itself is not judged here (new_attempt refuses non-re-runnable classes)."""
    b = _batch(st, batch)
    if b.get("revision") != 7:
        raise StateError(f"{batch}: attempts exist only at revision 7")
    if b["state"] not in ("FAILED", "INCOMPLETE"):
        raise StateError(f"{batch}: a new attempt needs the current attempt FAILED or INCOMPLETE, not {b['state']}")
    if any(e["to"] in PASS_STATES for e in _r7_entries(st, batch)):
        raise StateError(f"{batch}: an attempt already reached VERIFIED/AUDITED/ACCEPTED (at most one PASS; RR-1, RR-3)")
    m = current_attempt(st, batch)
    end = _attempt_failure(st, batch, m)
    if end is None or end.get("failure_class") not in FAILURE_CLASSES:
        raise StateError(f"{batch}: attempt {m} has no recorded failure class")
    return {"batch": batch, "attempt": m, "state": b["state"], "failure_class": end["failure_class"],
            "failure_signature": end.get("failure_signature")}


def check_retirement(batch, attempt, ref):
    """The retire tool's COMPLETE record for `attempt`: {path, sha256} exactly; the file is
    `.../<B>-R7.A<m>/RETIREMENT-COMPLETE.json`, its sha256 matches, it states COMPLETE for this batch and attempt, and
    its `intent_sha256` is the sibling RETIREMENT-INTENT.json's sha256. Returns the reference as stored."""
    if not isinstance(ref, dict) or set(ref) != {"path", "sha256"}:
        raise StateError("the retirement record reference must be exactly {path, sha256}")
    if not isinstance(ref["sha256"], str) or not HEX64_RE.fullmatch(ref["sha256"]):
        raise StateError("the retirement record sha256 is not a sha256 hex digest")
    ap = ops.abspath(str(ref["path"]))
    ops.check_paths([ap])
    if not os.path.isfile(ap):
        raise StateError("the retirement record does not exist")
    if os.path.basename(ap) != "RETIREMENT-COMPLETE.json" or \
            os.path.basename(os.path.dirname(ap)) != f"{batch}-R7.A{attempt}":
        raise StateError(f"the retirement record is not {batch}-R7.A{attempt}/RETIREMENT-COMPLETE.json")
    if ops.sha256_path(ap) != ref["sha256"]:
        raise StateError("the retirement record sha256 does not match the file")
    try:
        with open(ap, encoding="utf-8") as f:
            rec = json.load(f)
    except (OSError, json.JSONDecodeError):
        raise StateError("the retirement record is not readable JSON")
    if not isinstance(rec, dict) or rec.get("state") != "COMPLETE" or rec.get("batch") != batch \
            or rec.get("attempt") != attempt:
        raise StateError(f"the retirement record is not a COMPLETE retirement of {batch} attempt {attempt}")
    intent = os.path.join(os.path.dirname(ap), "RETIREMENT-INTENT.json")
    if not os.path.isfile(intent) or rec.get("intent_sha256") != ops.sha256_path(intent):
        raise StateError("the retirement record does not bind its RETIREMENT-INTENT.json")
    return {"path": ops.display(ap, 1), "sha256": ref["sha256"]}


def new_attempt(st, batch, cause_class, retirement_record, actor="orchestrator", may_start=None):
    """EG-6 option (b): open attempt m+1 of a revision-7 batch after attempt m was retired. Same run id `<B>-R7`.
    Refuses unless: `retirable` holds (R7; FAILED/INCOMPLETE; no attempt ever VERIFIED/AUDITED/ACCEPTED);
    cause_class ∈ {X, A1, A2} and equals the class recorded on attempt m's failure (H, D, S never re-run automatically;
    U = restore the archive and re-verify, no attempt); RR-7 does not fire; W-SYS has not stopped; m+1 <= M = 4;
    `retirement_record` = {path, sha256} of attempt m's COMPLETE retirement record (`check_retirement`); and the guard
    `may_start(batch) -> (ok, reason)` (the orchestrator's `may_start_attempt`, EG6-SPEC-A-v2 §1) holds. It is
    required: without it the call fails closed. Validates everything first (a refusal changes nothing)."""
    pre = retirable(st, batch)
    m = pre["attempt"]
    if cause_class == "U":
        raise StateError("class U is not re-run: restore the archive and re-verify (no new attempt)")
    if cause_class not in RERUNNABLE_CLASSES:
        raise StateError(f"cause class {cause_class!r} is not re-runnable (only X, A1, A2; H, D, S stop for a human act)")
    if cause_class != pre["failure_class"]:
        raise StateError(f"cause class {cause_class} differs from the class {pre['failure_class']} recorded on attempt {m}")
    rr7 = rr7_reason(st, batch)
    if rr7:
        raise StateError(rr7)
    if wsys_stopped(st) or wsys_status(st)["stop"]:
        raise StateError("W-SYS has stopped S5 (class D, systemic): no new attempt until a human act (WSYS-RESUME)")
    if m + 1 > M_MAX_ATTEMPTS:
        raise StateError(f"{batch}: attempt {m + 1} exceeds M = {M_MAX_ATTEMPTS} (RR-5, G-LOG-0106)")
    ref = check_retirement(batch, m, retirement_record)
    if may_start is None:
        raise StateError("the may_start_attempt guard is required (EG6-SPEC-A-v2 §1)")
    ok, why = may_start(batch)
    if not ok:
        raise StateError(f"may_start_attempt refused: {why}")
    b = _batch(st, batch)
    frm = b["state"]
    b["runs"].append({"run_id": b["run_id"], "state": "PREPARED", "revision": 7, "attempt": m + 1})
    b["state"] = "PREPARED"
    _append(st, batch, b["run_id"], frm, "PREPARED", f"new attempt {m + 1}: attempt {m} retired (class {cause_class})",
            actor, kind="NEW-ATTEMPT", attempt=m + 1, extra={"cause_class": cause_class, "retirement": ref})
    return st


def _binom_tail_scaled(n, f):
    """Σ_{k>=f} C(n,k) a^k b^(n-k) with q* = a/D exactly (integers): P(Bin(n, q*) >= f) = result / D^n."""
    from fractions import Fraction
    q = Fraction(WSYS_Q_STAR)
    a, dnm = q.numerator, q.denominator
    return sum(math.comb(n, k) * a ** k * (dnm - a) ** (n - k) for k in range(max(f, 0), n + 1)), dnm ** n


def wsys_triggers(n, f):
    """Exact: P(Bin(n, q*) >= f) <= α_w (integer arithmetic; no floating point)."""
    from fractions import Fraction
    alpha = Fraction(WSYS_ALPHA)
    tail, total = _binom_tail_scaled(n, f)
    return tail * alpha.denominator <= alpha.numerator * total


def wsys_threshold(n):
    """The smallest f with wsys_triggers(n, f) (None if none), by exact integer arithmetic."""
    from fractions import Fraction
    q, alpha = Fraction(WSYS_Q_STAR), Fraction(WSYS_ALPHA)
    a, dnm = q.numerator, q.denominator
    total, cum = dnm ** n, 0
    for k in range(n, -1, -1):                        # the tail grows as f decreases: find the first f that no longer stops
        cum += math.comb(n, k) * a ** k * (dnm - a) ** (n - k)
        if cum * alpha.denominator > alpha.numerator * total:
            return k + 1 if k < n else None
    return 0


def wsys_status(st):
    """EG-6 v3 F-02 W-SYS (execution-only stopping rule; no inferential role). Pure: reads only per-attempt class records
    of the state history, never a sample, audit or content artifact. n = completed post-canary attempts (every attempt
    of every non-canary revision-7 batch that reached VERIFIED or ended FAILED / INCOMPLETE, counted once, in history
    order of its first such entry), except attempts whose effective class is D (incl. RR-7-triggered: an identical
    signature to the previous attempt), S, H or U (and a failure without a recorded class, which fails closed as D);
    f = those that failed with X, A1 or A2. After every K = 20 counted attempts: STOP iff P(Bin(n, q*) >= f) <= α_w.
    A stop at any look is sticky (resumption needs a human act: `wsys_resume`).
    Monotone and audit-independent: once an attempt reached VERIFIED it counts as a pass for good; a later audit-stage
    FAILED (H, or D/S after VERIFIED) never removes or re-classes it.
    After a WSYS-RESUME (the most recent one governs): the count stays CUMULATIVE over all completed post-canary attempts
    (count_from "all", the default: §2.8 / G-LOG-0106 pre-register no reset), unless that resume act recorded
    count_from "resume", in which case only entries after it are read. Either way the state is stopped again iff a look
    completed after the resume point triggers (a look before it was spent by the stop that the resume lifted)."""
    seen, final = [], {}
    rseq = _last_seq(st, "WSYS-RESUME")
    count_from = st["history"][rseq].get("count_from", "all") if rseq is not None else "all"
    for e in st["history"]:
        if count_from == "resume" and e["seq"] <= rseq:
            continue
        bid = e["batch_id"]
        if bid in CANARY_BATCHES or e["run_id"] != f"{bid}-R7" or e["run_kind"] not in ("PRIMARY",):
            continue
        key = (bid, e.get("attempt", 1))
        if e["to"] in ("VERIFIED", "FAILED", "INCOMPLETE"):
            if key not in final:
                seen.append((key, e["seq"]))
            if e["to"] == "VERIFIED" and key not in final:
                final[key] = "PASS"
            elif e["to"] in ("FAILED", "INCOMPLETE") and final.get(key) != "PASS":   # a pass is never uncounted
                cls = e.get("failure_class") or "D"
                prev = _attempt_failure(st, bid, key[1] - 1) if key[1] >= 2 else None
                if e.get("failure_signature") and prev and prev.get("failure_signature") == e["failure_signature"]:
                    cls = "D"                          # RR-7-triggered: class D, not counted
                final[key] = cls
    n = f = 0
    looks, stop_at = [], None
    for key, seq in seen:                              # completion order; outcomes are the attempt's final records
        cls = final[key]
        if cls != "PASS" and cls not in WSYS_COUNTED:
            continue
        n += 1
        f += cls != "PASS"
        if n % WSYS_K == 0:
            hit = wsys_triggers(n, f)
            live = rseq is None or seq > rseq            # a look before the last resume point was spent by that stop
            looks.append({"n": n, "f": f, "threshold": wsys_threshold(n), "stop": hit, "after_resume": rseq is not None
                          and seq > rseq})
            if hit and live and stop_at is None:
                stop_at = n
    look_n = (n // WSYS_K) * WSYS_K
    return {"n": n, "f": f, "stop": stop_at is not None, "stop_at_n": stop_at, "count_from": count_from,
            "threshold": wsys_threshold(look_n) if look_n else None, "looks": looks,
            "constants": {"K": WSYS_K, "alpha_w": WSYS_ALPHA, "q_star": WSYS_Q_STAR, "counted": list(WSYS_COUNTED),
                          "canary_excluded": list(CANARY_BATCHES)}}


def _last_seq(st, kind):
    seqs = [e["seq"] for e in st["history"] if e["run_kind"] == kind]
    return seqs[-1] if seqs else None


def wsys_stopped(st):
    """True iff a WSYS-STOP entry exists with no later WSYS-RESUME (the durable class-D stop record)."""
    s, r = _last_seq(st, "WSYS-STOP"), _last_seq(st, "WSYS-RESUME")
    return s is not None and (r is None or s > r)


def wsys_stop(st, actor="orchestrator", reason="W-SYS look"):
    """T146: record a W-SYS stop durably. When `wsys_status` says stop and no stop is recorded yet (since the last
    resume), append one history entry of kind WSYS-STOP, class D, with the snapshot {n, f, threshold, look} (look = the
    look n at which it triggered, threshold = that look's stop threshold). Idempotent; returns True iff it appended."""
    s = wsys_status(st)
    if not s["stop"] or wsys_stopped(st):
        return False
    look = s["stop_at_n"]
    snap = {"n": s["n"], "f": s["f"], "threshold": wsys_threshold(look), "look": look}
    _append(st, "*", None, None, "WSYS-STOP", f"W-SYS stop (class D, systemic): {reason}", actor, kind="WSYS-STOP",
            extra={"failure_class": "D", "wsys": snap})
    return True


GLOG_RE = re.compile(r"G-LOG-\d{4}")


WSYS_COUNT_FROM = ("all", "resume")


def wsys_resume(st, glog, actor, reason, count_from="all"):
    """The human act that lifts a recorded W-SYS stop (a diagnosis; any instrument repair is a human gate per RR-6).
    Requires an active WSYS-STOP, a G-LOG reference `G-LOG-nnnn` and a reason. Appends a WSYS-RESUME entry that records
    `count_from`. After it new attempts are admitted again; W-SYS stops again iff a later look triggers.
    count_from "all" (the default): the count stays cumulative over every completed post-canary attempt; §2.8 /
    G-LOG-0106 say only "resumption requires a human act" and pre-register no reset, so a cumulative test that is still
    above threshold re-stops at the next look (the conservative direction). count_from "resume" restarts the count at
    the resume point: that is a HUMAN decision, recorded in the named G-LOG act (which must record it as a deviation
    from the pre-registration), never a tool default. Earlier attempts stay recorded; accepted objects stand."""
    if not wsys_stopped(st):
        raise StateError("no recorded W-SYS stop to resume from")
    if not isinstance(glog, str) or not GLOG_RE.fullmatch(glog):
        raise StateError("a W-SYS resume requires the governing G-LOG reference (G-LOG-nnnn)")
    if not reason:
        raise StateError("a W-SYS resume requires the recorded reason (the diagnosis)")
    if count_from not in WSYS_COUNT_FROM:
        raise StateError("count_from must be 'all' (default, cumulative) or 'resume' (a recorded human deviation)")
    _append(st, "*", None, "WSYS-STOP", "WSYS-RESUME", f"W-SYS resume ({glog}; count from {count_from}): {reason}", actor,
            kind="WSYS-RESUME", extra={"glog": glog, "count_from": count_from})
    return st


def load_guard():
    """The orchestrator's `may_start_attempt(batch) -> (ok, reason)` (the retire slice, a separate tool), or None."""
    try:
        orch = ops.load_script("p3b_s5_r7_orchestrate")
    except Exception:                                   # noqa: BLE001  (absent / not loadable: the CLI fails closed)
        return None
    return getattr(orch, "may_start_attempt", None)


COMPOSITION_KEYS = ("batch_id", "run_id", "tier", "hub_batch", "labels", "checklist", "in_checklist", "weights", "predicted")


def manifest_composition(manifest_path):
    return [{k: r.get(k) for k in COMPOSITION_KEYS} for r in ops.read_jsonl(manifest_path) if "batch_id" in r]


def rebind_manifest(st, new_manifest, previous_manifest, reason, actor="orchestrator"):
    """§26 contract revision (e.g. G-LOG-0048): rebind the state to a regenerated manifest. Refuses unless the previous
    manifest is exactly the one the state is bound to AND the batch composition (ids, order, run ids, tier, hub flag,
    labels, checklist, weights, predicted load) is identical. Recorded in the append-only history; batches already run
    keep their contract (the history and the previous manifest hash are retained)."""
    if not reason:
        raise StateError("a manifest rebind requires the recorded reason (the governance entry)")
    if ops.sha256_path(ops.abspath(previous_manifest)) != st["manifest_sha256"]:
        raise StateError("the previous manifest is not the one the state is bound to")
    old, new = manifest_composition(previous_manifest), manifest_composition(new_manifest)
    if old != new or [r["batch_id"] for r in new] != st["batch_order"]:
        raise StateError("manifest rebind refused: the batch composition differs")
    new_sha = ops.sha256_path(ops.abspath(new_manifest))
    st.setdefault("manifest_revisions", []).append({"from_sha256": st["manifest_sha256"], "to_sha256": new_sha,
                                                    "reason": reason, "utc": ops.utc_now()})
    _append(st, "*", None, st["manifest_sha256"], new_sha, f"manifest rebind: {reason}", actor, kind="MANIFEST-REVISION")
    st["manifest_sha256"] = new_sha
    return st


def revision_transition(st, new_manifest, previous_manifest, reason, actor="orchestrator"):
    """AG-2 (G-LOG-0102): the governed R2→R7 execution-identity transition — NOT a general run-id remap. Refuses unless:
    a governance reason is recorded; the previous manifest is exactly the bound one; every batch is PREPARED or FAILED
    (none dispatched, running, verified, audited or accepted) and no batch is already at revision 7; no COMPARISON run is
    open; and, batch by batch in the bound order, the previous manifest's run_id is exactly B-R2, the new one exactly
    B-R7, and every other composition field is identical. Validates everything first (a refusal changes nothing); then
    appends, per batch, a new R7 run (PREPARED, revision 7) — every R2 run and the hash-chained history stay intact —
    and rebinds the manifest hash."""
    if not reason:
        raise StateError("a revision transition requires the recorded reason (the governance entry)")
    if ops.sha256_path(ops.abspath(previous_manifest)) != st["manifest_sha256"]:
        raise StateError("the previous manifest is not the one the state is bound to")
    bad = sorted(b for b, v in st["batches"].items() if v["state"] not in ("PREPARED", "FAILED") or v.get("revision") == 7)
    if bad:
        raise StateError(f"revision transition refused: {len(bad)} batch(es) not PREPARED/FAILED or already R7")
    if st.get("comparison_runs"):
        raise StateError("revision transition refused: COMPARISON runs are open")
    old, new = manifest_composition(previous_manifest), manifest_composition(new_manifest)
    if [r["batch_id"] for r in old] != st["batch_order"] or [r["batch_id"] for r in new] != st["batch_order"]:
        raise StateError("revision transition refused: the batch ids or their order differ")
    for o, n in zip(old, new):
        b = o["batch_id"]
        if o.get("run_id") != f"{b}-R2" or n.get("run_id") != f"{b}-R7":
            raise StateError(f"revision transition refused: {b} run_id {o.get('run_id')!r} → {n.get('run_id')!r} is not B-R2 → B-R7")
        if {k: v for k, v in o.items() if k != "run_id"} != {k: v for k, v in n.items() if k != "run_id"}:
            raise StateError(f"revision transition refused: {b} composition differs beyond run_id")
    new_sha = ops.sha256_path(ops.abspath(new_manifest))
    for b in st["batch_order"]:
        B = st["batches"][b]
        r = f"{b}-R7"
        B["runs"].append({"run_id": r, "state": "PREPARED", "revision": 7})
        frm_run, frm_state = B["run_id"], B["state"]
        B["run_id"], B["state"], B["revision"] = r, "PREPARED", 7
        _append(st, b, r, frm_state, "PREPARED", f"revision transition R2→R7 (from {frm_run} {frm_state}): {reason}", actor,
                kind="REVISION-TRANSITION")
    st.setdefault("manifest_revisions", []).append({"from_sha256": st["manifest_sha256"], "to_sha256": new_sha, "reason": reason,
                                                    "kind": "REVISION-TRANSITION R2→R7", "utc": ops.utc_now()})
    _append(st, "*", None, st["manifest_sha256"], new_sha, f"manifest rebind (revision transition R2→R7): {reason}", actor,
            kind="MANIFEST-REVISION")
    st["manifest_sha256"] = new_sha
    return st


def resume(st, manifest_path=MANIFEST):
    """Resume from the state file and the manifest only."""
    if ops.sha256_path(ops.abspath(manifest_path)) != st["manifest_sha256"]:
        raise StateError("manifest changed since the state was initialised (dispatched batches stay frozen, §24.1)")
    if manifest_batches(manifest_path) != st["batch_order"]:
        raise StateError("manifest batch order differs from the state")
    plan = []
    for b in st["batch_order"]:
        e = st["batches"][b]
        plan.append({"batch_id": b, "run_id": e["run_id"], "state": e["state"], "next": NEXT_ACTION[e["state"]]})
    counts = {}
    for p in plan:
        counts[p["state"]] = counts.get(p["state"], 0) + 1
    return {"counts": counts, "batches": plan}


def main(argv=None):
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    ap.add_argument("--state", default=STATE)
    sub = ap.add_subparsers(dest="cmd", required=True)
    i = sub.add_parser("init")
    i.add_argument("--manifest", default=MANIFEST)
    t = sub.add_parser("transition")
    t.add_argument("batch")
    t.add_argument("to")
    t.add_argument("--reason", default="")
    t.add_argument("--evidence")
    t.add_argument("--failure-class", choices=FAILURE_CLASSES)       # EG-6: FAILED / INCOMPLETE of an R7 batch
    t.add_argument("--failure-signature")                             # EG-6 RR-7: sha256 of the failure-string set
    na = sub.add_parser("new-attempt")                                # EG-6 (G-LOG-0106): R7 only
    na.add_argument("batch")
    na.add_argument("--cause-class", required=True)
    na.add_argument("--retirement", required=True, help="attempt m's RETIREMENT-COMPLETE.json (written by retire)")
    na.add_argument("--retirement-sha256", help="the sha256 retire printed (default: computed from the file)")
    ws = sub.add_parser("wsys")
    ws.add_argument("--record", action="store_true", help="append the class-D WSYS-STOP entry if W-SYS says stop")
    ws.add_argument("--reason", default="W-SYS look")
    wr = sub.add_parser("wsys-resume")                                # the human act after a W-SYS stop
    wr.add_argument("--glog", required=True)
    wr.add_argument("--reason", required=True)
    wr.add_argument("--actor", required=True, help="the human deciding (e.g. human:<name>)")
    wr.add_argument("--count-from", choices=WSYS_COUNT_FROM, default="all",
                    help="all = cumulative (default, pre-registered); resume = restart (a recorded human deviation)")
    n = sub.add_parser("new-run")
    n.add_argument("batch")
    n.add_argument("--cause", required=True)
    ac = sub.add_parser("add-comparison")
    ac.add_argument("batch")
    ct = sub.add_parser("comparison")
    ct.add_argument("batch")
    ct.add_argument("to")
    ct.add_argument("--reason", default="")
    ct.add_argument("--evidence")
    r = sub.add_parser("resume")
    r.add_argument("--manifest", default=MANIFEST)
    sub.add_parser("show")
    rb = sub.add_parser("rebind-manifest")
    rb.add_argument("--manifest", default=MANIFEST)
    rb.add_argument("--previous", required=True, help="the manifest the state is bound to (e.g. extracted from git)")
    rb.add_argument("--reason", required=True)
    rt = sub.add_parser("revision-transition")          # AG-2 (G-LOG-0102): R2→R7 execution identity only
    rt.add_argument("--manifest", default=MANIFEST)
    rt.add_argument("--previous", required=True)
    rt.add_argument("--reason", required=True)
    a = ap.parse_args(argv)
    try:
        c.assert_sealed()
        if a.cmd == "init":
            st = init(a.manifest, a.state)
            print(json.dumps({"batches": len(st["batch_order"]), "state": "PREPARED"}))
        elif a.cmd == "resume":
            print(json.dumps(resume(load(a.state), a.manifest), indent=1))
        elif a.cmd == "show":
            print(json.dumps(load(a.state), indent=1, sort_keys=True))
        elif a.cmd == "wsys":
            st = load(a.state)
            if a.record and wsys_stop(st, reason=a.reason):
                save(st, a.state)
            print(json.dumps(dict(wsys_status(st), recorded_stop=wsys_stopped(st)), indent=1, sort_keys=True))
        else:
            st = load(a.state)
            if a.cmd == "transition":
                transition(st, a.batch, a.to, a.reason, evidence=a.evidence, failure_class=a.failure_class,
                           failure_signature=a.failure_signature)
            elif a.cmd == "new-attempt":
                rp = ops.abspath(a.retirement)
                ref = {"path": rp, "sha256": a.retirement_sha256 or (ops.sha256_path(rp) if os.path.isfile(rp) else "")}
                guard = load_guard()
                if guard is None:
                    raise StateError("new-attempt needs the orchestrator's may_start_attempt guard, which is unavailable")
                new_attempt(st, a.batch, a.cause_class, ref, may_start=guard)
            elif a.cmd == "wsys-resume":
                wsys_resume(st, a.glog, a.actor, a.reason, count_from=a.count_from)
            elif a.cmd == "new-run":
                new_run(st, a.batch, a.cause)
            elif a.cmd == "add-comparison":
                add_comparison(st, a.batch)
            elif a.cmd == "rebind-manifest":
                rebind_manifest(st, a.manifest, a.previous, a.reason)
            elif a.cmd == "revision-transition":
                revision_transition(st, a.manifest, a.previous, a.reason)
            else:
                comparison_transition(st, a.batch, a.to, a.reason, evidence=a.evidence)
            save(st, a.state)
            print(json.dumps(st["history"][-1], sort_keys=True))
        c.assert_sealed()
        return 0
    except c.S5Error as ex:
        print(f"REFUSED: {ex}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
