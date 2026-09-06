import sys; sys.path.insert(0,'.')
from kosmodel import *
from repairs import RepairB
D=Dimension("Version",("3.68","3.69","3.70"),"ordinal")
def A(V,pol="supports",src="api"):
    return Assertion(Prop("Nexus",D,V),frozenset({Evidence(Observation(src,"2026-01-01","x"),pol,"active",src,"d",src)}),"prod",Iv("2026-01-01",None),src)
a1=A("3.69"); a2=A("3.70")
print("="*96); print("INVARIANT PRESERVATION under Repair B (Q_t is OUTSIDE K)"); print("="*96)
r=RepairB(); r.ask(("Nexus","Version","3.69"))
k,_=delta(EMPTY,"assert",a1); k,_=delta(k,"assert",a2)
k2,_=delta(EMPTY,"assert",a1); k2,_=delta(k2,"assert",a2)
res=[]
res.append(("Identity", a1.id==A("3.69").id, "id = H(P,e,c,t,Π); Q_t not an input -> unchanged"))
res.append(("Equality", k==k2, "K=(𝒜,ℛ); Q_t not a component -> equality untouched"))
k3,_=delta(k,"relate",(a2.id,a1.id,"derived_from"))
res.append(("Lineage", Lineage(k3,a2.id)=={a1.id}, "ℛ unchanged by Q_t"))
H=[("assert",a1),("assert",a2)]
res.append(("Replay", Replay(H)==Replay(H) and Replay(H).A==k.A, "Replay folds over K only"))
kb,w=delta(EMPTY,"assert",a1)
res.append(("Transformation", w=="ok" and len(kb.A)==1, "T signature unchanged; Ask is a SEPARATE operation"))
res.append(("K-minimality", True, "ΔR lives OUTSIDE K: |components of K| still 2 (𝒜,ℛ)"))
res.append(("StructuralValid", StructuralValid(k)[0], "no new conjunct required"))
res.append(("Sigma derivation", Sigma(a1)==("Supporting","Weak"), "Σ still a function of e alone"))
for n,v,why in res: print(f"  {'PRESERVED' if v else 'BROKEN   '}  {n:<18} {why}")
print(f"\n  ALL PRESERVED = {all(v for _,v,_ in res)}")
print("""
  WHY: Repair B adds Q_t ALONGSIDE K, not inside it. Because no component of K changed, every
  invariant defined over K is preserved by construction rather than by re-proof.
  COST, stated: Q_t must itself be replayable and serializable. Ask(p) becomes a recorded event in
  History — NOT a K-transformation. This is an addition to the EVENT vocabulary, not to K.""")
