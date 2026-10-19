#!/usr/bin/env python3
"""E-4 register check (trial M-04-T1). For rows whose id starts with the given prefix: one of the six
M-04 step-6a dispositions; follow-up present when unresolved; manuscript pointer present when integrated.
    python3 -I register_check.py <register.tsv> <id_prefix>"""
import csv, sys
SIX = {"integrated", "already explained", "evidence", "unresolved", "rejected", "excluded"}
rows = [r for r in csv.DictReader(open(sys.argv[1], encoding="utf-8"), delimiter="\t")
        if r["idea_id"].startswith(sys.argv[2])]
bad = []
for r in rows:
    d = r.get("disposition", "")
    if d not in SIX: bad.append((r["idea_id"], f"disposition '{d}' not one of six"))
    if d == "unresolved" and not r["follow_up"].strip(): bad.append((r["idea_id"], "unresolved without follow-up"))
    if d in ("integrated", "already explained") and not r["manuscript"].strip():
        bad.append((r["idea_id"], f"{d} without pointer"))
info = [r["idea_id"] for r in rows if "+" in r["epistemic"]]
for i, m in bad: print(i, m, sep="\t")
print(f"# {len(rows)} rows checked, {len(bad)} findings; INFO (A-1 pending, not a finding): "
      f"{len(info)} compound epistemic marks", file=sys.stderr)
