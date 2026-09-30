#!/usr/bin/env python3
"""Read-only S5 canary pre-check under contract v2.8 (OB0012 + OB0114). Writes only into temporary copies under the
directory given as argv[1] (outside the repository). Prints counts and hashes only (no label names, no S-ids).
  cd docs/knowledgeos/chronological-read && python3 -B audit-p3b/impl/canary_precheck_v28.py <scratch dir>
"""
import json
import os
import shutil
import subprocess
import sys
import tempfile

CRD = os.getcwd()
sys.path.insert(0, os.path.join(CRD, "scripts"))
import p3b_s5_r7_orchestrate as O          # noqa: E402
import p3b_s5_ops_lib as ops                # noqa: E402

U, W, V, c = O.U, O.W, O.V, O.c
stm = ops.load_script("p3b_s5_state")
SP = sys.argv[1]
CANARY = ["OB0012", "OB0114"]
st = stm.load()
stm.verify_chain(st)
mpath = os.path.join(O.CR, "_batch_manifest_p3b_r2.jsonl")
mh = json.loads(open(mpath, encoding="utf-8").readline())["header"]
out = {"manifest_sha256": U.fsha(mpath), "state_bound": st["manifest_sha256"], "contract": mh["contract"],
       "revision_violations": U.revision_violations(mh),
       "addendum_file_matches_frozen": U.fsha(os.path.join(O.CR, U.ADDENDUM_PATH)) == U.ADDENDUM_SHA256,
       "reader_is_repo_reader": os.path.samefile(mh["r7_reader_abs"], os.path.join(O.CR, "scripts", "p3b_read_source.py")),
       "activation_commit_exists": subprocess.run(["git", "cat-file", "-e", mh["r7_activation_commit"] + "^{commit}"],
                                                  cwd=O.CR).returncode == 0,
       "wsys": {k: v for k, v in stm.wsys_status(st).items() if k in ("n", "f", "stop")},
       "batches": {}}
total_runs, prompt_sha = 0, {}
for B in CANARY:
    root = tempfile.mkdtemp(prefix=f"canary28-{B}-", dir=SP)
    _, e, _ = V.load_manifest_entry(O.CR, B)
    sr = mh["slice_root"]
    for rel in ["_batch_manifest_p3b_r2.jsonl", U.REV3_CONTRACT_PATH, U.ADDENDUM_PATH, f"{sr}/{B}.R7-PLAN.json"] + \
            [f"{sr}/{B}/{lab}.json" for lab in e["labels"]]:
        os.makedirs(os.path.dirname(os.path.join(root, rel)), exist_ok=True)
        shutil.copyfile(os.path.join(O.CR, rel), os.path.join(root, rel))
    r = O.prepare(B, root=root, emit=tempfile.mkdtemp(prefix=f"emit28-{B}-", dir=SP),
                  state_path=os.path.join(O.CR, "P3B-STATE.json"))
    ctx = O._context(B, root)
    plan, owners = ctx["plan"], ctx["owners"]
    ms = json.load(open(os.path.join(ctx["adir"], "INPUT-MANIFESTS.json")))
    roles, record_ids, hub = {}, 0, 0
    for run, (lab, role, _) in sorted(owners.items()):
        roles[role] = roles.get(role, 0) + 1
        m = ms.get(run) or U.input_manifest(root, B, sr, plan, run)
        p = O.render_prompt(run, B, plan, m, mh["r7_reader_abs"], mh["r7_activation_commit"], O.CR,
                            slice_text=ctx["slices"][lab])
        prompt_sha[run] = U.sha(p.encode())
        record_ids += ("RECORD IDS" in p) if role in ("SINGLE", "SYNTHESIS") else ("RECORD IDS" not in p)
        hub += "REQUIRED LINES" in p
    empties = [lab for lab, L in plan["labels"].items() if L["path"] == "EMPTY"]
    empty_ok = 0
    for lab in empties:
        sl = json.loads(ctx["slices"][lab])
        obj = O.empty_object(B, lab, e, sl, "2026-10-01", c.MODEL_ID, U.fsha(os.path.join(O.CR, "scripts", "p3b_s5_r7_orchestrate.py")))
        reg, _cons, _f = U.load_frozen_contract(O.CR)
        empty_ok += not O.self_check_violations(obj, B, reg) and not any(c.quarantine_hits(json.dumps(obj)))
    B_ = st["batches"][B]
    total_runs += len(owners)
    out["batches"][B] = {
        "state": (B_["state"], B_["run_id"], B_.get("revision"), stm.current_attempt(st, B)),
        "hub_batch": e.get("hub_batch"), "labels": len(e["labels"]), "runs": len(owners), "runs_by_role": roles,
        "view_violations": sum(len(U.slice_view_violations(ctx["slices"][lab], open(os.path.join(root, sr, B, f"{lab}.view.txt"),
                                                                                 encoding="utf-8").read(), lab)) for lab in ctx["slices"]),
        "reading_manifests": len(ms),
        "manifest_violations": sum(len(U.input_manifest_violations(m, plan, B, sr)) + sum(x["sha256"] is None for x in m["entries"])
                                   for m in ms.values()),
        "prompts_record_ids_ok": record_ids, "prompts_with_hub_required_lines": hub,
        "empty_labels": len(empties), "empty_objects_self_check_ok": empty_ok,
        "may_start_attempt": O.may_start_attempt(B)[0],
        "prompt_bundle_sha256": U.sha(U.canon({k: v for k, v in sorted(prompt_sha.items()) if k.startswith(B)}))}
out["total_runs"] = total_runs
out["tool_sha256_frozen_for_canary"] = {n: U.fsha(os.path.join(O.CR, "scripts", n)) for n in (
    "p3b_s5_r7_orchestrate.py", "p3b_s5_r7_witness.py", "p3b_s5_r7_verify.py", "p3b_s5_verify.py", "p3b_read_source.py",
    "p3b_s5_r7_universe.py", "p3b_s5_state.py", "p3b_s5_audit_record.py", "p3b_s5_accept.py", "p3b_s5_common.py")}
out["prompt_sha256"] = prompt_sha
txt = json.dumps(out, indent=1, sort_keys=True, ensure_ascii=False, default=list)
assert not any(c.quarantine_hits(txt)), "report failed the quarantine scan"
c.assert_sealed()
print(txt)
