#!/usr/bin/env python3
"""
admit_regression.py — regression cases for the `admit.py --audit` VOID-row
repair (L0-DEC-18). Governance-owned.

Each case archives the research workspace at --rev, optionally swaps in the
working-tree evidence/admit.py (--control worktree, the default), applies ONE
mutation to the COPY, runs `admit.py --audit`, and compares the outcome with
the expected post-repair behaviour written BEFORE the repair.

    python3 governance/regression/admit_regression.py                 # repaired admit.py (working tree)
    python3 governance/regression/admit_regression.py --control rev   # admit.py as committed at --rev (RED)

⛔ Never touches the live workspace, research evidence or registry state.
Mutations exist only in the temp copy; a result here is a TEST outcome, not a
production binding verdict.

Declared VOID shape (READ-RECEIPTS row 8, `cd845b6ab`):
    status starts with "VOID", correction_of present, file_id names a file
    with an EARLIER genuine receipt, and NO receipt fields
    (sha256_at_read / manifest_hash).
"""

import argparse, json, os, shutil, subprocess, sys, tempfile

HERE = os.path.dirname(os.path.abspath(__file__))
WS = os.path.dirname(os.path.dirname(HERE))
J = os.path.join
DIV9 = "['F0031', 'F0032', 'F0033', 'F0034', 'F0035', 'F0036', 'F0037', 'F0038', 'F0040']"


def R(t): return J(t, "evidence", "READ-RECEIPTS.jsonl")
def rows(t): return [json.loads(l) for l in open(R(t), encoding="utf-8") if l.strip()]
def put(t, L): open(R(t), "w", encoding="utf-8").write("".join(json.dumps(x, ensure_ascii=False) + "\n" for x in L))


def drop_void(t): put(t, [r for r in rows(t) if not str(r.get("status", "")).startswith("VOID")])
def stale_one(t):
    L = rows(t); L[0]["manifest_hash"] = "0" * 64; put(t, L)
def drift_one(t):
    L = rows(t); L[1]["sha256_at_read"] = "f" * 64; put(t, L)
def void_missing_correction(t):
    L = rows(t); L.append({"file_id": "F0041", "status": "VOID — test", "when": "2026-09-23"}); put(t, L)
def void_with_receipt_fields(t):
    L = rows(t); x = dict(L[0]); x.update(status="VOID — test", correction_of="F0041 receipt"); L.append(x); put(t, L)
def void_orphan(t):
    L = rows(t); L.append({"file_id": "F0999", "status": "VOID — test", "correction_of": "F0999 receipt", "when": "2026-09-23"}); put(t, L)
def unknown_shape(t):
    L = rows(t); L.append({"file_id": "F0041", "note": "neither receipt nor VOID"}); put(t, L)
def no_registry(t): os.remove(J(t, "FILE-REGISTRY.jsonl"))
def non_json_line(t):
    open(R(t), "a", encoding="utf-8").write('{"file_id": "F0041", \n')
def invalid_utf8_line(t):
    open(R(t), "ab").write(b'{"file_id": "F\xff\xfe"}\n')
def unhashable_file_id(t):
    L = rows(t); x = dict(L[0]); x["file_id"] = [x["file_id"]]; L.append(x); put(t, L)


# IR-A2 (L0-DEC-21): the expected receipt count is DERIVED from the snapshot,
# never hard-coded. Counted here independently of admit.py: rows that carry
# `sha256_at_read` and no `status`, counted BEFORE the case's mutation.
RCOUNT = "read receipts    : {N}"


def genuine_receipts(t):
    n = 0
    for l in open(R(t), encoding="utf-8"):
        try:
            r = json.loads(l) if l.strip() else None
        except ValueError:
            continue
        if isinstance(r, dict) and "sha256_at_read" in r and "status" not in r:
            n += 1
    return n


# (id, mutation, expected exit, expected STATUS, must-contain, must-not-contain, note)
CASES = [
    ("V1", None, 3, "BINDING_FAILURES_PRESENT",
     [RCOUNT, "VOID annotation", DIV9],
     ["Traceback", "DIFFERENT manifest hash", "file changed since reading", "unrecognised"],
     "HEAD: completes; STOP only on the known 9-ID divergence"),
    ("V2", drop_void, 3, "BINDING_FAILURES_PRESENT",
     [RCOUNT, DIV9], ["Traceback", "DIFFERENT manifest hash"],
     "control: same verdict without the VOID row"),
    ("V3", stale_one, 3, "BINDING_FAILURES_PRESENT",
     ["1 receipt(s) bound to a DIFFERENT manifest hash"], ["Traceback"],
     "VOID exclusion must not mask a genuinely stale receipt"),
    ("V4", drift_one, 3, "BINDING_FAILURES_PRESENT",
     ["1 receipt(s) whose file changed since reading"], ["Traceback"],
     "VOID exclusion must not mask drift"),
    ("V5", void_missing_correction, 3, "BINDING_FAILURES_PRESENT",
     ["unrecognised"], ["Traceback"], "near-miss VOID (no correction_of) is not accepted as VOID"),
    ("V6", void_with_receipt_fields, 3, "BINDING_FAILURES_PRESENT",
     ["unrecognised"], ["Traceback"], "a receipt cannot be hidden by adding a VOID status"),
    ("V7", void_orphan, 3, "BINDING_FAILURES_PRESENT",
     ["unrecognised"], ["Traceback"], "a VOID that voids no earlier receipt is not a declared VOID"),
    ("V8", unknown_shape, 3, "BINDING_FAILURES_PRESENT",
     ["unrecognised"], ["Traceback"], "unknown row shape: reported failure, no crash"),
    ("V9", no_registry, 0, "BOUND",
     [RCOUNT, "VOID annotation"], ["Traceback", "DIFFERENT manifest hash"],
     "the VOID row alone causes no failure (registry section absent in the copy)"),
    # ── L0-DEC-21 IR-A1 (written before the fix) ──
    ("V10", non_json_line, 3, "BINDING_FAILURES_PRESENT",
     ["unrecognised", RCOUNT], ["Traceback"], "IR-A1: an unparseable receipt line is an unrecognised row, not a crash"),
    ("V11", unhashable_file_id, 3, "BINDING_FAILURES_PRESENT",
     ["unrecognised", RCOUNT], ["Traceback"], "IR-A1: a non-string file_id is an unrecognised row, not a crash"),
    # ── L0-DEC-24 (written before the fix) ──
    ("V12", invalid_utf8_line, 3, "BINDING_FAILURES_PRESENT",
     ["unrecognised", RCOUNT], ["Traceback"], "IR-A1/L0-DEC-24: an invalid UTF-8 receipt line is an unrecognised row, not a crash"),
]


def snapshot(rev, control, dest):
    top = subprocess.run(["git", "rev-parse", "--show-toplevel"], cwd=WS, capture_output=True, text=True, check=True).stdout.strip()
    rel = os.path.relpath(WS, top)
    tar = J(dest, "s.tar")
    subprocess.run(["git", "archive", "-o", tar, rev, "--", rel], cwd=top, check=True)
    subprocess.run(["tar", "-xf", tar, "-C", dest], check=True); os.remove(tar)
    base = J(dest, rel)
    if control == "worktree":
        shutil.copy(J(WS, "evidence", "admit.py"), J(base, "evidence", "admit.py"))
    return dest, rel


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--rev", default="HEAD")
    ap.add_argument("--control", choices=("worktree", "rev"), default="worktree")
    a = ap.parse_args()
    work = tempfile.mkdtemp(prefix="kos-admit-regression.")
    bad = 0
    try:
        src, rel = snapshot(a.rev, a.control, work)
        for cid, mut, ex, st, need, forbid, note in CASES:
            d = tempfile.mkdtemp(prefix=cid + ".", dir=work)
            shutil.copytree(J(src, "docs"), J(d, "docs"))
            t = J(d, rel)
            need = [s.replace("{N}", str(genuine_receipts(t))) for s in need]
            if mut: mut(t)
            p = subprocess.run([sys.executable, "evidence/admit.py", "--audit"], cwd=t, capture_output=True, text=True)
            out = p.stdout + p.stderr
            status = next((l.split("STATUS:", 1)[1].strip() for l in out.splitlines() if l.startswith("STATUS:")), None)
            miss = []
            if p.returncode != ex: miss.append(f"exit {p.returncode} != {ex}")
            if status != st: miss.append(f"STATUS {status} != {st}")
            miss += [f"lacks {s!r}" for s in need if s not in out]
            miss += [f"contains {s!r}" for s in forbid if s in out]
            bad += bool(miss)
            print(f"  [{'OK ' if not miss else 'RED'}] {cid}  exit={p.returncode} STATUS={status}  {'; '.join(miss)}")
            shutil.rmtree(d, ignore_errors=True)
        print(f"\nadmit regression: {len(CASES) - bad}/{len(CASES)} match  (rev={a.rev}, control={a.control})")
        return 1 if bad else 0
    finally:
        shutil.rmtree(work, ignore_errors=True)


if __name__ == "__main__":
    sys.exit(main())
