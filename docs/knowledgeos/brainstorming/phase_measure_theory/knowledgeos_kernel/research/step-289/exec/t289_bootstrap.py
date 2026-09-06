#!/usr/bin/env python3
"""
Step 289: dependency graph -> cycles -> minimal feedback vertex set (bootstrap cut).
Every edge carries a corpus/derivation citation. Edges are DEFINITIONAL or OPERATIONAL
only: "A and B are mentioned together" is NOT an edge (mandate section 4).
The cut set is computed by exhaustive search, NOT asserted.
"""
from itertools import combinations, chain

# A -> B  means  "B needs A"   (edge  A gives something B requires)
# (src, dst, what B needs from A, class)
E = [
 ("O","T","which operations are mandatory fixes the transformation family","CORPUS 259"),
 ("T","delta","semantics can only be given for a fixed family","DERIVED"),
 ("O","O_K","permitted observations are drawn from the operation space","CORPUS 259.8"),
 ("O_K","approx","approx is forall O in O_K","CORPUS 261.5/261.21"),
 ("equiv","approx","261.1 lists them distinct; 258.8 defines equiv observationally","CORPUS 258.8 vs 261.1"),
 ("approx","equiv","same formula -> definitional entanglement (the N-1 conflict)","CORPUS 261.5/261.21"),
 ("equiv","delta","delta postconditions are unstatable without an equality","CORPUS 256/DERIVED"),
 ("delta","congruence","congruence is a property OF delta","CORPUS 258.9"),
 ("equiv","congruence","congruence is stated in terms of equiv","CORPUS 258.9"),
 ("congruence","equiv","'if it fails, equiv_K is too coarse' - adequacy of equiv is judged by congruence","CORPUS 258.9"),
 ("T","congruence","congruence quantifies over all T in T","CORPUS 258.9/259"),
 ("congruence","minimality","no minimality proof before congruence analysis","CORPUS 259"),
 ("minimality","K","K* = minimum info preserving mandatory distinctions","CORPUS 258.22"),
 ("K","equiv_T","K is proposed as a quotient of histories","CORPUS 258.12/260"),
 ("equiv_T","K","the quotient IS the state","CORPUS 258.12"),
 ("T","equiv_T","equiv_T is indexed by T","CORPUS 260"),
 ("identity","equiv","equality of objects presupposes identity semantics","CORPUS 258.2/261.26"),
 ("id_context","identity","I_48 transitivity holds only within a context","CORPUS 195.16"),
 ("Pi","equiv","Decision 3: is provenance inside semantic equality","CORPUS 254/261.8"),
 ("O","Pi","258.31: decided by whether a mandatory op observes provenance","CORPUS 258.31"),
 ("equiv","bindings","per-operation bindings need the relations","CORPUS 261.19"),
 ("O","bindings","bindings are per operation","CORPUS 261.19"),
 ("bindings","approx","which relation Deduplicate/Replay need feeds back to approx","CORPUS 261.19"),
 ("equiv","canonicalization","a quotient needs an equivalence relation","DERIVED"),
 ("canonicalization","struct_eq","= is decidable only relative to a canonicalization","CORPUS 38.85/EXECUTED"),
 ("evidence","canonicalization","canonicalization must FOLLOW evidence","CORPUS 38.85"),
 ("equiv","merge","Merge idempotence is stated modulo equiv","CORPUS 25J.45"),
 ("delta","merge","merge is a transformation","DERIVED"),
 ("K","state_rep","the representation must carry K","CORPUS 285"),
 ("state_rep","struct_eq","structural equality compares representations","DERIVED"),
 ("equiv","invariants","equality-sensitive invariants are conditional on equiv","CORPUS 287"),
 ("invariants","sufficiency","sufficiency = invariants preserved","CORPUS 273/277"),
 ("congruence","sufficiency","a valid abstraction must satisfy congruence","CORPUS 258.30"),
 ("sufficiency","kernel","kernel selection requires sufficiency","CORPUS 261.23"),
 ("equiv","kernel","261.23: selection stops while equality is ambiguous","CORPUS 261.23"),
 ("identity","kernel","261.23 condition 6","CORPUS 261.23"),
 ("O_K","kernel","261.23 condition 1","CORPUS 261.23"),
 ("O","kernel","261.23 condition 2","CORPUS 261.23"),
 ("Pi","kernel","261.23 condition 3","CORPUS 261.23"),
 ("assertion","kernel","261.23 condition 4","CORPUS 261.23"),
 ("temporal","kernel","261.23 condition 5","CORPUS 261.23"),
 ("Qualify","projection","pi_K computability blocked by Qualify","CORPUS 285"),
 ("policy","delta","admissibility gates transitions","CORPUS 278"),
]
NODES = sorted({n for a,b,_,_ in E for n in (a,b)})
ADJ = {n:set() for n in NODES}
for a,b,_,_ in E: ADJ[a].add(b)

def cycles_in(adj):
    """all elementary cycles, Johnson-lite via DFS (graph is small)"""
    found=set()
    def dfs(start,node,path,seen):
        for nxt in adj.get(node,()):
            if nxt==start and len(path)>=2:
                # canonical rotation so each cycle counted once
                i=path.index(min(path)); rot=tuple(path[i:]+path[:i]); found.add(rot)
            elif nxt not in seen and (nxt>start or True):
                if len(path)<9:
                    dfs(start,nxt,path+[nxt],seen|{nxt})
    for s in adj: dfs(s,s,[s],{s})
    # dedupe cycles that are rotations of one another
    uniq={}
    for c in found: uniq[frozenset(c) if len(set(c))==len(c) else c]=c
    return sorted(uniq.values(), key=lambda c:(len(c),c))

print("="*74); print("STEP 289  DEPENDENCY GRAPH / CYCLES / MINIMAL BOOTSTRAP CUT"); print("="*74)
print(f"nodes {len(NODES)}  edges {len(E)}   (every edge definitional or operational, each cited)")

C = cycles_in(ADJ)
print(f"\nelementary cycles found: {len(C)}")
for c in C[:20]:
    print("   " + " -> ".join(c) + f" -> {c[0]}")
if len(C)>20: print(f"   ... and {len(C)-20} more")

# which nodes are RESOLVABLE, and by what mechanism
RESOLVABLE = {   # node : (mechanism, why)
 "O":        ("NORMATIVE",  "membership declaration; no incoming derivation edge found"),
 "T":        ("NORMATIVE",  "same; 259 treats closure as precondition, never result"),
 "O_K":      ("NORMATIVE",  "261.21: 'O_K is not yet completely closed'"),
 "equiv":    ("NORMATIVE",  "N-1 conflict + Decision 3; 012 s35: not fully decidable"),
 "delta":    ("NORMATIVE",  "transformation semantics; commit case unspecifiable"),
 "Pi":       ("NORMATIVE",  "Decision 3 residue (technical half needs O)"),
 "id_context":("NORMATIVE", "a scoping choice; I_48's unbound parameter"),
 "assertion":("NORMATIVE",  "261.23 cond 4; Step 262 line"),
 "temporal": ("NORMATIVE",  "261.23 cond 5"),
 "evidence": ("NORMATIVE",  "38.85: canonicalization must follow evidence"),
 "policy":   ("NORMATIVE",  "278 governance layer"),
 "Qualify":  ("G1",         "irreducible; named, no body"),
}
DERIVED_ONLY = {"approx","congruence","minimality","K","equiv_T","identity","bindings",
                "canonicalization","struct_eq","merge","state_rep","invariants",
                "sufficiency","kernel","projection"}
print(f"\nnodes resolvable by a decision : {len(RESOLVABLE)}")
print(f"nodes only derivable            : {len(DERIVED_ONLY)}")

# minimal feedback vertex set, restricted to RESOLVABLE nodes
cand = sorted(RESOLVABLE)
def acyclic_without(cut):
    adj={n:{m for m in ADJ[n] if m not in cut} for n in NODES if n not in cut}
    return len(cycles_in(adj))==0

print("\n--- exhaustive search for the minimal cut over RESOLVABLE nodes ---")
best=None
for k in range(1,5):
    hits=[set(c) for c in combinations(cand,k) if acyclic_without(set(c))]
    print(f"  size {k}: {len(hits)} cut set(s)" + (f"   e.g. {sorted(hits[0])}" if hits else ""))
    if hits and best is None:
        best=k
        allmin=[sorted(h) for h in hits]
        break
print()
if best:
    print(f"MINIMAL CUT SIZE = {best}")
    print(f"number of distinct minimal cut sets = {len(allmin)}")
    for h in allmin[:12]: print("   ", h)
    if len(allmin)>12: print(f"    ... and {len(allmin)-12} more")
    # is {O,T} alone sufficient?
    print()
    for probe in [{"O"},{"T"},{"O","T"},{"O","T","equiv"},{"O","T","delta"},{"equiv"},{"delta"},{"equiv","delta"}]:
        print(f"   cut {sorted(probe)!s:34} -> acyclic? {acyclic_without(probe)}")
print("\n"+"="*74)
print("SCOPE: the graph is the AUDITED EDGE LIST above. A different edge list gives a")
print("different cut. No operation set, transformation semantics or equality is proposed.")
print("="*74)
