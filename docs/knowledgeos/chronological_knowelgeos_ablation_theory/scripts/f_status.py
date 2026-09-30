#!/usr/bin/env python3
"""F-Series restart point (§F-9): answers "the last completely audited F-file was F####; the next file is F####"
from the repository artifacts alone. Read-only.

  python3 f_status.py            # summary + next F-ID
  python3 f_status.py F3082      # full event history of one F-ID
"""
import collections
import sys

import f_common as C
import f_integrity as I


def main(argv):
    man = C.manifest()
    ok, f = I.verify_chain()                            # v1.2 (F-07): a broken ledger has no trustworthy restart point
    print("state-ledger chain:", "VERIFIED" if ok else "BROKEN — " + "; ".join(f[:5]))
    if not ok:
        return 1
    if argv:
        for e in C.events(argv[0]):
            print(e["seq"], e["utc"], f"{e['from']} -> {e['state']}", e["run_id"], e.get("note") or "")
        return 0
    cur = {f: C.current_state(f) for f in man}
    last_audited, nxt = None, None
    for f in man:                                   # list order
        if cur[f] == "AUDITED":
            last_audited = f
        else:
            nxt = f
            break
    print("state counts:", dict(collections.Counter(cur.values())))
    print(f"last contiguous AUDITED F-ID (list order): {last_audited}")
    print(f"next F-ID to process: {nxt} (current state {cur.get(nxt)}, list position {man[nxt]['list_order'] if nxt else '-'})")
    return 0


if __name__ == "__main__":
    sys.exit(main(sys.argv[1:]))
