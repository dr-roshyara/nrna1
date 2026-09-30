"""L0-REL-30: test of M3 invariant I-B4 (decision text immutable; change only by annotation / a new act) on the GIT HISTORY of the
rulings register. Frozen with REGISTER-HISTORY/SPEC.json before its first run. Prints NO ruling text: only row IDs, commit ids,
classifications and edit sizes. Deterministic. Usage: python3 register_history_check.py [--selftest]"""
import difflib
import json
import re
import subprocess
import sys

PATH_NOW = "engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md"
LABEL = re.compile(r"annotat|recording correction|provenance|adopted \d{4}-\d{2}-\d{2}|held \d{4}-\d{2}-\d{2}|withdrawn|superseded", re.I)
ROW = re.compile(r"^\|\s*(R-\d+)\s*\|\s*([0-9-]+)\s*\|(.*)$")
REPLACED_BELOW = 0.5      # similarity ratio below which a cell change is classed REPLACED (identity change), frozen


def norm(s): return re.sub(r"\s+", " ", s).strip()


def cells(rest):
    parts = re.split(r"\s\|\s", " " + rest.rstrip().rstrip("|") + " ")
    parts = [p.strip() for p in parts if p is not None]
    parts = [p for p in parts if p != ""] or [""]
    return parts[0], " | ".join(parts[1:])


def classify(first, later):
    a, b = norm(first), norm(later)
    if a == b: return {"class": "UNCHANGED"}
    if b.startswith(a): return {"class": "APPEND-ONLY", "appended_chars": len(b) - len(a), "appended_labelled": bool(LABEL.search(b[len(a):]))}
    sm = difflib.SequenceMatcher(None, a, b, autojunk=False)
    ins = dele = rep = 0; ins_text = []
    for op, i1, i2, j1, j2 in sm.get_opcodes():
        if op == "insert": ins += j2 - j1; ins_text.append(b[j1:j2])
        elif op == "delete": dele += i2 - i1
        elif op == "replace": rep += max(i2 - i1, j2 - j1)
    r = sm.ratio()
    if dele == 0 and rep == 0: return {"class": "INSERT-ONLY", "inserted_chars": ins, "inserted_labelled": bool(LABEL.search(" ".join(ins_text)))}
    return {"class": "REPLACED" if r < REPLACED_BELOW else "MODIFIED", "ratio": round(r, 3), "deleted_chars": dele, "replaced_chars": rep, "inserted_chars": ins}


def history():
    # r2 fix (disclosed): git ignores --follow when combined with --reverse (r1 returned 1 commit). List newest-first, reverse here.
    root = subprocess.run(["git", "rev-parse", "--show-toplevel"], capture_output=True, text=True, check=True).stdout.strip()
    out = subprocess.run(["git", "log", "--follow", "--format=@@C %h %ad", "--date=short", "--name-status", "--", PATH_NOW],
                         capture_output=True, text=True, check=True, cwd=root).stdout.splitlines()
    commits, cur = [], None
    for l in out:
        if l.startswith("@@C "): _, h, d = l.split(); cur = [h, d, None]; commits.append(cur)
        elif l.strip() and cur is not None and cur[2] is None:
            f = l.split("\t"); cur[2] = f[-1]
    return list(reversed(commits))


def rows_at(h, path):
    try: txt = subprocess.run(["git", "show", f"{h}:{path}"], capture_output=True, text=True, check=True).stdout
    except subprocess.CalledProcessError: return None
    rows = {}; dup = []
    for l in txt.splitlines():
        m = ROW.match(l)
        if m:
            rid = m.group(1)
            if rid in rows: dup.append(rid)
            rows[rid] = cells(m.group(3))
    return rows, dup


def run():
    commits = history(); first = {}; last = {}; events = []; dups = {}
    for h, d, p in commits:
        r = rows_at(h, p)
        if r is None: continue
        rows, dup = r
        if dup: dups[h] = sorted(set(dup))
        for rid, (c3, c4) in rows.items():
            if rid not in first: first[rid] = (h, d, c3, c4)
            last[rid] = (h, d, c3, c4)
        for rid in first:
            if rid not in rows and not any(e["row"] == rid and e["event"] == "DELETED" for e in events): events.append({"row": rid, "event": "DELETED", "commit": h, "date": d})
    res = {}
    for rid in sorted(first, key=lambda x: int(x[2:])):
        fh, fd, f3, f4 = first[rid]; lh, ld, l3, l4 = last[rid]
        # per-commit trajectory for column 3: first commit where it differs
        res[rid] = {"first": [fh, fd], "last": [lh, ld], "col3": classify(f3, l3), "col4": classify(f4, l4)}
    return {"commits": len(commits), "paths": sorted({p for _, _, p in commits if p}), "rows": res, "deleted_events": events, "duplicate_ids": dups}


def selftest():
    ok = [("unchanged", classify("a  b", "a b")["class"] == "UNCHANGED"),
          ("append-only", classify("abc", "abc **ANNOTATED x**")["class"] == "APPEND-ONLY"),
          ("labelled append detected", classify("abc", "abc annotation")["appended_labelled"] is True),
          ("insert-only", classify("abc def", "abc XYZ def")["class"] == "INSERT-ONLY"),
          ("modified", classify("the ruling says yes", "the ruling says no")["class"] == "MODIFIED"),
          ("replaced", classify("alpha beta gamma", "zzzz qqqq")["class"] == "REPLACED"),
          ("cell split", cells(" ruling text | effect | ") == ("ruling text", "effect"))]
    for n, g in ok: print(("ok   " if g else "FAIL ") + n)
    return all(g for _, g in ok)


if __name__ == "__main__":
    if "--selftest" in sys.argv: raise SystemExit(0 if selftest() else 1)
    print(json.dumps(run(), indent=1))
