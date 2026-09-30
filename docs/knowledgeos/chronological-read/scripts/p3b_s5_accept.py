#!/usr/bin/env python3
"""P3b S5 per-batch H-06 acceptance recorder (protocol v1.7 §20, §25; plan v2.3.2 §B.8 "Acceptance"; OMQ-17).

Records a HUMAN decision; it decides nothing and never auto-accepts. It refuses unless, in `P3B-STATE.json`, the
batch's current run has passed VERIFIED and AUDITED and is now AUDITED. Every argument below is required; there is no
default outcome.

  p3b_s5_accept.py --batch OB#### --outcome ACCEPTED|NOT-ACCEPTED --decision "<human decision text>"
                   --decided-by "<human name>" --reference "<G-LOG / decision reference>"
                   [--state P3B-STATE.json] [--out audit-p3b/S5-ACCEPTANCE.jsonl]

ACCEPTED -> the batch's state becomes ACCEPTED. NOT-ACCEPTED -> FAILED with the decision as cause (§20: no partial
acceptance). The record is appended to `audit-p3b/S5-ACCEPTANCE.jsonl` (append-only). A run is decided at most once.
Records stay `record_status: PROPOSED` (D-24, G-11); the acceptance is the join key "records of H-06-accepted batches".

Revision 7 (EG-9, G-LOG-0106 part (g)): `--tranche audit-p3b/H06-TRANCHE-<nnnn>.json` is REQUIRED (optional below
revision 7, whose behaviour is otherwise unchanged). The tranche and the log pass the plan §M item 6 allowlist gate
(`ops.check_paths`; the tranche pattern `audit-p3b/H06-TRANCHE-\\d{4}.json`, G-LOG-0106). `check_tranche` refuses,
before any state change, unless:
  (a)  the tranche file is tracked in git and unmodified against HEAD;
  (b′) the heading `## <reference> —` occurs exactly once in P3B-GOVERNANCE-LOG.md;
  (b)  that section (up to the next `## `) has exactly one line `H06-TRANCHE <path> sha256 <h>`, with <path> the
       tranche's path relative to the log's directory and <h> the sha256 of the tranche file;
  (c)  the tranche's reference / decided_by equal --reference / --decided-by and its decider passes the human-only check;
  (d)  the batch is listed exactly once, with its current run id and current attempt;
  (e)  the listed verify_report_sha256 / audit_report_sha256 equal the evidence sha256 of the current attempt's VERIFIED
       / AUDITED history entries;
  (f)  the listed outcome equals --outcome.
The governance log must be tracked and unmodified. The record then also carries tranche_path, tranche_sha256,
glog_section_sha256 (sha256 of the section text), glog_commit (HEAD) and tranche_commit (last commit touching it).
Residual (recorded, EG-9): a point-in-time check; the log has no code-level append-only enforcement.

  p3b_s5_accept.py ... --tranche audit-p3b/H06-TRANCHE-<nnnn>.json [--glog P3B-GOVERNANCE-LOG.md]
"""
import argparse
import hashlib
import json
import os
import re
import subprocess
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, _HERE)
import p3b_s5_ops_lib as ops  # noqa: E402
import p3b_s5_state as stm  # noqa: E402

c = ops.load_common()
OUT = "audit-p3b/S5-ACCEPTANCE.jsonl"
OUTCOMES = ("ACCEPTED", "NOT-ACCEPTED")
# Engineering never accepts its own work (EP-02): an AI / automation identity is refused as decider.
NON_HUMAN = re.compile(r"\b(claude|ai|agent|orchestrator|assistant|bot|script|automation|auto)\b", re.I)
GLOG = "P3B-GOVERNANCE-LOG.md"
TRANCHE_SCHEMA = "H06-TRANCHE v1"
REFERENCE_RE = re.compile(r"G-LOG-\d{4}")
ANCHOR_HINT = re.compile(r"H06-TRANCHE\s+\S+\s+sha256\s")
ANCHOR = re.compile(r"H06-TRANCHE (\S+) sha256 ([0-9a-f]{64})")
HEX64 = re.compile(r"[0-9a-f]{64}")


def previous_decisions(out):
    ap = ops.abspath(out)
    if not os.path.exists(ap):
        return []
    return ops.read_jsonl(ap)


# ---------------------------------------------------------------- revision 7: tranche binding (EG-9)
def _git(path, *args):
    """git in the repository holding `path` (never the caller's cwd)."""
    return subprocess.run(["git", "-C", os.path.dirname(path), *args], capture_output=True, text=True)


def _tracked_clean(path, what):
    if _git(path, "ls-files", "--error-unmatch", "--", path).returncode != 0:
        raise c.S5Error(f"{what} is not tracked in git")
    if _git(path, "diff", "--quiet", "HEAD", "--", path).returncode != 0:
        raise c.S5Error(f"{what} differs from HEAD (commit it before the acceptance)")


def glog_section(text, reference):
    """(b′) + extraction: the one section headed `## <reference> —`, up to the next `## ` line (exclusive)."""
    lines = text.splitlines(keepends=True)
    head = f"## {reference} —"
    starts = [i for i, ln in enumerate(lines) if ln.startswith(head)]
    if len(starts) != 1:
        raise c.S5Error(f"the heading '## {reference} —' occurs {len(starts)} times in the governance log (need exactly 1)")
    i = starts[0]
    j = next((k for k in range(i + 1, len(lines)) if lines[k].startswith("## ")), len(lines))
    return "".join(lines[i:j])


def current_attempt(st, batch):
    """The batch's current attempt: the state tool's `current_attempt` (EG-6 part (d)) when present, else the batch's
    `attempt` field if present, else 1. (T-16, attempt re-binding, is tested with EG-6 part (d).)"""
    f = getattr(stm, "current_attempt", None)
    return f(st, batch) if f else stm._batch(st, batch).get("attempt", 1)


def check_tranche(tranche_path, batch, b, st, outcome, decided_by, reference, gl_path=GLOG):
    """Refuses unless checks (a), (b′), (b), (c)-(f) hold (see the module doc). Returns the five capture fields."""
    tp, gp = os.path.realpath(ops.abspath(tranche_path)), os.path.realpath(ops.abspath(gl_path))
    if not REFERENCE_RE.fullmatch(str(reference)):
        raise c.S5Error("a revision-7 --reference must be a governance entry id G-LOG-####")
    ops.check_paths([tp])                                    # plan §M item 6, as every S5 input (R-S4 M2)
    ops.check_paths([gp])
    if not os.path.isfile(tp):
        raise c.S5Error("the tranche file does not exist")
    if not os.path.isfile(gp):
        raise c.S5Error("the governance log does not exist")
    _tracked_clean(tp, "the tranche file")                                            # (a)
    _tracked_clean(gp, "the governance log")                                          # capture precondition (T-17)
    with open(tp, "rb") as f:
        raw = f.read()
    t_sha = hashlib.sha256(raw).hexdigest()
    rel = os.path.relpath(tp, os.path.dirname(gp)).replace(os.sep, "/")
    with open(gp, encoding="utf-8") as f:
        section = glog_section(f.read(), reference)                                   # (b′)
    anchors = [ln for ln in section.splitlines() if ANCHOR_HINT.search(ln)]
    if len(anchors) != 1:                                                             # (b)
        raise c.S5Error(f"the {reference} section has {len(anchors)} H06-TRANCHE lines (need exactly 1)")
    m = ANCHOR.fullmatch(anchors[0].strip())
    if not m or m.group(1) != rel or m.group(2) != t_sha:
        raise c.S5Error(f"the {reference} H06-TRANCHE line does not name this tranche file and its sha256")
    try:
        t = json.loads(raw.decode("utf-8"))
    except (UnicodeDecodeError, json.JSONDecodeError):
        raise c.S5Error("the tranche file is not JSON")
    if not isinstance(t, dict) or t.get("schema") != TRANCHE_SCHEMA or not isinstance(t.get("batches"), list):
        raise c.S5Error(f"the tranche file is not a {TRANCHE_SCHEMA} document")
    if not isinstance(t.get("decided_by"), str) or NON_HUMAN.search(t["decided_by"]):     # (c)
        raise c.S5Error("the tranche's decided_by must name the human decider (H-06 is a human decision)")
    if t.get("reference") != reference or t["decided_by"] != decided_by:
        raise c.S5Error("the tranche's reference / decided_by differ from --reference / --decided-by")
    listed = [e for e in t["batches"] if isinstance(e, dict) and e.get("batch_id") == batch]
    if len(listed) != 1:                                                              # (d)
        raise c.S5Error(f"{batch} is listed {len(listed)} times in the tranche (need exactly 1)")
    e = listed[0]
    att = current_attempt(st, batch)
    if e.get("run_id") != b["run_id"] or e.get("attempt") != att:
        raise c.S5Error(f"the tranche lists {batch} run {e.get('run_id')} attempt {e.get('attempt')}, "
                        f"not the current {b['run_id']} attempt {att}")
    ents = [x for x in stm.run_history(st, batch, b["run_id"]) if x.get("attempt", 1) == att]
    live = {}
    for x in ents:
        if x["to"] in ("VERIFIED", "AUDITED") and (x.get("evidence") or {}).get("sha256"):
            live[x["to"]] = x["evidence"]["sha256"]
    for k, to in (("verify_report_sha256", "VERIFIED"), ("audit_report_sha256", "AUDITED")):   # (e)
        if not HEX64.fullmatch(str(e.get(k))) or e.get(k) != live.get(to):
            raise c.S5Error(f"the tranche's {k} for {batch} is not the live {to} evidence sha256")
    if e.get("outcome") != outcome:                                                   # (f)
        raise c.S5Error(f"the tranche lists {batch} as {e.get('outcome')}, not {outcome}")
    head = _git(gp, "rev-parse", "HEAD")
    last = _git(tp, "log", "-1", "--format=%H", "--", tp)
    if head.returncode or last.returncode or not last.stdout.strip():
        raise c.S5Error("the governance log / tranche commits cannot be resolved")
    return {"tranche_path": rel, "tranche_sha256": t_sha,
            "glog_section_sha256": hashlib.sha256(section.encode("utf-8")).hexdigest(),
            "glog_commit": head.stdout.strip(), "tranche_commit": last.stdout.strip()}


def record(batch, outcome, decision, decided_by, reference, state_path=stm.STATE, out=OUT, tranche=None, gl_path=GLOG):
    if outcome not in OUTCOMES:
        raise c.S5Error(f"--outcome must be one of {OUTCOMES}")
    for name, v in (("--decision", decision), ("--decided-by", decided_by), ("--reference", reference)):
        if not v or not str(v).strip():
            raise c.S5Error(f"{name} is required (no automatic acceptance)")
    if NON_HUMAN.search(decided_by):
        raise c.S5Error("--decided-by must name the human decider (H-06 is a human decision)")
    st = stm.load(state_path)
    b = stm._batch(st, batch)
    entries = stm.run_history(st, batch, b["run_id"])
    evidenced = {e["to"] for e in entries if e["to"] in ("VERIFIED", "AUDITED") and e.get("evidence", {}).get("sha256")}
    if b["state"] != "AUDITED" or evidenced != {"VERIFIED", "AUDITED"}:
        raise c.S5Error(f"{batch} run {b['run_id']} is {b['state']}: acceptance needs evidenced VERIFIED and AUDITED")
    if any(r.get("batch_id") == batch and r.get("run_id") == b["run_id"] for r in previous_decisions(out)):
        raise c.S5Error("this run already has a recorded H-06 decision")
    if b.get("revision") == 7 and not tranche:
        raise c.S5Error("a revision-7 batch requires --tranche (the committed, G-LOG-anchored H-06 tranche file)")
    bound = check_tranche(tranche, batch, b, st, outcome, decided_by, reference, gl_path) if tranche else None
    to = "ACCEPTED" if outcome == "ACCEPTED" else "FAILED"
    stm.transition(st, batch, to, reason=f"H-06 {outcome}: {reference}", actor=f"human:{decided_by}", _allow_accept=True)
    rec = {"artifact": OUT, "utc": ops.utc_now(), "batch_id": batch, "run_id": b["run_id"], "outcome": outcome,
           "decision_text": decision, "decided_by": decided_by, "reference": reference, "h06": True,
           "state_entry_sha256": st["history"][-1]["entry_sha256"], "seal_id": c.SEAL_ID,
           "recorder": "scripts/p3b_s5_accept.py", "recorder_version": ops.script_blob(__file__)}
    if bound:
        rec.update(bound)
    ops.append_jsonl(out, [rec])
    stm.save(st, state_path)
    return rec


def main(argv=None):
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    ap.add_argument("--batch", required=True)
    ap.add_argument("--outcome", required=True, choices=OUTCOMES)
    ap.add_argument("--decision", required=True)
    ap.add_argument("--decided-by", required=True)
    ap.add_argument("--reference", required=True)
    ap.add_argument("--state", default=stm.STATE)
    ap.add_argument("--out", default=OUT)
    ap.add_argument("--tranche", help="required at revision 7: audit-p3b/H06-TRANCHE-<nnnn>.json (EG-9)")
    ap.add_argument("--glog", default=GLOG, help="the governance log anchoring the tranche")
    a = ap.parse_args(argv)
    try:
        c.assert_sealed()
        rec = record(a.batch, a.outcome, a.decision, a.decided_by, a.reference, a.state, a.out, a.tranche, a.glog)
        c.assert_sealed()
        print(json.dumps(rec, sort_keys=True))
        return 0
    except c.S5Error as ex:
        print(f"REFUSED: {ex}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
