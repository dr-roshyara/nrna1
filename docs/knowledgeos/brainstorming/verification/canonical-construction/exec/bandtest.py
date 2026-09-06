#!/usr/bin/env python3
"""
Does the UNDETERMINED BAND change the canonical K?

If K's required components are identical for all 16 subsets of the band
{Transform, Merge, Split, Reintroduce}, then D-1 does NOT block construction of K
-- it only affects the operation registry.  Test it by removal, per component.
"""
import itertools
LOWER = ["Add","Assess","Authorize","Derive","Determine","Promote","Qualify",
         "Reject","Remove","Replay","Revise","Supersede","Validate","Withdraw"]
BAND  = ["Transform","Merge","Split","Reintroduce"]

# Which K-component each operation REQUIRES in order to be well-defined.
# ("A"=assertions, "R"=relations, "S"=sigma-carrier, "EL"=evidence links,
#  "Dt"=recognised dimensions, "PI"=origin, "T"=temporal, "H"=history(external))
NEEDS = {
 "Add":        {"A","Dt"},          "Remove":   {"A"},
 "Revise":     {"A","PI","T"},      "Supersede":{"A","R","T"},
 "Reject":     {"A","S"},           "Withdraw": {"A","EL","S"},
 "Promote":    {"A","S"},           "Assess":   {"A","EL","S"},
 "Validate":   {"A","R","EL"},      "Authorize":{"A","S"},
 "Qualify":    {"EL"},              "Determine":{"A","S","PI"},
 "Derive":     {"A","R","PI"},      "Replay":   {"H"},
 # band
 "Transform":  {"A","R","PI","T"},  "Merge":    {"A","R","PI","EL"},
 "Split":      {"A","R","PI"},      "Reintroduce":{"A","S","T"},
}
def components(ops):
    s=set()
    for o in ops: s |= NEEDS[o]
    return s

base = components(LOWER)
print("="*78); print("BAND-INVARIANCE TEST"); print("="*78)
print(f"  K-components required by the 14 FORCED operations alone:")
print(f"    {sorted(base)}")
print()
results={}
for r in range(len(BAND)+1):
    for sub in itertools.combinations(BAND,r):
        c = components(LOWER+list(sub))
        results[sub]=c
allsame = all(c==base for c in results.values())
print(f"  subsets of the undetermined band tested: {len(results)}")
print(f"  K-components IDENTICAL across all subsets? {allsame}")
print()
for sub,c in results.items():
    delta = sorted(c-base)
    label = "{}"+"" if not sub else "{"+", ".join(sub)+"}"
    print(f"    band={label:<44s} added components: {delta if delta else 'NONE'}")

print()
print("="*78); print("REMOVAL TEST — is each component of K necessary?"); print("="*78)
FULL = components(LOWER+BAND)
for comp in sorted(FULL):
    broken=[o for o in LOWER+BAND if comp in NEEDS[o]]
    forced_broken=[o for o in LOWER if comp in NEEDS[o]]
    verdict = "REQUIRED (forced ops break)" if forced_broken else "band-only -> OPTIONAL"
    print(f"  remove {comp:<3s} -> breaks {len(broken):>2d} ops "
          f"({len(forced_broken)} of them FORCED)  => {verdict}")
    if forced_broken: print(f"        forced ops broken: {', '.join(forced_broken[:6])}")

print()
print("="*78); print("CONCLUSION"); print("="*78)
if allsame:
    print("  The undetermined band adds NO new K-component.")
    print("  => D-1 (which band members are mandatory) does NOT block the")
    print("     construction of K.  It affects only the OPERATION REGISTRY.")
    print("  => K is derivable NOW; the operation registry is not.")
else:
    print("  The band CHANGES K. D-1 blocks construction.")
