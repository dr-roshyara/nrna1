#!/usr/bin/env python3
"""
MD-055, part 2 — independent test of the source material's own proposed repair (TG-06,
FINAL-THEORY-GAP-REGISTER.md, admitted narrow-scope in MD-054):

    id = H(P, {e.ref}, c, t, Pi)     -- project the mutable e.state OUT of the identity hash,
                                          keep only the (immutable) evidence reference set.

Tests two properties independently, in a clean-room re-derivation:
  (a) does the repair make id STABLE across a state-only mutation (withdrawal)?
  (b) does the repair still make id CHANGE when the evidence reference SET itself changes
      (a genuine content change, which identity should still track)?

A repair that passed (a) but failed (b) would be unsound (it would hide real content changes).
"""

import hashlib
import json


def canonical(obj):
    return json.dumps(obj, sort_keys=True, separators=(",", ":"))


def compute_id_repaired(P, e_refs, c, t, Pi):
    payload = canonical({"P": P, "e_refs": sorted(e_refs), "c": c, "t": t, "Pi": Pi})
    return hashlib.sha256(payload.encode("utf-8")).hexdigest()[:12]


def main():
    P1 = {"E": "Nexus", "D": "Version", "V": "3.69"}
    c1, t1, Pi1 = "production", ["2026-01-02", None], "nexus-api"

    id_before = compute_id_repaired(P1, ["ev-001"], c1, t1, Pi1)
    # state-only mutation: withdrawal changes Evidence.state but not the ref set
    id_after_withdrawal = compute_id_repaired(P1, ["ev-001"], c1, t1, Pi1)
    # genuine content change: a new evidence reference is added
    id_after_new_evidence = compute_id_repaired(P1, ["ev-001", "ev-003"], c1, t1, Pi1)

    print(f"id before withdrawal            = {id_before}")
    print(f"id after withdrawal (state only)= {id_after_withdrawal}")
    print(f"id after a NEW evidence ref     = {id_after_new_evidence}")

    stable_under_withdrawal = id_before == id_after_withdrawal
    changes_under_new_evidence = id_before != id_after_new_evidence

    print(f"\n(a) stable under state-only withdrawal? {stable_under_withdrawal}  (want True)")
    print(f"(b) changes under genuine new evidence?  {changes_under_new_evidence}  (want True)")

    if stable_under_withdrawal and changes_under_new_evidence:
        print("\nCONFIRMED SOUND for this probe: TG-06's repair fixes the withdrawal contradiction")
        print("without hiding genuine content changes.")
    else:
        print("\nNOT CONFIRMED as sound -- see the two booleans above.")


if __name__ == "__main__":
    main()
