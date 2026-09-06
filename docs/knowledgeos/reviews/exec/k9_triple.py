#!/usr/bin/env python3
"""Third robustness axis: the source program's OWN section-F sensitivity, crossed
with basis (K_4 vs K_9) and with the C-row mapping. Also tests section E's claim
that eight constructs are JUSTIFIED EXCLUSIONS from any kernel."""
import k9_closure as B, k9_sensitivity as S, itertools

G_epi = B.G                                              # register holds epistemic invariants
G_str = dict(B.G); G_str["InvariantReg"] = (["K"], "NOT ENUMERATED", "D")   # structural only

def clo(roots, g):
    seen, stack = set(), list(roots)
    while stack:
        n = stack.pop()
        if n in seen: continue
        seen.add(n); stack += g[n][0]
    return seen

# ---- section E's claim, re-tested under the DERIVED basis --------------------
EXCLUDED = ["Measurement","Missingness","Determination","Q_t","Assessment",
            "Qualification","Gamma","Authorization"]
c4 = clo([s for c in B.K4 for s in B.K4[c]], G_epi)
c9 = clo([s for c in B.K9 for s in B.K9[c][0]], G_epi)
print("="*78+"\nSECTION E's 'JUSTIFIED EXCLUSIONS', RE-TESTED UNDER K_9\n"+"="*78)
print(f"  {'construct':16s} {'excluded under K_4?':21s} {'excluded under K_9?':21s}")
flip = []
for n in EXCLUDED:
    a, b = n not in c4, n not in c9
    if a and not b: flip.append(n)
    print(f"  {n:16s} {str(a):21s} {str(b):21s}{'   <-- CLAIM FAILS' if a and not b else ''}")
print(f"\n  >> {len(flip)} of {len(EXCLUDED)} 'justified exclusions' become REQUIRED under the")
print(f"     derived basis: {', '.join(flip)}")
print("     The exclusions were justified RELATIVE TO the stipulated K_4, not absolutely.")

# ---- triple robustness -------------------------------------------------------
print("\n"+"="*78+"\nTRIPLE ROBUSTNESS: basis x C-row mapping x InvariantReg edge\n"+"="*78)
maps = [ {c: B.K9[c][0] for c in B.K9} ]
for row, alt in S.ALT.items():
    maps.append({c: (alt if c == row else B.K9[c][0]) for c in B.K9})
worlds = []
for g_name, g in (("epistemic", G_epi), ("structural", G_str)):
    worlds.append(("K_4", g_name, clo([s for c in B.K4 for s in B.K4[c]], g)))
    for i, m in enumerate(maps):
        worlds.append((f"K_9/m{i}", g_name, clo([s for c in m for s in m[c]], g)))
print(f"  {len(worlds)} worlds enumerated (2 edge-variants x (K_4 + 6 mappings))")
inv = set.intersection(*[w[2] for w in worlds])
uni = set.union(*[w[2] for w in worlds])
blocked_inv = sorted(n for n in inv if B.G[n][2])
print(f"\n  present in EVERY world ({len(inv)}): {', '.join(sorted(inv))}")
print(f"  of those, BLOCKED ({len(blocked_inv)}):")
for n in blocked_inv: print(f"      {B.G[n][2]:3s} {n:15s} {B.G[n][1]}")
print(f"\n  present in SOME world but not all ({len(uni-inv)}): {', '.join(sorted(uni-inv))}")
print(f"  present in NO world ({len(set(B.G)-uni)}): {', '.join(sorted(set(B.G)-uni)) or '(none)'}")
sizes = sorted({len(w[2]) for w in worlds})
print(f"\n  closure size ranges over {min(sizes)}..{max(sizes)} of {len(B.G)} constructs")
print("  => the size of 'the minimum kernel' is not determined by the corpus.")
