#!/usr/bin/env python3
"""P1c (A9) — build 12-SOURCE-REGISTER.md: one row per source_id in the roadmap
(00-ROADMAP-VALIDATED.jsonl), including duplicates, firewalled, and unresolvable
entries — not just the 2779 unique content-bearing files. Joins in date evidence and
objects_touched from 02-FILES.jsonl for CONTENT/FIREWALL-LIMITED rows.

`objects_touched` is shown truncated (first 8 + "...(+N more)") for table readability;
03-CONTRIBUTIONS.jsonl / 02-FILES.jsonl remain the fully faithful record — this register
is a navigation index, not a duplicate of the evidence.
"""
import json
import os
import subprocess

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def fmt_objs(objs):
    if not objs:
        return ""
    objs = list(objs)
    if len(objs) <= 8:
        return "; ".join(objs)
    return "; ".join(objs[:8]) + f" …(+{len(objs) - 8} more)"


def main():
    roadmap = load_jsonl(os.path.join(CR, "00-ROADMAP-VALIDATED.jsonl"))
    files_by_sid = {r["source_id"]: r for r in load_jsonl(os.path.join(CR, "02-FILES.jsonl"))}
    overlap = load_jsonl(os.path.join(CR, "08-OVERLAP-REGISTER.jsonl"))

    overlap_by_sid = {}
    for r in overlap:
        overlap_by_sid.setdefault(r["a"], []).append((r["b"], r["kind"], r["similarity"]))
        overlap_by_sid.setdefault(r["b"], []).append((r["a"], r["kind"], r["similarity"]))

    roadmap.sort(key=lambda r: int(r["source_id"][1:]))

    counts = {"RESOLVED": 0, "REPAIRED": 0, "UNRESOLVABLE": 0, "EXACT-DUP": 0, "UNIQUE": 0}

    lines = []
    lines.append("# 12-SOURCE-REGISTER — one row per roadmap entry (P1c)\n")
    lines.append(
        "Built by `scripts/build_source_register.py` from `00-ROADMAP-VALIDATED.jsonl` "
        "(source/dup identity) + `02-FILES.jsonl` (content/date evidence) + "
        "`08-OVERLAP-REGISTER.jsonl` (near-dup findings). `objects_touched` is truncated "
        "here for readability — see `03-CONTRIBUTIONS.jsonl` for the full record. "
        "This register is navigation only; identity is `source_id + commit + sha256` (R11), "
        "never `path` alone.\n"
    )
    lines.append(f"Corpus snapshot commit (as recorded at P0): `{open(os.path.join(CR, '00-CORPUS-SNAPSHOT.txt')).read().strip()}`\n")
    lines.append(
        "| source_id | path | resolve | dup | sha256 (short) | mtime | best_historical_date (basis) "
        "| status | provenance | overlap findings | objects_touched (truncated) |"
    )
    lines.append("|---|---|---|---|---|---|---|---|---|---|---|")

    for r in roadmap:
        sid = r["source_id"]
        resolve = r["resolve"]
        counts[resolve] = counts.get(resolve, 0) + 1
        dup = r.get("dup") or ""
        if dup == "UNIQUE":
            counts["UNIQUE"] += 1
        elif dup.startswith("EXACT-DUPLICATE-OF"):
            counts["EXACT-DUP"] += 1

        path = r.get("path") or ""
        sha = (r.get("sha256") or "")[:12]
        mtime = r.get("file_mtime") or ""

        f = files_by_sid.get(sid)
        if f:
            bhd = f.get("best_historical_date")
            basis = f.get("best_historical_date_basis")
            bhd_str = f"{bhd} ({basis})" if bhd else ""
            status = f["status"]
            provenance = f.get("provenance") or ""
            objs = fmt_objs(f.get("objects_touched") or [])
        else:
            bhd_str = ""
            status = resolve if resolve == "UNRESOLVABLE" else ("EXACT-DUPLICATE" if dup.startswith("EXACT") else "")
            provenance = ""
            objs = ""

        ov = overlap_by_sid.get(sid)
        ov_str = ""
        if ov:
            ov_str = "; ".join(f"{other}:{kind}({sim})" for other, kind, sim in ov)

        # escape pipes for markdown table safety
        def esc(s):
            return str(s).replace("|", "\\|").replace("\n", " ")

        lines.append(
            f"| {sid} | {esc(path)} | {resolve} | {esc(dup)} | {sha} | {mtime} | "
            f"{esc(bhd_str)} | {status} | {provenance} | {esc(ov_str)} | {esc(objs)} |"
        )

    lines.append("")
    lines.append("## Summary counts")
    lines.append(f"- Total roadmap entries: {len(roadmap)}")
    lines.append(f"- RESOLVED: {counts.get('RESOLVED', 0)} · REPAIRED: {counts.get('REPAIRED', 0)} · "
                  f"UNRESOLVABLE: {counts.get('UNRESOLVABLE', 0)}")
    lines.append(f"- UNIQUE (content-bearing, extracted): {counts.get('UNIQUE', 0)} · "
                  f"EXACT-DUPLICATE (pointer only, not re-extracted per R3): {counts.get('EXACT-DUP', 0)}")
    lines.append(f"- Near-duplicate/partial-overlap pairs found by mechanical scan: {len(overlap)}")

    out_path = os.path.join(CR, "12-SOURCE-REGISTER.md")
    with open(out_path, "w", encoding="utf-8") as fh:
        fh.write("\n".join(lines) + "\n")
    print(f"OK: {out_path} written — {len(roadmap)} rows")
    print(counts)


if __name__ == "__main__":
    main()
