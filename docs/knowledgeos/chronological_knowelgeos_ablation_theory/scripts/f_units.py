#!/usr/bin/env python3
"""F-Series content units (protocol v1.1 §F-8A). Deterministic segmentation of a READ-COMPLETE file into content units,
the coverage denominator of the content inventory. It does not replace reading: it runs only for an F-ID whose last
READ-COMPLETE event is for the given run, and it logs its own use to READ-LOG.jsonl (kind UNITS).

  python3 f_units.py --run FR-F3082-001 F3082          # writes ledger/F3082/UNITS.jsonl, prints the unit index
  python3 f_units.py --run FR-F3082-001 --status F3082 # coverage status against the current inventory (no write)
  python3 f_units.py --run FR-F3082-901 --auditor F3082 # the independent auditor's index (after its own full read; no write)

The unit index shows id, kind, pages, definition cue and a 120-character preview; the full text is in the pages already
read. UNITS.jsonl stores spans and hashes only (the original file stays the authoritative source).
"""
import json
import os
import re
import sys

import f_checks as K
import f_common as C


def main(argv):
    if len(argv) < 3 or argv[0] != "--run":
        print(__doc__, file=sys.stderr)
        return 2
    run, fid, status, auditor = argv[1], argv[-1], "--status" in argv, "--auditor" in argv
    man = C.manifest()
    if fid not in man:
        print(__doc__, file=sys.stderr)
        return 2
    if auditor:                                   # C13 §3: the auditor's own index, after the auditor's own full read
        cov = K.coverage(fid, run, "AUDITOR-PAGE-DIGESTS.jsonl") if re.fullmatch(rf"FR-{fid}-9\d\d", run) else None
        if not cov or not cov["complete"]:
            print(f"REFUSED: auditor run {run} has no complete read coverage (pages + AUDITOR-PAGE-DIGESTS)", file=sys.stderr)
            return 1
    else:
        rc = [e for e in C.events(fid) if e["state"] == "READ-COMPLETE"]
        if not rc or rc[-1]["run_id"] != run:
            print(f"REFUSED: {fid} has no READ-COMPLETE event for run {run} (units follow reading, never replace it)",
                  file=sys.stderr)
            return 1
    units = K.units_of(C.content_text(man[fid]))
    if status:
        ok, findings, stats = K.check_inventory(fid)
        print(json.dumps(stats), "\n" + ("CONTENT-COMPLETE" if ok else "NOT CONTENT-COMPLETE:\n  " + "\n  ".join(findings)))
        return 0 if ok else 1
    if not auditor:
        if C.current_state(fid) not in ("READ-COMPLETE", "AUDIT-FAILED", "CONTENT-EXTRACTED"):
            print(f"REFUSED: {fid} is {C.current_state(fid)}; UNITS.jsonl is frozen after CONTENT-EXTRACTED", file=sys.stderr)
            return 1
        with open(C.guard(K.ledger_path(fid, "UNITS.jsonl")), "w", encoding="utf-8") as f:
            for u in units:
                f.write(json.dumps(u, ensure_ascii=False) + "\n")
    C.append_jsonl(K.ledger_path(fid, "READ-LOG.jsonl"), {"run_id": run, "refused": False, "units": True,
                                                          "auditor": auditor, "n_units": len(units), "utc": C.utc(),
                                                          "f_id": fid, "content_sha256": man[fid]["content_sha256"]})
    for u in units:
        flags = "".join((" MATH" if u["math_bearing"] else "", " CUE" if u["definition_cue"] else ""))
        print(f"{u['unit_id']} {u['kind']:<9} p{u['page_start']}{flags} | {u['preview'].replace(chr(10), ' ⏎ ')}")
    print(f"--- {len(units)} units ({sum(1 for u in units if u['kind'] == 'MARKUP')} markup-only, no coverage needed; "
          f"{sum(1 for u in units if u['math_bearing'])} math-bearing, {sum(1 for u in units if u['definition_cue'])} "
          f"definition cues, {sum(1 for u in units if u['kind'] == 'CODE')} code)")
    return 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
