#!/usr/bin/env python3
"""P3b S5a/S5b pass snapshot (§9E "Inputs", §19.5; plan §H.7 link 1; OMQ-17). Infrastructure; G-LOG-0041.

Snapshots and hashes exactly the records a pass may read: the PROPOSED object, register and P1-gap files of batches
whose **primary** run carries an H-06 `ACCEPTED` decision in `audit-p3b/S5-ACCEPTANCE.jsonl`. COMPARISON runs
(`-R2S`) are never included (plan §K). Each batch contributes the run named in its acceptance record, with that
record's reference as the "acceptance state at research time" (§9E).

  p3b_s5a_snapshot.py --pass OA#### [--root DIR] [--acceptance FILE] [--out FILE]

Writes P3B-PASS-SNAPSHOT.json ({header: §19.5, body}) once; refuses to overwrite, refuses if not SEALED, refuses an
acceptance record for a COMPARISON run.
"""
import importlib.util
import json
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
_spec = importlib.util.spec_from_file_location("p3b_s5_common", os.path.join(_HERE, "p3b_s5_common.py"))
c = importlib.util.module_from_spec(_spec)
_spec.loader.exec_module(c)
OUT = "P3B-PASS-SNAPSHOT.json"
ACCEPTANCE = "audit-p3b/S5-ACCEPTANCE.jsonl"
FILES = ("objects", "register", "p1-gap-capture")


def build(root, acceptance_rel):
    c.check_inputs([acceptance_rel])
    acc_path = os.path.join(root, acceptance_rel)
    recs = []
    if os.path.exists(acc_path):
        with open(acc_path, encoding="utf-8") as f:
            recs = [json.loads(l) for l in f if l.strip()]
    accepted, seen = [], set()
    for r in recs:
        if r.get("outcome") != "ACCEPTED":
            continue
        run = r["run_id"]
        if run.endswith("R2S"):
            raise c.S5Error("an acceptance record names a COMPARISON run (plan §K)")
        if not re.fullmatch(r"OB\d{4}-R2(\.\d+)?", run) or r["batch_id"] in seen:
            raise c.S5Error("malformed or duplicate acceptance record")
        seen.add(r["batch_id"])
        files = {}
        for name in FILES:
            rel = f"ledger-p3b-r2/{run}/{name}.jsonl"
            c.check_inputs([rel])
            fp = os.path.join(root, rel)
            if os.path.exists(fp):
                files[rel] = c.sha256_file(fp)
        if f"ledger-p3b-r2/{run}/objects.jsonl" not in files:
            raise c.S5Error(f"accepted run {run} has no objects file")
        accepted.append({"batch_id": r["batch_id"], "run_id": run, "acceptance_ref": {
            "utc": r.get("utc"), "decided_by": r.get("decided_by"), "reference": r.get("reference")}, "files": files})
    accepted.sort(key=lambda x: x["batch_id"])
    return accepted


def main(argv=None):
    a = argv if argv is not None else sys.argv[1:]
    if "--pass" not in a or not re.fullmatch(r"OA\d{4}", a[a.index("--pass") + 1]):
        print(__doc__, file=sys.stderr)
        return 2
    pid = a[a.index("--pass") + 1]
    root = a[a.index("--root") + 1] if "--root" in a else c.CR
    acc = a[a.index("--acceptance") + 1] if "--acceptance" in a else ACCEPTANCE
    out = a[a.index("--out") + 1] if "--out" in a else os.path.join(root, OUT)
    c.assert_sealed()
    if os.path.exists(out):
        print(f"REFUSED: {os.path.basename(out)} exists (written once per pass)", file=sys.stderr)
        return 2
    accepted = build(root, acc)
    body = {"pass_id": pid, "record_state": "PROPOSED records of H-06-accepted batches (OMQ-17)",
            "accepted_batches": accepted, "counts": {"batches": len(accepted),
                                                     "files": sum(len(x["files"]) for x in accepted)}}
    txt = c.canon(body)
    hdr = c.header(__file__, [acc] if root == c.CR else [], {"pass_id": pid}, txt,
                   {"artifact": OUT, "acceptance_sha256": c.sha256_file(os.path.join(root, acc)) if os.path.exists(os.path.join(root, acc)) else None})
    with open(out, "w", encoding="utf-8") as f:
        f.write(c.canon({"header": hdr, "body": body}) + "\n")
    print(f"S5A SNAPSHOT {pid}: {len(accepted)} accepted batches, output_sha256 {hdr['output_sha256'][:16]}")
    c.assert_sealed()
    return 0


if __name__ == "__main__":
    sys.exit(main())
