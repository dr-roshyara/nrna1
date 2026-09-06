#!/usr/bin/env python3
"""One-at-a-time sensitivity of Closure(K_9) to the declared C-row -> seed mapping.

Same design as KR-BRIDGE sensitivity: perturb ONE row at a time to its recorded
alternative, hold the other eight at baseline, report what moves. This bounds how
much of the result is the canon and how much is my reading of it.
"""
import k9_closure as B   # re-uses G, closure(), K9 baseline

base = B.closure([s for c in B.K9 for s in B.K9[c][0]])
ALT = {   # row -> alternative seed list recorded in the baseline mapping
 "C-1 move an item one rung up the ladder":               ["Sigma"],
 "C-2 attach Committed by an authority act":              ["Authority","Sigma"],
 "C-4 change an in-force policy version by governed decision": ["Gamma","Policy"],
 "C-5 compose evidence: duplicates must not amplify, corroboration must": ["Assessment"],
 "C-6 compute the gap between state and requirement":     ["Missingness"],
}
print("="*78+"\nONE-AT-A-TIME SENSITIVITY OF Closure(K_9)\n"+"="*78)
print(f"  baseline |Closure(K_9)| = {len(base)}\n")
print(f"  {'perturbed row':16s} {'alt seeds':24s} {'size':>5s}  delta")
for row, alt in ALT.items():
    seeds = [s for c in B.K9 for s in (alt if c == row else B.K9[c][0])]
    cl = B.closure(seeds)
    gain, lost = sorted(cl-base), sorted(base-cl)
    d = ("+ "+", ".join(gain) if gain else "") + ("  - "+", ".join(lost) if lost else "")
    print(f"  {row[:14]:16s} {', '.join(alt)[:24]:24s} {len(cl):5d}  {d or '(no change)'}")

print("\n  ROBUST UNDER EVERY PERTURBATION (present in baseline and all 5 variants):")
allv = [base] + [B.closure([s for c in B.K9 for s in (a if c==r else B.K9[c][0])])
                 for r,a in ALT.items()]
inv = set.intersection(*allv)
print(f"      ({len(inv)}) {', '.join(sorted(inv))}")
print(f"  NOT robust to the mapping ({len(set.union(*allv)-inv)}): "
      f"{', '.join(sorted(set.union(*allv)-inv))}")
for n in ["O_core","Operations","InvariantReg","Transformation","Qualification"]:
    print(f"      {n:15s} robust-to-mapping? {n in inv}")

# ---- doubly-robust set: robust to BASIS and robust to MAPPING ----------------
print("\n"+"="*78+"\nDOUBLY-ROBUST BLOCKERS\n"+"="*78)
c4 = B.closure([s for c in B.K4 for s in B.K4[c]])
basis_rob = {n for n in (c4 & base) if B.G[n][2]}
map_rob   = {n for n in inv        if B.G[n][2]}
both      = basis_rob & map_rob
print(f"  blocked & robust to BASIS   ({len(basis_rob)}): {', '.join(sorted(basis_rob))}")
print(f"  blocked & robust to MAPPING ({len(map_rob)}): {', '.join(sorted(map_rob))}")
print(f"\n  >> DOUBLY ROBUST ({len(both)}): {', '.join(sorted(both))}")
print(f"  >> basis-robust but MAPPING-FRAGILE ({len(basis_rob-map_rob)}): "
      f"{', '.join(sorted(basis_rob-map_rob))}")
print(f"  >> mapping-robust but BASIS-FRAGILE ({len(map_rob-basis_rob)}): "
      f"{', '.join(sorted(map_rob-basis_rob))}")
print("\n  Every doubly-robust blocker, with its recorded defect:")
for n in sorted(both): print(f"      {B.G[n][2]:3s} {n:15s} {B.G[n][1]}")
