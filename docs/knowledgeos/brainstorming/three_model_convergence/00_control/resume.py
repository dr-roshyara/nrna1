#!/usr/bin/env python3
"""Deterministic resume verifier for the three-model convergence corpus pass.

Contract (MD-007):
  last_completed_sequence = N   <=>  records 0001..N ALL exist, complete, on disk
  next_sequence           = N + 1
  no file is skipped, no file is silently reclassified

Exit codes: 0 = consistent, resume at next_sequence.  2 = INCONSISTENT, stop and repair.
Run BEFORE processing any file. Never resume from memory, from context, or from a summary.
"""
import csv
import pathlib
import sys

ROOT = pathlib.Path(__file__).resolve().parent.parent
MAN = ROOT / "00_control" / "reading-manifest.tsv"
PROG = ROOT / "00_control" / "progress.tsv"
PF = ROOT / "01_source-analysis" / "per-file"

REQUIRED_MD = [
    "## Source",
    "## Primary Classification (PROVISIONAL",
    "## Secondary Classifications",
    "## Why This Classification?",
    "## What Is This Document About?",
    "## Concepts Introduced",
    "## New Contribution",
    "## Relationship to Earlier Research",
    "## Undefined Concepts",
    "## Research Status",
    "## Importance",
    "## Source Traceability",
    "## TIER 2 — DEEP ANALYSIS",
    "**Trigger status:",
    "## Classification Revision History",
]

# MD-008: routine files get a CONCISE Tier-1 record. Marked by this literal string near the top
# of the file. Concise records are held to a smaller, still-mandatory section set - coverage is
# never optional, only verbosity is reduced.
CONCISE_MARKER = "Concise Tier-1 record"
REQUIRED_MD_CONCISE = [
    "## Source",
    "## Primary Classification (PROVISIONAL",
    "## Secondary Classifications",
    "## Why This Classification?",
    "## What Is This Document About?",
    "## New Contribution",
    "## Relationship to Earlier Research",
    "## Undefined Concepts",
    "## Research Status",
    "## Importance",
    "## Source Traceability",
    "## TIER 2 — DEEP ANALYSIS",
    "**Trigger status:",
    "## Classification Revision History",
]

# MD-013 (from seq 0055 onward): YAML-primary lean format. The .md is a thin wrapper - substance
# lives in the .yaml. Coverage is still mechanically checked; verbosity is not required.
ULTRA_CONCISE_MARKER = "Ultra-concise record (MD-013)"
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

REQUIRED_YAML = [
    "sequence:",
    "path:",
    "primary_model_initial:",
    "primary_model_final:",
    "confidence:",
    "lineage_provisional:",
    "secondary_models:",
    "status:",
    "maturity:",
    "importance:",
    "introduces:",
    "undefined_concepts:",
    "bridges:",
    "tier2_triggered:",
    "tier2_reason:",
]


def fail(msg):
    print("INCONSISTENT: " + msg)
    sys.exit(2)


def main():
    manifest = {}
    with MAN.open() as fh:
        for row in csv.DictReader(fh, delimiter="\t"):
            manifest[row["seq"]] = row["path"]
    total = len(manifest)

    progress = {}
    with PROG.open() as fh:
        for row in csv.DictReader(fh, delimiter="\t"):
            progress[row["seq"]] = row

    if set(progress) != set(manifest):
        fail("progress.tsv and reading-manifest.tsv do not cover the same sequences")

    # MD-010 corpus boundary: EXCLUDED sequences (verification/synthesis/falsification/corpus/
    # classification under brainstorming/) require no per-file record and are scattered arbitrarily
    # through the log (not contiguous with each other or with DONE). The frontier N is therefore the
    # largest integer such that EVERY sequence 1..N has status DONE or EXCLUDED - found by scanning
    # forward from 1, not by sorting the union (a sorted union is not necessarily a prefix, since
    # EXCLUDED sequences ahead of a still-PENDING primary file must not advance the frontier past it).
    done = sorted(s for s, r in progress.items() if r["status"] == "DONE")
    total_all = len(manifest)
    N = 0
    while N < total_all:
        seq = "%04d" % (N + 1)
        st = progress[seq]["status"]
        if st in ("DONE", "EXCLUDED"):
            N += 1
        else:
            break
    handled_prefix = ["%04d" % i for i in range(1, N + 1)]
    # sanity: no DONE or EXCLUDED sequence may exist BEYOND a PENDING one still inside the prefix
    # range check above already guarantees this for the frontier; separately confirm no DONE record
    # exists past N (out-of-order processing), checked in step 5 below.
    nxt = N + 1
    n_done = sum(1 for s in handled_prefix if progress[s]["status"] == "DONE")
    n_excluded = sum(1 for s in handled_prefix if progress[s]["status"] == "EXCLUDED")
    if n_done != len(done):
        # some DONE records exist beyond the contiguous frontier - a gap was left by an EXCLUDED-
        # marked PENDING file being skipped incorrectly, or an out-of-order write. Surface it.
        stray = sorted(set(done) - set(s for s in handled_prefix if progress[s]["status"] == "DONE"))
        fail("DONE records exist beyond the contiguous handled frontier (gap before them): %s"
             % stray[:10])

    # 2. every DONE sequence has BOTH records, and both are complete
    for s in done:
        md = PF / (s + ".md")
        yml = PF / (s + ".yaml")
        if not md.exists():
            fail("%s marked DONE but %s.md is missing" % (s, s))
        if not yml.exists():
            fail("%s marked DONE but %s.yaml is missing" % (s, s))
        mt = md.read_text()
        yt = yml.read_text()
        is_ultra = ULTRA_CONCISE_MARKER in mt
        if is_ultra:
            required_md = REQUIRED_MD_ULTRA
        elif CONCISE_MARKER in mt:
            required_md = REQUIRED_MD_CONCISE
        else:
            required_md = REQUIRED_MD
        for h in required_md:
            if h not in mt:
                fail("%s.md is missing a mandatory section: %r" % (s, h))
        required_yaml = REQUIRED_YAML_ULTRA if is_ultra else REQUIRED_YAML
        for k in required_yaml:
            if k not in yt:
                fail("%s.yaml is missing a mandatory field: %r" % (s, k))
        # 3. the record must cite its manifest path (no silent substitution)
        if manifest[s] not in mt:
            fail("%s.md does not cite its manifest path %s" % (s, manifest[s]))
        if manifest[s] not in yt:
            fail("%s.yaml does not cite its manifest path %s" % (s, manifest[s]))
        # 4. provisional discipline: no FINAL classification during pass 1 (MD-004).
        # Ultra-concise records (MD-013) carry only `model.primary` - provisional by construction,
        # since finalization is a separate later pass over all records, never a per-file field here.
        if not is_ultra and "primary_model_final: pending_global_reclassification" not in yt:
            fail("%s.yaml assigns a FINAL classification during pass 1 - forbidden by MD-004" % s)

    # 5. no orphan records beyond N (would mean out-of-order processing)
    for p in PF.glob("[0-9][0-9][0-9][0-9].md"):
        if p.stem > ("%04d" % N):
            fail("record %s exists beyond last_completed_sequence %04d - out-of-order processing"
                 % (p.name, N))

    # corpus_tier: read from the manifest (MD-010/MD-011). PRIMARY = brainstorming/ minus the five
    # excluded subdirectories. Everything else (OUT_OF_SCOPE_ROOT, EXCLUDED_*) is not primary corpus.
    tier = {}
    with MAN.open() as fh:
        for row in csv.DictReader(fh, delimiter="\t"):
            tier[row["seq"]] = row.get("corpus_tier", "PRIMARY")

    n_primary_total = sum(1 for s in manifest if tier.get(s) == "PRIMARY")
    n_primary_done = sum(1 for s in done if tier.get(s) == "PRIMARY")
    n_adjacent_done = sum(1 for s in done if tier.get(s) != "PRIMARY")
    n_excluded_total = total_all - n_primary_total

    print("CONSISTENT")
    print("last_handled_sequence   = %04d  (DONE=%d [primary=%d, adjacent/out-of-scope=%d], EXCLUDED=%d)"
          % (N, n_done, n_primary_done, n_adjacent_done, n_excluded))
    print("next_sequence           = %04d" % nxt)
    if nxt <= total:
        print("next_path               = %s" % manifest["%04d" % nxt])
        print("remaining_primary       = %d of %d (N_primary total, brainstorming/-only scope)"
              % (n_primary_total - n_primary_done, n_primary_total))
        print("excluded_total          = %d (N_excluded: 5 derived subdirs + docs/knowledgeos/ root)"
              % n_excluded_total)
        if n_adjacent_done:
            print("adjacent_completed      = %d (root-level files analyzed before the brainstorming/-only "
                  "scope correction; retained, not primary corpus)" % n_adjacent_done)
    else:
        print("SEQUENTIAL PASS COMPLETE - global reclassification may now open (MD-004).")


if __name__ == "__main__":
    main()
