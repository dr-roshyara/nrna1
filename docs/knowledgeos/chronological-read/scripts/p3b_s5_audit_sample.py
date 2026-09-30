#!/usr/bin/env python3
"""P3b S5 §21 / OMQ-09 audit-sample draw (protocol v1.7 §21 items 1, 3, 5; G-LOG-0017; G-LOG-0027 F3(3); plan v2.3.2
§B.8, §G.3). Draws the sample only; it audits nothing.

For one batch's outputs (a run directory with `objects.jsonl` and `register.jsonl`):
  1. every object record whose `semantic_status` is CONTESTED or HOMONYM-SPLIT (§21 item 1);
  2. every HYPOTHESIS, STRUCTURE-CANDIDATE, SCHEMA-LIMITATION and METHODOLOGICAL-DEFICIENCY register record (§21 item 5);
  3. a seeded sample of min(2, available) other register records (§21 item 5);
  4. further seeded records (objects + register records not selected in 1-3): n = max(5, floor(0.1 x N)), N = all
     object + register records of the batch, capped by availability; at least one object record (drawn first) when
     one is available (OMQ-09);
  5. F3(3): for each label with more than 100 Stage-2B occurrences, a seeded sample of 30 occurrences. An occurrence
     is a `STAGE-2A-2B` stage-2 disposition; a disposition listing covered offsets (`offsets` / `stage2a_offsets`,
     F3(1)) contributes one occurrence per offset.
Seed: one stream per batch, `SeedSequence(20261100).spawn(n_batches)[manifest_index]` -> integer -> `random.Random`;
draws are made in the order above, labels sorted (deterministic).

Audit groups (§B.8, §21 item 3): groups of 5 consecutive batches by manifest index; a cross-batch audit is due when a
group's last batch is audited.

  p3b_s5_audit_sample.py sample RUN_DIR --index I --n-batches N [--batch-id B] [--out PATH]
  p3b_s5_audit_sample.py groups --n-batches N
"""
import argparse
import json
import math
import os
import random
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, _HERE)
import p3b_s5_ops_lib as ops  # noqa: E402

c = ops.load_common()
ROOT = 20261100
GROUP = 5
MANDATORY_LABEL = ("CONTESTED", "HOMONYM-SPLIT")
MANDATORY_KINDS = ("HYPOTHESIS", "STRUCTURE-CANDIDATE", "SCHEMA-LIMITATION", "METHODOLOGICAL-DEFICIENCY")
OTHER_REGISTER_MIN = 2
MIN_RECORDS, SHARE = 5, 0.10
F3_THRESHOLD, F3_SAMPLE = 100, 30


def batch_seed(index, n_batches):
    if not 0 <= index < n_batches:
        raise c.S5Error("manifest index out of range")
    return c.int_seed(ROOT, n_batches, index)


def occurrences(obj):
    out = []
    for d in obj.get("stage2_dispositions") or []:
        if d.get("method") != "STAGE-2A-2B":
            continue
        offs = d.get("offsets") or d.get("stage2a_offsets")
        base = [obj["working_label"], d.get("source_id"), d.get("hit_kind"), d.get("hit_key"), d.get("term_index")]
        if offs:
            out += [base + [o] for o in offs]
        else:
            out.append(base + [None])
    return sorted(out, key=lambda x: json.dumps(x))


def draw(objects, register, index, n_batches):
    rng = random.Random(batch_seed(index, n_batches))
    obj_ids = sorted(o["working_label"] for o in objects)
    by_obj = {o["working_label"]: o for o in objects}
    reg_sorted = sorted(register, key=lambda r: r["rs_id"])
    s1 = sorted(l for l in obj_ids if by_obj[l].get("semantic_status") in MANDATORY_LABEL)
    s2 = [r["rs_id"] for r in reg_sorted if r.get("kind") in MANDATORY_KINDS]
    others = [r["rs_id"] for r in reg_sorted if r.get("kind") not in MANDATORY_KINDS]
    s3 = sorted(rng.sample(others, min(OTHER_REGISTER_MIN, len(others))))
    N = len(objects) + len(register)
    target = max(MIN_RECORDS, math.floor(SHARE * N))
    chosen_reg = set(s2) | set(s3)
    pool_obj = [l for l in obj_ids if l not in s1]
    obj_available = bool(pool_obj)
    pool_reg = [r["rs_id"] for r in reg_sorted if r["rs_id"] not in chosen_reg]
    s4_obj, s4_reg = [], []
    if pool_obj and target > 0:
        first = rng.choice(pool_obj)
        s4_obj.append(first)
        pool_obj.remove(first)
    pool = [("object", l) for l in pool_obj] + [("register", r) for r in pool_reg]
    rest = rng.sample(pool, min(max(0, target - len(s4_obj)), len(pool)))
    s4_obj += [x for k, x in rest if k == "object"]
    s4_reg += [x for k, x in rest if k == "register"]
    f3 = {}
    for lab in obj_ids:
        occ = occurrences(by_obj[lab])
        if len(occ) > F3_THRESHOLD:
            f3[lab] = {"occurrences": len(occ), "sample": sorted(rng.sample(occ, F3_SAMPLE), key=lambda x: json.dumps(x))}
    return {
        "records_in_batch": {"objects": len(objects), "register": len(register), "total": N},
        "mandatory_contested_or_homonym_split": s1,
        "mandatory_register": s2,
        "other_register_sample": s3,
        "seeded_records": {"target": target, "objects": sorted(s4_obj), "register": sorted(s4_reg),
                           "n": len(s4_obj) + len(s4_reg), "object_included": bool(s4_obj),
                           "object_available": obj_available},
        "f3_stage2b": f3,
    }


def sample_run(run_dir, index, n_batches, batch_id=None):
    objs_p, reg_p = os.path.join(run_dir, "objects.jsonl"), os.path.join(run_dir, "register.jsonl")
    ops.check_paths([objs_p, reg_p])
    objects, register = ops.read_jsonl(objs_p), ops.read_jsonl(reg_p)
    if batch_id:
        objects = [o for o in objects if o.get("batch_id") == batch_id]
        register = [r for r in register if r.get("batch_id") == batch_id]
    body = {"batch_id": batch_id or (objects[0].get("batch_id") if objects else None), "manifest_index": index,
            "n_batches": n_batches, "seed_root": ROOT, "batch_seed": batch_seed(index, n_batches),
            "audit_group": group_of(index), **draw(objects, register, index, n_batches)}
    return body, [objs_p, reg_p]


def group_of(index):
    g = index // GROUP
    return {"group": g, "first_index": g * GROUP, "last_index": g * GROUP + GROUP - 1,
            "cross_batch_audit_due_after_this": index % GROUP == GROUP - 1}


def groups(n_batches):
    gs = []
    for g in range(math.ceil(n_batches / GROUP)):
        members = list(range(g * GROUP, min(n_batches, g * GROUP + GROUP)))
        gs.append({"group": g, "indices": members, "complete": len(members) == GROUP})
    return gs


def main(argv=None):
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    sub = ap.add_subparsers(dest="cmd", required=True)
    s = sub.add_parser("sample")
    s.add_argument("run_dir")
    s.add_argument("--index", type=int, required=True)
    s.add_argument("--n-batches", type=int, required=True)
    s.add_argument("--batch-id")
    s.add_argument("--out")
    g = sub.add_parser("groups")
    g.add_argument("--n-batches", type=int, required=True)
    a = ap.parse_args(argv)
    try:
        c.assert_sealed()
        if a.cmd == "groups":
            gs = groups(a.n_batches)
            print(json.dumps({"groups": len(gs), "incomplete_last": not gs[-1]["complete"] if gs else False}))
        else:
            body, inputs = sample_run(ops.abspath(a.run_dir), a.index, a.n_batches, a.batch_id)
            text = ops.canon(body)
            hdr = c.header(__file__, ops.header_inputs(inputs), {"index": a.index, "n_batches": a.n_batches,
                                                               "seed_root": ROOT}, text,
                           extra={"seed": body["batch_seed"]})
            doc = json.dumps({"header": hdr, "body": body}, indent=1, sort_keys=True, ensure_ascii=False) + "\n"
            if a.out:
                ops.write_new(a.out, doc)
            print(json.dumps({"output_sha256": hdr["output_sha256"], "seeded": body["seeded_records"]["n"],
                              "mandatory_register": len(body["mandatory_register"]),
                              "f3_labels": len(body["f3_stage2b"])}))
        c.assert_sealed()
        return 0
    except c.S5Error as ex:
        print(f"REFUSED: {ex}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
