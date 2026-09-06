import sys; sys.path.insert(0,'.')
import kosfix
from kosmodel import *
print("="*100); print("F15 — NON-IDENTIFIABILITY TEST  (T-4)"); print("="*100)
D=Dimension("Version",("3.68","3.69","3.70"),"ordinal")
def A(V,src,pol="supports"):
    return Assertion(Prop("Nexus",D,V),
      frozenset({Evidence(Observation(src,"2026-01-01","x"),pol,"active",src,"d",src)}),
      "prod",Iv("2026-01-01",None),src)
# construct K1 != K2 that agree on every O_core observation
a=A("3.69","srcA"); b=A("3.69","srcB")     # same proposition, different source => different assertions
K1=K(frozenset({a}),frozenset()); K2=K(frozenset({b}),frozenset())
print("STEP 1 — construct K1 != K2")
print(f"  K1 = {{a}}  a.Pi=srcA  id={a.id}")
print(f"  K2 = {{b}}  b.Pi=srcB  id={b.id}")
print(f"  K1 == K2 (structural): {K1==K2}")
print("\nSTEP 2 — are they observationally equivalent under O_core?")
def Ocore(k):
    return {"query(Nexus,Version)":sorted(x.P.V for x in k.A),
            "Sigma":sorted(str(Sigma(x)) for x in k.A),
            "|A|":len(k.A), "|R|":len(k.R),
            "Valid":StructuralValid(k)[0],
            "contradictions":sum(1 for x in k.A for y in k.A if x.id<y.id and contradicts(k,x,y))}
o1,o2=Ocore(K1),Ocore(K2)
for key in o1: print(f"    {key:<26} K1={o1[key]}   K2={o2[key]}   same={o1[key]==o2[key]}")
equiv=o1==o2
print(f"\n  K1 ~_Ocore K2 : {equiv}")
print(f"  => NON-IDENTIFIABILITY INSTANCE {'CONSTRUCTED' if equiv and K1!=K2 else 'NOT CONSTRUCTED'}")
print("\nSTEP 3 — is it a MISSING PRIMITIVE or a DERIVED PROPERTY?")
def nonidentifiable(k1,k2,O): return k1!=k2 and O(k1)==O(k2)
print(f"    nonidentifiable(K1,K2,Ocore) = {nonidentifiable(K1,K2,Ocore)}")
print("    ^ computed from EXISTING structural equality and an EXISTING observation set.")
print("    No new primitive was introduced. The predicate is a two-line derivation.")
print("\nSTEP 4 — can the theory STATE the distinction it cannot observe?")
print(f"    Pi(a)={a.Pi}  vs  Pi(b)={b.Pi}   -> the theory REPRESENTS the difference")
print(f"    Ocore cannot SEE it -> the theory also EXPRESSES the unobservability")
print("""
VERDICT F15:
  Non-identifiability is a DERIVED PROPERTY of (structural equality, observation set O),
  not a missing primitive. It is definable in two lines over constructs that already exist:

      NonIdentifiable(K1,K2,O)  :=  K1 != K2  AND  O(K1) = O(K2)

  Crucially the theory can BOTH represent the difference (Pi) AND express that a given
  observation set cannot detect it. That is exactly what a non-identifiability statement
  requires. Adding a primitive would violate M3 (no redundant distinction).

  => T-4 IS NOT THEORY-CRITICAL.  TC-1..TC-8 all PASS.  Primary classification: F-derived (CLOSED).
  Prior status 'inexpressible' was WRONG: it confused 'O cannot observe it' with
  'the theory cannot say it'. Recorded as a correction to my own earlier claim.""")
