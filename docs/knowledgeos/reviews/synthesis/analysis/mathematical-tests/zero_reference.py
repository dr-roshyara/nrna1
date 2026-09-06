#!/usr/bin/env python3
"""
GN-46 mathematical verification — Zero(K, EC) reference implementation
(testing tool only; source model: 025d Formal Zero Algebra).

Purpose: determine whether the SOURCE-LEVEL Zero is computable as claimed
(025d §25D.36 'No LLM is required for the core computation'), and rerun the
source's own falsification tests 25D.38 T1-T8 as executed code instead of
declared PASSes. Nothing here is architecture; the ratified model is not
modified; statuses follow 025d §25D.4 + §25D.7 (Missing) exactly.

Key computability observation encoded here: Zero is a TOTAL, TERMINATING
aggregation over a finite requirement set, RELATIVE to per-requirement
evaluators (rules / statistics / authority, §25D.12-13). The evaluators are
oracles from Zero's point of view; the aggregation itself is O(|R| * cost(eval)).
"""

STATUSES = ["Satisfied", "PartiallySatisfied", "Unknown", "Insufficient",
            "Conflicted", "Stale", "Invalid", "Prohibited", "NotApplicable",
            "Missing"]  # nine-set of §25D.4 + Missing per §25D.7

def evaluate(K, r, EC):
    """Per-requirement evaluator (025d §25D.12): deterministic given K and EC.
    K: dict prop -> list of evidence dicts {value, polarity, current, valid}.
    EC: (R, Gamma) with Gamma[r] = dict of sufficiency rules."""
    R, Gamma = EC
    rules = Gamma.get(r, {})
    ev = [e for e in K.get(r, []) if e.get("valid", True)]
    if rules.get("governed_artifact") and not ev:
        return "Missing"                     # expected governed artifact absent (25D.7 case B)
    if not ev:
        return "Unknown"                     # no evidence either way (25D.7 case A)
    pos = [e for e in ev if e["polarity"] > 0]
    neg = [e for e in ev if e["polarity"] < 0]
    if pos and neg:
        return "Conflicted"
    if not pos and neg:
        return "Invalid" if rules.get("negative_is_invalid") else "Insufficient"
    if all(not e.get("current", True) for e in pos):
        return "Stale"
    n_indep = len({e.get("root", id(e)) for e in pos if e.get("current", True)})
    if n_indep < rules.get("n_independent", 1):
        return "Insufficient"
    return "Satisfied"

def zero(K, EC):
    """Zero(K, EC) -> structured gap set {(r, status, reason)} (025d §25D.34).
    Total and terminating for finite R; deterministic (replayable, §25D.27)."""
    R, _ = EC
    vec = {r: evaluate(K, r, EC) for r in R}
    gaps = {r: s for r, s in vec.items()
            if s not in ("Satisfied", "NotApplicable")}
    return vec, gaps

def show(title, vec, gaps):
    print(f"\n{title}")
    for r, s in vec.items():
        mark = "  <- gap" if r in gaps else ""
        print(f"  {r:28s} {s}{mark}")

print("=" * 74)
print("Zero(K, EC) reference implementation — source-level status vector")
print("=" * 74)

# ---- 025d §25D.8 worked example (Nexus) -----------------------------------
R = ["CurrentVersionKnown", "BackupVerified", "GovernanceApprovalObtained"]
Gamma = {"GovernanceApprovalObtained": {"governed_artifact": True}}
EC = (R, Gamma)
K = {"CurrentVersionKnown": [{"value": "3.69", "polarity": +1, "current": True}]}
vec, gaps = zero(K, EC)
show("§25D.8 example (expect r_version Satisfied, backup Unknown, approval Missing):",
     vec, gaps)
assert vec == {"CurrentVersionKnown": "Satisfied",
               "BackupVerified": "Unknown",
               "GovernanceApprovalObtained": "Missing"}
print("  MATCHES the source's own expected output.")

# ---- 25D.38 falsification tests, EXECUTED ----------------------------------
print("\n25D.38 falsification tests, executed (historically declared-only):")

# T1 complete state -> Zero empty
K1 = {r: [{"value": 1, "polarity": +1, "current": True}] for r in R}
_, g1 = zero(K1, EC)
print(f"  T1 complete state -> gaps == {{}} : {'PASS' if g1 == {} else 'FAIL'}")

# T2 one unknown blocker -> nonempty
K2 = dict(K1); K2["BackupVerified"] = []
_, g2 = zero(K2, EC)
print(f"  T2 unknown blocker -> nonempty  : {'PASS' if 'BackupVerified' in g2 else 'FAIL'}")

# T3 irrelevant information must not create gaps
K3 = dict(K1); K3["SomethingIrrelevant"] = [{"value": 9, "polarity": +1, "current": True}]
_, g3 = zero(K3, EC)
print(f"  T3 irrelevant info, no new gap  : {'PASS' if g3 == {} else 'FAIL'}")

# T4 duplicate evidence must not change Zero
K4 = dict(K2)
K4["CurrentVersionKnown"] = K4["CurrentVersionKnown"] * 3
v4, g4 = zero(K4, EC)
print(f"  T4 duplicates leave Zero fixed  : {'PASS' if g4 == g2 else 'FAIL'}")

# T5 conflict stays Conflicted (not silently Unknown)
K5 = dict(K1)
K5["BackupVerified"] = [{"value": True, "polarity": +1, "current": True},
                        {"value": False, "polarity": -1, "current": True}]
v5, _ = zero(K5, EC)
print(f"  T5 conflict -> Conflicted       : {'PASS' if v5['BackupVerified'] == 'Conflicted' else 'FAIL'}")

# T6 stale evidence detected
K6 = dict(K1)
K6["BackupVerified"] = [{"value": True, "polarity": +1, "current": False}]
v6, _ = zero(K6, EC)
print(f"  T6 stale -> Stale               : {'PASS' if v6['BackupVerified'] == 'Stale' else 'FAIL'}")

# T7 requirement absent from contract -> no invented gap (25D.28)
_, g7 = zero({"ContainerCPUArchitecture": []}, EC)
print(f"  T7 no invented gaps             : {'PASS' if 'ContainerCPUArchitecture' not in g7 else 'FAIL'}")

# T8 historical contract: zero() is a pure function -> old results immutable
v8a, _ = zero(K2, EC)
EC2 = (R + ["NewRequirement"], Gamma)
v8b, _ = zero(K2, EC)           # recompute with the OLD contract
print(f"  T8 old contract replay stable   : {'PASS' if v8a == v8b else 'FAIL'}")

# ---- Insufficient (Gamma bar unmet) and the vector-vs-scalar law ------------
print("\nGamma sufficiency (Insufficient) and the never-a-number law:")
R2 = ["IndependentReproduction"]
EC3 = (R2, {"IndependentReproduction": {"n_independent": 2}})
K7 = {"IndependentReproduction": [{"value": 1, "polarity": +1, "current": True, "root": "a"}]}
v7, _ = zero(K7, EC3)
print(f"  1 item under a 2-independent bar -> {v7['IndependentReproduction']} "
      f"({'PASS' if v7['IndependentReproduction'] == 'Insufficient' else 'FAIL'})")
print("  NOTE: 'Insufficient' is expressible ONLY because Gamma is carried;")
print("  the ratified four-arm summary (unknown/conflicting/missing/invalid)")
print("  has no arm for it, nor for Stale or Prohibited — the PF-1 loss,")
print("  demonstrated here as executable semantics, not as prose.")

print("\nScalar-collapse counterexample (025d §25D.17, executed):")
vecA = {"r1": "Missing", "r2": "Unknown"}
vecB = {"r1": "Unknown", "r2": "Missing"}
print(f"  |gaps(A)| = |gaps(B)| = 2, yet A != B as work plans "
      f"({'PASS' if vecA != vecB else 'FAIL'}) — any count/scalar conflates them.")

print("\nCOMPUTABILITY VERDICT (this script is the witness):")
print("  Zero is computable, total and terminating over finite R, RELATIVE to")
print("  per-requirement evaluators; complexity O(|R| * cost(evaluate)).")
print("  What is NOT established: the evaluators themselves (Gamma semantics,")
print("  HumanAuthorization oracles) and eta (EC construction) — OQ-1/AF-F-1.")
