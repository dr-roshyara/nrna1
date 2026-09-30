"""S5a refusal paths: forbidden inputs, non-/tmp outputs, the seal assert at start and end, the pre-reveal hash gate,
the append-only pass record. The seal is monkeypatched in memory (a minimal copy of its state fields); the real seal
file is never modified (its sha256 is compared before and after)."""
import json
import os
import shutil
import sys
import tempfile

import s5a_test_fixtures as F
import p3b_s5_common as C
import p3b_s5a_cells as CELLS
import p3b_s5a_control_availability as AV
import p3b_s5a_controls as K
import p3b_s5a_g13 as G13
import p3b_s5a_generators as G

SEAL = "P3B-HOLDOUT-SEAL.json"
FORBIDDEN = tuple(f + "B0001.jsonl" if f.endswith("/") else f for f in C.FORBIDDEN)   # the common module's list


def _raises(fn, exc=C.S5Error, contains=None):
    try:
        fn()
    except exc as e:
        if contains:
            assert contains in str(e), str(e)
        return e
    raise AssertionError(f"{fn} did not raise {exc}")


class _Patch:
    def __init__(self, obj, name, value):
        self.obj, self.name, self.value = obj, name, value

    def __enter__(self):
        self.old = getattr(self.obj, self.name)
        setattr(self.obj, self.name, self.value)

    def __exit__(self, *a):
        setattr(self.obj, self.name, self.old)


def _fake_seal(state="UNSEALED", seal_id=None):
    real = C.dio.load_seal()
    fake = {"state": state, "seal_id": seal_id or real["seal_id"]}        # a minimal in-memory copy; no lists
    return lambda path=None: dict(fake)


def test_forbidden_inputs_refused():
    for p in FORBIDDEN:
        _raises(lambda p=p: C.check_inputs([p]))
        _raises(lambda p=p: G.load_snapshot_records([p]))
        _raises(lambda p=p: K.check_artifact_path(p))
    _raises(lambda: C.check_inputs(["../../../README.md"]))                            # outside the allowlist
    _raises(lambda: C.check_inputs(["docs/knowledgeos/some-corpus-file.md"]))
    assert K.check_artifact_path("/tmp/s5a-build/x.json") == "/tmp/s5a-build/x.json"   # build scratch allowed


def test_family_md_scoped_to_slice_construction():                                     # G-LOG-0045 item 1
    lab = sorted(C._s5_labels())[0]                                                     # an S5-population label
    fam = f"20-FAMILIES/{lab}.md"
    assert C.check_inputs([fam], purpose=C.FAMILY_MD_PURPOSE)
    _raises(lambda: C.check_inputs([fam]))                                              # no purpose: refused
    _raises(lambda: C.check_inputs([fam], purpose="o22-extract"))                       # another purpose: refused
    _raises(lambda: C.check_inputs(["20-FAMILIES/not-an-s5-label-zz.md"], purpose=C.FAMILY_MD_PURPOSE))


def test_outputs_only_under_tmp():
    _raises(lambda: G.safe_out("audit-p3b/S5A-CELL-RESULTS.json"))
    _raises(lambda: G.safe_out(os.path.join(C.CR, "P3B-CROSS-CANDIDATES.jsonl")))
    assert G.safe_out("/tmp/s5a-build/x.jsonl").startswith("/tmp/")
    _raises(lambda: AV.main(["--out", "audit-p3b/OTHER.json"]))


def test_real_pass_refuses_tmp_artifacts():
    _raises(lambda: G.safe_out("/tmp/s5a-build/P3B-CROSS-CANDIDATES.jsonl", allow_real=True), contains="--real-pass")
    _raises(lambda: K.check_artifact_path("/tmp/s5a-build/S5A-PASS-RECORD.json", real_pass=True), contains="--real-pass")
    _raises(lambda: G.safe_out(os.path.join(C.CR, "NOT-AN-S5-ARTIFACT.json"), allow_real=True))   # not allowlisted
    assert G.safe_out(os.path.join(C.CR, "P3B-CROSS-CANDIDATES.jsonl"), allow_real=True).endswith("P3B-CROSS-CANDIDATES.jsonl")
    tmp = "/tmp/s5a-build/rp"
    _raises(lambda: K.main(["--candidates", tmp + "/c.jsonl", "--blind-out", tmp + "/b.jsonl", "--reveal-out",
                            tmp + "/r.jsonl", "--pass-record", tmp + "/pr.json", "--real-pass"]), contains="--real-pass")
    _raises(lambda: CELLS.main(["reveal", "--pools", tmp + "/p.json", "--pass-record", tmp + "/pr.json", "--reveal",
                                tmp + "/r.jsonl", "--dispositions", tmp + "/d.jsonl", "--candidates", tmp + "/c.jsonl",
                                "--out", tmp + "/o.json", "--real-pass"]), contains="--real-pass")


def test_seal_assert_failure_aborts_every_tool():
    before = C.sha256_file(SEAL)
    tmp = tempfile.mkdtemp(prefix="s5a-refusal-", dir="/tmp")
    try:
        out = os.path.join(tmp, "c.jsonl")
        for fake in (_fake_seal("UNSEALED"), _fake_seal("SEALED", "HS-000000000000")):
            with _Patch(C.dio, "load_seal", fake):
                _raises(lambda: C.assert_sealed(), contains="not SEALED")
                _raises(lambda: G.main(["--out", out]))
                _raises(lambda: K.main(["--candidates", out, "--blind-out", out + ".b", "--reveal-out", out + ".r",
                                        "--pass-record", out + ".pr"]))
                _raises(lambda: CELLS.main(["pools", "--candidates", out, "--out", out + ".p", "--pass-record", out + ".pr"]))
                _raises(lambda: G13.main(["--register", out, "--results", out]))
                _raises(lambda: AV.main(["--out", out + ".av"]))
            assert not any(os.path.exists(out + s) for s in ("", ".b", ".r", ".pr", ".p", ".av"))
    finally:
        shutil.rmtree(tmp)
    assert C.sha256_file(SEAL) == before                                               # the real seal untouched


def test_seal_asserted_at_start_and_end():
    tmp = tempfile.mkdtemp(prefix="s5a-refusal-", dir="/tmp")
    try:
        body = {"artifact": "S5A-CELL-RESULTS.json", "cells": []}
        txt = json.dumps(body, indent=1, sort_keys=True, ensure_ascii=False)
        res = os.path.join(tmp, "r.json")
        K.write_json_artifact(res, {"output_sha256": C.sha256_bytes(txt.encode())}, body)
        reg = os.path.join(tmp, "reg.jsonl")
        open(reg, "w").write("")
        calls = []
        real = C.assert_sealed

        def logging_assert():
            calls.append(1)
            return real()
        with _Patch(C, "assert_sealed", logging_assert):
            assert G13.main(["--register", reg, "--results", res]) == 0
        assert len(calls) == 2
        n = [0]

        def fail_at_end():
            n[0] += 1
            if n[0] == 2:
                raise C.S5Error("seal changed during the run")
            return real()
        with _Patch(C, "assert_sealed", fail_at_end):
            _raises(lambda: G13.main(["--register", reg, "--results", res]), contains="seal changed")
    finally:
        shutil.rmtree(tmp)


def test_reveal_refuses_without_matching_prereveal_hash():
    tmp = tempfile.mkdtemp(prefix="s5a-refusal-", dir="/tmp")
    try:
        pools, pr, pr2, rev, disp, out, cand = (os.path.join(tmp, x) for x in (
            "pools.json", "pr.json", "pr2.json", "rev.jsonl", "d.jsonl", "o.json", "c.jsonl"))
        body = {"artifact": "S5A-POOLS-PREREVEAL.json", "cells": [], "units": {}}
        txt = json.dumps(body, indent=1, sort_keys=True, ensure_ascii=False)
        K.write_json_artifact(pools, {"output_sha256": C.sha256_bytes(txt.encode())}, body)
        m4 = K.m4r1_record([])                                   # the M4-R1 identity every hop must carry (G-LOG-0045)
        G.write_jsonl(rev, {"output_sha256": C.sha256_bytes(b""), "parameters": {"m4_residual": m4}}, [])
        G.write_jsonl(cand, {"output_sha256": C.sha256_bytes(b"")}, [])
        open(disp, "w").write("")
        pre = {"reveal_file_sha256": C.sha256_file(rev), "link3_candidates_file_sha256": C.sha256_file(cand),
               "blinding_level": {}, "m4_residual": m4}
        K.pass_record_append(pr, "PRE-ANALYSIS-BLIND-REVEAL", pre, CELLS.__file__)
        K.pass_record_append(pr, "LINK6-POOLS-PREREVEAL", {"pools_file_sha256": "0" * 64}, CELLS.__file__)
        args = lambda p: ["reveal", "--pools", pools, "--pass-record", p, "--reveal", rev, "--dispositions", disp,
                          "--candidates", cand, "--out", out, "--no-bcdd"]
        _raises(lambda: CELLS.main(args(pr)), contains="REFUSED")
        # the LINK6 entry is frozen once: a second (corrected) entry is refused, so the wrong first one stands
        _raises(lambda: K.pass_record_append(pr, "LINK6-POOLS-PREREVEAL", {"pools_file_sha256": C.sha256_file(pools)},
                                             CELLS.__file__), contains="already frozen")
        # a pass record holding two LINK6 entries (forged around the append guard) is refused at the reveal
        K.pass_record_append(pr2, "PRE-ANALYSIS-BLIND-REVEAL", pre, CELLS.__file__)
        K.pass_record_append(pr2, "LINK6-POOLS-PREREVEAL", {"pools_file_sha256": C.sha256_file(pools), "m4_residual": m4},
                             CELLS.__file__)
        # with one LINK6 entry every hash gate passes; the empty pools then fail the BH-family check (after the gates)
        _raises(lambda: CELLS.main(args(pr2)), contains="BH family")
        # a LINK6 entry without the M4-R1 identity (or with a different one) is refused before any analysis
        pr_m4 = os.path.join(tmp, "pr_m4.json")
        K.pass_record_append(pr_m4, "PRE-ANALYSIS-BLIND-REVEAL", pre, CELLS.__file__)
        K.pass_record_append(pr_m4, "LINK6-POOLS-PREREVEAL", {"pools_file_sha256": C.sha256_file(pools)}, CELLS.__file__)
        _raises(lambda: CELLS.main(args(pr_m4)), contains="M4-R1 binding")
        with _Patch(K, "FROZEN_ONCE", ()):
            K.pass_record_append(pr2, "LINK6-POOLS-PREREVEAL", {"pools_file_sha256": C.sha256_file(pools),
                                                                "m4_residual": m4}, CELLS.__file__)
        _raises(lambda: CELLS.main(args(pr2)), contains="2 LINK6-POOLS-PREREVEAL entries")
        _raises(lambda: K.pass_record_frozen(pr2, "LINK6-POOLS-PREREVEAL"), contains="exactly one")
        # candidates (link 3) altered after its hash was recorded -> refused
        pr3 = os.path.join(tmp, "pr3.json")
        K.pass_record_append(pr3, "PRE-ANALYSIS-BLIND-REVEAL", pre, CELLS.__file__)
        K.pass_record_append(pr3, "LINK6-POOLS-PREREVEAL", {"pools_file_sha256": C.sha256_file(pools)}, CELLS.__file__)
        G.write_jsonl(cand, {"output_sha256": C.sha256_bytes(b"")}, [{"record_type": "CANDIDATE"}])
        _raises(lambda: CELLS.main(args(pr3)), contains="link-3")
        # reveal file altered after its hash was recorded -> refused
        G.write_jsonl(rev, {"output_sha256": C.sha256_bytes(b"")}, [{"unit_key": "U000001", "members": ["a", "b"], "roles": []}])
        _raises(lambda: CELLS.main(args(pr3)), contains="reveal file")
        assert not os.path.exists(out)
        # append-only: tampering with an earlier entry breaks the chain and refuses further appends
        d = json.load(open(pr))
        d["body"]["entries"][0]["payload"]["reveal_file_sha256"] = "f" * 64
        txt = json.dumps(d["body"], indent=1, sort_keys=True, ensure_ascii=False)
        d["header"]["output_sha256"] = C.sha256_bytes(txt.encode())
        json.dump(d, open(pr, "w"), indent=1, sort_keys=True, ensure_ascii=False)
        _raises(lambda: K.pass_record_append(pr, "X", {}, CELLS.__file__), contains="append-only")
    finally:
        shutil.rmtree(tmp)


def test_tooling_never_names_sealed_artifacts():
    """§M item 6 grep: delegated to the H-19 guard itself (no forbidden literals in this test)."""
    import subprocess, sys
    r = subprocess.run([sys.executable, "-B", os.path.join(F.SCRIPTS, "p3b_s5_h19_guard.py"), "grep"],
                       capture_output=True, text=True)
    assert r.returncode == 0, r.stdout[-2000:]


if __name__ == "__main__":
    sys.exit(F.run_all(dict(globals())))
