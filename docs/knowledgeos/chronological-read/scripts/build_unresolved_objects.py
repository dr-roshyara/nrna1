#!/usr/bin/env python3
"""P2b (A10) closing artifact — _UNRESOLVED-OBJECTS.md: every contribution row whose
ONLY label is the UNKNOWN-OBJECT-CANDIDATE sentinel (so it was excluded from every
label's family.rows by design — see derive_families.py) is listed here, grouped by
which existing label(s) it was flagged as a candidate of. This satisfies P2b's
TERMINAL condition: "every contribution row is referenced by >=1 family, collection,
or _UNRESOLVED-OBJECTS.md." Purely mechanical (R13) — no identity claim, no merging.
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


def main():
    contribs = load_jsonl(os.path.join(CR, "03-CONTRIBUTIONS.jsonl"))
    only_unknown = [c for c in contribs if (c.get("labels") or []) == ["UNKNOWN-OBJECT-CANDIDATE"]]
    only_unknown.sort(key=lambda c: sid_num(c["source_id"]))

    by_candidate = defaultdict(list)
    no_candidate = []
    for c in only_unknown:
        cands = (c.get("unknown_candidate") or {}).get("candidate_of") or []
        if cands:
            for cand in cands:
                by_candidate[cand].append(c)
        else:
            no_candidate.append(c)

    lines = []
    lines.append("# 20-FAMILIES/_UNRESOLVED-OBJECTS.md — P2b closing artifact\n")
    lines.append(
        f"Every contribution row whose ONLY label is the `UNKNOWN-OBJECT-CANDIDATE` "
        f"sentinel ({len(only_unknown)} rows total) is listed here, grouped by which "
        f"existing/candidate label(s) it names in `unknown_candidate.candidate_of[]`. "
        f"These rows are deliberately excluded from every `20-FAMILIES/<label>.md` "
        f"family file (per R5/R12: a mere candidate-of pointer is not evidence FOR that "
        f"label, only evidence of an agent's uncertainty). This file exists solely to "
        f"satisfy the P2b closing assertion that every contribution row is referenced "
        f"somewhere — by a family, this file, or (for exact duplicates) the roadmap's "
        f"own duplicate record. Nothing here is a family write-up; nothing here is an "
        f"identity claim.\n"
    )
    lines.append(f"- Rows naming ≥1 candidate label: {sum(len(v) for v in by_candidate.values())} "
                 f"(rows may be listed under more than one candidate)")
    lines.append(f"- Rows naming NO candidate label at all (why_uncertain only): {len(no_candidate)}\n")

    lines.append("## By candidate label (existing/proposed label this row might belong to)\n")
    for cand in sorted(by_candidate.keys()):
        rows = by_candidate[cand]
        lines.append(f"### Candidate of `{cand}` ({len(rows)} rows)\n")
        for c in rows:
            wu = (c.get("unknown_candidate") or {}).get("why_uncertain", "")
            anchor = (c.get("anchor") or "").replace("\n", " ")
            lines.append(f"- `[{c['source_id']}]` anchor: \"{anchor}\" — why_uncertain: {wu}")
        lines.append("")

    if no_candidate:
        lines.append(f"## No candidate label named ({len(no_candidate)} rows)\n")
        lines.append(
            "These rows were flagged UNKNOWN-OBJECT-CANDIDATE without the extracting "
            "agent naming any specific pre-existing object it might be — a blanket "
            "hedge rather than a targeted uncertainty (against the extraction "
            "contract's own instruction that this sentinel requires a specific "
            "candidate). Listed here for P3's awareness; not corrected retroactively.\n"
        )
        for c in no_candidate:
            wu = (c.get("unknown_candidate") or {}).get("why_uncertain", "")
            anchor = (c.get("anchor") or "").replace("\n", " ")
            lines.append(f"- `[{c['source_id']}]` anchor: \"{anchor}\" — why_uncertain: {wu}")
        lines.append("")

    out_path = os.path.join(FAM, "_UNRESOLVED-OBJECTS.md")
    with open(out_path, "w", encoding="utf-8") as fh:
        fh.write("\n".join(lines) + "\n")
    print(f"OK: {out_path} written — {len(only_unknown)} rows covered "
          f"({len(by_candidate)} distinct candidate labels, {len(no_candidate)} with no candidate)")


if __name__ == "__main__":
    main()
