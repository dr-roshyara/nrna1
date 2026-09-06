#!/usr/bin/env python3
"""
STEP 291 bootstrap re-audit.  KEY CHANGE from Step 289: O, T and O_K are SEPARATE
nodes (mandate s2: "Do not assume that O, O_K and T are identical").
Corpus basis: 259.7 gives a FIVE-WAY operation classification and states
"Not every operation has the form T: K -> K"; 259.8 separates the state-transforming
operations from Assess and Authorize, which get different tests.
So:  T subset O   (kind 1 of 5)      O_K  relates to kind 5 (observation operations)
Every zero-result is degeneracy-checked (mandate s18).
"""
from itertools import combinations

E = [
 # (src, dst, what dst needs from src, class)
 ("O","T","T is kind 1 of O's five kinds (259.7); membership of T needs O","CORPUS 259.7/259.8"),
 ("O","O_K","observation operations are kind 5 of O (259.7)","CORPUS 259.7"),
 ("T","delta","semantics only for a fixed family","DERIVED"),
 ("O_K","approx","approx := forall O in O_K","CORPUS 261.5/261.21"),
 ("approx","equiv_cand","261.21 proposes equiv_K := approx's formula (CANDIDATE)","CORPUS 261.21 [candidate]"),
 ("equiv_cand","equiv","a ratified candidate would fill the equiv slot","NORMATIVE N-1'"),
 ("equiv","delta","delta postconditions unstatable without an equality","CORPUS/DERIVED"),
 ("delta","congruence","congruence is a property of delta","CORPUS 258.9"),
 ("equiv","congruence","congruence is stated in terms of equiv","CORPUS 258.9"),
 ("congruence","equiv","'if it fails, equiv_K is too coarse' - adequacy judged by congruence","CORPUS 258.9"),
 ("T","congruence","congruence quantifies over all T in T","CORPUS 258.9/259"),
 ("congruence","minimality","no minimality proof before congruence","CORPUS 259"),
 ("minimality","K","K* = min info preserving mandatory distinctions","CORPUS 258.22"),
 ("T","equiv_T","equiv_T indexed by T","CORPUS 260"),
 ("equiv_T","K","the quotient is the state","CORPUS 258.12"),
 ("identity","equiv","object equality presupposes identity","CORPUS 258.2"),
 ("id_context","identity","I_48 transitivity within a context","CORPUS 195.16"),
 ("Pi","equiv","Decision 3","CORPUS 254/261.8"),
 ("O","Pi","258.31: does a MANDATORY op observe provenance","CORPUS 258.31"),
 ("equiv","bindings","per-operation bindings need the relations","CORPUS 261.19"),
 ("O","bindings","bindings are per operation","CORPUS 261.19"),
 ("bindings","approx","which relation each op needs feeds back","CORPUS 261.19"),
 ("equiv","canon","a quotient needs an equivalence relation","DERIVED"),
 ("canon","struct_eq","= decidable only relative to a canonicalization","CORPUS 38.85"),
 ("evidence","canon","canonicalization must follow evidence","CORPUS 38.85"),
 ("equiv","merge","Merge idempotence stated modulo equiv","CORPUS 25J.45"),
 ("delta","merge","merge is a transformation","DERIVED"),
 ("K","state_rep","representation must carry K","CORPUS 285"),
 ("state_rep","struct_eq","= compares representations","DERIVED"),
 ("equiv","invariants","equality-sensitive invariants conditional","CORPUS 287"),
 ("invariants","suff","sufficiency = invariants preserved","CORPUS 273/277"),
 ("congruence","suff","valid abstraction must satisfy congruence","CORPUS 258.30"),
 ("suff","kernel","kernel needs sufficiency","CORPUS 261.23"),
 ("equiv","kernel","261.23: stop while equality ambiguous","CORPUS 261.23"),
 ("identity","kernel","261.23 cond 6","CORPUS 261.23"),
 ("O_K","kernel","261.23 cond 1","CORPUS 261.23"),
 ("O","kernel","261.23 cond 2","CORPUS 261.23"),
 ("Pi","kernel","261.23 cond 3","CORPUS 261.23"),
 ("assertion","kernel","261.23 cond 4","CORPUS 261.23"),
 ("temporal","kernel","261.23 cond 5","CORPUS 261.23"),
 ("Qualify","projection","pi_K computability blocked","CORPUS 285"),
 ("policy","delta","admissibility gates transitions","CORPUS 278"),
 # 259.8: Assess/Authorize get DIFFERENT tests, still equality-dependent
 ("equiv","assess_det","K1==K2 => Assess(K1,x)=Assess(K2,x)","CORPUS 259.8"),
 ("O","assess_det","Assess is kind 2 of O","CORPUS 259.7"),
]
NODES=sorted({n for a,b,_,_ in E for n in (a,b)}); ADJ={n:set() for n in NODES}
for a,b,_,_ in E: ADJ[a].add(b)

def cycles(adj):
    found={}
    def dfs(s,v,path,seen):
        for w in adj.get(v,()):
            if w==s and len(path)>=2:
                i=path.index(min(path)); rot=tuple(path[i:]+path[:i]); found[frozenset(rot)]=rot
            elif w not in seen and len(path)<10: dfs(s,w,path+[w],seen|{w})
    for s in adj: dfs(s,s,[s],{s})
    return sorted(found.values(), key=lambda c:(len(c),c))

print("="*78); print("STEP 291  BOOTSTRAP RE-AUDIT   (O, T, O_K SEPARATED)"); print("="*78)
print(f"nodes {len(NODES)}  edges {len(E)}")
print("\nCORPUS BASIS for the separation (mandate s2):")
print("  259.7  five kinds of operation: state-transformations | assessments |")
print("         governance | audit/history | OBSERVATION operations")
print("  259.7  'Not every operation has the form T: K -> K'")
print("  259.8  Revise/Transform/Supersede/Merge/Split/Withdraw/Promote enter the")
print("         primary congruence test; Assess and Authorize get DIFFERENT tests")
print("  => T is a SUBSET of O (kind 1); O_K relates to kind 5. THREE objects.")

C=cycles(ADJ)
print(f"\ncycles found: {len(C)}")
for c in C: print("   "+" -> ".join(c)+f" -> {c[0]}")

RESOLVABLE={"O","T","O_K","equiv","equiv_cand","delta","Pi","id_context","assertion",
            "temporal","evidence","policy","Qualify"}
def acyclic(cut):
    a={n:{m for m in ADJ[n] if m not in cut} for n in NODES if n not in cut}
    return len(cycles(a))==0

print("\n--- exhaustive minimal-cut search over resolvable nodes ---")
found_at=None
for k in range(1,4):
    hits=[sorted(c) for c in combinations(sorted(RESOLVABLE),k) if acyclic(set(c))]
    print(f"  size {k}: {len(hits)} cut set(s)")
    for h in hits: print(f"      {h}")
    if hits and found_at is None: found_at=k; break

print("\n--- targeted probes (mandate s9) ---")
for probe in [{"O"},{"T"},{"O_K"},{"O","T"},{"O","T","O_K"},{"equiv"},{"delta"},
              {"congruence"} , {"equiv_cand"}, {"approx"}]:
    print(f"   cut {sorted(probe)!s:30} -> acyclic? {acyclic(probe)}")

print("\n--- DEGENERACY CHECK on the zero-results (mandate s18) ---")
print("  Q: could 'cut {O} -> False' be vacuous (empty/universal test space)?")
print(f"     graph has {len(NODES)} nodes, {len(E)} edges, {len(C)} cycles -> NON-EMPTY, NON-TRIVIAL")
print(f"     O is in {sum(1 for c in C if 'O' in c)} of {len(C)} cycles  <- measured")
print(f"     T is in {sum(1 for c in C if 'T' in c)} of {len(C)} cycles  <- measured")
print(f"     O_K is in {sum(1 for c in C if 'O_K' in c)} of {len(C)} cycles  <- measured")
print(f"     equiv is in {sum(1 for c in C if 'equiv' in c)} of {len(C)} cycles  <- measured")
print("     => the result is substantive, not vacuous: the graph HAS cycles and the")
print("        membership counts differ across nodes.")

print("\n--- is {equiv} still the UNIQUE minimal cut? ---")
singles=[n for n in sorted(RESOLVABLE) if acyclic({n})]
print(f"  size-1 cuts among resolvable nodes: {singles}")
print("\n"+"="*78)
print("SCOPE: conclusions hold for THIS audited edge list. No O, T or O_K is proposed.")
print("="*78)
