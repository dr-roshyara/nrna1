#!/usr/bin/env python3
"""
Mandate §3 (readiness graph), §14 (critical path), §15 (minimum implementable subset).

METHOD  Build the construct dependency graph from the evidence in
        handoff/05 + TG-register + GN-77/84. Then compute:
          (a) the transitive closure of what a REFERENCE KERNEL requires;
          (b) which of those are BLOCKED, and by what class;
          (c) the topological order of blockers -> the critical path.
DECIDES NOTHING. It computes consequences of recorded statuses.
"""
import collections
def hr(t): print("\n"+"="*78+f"\n{t}\n"+"="*78)

# construct -> (depends_on, lane_status, block_class_or_None)
#   block classes: D derivation · O operation · T transformation · G governance
#                  I implementation · EC empirical · A architecture
G = {
 # --- structural core: no blockers
 "Identity":      ([],                                  "clear-all-lanes", "D?"),   # TG-06 contested
 "Equality":      (["Identity"],                        "clear-all-lanes", "D"),
 "Proposition":   ([],                                  "formal",          "D"),    # D-6 two types
 "Assertion":     (["Proposition","Identity","Provenance"], "formal",      None),
 "Provenance":    ([],                                  "formal",          None),
 "RelationType":  ([],                                  "formal",          None),
 "Relation":      (["Assertion","RelationType"],        "formal",          "D"),    # D-5 3 vs 8 field
 "K":             (["Assertion","Relation"],            "formal+L5",       "A"),    # two rival K
 "Lineage":       (["Relation","Provenance"],            "clear-all-lanes", None),
 "Orphan":        (["Relation"],                        "clear-all-lanes", None),
 "History":       (["K"],                               "formal",          "I"),
 # --- epistemic layer
 "Evidence":      (["Assertion"],                       "formal",          "D"),    # TG-08 no identity
 "Qualification": (["Evidence","Policy"],               "formal",          "D"),    # TG-14 no body
 "Sigma":         (["Evidence"],                        "formal",          "D"),    # TG-10/12
 "Q_t":           (["Proposition"],                     "formal",          "EC"),
 "Missingness":   (["Q_t","Sigma"],                     "formal",          "EC"),
 "Assessment":    (["Evidence","Policy","Sigma"],       "formal",          "D"),    # TG-13 two signatures
 "Measurement":   (["Assessment"],                      "formal-only",     "D"),
 # --- governance layer
 "Policy":        ([],                                  "RATIFIED",        "G"),    # GC-1
 "Authority":     ([],                                  "narrow-ratified", "D"),    # TG-01/07
 "Authorization": (["Authority","Policy"],              "formal-only",     "I"),
 "Gamma":         (["Authority"],                       "formal+L5p",      "I"),
 # --- the severed layer
 "InvariantReg":  (["K","Sigma","Policy"],              "NOT ENUMERATED",  "D"),    # G-67 = ℐ
 "O_core":        (["InvariantReg"],                    "NOT FROZEN",      "O"),    # necessity test needs R_mandatory
 "Operations":    (["O_core"],                          "0 canonical",     "O"),
 "Transformation":(["Operations","Identity","Equality"],"no body",         "T"),    # TG-09 + C-5#3
 "Rejection":     (["Transformation"],                  "CONTRADICTORY",   "T"),    # AF-F-31
 "Replay":        (["Transformation","History"],        "formal",          "T"),
 "Determination": (["Assessment","Authority"],          "formal",          "D"),
}

hr("A. THE READINESS DEPENDENCY GRAPH")
def closure(roots):
    seen, stack = set(), list(roots)
    while stack:
        n = stack.pop()
        if n in seen: continue
        seen.add(n); stack += G[n][0]
    return seen

# What does a REFERENCE KERNEL minimally require?
# A kernel must be able to: hold state, transition it, and replay.
KERNEL_CAPABILITIES = {
 "hold a knowledge state":      ["K"],
 "transition it legally":       ["Transformation"],
 "reject illegally":            ["Rejection"],
 "replay from history":         ["Replay"],
}
need = closure([c for cs in KERNEL_CAPABILITIES.values() for c in cs])
print(f"  a reference kernel's transitive requirement set: {len(need)} constructs")
print(f"    {sorted(need)}")
notneed = sorted(set(G) - need)
print(f"\n  NOT required by the kernel ({len(notneed)}): {notneed}")

hr("B. WHICH REQUIRED CONSTRUCTS ARE BLOCKED, AND BY WHAT CLASS")
byclass = collections.defaultdict(list)
for n in sorted(need):
    cls = G[n][2]
    if cls: byclass[cls].append(f"{n} [{G[n][1]}]")
for cls in sorted(byclass):
    print(f"\n  class {cls}:")
    for x in byclass[cls]: print(f"     {x}")
clear = [n for n in sorted(need) if not G[n][2]]
print(f"\n  required AND unblocked: {clear}")

hr("C. TOPOLOGICAL ORDER OF THE BLOCKERS  ->  THE CRITICAL PATH")
# topo sort restricted to the required set
indeg = {n: sum(1 for d in G[n][0] if d in need) for n in need}
order, ready = [], sorted([n for n in need if indeg[n]==0])
while ready:
    n = ready.pop(0); order.append(n)
    for m in sorted(need):
        if n in G[m][0]:
            indeg[m] -= 1
            if indeg[m]==0: ready.append(m)
print("  dependency order (earliest first), blockers marked:")
for i,n in enumerate(order,1):
    cls = G[n][2]
    print(f"   {i:>2}. {n:<16}{'  <-- BLOCKED ['+cls+']' if cls else ''}")

hr("D. THE FIRST IRREDUCIBLE BLOCKER ON THE PATH")
first = [n for n in order if G[n][2]]
print(f"  blockers in dependency order: {[n for n in first]}")
print(f"""
  The earliest blockers with NO blocked dependency are the true starting points:""")
roots = [n for n in first if not any(G[d][2] for d in G[n][0] if d in need)]
for n in roots: print(f"     {n:<16} class {G[n][2]:<3} status: {G[n][1]}")
print(f"""
  READING: everything downstream of these cannot legitimately begin.
  Note the chain  InvariantReg -> O_core -> Operations -> Transformation -> Rejection/Replay:
  it is LINEAR. There is no parallel route to a transition function.""")

hr("E. MINIMUM IMPLEMENTABLE SUBSET  (§15)")
print(f"""  Required (transitively) for a reference kernel : {len(need)}
  Of those, currently unblocked                  : {len(clear)}
  Of those, blocked                              : {len(need)-len(clear)}

  JUSTIFIED EXCLUSIONS -- constructs the research programme produced that a
  kernel does NOT require:""")
for n in notneed: print(f"     {n:<16} {G[n][1]}")
print("""
  Each exclusion is a DEPENDENCY fact: no construct in `need` depends on it.
  Measurement, Missingness, Determination, Q_t, Assessment, Qualification,
  Gamma and Authorization are all reachable ONLY from constructs the kernel
  does not require -- so a kernel can be built without them, and a kernel that
  has them is not thereby more correct.""")

hr("F. SENSITIVITY — the one modelling edge that changes the answer")
print("""  The edge  InvariantReg -> {K, Sigma, Policy}  is a MODELLING CHOICE of this
  script: it says the mandated invariant register contains invariants over the
  epistemic layer as well as the structural one. That edge is what drags Sigma,
  Evidence and Policy into the kernel's requirement set.

  Test the alternative: InvariantReg -> {K} only (structural invariants only).""")
G2 = dict(G); G2["InvariantReg"] = (["K"], "NOT ENUMERATED", "D")
def closure2(roots, g):
    seen, stack = set(), list(roots)
    while stack:
        n = stack.pop()
        if n in seen: continue
        seen.add(n); stack += g[n][0]
    return seen
need2 = closure2(["K","Transformation","Rejection","Replay"], G2)
print(f"\n  variant requirement set: {len(need2)} constructs")
print(f"    {sorted(need2)}")
dropped = sorted(need - need2)
print(f"\n  DROPS OUT of the minimum if invariants are structural-only ({len(dropped)}):")
for n in dropped: print(f"     {n:<16} {G[n][1]}  [{G[n][2]}]")
clear2 = [n for n in sorted(need2) if not G2[n][2]]
print(f"""
  variant: required {len(need2)} · unblocked {len(clear2)} · blocked {len(need2)-len(clear2)}

  => THE ANSWER TO §15 IS CONDITIONAL ON ONE UNRESOLVED QUESTION:
       does the mandated invariant register contain EPISTEMIC invariants,
       or only STRUCTURAL ones?
     If structural-only : the kernel needs {len(need2)} constructs and Sigma/Evidence/
                          Policy are OUT of the minimum.
     If epistemic too   : the kernel needs {len(need)} and they are IN.
     Nothing in the corpus settles this, because the register (G-67) has never
     been enumerated. RECORDED AS: BLOCKED — REQUIRES DERIVATION.""")
