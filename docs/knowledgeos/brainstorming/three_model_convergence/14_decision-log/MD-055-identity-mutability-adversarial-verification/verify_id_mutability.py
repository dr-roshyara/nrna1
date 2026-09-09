#!/usr/bin/env python3
"""
MD-055 — independent adversarial verification of the claimed identity/mutability
contradiction in the MD-054 (VERIFY SESSION) reconstruction:

    Assertion = (id, P, e, c, t, Pi)
    id = H(P, e, c, t, Pi)                      -- content-addressed hash
    e subset-of Evidence, Evidence.state mutable (active/withdrawn/invalidated)
    StructuralValid(K) requires: unique ids AND no dangling R endpoints AND
                                  no inverted intervals AND acyclic(DAG families)

Claim under test (source: KNOWLEDGE-STATE-FINAL-AUDIT.md / MATHEMATICAL-COMPLETENESS-AUDIT.md,
both already narrow-scope admitted, MD-054): withdrawing one evidence item on an assertion
re-keys that assertion's own identity hash, and any relation edge in R that referenced the
old id now dangles -- i.e. a legal, in-theory operation (withdraw) produces a
StructurallyInvalid K, which is an internal contradiction in the theory AS STATED.

This script implements ONLY the definitions as stated in the admitted narrative material
(re-derived independently, not copied from any verifier scratchpad -- none was available to
read). No corpus material is executed; no repository code is touched. This is a clean-room
re-derivation from the stated formulas, run now for the first time by this reconstruction.
"""

import hashlib
import json


def canonical(obj):
    """Deterministic serialization for hashing -- sorted keys, no whitespace ambiguity."""
    return json.dumps(obj, sort_keys=True, separators=(",", ":"))


def compute_id(P, e, c, t, Pi):
    """id = H(P, e, c, t, Pi) -- content-addressed hash over the five other fields."""
    payload = canonical({"P": P, "e": e, "c": c, "t": t, "Pi": Pi})
    return hashlib.sha256(payload.encode("utf-8")).hexdigest()[:12]


def make_assertion(P, e, c, t, Pi):
    aid = compute_id(P, e, c, t, Pi)
    return {"id": aid, "P": P, "e": e, "c": c, "t": t, "Pi": Pi}


def structural_valid(assertions_by_id, relations):
    """
    StructuralValid(K) (as stated): unique ids (guaranteed by dict-keying here) AND
    no dangling R endpoints AND no inverted intervals (not exercised by this probe)
    AND acyclic DAG families (not exercised by this probe -- one edge only).
    Returns (ok, reason).
    """
    for (frm, to, ty) in relations:
        if frm not in assertions_by_id:
            return False, f"dangling endpoint: relation source id {frm!r} not in K.A"
        if to not in assertions_by_id:
            return False, f"dangling endpoint: relation target id {to!r} not in K.A"
    return True, "ok"


def main():
    print("=== MD-055: independent re-derivation of the id/mutable-e.state claim ===\n")

    # --- Step 1: construct A1 with one active, supporting evidence item ---
    P1 = {"E": "Nexus", "D": "Version", "V": "3.69"}
    e1 = [{"ref": "ev-001", "polarity": "supports", "state": "active"}]
    c1 = "production"
    t1 = ["2026-01-02", None]
    Pi1 = "nexus-api"

    A1 = make_assertion(P1, e1, c1, t1, Pi1)
    id_before = A1["id"]
    print(f"A1 constructed. id (before withdrawal) = {id_before}")

    # --- Step 2: construct A2 (a distinct assertion) and relate it to A1 ---
    P2 = {"E": "Nexus", "D": "Version", "V": "3.70"}
    e2 = [{"ref": "ev-002", "polarity": "supports", "state": "active"}]
    A2 = make_assertion(P2, e2, "production", ["2026-06-01", None], "changelog")

    K_assertions = {A1["id"]: A1, A2["id"]: A2}
    K_relations = [(A2["id"], A1["id"], "supersedes")]  # A2 supersedes A1, by id

    ok, reason = structural_valid(K_assertions, K_relations)
    print(f"StructuralValid(K) immediately after asserting the relation: {ok} ({reason})")
    assert ok, "sanity check failed -- K should be valid before any mutation"

    # --- Step 3: withdraw A1's one evidence item (a stated-legal operation: e.state mutates) ---
    A1_after = dict(A1)
    A1_after["e"] = [{"ref": "ev-001", "polarity": "supports", "state": "withdrawn"}]
    # id is recomputed from the (now-changed) e, exactly as the stated formula id=H(P,e,c,t,Pi) requires
    id_after = compute_id(A1_after["P"], A1_after["e"], A1_after["c"], A1_after["t"], A1_after["Pi"])
    A1_after["id"] = id_after

    print(f"\nA1 evidence withdrawn (state: active -> withdrawn).")
    print(f"id (after withdrawal)  = {id_after}")
    print(f"SAME id before/after?  = {id_before == id_after}")

    # --- Step 4: replace A1 in K with its post-withdrawal self, exactly as a legal update would ---
    K_assertions_after = {A2["id"]: A2, A1_after["id"]: A1_after}
    # K_relations is UNCHANGED -- nothing in the theory as stated re-writes existing R edges
    # when an assertion's own id changes; R edges store the id value they were given at creation.

    ok2, reason2 = structural_valid(K_assertions_after, K_relations)
    print(f"\nStructuralValid(K) AFTER the legal withdrawal operation: {ok2} ({reason2})")

    print("\n=== VERDICT ===")
    if id_before != id_after and not ok2:
        print("CONFIRMED: the claimed contradiction reproduces under a clean-room re-derivation.")
        print("A single, stated-legal operation (evidence withdrawal) changes an assertion's own")
        print("content-addressed identity and leaves an existing, previously-valid relation edge")
        print("referencing an id that no longer resolves to any element of K.A -- StructuralValid(K)")
        print("fails immediately afterward, using only the definitions as stated (no invented rule).")
    else:
        print("NOT CONFIRMED as stated -- see id_before/id_after and ok2 above.")


if __name__ == "__main__":
    main()
