#!/usr/bin/env python3
"""E-1 coverage check (trial M-04-T1). Lists non-blank source lines covered neither by an idea-register
row's location nor by a non-propositional mark.  Read-only.
    python3 -I coverage_check.py <register.tsv> <nonprop.tsv> <repo_root> <source_path> [...]"""
import csv, re, sys
reg, nonprop, root, sources = sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4:]
SKIP = {"---", "```", "```text", "```yaml", "```python"}
def ranges(loc):
    for a, b in re.findall(r"L(\d+)(?:[-–](\d+))?", loc):
        yield from range(int(a), int(b or a) + 1)
rows = list(csv.DictReader(open(reg, encoding="utf-8"), delimiter="\t"))
marks = list(csv.DictReader(open(nonprop, encoding="utf-8"), delimiter="\t"))
total = 0
for src in sources:
    lines = open(f"{root}/{src}", encoding="utf-8").read().split("\n")
    cov = {i for r in rows if r["source"] == src for i in ranges(r["location"])}
    cov |= {i for m in marks if m["source"] == src for i in ranges(m["lines"])}
    sub = [i for i, l in enumerate(lines, 1) if l.strip() and l.strip() not in SKIP]
    unc = [i for i in sub if i not in cov]
    runs = []
    for i in unc:
        if runs and i == runs[-1][1] + 1: runs[-1][1] = i
        else: runs.append([i, i])
    total += len(unc)
    print(f"{src}\tnonblank={len(sub)}\tuncovered={len(unc)}\t" +
          " ".join(f"L{a}-{b}" if a != b else f"L{a}" for a, b in runs))
sys.exit(1 if total else 0)
