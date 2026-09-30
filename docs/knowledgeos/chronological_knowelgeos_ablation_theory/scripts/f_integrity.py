#!/usr/bin/env python3
"""F-Series v1.2 integrity layer (remediation of audit findings F-01, F-07, F-08, F-12, F-17, F-20).

- Hash-chained state ledger: every line written under v1.2 carries `prev_hash` (sha256 of the previous line's bytes)
  and `hash` (sha256 of its own canonical JSON without `hash`). The pre-v1.2 prefix is sealed by F-STATE-ANCHOR.json
  (line count + sha256 of those bytes). `verify_chain()` also checks per-F-ID seq continuity, `from` = previous state
  and transition legality for every line.
- Stage freezing: each stage event records {artifact: sha256}; every later gate and the audit call `verify_frozen()`.
- Execution approval: no transition beyond F-P0 and no reading unless F-GOVERNANCE-LOG.md carries
  `APPROVED-FOR-EXECUTION: <path> <sha256>` for each governing document in force.
- Human references: `F-LOG-####` must exist in the governance log and its section must name the F-ID.
- Human-decision switches: F-DECISIONS.json; a null value blocks everything that depends on it.
Nothing here edits or removes an existing line.
"""
import json
import os
import re

import f_common as C

ANCHOR = os.path.join(C.FDIR, "F-STATE-ANCHOR.json")
GOVLOG = os.path.join(C.FDIR, "F-GOVERNANCE-LOG.md")
DECISIONS = os.path.join(C.FDIR, "F-DECISIONS.json")
F_P0_STATES = {"REGISTERED", "RESOLVED", "RESOLUTION-FAILED", "FIREWALL-LIMITED", "SELF-CITATION-EXCLUDED",
               "IDENTIFIED", "EMPTY", "BINARY", "NON-TEXT"}

# artifacts frozen by each stage event (ledger/F####/<name>); optional ones are frozen when present
STAGE_ARTIFACTS = {
    "READ-COMPLETE": ["PAGE-DIGESTS.jsonl"],
    "CONTENT-EXTRACTED": ["UNITS.jsonl", "CONTENT-INVENTORY.jsonl", "UNIT-DISPOSITIONS.jsonl", "CATEGORY-CHECK.json",
                          "ISOLATION-ATTESTATION.json"],
    "RECONSTRUCTED": ["files.jsonl", "contributions.jsonl", "index-proposals.jsonl"],
    "ANALYZED": ["ANALYSIS.jsonl", "ANALYSIS-CHECKLIST.json", "CROSS-FILE.jsonl"],
    "RESEARCHED": ["research.jsonl"],
}
OPTIONAL_ARTIFACTS = {"RECONSTRUCTED": ["INDEPENDENT-AUDIT-L1.json", "AUDITOR-INVENTORY.jsonl",
                                        "AUDITOR-PAGE-DIGESTS.jsonl", "AUDITOR-ATTESTATION.json"]}
STAGE_ORDER = ["READING", "READ-COMPLETE", "CONTENT-EXTRACTED", "RECONSTRUCTED", "ANALYZED", "RESEARCHED", "AUDITED"]


# ---- canonical hashing --------------------------------------------------------------------------------------------
def canonical(rec):
    return json.dumps(rec, sort_keys=True, separators=(",", ":"), ensure_ascii=False)


def line_hash(line_bytes):
    return C.sha256_bytes(line_bytes.rstrip(b"\n"))


def raw_lines():
    if not os.path.exists(C.STATE):
        return []
    with open(C.STATE, "rb") as f:
        return [l for l in f.read().split(b"\n") if l.strip()]


# ---- chain --------------------------------------------------------------------------------------------------------
def anchor():
    return json.load(open(ANCHOR, encoding="utf-8")) if os.path.exists(ANCHOR) else None


def write_anchor():
    """Seal the current ledger as the legacy prefix (v1.2 adoption). Refuses if an anchor exists."""
    if os.path.exists(ANCHOR):
        raise RuntimeError("anchor exists (sealed once)")
    lines = raw_lines()
    rec = {"legacy_lines": len(lines), "legacy_sha256": C.sha256_bytes(b"\n".join(lines) + (b"\n" if lines else b"")),
           "last_line_hash": line_hash(lines[-1]) if lines else "GENESIS", "sealed_utc": C.utc(),
           "note": "pre-v1.2 events (no hash fields); sealed at v1.2 adoption"}
    with open(C.guard(ANCHOR), "w", encoding="utf-8") as f:
        json.dump(rec, f, indent=1)
    return rec


def verify_chain():
    """Return (ok, findings). Checks anchor, hash links, per-F seq continuity, from = previous state, legality."""
    f = []
    lines = raw_lines()
    an = anchor()
    n_legacy = 0
    if an:
        n_legacy = an["legacy_lines"]
        body = b"\n".join(lines[:n_legacy]) + (b"\n" if n_legacy else b"")
        if len(lines) < n_legacy or C.sha256_bytes(body) != an["legacy_sha256"]:
            f.append("legacy prefix does not match F-STATE-ANCHOR.json (an old event was edited or removed)")
    prev = line_hash(lines[n_legacy - 1]) if n_legacy else "GENESIS"
    last = {}
    for i, lb in enumerate(lines):
        try:
            e = json.loads(lb)
        except json.JSONDecodeError:
            f.append(f"line {i + 1}: not JSON")
            continue
        if i >= n_legacy:
            h = e.get("hash")
            body = {k: v for k, v in e.items() if k != "hash"}
            if e.get("prev_hash") != prev:
                f.append(f"line {i + 1}: prev_hash does not match the previous line")
            if h != C.sha256_bytes(canonical(body).encode("utf-8")):
                f.append(f"line {i + 1}: hash does not match the record")
            prev = line_hash(lb)
        fid = e.get("f_id")
        p_state, p_seq = last.get(fid, (None, 0))
        if e.get("seq") != p_seq + 1:
            f.append(f"line {i + 1}: {fid} seq {e.get('seq')} does not follow {p_seq}")
        if e.get("from") != p_state:
            f.append(f"line {i + 1}: {fid} from={e.get('from')} but previous state is {p_state}")
        st = e.get("state")
        if st not in C.ALLOWED_FROM or e.get("from") not in C.ALLOWED_FROM[st]:
            f.append(f"line {i + 1}: {fid} {e.get('from')} -> {st} is not a legal transition")
        last[fid] = (st, e.get("seq"))
    return not f, f


def chained_record(rec):
    """Add prev_hash and hash to a new record (called by f_common.record_event)."""
    lines = raw_lines()
    an = anchor()
    if lines and not an and "hash" not in json.loads(lines[0]):
        raise RuntimeError("ledger has unchained lines but no F-STATE-ANCHOR.json")
    rec = dict(rec, prev_hash=line_hash(lines[-1]) if lines else "GENESIS")
    rec["hash"] = C.sha256_bytes(canonical(rec).encode("utf-8"))
    return rec


# ---- freezing -----------------------------------------------------------------------------------------------------
def artifact_hashes(fid, stage):
    out = {}
    for name in STAGE_ARTIFACTS.get(stage, []):
        p = os.path.join(C.LEDGER, fid, name)
        out[name] = C.sha256_file(p) if os.path.exists(p) else None
    for name in OPTIONAL_ARTIFACTS.get(stage, []):
        p = os.path.join(C.LEDGER, fid, name)
        if os.path.exists(p):
            out[name] = C.sha256_file(p)
    return out


def governing_hashes():
    return {rel: (C.sha256_file(os.path.join(C.REPO, rel)) if os.path.exists(os.path.join(C.REPO, rel)) else None)
            for rel in C.GOVERNING_DOCS}


def active_freezes(fid):
    """Freezes still in force: a stage event re-freezes itself and invalidates later stages; READING resets."""
    frozen = {}
    for e in C.events(fid):
        st = e["state"]
        if st == "READING":
            frozen = {}
        if st in STAGE_ARTIFACTS and isinstance(e.get("evidence"), dict) and "frozen" in e["evidence"]:
            k = STAGE_ORDER.index(st)
            frozen = {s: v for s, v in frozen.items() if STAGE_ORDER.index(s) < k}
            frozen[st] = e["evidence"]["frozen"]
    return frozen


def verify_frozen(fid, before=None):
    """Re-verify every freeze in force; with `before`, only the stages that precede it (a transition into a stage
    re-freezes that stage, so its own earlier freeze is superseded, never silently kept)."""
    f = []
    for stage, arts in active_freezes(fid).items():
        if before in STAGE_ORDER and STAGE_ORDER.index(stage) >= STAGE_ORDER.index(before):
            continue
        for name, h in arts.items():
            p = os.path.join(C.LEDGER, fid, name)
            cur = C.sha256_file(p) if os.path.exists(p) else None
            if cur != h:
                f.append(f"{name} (frozen at {stage}) changed after the gate: {h} -> {cur}")
    return not f, f


# ---- governance ---------------------------------------------------------------------------------------------------
def govlog_text():
    return open(GOVLOG, encoding="utf-8").read() if os.path.exists(GOVLOG) else ""


def require_approval():
    """F-20: execution requires an approval line for each governing document with its current sha256. Only plain lines
    count: lines inside fenced code blocks (``` or ~~~) and indented lines are templates, never approvals."""
    kept, fence = [], None
    for ln in govlog_text().split("\n"):
        m = re.match(r"\s*(```|~~~)", ln)
        if m:
            fence = None if fence == m.group(1) else (fence or m.group(1))
            continue
        if fence is None:
            kept.append(ln)
    text = "\n".join(kept)
    missing = []
    for rel, h in governing_hashes().items():
        if h is None or not re.search(rf"^APPROVED-FOR-EXECUTION: {re.escape(rel)} {h}\s*$", text, re.MULTILINE):
            missing.append(f"{rel} ({h})")
    if missing:
        raise RuntimeError("PROTOCOL NOT APPROVED FOR EXECUTION — missing 'APPROVED-FOR-EXECUTION: <path> <sha256>' "
                           "in F-GOVERNANCE-LOG.md for: " + "; ".join(missing))
    # the approved contracts index pins every contract file: a changed contract revokes execution (C14 v1.2 §1)
    idx = os.path.join(C.REPO, C.CONTRACTS_INDEX_REL)
    drift = []
    for rel, h in re.findall(r"\|\s*`([^`]+\.md)`\s*\|\s*`([0-9a-f]{64})`\s*\|", open(idx, encoding="utf-8").read()):
        p = os.path.join(C.REPO, rel)
        if not os.path.exists(p) or C.sha256_file(p) != h:
            drift.append(rel)
    if drift:
        raise RuntimeError("PROTOCOL NOT APPROVED FOR EXECUTION — contract file(s) differ from the approved index: "
                           + "; ".join(drift))


def human_ref_ok(ref, fid):
    """F-08: a human reference is an existing F-LOG entry whose section names the F-ID."""
    if not ref or not re.fullmatch(r"F-LOG-\d{4}", ref):
        return False
    text = govlog_text()
    m = re.search(rf"^## {ref}\b.*?(?=^## |\Z)", text, re.MULTILINE | re.DOTALL)
    return bool(m and re.search(rf"\b{re.escape(fid)}\b", m.group(0)))


def decision(key):
    d = json.load(open(DECISIONS, encoding="utf-8")) if os.path.exists(DECISIONS) else {}
    return d.get(key)
