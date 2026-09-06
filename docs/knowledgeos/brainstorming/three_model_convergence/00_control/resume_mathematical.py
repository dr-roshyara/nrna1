#!/usr/bin/env python3
"""Deterministic resume verifier for the mathematical_ideas_that_can_be_implemented/ reading pass.

Parallel to 00_control/resume.py (the brainstorming/-only primary corpus pass, now
SEQUENTIAL PASS COMPLETE). This is a SEPARATE, later-commissioned pass over
mathematical_part_files.log's exact listing (282 entries, reading order = log order),
kept in its own manifest/progress files and its own per-file output directory
(01_source-analysis/per-file-mathematical/) so it never touches or reopens the
already-completed brainstorming-corpus pass.

Sequence IDs are "M0001".."M0282" (M-prefixed) to keep them visually and mechanically
distinct from the brainstorming corpus's 0001-2376 sequence space.

Exit codes: 0 = consistent, resume at next_sequence.  2 = INCONSISTENT, stop and repair.
"""
import csv
import pathlib
import sys

ROOT = pathlib.Path(__file__).resolve().parent.parent
MAN = ROOT / "00_control" / "mathematical-manifest.tsv"
PROG = ROOT / "00_control" / "mathematical-progress.tsv"
PF = ROOT / "01_source-analysis" / "per-file-mathematical"

REQUIRED_MD_ULTRA = [
    "## Source",
    "## Classification",
    "## Tier 2",
    "## Classification Revision History",
]
REQUIRED_YAML_ULTRA = [
    "sequence:",
    "path:",
    "model:",
    "  primary:",
    "source_role:",
    "importance:",
    "confidence:",
    "about:",
    "tier2_triggered:",
    "tier2_reason:",
]


def fail(msg):
    print("INCONSISTENT: " + msg)
    sys.exit(2)


def main():
    manifest = {}
    tier = {}
    with MAN.open() as fh:
        for row in csv.DictReader(fh, delimiter="\t"):
            manifest[row["seq"]] = row["path"]
            tier[row["seq"]] = row["tier"]
    total = len(manifest)

    progress = {}
    with PROG.open() as fh:
        for row in csv.DictReader(fh, delimiter="\t"):
            progress[row["seq"]] = row["status"]

    if set(progress) != set(manifest):
        fail("mathematical-progress.tsv and mathematical-manifest.tsv do not cover the same sequences")

    # Frontier: same forward-scan contiguity rule as the main resume.py.
    N = 0
    while N < total:
        seq = "M%04d" % (N + 1)
        st = progress[seq]
        if st in ("DONE", "EXCLUDED"):
            N += 1
        else:
            break
    nxt = N + 1
    handled_prefix = ["M%04d" % i for i in range(1, N + 1)]
    n_done = sum(1 for s in handled_prefix if progress[s] == "DONE")
    n_excluded = sum(1 for s in handled_prefix if progress[s] == "EXCLUDED")

    done_all = sorted(s for s, st in progress.items() if st == "DONE")
    if n_done != len(done_all):
        stray = sorted(set(done_all) - set(s for s in handled_prefix if progress[s] == "DONE"))
        fail("DONE records exist beyond the contiguous handled frontier (gap before them): %s" % stray[:10])

    for s in done_all:
        md = PF / (s + ".md")
        yml = PF / (s + ".yaml")
        if not md.exists():
            fail("%s marked DONE but %s.md is missing" % (s, s))
        if not yml.exists():
            fail("%s marked DONE but %s.yaml is missing" % (s, s))
        mt = md.read_text()
        yt = yml.read_text()
        for h in REQUIRED_MD_ULTRA:
            if h not in mt:
                fail("%s.md is missing a mandatory section: %r" % (s, h))
        for k in REQUIRED_YAML_ULTRA:
            if k not in yt:
                fail("%s.yaml is missing a mandatory field: %r" % (s, k))
        if manifest[s] not in mt:
            fail("%s.md does not cite its manifest path %s" % (s, manifest[s]))
        if manifest[s] not in yt:
            fail("%s.yaml does not cite its manifest path %s" % (s, manifest[s]))

    for p in PF.glob("M[0-9][0-9][0-9][0-9].md"):
        if p.stem > ("M%04d" % N):
            fail("record %s exists beyond last_completed_sequence M%04d - out-of-order processing" % (p.name, N))

    n_dup_total = sum(1 for s in manifest if tier.get(s) == "DUPLICATE")
    n_primary_total = total - n_dup_total

    print("CONSISTENT")
    print("last_handled_sequence   = M%04d  (DONE=%d, EXCLUDED=%d)" % (N, n_done, n_excluded))
    print("next_sequence           = M%04d" % nxt)
    if nxt <= total:
        print("next_path               = %s (%s)" % (manifest["M%04d" % nxt], tier["M%04d" % nxt]))
        print("remaining               = %d of %d total (%d primary-needing-record, %d duplicate)"
              % (total - N, total, n_primary_total, n_dup_total))
    else:
        print("MATHEMATICAL-PART SEQUENTIAL PASS COMPLETE.")


if __name__ == "__main__":
    main()
