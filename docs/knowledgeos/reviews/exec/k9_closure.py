#!/usr/bin/env python3
r"""Closure(K_9) — the authorized next act.

READ-ONLY with respect to canonical theory. This is a CONDITIONAL DEPENDENCY COMPUTATION.
It is NOT a proof of kernel minimality, and the result must not be called "the minimum kernel":
K_9 has necessity RELATIVE TO THE RATIFIED CANON; its SUFFICIENCY is untested and 88 of 99
contract cells are empty.

The graph G is reproduced VERBATIM from
  brainstorming/verification/gap-discovery/readiness/exec/minimum_implementable.py
so that the K_4 control reproduces that program's own published result.

THE ONE MODELLING ACT, declared: mapping C-1..C-9 onto seed constructs. Each seed is derived
from the C-row's own text and its cited forcing element (OPERATION-CONTRACT-GAP.md sect A).
Alternatives are recorded per row. NOTHING ELSE here is a choice.
"""
import collections

# ---- graph reproduced verbatim from minimum_implementable.py -----------------
G = {
 "Identity":      ([],                                  "clear-all-lanes", "D?"),
 "Equality":      (["Identity"],                        "clear-all-lanes", "D"),
 "Proposition":   ([],                                  "formal",          "D"),
 "Assertion":     (["Proposition","Identity","Provenance"], "formal",      None),
 "Provenance":    ([],                                  "formal",          None),
 "RelationType":  ([],                                  "formal",          None),
 "Relation":      (["Assertion","RelationType"],        "formal",          "D"),
 "K":             (["Assertion","Relation"],            "formal+L5",       "A"),
 "Lineage":       (["Relation","Provenance"],            "clear-all-lanes", None),
 "Orphan":        (["Relation"],                        "clear-all-lanes", None),
 "History":       (["K"],                               "formal",          "I"),
 "Evidence":      (["Assertion"],                       "formal",          "D"),
 "Qualification": (["Evidence","Policy"],               "formal",          "D"),
 "Sigma":         (["Evidence"],                        "formal",          "D"),
 "Q_t":           (["Proposition"],                     "formal",          "EC"),
 "Missingness":   (["Q_t","Sigma"],                     "formal",          "EC"),
 "Assessment":    (["Evidence","Policy","Sigma"],       "formal",          "D"),
 "Measurement":   (["Assessment"],                      "formal-only",     "D"),
 "Policy":        ([],                                  "RATIFIED",        "G"),
 "Authority":     ([],                                  "narrow-ratified", "D"),
 "Authorization": (["Authority","Policy"],              "formal-only",     "I"),
 "Gamma":         (["Authority"],                       "formal+L5p",      "I"),
 "InvariantReg":  (["K","Sigma","Policy"],              "NOT ENUMERATED",  "D"),
 "O_core":        (["InvariantReg"],                    "NOT FROZEN",      "O"),
 "Operations":    (["O_core"],                          "0 canonical",     "O"),
 "Transformation":(["Operations","Identity","Equality"],"no body",         "T"),
 "Rejection":     (["Transformation"],                  "CONTRADICTORY",   "T"),
 "Replay":        (["Transformation","History"],        "formal",          "T"),
 "Determination": (["Assessment","Authority"],          "formal",          "D"),
}

def closure(roots):
    seen, stack = set(), list(roots)
    while stack:
        n = stack.pop()
        if n in seen: continue
        seen.add(n); stack += G[n][0]
    return seen

# ---- K_4: the stipulated basis, verbatim from the source program -------------
K4 = {"hold a knowledge state":["K"], "transition it legally":["Transformation"],
      "reject illegally":["Rejection"], "replay from history":["Replay"]}

# ---- K_9: THE DECLARED MODELLING ACT ----------------------------------------
# seed | justification from the C-row's own text | alternatives considered
K9 = {
 "C-1 move an item one rung up the ladder":
   (["Transformation","Sigma"],
    "a transition (I-12 covering relation) over the status ladder",
    "Sigma alone if 'move' is read as a status predicate, not a transition"),
 "C-2 attach Committed by an authority act":
   (["Authorization","Sigma"],
    "canon fixes an AUTHORITY ACT (A6, I-4); the target is a ladder state",
    "Authority alone if the act is not routed through Authorization"),
 "C-3 make a Determination (Supported->Accepted under AcceptancePolicy)":
   (["Determination","Policy"],
    "names the Determination construct and its governing policy",
    "none — the C-row names the construct"),
 "C-4 change an in-force policy version by governed decision":
   (["Policy","Authorization"],
    "I-11 + the stratification loop: a governed approval over Policy",
    "Gamma if 'governed' is read as the authority context"),
 "C-5 compose evidence: duplicates must not amplify, corroboration must":
   (["Evidence"],
    "I-5/I-6, the two TESTED invariants, are stated over Evidence",
    "Assessment if composition is read as an assessment step"),
 "C-6 compute the gap between state and requirement":
   (["K"],
    "Zero(K,EC) is a function OF the state; EC is external (eta(G,IdealState))",
    "Missingness if the gap is read as the missingness construct"),
 "C-7 turn an observation into evidence":
   (["Qualification"],
    "the C-row IS the qualification predicate; canon leaves it undefined",
    "none — but see the DEGENERACY WARNING below"),
 "C-8 evaluate a decision contract for admissibility":
   (["InvariantReg","Authority","Evidence"],
    "DC 6-tuple slots Inv, Auth, Evidence map to recorded constructs",
    "Pre/Post/Temporal have no construct in G — UNDER-SPECIFIED"),
 "C-9 act, and observe the result":
   (["Determination"],
    "the decision interlock; the far side is OPEN BY RULING (OQ-4)",
    "no construct covers 'act'/'observe' — UNDER-SPECIFIED"),
}

def report(name, caps, seeds_of):
    print("\n"+"="*78+f"\n{name}\n"+"="*78)
    allseed = sorted({s for c in caps for s in seeds_of(c)})
    cl = closure(allseed)
    print(f"  seeds ({len(allseed)}): {', '.join(allseed)}")
    print(f"  CLOSURE SIZE: {len(cl)} of {len(G)} constructs")
    blocked = sorted(n for n in cl if G[n][2])
    clear   = sorted(n for n in cl if not G[n][2])
    print(f"  unblocked in closure ({len(clear)}): {', '.join(clear)}")
    print(f"  BLOCKED in closure ({len(blocked)}):")
    by = collections.defaultdict(list)
    for n in blocked: by[G[n][2]].append(n)
    for k in sorted(by): print(f"      {k:3s} {', '.join(sorted(by[k]))}")
    return cl

c4 = report("K_4 — the STIPULATED basis (control: must match the published 15/18)",
            K4, lambda c: K4[c])
c9 = report("K_9 — the DERIVED basis (C-1..C-9), under the declared mapping",
            K9, lambda c: K9[c][0])

print("\n"+"="*78+"\nCOMPARISON\n"+"="*78)
print(f"  |Closure(K_4)| = {len(c4)}    |Closure(K_9)| = {len(c9)}")
print(f"  K_4 \\ K_9 ({len(c4-c9)}): {', '.join(sorted(c4-c9)) or '(none)'}")
print(f"  K_9 \\ K_4 ({len(c9-c4)}): {', '.join(sorted(c9-c4)) or '(none)'}")
print(f"  outside BOTH ({len(set(G)-c4-c9)}): {', '.join(sorted(set(G)-c4-c9)) or '(none)'}")
print("\n  QUALIFICATION:")
print(f"      in Closure(K_4)? {'Qualification' in c4}")
print(f"      in Closure(K_9)? {'Qualification' in c9}")
print("      ^^ DEGENERACY WARNING: C-7 IS the qualification capability, so its seed is")
print("         Qualification. Its presence in Closure(K_9) is DEFINITIONAL, not a discovery.")
print("         The informative question is what ELSE differs — see K_9 \\ K_4 above.")

# ---- structural comparison ---------------------------------------------------
print("\n"+"="*78+"\nSTRUCTURE OF THE DIVERGENCE\n"+"="*78)
inter, union = c4 & c9, c4 | c9
print(f"  NESTED?  Closure(K_4) subseteq Closure(K_9)? {c4 <= c9}"
      f"   |   Closure(K_9) subseteq Closure(K_4)? {c9 <= c4}")
print(f"  => the two bases are {'NESTED' if (c4<=c9 or c9<=c4) else 'INCOMPARABLE'}\n")
print(f"  intersection ({len(inter)}): {', '.join(sorted(inter))}")
print(f"  union        ({len(union)} of {len(G)}): reached by at least one basis")
print(f"  neither      ({len(set(G)-union)}): {', '.join(sorted(set(G)-union))}")

print("\n  BLOCKERS BY ROBUSTNESS TO THE CHOICE OF BASIS:")
def blk(s): return {n for n in s if G[n][2]}
rob, only4, only9 = blk(inter), blk(c4-c9), blk(c9-c4)
print(f"    ROBUST  — blocked, in BOTH closures ({len(rob)}):")
for n in sorted(rob): print(f"        {G[n][2]:3s} {n:15s} {G[n][1]}")
print(f"    K_4-ONLY — blocked, stipulated basis only ({len(only4)}):")
for n in sorted(only4): print(f"        {G[n][2]:3s} {n:15s} {G[n][1]}")
print(f"    K_9-ONLY — blocked, derived basis only ({len(only9)}):")
for n in sorted(only9): print(f"        {G[n][2]:3s} {n:15s} {G[n][1]}")

print("\n  SEEDS NOT REACHED BY THE OTHER BASIS (the asymmetry, stated plainly):")
print(f"    K_4 stipulates capabilities whose seeds NO C-row forces: "
      f"{', '.join(sorted({s for c in K4 for s in K4[c]} - {s for c in K9 for s in K9[c][0]}))}")
print(f"    C-rows force seeds K_4 never names: "
      f"{', '.join(sorted({s for c in K9 for s in K9[c][0]} - {s for c in K4 for s in K4[c]}))}")

print("\n  LANE TALLY (blocked constructs per lane, by basis):")
lanes = sorted({G[n][2] for n in G if G[n][2]})
print(f"    {'lane':5s} {'K_4':>5s} {'K_9':>5s} {'both':>5s}")
for L in lanes:
    print(f"    {L:5s} {len([n for n in blk(c4) if G[n][2]==L]):5d}"
          f" {len([n for n in blk(c9) if G[n][2]==L]):5d}"
          f" {len([n for n in rob if G[n][2]==L]):5d}")
