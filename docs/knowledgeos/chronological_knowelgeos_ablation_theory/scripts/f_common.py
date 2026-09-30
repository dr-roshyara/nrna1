#!/usr/bin/env python3
"""F-Series shared library: fixed paths, manifest access, page model, append-only state ledger, transition rules.

Governing protocol: prompts/20260925_1239_F-SERIES-RESEARCH-PROTOCOL-v1.0.md (sections cited as §F-n).
The repository artifacts are the state (§F-9). Nothing in this module edits or removes a ledger line.
"""
import datetime
import hashlib
import json
import os
import subprocess

REPO = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True,
                               cwd=os.path.dirname(os.path.abspath(__file__))).strip()
FDIR_REL = "docs/knowledgeos/chronological_knowelgeos_ablation_theory"
S_REPO = REPO                                                          # the S-Series reader is always the real one
TEST_ROOT = os.environ.get("F_SERIES_TEST_ROOT")                      # test seam (tests/): a throw-away root outside
if TEST_ROOT:                                                          # the repository holding fixture content + state
    if os.path.abspath(TEST_ROOT).startswith(REPO):
        raise RuntimeError("F_SERIES_TEST_ROOT must be outside the repository")
    REPO = TEST_ROOT
FDIR = os.path.join(REPO, FDIR_REL)
FLIST_REL = "docs/knowledgeos/20260925_1206_list_of_files_to_read.log"   # authoritative population + order (§F-2)
MANIFEST = os.path.join(FDIR, "F-MANIFEST.jsonl")
STATE = os.path.join(FDIR, "F-SERIES-STATE.jsonl")                   # ledger A: where are we?
INTEGRITY = os.path.join(FDIR, "F-READ-INTEGRITY.jsonl")              # ledger B: did we actually read it?
LEDGER = os.path.join(FDIR, "ledger")                                  # per-F records: ledger/F####/
PROTOCOL_REL = f"{FDIR_REL}/prompts/20260925_1412_F-SERIES-RESEARCH-PROTOCOL-v1.2.md"   # supersedes v1.1 (F-LOG-0008)
CONTRACT_REL = f"{FDIR_REL}/prompts/20260925_1412_F-SERIES-AGENT-CONTRACT-v1.2.md"      # runbook
CONTRACTS_INDEX_REL = f"{FDIR_REL}/prompts/contracts-v1.2/00-CONTRACTS-IN-FORCE.md"      # which C01–C15 file applies
# F-20: every document whose sha256 must carry an APPROVED-FOR-EXECUTION line before any execution (f_integrity)
GOVERNING_DOCS = [PROTOCOL_REL, CONTRACT_REL, CONTRACTS_INDEX_REL]
FIREWALL_PREFIXES = ["docs/knowledgeos/brainstorming/three_model_convergence/"]   # inherited, v3.5 A0
SELF_PREFIXES = [FDIR_REL + "/"]                                                   # inherited, v3.5 A4 guard
# Reading integrity is REUSED, not re-implemented (§F-7, isolation rule §6). The verified code is taken from the
# COMMITTED blob of the S-Series reader (contract revision 3, G-LOG-0050) at a pinned commit and sha256 — never from the
# working tree, which another session may be editing (observed 2026-09-25, F-LOG-0002). Only the three verified
# definitions below are compiled from it (no module-level code runs, nothing is written anywhere).
import ast as _ast
S_READER_REL = "docs/knowledgeos/chronological-read/scripts/p3b_read_source.py"
S_READER_COMMIT = "b7e7fc856"
S_READER_SHA256 = "0d48829a2df7fd0cb60c3017ee8f086450c32e3bd3b029f4b06b693df5905902"
_src = subprocess.check_output(["git", "show", f"{S_READER_COMMIT}:{S_READER_REL}"], cwd=S_REPO)
if hashlib.sha256(_src).hexdigest() != S_READER_SHA256:
    raise RuntimeError("committed S-Series reader blob does not match its pin; F-Series refuses to run (§F-7)")
_keep = [n for n in _ast.parse(_src).body
         if (isinstance(n, _ast.FunctionDef) and n.name in ("pages_of", "stdout_kind"))
         or (isinstance(n, _ast.Assign) and [t.id for t in n.targets if isinstance(t, _ast.Name)] == ["PAGE_CHARS"])]
if len(_keep) != 3:
    raise RuntimeError("pinned S-Series reader no longer defines PAGE_CHARS, pages_of, stdout_kind")
_ns = {"os": os, "sys": __import__("sys")}
exec(compile(_ast.Module(body=_keep, type_ignores=[]), f"{S_READER_COMMIT}:{S_READER_REL}", "exec"), _ns)
PAGE_CHARS = _ns["PAGE_CHARS"]                                        # 20000
pages_of = _ns["pages_of"]                                            # identical page boundaries to S5 rev. 3
stdout_kind = _ns["stdout_kind"]

# §F-6 state machine. Main line, then exception states. A transition is legal only from the listed states.
MAIN = ["REGISTERED", "RESOLVED", "IDENTIFIED", "READING", "READ-COMPLETE", "CONTENT-EXTRACTED", "RECONSTRUCTED",
        "ANALYZED", "RESEARCHED", "AUDITED"]      # v1.1: READ-COMPLETE ≠ CONTENT-COMPLETE ≠ RESEARCH-COMPLETE
TERMINAL_EXCEPTIONS = ["PLACEHOLDER", "EMPTY", "BINARY", "NON-TEXT", "EXACT-DUPLICATE", "RESOLUTION-FAILED",
                       "FIREWALL-LIMITED", "SELF-CITATION-EXCLUDED", "READ-PARTIAL", "READ-FAILED",
                       "CONTENT-EXTRACTION-UNRESOLVED", "RECONSTRUCTION-UNRESOLVED", "ANALYSIS-UNRESOLVED",
                       "RESEARCH-UNRESOLVED"]
ALLOWED_FROM = {
    "REGISTERED": {None},
    "RESOLVED": {"REGISTERED"},
    "RESOLUTION-FAILED": {"REGISTERED"},
    "FIREWALL-LIMITED": {"REGISTERED"},
    "SELF-CITATION-EXCLUDED": {"REGISTERED", "RESOLVED"},
    "IDENTIFIED": {"RESOLVED"},
    "EMPTY": {"RESOLVED"},
    "BINARY": {"RESOLVED"},
    "NON-TEXT": {"RESOLVED"},
    "EXACT-DUPLICATE": {"IDENTIFIED"},           # applied at processing time, ruling RL-03 (§F-5)
    "PLACEHOLDER": {"READ-COMPLETE"},            # a judgement, so only after complete reading
    "READING": {"IDENTIFIED", "READ-PARTIAL", "READ-FAILED"},
    "READ-PARTIAL": {"READING"},
    "READ-FAILED": {"READING"},
    "READ-COMPLETE": {"READING"},
    "CONTENT-EXTRACTED": {"READ-COMPLETE", "CONTENT-EXTRACTED"},   # v1.2: re-extraction only with a human ref (F-06)
    "CONTENT-EXTRACTION-UNRESOLVED": {"READ-COMPLETE"},
    "RECONSTRUCTED": {"CONTENT-EXTRACTED"},       # v1.1: reconstruction consumes the inventory, never the reverse
    "RECONSTRUCTION-UNRESOLVED": {"CONTENT-EXTRACTED"},
    "ANALYZED": {"RECONSTRUCTED"},                # v1.1 Level 2: lens checklist + incremental cross-file discovery
    "ANALYSIS-UNRESOLVED": {"RECONSTRUCTED"},
    "RESEARCHED": {"ANALYZED"},                   # v1.1 Level 3: hypotheses, pre-registered, never tested per file
    "RESEARCH-UNRESOLVED": {"ANALYZED"},
    "AUDITED": {"RESEARCHED"} | set(TERMINAL_EXCEPTIONS),
    "AUDIT-FAILED": {"RESEARCHED"} | set(TERMINAL_EXCEPTIONS),
}
# v1.2 (F-17): after AUDIT-FAILED the F-ID is re-entered at a stage — never audited as such (no AUDIT-FAILED→AUDITED).
# Re-entry keeps the run id (f_transition), re-verifies the freezes before the stage and re-freezes the stage.
for _s in ("READING", "CONTENT-EXTRACTED", "RECONSTRUCTED", "ANALYZED", "RESEARCHED"):
    ALLOWED_FROM[_s] = ALLOWED_FROM[_s] | {"AUDIT-FAILED"}


def utc():
    return datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")


def sha256_bytes(b):
    return hashlib.sha256(b).hexdigest()


def sha256_file(path):
    with open(path, "rb") as f:
        return sha256_bytes(f.read())


def head_commit():
    return subprocess.check_output(["git", "rev-parse", "HEAD"], text=True, cwd=S_REPO).strip()


def read_jsonl(path):
    if not os.path.exists(path):
        return []
    with open(path, encoding="utf-8") as f:
        return [json.loads(l) for l in f if l.strip()]


def guard(path):
    """Write guard (§F-11): every F-Series write goes through here and must land inside the lane folder."""
    p = os.path.realpath(path)
    if not p.startswith(os.path.realpath(FDIR) + os.sep):
        raise RuntimeError(f"WRITE-OUTSIDE-LANE refused: {path}")
    return path


def append_jsonl(path, rec):
    guard(path)
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "a", encoding="utf-8") as f:
        f.write(json.dumps(rec, sort_keys=True, separators=(",", ":"), ensure_ascii=False) + "\n")


def manifest():
    """{f_id: row} in list order (dict preserves insertion order)."""
    return {r["f_id"]: r for r in read_jsonl(MANIFEST)}


def content_text(row):
    """Decoded text of the manifest-identified content. Refuses on identity drift (sha256 changed since P0)."""
    path = os.path.join(REPO, row["resolved_path"])
    b = open(path, "rb").read()
    if sha256_bytes(b) != row["content_sha256"]:
        raise RuntimeError(f"IDENTITY-DRIFT: {row['f_id']} {row['resolved_path']} no longer matches manifest sha256")
    return b.decode("utf-8", errors="replace")


def events(f_id=None):
    ev = read_jsonl(STATE)
    return [e for e in ev if f_id is None or e["f_id"] == f_id]


def current_state(f_id):
    ev = events(f_id)
    return ev[-1]["state"] if ev else None


def next_fid():
    """The first F-ID in processing (list) order that is not AUDITED; None when all are."""
    last = {}
    for e in events():
        last[e["f_id"]] = e["state"]
    for fid in manifest():
        if last.get(fid) != "AUDITED":
            return fid
    return None


def record_event(f_id, state, evidence, run_id=None, note=None):
    """Append one hash-chained transition event (v1.2). Refuses on a broken chain, an illegal transition, or —
    beyond F-P0 — an unapproved protocol (F-20)."""
    import f_integrity as I                                       # local import: f_integrity imports this module
    ok, f = I.verify_chain()
    if not ok:
        raise RuntimeError("STATE-LEDGER-CHAIN-BROKEN: " + "; ".join(f[:5]))
    if state not in I.F_P0_STATES:
        I.require_approval()
    cur = current_state(f_id)
    if state not in ALLOWED_FROM or cur not in ALLOWED_FROM[state]:
        raise RuntimeError(f"ILLEGAL-TRANSITION: {f_id} {cur} -> {state}")
    rec = {"f_id": f_id, "seq": len(events(f_id)) + 1, "from": cur, "state": state, "utc": utc(),
           "run_id": run_id, "evidence": evidence, "note": note, "protocol": PROTOCOL_REL,
           "governing_sha256": I.governing_hashes()}
    rec = I.chained_record(rec)
    append_jsonl(STATE, rec)
    return rec
