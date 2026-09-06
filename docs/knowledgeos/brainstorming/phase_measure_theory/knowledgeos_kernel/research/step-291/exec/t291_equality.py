#!/usr/bin/env python3
"""STEP 291: independent re-verification of 32->30 and the merge/join-semilattice claim."""
from itertools import combinations, product
A=["Observed","Reported","Inferred","Calculated","Assumed","Hypothesized","Unknown"]
S=["None","Weak","Moderate","Strong","VeryStrong"]; R=["Open","InProgress","Resolved","Unresolvable"]
V=["Current","Stale","Expired","Unknown"]; C=["None","Potential","Active","Resolved"]
N=["A","S","R","V","C"]; AX=dict(zip(N,[A,S,R,V,C]))
SIG=[dict(zip(N,t)) for t in product(A,S,R,V,C)]
print("="*78); print("STEP 291  EQUALITY RE-VERIFICATION (mandate s13, s14)"); print("="*78)
print(f"|Sigma| = {'*'.join(str(len(AX[k])) for k in N)} = {len(SIG)}   [CORPUS-VERIFIED, Q4A ss2.2-2.6]")
assert len(SIG)==2240

print("\n--- s13: independent re-verification of 32 -> 30 ---")
subs=[frozenset(c) for r in range(6) for c in combinations(N,r)]
prj=lambda s,X: tuple(s[k] for k in N if k in X)
parts={}
for X in subs:
    blocks={}
    for s in SIG: blocks.setdefault(prj(s,X),0)
    parts[X]=len(blocks)
print(f"  syntactically distinct axis subsets      : {len(subs)}   [MEASURED]")
distinct=len({frozenset(frozenset(i for i,s in enumerate(SIG) if prj(s,X)==k)
                        for k in {prj(s,X) for s in SIG}) for X in subs})
print(f"  mathematically distinct projections      : {distinct}   [MEASURED]")
deg=[(X,parts[X]) for X in subs if parts[X]==1 or parts[X]==len(SIG)]
for X,b in deg:
    kind="UNIVERSAL" if b==1 else "DISCRETE"
    lbl = ",".join(sorted(X)) if X else "(empty)"
    print(f"  degenerate endpoint: X={lbl:<22} blocks={b:<5} {kind}")
print(f"  non-degenerate candidates                : {len(subs)-len(deg)}   [MEASURED]")
print("""
  CORRECT TERMINOLOGY (mandate s13):
    32 mathematically distinct projections; 2 degenerate endpoints;
    30 non-degenerate candidates.
  ==> The endpoints are DEGENERATE, not INVALID. The corpus supplies no
      validity criterion for them, so 'invalid' would be my word, not its.
      Earlier artifacts said 'not a candidate' - that is TOO STRONG and is
      corrected here to 'degenerate endpoint'.""")

print("\n--- s14: is merge a JOIN? ---")
def union_merge(x,y): return x|y
tests=[("commutative", all(union_merge(a,b)==union_merge(b,a)
        for a,b in [({1,2},{2,3}),({1},set()),({1,2,3},{1})])),
       ("associative", all(union_merge(union_merge(a,b),c)==union_merge(a,union_merge(b,c))
        for a,b,c in [({1},{2},{3}),({1,2},{2,3},{3,4})])),
       ("idempotent",  all(union_merge(a,a)==a for a in [{1},{1,2},set()]))]
for t,r in tests: print(f"  PureClaimSetUnion  {t:12} {'PASS' if r else 'FAIL'}   [MEASURED]")
print("  => set union IS a join-semilattice op. This reproduces 060 ss60.39-60.41.")

# now with supersession, which 060.42 says the real merge has
print("\n  now a merge WITH supersession (060.42's objection), modelled minimally:")
def sup_merge(x,y):
    """later assertion supersedes earlier for the same subject; state = dict subj->(ver,val)"""
    out=dict(x)
    for k,(ver,val) in y.items():
        if k not in out or ver>out[k][0]: out[k]=(ver,val)
    return out
a={"nexus":(1,"3.69")}; b={"nexus":(2,"3.70")}; c={"nexus":(3,"3.71")}
print(f"    idempotent  : {sup_merge(a,a)==a}   [MEASURED]")
print(f"    commutative : {sup_merge(a,b)==sup_merge(b,a)}   [MEASURED]")
print(f"    associative : {sup_merge(sup_merge(a,b),c)==sup_merge(a,sup_merge(b,c))}   [MEASURED]")
print("""    -> laws happen to HOLD for this minimal supersession model.
    DEGENERACY CHECK (mandate s18): is that vacuous? The test space is 3 states
    over ONE subject with a total version order. A total order makes 'max' a
    join by construction. So the PASS is an artifact of the model's total order,
    NOT evidence about KnowledgeOS's merge.
    => RECORDED AS NON-EVIDENCE. 060.42 lists contradiction, temporal validity,
       semantic equivalence, probabilistic update and policy as further
       obstacles; none is modelled here, and modelling them would be inventing
       architecture (mandate s21).""")
print("""
  VERDICT on (K, merge, empty):
    NOT ESTABLISHED.  060 s60.71: 'Not proven ... we cannot yet assert
    KnowledgeOS is a semilattice.'  Laws hold for PureClaimSetUnion only.
    NOT REFUTED - retraction concerns state EVOLUTION, a different operation.
    No lattice is inferred from merge existing. No AGM comparison is used.""")
print("\n"+"="*78)
