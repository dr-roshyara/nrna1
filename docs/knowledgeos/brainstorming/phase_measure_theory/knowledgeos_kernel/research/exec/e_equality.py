#!/usr/bin/env python3
"""
Reviewer B's mandate E3/E4/E5, executed against the CORPUS relations (Step 246):
  A structural  K1 =  K2   byte/structurally identical
  B semantic    K1 == K2   "encode the same knowledge semantics"
  C observational K1 ~ K2  every permitted observation gives the same result
  D provenance-sensitive K1 =_lam K2  content AND relevant provenance equivalent
  "These are not interchangeable."  [CORPUS 246]
"""
import hashlib, json, itertools
def H(*p): return hashlib.sha256(json.dumps(p,sort_keys=True,default=str).encode()).hexdigest()[:8]

def A(P, e, c, t, Pi): return dict(P=P, e=e, c=c, t=t, Pi=Pi, id=H(P,e,c,t,Pi))
a_scan   = A(("svc","D.tls","1.3"), (("ev1","supports","active"),), "prod", ("2026-08-01",None), "origin:scan")
a_vendor = A(("svc","D.tls","1.3"), (("ev1","supports","active"),), "prod", ("2026-08-01",None), "origin:vendor")
K0 = {"a0"}
K1 = {"a0", a_scan["id"]}      # via o1
K2 = {"a0", a_vendor["id"]}    # via o2
o1 = ("Assert", a_scan);  o2 = ("Assert", a_vendor)

def eq_struct(x,y):  return x==y
def eq_semantic(x,y):
    # "same knowledge semantics" -- the ONLY reading the corpus offers is a NAME.
    # Any concrete procedure must choose what to discard. Decision 3 (254) is OPEN.
    proj = lambda k: {(m["P"],m["c"],m["t"]) for m in (a_scan,a_vendor) if m["id"] in k} | (k & {"a0"})
    return proj(x)==proj(y)
def eq_obs(x,y, queries):
    return all(q(x)==q(y) for q in queries)
QUERIES = [lambda k: len(k), lambda k: sorted(k)==sorted(k)]   # permitted observations: cardinality only
def eq_prov(x,y):
    prov = lambda k: {m["Pi"] for m in (a_scan,a_vendor) if m["id"] in k}
    return eq_semantic(x,y) and prov(x)==prov(y)

print("="*78); print("E4 — D285-5 REVALIDATED UNDER ALL FOUR CORPUS RELATIONS"); print("="*78)
print(f"  o1 != o2 : {o1 != o2}      (differ only in Pi)")
rows=[]
rows.append(("A structural  =",   eq_struct(K1,K2),   "Pi is inside id -> different ids -> different sets"))
rows.append(("B semantic    ==",  eq_semantic(K1,K2), "equal IF Pi is discarded -- but that CHOICE is Decision 3, OPEN"))
rows.append(("C observational ~", eq_obs(K1,K2,[QUERIES[0]]), "equal under cardinality-only observation"))
rows.append(("D provenance   =_lam", eq_prov(K1,K2),  "explicitly compares Pi -> unequal by construction"))
print(f"\n  {'relation':<20s} {'antecedent holds':<18s} {'property substantive?':<24s} note")
print("  "+"-"*96)
for name,holds,note in rows:
    subst = "SUBSTANTIVE" if holds else "VACUOUS"
    if name.startswith("B"): subst = "SUBSTANTIVE *but see E2*"
    print(f"  {name:<20s} {str(holds):<18s} {subst:<24s} {note}")

print()
print("="*78); print("E3 — IS THE HIERARCHY  history ⊊ structural ⊊ semantic  CORPUS?"); print("="*78)
print("  Corpus (246) defines FOUR relations: structural · semantic · observational · provenance-sensitive.")
print("  'history' equality is NOT among them.")
print("  The chain 'history ⊊ structural ⊊ semantic' appears ONLY in verification-lane artifacts.")
print("  => the HIERARCHY is a verification-lane construct (DERIVED), not CORPUS.")
print()
print("  What CAN be tested from the corpus four:")
print(f"    structural => semantic ?  {(not eq_struct(K1,K2)) or eq_semantic(K1,K2)}   (holds on this witness)")
print(f"    semantic  => structural?  {(not eq_semantic(K1,K2)) or eq_struct(K1,K2)}   (FAILS -> inclusion is STRICT)")
print(f"    provenance => semantic ?  {(not eq_prov(K1,K2)) or eq_semantic(K1,K2)}   (holds: D is defined AS semantic + Pi)")
print("  => structural ⊊ semantic  WITNESSED.   D ⊆ semantic  BY DEFINITION.")
print("  => observational is NOT comparable without fixing the permitted-observation set.")

print()
print("="*78); print("E5 — WHAT DOES D285-5 ACTUALLY ESTABLISH?"); print("="*78)
print("""  NOT: "delta is non-injective."
  NOT: "delta is non-injective in o."
  ONLY, and precisely:

      There EXIST o1 != o2 and a state K0 such that
          delta(K0,o1) ==_semantic delta(K0,o2)
      under a semantic projection that DISCARDS Pi.

  That is: non-injectivity MODULO a chosen semantic quotient.
  It is NOT a property of delta. It is a property of delta COMPOSED WITH that quotient.
  Under the corpus's relation D (provenance-sensitive), the same delta IS injective on this witness.

  => D285-5 does not establish non-injectivity of delta.
     It establishes that SOME quotients collapse operationally-distinct results,
     and that the quotient must therefore be named. That is weaker and correct.""")

print()
print("="*78); print("E6 — IDENTITY BOUNDARY: is the grantId collision the same problem?"); print("="*78)
print("""  D285-5's property : two OPERATIONS, one semantic RESULT   -> a QUOTIENT choice
  grantId collision  : two AUTHORITY ACTS, one IDENTIFIER      -> an ID-ASSIGNMENT defect

  Different layers. The first is about an equivalence relation on states;
  the second is about a key collision on a different object.
  => D285-5 does NOT imply a general identity rule for authority acts.
     Citing it as motivation for typing humanActRef is an OVERREACH. (Correction to my own R5.)""")
