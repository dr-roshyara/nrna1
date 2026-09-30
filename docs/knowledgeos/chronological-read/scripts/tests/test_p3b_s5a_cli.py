"""S5a CLI chain end to end in /tmp: generators -> controls (blind, reveal, pass record) -> cells pools -> cells reveal
-> G-13, with the data loaders monkeypatched to the synthetic fixture (no repository research data, no dispositions
from any real analysis). Also checks determinism of the written artifacts (output_sha256) across two runs."""
import json
import os
import random
import shutil
import sys
import tempfile

import s5a_test_fixtures as F
import p3b_s5_common as C
import p3b_s5a_cells as CELLS
import p3b_s5a_controls as K
import p3b_s5a_g13 as G13
import p3b_s5a_generators as G
from test_p3b_s5a_refusal import _Patch, _raises


def _chain(tmp):
    ctx = F.synthetic_ctx()
    p = lambda x: os.path.join(tmp, x)
    with _Patch(G.Ctx, "load", classmethod(lambda cls: ctx)), \
            _Patch(C, "hubs", lambda: {h: {} for h in ctx.hubs}), \
            _Patch(C, "discovery_pairs", lambda: [{"a": sorted(j)[0], "b": sorted(j)[1]} for j in ctx.judged]), \
            _Patch(C, "label_bands", lambda pop=None: ctx.bands):
        with open(p("P3B-PASS-SNAPSHOT.json"), "w") as f:
            json.dump({"synthetic": True}, f)
        assert G.main(["--out", p("cand.jsonl")]) == 0
        assert K.main(["--candidates", p("cand.jsonl"), "--blind-out", p("blind.jsonl"), "--reveal-out", p("reveal.jsonl"),
                       "--pass-record", p("pr.json"), "--pass-snapshot", p("P3B-PASS-SNAPSHOT.json")]) == 0
        assert CELLS.main(["pools", "--candidates", p("cand.jsonl"), "--out", p("pools.json"), "--pass-record", p("pr.json")]) == 0
        _, blind = G.read_jsonl_artifact(p("blind.jsonl"))
        rnd = random.Random(4)
        with open(p("disp.jsonl"), "w") as f:
            for b in blind:
                f.write(json.dumps({"unit_key": b["unit_key"], "disposition": "ANALYSED",
                                    "classes": {c: rnd.choice(["RECORDED", "NOT-RECORDED"]) for c in CELLS.CLASSES}}) + "\n")
        assert CELLS.main(["reveal", "--pools", p("pools.json"), "--pass-record", p("pr.json"), "--reveal", p("reveal.jsonl"),
                           "--dispositions", p("disp.jsonl"), "--candidates", p("cand.jsonl"),
                           "--pass-snapshot", p("P3B-PASS-SNAPSHOT.json"), "--out", p("results.json"), "--no-bcdd"]) == 0
        h, body = K.read_json_artifact(p("results.json"))
        for link in ("P3B-PASS-SNAPSHOT.json", "cand.jsonl", "pr.json", "pools.json", "reveal.jsonl", "disp.jsonl"):
            assert p(link) in h["input_hashes"], link                                    # links 1-3, 6, 7 hashed
        cell = body["cells"][0]
        rec = {"rs_id": "R1", "scale": "CROSS-OBJECT",
               "participating_objects": [{"working_label": "lab-01", "source_evidence": [{"source_id": "S0001", "anchor": "q"}],
                                          "acceptance_state": "PROPOSED", "tier": "U"}],
               "derived_from_records": ["x"], "common_structure": "x", "differences": [], "competing_explanation": "y",
               "disconfirmation": {"A": 1}, "falsification_condition": "z", "temporal_scope": "t",
               "generator_basis": {"generator": cell["generator"], "basis": "DISCOVERY"}, "structure_class": cell["class"],
               "control_comparison": {"cell_id": cell["cell_id"], "results_sha256": h["output_sha256"],
                                      "outcome": cell["outcome"]}, "research_status": "ANALYSED"}
        with open(p("reg.jsonl"), "w") as f:
            f.write(json.dumps(rec) + "\n")
        assert G13.main(["--register", p("reg.jsonl"), "--results", p("results.json")]) == 0
        rec["control_comparison"]["results_sha256"] = "0" * 64
        with open(p("reg.jsonl"), "w") as f:
            f.write(json.dumps(rec) + "\n")
        assert G13.main(["--register", p("reg.jsonl"), "--results", p("results.json")]) == 1
    hashes = {}
    for x in ("cand.jsonl", "blind.jsonl", "reveal.jsonl"):
        hashes[x] = G.read_jsonl_artifact(p(x))[0]["output_sha256"]
    hashes["pools.json"] = K.read_json_artifact(p("pools.json"))[0]["output_sha256"]
    # the results body copies the link-6 FILE hash (header timestamp included), so compare it without that one field
    rb = dict(K.read_json_artifact(p("results.json"))[1])
    rb.pop("link6_pools_sha256")
    hashes["results.json (minus link6 file hash)"] = C.sha256_bytes(json.dumps(rb, sort_keys=True).encode())
    _, pr = K.read_json_artifact(p("pr.json"))
    assert [e["kind"] for e in pr["entries"]] == ["PRE-ANALYSIS-BLIND-REVEAL", "LINK6-POOLS-PREREVEAL"]
    assert pr["entries"][1]["payload"]["pools_file_sha256"] == C.sha256_file(p("pools.json"))
    assert body["link6_pools_sha256"] == C.sha256_file(p("pools.json")) and body["K"] == 64
    return hashes


def test_m4r1_binding_blind_header_and_pass_record():
    """Human ruling A (R1 + cap 1), provenance: the blind header, the reveal header and the PRE-ANALYSIS and LINK6
    pass-record entries carry one withheld-set identity (count + canonical sha256, rule, cap); checks are hard
    failures; no withheld member label is written to the blind header or the pass record."""
    tmp = tempfile.mkdtemp(prefix="s5a-m4r1-", dir="/tmp")
    tmp2 = tempfile.mkdtemp(prefix="s5a-m4r1-", dir="/tmp")
    try:
        _chain(tmp)
        _chain(tmp2)
        p = lambda d, x: os.path.join(d, x)
        hb, _ = G.read_jsonl_artifact(p(tmp, "blind.jsonl"))
        hr, _ = G.read_jsonl_artifact(p(tmp, "reveal.jsonl"))
        _, pr = K.read_json_artifact(p(tmp, "pr.json"))
        pre, link6 = pr["entries"][0]["payload"]["m4_residual"], pr["entries"][1]["payload"]["m4_residual"]
        rec = hb["parameters"]["m4_residual"]
        # (4)(5)(6) header and pass-record count/hash, all equal; approved rule and cap
        assert rec == hr["parameters"]["m4_residual"] == pre == link6
        assert rec["rule"] == "R1 + cap 1" and rec["cap"] == 1 and rec["withheld_units"] > 0
        assert rec["scope"] == "all candidate/control units across all generators"
        assert K.check_m4r1_binding(rec, pre, link6) and K.verify_m4r1_binding(p(tmp, "blind.jsonl"), p(tmp, "pr.json"))
        # (1)(2) deterministic set and hash across two independent runs
        assert G.read_jsonl_artifact(p(tmp2, "blind.jsonl"))[0]["parameters"]["m4_residual"] == rec
        ctx = F.synthetic_ctx()
        _, cands = G.read_jsonl_artifact(p(tmp, "cand.jsonl"))
        cand = [r for r in cands if r["record_type"] == "CANDIDATE"]
        ctrl = [r for r in cands if r["record_type"] in ("CONTROL", "CONTROL-DRAW")]
        w1, w2 = K.withheld_sets(ctx, cand, ctrl), K.withheld_sets(ctx, cand, ctrl)
        assert w1 == w2 and K.m4r1_record(w1) == rec
        # (3) candidate/control symmetry: the rule reads members and evidence only, never role
        flipped = [dict(r, record_type="CONTROL", control_id="x" + r["candidate_id"], for_candidate=r["candidate_id"])
                   for r in cand if r.get("in_budget") and not r.get("self_derived")]
        assert set(K.withheld_sets(ctx, [], flipped)) >= {m for m in w1 if any(tuple(r["members"]) == m for r in flipped)}
        # (7) tampering with the set changes the hash and fails the binding
        tampered = K.m4r1_record(w1[1:])
        assert tampered["withheld_sets_sha256"] != rec["withheld_sets_sha256"]
        _raises(lambda: K.check_m4r1_binding(rec, tampered))
        _raises(lambda: K.check_m4r1_binding(rec, dict(rec, withheld_sets_sha256="0" * 64)))
        # (8) a changed cap changes the recorded identity and cannot pass
        with _Patch(K, "M4R1_CAP", 2):
            capped2 = K.m4r1_record(w1)
        assert capped2["cap"] == 2 and capped2["rule"] == "R1 + cap 2"
        _raises(lambda: K.check_m4r1_binding(rec, capped2))
        # (9) a changed rule identifier cannot pass silently
        with _Patch(K, "M4R1_WITHHOLD", False):
            no_r1 = K.m4r1_record(w1)
        _raises(lambda: K.check_m4r1_binding(rec, no_r1))
        _raises(lambda: K.check_m4r1_binding(rec, dict(rec, rule="R1+cap 1")))
        _raises(lambda: K.check_m4r1_binding(rec, {k: v for k, v in rec.items() if k != "canonicalization"}))
        _raises(lambda: K.check_m4r1_binding(rec))
        # a scope change (e.g. the rejected G-SHARED-GROUP-only scope) and a boolean cap cannot pass the approval check
        with _Patch(K, "M4R1_SCOPE", "G-SHARED-GROUP candidate/control units"):          # the rejected scope
            rescoped = K.m4r1_record(w1)
        _raises(lambda: K.check_m4r1_binding(rec, rescoped))
        _raises(lambda: K.check_m4r1_binding(rec, dict(rec, cap=True)))
        # a blind file altered after the pass record was written is refused
        with open(p(tmp, "blind.jsonl"), "a") as f:
            f.write("\n")
        _raises(lambda: K.verify_m4r1_binding(p(tmp, "blind.jsonl"), p(tmp, "pr.json")))
        # (10) no withheld member label leaks into the blind header or the pass record
        labels = {lab for m in w1 for lab in m}
        text = json.dumps(hb) + json.dumps(hr) + open(p(tmp, "pr.json")).read()
        leaked = [lab for lab in labels if f'"{lab}"' in text]
        assert not leaked, leaked
    finally:
        shutil.rmtree(tmp, ignore_errors=True)
        shutil.rmtree(tmp2, ignore_errors=True)


def test_prerelease_blinding_gate():
    """Registered pre-release gate (G-LOG-0045): runs on the written blind/reveal/pools bound to the pass record,
    threshold 0.55 over testable generators, writes the gate file and a frozen pass-record entry; refuses altered
    inputs; a second gate entry is refused."""
    import p3b_s5a_m4_residual as M
    tmp = tempfile.mkdtemp(prefix="s5a-gate-", dir="/tmp")
    tmp2 = tempfile.mkdtemp(prefix="s5a-gate-", dir="/tmp")
    try:
        _chain(tmp)
        p = lambda x: os.path.join(tmp, x)
        args = ["--blind", p("blind.jsonl"), "--reveal", p("reveal.jsonl"), "--pools", p("pools.json"),
                "--pass-record", p("pr.json"), "--out", p("gate.json")]
        rc = M.gate_main(args)
        h, body = K.read_json_artifact(p("gate.json"))
        assert body["threshold"] == 0.55 and body["result"] in ("PASS", "FAIL") and rc == (0 if body["result"] == "PASS" else 1)
        for g in body["testable_generators"]:
            v = body["generators"][g]
            assert v["blinding_level"] == "FULL" and v["max_pool_m"] >= 20
        assert body["result"] == ("PASS" if all(body["generators"][g]["best_auc"] <= 0.55 for g in body["testable_generators"])
                                  else "FAIL")
        _, pr = K.read_json_artifact(p("pr.json"))
        assert pr["entries"][-1]["kind"] == "PRE-RELEASE-BLINDING-GATE"
        assert pr["entries"][-1]["payload"]["gate_file_sha256"] == C.sha256_file(p("gate.json"))
        _raises(lambda: M.gate_main(args[:-1] + [p("gate2.json")]), contains="already frozen")     # runs once
        assert not os.path.exists(p("gate2.json"))                                                 # refused up front
        assert M.GATE_THRESHOLD == 0.55                                   # a registered constant, not a parameter
        # a blind file altered after the pass record was written is refused (fresh pass, gate not yet run)
        _chain(tmp2)
        with open(os.path.join(tmp2, "blind.jsonl"), "a") as f:
            f.write("\n")
        args2 = [x.replace(tmp, tmp2) for x in args]
        _raises(lambda: M.gate_main(args2), contains="does not match the pass record")
    finally:
        shutil.rmtree(tmp, ignore_errors=True)
        shutil.rmtree(tmp2, ignore_errors=True)


def test_cli_chain_and_determinism():
    t1 = tempfile.mkdtemp(prefix="s5a-cli-", dir="/tmp")
    t2 = tempfile.mkdtemp(prefix="s5a-cli-", dir="/tmp")
    try:
        a, b = _chain(t1), _chain(t2)
        assert a == b, (a, b)
        print("   cli output_sha256: " + ", ".join(f"{k}={v[:12]}" for k, v in sorted(a.items())))
    finally:
        shutil.rmtree(t1)
        shutil.rmtree(t2)


if __name__ == "__main__":
    sys.exit(F.run_all(dict(globals())))
