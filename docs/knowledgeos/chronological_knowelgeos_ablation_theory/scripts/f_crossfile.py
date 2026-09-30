#!/usr/bin/env python3
"""F-Series incremental cross-file candidates (contract C05). Read-only. Lists the deterministic candidates the ANALYZED
gate requires to be dispositioned: normalized term keys (inventory TERM/DEFINITION terms + contribution labels) that this
F-ID shares with every EARLIER, AUDITED F-ID in list order. No look-ahead; no identity decision (a candidate is a
question, never an answer).

  python3 f_crossfile.py F3083
"""
import json
import sys

import f_checks as K


def main(argv):
    if len(argv) != 1:
        print(__doc__, file=sys.stderr)
        return 2
    cands = K.cross_file_candidates(argv[0])
    for c in cands:
        print(json.dumps(c, ensure_ascii=False))
    print(f"--- {len(cands)} candidate(s)", file=sys.stderr)
    return 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
