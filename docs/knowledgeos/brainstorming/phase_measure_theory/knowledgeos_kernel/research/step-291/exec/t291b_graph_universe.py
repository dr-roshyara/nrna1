#!/usr/bin/env python3
"""AUDIT B: state the graph universe explicitly and recompute. The 29-node vs
13-node question: they are NOT two graphs. 29 = the node universe; 13 = the subset
searched for cuts. Cycles are computed on the FULL 29; cuts are SELECTED from the 13."""
import io, re
from itertools import combinations
src=io.open("t291_bootstrap.py",encoding="utf-8").read()
ns={}; exec(src.split("NODES=sorted")[0], ns); E=ns["E"]
NODES=sorted({n for a,b,_,_ in E for n in (a,b)})
ADJ={n:set() for n in NODES}
for a,b,_,_ in E: ADJ[a].add(b)
def cycles(adj):
    f={}
    def dfs(s,v,p,seen):
        for w in adj.get(v,()):
            if w==s and len(p)>=2:
                i=p.index(min(p)); r=tuple(p[i:]+p[:i]); f[frozenset(r)]=r
            elif w not in seen and len(p)<10: dfs(s,w,p+[w],seen|{w})
    for s in adj: dfs(s,s,[s],{s})
    return sorted(f.values(), key=lambda c:(len(c),c))

RESOLVABLE={"O","T","O_K","equiv","equiv_cand","delta","Pi","id_context","assertion",
            "temporal","evidence","policy","Qualify"}
DERIVABLE={"approx","congruence","minimality","K","equiv_T","identity","bindings","canon",
           "struct_eq","merge","state_rep","invariants","suff","kernel","projection","assess_det"}
print("="*78); print("AUDIT B  GRAPH-UNIVERSE RECONSTRUCTION"); print("="*78)
print(f"""
FULL NODE UNIVERSE                : {len(NODES)}   {NODES}
DECISION-RESOLVABLE SUBSET        : {len(RESOLVABLE)}   {sorted(RESOLVABLE)}
DERIVABLE-ONLY SUBSET             : {len(DERIVABLE)}   {sorted(DERIVABLE)}
overlap (must be empty)           : {sorted(RESOLVABLE & DERIVABLE)}
union covers universe?            : {sorted(set(NODES)-(RESOLVABLE|DERIVABLE)) or 'YES'}
DOCUMENTARY nodes (no node - they are defects ABOUT nodes, not nodes): 
     O-glyph overload, 261.21 self-reference, unregistered observations, kind-5 type
EXCLUDED nodes and why            : none excluded; every node named in an admitted edge is present

EDGE-GENERATION RULES
  admitted   : a cited passage states that B REQUIRES SOMETHING FROM A
  rejected   : co-mention; "both concepts appear in one document"
  definitions: an IDENTIFICATION (K = H/equiv_T) contributes ONE edge, in the
               direction of determination, not two (Step 289 v1 artifact removed)
  candidates : a CANDIDATE proposal is modelled as its own node (equiv_cand),
               NOT as a definitional edge  (Step 290 correction)
  blocked    : blocked dependencies ARE included - blockage is a status, not an absence
  transitive : NOT included; only direct dependencies are edges
GRAPH USED FOR CYCLES             : the FULL {len(NODES)}-node graph
GRAPH USED FOR FVS SELECTION      : cuts are SEARCHED over the {len(RESOLVABLE)} resolvable
                                    nodes, but tested for acyclicity on the FULL graph
  ==> 29 and 13 are NOT two graphs. One graph; a restricted CANDIDATE SET for cuts.""")

C=cycles(ADJ)
print(f"\nRECOMPUTED: nodes {len(NODES)}  edges {len(E)}  cycles {len(C)}")
for c in C: print("   "+" -> ".join(c)+f" -> {c[0]}")

def acyclic(cut, universe=None):
    a={n:{m for m in ADJ[n] if m not in cut} for n in NODES if n not in cut}
    return len(cycles(a))==0

print("\n--- FVS over the RESTRICTED candidate set (13 resolvable) ---")
r=[sorted(c) for c in combinations(sorted(RESOLVABLE),1) if acyclic(set(c))]
print(f"  size-1 cuts: {r}   -> unique: {len(r)==1}")

print("\n--- FVS over the UNRESTRICTED universe (all 29 nodes) — a DIFFERENT question ---")
r2=[n for n in NODES if acyclic({n})]
print(f"  size-1 cuts: {r2}")
print(f"  count: {len(r2)}  -> unique: {len(r2)==1}")
if set(r2)!={"equiv"}:
    print("  ==> REPORTED AS A SEPARATE RESULT (mandate B): over the FULL universe the")
    print("      minimal cut is NOT unique. Nodes that also break every cycle:")
    for n in r2: print(f"         {n:14} resolvable? {'YES' if n in RESOLVABLE else 'NO - derivable only'}")
    print("      A cut through a DERIVABLE node is not actionable: it cannot be 'decided'.")
    print("      But it must be reported, because uniqueness was claimed without stating")
    print("      the candidate set.")
print("\n"+"="*78)
