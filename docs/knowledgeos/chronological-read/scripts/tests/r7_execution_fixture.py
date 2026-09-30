#!/usr/bin/env python3
"""Synthetic R7 batch EXECUTION builder (test helper): given a plan, synthetic source bytes and the artifacts each run
writes, produce the harness transcripts (main + subagents + meta), the run input manifests, the reader output in the R7
self-describing header format, the orchestrator markers and the completion notifications — i.e. a complete, VALID
execution record. Mutation hooks let tests break exactly one thing. No corpus, no network, temp dirs only.
"""
import hashlib
import json
import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
sys.path.insert(0, os.path.dirname(HERE))
import r7_harness_fixture as hf                     # noqa: E402
import p3b_s5_r7_universe as U                      # noqa: E402

r5 = U.r5
READER_ABS = "/repo/docs/knowledgeos/chronological-read/scripts/p3b_read_source.py"
COMMIT = "c0ffee0000000000000000000000000000000000"


def canary(commit, run):
    return hashlib.sha256((commit + run).encode()).hexdigest()[:16]


def binding(run, batch, label, commit=COMMIT):
    return f"S5-RUN-BINDING run={run} batch={batch} label={label} canary={canary(commit, run)}"


def reader_cmd(run, batch, label, sid, k, step=7):
    return f"python3 -B {READER_ABS} --run {run} --batch {batch} --label {label} --step {step} --mode bytes --page {k} {sid}"


def reader_out(run, batch, label, sid, raw, k, inv="0" * 16):
    n = len(r5.byte_pages(raw))
    csha = hashlib.sha256(raw).hexdigest()
    chunk, rec = r5.byte_page_record(sid, raw, k, csha)
    return (f"=== {sid} page {k}/{n} bytes {rec['byte_start']}-{rec['byte_end']} (content sha256 {csha}; page sha256 "
            f"{rec['page_sha256']}) run {run} batch {batch} label {label} inv {inv} ===\n{chunk.decode('utf-8')}\n"
            f"=== END {sid} page {k}/{n} {rec['ack_token']} ===\n"), rec


def put(root, rel, data):
    p = os.path.join(root, rel)
    os.makedirs(os.path.dirname(p), exist_ok=True)
    with open(p, "wb") as f:
        f.write(data if isinstance(data, bytes) else data.encode("utf-8"))
    return p


def build(root, archive, batch, plan, contents, slice_root, run_outputs, hooks=None, steps=None):
    """run_outputs: {run: {relpath: text}} files each run writes (records; for final runs also objects etc.).
    hooks: {run: fn(calls) -> calls} to mutate an agent's calls; hooks["main"]: fn(items) -> items.
    Returns {"manifests", "agents": {run: agent_id}, "dispatch": {run: tool_use_id}, "t": {...}}."""
    hooks = hooks or {}
    steps = steps or {}
    owners = U.run_owner(plan)
    items, agents, dispatch, manifests, t = [], {}, {}, {}, 100.0
    order = []
    for lab, L in plan["labels"].items():
        for run, d in L["runs"].items():
            order.append((0 if d["role"] in ("UNIT", "SINGLE") else 1, lab, run, d["role"]))
    tl = {}
    uv_items = []

    def run_agent(run, lab, role, start):
        nonlocal t
        m = U.input_manifest(root, batch, slice_root, plan, run)
        manifests[run] = m
        calls = []
        slice_rel = f"{slice_root}/{batch}/{lab}.json"
        with open(os.path.join(root, slice_rel), encoding="utf-8") as f:
            calls.append(hf.read(os.path.join(root, slice_rel), hf.cat_n(f.read())))
        if role == "SYNTHESIS":
            for e in m["entries"]:
                if e["category"] == "UNIT-RECORDS":
                    with open(os.path.join(root, e["path"]), encoding="utf-8") as f:
                        calls.append(hf.read(os.path.join(root, e["path"]), hf.cat_n(f.read())))
        else:
            for s in sorted(owners[run][2]):
                raw = contents[s]
                for k in range(1, len(r5.byte_pages(raw)) + 1):
                    out, _ = reader_out(run, batch, lab, s, raw, k)
                    calls.append(hf.bash(reader_cmd(run, batch, lab, s, k, steps.get((run, s), 7)), out))
        for rel, text in sorted((run_outputs.get(run) or {}).items()):
            put(root, rel, text)
            calls.append(hf.write(os.path.join(root, rel), text))
        calls.append(hf.handback("done"))
        if run in hooks:
            calls = hooks[run](calls)
        aid = "a" + hashlib.sha256(run.encode()).hexdigest()[:16]
        did = "toolu_" + hashlib.sha256(("d" + run).encode()).hexdigest()[:20]
        agents[run], dispatch[run] = aid, did
        items.append(hf.main_dispatch(did, binding(run, batch, lab) + "\nRead only your inputs; follow the R7 contract.", start))
        hf.write_agent(archive, aid, did, calls, prompt=binding(run, batch, lab), start=start + 1)
        end = hf.agent_last_t(calls, start + 1)
        n_uses = sum(1 for c in calls)
        items.append(hf.main_notification(aid, did, n_uses, t=end + 0.5))
        tl[run] = (start, end)
        return end
    # reading runs first
    end_units = {}
    for _, lab, run, role in sorted(x for x in order if x[0] == 0):
        e = run_agent(run, lab, role, t)
        end_units.setdefault(lab, []).append(e)
        t = e + 2
    # units validated (DECOMPOSED labels), then synthesis
    for _, lab, run, role in sorted(x for x in order if x[0] == 1):
        units = [u for u, d in plan["labels"][lab]["runs"].items() if d["role"] == "UNIT"]
        printed = "".join(f"{U.fsha(os.path.join(root, U.LEDGER, u, 'file-reading-records.jsonl'))}  "
                          f"{U.LEDGER}/{u}/file-reading-records.jsonl\n" for u in sorted(units))
        items.append(hf.main_marker(f": S5-ORCH UNITS-VALIDATED batch={batch} label={lab}; sha256sum ...", printed, t))
        t += 3
        e = run_agent(run, lab, role, t)
        t = e + 2
    items.append(hf.main_marker(f": S5-ORCH ASSEMBLED batch={batch}; ls", "ok", t))
    t += 2
    items.append(hf.main_marker(f": S5-ORCH FINAL-VALIDATED batch={batch}; true", "ok", t))
    if "main" in hooks:
        items = hooks["main"](items)
    items.sort(key=lambda it: it["t"])
    main = hf.write_main(archive, items)
    return {"manifests": manifests, "agents": agents, "dispatch": dispatch, "times": tl, "main": main}
