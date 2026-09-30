#!/usr/bin/env python3
"""P3b S5 operations helper library (plan v2.3.2 §B, §C, §K, §M; G-LOG-0041). Imported by the S5 operations tools;
never executed on its own. Infrastructure only: nothing here dispatches a batch or reads corpus content.

  * `load_common()` loads the committed `p3b_s5_common.py` (the only data-access layer).
  * `check_paths()` applies the plan §M item 6 allowlist to every path inside the repository C; a path outside C is a
    review / scratch copy (tests, `/tmp` builds) and is refused only if its name is a forbidden census artifact.
  * `canon_hash()` is the canonical-JSON sha256 used by every S5 operations artifact.
  * `append_jsonl()` is the single append-only writer (it never truncates or rewrites a line).
"""
import datetime
import hashlib
import importlib.util
import json
import os
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_COMMON = None


def load_common():
    global _COMMON
    if _COMMON is None:
        spec = importlib.util.spec_from_file_location("p3b_s5_common", os.path.join(_HERE, "p3b_s5_common.py"))
        mod = importlib.util.module_from_spec(spec)
        spec.loader.exec_module(mod)
        _COMMON = mod
    return _COMMON


def load_script(name):
    """Load a sibling S5 script as a module (tools import each other's reviewed APIs this way)."""
    spec = importlib.util.spec_from_file_location(name, os.path.join(_HERE, name + ".py"))
    mod = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(mod)
    return mod


def utc_now():
    return datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ")


def canon(obj):
    return json.dumps(obj, sort_keys=True, separators=(",", ":"), ensure_ascii=False)


def canon_hash(obj):
    return hashlib.sha256(canon(obj).encode("utf-8")).hexdigest()


def sha256_path(path):
    with open(path, "rb") as f:
        return hashlib.sha256(f.read()).hexdigest()


def inside_cr(path):
    """Judged on the REAL path (symlinks resolved): a /tmp symlink to a repository file is inside."""
    return load_common().rel(abspath(path)) is not None


def abspath(path):
    c = load_common()
    return os.path.abspath(path if os.path.isabs(path) else os.path.join(c.CR, path))


def check_paths(paths, purpose=None):
    """Plan §M item 6. A path whose real path lies inside C goes to `check_inputs` (allowlist, forbidden list, symlinks
    resolved). A genuinely outside path (review / scratch copy) is refused if its own name or its target's name is a
    forbidden census artifact name. Refusals never echo a path (G-LOG-0042 M2)."""
    c = load_common()
    inside, bad = [], 0
    forbidden_names = {os.path.basename(f.rstrip("/")) for f in c.FORBIDDEN if not f.endswith("/")}
    for p in paths:
        ap = abspath(p)
        if inside_cr(ap):
            inside.append(ap)
        elif os.path.basename(ap) in forbidden_names or os.path.basename(os.path.realpath(ap)) in forbidden_names:
            bad += 1
    if bad:
        raise c.S5Error(f"{bad} path(s) outside the repository carry a forbidden census artifact name")
    if inside:
        c.check_inputs(inside, purpose=purpose)
    return True


def is_allowlisted(path, purpose=None):
    """True iff the path is inside C and passes `check_inputs` (it may then be printed or stored)."""
    c = load_common()
    try:
        return inside_cr(path) and c.check_inputs([abspath(path)], purpose=purpose)
    except c.S5Error:
        return False


def display(path, n, purpose=None):
    """A path safe to print or store: the repository-relative path if allowlisted, else a numbered placeholder (a
    non-allowlisted path could be a hold-out label name)."""
    if is_allowlisted(path, purpose):
        return load_common().rel(abspath(path)).replace(os.sep, "/")
    return f"<unlisted path #{n}>"


def read_jsonl(path):
    with open(abspath(path), encoding="utf-8") as f:
        return [json.loads(line) for line in f if line.strip()]


def append_jsonl(path, records):
    """Append-only: opens with mode 'a'; never rewrites an existing line."""
    path = abspath(path)
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "a", encoding="utf-8") as f:
        for r in records:
            f.write(canon(r) + "\n")


def write_new(path, text):
    """Write a file that must not exist yet (written-once artifacts)."""
    path = abspath(path)
    if os.path.exists(path):
        raise load_common().S5Error(f"refusing to overwrite an existing written-once artifact: {os.path.basename(path)}")
    os.makedirs(os.path.dirname(path), exist_ok=True)
    tmp = path + ".tmp"
    with open(tmp, "w", encoding="utf-8") as f:
        f.write(text)
    os.replace(tmp, path)


def write_atomic(path, text):
    path = abspath(path)
    os.makedirs(os.path.dirname(path), exist_ok=True)
    tmp = path + ".tmp"
    with open(tmp, "w", encoding="utf-8") as f:
        f.write(text)
    os.replace(tmp, path)


def header_inputs(paths):
    """§19.5 `input_hashes` keys: repository-relative for files inside C (so the allowlist check applies), absolute for
    review / scratch copies outside C."""
    c = load_common()
    return sorted(c.rel(abspath(p)) if inside_cr(p) else abspath(p) for p in paths)


def script_blob(script_file):
    return load_common().git("hash-object", os.path.abspath(script_file))


def is_comparison(rec):
    """COMPARISON (rerun, §K) records: run id `OB####-R2S`, or an explicit COMPARISON role."""
    rid = str(rec.get("run_id") or "")
    return rid.endswith("-R2S") or rec.get("record_role") == "COMPARISON" or rec.get("comparison") is True


def is_reanalysis(rec):
    """S5a same-model re-analysis dispositions (§K): never baseline, never frame."""
    return rec.get("record_role") == "REANALYSIS" or rec.get("kind") == "REANALYSIS-DISPOSITION"


def record_id(rec):
    """Object records are keyed by working_label (with run id); register records by rs_id."""
    if rec.get("rs_id"):
        return str(rec["rs_id"])
    return f"{rec.get('run_id')}:{rec.get('batch_id')}:{rec.get('working_label')}"


if __name__ == "__main__":
    print("library module; import it", file=sys.stderr)
    sys.exit(2)
