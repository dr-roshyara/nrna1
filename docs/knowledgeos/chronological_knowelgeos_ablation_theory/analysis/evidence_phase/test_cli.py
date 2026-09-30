"""Executable contract test: drives the REAL CLI (`python3 derive_constraints.py ...`) through subprocess.
Synthetic root only (no corpus file exists there); plus one zero-evidence run against the frozen results (reads
propositions.json + formal_basis.json + results_ref.json only). Run: python3 test_cli.py"""
import hashlib
import json
import os
import subprocess
import sys
import tempfile

H = os.path.dirname(os.path.abspath(__file__)); CLI = os.path.join(H, "derive_constraints.py")
T = tempfile.mkdtemp()
def w(name, obj=None, raw=None):
    p = os.path.join(T, name); open(p, "wb").write(raw if raw is not None else json.dumps(obj).encode()); return p
def sha(p): return hashlib.sha256(open(p, "rb").read()).hexdigest()

SRC = w("src.md", raw=b"line one\nthe rule says X holds\nline three\n")
inst = lambda a: {"properties": {"PROP": {"class": a}}}
RES = w("results.json", {"A": {"instances": {"i1": inst("HOLDS")}}, "B": {"instances": {"i1": inst("FAILS")}}, "C": {"instances": {"i1": inst("FAILS")}}})
PROPS = w("props.json", {"primary_models": ["A", "B"], "control_models": ["C"], "propositions": [
    {"id": "P1", "eq": "EQ-x", "claim": "c", "property": "PROP", "requires": "HOLDS"},
    {"id": "P2", "eq": "EQ-y", "claim": "c", "property": None, "gap": "g"}]})
BASIS = w("basis.json", {"status": "SECONDARY-REPRODUCED", "results_path": "results.json", "results_sha256": sha(RES)})
BAD_BASIS = w("basis_bad.json", {"status": "SECONDARY-REPRODUCED", "results_path": "results.json", "results_sha256": "0" * 64})
BAD_STATUS = w("basis_status.json", {"status": "PROBABLY-FINE", "results_path": "results.json", "results_sha256": sha(RES)})
CELL = {"id": "c1", "claim_id": "cl-1", "proposition": "P1", "source_slot": "S-1", "source_path": "src.md", "source_sha256": sha(SRC),
        "anchor": {"start_line": 2, "end_line": 2}, "exact_wording": "rule says X", "historical_date": {"value": "2026-01", "precision": "MONTH"},
        "source_claim": "X holds", "assessment": "SUPPORTED", "interpretation_confidence": "HIGH", "layer": "HISTORICAL-EVIDENCE",
        "reader_class": "SELF", "reader_id": "r1", "l0_release_id": "L0-REL-test"}
VALID = w("valid.json", {"cells": [CELL], "events": []})
BARE = w("bare.json", [CELL])
EMPTY = w("empty.json", {"cells": [], "events": []})
MALFORMED = w("malformed.json", {"cells": [dict(CELL, extra_key=1)], "events": []})
NOTJSON = w("notjson.json", raw=b"{not json")


def run(ev, basis=BASIS, props=PROPS, root=T, synthetic=True):
    args = [sys.executable, CLI, ev] + (["--basis", basis, "--props", props, "--root", root] if synthetic else [])
    return subprocess.run(args, capture_output=True, env=dict(os.environ, PYTHONDONTWRITEBYTECODE="1"))


fails = 0
def check(name, cond):
    global fails
    print(("ok   " if cond else "FAIL ") + name); fails += 0 if cond else 1
def doc(r):
    try: return json.loads(r.stdout.decode())  # exactly ONE JSON document, nothing else on stdout
    except Exception: return None


r = run(VALID); d = doc(r)
check("C1 valid evidence -> exit 0, exactly one JSON document, DERIVED", r.returncode == 0 and d is not None and d["status"] == "DERIVED")
check("C1b constraint derived through the CLI (A consistent, B inconsistent)", d and d["propositions"]["P1"]["per_model"] == {"A": "CONSISTENT", "B": "INCONSISTENT", "C": "INCONSISTENT"})
check("C1c full basis stamp in CLI output", d and d["propositions"]["P1"]["formal_basis"] == {"status": "SECONDARY-REPRODUCED", "results_path": "results.json", "results_sha256": sha(RES)})
r = run(MALFORMED); d = doc(r)
check("C2 malformed evidence -> exit 2, REJECT with violations, nothing derived", r.returncode == 2 and d and d["status"] == "REJECT" and d["violations"] and "propositions" not in d)
r = run(VALID, basis=BAD_BASIS)
check("C3 formal-basis hash mismatch -> non-zero exit, no DERIVED document", r.returncode != 0 and b"DERIVED" not in r.stdout and b"hash mismatch" in r.stderr)
r = run(VALID, basis=BAD_STATUS)
check("C3b formal-basis status outside the closed vocabulary -> non-zero exit", r.returncode != 0 and b"DERIVED" not in r.stdout)
a, b = run(VALID), run(VALID)
check("C4 deterministic: two runs are byte-identical", a.returncode == 0 and a.stdout == b.stdout)
d = doc(run(EMPTY))
check("C5 zero evidence -> every proposition SILENT, no flags, nothing open", d and {p["outcome"] for p in d["propositions"].values()} == {"SILENT"} and not d["family_flags"] and not d["open"])
before = sha(VALID); run(VALID)
check("C6 the evidence file is not rewritten by a run", sha(VALID) == before)
d = doc(run(BARE))
check("C7 bare-list input accepted as cells", d and d["status"] == "DERIVED" and d["propositions"]["P1"]["outcome"] == "SUPPORTED")
r = run(NOTJSON)
check("C8 non-JSON input -> non-zero exit, no DERIVED document", r.returncode != 0 and b"DERIVED" not in r.stdout)
check("C9 the synthetic root contains no corpus file", sorted(os.listdir(T)) == sorted(["src.md", "results.json", "props.json", "basis.json", "basis_bad.json",
      "basis_status.json", "valid.json", "bare.json", "empty.json", "malformed.json", "notjson.json"]))
r = run(EMPTY, synthetic=False); d = doc(r)
check("C10 REAL defaults (frozen results, basis hash verified), zero evidence -> exit 0, all SILENT, all NOT-ELIMINATED",
      r.returncode == 0 and d and {p["outcome"] for p in d["propositions"].values()} == {"SILENT"}
      and {m["status"] for m in d["models"].values()} == {"NOT-ELIMINATED"} and d["formal_basis"]["status"] == "SECONDARY-REPRODUCED")
print("REAL zero-evidence output sha256:", hashlib.sha256(r.stdout).hexdigest())
print("FAILURES:", fails); raise SystemExit(1 if fails else 0)
