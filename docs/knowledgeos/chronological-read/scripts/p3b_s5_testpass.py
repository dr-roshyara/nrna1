#!/usr/bin/env python3
"""P3b S5 test-pass tooling (protocol v1.7 §13.9 RC-9g, §13.10a; plan v2.3.2 §C.2; MINOR-7, MINOR-8; deliverable O-16).
Infrastructure only: it builds the frame, the permutation, the prefix and the per-test load decisions. It runs no test.

Approved parameters (G-LOG-0041; never changed here): permutation `random.Random(20260927).shuffle` of the frame sorted
by record id; fixed share s = 20% (prefix = ceil(0.2 N), computed in integers); load threshold 4,081 distinct hit keys
(T4); per-test whole-file read cap 8.63 MB = 8,633,127 bytes.

  (a) frame     all non-CORPUS TEST-DEFINED register records of the PRIMARY run (OBJECT records from batches, CROSS-OBJECT
                records from S5a); COMPARISON (§K rerun) records and re-analysis dispositions excluded; sorted by record id
                (`rs_id`) and hashed.
  (b) permute   the sorted frame's ids shuffled by random.Random(20260927); hashed.
  (c) prefix    the first ceil(0.2 N) ids of the permutation. Fixed: never lowered, never replaced.
  (d) load      a test's load = number of DISTINCT hit keys (S-id, anchor-or-offset, term) of the UNION of its B and D
                lexical-search results over DISCOVERY files. A result naming a hold-out file is refused (the searches run
                over the discovery population only, §M item 7).
                Non-CORPUS: load > 4,081 -> LOAD (not run; stays TEST-DEFINED; KEEPS its prefix place; not replaced).
  (e) reads     non-CORPUS: cumulative whole-file reads <= 8,633,127 bytes; a read that would pass the cap stops the test:
                LOAD-PARTIAL (stays TEST-DEFINED; no STATUS from a partial test).
  (f) CORPUS    never skipped, never truncated: if load > 4,081 or predicted reads > the cap, a `P3B-ESC` (load)
                escalation is written for the human BEFORE running; the test waits for the human's recorded budget
                (or a §19.4/§23 stop-the-line). A CORPUS read budget raises on overrun unless a human budget is given.
  (g) record    `test_plan_sha256` of every prefix record into the `s5_test_pass` section of P3B-INPUT-MANIFEST.json
                (§13.10a), before the testing run; the section is written once.

  p3b_s5_testpass.py frame REGISTER_FILE [...] --out FRAME.json
  p3b_s5_testpass.py plan FRAME.json [--loads LOADS.json] [--escalations ESC.jsonl] --out PLAN.json
  p3b_s5_testpass.py record PLAN.json --manifest P3B-INPUT-MANIFEST.json
"""
import argparse
import json
import os
import random
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, _HERE)
import p3b_s5_ops_lib as ops  # noqa: E402

c = ops.load_common()
SEED = 20260927
SHARE_NUM, SHARE_DEN = 1, 5                  # s = 20 %, fixed (MINOR-8)
LOAD_MAX = 4081                              # T4 figure (v1.7 §19.4)
READ_CAP = 8_633_127                         # 8.63 MB per test (plan §C.2)
SECTION = "s5_test_pass"


class EscalationRequired(c.S5Error):
    pass


# ---------------------------------------------------------------- (a) frame
def in_frame(rec):
    return (rec.get("lifecycle_stage") == "TEST-DEFINED" and rec.get("scale") != "CORPUS"
            and not ops.is_comparison(rec) and not ops.is_reanalysis(rec) and bool(rec.get("rs_id")))


def build_frame(records):
    rows = sorted(({"rs_id": r["rs_id"], "scale": r.get("scale"), "test_plan_sha256": r.get("test_plan_sha256"),
                    "record_sha256": ops.canon_hash(r)} for r in records if in_frame(r)), key=lambda x: x["rs_id"])
    ids = [x["rs_id"] for x in rows]
    if len(set(ids)) != len(ids):
        raise c.S5Error("duplicate rs_id in the test-pass frame")
    missing = [x["rs_id"] for x in rows if not x["test_plan_sha256"]]
    if missing:
        raise c.S5Error(f"{len(missing)} TEST-DEFINED record(s) lack test_plan_sha256 (§13.9)")
    return {"n": len(rows), "rows": rows, "frame_sha256": ops.canon_hash(rows)}


def corpus_records(records):
    return sorted((r for r in records if r.get("lifecycle_stage") == "TEST-DEFINED" and r.get("scale") == "CORPUS"
                   and not ops.is_comparison(r) and not ops.is_reanalysis(r)), key=lambda r: r["rs_id"])


# ---------------------------------------------------------------- (b), (c)
def permute(frame):
    ids = [x["rs_id"] for x in frame["rows"]]
    if ids != sorted(ids):
        raise c.S5Error("frame is not sorted by record id")
    perm = list(ids)
    random.Random(SEED).shuffle(perm)
    return {"seed": SEED, "order": perm, "permutation_sha256": ops.canon_hash(perm)}


def prefix_size(n):
    """ceil(0.2 n) in exact integer arithmetic (0.2*n in floating point can exceed the integer, e.g. n = 15)."""
    return (n * SHARE_NUM + SHARE_DEN - 1) // SHARE_DEN


def prefix(perm):
    k = prefix_size(len(perm["order"]))
    return perm["order"][:k]


# ---------------------------------------------------------------- (d) load unit
def hit_key(h):
    pos = h.get("anchor") if h.get("anchor") is not None else h.get("offset")
    return (h["source_id"], json.dumps(pos, ensure_ascii=False), h["term"])


def test_load(b_hits, d_hits, sets=None):
    """Distinct hit keys of B ∪ D over discovery files. Refuses a result that names a hold-out file."""
    scanner = ops.load_script("p3b_s5_quarantine_scan")
    keys = {hit_key(h) for h in list(b_hits) + list(d_hits)}
    if scanner.count_holdout_sids(sorted({k[0] for k in keys}), sets):
        raise c.S5Error("B/D search results include hold-out files; the searches must run over discovery files only")
    return len(keys)


# ---------------------------------------------------------------- (e), (f) budgets and decisions
class ReadBudget:
    """Per-test whole-file read budget. Non-CORPUS: a read that would pass the cap is refused -> LOAD-PARTIAL.
    CORPUS: no silent truncation: overrun raises EscalationRequired unless the human authorized a budget."""

    def __init__(self, corpus=False, authorized_budget=None):
        self.corpus, self.used, self.reads = corpus, 0, []
        self.cap = authorized_budget if (corpus and authorized_budget) else READ_CAP
        self.partial = False

    def read(self, source_id, nbytes):
        if self.used + nbytes > self.cap:
            if self.corpus:
                raise EscalationRequired(f"CORPUS test reads would exceed {self.cap} bytes: P3B-ESC (load) to the human")
            self.partial = True
            return False
        self.used += nbytes
        self.reads.append([source_id, nbytes])
        return True

    def outcome(self):
        return "LOAD-PARTIAL" if self.partial else "COMPLETE"


def decide(rs_id, load, corpus=False, predicted_read_bytes=None):
    if corpus:
        over = load > LOAD_MAX or (predicted_read_bytes is not None and predicted_read_bytes > READ_CAP)
        return {"rs_id": rs_id, "scale": "CORPUS", "load": load, "predicted_read_bytes": predicted_read_bytes,
                "decision": "ESCALATE-BEFORE-RUN" if over else "RUN-FULL", "skipped": False}
    if load > LOAD_MAX:
        return {"rs_id": rs_id, "load": load, "decision": "LOAD", "skipped": True, "keeps_prefix_place": True,
                "lifecycle_after": "TEST-DEFINED"}
    return {"rs_id": rs_id, "load": load, "decision": "RUN", "read_cap_bytes": READ_CAP, "skipped": False}


def escalation(dec):
    return {"escalation": "P3B-ESC", "reason": "LOAD", "rs_id": dec["rs_id"], "scale": "CORPUS", "load": dec["load"],
            "load_threshold": LOAD_MAX, "predicted_read_bytes": dec.get("predicted_read_bytes"), "read_cap_bytes": READ_CAP,
            "utc": ops.utc_now(), "required_human_action": "authorize the full test with a recorded budget, or invoke the "
            "§19.4/§23 stop-the-line (-> §26 change); the test is never skipped or truncated silently (plan §C.2)"}


def plan(frame, loads, corpus_loads=None):
    """loads: {rs_id: load} for prefix records (missing -> PENDING-LOAD). corpus_loads: {rs_id: {load, predicted_read_bytes}}."""
    perm = permute(frame)
    pre = prefix(perm)
    entries = []
    for pos, rid in enumerate(pre):
        if rid in loads:
            e = decide(rid, loads[rid])
        else:
            e = {"rs_id": rid, "decision": "PENDING-LOAD", "skipped": False}
        e["prefix_position"] = pos
        entries.append(e)
    corpus = [decide(rid, v["load"], True, v.get("predicted_read_bytes")) for rid, v in sorted((corpus_loads or {}).items())]
    if [e["rs_id"] for e in entries] != pre:               # invariant: no record dropped or replaced
        raise c.S5Error("prefix invariant violated")
    return {"frame_sha256": frame["frame_sha256"], "frame_n": frame["n"], **perm, "prefix_n": len(pre),
            "prefix": entries, "corpus": corpus,
            "summary": {"LOAD": sum(e["decision"] == "LOAD" for e in entries),
                        "RUN": sum(e["decision"] == "RUN" for e in entries),
                        "PENDING-LOAD": sum(e["decision"] == "PENDING-LOAD" for e in entries),
                        "CORPUS-ESCALATE": sum(e["decision"] == "ESCALATE-BEFORE-RUN" for e in corpus),
                        "conditional_on": f"load <= {LOAD_MAX} and reads <= {READ_CAP} bytes (non-CORPUS)"}}


# ---------------------------------------------------------------- (g) manifest section
def record_plans(plan_doc, frame, manifest_path):
    ap = ops.abspath(manifest_path)
    ops.check_paths([ap])
    with open(ap, encoding="utf-8") as f:
        man = json.load(f)
    if SECTION in man:
        raise c.S5Error(f"{SECTION} already recorded in the input manifest (written once, before the testing run)")
    tp = {r["rs_id"]: r["test_plan_sha256"] for r in frame["rows"]}
    pre_ids = [e["rs_id"] for e in plan_doc["prefix"]]
    man[SECTION] = {"frame_sha256": frame["frame_sha256"], "permutation_sha256": plan_doc["permutation_sha256"],
                    "seed": SEED, "share": "20% fixed", "prefix_n": len(pre_ids),
                    "test_plan_sha256": {rid: tp[rid] for rid in pre_ids}, "recorded_utc": ops.utc_now(),
                    "recorder": "scripts/p3b_s5_testpass.py", "recorder_version": ops.script_blob(__file__)}
    ops.write_atomic(ap, json.dumps(man, indent=1, sort_keys=True, ensure_ascii=False) + "\n")
    return man[SECTION]


def main(argv=None):
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    sub = ap.add_subparsers(dest="cmd", required=True)
    f = sub.add_parser("frame")
    f.add_argument("registers", nargs="+")
    f.add_argument("--out", required=True)
    p = sub.add_parser("plan")
    p.add_argument("frame")
    p.add_argument("--loads", help="JSON {rs_id: load} (prefix) and optional {'corpus': {rs_id: {load, predicted_read_bytes}}}")
    p.add_argument("--escalations")
    p.add_argument("--out", required=True)
    r = sub.add_parser("record")
    r.add_argument("plan")
    r.add_argument("--frame", required=True)
    r.add_argument("--manifest", required=True)
    a = ap.parse_args(argv)
    try:
        c.assert_sealed()
        if a.cmd == "frame":
            ops.check_paths(a.registers)
            recs = [x for p_ in a.registers for x in ops.read_jsonl(p_)]
            fr = build_frame(recs)
            ops.write_new(a.out, json.dumps(fr, indent=1, sort_keys=True) + "\n")
            print(json.dumps({"frame_n": fr["n"], "frame_sha256": fr["frame_sha256"]}))
        elif a.cmd == "plan":
            ops.check_paths([a.frame] + ([a.loads] if a.loads else []))
            fr = json.load(open(ops.abspath(a.frame), encoding="utf-8"))
            ld = json.load(open(ops.abspath(a.loads), encoding="utf-8")) if a.loads else {}
            corpus = ld.pop("corpus", {})
            pl = plan(fr, ld, corpus)
            esc = [escalation(e) for e in pl["corpus"] if e["decision"] == "ESCALATE-BEFORE-RUN"]
            if esc:
                if not a.escalations:
                    raise c.S5Error("CORPUS over-load: --escalations is required to record P3B-ESC for the human")
                ops.append_jsonl(a.escalations, esc)
            ops.write_new(a.out, json.dumps(pl, indent=1, sort_keys=True) + "\n")
            print(json.dumps({"prefix_n": pl["prefix_n"], "permutation_sha256": pl["permutation_sha256"], **pl["summary"]}))
        else:
            ops.check_paths([a.plan, a.frame])
            pl = json.load(open(ops.abspath(a.plan), encoding="utf-8"))
            fr = json.load(open(ops.abspath(a.frame), encoding="utf-8"))
            sec = record_plans(pl, fr, a.manifest)
            print(json.dumps({"prefix_n": sec["prefix_n"], "recorded": len(sec["test_plan_sha256"])}))
        c.assert_sealed()
        return 0
    except c.S5Error as ex:
        print(f"REFUSED: {ex}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
