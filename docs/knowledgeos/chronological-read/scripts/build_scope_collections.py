#!/usr/bin/env python3
"""P2b (A10) closing artifact — scope-based collections for contribution rows that
carry NO object label at all (labels == []). Per protocol B1/A10: "_theory-level.md /
_methodological.md = those scopes, same discipline [as families]." These 485 rows
(233 OBJECT, 140 METHODOLOGICAL, 109 THEORY-LEVEL, 3 CROSS-OBJECT) are genuinely
label-less — not UNKNOWN-OBJECT-CANDIDATE (that sentinel already covers a different
727 rows, handled by _UNRESOLVED-OBJECTS.md) — many are legitimate: a governance or
methodological finding that simply does not concern any single tracked object.

Writes:
  _methodological.md          140 rows, scope=METHODOLOGICAL, labels=[]
  _theory-level.md             109 rows, scope=THEORY-LEVEL, labels=[]
  _UNLABELED-OBJECT-SCOPE.md  236 rows, scope in {OBJECT, CROSS-OBJECT}, labels=[]
                              (not named in the protocol's own list — added here
                              because these rows would otherwise be uncovered by any
                              family, breaking the P2b TERMINAL condition)

Purely mechanical (R13). No identity claim; content is grouped by source batch/file
for readability, never reinterpreted.
"""
import json
import os
import re
import subprocess
from collections import defaultdict

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def sid_num(sid):
    m = re.match(r"S(\d+)", sid)
    return int(m.group(1)) if m else -1


def write_collection(rows, out_name, title, scope_desc):
    rows = sorted(rows, key=lambda c: sid_num(c["source_id"]))
    by_batch = defaultdict(list)
    for c in rows:
        by_batch[c.get("_batch_id", "UNKNOWN")].append(c)

    lines = [f"# {title}\n"]
    lines.append(
        f"Mechanically collected: every contribution row with {scope_desc} AND an "
        f"empty `labels[]` (no object touched) — {len(rows)} rows total. This is a "
        f"P2b closing artifact (protocol §A10/B1: \"_theory-level.md / "
        f"_methodological.md = those scopes, same discipline [as families]\"), "
        f"existing so every contribution row is referenced by ≥1 family, collection, "
        f"or `_UNRESOLVED-OBJECTS.md` (the P2b TERMINAL condition). Grouping by batch "
        f"is navigation only, never an identity or thematic claim.\n"
    )
    for batch_id in sorted(by_batch.keys(), key=lambda b: int(b[1:]) if b.startswith("B") else 0):
        batch_rows = by_batch[batch_id]
        lines.append(f"## {batch_id} ({len(batch_rows)} rows)\n")
        for c in batch_rows:
            types = ", ".join(c.get("types") or [])
            anchor = (c.get("anchor") or "").replace("\n", " ")
            statement = (c.get("statement") or "").replace("\n", " ")
            lines.append(f"- `[{c['source_id']}]` types=[{types}] — \"{statement}\" (anchor: \"{anchor}\")")
        lines.append("")

    out_path = os.path.join(FAM, out_name)
    with open(out_path, "w", encoding="utf-8") as fh:
        fh.write("\n".join(lines) + "\n")
    print(f"OK: {out_path} written — {len(rows)} rows")


def main():
    contribs = load_jsonl(os.path.join(CR, "03-CONTRIBUTIONS.jsonl"))
    unlabeled = [c for c in contribs if (c.get("labels") or []) == []]

    methodological = [c for c in unlabeled if c.get("scope") == "METHODOLOGICAL"]
    theory_level = [c for c in unlabeled if c.get("scope") == "THEORY-LEVEL"]
    object_scope = [c for c in unlabeled if c.get("scope") in ("OBJECT", "CROSS-OBJECT")]

    assert len(methodological) + len(theory_level) + len(object_scope) == len(unlabeled)

    write_collection(methodological, "_methodological.md",
                      "20-FAMILIES/_methodological.md — P2b scope collection",
                      "scope=METHODOLOGICAL")
    write_collection(theory_level, "_theory-level.md",
                      "20-FAMILIES/_theory-level.md — P2b scope collection",
                      "scope=THEORY-LEVEL")
    write_collection(object_scope, "_UNLABELED-OBJECT-SCOPE.md",
                      "20-FAMILIES/_UNLABELED-OBJECT-SCOPE.md — P2b closing artifact "
                      "(not in the protocol's named list; added for TERMINAL completeness)",
                      "scope in {OBJECT, CROSS-OBJECT}")

    print(f"Total label-less rows covered: {len(unlabeled)}")


if __name__ == "__main__":
    main()
