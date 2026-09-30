#!/usr/bin/env python3
"""R7 activation — DRY RUN (G-LOG-0101; R7 addendum v2.6 §15 item 7; ORD-1 (b), G-LOG-0097).

Builds, OUTSIDE the repository only, the complete R7 production state and re-derives the strata:
  - one frozen plan per batch: U.plan_derive_v7(batch, labels, slices, sizes, files_meta, binary decisions), with the
    decisions from the hash-bound governed artifact; sizes are METADATA byte lengths (r5.all_blob_sizes: git object sizes
    + preserved-audit-copy sizes) — no corpus byte is read;
  - the R7 entry fields per batch: run_id <batch>-R7, r7_plan_sha256, legacy_ledger_sha256 (U.legacy_digest), legacy_dirs;
  - the R7 header proposal: contract revision 7 (addendum path + sha), binary-decisions sha + authority, reader path,
    activation commit (HEAD);
  - the strata of the frozen plans (S.frame_attributes / S.assign), compared with the approved Freeze 2 strata.
Never writes the production manifest, the production slice root or any ledger. Deterministic; no ML; no seed.
  cd scripts && python3 -B p3b_s5_r7_activate.py --dry-run --slices <materialize-all dir> --emit <dir outside the repo>
"""
import argparse
import collections
import importlib.util
import json
import os
import subprocess
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
CR = os.path.dirname(HERE)
sys.path.insert(0, HERE)


def _load(name):
    s = importlib.util.spec_from_file_location(name, os.path.join(HERE, name + ".py"))
    m = importlib.util.module_from_spec(s)
    s.loader.exec_module(m)
    return m


c = _load("p3b_s5_common")
U = _load("p3b_s5_r7_universe")
S = _load("p3b_s5_r7_stats")
P = _load("p3b_s5_prepare")
r5 = U.r5
APPROVED = {"HUB": 66, "EMPTY": 7, "MULTIROW": 4, "DECOMPOSED": 173, "SINGLE": 1725}     # Freeze 2 proposal (G-LOG-0097)
AUTHORITY = "G-LOG-0096"


class _Sized:
    """A stand-in for file bytes that only carries the length (plan derivation uses len() alone)."""
    __slots__ = ("n",)

    def __init__(self, n):
        self.n = n

    def __len__(self):
        return self.n


class SizedMap(dict):
    def __init__(self, sizes):
        super().__init__()
        self.sizes = sizes

    def __missing__(self, s):
        if s not in self.sizes:
            raise KeyError(f"unsized required file {s} (a missing size is an error, never a silent zero)")
        return _Sized(self.sizes[s])


R7_SLICE_ROOT = "_batch_input_r2/s5/rev7"                                   # G-LOG-0102
ARCHIVE = os.path.expanduser("~/knowledgeos-witness-archive")                 # FD-4′


def r7_slice_text(t3, bid):
    """The validated R7 transformation of a revision-3 slice (as the R7 fixture): the execution identity B-R2 → B-R7."""
    return t3.replace(f'"{bid}-R2"', f'"{bid}-R7"').replace(f"{bid}-R2:", f"{bid}-R7:")


def write(slices_dir, reason, staging):
    """PRODUCTION activation (G-LOG-0102): stage everything under the repository, verify it independently from disk, then
    move it into place, write the R7 manifest and run the AG-2 state transition. STOPS (nothing moved) on any failure."""
    st_mod = _load("p3b_s5_state")
    man_path = os.path.join(CR, P.MANIFEST)
    lines = open(man_path, encoding="utf-8").read().split("\n")
    hdr = json.loads(lines[0])["header"]
    entries = [json.loads(l) for l in lines[1:] if l]
    if hdr["contract"].get("revision") != 3 or "rev3_base" in hdr:
        raise SystemExit("REFUSED: the production manifest is not the revision-3 base (already activated?)")
    final = os.path.join(CR, R7_SLICE_ROOT)
    if os.path.abspath(staging).startswith(c.REPO_ROOT + os.sep):
        raise SystemExit("REFUSED: staging must be outside the repository (a failed staging leaves nothing in the repo)")
    if os.path.exists(final) or os.path.exists(staging):
        raise SystemExit("REFUSED: the rev7 slice root or the staging directory already exists")
    chk = subprocess.run([sys.executable, "-B", os.path.join(HERE, "p3b_s5_prepare.py"), "--check"], capture_output=True, text=True)
    if "IDENTICAL" not in chk.stdout:
        raise SystemExit(f"REFUSED: prepare --check is not IDENTICAL before activation: {chk.stdout.strip()[:120]}")
    st = st_mod.load()
    if st["manifest_sha256"] != U.sha(open(man_path, "rb").read()):
        raise SystemExit("REFUSED: P3B-STATE is not bound to the current production manifest")
    raw = open(os.path.join(CR, U.BINARY_DECISIONS_PATH), "rb").read()
    dec, recs, F = U.binary_decisions(raw, U.sha(raw), AUTHORITY)
    if F:
        raise SystemExit(f"REFUSED: binary decisions invalid: {F[:3]}")
    man = [r for r in c.jl("P3B-IDENTITY-MANIFEST.jsonl") if "source_id" in r]
    sizes = r5.all_blob_sizes(man, c.REPO_ROOT, P.blob_sizes())
    fm = c.files_meta()
    ledger = os.path.join(CR, U.LEDGER)
    # ---------------------------------------------------------------- 1. stage slices + plans
    new_entries = []
    for e in entries:
        bid = e["batch_id"]
        d = os.path.join(staging, bid)
        os.makedirs(d)
        s7 = {}
        slices = {}
        for lab in e["labels"]:
            t3 = open(os.path.join(slices_dir, bid, f"{lab}.json"), encoding="utf-8").read().rstrip("\n")
            if U.sha(t3.encode("utf-8")) != e["slice_sha256"][lab]:
                raise SystemExit(f"STOP: {bid}/{lab}: the revision-3 slice ≠ the manifest hash")
            t7 = r7_slice_text(t3, bid)
            sl = json.loads(t7)
            if sl.get("run_id") != f"{bid}-R7" or sl.get("batch_id") != bid or sl.get("working_label") != lab:
                raise SystemExit(f"STOP: {bid}/{lab}: the R7 slice identity is wrong")
            with open(os.path.join(d, f"{lab}.json"), "w", encoding="utf-8") as f:
                f.write(t7 + "\n")
            s7[lab], slices[lab] = U.sha(t7.encode("utf-8")), sl
        plan = U.plan_derive_v7(bid, e["labels"], slices, SizedMap(sizes), fm, dec)
        body = U.canon(plan)
        with open(os.path.join(staging, f"{bid}.R7-PLAN.json"), "wb") as f:
            f.write(body)
        planned = set(U.run_owner(plan))
        legacy_dirs = sorted(x for x in (os.listdir(ledger) if os.path.isdir(ledger) else [])
                             if x.startswith(bid + "-") and x not in planned and x != f"{bid}-R7")
        new_entries.append(dict(e, run_id=f"{bid}-R7", slice_sha256=s7, rev3_slice_sha256=e["slice_sha256"],
                                r7_plan_sha256=U.sha(body), legacy_ledger_sha256=U.legacy_digest(ledger, bid, planned, f"{bid}-R7"),
                                legacy_dirs=legacy_dirs))
    head = subprocess.run(["git", "-C", CR, "rev-parse", "HEAD"], capture_output=True, text=True).stdout.strip()
    body_txt = "\n".join(U.canon(x).decode() for x in new_entries) + "\n"
    r7h = dict(hdr, contract={"revision": 7, "sha256": U.ADDENDUM_SHA256, "path": U.ADDENDUM_PATH},
               slice_root=R7_SLICE_ROOT, output_sha256=U.sha(body_txt.encode()),
               rev3_base={"contract": hdr["contract"], "output_sha256": hdr["output_sha256"], "slice_root": hdr.get("slice_root")},
               r7_reader_abs=os.path.join(HERE, "p3b_read_source.py"), r7_activation_commit=head, r7_archive=ARCHIVE,
               r7_binary_decisions_sha256=U.sha(raw), r7_binary_decisions_authority=AUTHORITY,
               r7_activation={"authority": "G-LOG-0102", "tool_sha256": U.sha(open(__file__, "rb").read())})
    man_txt = U.canon({"header": r7h}).decode() + "\n" + body_txt
    if any(c.quarantine_hits(man_txt)):
        raise SystemExit("STOP: the R7 manifest failed the quarantine scan")
    # ---------------------------------------------------------------- 2. independent verification from disk
    frame, n_labels, dec_files = {}, 0, set()
    for e in new_entries:
        bid = e["batch_id"]
        sl = {}
        for lab in e["labels"]:
            t = open(os.path.join(staging, bid, f"{lab}.json"), encoding="utf-8").read().rstrip("\n")
            if U.sha(t.encode("utf-8")) != e["slice_sha256"][lab]:
                raise SystemExit(f"STOP: staged slice {bid}/{lab} ≠ its manifest hash")
            sl[lab] = json.loads(t)
        pb = open(os.path.join(staging, f"{bid}.R7-PLAN.json"), "rb").read()
        re_plan = U.plan_derive_v7(bid, e["labels"], sl, SizedMap(sizes), fm, dec)
        if U.sha(pb) != e["r7_plan_sha256"] or U.canon(re_plan) != pb:
            raise SystemExit(f"STOP: staged plan {bid} ≠ its hash or its re-derivation")
        if U.plan_violations(json.loads(pb), re_plan, e["r7_plan_sha256"]):
            raise SystemExit(f"STOP: plan violations for {bid}")
        planned = set(U.run_owner(re_plan))
        if U.legacy_digest(ledger, bid, planned, f"{bid}-R7") != e["legacy_ledger_sha256"]:
            raise SystemExit(f"STOP: legacy digest mismatch for {bid}")
        for lab, L in re_plan["labels"].items():
            frame[lab] = S.frame_attributes({"labels": {lab: L}}, sl)[lab]
            dec_files |= set(L.get("binary_decisions") or {})
        n_labels += len(e["labels"])
    strata = {h: len(v) for h, v in S.assign(frame).items()}
    checks = {"batches": len(new_entries) == 396, "labels": n_labels == len(frame) == 1975, "strata": strata == APPROVED,
              "frame_sha256": S.sha(S.canon(sorted(frame))) == "d0c972bf53207dc581cf094298d273209e833ca009351af5f083912329857d0d",
              "binary_decisions": dec_files == set(dec), "revision_binding": not U.revision_violations(r7h),
              "base_consistency": not P.r7_base_consistency(entries, new_entries)}
    if not all(checks.values()):
        raise SystemExit(f"STOP: staging verification failed: {checks} strata={strata}")
    # ---------------------------------------------------------------- 3. production write + AG-2 state transition
    import shutil
    prev_copy = os.path.join(staging, "previous-manifest.jsonl")
    with open(prev_copy, "w", encoding="utf-8") as f:
        f.write("\n".join(lines))
    tmp = os.path.join(staging, "r7-manifest.jsonl")
    with open(tmp, "w", encoding="utf-8") as f:
        f.write(man_txt)
    st_new = json.loads(json.dumps(st))
    st_mod.revision_transition(st_new, tmp, prev_copy, reason)          # validates BEFORE anything is written
    shutil.copytree(staging, final, ignore=shutil.ignore_patterns("previous-manifest.jsonl", "r7-manifest.jsonl"))
    for e in new_entries:                                              # re-check the copied production tree
        bid = e["batch_id"]
        for lab in e["labels"]:
            t = open(os.path.join(final, bid, f"{lab}.json"), encoding="utf-8").read().rstrip("\n")
            if U.sha(t.encode("utf-8")) != e["slice_sha256"][lab]:
                raise SystemExit(f"STOP: production slice {bid}/{lab} ≠ its hash after copy (manifest NOT replaced)")
        if U.sha(open(os.path.join(final, f"{bid}.R7-PLAN.json"), "rb").read()) != e["r7_plan_sha256"]:
            raise SystemExit(f"STOP: production plan {bid} ≠ its hash after copy (manifest NOT replaced)")
    with open(man_path, "w", encoding="utf-8") as f:
        f.write(man_txt)
    if U.sha(open(man_path, "rb").read()) != st_new["manifest_sha256"]:
        raise SystemExit("STOP: the written manifest ≠ the state-bound hash")
    st_mod.save(st_new)
    return {"checks": checks, "strata": strata, "manifest_sha256": U.sha(open(man_path, "rb").read()),
            "manifest_output_sha256": r7h["output_sha256"], "activation_commit": head,
            "plans_sha256_of_all": U.sha(U.canon({e["batch_id"]: e["r7_plan_sha256"] for e in new_entries})),
            "state_manifest_sha256": st_new["manifest_sha256"]}


def rebind_contract(reason, staging, man_path=None, state_path=None, new_sha=None):
    """EG-4 (G-LOG-0105): rebind the ACTIVE R7 manifest to the frozen addendum (v2.6+AF-1 → v2.7). Only the header's
    contract.sha256 changes, plus an appended provenance record; the entry lines stay byte-identical (body
    output_sha256, slices, plans, labels, strata, Freeze 2 frame unchanged). The state is rebound through
    p3b_s5_state.rebind_manifest (composition identical). Every batch must be PREPARED at revision 7 (nothing
    dispatched). Validates everything first; a refusal writes nothing to the manifest or the state."""
    st_mod = _load("p3b_s5_state")
    man_path = man_path or os.path.join(CR, P.MANIFEST)
    state_path = state_path or os.path.join(CR, st_mod.STATE)
    new_sha = new_sha or U.ADDENDUM_SHA256
    if not reason:
        raise SystemExit("REFUSED: a contract rebind requires the recorded reason (the governance entry)")
    if os.path.abspath(staging).startswith(c.REPO_ROOT + os.sep) or os.path.exists(staging):
        raise SystemExit("REFUSED: staging must be a new directory outside the repository")
    if new_sha != U.ADDENDUM_SHA256 or U.fsha(os.path.join(CR, U.ADDENDUM_PATH)) != new_sha:
        raise SystemExit("REFUSED: the target is not the frozen R7 addendum sha256")
    raw = open(man_path, "rb").read()
    lines = raw.decode("utf-8").split("\n")
    hdr = json.loads(lines[0])["header"]
    con = hdr.get("contract") or {}
    if con.get("revision") != 7 or con.get("path") != U.ADDENDUM_PATH:
        raise SystemExit("REFUSED: the manifest is not an activated R7 manifest")
    if con.get("sha256") == new_sha:
        raise SystemExit("REFUSED: the manifest already binds the target addendum")
    st = st_mod.load(state_path)
    if st["manifest_sha256"] != U.sha(raw):
        raise SystemExit("REFUSED: P3B-STATE is not bound to the current manifest")
    bad = sorted(b for b, v in st["batches"].items() if v["state"] != "PREPARED" or v.get("revision") != 7)
    if bad:
        raise SystemExit(f"REFUSED: {len(bad)} batch(es) not PREPARED at revision 7")
    new_hdr = dict(hdr, contract=dict(con, sha256=new_sha),
                   r7_contract_rebinds=list(hdr.get("r7_contract_rebinds") or []) +
                   [{"from_sha256": con.get("sha256"), "to_sha256": new_sha, "authority": reason}])
    man_txt = U.canon({"header": new_hdr}).decode() + "\n" + "\n".join(lines[1:])
    if man_txt.split("\n")[1:] != lines[1:] or U.revision_violations(new_hdr):
        raise SystemExit("STOP: the rebind would change the body or violate the revision binding")
    if any(c.quarantine_hits(man_txt)):
        raise SystemExit("STOP: the rebound manifest failed the quarantine scan")
    os.makedirs(staging)
    prev_copy, tmp = os.path.join(staging, "previous-manifest.jsonl"), os.path.join(staging, "rebound-manifest.jsonl")
    with open(prev_copy, "wb") as f:
        f.write(raw)
    with open(tmp, "w", encoding="utf-8") as f:
        f.write(man_txt)
    st_new = json.loads(json.dumps(st))
    st_mod.rebind_manifest(st_new, tmp, prev_copy, reason)            # composition identical; validates BEFORE any write
    if st_new["batches"] != st["batches"]:
        raise SystemExit("STOP: the rebind would change a batch")
    with open(man_path, "w", encoding="utf-8") as f:
        f.write(man_txt)
    if U.fsha(man_path) != st_new["manifest_sha256"]:
        with open(man_path, "wb") as f:                                # restore; the state was not saved
            f.write(raw)
        raise SystemExit("STOP: the written manifest ≠ the state-bound hash (manifest restored)")
    st_mod.save(st_new, state_path)
    return {"from_contract_sha256": con.get("sha256"), "to_contract_sha256": new_sha,
            "from_manifest_sha256": U.sha(raw), "to_manifest_sha256": st_new["manifest_sha256"],
            "body_output_sha256": hdr.get("output_sha256"),
            "body_output_sha256_unchanged": U.sha("\n".join(lines[1:]).encode()) == U.sha("\n".join(man_txt.split("\n")[1:]).encode()),
            "batches": len(st_new["batches"]), "history_head_sha256": st_new["history"][-1]["entry_sha256"]}


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--rebind-contract", action="store_true")
    ap.add_argument("--dry-run", action="store_true")
    ap.add_argument("--write", action="store_true")
    ap.add_argument("--slices")
    ap.add_argument("--emit")
    ap.add_argument("--reason")
    ap.add_argument("--staging")
    a = ap.parse_args()
    if a.rebind_contract:                                              # EG-4 (G-LOG-0105)
        if not a.reason or not a.staging:
            raise SystemExit("--rebind-contract requires --reason (the governance entry) and --staging <new dir outside the repo>")
        c.assert_sealed()
        out = rebind_contract(a.reason, a.staging)
        print(json.dumps(out, indent=1, sort_keys=True))
        c.assert_sealed()
        return
    if not a.slices:
        raise SystemExit("--slices is required for --dry-run / --write")
    if a.write:
        if not a.reason or not a.staging:
            raise SystemExit("--write requires --reason (the governance entry) and --staging <dir outside the repo>")
        out = write(a.slices, a.reason, a.staging)
        print(json.dumps(out, indent=1, sort_keys=True))
        c.assert_sealed()
        return
    if not (a.dry_run and a.emit):
        raise SystemExit("use --dry-run --emit <dir> or --write --reason <G-LOG>")
    for d in (a.slices, a.emit):
        if os.path.abspath(d).startswith(c.REPO_ROOT + os.sep):
            raise SystemExit("REFUSED: dry-run directories must be outside the repository")
    lines = open(os.path.join(CR, P.MANIFEST), encoding="utf-8").read().split("\n")
    hdr = json.loads(lines[0])["header"]
    entries = [json.loads(l) for l in lines[1:] if l]
    raw = open(os.path.join(CR, U.BINARY_DECISIONS_PATH), "rb").read()
    dec, recs, F = U.binary_decisions(raw, U.sha(raw), AUTHORITY)
    if F:
        raise SystemExit(f"REFUSED: binary decisions invalid: {F[:3]}")
    man = [r for r in c.jl("P3B-IDENTITY-MANIFEST.jsonl") if "source_id" in r]
    sizes = r5.all_blob_sizes(man, c.REPO_ROOT, P.blob_sizes())
    fm = c.files_meta()
    ledger = os.path.join(CR, U.LEDGER)
    os.makedirs(os.path.join(a.emit, "plans"), exist_ok=True)
    frame, new_entries, decided_in_plans = {}, [], {}
    for e in entries:
        bid = e["batch_id"]
        slices = {lab: json.load(open(os.path.join(a.slices, bid, f"{lab}.json"), encoding="utf-8")) for lab in e["labels"]}
        plan = U.plan_derive_v7(bid, e["labels"], slices, SizedMap(sizes), fm, dec)
        body = U.canon(plan)
        with open(os.path.join(a.emit, "plans", f"{bid}.R7-PLAN.json"), "wb") as f:
            f.write(body)
        planned = set(U.run_owner(plan))
        legacy_dirs = sorted(d for d in (os.listdir(ledger) if os.path.isdir(ledger) else [])
                             if d.startswith(bid + "-") and d not in planned and d != f"{bid}-R7")
        new_entries.append(dict(e, run_id=f"{bid}-R7", r7_plan_sha256=U.sha(body),
                                legacy_ledger_sha256=U.legacy_digest(ledger, bid, planned, f"{bid}-R7"), legacy_dirs=legacy_dirs))
        for lab, L in plan["labels"].items():
            frame[lab] = S.frame_attributes({"labels": {lab: L}}, slices)[lab]
            if L.get("binary_decisions"):
                decided_in_plans[lab] = L["binary_decisions"]
    strata = {h: len(v) for h, v in S.assign(frame).items()}
    head = subprocess.run(["git", "-C", CR, "rev-parse", "HEAD"], capture_output=True, text=True).stdout.strip()
    r7_header = {"contract": {"revision": 7, "sha256": U.ADDENDUM_SHA256, "path": U.ADDENDUM_PATH},
                 "rev3_base": {"contract": hdr["contract"], "output_sha256": hdr["output_sha256"], "slice_root": hdr.get("slice_root")},
                 "r7_reader_abs": os.path.join(HERE, "p3b_read_source.py"), "r7_activation_commit": head,
                 "r7_binary_decisions_sha256": U.sha(raw), "r7_binary_decisions_authority": AUTHORITY}
    decided_files = {s for d in decided_in_plans.values() for s in d}
    report = {
        "artifact": "R7-ACTIVATION-DRY-RUN", "authority": "G-LOG-0101", "batches": len(new_entries),
        "labels": len(frame), "strata": strata, "approved_strata": APPROVED, "strata_match": strata == APPROVED,
        "frame_sha256": S.sha(S.canon(sorted(frame))),
        "binary_decisions": {"files_in_plans": len(decided_files), "all_12_present": decided_files == set(dec),
                             "labels_carrying_decisions": len(decided_in_plans)},
        "plans_sha256_of_all": U.sha(U.canon({e["batch_id"]: e["r7_plan_sha256"] for e in new_entries})),
        "batches_with_legacy_dirs": sorted(e["batch_id"] for e in new_entries if e["legacy_dirs"]),
        "r7_header": r7_header, "sizes_basis": "r5.all_blob_sizes (metadata; no corpus byte read)"}
    with open(os.path.join(a.emit, "R7-MANIFEST-CANDIDATE.jsonl"), "w", encoding="utf-8") as f:
        f.write(U.canon({"header": dict(hdr, **r7_header)}).decode() + "\n" +
                "\n".join(U.canon(x).decode() for x in new_entries) + "\n")
    text = json.dumps(report, indent=1, sort_keys=True)
    if any(c.quarantine_hits(text)):
        raise SystemExit("REFUSED: the report failed the quarantine scan")
    with open(os.path.join(a.emit, "R7-ACTIVATION-DRY-RUN.json"), "w", encoding="utf-8") as f:
        f.write(text + "\n")
    print(json.dumps({k: report[k] for k in ("batches", "labels", "strata", "strata_match", "frame_sha256",
                                             "binary_decisions", "batches_with_legacy_dirs")}))
    c.assert_sealed()


if __name__ == "__main__":
    main()
