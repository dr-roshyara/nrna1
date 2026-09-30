"""Shared fixtures for the S5 operations tests (scripts/tests/test_p3b_s5_ops_*.py).

Everything hold-out-shaped here is SYNTHETIC: the fake labels and S-ids below are not in the census. The real seal is
never modified and never copied with its lists; seal-failure paths use in-memory patches or a temp seal file whose lists
are synthetic. Scratch lives under /tmp/s5ops-build/.
"""
import contextlib
import json
import os
import sys
import tempfile

TESTS = os.path.dirname(os.path.abspath(__file__))
SCRIPTS = os.path.dirname(TESTS)
sys.path.insert(0, SCRIPTS)
import p3b_s5_ops_lib as ops  # noqa: E402

c = ops.load_common()
SCRATCH = "/tmp/s5ops-build"

FAKE_H = frozenset({"zz-fake-holdout-alpha", "zz-fake-holdout.beta"})
FAKE_HF = frozenset({"S9990", "S9991"})
FAKE_MENTIONS = {"S9991": frozenset({"disc-label-x"})}
FAKE_SETS = (FAKE_H, FAKE_HF, FAKE_MENTIONS)


def tmpdir(prefix):
    os.makedirs(SCRATCH, exist_ok=True)
    return tempfile.mkdtemp(prefix=prefix + "-", dir=SCRATCH)


@contextlib.contextmanager
def fake_holdout():
    """Replace the common hold-out accessor by the synthetic sets (restored afterwards)."""
    orig = c.holdout_sets
    c.holdout_sets = lambda: FAKE_SETS
    try:
        yield FAKE_SETS
    finally:
        c.holdout_sets = orig


@contextlib.contextmanager
def seal_state(state):
    """In-memory patch: the seal as loaded reads `state` (the real file is untouched)."""
    orig = c.dio.load_seal

    def patched(path=None):
        s = dict(orig(path))
        s["state"] = state
        return s
    c.dio.load_seal = patched
    try:
        yield
    finally:
        c.dio.load_seal = orig


@contextlib.contextmanager
def temp_seal_copy(state):
    """A temp seal file (synthetic lists, given state) served by a patched loader; the real seal is untouched."""
    d = tmpdir("seal")
    p = os.path.join(d, "P3B-HOLDOUT-SEAL.json")
    with open(p, "w", encoding="utf-8") as f:
        json.dump({"state": state, "seal_id": c.SEAL_ID, "holdout_labels": sorted(FAKE_H),
                   "holdout_files": sorted(FAKE_HF), "hf_sid_mentions_in_discovery_bundles": {}}, f)
    orig = c.dio.load_seal
    c.dio.load_seal = lambda path=None: json.load(open(p, encoding="utf-8"))
    try:
        yield p
    finally:
        c.dio.load_seal = orig


def write_jsonl(path, recs):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "w", encoding="utf-8") as f:
        for r in recs:
            f.write(json.dumps(r, sort_keys=True) + "\n")


def write_manifest(path, batch_ids):
    write_jsonl(path, [{"header": {"artifact": "_batch_manifest_p3b_r2.jsonl", "fixture": True}}] +
                [{"batch_id": b, "labels": [f"lab-{b}-{i}" for i in range(3)]} for b in batch_ids])


def read(path):
    with open(path, encoding="utf-8") as f:
        return f.read()


def read_jsonl(path):
    return [json.loads(line) for line in read(path).split("\n") if line.strip()]


def write_report(dirpath, name, batch, run, result="PASS", comparison=False, header=True, script=None, bad_hash=False):
    """A synthetic S5 verify / audit report with a §19.5-style header whose script_name is the producing tool
    (verify for VERIFIED evidence, audit_record for AUDITED evidence, derived from the file name unless given) and
    whose output_sha256 is the sha256 of the canonical body, as the state tool now requires (G-LOG-0042)."""
    os.makedirs(dirpath, exist_ok=True)
    p = os.path.join(dirpath, name)
    doc = {"body": {"batch_id": batch, "run_id": run, "result": result, "comparison": comparison}}
    if header:
        if script is None:
            script = "scripts/p3b_s5_audit_record.py" if "AUDITED" in name else "scripts/p3b_s5_verify.py"
        digest = c.sha256_bytes(c.canon(doc["body"]).encode("utf-8"))
        doc["header"] = {"script_name": script, "output_sha256": "0" * 64 if bad_hash else digest}
    with open(p, "w", encoding="utf-8") as f:
        json.dump(doc, f)
    return p
